@extends('merchant.mrc_app')
@section('title', 'Support Tickets')
@push('css')
<style>
    .badge-priority-low { background-color: #10b981; }
    .badge-priority-medium { background-color: #f59e0b; }
    .badge-priority-high { background-color: #f97316; }
    .badge-priority-urgent { background-color: #ef4444; }
    .ticket-row:hover {
        background-color: #f9fafb;
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .dark .ticket-row:hover {
        background-color: #1f2937;
    }
    .unread-ticket {
        background-color: #eff6ff;
    }
    .dark .unread-ticket {
        background-color: #1e3a5f;
    }
</style>
@endpush
@section('mrc_content')

    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    <i class="bx bx-support text-blue-600 mr-2"></i>
                    {{ translate('My Tickets') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400">Manage and track your support tickets</p>
            </div>
            <a href="{{ route('merchant.create_support_view') }}" 
               class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Ticket
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        @php
            $openCount = $all_ticket->where('status', 1)->count();
            $answeredCount = $all_ticket->where('status', 2)->count();
            $replyCount = $all_ticket->where('status', 3)->count();
            $closedCount = $all_ticket->where('status', 9)->count();
        @endphp
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 rounded-xl p-4 border border-yellow-200 dark:border-yellow-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-yellow-600 dark:text-yellow-400 text-sm font-medium">Open</p>
                    <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $openCount }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-600 dark:text-green-400 text-sm font-medium">Answered</p>
                    <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $answeredCount }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-4 border border-blue-200 dark:border-blue-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-600 dark:text-blue-400 text-sm font-medium">Your Reply</p>
                    <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $replyCount }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-600 dark:text-red-400 text-sm font-medium">Closed</p>
                    <p class="text-2xl font-bold text-red-900 dark:text-red-100">{{ $closedCount }}</p>
                </div>
                <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tickets Table -->
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Ticket ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Subject</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Created</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($all_ticket as $data)
                        <tr class="ticket-row transition-all duration-200 {{ $data->status == 3 ? 'unread-ticket' : '' }}">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-bold text-blue-600 dark:text-blue-400">#{{ $data->ticket }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white max-w-md truncate" title="{{ $data->subject }}">
                                    {{ $data->subject }}
                                </div>
                                @if($data->category)
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ ucfirst($data->category) }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $priority = $data->priority ?? 'medium';
                                    $priorityClass = 'badge-priority-' . $priority;
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold text-white {{ $priorityClass }}">
                                    {{ ucfirst($priority) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($data->created_at)->format('h:i A') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($data->status == 1)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300">
                                        <span class="w-2 h-2 bg-yellow-500 rounded-full mr-2 animate-pulse"></span>
                                        Opened
                                    </span>
                                @elseif($data->status == 2)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                        <span class="w-2 h-2 bg-green-500 rounded-full mr-2"></span>
                                        Answered
                                    </span>
                                @elseif($data->status == 3)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                                        <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>
                                        Your Reply
                                    </span>
                                @elseif($data->status == 9)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-900/30 text-gray-800 dark:text-gray-300">
                                        <span class="w-2 h-2 bg-gray-500 rounded-full mr-2"></span>
                                        Closed
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($data->status == 9)
                                    <a href="{{ route('merchant.ticket_customer_reply', $data->ticket) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200 text-sm font-medium">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                @else
                                    <a href="{{ route('merchant.ticket_customer_reply', $data->ticket) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium shadow-sm hover:shadow-md">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                        </svg>
                                        Reply
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                                <p class="text-gray-500 dark:text-gray-400 text-lg font-medium mb-2">No tickets found</p>
                                <p class="text-gray-400 dark:text-gray-500 text-sm mb-4">Create your first support ticket to get started</p>
                                <a href="{{ route('merchant.create_support_view') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create Ticket
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($all_ticket->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $all_ticket->links() }}
            </div>
        @endif
    </div>
@endsection
