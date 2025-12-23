<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <a href="{{ route('admin_dashboard') }}">
            @php
                $app_name = app_config('AppName');
                $image = app_config('AppLogo');

                // echo $image;

            @endphp
            <img src="{{ asset('storage/' . $image) }}" class="logo-icon w-50" alt="logo icon">
            {{-- <span class="logo-text">{{ $app_name }}</span> --}}
        </a>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>

    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('admin_dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i></div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

         <li>
        <a href="{{route('deposit')}}">
            <div class="parent-icon"><i class='fadeIn animated bx bx-outline'></i></div>
            <div class="menu-title">Deposit <span
                        class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\PaymentRequest::where('status', 0)->whereNotNull('payment_method')->count() }}</span> </div>
        </a>
    </li>

{{-- 
        <li>
            <a href="{{ route('admin.merchant.payment-request') }}">
                <div class="parent-icon"><i class='lni lni-mastercard'></i></div>
                <div class="menu-title">Deposit
                    <span
                        class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\PaymentRequest::where('status', 0)->whereNotNull('payment_method')->count() }}</span>
                </div>
            </a>
        </li>
        --}}

        <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="fadeIn animated bx bx-mobile-alt"></i>
            </div>
            <div class="menu-title">Withdraw

                @php
                    $success = App\Models\ServiceRequest::whereIn('status', [2, 3])->count();
                    $pending = App\Models\ServiceRequest::where('status', 0)->count();
                    $wating = App\Models\ServiceRequest::where('status', 1)->count();
                    $reject = App\Models\ServiceRequest::where('status', 4)->count();
                    $processing = App\Models\ServiceRequest::where('status', 5)->count();
                    $failed = App\Models\ServiceRequest::where('status', 6)->count();
                    $MakeCount = $pending + $wating + $processing + $failed;
                @endphp

                <span class="badge bg-danger rounded-pill ms-auto right">{{ $MakeCount }}</span>
            </div>
        </a>
        <ul>
            <li><a href="{{ route('serviceReq', 'all') }}"><i class='bx bx-radio-circle'></i>All Request</a></li>
            <li><a href="{{ route('serviceReq', 'success') }}"><i class='bx bx-radio-circle'></i>Success </a></li>
            <li><a href="{{ route('serviceReq', 'waiting') }}"><i class='bx bx-radio-circle'></i>Waiting <span
                        class="badge bg-info rounded-pill ms-auto right">{{ $wating }}</span></a>
            </li>
            <li>
                <a href="{{ route('serviceReq', 'pending') }}"><i class='bx bx-radio-circle'></i>Pending
                    <span class="badge bg-primary rounded-pill ms-auto right">{{ $pending }}</span></a>
            </li>
            <li>
                <a href="{{ route('serviceReq', 'rejected') }}"><i class='bx bx-radio-circle'></i>Reject <span
                        class="badge bg-primary rounded-pill ms-auto right">{{ $reject }}</span></a>
            </li>
            <li><a href="{{ route('serviceReq', 'processing') }}"><i class='bx bx-radio-circle'></i>Processing <span
                        class="badge bg-primary rounded-pill ms-auto right">{{ $processing }}</span> </a> </li>
            <li><a href="{{ route('serviceReq', 'failed') }}"><i class='bx bx-radio-circle'></i>Failed <span
                        class="badge bg-primary rounded-pill ms-auto right">{{ $failed }}</span> </a> </li>

        </ul>
    </li>

        <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-line-chart"></i>
            </div>
            <div class="menu-title">Balance Manager</div>
        </a>
        <ul>
            <li><a href="{{ route('balance_manager', 'all') }}"><i class='bx bx-radio-circle'></i>All
                    Transactions</a>
            </li>
            <li><a href="{{ route('balance_manager', 'pendings') }}"><i class='bx bx-radio-circle'></i>Pending <span
                        class="badge bg-primary rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 0)->count() }}</span></a>
            </li>

            <li><a href="{{ route('balance_manager', 'waiting') }}"><i class='bx bx-radio-circle'></i>Waiting <span
                        class="badge bg-info rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 33)->count() }}</span></a>
            </li>

            <li><a href="{{ route('balance_manager', 'danger') }}"><i class='bx bx-radio-circle'></i>Danger <span
                        class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 55)->count() }}</span></a>
            </li>

            <li><a href="{{ route('balance_manager', 'success') }}"><i class='bx bx-radio-circle'></i>Success </a>
            </li>
            <li><a href="{{ route('balance_manager', 'reject') }}"><i class='bx bx-radio-circle'></i>Reject </a></li>
        </ul>
    </li>

        <li>
        <a href="{{ route('admin_sms_inbox') }}">
            <div class="parent-icon"><i class="fadeIn animated bx bx-message-detail"></i>
            </div>
            <div class="menu-title">Sms Inbox</div>
        </a>
    </li>

