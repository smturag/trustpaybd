<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\smsInbox;

class MemberSms extends Controller
{
    //

	public function member_sms_inbox(Request $request)
    {
        $sender = $request->get('sender');
        $message = $request->get('message');

        $start_date = $request->get('from');
        $end_date = $request->get('to');
        $sim_number= $request->get('sim_number');

        $rows = $request->get('rows');
        $rows = $rows ? $rows : 50;

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ? $sort_type : 'desc';

        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ? $sort_by : 'id';

		$authid = auth('web')->user()->id;
		$usertype = auth('web')->user()->user_type;

        $qrdata = smsInbox::where($usertype,$authid)->orderBy($sort_by, $sort_type);

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($message)) {
            $qrdata->where('sms', 'like', '%' . $message . '%');
        }

        if (!empty($sim_number)) {
            $qrdata->where('sim_number', $sim_number);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }

        $data = $qrdata->select('*')->paginate($rows);

        if ($request->ajax()) {
            return view('member.member_sms.inbox_table_content', compact('data'));
        }
        return view('member.member_sms.list', compact('data'));
    }
}
