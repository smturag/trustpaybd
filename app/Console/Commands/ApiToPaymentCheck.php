<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ApiToPaymentCheck extends Command
{
    /**
     * Command name.
     *
     * Run: php artisan app:api-to-payment-check
     */
    protected $signature = 'app:api-to-payment-check';

    /**
     * Description.
     */
    protected $description = 'Approve pending payment requests when amount, method and trxId match MFS API transaction.';

    public function handle(): int
    {
        // Get all pending requests that have a trx ID
        $requests = PaymentRequest::where('status', 0)
            ->whereNotNull('payment_method_trx')
            ->get();

        if ($requests->isEmpty()) {
            $this->info('No pending payment requests found.');
            return self::SUCCESS;
        }

        Log::info($requests);

        foreach ($requests as $request) {
            try {
                $trxId = trim((string) $request->payment_method_trx);

                if ($trxId === '') {
                    continue;
                }

                // Call API (using your helper)
                $res = checkMfsTransaction($trxId);

                if (($res['status'] ?? null) !== 'success') {
                    // Optional debug logging
                    Log::info('MFS API returned non-success for payment request', [
                        'payment_request_id' => $request->id,
                        'trxId'              => $trxId,
                        'message'            => $res['message'] ?? null,
                    ]);
                    continue;
                }

                $transaction = $res['data'] ?? null;
                if (!is_array($transaction)) {
                    continue;
                }

                // ---- SIMPLE MATCH LOGIC ----
                $amountMatches = (int) ($transaction['amount'] ?? 0) === (int) $request->amount;

                $methodMatches = isset($transaction['method'])
                    && strcasecmp($transaction['method'], (string) $request->payment_method) === 0;

                $trxMatches = isset($transaction['trxId'])
                    && strcasecmp($transaction['trxId'], (string) $request->payment_method_trx) === 0;

                // Only approve when method, amount & trx all match
                if (!($amountMatches && $methodMatches && $trxMatches)) {
                    continue;
                }

                // ---- UPDATE PAYMENT REQUEST ----
                $request->status       = 2; // Approved
                $request->accepted_by  = 'mfs_api';
                $request->payment_type = $transaction['customType'] ?? $request->payment_type;

                // Keep for traceability
                $request->from_number = $transaction['senderPhone'] ?? $request->from_number;

                if (!empty($transaction['receiverPhone']) && $transaction['receiverPhone'] !== 'UNKNOWN') {
                    $request->sim_id = $transaction['receiverPhone'];
                }

                if ($request->save()) {
                    Log::info('Payment request approved via MFS API', [
                        'payment_request_id' => $request->id,
                        'trxId'              => $trxId,
                    ]);

                    if (function_exists('paymentRequestApprovedBalanceHandler')) {
                        paymentRequestApprovedBalanceHandler($request->id, 'id');
                    }

                    if (function_exists('merchantWebHook')) {
                        merchantWebHook($request->reference);
                    }
                }

            } catch (\Throwable $e) {
                Log::error('Error in app:api-to-payment-check command', [
                    'payment_request_id' => $request->id ?? null,
                    'error'              => $e->getMessage(),
                ]);

                continue;
            }
        }

        $this->info('app:api-to-payment-check completed.');
        return self::SUCCESS;
    }
}
