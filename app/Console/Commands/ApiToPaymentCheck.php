<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApiToPaymentCheck extends Command
{
    protected $signature = 'app:api-to-payment-check';
    protected $description = 'Checks and updates payment requests based on balance manager transactions.';

    public function handle()
    {
        // Fetch pending payment requests
        $requests = PaymentRequest::where('status', 0)->whereNotNull('payment_method_trx')->get();

        foreach ($requests as $request) {

            $fromNumber = $request->balanceManager->mobile ?? $request->from_number ?? '-';

            // ✅ Make sure trxId is not empty and trimmed
            $trxId = trim((string) $request->payment_method_trx);

            if ($trxId === '') {
                Log::warning('Skipping PaymentRequest: empty trx id', [
                    'payment_request_id' => $request->id,
                    'reference'          => $request->reference ?? null,
                ]);
                continue;
            }

            Log::info('Checking transaction for request', [
                'payment_request_id' => $request->id,
                'trxId'              => $trxId,
            ]);

            $checkTransactionResponse = checkTransaction($trxId);

            Log::info('checkTransaction response', [
                'payment_request_id' => $request->id,
                'response'           => $checkTransactionResponse,
            ]);

            if ($checkTransactionResponse['status'] === 'success') {

                $data = $checkTransactionResponse['data'];

                // ✅ Make comparison a bit safer (API may return string)
                if (
                    (int) $data['amount'] === (int) $request->amount &&
                    strtolower($data['method']) === strtolower($request->payment_method) &&
                    strtolower($data['customType']) === strtolower($request->payment_type)
                ) {
                    if (isset($data['receiverPhone']) && $data['receiverPhone'] !== 'UNKNOWN') {
                        $request->sim_id = $data['receiverPhone'];
                    }

                    $request->accepted_by  = "mfs_api";
                    $request->status       = 2;
                    $request->from_number  = $data['senderPhone'];
                    $request->updated_at   = now();
                    $request->save();

                    paymentRequestApprovedBalanceHandler($request->id, 'id');
                    merchantWebHook($request->reference);
                }
            }
        }
    }
}
