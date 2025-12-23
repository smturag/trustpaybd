@php($url_number = Request::segment(3))

<table id="transaction_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Sender</th>
        <th scope="col">Type</th>
        <th scope="col">C Number</th>
        <th scope="col">Oldbal</th>
        <th scope="col">Amount</th>
        <th scope="col">Comm.</th>
        <th scope="col">Lastbal</th>
        <th scope="col">Trxid</th>
        <th scope="col">A Number</th>
        <th scope="col">Status</th>
        <th scope="col">Date</th>
        <th scope="col">Action</th>
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr id="{{ $row->id }}">
            <td>{{ $row->id }}</td>
            <td>{{ $row->sender }}</td>
            <td style="text-transform: capitalize;">{{ str_replace("ng", "", $row->type) }}</td>
            <td>{{ $row->mobile }}</td>
            <td>{{ money($row->oldbal) }}</td>
            <td>{{ money($row->amount) }}</td>
            <td>{{ money($row->commission) }}</td>
            <td>{{ money($row->lastbal) }}</td>
            <td>{{ $row->trxid }}</td>
            <td>{{ $row->sim }}</td>
            @if($row->status == 20 || $row->status == 22)
                <td><span class='badge badge-pill bg-success'>Success</span></td>
            @elseif($row->status == 33)
                <td><span class='badge badge-pill bg-info text-white'>Waiting</span></td>
            @elseif($row->status == 55)
                <td><span class='badge badge-pill bg-danger text-white'>Danger</span></td>
            @elseif($row->status == 66)
                <td><span class='badge badge-pill bg-danger text-white'>Rejected</span></td>
            @elseif($row->status == 77)
                <td><span class='badge badge-pill bg-success text-white'>Approved</span></td>
            @else
                <td><span class='badge badge-pill bg-warning text-white'> Pending </span></td>
            @endif
            <td>
                {{ $row->sms_time }}
                <p class="text-success font-weight-bold">
                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans() ?>
                </p>
            </td>
            <td>
				@if(auth()->user('web')->user_type=='agent')
			
                @if($row->status == 20 || $row->status == 22 || $row->status == 77 || $row->status == 66) @else
				
                    <a href="#" class="openPopup btn btn-sm btn-success" id="{{ $row->id }}" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a>
                    <!--<a href="#" class="openPopup btn btn-sm btn-success" data-href="{{ route('approved_transaction', $row->id) }}" id="{{ $row->id }}" data-bs-toggle="modal" data-bs-target="#myModal"><i class="bx bx-check-double" aria-hidden="true"></i></a>-->
                    <a href="#" class="rejectBalance btn btn-sm btn-outline-danger" id="{{ $row->id }}"><i class="lni lni-cross-circle" aria-hidden="true"></i></a>
                @endif
				

                <a href="#" class="openPopup btn btn-sm btn-outline-info" data-bs-toggle="modal" data-href="{{ route('view_transaction', $row->id) }}" data-bs-target="#myModal"><i class="lni lni-eye" aria-hidden="true"></i></a>
				@else
				@endif
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
