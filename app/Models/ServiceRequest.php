<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function merchant()
    {
        return $this->belongsTo(Merchant::class, 'merchant_id', 'id')->withDefault();
    }

    public function user(){
        return $this->belongsTo(User::class, 'agent_id', 'id')->withDefault();
    }
}
