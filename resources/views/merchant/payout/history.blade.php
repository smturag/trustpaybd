@extends('merchant.mrc_app')
@section('title', 'Payout History')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
    <style>
        .status-badge {
            padding: 0.35rem 0.65rem;
            border-radius: 0.25rem;
            font-size: 0.875rem;
            font-weight: 500;
        }
        .table-responsive {
            border-radius: 0.5rem;
        }
    </style>
@endpush

@section('mrc_content')
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Payout</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('merchant_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Payout History</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('merchant.payout') }}" class="btn btn-primary">
                <i class='bx bx-plus-circle'></i> New Payout Request
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class='bx bx-wallet me-2'></i>Crypto Payout History</h5>
        </div>
        <div class="card-body">
            <!-- Filters -->
            <form method="GET" action="{{ route('merchant.payout-history') }}" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
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
                    <div class="col-md-3">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="text" name="start_date" id="start_date" class="form-control datepicker" 
                               value="{{ request('start_date') }}" placeholder="Select start date">
                    </div>
                    <div class="col-md-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="text" name="end_date" id="end_date" class="form-control datepicker" 
                               value="{{ request('end_date') }}" placeholder="Select end date">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class='bx bx-filter'></i> Filter
                        </button>
                        <a href="{{ route('merchant.payout-history') }}" class="btn btn-secondary">
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
                            <th>Payout ID</th>
                            <th>Date</th>
                            <th>Crypto / Network</th>
                            <th>Wallet Address</th>
                            <th>Amount</th>
                            <th>Balance Change</th>
                            <th>Net Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payouts as $payout)
                            <tr>
                                <td>
                                    <strong>{{ $payout->payout_id }}</strong>
                                </td>
                                <td>
                                    <div>{{ $payout->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $payout->created_at->format('h:i A') }}</small>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ strtoupper($payout->currency_name) }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $payout->network }}</small>
                                </td>
                                <td>
                                    <small class="font-monospace">{{ Str::limit($payout->wallet_address, 20, '...') }}</small>
                                </td>
                                <td>
                                    <strong>{{ money($payout->amount) }}</strong>
                                    <div><small class="text-danger">Fee: {{ money($payout->fee) }}</small></div>
                                </td>
                                <td>
                                    <div class="text-muted small">Old: {{ money($payout->old_balance) }}</div>
                                    <div class="text-primary fw-bold">New: {{ money($payout->new_balance) }}</div>
                                </td>
                                <td>
                                    <strong class="text-success">{{ money($payout->net_amount) }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $payout->status_color }}">
                                        {{ $payout->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('merchant.payout-details', $payout->id) }}" 
                                       class="btn btn-sm btn-outline-primary" title="View Details">
                                        <i class='bx bx-show'></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class='bx bx-info-circle fs-1 text-muted'></i>
                                    <p class="text-muted mb-0">No payout requests found</p>
                                    <a href="{{ route('merchant.payout') }}" class="btn btn-sm btn-primary mt-2">
                                        Create First Payout Request
                                    </a>
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

    <!-- Summary Cards -->
    @if($payouts->count() > 0)
    <div class="row mt-3">
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
    @endif
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
    $(document).ready(function() {
        // Initialize Flatpickr for date inputs
        flatpickr('.datepicker', {
            dateFormat: 'Y-m-d',
            allowInput: true
        });
    });
</script>
@endpush
