<table class="table table-bordered table-hover table-striped">
    <thead>
        <tr>
            <th class="text-center">#</th>
            <th class="text-center">Merchant ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Merchant Type</th>
            <th>Balance</th>
            <th class="text-center">Action</th>
        </tr>
    </thead>
    <tbody>
        @php $total = 0; @endphp
        @forelse ($data as $row)
            @php
                $balance = $row->merchant_type == 'sub_merchant'
                    ? subMerchantBalance($row->id)
                    : getMerchantBalance($row->id);
                $total += $balance['balance'];


            @endphp
            <tr id="{{ $row->id }}">
                <td class="text-center">{{ $row->id }}</td>
                <td class="text-center">{{ $row->username }}</td>
                <td>{{ $row->fullname }}</td>
                <td>{{ $row->email }}</td>
                <td>{{ $row->mobile }}</td>
                <td>{{ $row->merchant_type }}</td>
                <td>
                    <a href="javascript:void(0)"
                       class="btn btn-sm btn-primary text-white editBtn"
                       data-id="{{ $row->id }}"
                       data-name="{{ $row->username }}"
                       data-balance="{{ $row->merchant_type == 'general'?  $row->available_balance : $row->balance }}"
                       data-route="{{ route('merchant_add_balance', $row->id) }}">
                        <i class="bx bx-money"></i> {{ $row->merchant_type == 'general'?  $row->available_balance : $row->balance }}
                    </a>

                      {{-- @foreach($balance as $key => $value)
        <div><small><strong>{{ ucfirst($key) }}:</strong> {{ number_format($value, 2) }}</small></div>
    @endforeach --}}
                </td>
                <td class="text-center">
                    <a href="{{ route('merchant_edit', $row->id) }}" class="btn btn-outline-primary btn-sm">Edit</a>
                    <button class="btn btn-outline-danger btn-sm delete" onclick="delete_record('{{ $row->id }}', '{{ $row->fullname }}')">Delete</button>
                     <a href="{{ route('merchant_charge', $row) }}" class="btn btn-outline-primary btn-sm">Rate</a>
                     <a href="{{ route('admin.loginAsMerchant', $row->id) }}" class="btn btn-outline-success btn-sm" title="Login as this merchant">
                        <i class="bx bx-log-in"></i> Login as
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center p-5">No Record Found.</td>
            </tr>
        @endforelse

        @if (!$data->isEmpty())
            <tr>
                <th colspan="6">Total</th>
                <th>{{ number_format($total, 2) }}</th>
                <th></th>
            </tr>
        @endif
    </tbody>
</table>

<div class="mt-3">
    {!! $data->links('common.pagination-custom') !!}
</div>
