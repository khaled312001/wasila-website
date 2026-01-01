@extends('admin.layouts.app')

@section('title', 'رسالة الاتصال')
@section('page-title', 'رسالة الاتصال')

@push('styles')
<style>
    :root {
        --primary-medium: #08788B;
        --primary-dark: #025469;
    }
    
    .chat-container {
        height: calc(100vh - 200px);
        min-height: 700px;
        max-height: 900px;
        display: flex;
        flex-direction: column;
    }
    
    .messages-container {
        flex: 1;
        overflow-y: auto;
        overflow-x: hidden;
        padding: 1.5rem;
        background: linear-gradient(to bottom, #f8fafc 0%, #e5e7eb 100%);
        position: relative;
    }
    
    .messages-container #messagesList {
        display: flex;
        flex-direction: column;
        gap: 1rem;
        min-height: 100%;
    }
    
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
        animation: slideIn 0.3s ease-out;
        display: flex;
        flex-direction: column;
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
    
    .message-admin {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        color: white;
        margin-left: auto;
        margin-right: 0;
        border-radius: 1.25rem 1.25rem 0.25rem 1.25rem;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
        align-self: flex-end;
    }
    
    .message-customer {
        background: white;
        color: #1f2937;
        margin-right: auto;
        margin-left: 0;
        border-radius: 1.25rem 1.25rem 1.25rem 0.25rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        align-self: flex-start;
    }
    
    .message-bubble .p-4 {
        padding: 1rem 1.25rem;
    }
    
    .message-time {
        font-size: 0.75rem;
        opacity: 0.8;
        margin-top: 0.5rem;
        display: block;
    }
    
    .file-preview {
        border-radius: 0.75rem;
        overflow: hidden;
        margin-top: 0.75rem;
        background: rgba(0,0,0,0.05);
    }
    
    .file-preview img {
        max-width: 100%;
        max-height: 350px;
        object-fit: cover;
        cursor: pointer;
        display: block;
        width: 100%;
    }
    
    .file-preview video {
        max-width: 100%;
        max-height: 350px;
        border-radius: 0.75rem;
    }
    
    .file-attachment {
        background: rgba(0,0,0,0.08);
        padding: 1rem;
        border-radius: 0.75rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .file-attachment i {
        font-size: 2.5rem;
        color: #08788B;
    }
    
    .file-attachment div {
        flex: 1;
    }
    
    .chat-input-container {
        background: white;
        border-top: 2px solid #e5e7eb;
        padding: 1.25rem;
        flex-shrink: 0;
    }
    
    .chat-input-container .flex {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
    }
    
    .scroll-to-bottom {
        position: absolute;
        bottom: 100px;
        right: 20px;
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        color: white;
        border-radius: 50%;
        width: 45px;
        height: 45px;
        display: none;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.2);
        z-index: 10;
        transition: all 0.3s ease;
    }
    
    .scroll-to-bottom:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 16px rgba(0,0,0,0.3);
    }
    
    .scroll-to-bottom.visible {
        display: flex;
    }
    
    .messages-container::-webkit-scrollbar {
        width: 8px;
    }
    
    .messages-container::-webkit-scrollbar-track {
        background: rgba(0,0,0,0.05);
        border-radius: 4px;
    }
    
    .messages-container::-webkit-scrollbar-thumb {
        background: #08788B;
        border-radius: 4px;
    }
    
    .messages-container::-webkit-scrollbar-thumb:hover {
        background: #025469;
    }
    
    #messageInput {
        min-height: 50px;
        max-height: 150px;
        font-size: 15px;
        line-height: 1.5;
    }
    
    #sendButton {
        min-width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    #fileInput + label {
        min-width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chat-header {
        flex-shrink: 0;
    }
    
    @media (max-width: 768px) {
        .chat-container {
            height: calc(100vh - 150px);
            min-height: 500px;
        }
        
        .message-bubble {
            max-width: 85%;
        }
        
        .messages-container {
            padding: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="max-w-6xl mx-auto space-y-6">
    <!-- Back Button -->
    <div class="flex items-center">
        <a href="{{ route('admin.contact-messages.index') }}" 
           class="inline-flex items-center text-primary-medium hover:text-primary-dark transition-colors duration-200">
            <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            العودة إلى قائمة الرسائل
        </a>
    </div>

    <!-- Chat Container -->
    <div class="bg-white rounded-2xl shadow-2xl overflow-hidden chat-container">
        <!-- Chat Header -->
        <div class="chat-header bg-gradient-to-r from-primary-medium to-primary-dark text-white p-4 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="bg-white/20 p-2 rounded-full">
                    <span class="text-xl font-bold">{{ substr($contactMessage->name, 0, 1) }}</span>
                </div>
                <div>
                    <h2 class="text-lg font-bold">{{ $contactMessage->name }}</h2>
                    <p class="text-xs opacity-90">{{ $contactMessage->email }}</p>
                    @if($contactMessage->phone)
                    <p class="text-xs opacity-90">{{ $contactMessage->phone }}</p>
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if($contactMessage->phone)
                <a href="tel:{{ $contactMessage->phone }}" class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg text-sm transition-colors">
                    <i class="fas fa-phone ml-1"></i>
                    {{ $contactMessage->phone }}
                </a>
                @endif
                <a href="mailto:{{ $contactMessage->email }}" class="bg-white/20 hover:bg-white/30 px-3 py-1 rounded-lg text-sm transition-colors">
                    <i class="fas fa-envelope ml-1"></i>
                    {{ $contactMessage->email }}
                </a>
            </div>
        </div>

        <!-- Messages Container -->
        <div class="messages-container relative" id="messagesContainer">
            <div id="messagesList" style="display: flex; flex-direction: column; gap: 1rem;">
                <!-- Original Message -->
                <div class="message-bubble message-customer">
                    <div class="p-4">
                        @if($contactMessage->subject)
                        <div class="font-semibold mb-2 text-primary-medium">{{ $contactMessage->subject }}</div>
                        @endif
                        <div class="text-gray-800 whitespace-pre-wrap">{{ $contactMessage->message }}</div>
                        <div class="message-time text-gray-500">{{ $contactMessage->formatted_created_at }}</div>
                    </div>
                </div>

                <!-- Replies -->
                @forelse($contactMessage->replies as $reply)
                @include('admin.contact-messages.reply-item', ['reply' => $reply])
                @empty
                @endforelse
            </div>
            <div class="scroll-to-bottom" id="scrollToBottom" onclick="scrollToBottom()">
                <i class="fas fa-arrow-down"></i>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="chat-input-container">
            <form id="replyForm" enctype="multipart/form-data">
                @csrf
                <div class="flex items-end gap-2">
                    <!-- File Input -->
                    <label for="fileInput" class="bg-gray-100 hover:bg-gray-200 p-3 rounded-lg cursor-pointer transition-colors" title="إرسال ملف">
                        <i class="fas fa-paperclip text-gray-600"></i>
                        <input type="file" id="fileInput" name="file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                    </label>
                    
                    <!-- Image Preview -->
                    <div id="imagePreview" class="hidden relative">
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
                            placeholder="اكتب ردك..."
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
</div>
@endsection

@push('scripts')
<script>
let lastReplyId = {{ $contactMessage->replies->last()->id ?? 0 }};
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

function scrollToBottom() {
    const container = document.getElementById('messagesContainer');
    container.scrollTop = container.scrollHeight;
    isScrolledToBottom = true;
    document.getElementById('scrollToBottom').classList.remove('visible');
}

function handleKeyDown(e) {
    if (e.key === 'Enter' && !e.shiftKey) {
        e.preventDefault();
        document.getElementById('replyForm').dispatchEvent(new Event('submit'));
    }
}

// Polling for new messages
function startPolling() {
    pollingInterval = setInterval(function() {
        fetch(`{{ route('admin.contact-messages.replies', $contactMessage) }}?last_reply_id=${lastReplyId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.replies.length > 0) {
                data.replies.forEach(reply => {
                    addReplyToChat(reply);
                    lastReplyId = Math.max(lastReplyId, reply.id);
                });
                
                if (isScrolledToBottom) {
                    setTimeout(scrollToBottom, 100);
                }
            }
        })
        .catch(error => console.error('Polling error:', error));
    }, 3000);
}

// Add reply to chat
function addReplyToChat(reply) {
    const messagesList = document.getElementById('messagesList');
    const replyHtml = createReplyHTML(reply);
    messagesList.insertAdjacentHTML('beforeend', replyHtml);
}

function createReplyHTML(reply) {
    const isAdmin = reply.sender_type === 'admin';
    const bubbleClass = isAdmin ? 'message-admin' : 'message-customer';
    const time = new Date(reply.created_at).toLocaleString('ar-SA', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    let fileHtml = '';
    if (reply.file_path) {
        if (reply.is_image) {
            fileHtml = `
                <div class="file-preview">
                    <img src="${reply.file_url}" alt="${reply.file_name}" onclick="openImageModal('${reply.file_url}')" class="cursor-pointer">
                </div>
            `;
        } else if (reply.is_video) {
            fileHtml = `
                <div class="file-preview">
                    <video controls class="w-full max-h-64 rounded-lg">
                        <source src="${reply.file_url}" type="${reply.mime_type}">
                    </video>
                </div>
            `;
        } else if (reply.is_audio) {
            fileHtml = `
                <div class="file-preview">
                    <audio controls class="w-full">
                        <source src="${reply.file_url}" type="${reply.mime_type}">
                    </audio>
                </div>
            `;
        } else {
            fileHtml = `
                <div class="file-attachment">
                    <i class="fas fa-file"></i>
                    <div>
                        <div class="font-semibold">${reply.file_name}</div>
                        <a href="${reply.file_url}" download class="text-primary-medium hover:underline">تحميل</a>
                    </div>
                </div>
            `;
        }
    }
    
    return `
        <div class="message-bubble ${bubbleClass}">
            <div class="p-4">
                ${reply.message ? `<div class="${isAdmin ? 'text-white' : 'text-gray-800'} whitespace-pre-wrap">${reply.message}</div>` : ''}
                ${fileHtml}
                <div class="message-time ${isAdmin ? 'text-white/70' : 'text-gray-500'}">${time}</div>
            </div>
        </div>
    `;
}

// Form submit
document.getElementById('replyForm').addEventListener('submit', async function(e) {
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
        const response = await fetch('{{ route("admin.contact-messages.reply", $contactMessage) }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            addReplyToChat(data.data);
            lastReplyId = Math.max(lastReplyId, data.data.id);
            
            messageInput.value = '';
            messageInput.style.height = 'auto';
            document.getElementById('charCount').textContent = '0/5000';
            removeFile();
            
            scrollToBottom();
        } else {
            alert(data.message || 'حدث خطأ');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('حدث خطأ في إرسال الرسالة');
    } finally {
        sendButton.disabled = false;
        sendButton.innerHTML = '<i class="fas fa-paper-plane"></i>';
    }
});

// Open image modal
function openImageModal(imageUrl) {
    const modal = document.createElement('div');
    modal.className = 'fixed inset-0 bg-black bg-opacity-75 z-50 flex items-center justify-center p-4';
    modal.onclick = function(e) {
        if (e.target === modal) {
            document.body.removeChild(modal);
        }
    };
    
    modal.innerHTML = `
        <div class="relative max-w-4xl max-h-full">
            <button onclick="this.closest('.fixed').remove()" class="absolute top-4 right-4 text-white bg-black/50 rounded-full p-2 hover:bg-black/70">
                <i class="fas fa-times"></i>
            </button>
            <img src="${imageUrl}" alt="Preview" class="max-w-full max-h-[90vh] rounded-lg">
        </div>
    `;
    
    document.body.appendChild(modal);
}
</script>
@endpush
