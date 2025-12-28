<!--start header -->
<header class="sticky top-0 z-40 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md shadow-sm transition-all duration-200 border-b border-gray-200 dark:border-gray-700">
    <div class="transition-all duration-300">
        <nav class="relative">
            <div class="flex items-center justify-between h-16 px-4 lg:px-6">
                <!-- Left Section: Mobile Toggle + Logo + Quick Actions -->
                <div class="flex items-center space-x-2 sm:space-x-4">
                    <!-- Mobile Sidebar Toggle -->
                    <button id="mobileSidebarToggle" class="lg:hidden p-2.5 rounded-xl text-gray-600 dark:text-gray-300 hover:bg-gradient-to-br hover:from-blue-500 hover:to-blue-600 hover:text-white transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    
                    <!-- Logo (visible on larger screens) -->
                    <a href="{{ route('admin_dashboard') }}" class="hidden lg:flex items-center space-x-2 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-600 to-purple-600 flex items-center justify-center shadow-md group-hover:shadow-lg transition-all duration-300 group-hover:scale-105">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <span class="font-bold text-xl bg-gradient-to-r from-blue-600 to-purple-600 bg-clip-text text-transparent">Dashboard</span>
                    </a>
                    
                    @php
                        $depositCount = App\Models\PaymentRequest::where('status', 0)->whereNotNull('payment_method')->count();
                        $success = App\Models\ServiceRequest::whereIn('status', [2, 3])->count();
                        $pending = App\Models\ServiceRequest::where('status', 0)->count();
                        $wating = App\Models\ServiceRequest::where('status', 1)->count();
                        $reject = App\Models\ServiceRequest::where('status', 4)->count();
                        $processing = App\Models\ServiceRequest::where('status', 5)->count();
                        $failed = App\Models\ServiceRequest::where('status', 6)->count();
                        $MakeCount = $pending + $wating + $processing + $failed;
                    @endphp
                    
                    <!-- Deposit Quick Link -->
                    <a href="{{ route('admin.merchant.payment-request') }}" class="group relative hidden sm:flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                        <span class="font-semibold text-sm text-white">Deposit</span>
                        @if($depositCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg animate-pulse min-w-[20px] text-center ring-2 ring-white">{{ $depositCount }}</span>
                        @endif
                    </a>

                    <!-- Withdraw Quick Link -->
                    <a href="{{ route('serviceReq', 'all') }}" class="group relative hidden sm:flex items-center space-x-2 px-4 py-2.5 bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold text-sm text-white">Withdraw</span>
                        @if($MakeCount > 0)
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full shadow-lg animate-pulse min-w-[20px] text-center ring-2 ring-white">{{ $MakeCount }}</span>
                        @endif
                    </a>
                </div>

                <!-- Right Section: User Actions -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    
                    <!-- Support Ticket Notifications -->
                    @include('admin.partials.notification_dropdown')

                    <!-- Dark Mode Toggle -->
                    <button id="darkModeToggle" class="hidden sm:block p-2.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 group">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-300 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                        </svg>
                    </button>

                    <!-- User Profile Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="flex items-center space-x-2 p-1 sm:p-1.5 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-all duration-200 group">
                            <img src="{{ Auth::guard('admin')->user()->profile_pic ? asset('storage/'.Auth::guard('admin')->user()->profile_pic) : asset('static/backend/images/avatars/avatar-2.png') }}" class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl object-cover ring-2 ring-gray-200 dark:ring-gray-700 group-hover:ring-blue-500 dark:group-hover:ring-blue-400 transition-all duration-200" alt="avatar" />
                            <div class="hidden md:block text-left">
                                <p class="font-bold text-sm text-gray-800 dark:text-gray-200 leading-tight">{{ ucwords(Auth::guard('admin')->user()->admin_name) }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 leading-tight">{{ ucwords(Auth::guard('admin')->user()->type) }}</p>
                            </div>
                            <svg class="hidden sm:block w-4 h-4 text-gray-400 dark:text-gray-500 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95 translate-y-1" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95" class="absolute right-0 mt-3 w-64 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden z-50" style="display: none;">
                            <div class="px-5 py-4 bg-gradient-to-r from-blue-500 to-purple-600 border-b border-gray-200 dark:border-gray-700">
                                <p class="font-bold text-sm text-white">{{ ucwords(Auth::guard('admin')->user()->admin_name) }}</p>
                                <p class="text-xs text-blue-100 mt-0.5">{{ Auth::guard('admin')->user()->email ?? 'Admin' }}</p>
                            </div>
                            <div class="py-1">
                                <a href="{{ route('admin.profile') }}" class="flex items-center space-x-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white font-medium">Profile</span>
                                </a>
                                <a href="{{ route('reset_balance') }}" class="flex items-center space-x-3 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors group">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white font-medium">Reset Balance</span>
                                </a>
                            </div>
                            <div class="border-t border-gray-200 dark:border-gray-600">
                                <a href="{{ route('adminlogout') }}" class="flex items-center space-x-3 px-5 py-3 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors group">
                                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400 group-hover:text-red-600 dark:group-hover:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span class="text-sm text-gray-700 dark:text-gray-300 group-hover:text-red-600 dark:group-hover:text-red-500 font-medium">Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

<style>
    /* Theme transition */
    html, body, .card, .widgets-icons, .sidebar-wrapper, .topbar,
    .page-wrapper, .page-content, .footer, * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* Light theme styles */
    body.light-theme {
        background-color: #f9fbfd;
        color: #4c5258;
    }

    /* Dark mode styles */
    body.dark-theme {
        background-color: #1a1f23;
        color: #e4e5e6;
    }

    /* Dark mode toggle button styles */
    .dark-mode-icon {
        cursor: pointer;
        transition: transform 0.3s ease;
        position: relative;
    }

    .dark-mode-icon:hover {
        transform: rotate(30deg);
    }

    /* Animation for theme change */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .theme-changing {
        animation: pulse 0.5s ease;
    }

    .theme-changing i {
        transition: all 0.3s ease;
    }

    /* Improved card styles for both themes */
    .card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    /* Light theme card */
    html.light-theme .card {
        background-color: #ffffff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Dark theme card */
    html.dark-theme .card {
        background-color: #252a30;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Widget icons styling */
    .widgets-icons {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
        transition: all 0.3s;
    }

    /* Improved text colors for dark mode */
    html.dark-theme .text-secondary {
        color: #d1d3d5 !important;
    }

    html.dark-theme .text-info {
        color: #5bc0de !important;
    }

    html.dark-theme .text-success {
        color: #42d29d !important;
    }

    html.dark-theme .text-primary {
        color: #6c9fff !important;
    }

    /* Improved background colors for dark mode */
    html.dark-theme .bg-light-success {
        background-color: rgba(40, 199, 111, 0.2) !important;
    }

    html.dark-theme .bg-light-warning {
        background-color: rgba(255, 159, 67, 0.2) !important;
    }

    html.dark-theme .bg-light-danger {
        background-color: rgba(234, 84, 85, 0.2) !important;
    }

    html.dark-theme .bg-light-primary {
        background-color: rgba(115, 103, 240, 0.2) !important;
    }
</style>
