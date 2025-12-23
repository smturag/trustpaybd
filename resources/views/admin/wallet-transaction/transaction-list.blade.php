@extends('admin.layouts.admin_app')
@section('title', 'Transaction List')

@section('content')

    <style>
        /* Default width for Select2 */
        .select2 {
            width: 200px;
        }

        /* Adjust the width for medium-sized screens */
        @media screen and (min-width: 768px) {
            .select2 {
                width: 70%;
            }
        }

        /* Adjust the width for large-sized screens */
        @media screen and (min-width: 1200px) {
            .select2 {
                width: 70%;
            }
        }
    </style>

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
                <h6 class="mb-0 text-uppercase ps-3">Wallet Transaction List</h6>
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
                    <form action="#" data-route="{{ route('admin.merchant.payment-request') }}"
                        class="mb-5 justify-content-center" role="form" method="get" id="search-form"
                        accept-charset="utf-8">
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
                                    <label for="trxid">Transaction Id</label>
                                    <input type="text" class="form-control" name="trxid" id="trxid" />
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
                                    <button type="submit" class="btn btn-danger btn-block" id="search-btn"><span
                                            class="fa fa-search"></span> {{ translate('Search') }} </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        @include('admin.wallet-transaction.data')
                    </div>
                    <input type="hidden" name="hidden_page" id="hidden_page" value="2" />
                    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="approve_modal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered custom-modal">
            <div class="modal-content">
                <form action="{{ route('admin.wallet.change_status') }}" method="POST">

                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Stylish Modal Title</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <input class="form-control" type="text" name="transId" value="" id="modal_id" hidden>
                        <input type="text" name="status" value="" id="modal_status" hidden>
                        <div>
                            <label for="reason_or_trx">
                                <p id='label_name'> </p>
                            </label>
                            <input id="reason_or_trx" type="text" name="reason_or_trx" class="form-control" required>
                            <input id="userId" type="text" name="user_id" class="form-control" hidden>
                            <input id="userType" type="text" name="user_type" class="form-control" hidden>
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

        // $(document).ready(function () {

        //     $(document).on('submit', '#search-form', function (e) {

        //         e.preventDefault();
        //         $("button[type=submit]").attr("disabled", "disabled");
        //         $("button[type=submit]").empty().html("Please wait...");

        //         //var query = $('#serach').val();
        //         var page = $('#hidden_page').val();

        //        fetchData(page);
        //     });

        //     function fetchData(page) {
        //         $.ajax({
        //             url: '?page=' + page,
        //             //url:"!! route('balance_manager_filter') !!}?page="+page+"&sortby="+sort_by+"&sorttype="+sort_type,
        //             data: $('#search-form').serialize(),
        //             type: "get",
        //             datatype: "html"
        //         })
        //             .done(function (data) {
        //                 $("button[type=submit]").removeAttr('disabled');
        //                 $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
        //                 $("#tableContentWrap").empty().html(data);
        //                 //location.hash = page;
        //             })
        //             .fail(function (jqXHR, ajaxOptions, thrownError) {
        //                 $("button[type=submit]").empty().html("<span class='fa fa-search'></span> Search ");
        //                 alert('No response from server');
        //             });
        //     }

        //     $(document).on('click', '.sorting', function () {
        //         var column_name = $(this).data('column_name');
        //         var order_type = $(this).data('sorting_type');
        //         var reverse_order = '';
        //         if (order_type == 'asc') {
        //             $(this).data('sorting_type', 'desc');
        //             reverse_order = 'desc';
        //             $('#' + column_name + '').html('<span class="fa fa-angle-down"></span>');
        //         }
        //         if (order_type == 'desc') {
        //             $(this).data('sorting_type', 'asc');
        //             reverse_order = 'asc';
        //             $('#' + column_name + '').html('<span class="fa fa-angle-up"></span>');
        //         }
        //         $('#hidden_column_name').val(column_name);
        //         $('#hidden_sort_type').val(reverse_order);
        //         var page = $('#hidden_page').val();

        //         fetchData(page);
        //     });

        //     $(document).on('click', '.pagination a', function (event) {
        //         event.preventDefault();
        //         $('li').removeClass('active');
        //         $(this).parent().addClass('active');

        //         var page = $(this).attr('href').split('page=')[1];
        //         $('#hidden_page').val(page);
        //         var column_name = $('#hidden_column_name').val();
        //         var sort_type = $('#hidden_sort_type').val();
        //         //var query = $('#serach').val();

        //         fetchData(page, sort_type, column_name);
        //     });


        //     $(document).on('click', '.openPopup', function (e) {
        //         e.preventDefault();
        //         var id = $(this).attr('id');
        //         var dataURL = "{{ url('/admin/merchant/payment-request/approved-payment-request') }}/"+id;

        //         $('.modalbody').load(dataURL, function () {
        //             $('#myModal').modal({
        //                 show: true
        //             });
        //         });
        //     });

        //     $(document).on('click', '.rejectBalance', function (event) {
        //         event.preventDefault();
        //         var _token = "{{ csrf_token() }}";
        //         var id = $(this).attr('id');

        //         if (confirm('Are you sure to reject this request?')) {
        //             $.ajax({
        //                 type: 'POST',
        //                 url: "{{ url('/admin/merchant/payment-request/reject-payment-request') }}/" + id,
        //                 data: {_token: _token},
        //                 success: function (response) {
        //                     if (response.status === 200) {
        //                         $("table#payment_request_table").find('tr#' + id).css('background', 'antiquewhite');
        //                         $("table#payment_request_table").find('tr#' + id).find('span.badge').attr('class', 'badge badge-pill bg-danger text-white').text('Rejected');
        //                     }
        //                 },
        //             });
        //         }
        //     });
        // });

        $(document).ready(function() {
            $('#search-btn').click(function() {
                var form = $('#search-form');
                var route = form.data('route');
                var formData = form.serialize();

                console.log(formData);

                $.ajax({
                    url: route,
                    type: 'GET',
                    data: formData,
                    success: function(response) {
                        $('#tableContentWrap').html(response);
                        console.log(response);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            });
        });

        function changeStatus(id, action) {

            const dataSet = {
                transId: id,
                status: action == 0 ? 0 : 1
            }


            var confirmationMessage = action == 0 ? 'Are You sure you want to reject' : 'Are You sure you want to Success'
            if (confirm(confirmationMessage)) {
                $.ajax({
                    url: '{{ route('admin.wallet.change_status') }}',
                    type: 'POST',
                    data: dataSet,
                    headers: {

                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {

                        if (response == 1) {
                            location.reload();
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                    }
                });
            }

        }


        const approve_transection = document.querySelectorAll('.approve_transection');
        approve_transection.forEach(button => {
            button.addEventListener('click', function() {
                const getId = button.getAttribute('data-id');
                const getStatus = button.getAttribute('data-rstatus');
                const getUserType = button.getAttribute('data-user_type');
                const getUserId = button.getAttribute('data-user');

               
                console.log(getId, getStatus, getUserType, getUserId);
                const modal = new bootstrap.Modal(document.getElementById('approve_modal'));
                document.getElementById('modal_id').value = getId;
                document.getElementById('modal_status').value = getStatus;
                document.getElementById('label_name').textContent = 'TrxId'
                document.getElementById('userId').value = getUserId
                document.getElementById('userType').value = getUserType


                modal.show();

            });
        });



        const declineButtons = document.querySelectorAll('.declineButton');
        declineButtons.forEach(button => {
            button.addEventListener('click', function() {
                const getId = button.getAttribute('data-id');
                const getStatus = button.getAttribute('data-rstatus');
                const getUserType = button.getAttribute('data-user_type');
                const getUserId = button.getAttribute('data-user');

               
                console.log(getId, getStatus, getUserType, getUserId);
                const modal = new bootstrap.Modal(document.getElementById('approve_modal'));
                document.getElementById('modal_id').value = getId;
                document.getElementById('modal_status').value = getStatus;
                document.getElementById('label_name').textContent = 'Reason'
                document.getElementById('userId').value = getUserId
                document.getElementById('userType').value = getUserType


                modal.show();

            });
        });
    </script>


    <style>
        /* Custom styling for the modal */
        .custom-modal .modal-content {
            background-color: #f8f9fa;
            border: none;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }

        .custom-modal .modal-header {
            border-bottom: none;
        }

        .custom-modal .modal-title {
            font-size: 24px;
            color: #333;
        }

        .custom-modal .modal-body {
            padding: 20px;
        }
    </style>
@endpush
