<!-- Modern Tailwind Sidebar -->
<aside id="sidebar" class="fixed left-0 top-0 h-screen w-64 bg-gradient-to-b from-slate-900 via-slate-800 to-slate-900 text-white transition-all duration-300 ease-in-out overflow-y-auto z-50 shadow-2xl">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between p-6 border-b border-slate-700/50">
        <a href="{{ route('admin_dashboard') }}" class="flex items-center space-x-3 group">
            @php
                $app_name = app_config('AppName');
                $image = app_config('AppLogo');
            @endphp
            <img src="{{ asset('storage/' . $image) }}" class="h-10 w-auto transition-transform duration-300 group-hover:scale-110" alt="logo">
            <span class="text-xl font-bold bg-gradient-to-r from-blue-400 to-cyan-400 bg-clip-text text-transparent sidebar-text">{{ $app_name }}</span>
        </a>
        <button id="sidebarToggle" class="lg:hidden text-gray-400 hover:text-white transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="px-4 py-6 space-y-2">
        <!-- Dashboard -->
        <a href="{{ route('admin_dashboard') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin_dashboard') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <svg class="w-5 h-5 text-blue-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span class="font-medium sidebar-text">Dashboard</span>
        </a>

        <!-- Deposit -->
        <a href="{{ route('deposit') }}" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('deposit') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-green-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="font-medium sidebar-text">Deposit</span>
            </div>
            @php
                $depositCount = App\Models\PaymentRequest::where('status', 0)->whereNotNull('payment_method')->count();
            @endphp
            @if($depositCount > 0)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $depositCount }}</span>
            @endif
        </a>

        <!-- Withdraw -->
        <div x-data="{ open: {{ request()->routeIs('serviceReq') ? 'true' : 'false' }} }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-orange-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Withdraw</span>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $pending = App\Models\ServiceRequest::where('status', 0)->count();
                        $wating = App\Models\ServiceRequest::where('status', 1)->count();
                        $processing = App\Models\ServiceRequest::where('status', 5)->count();
                        $failed = App\Models\ServiceRequest::where('status', 6)->count();
                        $MakeCount = $pending + $wating + $processing + $failed;
                    @endphp
                    @if($MakeCount > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $MakeCount }}</span>
                    @endif
                    <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('serviceReq', 'all') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'all' ? 'text-cyan-400' : 'text-gray-300' }}">All Request</a>
                <a href="{{ route('serviceReq', 'success') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'success' ? 'text-cyan-400' : 'text-gray-300' }}">Success</a>
                <a href="{{ route('serviceReq', 'waiting') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'waiting' ? 'text-cyan-400' : 'text-gray-300' }}">
                    <span>Waiting</span>
                    @if($wating > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $wating }}</span>
                    @endif
                </a>
                <a href="{{ route('serviceReq', 'pending') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'pending' ? 'text-cyan-400' : 'text-gray-300' }}">
                    <span>Pending</span>
                    @if($pending > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pending }}</span>
                    @endif
                </a>
                <a href="{{ route('serviceReq', 'rejected') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'rejected' ? 'text-cyan-400' : 'text-gray-300' }}">Reject</a>
                <a href="{{ route('serviceReq', 'processing') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'processing' ? 'text-cyan-400' : 'text-gray-300' }}">
                    <span>Processing</span>
                    @if($processing > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $processing }}</span>
                    @endif
                </a>
                <a href="{{ route('serviceReq', 'failed') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors {{ request()->route()->parameter('status') == 'failed' ? 'text-cyan-400' : 'text-gray-300' }}">
                    <span>Failed</span>
                    @if($failed > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $failed }}</span>
                    @endif
                </a>
            </div>
        </div>

        <!-- Crypto Payout -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-yellow-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Crypto Payout</span>
                </div>
                <div class="flex items-center space-x-2">
                    @php
                        $pendingPayouts = App\Models\MerchantPayoutRequest::where('status', 0)->count();
                    @endphp
                    @if($pendingPayouts > 0)
                        <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingPayouts }}</span>
                    @endif
                    <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('admin.merchant-payout.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">All Requests</a>
                <a href="{{ route('admin.merchant-payout.index', ['status' => 0]) }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                    <span>Pending</span>
                    @if($pendingPayouts > 0)
                        <span class="bg-yellow-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingPayouts }}</span>
                    @endif
                </a>
                <a href="{{ route('admin.merchant-payout.index', ['status' => 4]) }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Completed</a>
                <a href="{{ route('admin.merchant-payout.index', ['status' => 3]) }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Rejected</a>
            </div>
        </div>

        <!-- Currency Management -->
        <a href="{{ route('admin.currency.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin.currency.index') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <svg class="w-5 h-5 text-purple-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="font-medium sidebar-text">Currency Management</span>
        </a>

        <!-- Pricing Plans -->
        <a href="{{ route('admin.pricing.index') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin.pricing.*') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <svg class="w-5 h-5 text-yellow-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <span class="font-medium sidebar-text">Pricing Plans</span>
        </a>

        <!-- Balance Manager -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-indigo-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Balance Manager</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('balance_manager', 'all') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">All Transactions</a>
                <a href="{{ route('balance_manager', 'pendings') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                    <span>Pending</span>
                    @php $pendingBM = App\Models\BalanceManager::where('status', 0)->count(); @endphp
                    @if($pendingBM > 0)
                        <span class="bg-blue-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $pendingBM }}</span>
                    @endif
                </a>
                <a href="{{ route('balance_manager', 'waiting') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                    <span>Waiting</span>
                    @php $waitingBM = App\Models\BalanceManager::where('status', 33)->count(); @endphp
                    @if($waitingBM > 0)
                        <span class="bg-cyan-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $waitingBM }}</span>
                    @endif
                </a>
                <a href="{{ route('balance_manager', 'danger') }}" class="flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                    <span>Danger</span>
                    @php $dangerBM = App\Models\BalanceManager::where('status', 55)->count(); @endphp
                    @if($dangerBM > 0)
                        <span class="bg-red-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $dangerBM }}</span>
                    @endif
                </a>
                <a href="{{ route('balance_manager', 'success') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Success</a>
                <a href="{{ route('balance_manager', 'reject') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Reject</a>
            </div>
        </div>

        <!-- SMS Inbox -->
        <a href="{{ route('admin_sms_inbox') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin_sms_inbox') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <svg class="w-5 h-5 text-pink-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
            </svg>
            <span class="font-medium sidebar-text">SMS Inbox</span>
        </a>

        <!-- All Users -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-teal-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="font-medium sidebar-text">All Users</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('merchantList') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Merchant</a>
                <a href="{{ route('customerList') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Customer</a>
                <a href="{{ route('userList') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Members</a>
            </div>
        </div>

        <!-- Method -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-emerald-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Method</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <div x-data="{ openPayment: false }" class="space-y-1">
                    <button @click="openPayment = !openPayment" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                        <span>Payment Method</span>
                        <svg class="w-3 h-3 transform transition-transform" :class="openPayment ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openPayment" x-transition class="ml-3 space-y-1">
                        <a href="{{ route('payment.mobile_banking') }}" class="block px-3 py-2 text-xs rounded hover:bg-slate-700/30 transition-colors text-gray-400">Mobile Banking</a>
                        <a href="{{ route('payment.api_method_list') }}" class="block px-3 py-2 text-xs rounded hover:bg-slate-700/30 transition-colors text-gray-400">API Method</a>
                    </div>
                </div>
                <div x-data="{ openWithdraw: false }" class="space-y-1">
                    <button @click="openWithdraw = !openWithdraw" class="w-full flex items-center justify-between px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">
                        <span>Withdraw Method</span>
                        <svg class="w-3 h-3 transform transition-transform" :class="openWithdraw ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="openWithdraw" x-transition class="ml-3 space-y-1">
                        <a href="{{ route('withdraw.mobile_banking') }}" class="block px-3 py-2 text-xs rounded hover:bg-slate-700/30 transition-colors text-gray-400">Mobile Banking</a>
                        <a href="{{ route('crypto.index') }}" class="block px-3 py-2 text-xs rounded hover:bg-slate-700/30 transition-colors text-gray-400">Crypto Currency</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modem List -->
        <a href="{{ route('admin_modemList') }}" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin_modemList') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <svg class="w-5 h-5 text-red-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
            </svg>
            <span class="font-medium sidebar-text">Modem List</span>
        </a>

        <!-- Activity Logs -->
        <a href="#" class="flex items-center space-x-3 px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
            <svg class="w-5 h-5 text-lime-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span class="font-medium sidebar-text">Activity Logs</span>
        </a>

        <!-- Settings -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-gray-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Settings</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('bulk_sms.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">SMS System</a>
                <a href="{{ route('mfs.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">MFS Operator</a>
                <a href="{{ route('settings.index') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">App Settings</a>
                <a href="{{ route('admin.database.update') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Database Update</a>
            </div>
        </div>

        <!-- Reports -->
        <div x-data="{ open: false }" class="space-y-1">
            <button @click="open = !open" class="w-full flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group">
                <div class="flex items-center space-x-3">
                    <svg class="w-5 h-5 text-sky-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium sidebar-text">Reports</span>
                </div>
                <svg class="w-4 h-4 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
            <div x-show="open" x-transition class="ml-6 space-y-1 border-l-2 border-slate-700 pl-4">
                <a href="{{ route('report.payment_report') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Payment Report</a>
                <a href="{{ route('report.service_report') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Service Report</a>
                <a href="{{ route('report.balance_summary') }}" class="block px-3 py-2 text-sm rounded hover:bg-slate-700/30 transition-colors text-gray-300">Balance Summary</a>
            </div>
        </div>

        <!-- Support -->
        <a href="{{ route('admin.support_list') }}" class="flex items-center justify-between px-4 py-3 rounded-lg hover:bg-slate-700/50 transition-all duration-200 group {{ request()->routeIs('admin.support_list') ? 'bg-gradient-to-r from-blue-600 to-cyan-600 shadow-lg shadow-blue-500/30' : '' }}">
            <div class="flex items-center space-x-3">
                <svg class="w-5 h-5 text-rose-400 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <span class="font-medium sidebar-text">Support</span>
            </div>
            @php
                $supportCount = App\Models\SupportTicket::whereIn('status', [1, 2, 3])->count();
            @endphp
            @if($supportCount > 0)
                <span class="bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">{{ $supportCount }}</span>
            @endif
        </a>
    </nav>

    <!-- Sidebar Footer -->
    <div class="p-4 mt-auto border-t border-slate-700/50">
        <div class="bg-gradient-to-r from-blue-600/20 to-cyan-600/20 border border-blue-500/30 rounded-lg p-3">
            <p class="text-xs text-gray-300 mb-2 sidebar-text">Need Help?</p>
            <a href="#" class="text-xs text-cyan-400 hover:text-cyan-300 transition-colors sidebar-text">Contact Support →</a>
        </div>
    </div>
</aside>

<!-- Overlay for mobile -->
<div id="sidebarOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden"></div>

<!-- Main Content Wrapper -->
<div id="mainContent" class="lg:ml-64 transition-all duration-300 ease-in-out">
    <!-- Add your main content here -->
</div>

<!-- Alpine.js for interactive components -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<script>
    // Sidebar toggle functionality
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const mainContent = document.getElementById('mainContent');

    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');
        sidebarOverlay.classList.toggle('hidden');
    }

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', toggleSidebar);
    }

    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // Close sidebar on mobile when clicking a link
    const sidebarLinks = sidebar.querySelectorAll('a');
    sidebarLinks.forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                toggleSidebar();
            }
        });
    });

    // Responsive sidebar
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
        }
    });
</script>

<style>
    /* Hide sidebar on mobile by default */
    @media (max-width: 1023px) {
        #sidebar {
            transform: translateX(-100%);
        }
        #sidebar:not(.-translate-x-full) {
            transform: translateX(0);
        }
    }

    /* Custom scrollbar for sidebar */
    #sidebar::-webkit-scrollbar {
        width: 6px;
    }

    #sidebar::-webkit-scrollbar-track {
        background: rgba(15, 23, 42, 0.3);
    }

    #sidebar::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 3px;
    }

    #sidebar::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }

    /* Smooth transitions */
    * {
        transition-property: background-color, border-color, color, fill, stroke, opacity, box-shadow, transform;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }
</style>
