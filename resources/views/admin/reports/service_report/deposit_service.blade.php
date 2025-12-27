<div class="row justify-content-center my-4">
    <div class="col-md-10">
        <div class="card border shadow-sm">
            <div class="card-header bg-light">
                <strong class="text-center d-block">Deposit Service Table</strong>
                @if(isset($dateRange) && $dateRange)
                    <small class="text-muted d-block text-center">
                        From: {{ \Carbon\Carbon::parse($dateRange->first_date)->format('d M Y H:i') }} | 
                        To: {{ \Carbon\Carbon::parse($dateRange->last_date)->format('d M Y H:i') }}
                    </small>
                @endif
                
                {{-- Active Filters Display --}}
                @if(request()->hasAny(['selectMerchant', 'selectSubMerchant', 'method', 'payment_type', 'start_date', 'end_date']))
                    <div class="mt-3 p-2 bg-white border rounded">
                        <small class="d-block mb-2"><strong>Active Filters:</strong></small>
                        <div class="d-flex flex-wrap gap-2">
                            @if(request('selectMerchant'))
                                @php
                                    $merchant = App\Models\Merchant::find(request('selectMerchant'));
                                @endphp
                                <span class="badge bg-primary">Merchant: {{ $merchant->fullname ?? 'N/A' }}</span>
                            @endif
                            
                            @if(request('selectSubMerchant'))
                                @php
                                    $subMerchant = App\Models\Merchant::find(request('selectSubMerchant'));
                                @endphp
                                <span class="badge bg-info">Sub Merchant: {{ $subMerchant->fullname ?? 'N/A' }}</span>
                            @endif
                            
                            @if(request('method') && request('method') != 'All Method')
                                <span class="badge bg-success">Method: {{ request('method') }}</span>
                            @endif
                            
                            @if(request('payment_type'))
                                <span class="badge bg-warning text-dark">Payment Type: {{ request('payment_type') }}</span>
                            @endif
                            
                            @if(request('start_date'))
                                <span class="badge bg-secondary">Start: {{ request('start_date') }}{{ request('start_time') ? ' ' . request('start_time') : '' }}</span>
                            @endif
                            
                            @if(request('end_date'))
                                <span class="badge bg-secondary">End: {{ request('end_date') }}{{ request('end_time') ? ' ' . request('end_time') : '' }}</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Total Amount</th>
                            <th>Total Fee</th>
                            <th>Total Commission</th>
                            <th>Net Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grandTotal = 0; 
                            $grandFee = 0;
                            $grandCommission = 0;
                            $grandNet = 0;
                        @endphp

                        @foreach($results as $result)
                            @php
                                $netAmount = $result['total_amount'] - $result['total_fee'] + $result['total_commission'];
                                $grandTotal += $result['total_amount'];
                                $grandFee += $result['total_fee'];
                                $grandCommission += $result['total_commission'];
                                $grandNet += $netAmount;
                            @endphp
                            <tr>
                                <td>{{ $result['payment_method'] }}</td>
                                <td>{{ number_format($result['total_amount'], 2) }}</td>
                                <td class="text-danger">{{ number_format($result['total_fee'], 2) }}</td>
                                <td class="text-success">{{ number_format($result['total_commission'], 2) }}</td>
                                <td><strong>{{ number_format($netAmount, 2) }}</strong></td>
                            </tr>
                        @endforeach

                        <tr class="bg-light font-weight-bold">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                            <td class="text-danger"><strong>{{ number_format($grandFee, 2) }}</strong></td>
                            <td class="text-success"><strong>{{ number_format($grandCommission, 2) }}</strong></td>
                            <td><strong>{{ number_format($grandNet, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
