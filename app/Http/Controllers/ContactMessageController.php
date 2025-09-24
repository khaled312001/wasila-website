<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ContactMessageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = ContactMessage::orderBy('created_at', 'desc')->paginate(15);
        $unreadCount = ContactMessage::unread()->count();
        
        return view('admin.contact-messages.index', compact('messages', 'unreadCount'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $contactMessage = ContactMessage::create($request->all());
            
            // Log the contact message for admin review
            Log::info('New contact message received', [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'subject' => $request->subject,
                'message' => $request->message
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.'
            ]);
        } catch (\Exception $e) {
            Log::error('Contact form error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ContactMessage $contactMessage)
    {
        // Mark message as read when viewing
        if (!$contactMessage->is_read) {
            $contactMessage->markAsRead();
        }
        
        return view('admin.contact-messages.show', compact('contactMessage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ContactMessage $contactMessage)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ContactMessage $contactMessage)
    {
        try {
            $contactMessage->delete();
            
            return redirect()->route('admin.contact-messages.index')
                ->with('success', 'تم حذف الرسالة بنجاح');
        } catch (\Exception $e) {
            Log::error('Contact message deletion error: ' . $e->getMessage());
            return redirect()->route('admin.contact-messages.index')
                ->with('error', 'حدث خطأ أثناء حذف الرسالة');
        }
    }

    /**
     * Mark message as read
     */
    public function markAsRead(ContactMessage $contactMessage)
    {
        $contactMessage->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الرسالة كمقروءة'
        ]);
    }

    /**
     * Mark message as unread
     */
    public function markAsUnread(ContactMessage $contactMessage)
    {
        $contactMessage->markAsUnread();
        
        return response()->json([
            'success' => true,
            'message' => 'تم تمييز الرسالة كغير مقروءة'
        ]);
    }

    /**
     * Get unread messages count for AJAX
     */
    public function getUnreadCount()
    {
        $count = ContactMessage::unread()->count();
        
        return response()->json([
            'count' => $count
        ]);
    }
}
