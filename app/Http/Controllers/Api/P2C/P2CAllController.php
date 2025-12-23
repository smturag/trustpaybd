<?php

namespace App\Http\Controllers\Api\P2C;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use App\Models\PaymentMethod;
use App\Service\Backend\BkashService;

class P2CAllController extends Controller
{

    protected $bkashService;
    public function __construct(Request $request)
    {

        $this->bkashService = new BkashService();

    }

    /**
     * Handle bKash redirect response.
     *
     * Example:
     * https://ibotbd.com/redirect_url?paymentID=TR0011r1IagPl1758048968325&status=success&signature=wxX1TlmG84&apiVersion=1.2.0-beta/
     */
    public function bkashRedirect(Request $request)
    {
        $paymentId   = $request->query('paymentID');
        $status      = $request->query('status');
        $signature   = $request->query('signature');
        $apiVersion  = $request->query('apiVersion');

        // Validate required parameters
        if (!$paymentId || !$status) {
            return response()->json([
                'success' => false,
                'message' => 'Missing required parameters',
            ], 400);
        }

        // Fetch payment request
        $paymentRequest = PaymentRequest::where('trxid', $paymentId)->first();

        if (!$paymentRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Payment request not found',
            ], 404);
        }

        // return $paymentRequest;

        // If already processed, prevent duplicate update
        // if ($paymentRequest->status != 0) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Payment already processed',
        //     ], 409);
        // }

        // Determine new status
        $statusMap = [
            'success' => 1,
            'failure' => 3,
            'cancel'  => 3,
        ];

        $newStatus = $statusMap[$status] ?? null;

        if ($newStatus) {
            DB::beginTransaction();

            if($newStatus == 3){

                $paymentRequest->update([
                    'payment_method' => 'bkash',
                    'status'         => 3,
                ]);


                $data = PaymentRequest::where('trxid', $paymentId)->first();

                $baseCallbackUrl = rtrim($data->callback_url, '/');

                   $callbackWithStatus = $baseCallbackUrl . '?payment=cancelled';

                    return redirect()->away($callbackWithStatus);


            }

            try {

                $findPaymentMethod = PaymentMethod::where('sim_id',$paymentRequest->sim_id)->first();

                if (!$findPaymentMethod) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Fraud Searching',
                    ], 409);
                }

                $tokenResponse = $this->bkashService->generateBkashToken($findPaymentMethod);

                if (!($tokenResponse['success'] ?? false)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token generation failed',
                    ], 400);
                }

                $idToken  = $tokenResponse['data']['id_token'];


                //doing Execute payment

                $dataFromExecute = $this->bkashService->executePayment(
                    $paymentId,
                    $idToken,
                    $findPaymentMethod->app_key
                );

                if (!isset($dataFromExecute['statusCode']) || $dataFromExecute['statusCode'] !== "0000") {
                    return response()->json([
                        'success' => false,
                        'message' => $dataFromExecute['statusMessage'] ?? 'Payment execution failed',
                        'data'    => $dataFromExecute,
                    ], 400);
                }

            //    $getTransactionInfo = $this->checkPaymentStatus($idToken , $findPaymentMethod->app_key ,  $paymentId );

            //    return $getTransactionInfo;

            $trxId       = $dataFromExecute['trxID'] ?? null;
            $amount      = $dataFromExecute['amount'] ?? null;
            $paymentTime = $dataFromExecute['paymentExecuteTime'] ?? null;

                $paymentRequest->update([
                    'payment_method' => 'bkash',
                    'status'         => $newStatus,
                    'payment_method_trx'=>$trxId,
                ]);

                DB::commit();

                $data = PaymentRequest::where('trxid', $paymentId)->first();

                merchantWebHook($data->reference);

                  $baseCallbackUrl = rtrim($data->callback_url, '/');

                    $callbackWithStatus = $baseCallbackUrl
                        . '?status=' . ($data->status == 1 ? 'success' : 'pending')
                        . '&reference=' . urlencode($data->reference)
                        . '&transaction_id=' . urlencode($data->transaction_id)
                        . '&payment_method=' . urlencode($data->payment_method);

                    return redirect()->away($callbackWithStatus);

            } catch (\Exception $e) {
                DB::rollBack();

                return response()->json([
                    'success' => false,
                    'message' => 'Database update failed',
                    'error'   => $e->getMessage(),
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid status received',
        ], 422);
    }








}
