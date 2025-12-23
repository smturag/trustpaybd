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
    <!-- Theme Style CSS -->
    <link rel="stylesheet" href="{{ asset('static/backend/css/dark-theme.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/backend/css/semi-dark.css') }}" />
    <link rel="stylesheet" href="{{ asset('static/backend/css/header-colors.css') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />


</head>

<body>


    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">

            <div class="ps-3">

            </div>
            <div class="ms-auto">
                <div class="btn-group">

                </div>
            </div>
        </div>



        <div class="row">


            <div class="col-xl-6 mx-auto">
                <div class="pricing-table">

                    <div class="row row-cols-12 row-cols-lg-12">
                        <!-- Free Tier -->
                        <div class="col">

                            <div class="mb-3 text-center">
                                <img src="{{ asset('static/backend/images/logo-icon.png') }}" width="60"
                                    alt="" />
                            </div>

                            <div class="card mb-5 mb-lg-0">

                                <div class="card-header bg-primary py-3 card radius-10">

                                    <h5 class="card-title text-white text-uppercase text-center">Cash Out</h5>
                                    <h6 class="card-price text-white text-center"><span id="amount"
                                            onclick="CopyToClipboardamount('amount');return false;">500</span>

                                </div>
                                <div class="card-body">






                                    <form class="row g-3">

                                        <div class="card radius-10 border-1 border-4">
                                            <div class="card-body">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <p class="mb-0 text-secondary">Agent Number</p>
                                                        <h4 class="my-3 text-info">
                                                            <div id="sample">0188888888</div>
                                                        </h4>
                                                        <span
                                                            onclick="CopyToClipboard('sample');return false;">Copy</span>
                                                    </div>
                                                    <div class="widgets-icons-2 rounded-circle text-white ms-auto">
                                                        <img src="{{ asset('static/backend/images/logo-icon.png') }}"
                                                            width="60" alt="" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <div class="col-md-12">
                                            <label for="input2" class="form-label">Payee Account No <span
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#exampleVerticallycenteredModal"><i
                                                        class="bx bx-info-square"></i></span>
                                            </label>
                                            <input type="text" class="form-control form-control-lg" id="input2"
                                                placeholder="যে নাম্বার থেকে পাঠিয়েছেন ">
                                        </div>
                                        <div class="col-md-12">
                                            <label for="input3" class="form-label">Transaction ID</label>
                                            <input type="text" class="form-control form-control-lg" id="input3"
                                                placeholder="ট্রানজেকশন আইডি দিন ">
                                        </div>




                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="false" aria-controls="collapseOne">
                                                        <span class="text-danger">Warning সতর্কতাঃ </span>
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="accordion-collapse collapse"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        Please note that you make deposit to <strong>NAGAD Cashout
                                                            (Agent)</strong>.
                                                        Be sure that you make the payment from the same wallet,
                                                        otherwise it may be lost.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="d-md-flex d-grid align-items-center gap-2">

                                                <button type="button" class="btn btn-primary">Submit</button>
                                                <button type="button" class="btn btn-danger">Cancell</button>

                                            </div>
                                        </div>

                                    </form>





                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="modal fade" id="exampleVerticallycenteredModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Modal title</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">Contrary to popular belief, Lorem Ipsum is not simply random text.
                                It has roots in a piece of classical Latin literature from 45 BC, making it over 2000
                                years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia,
                                looked up one of the more obscure Latin words, consectetur.</div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary"
                                    data-bs-dismiss="modal">Close</button>

                            </div>
                        </div>
                    </div>
                </div>


                <!-- Bootstrap JS -->
                <script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
                <!--plugins-->
                <script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
                <script src="{{ asset('static/backend/plugins/simplebar/js/simplebar.min.js') }}"></script>
                <script src="{{ asset('static/backend/plugins/metismenu/js/metisMenu.min.js') }}"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>

                <script type="text/javascript">
                    function CopyToClipboard(id) {
                        var r = document.createRange();
                        r.selectNode(document.getElementById(id));
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(r);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                    }

                    function CopyToClipboardamount(id) {
                        var r = document.createRange();
                        r.selectNode(document.getElementById(id));
                        window.getSelection().removeAllRanges();
                        window.getSelection().addRange(r);
                        document.execCommand('copy');
                        window.getSelection().removeAllRanges();
                    }
                </script>

</body>

</html>
