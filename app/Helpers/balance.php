<?php

use App\Models\Customer;
use App\Models\Merchant;
use App\Models\MfsOperator;
use App\Models\OperatorFeeCommission;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\UserCharge;
use Illuminate\Support\Facades\Log;

function allBalanceZero()
{
    backfillRatesForBalanceReset();

    $merchantList = Merchant::all();

    foreach ($merchantList as $merchant) {
        $merchant->balance = 0;
        $merchant->save();
        $getBalance = $merchant->merchant_type == 'general' ? getMerchantBalance($merchant->id) : subMerchantBalance($merchant->id);
        $merchant->balance = $getBalance['balance'];
        $merchant->available_balance = $merchant->merchant_type == 'general' ? $getBalance['availableBalance'] : null;
        $merchant->save();
    }

    $userList = User::all();

    foreach ($userList as $user) {
        if ($user->user_type == 'agent') {
            $user->balance = 0;
            $user->save();
            $getBalance = findAgentBalance($user->id);
            $user->balance = $getBalance['mainBalance'];
            $user->save();
        } elseif ($user->user_type == 'partner') {
            $user->balance = 0;
            $user->available_balance = 0;
            $user->save();
            $getBalance = partnerBalance($user->id);
            $user->balance = $getBalance['mainBalance'];
            $user->save();
        }
    }

    // $customers = Customer::all();

    // foreach ($customers as $customer) {
    //     $customer->balance = 0;
    //     $customer->save();
    // }
}

function backfillRatesForBalanceReset()
{
    PaymentRequest::whereNotNull('merchant_id')
        ->where('amount', '>', 0)
        ->orderBy('id')
        ->chunkById(200, function ($requests) {
            foreach ($requests as $request) {
                $amount = (float) $request->amount;
                if ($amount <= 0 || empty($request->payment_method)) {
                    continue;
                }

                $updates = [];
                $paymentType = $request->payment_type ?: 'P2A';
                $rateMerchantId = $request->sub_merchant ?: $request->merchant_id;
                if (!$rateMerchantId) {
                    continue;
                }

                $mfsOperatorId = MfsOperator::where('name', $request->payment_method)
                    ->where('type', $paymentType)
                    ->value('id');

                $merchantRate = calculateAmountFromRate(
                    $request->payment_method,
                    $paymentType,
                    'deposit',
                    $rateMerchantId,
                    $amount,
                    $mfsOperatorId
                );

                if (empty($merchantRate['error'])) {
                    $updates['merchant_fee'] = $merchantRate['general']['fee_amount'];
                    $updates['merchant_commission'] = $merchantRate['general']['commission_amount'];
                    $updates['merchant_main_amount'] = $merchantRate['general']['net_amount'];
                    $updates['sub_merchant_fee'] = $merchantRate['sub_merchant']['fee_amount'];
                    $updates['sub_merchant_commission'] = $merchantRate['sub_merchant']['commission_amount'];
                    $updates['sub_merchant_main_amount'] = $merchantRate['sub_merchant']['net_amount'];
                }

                $agent = $request->agent ? User::where('member_code', $request->agent)->first() : null;
                if ($agent) {
                    $memberRate = calculateAmountFromRateForMember(
                        $request->payment_method,
                        $paymentType,
                        'deposit',
                        $agent->id,
                        $amount,
                        $mfsOperatorId
                    );

                    if (empty($memberRate['error'])) {
                        $updates['partner_fee'] = $memberRate['member']['fee_amount'];
                        $updates['partner_commission'] = $memberRate['member']['commission_amount'];
                        $updates['partner_main_amount'] = $memberRate['member']['net_amount'];
                        $updates['user_fee'] = $memberRate['agent']['fee_amount'];
                        $updates['user_commission'] = $memberRate['agent']['commission_amount'];
                        $updates['user_main_amount'] = $memberRate['agent']['net_amount'];
                    }
                }

                if (!empty($updates)) {
                    $request->fill($updates);
                    $request->save();
                }
            }
        });

    ServiceRequest::whereNotNull('merchant_id')
        ->where('amount', '>', 0)
        ->orderBy('id')
        ->chunkById(200, function ($requests) {
            foreach ($requests as $servicedata) {
                $amount = (float) $servicedata->amount;
                if ($amount <= 0) {
                    continue;
                }

                $updates = [];
                $mfsOperator = null;
                if (!empty($servicedata->mfs_id)) {
                    $mfsOperator = MfsOperator::find($servicedata->mfs_id);
                } elseif (!empty($servicedata->mfs)) {
                    $mfsOperator = MfsOperator::where('name', $servicedata->mfs)->first();
                }

                $paymentType = $mfsOperator->type ?? 'P2A';
                $paymentMethod = $mfsOperator->name ?? $servicedata->mfs;
                $mfsOperatorId = $mfsOperator->id ?? $servicedata->mfs_id;

                $rateMerchantId = $servicedata->sub_merchant ?: $servicedata->merchant_id;
                if ($rateMerchantId && $paymentMethod) {
                    $merchantRate = calculateAmountFromRate(
                        $paymentMethod,
                        $paymentType,
                        'withdraw',
                        $rateMerchantId,
                        $amount,
                        $mfsOperatorId
                    );

                    if (empty($merchantRate['error'])) {
                        $updates['merchant_fee'] = $merchantRate['general']['fee_amount'];
                        $updates['merchant_commission'] = $merchantRate['general']['commission_amount'];
                        $updates['merchant_main_amount'] = $merchantRate['general']['net_amount'];
                        $updates['sub_merchant_fee'] = $merchantRate['sub_merchant']['fee_amount'];
                        $updates['sub_merchant_commission'] = $merchantRate['sub_merchant']['commission_amount'];
                        $updates['sub_merchant_main_amount'] = $merchantRate['sub_merchant']['net_amount'];
                    }
                }

                if (!empty($servicedata->agent_id) && is_numeric($servicedata->agent_id)) {
                    $memberRate = calculateAmountFromRateForMember(
                        $paymentMethod,
                        $paymentType,
                        'withdraw',
                        (int) $servicedata->agent_id,
                        $amount,
                        $mfsOperatorId
                    );

                    if (empty($memberRate['error'])) {
                        $updates['partner_fee'] = $memberRate['member']['fee_amount'];
                        $updates['partner_commission'] = $memberRate['member']['commission_amount'];
                        $updates['partner_main_amount'] = $memberRate['member']['net_amount'];
                        $updates['user_fee'] = $memberRate['agent']['fee_amount'];
                        $updates['user_commission'] = $memberRate['agent']['commission_amount'];
                        $updates['user_main_amount'] = $memberRate['agent']['net_amount'];
                    }
                }

                if (!empty($updates)) {
                    $servicedata->fill($updates);
                    $servicedata->save();
                }
            }
        });
}

