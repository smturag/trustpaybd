<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\CryptoCurrency;
use App\Models\CurrencyRate;
use App\Models\Merchant;
use App\Models\MerchantPayoutRequest;
use App\Models\PayoutSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class MerchantPayoutController extends Controller
{
    /**
     * Show payout request form
     */
    public function index()
    {
        $merchant = Auth::guard('merchant')->user();
        
        // Check if merchant has withdraw permission
        if ($merchant->withdraw_status == 0) {
            return redirect()->back()->with('alert', 'Your payout permission is off. Please contact admin.');
        }

        // Get active currencies
        $currencies = CurrencyRate::getActiveCurrencies();
        
        // Get merchant's preferred currency
        $merchantCurrency = $merchant->preferred_currency ?? 'BDT';
        
        // Calculate available balance (always stored in BDT)
        $balanceBDT = $merchant->merchant_type === 'sub_merchant'
            ? $merchant->balance
            : $merchant->available_balance;
        
        // Convert balance to merchant's preferred currency
        $availableBalance = CurrencyRate::convertFromBDT($balanceBDT, $merchantCurrency);
        
        // Get exchange rate
        $exchangeRate = CurrencyRate::getRate($merchantCurrency);

        // Get fee percentage for merchant's currency
        $feePercentage = CurrencyRate::getFee($merchantCurrency);

        return view('merchant.payout.create', compact('availableBalance', 'feePercentage', 'currencies', 'merchantCurrency', 'exchangeRate', 'balanceBDT'));
    }

    /**
     * Store payout request
     * 
     * Process Flow:
     * 1. Validate request data
     * 2. Get merchant details and calculate available balance (in BDT)
     * 3. Convert requested amount from merchant currency to BDT
     * 4. Check if sufficient balance exists
     * 5. Calculate fee and net amount (in BDT)
     * 6. Create payout request record
     * 7. Deduct amount from merchant balance immediately (reserve funds)
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'wallet_address' => 'required|string|max:255',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|exists:currency_rates,currency_code',
        ]);

        $merchant = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;
        $merchant_type = $merchant->merchant_type === 'sub_merchant' ? 'sub_merchant' : 'general';
        $adminMerchantId = $merchant_type === 'sub_merchant' ? $merchant->create_by : null;
        $actualMerchantId = $adminMerchantId ?? $merchantId;

        // Get available balance (always in BDT - base currency)
        // available_balance = merchant's legal/usable balance
        // balance = total balance including sub-merchants network
        $balanceBDT = $merchant_type === 'sub_merchant' 
            ? $merchant->balance 
            : $merchant->available_balance;
        
        // Get merchant's currency and exchange rate
        $merchantCurrency = $request->currency;
        $exchangeRate = CurrencyRate::getRate($merchantCurrency);
        
        // Convert requested amount from merchant currency to BDT (base currency)
        $requestedAmountBDT = CurrencyRate::convertToBDT($request->amount, $merchantCurrency);

        // Check if sufficient balance
        if ($requestedAmountBDT > $balanceBDT) {
            return redirect()->back()
                ->with('alert', 'Insufficient balance. Your available balance is ' . number_format($balanceBDT, 2) . ' BDT')
                ->withInput();
        }

        // Get payout currency from request
        $payoutCurrency = $request->input('payout_currency', $merchantCurrency);
        
        // Get fee percentage for the payout currency
        $feePercentage = CurrencyRate::getFee($payoutCurrency);
        
        // Calculate fee (on BDT amount)
        $feeBDT = $requestedAmountBDT * ($feePercentage / 100);
        $netAmountBDT = $requestedAmountBDT - $feeBDT;

        // Generate unique payout ID
        $payoutId = 'PAYOUT-' . $actualMerchantId . '-' . now()->format('YmdHis') . '-' . rand(1000, 9999);

        // Calculate remaining balance after payout
        $newBalanceBDT = $balanceBDT - $requestedAmountBDT;

        DB::beginTransaction();

        try {
            // Create payout request record
            $payout = MerchantPayoutRequest::create([
                'payout_id' => $payoutId,
                'merchant_id' => $actualMerchantId,
                'sub_merchant' => $merchant_type === 'sub_merchant' ? $merchantId : null,
                'wallet_address' => $request->wallet_address,
                // All amounts stored in BDT (base currency)
                'amount' => $requestedAmountBDT,
                'fee' => $feeBDT,
                'net_amount' => $netAmountBDT,
                // Merchant currency information
                'merchant_currency' => $merchantCurrency,
                'merchant_amount' => $request->amount,
                'exchange_rate' => $exchangeRate,
                'bdt_amount' => $requestedAmountBDT,
                // Balance tracking
                'old_balance' => $balanceBDT,
                'new_balance' => $newBalanceBDT,
                'status' => 0, // 0 = Pending
            ]);

            // Deduct amount from merchant balance when creating request (reserve funds)
            // This prevents merchant from spending the same funds while request is pending
            if ($merchant_type === 'sub_merchant') {
                // For sub-merchant: deduct from their balance
                Merchant::where('id', $merchantId)->decrement('balance', $requestedAmountBDT);
                // Also deduct from main merchant's total balance
                Merchant::where('id', $actualMerchantId)->decrement('balance', $requestedAmountBDT);
            } else {
                // For general merchant: deduct from available_balance (legal balance)
                Merchant::where('id', $actualMerchantId)->decrement('available_balance', $requestedAmountBDT);
                // Also deduct from total balance
                Merchant::where('id', $actualMerchantId)->decrement('balance', $requestedAmountBDT);
            }

            DB::commit();

            // Convert amounts for display message
            $requestedAmountDisplay = number_format($request->amount, 2) . ' ' . $merchantCurrency;
            $netAmountDisplay = number_format(CurrencyRate::convertFromBDT($netAmountBDT, $merchantCurrency), 2) . ' ' . $merchantCurrency;

            return redirect()->route('merchant.payout-history')
                ->with('success', "Payout request submitted successfully! You requested {$requestedAmountDisplay}, and will receive {$netAmountDisplay} after fees.");
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payout request failed: ' . $e->getMessage());
            return redirect()->back()
                ->with('alert', 'Something went wrong. Please try again or contact support.')
                ->withInput();
        }
    }

    /**
     * Show payout history
     */
    public function history(Request $request)
    {
        $merchant = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;
        $merchant_type = $merchant->merchant_type;

        $query = MerchantPayoutRequest::with(['cryptoCurrency', 'approvedBy']);

        if ($merchant_type === 'sub_merchant') {
            $query->where('sub_merchant', $merchantId);
        } else {
            $query->where('merchant_id', $merchantId)->whereNull('sub_merchant');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->has('start_date') && $request->start_date) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }

        $payouts = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('merchant.payout.history', compact('payouts'));
    }

    /**
     * Show payout details
     */
    public function show($id)
    {
        $merchant = Auth::guard('merchant')->user();
        $merchantId = $merchant->id;
        $merchant_type = $merchant->merchant_type;

        $query = MerchantPayoutRequest::with(['cryptoCurrency', 'approvedBy', 'merchant', 'subMerchant']);

        if ($merchant_type === 'sub_merchant') {
            $query->where('sub_merchant', $merchantId);
        } else {
            $query->where('merchant_id', $merchantId);
        }

        $payout = $query->findOrFail($id);

        return view('merchant.payout.details', compact('payout'));
    }
}
