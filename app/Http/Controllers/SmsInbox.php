<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\BalanceManager;

class SmsInbox extends Controller
{
    public function inbox_list(Request $request)
    {
        $sender = $request->get('sender');
        $message = $request->get('message');

        $start_date = $request->get('from');
        $end_date = $request->get('to');

        $rows = $request->get('rows');
        $rows = $rows ? $rows : 50;

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ? $sort_type : 'desc';

        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ? $sort_by : 'id';

        $qrdata = DB::table('sim_sms_inboxes')->orderBy('sim_sms_inboxes.' . $sort_by, $sort_type);

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($message)) {
            $qrdata->where('sms', 'like', '%' . $message . '%');
        }

        if (!empty($request->simNumber)) {
            $qrdata->where('simNumber', $request->simNumber);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('date(sim_sms_inboxes.updated_at)'), [$start_date, $end_date]);
        }

        $data = $qrdata->select('sim_sms_inboxes.*')->paginate($rows);

        if ($request->ajax()) {
            return view('sms-inbox.inbox-table-content', compact('data'));
        }
        return view('sms-inbox.inbox-list', compact('data'));
    }

    public function balance_manager(Request $request, $sim_number=null)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';

        $sender = $request->get('sender');
        $start_date = $request->get('from');
        $end_date = $request->get('to');
        $message = $request->get('message');
        $simNumber = $request->get('simNumber');

        $sim = DB::table('balance_manager')->select('sim')->distinct()->get();

        $qrdata = DB::table('balance_manager')
            ->orderBy('balance_manager.'.$sort_by, $sort_type);

        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i', strtotime($start_date));
            $end_date = date('Y-m-d H:i', strtotime($end_date));
            $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        }

        if(!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if(!empty($simNumber)) {
            $qrdata->where('sim', $simNumber);
        }

        if(!empty($message)) {
            $qrdata->where('sms', 'like', '%'.$message.'%');
        }

        if(!empty($request->status)) {
            if($request->status == 'success') {
                $qrdata->whereIn('status', [20, 22]);
            }
            elseif($request->status == 'rejected') {
                $qrdata->where('status', 66);
            }
            elseif($request->status == 'approved') {
                $qrdata->where('status', 77);
            }
            elseif($request->status == 'waiting') {
                $qrdata->where('status', 33);
            }
            elseif($request->status == 'danger') {
                $qrdata->where('status', 55);
            }
            elseif($request->status == 'pending') {
                $qrdata->where('status', 0);
            }
        }

        if(!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }

        if(!empty($request->type)) {
            if($request->type == "cashout") {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if($request->type == "cashin") {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if($request->type == "b2b") {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if($request->type == "RC") {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }
        }

        $data = $qrdata
            ->select('id', 'sender', 'mobile', 'oldbal', 'amount', 'lastbal', 'commission', 'trxid', 'sim', 'type', 'status', 'simslot', 'request_time', 'sms_time')
            ->paginate($rows);

        if ($request->ajax()) {
            return view('sms-inbox.balance-manager-content', compact('data'));
        }

        return view('sms-inbox.balance-manager', compact('data', 'sim'));
    }

    public function approved_balance_manager(Request $request, $id)
    {
        $request_data = BalanceManager::find($id);

        return view('sms-inbox.balance-manager-approved', compact('request_data'));
    }

    public function approved_balance_manager_save(Request $request)
    {
        if ($request->ajax()) {
            DB::table('balance_manager')->where('id', intval($request->id))->update([
                'lastbal' => $request->lastbal,
                'status' => 77
            ]);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function reject_balance_manager(Request $request, $id)
    {
        if ($request->ajax()) {
            DB::table('balance_manager')->where('id', $id)->update(['status' => 66]);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function view_balance_manager($id)
    {
        $request_data = ServiceRequest::find($id);
        $userdata = User::find($request_data->user_id);

        return view('sms-inbox.balance-manager-view', compact('userdata', 'request_data'));
    }
}
