<?php
/*
 * Copyright (c) 2021.
 * This file is originally created and maintained by Ariful Islam.
 * You are not allowed to modify any parts of this code or copy or even redistribute
 * full or small portion to anywhere. If you have any question
 * fee free to ask me at arif98741@gmail.com.
 * Ariful Islam
 * Software Engineer
 * https://github.com/arif98741
 * $time
 */

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends BaseController
{
    /**
     * Login api
     * @group Authentication
     * @param Request $request
     * @return JsonResponse
     * @response  {'hello'}
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Data validation error', $validator->errors());
        }

        $credentials = ['mobile' => $request->mobile, 'password' => $request->password];
        if (Auth::guard('merchant')->attempt($credentials)) {

            $user = Auth::guard('merchant')->user();

            if ($user->db_status == 0) {
                Auth::guard('merchant')->logout();
                $accountStatus = 'Your account is inactive!';
                return $this->sendError($accountStatus, ['error' => '']);
            }

            $tokenName = 'XyropayMerchantToken';
            $success['token'] = $user->createToken($tokenName)->plainTextToken;

            return $this->sendResponse($success, 'User token generated successfully');
        }

        return $this->sendError('Phone or password not matched', ['error' => 'Username or password not matched']);
    }


}
