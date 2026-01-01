@extends('admin.layouts.admin_app')
@section('title', 'Merchant Deposit Requests')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                        </svg>
                    </div>
                    Deposit Requests
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage and review all merchant deposit requests</p>
            </div>
            <div class="flex gap-3">
                <button onclick="$('#filter-form-container').slideToggle(300)" class="inline-flex items-center px-4 py-2.5 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-xl text-sm font-semibold text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200 shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div id="filter-form-container" class="mb-6" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-green-500 to-emerald-600 border-b border-green-600">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Advanced Filters
                </h3>
            </div>
            <div class="p-6">
                <form id="filter-form" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 skip-submit-guard">
                    
                    <!-- Show Entries -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Show Entries</label>
                        <div class="relative">
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="rows">
                                <option value="10">10 rows</option>
                                <option value="50" selected>50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="200">200 rows</option>
                            </select>
                        </div>
                    </div>

                    <!-- Transaction ID -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Transaction ID</label>
                        <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="trxid" placeholder="Enter transaction ID">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select name="status" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all">
                            <option value="">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="success">Success</option>
                            <option value="3">Rejected</option>
                        </select>
                    </div>

                    <!-- Customer Name/Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Customer Info</label>
                        <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="cust_name" placeholder="Name or number">
                    </div>

                    <!-- Method Number -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Method Number</label>
                        <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="method_number" placeholder="Enter method number">
                    </div>

                    <!-- Reference -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Reference</label>
                        <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="reference" placeholder="Enter reference">
                    </div>

                    <!-- MFS -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">MFS Provider</label>
                        <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="mfs">
                            <option value="">All Providers</option>
                            <option value="nagad">NAGAD</option>
                            <option value="bkash">bKash</option>
                            <option value="16216">Rocket</option>
                            <option value="upay">Upay</option>
                        </select>
                    </div>

                    <!-- Payment Type -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Payment Type</label>
                        <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="payment_type">
                            <option value="">All Types</option>
                            <option value="P2A">P2A - Cash Out</option>
                            <option value="P2P">P2P - Send Money</option>
                            <option value="P2C">P2C - Payment</option>
                        </select>
                    </div>

                    <!-- Merchant -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Merchant</label>
                        <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all select2" name="merchant_id">
                            <option value="">All Merchants</option>
                            @foreach($merchants as $merchant)
                                <option value="{{ $merchant->id }}">{{ $merchant->fullname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="start_date">
                    </div>

                    <!-- End Date -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="end_date">
                    </div>

                    <!-- Search Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- DataTable Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full" id="deposit_table">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 border-b-2 border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">#</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Merchant</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Method</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">MFS/Trx</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Fee/Comm</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Balance</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700"></tbody>
            </table>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0 shadow-2xl dark:bg-gray-800">
            <div class="modal-header bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-t-2xl border-0">
                <h5 class="modal-title font-bold flex items-center gap-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Deposit Details
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-6">
                <div class="overflow-hidden rounded-xl border border-gray-200 dark:border-gray-700">
                    <table class="w-full text-sm">
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800 w-1/3">Payment ID</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_payment_id"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Request ID</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_request_id"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">TRX ID</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_trxid"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Name</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_merchant_name"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Designation</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_designation"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Customer Phone</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_cust_phone"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Reference</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_reference"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Payment Method</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_payment_method"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Payment Type</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_payment_type"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Payment Trx</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_payment_trx"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Amount</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 font-bold" id="detail_amount"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Fee</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_fee"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Commission</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_commission"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Balance Change</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_balance_change"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">From Number</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_from_number"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Note</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_note"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Status</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_status"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Accepted By</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_accepted_by"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Callback URL</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-xs break-all" id="detail_callback_url"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Webhook URL</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100 text-xs break-all" id="detail_webhook_url"></td>
                            </tr>
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                <th class="px-4 py-3 text-left font-semibold text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-800">Created At</th>
                                <td class="px-4 py-3 text-gray-900 dark:text-gray-100" id="detail_created_at"></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-0 px-6 pb-6">
                <button type="button" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="reject_modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0 shadow-2xl dark:bg-gray-800">
            <form id="reject_form">
                @csrf
                <div class="modal-header bg-gradient-to-r from-red-500 to-red-600 text-white rounded-t-2xl border-0">
                    <h5 class="modal-title font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        Reject Transaction
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-6">
                    <input type="hidden" name="transId" id="modal_id">
                    
                    <div class="mb-4">
                        <label for="reason" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                        <textarea id="reason" name="reason" rows="3" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all resize-none" placeholder="Enter the reason for rejection..." required></textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This reason will be visible to the merchant.</p>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-6 pb-6 gap-3">
                    <button type="button" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">Reject Transaction</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Approve Modal -->
<div class="modal fade" id="spamModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="spamForm">
            @csrf
            <div class="modal-content rounded-2xl border-0 shadow-2xl dark:bg-gray-800">
                <div class="modal-header bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-t-2xl border-0">
                    <h5 class="modal-title font-bold flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Approve Payment Request
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                
                <div class="modal-body p-6">
                    <input type="hidden" name="payment_id" id="spam_payment_id">
                    
                    <div class="mb-4 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                        <div class="flex gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                <strong>Note:</strong> Fees and commissions will be automatically calculated based on the merchant's configured rates for the selected MFS operator.
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label for="spam_mfs_operator_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                MFS Operator <span class="text-red-500">*</span>
                            </label>
                            <select class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="mfs_operator_id" id="spam_mfs_operator_id" required>
                                <option value="">Select MFS Operator</option>
                                @foreach($mfsOperators as $operator)
                                    <option value="{{ $operator->id }}" data-name="{{ $operator->name }}" data-type="{{ $operator->type }}">
                                        {{ $operator->name }} ({{ $operator->type }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label for="spam_sim_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                SIM ID / Method Number <span class="text-gray-400">(Optional)</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="sim_id" id="spam_sim_id" placeholder="Enter SIM ID or method number">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep the existing SIM ID</p>
                        </div>
                        
                        <div>
                            <label for="spam_payment_trx" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Payment Method Trx <span class="text-red-500">*</span>
                            </label>
                            <input type="text" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="payment_method_trx" id="spam_payment_trx" placeholder="Enter transaction ID" required>
                        </div>
                        
                        <div>
                            <label for="spam_amount" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Amount <span class="text-gray-400">(Optional)</span>
                            </label>
                            <input type="number" step="0.01" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all" name="amount" id="spam_amount" placeholder="Enter amount">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty to keep the original amount</p>
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0 px-6 pb-6 gap-3">
                    <button type="button" class="px-6 py-2.5 bg-gray-500 hover:bg-gray-600 text-white font-semibold rounded-xl transition-all duration-200" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="px-6 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 hover:from-green-600 hover:to-emerald-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200">Approve Payment</button>
                </div>
            </div>
        </form>
    </div>
</div>




@endsection

@push('css')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    /* DataTables Custom Styling with Tailwind */
    #deposit_table {
        border-collapse: separate;
        border-spacing: 0;
    }

    #deposit_table tbody tr {
        transition: all 0.2s ease;
    }

    #deposit_table tbody tr:hover {
        background-color: rgba(16, 185, 129, 0.05) !important;
        transform: scale(1.001);
    }

    #deposit_table tbody td {
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e5e7eb;
        font-size: 0.875rem;
        color: #374151;
    }

    .dark #deposit_table tbody td {
        border-bottom: 1px solid #374151;
        color: #e5e7eb;
    }

    .dataTables_wrapper .dataTables_processing {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: auto;
        padding: 20px 40px;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(16, 185, 129, 0.3);
        z-index: 9999;
        border: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 1rem;
        margin: 0 0.25rem;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
        background: white;
        color: #374151;
        transition: all 0.2s;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: #10b981;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: #10b981;
    }

    .dataTables_wrapper .dataTables_info {
        padding: 1rem 0;
        color: #6b7280;
        font-size: 0.875rem;
    }

    .dataTables_wrapper .dataTables_length select,
    .dataTables_wrapper .dataTables_filter input {
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        border: 1px solid #e5e7eb;
        margin: 0 0.5rem;
    }

    /* Select2 Tailwind Styling */
    .select2-container--default .select2-selection--single {
        height: 42px;
        border-radius: 0.75rem;
        border: 1px solid #d1d5db;
        padding: 0.5rem 1rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 26px;
        color: #111827;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 40px;
    }

    /* Dark mode for select2 */
    .dark .select2-container--default .select2-selection--single {
        background-color: #374151;
        border-color: #4b5563;
    }

    .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #e5e7eb;
    }

    /* Badge styles */
    .badge {
        padding: 0.375rem 0.75rem;
        border-radius: 0.5rem;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-block;
    }

    .badge.bg-success { background-color: #10b981; color: white; }
    .badge.bg-warning { background-color: #f59e0b; color: white; }
    .badge.bg-danger { background-color: #ef4444; color: white; }
    .badge.bg-info { background-color: #3b82f6; color: white; }
</style>
@endpush

@push('js')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {

    // Initialize Bootstrap tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Serialize form to object
    $.fn.serializeObject = function(){
        let obj = {};
        let arr = this.serializeArray();
        $.each(arr, function() {
            if(obj[this.name] !== undefined){
                if(!Array.isArray(obj[this.name])) obj[this.name] = [obj[this.name]];
                obj[this.name].push(this.value || '');
            } else obj[this.name] = this.value || '';
        });
        return obj;
    };

    // Initialize DataTable
    let table = $('#deposit_table').DataTable({
        processing: true,
        serverSide: true,
        pageLength: 50, // default rows
        ajax: {
            url: "{{ route('deposit') }}",
            data: function(d){
                return $.extend({}, d, $('#filter-form').serializeObject());
            }
        },
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable:false, searchable:false},
            {data: 'merchant_name', name: 'merchant_name'},
            {data: 'payment_method', name: 'payment_method'},
            {data: 'payment_method_trx', name: 'payment_method_trx'},
            {data: 'amount', name: 'amount'},
            {data: 'fee_commission', name: 'fee_commission', orderable:false, searchable:false},
            {data: 'balance_change', name: 'balance_change', orderable:false, searchable:false},
            {data: 'dates', name: 'dates'},
            {data: 'status_html', name: 'status', orderable:false, searchable:false},
            {data: 'action', name: 'action', orderable:false, searchable:false}
        ],
        language: {
            processing: "<span>Please wait...</span>"
        },
        preDrawCallback: function(settings) {
            // Disable button while table is loading
            $('#filter-form button[type="submit"]').prop('disabled', true).text('Please wait...');
        },
        drawCallback: function(settings) {
            // Re-enable button after table has loaded
            $('#filter-form button[type="submit"]').prop('disabled', false).text('Search');
        }
    });

    // Filter form submit
    $('#filter-form').on('submit', function(e){
        e.preventDefault();
        table.ajax.reload();
    });

    // Select2 init
    $('.select2').select2({width: '100%'});
});


$(document).on('click', '.rejectPaymentBtn', function() {
    const paymentId = $(this).data('payment-id');
    $('#modal_id').val(paymentId);
    $('#reason').val(''); // clear previous reason
    const modal = new bootstrap.Modal(document.getElementById('reject_modal'));
    modal.show();
});

$(document).on('click', '.viewPaymentBtn', function() {
    const data = this.dataset;
    const setDetail = (id, value) => $(id).text(value || '-');

    setDetail('#detail_payment_id', data.paymentId);
    setDetail('#detail_request_id', data.requestId);
    setDetail('#detail_trxid', data.trxid);
    setDetail('#detail_merchant_name', data.merchantName);
    setDetail('#detail_designation', data.designation);
    setDetail('#detail_cust_phone', data.custPhone);
    setDetail('#detail_reference', data.reference);
    setDetail('#detail_payment_method', data.paymentMethod);
    setDetail('#detail_payment_type', data.paymentType);
    setDetail('#detail_payment_trx', data.paymentTrx);
    setDetail('#detail_amount', data.amount);
    setDetail('#detail_fee', data.fee);
    setDetail('#detail_commission', data.commission);
    $('#detail_balance_change').html(data.balanceChange || '-');
    setDetail('#detail_from_number', data.fromNumber);
    setDetail('#detail_note', data.note);
    setDetail('#detail_status', data.status);
    setDetail('#detail_accepted_by', data.acceptedBy);
    setDetail('#detail_callback_url', data.callbackUrl);
    setDetail('#detail_webhook_url', data.webhookUrl);
    setDetail('#detail_created_at', data.createdAt);

    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    modal.show();
});

$('#reject_form').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let button = form.find('button[type="submit"]');
    button.attr('disabled', true).text('Submitting...');

    let formData = new FormData(this); // automatically includes CSRF
    // Make sure transId is included
    formData.set('transId', $('#modal_id').val());

    $.ajax({
        url: "{{ route('reject_deposit_request') }}",
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.status === 200) {
                // Close modal
                $('#reject_modal').modal('hide');

                let table = $('#deposit_table').DataTable();
                let row = table.rows().nodes().to$().find(`button[data-payment-id="${$('#modal_id').val()}"]`).closest('tr');
                if (row.length) {
                    $(row).find('td:nth-child(7)').html("<span class='badge bg-danger text-white'>Rejected</span>");
                    $(row).find('td:nth-child(8)').html(""); // remove action buttons
                }

                swal("Success", res.message, "success");
            } else {
                swal("Error", res.message || 'Something went wrong!', "error");
            }

            button.removeAttr('disabled').text('Submit');
        },
        error: function(xhr) {
            let message = "Unknown error";
            if (xhr.responseJSON && xhr.responseJSON.errors) {
                message = Object.values(xhr.responseJSON.errors).map(e => e[0]).join("\n");
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                message = xhr.responseText;
            }

            swal("Error " + xhr.status, message, "error");
            button.removeAttr('disabled').text('Submit');
        }
    });
});

