<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use App\Models\Service;
use App\Models\ContactMessage;
use App\Models\OrderDocumentation;
use App\Models\CustomerMessage;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\PdfService;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }
    
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::guard('admin')->attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }
        
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
    
    public function dashboard()
    {
        $stats = [
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'completed')->count(),
            'total_services' => Service::count(),
            'active_services' => Service::where('is_active', true)->count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_messages' => CustomerMessage::count(),
            'unread_messages' => CustomerMessage::where('is_read', false)->where('sender_type', 'customer')->count(),
        ];
        
        // إخفاء الطلبات غير المدفوعة من MyFatoorah (لا تظهر للإدارة حتى يتم الدفع)
        $recent_orders = Order::with('orderItems.service')
            ->where(function($q) {
                $q->where('payment_method', '!=', 'MyFatoorah')
                  ->orWhere(function($subQ) {
                      $subQ->where('payment_method', 'MyFatoorah')
                           ->where('payment_status', 'paid');
                  });
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        // Top Services
        $topServices = Service::withCount(['orderItems as orders_count'])
            ->withSum('orderItems as total_revenue', 'total_price')
            ->orderBy('orders_count', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('stats', 'recent_orders', 'topServices'));
    }
    
    // Upload Order Documentation Video
    public function uploadDocumentation(Request $request, Order $order)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'video' => 'required|file|mimes:mp4,mov,avi,wmv|max:102400', // 100MB
        ]);
        
        try {
            $videoPath = $request->file('video')->store('documentation', 'public');
            
            // Get video duration and size
            $fileSize = $request->file('video')->getSize();
            
            // Copy file to public/storage for hosting providers that don't support symlinks
            $this->copyToPublicStorage($videoPath);
            
            $documentation = OrderDocumentation::create([
                'order_id' => $order->id,
                'title' => $request->title,
                'description' => $request->description,
                'video_path' => $videoPath,
                'file_size' => $fileSize,
                'uploaded_by' => Auth::id(),
                'is_visible_to_customer' => true,
            ]);
            
            // Send notification to customer
            if ($order->customer) {
                CustomerMessage::create([
                    'customer_id' => $order->customer_id,
                    'order_id' => $order->id,
                    'message' => "تم رفع فيديو توثيق جديد لطلبك: {$request->title}",
                    'sender_type' => 'admin',
                    'admin_id' => Auth::id(),
                ]);
            }
            
            return back()->with('success', 'تم رفع الفيديو بنجاح');
        } catch (\Exception $e) {
            Log::error('Error uploading documentation: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return back()->with('error', 'حدث خطأ أثناء رفع الفيديو: ' . $e->getMessage());
        }
    }
    
    // Delete Documentation
    public function deleteDocumentation(OrderDocumentation $documentation)
    {
        try {
            // Delete from storage/app/public
            Storage::disk('public')->delete($documentation->video_path);
            if ($documentation->thumbnail_path) {
                Storage::disk('public')->delete($documentation->thumbnail_path);
            }
            
            // Delete from public/storage
            $publicPath = public_path('storage/' . $documentation->video_path);
            if (file_exists($publicPath)) {
                unlink($publicPath);
            }
            if ($documentation->thumbnail_path) {
                $publicThumbPath = public_path('storage/' . $documentation->thumbnail_path);
                if (file_exists($publicThumbPath)) {
                    unlink($publicThumbPath);
                }
            }
            
            $documentation->delete();
            return back()->with('success', 'تم حذف الفيديو بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting documentation: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء حذف الفيديو');
        }
    }
    
    // Export Orders PDF
    public function exportOrdersPDF(Request $request)
    {
        $query = Order::with(['orderItems.service', 'customer']);
        
        // إخفاء الطلبات غير المدفوعة من MyFatoorah (لا تظهر للإدارة حتى يتم الدفع)
        // إلا إذا كان المستخدم يطبق فلتر محدد على payment_method أو payment_status
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
        
        return PdfService::download(
            'admin.reports.orders-pdf',
            compact('orders'),
            'orders-report-' . date('Y-m-d') . '.pdf',
            [
                'format' => 'A4-L',
                'orientation' => 'L',
            ]
        );
    }
    
    // Export Statistics PDF
    public function exportStatisticsPDF()
    {
        $stats = [
            'total_orders' => Order::count(),
            'total_revenue' => Order::where('payment_status', 'paid')->sum('total_amount'),
            'total_customers' => Customer::count(),
            'orders_by_status' => Order::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get(),
            'top_services' => Service::withCount('orders')
                ->orderBy('orders_count', 'desc')
                ->take(10)
                ->get(),
            'recent_orders' => Order::with('customer')
                ->latest()
                ->take(20)
                ->get(),
        ];
        
        return PdfService::download(
            'admin.reports.statistics-pdf',
            compact('stats'),
            'statistics-report-' . date('Y-m-d') . '.pdf',
            [
                'format' => 'A4',
                'orientation' => 'P',
            ]
        );
    }
    
    // Customer Messages Management
    public function customerMessages()
    {
        $messages = CustomerMessage::with(['customer', 'order', 'admin'])
            ->latest()
            ->paginate(20);
        
        // Get all customers with orders for the edit modal
        $customersWithOrders = Customer::with('orders')->get();
        
        return view('admin.customer-messages.index', compact('messages', 'customersWithOrders'));
    }
    
    // Reply to Customer
    public function replyToCustomer(Request $request, CustomerMessage $message)
    {
        try {
            $request->validate([
                'reply' => 'nullable|string|max:5000',
                'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,mp3,wav,ogg',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        }
        
        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return back()->with('error', 'غير مصرح لك بإرسال الرسالة');
            }
            
            $data = [
                'customer_id' => $message->customer_id,
                'order_id' => $message->order_id,
                'message' => $request->reply ?? '',
                'sender_type' => 'admin',
                'admin_id' => $admin->id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                try {
                    $file = $request->file('file');
                    $filePath = $file->store('customer-messages', 'public');
                    
                    // Determine file type
                    $mimeType = $file->getMimeType();
                    $fileType = 'document';
                    if (str_starts_with($mimeType, 'image/')) {
                        $fileType = 'image';
                    } elseif (str_starts_with($mimeType, 'video/')) {
                        $fileType = 'video';
                    } elseif (str_starts_with($mimeType, 'audio/')) {
                        $fileType = 'audio';
                    }

                    $data['file_path'] = $filePath;
                    $data['file_name'] = $file->getClientOriginalName();
                    $data['file_type'] = $fileType;
                    $data['file_size'] = $file->getSize();
                    $data['mime_type'] = $mimeType;
                } catch (\Exception $e) {
                    Log::error('Error uploading file in replyToCustomer: ' . $e->getMessage());
                    return back()->with('error', 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.');
                }
            }
            
            CustomerMessage::create($data);
            
            return back()->with('success', __('messages.message_sent_successfully'));
        } catch (\Exception $e) {
            Log::error('Error in replyToCustomer: ' . $e->getMessage(), [
                'message_id' => $message->id,
                'admin_id' => Auth::guard('admin')->id(),
                'trace' => $e->getTraceAsString(),
            ]);
            
            return back()->with('error', __('messages.error_sending_message'));
        }
    }

    public function getCustomerMessages(Request $request, Customer $customer)
    {
        $query = $customer->messages()->with(['admin', 'order']);
        
        if ($request->last_message_id) {
            $query->where('id', '>', $request->last_message_id);
        }
        
        $messages = $query->latest()->limit(50)->get()->reverse();
        
        // Mark as read
        $customer->messages()
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true]);
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }

    // Edit Customer Message
    public function editCustomerMessage(CustomerMessage $message)
    {
        $message->load(['customer', 'order', 'admin']);
        return view('admin.customer-messages.edit', compact('message'));
    }

    // Update Customer Message
    public function updateCustomerMessage(Request $request, CustomerMessage $message)
    {
        try {
            $request->validate([
                'message' => 'nullable|string|max:5000',
                'order_id' => 'nullable|exists:orders,id',
            ]);

            $data = [
                'message' => $request->message ?? '',
            ];

            if ($request->has('order_id')) {
                $data['order_id'] = $request->order_id;
            }

            $message->update($data);

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم تحديث الرسالة بنجاح',
                ]);
            }

            return redirect()->route('admin.customer.messages')
                ->with('success', 'تم تحديث الرسالة بنجاح');
        } catch (\Exception $e) {
            Log::error('Error updating customer message: ' . $e->getMessage());
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء تحديث الرسالة',
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء تحديث الرسالة');
        }
    }

    // Delete Customer Message
    public function destroyCustomerMessage(CustomerMessage $message)
    {
        try {
            // Delete file if exists
            if ($message->file_path && Storage::disk('public')->exists($message->file_path)) {
                Storage::disk('public')->delete($message->file_path);
            }

            $message->delete();

            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم حذف الرسالة بنجاح',
                ]);
            }

            return redirect()->route('admin.customer.messages')
                ->with('success', 'تم حذف الرسالة بنجاح');
        } catch (\Exception $e) {
            Log::error('Error deleting customer message: ' . $e->getMessage());
            
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء حذف الرسالة',
                ], 500);
            }

            return back()->with('error', 'حدث خطأ أثناء حذف الرسالة');
        }
    }

    public function sendMessageToCustomer(Request $request, Customer $customer)
    {
        try {
            $request->validate([
                'message' => 'nullable|string|max:5000',
                'order_id' => 'nullable|exists:orders,id',
                'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,mp3,wav,ogg',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('messages.error_occurred'),
                'errors' => $e->errors(),
            ], 422);
        }

        try {
            $admin = Auth::guard('admin')->user();
            
            if (!$admin) {
                return response()->json([
                    'success' => false,
                    'message' => 'غير مصرح لك بإرسال الرسالة',
                ], 401);
            }

            $data = [
                'customer_id' => $customer->id,
                'order_id' => $request->order_id,
                'message' => $request->message ?? '',
                'sender_type' => 'admin',
                'admin_id' => $admin->id,
            ];

            // Handle file upload
            if ($request->hasFile('file')) {
                try {
                    $file = $request->file('file');
                    $filePath = $file->store('customer-messages', 'public');
                    
                    // Determine file type
                    $mimeType = $file->getMimeType();
                    $fileType = 'document';
                    if (str_starts_with($mimeType, 'image/')) {
                        $fileType = 'image';
                    } elseif (str_starts_with($mimeType, 'video/')) {
                        $fileType = 'video';
                    } elseif (str_starts_with($mimeType, 'audio/')) {
                        $fileType = 'audio';
                    }

                    $data['file_path'] = $filePath;
                    $data['file_name'] = $file->getClientOriginalName();
                    $data['file_type'] = $fileType;
                    $data['file_size'] = $file->getSize();
                    $data['mime_type'] = $mimeType;
                } catch (\Exception $e) {
                    Log::error('Error uploading file in sendMessageToCustomer: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى.',
                    ], 500);
                }
            }

            $message = CustomerMessage::create($data);

            return response()->json([
                'success' => true,
                'message' => __('messages.message_sent_successfully'),
                'data' => $message->load('admin', 'order'),
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendMessageToCustomer: ' . $e->getMessage(), [
                'customer_id' => $customer->id,
                'admin_id' => Auth::guard('admin')->id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => __('messages.error_sending_message'),
            ], 500);
        }
    }
    
    // Customers Management
    public function customersIndex(Request $request)
    {
        $query = Customer::withCount(['orders', 'messages']);
        
        // Filters
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $customers = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();
        
        return view('admin.customers.index', compact('customers'));
    }
    
    public function customersShow(Customer $customer)
    {
        // إخفاء الطلبات غير المدفوعة من MyFatoorah عند تحميل الطلبات
        $customer->load(['orders' => function($query) {
            $query->where(function($q) {
                $q->where('payment_method', '!=', 'MyFatoorah')
                  ->orWhere(function($subQ) {
                      $subQ->where('payment_method', 'MyFatoorah')
                           ->where('payment_status', 'paid');
                  });
            })->with('orderItems.service');
        }, 'messages.admin']);
        
        $stats = [
            'total_orders' => $customer->orders()->where(function($q) {
                $q->where('payment_method', '!=', 'MyFatoorah')
                  ->orWhere(function($subQ) {
                      $subQ->where('payment_method', 'MyFatoorah')
                           ->where('payment_status', 'paid');
                  });
            })->count(),
            'total_spent' => $customer->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'completed')->count(),
        ];
        
        return view('admin.customers.show', compact('customer', 'stats'));
    }
    
    public function customersOrders(Customer $customer)
    {
        // إخفاء الطلبات غير المدفوعة من MyFatoorah
        $orders = $customer->orders()
            ->where(function($q) {
                $q->where('payment_method', '!=', 'MyFatoorah')
                  ->orWhere(function($subQ) {
                      $subQ->where('payment_method', 'MyFatoorah')
                           ->where('payment_status', 'paid');
                  });
            })
            ->with('orderItems.service')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.customers.orders', compact('customer', 'orders'));
    }
    
    public function customersMessages(Customer $customer)
    {
        $messages = $customer->messages()->with('admin', 'order')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.customers.messages', compact('customer', 'messages'));
    }
    
    public function exportCustomersExcel(Request $request)
    {
        $query = Customer::withCount(['orders', 'messages']);
        
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $customers = $query->orderBy('created_at', 'desc')->get();
        
        return Excel::download(new class($customers) implements \Maatwebsite\Excel\Concerns\FromCollection, \Maatwebsite\Excel\Concerns\WithHeadings, \Maatwebsite\Excel\Concerns\WithStyles, \Maatwebsite\Excel\Concerns\WithTitle {
            private $customers;
            
            public function __construct($customers) {
                $this->customers = $customers;
            }
            
            public function collection() {
                return $this->customers->map(function($customer) {
                    return [
                        'الاسم' => $customer->name,
                        'البريد الإلكتروني' => $customer->email,
                        'الهاتف' => $customer->phone ?? 'غير محدد',
                        'العنوان' => $customer->address ?? 'غير محدد',
                        'عدد الطلبات' => $customer->orders_count,
                        'عدد الرسائل' => $customer->messages_count,
                        'تاريخ التسجيل' => $customer->created_at->format('Y-m-d H:i:s'),
                    ];
                });
            }
            
            public function headings(): array {
                return [
                    'الاسم',
                    'البريد الإلكتروني',
                    'الهاتف',
                    'العنوان',
                    'عدد الطلبات',
                    'عدد الرسائل',
                    'تاريخ التسجيل'
                ];
            }
            
            public function title(): string {
                return 'العملاء';
            }
            
            public function styles(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet $sheet) {
                return [
                    1 => ['font' => ['bold' => true, 'size' => 12]],
                ];
            }
        }, 'customers-export-' . date('Y-m-d') . '.xlsx');
    }
    
    public function exportCustomersPDF(Request $request)
    {
        $query = Customer::withCount(['orders', 'messages']);
        
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%' . $request->email . '%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $customers = $query->orderBy('created_at', 'desc')->get();
        
        return PdfService::download(
            'admin.reports.customers-pdf',
            compact('customers'),
            'customers-report-' . date('Y-m-d') . '.pdf',
            [
                'format' => 'A4-L',
                'orientation' => 'L',
            ]
        );
    }
    
    /**
     * Sync all documentation files to public/storage
     */
    public function syncDocumentationFiles()
    {
        try {
            $documentations = OrderDocumentation::whereNotNull('video_path')->get();
            $syncedCount = 0;
            $errors = [];
            
            foreach ($documentations as $doc) {
                if ($this->copyToPublicStorage($doc->video_path)) {
                    $syncedCount++;
                } else {
                    $errors[] = "Failed to sync file for documentation ID {$doc->id}: {$doc->video_path}";
                }
            }
            
            return response()->json([
                'success' => true,
                'message' => "تمت مزامنة {$syncedCount} ملف بنجاح. عدد الأخطاء: " . count($errors),
                'synced_count' => $syncedCount,
                'errors' => $errors
            ]);
        } catch (\Exception $e) {
            Log::error('Error syncing documentation files: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء مزامنة الملفات: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Copy file to public/storage directory for hosting providers that don't support symlinks
     */
    private function copyToPublicStorage($filePath)
    {
        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $targetPath = public_path('storage/' . $filePath);
            
            // Check if source file exists
            if (!file_exists($sourcePath)) {
                Log::warning('Source file does not exist: ' . $sourcePath);
                return false;
            }
            
            // Create directory if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    Log::error('Failed to create directory: ' . $targetDir);
                    return false;
                }
            }
            
            // Copy file
            if (!copy($sourcePath, $targetPath)) {
                Log::error('Failed to copy file from ' . $sourcePath . ' to ' . $targetPath);
                return false;
            }
            
            // Set permissions
            chmod($targetPath, 0644);
            
            // Verify the copy was successful
            if (!file_exists($targetPath)) {
                Log::error('File copy verification failed: ' . $targetPath);
                return false;
            }
            
            Log::info('Successfully copied file to public storage: ' . $filePath);
            return true;
        } catch (\Exception $e) {
            Log::error('Exception in copyToPublicStorage: ' . $e->getMessage() . ' | File: ' . $filePath);
            return false;
        }
    }
}
