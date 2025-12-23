@extends('welcome')
@section('customer')
    <!-- Hero Section -->
    <div class="relative pt-10 bg-gradient-to-b from-indigo-50 via-white to-white overflow-hidden">
        <!-- Smaller Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div
                class="absolute top-0 left-0 w-40 h-40 bg-gradient-to-r from-indigo-100 to-blue-100 rounded-full mix-blend-multiply filter blur-lg opacity-25 animate-blob">
            </div>
            <div
                class="absolute top-20 right-0 w-40 h-40 bg-gradient-to-r from-green-100 to-emerald-100 rounded-full mix-blend-multiply filter blur-lg opacity-25 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute bottom-0 left-1/4 w-40 h-40 bg-gradient-to-r from-pink-100 to-purple-100 rounded-full mix-blend-multiply filter blur-lg opacity-25 animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <!-- Left Content -->
                <div class="relative z-10">
                    <div class="inline-flex items-center px-4 py-2 bg-indigo-50 rounded-full mb-6">
                        <span class="flex h-2 w-2 bg-indigo-600 rounded-full mr-2"></span>
                        <span class="text-sm font-medium text-indigo-600">Trusted Payment Gateway</span>
                    </div>

                    <h1 class="text-4xl md:text-6xl font-bold text-gray-900 mb-6 leading-tight">
                        Accept <span class="relative">
                            <span class="relative z-10 text-indigo-600">Payments</span>
                            <span
                                class="absolute bottom-0 left-0 w-full h-3 bg-indigo-100 -z-10 transform -rotate-1"></span>
                        </span> & Process <span class="relative">
                            <span class="relative z-10 text-green-600">Withdrawals</span>
                            <span class="absolute bottom-0 left-0 w-full h-3 bg-green-100 -z-10 transform -rotate-1"></span>
                        </span>
                    </h1>

                    <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                        Experience seamless transactions with our comprehensive payment gateway. Instant mobile banking
                        solutions for your business growth.
                    </p>

                    <!-- Features List -->
                    <div class="space-y-4 mb-8">
                        <div
                            class="flex items-center space-x-3 transform hover:translate-x-2 transition-transform duration-300">
                            <div class="flex-shrink-0 w-10 h-10 bg-indigo-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-check text-indigo-600"></i>
                            </div>
                            <p class="text-gray-700 font-medium">Support for all major mobile banking services</p>
                        </div>
                        <div
                            class="flex items-center space-x-3 transform hover:translate-x-2 transition-transform duration-300">
                            <div class="flex-shrink-0 w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-bolt text-green-600"></i>
                            </div>
                            <p class="text-gray-700 font-medium">Instant processing within Seconds</p>
                        </div>
                        <div
                            class="flex items-center space-x-3 transform hover:translate-x-2 transition-transform duration-300">
                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                                <i class="fas fa-shield-alt text-blue-600"></i>
                            </div>
                            <p class="text-gray-700 font-medium">Secure and encrypted transactions</p>
                        </div>
                    </div>

                    <!-- CTA Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="window.location.href='{{ route('customer.view_create_account') }}'"
                            class="group relative px-8 py-4 rounded-xl bg-gradient-to-r from-indigo-600 to-indigo-700 text-white font-semibold shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                            <span class="relative z-10">Become a Merchant</span>
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-all duration-500">
                            </div>
                        </button>
                        <button onclick="window.location.href='{{ route('develop_docs') }}'"
                            class="px-8 py-4 rounded-xl border-2 border-indigo-600 text-indigo-600 font-semibold hover:bg-indigo-50 transition-all duration-300 flex items-center justify-center space-x-2">
                            <span>View Documentation</span>
                            <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                        </button>
                    </div>
                </div>

                <!-- Right Content - Payment Methods Preview -->
                <div class="relative lg:ml-12">
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-green-50 rounded-3xl transform rotate-3">
                    </div>
                    <div class="relative bg-white p-8 rounded-3xl shadow-xl">
                        <!-- Payment Methods Grid -->
                        <div class="grid grid-cols-2 gap-6">
                            <!-- bKash Preview -->
                            <div
                                class="group flex items-center p-4 bg-pink-50/50 rounded-xl hover:bg-pink-50 transition-colors duration-300">
                                <div
                                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow transition-shadow duration-300">
                                    <img src="{{ asset('payments/bkash.png') }}" alt="bKash"
                                        class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">bKash</h5>
                                    <p class="text-sm text-pink-600">Most Popular</p>
                                </div>
                            </div>
                            <!-- Nagad Preview -->
                            <div
                                class="group flex items-center p-4 bg-orange-50/50 rounded-xl hover:bg-orange-50 transition-colors duration-300">
                                <div
                                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow transition-shadow duration-300">
                                    <img src="{{ asset('payments/nagad.png') }}" alt="Nagad"
                                        class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Nagad</h5>
                                    <p class="text-sm text-orange-600">Fast Processing</p>
                                </div>
                            </div>
                            <!-- Rocket Preview -->
                            <div
                                class="group flex items-center p-4 bg-blue-50/50 rounded-xl hover:bg-blue-50 transition-colors duration-300">
                                <div
                                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow transition-shadow duration-300">
                                    <img src="{{ asset('payments/rocket.png') }}" alt="Rocket"
                                        class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Rocket</h5>
                                    <p class="text-sm text-blue-600">Quick Transfer</p>
                                </div>
                            </div>
                            <!-- Upay Preview -->
                            <div
                                class="group flex items-center p-4 bg-purple-50/50 rounded-xl hover:bg-purple-50 transition-colors duration-300">
                                <div
                                    class="w-12 h-12 bg-white rounded-lg flex items-center justify-center mr-4 shadow-sm group-hover:shadow transition-shadow duration-300">
                                    <img src="{{ asset('payments/upay.png') }}" alt="Upay"
                                        class="w-8 h-8 object-contain group-hover:scale-110 transition-transform duration-300">
                                </div>
                                <div>
                                    <h5 class="font-semibold text-gray-900">Upay</h5>
                                    <p class="text-sm text-purple-600">Easy Payment</p>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Preview -->
                        <div class="mt-8 grid grid-cols-2 gap-4">
                            <div class="text-center p-4 bg-gradient-to-br from-indigo-50 to-indigo-100/50 rounded-xl">
                                <i class="fas fa-bolt text-indigo-600 text-3xl mb-2"></i>
                                <h3 class="text-2xl font-bold text-indigo-600">Instant</h3>
                                <p class="text-sm text-gray-600">Processing</p>
                            </div>
                            <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100/50 rounded-xl">
                                <i class="fas fa-headset text-green-600 text-3xl mb-2"></i>
                                <h3 class="text-2xl font-bold text-green-600">24/7</h3>
                                <p class="text-sm text-gray-600">Support Available</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trusted By Section -->
            <div class="mt-20 text-center relative z-10">
                <div class="max-w-7xl mx-auto px-4">
                    <!-- Section Header with Animation -->
                    <div class="mb-12 relative">
                        <span
                            class="inline-block px-4 py-1 bg-indigo-50 rounded-full text-indigo-600 text-sm font-semibold mb-3">Our
                            Growing Community</span>
                        <h3 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Online Payment Solutions</h3>
                        <h4 class="text-2xl font-bold text-indigo-600 mb-4">For Any Industry, Service and Website</h4>
                        <p class="text-xl text-gray-600 max-w-2xl mx-auto">For all types of businesses - join thousands of
                            successful companies that trust our payment solutions</p>
                        <div class="absolute -top-10 -right-10 w-20 h-20 bg-indigo-50 rounded-full opacity-20"></div>
                        <div class="absolute -bottom-10 -left-10 w-32 h-32 bg-green-50 rounded-full opacity-20"></div>
                    </div>

                    <!-- Business Types Grid with Enhanced Design -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-16">
                        <!-- Enterprise -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-building text-3xl text-indigo-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-indigo-600 transition-colors">
                                    Enterprise</h4>
                                <p class="text-gray-500">Large scale solutions for enterprise businesses of any type</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-indigo-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>

                        <!-- E-commerce -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-purple-50 to-purple-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-shopping-bag text-3xl text-purple-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-purple-600 transition-colors">
                                    E-commerce</h4>
                                <p class="text-gray-500">Integrated online store payment solutions</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-purple-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>

                        <!-- Gaming -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-red-50 to-red-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-gamepad text-3xl text-red-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-red-600 transition-colors">
                                    Gaming</h4>
                                <p class="text-gray-500">Secure payments for gaming and esports platforms</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-red-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>

                        <!-- Trading -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-green-50 to-green-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-chart-line text-3xl text-green-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                                    Trading</h4>
                                <p class="text-gray-500">Fast and secure trading payment solutions</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-green-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>

                        <!-- Forex -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-exchange-alt text-3xl text-yellow-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-yellow-600 transition-colors">
                                    Forex</h4>
                                <p class="text-gray-500">Reliable forex trading payment processing</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-yellow-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sports -->
                        <div
                            class="group p-8 bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
                            <div class="flex flex-col items-center">
                                <div
                                    class="w-20 h-20 bg-gradient-to-br from-blue-50 to-blue-100 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                                    <i class="fas fa-futbol text-3xl text-blue-600"></i>
                                </div>
                                <h4
                                    class="text-xl font-bold text-gray-900 mb-2 group-hover:text-blue-600 transition-colors">
                                    Sports</h4>
                                <p class="text-gray-500">Instant sports betting payment solutions</p>
                                <div class="mt-4 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-blue-600 font-medium">Learn more →</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Enhanced Stats Section -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-8 bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
                        <div class="p-4 text-center">
                            <div
                                class="text-4xl font-bold text-indigo-600 mb-2 group-hover:scale-110 transition-transform">
                                10K+</div>
                            <p class="text-gray-600 font-medium">Active Users</p>
                            <div class="w-12 h-1 bg-indigo-100 mx-auto mt-3"></div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-4xl font-bold text-green-600 mb-2 group-hover:scale-110 transition-transform">
                                99.9%</div>
                            <p class="text-gray-600 font-medium">Uptime</p>
                            <div class="w-12 h-1 bg-green-100 mx-auto mt-3"></div>
                        </div>
                        <div class="p-4 text-center">
                            <div
                                class="text-4xl font-bold text-purple-600 mb-2 group-hover:scale-110 transition-transform">
                                50M+</div>
                            <p class="text-gray-600 font-medium">Transactions</p>
                            <div class="w-12 h-1 bg-purple-100 mx-auto mt-3"></div>
                        </div>
                        <div class="p-4 text-center">
                            <div class="text-4xl font-bold text-blue-600 mb-2 group-hover:scale-110 transition-transform">
                                15+</div>
                            <p class="text-gray-600 font-medium">Countries</p>
                            <div class="w-12 h-1 bg-blue-100 mx-auto mt-3"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment & Withdraw Section -->
    <div id="payment-withdraw" class="py-20 relative overflow-hidden">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-indigo-50 via-white to-green-50">
            <div
                class="absolute top-0 left-0 w-72 h-72 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob">
            </div>
            <div
                class="absolute top-0 right-0 w-72 h-72 bg-green-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000">
            </div>
            <div
                class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000">
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <!-- Enhanced Header -->
            <div class="text-center mb-16">
                <div class="inline-flex items-center justify-center space-x-2 mb-4">
                    <span class="h-px w-8 bg-indigo-600"></span>
                    <span class="px-4 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm font-semibold">Payment
                        Gateway</span>
                    <span class="h-px w-8 bg-indigo-600"></span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold mb-6 animated-gradient-text">Payment & Withdraw Solutions</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    Experience seamless transactions with our secure payment gateway. Send and receive money instantly
                    through trusted mobile banking services.
                </p>
            </div>

            <!-- Cards Container with Enhanced Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 xl:gap-12">
                <!-- Payment Solutions Card -->
                <div
                    class="glass-effect p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100/20 group">
                    <!-- Card Header -->
                    <div class="flex items-center mb-8 transform group-hover:scale-105 transition-transform duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-700 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                            <i class="fas fa-credit-card text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-indigo-800 bg-clip-text text-transparent">
                                Payment Solutions</h3>
                            <p class="text-gray-600">Fast & Secure Payment Processing</p>
                        </div>
                    </div>

                    <!-- Mobile Banking Methods -->
                    <div class="space-y-4 mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Mobile Banking Options</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- bKash -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/bkash.png') }}"
                                            alt="bKash"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-mobile-alt text-2xl text-pink-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">bKash</h5>
                                        <p class="text-sm text-indigo-600">Instant Processing</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Fast &
                                        Secure</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>OTP
                                        Verification</p>
                                </div>
                            </div>

                            <!-- Nagad -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/nagad.png') }}"
                                            alt="Nagad"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-wallet text-2xl text-orange-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Nagad</h5>
                                        <p class="text-sm text-indigo-600">Fast Processing</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Quick &
                                        Easy</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>PIN
                                        Verification</p>
                                </div>
                            </div>

                            <!-- Rocket -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/rocket.png') }}" alt="Rocket"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-rocket text-2xl text-blue-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Rocket</h5>
                                        <p class="text-sm text-indigo-600">Quick Transfer</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Reliable
                                        Service</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Secure
                                        Payment</p>
                                </div>
                            </div>

                            <!-- Upay -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/upay.png') }}" alt="Upay"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-money-bill-wave text-2xl text-purple-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Upay</h5>
                                        <p class="text-sm text-indigo-600">Easy Payment</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Simple
                                        Process</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>24/7
                                        Service</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Features -->
                        <div class="mt-6 p-4 bg-indigo-50 rounded-xl">
                            <h5 class="font-semibold text-indigo-900 mb-3">Additional Features</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-shield-alt text-indigo-600"></i>
                                    <span class="text-gray-700">End-to-end Encryption</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-indigo-600"></i>
                                    <span class="text-gray-700">Instant Processing</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-history text-indigo-600"></i>
                                    <span class="text-gray-700">Transaction History</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-headset text-indigo-600"></i>
                                    <span class="text-gray-700">24/7 Support</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Features List -->
                    <div class="space-y-4">
                        <div class="flex items-start p-4 bg-white rounded-xl hover:shadow-md transition-all duration-300">
                            <div
                                class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg flex items-center justify-center mr-4">
                                <i class="fas fa-shield-alt text-white"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-900">Secure Transactions</h4>
                                <p class="text-sm text-gray-600 mt-1">End-to-end encryption with OTP verification</p>
                            </div>
                        </div>
                        <!-- Add more features with similar structure -->
                    </div>
                </div>

                <!-- Withdraw Solutions Card -->
                <div
                    class="glass-effect p-8 rounded-3xl shadow-xl hover:shadow-2xl transition-all duration-500 border border-gray-100/20 group">
                    <!-- Card Header -->
                    <div class="flex items-center mb-8 transform group-hover:scale-105 transition-transform duration-300">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-700 rounded-2xl flex items-center justify-center mr-4 shadow-lg">
                            <i class="fas fa-money-bill-wave text-2xl text-white"></i>
                        </div>
                        <div>
                            <h3
                                class="text-2xl font-bold bg-gradient-to-r from-green-600 to-green-800 bg-clip-text text-transparent">
                                Withdraw Solutions</h3>
                            <p class="text-gray-600">Withdraw Money From Wallet to Mobile Banking</p>
                        </div>
                    </div>

                    <!-- Mobile Banking Withdrawal Methods -->
                    <div class="space-y-4 mb-8">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Withdrawal Methods</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- bKash -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/bkash.png') }}" alt="bKash"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-mobile-alt text-2xl text-pink-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">bKash</h5>
                                        <p class="text-sm text-green-600">Instant Withdraw</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>1 Minute
                                        Processing</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>24/7
                                        Available</p>
                                </div>
                            </div>

                            <!-- Nagad -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-orange-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/nagad.png') }}" alt="Nagad"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-wallet text-2xl text-orange-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Nagad</h5>
                                        <p class="text-sm text-green-600">Quick Withdraw</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Instant
                                        Transfer</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Secure
                                        Process</p>
                                </div>
                            </div>

                            <!-- Rocket -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/rocket.png') }}" alt="Rocket"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-rocket text-2xl text-blue-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Rocket</h5>
                                        <p class="text-sm text-green-600">Fast Withdraw</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Quick
                                        Processing</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Safe
                                        Transfer</p>
                                </div>
                            </div>

                            <!-- Upay -->
                            <div
                                class="group bg-white p-4 rounded-xl border border-gray-100 hover:border-green-200 hover:shadow-lg transition-all duration-300">
                                <div class="flex items-center space-x-3 mb-3">
                                    <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center p-2">
                                        <img src="{{ asset('payments/upay.png') }}" alt="Upay"
                                            class="w-full h-full object-contain group-hover:scale-110 transition-transform duration-300"
                                            onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\'fas fa-money-bill-wave text-2xl text-purple-600\'></i>'">
                                    </div>
                                    <div>
                                        <h5 class="font-semibold text-gray-900">Upay</h5>
                                        <p class="text-sm text-green-600">Easy Withdraw</p>
                                    </div>
                                </div>
                                <div class="text-xs text-gray-600 space-y-1">
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Simple
                                        Process</p>
                                    <p class="flex items-center"><i class="fas fa-check text-green-500 mr-2"></i>Always
                                        Available</p>
                                </div>
                            </div>
                        </div>

                        <!-- Additional Features -->
                        <div class="mt-6 p-4 bg-green-50 rounded-xl">
                            <h5 class="font-semibold text-green-900 mb-3">Withdrawal Features</h5>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-bolt text-green-600"></i>
                                    <span class="text-gray-700">Instant Processing</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-shield-alt text-green-600"></i>
                                    <span class="text-gray-700">Secure Transfer</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-clock text-green-600"></i>
                                    <span class="text-gray-700">24/7 Withdrawals</span>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <i class="fas fa-headset text-green-600"></i>
                                    <span class="text-gray-700">Live Support</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Enhanced Call to Action -->
            <div class="mt-16 text-center">
                <div class="inline-block float-animation">
                    <button onclick="window.location.href='{{ route('customer.view_create_account') }}'"
                        class="group relative px-12 py-4 rounded-2xl bg-gradient-to-r from-indigo-600 to-indigo-800 text-white font-semibold shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <span class="relative z-10">Become a Merchant</span>
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-indigo-400 to-indigo-600 opacity-0 group-hover:opacity-100 transition-all duration-500">
                        </div>
                    </button>
                </div>
                <p class="mt-6 text-gray-600 flex items-center justify-center space-x-2">
                    <i class="fas fa-shield-alt text-indigo-600"></i>
                    <span>Secure Payment Gateway • Instant Processing • 24/7 Support</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Everything you need to accept payments</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-globe text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Global Payments</h3>
                    <p class="text-gray-600">Accept payments from customers anywhere in the world with 130+ currencies
                        supported.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Secure Processing</h3>
                    <p class="text-gray-600">Bank-grade security with fraud prevention and 3D Secure authentication.</p>
                </div>
                <div class="p-6 bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-code text-2xl text-indigo-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Developer First</h3>
                    <p class="text-gray-600">Powerful APIs and SDKs for custom integration into your applications.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Integration Steps -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Start Accepting Payments in Minutes</h2>
                <p class="text-gray-600 mt-4">Simple integration process to get you up and running</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="relative">
                    <div
                        class="absolute -left-4 top-0 w-8 h-8 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                        1</div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-bold mb-3">Create Account</h3>
                        <p class="text-gray-600">Sign up for free and verify your business details</p>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute -left-4 top-0 w-8 h-8 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                        2</div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-bold mb-3">Integrate API</h3>
                        <p class="text-gray-600">Add our simple code snippet to your website</p>
                    </div>
                </div>
                <div class="relative">
                    <div
                        class="absolute -left-4 top-0 w-8 h-8 bg-indigo-600 rounded-full text-white flex items-center justify-center">
                        3</div>
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <h3 class="text-xl font-bold mb-3">Start Processing</h3>
                        <p class="text-gray-600">Begin accepting payments immediately</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Security Features -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Enterprise-Grade Security</h2>
                <p class="text-gray-600 mt-4">Your security is our top priority</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="p-6 bg-gray-50 rounded-lg">
                    <div class="text-indigo-600 mb-4">
                        <i class="fas fa-lock text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">PCI DSS Level 1</h3>
                    <p class="text-gray-600">Highest level of payment security certification</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <div class="text-indigo-600 mb-4">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Fraud Prevention</h3>
                    <p class="text-gray-600">Advanced AI-powered fraud detection system</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <div class="text-indigo-600 mb-4">
                        <i class="fas fa-fingerprint text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">3D Secure 2.0</h3>
                    <p class="text-gray-600">Enhanced authentication for safer transactions</p>
                </div>
                <div class="p-6 bg-gray-50 rounded-lg">
                    <div class="text-indigo-600 mb-4">
                        <i class="fas fa-database text-3xl"></i>
                    </div>
                    <h3 class="text-lg font-semibold mb-2">Data Encryption</h3>
                    <p class="text-gray-600">End-to-end encryption for all transactions</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Dashboard Preview -->
    <div class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-6">Powerful Dashboard</h2>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-chart-line text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1">Real-time Analytics</h3>
                                <p class="text-gray-600">Monitor transactions and business performance in real-time</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-invoice text-blue-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1">Automated Reports</h3>
                                <p class="text-gray-600">Get detailed insights with customizable reports</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-users text-purple-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold mb-1">Customer Management</h3>
                                <p class="text-gray-600">Manage customer data and payment history</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                        alt="Analytics Dashboard Preview" class="rounded-lg w-full h-auto object-cover shadow-sm"
                        loading="lazy">
                </div>
            </div>
        </div>
    </div>

    <!-- Testimonials -->
    <div class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Trusted by Businesses Worldwide</h2>
                <p class="text-gray-600 mt-4">See what our customers say about us</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- First Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"iPayBD has transformed how we handle payments. The integration was
                        seamless, and their support team is exceptional."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/32.jpg" alt="Sarah Johnson"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=Sarah+Johnson&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">Sarah Johnson</h4>
                            <p class="text-sm text-gray-500">CEO, TechStart</p>
                        </div>
                    </div>
                </div>

                <!-- Second Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"The best payment gateway for e-commerce in Bangladesh. Fast processing
                        and excellent customer service. Highly recommended!"</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/42.jpg" alt="Kamal Hassan"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=Kamal+Hassan&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">Kamal Hassan</h4>
                            <p class="text-sm text-gray-500">Founder, ShopBD</p>
                        </div>
                    </div>
                </div>

                <!-- Third Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Their mobile banking integration is perfect. We've seen a 40% increase
                        in successful transactions since switching to iPayBD."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/45.jpg" alt="Rahima Akter"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=Rahima+Akter&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">Rahima Akter</h4>
                            <p class="text-sm text-gray-500">CTO, MobileShop</p>
                        </div>
                    </div>
                </div>

                <!-- Fourth Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Security and reliability are our top priorities, and iPayBD delivers on
                        both. Their fraud prevention system is outstanding."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/36.jpg" alt="David Chen"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=David+Chen&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">David Chen</h4>
                            <p class="text-sm text-gray-500">Security Head, SecurePay</p>
                        </div>
                    </div>
                </div>

                <!-- Fifth Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"The dashboard analytics and reporting features have made managing our
                        payments so much easier. Great platform!"</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="Fatima Islam"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=Fatima+Islam&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">Fatima Islam</h4>
                            <p class="text-sm text-gray-500">CFO, DigitalBazaar</p>
                        </div>
                    </div>
                </div>

                <!-- Sixth Testimonial -->
                <div class="bg-gray-50 p-6 rounded-lg hover:shadow-lg transition-all duration-300">
                    <div class="text-yellow-400 mb-4">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                    <p class="text-gray-600 mb-4">"Integration was a breeze with their well-documented API. Our developers
                        were impressed with the technical support."</p>
                    <div class="flex items-center">
                        <img src="https://randomuser.me/api/portraits/men/52.jpg" alt="Mohammad Ali"
                            class="w-12 h-12 rounded-full mr-3 object-cover border-2 border-indigo-100"
                            onerror="this.src='https://ui-avatars.com/api/?name=Mohammad+Ali&background=6366f1&color=fff'">
                        <div>
                            <h4 class="font-semibold">Mohammad Ali</h4>
                            <p class="text-sm text-gray-500">Lead Dev, TechSolutions</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="pricing" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl font-bold text-gray-900">Simple, Transparent Pricing</h2>
                <p class="text-gray-600 mt-4">No hidden fees. No surprises.</p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="p-8 bg-white rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold mb-4">Starter</h3>
                    <div class="text-4xl font-bold mb-4">1%</div>
                    <p class="text-gray-600 mb-6">Per successful charge</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            All payment methods
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            24/7 support
                        </li>
                    </ul>
                    <button onclick="window.location.href='{{ route('customer.view_create_account') }}'"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                        Become a Merchant
                    </button>
                </div>
                <div class="p-8 bg-indigo-600 rounded-xl shadow-lg text-white">
                    <h3 class="text-xl font-bold mb-4">Professional</h3>
                    <div class="text-4xl font-bold mb-4">1%</div>
                    <p class="text-indigo-100 mb-6">Per successful charge</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            All Starter features
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Advanced analytics
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check mr-2"></i>
                            Custom integration
                        </li>
                    </ul>
                    <button onclick="window.location.href='{{ route('customer.view_create_account') }}'"
                        class="w-full bg-white text-indigo-600 py-2 rounded-lg hover:bg-indigo-50">
                        Become a Merchant
                    </button>
                </div>
                <div class="p-8 bg-white rounded-xl shadow-sm border border-gray-100">
                    <h3 class="text-xl font-bold mb-4">Enterprise</h3>
                    <div class="text-4xl font-bold mb-4">Custom</div>
                    <p class="text-gray-600 mb-6">For large businesses</p>
                    <ul class="space-y-3 mb-8">
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            All Pro features
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Dedicated support
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-green-500 mr-2"></i>
                            Custom pricing
                        </li>
                    </ul>
                    <button onclick="window.location.href='{{ route('company') }}#contact'"
                        class="w-full bg-indigo-600 text-white py-2 rounded-lg hover:bg-indigo-700">
                        Contact Sales
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection
