<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        @php
            $app_name = app_config('AppName');
        @endphp
        <div class="d-flex align-items-center gap-3">
            <div>
                <h5 class="logo-text mb-0 fw-bold">{{ $app_name }}</h5>
                <span class="badge" style="background-color: #f8f9fa; color: #222; font-size: 9px; padding: 1px 6px;">Payment Gateway</span>
            </div>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i></div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('merchant_dashboard') }}">
                <div class="parent-icon"><i class='bx bx-grid-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        {{-- <li>
            <a href="{{ route('merchant.payment-request') }}">
                <div class="parent-icon"><i class="bx bxs-credit-card"></i>
                </div>
                <div class="menu-title">Deposit</div>
            </a>
        </li> --}}

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-money"></i></div>
                <div class="menu-title">Deposit</div>
            </a>
            <ul>
                <li><a href="{{ route('merchant.payment-request.create') }}"><i class='bx bx-plus-circle'></i>Create Deposit</a>
                </li>
                <li><a href="{{ route('merchant.payment-request') }}"><i class='bx bx-list-ul'></i>Deposit History</a>
                </li>
            </ul>
        </li>


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-money"></i></div>
                <div class="menu-title">Withdraw</div>
            </a>
            <ul>
                <li><a href="{{ route('merchant.withdraw') }}"><i class='bx bx-plus-circle'></i> Request Withdraw</a>
                </li>
                <li><a href="{{ route('merchant.service-request') }}"><i class='bx bx-list-ul'></i> Withdraw History</a>
                </li>
            </ul>
        </li>



        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-money"></i></div>
                <div class="menu-title">Payout</div>
            </a>
            <ul>
                <li><a href="{{ route('merchant.withdraw') }}"><i class='bx bx-plus-circle'></i>Payout Request </a>
                </li>
                <li><a href="{{ route('merchant.withdraw-list') }}"><i class='bx bx-list-ul'></i> Payout History</a>
                </li>
            </ul>
        </li>

        {{--

        <li>
            <a href="{{ route('merchant.payment-request.create') }}">
                <div class="parent-icon"><i class="bx bxs-credit-card"></i>
                </div>
                <div class="menu-title">Create Deposit</div>
            </a>
        </li>

        --}}

    {{--
        <li>
            <a href="{{ route('merchant_modemList') }}">
                <div class="parent-icon"><i class="bx bx-mobile-alt"></i>
                </div>
                <div class="menu-title">Modems</div>
            </a>
        </li>

        --}}


        {{-- <li>
            <a href="{{ route('merchant.developer-index') }}">
                <div class="parent-icon"><i class="bx bx-code-alt"></i></div>
                <div class="menu-title">Developer</div>
            </a>
        </li> --}}

        @if (auth()->guard('merchant')->user()->merchant_type == 'general')
            <li>
               <a href="{{ route('sub_merchant.list') }}">
                   <div class="parent-icon"><i class="bx bx-user-plus"></i></div>
                     <div class="menu-title">Sub Merchant</div>
                 </a>
             </li>
        @endif


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-code-block"></i>
                </div>
                <div class="menu-title">Developer</div>
            </a>
            <ul>
                <li><a href="{{ route('merchant.developer-index') }}"><i class='bx bx-key'></i>API Keys</a></li>
                <li><a href="{{ route('develop_docs') }}"><i class='bx bx-book-open'></i>Developer Docs</a></li>
            </ul>
            <!--    </li>-->
            <!--    	<li>-->
            <!--	<a class="has-arrow" href="javascript:;">-->
            <!--		<div class="parent-icon"><i class="bx bx-menu"></i>-->
            <!--		</div>-->
            <!--		<div class="menu-title">Reports</div>-->
            <!--	</a>-->
            <!--	<ul class="mm-collapse">-->
            <!--		<li> <a  href="javascript:;"><i class="bx bx-radio-circle"></i>Report 1</a></li>-->
            <!--	</ul>-->
            <!--</li>-->

         {{--        <li>
       <a class="has-arrow" href="javascript:;">
            <div class="parent-icon"><i class="bx bx-line-chart"></i>
            </div>
            <div class="menu-title">Balance Manager</div>
        </a>
        <ul>
            <li><a href="{{ route('merchant.balance_manager', 'all') }}"><i class='bx bx-radio-circle'></i>All
                    Transactions</a>
            </li>
            <li><a href="{{ route('merchant.balance_manager', 'pendings') }}"><i class='bx bx-radio-circle'></i>Pending <span
                        class="badge bg-primary rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 0)->count() }}</span></a>
            </li>

            <li><a href="{{ route('merchant.balance_manager', 'waiting') }}"><i class='bx bx-radio-circle'></i>Waiting <span
                        class="badge bg-info rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 33)->count() }}</span></a>
            </li>

            <li><a href="{{ route('merchant.balance_manager', 'danger') }}"><i class='bx bx-radio-circle'></i>Danger <span
                        class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\BalanceManager::where('status', 55)->count() }}</span></a>
            </li>

            <li><a href="{{ route('merchant.balance_manager', 'success') }}"><i class='bx bx-radio-circle'></i>Success </a>
            </li>
            <li><a href="{{ route('merchant.balance_manager', 'reject') }}"><i class='bx bx-radio-circle'></i>Reject </a></li>
        </ul>
    </li> --}}

            <li>
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-bar-chart-alt-2"></i>
                    </div>
                    <div class="menu-title">Reports</div>
                </a>
                @if (auth()->guard('merchant')->user()->merchant_type == 'general')
                <ul class="mm-collapse">
                    <li><a href="{{ route('report.merchant.payment_report') }}"><i class="bx bx-line-chart"></i>Payment Report </a></li>

                </ul>
                <ul class="mm-collapse">
            <li><a href="{{ route('report.merchant.service_report') }}"><i class="bx bx-radio-circle"></i>Service Report</a>
            </li>
        </ul>
                @endif
            </li>

        <li>
            <a href="{{ route('merchant.developer.service-rates') }}">
                <div class="parent-icon"><i class="bx bx-list-ul"></i></div>
                <div class="menu-title">Service Rates</div>
            </a>
        </li>

        <li>
            <a href="{{ route('merchant.support_list_view') }}">
                <div class="parent-icon"><i class="bx bx-headphone"></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>
        <li>
            <a href="{{ route('merchantlogout') }}">
                <div class="parent-icon"><i class="bx bx-log-out-circle text-danger"></i></div>
                <div class="menu-title">Log Out</div>
            </a>
        </li>
    </ul>
</div>
