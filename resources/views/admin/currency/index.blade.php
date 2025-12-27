@extends('admin.layouts.admin_app')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Currency Management</h1>
            <p class="text-muted mb-0">Manage exchange rates for multi-currency payouts (Base: BDT)</p>
        </div>
        <a href="{{ route('admin.currency.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Currency
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Currency Code</th>
                            <th>Currency Name</th>
                            <th>Symbol</th>
                            <th>Exchange Rate to BDT</th>
                            <th>1 BDT =</th>
                            <th>Fee %</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currencies as $currency)
                            <tr>
                                <td>
                                    <strong class="text-primary">{{ $currency->currency_code }}</strong>
                                    @if($currency->currency_code === 'BDT')
                                        <span class="badge bg-info text-dark ms-1">Base</span>
                                    @endif
                                </td>
                                <td>{{ $currency->currency_name }}</td>
                                <td>
                                    <span class="fs-5">{{ $currency->currency_symbol ?? '-' }}</span>
                                </td>
                                <td>
                                    <strong>{{ number_format($currency->exchange_rate_to_bdt, 6) }}</strong>
                                    <small class="text-muted d-block">
                                        1 {{ $currency->currency_code }} = {{ number_format($currency->exchange_rate_to_bdt, 2) }} BDT
                                    </small>
                                </td>
                                <td>
                                    @if($currency->currency_code !== 'BDT')
                                        <strong>{{ number_format(1 / $currency->exchange_rate_to_bdt, 6) }}</strong> {{ $currency->currency_code }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ number_format($currency->fee_percentage ?? 3.00, 2) }}%</span>
                                </td>
                                <td>
                                    @if($currency->status == 1)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $currency->updated_at->format('M d, Y') }}<br>
                                        {{ $currency->updated_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.currency.edit', $currency->id) }}" 
                                           class="btn btn-outline-primary">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        @if($currency->currency_code !== 'BDT')
                                            <form action="{{ route('admin.currency.destroy', $currency->id) }}" 
                                                  method="POST" 
                                                  onsubmit="return confirm('Are you sure you want to delete this currency?');"
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-coins fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No currencies found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="alert alert-info mt-4">
        <i class="fas fa-info-circle"></i>
        <strong>Note:</strong> 
        <ul class="mb-0 mt-2">
            <li>BDT is the base currency with a fixed exchange rate of 1.00</li>
            <li>All payout amounts are stored in BDT in the system</li>
            <li>Merchants can request payouts in their preferred currency</li>
            <li>Exchange rates are cached for 1 hour for optimal performance</li>
        </ul>
    </div>
</div>
@endsection
