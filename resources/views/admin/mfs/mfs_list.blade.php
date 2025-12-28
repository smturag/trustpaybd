@extends('admin.layouts.admin_app')
@section('title', 'Service Requests')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
    <style>
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
    </style>
@endpush

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-6 px-4 sm:px-6 lg:px-8">
    
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                    <div class="w-12 h-12 bg-gradient-to-br from-orange-500 to-red-600 rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    Service Requests
                </h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage withdrawal and service requests from merchants</p>
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

    @if (session('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 flex items-start gap-3">
            <div class="flex-shrink-0">
                <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div>
                <h6 class="font-semibold text-green-800 dark:text-green-300">Success</h6>
                <p class="text-sm text-green-700 dark:text-green-400">{{ session('message') }}</p>
            </div>
            <button type="button" class="ml-auto text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300" onclick="this.parentElement.remove()">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
        </div>
    @endif
    <!-- Filters Card -->
    <div id="filter-form-container" class="mb-6" style="display: none;">
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-red-600 border-b border-orange-600">
                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                    </svg>
                    Advanced Filters
                </h3>
            </div>
            <div class="p-6">
                <form action="#" role="form" method="post" id="search-form" accept-charset="utf-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                        
                        <!-- Show Entries -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Show Entries</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="rows" id="rows">
                                <option value="50">50 rows</option>
                                <option value="100">100 rows</option>
                                <option value="150">150 rows</option>
                                <option value="200">200 rows</option>
                                <option value="400">400 rows</option>
                                <option value="500">500 rows</option>
                            </select>
                        </div>

                        <!-- Merchant -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Merchant</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all select2" name="merchant_id" id="merchant_id">
                                <option value="">All Merchants</option>
                                @foreach ($merchants as $key => $merchant)
                                    <option value="{{ $merchant->id }}">{{ $merchant->fullname }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- MFS Provider -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">MFS Provider</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="mfs" id="mfs">
                                <option value="">All Providers</option>
                                <option value="NAGAD">NAGAD</option>
                                <option value="bKash">bKash</option>
                                <option value="16216">Rocket</option>
                                <option value="upay">Upay</option>
                            </select>
                        </div>

                        <!-- Date From -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all datepicker" name="from" id="date1" placeholder="Select start date" value="<?php echo $from; ?>" autocomplete="off">
                        </div>

                        <!-- Date To -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all datepicker" name="to" id="date2" placeholder="Select end date" value="<?php echo $to; ?>" autocomplete="off">
                        </div>

                        <!-- Agent Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Agent Number</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" id="single-select-field" name="simNumber" onchange="return redirectToSimNumber(this.options[this.selectedIndex].value);">
                                <option value="">All SIM Numbers</option>
                                @foreach ($sim as $simNumber)
                                    @if ($simNumber->sim != '')
                                        <option value="{{ $simNumber->sim }}" @selected($simNumber->sim == Request::segment(4))>
                                            {{ $simNumber->sim }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="status" id="status">
                                <option value="">All Status</option>
                                <option value="all">All</option>
                                <option value="success">Success</option>
                                <option value="waiting">Waiting</option>
                                <option value="pending">Pending</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                                <option value="processing">Processing</option>
                                <option value="failed">Failed</option>
                            </select>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="type" id="type">
                                <option value="">All Types</option>
                                <option value="cashout">Cash Out</option>
                                <option value="cashin">Cash In</option>
                            </select>
                        </div>

                        <!-- TRX ID -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">TRX ID</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="trxid" id="trxid" placeholder="Enter TRX ID">
                        </div>

                        <!-- Response TRX ID -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Response TRX ID</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="response_trxid" id="response_trxid" placeholder="Enter response TRX ID">
                        </div>

                        <!-- Customer Number -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Customer Number</label>
                            <input type="text" class="w-full px-4 py-2.5 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="cNumber" id="cNumber" placeholder="Enter customer number">
                        </div>

                        <!-- Search Button -->
                        <div class="flex items-end">
                            <button type="submit" class="w-full px-6 py-2.5 bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Search
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 overflow-hidden">
        <!-- Bulk Actions -->
        <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600">
            <div class="flex flex-col sm:flex-row gap-4 items-start sm:items-center">
                <div class="flex-1">
                    <input type="text" class="w-full px-4 py-2.5 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-orange-500 focus:border-transparent transition-all" name="reason" placeholder="Rejection reason (if any)">
                </div>
                <div class="relative inline-block">
                    <button class="inline-flex items-center px-6 py-2.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-200 gap-2" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        Bulk Actions
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item action-button" href="#" data-action="reject">Reject Selected</a></li>
                        <li><a class="dropdown-item action-button" href="#" data-action="resend">Resend Selected</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto" id="tableContentWrap">
            @include('admin.mfs.mfs_table_content')
        </div>
    </div>
</div>

<!-- View Modal -->
<div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content rounded-2xl border-0 shadow-2xl dark:bg-gray-800" id="myModalContent"></div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="decline_transection" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-2xl border-0 shadow-2xl dark:bg-gray-800">
            <form action="{{ route('service.reject_req') }}" method="POST">
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
                    <input class="form-control" type="text" name="transId" id="modal_id" hidden>
                    <input id="usertype" type="text" name="user_type" class="form-control" hidden>
                    <input id="user_id" type="text" name="user_id" class="form-control" hidden>
                    
                    <div>
                        <label for="reason_or_trx" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Rejection Reason *</label>
                        <textarea id="reason_or_trx" name="reason_or_trx" rows="3" class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-xl text-sm text-gray-900 dark:text-white placeholder-gray-400 focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all resize-none" placeholder="Enter the reason for rejection..." required></textarea>
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

@endsection


@push('js')
    <script type="text/javascript">
        $(document).ready(function() {

            $(document).on('click', '.openPopup', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                //var dataURL = $(this).attr('data-href');
                var dataURL = "{{ url('/admin/approved_req') }}/" + id;

                $('#myModalContent').load(dataURL, function() {
                    $('#myModal').modal({
                        show: true
                    });
                });
            });

            $(document).on('click', '.openDetails', function(e) {
                e.preventDefault();
                var dataURL = $(this).data('href');

                $('#myModalContent').load(dataURL, function() {
                    $('#myModal').modal({
                        show: true
                    });
                });
            });

            $(document).on('click', '.rejectBalance', function(event) {
                event.preventDefault();
                var _token = "{{ csrf_token() }}";
                var id = $(this).attr('id');

                if (confirm('Are you sure to delete this record?')) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/admin/reject-req') }}/" + id,
                        data: {
                            _token: _token
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                $("table#req_table").find('tr#' + id).css('background',
                                    'antiquewhite');
                                $("table#req_table").find('tr#' + id).find('span.badge').attr(
                                    'class', 'badge badge-pill bg-danger text-white').text(
                                    'Rejected');
                            }
                        },
                    });
                }
            });

            $(document).on('submit', '#search-form', function(e) {
                e.preventDefault();
                $("button[type=submit]").attr("disabled", "disabled");
                $("button[type=submit]").empty().html("Please wait...");
                var page = $('#hidden_page').val();

                fetchData(page);
            });

            $(document).on('click', '.sorting', function() {
                var column_name = $(this).data('column_name');
                var order_type = $(this).data('sorting_type');
                var reverse_order = '';
                if (order_type == 'asc') {
                    $(this).data('sorting_type', 'desc');
                    reverse_order = 'desc';
                    $('#' + column_name + '').html('<span class="fa fa-angle-down"></span>');
                }
                if (order_type == 'desc') {
                    $(this).data('sorting_type', 'asc');
                    reverse_order = 'asc';
                    $('#' + column_name + '').html('<span class="fa fa-angle-up"></span>');
                }
                $('#hidden_column_name').val(column_name);
                $('#hidden_sort_type').val(reverse_order);
                var page = $('#hidden_page').val();

                fetchData(page);
            });

            $(window).on('hashchange', function() {
                if (window.location.hash) {
                    var page = window.location.hash.replace('#', '');
                    if (page == Number.NaN || page <= 0) {
                        return false;
                    } else {
                        fetchData(page);
                    }
                }
            });

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent().addClass('active');

                var page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });
        });

        function fetchData(page) {
            $.ajax({
                    url: '?page=' + page,
                    data: $('#search-form').serialize(),
                    type: "get",
                    datatype: "html"
                })
                .done(function(data) {
                    $("button[type=submit]").removeAttr('disabled');
                    $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                    $("#tableContentWrap").empty().html(data);
                })
                .fail(function(jqXHR, ajaxOptions, thrownError) {
                    alert('No response from server');
                });
        }

        function redirectToSimNumber(val) {
            window.location.href = "/admin/balance-manager/{{ Request::segment(3) }}/" + val;
        }

        const declineButtons = document.querySelectorAll('.btn-outline-danger'); // Change to button class
        const RejectModal = new bootstrap.Modal(document.getElementById('decline_transection'));

        declineButtons.forEach(button => {
            button.addEventListener('click', function() {
                const get_id = button.getAttribute('data-id');
                const user_type = button.getAttribute('data-user_type');
                const user_id = button.getAttribute('data-user');

                console.log(get_id);

                document.getElementById('modal_id').value = get_id;
                document.getElementById('usertype').value = user_type;
                document.getElementById('user_id').value = user_id;

                RejectModal.show();
            });
        });





        // $("select#simNumber").select2();
    </script>

    <script>
        $(document).ready(function() {
            // Event listener for dropdown action buttons
            $('.action-button').on('click', function(e) {
                e.preventDefault();

                let selectedAction = $(this).data('action'); // Get the action from the clicked button
                let reason = $('input[name="reason"]').val();
                let selectedCheckboxes = $('input.row_checkbox:checked'); // Get selected checkboxes

                // Validate that at least one checkbox is selected
                if (selectedCheckboxes.length === 0) {
                    alert('Please select at least one row.');
                    return;
                }

                // Confirmation dialog
                if (confirm(`Are you sure you want to ${selectedAction}?`)) {
                    // Prepare the data to send
                    let selectedIds = selectedCheckboxes.map(function() {
                        return $(this).closest('tr').attr('id');
                    }).get();

                    let data = {
                        action: selectedAction,
                        reason: reason,
                        selected_ids: selectedIds, // Pass the selected row IDs
                        _token: '{{ csrf_token() }}' // Add CSRF token for Laravel
                    };

                    // Make the AJAX request
                    $.ajax({
                        url: '{{ route('admin.service_multiple_action') }}', // Replace with your route
                        type: 'POST',
                        data: data,
                        success: function(response) {
                            if (response.status == 'success') {
                                window.location.reload();
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log(error);
                            alert('Something went wrong. Please try again.');
                        }
                    });
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script>
        $(".datepicker").flatpickr();

        $(".time-picker").flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-time").flatpickr({
            enableTime: true,
            dateFormat: "Y-m-d H:i",
        });

        $(".date-format").flatpickr({
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-range").flatpickr({
            mode: "range",
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });

        $(".date-inline").flatpickr({
            inline: true,
            altInput: true,
            altFormat: "F j, Y",
            dateFormat: "Y-m-d",
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="{{ asset('static/backend/plugins/select2/js/select2-custom.js') }}"></script>
@endpush
