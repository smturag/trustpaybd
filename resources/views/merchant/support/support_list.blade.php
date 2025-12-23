@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">{{ translate('My Tickets') }} List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('merchant.create_support_view') }}">
                    <i class="bx bx-plus mr-1"></i> New Ticket

                </a>
            </div>
            <hr />
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                <tr>
                                    <th> Ticket Id </th>
                                    <th> Subject </th>
                                    <th> Raised Time </th>
                                    <th> Status </th>
                                    <th> Action </th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($all_ticket as $key => $data)
                                    <tr>
                                        <td>{{ $data->ticket }}</td>
                                        <td><b>{{ $data->subject }}</b></td>
                                        <td>{{ \Carbon\Carbon::parse($data->created_at)->format('F dS, Y - h:i A') }}</td>
                                        <td>
                                            @if ($data->status == 1)
                                                <button class="btn btn-warning"> Opened</button>
                                            @elseif($data->status == 2)
                                                <button type="button" class="btn btn-success"> Answered </button>
                                            @elseif($data->status == 3)
                                                <button type="button" class="btn btn-info"> Customer Reply </button>
                                            @elseif($data->status == 9)
                                                <button type="button" class="btn btn-danger"> Closed </button>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data->status == 9)
                                                <button type="button" class="btn btn-danger"> Closed </button>
                                            @else
                                                <a class="btn btn-primary"
                                                    href="{{ route('merchant.ticket_customer_reply', $data->ticket) }}"><b>View</b></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{ $all_ticket->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
