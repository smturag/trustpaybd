<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Modem;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentMethod;

class AdminModem extends Controller
{
    public function modemList(Request $request)
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

        $qrdata = Modem::where('db_status', 'live')->orderBy($sort_by, $sort_type);

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
            return view('admin.admin_modem.modem_table_content', compact('data'));
        }
        return view('admin.admin_modem.modem_list', compact('data'));
    }

    public function delete($id)
    {
        $request_data = Modem::find($id);
        PaymentMethod::where('sim_id', $request_data->sim_id)->delete();

        Modem::find($id)->delete();
        return response()->json(['message' => ' success '], 200);
    }

    public function modem_set_merchant(Request $request, $id)
    {
        $request_data = Modem::find($id);

        return view('admin.admin_modem.modem_set_merchant', compact('request_data'));
    }

    public function modem_for_merchant_saveAction(Request $request)
    {
        if ($request->ajax()) {
            Modem::where('id', intval($request->id))->update([
                'merchant_code' => $request->merchant_code,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function modem_operating_status(Request $request, $modem_id, $status)
    {
        if ($request->ajax()) {
            Modem::find($modem_id)->update([
                'operating_status' => $status,
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }

        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function modem_operating_service_status($modem_id, $status){

        Modem::find($modem_id)->update([
            'operator_service' => $status,
        ]);

        return redirect()->back()->with('success', 'Your operation was successful!');

    }
}
