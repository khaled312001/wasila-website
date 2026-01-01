<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;

class AnalyticsController extends Controller
{
    public function index()
    {
        // Get date range from request or default to last 30 days
        $dateRange = request('date_range', '30');
        $startDate = now()->subDays($dateRange)->startOfDay();
        $endDate = now()->endOfDay();

        // Overall Statistics
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_users' => Order::distinct('customer_email')->count('customer_email'),
            'total_services' => Service::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        // Revenue by month for the last 12 months
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Orders by status
        $ordersByStatus = Order::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get();

        // Top services by revenue
        $topServices = Service::withCount('orderItems')
            ->withSum('orderItems', 'total_price')
            ->orderBy('order_items_sum_total_price', 'desc')
            ->limit(10)
            ->get();

        // Daily orders for the selected period
        $dailyOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(CASE WHEN payment_status = "paid" THEN total_amount ELSE 0 END) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Payment methods statistics
        $paymentMethods = Order::where('payment_status', 'paid')
            ->whereNotNull('payment_method')
            ->select('payment_method', DB::raw('count(*) as count'), DB::raw('sum(total_amount) as total'))
            ->groupBy('payment_method')
            ->get();

        // Customer acquisition over time (using orders instead of users)
        $customerAcquisition = Order::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT customer_email) as count')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.analytics.index', compact(
            'stats',
            'monthlyRevenue',
            'ordersByStatus',
            'topServices',
            'dailyOrders',
            'paymentMethods',
            'customerAcquisition',
            'dateRange'
        ));
    }

    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');
        $dateRange = $request->get('date_range', '30');
        $startDate = now()->subDays($dateRange)->startOfDay();
        $endDate = now()->endOfDay();

        if ($format === 'excel') {
            return $this->exportToExcel($dateRange, $startDate, $endDate);
        } elseif ($format === 'pdf') {
            return $this->exportToPDF($dateRange, $startDate, $endDate);
        }

        return back()->with('error', 'تنسيق التصدير غير مدعوم');
    }
    
    private function exportToExcel($dateRange, $startDate, $endDate)
    {
        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'),
            'total_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'orders_by_status' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'top_services' => Service::withCount(['orderItems' => function($q) use ($startDate, $endDate) {
                $q->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get(),
        ];
        
        $orders = Order::with(['orderItems.service', 'customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return Excel::download(new class($stats, $orders, $dateRange) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithTitle {
            private $stats;
            private $orders;
            private $dateRange;
            
            public function __construct($stats, $orders, $dateRange) {
                $this->stats = $stats;
                $this->orders = $orders;
                $this->dateRange = $dateRange;
            }
            
            public function collection() {
                $data = collect();
                foreach ($this->orders as $order) {
                    foreach ($order->orderItems as $item) {
                        $data->push([
                            'رقم الطلب' => $order->order_number,
                            'اسم العميل' => $order->customer_name,
                            'البريد الإلكتروني' => $order->customer_email,
                            'رقم الهاتف' => $order->customer_phone,
                            'الخدمة' => $item->service->name_ar ?? '',
                            'الكمية' => $item->quantity,
                            'المبلغ' => $item->total_price,
                            'حالة الطلب' => $this->getStatusArabic($order->status),
                            'حالة الدفع' => $this->getPaymentStatusArabic($order->payment_status),
                            'تاريخ الطلب' => $order->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                }
                return $data;
            }
            
            public function headings(): array {
                return [
                    'رقم الطلب',
                    'اسم العميل',
                    'البريد الإلكتروني',
                    'رقم الهاتف',
                    'الخدمة',
                    'الكمية',
                    'المبلغ',
                    'حالة الطلب',
                    'حالة الدفع',
                    'تاريخ الطلب'
                ];
            }
            
            public function title(): string {
                return 'التقارير والإحصائيات';
            }
            
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                return [
                    1 => ['font' => ['bold' => true, 'size' => 12]],
                ];
            }
            
            private function getStatusArabic($status) {
                $statuses = [
                    'pending' => 'في الانتظار',
                    'confirmed' => 'مؤكد',
                    'processing' => 'قيد المعالجة',
                    'completed' => 'مكتمل',
                    'cancelled' => 'ملغي',
                ];
                return $statuses[$status] ?? $status;
            }
            
            private function getPaymentStatusArabic($status) {
                $statuses = [
                    'pending' => 'في الانتظار',
                    'paid' => 'مدفوع',
                    'failed' => 'فشل',
                ];
                return $statuses[$status] ?? $status;
            }
        }, 'analytics-report-' . date('Y-m-d') . '.xlsx');
    }
    
    private function exportToPDF($dateRange, $startDate, $endDate)
    {
        $stats = [
            'total_orders' => Order::whereBetween('created_at', [$startDate, $endDate])->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->whereBetween('created_at', [$startDate, $endDate])->sum('total_amount'),
            'total_customers' => Customer::whereBetween('created_at', [$startDate, $endDate])->count(),
            'orders_by_status' => Order::whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'top_services' => Service::withCount(['orderItems' => function($q) use ($startDate, $endDate) {
                $q->whereHas('order', function($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get(),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                ->whereBetween('created_at', [$startDate, $endDate])
                ->selectRaw('YEAR(created_at) as year, MONTH(created_at) as month, SUM(total_amount) as total')
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get(),
        ];
        
        $orders = Order::with(['orderItems.service', 'customer'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at', 'desc')
            ->take(100)
            ->get();
        
        return PdfService::download(
            'admin.reports.analytics-pdf',
            compact('stats', 'orders', 'dateRange', 'startDate', 'endDate'),
            'analytics-report-' . date('Y-m-d') . '.pdf',
            [
                'format' => 'A4-L',
                'orientation' => 'L',
            ]
        );
    }

    private function exportToCsv($orders)
    {
        $filename = 'orders_export_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // CSV Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم العميل',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'الخدمة',
                'الكمية',
                'المبلغ',
                'حالة الطلب',
                'حالة الدفع',
                'تاريخ الطلب'
            ]);

            foreach ($orders as $order) {
                foreach ($order->orderItems as $item) {
                    fputcsv($file, [
                        $order->order_number,
                        $order->customer_name,
                        $order->customer_email,
                        $order->customer_phone,
                        $item->service->name_ar,
                        $item->quantity,
                        $item->total_price,
                        $order->status,
                        $order->payment_status,
                        $order->created_at->format('Y-m-d H:i:s')
                    ]);
                }
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
