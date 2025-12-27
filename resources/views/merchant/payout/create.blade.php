@extends('merchant.mrc_app')
@section('title', 'Crypto Payout Request')

@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"/>
    <style>
        .crypto-card {
            transition: all 0.3s ease;
        }
        .crypto-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
@endpush

@section('mrc_content')
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session()->has('alert'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('alert') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Payout</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('merchant_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Crypto Payout Request</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('merchant.payout-history') }}" class="btn btn-primary">
                <i class='bx bx-history'></i> Payout History
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card crypto-card">
                <div class="card-header bg-gradient-primary text-white py-3">
                    <h5 class="mb-0"><i class='bx bx-wallet me-2'></i>Crypto Payout Request</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Balance Display -->
                    <div class="alert alert-info d-flex align-items-center mb-4">
                        <i class='bx bx-wallet-alt fs-3 me-3'></i>
                        <div class="flex-grow-1">
                            <h6 class="mb-1">Available Balance ({{ $merchantCurrency }})</h6>
                            <h4 class="mb-0 fw-bold">
                                {{ number_format($availableBalance, 2) }} {{ $merchantCurrency }}
                            </h4>
                        </div>
                    </div>

                    <form method="post" action="{{ route('merchant.payout-store') }}" class="row g-3">
                        @csrf
                        
                        <input type="hidden" name="currency" value="{{ $merchantCurrency }}">
                        <input type="hidden" name="payout_currency" id="payout_currency_hidden" value="{{ $merchantCurrency }}">

                        <div class="col-md-12">
                            <label class="form-label">
                                <i class='bx bx-money'></i> Your Account Currency (Base Currency)
                            </label>
                            <input type="text" class="form-control bg-light" value="{{ $merchantCurrency }} - {{ $currencies->where('currency_code', $merchantCurrency)->first()->currency_name ?? '' }}" readonly disabled>
                            <small class="text-muted">All amounts will be entered in this currency</small>
                        </div>

                        <div class="col-md-12">
                            <label for="payout_currency" class="form-label">
                                <i class='bx bx-transfer'></i> Select Payout Currency <span class="text-danger">*</span>
                            </label>
                            <select class="form-select" id="payout_currency" required>
                                @foreach($currencies as $curr)
                                    <option value="{{ $curr->currency_code }}" 
                                            data-rate="{{ $curr->exchange_rate_to_bdt }}"
                                            data-symbol="{{ $curr->currency_symbol }}"
                                            data-fee="{{ $curr->fee_percentage ?? 3.00 }}"
                                            {{ $merchantCurrency == $curr->currency_code ? 'selected' : '' }}>
                                        {{ $curr->currency_code }} - {{ $curr->currency_name }}
                                        @if($curr->currency_symbol) ({{ $curr->currency_symbol }}) @endif
                                    </option>
                                @endforeach
                            </select>
                            <small class="text-muted">Choose which currency you want to receive in your crypto wallet (Fee: <span id="fee_display">{{ number_format($feePercentage, 2) }}</span>%)</small>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-light border">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Exchange Rate:</span>
                                    <span id="exchange_rate_display" class="fw-bold text-primary">
                                        1 {{ $merchantCurrency }} = {{ number_format($exchangeRate, 6) }} {{ $merchantCurrency }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label for="wallet_address" class="form-label">
                                <i class='bx bx-credit-card'></i> Wallet Address <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control" id="wallet_address" name="wallet_address" rows="2" 
                                      placeholder="Enter your crypto wallet address" required>{{ old('wallet_address') }}</textarea>
                            <small class="text-muted">Make sure your wallet address is correct. Payments cannot be reversed!</small>
                        </div>

                        <div class="col-md-12">
                            <label for="amount" class="form-label">
                                <i class='bx bx-money'></i> Enter Amount in {{ $merchantCurrency }} <span class="text-danger">*</span>
                            </label>
                            <input type="number" step="0.01" max="{{ $availableBalance }}" 
                                   class="form-control form-control-lg" id="amount" name="amount" 
                                   placeholder="Enter amount in {{ $merchantCurrency }}" value="{{ old('amount') }}" required>
                            <small class="text-muted">
                                Minimum: 1.00 | Maximum: {{ number_format($availableBalance, 2) }} {{ $merchantCurrency }}
                            </small>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-success border-success">
                                <h6 class="mb-2"><i class='bx bx-calculator'></i> Converted Amount</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>You entered:</span>
                                    <h5 class="mb-0"><span id="amount_display">0.00</span> {{ $merchantCurrency }}</h5>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">You will receive (in crypto):</span>
                                    <h4 class="mb-0 text-success">
                                        <span id="converted_amount">0.00</span> <span id="payout-currency-code">{{ $merchantCurrency }}</span>
                                    </h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-light border">
                                <h6 class="mb-2"><i class='bx bx-receipt'></i> Transaction Summary (in {{ $merchantCurrency }})</h6>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Current Balance:</span>
                                    <span id="current_balance" class="fw-bold text-primary">{{ number_format($availableBalance, 2) }} {{ $merchantCurrency }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Request Amount:</span>
                                    <span id="request_amount" class="fw-bold">0.00 {{ $merchantCurrency }}</span>
                                </div>
                                <div class="d-flex justify-content-between mb-1">
                                    <span>Processing Fee (<span id="fee_percentage_display">{{ number_format($feePercentage, 2) }}</span>%):</span>
                                    <span id="processing_fee" class="fw-bold text-danger">0.00 {{ $merchantCurrency }}</span>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="fw-bold">Total Deducted:</span>
                                    <span id="net_amount_deducted" class="fw-bold text-danger">0.00 {{ $merchantCurrency }}</span>
                                </div>
                                <div class="alert alert-info mb-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold"><i class='bx bx-wallet'></i> Remaining Balance:</span>
                                        <h5 class="mb-0 text-success" id="remaining_balance">{{ number_format($availableBalance, 2) }} {{ $merchantCurrency }}</h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                                </small>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="alert alert-warning">
                                <i class='bx bx-info-circle me-2'></i>
                                <strong>Important:</strong> 
                                <ul class="mb-0 mt-2">
                                    <li>Double-check your wallet address before submitting</li>
                                    <li>Processing time: 1-24 hours after admin approval</li>
                                    <li>Processing fee: {{ $feePercentage }}% of the requested amount</li>
                                    <li>Minimum withdrawal amount: 1.00</li>
                                    <li>Your balance will be deducted immediately</li>
                                </ul>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class='bx bx-send me-2'></i>Submit Payout Request
                                </button>
                                <a href="{{ route('merchant.payout-history') }}" class="btn btn-outline-secondary">
                                    <i class='bx bx-arrow-back me-2'></i>Back to History
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function() {
        // Exchange rates and balance data
        const accountCurrency = '{{ $merchantCurrency }}';
        const accountExchangeRate = {{ $exchangeRate }};
        let payoutCurrency = $('#payout_currency').val() || '{{ $merchantCurrency }}';
        let payoutExchangeRate = parseFloat($('#payout_currency').find(':selected').data('rate')) || {{ $exchangeRate }};
        let feePercentage = parseFloat($('#payout_currency').find(':selected').data('fee')) || {{ $feePercentage }};

        // Initialize Select2
        $('#payout_currency').select2({
            theme: 'bootstrap-5'
        });

        // Update exchange rate display on page load
        updateExchangeRate();

        // Handle payout currency change
        $('#payout_currency').on('change', function() {
            const selectedOption = $(this).find(':selected');
            payoutCurrency = $(this).val();
            payoutExchangeRate = parseFloat(selectedOption.data('rate'));
            feePercentage = parseFloat(selectedOption.data('fee'));

            // Update hidden input
            $('#payout_currency_hidden').val(payoutCurrency);
            
            // Update fee display
            $('#fee_display').text(feePercentage.toFixed(2));
            $('#fee_percentage_display').text(feePercentage.toFixed(2));

            updateExchangeRate();

            // Recalculate if amount is entered
            if ($('#amount').val()) {
                calculateTransaction();
            }
        });

        function updateExchangeRate() {
            // Update currency display
            $('#payout-currency-code').text(payoutCurrency);

            // Update exchange rate display
            if (payoutCurrency === accountCurrency) {
                $('#exchange_rate_display').text('1 ' + accountCurrency + ' = 1 ' + accountCurrency + ' (Same Currency)');
            } else {
                // Calculate conversion rate from account currency to payout currency
                // Both rates are relative to BDT, so: 1 AccountCurrency = (accountRate/payoutRate) PayoutCurrency
                const conversionRate = accountExchangeRate / payoutExchangeRate;
                $('#exchange_rate_display').text(
                    '1 ' + accountCurrency + ' = ' + conversionRate.toFixed(6) + ' ' + payoutCurrency + ' | 1 ' + payoutCurrency + ' = ' + (1/conversionRate).toFixed(6) + ' ' + accountCurrency
                );
            }
        }

        // Calculate transaction
        $('#amount').on('input', calculateTransaction);

        function calculateTransaction() {
            const currentBalance = {{ $availableBalance }};
            const amountInAccountCurrency = parseFloat($('#amount').val()) || 0;
            
            // Calculate fee (always in account currency)
            const fee = amountInAccountCurrency * (feePercentage / 100);
            const netAmount = amountInAccountCurrency - fee;
            const totalDeducted = amountInAccountCurrency; // Full amount including fee
            const remainingBalance = currentBalance - totalDeducted;
            
            // Convert to payout currency
            let convertedAmount;
            if (payoutCurrency === accountCurrency) {
                convertedAmount = netAmount;
            } else {
                // Convert account currency to BDT first (if not BDT)
                const amountInBDT = accountCurrency === 'BDT' 
                    ? netAmount 
                    : netAmount * accountExchangeRate;
                
                // Convert BDT to payout currency
                convertedAmount = payoutCurrency === 'BDT' 
                    ? amountInBDT 
                    : amountInBDT / payoutExchangeRate;
            }

            // Update displays
            $('#amount_display').text(amountInAccountCurrency.toFixed(2));
            $('#converted_amount').text(convertedAmount.toFixed(6));
            $('#request_amount').text(amountInAccountCurrency.toFixed(2));
            $('#processing_fee').text(fee.toFixed(2));
            $('#net_amount_deducted').text(totalDeducted.toFixed(2));
            $('#remaining_balance').text(remainingBalance.toFixed(2) + ' ' + accountCurrency);
            
            // Color code remaining balance
            if (remainingBalance < 0) {
                $('#remaining_balance').removeClass('text-success').addClass('text-danger');
            } else if (remainingBalance < currentBalance * 0.1) {
                $('#remaining_balance').removeClass('text-success text-danger').addClass('text-warning');
            } else {
                $('#remaining_balance').removeClass('text-danger text-warning').addClass('text-success');
            }
        }

        // Trigger initial calculation if amount exists
        if ($('#amount').val()) {
            calculateTransaction();
        }
    });
</script>
@endpush