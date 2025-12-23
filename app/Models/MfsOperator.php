<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class MfsOperator extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $guarded = [];

    public function scopeMfsList(Builder $query, $status){
   return $query->where('status', $status);
}

    public function payment_method()
    {
        return $this->hasMany(PaymentMethod::class,'mobile_banking','id');
    }

    public function fee_commissions()
{
    return $this->hasMany(OperatorFeeCommission::class);
}
}
