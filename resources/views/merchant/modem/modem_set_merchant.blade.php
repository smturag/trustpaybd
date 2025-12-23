
        <div class="modal-content">
            <div class="modal-header">


            <h5 class="modal-title" id="largemodal1">{{ $request_data->member_code }} - {{ $request_data->sim_number }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="#" id="modem_for_merchant_save" method="post">
                @csrf

                <table class="table table-bordered table-sm">
                    <tr>
                        <th>{{ translate('member_code') }}</th>
                        <td>{{ $request_data->member_code }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('sim_number') }}</th>
                        <td>{{ $request_data->sim_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('deviceid') }}</th>
                        <td>{{ ($request_data->deviceid) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('partner') }}</th>
                        <td>{{ ($request_data->partner) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('dso') }}</th>
                        <td>{{ ($request_data->dso) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('agent') }}</th>
                        <td>{{ ($request_data->agent) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('modem_details') }}</th>
                        <td>{{ $request_data->modem_details }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('token') }}</th>
                        <td>{{ $request_data->token }}</td>
                    </tr>
                </table>


                <div class="form-group">
                    <label for="lastbal">Merchant Code</label>
                    <input type="number" name="merchant_code" id="merchant_code" class="form-control" value="{{ $request_data->merchant_code }}">
                    <input type="hidden" id="id" name="id" value="{{ $request_data->id }}">
                </div>

              	<div class="modal-footer">
					<button type="submit" class="btn btn-primary">{{ translate('Save') }}</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					
				</div>
            </form>
        </div>
    </div>



<script type="text/javascript">
    $(document).ready(function () {
        $("form#modem_for_merchant_save").submit(function (event) {
            event.preventDefault();

            var _token = "{{ csrf_token() }}";
            var merchant_code = $(this).find('input#merchant_code').val();
            var id = $(this).find('input#id').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('modem_for_merchant_saveAction') }}",
                data: {_token: _token, id:id, merchant_code: merchant_code},
                success: function (response) {
                    if (response.status === 200) {
                        $("table#modem_table").find('tr#' + id).css('background', 'antiquewhite');
                        $("table#modem_table").find('tr#' + id).find('span.badge').attr('class', 'badge badge-pill badge-success text-white').text('Approved');
                        $('#myModal').modal('hide');
                    }
                },
            });
            return false;
        });
    });
</script>
