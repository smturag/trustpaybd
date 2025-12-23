@extends('member.layout.member_app')
@section('title', 'Member List')

@section('member_content')

<style>
    .select2 {
        width: 200px;
    }

    @media screen and (min-width: 768px),
    screen and (min-width: 1200px) {
        .select2 {
            width: 70%;
        }
    }
</style>

@if (session()->has('message'))
    <div class="alert alert-success" id="alert_success">{{ session('message') }}</div>
@endif

@if (session()->has('alert'))
    <div class="alert alert-danger">{{ session('alert') }}</div>
@endif

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="header d-flex align-items-center mb-3">
            <h6 class="mb-0 text-uppercase ps-3">Payment Request List</h6>
        </div>
        <hr />

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Start Form --}}
        <form method="get" id="search-form" class="mb-5" accept-charset="utf-8" role="form"
              action="{{ url('admin/report/service/' . request()->route('service')) }}">
            <div class="row">

                {{-- Method --}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="method">Select Method</label>
                        <select name="method" class="form-control" id="method">
                            <option value="All Method">All Method</option>
                            @foreach($methods as $method)
                                <option value="{{ $method }}" {{ request('method') == $method ? 'selected' : '' }}>
                                    {{ $method }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Merchant --}}
              {{--  <div class="col-md-2">
                    <div class="form-group">
                        <label for="selectMerchant">Select Merchant</label>
                        <select class="form-control" name="selectMerchant" id="selectMerchant">
                            <option value="">Select Merchant</option>
                            @foreach (App\Models\Merchant::where('merchant_type','general')->get() as $merchant)
                                <option value="{{ $merchant->id }}" {{ request('selectMerchant') == $merchant->id ? 'selected' : '' }}>
                                    {{ $merchant->fullname }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div> --}}

                {{-- Date/Time --}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="start_time">Start Time</label>
                        <input type="time" class="form-control" name="start_time" value="{{ request('start_time') }}">
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="form-group">
                        <label for="end_time">End Time</label>
                        <input type="time" class="form-control" name="end_time" value="{{ request('end_time') }}">
                    </div>
                </div>

                {{-- ✅ Select Service --}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="selectService">Select Service</label>
                        <select class="form-control" name="service" id="selectService">
                            <option value="deposit" {{ request()->route('service') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                            <option value="withdraw" {{ request()->route('service') == 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                        </select>
                    </div>
                </div>

                {{-- ✅ Submit Button --}}
                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-group w-100">
                        <button type="submit" class="btn btn-primary w-100">Submit</button>
                    </div>
                </div>

            </div>
        </form>

        {{-- ✅ Result Section --}}
        @if(request()->route('service') == 'deposit')
            @include('member.reports.service_report.deposit_service', ['results' => $results])
        @elseif(request()->route('service') == 'withdraw')
            @include('member.reports.service_report.withdraw_service', ['results' => $results])
        @endif

    </div>
</div>

{{-- ✅ JS to fix dynamic form action --}}
@push('js')
<script>
    document.getElementById('search-form').addEventListener('submit', function(e) {
        const selectedService = document.getElementById('selectService').value;
        this.action = `service/${selectedService}`;
    });
</script>
@endpush


@endsection