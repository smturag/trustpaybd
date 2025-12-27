<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Merchant;
use App\Models\ServiceRequest;
use App\Models\Transaction;
use App\Models\WalletTransaction;
use App\Models\Customer;
use App\Models\Modem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportsController extends Controller
{
    //

    public function serviceReq(Request $request, $status_page, $sim_number = null)
    {
        $merchant_id = $request->get('merchant_id');
        $mfs = $request->get('mfs');

        $start_date = $request->get('from');
        $end_date = $request->get('to');

        $rows = $request->get('rows');
        $rows = $rows ? $rows : 50;

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ? $sort_type : 'desc';

        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ? $sort_by : 'id';

        $cNumber = $request->get('cNumber');

        $qrdata = ServiceRequest::with('merchant')->orderBy($sort_by, $sort_type);

        if (!empty($request->status) && $request->status !== 'all') {
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
        if (!empty($merchant_id)) {
            $merchent_idget = Merchant::find($request->get('merchant_id'));
            if ($merchent_idget->merchant_type == 'general') {
                $qrdata->where('merchant_id', $merchent_idget->id);
            } elseif ($merchent_idget->merchant_type == 'sub_merchant') {
                $qrdata->where('sub_merchant', $merchent_idget->id);
            }
        }

        if (!empty($mfs)) {
            $qrdata->where('mfs', $mfs);
        }

        if (!empty($request->get('response_trxid'))) {
            $qrdata->where('trxid', $request->get('response_trxid'));
        }

        if (!empty($request->sim_number)) {
            $qrdata->where('sim_number', $request->sim_number);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $qrdata->whereBetween(DB::raw('date(updated_at)'), [$start_date, $end_date]);
        }

        if (!empty($cNumber)) {
            $qrdata->where('number', $cNumber);
        }

        $data = $qrdata->select('*')->paginate($rows);

        $merchants = Merchant::orderBy('fullname')->get();

        if ($request->ajax()) {
            return view('admin.mfs.mfs_table_content', compact('data'));
        }
        return view('admin.mfs.mfs_list', compact('data', 'merchants'));
    }

    public function approved_req(Request $request, $id)
    {
        $request_data = ServiceRequest::with('merchant')->find($id);

        return view('admin.mfs.req-approved', compact('request_data'));
    }

    public function serviceReqDetails($id)
    {
        $request_data = ServiceRequest::with(['merchant', 'user'])->findOrFail($id);

        $subMerchant = $request_data->sub_merchant ? Merchant::find($request_data->sub_merchant) : null;
        $customer = $request_data->customer_id ? Customer::find($request_data->customer_id) : null;

        $merchantName = $request_data->merchant->fullname ?: $request_data->merchant->username;
        $subMerchantName = $subMerchant ? ($subMerchant->fullname ?: $subMerchant->username) : null;
        $customerName = $customer ? $customer->customer_name : null;
        $agentName = $request_data->user ? $request_data->user->fullname : null;
        $modemInfo = $request_data->modem_id ? getSimInfo($request_data->modem_id) : null;

        return view('admin.mfs.req-details', compact(
            'request_data',
            'merchantName',
            'subMerchantName',
            'customerName',
            'agentName',
            'modemInfo'
        ));
    }

    public function approved_save(Request $request)
    {
        if ($request->ajax()) {
            ServiceRequest::where('id', intval($request->id))->update([
                'get_trxid' => $request->get_trxid,
                'status' => 3,
            ]);

            WalletTransaction::where('service_request_id', intval($request->id))->update([
                'status' => 1,
            ]);

            serviceRequestApprovedBalanceHandler($request->id);

            merchantWebHookWithdraw($request->id);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function reject_req(Request $request, $id)
    {
        if ($request->ajax()) {
            $servicedata = ServiceRequest::find($id);
            ServiceRequest::where('id', $id)->update(['status' => 4,'merchant_balance_updated'=>1]);
            WalletTransaction::where('service_request_id', intval($id))->update([
                'status' => 3,
            ]);

            $userdata = Merchant::where('id', $servicedata->merchant_id)->first();

            merchantWebHookWithdraw($id);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }

    public function RejectRequest(Request $request)
    {
        $servicedata = ServiceRequest::find($request->transId);

        if ($servicedata->agent_id) {
            Transaction::where('trx', $servicedata->trxid)
                ->where('user_type', 'agent')
                ->where('user_id', $servicedata->agent_id)
                ->update(['status' => 3]);
        }
        ServiceRequest::where('id', $request->transId)->update([
            'status' => 4,
            'get_trxid' => $request->reason_or_trx,
            'merchant_balance_updated'=>1
        ]);

        WalletTransaction::where('service_request_id', intval($request->transId))->update([
            'status' => 2,
        ]);

        merchantWebHookWithdraw($request->transId);

        if ($request->user_type == 'merchant') {
            $userdata = Merchant::where('id', $request->user_id)->first();

            $amount = $servicedata->amount;

            $old_balance = $userdata->balance;
            $new_balance = $old_balance + $amount;

            $trx = $servicedata->trxid;

            $updatebal = Merchant::where('id', $request->user_id)->update(['balance' => $new_balance]);

            $trxcrt = Transaction::create([
                'user_id' => $request->user_id,
                'amount' => $amount,
                'charge' => 0,
                'old_balance' => $old_balance,
                'trx_type' => 'credit',
                'trx' => $trx,
                'details' => $servicedata->mfs . ' Request cancel ',
                'user_type' => 'merchant',
                'wallet_type' => 'main',
            ]);

            return redirect()->back();
        } elseif ($request->user_type == 'customer') {
            $userdata = Customer::where('id', $request->user_id)->first();

            $amount = $servicedata->amount;

            $old_balance = $userdata->balance;

            $new_balance = $old_balance + $amount;

            $trx = $servicedata->trxid;

            $updatebal = Customer::where('id', $request->user_id)->update(['balance' => $new_balance]);

            $trxcrt = Transaction::create([
                'user_id' => $request->user_id,
                'amount' => $amount,
                'charge' => 0,
                'old_balance' => $old_balance,
                'trx_type' => 'credit',
                'trx' => $trx,
                'details' => $servicedata->mfs . ' Request cancel ',
                'user_type' => 'customer',
                'wallet_type' => 'main',
            ]);

            return redirect()->back();
        }
    }

    public function resend_req($id)
    {
        $table = ServiceRequest::where('id', $id)->first();

        // if($table->agent_id){}
        $getRandomActiveUserId = getRandom($table->amount, $table->mfs);

        $transaction = Transaction::where('user_type', 'agent')->where('trx', $table->trxid)->where('amount', $table->amount)->where('trx_type', 'debit')->first();

        if ($transaction) {
            $transaction->update(['status' => 3]);
        }

        if ($getRandomActiveUserId) {
            $mfs = $table->mfs;
            $user = User::find($getRandomActiveUserId);
            $modem = Modem::where('member_code', $user->member_code)
                ->where(function ($query) use ($mfs) {
                    $query->where('operator', $mfs)->orWhere('operator', 'LIKE', "%{$mfs}%");
                })
                ->first();
        }

        ServiceRequest::where('id', $id)->update([
            'status' => $getRandomActiveUserId ? 1 : 0,
            'modem_id' => $getRandomActiveUserId ? $modem->id : null,
            'agent_id' => $getRandomActiveUserId ?? null,
        ]);

        if ($getRandomActiveUserId) {
            $agentBalance = findAgentBalance($getRandomActiveUserId);

            $agentBalance = User::find($getRandomActiveUserId);

            DB::table('transactions')->insert([
                'user_id' => $getRandomActiveUserId,
                'amount' => $table->amount,
                'charge' => 0,
                'old_balance' => $agentBalance->balance,
                'trx_type' => 'debit',
                'trx' => $table->trxid,
                'details' => 'Customer api payment using ' . $table->mfs,
                'user_type' => 'agent',
                'wallet_type' => 'main',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->back()->with('success', 'Data Update successfully');
    }

    public function service_multiple_action(Request $request)
    {
        $action = $request->input('action');
        $reason = $request->input('reason');
        $selectedIds = $request->input('selected_ids');

        try {
            //code...

            if ($action == 'reject') {
                // Process each selected row based on the action
                foreach ($selectedIds as $id) {
                    $servicedata = ServiceRequest::find($id);

                    if ($servicedata->status == 2 || $servicedata->status == 3 || $servicedata->status == 4) {
                    } else {
                        if ($servicedata->agent_id) {
                            Transaction::where('trx', $servicedata->trxid)
                                ->where('user_type', 'agent')
                                ->where('user_id', $servicedata->agent_id)
                                ->update(['status' => 3]);
                        }
                        ServiceRequest::where('id', $id)->update([
                            'status' => 4,
                            'get_trxid' => $reason,
                        ]);

                        WalletTransaction::where('service_request_id', intval($id))->update([
                            'status' => 2,
                        ]);

                        merchantWebHookWithdraw($id);

                        if ($request->user_type == 'merchant') {
                            $userdata = Merchant::where('id', $request->user_id)->first();

                            $amount = $servicedata->amount;

                            $old_balance = $userdata->balance;
                            $new_balance = $old_balance + $amount;

                            $trx = $servicedata->trxid;

                            $updatebal = Merchant::where('id', $request->user_id)->update(['balance' => $new_balance]);

                            $trxcrt = Transaction::create([
                                'user_id' => $request->user_id,
                                'amount' => $amount,
                                'charge' => 0,
                                'old_balance' => $old_balance,
                                'trx_type' => 'credit',
                                'trx' => $trx,
                                'details' => $servicedata->mfs . ' Request cancel ',
                                'user_type' => 'merchant',
                                'wallet_type' => 'main',
                            ]);

                            return redirect()->back();
                        } elseif ($request->user_type == 'customer') {
                            $userdata = Customer::where('id', $request->user_id)->first();

                            $amount = $servicedata->amount;

                            $old_balance = $userdata->balance;

                            $new_balance = $old_balance + $amount;

                            $trx = $servicedata->trxid;

                            $updatebal = Customer::where('id', $request->user_id)->update(['balance' => $new_balance]);

                            $trxcrt = Transaction::create([
                                'user_id' => $request->user_id,
                                'amount' => $amount,
                                'charge' => 0,
                                'old_balance' => $old_balance,
                                'trx_type' => 'credit',
                                'trx' => $trx,
                                'details' => $servicedata->mfs . ' Request cancel ',
                                'user_type' => 'customer',
                                'wallet_type' => 'main',
                            ]);

                            return redirect()->back();
                        }
                    }
                }
            } elseif ($action == 'approve') {
                foreach ($selectedIds as $id) {
                    ServiceRequest::where('id', $id)->update([
                        'status' => 3,
                        'get_trxid' => $request->trxid,
                    ]);

                    merchantWebHookWithdraw($id);
                }
            } elseif ($action == 'resend') {
                foreach ($selectedIds as $id) {
                    $table = ServiceRequest::where('id', $id)->first();

                    if ($table->status == 5 || $table->status == 6) {
                        $getRandomActiveUserId = getRandom($table->amount, $table->mfs);

                        $transaction = Transaction::where('user_type', 'agent')->where('trx', $table->trxid)->where('amount', $table->amount)->where('trx_type', 'debit')->first();

                        if ($transaction) {
                            $transaction->update(['status' => 3]);
                        }

                        ServiceRequest::where('id', $id)->update([
                            'status' => $getRandomActiveUserId ? 1 : 0,
                            'agent_id' => $getRandomActiveUserId ?? null,
                        ]);

                        if ($getRandomActiveUserId) {
                            $agentBalance = findAgentBalance($getRandomActiveUserId);
                            DB::table('transactions')->insert([
                                'user_id' => $getRandomActiveUserId,
                                'amount' => $table->amount,
                                'charge' => 0,
                                'old_balance' => $agentBalance['mainBalance'],
                                'trx_type' => 'debit',
                                'trx' => $table->trxid,
                                'details' => 'Customer api payment using ' . $table->mfs,
                                'user_type' => 'agent',
                                'wallet_type' => 'main',
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                        }
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
