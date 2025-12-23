@php
    use Carbon\Carbon;

    $time_check = time() - env('TRANSACTION_INPUT_TIME');

    $baseStatuses = ['on' => 'Dual On', 'off' => 'Dual Off'];
@endphp

<table id="modem_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
        <tr>
            <th>#</th>
            <th>Date</th>
            <th>Agent</th>
            <th>Deviceid</th>
            <th>Operator</th>
            <th>Sim</th>
            <th>Modem Details</th>
            <th>Operating Status</th>
            <th>Operator Service</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            @php
                $mdmsts = $row->up_time >= $time_check ? 'Online' : 'Offline';

                $operatingStatusLabels = [
                    0 => 'Dual Off',
                    1 => 'Only Cash In',
                    2 => 'Only Cash Out',
                    3 => 'Cash In + Cash Out',
                ];
                $status_name = $operatingStatusLabels[$row->operating_status] ?? 'Dual Off';

                $payment_methods_array = explode(',', $row->operator);
                $paymentStatuses = [];

                foreach ($payment_methods_array as $method) {
                    $method = trim($method);
                    if ($method !== '') {
                        $paymentStatuses[$method] = ucfirst($method);
                    }
                }

                $allStatuses = array_merge($baseStatuses, $paymentStatuses);

                $meke_operator_service = $allStatuses[$row->operator_service] ?? $row->operator_service;
            @endphp

            <tr id="{{ $row->id }}">
                <td>{{ $row->id }}</td>
                <td>
                    {{ date('d F Y', strtotime($row->created_at)) }}<br>
                    {{ date('h:i:s A', strtotime($row->created_at)) }}
                </td>
                <td>{{ $row->member_code }}</td>
                <td>{{ $row->deviceid }}</td>
                <td>{{ $row->operator }}</td>
                <td>
                    {{ $row->sim_number }} <br> Sim Number <br>
                    {{ $row->sim_id }} <br> Sim ID
                </td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    {{ $row->modem_details }}
                </td>

                {{-- Operating Status Dropdown --}}
                <td>
                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $status_name }}
                        </button>
                        {{--
                        <ul class="dropdown-menu">
                            @foreach ($operatingStatusLabels as $key => $label)
                                @if ($row->operating_status != $key)
                                    <li>
                                        <a class="dropdown-item"
                                           href="{{ route('admin.modem_operating_status', ['modem_id' => $row->id, 'status' => $key]) }}">
                                            {{ $label }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        --}}
                    </div>
                </td>

                {{-- Operator Service Dropdown --}}
                <td>
                    <div class="dropdown">
                        <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $meke_operator_service }}
                        </button>
                        {{--
                        <ul class="dropdown-menu">
                            @foreach ($allStatuses as $statusKey => $statusLabel)
                                @if ($row->operator_service != $statusKey && !empty($statusKey))
                                    <li>
                                        <a class="dropdown-item"
                                           onclick="return confirm('Are you sure want to change status?')"
                                           href="{{ route('admin.modem_operating_service_status', ['modem_id' => $row->id, 'status' => $statusKey]) }}">
                                            {{ $statusLabel }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        --}}
                    </div>
                </td>

                {{-- Online/Offline blinking badge --}}
                <td>
                    <span class="status-indicator {{ $mdmsts == 'Online' ? 'online' : 'offline' }}">
                        {{ $mdmsts }}
                    </span>
                </td>
            </tr>
        @endforeach

        @if ($data->isEmpty())
            <tr>
                <td colspan="11" class="text-center p-5">No Record Found.</td>
            </tr>
        @endif
    </tbody>
</table>

{{-- Pagination --}}
{!! $data->links('common.pagination-custom') !!}

{{-- Styles --}}
@push('styles')
<style>
    .status-indicator {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        color: white;
        animation-duration: 1s;
        animation-iteration-count: infinite;
        animation-timing-function: ease-in-out;
    }
    .online {
        background-color: #28a745;
        animation-name: blink-green;
    }
    .offline {
        background-color: #dc3545;
        animation-name: blink-red;
    }
    @keyframes blink-green {
        0%, 100% { background-color: #28a745; opacity: 1; }
        50% { background-color: #218838; opacity: 0.5; }
    }
    @keyframes blink-red {
        0%, 100% { background-color: #dc3545; opacity: 1; }
        50% { background-color: #c82333; opacity: 0.5; }
    }
</style>
@endpush

{{-- Bootstrap JS if not already included --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush
