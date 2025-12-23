@extends('welcome')
@section('customer')

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

            .card.custom-card {
    width: 90%;
    margin: 0 auto; /* This will center the card body horizontally */
}

            .display-none {
                display: none;
            }

            @media only screen and (max-width: 992px) {

                 .wrapper{
            margin-top: 80px;
            padding-top: 10px;
        }

            }
        </style>

        {!! NoCaptcha::renderJs() !!}

    </head>

    <body class="">

        <!--wrapper-->

        <div class="wrapper mt-93 pt-180 ">

            <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">

                <div class="container">

                    <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-2">

                        <div class="col mx-auto">

                            <div class="card custom-card mb-0">

                                <div class="card-body ">

                                    <div class="p-4">

                                        <div class="mb-3 text-center">

                                            @php
                                            $app_name = app_config('AppName');
                                            $image = app_config('AppLogo');
                                            @endphp

                                            <img src="{{ asset('storage/' . $image) }}" width="60"
                                                alt="" />
                                        </div>

                                        <div class="text-center mb-4">

                                            <p class="mb-0">Please Customer log in to your account</p>

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

                                            <form class="row g-3" action="{{ route('customerloginAction') }}" method="POST"
                                                class="row g-3">

                                                @csrf

                                                <div class="col-12">

                                                    <label for="username" class="form-label">Email</label>

                                                    <input type="text" class="form-control" id="username"
                                                        name="username"
                                                        value="{{ old('username') ? old('username') : '' }}"
                                                        placeholder="Enter Email" required>

                                                </div>

                                                <div class="col-12">

                                                    <label for="inputChoosePassword" class="form-label">Password</label>

                                                    <div class="input-group" id="show_hide_password">

                                                        <input type="password" name="password"
                                                            class="form-control border-end-0" id="inputChoosePassword"
                                                            placeholder="Enter Password"> <a href="javascript:;"
                                                            class="input-group-text bg-transparent"><i
                                                                class='bx bx-hide'></i></a>

                                                    </div>

                                                </div>

                                                <div
                                                    class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">

                                                    {!! app('captcha')->display() !!}

                                                    @if ($errors->has('g-recaptcha-response'))
                                                        <span class="help-block">

                                                            <strong>{{ $errors->first('g-recaptcha-response') }}</strong>

                                                        </span>
                                                    @endif

                                                </div>

                                                <div class="col-12">

                                                    <div class="d-grid">

                                                        <button type="submit" class="btn btn-primary">Sign in</button>
                                                        <hr>
                                                    </div>

                                                </div>

                                                <div class="col-12">
                                                    <div class="text-center ">
                                                        <p class="mb-0"> <a
                                                                href="{{route('customer.forget_password')}}">Forget Password</a></p>
                                                    </div>
                                                </div>

                                                <div class="col-12">

                                                    <div class="d-grid">

                                                        <a href="{{route('customer.view_create_account')}}" class="btn btn-primary">Sign Up</a>
                                                        <hr>
                                                    </div>

                                                </div>

                                                <div class="list-inline contacts-social text-center">
                                                    <a href="javascript:;"
                                                        class="list-inline-item bg-facebook text-white border-0 rounded-3"><i
                                                            class="bx bxl-facebook"></i></a>
                                                    {{-- <a href="javascript:;" class="list-inline-item bg-twitter text-white border-0 rounded-3"><i class="bx bxl-twitter"></i></a> --}}
                                                    <a href="javascript:;"
                                                        class="list-inline-item bg-google text-white border-0 rounded-3"><i
                                                            class="bx bxl-google"></i></a>
                                                    {{-- <a href="javascript:;" class="list-inline-item bg-linkedin text-white border-0 rounded-3"><i class="bx bxl-linkedin"></i></a> --}}
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

        <!--Password show & hide js -->

        <script>
            $("form").submit(function() {

                $("button[type=submit]").attr("disabled", "disabled");

                $("button[type=submit]").empty().html("Please wait...");

            });

            $(document).ready(function() {

                $("#show_hide_password a").on('click', function(event) {

                    event.preventDefault();

                    if ($('#show_hide_password input').attr("type") == "text") {

                        $('#show_hide_password input').attr('type', 'password');

                        $('#show_hide_password i').addClass("bx-hide");

                        $('#show_hide_password i').removeClass("bx-show");

                    } else if ($('#show_hide_password input').attr("type") == "password") {

                        $('#show_hide_password input').attr('type', 'text');

                        $('#show_hide_password i').removeClass("bx-hide");

                        $('#show_hide_password i').addClass("bx-show");

                    }

                });

            });
        </script>

        <!--app JS-->

        <script src="{{ asset('static/backend/js/app.js') }}"></script>

    </body>

    </html>

@endsection
