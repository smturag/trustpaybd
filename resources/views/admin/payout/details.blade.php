@extends('admin.layouts.admin_app')
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
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            font-size: 13px;
        }
    </style>
@endpush

@section('content')
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Merchant Management</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.merchant-payout.index') }}">Crypto Payout</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Details</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.merchant-payout.index') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to List
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-10 mx-auto">
            <div class="card detail-card">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0"><i class='bx bxl-bitcoin me-2'></i>Payout Request Details</h5>
                        <span class="badge bg-{{ $payout->status_color }} fs-6">{{ $payout->status_text }}</span>
                    </div>
                </div>
                <div class="card-body p-4">
                    <!-- Quick Actions -->
                    @if($payout->status == 0)
                    <div class="alert alert-warning mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class='bx bx-info-circle fs-4 me-2'></i>
                                <strong>Action Required:</strong> This payout is pending approval
                            </div>
                            <div>
                                <a href="{{ route('admin.merchant-payout.approve-form', $payout->id) }}" 
                                   class="btn btn-success btn-sm me-2">
                                    <i class='bx bx-check'></i> Approve
                                </a>
                                <a href="{{ route('admin.merchant-payout.reject-form', $payout->id) }}" 
                                   class="btn btn-danger btn-sm">
                                    <i class='bx bx-x'></i> Reject
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaction Info -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-receipt'></i> Transaction Information</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Payout ID:</div>
                            <div class="col-md-9">
                                <code class="fs-6">{{ $payout->payout_id }}</code>
                            </div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Request Date:</div>
                            <div class="col-md-9">
                                {{ $payout->created_at->format('d M Y, h:i A') }}
                                <small class="text-muted">({{ $payout->created_at->diffForHumans() }})</small>
                            </div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Last Updated:</div>
                            <div class="col-md-9">
                                {{ $payout->updated_at->format('d M Y, h:i A') }}
                                <small class="text-muted">({{ $payout->updated_at->diffForHumans() }})</small>
                            </div>
                        </div>
                    </div>

                    <!-- Merchant Details -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-store'></i> Merchant Information</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Merchant Name:</div>
                            <div class="col-md-9">
                                <strong>{{ $payout->merchant->fullname ?? 'N/A' }}</strong>
                                <span class="badge bg-info ms-2">ID: {{ $payout->merchant_id }}</span>
                            </div>
                        </div>

                        @if($payout->merchant)
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Merchant Email:</div>
                            <div class="col-md-9">{{ $payout->merchant->email ?? 'N/A' }}</div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Merchant Phone:</div>
                            <div class="col-md-9">{{ $payout->merchant->mobile ?? 'N/A' }}</div>
                        </div>
                        @endif

                        @if($payout->subMerchant)
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Sub Merchant:</div>
                            <div class="col-md-9">
                                {{ $payout->subMerchant->fullname }}
                                <span class="badge bg-secondary ms-2">ID: {{ $payout->sub_merchant }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <!-- Crypto Details -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-dollar-circle'></i> Cryptocurrency Details</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Currency:</div>
                            <div class="col-md-9">
                                <span class="fs-5">{{ strtoupper($payout->currency_name) }}</span>
                            </div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Network:</div>
                            <div class="col-md-9">
                                <span class="badge bg-info fs-6">{{ $payout->network }}</span>
                            </div>
                        </div>

                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Wallet Address:</div>
                            <div class="col-md-9">
                                <div class="wallet-address">{{ $payout->wallet_address }}</div>
                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="copyToClipboard('{{ $payout->wallet_address }}')">
                                    <i class='bx bx-copy'></i> Copy Address
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Amount Details -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-money'></i> Amount Details</h6>
                        
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <tr>
                                    <td class="fw-bold bg-light">Request Amount:</td>
                                    <td class="text-end fs-5 fw-bold">{{ money($payout->amount) }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-bold bg-light">Processing Fee ({{ number_format(($payout->fee / $payout->amount) * 100, 2) }}%):</td>
                                    <td class="text-end text-danger">- {{ money($payout->fee) }}</td>
                                </tr>
                                <tr class="table-success">
                                    <td class="fw-bold">Net Amount (To be paid):</td>
                                    <td class="text-end fs-4 fw-bold text-success">{{ money($payout->net_amount) }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Merchant Balance Impact -->
                    <div class="mb-4">
                        <h6 class="text-primary mb-3"><i class='bx bx-wallet'></i> Merchant Balance Impact</h6>
                        <div class="card border-primary">
                            <div class="card-body">
                                <div class="row text-center">
                                    <div class="col-md-4">
                                        <div class="border-end">
                                            <small class="text-muted d-block mb-2">Balance Before Payout</small>
                                            <h3 class="mb-0 text-primary">{{ money($payout->old_balance) }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="border-end">
                                            <small class="text-muted d-block mb-2">Amount Deducted</small>
                                            <h3 class="mb-0 text-danger">- {{ money($payout->amount) }}</h3>
                                            <small class="text-muted">(Fee: {{ money($payout->fee) }})</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <small class="text-muted d-block mb-2">Balance After Payout</small>
                                        <h3 class="mb-0 text-success">{{ money($payout->new_balance) }}</h3>
                                    </div>
                                </div>
                                <div class="text-center mt-3 pt-3 border-top">
                                    <div class="d-flex justify-content-center align-items-center">
                                        <span class="badge bg-info me-2">Balance Change:</span>
                                        <strong class="text-danger fs-5">{{ money($payout->old_balance) }} → {{ money($payout->new_balance) }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Transaction Hash (if completed) -->
                    @if($payout->transaction_hash)
                    <div class="mb-4">
                        <h6 class="text-success mb-3"><i class='bx bx-link'></i> Blockchain Information</h6>
                        
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Transaction Hash:</div>
                            <div class="col-md-9">
                                <div class="wallet-address">{{ $payout->transaction_hash }}</div>
                                <button class="btn btn-sm btn-outline-primary mt-2" onclick="copyToClipboard('{{ $payout->transaction_hash }}')">
                                    <i class='bx bx-copy'></i> Copy Hash
                                </button>
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
                            <div class="col-md-3 fw-bold">Approved By:</div>
                            <div class="col-md-9">{{ $payout->approvedBy->name ?? 'Admin' }}</div>
                        </div>
                        @endif

                        @if($payout->approved_at)
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Approved At:</div>
                            <div class="col-md-9">{{ $payout->approved_at->format('d M Y, h:i A') }}</div>
                        </div>
                        @endif

                        @if($payout->admin_note)
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Admin Note:</div>
                            <div class="col-md-9">
                                <div class="alert alert-info mb-0">{{ $payout->admin_note }}</div>
                            </div>
                        </div>
                        @endif

                        @if($payout->approval_documents)
                        <div class="info-row row">
                            <div class="col-md-3 fw-bold">Approval Documents:</div>
                            <div class="col-md-9">
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
                                                <small class="text-muted me-2">{{ \Carbon\Carbon::parse($doc['uploaded_at'])->format('M d, Y h:i A') }}</small>
                                                <i class='bx bx-download'></i>
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
                        @if($payout->approvedBy)
                            <p class="mb-1"><strong>Rejected By:</strong> {{ $payout->approvedBy->name ?? 'Admin' }}</p>
                        @endif
                        @if($payout->approved_at)
                            <p class="mb-1"><strong>Rejected At:</strong> {{ $payout->approved_at->format('d M Y, h:i A') }}</p>
                        @endif
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
                                    <div class="text-muted small">Under admin review</div>
                                </div>
                            </div>
                            @endif

                            @if($payout->status == 2 || $payout->status == 4)
                            <div class="d-flex align-items-start mb-3">
                                <div class="badge bg-success rounded-circle p-2 me-3">
                                    <i class='bx bx-check-double'></i>
                                </div>
                                <div>
                                    <strong>Approved by Admin</strong>
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

@push('js')
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            alert('Copied to clipboard!');
        }, function(err) {
            console.error('Could not copy text: ', err);
        });
    }
</script>
@endpush
