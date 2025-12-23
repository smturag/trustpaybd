<div class="row justify-content-center my-4">
    <div class="col-md-8">
        <div class="card border shadow-sm">
            <div class="card-header bg-light">
                <strong class="text-center d-block">Deposit Service Table</strong>
            </div>

            <div class="card-body p-0">
                <table class="table table-bordered m-0 text-center">
                    <thead>
                        <tr>
                            <th>Payment Method</th>
                            <th>Total Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $grandTotal = 0; @endphp

                        @foreach($results as $result)
                            <tr>
                                <td>{{ $result['payment_method'] }}</td>
                                <td>{{ number_format($result['total_amount'], 2) }}</td>
                            </tr>
                            @php $grandTotal += $result['total_amount']; @endphp
                        @endforeach

                        <tr class="bg-light font-weight-bold">
                            <td><strong>Total</strong></td>
                            <td><strong>{{ number_format($grandTotal, 2) }}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
