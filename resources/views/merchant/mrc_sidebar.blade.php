<!-- Modern Merchant Sidebar with Tailwind CSS -->
<aside id="merchant-sidebar" class="fixed top-0 left-0 z-50 w-64 h-screen transition-all duration-300 -translate-x-full lg:translate-x-0 sidebar-bg shadow-xl sidebar-border overflow-hidden">
    <!-- Sidebar Header -->
    <div class="flex items-center justify-between px-5 py-4 sidebar-header-border sidebar-header-gradient">
        @php
            $app_name = app_config('AppName');
        @endphp
        <div class="flex items-center space-x-3">
            <div class="flex items-center justify-center w-10 h-10 bg-white rounded-lg shadow-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                </svg>
            </div>
            <div>
                <h5 class="text-base font-bold text-white">{{ $app_name }}</h5>
                <span class="text-xs text-blue-100">Merchant Panel</span>
            </div>
        </div>
        <button id="sidebar-close" class="lg:hidden text-white hover:bg-white/20 rounded-lg p-1.5 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>


    <!-- Sidebar Navigation -->
    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1.5 sidebar-nav-bg" style="max-height: calc(100vh - 72px);">
        
        <!-- Dashboard -->
        <a href="{{ route('merchant_dashboard') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 {{ request()->routeIs('merchant_dashboard') ? 'sidebar-active' : 'sidebar-item' }}">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span class="text-sm">Dashboard</span>
        </a>

        <!-- Deposit Dropdown -->
        <div class="sidebar-dropdown">
            <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span class="text-sm">Deposit</span>
                </div>
                <svg class="w-4 h-4 transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="dropdown-content hidden pl-9 mt-1 space-y-1">
                <a href="{{ route('merchant.payment-request.create') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Create Deposit
                </a>
                <a href="{{ route('merchant.payment-request') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Deposit History
                </a>
            </div>
        </div>

        <!-- Withdraw Dropdown -->
        <div class="sidebar-dropdown">
            <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    <span class="text-sm">Withdraw</span>
                </div>
                <svg class="w-4 h-4 transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="dropdown-content hidden pl-9 mt-1 space-y-1">
                <a href="{{ route('merchant.withdraw') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Request Withdraw
                </a>
                <a href="{{ route('merchant.service-request') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Withdraw History
                </a>
            </div>
        </div>

        <!-- Crypto Payout Dropdown -->
        <div class="sidebar-dropdown">
            <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                <div class="flex items-center min-w-0">
                    <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm whitespace-nowrap">Crypto Payout</span>
                </div>
                <svg class="w-4 h-4 transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="dropdown-content hidden pl-9 mt-1 space-y-1">
                <a href="{{ route('merchant.payout') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Create Payout
                </a>
                <a href="{{ route('merchant.payout-history') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Payout History
                </a>
            </div>
        </div>

        <!-- Sub Merchant (Conditional) -->
        @if (auth()->guard('merchant')->user()->merchant_type == 'general')
            <a href="{{ route('sub_merchant.list') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="text-sm">Sub Merchant</span>
            </a>
        @endif

        <!-- Developer Dropdown -->
        <div class="sidebar-dropdown">
            <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                    </svg>
                    <span class="text-sm">Developer</span>
                </div>
                <svg class="w-4 h-4 transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="dropdown-content hidden pl-9 mt-1 space-y-1">
                <a href="{{ route('merchant.developer-index') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    API Keys
                </a>
                <a href="{{ route('develop_docs') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                    Documentation
                </a>
            </div>
        </div>

        <!-- Reports Dropdown (Conditional) -->
        @if (auth()->guard('merchant')->user()->merchant_type == 'general')
            <div class="sidebar-dropdown">
                <button class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        <span class="text-sm">Reports</span>
                    </div>
                    <svg class="w-4 h-4 transition-transform dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                <div class="dropdown-content hidden pl-9 mt-1 space-y-1">
                    <a href="{{ route('report.merchant.payment_report') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                        Payment Report
                    </a>
                    <a href="{{ route('report.merchant.service_report') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                        Service Report
                    </a>
                    <a href="{{ route('report.merchant.balance_summary') }}" class="block px-4 py-2 text-sm sidebar-dropdown-item transition-colors">
                        Balance Summary
                    </a>
                </div>
            </div>
        @endif

        <!-- Service Rates -->
        <a href="{{ route('merchant.developer.service-rates') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            <span class="text-sm">Service Rates</span>
        </a>

        <!-- Support -->
        <a href="{{ route('merchant.support_list_view') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span class="text-sm">Support</span>
        </a>

        <!-- My Account -->
        <a href="{{ route('merchant.profile') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-item">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-sm">My Account</span>
        </a>

        <!-- Divider -->
        <div class="sidebar-divider my-3"></div>

        <!-- Logout -->
        <a href="{{ route('merchantlogout') }}" class="flex items-center px-4 py-3 rounded-lg transition-all duration-200 sidebar-logout">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            <span class="text-sm font-medium">Logout</span>
        </a>
    </nav>
</aside>


<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 lg:hidden hidden transition-opacity duration-300"></div>

