<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use App\Models\Transaction;

class AdminCustomerController extends Controller
{
    public function customerFrm(Request $request)
    {
        return view('admin.customers.customer_add_form');
    }

    public function customerAddAction(Request $request)
    {
        DB::beginTransaction();

        $validatedData = $request->validate([
            'email' => 'required|string|email|unique:customers|max:255',
            'password' => 'required|string|min:6',
            // 'pin_code' => 'required|string|min:4',
            'mobile' => 'required|unique:customers|max:255',
            //'customername' => 'required|unique:customers|min:6|max:255',
            'customer_name' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
        ]);

        $password = $request->password;

        $customercreate = Customer::create([
            'customer_name' => $request->customer_name,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($password),
            'type' => 'personal',
            'status' => 1,
            'email_verification_token' => Str::random(32),
            'mobile_verification_token' => Str::random(32)
        ]);

        $mlMessage = "Dear " . $request->customer_name . " Thanks for your registration in Your Customer password is : " . $password . " And Login Mobile is : " . $request->mobile . "";

        if (($customercreate)) {

            DB::commit();

        } else {
            DB::rollback();
        }

        Session::flash('message', translate('member_created_successfully') . ' ' . $mlMessage);

        return redirect('admin/customerList')->withMsg(translate('member_created_successfully'));
    }

    public function index(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '10';

        $query_data = Customer::where('db_status', 'live');

        if (!empty($request->mtype)) {
            $query_data->where('type', $request->mtype);
        }

        if (!empty($request->get('message'))) {
            $query_data->where('customer_name', 'LIKE', '%' . $request->get('message') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
                ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
        }

        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('admin.customers.customer_data', compact('data'));
        }

        return view('admin.customers.customer_list', ['data' => $data]);
    }

    public function edit($id)
    {
        $customer = Customer::find($id);
        return view('admin.customers.customer_edit_form', ['customer' => $customer]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_name' => 'required|max:55',
            'email' => 'required',
            'mobile' => 'required',
            //'password' => 'required',
        ]);

        $customer = Customer::find($id);
        $customer->customer_name = $request->customer_name;
        $customer->email = $request->email;
        $customer->mobile = $request->mobile;

        if ($request->password) {
            $customer->password = bcrypt($request->password);
        }
        $customer->save();

        return redirect('/admin/customerList')->with('message', 'Data Update Success');
    }


    public function delete($id)
    {
//        $customer = Customer::find($id);
//        $customer->db_status = 'deleted';
//        $customer->save();
        Customer::where('id', $id)->delete();

        return response()->json(['message' => " success "], 200);
    }
    
     public function customer_add_balance(Request $request,$id)
    {
        
        $authid = auth()->guard('admin')->user()->id;
        
        $cpincode = auth()->guard('admin')->user()->pincode;
        

		$validatedData = $request->validate([
			'amount' => 'required|min:1',
            'pincode' => 'required']);

			$amount = $request->amount;
			$pincode = $request->pincode;
			$details = $request->details;
			$balance_type = $request->balance_type;
			
			
			if($cpincode!=$pincode){
			    
            Session::flash('alert', translate('pin_invalid'));
		 
		    return redirect()->back();
			    
			}
			
		

			$merchn = Customer::find($id);

			$oldbal = $merchn->balance;

			if($balance_type=='credit') {
			$new_bal = $oldbal + $amount;
			}else {
				$new_bal = $oldbal - $amount;
			}


           
			$trx = rand(11111111,99999999);

			DB::beginTransaction();

			$trxcrt = Transaction::create([
					'user_id' => $id,
					'amount' => $amount,
					'charge' => 0,
					'old_balance' => $oldbal,
					'trx_type' => $balance_type,
					'trx' => $trx,
					'details' => $details,
					'user_type' => 'customer',
					'wallet_type' => 'admin'
				]);
			$updatebal = Customer::where('id',$id)->update(['balance'=>$new_bal]);

		if(($trxcrt) && ($updatebal)){

            DB::commit();

			Session::flash('message', translate('balance_update_successfully'));

		 }else {

			  DB::rollback();
			  Session::flash('alert', translate('not_work'));
		 }



		return redirect()->back();

	}

}