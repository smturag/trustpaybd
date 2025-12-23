<nav class="bg-white shadow-lg fixed w-full z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="{{ route('home') }}" class="text-2xl font-bold text-indigo-600">{{ $app_name }}</a>
            </div>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="{{ route('home') }}#payment-withdraw" class="text-gray-700 hover:text-indigo-600">Solutions</a>
                <a href="{{ route('home') }}#pricing" class="text-gray-700 hover:text-indigo-600">Pricing</a>
                <a href="{{ route('develop_docs') }}" class="text-gray-700 hover:text-indigo-600">Developers</a>
                <a href="{{ route('company') }}" class="text-gray-700 hover:text-indigo-600">Company</a>
                <button onclick="window.location.href='{{ route('merchantlogin') }}'" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-md hover:bg-gray-200">
                    Sign In
                </button>
                <button onclick="window.location.href='{{ route('merchant.sign_up') }}'" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">Merchant Sign Up</button>
            </div>

            <!-- Mobile Menu Button -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-gray-600 hover:text-gray-900 focus:outline-none">
                    <i class="fas fa-bars text-2xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div id="mobile-menu" class="hidden md:hidden bg-white border-t border-gray-100">
        <div class="px-4 py-2 space-y-1">
            <a href="{{ route('home') }}#payment-withdraw" class="block py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg px-3">Solutions</a>
            <a href="{{ route('home') }}#pricing" class="block py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg px-3">Pricing</a>
            <a href="{{ route('develop_docs') }}" class="block py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg px-3">Developers</a>
            <a href="{{ route('company') }}" class="block py-2 text-base font-medium text-gray-700 hover:text-indigo-600 hover:bg-gray-50 rounded-lg px-3">Company</a>
            <div class="pt-4 pb-2 border-t border-gray-200">
                <button onclick="window.location.href='{{ route('merchantlogin') }}'" class="w-full mb-2 bg-gray-100 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-200">Sign In</button>
                <button onclick="window.location.href='{{ route('merchant.sign_up') }}'" class="w-full bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Merchant Sign Up</button>
            </div>
        </div>
    </div>
</nav>

<!-- Script to Toggle Mobile Menu -->
<script>
    document.getElementById('mobile-menu-button').addEventListener('click', function () {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
</script>
