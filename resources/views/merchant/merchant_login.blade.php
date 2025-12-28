<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ app_config('AppTitle') }} | Merchant Login</title>
    
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
    
    {!! NoCaptcha::renderJs() !!}
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

        <!-- Right Side - Login Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Tabs -->
                <div class="flex mb-8 bg-gray-100 rounded-xl p-1">
                    <button class="flex-1 py-3 px-4 bg-blue-600 text-white rounded-lg font-semibold transition-all">
                        Welcome back
                    </button>
                    <a href="{{ route('merchant.sign_up') }}" class="flex-1 py-3 px-4 text-gray-600 hover:text-gray-900 rounded-lg font-semibold text-center transition-all">
                        Sign up
                    </a>
                </div>

                <h2 class="text-2xl font-bold text-gray-900 mb-2">Sign in to your account to continue</h2>

                <!-- Google Sign In (Optional) -->
                <button type="button" class="w-full flex items-center justify-center space-x-3 py-3 px-4 border border-gray-300 rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition-all mb-6 mt-6">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                        <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                        <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                        <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                    </svg>
                    <span class="font-medium">Continue with Google</span>
                </button>

                <div class="relative mb-6">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-gray-50 text-gray-500">Or continue with email</span>
                    </div>
                </div>

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

                @if (Session::has('message'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-check-circle text-green-500 text-xl mr-3'></i>
                            <p class="text-sm text-green-700">{{ Session::get('message') }}</p>
                        </div>
                    </div>
                @endif

                @if (Session::has('alert'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-error-circle text-red-500 text-xl mr-3'></i>
                            <p class="text-sm text-red-700">{{ Session::get('alert') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('merchantloginAction') }}" method="POST" id="loginForm">
                    @csrf
                    
                    <!-- Email -->
                    <div class="mb-5">
                        <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Email address</label>
                        <input 
                            type="text" 
                            name="username" 
                            id="username" 
                            value="{{ old('username') }}" 
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                            placeholder="you@example.com"
                            required
                        >
                    </div>

                    <!-- Password -->
                    <div class="mb-5">
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
                                id="togglePassword"
                                class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 transition"
                            >
                                <i class='bx bx-hide text-xl'></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="flex items-center justify-between mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="remember-me" class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                            <span class="ml-2 text-sm text-gray-700">Remember me</span>
                        </label>
                        <a href="{{ route('merchant.forget_pass_page') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                            Forgot password?
                        </a>
                    </div>

                    <!-- reCAPTCHA -->
                    <div class="mb-6">
                        {!! NoCaptcha::display() !!}
                        @if ($errors->has('g-recaptcha-response'))
                            <p class="text-red-500 text-sm mt-2">{{ $errors->first('g-recaptcha-response') }}</p>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Sign in
                    </button>
                </form>

                <!-- Terms -->
                <p class="mt-6 text-center text-xs text-gray-500">
                    By signing in, you agree to our 
                    <a href="#" class="text-blue-600 hover:underline">Terms of Service</a> and 
                    <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Password toggle
        const togglePassword = document.getElementById('togglePassword');
        const password = document.getElementById('password');
        const toggleIcon = togglePassword.querySelector('i');

        togglePassword.addEventListener('click', function() {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            if (type === 'text') {
                toggleIcon.classList.remove('bx-hide');
                toggleIcon.classList.add('bx-show');
            } else {
                toggleIcon.classList.remove('bx-show');
                toggleIcon.classList.add('bx-hide');
            }
        });

        // Form submission
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        
        loginForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Signing in...';
            submitBtn.classList.add('opacity-75');
        });
    </script>
</body>
</html>
