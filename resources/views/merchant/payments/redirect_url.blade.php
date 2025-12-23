<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Payment Redirect</title>

    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <!-- CSRF token for Laravel -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    <h3>Processing your payment, please wait...</h3>

    <script>
        (function () {
            const currentUrl = window.location.href;
            const url = new URL(currentUrl);
            const params = new URLSearchParams(url.search);
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            // Function to submit params to backend
            function submit_url() {
                $.ajax({
                    url: '{{ route('submit_redirect') }}',
                    type: 'POST',
                    data: {
                        status: params.get('status'),
                        paymentid: params.get('paymentID'),
                    },
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    },
                    success: function (response) {
                        console.log("Redirecting to:", response);

                        // Remove query params so no infinite loop
                        window.history.replaceState({}, document.title, window.location.pathname);

                        // Redirect user to final success/failure page
                        window.location.href = response;
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert("Something went wrong! Please try again.");
                    }
                });
            }

            // Run only if status & paymentID exist
            if (params.get('status') && params.get('paymentID')) {
                submit_url();
            }
        })();
    </script>
</body>

</html>
