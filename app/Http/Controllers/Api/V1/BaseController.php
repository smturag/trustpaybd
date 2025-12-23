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

use App\AppTrait\AuthTrait;
use App\AppTrait\SettingTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * 200 success
 * 201 created
 * 210 already exist
 * 211 deleted
 * 203 already exist
 * 400 bad request
 * 401 unauthorized
 * 403 permission denied
 * 402 payment required
 * 404 not found
 * 405 method not allowed
 * 406 data not found
 * 500 internal server error
 * 501 not implemented
 * 502 bad gateway
 * 503 service unavailable
 * 505 http version not supported
 * 504 gateway timeout
 * status codes
 */
class BaseController extends Controller
{
    /**
     * success response method.
     *
     * @param $result
     * @param $message
     * @param int $code
     * @return JsonResponse
     */
    public function sendResponse($result, $message, int $code = ResponseAlias::HTTP_OK): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data' => $result,
        ];

        return response()->json($response, $code);
    }


    /**
     * return error response.
     * @param $error
     * @param array $errorMessages
     * @param int $code
     * @return JsonResponse
     */
    public function sendError($error, $errorMessages = [], int $code = 404): JsonResponse
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}
