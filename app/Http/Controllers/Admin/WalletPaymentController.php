<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use App\Models\WalletPayment;
use Illuminate\Http\Request;


class WalletPaymentController extends Controller
{
    public function index(Request $request)
    {
        $sort_by = $request->get('sortby');
        $sort_by = $sort_by ?: 'id';

        $sort_type = $request->get('sorttype');
        $sort_type = $sort_type ?: 'desc';

        $rows = $request->get('rows');
        $rows = $rows ?: '50';


        $query_data = WalletPayment::with(['agent'])->where('created_at', '!=', null);

        if (!empty($request->get('merchant_id'))) {
            $query_data->where('merchant_id', '=', $request->get('merchant_id'));
        }

        if (!empty($request->get('trxid'))) {
            $query_data->where('trxid', '=', $request->get('trxid'));
        }

        if (!empty($request->get('reference'))) {
            // $query_data->where('reference', 'LIKE', '%' . $request->get('reference') . '%')
            //     ->orWhere('email', 'LIKE', '%' . $request->get('message') . '%')
            //     ->orWhere('mobile', 'LIKE', '%' . $request->get('message') . '%');
            $query_data->where('reference', '=', $request->get('reference'));

        }
        

        if (!empty($request->get('start_date')) && !empty($request->get('end_date'))) {
            $query_data->where('created_at', '>=', $request->get('start_date'));
            $query_data->where('created_at', '<=', $request->get('end_date'));
        }

        $query_data->orderBy($sort_by, $sort_type);
        $data = $query_data->paginate($rows);

        if ($request->ajax()) {

            return view('admin.merchant.payment-request.data', compact('data'));
        }

        $data = [
            'data' => $data,
            'merchants' => Merchant::orderBy('fullname')->get(),
        ];

//return $data['data'];

        return view('admin.merchant.payment-request.request-list')->with($data);
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Foundation\Application
     */
    public function approved_payment_request(Request $request, $id)
    {
        $request_data = WalletPayment::with(['merchant'])->where('id', $id)->first();

        return view('admin.merchant.payment-request.payment-request-approved', compact('request_data'));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Foundation\Application|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function reject_payment_request(Request $request, $id)
    {
        if ($request->ajax()) {
            WalletPayment::where('id', $id)->update(['status' => 3]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);

       
    }

    public function approve_payment_request(Request $request,$id)
    {
        if ($request->ajax()) {
            WalletPayment::where('id', $id)->update(['status' => 2]);

            return response()->json([
                'status' => 200,
                'message' => 'Successfully Status Changed',
            ]);
        }
        return response(['error' => 'Failed to Changed Status.', 'status' => 'failed']);
    }
}
