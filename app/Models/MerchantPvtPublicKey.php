<?php

namespace App\Models;

use Ejarnutowski\LaravelApiKey\Models\ApiKey;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantPvtPublicKey extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the API key associated with this merchant key.
     */
    public function api_key()
    {
        return $this->belongsTo(ApiKey::class, 'api_key', 'key');
    }

    /**
     * Get the merchant that owns this API key.
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class);
    }

    /**
     * Get the IP whitelist entries for this merchant.
     */
    public function ipWhitelist()
    {
        return $this->hasMany(MerchantIpWhitelist::class, 'merchant_id', 'merchant_id');
    }
}
