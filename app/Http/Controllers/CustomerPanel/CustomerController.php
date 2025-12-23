<?php

namespace App\Http\Controllers\CustomerPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use Session;

use Illuminate\Support\Facades\DB;
use App\Models\BalanceManager;
use App\Models\ServiceRequest;
use App\Models\Customer;
use App\Models\Transaction;


class CustomerController extends Controller
{
    public function dashboard(Request $request, $sim_number = null)
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

        $authid = auth('customer')->user()->id;
        $username = auth('customer')->user()->username;


        $qrdata = BalanceManager::where('merchent_id', $username)->where('status', [20, 77])->orderBy($sort_by, $sort_type);


        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i', strtotime($start_date));
            $end_date = date('Y-m-d H:i', strtotime($end_date));
            $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        }

        if (!empty($request->type)) {
            if ($request->type == "cashout") {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if ($request->type == "cashin") {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if ($request->type == "b2b") {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if ($request->type == "RC") {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }
        }

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }


        if (!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }

        $data = $qrdata->paginate($rows);

        if ($request->ajax()) {
            return view('customer-panel.transaction-content', compact('data'));
        }

        $pageTitle = 'Dashboard';
        return view('customer-panel.dashboard', compact('data', 'pageTitle'));
    }


    public function TransactionContentIndex(Request $request, $sim_number = null)
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

        $authid = auth('customer')->user()->id;
        $username = auth('customer')->user()->username;


        //  $qrdata = BalanceManager::where('merchent_id', $username)->where('status', [20,77])->orderBy($sort_by, $sort_type);
        //  $qrdata = BalanceManager::where('merchent_id',$username)->get();
        $qrdata = BalanceManager::where('merchent_id', $username)->whereIn('status', [20, 77])->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])->orderBy($sort_by, $sort_type);


        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i', strtotime($start_date));
            $end_date = date('Y-m-d H:i', strtotime($end_date));
            $qrdata->whereBetween(DB::raw('date(idate)'), [$start_date, $end_date]);
        }

        if (!empty($request->type)) {
            if ($request->type == "cashout") {
                $qrdata->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout']);
            }

            if ($request->type == "cashin") {
                $qrdata->whereIn('type', ['ngcashin', 'bkcashin', 'rccashin']);
            }

            if ($request->type == "b2b") {
                $qrdata->whereIn('type', ['bkB2B', 'ngB2B', 'rcB2B']);
            }
            if ($request->type == "RC") {
                $qrdata->whereIn('type', ['bkRC', 'ngB2BRC', 'rcB2BRC']);
            }
        }

        if (!empty($sender)) {
            $qrdata->where('sender', $sender);
        }


        if (!empty($request->trxid)) {
            $qrdata->where('trxid', $request->trxid);
        }


        $data = $qrdata->paginate($rows);

        if ($request->ajax()) {
            return view('customer-panel.transaction-content', compact('data'));
        }


        return view('customer-panel.transaction', compact('data'));
    }

}
