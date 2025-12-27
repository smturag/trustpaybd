<?php

namespace App\Http\Controllers\Api\V1;

use App\Mail\CutomerWalletCreateSendMail;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\MerchantPvtPublicKey;
use App\Models\Modem;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Illuminate\Support\Facades\Log;

class MerchantPaymentController extends BaseController
{
    /**
     * Merchant Payment Controller
     * Display a listing of the payments.
     *
     * @return JsonResponse
     * @throws \Exception
     */
    public function createPayment(Request $request)
    {
        $xAuthorization = $request->header('x-authorization');
        $xAuthorizationSecret = $request->header('X-Authorization-Secret');

        $merchant = MerchantPvtPublicKey::where('api_key', $xAuthorization)->where('secret_key', $xAuthorizationSecret)->select('merchant_id')->first();
        if (empty($merchant)) {
            return $this->sendError('Invalid API key or secret key', [], 401);
        }

        $findMerchantDetails = Merchant::find($merchant['merchant_id']);
        $getAdminMerchant = null;
        $merchant_type = 'general';

        if ($findMerchantDetails->merchant_type == 'sub_merchant') {
            $getAdminMerchant = $findMerchantDetails->create_by;
            $merchant_type = 'sub_merchant';
        }

        // return  $merchant_type;

        $customErrorMessage = [
            'reference.unique' => "Duplicate reference-id $request->reference",
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required|numeric',
                'reference' => ['required', 'string', 'min:3', 'max:20', Rule::unique('payment_requests', 'reference')->where('merchant_id', $merchant->merchant_id)],
                'currency' => ['required', Rule::in(['BDT' /*'USD'*/])],
                'callback_url' => 'required|url',
                'cust_name' => 'required',
                'webhook_url'=>'nullable|url'
            ],
            $customErrorMessage,
        );

        if ($validator->fails()) {
            return $this->sendError('Data validation error', $validator->errors(), 400);
        }

        $data = $validator->validated();
        $data['callback_url'] = rtrim($data['callback_url'], '/');

        // if ($request->has('checkout_items')) {
        //     $data['checkout_items'] = json_encode($request->checkout_items);
        // }
        // if ($request->has('ext_field_1')) {
        //     $data['ext_field_1'] = json_encode($request->ext_field_1);
        // }
        // if ($request->has('ext_field_2')) {
        //     $data['ext_field_2'] = json_encode($request->ext_field_2);
        // }

        $data['request_id'] = generatePaymentRequestTrx(25);
        $data['merchant_id'] = $merchant_type == 'sub_merchant' ? $getAdminMerchant : $merchant['merchant_id'];
        $data['sub_merchant'] = $merchant_type == 'sub_merchant' ? $merchant['merchant_id'] : null;
        $data['ip'] = \request()?->ip();
        $data['user_agent'] = \request()?->userAgent();
        $data['issue_time'] = Carbon::now();
        $data['webhook_url'] = $request->webhook_url;

        $additionalData = [
            'callback_url' => $request->callback_url,
            'cust_name' => $request->cust_name,
            'cust_phone' => $request->cust_phone,
            'amount' => $request->amount,
        ];

        $singedRouteParam = [
            'invoice_id' => $data['request_id'],
        ];

        $url = URL::temporarySignedRoute('checkout', now()->addMinutes(600), $singedRouteParam);

        // $customerExist = Customer::where('email', $request->cust_email)
        //     ->orWhere('mobile', $request->cust_phone)
        //     ->exists();

        DB::beginTransaction();

