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
            <h6 class="mb-0 text-uppercase ps-3">Service Request List</h6>
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
                <!-- Search Form -->
                <form id="search-form" data-route="{{ route('merchant.service-request') }}">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('show') }}</label>
                                <select class="form-control" name="rows">
                                    @foreach([10,50,100,150,200,400,500] as $r)
                                        <option value="{{ $r }}">{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Transaction ID</label>
                                <input type="text" class="form-control" name="trxid">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>{{ translate('mfs') }}</label>
                                <select class="form-control" name="mfs">
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
                                <label>Date From</label>
                                <input type="date" class="form-control" name="from">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Date To</label>
                                <input type="date" class="form-control" name="to">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Status</label>
                                <select class="form-control" name="status">
                                    <option value="">--Status--</option>
                                    <option value="success">Success</option>
                                    <option value="waiting">Waiting</option>
                                    <option value="pending">Pending</option>
                                    <option value="rejected">Rejected</option>
                                    <option value="processing">Processing</option>
                                    <option value="failed">Failed</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Customer Number</label>
                                <input type="text" class="form-control" name="cNumber">
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group d-grid mt-4">
                                <button type="button" id="search-btn" class="btn btn-danger btn-block">
                                    <span class="fa fa-search"></span> {{ translate('Search') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Table -->
                <div class="table-responsive" id="tableContentWrap">
                    @include('merchant.service-request.data')
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function() {
    // Load table (AJAX)
    function loadTable(formData = null) {
        var route = $('#search-form').data('route');
        $.ajax({
            url: route,
            type: 'GET',
            data: formData,
            success: function(response) {
                $('#tableContentWrap').html(response);
            },
            error: function(xhr) {
                console.error(xhr.responseText);
                alert('Something went wrong. Please try again.');
            }
        });
    }

    // Search button click
    $('#search-btn').click(function(e) {
        e.preventDefault();
        var form = $('#search-form');
        var formData = form.serialize();
        var $btn = $(this);

        // Check if any filter is selected
        var hasFilter = false;
        form.find('input[type="text"], input[type="date"], select').each(function() {
            if ($(this).val() && $(this).val().trim() !== '') {
                hasFilter = true;
                return false; // exit loop
            }
        });

        $btn.prop('disabled', true).html('Please wait...');

        if (!hasFilter) {
            loadTable();
        } else {
            loadTable(formData);
        }

        setTimeout(function() {
            $btn.prop('disabled', false).html("<span class='fa fa-search'></span> {{ translate('Search') }}");
        }, 1000);
    });

    // Optional: load table on page load
    loadTable();
});
</script>
@endpush
