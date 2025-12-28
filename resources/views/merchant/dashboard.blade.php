@extends('merchant.mrc_app')
@section('title', 'Dashboard')

@section('mrc_content')
    @push('css')
        <?php
        use Carbon\Carbon;

        $today = Carbon::today();
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
        $username = auth('merchant')->user()->username;
        $total_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->sum('amount');
        $today_cashout = App\Models\BalanceManager::where('merchent_id', $username)
            ->whereIn('status', [20, 77])
            ->whereIn('type', ['ngcashout', 'bkcashout', 'rccashout'])
            ->whereDate('sms_time', $today)
            ->sum('amount');

        $merchant = Auth::guard('merchant')->user();
        $dashboardBalance = $merchant->merchant_type === 'sub_merchant'
            ? $merchant->balance
            : $merchant->available_balance;

        if ($merchant->merchant_type == 'general') {
            $total_payment_request = App\Models\PaymentRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [1, 2])
                ->sum('merchant_main_amount');

            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
                ->where('merchant_id', $merchant->id)
                ->whereIn('status', [1, 2])
                ->sum('merchant_main_amount');

            $total_payment_request_transection = App\Models\PaymentRequest::where('merchant_id', $merchant->id)->count();

            $total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('merchant_main_amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('merchant_main_amount');

            $total_mfs_transection = App\Models\ServiceRequest::where('merchant_id', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        } else {
            $total_payment_request = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [1, 2])
                ->sum('sub_merchant_main_amount');

            $total_payment_request_today = App\Models\PaymentRequest::whereDate('created_at', now())
                ->where('sub_merchant', $merchant->id)
                ->whereIn('status', [1, 2])
                ->sum('sub_merchant_main_amount');

            $total_payment_request_transection = App\Models\PaymentRequest::where('sub_merchant', $merchant->id)->count();
            $total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [2, 3])
                ->sum('sub_merchant_main_amount');
            $today_total_mfs_request = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereDate('created_at', now())
                ->whereIn('status', [2, 3])
                ->sum('sub_merchant_main_amount');
            $total_mfs_transection = App\Models\ServiceRequest::where('sub_merchant', $merchant->id)
                ->whereIn('status', [1, 2])
                ->count();
        }
        ?>
    @endpush

    <!-- Modern Tailwind Dashboard -->
    <div class="p-4 sm:p-6 space-y-4 sm:space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-xl lg:rounded-2xl shadow-2xl p-4 sm:p-6 lg:p-8 text-white">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-xl sm:text-2xl md:text-3xl lg:text-4xl font-bold mb-2">Welcome back, {{ $merchant->fullname }}! 👋</h1>
                    <p class="text-indigo-100 text-sm sm:text-base lg:text-lg">Here's your payment gateway overview for today</p>
                </div>
                <div class="flex items-center space-x-2 sm:space-x-3 bg-white/20 backdrop-blur-sm rounded-lg lg:rounded-xl px-3 sm:px-4 lg:px-6 py-2 sm:py-3 border border-white/30">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <span class="font-semibold text-sm sm:text-base lg:text-lg">{{ now()->format('d M, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Main Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- Available Balance Card -->
            <div class="bg-gradient-to-br from-emerald-500 to-green-600 rounded-xl lg:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl active:scale-95">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-white/20 backdrop-blur-sm p-2 sm:p-3 rounded-lg lg:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                    </div>
                    <div class="bg-white/20 backdrop-blur-sm px-2 sm:px-3 py-1 rounded-full text-xs font-semibold">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Active
                    </div>
                </div>
                <p class="text-emerald-100 text-xs sm:text-sm font-medium mb-1 sm:mb-2">Available Balance</p>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3">৳{{ number_format($dashboardBalance, 2) }}</h3>
                <div class="flex items-center text-xs sm:text-sm">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                    <span class="font-medium">Ready to use</span>
                </div>
                <div class="mt-3 sm:mt-4 bg-white/20 rounded-full h-1.5 sm:h-2 overflow-hidden">
                    <div class="bg-white h-full rounded-full transition-all duration-1000" style="width: 85%"></div>
                </div>
            </div>

            <!-- Deposit Card -->
            <div class="bg-gradient-to-br from-blue-500 to-indigo-600 rounded-xl lg:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl active:scale-95">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-white/20 backdrop-blur-sm p-2 sm:p-3 rounded-lg lg:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <a href="{{ route('merchant.payment-request') }}" class="bg-white/20 backdrop-blur-sm p-2 rounded-lg hover:bg-white/30 transition-colors active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <p class="text-blue-100 text-xs sm:text-sm font-medium mb-1 sm:mb-2">Total Deposit</p>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3">৳{{ number_format($total_payment_request, 2) }}</h3>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="truncate">Today: ৳{{ number_format($total_payment_request_today, 2) }}</span>
                    </div>
                    <span class="bg-white/20 backdrop-blur-sm px-2 py-1 rounded-full text-[10px] sm:text-xs whitespace-nowrap ml-2">{{ $total_payment_request_transection }} TXN</span>
                </div>
                <div class="mt-3 sm:mt-4 bg-white/20 rounded-full h-1.5 sm:h-2 overflow-hidden">
                    <div class="bg-white h-full rounded-full transition-all duration-1000" style="width: 75%"></div>
                </div>
            </div>

            <!-- Withdraw Card -->
            <div class="bg-gradient-to-br from-orange-500 to-red-600 rounded-xl lg:rounded-2xl shadow-xl p-4 sm:p-6 text-white transform hover:scale-105 transition-all duration-300 hover:shadow-2xl active:scale-95">
                <div class="flex items-center justify-between mb-3 sm:mb-4">
                    <div class="bg-white/20 backdrop-blur-sm p-2 sm:p-3 rounded-lg lg:rounded-xl">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <a href="{{ route('merchant.service-request') }}" class="bg-white/20 backdrop-blur-sm p-2 rounded-lg hover:bg-white/30 transition-colors active:scale-95">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                    </a>
                </div>
                <p class="text-orange-100 text-xs sm:text-sm font-medium mb-1 sm:mb-2">Total Withdraw</p>
                <h3 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-2 sm:mb-3">৳{{ number_format($total_mfs_request, 2) }}</h3>
                <div class="flex items-center justify-between text-xs sm:text-sm">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        <span class="truncate">Today: ৳{{ number_format($today_total_mfs_request, 2) }}</span>
                    </div>
                    <span class="bg-white/20 backdrop-blur-sm px-2 py-1 rounded-full text-[10px] sm:text-xs whitespace-nowrap ml-2">{{ $total_mfs_transection }} TXN</span>
                </div>
                <div class="mt-3 sm:mt-4 bg-white/20 rounded-full h-1.5 sm:h-2 overflow-hidden">
                    <div class="bg-white h-full rounded-full transition-all duration-1000" style="width: 65%"></div>
                </div>
            </div>
        </div>

        <!-- Additional Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            <!-- Today Payment Request -->
            <div class="dashboard-card rounded-xl shadow-lg p-4 sm:p-6 dashboard-border hover:shadow-xl transition-all duration-300 active:scale-95">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-blue-100 dashboard-icon-blue p-3 rounded-lg">
                        <svg class="w-6 h-6 dashboard-icon-blue-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm dashboard-text-secondary font-medium mb-1">Today Payment</p>
                <h4 class="text-2xl font-bold dashboard-text-primary mb-2">৳{{ number_format($total_payment_request_today, 2) }}</h4>
                <div class="flex items-center text-xs dashboard-icon-blue-text">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ now()->format('d M Y') }}
                </div>
            </div>

            <!-- Pending Payment -->
            <div class="dashboard-card rounded-xl shadow-lg p-6 dashboard-border hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-yellow-100 dashboard-icon-yellow p-3 rounded-lg">
                        <svg class="w-6 h-6 dashboard-icon-yellow-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm dashboard-text-secondary font-medium mb-1">Pending Payment</p>
                <h4 class="text-2xl font-bold dashboard-text-primary mb-2">৳{{ number_format(getMerchantBalance($merchant->id)['totalPendingPayment'], 2) }}</h4>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium dashboard-badge-yellow">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Pending
                </span>
            </div>

            <!-- Receive Balance -->
            <div class="dashboard-card rounded-xl shadow-lg p-6 dashboard-border hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-green-100 dashboard-icon-green p-3 rounded-lg">
                        <svg class="w-6 h-6 dashboard-icon-green-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm dashboard-text-secondary font-medium mb-1">Receive Balance</p>
                <h4 class="text-2xl font-bold dashboard-text-primary mb-2">৳{{ number_format(getMerchantBalance($merchant->id)['adminCreditAmount'], 2) }}</h4>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium dashboard-badge-green">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Credited
                </span>
            </div>

            <!-- Return Balance -->
            <div class="dashboard-card rounded-xl shadow-lg p-6 dashboard-border hover:shadow-xl transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-red-100 dashboard-icon-red p-3 rounded-lg">
                        <svg class="w-6 h-6 dashboard-icon-red-text" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                        </svg>
                    </div>
                </div>
                <p class="text-sm dashboard-text-secondary font-medium mb-1">Return Balance</p>
                <h4 class="text-2xl font-bold dashboard-text-primary mb-2">৳{{ number_format(getMerchantBalance($merchant->id)['adminDebitAmount'], 2) }}</h4>
                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium dashboard-badge-red">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    Debited
                </span>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
            <a href="{{ route('merchant.payment-request.create') }}" class="group dashboard-quick-action-blue border-2 border-blue-200 rounded-xl p-4 sm:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-blue-600 to-indigo-600 p-3 sm:p-4 rounded-xl text-white group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 flex-1">
                        <h3 class="text-base sm:text-lg font-bold dashboard-text-primary">Create Deposit</h3>
                        <p class="text-xs sm:text-sm dashboard-text-secondary">Start new payment request</p>
                    </div>
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2 dashboard-icon-blue-text group-hover:translate-x-2 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('merchant.withdraw') }}" class="group dashboard-quick-action-orange border-2 border-orange-200 rounded-xl p-4 sm:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-orange-600 to-red-600 p-3 sm:p-4 rounded-xl text-white group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 flex-1">
                        <h3 class="text-base sm:text-lg font-bold dashboard-text-primary">Request Withdraw</h3>
                        <p class="text-xs sm:text-sm dashboard-text-secondary">Withdraw your funds</p>
                    </div>
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2 dashboard-icon-orange-text group-hover:translate-x-2 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>

            <a href="{{ route('merchant.developer-index') }}" class="group dashboard-quick-action-purple border-2 border-purple-200 rounded-xl p-4 sm:p-6 hover:shadow-xl transition-all duration-300 hover:scale-105 active:scale-95">
                <div class="flex items-center">
                    <div class="bg-gradient-to-br from-purple-600 to-pink-600 p-3 sm:p-4 rounded-xl text-white group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4 flex-1">
                        <h3 class="text-base sm:text-lg font-bold dashboard-text-primary">API Settings</h3>
                        <p class="text-xs sm:text-sm dashboard-text-secondary">Manage API keys</p>
                    </div>
                    <svg class="w-5 h-5 sm:w-6 sm:h-6 ml-2 dashboard-icon-purple-text group-hover:translate-x-2 transition-transform flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </div>
            </a>
        </div>

        <!-- Transaction Chart Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
            <!-- Line Chart - Transaction Trends -->
            <div class="dashboard-card rounded-xl lg:rounded-2xl shadow-xl p-4 sm:p-6 dashboard-border">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold dashboard-text-primary">Transaction Trends</h3>
                        <p class="text-xs sm:text-sm dashboard-text-secondary">Last 7 days overview</p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 p-2 sm:p-3 rounded-lg text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                        </svg>
                    </div>
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="transactionLineChart"></canvas>
                </div>
                <div class="flex items-center justify-center gap-4 sm:gap-6 mt-4 sm:mt-6 flex-wrap">
                    <div class="flex items-center">
                        <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-blue-500 mr-2"></div>
                        <span class="text-xs sm:text-sm dashboard-text-secondary">Deposits</span>
                    </div>
                    <div class="flex items-center">
                        <div class="w-3 h-3 sm:w-4 sm:h-4 rounded-full bg-orange-500 mr-2"></div>
                        <span class="text-xs sm:text-sm dashboard-text-secondary">Withdraws</span>
                    </div>
                </div>
            </div>

            <!-- Bar Chart - Comparison -->
            <div class="dashboard-card rounded-xl lg:rounded-2xl shadow-xl p-4 sm:p-6 dashboard-border">
                <div class="flex items-center justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-lg sm:text-xl font-bold dashboard-text-primary">Deposit vs Withdraw</h3>
                        <p class="text-xs sm:text-sm dashboard-text-secondary">Daily comparison</p>
                    </div>
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 p-2 sm:p-3 rounded-lg text-white">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                    </div>
                </div>
                <div class="relative" style="height: 300px;">
                    <canvas id="transactionBarChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-3 sm:gap-4 mt-4 sm:mt-6">
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-3 sm:p-4">
                        <p class="text-xs dashboard-text-secondary mb-1">Total Deposits</p>
                        <p class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">৳{{ number_format(array_sum($chartData['deposits']), 2) }}</p>
                    </div>
                    <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg p-3 sm:p-4">
                        <p class="text-xs dashboard-text-secondary mb-1">Total Withdraws</p>
                        <p class="text-lg sm:text-xl font-bold text-orange-600 dark:text-orange-400">৳{{ number_format(array_sum($chartData['withdraws']), 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('css')
<style>
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 10px;
        height: 10px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f5f9;
    }
    ::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 5px;
    }
    ::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }

    /* Dark mode scrollbar */
    html.dark-theme ::-webkit-scrollbar-track {
        background: #1e293b;
    }
    html.dark-theme ::-webkit-scrollbar-thumb {
        background: #475569;
    }
    html.dark-theme ::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }

    /* Dashboard Card Styles - Light Mode */
    .dashboard-card {
        background: white;
    }
    
    .dashboard-border {
        border: 1px solid #e2e8f0;
    }
    
    .dashboard-text-primary {
        color: #0f172a;
    }
    
    .dashboard-text-secondary {
        color: #64748b;
    }
    
    /* Icon Backgrounds - Light Mode */
    .dashboard-icon-blue {
        background: #dbeafe;
    }
    
    .dashboard-icon-blue-text {
        color: #2563eb;
    }
    
    .dashboard-icon-yellow {
        background: #fef3c7;
    }
    
    .dashboard-icon-yellow-text {
        color: #d97706;
    }
    
    .dashboard-icon-green {
        background: #d1fae5;
    }
    
    .dashboard-icon-green-text {
        color: #059669;
    }
    
    .dashboard-icon-red {
        background: #fee2e2;
    }
    
    .dashboard-icon-red-text {
        color: #dc2626;
    }
    
    .dashboard-icon-orange-text {
        color: #ea580c;
    }
    
    .dashboard-icon-purple-text {
        color: #9333ea;
    }
    
    /* Badges - Light Mode */
    .dashboard-badge-yellow {
        background: #fef3c7;
        color: #92400e;
    }
    
    .dashboard-badge-green {
        background: #d1fae5;
        color: #065f46;
    }
    
    .dashboard-badge-red {
        background: #fee2e2;
        color: #991b1b;
    }
    
    /* Quick Actions - Light Mode */
    .dashboard-quick-action-blue {
        background: linear-gradient(to bottom right, #eff6ff, #dbeafe);
    }
    
    .dashboard-quick-action-orange {
        background: linear-gradient(to bottom right, #fff7ed, #fed7aa);
    }
    
    .dashboard-quick-action-purple {
        background: linear-gradient(to bottom right, #faf5ff, #f3e8ff);
    }
    
    /* Dark Mode Styles */
    html.dark-theme .dashboard-card {
        background: #1e293b;
    }
    
    html.dark-theme .dashboard-border {
        border-color: #334155;
    }
    
    html.dark-theme .dashboard-text-primary {
        color: #f1f5f9;
    }
    
    html.dark-theme .dashboard-text-secondary {
        color: #94a3b8;
    }
    
    /* Icon Backgrounds - Dark Mode */
    html.dark-theme .dashboard-icon-blue {
        background: rgba(37, 99, 235, 0.2);
    }
    
    html.dark-theme .dashboard-icon-blue-text {
        color: #60a5fa;
    }
    
    html.dark-theme .dashboard-icon-yellow {
        background: rgba(234, 179, 8, 0.2);
    }
    
    html.dark-theme .dashboard-icon-yellow-text {
        color: #fbbf24;
    }
    
    html.dark-theme .dashboard-icon-green {
        background: rgba(16, 185, 129, 0.2);
    }
    
    html.dark-theme .dashboard-icon-green-text {
        color: #34d399;
    }
    
    html.dark-theme .dashboard-icon-red {
        background: rgba(239, 68, 68, 0.2);
    }
    
    html.dark-theme .dashboard-icon-red-text {
        color: #f87171;
    }
    
    html.dark-theme .dashboard-icon-orange-text {
        color: #fb923c;
    }
    
    html.dark-theme .dashboard-icon-purple-text {
        color: #c084fc;
    }
    
    /* Badges - Dark Mode */
    html.dark-theme .dashboard-badge-yellow {
        background: rgba(234, 179, 8, 0.2);
        color: #fbbf24;
    }
    
    html.dark-theme .dashboard-badge-green {
        background: rgba(16, 185, 129, 0.2);
        color: #34d399;
    }
    
    html.dark-theme .dashboard-badge-red {
        background: rgba(239, 68, 68, 0.2);
        color: #f87171;
    }
    
    /* Quick Actions - Dark Mode */
    html.dark-theme .dashboard-quick-action-blue {
        background: rgba(37, 99, 235, 0.1);
        border-color: #1e40af;
    }
    
    html.dark-theme .dashboard-quick-action-orange {
        background: rgba(234, 88, 12, 0.1);
        border-color: #c2410c;
    }
    
    html.dark-theme .dashboard-quick-action-purple {
        background: rgba(147, 51, 234, 0.1);
        border-color: #7e22ce;
    }

    /* Animations */
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-slide-in {
        animation: slideInUp 0.6s ease-out forwards;
    }

    /* Card hover effects */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .hover-lift:hover {
        transform: translateY(-8px);
    }

    /* Gradient text */
    .gradient-text {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
</style>
@endpush

@push('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart data from backend
        const chartData = @json($chartData);

        // Check if user prefers dark mode
        const isDarkMode = document.documentElement.classList.contains('dark-theme');
        
        // Chart.js default colors based on theme
        const textColor = isDarkMode ? '#94a3b8' : '#64748b';
        const gridColor = isDarkMode ? '#334155' : '#e2e8f0';
        
        // Line Chart Configuration
        const lineCtx = document.getElementById('transactionLineChart');
        if (lineCtx) {
            const lineChart = new Chart(lineCtx, {
                type: 'line',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Deposits',
                            data: chartData.deposits,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#3b82f6',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        },
                        {
                            label: 'Withdraws',
                            data: chartData.withdraws,
                            borderColor: '#f97316',
                            backgroundColor: 'rgba(249, 115, 22, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 5,
                            pointHoverRadius: 7,
                            pointBackgroundColor: '#f97316',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                            titleColor: isDarkMode ? '#f1f5f9' : '#0f172a',
                            bodyColor: isDarkMode ? '#cbd5e1' : '#475569',
                            borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ৳' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        // Bar Chart Configuration
        const barCtx = document.getElementById('transactionBarChart');
        if (barCtx) {
            const barChart = new Chart(barCtx, {
                type: 'bar',
                data: {
                    labels: chartData.labels,
                    datasets: [
                        {
                            label: 'Deposits',
                            data: chartData.deposits,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: '#3b82f6',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        },
                        {
                            label: 'Withdraws',
                            data: chartData.withdraws,
                            backgroundColor: 'rgba(249, 115, 22, 0.8)',
                            borderColor: '#f97316',
                            borderWidth: 2,
                            borderRadius: 8,
                            borderSkipped: false,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#1e293b' : '#fff',
                            titleColor: isDarkMode ? '#f1f5f9' : '#0f172a',
                            bodyColor: isDarkMode ? '#cbd5e1' : '#475569',
                            borderColor: isDarkMode ? '#334155' : '#e2e8f0',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ৳' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor,
                                drawBorder: false
                            },
                            ticks: {
                                color: textColor,
                                callback: function(value) {
                                    return '৳' + value.toLocaleString();
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    }
                }
            });
        }

        // Animate cards on load
        const cards = document.querySelectorAll('[class*="bg-gradient"]');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.style.opacity = '0';
                card.style.transform = 'translateY(30px)';
                
                requestAnimationFrame(() => {
                    card.style.transition = 'all 0.6s cubic-bezier(0.4, 0, 0.2, 1)';
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                });
            }, index * 100);
        });

        // Add ripple effect to buttons
        document.querySelectorAll('a[class*="group"]').forEach(button => {
            button.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.width = ripple.style.height = size + 'px';
                ripple.style.left = x + 'px';
                ripple.style.top = y + 'px';
                ripple.classList.add('ripple');

                this.appendChild(ripple);

                setTimeout(() => ripple.remove(), 600);
            });
        });
    });
</script>
@endpush
