<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" id="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', 'Payorio Payment')); ?></title>

    <link href="<?php echo e(asset('static/backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />



    <style>


         /* Disables all mouse interactions */

        body {
            font-size: 14px;
            color: #4c5258;
            letter-spacing: .5px;
            background: #f4f4f4;
            overflow-x: hidden;
            font-family: Roboto, sans-serif;

        }

        .wallet-wrap {
            padding-bottom: 1.5rem;
            margin: 5px auto;
        }

        .logo-wrap {
            display: grid;
            padding-bottom: 1.5rem;
            gap: 1.25rem;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            margin: 50px auto;
        }

        .bank-img {
            margin-left: auto;
            margin-right: auto;
            height: 70%;
            width: 100%;
            /* -o-object-fit: cover; */
            object-fit: contain;
            transition-property: all;
            transition-timing-function: cubic-bezier(.4, 0, .2, 1);
            transition-duration: 300ms;
        }

        .bank-img-div {
            border-radius: 0.375rem;
            display: flex;
            height: 4rem;
            justify-content: center;
            overflow: hidden;
            padding: 1px;
            border: 1px solid #ccc;
            align-items: center;
        }

        /* Styling for the loader */
        .loader {
            border: 4px solid #f3f3f3;
            /* Light grey */
            border-top: 4px solid #3498db;
            /* Blue */
            border-radius: 50%;
            width: 100px;
            height: 100px;
            animation: spin .5s linear infinite;
            display: none;
            /* Hide the loader by default */
            margin: 0 auto;
            margin-bottom: 16px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>


    <?php
        $app_name = app_config('AppName');
        $support_number = app_config('support_whatsapp_number');
        $image = app_config('AppLogo');
        $wallet_status = app_config('wallet_payment_status');
        $onlineCheckingTime = env('PAYMENT_TIME');
    ?>



    <!-- Your content that will be populated by AJAX response -->
    <div id="content"></div>


    <div class="container">

        <div class="row row-cols-xl-2 my-5">
            <div class="col mx-auto">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="d-flex justify-content-end">
                            <a href="https://wa.me/<?php echo e($support_number); ?>" target="_blank" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Chat with us on WhatsApp">
                                <img src="<?php echo e(asset('headset copy.png')); ?>" width="80" alt="WhatsApp">
                            </a>

                         </div>
                        <div class="p-4">

                            

                            <div class="mb-4 text-center">
                                <img src="<?php echo e(asset('storage/' . $image)); ?>" width="150" alt="" class="d-block mx-auto mb-3">
                                <div style="white-space: nowrap;">
                                    <h2 class="d-inline text-primary mt-4"><?php echo e($app_name); ?> Payment System</h2>
                                </div>
                                <input type="hidden" name="wallet_status" value="<?php echo e($wallet_status); ?>" id="wallet_status">
                            </div>

                            <div class="col-9 mx-auto text-center">
                                <div class="row justify-content-center">
                                    <?php if($wallet_status == 'true'): ?>
                                        <div class="w-50 p-3 bg-primary text-white fw-bold payment-switcher"
                                            attr-value="wallet" style="background-color: #eee; cursor:pointer;">Wallet
                                            Money
                                        </div>
                                    <?php endif; ?>
                                    <div class="w-<?php echo e($wallet_status == 'true' ? 50 : 100); ?> p-3 text-black fw-bold payment-switcher"
                                        attr-value="mobile-banking" style="background-color: #eee; cursor: pointer">
                                        Mobile Banking
                                    </div>
                                </div>
                            </div>
                            <!--<div class="wallet-wrap" id="wallet-div">-->

                            <!--    <div class="form-group">-->
                            <!--        <div id="loader" class="loader"></div>-->

                            <!--        <input type="text" name="email_or_phone" id="mobile_input" required-->
                            <!--            class="form-control" autofocus placeholder="Enter Payorio Ac. Mobile or Email"-->
                            <!--            style="margin: 50px auto;height: 45px;" autocomplete="off">-->

                            <!--        <input type="number" name="email_or_phone" id="otp_input" minlength="6"-->
                            <!--            maxlength="6" class="form-control d-none" placeholder="Enter 6 digit otp"-->
                            <!--            style="margin: 50px auto;height: 45px;" autocomplete="off">-->

                            <!--        <input type="hidden" value="<?php echo e($payment_request->request_id); ?>"-->
                            <!--            name="request_id_input" id="request_id_input" autocomplete="off">-->

                            <!--        <button type="button" class="btn btn-primary d-block w-100 mt-4"-->
                            <!--            id="wallet-proceed">-->
                            <!--            Proceed-->
                            <!--        </button>-->

                            <!--        <button type="button" class="btn btn-primary mt-2 d-block w-100 d-none"-->
                            <!--            id="otp-proceed">-->
                            <!--            Proceed-->
                            <!--        </button>-->

                            <!--        <div class="mt-5">-->
                            <!--            <p class="text-center">If you don't have a account</p>-->
                            <!--            <a class="btn btn-sm d-block fw-bold" style="font-size: 17px; color: #0d6efd"-->
                            <!--                href="https://payorio.com/customer/create-account" target="_blank">Create-->
                            <!--                Account</a>-->
                            <!--        </div>-->
                            <!--    </div>-->

                            <!--</div>-->

                            
                                
                              <input type="hidden" value="<?php echo e($payment_request->request_id); ?>"
                             name="request_id_input" id="request_id_input" autocomplete="off">


                            <div class="logo-wrap d-none col-9 mx-auto" id="mfs-operator-div">
                            <?php $__currentLoopData = getOpNameList(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                            <?php
                                if($item == 'bkash'){
                                    $imagePath = 'payments/bkash.png';
                                }else if($item == 'nagad'){
                                    $imagePath = 'payments/nagad.png';
                                }else if($item == 'rocket'){
                                    $imagePath = 'payments/racket.png';
                                }
                            ?>

                                    <a href="<?php echo e(url("/checkout/payment/$payment_request->request_id/" . $item)); ?>" class="bank-img-div">
                                        <img src="<?php echo e(asset($imagePath)); ?>" class="bank-img" />
                                    </a>

                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


                            </div>

                            <div class="col-12   d-flex justify-content-center">
                                <button type="submit" class="btn"
                                    style="background: #D8DCFF; width: 80%; color: #0019FE; padding: 5px 10px; font-weight: bold; font-size: 2em">
                                    Pay <?php echo e(number_format($payment_request->amount, 2)); ?> ৳
                                </button>
                            </div>

                            <div class="col-12 mt-4 d-flex justify-content-center">
                                <button type="button" class="btn" onClick="submitCancel('<?php echo e($payment_request->request_id); ?>')"
                                    style="background: #f60303; width: 80%; color: #f2f2f9; padding: 5px 10px; font-weight: bold; font-size: 2em">
                                    Cancel
                                </button>
                            </div>

                            <?php
                            $paymentTime = env('PAGE_TIME')
                            ?>

                            <div class="col-12">
                                <div class="d-grid">
                                    <p class="fw-bold text-center"><span data-duration="<?php echo e($paymentTime); ?>" id="timer"></span></p>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="<?php echo e(asset('static/backend/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('static/backend/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        toastr.options.closeButton = true;
        toastr.options.showMethod = 'slideDown';
        toastr.options.closeMethod = 'slideUp';
        toastr.options.progressBar = true;
        // Function to show the loader
        function showLoader() {
            $("#loader").show();
        }

        // Function to hide the loader
        function hideLoader() {
            $("#loader").hide();
        }


        $(document).ready(function() {

            $('#submitLink').on('click', function(e) {
                e.preventDefault(); // Prevent default link behavior
                $('#submitFormButton').click(); // Trigger the hidden submit button
            });

            let get_wallet_status = $('#wallet_status').val();
            let check = 1;


            $(document).on('click', '.payment-switcher', function() {

                if (check == 1) {
                    $(this).addClass('bg-primary').addClass('text-white');
                    $(this).siblings().removeClass('bg-primary')
                        .addClass('text-black')
                        .removeClass('text-white');
                    let data = $(this).attr('attr-value');


                    if (data == 'wallet') {
                        $('.wallet-wrap').removeClass('d-none');
                        $('.logo-wrap').addClass('d-none');

                    } else if (data == 'mobile-banking') {

                        $('.wallet-wrap').addClass('d-none');
                        $('.logo-wrap').removeClass('d-none');
                    }
                    if (get_wallet_status == 'false') {
                        check = 2;

                    }
                } else {
                    return;
                }
            })

            function make_active() {
                if (get_wallet_status == true) {
                    $('.payment-switcher[attr-value="wallet"]').trigger('click');
                } else {
                    $('.payment-switcher[attr-value="mobile-banking"]').trigger('click');
                    // $('.wallet-wrap').addClass('d-none');
                    // $('.logo-wrap').removeClass('d-none');
                }
            }

            make_active();





            $(document).on('click', '#wallet-proceed', function() {
                let verifierInput = $('#mobile_input').val();
                if (verifierInput == '') {
                    toastr.warning('Please insert email or phone number of your wallet');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showLoader(); // Show the loader before the AJAX request starts
                    },
                    url: '<?php echo e(url('checkout/payment/otp-send')); ?>', // Specify the URL of the server-side script or API endpoint
                    type: 'POST',
                    data: {
                        'input_value': verifierInput
                    },
                    success: function(response) {
                        hideLoader(); // Hide the loader after the AJAX request is completed
                        if (response.success === false) {
                            $.each(response.error, function(index, errorValue) {
                                toastr.warning(errorValue);
                            });
                        } else if (response.success === true) {
                            $.each(response.message, function(index, successValue) {
                                $('#mobile_input').addClass('d-none');
                                $('#otp_input').removeClass('d-none');
                                $('#wallet-proceed').addClass('d-none');
                                $('#otp-proceed').text('Verify Otp').removeClass(
                                    'd-none');
                                toastr.success(successValue);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                        hideLoader(); // Hide the loader even in case of an error

                    }
                });
            })

            $(document).on('click', '#otp-proceed', function() {
                let otpInput = $('#otp_input').val();
                let mobile = $('#mobile_input').val();
                let request_id = $('#request_id_input').val();
                if (otpInput == '') {
                    toastr.warning('Otp should not be empty');
                    return;
                }

                if (otpInput.length < 6 || otpInput.length > 6) {
                    toastr.warning('Otp should be 6 characters long');
                    return;
                }

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        showLoader(); // Show the loader before the AJAX request starts
                    },
                    url: '<?php echo e(url('checkout/payment/otp-verify')); ?>', // Specify the URL of the server-side script or API endpoint
                    type: 'POST',
                    data: {
                        'otp_value': otpInput,
                        'input_value': mobile,
                        'request_id': request_id,
                    },
                    success: function(response) {

                        if (response.success === false) {
                            hideLoader(); // Hide the loader after the AJAX request is completed
                            $.each(response.error, function(index, errorValue) {
                                toastr.warning(errorValue);
                            });
                        } else if (response.success == true && response.hasOwnProperty('url')) {
                            $(this).attr('disabled');
                            $.each(response.message, function(index, successValue) {
                                toastr.success(successValue);
                            });
                            hideLoader(); // Hide the loader after the AJAX request is completed
                            setTimeout(function(e) {
                                window.location.href = response.url;
                            }, 1000);
                        } else if (response.success === true) {
                            toastr.success(response);

                            $.each(response.message, function(index, successValue) {
                                toastr.success(successValue);
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        toastr.error(error);
                        hideLoader(); // Hide the loader after the AJAX request is completed
                    }
                });
            })

        })

        $(document).ready(function() {
            var timer = $('#timer');
            var req_id = document.getElementById("request_id_input").value;
            // console.log(req_id)


            var duration = timer.data('duration');
            let flag = true;

            var interval = setInterval(function() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timer.css("color", "red").text("Warning! This page will expired after " + minutes + ":" + seconds );


                if (--duration < 0) {
                    if(flag == true){
                      flag = false;

                    }

                    canceledRequest(req_id);


                }

            }, 1000);
        });


        function submitCancel(req_id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait a moment.',
                allowOutsideClick: false,
                allowEscapeKey: false,
                allowEnterKey: false,
                didOpen: () => {
                    Swal.showLoading(); // Show the loading indicator
                }
            });

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '<?php echo e(url('/checkout/payment/cancelled')); ?>',
                type: 'POST',
                data: {
                    'request_id': req_id,
                },
                success: function(response) {

                    Swal.close();
                    if (response.success === true) {

                        Swal.fire({
                            title: 'Completed!',
                            text: 'Your operation was successful.',
                            icon: 'success'
                        });

                        window.location.assign(response.url);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(error);
                    Swal.close(); // Ensure the loader is hidden on error
                }
            });
        }
    });
}

function canceledRequest(req_id) {
    return $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '<?php echo e(url('/checkout/payment/cancelled')); ?>',
        type: 'POST',
        data: {
            'request_id': req_id
        },
        success: function(response) {
            console.log(response);

            if (response.success === false) {
                $.each(response.error, function(index, errorValue) {
                    toastr.warning(errorValue);
                });
                return; // Exit function if there's an error
            }

            if (response.success === true) {
                toastr.success(response);
                window.location.assign(response.url);
            } else {
                console.log(response); // Fallback logging for unexpected cases
            }
        },
        error: function(xhr, status, error) {
            toastr.error(error);
            hideLoader(); // Hide the loader after the AJAX request is completed
        }
    });
}


    </script>


    

    

    

</body>

</html>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/payments/select-method.blade.php ENDPATH**/ ?>