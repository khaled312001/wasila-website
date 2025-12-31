<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $customer = auth('customer')->user();
        
        $orders = $customer->orders()
            ->with(['service', 'documentation'])
            ->latest()
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $customer = auth('customer')->user();
        
        // Ensure customer owns this order
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $order->load(['service', 'documentation' => function($query) {
            $query->where('is_visible_to_customer', true);
        }]);

        return view('customer.orders.show', compact('order'));
    }

    public function invoice(Order $order)
    {
        $customer = auth('customer')->user();
        
        // Ensure customer owns this order
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $order->load('service');

        return view('customer.orders.invoice', compact('order'));
    }

    public function downloadInvoice(Order $order)
    {
        $customer = auth('customer')->user();
        
        // Ensure customer owns this order
        if ($order->customer_id !== $customer->id) {
            abort(403);
        }

        $order->load('service');

        $pdf = \PDF::loadView('customer.orders.invoice-pdf', compact('order'))
            ->setPaper('a4')
            ->setOption('enable-local-file-access', true)
            ->setOption('defaultFont', 'DejaVu Sans')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('fontHeightRatio', 1.1);
        
        return $pdf->download('invoice-' . $order->order_number . '.pdf');
    }
}

