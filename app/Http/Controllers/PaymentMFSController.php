<?php

namespace App\Http\Controllers;

use App\Helpers\BalanceManagerConstant;
use App\Helpers\PaymentConstant;
use App\Mail\WalletOtpSendMail;
use App\Models\Customer;
use App\Models\Merchant;
use App\Models\MfsOperator;
use App\Models\Modem;
use App\Models\Otp;
use App\Models\PaymentMethod;
use App\Models\PaymentRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Models\WalletTransaction;
use App\Service\Backend\PaymentRequestService;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Cache;

class PaymentMFSController extends Controller
{
    protected $paymentRequest;
    public function __construct()
    {
        $this->paymentRequest = new PaymentRequestService();
    }

    /**
     *
     * @param Request $request
     * @param string $invoice
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function checkout(Request $request, string $invoice)
    {
        if (!$request->hasValidSignature()) {
            abort(403, 'Payment url expired');
        }

        $payment_request = $this->paymentRequest->getPaymentRequestByRequestid($invoice);
        //$paymentMethods = PaymentMethod::where('status', 1)->inRandomOrder()->get();

        $merchantId = $payment_request->sub_merchant ?? $payment_request->merchant_id;
        $merchant_v1_permission = Merchant::select('v1_p2c', 'v1_p2a','v1_p2p','v1_direct_gateway','v1_manual_gateway')->find($merchantId);

        $paymentMethods = PaymentMethod::where('status', 1)->inRandomOrder()->get()->unique('mobile_banking');

        foreach ($paymentMethods as $paymentMethod) {
            $paymentMethod->mfs = $paymentMethod->mfs_operator()->first();
        }

        //return $paymentMethods;

        $data = [
            'paymentMethods' => $paymentMethods,
            'payment_request' => $payment_request,
            'permission' => $merchant_v1_permission,
        ];
        return view('merchant.payments.select-method')->with($data);
    }

    /**
     * @param Request $request
     * @param string $invoice
     * @return Application|Factory|View|\Illuminate\Foundation\Application
     */
    public function transaction_input(Request $request, string $invoice)
    {
        // $paymentMethods = PaymentMethod::whereHas('mfs_operator', function ($query) {
        //     $query->where('name', \request()->method);
        // })
        //     ->whereHas('modem', function ($query) {
        //         $query->where('up_time', '>=', time() - env('TRANSACTION_INPUT_TIME'))->whereIn('operating_status',[1,3]);
        //     })
        //     ->with('mfs_operator')
        //     ->with('modem')
        //     ->where('status', 1)
        //     ->inRandomOrder()
        //     ->first();

        $mfs_name = \request()->method;

        $paymentMethods = Modem::where('up_time', '>=', time() - env('TRANSACTION_INPUT_TIME'))
            ->whereIn('operating_status', [2, 3])
            ->whereIn('operator_service', ['on', $mfs_name])
            ->inRandomOrder()
            ->first();

        $data = [
            'paymentMethod' => $paymentMethods,
            'operator_name' => $mfs_name,
            'payment_request' => $this->paymentRequest->getPaymentRequestByRequestid($invoice),
            'app_name' => app_config('AppName'),
            'image' => app_config('AppLogo'),
            'number' => \request()->number,
            'type' => \request()->type,
        ];

        return view('merchant.payments.transaction-input')->with($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     * @throws ValidationException
     *
     */
    public function payment_save(Request $request)
    {
        $data = $this->validate($request, [
            'sim_id' => 'required',
            'trxid' => 'required|string|min:5',
            'request_id' => 'required|string|min:20',
            'payment_method' => 'required|string',
            'type' => 'required|string',
        ]);

        $approveCheck = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
            ->whereRaw('LOWER(payment_method_trx) = ?', [strtolower($request->trxid)])
            ->first();

        $findThisRequest = PaymentRequest::where('request_id', $request->request_id)->first();

        $paymentType = strtoupper((string) $request->type);
        $paymentMethod = $request->payment_method;
        $mfsOperator = MfsOperator::whereRaw('LOWER(name) = ?', [strtolower($paymentMethod)])
            ->where('type', $paymentType)
            ->first();
        if (!$mfsOperator) {
            $mfsOperator = MfsOperator::whereRaw('LOWER(name) = ?', [strtolower($paymentMethod)])->first();
        }
        $paymentType = $mfsOperator->type ?? $paymentType;
        $mfsOperatorId = $mfsOperator->id ?? null;

        Log::info('payment_save: rate lookup context', [
            'request_id' => $request->request_id,
            'payment_method' => $paymentMethod,
            'payment_type' => $paymentType,
            'mfs_operator_id' => $mfsOperatorId,
            'amount' => $findThisRequest->amount,
        ]);

        $merchantId = $findThisRequest->sub_merchant ?: $findThisRequest->merchant_id;
        $needsMerchantRate = $findThisRequest->merchant_main_amount === null || $findThisRequest->merchant_main_amount <= 0 ||
            ($findThisRequest->sub_merchant && ($findThisRequest->sub_merchant_main_amount === null || $findThisRequest->sub_merchant_main_amount <= 0));

        if ($merchantId && $needsMerchantRate) {
            $merchantRate = calculateAmountFromRate(
                $paymentMethod,
                $paymentType,
                'deposit',
                $merchantId,
                $findThisRequest->amount,
                $mfsOperatorId
            );

            Log::info('payment_save: merchant rate calculated', [
                'request_id' => $request->request_id,
                'merchant_id' => $merchantId,
                'rates' => $merchantRate,
            ]);

            $findThisRequest->update([
                'merchant_fee' => $merchantRate['general']['fee_amount'],
                'merchant_commission' => $merchantRate['general']['commission_amount'],
                'sub_merchant_fee' => $merchantRate['sub_merchant']['fee_amount'],
                'sub_merchant_commission' => $merchantRate['sub_merchant']['commission_amount'],
                'merchant_main_amount' => $merchantRate['general']['net_amount'],
                'sub_merchant_main_amount' => $merchantRate['sub_merchant']['net_amount'],
                'payment_type' => $paymentType,
            ]);
        } else {
            $findThisRequest->update([
                'payment_type' => $paymentType,
            ]);
        }


        if (($approveCheck->status == 1 || $approveCheck->status == 2) && $approveCheck->balance_updated == 1  ) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction Already Succeeded',
            ]);
        }

