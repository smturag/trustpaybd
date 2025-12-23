<!--sidebar wrapper -->
<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">

        @php
            $app_name = app_config('AppName');
            $image = app_config('AppLogo');
        @endphp

        <div>
            <img src="{{ asset('storage/'.$image) }}" class="logo-icon" alt="logo icon">
        </div>
        <div>
            <h4 class="logo-text">{{ $app_name }}</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{ route('customer_dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">Dashboard</div>
            </a>
        </li>

        <li>
            <a href="{{ route('customer.transactions') }}">
                <div class="parent-icon"><i class="lni lni-wallet"></i></div>
                <div class="menu-title">Transactions</div>
            </a>
        </li>

        <li>
            <a href="{{ route('customer.view_send_money') }}">
                <div class="parent-icon"><i class="lni lni-wallet"></i></div>
                <div class="menu-title">Send Money</div>
            </a>
        </li>

        <li>
            <a href="{{ route('customer.withdraw') }}">
                <div class="parent-icon"><i class="lni lni-wallet"></i></div>
                <div class="menu-title">Withdraw</div>
            </a>
        </li>

        <li>
            <a href="{{ url('/customer/deposit/' . url_encrypt(auth('customer')->id())) }}">
                <div class="parent-icon"><i class="lni lni-dollar"></i>
                </div>
                <div class="menu-title">Deposit</div>
            </a>
        </li>
        
         <li>
            <a href="{{ route('customer.view_betting') }}">
                <div class="parent-icon"><i class="lni lni-dollar"></i>
                </div>
                <div class="menu-title">Betting Deposit Withdraw</div>
            </a>
        </li>
        
        
        
        <!--         <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-menu"></i></div>
                <div class="menu-title">Reports</div>
            </a>
            <ul class="mm-collapse">
                <li><a href="javascript:;"><i class="bx bx-radio-circle"></i>Report 1</a></li>
            </ul>
        </li> -->
        <li>
            <a href="{{ route('customer.support_list_view') }}">
                <div class="parent-icon"><i class="bx bx-support"></i></div>
                <div class="menu-title">Support</div>
            </a>
        </li>
        <li>
            <a href="{{ route('customerlogout') }}">
                <div class="parent-icon"><i class="bx bx-exit text-red"></i></div>
                <div class="menu-title">Log Out</div>
            </a>
        </li>
    </ul>
</div>
