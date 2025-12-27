@extends('admin.layouts.admin_app')

@section('content')
<div class="container-fluid px-4">
    <div class="mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.currency.index') }}">Currency Management</a></li>
                <li class="breadcrumb-item active">Edit {{ $currency->currency_code }}</li>
            </ol>
        </nav>
        <h1 class="h3 mb-0">Edit Currency: {{ $currency->currency_name }}</h1>
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
                    <form action="{{ route('admin.currency.update', $currency->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="currency_code" class="form-label">Currency Code</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="currency_code" 
                                   value="{{ $currency->currency_code }}"
                                   disabled
                                   readonly>
                            <small class="text-muted">Currency code cannot be changed</small>
                        </div>

                        <div class="mb-3">
                            <label for="currency_name" class="form-label">
                                Currency Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" 
                                   class="form-control @error('currency_name') is-invalid @enderror" 
                                   id="currency_name" 
                                   name="currency_name" 
                                   value="{{ old('currency_name', $currency->currency_name) }}"
                                   required>
                            @error('currency_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="currency_symbol" class="form-label">Currency Symbol</label>
                            <input type="text" 
                                   class="form-control @error('currency_symbol') is-invalid @enderror" 
                                   id="currency_symbol" 
                                   name="currency_symbol" 
                                   value="{{ old('currency_symbol', $currency->currency_symbol) }}"
                                   maxlength="10">
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
                                   value="{{ old('exchange_rate_to_bdt', $currency->exchange_rate_to_bdt) }}"
                                   step="0.000001"
                                   min="0.000001"
                                   {{ $currency->currency_code === 'BDT' ? 'readonly' : '' }}
                                   required>
                            @if($currency->currency_code === 'BDT')
                                <small class="text-muted">BDT is the base currency. Rate is fixed at 1.00</small>
                            @else
                                <small class="text-muted">How many BDT equals 1 {{ $currency->currency_code }}</small>
                            @endif
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
                                       value="{{ old('fee_percentage', $currency->fee_percentage ?? 3.00) }}"
                                       step="0.01"
                                       min="0"
                                       max="100"
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Fee percentage charged on payouts in this currency</small>
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
                                <option value="1" {{ old('status', $currency->status) == 1 ? 'selected' : '' }}>Active</option>
                                <option value="0" {{ old('status', $currency->status) == 0 ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Currency
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
                    <h5 class="card-title"><i class="fas fa-history"></i> Current Rate Info</h5>
                    <hr>
                    <div class="mb-3">
                        <strong>Current Exchange Rate:</strong><br>
                        <span class="fs-4 text-primary">{{ number_format($currency->exchange_rate_to_bdt, 6) }}</span>
                    </div>

                    <div class="bg-white p-3 rounded mb-3">
                        <strong>Conversions:</strong>
                        <ul class="mb-0 mt-2">
                            <li>1 {{ $currency->currency_code }} = {{ number_format($currency->exchange_rate_to_bdt, 2) }} BDT</li>
                            @if($currency->currency_code !== 'BDT')
                                <li>1 BDT = {{ number_format(1 / $currency->exchange_rate_to_bdt, 6) }} {{ $currency->currency_code }}</li>
                            @endif
                        </ul>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated:</strong><br>
                        <small class="text-muted">
                            {{ $currency->updated_at->format('M d, Y h:i A') }}
                        </small>
                    </div>

                    @if($currency->currency_code !== 'BDT')
                        <div class="alert alert-warning mb-0">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Note:</strong><br>
                            <small>Changing the exchange rate will affect all future payout calculations.</small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const rateInput = document.getElementById('exchange_rate_to_bdt');
    const ratePreview = document.getElementById('rate-preview');
    const previewText = document.getElementById('preview-text');
    const currencyCode = '{{ $currency->currency_code }}';

    @if($currency->currency_code !== 'BDT')
    function updatePreview() {
        const rate = parseFloat(rateInput.value);

        if (rate > 0) {
            const bdtToCode = (1 / rate).toFixed(6);
            previewText.innerHTML = `
                1 ${currencyCode} = ${rate} BDT<br>
                1 BDT = ${bdtToCode} ${currencyCode}
            `;
            ratePreview.style.display = 'block';
        } else {
            ratePreview.style.display = 'none';
        }
    }

    rateInput.addEventListener('input', updatePreview);
    updatePreview();
    @endif
});
</script>
@endsection
