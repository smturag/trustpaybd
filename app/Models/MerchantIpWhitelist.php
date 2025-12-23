<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantIpWhitelist extends Model
{
    use HasFactory;

    protected $fillable = [
        'merchant_id',
        'ip_address',
        'description',
        'is_active'
    ];

    /**
     * Get the merchant that owns the IP whitelist entry.
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }
}
