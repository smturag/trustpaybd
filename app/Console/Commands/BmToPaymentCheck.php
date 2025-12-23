<?php

namespace App\Console\Commands;

use App\Models\Modem;
use App\Models\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BmToPaymentCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:bm-to-payment-check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and updates payment requests based on balance manager transactions.';

    /**
     * Execute the console command.
     */
    public function handle()
    {


        $query1 = DB::table('payment_requests as pr')
            ->join('balance_managers as bm', 'pr.payment_method_trx', '=', 'bm.trxid')
            ->whereNotNull('pr.payment_method')
            ->whereNotNull('pr.payment_method_trx')
            ->where('pr.status', 0)
            ->whereColumn('bm.amount', '!=', 'pr.amount') // Get only where amounts don't match
            ->select('pr.*', 'bm.sim as bm_sim', 'bm.agent as bm_agent', 'bm.type as bm_type', 'bm.status as bm_status', 'bm.member_code as bm_member_code', 'bm.amount as bm_amount', 'pr.amount as pr_amount');

        // Fetching the results
        $results1 = $query1->get();

        if ($results1->isNotEmpty()) {
            // Check if results are not empty
            foreach ($results1 as $item) {
               $check = DB::table('payment_requests')
                    ->where('id', $item->id)
                    ->update([
                        'status' => 0,
                        'note' => 'invalid amount',
                        'updated_at' => now(),
                        'balance_updated' => 1,
                        'accepted_by' => 'Automatic',
                    ]);

                    $payment = DB::table('payment_requests')
                        ->where('id', $item->id)
                        ->first();
                    paymentRequestApprovedBalanceHandler($item->id , 'id');
                    merchantWebHook($payment->reference);


                    Log::info("PaymentRequest {$item->id} marked invalid. PR Amount: {$item->pr_amount}, BM Amount: {$item->bm_amount}");
            }
        }

        // Query to find matching payment requests and balance manager transactions
        $query = DB::table('payment_requests as pr')
            ->join('balance_managers as bm', 'pr.payment_method_trx', '=', 'bm.trxid')
            ->where(function ($query) {
                $query->whereIn('bm.status', [20, 22, 33, 55, 77])
                    ->orWhereNull('bm.status'); // ✅ include null status
            })
            ->whereNotNull('pr.payment_method')
            ->whereNotNull('pr.payment_method_trx')
            ->where('pr.status', 0)
            ->whereColumn('bm.amount', 'pr.amount')
            ->select('pr.*', 'bm.sim as bm_sim', 'bm.agent as bm_agent', 'bm.type as bm_type', 'bm.status as bm_status', 'bm.member_code as bm_member_code ');


      $results = $query->get();


        // Processing the results
        if ($results->isNotEmpty()) {


            foreach ($results as $item) {
                $paymentTrxId = generateInvoiceNumber(6);
                $mekeMethod = null;
                $checkStatusArray = [20, 22,33,55,77];
                $checkSuccessStatus = false;

                // if ($item->bm_type == 'bkcashout') {
                //     $mekeMethod = 'bkash';
                // } elseif ($item->bm_type == 'ngcashout') {
                //     $mekeMethod = 'nagad';
                // }elseif($item->bm_type == 'bkRC') {
                //     $mekeMethod = 'bkash';
                // }elseif($item->bm_type == 'rccashout') {
                //     $mekeMethod = 'rocket';
                // }elseif($item->bm_type == 'upcashout') {
                //     $mekeMethod = 'upay';
                // }

                $type = strtolower((string) $item->bm_type);
                $typeMap = [
                    'bkcashout' => 'bkash',
                    'bkcashin' => 'bkash',
                    'bkb2b' => 'bkash',
                    'bkrc' => 'bkash',
                    'bkpayment' => 'bkash',
                    'ngcashout' => 'nagad',
                    'ngcashin' => 'nagad',
                    'ngb2btr' => 'nagad',
                    'ngb2brc' => 'nagad',
                    'ngpayment' => 'nagad',
                    'ngrc' => 'nagad',
                    'rccashout' => 'rocket',
                    'rcrc' => 'rocket',
                    'rcpayment' => 'rocket',
                    'upcashout' => 'upay',
                    'uprc' => 'upay',
                ];
                $mekeMethod = $typeMap[$type] ?? null;

                if (is_null($item->bm_status) || in_array($item->bm_status, $checkStatusArray)) {
                    $checkSuccessStatus = true;
                }

                $previousStatus = $item->status;

                $checkExistTransaction = DB::table('payment_requests')
                ->where('payment_method_trx', $item->payment_method_trx)
                ->whereIn('status',[1,2])
                ->first();



                if ($mekeMethod && $checkExistTransaction == null ) {
                    $agent = Modem::where('sim_number', $item->bm_sim)->first();
                    $paymentTrxId = generateInvoiceNumber(6);



                    DB::table('payment_requests')
                        ->where('id', $item->id)
                        ->update([
                            'status' => $checkSuccessStatus == true ? 2 : $previousStatus,
                            'payment_method' => $mekeMethod,
                            'sim_id' => $item->bm_sim,
                            'agent' => $agent->member_code,
                            'updated_at' => now(),
                            'balance_updated' => 1,
                            'accepted_by' => 'Automatic',
                            'trxid' => $paymentTrxId,
                        ]);

                         $payment = DB::table('payment_requests')
                                ->where('id', $item->id)
                                ->first();
                    merchantWebHook($payment->reference);
                    paymentRequestApprovedBalanceHandler($item->id , 'id');
                }
                return 1; // Assuming return 1 signifies success
            }
        }
    }
}
