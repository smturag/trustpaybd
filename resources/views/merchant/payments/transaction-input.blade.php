<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $app_name }} Payment</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .copy-btn:hover {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }

        .pulse-animation {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        .floating-card {
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .btn-gradient {
            background: linear-gradient(45deg, #667eea, #764ba2);
            transition: all 0.3s ease;
        }

        .btn-gradient:hover {
            background: linear-gradient(45deg, #764ba2, #667eea);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .btn-danger-gradient {
            background: linear-gradient(45deg, #ff6b6b, #ee5a52);
            transition: all 0.3s ease;
        }

        .btn-danger-gradient:hover {
            background: linear-gradient(45deg, #ee5a52, #ff6b6b);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(255, 107, 107, 0.3);
        }

        .input-focus {
            transition: all 0.3s ease;
        }

        .input-focus:focus {
            transform: scale(1.02);
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .number-display {
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .amount-display {
            background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
            color: white;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

        .btn-success {
            background-color: #28a745 !important;
            color: white !important;
        }

        .btn-red {
            background-color: #dc3545 !important;
            color: white !important;
        }
    </style>
</head>

<body class="gradient-bg min-h-screen">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="w-full max-w-md">
            <!-- Main Card -->
            <div class="glass-effect rounded-2xl shadow-2xl p-4 floating-card">

                <!-- Logo Section -->
                <div class="text-center mb-3">
                    <div class="bg-white rounded-lg p-2 inline-block shadow-md mb-2">
                        <img src="{{ asset('storage/' . $image) }}" class="h-8 w-auto mx-auto"
                            alt="{{ $app_name }}">
                    </div>
                    <h1 class="text-lg font-bold text-gray-800">{{ $app_name }} Payment</h1>
                    <p class="text-gray-600 text-xs">Secure Transaction Portal</p>
                </div>

                <!-- Agent Number Section -->
                <div class="mb-3">
                    <div class="number-display rounded-lg p-3 text-center shadow-md">
                        <p class="text-xs font-medium opacity-90">
                            @if ($type === 'P2A')
                                Agent
                            @elseif ($type === 'P2P')
                                Partner
                            @else
                                Merchant
                            @endif Number
                        </p>
                        <div class="flex items-center justify-center space-x-2 mt-1">
                            <span class="text-lg font-bold tracking-wider" id="text">{{ $number }}</span>
                            <button id="copy"
                                class="copy-btn bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-all duration-200"
                                title="Copy number">
                                <i class="fas fa-copy text-white text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Instructions -->
                <div class="text-center mb-3">
                    <div
                        class="bg-gradient-to-r from-orange-100 to-red-100 rounded-md p-2 border-l-4 border-orange-500">
                        <p class="text-gray-800 font-medium text-xs">
                            এই
                            <span class="text-orange-600 font-bold">
                                {{ $operator_name }}
                                @if ($type === 'P2A')
                                    Agent
                                @elseif($type === 'P2P')
                                    Partner
                                @else
                                    Merchant
                                @endif
                            </span>
                            নাম্বারে
                            @if ($type === 'P2A')
                                ক্যাশ আউট করুন
                            @elseif($type === 'P2P')
                                সেন্ড মানি করুন
                            @else
                                পেমেন্ট করুন
                            @endif
                        </p>

                    </div>
                </div>

                <!-- Amount Section -->
                <div class="mb-3">
                    <div class="amount-display rounded-lg p-3 text-center shadow-md">
                       <p class="text-xs font-medium opacity-90">
    Amount to
    @if ($type === 'P2A')
        Cash Out
    @elseif ($type === 'P2P')
        Send Money
    @else
        Payment
    @endif
</p>

                        <div class="flex items-center justify-center space-x-2 mt-1">
                            <span class="text-xl font-bold">৳{{ number_format($payment_request->amount, 0) }}</span>
                            <button id="copyAmount"
                                class="copy-btn bg-white bg-opacity-20 hover:bg-opacity-30 rounded-full p-1 transition-all duration-200"
                                title="Copy amount">
                                <i class="fas fa-copy text-white text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Warning -->
                <div class="mb-3">
                    <div class="bg-red-50 border border-red-200 rounded-md p-2 text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-clock text-red-500 mr-1 text-xs"></i>
                            <span class="text-red-700 font-semibold text-xs">
    ৫ মিনিটের মধ্যে
    @if ($type === 'P2A')
        ক্যাশ আউট
    @elseif ($type === 'P2P')
        সেন্ড মানি
    @else
        পেমেন্ট
    @endif
    সম্পন্ন করুন
</span>

                        </div>
                    </div>
                </div>

                <!-- Transaction Form -->
                <form action="{{ url('checkout/payment/payment_save') }}" method="post" id="payment_form"
                    class="space-y-3">
                    @csrf @method('post')

                    <!-- Transaction ID Input -->
                    <div class="space-y-2">
                        <div class="relative">
                            <label for="trxid" class="block text-xs font-medium text-gray-700 mb-1">
                                <i class="fas fa-receipt mr-1 text-blue-500 text-xs"></i>
                                Transaction ID
                            </label>
                            <div class="relative">
                                <input type="text" name="trxid" required
                                    class="input-focus w-full px-3 py-2 border-2 border-gray-200 rounded-md focus:border-blue-500 focus:outline-none text-sm font-semibold tracking-wider uppercase"
                                    id="trxid" placeholder="Enter Transaction ID" autocomplete="off">
                                <button type="button" id="paste-icon"
                                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-blue-100 hover:bg-blue-200 text-blue-600 px-2 py-1 rounded-sm transition-all duration-200 flex items-center space-x-1">
                                    <i class="fas fa-paste text-xs"></i>
                                    <span class="text-xs font-medium">Paste</span>
                                </button>
                            </div>
                        </div>

                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 rounded-md p-2">
                                <div class="flex items-center mb-1">
                                    <i class="fas fa-exclamation-triangle text-red-500 mr-1 text-xs"></i>
                                    <span class="text-red-700 font-semibold text-xs">Validation Errors</span>
                                </div>
                                @foreach ($errors->all() as $error)
                                    <p class="text-red-600 text-xs">{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Validation Error Message -->
                    <div id="validationDiv" class="hidden">
                        <div class="bg-red-50 border border-red-200 rounded-md p-2 text-center">
                            <i class="fas fa-times-circle text-red-500 text-sm"></i>
                            <p class="text-red-700 font-semibold text-xs mt-1">একটি ভূল Trx ID দিয়েছেন, Trx ID চেক করে
                                পুনরায় চেষ্টা করুন।</p>
                        </div>
                    </div>

                    <!-- Instructions -->
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-2 text-center">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-info-circle text-blue-500 mr-1 text-xs"></i>
                            <span class="text-blue-700 font-semibold text-xs">
    @if ($type === 'P2A')
        ক্যাশ আউট
    @elseif ($type === 'P2P')
        সেন্ড মানি
    @else
        পেমেন্ট
    @endif
    করার পরে যে Trx ID নাম্বারটি পেয়েছেন, সেই Trx ID নাম্বারটি লিখুন
</span>

                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" value="{{ $number }}" name="sim_id">
                    <input type="hidden" value="{{ $payment_request->request_id }}" id="request_id_input"
                        name="request_id">
                    <input type="hidden" value="{{ $operator_name }}" name="payment_method">
                    <input type="hidden" name="amount" value="{{ $payment_request->amount }}">
                    <input type="hidden" name="type" value="{{ $type }}">


                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <button type="button" id="verify_btn"
                            class="btn-gradient w-full py-2.5 text-white font-bold text-base rounded-md shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center space-x-2">
                            <i class="fas fa-check-circle text-sm"></i>
                            <span>Verify Transaction</span>
                        </button>

                        <button type="button" onClick="submitCancel('{{ $payment_request->request_id }}')"
                            class="btn-danger-gradient w-full py-2.5 text-white font-bold text-base rounded-md shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center space-x-2">
                            <i class="fas fa-times-circle text-sm"></i>
                            <span>Cancel Transaction</span>
                        </button>
                    </div>
                </form>

                <!-- Timer Section -->
                @php
                    $paymentTime = env('PAGE_TIME');
                @endphp
                <div class="mt-3 text-center">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md p-2">
                        <div class="flex items-center justify-center">
                            <i class="fas fa-hourglass-half text-yellow-500 mr-1 text-xs"></i>
                            <p class="text-yellow-600 font-medium text-xs" data-duration="{{ $paymentTime }}"
                                id="timer">Session Timer</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Payment Verification Modal -->
    <div class="fixed inset-0 bg-black bg-opacity-50 hidden z-50" id="paymentVerificationModal">
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-4 rounded-t-2xl">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-exclamation-triangle text-yellow-300"></i>
                        <h3 class="font-bold text-lg">Important Notice</h3>
                    </div>
                    <p class="text-red-100 text-sm mt-2">
                        Please don't close the Browser until verification complete. It will be automatically closed and
                        redirected.
                    </p>
                </div>

                <!-- Modal Body -->
                <div class="p-6 text-center">
                    <!-- Loader -->
                    <div class="mb-6">
                        <div class="inline-flex items-center justify-center w-20 h-20 bg-blue-100 rounded-full mb-4">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                        </div>
                        <!-- Fallback GIF loader -->
                        <img class="w-20 h-20 mx-auto hidden" src="{{ asset('images/loader-payment-request.gif') }}"
                            alt="Loading..." id="gif-loader">
                    </div>

                    <!-- Status Message -->
                    <div class="mb-4">
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Processing Payment</h4>
                        <p class="text-gray-600" id="modal-message">Please wait for confirmation</p>
                    </div>

                    <!-- Timer -->
                    <div class="bg-gray-50 rounded-lg p-3">
                        <p class="text-sm text-gray-600" id="popup-timer">Checking transaction status...</p>
                    </div>

                    <!-- Progress Indicator -->
                    <div class="mt-4">
                        <div class="flex justify-center space-x-2">
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"
                                style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-blue-600 rounded-full animate-bounce"
                                style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('static/backend/js/jquery.min.js') }}"></script>
    <script src="{{ asset('static/backend/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js') }}"></script>
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
        let countPending = 0;

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

            let transaction_trx = $('#trxid').val();

            if (transaction_trx == '') {
                toastr.warning('Transaction ID should not be empty');
                return false;
            }

            try {
                $getResponse = await checkTransactionIdBM(transaction_trx);

                if (transaction_trx.length < 8 || transaction_trx.length > 10) {
                    toastr.warning('Transaction ID should be between 8 to 10 characters');
                    return false;
                }

                // if (!/^(?=.*[0-9])(?=.*[a-zA-Z])[a-zA-Z0-9]+$/.test(transaction_trx)) {
                //   toastr.warning('Invalid Transaction ID');
                //   return false;
                // }

                // Check if transaction ID already exists in payment_requests
                try {
                    const checkResponse = await $.ajax({
                        url: '{{ url("/checkout/payment/check-transaction-id") }}',
                        type: 'POST',
                        data: {
                            trxid: transaction_trx,
                            request_id: req_id
                        },
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    // Handle response from transaction ID check
                    if (checkResponse.success === false) {
                        // Transaction ID already exists and cannot be used
                        Swal.fire({
                            title: 'Transaction ID Already Exists!',
                            text: checkResponse.message,
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                        return false;
                    }
                } catch (error) {
                    console.error('Error checking transaction ID:', error);
                    toastr.error('Failed to validate transaction ID. Please try again.');
                    return false;
                }

                var modal = document.getElementById("paymentVerificationModal");

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
                    html: '<span style="color: red;">আপনি কি নিশ্চিত যে আপনি একটি সঠিক Trx ID দিয়েছেন? যদি ভুল দিয়ে থাকেন তাহলে আপনার লেনদেন টি বাতিল করা হবে </span>',
                    icon: "warning",
                    showCancelButton: true,
                    cancelButtonText: 'Cancel',
                    dangerMode: true,
                    confirmButtonText: 'Confirm',
                    customClass: {
                        confirmButton: 'btn-success',
                        cancelButton: 'btn-red'
                    },
                    dangerMode: true,
                }).then(async (result) => {
                    if (result.isConfirmed) {
                        // Show the modal
                        modal.classList.remove('hidden');
                        var popup_timer = $('#popup-timer');
                        $('#timer').addClass('hidden');

                        // Submit transaction ID immediately
                        try {
                            const submitResponse = await $.ajax({
                                url: '{{ url("/checkout/payment/submit-transaction-id") }}',
                                type: 'POST',
                                data: $('#payment_form').serialize(),
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                }
                            });

                            if (submitResponse.success === false) {
                                modal.classList.add('hidden');
                                toastr.error(submitResponse.message);
                                return false;
                            }

                            // Transaction ID submitted successfully, start checking status
                            $('#modal-message').html('Transaction ID submitted. Checking status...');
                            
                            var duration = 300;
                            let statusCheckInterval = null;

                            // Timer countdown
                            let timerInterval = setInterval(function() {
                                let minutes = Math.floor(duration / 60);
                                let seconds = duration % 60;

                                minutes = minutes < 10 ? "0" + minutes : minutes;
                                seconds = seconds < 10 ? "0" + seconds : seconds;

                                popup_timer.text("Checking status... " + minutes + ":" + seconds);

                                if (--duration < 0) {
                                    clearInterval(timerInterval);
                                    clearInterval(statusCheckInterval);
                                    toastr.error('Time expired');
                                    window.location.reload();
                                }
                            }, 1000);

                            // Check status every 5 seconds
                            statusCheckInterval = setInterval(function() {
                                checkTransactionStatus(req_id, timerInterval, statusCheckInterval);
                            }, 5000);

                            // Check immediately once
                            checkTransactionStatus(req_id, timerInterval, statusCheckInterval);

                        } catch (error) {
                            modal.classList.add('hidden');
                            console.error('Submit error:', error);
                            toastr.error('Failed to submit transaction ID. Please try again.');
                        }
                    }
                });

            } catch (error) {
                console.error('An error occurred:', error);
            }
        });

        function playBeep() {
            const context = new(window.AudioContext || window.webkitAudioContext)();
            const oscillator = context.createOscillator();
            const gainNode = context.createGain();

            oscillator.type = 'square';
            oscillator.frequency.setValueAtTime(440, context.currentTime);
            oscillator.connect(gainNode);
            gainNode.connect(context.destination);

            oscillator.start();
            gainNode.gain.exponentialRampToValueAtTime(0.00001, context.currentTime + 0.1);
            oscillator.stop(context.currentTime + 0.3);
        }

        function hideValidationDiv() {
            setTimeout(() => {
                validationDiv.classList.add('hidden');
            }, 10000);
        }

        function showValidationDiv() {
            validationDiv.classList.remove('hidden');
            hideValidationDiv();
        }

        function checkTransactionStatus(requestId, timerInterval, statusCheckInterval) {
            $.ajax({
                url: '{{ url("/checkout/payment/check-status") }}',
                type: 'POST',
                data: {
                    request_id: requestId
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.status === 'completed') {
                        // Status 1 or 2 - Success
                        clearInterval(timerInterval);
                        clearInterval(statusCheckInterval);
                        
                        toastr.success(response.message);
                        $('#modal-message').html('<span class="text-green-600 font-bold">✓ ' + response.message + '</span>');
                        
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 2000);
                    } else if (response.status === 'rejected') {
                        // Status 3 - Rejected
                        clearInterval(timerInterval);
                        clearInterval(statusCheckInterval);
                        
                        toastr.error(response.message);
                        $('#modal-message').html('<span class="text-red-600 font-bold">✗ ' + response.message + '</span>');
                        
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 3000);
                    } else {
                        // Status 0 - Still pending
                        $('#modal-message').html('<span class="text-yellow-600">⏳ ' + response.message + '</span>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Status check error:', error);
                }
            });
        }

        async function checkTransactionIdBM(trxid) {
            return new Promise((resolve, reject) => {
                $.ajax({
                    url: '/check-exist-bm',
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

        // Old paymentVerification function removed - now using checkTransactionStatus polling

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
                }
            }, 1000);
        });

        function submitCancel(req_id) {

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('/checkout/payment/cancelled') }}',
                type: 'POST',
                data: {
                    'request_id': req_id
                },
                success: function(response) {
                    Swal.close();
                    if (response.success === true) {
                        Swal.fire({
                            title: 'Completed!',
                            text: 'Your operation was successful.',
                            icon: 'success'
                        });
                        // window.location.assign(response.url);
                        window.top.location.href = response.url;
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(error);
                    Swal.close();
                }
            });
        }



        function canceledRequest(req_id) {
            return $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '{{ url('/checkout/payment/cancelled') }}',
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
                        console.log(response);
                    }
                },
                error: function(xhr, status, error) {
                    toastr.error(error);
                }
            });
        }

        $(document).ready(function() {
            function checkTransactionId(trxid) {
                $.ajax({
                    url: '/check-transaction',
                    type: 'POST',
                    data: {
                        trxid: trxid
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success == false) {
                            // Transaction ID exists
                        } else {
                            toastr.error("Duplicate Transaction ID can't be used.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
            }

            $('#trxid').on('input', function() {
                countBm = 0;
                let trxid = $(this).val();
                trxid = trxid.replace(/[^a-zA-Z0-9]/g, '').toUpperCase();

                $(this).val(trxid);

                if (trxid.length >= 5) {
                    console.log(trxid);
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
</body>

</html>
