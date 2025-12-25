@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@push('css')
    <link href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endpush



@section('content')

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Service Request List</h6>
            </div>
            <hr />

            @if (session('message'))
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white">Success Alerts</h6>
                            <div class="text-white">{{ session('message') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="#" class="mb-5 justify-content-center" role="form" method="post" id="search-form"
                        accept-charset="utf-8">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rows">{{ translate('show') }}</label>
                                    <select class="form-control" name="rows" id="rows">
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                        <option value="150">150</option>
                                        <option value="200">200</option>
                                        <option value="400">400</option>
                                        <option value="500">500</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date1">Merchant</label>
                                     <select class="form-control select2" name="merchant_id" id="merchant_id">
                                        <option value="">Select Merchant</option>
                                        @foreach ($merchants as $key => $merchant)
                                            <option value="{{ $merchant->id }}">{{ $merchant->fullname }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender">{{ translate('mfs') }}</label>

                                    <select class="form-control" name="mfs" id="mfs">
                                        <option value="">--Select--</option>
                                        <option value="NAGAD">NAGAD</option>
                                        <option value="bKash">bKash</option>
                                        <option value="16216">Rocket</option>
                                        <option value="upay">upay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date1">Date From</label>
                                    <input type="text" class="form-control datepicker" name="from" id="date1"
                                        placeholder="Date From" size="18" value="<?php echo $from; ?>"
                                        autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="date2">Date To</label>
                                    <input type="text" class="form-control datepicker" name="to" id="date2"
                                        placeholder="Date To" size="18" value="<?php echo $to; ?>" autocomplete="off">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="simNumber">{{ translate('Agent_Number') }}</label>
                                    <select class="form-control" id="single-select-field" name="simNumber" id="simNumber"
                                        onchange="return redirectToSimNumber(this.options[this.selectedIndex].value);">
                                        <option value="">--Sim--</option>

                                        @foreach ($sim as $simNumber)
                                            @if ($simNumber->sim != '')
                                                <option value="{{ $simNumber->sim }}" @selected($simNumber->sim == Request::segment(4))>
                                                    {{ $simNumber->sim }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">{{ translate('status') }}</label>
                                    <select class="form-control" name="status" id="status">
                                        <option value="">--Status--</option>
                                        <option value="success">Success</option>
                                        <option value="waiting">Waiting</option>
                                        <option value="pending">Pending</option>
                                        <option value="approved">Approved</option>
                                        <option value="rejected">Rejected</option>
                                        <option value="processing">Processing</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="type">{{ translate('type') }}</label>
                                    <select class="form-control" name="type" id="type">
                                        <option value="">--Type--</option>
                                        <option value="cashout">Cash Out</option>
                                        <option value="cashin">Cash In</option>

                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="trxid">{{ translate('trxid') }}</label>
                                    <input type="text" class="form-control" name="trxid" id="trxid">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="trxid">{{ translate('Response TRX ID') }}</label>
                                    <input type="text" class="form-control" name="response_trxid" id="response_trxid">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="cNumber">{{ translate('Customer Number') }}</label>
                                    <input type="text" class="form-control" name="cNumber" id="cNumber">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label class="" for="">&nbsp;</label><br />
                                    <button type="submit" class="btn btn-danger btn-block"><span
                                            class="fa fa-search"></span> {{ translate('Search') }} </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        @include('admin.mfs.mfs_table_content')
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="reason"
                                    placeholder="If Any Reject reason" required>
                            </div>
                            <div class="col-md-3">
                                <div class="col">
                                    <div class="dropdown">
                                        <button class="btn btn-info dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            Action
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item action-button" href="#"
                                                    data-action="reject">Reject</a></li>
                                            <li><a class="dropdown-item action-button" href="#"
                                                    data-action="resend">Resend</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content" id="myModalContent"></div>
        </div>
    </div>


    {{-- <div class="modal fade" id="decline_transection" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <form action="{{ route('service.reject_req') }}" method="POST">

                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Transection</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input class="form-control" type="text" name="transId" value="" id="modal_id" hidden>
                        <div>
                            <label for="reason_or_trx">Reason</label>
                            <input id="reason_or_trx" type="text" name="reason_or_trx" class="form-control" required>
                            <input id="usertype" type="text" name="user_type" class="form-control" hidden>
                            <input id="user_id" type="text" name="user_id" class="form-control" hidden>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>

                </form>
            </div>
        </div>
    </div> --}}

    <div class="modal fade" id="decline_transection" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <form action="{{ route('service.reject_req') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Transaction</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="text" name="transId" id="modal_id" hidden>
                        <div>
                            <label for="reason_or_trx">Reason</label>
                            <input id="reason_or_trx" type="text" name="reason_or_trx" class="form-control" required>
                            <input id="usertype" type="text" name="user_type" class="form-control" hidden>
                            <input id="user_id" type="text" name="user_id" class="form-control" hidden>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
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
