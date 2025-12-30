<?php

namespace App\Console\Commands;

use App\Helpers\BalanceManagerConstant;
use App\Models\ServiceRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendTransactionToBalanceManager extends Command
{
    protected $signature = 'app:send-transaction-to-bm';
    protected $description = 'Send pending service requests to Balance Manager and store returned transaction IDs.';

    public function handle(): int
    {
        $webhookUrl = rtrim(config('app.url'), '/') . '/api/bm/mfs/webhook';
        $requests = ServiceRequest::query()
            ->whereNull('mfs_trnx_id')
            ->whereNotNull('mfs')
            ->whereIn('status', [0]) // pending/waiting/processing
            ->whereNotNull('number')
            ->where('amount', '>', 0)
            ->get();

        foreach ($requests as $request) {
            $payload = [
                'apiToken' => BalanceManagerConstant::token_key,
                'targetPhone' => $request->number,
                'amount' => (string) $request->amount,
                'reference' => $request->trxid,
                'mfs' => $request->mfs,
                'webhook_url' => $webhookUrl,
            ];

            try {
                $response = Http::timeout(10)->post('http://182.252.79.59:4003/send-transaction', $payload);

                if (!$response->successful()) {
                    Log::warning('send-transaction HTTP error', [
                        'service_request_id' => $request->id,
                        'status' => $response->status(),
                        'body' => $response->body(),
                    ]);
                    continue;
                }

                $data = $response->json();

                if (($data['success'] ?? false) && !empty($data['transactionId'])) {
                    $request->update([
                        'mfs_trnx_id' => $data['transactionId'],
                        'status' => 5,
                        'updated_at' => now(),
                    ]);
                } else {
                    Log::warning('send-transaction response missing transactionId', [
                        'service_request_id' => $request->id,
                        'response' => $data,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::error('send-transaction exception', [
                    'service_request_id' => $request->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        return Command::SUCCESS;
    }
}
