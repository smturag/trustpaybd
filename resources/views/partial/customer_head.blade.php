{{--

<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="zyNZfpLzvnaqwPzidgpe5DAtAwbMCWKwjmnOxIgu">
<meta name="description" content="Home">
<meta name="keywords" content="">
<title>Home</title>

<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/customstyle.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/style.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/fontawesome/css/all.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/themify-icons.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/select2.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/new-template/common-header.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/new-template/style.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('resources/views/Themes/modern/assets/public/css/new-template/owl-css/owl.min.css') }}">

<link rel="javascript" href="{{ asset('public/frontend/js/respond.html') }}">
<link rel="shortcut icon" href="{{ asset('images/logos/1530689937_favicon.png') }}" />

<script type="text/javascript">
    var SITE_URL = "index.html";
</script>

<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ config('app.name', 'Laravel') }}</title>

<link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
<link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet"/>
<link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('static/backend/css/bootstrap-extended.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
<link href="{{ asset('static/backend/css/app.css') }}" rel="stylesheet">
<link href="{{ asset('static/backend/css/icons.css') }}" rel="stylesheet">

<style type="text/css">
    input.pw {
        -webkit-text-security: circle;
    }

    .display-none {
        display: none;
    }

    html {
        scroll-behavior: smooth;
    }
</style>

{!! NoCaptcha::renderJs() !!} --}}


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trustpaybd - Modern Payment Gateway and Withdraw Solutions for Bangladeshi Mobile Banking</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
    <style>
        /* Custom animations */
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .float-animation {
            animation: float 3s ease-in-out infinite;
        }

        /* Glass morphism effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
        }

        /* Gradient text animation */
        .animated-gradient-text {
            background: linear-gradient(90deg, #4f46e5, #06b6d4, #4f46e5);
            background-size: 200% auto;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            animation: gradient 3s linear infinite;
        }

        @keyframes gradient {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(20px, -30px) scale(1.1); }
            66% { transform: translate(-15px, 15px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }

        .animate-blob {
            animation: blob 5s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb {
            background: #c5c5c5;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #a8a8a8;
        }

        /* Code block styles */
        .code-wrapper {
            position: relative;
            margin: 1.5rem 0;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .copy-button {
            position: absolute;
            top: 8px;
            right: 8px;
            padding: 6px 12px;
            background-color: rgba(99, 102, 241, 0.1);
            border-radius: 6px;
            font-size: 12px;
            color: #6366f1;
            cursor: pointer;
            transition: all 0.2s;
            backdrop-filter: blur(4px);
        }
        .copy-button:hover {
            background-color: rgba(99, 102, 241, 0.2);
            transform: translateY(-1px);
        }

        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }

        /* Active link indicator */
        .nav-link.active {
            color: #4f46e5;
            border-left: 3px solid #4f46e5;
            padding-left: 13px;
            margin-left: -16px;
        }

        /* Code block language indicator */
        .code-wrapper::before {
            content: attr(data-language);
            position: absolute;
            top: 0;
            left: 0;
            padding: 4px 8px;
            font-size: 12px;
            color: #6b7280;
            background-color: rgba(255, 255, 255, 0.1);
            border-bottom-right-radius: 4px;
        }

        /* Section transitions */
        section {
            transition: transform 0.3s ease-in-out;
        }
        section:hover {
            transform: translateY(-2px);
        }


    </style>
</head>
