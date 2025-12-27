<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MerchantPayoutRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'amount' => 'decimal:2',
        'fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'old_balance' => 'decimal:2',
        'new_balance' => 'decimal:2',
        'merchant_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'bdt_amount' => 'decimal:2',
        'approved_at' => 'datetime',
        'approval_documents' => 'array',
    ];

    public function merchantCurrency()
    {
        return $this->hasOne(CurrencyRate::class, 'currency_code', 'merchant_currency');
    }

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id')->withDefault();
    }

    public function subMerchant()
    {
        return $this->belongsTo(Merchant::class, 'sub_merchant', 'id')->withDefault();
    }

    public function cryptoCurrency()
    {
        return $this->belongsTo(CryptoCurrency::class, 'crypto_currency_id', 'id')->withDefault();
    }

    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by', 'id')->withDefault();
    }

    public function getStatusTextAttribute()
    {
        $statuses = [
            0 => 'Pending',
            1 => 'Processing',
            2 => 'Approved',
            3 => 'Rejected',
            4 => 'Completed'
        ];
        return $statuses[$this->status] ?? 'Unknown';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            0 => 'warning',
            1 => 'info',
            2 => 'success',
            3 => 'danger',
            4 => 'primary'
        ];
        return $colors[$this->status] ?? 'secondary';
    }
}
