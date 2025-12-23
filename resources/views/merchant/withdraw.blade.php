@extends('merchant.mrc_app')
@section('title', 'Transaction')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush


@section('mrc_content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
    @endif

    @if (session()->has('alert'))
        <div class="alert alert-warning" id="alert_warning">
            {{ session('alert') }}
        </div>
    @endif

    @php
        $merchant = Auth::guard('merchant')->user();
        $availableBalance = $merchant->merchant_type === 'sub_merchant'
            ? $merchant->balance
            : $merchant->available_balance;
    @endphp


    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Withdraw Request</h5>
                    <form method="post" action="{{ route('merchant.withdraw-save') }}" class="row g-3">
                        @csrf

                        <div class="col-md-12">
                            <label for="balance">{{ translate('current_balance') }}</label>
                            <input type="text" class="form-control" id="balance" readonly value="{{ money($availableBalance) }}">
                        </div>

                        <div class="col-md-12">
                            <label for="currency_name">{{ translate('Method Name') }}</label>

                            <div class="logo-wrap">
                                <select name="mfs_operator" class="form-control" id="currency_name">
                                    <option value="">{{ translate('select') }}</option>

                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->name }}">{{ $method->name }}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>

                    {{--    <div class="col-md-12">
                            <label for="network">{{ translate('Network') }}</label>

                            <div class="logo-wrap">
                                <select name="network" class="form-control" id="network">
                                    <option value="">{{ translate('select') }}</option>

                                    @foreach($paymentMethods as $method)
                                        <option value="{{ $method->network }}">{{ $method->network }}</option>
                                     @endforeach
                                </select>
                            </div>
                        </div>--}}

                        <div class="col-md-12">
                           <label for="deposit_address" class="form-label">Withdraw Number</label>
                            <input type="text" class="form-control" id="deposit_address" name="withdraw_number" value="{{ old('withdraw_number') }}">
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" max="{{ $availableBalance }}" onkeyup="notspace(this);" class="form-control" id="amount" name="amount" value="{{ old('amount') }}">
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4 w-100">Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

@endsection
