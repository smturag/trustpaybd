<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class RejectDepositRequest extends Command
{
    protected $signature = 'app:reject-payment-request';
    protected $description = 'Checks pending deposit requests and auto-rejects them after a certain time.';

    public function handle()
    {
        $requests = PaymentRequest::whereIn('status', [0, 4])
            ->where('created_at', '<=', Carbon::now()->subMinutes(60))
            ->orderBy('created_at', 'asc')
            ->limit(100)
            ->get();

        foreach ($requests as $request) {
            try {
                $request->update([
                    'status' => 3,
                    'note' => 'Auto Reject',
                    'merchant_balance_updated' => 1,
                ]);
                // Call webhook after rejection
                merchantWebHook($request->reference);

                Log::info("Payment request {$request->id} rejected successfully.");
            } catch (\Exception $e) {
                Log::error("Failed to reject payment request {$request->id}: " . $e->getMessage());
            }
        }

        $this->info("Processed {$requests->count()} payment requests.");
    }
}
