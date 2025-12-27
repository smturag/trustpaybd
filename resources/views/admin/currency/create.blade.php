@extends('admin.layouts.admin_app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.currency.index') }}">Currency Management</a></li>
                <li class="breadcrumb-item active">Add New Currency</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Add New Currency</h1>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('admin.currency.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="currency_code" class="form-label">
                                Currency Code <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('currency_code') is-invalid @enderror" 
                                   id="currency_code" 
                                   name="currency_code" 
                                   value="{{ old('currency_code') }}"
                                   placeholder="e.g., USD, EUR, GBP"
                                   maxlength="10"
                                   style="text-transform: uppercase;"
                                   required>
                            <small class="text-muted">3-letter ISO currency code (e.g., USD, EUR)</small>
                            @error('currency_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="currency_name" class="form-label">
                                Currency Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('currency_name') is-invalid @enderror" 
                                   id="currency_name" 
                                   name="currency_name" 
                                   value="{{ old('currency_name') }}"
                                   placeholder="e.g., US Dollar"
                                   required>
                            @error('currency_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="currency_symbol" class="form-label">
                                Currency Symbol
                            </label>
                            <input type="text" 
                                   class="form-control @error('currency_symbol') is-invalid @enderror" 
                                   id="currency_symbol" 
                                   name="currency_symbol" 
                                   value="{{ old('currency_symbol') }}"
                                   placeholder="e.g., $, €, £"
                                   maxlength="10">
                            <small class="text-muted">Optional symbol for display</small>
                            @error('currency_symbol')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="exchange_rate_to_bdt" class="form-label">
                                Exchange Rate to BDT <span class="text-danger">*</span>
                            </label>
                            <input type="number" 
                                   class="form-control @error('exchange_rate_to_bdt') is-invalid @enderror" 
                                   id="exchange_rate_to_bdt" 
                                   name="exchange_rate_to_bdt" 
                                   value="{{ old('exchange_rate_to_bdt') }}"
                                   step="0.000001"
                                   min="0.000001"
                                   placeholder="e.g., 110.50"
                                   required>
                            <small class="text-muted">How many BDT equals 1 unit of this currency</small>
                            @error('exchange_rate_to_bdt')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div id="rate-preview" class="alert alert-info mt-2" style="display: none;">
                                <strong>Preview:</strong><br>
                                <span id="preview-text"></span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="fee_percentage" class="form-label">
                                Payout Fee Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group">
                                <input type="number" 
                                       class="form-control @error('fee_percentage') is-invalid @enderror" 
                                       id="fee_percentage" 
                                       name="fee_percentage" 
                                       value="{{ old('fee_percentage', 3.00) }}"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       placeholder="e.g., 3.00"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Fee percentage charged on payouts in this currency (e.g., 3.00 for 3%)</small>
                            @error('fee_percentage')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="status" class="form-label">
                                Status <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('status') is-invalid @enderror" 
                                    id="status" 
                                    name="status" 
                                    required>
                                <option value="1" {{ old('status', '1') == '1' ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Currency
                            </button>
                            <a href="{{ route('admin.currency.index') }}" class="btn btn-secondary">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm bg-light">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-info-circle"></i> Exchange Rate Guide</h5>
                    <hr>
                    <p><strong>Base Currency:</strong> BDT (Bangladeshi Taka)</p>
                    <p class="mb-3">Enter how many BDT equals 1 unit of the new currency.</p>
                    
                    <div class="bg-white p-3 rounded mb-3">
                        <strong>Examples:</strong>
                        <ul class="mb-0 mt-2">
                            <li>1 USD = 110 BDT<br><small class="text-muted">Enter: 110</small></li>
                            <li>1 EUR = 120 BDT<br><small class="text-muted">Enter: 120</small></li>
                            <li>1 GBP = 140 BDT<br><small class="text-muted">Enter: 140</small></li>
                            <li>1 INR = 1.35 BDT<br><small class="text-muted">Enter: 1.35</small></li>
                        </ul>
                    </div>

                    <div class="alert alert-warning mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Important:</strong><br>
                        <small>Make sure to enter accurate exchange rates. These will be used for all payout calculations.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const codeInput = document.getElementById('currency_code');
    const rateInput = document.getElementById('exchange_rate_to_bdt');
    const ratePreview = document.getElementById('rate-preview');
    const previewText = document.getElementById('preview-text');

    function updatePreview() {
        const code = codeInput.value.toUpperCase();
        const rate = parseFloat(rateInput.value);

        if (code && rate > 0) {
            const bdtToCode = (1 / rate).toFixed(6);
            previewText.innerHTML = `
                1 ${code} = ${rate} BDT<br>
                1 BDT = ${bdtToCode} ${code}
            `;
            ratePreview.style.display = 'block';
        } else {
            ratePreview.style.display = 'none';
        }
    }

    codeInput.addEventListener('input', updatePreview);
    rateInput.addEventListener('input', updatePreview);
});
</script>
@endsection
