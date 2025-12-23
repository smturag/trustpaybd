@extends('member.layout.member_app')

@section('member_content')
    <?php
    define('current_page', 'Home Page');

    $authid = auth('web')->user()->id;
    $member_code = auth('web')->user()->member_code;
    $usertype = auth('web')->user()->user_type;

    use App\Models\User;
    use App\Models\Modem;
    use App\Models\BalanceManager;
    use App\Models\MfsOperator;

    $today = date('Y-m-d');

    $from = date('Y-m-d');
    $to = date('Y-m-d');

    //if($usertype=='agent'){

    //}

    $total_modem = Modem::where('member_code', $member_code)->count();
    $total_trx = BalanceManager::where($usertype, $authid)->count();
    $today_transection = BalanceManager::where($usertype, $authid)->whereDate('sms_time', now())->count();
    $total_trx_amount = BalanceManager::where($usertype, $authid)
        ->whereIn('status', ['20', '22', '77'])
        ->sum('amount');
    $today_trx_amount = BalanceManager::where($usertype, $authid)
        ->whereDate('sms_time', now())
        ->whereIn('status', ['20', '22', '77'])
        ->sum('amount');
    // $total_reseller = User::where('parent', $authid)->count();
    //  $reseller_balance = App\Models\User::where('parent', $authid)->sum('balance');
    // $payment_balance = App\Models\Payment::where('balance_from', $authid)->sum('amount');
    // $receive_balance = App\Models\Payment::where('user_id', $authid)->sum('amount');

    $allmodem = Modem::where('member_code', $member_code)->get();

    $get_user = auth()->user('web');





    if ($usertype == 'agent') {
        $userBalance = findAgentBalance($get_user->id);
        $get_merchant = App\Models\Merchant::where('fullname', $get_user->fullname)->first();
        $total_payment_request = App\Models\PaymentRequest::where('agent', $get_user->member_code)
            ->whereIn('status', [1, 2])
            ->sum('amount');
        $total_payment_request_today = App\Models\PaymentRequest::where('agent', $get_user->member_code)
            ->whereIn('status', [1, 2])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_payment_request_transection = App\Models\PaymentRequest::where('agent', $get_user->member_code)->count();

        $total_mfs_request = App\Models\ServiceRequest::where('agent_id', '=', $get_user->id)
            ->whereIn('status', [2, 3])
            ->sum('amount');
        $today_total_mfs_request = App\Models\ServiceRequest::where('agent_id', '=', $get_user->id)
            ->whereIn('status', [2, 3])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_mfs_transection = App\Models\ServiceRequest::where('agent_id', '=', $get_user->id)->count();

        $totalAmountReceive = App\Models\Transaction::where('user_type', 'agent')->where('user_id', $authid)->where('status', 2)->where('wallet_type', 'admin')->where('trx_type', 'credit')->sum('amount');
    } elseif ($usertype == 'dso') {
        $agents = User::where('dso', $get_user->id)
            ->where('user_type', 'agent')
            ->get();
        $agentIDs = $agents->pluck('member_code')->toArray();

        $total_payment_request = App\Models\PaymentRequest::whereIn('agent', $agentIDs)
            ->whereIn('status', [1, 2])
            ->sum('amount');
        $total_payment_request_today = App\Models\PaymentRequest::whereIn('agent', $agentIDs)
            ->whereIn('status', [1, 2])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_payment_request_transection = App\Models\PaymentRequest::whereIn('agent', $agentIDs)->count();

        $agents = User::where('dso', $get_user->id)
            ->where('user_type', 'agent')
            ->get();

        $agentIDs = $agents->pluck('id')->toArray();

        $total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
            ->whereIn('status', [2, 3])
            ->sum('amount');
        $today_total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
            ->whereIn('status', [2, 3])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_mfs_transection = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)->count();
    } elseif ($get_user->user_type == 'partner') {

       $userBalance = partnerBalance();


        $dso = App\Models\User::where('partner', $get_user->id)
            ->where('user_type', 'dso')
            ->get();
        $dsoIds = $dso->pluck('id')->toArray();
        $agents = User::whereIn('dso', $dsoIds)->where('user_type', 'agent')->get();
        $agentIDs = $agents->pluck('member_code')->toArray();

        $total_payment_request = App\Models\PaymentRequest::whereIn('agent', $agentIDs)
            ->whereIn('status', [1, 2])
            ->sum('amount');
        $total_payment_request_today = App\Models\PaymentRequest::whereIn('agent', $agentIDs)
            ->whereIn('status', [1, 2])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_payment_request_transection = App\Models\PaymentRequest::whereIn('agent', $agentIDs)->count();

        $dso = User::where('partner', $get_user->id)
            ->where('user_type', 'dso')
            ->get();
        $dsoIds = $dso->pluck('id')->toArray();
        $agents = User::whereIn('dso', $dsoIds)->where('user_type', 'agent')->get();
        $agentIDs = $agents->pluck('id')->toArray();

        $total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
            ->whereIn('status', [2, 3])
            ->sum('amount');
        $today_total_mfs_request = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)
            ->whereIn('status', [2, 3])
            ->whereDate('created_at', now())
            ->sum('amount');
        $total_mfs_transection = App\Models\ServiceRequest::whereIn('agent_id', $agentIDs)->count();
    }

    ?>





    <div>
        <div class="alert alert-danger border-0 bg-danger alert-dismissible fade show py-2">
            <div class="d-flex align-items-center">
                <div class="font-35 text-white"><i class="bx bxs-message-square-x"></i>
                </div>
                <div class="ms-3">
                    <h6 class="mb-0 text-white">Add Your WhatsAPP Number to get pending notification</h6>
                    <div>
                        <h5><a style="color: yellow;" href="{{ route('member.profile_view') }}">Click Here Go to your
                                profile</a></h5>
                    </div>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3">
        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-info">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Current Balance</p>
                            <h4 class="my-1 text-info">৳{{ money($get_user->user_type == 'agent'? $get_user->balance : ($get_user->user_type == 'partner'? $get_user->available_balance : null)  ) }}</h4>
                            <p class="mb-0 font-13">Wallet Balance</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-blues text-white ms-auto"><i
                                class='bx bxs-wallet'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10 border-start border-0 border-4 border-warning">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Device</p>
                            <h4 class="my-1 text-warning">{{ $total_modem }} </h4>
                            <p class="mb-0 font-13">Mobile Device Connect with server</p>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-orange text-white ms-auto"><i
                                class='bx bxs-mobile'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today Payment Request</p>
                            <h5 class="my-1">৳{{ money( $usertype == 'partner'? $userBalance['total_payment_request_today']  : $total_payment_request_today) }}</h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-danger text-danger ms-auto">
                            <i class='bx bxs-wallet'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Payment Request</p>
                            <h5 class="my-1">৳{{ money($usertype == 'partner'?$userBalance['total_payment_request']  : $total_payment_request) }}</h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved ( {{$usertype == 'partner'?$userBalance['total_payment_request_transaction']  : $total_payment_request_transection}})</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Pending Payment </p>
                            <h5 class="my-1">৳{{ money($userBalance['totalPendingPayment']) }}</h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Pending
                            </p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>

                </div>
            </div>
        </div>


        {{-- <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Payment Request Transaction</p>
                            <h5 class="my-1">{{ $total_payment_request_transection }}</h5>
                            <p class="mb-0 font-12 text-success"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Today MFS Request</p>
                            <h5 class="my-1">৳{{ money($usertype == 'partner'? $userBalance['total_payment_request']  : $today_total_mfs_request) }}</h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total MFS Request</p>
                            <h5 class="my-1">৳{{ money($userBalance['sumOfDebitAmount']) }}</h5>
                            <p class="mb-0 font-12 text-danger"><i class='bx bxs-down-arrow align-middle'></i>Only Success +
                                Approved ({{ $total_mfs_transection }})</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-binoculars'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary"> Pending Mfs</p>
                            <h5 class="my-1"> ৳ {{ money($userBalance['totalPendingMfs']) }}</h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only Pending
                                + Waiting + Failed + Processing</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>




        {{-- <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total MFS Request transection</p>
                            <h5 class="my-1"></h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}



        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Receive Amount</p>
                            <h5 class="my-1">৳ {{ money($userBalance['adminCreditAmount']) }}</h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">Total Return Amount</p>
                            <h5 class="my-1"> ৳ {{ money($userBalance['adminDebitAmount']) }}</h5>
                            <p class="mb-0 font-12 text-primary"><i class='bx bxs-down-arrow align-middle'></i>Only
                                Success
                                + Approved</p>
                        </div>
                        <div class="widgets-icons bg-light-primary text-primary ms-auto"><i class='bx bxs-user'></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div><!--end row-->
@endsection


@push('js')
    <script type="text/javascript"></script>
@endpush
