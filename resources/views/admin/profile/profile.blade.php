@extends('admin.layouts.admin_app')
@section('title', 'Profile')
@push('css')
<style>
    /* Enhanced Profile page styles */
    .profile-card {
        border-radius: 18px;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
        border: none;
        overflow: hidden;
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

    /* Dark mode specific styles */
    html.dark-theme .profile-card {
        background-color: #252a30;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    html.dark-theme .profile-image {
        border-color: #252a30;
    }

    html.dark-theme .form-control {
        background-color: #1e2124;
        border-color: #32383e;
        color: #e4e5e6;
    }

    html.dark-theme .input-group-text {
        background-color: #2c3136;
        border-color: #32383e;
        color: #e4e5e6;
    }

    html.dark-theme .password-toggle {
        background-color: rgba(255, 255, 255, 0.05);
    }

    html.dark-theme .profile-stats,
    html.dark-theme .last-login-info,
    html.dark-theme .password-requirements {
        background-color: rgba(255, 255, 255, 0.03);
        border-color: rgba(255, 255, 255, 0.05);
    }

    html.dark-theme .profile-stats {
        border-top: 1px solid rgba(255, 255, 255, 0.05);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    html.dark-theme .profile-badge {
        background-color: rgba(var(--bs-primary-rgb), 0.2);
    }

    html.dark-theme .profile-completion {
        background-color: rgba(255, 255, 255, 0.1);
    }

    html.dark-theme .form-label,
    html.dark-theme .profile-info h4 {
        color: #e4e5e6;
    }

    html.dark-theme .profile-info p,
    html.dark-theme .profile-stat-label,
    html.dark-theme .last-login-info,
    html.dark-theme .password-requirements li {
        color: #adb5bd;
    }

    /* Notification styles */
    #notification-container .alert {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
        padding: 15px 20px;
        margin-bottom: 10px;
        animation: slideIn 0.3s ease forwards;
    }

    @keyframes slideIn {
        from { transform: translateX(100%); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Activity timeline styles */
    .timeline-activity {
        position: relative;
        padding-left: 10px;
    }

    .activity-item {
        position: relative;
        padding-bottom: 20px;
        z-index: 1;
    }

    .activity-timeline-line {
        position: absolute;
        left: 20px;
        top: 30px;
        bottom: 0;
        width: 2px;
        background-color: #e9ecef;
        z-index: 0;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .activity-content {
        flex-grow: 1;
    }

    /* System statistics styles */
    .stat-card {
        transition: all 0.3s ease;
        border: 1px solid #f1f1f1;
    }

    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
    }

    /* Permission list styles */
    .permission-list {
        padding: 10px 0;
    }

    .permission-item {
        padding: 8px 10px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .permission-item:hover {
        background-color: #f8f9fa;
    }

    .permission-icon {
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* System information styles */
    .table-borderless td {
        padding: 10px 0;
        border-bottom: 1px solid #f1f1f1;
    }

    .table-borderless tr:last-child td {
        border-bottom: none;
    }

    .text-monospace {
        font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
        background-color: #f8f9fa;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    /* Dark theme adjustments for new elements */
    html.dark-theme .activity-timeline-line {
        background-color: #3e3e3e;
    }

    html.dark-theme .stat-card {
        background-color: #2e2e2e !important;
        border-color: #3e3e3e;
    }

    html.dark-theme .permission-item:hover {
        background-color: #2e2e2e;
    }

    html.dark-theme .table-borderless td {
        border-color: #3e3e3e;
    }

    html.dark-theme .text-monospace {
        background-color: #2e2e2e;
    }

    html.dark-theme .bg-light {
        background-color: #2e2e2e !important;
    }

    /* Badge styles */
    .badge {
        font-weight: 500;
        padding: 5px 10px;
        border-radius: 6px;
    }

    .bg-primary-light {
        background-color: rgba(13, 110, 253, 0.15);
    }

    .bg-success-light {
        background-color: rgba(25, 135, 84, 0.15);
    }

    .bg-info-light {
        background-color: rgba(13, 202, 240, 0.15);
    }

    .bg-warning-light {
        background-color: rgba(255, 193, 7, 0.15);
    }

    .bg-danger-light {
        background-color: rgba(220, 53, 69, 0.15);
    }
</style>
@endpush
@section('content')

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
            <div class="breadcrumb-title pe-3">Admin Profile</div>
            <div class="ps-3 ms-auto">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i> Dashboard</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Profile</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->
    <div class="container">
        <div class="main-body">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card profile-card">
                        <div class="card-body">
                            <div class="d-flex flex-column align-items-center">
                                <div class="profile-image-container">
                                    <img src="{{ $data->profile_pic ? asset('storage/'.$data->profile_pic) : asset('static/backend/images/avatars/avatar-1.png') }}"
                                        alt="Admin Profile" class="profile-image">
                                    <label for="profile_pic_input" class="profile-image-edit" title="Change Profile Picture">
                                        <i class="bx bx-camera"></i>
                                    </label>
                                </div>
                                <div class="profile-info">
                                    <h4>{{ $data->admin_name }}</h4>
                                    <p class="mb-1"><i class="bx bx-user-check me-1"></i>{{ ucwords($data->type) }}</p>
                                    <span class="profile-badge">
                                        <i class="bx bx-shield-quarter me-1"></i>Administrator
                                    </span>

                                    <!-- Profile completion progress -->
                                    @php
                                        $completionFields = [
                                            $data->admin_name,
                                            $data->email,
                                            $data->mobile,
                                            $data->profile_pic,
                                            $data->username
                                        ];
                                        $filledFields = 0;
                                        foreach($completionFields as $field) {
                                            if(!empty($field)) $filledFields++;
                                        }
                                        $completionPercentage = ($filledFields / count($completionFields)) * 100;

                                        // Get login count
                                        $loginCount = $data->last_login_count ?? 0;

                                        // Calculate days since registration
                                        $createdDate = new DateTime($data->created_at);
                                        $now = new DateTime();
                                        $daysSinceCreation = $createdDate->diff($now)->days;
                                    @endphp

                                    <div class="mt-3">
                                        <p class="mb-1 d-flex justify-content-between">
                                            <span>Profile Completion</span>
                                            <span>{{ round($completionPercentage) }}%</span>
                                        </p>
                                        <div class="progress profile-completion">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $completionPercentage }}%"
                                                aria-valuenow="{{ $completionPercentage }}" aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Profile statistics -->
                                <div class="profile-stats">
                                    <div class="profile-stat-item">
                                        <div class="profile-stat-value">{{ $loginCount }}</div>
                                        <div class="profile-stat-label">Logins</div>
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
                                    <i class="bx bx-time-five me-1"></i>Last login: {{ $data->last_login ? date('M d, Y h:i A', strtotime($data->last_login)) : 'Never' }}
                                    <br>
                                    <i class="bx bx-map-pin me-1"></i>Last IP: {{ $data->last_ip ?? 'Unknown' }}
                                </div>

                                <div class="profile-actions mt-4">
                                    <a href="{{ route('admin_dashboard') }}" class="btn btn-outline-primary">
                                        <i class="bx bx-home-alt"></i> Dashboard
                                    </a>
                                    <a href="{{ route('userList') }}" class="btn btn-outline-info">
                                        <i class="bx bx-group"></i> Users
                                    </a>
                                    <a href="{{ route('adminlogout') }}" class="btn btn-outline-danger">
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
                                    Update your contact information
                                </li>
                                <li class="list-group-item border-0 ps-0">
                                    <i class="bx bx-check-circle text-success me-2"></i>
                                    Log out from shared devices
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- System Statistics Card -->
                    <div class="card profile-card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bx bx-bar-chart-alt-2 me-1"></i>System Statistics
                            </h5>

                            @php
                                // Get system statistics
                                $totalUsers = \App\Models\User::where('db_status', 'live')->count();
                                $totalMerchants = \App\Models\Merchant::where('db_status', 'live')->count();
                                $totalCustomers = \App\Models\Customer::where('db_status', 'live')->count();
                                $totalTransactions = \App\Models\BalanceManager::whereIn('status', ['20', '22', '77'])->count();
                            @endphp

                            <div class="row g-3 mt-2">
                                <div class="col-6">
                                    <div class="stat-card bg-light p-3 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-primary-light text-primary rounded-circle p-2 me-3">
                                                <i class="bx bx-user"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ number_format($totalUsers) }}</h6>
                                                <small class="text-muted">Total Users</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-light p-3 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-success-light text-success rounded-circle p-2 me-3">
                                                <i class="bx bx-store"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ number_format($totalMerchants) }}</h6>
                                                <small class="text-muted">Merchants</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-light p-3 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-info-light text-info rounded-circle p-2 me-3">
                                                <i class="bx bx-group"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ number_format($totalCustomers) }}</h6>
                                                <small class="text-muted">Customers</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-card bg-light p-3 rounded">
                                        <div class="d-flex align-items-center">
                                            <div class="stat-icon bg-warning-light text-warning rounded-circle p-2 me-3">
                                                <i class="bx bx-transfer-alt"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0">{{ number_format($totalTransactions) }}</h6>
                                                <small class="text-muted">Transactions</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <a href="{{ route('admin_dashboard') }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bx bx-bar-chart me-1"></i>View Full Dashboard
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions Card -->
                    <div class="card profile-card mt-4">
                        <div class="card-body">
                            <h5 class="card-title">
                                <i class="bx bx-key me-1"></i>Permissions & Access
                            </h5>

                            <div class="permission-list mt-3">
                                <div class="permission-item d-flex align-items-center mb-2">
                                    <div class="permission-icon me-2">
                                        <i class="bx bx-check-circle text-success"></i>
                                    </div>
                                    <div class="permission-name">User Management</div>
                                </div>
                                <div class="permission-item d-flex align-items-center mb-2">
                                    <div class="permission-icon me-2">
                                        <i class="bx bx-check-circle text-success"></i>
                                    </div>
                                    <div class="permission-name">Transaction Management</div>
                                </div>
                                <div class="permission-item d-flex align-items-center mb-2">
                                    <div class="permission-icon me-2">
                                        <i class="bx bx-check-circle text-success"></i>
                                    </div>
                                    <div class="permission-name">System Settings</div>
                                </div>
                                <div class="permission-item d-flex align-items-center mb-2">
                                    <div class="permission-icon me-2">
                                        <i class="bx bx-check-circle text-success"></i>
                                    </div>
                                    <div class="permission-name">Reports & Analytics</div>
                                </div>
                                <div class="permission-item d-flex align-items-center">
                                    <div class="permission-icon me-2">
                                        <i class="bx bx-check-circle text-success"></i>
                                    </div>
                                    <div class="permission-name">API Access</div>
                                </div>
                            </div>

                            <div class="text-center mt-3">
                                <button class="btn btn-sm btn-outline-secondary" disabled>
                                    <i class="bx bx-edit me-1"></i>Request Additional Access
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card profile-card">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bx bx-user-circle me-1"></i>Personal Information</h5>

                            <form action="{{ route('admin.update_profile') }}" method="POST" enctype="multipart/form-data" id="profile-form" class="mt-4">
                                @csrf
                                <!-- Hidden file input for profile picture -->
                                <input type="file" id="profile_pic_input" name="profile_pic" accept="image/*" style="display: none">
                                <input type="hidden" value="{{ $data->id }}" name="id">

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="name" class="form-label">
                                            <i class="bx bx-user text-primary me-1"></i>Full Name
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-user"></i></span>
                                            <input type="text" class="form-control" id="name" value="{{ $data->admin_name }}" name="name" required>
                                        </div>
                                        <small class="text-muted">Your full name as it appears on official documents</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="username" class="form-label">
                                            <i class="bx bx-id-card text-primary me-1"></i>Username
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                            <input type="text" class="form-control" id="username" value="{{ $data->username }}" name="username" required>
                                        </div>
                                        <small class="text-muted">Used for login and identification</small>
                                    </div>

                                <div class="col-md-6">
                                        <label for="username" class="form-label">
                                            <i class="bx bx-id-card text-primary me-1"></i>Pincode
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-id-card"></i></span>
                                            <input type="text" class="form-control" id="pincode" value="{{ $data->pincode }}" name="pincode" required>
                                        </div>
                                        <small class="text-muted">Used for login and identification</small>
                                    </div>

                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label for="mobile" class="form-label">
                                            <i class="bx bx-phone text-primary me-1"></i>Mobile Number
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-phone"></i></span>
                                            <input type="text" class="form-control" id="mobile" value="{{ $data->mobile }}" name="mobile" required>
                                        </div>
                                        <small class="text-muted">Used for account recovery and notifications</small>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">
                                            <i class="bx bx-envelope text-primary me-1"></i>Email Address
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-envelope"></i></span>
                                            <input type="email" class="form-control" id="email" value="{{ $data->email }}" name="email" required>
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
                                                        <p class="mb-1"><strong>Account Type:</strong> {{ ucwords($data->type) }}</p>
                                                        <p class="mb-1"><strong>Created On:</strong> {{ date('M d, Y', strtotime($data->created_at)) }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p class="mb-1"><strong>Status:</strong> <span class="badge bg-success">Active</span></p>
                                                        <p class="mb-1"><strong>Last Updated:</strong> {{ date('M d, Y', strtotime($data->updated_at)) }}</p>
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

                            <form class="needs-validation mt-4" action="{{ route('admin.update_password') }}" method="POST" id="password-form">
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
                                            <input type="password" class="form-control" name="password" id="new_password" placeholder="Enter new password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="new_password">
                                                <i class="bx bx-hide"></i>
                                            </button>
                                        </div>
                                        <div class="password-strength mt-2" id="password-strength"></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="confirm_password" class="form-label">
                                            <i class="bx bx-check-shield text-primary me-1"></i>Confirm Password
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text"><i class="bx bx-lock"></i></span>
                                            <input type="password" class="form-control" name="password_confirmation" id="confirm_password" placeholder="Confirm new password" required>
                                            <button class="btn btn-outline-secondary toggle-password" type="button" data-target="confirm_password">
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

                    <!-- Activity Log Card -->
                    <div class="card profile-card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bx bx-history me-1"></i>Recent Activity</h5>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <p class="mb-0 text-muted">Your recent account activities</p>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="activityFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-filter-alt me-1"></i>Filter
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="activityFilterDropdown">
                                        <li><a class="dropdown-item active" href="#">All Activities</a></li>
                                        <li><a class="dropdown-item" href="#">Logins</a></li>
                                        <li><a class="dropdown-item" href="#">Profile Updates</a></li>
                                        <li><a class="dropdown-item" href="#">System Actions</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="timeline-activity mt-4">
                                @php
                                    // Get admin access logs
                                    $accessLogs = \App\Models\AdminAccessLog::where('admin_id', $data->id)
                                        ->orderBy('created_at', 'desc')
                                        ->take(5)
                                        ->get();
                                @endphp

                                @if($accessLogs && count($accessLogs) > 0)
                                    @foreach($accessLogs as $log)
                                    <div class="activity-item d-flex">
                                        <div class="activity-icon bg-primary-light text-primary">
                                            <i class="bx bx-log-in"></i>
                                        </div>
                                        <div class="activity-content ms-3">
                                            <p class="mb-0">
                                                <strong>Login</strong> from {{ $log->ip_address ?? 'Unknown IP' }}
                                                @if($log->browser)
                                                    <span class="badge bg-light text-dark">{{ $log->browser }}</span>
                                                @endif
                                                @if($log->platform)
                                                    <span class="badge bg-light text-dark">{{ $log->platform }}</span>
                                                @endif
                                            </p>
                                            <small class="text-muted">{{ date('M d, Y h:i A', strtotime($log->created_at)) }}</small>

                                            @if($log->longitude && $log->latitude)
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    <i class="bx bx-map me-1"></i>Location:
                                                    <a href="https://www.google.com/maps?q={{ $log->latitude }},{{ $log->longitude }}" target="_blank" class="text-primary">
                                                        View on map
                                                    </a>
                                                </small>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="activity-timeline-line"></div>
                                    @endforeach
                                @else
                                    <div class="activity-item d-flex">
                                        <div class="activity-icon bg-primary-light text-primary">
                                            <i class="bx bx-log-in"></i>
                                        </div>
                                        <div class="activity-content ms-3">
                                            <p class="mb-0"><strong>Login</strong> from {{ $data->last_ip ?? 'Unknown IP' }}</p>
                                            <small class="text-muted">{{ $data->last_login ? date('M d, Y h:i A', strtotime($data->last_login)) : 'Never' }}</small>
                                        </div>
                                    </div>
                                    <div class="activity-timeline-line"></div>
                                @endif

                                <div class="activity-item d-flex">
                                    <div class="activity-icon bg-success-light text-success">
                                        <i class="bx bx-user"></i>
                                    </div>
                                    <div class="activity-content ms-3">
                                        <p class="mb-0"><strong>Profile Updated</strong></p>
                                        <small class="text-muted">{{ date('M d, Y h:i A', strtotime($data->updated_at)) }}</small>
                                    </div>
                                </div>
                                <div class="activity-timeline-line"></div>

                                <div class="activity-item d-flex">
                                    <div class="activity-icon bg-info-light text-info">
                                        <i class="bx bx-shield"></i>
                                    </div>
                                    <div class="activity-content ms-3">
                                        <p class="mb-0"><strong>Account Created</strong></p>
                                        <small class="text-muted">{{ date('M d, Y h:i A', strtotime($data->created_at)) }}</small>
                                    </div>
                                </div>

                                <div class="text-center mt-4">
                                    <button class="btn btn-sm btn-outline-primary" id="load-more-activities">
                                        <i class="bx bx-loader me-1"></i>Load More
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Information Card -->
                    <div class="card profile-card mt-4">
                        <div class="card-body">
                            <h5 class="card-title"><i class="bx bx-server me-1"></i>System Information</h5>

                            <div class="table-responsive mt-3">
                                <table class="table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td class="fw-bold" width="40%">Admin ID</td>
                                            <td>{{ $data->id }}</td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Access Code</td>
                                            <td>
                                                <span class="text-monospace">{{ substr($data->access_code ?? 'Not set', 0, 4) }}****</span>
                                                <button class="btn btn-sm btn-link p-0 ms-2" id="show-access-code">
                                                    <i class="bx bx-show"></i>
                                                </button>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Account Status</td>
                                            <td>
                                                @if($data->status == 1)
                                                    <span class="badge bg-success">Active</span>
                                                @else
                                                    <span class="badge bg-danger">Inactive</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Last Password Change</td>
                                            <td>
                                                @if($data->pass_expire)
                                                    {{ date('M d, Y', strtotime($data->pass_expire)) }}
                                                    @php
                                                        $passExpireDate = new DateTime($data->pass_expire);
                                                        $now = new DateTime();
                                                        $daysSincePassChange = $passExpireDate->diff($now)->days;
                                                    @endphp
                                                    <small class="text-muted">({{ $daysSincePassChange }} days ago)</small>
                                                @else
                                                    <span class="text-muted">Not available</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Browser</td>
                                            <td>
                                                @php
                                                    $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
                                                    $browser = '';

                                                    if (strpos($userAgent, 'Chrome') !== false) {
                                                        $browser = 'Chrome';
                                                    } elseif (strpos($userAgent, 'Firefox') !== false) {
                                                        $browser = 'Firefox';
                                                    } elseif (strpos($userAgent, 'Safari') !== false) {
                                                        $browser = 'Safari';
                                                    } elseif (strpos($userAgent, 'Edge') !== false) {
                                                        $browser = 'Edge';
                                                    } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
                                                        $browser = 'Internet Explorer';
                                                    } else {
                                                        $browser = 'Unknown';
                                                    }
                                                @endphp
                                                <span>{{ $browser }}</span>
                                                <small class="text-muted">(Current session)</small>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="fw-bold">Current IP Address</td>
                                            <td>{{ $_SERVER['REMOTE_ADDR'] ?? 'Unknown' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
                    document.getElementById('confirm_password').value = '';
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

            // Password confirmation validation with real-time feedback
            const confirmPasswordInput = document.getElementById('confirm_password');

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

            // Enhanced form validation and submission with loading animation
            const passwordForm = document.getElementById('password-form');
            passwordForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const currentPassword = document.getElementById('current_password').value;
                const newPassword = document.getElementById('new_password').value;
                const confirmPassword = document.getElementById('confirm_password').value;

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

            // Enhanced notification function with sound and animation
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

            // Initialize tooltips if Bootstrap 5 is available
            if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
                const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            // Handle access code reveal
            const showAccessCodeBtn = document.getElementById('show-access-code');
            if (showAccessCodeBtn) {
                showAccessCodeBtn.addEventListener('click', function() {
                    const accessCodeSpan = this.previousElementSibling;
                    const icon = this.querySelector('i');

                    if (accessCodeSpan.textContent.includes('*')) {
                        // Show the full access code (this is just a simulation)
                        accessCodeSpan.textContent = "{{ $data->access_code ?? 'Not set' }}";
                        icon.className = 'bx bx-hide';

                        // Auto-hide after 5 seconds
                        setTimeout(() => {
                            accessCodeSpan.textContent = "{{ substr($data->access_code ?? 'Not set', 0, 4) }}****";
                            icon.className = 'bx bx-show';
                        }, 5000);
                    } else {
                        accessCodeSpan.textContent = "{{ substr($data->access_code ?? 'Not set', 0, 4) }}****";
                        icon.className = 'bx bx-show';
                    }
                });
            }

            // Handle activity filter dropdown
            const activityFilterItems = document.querySelectorAll('#activityFilterDropdown + .dropdown-menu .dropdown-item');
            activityFilterItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();

                    // Remove active class from all items
                    activityFilterItems.forEach(i => i.classList.remove('active'));

                    // Add active class to clicked item
                    this.classList.add('active');

                    // Update dropdown button text
                    const dropdownButton = document.getElementById('activityFilterDropdown');
                    dropdownButton.innerHTML = `<i class="bx bx-filter-alt me-1"></i>${this.textContent}`;

                    // Filter activities (just a simulation)
                    const filterType = this.textContent.trim().toLowerCase();
                    const activityItems = document.querySelectorAll('.activity-item');

                    if (filterType === 'all activities') {
                        activityItems.forEach(item => {
                            item.style.display = 'flex';
                        });
                    } else {
                        activityItems.forEach(item => {
                            const activityText = item.textContent.toLowerCase();
                            if (activityText.includes(filterType.replace(' activities', '').replace('s', ''))) {
                                item.style.display = 'flex';
                            } else {
                                item.style.display = 'none';
                            }
                        });
                    }
                });
            });

            // Handle load more activities button
            const loadMoreBtn = document.getElementById('load-more-activities');
            if (loadMoreBtn) {
                loadMoreBtn.addEventListener('click', function() {
                    this.innerHTML = '<i class="bx bx-loader bx-spin me-1"></i>Loading...';
                    this.disabled = true;

                    // Simulate loading more activities
                    setTimeout(() => {
                        // Create new activity items (this is just a simulation)
                        const timelineContainer = document.querySelector('.timeline-activity');

                        // Add new items before the load more button
                        const newActivities = [
                            {
                                icon: 'bx bx-cog',
                                iconClass: 'bg-warning-light text-warning',
                                title: 'System Settings Updated',
                                time: '3 days ago'
                            },
                            {
                                icon: 'bx bx-user-plus',
                                iconClass: 'bg-success-light text-success',
                                title: 'New User Added',
                                time: '5 days ago'
                            },
                            {
                                icon: 'bx bx-file',
                                iconClass: 'bg-info-light text-info',
                                title: 'Report Generated',
                                time: '1 week ago'
                            }
                        ];

                        newActivities.forEach(activity => {
                            const activityItem = document.createElement('div');
                            activityItem.className = 'activity-item d-flex';
                            activityItem.style.opacity = '0';
                            activityItem.style.transform = 'translateY(10px)';
                            activityItem.innerHTML = `
                                <div class="activity-icon ${activity.iconClass}">
                                    <i class="${activity.icon}"></i>
                                </div>
                                <div class="activity-content ms-3">
                                    <p class="mb-0"><strong>${activity.title}</strong></p>
                                    <small class="text-muted">${activity.time}</small>
                                </div>
                            `;

                            // Insert before the load more button
                            timelineContainer.insertBefore(activityItem, this.parentNode);

                            // Add timeline line
                            const timelineLine = document.createElement('div');
                            timelineLine.className = 'activity-timeline-line';
                            timelineContainer.insertBefore(timelineLine, this.parentNode);

                            // Animate in
                            setTimeout(() => {
                                activityItem.style.opacity = '1';
                                activityItem.style.transform = 'translateY(0)';
                                activityItem.style.transition = 'all 0.3s ease';
                            }, 100);
                        });

                        // Reset button
                        this.innerHTML = '<i class="bx bx-loader me-1"></i>Load More';
                        this.disabled = false;
                    }, 1500);
                });
            }

            // Add animation to system statistics
            const statCards = document.querySelectorAll('.stat-card');
            statCards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        });
    </script>
@endpush
