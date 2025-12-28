@php
use App\Models\PaymentRequest;
use App\Models\ServiceRequest;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

$filter = request()->get('filter', '7days');
$now = Carbon::now();

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

$paymentQuery = PaymentRequest::select(
    in_array($filter, ['monthly', 'last6months']) 
        ? DB::raw($filter == 'monthly' ? "MONTH(created_at) as period" : "DATE_FORMAT(created_at,'%Y-%m-01') as period") 
        : DB::raw("DATE(created_at) as period"),
    DB::raw("SUM(CASE WHEN status IN (1,2) THEN amount ELSE 0 END) as complete"),
    DB::raw("SUM(CASE WHEN status = 3 THEN amount ELSE 0 END) as rejected")
)->where('created_at','>=', $filter == 'monthly' ? now()->startOfYear() : $startDate);

$paymentData = $paymentQuery->groupBy('period')->orderBy('period')->get();

$mfsQuery = ServiceRequest::select(
    in_array($filter, ['monthly', 'last6months']) 
        ? DB::raw($filter == 'monthly' ? "MONTH(created_at) as period" : "DATE_FORMAT(created_at,'%Y-%m-01') as period") 
        : DB::raw("DATE(created_at) as period"),
    DB::raw("SUM(CASE WHEN status IN (2,3) THEN amount ELSE 0 END) as complete"),
    DB::raw("SUM(CASE WHEN status = 4 THEN amount ELSE 0 END) as rejected")
)->where('created_at','>=', $filter == 'monthly' ? now()->startOfYear() : $startDate);

$mfsData = $mfsQuery->groupBy('period')->orderBy('period')->get();

[$paymentComplete, $paymentRejected] = $mapData($paymentData, $categories, $filter);
[$mfsComplete, $mfsRejected] = $mapData($mfsData, $categories, $filter);
@endphp

<!-- Analytics Header with Filter -->
<div class="mb-6">
    <div class="bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 dark:from-indigo-600 dark:via-purple-600 dark:to-pink-600 rounded-2xl shadow-2xl p-6 border-2 border-white/20">
        <form method="GET" class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="w-14 h-14 rounded-2xl bg-white/20 backdrop-blur-xl flex items-center justify-center shadow-xl">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="text-2xl font-extrabold text-white mb-1 flex items-center">
                        Transaction Analytics
                    </h2>
                    <p class="text-white/90 text-sm font-medium">Comprehensive overview of your transactions</p>
                </div>
            </div>
            <div class="flex items-center space-x-3 bg-white/10 backdrop-blur-xl rounded-xl px-4 py-3 shadow-lg border border-white/20">
                <label class="text-sm font-bold text-white whitespace-nowrap flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Period:
                </label>
                <select name="filter" onchange="this.form.submit()" class="px-5 py-2.5 bg-white dark:bg-gray-800 border-2 border-white/30 rounded-xl focus:ring-2 focus:ring-white focus:border-white text-sm font-bold text-gray-700 dark:text-gray-200 cursor-pointer shadow-lg hover:shadow-xl transition-all duration-300 hover:scale-105">
                    <option value="7days" {{ $filter == '7days' ? 'selected' : '' }}>📅 Last 7 Days</option>
                    <option value="15days" {{ $filter == '15days' ? 'selected' : '' }}>📅 Last 15 Days</option>
                    <option value="30days" {{ $filter == '30days' ? 'selected' : '' }}>📅 Last 30 Days</option>
                    <option value="monthly" {{ $filter == 'monthly' ? 'selected' : '' }}>📆 This Year</option>
                    <option value="last6months" {{ $filter == 'last6months' ? 'selected' : '' }}>📆 Last 6 Months</option>
                </select>
            </div>
        </form>
    </div>
</div>

