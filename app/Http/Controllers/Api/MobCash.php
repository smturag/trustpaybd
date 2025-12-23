<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\McRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MobCash extends Controller
{
    public function getReq(Request $request){
        
        $mcnew_id = McRequest::where('status', 0)->first()->id;
        
         if (!$mcnew_id) {
            return response()->json([
                'status_code' => 500,
                'success' => false,
                'message' => 'Not Found',
            ]);
        }
        
           
        
          $mc_query = McRequest::where('status', '0')->orderBy('id', 'asc')->first();
          
          $slid = $mc_query->id;
          $customerid = $mc_query->bet_customer_id;
          $amount = $mc_query->amount;
          $req_type = $mc_query->type;
        
         return response()->json([
            'status_code' => 200,
            'slid' => $slid,
            'customer_id' => $customerid,
            'amount' => $amount,
            'req_type' => $req_type,
            'success' => true,
        ]);
        
        
    }
    
    public function data_submitted(Request $request)
    {
        
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:mc_requests,id',
            'status' => 'required',
            'msg' => 'nullable|string'
        ]);

        // If validation fails, return a 422 Unprocessable Entity response with the validation errors
        if ($validator->fails()) {
            return response()->json([
                'status_code' => 422,
                'message' => $validator->errors(),
                'success' => false,
            ]);
        }
        
        
         
        $service_request = McRequest::find($request->id);

        if ($service_request->status == $request->status) {
            return response()->json(
                [
                    'status_code' => 422,
                    'message' => 'Request Already Handled',
                ],
                422
            );
        }
        
        if ($request->status == 2 || $request->status == 6) {
            if ($service_request->status != 5) {
                return response()->json(['message' => 'Request Is Not In PROCESSING'], 421);
            }
        }

        if ($request->status == 5) {
            if ($service_request->status != 1 && $service_request->status != 0) {
                return response()->json(['message' => 'Request Is Not In WAITING'], 422);
            }
        }
        
        if ($request->status == 5) {
        
            $android_id = $request->android_id;
            $workcode = $request->workcode;
            $betuser = $request->betuser;
            $betpass = $request->betpass;
            
            $service_request->andoird_id = $android_id;
            $service_request->workcode = $workcode;
            $service_request->betuser = $betuser;
            $service_request->betpass = $betpass;
        
        }
        
        $service_request->status = $request->status;
        $service_request->msg = $request->msg;
        $service_request->save();
        
        // Return a success response
        return response()->json([
            'status_code' => 200,
            'message' => 'Request Updated Successfully',
            'success' => true,
        ]);
        
    }
}
