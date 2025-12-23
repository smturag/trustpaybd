<?php

namespace App\Http\Controllers\Api\V2;

use App\Helpers\BalanceManagerConstant;
use App\Http\Controllers\Controller;
use App\Http\Requests\MakeTransactionRequest;
use App\Models\Merchant;
use App\Models\MerchantPvtPublicKey;
use App\Models\Modem;
use App\Models\PaymentRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Service\Backend\BkashService;
use Illuminate\Support\Facades\Log;

class MerchantPRController extends Controller
{
    protected $merchantKey;
    protected $merchant;
    protected $bkashService;

    public function __construct(Request $request)
    {
        $excludedRoutes = ['mfsList'];
        $action = request()->route() ? request()->route()->getActionMethod() : null;

        $this->bkashService = new BkashService();

        if (!in_array($action, $excludedRoutes)) {
            $xAuthorization = $request->header('X-Authorization');
            $xAuthorizationSecret = $request->header('X-Authorization-Secret');

            $this->merchantKey = MerchantPvtPublicKey::where('api_key', $xAuthorization)->where('secret_key', $xAuthorizationSecret)->first();

            if (!$this->merchantKey) {
                abort(
                    response()->json(
                        [
                            'status' => false,
                            'message' => 'Invalid or missing token | অবৈধ বা অনুপস্থিত টোকেন',
                        ],
                        401,
                    ),
                );
            }

            $this->merchant = Merchant::find($this->merchantKey->merchant_id);
        }
    }

    public function check()
    {
        return Auth::user();
    }

    public function mfsList()
    {
        $dynamicList = $this->bkashService->getActiveBkashMethod(); // Collection
        $staticList = collect(mfsList('manual')); // convert static array to Collection

        $mergedList = $dynamicList->merge($staticList)->values(); // merge and reindex

        return response()->json($mergedList);
    }

