@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

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
            <h6 class="mb-2 mb-sm-0 text-uppercase text-center text-sm-start ps-sm-3">Merchant List</h6>
            <a class="ms-sm-auto btn btn-sm btn-primary mt-2 mt-sm-0" href="{{ route('merchantAdd') }}">
                <i class="bx bx-plus me-1"></i> New Merchant
            </a>
        </div>
        <hr />

        @if ($errors->any())
        <div class="alert alert-danger">
            <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
        @endif

        <div class="card">
    <div class="card-body">

        @php
            $items = ($merchant->merchant_rate && $merchant->merchant_rate->count() > 0)
                     ? $merchant->merchant_rate
                     : \App\Models\MfsOperator::where('status', 1)->get();
        @endphp

        @foreach($items as $item)
            <h6 class="mb-2">{{ $item->name ?? 'N/A' }}</h6>

            @php
                // Assuming 'types' is an array or collection; adjust as per your data structure
                $types = $item->types ?? ['Deposit','Withdraw'];
            @endphp

            @foreach($types as $type)
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">{{ $type }} Fee </label>
                        <input type="text" class="form-control" value="{{ $item->{$type.'_field1'} ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">{{ $type }} Commission</label>
                        <input type="text" class="form-control" value="{{ $item->{$type.'_field2'} ?? '' }}">
                    </div>
                </div>
            @endforeach
        @endforeach

    </div>
</div>



    </div>
</div>

@endsection
