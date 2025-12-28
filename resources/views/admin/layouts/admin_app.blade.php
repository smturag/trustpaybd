<!doctype html>
<html lang="en">
<head>
    <!-- Prevent Flash of Unstyled Content (FOUC) by applying theme before any content loads -->
    <script>
        (function() {
            // Check if dark mode is enabled
            const isDarkMode = localStorage.getItem('darkMode') === 'enabled';

            // Apply dark mode immediately for Tailwind
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ app_config('AppName') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="icon" href="{{ asset('static/backend/images/favicon-32x32.png') }}" type="image/png"/>

    <!-- Tailwind CSS with Custom Configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'primary': {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                    animation: {
                        'gradient': 'gradient 3s ease infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    },
                    boxShadow: {
                        'glow': '0 0 20px rgba(59, 130, 246, 0.5)',
                        'glow-lg': '0 0 30px rgba(59, 130, 246, 0.6)',
                    }
                }
            }
        }
    </script>
    
    <!-- Alpine.js for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Critical CSS for dark mode to prevent flash -->
    <style>
        /* Prevent flash during page load */
        .no-transition {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }

        /* Tailwind dark mode styles */
        html.dark {
            background-color: #111827;
            color-scheme: dark;
        }

        html.dark body {
            background-color: #111827;
            color: #f3f4f6;
        }
        
        html:not(.dark) {
            background-color: #f9fafb;
            color-scheme: light;
        }
        
        html:not(.dark) body {
            background-color: #f9fafb;
            color: #111827;
        }

        /* Tailwind dark mode wrapper backgrounds */
        html.dark .wrapper,
        html.dark .page-wrapper,
        html.dark .page-content {
            background-color: #111827;
        }

        html.dark .sidebar-wrapper {
            background-color: #1f2937;
        }

        html.dark .topbar {
            background-color: #1f2937;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Force dark mode for dashboard widgets */
        html.dark .bg-white {
            background-color: #1f2937 !important;
        }
        
        html.dark .text-gray-900 {
            color: #f3f4f6 !important;
        }
        
        html.dark .text-gray-500 {
            color: #9ca3af !important;
        }
        
        html.dark .text-gray-600 {
            color: #d1d5db !important;
        }
        
        html.dark .border-gray-100 {
            border-color: #374151 !important;
        }
        
        html.dark .bg-gray-50 {
            background-color: rgba(55, 65, 81, 0.5) !important;
        }

        /* Ensure page content is never hidden behind the sidebar */
        .page-wrapper {
            margin-left: 220px;
            transition: margin-left 0.3s ease;
        }
        @media (max-width: 1199px) {
            .page-wrapper {
                margin-left: 160px;
            }
        }
        @media (max-width: 991px) {
            .page-wrapper {
                margin-left: 0;
            }
        }

        /* Make default sidebar narrower */
        .sidebar-wrapper {
            width: 220px !important;
            transition: all 0.3s ease;
        }

        /* Sidebar collapsed state - make it compact but visible */
        .toggled .sidebar-wrapper {
            width: 70px !important;
        }
        
        .toggled .sidebar-wrapper .sidebar-header {
            padding: 0.5rem 0;
            text-align: center;
        }
        
        .toggled .sidebar-wrapper .sidebar-header .logo-icon {
            margin: 0 auto;
            display: block;
            max-width: 45px;
        }
        
        .toggled .sidebar-wrapper .sidebar-header .logo-text {
            display: none;
        }
        
        .toggled .sidebar-wrapper .metismenu li > a {
            padding: 0.75rem 0;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        
        .toggled .sidebar-wrapper .metismenu .parent-icon {
            margin: 0;
            width: 100%;
            text-align: center;
        }
        
        .toggled .sidebar-wrapper .metismenu .parent-icon i {
            font-size: 1.5rem;
            margin: 0;
        }
        
        .toggled .sidebar-wrapper .metismenu .menu-title {
            display: none;
        }
        
        .toggled .sidebar-wrapper .metismenu .badge {
            display: none;
        }
        
        .toggled .sidebar-wrapper .toggle-icon {
            display: flex !important;
        }
        
        .toggled .page-wrapper {
            margin-left: 70px;
        }
        
        @media (max-width: 991px) {
            .toggled .page-wrapper {
                margin-left: 0;
            }
            .sidebar-wrapper {
                width: 180px !important;
            }
        }

        @media (max-width: 767px) {
            .sidebar-wrapper {
                width: 220px !important;
            }
        }
    </style>

    <link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet"/>

    <link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.css" />




	@stack('css')
    <!-- Bootstrap CSS -->
    <link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/backend/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('static/backend/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('static/backend/css/icons.css') }}" rel="stylesheet">

    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('static/backend/css/dark-theme.css') }}"/>
    <link rel="stylesheet" href="{{ asset('static/backend/css/semi-dark.css') }}"/>
    <link rel="stylesheet" href="{{ asset('static/backend/css/header-colors.css') }}"/>

    {{-- Font Awesome --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-example" crossorigin="anonymous" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    {{-- data table --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />




</head>

<body class="bg-gray-50 dark:bg-gray-900 transition-colors duration-200">

<!-- Include Enhanced Sidebar -->
@include('admin.layouts.admin_sidebar')

<!-- Main Content Area with Premium Design -->
<div id="mainContent" class="lg:ml-64 transition-all duration-300 min-h-screen bg-gray-50 dark:bg-gray-900">
    @include('admin.layouts.admin_header')
    <div class="p-4 sm:p-6 bg-gray-50 dark:bg-gray-900">
        @yield('content')
    </div>
    @include('admin.layouts.admin_footer')
</div>




<script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>

<script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="{{ asset('static/backend/plugins/simplebar/js/simplebar.min.js') }}"></script>
<script src="{{ asset('static/backend/plugins/metismenu/js/metisMenu.min.js') }}"></script>

<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

{{-- data table --}}
<script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>


@stack('js')
<!-- Dark mode is now applied in the head section to prevent flash of unstyled content -->
<script src="{{ asset('static/backend/js/app.js') }}"></script>
<script>

$("form").submit(function(){
$("button[type=submit]").attr("disabled", "disabled");
$("button[type=submit]").empty().html("Please wait...");
});
</script>



    <script>
        function onlyNumbers(evt) {
            var e = event || evt; // for trans-browser compatibility
            var charCode = e.which || e.keyCode;
            if (charCode > 31 && (charCode < 48 || charCode > 57))
                return false;
            return true;

        }

        function PopUp(itarget, width, height) {
            try {
                width = (width != null) ? width : 400;
                height = (height != null) ? height : 400;
                var top, left;
                top = (screen.height / 2) - (height / 2);
                left = (screen.width / 2) - (width / 2);
                var lwin = window.open(itarget, '', 'location=no,height=' + height + ',width=' + width +
                    ',status=no,resizable=yes,scrollbars=yes,top=' + top + ',left=' + left);
                lwin.focus();
            } catch (oException) {
            }
        }

        $("form").submit(function () {
            $("button[type=submit]").attr("disabled", "disabled");
            $("button[type=submit]").empty().html("Please wait...");
        });

        function checkallbox(iobj) {
            inputArray = document.getElementsByTagName("input");
            for (i = 0; i < inputArray.length; i++) {
                if (inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj) {
                    tempCheckbox = inputArray[i];
                    if (tempCheckbox.checked) {
                        tempCheckbox.checked = false;
                    } else {
                        tempCheckbox.checked = true;
                    }
                }
            }
        }


        function checkallper(iobj) {
            inputArray = document.getElementsByName("per[]");
            for (i = 0; i < inputArray.length; i++) {
                if (inputArray[i].type.toLowerCase() == "checkbox" && inputArray[i] != iobj) {
                    tempCheckbox = inputArray[i];
                    if (tempCheckbox.checked) {
                        tempCheckbox.checked = false;
                    } else {
                        tempCheckbox.checked = true;
                    }
                }
            }
        }
    </script>

    <!-- Sidebar Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mobileToggle = document.getElementById('mobileSidebarToggle');
            const sidebarToggle = document.getElementById('sidebarToggle');
            
            // Create overlay
            const overlay = document.createElement('div');
            overlay.className = 'fixed inset-0 bg-black/50 z-40 hidden lg:hidden transition-opacity duration-300 opacity-0';
            document.body.appendChild(overlay);

            function openSidebar() {
                if(sidebar) {
                    sidebar.classList.remove('-translate-x-full');
                    sidebar.classList.add('translate-x-0');
                    overlay.classList.remove('hidden');
                    setTimeout(() => {
                        overlay.classList.remove('opacity-0');
                    }, 10);
                }
            }

            function closeSidebar() {
                if(sidebar) {
                    sidebar.classList.remove('translate-x-0');
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('opacity-0');
                    setTimeout(() => {
                        overlay.classList.add('hidden');
                    }, 300);
                }
            }

            if (mobileToggle) {
                mobileToggle.addEventListener('click', openSidebar);
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', closeSidebar);
            }

            overlay.addEventListener('click', closeSidebar);
        });
    </script>

    <!-- Dark Mode Toggle Script -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const darkModeToggle = document.getElementById('darkModeToggle');
        
        if (darkModeToggle) {
            // Check if dark mode is enabled
            const isDarkMode = localStorage.getItem('darkMode') === 'enabled';
            
            // Apply dark mode on page load
            if (isDarkMode) {
                document.documentElement.classList.add('dark');
                updateDarkModeIcon(true);
            } else {
                document.documentElement.classList.remove('dark');
                updateDarkModeIcon(false);
            }
            
            // Toggle dark mode on button click
            darkModeToggle.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const isCurrentlyDark = document.documentElement.classList.contains('dark');
                
                if (isCurrentlyDark) {
                    // Switch to light mode
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('darkMode', 'disabled');
                    updateDarkModeIcon(false);
                } else {
                    // Switch to dark mode
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('darkMode', 'enabled');
                    updateDarkModeIcon(true);
                }
            });
        }
        
        // Update icon based on current mode
        function updateDarkModeIcon(isDark) {
            const icon = darkModeToggle ? darkModeToggle.querySelector('svg') : null;
            if (icon) {
                if (isDark) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
                } else {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
                }
            }
        }
    });
    </script>

<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
</body>
</html>
