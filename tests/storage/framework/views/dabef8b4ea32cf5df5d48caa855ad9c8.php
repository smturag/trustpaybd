<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($app_name); ?> Payment</title>

    <link href="<?php echo e(asset('static/backend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" />
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

    <style>
        body {
            font-size: 14px;
            color: #4c5258;
            letter-spacing: .5px;
            background: #f4f4f4;
            overflow-x: hidden;
            font-family: Roboto, sans-serif;
        }

        .logo-wrap {
            display: grid;
            padding-bottom: 1.5rem;
            gap: 1.15rem;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            margin: 50px auto;
        }

        .bank-img {
            margin-left: auto;
            margin-right: auto;
            height: 70%;
            width: 100%;
            -o-object-fit: cover;
            object-fit: cover;
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

        .number_block {
            padding: 10px;
            background: #f7f7f7;
            color: #000;
            width: 80%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto;
            /* Centering the block within its parent */
        }

        @media (max-width: 480px) {
            .number_block {
                padding-right: 5px;
                padding-left: 5px;
                padding-top: 10px;
                padding-bottom: 10px;
                background: #f7f7f7;
                color: #000;
                width: 100%;
                display: flex;
                justify-content: center;
                align-items: center;
                margin: 0 auto;
                /* Centering the block within its parent */
            }
        }

        .transaction_input {
            border: 2px solid #0d6efd;
            border-radius: 0px;
            height: 47px;
            font-weight: 700;
            margin-bottom: 12px;

        }

        #copy {
            margin-left: 23px;
            cursor: pointer;
        }

        .copy_span i {
            color: #cbb7b7;
        }

        /*      .modal {
                  display:    none;
                  position:   fixed;
                  z-index:    1000;
                  top:        0;
                  left:       0;
                  height:     100%;
                  width:      100%;
                  background: rgba( 255, 255, 255, .8 )
                  url('https://i.stack.imgur.com/FhHRx.gif')
                  50% 50%
                  no-repeat;
              }*/

        /* When the body has the loading class, we turn
           the scrollbar off with overflow:hidden */
        body.loading .modal {
            overflow: hidden;
        }

        /* Anytime the body has the loading class, our
           modal element will be visible */
        body.loading .modal {
            display: block;
        }

        #copyAmount {
            margin-left: 15px;
        }


    </style>

<style>
    .btn-success {
        background-color: #28a745 !important; /* Green color */
        color: white !important;
    }

    .btn-red {
        background-color: #dc3545 !important; /* Red color */
        color: white !important;
    }
</style>


<style>

.input-group {
    display: flex;
    align-items: center;
    position: relative;
}

#trxid {
    flex-grow: 1;
    padding-right: 50px; /* Adjust padding to make space for the icon and text */
}

#paste-icon {
    position: absolute;
    right: 5px;
    top: 40%;
    transform: translateY(-50%);
    cursor: pointer;
    color: #555;
    display: flex;
    flex-direction: column;
    align-items: center;
    font-size: 14px; /* Smaller icon size */
}

.paste-text {
    font-size: 9px; /* Smaller font size for the text */
    margin-top: 2px;
    color: #555;
}


</style>
</head>

