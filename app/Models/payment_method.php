<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class payment_method extends Model
{
    use HasFactory,Notifiable,HasApiTokens;
    protected $guarded = [];

    public function scopeGetList(Builder $query,$status){
        $query->where('status',$status);
    }
}
