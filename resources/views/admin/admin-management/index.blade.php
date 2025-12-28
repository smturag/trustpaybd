@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Admin Management</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage system administrators</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.admin-management.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add New Admin
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 dark:bg-green-900/20 border-l-4 border-green-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 dark:text-green-400">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-6 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 p-4 rounded">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700 dark:text-red-400">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Admins Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Admin</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Contact</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Last Login</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($admins as $admin)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                <td class="px-6 py-4">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $admin->admin_name }}</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">ID: {{ $admin->id }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div>
                                        @if($admin->email)
                                            <p class="text-sm text-gray-900 dark:text-white">{{ $admin->email }}</p>
                                        @endif
                                        @if($admin->mobile)
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $admin->mobile }}</p>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($admin->role)
                                        <span class="px-2 py-1 text-xs font-medium text-blue-700 bg-blue-100 dark:bg-blue-900/30 dark:text-blue-400 rounded">
                                            {{ $admin->role->name }}
                                        </span>
                                    @else
                                        <span class="text-xs text-gray-400">No role</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <form action="{{ route('admin.admin-management.toggle-status', $admin) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center">
                                            @if($admin->status == 1)
                                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 dark:bg-green-900/30 dark:text-green-400 rounded">Active</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-medium text-red-700 bg-red-100 dark:bg-red-900/30 dark:text-red-400 rounded">Inactive</span>
                                            @endif
                                        </button>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    @if($admin->last_login)
                                        <div>
                                            <p class="text-xs text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($admin->last_login)->diffForHumans() }}</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">{{ $admin->last_ip }}</p>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400">Never</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <a href="{{ route('admin.admin-management.show', $admin) }}" class="text-blue-600 hover:text-blue-700 dark:text-blue-400" title="View">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <a href="{{ route('admin.admin-management.edit', $admin) }}" class="text-gray-600 hover:text-gray-700 dark:text-gray-400" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </a>
                                        <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-700 dark:text-red-400" title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No admins found</h3>
                                    <p class="text-gray-500 dark:text-gray-400 mb-4">Get started by creating your first admin.</p>
                                    <a href="{{ route('admin.admin-management.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                                        Add Admin
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($admins->hasPages())
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    {{ $admins->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
