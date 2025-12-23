@extends('welcome')
@section('customer')

@php
$app_name = app_config('AppName');
$image = app_config('AppLogo');
@endphp

    <!-- Company Header -->
    <div class="pt-24 bg-gradient-to-br from-indigo-50 via-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">About  {{ $app_name }} </h1>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Transforming digital payments in Bangladesh with innovative solutions and trusted technology.
                </p>
            </div>
        </div>
    </div>

    <!-- Company Info Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <!-- Mission & Vision -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12 mb-20">
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-bullseye text-2xl text-indigo-600"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">Our Mission</h2>
                <p class="text-gray-600 leading-relaxed">
                    To provide secure, reliable, and innovative payment solutions that empower businesses and individuals
                    across Bangladesh to participate in the digital economy.
                </p>
            </div>
            <div class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center mb-6">
                    <i class="fas fa-eye text-2xl text-indigo-600"></i>
                </div>
                <h2 class="text-2xl font-bold mb-4">Our Vision</h2>
                <p class="text-gray-600 leading-relaxed">
                    To become the leading payment gateway in Bangladesh, driving financial inclusion and digital
                    transformation through cutting-edge technology and exceptional service.
                </p>
            </div>
        </div>

        <!-- Company Values -->
        <div class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">Our Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-shield-alt text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Security First</h3>
                    <p class="text-gray-600">Ensuring the highest level of security for all transactions and data.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-users text-2xl text-blue-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Customer Focus</h3>
                    <p class="text-gray-600">Putting our customers' needs at the heart of everything we do.</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <i class="fas fa-lightbulb text-2xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-2">Innovation</h3>
                    <p class="text-gray-600">Continuously improving and innovating our services.</p>
                </div>
            </div>
        </div>

        <!-- Leadership Team -->
        <div id="team" class="mb-20">
            <h2 class="text-3xl font-bold text-center mb-12">Our Leadership Team</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden bg-gray-100">
                        <img src="https://placehold.co/200x200" alt="CEO" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold">John Doe</h3>
                    <p class="text-gray-600">CEO & Founder</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden bg-gray-100">
                        <img src="https://placehold.co/200x200" alt="CTO" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold">Jane Smith</h3>
                    <p class="text-gray-600">CTO</p>
                </div>
                <div class="text-center">
                    <div class="w-32 h-32 rounded-full mx-auto mb-4 overflow-hidden bg-gray-100">
                        <img src="https://placehold.co/200x200" alt="COO" class="w-full h-full object-cover">
                    </div>
                    <h3 class="text-xl font-semibold">Mike Johnson</h3>
                    <p class="text-gray-600">COO</p>
                </div>
            </div>
        </div>

        <!-- Contact Information -->
        <div id="contact" class="bg-white p-8 rounded-xl shadow-sm border border-gray-100">
            <h2 class="text-3xl font-bold mb-8">Contact Us</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <h3 class="text-xl font-semibold mb-4">Office Location</h3>
                    <p class="text-gray-600 mb-4">
                        <i class="fas fa-map-marker-alt text-indigo-600 mr-2"></i>
                        123 Digital Street, Dhaka 1200, Bangladesh
                    </p>
                    
                    <p class="text-gray-600">
                        <i class="fas fa-envelope text-indigo-600 mr-2"></i>
                        contact@trustpaybd.net
                    </p>
                </div>
                <div>
                    <h3 class="text-xl font-semibold mb-4">Business Hours</h3>
                    <p class="text-gray-600 mb-2">Monday - Friday: 9:00 AM - 6:00 PM</p>
                    <p class="text-gray-600 mb-2">Saturday: 10:00 AM - 4:00 PM</p>
                    <p class="text-gray-600">Sunday: Closed</p>
                    <p class="text-gray-600 mt-4">
                        <i class="fas fa-headset text-indigo-600 mr-2"></i>
                        24/7 Technical Support Available
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Telegram Live Chat Floating Button -->
<a href="{{app_config('telegram_id')}}" target="_blank" class="telegram-chat">
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 240 240">
    <path fill="white" d="M120 0C53.7 0 0 53.7 0 120s53.7 120 120 120 120-53.7 120-120S186.3 0 120 0zm54.3 80.6l-22.4 104.4c-1.7 7.8-6.1 9.8-12.4 6.1l-34.3-25.3-16.6 15.9c-1.8 1.8-3.3 3.3-6.7 3.3l2.4-34.6 62.9-56.9c2.7-2.4-.6-3.8-4.2-1.4L84.8 125.5 54.9 111.9c-7.5-2.9-7.6-7.5 1.6-11.1l103.9-40.0c4.8-1.9 9.0 1.1 8.0 19.8z"/>
  </svg>
</a>

<style>
.telegram-chat {
  position: fixed;
  right: 20px;
  bottom: 20px;
  width: 60px;
  height: 60px;
  border-radius: 50%;
  background: #0088cc;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  z-index: 9999;
  transition: transform 0.2s;
}
.telegram-chat:hover {
  transform: scale(1.1);
  background: #0077b3;
}
.telegram-chat svg {
  width: 32px;
  height: 32px;
}
</style>

@endsection