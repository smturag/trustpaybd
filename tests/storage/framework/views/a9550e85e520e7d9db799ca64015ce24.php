<?php $__env->startSection('customer'); ?>

    <!doctype html>

    <html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- CSRF Token -->

        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <title><?php echo e(config('app.name', 'Laravel')); ?></title>
        <link href="<?php echo e(asset('static/backend/plugins/simplebar/css/simplebar.css')); ?>" rel="stylesheet" />
        <link href="<?php echo e(asset('static/backend/plugins/metismenu/css/metisMenu.min.css')); ?>" rel="stylesheet" />
        <!-- Bootstrap CSS -->

        <link href="<?php echo e(asset('static/backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/backend/css/bootstrap-extended.css')); ?>" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
        <link href="<?php echo e(asset('static/backend/css/app.css')); ?>" rel="stylesheet">
        <link href="<?php echo e(asset('static/backend/css/icons.css')); ?>" rel="stylesheet">

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

        <?php echo NoCaptcha::renderJs(); ?>


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

                                            <?php
                                            $app_name = app_config('AppName');
                                            $image = app_config('AppLogo');
                                            ?>

                                            <img src="<?php echo e(asset('storage/' . $image)); ?>" width="60"
                                                alt="" />
                                        </div>

                                        <div class="text-center mb-4">

                                            <p class="mb-0">Please Customer log in to your account</p>

                                            <?php if($errors->any()): ?>
                                                <div class="alert alert-danger">

                                                    <ul>
                                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <li><?php echo e($error); ?></li>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                    </ul>
                                                </div>
                                            <?php endif; ?>

                                            <?php if(Session::has('message')): ?>
                                                <div class="alert alert-success"><?php echo e(Session::get('message')); ?></div>
                                            <?php endif; ?>


                                            <?php if(Session::has('alert')): ?>
                                                <div class="alert alert-danger"><?php echo e(Session::get('alert')); ?></div>
                                            <?php endif; ?>

                                        </div>

                                        <div class="form-body">

                                            <form class="row g-3" action="<?php echo e(route('customerloginAction')); ?>" method="POST"
                                                class="row g-3">

                                                <?php echo csrf_field(); ?>

                                                <div class="col-12">

                                                    <label for="username" class="form-label">Email</label>

                                                    <input type="text" class="form-control" id="username"
                                                        name="username"
                                                        value="<?php echo e(old('username') ? old('username') : ''); ?>"
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
                                                    class="form-group<?php echo e($errors->has('g-recaptcha-response') ? ' has-error' : ''); ?>">

                                                    <?php echo app('captcha')->display(); ?>


                                                    <?php if($errors->has('g-recaptcha-response')): ?>
                                                        <span class="help-block">

                                                            <strong><?php echo e($errors->first('g-recaptcha-response')); ?></strong>

                                                        </span>
                                                    <?php endif; ?>

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
                                                                href="<?php echo e(route('customer.forget_password')); ?>">Forget Password</a></p>
                                                    </div>
                                                </div>

                                                <div class="col-12">

                                                    <div class="d-grid">

                                                        <a href="<?php echo e(route('customer.view_create_account')); ?>" class="btn btn-primary">Sign Up</a>
                                                        <hr>
                                                    </div>

                                                </div>

                                                <div class="list-inline contacts-social text-center">
                                                    <a href="javascript:;"
                                                        class="list-inline-item bg-facebook text-white border-0 rounded-3"><i
                                                            class="bx bxl-facebook"></i></a>
                                                    
                                                    <a href="javascript:;"
                                                        class="list-inline-item bg-google text-white border-0 rounded-3"><i
                                                            class="bx bxl-google"></i></a>
                                                    
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

        <script src="<?php echo e(asset('static/backend/js/bootstrap.bundle.min.js')); ?>"></script>

        <!--plugins-->

        <script src="<?php echo e(asset('static/backend/js/jquery.min.js')); ?>"></script>

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

        <script src="<?php echo e(asset('static/backend/js/app.js')); ?>"></script>

    </body>

    </html>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('welcome', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/customer-panel/customer_login.blade.php ENDPATH**/ ?>