        // Check if payment request with same TRX ID exist or NOT
        $exists_data = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
            ->whereRaw('LOWER(payment_method_trx) = ?', [strtolower($request->trxid)])
            ->where('payment_method', $request->payment_method)
            ->where('amount', $request->amount)
            ->first();

        if ($exists_data != null) {
            // If exists then use that existing one from now on instead of the given request_id
            $paymentRequest = $exists_data;

            // Change the request id in data so it uses the existing req id instead of the new one
            $data['request_id'] = $paymentRequest->request_id;
            $data['sim_id'] = $paymentRequest->sim_id;
            $data['trxid'] = $paymentRequest->payment_method_trx;
            $data['payment_method'] = $paymentRequest->payment_method;
            $data['amount'] = $paymentRequest->amount;

            // Match the phone number of the user to make sure that he's the real person making this request
            $currentData = PaymentRequest::where('request_id', $data['request_id'])->first();
            if ($paymentRequest->cust_phone != $currentData->cust_phone) {
                return response()->json([
                    'success' => false,
                    'message' => 'Duplicate transaction ID',
                ]);
            }
        } else {
            // If doesn't exist then use the new request_id to find the new request
            $paymentRequest = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
                ->where('request_id', $data['request_id'])
                ->where('payment_method', $request->payment_method)
                ->first();
        }

        // Check if the provided agent actually exists
        // $agent = DB::table('payment_methods')
        //     ->join('mfs_operators', 'mfs_operators.id', '=', 'payment_methods.mobile_banking')
        //     ->where('mfs_operators.name', $data['payment_method'])
        //     ->where('payment_methods.sim_id', $data['sim_id'])
        //     ->first();

        $agent = DB::table('modems')->where('sim_number', $data['sim_id'])->first();
        // $agent = User::where()

