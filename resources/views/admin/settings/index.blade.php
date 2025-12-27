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

    @php
        $check_wallet = app_config('wallet_payment_status');
       $check_wallet_status = $check_wallet=="false"?0:1;
       try {
           $maintenance_mode = \App\Models\SystemSetting::isMaintenanceMode();
       } catch (\Exception $e) {
           $maintenance_mode = false;
       }
    //    dd($check_wallet_status);
    @endphp
    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">App Config</h6>
                {{-- <a class="ms-auto btn btn-sm btn-primary" href="{{ route('mfs.create_mfs') }}">
                    <i class="bx bx-plus mr-1"></i> New MFS
                </a> --}}
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

                    <div class="row">
                        <!-- Database Update Card -->
                        <div class="col-xl-6 mx-auto mb-4">
                            <div class="card border-primary">
                                <div class="card-header px-4 py-3 bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="bx bx-data"></i> Database Update
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="alert alert-info">
                                        <i class="bx bx-info-circle"></i>
                                        <strong>Update Database:</strong> Apply all pending database migrations and updates safely.
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Database Status</h6>
                                            <p class="mb-0 text-muted">
                                                Keep your database schema up to date
                                            </p>
                                        </div>
                                        <a href="{{ route('admin.database.update') }}" class="btn btn-primary">
                                            <i class="bx bx-refresh"></i> Update Database
                                        </a>
                                    </div>

                                    <div class="mt-3 pt-3 border-top">
                                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                            <i class="bx bx-shield-quarter"></i> 
                                            Safe to run on live. No data will be lost.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Mode Card -->
                        <div class="col-xl-6 mx-auto mb-4">
                            <div class="card border-danger">
                                <div class="card-header px-4 py-3 bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="bx bx-wrench"></i> Maintenance Mode
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="alert alert-warning">
                                        <i class="bx bx-info-circle"></i>
                                        <strong>Important:</strong> When maintenance mode is ON, all API calls, merchant panel, and customer panel will be blocked. Only admin panel will remain accessible.
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">System Status</h6>
                                            <p class="mb-0 text-muted">
                                                Current Status: 
                                                <span id="maintenance-status-text" class="badge {{ $maintenance_mode ? 'bg-danger' : 'bg-success' }} ms-2">
                                                    {{ $maintenance_mode ? 'UNDER MAINTENANCE' : 'OPERATIONAL' }}
                                                </span>
                                            </p>
                                        </div>
                                        <div class="form-check form-switch" style="transform: scale(1.5);">
                                            <input class="form-check-input" type="checkbox" id="maintenanceModeToggle" 
                                                   {{ $maintenance_mode ? 'checked' : '' }}>
                                        </div>
                                    </div>

                                    <div class="mt-3 pt-3 border-top">
                                        <p class="text-muted mb-0" style="font-size: 0.85rem;">
                                            <i class="bx bx-shield-quarter"></i> 
                                            Toggle ON to enable maintenance mode. Toggle OFF to allow transactions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 mx-auto">
                            <div class="card">
                                <div class="card-header px-4 py-3">
                                    <h5 class="mb-0">App Config</h5>
                                </div>
                                <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="card-body p-4">
                                        <form class="row g-3 needs-validation was-validated" novalidate="">
                                            <div class="col-md-12">
                                                <label for="bsValidation1" class="form-label">Brand Name</label>
                                                <input type="text" class="form-control" id="bsValidation1"
                                                    placeholder="AppName" name="AppName" value="{{ app_config('AppName') }}"
                                                    required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="bsValidation1" class="form-label">Whatsapp Support Number</label>
                                                <input type="text" class="form-control" id="bsValidation1"
                                                    placeholder="support_whatsapp_number" name="support_whatsapp_number" value="{{ app_config('support_whatsapp_number') }}"
                                                    required>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <label for="bsValidation2" class="form-label">Brand Image</label>
                                                <input type="file" class="form-control" name="AppLogo" id="bsValidation2"
                                                    placeholder="logo">
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>


                                            <div class="col-md-12">
                                                <label for="bsValidation2" class="form-label">Wallet Payment Status</label>
                                                <Select class="form-control" name="wallet_payment_status">
                                                    <option value="true"
                                                        {{$check_wallet_status=='1' ? 'selected' : '' }}>
                                                        Enable</option>
                                                    <option value="false"
                                                        {{ $check_wallet_status=='0' ? 'selected' : '' }}>
                                                        Disable</option>
                                                </Select>
                                                <div class="valid-feedback">
                                                    Looks good!
                                                </div>
                                            </div>

                                            </Select>
                                            <div class="valid-feedback">
                                                Looks good!
                                            </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="d-md-flex d-grid align-items-center gap-3">
                                            <button type="submit" class="btn btn-primary px-4">Submit</button>
                                            <button type="reset" class="btn btn-light px-4">Reset</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>
    </div>
    </div>


@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Handle maintenance mode toggle
            $('#maintenanceModeToggle').on('change', function() {
                const isChecked = $(this).is(':checked');
                const status = isChecked ? '1' : '0';
                
                // Confirm before changing
                const action = isChecked ? 'enable' : 'disable';
                const message = isChecked 
                    ? 'Are you sure you want to enable maintenance mode? This will block all API calls, merchant and customer access.'
                    : 'Are you sure you want to disable maintenance mode? This will allow all transactions.';
                
                if (!confirm(message)) {
                    // Revert toggle if cancelled
                    $(this).prop('checked', !isChecked);
                    return;
                }

                // Show loading
                const $statusText = $('#maintenance-status-text');
                $statusText.removeClass('bg-success bg-danger').addClass('bg-warning').text('UPDATING...');
                $(this).prop('disabled', true);

                // Send AJAX request
                $.ajax({
                    url: '{{ route('admin.settings.toggle_maintenance') }}',
                    type: 'POST',
                    data: {
                        status: status,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update status display
                            if (status === '1') {
                                $statusText.removeClass('bg-warning bg-success').addClass('bg-danger').text('UNDER MAINTENANCE');
                            } else {
                                $statusText.removeClass('bg-warning bg-danger').addClass('bg-success').text('OPERATIONAL');
                            }

                            // Show success message
                            toastr.success(response.message);
                        } else {
                            toastr.error('Failed to update maintenance mode');
                            // Revert toggle
                            $('#maintenanceModeToggle').prop('checked', !isChecked);
                            updateStatusDisplay(!isChecked);
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Error updating maintenance mode');
                        // Revert toggle
                        $('#maintenanceModeToggle').prop('checked', !isChecked);
                        updateStatusDisplay(!isChecked);
                    },
                    complete: function() {
                        $('#maintenanceModeToggle').prop('disabled', false);
                    }
                });
            });

            function updateStatusDisplay(isEnabled) {
                const $statusText = $('#maintenance-status-text');
                if (isEnabled) {
                    $statusText.removeClass('bg-success').addClass('bg-danger').text('UNDER MAINTENANCE');
                } else {
                    $statusText.removeClass('bg-danger').addClass('bg-success').text('OPERATIONAL');
                }
            }
        });
    </script>
@endpush
