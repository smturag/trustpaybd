<?php

namespace App\Http\Controllers\Api\IBot;

use App\Http\Controllers\Controller;
use App\Helpers\BalanceManagerConstant;
use App\Models\Modem;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\MerchantPvtPublicKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class iBotController extends Controller
{
    protected $merchantKey;
    protected $merchant;

    public function __construct(Request $request)
    {
        $excludedRoutes = ['mfsList'];
        $action = request()->route() ? request()->route()->getActionMethod() : null;

        if (!in_array($action, $excludedRoutes)) {
            $xAuthorization = $request->header('token');
            $this->merchantKey = MerchantPvtPublicKey::where('api_key', $xAuthorization)->first();

            if (!$this->merchantKey) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Invalid or missing token | অবৈধ বা অনুপস্থিত টোকেন',
                    ],
                    401,
                );
            }

            $this->merchant = Merchant::find($this->merchantKey->merchant_id);
        }
    }

    public function mfsList()
    {
        // Static list from your existing function
        $staticList = listOfIbotOp();

        // Fetch from external API
        $dynamicList = $this->fetchMfsApi();

        // Create a map from the static list for easy access
        $staticMap = collect($staticList)->keyBy(function ($item) {
            return strtolower($item['deposit_method']);
        });

        // Create a map from the dynamic list
        $dynamicMap = collect($dynamicList)->keyBy(function ($item) {
            return strtolower($item['type']);
        });

        // List of all unique methods (e.g., bkash, nagad, rocket)
        $allMethods = collect(array_merge($staticMap->keys()->toArray(), $dynamicMap->keys()->toArray()))->unique();

        // Combine them randomly
        $finalList = $allMethods
            ->map(function ($method) use ($staticMap, $dynamicMap) {
                $useStatic = rand(0, 1) === 1;

                if ($useStatic && $staticMap->has($method)) {
                    return $staticMap->get($method);
                } elseif ($dynamicMap->has($method)) {
                    return [
                        'deposit_method' => strtolower($dynamicMap[$method]['type']),
                        'deposit_number' => $dynamicMap[$method]['phone'],
                        'icon' => 'https://ibotbd.com/payments/' . strtolower($dynamicMap[$method]['type']) . '.png',
                    ];
                } elseif ($staticMap->has($method)) {
                    return $staticMap->get($method);
                }

                return null;
            })
            ->filter()
            ->values();

        return response()->json($finalList);
    }

    public function CreateDeposit(Request $request)
    {
        $checkApi = false;

        $rules = [
            'amount' => ['required', 'numeric'],
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
                        // API error handling (optional)
                    }

                    $existsLocally = DB::table('modems')
                        ->where('sim_number', $value)
                        ->whereIn('operating_status', [2, 3])
                        ->exists();

                    if ($existsLocally) {
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
        $merchant_type = $this->merchant->merchant_type === 'sub_merchant' ? 'sub_merchant' : 'general';

        if ($request->transaction_id == null) {
            $findReference = PaymentRequest::where('reference', $request->reference)->where('status', 4)->first();
            if ($findReference) {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Failed to submit data',
                    ],
                    500,
                );
            }

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
            ];
            
            // Capture merchant balances
            $mainMerchantId = $merchant_type === 'sub_merchant' ? $this->merchant->create_by : $this->merchant->id;
            $mainMerchant = Merchant::find($mainMerchantId);
            if ($mainMerchant) {
                $data['merchant_last_balance'] = $mainMerchant->balance;
                $data['merchant_new_balance'] = $mainMerchant->balance;
            }
            
            if ($merchant_type === 'sub_merchant') {
                $subMerchant = Merchant::find($this->merchant->id);
                if ($subMerchant) {
                    $data['sub_merchant_last_balance'] = $subMerchant->balance;
                    $data['sub_merchant_new_balance'] = $subMerchant->balance;
                }
            }

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

            if ($check) {
                return response()->json(
                    [
                        'status' => true,
                        'message' => 'Data submitted successfully',
                        'data' => $check,
                    ],
                    200,
                );
            } else {
                return response()->json(
                    [
                        'status' => false,
                        'message' => 'Failed to submit data',
                    ],
                    500,
                );
            }
        }

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

        $checkTransactionResponse = checkTransaction( $request->transaction_id);

        if (isset($checkTransactionResponse['status'], $checkTransactionResponse['data']) && $checkTransactionResponse['status'] === 'success' && isset($checkTransactionResponse['data']['amount'], $checkTransactionResponse['data']['method']) && (int) $checkTransactionResponse['data']['amount'] === (int) $request->amount && strtolower($checkTransactionResponse['data']['method']) === strtolower($request->payment_method)) {
            $data['status'] = '1';
            $data['senderPhone'] = $checkTransactionResponse['data']['senderPhone'] ?? null; // Safe access
        } else {
            // Fallback to DB check if transaction API fails or doesn't match amount
            $modifyMethod = '';

            if ($request->payment_method == 'bkash') {
                $modifyMethod = 'bkcashout';
            } elseif ($request->payment_method == 'nagad') {
                $modifyMethod = 'ngcashout';
            } elseif ($request->payment_method == 'rocket') {
                $modifyMethod = 'rccashout';
            } elseif ($request->payment_method == 'upay') {
                $modifyMethod = 'upcashout';
            }

            $balanceManager = DB::table('balance_managers')->where('sim', $request->deposit_number)->where('trxid', $request->transaction_id)->where('amount', $request->amount)->where('type', $modifyMethod)->first();

            $data['status'] = $balanceManager && (in_array($balanceManager->status, [BalanceManagerConstant::SUCCESS, BalanceManagerConstant::APPROVED, BalanceManagerConstant::DANGER, BalanceManagerConstant::WAITING]) || is_null($balanceManager->status)) ? 1 : 0;
        }

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

        // Track balance before transaction
        $mainMerchantId = $merchant_type === 'sub_merchant' ? $this->merchant->create_by : $this->merchant->id;
        $mainMerchant = Merchant::find($mainMerchantId);
        if ($mainMerchant) {
            $data['merchant_last_balance'] = $mainMerchant->balance;
            $data['merchant_new_balance'] = $mainMerchant->balance; // Will be updated on approval
        }

        if ($merchant_type === 'sub_merchant') {
            $subMerchant = Merchant::find($this->merchant->id);
            if ($subMerchant) {
                $data['sub_merchant_last_balance'] = $subMerchant->balance;
                $data['sub_merchant_new_balance'] = $subMerchant->balance; // Will be updated on approval
            }
        }

        $check = PaymentRequest::create($data);
        $webhook_url = url("/ibot/deposit/status-check/{$request->reference}");
        $baseCallbackUrl = rtrim($request->callback_url, '/');
        $callbackWithStatus = $baseCallbackUrl . '?status=' . ($data['status'] == 1 ? 'success' : 'pending') . '&reference=' . urlencode($request->reference) . '&transaction_id=' . urlencode($request->transaction_id) . '&payment_method=' . urlencode($request->payment_method);

        merchantWebHook($request->reference);

        return $check
            ? response()->json(
                [
                    'status' => $data['status'] == 1 ? 'success' : 'pending',
                    'message' => $data['status'] == 1 ? 'Deposit is successful | জমা সফল হয়েছে' : 'Deposit is processing, check status using track or webhook_url | জমা প্রক্রিয়াধীন, অনুগ্রহপূর্বক অপেক্ষা করুন',
                    'callback' => $callbackWithStatus,
                    'webhook_url' => $webhook_url,
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

    public function checkPaymentStatus($referenceId)
    {
        if (empty($referenceId)) {
            return response()->json(
                [
                    'status' => 'true',
                    'message' => 'Reference ID data not found | রেফারেন্স আইডির তথ্য পাওয়া যায়নি',
                ],
                404,
            );
        }

        $data = PaymentRequest::select('request_id', 'amount', 'payment_method', 'reference', 'cust_name', 'cust_phone', 'note', 'reject_msg', 'payment_method_trx', 'status')
            ->selectRaw(
                "
                CASE
                    WHEN status = 0 THEN 'pending'
                    WHEN status IN (1, 2) THEN 'completed'
                    WHEN status = 3 THEN 'rejected'
                    ELSE 'unknown'
                END as status_name
            ",
            )
            ->where('reference', $referenceId)
            ->first();

        return $data
            ? response()->json(
                [
                    'status' => 'true',
                    'data' => $data,
                ],
                200,
            )
            : response()->json(
                [
                    'status' => 'false',
                    'message' => 'Data not found | তথ্য পাওয়া যায়নি',
                ],
                404,
            );
    }

    public function createWithdraw(Request $request)
    {

        return redirect()->back();
        $messages = [
            'amount.required' => 'Amount is required | পরিমাণ প্রয়োজন',
            'amount.numeric' => 'Amount must be numeric | পরিমাণ অবশ্যই সংখ্যা হতে হবে',

            'mfs_operator.required' => 'Operator is required | অপারেটর প্রয়োজন',
            'mfs_operator.string' => 'Operator must be a string | অপারেটর অবশ্যই স্ট্রিং হতে হবে',
            'mfs_operator.exists' => 'Operator does not exist | অপারেটর বিদ্যমান নয়',

            'withdraw_number.required' => 'Withdraw number is required | উত্তোলনের নম্বর প্রয়োজন',
        ];

        $validator = Validator::make(
            $request->all(),
            [
                'amount' => 'required|numeric',
                'mfs_operator' => 'required|string|exists:mfs_operators,name',
                'withdraw_number' => 'required',
                'withdraw_id' => 'sometimes|string|unique:service_requests,trxid',
                'webhook_url' => 'sometimes|url',
            ],
            $messages,
        );

        if ($validator->fails()) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Validation failed | ভ্যালিডেশন ব্যর্থ হয়েছে',
                    'errors' => $validator->errors(),
                ],
                400,
            );
        }

        $merchantId = $this->merchantKey->merchant_id;
        $merchant = $this->merchant;
        $merchant_type = $merchant->merchant_type === 'sub_merchant' ? 'sub_merchant' : 'general';
        $adminMerchantId = $merchant_type === 'sub_merchant' ? $merchant->create_by : null;
        $actualMerchantId = $adminMerchantId ?? $merchantId;

        $balance = $merchant_type === 'sub_merchant' ? $merchant->balance : $merchant->available_balance;

        if ($request->amount > $balance) {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Amount is greater than Merchant Balance | পরিমাণ মার্চেন্ট ব্যালেন্সের থেকে বেশি',
                ],
                400,
            );
        }

        $recentRequest = ServiceRequest::where('number', $request->withdraw_number)
            ->where('amount', $request->amount)
            ->where('created_at', '>=', Carbon::now()->subMinutes(10))
            ->first();

        //    if ($recentRequest) {
        //         return response()->json([
        //             'status' => false,
        //             'message' => 'Duplicate request in last 10 minutes | গত ১০ মিনিটে পুনরাবৃত্তি অনুরোধ পাওয়া গেছে'
        //         ], 400);
        //     }

        $agentId = getRandom($request->amount, $request->mfs_operator);
        $invoiceNumber = $request->withdraw_id ?? 'TRX-' . $actualMerchantId . '-' . now()->format('YmdHis') . '-' . rand(1000, 9999);
        $webhook_url = url("ibot/withdraw/status-check/{$invoiceNumber}");

        DB::beginTransaction();
        try {
            $mfs = DB::table('mfs_operators')->where('name', $request->mfs_operator)->first();




            $service = new ServiceRequest();
            $service->trxid = $invoiceNumber;
            $service->merchant_id = $actualMerchantId;
            $service->sub_merchant = $merchant_type === 'sub_merchant' ? $merchantId : null;
            $service->mfs = $request->mfs_operator;
            $service->mfs_id = $mfs->id;
            $service->old_balance = $balance;
            $service->amount = $request->amount;
            $service->new_balance = $balance - $request->amount;
            $service->number = $request->withdraw_number;
            $service->type = 'personal';
            $service->status = $agentId ? 1 : 0;
            $service->agent_id = $agentId;
            $service->sim_balance = 0;
            $service->webhook_url = $request->webhook_url;
            $service->save();

            // if ($merchant_type === 'sub_merchant') {
            //     merchantBalanceAction($merchantId, 'minus', $request->amount, false);
            //     merchantBalanceAction($actualMerchantId, 'minus', $request->amount, false);
            // } else {
            //     merchantBalanceAction($actualMerchantId, 'minus', $request->amount, true);
            // }

            // agentBalanceAction($agentId, 'minus', $request->amount);

            DB::commit();

            return response()->json(
                [
                    'status' => true,
                    'message' => 'Withdraw request placed successfully & for confirmation please check webhook_url or check status route | উত্তোলনের অনুরোধ সফলভাবে জমা হয়েছে এবং নিশ্চিতকরণের জন্য webhook_url বা স্ট্যাটাস রুট পরীক্ষা করুন',
                    'trnx_id' => $invoiceNumber,
                    'webhook_url' => $webhook_url,
                ],
                200,
            );
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function checking_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'trnx_id' => 'required',
        ]);

        $get_data = ServiceRequest::select('number', 'mfs', 'old_balance', 'amount', 'new_balance', 'msg', 'status', 'get_trxid')->where('trxid', $request->trnx_id)->first();

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
                'withdraw_number' => $get_data->number,
                'mfs_operator' => $get_data->mfs,
                'amount' => $get_data->amount,
                'msg' => $get_data->get_trxid,
                'status' => $make_status,
            ];

            return response()->json(
                [
                    'status' => 'true',
                    'data' => $data,
                ],
                200,
            );
        }

        return response()->json(
            [
                'status' => 'false',
                'message' => 'This TRXID not available | এই TRXID উপলব্ধ নেই',

                // 'data' => $data
            ],
            400,
        );
    }

    private function fetchMfsApi()
    {
        try {
            $response = Http::withToken(BalanceManagerConstant::token_key)->get(BalanceManagerConstant::URL . '/api/available-methods');

            if ($response->successful()) {
                $data = $response->json();

                $grouped = collect($data)->groupBy('type');
                $result = [];

                foreach ($grouped as $type => $items) {
                    $previousPhone = Cache::get("last_used_phone_{$type}");

                    // If there's more than 1 item, avoid reusing the same phone
                    if ($items->count() > 1 && $previousPhone) {
                        $filtered = $items->filter(function ($item) use ($previousPhone) {
                            return $item['phone'] !== $previousPhone;
                        });

                        // If all items were filtered out, fall back to original list
                        $finalItems = $filtered->isNotEmpty() ? $filtered : $items;
                    } else {
                        $finalItems = $items;
                    }

                    // Pick one random item
                    $selected = $finalItems->random();

                    // Cache the current phone number
                    Cache::put("last_used_phone_{$type}", $selected['phone'], now()->addMinutes(10));

                    $result[] = $selected;
                }

                return $result;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch MFS API: ' . $e->getMessage());
        }

        return [];
    }

    private function checkTransaction($receiverPhone, $trxId)
    {
        $url = BalanceManagerConstant::URL . "/api/transaction/{$receiverPhone}/{$trxId}";

        $token = BalanceManagerConstant::token_key;

        try {
            $response = Http::withToken($token)->get($url);

            if ($response->successful()) {
                $json = $response->json();

                if ($json['success'] === true && isset($json['data'])) {
                    return [
                        'status' => 'success',
                        'message' => 'Transaction verified successfully',
                        'data' => $json['data'],
                    ];
                } else {
                    return [
                        'status' => 'error',
                        'message' => $json['message'] ?? 'Transaction not found',
                    ];
                }
            } else {
                return [
                    'status' => 'error',
                    'message' => 'API request failed',
                    'details' => $response->body(),
                    'code' => $response->status(),
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Exception during API request',
                'error' => $e->getMessage(),
            ];
        }
    }

    public function trackPaymentStatus($transactionId)
    {
        // if (empty($transactionId)) {
        //         return response()->json([
        //             'status' => 'true',
        //             'message' => 'Transaction ID data not found | ট্রান্সেকশন আইডির তথ্য পাওয়া যায়নি',
        //         ], 404);
        //     }

        $data = PaymentRequest::select('request_id', 'amount', 'payment_method', 'reference', 'cust_name', 'cust_phone', 'note', 'reject_msg', 'payment_method_trx', 'status')
            ->selectRaw(
                "
                        CASE
                            WHEN status = 0 THEN 'pending'
                            WHEN status IN (1, 2) THEN 'completed'
                            WHEN status = 3 THEN 'rejected'
                            ELSE 'unknown'
                        END as status_name
                    ",
            )
            ->where('payment_method_trx', $transactionId)
            ->first();

        return $data
            ? response()->json(
                [
                    'status' => 'true',
                    'data' => $data,
                ],
                200,
            )
            : response()->json(
                [
                    'status' => 'false',
                    'message' => 'Data not found | তথ্য পাওয়া যায়নি',
                ],
                404,
            );
    }
}
