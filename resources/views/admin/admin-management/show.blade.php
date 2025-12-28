@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('admin.admin-management.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $adminManagement->full_name }}</h1>
                        <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">@{{ $adminManagement->admin_name }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.admin-management.edit', $adminManagement) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Edit Admin
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Info Card -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Personal Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Username</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->admin_name }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Admin ID</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->id }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->email ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Mobile</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->mobile ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Type</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->type ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
                                <div class="mt-1">
                                    @if($adminManagement->status == 1)
                                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400 rounded">Inactive</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Login Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Login Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last Login</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $adminManagement->last_login ? \Carbon\Carbon::parse($adminManagement->last_login)->format('M d, Y h:i A') : 'Never' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Last IP</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->last_ip ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Login Count</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->last_login_count ?? 0 }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Created By</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">{{ $adminManagement->create_by ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Security Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Security Information</h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PIN Code Set</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $adminManagement->pincode ? 'Yes' : 'No' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">PIN Expiry</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $adminManagement->pin_expire ? \Carbon\Carbon::parse($adminManagement->pin_expire)->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Password Expiry</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $adminManagement->pass_expire ? \Carbon\Carbon::parse($adminManagement->pass_expire)->format('M d, Y') : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">2FA Enabled</p>
                                <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                    {{ $adminManagement->otp_google_code ? 'Yes' : 'No' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Side Info Card -->
            <div class="space-y-6">
                <!-- Role Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Role</h2>
                    </div>
                    <div class="p-6">
                        @if($adminManagement->role)
                            <div>
                                <span class="px-3 py-1 text-sm font-medium text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 rounded">
                                    {{ $adminManagement->role->name }}
                                </span>
                                @if($adminManagement->role->permissions->count() > 0)
                                    <p class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                        {{ $adminManagement->role->permissions->count() }} {{ Str::plural('permission', $adminManagement->role->permissions->count()) }}
                                    </p>
                                @endif
                            </div>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400">No role assigned</p>
                        @endif
                    </div>
                </div>

                <!-- Timestamps Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Timestamps</h2>
                    </div>
                    <div class="p-6 space-y-3">
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Created</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $adminManagement->created_at ? $adminManagement->created_at->format('M d, Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Last Updated</p>
                            <p class="mt-1 text-sm text-gray-900 dark:text-white">
                                {{ $adminManagement->updated_at ? $adminManagement->updated_at->format('M d, Y h:i A') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
