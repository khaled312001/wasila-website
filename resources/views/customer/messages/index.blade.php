@extends('customer.layouts.app')

@section('title', __('messages.messages'))
@section('page-title', __('messages.messages'))
@section('page-subtitle', __('messages.communicate_with_admin'))

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 300px);
        min-height: 600px;
        max-height: 800px;
    }
    
    .messages-container {
        height: calc(100% - 120px);
        overflow-y: auto;
        padding: 1rem;
        background: linear-gradient(to bottom, #e5e7eb 0%, #f3f4f6 100%);
    }
    
    .message-bubble {
        max-width: 70%;
        word-wrap: break-word;
        position: relative;
        animation: slideIn 0.3s ease-out;
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
    
    .message-customer {
        background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        color: white;
        margin-left: auto;
        border-radius: 1rem 1rem 0.25rem 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .message-admin {
        background: white;
        color: #1f2937;
        margin-right: auto;
        border-radius: 1rem 1rem 1rem 0.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .message-time {
        font-size: 0.7rem;
        opacity: 0.7;
        margin-top: 0.25rem;
    }
    
    .file-preview {
        border-radius: 0.5rem;
        overflow: hidden;
        margin-top: 0.5rem;
    }
    
    .file-preview img {
        max-width: 100%;
        max-height: 300px;
        object-fit: cover;
    }
    
    .file-attachment {
        background: rgba(0,0,0,0.05);
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .file-attachment i {
        font-size: 2rem;
        color: #3b82f6;
    }
    
    .chat-input-container {
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
    }
    
    .typing-indicator {
        display: none;
        padding: 0.5rem 1rem;
        color: #6b7280;
        font-size: 0.875rem;
    }
    
    .typing-indicator.active {
        display: block;
    }
    
    .scroll-to-bottom {
        position: absolute;
        bottom: 80px;
        right: 20px;
        background: #25D366;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 10;
    }
    
    .scroll-to-bottom.visible {
        display: flex;
    }
    
    /* Custom Scrollbar */
    .messages-container::-webkit-scrollbar {
        width: 6px;
    }
    
    .messages-container::-webkit-scrollbar-track {
        background: transparent;
    }
    
    .messages-container::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 3px;
    }
    
    .messages-container::-webkit-scrollbar-thumb:hover {
        background: #94a3b8;
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden chat-container">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-primary-medium to-primary-dark text-white p-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <i class="fas fa-headset text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-white">{{ __('messages.support_team') }}</h2>
                    <p class="text-xs text-white opacity-90 flex items-center gap-1" id="statusText">
                        <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                        متصل الآن
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3 flex-wrap">
                @if(request('order_id'))
                <div class="bg-white/20 px-3 py-1 rounded-lg text-sm text-white">
                    <i class="fas fa-shopping-cart ml-1"></i>
                    طلب #{{ \App\Models\Order::find(request('order_id'))->order_number ?? request('order_id') }}
                </div>
                @endif
                <a href="{{ route('home') }}#contact" class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm text-white font-semibold transition-all duration-200 flex items-center gap-2" title="{{ __('messages.contact_us') }}">
                    <i class="fas fa-envelope"></i>
                    <span>{{ __('messages.contact_us') }}</span>
                </a>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container relative" id="messagesContainer">
            <div class="space-y-3" id="messagesList">
                @forelse($messages as $message)
                @include('customer.messages.message-item', ['message' => $message])
                @empty
                <div class="text-center py-12">
                    <div class="bg-white rounded-full p-6 w-24 h-24 mx-auto mb-4 shadow-lg flex items-center justify-center">
                        <i class="fas fa-comments text-4xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_messages_yet') }}</p>
                    <p class="text-gray-400 text-sm">ابدأ المحادثة مع فريق الدعم</p>
                </div>
                @endforelse
            </div>
            <div class="scroll-to-bottom" id="scrollToBottom" onclick="scrollToBottom()">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>

        <!-- Typing Indicator -->
        <div class="typing-indicator" id="typingIndicator">
            <span class="flex items-center gap-1">
                <span class="animate-bounce">●</span>
                <span class="animate-bounce" style="animation-delay: 0.1s">●</span>
                <span class="animate-bounce" style="animation-delay: 0.2s">●</span>
                فريق الدعم يكتب...
            </span>
        </div>

        <!-- Chat Input -->
        <div class="chat-input-container">
            <form id="messageForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                <div class="flex items-end gap-2">
                    <!-- File Input -->
                    <label for="fileInput" class="bg-gray-100 hover:bg-gray-200 p-3 rounded-lg cursor-pointer transition-colors" title="إرسال ملف">
                        <i class="fas fa-paperclip text-gray-600"></i>
                        <input type="file" id="fileInput" name="file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                    </label>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden">
                        <img id="previewImage" src="" alt="Preview" class="w-16 h-16 object-cover rounded-lg">
                        <button type="button" onclick="removeImagePreview()" class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs">×</button>
                    </div>
                    
                    <!-- Message Input -->
                    <div class="flex-1 relative">
                        <textarea 
                            name="message" 
                            id="messageInput"
                            rows="1"
                            class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 pr-10 focus:ring-2 focus:ring-primary-medium focus:border-primary-medium resize-none"
                            placeholder="{{ __('messages.type_your_message') }}"
                            onkeydown="handleKeyDown(event)"></textarea>
                        <div class="absolute bottom-2 right-2 text-xs text-gray-400" id="charCount">0/5000</div>
                    </div>
                    
                    <!-- Send Button -->
                    <button type="submit" id="sendButton" class="bg-gradient-to-r from-primary-medium to-primary-dark text-white p-3 rounded-lg hover:shadow-lg transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </div>
                
                <!-- File Info -->
                <div id="fileInfo" class="hidden mt-2 p-2 bg-gray-100 rounded-lg text-sm text-gray-600">
                    <i class="fas fa-file ml-1"></i>
                    <span id="fileName"></span>
                    <button type="button" onclick="removeFile()" class="text-red-500 hover:text-red-700 mr-2">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Contact Admin Button -->
    <div class="mt-6 bg-gradient-to-r from-primary-medium to-primary-dark rounded-xl shadow-lg p-6 text-white">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-3 rounded-full">
                    <i class="fas fa-headset text-2xl"></i>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">{{ __('messages.communicate_with_admin') }}</h3>
                    <p class="text-sm text-white opacity-90">تواصل معنا مباشرة عبر نموذج الاتصال</p>
                </div>
            </div>
            <a href="{{ route('home') }}#contact" class="bg-white hover:bg-gray-100 text-primary-medium px-6 py-3 rounded-lg font-semibold transition-all duration-200 flex items-center gap-2 shadow-lg transform hover:scale-105">
                <i class="fas fa-envelope"></i>
                <span>{{ __('messages.contact_us') }}</span>
            </a>
        </div>
    </div>

    <!-- Orders Sidebar -->
    <div class="mt-6 bg-white rounded-xl shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fas fa-filter text-primary-medium"></i>
            {{ __('messages.filter_by_order') }}
        </h2>
        <div class="space-y-2">
            <a href="{{ route('customer.messages.index') }}" 
               class="block px-4 py-3 rounded-lg transition-all {{ !request('order_id') ? 'bg-gradient-to-r from-primary-medium to-primary-dark text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-comments ml-2"></i>
                {{ __('messages.all_messages') }}
            </a>
            @foreach(auth('customer')->user()->orders as $order)
            <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" 
               class="block px-4 py-3 rounded-lg transition-all {{ request('order_id') == $order->id ? 'bg-gradient-to-r from-primary-medium to-primary-dark text-white shadow-lg' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                <i class="fas fa-shopping-cart ml-2"></i>
                {{ __('messages.order') }} #{{ $order->order_number }}
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
    
    return `
        <div class="message-bubble message-${message.sender_type} p-4">
            <div class="flex items-center gap-2 mb-1">
                <span class="text-xs font-semibold opacity-80">${senderName}</span>
                ${message.order ? `<span class="text-xs opacity-60">• {{ __('messages.order') }} #${message.order.order_number}</span>` : ''}
            </div>
            ${message.message ? `<p class="text-sm mb-2">${escapeHtml(message.message)}</p>` : ''}
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

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
@endpush
