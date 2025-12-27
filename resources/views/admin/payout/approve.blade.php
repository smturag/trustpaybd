@extends('admin.layouts.admin_app')
@section('title', 'Approve Payout')

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
                    <li class="breadcrumb-item"><a href="{{ route('admin.merchant-payout.index') }}">Crypto Payout</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Approve</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Payout Summary Card -->
            <div class="card mb-3">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0"><i class='bx bx-info-circle me-2'></i>Payout Request Summary</h5>
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
                            <p><strong>Account Currency:</strong> <span class="badge bg-primary">{{ $payout->merchant_currency ?? 'BDT' }}</span></p>
                            <p><strong>Merchant Amount:</strong> <strong class="text-primary">{{ number_format($payout->merchant_amount, 2) }} {{ $payout->merchant_currency ?? 'BDT' }}</strong></p>
                            <p><strong>Request Amount (BDT):</strong> <strong class="text-info">{{ money($payout->amount) }}</strong></p>
                            <p><strong>Net Amount (BDT):</strong> <strong class="text-success fs-5">{{ money($payout->net_amount) }}</strong></p>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-12">
                            <div class="alert alert-info mb-0">
                                <strong><i class='bx bx-wallet me-2'></i>Merchant Balance:</strong>
                                <span class="text-primary">Before: {{ money($payout->old_balance) }}</span>
                                <i class='bx bx-right-arrow-alt mx-2'></i>
                                <span class="text-success">After: {{ money($payout->new_balance) }}</span>
                                <span class="text-danger ms-2">(Deducted: {{ money($payout->amount) }})</span>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <p class="mb-0"><strong>Wallet Address:</strong></p>
                    <div class="p-2 bg-light rounded font-monospace small">
                        {{ $payout->wallet_address }}
                    </div>
                </div>
            </div>

            <!-- Approve Form -->
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0"><i class='bx bx-check-circle me-2'></i>Approve Payout Request</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.merchant-payout.approve', $payout->id) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle me-2'></i>
                            <strong>Important:</strong> Make sure you have sent <strong>{{ number_format($payout->merchant_amount, 2) }} {{ $payout->merchant_currency ?? 'BDT' }}</strong> (equivalent to {{ money($payout->net_amount) }} BDT after fees) to the wallet address above before approving this request.
                        </div>

                        <div class="mb-3">
                            <label for="transaction_hash" class="form-label">
                                <i class='bx bx-link'></i> Transaction Hash / TxID <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control" 
                                   id="transaction_hash" 
                                   name="transaction_hash" 
                                   placeholder="Enter blockchain transaction hash"
                                   value="{{ old('transaction_hash') }}"
                                   required>
                            <small class="text-muted">Enter the transaction hash from the blockchain after sending payment</small>
                        </div>

                        <div class="mb-3">
                            <label for="documents" class="form-label">
                                <i class='bx bx-file'></i> Upload Documents <span class="text-danger">*</span>
                            </label>
                            <input type="file" 
                                   class="form-control" 
                                   id="documents" 
                                   name="documents[]" 
                                   multiple
                                   accept=".jpg,.jpeg,.png,.pdf,.doc,.docx"
                                   required>
                            <small class="text-muted">Upload proof of payment (screenshots, PDFs, etc.). Max 5MB per file. Required before approval.</small>
                            <div id="file-preview" class="mt-2"></div>
                        </div>

                        <div class="mb-3">
                            <label for="admin_note" class="form-label">
                                <i class='bx bx-note'></i> Admin Note (Optional)
                            </label>
                            <textarea class="form-control" 
                                      id="admin_note" 
                                      name="admin_note" 
                                      rows="3" 
                                      placeholder="Add any notes or comments">{{ old('admin_note') }}</textarea>
                            <small class="text-muted">This note will be visible to the merchant</small>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class='bx bx-error-circle'></i> Confirmation Required</h6>
                            <p class="mb-2">By approving this payout request, you confirm that:</p>
                            <ul class="mb-0">
                                <li>You have verified the wallet address</li>
                                <li>You have sent <strong>{{ number_format($payout->merchant_amount, 2) }} {{ $payout->merchant_currency ?? 'BDT' }}</strong> to the merchant's wallet</li>
                                <li>The transaction has been completed successfully</li>
                                <li>You have uploaded proof documents (transaction receipt, screenshots, etc.)</li>
                                <li>This action cannot be undone</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class='bx bx-check-double me-2'></i>Confirm & Approve Payout
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
document.getElementById('documents').addEventListener('change', function(e) {
    const filePreview = document.getElementById('file-preview');
    filePreview.innerHTML = '';
    
    if (this.files.length > 0) {
        const fileList = document.createElement('div');
        fileList.className = 'list-group';
        
        Array.from(this.files).forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'list-group-item d-flex justify-content-between align-items-center';
            fileItem.innerHTML = `
                <div>
                    <i class='bx bx-file me-2'></i>
                    <span>${file.name}</span>
                    <small class="text-muted ms-2">(${(file.size / 1024 / 1024).toFixed(2)} MB)</small>
                </div>
            `;
            fileList.appendChild(fileItem);
        });
        
        filePreview.appendChild(fileList);
    }
});
</script>
@endpush
