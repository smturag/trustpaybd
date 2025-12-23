<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MemberController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required',
            'password' => 'required',
            'fcm_token'=>'nullable'
        ]);

        if (!Auth::attempt($request->only('mobile', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = Auth::user();
        User::find($user->id)->update(['fcm_token'=>$request->fcm_token??$user->fcm_token]);
        $token = $user->createToken('API Token')->plainTextToken;
        $user->all_balance = findAgentBalance($user->id);

        return response()->json(
            [
                'token' => $token,
                'user'=> $user
            ],
            200
        );
    }

    public function userInfo()
    {
        $user = Auth::user();
        if ($user->user_type == 'agent') {
            $getBalance = findAgentBalance($user->id);
            $user->balance = $getBalance;
        }

        return response()->json(['user' => $user, 'status' => true], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function totalRequest(){
        $user = Auth::user();

        $paymentRequest = PaymentRequest::where('agent', $user->member_code)
            ->whereNotIn('status', [1, 2, 3])
            ->count();
        $serviceRequest = DB::table('service_requests')->where('agent_id', $user->id)->whereNotIn('status', [ 2, 3,4])
        ->count();

        $data = ['paymentRequest'=>$paymentRequest,'serviceRequest'=>$serviceRequest ];

        return $data;
    }
}
