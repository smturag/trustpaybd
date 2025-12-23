<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckPaymentRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-payment-request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $getTime = env('REJECT_TIME_MIN');
        // $this->info('start Api Command');
        $fifteenMinutesAgo = Carbon::now()->subMinutes($getTime);
        $data = PaymentRequest::where('created_at', '<', $fifteenMinutesAgo)->where('status', '=', 0)->get();
        if ($data) {
            foreach ($data as $request) {
                PaymentRequest::where('id', $request->id)
                    ->whereNull('payment_method')
                    ->update([
                        'status' => 3,
                    ]);
                merchantWebHook($request->reference);
            }
        }
    }
}