/**
 * $merchantId = THIS IS MERCHANT ID
 * $action = THIS IS PLUS OR MINUS , PLUS IS ADDING BALANCE MINUS IS DEDUCTION BALANCE FROM MAIN balance
 * $amount = WHICH AMOUNT SHOULD IMPLEMENT
 * $merchantHas = THIS IS MERCHANT BALANCE HIT OR NOT. SOMETIME WHEN SUB MERCHANT BALANCE HIT .. THIS TIME MERCHANT BALANCE SHOULD CHECK THATS FOR
 */

function merchantBalanceAction($merchantId, $action, $amount, $merchantHas)
{
    $merchantInfo = Merchant::find($merchantId);

    if ($merchantInfo->merchant_type == 'general') {
        if ($action == 'minus') {
            $merchantInfo->balance = $merchantInfo->balance - $amount;
            $merchantHas == true ? ($merchantInfo->available_balance = $merchantInfo->available_balance - $amount) : null;
            $merchantInfo->save();
        } elseif ($action == 'plus') {
            $merchantInfo->balance = $merchantInfo->balance + $amount;
            $merchantHas == true ? ($merchantInfo->available_balance = $merchantInfo->available_balance + $amount) : null;
            $merchantInfo->save();
        }
    } elseif ($merchantInfo->merchant_type == 'sub_merchant') {
        if ($action == 'minus') {
            $merchantInfo->balance = $merchantInfo->balance - $amount;
            $merchantInfo->save();
        } elseif ($action == 'plus') {
            $merchantInfo->balance = $merchantInfo->balance + $amount;
            $merchantInfo->save();
        }
    }
}

