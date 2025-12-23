@extends('merchant.mrc_app')
@section('title', 'Dashboard')

@section('mrc_content')
    @push('css')
        <?php

        use Carbon\Carbon;

        $today = Carbon::today();
        $total_modem = App\Models\Modem::where('db_status', 'live')->count();
        $total_agent = App\Models\User::where('db_status', 'live')->where('user_type', 'agent')->count();
        $total_trx_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
            ->whereDate('sms_time', now())
            ->count();
        $total_trx_amount_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
            ->whereDate('sms_time', now())
            ->sum('amount');
        $total_trx = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->count();
        $total_trx_amount = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->sum('amount');
        $total_pending = App\Models\BalanceManager::whereIn('status', [33, 55, 0])->count();
        $total_merchant = App\Models\Merchant::where('db_status', 'live')->count();
        $username = auth('merchant')->user()->username;
        $total_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->sum('amount');
        $today_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->whereDate('sms_time', $today)
            ->sum('amount');

        $merchant = Auth::guard('merchant')->user();
        $dashboardBalance = $merchant->merchant_type === 'sub_merchant'
            ? $merchant->balance
            : $merchant->available_balance;

        if ($merchant->merchant_type == 'general') {

            $total_payment_request = App\Models\PaymentRequest::where('merchant_id', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('merchant_main_amount');

            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
            ->where('merchant_id', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('merchant_main_amount');

            $total_payment_request_transection = App\Models\PaymentRequest::where('merchant_id', $merchant->id)->count();

            $total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('merchant_main_amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('merchant_main_amount');

            $total_mfs_transection = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        } else {

            $total_payment_request = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('sub_merchant_main_amount');


            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
            ->where('sub_merchant', $merchant->id)
            ->whereIn('status', [1, 2])
            ->sum('sub_merchant_main_amount');


            $total_payment_request_transection = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)->count();
            $total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('sub_merchant_main_amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('sub_merchant_main_amount');
            $total_mfs_transection = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        }

        ?>
    @endpush

    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center mb-4">
                <div>
                    <h5 class="mb-0">Welcome, {{ $merchant->fullname }}</h5>
                    <p class="text-secondary mb-0 mt-1">Here's your payment gateway overview</p>
                </div>
                <div class="ms-auto">
                    <span class="badge bg-light-primary text-primary p-2">
                        <i class="bx bx-calendar me-1"></i> {{ now()->format('d M, Y') }}
                    </span>
                </div>
            </div>

            <!-- Main Dashboard Cards -->
            <div class="row g-3">
                <div class="col-md-6 col-lg-4 col-xl">
                    <div class="card h-100 radius-10 border-start border-3 border-success">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="widgets-icons-2 bg-gradient-success text-white me-3">
                                    <i class='bx bx-wallet'></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-secondary fw-bold">Available Balance</p>
                                    <h4 class="my-1 text-success">
                                        ৳{{ money($dashboardBalance) }}
                                    </h4>
                                    <p class="mb-0 font-13 text-success">
                                        <i class='bx bxs-up-arrow align-middle'></i> Available to use
                                    </p>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 85%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl">
                    <div class="card h-100 radius-10 border-start border-3 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="widgets-icons-2 bg-gradient-primary text-white me-3">
                                    <i class='bx bxs-credit-card'></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-secondary fw-bold">Deposit</p>
                                    <h4 class="my-1 text-primary">৳{{ money($total_payment_request) }}</h4>
                                    <p class="mb-0 font-13 text-primary">
                                        <i class='bx bx-trending-up align-middle'></i> Today: ৳{{ money($total_payment_request_today) }}
                                    </p>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-primary" role="progressbar" style="width: 75%"></div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <div class="flex-grow-1">
                                    <a href="{{ route('merchant.payment-request') }}" class="text-primary fw-bold font-13">
                                        <i class='bx bx-link-external'></i> View All ({{ $total_payment_request_transection }})
                                    </a>
                                </div>
                                <div>
                                    <span class="badge bg-light-primary text-primary">
                                        <i class='bx bx-check-circle'></i> Success
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4 col-xl">
                    <div class="card h-100 radius-10 border-start border-3 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="widgets-icons-2 bg-gradient-warning text-white me-3">
                                    <i class='bx bx-mobile-alt'></i>
                                </div>
                                <div>
                                    <p class="mb-0 text-secondary fw-bold">Withdraw</p>
                                    <h4 class="my-1 text-warning">৳{{ money($total_mfs_request) }}</h4>
                                    <p class="mb-0 font-13 text-warning">
                                        <i class='bx bx-trending-up align-middle'></i> Today: ৳{{ money($today_total_mfs_request) }}
                                    </p>
                                </div>
                            </div>
                            <div class="progress mt-3" style="height: 6px;">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 65%"></div>
                            </div>
                            <div class="d-flex align-items-center mt-3">
                                <div class="flex-grow-1">
                                    <a href="{{ route('merchant.service-request') }}" class="text-warning fw-bold font-13">
                                        <i class='bx bx-link-external'></i> View All ({{ $total_mfs_transection }})
                                    </a>
                                </div>
                                <div>
                                    <span class="badge bg-light-warning text-warning">
                                        <i class='bx bx-mobile'></i> Mobile Banking
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Transaction Stats -->
            <div class="row g-3 mt-3">
                <!-- Today Payment Request Card -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Today Payment Request</p>
                                    <h5 class="my-1">৳{{ money($total_payment_request_today) }}</h5>
                                    <span class="badge bg-light-primary text-primary font-12"><i class='bx bx-time align-middle'></i> {{ now()->format('d M Y') }}</span>
                                </div>
                                <div class="widgets-icons bg-light-primary text-primary ms-auto">
                                    <i class='bx bx-calendar-check'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Payment Request Card -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Payment Request</p>
                                    <h5 class="my-1">৳{{ money($total_payment_request) }}</h5>
                                    <span class="badge bg-light-primary text-primary font-12"><i class='bx bx-check-double align-middle'></i> Success + Approved ({{ $total_payment_request_transection }})</span>
                                </div>
                                <div class="widgets-icons bg-light-primary text-primary ms-auto">
                                    <i class='bx bxs-credit-card'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Payment Card -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Pending Payment</p>
                                    <h5 class="my-1">৳{{ money(getMerchantBalance($merchant->id)['totalPendingPayment']) }}</h5>
                                    <span class="badge bg-light-warning text-warning font-12"><i class='bx bx-loader-circle align-middle'></i> Pending</span>
                                </div>
                                <div class="widgets-icons bg-light-warning text-warning ms-auto">
                                    <i class='bx bx-time-five'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Receive Balance Card -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Receive Balance</p>
                                    <h5 class="my-1">৳{{ money(getMerchantBalance($merchant->id)['adminCreditAmount']) }}</h5>
                                    <span class="badge bg-light-success text-success font-12"><i class='bx bx-check-double align-middle'></i> Success + Approved</span>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto">
                                    <i class='bx bx-download'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Return Balance Card -->
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Return Balance</p>
                                    <h5 class="my-1">৳{{ money(getMerchantBalance($merchant->id)['adminDebitAmount']) }}</h5>
                                    <span class="badge bg-light-danger text-danger font-12"><i class='bx bx-check-double align-middle'></i> Success + Approved</span>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bx-upload'></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


                @php
    $modems = activeMfsApi(); // Example source
    $totalOnline = count($modems);
@endphp

<!--
<div class="container mt-4">
    <h5 class="mb-4" style="font-weight: bold; color: green;">
        <i class="fas fa-wifi"></i> Total Online Modems:
        <span class="badge bg-success" style="font-size: 18px;">{{ $totalOnline }}</span>
    </h5>

    <div class="row">
        @foreach($modems as $modem)
            @php
                $operator = strtolower($modem['type']);
                switch ($operator) {
                    case 'bkash':
                        $imagePath = 'payments/bkash.png';
                        break;
                    case 'nagad':
                        $imagePath = 'payments/nagad.png';
                        break;
                    case 'rocket':
                        $imagePath = 'payments/rocket.png';
                        break;
                    case 'upay':
                        $imagePath = 'payments/upay.png';
                        break;
                    default:
                        $imagePath = 'payments/default.png';
                }
            @endphp

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="p-3 rounded text-white" style="background-color: #00c851;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="font-size: 1.2rem; font-weight: bold;">
                            {{ $modem['phone'] }}
                        </div>
                        <img src="{{ asset($imagePath) }}" alt="{{ $operator }}"
                            style="width: 40px; border-radius: 6px;">
                    </div>
                    <div style="font-size: 1rem; margin-top: 6px;">
                        {{ number_format($modem['balance'], 2) }} ৳
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <i class="fas fa-battery-three-quarters fa-lg me-2"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
 -->

            <!-- Recent Transactions Section

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card radius-10">
                        <div class="card-header bg-transparent">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h6 class="mb-0 fw-bold"><i class='bx bx-history me-1'></i> Recent Transactions</h6>
                                    <p class="text-secondary mb-0 mt-1 font-13">Your latest payment activities</p>
                                </div>
                                <div class="ms-auto">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary active">All</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary">Payments</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary">MFS</button>
                                        <button type="button" class="btn btn-sm btn-outline-primary">Withdrawals</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="transaction-timeline p-3">

                            <div class="table-responsive">
                                <table class="table align-middle mb-0 table-hover">
                                    <thead class="table-light">
                                        <tr>
                                            <th>TRX ID</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            // Get recent transactions (combining payment requests and MFS)
                                            $recentPayments = App\Models\PaymentRequest::where('merchant_id', $merchant->id)
                                                ->orderBy('created_at', 'desc')
                                                ->take(3)
                                                ->get();

                                            $recentMfs = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                                                ->orderBy('created_at', 'desc')
                                                ->take(3)
                                                ->get();

                                            $recentWithdrawals = App\Models\WalletTransaction::where('merchant_id', $merchant->id)
                                                ->where('type', 'withdraw')
                                                ->orderBy('created_at', 'desc')
                                                ->take(2)
                                                ->get();
                                        @endphp


                                        @foreach($recentPayments as $payment)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="transaction-icon bg-light-primary text-primary">
                                                            <i class='bx bxs-credit-card'></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <p class="mb-0 font-14">{{ $payment->trxid }}</p>
                                                            <p class="mb-0 text-secondary font-13">Payment</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-primary text-primary">Gateway</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">৳{{ money($payment->amount) }}</span>
                                                </td>
                                                <td>
                                                    @if($payment->status == 'success')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($payment->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($payment->status == 'failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $payment->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $payment->created_at->format('d M, Y') }}
                                                        <p class="mb-0 text-secondary font-13">{{ $payment->created_at->format('h:i A') }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        @foreach($recentMfs as $mfs)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="transaction-icon bg-light-warning text-warning">
                                                            <i class='bx bx-mobile-alt'></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <p class="mb-0 font-14">{{ $mfs->trxid }}</p>
                                                            <p class="mb-0 text-secondary font-13">MFS</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-warning text-warning">Mobile Banking</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">৳{{ money($mfs->amount) }}</span>
                                                </td>
                                                <td>
                                                    @if($mfs->status == 'success')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($mfs->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($mfs->status == 'failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $mfs->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $mfs->created_at->format('d M, Y') }}
                                                        <p class="mb-0 text-secondary font-13">{{ $mfs->created_at->format('h:i A') }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach


                                        @foreach($recentWithdrawals as $withdrawal)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="transaction-icon bg-light-danger text-danger">
                                                            <i class='bx bx-money-withdraw'></i>
                                                        </div>
                                                        <div class="ms-2">
                                                            <p class="mb-0 font-14">{{ $withdrawal->trx }}</p>
                                                            <p class="mb-0 text-secondary font-13">Withdrawal</p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-light-danger text-danger">Withdraw</span>
                                                </td>
                                                <td>
                                                    <span class="fw-bold">৳{{ money($withdrawal->debit) }}</span>
                                                </td>
                                                <td>
                                                    @if($withdrawal->status == 'success')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($withdrawal->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($withdrawal->status == 'failed')
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $withdrawal->status }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ $withdrawal->created_at->format('d M, Y') }}
                                                        <p class="mb-0 text-secondary font-13">{{ $withdrawal->created_at->format('h:i A') }}</p>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="#" class="btn btn-sm btn-outline-primary">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-4 mb-2">
                                <a href="{{ route('merchant.payment-request') }}" class="btn btn-primary">
                                    <i class='bx bx-list-ul me-1'></i> View All Transactions
                                </a>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
        </div>
    </div>
    <!--end row-->

@endsection

@push('css')
<style>
    /* Enhanced card styling */
    .card {
        border: none;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border-radius: 15px;
        overflow: hidden;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Card header styling */
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1.2rem 1.5rem;
    }

    /* Gradient borders for cards */
    .border-success {
        border-left: 5px solid transparent !important;
        border-image: linear-gradient(to bottom, #28a745, #20c997) !important;
        border-image-slice: 1 !important;
    }

    .border-info {
        border-left: 5px solid transparent !important;
        border-image: linear-gradient(to bottom, #17a2b8, #0dcaf0) !important;
        border-image-slice: 1 !important;
    }

    .border-primary {
        border-left: 5px solid transparent !important;
        border-image: linear-gradient(to bottom, #0d6efd, #6610f2) !important;
        border-image-slice: 1 !important;
    }

    .border-warning {
        border-left: 5px solid transparent !important;
        border-image: linear-gradient(to bottom, #ffc107, #fd7e14) !important;
        border-image-slice: 1 !important;
    }

    .border-danger {
        border-left: 5px solid transparent !important;
        border-image: linear-gradient(to bottom, #dc3545, #c71f37) !important;
        border-image-slice: 1 !important;
    }

    /* Enhanced widget icons */
    .widgets-icons-2 {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 15px;
        font-size: 28px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        background-size: 200% auto;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
    }

    .widgets-icons-2::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: inherit;
        border-radius: inherit;
        opacity: 0.7;
        z-index: -1;
        transform: scale(0.9);
        transition: all 0.3s ease;
    }

    .card:hover .widgets-icons-2 {
        background-position: right center;
        transform: scale(1.05);
    }

    .card:hover .widgets-icons-2::before {
        transform: scale(1.2);
        opacity: 0.3;
    }

    .bg-gradient-success {
        background-image: linear-gradient(45deg, #28a745, #20c997);
    }

    .bg-gradient-info {
        background-image: linear-gradient(45deg, #17a2b8, #0dcaf0);
    }

    .bg-gradient-primary {
        background-image: linear-gradient(45deg, #0d6efd, #6610f2);
    }

    .bg-gradient-warning {
        background-image: linear-gradient(45deg, #ffc107, #fd7e14);
    }

    .bg-gradient-danger {
        background-image: linear-gradient(45deg, #dc3545, #c71f37);
    }

    /* Transaction icons */
    .transaction-icon {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    tr:hover .transaction-icon {
        transform: scale(1.1);
    }

    /* Progress bars */
    .progress {
        height: 8px !important;
        overflow: visible;
        background-color: rgba(0, 0, 0, 0.05);
        border-radius: 10px;
        margin-top: 15px;
    }

    .progress-bar {
        position: relative;
        border-radius: 10px;
        overflow: visible;
        background-size: 200% auto;
        animation: progress-animation 2s linear infinite;
    }

    @keyframes progress-animation {
        0% {
            background-position: 0% center;
        }
        50% {
            background-position: 100% center;
        }
        100% {
            background-position: 0% center;
        }
    }

    .progress-bar::after {
        content: '';
        position: absolute;
        right: 0;
        top: -4px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: inherit;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0% {
            transform: scale(1);
            opacity: 1;
        }
        50% {
            transform: scale(1.2);
            opacity: 0.8;
        }
        100% {
            transform: scale(1);
            opacity: 1;
        }
    }

    .bg-success {
        background-image: linear-gradient(45deg, #28a745, #20c997) !important;
    }

    .bg-info {
        background-image: linear-gradient(45deg, #17a2b8, #0dcaf0) !important;
    }

    .bg-primary {
        background-image: linear-gradient(45deg, #0d6efd, #6610f2) !important;
    }

    .bg-warning {
        background-image: linear-gradient(45deg, #ffc107, #fd7e14) !important;
    }

    .bg-danger {
        background-image: linear-gradient(45deg, #dc3545, #c71f37) !important;
    }

    .bg-purple {
        background-image: linear-gradient(45deg, #6f42c1, #8540c9) !important;
    }

    .bg-orange {
        background-image: linear-gradient(45deg, #fd7e14, #f96700) !important;
    }

    .bg-teal {
        background-image: linear-gradient(45deg, #20c997, #0fae81) !important;
    }

    /* Text colors */
    .text-purple {
        color: #6f42c1 !important;
    }

    .text-orange {
        color: #fd7e14 !important;
    }

    .text-teal {
        color: #20c997 !important;
    }

    /* Border colors */
    .border-purple {
        border-color: #6f42c1 !important;
        border-image: linear-gradient(to bottom, #6f42c1, #8540c9) !important;
        border-image-slice: 1 !important;
    }

    .border-orange {
        border-color: #fd7e14 !important;
        border-image: linear-gradient(to bottom, #fd7e14, #f96700) !important;
        border-image-slice: 1 !important;
    }

    .border-teal {
        border-color: #20c997 !important;
        border-image: linear-gradient(to bottom, #20c997, #0fae81) !important;
        border-image-slice: 1 !important;
    }

    /* Background light colors for badges */
    .bg-light-purple {
        background-color: rgba(111, 66, 193, 0.15) !important;
    }

    .bg-light-orange {
        background-color: rgba(253, 126, 20, 0.15) !important;
    }

    .bg-light-teal {
        background-color: rgba(32, 201, 151, 0.15) !important;
    }

    /* Gradient backgrounds */
    .bg-gradient-purple {
        background-image: linear-gradient(45deg, #6f42c1, #8540c9) !important;
    }

    .bg-gradient-orange {
        background-image: linear-gradient(45deg, #fd7e14, #f96700) !important;
    }

    .bg-gradient-teal {
        background-image: linear-gradient(45deg, #20c997, #0fae81) !important;
    }

    /* Enhanced text styling */
    .fw-bold {
        font-weight: 600 !important;
    }

    .text-secondary {
        color: #6c757d !important;
    }

    /* Badge styling */
    .badge {
        padding: 0.5em 0.8em;
        font-weight: 500;
        letter-spacing: 0.3px;
    }

    /* Table styling */
    .table-hover tbody tr {
        transition: all 0.2s ease;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(13, 110, 253, 0.05);
        transform: translateX(5px);
    }

    /* Button styling */
    .btn-outline-primary {
        border-width: 1.5px;
    }

    .btn-outline-primary:hover {
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    .btn-primary {
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.2);
    }

    /* Light background colors for badges and icons */
    .bg-light-primary {
        background-color: rgba(13, 110, 253, 0.1);
    }

    .bg-light-success {
        background-color: rgba(40, 167, 69, 0.1);
    }

    .bg-light-info {
        background-color: rgba(23, 162, 184, 0.1);
    }

    .bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
    }

    .bg-light-danger {
        background-color: rgba(220, 53, 69, 0.1);
    }
</style>
@endpush


@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(20px)';

                // Trigger reflow
                card.offsetHeight;

                // Apply animation
                card.style.transition = 'all 0.5s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add animation to progress bars
        const progressBars = document.querySelectorAll('.progress-bar');
        setTimeout(() => {
            progressBars.forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';

                // Trigger reflow
                bar.offsetHeight;

                // Apply animation
                bar.style.transition = 'width 1s ease-in-out';
                bar.style.width = width;
            });
        }, 500);

        // Make filter buttons functional
        const filterButtons = document.querySelectorAll('.btn-group .btn');
        const transactionRows = document.querySelectorAll('tbody tr');

        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                filterButtons.forEach(btn => btn.classList.remove('active'));

                // Add active class to clicked button
                this.classList.add('active');

                const filter = this.textContent.trim().toLowerCase();

                // Show/hide rows based on filter
                transactionRows.forEach(row => {
                    if (filter === 'all') {
                        row.style.display = '';
                    } else if (filter === 'payments') {
                        row.style.display = row.querySelector('.text-primary') ? '' : 'none';
                    } else if (filter === 'mfs') {
                        row.style.display = row.querySelector('.text-warning') ? '' : 'none';
                    } else if (filter === 'withdrawals') {
                        row.style.display = row.querySelector('.text-danger') ? '' : 'none';
                    }
                });
            });
        });
    });
</script>
@endpush
