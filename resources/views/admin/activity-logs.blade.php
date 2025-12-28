@extends('admin.layouts.admin_app')

@push('styles')
<style>
    /* Dark Mode Styles */
    .dark .card {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    
    .dark .card-header {
        background-color: #1e293b !important;
        border-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    
    .dark .card-body {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
    }
    
    .dark .card-footer {
        background-color: #1e293b !important;
        border-color: #334155 !important;
    }
    
    .dark .form-control,
    .dark .form-select {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #f1f5f9 !important;
    }
    
    .dark .form-control::placeholder {
        color: #94a3b8 !important;
    }
    
    .dark .form-control:focus,
    .dark .form-select:focus {
        background-color: #334155 !important;
        border-color: #3b82f6 !important;
        color: #f1f5f9 !important;
    }
    
    .dark .form-label {
        color: #cbd5e1 !important;
    }
    
    .dark .table {
        color: #cbd5e1 !important;
    }
    
    .dark .table thead {
        background-color: #334155 !important;
        color: #f1f5f9 !important;
    }
    
    .dark .table thead th {
        color: #f1f5f9 !important;
        border-color: #475569 !important;
    }
    
    .dark .table-hover tbody tr:hover {
        background-color: #334155 !important;
    }
    
    .dark .table td {
        border-color: #334155 !important;
    }
    
    .dark .text-muted {
        color: #94a3b8 !important;
    }
    
    .dark h1, .dark h2, .dark h3, .dark h4, .dark h5, .dark h6 {
        color: #f1f5f9 !important;
    }
    
    .dark .modal-content {
        background-color: #1e293b !important;
        color: #cbd5e1 !important;
    }
    
    .dark .modal-header,
    .dark .modal-footer {
        border-color: #334155 !important;
    }
    
    .dark .modal-title {
        color: #f1f5f9 !important;
    }
    
    .dark .btn-close {
        filter: invert(1);
    }
    
    .dark .btn-link {
        color: #cbd5e1 !important;
    }
    
    .dark .pagination .page-link {
        background-color: #334155 !important;
        border-color: #475569 !important;
        color: #cbd5e1 !important;
    }
    
    .dark .pagination .page-item.active .page-link {
        background-color: #3b82f6 !important;
        border-color: #3b82f6 !important;
    }
    
    .dark .pagination .page-link:hover {
        background-color: #475569 !important;
        color: #f1f5f9 !important;
    }
    
    .dark .alert-warning {
        background-color: #78350f !important;
        border-color: #92400e !important;
        color: #fef3c7 !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 text-gray-800">
                <i class="bx bx-list-ul me-2"></i>Activity Logs
            </h1>
            <p class="text-muted mb-0">Track all system activities and user actions</p>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#clearOldModal">
                <i class="bx bx-trash me-1"></i>Clear Old Logs
            </button>
            <button type="button" class="btn btn-outline-primary" onclick="exportLogs()">
                <i class="bx bx-download me-1"></i>Export
            </button>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total Logs</p>
                            <h3 class="mb-0">{{ number_format($stats['total']) }}</h3>
                        </div>
                        <div class="bg-primary bg-opacity-10 p-3 rounded">
                            <i class="bx bx-list-ul text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Today</p>
                            <h3 class="mb-0">{{ number_format($stats['today']) }}</h3>
                        </div>
                        <div class="bg-success bg-opacity-10 p-3 rounded">
                            <i class="bx bx-calendar-check text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">This Week</p>
                            <h3 class="mb-0">{{ number_format($stats['this_week']) }}</h3>
                        </div>
                        <div class="bg-info bg-opacity-10 p-3 rounded">
                            <i class="bx bx-calendar-week text-info fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">This Month</p>
                            <h3 class="mb-0">{{ number_format($stats['this_month']) }}</h3>
                        </div>
                        <div class="bg-warning bg-opacity-10 p-3 rounded">
                            <i class="bx bx-calendar text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-bottom">
            <h5 class="mb-0">
                <i class="bx bx-filter me-2"></i>Filters
                <button class="btn btn-sm btn-link float-end" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                    <i class="bx bx-chevron-down"></i>
                </button>
            </h5>
        </div>
        <div class="collapse show" id="filtersCollapse">
            <div class="card-body">
                <form action="{{ route('admin.activity-logs.index') }}" method="GET" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search description...">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Log Name</label>
                            <select class="form-select" name="log_name">
                                <option value="">All Log Names</option>
                                @foreach($logNames as $logName)
                                    <option value="{{ $logName }}" {{ request('log_name') == $logName ? 'selected' : '' }}>
                                        {{ ucfirst($logName) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Event</label>
                            <select class="form-select" name="event">
                                <option value="">All Events</option>
                                @foreach($events as $event)
                                    <option value="{{ $event }}" {{ request('event') == $event ? 'selected' : '' }}>
                                        {{ ucfirst($event) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Admin User</label>
                            <select class="form-select" name="causer_id">
                                <option value="">All Admins</option>
                                @foreach($admins as $admin)
                                    <option value="{{ $admin->id }}" {{ request('causer_id') == $admin->id ? 'selected' : '' }}>
                                        {{ $admin->admin_name ?? $admin->username }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Subject Type</label>
                            <select class="form-select" name="subject_type">
                                <option value="">All Types</option>
                                @foreach($subjectTypes as $type)
                                    <option value="{{ $type }}" {{ request('subject_type') == $type ? 'selected' : '' }}>
                                        {{ class_basename($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <div class="w-100">
                                <button type="submit" class="btn btn-primary w-100 mb-2">
                                    <i class="bx bx-search me-1"></i>Filter
                                </button>
                                <a href="{{ route('admin.activity-logs.index') }}" class="btn btn-outline-secondary w-100">
                                    <i class="bx bx-reset me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Activity Logs Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header border-bottom">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Activity Logs ({{ $logs->total() }} records)</h5>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-outline-danger" id="bulkDeleteBtn" style="display: none;">
                        <i class="bx bx-trash me-1"></i>Delete Selected (<span id="selectedCount">0</span>)
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Time</th>
                            <th>Log Name</th>
                            <th>Description</th>
                            <th>Event</th>
                            <th>Causer</th>
                            <th>Subject</th>
                            <th width="200">Device & Location</th>
                            <th width="100">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                            <tr>
                                <td>
                                    <input type="checkbox" class="form-check-input log-checkbox" value="{{ $log->id }}">
                                </td>
                                <td>
                                    <small class="text-muted">
                                        {{ $log->created_at->format('M d, Y') }}<br>
                                        {{ $log->created_at->format('h:i A') }}
                                    </small>
                                </td>
                                <td>
                                    @if($log->log_name)
                                        <span class="badge bg-secondary">{{ ucfirst($log->log_name) }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="text-truncate d-inline-block" style="max-width: 300px;" title="{{ $log->description }}">
                                        {{ $log->description }}
                                    </span>
                                </td>
                                <td>
                                    @if($log->event)
                                        <span class="badge bg-{{ $log->color_class }}">
                                            <i class="{{ $log->icon_class }}"></i> {{ ucfirst($log->event) }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->causer)
                                        <div>
                                            <strong>{{ $log->causer->admin_name ?? $log->causer->name ?? $log->causer->username }}</strong><br>
                                            <small class="text-muted">{{ $log->causer->email ?? '-' }}</small>
                                            @if($log->ip_address)
                                                <br><small class="text-info"><i class="bx bx-globe"></i> {{ $log->ip_address }}</small>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">System</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->subject_type)
                                        <span class="badge bg-light text-dark">
                                            {{ class_basename($log->subject_type) }}
                                            @if($log->subject_id)
                                                #{{ $log->subject_id }}
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($log->device || $log->browser || $log->platform || $log->country)
                                        <div class="d-flex flex-column gap-1">
                                            @if($log->device)
                                                <span class="badge bg-secondary">
                                                    <i class="bx bx-devices"></i> {{ $log->device }}
                                                </span>
                                            @endif
                                            @if($log->browser)
                                                <span class="badge bg-info">
                                                    <i class="bx bx-window"></i> {{ $log->browser }}
                                                </span>
                                            @endif
                                            @if($log->platform)
                                                <span class="badge bg-primary">
                                                    <i class="bx bx-laptop"></i> {{ $log->platform }}
                                                </span>
                                            @endif
                                            @if($log->country)
                                                <span class="badge bg-success">
                                                    @if($log->country_code && $log->country_code !== 'XX')
                                                        <img src="https://flagcdn.com/16x12/{{ strtolower($log->country_code) }}.png" 
                                                             alt="{{ $log->country }}" 
                                                             style="vertical-align: middle;">
                                                    @else
                                                        <i class="bx bx-map"></i>
                                                    @endif
                                                    {{ $log->country }}
                                                    @if($log->city && $log->city !== 'Unknown')
                                                        , {{ $log->city }}
                                                    @endif
                                                </span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-info" onclick="viewLog({{ $log->id }})" title="View Details">
                                            <i class="bx bx-show"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger" onclick="deleteLog({{ $log->id }})" title="Delete">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <i class="bx bx-info-circle fs-1 text-muted mb-3"></i>
                                    <p class="text-muted mb-0">No activity logs found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($logs->hasPages())
            <div class="card-footer bg-white dark:bg-slate-800 border-top dark:border-slate-700">
                <div class="dark:text-slate-300">
                    {{ $logs->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    </div>
</div>

<!-- View Log Details Modal -->
<div class="modal fade" id="viewLogModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="bx bx-info-circle me-2"></i>Activity Log Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="logDetailsContent">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-top">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Clear Old Logs Modal -->
<div class="modal fade" id="clearOldModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title"><i class="bx bx-trash me-2"></i>Clear Old Logs</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="clearOldForm">
                @csrf
                <div class="modal-body">
                    <p class="text-muted">Delete activity logs older than specified days.</p>
                    <div class="mb-3">
                        <label class="form-label">Delete logs older than (days):</label>
                        <input type="number" class="form-control" name="days" value="30" min="1" required>
                        <small class="text-muted">Example: 30 will delete logs older than 30 days</small>
                    </div>
                    <div class="alert alert-warning">
                        <i class="bx bx-info-circle me-1"></i>
                        <strong>Warning:</strong> This action cannot be undone!
                    </div>
                </div>
                <div class="modal-footer border-top">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bx bx-trash me-1"></i>Clear Old Logs
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all checkbox
    $('#selectAll').on('change', function() {
        $('.log-checkbox').prop('checked', $(this).prop('checked'));
        updateBulkDeleteButton();
    });

    // Individual checkboxes
    $('.log-checkbox').on('change', function() {
        updateBulkDeleteButton();
        if (!$(this).prop('checked')) {
            $('#selectAll').prop('checked', false);
        }
    });

    // Update bulk delete button visibility
    function updateBulkDeleteButton() {
        const selectedCount = $('.log-checkbox:checked').length;
        $('#selectedCount').text(selectedCount);
        if (selectedCount > 0) {
            $('#bulkDeleteBtn').show();
        } else {
            $('#bulkDeleteBtn').hide();
        }
    }

    // Bulk delete
    $('#bulkDeleteBtn').on('click', function() {
        const selectedIds = $('.log-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            return;
        }

        if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected log(s)?`)) {
            return;
        }

        $.ajax({
            url: '{{ route("admin.activity-logs.bulk-destroy") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                ids: selectedIds
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert(response.message || 'Failed to delete logs');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete logs'));
            }
        });
    });

    // Clear old logs form
    $('#clearOldForm').on('submit', function(e) {
        e.preventDefault();

        const days = $(this).find('input[name="days"]').val();
        
        if (!confirm(`Are you sure you want to delete all logs older than ${days} days?`)) {
            return;
        }

        $.ajax({
            url: '{{ route("admin.activity-logs.clear-old") }}',
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message || 'Failed to clear old logs');
                }
            },
            error: function(xhr) {
                alert('Error: ' + (xhr.responseJSON?.message || 'Failed to clear old logs'));
            }
        });
    });
});

// View log details
function viewLog(id) {
    $('#viewLogModal').modal('show');
    
    $.ajax({
        url: `/admin/activity-logs/${id}`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const log = response.log;
                const html = `
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">ID</label>
                            <p>${log.id}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Created At</label>
                            <p>${new Date(log.created_at).toLocaleString()}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Log Name</label>
                            <p>${log.log_name || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Event</label>
                            <p>${log.event || '-'}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Causer</label>
                            <p>${response.causer_name}<br><small class="text-muted">${response.causer_email}</small></p>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Subject</label>
                            <p>${response.subject_name}</p>
                        </div>
                        ${log.ip_address ? `
                        <div class="col-md-6">
                            <label class="form-label fw-bold">IP Address</label>
                            <p><i class="bx bx-globe text-info"></i> ${log.ip_address}</p>
                        </div>
                        ` : ''}
                        ${log.device ? `
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Device</label>
                            <p><i class="bx bx-devices"></i> ${log.device}</p>
                        </div>
                        ` : ''}
                        ${log.browser ? `
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Browser</label>
                            <p><i class="bx bx-window"></i> ${log.browser}</p>
                        </div>
                        ` : ''}
                        ${log.platform ? `
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Platform</label>
                            <p><i class="bx bx-laptop"></i> ${log.platform}</p>
                        </div>
                        ` : ''}
                        ${log.country ? `
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Country</label>
                            <p>
                                ${log.country_code && log.country_code !== 'XX' ? 
                                    `<img src="https://flagcdn.com/16x12/${log.country_code.toLowerCase()}.png" alt="${log.country}" style="vertical-align: middle;">` : 
                                    '<i class="bx bx-map"></i>'}
                                ${log.country}${log.city && log.city !== 'Unknown' ? ', ' + log.city : ''}
                            </p>
                        </div>
                        ` : ''}
                        ${log.user_agent ? `
                        <div class="col-12">
                            <label class="form-label fw-bold">User Agent</label>
                            <p class="small text-muted">${log.user_agent}</p>
                        </div>
                        ` : ''}
                        <div class="col-12">
                            <label class="form-label fw-bold">Description</label>
                            <p>${log.description}</p>
                        </div>
                        ${log.properties && Object.keys(log.properties).length > 0 ? `
                        <div class="col-12">
                            <label class="form-label fw-bold">Properties</label>
                            <pre class="bg-light p-3 rounded"><code>${JSON.stringify(log.properties, null, 2)}</code></pre>
                        </div>
                        ` : ''}
                    </div>
                `;
                $('#logDetailsContent').html(html);
            } else {
                $('#logDetailsContent').html('<p class="text-danger">Failed to load log details</p>');
            }
        },
        error: function() {
            $('#logDetailsContent').html('<p class="text-danger">Failed to load log details</p>');
        }
    });
}

// Delete log
function deleteLog(id) {
    if (!confirm('Are you sure you want to delete this activity log?')) {
        return;
    }

    $.ajax({
        url: `/admin/activity-logs/${id}`,
        method: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.success) {
                location.reload();
            } else {
                alert(response.message || 'Failed to delete log');
            }
        },
        error: function(xhr) {
            alert('Error: ' + (xhr.responseJSON?.message || 'Failed to delete log'));
        }
    });
}

// Export logs
function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '{{ route("admin.activity-logs.export") }}?' + params.toString();
}
</script>
@endpush
