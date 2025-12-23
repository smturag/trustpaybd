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
                    <h5 class="mb-4">Add Currency</h5>
                    <hr>
                    <form class="row g-3" action="{{ route('crypto.save_currency_form') }}" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label for="currency_name">Currency Name</label>
                            <select class="form-control" name="currency_name" id="currency_name" required>
                                <option value="">--Select--</option>
                                <option value="usdt">USDT</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="network">Network <span>*</span></label>
                            <select class="form-control" name="network" id="network" required>
                                <option value="">--Select--</option>
                                <option value="TRON_(TRC20)">TRON (TRC20)</option>
                            </select>
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
        $(document).ready(function () {

        });
    </script>
@endpush
