<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class AdminBm extends Controller
{
    public function balance_manager(Request $request, $status_page, $sim_number = null)
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

        $sim = BalanceManager::select('sim')->distinct()->get();
        $types = BalanceManager::select('type')->whereNotNull('type')->distinct()->pluck('type');

        $qrdata = BalanceManager::orderBy($sort_by, $sort_type);

        // if (!empty($start_date) && !empty($end_date)) {
        //     $start_date = date('Y-m-d H:i', strtotime($start_date));
        //     $end_date = date('Y-m-d H:i', strtotime($end_date));
        //     $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        // }

        if (!empty($start_date) && !empty($end_date)) {
    // Parse the date using Carbon
    $start_date = Carbon::parse($start_date)->startOfDay();
    $end_date = Carbon::parse($end_date)->endOfDay();

    // Apply whereBetween for the 'idate' column
    $qrdata->whereBetween('created_at', [$start_date, $end_date]);
}


        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }

        if (!empty($simNumber)) {
            $qrdata->where('sim', $simNumber);
        }

        if (!empty($message)) {
            $qrdata->where('sms', 'like', '%' . $message . '%');
        }

        if (!empty($request->status)) {
            if ($request->status == 'success') {
                $qrdata->whereIn('status', [20, 22]);
            } elseif ($request->status == 'rejected') {
                $qrdata->where('status', 66);
            } elseif ($request->status == 'approved') {
                $qrdata->where('status', 77);
            } elseif ($request->status == 'waiting') {
                $qrdata->where('status', 33);
            } elseif ($request->status == 'danger') {
                $qrdata->where('status', 55);
            } elseif ($request->status == 'pending') {
                // $qrdata->where('status', 0);
                $qrdata->whereNull('status');

            }elseif($request->status =='success_approve'){
                $qrdata->whereIn('status', [20, 22,77]);
            }
        }


        if ($status_page == 'success') {
            $qrdata->whereIn('status', [20, 22, 77]);
        }

        if ($status_page == 'reject') {
            $qrdata->where('status', 66);
        }

        if ($status_page == 'danger') {
            $qrdata->where('status', 55);
        }

        if ($status_page == 'pendings') {
            $qrdata->where('status', 0);
        }

        if ($status_page == 'waiting') {
            $qrdata->where('status', 33);
        }

        if (!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }

        $typeFilter = $request->get('type');
        if (!empty($typeFilter)) {
            if ($typeFilter == "cashout") {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if ($typeFilter == "cashin") {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if ($typeFilter == "b2b") {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if ($typeFilter == "RC") {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }

            // Fallback to exact type match for any other type values coming from DB.
            if (!in_array($typeFilter, ['cashout', 'cashin', 'b2b', 'RC'])) {
                $qrdata->where('type', $typeFilter);
            }
        }

        $data = $qrdata
            ->select('*')
            ->paginate($rows);

        if ($request->ajax()) {
            return view('admin.bm.balance-manager-content', compact('data'));
        }

        return view('admin.bm.balance-manager', compact('data', 'sim', 'types'));
    }


    public function approved_balance_manager(Request $request, $id)
    {
        $request_data = BalanceManager::find($id);

        return view('admin.bm.balance-manager-approved', compact('request_data'));
    }

    public function approved_balance_manager_save(Request $request)
    {
        if ($request->ajax()) {
            BalanceManager::where('id', intval($request->id))->update([
                'lastbal' => $request->lastbal,
                'status' => 77
            ]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function reject_balance_manager(Request $request, $id)
    {
        if ($request->ajax()) {
            BalanceManager::where('id', $id)->update(['status' => 66]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function view_balance_manager($id)
    {
        $request_data = BalanceManager::find($id);
        //$userdata = User::find($request_data->user_id);

        return view('admin.bm.balance-manager-view', compact('userdata', 'request_data'));
    }
}
