<!-- Modern Tailwind Header -->
<header id="main-header" class="lg:ml-64 transition-all duration-300 sticky top-0 z-30">
    <nav class="header-bg header-border shadow-sm backdrop-blur-md bg-opacity-95">
        <div class="px-3 sm:px-4 lg:px-6 py-2.5 sm:py-3">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Toggle -->
                <button id="mobile-menu-toggle" class="lg:hidden p-2 rounded-lg header-icon-btn transition-colors -ml-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                
                <!-- Desktop Sidebar Toggle -->
                <button id="desktop-sidebar-toggle" class="hidden lg:flex p-2 rounded-lg header-icon-btn transition-colors -ml-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Quick Access Buttons (Desktop) -->
                <div class="hidden md:flex items-center space-x-2 lg:space-x-3">
                    <a href="{{ route('merchant.payment-request') }}" class="inline-flex items-center px-2.5 sm:px-3 lg:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white text-xs lg:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 mr-1 lg:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                        </svg>
                        <span class="hidden lg:inline">Deposit</span>
                        <span class="lg:hidden">Add</span>
                    </a>
                    <a href="{{ route('merchant.service-request') }}" class="inline-flex items-center px-2.5 sm:px-3 lg:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-orange-600 to-orange-700 hover:from-orange-700 hover:to-orange-800 text-white text-xs lg:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 mr-1 lg:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="hidden lg:inline">Withdraw</span>
                        <span class="lg:hidden">Out</span>
                    </a>
                    <a href="{{ route('merchant.payout') }}" class="inline-flex items-center px-2.5 sm:px-3 lg:px-4 py-1.5 sm:py-2 bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white text-xs lg:text-sm font-medium rounded-lg shadow-sm hover:shadow-md transition-all duration-200 hover:scale-105 active:scale-95">
                        <svg class="w-4 h-4 mr-1 lg:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="hidden lg:inline">Crypto</span>
                        <span class="lg:hidden">₿</span>
                    </a>
                </div>

                <!-- Right Side Actions -->
                <div class="flex items-center space-x-2 sm:space-x-3 ml-auto">
                    <!-- Return to Admin (if impersonating) -->
                    @if(session('impersonate_admin_id'))
                        <a href="{{ route('merchant.returnToAdmin') }}" class="inline-flex items-center px-2 sm:px-3 py-1.5 sm:py-2 bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-1 sm:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                            </svg>
                            <span class="hidden sm:inline">Return to Admin</span>
                            <span class="sm:hidden">Admin</span>
                        </a>
                    @endif

                    <!-- Balance Display -->
                    @php
                        $merchant = Auth::guard('merchant')->user();
                    @endphp
                    <div class="hidden md:flex items-center space-x-2 lg:space-x-3">
                        <div class="flex items-center px-2 lg:px-4 py-2 balance-card-emerald border border-emerald-200 rounded-lg lg:rounded-xl">
                            <svg class="w-4 lg:w-5 h-4 lg:h-5 balance-icon-emerald mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                            </svg>
                            <div class="text-left">
                                <p class="text-[10px] lg:text-xs balance-label-emerald font-medium">Main</p>
                                <p class="text-xs lg:text-sm font-bold balance-amount-emerald">৳{{ number_format($merchant->balance, 2) }}</p>
                            </div>
                        </div>
                        <div class="flex items-center px-2 lg:px-4 py-2 balance-card-blue border border-blue-200 rounded-lg lg:rounded-xl">
                            <svg class="w-4 lg:w-5 h-4 lg:h-5 balance-icon-blue mr-1 lg:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div class="text-left">
                                <p class="text-[10px] lg:text-xs balance-label-blue font-medium">Available</p>
                                <p class="text-xs lg:text-sm font-bold balance-amount-blue">৳{{ number_format($merchant->available_balance, 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Dark Mode Toggle -->
                    <button class="hidden sm:flex items-center justify-center w-10 h-10 rounded-lg header-icon-btn transition-colors dark-mode-icon">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                        </svg>
                    </button>

                    <!-- Notifications -->
                    @include('merchant.partials.notification_dropdown')

                    <!-- User Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.away="open = false">
                        <button @click="open = !open" class="flex items-center space-x-3 px-3 py-2 rounded-lg header-profile-btn transition-colors">
                            <div class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full text-white font-semibold shadow-lg">
                                {{ substr(auth('merchant')->user()->fullname ?? auth('merchant')->user()->username, 0, 2) }}
                            </div>
                            <div class="hidden md:block text-left">
                                <p class="text-sm font-semibold header-text-primary">
                                    {{ auth('merchant')->user()->fullname ?? auth('merchant')->user()->name ?? auth('merchant')->user()->username }}
                                </p>
                                <p class="text-xs header-text-secondary">Merchant Account</p>
                            </div>
                            <svg class="w-4 h-4 header-text-secondary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" 
                             x-transition:enter="transition ease-out duration-200"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-150"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-64 dropdown-menu rounded-xl shadow-2xl border dropdown-border py-2 z-50"
                             style="display: none;">
                            
                            <div class="px-4 py-3 border-b dropdown-border">
                                <p class="text-sm font-semibold header-text-primary">
                                    {{ auth('merchant')->user()->fullname ?? auth('merchant')->user()->username }}
                                </p>
                                <p class="text-xs header-text-secondary mt-1">{{ auth('merchant')->user()->email ?? auth('merchant')->user()->username }}</p>
                            </div>

                            <div class="py-2">
                                <a href="{{ route('merchant.profile') }}" class="flex items-center px-4 py-2 text-sm dropdown-item transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Profile
                                </a>
                                <a href="{{ route('merchant.developer-index') }}" class="flex items-center px-4 py-2 text-sm dropdown-item transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                    </svg>
                                    Developer API
                                </a>
                                <a href="{{ route('merchant.support_list_view') }}" class="flex items-center px-4 py-2 text-sm dropdown-item transition-colors">
                                    <svg class="w-4 h-4 mr-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Support
                                </a>
                            </div>

                            <div class="border-t dropdown-border pt-2">
                                <a href="{{ route('merchantlogout') }}" class="flex items-center px-4 py-2 text-sm dropdown-logout transition-colors">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </a>
                            </div>
                        </div>
                    </div>

        <!-- Mobile Balance Display (Visible only on mobile) -->
        <div class="md:hidden mobile-balance-section px-4 py-2">
            <div class="flex items-center justify-between space-x-2">
                <div class="flex-1 flex items-center px-2 py-1.5 balance-card-emerald-mobile border border-emerald-200 rounded-lg">
                    <svg class="w-4 h-4 balance-icon-emerald mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" />
                    </svg>
                    <div class="text-left min-w-0 flex-1">
                        <p class="text-[9px] balance-label-emerald font-medium leading-tight">Main</p>
                        <p class="text-xs font-bold balance-amount-emerald truncate">{{ number_format($merchant->balance, 2) }}</p>
                    </div>
                </div>
                <div class="flex-1 flex items-center px-2 py-1.5 balance-card-blue-mobile border border-blue-200 rounded-lg">
                    <svg class="w-4 h-4 balance-icon-blue mr-1.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-left min-w-0 flex-1">
                        <p class="text-[9px] balance-label-blue font-medium leading-tight">Available</p>
                        <p class="text-xs font-bold balance-amount-blue truncate">{{ number_format($merchant->available_balance, 2) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>

<!-- Alpine.js for dropdown functionality -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

<!-- Sidebar toggle script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileToggle = document.getElementById('mobile-menu-toggle');
        const desktopToggle = document.getElementById('desktop-sidebar-toggle');
        const sidebar = document.getElementById('merchant-sidebar');
        const sidebarOverlay = document.getElementById('sidebar-overlay');
        const mainHeader = document.getElementById('main-header');
        const mainContent = document.getElementById('main-content');
        
        // Mobile toggle - opens sidebar
        if (mobileToggle && sidebar) {
            mobileToggle.addEventListener('click', function() {
                sidebar.classList.remove('-translate-x-full');
                if (sidebarOverlay) {
                    sidebarOverlay.classList.remove('hidden');
                }
                document.body.style.overflow = 'hidden';
            });
        }
        
        // Desktop toggle - collapses/expands sidebar
        if (desktopToggle && sidebar) {
            desktopToggle.addEventListener('click', function() {
                const isCollapsed = sidebar.classList.contains('sidebar-collapsed');
                
                if (isCollapsed) {
                    // Expand sidebar
                    sidebar.classList.remove('sidebar-collapsed');
                    if (mainHeader) mainHeader.classList.remove('sidebar-collapsed');
                    if (mainContent) mainContent.classList.remove('sidebar-collapsed');
                    localStorage.setItem('sidebarCollapsed', 'false');
                } else {
                    // Collapse sidebar
                    sidebar.classList.add('sidebar-collapsed');
                    if (mainHeader) mainHeader.classList.add('sidebar-collapsed');
                    if (mainContent) mainContent.classList.add('sidebar-collapsed');
                    localStorage.setItem('sidebarCollapsed', 'true');
                }
            });
        }
        
        // Restore sidebar state from localStorage
        const sidebarCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (sidebarCollapsed && window.innerWidth >= 1024) {
            sidebar.classList.add('sidebar-collapsed');
            if (mainHeader) mainHeader.classList.add('sidebar-collapsed');
            if (mainContent) mainContent.classList.add('sidebar-collapsed');
        }
    });
</script>

<!-- Header Dark Mode Styles -->
<style>
    /* Light Mode Styles */
    .header-bg {
        background: white;
    }
    
    .header-border {
        border-bottom: 1px solid #e2e8f0;
    }
    
    .header-icon-btn {
        color: #475569;
    }
    
    .header-icon-btn:hover {
        background: #f1f5f9;
    }
    
    .header-profile-btn:hover {
        background: #f1f5f9;
    }
    
    .header-text-primary {
        color: #1e293b;
    }
    
    .header-text-secondary {
        color: #64748b;
    }
    
    .dropdown-menu {
        background: white;
    }
    
    .dropdown-border {
        border-color: #e2e8f0;
    }
    
    .dropdown-item {
        color: #334155;
    }
    
    .dropdown-item:hover {
        background: #f1f5f9;
    }
    
    .dropdown-logout {
        color: #dc2626;
    }
    
    .dropdown-logout:hover {
        background: #fef2f2;
    }
    
    .mobile-balance-section {
        border-top: 1px solid #e2e8f0;
        background: #f8fafc;
    }
    
    /* Balance Cards */
    .balance-card-emerald {
        background: linear-gradient(to right, #ecfdf5, #d1fae5);
    }
    
    .balance-card-emerald-mobile {
        background: linear-gradient(to right, #ecfdf5, #d1fae5);
    }
    
    .balance-icon-emerald {
        color: #059669;
    }
    
    .balance-label-emerald {
        color: #059669;
    }
    
    .balance-amount-emerald {
        color: #047857;
    }
    
    .balance-card-blue {
        background: linear-gradient(to right, #eff6ff, #dbeafe);
    }
    
    .balance-card-blue-mobile {
        background: linear-gradient(to right, #eff6ff, #dbeafe);
    }
    
    .balance-icon-blue {
        color: #2563eb;
    }
    
    .balance-label-blue {
        color: #2563eb;
    }
    
    .balance-amount-blue {
        color: #1d4ed8;
    }
    
    /* Dark Mode Styles */
    html.dark-theme .header-bg {
        background: #0f172a;
    }
    
    html.dark-theme .header-border {
        border-bottom-color: #334155;
    }
    
    html.dark-theme .header-icon-btn {
        color: #cbd5e1;
    }
    
    html.dark-theme .header-icon-btn:hover {
        background: #1e293b;
    }
    
    html.dark-theme .header-profile-btn:hover {
        background: #1e293b;
    }
    
    html.dark-theme .header-text-primary {
        color: #e2e8f0;
    }
    
    html.dark-theme .header-text-secondary {
        color: #94a3b8;
    }
    
    html.dark-theme .dropdown-menu {
        background: #1e293b;
    }
    
    html.dark-theme .dropdown-border {
        border-color: #334155;
    }
    
    html.dark-theme .dropdown-item {
        color: #cbd5e1;
    }
    
    html.dark-theme .dropdown-item:hover {
        background: #334155;
    }
    
    html.dark-theme .dropdown-logout {
        color: #f87171;
    }
    
    html.dark-theme .dropdown-logout:hover {
        background: rgba(239, 68, 68, 0.1);
    }
    
    html.dark-theme .mobile-balance-section {
        border-top-color: #334155;
        background: rgba(30, 41, 55, 0.5);
    }
    
    /* Dark Mode Balance Cards */
    html.dark-theme .balance-card-emerald {
        background: rgba(16, 185, 129, 0.1);
        border-color: #065f46;
    }
    
    html.dark-theme .balance-card-emerald-mobile {
        background: rgba(16, 185, 129, 0.1);
        border-color: #065f46;
    }
    
    html.dark-theme .balance-icon-emerald {
        color: #34d399;
    }
    
    html.dark-theme .balance-label-emerald {
        color: #34d399;
    }
    
    html.dark-theme .balance-amount-emerald {
        color: #6ee7b7;
    }
    
    html.dark-theme .balance-card-blue {
        background: rgba(59, 130, 246, 0.1);
        border-color: #1e40af;
    }
    
    html.dark-theme .balance-card-blue-mobile {
        background: rgba(59, 130, 246, 0.1);
        border-color: #1e40af;
    }
    
    html.dark-theme .balance-icon-blue {
        color: #60a5fa;
    }
    
    html.dark-theme .balance-label-blue {
        color: #60a5fa;
    }
    
    html.dark-theme .balance-amount-blue {
        color: #93c5fd;
    }
</style>
