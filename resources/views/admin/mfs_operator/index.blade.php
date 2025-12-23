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
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">MFS List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('mfs.create_mfs') }}">
                    <i class="bx bx-plus mr-1"></i> New MFS
                </a>
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
                    <table id="user-table" class="display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Deposit Fee</th>
                                <th>Deposit Commission</th>
                                <th>Withdraw Fee</th>
                                <th>Withdraw Commission</th>
                                <th>Status</th>
                                <th>Action</th>

                                <!-- Add more columns as needed -->
                            </tr>
                        </thead>
                        <tbody>
                            {{-- Loop through the $users collection and generate table rows --}}
                            @foreach ($data as $key=>$data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td><img src="{{ asset( $data->image) }}" alt="Image"style="width: 40px; height: 40px;"></td>
                                    <td>{{ $data->name }}</td>
                                    <td>{{ $data->type }}</td>
                                    <td>{{ $data->deposit_fee }}</td>
                                    <td>{{ $data->deposit_commission }}</td>
                                    <td>{{ $data->withdraw_fee }}</td>
                                    <td>{{ $data->withdraw_commission }}</td>
                                    {{-- @dd($data->status) --}}
                                    <td><input class="form-check-input" data-mfs_id="{{ $data->id }}" id="switch-button"
                                            type="checkbox" role="switch" id="flexSwitchCheckDefault1" name="mfs_status"
                                            data-value="{{ $data->status }}" {{ $data->status == 1 ? 'checked' : '' }}></td>
                                    <td>
                                        <div class="btn-group ">
                                            <button type="button" class="btn btn-warning">Action</button>
                                            <button type="button"
                                                class="btn btn-warning split-bg-warning dropdown-toggle dropdown-toggle-split"
                                                data-bs-toggle="dropdown" aria-expanded="false"> <span
                                                    class="visually-hidden">Toggle Dropdown</span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a class="dropdown-item"
                                                        href="{{ route('mfs.edit_mfs_view', ['id' => $data->id]) }}"><button
                                                            type="button" class="btn btn-info px-5" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModal">Edit</button></a>
                                                </li>
                                                <li>
                                                    <form action="{{ route('mfs.destroy') }}" method="POST"
                                                        id="deleteForm">
                                                        @csrf
                                                        <input hidden name="id" value=" {{ $data->id }}">
                                                        <a class="dropdown-item" href="#"><button type="button"
                                                                class="btn btn-danger px-5"
                                                                onclick="confirmDelete()">Delete</button></a>

                                                    </form>
                                                </li>

                                            </ul>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>


                </div>

            </div>
        </div>
    </div>
    </div>


@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#user-table').DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0], // Disable sorting on the "SL" column
                    responsive: true,
                    select: true
                }]
            });

            // $('#switch-button').on('change', function() {
            //     var id = $(this).data('mfs_id');

            //     var status = $(this).data('value');
            //     console.log(status);
            //     console.log(id);

            //     var url = "{{ route('mfs.status_update') }}";

            //     $.ajax({
            //         url: url,
            //         type: 'POST',
            //         data: {
            //             // Pass any data you want to send with the request
            //             id: id,
            //             status: status
            //         },
            //         headers: {
            //             'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token to the request
            //         },
            //         success: function(response) {
            //             // Handle the response
            //             // console.log(response);
            //             location.reload();
            //         },
            //         error: function(xhr, status, error) {
            //             // Handle error case
            //             console.log('Error:', error);
            //         }
            //     });

            // })
            $('#user-table').on('click', '.form-check-input', function() {
                var row = $(this).closest('tr');
                // var id = row.find('.id').text();
                var pm_id = $(this).data('mfs_id');
                var status = $(this).data('value');
                var url = "{{ route('mfs.status_update') }}";
                // var checked = $(this).is(':checked');
                // console.log('ID:', pm_id, 'Checked:', checked);


                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        // Pass any data you want to send with the request
                        id: pm_id,

                        status: status
                    },
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token to the request
                    },
                    success: function(response) {
                        // Handle the response
                        // console.log(response);
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        // Handle error case
                        console.log('Error:', error);
                    }
                });
            });


        });


        function confirmDelete() {
            if (confirm('Are you sure you want to delete this record?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endpush
