@extends('admin.layouts.app')

@section('title', 'رسائل العميل')
@section('page-title', 'رسائل العميل: ' . $customer->name)

@push('styles')
<style>
    /* Responsive Chat Container */
    .chat-container {
        height: calc(100vh - 200px);
        min-height: 500px;
        max-height: 900px;
        display: flex;
        flex-direction: column;
    }
    
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 150px);
            min-height: 400px;
            max-height: none;
        }
    }
    
    @media (max-width: 640px) {
        .chat-container {
            height: calc(100vh - 120px);
            min-height: 350px;
        }
    }
    
    /* Messages Container */
    .messages-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 0.5rem;
        background: #E5DDD5;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3Cpattern id='grid' width='100' height='100' patternUnits='userSpaceOnUse'%3E%3Cpath d='M 100 0 L 0 0 0 100' fill='none' stroke='%23E5DDD5' stroke-width='1'/%3E%3C/pattern%3E%3C/defs%3E%3Crect width='100' height='100' fill='url(%23grid)'/%3E%3C/svg%3E");
        background-size: 100px 100px;
        position: relative;
    }
    
    @media (max-width: 768px) {
        .messages-container {
            padding: 0.75rem;
        }
    }
    
    @media (max-width: 640px) {
        .messages-container {
            padding: 0.5rem;
        }
    }
    
    /* Message Bubble */
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
        word-break: break-word;
        position: relative;
        animation: slideIn 0.3s ease-out;
    }
    
    @media (max-width: 768px) {
        .message-bubble {
            max-width: 85%;
        }
    }
    
    @media (max-width: 640px) {
        .message-bubble {
            max-width: 90%;
        }
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Admin Message - WhatsApp Style */
    .message-admin {
        background: #DCF8C6;
        color: #111b21;
        margin-left: auto;
        border-radius: 0.5rem 0.5rem 0.125rem 0.5rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        padding: 0.375rem 0.5rem 0.125rem 0.5rem;
        position: relative;
        max-width: 65%;
    }
    
    .message-admin::before {
        content: '';
        position: absolute;
        left: -8px;
        bottom: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 8px 13px 0;
        border-color: transparent #DCF8C6 transparent transparent;
    }
    
    /* Customer Message - WhatsApp Style */
    .message-customer {
        background: #ffffff;
        color: #111b21;
        margin-right: auto;
        border-radius: 0.5rem 0.5rem 0.5rem 0.125rem;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        padding: 0.375rem 0.5rem 0.125rem 0.5rem;
        position: relative;
        max-width: 65%;
    }
    
    .message-customer::before {
        content: '';
        position: absolute;
        right: -8px;
        bottom: 0;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 13px 8px;
        border-color: transparent transparent transparent #ffffff;
    }
    
    .message-time {
        font-size: 0.6875rem;
        opacity: 0.6;
        margin-top: 0.125rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 0.25rem;
        direction: ltr;
        text-align: left;
        color: #667781;
    }
    
    .message-time i {
        font-size: 0.625rem;
    }
    
    /* File Preview */
    .file-preview {
        border-radius: 0.75rem;
        overflow: hidden;
        margin-top: 0.75rem;
        max-width: 100%;
    }
    
    .file-preview img {
        max-width: 100%;
        max-height: 300px;
        object-fit: cover;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .file-preview img:hover {
        transform: scale(1.02);
    }
    
    @media (max-width: 640px) {
        .file-preview img {
            max-height: 200px;
        }
    }
    
    /* File Attachment */
    .file-attachment {
        background: rgba(8, 120, 139, 0.1);
        padding: 0.875rem;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.875rem;
        transition: all 0.3s ease;
    }
    
    .file-attachment:hover {
        background: rgba(8, 120, 139, 0.15);
    }
    
    .file-attachment i {
        font-size: 2rem;
        color: #08788B;
        flex-shrink: 0;
    }
    
    /* Chat Input Container */
    .chat-input-container {
        background: white;
        border-top: 2px solid #e5e7eb;
        padding: 1.25rem;
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .chat-input-container {
            padding: 1rem;
        }
    }
    
    @media (max-width: 640px) {
        .chat-input-container {
            padding: 0.75rem;
        }
    }
    
    /* Input Group */
    .input-group {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    @media (max-width: 640px) {
        .input-group {
            gap: 0.5rem;
        }
    }
    
    /* Textarea */
    .message-textarea {
        flex: 1;
        min-width: 200px;
        border: 2px solid #e5e7eb;
        border-radius: 1rem;
        padding: 0.875rem 1rem;
        padding-bottom: 2rem;
        font-size: 0.9375rem;
        resize: none;
        transition: all 0.3s ease;
        font-family: inherit;
    }
    
    .message-textarea:focus {
        outline: none;
        border-color: #08788B;
        box-shadow: 0 0 0 3px rgba(8, 120, 139, 0.1);
    }
    
    @media (max-width: 640px) {
        .message-textarea {
            font-size: 16px; /* Prevent zoom on iOS */
            padding: 0.75rem;
            padding-bottom: 1.75rem;
        }
    }
    
    /* Character Count */
    .char-count {
        position: absolute;
        bottom: 0.5rem;
        right: 0.75rem;
        font-size: 0.75rem;
        color: #94a3b8;
        pointer-events: none;
    }
    
    .char-count.warning {
        color: #f59e0b;
    }
    
    .char-count.danger {
        color: #ef4444;
    }
    
    /* File Input Button */
    .file-input-btn {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        padding: 0.875rem;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .file-input-btn:hover {
        background: #e2e8f0;
        border-color: #cbd5e1;
        transform: scale(1.05);
    }
    
    .file-input-btn i {
        font-size: 1.25rem;
        color: #64748b;
    }
    
    @media (max-width: 640px) {
        .file-input-btn {
            padding: 0.75rem;
        }
    }
    
    /* Send Button */
    .send-button {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
        color: white;
        border: none;
        padding: 0.875rem 1.5rem;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .send-button:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(8, 120, 139, 0.4);
    }
    
    .send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .send-button i {
        font-size: 1.125rem;
    }
    
    @media (max-width: 640px) {
        .send-button {
            padding: 0.75rem 1.25rem;
        }
    }
    
    /* Image Preview */
    .image-preview-container {
        position: relative;
        display: inline-block;
        margin-bottom: 0.5rem;
    }
    
    .image-preview-container img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.5rem;
        border: 2px solid #e5e7eb;
    }
    
    .image-preview-remove {
        position: absolute;
        top: -0.5rem;
        right: -0.5rem;
        background: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 1rem;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }
    
    /* File Info */
    .file-info {
        margin-top: 0.75rem;
        padding: 0.75rem;
        background: #f1f5f9;
        border-radius: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.875rem;
        color: #475569;
    }
    
    .file-info-remove {
        color: #ef4444;
        cursor: pointer;
        padding: 0.25rem;
        transition: all 0.2s ease;
    }
    
    .file-info-remove:hover {
        transform: scale(1.2);
    }
    
    /* Scroll to Bottom Button */
    .scroll-to-bottom {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
        color: white;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.4);
        z-index: 10;
        transition: all 0.3s ease;
    }
    
    .scroll-to-bottom.visible {
        display: flex;
    }
    
    .scroll-to-bottom:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(8, 120, 139, 0.5);
    }
    
    @media (max-width: 640px) {
        .scroll-to-bottom {
            width: 44px;
            height: 44px;
            bottom: 0.75rem;
            right: 0.75rem;
        }
    }
    
    /* Scrollbar */
    .messages-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .messages-container::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }
    
    .messages-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
    
    /* Chat Header */
    .chat-header {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        padding: 1.25rem 1.5rem;
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .chat-header {
            padding: 1rem;
        }
    }
    
    @media (max-width: 640px) {
        .chat-header {
            padding: 0.875rem;
        }
    }
    
    .chat-header-content {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    @media (max-width: 640px) {
        .chat-header-content {
            flex-direction: column;
            align-items: flex-start;
        }
    }
    
    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 0.875rem;
    }
    
    .chat-header-icon {
        width: 48px;
        height: 48px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    
    @media (max-width: 640px) {
        .chat-header-icon {
            width: 40px;
            height: 40px;
        }
    }
    
    .chat-header-info h2 {
        font-size: 1.125rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
    }
    
    .chat-header-info p {
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    @media (max-width: 640px) {
        .chat-header-info h2 {
            font-size: 1rem;
        }
        
        .chat-header-info p {
            font-size: 0.75rem;
        }
    }
    
    .chat-header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    @media (max-width: 640px) {
        .chat-header-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
    
    .chat-header-btn {
        background: rgba(255, 255, 255, 0.2);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-size: 0.875rem;
        transition: all 0.3s ease;
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .chat-header-btn:hover {
        background: rgba(255, 255, 255, 0.3);
    }
    
    @media (max-width: 640px) {
        .chat-header-btn {
            padding: 0.5rem 0.75rem;
            font-size: 0.75rem;
        }
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        width: 96px;
        height: 96px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .empty-state-icon i {
        font-size: 2.5rem;
        color: #94a3b8;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #94a3b8;
        font-size: 0.9375rem;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto px-4 py-6">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden chat-container">
        <!-- Chat Header -->
        <div class="chat-header">
            <div class="chat-header-content">
                <div class="chat-header-left">
                    <div class="chat-header-icon">
                        <i class="fas fa-user text-xl text-white"></i>
                    </div>
                    <div class="chat-header-info">
                        <h2 class="text-white font-bold">{{ $customer->name }}</h2>
                        <p class="text-white opacity-90">{{ $customer->email }}</p>
                    </div>
                </div>
                <div class="chat-header-actions">
                    @if($customer->phone)
                    <a href="tel:{{ $customer->phone }}" class="chat-header-btn">
                        <i class="fas fa-phone"></i>
                        <span class="hidden sm:inline">{{ $customer->phone }}</span>
                        <span class="sm:hidden">اتصال</span>
                    </a>
                    @endif
                    <a href="{{ route('admin.customers.show', $customer) }}" class="chat-header-btn">
                        <i class="fas fa-user-circle"></i>
                        <span class="hidden sm:inline">تفاصيل العميل</span>
                        <span class="sm:hidden">التفاصيل</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container relative" id="messagesContainer">
            <div class="space-y-3" id="messagesList">
                @forelse($messages as $message)
                @include('admin.customers.message-item', ['message' => $message, 'customer' => $customer])
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3>لا توجد رسائل بعد</h3>
                    <p>ابدأ المحادثة مع العميل</p>
                </div>
                @endforelse
            </div>
            <div class="scroll-to-bottom" id="scrollToBottom" onclick="scrollToBottom()">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="chat-input-container">
            <form id="messageForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" value="">
                
                <!-- Image Preview -->
                <div id="imagePreview" class="hidden mb-2">
                    <div class="image-preview-container">
                        <img id="previewImage" src="" alt="Preview">
                        <button type="button" onclick="removeImagePreview()" class="image-preview-remove">×</button>
                    </div>
                </div>
                
                <!-- Input Group -->
                <div class="input-group">
                    <!-- File Input -->
                    <label for="fileInput" class="file-input-btn" title="إرسال ملف">
                        <i class="fas fa-paperclip"></i>
                        <input type="file" id="fileInput" name="file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                    </label>
                    
                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            rows="1"
                            class="message-textarea"
                            placeholder="اكتب رسالتك..."
                            onkeydown="handleKeyDown(event)"
                            maxlength="5000"></textarea>
                        <div class="char-count" id="charCount">0/5000</div>
                    </div>
                    
                    <!-- Send Button -->
                    <button type="submit" id="sendButton" class="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                
                <!-- File Info -->
                <div id="fileInfo" class="hidden file-info">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-file"></i>
                        <span id="fileName"></span>
                    </div>
                    <button type="button" onclick="removeFile()" class="file-info-remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let lastMessageId = {{ $messages->last()->id ?? 0 }};
let pollingInterval;
let isScrolledToBottom = true;

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    scrollToBottom();
    startPolling();
    
    // File input handler
    document.getElementById('fileInput').addEventListener('change', function(e) {
        handleFileSelect(e.target.files[0]);
    });
    
    // Character counter
    document.getElementById('messageInput').addEventListener('input', function() {
        const count = this.value.length;
        const charCountEl = document.getElementById('charCount');
        charCountEl.textContent = count + '/5000';
        
        // Update color based on count
        charCountEl.classList.remove('warning', 'danger');
        if (count > 4500) {
            charCountEl.classList.add('danger');
        } else if (count > 4000) {
            charCountEl.classList.add('warning');
        }
    });
    
    // Auto-resize textarea
    document.getElementById('messageInput').addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
    
    // Scroll detection
    document.getElementById('messagesContainer').addEventListener('scroll', function() {
        const container = this;
        isScrolledToBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
        document.getElementById('scrollToBottom').classList.toggle('visible', !isScrolledToBottom);
    });
});

// Handle file select
function handleFileSelect(file) {
    if (!file) return;
    
    if (file.size > 10 * 1024 * 1024) {
        alert('حجم الملف كبير جداً. الحد الأقصى 10MB');
        return;
    }
    
    document.getElementById('fileInfo').classList.remove('hidden');
    document.getElementById('fileName').textContent = file.name;
    
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('imagePreview').classList.add('hidden');
}

function removeImagePreview() {
    document.getElementById('imagePreview').classList.add('hidden');
    removeFile();
}

function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('messageForm').dispatchEvent(new Event('submit'));
    }
}

function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
    isScrolledToBottom = true;
    document.getElementById('scrollToBottom').classList.remove('visible');
}

// Start polling
function startPolling() {
    pollingInterval = setInterval(function() {
        fetch('{{ route("admin.customers.messages.get", $customer) }}?last_message_id=' + lastMessageId)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.messages.length > 0) {
                    data.messages.forEach(message => {
                        addMessageToChat(message);
                        lastMessageId = Math.max(lastMessageId, message.id);
                    });
                    
                    if (isScrolledToBottom) {
                        setTimeout(scrollToBottom, 100);
                    }
                }
            })
            .catch(error => console.error('Error:', error));
    }, 3000);
}

