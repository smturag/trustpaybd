@php
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$filter = request()->get('filter', '7days'); // default last 7 days
$now = Carbon::now();

// Generate categories based on filter
switch ($filter) {
    case '7days':
        $startDate = $now->copy()->subDays(6);
        $categories = [];
        for ($d = 0; $d < 7; $d++) $categories[] = $startDate->copy()->addDays($d)->format('d M');
        break;
    case '15days':
        $startDate = $now->copy()->subDays(14);
        $categories = [];
        for ($d = 0; $d < 15; $d++) $categories[] = $startDate->copy()->addDays($d)->format('d M');
        break;
    case '30days':
        $startDate = $now->copy()->subDays(29);
        $categories = [];
        for ($d = 0; $d < 30; $d++) $categories[] = $startDate->copy()->addDays($d)->format('d M');
        break;
    case 'last6months':
        $categories = [];
        for ($m = 5; $m >= 0; $m--) $categories[] = $now->copy()->subMonths($m)->format('M Y');
        break;
    case 'monthly':
    default:
        $categories = [];
        for ($m = 1; $m <= 12; $m++) $categories[] = Carbon::create()->month($m)->format('M');
        break;
}

// Map data helper (only complete and rejected now)
function mapData($data, $categories, $filter) {
    $resultComplete = array_fill(0, count($categories), 0);
    $resultRejected = array_fill(0, count($categories), 0);

    foreach ($data as $item) {
        if (in_array($filter, ['monthly', 'last6months'])) {
            $index = $filter == 'monthly' ? $item->period - 1 : array_search(Carbon::parse($item->period)->format('M Y'), $categories);
            if($index === false) continue;
        } else {
            $index = array_search(Carbon::parse($item->period)->format('d M'), $categories);
            if($index === false) continue;
        }
        $resultComplete[$index] = $item->complete ?? 0;
        $resultRejected[$index] = $item->rejected ?? 0;
    }

    return [$resultComplete, $resultRejected];
}

// Query PaymentRequest
$paymentQuery = PaymentRequest::select(
    in_array($filter, ['monthly', 'last6months']) 
        ? DB::raw($filter == 'monthly' ? "MONTH(created_at) as period" : "DATE_FORMAT(created_at,'%Y-%m-01') as period") 
        : DB::raw("DATE(created_at) as period"),
    DB::raw("SUM(CASE WHEN status IN (1,2) THEN amount ELSE 0 END) as complete"),
    DB::raw("SUM(CASE WHEN status = 3 THEN amount ELSE 0 END) as rejected")
)->where('created_at','>=', $filter == 'monthly' ? now()->startOfYear() : $startDate);

$paymentData = $paymentQuery->groupBy('period')->orderBy('period')->get();

// Query ServiceRequest
$mfsQuery = ServiceRequest::select(
    in_array($filter, ['monthly', 'last6months']) 
        ? DB::raw($filter == 'monthly' ? "MONTH(created_at) as period" : "DATE_FORMAT(created_at,'%Y-%m-01') as period") 
        : DB::raw("DATE(created_at) as period"),
    DB::raw("SUM(CASE WHEN status IN (2,3) THEN amount ELSE 0 END) as complete"),
    DB::raw("SUM(CASE WHEN status = 4 THEN amount ELSE 0 END) as rejected")
)->where('created_at','>=', $filter == 'monthly' ? now()->startOfYear() : $startDate);

$mfsData = $mfsQuery->groupBy('period')->orderBy('period')->get();

// Map data
[$paymentComplete, $paymentRejected] = mapData($paymentData, $categories, $filter);
[$mfsComplete, $mfsRejected] = mapData($mfsData, $categories, $filter);
@endphp

<div class="mb-3">
    <form method="GET">
        <label>View By: </label>
        <select name="filter" onchange="this.form.submit()">
            <option value="7days" {{ $filter == '7days' ? 'selected' : '' }}>Last 7 Days</option>
            <option value="15days" {{ $filter == '15days' ? 'selected' : '' }}>Last 15 Days</option>
            <option value="30days" {{ $filter == '30days' ? 'selected' : '' }}>Last 30 Days</option>
            <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>This Year (Monthly)</option>
            <option value="last6months" {{ $filter == 'last6months' ? 'selected' : '' }}>Last 6 Months</option>
        </select>
    </form>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">Payment Request Summary</div>
            <div class="card-body">
                <div id="payment-summary-chart"></div>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header">MFS Request Summary</div>
            <div class="card-body">
                <div id="mfs-summary-chart"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function renderMixedChart(selector, complete, rejected, categories) {
        new ApexCharts(document.querySelector(selector), {
            chart: { height: 350, type: 'line' },
            series: [
                { name: 'Completed', type: 'column', data: complete },
                { name: 'Rejected', type: 'line', data: rejected }
            ],
            stroke: { width: [0, 3], curve: 'smooth' },
            dataLabels: { enabled: true, formatter: val => val.toLocaleString() },
            xaxis: { categories: categories },
            yaxis: { title: { text: 'Amount' } },
            colors: ['#0d6efd','#dc3545'],
            grid: { borderColor: '#e9ecef' },
            legend: { position: 'top' }
        }).render();
    }

    renderMixedChart("#payment-summary-chart", @json($paymentComplete), @json($paymentRejected), @json($categories));
    renderMixedChart("#mfs-summary-chart", @json($mfsComplete), @json($mfsRejected), @json($categories));
});
</script>
