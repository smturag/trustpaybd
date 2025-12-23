@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')
@push('css')
@endpush
@section('content')

    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
    @endif

    @if (Session::has('alert'))
        <div class="alert alert-danger">{{ Session::get('alert') }}</div>
    @endif


    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit Payment Method for Mobile Banking</h5>
                    <hr>
                    <form class="row g-3" action="{{ route('payment.api_method_update') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" value="{{ $data->id }}">

                        @php
                            $payment_method_info = App\Models\PaymentMethod::find($data->id);
                            $get_name = App\Models\MfsOperator::find($payment_method_info->mobile_banking)->name;
                        @endphp

                        <div class="col-md-12">
                            <label for="rows">Select from MFS operator list <span>*</span></label>
                            <select class="form-control" name="mfs_name" id="rows" required>
                                <option value="" disabled selected>Select MFS operator</option>
                                <option value="bkash" {{ $get_name == 'bkash' ? 'selected' : '' }}>Bkash</option>
                                <option value="nagad" {{ $get_name == 'nagad' ? 'selected' : '' }}>Nagad</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="rows">Username<span>*</span></label>
                            <input type="text" class="form-control" name="username" value="{{ $data->member_code }}"
                                required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">Password<span>*</span></label>
                            <input type="text" class="form-control" name="password" value="{{ $data->password }}"
                                required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">App Key<span>*</span></label>
                            <input type="text" class="form-control" name="app_key" value="{{ $data->app_key }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">App Secret<span>*</span></label>
                            <input type="text" class="form-control" name="app_secret" value="{{ $data->app_secret }}"
                                required>
                        </div>




                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Submit</button>

                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $('#agent_select').select2({
                placeholder: 'Select an option',
                maximumSelectionLength: 2,
            });

            $('#modem_select').select2({
                placeholder: 'Select an option',
                maximumSelectionLength: 2,
            });


            $('#agent_select').on('change', function() {

                var member_code = $('#agent_select').val();
                console.log(member_code)

                var url = "{{ route('payment.agents_modems', ':id') }}";
                url = url.replace(':id', member_code);

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function(response) {

                        var options = response;
                        console.log(options)
                        $.each(options, function(index, value) {
                            console.log(value.id);
                            $('#modem_select').append('<option value="' + value.id +
                                '">' + value.text + '</option>');
                        });

                        $("#modem_select").trigger("change");

                    },
                    error: function(xhr, status, error) {
                        console.log("Error:", error);
                    }
                });


            })
        })
    </script>
@endpush
