<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th>SL</th>
            <th>Date</th>
            <th>Name</th>
            <th>User Type</th>
            <th>Payment Type</th>
            <th>Created By</th>
            <th>Note</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @php
            $total = 0;
        @endphp
        @foreach ($data as $key => $item)
            @php

                $total += $item->amount;
                $merchantType = "";
                if ($item->user_type == 'merchant' || $item->user_type == 'sub_merchant') {
                    $merchantType = "Merchant";
                    $findUser = App\Models\Merchant::find($item->user_id);
                } else {
                    $merchantType = "User";
                    $findUser = App\Models\User::find($item->user_id);
                }

                $creatorInfo = " ";
                

                if($item->creator_type == 'admin'){
                    $creatorInfo = App\Models\Admin::find($item->creator_id);
                }else if($item->creator_type == 'merchant'){
                    $creatorInfo = App\Models\Merchant::find($item->creator_id);
                }

            @endphp

           

            <tr>
                <td>{{ $key += 1 }}</td>
                <td>{{$item->created_at->format('d-m-Y h:i A') }}</td>
                <td>{{ $findUser->fullname }}</td>
                <td>{{ $merchantType == 'Merchant'?  $findUser->merchant_type : $item->user_type }}</td>
                <td>{{ $item->trx_type == 'credit' ? 'Add' : 'Return' }}</td>
                
                <td>
                    @if($item->creator_type == 'admin')
                        {{$creatorInfo->admin_name}} <br> Admin
                    @elseif($item->creator_type == 'merchant')
                        
                        {{$creatorInfo->fullname}} <br> Merchant
                    @endif
                </td>
                <td>{{ $item->details }}</td>
                <td>{{ $item->amount }}</td>
            </tr>
        @endforeach

        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @else
            <tr>
                <th colspan="7">Total</th>
                <th>{{ $total}}</th>
            </tr>
        @endif
    </tbody>

    {!! $data->links('common.pagination-custom') !!}
</table>
