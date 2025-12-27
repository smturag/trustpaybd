<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Auth;
use Mail;

use App\Models\Merchant;
use App\Models\Transaction;
use App\Models\MfsOperator;
use App\Models\OperatorFeeCommission;

class AdminMerchant extends Controller
{
    public function index(Request $request)
    {
        // $sort_by = $request->get('sortby') ?: 'id';
        $sort_type = $request->get('sorttype') ?: 'asc';
        $rows = $request->get('rows') ? $request->get('rows') : 50;

        $query = Merchant::where('db_status', 'live');

        if ($request->filled('member_code')) {
            $query->where('username', $request->member_code);
        }

        if ($request->filled('message')) {
            $query->where(function ($q) use ($request) {
                $q->where('fullname', 'LIKE', '%' . $request->message . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->message . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $request->message . '%');
            });
        }

        $data = $query->paginate($rows);

        if ($request->ajax()) {
            return view('admin.merchant.admin_merchant_data', compact('data'))->render();
        }

        return view('admin.merchant.admin_merchant_list', compact('data'));
    }

    public function edit($id)
    {
        $user = Merchant::find($id);
        return view('admin.merchant.merchant_edit_form', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|max:55',
            'email' => 'required',
            'mobile' => 'required',
            //'password' => 'required',
        ]);

        $user = Merchant::find($id);
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->deposit_status = $request->deposit_status == 'on' ? 1 : 0;
        $user->withdraw_status = $request->withdraw_status == 'on' ? 1 : 0;
        $user->v1_p2c = $request->v1_p2c == 'on' ? 1 : 0;
        $user->v1_p2a = $request->v1_p2a == 'on' ? 1 : 0;
        $user->v1_p2p = $request->v1_p2p == 'on' ? 1 : 0;
        $user->v1_manual_gateway = $request->v1_manual_gateway == 'on' ? 1 : 0;
        $user->v1_direct_gateway = $request->v1_direct_gateway == 'on' ? 1 : 0;
        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect('/admin/merchantList')->with('message', 'Data Update Success');
    }

    public function merchantAdd(Request $request)
    {
        return view('admin.merchant.merchant_add_form');
    }

    public function AddAction(Request $request)
    {
        DB::beginTransaction();

        $validatedData = $request->validate([
            'email' => 'required|string|email|unique:merchants|max:255',
            'password' => 'required|string|min:6',
            // 'pin_code' => 'required|string|min:4',
            'mobile' => 'required|unique:merchants|max:255',
            //'username' => 'required|unique:users|min:6|max:255',
            'fullname' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
        ]);

        $authid = auth()->user()->id;
        $member_code = rand(1111, 9999);
        $user_type = 'general';
        $password = $request->password;

        $usercreate = Merchant::create([
            'fullname' => $request->fullname,
            'username' => $member_code,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($password),
            'merchant_type' => $user_type,
            'create_by' => $authid,
            'status' => 1,
            'email_verification_token' => Str::random(32),
        ]);

        $mlMessage = 'Dear ' . $request->fullname . ' Thanks for your registration in Your Merchant ID is : ' . $member_code . ' password is : ' . $password . ' And Login Mobile is : ' . $request->mobile . '';

        if ($usercreate) {
            DB::commit();
        } else {
            DB::rollback();
        }

        Session::flash('message', translate('merchant_created_successfully') . ' ' . $mlMessage);

        return redirect()->back()->withMsg(translate('merchant_created_successfully'));
    }

    public function delete($id)
    {
        $user = Merchant::find($id);
        $user->db_status = 'deleted';
        $user->save();
        //User::where('user_type', 'partner')->where('id', $id)->delete();

        return response()->json(['message' => ' success '], 200);
    }

    public function merchant_add_balance(Request $request, $id)
    {
        // ✅ Validate request
        $validatedData = $request->validate([
            'amount' => 'required|numeric|min:1',
            'pincode' => 'required',
            'balance_type' => 'required|in:credit,debit',
            'details' => 'nullable|string',
        ]);

        $amount = $validatedData['amount'];
        $pincode = $validatedData['pincode'];
        $details = $validatedData['details'] ?? null;
        $balanceType = $validatedData['balance_type'];

        $admin = auth('admin')->user();

        // ✅ Check pincode
        if ($admin->pincode !== $pincode) {
            Session::flash('alert', translate('Pin not matched'));
            return redirect()->back();
        }

        $merchant = Merchant::find($id);

        if (!$merchant) {
            Session::flash('alert', translate('Merchant not found'));
            return redirect()->back();
        }

        $oldBalance = 0;
        $newBalance = 0;
        $newMerchantBalance = 0;



        // ✅ Debit check for general merchant
        if ($merchant->merchant_type === 'general' && $balanceType === 'debit') {
            if ($merchant->available_balance < $amount) {
                Session::flash('alert', translate('Merchant balance is less than your amount'));
                return redirect()->back();
            }

            $oldBalance = $merchant->available_balance;
            $newBalance = $merchant->available_balance-$amount;
            $newMerchantBalance = $merchant->balance-$amount;

        } elseif ($merchant->merchant_type === 'sub_merchant' && $balanceType === 'debit') {
            if ($merchant->balance < $amount) {
                Session::flash('alert', translate('Merchant balance is less than your amount'));
                return redirect()->back();
            }

            $oldBalance = $merchant->balance;
            $newBalance = $merchant->balance - $amount;

        }

        // ✅ Calculate new balance



        if ($merchant->merchant_type === 'general' && $balanceType === 'credit') {
            $oldBalance = $merchant->available_balance;
            $newBalance = $merchant->available_balance + $amount;
            $newMerchantBalance = $merchant->balance + $amount;
        } elseif ($merchant->merchant_type === 'sub_merchant' && $balanceType === 'credit') {
            $oldBalance = $merchant->balance;
            $newBalance = $merchant->balance + $amount;
        }




        // ✅ Generate transaction reference
        $trx = rand(11111111, 99999999);

        DB::beginTransaction();

        try {
            Transaction::create([
                'user_id' => $merchant->id,
                'amount' => $amount,
                'charge' => 0,
                'old_balance' => $oldBalance,
                'after_balance' => $newBalance,
                'trx_type' => $balanceType,
                'trx' => $trx,
                'details' => $details,
                'user_type' => $merchant->merchant_type === 'general' ? 'merchant' : 'sub_merchant',
                'wallet_type' => 'admin',
                'creator_id' => $admin->id,
                'creator_type' => 'admin',
            ]);

            if($merchant->merchant_type === 'general'){
                $merchant->update(['balance' => $newBalance,'available_balance'=> $newMerchantBalance]);
            }elseif($merchant->merchant_type === 'sub_merchant'){
                $merchant->update(['balance' => $newBalance]);
            }

            DB::commit();

            Session::flash('message', translate('Balance updated successfully'));
        } catch (\Exception $e) {
            DB::rollback();
            Session::flash('alert', translate('Something went wrong: ') . $e->getMessage());
        }

        return redirect()->back();
    }


    public function merchant_charge($merchant){

       $merchant = Merchant::with('merchantRate')->find($merchant);

    //    return $merchant;

        return view('admin.merchant.rate_data',compact('merchant'));

    }

