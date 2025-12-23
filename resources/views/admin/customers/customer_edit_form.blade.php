@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')

@section('content')

    <div class="row">
        <div class="col-xl-6">
            <div class="card">
                <div class="card-body p-4">

                    <h5 class="mb-4">{{ translate('customer') }} Edit</h5>

                    <form class="row g-3" action="{{ route('customer_update', ['id' => $customer->id]) }}" method="POST">
                        @csrf

                        <div class="col-md-12">
                            <label for="customer_name" class="form-label">Full Name</label>
                            <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror" id="customer_name" value="{{ $customer->customer_name }}">
                            @error('customer_name')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" value="{{$customer->email}}">
                            @error('email')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="mobile" class="form-label">Phone</label>
                            <input type="text" name="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" value="{{ $customer->mobile }}">
                            @error('mobile')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" id="password">
                            @error('password')
                            <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-12">
                            <label class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" name="status" value="1" <?php if ($customer->status == 1) {
                                    echo "checked='checked'";
                                } ?>>
                                <span class="custom-control-label">{{ translate('active')}}</span>
                            </label>
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
