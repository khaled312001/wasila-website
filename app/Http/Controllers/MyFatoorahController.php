<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Helpers\SettingsHelper;
use MyFatoorah\LaravelPackage\MyFatoorah;
use Illuminate\Support\Facades\DB;

class MyFatoorahController extends Controller
{
    public function index()
    {
        // Get comprehensive payment statistics
        $stats = [
            'total_payments' => Order::where('payment_status', 'paid')->count(),
            'pending_payments' => Order::where('payment_status', 'pending')->count(),
            'failed_payments' => Order::where('payment_status', 'failed')->count(),
            'refunded_payments' => Order::where('payment_status', 'refunded')->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'today_revenue' => Order::where('payment_status', 'paid')
                ->whereDate('created_at', today())
                ->sum('total_amount'),
            'monthly_revenue' => Order::where('payment_status', 'paid')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_amount'),
            'total_refunded' => Order::where('payment_status', 'refunded')->sum('refund_amount'),
            'average_order_value' => Order::where('payment_status', 'paid')->avg('total_amount'),
        ];

        // Get payment methods statistics
        $payment_methods_stats = Order::where('payment_status', 'paid')
            ->whereNotNull('payment_method')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Get recent payments
        $recent_payments = Order::with('orderItems.service')
            ->whereNotNull('payment_reference')
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        // Get daily revenue for the last 30 days
        $daily_revenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('admin.myfatoorah.index', compact('stats', 'recent_payments', 'payment_methods_stats', 'daily_revenue'));
    }

    public function transactions(Request $request)
    {
        $query = Order::with('orderItems.service')
            ->whereNotNull('payment_reference');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('updated_at', 'desc')->paginate(20);

        return view('admin.myfatoorah.transactions', compact('transactions'));
    }

    public function showTransaction(Order $order)
    {
        $order->load('orderItems.service');
        
        // Get payment details from MyFatoorah if available
        $paymentDetails = null;
        if ($order->payment_reference) {
            try {
                $apiKey = SettingsHelper::get('myfatoorah_api_key');
                $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
                $myfatoorah = new MyFatoorah($apiKey, $isTest);
                $paymentDetails = $myfatoorah->getPaymentStatus($order->payment_reference);
            } catch (\Exception $e) {
                // Handle error silently
            }
        }

        return view('admin.myfatoorah.show', compact('order', 'paymentDetails'));
    }

