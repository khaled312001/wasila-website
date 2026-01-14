@extends('admin.layouts.app')

@section('title', 'رسائل العملاء')
@section('page-title', 'رسائل العملاء')

@push('styles')
<style>
    .message-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    
    .message-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(8, 120, 139, 0.15);
        border-color: rgba(8, 120, 139, 0.3);
    }
    
    .message-card.unread {
        background: linear-gradient(135deg, rgba(8, 120, 139, 0.05) 0%, rgba(60, 166, 180, 0.05) 100%);
        border-left: 4px solid #08788B;
    }
    
    .message-card.customer-message {
        border-left-color: #3b82f6;
    }
    
    .message-card.admin-message {
        border-left-color: #10b981;
    }
    
    .message-avatar {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 700;
        font-size: 1.125rem;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .message-avatar.customer {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    }
    
    .message-avatar.admin {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    
    .status-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .status-read {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-unread {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
        color: white;
    }
    
    .sender-badge {
        padding: 0.375rem 0.875rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
    }
    
    .sender-customer {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .sender-admin {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .action-btn {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
        color: white;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, #065a6b 0%, #2a8a96 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .btn-reply {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .btn-reply:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-2xl shadow-lg p-6" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(8, 120, 139, 0.1);">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: #025469;">رسائل العملاء</h1>
                <p class="text-lg" style="color: #64748b;">إدارة رسائل العملاء المتعلقة بالطلبات</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="px-4 py-2 rounded-full text-sm font-semibold" style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%); color: white; box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);">
                        <i class="fas fa-comments ml-2"></i>
                        إجمالي الرسائل: {{ $messages->total() }}
                    </div>
                    @php
                        $unreadCount = $messages->where('is_read', false)->where('sender_type', 'customer')->count();
                    @endphp
                    @if($unreadCount > 0)
                    <div class="px-4 py-2 rounded-full text-sm font-semibold" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);">
                        <i class="fas fa-bell ml-2"></i>
                        غير مقروءة: {{ $unreadCount }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Messages List -->
    <div class="space-y-4">
        @if($messages->count() > 0)
        @foreach($messages as $message)
        <div class="message-card {{ !$message->is_read && $message->sender_type === 'customer' ? 'unread' : '' }} {{ $message->sender_type === 'customer' ? 'customer-message' : 'admin-message' }}">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Avatar & Sender Info -->
                <div class="flex items-center gap-4 flex-1">
                    <div class="message-avatar {{ $message->sender_type }}">
                        @if($message->sender_type === 'customer')
                            {{ $message->customer ? mb_substr($message->customer->name, 0, 1, 'UTF-8') : 'ع' }}
                        @else
                            {{ $message->admin ? mb_substr($message->admin->name, 0, 1, 'UTF-8') : 'إ' }}
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1 flex-wrap">
                            <h3 class="text-lg font-bold" style="color: #1e293b;">
                                @if($message->sender_type === 'customer')
                                    {{ $message->customer ? $message->customer->name : 'عميل محذوف' }}
                                @else
                                    {{ $message->admin ? $message->admin->name : 'الإدارة' }}
                                @endif
                            </h3>
                            <span class="sender-badge sender-{{ $message->sender_type }}">
                                <i class="fas {{ $message->sender_type === 'customer' ? 'fa-user' : 'fa-user-shield' }}"></i>
                                {{ $message->sender_type === 'customer' ? 'عميل' : 'إدارة' }}
                            </span>
                            @if(!$message->is_read && $message->sender_type === 'customer')
                            <span class="w-3 h-3 rounded-full animate-pulse" style="background: #08788B;"></span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 flex-wrap">
                            @if($message->customer)
                            <div class="flex items-center gap-1">
                                <i class="fas fa-envelope text-sm" style="color: #64748b;"></i>
                                <span class="text-sm" style="color: #475569;">{{ $message->customer->email }}</span>
                            </div>
                            @endif
                            @if($message->order)
                            <a href="{{ route('admin.orders.show', $message->order) }}" class="flex items-center gap-1 hover:underline">
                                <i class="fas fa-shopping-cart text-sm" style="color: #64748b;"></i>
                                <span class="text-sm" style="color: #475569;">طلب #{{ $message->order->order_number }}</span>
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Status & Actions -->
                <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                    <div class="flex flex-col gap-2">
                        @if($message->sender_type === 'customer')
                        <span class="status-badge {{ $message->is_read ? 'status-read' : 'status-unread' }}">
                            <i class="fas {{ $message->is_read ? 'fa-check-circle' : 'fa-envelope' }}"></i>
                            {{ $message->is_read ? 'مقروءة' : 'غير مقروءة' }}
                        </span>
                        @endif
                        <div class="text-xs" style="color: #64748b;">
                            <i class="fas fa-clock ml-1"></i>
                            {{ $message->created_at->diffForHumans() }}
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 flex-wrap">
                        @if($message->sender_type === 'customer' && $message->customer)
                        <a href="{{ route('admin.customers.messages', $message->customer) }}" 
                           class="action-btn btn-view">
                            <i class="fas fa-comments ml-1"></i>
                            المحادثة
                        </a>
                        <a href="{{ route('admin.customers.messages', $message->customer) }}" 
                           class="action-btn btn-reply">
                            <i class="fas fa-reply ml-1"></i>
                            رد
                        </a>
                        @elseif($message->customer)
                        <a href="{{ route('admin.customers.messages', $message->customer) }}" 
                           class="action-btn btn-view">
                            <i class="fas fa-eye ml-1"></i>
                            عرض
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            
            <!-- Message Preview -->
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(8, 120, 139, 0.1);">
                @if($message->message)
                <p class="text-sm leading-relaxed mb-2" style="color: #475569;">
                    {{ Str::limit($message->message, 200) }}
                </p>
                @endif
                @if($message->file_path)
                <div class="flex items-center gap-2 mt-2">
                    <i class="fas {{ $message->isImage() ? 'fa-image' : ($message->isVideo() ? 'fa-video' : ($message->isAudio() ? 'fa-music' : 'fa-file')) }} text-sm" style="color: #08788B;"></i>
                    <span class="text-sm" style="color: #64748b;">{{ $message->file_name }}</span>
                    @if($message->file_size)
                    <span class="text-xs" style="color: #94a3b8;">({{ $message->formatted_file_size }})</span>
                    @endif
                </div>
                @endif
            </div>
        </div>
        @endforeach

        <!-- Pagination -->
        <div class="mt-6 flex justify-center">
            <div class="rounded-lg p-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(8, 120, 139, 0.1);">
                {{ $messages->links() }}
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-16 rounded-2xl" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(8, 120, 139, 0.1);">
            <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center" style="background: linear-gradient(135deg, rgba(8, 120, 139, 0.1) 0%, rgba(60, 166, 180, 0.1) 100%);">
                <i class="fas fa-comments text-4xl" style="color: #08788B;"></i>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color: #1e293b;">لا توجد رسائل</h3>
            <p class="text-base" style="color: #64748b;">لم يتم إرسال أي رسائل من العملاء بعد.</p>
        </div>
        @endif
    </div>
</div>
@endsection
