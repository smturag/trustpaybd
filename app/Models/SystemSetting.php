<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SystemSetting extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get setting value by key
     */
    public static function getValue($key, $default = null)
    {
        return Cache::remember("system_setting_{$key}", 3600, function () use ($key, $default) {
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

        Cache::forget("system_setting_{$key}");
        return $setting;
    }

    /**
     * Check if maintenance mode is enabled
     */
    public static function isMaintenanceMode()
    {
        return (bool) self::getValue('maintenance_mode', 0);
    }

    /**
     * Enable maintenance mode
     */
    public static function enableMaintenanceMode()
    {
        return self::setValue('maintenance_mode', '1', 'System maintenance mode status');
    }

    /**
     * Disable maintenance mode
     */
    public static function disableMaintenanceMode()
    {
        return self::setValue('maintenance_mode', '0', 'System maintenance mode status');
    }
}
