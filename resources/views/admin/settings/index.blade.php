@extends('admin.layouts.admin_app')
@section('title', 'Dashboard')
@push('css')
<style>
    .settings-card {
        border-radius: 15px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.3s ease;
        border: none;
        overflow: hidden;
    }

    .settings-card:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .settings-card .card-header {
        border-bottom: 2px solid rgba(0, 0, 0, 0.05);
        font-weight: 600;
    }

    .form-label {
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .form-label i {
        font-size: 1.1rem;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
    }

    .input-group .form-control {
        border-left: none;
    }

    .input-group:focus-within .input-group-text {
        background-color: #e7f1ff;
        border-color: #86b7fe;
    }

    .input-group:focus-within .form-control {
        border-color: #86b7fe;
    }

    .image-preview-box {
        display: inline-block;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 8px;
        margin-top: 8px;
        border: 2px dashed #dee2e6;
    }

    .image-preview-box img {
        display: block;
    }

    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        border-radius: 15px;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 5px 20px rgba(102, 126, 234, 0.3);
    }

    .page-header h4 {
        margin: 0;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .page-header p {
        margin: 0.5rem 0 0 0;
        opacity: 0.95;
    }

    .submit-section {
        background: #f8f9fa;
        padding: 1.5rem;
        border-radius: 10px;
        margin-top: 1.5rem;
    }

    .btn-primary {
        padding: 0.6rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(13, 110, 253, 0.3);
    }

    .btn-light {
        padding: 0.6rem 2rem;
        font-weight: 600;
        border-radius: 8px;
        border: 2px solid #dee2e6;
    }

    .system-card {
        height: 100%;
    }
</style>
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
    @endphp

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="page-header">
                    <h4>
                        <i class="bx bx-cog"></i>
                        Application Settings
                    </h4>
                    <p>Configure your application settings, branding, and system preferences</p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 mx-auto">

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bx bx-error-circle me-2"></i>
                    <strong>Oops! There were some errors:</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <!-- Database Update Card -->
                        <div class="col-xl-6 mb-4">
                            <div class="card settings-card system-card border-primary">
                                <div class="card-header px-4 py-3 bg-primary text-white">
                                    <h5 class="mb-0">
                                        <i class="bx bx-data"></i> Database Update
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="alert alert-info border-0">
                                        <i class="bx bx-info-circle"></i>
                                        <strong>Update Database:</strong> Apply all pending database migrations and updates safely.
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">Database Status</h6>
                                            <p class="mb-0 text-muted small">
                                                Keep your database schema up to date
                                            </p>
                                        </div>
                                        <a href="{{ route('admin.database.update') }}" class="btn btn-primary">
                                            <i class="bx bx-refresh"></i> Update Database
                                        </a>
                                    </div>

                                    <div class="mt-3 pt-3 border-top">
                                        <p class="text-muted mb-0 small">
                                            <i class="bx bx-shield-quarter"></i> 
                                            Safe to run on live. No data will be lost.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Maintenance Mode Card -->
                        <div class="col-xl-6 mb-4">
                            <div class="card settings-card system-card border-danger">
                                <div class="card-header px-4 py-3 bg-danger text-white">
                                    <h5 class="mb-0">
                                        <i class="bx bx-wrench"></i> Maintenance Mode
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="alert alert-warning border-0">
                                        <i class="bx bx-info-circle"></i>
                                        <strong>Important:</strong> When maintenance mode is ON, all API calls, merchant panel, and customer panel will be blocked. Only admin panel will remain accessible.
                                    </div>
                                    
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <h6 class="mb-1">System Status</h6>
                                            <p class="mb-0 text-muted small">
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
                                        <p class="text-muted mb-0 small">
                                            <i class="bx bx-shield-quarter"></i> 
                                            Toggle ON to enable maintenance mode. Toggle OFF to allow transactions.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- App Configuration Card -->
                <div class="col-xl-12">
                    <div class="card settings-card">
                        <div class="card-header px-4 py-3 bg-gradient" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                            <h5 class="mb-0">
                                <i class="bx bx-cog"></i> Application Configuration
                            </h5>
                        </div>
                        <form action="{{ route('settings.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Brand Name -->
                                    <div class="col-md-6">
                                        <label for="brandName" class="form-label">
                                            <i class="bx bx-store text-primary"></i>
                                            Brand Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-store"></i></span>
                                            <input type="text" class="form-control" id="brandName"
                                                placeholder="Enter your brand name" name="AppName" value="{{ app_config('AppName') }}"
                                                required>
                                        </div>
                                        <small class="text-muted">This will appear throughout your application</small>
                                    </div>

                                    <!-- WhatsApp Support -->
                                    <div class="col-md-6">
                                        <label for="whatsapp" class="form-label">
                                            <i class="bx bxl-whatsapp text-success"></i>
                                            WhatsApp Support Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bxl-whatsapp"></i></span>
                                            <input type="text" class="form-control" id="whatsapp"
                                                placeholder="+880 1XXX-XXXXXX" name="support_whatsapp_number" value="{{ app_config('support_whatsapp_number') }}"
                                                required>
                                        </div>
                                        <small class="text-muted">Customer support contact number</small>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            <i class="bx bx-envelope text-info"></i>
                                            Support Email
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                            <input type="email" class="form-control" id="email"
                                                placeholder="support@example.com" name="email" value="{{ app_config('email') }}">
                                        </div>
                                        <small class="text-muted">Primary support email address</small>
                                    </div>

                                    <!-- Telegram ID -->
                                    <div class="col-md-6">
                                        <label for="telegram_id" class="form-label">
                                            <i class="bx bxl-telegram text-primary"></i>
                                            Telegram ID
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bxl-telegram"></i></span>
                                            <input type="text" class="form-control" id="telegram_id"
                                                placeholder="@username or chat_id" name="telegram_id" value="{{ app_config('telegram_id') }}">
                                        </div>
                                        <small class="text-muted">Your Telegram username or chat ID</small>
                                    </div>

                                    <!-- Brand Logo -->
                                    <div class="col-md-6">
                                        <label for="appLogo" class="form-label">
                                            <i class="bx bx-image text-warning"></i>
                                            Brand Image (Logo)
                                        </label>
                                        <input type="file" class="form-control" name="AppLogo" id="appLogo"
                                            accept="image/*">
                                        @if(app_config('AppLogo'))
                                            <div class="image-preview-box mt-2">
                                                <small class="text-muted d-block mb-2">Current Logo:</small>
                                                <img src="{{ asset('storage/'.app_config('AppLogo')) }}" height="50" style="max-width: 150px; object-fit: contain;" alt="logo">
                                            </div>
                                        @endif
                                        <small class="text-muted d-block mt-1">Recommended: PNG format, transparent background</small>
                                    </div>

                                    <!-- Favicon -->
                                    <div class="col-md-6">
                                        <label for="favicon" class="form-label">
                                            <i class="bx bx-bookmark text-danger"></i>
                                            Favicon
                                        </label>
                                        <input type="file" class="form-control" name="favicon" id="favicon"
                                            accept="image/x-icon,image/png,image/jpeg,image/gif">
                                        @if(app_config('favicon'))
                                            <div class="image-preview-box mt-2">
                                                <small class="text-muted d-block mb-2">Current Favicon:</small>
                                                <img src="{{ asset('storage/'.app_config('favicon')) }}" height="32" style="width: 32px; object-fit: contain;" alt="favicon">
                                            </div>
                                        @endif
                                        <small class="text-muted d-block mt-1">Recommended: 32x32 or 64x64 pixels, ICO or PNG</small>
                                    </div>

                                    <!-- Wallet Payment Status -->
                                    <div class="col-md-6">
                                        <label for="walletStatus" class="form-label">
                                            <i class="bx bx-wallet text-success"></i>
                                            Wallet Payment Status
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-wallet"></i></span>
                                            <select class="form-control form-select" name="wallet_payment_status" id="walletStatus">
                                                <option value="true" {{ $check_wallet_status=='1' ? 'selected' : '' }}>
                                                    <i class="bx bx-check-circle"></i> Enable
                                                </option>
                                                <option value="false" {{ $check_wallet_status=='0' ? 'selected' : '' }}>
                                                    <i class="bx bx-x-circle"></i> Disable
                                                </option>
                                            </select>
                                        </div>
                                        <small class="text-muted">Allow customers to pay using wallet balance</small>
                                    </div>
                                </div>

                                <!-- Submit Section -->
                                <div class="submit-section">
                                    <div class="d-flex align-items-center gap-3 flex-wrap">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bx bx-save me-2"></i>Save Changes
                                        </button>
                                        <button type="reset" class="btn btn-light">
                                            <i class="bx bx-reset me-2"></i>Reset
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
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
