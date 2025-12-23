<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use App\Models\PaymentMethod;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Service\Backend\BkashService;

class RealMerchantTransactionCheck extends Command
{
    protected $signature = 'app:real-merchant-transaction-check';
    protected $description = 'Live merchant API check for transaction success and further processing.';

    protected $bkashService;

    public function __construct()
    {
        parent::__construct();
        $this->bkashService = new BkashService();
    }

    public function handle()
    {
        $requests = PaymentRequest::where('status', 0)->get();

        foreach ($requests as $request) {
            $findPaymentMethod = PaymentMethod::where('sim_id', $request->sim_id)->first();

            if (!$findPaymentMethod) {
                Log::warning("PaymentMethod not found for sim_id: {$request->sim_id}");
                continue;
            }

            $tokenResponse = $this->bkashService->generateBkashToken($findPaymentMethod);

            if (!($tokenResponse['success'] ?? false)) {
                Log::error("Bkash token generation failed for sim_id: {$request->sim_id}");
                continue;
            }

            $idToken   = $tokenResponse['data']['id_token'];
            $paymentId = $request->payment_id; // assuming you store payment_id in DB

            // Execute payment
            $dataFromExecute = $this->bkashService->executePayment(
                $paymentId,
                $idToken,
                $findPaymentMethod->app_key
            );

            if (!isset($dataFromExecute['statusCode']) || $dataFromExecute['statusCode'] !== "0000") {
                Log::error("Payment execution failed for reference: {$request->reference}");
                continue;
            }

            $trxId       = $dataFromExecute['trxID'] ?? null;
            $amount      = $dataFromExecute['amount'] ?? null;
            $paymentTime = $dataFromExecute['paymentExecuteTime'] ?? null;

            if ($amount != $request->amount) {
                Log::warning("Amount mismatch for reference {$request->reference}: Expected {$request->amount}, got {$amount}");
                continue;
            }

            // Call webhook
            merchantWebHook($request->reference);

            paymentRequestApprovedBalanceHandler($request->id , 'id');

            $request->update([
                'payment_method'      => 'bkash',
                'status'              => 1,
                'payment_method_trx'  => $trxId,
                'payment_time'        => $paymentTime,
            ]);

            Log::info("Payment updated successfully for reference: {$request->reference}, trxID: {$trxId}");
        }

        return Command::SUCCESS;
    }
}
