@extends('welcome')
@section('customer')
    <!-- Documentation Header -->
    <div class="pt-10 bg-gradient-to-br from-indigo-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-gray-900 mb-6">
                    Developer Documentation
                </h1>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto leading-relaxed">
                    Everything you need to integrate iPayBD payments into your application.
                </p>
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="#getting-started"
                        class="bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700 transition">
                        Get Started
                    </a>
                    <a href="#api-reference"
                        class="bg-white text-indigo-600 px-6 py-3 rounded-lg border border-indigo-600 hover:bg-indigo-50 transition">
                        API Reference
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Documentation Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-12 gap-8">
            <!-- Sidebar Navigation -->

            <div class="col-span-12 md:col-span-3">
                <div class="sticky top-24 bg-white rounded-xl shadow-sm p-6 border border-gray-100">
                    <div class="flex items-center space-x-2 mb-6">
                        <i class="fas fa-book text-indigo-600"></i>
                        <h3 class="text-lg font-semibold">Documentation</h3>
                    </div>
                    <!-- Scrollable container -->
                    <div class="max-h-[calc(100vh-10rem)] overflow-y-auto">
                        <ul class="space-y-3">
                            <!-- Overview -->
                            <li>
                                <a href="#overview"
                                    class="nav-link block px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition">
                                    Overview
                                </a>
                            </li>

                            <!-- Getting Started -->
                            <li>
                                <a
                                    class="nav-link block px-4 py-2 text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg transition flex justify-between items-center">
                                    <span>Getting Started</span>
                                </a>
                                <!-- Child items for "Getting Started" -->
                                <ul class="pl-6 mt-2 space-y-2">

<!-- v1 Payment Menu -->
<li x-data="{ open: false }">
    <a @click="open = !open"
        class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-indigo-50 group">
        <div class="flex items-center space-x-3">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-semibold">v1</span>
            <span class="font-medium text-gray-700 group-hover:text-indigo-600">Payment API</span>
        </div>
        <i :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-gray-400 text-sm transition-transform duration-300 group-hover:text-indigo-600"></i>
    </a>

    <!-- Direct Payment routes under v1 -->
    <ul x-show="open" class="pl-12 mt-2 space-y-2 border-l-2 border-indigo-100">
        <li>
            <a href="#v1-create-payment"
                class="block py-1.5 text-sm text-gray-600 hover:text-indigo-600 hover:translate-x-1 transition-all duration-300">
                Create Payment
            </a>
        </li>
        <li class="pb-4">
            <a href="#v1-track-status"
                class="block py-1.5 text-sm text-gray-600 hover:text-indigo-600 hover:translate-x-1 transition-all duration-300">
                Track Payment Status
            </a>
        </li>
    </ul>
</li>

<li x-data="{ open: false }">
                                        <a @click="open = !open"
                                            class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-green-50 group">
                                            <div class="flex items-center space-x-3">
                                                <span class="flex items-center justify-center w-6 h-6 rounded-full bg-green-100 text-green-600 text-xs font-semibold">v2</span>
                                                <span class="font-medium text-gray-700 group-hover:text-green-600">H2H Payment API</span>
                                            </div>
                                            <i :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-gray-400 text-sm transition-transform duration-300 group-hover:text-green-600"></i>
                                        </a>
                                        <!-- Nested sections inside v2 -->
                                        <ul x-show="open" class="pl-12 mt-2 space-y-2 border-l-2 border-green-100">

                                             <li>
                                                <a href="#payment-integration"
                                                    class="block py-1.5 text-sm text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-300">
                                                    Introduction
                                                </a>
                                            </li>

                                            <!-- Available Method -->
                                            <li>
                                                <a href="#v2-available-method"
                                                    class="block py-1.5 text-sm text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-300">
                                                    Available Method
                                                </a>
                                            </li>

                                            <!-- Create Payment -->
                                            <li>
                                                <a href="#v2-create-payment"
                                                    class="block py-1.5 text-sm text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-300">
                                                    Create Payment
                                                </a>
                                            </li>

                                            <!-- Track Status -->
                                            <li class="pb-4"> <!-- Added padding-bottom here -->
                                                <a href="#v2-status-track"
                                                    class="block py-1.5 text-sm text-gray-600 hover:text-green-600 hover:translate-x-1 transition-all duration-300">
                                                    Track Status
                                                </a>
                                            </li>
                                        </ul>
                                    </li>

<!-- v2 Withdraw Menu -->
<li x-data="{ open: false }">
    <a @click="open = !open"
        class="flex items-center justify-between px-3 py-2 text-sm rounded-lg transition-all duration-300 hover:bg-indigo-50 group">
        <div class="flex items-center space-x-3">
            <span class="flex items-center justify-center w-6 h-6 rounded-full bg-indigo-100 text-indigo-600 text-xs font-semibold">v1</span>
            <span class="font-medium text-gray-700 group-hover:text-indigo-600">Withdraw API</span>
        </div>
        <i :class="open ? 'fa-chevron-down' : 'fa-chevron-right'" class="fas text-gray-400 text-sm transition-transform duration-300 group-hover:text-indigo-600"></i>
    </a>
    <ul x-show="open" class="pl-12 mt-2 space-y-2 border-l-2 border-indigo-100">
        <li>
            <a href="#v1-mfs-create"
                class="block py-1.5 text-sm text-gray-600 hover:text-indigo-600 hover:translate-x-1 transition-all duration-300">
                Withdraw Create
            </a>
        </li>
        <li class="pb-4">
            <a href="#v1-status-check-mfs"
                class="block py-1.5 text-sm text-gray-600 hover:text-indigo-600 hover:translate-x-1 transition-all duration-300">
                Track Status
            </a>
        </li>
    </ul>
</li>