// Reset modal buttons when opened
$('#reject_modal').on('show.bs.modal', function () {
    let button = $(this).find('button[type="submit"]');
    button.removeAttr("disabled").text("Submit");
    $(this).find('form')[0].reset();
});

// Remove the show.bs.modal reset handler - it was clearing values set by spamPaymentBtn
// Reset is now handled when modal is hidden instead

$(document).on('click', '.spamPaymentBtn', function() {
    let paymentId = $(this).data('payment-id');
    let simId = $(this).data('sim-id') || '';
    let paymentMethod = $(this).data('payment-method') || '';
    let paymentType = $(this).data('payment-type') || '';
    let paymentTrx = $(this).data('payment-trx') || '';
    let amount = $(this).data('amount') || '';
    
    // Debug: Log values to console
    console.log('Payment ID:', paymentId);
    console.log('SIM ID:', simId);
    console.log('Payment Method:', paymentMethod);
    console.log('Payment Type:', paymentType);
    console.log('Payment Trx:', paymentTrx);
    console.log('Amount:', amount);
    
    // Show modal first
    $('#spamModal').modal('show');
    
    // Then set values after a short delay to ensure modal is rendered
    setTimeout(function() {
        $('#spam_payment_id').val(paymentId);
        $('#spam_sim_id').val(simId);
        $('#spam_payment_trx').val(paymentTrx);
        $('#spam_amount').val(amount);
        
        // Set MFS operator based on payment method and type
        if (paymentMethod && paymentType) {
            let operatorFound = false;
            $('#spam_mfs_operator_id option').each(function() {
                let optionName = $(this).data('name');
                let optionType = $(this).data('type');
                if (optionName && optionType && 
                    optionName.toLowerCase() === paymentMethod.toLowerCase() && 
                    optionType.toLowerCase() === paymentType.toLowerCase()) {
                    $('#spam_mfs_operator_id').val($(this).val());
                    operatorFound = true;
                    console.log('MFS Operator found and set:', $(this).val());
                    return false; // break loop
                }
            });
            
            if (!operatorFound) {
                $('#spam_mfs_operator_id').val('');
                console.log('MFS Operator not found');
            }
        } else {
            $('#spam_mfs_operator_id').val('');
        }
        
        console.log('Form values set successfully');
    }, 100);
});

