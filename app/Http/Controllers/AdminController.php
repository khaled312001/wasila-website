<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Order;
use App\Models\Service;
use App\Models\ContactMessage;
use App\Models\OrderDocumentation;
use App\Models\CustomerMessage;
use App\Models\Customer;
use Barryvdh\DomPDF\Facade\Pdf;
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
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::unread()->count(),
        ];
        
        $recent_orders = Order::with('orderItems.service')
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
            return back()->with('error', 'حدث خطأ أثناء رفع الفيديو: ' . $e->getMessage());
        }
    }
    
    // Delete Documentation
    public function deleteDocumentation(OrderDocumentation $documentation)
    {
        try {
            Storage::disk('public')->delete($documentation->video_path);
            if ($documentation->thumbnail_path) {
                Storage::disk('public')->delete($documentation->thumbnail_path);
            }
            $documentation->delete();
            return back()->with('success', 'تم حذف الفيديو بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف الفيديو');
        }
    }
    
    // Export Orders PDF
    public function exportOrdersPDF(Request $request)
    {
        $query = Order::with(['orderItems.service', 'customer']);
        
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
        
        $pdf = Pdf::loadView('admin.reports.orders-pdf', compact('orders'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable-local-file-access', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('fontHeightRatio', 1.1);
        
        return $pdf->download('orders-report-' . date('Y-m-d') . '.pdf');
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
        
        $pdf = Pdf::loadView('admin.reports.statistics-pdf', compact('stats'))
            ->setPaper('a4')
            ->setOption('enable-local-file-access', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('fontHeightRatio', 1.1);
        
        return $pdf->download('statistics-report-' . date('Y-m-d') . '.pdf');
    }
    
    // Customer Messages Management
    public function customerMessages()
    {
        $messages = CustomerMessage::with(['customer', 'order', 'admin'])
            ->latest()
            ->paginate(20);
        
        return view('admin.customer-messages.index', compact('messages'));
    }
    
    // Reply to Customer
    public function replyToCustomer(Request $request, CustomerMessage $message)
    {
        $request->validate([
            'reply' => 'required|string|max:5000',
        ]);
        
        CustomerMessage::create([
            'customer_id' => $message->customer_id,
            'order_id' => $message->order_id,
            'message' => $request->reply,
            'sender_type' => 'admin',
            'admin_id' => Auth::id(),
        ]);
        
        return back()->with('success', 'تم إرسال الرد بنجاح');
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
        $customer->load(['orders.orderItems.service', 'messages.admin']);
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'total_spent' => $customer->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'completed')->count(),
        ];
        
        return view('admin.customers.show', compact('customer', 'stats'));
    }
    
    public function customersOrders(Customer $customer)
    {
        $orders = $customer->orders()->with('orderItems.service')->orderBy('created_at', 'desc')->paginate(20);
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
        
        $pdf = Pdf::loadView('admin.reports.customers-pdf', compact('customers'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable-local-file-access', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('fontHeightRatio', 1.1);
        
        return $pdf->download('customers-report-' . date('Y-m-d') . '.pdf');
    }
}
