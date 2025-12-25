<table id="req_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
        <tr>
            <th scope="col" class="text-center">
                <input type="checkbox" id="select_all" />
            </th>
            <th scope="col" class="text-center">#</th>
            {{-- <th scope="col" class="text-center">Sender</th> --}}
            <th scope="col">ReqTime</th>
            <th scope="col">Name</th>
            <th scope="col">MFS</th>
            <th scope="col">Number</th>
            <th scope="col">Oldbal</th>
            <th scope="col">Amount</th>
            <th scope="col">Fee</th>
            <th scope="col">Commission</th>
            <th scope="col">Main Balance</th>
            <th scope="col">Lastbal</th>
            <th scope="col">Trxid</th>
           {{--   <th scope="col">Type</th> --}}
            <th scope="col">Status</th>
            <th scope="col">Route</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0 @endphp
        @foreach ($data as $row)
        @php
            $designation = "";
             $total += $row->amount ;
             if ($row->sub_merchant != null) {
                    $Merchant = App\Models\Merchant::find($row->sub_merchant);
                    $designation = "Sub Merchant";
                }elseif ($row->merchant_id != null) {
                    $Merchant = App\Models\Merchant::find($row->merchant_id);
                    $designation = "Merchant";
            }
            $isSubMerchant = !empty($row->sub_merchant);
            $fee = $isSubMerchant ? $row->sub_merchant_fee : $row->merchant_fee;
            $commission = $isSubMerchant ? $row->sub_merchant_commission : $row->merchant_commission;
            $mainAmount = $isSubMerchant ? $row->sub_merchant_main_amount : $row->merchant_main_amount;
        @endphp
            <tr id="{{ $row->id }}">
                <td>
                    @if ($row->status == 5 || $row->status == 6)
                    <input type="checkbox" class="row_checkbox" />
                    @endif
                </td>
                <td>{{ $row->id }}</td>
                <td>{{ bdtime($row->created_at) }}
                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans(); ?>
                    </p>

                    {{ bdtime($row->updated_at) }}
                    <p class="text-success font-weight-bold">
                        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->updated_at))->diffForHumans(); ?>
                    </p>
                </td>
                <td>{{ $Merchant->fullname }} <br> {{$designation}} <br> {{$row->trxid}} </td>

                @if ($row->customer_id)
                    @php

                        $get_receiver_customer = App\Models\Customer::find($row->customer_id);

                    @endphp
                    <td>{{ $get_receiver_customer->customer_name }}</td>
                @endif
                {{-- <td>{{ DB::table('merchants')->where('id', $row->merchant_id)->value('fullname') }}</td> --}}


                @php
                    $make_type = '';
                    if ($row->type == 'agent') {
                        $make_type = 'Cash Out';
                    } elseif ($row->type == 'personal') {
                        $make_type = 'Cash In';
                    } elseif ($row->type == 'merchant') {
                        $make_type = 'Payment';
                    }
                @endphp

                <td style="text-transform: capitalize;">{{ $row->mfs }} <br> {{$make_type}}</td>
                <td>{{ $row->number }}</td>
                <td>{{ money($row->old_balance) }}</td>
                <td>{{ money($row->amount) }}</td>
                <td>{{ $fee !== null ? money($fee) : '-' }}</td>
                <td>{{ $commission !== null ? money($commission) : '-' }}</td>
                <td>{{ $mainAmount !== null ? money($mainAmount) : '-' }}</td>
                <td>{{ money($row->new_balance) }}</td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    {{ $row->get_trxid }}
                </td>

              

                 {{--  <td>{{ $make_type }}</td> --}}


                <td>
                    @if ($row->status == 2)
                        <span class='badge badge-pill bg-success'>Success</span>
                    @elseif($row->status == 1)
                        <span class='badge badge-pill bg-info text-white'>Waiting</span>
                    @elseif($row->status == 4)
                        <span class='badge badge-pill bg-danger text-white'>Rejected</span>
                    @elseif($row->status == 3)
                        <span class='badge badge-pill bg-success text-white'>Approved</span>
                    @elseif($row->status == 5)
                        <span class='badge badge-pill  text-white' style="background-color: blue">Processing</span>
                    @elseif($row->status == 6)
                        <span class='badge badge-pill bg-green text-white'
                            style="background-color: chocolate">Failed</span>
                    @else
                        <span class='badge badge-pill bg-warning text-white'> Pending </span>
                    @endif
    <br>
                    @if (!empty($row->action_by))
                        <span class='badge badge-pill bg-green text-white' style="background-color: green">
                            {{ $row->action_by }}
                        </span>
                    @endif



                </td>

                <td>
                    @if ($row->agent_id)
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue">{{ $row->user->fullname }}</span>
                        <br><span class='badge badge-pill'
                            style="color: #e6be96; background-color: blue">{{ getSimInfo($row->modem_id) }}</span>
                    @endif

                </td>

                <td>
                    <a href="#" class="openDetails btn btn-sm btn-outline-primary"
                        data-href="{{ route('service_req_details', ['id' => $row->id]) }}"
                        data-bs-toggle="modal" data-bs-target="#myModal">
                        <i class="bx bx-show" aria-hidden="true"></i>
                    </a>

                    @php
                        $make_user = '';
                        $make_type = '';
                        if ($row->merchant_id) {
                            $make_user = $row->merchant_id;
                            $make_type = 'merchant';
                        } else {
                            $make_user = $row->customer_id;
                            $make_type = 'customer';
                        }

                    @endphp

                    @if ($row->status == 2 || $row->status == 3 || $row->status == 4)
                    @else
                        <a href="#" class="openPopup btn btn-sm btn-success" id="{{ $row->id }}"
                            data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double"
                                aria-hidden="true"></i></a>
                        {{--                    <a href="#" class="openPopup btn btn-sm btn-success" data-href="{ route('approved_balance_manager', $row->id) }}" id="{{ $row->id }}" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a> --}}
                        {{-- <a href="#" class="rejectBalance btn btn-sm btn-outline-danger"
                            id="{{ $row->id }}"><i class="lni lni-cross-circle" aria-hidden="true"></i></a> --}}

                        {{-- <a class="btn btn-sm btn-outline-danger" data-user_type="{{ $make_type }}"
                            data-user="{{ $make_user }}" data-id="{{ $row->id }}"><i
                                class=" reject_trans lni lni-cross-circle" aria-hidden="true"></i></a> --}}

                        <a class="btn btn-sm btn-outline-danger" data-user_type="{{ $make_type }}"
                            data-user="{{ $make_user }}" data-id="{{ $row->id }}">
                            <i class="reject_trans lni lni-cross-circle" aria-hidden="true"></i>
                        </a>
                    @endif

                    @if ($row->status == 5 || $row->status == 6)
                        <a class="btn btn-sm btn-outline-info" href="{{ route('resend_req', ['id' => $row->id]) }}"
                            onclick="return confirm('Are you sure to resend this request?')" data-toggle="tooltip"
                            data-placement="right" title="Resend"><i class="lni lni-reload"></i></a>
                    @endif




                </td>
            </tr>
        @endforeach

        @if ($data->isEmpty())
            <tr>
                <td colspan="16" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            <tr>
            <th colspan="7">Total</th>
            <th><?php echo number_format($total, 2); ?></th>
            <th colspan="8"></th>
        </tr>
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
