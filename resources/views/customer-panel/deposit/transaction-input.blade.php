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
        .number_block {
            padding: 10px;
            background: #f7f7f7;
            color: #000;
        }

        .transaction_input {
            border: 2px solid #0d6efd;
            border-radius: 0px;
            height: 47px;
            font-weight: 700;
            margin-bottom: 12px;
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

        @-webkit-keyframes
        rotate {  100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
        @keyframes
        rotate {  100% {
            -webkit-transform: rotate(360deg);
            transform: rotate(360deg);
        }
        }
        @-webkit-keyframes
        dash {  0% {
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
        @keyframes
        dash {  0% {
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
        @-webkit-keyframes
        color {  100%, 0% {
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
        @keyframes
        color {  100%, 0% {
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
                    <div class="p-4">
                        <div class="col-12">
                            <div class="d-grid">
                                <h3 class="text-primary fw-bold text-center">{{ ucwords($paymentMethods->mfs_operator->name) }} Agent Cashout</h3>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <h3 class="fw-bold text-center number_block">{{ $paymentMethods->sim_id }}</h3>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <p class="fw-bold text-center">এই নাম্বারে ক্যাশ আউট করুন</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-grid">
                                <h3 class="fw-bold text-center number_block text-success">{{ number_format($props_data['deposit_amount'],2) }}৳</h3>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-grid">
                                <p class="fw-bold text-center">10 মিনিটের মধ্যে ক্যাশআউট সম্পন্ন করুন</p>
                            </div>
                        </div>

                        <form id="depositFormSubmit" action="#" method="post">

                            <div class="col-12">
                                <div class="d-grid">
                                    <input type="text" name="trxid" id="trxid" required class="transaction_input form-control" placeholder="Enter transaction ID" autocomplete="off" />

                                    @if($errors->any())
                                        @foreach($errors->all() as $error)
                                            <span class="text text-danger">{{ $error }}</span>
                                        @endforeach
                                        <br>
                                    @endif
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <p class="fw-bold text-center">ক্যাশআউটের TrxID নাম্বারটি লিখুন</p>
                                </div>
                            </div>
                            <div class="col-12 mt-3">
                                <div class="d-grid">
                                    <input type="hidden" value="{{ $paymentMethods->sim_id }}" name="sim_id">
                                    <input type="hidden" value="{{ $customer_id }}" name="customer_id" id="customer_id">
                                    <input type="hidden" value="{{ $props_data['deposit_amount'] }}" name="deposit_amount">
                                    <input type="hidden" value="{{ $props_data['payment_method'] }}" name="payment_method">
                                    <button type="submit" class="btn btn-primary" id="verifyTransaction0">Verify</button>
                                </div>
                            </div>
                        </form>

                        <div class="col-12">
                            <div class="d-grid">
                                <p id="timer" data-duration="600" class="fw-bold mt-2 text-center text-danger">Remaining 9:23</p>
                                <p><a href="{{ url()->previous() }}">Back</a></p>
                            </div>
                        </div>

                    </div>
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
    $(document).ready(function () {
        $(document).on('submit', 'form#depositFormSubmit', function (event){
            event.preventDefault();

            if($("#trxid").val() == "") { alert('Please Enter Transaction Number'); return; }

            var modal = $("#depositWaitingModal");
            var button = $(this);
            var form = $('form#depositFormSubmit');

            button.attr("disabled", "disabled");

            swal({
                title: "Are you sure?",
                text: "You will Submit this Deposit request?",
                icon: "warning",
                buttons: {
                    cancel: true,
                    confirm: 'Confirm',
                },
                dangerMode: true,
            }).then((isConfirm) => {
                if (isConfirm) {
                    $.ajax({
                        url: "{{ url('/customer/deposit-form-submit') }}",
                        headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        type: "post",
                        data: form.serialize(),
                        dataType: "json",
                        success: function (data) {
                            if(data.status_code === 200) {
                                window.location.assign('/customer/dashboard');
                            }

                            if(data.status_code === 300) {
                                let url = "{{ url('/customer/deposit/payment/success-page') }}/" + data.insert_payment_id;
                                window.location.assign(url);
                                history.replaceState(null, null, url);
                            }
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            //toastr.warning(jqXHR.responseJSON.errors.customer_id[0]);
                            button.removeAttr('disabled');
                        }
                    });
                }
            });
        });
    });



    $(document).ready(function() {
        var timer = $('#timer');

        var duration = timer.data('duration');

        var interval = setInterval(function() {
            let minutes = Math.floor(duration / 60);
            let seconds = duration % 60;

            minutes = minutes < 10 ? "0" + minutes : minutes;
            seconds = seconds < 10 ? "0" + seconds : seconds;

            timer.text("Warning! After "+minutes + ":" + seconds +" payment number will be changed.");

            if (--duration < 0) {
                clearInterval(interval);
                window.location.reload();
            }
        }, 1000);
    });
</script>
</body>
</html>
