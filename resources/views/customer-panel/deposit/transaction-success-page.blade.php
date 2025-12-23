<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link href="{{ asset('static/backend/css/bootstrap.min.css') }}" rel="stylesheet">

    <style>
        body {
            font-size: 14px;
            color: #4c5258;
            letter-spacing: .5px;
            background: #f4f4f4;
            overflow-x: hidden;
            font-family: Roboto, sans-serif;
        }

        .loader {
            position: relative;
            margin: 0px auto;
            width: 100px;
        }

        .loader:before {
            content: '';
            display: block;
            padding-top: 100%;
        }

        .circular {
            -webkit-animation: rotate 2s linear infinite;
            animation: rotate 2s linear infinite;
            height: 100%;
            -webkit-transform-origin: center center;
            -ms-transform-origin: center center;
            transform-origin: center center;
            width: 100%;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            margin: auto;
        }

        .path {
            stroke-dasharray: 1, 200;
            stroke-dashoffset: 0;
            -webkit-animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
            animation: dash 1.5s ease-in-out infinite, color 6s ease-in-out infinite;
            stroke-linecap: round;
        }

        @-webkit-keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @keyframes rotate {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }

        @-webkit-keyframes dash {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -35;
            }
            100% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -124;
            }
        }

        @keyframes dash {
            0% {
                stroke-dasharray: 1, 200;
                stroke-dashoffset: 0;
            }
            50% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -35;
            }
            100% {
                stroke-dasharray: 89, 200;
                stroke-dashoffset: -124;
            }
        }

        @-webkit-keyframes color {
            100%, 0% {
                stroke: #d62d20;
            }
            40% {
                stroke: #0057e7;
            }
            66% {
                stroke: #008744;
            }
            80%, 90% {
                stroke: #ffa700;
            }
        }

        @keyframes color {
            100%, 0% {
                stroke: #d62d20;
            }
            40% {
                stroke: #0057e7;
            }
            66% {
                stroke: #008744;
            }
            80%, 90% {
                stroke: #ffa700;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row row-cols-xl-2 mt-5">
        <div class="col mx-auto">
            <div class="card mb-0">
                <div class="card-body">

                    <div class="alert alert-danger">
                        Please don't close the Browser until verification. I will be automatically closed.
                    </div>

                    <div class="loader">
                        <svg class="circular" viewBox="25 25 50 50">
                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                        </svg>
                    </div>

                    <p style="text-align: center;font-size: 18px;margin-top: 50px;">Please wait for confirmation</p>
                    <p id="timer" data-duration="120" class="text-center">02:00</p>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
<script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
<script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>

<script type="text/javascript">
    // Disable the reload button
    window.addEventListener('beforeunload', function(event) {
        event.preventDefault();
        event.returnValue = 'Refreshing is disabled on this page.';
    });

    document.addEventListener("keydown", function (event) {
        if (event.key === "F5" || (event.ctrlKey && event.key === "r")) {
            event.preventDefault();
        }
    });


    $(document).ready(function () {

        setInterval(function (e) {
            var payment_id = "{{ $data->id }}";
            var customer_id = "{{ $data->customer_id }}";
            var trxid = "{{ $data->trxid }}";
            var deposit_amount = "{{ $data->amount }}";
            var sim_id = "{{ $data->sim_id }}";
            var payment_method = "{{ $data->payment_method }}";

            $.ajax({
                url: "{{ url('/customer/get-deposit-success-status') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                type: "post",
                data: {payment_id: payment_id, customer_id: customer_id, trxid: trxid, deposit_amount: deposit_amount, sim_id:sim_id, payment_method:payment_method},
                dataType: "json",
                success: function (data) {
                    if (data.status_code === 200) {
                        toastr.success(data.message);
                        window.location.assign('/customer/dashboard');
                    }

                    if (data.status_code === 500) {
                        console.log(data.message);
                    }

                    if (data.status_code === 300) {
                        toastr.error(data.message);
                        window.location.assign('/customer/dashboard');
                    }
                }
            });
        }, 5 * 1000);

    });


    $(document).ready(function() {
        var timer = $('#timer');

        var duration = timer.data('duration');

        var interval = setInterval(function() {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timer.text("Redirect after "+minutes + ":" + seconds);

            if (--duration < 0) {
                clearInterval(interval);
                window.location.assign('/customer/dashboard');
            }

        }, 1000);
    });
</script>
</body>
</html>
