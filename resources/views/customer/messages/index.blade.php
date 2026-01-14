@extends('customer.layouts.app')

@section('title', __('messages.messages'))
@section('page-title', __('messages.messages'))
@section('page-subtitle', __('messages.communicate_with_admin'))

@push('styles')
<style>
    /* CSS Variables for Consistency */
    :root {
        --primary-gradient: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        --primary-gradient-hover: linear-gradient(135deg, #025469 0%, #08788B 50%, #6366f1 100%);
        --whatsapp-gradient: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
        --success-gradient: linear-gradient(135deg, #10b981 0%, #059669 100%);
        --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.15);
        --shadow-lg: 0 10px 30px rgba(0, 0, 0, 0.2);
        --shadow-xl: 0 20px 60px rgba(0, 0, 0, 0.1);
        --transition-base: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --border-radius: 1.25rem;
        --border-radius-sm: 0.75rem;
    }
    
    /* Main Container */
    .messages-page-container {
        max-width: 90rem;
        margin: 0 auto;
        padding: 0 1rem;
    }
    
    .chat-container {
        height: calc(100vh - 280px);
        min-height: 650px;
        max-height: 850px;
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-xl);
        border-radius: var(--border-radius);
        overflow: hidden;
        background: white;
    }
    
    /* Chat Header */
    .chat-header {
        background: var(--primary-gradient);
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow: var(--shadow-sm);
        position: relative;
        overflow: hidden;
    }
    
    .chat-header::before {
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
    
    .chat-header-content {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .chat-header-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .chat-header-icon {
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: var(--transition-base);
        flex-shrink: 0;
    }
    
    .chat-header-icon:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: rotate(5deg) scale(1.05);
    }
    
    .chat-header-icon i {
        font-size: 1.5rem;
        color: white;
    }
    
    .chat-header-info h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fef08a;
        margin-bottom: 0.25rem;
    }
    
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
        0%, 100% { box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.2); }
        50% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
    }
    
    .status-text {
        font-size: 0.875rem;
        color: #fef08a;
        opacity: 0.95;
        font-weight: 500;
    }
    
    .chat-header-actions {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .order-badge {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.875rem;
        color: white;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
    }
    
    .btn-header {
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        padding: 0.625rem 1.25rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.875rem;
        color: white;
        font-weight: 600;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-sm);
        text-decoration: none;
    }
    
    .btn-header:hover {
        background: rgba(255, 255, 255, 0.3);
        transform: scale(1.05);
        box-shadow: var(--shadow-md);
    }
    
    /* Messages Container */
    .messages-container {
        flex: 1;
        overflow-y: auto;
        padding: 1.5rem;
        background: linear-gradient(to bottom, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%);
        position: relative;
    }
    
    .messages-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    /* Message Bubbles */
    .message-wrapper {
        display: flex;
        animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }
    
    .message-wrapper.customer {
        justify-content: flex-end;
    }
    
    .message-wrapper.admin {
        justify-content: flex-start;
    }
    
    .message-bubble {
        max-width: 75%;
        word-wrap: break-word;
        position: relative;
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
    
    .message-customer {
        background: var(--whatsapp-gradient);
        color: white;
        border-radius: var(--border-radius) var(--border-radius) 0.25rem var(--border-radius);
        box-shadow: 0 4px 12px rgba(37, 211, 102, 0.3), var(--shadow-sm);
        padding: 1rem;
    }
    
    .message-admin {
        background: white;
        color: #1f2937;
        border-radius: var(--border-radius) var(--border-radius) var(--border-radius) 0.25rem;
        box-shadow: var(--shadow-md);
        border: 1px solid rgba(0, 0, 0, 0.05);
        padding: 1rem;
    }
    
    .message-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .message-sender {
        font-size: 0.75rem;
        font-weight: 700;
        opacity: 0.9;
    }
    
    .message-order-ref {
        font-size: 0.75rem;
        opacity: 0.7;
    }
    
    .message-content {
        font-size: 0.875rem;
        line-height: 1.6;
        margin-bottom: 0.5rem;
        white-space: pre-wrap;
    }
    
    .message-time {
        font-size: 0.7rem;
        opacity: 0.75;
        margin-top: 0.5rem;
        font-weight: 500;
    }
    
    /* File Attachments */
    .file-preview {
        border-radius: var(--border-radius-sm);
        overflow: hidden;
        margin-top: 0.75rem;
        box-shadow: var(--shadow-sm);
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
    
    .file-attachment {
        background: rgba(255, 255, 255, 0.15);
        padding: 1rem;
        border-radius: var(--border-radius-sm);
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
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        background: var(--primary-gradient);
        width: 100px;
        height: 100px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: var(--shadow-lg);
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
    
    .empty-state-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }
    
    .empty-state-description {
        font-size: 0.875rem;
        color: #64748b;
    }
    
    /* Scroll to Bottom Button */
    .scroll-to-bottom {
        position: absolute;
        bottom: 100px;
        right: 30px;
        background: var(--whatsapp-gradient);
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
        transition: var(--transition-base);
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
        0% { opacity: 0; transform: scale(0.3); }
        50% { transform: scale(1.05); }
        70% { transform: scale(0.9); }
        100% { opacity: 1; transform: scale(1); }
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
    
    .typing-dots {
        display: inline-flex;
        gap: 0.25rem;
        margin-left: 0.5rem;
    }
    
    .typing-dots span {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #08788B;
        animation: typingDot 1.4s ease-in-out infinite;
    }
    
    .typing-dots span:nth-child(1) { animation-delay: 0s; }
    .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
    .typing-dots span:nth-child(3) { animation-delay: 0.4s; }
    
    @keyframes typingDot {
        0%, 60%, 100% { transform: translateY(0); opacity: 0.7; }
        30% { transform: translateY(-10px); opacity: 1; }
    }
    
    /* Chat Input Container */
    .chat-input-container {
        background: white;
        border-top: 2px solid #e5e7eb;
        padding: 1.25rem;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .input-group {
        display: flex;
        align-items: flex-end;
        gap: 0.75rem;
    }
    
    .file-input-btn {
        background: #f8fafc;
        border: 2px solid #e5e7eb;
        padding: 0.875rem;
        border-radius: var(--border-radius-sm);
        cursor: pointer;
        transition: var(--transition-base);
        flex-shrink: 0;
    }
    
    .file-input-btn:hover {
        background: #f1f5f9;
        border-color: #08788B;
        transform: scale(1.05);
    }
    
    .file-input-btn i {
        font-size: 1.125rem;
        color: #64748b;
    }
    
    .image-preview {
        position: relative;
        display: inline-block;
        flex-shrink: 0;
    }
    
    .image-preview img {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: var(--border-radius-sm);
        border: 2px solid #e5e7eb;
    }
    
    .message-input-wrapper {
        flex: 1;
        position: relative;
    }
    
    .message-input {
        width: 100%;
        border: 2px solid #e5e7eb;
        border-radius: var(--border-radius-sm);
        padding: 0.875rem 3rem 0.875rem 1rem;
        resize: none;
        transition: var(--transition-base);
        font-family: inherit;
        font-size: 0.875rem;
        line-height: 1.5;
    }
    
    .message-input:focus {
        outline: none;
        border-color: #08788B;
        box-shadow: 0 0 0 3px rgba(8, 120, 139, 0.1);
    }
    
    .char-count {
        position: absolute;
        bottom: 0.75rem;
        right: 0.75rem;
        font-size: 0.75rem;
        color: #94a3b8;
        font-weight: 500;
        pointer-events: none;
    }
    
    .char-count.warning {
        color: #ef4444;
    }
    
    .send-button {
        background: var(--primary-gradient);
        padding: 0.875rem 1.25rem;
        border-radius: var(--border-radius-sm);
        color: white;
        border: none;
        cursor: pointer;
        transition: var(--transition-base);
        box-shadow: var(--shadow-md);
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .send-button:hover:not(:disabled) {
        background: var(--primary-gradient-hover);
        transform: translateY(-2px);
        box-shadow: var(--shadow-lg);
    }
    
    .send-button:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }
    
    .file-info {
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border: 2px solid #e5e7eb;
        border-radius: var(--border-radius-sm);
        padding: 0.875rem;
        margin-top: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .file-info i {
        color: #08788B;
        font-size: 1.125rem;
    }
    
    .file-info-name {
        flex: 1;
        font-size: 0.875rem;
        font-weight: 500;
        color: #475569;
    }
    
    /* Contact Card */
    .contact-card {
        background: var(--primary-gradient);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-lg);
        position: relative;
        overflow: hidden;
        margin-top: 1.5rem;
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
    
    .contact-card-content {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .contact-card-left {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .contact-card-icon {
        width: 64px;
        height: 64px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: var(--shadow-sm);
    }
    
    .contact-card-icon i {
        font-size: 2rem;
        color: white;
    }
    
    .contact-card-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #fef08a;
        margin-bottom: 0.25rem;
    }
    
    .contact-card-info p {
        font-size: 0.875rem;
        color: #fef08a;
        opacity: 0.95;
    }
    
    .btn-contact {
        background: white;
        color: #08788B;
        padding: 0.875rem 1.5rem;
        border-radius: var(--border-radius-sm);
        font-weight: 700;
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: var(--shadow-lg);
        text-decoration: none;
    }
    
    .btn-contact:hover {
        background: #f9fafb;
        transform: scale(1.05);
        box-shadow: var(--shadow-xl);
    }
    
    /* Orders Filter Sidebar */
    .orders-sidebar {
        background: white;
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-md);
        border: 1px solid #e5e7eb;
        margin-top: 1.5rem;
    }
    
    .orders-sidebar-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
    
    .orders-sidebar-header i {
        color: #08788B;
        font-size: 1.25rem;
    }
    
    .orders-sidebar-header h2 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
    }
    
    .order-filter-list {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .order-filter-item {
        padding: 0.875rem 1rem;
        border-radius: var(--border-radius-sm);
        transition: var(--transition-base);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
        color: inherit;
    }
    
    .order-filter-item:hover {
        transform: translateX(-4px);
    }
    
    .order-filter-item.active {
        background: var(--primary-gradient);
        color: white;
        box-shadow: var(--shadow-md);
    }
    
    .order-filter-item:not(.active) {
        background: #f8fafc;
        color: #64748b;
    }
    
    .order-filter-item:not(.active):hover {
        background: #f1f5f9;
        color: #475569;
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
        
        .input-group {
            flex-wrap: wrap;
        }
    }
</style>
@endpush

@section('content')
<div class="messages-page-container">
    <div class="space-y-6">
        <!-- Main Chat Container -->
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="chat-header-content">
                    <div class="chat-header-left">
                        <div class="chat-header-icon">
                            <i class="fas fa-headset"></i>
                        </div>
                        <div>
                            <h2 class="chat-header-info">{{ __('messages.support_team') }}</h2>
                            <div class="status-indicator">
                                <span class="status-dot"></span>
                                <span class="status-text" id="statusText">متصل الآن</span>
                            </div>
                        </div>
                    </div>
                    <div class="chat-header-actions">
                        @if(request('order_id'))
                        <div class="order-badge">
                            <i class="fas fa-shopping-cart"></i>
                            <span>طلب #{{ \App\Models\Order::find(request('order_id'))->order_number ?? request('order_id') }}</span>
                        </div>
                        @endif
                        <a href="{{ route('home') }}#contact" class="btn-header" title="{{ __('messages.contact_us') }}">
                            <i class="fas fa-envelope"></i>
                            <span>{{ __('messages.contact_us') }}</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Messages Container -->
            <div class="messages-container relative" id="messagesContainer">
                <div class="messages-list" id="messagesList">
                    @forelse($messages as $message)
                    @include('customer.messages.message-item', ['message' => $message])
                    @empty
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-comments"></i>
                        </div>
                        <p class="empty-state-title">{{ __('messages.no_messages_yet') }}</p>
                        <p class="empty-state-description">ابدأ المحادثة مع فريق الدعم</p>
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
                    <span>فريق الدعم يكتب</span>
                    <span class="typing-dots">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </span>
            </div>

            <!-- Chat Input -->
            <div class="chat-input-container">
                <form id="messageForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_id" value="{{ request('order_id') }}">
                    <div class="input-group">
                        <label for="fileInput" class="file-input-btn" title="إرسال ملف">
                            <i class="fas fa-paperclip"></i>
                            <input type="file" id="fileInput" name="file" accept="image/*,video/*,audio/*,.pdf,.doc,.docx,.xls,.xlsx" class="hidden">
                        </label>
                        
                        <div id="imagePreview" class="image-preview hidden">
                            <img id="previewImage" src="" alt="Preview">
                        </div>
                        
                        <div class="message-input-wrapper">
                            <textarea 
                                name="message" 
                                id="messageInput"
                                rows="1"
                                class="message-input"
                                placeholder="{{ __('messages.type_your_message') }}"
                                onkeydown="handleKeyDown(event)"></textarea>
                            <div class="char-count" id="charCount">0/5000</div>
                        </div>
                        
                        <button type="submit" id="sendButton" class="send-button">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </div>
                    
                    <div id="fileInfo" class="file-info hidden">
                        <i class="fas fa-file"></i>
                        <span id="fileName" class="file-info-name"></span>
                    </div>
                </form>
            </div>
        </div>

        <!-- Contact Admin Card -->
        <div class="contact-card text-white">
            <div class="contact-card-content">
                <div class="contact-card-left">
                    <div class="contact-card-icon">
                        <i class="fas fa-headset"></i>
                    </div>
                    <div class="contact-card-info">
                        <h3>{{ __('messages.communicate_with_admin') }}</h3>
                        <p>تواصل معنا مباشرة عبر نموذج الاتصال</p>
                    </div>
                </div>
                <a href="{{ route('home') }}#contact" class="btn-contact">
                    <i class="fas fa-envelope"></i>
                    <span>{{ __('messages.contact_us') }}</span>
                </a>
            </div>
        </div>

        <!-- Orders Filter Sidebar -->
        <div class="orders-sidebar">
            <div class="orders-sidebar-header">
                <i class="fas fa-filter"></i>
                <h2>{{ __('messages.filter_by_order') }}</h2>
            </div>
            <div class="order-filter-list">
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
</div>
@endsection

@push('scripts')
<script>
// Chat Messages Manager
const ChatMessages = {
    lastMessageId: {{ $messages->last()->id ?? 0 }},
    pollingInterval: null,
    isScrolledToBottom: true,
    
    init() {
        this.scrollToBottom();
        this.startPolling();
        this.setupEventListeners();
    },
    
    setupEventListeners() {
        // File input handler
        document.getElementById('fileInput').addEventListener('change', (e) => {
            this.handleFileSelect(e.target.files[0]);
        });
        
        // Character counter
        const messageInput = document.getElementById('messageInput');
        messageInput.addEventListener('input', () => {
            const count = messageInput.value.length;
            const charCount = document.getElementById('charCount');
            charCount.textContent = `${count}/5000`;
            charCount.classList.toggle('warning', count > 4500);
        });
        
        // Auto-resize textarea
        messageInput.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = (this.scrollHeight) + 'px';
        });
        
        // Scroll detection
        document.getElementById('messagesContainer').addEventListener('scroll', () => {
            const container = document.getElementById('messagesContainer');
            this.isScrolledToBottom = container.scrollHeight - container.scrollTop <= container.clientHeight + 100;
            document.getElementById('scrollToBottom').classList.toggle('visible', !this.isScrolledToBottom);
        });
        
        // Form submit
        document.getElementById('messageForm').addEventListener('submit', (e) => {
            e.preventDefault();
            this.handleSubmit();
        });
    },
    
    handleFileSelect(file) {
        if (!file) return;
        
        if (file.size > 10 * 1024 * 1024) {
            alert('حجم الملف كبير جداً. الحد الأقصى 10MB');
            return;
        }
        
        document.getElementById('fileInfo').classList.remove('hidden');
        document.getElementById('fileName').textContent = file.name;
        
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                document.getElementById('previewImage').src = e.target.result;
                document.getElementById('imagePreview').classList.remove('hidden');
            };
            reader.readAsDataURL(file);
        }
    },
    
    removeFile() {
        document.getElementById('fileInput').value = '';
        document.getElementById('fileInfo').classList.add('hidden');
        document.getElementById('imagePreview').classList.add('hidden');
    },
    
    removeImagePreview() {
        document.getElementById('imagePreview').classList.add('hidden');
        this.removeFile();
    },
    
    handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.getElementById('messageForm').dispatchEvent(new Event('submit'));
        }
    },
    
    scrollToBottom() {
        const container = document.getElementById('messagesContainer');
        container.scrollTop = container.scrollHeight;
        this.isScrolledToBottom = true;
        document.getElementById('scrollToBottom').classList.remove('visible');
    },
    
    startPolling() {
        this.pollingInterval = setInterval(() => {
            fetch(`{{ route("customer.messages.get") }}?order_id={{ request("order_id") }}&last_message_id=${this.lastMessageId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.messages.length > 0) {
                        data.messages.forEach(message => {
                            this.addMessageToChat(message);
                            this.lastMessageId = Math.max(this.lastMessageId, message.id);
                        });
                        
                        if (this.isScrolledToBottom) {
                            setTimeout(() => this.scrollToBottom(), 100);
                        }
                    }
                })
                .catch(error => console.error('Error:', error));
        }, 3000);
    },
    
    addMessageToChat(message) {
        const messagesList = document.getElementById('messagesList');
        const emptyState = messagesList.querySelector('.empty-state');
        if (emptyState) emptyState.remove();
        
        const messageDiv = document.createElement('div');
        messageDiv.className = `message-wrapper ${message.sender_type}`;
        messageDiv.innerHTML = this.getMessageHTML(message);
        messagesList.appendChild(messageDiv);
    },
    
    getMessageHTML(message) {
        const isCustomer = message.sender_type === 'customer';
        const senderName = isCustomer ? '{{ __("messages.you") }}' : (message.admin?.name || '{{ __("messages.admin") }}');
        const time = new Date(message.created_at).toLocaleTimeString('ar-SA', { hour: '2-digit', minute: '2-digit' });
        
        let fileHTML = '';
        if (message.file_path) {
            if (message.file_type === 'image') {
                fileHTML = `<div class="file-preview"><img src="${message.file_url}" alt="${message.file_name}" class="rounded-lg cursor-pointer" onclick="ChatMessages.openImageModal('${message.file_url}')"></div>`;
            } else {
                const icon = message.file_type === 'video' ? 'fa-video' : 
                            message.file_type === 'audio' ? 'fa-music' : 'fa-file';
                fileHTML = `
                    <div class="file-attachment">
                        <i class="fas ${icon}"></i>
                        <div class="flex-1">
                            <div class="font-semibold">${this.escapeHtml(message.file_name)}</div>
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
            <div class="message-bubble message-${message.sender_type}">
                <div class="message-header">
                    <span class="message-sender">${this.escapeHtml(senderName)}</span>
                    ${message.order ? `<span class="message-order-ref">• {{ __('messages.order') }} #${message.order.order_number}</span>` : ''}
                </div>
                ${message.message ? `<p class="message-content">${this.escapeHtml(message.message)}</p>` : ''}
                ${fileHTML}
                <p class="message-time">${time}</p>
            </div>
        `;
    },
    
    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    },
    
    async handleSubmit() {
        const formData = new FormData(document.getElementById('messageForm'));
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
                this.addMessageToChat(data.data);
                this.lastMessageId = Math.max(this.lastMessageId, data.data.id);
                
                messageInput.value = '';
                messageInput.style.height = 'auto';
                document.getElementById('charCount').textContent = '0/5000';
                document.getElementById('charCount').classList.remove('warning');
                this.removeFile();
                
                this.scrollToBottom();
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
    },
    
    openImageModal(imageUrl) {
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
        modal.addEventListener('click', (e) => {
            if (e.target === modal) modal.remove();
        });
    }
};

// Global functions for onclick handlers
function scrollToBottom() {
    ChatMessages.scrollToBottom();
}

function removeFile() {
    ChatMessages.removeFile();
}

function removeImagePreview() {
    ChatMessages.removeImagePreview();
}

function handleKeyDown(e) {
    ChatMessages.handleKeyDown(e);
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', () => {
    ChatMessages.init();
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    if (ChatMessages.pollingInterval) {
        clearInterval(ChatMessages.pollingInterval);
    }
});
</script>
@endpush
