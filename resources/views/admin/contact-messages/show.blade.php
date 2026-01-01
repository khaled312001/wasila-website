@extends('admin.layouts.app')

@section('title', 'رسالة الاتصال')
@section('page-title', 'رسالة الاتصال')

@push('styles')
<style>
    .chat-container {
        height: calc(100vh - 250px);
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
    
    .message-admin {
        background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
        color: white;
        margin-left: auto;
        border-radius: 1rem 1rem 0.25rem 1rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    }
    
    .message-customer {
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
        cursor: pointer;
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
        color: var(--primary-medium);
    }
    
    .chat-input-container {
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 1rem;
    }
    
    .scroll-to-bottom {
        position: absolute;
        bottom: 80px;
        right: 20px;
        background: var(--primary-medium);
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
        <div class="bg-gradient-to-r from-primary-medium to-primary-dark text-white p-4 flex items-center justify-between">
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
            <div class="space-y-3" id="messagesList">
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
