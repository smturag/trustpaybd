<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceManager;
use App\Models\Customer;
use App\Models\User;
use App\Models\Merchant;
use App\Models\PaymentRequest;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class DepositController extends Controller
{
    /**
     * Show payment requests with filters and DataTable server-side processing
     */
    public function index(Request $request)
    {
        // If it's an AJAX request (DataTables)
        if ($request->ajax()) {
            // Only select needed columns
            $query = PaymentRequest::select(['id', 'merchant_id', 'sub_merchant', 'customer_id', 'payment_method', 'sim_id', 'payment_method_trx', 'cust_name', 'cust_phone', 'status', 'reference', 'payment_type', 'note', 'reject_msg', 'created_at', 'updated_at', 'accepted_by', 'from_number', 'amount'])
                ->with(['merchant:id,fullname', 'subMerchant:id,fullname', 'customer:id,customer_name', 'balanceManager:trxid,mobile', 'sim:sim_id,type'])
                ->orderBy('created_at', 'desc'); // latest first

            // Filters
            if ($request->merchant_id) {
                $merchant = Merchant::find($request->merchant_id);
                if ($merchant) {
                    if ($merchant->merchant_type == 'general') {
                        $query->where('merchant_id', $merchant->id);
                    } else {
                        $query->where('sub_merchant', $merchant->id);
                    }
                }
            }

            if ($request->mfs) {
                $query->where('payment_method', $request->mfs);
            }
            if ($request->method_number) {
                $query->where('sim_id', $request->method_number);
            }
            if ($request->trxid) {
                $query->where('payment_method_trx', $request->trxid);
            }
            if ($request->cust_name) {
                $query->where(function ($q) use ($request) {
                    $q->where('cust_name', 'like', "%{$request->cust_name}%")->orWhere('cust_phone', 'like', "%{$request->cust_name}%");
                });
            }
            if ($request->status) {
                if ($request->status == 'pending') {
                    $query->where('status', 0);
                } else {
                    $query->where('status', $request->status);
                }
            }
            if ($request->reference) {
                $query->where('reference', $request->reference);
            }
            if ($request->start_date && $request->end_date) {
                $query->whereDate('created_at', '>=', $request->start_date)->whereDate('created_at', '<=', $request->end_date);
            }

            // Return DataTables response
            return DataTables::of($query)
                ->addIndexColumn()

                ->addColumn('merchant_name', function ($row) {
                    $designation = '';
                    $name = '';

                    if ($row->customer) {
                        $name = $row->customer->customer_name ?? $row->cust_name;
                        $designation = 'Customer';
                    } elseif ($row->subMerchant) {
                        $name = $row->subMerchant->fullname ?? 'N/A';
                        $designation = 'Sub Merchant';
                    } elseif ($row->merchant) {
                        $name = $row->merchant->fullname ?? 'N/A';
                        $designation = 'Merchant';
                    } else {
                        $name = $row->cust_name ?? 'N/A';
                        $designation = '-';
                    }

                    $reference = $row->reference ? "<br><span class='text-info'>{$row->reference}</span>" : '';

                    return "{$name} <br> <span class='text-success font-weight-bold'>{$designation}</span> {$reference}";
                })

                ->editColumn('payment_method', function ($row) {
                    $method = $row->payment_method;
                    $simId = $row->sim_id;
                    // $make_method = 'Cashout';

                    // if ($row->sim) {
                    //     if ($row->sim->type === 'agent') {
                    //         $make_method = 'Cashout';
                    //     } elseif ($row->sim->type === 'personal') {
                    //         $make_method = 'Send Money';
                    //     } elseif ($row->sim->type === 'customer') {
                    //         $make_method = 'Payment';
                    //     }
                    // }

                    // Override if P2C
                    // if ($row->payment_type === 'P2C') {
                    //     $make_method = 'Payment';
                    // }

                    $make_method = $row->payment_type;

                    return "{$method}, {$simId} <br> {$make_method}";
                })

                ->addColumn('from_number', function ($row) {
                    return $row->balanceManager->mobile ?? $row->from_number;
                })

                ->addColumn('note', function ($row) {
                    return $row->note ?? ($row->reject_msg ?? '-');
                })

                ->addColumn('status_html', function ($row) {
                    switch ($row->status) {
                        case 0:
                            return "<span class='badge badge-pill bg-warning text-white'>Pending</span>";
                        case 1:
                            return "<span class='badge badge-pill bg-success text-white'>
                                        <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i>Success
                                    </span>";
                        case 2:
                            $acceptedBy = $row->accepted_by ? "<br>{$row->accepted_by}" : '';
                            return "<span class='badge badge-pill bg-success text-white'>
                                        <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i>Approved
                                    </span>{$acceptedBy}";
                        case 3:
                            return "<span class='badge badge-pill bg-danger text-white'>Rejected</span>";
                        case 4:
                            return "<span class='badge badge-pill bg-danger text-white'>Spam</span>";
                        default:
                            return '-';
                    }
                })

                ->addColumn('amount', function ($row) {
                    return number_format($row->amount, 2);
                })

                ->addColumn('action', function ($row) {
                    $btn = '';

                    if ($row->status == 0 || $row->status == 4) {
                        $btn .=
                            '<a href="#" data-payment-id="' .
                            $row->id .
                            '" class="rejectPaymentBtn openPopup btn btn-sm btn-outline-danger">
                                    <i class="lni lni-cross-circle" aria-hidden="true"></i>
                                </a> ';
                        $btn .=
                            '<button type="button" data-payment-id="' .
                            $row->id .
                            '" class="spamPaymentBtn btn btn-sm btn-success">
                                    <i class="bx bx-check-double"></i> Approve
                                </button>';
                    } elseif ($row->status == 3) {
                        $btn .=
                            '<a href="' .
                            route('pending-payment-request', ['id' => $row->id]) .
                            '"
                                    class="pendingPaymentBtn openPopup btn btn-sm btn-outline-danger"
                                    onclick="return confirm(\'Are you sure you want to mark this payment as pending?\');"
                                    title="Mark as Pending">
                                    <i class="bx bx-hourglass" aria-hidden="true"></i>
                                </a>';
                    }

                    return $btn;
                })

                ->addColumn('dates', function ($row) {
                    $created = $row->created_at ? $row->created_at->format('h:i:sa, d-m-Y') : '-';
                    $createdAgo = $row->created_at ? Carbon::parse($row->created_at)->diffForHumans() : '-';

                    $updated = $row->updated_at ? $row->updated_at->format('h:i:sa, d-m-Y') : '-';
                    $updatedAgo = $row->updated_at ? Carbon::parse($row->updated_at)->diffForHumans() : '-';

                    return "
                        {$created} <br>
                        <span class='text-success font-weight-bold'>{$createdAgo}</span> <br>
                        {$updated} <br>
                        <span class='text-success font-weight-bold'>{$updatedAgo}</span>
                    ";
                })

                ->rawColumns(['merchant_name', 'status_html', 'action', 'payment_method', 'dates'])
                ->make(true);
        }

        // Cache merchants list for 10 minutes
        $merchants = Cache::remember('merchants_list', 600, function () {
            return Merchant::orderBy('fullname')->get(['id', 'fullname', 'merchant_type']);
        });

        return view('admin.deposit.index', compact('merchants'));
    }

    public function reject_deposit_request(Request $request)
    {
        $request->validate([
            'transId' => 'required|integer|exists:payment_requests,id',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $payment = PaymentRequest::findOrFail($request->transId);
            $payment->update([
                'status' => 3, // Rejected
                'reject_msg' => $request->reason,
                ''
            ]);


            if (function_exists('merchantWebHook')) {
                merchantWebHook($payment->reference);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Payment request rejected successfully.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Something went wrong: ' . $e->getMessage(),
            ]);
        }
    }

    public function approve_deposit_request(Request $request)
    {
        // Validate input — trx and amount are optional
        $request->validate([
            'payment_id' => 'required|exists:payment_requests,id',
            'payment_method_trx' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric',
        ]);

        // Find the payment request only if pending/spam
        $payment = PaymentRequest::where('id', $request->payment_id)
            ->whereIn('status', [0, 4]) // 0 = pending, 4 = spam
            ->first();

        if (!$payment) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Payment not found or not eligible for approval',
                ],
                404,
            );
        }

        if (!$payment->payment_method_trx && !$request->payment_method_trx) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'This payment has no transaction id',
                ],
                404,
            );
        }

        // Prevent duplicate transaction check ONLY if trx is provided
        if ($request->payment_method_trx) {
            $exists = PaymentRequest::where('payment_method_trx', $request->payment_method_trx)
                ->whereIn('status', [1, 2]) // 1 = success, 2 = approved
                ->first();

            if ($exists) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'This transaction is already approved',
                    ],
                    409,
                );
            }

            // Save trx if provided
            $payment->payment_method_trx = $request->payment_method_trx;
        }

        // Save amount if provided
        if ($request->amount) {
            $payment->amount = $request->amount;

            $merchantRate = calculateAmountFromRate($payment->payment_method, $payment->payment_type, 'deposit',$payment->merchant_id , $request->amount);
            $currentAgent = User::where('member_code',$payment->agent)->where('user_type','agent')->first();
            $memberRate = calculateAmountFromRateForMember($payment->payment_method, $payment->payment_type, 'deposit', $currentAgent->id, $request->amount);

            $payment->merchant_fee             = $merchantRate['general']['fee_amount'];
            $payment->merchant_commission      = $merchantRate['general']['commission_amount'];
            $payment->merchant_main_amount     = $merchantRate['general']['net_amount'];

            $payment->sub_merchant_fee         = $merchantRate['sub_merchant']['fee_amount'];
            $payment->sub_merchant_commission  = $merchantRate['sub_merchant']['commission_amount'];
            $payment->sub_merchant_main_amount = $merchantRate['sub_merchant']['net_amount'];

            $payment->partner_fee              = $memberRate['member']['fee_amount'];
            $payment->partner_commission       = $memberRate['member']['commission_amount'];
            $payment->partner_main_amount      = $memberRate['member']['net_amount'];

            $payment->user_fee                 = $memberRate['agent']['fee_amount'];
            $payment->user_commission          = $memberRate['agent']['commission_amount'];
            $payment->user_main_amount         = $memberRate['agent']['net_amount'];


        }



        // Mark payment as approved
        $payment->status = 2; // approved

        if ($payment->save()) {
            // Trigger merchant webhook if needed

            paymentRequestApprovedBalanceHandler($payment->id , 'id');

            merchantWebHook($payment->reference);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'message' => 'Payment approved successfully',
            ]);
        }

        return response()->json(
            [
                'success' => false,
                'message' => 'Failed to update payment status',
            ],
            500,
        );
    }
}
