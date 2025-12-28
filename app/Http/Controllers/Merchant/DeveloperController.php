<?php

namespace App\Http\Controllers\Merchant;

use App\Models\MerchantIpWhitelist;
use App\Models\MerchantPvtPublicKey;
use App\Models\MfsOperator;
use App\Models\OperatorFeeCommission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

class DeveloperController
{
    public function index()
    {
        $merchantId = auth()->guard('merchant')->user()->id;

        $data = [
            'pageTitle' => 'Developer Option',
            'merchantToken' => MerchantPvtPublicKey::with(['api_key'])
                ->where('merchant_id', $merchantId)
                ->first(),
            'ipWhitelist' => MerchantIpWhitelist::where('merchant_id', $merchantId)
                ->orderBy('created_at', 'desc')
                ->get(),
        ];

        return view('merchant.developer')->with($data);
    }

    public function serviceRates(Request $request)
    {
        $merchantId = auth()->guard('merchant')->user()->id;

        // Get all operators for dropdown (unique by name)
        $allOperators = MfsOperator::where('status', 1)
            ->orderBy('name')
            ->get()
            ->unique('name');

        // Build query for filtered operators
        $operatorsQuery = MfsOperator::where('status', 1);

        // Apply operator filter
        if ($request->filled('operator')) {
            $operatorsQuery->where('name', $request->operator);
        }

        // Apply type filter
        if ($request->filled('type')) {
            $operatorsQuery->where('type', $request->type);
        }

        $operators = $operatorsQuery->orderBy('name')->get();

        // Filter by action if needed
        $filteredOperators = $operators;
        $action = $request->action;

        $data = [
            'pageTitle' => 'Service Rates',
            'rates' => OperatorFeeCommission::where('merchant_id', $merchantId)->get(),
            'operators' => $filteredOperators,
            'allOperators' => $allOperators,
            'filters' => [
                'operator' => $request->operator,
                'type' => $request->type,
                'action' => $request->action,
            ],
        ];

        return view('merchant.developer.service_rates')->with($data);
    }

    public function developer_docs()
    {
        $data = [
            'pageTitle' => 'Developer Option',
            'merchantToken' => PersonalAccessToken::where('tokenable_type', 'App\Models\Merchant')
                ->where('tokenable_id', Auth::guard('merchant')->user()->id)
                ->orderBy('id', 'desc')
                ->first(),
        ];

        return view('merchant.developer.developer_docs')->with($data);
    }

    /**
     * GEnumerate Developer APi Token
     * @return RedirectResponse
     */
    public function apiKeyGenerate(): RedirectResponse
    {
        $user = Auth::guard('merchant')->user();
        $merchant = MerchantPvtPublicKey::where('merchant_id', $user->id)
            ->count();
        if ($merchant == 0) {
            $keyArray = generateSecretAndPublicKey($user->id);
            MerchantPvtPublicKey::create([
                'merchant_id' => $user->id,
                'api_key' => $keyArray['key'],
                'secret_key' => $keyArray['secret'],
            ]);
            return redirect()
                ->route('merchant.developer-index');
        }else{
            MerchantPvtPublicKey::where('merchant_id',$user->id)->delete();

            $keyArray = generateSecretAndPublicKey($user->id);
            MerchantPvtPublicKey::create([
                'merchant_id' => $user->id,
                'api_key' => $keyArray['key'],
                'secret_key' => $keyArray['secret'],
            ]);
            return redirect()
                ->route('merchant.developer-index');
        }
        return redirect()
            ->route('merchant.developer-index');

    }

    /**
     * Add a new IP address to the whitelist
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function addIpToWhitelist(Request $request): RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'ip_address' => 'required|string|max:45',
            'description' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->route('merchant.developer-index')
                ->withErrors($validator)
                ->withInput();
        }

        $merchantId = auth()->guard('merchant')->user()->id;

        // Check if IP already exists for this merchant
        $existingIp = MerchantIpWhitelist::where('merchant_id', $merchantId)
            ->where('ip_address', $request->ip_address)
            ->first();

        if ($existingIp) {
            return redirect()
                ->route('merchant.developer-index')
                ->with('error', 'This IP address is already in your whitelist.');
        }

        // Validate IP format (IPv4 or IPv6)
        $isValidIpv4 = filter_var($request->ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        $isValidIpv6 = filter_var($request->ip_address, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        $isCidr = strpos($request->ip_address, '/') !== false;

        if (!$isValidIpv4 && !$isValidIpv6 && !$isCidr) {
            return redirect()
                ->route('merchant.developer-index')
                ->with('error', 'Please enter a valid IPv4 or IPv6 address or CIDR range.');
        }

        // Create new whitelist entry
        MerchantIpWhitelist::create([
            'merchant_id' => $merchantId,
            'ip_address' => $request->ip_address,
            'description' => $request->description,
            'is_active' => true,
        ]);

        return redirect()
            ->route('merchant.developer-index')
            ->with('success', 'IP address added to whitelist successfully.');
    }

    /**
     * Toggle the active status of an IP whitelist entry
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function toggleIpStatus($id): RedirectResponse
    {
        $merchantId = auth()->guard('merchant')->user()->id;

        $ipEntry = MerchantIpWhitelist::where('id', $id)
            ->where('merchant_id', $merchantId)
            ->first();

        if (!$ipEntry) {
            return redirect()
                ->route('merchant.developer-index')
                ->with('error', 'IP whitelist entry not found.');
        }

        $ipEntry->is_active = !$ipEntry->is_active;
        $ipEntry->save();

        $status = $ipEntry->is_active ? 'enabled' : 'disabled';

        return redirect()
            ->route('merchant.developer-index')
            ->with('success', "IP address {$ipEntry->ip_address} {$status} successfully.");
    }

    /**
     * Delete an IP whitelist entry
     *
     * @param int $id
     * @return RedirectResponse
     */
    public function deleteIpFromWhitelist($id): RedirectResponse
    {
        $merchantId = auth()->guard('merchant')->user()->id;

        $ipEntry = MerchantIpWhitelist::where('id', $id)
            ->where('merchant_id', $merchantId)
            ->first();

        if (!$ipEntry) {
            return redirect()
                ->route('merchant.developer-index')
                ->with('error', 'IP whitelist entry not found.');
        }

        $ipEntry->delete();

        return redirect()
            ->route('merchant.developer-index')
            ->with('success', "IP address {$ipEntry->ip_address} removed from whitelist.");
    }
}