function agentBalanceAction($agentId, $action, $amount)
{
    $foundAgent = User::find($agentId);

    if ($foundAgent) {
        if ($action == 'plus') {
            $foundAgent->update(['balance' => $foundAgent->balance + $amount]);
        } elseif ($action == 'minus') {
            $foundAgent->update(['balance' => $foundAgent->balance - $amount]);
        }
    }
}

function partnerBalanceAction($partnerId, $action, $amount, $own)
{
    $foundPartner = User::where('user_type', 'partner')->where('id', $partnerId)->first();

    $oldBalance = $foundPartner->balance;
    $oldAvailableBalance = $foundPartner->available_balance;

    if ($action === 'plus') {
        $newBalance = $oldBalance + $amount;
        $foundPartner->update(['balance' => $newBalance]);
        if ($own) {
            $newAvailableBalance = $oldAvailableBalance + $amount;
            $foundPartner->update(['available_balance' => $newAvailableBalance]);
        }
    } elseif ($action === 'minus') {
        $newBalance = $oldBalance - $amount;
        $foundPartner->update(['balance' => $newBalance]);

        if ($own) {
            $newAvailableBalance = $oldAvailableBalance - $amount;
            $foundPartner->update(['available_balance' => $newAvailableBalance]);
        }
    }
}

function calculateAmountFromRate($paymentMethod, $paymentType, $action, $merchantId, $amount, $mfsOperatorId = null)
{
    $merchant = Merchant::find($merchantId);

    if (!$merchant) {
        return [
            'error' => 'Merchant not found',
            'original_amount' => round($amount, 2),
            'general' => [],
            'sub_merchant' => [],
        ];
    }

    // ----------------------------
    // 1️⃣ General / Parent Merchant Data
    // ----------------------------
    $generalMerchantId = $merchant->merchant_type === 'sub_merchant' && $merchant->create_by ? $merchant->create_by : $merchant->id;

    $paymentMethodNormalized = $paymentMethod ? strtolower($paymentMethod) : $paymentMethod;

    if (!empty($mfsOperatorId)) {
        $generalFeeRecord = OperatorFeeCommission::where('operator_fee_commissions.mfs_operator_id', $mfsOperatorId)
            ->where('operator_fee_commissions.merchant_id', $generalMerchantId)
            ->where('operator_fee_commissions.action', $action)
            ->select('operator_fee_commissions.*')
            ->first();
    } else {
        $generalFeeRecord = OperatorFeeCommission::join('mfs_operators', 'operator_fee_commissions.mfs_operator_id', '=', 'mfs_operators.id')
            ->whereRaw('LOWER(mfs_operators.name) = ?', [$paymentMethodNormalized])
            ->where('mfs_operators.type', $paymentType)
            ->where('operator_fee_commissions.merchant_id', $generalMerchantId)
            ->where('operator_fee_commissions.action', $action)
            ->select('operator_fee_commissions.*')
            ->first();
    }

    // If not found, fallback to zeros
    $generalFeePercent = $generalFeeRecord->fee ?? 0;
    $generalCommissionPercent = $generalFeeRecord->commission ?? 0;

    $generalFeeAmount = round(($amount * $generalFeePercent) / 100, 2);
    $generalCommissionAmount = round(($amount * $generalCommissionPercent) / 100, 2);
    $generalNetAmount = $action == 'deposit' ? round($amount - $generalFeeAmount + $generalCommissionAmount, 2) : round($amount + $generalFeeAmount - $generalCommissionAmount, 2) ;

    $generalData = [
        'merchant_id' => $generalMerchantId,
        'fee_percent' => $generalFeePercent,
        'commission_percent' => $generalCommissionPercent,
        'fee_amount' => $generalFeeAmount,
        'commission_amount' => $generalCommissionAmount,
        'net_amount' => $generalNetAmount,
    ];

    // ----------------------------
    // 2️⃣ Sub-Merchant Data
    // ----------------------------
    if ($merchant->merchant_type === 'sub_merchant') {
        // Base amount for sub-merchant = general merchant’s net amount
        $baseAmount = $amount;

        if (!empty($mfsOperatorId)) {
            $subFeeRecord = OperatorFeeCommission::where('operator_fee_commissions.mfs_operator_id', $mfsOperatorId)
                ->where('operator_fee_commissions.merchant_id', $merchant->id)
                ->where('operator_fee_commissions.action', $action)
                ->select('operator_fee_commissions.*')
                ->first();
        } else {
            $subFeeRecord = OperatorFeeCommission::join('mfs_operators', 'operator_fee_commissions.mfs_operator_id', '=', 'mfs_operators.id')
                ->whereRaw('LOWER(mfs_operators.name) = ?', [$paymentMethodNormalized])
                ->where('mfs_operators.type', $paymentType)
                ->where('operator_fee_commissions.merchant_id', $merchant->id)
                ->where('operator_fee_commissions.action', $action)
                ->select('operator_fee_commissions.*')
                ->first();
        }

        // If not found, set all to zero
        $subFeePercent = $subFeeRecord->fee ?? 0;
        $subCommissionPercent = $subFeeRecord->commission ?? 0;

        $subFeeAmount = round(($baseAmount * $subFeePercent) / 100, 2);
        $subCommissionAmount = round(($baseAmount * $subCommissionPercent) / 100, 2);
        $subNetAmount = $action == 'deposit' ? round($baseAmount - $subFeeAmount + $subCommissionAmount, 2) : round($baseAmount + $subFeeAmount - $subCommissionAmount, 2) ;

        $subData = [
            'merchant_id' => $merchant->id,
            'fee_percent' => $subFeePercent,
            'commission_percent' => $subCommissionPercent,
            'fee_amount' => $subFeeAmount,
            'commission_amount' => $subCommissionAmount,
            'net_amount' => $subNetAmount,
        ];
    } else {
        // Not a sub-merchant: all zeroed out
        $subData = [
            'merchant_id' => $merchant->id,
            'fee_percent' => 0.0,
            'commission_percent' => 0.0,
            'fee_amount' => 0.0,
            'commission_amount' => 0.0,
            'net_amount' => 0.0,
        ];
    }

    return [
        'original_amount' => round($amount, 2),
        'general' => $generalData,
        'sub_merchant' => $subData,
    ];
}

