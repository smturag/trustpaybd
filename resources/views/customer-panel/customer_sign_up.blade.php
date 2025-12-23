@extends('welcome')
@section('customer')

@php
$app_name = app_config('AppName');
$image = app_config('AppLogo');
@endphp

    <div class="min-h-screen flex flex-col justify-center py-12 sm:px-6 lg:px-8 pt-24">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <a href="index.html" class="flex justify-center text-3xl font-bold text-indigo-600 mb-8">{{ $app_name{{ route('customer.view_create_account') }} }}</a>
            <h2 class="text-center text-3xl font-bold text-gray-900">Create your merchant account</h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{ route('merchantlogin') }}" class="font-medium text-indigo-600 hover:text-indigo-500">Sign in</a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-100">
                <form class="space-y-6" action="#" method="POST">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Full name</label>
                        <div class="mt-1">
                            <input id="name" name="name" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your full name">
                        </div>
                    </div>

                    <div>
                        <label for="company" class="block text-sm font-medium text-gray-700">Company / Shop name</label>
                        <div class="mt-1">
                            <input id="company" name="company" type="text" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Enter your business name">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">This will be displayed on your payment receipts</p>
                    </div>

                    <div>
                        <label for="website" class="block text-sm font-medium text-gray-700">Business Website</label>
                        <div class="mt-1">
                            <div class="flex rounded-md shadow-sm">
                                <span
                                    class="inline-flex items-center px-3 rounded-l-md border border-r-0 border-gray-300 bg-gray-50 text-gray-500 text-sm">
                                    https://
                                </span>
                                <input type="text" name="website" id="website"
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-none rounded-r-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="www.yourwebsite.com">
                            </div>
                            <p class="mt-1 text-xs text-gray-500">Optional - Enter your business website URL</p>
                        </div>
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email address</label>
                        <div class="mt-1">
                            <input id="email" name="email" type="email" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="you@example.com">
                        </div>
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700">Mobile number</label>
                        <div class="mt-1 flex">
                            <!-- Country Select -->
                            <div class="relative w-24 mr-2">
                                <select id="country-code" name="country-code"
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 pr-8">
                                    <option value="+880" selected>🇧🇩 +880</option>
                                    <option value="+91">🇮🇳 +91</option>
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+86">🇨🇳 +86</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+82">🇰🇷 +82</option>
                                    <option value="+971">🇦🇪 +971</option>
                                    <option value="+966">🇸🇦 +966</option>
                                </select>
                                <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                    <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                </div>
                            </div>
                            <!-- Phone Input -->
                            <div class="flex-1">
                                <input type="tel" id="phone" name="phone" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Enter mobile number">
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">We'll send a verification code to this number</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <input id="password" name="password" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Create a strong password">
                            </div>
                            <div>
                                <input id="confirm-password" name="confirm-password" type="password" required
                                    class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                                    placeholder="Confirm your password">
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500">Password must be at least 8 characters long</p>
                    </div>

                    <div class="flex items-center">
                        <input id="terms" name="terms" type="checkbox" required
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="terms" class="ml-2 block text-sm text-gray-900">
                            I agree to the <a href="#" class="text-indigo-600 hover:text-indigo-500">Terms</a> and
                            <a href="#" class="text-indigo-600 hover:text-indigo-500">Privacy Policy</a>
                        </label>
                    </div>

                    <div>
                        <button type="submit" disabled
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create account
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">Or continue with</span>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-2 gap-3">
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-google text-xl"></i>
                            </a>
                        </div>
                        <div>
                            <a href="#"
                                class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <i class="fab fa-facebook text-xl"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
