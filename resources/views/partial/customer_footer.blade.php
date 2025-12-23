<footer class="bg-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div>
                <h3 class="text-xl font-bold mb-4">{{ $app_name }}</h3>
                <p class="text-gray-400">Modern payment solutions for the digital age.</p>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Solutions</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}#payment-withdraw" class="text-gray-400 hover:text-white">Payment & Withdraw</a></li>
                    <li><a href="{{ route('home') }}#features" class="text-gray-400 hover:text-white">Features</a></li>
                    <li><a href="{{ route('home') }}#pricing" class="text-gray-400 hover:text-white">Pricing</a></li>
                    <li><a href="{{ route('home') }}#security" class="text-gray-400 hover:text-white">Security</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('develop_docs') }}" class="text-gray-400 hover:text-white">Developers</a></li>
                    <li><a href="{{ route('develop_docs') }}" class="text-gray-400 hover:text-white">Documentation</a></li>
                    <li><a href="{{ route('develop_docs') }}" class="text-gray-400 hover:text-white">API Reference</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-semibold mb-4">Company</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('company') }}#about" class="text-gray-400 hover:text-white">About Us</a></li>
                    <li><a href="{{ route('company') }}#contact" class="text-gray-400 hover:text-white">Contact</a></li>
                    <li><a href="{{ route('merchantlogin') }}" class="text-gray-400 hover:text-white">Sign In</a></li>
                    <li><a href="{{ route('merchant.sign_up') }}" class="text-gray-400 hover:text-white">Merchant Sign Up</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8">
            <div class="flex justify-between items-center">
                <p class="text-gray-400">&copy; {{ date('Y') }} {{ $app_name  }}. All rights reserved.</p>
                <div class="flex space-x-6">
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-linkedin"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white">
                        <i class="fab fa-github"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>
