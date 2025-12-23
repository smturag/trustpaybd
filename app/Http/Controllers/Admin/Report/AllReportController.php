<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceRequest;

class AllReportController extends Controller
{
    public function PaymentReport(Request $request)
    {

        $rows = $request->input('rows', 10);
        $profileType = $request->input('profileType');
        $selectMerchant = $request->input('selectMerchant');
        $selectPartner = $request->input('selectPartner');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $payment_type = $request->input('payment_type');

        // Build the query based on filters
        $query = Transaction::where('wallet_type', 'admin');

        if ($profileType) {
            $query->where('user_type', $profileType);
        }

        if ($selectMerchant) {
            $query->where('user_id', $selectMerchant)->where('user_type','merchant')->orWhere('user_type','sub_merchant');
        }

        if ($selectPartner) {
            $query->where('user_id', $selectPartner)->where('user_type','partner');
        }

        if ($start_date &&  $end_date) {
            Log::info(1);
            $query->whereDate('created_at', '>=', $start_date);
            $query->whereDate('created_at', '<=', $end_date);
        }

        if ($payment_type) {
            $query->where('trx_type', $payment_type);
        }

        // Paginate the results
        $data = $query->paginate($rows);

        if ($request->ajax()) {
            return view('admin.reports.payment_report.table', compact('data'));
        }

        return view('admin.reports.payment_report.index', compact('data'));
    }

public function ServiceReport(Request $request, $service ='deposit')
{
    $merchantId = $request->input('selectMerchant');
    $method     = $request->input('method');
    $startDate  = $request->input('start_date');
    $endDate    = $request->input('end_date');
    $startTime  = $request->input('start_time');
    $endTime    = $request->input('end_time');
    $subMerchantId = $request->input('selectSubMerchant');

    $results = collect(); // ← avoid error on empty

    if ($service == 'deposit') {
        $query = PaymentRequest::whereIn('status', [1, 2]);

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

         if ($subMerchantId) {
            $query->where('sub_merchant', $subMerchantId);
        }

        if ($startDate || $endDate) {
            $startDateTime = $startDate ? $startDate : now()->toDateString();
            $endDateTime   = $endDate   ? $endDate   : now()->toDateString();

            $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
            $endDateTime   .= $endTime   ? ' ' . $endTime   : ' 23:59:59';

            $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
        }

        if (!$method || $method === 'All Method') {
            $results = $query
                ->select('payment_method', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderBy('payment_method')
                ->get();
        } else {
            $results = $query
                ->where('payment_method', $method)
                ->select('payment_method', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderBy('payment_method')
                ->get();
        }

        $methods = PaymentRequest::select('payment_method')->distinct()->pluck('payment_method');

    } elseif ($service == 'withdraw') {
        $query = ServiceRequest::whereIn('status', [3, 2]);

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

        if ($subMerchantId) {
            $query->where('sub_merchant', $subMerchantId);
        }

        if ($startDate || $endDate) {
            $startDateTime = $startDate ? $startDate : now()->toDateString();
            $endDateTime   = $endDate   ? $endDate   : now()->toDateString();

            $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
            $endDateTime   .= $endTime   ? ' ' . $endTime   : ' 23:59:59';

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
    } else {
        // Optional: redirect or 404 if no valid service
        $methods = collect();
    }

    return view('admin.reports.service_report.index', [
        'service' => $service,
        'methods' => $methods,
        'results' => $results->toArray()
    ]);
}


}
