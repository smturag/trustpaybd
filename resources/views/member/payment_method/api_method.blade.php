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
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Api Method List</h6>
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('add_api_method') }}">
                    <i class="bx bx-plus mr-1"></i> Add Api Method
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
                                <th>Operator Name</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>

                            @foreach ($data as $key => $item)
                                @php
                                    $payment_method_info = App\Models\PaymentMethod::find($item->id);
                                    $get_name = App\Models\MfsOperator::find($payment_method_info->mobile_banking)
                                        ->name;
                                @endphp
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $get_name }}</td>
                                    <td>{{ $item->sim_id }}</td>
                                    <td>{{ $item->type }}</td>

                                    <td>
                                        <input class="form-check-input" data-pm_id="{{ $item->id }}" id="switch-button"
                                            type="checkbox" role="switch" id="flexSwitchCheckDefault1" name="pm_status"
                                            data-value="{{ $item->status }}" {{ $item->status == 1 ? 'checked' : '' }}>
                                    </td>
                                    <td>
                                        
                                        <a href="{{ route('api_method_edit',['id'=>$item->id]) }}" class="btn btn-outline-primary mt-2">Edit</a>
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