<!-- Sidebar JavaScript -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('merchant-sidebar');
        const sidebarClose = document.getElementById('sidebar-close');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        
        // Close sidebar function
        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            sidebarOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }
        
        // Close button click
        if (sidebarClose) {
            sidebarClose.addEventListener('click', closeSidebar);
        }
        
        // Overlay click
        if (sidebarOverlay) {
            sidebarOverlay.addEventListener('click', closeSidebar);
        }
        
        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !sidebar.classList.contains('-translate-x-full')) {
                closeSidebar();
            }
        });
        
        // Dropdown functionality
        const dropdowns = document.querySelectorAll('.sidebar-dropdown');
        dropdowns.forEach(dropdown => {
            const button = dropdown.querySelector('button');
            const content = dropdown.querySelector('.dropdown-content');
            
            if (button && content) {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    // Close other dropdowns
                    dropdowns.forEach(otherDropdown => {
                        if (otherDropdown !== dropdown) {
                            const otherContent = otherDropdown.querySelector('.dropdown-content');
                            const otherArrow = otherDropdown.querySelector('.dropdown-arrow');
                            if (otherContent) otherContent.classList.add('hidden');
                            if (otherArrow) otherArrow.classList.remove('rotate-180');
                        }
                    });
                    
                    // Toggle current dropdown
                    content.classList.toggle('hidden');
                    const arrow = dropdown.querySelector('.dropdown-arrow');
                    if (arrow) arrow.classList.toggle('rotate-180');
                });
            }
        });
        
        // Auto-close sidebar on link click (mobile)
        const sidebarLinks = sidebar.querySelectorAll('a[href]');
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function() {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1024) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebarOverlay.classList.add('hidden');
                    document.body.style.overflow = '';
                } else {
                    sidebar.classList.add('-translate-x-full');
                }
            }, 250);
        });
    });
</script>

<!-- Sidebar Custom Styles -->
<style>
    /* Light Mode Styles */
    .sidebar-bg {
        background: white;
        border-right: 1px solid #e5e7eb;
    }
    
    .sidebar-border {
        border-right: 1px solid #e5e7eb;
    }
    
    .sidebar-header-border {
        border-bottom: 1px solid #e5e7eb;
    }
    
    .sidebar-header-gradient {
        background: linear-gradient(to right, #2563eb, #4f46e5);
    }
    
    .sidebar-nav-bg {
        background: white;
    }
    
    .sidebar-item {
        color: #374151;
    }
    
    .sidebar-item:hover {
        background: #f3f4f6;
    }
    
    .sidebar-active {
        background: #eff6ff;
        color: #2563eb;
        font-weight: 600;
    }
    
    .sidebar-dropdown-item {
        color: #6b7280;
        border-radius: 0.5rem;
    }
    
    .sidebar-dropdown-item:hover {
        background: #f9fafb;
        color: #2563eb;
    }
    
    .sidebar-divider {
        border-top: 1px solid #e5e7eb;
    }
    
    .sidebar-logout {
        color: #dc2626;
    }
    
    .sidebar-logout:hover {
        background: #fef2f2;
    }
    
    /* Dark Mode Styles */
    html.dark-theme .sidebar-bg,
    html.dark-theme .sidebar-nav-bg {
        background: #111827;
    }
    
    html.dark-theme .sidebar-border {
        border-right-color: #374151;
    }
    
    html.dark-theme .sidebar-header-border {
        border-bottom-color: #374151;
    }
    
    html.dark-theme .sidebar-header-gradient {
        background: linear-gradient(to right, #1e40af, #4338ca);
    }
    
    html.dark-theme .sidebar-item {
        color: #d1d5db;
    }
    
    html.dark-theme .sidebar-item:hover {
        background: #1f2937;
    }
    
    html.dark-theme .sidebar-active {
        background: rgba(37, 99, 235, 0.2);
        color: #60a5fa;
        font-weight: 600;
    }
    
    html.dark-theme .sidebar-dropdown-item {
        color: #9ca3af;
    }
    
    html.dark-theme .sidebar-dropdown-item:hover {
        background: rgba(31, 41, 55, 0.5);
        color: #60a5fa;
    }
    
    html.dark-theme .sidebar-divider {
        border-top-color: #374151;
    }
    
    html.dark-theme .sidebar-logout {
        color: #f87171;
    }
    
    html.dark-theme .sidebar-logout:hover {
        background: rgba(239, 68, 68, 0.1);
    }
    
    /* Custom scrollbar for sidebar */
    #merchant-sidebar nav::-webkit-scrollbar {
        width: 6px;
    }
    
    #merchant-sidebar nav::-webkit-scrollbar-track {
        background: transparent;
    }
    
    #merchant-sidebar nav::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.3);
        border-radius: 3px;
    }
    
    #merchant-sidebar nav::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.5);
    }
    
    /* Dark mode scrollbar */
    html.dark-theme #merchant-sidebar nav::-webkit-scrollbar-thumb {
        background: rgba(71, 85, 105, 0.5);
    }
    
    html.dark-theme #merchant-sidebar nav::-webkit-scrollbar-thumb:hover {
        background: rgba(71, 85, 105, 0.7);
    }
    
    /* Smooth transitions */
    .dropdown-content {
        transition: all 0.3s ease;
    }
    
    .dropdown-arrow {
        transition: transform 0.3s ease;
    }
    
    /* Mobile touch improvements */
    @media (max-width: 1023px) {
        #merchant-sidebar a,
        #merchant-sidebar button {
            -webkit-tap-highlight-color: transparent;
        }
    }
</style>

