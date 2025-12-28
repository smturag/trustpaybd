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

// Map data helper (only complete and rejected now) - Using anonymous function to prevent redeclaration
$mapData = function($data, $categories, $filter) {
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
};

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
[$paymentComplete, $paymentRejected] = $mapData($paymentData, $categories, $filter);
[$mfsComplete, $mfsRejected] = $mapData($mfsData, $categories, $filter);
@endphp

<!-- Filter Section -->
<div class="mb-6">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
        <form method="GET" class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Transaction Analytics</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Comprehensive overview of your transactions</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 w-full sm:w-auto">
                <label class="text-sm font-semibold text-gray-700 dark:text-gray-300 whitespace-nowrap">Period:</label>
                <select name="filter" onchange="this.form.submit()" class="flex-1 sm:flex-initial px-4 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 transition-all duration-200 cursor-pointer shadow-sm hover:shadow-md">
                    <option value="7days" {{ $filter == '7days' ? 'selected' : '' }}>📅 Last 7 Days</option>
                    <option value="15days" {{ $filter == '15days' ? 'selected' : '' }}>📅 Last 15 Days</option>
                    <option value="30days" {{ $filter == '30days' ? 'selected' : '' }}>📅 Last 30 Days</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>📆 This Year (Monthly)</option>
                    <option value="last6months" {{ $filter == 'last6months' ? 'selected' : '' }}>📆 Last 6 Months</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Charts Grid -->
