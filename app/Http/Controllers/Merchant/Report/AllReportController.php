<?php

namespace App\Http\Controllers\Merchant\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceRequest;
use App\Models\MerchantPayoutRequest;
use App\Models\Merchant;
use Illuminate\Support\Facades\Auth;

class AllReportController extends Controller
{
    // public function PaymentReport(Request $request)
    // {

    //     $rows = $request->input('rows', 10);
    //     $profileType = $request->input('profileType');
    //     $selectMerchant = $request->input('selectMerchant');
    //     $selectPartner = $request->input('selectPartner');
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');
    //     $payment_type = $request->input('payment_type');

    //     // Build the query based on filters
    //     $query = Transaction::where('wallet_type', 'admin');

    //     if ($profileType) {
    //         $query->where('user_type', $profileType);
    //     }

    //     if ($selectMerchant) {
    //         $query->where('user_id', $selectMerchant)->where('user_type','merchant');
    //     }

    //     if ($selectPartner) {
    //         $query->where('user_id', $selectPartner)->where('user_type','partner');
    //     }

    //     if ($start_date &&  $end_date) {
    //         Log::info(1);
    //         $query->whereDate('created_at', '>=', $start_date);
    //         $query->whereDate('created_at', '<=', $end_date);
    //     }

    //     if ($payment_type) {
    //         $query->where('trx_type', $payment_type);
    //     }

    //     // Paginate the results
    //     $data = $query->paginate($rows);

    //     if ($request->ajax()) {
    //         return view('admin.reports.payment_report.table', compact('data'));
    //     }

    //     return view('admin.reports.payment_report.index', compact('data'));
    // }

