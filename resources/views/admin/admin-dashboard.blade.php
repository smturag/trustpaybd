@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@push('css')
<style>
    /* Premium Glassmorphism & Modern Widget Card Styles */
    .dashboard-card {
        border-radius: 16px !important;
        box-shadow: 0 4px 24px rgba(0,140,255,0.10), 0 2px 8px rgba(30,90,62,0.06);
        border: none;
        background: rgba(255,255,255,0.82);
        backdrop-filter: blur(6px);
        position: relative;
        min-height: 70px;
        max-width: 100%;
        overflow: hidden;
        margin-bottom: 0.5rem;
        transition: transform 0.13s cubic-bezier(.4,0,.2,1), box-shadow 0.13s;
    }
    .dashboard-card:hover {
        transform: translateY(-2px) scale(1.025);
        box-shadow: 0 8px 32px rgba(0,140,255,0.16), 0 4px 16px rgba(30,90,62,0.10);
        z-index: 2;
    }
    .dashboard-card .card-body.p-4 {
        padding: 0.7rem 1rem !important;
        display: flex;
        align-items: center;
        gap: 0.7rem;
    }
    /* Unique icon backgrounds and accent borders for each card */
    .widget-icon-bg {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        margin-right: 0.6rem;
        box-shadow: 0 2px 8px rgba(0,140,255,0.13);
        border: 2.5px solid #fff;
        transition: box-shadow 0.13s;
    }
    .dashboard-card:hover .widget-icon-bg {
        box-shadow: 0 4px 16px rgba(0,140,255,0.18);
    }
    /* Vibrant gradients and bolder accent borders */
    .widget-modem { background: linear-gradient(135deg, #e3f2fd 60%, #90caf9 100%, #2196f3 120%); border-left: 5px solid #2196f3; }
    .widget-agent { background: linear-gradient(135deg, #fffde7 60%, #ffe082 100%, #ffc107 120%); border-left: 5px solid #ffc107; }
    .widget-merchant { background: linear-gradient(135deg, #e8f5e9 60%, #a5d6a7 100%, #43a047 120%); border-left: 5px solid #43a047; }
    .widget-payment-today { background: linear-gradient(135deg, #ffebee 60%, #ef9a9a 100%, #e53935 120%); border-left: 5px solid #e53935; }
    .widget-payment-total { background: linear-gradient(135deg, #e3f2fd 60%, #b3e5fc 100%, #0288d1 120%); border-left: 5px solid #0288d1; }
    .widget-payment-pending { background: linear-gradient(135deg, #f3e5f5 60%, #ce93d8 100%, #8e24aa 120%); border-left: 5px solid #8e24aa; }
    .widget-mfs-today { background: linear-gradient(135deg, #e0f7fa 60%, #80deea 100%, #00bcd4 120%); border-left: 5px solid #00bcd4; }
    .widget-mfs-total { background: linear-gradient(135deg, #f1f8e9 60%, #dcedc8 100%, #689f38 120%); border-left: 5px solid #689f38; }
    .widget-mfs-pending { background: linear-gradient(135deg, #fff3e0 60%, #ffcc80 100%, #ff9800 120%); border-left: 5px solid #ff9800; }
    .widget-agent-credit { background: linear-gradient(135deg, #e1f5fe 60%, #81d4fa 100%, #039be5 120%); border-left: 5px solid #039be5; }
    .widget-agent-debit { background: linear-gradient(135deg, #fbe9e7 60%, #ffab91 100%, #d84315 120%); border-left: 5px solid #d84315; }
    .widget-merchant-credit { background: linear-gradient(135deg, #f9fbe7 60%, #f0f4c3 100%, #afb42b 120%); border-left: 5px solid #afb42b; }
    .widget-merchant-debit { background: linear-gradient(135deg, #ede7f6 60%, #b39ddb 100%, #5e35b1 120%); border-left: 5px solid #5e35b1; }
    /* Icon backgrounds: more distinct, glassy, and vibrant */
    .widget-modem .widget-icon-bg { background: linear-gradient(135deg, #2196f3 60%, #90caf9 100%); color: #fff; }
    .widget-agent .widget-icon-bg { background: linear-gradient(135deg, #ffc107 60%, #ffe082 100%); color: #fff; }
    .widget-merchant .widget-icon-bg { background: linear-gradient(135deg, #43a047 60%, #a5d6a7 100%); color: #fff; }
    .widget-payment-today .widget-icon-bg { background: linear-gradient(135deg, #e53935 60%, #ef9a9a 100%); color: #fff; }
    .widget-payment-total .widget-icon-bg { background: linear-gradient(135deg, #0288d1 60%, #b3e5fc 100%); color: #fff; }
    .widget-payment-pending .widget-icon-bg { background: linear-gradient(135deg, #8e24aa 60%, #ce93d8 100%); color: #fff; }
    .widget-mfs-today .widget-icon-bg { background: linear-gradient(135deg, #00bcd4 60%, #80deea 100%); color: #fff; }
    .widget-mfs-total .widget-icon-bg { background: linear-gradient(135deg, #689f38 60%, #dcedc8 100%); color: #fff; }
    .widget-mfs-pending .widget-icon-bg { background: linear-gradient(135deg, #ff9800 60%, #ffcc80 100%); color: #fff; }
    .widget-agent-credit .widget-icon-bg { background: linear-gradient(135deg, #039be5 60%, #81d4fa 100%); color: #fff; }
    .widget-agent-debit .widget-icon-bg { background: linear-gradient(135deg, #d84315 60%, #ffab91 100%); color: #fff; }
    .widget-merchant-credit .widget-icon-bg { background: linear-gradient(135deg, #afb42b 60%, #f0f4c3 100%); color: #fff; }
    .widget-merchant-debit .widget-icon-bg { background: linear-gradient(135deg, #5e35b1 60%, #b39ddb 100%); color: #fff; }
    /* Card content tweaks: refined, modern typography */
    .dashboard-card .fw-bold, .dashboard-card .fs-2, .dashboard-card h4.fw-bold {
        font-size: 1.13rem !important;
        font-weight: 800 !important;
        margin-bottom: 0.03rem;
        letter-spacing: -0.5px;
        color: #222b45;
    }
    .dashboard-card .fw-semibold, .dashboard-card .mb-2.text-secondary.fw-bold {
        font-size: 0.89rem;
        opacity: 0.93;
        font-weight: 700;
        margin-bottom: 0.08rem;
        color: #4b5563;
    }
    .dashboard-card .font-13 {
        font-size: 0.74rem;
        opacity: 0.87;
    }
    .dashboard-card .badge {
        font-size: 0.66rem;
        padding: 0.10em 0.36em;
        border-radius: 7px;
        margin-left: 0.13rem;
        font-weight: 700;
        background: #008cff;
        color: #fff !important;
        box-shadow: 0 1px 2px rgba(0,140,255,0.07);
    }
    .widget-trend {
        display: flex;
        align-items: center;
        gap: 0.13rem;
        font-weight: 600;
        margin-top: 0.04rem;
        font-size: 0.74rem;
        color: #6c757d;
    }
    .dashboard-card .d-flex.align-items-center { gap: 0.18rem; }
    @media (max-width: 991.98px) {
        .dashboard-card { min-height: 40px; }
        .widget-icon-bg { width: 20px; height: 20px; font-size: 0.8rem; margin-right: 0.13rem; }
        .dashboard-card .fw-bold, .dashboard-card .fs-2, .dashboard-card h4.fw-bold { font-size: 0.8rem !important; }
        .dashboard-card .fw-semibold, .dashboard-card .mb-2.text-secondary.fw-bold { font-size: 0.62rem; }
    }
    @media (max-width: 575.98px) {
        .dashboard-card { min-height: 28px; }
        .widget-icon-bg { width: 12px; height: 12px; font-size: 0.45rem; margin-right: 0.05rem; }
        .dashboard-card .fw-bold, .dashboard-card .fs-2, .dashboard-card h4.fw-bold { font-size: 0.48rem !important; }
        .dashboard-card .fw-semibold, .dashboard-card .mb-2.text-secondary.fw-bold { font-size: 0.32rem; }
    }
    [data-bs-theme="dark"] .dashboard-card {
        background: linear-gradient(135deg, #1e5a3e 90%, #232b2f 100%) !important;
        box-shadow: 0 1.5px 6px rgba(0,140,255,0.13), 0 1px 3px rgba(30,90,62,0.13);
    }
    [data-bs-theme="dark"] .widget-icon-bg {
        background: linear-gradient(135deg, #232b2f 60%, #1e5a3e 100%) !important;
        color: #fff;
    }
</style>
@endpush

@section('content')

    <?php
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



    ?>

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 g-3">
        {{-- Widget: Total Modem --}}
        <div class="col">
            <div class="card dashboard-card widget-modem radius-10">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="widget-icon-bg"><i class='bx bxs-mobile'></i></div>
                    <div>
                        <div class="fw-semibold mb-2">Total Modem</div>
                        <div class="fw-bold">{{ $total_modem }}</div>
                        <div class="widget-trend text-success"><i class='bx bx-trending-up'></i> Only Active Modem</div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Widget: Total Agent --}}
        <div class="col">
            <div class="card dashboard-card widget-agent radius-10">
                <div class="card-body p-4 d-flex align-items-center">
                    <div class="widget-icon-bg"><i class='bx bxs-group'></i></div>
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
