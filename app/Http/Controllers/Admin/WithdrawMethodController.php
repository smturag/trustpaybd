<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\WithdrawMethod;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class WithdrawMethodController extends Controller
{
    public function index()
    {
        $data = DB::table('withdraw_methods')
            ->join('mfs_operators', 'withdraw_methods.mobile_banking', 'mfs_operators.id')
            ->select('withdraw_methods.*', 'mfs_operators.name as mfs_name')
            ->get();

        return view('admin.withdraw_method.mobile-banking-list', compact('data'));
    }

    public function mobile_banking_create_view()
    {
        return view('admin.withdraw_method.create_mfs_method_form');
    }

    public function edit_status(Request $request)
    {
        $data = WithdrawMethod::find($request->id);

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
        $check = WithdrawMethod::where('mobile_banking', $request->mfs_name)
            ->where('type', $request->agent_type)
            ->first();

        if (!$check) {
            $model = WithdrawMethod::create([
                'mobile_banking' => $request->mfs_name,
                'type' => $request->agent_type,
            ]);

            if ($model) {
                return redirect()->route('withdraw.mobile_banking')->with('message', 'Record deleted successfully.');
            } else {
                return Redirect::back()->with('alert', 'Failed to save data.');
            }
        }
        return Redirect::back()->with('alert', 'This data already exists.');
    }


    public function withdraw_destroy(Request $request)
    {
        $record = WithdrawMethod::find($request->id);
        $record->delete();

        return redirect()->route('withdraw.mobile_banking')->with('message', 'Record deleted successfully.');
    }
}
