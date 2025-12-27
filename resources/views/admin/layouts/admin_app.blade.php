<!doctype html>
<html lang="en">
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
    <title>{{ app_config('AppName') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <link rel="icon" href="{{ asset('static/backend/images/favicon-32x32.png') }}" type="image/png"/>

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

<body class="no-transition">
<script>
    // Remove the no-transition class after the page has loaded
    window.addEventListener('load', function() {
        document.body.classList.remove('no-transition');
    });
</script>
<div class="wrapper">
    @include('admin.layouts.admin_sidebar')
	 <div class="page-wrapper">
        <div class="page-content">
    @yield('content')
	</div>
	</div>
    @include('admin.layouts.admin_header')
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


<!--<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>-->
</body>
</html>
