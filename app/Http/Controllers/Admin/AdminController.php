<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PaymentRequest;
use App\Models\Merchant;
use App\Models\User;
use App\Models\OperatorFeeCommission;
use Illuminate\Support\Facades\Schema;
use App\Models\MfsOperator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
	//

	public function dashboard() {
        return view('admin.admin-dashboard-new');
    }

    public function reset_balance(){
        DB::beginTransaction();
        
        try {
            // Step 1: Reset all balances to zero
            Merchant::query()->update([
                'balance' => 0,
                'available_balance' => 0
            ]);
            
            User::query()->update(['balance' => 0, 'available_balance' => 0]);
            
            // Reset balance update flags in payment_requests
            PaymentRequest::query()->update([
                'merchant_balance_updated' => 0,
                'balance_updated' => 0
            ]);
            
            Log::info('Reset Balance: All balances set to zero');
            
            // Step 2: Get all approved payment requests and update fees/commissions
            $paymentRequests = PaymentRequest::whereIn('status', [1, 2])
                ->orderBy('id', 'asc')
                ->get();
            
            $processedCount = 0;
            $updatedCount = 0;
            
            foreach ($paymentRequests as $payment) {
                $processedCount++;
                
                // Get the MFS operator based on payment_method (using 'name' column, not 'operator')
                $mfsOperator = MfsOperator::where('name', $payment->payment_method)->first();
                
                if (!$mfsOperator) {
                    Log::warning("Reset Balance: MFS Operator not found for payment_method: {$payment->payment_method}, Payment ID: {$payment->id}");
                    continue;
                }
                
                $amount = $payment->amount;
                
                // === MERCHANT FEE & COMMISSION ===
                // First check if merchant has custom fee/commission
                $merchantFeeCommission = OperatorFeeCommission::where('merchant_id', $payment->merchant_id)
                    ->where('mfs_operator_id', $mfsOperator->id)
                    ->where('action', 'deposit')
                    ->first();
                
                if ($merchantFeeCommission) {
                    // Use merchant-specific fee/commission
                    $merchantFeePercent = $merchantFeeCommission->fee;
                    $merchantCommissionPercent = $merchantFeeCommission->commission;
                } else {
                    // Use default fee/commission from mfs_operators table
                    $merchantFeePercent = $mfsOperator->deposit_fee ?? 0;
                    $merchantCommissionPercent = $mfsOperator->deposit_commission ?? 0;
                }
                
                $merchantFee = round(($amount * $merchantFeePercent) / 100, 2);
                $merchantCommission = round(($amount * $merchantCommissionPercent) / 100, 2);
                $merchantMainAmount = round($amount - $merchantFee + $merchantCommission, 2);
                
                // === SUB-MERCHANT FEE & COMMISSION ===
                $subMerchantFee = 0;
                $subMerchantCommission = 0;
                $subMerchantMainAmount = 0;
                
                if ($payment->sub_merchant && $payment->sub_merchant != '0') {
                    $subFeeCommission = OperatorFeeCommission::where('merchant_id', $payment->sub_merchant)
                        ->where('mfs_operator_id', $mfsOperator->id)
                        ->where('action', 'deposit')
                        ->first();
                    
                    if ($subFeeCommission) {
                        // Use sub-merchant specific fee/commission
                        $subFeePercent = $subFeeCommission->fee;
                        $subCommissionPercent = $subFeeCommission->commission;
                    } else {
                        // Use default fee/commission from mfs_operators table
                        $subFeePercent = $mfsOperator->deposit_fee ?? 0;
                        $subCommissionPercent = $mfsOperator->deposit_commission ?? 0;
                    }
                    
                    $subMerchantFee = round(($amount * $subFeePercent) / 100, 2);
                    $subMerchantCommission = round(($amount * $subCommissionPercent) / 100, 2);
                    $subMerchantMainAmount = round($amount - $subMerchantFee + $subMerchantCommission, 2);
                }
                
                // === AGENT FEE & COMMISSION ===
                $userFee = 0;
                $userCommission = 0;
                $userMainAmount = 0;
                
                if ($payment->agent) {
                    $agent = User::where('id', $payment->agent)->where('user_type', 'agent')->first();
                    if ($agent) {
                        // Check if agent has custom fee in operator_fee_commissions
                        // Note: merchant_id in operator_fee_commissions can reference users table id
                        $agentFeeCommission = OperatorFeeCommission::where('merchant_id', $payment->agent)
                            ->where('mfs_operator_id', $mfsOperator->id)
                            ->where('action', 'deposit')
                            ->first();
                        
                        if ($agentFeeCommission) {
                            $agentFeePercent = $agentFeeCommission->fee;
                            $agentCommissionPercent = $agentFeeCommission->commission;
                        } else {
                            // Use default from mfs_operators
                            $agentFeePercent = $mfsOperator->deposit_fee ?? 0;
                            $agentCommissionPercent = $mfsOperator->deposit_commission ?? 0;
                        }
                        
                        $userFee = round(($amount * $agentFeePercent) / 100, 2);
                        $userCommission = round(($amount * $agentCommissionPercent) / 100, 2);
                        $userMainAmount = round($amount - $userFee + $userCommission, 2);
                    }
                }
                
                // === PARTNER FEE & COMMISSION ===
                $partnerFee = 0;
                $partnerCommission = 0;
                $partnerMainAmount = 0;
                
                if ($payment->partner) {
                    $partner = User::where('id', $payment->partner)->where('user_type', 'partner')->first();
                    if ($partner) {
                        $partnerFeeCommission = OperatorFeeCommission::where('merchant_id', $payment->partner)
                            ->where('mfs_operator_id', $mfsOperator->id)
                            ->where('action', 'deposit')
                            ->first();
                        
                        if ($partnerFeeCommission) {
                            $partnerFeePercent = $partnerFeeCommission->fee;
                            $partnerCommissionPercent = $partnerFeeCommission->commission;
                        } else {
                            // Use default from mfs_operators
                            $partnerFeePercent = $mfsOperator->deposit_fee ?? 0;
                            $partnerCommissionPercent = $mfsOperator->deposit_commission ?? 0;
                        }
                        
                        $partnerFee = round(($amount * $partnerFeePercent) / 100, 2);
                        $partnerCommission = round(($amount * $partnerCommissionPercent) / 100, 2);
                        $partnerMainAmount = round($amount - $partnerFee + $partnerCommission, 2);
                    }
                }
                
                // === UPDATE PAYMENT REQUEST WITH ALL CALCULATED VALUES ===
                $payment->update([
                    'merchant_fee' => $merchantFee,
                    'merchant_commission' => $merchantCommission,
                    'merchant_main_amount' => $merchantMainAmount,
                    'sub_merchant_fee' => $subMerchantFee,
                    'sub_merchant_commission' => $subMerchantCommission,
                    'sub_merchant_main_amount' => $subMerchantMainAmount,
                    'user_fee' => $userFee,
                    'user_commission' => $userCommission,
                    'user_main_amount' => $userMainAmount,
                    'partner_fee' => $partnerFee,
                    'partner_commission' => $partnerCommission,
                    'partner_main_amount' => $partnerMainAmount,
                ]);
                
                $updatedCount++;
            }
            
            Log::info("Reset Balance: Phase 1 Complete - Updated {$updatedCount} payment requests with fees/commissions");
            
            // Step 3: Now recalculate all balances based on updated payment_requests (ADD)
            foreach ($paymentRequests as $payment) {
                // Update Merchant Balance
                $merchant = Merchant::find($payment->merchant_id);
                if ($merchant) {
                    if ($merchant->merchant_type === 'general') {
                        $merchant->increment('available_balance', $payment->merchant_main_amount);
                    }
                    $merchant->increment('balance', $payment->merchant_main_amount);
                }
                
                // Update Sub-Merchant Balance
                if ($payment->sub_merchant && $payment->sub_merchant != '0') {
                    $subMerchant = Merchant::find($payment->sub_merchant);
                    if ($subMerchant) {
                        $subMerchant->increment('balance', $payment->sub_merchant_main_amount);
                    }
                }
                
                // Update Agent Balance (from users table where user_type='agent')
                if ($payment->agent && $payment->user_main_amount > 0) {
                    $agent = User::where('id', $payment->agent)->where('user_type', 'agent')->first();
                    if ($agent) {
                        $agent->increment('balance', $payment->user_main_amount);
                    }
                }
                
                // Update Partner Balance (from users table where user_type='partner')
                if ($payment->partner && $payment->partner_main_amount > 0) {
                    $partner = User::where('id', $payment->partner)->where('user_type', 'partner')->first();
                    if ($partner) {
                        $partner->increment('balance', $payment->partner_main_amount);
                    }
                }
                
                // Mark as balance updated
                $payment->update([
                    'merchant_balance_updated' => 1,
                    'balance_updated' => 1
                ]);
            }
            
            Log::info("Reset Balance: Phase 2 Complete - Added payment requests to balances");
            
            // Step 4: Process service_requests (MINUS from balances)
            $serviceRequests = DB::table('service_requests')
                ->whereIn('status', [1, 2])
                ->orderBy('id', 'asc')
                ->get();
            
            $serviceCount = 0;
            foreach ($serviceRequests as $service) {
                $serviceCount++;
                
                // Deduct from Merchant Balance
                if ($service->merchant_id) {
                    $merchant = Merchant::find($service->merchant_id);
                    if ($merchant && $service->merchant_main_amount) {
                        $merchant->decrement('balance', $service->merchant_main_amount);
                        if ($merchant->merchant_type === 'general') {
                            $merchant->decrement('available_balance', $service->merchant_main_amount);
                        }
                    }
                }
                
                // Deduct from Sub-Merchant Balance
                if ($service->sub_merchant && $service->sub_merchant != '0' && $service->sub_merchant_main_amount) {
                    $subMerchant = Merchant::find($service->sub_merchant);
                    if ($subMerchant) {
                        $subMerchant->decrement('balance', $service->sub_merchant_main_amount);
                    }
                }
                
                // Deduct from Agent Balance
                if ($service->agent_id && $service->user_main_amount > 0) {
                    $agent = User::where('id', $service->agent_id)->where('user_type', 'agent')->first();
                    if ($agent) {
                        $agent->decrement('balance', $service->user_main_amount);
                    }
                }
                
                // Deduct from Partner Balance
                if ($service->partner && $service->partner_main_amount > 0) {
                    $partner = User::where('id', $service->partner)->where('user_type', 'partner')->first();
                    if ($partner) {
                        $partner->decrement('balance', $service->partner_main_amount);
                    }
                }
            }
            
            Log::info("Reset Balance: Phase 3 Complete - Deducted {$serviceCount} service requests from balances");
            
            // Step 5: Process merchant_payout_requests (MINUS from balances)
            if (Schema::hasTable('merchant_payout_requests')) {
                $payoutRequests = DB::table('merchant_payout_requests')
                    ->whereIn('status', [2, 4]) // 2=approved, 4=completed
                    ->orderBy('id', 'asc')
                    ->get();
                
                $payoutCount = 0;
                foreach ($payoutRequests as $payout) {
                    $payoutCount++;
                    
                    // Deduct net_amount from merchant balance
                    if ($payout->merchant_id) {
                        $merchant = Merchant::find($payout->merchant_id);
                        if ($merchant && $payout->net_amount) {
                            $merchant->decrement('balance', $payout->net_amount);
                            if ($merchant->merchant_type === 'general') {
                                $merchant->decrement('available_balance', $payout->net_amount);
                            }
                        }
                    }
                    
                    // Deduct from sub-merchant if exists
                    if ($payout->sub_merchant && $payout->sub_merchant != '0') {
                        $subMerchant = Merchant::find($payout->sub_merchant);
                        if ($subMerchant && $payout->net_amount) {
                            $subMerchant->decrement('balance', $payout->net_amount);
                        }
                    }
                }
                
                Log::info("Reset Balance: Phase 4 Complete - Deducted {$payoutCount} payout requests from balances");
            }
            
            DB::commit();
            
            Log::info("Reset Balance: Complete. Payments: {$processedCount}, Services: {$serviceCount}, Payouts: " . ($payoutCount ?? 0));
            
            return redirect()->back()->with('message', "Balance reset complete! Added {$processedCount} payment deposits, deducted {$serviceCount} service requests, and " . ($payoutCount ?? 0) . " payout requests.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reset Balance Error: ' . $e->getMessage());
            Log::error($e->getTraceAsString());
            
            return redirect()->back()->with('alert', 'Error resetting balance: ' . $e->getMessage());
        }
    }

}
