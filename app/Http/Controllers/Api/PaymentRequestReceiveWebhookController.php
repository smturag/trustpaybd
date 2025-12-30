<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentRequestReceiveWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->validate([
            'transactionId' => 'required|string',
            'status' => 'required|numeric',
            'message' => 'nullable|string',
            'amount' => 'sometimes|numeric',
            'method' => 'sometimes|string',
            'customType' => 'sometimes|string',
            'senderPhone' => 'sometimes|string',
            'receiverPhone' => 'sometimes|string',
            'reference' => 'sometimes|string',
        ]);

        $paymentRequest = PaymentRequest::where('payment_method_trx', $data['transactionId'])
            ->where('status', 0)
            ->where('amount',$data['transactionId'])
            ->where('payment_method', $data['method'])
            ->where('payment_type', $data['customType'])
            ->first();

        if (!$paymentRequest) {
            Log::warning('Payment webhook transaction not found', ['transactionId' => $data['transactionId']]);
            return response()->json(['status' => false, 'message' => 'Payment request not found'], 404);
        }

        $incomingStatus = (int) $data['status'];

        if (empty($paymentRequest->payment_method_trx)) {
            $paymentRequest->payment_method_trx = $data['transactionId'];
        }
        if (!empty($data['method'])) {
            $paymentRequest->payment_method = $data['method'];
        }
        if (!empty($data['customType'])) {
            $paymentRequest->payment_type = $data['customType'];
        }
        if (!empty($data['receiverPhone']) && $data['receiverPhone'] !== 'UNKNOWN') {
            $paymentRequest->sim_id = $data['receiverPhone'];
        }
        if (!empty($data['senderPhone'])) {
            $paymentRequest->from_number = $data['senderPhone'];
        }

        // Mirror ApiToPaymentCheck comparison for success handling
        $matchesAmount = isset($data['amount'])
            ? (int) $data['amount'] === (int) $paymentRequest->amount
            : true; // if no amount provided, do not block success
        $matchesMethod = isset($data['method'])
            ? strtolower($data['method']) === strtolower((string) $paymentRequest->payment_method)
            : true;
        $matchesType = isset($data['customType'])
            ? strtolower($data['customType']) === strtolower((string) $paymentRequest->payment_type)
            : true;

        // Derive status: only mark success when payload matches expected values
        $newStatus = $paymentRequest->status; // default: unchanged
        if (in_array($incomingStatus, [2, 3], true) && $matchesAmount && $matchesMethod && $matchesType) {
            $newStatus = 2;
        } elseif (in_array($incomingStatus, [4, 6], true)) {
            $newStatus = 3; // rejected/failed
        } elseif ($incomingStatus === 0 || $incomingStatus === 1 || $incomingStatus === 5) {
            $newStatus = 0; // pending/waiting/processing
        }

        $paymentRequest->status = $newStatus;
        if (array_key_exists('message', $data)) {
            $paymentRequest->note = $data['message'];
        }
        $paymentRequest->accepted_by = 'mfs_api_webhook';
        $paymentRequest->updated_at = now();
        $paymentRequest->save();

        if ($newStatus === 2) {
            paymentRequestApprovedBalanceHandler($paymentRequest->id, 'id');
            merchantWebHook($paymentRequest->reference);
        }

        return response()->json(['status' => true, 'message' => 'Webhook processed']);
    }
}
