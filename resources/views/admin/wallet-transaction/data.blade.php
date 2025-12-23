<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            <th scope="col">Name</th>
            <th scope="col">Method</th>
            <th scope="col">Type</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <th scope="col">Withdraw AC</th>
            <th scope="col" class="text-center">TrxId</th>
            {{-- <th scope="col">Reference</th> --}}
            <th scope="col">Date</th>
            <th scope="col">Status</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>


        @php $total = 0 @endphp


        @foreach ($data as $key => $row)
            @php
                $total += $key;
                $make_method = '';
                $make_user = '';
                $make_type = '';
                $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();
                
                if ($get_method->type == 'agent') {
                    $make_method = 'Cashout';
                } elseif ($get_method->type == 'personal') {
                    $make_method = 'Send Money';
                } elseif ($get_method->type == ' customer') {
                    $make_method = 'Payment';
                }
                
                if ($row->customer_id) {
                    // $customer = App\Models\Customer::find($row->customer_id);
                    $customer = CustomerInfo($row->customer_id);
                } elseif ($row->merchant_id) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                }
                
                if ($row->merchant_id) {
                    $make_user = $row->merchant_id;
                    $make_type = 'merchant';
                }
                $make_user = $row->customer_id;
                $make_type = 'customer';
                
            @endphp
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ ++$key }}</td>

                @if ($row->customer_id)
                    <td class=" text-center"> <span class="text-danger">{{ $customer->customer_name }} </span><br> <span
                            class=" font-weight-bold"> Customer</span>

                        <br>
                        @if ($row->receiver_customer_id !== null)
                            @php
                                
                                $get_receiver_customer = CustomerInfo($row->customer_id);
                                
                            @endphp
                            To: <span class="text-success font-weight-bold">

                                {{ $get_receiver_customer->customer_name }}</span>
                        @endif
                    </td>
                @else
                    <td class="text-center">{{ $Merchant->fullname }} <br> <span class="text-success font-weight-bold">
                            Merchant</span>
                    </td>
                @endif
                <td class="text-center">
                    @if ($row->merchant_id == null)
                        {{ $row->payment_method }}, {{ $row->sim_id }}
                    @else
                        {{ $row->currency_name }}
                    @endif
                </td>
                <!--<td class="text-center">{{ fake()->randomElement(['cashin', 'cashout', 'send money']) }}</td>-->
                <td class="text-center">{{ $row->type }}</td>
                {{-- <td class="text-center">{{ $make_method }}</td> --}}
                <td class="text-center">{{ $row->debit }}</td>
                <td class="text-center">{{ $row->credit }}</td>
                <td class="text-center">
                    @if ($row->merchant_id == null)
                        {{ $row->account_number }} <br> {{ $row->account_type }}
                </td>
            @else
                {{ $row->network }} <br> {{ $row->deposit_address }}</td>
        @endif
        <td>{{ $row->trxid }}</td>
        {{-- <td>{{ $row->reference }}</td> --}}

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
        @elseif($row->status == 1 || $row->status == 2)
            <td><span class='badge badge-pill bg-success text-white'>
                    <i
                        class="bx bx-radio-circle-marked bx-burst bx-rotate-90 align-middle font-18 me-1"></i>Success</span>
            </td>
        @elseif($row->status == 3)
            <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
        @else
            <td></td>
        @endif
        {{-- <td>

            @if ($row->type == 'withdraw')
                @if ($row->status == 0)
                 
                    <a href="#" id="approve_transection" data-id="{{ $row->id }}" data-astatus='0'
                        class="openPopup btn btn-sm btn-success"><i class="bx bx-check-double"
                            aria-hidden="true"></i></a>

                    <a href="#" id="decline_transection" data-id="{{ $row->id }}"
                        data-user_type="{{ $make_type }}" data-user="{{ $make_user }}" data-rstatus='1'
                        class="openPopup btn btn-sm btn-outline-danger"><i class="lni lni-cross-circle"
                            aria-hidden="true"></i></a>
                @endif
            @endif


        </td> --}}

        <td>
            @if ($row->type == 'withdraw')
                @if ($row->status == 0)

                    {{-- <a href="#" id="decline_transection_{{ $row->id }}" data-id="{{ $row->id }}"
                        data-user_type="{{ $make_type }}" data-user="{{ $make_user }}" data-rstatus='1'
                        class="openPopup btn btn-sm btn-outline-danger"><i class="lni lni-cross-circle"
                            aria-hidden="true"></i></a> --}}

                            <a href="#" data-id="{{ $row->id }}" data-astatus='0'
                                class=" approve_transection openPopup btn btn-sm btn-success"><i class="bx bx-check-double"
                                    aria-hidden="true"></i></a>


                            <button class="declineButton btn btn-sm btn-outline-danger"
                            data-id="{{ $row->id }}"
                            data-user_type="{{ $make_type }}"
                            data-user="{{ $make_user }}"
                            data-rstatus="1">
                            <i class="lni lni-cross-circle"
                            aria-hidden="true"></i>
                        </button>

                      
                @endif
            @endif
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
