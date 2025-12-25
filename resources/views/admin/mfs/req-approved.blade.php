<div class="modal-header">
    <h5 class="modal-title" id="largemodal1">{{ $request_data->merchant->username }} -{{ $request_data->mfs }} -
        {{ $request_data->number }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <form action="#" id="approved_save" method="post">
        @csrf

        <table class="table table-bordered table-sm">
            <tr>
                <th>{{ translate('sender') }}</th>
                <td>{{ $request_data->merchant->username }}</td>
            </tr>
            <tr>
                <th>{{ translate('customer_number') }}</th>
                <td>{{ $request_data->number }}</td>
            </tr>
            <tr>
                <th>{{ translate('oldbal') }}</th>
                <td>{{ money($request_data->old_balance) }}</td>
            </tr>
            <tr>
                <th>{{ translate('amount') }}</th>
                <td>{{ money($request_data->amount) }}</td>
            </tr>
            <tr>
                <th>{{ translate('mfs') }}</th>
                <td>{{ $request_data->mfs }}</td>
            </tr>
            <tr>
                <th>{{ translate('type') }}</th>
                <td>{{ $request_data->type }}</td>
            </tr>
            <tr>
                <th>{{ translate('lastbal') }}</th>
                <td>{{ money($request_data->new_balance) }}</td>
            </tr>
            <tr>
                <th>{{ translate('trxid') }}</th>
                <td>{{ $request_data->trxid }}</td>
            </tr>

        </table>

        <p>SMS Body: {{ $request_data->msg }}</p>

        <div class="form-group">
            <label for="lastbal">TRX ID</label>
            <input type="text" name="get_trxid" id="get_trxid" class="form-control"
                value="{{ $request_data->get_trxid }}">
            <input type="hidden" id="id" name="id" value="{{ $request_data->id }}">
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </form>
</div>


<script type="text/javascript">
    $(document).ready(function() {
        $("form#approved_save").submit(function(event) {
            event.preventDefault();

            var _token = "{{ csrf_token() }}";
            var get_trxid = $(this).find('input#get_trxid').val();
            var id = $(this).find('input#id').val();

            $.ajax({
                type: 'POST',
                url: "{{ route('approved_save') }}",
                data: {
                    _token: _token,
                    id: id,
                    get_trxid: get_trxid
                },
                success: function(response) {
                    if (response.status === 200) {
                        $("table#req_table").find('tr#' + id).css('background',
                            'antiquewhite');
                        $("table#req_table").find('tr#' + id).find('span.badge').attr(
                            'class', 'badge badge-pill badge-success text-white').text(
                            'Approved');
                        $('#myModal').modal('hide');
                    }
                },
            });
            return false;
        });
    });
</script>
