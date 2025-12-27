<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment Requests Export</title>
    <style>
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; vertical-align: top; }
        th { background: #f3f3f3; }
    </style>
</head>
<body>
@php
    $isGeneral = $user->merchant_type === 'general';
@endphp
<table>
    <thead>
        <tr>
            <th>From</th>
            <th>Method</th>
            <th>Payment Type</th>
            <th>Amount</th>
            <th>Fee</th>
            <th>Commission</th>
            <th>New Amount</th>
            <th>Balance Change</th>
            <th>TrxId</th>
            <th>Reference</th>
            <th>Created At</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $row)
            @php
                $fee = $isGeneral ? $row->merchant_fee : $row->sub_merchant_fee;
                $commission = $isGeneral ? $row->merchant_commission : $row->sub_merchant_commission;
                $netAmount = $isGeneral ? $row->merchant_main_amount : $row->sub_merchant_main_amount;
            @endphp
            <tr>
                <td>{{ $bmMobiles[$row->payment_method_trx] ?? '' }}</td>
                <td>{{ $row->payment_method }}</td>
                <td>{{ $row->payment_type }}</td>
                <td>{{ $row->amount }}</td>
                <td>{{ $fee }}</td>
                <td>{{ $commission }}</td>
                <td>{{ $netAmount }}</td>
                <td>
                    {{ $row->merchant_last_balance !== null ? number_format($row->merchant_last_balance, 2) : '-' }} → 
                    {{ $row->merchant_new_balance !== null ? number_format($row->merchant_new_balance, 2) : '-' }}
                </td>
                <td>{{ $row->payment_method_trx }}</td>
                <td>{{ $row->reference }}</td>
                <td>{{ optional($row->created_at)->format('Y-m-d H:i:s') }}</td>
                <td>
                    @if ($row->status == 0)
                        Pending
                    @elseif ($row->status == 1)
                        Success
                    @elseif ($row->status == 2)
                        Approved
                    @elseif ($row->status == 3)
                        Rejected
                    @else
                        Unknown
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
</body>
</html>
