<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            <th scope="col">Method</th>
            <th scope="col">Amount</th>
            <th scope="col">Fee / Comm</th>
            <th scope="col">Main Balance</th>
            <th scope="col">Balance Change</th>
            <th scope="col">TrxId</th>
            <th scope="col">Date</th>
            <th scope="col">Status</th>
        </tr>
    </thead>
    <tbody>

        @php $total = 0 @endphp
        @foreach ($data as $key => $row)
            {{-- @php
            $total += $key;
            $make_method = '';
            $get_method = \App\Models\payment_method::where('sim_id',$row->sim_id)->first();

            if($get_method->type == 'agent'){
                $make_method = 'Cashout';
            }else if($get_method->type == 'personal'){
             $make_method = 'Send Money';
            }else if($get_method->type == 'merchant'){
             $make_method = 'Payment';
            }


        @endphp --}}

            @php
                $total += $row->amount;

                $subMerchantName = "";

                if($row->sub_merchant != null && auth('merchant')->user()->merchant_type == 'general'){
                    $subMerchantName = \App\Models\Merchant::find($row->sub_merchant)->name;
                }
            @endphp


            <tr id="{{ $row->id }}">
                <td class="text-center">{{ ++$key }}</td>
                <td class="text-center">{{ $row->mfs }} <br> {{ $row->sim_number }} <br> <small class="text-muted">{{ $row->number }}</small> </td>

                <td class="text-center">
                    {{ $row->amount }}<br>
                    <small class="text-primary copy-withdraw-id d-inline-block mt-1" data-id="{{ $row->trxid ?? '' }}" style="cursor: pointer;">
                        {{ $row->trxid ? 'Withdraw ID: ' . $row->trxid : 'Withdraw ID: N/A' }}
                    </small>
                </td>
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
                    <span class="badge bg-secondary">{{ $row->old_balance }}</span>
                    <i class="bx bx-right-arrow-alt"></i>
                    <span class="badge bg-success">{{ $row->new_balance }}</span>
                </td>
                <td style="width: 150px; word-wrap: break-word; white-space: normal;">
                    {{ $row->get_trxid }} <br>
                    <small class="text-muted">SIM: {{ $row->sim_number }}</small>
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
                        {{-- <span class='badge badge-pill bg-green text-white'
                            style="background-color: chocolate">Failed</span> --}}

                            <span class='badge badge-pill bg-warning text-white'> Pending </span>
                    @else
                        <span class='badge badge-pill bg-warning text-white'> Pending </span>
                    @endif



                </td>
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
            <tr>
                <th colspan="4">Total</th>
                <th><?php echo number_format($total, 2); ?></th>
                <th>-</th>
            </tr>
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