<body>

    <div class="container">
        <div class="row row-cols-xl-2 mt-5">
            <div class="col mx-auto">
                <div class="card mb-0">
                    <div class="card-body">
                        <div class="p-2 md:p-4">
                            <div class="mb-4 text-center">
                                <img src="<?php echo e(asset('storage/' . $image)); ?>" width="120" height="30" alt="">
                                <!--<h2 class="text-primary"><?php echo e($app_name); ?> Payment System</h2>-->
                            </div>

                            

                            <div class="col-12 d-flex justify-content-center">
                                <div class="number_block">
                                    <h3 class="fw-bold text-center"><span
                                            id="text"><?php echo e($paymentMethod->sim_number); ?></span>
                                        <span id="copy" title="click to copy">
                                            <i class="fa fa-copy"></i>
                                        </span>
                                    </h3>
                                </div>
                            </div>

                            <div class="col-12 mt-1">
                                <div class="d-grid">
                                    <p class="fw-bold text-center">এই <span class="text-capitalize"> <strong
                                                class="text-danger"> <?php echo e($operator_name); ?>

                                               Agent </strong> </span> নাম্বারে ক্যাশ আউট করুন</p>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="d-grid">
                                    <h2 class="fw-bold text-center number_block text-success">
                                        <b> ৳<?php echo e(number_format($payment_request->amount, 0)); ?> </b>
                                        <span id="copyAmount" title="click to copy"><i class="fa fa-copy"></i></span>
                                    </h2>

                                </div>
                            </div>


                            <div class="col-12 mt-2">
                                <div class="d-grid">
                                    <p class="fw-bold text-center text-danger">5 মিনিটের মধ্যে ক্যাশ আউট সম্পন্ন করুন</p>
                                </div>
                            </div>

                            <form action="<?php echo e(url('checkout/payment/payment_save')); ?>" method="post" id="payment_form">
                                <?php echo csrf_field(); ?> <?php echo method_field('post'); ?>
                                <div class="col-12 d-flex justify-content-center">
                                    <div class="number_block">
                                        

                                            <div class="input-group">
                                                <input type="text" name="trxid" required class="transaction_input form-control" id="trxid" placeholder="Enter transaction ID" autocomplete="off">
                                                <span class="input-group-text" id="paste-icon">
                                                    <i class="fa fa-paste"></i>
                                                    <span class="paste-text">Paste</span>
                                                </span>
                                            </div>
                                        <?php if($errors->any()): ?>
                                            <div class="text text-danger">
                                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <p><?php echo e($error); ?></p>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="col-12" id="validationDiv" style="display: none">
                                    <div class="d-grid">
                                        <div class="number_block">
                                            <p class="fw-bold text-center text-danger"> একটি ভূল Trx ID দিয়েছেন,Trx ID চেক করে পুনরায় চেষ্টা করুন । </p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="d-grid">
                                        <div class="number_block">
                                            <p class="fw-bold text-center">ক্যাশ আউট করার পরে যে Trx ID নাম্বাটি পেয়েছেন, সেই Trx ID নাম্বারটি লিখুন </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 mt-3">
                                    <div class="d-grid">
                                        <input type="hidden" value="<?php echo e($paymentMethod->sim_number); ?>" name="sim_id">
                                        <input type="hidden" value="<?php echo e($payment_request->request_id); ?>"
                                            id="request_id_input" name="request_id">
                                        <input type="hidden" value="<?php echo e($operator_name); ?>"
                                            name="payment_method">
                                        <input type="hidden" name="amount" value="<?php echo e($payment_request->amount); ?>">
                                        <div class="col-12 d-flex justify-content-center">
                                            <button type="button" id="verify_btn"
                                                style=" width: 80%; padding: 5px 10px; font-weight: bold; font-size: 2em"
                                                class="btn btn-primary">Verify</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="col-12 mt-4 d-flex justify-content-center">
                                <button type="button" class="btn"
                                    onClick="submitCancel('<?php echo e($payment_request->request_id); ?>')"
                                    style="background: #f60303; width: 80%; color: #f2f2f9; padding: 5px 10px; font-weight: bold; font-size: 2em">
                                    Cancel
                                </button>
                            </div>

                            <?php
                                $paymentTime = env('PAGE_TIME');
                            ?>
                            <div class="col-12">
                                <div class="d-grid">
                                    <p class="fw-bold text-center"><span data-duration="<?php echo e($paymentTime); ?>"
                                            id="timer"></span></p>
                                    
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="paymentVerificationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="alert alert-danger">
                        Please don't close the Browser until verification complete. It will be automatically closed and
                        redirected.
                    </div>
                </div>
                <div class="modal-body">
                    <div class="loader">
                        <img style="width: 100px;
                                height: 100px;
                                margin: 0 auto;
                                display: block;"
                            src="<?php echo e(asset('images/loader-payment-request.gif')); ?>" alt="">
                    </div>

                    <p style="text-align: center;font-size: 18px;margin-top: 50px;" id="modal-message">Please wait for
                        confirmation</p>
                    <p style="text-align: center;font-size: 14px;margin-top: 50px;" id="popup-timer"></p>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('static/backend/js/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('static/backend/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js')); ?>"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        const textElement = document.getElementById("text");
        const copyButton = document.getElementById("copy");
        const copyAmountButton = document.getElementById("copyAmount");
        var req_id = document.getElementById("request_id_input").value;
        toastr.options.closeButton = true;
        toastr.options.showMethod = 'slideDown';
        toastr.options.closeMethod = 'slideUp';
        toastr.options.progressBar = true;
        var submitAction = false;
        let countBm = 0;

        let validationDiv = document.getElementById('validationDiv')

        const copyText = (e) => {
            window.getSelection().selectAllChildren(textElement);
            document.execCommand("copy");
            toastr.info("Copied successfully ✅");
        };

        const resetTooltip = (e) => {
            e.target.setAttribute("tooltip", "Copy to clipboard");
        };

        copyButton.addEventListener("click", (e) => copyText(e));
        copyButton.addEventListener("mouseover", (e) => resetTooltip(e));

        copyAmountButton.addEventListener("click", (e) => copyText(e));
        copyAmountButton.addEventListener("mouseover", (e) => resetTooltip(e));

        $(document).on('click', '#verify_btn', async function(e) {

            e.preventDefault();

            let transaction_trx = $('.transaction_input ').val();

            if (transaction_trx == '') {
                toastr.warning('Transaction ID should not be empty');
                return false;
            }


            try {

                $getResponse = await checkTransactionIdBM(transaction_trx);

                if ($getResponse != true) {
                    playBeep();
                    validationDiv.style.display = 'block';
                    hideValidationDiv()
                    if (countBm < 1) {
                        countBm++;
                        return false;
                    }
                }

                if (transaction_trx.length < 8 || transaction_trx.length > 10) {
                    toastr.warning('Transaction ID should be between 8 to 10 characters');
                    return false;
                }



                // if (transaction_trx.length < 5) {
                //     toastr.warning('Transaction ID should be at least 5 characters');
                //     return false;
                // }

                if (!/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/.test(transaction_trx)) {
                    toastr.warning('Invalid Transaction ID');
                    return false;
                }


                var modal = $("#paymentVerificationModal");
                $(modal).modal({
                    backdrop: 'static',
                    keyboard: false
                });

                history.pushState(null, null, document.URL);
                window.addEventListener('popstate', function() {
                    history.pushState(null, null, document.URL);
                });

                document.addEventListener("keydown", function(event) {
                    if (event.key === "F5" || (event.ctrlKey && event.key === "r")) {
                        event.preventDefault();
                    }
                });


                Swal.fire({
                    title: "Confirmation page",
                    html: '<span style="color: red;">আপনি কি নিশ্চিত যে আপনি একটি সঠিক Trx ID দিয়েছেন? যদি ভুল দিয়ে থাকেন তাহলে আপনার লেনদেন টি বাতিল করা হবে </span>',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',   // Text for the cancel button
                    dangerMode: true, // This adds a danger color to the confirm button
                    confirmButtonText: 'Confirm',
                    customClass: {
                        confirmButton: 'btn-success',
                        cancelButton: 'btn-red'
                    },
                    // buttons: {
                    //     cancel: true,
                    //     confirm: 'Confirm',
                    // },
                    dangerMode: true,
                }).then((result) => {
                    if (result.isConfirmed) {
                        modal.modal('show');
                        //modal popup timer
                        var popup_timer = $('#popup-timer');
                        $('#timer').addClass('d-none');

                        var duration = 300;

                        setInterval(function() {
                            let minutes = Math.floor(duration / 60);
                            let seconds = duration % 60;

                            minutes = minutes < 10 ? "0" + minutes : minutes;
                            seconds = seconds < 10 ? "0" + seconds : seconds;

                            popup_timer.text("Redirecting after " + minutes + ":" + seconds);

                            // if (duration < 0) {
                            if (--duration < 0) {
                                clearInterval(interval);
                            }

                        }, 1000);
                        setInterval(function(interval) {
                            paymentVerification(interval);
                        }, 10000);
                    }
                });

            } catch (error) {
                console.error('An error occurred:', error);
            }


        });

        function playBeep() {
            const context = new (window.AudioContext || window.webkitAudioContext)();
            const oscillator = context.createOscillator();
            const gainNode = context.createGain();

            oscillator.type = 'square'; // Type of wave: sine, square, triangle, sawtooth
            oscillator.frequency.setValueAtTime(440, context.currentTime); // Frequency in Hz (440Hz is the standard A note)
            oscillator.connect(gainNode);
            gainNode.connect(context.destination);

            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, context.currentTime + 0.1); // Short beep (0.1 seconds)
            oscillator.stop(context.currentTime + 0.3); // Stop oscillator after 0.1 seconds
        }


                    function hideValidationDiv() {
                setTimeout(() => {
                    validationDiv.style.display = 'none';

                }, 10000); // 5,000 milliseconds = 5 seconds
            }

        async function checkTransactionIdBM(trxid) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/check-exist-bm', // Adjust the URL to your route
                    type: 'POST',
                    data: {
                        trxid: trxid
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success === true) {
                            resolve(true);
                        } else {
                            resolve(false);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        reject(error);
                    }
                });
            });
        }


        function paymentVerification() {



            let actionUrl = $('#payment_form').attr('action');
            $.ajax({
                url: actionUrl, // Specify the URL of the server-side script or API endpoint
                type: 'POST', // Set the HTTP method to POST
                data: $('#payment_form').serialize(),
                success: function(response) {
                    console.log(response);

                    if (response.success == false && response.message == 'No transaction found') {
                        $(this).attr('disabled');
                        toastr.error(response.message);
                        $('#modal-message').html(response.message);
                        setTimeout(function(e) {
                            window.location.reload();
                        }, 1000);

                    } else if (response.success == false && response.message ==
                        'Transaction Pending') {
                        //toastr.warning(response.message);
                        $('#modal-message').html(response.message);
                    } else if (response.success == true && response.hasOwnProperty('url')) {
                        $(this).attr('disabled');
                        toastr.success(response.message);
                        $('#modal-message').html(response.message);
                        setTimeout(function(e) {
                            window.location.href = response.url;
                        }, 2000);
                    } else if (response.success == false && response.message.includes(
                            'Transaction Already')) {
                        //toastr.error(response.message);
                        $('#modal-message').html(response.message);
                        canceledRequest(req_id);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle the error here
                    //toastr.error(error);
                    console.log("XHR object:", xhr.responseText); // Full XHR object
                    console.log("Status:", status); // Status (e.g., 'error', 'timeout')
                    console.log("Error:", error);
                    //$(this).removeAttr('disabled');
                }
            });
        }


        $(document).ready(function() {
            var timer = $('#timer');

            var duration = timer.data('duration');

            var interval = setInterval(function() {
                let minutes = Math.floor(duration / 60);
                let seconds = duration % 60;

                minutes = minutes < 10 ? "0" + minutes : minutes;
                seconds = seconds < 10 ? "0" + seconds : seconds;

                timer.css("color", "red").text("Warning! This page will expired after " +
                    minutes + ":" +
                    seconds);
                if (--duration < 0) {

                    canceledRequest(req_id);


                    // window.location.reload();
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
                            'request_id': req_id
                        },
                        success: function(response) {

                            // Close the processing modal and show success message
                            Swal.close();
                            if (response.success === true) {
                                1
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


        // function checkTransactionIdBM(trxid) {
        //     $.ajax({
        //         url: '/check-exist-bm', // Adjust the URL to your route
        //         type: 'POST',
        //         data: {
        //             trxid: trxid
        //         },
        //         headers: {
        //             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
        //                 'content') // Include CSRF token if using Laravel
        //         },
        //         success: function(response) {

        //             console.log(response);

        //             if (response.success == true) {
        //                 return true;

        //             } else {

        //                 return false
        //             }
        //         },
        //         error: function(xhr, status, error) {
        //             console.error('Error:', error);
        //             // $('#verify_btn').prop('disabled', true);
        //         }
        //     });
        // }



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

        // $('#verify_btn').prop('disabled', true);


        $(document).ready(function() {
            // Function to check if the transaction ID exists
            function checkTransactionId(trxid) {
                $.ajax({
                    url: '/check-transaction', // Adjust the URL to your route
                    type: 'POST',
                    data: {
                        trxid: trxid
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content') // Include CSRF token if using Laravel
                    },
                    success: function(response) {

                        if (response.success == false) {
                            // Transaction ID exists
                            // $('#verify_btn').prop('disabled', false);
                        } else {
                            toastr.error("Duplicate Transaction ID can't be used.");
                            // Transaction ID does not exist
                            //   $('#verify_btn').prop('disabled', true);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        // $('#verify_btn').prop('disabled', true);
                    }
                });
            }

            // Event handler for input field change
            $('#trxid').on('input', function() {
                countBm = 0;
                let trxid = $(this).val();
                trxid = trxid.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

                $(this).val(trxid);

                // Check if the input length is at least 5 after modifications
                if (trxid.length >= 5) {
                    console.log(trxid);
                    //checkTransactionId(trxid);
                } else {
                    // Logic when length is less than 5 (if needed)
                }
            });

        })

        document.getElementById('paste-icon').addEventListener('click', function() {
    navigator.clipboard.readText().then(function(text) {
        document.getElementById('trxid').value = text;
    }).catch(function(err) {
        console.error('Failed to read clipboard contents: ', err);
    });
})
    </script>



    <!--<script src="<?php echo e(asset('static/backend/js/validation.js')); ?>"></script>-->
    <div class="modal"><!-- Place at bottom of page --></div>
</body>

</html>
<?php /**PATH F:\Rahim Vai\Gateway\payment-gateway\resources\views/merchant/payments/transaction-input.blade.php ENDPATH**/ ?>