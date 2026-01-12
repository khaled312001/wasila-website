@extends('customer.layouts.app')

@section('title', __('messages.order_details'))
@section('page-title', __('messages.order_details'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@push('styles')
<style>
    /* Order Details Container */
    .order-details-container {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
        .order-details-container {
            grid-template-columns: 3fr 1fr;
            align-items: start;
        }
        
        /* Main content on the left */
        .order-details-container > .main-content {
            grid-column: 1;
        }
        
        /* Sidebar on the right */
        .order-details-container > .sidebar {
            grid-column: 2;
            position: sticky;
            top: 1.5rem;
        }
    }
    
    /* Documentation Section */
    .documentation-section {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 50%, #f1f5f9 100%);
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        position: relative;
        overflow: hidden;
    }
    
    .documentation-section::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(8, 120, 139, 0.05) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .documentation-header {
        display: flex;
        align-items: center;
        gap: 1.25rem;
        margin-bottom: 2rem;
        position: relative;
        z-index: 10;
    }
    
    .documentation-icon {
        width: 70px;
        height: 70px;
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        border-radius: 1.25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 24px rgba(8, 120, 139, 0.3);
        transition: all 0.3s ease;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .documentation-icon:hover {
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 12px 32px rgba(8, 120, 139, 0.4);
    }
    
    .documentation-icon i {
        font-size: 2rem;
        color: white;
    }
    
    /* Video Card */
    .video-card {
        background: white;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
    }
    
    .video-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .video-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #08788B 0%, #4f46e5 100%);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }
    
    .video-card:hover::before {
        transform: scaleX(1);
    }
    
    .video-info {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.25rem;
        gap: 1rem;
    }
    
    .video-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 0.5rem;
    }
    
    .video-title i {
        color: #08788B;
        font-size: 1.5rem;
    }
    
    .video-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-top: 0.75rem;
    }
    
    .meta-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.85rem;
        color: #475569;
    }
    
    .meta-badge i {
        color: #08788B;
    }
    
    .video-actions {
        display: flex;
        gap: 0.75rem;
    }
    
    .video-action-btn {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .video-action-btn:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .video-action-btn.open {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
    }
    
    .video-action-btn.download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    /* Video Player */
    .video-player-container {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border: 2px solid #334155;
        position: relative;
    }
    
    .video-player-container video {
        width: 100%;
        max-height: 600px;
        min-height: 400px;
        display: block;
    }
    
    /* Order Info Card */
    .order-info-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    .order-info-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .order-info-header i {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 0;
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s ease;
    }
    
    .info-row:hover {
        background: #f8fafc;
        margin: 0 -1rem;
        padding-left: 1rem;
        padding-right: 1rem;
        border-radius: 0.5rem;
    }
    
    .info-row:last-child {
        border-bottom: none;
    }
    
    .info-label {
        color: #64748b;
        font-size: 0.95rem;
        font-weight: 500;
    }
    
    .info-value {
        font-weight: 600;
        color: #1e293b;
        text-align: left;
    }
    
    /* Status Badge */
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: capitalize;
    }
    
    .status-badge.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-badge.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .status-badge.processing {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .status-badge.paid {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-badge.unpaid {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    /* Sidebar */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    /* Actions Card */
    .actions-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    .actions-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .actions-header i {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .action-buttons {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-radius: 1rem;
        font-weight: 600;
        font-size: 1rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        position: relative;
        overflow: hidden;
    }
    
    .action-btn::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }
    
    .action-btn:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .action-btn i {
        position: relative;
        z-index: 1;
    }
    
    .action-btn span {
        position: relative;
        z-index: 1;
    }
    
    /* View Invoice Button - Primary Color */
    .action-btn.view-invoice {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        color: white;
    }
    
    .action-btn.view-invoice:hover {
        background: linear-gradient(135deg, #025469 0%, #08788B 50%, #6366f1 100%);
    }
    
    /* Download Invoice Button - Green */
    .action-btn.download-invoice {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .action-btn.download-invoice:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
    }
    
    /* Contact About Order Button - Blue */
    .action-btn.contact-order {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .action-btn.contact-order:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
    }
    
    /* Customer Info Card */
    .customer-info-card {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    .customer-info-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .customer-info-header i {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
    }
    
    .customer-info-item {
        margin-bottom: 1.25rem;
    }
    
    .customer-info-item:last-child {
        margin-bottom: 0;
    }
    
    .customer-info-label {
        font-size: 0.85rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .customer-info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .order-details-container {
            grid-template-columns: 1fr;
        }
        
        .video-info {
            flex-direction: column;
        }
        
        .video-actions {
            width: 100%;
            justify-content: flex-start;
        }
    }
    
    @media (max-width: 768px) {
        .documentation-section,
        .order-info-card,
        .actions-card,
        .customer-info-card {
            padding: 1.5rem;
        }
        
        .documentation-icon {
            width: 60px;
            height: 60px;
        }
        
        .action-btn {
            padding: 0.875rem 1.25rem;
            font-size: 0.95rem;
        }
    }
</style>
@endpush

@section('content')
<div class="order-details-container">
    <!-- Main Content - Left Side -->
    <div class="main-content space-y-6">
        <!-- Documentation Videos Section -->
        @if($order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0)
        <div class="documentation-section">
            <div class="documentation-header">
                <div class="documentation-icon">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 mb-1">{{ __('messages.order_documentation') }}</h2>
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-info-circle text-primary-medium"></i>
                        فيديوهات توثيق تنفيذ الطلب
                    </p>
                </div>
            </div>
            
            <div class="space-y-6">
                @foreach($order->documentation->where('is_visible_to_customer', true) as $doc)
                <div class="video-card">
                    <div class="video-info">
                        <div class="flex-1">
                            <h3 class="video-title">
                                <i class="fas fa-file-video"></i>
                                {{ $doc->title ?? 'فيديو توثيق' }}
                            </h3>
                            @if($doc->description)
                            <p class="text-sm text-gray-600 mt-2 mb-3">{{ $doc->description }}</p>
                            @endif
                            <div class="video-meta">
                                <span class="meta-badge">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ $doc->created_at->format('Y-m-d H:i') }}
                                </span>
                                @if($doc->formatted_file_size)
                                <span class="meta-badge">
                                    <i class="fas fa-hdd"></i>
                                    {{ $doc->formatted_file_size }}
                                </span>
                                @endif
                                @if($doc->formatted_duration)
                                <span class="meta-badge">
                                    <i class="fas fa-clock"></i>
                                    {{ $doc->formatted_duration }}
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="video-actions">
                            <a href="{{ $doc->video_url }}" target="_blank" 
                               class="video-action-btn open" title="فتح في نافذة جديدة">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                            <a href="{{ $doc->video_url }}" download
                               class="video-action-btn download" title="تحميل الفيديو">
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    
                    @if($doc->video_path)
                    <div class="video-player-container">
                        <video controls class="w-full" style="max-height: 600px; min-height: 400px;" preload="metadata" playsinline webkit-playsinline data-doc-id="{{ $doc->id }}">
                            <source src="{{ $doc->video_url }}" type="video/mp4">
                            <source src="{{ $doc->video_url }}" type="video/webm">
                            <source src="{{ $doc->video_url }}" type="video/ogg">
                            <div class="text-white p-8 text-center bg-gray-900">
                                <i class="fas fa-exclamation-triangle text-4xl mb-4 text-yellow-400"></i>
                                <p class="text-lg mb-4">متصفحك لا يدعم تشغيل الفيديو</p>
                                <a href="{{ $doc->video_url }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl" download>
                                    <i class="fas fa-download ml-2"></i>
                                    اضغط هنا لتحميل الفيديو
                                </a>
                            </div>
                        </video>
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Order Information Card -->
        <div class="order-info-card">
            <div class="order-info-header">
                <i class="fas fa-info-circle"></i>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.order_information') }}</h2>
            </div>
            
            <div class="space-y-2">
                <div class="info-row">
                    <span class="info-label">{{ __('messages.order_number') }}:</span>
                    <span class="info-value">#{{ $order->order_number }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.service') }}:</span>
                    <span class="info-value">{{ $order->service_name ?? __('messages.service') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.amount') }}:</span>
                    <span class="info-value text-green-600 font-bold text-lg">{{ number_format($order->total_amount, 2) }} ر.س</span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.status') }}:</span>
                    <span class="status-badge {{ $order->status }}">
                        @if($order->status === 'completed')
                            <i class="fas fa-check-circle"></i>
                        @elseif($order->status === 'pending')
                            <i class="fas fa-clock"></i>
                        @elseif($order->status === 'processing')
                            <i class="fas fa-spinner fa-spin"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.payment_status') }}:</span>
                    <span class="status-badge {{ $order->payment_status }}">
                        @if($order->payment_status === 'paid')
                            <i class="fas fa-check-circle"></i>
                        @else
                            <i class="fas fa-times-circle"></i>
                        @endif
                        {{ __('messages.' . $order->payment_status) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">{{ __('messages.order_date') }}:</span>
                    <span class="info-value">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Actions Card -->
        <div class="actions-card">
            <div class="actions-header">
                <i class="fas fa-bolt"></i>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.actions') }}</h2>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('customer.orders.invoice', $order) }}" class="action-btn view-invoice">
                    <i class="fas fa-file-invoice"></i>
                    <span>{{ __('messages.view_invoice') }}</span>
                </a>
                <a href="{{ route('customer.orders.invoice.download', $order) }}" class="action-btn download-invoice">
                    <i class="fas fa-download"></i>
                    <span>{{ __('messages.download_invoice') }}</span>
                </a>
                <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" class="action-btn contact-order">
                    <i class="fas fa-comments"></i>
                    <span>{{ __('messages.contact_about_order') }}</span>
                </a>
            </div>
        </div>

        <!-- Customer Information Card -->
        <div class="customer-info-card">
            <div class="customer-info-header">
                <i class="fas fa-user"></i>
                <h2 class="text-2xl font-bold text-gray-900">{{ __('messages.customer_information') }}</h2>
            </div>
            
            <div class="space-y-4">
                <div class="customer-info-item">
                    <div class="customer-info-label">{{ __('messages.name') }}:</div>
                    <div class="customer-info-value">{{ $order->customer_name }}</div>
                </div>
                <div class="customer-info-item">
                    <div class="customer-info-label">{{ __('messages.email') }}:</div>
                    <div class="customer-info-value">{{ $order->customer_email }}</div>
                </div>
                @if($order->customer_phone)
                <div class="customer-info-item">
                    <div class="customer-info-label">{{ __('messages.phone') }}:</div>
                    <div class="customer-info-value">{{ $order->customer_phone }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enhance video player experience for customers
    document.addEventListener('DOMContentLoaded', function() {
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            // Add error handling
            video.addEventListener('error', function(e) {
                console.error('Video error:', e);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-white p-6 text-center bg-red-600 rounded-lg m-4';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-3xl mb-3"></i><p class="text-lg">حدث خطأ في تحميل الفيديو</p><p class="text-sm mt-2">يرجى المحاولة مرة أخرى أو تحميل الفيديو</p>';
                video.parentElement.appendChild(errorDiv);
            });
            
            // Track video view
            video.addEventListener('play', function() {
                if (video.dataset.docId) {
                    console.log('Video viewed:', video.dataset.docId);
                }
            });
            
            // Add loading indicator
            video.addEventListener('loadstart', function() {
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 rounded-xl z-10';
                loadingDiv.id = 'loading-' + (video.id || Math.random());
                loadingDiv.innerHTML = '<div class="text-white text-center"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>جاري تحميل الفيديو...</p></div>';
                video.parentElement.style.position = 'relative';
                video.parentElement.appendChild(loadingDiv);
            });
            
            // Remove loading indicator when video can play
            video.addEventListener('canplay', function() {
                const loadingDiv = video.parentElement.querySelector('[id^="loading-"]');
                if (loadingDiv) {
                    loadingDiv.style.transition = 'opacity 0.3s';
                    loadingDiv.style.opacity = '0';
                    setTimeout(() => loadingDiv.remove(), 300);
                }
            });
        });
    });
</script>
@endpush
@endsection
