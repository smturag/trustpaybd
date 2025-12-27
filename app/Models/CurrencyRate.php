<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class CurrencyRate extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'exchange_rate_to_bdt' => 'decimal:6',
        'fee_percentage' => 'decimal:2',
        'status' => 'integer',
    ];

    /**
     * Get exchange rate for a currency code
     */
    public static function getRate($currencyCode)
    {
        return Cache::remember("currency_rate_{$currencyCode}", 3600, function () use ($currencyCode) {
            $currency = self::where('currency_code', $currencyCode)
                ->where('status', 1)
                ->first();
            
            return $currency ? $currency->exchange_rate_to_bdt : 1;
        });
    }

    /**
     * Get fee percentage for a currency code
     */
    public static function getFee($currencyCode)
    {
        return Cache::remember("currency_fee_{$currencyCode}", 3600, function () use ($currencyCode) {
            $currency = self::where('currency_code', $currencyCode)
                ->where('status', 1)
                ->first();
            
            return $currency ? $currency->fee_percentage : 3.00;
        });
    }

    /**
     * Convert amount from one currency to BDT
     */
    public static function convertToBDT($amount, $fromCurrency)
    {
        if ($fromCurrency === 'BDT') {
            return $amount;
        }

        $rate = self::getRate($fromCurrency);
        return $amount * $rate;
    }

    /**
     * Convert amount from BDT to another currency
     */
    public static function convertFromBDT($amount, $toCurrency)
    {
        if ($toCurrency === 'BDT') {
            return $amount;
        }

        $rate = self::getRate($toCurrency);
        return $amount / $rate;
    }

    /**
     * Get all active currencies
     */
    public static function getActiveCurrencies()
    {
        return Cache::remember('active_currencies', 3600, function () {
            return self::where('status', 1)->orderBy('currency_code')->get();
        });
    }

    /**
     * Clear currency cache
     */
    public static function clearCache()
    {
        Cache::forget('active_currencies');
        $currencies = self::all();
        foreach ($currencies as $currency) {
            Cache::forget("currency_rate_{$currency->currency_code}");
            Cache::forget("currency_fee_{$currency->currency_code}");
        }
    }

    /**
     * Boot method to clear cache on update
     */
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }
}
