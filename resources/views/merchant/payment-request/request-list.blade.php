@extends('merchant.mrc_app')
@section('title', 'Dashboard')


@section('mrc_content')

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
    @endif

    @if (Session::has('alert'))
        <div class="alert alert-danger">{{ Session::get('alert') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Payment Request List</h6>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-secondary export-btn" data-format="csv">CSV</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary export-btn" data-format="excel">Excel</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary export-btn" data-format="pdf">PDF</button>
                </div>
                {{-- <a class="ms-auto btn btn-sm btn-primary" href="{{ route('userAdd') }}">
                    <i class="bx bx-plus mr-1"></i> New Payment Request
                </a> --}}
            </div>
            <hr />

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <form action="#" class="mb-5 justify-content-center" role="form" method="GET" id="search-form"
                        data-route="{{ route('merchant.payment-request') }}" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rows">{{ translate('show') }}</label>
                                    <select class="form-control" name="rows" id="rows">
                                        <option value="10">10</option>
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
                                    <label for="message">Status</label>
                                    <select name="status" class="form-control id="">
                                        <option value="">Select Status</option>
                                        <option value="pending">Pending</option>
                                        <option value="1">Success</option>
                                        <option value="3">Rejected</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="message">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" id="start_date">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="payment_type">Type</label>
                                    <select name="payment_type" class="form-control" id="payment_type">
                                        <option value="">Select Type</option>
                                        <option value="P2A" {{ request('payment_type') == 'P2A' ? 'selected' : '' }}>P2A - Cash Out</option>
                                        <option value="P2P" {{ request('payment_type') == 'P2P' ? 'selected' : '' }}>P2P - Send Money</option>
                                        <option value="P2C" {{ request('payment_type') == 'P2C' ? 'selected' : '' }}>P2C - Payment</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="message">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="member_code">Reference</label>
                                    <input type="text" class="form-control" name="reference" id="reference" />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="reference">Customer Name or Number</label>
                                    <input type="text" class="form-control" name="cust_name" id="reference" />
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="trxid">Transaction ID</label>
                                    <input type="text" class="form-control" name="trxid">
                                </div>
                            </div>

                            <!--<div class="col-md-2">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="trxid">Transaction ID</label>-->
                            <!--        <select class="form-control" name="trxid" id="trxid">-->
                            <!--            <option value="">--type--</option>-->
                            <!--            <option value="partner">Partner</option>-->
                            <!--            <option value="dso">DSO</option>-->
                            <!--            <option value="agent">Agent</option>-->

                            <!--        </select>-->
                            <!--    </div>-->
                            <!--</div>-->


                            <!--<div class="col-md-4">-->
                            <!--    <div class="form-group">-->
                            <!--        <label for="message">Mobile/Email/Name</label>-->
                            <!--        <input type="text" class="form-control" name="message" id="message">-->
                            <!--    </div>-->
                            <!--</div>-->

                            <div class="col-md-2">
                                <div class="form-group d-grid">
                                    <label class="" for="">&nbsp;</label>
                                    <button type="submit" class="btn btn-danger btn-block"><span
                                            class="fa fa-search"></span> {{ translate('Search') }} </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        @include('merchant.payment-request.data')
                    </div>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="2" />
                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                </div>
            </div>
        </div>
    </div>

    <!-- Details Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <table class="table table-sm mb-0">
                            <tbody>
                                <tr><th>Payment ID</th><td id="detail_payment_id"></td></tr>
                                <tr><th>Request ID</th><td id="detail_request_id"></td></tr>
                                <tr><th>TRX ID</th><td id="detail_trxid"></td></tr>
                                <tr><th>Merchant Name</th><td id="detail_merchant_name"></td></tr>
                                <tr><th>Reference</th><td id="detail_reference"></td></tr>
                                <tr><th>Payment Method</th><td id="detail_payment_method"></td></tr>
                                <tr><th>Payment Trx</th><td id="detail_payment_trx"></td></tr>
                                <tr><th>Amount</th><td id="detail_amount"></td></tr>
                                <tr><th>Fee</th><td id="detail_fee"></td></tr>
                                <tr><th>Commission</th><td id="detail_commission"></td></tr>
                                <tr><th>New Amount</th><td id="detail_new_amount"></td></tr>
                                <tr><th>From Number</th><td id="detail_from_number"></td></tr>
                                <tr><th>Note</th><td id="detail_note"></td></tr>
                                <tr><th>Status</th><td id="detail_status"></td></tr>
                                <tr><th>Created At</th><td id="detail_created_at"></td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    {{-- <script type="text/javascript">
        $(window).on('hashchange', function () {
            if (window.location.hash) {
                var page = window.location.hash.replace('#', '');
                if (page == Number.NaN || page <= 0) {
                    return false;
                } else {
                    fetchData(page);
                }
            }
        });

        function delete_record(id, title) {
            var button = $('table').find("tr#" + id).find(".delete");

            button.attr("disabled", "disabled");
            button.empty().html("Deleting...");

            swal({
                title: "Are you sure?",
                text: "You will Request " + title + " ?",
                icon: "warning",
                // showCancelButton: true,
                // confirmButtonColor: "#DD6B55",
                // confirmButtonText: "Yes, " + title + " it!",
                // closeOnConfirm: false,
                buttons: {
                    cancel: true,
                    confirm: 'Confirm Delete',
                },
                dangerMode: true,
            })
                .then((isConfirm) => {
                    if (isConfirm) {
                        $.ajax({
                            url: "{{ url('admin/user-delete/') }}/" + id,
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            type: "post",
                            data: {id: id, _method: "DELETE"},
                            dataType: "json",
                            success: function (data) {
                                swal("Done!", "It was successfully " + title, "success");
                                $('table').find('tr#' + id).animate({backgroundColor: "#e74c3c", color: "#fff"}, "slow").animate({opacity: "hide"}, "slow");
                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                alert('Error adding / update data');
                            }
                        });
                        //reloadfetchdata(page, sort_type, column_name);
                    } else {
                        swal("Cancelled", "Your imaginary file is safe :)", "error");
                        button.removeAttr("disabled");
                        button.empty().html("Delete");
                    }
                });
        }

        $(document).ready(function () {

            $(document).on('submit', '#search-form', function (e) {

                e.preventDefault();
                $("button[type=submit]").attr("disabled", "disabled");
                $("button[type=submit]").empty().html("Please wait...");

                //var query = $('#serach').val();
                var page = $('#hidden_page').val();

                // console.log(page);

               fetchData(page);
            });

            function fetchData(page) {

                // console.log( $('#search-form').serialize())
                $.ajax({
                    url: '?page=' + page,
                    //url:"!! route('balance_manager_filter') !!}?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type,
                    data: $('#search-form').serialize(),
                    type: "get",
                     datatype: "html"
                })
                    .done(function (data) {
                        $("button[type=submit]").removeAttr('disabled');
                        $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                        $("#tableContentWrap").empty().html(data);
                        //location.hash = page;

                        console.log(data)
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
                        alert('No response from server');
                    });
            }

            $(document).on('click', '.sorting', function () {
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

            $(document).on('click', '.pagination a', function (event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent().addClass('active');

                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                //var query = $('#serach').val();

                fetchData(page, sort_type, column_name);
            });
        });
    </script> --}}

    <script>
            $(document).ready(function () {
        // Initialize Bootstrap tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // View payment details
        $(document).on('click', '.viewPaymentBtn', function() {
            const data = this.dataset;
            const setDetail = (id, value) => $(id).text(value || '-');

            setDetail('#detail_payment_id', data.paymentId);
            setDetail('#detail_request_id', data.requestId);
            setDetail('#detail_trxid', data.trxid);
            setDetail('#detail_merchant_name', data.merchantName);
            setDetail('#detail_reference', data.reference);
            setDetail('#detail_payment_method', data.paymentMethod);
            setDetail('#detail_payment_trx', data.paymentTrx);
            setDetail('#detail_amount', data.amount);
            setDetail('#detail_fee', data.fee);
            setDetail('#detail_commission', data.commission);
            setDetail('#detail_new_amount', data.newAmount);
            setDetail('#detail_from_number', data.fromNumber);
            setDetail('#detail_note', data.note);
            setDetail('#detail_status', data.status);
            setDetail('#detail_created_at', data.createdAt);

            const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
            modal.show();
        });

        $('#search-form').on('submit', function (e) {
            e.preventDefault();

            var form = $(this);
            var route = form.data('route');
            var formData = form.serialize();
            var $btn = form.find('button[type=submit]');

            // disable + show loading text
            $btn.prop('disabled', true).html("Please wait...");

            $.ajax({
                url: route,
                type: 'GET',
                data: formData,
                success: function (response) {
                    $('#tableContentWrap').html(response);
                },
                error: function (xhr, status, error) {
                    console.error(error);
                    alert("Something went wrong. Please try again.");
                },
                complete: function () {
                    // always re-enable the button after request
                    $btn.prop('disabled', false).html("<span class='fa fa-search'></span> {{ translate('Search') }}");
                }
            });
        });

        $('.export-btn').on('click', function () {
            var format = $(this).data('format');
            var formData = $('#search-form').serialize();
            var baseUrl = "{{ route('merchant.payment-request.export') }}";
            var url = baseUrl + (formData ? ('?' + formData + '&') : '?') + 'format=' + format;
            window.location.href = url;
        });
    });
    </script>
@endpush
