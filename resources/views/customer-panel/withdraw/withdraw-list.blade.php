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
        <div class="card">
            <div class="card-body">
                <h6 class="mb-0 text-uppercase">Withdraw List</h6>
                <hr/>
                <div class="table-responsive">
                    <table id="trade_list" class="table table-striped table-bordered mb-0">
                        <thead>
                        <tr>
                            <th>{{ translate('sl') }}</th>
                            <th>{{ translate('type') }}</th>
                            <th>{{ translate('Payment Method') }}</th>
                            <th>{{ translate('Account Type') }}</th>
                            <th>{{ translate('Account Number') }}</th>
                            <th>{{ translate('trxid') }}</th>
                            <th>{{ translate('debit') }}</th>
                            <th>{{ translate('date') }}</th>
                            <th>{{ translate('status') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($all_request as $drow)
                            <?php
                            if ($drow->status == 0) {
                                $stsmsg = "<font color='black'>Pending</font>";
                            } elseif ($drow->status == 2) {
                                $stsmsg = "<font color='green'>Success</font>";
                            } elseif ($drow->status == 3) {
                                $stsmsg = "<font color='green'>Approved</font>";
                            } elseif ($drow->status == 1) {
                                $stsmsg = "<font color='blue'>Waiting</font>";
                            } elseif ($drow->status == 4) {
                                $stsmsg = "<font color='red'>Fail</font>";
                            }
                            ?>

                            <tr>
                                <td class="text-truncate">{{ $loop->iteration }}</td>
                                <td class="text-truncate">{{ $drow->type }}</td>
                                <td class="text-truncate">{{ $drow->payment_method }}</td>
                                <td class="text-truncate">{{ $drow->account_type }}</td>
                                <td class="text-truncate">{{ $drow->account_number }}</td>
                                <td class="text-truncate">{{ $drow->trxid }}</td>
                                <td class="text-truncate">{{ $drow->debit }}</td>
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
