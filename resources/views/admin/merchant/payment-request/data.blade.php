<style>
    .custom-note-container {
    max-width: 150px; /* Set the desired maximum width */
    word-break: break-word; /* Ensure text breaks if it's too long */
    white-space: normal; /* Allow wrapping */
}

.custom-note-content {
    overflow: hidden;
    text-overflow: ellipsis;
}
</style>
<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            <th scope="col">Name</th>

            <th scope="col">Method</th>
            <th scope="col">MFS Method/Trx</th>
            <!--<th scope="col">Type</th>-->
            <th scope="col">Amount</th>
            {{-- <th scope="col" class="text-center">TrxId</th> --}}
            <!--<th scope="col">Reference</th>-->
            <th scope="col">From </th>

            <th scope="col">Note</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>


        @php $total = 0 @endphp

        @foreach ($data as $key => $row)
            @php
                $designation = "";
                $total += $row->amount;
                $make_method = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();
                $make_method = 'Cashout';
                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == ' customer') {
                    $make_method = 'Payment';
                }

                if ($row->customer_id) {
                    $customer = CustomerInfo($row->customer_id);
                    $designation = 'Customer';
                } elseif ($row->sub_merchant != null) {
                    $Merchant = App\Models\Merchant::find($row->sub_merchant);
                    $designation = "Sub Merchant";
                }elseif ($row->merchant_id != null) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                    $designation = "Merchant";
                }

                $BMData = DB::table('balance_managers')
                    ->where('trxid', $row->payment_method_trx)
                    ->first();

            @endphp
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ ++$key }}</td>


                @if ($row->customer_id)
                    <td class="text-center">{{ $customer->customer_name }} <br> <span
                            class="text-success font-weight-bold"></span> </td>
                @else
                    <td class="text-center">{{ $Merchant->fullname }} <br> <span class="text-success font-weight-bold">
                            {{$designation}} </span> <br> <span class="text-info">{{ $row->reference}}</td>
                @endif
                <td class="text-center">{{ $row->payment_method }}, {{ $row->sim_id }} <br> {{ $row->payment_type == "P2C" ? "Payment" : $make_method }} </td>
                <!--<td class="text-center">{{ fake()->randomElement(['cashin', 'cashout', 'send money']) }}</td>-->
                <td class="text-center"> {{ $row->payment_method_trx }}


                </td>
                <!--<td class="text-center">{{ $make_method }}  </td>-->
                <td class="text-center">{{ $row->amount }}</td>
                <!--<td>{{ $row->reference }}</td>-->
                <!--<td class="text-center note-container"><div class="note-content">  {{ $BMData ? $BMData->mobile : '' }} <span-->
                <!--        class="text-success font-weight-bold">-->
                <!--        <br> {{ $row->cust_name }} <br> {{ $row->cust_phone }} </span></div>  </td>-->
                <!--<td>-->

                <td class="text-center custom-note-container">
                    <div class="custom-note-content">
                        {{ $BMData ? $BMData->mobile : $row->from_number }}
                        <span class="text-success font-weight-bold">
                            <br> {{ $row->cust_name }} <br> {{ $row->cust_phone }}
                        </span>
                    </div>
                </td>
                <td class="text-center custom-note-container">
                        <div class="custom-note-content">
                            {{ $row->reject_msg }} <br>
                            {{ $row->note }}
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
                                class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Approved</span>  <br> {{ $row->accepted_by }}
                    </td>
                @elseif($row->status == 3)
                    <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
                    @elseif($row->status == 4)
                    <td><span class='badge badge-pill bg-danger text-white'>Spam</span></td>
                @else
                    <td></td>
                @endif
                <td>

                    @if ($row->status == 0 || $row->status == 4 )
                        <a href="#" data-payment-id="{{ $row->id }}"
                            class=" rejectPaymentBtn openPopup btn btn-sm btn-outline-danger"><i
                                class="lni lni-cross-circle" aria-hidden="true"></i></a>
                                                        @if ($row->status != 4)

                                <!--
                                <button type="button" data-payment-id="{{ $row->id }}"
                                    class="completePaymentBtn openPopup btn btn-sm btn-success">
                                    <i class="bx bx-check-double"></i>
                                </button>
                                -->
                                <button type="button" data-payment-id="{{ $row->id }}"
                                    class="spamPaymentBtn btn btn-sm btn-success">
                                    <i class="bx bx-check-double"></i> Approve
                                </button>

                            @else
                                <!-- Spam Request button -->
                                <button type="button" data-payment-id="{{ $row->id }}"
                                    class="spamPaymentBtn btn btn-sm btn-success">
                                    <i class="bx bx-check-double"></i> Approve
                                </button>
                            @endif
                    @elseif ($row->status == 3)
                        <a href="{{ route('pending-payment-request', ['id' => $row->id]) }}"
   class="pendingPaymentBtn openPopup btn btn-sm btn-outline-danger"
   onclick="return confirm('Are you sure you want to mark this payment as pending?');"
   title="Mark as Pending">
    <i class="bx bx-hourglass" aria-hidden="true"></i>
</a>



                    @endif


                </td>
            </tr>
        @endforeach


        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            <tr>
                <th colspan="5">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
