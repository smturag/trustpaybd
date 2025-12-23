<table class="table table-bordered table-hover mb-0 table-striped" id="payment_request_table">
    <thead>
        <tr class="text-center">
            <th scope="col" class="text-center">#</th>
            <th scope="col">Type</th>
            <th scope="col">Method</th>
            <th scope="col">Debit</th>
            <th scope="col">Credit</th>
            <th scope="col" class="text-center">TrxId</th>
            <th scope="col">Status</th>
            <th scope="col">Note</th>
            <th scope="col">Date</th>
        </tr>
    </thead>
    <tbody>

        {{-- @dd($data) --}}



        @foreach ($data as $row)
            <?php
            $make_type = '';
            $make_method = '';
            $get_method = \App\Models\payment_method::where('sim_id', $row->sim_id)->first();
            $merchant = App\Models\Merchant::find($row->merchant_id);
            
            ?>



            <tr id="{{ $row->id }}">
                <td class="text-center">{{ $loop->iteration }}</td>
                <td class="text-center">
                    @if ($row->type == 'payment_received')
                        <span class="text-success"> Payment Received </span><br> <span>{{ $merchant->fullname }}</span>
                    @elseif($row->type == 'payment')
                        <span class="text-danger"> Payment </span><br> <span>{{ $merchant->fullname }}</span>
                    @else
                        {{ $row->type }}
                    @endif
                </td>
                <td class="text-center">{{ $row->payment_method }}</td>
                <td class="text-right">{{ $row->debit }}</td>
                <td class="text-right">{{ $row->credit }}</td>
                <td class="text-center">{{ $row->trxid }}</td>

                @if ($row->status == 0)
                <td class="text-center text-info">Pending</td>
                @elseif ($row->status == 1)
                <td class="text-center text-success">Success</td>
                @elseif ($row->status == 2)
                <td class="text-center text-danger">Rejected</td>
                @endif
                <td>{{ $row->note }}</td>
                <td class="text-center">{{ date('d/m/Y', strtotime($row->created_at)) }}</td>
            </tr>
        @endforeach


        @if ($data->isEmpty())
            <tr>
                <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
            </tr>
        @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
