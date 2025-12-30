<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ServiceRequestReceiveWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $data = $request->validate([
            'transactionId' => 'required|string',
            'status' => 'required|numeric',
            'message' => 'nullable|string',
            'reference' => 'sometimes|string',
        ]);

        // Look up by BM transaction id first, fall back to our trxid/reference if needed
        $serviceRequest = ServiceRequest::where('mfs_trnx_id', $data['transactionId'])
            ->orWhere('trxid', $data['transactionId'])
            ->orWhere('trxid', $data['reference'] ?? null)
            ->first();

        if (!$serviceRequest) {
            Log::warning('BM webhook transaction not found', ['transactionId' => $data['transactionId']]);
            return response()->json(['status' => false, 'message' => 'Service request not found'], 404);
        }

        $incomingStatus = (int) $data['status'];
        $statusMap = [
            0 => 0, // pending
            1 => 1, // waiting
            2 => 2, // success
            3 => 3, // approved
            4 => 4, // rejected
            5 => 5, // processing
            6 => 6, // failed
        ];

        $newStatus = $statusMap[$incomingStatus] ?? $serviceRequest->status;

        // Persist BM transaction id if it was missing
        if (empty($serviceRequest->mfs_trnx_id)) {
            $serviceRequest->mfs_trnx_id = $data['transactionId'];
        }

        $serviceRequest->status = $newStatus;
        if (array_key_exists('message', $data)) {
            $serviceRequest->msg = $data['message'];
        }
        $serviceRequest->save();

        if (in_array($newStatus, [2, 3], true)) {
            serviceRequestApprovedBalanceHandler($serviceRequest->id);
        }

        return response()->json(['status' => true, 'message' => 'Webhook processed']);
    }
}
