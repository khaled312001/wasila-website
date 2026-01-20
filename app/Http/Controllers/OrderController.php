<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use App\Mail\OrderCreatedMail;
use App\Http\Controllers\MyFatoorahController;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Check if customer is logged in - REQUIRED for checkout
        if (!auth('customer')->check()) {
            // Store checkout URL in session for redirect after login
            session(['checkout_redirect' => $request->fullUrl()]);
            
            return redirect()->route('customer.login')
                ->with('error', app()->getLocale() === 'ar' 
                    ? 'يجب تسجيل الدخول قبل إكمال الطلب' 
                    : 'You must login before completing the order');
        }
        
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
        
        // Get customer (guaranteed to be logged in at this point)
        $customer = auth('customer')->user();
        
        return view('orders.checkout', compact('serviceId', 'serviceName', 'servicePrice', 'serviceDescription', 'customer'));
    }
    
    public function store(Request $request)
    {
        // Check if customer is logged in
        if (!auth('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' 
                    ? 'يرجى تسجيل الدخول أولاً' 
                    : 'Please login first'
            ], 401);
        }
        
        $customer = auth('customer')->user();
        
        try {
            $request->validate([
                'service_id' => 'required|exists:services,id',
                'quantity' => 'required|integer|min:1|max:100',
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_address' => 'nullable|string',
                'payment_method' => 'required|in:card,bank,cod,myfatoorah',
                // Credit card fields (optional)
                'card_number' => 'nullable|string|max:20',
                'cardholder_name' => 'nullable|string|max:255',
                'expiry_date' => 'nullable|string|max:10',
                'cvv' => 'nullable|string|max:4',
                // Bank transfer fields (optional)
                'bank_name' => 'nullable|string|max:255',
                'account_number' => 'nullable|string|max:50',
                'transfer_reference' => 'nullable|string|max:50',
                // COD fields (optional)
                'delivery_time' => 'nullable|string|max:50',
                'delivery_notes' => 'nullable|string|max:500'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => app()->getLocale() === 'ar' ? 'يرجى التحقق من البيانات المدخلة' : 'Please check the entered data',
                'errors' => $e->errors()
            ], 422);
        }
        
        try {
            DB::beginTransaction();
            
            $service = Service::findOrFail($request->service_id);
            $totalAmount = $service->price * $request->quantity;
            
            // تحديد طريقة الدفع
            $paymentMethod = '';
            switch($request->payment_method) {
                case 'card':
                    $paymentMethod = 'Credit/Debit Card';
                    break;
                case 'bank':
                    $paymentMethod = 'Bank Transfer';
                    break;
                case 'cod':
                    $paymentMethod = 'Cash on Delivery';
                    break;
                case 'myfatoorah':
                    $paymentMethod = 'MyFatoorah';
                    break;
            }
            
            // جمع معلومات الدفع الإضافية
            $paymentDetails = [];
            if ($request->payment_method === 'card') {
                $paymentDetails = [
                    'card_number' => $request->card_number,
                    'cardholder_name' => $request->cardholder_name,
                    'expiry_date' => $request->expiry_date,
                    'cvv' => $request->cvv
                ];
            } elseif ($request->payment_method === 'bank') {
                $paymentDetails = [
                    'bank_name' => $request->bank_name,
                    'account_number' => $request->account_number,
                    'transfer_reference' => $request->transfer_reference
                ];
            } elseif ($request->payment_method === 'cod') {
                $paymentDetails = [
                    'delivery_time' => $request->delivery_time,
                    'delivery_notes' => $request->delivery_notes
                ];
            }
            
            // Normalize phone number - remove country code if exists
            $countryCode = $this->extractCountryCode($request->customer_country) ?? '+966';
            $normalizedPhone = $this->normalizePhoneNumber($request->customer_phone, $countryCode);
            
            // Determine initial status based on payment method
            // If payment is not completed, don't use 'pending' status
            // Only COD orders can be 'pending' since payment is collected on delivery
            $initialStatus = 'confirmed'; // Default to 'confirmed' for unpaid orders
            if ($paymentMethod === 'cod') {
                // For COD, use 'pending' as payment will be collected on delivery
                $initialStatus = 'pending';
            }
            // For other payment methods (card, bank, myfatoorah), use 'confirmed' 
            // with payment_status 'pending' to indicate order is confirmed but payment is pending
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $normalizedPhone,
                'customer_country' => $this->extractCountryName($request->customer_country) ?? 'السعودية',
                'country_code' => $countryCode,
                'full_phone_number' => $countryCode . $normalizedPhone,
                'customer_address' => $request->customer_address ?? $customer->address ?? '',
                'total_amount' => $totalAmount,
                'status' => $initialStatus,
                'payment_status' => 'pending',
                'payment_method' => $paymentMethod,
                'payment_details' => json_encode($paymentDetails),
            ]);
            
            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => $request->quantity,
                'unit_price' => $service->price,
                'total_price' => $totalAmount,
            ]);
            
            DB::commit();
            
            // إرسال إيميل للإدارة عند إنشاء الطلب (لطرق الدفع غير MyFatoorah)
            // لـ MyFatoorah سيتم إرسال الإيميل بعد الدفع الناجح
            if ($request->payment_method !== 'myfatoorah') {
                try {
                    $adminEmail = SettingsHelper::contactEmail();
                    Mail::to($adminEmail)->send(new OrderCreatedMail($order));
                    Log::info('Order created email sent successfully to: ' . $adminEmail);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send order created email: ' . $emailException->getMessage());
                }
            }
            
            // إذا كانت طريقة الدفع عبر MyFatoorah، توجيه مباشرة إلى صفحة الدفع في MyFatoorah
            if ($request->payment_method === 'myfatoorah') {
                return response()->json([
                    'success' => true,
                    'redirect' => route('myfatoorah.index', ['oid' => $order->id]),
                    'message' => app()->getLocale() === 'ar' ? 'تم إنشاء الطلب بنجاح! سيتم توجيهك لصفحة الدفع' : 'Order created successfully! You will be redirected to payment page'
                ]);
            }
            
            // إرجاع استجابة JSON للطلب المكتمل
            return response()->json([
                'success' => true,
                'message' => app()->getLocale() === 'ar' ? 'تم تأكيد طلبك بنجاح! سوف نتواصل معك قريباً' : 'Your order has been confirmed successfully! We will contact you soon',
                'order_number' => $order->order_number,
                'payment_method' => $paymentMethod,
                'total_amount' => $totalAmount
            ]);
                
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
        // Log session data for debugging
        Log::info('Order confirmation page accessed', [
            'has_success' => $request->session()->has('success'),
            'has_error' => $request->session()->has('error'),
            'has_info' => $request->session()->has('info'),
            'success_message' => $request->session()->get('success'),
            'error_message' => $request->session()->get('error'),
            'info_message' => $request->session()->get('info'),
            'has_order_confirmation' => $request->session()->has('order_confirmation'),
        ]);
        
        // Get order data from session (set by MyFatoorah callback)
        $orderData = $request->session()->get('order_confirmation');
        
        // If no session data, try to get from URL parameters (for backward compatibility)
        if (!$orderData) {
            $customerCountry = $request->get('customer_country');
            $orderData = [
                'order_number' => $request->get('order_number'),
                'service_name' => $request->get('service_name'),
                'service_price' => $request->get('service_price'),
                'service_quantity' => $request->get('service_quantity'),
                'customer_name' => $request->get('customer_name'),
                'customer_email' => $request->get('customer_email'),
                'customer_phone' => $request->get('customer_phone'),
                'customer_country' => $this->extractCountryName($customerCountry) ?? 'السعودية',
                'country_code' => $this->extractCountryCode($customerCountry) ?? '+966',
                'customer_address' => $request->get('customer_address', ''),
                'total_amount' => $request->get('total_amount'),
                'payment_status' => $request->get('payment_status', 'pending'),
                'payment_method' => $request->get('payment_method', 'MyFatoorah')
            ];
        }
        
        // If still no data, try to get from paymentId in URL (MyFatoorah callback)
        if (!$orderData && $request->has('paymentId')) {
            try {
                $paymentId = $request->get('paymentId');
                $apiKey = SettingsHelper::get('myfatoorah_api_key');
                $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
                
                $mfConfig = [
                    'apiKey'      => $apiKey,
                    'isTest'      => $isTest,
                    'countryCode' => SettingsHelper::get('myfatoorah_currency', 'SAU'),
                ];
                
                $mfObj = new MyFatoorahPaymentStatus($mfConfig);
                $paymentStatus = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
                
                if ($paymentStatus && isset($paymentStatus['UserDefinedField'])) {
                    $order = Order::with('orderItems.service')->find($paymentStatus['UserDefinedField']);
                    
                    if ($order) {
                        $orderData = [
                            'order_number' => $order->order_number,
                            'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                            'service_price' => $order->orderItems->first()->unit_price ?? 0,
                            'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                            'customer_name' => $order->customer_name,
                            'customer_email' => $order->customer_email,
                            'customer_phone' => $order->customer_phone,
                            'customer_address' => $order->customer_address ?? '',
                            'total_amount' => $order->total_amount,
                            'payment_status' => $paymentStatus['InvoiceStatus'] === 'Paid' ? 'paid' : ($paymentStatus['InvoiceStatus'] === 'Failed' ? 'failed' : 'pending'),
                            'payment_method' => $paymentStatus['PaymentMethod'] ?? 'MyFatoorah'
                        ];
                        
                        // Store in session for future requests
                        $request->session()->put('order_confirmation', $orderData);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Error getting order data from paymentId: ' . $e->getMessage());
            }
        }
        
        return view('orders.confirmation', compact('orderData'));
    }
    
    public function paymentCallback(Request $request)
    {
        try {
            $paymentId = $request->input('paymentId');
            
            if (!$paymentId) {
                return redirect()->route('home')
                    ->with('error', 'لم يتم العثور على معرف الدفع. يرجى التواصل معنا.');
            }
            
            $apiKey = SettingsHelper::get('myfatoorah_api_key');
            $isTest = SettingsHelper::get('myfatoorah_is_test', '1') == '1';
            
            $mfConfig = [
                'apiKey'      => $apiKey,
                'isTest'      => $isTest,
                'countryCode' => SettingsHelper::get('myfatoorah_currency', 'SAU'),
            ];
            
            $mfObj = new MyFatoorahPaymentStatus($mfConfig);
            $paymentStatus = $mfObj->getPaymentStatus($paymentId, 'PaymentId');
            
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
                // Download invoice from MyFatoorah
                $invoiceId = $paymentStatus['InvoiceId'] ?? $paymentId;
                $invoicePath = MyFatoorahController::downloadInvoiceFromMyFatoorah($invoiceId, $order);
                
                $order->update([
                    'payment_status' => 'paid',
                    'payment_method' => $paymentStatus['PaymentMethod'] ?? 'MyFatoorah',
                    'payment_reference' => $paymentId,
                    'status' => 'confirmed',
                    'notes' => 'تم الدفع بنجاح عبر ماي فاتورة',
                    'invoice_path' => $invoicePath
                ]);
                
                // إرسال إيميل للإدارة عند الدفع الناجح مع الفاتورة
                try {
                    $adminEmail = SettingsHelper::contactEmail();
                    $order = $order->fresh()->load('orderItems.service');
                    $mail = new OrderCreatedMail($order);
                    
                    // Attach invoice if available
                    if ($invoicePath && Storage::disk('public')->exists($invoicePath)) {
                        $mail->attach(
                            Storage::disk('public')->path($invoicePath),
                            [
                                'as' => 'invoice-' . $order->order_number . '.pdf',
                                'mime' => 'application/pdf',
                            ]
                        );
                    }
                    
                    Mail::to($adminEmail)->send($mail);
                    Log::info('Order paid email sent successfully to: ' . $adminEmail);
                } catch (\Exception $emailException) {
                    Log::error('Failed to send order paid email: ' . $emailException->getMessage());
                }
                
                // Store order data in session for confirmation page
                $request->session()->put('order_confirmation', [
                    'order_number' => $order->order_number,
                    'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                    'service_price' => $order->orderItems->first()->unit_price ?? 0,
                    'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                    'customer_name' => $order->customer_name,
                    'customer_email' => $order->customer_email,
                    'customer_phone' => $order->customer_phone,
                    'customer_address' => $order->customer_address ?? '',
                    'total_amount' => $order->total_amount,
                    'payment_status' => 'paid',
                    'payment_method' => $order->payment_method
                ]);
                
                // Use direct URL path to avoid route resolution issues
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('success', 'تم الدفع بنجاح! شكراً لك على دعمك لمشروع وسيلة الخيري.');
                    
            } elseif ($paymentStatus['InvoiceStatus'] === 'Failed') {
                $order->update([
                    'payment_status' => 'failed',
                    'payment_reference' => $paymentId,
                    'status' => 'cancelled',
                    'notes' => 'فشل في الدفع: ' . ($paymentStatus['InvoiceError'] ?? 'Unknown error')
                ]);
                
                // Use direct URL path to avoid route resolution issues
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('error', 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى أو التواصل معنا.')
                    ->with('order_data', [
                        'order_number' => $order->order_number,
                        'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                        'service_price' => $order->orderItems->first()->unit_price ?? 0,
                        'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'customer_phone' => $order->customer_phone,
                        'customer_address' => $order->customer_address ?? '',
                        'total_amount' => $order->total_amount,
                        'payment_status' => 'failed'
                    ]);
                    
            } else {
                // حالة أخرى (مثل Pending)
                // Use 'confirmed' status with payment_status 'pending' instead of 'payment_pending'
                $order->update([
                    'payment_status' => 'pending',
                    'payment_reference' => $paymentId,
                    'status' => 'confirmed',
                    'notes' => 'في انتظار تأكيد الدفع'
                ]);
                
                // Use direct URL path to avoid route resolution issues
                $locale = app()->getLocale();
                $confirmationUrl = ($locale === 'en') ? '/en/orders/confirmation' : '/orders/confirmation';
                
                return redirect($confirmationUrl)
                    ->with('info', 'تم استلام طلبك بنجاح. في انتظار تأكيد الدفع.')
                    ->with('order_data', [
                        'order_number' => $order->order_number,
                        'service_name' => $order->orderItems->first()->service->name_ar ?? 'Service',
                        'service_price' => $order->orderItems->first()->unit_price ?? 0,
                        'service_quantity' => $order->orderItems->first()->quantity ?? 1,
                        'customer_name' => $order->customer_name,
                        'customer_email' => $order->customer_email,
                        'customer_phone' => $order->customer_phone,
                        'customer_address' => $order->customer_address ?? '',
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
    public function index(Request $request)
    {
        try {
            // أولاً: الحصول على عدد الطلبات الإجمالي بدون أي فلترة
            $totalOrdersCount = Order::count();
            
            Log::info('Total orders in database: ' . $totalOrdersCount);
            
            // بناء الاستعلام - بدون أي فلترة افتراضية
            $query = Order::query();
            
            // التحقق من وجود أي فلتر مطبق
            $hasAnyFilter = $request->filled('order_number') || 
                           $request->filled('customer_name') || 
                           $request->filled('customer_email') || 
                           $request->filled('status') || 
                           $request->filled('payment_status') || 
                           $request->filled('payment_method') || 
                           $request->filled('date_from') || 
                           $request->filled('date_to') || 
                           $request->filled('amount_min') || 
                           $request->filled('amount_max');
            
            // إخفاء الطلبات غير المدفوعة من MyFatoorah فقط عند تطبيق أي فلتر
            // إذا لم يكن هناك أي فلتر، اظهر جميع الطلبات
            if ($hasAnyFilter) {
                $hasPaymentFilter = $request->filled('payment_method') || $request->filled('payment_status');
                if (!$hasPaymentFilter) {
                    $query->where(function($q) {
                        $q->where('payment_method', '!=', 'MyFatoorah')
                          ->orWhere(function($subQ) {
                              $subQ->where('payment_method', 'MyFatoorah')
                                   ->where('payment_status', 'paid');
                          });
                    });
                }
            }
            
            // تحميل العلاقات بشكل آمن
            $query->with(['orderItems' => function($q) {
                $q->with(['service' => function($serviceQuery) {
                    // تحميل الخدمة حتى لو كانت محذوفة
                }]);
            }]);
            
            // تطبيق الفلاتر فقط إذا تم إرسالها من المستخدم
            if ($request->filled('order_number')) {
                $query->where('order_number', 'like', '%' . $request->order_number . '%');
            }
            
            if ($request->filled('customer_name')) {
                $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
            }
            
            if ($request->filled('customer_email')) {
                $query->where('customer_email', 'like', '%' . $request->customer_email . '%');
            }
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->filled('payment_status')) {
                $query->where('payment_status', $request->payment_status);
            }
            
            if ($request->filled('payment_method')) {
                $query->where('payment_method', $request->payment_method);
            }
            
            if ($request->filled('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            
            if ($request->filled('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }
            
            if ($request->filled('amount_min')) {
                $query->where('total_amount', '>=', $request->amount_min);
            }
            
            if ($request->filled('amount_max')) {
                $query->where('total_amount', '<=', $request->amount_max);
            }
            
            // الحصول على الطلبات بدون أي فلترة افتراضية
            $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
            
            // Log for debugging
            Log::info('Admin orders index', [
                'total_orders_in_db' => $totalOrdersCount,
                'filtered_orders_count' => $orders->total(),
                'current_page' => $orders->currentPage(),
                'items_on_page' => $orders->count(),
                'filters_applied' => $request->all()
            ]);
            
            return view('admin.orders.index', compact('orders', 'totalOrdersCount'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            // محاولة الحصول على الطلبات بدون علاقات في حالة الخطأ
            try {
                $simpleOrders = Order::orderBy('created_at', 'desc')->paginate(20);
                return view('admin.orders.index', [
                    'orders' => $simpleOrders,
                    'error' => 'حدث خطأ أثناء تحميل العلاقات: ' . $e->getMessage()
                ]);
            } catch (\Exception $e2) {
                return view('admin.orders.index', [
                    'orders' => collect([])->paginate(20),
                    'error' => 'حدث خطأ أثناء تحميل الطلبات: ' . $e2->getMessage()
                ]);
            }
        }
    }
    
    public function adminShow(Order $order)
    {
        $order->load('orderItems.service');
        
        // Load documentation safely (handle if table doesn't exist)
        try {
            $order->load('documentation');
        } catch (\Exception $e) {
            // If table doesn't exist, set empty collection
            $order->setRelation('documentation', collect([]));
        }
        
        return view('admin.orders.show', compact('order'));
    }
    
    public function update(Request $request, Order $order)
    {
        // Determine which form was submitted based on the presence of customer data
        if ($request->has('customer_name')) {
            // Customer information update
            $request->validate([
                'customer_name' => 'required|string|max:255',
                'customer_email' => 'required|email|max:255',
                'customer_phone' => 'required|string|max:20',
                'customer_country' => 'nullable|string|max:255',
                'customer_address' => 'nullable|string'
            ]);

            // Normalize phone number - remove country code if exists
            $countryCode = $this->extractCountryCode($request->customer_country) ?? ($order->country_code ?? '+966');
            $normalizedPhone = $this->normalizePhoneNumber($request->customer_phone, $countryCode);

            $order->update([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $normalizedPhone,
                'customer_country' => $this->extractCountryName($request->customer_country) ?? $order->customer_country,
                'customer_address' => $request->customer_address ?? $order->customer_address,
                'country_code' => $countryCode,
                'full_phone_number' => $countryCode . $normalizedPhone,
            ]);

            return redirect()->route('admin.orders.show', $order)
                           ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث بيانات العميل بنجاح' : 'Customer information updated successfully.');
        } else {
            // Order status update
            $request->validate([
                'status' => 'required|in:pending,confirmed,processing,completed,cancelled',
                'payment_status' => 'required|in:pending,paid,failed',
                'notes' => 'nullable|string'
            ]);

            $order->update($request->only(['status', 'payment_status', 'notes']));

            return redirect()->route('admin.orders.show', $order)
                           ->with('success', app()->getLocale() === 'ar' ? 'تم تحديث حالة الطلب بنجاح' : 'Order status updated successfully.');
        }
    }

    /**
     * Seed demo orders (delete old and create new)
     */
    public function seedDemoOrders()
    {
        try {
            \Artisan::call('db:seed', ['--class' => 'OrderSeeder', '--force' => true]);
            
            $output = \Artisan::output();
            
            return redirect()->route('admin.orders.index')
                ->with('success', 'تم حذف الطلبات القديمة وإنشاء طلبات ديمو جديدة بنجاح! ' . $output);
        } catch (\Exception $e) {
            \Log::error('Error seeding demo orders: ' . $e->getMessage());
            
            return redirect()->route('admin.orders.index')
                ->with('error', 'حدث خطأ أثناء إنشاء الطلبات الديمو: ' . $e->getMessage());
        }
    }
    
    /**
     * Export orders to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Order::with('orderItems.service');
        
        // التحقق من وجود أي فلتر مطبق
        $hasAnyFilter = $request->filled('order_number') || 
                       $request->filled('customer_name') || 
                       $request->filled('customer_email') || 
                       $request->filled('status') || 
                       $request->filled('payment_status') || 
                       $request->filled('payment_method') || 
                       $request->filled('date_from') || 
                       $request->filled('date_to') || 
                       $request->filled('amount_min') || 
                       $request->filled('amount_max');
        
        // إخفاء الطلبات غير المدفوعة من MyFatoorah فقط عند تطبيق أي فلتر
        // إذا لم يكن هناك أي فلتر، اظهر جميع الطلبات
        if ($hasAnyFilter) {
            $hasPaymentFilter = $request->filled('payment_method') || $request->filled('payment_status');
            if (!$hasPaymentFilter) {
                $query->where(function($q) {
                    $q->where('payment_method', '!=', 'MyFatoorah')
                      ->orWhere(function($subQ) {
                          $subQ->where('payment_method', 'MyFatoorah')
                               ->where('payment_status', 'paid');
                      });
                });
            }
        }
        
        // Apply same filters as index
        if ($request->filled('order_number')) {
            $query->where('order_number', 'like', '%' . $request->order_number . '%');
        }
        if ($request->filled('customer_name')) {
            $query->where('customer_name', 'like', '%' . $request->customer_name . '%');
        }
        if ($request->filled('customer_email')) {
            $query->where('customer_email', 'like', '%' . $request->customer_email . '%');
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        if ($request->filled('amount_min')) {
            $query->where('total_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('total_amount', '<=', $request->amount_max);
        }
        
        $orders = $query->orderBy('created_at', 'desc')->get();
        
        return Excel::download(new class($orders) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithTitle {
            private $orders;
            
            public function __construct($orders) {
                $this->orders = $orders;
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
                            'طريقة الدفع' => $order->payment_method ?? 'غير محدد',
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
                    'طريقة الدفع',
                    'تاريخ الطلب'
                ];
            }
            
            public function title(): string {
                return 'الطلبات';
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
        }, 'orders-export-' . date('Y-m-d') . '.xlsx');
    }

    /**
     * Extract country name from country value that includes country code
     */
    private function extractCountryName($countryValue)
    {
        if (!$countryValue) return null;

        // Handle special cases
        if ($countryValue === 'أخرى') return 'أخرى';

        // Extract country name from format "+966_السعودية"
        if (strpos($countryValue, '_') !== false) {
            $parts = explode('_', $countryValue, 2);
            return $parts[1] ?? null;
        }

        return $countryValue;
    }

    /**
     * Normalize phone number by removing country code if it exists
     * Ensures country code comes before the number (not duplicated)
     */
    private function normalizePhoneNumber($phoneNumber, $countryCode)
    {
        if (!$phoneNumber) return '';

        // Remove any spaces, dashes, or parentheses
        $phoneNumber = preg_replace('/[\s\-\(\)]/', '', $phoneNumber);

        // Remove country code if it exists at the beginning
        $countryCodeWithoutPlus = ltrim($countryCode, '+');
        if (strpos($phoneNumber, $countryCode) === 0) {
            // Remove country code with +
            $phoneNumber = substr($phoneNumber, strlen($countryCode));
        } elseif (strpos($phoneNumber, $countryCodeWithoutPlus) === 0) {
            // Remove country code without +
            $phoneNumber = substr($phoneNumber, strlen($countryCodeWithoutPlus));
        }

        // Remove leading zeros if any
        $phoneNumber = ltrim($phoneNumber, '0');

        return $phoneNumber;
    }

    /**
     * Extract country code from country value that includes country code
     */
    private function extractCountryCode($countryValue)
    {
        if (!$countryValue) return null;

        // Handle special cases
        if ($countryValue === 'أخرى') return '+966'; // Default to Saudi Arabia

        // Extract country code from format "+966_السعودية"
        if (strpos($countryValue, '_') !== false) {
            $parts = explode('_', $countryValue, 2);
            return $parts[0] ?? null;
        }

        // If it's just a country name, return default code
        $defaultCodes = [
            'السعودية' => '+966',
            'الإمارات' => '+971',
            'الكويت' => '+965',
            'قطر' => '+974',
            'البحرين' => '+973',
            'عمان' => '+968',
            'الأردن' => '+962',
            'مصر' => '+20',
            'لبنان' => '+961',
            'سوريا' => '+963',
            'العراق' => '+964',
            'اليمن' => '+967',
            'السودان' => '+249',
            'ليبيا' => '+218',
            'تونس' => '+216',
            'الجزائر' => '+213',
            'المغرب' => '+212',
            'موريتانيا' => '+222',
            'فلسطين' => '+970',
            'تركيا' => '+90'
        ];

        return $defaultCodes[$countryValue] ?? '+966';
    }
    
    /**
     * Store order documentation (video/audio)
     */
    public function storeDocumentation(Request $request, Order $order)
    {
        $request->validate([
            'file' => 'required|file|mimes:mp4,avi,mov,mp3,wav|max:102400', // 100MB max
            'description' => 'nullable|string|max:500'
        ]);
        
        $file = $request->file('file');
        $fileType = str_contains($file->getMimeType(), 'video') ? 'video' : 'audio';
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('order_documentation', $fileName, 'public');
        
        \App\Models\OrderDocumentation::create([
            'order_id' => $order->id,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'description' => $request->description,
            'is_visible_to_customer' => true
        ]);
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', app()->getLocale() === 'ar' ? 'تم رفع الملف بنجاح' : 'File uploaded successfully.');
    }
    
    /**
     * Delete order documentation
     */
    public function destroyDocumentation(Order $order, \App\Models\OrderDocumentation $documentation)
    {
        // Ensure documentation belongs to this order
        if ($documentation->order_id !== $order->id) {
            abort(403);
        }
        
        // Delete file from storage
        if (\Storage::disk('public')->exists($documentation->file_path)) {
            \Storage::disk('public')->delete($documentation->file_path);
        }
        
        $documentation->delete();
        
        return redirect()->route('admin.orders.show', $order)
            ->with('success', app()->getLocale() === 'ar' ? 'تم حذف الملف بنجاح' : 'File deleted successfully.');
    }
    
    /**
     * Delete a single order
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();
            
            // Delete order items first
            $order->orderItems()->delete();
            
            // Delete order documentation if exists
            try {
                $order->documentation()->each(function($doc) {
                    if (\Storage::disk('public')->exists($doc->file_path)) {
                        \Storage::disk('public')->delete($doc->file_path);
                    }
                    $doc->delete();
                });
            } catch (\Exception $e) {
                // Documentation table might not exist, ignore
            }
            
            // Delete customer messages related to this order
            try {
                $order->customerMessages()->delete();
            } catch (\Exception $e) {
                // Customer messages might not have order_id, ignore
            }
            
            // Delete the order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('admin.orders.index')
                ->with('success', app()->getLocale() === 'ar' ? 'تم حذف الطلب بنجاح' : 'Order deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting order: ' . $e->getMessage());
            
            return redirect()->route('admin.orders.index')
                ->with('error', app()->getLocale() === 'ar' ? 'حدث خطأ أثناء حذف الطلب: ' . $e->getMessage() : 'Error deleting order: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete multiple orders
     */
    public function destroyMultiple(Request $request)
    {
        $request->validate([
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id'
        ]);
        
        try {
            DB::beginTransaction();
            
            $orderIds = $request->order_ids;
            $orders = Order::whereIn('id', $orderIds)->get();
            
            foreach ($orders as $order) {
                // Delete order items
                $order->orderItems()->delete();
                
                // Delete order documentation if exists
                try {
                    $order->documentation()->each(function($doc) {
                        if (\Storage::disk('public')->exists($doc->file_path)) {
                            \Storage::disk('public')->delete($doc->file_path);
                        }
                        $doc->delete();
                    });
                } catch (\Exception $e) {
                    // Documentation table might not exist, ignore
                }
                
                // Delete customer messages related to this order
                try {
                    $order->customerMessages()->delete();
                } catch (\Exception $e) {
                    // Customer messages might not have order_id, ignore
                }
            }
            
            // Delete all orders
            Order::whereIn('id', $orderIds)->delete();
            
            DB::commit();
            
            $count = count($orderIds);
            return redirect()->route('admin.orders.index')
                ->with('success', app()->getLocale() === 'ar' 
                    ? "تم حذف {$count} طلب بنجاح" 
                    : "Successfully deleted {$count} orders.");
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting multiple orders: ' . $e->getMessage());
            
            return redirect()->route('admin.orders.index')
                ->with('error', app()->getLocale() === 'ar' 
                    ? 'حدث خطأ أثناء حذف الطلبات: ' . $e->getMessage() 
                    : 'Error deleting orders: ' . $e->getMessage());
        }
    }
}
