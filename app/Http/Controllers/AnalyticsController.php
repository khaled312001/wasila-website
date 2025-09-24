<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Service;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'total_users' => User::count(),
            'total_services' => Service::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
        ];

        // Revenue by month for the last 12 months
        $monthlyRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subMonths(12))
            ->select(
                DB::raw('strftime("%Y", created_at) as year'),
                DB::raw('strftime("%m", created_at) as month'),
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
                DB::raw('date(created_at) as date'),
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

        // Customer acquisition over time
        $customerAcquisition = User::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('date(created_at) as date'),
                DB::raw('COUNT(*) as count')
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
        $format = $request->get('format', 'csv');
        $dateRange = $request->get('date_range', '30');
        $startDate = now()->subDays($dateRange)->startOfDay();
        $endDate = now()->endOfDay();

        $orders = Order::with(['orderItems.service', 'user'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        if ($format === 'csv') {
            return $this->exportToCsv($orders);
        }

        return back()->with('error', 'تنسيق التصدير غير مدعوم');
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
