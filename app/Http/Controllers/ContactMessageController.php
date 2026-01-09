<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactMessage;
use App\Models\ContactMessageReply;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageMail;
use App\Helpers\SettingsHelper;

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
            
            // Send email notification
            try {
                $contactEmail = SettingsHelper::contactEmail();
                Mail::to($contactEmail)->send(new ContactMessageMail($contactMessage));
                Log::info('Contact message email sent successfully to: ' . $contactEmail);
            } catch (\Exception $emailException) {
                // Log email error but don't fail the request
                Log::error('Failed to send contact message email: ' . $emailException->getMessage());
                // You can optionally notify admin about email failure
            }
            
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
        
        // Load replies with admin
        $contactMessage->load(['replies.admin']);
        
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

    /**
     * Send reply to contact message
     */
    public function sendReply(Request $request, ContactMessage $contactMessage)
    {
        $request->validate([
            'message' => 'nullable|string|max:5000',
            'file' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,doc,docx,xls,xlsx,mp4,avi,mov,wmv,mp3,wav,ogg',
        ]);

        $data = [
            'contact_message_id' => $contactMessage->id,
            'admin_id' => Auth::id(),
            'message' => $request->message ?? '',
            'sender_type' => 'admin',
        ];

        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filePath = $file->store('contact-message-replies', 'public');
            
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

        if (empty($data['message']) && !isset($data['file_path'])) {
            return response()->json([
                'success' => false,
                'message' => 'يجب إدخال رسالة أو رفع ملف'
            ], 422);
        }

        $reply = ContactMessageReply::create($data);
        $reply->load('admin');

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال الرد بنجاح',
            'data' => [
                'id' => $reply->id,
                'message' => $reply->message,
                'file_path' => $reply->file_path,
                'file_name' => $reply->file_name,
                'file_type' => $reply->file_type,
                'mime_type' => $reply->mime_type,
                'file_url' => $reply->file_url,
                'is_image' => $reply->is_image,
                'is_video' => $reply->is_video,
                'is_audio' => $reply->is_audio,
                'sender_type' => $reply->sender_type,
                'created_at' => $reply->created_at->toISOString(),
            ]
        ]);
    }

    /**
     * Get replies for a contact message
     */
    public function getReplies(Request $request, ContactMessage $contactMessage)
    {
        $query = $contactMessage->replies()->with('admin');
        
        if ($request->last_reply_id) {
            $query->where('id', '>', $request->last_reply_id);
        }
        
        $replies = $query->orderBy('created_at', 'asc')->get();
        
        // Mark customer replies as read
        $contactMessage->replies()
            ->where('sender_type', 'customer')
            ->where('is_read', false)
            ->update(['is_read' => true, 'read_at' => now()]);
        
        // Format replies for JSON response
        $formattedReplies = $replies->map(function($reply) {
            return [
                'id' => $reply->id,
                'message' => $reply->message,
                'file_path' => $reply->file_path,
                'file_name' => $reply->file_name,
                'file_type' => $reply->file_type,
                'mime_type' => $reply->mime_type,
                'file_url' => $reply->file_url,
                'is_image' => $reply->is_image,
                'is_video' => $reply->is_video,
                'is_audio' => $reply->is_audio,
                'sender_type' => $reply->sender_type,
                'created_at' => $reply->created_at->toISOString(),
            ];
        });
        
        return response()->json([
            'success' => true,
            'replies' => $formattedReplies,
        ]);
    }
}
