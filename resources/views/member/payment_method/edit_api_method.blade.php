@extends('member.layout.member_app')
@section('title', 'Dashboard')
@push('css')
@endpush
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
        <div class="col-xl-6 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">Edit Payment Method for Mobile Banking</h5>
                    <hr>
                    <form class="row g-3" action="{{ route('api_method_update') }}" method="POST">
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
                            <label for="rows">Username / Mobile Number<span>*</span></label>
                            <input type="text" class="form-control" name="username" value="{{ $data->sim_id }}"
                                required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">Password / Merchant ID<span>*</span></label>
                            <input type="text" class="form-control" name="password" value="{{ $data->password }}"
                                required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">App Key / Public Gateway Key<span>*</span></label>
                            <input type="text" class="form-control" name="app_key" value="{{ $data->app_key }}" required>
                        </div>

                        <div class="col-md-12">
                            <label for="rows">App Secret/ Private Key<span>*</span></label>
                            
                             <textarea name="app_secret" class="form-control"  required>{{ $data->app_secret }}</textarea>
                            
                        </div>
                        
                        	<div class="col-md-12">
										
										  <label class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name="status" value="1" <?php if($data->status==1) { echo "checked='checked'";} ?>>
                                        <span class="custom-control-label">{{ translate('active')}}
                                        </span>
                                    </label>
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

