<?php

namespace App\Service\Backend;

use App\Models\PaymentRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PaymentRequestService
{
    public string $model = PaymentRequest::class;

    /**
     * @param string $orderBy
     * @param string $order
     * @return Builder|Model
     */
    public function getAllPaymentRequests(string $orderBy = 'id', string $order = 'desc')
    {
        return $this->model::with(['merchant','agent','dso','partner'])
            ->where('status',0)
            ->orderBy($orderBy, $order)
            ->get();
    }


    /**
     * @param string $trxid
     * @return mixed
     */
    public function getPaymentRequestByRequestid(string $request_id)
    {
        return $this->model::with(['merchant','agent','dso','partner'])
            ->where('request_id',$request_id)
            ->where('status',0)
            ->firstOrFail();
    }
    /**
     * @param string $trxid
     * @return mixed
     */
    public function getPaymentRequestByTrxid(string $trxid)
    {
        return $this->model::with(['merchant','agent','dso','partner'])
            ->where('trxid',$trxid)
            ->firstOrFail();
    }
}
