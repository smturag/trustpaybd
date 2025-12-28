@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

    @php
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

    $total_balance = App\Models\BalanceManager::select([
        DB::raw('MAX(id) as id'),
        'sender',
        'sim',
        DB::raw('MAX(lastbal) as lastbal')
    ])
    ->whereIn('status', ['20', '22', '77'])
    ->groupBy('sender', 'sim')
    ->get()
    ->sum('lastbal');

    $agent_credit = App\Models\MerchantPayoutRequest::where('request_type', 'credit')->where('merchant_type', 'agent')->sum('amount');
    $agent_debit = App\Models\MerchantPayoutRequest::where('request_type', 'debit')->where('merchant_type', 'agent')->sum('amount');
    $merchant_credit = App\Models\MerchantPayoutRequest::where('request_type', 'credit')->where('merchant_type', 'merchant')->sum('amount');
    $merchant_debit = App\Models\MerchantPayoutRequest::where('request_type', 'debit')->where('merchant_type', 'merchant')->sum('amount');
    @endphp

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 mb-6">
        
        {{-- Total Modem --}}
        <div class="group bg-gradient-to-br from-blue-50 via-blue-100 to-cyan-100 rounded-2xl p-6 border-l-4 border-blue-500 hover:shadow-2xl hover:shadow-blue-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-blue-700 mb-1">Total Modem</p>
                    <p class="text-3xl font-bold text-blue-900">{{ $total_modem }}</p>
                    <p class="text-xs text-blue-600 mt-1 flex items-center">
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                        </svg>
                        Only Active
                    </p>
                </div>
            </div>
        </div>

        {{-- Total Agent --}}
        <div class="group bg-gradient-to-br from-amber-50 via-amber-100 to-yellow-100 rounded-2xl p-6 border-l-4 border-amber-500 hover:shadow-2xl hover:shadow-amber-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-amber-500 to-amber-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-amber-700 mb-1">Total Agent</p>
                    <p class="text-3xl font-bold text-amber-900">{{ $total_agent }}</p>
                    <p class="text-xs text-amber-600 mt-1">Active Agents</p>
                </div>
            </div>
        </div>

        {{-- Total Merchant --}}
        <div class="group bg-gradient-to-br from-green-50 via-green-100 to-emerald-100 rounded-2xl p-6 border-l-4 border-green-500 hover:shadow-2xl hover:shadow-green-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-green-500 to-green-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V8a2 2 0 00-2-2h-5L9 4H4zm7 5a1 1 0 10-2 0v1H8a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V9z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-green-700 mb-1">Total Merchant</p>
                    <p class="text-3xl font-bold text-green-900">{{ $total_merchant }}</p>
                    <p class="text-xs text-green-600 mt-1">Active Merchants</p>
                </div>
            </div>
        </div>

        {{-- Payment Today --}}
        <div class="group bg-gradient-to-br from-rose-50 via-rose-100 to-red-100 rounded-2xl p-6 border-l-4 border-rose-500 hover:shadow-2xl hover:shadow-rose-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-rose-500 to-rose-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z"/>
                        <path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-rose-700 mb-1">Payment Today</p>
                    <p class="text-2xl font-bold text-rose-900">৳{{ number_format($total_payment_request_today, 2) }}</p>
                    <p class="text-xs text-rose-600 mt-1">Today's Revenue</p>
                </div>
            </div>
        </div>

        {{-- Payment Total --}}
        <div class="group bg-gradient-to-br from-sky-50 via-sky-100 to-blue-100 rounded-2xl p-6 border-l-4 border-sky-500 hover:shadow-2xl hover:shadow-sky-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-sky-500 to-sky-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-sky-700 mb-1">Payment Total</p>
                    <p class="text-2xl font-bold text-sky-900">৳{{ number_format($total_payment_request, 2) }}</p>
                    <p class="text-xs text-sky-600 mt-1">All Time</p>
                </div>
            </div>
        </div>

        {{-- Payment Pending --}}
        <div class="group bg-gradient-to-br from-purple-50 via-purple-100 to-violet-100 rounded-2xl p-6 border-l-4 border-purple-500 hover:shadow-2xl hover:shadow-purple-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-purple-500 to-purple-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-purple-700 mb-1">Payment Pending</p>
                    <p class="text-3xl font-bold text-purple-900">{{ App\Models\PaymentRequest::where('status', 0)->count() }}</p>
                    <p class="text-xs text-purple-600 mt-1">{{ $total_payments_pending_transection ?? 0 }}% of Total</p>
                </div>
            </div>
        </div>

        {{-- MFS Today --}}
        <div class="group bg-gradient-to-br from-cyan-50 via-cyan-100 to-teal-100 rounded-2xl p-6 border-l-4 border-cyan-500 hover:shadow-2xl hover:shadow-cyan-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-cyan-500 to-cyan-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-cyan-700 mb-1">MFS Today</p>
                    <p class="text-2xl font-bold text-cyan-900">৳{{ number_format($today_total_mfs_request, 2) }}</p>
                    <p class="text-xs text-cyan-600 mt-1">Today's MFS</p>
                </div>
            </div>
        </div>

        {{-- MFS Total --}}
        <div class="group bg-gradient-to-br from-lime-50 via-lime-100 to-green-100 rounded-2xl p-6 border-l-4 border-lime-500 hover:shadow-2xl hover:shadow-lime-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-lime-500 to-lime-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-lime-700 mb-1">MFS Total</p>
                    <p class="text-2xl font-bold text-lime-900">৳{{ number_format($total_mfs_request, 2) }}</p>
                    <p class="text-xs text-lime-600 mt-1">All Time MFS</p>
                </div>
            </div>
        </div>

        {{-- MFS Pending --}}
        <div class="group bg-gradient-to-br from-orange-50 via-orange-100 to-amber-100 rounded-2xl p-6 border-l-4 border-orange-500 hover:shadow-2xl hover:shadow-orange-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-orange-500 to-orange-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-orange-700 mb-1">MFS Pending</p>
                    <p class="text-3xl font-bold text-orange-900">{{ App\Models\ServiceRequest::where('status', 0)->count() }}</p>
                    <p class="text-xs text-orange-600 mt-1">{{ $total_mfs_pending_transection ?? 0 }}% of Total</p>
                </div>
            </div>
        </div>

        {{-- Agent Credit --}}
        <div class="group bg-gradient-to-br from-indigo-50 via-indigo-100 to-blue-100 rounded-2xl p-6 border-l-4 border-indigo-500 hover:shadow-2xl hover:shadow-indigo-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-indigo-700 mb-1">Agent Credit</p>
                    <p class="text-2xl font-bold text-indigo-900">৳{{ number_format($agent_credit, 2) }}</p>
                    <p class="text-xs text-indigo-600 mt-1">Total Credits</p>
                </div>
            </div>
        </div>

        {{-- Agent Debit --}}
        <div class="group bg-gradient-to-br from-red-50 via-red-100 to-rose-100 rounded-2xl p-6 border-l-4 border-red-500 hover:shadow-2xl hover:shadow-red-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" transform="rotate(180 10 10)"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-red-700 mb-1">Agent Debit</p>
                    <p class="text-2xl font-bold text-red-900">৳{{ number_format($agent_debit, 2) }}</p>
                    <p class="text-xs text-red-600 mt-1">Total Debits</p>
                </div>
            </div>
        </div>

        {{-- Merchant Credit --}}
        <div class="group bg-gradient-to-br from-yellow-50 via-yellow-100 to-amber-100 rounded-2xl p-6 border-l-4 border-yellow-500 hover:shadow-2xl hover:shadow-yellow-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-yellow-500 to-yellow-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-yellow-700 mb-1">Merchant Credit</p>
                    <p class="text-2xl font-bold text-yellow-900">৳{{ number_format($merchant_credit, 2) }}</p>
                    <p class="text-xs text-yellow-600 mt-1">Total Credits</p>
                </div>
            </div>
        </div>

        {{-- Merchant Debit --}}
        <div class="group bg-gradient-to-br from-violet-50 via-violet-100 to-purple-100 rounded-2xl p-6 border-l-4 border-violet-500 hover:shadow-2xl hover:shadow-violet-200/50 transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-violet-500 to-violet-600 flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v3.586L7.707 9.293a1 1 0 00-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 10.586V7z" transform="rotate(180 10 10)"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-violet-700 mb-1">Merchant Debit</p>
                    <p class="text-2xl font-bold text-violet-900">৳{{ number_format($merchant_debit, 2) }}</p>
                    <p class="text-xs text-violet-600 mt-1">Total Debits</p>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        @include('admin.admin_chart')
    </div>

@endsection
