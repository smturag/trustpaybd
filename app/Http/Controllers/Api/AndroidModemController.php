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

class AndroidModemController extends Controller
{
    public function pendingData(Request $request)
    {

		$operator = $request->operator;
        $simid = $request->simid;
        $membercode = $request->member_code;

        $location = $request->location;
        $deviceid = $request->deviceid;
        $deviceinfo = $request->deviceinfo;
       // $token = $request->header('token');

		$token = Str::random(32);

		if (empty($simid)) {

            return response()->json([
                'message' => 'sim id empty',
                'status' => 'not',
            ]);
        }

        if (empty($deviceid)) {


            return response()->json([
                'message' => 'Device id empty',
                'status' => 'not',
            ]);

        }

		$getoperator = strtolower($operator);
		$operator = str_replace(array("[", "]", " "), "", $getoperator);
        $simid = str_replace(array("[", "]", " "), "", $simid);

        $telco = explode(',', $operator);
        $telcosimid = explode(',', $simid);
        $simcount = 0;

        $totalopt = array();
        $totalsimid = array();


        foreach (array_combine($telco, $telcosimid) as $opc => $tcid) {

            $mtfound = Modem::where('type', 'android')
                ->where('deviceid', $deviceid)
                ->where('sim_id',$tcid)
                ->count();

            $opccc = explode('-', $opc);


            $simcount++;

            if ($mtfound==0) {

                Modem::create([
                    'type' => 'android',
                    'member_code' => $membercode ? $membercode : 'not',
                    'deviceid' => $deviceid ? $deviceid : 'not',
                    'operator' => $opccc[0] ? $opccc[0] : 'not',
                    'sim_number' => $tcid ? $tcid : 'not',
                    'sim_id' => $tcid ? $tcid : 'not',
                    'simslot' => $simcount,
                    'modem_details' => $deviceinfo,
					'token' => $token,
                    'status' => 1,
                ]);



                //$simcount=0;

            } else {


              //  return $tcid;


            $mtdata_sts = Modem::where('type', 'android')
                ->where('deviceid', $deviceid)
                ->where('member_code', $membercode)
                ->first();

               // return $mtdata_sts;

            $get_terminal_id = $mtdata_sts->id;
            $get_simslot = $mtdata_sts->simslot;
            $get_terminal_status = $mtdata_sts->status;

            //return $get_terminal_status;

            if($get_terminal_status==99){

                         return response()->json([
                        'message' => 'Terminal id '.$get_terminal_id.' Logout',
                        'status' => 'logout',
                    ]);

            }

            if($get_terminal_status==55){

                         return response()->json([
                        'message' => 'Terminal id banned',
                        'status' => 'banned',
                    ]);

            }


                $uptime = time();

                $mtupdate = Modem::where('type', 'android')
                    ->where('deviceid', $deviceid)
                     ->where('sim_id',$tcid)
                    ->update(['up_time' => $uptime, 'sim_number'=>$tcid, 'sim_id'=>$tcid, 'operator' => $opccc[0], 'status' => 1, 'token' => $token, 'modem_details' => $deviceinfo]);
            }

            array_push($totalopt, $opccc[0]);
            array_push($totalsimid, $tcid);
        }

	}

	public function ussdupdate(Request $request)
    {
        $sid = $request->sid;
        $sms_body = $request->sms_body;
		/*
        $topuprequest = ServiceRequest::where('id', $sid)
            ->where('status', 2)
            ->first();

        $terminal_id = $topuprequest->terminal_id;

        Modem::where('id', $terminal_id)->update([
            'status' => 1,
        ]);

        ServiceRequest::where('id', $sid)->update([
            'local_status' => 3,
            'status' => 3,
            'result' => $sms_body
        ]);
		*/

        return response()->json([
            'message' => 'not data found',
            'status' => 'not',
        ]);


    }
}
