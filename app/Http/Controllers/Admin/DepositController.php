<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BalanceManager;
use App\Models\Customer;
use App\Models\User;
use App\Models\Merchant;
use App\Models\MfsOperator;
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
            $query = PaymentRequest::select(['id', 'request_id', 'trxid', 'merchant_id', 'sub_merchant', 'customer_id', 'payment_method', 'sim_id', 'payment_method_trx', 'cust_name', 'cust_phone', 'status', 'reference', 'payment_type', 'note', 'reject_msg', 'created_at', 'updated_at', 'accepted_by', 'from_number', 'amount', 'merchant_fee', 'merchant_commission', 'sub_merchant_fee', 'sub_merchant_commission', 'callback_url', 'webhook_url', 'merchant_last_balance', 'merchant_new_balance'])
                ->with(['merchant:id,fullname', 'subMerchant:id,fullname', 'customer:id,customer_name', 'balanceManager:trxid,mobile', 'sim:sim_id,type'])
                ->orderBy('id', 'desc'); // latest first by ID

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
            if ($request->payment_type) {
                $query->where('payment_type', $request->payment_type);
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
                } elseif ($request->status == 'success') {
                    $query->whereIn('status', [1, 2]);
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
                    $make_method = $row->payment_type;
                    $fromNumber = $row->balanceManager->mobile ?? $row->from_number ?? '-';

                    return "{$method}, {$simId} <br> {$make_method} <br> <small class='text-muted'>From: {$fromNumber}</small>";
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

                ->addColumn('fee_commission', function ($row) {
                    $fee = $row->sub_merchant ? $row->sub_merchant_fee : $row->merchant_fee;
                    $commission = $row->sub_merchant ? $row->sub_merchant_commission : $row->merchant_commission;
                    
                    $feeText = $fee !== null ? number_format($fee, 2) : '-';
                    $commissionText = $commission !== null ? number_format($commission, 2) : '-';
                    
                    return '<span class="badge bg-danger">' . $feeText . '</span> / <span class="badge bg-success">' . $commissionText . '</span>';
                })

                ->addColumn('balance_change', function ($row) {
                    $html = '';
                    if ($row->merchant_last_balance !== null) {
                        $html .= '<span class="badge bg-secondary">' . number_format($row->merchant_last_balance, 2) . '</span>';
                    } else {
                        $html .= '<span class="text-muted">-</span>';
                    }
                    
                    $html .= ' <i class="bx bx-right-arrow-alt"></i> ';
                    
                    if ($row->merchant_new_balance !== null) {
                        $html .= '<span class="badge bg-success">' . number_format($row->merchant_new_balance, 2) . '</span>';
                    } else {
                        $html .= '<span class="text-muted">-</span>';
                    }
                    
                    return $html;
                })

                ->addColumn('action', function ($row) {
                    $btn = '';

                    $name = 'N/A';
                    $designation = '-';
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
                    }

                    $statusText = match ($row->status) {
                        0 => 'Pending',
                        1 => 'Success',
                        2 => 'Approved',
                        3 => 'Rejected',
                        4 => 'Spam',
                        default => '-',
                    };

                    $fromNumber = $row->balanceManager->mobile ?? $row->from_number ?? '-';
                    $note = $row->note ?? ($row->reject_msg ?? '-');
                    $fee = $row->sub_merchant ? $row->sub_merchant_fee : $row->merchant_fee;
                    $commission = $row->sub_merchant ? $row->sub_merchant_commission : $row->merchant_commission;
                    $amount = $row->amount !== null ? number_format($row->amount, 2) : '-';
                    $feeText = $fee !== null ? number_format($fee, 2) : '-';
                    $commissionText = $commission !== null ? number_format($commission, 2) : '-';
                    $createdAt = $row->created_at ? $row->created_at->format('d-m-Y h:i:sa') : '-';
                    $updatedAt = $row->updated_at ? $row->updated_at->format('d-m-Y h:i:sa') : '-';
                    
                    // Build balance change HTML
                    $balanceChangeHtml = '';
                    if ($row->merchant_last_balance !== null) {
                        $balanceChangeHtml .= '<span class="badge bg-secondary">' . number_format($row->merchant_last_balance, 2) . '</span>';
                    } else {
                        $balanceChangeHtml .= '<span class="text-muted">-</span>';
                    }
                    $balanceChangeHtml .= ' <i class="bx bx-right-arrow-alt"></i> ';
                    if ($row->merchant_new_balance !== null) {
                        $balanceChangeHtml .= '<span class="badge bg-success">' . number_format($row->merchant_new_balance, 2) . '</span>';
                    } else {
                        $balanceChangeHtml .= '<span class="text-muted">-</span>';
                    }

                    $btn .=
                        '<button type="button"
                            class="viewPaymentBtn btn btn-sm btn-outline-primary"
                            style="padding: 2px 4px; font-size: 10px;"
                            title="View Details"
                            data-bs-toggle="tooltip"
                            data-payment-id="' . e($row->id) . '"
                            data-request-id="' . e($row->request_id ?? '-') . '"
                            data-trxid="' . e($row->trxid ?? '-') . '"
                            data-merchant-name="' . e($name) . '"
                            data-designation="' . e($designation) . '"
                            data-reference="' . e($row->reference ?? '-') . '"
                            data-payment-method="' . e($row->payment_method ?? '-') . '"
                            data-sim-id="' . e($row->sim_id ?? '-') . '"
                            data-payment-type="' . e($row->payment_type ?? '-') . '"
                            data-payment-trx="' . e($row->payment_method_trx ?? '-') . '"
                            data-amount="' . e($amount) . '"
                            data-fee="' . e($feeText) . '"
                            data-commission="' . e($commissionText) . '"
                            data-balance-change="' . e($balanceChangeHtml) . '"
                            data-from-number="' . e($fromNumber) . '"
                            data-note="' . e($note) . '"
                            data-status="' . e($statusText) . '"
                            data-accepted-by="' . e($row->accepted_by ?? '-') . '"
                            data-cust-name="' . e($row->cust_name ?? '-') . '"
                            data-cust-phone="' . e($row->cust_phone ?? '-') . '"
                            data-callback-url="' . e($row->callback_url ?? '-') . '"
                            data-webhook-url="' . e($row->webhook_url ?? '-') . '"
                            data-created-at="' . e($createdAt) . '"
                            data-updated-at="' . e($updatedAt) . '"
                        >
                            <i class="bx bx-show"></i>
                        </button><br>'; 

                    if ($row->status == 0 || $row->status == 4) {
                        $btn .=
                            '<a href="#" data-payment-id="' .
                            $row->id .
                            '" class="rejectPaymentBtn openPopup btn btn-sm btn-outline-danger" title="Reject Request" data-bs-toggle="tooltip">
                                    <i class="lni lni-cross-circle" aria-hidden="true"></i>
                                </a><br>';
                        $btn .=
                            '<button type="button" data-payment-id="' .
                            $row->id .
                            '" data-sim-id="' . e($row->sim_id ?? '') . 
                            '" data-payment-method="' . e($row->payment_method ?? '') . 
                            '" data-payment-type="' . e($row->payment_type ?? '') . 
                            '" data-payment-trx="' . e($row->payment_method_trx ?? '') . 
                            '" data-amount="' . e($row->amount ?? '') . 
                            '" class="spamPaymentBtn btn btn-sm btn-success" title="Approve Request" data-bs-toggle="tooltip">
                                    <i class="bx bx-check-double"></i> Approve
                                </button>';
                    } elseif ($row->status == 3) {
                        $btn .=
                            '<a href="' .
                            route('pending-payment-request', ['id' => $row->id]) .
                            '"
                                    class="pendingPaymentBtn openPopup btn btn-sm btn-outline-danger"
                                    onclick="return confirm(\'Are you sure you want to mark this payment as pending?\');"
                                    title="Mark as Pending"
                                    data-bs-toggle="tooltip">
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

                ->rawColumns(['merchant_name', 'status_html', 'action', 'payment_method', 'dates', 'balance_change', 'fee_commission'])
                ->make(true);
        }

        // Cache merchants list for 10 minutes
        $merchants = Cache::remember('merchants_list', 600, function () {
            return Merchant::orderBy('fullname')->get(['id', 'fullname', 'merchant_type']);
        });

        // Cache MFS operators list for 10 minutes
        $mfsOperators = Cache::remember('mfs_operators_list', 600, function () {
            return MfsOperator::where('status', 1)->orderBy('name')->get(['id', 'name', 'type']);
        });

        return view('admin.deposit.index', compact('merchants', 'mfsOperators'));
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
        // Validate input
        $request->validate([
            'payment_id' => 'required|exists:payment_requests,id',
            'mfs_operator_id' => 'required|exists:mfs_operators,id',
            'payment_method_trx' => 'required|string|max:255',
            'sim_id' => 'nullable|string|max:255',
            'amount' => 'nullable|numeric|min:0',
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

        // Get MFS Operator details
        $mfsOperator = MfsOperator::find($request->mfs_operator_id);
        
        if (!$mfsOperator) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Invalid MFS Operator',
                ],
                400,
            );
        }

        // Prevent duplicate transaction check
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

            // Save trx
            $payment->payment_method_trx = $request->payment_method_trx;
        }

        // Update payment method and type based on selected MFS operator
        $payment->payment_method = $mfsOperator->name;
        $payment->payment_type = $mfsOperator->type;
        
        // Update sim_id (if provided, update it; if empty, keep existing or clear based on request presence)
        if ($request->has('sim_id')) {
            $payment->sim_id = $request->sim_id ?: $payment->sim_id;
        }

        // Calculate fees and commissions based on amount
        $amount = $request->amount ? $request->amount : $payment->amount;
        
        if ($request->amount) {
            $payment->amount = $request->amount;
        }

        // Determine the correct merchant ID for rate calculation
        // If sub_merchant exists, use it; otherwise use merchant_id
        $rateMerchantId = $payment->sub_merchant ?: $payment->merchant_id;

        // Calculate merchant rate using the MFS operator
        // This will fetch merchant-specific fees from operator_fee_commissions table
        $merchantRate = calculateAmountFromRate(
            $mfsOperator->name, 
            $mfsOperator->type, 
            'deposit',
            $rateMerchantId, 
            $amount,
            $mfsOperator->id
        );
        
        // Calculate member rate for agent
        $currentAgent = User::where('member_code', $payment->agent)
            ->where('user_type', 'agent')
            ->first();
        
        // Only calculate member rates if agent exists
        if ($currentAgent) {
            $memberRate = calculateAmountFromRateForMember(
                $mfsOperator->name, 
                $mfsOperator->type, 
                'deposit', 
                $currentAgent->id, 
                $amount,
                $mfsOperator->id
            );
        } else {
            // Set default values when no agent exists
            $memberRate = [
                'member' => [
                    'fee_amount' => 0,
                    'commission_amount' => 0,
                    'net_amount' => 0
                ],
                'agent' => [
                    'fee_amount' => 0,
                    'commission_amount' => 0,
                    'net_amount' => 0
                ]
            ];
        }

        // Update merchant fees and commissions
        $payment->merchant_fee             = $merchantRate['general']['fee_amount'];
        $payment->merchant_commission      = $merchantRate['general']['commission_amount'];
        $payment->merchant_main_amount     = $merchantRate['general']['net_amount'];

        $payment->sub_merchant_fee         = $merchantRate['sub_merchant']['fee_amount'];
        $payment->sub_merchant_commission  = $merchantRate['sub_merchant']['commission_amount'];
        $payment->sub_merchant_main_amount = $merchantRate['sub_merchant']['net_amount'];

        // Update agent/member fees and commissions
        $payment->partner_fee              = $memberRate['member']['fee_amount'] ?? 0;
        $payment->partner_commission       = $memberRate['member']['commission_amount'] ?? 0;
        $payment->partner_main_amount      = $memberRate['member']['net_amount'] ?? 0;

        $payment->user_fee                 = $memberRate['agent']['fee_amount'] ?? 0;
        $payment->user_commission          = $memberRate['agent']['commission_amount'] ?? 0;
        $payment->user_main_amount         = $memberRate['agent']['net_amount'] ?? 0;

        // Mark payment as approved and track who approved
        $payment->status = 2; // approved
        $payment->accepted_by = auth()->guard('admin')->check() ? auth()->guard('admin')->user()->name : 'Admin';

        if ($payment->save()) {
            // Trigger balance handler to affect merchant/agent balances
            paymentRequestApprovedBalanceHandler($payment->id, 'id');

            // Trigger merchant webhook if needed
            merchantWebHook($payment->reference);

            return response()->json([
                'success' => true,
                'payment_id' => $payment->id,
                'message' => 'Payment approved successfully. Fees and commissions have been applied.',
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
