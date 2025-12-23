<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MerchantPaymentRequestController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows          = $request->get('rows');
        $rows          = $rows ?: '50';
        $status        = $request->get('status');
        $cust_name     = $request->get('cust_name');
        $mfs           = $request->get('mfs');
        $method_number = $request->get('method_number');

        $query_data = PaymentRequest::with(['agent'])->where('created_at', '!=', null);

        if (! empty($request->get('merchant_id'))) {

            $checkMerchant = Merchant::find($request->get('merchant_id'));
            if ($checkMerchant->merchant_type == 'general') {
                $query_data->where('merchant_id', '=', $request->get('merchant_id'));
            } elseif ($checkMerchant->merchant_type == 'sub_merchant') {
                $query_data->where('sub_merchant', '=', $request->get('merchant_id'));
            }

        }

        if (! empty($mfs)) {
            $query_data->where('payment_method', $mfs);
        }

        if (! empty($method_number)) {
            $query_data->where('sim_id', $method_number);
        }

        if (! empty($request->get('trxid'))) {
            $query_data->where('payment_method_trx', '=', $request->get('trxid'));
        }

        if ($cust_name) {
            $query_data->where(function ($query) use ($cust_name) {
                $query->where('cust_name', $cust_name)
                    ->orWhere('cust_phone', $cust_name);
            });
        }

        if ($status) {
            if ($status == 1) {
                $query_data->whereIn('status', [1, 2]);
            } elseif ($status == 'pending') {
                $query_data->where('status', 0);
            } else {
                $query_data->where('status', $status);
            }
        }

        if (! empty($request->get('reference'))) {
            // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
            //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
            //     ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
            $query_data->where('reference', '=', $request->get('reference'));
        }

        // if (!empty($request->get('start_date')) && !empty($request->get('end_date'))) {
        //     $query_data->where('created_at', '>=', $request->get('start_date'));
        //     $query_data->where('created_at', '<=', $request->get('end_date'));
        // }

        if (! empty($request->get('start_date')) && ! empty($request->get('end_date'))) {
            $query_data->whereDate('created_at', '>=', $request->get('start_date'))
                ->whereDate('created_at', '<=', $request->get('end_date'));
        }

        $query_data->orderBy($sort_by, $sort_type)->whereNotNull('payment_method');
        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('admin.merchant.payment-request.data', compact('data'));
        }

        $data = [
            'data'      => $data,
            'merchants' => Merchant::orderBy('fullname')->get(),

        ];

        return view('admin.merchant.payment-request.request-list')->with($data);
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

        merchantWebHook($request_data->reference);

        return view('admin.merchant.payment-request.payment-request-approved', compact('request_data'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function reject_payment_request(Request $request)
    {
        $payment = PaymentRequest::where('id', $request->transId)->first();

        if ($payment) {
            // আপডেট করা
            $payment->update([
                'status'     => 3,
                'reject_msg' => $request->reason,
            ]);

            // webhook কল করা
            merchantWebHook($payment->reference);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Status Changed',
            ]);
        } else {
            return response()->json([
                'status'  => 404,
                'message' => 'Transaction not found',
            ]);
        }
    }

    public function approve_payment_request(Request $request, $id)
    {

        try {

            if ($request->ajax()) {
                $payment_request = PaymentRequest::find($id);

                //$payment_request->update(['status' => 2]);
                // PaymentRequest::where('id', $id)->update(['status' => 2]);

                DB::beginTransaction();

                DB::table('payment_requests')
                    ->where('id', $id)
                    ->update(['status' => 2, 'updated_at' => Carbon::now(), 'accepted_by' => 'Admin']);

                merchantWebHook($payment_request->reference);

                $agentBalance = findAgentBalance($payment_request->agent);

                DB::table('transactions')->insert([
                    'user_id'     => $payment_request->agent,
                    'amount'      => $payment_request->amount,
                    'charge'      => 0,
                    'old_balance' => $agentBalance['mainBalance'],
                    'trx_type'    => 'credit',
                    'trx'         => $payment_request->amount,
                    'details'     => 'Customer api payment using ' . $payment_request->payment_method,
                    'user_type'   => 'agent',
                    'wallet_type' => 'main',
                    'updated_at'  => Carbon::now(),

                ]);

                $success = Customer::where('id', $payment_request->customer_id)
                    ->where('payment_status', 'processing')
                    ->increment('balance', $payment_request->amount, ['payment_status' => 'success', 'updated_at' => now()]);

                if ($success) {
                    $old_balance = DB::table('customers')
                        ->where('id', $payment_request->customer_id)
                        ->value('balance');

                    DB::table('wallet_transactions')->insert([
                        'customer_id'    => $payment_request->customer_id,
                        'credit'         => $payment_request->amount,
                        'trxid'          => $payment_request->trxid,
                        'agent_sim'      => $payment_request->sim_id,
                        'old_balance'    => $old_balance,
                        'payment_method' => $payment_request->payment_method,
                        'status'         => 1, //0 pending, 1-success, 2-reject
                        'ip'             => '',
                        'type'           => 'deposit',
                        'created_at'     => now(),
                        'updated_at'     => now(),
                    ]);
                }

                DB::commit();
                return response()->json([
                    'status'  => 200,
                    'message' => 'Successfully Status Changed',
                ]);
            }
            return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);

            //code...
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
            return $th;
        }
    }

    public function pending_payment_request($id)
    {

        $check = DB::table('payment_requests')
            ->where('id', $id)
            ->whereNotIn('status', [1, 2])
            ->update([
                'status'     => 0,
                'updated_at' => Carbon::now(),
            ]);

        if ($check) {
            return redirect()->back()->with('success', 'Payment request pending successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to approve payment request.');
        }

    }

    public function markAsSpam(Request $request)
    {
        $request->validate([
            'payment_id'         => 'required|exists:payment_requests,id',
            'payment_method_trx' => 'required|string|max:255',
            'amount'             => 'sometimes|numeric',
        ]);

        $payment = PaymentRequest::where('id', $request->payment_id)
            ->whereIn('status', [0, 4]) // only pending/rejected status
            ->first();

        //check existing success transaction provided by this payment_method_trx

        $checkExistingSuccessTransaction = PaymentRequest::where('payment_method_trx', $request->payment_method_trx)
            ->whereIn('status', [1, 2])
            ->first();

        if ($checkExistingSuccessTransaction) {
            return response()->json([
                'status'  => false,
                'message' => 'This Transaction already approved',
            ], 409); // 409 Conflict is more appropriate than 404
        }

        if (! $payment) {
            return response()->json([
                'status'  => false,
                'message' => 'Payment not found or not eligible for spam marking',
            ], 404);
        }

                                          // 🔥 use a dedicated spam status instead of mixing
        $payment->status             = 2; // let's say 5 = spam
        $payment->payment_method_trx = $request->payment_method_trx;
        if ($request->amount) {
            $payment->amount = $request->amount;
        }

        if ($payment->save()) {

            merchantWebHook($payment->reference);

            return response()->json([
                'status'  => true,
                'message' => 'Payment marked as spam successfully',
            ], 200);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Failed to update payment status',
        ], 500);
    }

}
