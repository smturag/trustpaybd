<?php

namespace App\Helpers;

class DeviceDetector
{
    /**
     * Get device information from user agent
     *
     * @param string|null $userAgent
     * @return array
     */
    public static function getDeviceInfo(?string $userAgent = null): array
    {
        $userAgent = $userAgent ?? request()->userAgent();

        return [
            'device' => self::getDevice($userAgent),
            'browser' => self::getBrowser($userAgent),
            'platform' => self::getPlatform($userAgent),
        ];
    }

    /**
     * Get device type
     *
     * @param string $userAgent
     * @return string
     */
    private static function getDevice(string $userAgent): string
    {
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*mobile))/i', $userAgent)) {
            return 'Tablet';
        }

        if (preg_match('/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/', $userAgent)) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    /**
     * Get browser name
     *
     * @param string $userAgent
     * @return string
     */
    private static function getBrowser(string $userAgent): string
    {
        $browsers = [
            'Edg' => 'Edge',
            'Edge' => 'Edge',
            'OPR' => 'Opera',
            'Opera' => 'Opera',
            'Chrome' => 'Chrome',
            'Safari' => 'Safari',
            'Firefox' => 'Firefox',
            'MSIE' => 'Internet Explorer',
            'Trident' => 'Internet Explorer',
        ];

        foreach ($browsers as $pattern => $browser) {
            if (stripos($userAgent, $pattern) !== false) {
                // Special handling for Safari (must come after Chrome check)
                if ($browser === 'Safari' && stripos($userAgent, 'Chrome') !== false) {
                    continue;
                }
                return $browser;
            }
        }

        return 'Unknown';
    }

    /**
     * Get platform/OS
     *
     * @param string $userAgent
     * @return string
     */
    private static function getPlatform(string $userAgent): string
    {
        $platforms = [
            'Windows NT 10.0' => 'Windows 10',
            'Windows NT 11.0' => 'Windows 11',
            'Windows NT 6.3' => 'Windows 8.1',
            'Windows NT 6.2' => 'Windows 8',
            'Windows NT 6.1' => 'Windows 7',
            'Windows NT 6.0' => 'Windows Vista',
            'Windows NT 5.1' => 'Windows XP',
            'Mac OS X' => 'Mac OS X',
            'Macintosh' => 'Mac OS',
            'iPhone' => 'iOS',
            'iPad' => 'iOS',
            'iPod' => 'iOS',
            'Android' => 'Android',
            'Linux' => 'Linux',
            'Ubuntu' => 'Ubuntu',
        ];

        foreach ($platforms as $pattern => $platform) {
            if (stripos($userAgent, $pattern) !== false) {
                // Extract version for Android
                if ($platform === 'Android') {
                    preg_match('/Android\s([0-9\.]+)/', $userAgent, $matches);
                    return isset($matches[1]) ? "Android {$matches[1]}" : 'Android';
                }
                
                // Extract version for iOS
                if ($platform === 'iOS') {
                    preg_match('/OS\s([0-9_]+)/', $userAgent, $matches);
                    if (isset($matches[1])) {
                        $version = str_replace('_', '.', $matches[1]);
                        return "iOS {$version}";
                    }
                    return 'iOS';
                }
                
                return $platform;
            }
        }

        return 'Unknown';
    }

    /**
     * Get country from IP address
     *
     * @param string|null $ip
     * @return array
     */
    public static function getCountryFromIp(?string $ip = null): array
    {
        $ip = $ip ?? request()->ip();

        // Skip for local IPs
        if (in_array($ip, ['127.0.0.1', '::1', 'localhost']) || 
            preg_match('/^192\.168\.|^10\.|^172\.(1[6-9]|2[0-9]|3[0-1])\./', $ip)) {
            return [
                'country' => 'Local',
                'country_code' => 'LC',
                'city' => 'Local',
            ];
        }

        try {
            // Using ip-api.com free API (150 requests per minute)
            $response = @file_get_contents("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city");
            
            if ($response) {
                $data = json_decode($response, true);
                
                if ($data && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'country_code' => $data['countryCode'] ?? 'XX',
                        'city' => $data['city'] ?? 'Unknown',
                    ];
                }
            }
        } catch (\Exception $e) {
            \Log::warning('Failed to get country from IP: ' . $e->getMessage());
        }

        return [
            'country' => 'Unknown',
            'country_code' => 'XX',
            'city' => 'Unknown',
        ];
    }

    /**
     * Get full device details including IP and country
     *
     * @return array
     */
    public static function getFullDeviceDetails(): array
    {
        $ip = request()->ip();
        $userAgent = request()->userAgent();
        $deviceInfo = self::getDeviceInfo($userAgent);
        $countryInfo = self::getCountryFromIp($ip);

        return array_merge(
            [
                'ip_address' => $ip,
                'user_agent' => $userAgent,
            ],
            $deviceInfo,
            $countryInfo
        );
    }
}
