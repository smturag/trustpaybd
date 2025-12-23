@extends('member.layout.member_app')

@section('member_content')

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
                    <form action="#" method="GET" data-route="{{ route('user.payment-request') }}"
                        class="mb-5 justify-content-center" role="form" id="search-form" accept-charset="utf-8">
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
                                    <label for="reference">Customer Name or Number</label>
                                    <input type="text" class="form-control" name="cust_name" id="reference" />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="member_code">Transaction Id</label>
                                    <input type="text" class="form-control" name="trxid" id="trxid" />
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
                                    <label for="reference">Method Number</label>
                                    <input type="text" class="form-control" name="method_number" id="reference" />
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender">{{ translate('mfs') }}</label>

                                    <select class="form-control" name="mfs" id="mfs">
                                        <option value="">--Select--</option>
                                        <option value="nagad">NAGAD</option>
                                        <option value="bkash">bKash</option>
                                        <option value="16216">Rocket</option>
                                        <option value="upay">upay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="reference">Reference</label>
                                    <input type="text" class="form-control" name="reference" id="reference" />
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
                                    <label for="message">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group d-grid">
                                    <label class="" for="">&nbsp;</label>
                                    <button type="button" id="search-btn" class="btn btn-danger btn-block"><span
                                            class="fa fa-search"></span> {{ translate('Search') }} </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        @include('member.payment_request.data')
                    </div>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="2" />
                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modalbody">
            </div>
        </div>
    </div>

    <div class="modal fade" id="reject_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <form id="reject_form">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Reject Transaction Confirm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input class="form-control" type="text" name="transId" value="" id="modal_id" hidden>
                        <div>
                            <label for="reason">Reject Reason</label>
                            <input id="reason" type="text" name="reason" class="form-control" required>
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
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "post",
                            data: {
                                id: id,
                                _method: "DELETE"
                            },
                            dataType: "json",
                            success: function(data) {
                                swal("Done!", "It was successfully " + title, "success");
                                $('table').find('tr#' + id).animate({
                                    backgroundColor: "#e74c3c",
                                    color: "#fff"
                                }, "slow").animate({
                                    opacity: "hide"
                                }, "slow");
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
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

        $(document).ready(function() {

            // $('#search-btn').click(function() {
            //     var form = $('#search-form');
            //     var route = form.data('route');
            //     var formData = form.serialize();

            //     console.log(1)

            //     $.ajax({
            //         url: route,
            //         type: 'GET',
            //         data: formData,
            //         success: function(response) {
            //             $('#tableContentWrap').html(response);
            //             // console.log(response);
            //         },
            //         error: function(xhr, status, error) {
            //             console.error(error);
            //         }
            //     });
            // });
            $('#search-btn').click(function(e) {
                e.preventDefault(); // Prevent the default form submission

                var form = $('#search-form');
                var route = form.data('route');
                var formData = form.serialize();

                console.log('Submitting form...');

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#tableContentWrap').html(response);
                        console.log('Form submission successful.');
                    },
                    error: function(xhr, status, error) {
                        console.error('Form submission failed:', error);
                    }
                });
            });

            // $(document).on('submit', '#search-form', function (e) {

            //     e.preventDefault();
            //     $("button[type=submit]").attr("disabled", "disabled");
            //     $("button[type=submit]").empty().html("Please wait...");

            //     //var query = $('#serach').val();
            //     var page = $('#hidden_page').val();

            //     fetchData(page);
            // });

            // function fetchData(page) {
            //     $.ajax({
            //         url: '?page=' + page,
            //         //url:"!! route('balance_manager_filter') !!}?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type,
            //         data: $('#search-form').serialize(),
            //         type: "get",
            //         datatype: "html"
            //     })
            //         .done(function (data) {
            //             $("button[type=submit]").removeAttr('disabled');
            //             $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
            //             $("#tableContentWrap").empty().html(data);
            //             //location.hash = page;
            //         })
            //         .fail(function (jqXHR, ajaxOptions, thrownError) {
            //             $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
            //             alert('No response from server');
            //         });
            // }

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

            $(document).on('click', '.pagination a', function(event) {
                event.preventDefault();
                $('li').removeClass('active');
                $(this).parent().addClass('active');

                var page = $(this).attr('href').split('page=')[1];
                $('#hidden_page').val(page);
                var column_name = $('#hidden_column_name').val();
                var sort_type = $('#hidden_sort_type').val();
                //var query = $('#serach').val();

                console.log(11)

                fetchData(page, sort_type, column_name);
            });


            $(document).on('click', '.openPopup', function(e) {
                e.preventDefault();
                var id = $(this).attr('id');
                var dataURL = "{{ url('/admin/merchant/payment-request/approved-payment-request') }}/" + id;

                $('.modalbody').load(dataURL, function() {
                    $('#myModal').modal({
                        show: true
                    });
                });
            });

            $(document).on('click', '.rejectBalance', function(event) {
                event.preventDefault();
                var _token = "{{ csrf_token() }}";
                var id = $(this).attr('id');

                if (confirm('Are you sure to reject this request?')) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ url('/admin/merchant/payment-request/reject-payment-request') }}/" +
                            id,
                        data: {
                            _token: _token
                        },
                        success: function(response) {
                            if (response.status === 200) {
                                $("table#payment_request_table").find('tr#' + id).css(
                                    'background', 'antiquewhite');
                                $("table#payment_request_table").find('tr#' + id).find(
                                    'span.badge').attr('class',
                                    'badge badge-pill bg-danger text-white').text(
                                    'Rejected');
                            }
                        },
                    });
                }
            });
        });



        const CompleteButtons = document.querySelectorAll('.completePaymentBtn');
        CompleteButtons.forEach(button => {
            button.addEventListener('click', function() {

                const paymentId = button.getAttribute('data-payment-id')
                console.log(paymentId)
                var confirmed = confirm("Are you sure you want to Approve this transection?");
                if (confirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('agent.approve-payment-request', ['id' => ':id']) }}"
                            .replace(':id', paymentId),
                        dataType: 'json',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        success: function(response) {
                            console.log(response);
                            // Handle the response from the server
                            if (response.status === 200) {
                                alert('Payment request Approved successfully.');
                                location.reload();
                                // You can also update the page content if needed.
                            } else {
                                alert('Failed to Approved payment request.');
                            }
                        },
                        error: function(xhr, status, error) {
                            // Handle AJAX errors if any
                            console.error(error);
                        }
                    });
                }
            });
        });


        const declineButtons = document.querySelectorAll('.declineButton');
        declineButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = new bootstrap.Modal(document.getElementById('reject_modal'));
                const getId = button.getAttribute('data-payment-id')
                console.log(getId);
                document.getElementById('modal_id').value = getId;
                modal.show();
            });
        });



        document.addEventListener("DOMContentLoaded", function() {
            var form = document.getElementById("reject_form");

            form.addEventListener("submit", function(event) {
                event.preventDefault();

                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: "{{ route('agent.reject-payment-request') }}", // Comma was missing here
                    dataType: 'json',
                    data: formData, // Send the form data
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false, // Prevent jQuery from processing the data
                    contentType: false, // Prevent jQuery from setting contentType
                    success: function(response) {
                        console.log(response);
                        // Handle the response from the server
                        if (response.status === 200) {
                            alert('Payment request rejected successfully.');
                            location.reload();
                            // You can also update the page content if needed.
                        } else {
                            alert('Failed to reject payment request.');
                        }
                    },
                    error: function(xhr, status, error) {
                        // Handle AJAX errors if any
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endpush
