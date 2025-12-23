<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;
use Auth;
use Mail;
use App\Models\BalanceManager;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class PaymentRequestController extends Controller
{
    public function index(Request $request)
    {


        $get_user = auth()->user('web');

        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';
        $cust_name = $request->get('cust_name');

        $mfs = $request->get('mfs');
        $method_number = $request->get('method_number');

        $status = $request->get('status');

        if ($get_user->user_type == 'agent') {
            $get_merchant = Merchant::where('fullname', $get_user->fullname)->first();
            // dd($get_merchant);

            $query_data = PaymentRequest::where('created_at', '!=', null)->where('agent', $get_user->member_code);
            // ->where('merchant_id', '=', $get_merchant->id);
        } elseif ($get_user->user_type == 'dso') {
            $agents = User::where('dso', $get_user->id)
                ->where('user_type', 'agent')
                ->get();
            $agentIDs = $agents->pluck('member_code')->toArray();
            $query_data = PaymentRequest::whereIn('agent', $agentIDs);
        } elseif ($get_user->user_type == 'partner') {
            // $dso = User::where('partner', $get_user->id)
            //     ->where('user_type', 'dso')
            //     ->get();
            // $dsoIds = $dso->pluck('id')->toArray();
            // $agents = User::whereIn('dso', $dsoIds)->where('user_type', 'agent')->get();
            // $agentIDs = $agents->pluck('member_code')->toArray();
            // $query_data = PaymentRequest::whereIn('agent', $agentIDs);
            $query_data = PaymentRequest::where('partner',$get_user->member_code);

        }

        if($cust_name) {
            $query_data->where(function($query) use ($cust_name) {
                $query->where('cust_name', $cust_name)
                      ->orWhere('cust_phone', $cust_name);
            });
        }

        if (!empty($mfs)) {
            $query_data->where('payment_method', $mfs);
        }

        if (!empty($method_number)) {
            $query_data->where('sim_id', $method_number);
        }

        if (!empty($request->get('trxid'))) {
            $query_data->where('payment_method_trx', '=', $request->get('trxid'));
        }

        if (!empty($request->get('reference'))) {
            // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
            //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
            //     ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
            $query_data->where('reference', '=', $request->get('reference'));
        }

        // if (!empty($request->get('start_date')) && !empty($request->get('end_date'))) {
        //     $query_data->where('created_at', '>=', $request->get('start_date'));
        //     $query_data->where('created_at', '<=', $request->get('end_date'));
        // }

        if (!empty($request->get('start_date')) && !empty($request->get('end_date'))) {
            $query_data->whereDate('created_at', '>=', $request->get('start_date'))
                       ->whereDate('created_at', '<=', $request->get('end_date'));
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

        $query_data->orderBy($sort_by, $sort_type)->whereNotNull('payment_method');
        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('member.payment_request.data', compact('data'));
        }

        $data = [
            'data' => $data,
            'merchants' => Merchant::orderBy('fullname')->get(),
            'user_type' => $get_user->user_type,
        ];

        return view('member.payment_request.index')->with($data);
    }

    // public function approved_payment_request(Request $request, $id)
    // {
    //     $request_data = PaymentRequest::with(['merchant'])->where('id', $id)->first();

    //     return view('admin.merchant.payment-request.payment-request-approved', compact('request_data'));
    // }

    public function reject_payment_request(Request $request)
    {
        PaymentRequest::where('id', $request->transId)->update(['status' => 3, 'reject_msg' => $request->reason, 'merchant_balance_updated'=>1]);

         merchantWebHook($request->transId);


        return response()->json([
            'status' => 200,
            'message' => 'Successfully Status Changed',
        ]);
    }

    public function approve_payment_request(Request $request, $id)
    {
        if ($request->ajax()) {
            PaymentRequest::where('id', $id)->update(['status' => 2,'accepted_by'=>'Agent']);

            paymentRequestApprovedBalanceHandler($id,'id');
            merchantWebHook($id);



            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }
}
