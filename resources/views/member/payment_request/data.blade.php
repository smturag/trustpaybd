<style>
    .note-container {
        width: 100%;
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
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            {{-- <th scope="col">Customer Name</th> --}}
            <th scope="col">Method</th>
            <th scope="col">Type</th>
            <th scope="col">Amount</th>
            <th scope="col">Fee</th>
            <th scope="col">Commission</th>
            <th scope="col">Net Amount</th>
            <th scope="col" class="text-center">TrxId - Reference</th>
            <!--<th scope="col">Reference Id</th>-->
            <th scope="col">From </th>

            <th scope="col">Note</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            @if ($user_type == 'agent')
                <th scope="col" class="text-center">Action</th>
            @endif
        </tr>
    </thead>
    <tbody>

        @php $total = 0 @endphp


        @foreach ($data as $key => $row)
            @php
                $total += $row->amount;
                $make_method = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();

                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == ' customer') {
                    $make_method = 'Payment';
                }

                if ($row->customer_id) {
                    $customer = App\Models\Customer::find($row->customer_id);
                } elseif ($row->merchant_id) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                }

                $BMData = DB::table('balance_managers')->where('trxid', $row->payment_method_trx)->first();

            @endphp
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ ++$key }}</td>
                {{-- <td class="text-center">{{ $row->merchant->fullname }}</td> --}}
                {{-- <td class="text-center">{{ CustomerInfo($row->customer_id)->customer_name }}</td> --}}
                {{-- <td class="text-center">{{ $row->cust_name }}</td> --}}

                <td class="text-center">{{ $row->payment_method }}, {{ $row->sim_id }}</td>
                <td class="text-center">{{ $make_method }}</td>
                <td class="text-center">{{ $row->amount }}</td>



                <td class="text-center">
                    {{ auth('web')->user()->user_type == 'agent' ? $row->user_fee : $row->partner_fee }}</td>
                <td class="text-center">
                    {{ auth('web')->user()->user_type == 'agent' ? $row->user_commission : $row->partner_commission }}
                </td>
                <td class="text-center">
                    {{ auth('web')->user()->user_type == 'agent' ? $row->user_main_amount : $row->partner_main_amount }}
                </td>

                <td>{{ $row->payment_method_trx }} <span class="text-success"> <br> {{ $row->reference }} </span></td>
                <!--<td>{{ $row->reference }}</td>-->
                <td class="text-center"> {{ $BMData ? $BMData->mobile : '' }} <span
                        class="text-success font-weight-bold">
                        <br> {{ $row->cust_name }} <br> {{ $row->cust_phone }} </span></td>
                <td>
                    <div class="note-container">
                        <div class="note-content">
                            {{ $row->reject_msg }}
                        </div>
                    </div>
                </td>

                <td class="text-center">
                    @if ($row->created_at)
                        {{ $row->created_at->format('h:i:sa, d-m-Y') }},
                        <br>
                        <span
                            class="text-success font-weight-bold">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans() }}</span>
                    @endif
                    <br>
                    @if ($row->updated_at)
                        {{ $row->updated_at->format('h:i:sa, d-m-Y') }},
                        <br>
                        <span
                            class="text-success font-weight-bold">{{ \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans() }}</span>
                    @endif
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

                @if ($user_type == 'agent')
                    <td>

                        @if ($row->status == 0)
                            <a href="#" data-payment-id="{{ $row->id }}"
                                class=" declineButton openPopup btn btn-sm btn-outline-danger"><i
                                    class="lni lni-cross-circle" aria-hidden="true"></i></a>


                            <a href="#" data-payment-id="{{ $row->id }}"
                                class=" completePaymentBtn openPopup btn btn-sm btn-success"><i
                                    class="bx bx-check-double" aria-hidden="true"></i></a>
                        @endif

                    </td>
                @endif
            </tr>
        @endforeach


        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            <tr>
                <th colspan="3">Total</th>
                <th class="text-center"><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
{{-- {!! $data->links('common.pagination') !!} --}}
