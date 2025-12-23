<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MfsOperator;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Session;
use Auth;
use Mail;

use App\Models\User;
use App\Models\UserCharge;
use Illuminate\Support\Facades\Session as FacadesSession;

class MemberController extends Controller
{
    public function userFrm(Request $request)
    {
        return view('admin.users.user_add_form');
    }

    public function userAddAction(Request $request)
    {
        DB::beginTransaction();

        $validatedData = $request->validate([
            'email' => 'required|string|email|unique:users|max:255',
            'password' => 'required|string|min:6',
            // 'pin_code' => 'required|string|min:4',
            'mobile' => 'required|unique:users|max:255',
            //'username' => 'required|unique:users|min:6|max:255',
            'fullname' => ['required', 'regex:/^[A-ZÀÂÇÉÈÊËÎÏÔÛÙÜŸÑÆŒa-zàâçéèêëîïôûùüÿñæœ0-9_.,() ]+$/'],
        ]);

        $authid = auth()->user()->id;
        $member_code = rand(1111, 9999);
        $user_type = 'partner';
        $password = $request->password;

        $usercreate = User::create([
            'fullname' => $request->fullname,
            'member_code' => $member_code,
            'mobile' => $request->mobile,
            'email' => $request->email,
            'password' => bcrypt($password),
            'user_type' => $user_type,
            'create_by' => $authid,
            'status' => 1,
            'email_verification_token' => Str::random(32),
        ]);

        $mlMessage = 'Dear ' . $request->fullname . ' Thanks for your registration in Your Parner ID is : ' . $member_code . ' password is : ' . $password . ' And Login Mobile is : ' . $request->mobile . '';

        if ($usercreate) {
            DB::commit();
        } else {
            DB::rollback();
        }

        Session::flash('message', translate('member_created_successfully') . ' ' . $mlMessage);

        return redirect()->back()->withMsg(translate('member_created_successfully'));
    }

    public function index(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $query_data = User::where('db_status', 'live');

        if (!empty($request->member_code)) {
            $query_data->where('member_code', $request->member_code);
        }

        if (!empty($request->mtype)) {
            $query_data->where('user_type', $request->mtype);
        }

        if (!empty($request->get('message'))) {
            $query_data
                ->where('fullname', 'LIKE', '%' . $request->get('message') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
                ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
        }

        $data = $query_data->paginate($rows);

        if ($request->ajax()) {
            return view('admin.users.user_data', compact('data'));
        }

        return view('admin.users.user_list', ['data' => $data]);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('admin.users.user_edit_form', ['user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'fullname' => 'required|max:55',
            'email' => 'required',
            'mobile' => 'required',
            //'password' => 'required',
        ]);

        $user = User::find($id);
        $user->fullname = $request->fullname;
        $user->email = $request->email;
        $user->mobile = $request->mobile;

        if ($request->password) {
            $user->password = bcrypt($request->password);
        }
        $user->save();

        return redirect('/admin/userList')->with('message', 'Data Update Success');
    }

    public function delete($id)
    {
        $user = User::find($id);
        $user->db_status = 'deleted';
        $user->save();
        //User::where('user_type', 'partner')->where('id', $id)->delete();

        return response()->json(['message' => ' success '], 200);
    }

    public function agent_active($agent_id)
    {
        $user = User::where('id', $agent_id)->where('user_type', 'agent')->first();
        if ($user) {
            $user->auto_active_agent = !$user->auto_active_agent;
            $user->save();
            return redirect()->back()->with('message', 'Data Update Success');
        }
        return redirect()->back()->with('message', 'Data not updated');
    }

    public function agent_add_balance(Request $request, $id)
    {
        $validatedData = $request->validate([
            'amount' => 'required|min:1',
            'pincode' => 'required',
        ]);

        $amount = $request->amount;
        $pincode = $request->pincode;
        $details = $request->details;
        $balance_type = $request->balance_type;

        $user = User::find($id);

        if ($user->user_type == 'partner') {
            if ($request->balance_type == 'credit') {
                partnerBalanceAction($id, 'plus', $request->amount, true);
            } elseif ($request->balance_type == 'debit') {
                if ($user->available_balance < $request->amount) {
                    FacadesSession::flash('alert', translate('Partner has not this type of balance'));
                    return redirect()->back();
                }
                partnerBalanceAction($id, 'minus', $request->amount, true);
            }
        } elseif ($user->user_type == 'agent') {
            if ($request->balance_type == 'credit') {
                partnerBalanceAction($user->create_by, 'plus', $request->amount, false);
                agentBalanceAction($id, 'plus', $request->amount);
            } elseif ($request->balance_type == 'debit') {
                if ($user->balance < $request->amount) {
                    FacadesSession::flash('alert', translate('Partner has not this type of balance'));
                    return redirect()->back();
                }
                partnerBalanceAction($user->create_by, 'minus', $request->amount, false);
                agentBalanceAction($id, 'minus', $request->amount);
            }
        }

        $oldbal = $user->balance;
        $new_bal = $oldbal - $amount;
        $trx = rand(11111111, 99999999);
        DB::beginTransaction();
        $trxcrt = Transaction::create([
            'user_id' => $id,
            'amount' => $amount,
            'charge' => 0,
            'old_balance' => $oldbal,
            'trx_type' => $balance_type,
            'trx' => $trx,
            'details' => $details,
            'user_type' => 'agent',
            'wallet_type' => 'admin',
        ]);

        if ($trxcrt) {
            DB::commit();

            FacadesSession::flash('message', translate('balance_update_successfully'));
        } else {
            DB::rollback();
            FacadesSession::flash('alert', translate('not_work'));
        }

        return redirect()->back();
    }

    public function editFees($user_id)
    {
        $user = User::with('user_rate.operator')->findOrFail($user_id);
        $operators = MfsOperator::where('status', 1)->get();

        return view('admin.users.edit_fees', compact('user', 'operators'));
    }

    public function updateFees(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $data = $request->input('fees');

        foreach ($data as $operatorId => $actions) {
            foreach ($actions as $action => $values) {
                UserCharge::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'mfs_operator_id' => $operatorId,
                        'action' => $action,
                    ],
                    [
                        'fee' => $values['fee'] ?? 0,
                        'commission' => $values['commission'] ?? 0,
                    ],
                );
            }
        }

        return redirect()->back()->with('message', 'Fees & Commissions updated successfully!');
    }
}
