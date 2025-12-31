<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $customer = auth('customer')->user();
        
        $messages = $customer->messages()
            ->with(['admin', 'order'])
            ->latest()
            ->paginate(20);

        // Mark admin messages as read
        $customer->messages()
            ->where('sender_type', 'admin')
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return view('customer.messages.index', compact('messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:5000',
            'order_id' => 'nullable|exists:orders,id',
        ]);

        $customer = auth('customer')->user();

        $message = $customer->messages()->create([
            'message' => $request->message,
            'order_id' => $request->order_id,
            'sender_type' => 'customer',
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.message_sent_successfully'),
                'data' => $message->load('admin', 'order'),
            ]);
        }

        return back()->with('success', __('messages.message_sent_successfully'));
    }
}

