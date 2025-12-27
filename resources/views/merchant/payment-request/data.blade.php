<style>
    .note-container {
        width: 80%;
        /* Set to the width of the table cell */
        overflow: hidden;
        /* Hide overflow content */
        position: relative;
        /* Keep the position relative if needed for other styles */
    }

    .note-content {
        display: inline-block;
        white-space: normal;
        /* Allow text to wrap onto the next line */
        word-wrap: break-word;
        /* Break long words onto the next line */
        word-break: break-all;
        /* Ensure long unbroken strings wrap to the next line */
    }
</style>


<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    {{--
    @php
    $merchant = Auth::guard('merchant')->user();
    dd($merchant);
    @endphp --}}



    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            <th scope="col">Method</th>
            <th scope="col">MFS Method/Trx</th>
            <th scope="col">Amount</th>
            <th scope="col">Fee / Comm</th>
            <th scope="col">New Amount</th>
            <th scope="col">Balance Change</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>

        @php $total = 0 @endphp


        @foreach ($data as $key => $row)
            @php

                $subMerchantName = '';

                if ($row->sub_merchant != null && auth('merchant')->user()->merchant_type == 'general') {
                    $subMerchantName = \App\Models\Merchant::find($row->sub_merchant)->fullname;
                }

                $total += $key;
                $make_method = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();

                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == 'merchant') {
                    $make_method = 'Payment';
                }

                $BMData = DB::table('balance_managers')->where('trxid', $row->payment_method_trx)->first();

            @endphp
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ ++$key }}</td>

                <td class="text-center">
                    {{ $subMerchantName }} <br>
                    {{ $row->payment_method }}, {{ $row->sim_id }} <br>
                    {{ $row->payment_type }} <br>
                    <small class="text-muted">From: {{ $BMData ? $BMData->mobile : '-' }}</small>
                </td>

                <td class="text-center">
                    <span class="text-success">{{ $row->payment_method_trx }}</span> <br>
                    <span class="text-info">{{ $row->reference }}</span>
                </td>

                <td class="text-center">{{ $row->amount }}</td>

                @if (auth('merchant')->user()->merchant_type == 'general')
                    <td class="text-center">
                        <span class="badge bg-danger">{{ number_format($row->merchant_fee, 2) }}</span> /
                        <span class="badge bg-success">{{ number_format($row->merchant_commission, 2) }}</span>
                    </td>
                    <td class="text-center">{{ $row->merchant_main_amount }} </td>
                @elseif(auth('merchant')->user()->merchant_type == 'sub_merchant')
                    <td class="text-center">
                        <span class="badge bg-danger">{{ number_format($row->sub_merchant_fee, 2) }}</span> /
                        <span class="badge bg-success">{{ number_format($row->sub_merchant_commission, 2) }}</span>
                    </td>
                    <td class="text-center">{{ $row->sub_merchant_main_amount }} </td>
                @endif

                <td class="text-center">
                    <span class="badge bg-secondary">{{ number_format($row->merchant_last_balance, 2) }}</span>
                    <i class="bx bx-right-arrow-alt"></i>
                    <span class="badge bg-success">{{ number_format($row->merchant_new_balance, 2) }}</span>
                </td>

                <td class="text-center">
                    {{ $row->created_at->format('h:i:sa, d-m-Y') }},
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?></span>
                </td>
                @if ($row->status == 0)
                    <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
                @elseif($row->status == 1)
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Success</span>
                    </td>
                @elseif($row->status == 2)
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Approved</span>
                    </td>
                @elseif($row->status == 3)
                    <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                @else
                    <td></td>
                @endif

                <td class="text-center">
                    <button type="button" class="viewPaymentBtn btn btn-sm btn-outline-primary"
                        data-payment-id="{{ $row->id }}"
                        data-request-id="{{ $row->request_id }}"
                        data-trxid="{{ $row->trxid }}"
                        data-merchant-name="{{ $subMerchantName }}"
                        data-payment-method="{{ $row->payment_method }}"
                        data-payment-trx="{{ $row->payment_method_trx }}"
                        data-reference="{{ $row->reference }}"
                        data-amount="{{ number_format($row->amount, 2) }}"
                        data-fee="{{ auth('merchant')->user()->merchant_type == 'general' ? number_format($row->merchant_fee, 2) : number_format($row->sub_merchant_fee, 2) }}"
                        data-commission="{{ auth('merchant')->user()->merchant_type == 'general' ? number_format($row->merchant_commission, 2) : number_format($row->sub_merchant_commission, 2) }}"
                        data-new-amount="{{ auth('merchant')->user()->merchant_type == 'general' ? $row->merchant_main_amount : $row->sub_merchant_main_amount }}"
                        data-from-number="{{ $BMData ? $BMData->mobile : '-' }}"
                        data-note="{{ $row->note ?? $row->reject_msg ?? '-' }}"
                        data-created-at="{{ $row->created_at->format('h:i:sa, d-m-Y') }}"
                        data-status="{{ ['Pending', 'Success', 'Approved', 'Rejected'][$row->status] ?? '-' }}"
                        title="View Details"
                        data-bs-toggle="tooltip">
                        <i class="bx bx-show"></i>
                    </button>
                </td>
            </tr>
        @endforeach


        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            {{-- <tr>
            <th colspan="6">Total</th>
            <th><?php echo number_format($total, 2); ?></th>
            <th>-</th>
        </tr>
--}}
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
