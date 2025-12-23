<?php

namespace App\Http\Controllers\CustomerPanel;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\ServiceRequest;
use App\Models\WalletTransaction;
use App\Models\MfsOperator;
use App\Models\PaymentMethod;
use App\Models\PaymentRequest;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\McRequest;


class CustomerPaymentRequestController extends Controller
{
    public function transactions(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $customer_id = Auth::guard('customer')->id();
        $query_data = WalletTransaction::where('customer_id', $customer_id)->orderByDesc('id');

        if (!empty($request->get('trxid'))) {
            $query_data->where('trxid', '=', $request->get('trxid'));
        }

        if (!empty($request->get('reference'))) {
            // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
            //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%');
            $query_data->where('reference', '=', $request->get('reference'));

        }

        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('customer-panel.transactions.data', compact('data'));
        }

        $data = [
            'data' => $data,
        ];

        return view('customer-panel.transactions.transaction-list')->with($data);
    }


    public function deposit(Request $request, $customer_id)
    {
        $paymentMethods = DB::table('mfs_operators')
            ->leftJoin('payment_methods', 'mfs_operators.id', 'payment_methods.mobile_banking')
            ->where('payment_methods.status', 1)
            ->select('name', 'image')
            ->distinct('name')
            ->get();
            
           // return $paymentMethods;
            
            
            if(empty($paymentMethods)){
                
                  session()->flash('alert', 'Payment Method not found');
                    Session::flash('type', 'warning');
                    return redirect()->back()->withInput();
                        
               
                
            }else{
                
                  return view('customer-panel.deposit.select-method', compact('customer_id', 'paymentMethods'));
                
          
                
                
            }
           
           
       
    }


    public function deposit_payment(Request $request, $customer_id)
    {
        $props_data = $request->all();
        
       // return $props_data;
        
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        $paymentMethods = PaymentMethod::whereHas('mfs_operator', function ($query) use ($request) {
            $query->where('name', $request->payment_method);
        })
            ->with('mfs_operator')
            ->where('status', 1)
            ->inRandomOrder()
            ->first();
            
            
            if($paymentMethods->type=='api'){
                
                $payment_method = $request->payment_method;
                
                $customer_id = url_decrypt($customer_id);
                $invoice = 'REF'.rand(111111,999999999);
                $trxid = rand(111111,999999999);
                
                DB::beginTransaction();
                try {
                    $insert_payment_id = DB::table('payment_requests')->insertGetId([
                        'customer_id' => $customer_id,
                        'sim_id' => $paymentMethods->sim_id,
                        'trxid' => $trxid,
                        'reference'=>$invoice,
                        'currency' => 'BDT',
                        'payment_method' => $payment_method,
                        'status' => 1,
                        'amount' => $request->deposit_amount,
                        'agent' => $paymentMethods->member_code,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                    
                     DB::Commit();
                    
                    
                }catch (\Exception $e) {
                    DB::rollback();

                    return response()->json([
                        'status_code' => 500,
                        'message' => 'Something went wrong. Try again',
                    ]);
                }
                
                $sim_id = $paymentMethods->sim_id;
                $api_data = $paymentMethods->password;
                $app_key = $paymentMethods->app_key;
                $app_secret = $paymentMethods->app_secret;
                
                $domain = $_SERVER[ 'SERVER_NAME' ];
                
                $mcallback = 'https://'.$domain.'/api/customer_callback';
                
                $execute_url = 'https://'.$domain.'/api/excuteapi';
                
                
                
                if ($payment_method == 'bkash') {
                    
                    $url = "https://jopay.xyz/api/bkpay";
                  
                    $post_data = [
                        'order_id'  => $invoice,
                        'amount'  => $request->deposit_amount,
                        'password' =>$api_data,
                        'username' => $sim_id,
                        'merchant_callback' => $mcallback,
                        'execute_url' => $execute_url,
                        'app_key' => $app_key,
                        'app_secret' =>$app_secret ,
                        'payeer' =>'0'
                    ];
                
                    $getdata = sendPostData($url,$post_data);
                    
                    $encodeurl = json_decode($getdata);
                
                    $statusCode = $encodeurl->statusCode;
                    
                    if($statusCode=='0000'){
                        
                             PaymentRequest::where('reference',$invoice)->update(['payment_method' => 'bkash api','ext_field_1'=>$encodeurl->paymentID]);
                        
                            return redirect()->away( $encodeurl->bkashURL );
                        
                            
                    }else{
                        
                         Session::flash('alert', $encodeurl->message);
                        
                        return redirect()->back()->with('alert', $encodeurl->message);
                       
                    }
                    
                }elseif($payment_method =='nagad'){
            
                 $url = "https://jopay.xyz/api/ngpay";
                      
                    $post_data = [
                        'order_id'  => $invoice,
                        'amount'  => $request->deposit_amount,
                        'merchant_id' =>$api_data,
                        'merchant_number' => $sim_id,
                        'merchant_callback' => $mcallback,
                        'execute_url' => $execute_url,
                        'public_key' => $app_key,
                        'private_key' =>$app_secret
                    ];
                
                $getdata = sendPostData($url,$post_data);
                
                $encodeurl = json_decode($getdata);
                
                $statusCode = $encodeurl->status;
                
                 if($statusCode=='Success'){
                    
                        PaymentRequest::where('reference',$invoice)->update(['payment_method' => 'nagad api','ext_field_1'=>$encodeurl->paymentID]);
                    
                        return redirect()->away( $encodeurl->callBackUrl );
                    
                        
                }else{
                    
                     Session::flash('alert', $encodeurl->message);
                    
                     return redirect()->back()->with('alert', $encodeurl->message);
                }
            
        }
                        
            }else{
                
                 return view('customer-panel.deposit.transaction-input', compact('paymentMethods', 'props_data', 'customer_id'));
                
            }
            
       
    }


    public function deposit_form_submit(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->validate($request, [
                'sim_id' => 'required',
                'trxid' => 'required|string|min:5|unique:payment_requests',
                'customer_id' => 'required|string|min:20',
                'payment_method' => 'required|string',
            ]);

            $customer_id = url_decrypt($request->customer_id);

            $agent = DB::table('payment_methods')
                ->leftJoin('mfs_operators', 'mfs_operators.id', '=', 'payment_methods.mobile_banking')
                ->where('mfs_operators.name', $data['payment_method'])
                ->where('payment_methods.sim_id', $data['sim_id'])
                ->first();

            if (!$agent) {
                return response()->json([
                    'status_code' => 300,
                    'message' => 'Failed to find customer information. Contact with admin',
                ]);
            }

            $balanceManagerStatus = DB::table('balance_managers')
                ->where('sim', $request->sim_id)
                ->where('trxid', $request->trxid)
                ->where('amount', $request->deposit_amount)
                ->value('status');

            if ($balanceManagerStatus == 20 || $balanceManagerStatus == 77) {

                DB::beginTransaction();
                try {
                    $insert_payment_id = DB::table('payment_requests')->insertGetId([
                        'customer_id' => $customer_id,
                        'sim_id' => $data['sim_id'],
                        'trxid' => $request->trxid,
                        'currency' => 'BDT',
                        'payment_method' => $data['payment_method'],
                        'status' => 1,
                        'amount' => $request->deposit_amount,
                        'agent' => $agent->member_code,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    $old_balance = DB::table('customers')
                        ->where('id', $customer_id)
                        ->value('balance');;

                    DB::table('wallet_transactions')->insert([
                        'customer_id' => $customer_id,
                        'credit' => $request->deposit_amount,
                        'trxid' => $request->trxid,
                        'agent_sim' => $request->sim_id,
                        'old_balance' => $old_balance,
                        'payment_method' => $request->payment_method,
                        'status' => 1, //0 pending, 1-success, 2-reject
                        'ip' => '',
                        'type' => 'deposit',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);


                    Customer::where('id', $customer_id)
                        ->increment('balance', $request->deposit_amount, ['payment_status' => 'success', 'updated_at' => now()]);

                    DB::Commit();

                    Session::flash('message', 'Deposit has been Successful');

                    return response()->json([
                        'status_code' => 200,
                        'insert_payment_id' => url_encrypt($insert_payment_id),
                        'message' => 'Deposit has been Successful.',
                    ]);

                } catch (\Exception $e) {
                    DB::rollback();

                    return response()->json([
                        'status_code' => 500,
                        'message' => 'Something went wrong. Try again',
                    ]);
                }

            } else {
                $insert_payment_id = DB::table('payment_requests')->insertGetId([
                    'customer_id' => $customer_id,
                    'sim_id' => $data['sim_id'],
                    'trxid' => $request->trxid,
                    'currency' => 'BDT',
                    'payment_method' => $data['payment_method'],
                    'status' => 0,
                    'amount' => $request->deposit_amount,
                    'agent' => $agent->member_code,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Customer::where('id', $customer_id)
                    ->update(['payment_status' => 'processing', 'updated_at' => now()]);

                return response()->json([
                    'status_code' => 300,
                    'insert_payment_id' => url_encrypt($insert_payment_id),
                    'message' => 'Deposit Request Submitted Successfully',
                ]);
            }
        }

        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function deposit_payment_success_page(Request $request, $payment_id)
    {
        $payment_id2 = url_decrypt($payment_id);

        $data = DB::table('payment_requests')
            ->where('id', $payment_id2)
            ->first();

        return view('customer-panel.deposit.transaction-success-page', compact('payment_id', 'data'));
    }

    public function get_deposit_success_status(Request $request)
    {
        if ($request->ajax()) {
            $payment_status = PaymentRequest::where('id', $request->payment_id)
                ->where('trxid', $request->trxid)
                ->value('status');

            if ($payment_status == 1 || $payment_status == 2) {

                $success = Customer::where('id', $request->customer_id)
                    ->where('payment_status', 'processing')
                    ->increment('balance', $request->deposit_amount, ['updated_at' => now()]);

                if($success) {
                    $old_balance = DB::table('customers')
                        ->where('id', $request->customer_id)
                        ->value('balance');

                    DB::table('wallet_transactions')->insert([
                        'customer_id' => $request->customer_id,
                        'credit' => $request->deposit_amount,
                        'trxid' => $request->trxid,
                        'agent_sim' => $request->sim_id,
                        'old_balance' => $old_balance,
                        'payment_method' => $request->payment_method,
                        'status' => 1,
                        'ip' => '',
                        'type' => 'deposit',
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);
                }

                Session::flash('Deposit has been Successful');

                return response()->json([
                    'status_code' => 200,
                    'status' => $payment_status,
                    'message' => 'Deposit Request Submitted Successfully',
                ]);
            }

            if ($payment_status == 3) {
                DB::table('wallet_transactions')->insert([
                    'customer_id' => $request->customer_id,
                    'credit' => $request->deposit_amount,
                    'trxid' => $request->trxid,
                    'agent_sim' => $request->sim_id,
                    'payment_method' => $request->payment_method,
                    'status' => 2, //0 pending, 1-success, 2-reject
                    'ip' => '',
                    'type' => 'deposit',
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                Customer::where('id', $request->customer_id)
                    ->update(['payment_status' => null, 'updated_at' => now()]);

                Session::flash('message', 'Deposit Request rejected');

                return response()->json([
                    'status_code' => 300,
                    'message' => 'Deposit Request rejected',
                ]);
            }

            return response()->json([
                'status_code' => 500,
                'message' => 'Please wait for confirmation',
            ]);
        }

        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }


    public function withdraw_form()
    {
        $paymentMethods = DB::table('mfs_operators')
            ->leftJoin('payment_methods', 'mfs_operators.id', 'payment_methods.mobile_banking')
            ->where('payment_methods.status', 1)
            ->select('name')
            ->distinct('name')
            ->get();

        return view('customer-panel.withdraw-form', compact('paymentMethods'));
    }


    public function withdraw_save(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
            'account_type' => 'required|string',
            'account_number' => 'required',
            'amount' => 'required|min:1',
        ]);

        $authid = auth('customer')->user()->id;
        $old_balance = auth('customer')->user()->balance;
        $amount = $request->amount;
        $new_balance = $old_balance - $request->amount;


        DB::beginTransaction();

        if ($old_balance <= $request->amount) {
            Session::flash('alert', 'insufficient Balance');
            return redirect()->back()->with('alert', 'insufficient Balance');
        }

        $trxid = rand(11111111, 99999999);

        $updatebal = Customer::where('id', $authid)
            ->update(['balance' => $new_balance]);

        $send = ServiceRequest::create([
            'customer_id' => $authid,
            'old_balance' => $old_balance,
            'new_balance' => $new_balance,
            'get_trxid' => $trxid,
            'number' => $request->account_number,
            'type' => $request->account_type,
            'mfs' => $request->payment_method,
            'amount' => $request->amount,
            'sim_balance' => 0,
        ]);

         

        $trxcrt = WalletTransaction::create([
            'customer_id' => $authid,
            'old_balance' => $old_balance,
            'payment_method' => $request->payment_method,
            'debit' => $amount,
            'account_type' => $request->account_type,
            'account_number' => $request->account_number,
            'type' => 'withdraw',
            'status' => 0,
            'trxid' => $trxid,
            'service_request_id'=> $send->id,
        ]);

        if (($trxcrt) && ($updatebal)) {
            DB::commit();
            Session::flash('message', translate('Request Submitted Successful'));

        } else {

            DB::rollback();
            Session::flash('alert', translate('not_work'));
        }

        return redirect('customer/transactions');
    }

    public function sendMoney()
    {
        return view('customer-panel.send_money.index');
    }

    public function submit_send_money(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'receiver_id' => 'required',
            'amount' => 'required',
            'note' => 'required',
        ]);

        $my_auth = auth('customer')->user()->id;
        $textId = 'TX' . substr(str_replace('-', '', uniqid()), 0, 10);

        $receiver = Customer::find($request->receiver_id);
        $receiver->balance += $request->amount;
        $receiver->save();
        $sender = Customer::find($my_auth);
        $sender->balance -= $request->amount;
        $sender->save();

        $result = DB::table('wallet_transactions')->insert([
            'customer_id' => $my_auth,
            'receiver_customer_id' => $request->receiver_id,
            'note' => $request->note,
            'debit' => $request->amount,
            'trxid' => $textId,
            'status' => 1, //0 pending, 1-success, 2-reject
            'ip' => '',
            'type' => 'send_money',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        if ($result) {
            Session::flash('message', 'Successfully Send Money');
            return redirect()->route('customer.transactions');
        } else {
            $receiver = Customer::find($request->receiver_id);
            $receiver->balance -= $request->amount;
            $receiver->save();

            $sender = Customer::find($my_auth);
            $sender->balance += $request->amount;
            $sender->save();

            Session::flash('message', 'error occurred while');
            return redirect()->back();
        }
    }
    
    public function betting()
    {
       
        return view('customer-panel.betting..betting_form');
    }
    
    public function submit_betting(Request $request)
    {
        $request->validate([
            'req_type' => 'required|string',
            'account_number' => 'required',
            'amount' => 'required|min:1',
        ]);

        $authid = auth('customer')->user()->id;
        $old_balance = auth('customer')->user()->balance;
        $amount = $request->amount;
        $new_balance = $old_balance - $request->amount;


        DB::beginTransaction();

        if ($old_balance <= $request->amount) {
            Session::flash('alert', 'insufficient Balance');
            return redirect()->back()->with('alert', 'insufficient Balance');
        }

        $trxid = rand(11111111, 99999999);

        $updatebal = Customer::where('id', $authid)
            ->update(['balance' => $new_balance]);

        $send = McRequest::create([
            'user_id' => $authid,
            'bet_customer_id' => $request->account_number,
            'type' => $request->req_type,
            'amount' => $request->amount,
             'trxid' => $trxid,
            'status' => 0,
        ]);

         

        $trxcrt = WalletTransaction::create([
            'customer_id' => $authid,
            'old_balance' => $old_balance,
            'payment_method' => 'balance',
            'debit' => $amount,
            'account_type' => $request->req_type,
            'account_number' => $request->account_number,
            'type' => 'betting',
            'status' => 0,
            'trxid' => $trxid,
            'service_request_id'=> $send->id,
        ]);

        if (($trxcrt) && ($updatebal)) {
            DB::commit();
            Session::flash('message', translate('Request Submitted Successful'));

        } else {

            DB::rollback();
            Session::flash('alert', translate('not_work'));
        }

        return redirect('customer/transactions');
    }

}
