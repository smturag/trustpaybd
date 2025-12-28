@extends('admin.layouts.admin_app')
@section('title', 'Merchant Fees & Commissions')

@push('css')
<style>
    .filter-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        border-radius: 15px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .filter-card .form-label {
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .filter-card .form-control,
    .filter-card .form-select {
        border: 2px solid rgba(255, 255, 255, 0.3);
        background: rgba(255, 255, 255, 0.95);
        border-radius: 10px;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    
    .filter-card .form-control:focus,
    .filter-card .form-select:focus {
        border-color: white;
        box-shadow: 0 0 0 0.2rem rgba(255, 255, 255, 0.25);
        background: white;
    }
    
    .operator-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        overflow: hidden;
        margin-bottom: 20px;
    }
    
    .operator-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
        transform: translateY(-2px);
    }
    
    .operator-card-header {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
        color: white;
        padding: 15px 20px;
        font-weight: 600;
        font-size: 1.1rem;
        border: none;
    }
    
    .operator-type-badge {
        background: rgba(255, 255, 255, 0.3);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .fee-row {
        background: #f8f9fa;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 15px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }
    
    .fee-row:hover {
        background: #e9ecef;
        border-left-color: #764ba2;
    }
    
    .action-label {
        font-weight: 700;
        color: #495057;
        text-transform: uppercase;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }
    
    .action-label i {
        margin-right: 8px;
        font-size: 1.2rem;
    }
    
    .deposit-icon {
        color: #28a745;
    }
    
    .withdraw-icon {
        color: #dc3545;
    }
    
    .input-group-text {
        background: #e9ecef;
        border: 1px solid #ced4da;
        font-size: 0.85rem;
        font-weight: 600;
        color: #6c757d;
    }
    
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 8px;
        font-weight: 500;
    }
    
    .form-control:focus {
        border-color: #667eea;
        box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
    }
    
    .btn-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .btn-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
        border: none;
        border-radius: 10px;
        padding: 10px 25px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(23, 162, 184, 0.4);
    }
    
    .btn-success {
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        border: none;
        border-radius: 10px;
        padding: 12px 35px;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.3s ease;
    }
    
    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
    }
    
    .page-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 25px;
        border-radius: 15px;
        margin-bottom: 30px;
        box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
    }
    
    .merchant-info {
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        margin-bottom: 25px;
    }
    
    .merchant-info h5 {
        color: #495057;
        font-weight: 700;
        margin-bottom: 10px;
    }
    
    .merchant-info .merchant-details {
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .merchant-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 20px;
        border-radius: 25px;
        font-weight: 600;
        font-size: 0.95rem;
    }
    
    .no-operators {
        text-align: center;
        padding: 60px 20px;
        background: #f8f9fa;
        border-radius: 15px;
        margin-top: 30px;
    }
    
    .no-operators i {
        font-size: 4rem;
        color: #6c757d;
        margin-bottom: 20px;
    }
    
    .filter-btn-group {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    @media (max-width: 768px) {
        .fee-row {
            padding: 15px;
        }
        
        .operator-card-header {
            font-size: 1rem;
            padding: 12px 15px;
        }
        
        .filter-btn-group {
            flex-direction: column;
        }
        
        .filter-btn-group .btn {
            width: 100%;
        }
    }
</style>
@endpush

@section('content')

@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" id="alert_success">
        <i class="bx bx-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if(session('alert'))
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="bx bx-error me-2"></i>{{ session('alert') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-xl-12 mx-auto">
        <!-- Page Header -->
        <div class="page-header">
            <div class="d-flex align-items-center justify-content-between flex-wrap">
                <div>
                    <h4 class="mb-2"><i class="bx bx-dollar-circle me-2"></i>Merchant Fees & Commissions</h4>
                    <p class="mb-0 opacity-75">Manage operator fees and commission rates</p>
                </div>
                <a class="btn btn-light mt-2 mt-sm-0" href="{{ route('merchantList') }}">
                    <i class="bx bx-left-arrow-alt me-1"></i> Back to Merchants
                </a>
            </div>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show">
                <h6 class="alert-heading"><i class="bx bx-error-circle me-2"></i>Please fix the following errors:</h6>
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Merchant Info -->
        <div class="merchant-info">
            <h5><i class="bx bx-user-circle me-2"></i>Merchant Information</h5>
            <div class="merchant-details">
                <span class="merchant-badge">{{ $merchant->fullname }}</span>
                <span class="text-muted">Username: <strong>{{ $merchant->username }}</strong></span>
                <span class="text-muted">ID: <strong>#{{ $merchant->id }}</strong></span>
            </div>
        </div>

        <!-- Filter Card -->
        <div class="card filter-card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('merchant_charge', $merchant->id) }}" id="filterForm">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">
                                <i class="bx bx-wallet me-1"></i>Select Operator
                            </label>
                            <select name="operator_id" class="form-select">
                                <option value="">All Operators</option>
                                @foreach($allOperators as $op)
                                    <option value="{{ $op->id }}" {{ ($operatorId ?? '') == $op->id ? 'selected' : '' }}>
                                        {{ $op->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="bx bx-filter me-1"></i>Operator Type
                            </label>
                            <select name="operator_type" class="form-select">
                                <option value="">All Types</option>
                                @foreach($operatorTypes as $type)
                                    <option value="{{ $type }}" {{ ($operatorType ?? '') == $type ? 'selected' : '' }}>
                                        {{ ucfirst($type) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">
                                <i class="bx bx-transfer me-1"></i>Action Type
                            </label>
                            <select name="action" class="form-select">
                                <option value="">All Actions</option>
                                <option value="deposit" {{ ($action ?? '') == 'deposit' ? 'selected' : '' }}>Deposit</option>
                                <option value="withdraw" {{ ($action ?? '') == 'withdraw' ? 'selected' : '' }}>Withdraw</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label d-none d-md-block">&nbsp;</label>
                            <div class="filter-btn-group">
                                <button type="submit" class="btn btn-light">
                                    <i class="bx bx-filter-alt me-1"></i> Apply
                                </button>
                                <a href="{{ route('merchant_charge', $merchant->id) }}" class="btn btn-outline-light">
                                    <i class="bx bx-reset me-1"></i> Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="mb-4">
            <button type="button" id="setDefaultsBtn" class="btn btn-info">
                <i class="bx bx-refresh me-2"></i>Set Default Operator Fees & Commissions
            </button>
        </div>

        <!-- Fees Form -->
        @if($operators->count() > 0)
            <form id="feesForm" action="{{ route('updateFees', $merchant->id) }}" method="POST">
                @csrf

                @foreach($operators as $operator)
                    <div class="operator-card">
                        <div class="operator-card-header">
                            <div class="d-flex align-items-center justify-content-between flex-wrap">
                                <span><i class="bx bx-wallet me-2"></i>{{ $operator->name }}</span>
                                <span class="operator-type-badge">{{ $operator->type }}</span>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $actionsToShow = ($action ?? '') ? [$action] : ['deposit', 'withdraw'];
                            @endphp

                            @foreach($actionsToShow as $actionType)
                                @php
                                    $record = $merchant->merchant_rate->first(function($r) use ($operator, $actionType) {
                                        return $r->mfs_operator_id == $operator->id && $r->action == $actionType;
                                    });

                                    $defaultFee = $actionType == 'deposit' ? $operator->deposit_fee : $operator->withdraw_fee;
                                    $defaultCommission = $actionType == 'deposit' ? $operator->deposit_commission : $operator->withdraw_commission;
                                    $currentFee = $record->fee ?? '';
                                    $currentCommission = $record->commission ?? '';
                                @endphp

                                <div class="fee-row">
                                    <div class="action-label">
                                        <i class="bx {{ $actionType == 'deposit' ? 'bx-download deposit-icon' : 'bx-upload withdraw-icon' }}"></i>
                                        {{ ucfirst($actionType) }}
                                    </div>
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label">Fee (%)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-percentage"></i></span>
                                                <input type="number" step="0.01" class="form-control fee-input"
                                                       name="fees[{{ $operator->id }}][{{ $actionType }}][fee]"
                                                       data-default="{{ $defaultFee }}"
                                                       value="{{ $currentFee ?: $defaultFee }}"
                                                       placeholder="Enter fee percentage">
                                                <span class="input-group-text text-muted">Default: {{ $defaultFee }}%</span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Commission (%)</label>
                                            <div class="input-group">
                                                <span class="input-group-text"><i class="bx bx-money"></i></span>
                                                <input type="number" step="0.01" class="form-control commission-input"
                                                       name="fees[{{ $operator->id }}][{{ $actionType }}][commission]"
                                                       data-default="{{ $defaultCommission }}"
                                                       value="{{ $currentCommission ?: $defaultCommission }}"
                                                       placeholder="Enter commission percentage">
                                                <span class="input-group-text text-muted">Default: {{ $defaultCommission }}%</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-save me-2"></i>Save All Fees & Commissions
                    </button>
                </div>
            </form>
        @else
            <div class="no-operators">
                <i class="bx bx-info-circle"></i>
                <h4 class="text-muted">No Operators Found</h4>
                <p class="text-muted">No operators match your filter criteria. Try adjusting your filters.</p>
                <a href="{{ route('merchant_charge', $merchant->id) }}" class="btn btn-primary mt-3">
                    <i class="bx bx-reset me-2"></i>Reset Filters
                </a>
            </div>
        @endif

    </div>
</div>

@endsection

@push('js')
<script>
$(document).ready(function () {
    // Set Default Values Button
    $('#setDefaultsBtn').click(function () {
        if(confirm('Are you sure you want to reset all fees and commissions to their default values?')) {
            $('.fee-input').each(function () {
                $(this).val($(this).data('default'));
            });
            $('.commission-input').each(function () {
                $(this).val($(this).data('default'));
            });
            
            // Show success message
            $('html, body').animate({scrollTop: 0}, 500);
            if($('#alert_success').length === 0) {
                $('<div class="alert alert-info alert-dismissible fade show" id="temp_alert">' +
                  '<i class="bx bx-info-circle me-2"></i>All values have been reset to defaults. Click "Save" to apply changes.' +
                  '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                  '</div>').insertBefore('.merchant-info');
            }
        }
    });
    
    // Auto-hide success alerts
    setTimeout(function() {
        $('#alert_success, #temp_alert').fadeOut('slow');
    }, 5000);
    
    // Form validation
    $('#feesForm').on('submit', function(e) {
        let hasValue = false;
        $('.fee-input, .commission-input').each(function() {
            if($(this).val() !== '') {
                hasValue = true;
                return false;
            }
        });
        
        if(!hasValue) {
            e.preventDefault();
            alert('Please enter at least one fee or commission value.');
            return false;
        }
    });
});
</script>
@endpush
