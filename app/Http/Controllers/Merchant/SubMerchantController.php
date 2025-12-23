<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

class SubMerchantController extends Controller
{
    public function index()
    {
        $data = Merchant::where('create_by', auth()->guard('merchant')->user()->id)->get();
        return view('merchant.sub_merchant.index', compact('data'));
    }

    public function create()
    {
        return view('merchant.sub_merchant.create');
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $validatedData = $request->validate([
            'email' => 'required|string|email|unique:merchants|max:255',
            'password' => 'required|string|min:6',
            'mobile' => 'required|unique:merchants|max:255',
            'fullname' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
        ]);

        $authid = auth()->guard('merchant')->user()->id;
        $member_code = checkMerchantCode();
        $user_type = 'sub_merchant';
        $password = $request->password;
        $userCreate = Merchant::create([
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

        if ($userCreate) {
            DB::commit();
            Session::flash('message', translate('merchant_created_successfully') . ' ' . $mlMessage);
            return redirect()->back()->withMsg(translate('merchant_created_successfully'));
        } else {
            DB::rollback();
            return redirect()->back()->withMsg(translate('Merchant not created successfully'));
        }
    }

    public function merchant_add_balance(Request $request)
    {
        $user = auth()->guard('merchant')->user();

        $validatedData = $request->validate([
            'amount' => 'required|min:1',
            'pincode' => 'required',
        ]);

        $amount = $request->amount;
        $pincode = $request->pincode;
        $details = $request->details;
        $balance_type = $request->balance_type;
        $sub_merchant = Merchant::where('username', $request->subMerchanId)->first();
        $merchant = Merchant::find($sub_merchant->create_by);

        if ($user->pincode != $pincode) {
            Session::flash('alert', translate('Pincode not matched'));
            return redirect()->back();
        }

        if ($request->balance_type == 'debit') {
            if ($sub_merchant->balance < $request->amount) {
                Session::flash('alert', translate('Request amount is greater thant sub merchant amount'));
                return redirect()->back();
            }
        } else {
            if ($merchant->available_balance < $request->amount) {
                Session::flash('alert', translate('You have inefficient balance to give sub merchant '));
                return redirect()->back();
            }
        }

        $oldbal = $sub_merchant->balance;
        $new_bal = $sub_merchant->balance - $request->amount;

        $trx = rand(11111111, 99999999);

        DB::beginTransaction();

        $trxcrt = Transaction::create([
            'user_id' => $sub_merchant->id,
            'amount' => $request->amount,
            'charge' => 0,
            'old_balance' => $oldbal,
            'trx_type' => $balance_type,
            'trx' => $trx,
            'details' => $details,
            'user_type' => 'sub_merchant',
            'wallet_type' => 'merchant',
            'creator_id' => $user->id,
            'creator_type' => 'merchant',
        ]);

        if ($request->balance_type == 'debit') {
            $merchant->available_balance = $merchant->available_balance + $request->amount;
            $merchant->save();

            $sub_merchant->balance = $sub_merchant->balance - $request->amount;
            $sub_merchant->save();
        } else {
            $merchant->available_balance = $merchant->available_balance - $request->amount;
            $merchant->save();

            $sub_merchant->balance = $sub_merchant->balance + $request->amount;
            $sub_merchant->save();
        }

        if ($trxcrt) {
            DB::commit();
            Session::flash('message', translate('balance_update_successfully'));
        } else {
            Session::flash('alert', translate('not_work'));
        }

        return redirect()->back();
    }

    public function merchantEdit($id)
    {
        $data = Merchant::find($id);

        return view('merchant.sub_merchant.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:sub_merchants,email,' . $id,
            'mobile' => 'required|string|max:15',
            'password' => 'nullable|min:6', // Password is optional
        ]);

        $subMerchant = Merchant::findOrFail($id);
        $subMerchant->fullname = $request->fullname;
        $subMerchant->email = $request->email;
        $subMerchant->mobile = $request->mobile;

        // Update password only if provided
        if ($request->filled('password')) {
            $subMerchant->password = bcrypt($request->password);
        }

        $subMerchant->save();

        return redirect()->route('sub_merchant.index')->with('success', 'Sub Merchant updated successfully.');
    }
}
