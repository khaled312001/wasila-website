@extends('customer.layouts.app')

@section('title', __('messages.messages'))
@section('page-title', __('messages.messages'))
@section('page-subtitle', __('messages.communicate_with_admin'))

@push('styles')
<style>
    /* Main Container */
    .chat-container {
        height: calc(100vh - 280px);
        min-height: 650px;
        max-height: 850px;
        display: flex;
        flex-direction: column;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Messages Container */
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: linear-gradient(to bottom, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        position: relative;
    }
    
    /* Message Bubbles */
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
        animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        transition: transform 0.2s ease;
    }
    
    .message-bubble:hover {
        transform: translateY(-2px);
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(15px) scale(0.95);
        }
        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }
    
    /* Customer Messages */
    .message-customer {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
        margin-left: auto;
        border-radius: 1.25rem 1.25rem 0.25rem 1.25rem;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3), 0 2px 4px rgba(0, 0, 0, 0.1);
        position: relative;
    }
    
    .message-customer::before {
        content: '';
        position: absolute;
        bottom: 0;
        right: -8px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 0 12px 12px;
        border-color: transparent transparent #128C7E transparent;
    }
    
    /* Admin Messages */
    .message-admin {
        background: white;
        color: #1f2937;
        margin-right: auto;
        border-radius: 1.25rem 1.25rem 1.25rem 0.25rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08), 0 2px 4px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
        position: relative;
    }
    
    .message-admin::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: -8px;
        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 12px 12px 0;
        border-color: transparent white transparent transparent;
    }
    
    .message-time {
        font-size: 0.7rem;
        opacity: 0.75;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    /* File Preview */
    .file-preview {
        border-radius: 0.75rem;
        overflow: hidden;
        margin-top: 0.75rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: transform 0.2s ease;
    }
    
    .file-preview:hover {
        transform: scale(1.02);
    }
    
    .file-preview img {
        max-width: 100%;
        max-height: 350px;
        object-fit: cover;
        cursor: pointer;
        display: block;
    }
    
    /* File Attachment */
    .file-attachment {
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: background 0.2s ease;
        backdrop-filter: blur(10px);
    }
    
    .message-admin .file-attachment {
        background: rgba(0, 0, 0, 0.03);
    }
    
    .file-attachment:hover {
        background: rgba(255, 255, 255, 0.25);
    }
    
    .message-admin .file-attachment:hover {
        background: rgba(0, 0, 0, 0.06);
    }
    
    .file-attachment i {
        font-size: 2.5rem;
        color: #3b82f6;
        opacity: 0.9;
    }
    
    /* Chat Header */
    .chat-header {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .chat-header-icon {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.75rem;
        border-radius: 50%;
        transition: all 0.3s ease;
    }
    
    .chat-header-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(5deg) scale(1.05);
    }
    
    /* Chat Input Container */
    .chat-input-container {
        background: white;
        border-top: 2px solid #e5e7eb;
        padding: 1.25rem;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .chat-input-container textarea {
        border: 2px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .chat-input-container textarea:focus {
        border-color: #08788B;
        box-shadow: 0 0 0 3px rgba(8, 120, 139, 0.1);
    }
    
    /* Typing Indicator */
    .typing-indicator {
        display: none;
        padding: 0.75rem 1.25rem;
        background: rgba(255, 255, 255, 0.95);
        border-top: 1px solid #e5e7eb;
        color: #6b7280;
        font-size: 0.875rem;
        font-style: italic;
    }
    
    .typing-indicator.active {
        display: block;
        animation: fadeIn 0.3s ease;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    .typing-dots span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #08788B;
        margin: 0 2px;
    }
    
    /* Scroll to Bottom Button */
    .scroll-to-bottom {
        position: absolute;
        bottom: 100px;
        right: 30px;
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
        border-radius: 50%;
        width: 48px;
        height: 48px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.4);
        z-index: 10;
        transition: all 0.3s ease;
    }
    
    .scroll-to-bottom:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(37, 211, 102, 0.5);
    }
    
    .scroll-to-bottom.visible {
        display: flex;
        animation: bounceIn 0.5s ease;
    }
    
    @keyframes bounceIn {
        0% {
            opacity: 0;
            transform: scale(0.3);
        }
        50% {
            transform: scale(1.05);
        }
        70% {
            transform: scale(0.9);
        }
        100% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Custom Scrollbar */
    .messages-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .messages-container::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-container::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #cbd5e1, #94a3b8);
        border-radius: 4px;
    }
    
    .messages-container::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #94a3b8, #64748b);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 8px 24px rgba(8, 120, 139, 0.2);
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .empty-state-icon i {
        font-size: 3rem;
        color: white;
    }
    
    /* Contact Card */
    .contact-card {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 10px 30px rgba(8, 120, 139, 0.2);
        position: relative;
        overflow: hidden;
    }
    
    .contact-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    /* Orders Filter Sidebar */
    .orders-sidebar {
        background: white;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    .order-filter-item {
        padding: 0.875rem 1rem;
        border-radius: 0.75rem;
        transition: all 0.3s ease;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .order-filter-item:hover {
        transform: translateX(-4px);
    }
    
    .order-filter-item.active {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .order-filter-item:not(.active) {
        background: #f8fafc;
        color: #64748b;
    }
    
    .order-filter-item:not(.active):hover {
        background: #f1f5f9;
        color: #475569;
    }
    
    /* File Input Button */
    .file-input-btn {
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        padding: 0.875rem;
        border-radius: 0.75rem;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .file-input-btn:hover {
        background: #f1f5f9;
        border-color: #08788B;
        transform: scale(1.05);
    }
    
    /* Send Button */
    .send-button {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        padding: 0.875rem 1.25rem;
        border-radius: 0.75rem;
        color: white;
        transition: all 0.3s ease;
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
    
    /* File Info Display */
    .file-info {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e5e7eb;
        border-radius: 0.75rem;
        padding: 0.875rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    /* Image Preview */
    .image-preview {
        position: relative;
        display: inline-block;
        margin-top: 0.75rem;
    }
    
    .image-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 0.75rem;
        border: 2px solid #e5e7eb;
    }
    
    .image-preview button {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #ef4444;
        color: white;
        border-radius: 50%;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid white;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .image-preview button:hover {
        background: #dc2626;
        transform: scale(1.1);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 200px);
            min-height: 500px;
        }
        
        .message-bubble {
            max-width: 85%;
        }
        
        .chat-header {
            padding: 1rem;
        }
        
        .contact-card {
            padding: 1.5rem;
        }
    }
    
    /* Status Indicator */
    .status-indicator {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #10b981;
        box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        animation: pulse 2s ease-in-out infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2);
        }
        50% {
            box-shadow: 0 0 0 8px rgba(16, 185, 129, 0);
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Main Chat Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden chat-container">
        <!-- Chat Header -->
        <div class="chat-header flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-4">
                <div class="chat-header-icon">
                    <i class="fas fa-headset text-2xl text-white"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-yellow-200 mb-1">{{ __('messages.support_team') }}</h2>
                    <div class="status-indicator">
                        <span class="status-dot"></span>
                        <span class="text-sm text-yellow-200 opacity-95 font-medium" id="statusText">متصل الآن</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                @if(request('order_id'))
                <div class="bg-white/20 backdrop-blur-sm px-4 py-2 rounded-xl text-sm text-white font-semibold flex items-center gap-2 shadow-lg">
                    <i class="fas fa-shopping-cart"></i>
                    <span>طلب #{{ \App\Models\Order::find(request('order_id'))->order_number ?? request('order_id') }}</span>
                </div>
                @endif
                <a href="{{ route('home') }}#contact" class="bg-white/20 hover:bg-white/30 backdrop-blur-sm px-5 py-2.5 rounded-xl text-sm text-white font-semibold transition-all duration-300 flex items-center gap-2 shadow-lg hover:shadow-xl transform hover:scale-105" title="{{ __('messages.contact_us') }}">
                    <i class="fas fa-envelope"></i>
                    <span>{{ __('messages.contact_us') }}</span>
                </a>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container relative" id="messagesContainer">
            <div class="space-y-4" id="messagesList">
                @forelse($messages as $message)
                @include('customer.messages.message-item', ['message' => $message])
                @empty
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-comments"></i>
                    </div>
                    <p class="text-gray-700 text-xl font-bold mb-2">{{ __('messages.no_messages_yet') }}</p>
                    <p class="text-gray-500 text-sm">ابدأ المحادثة مع فريق الدعم</p>
                </div>
                @endforelse
            </div>
            <div class="scroll-to-bottom" id="scrollToBottom" onclick="scrollToBottom()">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div class="typing-indicator" id="typingIndicator">
            <span class="flex items-center gap-2">
                <span class="typing-dots">
                    <span style="animation-delay: 0s;"></span>
                    <span style="animation-delay: 0.2s;"></span>
                    <span style="animation-delay: 0.4s;"></span>
                </span>
                <span>فريق الدعم يكتب...</span>
            </span>
        </div>

        <!-- Chat Input -->
        <div class="chat-input-container">
            <form id="messageForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                <div class="flex items-end gap-3">
                    <!-- File Input -->
                    <label for="fileInput" class="file-input-btn" title="إرسال ملف">
                        <i class="fas fa-paperclip text-gray-600 text-lg"></i>
                        <input type="file" id="fileInput" name="file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                    </label>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="image-preview hidden">
                        <img id="previewImage" src="" alt="Preview">
                        <button type="button" onclick="removeImagePreview()">×</button>
                    </div>
                    
                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            rows="1"
                            class="w-full border-2 border-gray-300 rounded-xl px-4 py-3 pr-12 focus:ring-2 focus:ring-primary-medium focus:border-primary-medium resize-none transition-all duration-300"
                            placeholder="{{ __('messages.type_your_message') }}"
                            onkeydown="handleKeyDown(event)"></textarea>
                        <div class="absolute bottom-3 right-3 text-xs text-gray-400 font-medium" id="charCount">0/5000</div>
                    </div>
                    
                    <!-- Send Button -->
                    <button type="submit" id="sendButton" class="send-button">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                
                <!-- File Info -->
                <div id="fileInfo" class="file-info hidden">
                    <i class="fas fa-file text-primary-medium text-lg"></i>
                    <span id="fileName" class="flex-1 text-sm font-medium text-gray-700"></span>
                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700 transition-colors">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contact Admin Card -->
    <div class="contact-card text-white relative z-10">
        <div class="flex items-center justify-between flex-wrap gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 backdrop-blur-sm p-4 rounded-full shadow-lg">
                    <i class="fas fa-headset text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-yellow-200 mb-1">{{ __('messages.communicate_with_admin') }}</h3>
                    <p class="text-sm text-yellow-200 opacity-95">تواصل معنا مباشرة عبر نموذج الاتصال</p>
                </div>
            </div>
            <a href="{{ route('home') }}#contact" class="bg-white hover:bg-gray-50 text-primary-medium px-6 py-3 rounded-xl font-bold transition-all duration-300 flex items-center gap-2 shadow-xl transform hover:scale-105">
                <i class="fas fa-envelope"></i>
                <span>{{ __('messages.contact_us') }}</span>
            </a>
        </div>
    </div>

    <!-- Orders Filter Sidebar -->
    <div class="orders-sidebar">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-primary-medium"></i>
            {{ __('messages.filter_by_order') }}
        </h2>
        <div class="space-y-2">
            <a href="{{ route('customer.messages.index') }}" 
               class="order-filter-item {{ !request('order_id') ? 'active' : '' }}">
                <i class="fas fa-comments"></i>
                <span>{{ __('messages.all_messages') }}</span>
            </a>
            @foreach(auth('customer')->user()->orders as $order)
            <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" 
               class="order-filter-item {{ request('order_id') == $order->id ? 'active' : '' }}">
                <i class="fas fa-shopping-cart"></i>
                <span>{{ __('messages.order') }} #{{ $order->order_number }}</span>
            </a>
            @endforeach
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
        document.getElementById('charCount').textContent = count + '/5000';
        if (count > 4500) {
            document.getElementById('charCount').classList.add('text-red-500');
        } else {
            document.getElementById('charCount').classList.remove('text-red-500');
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
    
    // Check file size (10MB)
    if (file.size > 10 * 1024 * 1024) {
        alert('حجم الملف كبير جداً. الحد الأقصى 10MB');
        return;
    }
    
    // Show file info
    document.getElementById('fileInfo').classList.remove('hidden');
    document.getElementById('fileName').textContent = file.name;
    
    // If image, show preview
    if (file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('imagePreview').classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}

// Remove file
function removeFile() {
    document.getElementById('fileInput').value = '';
    document.getElementById('fileInfo').classList.add('hidden');
    document.getElementById('imagePreview').classList.add('hidden');
}

// Remove image preview
function removeImagePreview() {
    document.getElementById('imagePreview').classList.add('hidden');
    removeFile();
}

// Handle key down
function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('messageForm').dispatchEvent(new Event('submit'));
    }
}

// Scroll to bottom
function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
    isScrolledToBottom = true;
    document.getElementById('scrollToBottom').classList.remove('visible');
}

// Start polling for new messages
function startPolling() {
    pollingInterval = setInterval(function() {
        fetch('{{ route("customer.messages.get") }}?order_id={{ request("order_id") }}&last_message_id=' + lastMessageId)
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
    }, 3000); // Poll every 3 seconds
}

// Add message to chat
function addMessageToChat(message) {
    const messagesList = document.getElementById('messagesList');
    
    // Remove empty state if exists
    const emptyState = messagesList.querySelector('.empty-state');
    if (emptyState) {
        emptyState.remove();
    }
    
    const messageDiv = document.createElement('div');
    messageDiv.className = 'flex ' + (message.sender_type === 'customer' ? 'justify-end' : 'justify-start');
    messageDiv.innerHTML = getMessageHTML(message);
    messagesList.appendChild(messageDiv);
}

// Get message HTML
function getMessageHTML(message) {
    const isCustomer = message.sender_type === 'customer';
    const senderName = isCustomer ? '{{ __("messages.you") }}' : (message.admin?.name || '{{ __("messages.admin") }}');
    const time = new Date(message.created_at).toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
    
    let fileHTML = '';
    if (message.file_path) {
        if (message.file_type === 'image') {
            fileHTML = `<div class="file-preview"><img src="${message.file_url}" alt="${message.file_name}" class="rounded-lg cursor-pointer" onclick="openImageModal('${message.file_url}')"></div>`;
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
                    <a href="${message.file_url}" download class="text-primary-medium hover:text-primary-dark transition-colors">
                        <i class="fas fa-download"></i>
                    </a>
                </div>
            `;
        }
    }
    
    return `
        <div class="message-bubble message-${message.sender_type} p-4">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-xs font-bold opacity-90">${senderName}</span>
                ${message.order ? `<span class="text-xs opacity-70">• {{ __('messages.order') }} #${message.order.order_number}</span>` : ''}
            </div>
            ${message.message ? `<p class="text-sm mb-2 leading-relaxed">${escapeHtml(message.message)}</p>` : ''}
            ${fileHTML}
            <p class="message-time">${time}</p>
        </div>
    `;
}

// Escape HTML
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
        const response = await fetch('{{ route("customer.messages.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            // Add message to chat immediately
            addMessageToChat(data.data);
            lastMessageId = Math.max(lastMessageId, data.data.id);
            
            // Clear form
            messageInput.value = '';
            messageInput.style.height = 'auto';
            document.getElementById('charCount').textContent = '0/5000';
            document.getElementById('charCount').classList.remove('text-red-500');
            removeFile();
            
            scrollToBottom();
        } else {
            alert(data.message || '{{ __("messages.error_occurred") }}');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('{{ __("messages.error_sending_message") }}');
    } finally {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
});

// Open image modal
function openImageModal(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-90 z-50 flex items-center justify-center p-4';
    modal.innerHTML = `
        <div class="relative max-w-5xl max-h-full">
            <img src="${imageUrl}" alt="Image" class="max-w-full max-h-screen rounded-xl shadow-2xl">
            <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 bg-white text-gray-800 rounded-full w-12 h-12 flex items-center justify-center hover:bg-gray-200 transition-colors shadow-lg">
                <i class="fas fa-times text-xl"></i>
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

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
@endpush
