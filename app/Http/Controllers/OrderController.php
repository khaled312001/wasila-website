<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MyFatoorah\LaravelPackage\MyFatoorah;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Get service data from URL parameters
        $serviceId = $request->get('service_id');
        $serviceName = $request->get('service_name');
        $servicePrice = $request->get('service_price');
        $serviceDescription = $request->get('service_description');
        
        // If no service data provided, redirect to services
        if (!$serviceId || !$serviceName || !$servicePrice) {
            return redirect()->route('services')
                ->with('error', app()->getLocale() === 'ar' ? 'يرجى اختيار خدمة أولاً' : 'Please select a service first');
        }
        
        return view('orders.checkout', compact('serviceId', 'serviceName', 'servicePrice', 'serviceDescription'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'quantity' => 'required|integer|min:1',
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string',
        ]);
        
        try {
            DB::beginTransaction();
            
            $service = Service::findOrFail($request->service_id);
            $totalAmount = $service->price * $request->quantity;
            
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'customer_address' => $request->customer_address,
                'total_amount' => $totalAmount,
                'status' => 'pending',
                'payment_status' => 'pending',
            ]);
            
            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => $request->quantity,
                'unit_price' => $service->price,
                'total_price' => $totalAmount,
            ]);
            
            DB::commit();
            
            // إنشاء جلسة دفع مع MyFatoorah
            $paymentData = [
                'CustomerName' => $request->customer_name,
                'CustomerEmail' => $request->customer_email,
                'CustomerMobile' => $request->customer_phone,
                'InvoiceValue' => $totalAmount,
                'DisplayCurrencyIso' => SettingsHelper::get('myfatoorah_currency', 'SAR'),
                'MobileCountryCode' => '+966',
                'CustomerAddress' => [
                    'Address' => $request->customer_address,
                    'City' => 'الرياض',
                    'Country' => 'SA'
                ],
                'InvoiceItems' => [
                    [
                        'ItemName' => $service->name_ar,
                        'Quantity' => $request->quantity,
                        'UnitPrice' => $service->price,
                        'Weight' => 0,
                        'Width' => 0,
                        'Height' => 0,
                        'Depth' => 0
                    ]
                ],
                'CallBackUrl' => route('payment.callback'),
                'ErrorUrl' => route('payment.error'),
                'Language' => 'ar',
                'CustomerReference' => $order->order_number,
                'UserDefinedField' => $order->id,
                'InvoiceDisplayValue' => $totalAmount,
                'InvoiceDescription' => 'طلب خدمة من وسيلة الخيرية - ' . $service->name_ar
            ];
            
            try {
                $apiKey = SettingsHelper::get('myfatoorah_api_key');
                $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
                
                if (!$apiKey) {
                    throw new \Exception('يرجى إعداد مفتاح API لبوابة الدفع أولاً');
                }
                
                $myfatoorah = new MyFatoorah($apiKey, $isTest);
                $paymentUrl = $myfatoorah->sendPayment($paymentData);
                
                // تحديث حالة الطلب
                $order->update([
                    'payment_reference' => $paymentUrl,
                    'status' => 'payment_pending'
                ]);
                
                return redirect($paymentUrl);
            } catch (\Exception $paymentException) {
                // في حالة فشل الدفع، احتفظ بالطلب ولكن أضف ملاحظة
                $order->update([
                    'notes' => 'فشل في إنشاء جلسة الدفع: ' . $paymentException->getMessage(),
                    'status' => 'payment_failed'
                ]);
                
                return redirect()->route('orders.confirmation')
                    ->with('error', 'تم إنشاء الطلب بنجاح، ولكن حدث خطأ في معالجة الدفع. يرجى التواصل معنا لإكمال عملية الدفع.')
                    ->with('order_data', [
                        'order_number' => $order->order_number,
                        'service_name' => $service->name_ar,
                        'service_price' => $service->price,
                        'service_quantity' => $request->quantity,
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email,
                        'customer_phone' => $request->customer_phone,
                        'customer_address' => $request->customer_address,
                        'total_amount' => $totalAmount,
                        'payment_status' => 'failed'
                    ]);
            }
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'حدث خطأ أثناء إرسال الطلب: ' . $e->getMessage());
        }
    }
    
    public function show(Order $order)
    {
        return view('orders.show', compact('order'));
    }
    
    public function confirmation(Request $request)
    {
        // Get order data from URL parameters or session
        $orderData = $request->session()->get('order_confirmation');
        
        if (!$orderData) {
            // Try to get from URL parameters
            $orderData = [
                'order_number' => $request->get('order_number'),
                'service_name' => $request->get('service_name'),
                'service_price' => $request->get('service_price'),
                'service_quantity' => $request->get('service_quantity'),
                'customer_name' => $request->get('customer_name'),
                'customer_email' => $request->get('customer_email'),
                'customer_phone' => $request->get('customer_phone'),
                'customer_address' => $request->get('customer_address'),
                'total_amount' => $request->get('total_amount'),
                'payment_status' => $request->get('payment_status', 'pending')
            ];
        }
        
        return view('orders.confirmation', compact('orderData'));
    }
    
    public function paymentCallback(Request $request)
    {
        try {
            $myfatoorah = new MyFatoorah();
            $paymentId = $request->input('paymentId');
            
            if (!$paymentId) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الدفع. يرجى التواصل معنا.');
            }
            
            $paymentStatus = $myfatoorah->getPaymentStatus($paymentId);
            
            if (!$paymentStatus) {
                return redirect()->route('home')
                    ->with('error', 'فشل في التحقق من حالة الدفع. يرجى التواصل معنا.');
            }
            
            $orderId = $paymentStatus['UserDefinedField'] ?? null;
            
            if (!$orderId) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الطلب. يرجى التواصل معنا.');
            }
            
            $order = Order::find($orderId);
            
            if (!$order) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على الطلب. يرجى التواصل معنا.');
            }
            
            // تحديث حالة الطلب بناءً على حالة الدفع
            if ($paymentStatus['InvoiceStatus'] === 'Paid') {
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentStatus['PaymentMethod'] ?? 'MyFatoorah',
                    'payment_reference' => $paymentId,
                    'status' => 'confirmed',
                    'notes' => 'تم الدفع بنجاح عبر ماي فاتورة'
                ]);
                
                // Store order data in session for confirmation page
                $request->session()->put('order_confirmation', [
                    'order_number' => $order->order_number,
                    'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                    'service_price' => $order->orderItems->first()->unit_price ?? 0,
                    'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'customer_address' => $order->customer_address,
                    'total_amount' => $order->total_amount,
                    'payment_status' => 'paid',
                    'payment_method' => $order->payment_method
                ]);
                
                return redirect()->route('orders.confirmation')
                    ->with('success', 'تم الدفع بنجاح! شكراً لك على دعمك لمشروع وسيلة الخيري.');
                    
            } elseif ($paymentStatus['InvoiceStatus'] === 'Failed') {
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $paymentId,
                    'status' => 'payment_failed',
                    'notes' => 'فشل في الدفع: ' . ($paymentStatus['InvoiceError'] ?? 'Unknown error')
                ]);
                
                return redirect()->route('orders.confirmation')
                    ->with('error', 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى أو التواصل معنا.')
                    ->with('order_data', [
                        'order_number' => $order->order_number,
                        'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                        'service_price' => $order->orderItems->first()->unit_price ?? 0,
                        'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'customer_phone' => $order->customer_phone,
                        'customer_address' => $order->customer_address,
                        'total_amount' => $order->total_amount,
                        'payment_status' => 'failed'
                    ]);
                    
            } else {
                // حالة أخرى (مثل Pending)
                $order->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $paymentId,
                    'status' => 'payment_pending',
                    'notes' => 'في انتظار تأكيد الدفع'
                ]);
                
                return redirect()->route('orders.confirmation')
                    ->with('info', 'تم استلام طلبك بنجاح. في انتظار تأكيد الدفع.')
                    ->with('order_data', [
                        'order_number' => $order->order_number,
                        'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                        'service_price' => $order->orderItems->first()->unit_price ?? 0,
                        'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'customer_phone' => $order->customer_phone,
                        'customer_address' => $order->customer_address,
                        'total_amount' => $order->total_amount,
                        'payment_status' => 'pending'
                    ]);
            }
                
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'حدث خطأ في معالجة الدفع. يرجى التواصل معنا مع رقم الطلب إذا كان متوفراً.');
        }
    }
    
    public function paymentError(Request $request)
    {
        // تسجيل محاولة الدفع الفاشلة
        Log::info('Payment error occurred', [
            'request_data' => $request->all(),
            'user_agent' => $request->userAgent(),
            'ip' => $request->ip()
        ]);
        
        return redirect()->route('home')
            ->with('error', 'تم إلغاء عملية الدفع. يمكنك المحاولة مرة أخرى أو التواصل معنا للمساعدة.');
    }
    
    // Admin methods
    public function index()
    {
        $orders = Order::with('orderItems.service')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }
    
    public function adminShow(Order $order)
    {
        $order->load('orderItems.service');
        return view('admin.orders.show', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
            'payment_status' => 'required|in:pending,paid,failed',
            'notes' => 'nullable|string'
        ]);
        
        $order->update($request->only(['status', 'payment_status', 'notes']));
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', 'Order updated successfully.');
    }
}
