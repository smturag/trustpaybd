<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\BalanceManager;
use App\Models\Modem;

class MemberReport extends Controller
{
    
	public function member_transaction(Request $request, $sim_number=null)
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
		
		$authid = auth()->user('web')->id;
		$usertype = auth()->user('web')->user_type;
		$member_code = auth()->user('web')->member_code;

        $sim = Modem::where('member_code', $member_code)->get();

        $qrdata = BalanceManager::where($usertype, $authid)->orderBy($sort_by, $sort_type);

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

        $data = $qrdata->paginate($rows);
		
        if ($request->ajax()) {
            return view('member.transaction-content', compact('data'));
        }

        return view('member.transaction', compact('data', 'sim'));
    }
	
	
	
	
	public function approved_transaction(Request $request, $id)
    {
        $request_data = BalanceManager::find($id);

        return view('member.transaction-approved', compact('request_data'));
    }

    public function approved_transaction_save(Request $request)
    {
        if ($request->ajax()) {
            BalanceManager::where('id', intval($request->id))->update([
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

    public function reject_transaction(Request $request, $id)
    {
        if ($request->ajax()) {
            BalanceManager::where('id', $id)->update(['status' => 66]);

            return response()->json([
                'status'  => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function view_transaction($id)
    {
        $request_data = BalanceManager::find($id);
        //$userdata = User::find($request_data->user_id);

        return view('member.transaction-view', compact('userdata', 'request_data'));
    }
	
	public function transaction_report_api(Request $request, $sim_number=null)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '100';
		
		$authid = auth()->user('web')->id;
		$usertype = auth()->user('web')->user_type;
		$member_code = auth()->user('web')->member_code;

        $sim = Modem::where('member_code', $member_code)->get();

        $qrdata = BalanceManager::where($usertype, $authid)->orderBy($sort_by, $sort_type);
		
        if(!empty($sim_number)) {
            $qrdata->where('sim', $sim_number);
        }
        $data = $qrdata->paginate($rows);

        return view('member.transaction', compact('data', 'sim'));
    }
	
}
