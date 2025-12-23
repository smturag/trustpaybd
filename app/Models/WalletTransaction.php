<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'merchant_id',
        'user_id',
        'debit',
        'credit',
        'old_balance',
        'trxid',
        'agent_sim',
        'type',
        'status',
        'ip',
        'currency_name',
        'network',
        'deposit_address',
        'account_type',
        'account_number',
        'payment_method',
        'note',
        'service_request_id'
    ];
}
