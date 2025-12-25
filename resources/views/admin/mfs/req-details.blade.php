<div class="modal-header">
    <h5 class="modal-title">Service Request Details #{{ $request_data->id }}</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">
    <div class="table-responsive">
        <table class="table table-bordered table-sm">
            <tr>
                <th>Merchant</th>
                <td>{{ $merchantName ?: '-' }}</td>
            </tr>
            <tr>
                <th>Sub Merchant</th>
                <td>{{ $subMerchantName ?: '-' }}</td>
            </tr>
            <tr>
                <th>Customer</th>
                <td>{{ $customerName ?: '-' }}</td>
            </tr>
            <tr>
                <th>Agent</th>
                <td>{{ $agentName ?: '-' }}</td>
            </tr>
            <tr>
                <th>Modem</th>
                <td>{{ $modemInfo ?: '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-bordered table-sm mb-0">
            <tr><th style="width: 220px;">id</th><td style="word-break: break-word;">{{ $request_data->id ?? '-' }}</td></tr>
            <tr><th>merchant_id</th><td style="word-break: break-word;">{{ $request_data->merchant_id ?? '-' }}</td></tr>
            <tr><th>sub_merchant</th><td style="word-break: break-word;">{{ $request_data->sub_merchant ?? '-' }}</td></tr>
            <tr><th>customer_id</th><td style="word-break: break-word;">{{ $request_data->customer_id ?? '-' }}</td></tr>
            <tr><th>mfs</th><td style="word-break: break-word;">{{ $request_data->mfs ?? '-' }}</td></tr>
            <tr><th>mfs_id</th><td style="word-break: break-word;">{{ $request_data->mfs_id ?? '-' }}</td></tr>
            <tr><th>number</th><td style="word-break: break-word;">{{ $request_data->number ?? '-' }}</td></tr>
            <tr><th>type</th><td style="word-break: break-word;">{{ $request_data->type ?? '-' }}</td></tr>
            <tr><th>old_balance</th><td style="word-break: break-word;">{{ $request_data->old_balance ?? '-' }}</td></tr>
            <tr><th>amount</th><td style="word-break: break-word;">{{ $request_data->amount ?? '-' }}</td></tr>
            <tr><th>new_balance</th><td style="word-break: break-word;">{{ $request_data->new_balance ?? '-' }}</td></tr>
            <tr><th>merchant_fee</th><td style="word-break: break-word;">{{ $request_data->merchant_fee ?? '-' }}</td></tr>
            <tr><th>merchant_commission</th><td style="word-break: break-word;">{{ $request_data->merchant_commission ?? '-' }}</td></tr>
            <tr><th>sub_merchant_fee</th><td style="word-break: break-word;">{{ $request_data->sub_merchant_fee ?? '-' }}</td></tr>
            <tr><th>sub_merchant_commission</th><td style="word-break: break-word;">{{ $request_data->sub_merchant_commission ?? '-' }}</td></tr>
            <tr><th>merchant_main_amount</th><td style="word-break: break-word;">{{ $request_data->merchant_main_amount ?? '-' }}</td></tr>
            <tr><th>sub_merchant_main_amount</th><td style="word-break: break-word;">{{ $request_data->sub_merchant_main_amount ?? '-' }}</td></tr>
            <tr><th>user_fee</th><td style="word-break: break-word;">{{ $request_data->user_fee ?? '-' }}</td></tr>
            <tr><th>user_commission</th><td style="word-break: break-word;">{{ $request_data->user_commission ?? '-' }}</td></tr>
            <tr><th>user_main_amount</th><td style="word-break: break-word;">{{ $request_data->user_main_amount ?? '-' }}</td></tr>
            <tr><th>partner_fee</th><td style="word-break: break-word;">{{ $request_data->partner_fee ?? '-' }}</td></tr>
            <tr><th>partner_commission</th><td style="word-break: break-word;">{{ $request_data->partner_commission ?? '-' }}</td></tr>
            <tr><th>partner_main_amount</th><td style="word-break: break-word;">{{ $request_data->partner_main_amount ?? '-' }}</td></tr>
            <tr><th>sim_balance</th><td style="word-break: break-word;">{{ $request_data->sim_balance ?? '-' }}</td></tr>
            <tr><th>modem_id</th><td style="word-break: break-word;">{{ $request_data->modem_id ?? '-' }}</td></tr>
            <tr><th>sim_number</th><td style="word-break: break-word;">{{ $request_data->sim_number ?? '-' }}</td></tr>
            <tr><th>agent_id</th><td style="word-break: break-word;">{{ $request_data->agent_id ?? '-' }}</td></tr>
            <tr><th>partner</th><td style="word-break: break-word;">{{ $request_data->partner ?? '-' }}</td></tr>
            <tr><th>trxid</th><td style="word-break: break-word;">{{ $request_data->trxid ?? '-' }}</td></tr>
            <tr><th>msg</th><td style="word-break: break-word;">{{ $request_data->msg ?? '-' }}</td></tr>
            <tr><th>get_trxid</th><td style="word-break: break-word;">{{ $request_data->get_trxid ?? '-' }}</td></tr>
            <tr><th>idate</th><td style="word-break: break-word;">{{ $request_data->idate ?? '-' }}</td></tr>
            <tr><th>status</th><td style="word-break: break-word;">{{ $request_data->status ?? '-' }}</td></tr>
            <tr><th>created_at</th><td style="word-break: break-word;">{{ $request_data->created_at ?? '-' }}</td></tr>
            <tr><th>updated_at</th><td style="word-break: break-word;">{{ $request_data->updated_at ?? '-' }}</td></tr>
            <tr><th>send_sms</th><td style="word-break: break-word;">{{ $request_data->send_sms ?? '-' }}</td></tr>
            <tr><th>agent_send_sms</th><td style="word-break: break-word;">{{ $request_data->agent_send_sms ?? '-' }}</td></tr>
            <tr><th>action_by</th><td style="word-break: break-word;">{{ $request_data->action_by ?? '-' }}</td></tr>
            <tr><th>webhook_url</th><td style="word-break: break-word;">{{ $request_data->webhook_url ?? '-' }}</td></tr>
            <tr><th>merchant_balance_updated</th><td style="word-break: break-word;">{{ $request_data->merchant_balance_updated ?? '-' }}</td></tr>
        </table>
    </div>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
</div>
