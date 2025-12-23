<table class="table table-bordered table-hover mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
		<th scope="col">Date</th>
        <th scope="col">Agent</th>
        <th scope="col">Deviceid</th>
        <th scope="col">Operator</th>
        <th scope="col">Sim</th>

        <th scope="col">Action</th>

    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td class="text-center">{{ $row->id }}</td>
			<td>
                {{ date('d F Y', strtotime($row->created_at)) }}<br>
                {{ date('h:i:s A', strtotime($row->created_at)) }}
            </td>
            <td >{{ $row->member_code }}</td>
            <td>{{ $row->deviceid }}</td>
            <td>{{ $row->operator }}</td>
            <td>{{ $row->sim_number }}</td>

			 <td class="text-center">

                <button class="btn btn-sm btn-outline-danger delete" id="{{ $row->id }}" onClick="delete_record(this.id, '{{ $row->deviceid }}')">Delete</button>
            </td>
        </tr>
    @endforeach

    @if($data->isEmpty())
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    @endif
    </tbody>
</table>


{!! $data->links('common.pagination-custom') !!}
