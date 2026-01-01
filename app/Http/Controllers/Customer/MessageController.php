<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\CustomerMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
            'message' => 'nullable|string|max:5000',
            'order_id' => 'nullable|exists:orders,id',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,mp3,wav,ogg',
        ]);

        $customer = auth('customer')->user();

        $data = [
            'message' => $request->message ?? '',
            'order_id' => $request->order_id,
            'sender_type' => 'customer',
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
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
        }

        $message = $customer->messages()->create($data);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => __('messages.message_sent_successfully'),
                'data' => $message->load('admin', 'order'),
            ]);
        }

        return back()->with('success', __('messages.message_sent_successfully'));
    }

    public function getMessages(Request $request)
    {
        $customer = auth('customer')->user();
        
        $query = $customer->messages()->with(['admin', 'order']);
        
        if ($request->order_id) {
            $query->where('order_id', $request->order_id);
        }
        
        if ($request->last_message_id) {
            $query->where('id', '>', $request->last_message_id);
        }
        
        $messages = $query->latest()->limit(50)->get()->reverse();
        
        return response()->json([
            'success' => true,
            'messages' => $messages,
        ]);
    }
}

