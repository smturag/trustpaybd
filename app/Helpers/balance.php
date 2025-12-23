<?php

use App\Models\Customer;
use App\Models\Merchant;
use App\Models\OperatorFeeCommission;
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use App\Models\User;
use App\Models\UserCharge;
use Illuminate\Support\Facades\Log;

function allBalanceZero()
{
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

function calculateAmountFromRate($paymentMethod, $paymentType, $action, $merchantId, $amount)
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

    $generalFeeRecord = OperatorFeeCommission::join('mfs_operators', 'operator_fee_commissions.mfs_operator_id', '=', 'mfs_operators.id')->where('mfs_operators.name', $paymentMethod)->where('mfs_operators.type', $paymentType)->where('operator_fee_commissions.merchant_id', $generalMerchantId)->where('operator_fee_commissions.action', $action)->select('operator_fee_commissions.*')->first();

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

        $subFeeRecord = OperatorFeeCommission::join('mfs_operators', 'operator_fee_commissions.mfs_operator_id', '=', 'mfs_operators.id')->where('mfs_operators.name', $paymentMethod)->where('mfs_operators.type', $paymentType)->where('operator_fee_commissions.merchant_id', $merchant->id)->where('operator_fee_commissions.action', $action)->select('operator_fee_commissions.*')->first();

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

function calculateAmountFromRateForMember($paymentMethod, $paymentType, $action, $memberId, $amount)
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

    $partnerFeeRecord = UserCharge::join('mfs_operators', 'user_charges.mfs_operator_id', '=', 'mfs_operators.id')->where('mfs_operators.name', $paymentMethod)->where('mfs_operators.type', $paymentType)->where('user_charges.user_id', $partnerId)->where('user_charges.action', $action)->select('user_charges.*')->first();

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

        $subFeeRecord = UserCharge::join('mfs_operators', 'user_charges.mfs_operator_id', '=', 'mfs_operators.id')->where('mfs_operators.name', $paymentMethod)->where('mfs_operators.type', $paymentType)->where('user_charges.user_id', $member->id)->where('user_charges.action', $action)->select('user_charges.*')->first();

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

    $agent = User::where('member_code', $request->agent)->first();

    $agent->user_type == 'agent' ? agentBalanceAction($agent->id, 'plus', $request->user_main_amount) : null;

    $partner = User::where('member_code', $request->partner)->first();

    $partner->user_type == 'partner' ? partnerBalanceAction($partner->id, 'plus', $request->partner_main_amount, false) : null;
}

function serviceRequestApprovedBalanceHandler($serviceRequestId)
{
    $servicedata = ServiceRequest::where('id', $serviceRequestId)
        ->whereIn('status', [2, 3])
        ->where('merchant_balance_updated', 0)
        ->first();

    if ($servicedata) {
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
