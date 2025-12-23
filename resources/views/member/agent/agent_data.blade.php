<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Member Code</th>
        <th scope="col">Full Name</th>
        <th scope="col">Email</th>
        <th scope="col">Phone</th>
        <th scope="col">Type</th>
        <th scope="col">Balance</th>
        <th scope="col" class="text-center">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
	<?php
		$total += $row->balance;
	?>
        <tr id="{{ $row->id }}">
            <td class="text-center">{{ $row->id }}</td>
            <td class="text-center">{{ $row->member_code }}</td>
            <td>{{ $row->fullname }}</td>
            <td>{{ $row->email }}</td>
            <td>{{ $row->mobile }}</td>
            <td>{{ $row->user_type }}</td>
			 <td>{{$row->balance }}</td>
            <td class="text-center">
                <a class="btn btn-sm btn-outline-primary" href="{{ route('member_edit', ['id' => $row->id]) }}">Edit</a>
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
		<th colspan="6">Total</th>
		<th><?php echo number_format($total,2)?></th>
		<th colspan="6">&nbsp;</th>
		</tr>

    @endif
    </tbody>
</table>

{!! $data->links('common.pagination-custom') !!}
