<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'price_type',
        'description',
        'features',
        'button_text',
        'button_link',
        'is_featured',
        'display_order',
        'status'
    ];

    protected $casts = [
        'features' => 'array',
        'is_featured' => 'boolean',
        'status' => 'boolean',
    ];

    /**
     * Get active pricing plans ordered by display_order
     */
    public static function getActivePlans()
    {
        return self::where('status', true)
            ->orderBy('display_order')
            ->get();
    }
}
