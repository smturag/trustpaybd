<!--start header -->
<header>
    <div class="topbar d-flex align-items-center">
        <nav class="navbar navbar-expand gap-3">

            <div class="mobile-toggle-menu"><i class='bx bx-menu'></i></div>

            <!-- Quick Access Buttons -->
            <div class="d-none d-md-flex align-items-center ms-3">
                <a href="{{ route('admin.merchant.payment-request') }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class='bx bxs-credit-card me-1'></i> Deposit
                    <span
                        class="badge bg-danger rounded-pill ms-auto right">{{ App\Models\PaymentRequest::where('status', 0)->whereNotNull('payment_method')->count() }}</span>
                </a>
                <a href="{{ route('serviceReq', 'all') }}" class="btn btn-sm btn-outline-warning me-2">
                    <i class='bx bx-mobile-alt me-1'></i> Withdraw
                      @php
                    $success = App\Models\ServiceRequest::whereIn('status', [2, 3])->count();
                    $pending = App\Models\ServiceRequest::where('status', 0)->count();
                    $wating = App\Models\ServiceRequest::where('status', 1)->count();
                    $reject = App\Models\ServiceRequest::where('status', 4)->count();
                    $processing = App\Models\ServiceRequest::where('status', 5)->count();
                    $failed = App\Models\ServiceRequest::where('status', 6)->count();
                    $MakeCount = $pending + $wating + $processing + $failed;
                @endphp

                <span class="badge bg-danger rounded-pill ms-auto right">{{ $MakeCount }}</span>
                </a>
                {{--

                <a href="{{ route('admin.wallet.transactions') }}" class="btn btn-sm btn-outline-info">
                    <i class='bx bx-transfer me-1'></i> Wallet Transaction
                </a>--}}
            </div>

            <!-- Notification Bell -->
            <div class="top-menu ms-auto">
                <ul class="navbar-nav align-items-center gap-2">
                    <li class="nav-item dropdown dropdown-large">
                        <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" data-bs-toggle="dropdown">
                            <i class='bx bx-bell fs-5'></i>
                            @php $notificationCount = 0; @endphp
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
                    <h5 class="text-center flex-grow-1 text-danger" id="billing"></h5>
                    <a href="https://billing.irecharge.net" target="_blank" id="billing-link" style="display: none;"> <u class="text-success"> Renew Now</u></a>
                    <li class="nav-item dark-mode d-sm-flex">
                        <a class="nav-link dark-mode-icon" href="javascript:;"><i class='bx bx-moon'></i></a>
                    </li>
                </ul>
            </div>
            <div class="user-box dropdown px-3">
                <a class="d-flex align-items-center nav-link dropdown-toggle gap-3 dropdown-toggle-nocaret" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="{{ asset('static/backend/images/avatars/avatar-2.png') }}" class="user-img" alt="user avatar" />
                    <div class="user-info">
                        <p class="user-name mb-0">{{ ucwords(Auth::guard('admin')->user()->admin_name) }}</p>
                        <p class="designation mb-0">{{ ucwords(Auth::guard('admin')->user()->type) }}</p>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('admin.profile') }}"><i class="bx bx-user fs-5"></i><span>Profile</span></a></li>
                    <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-cog fs-5"></i><span>Settings</span></a>
                    </li>
                    {{-- <li><a class="dropdown-item d-flex align-items-center" href="javascript:;"><i class="bx bx-dollar-circle fs-5"></i><span>Earnings</span></a>
                    </li> --}}
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('reset_balance') }}"><i class="bx bx-download fs-5"></i><span>Reset Balance</span></a>
                    </li>
                    <li>
                        <div class="dropdown-divider mb-0"></div>
                    </li>
                    <li><a class="dropdown-item d-flex align-items-center" href="{{ route('adminlogout') }}"><i class="bx bx-log-out-circle"></i><span>Logout</span></a>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</header>

 <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>

