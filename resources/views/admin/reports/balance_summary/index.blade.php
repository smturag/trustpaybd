@extends('admin.layouts.admin_app')
@section('title', 'Balance Summary')
@section('content')

<style>
    .summary-card {
        border-radius: 10px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .summary-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.1);
    }
    .stat-icon {
        font-size: 48px;
        opacity: 0.2;
        position: absolute;
        right: 20px;
        top: 20px;
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
    .expected-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
</style>

@if (session()->has('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="mb-0"><i class='bx bx-wallet'></i> Balance Summary Report</h4>
                @if($merchant)
                    <p class="text-muted mb-0">Merchant: <strong>{{ $merchant->fullname }}</strong></p>
                @else
                    <p class="text-muted mb-0">All Merchants</p>
                @endif
            </div>
        </div>

        <!-- Filter Form -->
        <form method="GET" action="{{ route('report.balance_summary') }}" class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Select Merchant</label>
                        <select name="selectMerchant" class="form-control">
                            <option value="">All Merchants</option>
                            @foreach (App\Models\Merchant::where('merchant_type','general')->get() as $m)
                                <option value="{{ $m->id }}" {{ $merchantId == $m->id ? 'selected' : '' }}>
                                    {{ $m->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Select Sub Merchant</label>
                        <select name="selectSubMerchant" class="form-control">
                            <option value="">All Sub Merchants</option>
                            @foreach (App\Models\Merchant::where('merchant_type','sub_merchant')->get() as $m)
                                <option value="{{ $m->id }}" {{ $subMerchantId == $m->id ? 'selected' : '' }}>
                                    {{ $m->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" class="form-control" value="{{ $startDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Date</label>
                        <input type="date" name="end_date" class="form-control" value="{{ $endDate }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Start Time</label>
                        <input type="time" name="start_time" class="form-control" value="{{ request('start_time', '00:00') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">End Time</label>
                        <input type="time" name="end_time" class="form-control" value="{{ request('end_time', '23:59') }}">
                    </div>
                    <div class="col-md-12 d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class='bx bx-search'></i> Filter
                        </button>
                        <a href="{{ route('report.balance_summary') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </div>
        </form>

        <!-- Main Statistics Cards -->
        <div class="row g-3 mb-4">
            <!-- Deposit Summary -->
            <div class="col-md-3">
                <div class="card summary-card deposit-card position-relative">
                    <div class="card-body">
                        <i class='bx bx-download stat-icon'></i>
                        <h5 class="mb-3"><i class='bx bx-plus-circle'></i> Deposits</h5>
                        <div class="mb-2">
                            <small>Total Transactions</small>
                            <h4 class="mb-0">{{ $depositStats->total_count ?? 0 }}</h4>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Amount:</span>
                            <strong>৳ {{ number_format($depositStats->total_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-danger">
                            <span>Fee:</span>
                            <strong>- ৳ {{ number_format($depositStats->total_fee ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Commission:</span>
                            <strong>+ ৳ {{ number_format($depositStats->total_commission ?? 0, 2) }}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <strong>Net:</strong>
                            <strong class="text-primary">৳ {{ number_format($depositNet, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdraw Summary -->
            <div class="col-md-3">
                <div class="card summary-card withdraw-card position-relative">
                    <div class="card-body">
                        <i class='bx bx-upload stat-icon'></i>
                        <h5 class="mb-3"><i class='bx bx-minus-circle'></i> Withdrawals</h5>
                        <div class="mb-2">
                            <small>Total Transactions</small>
                            <h4 class="mb-0">{{ $withdrawStats->total_count ?? 0 }}</h4>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Amount:</span>
                            <strong>৳ {{ number_format($withdrawStats->total_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-danger">
                            <span>Fee:</span>
                            <strong>- ৳ {{ number_format($withdrawStats->total_fee ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Commission:</span>
                            <strong>+ ৳ {{ number_format($withdrawStats->total_commission ?? 0, 2) }}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <strong>Net:</strong>
                            <strong class="text-danger">- ৳ {{ number_format($withdrawNet, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payout Summary -->
            <div class="col-md-3">
                <div class="card summary-card payout-card position-relative">
                    <div class="card-body">
                        <i class='bx bx-money stat-icon'></i>
                        <h5 class="mb-3"><i class='bx bx-dollar'></i> Payouts</h5>
                        <div class="mb-2">
                            <small>Total Requests</small>
                            <h4 class="mb-0">{{ $payoutStats->total_count ?? 0 }}</h4>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Total:</span>
                            <strong>৳ {{ number_format($payoutStats->total_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-warning">
                            <span>Pending:</span>
                            <strong>৳ {{ number_format($payoutStats->pending_amount ?? 0, 2) }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1 text-success">
                            <span>Approved:</span>
                            <strong>৳ {{ number_format($payoutStats->approved_amount ?? 0, 2) }}</strong>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between">
                            <strong>Net:</strong>
                            <strong class="text-danger">- ৳ {{ number_format($payoutNet, 2) }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expected Balance -->
            <div class="col-md-3">
                <div class="card summary-card expected-card position-relative">
                    <div class="card-body">
                        <i class='bx bx-calculator stat-icon'></i>
                        <h5 class="mb-3"><i class='bx bx-check-circle'></i> Expected Balance</h5>
                        <div class="mb-3">
                            <small class="opacity-75">Calculated from transactions</small>
                        </div>
                        <h2 class="mb-3">৳ {{ number_format($expectedBalance, 2) }}</h2>
                        <hr class="my-2 border-light">
                        <small class="opacity-75">
                            = Deposits ({{ number_format($depositNet, 2) }})<br>
                            - Withdrawals ({{ number_format($withdrawNet, 2) }})<br>
                            - Payouts ({{ number_format($payoutNet, 2) }})
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Method Breakdown Tables -->
        <div class="row g-3">
            <!-- Deposit by Method -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class='bx bx-download'></i> Deposit Breakdown by Method</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th class="text-end">Count</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($depositByMethod as $item)
                                <tr>
                                    <td>{{ $item->payment_method }}</td>
                                    <td class="text-end">{{ $item->count }}</td>
                                    <td class="text-end">৳ {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Withdraw by Method -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header bg-warning text-white">
                        <h6 class="mb-0"><i class='bx bx-upload'></i> Withdrawal Breakdown by Method</h6>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm mb-0">
                            <thead>
                                <tr>
                                    <th>Method</th>
                                    <th class="text-end">Count</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($withdrawByMethod as $item)
                                <tr>
                                    <td>{{ $item->payment_method }}</td>
                                    <td class="text-end">{{ $item->count }}</td>
                                    <td class="text-end">৳ {{ number_format($item->total, 2) }}</td>
                                </tr>
                                @empty
                                <tr><td colspan="3" class="text-center text-muted">No data</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection
