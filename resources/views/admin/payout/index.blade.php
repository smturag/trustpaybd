@extends('admin.layouts.admin_app')
@section('title', 'Merchant Payout Requests')

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" id="alert_success">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('alert'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Merchant Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crypto Payout Requests</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">

        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-3">
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <h6 class="mb-0">Pending</h6>
                    <h4 class="mb-0">{{ $payouts->where('status', 0)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <h6 class="mb-0">Processing</h6>
                    <h4 class="mb-0">{{ $payouts->where('status', 1)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <h6 class="mb-0">Completed</h6>
                    <h4 class="mb-0">{{ $payouts->where('status', 4)->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-danger text-white">
                <div class="card-body">
                    <h6 class="mb-0">Rejected</h6>
                    <h4 class="mb-0">{{ $payouts->where('status', 3)->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class='bx bxl-bitcoin me-2'></i>Crypto Payout Requests</h5>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('admin.merchant-payout.index') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="merchant_id" class="form-label">Merchant</label>
                        <select name="merchant_id" id="merchant_id" class="form-select">
                            <option value="">All Merchants</option>
                            @foreach($merchants as $merchant)
                                <option value="{{ $merchant->id }}" {{ request('merchant_id') == $merchant->id ? 'selected' : '' }}>
                                    {{ $merchant->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="status" class="form-label">Status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="">All Status</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Pending</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Processing</option>
                            <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Approved</option>
                            <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Rejected</option>
                            <option value="4" {{ request('status') == '4' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="start_date" class="form-control" 
                               value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" name="end_date" id="end_date" class="form-control" 
                               value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class='bx bx-filter'></i> Filter
                        </button>
                        <a href="{{ route('admin.merchant-payout.index') }}" class="btn btn-secondary">
                            <i class='bx bx-reset'></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <!-- Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Payout ID</th>
                            <th>Date</th>
                            <th>Merchant</th>
                            <th>Crypto / Network</th>
                            <th>Amount</th>
                            <th>Balance Impact</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td>{{ $payout->id }}</td>
                                <td>
                                    <strong>{{ $payout->payout_id }}</strong>
                                </td>
                                <td>
                                    <div>{{ $payout->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $payout->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $payout->merchant->fullname ?? 'N/A' }}</strong>
                                    </div>
                                    @if($payout->subMerchant)
                                        <small class="text-muted">Sub: {{ $payout->subMerchant->fullname }}</small>
                                    @endif
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ strtoupper($payout->currency_name) }}</strong>
                                    </div>
                                    <small class="badge bg-info">{{ $payout->network }}</small>
                                </td>
                                <td>
                                    <strong>{{ money($payout->amount) }}</strong>
                                    <div><small class="text-danger">Fee: {{ money($payout->fee) }}</small></div>
                                    <div><small class="text-success">Net: {{ money($payout->net_amount) }}</small></div>
                                </td>
                                <td>
                                    <div class="text-muted small">Before: {{ money($payout->old_balance) }}</div>
                                    <div class="text-primary fw-bold">After: {{ money($payout->new_balance) }}</div>
                                    <div><small class="text-danger">-{{ money($payout->amount) }}</small></div>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payout->status_color }}">
                                        {{ $payout->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.merchant-payout.show', $payout->id) }}" 
                                           class="btn btn-sm btn-info" title="View Details">
                                            <i class='bx bx-show'></i>
                                        </a>
                                        @if($payout->status == 0)
                                            <a href="{{ route('admin.merchant-payout.approve-form', $payout->id) }}" 
                                               class="btn btn-sm btn-success" title="Approve">
                                                <i class='bx bx-check'></i>
                                            </a>
                                            <a href="{{ route('admin.merchant-payout.reject-form', $payout->id) }}" 
                                               class="btn btn-sm btn-danger" title="Reject">
                                                <i class='bx bx-x'></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class='bx bx-info-circle fs-1 text-muted'></i>
                                    <p class="text-muted mb-0">No payout requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($payouts->hasPages())
                <div class="mt-3">
                    {{ $payouts->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Auto-hide success message
        setTimeout(function() {
            $('#alert_success').fadeOut('slow');
        }, 3000);
    });
</script>
@endpush
