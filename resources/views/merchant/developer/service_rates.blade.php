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
                                @php
                                    $record = $rates->firstWhere(function ($rate) use ($operator, $action) {
                                        return $rate->mfs_operator_id == $operator->id && $rate->action == $action;
                                    });

                                    $defaultFee = $action === 'deposit' ? $operator->deposit_fee : $operator->withdraw_fee;
                                    $defaultCommission = $action === 'deposit' ? $operator->deposit_commission : $operator->withdraw_commission;
                                    $fee = $record->fee ?? $defaultFee;
                                    $commission = $record->commission ?? $defaultCommission;
                                @endphp
                                <tr>
                                    <td>{{ $operator->name }}</td>
                                    <td>{{ ucfirst($operator->type) }}</td>
                                    <td>{{ ucfirst($action) }}</td>
                                    <td>{{ $fee }}</td>
                                    <td>{{ $commission }}</td>
                                </tr>
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
