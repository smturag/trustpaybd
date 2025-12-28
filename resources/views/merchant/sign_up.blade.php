<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ app_config('AppTitle') }} | Merchant Sign Up</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>

<body class="min-h-screen bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Left Side - Blue Features Section -->
        <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-800 p-12 flex-col justify-between text-white">
            <!-- Logo & Title -->
            <div>
                <a href="{{ route('home') }}" class="inline-flex items-center text-white hover:text-blue-100 mb-4 transition group">
                    <i class='bx bx-home-alt text-2xl mr-2 group-hover:scale-110 transition-transform'></i>
                    <span class="font-medium">Back to Home</span>
                </a>
                <h1 class="text-4xl font-bold mb-3">{{ app_config('AppName') }}</h1>
                <p class="text-blue-100 text-lg">Complete Payment Gateway & Digital Services Platform</p>
            </div>
            
            <!-- Features -->
            <div class="space-y-8 my-auto">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-mobile text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Mobile Recharge</h3>
                        <p class="text-blue-100">Instant mobile recharge for all operators with best rates</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-credit-card text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Payment Solutions</h3>
                        <p class="text-blue-100">Fast & Secure Payment Processing</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-wallet-alt text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Withdraw Solutions</h3>
                        <p class="text-blue-100">Withdraw Money From Wallet to Mobile Banking</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-sm text-blue-100">
                <p>&copy; 2025 {{ app_config('AppName') }}. All rights reserved.</p>
                <div class="flex space-x-4 mt-2">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms</a>
                    <a href="#" class="hover:text-white transition">Refund Policy</a>
                </div>
            </div>
        </div>

        <!-- Right Side - Sign Up Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12 overflow-y-auto">
            <div class="w-full max-w-md">
                <!-- Tabs -->
                <div class="flex mb-8 bg-gray-100 rounded-xl p-1">
                    <a href="{{ route('merchantlogin') }}" class="flex-1 py-3 px-4 text-gray-600 hover:text-gray-900 rounded-lg font-semibold text-center transition-all">
                        Welcome back
                    </a>
                    <button class="flex-1 py-3 px-4 bg-blue-600 text-white rounded-lg font-semibold transition-all">
                        Sign up
                    </button>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-6">Create your merchant account</h2>

                <!-- Alert Messages -->
                @if($errors->any())
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-error-circle text-red-500 text-xl mr-3'></i>
                            <div class="flex-1">
                                <ul class="text-sm text-red-700 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                @if(session('success'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-check-circle text-green-500 text-xl mr-3'></i>
                            <p class="text-sm text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Sign Up Form -->
                <form action="{{ route('merchant.sign_up.submit') }}" method="POST" id="signUpForm" class="space-y-5">
                    @csrf
                    
                    <!-- Full Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="Enter your full name"
                            required
                        >
                    </div>

                    <!-- Company Name -->
                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Company / Shop Name</label>
                        <input 
                            type="text" 
                            name="company" 
                            id="company" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="Your business name"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-500">Displayed on payment receipts</p>
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="you@example.com"
                            required
                        >
                    </div>

                    <!-- Mobile Number -->
                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Mobile Number</label>
                        <input 
                            type="tel" 
                            name="phone" 
                            id="phone" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="+1 234 567 8900"
                            required
                        >
                        <p class="mt-1 text-xs text-gray-500">Verification code will be sent</p>
                    </div>

                    <!-- Country -->
                    <div>
                        <label for="country" class="block text-sm font-medium text-gray-700 mb-2">Country</label>
                        <select 
                            name="country" 
                            id="country" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            required
                        >
                            <option value="">Select your country</option>
                            <option value="bd">Bangladesh (+880)</option>
                            <option value="in">India (+91)</option>
                            <option value="pk">Pakistan (+92)</option>
                            <option value="us">United States (+1)</option>
                            <option value="gb">United Kingdom (+44)</option>
                            <option value="ca">Canada (+1)</option>
                            <option value="au">Australia (+61)</option>
                            <option value="ae">United Arab Emirates (+971)</option>
                            <option value="sa">Saudi Arabia (+966)</option>
                            <option value="sg">Singapore (+65)</option>
                        </select>
                    </div>

                    <!-- Website (Optional) -->
                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700 mb-2">Business Website (Optional)</label>
                        <div class="flex rounded-xl overflow-hidden border border-gray-300 focus-within:ring-2 focus-within:ring-blue-500">
                            <span class="inline-flex items-center px-4 bg-gray-100 text-gray-600 text-sm font-medium">
                                https://
                            </span>
                            <input 
                                type="text" 
                                name="website" 
                                id="website" 
                                class="flex-1 px-4 py-3 bg-gray-50 outline-none"
                                placeholder="www.yourwebsite.com"
                            >
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none pr-12"
                                    placeholder="••••••••"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                                >
                                    <i class='bx bx-hide text-xl'></i>
                                </button>
                            </div>
                        </div>
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm Password</label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password_confirmation" 
                                    id="password_confirmation" 
                                    class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none pr-12"
                                    placeholder="••••••••"
                                    required
                                >
                                <button 
                                    type="button" 
                                    onclick="togglePassword('password_confirmation', this)"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                                >
                                    <i class='bx bx-hide text-xl'></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">At least 8 characters long</p>

                    <!-- Terms Checkbox -->
                    <div class="flex items-start">
                        <input 
                            type="checkbox" 
                            name="terms" 
                            id="terms" 
                            class="w-4 h-4 mt-1 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                            required
                        >
                        <label for="terms" class="ml-3 text-sm text-gray-700">
                            I agree to the <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Create Account
                    </button>
                </form>

                <!-- Sign In Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    Already have an account? 
                    <a href="{{ route('merchantlogin') }}" class="text-blue-600 hover:underline font-medium">Sign in</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Toggle password visibility
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            const icon = button.querySelector('i');
            
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bx-hide');
                icon.classList.add('bx-show');
            } else {
                input.type = 'password';
                icon.classList.remove('bx-show');
                icon.classList.add('bx-hide');
            }
        }

        // Form submission
        const signUpForm = document.getElementById('signUpForm');
        const submitBtn = document.getElementById('submitBtn');
        
        signUpForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Creating Account...';
            submitBtn.classList.add('opacity-75');
        });
    </script>
</body>
</html>