public function editFees($merchantId) 
    {
        $merchant = Merchant::with('merchant_rate.operator')->findOrFail($merchantId);
        $operators = MfsOperator::where('status',1)->get();

        return view('admin.merchant.edit_fees', compact('merchant','operators'));
    }

    public function updateFees(Request $request, $merchantId)
    {
        $merchant = Merchant::findOrFail($merchantId);
        $data = $request->input('fees');

        foreach($data as $operatorId => $actions){
            foreach($actions as $action => $values){
                OperatorFeeCommission::updateOrCreate(
                    [
                        'merchant_id' => $merchant->id,
                        'mfs_operator_id' => $operatorId,
                        'action' => $action
                    ],
                    [
                        'fee' => $values['fee'] ?? 0,
                        'commission' => $values['commission'] ?? 0
                    ]
                );
            }
        }

        return redirect()->back()->with('message','Fees & Commissions updated successfully!');
    }

    /**
     * Login as merchant - Admin impersonation
     */
    public function loginAsMerchant($id)
    {
        $merchant = Merchant::findOrFail($id);
        
        // Store admin ID in session to allow reverting back
        Session::put('impersonate_admin_id', Auth::guard('admin')->id());
        
        // Logout from admin guard
        Auth::guard('admin')->logout();
        
        // Login as merchant
        Auth::guard('merchant')->login($merchant);
        
        // Redirect to merchant dashboard
        return redirect()->route('merchant_dashboard')->with('message', 'You are now logged in as ' . $merchant->fullname);
    }
}
