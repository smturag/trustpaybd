<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class PayoutSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        return Cache::remember("payout_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set setting value by key
     */
    public static function setValue($key, $value, $description = null)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
            ]
        );

        Cache::forget("payout_setting_{$key}");
        return $setting;
    }

    /**
     * Get crypto payout fee percentage
     */
    public static function getPayoutFeePercentage()
    {
        return (float) self::getValue('crypto_payout_fee_percentage', 1);
    }
}
