<table class="table table-bordered table-hover mb-0 table-striped align-middle" id="payment_request_table">
    <thead class="table-dark">
        <tr class="text-center">
            <th style="width: 50px;">SL</th>
            <th style="width: 100px;">Date</th>
            <th style="width: 150px;">Name</th>
            <th style="width: 100px;">User Type</th>
            <th style="width: 120px;">Payment Type</th>
            <th style="width: 180px;">Created By</th>
            <th style="width: 200px;">Note</th>
            <th style="width: 120px;">Amount</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp

        @foreach ($data as $item)
            @php
                $total += $item->amount;

                if ($item->user_type == 'merchant' || $item->user_type == 'sub_merchant') {
                    $findUser = App\Models\Merchant::find($item->user_id);
                } else {
                    $findUser = App\Models\User::find($item->user_id);
                }
            @endphp

            <tr class="text-center">
                <td>{{ $loop->iteration }}</td>
                <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                <td>{{ $findUser->fullname ?? 'N/A' }}</td>
                <td>{{ ucfirst($item->user_type) }}</td>
                <td>{{ $item->trx_type == 'credit' ? 'Add' : 'Return' }}</td>
                <td>
                    @if ($item->creator_type == 'admin')
                        {{ optional(App\Models\Admin::find($item->creator_id))->admin_name }}
                    @else
                        {{ optional(App\Models\Merchant::find($item->creator_id))->fullname }}
                    @endif
                    <br>
                    <span class="badge bg-info text-dark">{{ ucfirst($item->creator_type) }}</span>
                </td>
                <td class="text-start text-truncate" style="max-width: 180px;" title="{{ $item->details }}">
                    {{ $item->details }}
                </td>
                <td class="fw-bold">{{ number_format($item->amount, 2) }}</td>
            </tr>
        @endforeach

        @if ($data->isEmpty())
            <tr>
                <td colspan="8" class="text-center py-5 fs-5">No Record Found.</td>
            </tr>
        @else
            <tr class="table-secondary fw-bold">
                <th colspan="7" class="text-end">Total</th>
                <th>{{ number_format($total, 2) }}</th>
            </tr>
        @endif
    </tbody>

    {!! $data->links('common.pagination-custom') !!}
</table>
