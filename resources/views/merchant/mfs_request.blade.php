@extends('merchant.mrc_app')
@section('title', 'Transaction')

@section('mrc_content')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
 <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
@endpush



    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('merchant_dashboard') }}"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">Request Send</li>
                </ol>
            </nav>
        </div>

    </div>
    <!--end breadcrumb-->



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
        $merchant = auth('merchant')->user();
        $availableBalance = $merchant->merchant_type === 'sub_merchant'
            ? $merchant->balance
            : $merchant->available_balance;
    @endphp

    <div class="row">
        <div class="col-lg-3">


            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Send Request</h5>
                    <form method="post" action="{{ route('requestAction') }}" class="row g-3">
                        @csrf

                        <div class="col-md-12">
                            <label for="username">{{ translate('current_balance') }}</label>
                            <input type="text" class="form-control" id="balance" readonly
                                value="{{ money($availableBalance) }}">
                        </div>

                        <div class="col-md-12">
                            <label for="username">{{ translate('mfs') }}</label>
                            <select name="mfs" class="form-control" id="mfs">
                                <option value="">{{ translate('select') }}</option>
                                <option value="bKash">{{ translate('bKash') }}</option>
                                <option value="NAGAD">{{ translate('NAGAD') }}</option>
                                <option value="Rocket">{{ translate('Rocket') }}</option>
                                <option value="UPAY">{{ translate('UPAY') }}</option>
                            </select>

                        </div>


                        <div class="col-md-12">
                            <label for="input1" class="form-label">Number</label>
                            <input type="number" onkeyup="notspace(this);" class="form-control" id="number"
                                name="number" placeholder="{{ translate('enter') }} {{ translate('number') }}"
                                value="{{ old('number') }}">

                        </div>

                        <div class="col-md-12">
                            <label for="input1" class="form-label">Amount</label>
                            <input type="text" onkeyup="notspace(this);" class="form-control" id="amount"
                                name="amount" placeholder="{{ translate('enter') }} {{ translate('amount') }}"
                                value="{{ old('amount') }}">

                        </div>

                        <div class="col-md-12">
                            <label for="username">{{ translate('type') }}</label>
                            <select name="type" class="form-control" id="type">
                                <option value="">{{ translate('select') }}</option>
                                <option value="cashin">{{ translate('cash_in') }}</option>
                                <option value="cashout">{{ translate('cash_out') }}</option>

                            </select>

                        </div>



                        <div class="col-md-12">
                            <label for="pin_code">{{ translate('transaction_pin') }}</label>
                            <input type="password" class="form-control" id="pin_code" name="pin_code"
                                placeholder="{{ translate('enter') }} {{ translate('transaction_pin') }}">

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

        <div class="col-lg-9">
            <div class="card">

                <div class="card-body">
                    <h6 class="mb-0 text-uppercase">Last 10 Request of Mobile Banking</h6>
                    <hr />
                    <div class="table-responsive">
                        <table id="trade_list" class="table table-striped table-bordered mb-0">
                            <thead>
                                <tr>
                                    <th>{{ translate('sl') }}</th>
                                    <th>{{ translate('number') }}</th>
                                    <th>{{ translate('amount') }}</th>
                                    <th>{{ translate('mfs') }}</th>
                                    <th>{{ translate('type') }}</th>
                                    <th>{{ translate('trxid') }}</th>
                                    <th>{{ translate('date') }}</th>
                                    <th>{{ translate('status') }}</th>


                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_request as $drow)
                                    <?php

                                    if ($drow->status == 0) {
                                        $stsmsg = "<font color='black'>Panding</font>";
                                    } elseif ($drow->status == 2) {
                                        $stsmsg = "<font color='green'>Success</font>";
                                    } elseif ($drow->status == 3) {
                                        $stsmsg = "<font color='green'>Approved</font>";
                                    } elseif ($drow->status == 1) {
                                        $stsmsg = "<font color='blue'>Watting</font>";
                                    } elseif ($drow->status == 4) {
                                        $stsmsg = "<font color='red'>Fail</font>";
                                    }

                                    ?>
                                    <tr>
                                        <td class="text-truncate">{{ $drow->id }}</td>

                                        <td class="text-truncate"> {{ $drow->number }}</td>
                                        <td class="text-truncate"> {{ $drow->amount }}</td>
                                        <td class="text-truncate"> {{ $drow->mfs }}</td>
                                        <td class="text-truncate"> {{ $drow->type }}</td>

                                        <td class="text-truncate">{{ $drow->get_trxid }}</td>
                                        <td class="text-truncate">{{ $drow->created_at }}</td>
                                        <td class="text-truncate">{!! $stsmsg !!}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>



        </div>


    </div>



@endsection