<!-- v2 Withdraw Menu (moved outside) -->



                                    <!-- v2 Section -->

                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Include Alpine.js -->
            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>




            <!-- Main Content -->
            <div class="col-span-12 md:col-span-9 space-y-8">
                <!-- Overview Section -->
                <section id="overview" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6">Overview</h2>

                    <!-- Introduction -->
                    <div class="mb-8">
                        <p class="text-gray-600 mb-4">
                           🔹 What is iPaybd? <br>
                                iPayBd is a powerful Instant Automatic Deposit and Withdrawal Solution designed
                                for merchants, small businesses, and platform developers. It enables seamless
                                and automated fund transfers between your application and customer wallets
                                through mobile banking services.
                                With iPayBd, any web application, mobile app, or software platform can effortlessly:
                                ●
                                Accept payments (deposits) from customers via mobile banking.
                                ●
                                Withdraw funds directly to the customer’s mobile wallet.
                                iPayBd is easy to integrate using HTTP-based RESTful APIs and returns data in
                                JSON format, making it developer-friendly and fast to deploy.
                        </p>
                        <ul class="list-disc list-inside space-y-2 text-gray-600 mb-6">
                            <li>Instant Payment Collection via: bKash, Nagad, Rocket, Upay and more</li>
                            <li>Automated Withdrawals to customer wallets</li>
                            <li>Real-Time Validation of transaction data</li>
                            <li>JSON API with HTTP request/response</li>
                            <li>Webhook Support for instant transaction notifications</li>
                        </ul>
                    </div>

                    <!-- Architecture Overview -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Architecture Overview</h3>
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                    <div class="text-indigo-600 mb-2">
                                        <i class="fas fa-globe text-2xl"></i>
                                    </div>
                                    <h4 class="font-semibold mb-2">Client Side</h4>
                                    <p class="text-sm text-gray-600">Secure collection of payment details using our API
                                    </p>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                    <div class="text-indigo-600 mb-2">
                                        <i class="fas fa-server text-2xl"></i>
                                    </div>
                                    <h4 class="font-semibold mb-2">Server Side</h4>
                                    <p class="text-sm text-gray-600">Payment processing and transaction management via API
                                    </p>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4 bg-white">
                                    <div class="text-indigo-600 mb-2">
                                        <i class="fas fa-bell text-2xl"></i>
                                    </div>
                                    <h4 class="font-semibold mb-2">Webhooks</h4>
                                    <p class="text-sm text-gray-600">Real-time notifications for payment events</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Available SDKs -->
                    {{-- <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Available APIs</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="text-center p-4 border rounded-lg hover:border-indigo-500 transition-colors">
                                <i class="fab fa-js text-3xl text-yellow-400 mb-2"></i>
                                <p class="font-medium">JavaScript</p>
                            </div>
                            <div class="text-center p-4 border rounded-lg hover:border-indigo-500 transition-colors">
                                <i class="fab fa-python text-3xl text-blue-500 mb-2"></i>
                                <p class="font-medium">Python</p>
                            </div>
                            <div class="text-center p-4 border rounded-lg hover:border-indigo-500 transition-colors">
                                <i class="fab fa-php text-3xl text-purple-500 mb-2"></i>
                                <p class="font-medium">PHP</p>
                            </div>
                            <div class="text-center p-4 border rounded-lg hover:border-indigo-500 transition-colors">
                                <i class="fab fa-java text-3xl text-red-500 mb-2"></i>
                                <p class="font-medium">Java</p>
                            </div>
                        </div>
                    </div> --}}

                    <!-- Integration Process -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Integration Process</h3>
                        <div class="relative">
                            <!-- Timeline -->
                            <div class="absolute left-4 top-0 bottom-0 w-0.5 bg-gray-200"></div>

                            <div class="space-y-6">
                                <div class="relative pl-8">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white">
                                        1</div>
                                    <h4 class="font-semibold mb-1">Account Setup</h4>
                                    <p class="text-gray-600">Create an account and get your API keys</p>
                                </div>
                                <div class="relative pl-8">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white">
                                        2</div>
                                    <h4 class="font-semibold mb-1">Integration</h4>
                                    <p class="text-gray-600">Implement payment flow using our APIs</p>
                                </div>
                                <div class="relative pl-8">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white">
                                        3</div>
                                    <h4 class="font-semibold mb-1">Testing</h4>
                                    <p class="text-gray-600">Test integration using test API keys</p>
                                </div>
                                <div class="relative pl-8">
                                    <div
                                        class="absolute left-0 w-8 h-8 bg-indigo-600 rounded-full flex items-center justify-center text-white">
                                        4</div>
                                    <h4 class="font-semibold mb-1">Go Live</h4>
                                    <p class="text-gray-600">Switch to production API keys and start accepting payments</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Getting Started Section -->
                <section id="getting-started" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6">Getting Started</h2>
                    <p class="text-gray-600 mb-6">
                        Follow these steps to start accepting payments with iPayBD.
                    </p>

                    <section id="current-url" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold mb-6 flex items-center">
                            <svg class="w-6 h-6 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                            </svg>
                            Base URL
                        </h2>
                        <p class="text-gray-600 mb-6">
                            <span class="font-mono text-blue-500 bg-blue-50 px-3 py-1 rounded-md">
                                <?php echo $_SERVER['HTTP_HOST']; ?>
                            </span>
                        </p>
                    </section>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">1. Get Your API Keys</h3>
                        <div class="code-wrapper">
                            <button class="copy-button" onclick="copyCode(this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <pre class="language-bash rounded-lg"><code>X-Authorization="pk_test_123..."
