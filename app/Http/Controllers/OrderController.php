<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Helpers\SettingsHelper;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use MyFatoorah\Library\MyFatoorah;
use MyFatoorah\Library\API\Payment\MyFatoorahPayment;
use MyFatoorah\Library\API\Payment\MyFatoorahPaymentStatus;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{
    public function checkout(Request $request)
    {
        // Check if customer is logged in
        if (!auth('customer')->check()) {
            session(['checkout_redirect' => $request->fullUrl()]);
            return redirect()->route('auth.google')
                ->with('info', app()->getLocale() === 'ar' 
                    ? 'يرجى تسجيل الدخول أولاً لإتمام الطلب' 
                    : 'Please login first to complete your order');
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
            
            $order = Order::create([
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $request->customer_phone,
                'customer_country' => $this->extractCountryName($request->customer_country) ?? 'السعودية',
                'country_code' => $this->extractCountryCode($request->customer_country) ?? '+966',
                'full_phone_number' => ($this->extractCountryCode($request->customer_country) ?? '+966') . $request->customer_phone,
                'customer_address' => $request->customer_address ?? $customer->address ?? '',
                'total_amount' => $totalAmount,
                'status' => 'pending',
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
            
            // إذا كانت طريقة الدفع عبر MyFatoorah، توجيه إلى صفحة الدفع
            if ($request->payment_method === 'myfatoorah') {
                return response()->json([
                    'success' => true,
                    'redirect' => route('myfatoorah.checkout', ['oid' => $order->id]),
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
        // Get order data from URL parameters or session
        $orderData = $request->session()->get('order_confirmation');
        
        if (!$orderData) {
            // Try to get from URL parameters
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
                'payment_status' => $request->get('payment_status', 'pending')
            ];
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
                    'customer_address' => $order->customer_address ?? '',
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
                        'customer_address' => $order->customer_address ?? '',
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
            $query = Order::with(['orderItems' => function($q) {
                $q->with('service');
            }]);
            
            // Advanced Filters
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
            
            $orders = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
            
            // Log for debugging
            Log::info('Admin orders index', [
                'total_orders' => $orders->total(),
                'current_page' => $orders->currentPage(),
                'filters' => $request->all()
            ]);
            
            return view('admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            Log::error('Error loading orders: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return view('admin.orders.index', [
                'orders' => collect([])->paginate(20),
                'error' => 'حدث خطأ أثناء تحميل الطلبات: ' . $e->getMessage()
            ]);
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

            $order->update($request->only([
                'customer_name',
                'customer_email',
                'customer_phone',
                'customer_country',
                'customer_address'
            ]));

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
     * Export orders to Excel
     */
    public function exportExcel(Request $request)
    {
        $query = Order::with('orderItems.service');
        
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
}