<!-- Charts Grid -->
<div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
    <!-- Payment Transactions Chart -->
    <div class="relative group">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-3xl blur opacity-30 group-hover:opacity-50 transition duration-300"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden">
            <!-- Chart Header -->
            <div class="relative bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 4 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-white">Payment Transactions</h3>
                            <p class="text-xs text-blue-100 font-semibold">Revenue analysis by period</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-4 py-1.5 bg-white/20 backdrop-blur-xl text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chart Area -->
            <div class="p-6 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
                <div id="payment-chart" class="w-full" style="min-height: 320px;"></div>
            </div>

            <!-- Stats Summary -->
            <div class="px-6 pb-6 pt-4 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative overflow-hidden bg-white dark:bg-gray-700 rounded-2xl p-5 shadow-lg border-2 border-green-200 dark:border-green-800 group hover:scale-105 transition-all duration-300">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-green-500/10 rounded-full blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full shadow-lg animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Completed</span>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white">৳{{ number_format(array_sum($paymentComplete), 0) }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">Total Successful</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden bg-white dark:bg-gray-700 rounded-2xl p-5 shadow-lg border-2 border-red-200 dark:border-red-800 group hover:scale-105 transition-all duration-300">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full shadow-lg"></div>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Rejected</span>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white">৳{{ number_format(array_sum($paymentRejected), 0) }}</p>
                            <p class="text-xs text-red-600 dark:text-red-400 font-semibold mt-1">Total Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MFS Transactions Chart -->
    <div class="relative group">
        <div class="absolute -inset-0.5 bg-gradient-to-r from-green-500 to-emerald-500 rounded-3xl blur opacity-30 group-hover:opacity-50 transition duration-300"></div>
        <div class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl overflow-hidden">
            <!-- Chart Header -->
            <div class="relative bg-gradient-to-r from-green-500 to-emerald-500 px-6 py-5">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 rounded-xl bg-white/20 backdrop-blur-xl flex items-center justify-center shadow-lg">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-extrabold text-white">MFS Transactions</h3>
                            <p class="text-xs text-green-100 font-semibold">Mobile financial services</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="px-4 py-1.5 bg-white/20 backdrop-blur-xl text-white text-xs font-bold rounded-full shadow-lg flex items-center">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-pulse"></span>
                            Active
                        </span>
                    </div>
                </div>
            </div>

            <!-- Chart Area -->
            <div class="p-6 bg-gradient-to-br from-gray-50 to-green-50 dark:from-gray-900 dark:to-gray-800">
                <div id="mfs-chart" class="w-full" style="min-height: 320px;"></div>
            </div>

            <!-- Stats Summary -->
            <div class="px-6 pb-6 pt-4 bg-gradient-to-br from-gray-50 to-green-50 dark:from-gray-900 dark:to-gray-800">
                <div class="grid grid-cols-2 gap-4">
                    <div class="relative overflow-hidden bg-white dark:bg-gray-700 rounded-2xl p-5 shadow-lg border-2 border-green-200 dark:border-green-800 group hover:scale-105 transition-all duration-300">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-green-500/10 rounded-full blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-3 h-3 bg-green-500 rounded-full shadow-lg animate-pulse"></div>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Completed</span>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white">৳{{ number_format(array_sum($mfsComplete), 0) }}</p>
                            <p class="text-xs text-green-600 dark:text-green-400 font-semibold mt-1">Total Successful</p>
                        </div>
                    </div>
                    <div class="relative overflow-hidden bg-white dark:bg-gray-700 rounded-2xl p-5 shadow-lg border-2 border-red-200 dark:border-red-800 group hover:scale-105 transition-all duration-300">
                        <div class="absolute top-0 right-0 -mr-8 -mt-8 w-24 h-24 bg-red-500/10 rounded-full blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="w-3 h-3 bg-red-500 rounded-full shadow-lg"></div>
                                <span class="text-xs font-bold text-gray-600 dark:text-gray-300 uppercase tracking-wide">Rejected</span>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-900 dark:text-white">৳{{ number_format(array_sum($mfsRejected), 0) }}</p>
                            <p class="text-xs text-red-600 dark:text-red-400 font-semibold mt-1">Total Failed</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Detect Tailwind dark mode
    const isDarkMode = () => document.documentElement.classList.contains('dark');
    
    function getChartColors(isDark) {
        return {
            textColor: isDark ? '#e5e7eb' : '#374151',
            labelColor: isDark ? '#9ca3af' : '#6B7280',
            borderColor: isDark ? '#374151' : '#E5E7EB',
            dataLabelBg: isDark ? '#1f2937' : '#fff',
            dataLabelColor: isDark ? '#fff' : '#1f2937'
        };
    }
    
    function createChart(selector, seriesData, categories, colors, isDark) {
        const chartColors = getChartColors(isDark);
        
        const options = {
            chart: { 
                height: 320,
                type: 'bar',
                fontFamily: 'Inter, system-ui, sans-serif',
                toolbar: {
                    show: true,
                    tools: {
                        download: true,
                        selection: false,
                        zoom: true,
                        zoomin: true,
                        zoomout: true,
                        pan: false,
                        reset: true
                    },
                    autoSelected: 'zoom'
                },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 1000,
                    animateGradually: {
                        enabled: true,
                        delay: 200
                    },
                    dynamicAnimation: {
                        enabled: true,
                        speed: 400
                    }
                },
                background: 'transparent',
                dropShadow: {
                    enabled: true,
                    top: 3,
                    left: 0,
                    blur: 10,
                    opacity: isDark ? 0.3 : 0.1
                }
            },
            series: seriesData,
            plotOptions: {
                bar: {
                    borderRadius: 12,
                    columnWidth: '65%',
                    borderRadiusApplication: 'end',
                    borderRadiusWhenStacked: 'all',
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            dataLabels: { 
                enabled: true,
                formatter: val => val > 0 ? '৳' + (val/1000).toFixed(0) + 'k' : '',
                offsetY: -25,
                style: {
                    fontSize: '11px',
                    fontWeight: 900,
                    colors: [chartColors.dataLabelColor]
                },
                background: {
                    enabled: true,
                    foreColor: chartColors.dataLabelBg,
                    padding: 8,
                    borderRadius: 8,
                    borderWidth: 0,
                    opacity: 0.95,
                    dropShadow: {
                        enabled: true,
                        top: 2,
                        left: 2,
                        blur: 4,
                        opacity: 0.3
                    }
                }
            },
            stroke: {
                show: true,
                width: 2,
                colors: ['transparent']
            },
            xaxis: { 
                categories: categories,
                labels: {
                    style: {
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: chartColors.labelColor
                    },
                    rotate: -45,
                    rotateAlways: false
                },
                axisBorder: {
                    show: true,
                    color: chartColors.borderColor,
                    height: 2
                },
                axisTicks: {
                    show: true,
                    color: chartColors.borderColor,
                    height: 6
                }
            },
            yaxis: { 
                title: { 
                    text: 'Amount (৳)',
                    style: {
                        fontSize: '14px',
                        fontWeight: 800,
                        color: chartColors.textColor
                    }
                },
                labels: {
                    formatter: val => '৳' + (val/1000).toFixed(0) + 'k',
                    style: {
                        fontSize: '12px',
                        fontWeight: 700,
                        colors: chartColors.labelColor
                    }
                }
            },
            grid: { 
                borderColor: chartColors.borderColor,
                strokeDashArray: 4,
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
                    top: -20,
                    right: 20,
                    bottom: 0,
                    left: 15
                }
            },
            legend: { 
                show: true,
                position: 'top',
                horizontalAlign: 'right',
                fontSize: '13px',
                fontWeight: 800,
                labels: {
                    colors: chartColors.textColor
                },
                markers: {
                    width: 16,
                    height: 16,
                    radius: 4,
                    offsetX: -3
                },
                itemMargin: {
                    horizontal: 20,
                    vertical: 10
                }
            },
            colors: colors,
            fill: {
                type: 'gradient',
                gradient: {
                    shade: isDark ? 'dark' : 'light',
                    type: 'vertical',
                    shadeIntensity: 0.5,
                    gradientToColors: colors.map(c => c + (isDark ? 'DD' : 'CC')),
                    inverseColors: false,
                    opacityFrom: 0.95,
                    opacityTo: 0.85,
                    stops: [0, 100]
                }
            },
            tooltip: {
                enabled: true,
                shared: true,
                intersect: false,
                theme: isDark ? 'dark' : 'light',
                style: {
                    fontSize: '13px',
                    fontFamily: 'Inter, system-ui, sans-serif'
                },
                y: {
                    formatter: val => '৳' + val.toLocaleString('en-IN', {maximumFractionDigits: 0})
                },
                marker: {
                    show: true
                },
                x: {
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
                            columnWidth: '80%'
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    xaxis: {
                        labels: {
                            rotate: -45
                        }
                    }
                }
            }]
        };
        
        return new ApexCharts(document.querySelector(selector), options);
    }

    // Initialize charts
    const paymentChart = createChart(
        "#payment-chart",
        [
            { name: 'Completed', data: @json($paymentComplete) },
            { name: 'Rejected', data: @json($paymentRejected) }
        ],
        @json($categories),
        ['#3B82F6', '#EF4444'],
        isDarkMode()
    );
    
    const mfsChart = createChart(
        "#mfs-chart",
        [
            { name: 'Completed', data: @json($mfsComplete) },
            { name: 'Rejected', data: @json($mfsRejected) }
        ],
        @json($categories),
        ['#10B981', '#EF4444'],
        isDarkMode()
    );

    paymentChart.render();
    mfsChart.render();

    // Watch for dark mode changes and update charts
    const observer = new MutationObserver((mutations) => {
        mutations.forEach((mutation) => {
            if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                const isDark = isDarkMode();
                
                // Update payment chart
                paymentChart.updateOptions({
                    ...getChartColors(isDark),
                    theme: { mode: isDark ? 'dark' : 'light' },
                    tooltip: { theme: isDark ? 'dark' : 'light' }
                });
                
                // Update MFS chart
                mfsChart.updateOptions({
                    ...getChartColors(isDark),
                    theme: { mode: isDark ? 'dark' : 'light' },
                    tooltip: { theme: isDark ? 'dark' : 'light' }
                });
            }
        });
    });

    observer.observe(document.documentElement, {
        attributes: true,
        attributeFilter: ['class']
    });
});
</script>
