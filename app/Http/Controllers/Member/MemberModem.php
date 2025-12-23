<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\Modem;
use App\Models\PaymentMethod;
use App\Models\MfsOperator;

class MemberModem extends Controller
{
    //

	public function member_modem_list(Request $request)
    {
        $merchant_code = $request->get('merchant_code');
        $member_code = $request->get('member_code');

        $start_date = $request->get('from');
        $end_date = $request->get('to');

        $rows = $request->get('rows');
        $rows = $rows ? $rows : 50;

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ? $sort_type : 'desc';

        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ? $sort_by : 'id';

		$member_code = auth()->user('web')->member_code;

        $qrdata = Modem::where('db_status', 'live')->where('member_code', $member_code)->orderBy($sort_by, $sort_type);

        if (!empty($member_code)) {
            $qrdata->where('member_code', $member_code);
        }

        if (!empty($merchant_code)) {
            $qrdata->where('merchant_code', $merchant_code);
        }

        if (!empty($request->sim_number)) {
            $qrdata->where('sim_number', $request->sim_number);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }

        $data = $qrdata->select('*')->paginate($rows);

        if ($request->ajax()) {
            return view('member.member_modem.modem_table_content', compact('data'));
        }
        return view('member.member_modem.modem_list', compact('data'));
    }

    public function member_modem_delete($id)
    {

		$user = Modem::find($id);
		$user->db_status = 'deleted';
		$user->save();
        //User::where('user_type', 'partner')->where('id', $id)->delete();

        return response()->json(['message' => " success "], 200);
    }

    public function api_method_list()
    {

        $authid = auth()->user('web')->id;
        $member_code = auth()->user('web')->member_code;

        $data = PaymentMethod::where('type', 'api')->where('member_code',$member_code)->get();

        return view('member.payment_method.api_method', compact('data'));
    }

    public function add_api_method()
    {
        return view('member.payment_method.create_api_method');
    }

    public function add_api_method_store(Request $request)
    {
        $validator = $request->validate([
            'mfs_name' => 'required|in:bkash,nagad', // Validate MFS operator selection
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'app_key' => 'required|string',
            'app_secret' => 'required|string',
        ]);

        $get_mfs_operator_info = MfsOperator::where('name', $request->mfs_name)->first();

        	$authid = auth('web')->user()->id;
			$member_code = auth('web')->user()->member_code;

        $check = PaymentMethod::create([
            'mobile_banking' => $get_mfs_operator_info->id,
            'type' => 'api',
            'member_code' => $member_code,
            'sim_id' => $request->username,
            'password' => $request->password,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret
        ]);

        if ($check) {
            return redirect()->route('api_method_list')->with('message', 'Record deleted successfully.');
        }
        return redirect()->back();
    }

    public function api_method_edit($id)
    {
        $data = PaymentMethod::find($id);

        return view('member.payment_method.edit_api_method', compact('data'));
    }

    public function api_method_update(Request $request)
    {
        $validator = $request->validate([
            'mfs_name' => 'required|in:bkash,nagad',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'app_key' => 'required|string',
            'app_secret' => 'required|string',
        ]);

        $get_mfs_operator_info = MfsOperator::where('name', $request->mfs_name)->first();

        	$authid = auth()->user('web')->id;
			$member_code = auth()->user('web')->member_code;

        $check = PaymentMethod::find($request->id)->update([
            'mobile_banking' => $get_mfs_operator_info->id,
            'sim_id' => $request->username,
            'password' => $request->password,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret,
             'status' => $request->status ? $request->status : 0,
        ]);

        if ($check) {
            return redirect()->route('api_method_list')->with('message', 'Record added successfully.');
        }
        return redirect()->back();
    }

}
