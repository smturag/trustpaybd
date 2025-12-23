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
                    <form class="row g-3" action="{{ route('withdraw.create_payment_method') }}" method="POST">
                        @csrf
                        <div class="col-md-12">
                            <label for="rows">Select from MFS operator list</label>
                            <select class="form-control" name="mfs_name" id="rows" required>
                                <option value="" disabled selected>Select MFS operator</option>
                                @foreach (App\Models\MfsOperator::mfsList(1)->get() as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="rows">Select Type <span>*</span></label>
                            <select class="form-control" name="agent_type" id="rows" required>
                                <option value="" disabled selected>Select type</option>
                                <option value="personal">Personal</option>
                                <option value="agent">Agent</option>
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
