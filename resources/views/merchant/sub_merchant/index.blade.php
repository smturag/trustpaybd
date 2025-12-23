@extends('merchant.mrc_app')
@section('title', 'Dashboard')
@section('mrc_content')

    @push('css')
        <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    @endpush
    @if (session()->has('message'))
        <div class="alert alert-success" id="alert_success">
            {{ session('message') }}
        </div>
    @endif

    @if (Session::has('alert'))
        <div class="alert alert-danger">{{ Session::get('alert') }}</div>
    @endif

    <div class="row">
        <div class="col-xl-12 mx-auto">
            <div class="header d-flex align-items-center mb-3">
                <h6 class="mb-0 text-uppercase ps-3">Sub Merchant List</h6>
                {{-- Uncomment this if you want to add a "New Payment Request" button --}}
                <a class="ms-auto btn btn-sm btn-primary" href="{{ route('sub_merchant.create') }}">
                    <i class="bx bx-plus mr-1"></i> Sub Merchant Create
                </a>
            </div>
            <hr />

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Card for displaying the Public Key --}}
            <div class="card">
                <div class="card-body">
                    <div class="item-content">
                        <div class="mb-3">
                            <table class="table table-striped table-hover" id="myTable">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Merchant ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Balance</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $key => $item)
                                        <tr>
                                            <td>{{ $key++ }}</td>
                                            <td>{{ $item->username }}</td>
                                            <td>{{ $item->fullname }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->mobile }}</td>

                                            <td>
                                                <a href="javascript:void(0)"
                                                   data-id="{{ $item->id }}"
                                                   data-name="{{ $item->fullname }}"
                                                   data-code="{{ $item->username }}"
                                                   data-balance="{{ subMerchantBalance($item->id)['balance'] }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editModal"
                                                   class="btn btn-sm btn-primary text-white editBtn me-2">
                                                    <i class="bx bx-money"></i>
                                                    {{ subMerchantBalance($item->id)['balance'] }}
                                                </a>
                                            </td>
                                            <td><a class="btn btn-sm btn-outline-primary" href="">Edit</a></td>
                                        </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Button trigger for modal -->


    <!-- Modal Structure -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-3">

            @php
                $merchant = Auth::guard('merchant')->user();

            @endphp

            <!-- Modal Header -->
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="editModalLabel">
                    <i class="bi bi-wallet2 me-2"></i> @lang('Balance Operation')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <form method="POST" action="{{ route('sub_merchant_add_balance') }}" class="needs-validation" novalidate>
                @csrf
                <div class="modal-body p-4">

                    <!-- Available Balance -->
                    <div class="alert alert-info text-center fw-bold fs-6">
                        <i class="bi bi-cash-coin me-2"></i>
                        @lang('Available Balance'):
                        <span class="text-success">{{ $merchant->available_balance }}</span>
                    </div>

                    <input type="hidden" id="id" name="id" required>

                    <!-- Sub Merchant ID -->
                    <div class="mb-3">
                        <label for="subMerchanId" class="form-label fw-semibold">@lang('Sub Merchant ID')</label>
                        <input type="text" id="subMerchanId" class="form-control" name="subMerchanId" readonly>
                    </div>

                    <!-- Current Balance -->
                    <div class="mb-3">
                        <label for="currentBalance" class="form-label fw-semibold">@lang('Current Balance')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-coin"></i></span>
                            <input type="number" id="currentBalance" class="form-control" name="balance" readonly>
                        </div>
                    </div>

                    <!-- Balance Type -->
                    <div class="mb-3">
                        <label for="balanceType" class="form-label fw-semibold">@lang('Transaction Type')</label>
                        <select id="balanceType" name="balance_type" class="form-select" required>
                            <option disabled selected value="">-- @lang('Select Type') --</option>
                            <option value="credit">➕ @lang('Add')</option>
                            <option value="debit">➖ @lang('Return')</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label fw-semibold">@lang('Amount')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-currency-dollar"></i></span>
                            <input type="number" id="amount" class="form-control" name="amount" required>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="mb-3">
                        <label for="details" class="form-label fw-semibold">@lang('Note')</label>
                        <textarea id="details" class="form-control" name="details" rows="3" placeholder="@lang('Enter note...')"></textarea>
                    </div>

                    <!-- Pin -->
                    <div class="mb-3">
                        <label for="pincode" class="form-label fw-semibold">@lang('PIN Code')</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" id="pincode" class="form-control" name="pincode" required>
                        </div>
                    </div>

                </div>

                <!-- Modal Footer -->
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> @lang('Close')
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> @lang('Submit')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@push('js')
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
   <script>
    $(document).ready(function() {
        $('#myTable').DataTable({
            "pageLength": 50,
            "lengthMenu": [ [10, 25, 50, 100], [10, 25, 50, 100] ]
        });
    });
</script>

<script>
    $(function($) {
        "use strict";

        $('.editBtn').on('click', function() {
            var modal = $('#editModal');

            // modal.find('form').attr('action', $(this).data('route'));  // Setting action route
            modal.find('input[name=subMerchanId]').val($(this).data('id'))
            modal.find('input[name=subMerchanId]').val($(this).data('code'));  // Sub Merchant ID
            modal.find('input[name=name]').val($(this).data('name'));  // Name
            modal.find('input[name=balance]').val($(this).data('balance'));  // Balance
            modal.find('select[name=balance_type]').val($(this).data('balance_type'));  // Balance Type (if you have set it)
            modal.find('input[name=pincode]').val($(this).data('pincode'));  // Pin
            modal.find('textarea[name=details]').val($(this).data('details'));  // Note
        });
    });
</script>
@endpush
