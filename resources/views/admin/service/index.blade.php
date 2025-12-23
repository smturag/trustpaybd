@extends('admin.layouts.admin_app')
@section('title', 'Service')
@section('content')

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-none d-sm-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Service Request List</h6>
            </div>
            <hr />

            @if (session('message'))
                <div class="alert alert-success border-0 bg-success alert-dismissible fade show py-2">
                    <div class="d-flex align-items-center">
                        <div class="font-35 text-white"><i class="bx bxs-check-circle"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="mb-0 text-white">Success Alerts</h6>
                            <div class="text-white">{{ session('message') }}</div>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="tabletable-striped table-hover text-center" id="myTable">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    {{-- <th>Action</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @if ($item->status == 0)
                                                <a href="{{ route('service.change_status', ['id' => $item->id]) }}" onclick="return confirm('Are you sure you want to change the status?')">
                                                    <span class="badge bg-danger">Deactivate</span>
                                                </a>
                                            @else
                                                <a href="{{ route('service.change_status', ['id' => $item->id]) }}" onclick="return confirm('Are you sure you want to change the status?')">
                                                    <span class="badge bg-success">Active</span>
                                                </a>
                                            @endif
                                        </td>
                                        {{-- <td>{{ $key + 1 }}</td> --}}

                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script>
            $(document).ready(function() {
                $('#myTable').DataTable();
            });
        </script>
    @endpush

@endsection