function calculateAmountFromRateForMember($paymentMethod, $paymentType, $action, $memberId, $amount, $mfsOperatorId = null)
{
    $member = User::find($memberId);

    if (!$member) {
        return [
            'error' => 'Member not found',
            'original_amount' => round($amount, 2),
            'partner' => [],
            'agent' => [],
        ];
    }

    // ----------------------------
    // 1️⃣ General / Parent Merchant Data
    // ----------------------------
    $partnerId = $member->user_type === 'agent' && $member->create_by ? $member->create_by : $member->id;

    $paymentMethodNormalized = $paymentMethod ? strtolower($paymentMethod) : $paymentMethod;

    if (!empty($mfsOperatorId)) {
        $partnerFeeRecord = UserCharge::where('user_charges.mfs_operator_id', $mfsOperatorId)
            ->where('user_charges.user_id', $partnerId)
            ->where('user_charges.action', $action)
            ->select('user_charges.*')
            ->first();
    } else {
        $partnerFeeRecord = UserCharge::join('mfs_operators', 'user_charges.mfs_operator_id', '=', 'mfs_operators.id')
            ->whereRaw('LOWER(mfs_operators.name) = ?', [$paymentMethodNormalized])
            ->where('mfs_operators.type', $paymentType)
            ->where('user_charges.user_id', $partnerId)
            ->where('user_charges.action', $action)
            ->select('user_charges.*')
            ->first();
    }

    // If not found, fallback to zeros
    $partnerFeePercent = $partnerFeeRecord->fee ?? 0;
    $partnerCommissionPercent = $partnerFeeRecord->commission ?? 0;

    $partnerFeeAmount = round(($amount * $partnerFeePercent) / 100, 2);
    $partnerCommissionAmount = round(($amount * $partnerCommissionPercent) / 100, 2);
    $partnerNetAmount = $action == 'deposit' ? round($amount - $partnerFeeAmount + $partnerCommissionAmount, 2) : round($amount + $partnerFeeAmount - $partnerCommissionAmount, 2) ;

    $partnerData = [
        'partner_id' => $partnerId,
        'fee_percent' => $partnerFeePercent,
        'commission_percent' => $partnerCommissionPercent,
        'fee_amount' => $partnerFeeAmount,
        'commission_amount' => $partnerCommissionAmount,
        'net_amount' => $partnerNetAmount,
    ];

    // ----------------------------
    // 2️⃣ agent Data
    // ----------------------------
    if ($member->user_type === 'agent') {
        // Base amount for sub-merchant = general merchant’s net amount
        $baseAmount = $amount;

        if (!empty($mfsOperatorId)) {
            $subFeeRecord = UserCharge::where('user_charges.mfs_operator_id', $mfsOperatorId)
                ->where('user_charges.user_id', $member->id)
                ->where('user_charges.action', $action)
                ->select('user_charges.*')
                ->first();
        } else {
            $subFeeRecord = UserCharge::join('mfs_operators', 'user_charges.mfs_operator_id', '=', 'mfs_operators.id')
                ->whereRaw('LOWER(mfs_operators.name) = ?', [$paymentMethodNormalized])
                ->where('mfs_operators.type', $paymentType)
                ->where('user_charges.user_id', $member->id)
                ->where('user_charges.action', $action)
                ->select('user_charges.*')
                ->first();
        }

        // If not found, set all to zero
        $subFeePercent = $subFeeRecord->fee ?? 0;
        $subCommissionPercent = $subFeeRecord->commission ?? 0;

        $subFeeAmount = round(($baseAmount * $subFeePercent) / 100, 2);
        $subCommissionAmount = round(($baseAmount * $subCommissionPercent) / 100, 2);
        $subNetAmount = $action == 'deposit' ? round($baseAmount - $subFeeAmount + $subCommissionAmount, 2) : round($baseAmount + $subFeeAmount - $subCommissionAmount, 2) ;

        $subData = [
            'user_id' => $member->id,
            'fee_percent' => $subFeePercent,
            'commission_percent' => $subCommissionPercent,
            'fee_amount' => $subFeeAmount,
            'commission_amount' => $subCommissionAmount,
            'net_amount' => $subNetAmount,
        ];
    } else {
        // Not a sub-merchant: all zeroed out
        $subData = [
            'user_id' => $member->id,
            'fee_percent' => 0.0,
            'commission_percent' => 0.0,
            'fee_amount' => 0.0,
            'commission_amount' => 0.0,
            'net_amount' => 0.0,
        ];
    }

    // ----------------------------
    // ✅ Return Complete Structure
    // ----------------------------
    return [
        'original_amount' => round($amount, 2),
        'member' => $partnerData,
        'agent' => $subData,
    ];
}

