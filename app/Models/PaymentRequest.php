<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id',
        'trxid',
        'amount',
        'merchant_id',
        'reference',
        'payment_method',
        'currency',
        'callback_url',
        'sim_id',
        'cust_name',
        'cust_phone',
        'cust_address',
        'checkout_items',
        'note',
        'ext_field_1',
        'ext_field_2',
        'issue_time',
        'agent',
        'dso',
        'partner',
        'modem_id',
        'device_id',
        'ip',
        'user_agent',
        'balance_updated',
        'payment_method_trx',
        'from_number',
        'sub_merchant',
        'status',
        'accepted_by',
        'token',
        'merchant_fee',
        'merchant_commission',
        'sub_merchant_fee',
        'sub_merchant_commission',
        'merchant_main_amount',
        'sub_merchant_main_amount',
        'merchant_balance_updated',
        'payment_type',
        'partner_fee',
        'partner_commission',
        'user_fee',
        'user_commission',
        'partner_main_amount',
        'user_main_amount',
        'webhook_url',
        'merchant_last_balance',
        'merchant_new_balance'


    ];

    /**
     * @return BelongsTo
     */
    public function merchant()
    {
        return $this->belongsTo(Merchant::class,'merchant_id','id');
    }

    /**
     * @return BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo(User::class,'agent','id')->withDefault();
    }

    /**
     * @return BelongsTo
     */
    public function dso()
    {
        return $this->belongsTo(User::class,'dso','id');
    }

    /**
     * @return BelongsTo
     */
    public function partner()
    {
        return $this->belongsTo(User::class,'partner','id');
    }




        public function subMerchant() {
            return $this->belongsTo(Merchant::class, 'sub_merchant');
        }

        public function customer() {
            return $this->belongsTo(Customer::class, 'customer_id');
        }

        public function balanceManager() {
            return $this->hasOne(BalanceManager::class, 'trxid', 'payment_method_trx');
        }

        public function sim() {
            return $this->belongsTo(PaymentMethod::class, 'sim_id', 'sim_id');
        }




}