<script>
// Improved dark mode toggle implementation
document.addEventListener('DOMContentLoaded', function() {
    // Check if dark mode preference exists in local storage
    var isDarkMode = localStorage.getItem('darkModePreference') === 'true';

    // Make sure the icon matches the current theme
    updateDarkModeIcon(isDarkMode);

    // Add click event listener to the dark mode icon
    var darkModeIcon = document.querySelector(".dark-mode-icon");
    if (darkModeIcon) {
        darkModeIcon.addEventListener('click', function() {
            // Toggle the dark mode state
            isDarkMode = !isDarkMode;

            // Apply the theme with animation
            applyThemeWithAnimation(isDarkMode);

            // Store the dark mode preference in local storage
            localStorage.setItem('darkModePreference', isDarkMode);
        });
    }

    // Function to update just the icon based on theme
    function updateDarkModeIcon(isDark) {
        var icon = document.querySelector(".dark-mode-icon i");
        if (icon) {
            icon.className = isDark ? 'bx bx-sun' : 'bx bx-moon';
        }
    }

    // Function to apply theme with animation
    function applyThemeWithAnimation(isDark) {
        var html = document.documentElement;
        var darkModeIcon = document.querySelector(".dark-mode-icon");

        // Add animation class to indicate toggle is working
        if (darkModeIcon) {
            darkModeIcon.classList.add('theme-changing');
            setTimeout(function() {
                darkModeIcon.classList.remove('theme-changing');
            }, 500);
        }

        // Update the theme class
        html.className = isDark ? 'dark-theme' : 'light-theme';

        // Update the icon
        updateDarkModeIcon(isDark);
    }
});

 $(document).ready(function() {
            $.ajax({
                url: 'https://billing.irecharge.net/api/verify_me', // API URL
                method: 'POST', // POST request
                contentType: 'application/json', // Sending JSON
                data: JSON.stringify({
                    domain: 'cricex.xyz', // Data to send
                    service: 'iPaybd Billing For MFS Withdraw'
                }),
                success: function(response) {
                    // Display the response in the container

                    if(response.message_status){
                        $( "#billing" ).show();
                        $("#billing").text(response.message);
                        $("#billing-link").show();
                    }

                },
                error: function(xhr, status, error) {
                    // Handle any errors
                    console.error('Error:', error);
                    $('#response-container').html('An error occurred: ' + error);
                }
            });
    });


</script>

<style>
    /* Theme transition */
    html, body, .card, .widgets-icons, .sidebar-wrapper, .topbar,
    .page-wrapper, .page-content, .footer, * {
        transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
    }

    /* Light theme styles */
    body.light-theme {
        background-color: #f9fbfd;
        color: #4c5258;
    }

    /* Dark mode styles */
    body.dark-theme {
        background-color: #1a1f23;
        color: #e4e5e6;
    }

    /* Dark mode toggle button styles */
    .dark-mode-icon {
        cursor: pointer;
        transition: transform 0.3s ease;
        position: relative;
    }

    .dark-mode-icon:hover {
        transform: rotate(30deg);
    }

    /* Animation for theme change */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }

    .theme-changing {
        animation: pulse 0.5s ease;
    }

    .theme-changing i {
        transition: all 0.3s ease;
    }

    /* Improved card styles for both themes */
    .card {
        transition: all 0.3s ease;
        border-radius: 10px;
    }

    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    /* Light theme card */
    html.light-theme .card {
        background-color: #ffffff;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    /* Dark theme card */
    html.dark-theme .card {
        background-color: #252a30;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }

    /* Widget icons styling */
    .widgets-icons {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 24px;
        transition: all 0.3s;
    }

    /* Improved text colors for dark mode */
    html.dark-theme .text-secondary {
        color: #d1d3d5 !important;
    }

    html.dark-theme .text-info {
        color: #5bc0de !important;
    }

    html.dark-theme .text-success {
        color: #42d29d !important;
    }

    html.dark-theme .text-primary {
        color: #6c9fff !important;
    }

    /* Improved background colors for dark mode */
    html.dark-theme .bg-light-success {
        background-color: rgba(40, 199, 111, 0.2) !important;
    }

    html.dark-theme .bg-light-warning {
        background-color: rgba(255, 159, 67, 0.2) !important;
    }

    html.dark-theme .bg-light-danger {
        background-color: rgba(234, 84, 85, 0.2) !important;
    }

    html.dark-theme .bg-light-primary {
        background-color: rgba(115, 103, 240, 0.2) !important;
    }
</style>
