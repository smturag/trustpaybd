<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PaymentMethod extends Model
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    public function scopeGetList(Builder $query, $status)
    {
        $query->where('status', $status);
    }

    public function mfs_operator()
    {
        return $this->belongsTo(MfsOperator::class, 'mobile_banking', 'id')->withDefault();
    }

    public function modem()
    {
        return $this->belongsTo(Modem::class, 'sim_id', 'sim_number')->withDefault();
    }
}