        if (!$agent) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to find agent information. Contact with admin',
            ]);
        }

        $cacheKey = 'commission_updated_' . $data['request_id'];

        $needsMemberRate = $findThisRequest->user_main_amount === null || $findThisRequest->user_main_amount <= 0 ||
            $findThisRequest->partner_main_amount === null || $findThisRequest->partner_main_amount <= 0;

        if ($needsMemberRate || !Cache::has($cacheKey)) {
            $findAgent = User::where('member_code', $agent->member_code)->first();

            if ($findAgent) {
                $memberRate = calculateAmountFromRateForMember(
                    $paymentMethod,
                    $paymentType,
                    'deposit',
                    $findAgent->id,
                    $findThisRequest->amount,
                    $mfsOperatorId
                );

                Log::info('payment_save: member rate calculated', [
                    'request_id' => $data['request_id'],
                    'agent_id' => $findAgent->id,
                    'rates' => $memberRate,
                ]);

                DB::table('payment_requests')
                    ->where('request_id', $data['request_id'])
                    ->update([
                        'partner_fee' => $memberRate['member']['fee_amount'],
                        'partner_commission' => $memberRate['member']['commission_amount'],
                        'user_fee' => $memberRate['agent']['fee_amount'],
                        'user_commission' => $memberRate['agent']['commission_amount'],
                        'partner_main_amount' => $memberRate['member']['net_amount'],
                        'user_main_amount' => $memberRate['agent']['net_amount'],
                    ]);

                // Cache the update flag for 24 hours (or indefinitely)
                Cache::forever($cacheKey, true);

                Log::info('Commission & fee updated successfully', ['request_id' => $data['request_id']]);
            }
        }

        // If this request's balance related calculations are done or not
        if ($paymentRequest->balance_updated == 1) {
            // This means all the jobs related to this request is complete,
            // now we can directly show the status of the request to the user
            if ($paymentRequest->status == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Already Pending',
                ]);
            } elseif ($paymentRequest->status == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Already Succeeded',
                ]);
            } elseif ($paymentRequest->status == 2) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Already Verified',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Already Rejected',
                ]);
            }
        } else {
            // This means balance not updated, need to do the tasks
            $balanceManagerQuery = DB::table('balance_managers')->where('sim', $data['sim_id'])->where('trxid', $data['trxid'])->where('amount', $data['amount']);

            $balanceManagerTypeMap = [
                'bkash' => [
                    'p2a' => ['bkcashout'],
                    'p2p' => ['bkrc'],
                    'p2c' => ['bkpayment'],
                ],
                'nagad' => [
                    'p2a' => ['ngcashout'],
                    'p2p' => ['ngrc'],
                    'p2c' => ['ngpayment'],
                ],
                'rocket' => [
                    'p2a' => ['rccashout'],
                    'p2p' => ['rcrc'],
                    'p2c' => ['rcpayment'],
                ],
                'upay' => [
                    'p2a' => ['upcashout', 'uprc'],
                ],
            ];

            $paymentMethod = $data['payment_method'] ?? null;
            $paymentType = $data['type'] ?? null;
            $normalizedPaymentType = is_string($paymentType) ? strtolower($paymentType) : $paymentType;

            if ($paymentMethod && $normalizedPaymentType && isset($balanceManagerTypeMap[$paymentMethod][$normalizedPaymentType])) {
                $balanceManagerQuery->whereIn('type', $balanceManagerTypeMap[$paymentMethod][$normalizedPaymentType]);
            }

            $balanceManager = $balanceManagerQuery->first();

            $paymentTrxId = generateInvoiceNumber(6);

            if ($balanceManager != null) {
                if ($balanceManager->status == BalanceManagerConstant::SUCCESS || $balanceManager->status == BalanceManagerConstant::APPROVED) {
                    DB::beginTransaction();
                    try {
                        $updateStatus = DB::table('payment_requests')
                            ->where('request_id', $data['request_id'])
                            ->update([
                                'sim_id' => $data['sim_id'],
                                'trxid' => $paymentTrxId,
                                'payment_method_trx' => $data['trxid'],
                                'payment_method' => $data['payment_method'],
                                'status' => 1,
                                'agent' => $agent->member_code,
                                'partner' => getPartnerFromAgent($agent->member_code)->member_code,
                                'balance_updated' => 1,
                                'updated_at' => now(),
                            ]);

                        paymentRequestApprovedBalanceHandler($data['request_id'], 'req_id');

                        $responseArray = [
                            'payment_method' => $data['payment_method'],
                            'request_id' => $data['request_id'],
                            'reference' => $paymentRequest->reference,
                            'sim_id' => $data['sim_id'],
                        ];

                        if ($updateStatus) {
                            $responseArray = [
                                'payment' => 'success', // or pending/rejected etc.
                                'payment_method' => $data['payment_method'],
                                'request_id' => $data['request_id'],
                                'reference' => $paymentRequest->reference,
                                'sim_id' => $data['sim_id'],
                                'trxid' => $data['trxid'],
                            ];
                        } else {
                            $responseArray['payment'] = 'pending';

                            $responseArray = [
                                'payment' => 'pending', // or pending/rejected etc.
                                'payment_method' => $data['payment_method'],
                                'request_id' => $data['request_id'],
                                'reference' => $paymentRequest->reference,
                                'sim_id' => $data['sim_id'],
                            ];
                        }
                        $responseArray['amount'] = $paymentRequest->amount;

                        $queryString = http_build_query($responseArray);
                        $url = $paymentRequest->callback_url . '/?' . $queryString;
                        DB::Commit();

                        return response()->json([
                            'success' => true,
                            'message' => 'Transaction is successfully verified',
                            'url' => $url,
                        ]);
                    } catch (Exception $e) {
                        DB::rollback();
                        return response()->json([
                            'success' => false,
                            'message' => 'Something went wrong. Try again1',
                        ]);
                    }
                } else {
                    $updateStatus = DB::table('payment_requests')
                        ->where('request_id', $data['request_id'])
                        ->update([
                            'sim_id' => $data['sim_id'],
                            'trxid' => $paymentTrxId,
                            'payment_method_trx' => $data['trxid'],
                            'payment_method' => $data['payment_method'],
                            'status' => 0,
                            'agent' => $agent->member_code,
                            'partner' => getPartnerFromAgent($agent->member_code)->member_code,
                            'updated_at' => now(),
                        ]);

                    $responseArray = [
                        'payment' => 'pending', // or pending/rejected etc.
                        'payment_method' => $data['payment_method'],
                        'request_id' => $data['request_id'],
                        'reference' => $paymentRequest->reference,
                        'sim_id' => $data['sim_id'],
                    ];
                    $responseArray['amount'] = $paymentRequest->amount;
                    $queryString = http_build_query($responseArray);
                    $url = $paymentRequest->callback_url . '/?' . $queryString;

                    return response()->json([
                        'success' => false,
                        'message' => 'Transaction Pending',
                        'url' => $url,
                    ]);
                }
            } elseif ($paymentRequest->status == 0) {
                if ($paymentRequest->sim_id == '') {
                    // $agent = DB::table('payment_methods')
                    //     ->join('mfs_operators', 'mfs_operators.id', '=', 'payment_methods.mobile_banking')
                    //     ->where('mfs_operators.name', $data['payment_method'])
                    //     ->where('payment_methods.sim_id', $data['sim_id'])
                    //     ->first();
                    $agent = DB::table('modems')->where('sim_number', $data['sim_id'])->first();

                    DB::table('payment_requests')
                        ->where('request_id', $data['request_id'])
                        ->update([
                            'sim_id' => $data['sim_id'],
                            'payment_method_trx' => $data['trxid'],
                            'payment_method' => $data['payment_method'],
                            'agent' => $agent->member_code,
                            'partner' => getPartnerFromAgent($agent->member_code)->member_code,
                            'updated_at' => now(),
                        ]);
                }

                $responseArray = [
                    'payment' => 'pending', // or pending/rejected etc.
                    'payment_method' => $data['payment_method'],
                    'request_id' => $data['request_id'],
                    'reference' => $paymentRequest->reference,
                    'sim_id' => $data['sim_id'],
                ];
                $responseArray['amount'] = $paymentRequest->amount;
                $queryString = http_build_query($responseArray);
                $url = $paymentRequest->callback_url . '/?' . $queryString;

                return response()->json([
                    'success' => false,
                    'message' => 'Transaction Pending',
                    'url' => $url,
                ]);
            } elseif ($paymentRequest->status == PaymentConstant::SUCCESS) {
                //success
                DB::beginTransaction();
                try {
                    $updateStatus = DB::table('payment_requests')
                        ->where('request_id', $data['request_id'])
                        ->update([
                            'sim_id' => $data['sim_id'],
                            'trxid' => $paymentTrxId,
                            'payment_method' => $data['payment_method'],
                            'agent' => $agent->member_code,
                            'partner' => getPartnerFromAgent($agent->member_code)->member_code,
                            'balance_updated' => 1,
                            'updated_at' => now(),
                        ]);

                    paymentRequestApprovedBalanceHandler($data['request_id'], 'req_id');

                    $responseArray = [
                        'payment_method' => $data['payment_method'],
                        'request_id' => $data['request_id'],
                        'reference' => $paymentRequest->reference,
                        'sim_id' => $data['sim_id'],
                    ];

                    if ($updateStatus) {
                        //get customer current balance
                        $customerDbBalance = DB::table('merchants')->select('balance')->where('id', $paymentRequest->merchant_id)->first();

                        $responseArray['payment'] = 'success';
                        // $responseArray['trxid'] = $paymentTrxId;
                        $responseArray['trxid'] = $data['trxid'];

                        $responseArray = [
                            'payment' => 'success',
                            'payment_method' => $data['payment_method'],
                            'request_id' => $data['request_id'],
                            'reference' => $paymentRequest->reference,
                            'sim_id' => $data['sim_id'],
                            'trxid' => $data['trxid'],
                        ];
                    } else {
                        $responseArray['payment'] = 'pending';
                        $responseArray = [
                            'payment' => 'pending',
                            'payment_method' => $data['payment_method'],
                            'request_id' => $data['request_id'],
                            'reference' => $paymentRequest->reference,
                            'sim_id' => $data['sim_id'],
                        ];
                    }
                    $responseArray['request_id'] = $paymentRequest->request_id;
                    $responseArray['amount'] = $paymentRequest->amount;

                    paymentRequestApprovedBalanceHandler($paymentRequest->request_id, 'req_id');

                    $queryString = http_build_query($responseArray);
                    $url = $paymentRequest->callback_url . '/?' . $queryString;
                    DB::Commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Transaction is successfully completed',
                        'url' => $url,
                    ]);
                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Try again',
                    ]);
                }
            } elseif ($paymentRequest->status == PaymentConstant::REJECTED) {
                //rejected

                DB::beginTransaction();
                try {
                    DB::table('payment_requests')
                        ->where('request_id', $data['request_id'])
                        ->update([
                            'sim_id' => $data['sim_id'],
                            'trxid' => $paymentTrxId,
                            'payment_method' => $data['payment_method'],
                            'agent' => $agent->member_code,
                            'partner' => getPartnerFromAgent($agent->member_code)->member_code,
                            'balance_updated' => 1,
                            'updated_at' => now(),
                        ]);

                    $responseArray = [
                        'payment_method' => $data['payment_method'],
                        'request_id' => $data['request_id'],
                        'reference' => $paymentRequest->reference,
                        'sim_id' => $data['sim_id'],
                    ];

                    $responseArray['payment'] = 'rejected';
                    $responseArray = [
                        'payment' => 'rejected',
                        'payment_method' => $data['payment_method'],
                        'request_id' => $data['request_id'],
                        'reference' => $paymentRequest->reference,
                        'sim_id' => $data['sim_id'],
                    ];
                    // $responseArray['trxid'] = $paymentTrxId;
                    $responseArray['trxid'] = $data['trxid'];

                    $responseArray['request_id'] = $paymentRequest->request_id;
                    $responseArray['amount'] = $paymentRequest->amount;

                    $queryString = http_build_query($responseArray);
                    $url = $paymentRequest->callback_url . '/?' . $queryString;
                    DB::Commit();

                    $paymentRequest = DB::table('payment_requests')->where('request_id', $data['request_id'])->first();
                    return response()->json([
                        'success' => true,
                        'message' => 'Transaction is rejected.',
                        'url' => $url,
                    ]);
                } catch (Exception $e) {
                    DB::rollback();
                    return response()->json([
                        'success' => false,
                        'message' => 'Something went wrong. Try again3',
                    ]);
                }
            }
        }
    }

    /**
     * @throws ValidationException
     */
    public function payment_auto_processing(Request $request)
    {
        $data = $this->validate($request, [
            'sim_id' => 'required',
            'trxid' => 'required|string|min:5',
            'request_id' => 'required|string|min:20',
            'payment_method' => 'required|string',
        ]);

        $paymentRequest = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
            ->where('request_id', $data['request_id'])
            ->first();
        $paymentTrxId = generatePaymentRequestTrx(12);

        $responseArray['success'] = 3;
        // $responseArray['trxid'] = $paymentTrxId;
        $responseArray['trxid'] = $request->trxid;

        $responseArray['request_id'] = $paymentRequest->request_id;
        $responseArray['amount'] = $paymentRequest->amount;

        $queryString = http_build_query($responseArray);
        $url = $paymentRequest->callback_url . '/?' . $queryString;
        return response()->json([
            'success' => true,
            'message' => 'Transaction status automatically changed to processing and redirecting to merchant page',
            'url' => $url,
        ]);
    }

    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function otpSend(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input_value' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'error' => $validator->errors()->all(),
            ];
            return response()->json($data);
        }

        $userData = Customer::where(function ($query) use ($request) {
            $inputValue = $request->input_value;
            $query->where('email', $inputValue)->orWhere('mobile', $inputValue);
        })->first();

        if (!$userData) {
            $data = [
                'success' => false,
                'error' => ['No wallet found for the given input'],
            ];
            return response()->json($data);
        }

        $otpData = Otp::where([
            'purpose' => 'wallet-pay-to-merchant-by-customer',
            'purpose_id' => $userData->id,
            'status' => 0,
        ])
            ->select('sent', 'code', 'expiration')
            ->orderBy('id', 'desc')
            ->first();

        if ($otpData) {
            $sentTime = $otpData->sent;
            $nextSentTime = Carbon::createFromDate($sentTime)->addSecond(10)->format('Y-m-d H:i:s');
            if (Carbon::now() < $nextSentTime) {
                $data = [
                    'success' => false,
                    'error' => ['You can request for otp after 30s'],
                ];
                return response()->json($data);
            }
        }

        DB::beginTransaction();
        try {
            $otp = rand(111111, 999999);
            Otp::where('purpose', 'wallet-pay-to-merchant-by-customer')->where('purpose_id', $userData->id)->delete();

            $data = [
                'sent' => Carbon::now(),
                'code' => $otp,
                'purpose' => 'wallet-pay-to-merchant-by-customer',
                'expiration' => Carbon::now()->addMinute(10)->format('Y-m-d H:i:s'),
                'purpose_id' => $userData->id,
            ];
            Otp::create($data);
            Mail::to($userData->email)->send(
                new WalletOtpSendMail([
                    'email' => $userData->email,
                    'data' => $otp,
                ]),
            );

            DB::commit();
            return [
                'success' => true,
                'message' => ['Otp successfully sent to email. Please check inbox , if not found check from spam'],
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'error' => ['Failed to send otp. Something went wrong ' . $e->getMessage()],
            ];
        }
    }

    /**
     * Wallet Payment Using Otp Verify
     * @param Request $request
     * @return array
     */
    public function otpVerify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'input_value' => 'required',
            'otp_value' => 'required|min:6|max:6',
            'request_id' => 'required',
        ]);

        if ($validator->fails()) {
            $data = [
                'success' => false,
                'error' => $validator->errors()->all(),
            ];
            return response()->json($data);
        }

        // 1. Fetch user data using email or mobile directly
        $inputValue = $request->input_value;
        $customerData = Customer::where('email', $inputValue)->orWhere('mobile', $inputValue)->first();

        if (!$customerData) {
            $data = [
                'success' => false,
                'error' => ['No wallet found for the given input'],
            ];
            return response()->json($data);
        }

        // 2. Use Eloquent's query builder for Otp
        $otpData = Otp::where([
            'purpose' => 'wallet-pay-to-merchant-by-customer',
            'purpose_id' => $customerData->id,
            'code' => $request->otp_value,
            'status' => 0,
        ])
            ->latest('id')
            ->first();

        if ($otpData == null) {
            $data = [
                'success' => false,
                'error' => ['Otp is invalid'],
            ];
            return response()->json($data);
        }

        if ($otpData->expiration < now()) {
            $data = [
                'success' => false,
                'error' => ['Otp is expired'],
            ];
            return response()->json($data);
        }

        $paymentRequest = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
            ->where('request_id', $request->request_id)
            ->first();
        if ($paymentRequest == null) {
            $data = [
                'success' => false,
                'error' => ['Payment page expired'],
            ];
            return response()->json($data);
        }

        $customerWalletBalance = $customerData->balance;
        $paymentRequestAmount = $paymentRequest->amount;

        if ($customerWalletBalance < $paymentRequestAmount) {
            $data = [
                'success' => false,
                'error' => ['Insufficient wallet fund. Please deposit your wallet'],
            ];
            return response()->json($data);
        }

        DB::beginTransaction();
        try {
            Customer::where('id', $customerData->id)->decrement('balance', $paymentRequestAmount, ['updated_at' => now()]);
            Merchant::where('id', $paymentRequest->merchant_id)->increment('balance', $paymentRequestAmount, ['updated_at' => now()]);
            $paymentTrxId = generatePaymentRequestTrx(12);

            DB::table('payment_requests')
                ->where('request_id', $request->request_id)
                ->update([
                    'trxid' => $paymentTrxId,
                    'payment_method' => 'wallet',
                    'status' => 1,
                    'updated_at' => now(),
                ]);
            Otp::where('id', $otpData->id)->update(['status' => 1]);

            Transaction::create([
                'user_id' => $paymentRequest->merchant_id,
                'amount' => $paymentRequestAmount,
                'charge' => 0,
                'old_balance' => $customerData->balance,
                'trx_type' => 'credit',
                'trx' => $paymentTrxId,
                'details' => 'Customer api payment using wallet',
                'user_type' => 'merchant',
                'wallet_type' => 'main',
                'created_at' => now(),
            ]);

            WalletTransaction::create([
                'customer_id' => $customerData->id,
                'merchant_id' => $paymentRequest->merchant_id,
                'old_balance' => $customerData->balance,
                'payment_method' => 'wallet',
                'debit' => $paymentRequestAmount,
                'ip' => $request->ip(),
                'type' => 'payment',
                'status' => 1,
                'trxid' => $paymentTrxId,
                'note' => 'customer wallet payment',
            ]);

            $responseArray['payment_method'] = 'wallet';
            $responseArray['request_id'] = $paymentRequest->request_id;
            $responseArray['success'] = 1;
            $responseArray['reference'] = $paymentRequest->reference;
            $responseArray['trxid'] = $paymentTrxId;

            DB::commit();
            $queryString = http_build_query($responseArray);
            $url = $paymentRequest->callback_url . '/?' . $queryString;
            return [
                'success' => true,
                'message' => ['Payment successfully done using wallet. You will be redirect to merchant website soon'],
                'url' => $url,
            ];
        } catch (Exception $e) {
            DB::rollback();
            return [
                'success' => false,
                'error' => ['Failed to process payment. Something went wrong ' . $e->getMessage()],
            ];
        }
    }

    public function check_bkash()
    {
        // $proxyUrl = 'https://cors-anywhere.herokuapp.com/';
        $url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';

        $data = [
            'app_key' => '4f6o0cjiki2rfm34kfdadl1eqq',
            'app_secret' => '2is7hdktrekvrbljjh44ll3d9l1dtjo4pasmjvs5vl5qr3fug4b',
        ];

        $headers = ['Content-Type: application/json', 'Accept: application/json', 'username: sandboxTokenizedUser02', 'password: sandboxTokenizedUser02@12345'];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        // Execute cURL session and get the response
        $response = curl_exec($ch);

        // Check for cURL errors
        if ($response === false) {
            $error = curl_error($ch);
            curl_close($ch);
            return response()->json(['error' => $error], 500);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the response
        $responseData = json_decode($response, true);

        $create_payment_url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/create';
        $create_payment_headers = ['Content-Type: application/json', 'Accept: application/json', 'Authorization:' . $responseData['id_token'], 'X-App-Key:' . $data['app_key']];
        $create_payments_data = [
            'mode' => '0011', // Mandatory String
            'payerReference' => '01770618575', // Mandatory String (wallet number pre-populated)
            'callbackURL' => 'https://your-merchant-platform.com/callback', // Mandatory String (base URL for callback)
            'amount' => '1', // Mandatory String (payment amount)
            'currency' => 'BDT', // Mandatory String (currency)
            'intent' => 'sale', // Mandatory String (intent for checkout)
            'merchantInvoiceNumber' => 'INV123456789', // Mandatory String (unique invoice number)
            'merchantAssociationInfo' => 'MI05MID54RF09123456789', // Optional String (TLV formatted data)
        ];

        $ch2 = curl_init();

        // Set cURL options
        curl_setopt($ch2, CURLOPT_URL, $create_payment_url);
        curl_setopt($ch2, CURLOPT_POST, true);
        curl_setopt($ch2, CURLOPT_HTTPHEADER, $create_payment_headers);
        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($create_payments_data));

        // Execute cURL session and get the response
        $response2 = curl_exec($ch2);

        // Check for cURL errors
        if ($response2 === false) {
            $error = curl_error($ch);
            curl_close($ch2);
            return response()->json(['error' => $error], 500);
        }

        $responseData2 = json_decode($response2, true);

        // Return the response
        return response()->json($responseData2);
    }

    public function live_api_submit(Request $request)
    {
        $check_mfs = PaymentMethod::find($request->method_id);
        $check_payments_method = MfsOperator::find($check_mfs->mobile_banking);
        if ($check_payments_method->name == 'bkash') {
            // $url = 'https://tokenized.sandbox.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';
            $url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/token/grant';

            // $data = [
            //     'app_key' => $check_mfs->app_key,
            //     'app_secret' => $check_mfs->app_secret,
            // ];

            $data = [
                'app_key' => '9eJFy3i75ho3H2kY8At6eW5Itc',
                'app_secret' => 'RBNTyfnGSY4NBgpNjcCf0P3mDHumJYdidN5L2pDKudPU2Hq6IKP4',
            ];

            //  $headers = ['Content-Type: application/json', 'Accept: application/json', 'username:' . $check_mfs->member_code, 'password:' . $check_mfs->password];
            $headers = ['Content-Type: application/json', 'Accept: application/json', 'username: 01871168733', 'password: dV[%kn89<t]'];

            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $response = curl_exec($ch);

            // return $response;

            if ($response === false) {
                $error = curl_error($ch);
                curl_close($ch);
                return redirect()->back();
                return response()->json(['error' => $error], 500);
            }

            // Close cURL session
            curl_close($ch);

            // Decode the response
            $responseData = json_decode($response, true);

            $create_payment_url = 'https://tokenized.pay.bka.sh/v1.2.0-beta/tokenized/checkout/create';
            $create_payment_headers = ['Content-Type: application/json', 'Accept: application/json', 'Authorization:' . $responseData['id_token'], 'X-App-Key:' . $data['app_key']];
            $create_payments_data = [
                'agreementID' => $request->request_id,
                'mode' => '0011', // Mandatory String
                'payerReference' => '0000', // Mandatory String (wallet number pre-populated)
                // 'callbackURL' => $request->callback_url, // Mandatory String (base URL for callback)
                'callbackURL' => route('redirect_url'),
                'amount' => $request->amount, // Mandatory String (payment amount)
                'currency' => 'BDT', // Mandatory String (currency)
                'intent' => 'sale', // Mandatory String (intent for checkout)
                'merchantInvoiceNumber' => $request->reference, // Mandatory String (unique invoice number)
                'merchantAssociationInfo' => 'MI05MID54RF09123456789', // Optional String (TLV formatted data)
            ];

            $ch2 = curl_init();

            // Set cURL options
            curl_setopt($ch2, CURLOPT_URL, $create_payment_url);
            curl_setopt($ch2, CURLOPT_POST, true);
            curl_setopt($ch2, CURLOPT_HTTPHEADER, $create_payment_headers);
            curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch2, CURLOPT_POSTFIELDS, json_encode($create_payments_data));

            // Execute cURL session and get the response
            $response2 = curl_exec($ch2);

            // Check for cURL errors
            if ($response2 === false) {
                $error = curl_error($ch);
                curl_close($ch2);
                return redirect()->back();
                return response()->json(['error' => $error], 500);
            }

            $responseData2 = json_decode($response2, true);

            if (!isset($responseData2['bkashURL'])) {
                return redirect()->back();
            }

            Session::put([
                'callbackURL' => $request->callback_url,
                'merchantInvoiceNumber' => $request->reference,
                'amount' => $request->amount,
                'merchant_id' => $request->merchant_id,
            ]);

            DB::table('payment_requests')
                ->where('request_id', $request->request_id)
                ->update([
                    'trxid' => $responseData2['paymentID'],
                    'payment_method' => 'api_bkash',
                    'status' => 0,
                    'updated_at' => now(),
                ]);

            $url = $responseData2['bkashURL'];

            return redirect($url);
            // return view('merchant.payments.api_view',compact('url'));
        }
    }

    public function redirect_url()
    {
        return view('merchant.payments.redirect_url');
    }

    public function submit_redirect(Request $request)
    {
        Log::info('Submit Redirect Request:', $request->all());

        $callbackURL = Session::get('callbackURL');
        $reference = Session::get('merchantInvoiceNumber');
        $amount = Session::get('amount');
        $merchant_id = Session::get('merchant_id');

        $status = $request->input('status');
        $get_paymentID = $request->input('paymentID');

        Session::forget('callbackURL');
        Session::forget('merchantInvoiceNumber');
        Session::forget('amount');
        Session::forget('merchant_id');

        Log::info($status);

        $check_payment_request = PaymentRequest::where('reference', $reference)->first();

        if ($check_payment_request->status != 0) {
            return $callbackURL;
        }

        try {
            if ($check_payment_request->status == 0 && $callbackURL && $reference && $amount) {
                $make_new_status = '';
                if ($status == 'success') {
                    $make_new_status = 1;
                } elseif ($status == 'failure') {
                    $make_new_status = 4;
                } elseif ($status == 'cancel') {
                    $make_new_status = 3;
                }

                Log::info($make_new_status);

                if ($make_new_status != '') {
                    DB::beginTransaction();

                    PaymentRequest::where('reference', $reference)->update([
                        'payment_method_trx' => $get_paymentID,
                        'payment_method' => 'bkash',
                        'status' => $make_new_status,
                    ]);
                    $get_merchant_info = Merchant::find($merchant_id);

                    Transaction::create([
                        'user_id' => $merchant_id,
                        'amount' => $amount,
                        'charge' => 0,
                        'old_balance' => $get_merchant_info->balance,
                        'trx_type' => 'credit',
                        'trx' => $get_paymentID,
                        'details' => 'Bkash CashIn',
                        'user_type' => 'merchant',
                        'wallet_type' => 'bKash Api',
                        'created_at' => now(),
                    ]);

                    Merchant::find($merchant_id)->update([
                        'balance' => $get_merchant_info->balance + $amount,
                    ]);

                    DB::commit();
                }

                return $callbackURL;
            } else {
                return $callbackURL;
            }
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function cancelled_payment(Request $request)
    {
        // Fetch the payment request with related entities
        $paymentRequest = PaymentRequest::with(['merchant', 'agent', 'dso', 'partner'])
            ->where('request_id', $request['request_id'])
            ->first();

        // Log the payment request details

        $flag = 'reject';

        if ($paymentRequest->payment_method_trx != null && $paymentRequest->payment_method != null) {
            $flag = 'pending';
        } else {
            DB::table('payment_requests')
                ->where('request_id', $request['request_id'])
                ->update(['status' => 3]);
        }
        $responseArray = [
            //'success' => $flag == 'reject' ? 4 : 0, // Adjust the success code based on the flag
            'payment' => 'cancelled',
        ];
        $queryString = http_build_query($responseArray);

        // Check if callback_url is not null before appending query string
        $url = $paymentRequest->callback_url ? $paymentRequest->callback_url . '/?' . $queryString : null;
        // $url = $paymentRequest->callback_url ? $paymentRequest->callback_url . '/' . 'cancelled' : null;

        // Return the final response
        return response()->json([
            'success' => true,
            'message' => ['Payment successfully processed. You will be redirected to the merchant website soon.'],
            'url' => $url,
        ]);
    }

    public function checkTransaction(Request $request)
    {
        $request->validate([
            'trxid' => 'required|string',
        ]);

        // Convert the input trxid to lowercase
        $trxid = strtolower($request->trxid);

        // Perform a case-insensitive check in the database
        $exists = DB::table('payment_requests')
            ->whereRaw('LOWER(payment_method_trx) = ?', [$trxid])
            ->whereIn('status', [1, 2, 0])
            ->exists();

        // Return JSON response indicating whether the transaction ID exists
        return response()->json([
            'success' => $exists,
        ]);
    }

    public function checkTransactionBM(Request $request)
    {
        $request->validate([
            'trxid' => 'required|string',
        ]);

        $trxid = strtolower($request->trxid);

        $exists = DB::table('balance_managers')->where('trxid', $trxid)->exists();

        return response()->json([
            'success' => $exists,
        ]);
    }

    public function merchant_api_submit(Request $request)
    {
        //return $request->all();
        $check_mfs = PaymentMethod::find($request->method_id);
        $check_payments_method = MfsOperator::find($check_mfs->mobile_banking);

        $sim_id = $check_mfs->sim_id;
        $api_data = $check_mfs->password;
        $app_key = $check_mfs->app_key;
        $app_secret = $check_mfs->app_secret;

        $domain = $_SERVER['SERVER_NAME'];

        $mcallback = 'https://' . $domain . '/api/mcallback';

        $execute_url = 'https://' . $domain . '/api/excuteapi';

        $invoice = $request->reference;

        if ($check_payments_method->name == 'bkash') {
            $url = 'https://jopay.xyz/api/bkpay';

            $post_data = [
                'order_id' => $request->reference,
                'amount' => $request->amount,
                'password' => $api_data,
                'username' => $sim_id,
                'merchant_callback' => $mcallback,
                'execute_url' => $execute_url,
                'app_key' => $app_key,
                'app_secret' => $app_secret,
                'payeer' => '0',
            ];

            $getdata = sendPostData($url, $post_data);

            //return $getdata;

            $encodeurl = json_decode($getdata);

            $statusCode = $encodeurl->statusCode;

            if ($statusCode == '0000') {
                PaymentRequest::where('reference', $invoice)->update(['payment_method' => 'bkash', 'note' => 'bkash api merchant', 'ext_field_1' => $encodeurl->paymentID]);

                return redirect()->away($encodeurl->bkashURL);
            } else {
                Session::flash('alert', $encodeurl->message);

                return redirect()->back()->with('alert', $encodeurl->message);

                //return $encodeurl;
            }
        } elseif ($check_payments_method->name == 'nagad') {
            $url = 'https://jopay.xyz/api/ngpay';

            $post_data = [
                'order_id' => $request->reference,
                'amount' => $request->amount,
                'merchant_id' => $api_data,
                'merchant_number' => $sim_id,
                'merchant_callback' => $mcallback,
                'execute_url' => $execute_url,
                'public_key' => $app_key,
                'private_key' => $app_secret,
            ];

            $getdata = sendPostData($url, $post_data);

            $encodeurl = json_decode($getdata);

            // return $encodeurl;

            $statusCode = $encodeurl->status;

            if ($statusCode == 'Success') {
                PaymentRequest::where('reference', $invoice)->update(['payment_method' => 'nagad', 'note' => 'nagad api merchant', 'ext_field_1' => $encodeurl->paymentID]);

                return redirect()->away($encodeurl->callBackUrl);
            } else {
                Session::flash('alert', $encodeurl->message);

                return redirect()->back()->with('alert', $encodeurl->message);
            }
        }
    }

    public function merchantpaiback(Request $request)
    {
        $get_paymentID = $request->paymentID;
        $reference = $request->orderId;
        $status = $request->status;
        $signature = $request->signature;

        $check_payment_request = PaymentRequest::where('ext_field_1', $get_paymentID)->first();
        $merchant_id = $check_payment_request->merchant_id;
        $amount = $check_payment_request->amount;
        $payment_method = $check_payment_request->payment_method;
        $reference = $check_payment_request->reference;

        $make_new_status = '';
        if ($status == 'success') {
            $make_new_status = 1;
        } elseif ($status == 'failure') {
            $make_new_status = 3;
        } elseif ($status == 'cancel') {
            $make_new_status = 3;
        }

        PaymentRequest::where('ext_field_1', $get_paymentID)->update([
            'payment_method_trx' => $signature,
            'status' => $make_new_status,
        ]);

        if ($make_new_status == 1) {
            DB::beginTransaction();

            $get_merchant_info = Merchant::find($merchant_id);

            Transaction::create([
                'user_id' => $merchant_id,
                'amount' => $amount,
                'charge' => 0,
                'old_balance' => $get_merchant_info->balance,
                'trx_type' => 'credit',
                'trx' => $signature,
                'details' => $payment_method . ' api deposit',
                'user_type' => 'merchant',
                'wallet_type' => $payment_method . ' Api',
                'created_at' => now(),
            ]);

            Merchant::find($merchant_id)->update([
                'balance' => $get_merchant_info->balance + $amount,
            ]);

            DB::commit();
        }

        $callbackurl = $check_payment_request->callback_url . '?paymentID=' . $get_paymentID . '&payment_method=' . $payment_method . '&payment_method_trx=' . $signature . '&reference=' . $reference . '&signature=' . $signature . '&success=' . $make_new_status . '&status=' . $make_new_status;

        return redirect()->to($callbackurl);

        //  return redirect()->away( $check_payment_request->callback_url );
    }

    public function excuteapi(Request $request)
    {
        $get_paymentID = $request->paymentRefId;

        //$check_payment_request = PaymentRequest::where('ext_field_1', $get_paymentID)->first();

        PaymentRequest::where('ext_field_1', $get_paymentID)->update([
            'ext_field_1' => $request->all(),
        ]);

        $response = [
            'status' => '0000',
            'message' => 'excute success',
        ];
        return response()->json($response, 200);
    }

    public function customer_paiback(Request $request)
    {
        //return $request->all();

        $get_paymentID = $request->paymentID;
        $reference = $request->orderId;
        $status = $request->status;
        $signature = $request->signature;

        $check_payment_request = PaymentRequest::where('ext_field_1', $get_paymentID)->first();
        $customer_id = $check_payment_request->customer_id;
        $amount = $check_payment_request->amount;
        $payment_method = $check_payment_request->payment_method;

        $make_new_status = '';
        if ($status == 'success') {
            $make_new_status = 1;
        } elseif ($status == 'failure') {
            $make_new_status = 3;
        } elseif ($status == 'cancel') {
            $make_new_status = 3;
        }

        PaymentRequest::where('ext_field_1', $get_paymentID)->update([
            'payment_method_trx' => $signature,
            'status' => $make_new_status,
        ]);

        if ($make_new_status == 1) {
            DB::beginTransaction();

            $get_customer_info = Customer::find($customer_id);

            DB::table('wallet_transactions')->insert([
                'customer_id' => $customer_id,
                'credit' => $amount,
                'trxid' => $reference,
                'agent_sim' => '123',
                'old_balance' => $get_customer_info->balance,
                'payment_method' => $payment_method,
                'status' => 1, //0 pending, 1-success, 2-reject
                'ip' => '',
                'type' => 'deposit',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Customer::find($customer_id)->update([
                'balance' => $get_customer_info->balance + $amount,
            ]);

            DB::commit();
        }

        session()->flash('alert', 'Deposit has been ' . $status);
        Session::flash('type', 'warning');

        Session::flash('alert', 'Deposit has been ' . $status);

        return redirect()
            ->route('mc_dashboard')
            ->with('alert', 'Deposit has been ' . $status);
    }

    public function handle($version, $product, $paymentID, $status, $signature)
    {
        // Log the callback parameters
        \Log::info('Payment Callback Received', [
            'version' => $version,
            'product' => $product,
            'paymentID' => $paymentID,
            'status' => $status,
            'signature' => $signature,
        ]);

        // Example: Verify signature or update payment status here
        // ...

        return response()->json([
            'message' => 'Callback received successfully',
            'data' => compact('version', 'product', 'paymentID', 'status', 'signature'),
        ]);
    }
}