X-Authorization-Secret="sk_test_456..."</code></pre>
                        </div>
                    </div>

                    {{-- <section id="v1-create-payment">
                        <div class="mb-8">
                            <h3 class="text-xl font-semibold mb-4">Create Payment</h3>
                            <!-- JSON Data Section -->
                            <div class="code-wrapper mt-6">
                                <button class="copy-button" onclick="copyCode(this)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <!-- Code Block for JSON Data -->
                                <pre class="language-json rounded-lg"><code>{
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
}</code></pre>
                            </div>
                            <!-- Property Definition Table -->
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold mb-4">Property Definition</h4>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Property</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Example</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Mandatory</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Definition</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">amount</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">100.00</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Amount will be used as merchant payment</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">reference</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(min:3, max: 20)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">inv-0215285852</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Customer invoice/tracking number will be used as reference</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">currency</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(4)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">BDT</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Always pass BDT as value</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">callback_url</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">text</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">https://example.com/callback.php</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A valid and working URL where the customer will be redirected after successful/failed payment</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">cust_name</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(255)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Ariful Islam</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Customer name should be passed here</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">cust_phone</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(15)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">+8801711XXYYZZ</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Customer phone number should be passed here</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">cust_address</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(100)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Dhaka, Bangladesh</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">no</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Customer address should be passed here</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">checkout_items</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">array</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">[ ]</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">no</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Merchant may pass multiple product items or other types of data as an array</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700">note</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">varchar(100)</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">some text</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">no</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Additional notes or comments</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold mb-4">PHP cURL Request Example</h4>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-php rounded-lg"><code>$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'example.com/api/v1/payment/create-payment',
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
        "webhook_url": "https://example.com",
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
echo $response;</code></pre>
                                </div>
                            </div>


                            <!-- Response Examples Section -->
                            <div class="mt-8">
                                <h4 class="text-lg font-semibold mb-4">Response Examples</h4>
                                <!-- Success Response -->
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json rounded-lg"><code>{
"success": true,
"message": "Payment request created successfully",
"data": {
    "request_id": "c05a584911240b30f525041959c5c38540fdd5f34639b214b9",
    "amount": "100",
    "reference": "5w21rm54e4FD",
    "currency": "BDT",
    "issue_time": "2025-03-06 23:54:44",
    "payment_url": "https://ipaybd.net/checkout/c05a584911240b30f525041959c5c38540fdd5f34639b214b9?expires=1741319684&signature=6294408b326cf940cad20816cda5aa96c234fcabfeef49d013be6e6daeb9514e"
}
}
</code></pre>
                                </div>
                                <!-- Error Responses -->

                                <!-- Error Responses -->
                                <div class="mt-6">
                                    <h5 class="text-md font-semibold mb-2">Error Responses</h5>
                                    <!-- Unauthorized -->
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>===========unauthorized===========
                    {
                        "success": false,
                        "code": 403,
                        "message": "not authorized"
                    }</code></pre>
                                    </div>
                                    <!-- Duplicate Reference Id -->
                                    <div class="code-wrapper mt-4">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>===========Duplicate Reference Id===========
                    {
                        "success": false,
                        "message": "Data validation error",
                        "data": {
                            "reference": [
                                "Duplicate reference-id iuy8767jyLKgJDKgFfJ"
                            ]
                        }
                    }</code></pre>
                                    </div>
                                    <!-- Fields Required -->
                                    <div class="code-wrapper mt-4">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>=============Fields Required==============
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
                    }</code></pre>
                                    </div>
                                    <!-- Data Type Error -->
                                    <div class="code-wrapper mt-4">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>==============data type error==============
                    {
                        "success": false,
                        "message": "Data validation error",
                        "data": {
                            "amount": [
                                "The amount field must be a number."
                            ]
                        }
                    }</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <section id="v1-create-payment" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                        <!-- Endpoint Section -->
                        <div class="mb-8">
                            <h3 class="text-2xl font-bold mb-4">Create Payment</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <span class="font-mono text-blue-500">POST</span>
                                <span class="font-mono text-gray-700">/api/v1/payment/create-payment</span>
                            </div>
                        </div>

                        <!-- Multi-Language HTTP Request Examples -->
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">HTTP Request Examples</h4>
                            <!-- Tab Navigation -->
                            <div class="flex space-x-4 border-b border-gray-200 mb-6">
                                <button class="tab-button active px-4 py-2 text-lg font-semibold text-blue-500" onclick="switchTab('curl')">cURL</button>
                                <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('php')">PHP</button>
                                <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('python')">Python</button>
                                <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('javascript')">JavaScript</button>
                                <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('csharp')">C#</button>
                            </div>

                            <!-- cURL Tab -->
                            <div id="curl-tab" class="tab-content active">
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash"><code>curl -X POST "https://example.com/api/v1/payment/create-payment" \
                    -H "X-Authorization: pk_test_123..." \
                    -H "X-Authorization-Secret: sk_test_456..." \
                    -H "Content-Type: application/json" \
                    -d '{
                        "amount": "1100",
                        "reference": "iuy87dFfJ",
                        "currency": "BDT",
                        "callback_url": "https://example.com",
                        "webhook_url": "https://example.com",
                        "cust_name": "arif",
                        "note": "test"
                    }'</code></pre>
                                </div>
                            </div>

                            <!-- PHP Tab -->
                            <div id="php-tab" class="tab-content">
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-php"><code>$curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://example.com/api/v1/payment/create-payment",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => array(
                            "X-Authorization: pk_test_123...",
                            "X-Authorization-Secret: sk_test_456...",
                            "Content-Type: application/json"
                        ),
                        CURLOPT_POSTFIELDS => '{
                            "amount": "1100",
                            "reference": "iuy87dFfJ",
                            "currency": "BDT",
                            "callback_url": "https://example.com",
                            "webhook_url": "https://example.com",
                            "cust_name": "arif",
                            "note": "test"
                        }'
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    echo $response;</code></pre>
                                </div>
                            </div>

                            <!-- Python Tab -->
                            <div id="python-tab" class="tab-content">
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-python"><code>import requests

                    url = "https://example.com/api/v1/payment/create-payment"
                    headers = {
                        "X-Authorization": "pk_test_123...",
                        "X-Authorization-Secret": "sk_test_456...",
                        "Content-Type": "application/json"
                    }
                    payload = {
                        "amount": "1100",
                        "reference": "iuy87dFfJ",
                        "currency": "BDT",
                        "callback_url": "https://example.com",
                        "webhook_url": "https://example.com",
                        "cust_name": "arif",
                        "note": "test"
                    }

                    response = requests.post(url, json=payload, headers=headers)
                    print(response.json())</code></pre>
                                </div>
                            </div>

                            <!-- JavaScript Tab -->
                            <div id="javascript-tab" class="tab-content">
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-javascript"><code>const url = "https://example.com/api/v1/payment/create-payment";
                    const headers = {
                        "X-Authorization": "pk_test_123...",
                        "X-Authorization-Secret": "sk_test_456...",
                        "Content-Type": "application/json"
                    };
                    const payload = {
                        amount: "1100",
                        reference: "iuy87dFfJ",
                        currency: "BDT",
                        callback_url: "https://example.com",
                        cust_name: "arif",
                        note: "test"
                    };

                    fetch(url, {
                        method: "POST",
                        headers: headers,
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => console.log(data))
                    .catch(error => console.error(error));</code></pre>
                                </div>
                            </div>

                            <!-- C# Tab -->
                            <div id="csharp-tab" class="tab-content">
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-csharp"><code>using System;
                    using System.Net.Http;
                    using System.Text;
                    using System.Threading.Tasks;

                    class Program
                    {
                        static async Task Main(string[] args)
                        {
                            var url = "https://example.com/api/v1/payment/create-payment";
                            var payload = new
                            {
                                amount = "1100",
                                reference = "iuy87dFfJ",
                                currency = "BDT",
                                callback_url = "https://example.com",
                                cust_name = "arif",
                                note = "test"
                            };
                            var headers = new
                            {
                                X_Authorization = "pk_test_123...",
                                X_Authorization_Secret = "sk_test_456...",
                                Content_Type = "application/json"
                            };

                            using (var client = new HttpClient())
                            {
                                client.DefaultRequestHeaders.Add("X-Authorization", headers.X_Authorization);
                                client.DefaultRequestHeaders.Add("X-Authorization-Secret", headers.X_Authorization_Secret);
                                client.DefaultRequestHeaders.Add("Content-Type", headers.Content_Type);

                                var content = new StringContent(Newtonsoft.Json.JsonConvert.SerializeObject(payload), Encoding.UTF8, "application/json");
                                var response = await client.PostAsync(url, content);
                                var result = await response.Content.ReadAsStringAsync();
                                Console.WriteLine(result);
                            }
                        }
                    }</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- JSON Example Section -->
                        {{-- <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">JSON Example</h4>
                            <div class="code-wrapper">
                                <button class="copy-button" onclick="copyCode(this)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <pre class="language-json"><code>{
                        "amount": "1100",
                        "reference": "iuy87dFfJ",
                        "currency": "BDT",
                        "callback_url": "https://example.com",
                        "webhook_url": "https://example.com",
                        "cust_name": "arif",
                        "note": "test"
                    }</code></pre>
                            </div>
                        </div> --}}

                        <!-- Property Definition Table -->
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">Property Definition</h4>
                            <div class="overflow-x-auto">
                                <table class="min-w-full bg-white border border-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Property</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Example</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Mandatory</th>
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Definition</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">amount</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">100.00</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Amount will be used as merchant payment</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">reference</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">varchar(min:3, max: 20)</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">inv-0215285852</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Customer invoice/tracking number will be used as reference</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">currency</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">varchar(4)</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">BDT</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Always pass BDT as value</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">callback_url</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">text</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">https://example.com/callback.php</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">A valid and working URL where the customer will be redirected after successful/failed payment</td>
                                        </tr>
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">cust_name</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">varchar(255)</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Ariful Islam</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">yes</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Customer name should be passed here</td>
                                        </tr>

                                        <tr>
                                            <td class="px-4 py-2 text-sm text-gray-700">note</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">varchar(100)</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">some text</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">no</td>
                                            <td class="px-4 py-2 text-sm text-gray-700">Additional notes or comments</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Response Examples Section -->
                        <div class="mb-8">
                            <h4 class="text-xl font-semibold mb-4">Response Examples</h4>
                            <!-- Success Response -->
                            <div class="code-wrapper">
                                <button class="copy-button" onclick="copyCode(this)">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                                <pre class="language-json"><code>{
                        "success": true,
                        "message": "Payment request created successfully",
                        "data": {
                            "request_id": "c05a584911240b30f525041959c5c38540fdd5f34639b214b9",
                            "amount": "100",
                            "reference": "5w21rm54e4FD",
                            "currency": "BDT",
                            "issue_time": "2025-03-06 23:54:44",
                            "payment_url": "https://ipaybd.net/checkout/c05a584911240b30f525041959c5c38540fdd5f34639b214b9?expires=1741319684&signature=6294408b326cf940cad20816cda5aa96c234fcabfeef49d013be6e6daeb9514e"
                        }
                    }</code></pre>
                            </div>

                            <!-- Error Responses -->
                            <div class="mt-6">
                                <h5 class="text-md font-semibold mb-2">Error Responses</h5>
                                <!-- Unauthorized -->
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json"><code>{
                        "success": false,
                        "code": 403,
                        "message": "not authorized"
                    }</code></pre>
                                </div>
                                <!-- Duplicate Reference Id -->
                                <div class="code-wrapper mt-4">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json"><code>{
                        "success": false,
                        "message": "Data validation error",
                        "data": {
                            "reference": [
                                "Duplicate reference-id iuy8767jyLKgJDKgFfJ"
                            ]
                        }
                    }</code></pre>
                                </div>
                                <!-- Fields Required -->
                                <div class="code-wrapper mt-4">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json"><code>{
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
                    }</code></pre>
                                </div>
                                <!-- Data Type Error -->
                                <div class="code-wrapper mt-4">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json"><code>{
                        "success": false,
                        "message": "Data validation error",
                        "data": {
                            "amount": [
                                "The amount field must be a number."
                            ]
                        }
                    }</code></pre>
                                </div>
                            </div>
                        </div>

                        <div class="code-wrapper mt-4">
    <button class="copy-button" onclick="copyCode(this)">
        <i class="fas fa-copy"></i> Copy
    </button>
    <pre class="language-json rounded-lg"><code>===========Payment Callback Handler===========
 Handle callback URL parameters
 Sample Success URL:
 http://127.0.0.1:5500/?payment=success&payment_method=rocket&
