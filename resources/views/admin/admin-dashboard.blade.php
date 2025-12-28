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
                <div class="flex items-center space-x-4 mt-4">
                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z"/>
                        </svg>
                        <span class="text-white font-semibold">{{ now()->format('l, M d, Y') }}</span>
                    </div>
                    <div class="flex items-center space-x-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-lg">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-white font-semibold">System Online</span>
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
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8"
        </div>

        {{-- MFS Total --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-lime-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-lime-400/20 to-green-400/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-lime-500 to-green-600 flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">MFS Total</h3>
                    <p class="text-3xl font-extrabold text-gray-900 dark:text-white mb-2">৳{{ number_format($total_mfs_request, 0) }}</p>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="px-3 py-1 bg-lime-100 dark:bg-lime-900/30 text-lime-700 dark:text-lime-300 rounded-full font-semibold">All Time MFS</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- MFS Pending --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl p-7 shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 border border-orange-100 dark:border-gray-700">
            <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-400/20 to-amber-400/20 rounded-full -mr-16 -mt-16 group-hover:scale-150 transition-transform duration-700"></div>
            <div class="relative flex items-start justify-between">
                <div class="flex-1">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 flex items-center justify-center shadow-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-500">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">MFS Pending</h3>
                    <p class="text-4xl font-extrabold text-gray-900 dark:text-white mb-2">{{ App\Models\ServiceRequest::where('status', 0)->count() }}</p>
                    <div class="flex items-center space-x-2 text-sm">
                        <span class="px-3 py-1 bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 rounded-full font-semibold">{{ $total_mfs_pending_transection ?? 0 }}% of Total</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        @include('admin.admin_chart')
    </div>

@endsection
