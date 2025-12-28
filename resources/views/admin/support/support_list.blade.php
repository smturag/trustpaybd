@extends('admin.layouts.admin_app')
@section('title', 'Support Tickets')
@push('css')
<style>
    .badge-priority-low { background-color: #28a745; }
    .badge-priority-medium { background-color: #ffc107; }
    .badge-priority-high { background-color: #fd7e14; }
    .badge-priority-urgent { background-color: #dc3545; }
    .ticket-filters {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
    }
    .filter-btn-group .btn {
        margin-right: 10px;
        margin-bottom: 10px;
    }
    .ticket-row:hover {
        background-color: #f1f3f5;
        cursor: pointer;
    }
    .unread-ticket {
        font-weight: bold;
        background-color: #e7f3ff;
    }
</style>
@endpush
@section('content')

@include('primary.error_notify')

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center justify-content-between mb-3">
                <h4 class="mb-0"><i class="bx bx-support"></i> Support Tickets</h4>
                <div>
                    <span class="badge bg-primary">Total: {{ $all_ticket->total() }}</span>
                </div>
            </div>
            
            <!-- Filters Section -->
            <div class="ticket-filters">
                <form method="GET" action="{{ route('admin.support_list') }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" placeholder="Ticket ID or Subject" value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status">
                                <option value="">All Status</option>
                                <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Opened</option>
                                <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Answered</option>
                                <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Customer Reply</option>
                                <option value="9" {{ request('status') == '9' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="">All Priority</option>
                                <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">User Type</label>
                            <select class="form-select" name="customer_type">
                                <option value="">All Types</option>
                                <option value="0" {{ request('customer_type') === '0' ? 'selected' : '' }}>Merchant</option>
                                <option value="1" {{ request('customer_type') == '1' ? 'selected' : '' }}>Agent</option>
                                <option value="2" {{ request('customer_type') == '2' ? 'selected' : '' }}>Customer</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="bx bx-search-alt"></i> Filter
                                </button>
                                <a href="{{ route('admin.support_list') }}" class="btn btn-secondary w-100">
                                    <i class="bx bx-reset"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Subject</th>
                                    <th>User Type</th>
                                    <th>Priority</th>
                                    <th>Created</th>
                                    <th>Last Reply</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($all_ticket as $data)
                                    <tr class="ticket-row {{ $data->status == 3 ? 'unread-ticket' : '' }}">
                                        <td>
                                            <strong class="text-primary">#{{ $data->ticket }}</strong>
                                        </td>
                                        <td>
                                            <div class="text-truncate" style="max-width: 300px;" title="{{ $data->subject }}">
                                                {{ $data->subject }}
                                            </div>
                                        </td>
                                        <td>
                                            @if ($data->customer_type == 0)
                                                <span class="badge bg-info">Merchant</span>
                                            @elseif($data->customer_type == 1)
                                                <span class="badge bg-primary">Agent</span>
                                            @else
                                                <span class="badge bg-secondary">Customer</span>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $priority = $data->priority ?? 'medium';
                                                $priorityClass = 'badge-priority-' . $priority;
                                            @endphp
                                            <span class="badge {{ $priorityClass }} text-white">
                                                {{ ucfirst($priority) }}
                                            </span>
                                        </td>
                                        <td>
                                            <small>{{ \Carbon\Carbon::parse($data->created_at)->format('M d, Y') }}</small>
                                            <br>
                                            <small class="text-muted">{{ \Carbon\Carbon::parse($data->created_at)->format('h:i A') }}</small>
                                        </td>
                                        <td>
                                            @if($data->last_reply_at)
                                                <small>{{ \Carbon\Carbon::parse($data->last_reply_at)->diffForHumans() }}</small>
                                            @else
                                                <small class="text-muted">No replies</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data->status == 1)
                                                <span class="badge bg-warning text-dark">Opened</span>
                                            @elseif($data->status == 2)
                                                <span class="badge bg-success">Answered</span>
                                            @elseif($data->status == 3)
                                                <span class="badge bg-info">Customer Reply</span>
                                            @elseif($data->status == 9)
                                                <span class="badge bg-danger">Closed</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($data->status == 9)
                                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.view_ticket', $data->ticket) }}">
                                                    <i class="bx bx-show"></i> View
                                                </a>
                                            @else
                                                <a class="btn btn-sm btn-primary" href="{{ route('admin.view_ticket', $data->ticket) }}">
                                                    <i class="bx bx-message-square-detail"></i> Reply
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-4">
                                            <i class="bx bx-info-circle" style="font-size: 48px; color: #ccc;"></i>
                                            <p class="text-muted mt-2">No tickets found</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-3">
                            {{ $all_ticket->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
