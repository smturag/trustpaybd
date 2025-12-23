<?php

namespace App\Http\Middleware;

use App\Models\Merchant;
use App\Models\MerchantIpWhitelist;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\MerchantPvtPublicKey;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ApiKeyAuth2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    $authorization = $request->header('X-Authorization');
    $authorizationSecret = $request->header('X-Authorization-Secret');

    // Validate the API and secret keys
    $merchantKey = MerchantPvtPublicKey::where('api_key', $authorization)
                                     ->where('secret_key', $authorizationSecret)
                                     ->first();

    if (!empty($merchantKey)) {
        // Check IP whitelist if enabled
        $clientIp = $request->ip();

        // Get all active IP whitelist entries for this merchant
        $whitelistedIps = MerchantIpWhitelist::where('merchant_id', $merchantKey->merchant_id)
                                            ->where('is_active', true)
                                            ->pluck('ip_address')
                                            ->toArray();

        // If there are whitelist entries, verify the client IP is in the list
        if (!empty($whitelistedIps)) {
            $ipAllowed = false;

            foreach ($whitelistedIps as $whitelistedIp) {
                // Check for exact match (IPv4 or IPv6)
                if ($clientIp === $whitelistedIp) {
                    $ipAllowed = true;
                    break;
                }

                // Check for CIDR notation (e.g., 192.168.1.0/24)
                if (strpos($whitelistedIp, '/') !== false) {
                    if ($this->ipInRange($clientIp, $whitelistedIp)) {
                        $ipAllowed = true;
                        break;
                    }
                }
            }

            if (!$ipAllowed) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'IP address not whitelisted for API access',
                    'code' => 403
                ], 403);
            }
        }

        // If the merchant is valid and IP is allowed, retrieve merchant details
        $getMerchant = Merchant::find($merchantKey->merchant_id);
        Auth::login($getMerchant);
        return $next($request);
    }

    // If authorization fails, return an Unauthorized response
    return response()->json(['message' => 'Unauthorized'], 401);
}

/**
 * Check if an IP address is within a CIDR range
 *
 * @param string $ip The IP address to check
 * @param string $range The CIDR range (e.g., 192.168.1.0/24)
 * @return bool
 */
protected function ipInRange($ip, $range)
{
    // Handle IPv4 addresses
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
        if (strpos($range, '/') !== false) {
            list($subnet, $bits) = explode('/', $range);
            $ip = ip2long($ip);
            $subnet = ip2long($subnet);
            $mask = -1 << (32 - $bits);
            $subnet &= $mask;
            return ($ip & $mask) == $subnet;
        }
    }

    // Handle IPv6 addresses
    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
        if (strpos($range, '/') !== false) {
            list($subnet, $bits) = explode('/', $range);

            // Convert IP address to binary string
            $ipBin = $this->ipv6ToBinary($ip);
            $subnetBin = $this->ipv6ToBinary($subnet);

            // Compare the first $bits bits
            return substr($ipBin, 0, $bits) === substr($subnetBin, 0, $bits);
        }
    }

    return false;
}

/**
 * Convert an IPv6 address to a binary string
 *
 * @param string $ip The IPv6 address
 * @return string Binary representation
 */
protected function ipv6ToBinary($ip)
{
    $binary = '';
    $parts = explode(':', $ip);

    // Handle IPv6 shorthand notation
    if (count($parts) < 8) {
        $ip = str_replace('::', str_repeat(':0', 8 - count($parts) + 1), $ip);
        $parts = explode(':', $ip);
    }

    foreach ($parts as $part) {
        if ($part === '') {
            $part = '0';
        }
        $binary .= str_pad(base_convert($part, 16, 2), 16, '0', STR_PAD_LEFT);
    }

    return $binary;
}
}
