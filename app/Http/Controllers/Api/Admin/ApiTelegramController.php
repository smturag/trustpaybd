<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\BalanceManager;
use Illuminate\Support\Facades\Validator;

class ApiTelegramController extends Controller
{
    public function searchDepositTransaction($referenceId){

        if (empty($referenceId)) {
            return response()->json([
                'status' => 'true',
                'message' => 'Reference ID data not found | রেফারেন্স আইডির তথ্য পাওয়া যায়নি',
            ], 404);
        }

        $data = PaymentRequest::select(
            'request_id',
            'amount',
            'payment_method',
            'reference',
            'cust_name',
            'cust_phone',
            'note',
            'reject_msg',
            'payment_method_trx',
            'status'
        )
            ->selectRaw("
                CASE
                    WHEN status = 0 THEN 'pending'
                    WHEN status IN (1, 2) THEN 'completed'
                    WHEN status = 3 THEN 'rejected'
                    ELSE 'unknown'
                END as status_name
            ")
            ->where('reference', $referenceId)
            ->first();

        return $data
            ? response()->json([
                'status' => 'true',
                'data' => $data
            ], 200)
            : response()->json([
                'status' => 'false',
                'message' => 'Data not found | তথ্য পাওয়া যায়নি',
            ], 404);


    }

    public function updateTransaction(Request $request, $referenceId)
{
    $validator = Validator::make($request->all(), [
        'payment_method_trx' => 'required',
        'amount'                =>         'required',
        'from_number'        => 'required',
        'to_number'          => 'nullable',
        'status'             => 'required|in:2,3',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'errors' => $validator->errors(),
        ], 422);
    }

     //check existing success transaction provided by this payment_method_trx

       $checkExistingSuccessTransaction = PaymentRequest::where('payment_method_trx', $request->payment_method_trx)->where('amount',$request->amount)
    ->whereIn('status', [1, 2, 3])
    ->first();

    if ($checkExistingSuccessTransaction) {
        return response()->json([
            'status' => false,
            'message' => 'This Transaction already approved or rejected',
            'transaction_status' => in_array($checkExistingSuccessTransaction->status, [1, 2]) ? 'approved' : 'rejected',
        ], 409); // 409 Conflict is more appropriate
    }



    $transaction = PaymentRequest::where('reference', $referenceId)
        ->whereIn('status', [0, 4])
        ->where('amount',$request->amount)
        ->first();

    if (! $transaction) {
        return response()->json([
            'status' => false,
            'message' => 'Data not found | তথ্য পাওয়া যায়নি',
        ], 404);
    }

    $transaction->update([
        'status'      => $request->status,
        'from_number' => $request->from_number,
        'sim_id'      => $request->to_number,
        'payment_method_trx'=> $request->payment_method_trx,
    ]);

    paymentRequestApprovedBalanceHandler($transaction->id , 'id');
    merchantWebHook($transaction->reference);

    return response()->json([
        'status' => true,
        'message' => 'Transaction updated successfully',
    ], 200);
}


          public function checking_status(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'trnx_id' => 'required',
        ]);


        $get_data = ServiceRequest::select('number', 'mfs', 'old_balance', 'amount', 'new_balance', 'msg', 'status','get_trxid')
            ->where('trxid', $request->trnx_id)
            ->first();

        if ($get_data) {
            $make_status = '';
            switch ($get_data->status) {
                case 1:
                    $make_status = 'pending'; //this is waiting but we show mercent pending
                    break;
                case 2:
                    $make_status = 'success';
                    break;
                case 3:
                    $make_status = 'success';
                    break;
                case 4:
                    $make_status = 'rejected';
                    break;

                default:
                    $make_status = 'pending';
                    break;
            }

            $data = [
                'withdraw_number' => $get_data->number,
                'mfs_operator' => $get_data->mfs,
                'amount' => $get_data->amount,
                'msg' => $get_data->get_trxid,
                'status' => $make_status,
            ];

            return response()->json(
                [
                    'status' => 'true',
                    'data' => $data,
                ],
                200,
            );
        }

        return response()->json(
            [
                'status' => 'false',
                'message' => 'This TRXID not available | এই TRXID উপলব্ধ নেই',

                // 'data' => $data
            ],
            400,
        );
    }

    public function checkBm($trxid){

        $data = BalanceManager::select('sender','sim','amount','mobile','trxid','sms_time','status','type')->where('trxid',$trxid)->first();

        if($data){

            if($data->status == 20 || $data->status == 20 || $data->status == 77){
                $data->status = "Success";
            }else if($data->status == 33){
                $data->status = "Waiting";
            }else if($data->status == 55){
                $data->status = "Danger";
            }else if($data->status == 66){
                $data->status = "Rejected";
            }else{
                $data->status = "pending";
            }

            return response()->json(
            [
                'status' => 'true',
                'data' => $data,
            ],
            200,
        );

        }

        return response()->json(
            [
                'status' => 'false',
                'message' => 'This TRXID not available | এই TRXID উপলব্ধ নেই',
            ],
            409,
        );



    }

}
