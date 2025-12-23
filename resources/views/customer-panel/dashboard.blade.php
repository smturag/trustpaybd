@extends('customer-panel.customer_app')
@section('title', 'Dashboard')

@push('css')
    <?php
    use Carbon\Carbon;

    $today = Carbon::today();
    $total_customer = App\Models\Customer::where('db_status', 'live')->count();
    $username = auth('customer')->user()->username;
    ?>
@endpush

@section('customer_content')

    @if(session()->has('alert'))
        <div class="alert alert-success" id="alert_success">
            {{session('alert')}}
        </div>
    @endif

    <div class="card radius-10">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <div>
                    <h5 class="mb-2">Welcome, Customer</h5>
                </div>
            </div>

            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-4 justify-content-center">
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Current Balance</p>
                                    <h5 class="my-1">{{ auth('customer')->user()->balance }} </h5>
                                    <p class="mb-0 font-12 text-info"><i class='bx bxs-up-arrow align-middle'></i>Active Balance</p>
                                </div>
                                <div class="widgets-icons bg-light-success text-success ms-auto">
                                    <i class='bx bxs-mobile'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Withdraw</p>
                                    <h5 class="my-1">৳0</h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Payment</p>
                                    <h5 class="my-1">৳0</h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card radius-10">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <p class="mb-0 text-secondary">Total Cashout</p>
                                    <h5 class="my-1">৳{{$total_cashout}}</h5>
                                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>
                                </div>
                                <div class="widgets-icons bg-light-danger text-danger ms-auto">
                                    <i class='bx bxs-wallet'></i>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <!--<div class="col">-->
                <!--    <div class="card radius-10">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center">-->
                <!--                <div>-->
                <!--                    <p class="mb-0 text-secondary">Today Transaction</p>-->
                <!--                    <h5 class="my-1">{{ $total_trx_today }}</h5>-->
                <!--                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>-->
                <!--                </div>-->
                <!--                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i-->
                <!--                            class='bx bxs-binoculars'></i>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<div class="col">-->
                <!--    <div class="card radius-10">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center">-->
                <!--                <div>-->
                <!--                    <p class="mb-0 text-secondary">Total TRX Amount</p>-->
                <!--                    <h5 class="my-1">৳{{ money($total_trx_amount) }}</h5>-->
                <!--                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>-->
                <!--                </div>-->
                <!--                <div class="widgets-icons bg-light-danger text-danger ms-auto"><i-->
                <!--                            class='bx bxs-wallet'></i>-->
                <!--                </div>-->
                <!--            </div>-->

                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col">-->
                <!--    <div class="card radius-10">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center">-->
                <!--                <div>-->
                <!--                    <p class="mb-0 text-secondary">Total Transaction</p>-->
                <!--                    <h5 class="my-1">{{ $total_trx }}</h5>-->
                <!--                    <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success + Approved</p>-->
                <!--                </div>-->
                <!--                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col">-->
                <!--    <div class="card radius-10">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center">-->
                <!--                <div>-->
                <!--                    <p class="mb-0 text-secondary">Total Pending</p>-->
                <!--                    <h5 class="my-1">{{ $total_pending }}</h5>-->
                <!--                    <p class="mb-0 font-12 text-danger"><i class='bx bxs-down-arrow align-middle'></i>Waiting + Danger + Pending</p>-->
                <!--                </div>-->
                <!--                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->

                <!--<div class="col">-->
                <!--    <div class="card radius-10">-->
                <!--        <div class="card-body">-->
                <!--            <div class="d-flex align-items-center">-->
                <!--                <div>-->
                <!--                    <p class="mb-0 text-secondary">Total Customer</p>-->
                <!--                    <h5 class="my-1">{{ $total_customer }}</h5>-->
                <!--                    <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only Active Customer</p>-->
                <!--                </div>-->
                <!--                <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>-->
                <!--                </div>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
            </div>
        </div>
    </div>
    <!--end row-->



    <!--<div class="card radius-10">-->
    <!--    <div class="card-body">-->
    <!--        <div class="d-flex align-items-center">-->
    <!--            <div>-->
    <!--                <h5 class="mb-0">Recent Transaction</h5>-->
    <!--            </div>-->

    <!--        </div>-->
    <!--        <hr/>-->
    <!--        <div class="table-responsive">-->
    <!--            <table class="table align-middle mb-0 table-bordered">-->
    <!--                <thead class="table-light">-->
    <!--                <tr>-->
    <!--                    <th>id</th>-->
    <!--                    <th scope="col" class="text-center">Sender</th>-->
    <!--                    <th scope="col">C Number</th>-->
    <!--                    <th scope="col">Oldbal</th>-->
    <!--                    <th scope="col">Amount</th>-->
    <!--                    <th scope="col">Comm.</th>-->
    <!--                    <th scope="col">Lastbal</th>-->
    <!--                    <th scope="col">Trxid</th>-->
    <!--                    <th scope="col">A Number</th>-->
    <!--                    <th scope="col">Status</th>-->
    <!--                    <th scope="col">Date</th>-->
    <!--                </tr>-->
    <!--                </thead>-->
    <!--                <tbody>-->

    <!--                <?php $lasttrx = App\Models\BalanceManager::orderBy('id', 'desc')->take(10)->get(); ?>-->

    <!--                @foreach($lasttrx as $row)-->
    <!--                    <tr>-->
    <!--                        <td>{{ $row->id }}</td>-->
    <!--                        <td>{{ $row->sender }}</td>-->
    <!--                        <td>{{ $row->mobile }}</td>-->
    <!--                        <td class="text-end">{{ money($row->oldbal) }}</td>-->
    <!--                        <td class="text-end">{{ money($row->amount) }}</td>-->
    <!--                        <td>{{ money($row->commission) }}</td>-->
    <!--                        <td class="text-end">{{ money($row->lastbal) }}</td>-->
    <!--                        <td>{{ $row->trxid }}</td>-->
    <!--                        <td>{{ $row->sim }}</td>-->

    <!--                        @if($row->status == 20 || $row->status == 22)-->
    <!--                            <td><span class='badge badge-pill bg-success'><i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Success</span></td>-->
    <!--                        @elseif($row->status == 33)-->
    <!--                            <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>-->
    <!--                        @elseif($row->status == 55)-->
    <!--                            <td><span class='badge badge-pill bg-danger text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Danger</span></td>-->
    <!--                        @elseif($row->status == 66)-->
    <!--                            <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>-->
    <!--                        @elseif($row->status == 77)-->
    <!--                            <td><span class='badge badge-pill bg-success text-white'> <i class='bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1'></i> Approved</span></td>-->
    <!--                        @else-->
    <!--                            <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>-->
    <!--                        @endif-->

    <!--                        <td>-->
    <!--                            {{ $row->sms_time }}-->
    <!--                            <p class="text-success font-weight-bold">-->
    <!--                                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans() ?>-->
    <!--                            </p>-->
    <!--                        </td>-->
    <!--                    </tr>-->
    <!--                @endforeach-->
    <!--                </tbody>-->
    <!--            </table>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>-->




    @endsection


    @push('js')
        <script type="text/javascript">

        </script>

    @endpush
