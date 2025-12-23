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

    @if($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


   

    <div class="row">
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Check BULK SMS Provider</h5>
                    <hr>
                    <form class="row g-3" action="{{ route('bulk_sms.check_sms_connection') }}" method="POST">
                        @csrf

                        <input type="text"  value="{{$data->access_token}}" name="provider_info" hidden>

                        <div class="col-md-12">
                            <label for="bsValidation3" class="form-label">Choose SMS Type tesing</label>
                            <div class="form-check ">
                                <div class="ml-4"> <input class="form-check-input" value="0" type="radio"
                                        name="check_type" id="check_type" checked>
                                    <label class="form-check-label"  for="flexRadioDefault1">
                                        SIM SMS
                                    </label>
                                </div>

                                <div>

                                    <input class="form-check-input" value="1" type="radio" name="check_type" id="check_type">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Whatsapp SMS
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="NUMBER" class="form-label">Reciver Mobile Number</label>
                            <input type="text" name="mobile_number" class="form-control" id="Number"
                                placeholder="number input with country code 8801xxxxxxxxx" required>
                            <div class="invalid-feedback">
                                Please provide Reciver Number
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="body" class="form-label">SMS Body</label>
                            <textarea name="smsbody" class="form-control" id="body" required></textarea>
                            <div class="invalid-feedback">
                                Please provide sms body
                            </div>
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
    <script></script>
@endpush
