<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OperatorFeeCommission extends Model
{
    use HasFactory;

    protected $guarded;

    public function merchant(){
        return $this->belongsTo(Merchant::class,'merchant_id','id');
    }


    public function operator()
    {
        return $this->belongsTo(MfsOperator::class, 'mfs_operator_id');
    }
}
