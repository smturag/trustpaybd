<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\WalletTransaction;
use Illuminate\Http\Request;
use App\Models\ServiceRequest;
use App\Models\Transaction;
use App\Models\Customer;

class WalletTransactionController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $query_data = WalletTransaction::where('created_at', '!=', null);

        if (!empty($request->get('merchant_id'))) {
            $query_data->where('merchant_id', '=', $request->get('merchant_id'));
        }

        if (!empty($request->get('customer_id'))) {
            $query_data->where('customer_id', '=', $request->get('customer_id'));
        }

        if (!empty($request->get('trxid'))) {
            $query_data->where('trxid', '=', $request->get('trxid'));
        }

        if (!empty($request->get('reference'))) {
            // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
            //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
            $query_data->where('reference', '=', $request->get('reference'));
        }

        if (!empty($request->get('start_date')) && !empty($request->get('end_date'))) {
            $query_data->where('created_at', '>=', $request->get('start_date'));
            $query_data->where('created_at', '<=', $request->get('end_date'));
        }

        $query_data->orderBy($sort_by, $sort_type);
        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('admin.wallet-transaction.data', compact('data'));
        }

        $data = [
            'data' => $data,
            'merchants' => Merchant::orderBy('fullname')->get(),
        ];

        return view('admin.wallet-transaction.transaction-list')->with($data);
    }

    public function change_wallet_status(Request $request)
    {

        

        $wallet = WalletTransaction::find($request->transId);
       
      //make WalletTransaction decline and ServiceRequest decline

        if ($request->status == 1) {

            $wallet->update([
                'status' => 3,
            ]);

            ServiceRequest::where('id',$wallet->service_request_id)->update([
                'get_trxid' => $request->reason_or_trx,
                'status' => 4
            ]);
            $servicedata = ServiceRequest::find($wallet->service_request_id);


            if($request->user_type == 'merchant'){

                $userdata = Merchant::where('id',$request->user_id)->first();
        
                $amount = $servicedata->amount;
            
                $old_balance = $userdata->balance;
            
                $new_balance = $old_balance + $amount;
            
                $trx = $servicedata->trxid;
            
                $updatebal = Merchant::where('id', $request->user_id)->update(['balance'=>$new_balance]);
            
                $trxcrt = Transaction::create([
                    'user_id' => $request->user_id,
                    'amount' => $amount,
                    'charge' => 0,
                    'old_balance' => $old_balance,
                    'trx_type' => 'credit',
                    'trx' => $trx,
                    'details' => $servicedata->mfs.' Request cancel ',
                    'user_type' => 'merchant',
                    'wallet_type' => 'main'
                ]);
        
                return redirect()->back();
        
        
            }else if($request->user_type == 'customer'){
                $userdata = Customer::where('id',$request->user_id)->first();
        
                $amount = $servicedata->amount;
            
                $old_balance = $userdata->balance;
            
                $new_balance = $old_balance + $amount;
            
                $trx = $servicedata->trxid;
            
                $updatebal = Customer::where('id', $request->user_id)->update(['balance'=>$new_balance]);
            
                $trxcrt = Transaction::create([
                    'user_id' => $request->user_id,
                    'amount' => $amount,
                    'charge' => 0,
                    'old_balance' => $old_balance,
                    'trx_type' => 'credit',
                    'trx' => $trx,
                    'details' => $servicedata->mfs.' Request cancel ',
                    'user_type' => 'customer',
                    'wallet_type' => 'main'
                ]);
        
                return redirect()->back();
        
            }



           
        }
        
//make WalletTransaction approved and ServiceRequest approve

        if ($request->status == 0) {
            $wallet->update([
                'status' => 1,
            ]);

            ServiceRequest::where('id',$wallet->service_request_id)->update([
                'get_trxid' => $request->reason_or_trx,
                'status' => 3
            ]);

            return redirect()->route('admin.wallet.transactions');
        }
    }
}
