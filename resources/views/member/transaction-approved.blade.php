
        <div class="modal-content">
            <div class="modal-header">


            <h5 class="modal-title" id="largemodal1">{{ $request_data->sender }} - {{ $request_data->mobile }}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <form action="#" id="approved_transaction_save" method="post">
                @csrf

                <table class="table table-bordered table-sm">
                    <tr>
                        <th>{{ translate('sender') }}</th>
                        <td>{{ $request_data->sender }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('customer_number') }}</th>
                        <td>{{ $request_data->mobile }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('oldbal') }}</th>
                        <td>{{ money($request_data->oldbal) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('amount') }}</th>
                        <td>{{ money($request_data->amount) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('comm') }}</th>
                        <td>{{ money($request_data->commission) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('lastbal') }}</th>
                        <td>{{ money($request_data->lastbal) }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('trxid') }}</th>
                        <td>{{ $request_data->trxid }}</td>
                    </tr>
                    <tr>
                        <th>{{ translate('agent_number') }}</th>
                        <td>{{ $request_data->sim }}</td>
                    </tr>
                </table>

                <p>SMS Body: {{ $request_data->sms_body }}</p>

                <div class="form-group">
                    <label for="lastbal">Last Balance</label>
                    <input type="number" name="lastbal" id="lastbal" class="form-control" value="{{ $request_data->lastbal }}">
                    <input type="hidden" id="id" name="id" value="{{ $request_data->id }}">
                </div>

                <div class="modal-footer">
					 <button type="submit" class="btn btn-success">{{ translate('Save & Approve Last Balance') }}</button>
					<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					
				</div>
            </form>
        </div>
    </div>



<script type="text/javascript">
    $(document).ready(function () {
        $("form#approved_transaction_save").submit(function (event) {
            event.preventDefault();

            var _token = "{{ csrf_token() }}";
            var lastbal = $(this).find('input#lastbal').val();
            var id = $(this).find('input#id').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('approved_transaction_save') }}",
                data: {_token: _token, id:id, lastbal: lastbal},
                success: function (response) {
                    if (response.status === 200) {
                        $("table#transaction_table").find('tr#' + id).css('background', 'antiquewhite');
                        $("table#transaction_table").find('tr#' + id).find('span.badge').attr('class', 'badge badge-pill badge-success text-white').text('Approved');
                         $('table').find('tr#' + id).animate({backgroundColor: "#e74c3c", color: "#fff"}, "slow").animate({opacity: "hide"}, "slow");
						$('#myModal').modal('hide');
                    }
                },
            });
            return false;
        });
    });
</script>
