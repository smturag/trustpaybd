<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McRequest extends Model
{
    use HasFactory;
    
     protected $table = 'mc_requests';
    protected $guarded = [];


    public function customer()
    {
        return $this->belongsTo(Customer::class,'user_id','id');
    }
}
