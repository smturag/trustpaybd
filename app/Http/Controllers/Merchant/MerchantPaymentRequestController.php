<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\BalanceManager;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Helpers\BalanceManagerConstant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\Rule;
use App\Models\Modem;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use Barryvdh\DomPDF\Facade\Pdf;

class MerchantPaymentRequestController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->get('sortby', 'id');
        $sort_type = $request->get('sorttype', 'desc');
        $rows = $request->get('rows', 10);

        $user = auth('merchant')->user();
        $query_data = $this->buildPaymentRequestQuery($request, $user);

        $data = $query_data->orderBy($sort_by, $sort_type)->paginate($rows);

        if ($request->ajax()) {
            return view('merchant.payment-request.data', compact('data'));
        }

        $data = [
            'data' => $data,
            'merchants' => Merchant::orderBy('fullname')->get(),
        ];

        //return $data['data'];

        return view('merchant.payment-request.request-list')->with($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function approved_payment_request(Request $request, $id)
    {
        $request_data = PaymentRequest::with(['merchant'])
            ->where('id', $id)
            ->first();

        return view('admin.merchant.payment-request.payment-request-approved', compact('request_data'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function reject_payment_request(Request $request, $id)
    {
        if ($request->ajax()) {
            PaymentRequest::where('id', $id)->update(['status' => 3]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function approve_payment_request(Request $request, $id)
    {
        if ($request->ajax()) {
            PaymentRequest::where('id', $id)->update(['status' => 2]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    // public function service_request_index(Request $request)
    // {




    //     $sort_by = $request->get('sortby');
    //     $sort_by = $sort_by ?: 'id';

    //     $sort_type = $request->get('sorttype');
    //     $sort_type = $sort_type ?: 'desc';

    //     $rows = $request->get('rows');
    //     $rows = $rows ?: '10';

    //     $mfs = $request->get('mfs');
    //     $trxid = $request->get('trxid');
    //     $cNumber = $request->get('cNumber');
    //     $start_date = $request->get('from');
    //     $end_date = $request->get('to');

    //     $user = auth('merchant')->user();

    //     if($user->merchant_type == 'sub_merchant'){

    //         $query_data = ServiceRequest::where('created_at', '!=', null)
    //         ->where('sub_merchant', $user->id)
    //         ->orderBy($sort_by, $sort_type);

    //     }else{
    //         $query_data = ServiceRequest::where('created_at', '!=', null)
    //         ->where('merchant_id', $user->id)
    //         ->orderBy($sort_by, $sort_type);

    //     }



    //     if (!empty($request->get('merchant_id'))) {
    //         $query_data->where('merchant_id', '=', $request->get('merchant_id'));
    //     }

    //     if (!empty($request->get('trxid'))) {
    //         $query_data->where('get_trxid', '=', $request->get('trxid'));
    //     }

    //     if (!empty($request->status)) {
    //         switch ($request->status) {
    //             case 'success':
    //                 $query_data->whereIn('status', [2, 3]);
    //                 break;
    //             case 'rejected':
    //                 $query_data->where('status', 4);
    //                 break;
    //             // case 'approved':
    //             //     $qrdata->where('status', 3);
    //             //     break;
    //             case 'waiting':
    //                 $query_data->where('status', 1);
    //                 break;
    //             case 'pending':
    //                 $query_data->where('status', 0);
    //                 break;
    //             case 'processing':
    //                 $query_data->where('status', 5);
    //                 break;
    //             case 'failed':
    //                 $query_data->where('status', 6);
    //                 break;
    //         }
    //     }

    //     if (!empty($cNumber)) {
    //         $query_data->where('number', $cNumber);
    //     }

    //     // if (!empty($trxid)) {
    //     //     $query_data->where('get_trxid', $trxid);
    //     // }

    //     if (!empty($mfs)) {
    //         $query_data->where('mfs', $mfs);
    //     }

    //     // if (!empty($request->get('reference'))) {
    //     //     // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
    //     //     //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
    //     //     //     ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
    //     //     $query_data->where('reference', '=', $request->get('reference'));
    //     // }

    //     // if (!empty($request->get('from')) && !empty($request->get('to'))) {
    //     //     $query_data->where('created_at', '>=', $request->get('from'));
    //     //     $query_data->where('created_at', '<=', $request->get('to'));
    //     // }


    //     if ($request->filled(['from', 'to'])) {
    //         $start_date =Carbon::parse($request->get('from'))->startOfDay();
    //         $end_date =Carbon::parse($request->get('to'))->endOfDay();

    //         $query_data->whereBetween('created_at', [$start_date, $end_date]);
    //         }

    //     $data = $query_data->paginate($rows);

    //     if ($request->ajax()) {
    //         return view('admin.merchant.service-request.data', compact('data'));
    //     }

    //     $data = [
    //         'data' => $data,
    //         'merchants' => Merchant::orderBy('fullname')->get(),
    //     ];

    //     //return $data['data'];

    //     return view('merchant.service-request.request-list')->with($data);
    // }


    public function service_request_index(Request $request)
    {
        $sort_by = $request->get('sortby', 'id');
        $sort_type = $request->get('sorttype', 'desc');
        $rows = $request->get('rows', 10);

        $user = auth('merchant')->user();
        $query_data = $this->buildServiceRequestQuery($request, $user);

        $data = $query_data->orderBy($sort_by, $sort_type)->paginate($rows);

        if ($request->ajax()) {
            // ✅ IMPORTANT: match your Blade path
            return view('merchant.service-request.data', compact('data'))->render();
        }

        $merchants = Merchant::orderBy('fullname')->get();

        return view('merchant.service-request.request-list', compact('data', 'merchants'));
    }

    public function exportPaymentRequests(Request $request)
    {
        $format = strtolower($request->get('format', 'csv'));
        $user = auth('merchant')->user();
        $sort_by = $request->get('sortby', 'id');
        $sort_type = $request->get('sorttype', 'desc');

        $query_data = $this->buildPaymentRequestQuery($request, $user);
        $data = $query_data->orderBy($sort_by, $sort_type)->get();

        $bmMobiles = DB::table('balance_managers')
            ->whereIn('trxid', $data->pluck('payment_method_trx')->filter()->all())
            ->pluck('mobile', 'trxid');

        $filename = 'payment-requests-' . now()->format('Ymd-His');

        if ($format === 'pdf') {
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return redirect()->back()->with('alert', 'PDF export is not available. Please install barryvdh/laravel-dompdf.');
            }

            $pdf = Pdf::loadView('merchant.exports.payment_requests', [
                'data' => $data,
                'user' => $user,
                'bmMobiles' => $bmMobiles,
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        if ($format === 'excel') {
            return response()
                ->view('merchant.exports.payment_requests', [
                    'data' => $data,
                    'user' => $user,
                    'bmMobiles' => $bmMobiles,
                ])
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        }

        $headers = [
            'From',
            'Method',
            'Payment Type',
            'Amount',
            'Fee',
            'Commission',
            'New Amount',
            'Last Balance',
            'New Balance',
            'TrxId',
            'Reference',
            'Note',
            'Created At',
            'Status',
        ];

        return response()->streamDownload(function () use ($data, $user, $headers, $bmMobiles) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            $isGeneral = $user->merchant_type === 'general';
            foreach ($data as $row) {
                $fee = $isGeneral ? $row->merchant_fee : $row->sub_merchant_fee;
                $commission = $isGeneral ? $row->merchant_commission : $row->sub_merchant_commission;
                $netAmount = $isGeneral ? $row->merchant_main_amount : $row->sub_merchant_main_amount;

                fputcsv($handle, [
                    $bmMobiles[$row->payment_method_trx] ?? '',
                    $row->payment_method,
                    $row->payment_type,
                    $row->amount,
                    $fee,
                    $commission,
                    $netAmount,
                    $row->merchant_last_balance !== null ? number_format($row->merchant_last_balance, 2) : '-',
                    $row->merchant_new_balance !== null ? number_format($row->merchant_new_balance, 2) : '-',
                    $row->payment_method_trx,
                    $row->reference,
                    $row->reject_msg,
                    optional($row->created_at)->format('Y-m-d H:i:s'),
                    $this->paymentRequestStatusLabel($row->status),
                ]);
            }
            fclose($handle);
        }, $filename . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportServiceRequests(Request $request)
    {
        $format = strtolower($request->get('format', 'csv'));
        $user = auth('merchant')->user();
        $sort_by = $request->get('sortby', 'id');
        $sort_type = $request->get('sorttype', 'desc');

        $query_data = $this->buildServiceRequestQuery($request, $user);
        $data = $query_data->orderBy($sort_by, $sort_type)->get();

        $filename = 'service-requests-' . now()->format('Ymd-His');

        if ($format === 'pdf') {
            if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
                return redirect()->back()->with('alert', 'PDF export is not available. Please install barryvdh/laravel-dompdf.');
            }

            $pdf = Pdf::loadView('merchant.exports.service_requests', [
                'data' => $data,
                'user' => $user,
            ])->setPaper('a4', 'landscape');

            return $pdf->download($filename . '.pdf');
        }

        if ($format === 'excel') {
            return response()
                ->view('merchant.exports.service_requests', [
                    'data' => $data,
                    'user' => $user,
                ])
                ->header('Content-Type', 'application/vnd.ms-excel')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '.xls"');
        }

        $headers = [
            'Number',
            'MFS',
            'SIM Number',
            'Old Balance',
            'Amount',
            'Fee',
            'Commission',
            'Main Balance',
            'New Balance',
            'TrxId',
            'Created At',
            'Status',
        ];

        return response()->streamDownload(function () use ($data, $user, $headers) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, $headers);

            $isGeneral = $user->merchant_type === 'general';
            foreach ($data as $row) {
                $fee = $isGeneral ? $row->merchant_fee : $row->sub_merchant_fee;
                $commission = $isGeneral ? $row->merchant_commission : $row->sub_merchant_commission;
                $netAmount = $isGeneral ? $row->merchant_main_amount : $row->sub_merchant_main_amount;

                fputcsv($handle, [
                    $row->number,
                    $row->mfs,
                    $row->sim_number,
                    $row->old_balance,
                    $row->amount,
                    $fee,
                    $commission,
                    $netAmount,
                    $row->new_balance,
                    $row->get_trxid,
                    optional($row->created_at)->format('Y-m-d H:i:s'),
                    $this->serviceRequestStatusLabel($row->status),
                ]);
            }
            fclose($handle);
        }, $filename . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }



    public function createNewDeposit()
    {

        // dd(mfsList());

        // $staticList = listOfIbotOp();
        // $dynamicList = $this->fetchMfsApi();

        // $staticMap = collect($staticList)->keyBy(fn ($item) => strtolower($item['deposit_method']));
        // $dynamicMap = collect($dynamicList)->keyBy(fn ($item) => strtolower($item['type']));

        // $allMethods = collect(array_merge(
        //     $staticMap->keys()->toArray(),
        //     $dynamicMap->keys()->toArray()
        // ))->unique();

        // $finalList = $allMethods->map(function ($method) use ($staticMap, $dynamicMap) {
        //     $useStatic = rand(0, 1) === 1;

        //     if ($useStatic && $staticMap->has($method)) {
        //         return $staticMap->get($method);
        //     } elseif ($dynamicMap->has($method)) {
        //         return [
        //             'deposit_method' => strtolower($dynamicMap[$method]['type']),
        //             'deposit_number' => $dynamicMap[$method]['phone'],
        //             'icon' => "https://ibotbd.com/payments/" . strtolower($dynamicMap[$method]['type']) . ".png"
        //         ];
        //     } elseif ($staticMap->has($method)) {
        //         return $staticMap->get($method);
        //     }

        //     return null;
        // })->filter()->values();

        return view('merchant.payment-request.create_payment_request', [
            'mfsList' => mfsList()
        ]);
    }


    public function depositRequestStore(Request $request)
    {




        // ✅ Basic Validation
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required',
            'deposit_number' => 'required',
            'from_number' => 'nullable',
            'transaction_id' => [
                'required',
                'string',
                'min:8',
                'max:10',
                'regex:/^[a-zA-Z0-9]+$/',
                function ($attribute, $value, $fail) {
                    $exists = PaymentRequest::where('payment_method_trx', $value)
                        ->whereIn('status', [0, 1, 2])
                        ->exists();
                    if ($exists) {
                        $fail('The transaction ID is already used & it is under working. | ট্রানজ্যাকশন আইডিটি ইতিমধ্যে ব্যবহৃত হয়েছে অথবা এখনও প্রক্রিয়াধীন।');
                    }
                },
            ],
        ]);

        // ✅ Find Agent by SIM Number
        $agent = Modem::where('sim_number', $request->deposit_number)->first();
        if (!$agent) {
            return redirect()->back()->with('alert', 'Invalid agent ID or unavailable agent.');
        }

        $user = auth('merchant')->user();


        $merchantRate = calculateAmountFromRate($request->payment_method, $request->account_type, 'deposit', auth('merchant')->user()->id, $request->amount);

        $currentAgent = User::where('member_code', $agent->member_code)->where('user_type', 'agent')->first();

        $memberRate = calculateAmountFromRateForMember($request->payment_method, $request->account_type, 'deposit', $currentAgent->id, $request->amount);


        // ✅ Get merchant's current balance
        $mainMerchantId = $user->merchant_type == 'sub_merchant' ? $user->create_by : $user->id;
        $mainMerchant = Merchant::find($mainMerchantId);
        $mainMerchantBalance = $mainMerchant ? floatval($mainMerchant->balance) : 0;

        // ✅ Get sub-merchant balance if applicable
        $subMerchantBalance = null;
        if ($user->merchant_type == 'sub_merchant') {
            $subMerchant = Merchant::find($user->id);
            $subMerchantBalance = $subMerchant ? floatval($subMerchant->balance) : 0;
        }

        // ✅ Generate base data array
        $data = [
            'agent' => $agent->member_code,
            'partner' => optional(getPartnerFromAgent($agent->member_code))->member_code,
            'from_number' => $request->from_number ?? null,
            'modem_id' => $agent->id,
            'request_id' => generatePaymentRequestTrx(25),
            'payment_method_trx' => $request->transaction_id,
            'sim_id' => $request->deposit_number,
            'trxid' => generatePaymentRequestTrx(6),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'merchant_id' => $user->merchant_type == 'sub_merchant' ? $user->create_by : $user->id,
            'sub_merchant' => $user->merchant_type == 'sub_merchant' ? $user->id : null,
            'reference' => substr(Str::uuid()->toString(), 0, 20), // Unique reference
            'currency' => 'BDT',
            'callback_url' => '/', // update if needed
            'cust_name' => $request->cust_name ?? null,
            'cust_phone' => $request->cust_phone ?? null,
            'cust_address' => $request->cust_address ?? null,
            'issue_time' => Carbon::now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accepted_by' => null,
            'payment_type' => $request->account_type,
            'merchant_fee' => $merchantRate['general']['fee_amount'],
            'merchant_commission' => $merchantRate['general']['commission_amount'],
            'sub_merchant_fee' => $merchantRate['sub_merchant']['fee_amount'],
            'sub_merchant_commission' => $merchantRate['sub_merchant']['commission_amount'],
            'merchant_main_amount' => $merchantRate['general']['net_amount'],
            'sub_merchant_main_amount' => $merchantRate['sub_merchant']['net_amount'],
            'merchant_last_balance' => $mainMerchantBalance,
            'merchant_new_balance' => $mainMerchantBalance, // Will be updated on approval
            'sub_merchant_last_balance' => $subMerchantBalance,
            'sub_merchant_new_balance' => $subMerchantBalance, // Will be updated on approval
            'partner_fee' => $memberRate['member']['fee_amount'],
            'partner_commission' => $memberRate['member']['commission_amount'],
            'user_fee' => $memberRate['agent']['fee_amount'],
            'user_commission' => $memberRate['agent']['commission_amount'],
            'partner_main_amount' => $memberRate['member']['net_amount'],
            'user_main_amount' => $memberRate['agent']['net_amount'],
        ];

        // ✅ Store to database
        PaymentRequest::create($data);

        // ✅ Redirect back with success message
        return redirect()->back()->with('message', 'Deposit request submitted successfully.');
    }


    private function fetchMfsApi()
    {
        try {
            $response = Http::withToken(BalanceManagerConstant::token_key)
                ->get(BalanceManagerConstant::URL . '/api/available-methods');

            if ($response->successful()) {
                $data = $response->json();

                $grouped = collect($data)->groupBy('type');
                $result = [];

                foreach ($grouped as $type => $items) {
                    $previousPhone = Cache::get("last_used_phone_{$type}");

                    // If there's more than 1 item, avoid reusing the same phone
                    if ($items->count() > 1 && $previousPhone) {
                        $filtered = $items->filter(function ($item) use ($previousPhone) {
                            return $item['phone'] !== $previousPhone;
                        });

                        // If all items were filtered out, fall back to original list
                        $finalItems = $filtered->isNotEmpty() ? $filtered : $items;
                    } else {
                        $finalItems = $items;
                    }

                    // Pick one random item
                    $selected = $finalItems->random();

                    // Cache the current phone number
                    Cache::put("last_used_phone_{$type}", $selected['phone'], now()->addMinutes(10));

                    $result[] = $selected;
                }

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch MFS API: ' . $e->getMessage());
        }

        return [];
    }

    private function buildPaymentRequestQuery(Request $request, $user)
    {
        $query_data = PaymentRequest::with(['agent'])

            ->whereNotNull('created_at');

        if ($user->merchant_type == 'sub_merchant') {
            $query_data->where('sub_merchant', $user->id);
        } else {
            $query_data->where('merchant_id', $user->id);
        }

        if ($request->filled('merchant_id')) {
            $query_data->where('merchant_id', $request->get('merchant_id'));
        }

        if ($request->filled('trxid')) {
            $query_data->where('payment_method_trx', $request->get('trxid'));
        }

        if ($request->filled('cust_name')) {
            $cust_name = $request->get('cust_name');
            $query_data->where(function ($query) use ($cust_name) {
                $query->where('cust_name', $cust_name)->orWhere('cust_phone', $cust_name);
            });
        }

        if ($request->filled('reference')) {
            $query_data->where('reference', $request->get('reference'));
        }

        if ($request->filled('payment_type')) {
            $query_data->where('payment_type', $request->get('payment_type'));
        }

        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status == 1) {
                $query_data->whereIn('status', [1, 2]);
            } elseif ($status === 'pending') {
                $query_data->where('status', 0);
            } else {
                $query_data->where('status', $status);
            }
        }

        if ($request->filled(['start_date', 'end_date'])) {
            $query_data->where('created_at', '>=', $request->get('start_date'));
            $query_data->where('created_at', '<=', $request->get('end_date'));
        }

        return $query_data;
    }

    private function buildServiceRequestQuery(Request $request, $user)
    {
        $query_data = ServiceRequest::query();

        if ($user->merchant_type == 'sub_merchant') {
            $query_data->where('sub_merchant', $user->id);
        } else {
            $query_data->where('merchant_id', $user->id);
        }

        if ($request->filled('merchant_id')) {
            $query_data->where('merchant_id', $request->merchant_id);
        }

        if ($request->filled('trxid')) {
            $query_data->where('get_trxid', $request->trxid);
        }

        if ($request->filled('status')) {
            switch ($request->status) {
                case 'success':
                    $query_data->whereIn('status', [2, 3]);
                    break;
                case 'rejected':
                    $query_data->where('status', 4);
                    break;
                case 'waiting':
                    $query_data->where('status', 1);
                    break;
                case 'pending':
                    $query_data->where('status', 0);
                    break;
                case 'processing':
                    $query_data->where('status', 5);
                    break;
                case 'failed':
                    $query_data->where('status', 6);
                    break;
            }
        }

        if ($request->filled('cNumber')) {
            $query_data->where('number', $request->cNumber);
        }

        if ($request->filled('mfs')) {
            $query_data->where('mfs', $request->mfs);
        }

        if ($request->filled(['from', 'to'])) {
            $start_date = \Carbon\Carbon::parse($request->from)->startOfDay();
            $end_date = \Carbon\Carbon::parse($request->to)->endOfDay();
            $query_data->whereBetween('created_at', [$start_date, $end_date]);
        }

        return $query_data;
    }

    private function paymentRequestStatusLabel($status)
    {
        if ($status == 0) {
            return 'Pending';
        }
        if ($status == 1) {
            return 'Success';
        }
        if ($status == 2) {
            return 'Approved';
        }
        if ($status == 3) {
            return 'Rejected';
        }
        return 'Unknown';
    }

    private function serviceRequestStatusLabel($status)
    {
        if ($status == 2) {
            return 'Success';
        }
        if ($status == 1) {
            return 'Waiting';
        }
        if ($status == 4) {
            return 'Rejected';
        }
        if ($status == 3) {
            return 'Approved';
        }
        if ($status == 5) {
            return 'Processing';
        }
        if ($status == 6) {
            return 'Failed';
        }
        return 'Pending';
    }


}
