@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')

    <style>
        /* Your existing styles */
        .select2 {
            width: 200px;
        }

        @media screen and (min-width: 768px) {
            .select2 {
                width: 70%;
            }
        }

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

    @if (session()->has('alert'))
        <div class="alert alert-danger">{{ session('alert') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Payment Request List</h6>
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
                    <form action="#" data-route="{{ route('report.merchant.payment_report') }}"
                        class="mb-5 justify-content-center" role="form" method="get" id="search-form"
                        accept-charset="utf-8">
                        <div class="row">
                            <!-- Your existing form fields -->
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="rows">{{ __('Show') }}</label>
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
                                    <label for="message">Profile Type</label>
                                    <select name="profileType" class="form-control" id="profileType">
                                        <option value=""  selected>Select Status</option>
                                        <option value="sub_merchant">Sub Merchant</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="message">Select Sub Merchant </label>
                                    <select class="form-control" name="selectSubMerchant" id="selectSubMerchant">
                                        <option value=""  selected>Select Merchant</option>
                                        @foreach (App\Models\Merchant::where('merchant_type','sub_merchant')->where('create_by',auth()->guard('merchant')->user()->id)->get() as $sub_merchant)
                                            <option value="{{ $sub_merchant->id }}">{{ $sub_merchant->fullname }}</option>
                                        @endforeach
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
                                    <label for="message">End Date</label>
                                    <input type="date" class="form-control" name="end_date" id="end_date">
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="sender">{{ translate('Select Payament Type') }}</label>

                                    <select class="form-control" name="payment_type" id="payment_type">
                                        <option value=""  selected>Select Payment Type</option>
                                        <option value="credit">Add (+)</option>
                                        <option value="debit">Return (-) </option>
                                    </select>

                                </div>
                            </div>


                            <div class="col-md-2">
                                <div class="form-group d-grid">
                                    <label class="" for="">&nbsp;</label>
                                    <button type="submit" class="btn btn-danger btn-block" id="search-button"><span
                                            class="fa fa-search"></span> {{ __('Search') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive" id="tableContentWrap">
                        @include('merchant.reports.payment_report.table')
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('js')
    {{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
    <!-- If you're using Select2 or other plugins, ensure they're included -->
    <script type="text/javascript">
        $(document).ready(function() {

            var $submitButton = $('#search-button');
            var originalButtonHTML = $submitButton.html();

            // Function to fetch and update the table
            function fetchTable(url) {
                $.ajax({
                    url: url,
                    method: 'GET',
                    data: $('#search-form').serialize(),
                    beforeSend: function() {
                        // Optional: Show a loader or disable the form
                        $('#tableContentWrap').html(
                            '<div class="text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>'
                        );
                    },
                    success: function(data) {
                        $('#tableContentWrap').html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(error);
                        $('#tableContentWrap').html(
                            '<div class="alert alert-danger">An error occurred while fetching data.</div>'
                        );
                    },
                    complete: function() {
                        // Re-enable the button
                        $submitButton.prop('disabled', false);
                        // Restore the original button HTML
                        $submitButton.html(originalButtonHTML);
                        // Optional: Hide loader or overlay if used
                    }
                });
            }

            // Handle form submission
            $('#search-form').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission
                var route = $(this).data('route'); // Get the route from data-route
                fetchTable(route);
            });

            // Handle pagination links
            $(document).on('click', '#tableContentWrap .pagination a', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                fetchTable(url);
            });

            // Optional: Trigger search on filter change without form submission
            // Example:
            /*
            $('#rows, #profileType, #selectMerchant, #selectPartner, #start_date, #end_date, #payment_type').on('change', function() {
                var route = $('#search-form').data('route');
                fetchTable(route);
            });
            */
        });
    </script>
@endpush
