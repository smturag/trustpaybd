<?php

namespace App\Listeners;

use App\Events\McWithdrawCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\McRequest;
use App\Service\Backend\MobCashApi;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WithdrawListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(McWithdrawCreated $event): void
    {
        
        $getuser_number = Cache::get('getuser_'.$event->userid);
         
        // Log::info('json_19'.$getuser_number);
         
        $status_up = 5;
        $api_response = "";

       if($getuser_number){

           $mcapi = new MobCashApi();

           $finduserapi = $mcapi->finduser($getuser_number,$event->betuserid);
            Log::info('json2 '.json_encode($finduserapi)); 
            
            Log::info('json_token '.$getuser_number);

           if (isset($finduserapi['Token'])) {

               $usertoken = $finduserapi['Token'];

               $depositapi = $mcapi->withdraw($getuser_number,$usertoken,$event->code);
               
                       if ($depositapi['Success'] == true) {
                                   
                                   $status_up = 2;
                                   
                                   $api_response = json_encode($depositapi); 
                                   
                               }else{
                                   
                                    $status_up = 3;
                                    
                                    $api_response = json_encode($depositapi); 
                                   
                               }
               
                McRequest::where('trxid',$event->trxid)->update(['api_result'=>' finduser :'.json_encode($finduserapi).' deposit result : '.json_encode($depositapi),'status'=>$status_up]);    
                
                return;       
               
           }else{
               
               $title = $finduserapi['status'];
               
              
               $authapi = $mcapi->auth($event->userid,$event->mcpassword,$event->workcode,$event->appguid);
                
                 Log::info('json3 '.json_encode($authapi)); 
                
                   if ($authapi['Success'] && isset($authapi['Value']['Token'])) {
                       
                           $re_token = $authapi['Value']['Token'];
                           $refreshToken = $authapi['Value']['RefreshToken'];
                       
                       Cache::put('getuser_'.$event->userid, $re_token,now()->addHour(2));
                     
                     $finduserapi = $mcapi->finduser($re_token,$event->betuserid);
                      Log::info('json4 '.json_encode($finduserapi)); 
                       if (isset($finduserapi['Token'])) {
       
                           $usertoken = $finduserapi['Token'];
       
                           $depositapi = $mcapi->withdraw($re_token,$usertoken,$event->code);
                          
                               if ($depositapi['Success'] == true) {
                                   
                                   $status_up = 2;
                                   
                               }else{
                                   
                                    $status_up = 3;
                                   
                               }
                               
                        McRequest::where('trxid',$event->trxid)->update(['api_result'=>' finduser :'.json_encode($finduserapi).' deposit result : '.json_encode($depositapi),'status'=>$status_up]);                 
                
                        return;       
                
                       }else{
                           
                            $status_up = 3;
                           
                           $api_response = json_encode($finduserapi); 
                       }
                 }
               
               
           }

           

       }else{

           $mcapi = new MobCashApi();

           $authapi = $mcapi->auth($event->userid,$event->mcpassword,$event->workcode,$event->appguid);
           
           Log::info('json1 '.json_encode($authapi)); 

           if ($authapi['Success'] && isset($authapi['Value']['Token'])) {
               $token = $authapi['Value']['Token'];
               $refreshToken = $authapi['Value']['RefreshToken'];
   
               Cache::put('getuser_'.$event->userid, $token,now()->addHour(2));

               $finduserapi = $mcapi->finduser($token,$event->betuserid);
              // Log::info('json2'.$finduserapi); 
               if (isset($finduserapi['Token'])) {

                   $usertoken = $finduserapi['Token'];

                   $depositapi = $mcapi->withdraw($token,$usertoken,$event->code);
                   
                   if ($depositapi['Success'] == true) {
                       
                       $status_up = 2;
                       
                   }else{
                       
                        $status_up = 3;
                       
                   }
                   
                McRequest::where('trxid',$event->trxid)->update(['api_result'=>' finduser :'.json_encode($finduserapi).' deposit result : '.json_encode($depositapi),'status'=>$status_up]);                
                
                return;       
                   
                   
                  // Log::info('json5'.$depositapi); 
               }
   
           }

       }

       McRequest::where('trxid',$event->trxid)->update(['api_result'=>$depositapi?$api_response:$finduserapi,'status'=>$status_up]);

    }
}
