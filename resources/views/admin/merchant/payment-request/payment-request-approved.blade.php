<div class="modal-content">
    <div class="modal-header btn-success text-white">
        <h5 class="modal-title" id="largemodal1">Payment Request - {{ $request_data->payment_method }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <form action="#" id="approved_balance_manager_save" method="post">
            @csrf

            <table class="table table-bordered table-sm">
                <tr>
                    <th>{{ translate('Trxid') }}</th>
                    <td class="text-center">{{ $request_data->trxid }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Amount') }}</th>
                    <td class="text-center"><span class="fw-bold">{{ $request_data->amount }}</span></td>
                </tr>
                <tr>
                    <th>{{ translate('Payment Method') }}</th>
                    <td class="text-center">{{ $request_data->payment_method }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Customer Trxid') }}</th>
                    <td class="text-center">{{ $request_data->reference }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Currency') }}</th>
                    <td class="text-center">{{ $request_data->currency }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Callback Url') }}</th>
                    <td><a href="{{ $request_data->callback_url }}">{{ $request_data->callback_url }}</a></td>
                </tr>


                <tr>
                    <th>{{ translate('Customer Name') }}</th>
                    <td>{{ $request_data->cust_name }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Customer Number') }}</th>
                    <td class="text-center">{{ $request_data->cust_phone }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Note') }}</th>
                    <td>{{ $request_data->note }}</td>
                </tr>
                <tr>
                    <th>{{ translate('Request from') }}</th>
                    <td class="text-center">{{ $request_data->ip }}</td>
                </tr>

                <tr>
                    <th>{{ translate('Merchant Info') }}</th>
                    <td>
                        {{--                       {{ $request_data->merchant->m }}--}}

                        <p>{{  $request_data->merchant->fullname }}</br>
                            {{  $request_data->merchant->mobile }}</br>
                            balance(<span class="fw-bold">{{  $request_data->merchant->balance }}</span>)</br>
                            {{  $request_data->merchant->merchant_type }}</p>
                    </td>
                </tr>
            </table>

            <div>
                <select name="" id="status_dropdown" class="form-control">
                    <option value="1">Select Status</option>
                    <option value="1">Success</option>
                    <option value="0">Pending</option>
                    <option value="2">Reject</option>
                </select>
            </div>

            <div class="modal-footer">
                <input type="hidden" value="{{ $request_data->id }}" id="id">
                <button type="submit" class="btn btn-success">{{ translate('Update') }}</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </form>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("form#approved_balance_manager_save").submit(function (event) {
            event.preventDefault();

            var _token = "{{ csrf_token() }}";
            var status = $("#status_dropdown").val();
            var id = $(this).find('input#id').val();


            $.ajax({
                type: 'POST',
                url: "{{ url('admin/merchant/payment-request/approve-payment-request') }}/" + id,
                data: {_token: _token, id: id, status: status},
                success: function (response) {
                    if (response.status === 200) {
                        $("table#balance_manager_table").find('tr#' + id).css('background', 'antiquewhite');
                        $("table#balance_manager_table").find('tr#' + id).find('span.badge').attr('class', 'badge badge-pill badge-success text-white').text('Approved');
                        $('#myModal').modal('hide');
                    }
                },
            });
            return false;
        });
    });
</script>
