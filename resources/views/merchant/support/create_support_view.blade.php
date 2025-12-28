@extends('merchant.mrc_app')
@section('title', 'Create Support Ticket')
@push('css')
<style>
    .ticket-form-card {
        transition: all 0.3s ease;
    }
    .ticket-form-card:hover {
        transform: translateY(-2px);
    }
    .form-input-focus:focus {
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .priority-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }
    .category-chip {
        transition: all 0.2s ease;
    }
    .category-chip:hover {
        transform: scale(1.05);
    }
</style>
@endpush

@section('mrc_content')

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
            <div class="flex items-start">
                <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <div class="flex-1">
                    <h3 class="text-red-800 dark:text-red-200 font-semibold mb-1">Please fix the following errors:</h3>
                    <ul class="list-disc list-inside text-red-700 dark:text-red-300 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 rounded-lg p-4 shadow-sm" id="alert_success">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-green-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 dark:text-green-200 font-medium">{{ session('message') }}</p>
            </div>
        </div>
    @endif

    @if (session()->has('alert'))
        <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm" id="alert_warning">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
                <p class="text-yellow-800 dark:text-yellow-200 font-medium">{{ session('alert') }}</p>
            </div>
        </div>
    @endif

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    <i class="bx bx-message-square-add text-blue-600 mr-2"></i>
                    {{ translate('create_ticket') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Submit a new support ticket and our team will assist you shortly</p>
            </div>
            <a href="{{ route('merchant.support_list_view') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Tickets
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Form -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg ticket-form-card border border-gray-200 dark:border-gray-700">
                <div class="p-6 sm:p-8">
                    <form method="POST" action="{{ route('merchant.support_submit') }}" accept-charset="UTF-8" class="space-y-6" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Subject Field -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bx bx-heading text-blue-600 mr-1"></i>
                                Subject <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="subject" 
                                value="{{ old('subject') }}" 
                                required
                                placeholder="Brief description of your issue"
                                class="form-input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none transition-all duration-200 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 {{ $errors->has('subject') ? 'border-red-500' : '' }}"
                            >
                            @if ($errors->has('subject'))
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $errors->first('subject') }}
                                </p>
                            @endif
                        </div>

                        <!-- Priority Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <i class="bx bx-flag text-orange-600 mr-1"></i>
                                Priority Level
                            </label>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                <label class="relative">
                                    <input type="radio" name="priority" value="low" class="peer sr-only" checked>
                                    <div class="flex items-center justify-center px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer transition-all duration-200 peer-checked:border-green-500 peer-checked:bg-green-50 dark:peer-checked:bg-green-900/20 hover:border-green-400">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-green-700 dark:peer-checked:text-green-400">Low</span>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="medium" class="peer sr-only">
                                    <div class="flex items-center justify-center px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer transition-all duration-200 peer-checked:border-yellow-500 peer-checked:bg-yellow-50 dark:peer-checked:bg-yellow-900/20 hover:border-yellow-400">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-yellow-700 dark:peer-checked:text-yellow-400">Medium</span>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="high" class="peer sr-only">
                                    <div class="flex items-center justify-center px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer transition-all duration-200 peer-checked:border-orange-500 peer-checked:bg-orange-50 dark:peer-checked:bg-orange-900/20 hover:border-orange-400">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-orange-700 dark:peer-checked:text-orange-400">High</span>
                                    </div>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="priority" value="urgent" class="peer sr-only">
                                    <div class="flex items-center justify-center px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 cursor-pointer transition-all duration-200 peer-checked:border-red-500 peer-checked:bg-red-50 dark:peer-checked:bg-red-900/20 hover:border-red-400">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 peer-checked:text-red-700 dark:peer-checked:text-red-400">Urgent</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Category Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <i class="bx bx-category text-purple-600 mr-1"></i>
                                Category
                            </label>
                            <select name="category" class="form-input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none transition-all duration-200 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
                                <option value="">Select a category</option>
                                <option value="technical">Technical Issue</option>
                                <option value="billing">Billing & Payments</option>
                                <option value="account">Account Related</option>
                                <option value="feature">Feature Request</option>
                                <option value="general">General Inquiry</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <!-- Message Field -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <i class="bx bx-message-detail text-green-600 mr-1"></i>
                                Message <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <textarea 
                                    name="detail" 
                                    rows="8" 
                                    required
                                    placeholder="Please describe your issue in detail. Include any relevant information that will help us assist you better..."
                                    class="form-input-focus w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:outline-none transition-all duration-200 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 resize-none {{ $errors->has('detail') ? 'border-red-500' : '' }}"
                                >{{ old('detail') }}</textarea>
                                <div class="absolute bottom-3 right-3 text-xs text-gray-400 dark:text-gray-500">
                                    <span id="charCount">0</span> characters
                                </div>
                            </div>
                            @if ($errors->has('detail'))
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $errors->first('detail') }}
                                </p>
                            @endif
                        </div>

                        <!-- File Attachments -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                                <i class="bx bx-paperclip text-indigo-600 mr-1"></i>
                                Attachments (Optional)
                                <span class="text-xs text-gray-500 font-normal ml-2">Max 10MB per file</span>
                            </label>
                            <div class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-8 text-center hover:border-blue-500 dark:hover:border-blue-400 transition-all duration-200 bg-gray-50 dark:bg-gray-900/50" id="dropZone">
                                <input 
                                    type="file" 
                                    name="attachments[]" 
                                    id="fileInput" 
                                    multiple 
                                    accept=".jpg,.jpeg,.png,.pdf,.doc,.docx,.txt,.zip"
                                    class="hidden"
                                >
                                <div id="dropZoneContent">
                                    <div class="flex justify-center mb-4">
                                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                        <button type="button" onclick="document.getElementById('fileInput').click()" class="font-semibold text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 transition-colors">
                                            Click to upload
                                        </button> 
                                        <span class="text-gray-500 dark:text-gray-500">or drag and drop</span>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">
                                        PNG, JPG, PDF, DOC, DOCX, TXT, ZIP (max 10MB each)
                                    </p>
                                </div>
                                <div id="fileList" class="mt-6 space-y-2 hidden text-left"></div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-center justify-between pt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <i class="bx bx-info-circle mr-1"></i>
                                We typically respond within 5 minutes
                            </p>
                            <button 
                                type="submit"
                                class="inline-flex items-center px-8 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200"
                            >
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                {{ translate('submit_now') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar - Tips & Info -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl p-6 border border-blue-200 dark:border-blue-800 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-blue-900 dark:text-blue-100">Quick Tips</h3>
                </div>
                <ul class="space-y-3 text-sm text-blue-800 dark:text-blue-200">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Be specific and clear about your issue
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Include any error messages you received
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Mention steps to reproduce the problem
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 flex-shrink-0 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Choose the correct priority and category
                    </li>
                </ul>
            </div>

            <!-- Response Time Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-green-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Response Time</h3>
                </div>
                <div class="space-y-3">
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Urgent</span>
                        <span class="text-sm font-bold text-red-600">2-4 hours</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">High</span>
                        <span class="text-sm font-bold text-orange-600">4-8 hours</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Medium</span>
                        <span class="text-sm font-bold text-yellow-600">8-24 hours</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900 rounded-lg">
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Low</span>
                        <span class="text-sm font-bold text-green-600">24-48 hours</span>
                    </div>
                </div>
            </div>

            <!-- Contact Card -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl p-6 border border-purple-200 dark:border-purple-800 shadow-sm">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-purple-900 dark:text-purple-100">Need Help?</h3>
                </div>
                <p class="text-sm text-purple-800 dark:text-purple-200 mb-4">
                    For urgent matters, you can also reach us directly:
                </p>
                <div class="space-y-2 text-sm">
                    <div class="flex items-center text-purple-800 dark:text-purple-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        support@trustpay.com
                    </div>
                    <div class="flex items-center text-purple-800 dark:text-purple-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        +1 (555) 123-4567
                    </div>
                </div>
            </div>
        </div>
    </div>

@push('js')
<script>
    // Character counter for message field
    const textarea = document.querySelector('textarea[name="detail"]');
    const charCount = document.getElementById('charCount');
    
    if (textarea && charCount) {
        textarea.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }

    // File upload functionality
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('fileInput');
    const fileList = document.getElementById('fileList');
    const dropZoneContent = document.getElementById('dropZoneContent');
    let selectedFiles = [];

    // Prevent default drag behaviors
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, preventDefaults, false);
        document.body.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    // Highlight drop zone when item is dragged over it
    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, highlight, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, unhighlight, false);
    });

    function highlight() {
        dropZone.classList.add('border-blue-500', 'bg-blue-50');
    }

    function unhighlight() {
        dropZone.classList.remove('border-blue-500', 'bg-blue-50');
    }

    // Handle dropped files
    dropZone.addEventListener('drop', handleDrop, false);

    function handleDrop(e) {
        const dt = e.dataTransfer;
        const files = dt.files;
        handleFiles(files);
    }

    // Handle file input change
    fileInput.addEventListener('change', function() {
        handleFiles(this.files);
    });

    function handleFiles(files) {
        files = [...files];
        files.forEach(file => {
            if (validateFile(file)) {
                selectedFiles.push(file);
            }
        });
        updateFileList();
    }

    function validateFile(file) {
        const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'application/pdf', 
                           'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                           'text/plain', 'application/zip'];
        const maxSize = 10 * 1024 * 1024; // 10MB

        if (!validTypes.includes(file.type)) {
            alert('Invalid file type: ' + file.name + '. Please upload JPG, PNG, PDF, DOC, DOCX, TXT, or ZIP files.');
            return false;
        }

        if (file.size > maxSize) {
            alert('File too large: ' + file.name + '. Maximum size is 10MB.');
            return false;
        }

        return true;
    }

    function updateFileList() {
        fileList.innerHTML = '';
        
        if (selectedFiles.length > 0) {
            fileList.classList.remove('hidden');
            dropZoneContent.classList.add('hidden');
            
            selectedFiles.forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition-shadow';
                
                const fileIcon = getFileIcon(file.type);
                const fileSize = formatFileSize(file.size);
                
                fileItem.innerHTML = `
                    <div class="flex items-center space-x-3 flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            ${fileIcon}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${file.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${fileSize}</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="flex-shrink-0 ml-3 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                
                fileList.appendChild(fileItem);
            });

            // Add "Add more files" button
            const addMoreBtn = document.createElement('button');
            addMoreBtn.type = 'button';
            addMoreBtn.className = 'w-full p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:border-blue-500 dark:hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-all';
            addMoreBtn.innerHTML = '<i class="bx bx-plus mr-1"></i> Add more files';
            addMoreBtn.onclick = () => fileInput.click();
            fileList.appendChild(addMoreBtn);

            // Update file input
            updateFileInput();
        } else {
            fileList.classList.add('hidden');
            dropZoneContent.classList.remove('hidden');
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        updateFileList();
    }

    // Make removeFile available globally
    window.removeFile = removeFile;

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        fileInput.files = dataTransfer.files;
    }

    function getFileIcon(fileType) {
        if (fileType.startsWith('image/')) {
            return '<svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>';
        } else if (fileType === 'application/pdf') {
            return '<svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
        } else if (fileType.includes('word') || fileType === 'application/msword') {
            return '<svg class="w-8 h-8 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>';
        } else if (fileType === 'application/zip') {
            return '<svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/></svg>';
        } else {
            return '<svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>';
        }
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const successAlert = document.getElementById('alert_success');
        const warningAlert = document.getElementById('alert_warning');
        
        if (successAlert) {
            successAlert.style.transition = 'opacity 0.5s';
            successAlert.style.opacity = '0';
            setTimeout(() => successAlert.remove(), 500);
        }
        
        if (warningAlert) {
            warningAlert.style.transition = 'opacity 0.5s';
            warningAlert.style.opacity = '0';
            setTimeout(() => warningAlert.remove(), 500);
        }
    }, 5000);
</script>
@endpush

@endsection
