@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.permissions.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Permission</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Define a new system permission</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form action="{{ route('admin.permissions.store') }}" method="POST" class="p-6 space-y-6">
                @csrf

                <!-- Permission Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Permission Name (slug) <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                           value="{{ old('name') }}" placeholder="e.g., create-user, edit-post, delete-product">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use lowercase letters, numbers, and hyphens only</p>
                </div>

                <!-- Display Name -->
                <div>
                    <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Display Name
                    </label>
                    <input type="text" name="display_name" id="display_name"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('display_name') border-red-500 @enderror"
                           value="{{ old('display_name') }}" placeholder="e.g., Create Users, Edit Posts">
                    @error('display_name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('description') border-red-500 @enderror"
                              placeholder="Describe what this permission allows...">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Group -->
                <div>
                    <label for="group" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Group
                    </label>
                    <input type="text" name="group" id="group" list="existing-groups"
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('group') border-red-500 @enderror"
                           value="{{ old('group', 'General') }}" placeholder="e.g., Users, Products, Settings">
                    <datalist id="existing-groups">
                        @foreach($groups as $existingGroup)
                            <option value="{{ $existingGroup }}">
                        @endforeach
                    </datalist>
                    @error('group')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Group similar permissions together</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.permissions.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Create Permission
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
