<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">



    <!-- CSRF Token -->

    <meta name="csrf-token" content="{{ csrf_token() }}">



    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('static/backend/plugins/simplebar/css/simplebar.css') }}" rel="stylesheet" />
    <link href="{{ asset('static/backend/plugins/metismenu/css/metisMenu.min.css') }}" rel="stylesheet" />

    <!-- Bootstrap CSS -->

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
    </style>

    {!! NoCaptcha::renderJs() !!}





</head>

@php
$app_name = app_config('AppName');
$image = app_config('AppLogo');
@endphp

<body class="">

    <!--wrapper-->

    <div class="wrapper">

        <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">

            <div class="container">

                <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">

                    <div class="col mx-auto">

                        <div class="card mb-0">

                            <div class="card-body">

                                <div class="p-4">

                                    <div class="mb-3 text-center">

                                        <img src="{{ asset('storage/' . $image) }}" width="60"
                                            alt="" />

                                    </div>

                                    <div class="text-center mb-4">



                                        <p class="mb-0">Please Merchant log in to your account</p>



                                        @if ($errors->any())



                                            <div class="alert alert-danger">

                                                <ul>

                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach

                                                </ul>

                                            </div>





                                        @endif





                                        @if (Session::has('message'))
                                            <div class="alert alert-success">{{ Session::get('message') }}</div>
                                        @endif



                                        @if (Session::has('alert'))
                                            <div class="alert alert-danger">{{ Session::get('alert') }}</div>
                                        @endif
                                    </div>

                                    <div class="form-body">

                                        <form action="{{ route('merchant.merchant_forget_password') }}" method="POST"
                                            class="row g-3">

                                            @csrf



                                            <div class="col-12">

                                                <label for="username" class="form-label">Email</label>

                                                <input type="email" class="form-control" id="username"
                                                    name="email"  placeholder="Enter Email" required>

                                            </div>

                                            <div class="col-12">

                                                <div class="d-grid">

                                                    <button type="submit" class="btn btn-primary">Forget Password</button>

                                                </div>

                                            </div>

                                        </form>

                                    </div>


                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <!--end row-->

            </div>

        </div>

    </div>

    <!--end wrapper-->





    <!-- Bootstrap JS -->

    <script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>

    <!--plugins-->

    <script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
    <!--app JS-->

    <script src="{{ asset('static/backend/js/app.js') }}"></script>





</body>

</html>