// Submit Spam form via AJAX
$('#spamForm').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let button = form.find('button[type="submit"]');

    button.attr("disabled", true).html("Submitting...");

    $.ajax({
        url: "{{ route('approve_deposit_request') }}",
        method: "POST",
        data: form.serialize(),
        dataType: "json",
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            if (res.success) {
                $('#spamModal').modal('hide');
                let table = $('#deposit_table').DataTable();
                let row = table.rows().nodes().to$().find(`button[data-payment-id="${res.payment_id}"]`).closest('tr');
                if (row.length) {
                    $(row).find('td:nth-child(7)').html("<span class='badge bg-success text-white'>Approved</span>");
                    $(row).find('td:nth-child(8)').html("");
                    $(row).css('background-color', '#d4edda').animate({ backgroundColor: '' }, 2000);
                }
                swal("Success", res.message, "success");
            } else {
                swal("Error", res.message, "error");
            }
            button.removeAttr("disabled").html("Submit");
        },
        error: function(xhr, status, err) {
            console.error("XHR:", xhr);
            console.error("Status:", status);
            console.error("Error:", err);

            let message = "Unknown error";
            if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            } else if (xhr.responseText) {
                message = xhr.responseText;
            }

            swal("Error " + xhr.status, message, "error");
            button.removeAttr("disabled").html("Submit");
        }
    });
});

$('#filter-form').on('submit', function(e){
    e.preventDefault();

    let button = $(this).find('button[type="submit"]');
    button.prop('disabled', true).text('Please wait...');

    $('#deposit_table').DataTable().ajax.reload(function(){
        // Re-enable after reload
        button.prop('disabled', false).text('Search');
    });
});



</script>
@endpush
