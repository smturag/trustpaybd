<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ app_config('AppTitle') }} | Reset Password</title>
    
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
                <p class="text-blue-100 text-lg">Mobile Recharge & Digital Services Platform</p>
            </div>
            
            <!-- Features -->
            <div class="space-y-8 my-auto">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-shield-alt-2 text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Secure Account</h3>
                        <p class="text-blue-100">Your account security is our top priority</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-mail-send text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">Quick Recovery</h3>
                        <p class="text-blue-100">Reset your password in just a few clicks</p>
                    </div>
                </div>
                
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-white bg-opacity-20 rounded-lg flex items-center justify-center">
                        <i class='bx bx-support text-2xl'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                        <p class="text-blue-100">Our team is here to help whenever you need</p>
                    </div>
                </div>
            </div>
            
            <!-- Footer -->
            <div class="text-sm text-blue-100">
                <p>&copy; 2025 {{ app_config('AppName') }}. All rights reserved.</p>
            </div>
        </div>

        <!-- Right Side - Reset Password Form -->
        <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
            <div class="w-full max-w-md">
                <!-- Back Button -->
                <a href="{{ route('merchantlogin') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900 mb-8 transition">
                    <i class='bx bx-arrow-back text-xl mr-2'></i>
                    <span class="font-medium">Back to Login</span>
                </a>

                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 mb-2">Reset Password</h2>
                    <p class="text-gray-600">Enter your email address and we'll send you a link to reset your password</p>
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

                @if(session('message'))
                    <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-check-circle text-green-500 text-xl mr-3'></i>
                            <p class="text-sm text-green-700">{{ session('message') }}</p>
                        </div>
                    </div>
                @endif

                @if(session('alert'))
                    <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg">
                        <div class="flex items-start">
                            <i class='bx bx-error-circle text-red-500 text-xl mr-3'></i>
                            <p class="text-sm text-red-700">{{ session('alert') }}</p>
                        </div>
                    </div>
                @endif

                <!-- Reset Form -->
                <form action="{{ route('merchant.merchant_forget_password') }}" method="POST" id="resetForm" class="space-y-6">
                    @csrf
                    
                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class='bx bx-envelope text-gray-400 text-xl'></i>
                            </div>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all outline-none"
                                placeholder="you@example.com"
                                required
                            >
                        </div>
                        <p class="mt-2 text-xs text-gray-500">We'll send a password reset link to this email</p>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        id="submitBtn"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                    >
                        Send Reset Link
                    </button>
                </form>

                <!-- Additional Info -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg border border-blue-100">
                    <div class="flex items-start">
                        <i class='bx bx-info-circle text-blue-600 text-xl mr-3 mt-0.5'></i>
                        <div class="text-sm text-blue-900">
                            <p class="font-medium mb-1">Can't access your email?</p>
                            <p class="text-blue-700">Contact our support team at <a href="mailto:{{ app_config('email') }}" class="underline hover:text-blue-900">{{ app_config('email') }}</a></p>
                        </div>
                    </div>
                </div>

                <!-- Sign Up Link -->
                <p class="mt-6 text-center text-sm text-gray-600">
                    Don't have an account? 
                    <a href="{{ route('merchant.sign_up') }}" class="text-blue-600 hover:underline font-medium">Sign up</a>
                </p>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Form submission
        const resetForm = document.getElementById('resetForm');
        const submitBtn = document.getElementById('submitBtn');
        
        resetForm.addEventListener('submit', function() {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Sending Link...';
            submitBtn.classList.add('opacity-75');
        });
    </script>
</body>
</html>
