@extends('admin.layouts.app')

@section('title', 'إدارة الرسائل')
@section('page-title', 'إدارة الرسائل')

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
    
    .btn-mark {
        background: rgba(8, 120, 139, 0.1);
        color: #08788B;
        border: 1px solid rgba(8, 120, 139, 0.2);
    }
    
    .btn-mark:hover {
        background: rgba(8, 120, 139, 0.2);
        color: #065a6b;
    }
    
    .btn-delete {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.2);
    }
    
    .btn-delete:hover {
        background: rgba(239, 68, 68, 0.2);
        color: #dc2626;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="rounded-2xl shadow-lg p-6" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(8, 120, 139, 0.1);">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2" style="color: #025469;">إدارة الرسائل</h1>
                <p class="text-lg" style="color: #64748b;">إدارة رسائل التواصل من الموقع</p>
            </div>
            <div class="mt-4 md:mt-0">
                <div class="flex items-center gap-4 flex-wrap">
                    <div class="px-4 py-2 rounded-full text-sm font-semibold" style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%); color: white; box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);">
                        <i class="fas fa-envelope ml-2"></i>
                        إجمالي الرسائل: {{ $messages->total() }}
                    </div>
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
        <div class="message-card {{ !$message->is_read ? 'unread' : '' }}">
            <div class="flex flex-col md:flex-row md:items-center gap-4">
                <!-- Avatar & Sender Info -->
                <div class="flex items-center gap-4 flex-1">
                    <div class="message-avatar">
                        {{ mb_substr($message->name, 0, 1, 'UTF-8') }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-lg font-bold" style="color: #1e293b;">
                                {{ $message->name }}
                            </h3>
                            @if(!$message->is_read)
                            <span class="w-3 h-3 rounded-full animate-pulse" style="background: #08788B;"></span>
                            @endif
                        </div>
                        <div class="flex items-center gap-4 flex-wrap">
                            <div class="flex items-center gap-1">
                                <i class="fas fa-envelope text-sm" style="color: #64748b;"></i>
                                <span class="text-sm" style="color: #475569;">{{ $message->email }}</span>
                            </div>
                            @if($message->phone)
                            <div class="flex items-center gap-1">
                                <i class="fas fa-phone text-sm" style="color: #64748b;"></i>
                                <span class="text-sm" style="color: #475569;">{{ $message->phone }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Status & Actions -->
                <div class="flex flex-col md:flex-row items-start md:items-center gap-3">
                    <div class="flex flex-col gap-2">
                        <span class="status-badge {{ $message->is_read ? 'status-read' : 'status-unread' }}">
                            <i class="fas {{ $message->is_read ? 'fa-check-circle' : 'fa-envelope' }}"></i>
                            {{ $message->is_read ? 'مقروءة' : 'غير مقروءة' }}
                        </span>
                        <div class="text-xs" style="color: #64748b;">
                            <i class="fas fa-clock ml-1"></i>
                            {{ $message->time_ago }}
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 flex-wrap">
                        <a href="{{ route('admin.contact-messages.show', $message) }}" 
                           class="action-btn btn-view">
                            <i class="fas fa-eye ml-1"></i>
                            عرض
                        </a>
                        @if($message->is_read)
                        <button onclick="markAsUnread({{ $message->id }})" 
                                class="action-btn btn-mark">
                            <i class="fas fa-envelope ml-1"></i>
                            غير مقروءة
                        </button>
                        @else
                        <button onclick="markAsRead({{ $message->id }})" 
                                class="action-btn btn-mark">
                            <i class="fas fa-check ml-1"></i>
                            مقروءة
                        </button>
                        @endif
                        <button onclick="deleteMessage({{ $message->id }})" 
                                class="action-btn btn-delete">
                            <i class="fas fa-trash ml-1"></i>
                            حذف
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Subject & Message Preview -->
            <div class="mt-4 pt-4 border-t" style="border-color: rgba(8, 120, 139, 0.1);">
                <h4 class="font-semibold mb-2" style="color: #025469;">
                    <i class="fas fa-tag ml-2" style="color: #08788B;"></i>
                    {{ $message->subject ?: 'لا يوجد موضوع' }}
                </h4>
                <p class="text-sm leading-relaxed" style="color: #475569;">
                    {{ Str::limit($message->message, 150) }}
                </p>
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
                <i class="fas fa-inbox text-4xl" style="color: #08788B;"></i>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color: #1e293b;">لا توجد رسائل</h3>
            <p class="text-base" style="color: #64748b;">لم يتم إرسال أي رسائل بعد.</p>
        </div>
        @endif
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 flex items-center justify-center p-4">
    <div class="relative w-full max-w-md rounded-2xl shadow-2xl" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%); border: 1px solid rgba(8, 120, 139, 0.1);">
        <div class="p-6 text-center">
            <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);">
                <i class="fas fa-exclamation-triangle text-3xl" style="color: #ef4444;"></i>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color: #1e293b;">تأكيد الحذف</h3>
            <div class="mt-4 mb-6">
                <p class="text-base leading-relaxed" style="color: #475569;">هل أنت متأكد من حذف هذه الرسالة؟ لا يمكن التراجع عن هذا الإجراء.</p>
            </div>
            <div class="flex items-center justify-center gap-3">
                <button id="confirmDelete" class="px-6 py-3 text-white text-base font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-primary-medium" style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%); box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);" onmouseover="this.style.background='linear-gradient(135deg, #065a6b 0%, #2a8a96 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(8, 120, 139, 0.4)';" onmouseout="this.style.background='linear-gradient(135deg, #08788B 0%, #3CA6B4 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 12px rgba(8, 120, 139, 0.3)';">
                    <i class="fas fa-trash ml-2"></i>
                    حذف
                </button>
                <button onclick="closeDeleteModal()" class="px-6 py-3 text-base font-semibold rounded-lg transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-gray-300" style="background: rgba(107, 114, 128, 0.1); color: #475569; border: 1px solid rgba(107, 114, 128, 0.2);" onmouseover="this.style.background='rgba(107, 114, 128, 0.2)';" onmouseout="this.style.background='rgba(107, 114, 128, 0.1)';">
                    <i class="fas fa-times ml-2"></i>
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let messageToDelete = null;

function markAsRead(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-read`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function markAsUnread(messageId) {
    fetch(`/admin/contact-messages/${messageId}/mark-unread`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
        },
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
}

function deleteMessage(messageId) {
    messageToDelete = messageId;
    document.getElementById('deleteModal').classList.remove('hidden');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.add('hidden');
    messageToDelete = null;
}

document.getElementById('confirmDelete').addEventListener('click', function() {
    if (messageToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/contact-messages/${messageToDelete}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
});
</script>
@endpush
