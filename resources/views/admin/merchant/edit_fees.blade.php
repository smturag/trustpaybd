@extends('admin.layouts.admin_app')
@section('title', 'Merchant Fees & Commissions')

@section('content')

@if(session('message'))
    <div class="alert alert-success" id="alert_success">{{ session('message') }}</div>
@endif
@if(session('alert'))
    <div class="alert alert-danger">{{ session('alert') }}</div>
@endif

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="header d-flex align-items-center mb-3 flex-column flex-sm-row">
            <h6 class="mb-2 mb-sm-0 text-uppercase text-center text-sm-start ps-sm-3">Merchant Fees & Commissions</h6>
            <a class="ms-sm-auto btn btn-sm btn-primary mt-2 mt-sm-0" href="{{ route('merchantList') }}">
                <i class="bx bx-left-arrow me-1"></i> Back to Merchants
            </a>
        </div>
        <hr/>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">

                <h5 class="mb-4">Merchant: {{ $merchant->fullname }} ({{ $merchant->username }})</h5>

                <!-- ✅ Set Defaults Button -->
                <button type="button" id="setDefaultsBtn" class="btn btn-info mb-3">
                    Set Default Operator Fees & Commissions
                </button>

                <form id="feesForm" action="{{ route('updateFees', $merchant->id) }}" method="POST">
                    @csrf

                    @foreach($operators as $operator)
                        <h6 class="mt-3 mb-2">{{ $operator->name }} <small class="text-muted">({{ $operator->type }})</small></h6>

                        @foreach(['deposit','withdraw'] as $action)
                            @php
                                $record = $merchant->merchant_rate->firstWhere(function($r) use($operator,$action){
                                    return $r->mfs_operator_id == $operator->id && $r->action == $action;
                                });

                                $defaultFee = $action == 'deposit' ? $operator->deposit_fee : $operator->withdraw_fee;
                                $defaultCommission = $action == 'deposit' ? $operator->deposit_commission : $operator->withdraw_commission;
                                $currentFee = $record->fee ?? '';
                                $currentCommission = $record->commission ?? '';
                            @endphp

                            <div class="row g-3 mb-3 align-items-center">
                                <div class="col-md-6">
                                    <label class="form-label text-capitalize">{{ $action }} Fee (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control fee-input"
                                               name="fees[{{ $operator->id }}][{{ $action }}][fee]"
                                               data-default="{{ $defaultFee }}"
                                               value="{{ $currentFee ?: $defaultFee }}">
                                        <span class="input-group-text text-muted">Default: {{ $defaultFee }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-capitalize">{{ $action }} Commission (%)</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control commission-input"
                                               name="fees[{{ $operator->id }}][{{ $action }}][commission]"
                                               data-default="{{ $defaultCommission }}"
                                               value="{{ $currentCommission ?: $defaultCommission }}">
                                        <span class="input-group-text text-muted">Default: {{ $defaultCommission }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endforeach

                    <button type="submit" class="btn btn-success mt-3">Save Fees & Commissions</button>
                </form>
            </div>
        </div>

    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function () {
    // ✅ Set Default Values Button
    $('#setDefaultsBtn').click(function () {
        $('.fee-input').each(function () {
            $(this).val($(this).data('default'));
        });
        $('.commission-input').each(function () {
            $(this).val($(this).data('default'));
        });
    });
});
</script>
@endpush
