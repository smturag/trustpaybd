<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\smsInbox;
use App\Models\Modem;
use App\Models\User;
use App\Models\Merchant;
use App\Models\BalanceManager;
use App\Models\SmsSetting;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\Log;

class ReactNativeModemController extends Controller
{
    public function sendSmsToServer(Request $request)
    {
        // return response()->json([
        //     'message' => $request->all(),
        //     'status' => 'test',
        //     'success' => false
        // ]);

        $getoperator = $request->operator;
        $msg = $request->smsbody;
        $msgNormalized = preg_replace('/\s+/', ' ', trim($msg));
        // $sendcode = trim($request->sender);
        // $sendcodeLower = strtolower($sendcode);
        $sendcode = trim($request->sender);
        $sendcodeLower = $request->sender;
        $simid = $request->simid;
        $deviceid = $request->deviceid;
        $membercode = $request->membercode;
        $simslot = $request->simslot;
        $token = $request->token;
        $request_time = $request->smstime;
        $simnumber = $request->simnumber;

        if ($simslot == 0) {
            $tersimslot = 0;
            $dbinsertsim = 1;
        } else {
            $tersimslot = 1;
            $dbinsertsim = 2;
        }

        DB::beginTransaction();

        if (empty($membercode)) {
            return response()->json([
                'message' => 'Member id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($deviceid)) {
            return response()->json([
                'message' => 'Device id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($token)) {
            return response()->json([
                'message' => 'Token id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($msg)) {
            return response()->json([
                'message' => 'Message Body empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($sendcode)) {
            return response()->json([
                'message' => 'Sendcode id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        $getsms_time = date('Y-m-d H:i:s', $request_time / 1000);
        // $getsms_time = now();
        $idate = date('Y-m-d');

        $aget_udata = User::where('member_code', $membercode)->first();

        $user_id = $aget_udata->id;
        $partner_id = $aget_udata->partner;
        $dso_id = $aget_udata->dso;

        $modem_data = Modem::where('type', 'android')->where('deviceid', $deviceid)->where('simslot', $tersimslot)->where('member_code', $membercode)->first();

        //return "deviceid". $deviceid .", dbinsertsim". $dbinsertsim .", membercode". $membercode;
        //return $modem_data->id;

        $modem_id = $modem_data->id;
        $sim_number = $modem_data->sim_number;
        $merchant_code = $modem_data->merchant_code;

        $sms_exist = smsInbox::where('sms', $msg)->count();

        $smsinbxcrt = null; // Initialize variable

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
                'token' => $token,
                'partner' => $partner_id ? $partner_id : '-1',
                'dso' => $dso_id ? $dso_id : '-1',
                'agent' => $user_id ? $user_id : '-1',
            ]);

            // $mtupdate = Modem::where('type', 'android')
            //     ->where('deviceid', $deviceid)
            //     ->where('member_code', $membercode)
            //     ->update(['up_time' => time()]);
        }

        if (empty($simnumber) || empty($sim_number)) {
            return response()->json([
                'message' => 'Sim number id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        //        else {
        //            $sim_number = 'testagentnumber';
        //        }

        $smsbodytype = '';

        // Initialize all variables with default values
        $amount = 0;
        $number = '';
        $trxid = '';
        $lastbal = 0;
        $comm = 0;
        $fee = 0;
        $baltype = '';
        $sms_date = now()->format('Y-m-d H:i');

        /// balance manager full function start here

        if (in_array($sendcodeLower, ['NAGAD', 'bKash', '16216', 'upay'], true)) {
            $responseValue = explode(':', $msg);

            //Cash Out Received.Amount: Tk 2100.00Customer: 01672151119TxnID: 71NHMIC5Comm: Tk 8.61Balance: Tk 110060.8121/01/2023 18:30
            //Cash In Successful.Amount: Tk 3000.00 Customer: 01827147299 TxnID: 71R8DC4R Comm: Tk 12.30 Balance: Tk 10980.99 12/03/2023 22:06

            if (str_contains($responseValue[0], 'Cash Out') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngcashout';
                $baltype = 'plus';
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
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            // nagad cashin

            if (str_contains($responseValue[0], 'Cash In') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngcashin';
                $baltype = 'minus';
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
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            // nagad B2B
            // B2B Transfer Successful. Amount: Tk 3000.00 Receiver: 01810030342 TxnID: 71R9VMKW Balance: Tk 43462.02 13/03/2023 17:30

            if (str_contains($responseValue[0], 'B2B Transfer') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngB2BTR';
                $baltype = 'minus';
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
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            // nagad b2b receive
            //B2B Received. Amount: Tk 3000.00 Sender: 01810030342 TxnID: 71R9OL1C Balance: Tk 16202.72 13/03/2023 16:05

            if (str_contains($responseValue[0], 'B2B Received') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngB2BRC';
                $baltype = 'plus';
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
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            //Cash Out Tk 785.00 from 01841721504 successful. Fee Tk 0.00. Balance Tk 121,710.33. TrxID AA24AJJH6I at 02/01/2023 22:05

            //Congratulations! You have received Cashback Tk 1.00. Balance Tk 91,950.74. TrxID AC889WE2G0 at 08/03/2023 10:50

            if (str_contains($responseValue[0], 'Cash Out') && $sendcodeLower === 'bKash') {
                $smsbodytype = 'bkcashout';
                $baltype = 'plus';
                $amount = floatval(value: str_replace(',', '', getStringBetween($msg, 'Tk ', ' from')));
                $number = trim(getStringBetween($msg, 'from ', ' success'));
                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                $getcom = ($amount * 0.4) / 100;
                //$comm = trim(getStringBetween($msg, 'Comm: Tk ', 'Balance'));
                $comm = $getcom;

                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = trim(getStringBetween($msg, 'at ', '')); // everything after "at "

                if (preg_match('/\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}/', $date)) {
                    $sms_date = Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i');
                } else {
                    Log::error('Invalid date format in SMS: ' . $date);
                    $sms_date = null;
                }

                // $date = substr($msg, -16, strlen($msg));
                // $hours = substr($date, 11, 2);
                // $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            //Cash In Tk 2,000.00 to 01856600204 successful. Fee Tk 0.00. Balance Tk 2,851.16. TrxID AC829VTZTE at 08/03/2023 10:37
            if (str_contains($responseValue[0], 'Cash In') && $sendcodeLower === 'bKash') {
                $smsbodytype = 'bkcashin';
                $baltype = 'minus';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Cash In Tk ', ' to')));
                $number = trim(getStringBetween($msg, 'to ', ' success'));
                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                $getcom = ($amount * 0.4) / 100;
                $comm = $getcom;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = trim(getStringBetween($msg, 'at ', ''));

                try {
                    if (preg_match('/\d{2}\/\d{2}\/\d{4} \d{2}:\d{2}/', $date)) {
                        // 24-hour format e.g. 08/03/2023 10:37 or 08/03/2023 22:05
                        $sms_date = Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i');
                    } elseif (preg_match('/\d{2}\/\d{2}\/\d{4} \d{1,2}:\d{2} (AM|PM)/i', $date)) {
                        // 12-hour format e.g. 08/03/2023 10:37 AM
                        $sms_date = Carbon::createFromFormat('d/m/Y h:i A', $date)->format('Y-m-d H:i');
                    } else {
                        // Fallback if no regex matched → keep raw date string
                        $sms_date = Carbon::parse($date)->format('Y-m-d H:i');
                    }
                } catch (\Exception $e) {
                    // Absolute fallback → save current time if parsing fails
                    Log::error('Date parsing failed: ' . $date . ' | ' . $e->getMessage());
                    $sms_date = now()->format('Y-m-d H:i');
                }

                //     $date = substr($msg, -16, strlen($msg));
                //     $hours = substr($date, 11, 2);
                //    $sms_date = $hours > 12 ? Carbon::createFromFormat("d/m/Y H:i", $date)->format("Y-m-d H:i") : Carbon::createFromFormat("d/m/Y h:i", $date)->format("Y-m-d H:i");
            }

            // B2B Transfer Tk 2,000.00 to 01704172631 successful. Fee Tk 0.00. Balance Tk 7,521.24. TrxID ACD7FJDL8B at 13/03/2023 16:48

            if (str_contains($responseValue[0], 'B2B Transfer') && $sendcodeLower === 'bKash') {
                $smsbodytype = 'bkB2B';
                $baltype = 'minus';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'Transfer Tk ', ' to')));
                $number = trim(getStringBetween($msg, 'to ', ' success'));

                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                //$getcom = $amount*0.4/100;
                $comm = 00;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            // You have received Tk 2,000.00 from 01704172631. Fee Tk 0.00. Balance Tk 2,201.51. TrxID ACA8CBMVZ2 at 10/03/2023 16:36

            if (str_contains($responseValue[0], 'received') && $sendcodeLower === 'bKash' && !str_contains($responseValue[0], 'payment')) {
                $smsbodytype = 'bkRC';
                $baltype = 'plus';
                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'received Tk ', ' from')));
                $number = trim(getStringBetween($msg, 'from ', ' Fee'));

                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));
                //$getcom = $amount*0.4/100;
                $comm = 00;
                $lastbal = trim(getStringBetween($msg, 'Balance Tk ', '. TrxID'));

                $lastbal = floatval(str_replace(',', '', $lastbal));

                $date = substr($msg, -16, strlen($msg));
                $hours = substr($date, 11, 2);
                $sms_date = $hours > 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            //Rocket B2C Cash-Out: Cash-Out from A/C: ***097 Tk2,009.00 Comm:Tk8.43; A/C Balance: Tk87,455.82.TxnId: 6017463139 Date:25-DEC-25 05:53:00 pm. Download https://bit.ly/nexuspay
            if (str_contains($responseValue[0], 'Cash-Out') && $sendcodeLower === '16216') {
                $smsbodytype = 'rccashout';
                $baltype = 'plus';

                // Amount - handle formats like "Tk2,009.00"
                $amount = floatval(str_replace(',', '', getStringBetween($msg, ' Tk', ' Comm')));

                // Account number - handle both "A/C: ***097" and "A/C: 017559717268"
                $number = trim(getStringBetween($msg, 'A/C: ', ' Tk'));

                // Commission - extract from "Comm:Tk8.43"
                $comm = floatval(str_replace(',', '', getStringBetween($msg, 'Comm:Tk', ';')));

                // Transaction ID
                $trxid = trim(getStringBetween($msg, 'TxnId: ', ' Date:'));

                // Last balance - handle "A/C Balance: Tk87,455.82"
                $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'Balance: Tk', '.TxnId')));

                // Date string - extract date portion
                preg_match('/Date:(\d{2}-[A-Z]{3}-\d{2}\s\d{2}:\d{2}:\d{2}\s(?:am|pm))/i', $msg, $dateMatch);
                $rocket_date = $dateMatch[1] ?? null;

                // Convert Rocket date to standard format (Y-m-d H:i)
                if ($rocket_date) {
                    try {
                        $sms_date = Carbon::createFromFormat('d-M-y h:i:s a', $rocket_date)->format('Y-m-d H:i');
                    } catch (\Exception $e) {
                        Log::error('Rocket cashout date parsing failed: ' . $rocket_date . ' | ' . $e->getMessage());
                        $sms_date = now()->format('Y-m-d H:i');
                    }
                } else {
                    $sms_date = now()->format('Y-m-d H:i');
                }

                // Debug logging
                Log::info('Rocket Cash-Out SMS Parsed', [
                    'amount' => $amount,
                    'number' => $number,
                    'comm' => $comm,
                    'lastbal' => $lastbal,
                    'trxid' => $trxid,
                    'sms_date' => $sms_date,
                    'smsbodytype' => $smsbodytype,
                    'baltype' => $baltype
                ]);
            }

            //upay cashout :  You have received Cash-out of Tk. 3940.00 from 01576987382. Comm: TK. 16.1343. Balance Tk. 6568.82. TrxID 01JYG4GET7 at 24/06/2025 10:52.

            if (str_contains($msg, 'Cash-out') && $sendcodeLower === 'upay') {
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

            /*
             * Rocket Receive SMS - rcRC Format (Old format with Download link)
             * Tk100.00 received from A/C:***946 Fee:Tk0, Your A/C Balance: Tk100.00 TxnId:6022415176 Date:27-DEC-25 08:43:52 pm. Download https://bit.ly/nexuspay
             */

            if (str_contains($responseValue[0], 'received') && $sendcodeLower === '16216' && str_contains($msg, 'Download')) {
                $smsbodytype = 'rcRC'; // Rocket Receive SMS (Old format)
                $baltype = 'plus';
                $comm = 0;

                // Amount - handle both "Tk.100.00" and "Tk100.00" formats
                $amountStr = getStringBetween($msg, 'Tk', ' received');
                $amount = floatval(str_replace(',', '', trim($amountStr, '.')));

                // Account number (A/C)
                $number = trim(getStringBetween($msg, 'A/C:', ' Fee'));

                // Fee
                $fee = floatval(str_replace(',', '', getStringBetween($msg, 'Fee:Tk', ',')));

                // Last balance
                $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'Your A/C Balance: Tk', ' TxnId')));

                // Transaction ID
                $trxid = trim(getStringBetween($msg, 'TxnId:', ' Date:'));

                // Date string - extract date portion
                preg_match('/Date:(\d{2}-[A-Z]{3}-\d{2}\s\d{2}:\d{2}:\d{2}\s(?:am|pm))/i', $msg, $dateMatch);
                $rocket_date = $dateMatch[1] ?? null;

                // Convert Rocket date to standard format (Y-m-d H:i)
                if ($rocket_date) {
                    try {
                        $sms_date = Carbon::createFromFormat('d-M-y h:i:s a', $rocket_date)->format('Y-m-d H:i');
                    } catch (\Exception $e) {
                        Log::error('Rocket rcRC date parsing failed: ' . $rocket_date . ' | ' . $e->getMessage());
                        $sms_date = now()->format('Y-m-d H:i');
                    }
                } else {
                    $sms_date = now()->format('Y-m-d H:i');
                }

                // Debug logging
                Log::info('Rocket rcRC SMS Parsed', [
                    'amount' => $amount,
                    'number' => $number,
                    'fee' => $fee,
                    'lastbal' => $lastbal,
                    'trxid' => $trxid,
                    'sms_date' => $sms_date,
                    'smsbodytype' => $smsbodytype,
                    'baltype' => $baltype
                ]);
            }

            /*
             * Rocket Receive SMS - rcpayment Format (New format with Ref No and NetBal)
             * Tk.20.00 received from A/C:***946 Ref No: NA Fee: Tk.00 NetBal: Tk41.00 TxnId: 6022164589 Date:27-DEC-25 07:17:16 pm.
             */

            if (str_contains($responseValue[0], 'received') && $sendcodeLower === '16216' && !str_contains($msg, 'Download')) {
                $smsbodytype = 'rcpayment'; // Rocket Receive SMS (New format)
                $baltype = 'plus';
                $comm = 0;

                // Amount - handle both "Tk.20.00" and "Tk20.00" formats
                $amountStr = getStringBetween($msg, 'Tk', ' received');
                $amount = floatval(str_replace(',', '', trim($amountStr, '.')));

                // Account number (A/C) - handle both "A/C:***946" and "A/C:*842" formats
                $number = trim(getStringBetween($msg, 'A/C:', ' '));

                // Fee - handle both "Fee: Tk.00" and "Fee:Tk0" formats
                if (str_contains($msg, 'Fee: Tk')) {
                    $fee = floatval(str_replace(',', '', getStringBetween($msg, 'Fee: Tk', ' ')));
                } else {
                    $fee = floatval(str_replace(',', '', getStringBetween($msg, 'Fee:Tk', ' ')));
                }

                // Last balance - handle both "NetBal: Tk41.00" and "Your A/C Balance: Tk120.00" formats
                if (str_contains($msg, 'NetBal: Tk')) {
                    $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'NetBal: Tk', ' TxnId')));
                } elseif (str_contains($msg, 'Your A/C Balance: Tk')) {
                    $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'Your A/C Balance: Tk', '. TxnId')));
                } else {
                    $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'Balance: Tk', ' TxnId')));
                }

                // Transaction ID
                $trxid = trim(getStringBetween($msg, 'TxnId: ', ' Date:'));
                if (empty($trxid)) {
                    $trxid = trim(getStringBetween($msg, 'TxnId:', ' Date:'));
                }

                // Date string - extract date portion
                preg_match('/Date:(\d{2}-[A-Z]{3}-\d{2}\s\d{2}:\d{2}:\d{2}\s(?:am|pm))/i', $msg, $dateMatch);
                $rocket_date = $dateMatch[1] ?? null;

                // Convert Rocket date to standard format (Y-m-d H:i)
                if ($rocket_date) {
                    try {
                        $sms_date = Carbon::createFromFormat('d-M-y h:i:s a', $rocket_date)->format('Y-m-d H:i');
                    } catch (\Exception $e) {
                        Log::error('Rocket rcpayment date parsing failed: ' . $rocket_date . ' | ' . $e->getMessage());
                        $sms_date = now()->format('Y-m-d H:i');
                    }
                } else {
                    $sms_date = now()->format('Y-m-d H:i');
                }

                // Debug logging
                Log::info('Rocket rcpayment SMS Parsed', [
                    'amount' => $amount,
                    'number' => $number,
                    'fee' => $fee,
                    'lastbal' => $lastbal,
                    'trxid' => $trxid,
                    'sms_date' => $sms_date,
                    'smsbodytype' => $smsbodytype,
                    'baltype' => $baltype
                ]);
            }

            /*
            You have received payment Tk 55.00 from 01929952387. Fee Tk 0.00. Balance Tk 55.59.TrxID CJKOGO2GAQ at 20/10/2025 18:00
            */

            if (str_contains($msg, 'received payment') && $sendcodeLower === 'bKash') {
                $smsbodytype = 'bkPayment';
                $baltype = 'plus';
                $comm = 0;

                $amount = floatval(str_replace(',', '', getStringBetween($msg, 'payment Tk ', ' from')));
                $number = trim(getStringBetween($msg, 'from ', '. Fee'));
                $fee = floatval(str_replace(',', '', getStringBetween($msg, 'Fee Tk ', '. Balance')));
                $lastbal = floatval(str_replace(',', '', getStringBetween($msg, 'Balance Tk ', '.TrxID')));
                $trxid = trim(getStringBetween($msg, 'TrxID ', ' at'));

                // Extract the date at the end
                $date = substr($msg, -16); // "20/10/2025 18:00"
                $hours = substr($date, 11, 2);

                // Format date/time properly
                $sms_date = $hours >= 12 ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : Carbon::createFromFormat('d/m/Y h:i', $date)->format('Y-m-d H:i');
            }

            /*
            Payment Received. Amount: Tk 50.00 Customer: 01929952387 TxnID: 74HΝΕΚΟ8 Balance: Tk 247.78 20/10/2025 17:58
            */

            if (str_contains($msgNormalized, 'Payment Received') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngPayment';
                $baltype = 'plus';
                $comm = 0;

                // Extract using your helper (no \n)
                $amount = floatval(str_replace(',', '', getStringBetweenForMassage($msgNormalized, 'Amount: Tk ', ' Customer:')));
                $number = trim(getStringBetweenForMassage($msgNormalized, 'Customer: ', ' TxnID:'));
                $trxid = trim(getStringBetweenForMassage($msgNormalized, 'TxnID: ', ' Balance:'));
                $lastbal = floatval(str_replace(',', '', getStringBetweenForMassage($msgNormalized, 'Balance: Tk ', ' ')));

                // Extract date & time (last portion)
                preg_match('/(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})/', $msgNormalized, $dateMatch);
                $date = $dateMatch[1] ?? null;
                $sms_date = $date ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : null;
            }

            /*
             *Money Received. Amount: Tk 10.00 Sender: 01929952387 Ref: N/A TxnID: 74HNFMWH Balance: Tk 334.49 20/10/2025 18:03
             */



            if (str_contains($msgNormalized, 'Money Received') && $sendcodeLower === 'NAGAD') {
                $smsbodytype = 'ngRC';
                $baltype = 'plus';
                $comm = 0;

                // Use your existing helper function
                $amount = floatval(str_replace(',', '', getStringBetweenForMassage($msgNormalized, 'Amount: Tk ', ' Sender:')));
                $number = trim(getStringBetweenForMassage($msgNormalized, 'Sender: ', ' Ref:'));
                $ref = trim(getStringBetweenForMassage($msgNormalized, 'Ref: ', ' TxnID:'));
                $trxid = trim(getStringBetweenForMassage($msgNormalized, 'TxnID: ', ' Balance:'));
                $lastbal = floatval(str_replace(',', '', getStringBetweenForMassage($msgNormalized, 'Balance: Tk ', ' ')));

                // Extract date/time (last portion)
                preg_match('/(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})/', $msgNormalized, $dateMatch);
                $date = $dateMatch[1] ?? null;
                $sms_date = $date ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : null;


            }

            /*
             *Tk. 900.00 has been received from 01540669293. Ref-. Balance Tk. 900.00. TrxID 01K7C8Z24V at 12/10/2025 19:15
             */
            if (str_contains($msg, 'has been received') && $sendcodeLower === 'upay') {
                $smsbodytype = 'upRC';
                $baltype = 'plus';
                $comm = 0;

                // Extract values using your helper
                $amount = floatval(str_replace(',', '', getStringBetweenForMassage($msg, 'Tk. ', ' has been received')));
                $number = trim(getStringBetweenForMassage($msg, 'from ', '. Ref'));
                $ref = trim(getStringBetweenForMassage($msg, 'Ref', '. Balance'));
                $lastbal = floatval(str_replace(',', '', getStringBetweenForMassage($msg, 'Balance Tk. ', '. TrxID')));
                $trxid = trim(getStringBetweenForMassage($msg, 'TrxID ', ' at'));

                // Extract & format date/time
                preg_match('/(\d{2}\/\d{2}\/\d{4}\s\d{2}:\d{2})/', $msg, $dateMatch);
                $date = $dateMatch[1] ?? null;
                $sms_date = $date ? Carbon::createFromFormat('d/m/Y H:i', $date)->format('Y-m-d H:i') : null;
            }


            if ($sendcodeLower === 'nagad' && strtolower((string) $smsbodytype) === 'rc') {
                $smsbodytype = 'ngRC';
            }

            $bmcount = BalanceManager::where('sms_body', $msg)->count();

            if ($bmcount == 0) {
                $getbalancedata = BalanceManager::where('sender', $sendcode)->where('sim', $sim_number)->where('deviceid', $deviceid)->where('simslot', $dbinsertsim)->orderBy('id', 'desc')->first();

                // Initialize variables with default values if no previous balance record exists
                $getbmlastbaldb = $getbalancedata ? $getbalancedata->lastbal : 0;
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

                    // if($smsbodytype== 'ngcashout' && $membercode){
                    //     SendSMS('waiting',$membercode,$number,$trxid,$amount,$sms_date );
                    // };
                } elseif ($defran >= 501 && $defran <= 25000000) {
                    $bmstst = 55;

                    // if($smsbodytype== 'ngcashout' && $membercode){
                    //     SendSMS('danger',$membercode,$number,$trxid,$amount,$sms_date );
                    // };

                    // SendSMS('danger',$membercode,$number,$trxid,$amount,$sms_date );
                } elseif (empty($getbmlastbal)) {
                    $bmstst = 10;
                }

                $bmanger = BalanceManager::create([
                    'request_time' => $getsms_time,
                    'member_code' => $membercode ? $membercode : 'not_member',
                    'sender' => $sendcode,
                    'sim' => $sim_number ? $sim_number : $simnumber,
                    'oldbal' => $oldbalance,
                    'amount' => $amount,
                    'lastbal' => $lastbal ?: 0,
                    'status' => $bmstst,
                    'type' => $smsbodytype,
                    'trxid' => $trxid,
                    'mobile' => $number,
                    'sms_body' => $msg,
                    'simslot' => $dbinsertsim,
                    'deviceid' => $deviceid,
                    'telco' => $getoperator,
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
                    'ext_field_3' => $sms_date,
                ]);

                if ($bmanger) {
                    $message = $msg;

                    $serviceRequestMethod = '';
                    $getSender = strtolower($bmanger->sender);

                    if ($getSender == '16216') {
                        $serviceRequestMethod = 'rocket';
                    } elseif ($getSender == 'bkash') {
                        $serviceRequestMethod = 'bkash';
                    } elseif ($getSender == 'nagad') {
                        $serviceRequestMethod = 'nagad';
                    } elseif ($getSender == 'upay') {
                        $serviceRequestMethod = 'upay';
                    }

                    $service_requests = ServiceRequest::whereIn('status', [1, 6, 5])
                        ->where('mfs', $serviceRequestMethod)
                        ->get();

                    foreach ($service_requests as $req_item) {
                        $number = (string) $req_item->number;

                        $encodedNumber = strlen($number) >= 11 ? substr($number, 0, 4) . 'XXXX' . substr($number, 8) : $number;

                        $amountString = (string) $req_item->amount;
                        if (empty($amountString) || (is_numeric($amountString) && (float) $amountString < 0)) {
                            $amount = '0';
                        } else {
                            $amount = strtok($amountString, '.'); // Get the integer part of the amount
                            if (empty($amount)) {
                                $amount = '0';
                            }
                        }

                        $messageContainsNumber = strpos($message, $number) !== false || strpos($message, $encodedNumber) !== false || strpos($message, substr($encodedNumber, 1)) !== false || strpos($message, substr($number, 1)) !== false;

                        $messageContainsSuccess = stripos($message, 'success') !== false;

                        $normalizedMessage = normalizeAmount($message);
                        $normalizedAmount = normalizeAmount($amount);

                        $messageContainsAmount = stripos($normalizedMessage, $normalizedAmount) !== false;

                        if ($messageContainsNumber && $messageContainsSuccess && $messageContainsAmount) {
                            $req_item->status = 2;
                            // $req_item->result = $message;

                            // $words = explode(' ', $message);
                            // $longestWord = '';
                            // foreach ($words as $word) {
                            //     if (strlen($word) > strlen($longestWord)) {
                            //         $longestWord = $word;
                            //     }
                            // }

                            // $req_item->get_trxid = $longestWord;

                            $trxid = '';
                            $isCashIn = stripos($message, 'Cash In') !== false || stripos($message, 'Cash-In') !== false;

                            if ($isCashIn) {
                                if ($serviceRequestMethod === 'bkash' || $serviceRequestMethod === 'upay') {
                                    // Cash In format: "TrxID CGD8MS316I"
                                    if (preg_match('/TrxID[\s:]*([A-Z0-9]+)/i', $message, $matches)) {
                                        $trxid = $matches[1];
                                    }
                                } elseif ($serviceRequestMethod === 'nagad') {
                                    // Cash In format: "TxnID: 745I7GNM"
                                    if (preg_match('/TxnID[\s:]*([A-Z0-9]+)/i', $message, $matches)) {
                                        $trxid = $matches[1];
                                    }
                                } elseif ($serviceRequestMethod === 'rocket') {
                                    // Rocket B2C format: "TxnId: 5562724147"
                                    if (preg_match('/TxnId[\s:]*([0-9]+)/i', $message, $matches)) {
                                        $trxid = $matches[1];
                                    }
                                }
                            }

                            if ($trxid) {
                                $req_item->get_trxid = $trxid;
                                $req_item->action_by = 'Automatic';
                                $req_item->save();
                            }

                            sleep(5);

                            break; // Keep this if you want to stop after the first match
                        }
                    }
                }
            }
        }

        $statsusinsert = false;

        if ($smsinbxcrt) {
            DB::commit();
            $statsusinsert = true;
            $status_code = 200;
            $status = 'success';
        } else {
            DB::rollback();
            $statsusinsert = false;
            $status_code = 500;
            $status = 'error';
        }

        return response()->json([
            'message' => 'message 0' . $statsusinsert . ' success',
            'status_code' => $status_code,
            'status' => $status,
            'smstime' => $request_time,
            'success' => $statsusinsert,
        ]);
    }

    public function verify_rn_device(Request $request)
    {
        $membercode = $request->membercode ?: $request->opcode;
        $oparetor = $request->oparator;
        $oparetor2 = $request->oparator2;
        $simid = $request->simid;
        $simid2 = $request->simid2;
        $deviceid = $request->deviceid;
        $sim_number = $request->simnumber;
        $sim_number2 = $request->simnumber2;
        $modem_details = $request->modem_details;

        $sim_1_transaction_type = $request->sim1_transaction_type;
        $sim_2_transaction_type = $request->sim2_transaction_type;

        $token = Str::random(32);

        if (empty($membercode)) {
            return response()->json([
                'message' => 'Member id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($deviceid)) {
            return response()->json([
                'message' => 'Device id empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        if (empty($sim_number) && empty($sim_number2)) {
            return response()->json([
                'message' => 'Sim Number empty',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        $userfound = User::where('user_type', 'agent')->where('member_code', $membercode)->where('status', 1)->exists();

        if (!$userfound) {
            return response()->json([
                'message' => 'User id Not Found',
                'status_code' => 500,
                'success' => false,
            ]);
        }

        DB::beginTransaction();
        try {
            $modemId = null;
            $modemId2 = null;

            $modems = Modem::where('type', 'android')->where('deviceid', $deviceid)->where('member_code', $membercode)->get();

            if ($modems->isEmpty()) {
                // Insert new modems
                if (!empty($sim_number)) {


                    $sim_1_transaction_type = $sim_1_transaction_type ?? 'P2A'; // fallback if not set

                    // Check if 'desktop' exists in modem details (case-insensitive)
                    if (strpos(strtolower($modem_details), 'desktop') !== false) {
                        $sim_1_transaction_type = 'P2A';
                    }

                    $modem = Modem::create([
                        'type' => 'android',
                        'member_code' => $membercode,
                        'deviceid' => $deviceid,
                        'operator' => $oparetor ?: null,
                        'sim_id' => $simid ?: $sim_number,
                        'sim_number' => $sim_number,
                        'transaction_type' => $sim_1_transaction_type,
                        'modem_details' => $modem_details,
                        'token' => $token,
                        'up_time' => time(),
                        'status' => 1,
                        'simslot' => 0,
                    ]);
                    $modemId = $modem->id;
                }
                if (!empty($sim_number2)) {

                    $sim_2_transaction_type = $sim_2_transaction_type ?? 'P2A'; // fallback if not set

                    // Check if 'desktop' exists in modem details (case-insensitive)
                    if (strpos(strtolower($modem_details), 'desktop') !== false) {
                        $sim_1_transaction_type = 'P2A';
                    }


                    $modem2 = Modem::create([
                        'type' => 'android',
                        'member_code' => $membercode,
                        'deviceid' => $deviceid,
                        'operator' => $oparetor2 ?: null,
                        'sim_id' => $simid2 ?: $sim_number2,
                        'transaction_type' => $sim_2_transaction_type,
                        'sim_number' => $sim_number2,
                        'modem_details' => $modem_details,
                        'token' => $token,
                        'up_time' => time(),
                        'status' => 1,
                        'simslot' => 1,
                    ]);
                    $modemId2 = $modem2->id;
                }
            } else {
                // Update existing modems
                if (count($modems) > 2) {
                    // If there are more than 2 modems, delete the excess
                    $modems->skip(2)->each->delete();
                }
                foreach ($modems as $index => $modem) {
                    if ($modem->simslot == 0 && !empty($sim_number)) {
                        $modem->update([
                            'up_time' => time(),
                            'operator' => $oparetor,
                            'sim_number' => $sim_number,
                            'token' => $token,
                            'simslot' => 0,
                        ]);
                        $modemId = $modem->id;
                    }
                    if ($modem->simslot == 1 && !empty($sim_number2)) {
                        $modem->update([
                            'up_time' => time(),
                            'operator' => $oparetor2,
                            'sim_number' => $sim_number2,
                            'token' => $token,
                            'simslot' => 1,
                        ]);
                        $modemId2 = $modem->id;
                    }
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Successful login modem',
                'username' => $membercode,
                'modemId' => $modemId,
                'modemId2' => $modemId2,
                'token' => $token,
                'status_code' => 200,
                'success' => true,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'message' => 'Transaction failed: ' . $e->getMessage(),
                'status_code' => 500,
                'success' => false,
            ]);
        }
    }

    public function update_rn_sms_app($current_version)
    {
        $latest_version_from_store = app_config('rn_sms_app_version');

        if ($current_version != $latest_version_from_store) {
            return response()->json([
                'message' => 'Your SMS App need to update for smooth work.',
                'status_code' => 200,
                'latest_version' => $latest_version_from_store,
                'update' => true,
            ]);
        }

        return response()->json([
            'message' => 'Your SMS App Version Up to date.',
            'status_code' => 200,
            'latest_version' => $latest_version_from_store,
            'update' => false,
        ]);
    }
}
