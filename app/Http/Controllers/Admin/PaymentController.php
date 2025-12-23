<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MfsOperator;
use DB;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class PaymentController extends Controller
{
    public function index_mobile_banking()
    {
        // $data = payment_method::getList(1)->get();
          $data = PaymentMethod::whereNot('type','api')->get();

        // $data = DB::table('payment_methods')->join('mfs_operators', 'payment_methods.mobile_banking', 'mfs_operators.id')->join('users', 'payment_methods.member_code', 'users.member_code')->join('modems', 'payment_methods.sim_id', 'modems.sim_id')->select('payment_methods.*', 'mfs_operators.name as mfs_name', 'users.fullname as users_name', 'modems.sim_number as sim_number')->get();

        return view('admin.payment_method.mobile_banking', compact('data'));
    }

    public function mobile_banking_create_view()
    {
        return view('admin.payment_method.create_mobile_banking_payment');
    }
    public function mobile_banking_edit_view()
    {
        return view('admin.payment_method.create_mobile_banking_payment');
    }

    public function edit_status_mobile_banking(Request $request)
    {
        $data = PaymentMethod::find($request->id);
        if ($request->status == '0') {
            $data->status = 1;
        } else {
            $data->status = 0;
        }
        $data->save();

        return $request;
    }

    public function create_payment_method(Request $request)
    {
        $check = PaymentMethod::where('mobile_banking', $request->mfs_name)
            ->where('type', $request->agent_type)
            ->where('member_code', $request->member)
            ->where('sim_id', $request->modems)
            ->first();
        if (!$check) {
            $model = PaymentMethod::create([
                'mobile_banking' => $request->mfs_name,
                'type' => $request->agent_type,
                'member_code' => $request->member,
                'sim_id' => $request->modems,
                // Set other attributes
            ]);

            if ($model) {
                return redirect()->route('payment.mobile_banking')->with('message', 'Record deleted successfully.');
            } else {
                return Redirect::back()->with('alert', 'Failed to save data.');
            }
        }
        return Redirect::back()->with('alert', 'This data already exists.');
    }

    public function get_agent_modems($id)
    {
        $data = DB::table('modems')->where('member_code', '=', $id)->select('sim_id as id', 'sim_number as text')->get();

        return $data;
    }

    public function pm_destroy(Request $request)
    {
        // Retrieve the record you want to delete

        $record = PaymentMethod::find($request->id);

        // Delete the record
        $record->delete();

        // Perform any additional actions, such as displaying a success message

        return redirect()->route('payment.mobile_banking')->with('message', 'Record deleted successfully.');
    }

    public function CreateMobileBankingPayment()
    {
        return view('admin.payment_method.create_mobile_banking_payment');
    }

    public function api_method_list()
    {
        $data = PaymentMethod::where('type', 'api')->get();

        return view('admin.payment_method.api_method', compact('data'));
    }

    public function add_api_method()
    {
        return view('admin.payment_method.create_api_method');
    }

    public function add_api_method_store(Request $request)
    {
        $validator = $request->validate([
            'mfs_name' => 'required|in:bkash,nagad', // Validate MFS operator selection
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'app_key' => 'required|string|max:255',
            'app_secret' => 'required|string|max:255',
        ]);

        $get_mfs_operator_info = MfsOperator::where('name', $request->mfs_name)->first();

        $check = PaymentMethod::create([
            'mobile_banking' => $get_mfs_operator_info->id,
            'type' => 'api',
            'member_code' => $request->username,
            'sim_id' => null,
            'password' => $request->password,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret,
        ]);

        if ($check) {
            return redirect()->route('payment.api_method_list')->with('message', 'Record deleted successfully.');
        }
        return redirect()->back();
    }

    public function api_method_edit($id)
    {
        $data = PaymentMethod::find($id);

        return view('admin.payment_method.edit_api_method', compact('data'));
    }

    public function api_method_update(Request $request)
    {
        $validator = $request->validate([
            'mfs_name' => 'required|in:bkash,nagad',
            'username' => 'required|string|max:255',
            'password' => 'required|string|max:255',
            'app_key' => 'required|string|max:255',
            'app_secret' => 'required|string|max:255',
        ]);

        $get_mfs_operator_info = MfsOperator::where('name', $request->mfs_name)->first();

        $check = PaymentMethod::find($request->id)->update([
            'mobile_banking' => $get_mfs_operator_info->id,
            'type' => 'api',
            'member_code' => $request->username,
            'sim_id' => null,
            'password' => $request->password,
            'app_key' => $request->app_key,
            'app_secret' => $request->app_secret,
        ]);

        if ($check) {
            return redirect()->route('payment.api_method_list')->with('message', 'Record added successfully.');
        }
        return redirect()->back();
    }
}