request_id=7dc7ae27c564e9bffcc72d3d3f10fc69b50998fe24ff0cd566&reference
=ref_usx60cisvas8886&sim_id=01893087273&trxid=DR123456&amount=50

 Sample Cancel URL:
 http://127.0.0.1:5500/?payment=Cancelled

 Status codes:
 payment = success
 payment = rejected
 payment = Cancelled
 payment = pending

 </code></pre>
</div>
                    </section>

                    <!-- JavaScript for Tabs and Copy Functionality -->
                    <script>
                        // Switch between tabs
                        function switchTab(tabName) {
                            // Hide all tab contents
                            document.querySelectorAll('.tab-content').forEach(tab => {
                                tab.classList.remove('active');
                            });

                            // Show the selected tab content
                            document.getElementById(`${tabName}-tab`).classList.add('active');

                            // Update tab button styles
                            document.querySelectorAll('.tab-button').forEach(button => {
                                button.classList.remove('active');
                            });
                            document.querySelector(`button[onclick="switchTab('${tabName}')"]`).classList.add('active');
                        }

                        // Copy code to clipboard
                        function copyCode(button) {
                            const codeBlock = button.nextElementSibling.textContent;
                            navigator.clipboard.writeText(codeBlock).then(() => {
                                button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                                setTimeout(() => {
                                    button.innerHTML = '<i class="fas fa-copy"></i> Copy';
                                }, 2000);
                            });
                        }
                    </script>

                    <!-- Styles for Tabs and Code Block -->
                    <style>
                        .tab-button {
                            transition: color 0.3s ease;
                        }
                        .tab-button.active {
                            color: #3b82f6; /* Blue-500 */
                            border-bottom: 2px solid #3b82f6;
                        }
                        .tab-content {
                            display: none;
                        }
                        .tab-content.active {
                            display: block;
                        }
                        .code-wrapper {
                            position: relative;
                            background: #f9fafb;
                            border-radius: 0.5rem;
                            padding: 1rem;
                            margin-top: 1rem;
                        }
                        .copy-button {
                            position: absolute;
                            top: 0.5rem;
                            right: 0.5rem;
                            background: #3b82f6;
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border: none;
                            border-radius: 0.25rem;
                            cursor: pointer;
                            transition: background 0.3s ease;
                        }
                        .copy-button:hover {
                            background: #2563eb;
                        }
                        pre {
                            margin: 0;
                            white-space: pre-wrap;
                            word-wrap: break-word;
                        }
                    </style>

                    <section id="v1-track-status">
                        <h2 class="text-2xl font-bold mb-6">Track Payment Status</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to track the status of a payment using the <code>referenceId</code>. The response will include details about the payment, such as its status, amount, and customer information.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X GET "https://example.com/api/v1/payment/track-status/REF12345" \
                    -H "X-Authorization: pk_test_123..." \
                    -H "X-Authorization-Secret: sk_test_456..."</code></pre>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>



                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the payment is found, the response will include the payment details and status information.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "true",
                        "data": {
                            "request_id": "12345",
                            "amount": "100.00",
                            "payment_method": "credit_card",
                            "reference": "REF12345",
                            "cust_name": "John Doe",
                            "cust_phone": "+1234567890",
                            "note": "Test payment",
                            "reject_msg": null,
                            "payment_method_trx": "trx_67890",
                            "status": pending, //pending,rejected,completed
                        }
                    }</code></pre>
                    <p>Details about the possible statuses (e.g., pending, completed, Rejected).</p>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the payment is not found or the <code>referenceId</code> is invalid, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "false",
                        "message": "Data Not found"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>true</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>data</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">object</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Contains the payment details if the request is successful.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>request_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique ID of the payment request.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount of the payment.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>payment_method</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The payment method used (e.g., <code>credit_card</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>reference</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The reference ID provided during payment creation.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>cust_name</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The name of the customer.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>cust_phone</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The phone number of the customer.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>note</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Additional notes provided during payment creation.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>reject_msg</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The rejection message, if the payment was rejected.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>payment_method_trx</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The transaction ID from the payment method.</td>
                                            </tr>

                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>Status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Details about the possible statuses (e.g., <code>pending</code>, <code>Completed</code>, <code>Rejected</code>).</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- <section id="v1-mfs-create">

                        <h5 class="text-2xl font-bold mb-6">Create Withdraw</h5>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to create a cash-in request for a customer. The request will deduct the specified amount from the merchant's balance and initiate a payment to the customer's mobile financial service (MFS) account. The response will include a unique transaction ID (<code>trxid</code>) for tracking the payment status.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X POST "https://example.com/api/v1/mfs/create" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..." \
                        -H "Content-Type: application/json" \
                        -d '{
                            "amount": 100.00,
                            "mfs_operator": "bKash",
                            "cust_number": "+8801712345678"
                        }'</code></pre>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameters are required to create a cash-in request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount to be transferred to the customer's MFS account.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>mfs_operator</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The name of the MFS operator (e.g., <code>bKash</code>, <code>Nagad</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>cust_number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The customer's mobile number registered with the MFS operator.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the cash-in request is successfully created, the response will include the transaction ID (<code>trxid</code>).
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "true",
                        "trxid": "TRX-12345-20231010123456-5678"
                    }</code></pre>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the request fails due to validation errors or insufficient merchant balance, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "false",
                        "message": "Amount is greater than Merchant Balance"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>success</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>trxid</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique transaction ID for tracking the payment status.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>message</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A message describing the error, if the request fails.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <section id="v1-mfs-create" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                        <h5 class="text-2xl font-bold mb-6">Create Withdraw</h5>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to create a cash-in request for a customer. The request will deduct the specified amount from the merchant's balance and initiate a payment to the customer's mobile financial service (MFS) account. The response will include a unique transaction ID (<code>trxid</code>) for tracking the payment status.
                            </p>

                            <!-- Endpoint Section -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Endpoint</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <span class="font-mono text-blue-500">POST</span>
                                    <span class="font-mono text-gray-700">/api/v1/mfs/create</span>
                                </div>
                            </div>

                            <!-- Multi-Language HTTP Request Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">HTTP Request Examples</h3>
                                <!-- Tab Navigation -->
                                <div class="flex space-x-4 border-b border-gray-200 mb-6">
                                    <button class="tab-button active px-4 py-2 text-lg font-semibold text-blue-500" onclick="switchTab('curl')">cURL</button>
                                    <!--
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('php')">PHP</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('python')">Python</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('javascript')">JavaScript</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('csharp')">C#</button>
                                -->
                                </div>

                                <!-- cURL Tab -->
                                <div id="curl-tab" class="tab-content active">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-bash"><code>curl -X POST "http://localhost:8000/api/v1/mfs/create" \
                    -H "Content-Type: application/json" \
                    -d '{
                        "amount": 20,
                        "mfs_operator": "bkash",
                        "cust_number":"0123456789",
                        "withdraw_id":"012458756",
                        "webhook_url": "https://example.com/withdraw-callback"

                    }'</code></pre>
                                    </div>
                                </div>

                                <!-- PHP Tab -->
                                <div id="php-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-php"><code>$curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "http://localhost:8000/api/v1/mfs/create",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => array(
                            "Content-Type: application/json"
                        ),
                        CURLOPT_POSTFIELDS => '{
                            "amount": 100.00,
                            "mfs_operator": "bKash",
                            "cust_number": "+8801712345678",
                             "withdraw_id":"012458756"
                        }'
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    echo $response;</code></pre>
                                    </div>
                                </div>

                                <!-- Python Tab -->
                                <div id="python-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-python"><code>import requests

                    url = "http://localhost:8000/api/v1/mfs/create"
                    headers = {
                        "Content-Type": "application/json"
                    }
                    payload = {
                        "amount": 100.00,
                        "mfs_operator": "bKash",
                        "cust_number": "+8801712345678",
                         "withdraw_id":"012458756"
                    }

                    response = requests.post(url, json=payload, headers=headers)
                    print(response.json())</code></pre>
                                    </div>
                                </div>

                                <!-- JavaScript Tab -->
                                <div id="javascript-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-javascript"><code>const url = "http://localhost:8000/api/v1/mfs/create";
                    const headers = {
                        "Content-Type": "application/json"
                    };
                    const payload = {
                        amount: 100.00,
                        mfs_operator: "bKash",
                        cust_number: "+8801712345678",
                         withdraw_id:"012458756"
                    };

                    fetch(url, {
                        method: "POST",
                        headers: headers,
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => console.log(data))
                    .catch(error => console.error(error));</code></pre>
                                    </div>
                                </div>

                                <!-- C# Tab -->
                                <div id="csharp-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-csharp"><code>using System;
                    using System.Net.Http;
                    using System.Text;
                    using System.Threading.Tasks;

                    class Program
                    {
                        static async Task Main(string[] args)
                        {
                            var url = "http://localhost:8000/api/v1/mfs/create";
                            var payload = new
                            {
                                amount = 100.00,
                                mfs_operator = "bKash",
                                cust_number = "+8801712345678",
                                 withdraw_id ="012458756"
                            };
                            var headers = new
                            {
                                Content_Type = "application/json"
                            };

                            using (var client = new HttpClient())
                            {
                                client.DefaultRequestHeaders.Add("Content-Type", headers.Content_Type);

                                var content = new StringContent(Newtonsoft.Json.JsonConvert.SerializeObject(payload), Encoding.UTF8, "application/json");
                                var response = await client.PostAsync(url, content);
                                var result = await response.Content.ReadAsStringAsync();
                                Console.WriteLine(result);
                            }
                        }
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameters are required to create a cash-in request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount to be transferred to the customer's MFS account.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>mfs_operator</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The name of the MFS operator (e.g., <code>bKash</code>, <code>Nagad</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>cust_number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The customer's mobile number registered with the MFS operator.</td>
                                            </tr>
                                              <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>withdraw_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">withdraw_id</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The withdraw_id is unique.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the cash-in request is successfully created, the response will include the transaction ID (<code>trxid</code>).
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json"><code>{
                        "status": "success",
                        "trxid": "TRX-12345-20231010123456-5678"
                    }</code></pre>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the request fails due to validation errors or insufficient merchant balance, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json"><code>{
                        "status": "false",
                        "message": "Amount is greater than Merchant Balance"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>success</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>trxid</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique transaction ID for tracking the payment status.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>message</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A message describing the error, if the request fails.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- JavaScript for Tabs and Copy Functionality -->
                    <script>
                        // Switch between tabs
                        function switchTab(tabName) {
                            // Hide all tab contents
                            document.querySelectorAll('.tab-content').forEach(tab => {
                                tab.classList.remove('active');
                            });

                            // Show the selected tab content
                            document.getElementById(`${tabName}-tab`).classList.add('active');

                            // Update tab button styles
                            document.querySelectorAll('.tab-button').forEach(button => {
                                button.classList.remove('active');
                            });
                            document.querySelector(`button[onclick="switchTab('${tabName}')"]`).classList.add('active');
                        }

                        // Copy code to clipboard
                        function copyCode(button) {
                            const codeBlock = button.nextElementSibling.textContent;
                            navigator.clipboard.writeText(codeBlock).then(() => {
                                button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                                setTimeout(() => {
                                    button.innerHTML = '<i class="fas fa-copy"></i> Copy';
                                }, 2000);
                            });
                        }
                    </script>

                    <!-- Styles for Tabs and Code Block -->
                    <style>
                        .tab-button {
                            transition: color 0.3s ease;
                        }
                        .tab-button.active {
                            color: #3b82f6; /* Blue-500 */
                            border-bottom: 2px solid #3b82f6;
                        }
                        .tab-content {
                            display: none;
                        }
                        .tab-content.active {
                            display: block;
                        }
                        .code-wrapper {
                            position: relative;
                            background: #f9fafb;
                            border-radius: 0.5rem;
                            padding: 1rem;
                            margin-top: 1rem;
                        }
                        .copy-button {
                            position: absolute;
                            top: 0.5rem;
                            right: 0.5rem;
                            background: #3b82f6;
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border: none;
                            border-radius: 0.25rem;
                            cursor: pointer;
                            transition: background 0.3s ease;
                        }
                        .copy-button:hover {
                            background: #2563eb;
                        }
                        pre {
                            margin: 0;
                            white-space: pre-wrap;
                            word-wrap: break-word;
                        }
                    </style>

                    {{-- <section id="v1-status-check-mfs">
                        <h2 class="text-2xl font-bold mb-6">Check Transaction Status</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to check the status of a transaction using the <code>trnx_id</code>. The response will include details about the transaction, such as its status, amount, and associated MFS information.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X POST "https://example.com/api/v1/mfs/status_check" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..." \
                        -H "Content-Type: application/json" \
                        -d '{
                            "trnx_id": "TRX-12345-20231010123456-5678"
                        }'</code></pre>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameters are required to check the transaction status:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>trnx_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique transaction ID to check the status for.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the transaction is found, the response will include the transaction details and status.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                       {
    "status": "true",
    "data": {
        "withdraw_number": "01818898189",
        "mfs_operator": "bkash",
        "amount": "20",
        "msg": "[Pop-up window, Carrier info, Amount too low to transact., OK]",
        "status": "pending"
    }
}
                    }</code></pre>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the transaction is not found or the <code>trnx_id</code> is invalid, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "false",
                        "message": "This TRXID not available"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>success</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>data</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">object</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Contains the transaction details if the request is successful.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The customer's mobile number associated with the transaction.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>mfs</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The MFS operator used for the transaction (e.g., <code>bKash</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount of the transaction.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>msg</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The transaction ID provided in the request.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The current status of the transaction (<code>pending</code>, <code>success</code>, or <code>rejected</code>).</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section> --}}

                    <section id="v1-status-check-mfs" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                        <h2 class="text-2xl font-bold mb-6">Check Transaction Status</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to check the status of a transaction using the <code>trnx_id</code>. The response will include details about the transaction, such as its status, amount, and associated MFS information.
                            </p>

                            <!-- Endpoint Section -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Endpoint</h3>
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <span class="font-mono text-blue-500">POST</span>
                                    <span class="font-mono text-gray-700">/api/v1/mfs/status_check</span>
                                </div>
                            </div>

                            <!-- Multi-Language HTTP Request Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">HTTP Request Examples</h3>
                                <!-- Tab Navigation -->
                                <div class="flex space-x-4 border-b border-gray-200 mb-6">
                                    <button class="tab-button active px-4 py-2 text-lg font-semibold text-blue-500" onclick="switchTab('curl')">cURL</button>
                                   <!--
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('php')">PHP</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('python')">Python</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('javascript')">JavaScript</button>
                                    <button class="tab-button px-4 py-2 text-lg font-semibold text-gray-500" onclick="switchTab('csharp')">C#</button>
                               -->
                                </div>

                                <!-- cURL Tab -->
                                <div id="curl-tab" class="tab-content active">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-bash"><code>curl -X POST "https://example.com/api/v1/mfs/status_check" \
                    -H "X-Authorization: pk_test_123..." \
                    -H "X-Authorization-Secret: sk_test_456..." \
                    -H "Content-Type: application/json" \
                    -d '{
                        "trnx_id": "TRX-12345-20231010123456-5678"
                    }'</code></pre>
                                    </div>
                                </div>

                                <!-- PHP Tab -->
                                <div id="php-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-php"><code>$curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => "https://example.com/api/v1/mfs/status_check",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_HTTPHEADER => array(
                            "X-Authorization: pk_test_123...",
                            "X-Authorization-Secret: sk_test_456...",
                            "Content-Type: application/json"
                        ),
                        CURLOPT_POSTFIELDS => '{
                            "trnx_id": "TRX-12345-20231010123456-5678"
                        }'
                    ));

                    $response = curl_exec($curl);
                    curl_close($curl);
                    echo $response;</code></pre>
                                    </div>
                                </div>

                                <!-- Python Tab -->
                                <div id="python-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-python"><code>import requests

                    url = "https://example.com/api/v1/mfs/status_check"
                    headers = {
                        "X-Authorization": "pk_test_123...",
                        "X-Authorization-Secret": "sk_test_456...",
                        "Content-Type": "application/json"
                    }
                    payload = {
                        "trnx_id": "TRX-12345-20231010123456-5678"
                    }

                    response = requests.post(url, json=payload, headers=headers)
                    print(response.json())</code></pre>
                                    </div>
                                </div>

                                <!-- JavaScript Tab -->
                                <div id="javascript-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-javascript"><code>const url = "https://example.com/api/v1/mfs/status_check";
                    const headers = {
                        "X-Authorization": "pk_test_123...",
                        "X-Authorization-Secret": "sk_test_456...",
                        "Content-Type": "application/json"
                    };
                    const payload = {
                        trnx_id: "TRX-12345-20231010123456-5678"
                    };

                    fetch(url, {
                        method: "POST",
                        headers: headers,
                        body: JSON.stringify(payload)
                    })
                    .then(response => response.json())
                    .then(data => console.log(data))
                    .catch(error => console.error(error));</code></pre>
                                    </div>
                                </div>

                                <!-- C# Tab -->
                                <div id="csharp-tab" class="tab-content">
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-csharp"><code>using System;
                    using System.Net.Http;
                    using System.Text;
                    using System.Threading.Tasks;

                    class Program
                    {
                        static async Task Main(string[] args)
                        {
                            var url = "https://example.com/api/v1/mfs/status_check";
                            var payload = new
                            {
                                trnx_id = "TRX-12345-20231010123456-5678"
                            };
                            var headers = new
                            {
                                X_Authorization = "pk_test_123...",
                                X_Authorization_Secret = "sk_test_456...",
                                Content_Type = "application/json"
                            };

                            using (var client = new HttpClient())
                            {
                                client.DefaultRequestHeaders.Add("X-Authorization", headers.X_Authorization);
                                client.DefaultRequestHeaders.Add("X-Authorization-Secret", headers.X_Authorization_Secret);
                                client.DefaultRequestHeaders.Add("Content-Type", headers.Content_Type);

                                var content = new StringContent(Newtonsoft.Json.JsonConvert.SerializeObject(payload), Encoding.UTF8, "application/json");
                                var response = await client.PostAsync(url, content);
                                var result = await response.Content.ReadAsStringAsync();
                                Console.WriteLine(result);
                            }
                        }
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameters are required to check the transaction status:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>trnx_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique transaction ID to check the status for.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the transaction is found, the response will include the transaction details and status.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json"><code>{
                        {
    "status": "true",
    "data": {
        "withdraw_number": "01448898189",
        "mfs_operator": "bkash",
        "amount": "20",
        "msg": "[ Carrier info, Amount too low to transact., OK]",
        "status": "pending"
    }

                    }</code></pre>

                   <p> Details about the possible statuses (e.g., pending, success, rejected). </p>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the transaction is not found or the <code>trnx_id</code> is invalid, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json"><code>{
                        "status": "false",
                        "message": "This TRXID not available"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>success</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>data</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">object</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Contains the transaction details if the request is successful.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The customer's mobile number associated with the transaction.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>mfs</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The MFS operator used for the transaction (e.g., <code>bKash</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount of the transaction.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>msg</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The transaction ID provided in the request.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The current status of the transaction (<code>pending</code>, <code>success</code>, or <code>rejected</code>).</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- JavaScript for Tabs and Copy Functionality -->
                    <script>
                        // Switch between tabs
                        function switchTab(tabName) {
                            // Hide all tab contents
                            document.querySelectorAll('.tab-content').forEach(tab => {
                                tab.classList.remove('active');
                            });

                            // Show the selected tab content
                            document.getElementById(`${tabName}-tab`).classList.add('active');

                            // Update tab button styles
                            document.querySelectorAll('.tab-button').forEach(button => {
                                button.classList.remove('active');
                            });
                            document.querySelector(`button[onclick="switchTab('${tabName}')"]`).classList.add('active');
                        }

                        // Copy code to clipboard
                        function copyCode(button) {
                            const codeBlock = button.nextElementSibling.textContent;
                            navigator.clipboard.writeText(codeBlock).then(() => {
                                button.innerHTML = '<i class="fas fa-check"></i> Copied!';
                                setTimeout(() => {
                                    button.innerHTML = '<i class="fas fa-copy"></i> Copy';
                                }, 2000);
                            });
                        }
                    </script>

                    <!-- Styles for Tabs and Code Block -->
                    <style>
                        .tab-button {
                            transition: color 0.3s ease;
                        }
                        .tab-button.active {
                            color: #3b82f6; /* Blue-500 */
                            border-bottom: 2px solid #3b82f6;
                        }
                        .tab-content {
                            display: none;
                        }
                        .tab-content.active {
                            display: block;
                        }
                        .code-wrapper {
                            position: relative;
                            background: #f9fafb;
                            border-radius: 0.5rem;
                            padding: 1rem;
                            margin-top: 1rem;
                        }
                        .copy-button {
                            position: absolute;
                            top: 0.5rem;
                            right: 0.5rem;
                            background: #3b82f6;
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border: none;
                            border-radius: 0.25rem;
                            cursor: pointer;
                            transition: background 0.3s ease;
                        }
                        .copy-button:hover {
                            background: #2563eb;
                        }
                        pre {
                            margin: 0;
                            white-space: pre-wrap;
                            word-wrap: break-word;
                        }
                    </style>

                    {{-- <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">3. Initialize example</h3>
                        <div class="code-wrapper">
                            <button class="copy-button" onclick="copyCode(this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <pre class="language-javascript rounded-lg"><code>import { PayGate } from '@paygate/js';

const paygate = new PayGate('your_public_key');

// Create a payment
const payment = await paygate.createPayment({
    amount: 1000, // Amount in cents
    currency: 'USD',
    description: 'Test payment'
});</code></pre>
                        </div>
                    </div> --}}
                </section>


                <!-- Payment Integration Section -->
                <section id="payment-integration" class="bg-white rounded-xl shadow-sm p-8 border border-gray-100">
                    <h2 class="text-2xl font-bold mb-6">H2H Payment Integration</h2>

                     <p>
        With H2H, merchants can also create their own payment iframe page under their own branding, giving customers a seamless payment
        experience inside the merchant’s platform.
    </p>

                    <section id="v2-introduction">
    <h2>H2H Payment Introduction</h2>

    <h3>Payment Flow</h3>
    <ul style="line-height: 1.8; margin-bottom: 20px;">
        <li>
            <strong>Check Available Methods</strong> –
            First, check which payment methods are currently available from the system’s <em>Available Methods API</em>.
        </li>

        <li>
            <strong>User Chooses a Method</strong> –
            The user selects a preferred payment method (e.g., bKash, Nagad, Rocket, Upay) and a deposit number from the available list.
        </li>

        <li>
            <strong>Send Payment Request</strong> –
            After selection, the user sends a payment request including the method, deposit number, amount, reference ID, and callback URL.
        </li>

        <li>
            <strong>System Creates Payment Request</strong> –
            The system validates the request, creates a new payment record, and returns the status with both a callback URL and a webhook URL.
        </li>
    </ul>

    <p style="margin-top: 25px;">
        H2H (Host-to-Host) payment integration connects businesses directly with mobile financial services like bKash, Nagad, Rocket, and Upay.
        It enables secure server-to-server communication so merchants can automate deposits and withdrawals in real time.
    </p>



    <p>
        iPaybd supports this by providing a unique deposit number for each merchant, which can be shown inside the merchant’s custom iframe or application.
    </p>
</section>



                    <section id="v2-available-method">
                        <h2 class="text-2xl font-bold mb-6">Get Available Payment Methods (v2)</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to retrieve a list of available payment methods (MFS operators) along with their associated SIM numbers and icons. This endpoint is part of the <strong>v2 API</strong> and requires authentication via <code>X-Authorization</code> and <code>X-Authorization-Secret</code> headers.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X GET "https://example.com/api/v2/payment/available-method" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..."</code></pre>
                                </div>
                            </div>

                            <!-- Request Headers -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Headers</h3>
                                <p class="text-gray-700 mb-4">
                                    The following headers are required to authenticate the request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Header</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API public key for authentication.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization-Secret</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API secret key for authentication.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below is an example of the response you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        The response includes a list of available payment methods, their associated SIM numbers, and icons.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>[
    {
        "deposit_method": "nagad",
        "deposit_number": "018xxx33742",
        "icon": "https://ibotbd.com/payments/nagad.png",
        "type": "P2C"
    },
    {
        "deposit_method": "upay",
        "deposit_number": "0132xxx6627",
        "icon": "https://ibotbd.com/payments/upay.png",
        "type": "P2A"
    },
    {
        "deposit_method": "bkash",
        "deposit_number": "0163xxx9900",
        "icon": "https://ibotbd.com/payments/bkash.png",
        "type": "P2C"
    },
    {
        "deposit_method": "rocket",
        "deposit_number": "01xxx8164207",
        "icon": "https://ibotbd.com/payments/rocket.png",
        "type": "P2C"
    }
]</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>deposit_method</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The name of the payment method (e.g., <code>bkash</code>, <code>nagad</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>deposit_number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The SIM number associated with the payment method.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>icon</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The URL of the icon representing the payment method.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>Type</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The String of the type representing the type that means merchant or agent.</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section id="v2-create-payment">
                        <h2 class="text-2xl font-bold mb-6">Create Payment (v2)</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to create a payment request. This endpoint is part of the <strong>v2 API</strong> and requires authentication via <code>X-Authorization</code> and <code>X-Authorization-Secret</code> headers. The payment request will be processed based on the provided details, such as amount, customer information, and payment method.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example(For P2A)</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X POST "https://example.com/api/v2/payment/create-payment" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..." \
                        -H "Content-Type: application/json" \
                        -d '{
                                "amount": 50,
                                "reference": "Rvx99sS99",
                                "currency": "BDT",
                                "callback_url": "https://example.com/callback",
                                "transaction_id": "01JYG4GET7",
                                "from_number": "01712345678",
                                "payment_method":"rocket",
                                "deposit_number":"013358164207"
                                "type":"P2A"
                                }'</code></pre>
                                </div>
                            </div>

                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example (For P2C)</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X POST "https://example.com/api/v2/payment/create-payment" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..." \
                        -H "Content-Type: application/json" \
                        -d '{
                                "amount": 50,
                                "reference": "Rvx99sS99",
                                "currency": "BDT",
                                "callback_url": "https://example.com/callback",
                                "payment_method":"bkash",
                                "deposit_number":"013358164207",
                                "type":"P2C"
                                }'</code></pre>
                                </div>
                            </div>

                            <!-- Request Headers -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Headers</h3>
                                <p class="text-gray-700 mb-4">
                                    The following headers are required to authenticate the request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Header</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API public key for authentication.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization-Secret</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API secret key for authentication.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameters are required to create a payment request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount of the payment.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>reference</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A unique reference ID for the payment (3-20 characters).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>currency</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The currency of the payment (e.g., <code>BDT</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>callback_url</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The URL to receive payment status updates.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>transaction_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A unique transaction ID (8-10 alphanumeric characters).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>from_number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The sender's phone number.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>payment_method</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The payment method (e.g., <code>bkash</code>, <code>nagad</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>deposit_number</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The SIM number associated with the payment method.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>type</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">This indicates which type customer wants to select P2A reffer Partner to Agent P2C define Pertner to Customer.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>
                                </div>

                                <p> this is for P2A response </p>

                                <pre class="language-bash rounded-lg"><code>{

    "status": "pending",
    "message": "Deposit is processing, check status using track or webhook_url | জমা প্রক্রিয়াধীন, অনুগ্রহপূর্বক অপেক্ষা করুন",
    "callback": "https://example.com/callback?status=pending&reference=Rvx99ssS99&transaction_id=01JYG4GET7&payment_method=rocket"
}</code></pre>
<p>Details about the possible statuses (e.g., pending, completed, Rejected).</p>

                                <!-- Success Response -->

                                <p> This is for P2C response </p>

                                 <pre class="language-bash rounded-lg"><code>{
    "status": true,
    "message": "Data submitted successfully ",
    "data": {
        "sim_id": "01871168733",
        "amount": 10,
        "payment_method": "api_bkash",
        "reference": "turag05",
        "currency": "BDT",
        "callback_url": "https://google.com/",
        "cust_name": null,
        "issue_time": "2025-09-17 21:22:20"
    },
    "type": "P2C",
    "URL": "https://payment.bkash.com/?paymentId=TR0011sOIQxlE1758136940367&hash=YRWvoY.Y1l(YDq_dS9C!6yMVN6WoxgqMwtx1zH)GiFLgEh*POv4.-dmjttRcJnImyTsMDknDDlHHOx(_.n.6f)FFnty)AaVVZeFX1758136940367&mode=0011&apiVersion=v1.2.0-beta/"
}</code></pre>




                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The status of the payment request (<code>success</code>, <code>pending</code>, or <code>error</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>message</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A message describing the result of the request.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>Callback Url</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">object</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">A list of possible statuses for the payment request.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="code-wrapper mt-4">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-json rounded-lg"><code>===========Payment Callback Handler===========
Handle callback URL parameters
Sample Success URL:
http://127.0.0.1:5500/?payment=success&payment_method=rocket&
request_id=7dc7ae27c564e9bffcc72d3d3f10fc69b50998fe24ff0cd566&reference
=ref_usx60cisvas8886&sim_id=01893087273&trxid=DR123456&amount=50

Sample Cancel URL:
http://127.0.0.1:5500/?payment=Cancelled

Status codes:
payment = success
payment = rejected
payment = Cancelled
payment = pending

</code></pre>
                                </div>
                        </div>
                    </section>

                    <section id="v2-status-track">
                        <h2 class="text-2xl font-bold mb-6">Track Payment Status (v2)</h2>
                        <div class="mb-8">
                            <p class="text-gray-700 mb-4">
                                Use this endpoint to track the status of a payment using the <code>referenceId</code>. This endpoint is part of the <strong>v2 API</strong> and requires authentication via <code>X-Authorization</code> and <code>X-Authorization-Secret</code> headers. The response will include details about the payment, such as its status, amount, and customer information.
                            </p>

                            <!-- cURL Request Example -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">cURL Request Example</h3>
                                <div class="code-wrapper">
                                    <button class="copy-button" onclick="copyCode(this)">
                                        <i class="fas fa-copy"></i> Copy
                                    </button>
                                    <pre class="language-bash rounded-lg"><code>curl -X GET "https://example.com/api/v2/payment/status-track/REF12345" \
                        -H "X-Authorization: pk_test_123..." \
                        -H "X-Authorization-Secret: sk_test_456..."</code></pre>
                                </div>
                            </div>

                            <!-- Request Headers -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Headers</h3>
                                <p class="text-gray-700 mb-4">
                                    The following headers are required to authenticate the request:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Header</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API public key for authentication.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>X-Authorization-Secret</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Your API secret key for authentication.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Request Parameters -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Request Parameters</h3>
                                <p class="text-gray-700 mb-4">
                                    The following parameter is required to track the payment status:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>referenceId</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique reference ID of the payment to track.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Response Examples -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Examples</h3>
                                <p class="text-gray-700 mb-4">
                                    Below are examples of the responses you might receive when calling this endpoint:
                                </p>

                                <!-- Success Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Success Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the payment is found, the response will include the payment details and status information.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
    "status": "true",
    "data": {
        "request_id": "4305381816f2515178de5b02b3b819551ca437a75f98b966d3",
        "amount": 50,
        "payment_method": "rocket",
        "reference": "Rvx99sS99",
        "cust_name": null,
        "cust_phone": null,
        "note": null,
        "reject_msg": null,
        "payment_method_trx": "01JYG4GET7",
        "status": "pending"
    }
}</code></pre>

<p> Status can be pending, success , rejected </p>
                                    </div>
                                </div>

                                <!-- Error Response -->
                                <div class="mb-6">
                                    <h4 class="text-lg font-semibold mb-2">Error Response</h4>
                                    <p class="text-gray-700 mb-2">
                                        If the payment is not found or the <code>referenceId</code> is invalid, the response will indicate the error.
                                    </p>
                                    <div class="code-wrapper">
                                        <button class="copy-button" onclick="copyCode(this)">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                        <pre class="language-json rounded-lg"><code>{
                        "status": "false",
                        "message": "Data Not found"
                    }</code></pre>
                                    </div>
                                </div>
                            </div>

                            <!-- Explanation of Response Fields -->
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold mb-4">Response Field Details</h3>
                                <p class="text-gray-700 mb-4">
                                    Here's a breakdown of the fields in the response:
                                </p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white border border-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Field</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Type</th>
                                                <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Indicates whether the request was successful (<code>true</code>) or not (<code>false</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>data</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">object</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Contains the payment details if the request is successful.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>request_id</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The unique ID of the payment request.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>amount</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The amount of the payment.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>payment_method</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The payment method used (e.g., <code>bkash</code>, <code>nagad</code>).</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>reference</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The reference ID provided during payment creation.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>cust_name</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The name of the customer.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>note</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">Additional notes provided during payment creation.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>reject_msg</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The rejection message, if the payment was rejected.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>payment_method_trx</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">string</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The transaction ID from the payment method.</td>
                                            </tr>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-700"><code>status</code></td>
                                                <td class="px-4 py-2 text-sm text-gray-700">numeric</td>
                                                <td class="px-4 py-2 text-sm text-gray-700">The current status of the payment.</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Basic Payment Form</h3>
                        <div class="code-wrapper">
                            <button class="copy-button" onclick="copyCode(this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <pre class="language-html rounded-lg"><code>&lt;form id="payment-form"&gt;
    &lt;div class="form-group"&gt;
        &lt;label&gt;Card Number&lt;/label&gt;
        &lt;div id="card-number"&gt;&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="form-group"&gt;
        &lt;label&gt;Expiry Date&lt;/label&gt;
        &lt;div id="card-expiry"&gt;&lt;/div&gt;
    &lt;/div&gt;
    &lt;div class="form-group"&gt;
        &lt;label&gt;CVC&lt;/label&gt;
        &lt;div id="card-cvc"&gt;&lt;/div&gt;
    &lt;/div&gt;
    &lt;button type="submit"&gt;Pay Now&lt;/button&gt;
&lt;/form&gt;</code></pre>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold mb-4">Handle Payment Submission</h3>
                        <div class="code-wrapper">
                            <button class="copy-button" onclick="copyCode(this)">
                                <i class="fas fa-copy"></i> Copy
                            </button>
                            <pre class="language-javascript rounded-lg"><code>const form = document.getElementById('payment-form');

form.addEventListener('submit', async (event) => {
    event.preventDefault();

    const { token, error } = await paygate.createToken();

    if (error) {
        console.error(error);
        return;
    }

    // Send token to your server
    const response = await fetch('/api/payment', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ token: token.id })
    });
});</code></pre>
                        </div>
                    </div> --}}
                </section>
            </div>
        </div>
    </div>
@endsection
