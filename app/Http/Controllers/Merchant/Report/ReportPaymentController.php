<?php

namespace App\Http\Controllers\Merchant\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportPaymentController extends Controller
{
    // public function PaymentReport(Request $request)
    // {
    //     $rows = $request->input('rows', 10);
    //     $profileType = $request->input('profileType');
    //     $selectSubMerchant = $request->input('selectSubMerchant');
    //     $start_date = $request->input('start_date');
    //     $end_date = $request->input('end_date');
    //     $payment_type = $request->input('payment_type');

    //     // Build the query based on filters
    //     $query1 = Transaction::where('wallet_type', 'admin')
    //         ->where('user_type', 'merchant')
    //         ->where('user_id', Auth::guard('merchant')->user()->id);


    //     $query2 = DB::table('transactions as tr')
    //         ->join('merchants as mr', 'tr.user_id', '=', 'mr.id')
    //         ->where('mr.create_by', Auth::guard('merchant')->user()->id)
    //         ->where('tr.wallet_type', 'merchant')
    //         ->where('mr.merchant_type', 'sub_merchant')
    //         ->where('tr.user_type', 'sub_merchant')
    //         ->select('tr.*');


    //     $query = $query1->union($query2);

    //     if ($profileType && $profileType == 'sub_merchant') {
    //         $query->where('user_type', $profileType);
    //     } elseif ($profileType) {
    //         $query->where('wallet_type', $profileType);
    //     }

    //     if ($selectSubMerchant) {
    //         $query->where('user_id', $selectSubMerchant)->where('user_type', 'sub_merchant');
    //     }

    //     if ($start_date && $end_date) {
    //         $query->whereDate('created_at', '>=', $start_date);
    //         $query->whereDate('created_at', '<=', $end_date);
    //     }

    //     if ($payment_type) {
    //         $query->where('trx_type', $payment_type);
    //     }

    //     // Paginate the results
    //     $data = $query->paginate($rows);

    //     if ($request->ajax()) {
    //         return view('merchant.reports.payment_report.table', compact('data'));
    //     }

    //     return view('merchant.reports.payment_report.index', compact('data'));
    // }

    public function PaymentReport(Request $request)
{
    $rows = $request->input('rows', 10);
    $profileType = $request->input('profileType');
    $selectSubMerchant = $request->input('selectSubMerchant');
    $start_date = $request->input('start_date');
    $end_date = $request->input('end_date');
    $payment_type = $request->input('payment_type');

    // Base Query 1: Admin → Merchant transactions
    $query1 = Transaction::query()
        ->whereNot('wallet_type', 'main')
        // ->where('user_type', 'merchant')
        ->where('user_id', Auth::guard('merchant')->user()->id);

    // Base Query 2: Merchant → Sub-Merchant transactions
    $query2 = DB::table('transactions as tr')
        ->join('merchants as mr', 'tr.user_id', '=', 'mr.id')
        ->where('mr.create_by', Auth::guard('merchant')->user()->id)
        ->whereNot('wallet_type', 'main')
        // ->where('tr.wallet_type', 'merchant')
        // ->where('mr.merchant_type', 'sub_merchant')
        // ->where('tr.user_type', 'sub_merchant')
        ->select('tr.*');

    // Apply filters to both queries
    if ($profileType && $profileType == 'sub_merchant') {
        $query1->where('user_type', $profileType);
        $query2->where('tr.user_type', $profileType);
    } elseif ($profileType) {
        $query1->where('wallet_type', $profileType);
        $query2->where('tr.wallet_type', $profileType);
    }

    if ($selectSubMerchant) {
        $query1->where('user_id', $selectSubMerchant)->where('user_type', 'sub_merchant');
        $query2->where('tr.user_id', $selectSubMerchant)->where('tr.user_type', 'sub_merchant');
    }

    if ($start_date && $end_date) {
        $query1->whereBetween(DB::raw('DATE(created_at)'), [$start_date, $end_date]);
        $query2->whereBetween(DB::raw('DATE(tr.created_at)'), [$start_date, $end_date]);
    }

    if ($payment_type) {
        $query1->where('trx_type', $payment_type);
        $query2->where('tr.trx_type', $payment_type);
    }

    // Combine queries using unionAll
    $unionQuery = $query1->unionAll($query2);

    // Wrap in subquery so pagination works
    $data = DB::query()
        ->fromSub($unionQuery, 'transactions')
        ->orderBy('created_at', 'desc')
        ->paginate($rows);

    if ($request->ajax()) {
        return view('merchant.reports.payment_report.table', compact('data'));
    }

    return view('merchant.reports.payment_report.index', compact('data'));
}

}
