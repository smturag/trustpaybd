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
                <h6 class="mb-0 text-uppercase ps-3">Mobile Banking List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('payment.mobile_banking_create_view') }}">
                    <i class="bx bx-plus mr-1"></i> Add Mobile Banking User
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
                    <table id="data_table" class="display">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User Name</th>
                                <th>Operator Name</th>
                                <th>Type</th>
                                <th>Sim Number</th>
                                <th>Live Status</th>
                                <th>Status</th>
                                <th>Action</th>

                                <!-- Add more columns as needed -->
                            </tr>
                        </thead>


                        <tbody>
                            {{-- Loop through the $users collection and generate table rows --}}
                            @foreach ($data as $key => $data)
                            
                             @php
                                $getModem = App\Models\Modem::where('sim_number',$data->sim_id)->first();
                                $user = App\Models\User::where('member_code',$getModem->member_code)->first();
                                
                         
                                $getMfs = DB::table('mfs_operators')->find($data->mobile_banking);
                              
                            @endphp
                            
                            
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->fullname }}</td>
                                   <td>{{ $getMfs->name }}</td>
                                    <td>{{ $data->type }}</td>
                                   <td>{{ $data->sim_id }}</td>
                                    <td>{!! getLiveStatus($data->sim_id) !!}</td>
                                    <td>
                                        <input class="form-check-input" data-pm_id="{{ $data->id }}" id="switch-button"
                                            type="checkbox" role="switch" id="flexSwitchCheckDefault1" name="pm_status"
                                            data-value="{{ $data->status }}" {{ $data->status == 1 ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        <form action="{{ route('payment.destroy') }}" method="POST" id="deleteForm">
                                        @csrf
                                        <input hidden name="id" value=" {{ $data->id }}">
                                        <button onclick="confirmDelete()" type="button" class="btn btn-outline-danger px-5">Delete</button>
                                        </form>
                                        {{-- <div class="btn-group">
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
                                                    <form action="{{ route('payment.destroy') }}" method="POST"
                                                        id="deleteForm">
                                                        @csrf
                                                        <input hidden name="id" value=" {{ $data->id }}">
                                                        <a class="dropdown-item" href="#"><button type="button"
                                                                class="btn btn-danger px-5"
                                                                onclick="confirmDelete()">Delete</button></a>

                                                    </form>
                                                </li>

                                            </ul>
                                        </div> --}}
                                    </td>



                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $('#data_table').DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0], // Disable sorting on the "SL" column
                    responsive: true,
                    select: true
                }]
            });

            $('#data_table').on('click', '.form-check-input', function() {
                var row = $(this).closest('tr');
                // var id = row.find('.id').text();
                var pm_id = $(this).data('pm_id');
                var status = $(this).data('value');
                var url = "{{ route('payment.edit_status_mobile_banking') }}";
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
        })
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this record?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endpush
