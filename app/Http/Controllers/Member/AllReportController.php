<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Auth;

class AllReportController extends Controller
{

public function ServiceReport(Request $request, $service ='deposit')
{
    $merchantId = Auth::guard('merchant')->user()->id;
    $method     = $request->input('method');
    $startDate  = $request->input('start_date');
    $endDate    = $request->input('end_date');
    $startTime  = $request->input('start_time');
    $endTime    = $request->input('end_time');

    $results = collect(); // ← avoid error on empty

    if ($service == 'deposit') {
        $query = PaymentRequest::whereIn('status', [1, 2]);

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

        if ($startDate || $endDate) {
            $startDateTime = $startDate ? $startDate : now()->toDateString();
            $endDateTime   = $endDate   ? $endDate   : now()->toDateString();

            $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
            $endDateTime   .= $endTime   ? ' ' . $endTime   : ' 23:59:59';

            $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
        }

        if (!$method || $method === 'All Method') {
            $results = $query
                ->select('payment_method', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderBy('payment_method')
                ->get();
        } else {
            $results = $query
                ->where('payment_method', $method)
                ->select('payment_method', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('payment_method')
                ->orderBy('payment_method')
                ->get();
        }

        $methods = PaymentRequest::select('payment_method')->distinct()->pluck('payment_method');

    } elseif ($service == 'withdraw') {
        $query = ServiceRequest::whereIn('status', [3, 2]);

        if ($merchantId) {
            $query->where('merchant_id', $merchantId);
        }

        if ($startDate || $endDate) {
            $startDateTime = $startDate ? $startDate : now()->toDateString();
            $endDateTime   = $endDate   ? $endDate   : now()->toDateString();

            $startDateTime .= $startTime ? ' ' . $startTime : ' 00:00:00';
            $endDateTime   .= $endTime   ? ' ' . $endTime   : ' 23:59:59';

            $query->whereBetween('created_at', [$startDateTime, $endDateTime]);
        }

        if (!$method || $method === 'All Method') {
            $results = $query
                ->select('mfs', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('mfs')
                ->orderBy('mfs')
                ->get();
        } else {
            $results = $query
                ->where('mfs', $method)
                ->select('mfs', \DB::raw('SUM(amount) as total_amount'))
                ->groupBy('mfs')
                ->orderBy('mfs')
                ->get();
        }

        $methods = ServiceRequest::select('mfs')->distinct()->pluck('mfs');
    } else {
        // Optional: redirect or 404 if no valid service
        $methods = collect();
    }

    return view('member.reports.service_report.index', [
        'service' => $service,
        'methods' => $methods,
        'results' => $results->toArray()
    ]);
}


}