    public function refund(Request $request, Order $order)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0.01|max:' . $order->total_amount,
            'reason' => 'required|string|max:255'
        ]);

        try {
            $apiKey = SettingsHelper::get('myfatoorah_api_key');
            $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
            $myfatoorah = new MyFatoorah($apiKey, $isTest);
            
            $refundData = [
                'Key' => $order->payment_reference,
                'KeyType' => 'PaymentId',
                'RefundChargeOnCustomer' => false,
                'ServiceChargeOnCustomer' => false,
                'Amount' => $request->amount,
                'Comment' => $request->reason
            ];

            $refundResult = $myfatoorah->makeRefund($refundData);

            if ($refundResult && $refundResult['IsSuccess']) {
                // Update order status
                $order->update([
                    'status' => 'cancelled',
                    'payment_status' => 'refunded',
                    'refund_amount' => $request->amount,
                    'refund_reason' => $request->reason,
                    'refund_reference' => $refundResult['Data']['RefundReference']
                ]);

                return redirect()->route('admin.myfatoorah.show', $order)
                    ->with('success', 'تم استرداد المبلغ بنجاح');
            } else {
                return back()->with('error', 'فشل في استرداد المبلغ: ' . ($refundResult['Message'] ?? 'خطأ غير معروف'));
            }

        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء استرداد المبلغ: ' . $e->getMessage());
        }
    }

    public function testConnection()
    {
        try {
            $apiKey = SettingsHelper::get('myfatoorah_api_key');
            $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
            
            if (!$apiKey) {
                return response()->json([
                    'success' => false,
                    'message' => 'يرجى إدخال مفتاح API أولاً'
                ]);
            }
            
            $myfatoorah = new MyFatoorah($apiKey, $isTest);
            $result = $myfatoorah->getPaymentMethods();
            
            if ($result && isset($result['IsSuccess']) && $result['IsSuccess']) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم الاتصال بنجاح مع MyFatoorah',
                    'payment_methods_count' => count($result['Data']),
                    'payment_methods' => $result['Data']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'فشل في الاتصال مع MyFatoorah'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في الاتصال: ' . $e->getMessage()
            ]);
        }
    }

    public function settings()
    {
        $config = [
            'api_key' => SettingsHelper::get('myfatoorah_api_key', ''),
            'is_test' => SettingsHelper::get('myfatoorah_is_test', '1') == '1',
            'currency_iso' => SettingsHelper::get('myfatoorah_currency', 'SAR'),
        ];
        return view('admin.myfatoorah.settings', compact('config'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'api_key' => 'required|string',
            'is_test' => 'boolean',
            'currency_iso' => 'required|string|size:3',
        ]);

        // Update settings in database
        \App\Models\Setting::set('myfatoorah_api_key', $request->api_key, 'string', 'مفتاح API لبوابة الدفع MyFatoorah');
        \App\Models\Setting::set('myfatoorah_is_test', $request->has('is_test') ? '1' : '0', 'boolean', 'وضع الاختبار لبوابة الدفع');
        \App\Models\Setting::set('myfatoorah_currency', $request->currency_iso, 'string', 'العملة الافتراضية');

        // Clear settings cache
        \App\Models\Setting::clearCache();

        return redirect()->route('admin.myfatoorah.settings')
            ->with('success', 'تم تحديث إعدادات بوابة الدفع بنجاح');
    }

    public function exportTransactions(Request $request)
    {
        $query = Order::with('orderItems.service')
            ->whereNotNull('payment_reference');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $transactions = $query->orderBy('created_at', 'desc')->get();

        $filename = 'transactions_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($transactions) {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fwrite($file, "\xEF\xBB\xBF");
            
            // Headers
            fputcsv($file, [
                'رقم الطلب',
                'اسم العميل',
                'البريد الإلكتروني',
                'رقم الهاتف',
                'المبلغ',
                'طريقة الدفع',
                'حالة الدفع',
                'تاريخ الطلب',
                'تاريخ الدفع',
                'مرجع الدفع'
            ]);

            foreach ($transactions as $transaction) {
                fputcsv($file, [
                    $transaction->order_number,
                    $transaction->customer_name,
                    $transaction->customer_email,
                    $transaction->customer_phone,
                    $transaction->total_amount,
                    $transaction->payment_method,
                    $transaction->payment_status,
                    $transaction->created_at->format('Y-m-d H:i:s'),
                    $transaction->updated_at->format('Y-m-d H:i:s'),
                    $transaction->payment_reference
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function retryPayment(Order $order)
    {
        try {
            if ($order->payment_status === 'paid') {
                return back()->with('error', 'هذا الطلب مدفوع بالفعل');
            }

            // Create new payment session
            $paymentData = [
                'CustomerName' => $order->customer_name,
                'CustomerEmail' => $order->customer_email,
                'CustomerMobile' => $order->customer_phone,
                'InvoiceValue' => $order->total_amount,
                'DisplayCurrencyIso' => 'SAR',
                'MobileCountryCode' => '+966',
                'CustomerAddress' => [
                    'Address' => $order->customer_address,
                    'City' => 'الرياض',
                    'Country' => 'SA'
                ],
                'InvoiceItems' => $order->orderItems->map(function ($item) {
                    return [
                        'ItemName' => $item->service->name_ar,
                        'Quantity' => $item->quantity,
                        'UnitPrice' => $item->price,
                        'Weight' => 0,
                        'Width' => 0,
                        'Height' => 0,
                        'Depth' => 0
                    ];
                })->toArray(),
                'CallBackUrl' => route('payment.callback'),
                'ErrorUrl' => route('payment.error'),
                'Language' => 'ar',
                'CustomerReference' => $order->order_number,
                'UserDefinedField' => $order->id
            ];
            
            $apiKey = SettingsHelper::get('myfatoorah_api_key');
            $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
            $myfatoorah = new MyFatoorah($apiKey, $isTest);
            $paymentUrl = $myfatoorah->sendPayment($paymentData);
            
            return redirect($paymentUrl);
                
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إعادة محاولة الدفع: ' . $e->getMessage());
        }
    }
}