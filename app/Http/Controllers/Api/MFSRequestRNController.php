<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Customer;
use App\Models\PaymentRequest;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

// use DB;

use App\Models\Merchant;
use App\Models\Modem;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class MFSRequestRNController extends Controller
{
    public function serviceReq($agent_code)
    {
        if (!$agent_code) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Agent Code Not Found',
                'success' => false,
            ]);
        }

        $agent_id = User::where('member_code', $agent_code)->first()->id;

        if (!$agent_id) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Agent Not Found',
            ]);
        }

        $merchant_id = request()->query('merchant_id');
        $mfs = request()->query('mfs');
        $trxid = request()->query('trxid');
        $simNumber = request()->query('simNumber');
        $cNumber = request()->query('cNumber');
        $start_date = request()->query('from');
        $end_date = request()->query('to');
        $status = request()->query('status');
        $sim_number = request()->query('sim_number');
        $search_keyword = request()->query('search');

        $mfs_query = DB::table('service_requests')
            ->where(function ($query) use ($agent_id) {
                $query->where('agent_id', '=', $agent_id)->orWhere(function ($query) {
                    // Exclude records with status 'rejected' and agent_id as NULL
                    $query->whereNull('agent_id')->where('status', '<>', 4); // 4 is the 'rejected' status
                });
            })
            ->orderBy('id', 'desc');

        // if (request()->query('status') == null || request()->query('status') == 'all') {
        //     $mfs_query
        //         ->where(function ($query) use ($agent_id) {
        //             $query->where('agent_id', '=', $agent_id)->orWhere(function ($query) {
        //                 // Exclude records with status 'rejected' and agent_id as NULL
        //                 $query->whereNull('agent_id')->where('status', '<>', 4); // 4 is the 'rejected' status
        //             });
        //         })
        //         ->orderBy('id', 'desc');
        // } else {
        //     $mfs_query
        //         ->where('status', request()->query('status'))
        //         // ->where('agent_id', $agent_id)
        //         // ->where(function (Builder $query) use ($agent_id) {
        //         //     $query->where('agent_id', $agent_id)->orWhere('agent_id', null);
        //         // })
        //         // ->where('status', '<>', 4)

        //         ->where(function ($query) use ($agent_id) {
        //             $query->where('agent_id', '=', $agent_id)->orWhere(function ($query) {
        //                 // Exclude records with status 'rejected' and agent_id as NULL
        //                 $query->whereNull('agent_id')->where('status', '<>', 4); // 4 is the 'rejected' status
        //             });
        //         })
        //         ->orderBy('id', 'desc');
        // }

        // foreach ($mfs_query as $pay) {
        //     $pay->statusColor = 'red';
        //     //$pay->type = getPaymenType($pay->sim_id);
        //     $pay->statusText = getServiceStatus(intval($pay->status));
        // }

        // $mfs_list = $mfs_query->toArray();

        if ($status != 'all' && isset($status)) {
            $mfs_query->where('status', $status);
        }

        if (!empty($merchant_id)) {
            $merchant = Merchant::where('username', $merchant_id)->first();
            if ($merchant) {
                $mfs_query->where('merchant_id', $merchant->id);
            }
        }

        // Filter based on MFS (Mobile Financial Service)
        if (!empty($mfs)) {
            $mfs_query->where('mfs', $mfs);
        }

        // Filter based on SIM number
        if (!empty($search_keyword)) {
            $mfs_query->where(function ($query) use ($search_keyword) {
                $query->where('sim_number', $search_keyword)->orWhere('get_trxid', $search_keyword)->orWhere('number', $search_keyword);
            });
        }

        // Filter based on date range
        if (!empty($start_date) && !empty($end_date)) {
            $mfs_query->whereBetween(DB::raw('DATE(updated_at)'), [$start_date, $end_date]);
        }

        $data = $mfs_query->paginate(request()->query('limit') != null ? request()->query('limit') : 20);

        return response()->json([
            'status_code' => 200,
            'mfs_list' => $data,
            'success' => true,
        ]);
    }

    public function accept_mfs_request(Request $request)
    {
        if (!$request->request_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'MFS Id Not Found',
            ]);
        }

        if (!$request->agent_code) {
            return response()->json([
                'status_code' => 500,
                'message' => 'MFS Id Not Found',
            ]);
        }

        $table = ServiceRequest::where('id', $request->request_id)->first();
        $agent_id = User::where('member_code', $request->agent_code)->value('id');

        if ($table->agent_id == null && $table->status == 0) {
            ServiceRequest::where('id', $request->request_id)->update([
                'status' => 1,
                'agent_id' => $agent_id,
            ]);

            return response()->json([
                'status_code' => 200,
                'message' => 'Accept request Successfully',
            ]);
        }

        return response()->json([
            'status_code' => 500,
            'message' => 'Already someone has accepted this request.',
        ]);
    }

    public function approve_mfs_request(Request $request)
    {
        if (!$request->request_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'MFS Id Not Found',
            ]);
        }

        ServiceRequest::where('id', $request->request_id)->update([
            'status' => 3,
            'get_trxid' => $request->trxid,
        ]);

        merchantWebHookWithdraw($request->request_id);

        return response()->json([
            'status_code' => 200,
            'message' => 'Request Approved Successfully',
        ]);
    }

    public function reject_mfs_request(Request $request)
    {
        if (!$request->request_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'MFS Id Not Found',
            ]);
        }

        $servicedata = ServiceRequest::where('id', $request->request_id)->first();

        $servicedata->update([
            'status' => 4,
            'agent_id' => null,
            'get_trxid' => $request->trxid,
            'merchant_balance_updated' => 1,
        ]);

        merchantWebHookWithdraw($request->request_id);

        return response()->json([
            'status_code' => 200,
            'message' => 'Request Rejected Successfully',
        ]);
    }

    public function getServiceReq($agent_code)
    {
        // Check if the agent code is provided
        if (!$agent_code) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Agent Code Not Found',
                'success' => false,
            ]);
        }

        // Get the sim1app and sim2app values from the request
        $sim1App = request()->input('sim1app');
        $sim2App = request()->input('sim2app');

        // Initialize the MFS array
        $mfsArray = [];

        // Add sim1App values to the MFS array if not already present
        if (!empty($sim1App)) {
            $s1Array = explode(',', $sim1App);
            foreach ($s1Array as $item) {
                if (!in_array($item, $mfsArray)) {
                    $mfsArray[] = $item;
                }
            }
        }

        // Add sim2App values to the MFS array if not already present
        if (!empty($sim2App)) {
            $s2Array = explode(',', $sim2App);
            foreach ($s2Array as $item) {
                if (!in_array($item, $mfsArray)) {
                    $mfsArray[] = $item;
                }
            }
        }

        // Map the MFS array to their respective IDs
        $stringToIdMap = [
            'rocket' => 3,
            'upay' => 4,
            'nagad' => 2,
            'bkash' => 1,
        ];

        $idArray = array_map(function ($string) use ($stringToIdMap) {
            return $stringToIdMap[$string] ?? null;
        }, $mfsArray);

        // Filter out null values from idArray
        $idArray = array_filter($idArray);

        // Retrieve the agent ID using the provided agent code
        $agent_id = User::where('member_code', $agent_code)->value('id');

        // Check if the agent ID was found
        if (!$agent_id) {
            return response()->json([
                'status_code' => 500,
                'message' => 'Agent not found',
                'success' => false,
            ]);
        }

        // Query the service_requests table
        $mfs_data = DB::table('service_requests')->where('status', 1)->where('agent_id', $agent_id)->whereIn('mfs_id', $idArray)->first();

        // Return the response with the MFS data
        return response()->json([
            'status_code' => 200,
            'mfs_list' => $mfs_data,
            'success' => true,
        ]);
    }

    public function data_submitted(Request $request)
    {


         Log::info('Data Submitted Request', [
        'ip' => $request->ip(),
        'url' => $request->fullUrl(),
        'method' => $request->method(),
        'headers' => $request->headers->all(),
        'payload' => $request->all(),
    ]);
    
        // Basic validation without DB exists rule
        $validator = Validator::make($request->all(), [
            'id' => 'required', // Accept string, integer, or float
            'status' => 'required|integer',
            'get_trxid' => 'nullable|string',
            'modem_id' => 'nullable|string',
            'msg' => 'nullable|string',
            'idate' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status_code' => 422,
                    'message' => $validator->errors(),
                    'success' => false,
                ],
                422,
            );
        }

        return DB::transaction(function () use ($request) {
            // Convert id to integer for database lookup (handles string/float input)
            $id = is_numeric($request->id) ? (int) $request->id : $request->id;
            $service_request = ServiceRequest::lockForUpdate()->find($id);

            if (!$service_request) {
                return response()->json(
                    [
                        'status_code' => 404,
                        'message' => 'Service Request not found',
                        'success' => false,
                    ],
                    404,
                );
            }

            // Already handled
            if ($service_request->status == $request->status) {
                return response()->json(
                    [
                        'status_code' => 422,
                        'message' => 'Request Already Handled',
                        'success' => false,
                    ],
                    422,
                );
            }

            // Processing check
            if (in_array($request->status, [2, 6])) {
                if ($service_request->status != 5) {
                    return response()->json(
                        [
                            'status_code' => 421,
                            'message' => 'Request Is Not In PROCESSING',
                            'success' => false,
                        ],
                        421,
                    );
                }
            }

            // Waiting check
            if ($request->status == 5) {
                if (!in_array($service_request->status, [0, 1])) {
                    return response()->json(
                        [
                            'status_code' => 422,
                            'message' => 'Request Is Not In WAITING',
                            'success' => false,
                        ],
                        422,
                    );
                }
            }

            // Get modem sim number (optional)
            $modem_sim_number = optional(Modem::find($request->modem_id))->sim_number;

            // Update fields
            $service_request->status = $request->status;
            $service_request->get_trxid = $request->get_trxid === 'FAILED' ? $request->msg : $request->get_trxid;
            $service_request->sim_number = $modem_sim_number;
            $service_request->modem_id = (int) $request->modem_id;
            $service_request->msg = $request->msg;
            $service_request->idate = $request->idate;
            $request->status == 6 ? ($service_request->merchant_balance_updated = 1) : null;
            $service_request->save();
            serviceRequestApprovedBalanceHandler($id);

            merchantWebHookWithdraw($id);

            return response()->json([
                'status_code' => 200,
                'message' => 'Request Updated Successfully',
                'success' => true,
            ]);
        });
    }

    public function update_modem()
    {
        $modemId1 = request()->query('modemId1');
        $modemId2 = request()->query('modemId2');
        $modem_details = request()->query('modem_details');

        $modem1_operator = request()->query('modem1_operator');
        $modem2_operator = request()->query('modem2_operator');

        $modem1_simId = request()->query('modem1_simId');
        $modem2_simId = request()->query('modem2_simId');
        $modem1_simNumber = request()->query('modem1_simNumber');
        $modem2_simNumber = request()->query('modem2_simNumber');
        $modem1_simBalance = request()->query('modem1_simBalance');
        $modem2_simBalance = request()->query('modem2_simBalance');

        if (empty($modemId1) && empty($modemId2)) {
            return response()->json(
                [
                    'status_code' => 400,
                    'message' => 'Error: Both modemId1 and modemId2 cannot be null or empty',
                    'success' => false,
                ],
                400,
            );
        }

        if (!empty($modemId1)) {
            $modem1 = Modem::find($modemId1);

            if ($modem1) {
                $modem1->update([
                    'up_time' => time(),
                    'operator' => $modem1_operator ?? $modem1->operator,
                    'sim_id' => $modem1_simId ?? $modem1->sim_id,
                    'sim_number' => $modem1_simNumber ?? $modem1->sim_number,
                    'sim_balance' => $modem1_simBalance,
                    'modem_details' => isset($modem_details) ? $modem_details : '',
                ]);
            }
        }

        if (!empty($modemId2)) {
            $modem2 = Modem::find($modemId2);
            if ($modem2) {
                $modem2->update([
                    'up_time' => time(),
                    'operator' => $modem2_operator ?? $modem2->operator,
                    'sim_id' => $modem2_simId ?? $modem2->sim_id,
                    'sim_number' => $modem2_simNumber ?? $modem2->sim_number,
                    'sim_balance' => $modem2_simBalance ?? $modem2->sim_balance,
                    'modem_details' => isset($modem_details) ? $modem_details : '',
                ]);
            }
        }

        return response()->json([
            'status_code' => 200,
            'message' => 'Modem Updated Successfully',
            'success' => true,
        ]);
    }

    public function resendServiceReq($mfs_id)
    {
        if (empty($mfs_id)) {
            return response()->json(
                [
                    'message' => 'MFS Id not found',
                    'success' => false,
                ],
                404,
            );
        }

        $updated = ServiceRequest::where('id', $mfs_id)->update([
            'status' => 1,
        ]);

        if ($updated) {
            return response()->json(
                [
                    'message' => 'Resend successful',
                    'success' => true,
                ],
                200,
            );
        } else {
            return response()->json(
                [
                    'message' => 'Failed to resend',
                    'success' => false,
                ],
                500,
            );
        }
    }
}
