<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\smsInbox;
use App\Models\Modem;
use App\Models\User;
use App\Models\Merchant;
use App\Models\BalanceManager;
use App\Models\SmsSetting;
use Illuminate\Support\Facades\Http;

class SmsModem extends Controller
{
    public function index(Request $request)
    {
        $membercode = $request->membercode;
        $oparetor = $request->oparator;
        $simid = $request->simid;
        $deviceid = $request->deviceid;
        $sim_number = $request->sumnumber ? $request->sumnumber : $request->simnumber;

        $token = Str::random(32);

        $uptime = time();

        DB::beginTransaction();

        if (empty($membercode)) {
            return response()->json([
                'message' => 'member id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($deviceid)) {
            return response()->json([
                'message' => 'Device id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($sim_number)) {
            return response()->json([
                'message' => 'Sim Number empty',
                'status' => 'not',
                'success' => false
            ]);
        }


        $userfound = User::where('user_type', 'agent')
            ->where('member_code', $membercode)
            ->where('status', 1)
            ->count();

        if ($userfound == 0) {

            return response()->json([
                'message' => 'User id Not Found',
                'status' => 'not',
                'success' => false
            ]);

        }


        $mtfound = Modem::where('type', 'android')
            ->where('deviceid', $deviceid)
            ->where('member_code', $membercode)
            ->count();

        if ($mtfound == 0) {


            $simid = str_replace(array("[", "]", " "), "", $simid);
            $sim_number = str_replace(array("[", "]", " "), "", $sim_number);

            $modeminsert = Modem::create([
                'type' => 'android',
                'member_code' => $membercode ? $membercode : 'not',
                'deviceid' => $deviceid ? $deviceid : 'not',
                'operator' => $oparetor ? $oparetor : 'not',
                'sim_id' => $simid ? $simid : 'not',
                'sim_number' => $sim_number,
                'modem_details' => $modem_details,
                'token' => $token,
                'up_time' => $uptime,
                'status' => 1
            ]);
        } else {


            $mtupdate = Modem::where('type', 'android')
                ->where('deviceid', $deviceid)
                ->where('member_code', $membercode)
                ->update(['up_time' => $uptime, 'operator' => $oparetor, 'sim_number' => $sim_number, 'token' => $token]);

            //$statsusinsert = true;

        }


        if (($modeminsert) || ($mtupdate)) {

            DB::commit();

            $statsusinsert = true;

        } else {
            DB::rollback();
            $statsusinsert = false;
        }

        return response()->json([
            'message' => 'successful login modem',
            'username' => $membercode,
            'token' => $token,
            'status' => 'success',
            'success' => $statsusinsert
        ]);
    }

    public function smsin(Request $request)
    {
        $getoperator_name = $request->operator;
        $msg = $request->smsbody;
        $sendcode = $request->sender;
        $simid = $request->simid;
        $deviceid = $request->deviceid;
        $membercode = $request->membercode;
        $simslot = $request->simslot;
        $token = $request->token;
        $request_time = $request->smstime;
        $simnumber = $request->simnumber;

        $operator = strtolower($getoperator_name);
        $getoperator = str_replace(array("[", "]", " "), "", $operator);

        $simnumber = str_replace(array("[", "]", " "), "", $simnumber);

        $uptime = time();


        if ($simslot == 2) {
            $tersimslot = 1;
            $dbinsertsim = 2;
        } else {
            $tersimslot = 0;
            $dbinsertsim = 1;
        }


        //    if($simslot=='-1'){
//$tersimslot = 0;
        //     $dbinsertsim = 1;
        // }else {
//$tersimslot = 2;
        //     $dbinsertsim = 2;
        //}


        // DB::beginTransaction();

        if (empty($membercode)) {
            return response()->json([
                'message' => 'member id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($deviceid)) {
            return response()->json([
                'message' => 'Device id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($token)) {
            return response()->json([
                'message' => 'Token id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($msg)) {
            return response()->json([
                'message' => 'Message id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($sendcode)) {
            return response()->json([
                'message' => 'Sendcode id empty',
                'status' => 'not',
                'success' => false
            ]);
        }


        // modem insert

        $mtfound = Modem::where('type', 'android')
            ->where('deviceid', $deviceid)
            ->where('member_code', $membercode)
            ->count();

        if ($mtfound == 0) {
            $modeminsert = Modem::create([
                'type' => 'android',
                'member_code' => $membercode ? $membercode : 'not',
                'deviceid' => $deviceid ? $deviceid : 'not',
                'operator' => $getoperator ? $getoperator : 'not',
                'sim_id' => $simnumber ? $simnumber : 'not',
                'sim_number' => $simnumber,
                'modem_details' => $modem_details,
                'token' => $token,
                'up_time' => $uptime,
                'status' => 1
            ]);
        } else {
            // $mtupdate = Modem::where('type', 'android')
            //     ->where('deviceid', $deviceid)
            //     ->where('member_code', $membercode)
            //     ->update(['up_time' => $uptime, 'operator' => $oparetor, 'sim_number' => $sim_number, 'token' => $token]);
        }

        $getsms_time = date('Y-m-d H:i:s', $request_time / 1000);
        $idate = date('Y-m-d');

        $aget_udata = User::where('member_code', $membercode)->first();

        $user_id = $aget_udata->id;
        $partner_id = $aget_udata->partner;
        $dso_id = $aget_udata->dso;


        $modem_data = Modem::where('type', 'android')
            ->where('deviceid', $deviceid)
            ->where('simslot', $dbinsertsim)
            ->where('member_code', $membercode)
            ->first();

        $modem_id = $modem_data->id;
        $sim_number = $modem_data->sim_number;
        $merchant_code = $modem_data->merchant_code;

        $sms_exist = smsInbox::where('sms', $msg)->count();

        if ($sms_exist == 0) {
            //$agent_id = $aget_udata->id;

            $smsinbxcrt = smsInbox::create([
                'sender' => $sendcode,
                'sms' => $msg,
                'member_code' => $membercode ? $membercode : 'not_member',
                'modem' => $modem_id,
                'sim_slot' => $dbinsertsim ? $dbinsertsim : $simslot,
                'device_id' => $deviceid,
                'sms_time' => $getsms_time,
                'sim_number' => $sim_number ? $sim_number : $simnumber,
                'merchant_id' => $merchant_code ? $merchant_code : '-1',
                'token' => $sim,
                'partner' => $partner_id ? $partner_id : '-1',
                'dso' => $dso_id ? $dso_id : '-1',
                'agent' => $user_id ? $user_id : '-1',
            ]);

            $mtupdate = Modem::where('type', 'android')
                ->where('deviceid', $deviceid)
                ->where('member_code', $membercode)
                ->update(['up_time' => $uptime]);

        }


        if (empty($simnumber) || empty($sim_number)) {
            return response()->json([
                'message' => 'Sim number id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

//        else {
//            $sim_number = 'testagentnumber';
//        }

        $smsbodytype = '';

        $trxtype = '';


        if ($sendcode == '4917632070558') {
            $sendcode = "NAGAD";
        } elseif ($sendcode == '8801844532630') {
            $sendcode = "NAGAD";
        }

//8801844532630  4917632070558//

        /// balance manager full function start here

        if ($sendcode == 'NAGAD' or $sendcode == 'Nagad' or $sendcode == 'bKash' or $sendcode == '16216' or  $sendcode == 'upay') {
            $responseValue = explode(':', $msg);

            //Cash Out Received.Amount: Tk 2100.00Customer: 01672151119TxnID: 71NHMIC5Comm: Tk 8.61Balance: Tk 110060.8121/01/2023 18:30
            //Cash In Successful.Amount: Tk 3000.00 Customer: 01827147299 TxnID: 71R8DC4R Comm: Tk 12.30 Balance: Tk 10980.99 12/03/2023 22:06

            /// fake msg
            //Cash Out ReceivedAmount: Tk 1000.00Customer:01633768651TxnID:7200XCJBComm: Tk 04.10Balance: Tk 65981.1025/06/2023 17:25

            if (str_contains($responseValue[0], 'Cash Out') && ($sendcode == 'NAGAD' || $sendcode == 'Nagad')) {
                $smsbodytype = 'ngcashout';
                $trxtype = 'cashout';
                $baltype = 'plus';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Tk ', 'Customer')));
                $number = trim(getStringBetween($msg, 'Customer:', 'TxnID'));
                $trxid = trim(getStringBetween($msg, 'TxnID:', 'Comm:'));

                $comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = floatval(str_replace(',', '', $comm));

                $lastbal = trim(getStringBetween($msg, 'Balance: Tk ', ''));
                $lastbal = substr($lastbal, 0, -16);
                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            // nagad cashin

            if (str_contains($responseValue[0], 'Cash In') && ($sendcode == 'NAGAD' || $sendcode == 'Nagad')) {
                $smsbodytype = 'ngcashin';
                $baltype = 'minus';
                $trxtype = 'cashin';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Tk ', 'Customer')));
                $number = trim(getStringBetween($msg, 'Customer: ', 'TxnID'));
                $trxid = trim(getStringBetween($msg, 'TxnID: ', 'Comm:'));

                $comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = floatval(str_replace(',', '', $comm));

                $lastbal = trim(getStringBetween($msg, 'Balance: Tk ', ''));
                $lastbal = substr($lastbal, 0, -16);
                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }


            // naga B2B
            // B2B Transfer Successful. Amount: Tk 3000.00 Receiver: 01810030342 TxnID: 71R9VMKW Balance: Tk 43462.02 13/03/2023 17:30

            if (str_contains($responseValue[0], 'B2B Transfer') && ($sendcode == 'NAGAD' || $sendcode == 'Nagad')) {
                $smsbodytype = 'ngB2BTR';
                $baltype = 'minus';
                $trxtype = 'B2B_transfer';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Tk ', 'Receiver')));
                $number = trim(getStringBetween($msg, 'Receiver: ', 'TxnID'));
                $trxid = trim(getStringBetween($msg, 'TxnID: ', 'Balance:'));

                // $comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = 00;

                $lastbal = trim(getStringBetween($msg, 'Balance: Tk ', ''));
                $lastbal = substr($lastbal, 0, -16);
                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }


            // nagad b2b receive
            //B2B Received. Amount: Tk 3000.00 Sender: 01810030342 TxnID: 71R9OL1C Balance: Tk 16202.72 13/03/2023 16:05

            if (str_contains($responseValue[0], 'B2B Received') && ($sendcode == 'NAGAD' || $sendcode == 'Nagad')) {
                $smsbodytype = 'ngB2BRC';
                $baltype = 'plus';
                $trxtype = 'B2B_receive';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Tk ', 'Sender')));
                $number = trim(getStringBetween($msg, 'Sender: ', 'TxnID'));
                $trxid = trim(getStringBetween($msg, 'TxnID: ', 'Balance:'));

                // $comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = 00;

                $lastbal = trim(getStringBetween($msg, 'Balance: Tk ', ''));
                $lastbal = substr($lastbal, 0, -16);
                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }


            //Cash Out Tk 785.00 from 01841721504 successful. Fee Tk 0.00. Balance Tk 121,710.33. TrxID AA24AJJH6I at 02/01/2023 22:05

            //Congratulations! You have received Cashback Tk 1.00. Balance Tk 91,950.74. TrxID AC889WE2G0 at 08/03/2023 10:50


            if (str_contains($responseValue[0], 'Cash Out') && $sendcode == 'bKash') {
                $smsbodytype = 'bkcashout';
                $baltype = 'plus';
                $trxtype = 'cashout';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Tk ', ' from')));
                $number = trim(getStringBetween($msg, 'from ', ' success'));
                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                $getcom = $amount * 0.4 / 100;
                //$comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = $getcom;

                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            //Cash In Tk 2,000.00 to 01856600204 successful. Fee Tk 0.00. Balance Tk 2,851.16. TrxID AC829VTZTE at 08/03/2023 10:37
            if (str_contains($responseValue[0], 'Cash In') && $sendcode == 'bKash') {
                $smsbodytype = 'bkcashin';
                $baltype = 'minus';
                $trxtype = 'cashin';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Cash In Tk ', ' to')));
                $number = trim(getStringBetween($msg, 'to ', ' success'));
                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                $getcom = $amount * 0.4 / 100;
                $comm = $getcom;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }


            // B2B Transfer Tk 2,000.00 to 01704172631 successful. Fee Tk 0.00. Balance Tk 7,521.24. TrxID ACD7FJDL8B at 13/03/2023 16:48

            if (str_contains($responseValue[0], 'B2B Transfer') && $sendcode == 'bKash') {
                $smsbodytype = 'bkB2B';
                $baltype = 'minus';
                $trxtype = 'B2B_transfer';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Transfer Tk ', ' to')));
                $number = trim(getStringBetween($msg, 'to ', ' success'));

                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                //$getcom = $amount*0.4/100;
                $comm = 00;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            // You have received Tk 2,000.00 from 01704172631. Fee Tk 0.00. Balance Tk 2,201.51. TrxID ACA8CBMVZ2 at 10/03/2023 16:36

            if (str_contains($responseValue[0], 'received') && $sendcode == 'bKash') {
                $smsbodytype = 'bkRC';
                $baltype = 'plus';
                $trxtype = 'B2B_receive';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'received Tk ', ' from')));
                $number = trim(getStringBetween($msg, 'from ', ' Fee'));

                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                //$getcom = $amount*0.4/100;
                $comm = 00;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            //Rocket B2C: Cash-Out from A/C: 017559717268 Tk500.00 Comm:Tk2.10; A/C Balance: Tk3,741.39.TxnId: 3478851618 Date:22-JAN-23 10:08:31 am. Download https://bit.ly/nexuspa
            if (str_contains($responseValue[1], 'Cash-Out') && $sendcode == '16216') {
                $smsbodytype = 'rccashout';
                $baltype = 'plus';
                $trxtype = 'cashout';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, ' Tk', ' Comm')));
                $number = trim(getStringBetween($msg, 'A/C: ', ' Tk'));
                $trxid = trim(getStringBetween($msg, 'TxnId: ', ' Date:'));
                $lastbal = trim(getStringBetween($msg, 'Balance: Tk', '.TxnId:'));
                $lastbal = floatval(str_replace(',', '', $lastbal));

                $rocket_date = getStringBetween($msg, 'Date:', '. Download');
                $sms_date = Carbon::createFromFormat("d-M-y h:i a", $rocket_date)->format("Y-m-d H:i");
            }

            //upday cashout :  You have received Cash-out of Tk. 3940.00 from 01576987382. Comm: TK. 16.1343. Balance Tk. 6568.82. TrxID 01JYG4GET7 at 24/06/2025 10:52.

            if (str_contains($msg, 'Cash-out') && $sendcode == 'upay') {
                    $smsbodytype = 'upcashout';
                    $baltype = 'plus';
                    $trxtype = 'cashout';

                    // Extract amount
                    $amount = floatval(getStringBetween($msg, 'Cash-out of Tk. ', ' from'));

                    // Extract sender number
                    $number = getStringBetween($msg, 'from ', '. Comm');

                    // Extract transaction ID
                    $trxid = getStringBetween($msg, 'TrxID ', ' at');

                    // Extract balance
                    $lastbal = floatval(getStringBetween($msg, 'Balance Tk. ', '. TrxID'));

                   // Extract and format date
                    $dateStr = getStringBetween($msg, 'at ', '.'); // e.g., "24/06/2025 10:52"
                    
                    // Clean the date string (remove non-ASCII characters like &nbsp;)
                    $dateStr = preg_replace('/[^\x20-\x7E]/u', ' ', $dateStr);
                    $dateStr = trim($dateStr);

                    // Parse using exact format (handle leading zeros)
                    $sms_date = Carbon::createFromFormat('d/m/Y H:i', $dateStr)->format('Y-m-d H:i');

                }



            $reqnumber = (int)filter_var($number, FILTER_SANITIZE_NUMBER_INT);
            $numberlenth = strlen($reqnumber);

            $numbervalid = 'no';
            if ($numberlenth == 10) {
                $regexp = "/^[0-9]{10}$/";
                $reqnumber = "0" . $reqnumber;
            } else {
                $regexp = "/^[0-9]{11}$/";
            }

            if (preg_match($regexp, $reqnumber)) {

                $numbervalid = 'yes';

            }

            if (empty($reqnumber)) {

                return response()->json([
                    'message' => 'message success',
                    'status' => 'success',
                    'success' => true
                ], 200);


            }


            $bmcount = BalanceManager::where('sms_body', $msg)->count();

            if ($bmcount == 0) {

                //  $pendingstatus = [20,77];

                $getbalancedata = BalanceManager::where('sender', $sendcode)
                    ->where('sim', $sim_number)
                    ->where('deviceid', $deviceid)
                    ->where('simslot', $dbinsertsim)
                    ->orderBy('id', 'desc')
                    ->first();

                $getbmlastbaldb = $getbalancedata->lastbal;

                $getbmlastbal = intval($getbmlastbaldb);

                if ($baltype == 'plus') {

                    $currentlastbal = $getbmlastbal + $amount + $comm;
                    $oldbalance = $getbmlastbaldb - ($amount + $comm);

                } else {
                    $currentlastbal = $getbmlastbal - ($amount + $comm);
                    $oldbalance = $getbmlastbaldb + $amount + $comm;
                }


                $courrentbmbalnumber = intval($currentlastbal);

                $defranceamount = $lastbal - $currentlastbal;


                $defran = intval($lastbal) - intval($currentlastbal);

                if ($courrentbmbalnumber == intval($lastbal)) {

                    $bmstst = 20;

                } elseif ($defran >= 1 && $defran <= 15) {

                    $bmstst = 20;

                } elseif ($defran >= 16 && $defran <= 500) {

                    $bmstst = 33;


                } elseif ($defran >= 501 && $defran <= 25000000) {

                    $bmstst = 55;


                } elseif (empty($getbmlastbal)) {

                    $bmstst = 10;
                } elseif (($trxtype == 'cashin') || ($trxtype == 'B2B_transfer') || ($trxtype == 'B2B_receive')) {

                    $bmstst = 20;
                }


                //if (!empty($smsbodytype) && !empty($amount)) {

                $bmanger = BalanceManager::create([
                    'request_time' => $getsms_time,
                    'member_code' => $membercode ? $membercode : 'not_member',
                    'sender' => $sendcode,
                    'sim' => $sim_number ? $sim_number : $simnumber,
                    'oldbal' => $oldbalance,
                    'amount' => $amount,
                    'lastbal' => $lastbal ? $lastbal : 0,
                    'status' => $bmstst,
                    'type' => $smsbodytype,
                    'trxid' => $trxid,
                    'mobile' => $number,
                    'sms_body' => $msg,
                    'simslot' => $dbinsertsim,
                    'deviceid' => $deviceid,
                    'telco' => $simoprt,
                    'commission' => $comm,
                    'sms_time' => $sms_date,
                    'smbal' => $courrentbmbalnumber,
                    'note' => $defranceamount,
                    'modem_id' => $modem_id,
                    'partner' => $partner_id ? $partner_id : '-1',
                    'dso' => $dso_id ? $dso_id : '-1',
                    'agent' => $user_id ? $user_id : '-1',
                    'merchent_id' => $merchant_code ? $merchant_code : '-1',
                    'ext_field' => $currentlastbal,
                    'ext_field_2' => $defran,
                    'idate' => $idate,
                    'token' => $token,
                    'ext_field_3' => $sms_date
                ]);

                //Sending sms to user

                if ($bmanger) {
                    $type = '';

                    if ($smsbodytype == 'ngcashout' && $membercode) {
                        if ($bmstst == 33 || $bmstst == 55 || $bmstst == null) {
                            if ($bmstst == 33) {
                                $type = 'waiting';
                            } else if ($bmstst == 33) {
                                $type = 'danger';
                            } else if ($bmstst == null) {
                                $type = 'pending';
                            }

                            $member_info = User::where('member_code', $membercode)->first();
                            $provider = SmsSetting::where('provider', 'sms_city')->first();
                            $text = "New pending message please check and verify quickly Sender:" . $sendcode . " C-Number:" . $number . " TRXID:" . $trxid . " amount: " . $amount . " date:" . $sms_date;
                            $post_data = [
                                "contact" => [
                                    [
                                        "number" => $member_info->mobile,
                                        "message" => $text
                                    ],

                                ]
                            ];
                            $response = Http::withHeaders([
                                'Api-key' => $provider->access_token,
                                'Content-Type' => 'application/json',
                            ])->post('https://smscity.net/api/whatsapp/send', $post_data);

                        }
                    }
                }


                //}
            }
        }

        $statsusinsert = false;

//       if($smsinbxcrt){

//             DB::commit();

//             $statsusinsert = true;

// 		 }else {
// 			  DB::rollback();
// 			  $statsusinsert = false;
// 		 }


        return response()->json([
            'message' => 'message ' . $statsusinsert . ' success',
            'status' => 'success',
            'success' => true
        ], 200);

    }


    public function deviceverify(Request $request)
    {
        $membercode = $request->membercode ? $request->membercode : $request->opcode;
        $oparetor = $request->operator ? $request->operator : $request->oparator;
        $simid = $request->simid;
        $deviceid = $request->deviceid;
        $sim_number = $request->simid ? $request->simid : $request->simnumber;

        $token = Str::random(32);

        $uptime = time();

        // DB::beginTransaction();

        if (empty($membercode)) {
            return response()->json([
                'message' => 'member id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($deviceid)) {
            return response()->json([
                'message' => 'Device id empty',
                'status' => 'not',
                'success' => false
            ]);
        }

        if (empty($sim_number)) {
            return response()->json([
                'message' => 'Sim Number empty',
                'status' => 'not',
                'success' => false
            ]);
        }


        $userfound = User::where('user_type', 'agent')
            ->where('member_code', $membercode)
            ->where('status', 1)
            ->count();

        if ($userfound == 0) {

            return response()->json([
                'message' => 'User id Not Found',
                'status' => 'not',
                'success' => false
            ]);

        }

        /*

                $mtfound = Modem::where('type', 'android')
                    ->where('deviceid', $deviceid)
                    ->where('member_code', $membercode)
                    ->count();

                if ($mtfound == 0) {
                    $modeminsert = Modem::create([
                                    'type' => 'android',
                                    'member_code' => $membercode ? $membercode : 'not',
                                    'deviceid' => $deviceid ? $deviceid : 'not',
                                    'operator' => $oparetor ? $oparetor : 'not',
                                    'sim_id' => $simid ? $simid : 'not',
                                    'sim_number' => $sim_number,
                                    'modem_details' => $modem_details,
                                    'token' => $token,
                                    'up_time' =>$uptime,
                                    'status' => 1
                                ]);
                } else {



                    $mtupdate = Modem::where('type', 'android')
                        ->where('deviceid', $deviceid)
                        ->where('member_code', $membercode)
                        ->update(['up_time' => $uptime, 'operator' => $oparetor, 'sim_number' => $sim_number, 'token' => $token]);

                        //$statsusinsert = true;

                }


                if(($modeminsert) || ($mtupdate)){

                    DB::commit();

                    $statsusinsert = true;

                 }else {
                      DB::rollback();
                      $statsusinsert = false;
                 }
                 */

        return response()->json([
            'message' => 'successful login modem',
            'username' => $membercode,
            'token' => $token,
            'status' => 'success',
            'success' => true
        ]);
    }


    public function testSms(Request $req)
    {
        // $smsbodytype= 'ngcashout';
        // $membercode= 1;
        // $number=111111;
        // $trxid='i;erg;oaeru';
        // $amounnt = 150;
        // $sms_date = 11;

        //  if($smsbodytype== 'ngcashout' && $membercode){

        //     //send_sms('waiting',$membercode,$number,$trxid,$amount,$sms_date );

        //       return $this->send_sms('waiting',$membercode,$number,$trxid,$amount,$sms_date );
        // };


        // function send_sms($type, $membercode, $customer_number, $trxid, $amount, $sms_date ){

        //     return 1;

        //     $member_info = User::where('member_code',$membercode )->first();
        //     $provider = SmsSetting::where('provider','sms_city')->first();

        //     $text = "you have a". $type ." message please check and verify quickly Sender: bkash Customer number:".$customer_number ." TRXID:".$trxid." amount: ".$amount." date:".$sms_date;

        //           $post_data = [
        //                 "contact" => [
        //                     [
        //                         "number" => '88'. $member_info->mobile ,
        //                         "message" => $text
        //                     ],

        //                 ]
        //             ];

        //                     $response = Http::withHeaders([
        //                         'Api-key' => $provider->access_token,
        //                         'Content-Type' => 'application/json',
        //                     ])->post('https://smscity.net/api/whatsapp/send', $post_data);
        // };

    }





    public function android_modem_app_update($current_version)
    {
        $latest_version_from_store = app_config('android_modem_app_version');

        if ($current_version != $latest_version_from_store) {
            return response()->json([
                'message' => 'Your SMS App need to update for smooth work.',
                'status_code' => 200,
                'latest_version' => $latest_version_from_store,
                'app_url' => url('xoreymodem-').$latest_version_from_store.'.apk',
                'update' => true
            ]);
        }

        return response()->json([
            'message' => 'Your SMS App Version Up to date.',
            'status_code' => 200,
            'latest_version' => $latest_version_from_store,
            'update' => false
        ]);
    }
}
