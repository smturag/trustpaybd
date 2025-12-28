@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Permissions Management</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage system permissions</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <a href="{{ route('admin.roles.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Roles
                    </a>
                    <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Create Permission
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded">
                <p class="text-green-700 dark:text-green-400">{{ session('success') }}</p>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
                <p class="text-red-700 dark:text-red-400">{{ session('error') }}</p>
            </div>
        @endif

        <!-- Permissions by Group -->
        <div class="space-y-6">
            @forelse($permissions as $group => $groupPermissions)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $group }} ({{ $groupPermissions->count() }})</h2>
                    </div>
                    <div class="p-6">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="border-b border-gray-200 dark:border-gray-700">
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Permission</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Description</th>
                                        <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Assigned Roles</th>
                                        <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @foreach($groupPermissions as $permission)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="py-3 px-4">
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $permission->display_name ?? $permission->name }}</p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->name }}</p>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <p class="text-sm text-gray-600 dark:text-gray-300">{{ $permission->description ?? '-' }}</p>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex flex-wrap gap-2">
                                                    @forelse($permission->roles as $role)
                                                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 rounded">
                                                            {{ $role->display_name ?? $role->name }}
                                                        </span>
                                                    @empty
                                                        <span class="text-xs text-gray-400">None</span>
                                                    @endforelse
                                                </div>
                                            </td>
                                            <td class="py-3 px-4">
                                                <div class="flex items-center justify-end space-x-2">
                                                    <a href="{{ route('admin.permissions.edit', $permission) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                        </svg>
                                                    </a>
                                                    <form action="{{ route('admin.permissions.destroy', $permission) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No permissions found</h3>
                        <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first permission.</p>
                        <a href="{{ route('admin.permissions.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                            Create Permission
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