<!-- Charts Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Payment Request Chart -->
    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-300">
        <div class="px-6 py-5 bg-gradient-to-r from-blue-500 to-cyan-600 border-b border-blue-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Payment Transactions</h3>
                        <p class="text-xs text-blue-100">Revenue analysis by period</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full">Active</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div id="payment-summary-chart" class="w-full"></div>
        </div>
        <div class="px-6 pb-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Completed</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">৳{{ number_format(array_sum($paymentComplete), 0) }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Rejected</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">৳{{ number_format(array_sum($paymentRejected), 0) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- MFS Request Chart -->
    <div class="group bg-white dark:bg-gray-800 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-2xl transition-all duration-300">
        <div class="px-6 py-5 bg-gradient-to-r from-green-500 to-emerald-600 border-b border-green-600">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">MFS Transactions</h3>
                        <p class="text-xs text-green-100">Mobile financial services</p>
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-semibold rounded-full">Active</span>
                </div>
            </div>
        </div>
        <div class="p-6">
            <div id="mfs-summary-chart" class="w-full"></div>
        </div>
        <div class="px-6 pb-6">
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl p-4 border border-green-200 dark:border-green-800">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Completed</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">৳{{ number_format(array_sum($mfsComplete), 0) }}</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 rounded-xl p-4 border border-red-200 dark:border-red-800">
                    <div class="flex items-center space-x-2 mb-2">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-xs font-semibold text-gray-600 dark:text-gray-300">Rejected</span>
                    </div>
                    <p class="text-xl font-bold text-gray-900 dark:text-white">৳{{ number_format(array_sum($mfsRejected), 0) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const isDark = document.documentElement.classList.contains('dark');
    
    const chartOptions = {
        theme: {
            mode: isDark ? 'dark' : 'light',
            palette: 'palette1'
        },
        chart: { 
            height: 320, 
            type: 'line',
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: true,
                tools: {
                    download: true,
                    selection: true,
                    zoom: true,
                    zoomin: true,
                    zoomout: true,
                    pan: true,
                    reset: true
                },
                autoSelected: 'zoom'
            },
            animations: {
                enabled: true,
                easing: 'easeinout',
                speed: 800,
                animateGradually: {
                    enabled: true,
                    delay: 150
                }
            },
            background: 'transparent'
        },
        stroke: { 
            width: [0, 4], 
            curve: 'smooth',
            dashArray: [0, 0]
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                columnWidth: '55%',
                borderRadiusApplication: 'end',
                dataLabels: {
                    position: 'top'
                }
            }
        },
        dataLabels: { 
            enabled: true,
            enabledOnSeries: [0],
            formatter: val => val > 0 ? '৳' + val.toLocaleString('en-IN', {maximumFractionDigits: 0}) : '',
            style: {
                fontSize: '11px',
                fontWeight: 700,
                colors: [isDark ? '#fff' : '#374151']
            },
            background: {
                enabled: true,
                foreColor: isDark ? '#1f2937' : '#fff',
                padding: 6,
                borderRadius: 6,
                borderWidth: 0,
                opacity: 0.95,
                dropShadow: {
                    enabled: true,
                    top: 1,
                    left: 1,
                    blur: 2,
                    opacity: 0.2
                }
            },
            offsetY: -8
        },
        xaxis: { 
            labels: {
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: isDark ? '#9ca3af' : '#6B7280'
                },
                rotate: -45,
                rotateAlways: false
            },
            axisBorder: {
                show: true,
                color: isDark ? '#374151' : '#E5E7EB'
            },
            axisTicks: {
                show: true,
                color: isDark ? '#374151' : '#E5E7EB'
            }
        },
        yaxis: { 
            title: { 
                text: 'Amount (৳)',
                style: {
                    fontSize: '13px',
                    fontWeight: 700,
                    color: isDark ? '#e5e7eb' : '#374151'
                }
            },
            labels: {
                formatter: val => '৳' + val.toLocaleString('en-IN', {maximumFractionDigits: 0}),
                style: {
                    fontSize: '12px',
                    fontWeight: 600,
                    colors: isDark ? '#9ca3af' : '#6B7280'
                }
            }
        },
        grid: { 
            borderColor: isDark ? '#374151' : '#E5E7EB',
            strokeDashArray: 3,
            xaxis: {
                lines: {
                    show: false
                }
            },
            yaxis: {
                lines: {
                    show: true
                }
            },
            padding: {
                top: 0,
                right: 15,
                bottom: 0,
                left: 10
            }
        },
        legend: { 
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '13px',
            fontWeight: 700,
            labels: {
                colors: isDark ? '#e5e7eb' : '#374151'
            },
            markers: {
                width: 14,
                height: 14,
                radius: 4,
                offsetX: -2
            },
            itemMargin: {
                horizontal: 16,
                vertical: 8
            }
        },
        tooltip: {
            shared: true,
            intersect: false,
            theme: isDark ? 'dark' : 'light',
            style: {
                fontSize: '13px',
                fontFamily: 'Inter, sans-serif'
            },
            y: {
                formatter: val => '৳' + val.toLocaleString('en-IN', {maximumFractionDigits: 0})
            },
            marker: {
                show: true
            }
        },
        responsive: [{
            breakpoint: 768,
            options: {
                chart: {
                    height: 280
                },
                plotOptions: {
                    bar: {
                        columnWidth: '70%'
                    }
                },
                legend: {
                    position: 'bottom',
                    horizontalAlign: 'center'
                },
                xaxis: {
                    labels: {
                        rotate: -45
                    }
                }
            }
        }]
    };

    function renderChart(selector, complete, rejected, categories, colors) {
        const options = {
            ...chartOptions,
            series: [
                { name: 'Completed', type: 'column', data: complete },
                { name: 'Rejected', type: 'line', data: rejected }
            ],
            colors: colors,
            fill: {
                type: ['gradient', 'solid'],
                gradient: {
                    shade: isDark ? 'dark' : 'light',
                    type: 'vertical',
                    shadeIntensity: 0.4,
                    gradientToColors: [colors[0] + 'CC'],
                    opacityFrom: 0.95,
                    opacityTo: 0.75,
                    stops: [0, 90, 100]
                }
            },
            xaxis: {
                ...chartOptions.xaxis,
                categories: categories
            }
        };
        
        new ApexCharts(document.querySelector(selector), options).render();
    }

    renderChart("#payment-summary-chart", @json($paymentComplete), @json($paymentRejected), @json($categories), ['#3B82F6', '#EF4444']);
    renderChart("#mfs-summary-chart", @json($mfsComplete), @json($mfsRejected), @json($categories), ['#10B981', '#EF4444']);
});
</script>