    public function makeTransaction(Request $request)
    {
        $checkApi = false;

        $rules = [
            'amount' => ['required', 'numeric'],
            'type' => 'required|in:P2A,P2C',
            'reference' => ['required', 'string', 'min:3', 'max:20', Rule::unique('payment_requests', 'reference')->where(fn($query) => $query->where('merchant_id', $this->merchant->id)->where('status', '!=', 4))],
            'currency' => ['required', Rule::in(['BDT'])],
            'callback_url' => ['required', 'url'],
            'cust_name' => ['nullable', 'min:3', 'max:50'],
            'cust_phone' => ['nullable', 'min:3', 'max:15'],
            'cust_address' => ['nullable', 'min:3', 'max:100'],
            'checkout_items' => ['sometimes', 'array'],
            'note' => ['sometimes', 'string'],
            'transaction_id' => [
                'sometimes',
                'string',
                'min:8',
                'max:10',
                'regex:/^[a-zA-Z0-9]+$/',
                function ($attribute, $value, $fail) {
                    $exists = PaymentRequest::where('payment_method_trx', $value)
                        ->whereIn('status', [0, 1, 2])
                        ->exists();

                    if ($exists) {
                        $fail('The transaction ID is already used & it is under working. | ট্রানজ্যাকশন আইডিটি ইতিমধ্যে ব্যবহৃত হয়েছে অথবা এখনও প্রক্রিয়াধীন।');
                    }
                },
            ],
            'from_number' => ['nullable'],
            'payment_method' => ['required', Rule::in(['bkash', 'nagad', 'rocket', 'upay'])],
            'deposit_number' => [
                'sometimes',
                function ($attribute, $value, $fail) use (&$checkApi) {
                    try {
                        $response = Http::withToken(BalanceManagerConstant::token_key)->get(BalanceManagerConstant::URL . '/api/available-methods');

                        if ($response->successful()) {
                            $methods = $response->json();

                            foreach ($methods as $method) {
                                if ($method['phone'] === $value && isset($method['activeStatus']) && $method['activeStatus'] === true) {
                                    $checkApi = true;
                                    return;
                                }
                            }
                        }
                    } catch (\Exception $e) {
                        // API error handling
                    }

                    $existsLocally = DB::table('modems')
                        ->where('sim_number', $value)
                        ->whereIn('operating_status', [2, 3])
                        ->exists();

                    if ($existsLocally) {
                        $checkApi = false;
                        return;
                    }

                    $existP2C = DB::table('payment_methods')->where('sim_id', $value)->where('status', 1)->first();

                    if ($existP2C) {
                        $checkApi = false;
                        return;
                    }

                    $fail('The deposit number is not allowed. | এই জমা নম্বরটি অনুমোদিত নয়।');
                },
            ],
        ];

        $messages = [
            'amount.required' => 'Amount is required | পরিমাণ প্রয়োজন',
            'amount.numeric' => 'Amount must be a number | পরিমাণ একটি সংখ্যা হতে হবে',

            'reference.required' => 'Reference is required | রেফারেন্স প্রয়োজন',
            'reference.string' => 'Reference must be a string | রেফারেন্স স্ট্রিং হতে হবে',
            'reference.min' => 'Reference must be at least ৩ characters | রেফারেন্স কমপক্ষে ৩ অক্ষরের হতে হবে',
            'reference.max' => 'Reference must not exceed ২০ characters | রেফারেন্স সর্বোচ্চ ২০ অক্ষরের হতে পারে',
            'reference.unique' => 'Reference already exists | রেফারেন্স ইতিমধ্যে বিদ্যমান',

            'currency.required' => 'Currency is required | মুদ্রা প্রয়োজন',
            'currency.in' => 'Currency must be BDT | মুদ্রা অবশ্যই BDT হতে হবে',

            'callback_url.required' => 'Callback URL is required | কলব্যাক ইউআরএল প্রয়োজন',
            'callback_url.url' => 'Callback URL must be valid | সঠিক কলব্যাক ইউআরএল দিন',

            'cust_name.min' => 'Customer name must be at least ৩ characters | নাম কমপক্ষে ৩ অক্ষরের হতে হবে',
            'cust_name.max' => 'Customer name must not exceed ৫০ characters | নাম সর্বোচ্চ ৫০ অক্ষরের হতে পারে',

            'cust_phone.min' => 'Phone number too short | ফোন নম্বর খুব ছোট',
            'cust_phone.max' => 'Phone number too long | ফোন নম্বর অনেক বড়',

            'cust_address.min' => 'Address too short | ঠিকানা খুব ছোট',
            'cust_address.max' => 'Address too long | ঠিকানা অনেক বড়',

            'transaction_id.required' => 'Transaction ID is required | ট্রানজ্যাকশন আইডি প্রয়োজন',
            'transaction_id.min' => 'Transaction ID must be at least ৮ characters | ট্রানজ্যাকশন আইডি কমপক্ষে ৮ অক্ষরের হতে হবে',
            'transaction_id.max' => 'Transaction ID must not exceed ১০ characters | ট্রানজ্যাকশন আইডি সর্বোচ্চ ১০ অক্ষরের হতে পারে',
            'transaction_id.regex' => 'Transaction ID must be alphanumeric | ট্রানজ্যাকশন আইডি অবশ্যই অক্ষর এবং সংখ্যা মিশ্রিত হতে হবে',

            'payment_method.required' => 'Payment method is required | পেমেন্ট পদ্ধতি প্রয়োজন',
            'payment_method.in' => 'Invalid payment method | অবৈধ পেমেন্ট পদ্ধতি',

            'deposit_number.required' => 'Deposit number is required | জমা নম্বর প্রয়োজন',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'errors' => $validator->errors(),
                ],
                422,
            );
        }

        $data['senderPhone'] = '';
        $agent = Modem::where('sim_number', $request->deposit_number)->first();
        $currentAgent = User::where('member_code',$agent->member_code)->where('user_type','agent')->first();
        $merchant_type = $this->merchant->merchant_type === 'sub_merchant' ? 'sub_merchant' : 'general';

        /**
         * CASE 1: Transaction ID is not provided
         */
        if ($request->transaction_id == null) {
            $findReference = PaymentRequest::where('reference', $request->reference)->where('status', 4)->first();

            if ($findReference) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Reference already exists',
                    ],
                    500,
                );
            }

            /*
            calculate merchant and sub merchant  rate
            */

            $merchantRate = calculateAmountFromRate($request->payment_method, $request->type, 'deposit', $this->merchant->id, $request->amount);

            

            $memberRate = calculateAmountFromRateForMember($request->payment_method, $request->type, 'deposit', $currentAgent->id, $request->amount);

            /*
                BEFORE INSERT DATA INTO DATABASE DATA PREPARATION

            */

            $data += [
                'agent' => $agent->member_code,
                'partner' => getPartnerFromAgent($agent->member_code)->member_code ?? null,
                'from_number' => $request->from_number,
                'modem_id' => $agent->id,
                'request_id' => generatePaymentRequestTrx(25),
                'payment_method_trx' => null,
                'sim_id' => $request->deposit_number,
                'trxid' => generatePaymentRequestTrx(6),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'merchant_id' => $merchant_type === 'sub_merchant' ? $this->merchant->create_by : $this->merchant->id,
                'sub_merchant' => $merchant_type === 'sub_merchant' ? $this->merchant->id : null,
                'reference' => $request->reference,
                'currency' => $request->currency,
                'callback_url' => rtrim($request->callback_url, '/') . '/',
                'cust_name' => $request->cust_name,
                'cust_phone' => $request->cust_phone,
                'cust_address' => $request->cust_address,
                'issue_time' => Carbon::now(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 4,
                'payment_type' => $request->type,
                'merchant_fee' => $merchantRate['general']['fee_amount'],
                'merchant_commission' => $merchantRate['general']['commission_amount'],
                'sub_merchant_fee' => $merchantRate['sub_merchant']['fee_amount'],
                'sub_merchant_commission' => $merchantRate['sub_merchant']['commission_amount'],
                'merchant_main_amount' => $merchantRate['general']['net_amount'],
                'sub_merchant_main_amount' => $merchantRate['sub_merchant']['net_amount'],
                'partner_fee' => $memberRate['member']['fee_amount'],
                'partner_commission' => $memberRate['member']['commission_amount'],
                'user_fee' => $memberRate['agent']['fee_amount'],
                'user_commission' => $memberRate['agent']['commission_amount'],
                'partner_main_amount' => $memberRate['member']['net_amount'],
                'user_main_amount' => $memberRate['agent']['net_amount'],
            ];

            if ($request->has('checkout_items')) {
                $data['checkout_items'] = json_encode($request->checkout_items);
            }
            if ($request->has('ext_field_1')) {
                $data['ext_field_1'] = json_encode($request->ext_field_1);
            }
            if ($request->has('ext_field_2')) {
                $data['ext_field_2'] = json_encode($request->ext_field_2);
            }

            $check = PaymentRequest::create($data);

            /*
                DATA INSERTED DONE

            */

            /*
                END OF INSERTING BALANCE

            */

            $getBkashApiData = ' ';

            /*
               WORKING OF BKASH LIVE API MERCHANT

            */

            if ($request->type == 'P2C' && $request->payment_method == 'bkash') {
                $getBkashApiData = $this->bkashService->createPayment($check);

                $getPayementData = PaymentRequest::select('sim_id', 'amount', 'payment_method', 'reference', 'currency', 'callback_url', 'cust_name', 'issue_time')->find($check->id);

                if ($getBkashApiData['success']) {
                    return response()->json(
                        [
                            'status' => true,
                            'message' => 'Data submitted successfully ',
                            'data' => $getPayementData,
                            'type' => 'P2C',
                            'URL' => $getBkashApiData['data'],
                        ],
                        200,
                    );
                }
            }

            /*
                END OF BKASH LIVE API MERCHANT
            */

            if ($check) {
                $getPayementData = PaymentRequest::select('sim_id', 'amount', 'payment_method', 'reference', 'currency', 'callback_url', 'cust_name', 'issue_time')->find($check->id);

                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Data submitted successfully',
                        'data' => $getPayementData,
                        'type' => 'P2A',
                    ],
                    200,
                );
            }

            return response()->json(
                [
                    'status' => false,
                    'message' => 'Failed to submit data',
                ],
                500,
            );
        }

        /**
         * CASE 2: With Transaction ID
         */
        if ($checkApi == false && !$agent) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Failed to find agent information. Contact admin. | এজেন্টের তথ্য পাওয়া যায়নি। অ্যাডমিনের সাথে যোগাযোগ করুন।',
                ],
                400,
            );
        }

        $findReference = PaymentRequest::where('reference', $request->reference)->where('status', 4)->first();

        if ($findReference) {
            $findReference->delete();
        }

        $checkTransactionResponse = checkTransaction($request->transaction_id);

        /**
         * WORKING WITH MFS API
         */

        if (isset($checkTransactionResponse['status'], $checkTransactionResponse['data']) && $checkTransactionResponse['status'] === 'success' && isset($checkTransactionResponse['data']['amount'], $checkTransactionResponse['data']['method']) && (int) $checkTransactionResponse['data']['amount'] === (int) $request->amount && strtolower($checkTransactionResponse['data']['method']) === strtolower($request->payment_method)) {
            $data['status'] = '1';
            $data['senderPhone'] = $checkTransactionResponse['data']['senderPhone'] ?? null;
        } else {
            /**
             * WORKING WITH BALANCE MANEGER
             */

            $modifyMethod = match ($request->payment_method) {
                'bkash' => 'bkcashout',
                'nagad' => 'ngcashout',
                'rocket' => 'rccashout',
                'upay' => 'upcashout',
                default => '',
            };

            $balanceManager = DB::table('balance_managers')->where('sim', $request->deposit_number)->where('trxid', $request->transaction_id)->where('amount', $request->amount)->where('type', $modifyMethod)->first();

            $data['status'] = $balanceManager && (in_array($balanceManager->status, [BalanceManagerConstant::SUCCESS, BalanceManagerConstant::APPROVED, BalanceManagerConstant::DANGER, BalanceManagerConstant::WAITING]) || is_null($balanceManager->status)) ? 1 : 0;
        }

        /**
         * CALCULATE MERCHANT AND SUB MERCHANT RATE
         */

        $merchantRate = calculateAmountFromRate($request->payment_method, $request->type, 'deposit', $this->merchant->id, $request->amount);

        $memberRate = calculateAmountFromRateForMember($request->payment_method, $request->type, 'deposit', $currentAgent->id, $request->amount);

        /**
         * DATA PREPARETION FOR INSERT INTO TABLE
         */

        $data += [
            'agent' => $agent->member_code,
            'partner' => getPartnerFromAgent($agent->member_code)->member_code ?? null,
            'from_number' => $request->from_number ?? $data['senderPhone'],
            'modem_id' => $agent->id,
            'request_id' => generatePaymentRequestTrx(25),
            'payment_method_trx' => $request->transaction_id,
            'sim_id' => $request->deposit_number,
            'trxid' => generatePaymentRequestTrx(6),
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'merchant_id' => $merchant_type === 'sub_merchant' ? $this->merchant->create_by : $this->merchant->id,
            'sub_merchant' => $merchant_type === 'sub_merchant' ? $this->merchant->id : null,
            'reference' => $request->reference,
            'currency' => $request->currency,
            'callback_url' => rtrim($request->callback_url, '/') . '/',
            'cust_name' => $request->cust_name,
            'cust_phone' => $request->cust_phone,
            'cust_address' => $request->cust_address,
            'issue_time' => Carbon::now(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'accepted_by' => $checkApi == true ? 'API' : '',
            'merchant_fee' => $merchantRate['general']['fee_amount'],
            'merchant_commission' => $merchantRate['general']['commission_amount'],
            'sub_merchant_fee' => $merchantRate['sub_merchant']['fee_amount'],
            'sub_merchant_commission' => $merchantRate['sub_merchant']['commission_amount'],
            'merchant_main_amount' => $merchantRate['general']['net_amount'],
            'sub_merchant_main_amount' => $merchantRate['sub_merchant']['net_amount'],
            'partner_fee' => $memberRate['member']['fee_amount'],
            'partner_commission' => $memberRate['member']['commission_amount'],
            'user_fee' => $memberRate['agent']['fee_amount'],
            'user_commission' => $memberRate['agent']['commission_amount'],
            'partner_main_amount' => $memberRate['member']['net_amount'],
            'user_main_amount' => $memberRate['agent']['net_amount'],
        ];

        if ($request->has('checkout_items')) {
            $data['checkout_items'] = json_encode($request->checkout_items);
        }
        if ($request->has('ext_field_1')) {
            $data['ext_field_1'] = json_encode($request->ext_field_1);
        }
        if ($request->has('ext_field_2')) {
            $data['ext_field_2'] = json_encode($request->ext_field_2);
        }

        $check = PaymentRequest::create($data);

        /**
         * END OF INSERT DATA INTO TABLE
         */

        /**
         * MERCHANT AND SUB MERCHANT BALANCE HELPER FUNCTION
         */

        // if ($merchant_type === 'sub_merchant') {
        //     merchantBalanceAction($this->merchant->id, 'plus', $merchantRate['sub_merchant']['net_amount'], false);
        //     merchantBalanceAction($this->merchant->create_by, 'plus', $merchantRate['general']['net_amount'], false);
        // } else {
        //     merchantBalanceAction($this->merchant->id, 'plus', $merchantRate['general']['net_amount'], true);
        // }

        /**
         * END OF ADDING BALANCE INTO
         */

        /**
         * THIS IS WEBHOOK. INSTANT HIT ON CLIENT CALLBACK URL
         */

        merchantWebHook($request->reference);

        /**
         * END OF WEBHOOK
         */

        $webhook_url = url("/ibot/deposit/status-check/{$request->reference}");
        $baseCallbackUrl = rtrim($request->callback_url, '/');
        $callbackWithStatus = $baseCallbackUrl . '?status=' . ($data['status'] == 1 ? 'success' : 'pending') . '&reference=' . urlencode($request->reference) . '&transaction_id=' . urlencode($request->transaction_id) . '&payment_method=' . urlencode($request->payment_method);

        return $check
            ? response()->json(
                [
                    'status' => $data['status'] == 1 ? 'success' : 'pending',
                    'message' => $data['status'] == 1 ? 'Deposit is successful | জমা সফল হয়েছে' : 'Deposit is processing, check status using track or webhook_url | জমা প্রক্রিয়াধীন, অনুগ্রহপূর্বক অপেক্ষা করুন',
                    'callback' => $callbackWithStatus,
                    'type' => 'P2A',
                ],
                200,
            )
            : response()->json(
                [
                    'status' => 'error',
                    'message' => 'Please contact admin | অনুগ্রহ করে অ্যাডমিনের সাথে যোগাযোগ করুন',
                ],
                520,
            );
    }
}
