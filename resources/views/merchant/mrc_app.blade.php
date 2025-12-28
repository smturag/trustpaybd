<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Prevent Flash of Unstyled Content (FOUC) by applying theme before any content loads -->
    <script>
        (function() {
            // Check if dark mode preference exists in local storage
            var isDarkMode = localStorage.getItem('darkModePreference') === 'true';

            // Apply the theme immediately to the html element
            document.documentElement.className = isDarkMode ? 'dark-theme' : 'light-theme';

            // Also add a class to the body when it's created
            document.addEventListener('DOMContentLoaded', function() {
                if (isDarkMode) {
                    document.body.classList.add('dark-theme');
                } else {
                    document.body.classList.add('light-theme');
                }
            });
        })();
    </script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>
        @if (!isset($pageTitle))
            {{ config('app.name', 'Laravel') }}
        @else
            {{ 'Merchant ' . $pageTitle . ' - ' . config('app.name') }}
        @endif
    </title>

    <!-- Critical CSS for dark mode to prevent flash -->
    <style>
        /* Prevent flash during page load */
        .no-transition {
            -webkit-transition: none !important;
            -moz-transition: none !important;
            -o-transition: none !important;
            transition: none !important;
        }

        html.dark-theme {
            background-color: #070d0e;
            color: #e4e5e6;
        }

        html.dark-theme body {
            background-color: #070d0e;
            color: #e4e5e6;
        }

        html.dark-theme .wrapper,
        html.dark-theme .page-wrapper,
        html.dark-theme .page-content {
            background-color: #070d0e;
        }

        html.dark-theme .sidebar-wrapper {
            background-color: #12181a;
        }

        html.dark-theme .card {
            background-color: #12181a;
        }

        html.dark-theme .topbar {
            background-color: #12181a;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'sidebar-dark': '#0f172a',
                        'sidebar-light': '#1e293b',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Merchant Sidebar Styles -->
    <link href="{{ asset('css/merchant-tailwind-sidebar.css') }}" rel="stylesheet">
    
    <link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />
    @stack('css')

    <!-- Bootstrap CSS -->
    <link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('static/backend/css/bootstrap-extended.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
    <link href="{{ asset('static/backend/css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('static/backend/css/icons.css') }}" rel="stylesheet">
    <link href="{{ asset('noteStyle/script.js') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>


    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('static/backend/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/backend/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/backend/css/header-colors.css') }}" />
</head>

<body class="no-transition">
    <script>
        // Remove the no-transition class after the page has loaded
        window.addEventListener('load', function() {
            document.body.classList.remove('no-transition');
        });
    </script>
    <div class="wrapper">
        @include('merchant.mrc_sidebar')
        @include('merchant.mrc_head')
        <div id="main-content" class="page-wrapper lg:ml-64 transition-all duration-300">
            <div class="page-content px-3 sm:px-4 md:px-6">
                @yield('mrc_content')

            </div>
        </div>

        <!--start overlay-->
        <div class="overlay toggle-icon"></div>
        <!--end overlay-->
        <!--Start Back To Top Button-->
        <a href="javaScript:;" class="back-to-top"><i class='bx bxs-up-arrow-alt'></i></a>
        <!--End Back To Top Button-->
        <footer class="page-footer">
            <p class="mb-0">Copyright © {{ date('Y') }}. All right reserved.</p>
        </footer>
    </div>
    <!--end wrapper-->

    </div>


    <!-- Bootstrap JS -->
    <script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
    <!--plugins-->
    <script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('static/backend/plugins/simplebar/js/simplebar.min.js') }}"></script>
    <script src="{{ asset('static/backend/plugins/metismenu/js/metisMenu.min.js') }}"></script>
    <script src="{{ asset('noteStyle/script.js') }}"></script>
    @stack('js')

    <!-- Dark mode toggle script -->
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
    </script>

    <!-- Dark mode toggle styles -->
    <style>
        /* Theme transition */
        html, body, .card, .widgets-icons, .sidebar-wrapper, .topbar,
        .page-wrapper, .page-content, .footer, * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
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
    </style>

    <!-- App JS (with dark mode toggle disabled) -->
    <script src="{{ asset('static/backend/js/app.js') }}"></script>
    <script>
        // Disable the app.js dark mode toggle to avoid conflicts
        $(function() {
            // Override the dark mode toggle in app.js
            $(".dark-mode").off('click');
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
            } catch (oException) {}
        }

        $("form").submit(function() {
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


</body>

</html>
