<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $customer = auth('customer')->user();
        
        $stats = [
            'total_orders' => $customer->orders()->count(),
            'pending_orders' => $customer->orders()->where('status', 'pending')->count(),
            'completed_orders' => $customer->orders()->where('status', 'completed')->count(),
            'total_spent' => $customer->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'unread_messages' => $customer->unreadMessages()->count(),
        ];

        $recentOrders = $customer->orders()
            ->with('service')
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = $customer->messages()
            ->with('admin')
            ->latest()
            ->take(5)
            ->get();

        return view('customer.dashboard', compact('customer', 'stats', 'recentOrders', 'recentMessages'));
    }
}

