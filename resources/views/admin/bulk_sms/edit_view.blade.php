
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
                    <h5 class="mb-4">Add Payment Method for Mobile Banking</h5>
                    <hr>
                    <form class="row g-3" action="{{ route('bulk_sms.update',$data->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                      
                        <div class="col-md-12">
                            <label for="rows">Select from sms provider</label>
                            <select class="form-control" name="provider" id="rows" required>
                                <option value="sms_city"selected>SMS City</option>

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="bsValidation3" class="form-label">Common Link</label>
                            <input type="text" name="common_link" class="form-control" id="bsValidation3" value="{{$data->common_link}}" placeholder="Common Link" required>
                            <div class="invalid-feedback">
                                Please provide common link
                              </div>
                        </div>
                        <div class="col-md-12">
                            <label for="bsValidation3" class="form-label">Access token</label>
                            <input type="text" value="{{$data->access_token}}" name="access_token" class="form-control" id="bsValidation3" placeholder="Access token" required>
                            <div class="invalid-feedback">
                                Please provide access token
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
    <script>

    </script>
@endpush
