<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Models\smsInbox;

class AdminSmsInbox extends Controller
{
    //
	
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

        $qrdata = smsInbox::orderBy($sort_by, $sort_type);

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($message)) {
            $qrdata->where('sms', 'like', '%' . $message . '%');
        }

        if (!empty($request->sim_number)) {
            $qrdata->where('sim_number', $request->sim_number);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }

        $data = $qrdata->select('*')->paginate($rows);

        if ($request->ajax()) {
            return view('admin.sms_inbox.inbox_table_content', compact('data'));
        }
        return view('admin.sms_inbox.list', compact('data'));
    }

}
