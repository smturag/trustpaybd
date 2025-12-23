@extends('customer-panel.layout.customer_app')
@section('title', 'Customer Profile')

@push('css')
<style>
    /* Enhanced Profile page styles */
    .profile-card {
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        overflow: hidden;
        margin-bottom: 25px;
    }

    .profile-card:hover {
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        transform: translateY(-5px);
    }

    .profile-card .card-body {
        padding: 1.8rem;
    }

    .profile-card .card-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
        position: relative;
        padding-bottom: 0.75rem;
    }

    .profile-card .card-title:after {
        content: '';
        position: absolute;
        left: 0;
        bottom: 0;
        height: 3px;
        width: 50px;
        background: var(--bs-primary);
        border-radius: 10px;
    }

    .profile-image-container {
        position: relative;
        width: 160px;
        height: 160px;
        margin: 0 auto;
        border-radius: 50%;
        padding: 5px;
        background: linear-gradient(145deg, var(--bs-primary), var(--bs-info));
    }

    .profile-image {
        width: 150px;
        height: 150px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
    }

    .profile-image-edit {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: var(--bs-primary);
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        z-index: 1;
    }

    .profile-image-edit:hover {
        transform: scale(1.1) rotate(5deg);
        background: var(--bs-info);
    }

    .profile-info {
        padding: 25px 0 15px;
        text-align: center;
    }

    .profile-info h4 {
        margin-bottom: 8px;
        font-weight: 700;
        font-size: 1.4rem;
    }

    .profile-info p {
        color: #6c757d;
        font-size: 0.95rem;
    }

    .profile-badge {
        display: inline-block;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
        margin-top: 10px;
        background: rgba(var(--bs-primary-rgb), 0.1);
        color: var(--bs-primary);
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        margin: 20px 0;
        padding: 15px 0;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    }

    .profile-stat-item {
        text-align: center;
    }

    .profile-stat-value {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--bs-primary);
    }

    .profile-stat-label {
        font-size: 0.8rem;
        color: #6c757d;
    }

    .form-label {
        font-weight: 600;
        margin-bottom: 10px;
        font-size: 0.9rem;
        color: #495057;
    }

    .form-control {
        border-radius: 10px;
        padding: 12px 15px;
        border: 1px solid #e2e8f0;
        font-size: 0.95rem;
        transition: all 0.3s;
    }

    .form-control:focus {
        box-shadow: 0 0 0 3px rgba(var(--bs-primary-rgb), 0.15);
        border-color: var(--bs-primary);
    }

    .input-group-text {
        border-radius: 10px 0 0 10px;
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        border: 1px solid #e2e8f0;
        border-right: none;
        color: var(--bs-primary);
    }

    .btn-profile {
        border-radius: 10px;
        padding: 12px 25px;
        font-weight: 600;
        letter-spacing: 0.3px;
        text-transform: uppercase;
        font-size: 0.85rem;
        box-shadow: 0 4px 10px rgba(var(--bs-primary-rgb), 0.2);
        transition: all 0.3s;
    }

    .btn-profile:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(var(--bs-primary-rgb), 0.3);
    }

    .password-toggle {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 15px;
        padding: 12px 15px;
        border-radius: 10px;
        background-color: rgba(var(--bs-primary-rgb), 0.05);
        transition: all 0.3s;
    }

    .password-toggle:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.1);
    }

    .password-toggle label {
        margin-bottom: 0;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .form-check-input {
        width: 2.5em;
        height: 1.25em;
        cursor: pointer;
    }

    .password-section {
        border-radius: 18px;
        margin-top: 20px;
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        transform-origin: top;
    }

    .profile-completion {
        height: 8px;
        border-radius: 10px;
        margin: 15px 0;
        background-color: rgba(var(--bs-primary-rgb), 0.1);
        overflow: hidden;
    }

    .profile-completion .progress-bar {
        border-radius: 10px;
        background: linear-gradient(90deg, var(--bs-primary), var(--bs-info));
        transition: width 1s ease;
    }

    .last-login-info {
        font-size: 0.85rem;
        color: #6c757d;
        margin-top: 15px;
        padding: 10px;
        border-radius: 10px;
        background-color: rgba(0, 0, 0, 0.02);
    }

    .toggle-password {
        border-radius: 0 10px 10px 0 !important;
    }

    .password-strength {
        margin-top: 8px;
        font-size: 0.8rem;
    }

    .password-requirements {
        margin-top: 15px;
        padding: 15px;
        border-radius: 10px;
        background-color: rgba(0, 0, 0, 0.02);
    }

    .password-requirements ul {
        padding-left: 20px;
        margin-bottom: 0;
    }

    .password-requirements li {
        font-size: 0.85rem;
        color: #6c757d;
        margin-bottom: 5px;
    }

    .password-requirements li.valid {
        color: var(--bs-success);
    }

    .password-requirements li i {
        margin-right: 5px;
    }

    .profile-actions {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-top: 20px;
    }

    .profile-actions .btn {
        padding: 8px 15px;
        border-radius: 10px;
        font-size: 0.85rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 5px;
        transition: all 0.3s;
    }

    .profile-actions .btn:hover {
        transform: translateY(-2px);
    }

    /* Animation classes */
    .fade-in {
        animation: fadeIn 0.5s ease forwards;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
@endpush

@section('customer_content')
    <!-- Notification alerts -->
    <div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bx bx-check-circle me-1"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (Session::has('alert'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i> {{ Session::get('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bx bx-error-circle me-1"></i> Please check the form for errors
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            <ul class="mt-2 mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">Customer Profile</div>
            <div class="ps-3 ms-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('customer_dashboard') }}"><i class="bx bx-home-alt"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="row">
            <div class="col-lg-4">
                <div class="card profile-card">
                    <div class="card-body">
                        <div class="d-flex flex-column align-items-center">
                            <div class="profile-image-container">
                                <img src="{{ $profile_data->profile_pic ? url('storage/app/public/'. $profile_data->profile_pic) : asset('static/backend/images/avatars/avatar-1.png') }}"
                                    alt="Customer Profile" class="profile-image">
                                <label for="profile_pic_input" class="profile-image-edit" title="Change Profile Picture">
                                    <i class="bx bx-camera"></i>
                                </label>
                            </div>
                            <div class="profile-info">
                                <h4>{{ $profile_data->fullname }}</h4>
                                <p class="mb-1"><i class="bx bx-user me-1"></i>{{ $profile_data->username }}</p>
                                <span class="profile-badge">
                                    <i class="bx bx-check-shield me-1"></i>Verified Customer
                                </span>

                                <!-- Profile completion progress -->
                                @php
                                    $completionFields = [
                                        $profile_data->fullname,
                                        $profile_data->email,
                                        $profile_data->mobile,
                                        $profile_data->profile_pic,
                                        $profile_data->username
                                    ];
                                    $filledFields = 0;
                                    foreach($completionFields as $field) {
                                        if(!empty($field)) $filledFields++;
                                    }
                                    $completionPercentage = ($filledFields / count($completionFields)) * 100;

                                    // Calculate days since registration
                                    $createdDate = new DateTime($profile_data->created_at);
                                    $now = new DateTime();
                                    $daysSinceCreation = $createdDate->diff($now)->days;
                                @endphp

                                <div class="mt-3">
                                    <p class="mb-1 d-flex justify-content-between">
                                        <span>Profile Completion</span>
                                        <span>{{ round($completionPercentage) }}%</span>
                                    </p>
                                    <div class="profile-completion">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage }}%"
                                            aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile statistics -->
                            <div class="profile-stats">
                                <div class="profile-stat-item">
                                    <div class="profile-stat-value">{{ $profile_data->balance ?? '0.00' }}</div>
                                    <div class="profile-stat-label">Balance</div>
                                </div>
                                <div class="profile-stat-item">
                                    <div class="profile-stat-value">{{ $daysSinceCreation }}</div>
                                    <div class="profile-stat-label">Days Active</div>
                                </div>
                                <div class="profile-stat-item">
                                    <div class="profile-stat-value">
                                        <i class="bx bx-check-shield"></i>
                                    </div>
                                    <div class="profile-stat-label">Verified</div>
                                </div>
                            </div>

                            <div class="last-login-info">
                                <i class="bx bx-time-five me-1"></i>Last login: {{ $profile_data->last_login ? date('M d, Y h:i A', strtotime($profile_data->last_login)) : 'Never' }}
                                <br>
                                <i class="bx bx-map-pin me-1"></i>Last IP: {{ $profile_data->last_ip ?? 'Unknown' }}
                            </div>

                            <div class="profile-actions">
                                <a href="{{ route('customer_dashboard') }}" class="btn btn-outline-primary">
                                    <i class="bx bx-home-alt"></i> Dashboard
                                </a>
                                <a href="#" class="btn btn-outline-info">
                                    <i class="bx bx-wallet"></i> Wallet
                                </a>
                                <a href="#" class="btn btn-outline-danger">
                                    <i class="bx bx-log-out"></i> Logout
                                </a>
                            </div>

                            <!-- Account status -->
                            <div class="mt-4 text-center">
                                <span class="badge bg-success rounded-pill px-3 py-2">
                                    <i class="bx bx-check-circle me-1"></i> Account Active
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Verification Status Card -->
                <div class="card profile-card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bx bx-check-shield me-1"></i>Verification Status
                        </h5>

                        <ul class="list-group list-group-flush mt-3">
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 ps-0">
                                Email Verification
                                <span class="badge bg-success rounded-pill">Verified</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 ps-0">
                                Phone Verification
                                <span class="badge bg-success rounded-pill">Verified</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 ps-0">
                                KYC Verification
                                <span class="badge bg-warning rounded-pill">Pending</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center border-0 ps-0">
                                2FA Authentication
                                <span class="badge bg-danger rounded-pill">Disabled</span>
                            </li>
                        </ul>

                        <div class="text-center mt-3">
                            <a href="#" class="btn btn-sm btn-outline-primary">
                                <i class="bx bx-shield-quarter me-1"></i>Complete Verification
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Security Tips Card -->
                <div class="card profile-card mt-4">
                    <div class="card-body">
                        <h5 class="card-title">
                            <i class="bx bx-shield-quarter me-1"></i>Security Tips
                        </h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item border-0 ps-0">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                Use a strong, unique password
                            </li>
                            <li class="list-group-item border-0 ps-0">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                Enable two-factor authentication
                            </li>
                            <li class="list-group-item border-0 ps-0">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                Keep your account details private
                            </li>
                            <li class="list-group-item border-0 ps-0">
                                <i class="bx bx-check-circle text-success me-2"></i>
                                Log out from shared devices
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-lg-8">
                <div class="card profile-card">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bx-user-circle me-1"></i>Personal Information</h5>

                        <form action="{{ route('customerProfileUpdate') }}" method="POST" enctype="multipart/form-data" id="profile-form" class="mt-4">
                            @csrf
                            <!-- Hidden file input for profile picture -->
                            <input type="file" id="profile_pic_input" name="profile_pic" accept="image/*" style="display: none">

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="name" class="form-label">
                                        <i class="bx bx-user text-primary me-1"></i>Full Name
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-user"></i></span>
                                        <input type="text" class="form-control" id="name" value="{{ $profile_data->fullname }}" name="name" required>
                                    </div>
                                    <small class="text-muted">Your full name as it appears on official documents</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="username" class="form-label">
                                        <i class="bx bx-id-card text-primary me-1"></i>Username
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                        <input type="text" class="form-control" id="username" value="{{ $profile_data->username }}" name="username" readonly>
                                    </div>
                                    <small class="text-muted">Used for login and identification (cannot be changed)</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="mobile" class="form-label">
                                        <i class="bx bx-phone text-primary me-1"></i>Mobile Number
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                        <input type="text" class="form-control" id="mobile" value="{{ $profile_data->mobile }}" name="mobile" required>
                                    </div>
                                    <small class="text-muted">Used for account recovery and notifications</small>
                                </div>
                                <div class="col-md-6">
                                    <label for="email" class="form-label">
                                        <i class="bx bx-envelope text-primary me-1"></i>Email Address
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                        <input type="email" class="form-control" id="email" value="{{ $profile_data->email }}" name="email" required>
                                    </div>
                                    <small class="text-muted">Primary contact for account notifications</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label class="form-label">
                                        <i class="bx bx-calendar text-primary me-1"></i>Account Information
                                    </label>
                                    <div class="card bg-light border-0">
                                        <div class="card-body py-3">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Customer ID:</strong> {{ $profile_data->id }}</p>
                                                    <p class="mb-1"><strong>Created On:</strong> {{ date('M d, Y', strtotime($profile_data->created_at)) }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                                                    <p class="mb-1"><strong>Last Updated:</strong> {{ date('M d, Y', strtotime($profile_data->updated_at)) }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <button type="submit" class="btn btn-primary btn-profile">
                                    <i class="bx bx-save me-1"></i>Save Changes
                                </button>

                                <div class="password-toggle">
                                    <label for="div_check_box">Change Password</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="div_check_box">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card profile-card password-section mt-4 fade-in" id="password_div" style="display: none">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bx-lock-alt me-1"></i>Change Password</h5>

                        <form class="needs-validation mt-4" action="{{ route('customerChangePassword') }}" method="POST" id="password-form">
                            @csrf
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <label for="current_password" class="form-label">
                                        <i class="bx bx-key text-primary me-1"></i>Current Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-key"></i></span>
                                        <input type="password" class="form-control" name="current_password" id="current_password" placeholder="Enter your current password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="current_password">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Enter your current password to verify your identity</small>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label for="new_password" class="form-label">
                                        <i class="bx bx-lock text-primary me-1"></i>New Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                        <input type="password" class="form-control" name="new_password" id="new_password" placeholder="Enter new password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                    <div class="password-strength mt-2" id="password-strength"></div>
                                </div>
                                <div class="col-md-6">
                                    <label for="new_password_confirmation" class="form-label">
                                        <i class="bx bx-check-shield text-primary me-1"></i>Confirm Password
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                        <input type="password" class="form-control" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm new password" required>
                                        <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password_confirmation">
                                            <i class="bx bx-hide"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Password requirements -->
                            <div class="password-requirements mb-4">
                                <p class="mb-2 fw-bold"><i class="bx bx-info-circle me-1"></i>Password Requirements:</p>
                                <ul id="password-requirements-list">
                                    <li id="length-check"><i class="bx bx-x-circle"></i> At least 8 characters long</li>
                                    <li id="uppercase-check"><i class="bx bx-x-circle"></i> At least one uppercase letter</li>
                                    <li id="lowercase-check"><i class="bx bx-x-circle"></i> At least one lowercase letter</li>
                                    <li id="number-check"><i class="bx bx-x-circle"></i> At least one number</li>
                                    <li id="special-check"><i class="bx bx-x-circle"></i> At least one special character</li>
                                </ul>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                                <button type="button" class="btn btn-outline-secondary me-md-2" id="cancel-password-change">
                                    <i class="bx bx-x me-1"></i>Cancel
                                </button>
                                <button type="submit" class="btn btn-primary btn-profile" id="update-password-btn">
                                    <i class="bx bx-check me-1"></i>Update Password
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Transaction History Card -->
                <div class="card profile-card mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bx-transfer-alt me-1"></i>Recent Transactions</h5>

                        @php
                            // Get recent transactions
                            $transactions = \App\Models\Transaction::where('user_id', $profile_data->id)
                                ->where('user_type', 'customer')
                                ->orderBy('created_at', 'desc')
                                ->take(5)
                                ->get();
                        @endphp

                        @if(count($transactions) > 0)
                            <div class="table-responsive mt-3">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Transaction ID</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>{{ date('M d, Y', strtotime($transaction->created_at)) }}</td>
                                                <td><span class="text-monospace">{{ $transaction->trx }}</span></td>
                                                <td>
                                                    @if($transaction->trx_type == '+')
                                                        <span class="text-success">+{{ number_format($transaction->amount, 2) }}</span>
                                                    @else
                                                        <span class="text-danger">-{{ number_format($transaction->amount, 2) }}</span>
                                                    @endif
                                                </td>
                                                <td>{{ $transaction->details }}</td>
                                                <td>
                                                    <span class="badge bg-success">Completed</span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-list-ul me-1"></i>View All Transactions
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="bx bx-receipt fs-1 text-muted"></i>
                                <p class="mt-2">No transactions found</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Methods Card -->
                <div class="card profile-card mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bx bx-credit-card me-1"></i>Payment Methods</h5>

                        <div class="payment-methods mt-3">
                            <div class="payment-method-item d-flex align-items-center p-3 border rounded mb-3">
                                <div class="payment-icon me-3">
                                    <i class="bx bxl-visa fs-1 text-primary"></i>
                                </div>
                                <div class="payment-details flex-grow-1">
                                    <h6 class="mb-1">Visa ending in 4242</h6>
                                    <p class="mb-0 text-muted small">Expires 12/2025</p>
                                </div>
                                <div class="payment-actions">
                                    <span class="badge bg-success me-2">Default</span>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="payment-method-item d-flex align-items-center p-3 border rounded">
                                <div class="payment-icon me-3">
                                    <i class="bx bxl-mastercard fs-1 text-danger"></i>
                                </div>
                                <div class="payment-details flex-grow-1">
                                    <h6 class="mb-1">Mastercard ending in 8888</h6>
                                    <p class="mb-0 text-muted small">Expires 08/2024</p>
                                </div>
                                <div class="payment-actions">
                                    <button class="btn btn-sm btn-outline-primary me-2">
                                        <i class="bx bx-check"></i> Set Default
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="text-center mt-4">
                                <button class="btn btn-sm btn-primary">
                                    <i class="bx bx-plus-circle me-1"></i> Add Payment Method
                                </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Add animation classes to elements
        document.querySelectorAll('.profile-card').forEach(card => {
            card.classList.add('fade-in');
        });

        // Profile picture upload handling with enhanced preview
        const profilePicInput = document.getElementById('profile_pic_input');
        const profileForm = document.getElementById('profile-form');
        const profileImage = document.querySelector('.profile-image');

        // Trigger file input when clicking on the edit icon
        document.querySelector('.profile-image-edit').addEventListener('click', function(e) {
            e.preventDefault();
            profilePicInput.click();
        });

        // Add hover effect to profile image
        if (profileImage) {
            profileImage.addEventListener('mouseenter', function() {
                document.querySelector('.profile-image-edit').style.opacity = '1';
            });

            profileImage.addEventListener('mouseleave', function() {
                document.querySelector('.profile-image-edit').style.opacity = '0.8';
            });
        }

        // Enhanced image preview and upload
        profilePicInput.addEventListener('change', function() {
            if (this.files && this.files[0]) {
                const file = this.files[0];

                // Validate file type and size
                const validTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
                const maxSize = 2 * 1024 * 1024; // 2MB

                if (!validTypes.includes(file.type)) {
                    showNotification('Please select a valid image file (JPEG, PNG, GIF)', 'error');
                    return;
                }

                if (file.size > maxSize) {
                    showNotification('Image size should be less than 2MB', 'error');
                    return;
                }

                // Preview the image with animation
                const reader = new FileReader();
                reader.onload = function(e) {
                    // Create temporary image to apply transition
                    const tempImg = new Image();
                    tempImg.src = e.target.result;

                    tempImg.onload = function() {
                        // Apply fade out effect
                        profileImage.style.opacity = '0';

                        // After fade out, update the image and fade in
                        setTimeout(() => {
                            profileImage.src = e.target.result;
                            profileImage.style.opacity = '1';

                            // Show notification
                            showNotification('Uploading profile picture...', 'info');

                            // Submit the form after a short delay to show the preview
                            setTimeout(() => {
                                profileForm.submit();
                            }, 800);
                        }, 300);
                    };
                };
                reader.readAsDataURL(file);
            }
        });

        // Enhanced password change toggle with animation
        const checkbox = document.getElementById('div_check_box');
        const passwordDiv = document.getElementById('password_div');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Show password section with animation
                passwordDiv.style.display = 'block';
                passwordDiv.style.opacity = '0';
                passwordDiv.style.transform = 'translateY(20px)';

                // Trigger reflow to apply initial styles before animation
                passwordDiv.offsetHeight;

                // Apply animation
                passwordDiv.style.opacity = '1';
                passwordDiv.style.transform = 'translateY(0)';

                // Smooth scroll to password section
                setTimeout(() => {
                    passwordDiv.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            } else {
                // Hide with animation
                passwordDiv.style.opacity = '0';
                passwordDiv.style.transform = 'translateY(20px)';

                setTimeout(() => {
                    passwordDiv.style.display = 'none';
                }, 300);
            }
        });

        // Cancel password change button with enhanced UX
        document.getElementById('cancel-password-change').addEventListener('click', function() {
            // Apply animation before hiding
            passwordDiv.style.opacity = '0';
            passwordDiv.style.transform = 'translateY(20px)';

            setTimeout(() => {
                checkbox.checked = false;
                passwordDiv.style.display = 'none';

                // Clear password fields
                document.getElementById('current_password').value = '';
                document.getElementById('new_password').value = '';
                document.getElementById('new_password_confirmation').value = '';
                document.getElementById('password-strength').innerHTML = '';

                // Reset password requirement checks
                updatePasswordRequirements('');
            }, 300);
        });

        // Enhanced toggle password visibility with animation
        const toggleButtons = document.querySelectorAll('.toggle-password');
        toggleButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const passwordInput = document.getElementById(targetId);
                const icon = this.querySelector('i');

                // Add animation to the icon
                icon.style.transform = 'rotate(180deg)';

                setTimeout(() => {
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        icon.className = 'bx bx-show';
                    } else {
                        passwordInput.type = 'password';
                        icon.className = 'bx bx-hide';
                    }

                    // Reset the transform
                    icon.style.transform = 'rotate(0deg)';
                }, 150);
            });
        });

        // Enhanced password strength meter with real-time requirements check
        const newPasswordInput = document.getElementById('new_password');
        const strengthMeter = document.getElementById('password-strength');
        const updatePasswordBtn = document.getElementById('update-password-btn');

        // Function to update password requirements list
        function updatePasswordRequirements(password) {
            const lengthCheck = document.getElementById('length-check');
            const uppercaseCheck = document.getElementById('uppercase-check');
            const lowercaseCheck = document.getElementById('lowercase-check');
            const numberCheck = document.getElementById('number-check');
            const specialCheck = document.getElementById('special-check');

            // Check each requirement
            const hasLength = password.length >= 8;
            const hasUppercase = /[A-Z]/.test(password);
            const hasLowercase = /[a-z]/.test(password);
            const hasNumber = /\d/.test(password);
            const hasSpecial = /[^a-zA-Z0-9]/.test(password);

            // Update the list items
            updateRequirementItem(lengthCheck, hasLength);
            updateRequirementItem(uppercaseCheck, hasUppercase);
            updateRequirementItem(lowercaseCheck, hasLowercase);
            updateRequirementItem(numberCheck, hasNumber);
            updateRequirementItem(specialCheck, hasSpecial);

            // Enable/disable submit button based on all requirements being met
            const allRequirementsMet = hasLength && hasUppercase && hasLowercase && hasNumber && hasSpecial;
            updatePasswordBtn.disabled = !allRequirementsMet;

            return allRequirementsMet;
        }

        // Helper function to update a requirement item
        function updateRequirementItem(element, isValid) {
            const icon = element.querySelector('i');

            if (isValid) {
                element.classList.add('valid');
                icon.className = 'bx bx-check-circle text-success';
            } else {
                element.classList.remove('valid');
                icon.className = 'bx bx-x-circle text-danger';
            }
        }

        // Enhanced password strength meter
        if (newPasswordInput) {
            newPasswordInput.addEventListener('input', function() {
                const password = this.value;
                let strength = 0;
                let feedback = '';

                // Check requirements and update the list
                const requirementsMet = updatePasswordRequirements(password);

                // Calculate strength
                if (password.length >= 8) strength += 1;
                if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 1;
                if (password.match(/\d/)) strength += 1;
                if (password.match(/[^a-zA-Z\d]/)) strength += 1;

                // Generate feedback with animated progress bar
                switch (strength) {
                    case 0:
                        feedback = '<div class="progress" style="height: 6px;"><div class="progress-bar bg-danger" style="width: 25%; transition: width 0.5s ease;"></div></div><small class="text-danger">Very Weak</small>';
                        break;
                    case 1:
                        feedback = '<div class="progress" style="height: 6px;"><div class="progress-bar bg-danger" style="width: 25%; transition: width 0.5s ease;"></div></div><small class="text-danger">Weak</small>';
                        break;
                    case 2:
                        feedback = '<div class="progress" style="height: 6px;"><div class="progress-bar bg-warning" style="width: 50%; transition: width 0.5s ease;"></div></div><small class="text-warning">Fair</small>';
                        break;
                    case 3:
                        feedback = '<div class="progress" style="height: 6px;"><div class="progress-bar bg-info" style="width: 75%; transition: width 0.5s ease;"></div></div><small class="text-info">Good</small>';
                        break;
                    case 4:
                        feedback = '<div class="progress" style="height: 6px;"><div class="progress-bar bg-success" style="width: 100%; transition: width 0.5s ease;"></div></div><small class="text-success">Strong</small>';
                        break;
                }

                strengthMeter.innerHTML = feedback;
            });
        }

        // Password confirmation validation with real-time feedback
        const confirmPasswordInput = document.getElementById('new_password_confirmation');
        if (confirmPasswordInput && newPasswordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                const newPassword = newPasswordInput.value;
                const confirmPassword = this.value;

                if (confirmPassword === '') {
                    this.style.borderColor = '';
                    return;
                }

                if (newPassword === confirmPassword) {
                    this.style.borderColor = '#28a745';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(40, 167, 69, 0.25)';
                } else {
                    this.style.borderColor = '#dc3545';
                    this.style.boxShadow = '0 0 0 0.2rem rgba(220, 53, 69, 0.25)';
                }
            });
        }

        // Enhanced form validation and submission with loading animation
        const passwordForm = document.getElementById('password-form');
        if (passwordForm) {
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('new_password_confirmation').value;

                // Validate all fields are filled
                if (!currentPassword || !newPassword || !confirmPassword) {
                    showNotification('Please fill in all password fields', 'error');
                    return;
                }

                // Validate password requirements
                if (!updatePasswordRequirements(newPassword)) {
                    showNotification('Password does not meet all requirements', 'error');
                    return;
                }

                // Validate passwords match
                if (newPassword !== confirmPassword) {
                    showNotification('Passwords do not match!', 'error');
                    confirmPasswordInput.focus();
                    return;
                }

                // Show loading state with animation
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin me-1"></i>Updating...';
                submitBtn.disabled = true;

                // Add subtle animation to the form
                this.style.opacity = '0.8';

                // Submit the form after a short delay for better UX
                setTimeout(() => {
                    this.submit();
                }, 500);
            });
        }

        // Enhanced notification function with animation
        function showNotification(message, type = 'success') {
            const container = document.getElementById('notification-container');
            const notification = document.createElement('div');

            // Set notification class based on type
            notification.className = `alert alert-${type} alert-dismissible fade`;

            // Set icon based on notification type
            let icon = '';
            switch (type) {
                case 'success':
                    icon = '<i class="bx bx-check-circle me-1"></i>';
                    break;
                case 'error':
                    icon = '<i class="bx bx-error-circle me-1"></i>';
                    break;
                case 'info':
                    icon = '<i class="bx bx-info-circle me-1"></i>';
                    break;
                case 'warning':
                    icon = '<i class="bx bx-error me-1"></i>';
                    break;
            }

            // Set notification content
            notification.innerHTML = `
                <div class="d-flex align-items-center">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-content">${message}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;

            // Add to container
            container.appendChild(notification);

            // Trigger animation after a small delay
            setTimeout(() => {
                notification.classList.add('show');
            }, 10);

            // Auto-remove after 5 seconds with animation
            setTimeout(() => {
                notification.classList.remove('show');
                setTimeout(() => {
                    if (notification.parentNode) {
                        container.removeChild(notification);
                    }
                }, 300);
            }, 5000);
        }

        // Auto-dismiss alerts after 5 seconds with animation
        const alerts = document.querySelectorAll('.alert:not(#notification-container .alert)');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }, 5000);
        });

        // Add subtle animations to form inputs
        const formInputs = document.querySelectorAll('.form-control');
        formInputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
                this.parentElement.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
            });

            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
                this.parentElement.style.boxShadow = 'none';
            });
        });

        // Add animation to the profile completion progress bar
        const progressBar = document.querySelector('.profile-completion .progress-bar');
        if (progressBar) {
            setTimeout(() => {
                progressBar.style.width = progressBar.getAttribute('aria-valuenow') + '%';
            }, 500);
        }

        // Payment method interactions
        const addPaymentBtn = document.querySelector('.payment-methods .btn-primary');
        if (addPaymentBtn) {
            addPaymentBtn.addEventListener('click', function() {
                showNotification('Payment method feature coming soon!', 'info');
            });
        }

        // Set default payment method
        const setDefaultBtns = document.querySelectorAll('.payment-actions .btn-outline-primary');
        setDefaultBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove default badge from all payment methods
                document.querySelectorAll('.payment-actions .badge-success').forEach(badge => {
                    badge.remove();
                });

                // Add default badge to this payment method
                const paymentActions = this.closest('.payment-actions');
                const defaultBadge = document.createElement('span');
                defaultBadge.className = 'badge bg-success me-2';
                defaultBadge.textContent = 'Default';
                paymentActions.prepend(defaultBadge);

                // Remove the set default button
                this.remove();

                showNotification('Default payment method updated', 'success');
            });
        });

        // Delete payment method
        const deletePaymentBtns = document.querySelectorAll('.payment-actions .btn-outline-danger');
        deletePaymentBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const paymentMethod = this.closest('.payment-method-item');

                // Animate removal
                paymentMethod.style.opacity = '0';
                paymentMethod.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    paymentMethod.remove();
                    showNotification('Payment method removed', 'success');
                }, 300);
            });
        });
    });
</script>
@endpush