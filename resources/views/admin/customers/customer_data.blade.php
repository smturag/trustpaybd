<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Balance</th>
        <th scope="col" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <?php $total += $row->balance; ?>

        <tr id="{{ $row->id }}">
            <td class="text-center">{{ $row->id }}</td>
            <td>{{ $row->customer_name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->mobile }}</td>
            <td>
                <a href="javascript:void(0)" data-id="{{ $row->id }}" data-name="{{ $row->customer_name }}" data-balance="{{ $row->balance }}" data-route="{{ route('customer_add_balance', $row->id) }}" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-primary text-white editBtn btn-sm me-2">
                    <i class="bx bx-money"></i>{{ $row->balance }}
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('customer_edit', ['id' => $row->id]) }}">Edit</a>
                <button class="btn btn-sm btn-outline-danger delete" id="{{ $row->id }}" onClick="delete_record(this.id, '{{ $row->fullname }}')">Delete</button>
            </td>
        </tr>
    @endforeach




    @if($data->isEmpty())
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    @else

        <tr>
            <th colspan="4">Total</th>
            <th><?php echo number_format($total, 2) ?></th>
            <th>&nbsp;</th>
        </tr>

    @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}

<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Balance</th>
        <th scope="col" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <?php $total += $row->balance; ?>

        <tr id="{{ $row->id }}">
            <td class="text-center">{{ $row->id }}</td>
            <td>{{ $row->customer_name }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->mobile }}</td>
            <td>
                <a href="javascript:void(0)" data-id="{{ $row->id }}" data-name="{{ $row->customer_name }}" data-balance="{{ $row->balance }}" data-route="{{ route('customer_add_balance', $row->id) }}" data-bs-toggle="modal" data-bs-target="#editModal" class="btn btn-sm btn-primary text-white editBtn btn-sm me-2">
                    <i class="bx bx-money"></i>{{ $row->balance }}
                </a>
            </td>
            <td class="text-center">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('customer_edit', ['id' => $row->id]) }}">Edit</a>
                <button class="btn btn-sm btn-outline-danger delete" id="{{ $row->id }}" onClick="delete_record(this.id, '{{ $row->fullname }}')">Delete</button>
            </td>
        </tr>
    @endforeach




    @if($data->isEmpty())
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    @else

        <tr>
            <th colspan="4">Total</th>
            <th><?php echo number_format($total, 2) ?></th>
            <th>&nbsp;</th>
        </tr>

    @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}

 <script>
        $(function ($) {
            "use strict";

            $('.editBtn').on('click', function () {
                var modal = $('#editModal');
                modal.find('form').attr('action', $(this).data('route'));
                modal.find('input[name=name]').val($(this).data('name'));
                modal.find('input[name=balance]').val($(this).data('balance'));
                modal.find('input[name=balance_type]').val($(this).data('balance_type'));
                modal.find('input[name=pincode]').val($(this).data('pincode'));
                modal.find('textarea[name=details]').val($(this).data('details'));
            });
        });
    </script>

