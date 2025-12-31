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
        $orders = Order::with(['customer', 'service'])
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->from_date, fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->to_date, fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->orderBy('created_at', 'desc')
            ->get();
        
        $pdf = Pdf::loadView('admin.reports.orders-pdf', compact('orders'))
            ->setPaper('a4', 'landscape')
            ->setOption('enable-local-file-access', true);
        
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
            ->setOption('enable-local-file-access', true);
        
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
}
