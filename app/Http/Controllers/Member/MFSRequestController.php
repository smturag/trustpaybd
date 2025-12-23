<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\Merchant;
use App\Models\Modem;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use App\Models\WalletTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MFSRequestController extends Controller
{
    // public function serviceReq(Request $request, $status_page, $sim_number = null)
    // public function serviceReq(Request $request)
    // {

    //     // dd($request);
    //     $get_user = auth()->user('web');
    //     $user_type = $get_user->user_type;

    //     $merchant_id = $request->get('merchant_id');
    //     $mfs = $request->get('mfs');
    //     $start_date = $request->get('from');
    //     $end_date = $request->get('to');
    //     $rows = $request->get('rows') ? $request->get('rows') : 50;
    //     $sort_type = $request->get('sorttype') ? $request->get('sorttype') : 'desc';
    //     $sort_by = $request->get('sortby') ? $request->get('sortby') : 'id';

    //     // Initialize the query
    //     $qrdata = ServiceRequest::orderBy($sort_by, $sort_type);

    //     // Filter based on user type
    //     if ($get_user->user_type == 'agent') {
    //         $qrdata->where('agent_id', '=', $get_user->id)->orWhereNull('agent_id');
    //     } elseif ($get_user->user_type == 'dso') {
    //         $agents = User::where('dso', $get_user->id)
    //             ->where('user_type', 'agent')
    //             ->get();
    //         $agentIDs = $agents->pluck('id')->toArray();
    //         $qrdata->whereIn('agent_id', $agentIDs);
    //     } elseif ($get_user->user_type == 'partner') {
    //         $dso = User::where('partner', $get_user->id)
    //             ->where('user_type', 'dso')
    //             ->get();
    //         $dsoIds = $dso->pluck('id')->toArray();
    //         $agents = User::whereIn('dso', $dsoIds)->where('user_type', 'agent')->get();
    //         $agentIDs = $agents->pluck('id')->toArray();
    //         $qrdata->whereIn('agent_id', $agentIDs);
    //     }

    //     // Filter based on status
    //     if (!empty($request->status)) {
    //         if ($request->status == 'success') {
    //             $qrdata->whereIn('status', [2, 3]);
    //         } elseif ($request->status == 'rejected') {
    //             // Fixed typo here
    //             $qrdata->where('status', 4);
    //         } elseif ($request->status == 'approved') {
    //             $qrdata->where('status', 3);
    //         } elseif ($request->status == 'waiting') {
    //             $qrdata->where('status', 1);
    //         } elseif ($request->status == 'pending') {
    //             $qrdata->where('status', 0);
    //         }
    //     }

    //     // Filter based on merchant_id
    //     if (!empty($merchant_id)) {
    //         $merchent_idget = Merchant::where('username', $merchant_id)->first();
    //         $qrdata->where('merchant_id', $merchent_idget->id);
    //     }

    //     // Filter based on MFS (Mobile Financial Service)
    //     if (!empty($mfs)) {
    //         $qrdata->where('mfs', $mfs);
    //     }

    //     // Filter based on SIM number
    //     if (!empty($request->sim_number)) {
    //         $qrdata->where('sim_number', $request->sim_number);
    //     }

    //     // Filter based on date range
    //     if (!empty($start_date) && !empty($end_date)) {
    //         $qrdata->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
    //     }

    //     // Paginate the results
    //     $data = $qrdata->paginate($rows);

    //     // Return the appropriate view based on AJAX or standard request
    //     // if ($request->ajax()) {
    //     //     return view('member.mfs_request.mfs_table_content', compact('data', 'user_type'));
    //     // }

    //     Log::info($qrdata->get());

    //     return view('member.mfs_request.index', compact('data', 'user_type'));
    // }

    public function serviceReq(Request $request, $status_page, $sim_number = null)
    {
        // Get the authenticated user and their type
        $get_user = auth()->user('web');
        $user_type = $get_user->user_type;

        // Retrieve request parameters with defaults
        $merchant_id = $request->get('merchant_id');
        $mfs = $request->get('mfs');
        $trxid = $request->get('trxid');
        $simNumber = $request->get('simNumber');
        $cNumber = $request->get('cNumber');
        $start_date = $request->get('from');
        $end_date = $request->get('to');
        $rows = $request->get('rows', 50);
        $sort_type = $request->get('sorttype', 'desc');
        $sort_by = $request->get('sortby', 'id');

        // Initialize the query with sorting
        $qrdata = ServiceRequest::orderBy($sort_by, $sort_type);

        // Filter based on user type
        if ($user_type == 'agent') {
            $qrdata->where(function ($query) use ($get_user) {
                $query->where('agent_id', '=', $get_user->id)->orWhere(function ($query) {
                    // Exclude records with status 'rejected' and agent_id as NULL
                    $query->whereNull('agent_id')->where('status', '<>', 4); // 4 is the 'rejected' status
                });
            });
        } elseif ($user_type == 'dso') {
            $agentIDs = User::where('dso', $get_user->id)->where('user_type', 'agent')->pluck('id')->toArray();
            $qrdata->whereIn('agent_id', $agentIDs);
        } elseif ($user_type == 'partner') {
            $dsoIds = User::where('partner', $get_user->id)->where('user_type', 'dso')->pluck('id')->toArray();
            $agentIDs = User::whereIn('dso', $dsoIds)->where('user_type', 'agent')->pluck('id')->toArray();
            $qrdata->whereIn('agent_id', $agentIDs);
        }

        // Filter based on status
        if (!empty($request->status)) {
            switch ($request->status) {
                case 'success':
                    $qrdata->whereIn('status', [2, 3]);
                    break;
                case 'rejected':
                    $qrdata->where('status', 4);
                    break;
                // case 'approved':
                //     $qrdata->where('status', 3);
                //     break;
                case 'waiting':
                    $qrdata->where('status', 1);
                    break;
                case 'pending':
                    $qrdata->where('status', 0);
                    break;
                case 'processing':
                    $qrdata->where('status', 5);
                    break;
                case 'failed':
                    $qrdata->where('status', 6);
                    break;
            }
        }

        // Filter based on merchant_id
        if (!empty($merchant_id)) {
            $merchant = Merchant::where('username', $merchant_id)->first();
            if ($merchant) {
                $qrdata->where('merchant_id', $merchant->id);
            }
        }

        // Filter based on MFS (Mobile Financial Service)
        if (!empty($mfs)) {
            $qrdata->where('mfs', $mfs);
        }

        if (!empty($simNumber)) {
            $qrdata->where('modem_id', $simNumber);
        }

        // Filter based on SIM number
        if (!empty($request->sim_number)) {
            $qrdata->where('sim_number', $request->sim_number);
        }

        // Filter based on date range
        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('DATE(updated_at)'), [$start_date, $end_date]);
        }

        if (!empty($trxid)) {
            $qrdata->where('get_trxid', $trxid);
        }

        if (!empty($cNumber)) {
            $qrdata->where('number', $cNumber);
        }

        // Paginate the results
        $data = $qrdata->paginate($rows);

        if ($request->ajax()) {
            return view('member.mfs_request.mfs_table_content', compact('data', 'user_type'));
        }

        // Return the view with the filtered data
        return view('member.mfs_request.index', compact('data', 'user_type'));
    }

    public function accept_mfs_request($id, Request $request)
    {
        if ($request->ajax()) {
            $table = ServiceRequest::where('id', $id)->first();

            if ($table->agent_id == null && $table->status == 0) {
                DB::beginTransaction();

                ServiceRequest::where('id', $id)
                    ->where('status', 0)
                    ->update([
                        'status' => 1,
                        'agent_id' => $request->agent_id,
                    ]);

                $wallet = WalletTransaction::where('service_request_id', $id)->first();
                if ($wallet) {
                    $wallet->update([
                        'status' => 1,
                    ]);
                }

            $findAgent = User::where('member_code', $table->member_code)->first();

             serviceRequestApprovedBalanceHandler($id);
                merchantWebHookWithdraw($id);


                DB::table('transactions')->insert([
                    'user_id' => $request->agent_id,
                    'amount' => $table->amount,
                    'charge' => 0,
                    'old_balance' => $findAgent->balance,
                    'trx_type' => 'debit',
                    'trx' => $table->trxid,
                    'details' => 'Customer api payment using ' . $table->mfs,
                    'user_type' => 'agent',
                    'wallet_type' => 'main',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);

                DB::commit();

                return response()->json([
                    'status' => 200,
                    'message' => 'Accept request Successfully',
                ]);
            }

            DB::rollBack();

            return response()->json([
                'status' => 500,
                'message' => 'Already someone has accepted this request.',
            ]);
        }
        DB::rollBack();
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function approve_mfs_request(Request $request)
    {
        ServiceRequest::where('id', $request->request_id)->update([
            'status' => 3,
            'get_trxid' => $request->trxid,
        ]);


        serviceRequestApprovedBalanceHandler($request->request_id);
        merchantWebHookWithdraw($request->request_id);




        return back()->with('Successfully saved the Trx ID.');
    }

    public function reject_mfs_request(Request $request)
    {
        if (1 == 1) {
            $table = ServiceRequest::where('id', $request->id)->first();

            $userdata = Merchant::where('id', $table->merchant_id)->first();
            $amount = $table->amount;
            $old_balance = $userdata->balance;
            $new_balance = $old_balance + $amount;
            $updatebal = Merchant::where('id', $table->merchant_id)->update(['balance' => $new_balance]);

            $transaction = Transaction::where('user_type', 'agent')
                ->where('trx', $table->trxid)
                ->where('amount', $table->amount)
                ->where('user_id', auth()->user('web')->id)
                ->where('trx_type', 'debit')
                ->first();

            if ($transaction) {
                $transaction->update(['status' => 3]);
            }

            ServiceRequest::where('id', $request->id)->update([
                'status' => 4,
                'get_trxid' => $request->reason_or_trx,
                'merchant_balance_updated'=>1
            ]);

            $wallet = WalletTransaction::where('service_request_id', $request->id)->first();
            if ($wallet) {
                $wallet->update([
                    'status' => 3,
                ]);
            }

            merchantWebHookWithdraw($request->id);

            // return response()->json([
            //     'status' => 200,
            //     'message' => 'Your request rejected',
            // ]);

            return back()->with('Failed to reject request.');
        }
        // return back()->with('Failed to reject request.');
    }

    public function resend_req($id)
    {
        ServiceRequest::where('id', $id)->update([
            'status' => 1,
        ]);

        return redirect()->back()->with('success', 'Data Update successfully');
    }

    public function service_multiple_action(Request $request)
    {
        // return $request;

        $action = $request->input('action');
        $reason = $request->input('reason');
        $selectedIds = $request->input('selected_ids');

        try {
            //code...

            if ($action == 'reject') {
                // Process each selected row based on the action
                foreach ($selectedIds as $id) {
                    $table = DB::table('service_requests')->where('id', $id)->first();

                    if ($table->status == 2 || $table->status == 3 || $table->status == 4) {
                    } else {
                        $transaction = Transaction::where('user_type', 'agent')
                            ->where('trx', $table->trxid)
                            ->where('amount', $table->amount)
                            ->where('user_id', auth()->user('web')->id)
                            ->where('trx_type', 'debit')
                            ->first();

                        if ($transaction) {
                            $transaction->update(['status' => 3]);
                        }
                        ServiceRequest::where('id', $id)->update([
                            'status' => 4,
                            'get_trxid' => $reason,
                        ]);
                    }
                }
            } elseif ($action == 'approve') {
                foreach ($selectedIds as $id) {
                    ServiceRequest::where('id', $id)->update([
                        'status' => 3,
                        'get_trxid' => $request->trxid,
                    ]);
                }
            } elseif ($action == 'resend') {
                foreach ($selectedIds as $id) {
                    $table = ServiceRequest::where('id', $id)->first();
                    if ($table->status == 5 || $table->status == 6) {
                        ServiceRequest::where('id', $id)->update([
                            'status' => 1,
                        ]);
                    }
                }
            }

            // Return a response, e.g., updated table content
            return response()->json([
                'status' => 'success',
                'message' => 'Action processed successfully',
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'false',
                'message' => 'Action processed successfully',
            ]);
        }
    }
}
