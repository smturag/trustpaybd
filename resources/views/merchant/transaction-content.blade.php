@php($url_number = Request::segment(3))

<table id="balance_manager_table" class="table table-hover mb-0 text-center align-middle table-bordered">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col" class="text-center">Sender</th>
        <th scope="col">C Number</th>
        <th scope="col">Amount</th>
        <th scope="col">Trxid</th>
        <th scope="col">A Number</th>
        <th scope="col">Status</th>
        <th scope="col">Date</th>
       
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr id="{{ $row->id }}">
            <td>{{ $loop->iteration }}</td>
            <td>{{ $row->sender }}</td>
          
            <td>{{ $row->mobile }}</td>
        
            <td>{{ money($row->amount) }}</td>
           
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