        try {
            // Capture current merchant balance before creating payment
            $merchantId = $merchant_type == 'sub_merchant' ? $merchant['merchant_id'] : $merchant['merchant_id'];
            $merchantBalance = Merchant::where('id', $merchantId)->value('balance');
            $mainMerchantId = $merchant_type == 'sub_merchant' ? $getAdminMerchant : $merchant['merchant_id'];
            $mainMerchantBalance = Merchant::where('id', $mainMerchantId)->value('balance');
            
            $data['merchant_last_balance'] = $mainMerchantBalance;
            $data['merchant_new_balance'] = $mainMerchantBalance; // Will be updated on approval
            $data['sub_merchant_last_balance'] = $merchant_type == 'sub_merchant' ? $merchantBalance : null;
            $data['sub_merchant_new_balance'] = $merchant_type == 'sub_merchant' ? $merchantBalance : null; // Will be updated on approval
            
            if ($payment = PaymentRequest::create($data)) {
                // if ($customerExist == false) {
                //     $customerPassword = rand(1111, 999999);
                //     Customer::create([
                //         'customer_name' => $request->cust_name,
                //         'email' => $request->cust_email,
                //         'password' => bcrypt($customerPassword),
                //         'mobile' => $request->cust_phone,
                //         'type' => 'personal',
                //         'status' => 1,
                //         'email_verified_at' => now(),
                //         'email_verification_token' => Str::random(32),
                //         'mobile_verification_token' => Str::random(32),
                //     ]);
                //     // Mail::to($request->cust_email)->send(
                //     //     new CutomerWalletCreateSendMail([
                //     //         'email' => $request->cust_email,
                //     //         'data' => [
                //     //             'customer_email' => $request->cust_email,
                //     //             'customer_password' => $customerPassword,
                //     //         ],
                //     //     ]),
                //     // );
                // }

                $success = [
                    'request_id' => $data['request_id'],
                    'amount' => $request->amount,
                    'reference' => $request->reference,
                    'currency' => $request->currency,
                    'issue_time' => $payment->issue_time->format('Y-m-d H:i:s'),
                    'payment_url' => $url,
                ];
                DB::commit();
                return $this->sendResponse($success, 'Payment request created successfully', ResponseAlias::HTTP_CREATED);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError('Failed to create payment request' . $e->getMessage());
        }

        return $this->sendError('Failed to create payment request', []);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function customerPaymentReceived(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'currency' => ['required', Rule::in(['BDT'])],
            'receiver_phone_or_email' => 'required|min:5',
        ]);

        $data = $validator->validated();

        if ($validator->fails()) {
            return $this->sendError('Data validation error', $validator->errors(), 400);
        }

        if (is_numeric($data['receiver_phone_or_email'])) {
            $customer_mobile = $data['receiver_phone_or_email'];
        } elseif (filter_var($data['receiver_phone_or_email'], FILTER_VALIDATE_EMAIL)) {
            $customer_email = $data['receiver_phone_or_email'];
        }

        $xAuthorization = $request->header('x-authorization');
        $merchant_id = MerchantPvtPublicKey::where('api_key', $xAuthorization)->value('merchant_id');

        if (!$merchant_id) {
            return response()->json([
                'status_code' => 2001,
                'message' => 'Merchant Account Not Found.',
            ]);
        }

        $customer_id = Customer::where('email', $customer_email)->orWhere('mobile', $customer_mobile)->value('id');

        if (!$customer_id) {
            return response()->json([
                'status_code' => 500, //Invalid Api Key
                'message' => 'Customer Account Not Found.',
            ]);
        }

        $old_balance = DB::table('merchants')->where('id', $merchant_id)->value('balance');

        if ($old_balance <= $request->amount) {
            return response()->json([
                'status_code' => 2006,
                'message' => 'Insufficient Balance',
            ]);
        }

        $trxID = rand(11111111, 99999999);

