@extends('merchant.mrc_app')
@section('title', 'Balance Summary')
@section('mrc_content')

    <style>
        .summary-card {
            border-radius: 10px;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .summary-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
        }

        .stat-icon {
            font-size: 48px;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .balance-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .deposit-card {
            background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);
        }

        .withdraw-card {
            background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);
        }

        .payout-card {
            background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);
        }

        @media print {
            .no-print {
                display: none !important;
            }

            .summary-card {
                page-break-inside: avoid;
            }
        }
    </style>

    @if (session()->has('message'))
        <div class="alert alert-success no-print">{{ session('message') }}</div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4 no-print">
                <div>
                    <h4 class="mb-0"><i class='bx bx-wallet'></i> Balance Summary</h4>
                    <p class="text-muted mb-0">Merchant: <strong>{{ $merchant->fullname }}</strong></p>
                </div>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class='bx bx-printer'></i> Print Report
                </button>
            </div>

            <!-- Date Filter Form -->
            <form method="GET" action="{{ route('report.merchant.balance_summary') }}" class="card mb-4 no-print">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">End Date</label>
                            <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control"
                                value="{{ request('start_time', '00:00') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control"
                                value="{{ request('end_time', '23:59') }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class='bx bx-search'></i> Filter
                            </button>
                        </div>
                    </div>
                    @if($startDate || $endDate)
                        <div class="mt-2">
                            <span class="badge bg-info">
                                Filtered: {{ $startDate ? date('d M Y', strtotime($startDate)) : 'Start' }}
                                to {{ $endDate ? date('d M Y', strtotime($endDate)) : 'End' }}
                            </span>
                            <a href="{{ route('report.merchant.balance_summary') }}" class="badge bg-secondary">Clear Filter</a>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Current Balance Cards -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="card summary-card balance-card position-relative">
                        <div class="card-body">
                            <i class='bx bx-wallet stat-icon'></i>
                            <h6 class="mb-2 opacity-75">Current Available Balance</h6>
                            <h2 class="mb-0">৳ {{ number_format($merchant->available_balance ?? 0, 2) }}</h2>
                            <small class="opacity-75">Your legal balance for transactions</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card summary-card balance-card position-relative">
                        <div class="card-body">
                            <i class='bx bx-dollar-circle stat-icon'></i>
                            <h6 class="mb-2 opacity-75">Total Balance (with Network)</h6>
                            <h2 class="mb-0">৳ {{ number_format($merchant->balance ?? 0, 2) }}</h2>
                            <small class="opacity-75">Including sub-merchant network balance</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Statistics Cards -->
            <div class="row g-3 mb-4">
                <!-- Deposit Summary -->
                <div class="col-md-4">
                    <div class="card summary-card deposit-card position-relative">
                        <div class="card-body">
                            <i class='bx bx-plus-circle stat-icon'></i>
                            <h5 class="mb-3"><i class='bx bx-download'></i> Deposits</h5>
                            <div class="mb-2">
                                <small>Total Transactions</small>
                                <h4 class="mb-0">{{ $depositStats->total_count ?? 0 }}</h4>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Gross Amount:</span>
                                <strong>৳ {{ number_format($depositStats->total_amount ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-danger">
                                <span>Total Fee:</span>
                                <strong>- ৳ {{ number_format($depositStats->total_fee ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1 text-success">
                                <span>Commission:</span>
                                <strong>+ ৳ {{ number_format($depositStats->total_commission ?? 0, 2) }}</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <strong>Net Received:</strong>
                                <strong class="text-primary">৳ {{ number_format($depositNet, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Withdraw Summary -->
                <div class="col-md-4">
                    <div class="card summary-card withdraw-card position-relative">
                        <div class="card-body">
                            <i class='bx bx-minus-circle stat-icon'></i>
                            <h5 class="mb-3"><i class='bx bx-upload'></i> Withdrawals</h5>
                            <div class="mb-2">
                                <small>Total Transactions</small>
                                <h4 class="mb-0">{{ $withdrawStats->total_count ?? 0 }}</h4>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Total Amount:</span>
                                <strong>৳ {{ number_format($withdrawStats->total_amount ?? 0, 2) }}</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <strong>Total Sent:</strong>
                                <strong class="text-danger">৳ {{ number_format($withdrawNet, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payout Summary -->
                <div class="col-md-4">
                    <div class="card summary-card payout-card position-relative">
                        <div class="card-body">
                            <i class='bx bx-transfer stat-icon'></i>
                            <h5 class="mb-3"><i class='bx bx-money'></i> Payouts</h5>
                            <div class="mb-2">
                                <small>Total Requests</small>
                                <h4 class="mb-0">{{ $payoutStats->total_count ?? 0 }}</h4>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between mb-1">
                                <span>Pending:</span>
                                <strong class="text-warning">৳
                                    {{ number_format($payoutStats->pending_amount ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Approved:</span>
                                <strong class="text-success">৳
                                    {{ number_format($payoutStats->approved_amount ?? 0, 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Rejected:</span>
                                <strong class="text-danger">৳
                                    {{ number_format($payoutStats->rejected_amount ?? 0, 2) }}</strong>
                            </div>
                            <hr class="my-2">
                            <div class="d-flex justify-content-between">
                                <strong>Total Paid Out:</strong>
                                <strong class="text-danger">৳ {{ number_format($payoutNet, 2) }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Balance Calculation Card -->
            <div class="card mb-4 border-primary">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class='bx bx-calculator'></i> Balance Calculation</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr>
                                    <td><i class='bx bx-plus text-success'></i> Net Deposits Received</td>
                                    <td class="text-end"><strong>৳ {{ number_format($depositNet, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class='bx bx-minus text-danger'></i> Total Withdrawals Sent</td>
                                    <td class="text-end"><strong>- ৳ {{ number_format($withdrawNet, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td><i class='bx bx-minus text-danger'></i> Total Payouts Completed</td>
                                    <td class="text-end"><strong>- ৳ {{ number_format($payoutNet, 2) }}</strong></td>
                                </tr>
                                <tr class="border-top">
                                    <td><strong><i class='bx bx-wallet'></i> Expected Balance</strong></td>
                                    <td class="text-end">
                                        <h4 class="mb-0 text-{{ $expectedBalance >= 0 ? 'success' : 'danger' }}">
                                            ৳ {{ number_format($expectedBalance, 2) }}
                                        </h4>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Current Actual Balance</strong></td>
                                    <td class="text-end">
                                        <h4 class="mb-0 text-primary">৳
                                            {{ number_format($merchant->available_balance ?? 0, 2) }}</h4>
                                    </td>
                                </tr>
                                @php
                                    $difference = ($merchant->available_balance ?? 0) - $expectedBalance;
                                @endphp
                                <tr class="border-top">
                                    <td><strong>Difference</strong></td>
                                    <td class="text-end">
                                        <h5 class="mb-0 text-{{ abs($difference) < 0.01 ? 'success' : 'warning' }}">
                                            {{ $difference >= 0 ? '+' : '' }} ৳ {{ number_format($difference, 2) }}
                                            @if(abs($difference) < 0.01)
                                                <i class='bx bx-check-circle text-success'></i>
                                            @endif
                                        </h5>
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-4 d-flex align-items-center justify-content-center">
                            <div class="text-center">
                                @if(abs($difference) < 0.01)
                                    <i class='bx bx-check-circle text-success' style="font-size: 80px;"></i>
                                    <h6 class="text-success mt-2">Balance Matched!</h6>
                                @elseif($difference > 0)
                                    <i class='bx bx-info-circle text-info' style="font-size: 80px;"></i>
                                    <h6 class="text-info mt-2">Extra Balance</h6>
                                @else
                                    <i class='bx bx-error-circle text-warning' style="font-size: 80px;"></i>
                                    <h6 class="text-warning mt-2">Balance Deficit</h6>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Method Breakdown -->
            <div class="row g-3">
                <!-- Deposit by Method -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-chart'></i> Deposits by Method</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($depositByMethod as $method)
                                        <tr>
                                            <td><strong>{{ $method->payment_method }}</strong></td>
                                            <td class="text-end">{{ $method->count }}</td>
                                            <td class="text-end">৳ {{ number_format($method->total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Withdraw by Method -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-chart'></i> Withdrawals by Method</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Method</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($withdrawByMethod as $method)
                                        <tr>
                                            <td><strong>{{ $method->payment_method }}</strong></td>
                                            <td class="text-end">{{ $method->count }}</td>
                                            <td class="text-end">৳ {{ number_format($method->total, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Payout by Currency -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class='bx bx-chart'></i> Payouts by Currency</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Currency</th>
                                        <th>Status</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($payoutByCurrency as $currency)
                                        <tr>
                                            <td><strong>{{ strtoupper($currency->merchant_currency) }}</strong></td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $currency->status == 0 ? 'warning' : ($currency->status == 4 ? 'success' : 'danger') }}">
                                                    {{ $currency->status == 0 ? 'Pending' : ($currency->status == 4 ? 'Approved' : 'Rejected') }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                ৳ {{ number_format($currency->total_bdt, 2) }}
                                                <br>
                                                <small class="text-muted">{{ number_format($currency->total_currency, 2) }}
                                                    {{ strtoupper($currency->merchant_currency) }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3" class="text-center text-muted">No data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            @if($startDate)
                <!-- Statement Summary Header -->
                <div class="card mt-4 mb-3 border-primary">
                    <div class="card-header bg-primary text-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><i class='bx bx-file'></i> Account Statement</h5>
                            <div>
                                <span class="badge bg-light text-dark">Start: {{ date('d M Y', strtotime($startDate)) }}</span>
                                <span class="badge bg-light text-dark">End:
                                    {{ $endDate ? date('d M Y', strtotime($endDate)) : 'Today' }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Opening Balance:</strong></p>
                                <h4 class="text-primary">৳ {{ number_format($openingBalance, 2) }}</h4>
                            </div>
                            <div class="col-md-6 text-end">
                                <p class="mb-1"><strong>Closing Balance:</strong></p>
                                <h4 class="text-success">৳
                                    {{ number_format($statementList->count() > 0 ? $statementList->last()['balance'] : $openingBalance, 2) }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 7-Day Balance History -->
                @if($balanceHistory->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header bg-secondary text-white">
                            <h6 class="mb-0"><i class='bx bx-history'></i> Previous 7-Day Balance History</h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Date</th>
                                            <th class="text-end">Opening Balance</th>
                                            <th class="text-end">Closing Balance</th>
                                            <th class="text-end">Change</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($balanceHistory as $day)
                                            @php
                                                $change = $day['closing_balance'] - $day['opening_balance'];
                                            @endphp
                                            <tr>
                                                <td><strong>{{ date('d M Y (D)', strtotime($day['date'])) }}</strong></td>
                                                <td class="text-end">৳ {{ number_format($day['opening_balance'], 2) }}</td>
                                                <td class="text-end">৳ {{ number_format($day['closing_balance'], 2) }}</td>
                                                <td class="text-end text-{{ $change >= 0 ? 'success' : 'danger' }}">
                                                    {{ $change >= 0 ? '+' : '' }} ৳ {{ number_format($change, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

               
            @endif

            <!-- Print Footer -->
            <div class="text-center mt-4 d-none d-print-block">
                <hr>
                <p class="text-muted small">Generated on {{ date('d M Y, h:i A') }} | {{ config('app.name') }}</p>
            </div>
        </div>
    </div>

@endsection