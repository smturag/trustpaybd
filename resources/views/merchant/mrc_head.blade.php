<header>
    <div class="topbar d-flex align-items-center shadow-sm">
        <nav class="navbar navbar-expand gap-3">
            <div class="mobile-toggle-menu">
                <i class='bx bx-menu fs-3'></i>
            </div>

            <!-- Quick Access Buttons -->
            <div class="d-none d-md-flex align-items-center ms-3">
                <a href="{{ route('merchant.payment-request') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class='bx bxs-credit-card me-1'></i> Deposit
                </a>
                <a href="{{ route('merchant.service-request') }}" class="btn btn-sm btn-outline-warning me-2">
                    <i class='bx bx-mobile-alt me-1'></i> Withdraw
                </a>
                <a href="{{ route('merchant.withdraw') }}" class="btn btn-sm btn-outline-danger">
                    <i class='bx bx-money-withdraw me-1'></i> Payout
                </a>
            </div>

            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-2">
                    <!-- Dark Mode Toggle -->
                    <li class="nav-item dark-mode d-none d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;">
                            <i class='bx bx-moon fs-5'></i>
                        </a>
                    </li>

                    <!-- Balance Display -->
                    <li class="nav-item d-none d-lg-flex">
                        @php
                            $merchant = Auth::guard('merchant')->user();
                        @endphp
                        <div class="d-flex align-items-center px-3 py-1 rounded-pill bg-light-success">
                            <i class='bx bx-wallet text-success me-1'></i>
                            <span class="fw-bold text-success">Main ৳{{ $merchant->balance }}</span>
                        </div>
                         <div class="d-flex align-items-center px-3 py-1 rounded-pill bg-light-success">
                            <i class='bx bx-wallet text-success me-1'></i>
                            <span class="fw-bold text-success">Available ৳{{ $merchant->available_balance }}</span>
                        </div>
                    </li>

                    <!-- Notifications -->
                    <li class="nav-item dropdown dropdown-large d-none">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#"
                            data-bs-toggle="dropdown">
                            <i class='bx bx-bell fs-5'></i>
                            @php
                                $notificationCount = 3; // Replace with dynamic count from backend if available
                            @endphp
                            @if($notificationCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:0.75rem;min-width:22px;">
                                    {{ $notificationCount }}
                                </span>
                            @endif
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <div class="header-notifications-list p-3">
                                <h6 class="mb-3">Notifications</h6>
                                <div class="text-center py-4">
                                    <p class="mt-2 small text-muted">No new notifications</p>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>

            <!-- User Profile Menu -->
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret"
                    href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-img rounded-circle bg-light-primary p-1">
                        <img src="{{ asset('static/backend/images/avatars/avatar-2.png') }}" class="rounded-circle" width="40"
                            alt="user avatar">
                    </div>
                    <span class="user-name mb-0 fw-bold ms-2" style="font-size:1.1rem;">
                        {{ auth('merchant')->user()->fullname ?? auth('merchant')->user()->name ?? auth('merchant')->user()->username }}
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0">
                    <li>
                        <a href="{{ route('merchant.profile') }}" class="dropdown-item d-flex align-items-center">
                            <i class="bx bx-user-circle fs-5 me-2 text-primary"></i>
                            <span>My Profile</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('merchant.developer-index') }}" class="dropdown-item d-flex align-items-center">
                            <i class="bx bx-code-block fs-5 me-2 text-primary"></i>
                            <span>Developer API</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('merchant.support_list_view') }}" class="dropdown-item d-flex align-items-center">
                            <i class="bx bx-headphone fs-5 me-2 text-primary"></i>
                            <span>Support</span>
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li>
                        <a class="dropdown-item d-flex align-items-center" href="{{ route('merchantlogout') }}">
                            <i class="bx bx-log-out-circle fs-5 me-2 text-danger"></i>
                            <span>Logout</span>
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>
 <!--end header -->
