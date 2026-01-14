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
    
    .btn-edit {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .btn-edit:hover {
        background: linear-gradient(135deg, #d97706 0%, #b45309 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
    }
    
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
    }
    
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
    }
    
    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .modal-content {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        margin: auto;
        padding: 2rem;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
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
                        <button onclick="openEditModal({{ $message->id }}, '{{ addslashes($message->message ?? '') }}', {{ $message->order_id ?? 'null' }}, {{ $message->customer_id ?? 'null' }})" 
                                class="action-btn btn-edit">
                            <i class="fas fa-edit ml-1"></i>
                            تعديل
                        </button>
                        <button onclick="openDeleteModal({{ $message->id }})" 
                                class="action-btn btn-delete">
                            <i class="fas fa-trash ml-1"></i>
                            حذف
                        </button>
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

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="modal">
    <div class="modal-content">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(220, 38, 38, 0.1) 100%);">
                <i class="fas fa-exclamation-triangle text-3xl" style="color: #ef4444;"></i>
            </div>
            <h3 class="text-xl font-bold mb-2" style="color: #1e293b;">تأكيد الحذف</h3>
            <p class="text-base mb-6" style="color: #475569;">هل أنت متأكد من حذف هذه الرسالة؟ لا يمكن التراجع عن هذا الإجراء.</p>
            <div class="flex items-center justify-center gap-3">
                <button id="confirmDeleteBtn" class="px-6 py-3 text-white text-base font-semibold rounded-lg transition-all duration-200" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                    <i class="fas fa-trash ml-2"></i>
                    حذف
                </button>
                <button onclick="closeDeleteModal()" class="px-6 py-3 text-base font-semibold rounded-lg transition-all duration-200" style="background: rgba(107, 114, 128, 0.1); color: #475569; border: 1px solid rgba(107, 114, 128, 0.2);">
                    <i class="fas fa-times ml-2"></i>
                    إلغاء
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <div class="mb-4">
            <h3 class="text-2xl font-bold mb-2" style="color: #025469;">
                <i class="fas fa-edit ml-2"></i>
                تعديل الرسالة
            </h3>
        </div>
        <form id="editMessageForm" onsubmit="updateMessage(event)">
            @csrf
            @method('PUT')
            <input type="hidden" id="editMessageId" name="message_id">
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2" style="color: #1e293b;">الرسالة</label>
                <textarea id="editMessageText" name="message" rows="6" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium" style="border-color: rgba(8, 120, 139, 0.2);" maxlength="5000"></textarea>
                <div class="text-xs mt-1" style="color: #64748b;">
                    <span id="editCharCount">0</span>/5000
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-semibold mb-2" style="color: #1e293b;">الطلب (اختياري)</label>
                <select id="editOrderId" name="order_id" class="w-full px-4 py-3 border rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium" style="border-color: rgba(8, 120, 139, 0.2);">
                    <option value="">لا يوجد طلب</option>
                </select>
            </div>
            
            <div class="flex items-center justify-end gap-3">
                <button type="button" onclick="closeEditModal()" class="px-6 py-3 text-base font-semibold rounded-lg transition-all duration-200" style="background: rgba(107, 114, 128, 0.1); color: #475569; border: 1px solid rgba(107, 114, 128, 0.2);">
                    <i class="fas fa-times ml-2"></i>
                    إلغاء
                </button>
                <button type="submit" class="px-6 py-3 text-white text-base font-semibold rounded-lg transition-all duration-200" style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);">
                    <i class="fas fa-save ml-2"></i>
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
let messageToDelete = null;
let currentEditMessageId = null;

// Delete Modal Functions
function openDeleteModal(messageId) {
    messageToDelete = messageId;
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
    messageToDelete = null;
}

document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (messageToDelete) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/customer-messages/${messageToDelete}`;
        
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

// Orders data for edit modal
const ordersData = @json($customersWithOrders->mapWithKeys(function($customer) {
    return [$customer->id => $customer->orders->map(function($order) {
        return ['id' => $order->id, 'number' => $order->order_number];
    })];
}));

// Edit Modal Functions
function openEditModal(messageId, messageText, orderId, customerId) {
    currentEditMessageId = messageId;
    document.getElementById('editMessageId').value = messageId;
    document.getElementById('editMessageText').value = messageText || '';
    
    // Load orders for this customer
    const orderSelect = document.getElementById('editOrderId');
    orderSelect.innerHTML = '<option value="">لا يوجد طلب</option>';
    
    if (customerId && ordersData[customerId]) {
        ordersData[customerId].forEach(function(order) {
            const option = document.createElement('option');
            option.value = order.id;
            option.textContent = 'طلب #' + order.number;
            if (orderId && order.id == orderId) {
                option.selected = true;
            }
            orderSelect.appendChild(option);
        });
    }
    
    updateCharCount();
    document.getElementById('editModal').classList.add('show');
}

function closeEditModal() {
    document.getElementById('editModal').classList.remove('show');
    currentEditMessageId = null;
    document.getElementById('editMessageForm').reset();
}

function updateCharCount() {
    const textarea = document.getElementById('editMessageText');
    const charCount = document.getElementById('editCharCount');
    charCount.textContent = textarea.value.length;
}

document.getElementById('editMessageText').addEventListener('input', updateCharCount);

function updateMessage(event) {
    event.preventDefault();
    
    if (!currentEditMessageId) return;
    
    const formData = new FormData(event.target);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
    formData.append('_method', 'PUT');
    
    fetch(`/admin/customer-messages/${currentEditMessageId}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('تم تحديث الرسالة بنجاح');
            location.reload();
        } else {
            alert(data.message || 'حدث خطأ أثناء تحديث الرسالة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء تحديث الرسالة');
    });
}

// Close modals when clicking outside
window.onclick = function(event) {
    const deleteModal = document.getElementById('deleteModal');
    const editModal = document.getElementById('editModal');
    
    if (event.target == deleteModal) {
        closeDeleteModal();
    }
    if (event.target == editModal) {
        closeEditModal();
    }
}
</script>
@endpush
