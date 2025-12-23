<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoAssignBMToAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-assign-b-m-to-agent';

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
        $data = DB::table('service_requests')->whereNull('agent_id')->where('status', 0)->get();

        // Log::info($data);

        if ($data) {
            foreach ($data as $item) {
                $getRandomActiveUserId = getRandom($item->amount, $item->mfs);

                if ($getRandomActiveUserId) {

                    $memberRate = calculateAmountFromRateForMember($item->mfs, 'P2A', 'withdraw', $getRandomActiveUserId, $item->amount);

                    ServiceRequest::find($item->id)->update([
                        'status' => $getRandomActiveUserId ? 1 : 0,
                        'agent_id' => $getRandomActiveUserId ? $getRandomActiveUserId : $item->agent_id,
                        'partner' => $memberRate['member']['partner_id'],
                        'partner_fee' => $memberRate['member']['fee_amount'],
                        'partner_commission' => $memberRate['member']['commission_amount'],
                        'user_fee' => $memberRate['agent']['fee_amount'],
                        'user_commission' => $memberRate['agent']['commission_amount'],
                        'partner_main_amount' => $memberRate['member']['net_amount'],
                        'user_main_amount' => $memberRate['agent']['net_amount']
                    ]);

                    $agent = User::find($getRandomActiveUserId);

                    DB::table('transactions')->insert([
                        'user_id' => $getRandomActiveUserId,
                        'amount' => $item->amount,
                        'charge' => 0,
                        'old_balance' => $agent->balance,
                        'trx_type' => 'debit',
                        'trx' => $item->trxid,
                        'details' => 'Customer api payment using ' . $item->mfs,
                        'user_type' => 'agent',
                        'wallet_type' => 'main',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
