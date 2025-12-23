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
            {{-- <th scope="col">Merchant</th> --}}
            {{-- <th scope="col">Customer Name</th> --}}
            <th scope="col">From</th>
            <th scope="col">Method</th>
            {{-- <th scope="col">Type</th> --}}
            <th scope="col">Amount</th>
            <th scope="col">Fee</th>
            <th scope="col">Commission</th>
            <th scope="col">New Amount</th>
            <th scope="col" class="text-center">TrxId & Reference</th>
            <th scope="col" class="text-center">Note</th>
            {{-- <th scope="col">Customer Trxid</th> --}}
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            {{-- <th scope="col" class="text-center">Action</th> --}}
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
                {{--   <td class="text-center">
                    {{ CustomerInfo($row->customer_id)->customer_name ? CustomerInfo($row->customer_id)->customer_name : $row->cust_name }}
                    <br> {{ $row->cust_phone }}
                </td> --}}

                <td class="text-center"> {{ $BMData ? $BMData->mobile : '' }} <span
                        class="text-success font-weight-bold">
                    </span></td>

                {{-- <td class="text-center">{{ $row->merchant->fullname }}</td> --}}
                <td class="text-center"> {{ $subMerchantName }} <br> {{ $row->payment_method }}, {{ $row->sim_id }}
                </td>
                <!--<td class="text-center">{{ fake()->randomElement(['cashin', 'cashout', 'send money']) }}</td>-->
                {{-- <td class="text-center">{{ $make_method }}</td> --}}
                <td class="text-center">{{ $row->amount }}</td>

                @if (auth('merchant')->user()->merchant_type == 'general')
                    <td class="text-center">{{ $row->merchant_fee }} </td>
                    <td class="text-center">{{ $row->merchant_commission }} </td>
                    <td class="text-center">{{ $row->merchant_main_amount }} </td>
                @elseif(auth('merchant')->user()->merchant_type == 'sub_merchant')
                    <td class="text-center">{{ $row->sub_merchant_fee }} </td>
                    <td class="text-center">{{ $row->sub_merchant_fee }} </td>
                    <td class="text-center">{{ $row->sub_merchant_main_amount }} </td>
                @endif

                <td>
                    <span class="text-success">{{ $row->payment_method_trx }} </span>
                    <br>
                    <span class="text-info">{{ $row->reference }} </span>
                </td>
                <td>
                    <div class="note-container">
                        <div class="note-content">
                            {{ $row->reject_msg }}
                        </div>
                    </div>
                </td>

                <td class="text-center">
                    {{ $row->created_at->format('h:i:sa, d-m-Y') }},
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?></span>
                    <br>
                    {{ $row->updated_at->format('h:i:sa, d-m-Y') }},
                    <br>
                    <span class="text-success font-weight-bold"><?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?></span>
                </td>
                @if ($row->status == 0)
                    <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
                @elseif($row->status == 1)
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i
                                class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Success</span>
                    </td>
                @elseif($row->status == 2)
                    <td><span class='badge badge-pill bg-success text-white'>
                            <i
                                class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Approved</span>
                    </td>
                @elseif($row->status == 3)
                    <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                @else
                    <td></td>
                @endif
                {{-- <td>
                @if ($row->status == 0 || $row->status == 2)
                    <a href="#" class="openPopup btn btn-sm btn-success" id="{{ $row->id }}" data-bs-toggle="modal"
                       data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a>

                    <a href="#" class="rejectBalance btn btn-sm btn-outline-danger" id="{{ $row->id }}"><i
                            class="lni lni-cross-circle" aria-hidden="true"></i></a>

                @else
                    <a href="#" class="openPopup btn btn-sm btn-outline-info" data-bs-toggle="modal"
                       data-href="{{ route('view_balance_manager', $row->id) }}" data-bs-target="#myModal"><i
                            class="lni lni-eye" aria-hidden="true"></i></a>
                @endif

            </td> --}}
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