    public function ServiceReport(Request $request, $service = 'deposit')
    {
        $merchantId = Auth::guard('merchant')->user()->id;
        $method = $request->input('method');
        $paymentType = $request->input('payment_type');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time');
        $endTime = $request->input('end_time');

        $results = collect(); // ← avoid error on empty

        if ($service == 'deposit') {
            $query = PaymentRequest::whereIn('status', [1, 2]);

            if ($merchantId) {
                $query->where('merchant_id', $merchantId);
            }

            if ($paymentType && $paymentType !== 'All') {
                $query->where('payment_type', $paymentType);
            }

            if ($startDate || $endDate) {
                $startDateTime = $startDate ? $startDate : now()->toDateString();
                $endDateTime = $endDate ? $endDate : now()->toDateString();

                $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
                $endDateTime .= $endTime ? ' ' . $endTime : ' 23:59:59';

                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }

            if (!$method || $method === 'All Method') {
                $results = $query
                    ->select(
                        'payment_method',
                        \DB::raw('SUM(amount) as total_amount'),
                        \DB::raw('SUM(merchant_fee) as total_fee'),
                        \DB::raw('SUM(merchant_commission) as total_commission')
                    )
                    ->groupBy('payment_method')
                    ->orderBy('payment_method')
                    ->get();
            } else {
                $results = $query
                    ->where('payment_method', $method)
                    ->select(
                        'payment_method',
                        \DB::raw('SUM(amount) as total_amount'),
                        \DB::raw('SUM(merchant_fee) as total_fee'),
                        \DB::raw('SUM(merchant_commission) as total_commission')
                    )
                    ->groupBy('payment_method')
                    ->orderBy('payment_method')
                    ->get();
            }

            $methods = PaymentRequest::select('payment_method')->distinct()->pluck('payment_method');

            // Get first and last transaction dates for display
            $dateRange = PaymentRequest::whereIn('status', [1, 2])
                ->where('merchant_id', $merchantId)
                ->selectRaw('MIN(created_at) as first_date, MAX(created_at) as last_date')
                ->first();

        } elseif ($service == 'withdraw') {
            $query = ServiceRequest::whereIn('status', [3, 2]);

            if ($merchantId) {
                $query->where('merchant_id', $merchantId);
            }

            if ($startDate || $endDate) {
                $startDateTime = $startDate ? $startDate : now()->toDateString();
                $endDateTime = $endDate ? $endDate : now()->toDateString();

                $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
                $endDateTime .= $endTime ? ' ' . $endTime : ' 23:59:59';

                $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
            }

            if (!$method || $method === 'All Method') {
                $results = $query
                    ->select('mfs', \DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('mfs')
                    ->orderBy('mfs')
                    ->get();
            } else {
                $results = $query
                    ->where('mfs', $method)
                    ->select('mfs', \DB::raw('SUM(amount) as total_amount'))
                    ->groupBy('mfs')
                    ->orderBy('mfs')
                    ->get();
            }

            $methods = ServiceRequest::select('mfs')->distinct()->pluck('mfs');

            // Get first and last transaction dates for display
            $dateRange = ServiceRequest::whereIn('status', [3, 2])
                ->where('merchant_id', $merchantId)
                ->selectRaw('MIN(created_at) as first_date, MAX(created_at) as last_date')
                ->first();

        } else {
            // Optional: redirect or 404 if no valid service
            $methods = collect();
            $dateRange = null;
        }

        return view('merchant.reports.service_report.index', [
            'service' => $service,
            'methods' => $methods,
            'results' => $results->toArray(),
            'dateRange' => $dateRange
        ]);
    }

    /**
     * Balance Summary Report - Shows comprehensive balance overview
     */
    public function BalanceSummary(Request $request)
    {
        $merchantId = Auth::guard('merchant')->user()->id;
        $merchant = Merchant::findOrFail($merchantId);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $startTime = $request->input('start_time', '00:00:00');
        $endTime = $request->input('end_time', '23:59:59');

        // Build date range for queries
        $dateFilter = function ($query) use ($startDate, $endDate, $startTime, $endTime) {
            if ($startDate || $endDate) {
                $start = ($startDate ?: now()->toDateString()) . ' ' . $startTime;
                $end = ($endDate ?: now()->toDateString()) . ' ' . $endTime;
                $query->whereBetween('created_at', [$start, $end]);
            }
        };

        // 1. Deposit Summary (Approved deposits)
        $depositQuery = PaymentRequest::where('merchant_id', $merchantId)
            ->whereIn('status', [1, 2]);
        $dateFilter($depositQuery);

        $depositStats = $depositQuery
            ->selectRaw('COUNT(*) as total_count,
                     SUM(amount) as total_amount,
                     SUM(merchant_fee) as total_fee,
                     SUM(merchant_commission) as total_commission')
            ->first();

        // 2. Withdraw Summary (Approved withdraws)
        $withdrawQuery = ServiceRequest::where('merchant_id', $merchantId)
            ->whereIn('status', [2, 3]);
        $dateFilter($withdrawQuery);

        $withdrawStats = $withdrawQuery
            ->selectRaw('COUNT(*) as total_count,
                     SUM(amount) as total_amount')
            ->first();

        // Add total_fee as 0 since service_requests table doesn't have fee column
        $withdrawStats->total_fee = 0;

        // 3. Payout Summary (All payout statuses)
        $payoutQuery = MerchantPayoutRequest::where('merchant_id', $merchantId);
        $dateFilter($payoutQuery);

        $payoutStats = $payoutQuery
            ->selectRaw('COUNT(*) as total_count,
                     SUM(amount) as total_amount,
                     SUM(fee) as total_fee,
                     SUM(CASE WHEN status = 0 THEN amount ELSE 0 END) as pending_amount,
                     SUM(CASE WHEN status = 4 THEN amount ELSE 0 END) as approved_amount,
                     SUM(CASE WHEN status = 3 THEN amount ELSE 0 END) as rejected_amount')
            ->first();

        // 4. Calculate net amounts
        $depositNet = ($depositStats->total_amount ?? 0) - ($depositStats->total_fee ?? 0) + ($depositStats->total_commission ?? 0);
        $withdrawNet = ($withdrawStats->total_amount ?? 0); // No fees for withdrawals
        $payoutNet = ($payoutStats->approved_amount ?? 0) + ($payoutStats->total_fee ?? 0);

        // 5. Calculate expected balance
        $expectedBalance = $depositNet - $withdrawNet - $payoutNet;

        // 6. Get payment method breakdown for deposits
        $depositByMethod = PaymentRequest::where('merchant_id', $merchantId)
            ->whereIn('status', [1, 2])
            ->when($startDate || $endDate, function ($q) use ($dateFilter) {
                $dateFilter($q);
            })
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get();

        // 7. Get withdraw method breakdown
        $withdrawByMethod = ServiceRequest::where('merchant_id', $merchantId)
            ->whereIn('status', [2, 3])
            ->when($startDate || $endDate, function ($q) use ($dateFilter) {
                $dateFilter($q);
            })
            ->selectRaw('mfs as payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('mfs')
            ->get();

        // 8. Get payout currency breakdown
        $payoutByCurrency = MerchantPayoutRequest::where('merchant_id', $merchantId)
            ->when($startDate || $endDate, function ($q) use ($dateFilter) {
                $dateFilter($q);
            })
            ->selectRaw('merchant_currency, 
                     COUNT(*) as count, 
                     SUM(amount) as total_bdt,
                     SUM(merchant_amount) as total_currency,
                     status')
            ->groupBy('merchant_currency', 'status')
            ->get();
        // 9. Build Statement List and 7-Day History (when date filter is applied)
        $statementList = collect();
        $openingBalance = 0;
        $balanceHistory = collect();

        if ($startDate) {
            $startDateTime = $startDate . ' ' . $startTime;
            $endDateTime = ($endDate ?: now()->toDateString()) . ' ' . $endTime;

            // Helper function to calculate balance at a specific date
            $calculateBalanceAt = function ($merchantId, $dateTime) {
                $deposits = PaymentRequest::where('merchant_id', $merchantId)
                    ->whereIn('status', [1, 2])
                    ->where('created_at', '<', $dateTime)
                    ->selectRaw('COALESCE(SUM(amount - merchant_fee + merchant_commission), 0) as net')
                    ->first()->net ?? 0;

                $withdraws = ServiceRequest::where('merchant_id', $merchantId)
                    ->whereIn('status', [2, 3])
                    ->where('created_at', '<', $dateTime)
                    ->sum('amount') ?? 0;

                $payouts = MerchantPayoutRequest::where('merchant_id', $merchantId)
                    ->where('status', 4)
                    ->where('created_at', '<', $dateTime)
                    ->selectRaw('COALESCE(SUM(amount + fee), 0) as total')
                    ->first()->total ?? 0;

                return $deposits - $withdraws - $payouts;
            };

            // Calculate Opening Balance (balance before startDate)
            $openingBalance = $calculateBalanceAt($merchantId, $startDateTime);

            // Build 7-Day Balance History
            for ($i = 7; $i >= 1; $i--) {
                $dayDate = \Carbon\Carbon::parse($startDate)->subDays($i)->format('Y-m-d');
                $dayStart = $dayDate . ' 00:00:00';
                $dayEnd = $dayDate . ' 23:59:59';

                $dayOpeningBalance = $calculateBalanceAt($merchantId, $dayStart);
                $dayClosingBalance = $calculateBalanceAt($merchantId, $dayEnd . ':59'); // End of day

                $balanceHistory->push([
                    'date' => $dayDate,
                    'opening_balance' => $dayOpeningBalance,
                    'closing_balance' => $calculateBalanceAt($merchantId, \Carbon\Carbon::parse($dayDate)->addDay()->format('Y-m-d') . ' 00:00:00'),
                ]);
            }

            // Fetch all transactions in the date range
            $deposits = PaymentRequest::where('merchant_id', $merchantId)
                ->whereIn('status', [1, 2])
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->get()
                ->map(function ($item) {
                    $net = $item->amount - $item->merchant_fee + $item->merchant_commission;
                    return [
                        'date' => $item->created_at,
                        'type' => 'Deposit',
                        'description' => $item->payment_method . ' - ' . ($item->payment_method_trx ?? $item->reference),
                        'credit' => $net,
                        'debit' => 0,
                    ];
                });

            $withdraws = ServiceRequest::where('merchant_id', $merchantId)
                ->whereIn('status', [2, 3])
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->get()
                ->map(function ($item) {
                    return [
                        'date' => $item->created_at,
                        'type' => 'Withdraw',
                        'description' => $item->mfs . ' - ' . $item->number,
                        'credit' => 0,
                        'debit' => $item->amount,
                    ];
                });

            $payouts = MerchantPayoutRequest::where('merchant_id', $merchantId)
                ->where('status', 4)
                ->whereBetween('created_at', [$startDateTime, $endDateTime])
                ->get()
                ->map(function ($item) {
                    $total = $item->amount + $item->fee;
                    return [
                        'date' => $item->created_at,
                        'type' => 'Payout',
                        'description' => strtoupper($item->merchant_currency) . ' - ' . ($item->wallet_address ?? 'Crypto'),
                        'credit' => 0,
                        'debit' => $total,
                    ];
                });

            // Merge and sort by date
            $allTransactions = $deposits->concat($withdraws)->concat($payouts)->sortBy('date')->values();

            // Calculate running balance
            $runningBalance = $openingBalance;
            $statementList = $allTransactions->map(function ($item) use (&$runningBalance) {
                $runningBalance = $runningBalance + $item['credit'] - $item['debit'];
                $item['balance'] = $runningBalance;
                return $item;
            });
        }

        return view('merchant.reports.balance_summary.index', compact(
            'merchant',
            'depositStats',
            'withdrawStats',
            'payoutStats',
            'depositNet',
            'withdrawNet',
            'payoutNet',
            'expectedBalance',
            'depositByMethod',
            'withdrawByMethod',
            'payoutByCurrency',
            'startDate',
            'endDate',
            'statementList',
            'openingBalance',
            'balanceHistory'
        ));
    }

}