function serviceRequestRejectBalanceHandler($serviceRequestId)
{
    $servicedata = ServiceRequest::find($serviceRequestId);

    if ($servicedata && $servicedata->status == 4) {
        if ($servicedata->sub_merchant) {
            merchantBalanceAction($servicedata->sub_merchant, 'plus', $servicedata->sub_merchant_main_amount, false);

            merchantBalanceAction($servicedata->merchant_id, 'plus', $servicedata->merchant_main_amount, false);
        } else {
            merchantBalanceAction($servicedata->merchant_id, 'plus', $servicedata->merchant_main_amount, true);
        }
    }
}

function paymentRequestRejectBalanceHandler($paymentRequestId)
{
    $request = PaymentRequest::where('merchant_balance_updated', 0)->where('id', $paymentRequestId)->first();

    if ($request && $request->status == 3) {
        if ($request->sub_merchant) {
            merchantBalanceAction($request->sub_merchant, 'minus', $request->sub_merchant_main_amount, false);
            merchantBalanceAction($request->merchant_id, 'minus', $request->merchant_main_amount, false);

            $request->merchant_balance_updated = 1;
            $request->save();
        } else {
            merchantBalanceAction($request->merchant_id, 'minus', $request->merchant_main_amount, true);

            $request->merchant_balance_updated = 1;
            $request->save();
        }
    }
}

