@extends('merchant.mrc_app')
@section('title', 'Transaction')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet"/>
@endpush

@section('mrc_content')
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

    <div class="row">
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase">Mobile Banking List</h6>
                <hr/>
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

@endsection
