@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

    @php
    $total_modem = App\Models\Modem::where('db_status', 'live')->where('status', 1)->count();
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

    <!-- Welcome Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 rounded-2xl p-8 shadow-2xl relative overflow-hidden">
            <div class="absolute inset-0 bg-black opacity-10"></div>
            <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -ml-10 -mb-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
            <div class="relative z-10">
                <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">Welcome back, {{ ucwords(Auth::guard('admin')->user()->admin_name) }}! 👋</h1>
                <p class="text-blue-100 text-lg">Here's what's happening with your business today.</p>
                <div class="flex flex-wrap items-center gap-4 mt-4">
                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                        </svg>
                        <span class="text-white font-semibold text-sm md:text-base">{{ now()->format('l, M d, Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white font-semibold text-sm md:text-base">System Online</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        
        {{-- Total Modem --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-100 dark:bg-blue-900/30 px-3 py-1 rounded-full">Active</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Modems</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $total_modem }}</p>
            <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="font-medium">Online Now</span>
            </div>
        </div>

        {{-- Total Agent --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-purple-600 dark:text-purple-400 bg-purple-100 dark:bg-purple-900/30 px-3 py-1 rounded-full">Verified</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Agents</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $total_agent }}</p>
            <div class="flex items-center text-sm text-purple-600 dark:text-purple-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                </svg>
                <span class="font-medium">Active Users</span>
            </div>
        </div>

        {{-- Total Merchant --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 5a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 px-3 py-1 rounded-full">Live</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Total Merchants</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $total_merchant }}</p>
            <div class="flex items-center text-sm text-green-600 dark:text-green-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                </svg>
                <span class="font-medium">Registered</span>
            </div>
        </div>

        {{-- Payment Pending --}}
        <div class="group relative bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 hover:-translate-y-1 border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-4">
                <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <span class="text-xs font-semibold text-orange-600 dark:text-orange-400 bg-orange-100 dark:bg-orange-900/30 px-3 py-1 rounded-full">Pending</span>
            </div>
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide mb-1">Pending Payments</h3>
            <p class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ App\Models\PaymentRequest::where('status', 0)->count() }}</p>
            <div class="flex items-center text-sm text-orange-600 dark:text-orange-400">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <span class="font-medium">{{ $total_payments_pending_transection ?? 0 }}% of Total</span>
            </div>
        </div>
    </div>

    <!-- Revenue Overview -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Payment Revenue Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">Payment Revenue</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Transaction overview</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-rose-500 to-pink-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-rose-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Today</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">৳{{ number_format($total_payment_request_today, 0) }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">All Time</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">৳{{ number_format($total_payment_request, 0) }}</span>
                </div>
            </div>
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-300">Success Rate</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $total_payments_complete_transection ?? 0 }}%</span>
                </div>
            </div>
        </div>

        <!-- MFS Revenue Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">MFS Revenue</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Mobile Financial Service</p>
                </div>
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-cyan-500 to-teal-600 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
            </div>
            <div class="space-y-4">
                <div class="flex items-center justify-between py-3 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-cyan-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Today</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">৳{{ number_format($today_total_mfs_request, 0) }}</span>
                </div>
                <div class="flex items-center justify-between py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-300">All Time</span>
                    </div>
                    <span class="text-lg font-bold text-gray-900 dark:text-white">৳{{ number_format($total_mfs_request, 0) }}</span>
                </div>
            </div>
            <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-xl">
                <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-300">Success Rate</span>
                    <span class="font-bold text-green-600 dark:text-green-400">{{ $total_mfs_complete_transection ?? 0 }}%</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Transaction Count Card -->
        <div class="bg-gradient-to-br from-indigo-500 to-purple-600 dark:from-indigo-600 dark:to-purple-700 rounded-2xl p-6 shadow-lg text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90 uppercase tracking-wide">Transactions Today</h3>
                <svg class="w-8 h-8 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($total_trx_today) }}</p>
            <div class="flex items-center space-x-2 text-sm opacity-90">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                <span>৳{{ number_format($total_trx_amount_today, 0) }} volume</span>
            </div>
        </div>

        <!-- Total Transactions Card -->
        <div class="bg-gradient-to-br from-emerald-500 to-green-600 dark:from-emerald-600 dark:to-green-700 rounded-2xl p-6 shadow-lg text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90 uppercase tracking-wide">Total Transactions</h3>
                <svg class="w-8 h-8 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a2 2 0 00-2 2v11a3 3 0 106 0V4a2 2 0 00-2-2H4zm1 14a1 1 0 100-2 1 1 0 000 2zm5-1.757l4.9-4.9a2 2 0 000-2.828L13.485 5.1a2 2 0 00-2.828 0L10 5.757v8.486zM16 18H9.071l6-6H16a2 2 0 012 2v2a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($total_trx) }}</p>
            <div class="flex items-center space-x-2 text-sm opacity-90">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                </svg>
                <span>৳{{ number_format($total_trx_amount, 0) }} total</span>
            </div>
        </div>

        <!-- Pending Transactions Card -->
        <div class="bg-gradient-to-br from-amber-500 to-orange-600 dark:from-amber-600 dark:to-orange-700 rounded-2xl p-6 shadow-lg text-white">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold opacity-90 uppercase tracking-wide">Pending Review</h3>
                <svg class="w-8 h-8 opacity-80" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                </svg>
            </div>
            <p class="text-4xl font-bold mb-2">{{ number_format($total_pending) }}</p>
            <div class="flex items-center space-x-2 text-sm opacity-90">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"/>
                </svg>
                <span>Requires attention</span>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 gap-8 mb-8">
        @include('admin.admin_chart_modern')
    </div>

@endsection
