<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Session;
use Auth;
use Mail;
use App\Models\BalanceManager;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use App\Models\User;

class PaymentRequestRNController extends Controller
{
    public function index($agent_code)
    {
        if (!$agent_code) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Agent Code Not Found',
                'success' => false
            ]);
        }
$search_keyword = request()->query('search');
$status = request()->query('status');
$limit = request()->query('limit', 20); // Default limit to 20

$mfs_query = PaymentRequest::where('agent', $agent_code)
    ->select('id', 'customer_id', 'sim_id', 'amount', 'payment_method_trx', 'reference', 'status', 'payment_method', 'cust_name', 'created_at', 'updated_at');

if ($status !== "all" && isset($status)) {
    $mfs_query->where('status', $status);
}

if (!empty($search_keyword)) {
    $mfs_query->where(function($query) use ($search_keyword) {
        $query->where('sim_id', $search_keyword)
              ->orWhere('payment_method_trx', $search_keyword);
    });
}

$payment_query = $mfs_query->orderByDesc('id')->paginate($limit);


        // foreach ($payment_query as  $pay) {
        //     $pay->customer_name = $pay->customer_id ? Customer::find($pay->customer_id)->customer_name : $pay->cust_name;
        //     $pay->statusColor = 'red';
        //     $pay->type = getPaymenType($pay->sim_id);
        //     $pay->statusText = getPaymentStatus(intval($pay->status));
        // }

        // $payment_list = $payment_query->toArray();

        return response()->json([
            'status_code' => 200,
            'payment_list' => $payment_query,
            'success' => true
        ]);
    }


    public function reject_payment_request(Request $request)
    {
        if (!$request->request_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Payment Id Not Found',
            ]);
        }

        PaymentRequest::where('id', $request->request_id)->update([
            'status' => 3,
            'reject_msg'=> $request->reject_msg,
            'merchant_balance_updated'=>1
        ]);

        return response()->json([
            'status_code' => 200,
            'message' => 'Request Rejected Successfully',
        ]);
    }

    public function approve_payment_request(Request $request)
    {
        if (!$request->request_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Payment Id Not Found',
            ]);
        }

        PaymentRequest::where('id', $request->request_id)->update(['status' => 2,'merchant_balance_updated'=>1]);

        paymentRequestApprovedBalanceHandler($request->request_id,'id');



        return response()->json([
            'status_code' => 200,
            'message' => 'Request Approved Successfully',
        ]);
    }
}
