@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

    @php
    $total_modem = App\Models\Modem::where('db_status', 'live')->count();
    $total_agent = App\Models\User::where('db_status', 'live')->where('user_type', 'agent')->count();
    $total_trx_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
        ->whereDate('sms_time', now())
        ->count();
    $total_trx_amount_today = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])
        ->whereDate('sms_time', now())
        ->sum('amount');
    $total_trx = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->count();
    $total_trx_amount = App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->sum('amount');
    $total_pending = App\Models\BalanceManager::whereIn('status', [33, 55, 0])->count();
    $total_merchant = App\Models\Merchant::where('db_status', 'live')->count();

    $total_payment_request = App\Models\PaymentRequest::whereIn('status', [1, 2])->sum('amount');
    $total_payment_request_today = App\Models\PaymentRequest::whereIn('status', [1, 2])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_mfs_request = App\Models\ServiceRequest::whereIn('status', [2, 3])->sum('amount');
    $today_total_mfs_request = App\Models\ServiceRequest::whereIn('status', [2, 3])
        ->whereDate('created_at', now())
        ->sum('amount');

    $total_payment_request_transection = App\Models\PaymentRequest::count();
    if ($total_payment_request_transection) {
        $total_payments_complete_transection = round((App\Models\PaymentRequest::whereIn('status',[1,2])->count() * 100) / $total_payment_request_transection);
        $total_payments_pending_transection = round((App\Models\PaymentRequest::where('status', 0)->count() * 100) / $total_payment_request_transection);
        $total_payments_rejected_transection = round((App\Models\PaymentRequest::where('status', 3)->count() * 100) / $total_payment_request_transection);
    }

    $total_mfs_transection = App\Models\ServiceRequest::count();
    if ($total_mfs_transection) {
        $total_mfs_complete_transection = round((App\Models\ServiceRequest::whereIn('status', [2,3])->count() * 100) / $total_mfs_transection);
        $total_mfs_rejected_transection = round((App\Models\ServiceRequest::where('status', 4)->count() * 100) / $total_mfs_transection);
        $total_mfs_pending_transection = round((App\Models\ServiceRequest::where('status', 0)->count() * 100) / $total_mfs_transection);
    }
    @endphp

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
        {{-- Widget: Total Modem --}}
        <div class="group bg-gradient-to-br from-blue-50 via-blue-100 to-cyan-100 rounded-2xl p-5 border-l-4 border-blue-500 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-700 mb-1">Total Modem</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $total_modem }}</p>
                    <p class="text-xs text-blue-600 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        Only Active Modem
                    </p>
                </div>
            </div>
        </div>
                    <div>
                        <div class="fw-semibold mb-2">Total Agent</div>
                        <div class="fw-bold">{{ $total_agent }}</div>
                        <div class="widget-trend text-info"><i class='bx bx-user'></i> Only Agent User</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Widget: Total Merchant --}}
        <div class="col">
            <div class="card dashboard-card widget-merchant radius-10">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="widget-icon-bg"><i class='bx bxs-group'></i></div>
                    <div>
                        <div class="fw-semibold mb-2">Total Merchant</div>
                        <div class="fw-bold">{{DB::table('merchants')->count()}}</div>
                        <div class="widget-trend text-success"><i class='bx bx-user'></i> Only Merchant User</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Widget: Today Payment Request --}}
        <div class="col">
            <div class="card dashboard-card widget-payment-today radius-10">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="widget-icon-bg"><i class='bx bxs-wallet'></i></div>
                    <div>
                        <div class="fw-semibold mb-2">Today Payment Request</div>
                        <div class="fw-bold">৳{{ money($total_payment_request_today) }}</div>
                        <div class="widget-trend text-success"><i class='bx bx-check'></i> Only Success + Approved</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Widget: Today MFS Request --}}
        <div class="col">
            <div class="card dashboard-card widget-mfs-today radius-10">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="widget-icon-bg"><i class='bx bxs-user'></i></div>
                    <div>
                        <div class="fw-semibold mb-2">Today MFS Request</div>
                        <div class="fw-bold">৳{{ money($today_total_mfs_request) }}</div>
                        <div class="widget-trend text-primary"><i class='bx bx-check'></i> Only Success + Approved</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('admin.admin_chart')

    @php
    $modems = activeMfsApi(); // Example source
    $totalOnline = count($modems);