function paymentRequestApprovedBalanceHandler($paymentRequestId, $type)
{
    if ($type == 'id') {
        $request = PaymentRequest::whereIn('status', [1, 2])
            ->where('id', $paymentRequestId)
            ->where('merchant_balance_updated', 0)
            ->first();
    } elseif ($type == 'req_id') {
        $request = PaymentRequest::whereIn('status', [1, 2])
            ->where('request_id', $paymentRequestId)
            ->where('merchant_balance_updated', 0)
            ->first();
    }

    if (!$request) {
        return null;
    }

    $updates = [];
    $amount = (float) $request->amount;

    $needsMerchantRate = $amount > 0 && (
        $request->merchant_main_amount === null || $request->merchant_main_amount <= 0 ||
        ($request->sub_merchant && ($request->sub_merchant_main_amount === null || $request->sub_merchant_main_amount <= 0))
    );

    if ($needsMerchantRate) {
        $rateMerchantId = $request->sub_merchant ?: $request->merchant_id;
        $merchantRate = calculateAmountFromRate(
            $request->payment_method,
            $request->payment_type,
            'deposit',
            $rateMerchantId,
            $amount
        );

        if (empty($merchantRate['error'])) {
            $updates['merchant_fee'] = $merchantRate['general']['fee_amount'];
            $updates['merchant_commission'] = $merchantRate['general']['commission_amount'];
            $updates['merchant_main_amount'] = $merchantRate['general']['net_amount'];

            $updates['sub_merchant_fee'] = $merchantRate['sub_merchant']['fee_amount'];
            $updates['sub_merchant_commission'] = $merchantRate['sub_merchant']['commission_amount'];
            $updates['sub_merchant_main_amount'] = $merchantRate['sub_merchant']['net_amount'];
        }
    }

    $agent = User::where('member_code', $request->agent)->first();
    $needsMemberRate = $amount > 0 && (
        $request->user_main_amount === null || $request->user_main_amount <= 0 ||
        $request->partner_main_amount === null || $request->partner_main_amount <= 0
    );

    if ($agent && $needsMemberRate) {
        $memberRate = calculateAmountFromRateForMember(
            $request->payment_method,
            $request->payment_type,
            'deposit',
            $agent->id,
            $amount
        );

        if (empty($memberRate['error'])) {
            $updates['partner_fee'] = $memberRate['member']['fee_amount'];
            $updates['partner_commission'] = $memberRate['member']['commission_amount'];
            $updates['partner_main_amount'] = $memberRate['member']['net_amount'];

            $updates['user_fee'] = $memberRate['agent']['fee_amount'];
            $updates['user_commission'] = $memberRate['agent']['commission_amount'];
            $updates['user_main_amount'] = $memberRate['agent']['net_amount'];
        }
    }

    if (!empty($updates)) {
        $request->fill($updates);
        $request->save();
    }

    if ($request->sub_merchant) {
        merchantBalanceAction($request->sub_merchant, 'plus', $request->sub_merchant_main_amount, false);
        merchantBalanceAction($request->merchant_id, 'plus', $request->merchant_main_amount, false);

        $request->merchant_balance_updated = 1;
        $request->save();
    } else {
        merchantBalanceAction($request->merchant_id, 'plus', $request->merchant_main_amount, true);
        $request->merchant_balance_updated = 1;
        $request->save();
    }

    if ($agent && $agent->user_type == 'agent') {
        agentBalanceAction($agent->id, 'plus', $request->user_main_amount);
    }

    $partner = User::where('member_code', $request->partner)->first();

    if ($partner && $partner->user_type == 'partner') {
        partnerBalanceAction($partner->id, 'plus', $request->partner_main_amount, false);
    }
}

function serviceRequestApprovedBalanceHandler($serviceRequestId)
{
    $servicedata = ServiceRequest::where('id', $serviceRequestId)
        ->whereIn('status', [2, 3])
        ->where('merchant_balance_updated', 0)
        ->first();

    if ($servicedata) {
        $updates = [];
        $amount = (float) $servicedata->amount;

        $needsMerchantRate = $amount > 0 && (
            $servicedata->merchant_main_amount === null || $servicedata->merchant_main_amount <= 0 ||
            ($servicedata->sub_merchant && ($servicedata->sub_merchant_main_amount === null || $servicedata->sub_merchant_main_amount <= 0))
        );

        if ($needsMerchantRate && $servicedata->merchant_id) {
            $mfsOperator = null;
            if (!empty($servicedata->mfs_id)) {
                $mfsOperator = MfsOperator::find($servicedata->mfs_id);
            } elseif (!empty($servicedata->mfs)) {
                $mfsOperator = MfsOperator::where('name', $servicedata->mfs)->first();
            }

            $paymentType = $mfsOperator->type ?? 'P2A';
            $paymentMethod = $mfsOperator->name ?? $servicedata->mfs;
            $mfsOperatorId = $mfsOperator->id ?? $servicedata->mfs_id;
            $rateMerchantId = $servicedata->sub_merchant ?: $servicedata->merchant_id;

            $merchantRate = calculateAmountFromRate(
                $paymentMethod,
                $paymentType,
                'withdraw',
                $rateMerchantId,
                $amount,
                $mfsOperatorId
            );

            if (empty($merchantRate['error'])) {
                $updates['merchant_fee'] = $merchantRate['general']['fee_amount'];
                $updates['merchant_commission'] = $merchantRate['general']['commission_amount'];
                $updates['merchant_main_amount'] = $merchantRate['general']['net_amount'];

                $updates['sub_merchant_fee'] = $merchantRate['sub_merchant']['fee_amount'];
                $updates['sub_merchant_commission'] = $merchantRate['sub_merchant']['commission_amount'];
                $updates['sub_merchant_main_amount'] = $merchantRate['sub_merchant']['net_amount'];
            }
        }

        $needsMemberRate = $amount > 0 && (
            $servicedata->user_main_amount === null || $servicedata->user_main_amount <= 0 ||
            $servicedata->partner_main_amount === null || $servicedata->partner_main_amount <= 0
        );

        if ($needsMemberRate && $servicedata->agent_id) {
            $mfsOperator = null;
            if (!empty($servicedata->mfs_id)) {
                $mfsOperator = MfsOperator::find($servicedata->mfs_id);
            } elseif (!empty($servicedata->mfs)) {
                $mfsOperator = MfsOperator::where('name', $servicedata->mfs)->first();
            }

            $paymentType = $mfsOperator->type ?? 'P2A';
            $paymentMethod = $mfsOperator->name ?? $servicedata->mfs;
            $mfsOperatorId = $mfsOperator->id ?? $servicedata->mfs_id;
            $memberRate = calculateAmountFromRateForMember(
                $paymentMethod,
                $paymentType,
                'withdraw',
                $servicedata->agent_id,
                $amount,
                $mfsOperatorId
            );

            if (empty($memberRate['error'])) {
                $updates['partner_fee'] = $memberRate['member']['fee_amount'];
                $updates['partner_commission'] = $memberRate['member']['commission_amount'];
                $updates['partner_main_amount'] = $memberRate['member']['net_amount'];

                $updates['user_fee'] = $memberRate['agent']['fee_amount'];
                $updates['user_commission'] = $memberRate['agent']['commission_amount'];
                $updates['user_main_amount'] = $memberRate['agent']['net_amount'];
            }
        }

        if (!empty($updates)) {
            $servicedata->fill($updates);
            $servicedata->save();
        }

        if ($servicedata->sub_merchant) {
            merchantBalanceAction($servicedata->sub_merchant, 'minus', $servicedata->sub_merchant_main_amount, false);

            merchantBalanceAction($servicedata->merchant_id, 'minus', $servicedata->merchant_main_amount, false);

            $servicedata->merchant_balance_updated = 1;
            $servicedata->save();
        } else {
            merchantBalanceAction($servicedata->merchant_id, 'minus', $servicedata->merchant_main_amount, true);

            $servicedata->merchant_balance_updated = 1;
            $servicedata->save();
        }

        agentBalanceAction($servicedata->agent_id, 'minus', $servicedata->user_main_amount);
        partnerBalanceAction($servicedata->partner, 'minus', $servicedata->partner_main_amount, false);
    }
}
