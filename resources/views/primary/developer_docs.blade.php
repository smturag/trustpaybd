<!DOCTYPE html>
<html lang="en" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title> iPay BD - Developer - Api Documentation</title>
    <meta name="title" content="iPay BD - Developer - Api Documentation">
    <meta name="description" content="iPay BD - Simple Money Transfers">
    <meta name="keywords" content="wallet,currency,e-wallet">
    <link rel="shortcut icon" href="{{ asset('frontend/developer/images/favicon.png') }}" type="image/x-icon">


    <link rel="apple-touch-icon" href="{{ asset('frontend/developer/images/logo.png') }}">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-title" content="iPay BD - Developer - Api Documentation">

    <meta itemprop="name" content="iPay BD - Developer - Api Documentation">
    <meta itemprop="description" content>
    <meta itemprop="image" content="https://xcash.clickitbd.com/assets/images/seo/64b46bd42807a1689545684.png">

    <meta property="og:type" content="website">
    <meta property="og:title" content="iPay BD - Simple Money Transfers">
    <meta property="og:description" content="iPay BD - Simple Money Transfers">
    <meta property="og:image" content="images/64b46bd42807a1689545684.png">
    <meta property="og:image:type" content="image/png">
    <meta property="og:image:width" content="1180">
    <meta property="og:image:height" content="600">
    <meta property="og:url" content="https://xcash.clickitbd.com/api/documentation">

    <meta name="twitter:card" content="summary_large_image">

    <link rel="stylesheet" href="{{ asset('frontend/developer/css/bootstrap.min.css') }}">
    <!-- fontawesome 5  -->
    <link rel="stylesheet" href="{{ asset('frontend/developer/css/all.min.css') }}">
    <!-- lineawesome font -->
    <link rel="stylesheet" href="{{ asset('frontend/developer/css/line-awesome.min.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/developer/css/lightcase.css') }}">
    <!-- slick slider css -->
    <link rel="stylesheet" href="{{ asset('frontend/developer/css/slick.css') }}">
    <!-- main css -->

    <link rel="stylesheet" href="{{ asset('frontend/developer/css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/developer/css/main.css') }}">

    <link rel="stylesheet" href="{{ asset('frontend/developer/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.23.0/min/vs/editor/editor.main.min.css" />

    <style>
        .header.style--two .main-menu li a {
            padding: 0.5rem 0;
        }

        .header.style--two .header__bottom {
            padding: 15px 0;
        }

        .code-block {
            background-color: black;
            border-radius: 4px;
            padding: 10px;
        }

        /* Styles for the code block header */
        .code-block-header {
            color: #00ff00; /* Green color for the header text */
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        /* Styles for the code block code */
        .code-block pre {
            font-family: "Courier New", Courier, monospace;
            font-size: 18px;
            color: #e5e5e5; /* Light gray color for the code text */
            white-space: pre-wrap;
            word-wrap: break-word;
            margin: 0;
        }

        /* Styles for the copy button */
        .clipboard-btn {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

    </style>

    <link href="{{ asset('frontend/developer/color.php') }}" rel="stylesheet">

</head>

<body>

    <div class="preloader">
        <div class="preloader-container">
            <span class="animated-preloader"></span>
        </div>
    </div>



    <div class="preloader">
        <div class="preloader-container">
            <span class="animated-preloader"></span>
        </div>
    </div>

    <header class="header">

        <div class="header__bottom">
            <div class="container-fluid">
                <nav class="navbar navbar-expand-xl p-0 align-items-center">
                    <a class="site-logo site-title" href="https://xcash.clickitbd.com">
                        <img src="{{ asset('images/logos/1686760341_logo.png') }}"alt="logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="menu-toggle"></span>
                    </button>
                    <div class="collapse navbar-collapse mt-lg-0 mt-3" id="navbarSupportedContent">
                        <ul class="navbar-nav main-menu ms-auto">
                            <li><a href="/">Home</a></li>
                        </ul>
                        <div class="nav-right">
                            <a href="/customer"
                                class="btn btn-sm btn--base d-lg-inline-flex align-items-center">
                                <i class="las la-user-circle font-size--18px me-2"></i> Login </a>
                                <a href="{{ route('customer.view_create_account') }}"
                                class="btn btn-sm btn--base d-lg-inline-flex align-items-center">
                                <i class="las la-user-circle font-size--18px ml-2"></i> Create Account </a>
                        </div>
                    </div>
                </nav>
            </div>
        </div><!-- header__bottom end -->
    </header>

    <div class="main-wrapper">

        <section class="inner-hero overlay--one bg_img"
            style="background-image: url(&#x27;images/62efad03dcc131659874563.jpg&#x27;);">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <h2 class="page-title text-center text-white">Developer - Api Documentation</h2>

                    </div>
                </div>
            </div>
        </section>

        <!-- documentation section start -->
        <div class="pt-50 pb-50 documentation-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-2">
                        <button class="sidebar-menu-open-btn mb-5"><i class="las la-bars"></i> Menu</button>
                        <div class="documentation-menu-wrapper">
                            <button class="sidebar-close-btn"><i class="las la-times"></i></button>
                            <nav class="sidebar-menu">
                                <ul class="menu">
                                    <li class="has_child"><a href="#introduction-section">Get started</a>
                                        <ul class="drp-menu">
                                            <li class="active"><a href="#introduction">Introduction</a></li>
                                            <li><a href="#api-key">Get Api Key</a></li>
                                            <li><a href="#api-key">Create Payment</a></li>
                                            <li><a href="#setting-two">Property Definition</a></li>
                                            <li><a href="#form_data">Example Form Data</a></li>
                                            <li><a href="#php_code">Example PHP code</a></li>
                                            <li><a href="#success_data">Success Response</a></li>
                                            <li><a href="#r_error">Response errors</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </div>
                    <div class="col-lg-10">
                        <div class="doc-body">
                            <div class="doc-section" id="introduction-section">
                                <div class="doc-content">
                                    <section id="introduction">
                                        <h3>Introduction</h3>
                                        <p class="mt-2">This section describes the <strong>iPay BD</strong>
                                            payment gateway API. </p>
                                        <hr>
                                        <p class="text-justify">
                                            <strong>iPay BD</strong> API is easy to implement in your business software.
                                            Our API is well formatted URLs, accepts cURL requests, returns JSON
                                            responses.
                                        </p>

                                    </section>
                                </div><!-- doc-content end -->
                            </div><!-- doc-section end -->
                            {{-- <div class="doc-section" id="currency">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Supported Currencies</h2>
                                        <p class="mt-2">This section describes the currencies supported by
                                            <strong>iPay BD</strong></p>
                                        <hr>
                                        <p>
                                            <strong>iPay BD</strong>
                                            allows to make transaction with below currencies. Any new currency may
                                            update in future.
                                        </p>
                                    </section>
                                    <section id="setting-two">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Currency Name</th>
                                                        <th>Currency Symbol</th>
                                                        <th>Currency Code</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>United States Dollar</td>
                                                        <td>$</td>
                                                        <td>USD</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tether trc20</td>
                                                        <td>₮</td>
                                                        <td>USDT</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Bangladeshi Taka</td>
                                                        <td>৳</td>
                                                        <td>BDT</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div><!-- table-responsive end -->
                                    </section>
                                </div><!-- doc-content end -->
                            </div><!-- doc-section end --> --}}
                            <div class="doc-section" id="api-key">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Get The Api Key</h2>
                                        <p class="mt-2">This section describes how you can get your api key.</p>
                                        <hr>
                                        <p class="text-justify">Login to your <strong>iPay BD</strong>
                                            merchant account. If you don't have any ? please contact with admin
                                        </p>
                                        <p>Next step is to find the <span class="text--base">Api Key</span>
                                            menu in your dashboard sidebar. Click the menu. </p>
                                        <p class="text-justify">The api keys can be found there which is <strong>Public
                                                key and Secret key.</strong>
                                            Use these keys to initiate the API request. Every time you can generate new
                                            API key by clicking <span class="text--base">Generate Api Key</span>
                                            button. Remember do not share these keys with anyone. </p>
                                    </section>
                                </div><!-- doc-content end -->
                            </div><!-- doc-section end -->

                            <div class="doc-section" id="initiate">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Initiate Payment</h2>
                                        <p class="mt-2">This section describes the process of initaiing the payment.
                                        </p>
                                        <hr>
                                        <p>
                                            For accessing to iPay BD payment gateway you will need to generate public key and secret key from merchant developer settings

                                            After that send those two keys as header , mentioned in example code below </p>

                                        <p>
                                            <strong>Request Method:</strong>
                                            <span class="text--base">POST</span>
                                        </p>
                                    </section>
                                    <section id="setting-two">
                                        <h2>Property Definition</h2>
                                        <p>Request to the end point with the following parameters below.</p>
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>PROPERTY</th>
                                                        <th>TYPE</th>
                                                        <th>EXAMPLE</th>

                                                        <th>DEFINITION</th>


                                                    </tr>
                                                </thead>
                                                {{-- <tbody>
                                                    <tr>
                                                        <td>public_key</td>
                                                        <td>string (50)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Your Public API key
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>identifier</td>
                                                        <td>string (20)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Identifier is basically for identify payment at your end
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>currency</td>
                                                        <td>string (4)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Currency Code, Must be in Upper Case. e.g. USD,EUR
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>amount</td>
                                                        <td>decimal</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Payment amount.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>details</td>
                                                        <td>string (100)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Details of your payment or transaction.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>ipn_url</td>
                                                        <td>string</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            The url of instant payment notification.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>success_url</td>
                                                        <td>string</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Payment success redirect url.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>cancel_url</td>
                                                        <td>string</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Payment cancel redirect url.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>site_logo</td>
                                                        <td>string/url</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Your business site logo.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>checkout_theme</td>
                                                        <td>string</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--info font-size--12px">Optional</span>
                                                            Checkout form theme dark/light. Default theme is light
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_name</td>
                                                        <td>string (30)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Customer name.
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td>customer_email</td>
                                                        <td>string (30)</td>
                                                        <td>
                                                            <span
                                                                class="badge badge--danger font-size--12px">Required</span>
                                                            Customer valid email.
                                                        </td>
                                                    </tr>
                                                </tbody> --}}

                                                <tbody>
                                                    <tr>
                                                        <td><span class="fw-bold">amount</span></td>
                                                        <td class="text-center">numeric <span class="badge badge--danger font-size--12px">Required</span> </td>
                                                        <td class="text-center">100.00</td>

                                                        <td><strong>amount</strong> will be used as merchant payment
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fw-bold">reference</span></td>
                                                        <td class="text-center">varchar(min:3, max: 20) <span class="badge badge--danger font-size--12px">Required</span></td>
                                                        <td class="text-center">inv-0215285852</td>

                                                        <td>Customer invoice/tracking number will be used as <strong>reference</strong></td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fw-bold">currency</span></td>
                                                        <td class="text-center">varchar(4) <span class="badge badge--danger font-size--12px">Required</span></td>
                                                        <td class="text-center">BDT</td>

                                                        <td>always pass BDT as value</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fw-bold">callback_url</span></td>
                                                        <td class="text-center">text <span class="badge badge--danger font-size--12px">Required</span></td>
                                                        <td class="text-center">https://example.com/callback.php</td>

                                                        <td>A valid and working url where customer will be redirect to after successful/failed payment</td>
                                                    </tr>

                                                    <tr>
                                                        <td><span class="fw-bold">cust_name</span></td>
                                                        <td class="text-center">varchar(255) <span class="badge badge--danger font-size--12px">Required</span></td>
                                                        <td class="text-center">Ariful Islam</td>

                                                        <td>Customer name should pass here</td>
                                                    </tr>

                                                    <tr>
                                                        <td><span class="fw-bold">cust_phone</span></td>
                                                        <td class="text-center">varchar(15) <span class="badge badge--danger font-size--12px">Required</span></td>
                                                        <td class="text-center">+8801711XXYYZZ</td>

                                                        <td>Customer phone should pass here</td>
                                                    </tr>
                                                    <tr>
                                                        <td><span class="fw-bold">cust_address</span></td>
                                                        <td class="text-center">varchar(100)</td>
                                                        <td class="text-center">Dhaka, Bangladesh</td>
                                                        <td>Customer address should pass here</td>
                                                    </tr>

                                                    <tr>
                                                        <td><span class="fw-bold">checkout_items</span></td>
                                                        <td class="text-center">array</td>
                                                        <td class="text-center">[ ]</td>

                                                        <td>merchant may pass multiple products items or other types of data as array</td>
                                                    </tr>

                                                    <tr>
                                                        <td><span class="fw-bold">note</span></td>
                                                        <td class="text-center">varchar(100)</td>
                                                        <td class="text-center">some rext</td>
                                                        <td class="text-center">no</td>
                                                        <td></td>
                                                    </tr>


                                                    </tbody>
                                            </table>
                                        </div><!-- table-responsive end -->

                                    </section>
                                </div><!-- doc-content end -->
                                                        </div>
                            <div id="form_data" class="doc-section" id="initiate">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Example Form Data </h2>
                                        <p class="mt-2">This section describe example of form data.
                                        </p>
                                        <hr>
                                        <div id="success_response" class="code-block">
                                            <button class="clipboard-btn" data-clipboard-target="#response">Copy</button>
                                            <div class="code-block-header">Example Form Data (BODY)</div>

                                            <!-- JSON data will be displayed here -->
                                            <pre><code class="language-json" id="response">
{
    "amount": "1100",
    "reference": "iuy87dFfJ",
    "currency": "BDT",
    "callback_url": "https://example.com",
    "cust_name": "arif",
    "cust_phone": "+855454454",
    "cust_address": "dhaka",
    "checkout_items": {
        "item1": {
            "name": "Product1",
            "size": "50kg",
            "shape": "square"
        },
        "item2": {
            "name": "Product2",
            "size": "100kg",
            "shape": "round"
        }
    },
    "note": "test"
}
                                    </code></pre>
                                        </div>
                                    </section>

                                </div><!-- doc-content end -->
                            </div><!-- doc-section end -->

                            <div class="doc-section" id="php_code">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Example of PHP Curl Request </h2>
                                        <p class="mt-2">This section describe example of  PHP Curl Request.
                                        </p>
                                        <hr>
                                        <div id="success_response" class="code-block">
                                            <button class="clipboard-btn" data-clipboard-target="#response">Copy</button>
                                            <div class="code-block-header">Example CURL PHP Code</div>

                                            <!-- JSON data will be displayed here -->
                                            <pre><code class="language-json" id="response">
$curl = curl_init();

curl_setopt_array($curl, array(
CURLOPT_URL => 'https://ipaybd.com/api/v1/payment/create-payment',
CURLOPT_RETURNTRANSFER => true,
CURLOPT_ENCODING => '',
CURLOPT_MAXREDIRS => 10,
CURLOPT_TIMEOUT => 0,
CURLOPT_FOLLOWLOCATION => true,
CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
CURLOPT_CUSTOMREQUEST => 'POST',
CURLOPT_POSTFIELDS =>'{
    "amount": "1100",
    "reference": "iuy87dFfJ",
    "currency": "BDT",
    "callback_url": "https://example.com",
    "cust_name": "arif",
    "cust_phone": "+855454454",
    "cust_address": "dhaka",
    "checkout_items": {
        "item1": {
            "name": "Product1",
            "size": "50kg",
            "shape": "square"
        },
        "item2": {
            "name": "Product2",
            "size": "100kg",
            "shape": "round"
        }
    },
    "note": "test"
}',
CURLOPT_HTTPHEADER => array(
    'X-Authorization: cFlTHJphTER2O3nAlV64T9fbjV85l9QuyWZaSKQeU7Z7oLBJpHQqs7PfwPrh9AJE',
    'X-Authorization-Secret: VxmTCiq76Hvbj1xByLw354ltvJISvnvefah9VEPjMlcj3LmVs7BcW1DDBeZZAHw3',
    'Content-Type: application/json'
),
));

$response = curl_exec($curl);
curl_close($curl);
echo $response;
                                    </code></pre>
                                        </div>
                                    </section>

                                </div><!-- doc-content end -->
                            </div>


                            <div class="doc-section" id="success_data">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Example of Response With Payment Success Payment Url</h2>
                                        <p class="mt-2">This section describe example of Response With Payment Success Payment Url.
                                        </p>
                                        <hr>
                                        <div id="success_response" class="code-block">
                                            <button class="clipboard-btn" data-clipboard-target="#response">Copy</button>
                                            <div class="code-block-header">Example Response With Payment Success Payment Url</div>

                                            <!-- JSON data will be displayed here -->
                                            <pre><code class="language-json" id="response">
{
    "status": "success",
    "message": "Payment created successfully",
    "data": {
        "payment_id": "abc123",
        "payment_url": "https://payment.example.com/abc123"
    }
}
                                    </code></pre>
                                        </div>
                                    </section>

                                </div><!-- doc-content end -->
                            </div>

                            <div class="doc-section" id="r_error">
                                <div class="doc-content">
                                    <section id>
                                        <h2>Example of Response with some errors</h2>
                                        <p class="mt-2">This section describe example of Response with some errors.
                                        </p>
                                        <hr>
                                        <div id="success_response" class="code-block">
                                            <button class="clipboard-btn" data-clipboard-target="#response">Copy</button>
                                            <div class="code-block-header">Example Response with some errors</div>

                                            <!-- JSON data will be displayed here -->
                                            <pre><code class="language-json" id="response">
    ===========unauthorized===========
    {
        "success": false,
        "code": 403,
        "message": "not authorized"
    }


    ===========Duplicate Reference Id===========
    {
        "success": false,
        "message": "Data validation error",
        "data": {
            "reference": [
                "Duplicate reference-id iuy8767jyLKgJDKgFfJ"
            ]
        }
    }

    =============Fields Required==============
    {
        "success": false,
        "message": "Data validation error",
        "data": {
            "amount": [
                "The amount field is required."
            ],
            "reference": [
                "The reference field is required."
            ]
        }
    }

    ==============data type error==============
    {
        "success": false,
        "message": "Data validation error",
        "data": {
            "amount": [
                "The amount field must be a number."
            ]
        }
    }

                                    </code></pre>
                                        </div>
                                    </section>

                                </div><!-- doc-content end -->
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- documentation section end -->

    </div>

    <!-- cookies dark version end -->

    <!-- jQuery library -->
    <script src="{{ asset('frontend/developer/js/jquery-3.6.0.min.js') }}"></script>
    <!-- bootstrap js -->
    <script src="{{ asset('frontend/developer/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slick slider js -->
    <script src="{{ asset('frontend/developer/js/slick.min.js') }}"></script>
    <!-- scroll animation -->
    <script src="{{ asset('frontend/developer/js/wow.min.js') }}"></script>
    <!-- lightcase js -->
    <script src="{{ asset('frontend/developer/js/lightcase.min.js') }}"></script>

    <script src="{{ asset('frontend/developer/js/jquery.paroller.min.js') }}"></script>
    <!-- main js -->
    <script src="{{ asset('frontend/developer/js/app.js') }}"></script>

    <script src="{{ asset('frontend/developer/js/clipboard.min.js') }}"></script>
    <script src="{{ asset('frontend/developer/js/menu-spy.min.js') }}"></script>
    <script src="{{ asset('frontend/developer/js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('frontend/developer/js/highlight.min.js') }}"></script>


    <link rel="stylesheet" href="{{ asset('frontend/developer/css/iziToast.min.css') }}">
    <script src="{{ asset('frontend/developer/js/iziToast.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/clipboard.js/2.0.8/clipboard.min.js"></script>

    <!-- Include the Monaco Editor JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.23.0/min/vs/loader.min.js"></script>
    <script>
        // Initialize Clipboard.js for the copy button
        const clipboard = new ClipboardJS('.clipboard-btn');
        clipboard.on('success', function (e) {
            alert('JSON data copied to clipboard!');
            e.clearSelection();
        });
        clipboard.on('error', function (e) {
            console.error('Failed to copy JSON data.');
        });

        // Load the Monaco Editor
        require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.23.0/min/vs' }});
        require(['vs/editor/editor.main'], function() {
            // Wait for the editor to be fully initialized
            monaco.editor.onDidCreateEditor(function(editor) {
                editor.updateOptions({
                    readOnly: true, // Make the editor read-only
                    theme: 'vs-dark' // Use the dark theme
                });
            });

            // Create the editor inside the container
            const editor = monaco.editor.create(document.getElementById('response'), {
                value: JSON.stringify(jsonData, null, 4),
                language: 'json',
            });
        });
    </script>

</body>

</html>
