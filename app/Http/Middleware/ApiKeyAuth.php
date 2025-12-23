<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use App\Models\MerchantPvtPublicKey;
use App\Models\MerchantIpWhitelist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('X-Authorization');
        $authorizationSecret = $request->header('X-Authorization-Secret');

        $merchantKey = MerchantPvtPublicKey::where('api_key', $authorization)
            ->where('secret_key', $authorizationSecret)
            ->first();

        if (!$merchantKey) {
            return response()->json(['message' => 'Unauthorized: Invalid API credentials.'], 401);
        }

        $merchantId = $merchantKey->merchant_id;
        $currentIP = $request->ip();

        // Get all whitelist entries for the merchant
        $ipWhitelists = MerchantIpWhitelist::where('merchant_id', $merchantId)->get();

        if ($ipWhitelists->isEmpty()) {
            // IP whitelist disabled, allow access
            return $next($request);
        }

        // Check if there is at least one active IP
        $activeWhitelists = $ipWhitelists->where('is_active', true);

        if ($activeWhitelists->isEmpty()) {
            // IP whitelist enabled, but no active entries = block
            return response()->json(['message' => 'Access denied: No active IP whitelist entries.'], 401);
        }

        // Check if the current IP is in the active whitelist
        $matched = $activeWhitelists->contains(function ($entry) use ($currentIP) {
            return $entry->ip_address === $currentIP;
        });

        if (!$matched) {
            return response()->json(['message' => 'Access denied: IP not allowed.'], 401);
        }

        return $next($request);
    }
}