@endphp

<div class="container mt-4">
    <h5 class="mb-4" style="font-weight: bold; color: green;">
        <i class="fas fa-wifi"></i> Total Online Modems:
        <span class="badge bg-success" style="font-size: 18px;">{{ $totalOnline }}</span>
    </h5>

    <div class="row">
        @foreach($modems as $modem)
            @php
                $operator = strtolower($modem['type']);
                switch ($operator) {
                    case 'bkash':
                        $imagePath = 'payments/bkash.png';
                        break;
                    case 'nagad':
                        $imagePath = 'payments/nagad.png';
                        break;
                    case 'rocket':
                        $imagePath = 'payments/rocket.png';
                        break;
                    case 'upay':
                        $imagePath = 'payments/upay.png';
                        break;
                    default:
                        $imagePath = 'payments/default.png';
                }
            @endphp

            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="p-3 rounded text-white" style="background-color: #00c851;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div style="font-size: 1.2rem; font-weight: bold;">
                            {{ $modem['phone'] }}
                        </div>
                        <img src="{{ asset($imagePath) }}" alt="{{ $operator }}"
                            style="width: 40px; border-radius: 6px;">
                    </div>
                    <div style="font-size: 1rem; margin-top: 6px;">
                        {{ number_format($modem['balance'], 2) }} ৳
                    </div>
                    <div class="d-flex align-items-center mt-2">
                        <i class="fas fa-battery-three-quarters fa-lg me-2"></i>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>




    <div class="row mt-4">
        @if (count(activeModem()['online_modems']) > 0)
            <div class="col-12 mb-3">
                <h5 class="text-success fw-bold">
                    <i class='bx bx-wifi me-2'></i>Total Online Modems:
                    <span class="badge bg-success rounded-pill">{{ activeModem()['online_count'] }}</span>
                </h5>
            </div>


            @foreach (activeModem()['online_modems'] as $item)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card modem-card w-100 border-0 shadow-sm bg-success">
                        <div class="card-body text-white d-flex flex-column justify-content-between align-items-start">
                            <div class="w-100 d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="card-title fw-bold">{{ $item->sim_number }}</h5>
                                    @php
                                        $data = $item->modem_details;
                                        preg_match('/Charge: (\d+)/', $data, $matches);
                                        $charge = isset($matches[1]) ? $matches[1] : 0;
                                    @endphp
                                    @foreach (explode(',', $item->operator) as $operator)
                                        @php
                                            $data = DB::table('balance_managers')
                                                ->where('sender', $operator == 'rocket' ? '16216' : $operator  )
                                                ->where('sim', $item->sim_id)
                                                ->latest('created_at')
                                                ->first();
                                        @endphp
                                        <p class="card-title mb-2">{{ $data->lastbal ?? 0.0 }}</p>
                                    @endforeach
                                    @php
                                        // Calculate the width of the charge level rectangle based on the charge percentage
                                        $chargeWidth = ($charge / 100) * 38; // 38 is the max width for 100% charge
                                        $chargeColor = $charge > 20 ? '#FFFFFF' : '#ff5722'; // White if charge > 20%, otherwise red
                                        $strokeColor = '#FFFFFF'; // White stroke for better visibility in dark mode
                                    @endphp

                                    <div class="battery-icon mt-2">
                                        <svg width="50" height="25" viewBox="0 0 50 25" xmlns="http://www.w3.org/2000/svg">
                                            <rect x="1" y="4" width="42" height="17" fill="none" stroke="{{ $strokeColor }}" stroke-width="2" rx="3" ry="3"/>
                                            <rect x="43" y="8" width="5" height="9" fill="{{ $strokeColor }}"/>
                                            <rect x="3" y="6" width="{{ $chargeWidth }}" height="13" fill="{{ $chargeColor }}"/>
                                        </svg>
                                        <span class="ms-1 fw-bold">{{ $charge }}%</span>
                                    </div>
                                </div>
                                <div class="bg-white p-2 rounded d-flex flex-column" style="border-radius: 10px;">
                                    @foreach (explode(',', $item->operator) as $operator)
                                        @php
                                            $operator = trim($operator); // Trim any whitespace
                                            $imagePath = '';
                                            switch ($operator) {
                                                case 'bkash':
                                                    $imagePath = 'payments/bkash.png';
                                                    break;
                                                case 'nagad':
                                                    $imagePath = 'payments/nagad.png';
                                                    break;
                                                case 'rocket':
                                                    $imagePath = 'payments/rocket.png';
                                                    break;
                                                case 'upay':
                                                    $imagePath = 'payments/upay.png';
                                                    break;
                                                default:
                                                    $imagePath = 'payments/default.png'; // Fallback image
                                            }
                                        @endphp
                                        <img src="{{ asset($imagePath) }}" alt="{{ $operator }}"
                                            class="operator-logo" style="width: 40px; border-radius: 8px; margin-bottom: 8px;">
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="col-12">
                <div class="alert alert-danger text-center p-4 border-0 shadow-sm">
                    <i class='bx bx-wifi-off fs-1 mb-2'></i>
                    <h4 class="fw-bold"> No modems are currently online. </h4>
                </div>
            </div>
        @endif
    </div>


    <!--end row-->



    {{-- <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-0">Recent Transaction</h5>
                </div>

            </div>
            <hr/>
            <div class="table-responsive">
                <table class="table align-middle mb-0 table-bordered">
                    <thead class="table-light">
                    <tr>
                        <th>id</th>
                        <th scope="col" class="text-center">Sender</th>
                        <th scope="col">C Number</th>
                        <th scope="col">Oldbal</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Comm.</th>
                        <th scope="col">Lastbal</th>
                        <th scope="col">Trxid</th>
                        <th scope="col">A Number</th>
                        <th scope="col">Status</th>
                        <th scope="col">Date</th>
                    </tr>
                    </thead>
                    <tbody>

                    <?php $lasttrx = App\Models\BalanceManager::orderBy('id', 'desc')->take(10)->get(); ?>

                    @foreach ($lasttrx as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->sender }}</td>
                            <td>{{ $row->mobile }}</td>
                            <td class="text-end">{{ money($row->oldbal) }}</td>
                            <td class="text-end">{{ money($row->amount) }}</td>
                            <td>{{ money($row->commission) }}</td>
                            <td class="text-end">{{ money($row->lastbal) }}</td>
                            <td>{{ $row->trxid }}</td>
                            <td>{{ $row->sim }}</td>

                            @if ($row->status == 20 || $row->status == 22)
                                <td><span class='badge badge-pill bg-success'><i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Success</span></td>
                            @elseif($row->status == 33)
                                <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>
                            @elseif($row->status == 55)
                                <td><span class='badge badge-pill bg-danger text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Danger</span></td>
                            @elseif($row->status == 66)
                                <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                            @elseif($row->status == 77)
                                <td><span class='badge badge-pill bg-success text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Approved</span></td>
                            @else
                                <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
                            @endif

                            <td>
                                {{ $row->sms_time }}
                                <p class="text-success font-weight-bold">
                                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans(); ?>
                                </p>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div> --}}










@endsection

@push('js')
    <script src="/static/backend/plugins/apexcharts-bundle/js/apexcharts.min.js"></script>
@endpush
