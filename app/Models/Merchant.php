<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Merchant extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'merchants';

    protected $guard_name = 'merchant';
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function merchantService(){
        $this->hasMany(MerchantService::class,'merchant_id','id');
    }

    public function merchantRate(){
        return $this->hasMany(OperatorFeeCommission::class,'merchant_id','id');
    }

    public function merchant_rate()
    {
        return $this->hasMany(OperatorFeeCommission::class);
    }
}