{{-- 
<li> 
    <a href="{{ route('admin.wallet.transactions') }}">
        <div class="parent-icon"><i class='lni lni-wallet'></i></div>
        <div class="menu-title">Wallet Transaction</div>
        <span class="badge bg-danger rounded-pill ms-auto right">
            {{ App\Models\WalletTransaction::where('status', 0)->whereNotNull('payment_method')->count() }}
        </span>
    </a>
</li> 
--}}

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-user"></i>
                </div>
                <div class="menu-title">All User</div>
            </a>
            <ul>
                <li>
                    <a href="{{ route('merchantList') }}">
                        <div class="parent-icon"><i class='bx bx-radio-circle'></i></div>
                        <div class="menu-title">Merchant</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('customerList') }}">
                        <div class="parent-icon"><i class='bx bx-radio-circle'></i></div>
                        <div class="menu-title">Customer</div>
                    </a>
                </li>

                <li>
                    <a href="{{ route('userList') }}">
                        <div class="parent-icon"><i class='bx bx-radio-circle'></i></div>
                        <div class="menu-title">Members</div>
                    </a>
                </li>

        </li>




    </ul>
    </li>


    


    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="fadeIn animated bx bx-money"></i>
            </div>
            <div class="menu-title">Method</div>
        </a>
        <ul class="mm-collapse">
            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-radio-circle"></i>
                    </div>
                    <div class="menu-title">Payment Method</div>
                </a>
                <ul>
                    <li><a href="{{ route('payment.mobile_banking') }}"><i class='bx bx-radio-circle'></i>Mobile
                            Banking</a>
                    </li>
                </ul>
                <ul>
                    <li><a href="{{ route('payment.api_method_list') }}"><i class='bx bx-radio-circle'></i>Api
                            Method</a>
                    </li>
                </ul>
            </li>

            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-radio-circle"></i>
                    </div>
                    <div class="menu-title">Withdraw Method</div>
                </a>
                <ul>
                    <li><a href="{{ route('withdraw.mobile_banking') }}"><i class='bx bx-radio-circle'></i>Mobile
                            Banking</a>
                    </li>
                    <li><a href="{{ route('crypto.index') }}"><i class='bx bx-radio-circle'></i>Crypto Currency </a>
                    </li>
                </ul>
            </li>
        </ul>
    </li>

    <li>
        <a href="{{ route('admin_modemList') }}">
            <div class="parent-icon"><i class='bx bx-mobile'></i></div>
            <div class="menu-title">Modem List</div>
        </a>
    </li>





    <!--<li>-->
    <!--    <a href="{ route('merchantList') }}">-->
    <!--        <div class="parent-icon"><i class='bx bx-user'></i></div>-->
    <!--        <div class="menu-title">Merchant Client</div>-->
    <!--    </a>-->
    <!--</li>-->

    <!--<li>-->
    <!--    <a href="#">-->
    <!--        <div class="parent-icon"><i class='bx bx-user'></i></div>-->
    <!--        <div class="menu-title">Merchant Payment</div>-->
    <!--    </a>-->
    <!--</li>-->






    {{-- <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="far fa-clock"></i>
                </div>
                <div class="menu-title">All Reports</div>
            </a>
            <ul>
                <li><a href="{{ route('sim_report', 'all') }}"><i class='bx bx-radio-circle'></i>Report Sim</a></li>
                <li><a href="{{ route('balance_manager', 'pendings') }}"><i class='bx bx-radio-circle'></i>Report Member <span class="badge bg-primary rounded-pill ms-auto right">{{   App\Models\BalanceManager::where('status', 0)->count(); }}</span></a></li>

                 <li><a href="{{ route('balance_manager', 'waiting') }}"><i class='bx bx-radio-circle'></i>Report Partner<span class="badge bg-info rounded-pill ms-auto right">{{   App\Models\BalanceManager::where('status', 33)->count(); }}</span></a></li>

                <li><a href="{{ route('balance_manager', 'danger') }}"><i class='bx bx-radio-circle'></i>Repor Merchant <span class="badge bg-danger rounded-pill ms-auto right">{{   App\Models\BalanceManager::where('status', 55)->count(); }}</span></a></li>


                </li>
            </ul>
        </li> --}}

    <li>
        <a href="#">
            <div class="parent-icon"><i class='fadeIn animated bx bx-outline'></i></div>
            <div class="menu-title">Activity Logs</div>
        </a>
    </li>


    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class='bx bx-cog'></i>
            </div>
            <div class="menu-title">Settings</div>
        </a>
        <ul>
            <li><a href="{{ route('bulk_sms.index') }}"><i class="fadeIn animated bx bx-message-add"></i>SMS
                    System</a></li>
            <li><a href="{{ route('mfs.index') }}"><i class="bx bx-radio-circle"></i>MFS Operator</a></li>
            <li><a href="{{ route('settings.index') }}"><i class="bx bx-radio-circle"></i>App Settings</a></li>
           
            
            {{-- <li>
                    <a class="has-arrow" href="javascript:;">
                        <div class="parent-icon"><i class="bx bx-cog fs-5"></i>
                        </div>
                        <div class="menu-title">Service</div>
                    </a>
                    <ul>
                       <li> <a ><i class="bx bx-radio-circle"></i>MFS Operator
                    </a>
                </li>
                    </ul>
                </li> --}}
        </ul>
    </li>





    <li>
        <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-menu"></i>
            </div>
            <div class="menu-title">Reports</div>
        </a>
        <ul class="mm-collapse">
            <li><a href="{{ route('report.payment_report') }}"><i class="bx bx-radio-circle"></i>Payment Report</a>
            </li>
        </ul>

        <ul class="mm-collapse">
            <li><a href="{{ route('report.service_report') }}"><i class="bx bx-radio-circle"></i>Service Report</a>
            </li>
        </ul>

    </li>

    <li>
        <a href="{{ route('admin.support_list') }}">
            <div class="parent-icon"><i class="bx bx-support"></i>
            </div>
            <div class="menu-title">Support
                <span
                    class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\SupportTicket::whereIn('status', [1, 2, 3])->count() }}</span>
            </div>
        </a>
    </li>


   

    </ul>
    <!--end navigation-->
</div>
