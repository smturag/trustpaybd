@extends('member.layout.member_app')
@section('title', 'Member List')

@section('member_content')


@if(session()->has('message'))
 <div class="alert alert-success" id="alert_success">
   {{session('message')}}
 </div>
@endif
@if (Session::has('alert'))
<div class="alert alert-danger">{{ Session::get('alert') }}</div>
@endif

            <div class="row">
                <div class="col-xl-12 mx-auto">
                    {{-- <div class="header d-none d-sm-flex align-items-center mb-3">
                        <h6 class="mb-0 text-uppercase ps-3">@if(auth()->user('web')->user_type=='partner') DSO @else Agent @endif List</h6>
                        <a class="ms-auto btn btn-sm btn-primary" href="{{ route('member_add') }}">
                            <i class="bx bx-plus mr-1"></i> New @if(auth()->user('web')->user_type=='partner') DSO @else Agent @endif
                        </a>
                    </div> --}}

                    <div class="header d-none d-sm-flex align-items-center mb-3">
                        <h6 class="mb-0 text-uppercase ps-3">@if(auth()->user('web')->user_type=='partner') Agent @endif List</h6>
                        <a class="ms-auto btn btn-sm btn-primary" href="{{ route('member_add') }}">
                            <i class="bx bx-plus mr-1"></i> New @if(auth()->user('web')->user_type=='partner')  Agent @endif
                        </a>
                    </div>


                    <hr/>

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
                            <form action="#" class="mb-5 justify-content-center" role="form" method="post" id="search-form" accept-charset="utf-8">
							@csrf
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
                                            <label for="member_code">{{ translate('member_code') }}</label>
                                            <input type="text" class="form-control" name="member_code" id="member_code" value="{{ old('member_code') ? old('member_code') : '' }}" />
                                        </div>
                                    </div>


                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="message">Mobile/Email/Name</label>
                                            <input type="text" class="form-control" name="message" id="message" value="{{ old('message') ? old('message') : '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group d-grid">
                                            <label class="" for="">&nbsp;</label>
                                            <button type="submit" class="btn btn-danger btn-block"><span class="fa fa-search"></span> {{ translate('Search') }} </button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive" id="tableContentWrap">
                                @include('member.agent.agent_data')
                            </div>
{{--                            <input type="hidden" name="hidden_page" id="hidden_page" value="1" />--}}
                            <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
                            <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />

                        </div>
                    </div>
                </div>
            </div>





@endsection

@push('js')
      <script type="text/javascript">
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
                            url: "{{ url('member_delete/') }}/" + id,
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

                fetchData(page);
            });

            function fetchData(page) {
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
    </script>
@endpush

