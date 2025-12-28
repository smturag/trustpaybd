@extends('merchant.mrc_app')
@section('title', 'Service Rates')

@section('mrc_content')
    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div>
                    <h5 class="mb-0">Service Rates</h5>
                    <p class="text-secondary mb-0 mt-1">Your current fee and commission rates for each operator.</p>
                </div>
            </div>

            <!-- Filter System -->
            <form method="GET" action="{{ route('merchant.developer.service-rates') }}" id="filterForm">
                <div class="row mb-4">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="operatorFilter" class="form-label">Operator</label>
                        <select class="form-select" id="operatorFilter" name="operator">
                            <option value="">All Operators</option>
                            @foreach($allOperators as $operator)
                                <option value="{{ $operator->name }}" {{ request('operator') == $operator->name ? 'selected' : '' }}>
                                    {{ $operator->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="typeFilter" class="form-label">Operator Type</label>
                        <select class="form-select" id="typeFilter" name="type">
                            <option value="">All Types</option>
                            @php
                                $types = $allOperators->pluck('type')->unique()->sort();
                            @endphp
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ request('type') == $type ? 'selected' : '' }}>
                                    {{ ucfirst($type) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label for="actionFilter" class="form-label">Action</label>
                        <select class="form-select" id="actionFilter" name="action">
                            <option value="">All Actions</option>
                            <option value="deposit" {{ request('action') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="withdraw" {{ request('action') == 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-filter-alt me-1"></i> Filter
                        </button>
                        <a href="{{ route('merchant.developer.service-rates') }}" class="btn btn-secondary">
                            <i class="bx bx-reset me-1"></i> Reset
                        </a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Operator</th>
                            <th>Type</th>
                            <th>Action</th>
                            <th>Fee (%)</th>
                            <th>Commission (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($operators as $operator)
                            @foreach(['deposit', 'withdraw'] as $action)
                                @if(!request('action') || request('action') == $action)
                                    @php
                                        $record = $rates->firstWhere(function ($rate) use ($operator, $action) {
                                            return $rate->mfs_operator_id == $operator->id && $rate->action == $action;
                                        });

                                        $defaultFee = $action === 'deposit' ? $operator->deposit_fee : $operator->withdraw_fee;
                                        $defaultCommission = $action === 'deposit' ? $operator->deposit_commission : $operator->withdraw_commission;
                                        $fee = $record->fee ?? $defaultFee;
                                        $commission = $record->commission ?? $defaultCommission;
                                    @endphp
                                    <tr data-operator="{{ $operator->name }}" data-type="{{ $operator->type }}" data-action="{{ $action }}">
                                        <td>{{ $operator->name }}</td>
                                        <td>{{ ucfirst($operator->type) }}</td>
                                        <td>{{ ucfirst($action) }}</td>
                                        <td>{{ $fee }}</td>
                                        <td>{{ $commission }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">No service rates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


@endsection
