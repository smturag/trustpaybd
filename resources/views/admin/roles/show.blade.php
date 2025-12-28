@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $role->display_name ?? $role->name }}</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">{{ $role->description }}</p>
                </div>
                <a href="{{ route('admin.roles.edit', $role) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                    Edit Role
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Permissions Card -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Permissions ({{ $role->permissions->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($role->permissions->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($role->permissions->groupBy('group') as $group => $groupPermissions)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">{{ $group }}</h4>
                                        <ul class="space-y-2">
                                            @foreach($groupPermissions as $permission)
                                                <li class="flex items-center text-sm text-gray-700 dark:text-gray-300">
                                                    <svg class="w-4 h-4 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    {{ $permission->display_name ?? $permission->name }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">No permissions assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Users Card -->
            <div>
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Users ({{ $role->users->count() }})</h2>
                    </div>
                    <div class="p-6">
                        @if($role->users->count() > 0)
                            <ul class="space-y-3">
                                @foreach($role->users as $user)
                                    <li class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-700 last:border-0">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</p>
                                        </div>
                                        <form action="{{ route('admin.roles.remove-user', [$role, $user->id]) }}" method="POST" onsubmit="return confirm('Remove this user from the role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400">No users assigned</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
