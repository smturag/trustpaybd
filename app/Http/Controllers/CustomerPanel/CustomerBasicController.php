<?php

namespace App\Http\Controllers\CustomerPanel;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use Auth;

class CustomerBasicController extends Controller
{
    protected $my_auth;

    public function __construct()
    {
        $this->my_auth = auth('customer')->user()->id;
    }

    public function findCustomer($letter)
    {
        $customers = Customer::where(function ($query) use ($letter) {
            $query->where('email', 'like', "%{$letter}%")
                  ->orWhere('mobile', 'like', "%{$letter}%");
        })
        ->where('id', '!=', $this->my_auth)
        ->whereNotNull('email_verified_at')
        ->where('status', '=', 1)
        ->get();

        if (sizeof($customers) != 0) {
            return true;
        }
        return false;
    }

    public function check_amount($amount_qty)
    {
        $my_auth = auth('customer')->user()->id;
        $data = Customer::find($my_auth);
        $balance = $data->balance;

        if($balance>$amount_qty ||  $balance==$amount_qty ){
            return true;
        }

        return false;
    }

    public function check_customer($name){
        $data = Customer::where('email', '=' , $name )
        ->orWhere('mobile','=', $name)->first();

        if($data){
            return $data;
        }

        return false;

    }
}