        DB::beginTransaction();
        try {
            $success = DB::table('wallet_transactions')->insert([
                'customer_id' => $customer_id,
                'merchant_id' => $merchant_id,
                'credit' => $request->amount,
                'trxid' => $trxID,
                'status' => 1, //0 pending, 1-success, 2-reject
                'type' => 'payment_received',
                //'old_balance' => $old_balance,
                'payment_method' => 'wallet',
                'merchant_reference' => $request->merchant_reference,
                //'note' => $request->note,
                'ip' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $mer_success = Merchant::where('id', $customer_id)->decrement('balance', $request->amount, ['updated_at' => now()]);

            $cus_success = Customer::where('id', $customer_id)->increment('balance', $request->amount, ['updated_at' => now()]);

            if ($success && $mer_success && $cus_success) {
                DB::Commit();

                return response()->json([
                    'status_code' => 200,
                    'trxID' => $trxID,
                    'message' => 'Payment request created successfully',
                    'transaction_status' => 'success',
                    'amount' => $request->amount,
                    'receiver_phone_or_email' => $data['receiver_phone_or_email'],
                    'currency' => $data['currency'],
                    'reference' => $request->reference,
                    'completed_time' => now(),
                ]);
            }
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status_code' => 500,
                'message' => 'Something went wrong. Try again',
            ]);
        }
        return $this->sendError('Failed to create payment request', []);
    }

    public function createCashIn(Request $request)
    {
        // $xAuthorization = $request->header('x-authorization');
        // $merchant = MerchantPvtPublicKey::where('api_key', $xAuthorization)->select('merchant_id')->first();

        $xAuthorization = $request->header('x-authorization');
        $xAuthorizationSecret = $request->header('X-Authorization-Secret');

        $merchant = MerchantPvtPublicKey::where('api_key', $xAuthorization)->where('secret_key', $xAuthorizationSecret)->select('merchant_id')->first();

        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric',
            'mfs_operator' => 'required|string|exists:mfs_operators,name',
            // 'currency' => ['required', Rule::in(['BDT' /*'USD'*/])],
            'cust_number' => 'required',
            'withdraw_id' => 'nullable|unique:service_requests,trxid',
            'webhook_url' => 'sometimes|url',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Data validation error', $validator->errors(), 400);
        }

        $exists = ServiceRequest::where('trxid', $request->withdraw_id)->exists();

        if ($exists) {
            return response()->json([
                'status' => false,
                'message' => 'This trxid already exists!',
            ]);
        }

        $getMfs = DB::table('mfs_operators')->where('name', $request->mfs_operator)->first();

        $get_merchant_info = Merchant::find($merchant['merchant_id']);

        $getAdminMerchant = null;
        $merchant_type = 'general';

        if ($get_merchant_info->merchant_type == 'sub_merchant') {
            $getAdminMerchant = $get_merchant_info->create_by;
            $merchant_type = 'sub_merchant';
        }

        $getRandomActiveUserId = getRandom($request->amount, $request->mfs_operator);

        if ($merchant_type == 'sub_merchant') {
            $merchantCurrentBalance = $get_merchant_info->balance;
        } else {
            $merchantCurrentBalance = $get_merchant_info->available_balance;
        }


        $genAmount  = (float) $request->amount;
        $genBalance = (float) $merchantCurrentBalance;


        if ($genAmount >  $genBalance) {
            return response()->json(
                [
                    'status' => 'false',
                    'message' => 'Amount is greater than Merchant Balance',
                    // 'data' => $data
                ],
                400,
            );
        }

        $invoiceNumber = $request->withdraw_id ??  'TRX-' . $merchant['merchant_id'] . '-' . date('YmdHis') . '-' . rand(1000, 9999);

        //checking same number  with same amount not exutable

        $findMfsWithSameNumberSameAmount = ServiceRequest::where('number', $request->cust_number)
            ->where('amount', $request->amount)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->first();

        DB::beginTransaction();

        $merchantRate = calculateAmountFromRate($request->mfs_operator, 'P2A', 'withdraw', $merchant['merchant_id'], $request->amount);

        $getRandomActiveUserId ? ($memberRate = calculateAmountFromRateForMember($request->mfs_operator, 'P2A', 'withdraw', $getRandomActiveUserId, $request->amount)) : null;

        $service_request = new ServiceRequest();
        $service_request->trxid = $request->withdraw_id ?? $invoiceNumber;
        $service_request->merchant_id = $merchant_type == 'sub_merchant' ? $getAdminMerchant : $merchant['merchant_id'];
        $service_request->sub_merchant = $merchant_type == 'sub_merchant' ? $merchant['merchant_id'] : null;
        $service_request->mfs = $request->mfs_operator;
        $service_request->mfs_id = $getMfs->id;
        $service_request->old_balance = $merchantCurrentBalance;
        $service_request->amount = $request->amount;
        $service_request->new_balance = $merchantCurrentBalance - $request->amount;
        $service_request->sim_balance = 0.0;
        $service_request->number = $request->cust_number;
        $service_request->type = 'personal';
        $service_request->status = $getRandomActiveUserId ? 1 : 0;
        $service_request->agent_id = $getRandomActiveUserId ?? $getRandomActiveUserId;
        $service_request->webhook_url = $request->webhook_url;
        $service_request->merchant_fee = $merchantRate['general']['fee_amount'];
        $service_request->merchant_commission = $merchantRate['general']['commission_amount'];
        $service_request->sub_merchant_fee = $merchantRate['sub_merchant']['fee_amount'];
        $service_request->sub_merchant_commission = $merchantRate['sub_merchant']['commission_amount'];
        $service_request->merchant_main_amount = $merchantRate['general']['net_amount'];
        $service_request->sub_merchant_main_amount = $merchantRate['sub_merchant']['net_amount'];

        if ($getRandomActiveUserId) {
            $service_request->partner = $memberRate['member']['partner_id'];
            $service_request->partner_fee = $memberRate['member']['fee_amount'];
            $service_request->partner_commission = $memberRate['member']['commission_amount'];
            $service_request->user_fee = $memberRate['agent']['fee_amount'];
            $service_request->user_commission = $memberRate['agent']['commission_amount'];
            $service_request->partner_main_amount = $memberRate['member']['net_amount'];
            $service_request->user_main_amount = $memberRate['agent']['net_amount'];
        }

        if ($service_request->save()) {
            if ($getRandomActiveUserId) {
                $agentBalance = findAgentBalance($getRandomActiveUserId);

                DB::table('transactions')->insert([
                    'user_id' => $getRandomActiveUserId,
                    'amount' => $genAmount,
                    'charge' => 0,
                    'old_balance' => $agentBalance['mainBalance'],
                    'trx_type' => 'debit',
                    'trx' => $invoiceNumber,
                    'details' => 'Customer api payment using ' . $request->mfs_operator,
                    'user_type' => 'agent',
                    'wallet_type' => 'main',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            DB::commit();

            return response()->json(
                [
                    'status' => 'true',
                    'trxid' => $invoiceNumber,
                    // 'data' => $data
                ],
                200,
            );
        }

        DB::rollBack();

        return response()->json(
            [
                'status' => 'false',
                'message' => 'Something went wrong',
            ],
            400,
        );
    }

    public function checking_status(Request $request)
    {
        $xAuthorization = $request->header('x-authorization');
        $xAuthorizationSecret = $request->header('X-Authorization-Secret');

        $merchant = MerchantPvtPublicKey::where('api_key', $xAuthorization)->where('secret_key', $xAuthorizationSecret)->select('merchant_id')->first();
        if (empty($merchant)) {
            return $this->sendError('Invalid API key or secret key', [], 401);
        }

        $validator = Validator::make($request->all(), [
            'trnx_id' => 'required',
        ]);

        $get_data = ServiceRequest::select('number', 'mfs', 'old_balance', 'amount', 'new_balance', 'msg', 'status', 'get_trxid', 'trxid')->where('trxid', $request->trnx_id)->first();

        if ($get_data) {
            $make_status = '';
            switch ($get_data->status) {
                case 1:
                    $make_status = 'pending'; //this is waiting but we show mercent pending
                    break;
                case 2:
                    $make_status = 'success';
                    break;
                case 3:
                    $make_status = 'success';
                    break;
                case 4:
                    $make_status = 'rejected';
                    break;

                default:
                    $make_status = 'pending';
                    break;
            }

            $data = [
                'number' => $get_data->number,
                'mfs' => $get_data->mfs,
                // 'old_balance' => $get_data->old_balance,
                'amount' => $get_data->amount,
                // 'new_balance' => $get_data->new_balance,
                'msg' => $make_status == 'rejected' ? $get_data->msg : $get_data->get_trxid,
                'withdraw_id' => $get_data->trxid,
                'status' => $make_status,
            ];

            return response()->json(
                [
                    'status' => 'true',
                    'data' => $data,
                    // 'data' => $data
                ],
                200,
            );
        }

        return response()->json(
            [
                'status' => 'false',
                'message' => 'This TRXID not available',
                // 'data' => $data
            ],
            400,
        );
    }

    function checkPaymentStatus($referenceId)
    {
        if (empty($referenceId)) {
            return response()->json(
                [
                    'status' => 'true',
                    'message' => 'Reference ID data not found',
                ],
                status: 404,
            );
        }

        if ($referenceId) {
            $statusDetails = [
                'pending' => 0,
                'Completed' => [1, 2],
                'Rejected' => 3,
            ];

            // $data = PaymentRequest::select('request_id', 'amount', 'payment_method', 'reference', 'cust_name', 'cust_phone', 'note', 'reject_msg','payment_method_trx', 'status')->where('reference', $referenceId)->first();

            $data = PaymentRequest::select('request_id', 'amount', 'payment_method', 'reference', 'cust_name', 'cust_phone', 'note', 'reject_msg', 'payment_method_trx', 'status')
                ->selectRaw(
                    "
    CASE
        WHEN status = 0 THEN 'pending'
        WHEN status IN (1, 2) THEN 'completed'
        WHEN status = 3 THEN 'rejected'
        ELSE 'unknown'
    END as status
",
                )
                ->where('reference', $referenceId)
                ->first();

            if (!empty($data)) {
                // $data['statusDetails'] = $statusDetails;
                return response()->json(
                    [
                        'status' => 'true',
                        'data' => $data,
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => 'false',
                        'message' => 'Data Not found',
                    ],
                    404,
                );
            }
        }

        return response()->json(
            [
                'status' => 'true',
                'message' => 'Reference ID data not found',
            ],
            404,
        );
    }
}
