<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Services\PdfService;

class AnalyticsController extends Controller
{
    /**
     * Reports page — advanced filters + charts.
     *
     * Filter inputs (all optional):
     *   period         daily | weekly | monthly | yearly | custom    (default: monthly)
     *   month          Y-m (used when period=monthly)
     *   from / to      Y-m-d (used when period=custom or to override)
     *   service_id     int
     *   status         pending|confirmed|processing|completed|cancelled
     *   payment_status pending|paid|failed
     *   payment_method MyFatoorah|cod|...
     */
    public function index(Request $request)
    {
        $filters = $this->resolveFilters($request);
        [$startDate, $endDate, $bucket, $bucketLabel] = $this->resolveDateRange($filters);

        // ------- Base scoped query (respects filters except date-bucketing) -------
        $base = function () use ($filters, $startDate, $endDate) {
            $q = Order::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid');
            if (!empty($filters['status']))         $q->where('status', $filters['status']);
            if (!empty($filters['payment_method'])) $q->where('payment_method', $filters['payment_method']);
            if (!empty($filters['service_id'])) {
                $q->whereHas('orderItems', function ($q2) use ($filters) {
                    $q2->where('service_id', $filters['service_id']);
                });
            }
            return $q;
        };

        // ------- KPIs ----------
        $kpis = [
            'total_orders'    => (clone $base())->count(),
            'paid_orders'     => (clone $base())->where('payment_status', 'paid')->count(),
            'pending_orders'  => (clone $base())->where('status', 'pending')->count(),
            'completed_orders'=> (clone $base())->where('status', 'completed')->count(),
            'cancelled_orders'=> (clone $base())->where('status', 'cancelled')->count(),
            'total_revenue'   => (float) (clone $base())->where('payment_status', 'paid')->sum('total_amount'),
            'avg_order_value' => 0,
            'unique_customers'=> (clone $base())->distinct('customer_email')->count('customer_email'),
            'new_customers'   => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
        ];
        if ($kpis['paid_orders'] > 0) {
            $kpis['avg_order_value'] = round($kpis['total_revenue'] / $kpis['paid_orders'], 2);
        }
        $kpis['conversion_rate'] = $kpis['total_orders'] > 0
            ? round(($kpis['paid_orders'] / $kpis['total_orders']) * 100, 1)
            : 0;

        // ------- Period comparison (vs previous equal range) ----------
        $rangeSeconds = $endDate->getTimestamp() - $startDate->getTimestamp();
        $prevEnd   = (clone $startDate)->subSecond();
        $prevStart = (clone $prevEnd)->subSeconds($rangeSeconds);
        $prevRevenue = (float) Order::whereBetween('created_at', [$prevStart, $prevEnd])
            ->where('payment_status', 'paid')->sum('total_amount');
        $prevOrders  = (int) Order::whereBetween('created_at', [$prevStart, $prevEnd])->count();

        $kpis['revenue_change_pct'] = $prevRevenue > 0
            ? round((($kpis['total_revenue'] - $prevRevenue) / $prevRevenue) * 100, 1)
            : ($kpis['total_revenue'] > 0 ? 100 : 0);
        $kpis['orders_change_pct']  = $prevOrders > 0
            ? round((($kpis['total_orders'] - $prevOrders) / $prevOrders) * 100, 1)
            : ($kpis['total_orders'] > 0 ? 100 : 0);

        // ------- Bucketed time-series ----------
        $rows = (clone $base())
            ->select(
                DB::raw($bucket['select']  . ' as bucket'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_count')
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $timeseries = $this->fillTimeSeries($rows, $startDate, $endDate, $bucket['key']);

        // ------- Status breakdown ----------
        $statusBreakdown = (clone $base())
            ->select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $paymentBreakdown = (clone $base())
            ->select('payment_status', DB::raw('COUNT(*) as count'))
            ->groupBy('payment_status')
            ->pluck('count', 'payment_status')
            ->toArray();

        $paymentMethods = (clone $base())
            ->whereNotNull('payment_method')
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('payment_method')
            ->orderByDesc('count')
            ->get();

        // ------- Top services in the filtered window ----------
        $topServices = Service::withCount(['orderItems as orders_count' => function ($q) use ($startDate, $endDate, $filters) {
                if (!empty($filters['service_id'])) {
                    $q->where('service_id', $filters['service_id']);
                }
                $q->whereHas('order', function ($q2) use ($startDate, $endDate, $filters) {
                    $q2->whereBetween('created_at', [$startDate, $endDate])
                       ->where('payment_status', 'paid');
                    if (!empty($filters['status']))         $q2->where('status', $filters['status']);
                    if (!empty($filters['payment_method'])) $q2->where('payment_method', $filters['payment_method']);
                });
            }])
            ->withSum(['orderItems as revenue' => function ($q) use ($startDate, $endDate, $filters) {
                if (!empty($filters['service_id'])) {
                    $q->where('service_id', $filters['service_id']);
                }
                $q->whereHas('order', function ($q2) use ($startDate, $endDate, $filters) {
                    $q2->whereBetween('created_at', [$startDate, $endDate])
                       ->where('payment_status', 'paid');
                    if (!empty($filters['status'])) $q2->where('status', $filters['status']);
                    if (!empty($filters['payment_method'])) $q2->where('payment_method', $filters['payment_method']);
                });
            }], 'total_price')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        // ------- Top customers ----------
        $topCustomers = (clone $base())
            ->where('payment_status', 'paid')
            ->select('customer_email', 'customer_name', DB::raw('COUNT(*) as orders'), DB::raw('SUM(total_amount) as revenue'))
            ->groupBy('customer_email', 'customer_name')
            ->orderByDesc('revenue')
            ->take(10)
            ->get();

        // ------- Recent orders in the filtered window ----------
        $recentOrders = (clone $base())
            ->with('orderItems.service')
            ->orderByDesc('created_at')
            ->take(15)
            ->get();

        // ------- Dropdown data ----------
        $services = Service::orderBy('name_ar')->get(['id', 'name_ar', 'name_en']);

        return view('admin.analytics.index', compact(
            'filters', 'startDate', 'endDate', 'bucketLabel',
            'kpis', 'timeseries',
            'statusBreakdown', 'paymentBreakdown', 'paymentMethods',
            'topServices', 'topCustomers', 'recentOrders',
            'services'
        ));
    }

    /**
     * Resolve filter inputs into a clean array (with defaults).
     */
    private function resolveFilters(Request $request): array
    {
        return [
            'period'         => $request->input('period', 'monthly'),
            'month'          => $request->input('month', now()->format('Y-m')),
            'from'           => $request->input('from'),
            'to'             => $request->input('to'),
            'service_id'     => $request->input('service_id'),
            'status'         => $request->input('status'),
            'payment_status' => $request->input('payment_status'),
            'payment_method' => $request->input('payment_method'),
        ];
    }

    /**
     * Convert period + from/to into [startDate, endDate, bucket-config, label].
     */
    private function resolveDateRange(array $filters): array
    {
        $period = $filters['period'] ?? 'monthly';
        $now = now();

        $start = null;
        $end   = $now->copy()->endOfDay();

        switch ($period) {
            case 'daily':
                // Today only — bucket by hour
                $start = $now->copy()->startOfDay();
                $bucket = ['key' => 'hour', 'select' => "DATE_FORMAT(created_at,'%Y-%m-%d %H:00')"];
                $label = 'اليوم (بالساعة)';
                break;
            case 'weekly':
                $start = $now->copy()->subDays(6)->startOfDay();
                $bucket = ['key' => 'day', 'select' => "DATE(created_at)"];
                $label = 'آخر 7 أيام (يومي)';
                break;
            case 'yearly':
                $start = $now->copy()->subMonths(11)->startOfMonth();
                $bucket = ['key' => 'month', 'select' => "DATE_FORMAT(created_at,'%Y-%m')"];
                $label = 'آخر 12 شهر (شهري)';
                break;
            case 'custom':
                $start = $filters['from'] ? Carbon::parse($filters['from'])->startOfDay() : $now->copy()->subDays(29)->startOfDay();
                $end   = $filters['to']   ? Carbon::parse($filters['to'])->endOfDay()     : $now->copy()->endOfDay();
                $days = $start->diffInDays($end);
                if ($days > 366) {
                    $bucket = ['key' => 'month', 'select' => "DATE_FORMAT(created_at,'%Y-%m')"];
                } elseif ($days > 60) {
                    $bucket = ['key' => 'week',  'select' => "DATE_FORMAT(created_at,'%x-W%v')"];
                } else {
                    $bucket = ['key' => 'day',   'select' => "DATE(created_at)"];
                }
                $label = 'مخصّص: ' . $start->format('Y-m-d') . ' → ' . $end->format('Y-m-d');
                break;
            case 'monthly':
            default:
                try {
                    $selectedMonth = !empty($filters['month'])
                        ? Carbon::createFromFormat('Y-m', $filters['month'])->startOfMonth()
                        : $now->copy()->startOfMonth();
                } catch (\Exception $e) {
                    $selectedMonth = $now->copy()->startOfMonth();
                }

                if ($selectedMonth->greaterThan($now)) {
                    $selectedMonth = $now->copy()->startOfMonth();
                }

                $start = $selectedMonth->copy()->startOfMonth();
                $end = $selectedMonth->isSameMonth($now)
                    ? $now->copy()->endOfDay()
                    : $selectedMonth->copy()->endOfMonth();
                $bucket = ['key' => 'day', 'select' => "DATE(created_at)"];
                $label = 'من أول الشهر إلى اليوم (يومي)';
                break;
        }

        return [$start, $end, $bucket, $label];
    }

    /**
     * Pad time-series so empty buckets still produce a zero point on the chart.
     */
    private function fillTimeSeries($rows, Carbon $start, Carbon $end, string $key): array
    {
        $byBucket = [];
        foreach ($rows as $r) {
            $byBucket[(string) $r->bucket] = [
                'orders'  => (int)   $r->orders_count,
                'revenue' => (float) $r->revenue,
                'paid'    => (int)   $r->paid_count,
            ];
        }

        $labels = [];
        $orders = [];
        $revenue = [];
        $cursor = $start->copy();

        switch ($key) {
            case 'hour':
                while ($cursor <= $end) {
                    $k = $cursor->format('Y-m-d H:00');
                    $labels[]  = $cursor->format('H:00');
                    $orders[]  = $byBucket[$k]['orders']  ?? 0;
                    $revenue[] = $byBucket[$k]['revenue'] ?? 0;
                    $cursor->addHour();
                }
                break;
            case 'day':
                while ($cursor <= $end) {
                    $k = $cursor->format('Y-m-d');
                    $labels[]  = $cursor->format('d M');
                    $orders[]  = $byBucket[$k]['orders']  ?? 0;
                    $revenue[] = $byBucket[$k]['revenue'] ?? 0;
                    $cursor->addDay();
                }
                break;
            case 'week':
                while ($cursor <= $end) {
                    $k = $cursor->format('o-\WW'); // ISO year-week
                    $labels[]  = 'Wk ' . $cursor->format('W');
                    $orders[]  = $byBucket[$k]['orders']  ?? 0;
                    $revenue[] = $byBucket[$k]['revenue'] ?? 0;
                    $cursor->addWeek();
                }
                break;
            case 'month':
            default:
                while ($cursor <= $end) {
                    $k = $cursor->format('Y-m');
                    $labels[]  = $cursor->format('M Y');
                    $orders[]  = $byBucket[$k]['orders']  ?? 0;
                    $revenue[] = $byBucket[$k]['revenue'] ?? 0;
                    $cursor->addMonth();
                }
                break;
        }

        return [
            'labels'  => $labels,
            'orders'  => $orders,
            'revenue' => $revenue,
        ];
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $filters = $this->resolveFilters($request);
        [$startDate, $endDate] = $this->resolveDateRange($filters);

        if ($format === 'excel') return $this->exportToExcel($startDate, $endDate, $filters);
        if ($format === 'pdf')   return $this->exportToPDF($startDate, $endDate, $filters);

        return back()->with('error', 'تنسيق التصدير غير مدعوم');
    }

    private function exportToExcel(Carbon $startDate, Carbon $endDate, array $filters)
    {
            $orders = Order::with(['orderItems.service'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->when(!empty($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['payment_method']), fn ($q) => $q->where('payment_method', $filters['payment_method']))
            ->when(!empty($filters['service_id']), function ($q) use ($filters) {
                $q->whereHas('orderItems', fn ($q2) => $q2->where('service_id', $filters['service_id']));
            })
            ->orderByDesc('created_at')
            ->get();

        return Excel::download(new class($orders) implements
            \Maatwebsite\Excel\Concerns\FromCollection,
            \Maatwebsite\Excel\Concerns\WithHeadings,
            \Maatwebsite\Excel\Concerns\WithStyles,
            \Maatwebsite\Excel\Concerns\WithTitle
        {
            private $orders;
            public function __construct($orders) { $this->orders = $orders; }

            public function collection() {
                $data = collect();
                foreach ($this->orders as $order) {
                    foreach ($order->orderItems as $item) {
                        $data->push([
                            'رقم الطلب' => $order->order_number,
                            'اسم العميل' => $order->customer_name,
                            'البريد' => $order->customer_email,
                            'الهاتف' => $order->customer_phone,
                            'الخدمة' => $item->service->name_ar ?? '',
                            'الكمية' => $item->quantity,
                            'المبلغ' => $item->total_price,
                            'حالة الطلب' => $order->status,
                            'حالة الدفع' => $order->payment_status,
                            'طريقة الدفع' => $order->payment_method,
                            'تاريخ الطلب' => $order->created_at->format('Y-m-d H:i'),
                        ]);
                    }
                }
                return $data;
            }

            public function headings(): array {
                return ['رقم الطلب','اسم العميل','البريد','الهاتف','الخدمة','الكمية','المبلغ','حالة الطلب','حالة الدفع','طريقة الدفع','تاريخ الطلب'];
            }
            public function title(): string { return 'تقرير'; }
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                return [1 => ['font' => ['bold' => true, 'size' => 12]]];
            }
        }, 'wasila-report-' . now()->format('Y-m-d-His') . '.xlsx');
    }

    private function exportToPDF(Carbon $startDate, Carbon $endDate, array $filters)
    {
        [$unusedStart, $unusedEnd, $bucket] = $this->resolveDateRange($filters);

        $base = function () use ($filters, $startDate, $endDate) {
            $q = Order::query()
                ->whereBetween('created_at', [$startDate, $endDate])
                ->where('payment_status', 'paid');
            if (!empty($filters['status']))         $q->where('status', $filters['status']);
            if (!empty($filters['payment_method'])) $q->where('payment_method', $filters['payment_method']);
            if (!empty($filters['service_id'])) {
                $q->whereHas('orderItems', function ($q2) use ($filters) {
                    $q2->where('service_id', $filters['service_id']);
                });
            }
            return $q;
        };

        $stats = [
            'total_orders'  => (clone $base())->count(),
            'paid_orders'   => (clone $base())->where('payment_status', 'paid')->count(),
            'total_revenue' => (float) (clone $base())->where('payment_status', 'paid')->sum('total_amount'),
            'total_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'unique_customers' => (clone $base())->distinct('customer_email')->count('customer_email'),
            'orders_by_status' => (clone $base())
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'payment_methods' => (clone $base())
                ->whereNotNull('payment_method')
                ->selectRaw('payment_method, count(*) as count, sum(case when payment_status = "paid" then total_amount else 0 end) as total')
                ->groupBy('payment_method')
                ->orderByDesc('count')
                ->get(),
            'top_services' => Service::withCount(['orderItems as orders_count' => function ($q) use ($startDate, $endDate, $filters) {
                    if (!empty($filters['service_id'])) {
                        $q->where('service_id', $filters['service_id']);
                    }
                    $q->whereHas('order', function ($q2) use ($startDate, $endDate, $filters) {
                        $q2->whereBetween('created_at', [$startDate, $endDate])
                           ->where('payment_status', 'paid');
                        if (!empty($filters['status']))         $q2->where('status', $filters['status']);
                        if (!empty($filters['payment_method'])) $q2->where('payment_method', $filters['payment_method']);
                    });
                }])
                ->orderByDesc('orders_count')
                ->take(10)
                ->get(),
        ];

        $rows = (clone $base())
            ->select(
                DB::raw($bucket['select'] . ' as bucket'),
                DB::raw('COUNT(*) as orders_count'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as revenue'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid_count')
            )
            ->groupBy('bucket')
            ->orderBy('bucket')
            ->get();

        $timeseries = $this->fillTimeSeries($rows, $startDate, $endDate, $bucket['key']);

        $orders = Order::with(['orderItems.service', 'customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->when(!empty($filters['status']), fn ($q) => $q->where('status', $filters['status']))
            ->when(!empty($filters['payment_method']), fn ($q) => $q->where('payment_method', $filters['payment_method']))
            ->when(!empty($filters['service_id']), function ($q) use ($filters) {
                $q->whereHas('orderItems', fn ($q2) => $q2->where('service_id', $filters['service_id']));
            })
            ->orderByDesc('created_at')
            ->take(200)
            ->get();

        return PdfService::download(
            'admin.reports.analytics-pdf',
            compact('stats', 'orders', 'startDate', 'endDate', 'filters', 'timeseries'),
            'wasila-report-' . now()->format('Y-m-d') . '.pdf',
            ['format' => 'A4-L', 'orientation' => 'L']
        );
    }
}
