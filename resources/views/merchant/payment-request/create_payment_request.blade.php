@extends('merchant.mrc_app')
@section('title', 'Create Deposit')
@section('mrc_content')

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h4 class="fw-bold mb-0">💰 Create New Deposit</h4>
             
            </div>

            {{-- Alerts --}}
            @if (session()->has('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif
            @if (Session::has('alert'))
                <div class="alert alert-danger">{{ Session::get('alert') }}</div>
            @endif
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Deposit Form --}}
            <form action="{{ route('merchant.deposit.store') }}" method="POST">
                @csrf

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Amount *</label>
                        <input type="number" name="amount" class="form-control form-control-lg"
                            placeholder="Enter deposit amount" required value="{{ old('amount') }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">From Number (Optional)</label>
                        <input type="text" name="from_number" class="form-control form-control-lg"
                            placeholder="e.g. 017xxxxxxxx" value="{{ old('from_number') }}">
                    </div>
                </div>

                <h6 class="fw-bold mb-3">Select Payment Method *</h6>

                {{-- Payment Gateways Grid --}}
                <div class="logo-wrap col-12 mx-auto mb-4" id="mfs-operator-div">
                    @foreach ($mfsList as $item)
                        <div class="bank-img-div"
                            data-method="{{ $item['deposit_method'] }}"
                            data-number="{{ $item['deposit_number'] }}"
                            data-type="{{ $item['type'] }}">
                            <img src="{{ $item['icon'] }}" alt="{{ $item['deposit_method'] }}" class="bank-img">
                            <div class="gateway-info">
                                <div><strong>{{ ucfirst($item['deposit_method']) }}</strong></div>
                                <div>{{ $item['deposit_number'] }}</div>
                                <span class="gateway-type">
                                    @if($item['type'] == 'P2A') Send Money
                                    @elseif($item['type'] == 'P2C') Cash In
                                    @elseif($item['type'] == 'P2P') Peer Transfer
                                    @else {{ $item['type'] }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Hidden Fields (filled by JS) --}}
                <input type="hidden" name="payment_method" id="selected_method">
                <input type="hidden" name="deposit_number" id="selected_number">
                <input type="hidden" name="account_type" id="selected_type">

                {{-- Transaction Info --}}
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Transaction ID *</label>
                                <input type="text" name="transaction_id" class="form-control form-control-lg"
                                    placeholder="Enter transaction ID" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Note (Optional)</label>
                                <textarea name="note" class="form-control form-control-lg" rows="1"
                                    placeholder="Write a note if needed..."></textarea>
                            </div>
                        </div>
                        <div class="text-end mt-4">
                            <button type="submit" class="btn btn-success btn-lg px-5">
                                <i class="bi bi-send"></i> Submit Deposit
                            </button>
                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

@endsection

@push('css')
<style>
    /* Compact Responsive Gateway Grid */
    .logo-wrap {
        display: grid;
        gap: 0.75rem;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        margin: 1.5rem auto;
    }

    .bank-img-div {
        border: 1px solid #ddd;
        border-radius: 12px;
        background: #fff;
        text-align: center;
        padding: 10px 6px;
        height: 110px;
        cursor: pointer;
        transition: all .2s ease-in-out;
    }

    .bank-img-div:hover {
        box-shadow: 0 4px 12px rgba(0, 0, 0, .08);
        transform: translateY(-2px);
    }

    .bank-img-div.active {
        border: 2px solid #28a745;
        box-shadow: 0 4px 14px rgba(40, 167, 69, .3);
        background-color: #f9fff9;
    }

    .bank-img {
        height: 40px;
        width: auto;
        object-fit: contain;
        margin-bottom: 5px;
    }

    .gateway-info {
        font-size: 13px;
        color: #444;
        line-height: 1.2;
    }

    .gateway-type {
        display: inline-block;
        font-size: 11px;
        background: #f1f1f1;
        border-radius: 5px;
        padding: 2px 6px;
        margin-top: 2px;
        color: #555;
        font-weight: 500;
    }

    @media (max-width: 576px) {
        .bank-img-div {
            height: 90px;
            padding: 8px 4px;
        }

        .bank-img {
            height: 32px;
        }

        .gateway-info {
            font-size: 12px;
        }
    }
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const gateways = document.querySelectorAll('.bank-img-div');
    const methodField = document.getElementById('selected_method');
    const numberField = document.getElementById('selected_number');
    const typeField = document.getElementById('selected_type');

    gateways.forEach(card => {
        card.addEventListener('click', () => {
            gateways.forEach(c => c.classList.remove('active'));
            card.classList.add('active');

            methodField.value = card.dataset.method;
            numberField.value = card.dataset.number;
            typeField.value = card.dataset.type;
        });
    });
});
</script>
@endpush
