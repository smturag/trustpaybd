<div class="row justify-content-center my-4">
    <div class="col-md-10">
        <div class="card border shadow-sm">
            <div class="card-header bg-light">
                <strong class="text-center d-block">Withdraw Service Table</strong>
                <div class="text-center mt-2" style="font-size: 0.9rem;">
                    @if(request('method') && request('method') != 'All Method')
                        <span class="badge bg-primary me-2">Method: {{ request('method') }}</span>
                    @endif
                    @php
                        // Determine start date and time
                        if(request('start_date')) {
                            $startDateDisplay = date('d M Y', strtotime(request('start_date')));
                            $startTimeDisplay = request('start_time') ? date('h:i A', strtotime(request('start_time'))) : '12:00 AM';
                        } elseif(isset($dateRange) && $dateRange && $dateRange->first_date) {
                            $startDateDisplay = date('d M Y', strtotime($dateRange->first_date));
                            $startTimeDisplay = date('h:i A', strtotime($dateRange->first_date));
                        } else {
                            $startDateDisplay = date('d M Y');
                            $startTimeDisplay = date('h:i A');
                        }
                        
                        // Determine end date and time
                        if(request('end_date')) {
                            $endDateDisplay = date('d M Y', strtotime(request('end_date')));
                            $endTimeDisplay = request('end_time') ? date('h:i A', strtotime(request('end_time'))) : '11:59 PM';
                        } elseif(isset($dateRange) && $dateRange && $dateRange->last_date) {
                            $endDateDisplay = date('d M Y', strtotime($dateRange->last_date));
                            $endTimeDisplay = date('h:i A', strtotime($dateRange->last_date));
                        } else {
                            $endDateDisplay = date('d M Y');
                            $endTimeDisplay = date('h:i A');
                        }
                    @endphp
                    <span class="badge bg-success me-2" style="font-size: 0.95rem; padding: 6px 12px;">
                        <i class='bx bx-calendar'></i> Date Range: 
                        <strong>{{ $startDateDisplay }}</strong>
                        <small>({{ $startTimeDisplay }})</small>
                        to 
                        <strong>{{ $endDateDisplay }}</strong>
                        <small>({{ $endTimeDisplay }})</small>
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead>
                        <tr>
                            <th style="width: 50%;">MFS</th>
                            <th style="width: 50%;">Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp

                        @foreach($results as $result)
                            <tr>
                                <td style="text-align: left; padding-left: 15px;"><strong>{{ $result['mfs'] }}</strong></td>
                                <td>৳ {{ number_format($result['total_amount'], 2) }}</td>
                            </tr>
                            @php $grandTotal += $result['total_amount']; @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="bg-light font-weight-bold grand-total-row">
                            <td style="text-align: left; padding-left: 15px;"><strong>Grand Total</strong></td>
                            <td><strong>৳ {{ number_format($grandTotal, 2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
