@extends('merchant.mrc_app')
@section('title', 'Payout Details')

@push('css')
    <style>
        .detail-card {
            border-left: 4px solid #0d6efd;
        }
        .info-row {
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .wallet-address {
            word-break: break-all;
            font-family: monospace;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
@endpush

@section('mrc_content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Payout</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('merchant_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('merchant.payout-history') }}">Payout History</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('merchant.payout-history') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to History
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card detail-card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class='bx bx-info-circle me-2'></i>Payout Request Details</h5>
                        <span class="badge bg-{{ $payout->status_color }} fs-6">{{ $payout->status_text }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Transaction Info -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-receipt'></i> Transaction Information</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Payout ID:</div>
                            <div class="col-md-8">{{ $payout->payout_id }}</div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Request Date:</div>
                            <div class="col-md-8">
                                {{ $payout->created_at->format('d M Y, h:i A') }}
                                <small class="text-muted">({{ $payout->created_at->diffForHumans() }})</small>
                            </div>
                        </div>

                        @if($payout->merchant_type === 'sub_merchant' && $payout->subMerchant)
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Merchant:</div>
                            <div class="col-md-8">{{ $payout->subMerchant->fullname ?? 'N/A' }}</div>
                        </div>
                        @endif
                    </div>

                    <!-- Crypto Details -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-dollar-circle'></i> Cryptocurrency Details</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Currency:</div>
                            <div class="col-md-8">{{ strtoupper($payout->currency_name) }}</div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Network:</div>
                            <div class="col-md-8">
                                <span class="badge bg-info">{{ $payout->network }}</span>
                            </div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Wallet Address:</div>
                            <div class="col-md-8">
                                <div class="wallet-address">{{ $payout->wallet_address }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Details -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-money'></i> Amount Details</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Request Amount:</div>
                            <div class="col-md-8 fs-5 fw-bold">{{ money($payout->amount) }}</div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Processing Fee:</div>
                            <div class="col-md-8 text-danger">- {{ money($payout->fee) }}</div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Net Amount:</div>
                            <div class="col-md-8 fs-5 fw-bold text-success">{{ money($payout->net_amount) }}</div>
                        </div>
                    </div>

                    <!-- Balance Impact -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-wallet'></i> Balance Impact</h6>
                        <div class="card bg-light border-0">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-5">
                                        <small class="text-muted d-block">Balance Before</small>
                                        <h4 class="mb-0 text-primary">{{ money($payout->old_balance) }}</h4>
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center justify-content-center">
                                        <i class='bx bx-right-arrow-alt fs-2 text-danger'></i>
                                    </div>
                                    <div class="col-md-5">
                                        <small class="text-muted d-block">Balance After</small>
                                        <h4 class="mb-0 text-success">{{ money($payout->new_balance) }}</h4>
                                    </div>
                                </div>
                                <div class="text-center mt-3">
                                    <span class="badge bg-danger fs-6">
                                        Deducted: {{ money($payout->amount) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Hash (if completed) -->
                    @if($payout->transaction_hash)
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-link'></i> Blockchain Information</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Transaction Hash:</div>
                            <div class="col-md-8">
                                <div class="wallet-address">{{ $payout->transaction_hash }}</div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Status Information -->
                    @if($payout->status == 2 || $payout->status == 4)
                    <div class="mb-4">
                        <h6 class="text-success mb-3"><i class='bx bx-check-circle'></i> Approval Information</h6>
                        
                        @if($payout->approvedBy)
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Approved By:</div>
                            <div class="col-md-8">{{ $payout->approvedBy->name ?? 'Admin' }}</div>
                        </div>
                        @endif

                        @if($payout->approved_at)
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Approved At:</div>
                            <div class="col-md-8">{{ $payout->approved_at->format('d M Y, h:i A') }}</div>
                        </div>
                        @endif

                        @if($payout->admin_note)
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Admin Note:</div>
                            <div class="col-md-8">
                                <div class="alert alert-info mb-0">{{ $payout->admin_note }}</div>
                            </div>
                        </div>
                        @endif

                        @if($payout->approval_documents)
                        <div class="info-row row">
                            <div class="col-md-4 fw-bold">Approval Documents:</div>
                            <div class="col-md-8">
                                <div class="alert alert-success mb-2">
                                    <i class='bx bx-info-circle me-2'></i>
                                    <strong>Proof of payment documents uploaded by admin</strong>
                                </div>
                                <div class="list-group">
                                    @php
                                        $documents = json_decode($payout->approval_documents, true);
                                    @endphp
                                    @foreach($documents as $doc)
                                        <a href="{{ asset('storage/' . $doc['path']) }}" 
                                           target="_blank"
                                           class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                            <div>
                                                <i class='bx bx-file me-2'></i>
                                                <span>{{ $doc['filename'] }}</span>
                                            </div>
                                            <div>
                                                <small class="text-muted me-2">Uploaded: {{ \Carbon\Carbon::parse($doc['uploaded_at'])->format('M d, Y h:i A') }}</small>
                                                <span class="badge bg-primary">
                                                    <i class='bx bx-download'></i> View/Download
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($payout->status == 3)
                    <div class="alert alert-danger">
                        <h6 class="alert-heading"><i class='bx bx-error-circle'></i> Rejection Information</h6>
                        <p class="mb-0"><strong>Reason:</strong> {{ $payout->reject_reason ?? 'No reason provided' }}</p>
                    </div>
                    @endif

                    <!-- Status Timeline -->
                    <div class="mt-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-time'></i> Status Timeline</h6>
                        <div class="timeline">
                            <div class="d-flex align-items-start mb-3">
                                <div class="badge bg-success rounded-circle p-2 me-3">
                                    <i class='bx bx-check'></i>
                                </div>
                                <div>
                                    <strong>Request Created</strong>
                                    <div class="text-muted small">{{ $payout->created_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>

                            @if($payout->status >= 1)
                            <div class="d-flex align-items-start mb-3">
                                <div class="badge bg-info rounded-circle p-2 me-3">
                                    <i class='bx bx-loader'></i>
                                </div>
                                <div>
                                    <strong>Processing</strong>
                                    <div class="text-muted small">Under review by admin</div>
                                </div>
                            </div>
                            @endif

                            @if($payout->status == 2 || $payout->status == 4)
                            <div class="d-flex align-items-start mb-3">
                                <div class="badge bg-success rounded-circle p-2 me-3">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div>
                                    <strong>Approved</strong>
                                    <div class="text-muted small">
                                        {{ $payout->approved_at ? $payout->approved_at->format('d M Y, h:i A') : 'Recently' }}
                                    </div>
                                </div>
                            </div>
                            @endif

                            @if($payout->status == 4)
                            <div class="d-flex align-items-start">
                                <div class="badge bg-primary rounded-circle p-2 me-3">
                                    <i class='bx bx-check-shield'></i>
                                </div>
                                <div>
                                    <strong>Completed</strong>
                                    <div class="text-muted small">Payment sent to wallet</div>
                                </div>
                            </div>
                            @endif

                            @if($payout->status == 3)
                            <div class="d-flex align-items-start">
                                <div class="badge bg-danger rounded-circle p-2 me-3">
                                    <i class='bx bx-x'></i>
                                </div>
                                <div>
                                    <strong>Rejected</strong>
                                    <div class="text-muted small">{{ $payout->updated_at->format('d M Y, h:i A') }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
