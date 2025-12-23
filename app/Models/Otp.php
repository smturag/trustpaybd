<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Otp extends Model
{
    protected $table = 'otps';

    protected $fillable = [

        'purpose',
        'purpose_id',
        'code',
        'sent',
        'expiration',
        'status'
    ];

}