function addMessageToChat(message) {
    const messagesList = document.getElementById('messagesList');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex ' + (message.sender_type === 'admin' ? 'justify-end' : 'justify-start');
    messageDiv.innerHTML = getMessageHTML(message);
    messagesList.appendChild(messageDiv);
}

function getMessageHTML(message) {
    const isAdmin = message.sender_type === 'admin';
    const senderName = isAdmin ? '{{ auth()->user()->name }}' : '{{ $customer->name }}';
    const time = new Date(message.created_at).toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
    
    let fileHTML = '';
    if (message.file_path) {
        if (message.file_type === 'image') {
            fileHTML = `<div class="file-preview"><img src="${message.file_url}" alt="${message.file_name}" class="rounded-lg"></div>`;
        } else {
            const icon = message.file_type === 'video' ? 'fa-video' : 
                        message.file_type === 'audio' ? 'fa-music' : 
                        'fa-file';
            fileHTML = `
                <div class="file-attachment">
                    <i class="fas ${icon}"></i>
                    <div class="flex-1">
                        <div class="font-semibold">${message.file_name}</div>
                        <div class="text-xs opacity-70">${message.formatted_file_size || ''}</div>
                    </div>
                    <a href="${message.file_url}" download class="text-primary-medium hover:text-primary-dark">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            `;
        }
    }
    
    // إضافة أيقونات حالة القراءة للرسائل المرسلة من الإدارة
    let readStatusIcon = '';
    if (isAdmin) {
        if (message.is_read) {
            readStatusIcon = '<i class="fas fa-check-double" style="color: #53bdeb;"></i>';
        } else {
            readStatusIcon = '<i class="fas fa-check"></i>';
        }
    }
    
    return `
        <div class="message-bubble message-${message.sender_type} p-4">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold opacity-80">${senderName}</span>
            </div>
            ${message.message ? `<p class="text-sm mb-2">${escapeHtml(message.message)}</p>` : ''}
            ${fileHTML}
            <p class="message-time">${time} ${readStatusIcon}</p>
        </div>
    `;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

// Form submit
document.getElementById('messageForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const messageInput = document.getElementById('messageInput');
    const fileInput = document.getElementById('fileInput');
    
    if (!messageInput.value.trim() && !fileInput.files[0]) {
        return;
    }
    
    const sendButton = document.getElementById('sendButton');
    sendButton.disabled = true;
    sendButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
    
    try {
        const response = await fetch('{{ route("admin.customers.messages.send", $customer) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        let data;
        try {
            data = await response.json();
        } catch (jsonError) {
            console.error('JSON parse error:', jsonError);
            throw new Error('حدث خطأ في معالجة الاستجابة من الخادم');
        }
        
        if (response.ok && data.success) {
            addMessageToChat(data.data);
            lastMessageId = Math.max(lastMessageId, data.data.id);
            
            messageInput.value = '';
            messageInput.style.height = 'auto';
            document.getElementById('charCount').textContent = '0/5000';
            removeFile();
            
            scrollToBottom();
        } else {
            const errorMessage = data.message || data.error || '{{ __("messages.error_sending_message") }}';
            alert(errorMessage);
        }
    } catch (error) {
        console.error('Error:', error);
        const errorMessage = error.message || '{{ __("messages.error_sending_message") }}';
        alert(errorMessage);
    } finally {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
});

// Open image modal
function openImageModal(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center';
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full p-4">
            <img src="${imageUrl}" alt="Image" class="max-w-full max-h-screen rounded-lg">
            <button onclick="this.closest('.fixed').remove()" class="absolute top-6 right-6 bg-white text-gray-800 rounded-full w-10 h-10 flex items-center justify-center hover:bg-gray-200">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    document.body.appendChild(modal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.remove();
        }
    });
}

window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
@endpush
