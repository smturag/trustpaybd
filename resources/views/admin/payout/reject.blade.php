@extends('admin.layouts.admin_app')
@section('title', 'Reject Payout')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('alert'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Merchant Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.merchant-payout.index') }}">Merchant Payout</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Reject</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Payout Summary Card -->
            <div class="card mb-3">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class='bx bx-error-circle me-2'></i>Payout Request Summary</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Payout ID:</strong> <code>{{ $payout->payout_id }}</code></p>
                            <p><strong>Merchant:</strong> {{ $payout->merchant->fullname ?? 'N/A' }}</p>
                            @if($payout->subMerchant)
                            <p><strong>Sub Merchant:</strong> {{ $payout->subMerchant->fullname }}</p>
                            @endif
                            <p><strong>Request Date:</strong> {{ $payout->created_at->format('d M Y, h:i A') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Merchant Currency:</strong> {{ strtoupper($payout->merchant_currency) }}</p>
                            <p><strong>Merchant Amount:</strong> <strong class="text-primary">{{ number_format($payout->merchant_amount, 2) }} {{ strtoupper($payout->merchant_currency) }}</strong></p>
                            <p><strong>Request Amount (BDT):</strong> <strong class="text-info">{{ money($payout->amount) }}</strong></p>
                            <p><strong>Fee:</strong> <span class="text-danger">{{ money($payout->fee) }}</span></p>
                            <p><strong>Net Amount (BDT):</strong> <strong class="text-success">{{ money($payout->net_amount) }}</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">
                                <strong><i class='bx bx-info-circle me-2'></i>Balance Refund:</strong>
                                Upon rejection, <strong>{{ money($payout->amount) }}</strong> will be refunded to merchant.
                                <br>
                                <span class="text-muted small">Current Balance: {{ money($payout->new_balance) }} → After Refund: {{ money($payout->old_balance) }}</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0"><strong>Bank/Payment Details:</strong></p>
                    <div class="p-2 bg-light rounded small">
                        {{ $payout->payment_details ?? $payout->wallet_address ?? 'N/A' }}
                    </div>
                </div>
            </div>

            <!-- Reject Form -->
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0"><i class='bx bx-x-circle me-2'></i>Reject Payout Request</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.merchant-payout.reject', $payout->id) }}">
                        @csrf

                        <div class="alert alert-warning">
                            <i class='bx bx-info-circle me-2'></i>
                            <strong>Note:</strong> The merchant's balance will be automatically refunded after rejection.
                        </div>

                        <div class="mb-3">
                            <label for="reject_reason" class="form-label">
                                <i class='bx bx-message-square-error'></i> Rejection Reason <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" 
                                      id="reject_reason" 
                                      name="reject_reason" 
                                      rows="4" 
                                      placeholder="Enter the reason for rejection (will be visible to merchant)"
                                      required>{{ old('reject_reason') }}</textarea>
                            <small class="text-muted">Please provide a clear reason so the merchant understands why the payout was rejected</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"><i class='bx bx-error'></i> Common Rejection Reasons:</label>
                            <div class="list-group">
                                <button type="button" class="list-group-item list-group-item-action" onclick="setReason('Invalid wallet address provided')">
                                    Invalid wallet address provided
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setReason('Wallet address does not match the selected network')">
                                    Wallet address does not match the selected network
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setReason('Insufficient verification documents')">
                                    Insufficient verification documents
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setReason('Suspicious activity detected on account')">
                                    Suspicious activity detected on account
                                </button>
                                <button type="button" class="list-group-item list-group-item-action" onclick="setReason('Payout amount exceeds daily/monthly limit')">
                                    Payout amount exceeds daily/monthly limit
                                </button>
                            </div>
                        </div>

                        <div class="alert alert-danger">
                            <h6 class="alert-heading"><i class='bx bx-error-circle'></i> Confirmation Required</h6>
                            <p class="mb-2">By rejecting this payout request:</p>
                            <ul class="mb-0">
                                <li>The merchant's balance of <strong>{{ money($payout->amount) }}</strong> will be refunded</li>
                                <li>The merchant will receive a notification with your rejection reason</li>
                                <li>This action cannot be undone</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-danger btn-lg">
                                <i class='bx bx-x-circle me-2'></i>Confirm & Reject Payout
                            </button>
                            <a href="{{ route('admin.merchant-payout.show', $payout->id) }}" class="btn btn-outline-secondary">
                                <i class='bx bx-arrow-back me-2'></i>Cancel & Go Back
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    function setReason(reason) {
        document.getElementById('reject_reason').value = reason;
    }
</script>
@endpush
