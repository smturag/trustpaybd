@extends('admin.layouts.admin_app')
@section('title', 'Database Update')

@push('css')
<style>
    .migration-card {
        border-left: 4px solid #0d6efd;
    }
    .pending-migration {
        background-color: #fff3cd;
        border-left-color: #ffc107;
    }
    .ran-migration {
        background-color: #d1e7dd;
        border-left-color: #198754;
    }
    .update-btn {
        font-size: 1.1rem;
        padding: 12px 30px;
    }
    .status-badge {
        font-size: 0.9rem;
        padding: 8px 15px;
    }
    .migration-list {
        max-height: 400px;
        overflow-y: auto;
    }
    .spinner-border-sm {
        width: 1rem;
        height: 1rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-xl-10 mx-auto">
        <div class="header d-flex align-items-center mb-3">
            <h6 class="mb-0 text-uppercase ps-3">
                <i class="bx bx-data"></i> Database Update Manager
            </h6>
        </div>
        <hr />

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="bx bx-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="bx bx-error-circle"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(isset($error))
            <div class="alert alert-warning">
                <i class="bx bx-info-circle"></i> {{ $error }}
            </div>
        @endif

        <!-- Status Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h5 class="mb-2">
                            <i class="bx bx-server"></i> Database Status
                        </h5>
                        <p class="text-muted mb-0">
                            Current database version and pending updates
                        </p>
                    </div>
                    <div class="col-md-6 text-end">
                        <div id="status-container">
                            @if(count($pendingMigrations) > 0)
                                <span class="badge bg-warning status-badge">
                                    <i class="bx bx-time"></i> {{ count($pendingMigrations) }} Pending Updates
                                </span>
                            @else
                                <span class="badge bg-success status-badge">
                                    <i class="bx bx-check"></i> Database Up to Date
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Direct URL Card -->
        <div class="card mb-4 border-primary">
            <div class="card-header bg-primary text-white">
                <h6 class="mb-0">
                    <i class="bx bx-link"></i> Direct Migration URL (For Live Updates)
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info mb-3">
                    <i class="bx bx-info-circle"></i>
                    <strong>Quick Update:</strong> Use this URL directly in your browser to update database on live server without clicking buttons.
                </div>
                
                <div class="input-group mb-3">
                    <input type="text" class="form-control" id="directUrl" value="{{ url('/admin/run-migrations-now') }}" readonly>
                    <button class="btn btn-primary" type="button" onclick="copyUrl()">
                        <i class="bx bx-copy"></i> Copy URL
                    </button>
                    <a href="{{ route('admin.run-migrations-now') }}" class="btn btn-success" target="_blank">
                        <i class="bx bx-play-circle"></i> Run Now
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <small class="text-muted">
                        <i class="bx bx-shield-quarter"></i> Safe for live/production
                    </small>
                    <small class="text-muted">
                        <i class="bx bx-check-circle"></i> Shows detailed progress
                    </small>
                    <small class="text-muted">
                        <i class="bx bx-error-circle"></i> Error-free execution
                    </small>
                </div>
            </div>
        </div>

        <!-- Update Action Card -->
        <div class="card mb-4 migration-card @if(count($pendingMigrations) > 0) pending-migration @else ran-migration @endif">
            <div class="card-body text-center p-5">
                <i class="bx bx-data" style="font-size: 60px; color: #0d6efd;"></i>
                <h4 class="mt-3 mb-3">Update Database</h4>
                
                @if(count($pendingMigrations) > 0)
                    <p class="text-muted mb-4">
                        There are <strong>{{ count($pendingMigrations) }}</strong> pending database updates. 
                        Click the button below to update your database safely.
                    </p>
                    
                    <div class="alert alert-info text-start">
                        <i class="bx bx-info-circle"></i>
                        <strong>Important:</strong>
                        <ul class="mb-0 mt-2">
                            <li>This will update your database structure</li>
                            <li>All migrations will run automatically</li>
                            <li>The process is safe and reversible</li>
                            <li>No data will be lost</li>
                        </ul>
                    </div>

                    <button type="button" class="btn btn-primary update-btn" id="runMigrationsBtn">
                        <i class="bx bx-refresh"></i> Update Database Now
                    </button>
                @else
                    <p class="text-success mb-4">
                        <i class="bx bx-check-circle"></i> 
                        Your database is up to date! No pending migrations.
                    </p>
                @endif
            </div>
        </div>

        <!-- Pending Migrations List -->
        @if(count($pendingMigrations) > 0)
        <div class="card mb-4">
            <div class="card-header bg-warning">
                <h6 class="mb-0 text-white">
                    <i class="bx bx-list-ul"></i> Pending Migrations ({{ count($pendingMigrations) }})
                </h6>
            </div>
            <div class="card-body migration-list">
                <div class="list-group">
                    @foreach($pendingMigrations as $migration)
                        <div class="list-group-item">
                            <i class="bx bx-time text-warning"></i>
                            <code>{{ $migration }}</code>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Recent Migrations -->
        @if(count($ranMigrations) > 0)
        <div class="card">
            <div class="card-header bg-success">
                <h6 class="mb-0 text-white">
                    <i class="bx bx-check-circle"></i> Recently Applied Migrations
                </h6>
            </div>
            <div class="card-body migration-list">
                <div class="list-group">
                    @foreach($ranMigrations as $migration)
                        <div class="list-group-item">
                            <i class="bx bx-check text-success"></i>
                            <code>{{ $migration }}</code>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Output Modal -->
<div class="modal fade" id="outputModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bx bx-terminal"></i> Migration Output
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="migrationOutput" style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 5px; max-height: 400px; overflow-y: auto;"></pre>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    $('#runMigrationsBtn').on('click', function() {
        const $btn = $(this);
        const originalHtml = $btn.html();
        
        // Confirm action
        if (!confirm('Are you sure you want to update the database? This will apply all pending migrations.')) {
            return;
        }

        // Disable button and show loading
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span> Updating...');

        $.ajax({
            url: '{{ route('admin.database.run-migrations') }}',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    // Show success message
                    toastr.success(response.message);

                    // Show output in modal
                    $('#migrationOutput').text(response.output || 'Migration completed successfully!');
                    $('#outputModal').modal('show');

                    // Reload page after modal is closed
                    $('#outputModal').on('hidden.bs.modal', function() {
                        location.reload();
                    });
                } else {
                    toastr.error(response.message || 'Migration failed');
                    $btn.prop('disabled', false).html(originalHtml);
                }
            },
            error: function(xhr) {
                let errorMsg = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                toastr.error(errorMsg);
                
                // Show error details if available
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    $('#migrationOutput').text('ERROR: ' + xhr.responseJSON.error);
                    $('#outputModal').modal('show');
                }
                
                $btn.prop('disabled', false).html(originalHtml);
            }
        });
    });

    // Copy URL function
    window.copyUrl = function() {
        const urlInput = document.getElementById('directUrl');
        urlInput.select();
        urlInput.setSelectionRange(0, 99999); // For mobile devices
        
        navigator.clipboard.writeText(urlInput.value).then(function() {
            toastr.success('URL copied to clipboard!');
        }, function() {
            // Fallback for older browsers
            document.execCommand('copy');
            toastr.success('URL copied to clipboard!');
        });
    };

    // Auto-check status every 30 seconds (optional)
    setInterval(function() {
        $.get('{{ route('admin.database.check-status') }}', function(response) {
            if (response.has_pending) {
                $('#status-container').html(
                    '<span class="badge bg-warning status-badge">' +
                    '<i class="bx bx-time"></i> ' + response.pending_count + ' Pending Updates' +
                    '</span>'
                );
            }
        });
    }, 30000);
});
</script>
@endpush
