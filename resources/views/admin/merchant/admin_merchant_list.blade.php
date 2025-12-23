@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

@if(session('message'))
    <div class="alert alert-success" id="alert_success">{{ session('message') }}</div>
@endif
@if(session('alert'))
    <div class="alert alert-danger">{{ session('alert') }}</div>
@endif

<div class="row">
    <div class="col-xl-12 mx-auto">
        <div class="header d-flex align-items-center mb-3 flex-column flex-sm-row">
            <h6 class="mb-2 mb-sm-0 text-uppercase text-center text-sm-start ps-sm-3">Merchant List</h6>
            <a class="ms-sm-auto btn btn-sm btn-primary mt-2 mt-sm-0" href="{{ route('merchantAdd') }}">
                <i class="bx bx-plus me-1"></i> New Merchant
            </a>
        </div>
        <hr/>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <div class="card">
            <div class="card-body">
                <form method="get" id="search-form" accept-charset="utf-8">
                    <div class="row">
                        <div class="col-md-2">
                            <label for="rows">Show</label>
                            <select class="form-control" name="rows" id="rows">
                                @foreach([10, 50, 100, 150, 200, 400, 500] as $r)
                                    <option value="{{ $r }}">{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="member_code">Merchant ID</label>
                            <input type="text" class="form-control" name="member_code" id="member_code"/>
                        </div>
                        <div class="col-md-4">
                            <label>Mobile/Email/Name</label>
                            <input type="text" class="form-control" name="message" id="message"/>
                        </div>
                        <div class="col-md-2">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-danger btn-block" id="searchBtn">
                                <span class="fa fa-search"></span> Search
                            </button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive mt-4" id="tableContentWrap">
                    @include('admin.merchant.admin_merchant_data')
                </div>

                <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id"/>
                <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="desc"/>
                <input type="hidden" name="hidden_page" id="hidden_page" value="1"/>
            </div>
        </div>
    </div>
</div>

@include('admin.merchant.edit_balance_modal')

@endsection

@push('js')
<script>
$(document).ready(function () {
    function fetchData(page) {
        let form = $('#search-form');
        $.ajax({
            url: '?page=' + page,
            data: form.serialize() + '&sortby=' + $('#hidden_column_name').val() + '&sorttype=' + $('#hidden_sort_type').val(),
            success: function (data) {
                $('#tableContentWrap').html(data);
                $("#searchBtn").html('<span class="fa fa-search"></span> Search').prop('disabled', false);
            },
            error: function () {
                alert('Something went wrong. Please try again.');
                $("#searchBtn").html('<span class="fa fa-search"></span> Search').prop('disabled', false);
            }
        });
    }

    $(document).on('submit', '#search-form', function (e) {
        e.preventDefault();
        $("#searchBtn").html('Please wait...').prop('disabled', true);
        fetchData(1);
    });

    $(document).on('click', '.pagination a', function (e) {
        e.preventDefault();
        let page = $(this).attr('href').split('page=')[1];
        $('#hidden_page').val(page);
        fetchData(page);
    });

    $(document).on('click', '.sorting', function () {
        let column = $(this).data('column_name');
        let order = $(this).data('sorting_type') === 'asc' ? 'desc' : 'asc';
        $('#hidden_column_name').val(column);
        $('#hidden_sort_type').val(order);
        fetchData($('#hidden_page').val());
    });

    // ✅ Fix: open modal from AJAX-loaded content
    $(document).on('click', '.editBtn', function () {
        const modal = $('#editModal');
        modal.find('form').attr('action', $(this).data('route'));
        modal.find('input[name=name]').val($(this).data('name'));
        modal.find('input[name=balance]').val($(this).data('balance'));
        modal.find('select[name=balance_type]').val('credit');
        modal.find('input[name=amount]').val('');
        modal.find('textarea[name=details]').val('');
        modal.find('input[name=pincode]').val('');
        modal.modal('show');
    });
});
</script>
@endpush
