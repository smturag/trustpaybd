@extends('customer-panel.customer_app')
@section('title', 'Transaction')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush


@section('customer_content')
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

    <div class="row">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Betting Request</h5>
                    <form method="post" action="{{ route('customer.submit_betting') }}" class="row g-3">
                        @csrf

                        <div class="col-md-12">
                            <label for="balance">{{ translate('current_balance') }}</label>
                            <input type="text" class="form-control" id="balance" readonly value="{{ auth('customer')->user()->balance }}">
                        </div>

                        <div class="col-md-12">
                            <label for="account_type">{{ translate('Type') }}</label>

                            <select class="form-control" name="req_type" id="account_type" required="">
                                <option value="" selected>--Select--</option>
                                <option value="wf">Withdrwa</option>
                                <option value="add">Deposit</option>
                               
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label for="account_number" class="form-label">Customer ID</label>
                            <input type="text" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">Amount / Code </label>
                            <input type="text" onkeyup="notspace(this);" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
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


@push('js')
    <script>
        $(document).ready(function() {
            $('#payment_method').on('change', function (){
                var val = $(this).find('option:selected').text();

                $('label[for="account_number"]').text(val +" Number");
            });
        });
    </script>
@endpush