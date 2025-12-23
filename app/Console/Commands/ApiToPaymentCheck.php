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
        // Fetch payment requests using Eloquent
        $requests = PaymentRequest::where('status', 0)
            ->get();

        foreach ($requests as $request) {
            $checkTransactionResponse = checkTransaction($request->payment_method_trx);

            if ($checkTransactionResponse['status'] === 'success') {



                if($checkTransactionResponse['data']['amount'] == (int) $request->amount  &&
    strtolower($checkTransactionResponse['data']['method']) === strtolower($request->payment_method)){

                    if(isset($checkTransactionResponse['data']['receiverPhone']) && $checkTransactionResponse['data']['receiverPhone'] != 'UNKNOWN' ){
                            $request->sim_id = $checkTransactionResponse['data']['receiverPhone'];
                    }

                    $request->accepted_by= "mfs_api";
                    $request->status = 1;
                    $request->from_number = $checkTransactionResponse['data']['senderPhone'];
                    $request->save();

                    paymentRequestApprovedBalanceHandler($request , 'id');
                    merchantWebHook($request->reference);

                }

            }
        }
    }
}
