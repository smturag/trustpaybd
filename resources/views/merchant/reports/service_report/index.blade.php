@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')

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

    /* Print Styles */
    @media print {
        @page {
            size: A4;
            margin: 15mm;
        }
        
        .no-print {
            display: none !important;
        }
        
        body * {
            visibility: hidden;
        }
        
        #printable-area, #printable-area * {
            visibility: visible;
        }
        
        #printable-area {
            position: absolute;
            left: 50%;
            top: 0;
            transform: translateX(-50%);
            width: 100%;
            max-width: 210mm;
        }
        
        .print-header {
            border-bottom: 3px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .print-header h3 {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #000;
        }
        
        .print-info {
            font-size: 12px;
            line-height: 1.6;
        }
        
        .print-info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        
        .card {
            border: 2px solid #000 !important;
            box-shadow: none !important;
            page-break-inside: avoid;
        }
        
        .card-header {
            background-color: #f0f0f0 !important;
            border-bottom: 2px solid #000 !important;
            padding: 12px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .card-header strong {
            font-size: 16px;
            color: #000;
        }
        
        .badge {
            border: 1px solid #000 !important;
            padding: 4px 8px !important;
            font-size: 11px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        table {
            page-break-inside: auto;
            border-collapse: collapse !important;
            width: 100%;
        }
        
        tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }
        
        thead {
            display: table-header-group;
        }
        
        thead th {
            background-color: #e0e0e0 !important;
            font-weight: bold;
            font-size: 13px;
            padding: 10px !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        tbody td {
            padding: 8px !important;
            font-size: 12px;
        }
        
        .table-bordered th,
        .table-bordered td {
            border: 1px solid #000 !important;
        }
        
        .bg-light {
            background-color: #f5f5f5 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .font-weight-bold,
        strong {
            font-weight: bold !important;
        }
        
        .text-danger {
            color: #000 !important;
        }
        
        .text-success {
            color: #000 !important;
        }
        
        tfoot tr,
        .grand-total-row {
            font-weight: bold;
            background-color: #e8e8e8 !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>

@if (session()->has('message'))
    <div class="alert alert-success no-print" id="alert_success">{{ session('message') }}</div>
@endif

@if (session()->has('alert'))
    <div class="alert alert-danger no-print">{{ session('alert') }}</div>
@endif

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="header d-flex align-items-center mb-3 no-print">
            <h6 class="mb-0 text-uppercase ps-3">Payment Request List</h6>
        </div>
        <hr class="no-print" />

        @if ($errors->any())
            <div class="alert alert-danger no-print">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- ✅ Start Form --}}
        <form method="get" id="search-form" class="mb-5 no-print" accept-charset="utf-8" role="form"
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

                {{-- Payment Type --}}
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="payment_type">Payment Type</label>
                        <select name="payment_type" class="form-control" id="payment_type">
                            <option value="All" {{ request('payment_type') == 'All' ? 'selected' : '' }}>All</option>
                            <option value="P2A" {{ request('payment_type') == 'P2A' ? 'selected' : '' }}>P2A</option>
                            <option value="P2C" {{ request('payment_type') == 'P2C' ? 'selected' : '' }}>P2C</option>
                            <option value="P2P" {{ request('payment_type') == 'P2P' ? 'selected' : '' }}>P2P</option>
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
        <div id="printable-area">
            {{-- Print Button and Filter Info --}}
            @if(count($results) > 0)
            <div class="row mb-3">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">{{ ucfirst(request()->route('service')) }} Report</h5>
                        </div>
                        <button type="button" class="btn btn-success no-print" onclick="window.print()">
                            <i class="bx bx-printer"></i> Print Report
                        </button>
                    </div>
                </div>
            </div>
            @endif

            {{-- Print Header --}}
            <div class="text-center mb-4 d-none d-print-block print-header">
                <h3>{{ config('app.name', 'TrustPay') }}</h3>
                <h4 style="margin: 5px 0; font-size: 18px;">Service Report</h4>
                <div class="print-info" style="margin-top: 15px;">
                    <div class="print-info-row">
                        <span><strong>Merchant:</strong> {{ Auth::guard('merchant')->user()->fullname ?? 'N/A' }}</span>
                        <span><strong>Generated:</strong> {{ date('d M Y, h:i A') }}</span>
                    </div>
                    <div class="print-info-row">
                        <span><strong>Service Type:</strong> {{ ucfirst(request()->route('service')) }}</span>
                        @if(request('payment_type') && request('payment_type') != 'All')
                            <span><strong>Payment Type:</strong> {{ request('payment_type') }}</span>
                        @endif
                    </div>
                    @if(request('method') && request('method') != 'All Method')
                    <div class="print-info-row">
                        <span><strong>Method:</strong> {{ request('method') }}</span>
                    </div>
                    @endif
                    @if(request('start_date') || request('end_date'))
                    <div class="print-info-row">
                        <span><strong>Period:</strong> 
                            {{ request('start_date') ? date('d M Y', strtotime(request('start_date'))) : 'Start' }}
                            @if(request('start_time')) {{ date('h:i A', strtotime(request('start_time'))) }} @endif
                            to 
                            {{ request('end_date') ? date('d M Y', strtotime(request('end_date'))) : 'End' }}
                            @if(request('end_time')) {{ date('h:i A', strtotime(request('end_time'))) }} @endif
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            @if(request()->route('service') == 'deposit')
                @include('merchant.reports.service_report.deposit_service', ['results' => $results, 'dateRange' => $dateRange ?? null])
            @elseif(request()->route('service') == 'withdraw')
                @include('merchant.reports.service_report.withdraw_service', ['results' => $results, 'dateRange' => $dateRange ?? null])
            @endif
        </div>

    </div>
</div>

{{-- ✅ JS to fix dynamic form action --}}
@push('js')
<script>
    document.getElementById('search-form').addEventListener('submit', function(e) {
        const selectedService = document.getElementById('selectService').value;
        this.action = `/merchant/report/service/${selectedService}`;
    });
</script>
@endpush


@endsection