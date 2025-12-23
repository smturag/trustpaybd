<table class="table table-hover table-bordered mb-0 table-striped">
    <thead>
    <tr>
        <th scope="col" class="text-center">#</th>
        <th scope="col">ReqTime/SmsTime</th>
        <th scope="col" class="text-center">Sender</th>
      	<!--<th scope="col">SmsTime</th>-->
		 <th scope="col">Sim</th>
        <th scope="col">Message</th>
        <th scope="col">DeviceId</th>
        <th scope="col">Sim Slot</th>
        
    </tr>
    </thead>
    <tbody>
    @foreach($data as $row)
        <tr>
            <td class="text-center">{{ $row->id }}</td>
			<td>{{ bdtime($row->created_at) }}
			 <p class="text-success font-weight-bold">
                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->created_at))->diffForHumans() ?>
                </p>
                {{ bdtime($row->sms_time) }}
            <p class="text-success font-weight-bold">
                    <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans() ?>
                </p>
			</td>
            <td class="text-center">{{ $row->sender }}</td>
            <!--<td>{{ bdtime($row->sms_time) }}-->
            <!--<p class="text-success font-weight-bold">-->
            <!--        <?php echo \Carbon\Carbon::createFromTimeStamp(strtotime($row->sms_time))->diffForHumans() ?>-->
            <!--    </p>-->
            <!--</td>-->
			<td>{{ $row->sim_number }}</td>
            <td  id="multiline-text">{{ $row->sms }}</td>
            <td>{{ $row->device_id }}</td>
            <td>{{ $row->sim_slot }}</td>
        </tr>
    @endforeach

    @if($data->isEmpty())
        <tr>
            <td colspan="15" style="text-align: center;padding: 50px;font-size: 18px;">No Record Found.</td>
        </tr>
    @endif
    </tbody>
</table>

<style>
    #multiline-text {
  width: 200px; /* Adjust the width as needed */
  height: auto;
  border: 1px solid #000;
  padding: 10px;
  white-space: pre-wrap;
}
</style>


{!! $data->links('common.pagination-custom') !!}
