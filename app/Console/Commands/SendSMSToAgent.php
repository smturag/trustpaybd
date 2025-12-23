<?php

namespace App\Console\Commands;

use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use Faker\Provider\ar_EG\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SendSMSToAgent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-s-m-s-to-agent';

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

        // $getTime = env('REJECT_TIME_MIN');
        $minutes = Carbon::now()->subMinutes(1);
        $data = PaymentRequest::where('status', 0)->whereNotNull('payment_method')->whereNotNull('payment_method_trx')->where('created_at', '<', Carbon::now()->subMinutes(1))->get();

        if ($data) {
            foreach ($data as $item) {
                if ($item->send_sms == null) {
                    $phone = $item->cust_phone;
                    $memeberCode = $item->agent;
                    $method = $item->payment_method;
                    $trxid = $item->payment_method_trx;
                    $amount = $item->amount;
                    SendCronPaymentSms($memeberCode, $method, $phone, $trxid, $amount);
                    DB::table('payment_requests')
                        ->where('request_id', $item->request_id)
                        ->update(['send_sms' => 1]);
                } elseif ($item->send_sms == 1 && $item->created_at < Carbon::now()->subMinutes(5)) {
                    $phone = $item->cust_phone;
                    $memeberCode = $item->agent;
                    $method = $item->payment_method;
                    $trxid = $item->payment_method_trx;
                    $amount = $item->amount;
                    SendCronPaymentSms($memeberCode, $method, $phone, $trxid, $amount);
                    DB::table('payment_requests')
                        ->where('request_id', $item->request_id)
                        ->update(['send_sms' => 2]);
                }
            }
        }

        $mfsData = ServiceRequest::whereNull('agent_id')->where('status', 0)->where('created_at', '<', Carbon::now()->subMinutes(1))->get();

        if ($mfsData) {
            foreach ($mfsData as $mfsItem) {
                if ($mfsItem->send_sms == null) {
                    $phone = $mfsItem->number;
                    $method = $mfsItem->mfs;
                    $amount = $mfsItem->amount;
                    sendServiceRequestSms($phone, $method, $amount);
                    ServiceRequest::where('id', $mfsItem->id)->update(['send_sms' => 1]);


                } elseif ($mfsItem->send_sms == 1 && $mfsItem->created_at < Carbon::now()->subMinutes(5)) {
                    $phone = $mfsItem->number;
                    $method = $mfsItem->mfs;
                    $amount = $mfsItem->amount;
                    sendServiceRequestSms($phone, $method, $amount);


                    ServiceRequest::where('id', $mfsItem->id)->update(['send_sms' => 2]);
                }
            }
        }

        $mfsAgentData = ServiceRequest::whereNotNull('agent_id')
            ->whereIn('status', [0, 1, 5, 6])
            ->where('created_at', '<', Carbon::now()->subMinutes(1))
            ->get();

        if ($mfsAgentData) {
            foreach ($mfsAgentData as $mfsItem) {
                if ($mfsItem->agent_send_sms == null) {
                    $phone = $mfsItem->number;
                    $method = $mfsItem->mfs;
                    $amount = $mfsItem->amount;
                    $agent = User::find($mfsItem->agent_id);
                    $agentNumber = $agent->mobile;
                    if ($agentNumber) {
                        sendFCMNotificationAgent($agent->fcm_token);
                        sendServiceRequestSmsAgent($phone, $method, $amount, $agentNumber);
                        ServiceRequest::where('id', $mfsItem->id)->update(['agent_send_sms' => 1]);
                    }
                } elseif ($mfsItem->agent_send_sms == 1 && $mfsItem->created_at < Carbon::now()->subMinutes(5)) {
                    $phone = $mfsItem->number;
                    $method = $mfsItem->mfs;
                    $amount = $mfsItem->amount;
                    $agent = User::find($mfsItem->agent_id);
                    $agentNumber = $agent->mobile;
                    if ($agentNumber) {
                        sendFCMNotificationAgent($agent->fcm_token);
                        sendServiceRequestSmsAgent($phone, $method, $amount, $agentNumber );
                        ServiceRequest::where('id', $mfsItem->id)->update(['agent_send_sms' => 2]);
                    }
                }
            }
        }
    }
}
