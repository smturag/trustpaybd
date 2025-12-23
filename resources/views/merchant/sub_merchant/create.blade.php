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
                <h6 class="mb-0 text-uppercase ps-3">Sub Merchant Create</h6>
                {{-- Uncomment this if you want to add a "New Payment Request" button --}}
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('sub_merchant.list') }}">
                    <i class="bx bx-plus mr-1"></i> Back
                </a>
            </div>
            <hr />

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card  col-md-6 mx-auto">
                <div class="card-body d-flex justify-content-center align-items-center" style="height: 100%;">
                    <div class="item-content w-100">
                        <form class="row g-3" action="{{ route('sub_merchant.store') }}" method="POST">
                            @csrf
                            <div class="col-md-12">
                                <label for="fullname" class="form-label">Full Name</label>
                                <input type="text" name="fullname" class="form-control @error('fullname') is-invalid @enderror" id="fullname" placeholder="Full Name">
                                @error('fullname')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" onkeyup="notspace(this);" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="Email">
                                @error('email')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="mobile" class="form-label">Mobile</label>
                                <input type="tel" onkeyup="notspace(this);" name="mobile" class="form-control @error('mobile') is-invalid @enderror" id="mobile" placeholder="Mobile">
                                @error('mobile')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" onkeyup="notspace(this);" name="password" class="form-control @error('password') is-invalid @enderror" id="password" placeholder="Password">
                                @error('password')
                                    <div class="alert alert-danger mt-2">{{ $message }}</div>
                                @enderror
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
    </div>

@endsection

@push('js')
    {{-- Add your JavaScript here if needed --}}
@endpush
