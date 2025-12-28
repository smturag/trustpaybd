<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Access Denied</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-red-50 to-orange-50 min-h-screen flex items-center justify-center px-4">
    <div class="max-w-2xl w-full">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-red-500 to-orange-500 p-8 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                    <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h1 class="text-5xl font-bold text-white mb-2">403</h1>
                <p class="text-xl text-white opacity-90">Access Denied</p>
            </div>
            
            <div class="p-8 text-center">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">
                    You don't have permission to access this resource
                </h2>
                <p class="text-gray-600 mb-6 leading-relaxed">
                    {{ $exception->getMessage() ?: 'Sorry, you are not authorized to access this page. If you believe this is an error, please contact your system administrator.' }}
                </p>
                
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        <div class="text-sm text-left">
                            <p class="font-semibold text-red-800 mb-1">Why am I seeing this?</p>
                            <ul class="text-red-700 space-y-1 list-disc list-inside">
                                <li>Your account doesn't have the required permissions</li>
                                <li>Your role may have been changed recently</li>
                                <li>The resource may be restricted to specific roles</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3 justify-center">
                    <button onclick="window.history.back()" class="inline-flex items-center justify-center px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Go Back
                    </button>
                    <a href="{{ route('admin_dashboard') }}" class="inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-red-500 to-orange-500 hover:from-red-600 hover:to-orange-600 text-white font-medium rounded-lg transition-all duration-200 shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
        
        <p class="text-center text-gray-500 text-sm mt-6">
            Need help? Contact your administrator for assistance.
        </p>
    </div>
</body>
</html>
