@extends('admin.layouts.admin_app')
@section('title', 'Payout Fee Settings')

@section('content')
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" id="alert_success">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

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

    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="breadcrumb-title pe-3">Settings</div>
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin_dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.merchant-payout.index') }}">Crypto Payout</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Fee Settings</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('admin.merchant-payout.index') }}" class="btn btn-secondary">
                <i class='bx bx-arrow-back'></i> Back to Payouts
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Current Settings Card -->
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0"><i class='bx bx-info-circle me-2'></i>Current Settings</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Payout Fee Percentage</h6>
                            <h2 class="text-primary mb-0">{{ $feePercentage }}%</h2>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted mb-2">Example Calculation</h6>
                            <p class="mb-0">
                                Amount: <strong>৳ 1,000.00</strong><br>
                                Fee: <strong class="text-danger">৳ {{ number_format(1000 * ($feePercentage / 100), 2) }}</strong><br>
                                Net: <strong class="text-success">৳ {{ number_format(1000 - (1000 * ($feePercentage / 100)), 2) }}</strong>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Update Settings Form -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class='bx bx-cog me-2'></i>Update Payout Fee Settings</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('admin.merchant-payout.update-settings') }}">
                        @csrf

                        <div class="alert alert-info">
                            <i class='bx bx-info-circle me-2'></i>
                            <strong>Note:</strong> The fee percentage will be applied to all new payout requests. Existing pending requests will use the fee calculated at the time of their creation.
                        </div>

                        <div class="mb-4">
                            <label for="fee_percentage" class="form-label">
                                <i class='bx bx-percentage'></i> Payout Fee Percentage <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-lg">
                                <input type="number" 
                                       step="0.01" 
                                       min="0" 
                                       max="100" 
                                       class="form-control" 
                                       id="fee_percentage" 
                                       name="fee_percentage" 
                                       value="{{ old('fee_percentage', $feePercentage) }}" 
                                       required>
                                <span class="input-group-text">%</span>
                            </div>
                            <small class="text-muted">Enter a value between 0 and 100 (e.g., 1 for 1%, 0.5 for 0.5%, 2.5 for 2.5%)</small>
                        </div>

                        <!-- Fee Calculation Preview -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3"><i class='bx bx-calculator'></i> Fee Calculation Preview</h6>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered mb-0">
                                        <thead>
                                            <tr>
                                                <th>Request Amount</th>
                                                <th>Fee (at <span id="preview_percentage">{{ $feePercentage }}</span>%)</th>
                                                <th>Net Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody id="preview_table">
                                            <tr>
                                                <td>৳ 1,000.00</td>
                                                <td class="text-danger">৳ <span class="fee_1000">{{ number_format(1000 * ($feePercentage / 100), 2) }}</span></td>
                                                <td class="text-success">৳ <span class="net_1000">{{ number_format(1000 - (1000 * ($feePercentage / 100)), 2) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>৳ 5,000.00</td>
                                                <td class="text-danger">৳ <span class="fee_5000">{{ number_format(5000 * ($feePercentage / 100), 2) }}</span></td>
                                                <td class="text-success">৳ <span class="net_5000">{{ number_format(5000 - (5000 * ($feePercentage / 100)), 2) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>৳ 10,000.00</td>
                                                <td class="text-danger">৳ <span class="fee_10000">{{ number_format(10000 * ($feePercentage / 100), 2) }}</span></td>
                                                <td class="text-success">৳ <span class="net_10000">{{ number_format(10000 - (10000 * ($feePercentage / 100)), 2) }}</span></td>
                                            </tr>
                                            <tr>
                                                <td>৳ 50,000.00</td>
                                                <td class="text-danger">৳ <span class="fee_50000">{{ number_format(50000 * ($feePercentage / 100), 2) }}</span></td>
                                                <td class="text-success">৳ <span class="net_50000">{{ number_format(50000 - (50000 * ($feePercentage / 100)), 2) }}</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning">
                            <h6 class="alert-heading"><i class='bx bx-error-circle'></i> Important</h6>
                            <ul class="mb-0">
                                <li>This fee will be deducted from merchant payouts</li>
                                <li>Changes take effect immediately for new payout requests</li>
                                <li>Merchants will see the fee before confirming their payout</li>
                                <li>Setting fee to 0 means no fee will be charged</li>
                            </ul>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class='bx bx-save me-2'></i>Update Fee Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        // Auto-hide success message
        setTimeout(function() {
            $('#alert_success').fadeOut('slow');
        }, 3000);

        // Real-time fee calculation preview
        $('#fee_percentage').on('input', function() {
            const percentage = parseFloat($(this).val()) || 0;
            $('#preview_percentage').text(percentage);

            // Calculate for different amounts
            const amounts = [1000, 5000, 10000, 50000];
            amounts.forEach(amount => {
                const fee = amount * (percentage / 100);
                const net = amount - fee;
                
                $(`.fee_${amount}`).text(formatNumber(fee));
                $(`.net_${amount}`).text(formatNumber(net));
            });
        });

        function formatNumber(num) {
            return num.toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
        }
    });
</script>
@endpush
