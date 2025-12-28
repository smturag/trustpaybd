@extends('admin.layouts.admin_app')

@section('content')
<div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.roles.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Role</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update role details and permissions</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md">
            <form action="{{ route('admin.roles.update', $role) }}" method="POST" class="p-6 space-y-6">
                @csrf
                @method('PUT')

                <!-- Role Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Role Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" required
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:text-white @error('name') border-red-500 @enderror"
                           value="{{ old('name', $role->name) }}" placeholder="e.g., admin, manager, editor">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use lowercase letters, numbers, and hyphens only</p>
                </div>

                <!-- Permissions -->
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Permissions
                        </label>
                        <label class="flex items-center space-x-2 cursor-pointer">
                            <input type="checkbox" id="selectAll" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Select All</span>
                        </label>
                    </div>
                    <div class="space-y-6">
                        @foreach($permissions as $group => $groupPermissions)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">{{ $group }}</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                    @foreach($groupPermissions as $permission)
                                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 p-2 rounded">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                                   class="mt-1 h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                            <div class="flex-1">
                                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $permission->display_name ?? $permission->name }}</span>
                                                @if($permission->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $permission->description }}</p>
                                                @endif
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors">
                        Update Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const permissionCheckboxes = document.querySelectorAll('input[name="permissions[]"]');
    
    // Select/Deselect all
    selectAllCheckbox.addEventListener('change', function() {
        permissionCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update select all checkbox state when individual checkboxes change
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
            selectAllCheckbox.checked = allChecked;
            selectAllCheckbox.indeterminate = anyChecked && !allChecked;
        });
    });
    
    // Initialize select all state
    const allChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
    const anyChecked = Array.from(permissionCheckboxes).some(cb => cb.checked);
    selectAllCheckbox.checked = allChecked;
    selectAllCheckbox.indeterminate = anyChecked && !allChecked;
});
</script>
@endsection
