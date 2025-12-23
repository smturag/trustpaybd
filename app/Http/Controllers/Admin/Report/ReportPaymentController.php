<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AllReportController extends Controller
{
    public function PaymentReport(Request $request)
    {

        $rows = $request->input('rows', 10);
        $profileType = $request->input('profileType');
        $selectMerchant = $request->input('selectMerchant');
        $selectPartner = $request->input('selectPartner');
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');
        $payment_type = $request->input('payment_type');

        // Build the query based on filters
        $query = Transaction::where('wallet_type', 'admin');

        if ($profileType) {
            $query->where('user_type', $profileType);
        }

        if ($selectMerchant) {
            $query->where('user_id', $selectMerchant)->where('user_type','merchant');
        }

        if ($selectPartner) {
            $query->where('user_id', $selectPartner)->where('user_type','partner');
        }

        if ($start_date &&  $end_date) {
            Log::info(1);
            $query->whereDate('created_at', '>=', $start_date);
            $query->whereDate('created_at', '<=', $end_date);
        }

        if ($payment_type) {
            $query->where('trx_type', $payment_type);
        }

        // Paginate the results
        $data = $query->paginate($rows);

        if ($request->ajax()) {
            return view('admin.reports.payment_report.table', compact('data'));
        }

        return view('admin.reports.payment_report.index', compact('data'));
    }
}
