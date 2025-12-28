<!DOCTYPE html>
<html lang="en">
<head>
    <base href="{{ url('/') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="shortcut icon" href="static/backend/images/favicon.png">
    <title>{{ app_config('AppTitle') }} | Admin Login</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        /* Animated Mesh Gradient Background */
        .mesh-gradient {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            z-index: -2;
        }
        
        .mesh-gradient::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(252, 70, 107, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(99, 102, 241, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 90% 20%, rgba(168, 85, 247, 0.3) 0%, transparent 50%);
            animation: mesh-animation 20s ease infinite;
        }
        
        @keyframes mesh-animation {
            0%, 100% { transform: scale(1) translate(0, 0); }
            33% { transform: scale(1.1) translate(10px, -10px); }
            66% { transform: scale(0.9) translate(-10px, 10px); }
        }
        
        /* Floating particles */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }
        
        .particle {
            position: absolute;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float-up 15s infinite ease-in-out;
        }
        
        @keyframes float-up {
            0% {
                transform: translateY(100vh) scale(0);
                opacity: 0;
            }
            50% {
                opacity: 0.8;
            }
            100% {
                transform: translateY(-100vh) scale(1);
                opacity: 0;
            }
        }
        
        /* Glass morphism card */
        .glass-card {
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 
                0 8px 32px 0 rgba(31, 38, 135, 0.2),
                inset 0 0 0 1px rgba(255, 255, 255, 0.5);
        }
        
        /* Premium input styling */
        .premium-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .premium-input:focus {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px -10px rgba(102, 126, 234, 0.3);
        }
        
        /* Gradient button with shine effect */
        .btn-premium {
            position: relative;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            overflow: hidden;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .btn-premium::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-premium:hover::before {
            left: 100%;
        }
        
        .btn-premium:hover {
            transform: translateY(-3px);
            box-shadow: 0 20px 40px rgba(102, 126, 234, 0.4);
        }
        
        .btn-premium:active {
            transform: translateY(-1px);
        }
        
        /* Logo pulse animation */
        .logo-pulse {
            animation: pulse-scale 2s ease-in-out infinite;
        }
        
        @keyframes pulse-scale {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        /* Floating animation for illustration */
        .float-slow {
            animation: float-slow 6s ease-in-out infinite;
        }
        
        @keyframes float-slow {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        /* Feature card hover effect */
        .feature-card {
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px) scale(1.05);
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }
        
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 5px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Loading spinner */
        .spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        /* Fade in animation */
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Stagger animation for form elements */
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
        .stagger-4 { animation-delay: 0.4s; }
        
        /* Alert animations */
        .slide-down {
            animation: slideDown 0.3s ease-out;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
    
    {!! NoCaptcha::renderJs() !!}
</head>


<body class="min-h-screen">
    <!-- Animated Background -->
    <div class="mesh-gradient"></div>
    
    <!-- Floating Particles -->
    <div class="particles" id="particles"></div>
    
    <div class="relative min-h-screen flex items-center justify-center p-4 md:p-8">
        <div class="w-full max-w-7xl mx-auto fade-in">
            <div class="grid lg:grid-cols-2 gap-12 items-center">
                
                <!-- Left Side - Premium Illustration & Features -->
                <div class="hidden lg:block space-y-12">
                    <!-- Main Illustration -->
                    <div class="float-slow">
                        <div class="relative">
                            <!-- Decorative circles -->
                            <div class="absolute top-0 left-0 w-72 h-72 bg-white opacity-10 rounded-full blur-3xl"></div>
                            <div class="absolute bottom-0 right-0 w-96 h-96 bg-purple-300 opacity-20 rounded-full blur-3xl"></div>
                            
                            <!-- Dashboard mockup -->
                            <div class="relative bg-white bg-opacity-20 backdrop-blur-lg rounded-3xl p-8 border border-white border-opacity-30 shadow-2xl">
                                <div class="bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl p-6 space-y-4">
                                    <!-- Header -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-12 h-12 rounded-xl bg-white bg-opacity-20 flex items-center justify-center">
                                                <i class='bx bx-grid-alt text-2xl text-white'></i>
                                            </div>
                                            <div>
                                                <div class="h-3 w-24 bg-white bg-opacity-30 rounded"></div>
                                                <div class="h-2 w-16 bg-white bg-opacity-20 rounded mt-2"></div>
                                            </div>
                                        </div>
                                        <div class="w-10 h-10 rounded-lg bg-white bg-opacity-20"></div>
                                    </div>
                                    
                                    <!-- Stats Cards -->
                                    <div class="grid grid-cols-2 gap-3">
                                        <div class="bg-white bg-opacity-10 rounded-xl p-4">
                                            <div class="h-2 w-12 bg-white bg-opacity-30 rounded mb-2"></div>
                                            <div class="h-4 w-20 bg-white bg-opacity-50 rounded"></div>
                                        </div>
                                        <div class="bg-white bg-opacity-10 rounded-xl p-4">
                                            <div class="h-2 w-12 bg-white bg-opacity-30 rounded mb-2"></div>
                                            <div class="h-4 w-20 bg-white bg-opacity-50 rounded"></div>
                                        </div>
                                    </div>
                                    
                                    <!-- Chart -->
                                    <div class="bg-white bg-opacity-10 rounded-xl p-4 space-y-2">
                                        <div class="flex items-end justify-between h-24">
                                            <div class="w-8 bg-white bg-opacity-30 rounded-t" style="height: 40%"></div>
                                            <div class="w-8 bg-white bg-opacity-40 rounded-t" style="height: 60%"></div>
                                            <div class="w-8 bg-white bg-opacity-50 rounded-t" style="height: 80%"></div>
                                            <div class="w-8 bg-white bg-opacity-60 rounded-t" style="height: 50%"></div>
                                            <div class="w-8 bg-white bg-opacity-70 rounded-t" style="height: 90%"></div>
                                            <div class="w-8 bg-white bg-opacity-50 rounded-t" style="height: 70%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Welcome Text -->
                    <div class="text-center text-white space-y-6">
                        <h1 class="text-5xl font-extrabold leading-tight">
                            Welcome to<br/>
                            <span class="bg-clip-text text-transparent bg-gradient-to-r from-white via-purple-200 to-white">
                                Admin Dashboard
                            </span>
                        </h1>
                        <p class="text-xl text-white text-opacity-90 max-w-md mx-auto">
                            Manage your platform with powerful tools and real-time insights
                        </p>
                    </div>
                    
                    <!-- Feature Cards -->
                    <div class="grid grid-cols-3 gap-6">
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 text-center border border-white border-opacity-20">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mb-4 shadow-lg">
                                <i class='bx bx-shield-alt-2 text-3xl text-white'></i>
                            </div>
                            <h3 class="text-white font-semibold mb-2">Secure</h3>
                            <p class="text-white text-opacity-70 text-sm">Bank-level security</p>
                        </div>
                        
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 text-center border border-white border-opacity-20">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mb-4 shadow-lg">
                                <i class='bx bx-rocket text-3xl text-white'></i>
                            </div>
                            <h3 class="text-white font-semibold mb-2">Fast</h3>
                            <p class="text-white text-opacity-70 text-sm">Lightning speed</p>
                        </div>
                        
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-6 text-center border border-white border-opacity-20">
                            <div class="w-16 h-16 mx-auto rounded-2xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center mb-4 shadow-lg">
                                <i class='bx bx-line-chart text-3xl text-white'></i>
                            </div>
                            <h3 class="text-white font-semibold mb-2">Powerful</h3>
                            <p class="text-white text-opacity-70 text-sm">Advanced analytics</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Premium Login Form -->
                <div class="w-full">
                    <div class="glass-card rounded-3xl p-8 md:p-12 shadow-2xl fade-in">
                        <!-- Logo Section -->
                        <div class="text-center mb-10 stagger-1 fade-in">
                            <div class="inline-block logo-pulse">
                                <div class="relative">
                                    <div class="absolute inset-0 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-2xl blur-xl opacity-75"></div>
                                    <div class="relative p-4 rounded-2xl bg-gradient-to-br from-purple-500 to-indigo-600">
                                        <img src="{{ asset('static/backend/images/logo-icon.png') }}" width="56" alt="Logo" class="brightness-0 invert">
                                    </div>
                                </div>
                            </div>
                            <h2 class="text-4xl font-bold mt-6 mb-3">
                                <span class="bg-clip-text text-transparent bg-gradient-to-r from-purple-600 to-indigo-600">
                                    Admin Portal
                                </span>
                            </h2>
                            <p class="text-gray-600 text-lg">Sign in to {{ app_config('AppName') }}</p>
                        </div>

                        <!-- Alert Messages -->
                        @if($errors->any())
                            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl slide-down shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class='bx bx-error-circle text-red-500 text-2xl'></i>
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <h3 class="text-sm font-semibold text-red-800 mb-2">Please fix the following errors:</h3>
                                        <ul class="text-sm text-red-700 space-y-1 list-disc list-inside">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (Session::has('message'))
                            <div class="mb-8 bg-green-50 border-l-4 border-green-500 p-5 rounded-2xl slide-down shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class='bx bx-check-circle text-green-500 text-2xl'></i>
                                    </div>
                                    <p class="ml-4 text-sm text-green-700 font-medium">{{ Session::get('message') }}</p>
                                </div>
                            </div>
                        @endif

                        @if (Session::has('alert'))
                            <div class="mb-8 bg-red-50 border-l-4 border-red-500 p-5 rounded-2xl slide-down shadow-sm">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <i class='bx bx-error-circle text-red-500 text-2xl'></i>
                                    </div>
                                    <p class="ml-4 text-sm text-red-700 font-medium">{{ Session::get('alert') }}</p>
                                </div>
                            </div>
                        @endif

                        <!-- Login Form -->
                        <form action="{{ route('adminloginAction') }}" method="POST" id="loginForm" class="space-y-7">
                            @csrf
                            
                            <!-- Username Field -->
                            <div class="stagger-2 fade-in">
                                <label for="username" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class='bx bx-user mr-2 text-purple-600'></i>Username
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class='bx bx-user text-gray-400 text-xl group-focus-within:text-purple-600 transition-colors'></i>
                                    </div>
                                    <input 
                                        type="text" 
                                        name="username" 
                                        id="username" 
                                        value="{{ old('username') }}" 
                                        class="premium-input w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 outline-none text-gray-700 font-medium"
                                        placeholder="Enter your username"
                                        required
                                    >
                                </div>
                            </div>

                            <!-- Password Field -->
                            <div class="stagger-3 fade-in">
                                <label for="password" class="block text-sm font-bold text-gray-700 mb-3">
                                    <i class='bx bx-lock-alt mr-2 text-purple-600'></i>Password
                                </label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class='bx bx-lock-alt text-gray-400 text-xl group-focus-within:text-purple-600 transition-colors'></i>
                                    </div>
                                    <input 
                                        type="password" 
                                        name="password" 
                                        id="password" 
                                        class="premium-input w-full pl-12 pr-14 py-4 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:ring-4 focus:ring-purple-100 transition-all duration-300 outline-none text-gray-700 font-medium"
                                        placeholder="Enter your password"
                                        required
                                    >
                                    <button 
                                        type="button" 
                                        id="togglePassword"
                                        class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600 transition-colors p-1"
                                    >
                                        <i class='bx bx-hide text-2xl'></i>
                                    </button>
                                </div>
                            </div>

                            <!-- reCAPTCHA -->
                            <div class="stagger-4 fade-in">
                                <div class="flex justify-center">
                                    {!! app('captcha')->display() !!}
                                </div>
                                @if ($errors->has('g-recaptcha-response'))
                                    <p class="text-red-500 text-sm mt-3 text-center font-medium">
                                        <i class='bx bx-error-circle mr-1'></i>
                                        {{ $errors->first('g-recaptcha-response') }}
                                    </p>
                                @endif
                            </div>

                            <!-- Submit Button -->
                            <div class="stagger-4 fade-in">
                                <button 
                                    type="submit" 
                                    id="submitBtn"
                                    class="btn-premium w-full text-white font-bold py-5 rounded-xl flex items-center justify-center space-x-3 text-lg shadow-xl"
                                >
                                    <i class='bx bx-log-in-circle text-2xl'></i>
                                    <span id="btnText">Sign In to Dashboard</span>
                                </button>
                            </div>
                        </form>

                        <!-- Footer -->
                        <div class="mt-10 pt-8 border-t border-gray-200">
                            <div class="flex items-center justify-center space-x-2 text-gray-600">
                                <i class='bx bx-shield-quarter text-xl text-purple-600'></i>
                                <p class="text-sm font-medium">
                                    Protected by {{ app_config('AppName') }} Security
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Features (visible only on mobile) -->
                    <div class="lg:hidden mt-8 grid grid-cols-3 gap-4">
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-4 text-center border border-white border-opacity-30">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center mb-2 shadow-lg">
                                <i class='bx bx-shield-alt-2 text-2xl text-white'></i>
                            </div>
                            <p class="text-white text-xs font-semibold">Secure</p>
                        </div>
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-4 text-center border border-white border-opacity-30">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center mb-2 shadow-lg">
                                <i class='bx bx-rocket text-2xl text-white'></i>
                            </div>
                            <p class="text-white text-xs font-semibold">Fast</p>
                        </div>
                        <div class="feature-card bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl p-4 text-center border border-white border-opacity-30">
                            <div class="w-12 h-12 mx-auto rounded-xl bg-gradient-to-br from-pink-400 to-pink-600 flex items-center justify-center mb-2 shadow-lg">
                                <i class='bx bx-line-chart text-2xl text-white'></i>
                            </div>
                            <p class="text-white text-xs font-semibold">Powerful</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // Create floating particles
        function createParticles() {
            const particles = document.getElementById('particles');
            const particleCount = 30;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                
                const size = Math.random() * 60 + 20;
                particle.style.width = size + 'px';
                particle.style.height = size + 'px';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 15 + 's';
                particle.style.animationDuration = (Math.random() * 10 + 10) + 's';
                
                particles.appendChild(particle);
            }
        }
        
        createParticles();

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

        // Form submission with loading state
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        
        loginForm.addEventListener('submit', function(e) {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.innerHTML = '<i class="bx bx-loader-alt spinner text-2xl"></i><span class="ml-2">Signing In...</span>';
        });

        // Add focus effect to inputs
        const inputs = document.querySelectorAll('input[type="text"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('ring-2', 'ring-purple-200');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('ring-2', 'ring-purple-200');
            });
        });
    </script>
</body>
</html>