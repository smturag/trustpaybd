<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'user_id',
        'amount',
        'trxid',
        'status',
        'ip',
        'note',
    ];
}
