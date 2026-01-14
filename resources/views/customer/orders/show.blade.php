@extends('customer.layouts.app')

@section('title', __('messages.order_details'))
@section('page-title', __('messages.order_details'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@push('styles')
<style>
    /* Main Container */
    .order-details-page {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    .order-details-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    @media (min-width: 1024px) {
        .order-details-grid {
            grid-template-columns: 2fr 1fr;
            align-items: start;
        }
    }
    
    /* Order Header Card */
    .order-header-card {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        border-radius: 1.5rem;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 20px 60px rgba(8, 120, 139, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .order-header-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .order-header-content {
        position: relative;
        z-index: 10;
    }
    
    .order-number-display {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .order-number-display i {
        font-size: 2rem;
        opacity: 0.9;
    }
    
    .order-status-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-top: 1.5rem;
        align-items: center;
    }
    
    /* Modern Card Styles */
    .modern-card {
        background: white;
        border-radius: 1.25rem;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .modern-card::before {
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
    
    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.12);
    }
    
    .modern-card:hover::before {
        transform: scaleX(1);
    }
    
    .card-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .card-icon {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.5rem;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .card-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin: 0;
    }
    
    /* Status Badges */
    .status-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 2rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-transform: capitalize;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .status-badge-modern.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-badge-modern.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .status-badge-modern.processing {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .status-badge-modern.confirmed {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .status-badge-modern.paid {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-badge-modern.unpaid {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    
    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    @media (min-width: 640px) {
        .info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    .info-item {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        transition: all 0.2s ease;
    }
    
    .info-item:hover {
        background: #f1f5f9;
        border-color: #cbd5e1;
        transform: translateX(4px);
    }
    
    .info-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .info-label i {
        color: #08788B;
        font-size: 0.875rem;
    }
    
    .info-value {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .info-value.amount {
        font-size: 1.5rem;
        color: #10b981;
        font-weight: 700;
    }
    
    /* Documentation Section */
    .documentation-grid {
        display: grid;
        gap: 1.5rem;
        margin-top: 1.5rem;
    }
    
    .video-card-modern {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
    }
    
    .video-card-modern:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
    }
    
    .video-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1rem;
        gap: 1rem;
    }
    
    .video-title-section {
        flex: 1;
    }
    
    .video-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .video-title i {
        color: #08788B;
    }
    
    .video-description {
        color: #64748b;
        font-size: 0.9375rem;
        margin-bottom: 1rem;
    }
    
    .video-meta-modern {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }
    
    .meta-badge-modern {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.8125rem;
        color: #475569;
    }
    
    .meta-badge-modern i {
        color: #08788B;
    }
    
    .video-actions-modern {
        display: flex;
        gap: 0.75rem;
    }
    
    .video-action-btn-modern {
        width: 48px;
        height: 48px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        text-decoration: none;
    }
    
    .video-action-btn-modern:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }
    
    .video-action-btn-modern.open {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
    }
    
    .video-action-btn-modern.download {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .video-player-modern {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 1rem;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        border: 2px solid #334155;
        margin-top: 1rem;
    }
    
    .video-player-modern video {
        width: 100%;
        max-height: 600px;
        min-height: 400px;
        display: block;
    }
    
    /* Sidebar */
    .sidebar-modern {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }
    
    @media (min-width: 1024px) {
        .sidebar-modern {
            position: sticky;
            top: 2rem;
        }
    }
    
    /* Action Buttons */
    .action-buttons-modern {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .action-btn-modern {
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
    
    .action-btn-modern::before {
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
    
    .action-btn-modern:hover::before {
        width: 300px;
        height: 300px;
    }
    
    .action-btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .action-btn-modern i,
    .action-btn-modern span {
        position: relative;
        z-index: 1;
    }
    
    .action-btn-modern.primary {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        color: white;
    }
    
    .action-btn-modern.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .action-btn-modern.info {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    /* Customer Info */
    .customer-info-modern {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }
    
    .customer-info-item-modern {
        padding: 1rem;
        background: #f8fafc;
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
    }
    
    .customer-info-label-modern {
        font-size: 0.875rem;
        color: #64748b;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .customer-info-value-modern {
        font-size: 1rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 3rem 2rem;
        color: #64748b;
    }
    
    .empty-state i {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1rem;
    }
    
    .empty-state h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.5rem;
    }
    
    .empty-state p {
        color: #64748b;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .order-header-card {
            padding: 1.5rem;
        }
        
        .order-number-display {
            font-size: 1.75rem;
        }
        
        .modern-card {
            padding: 1.5rem;
        }
        
        .card-icon {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }
        
        .card-title {
            font-size: 1.25rem;
        }
        
        .info-grid {
            grid-template-columns: 1fr;
        }
        
        .video-header {
            flex-direction: column;
        }
        
        .video-actions-modern {
            width: 100%;
            justify-content: flex-start;
        }
    }
</style>
@endpush

@section('content')
<div class="order-details-page">
    <!-- Order Header -->
    <div class="order-header-card">
        <div class="order-header-content">
            <div class="order-number-display">
                <i class="fas fa-receipt"></i>
                <span>طلب رقم #{{ $order->order_number }}</span>
            </div>
            <p class="text-white opacity-90 text-lg">{{ __('messages.order_date') }}: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            
            <div class="order-status-row">
                <span class="status-badge-modern {{ $order->status }}">
                    @if($order->status === 'completed')
                        <i class="fas fa-check-circle"></i>
                    @elseif($order->status === 'pending')
                        <i class="fas fa-clock"></i>
                    @elseif($order->status === 'confirmed' && $order->payment_status === 'pending')
                        <i class="fas fa-credit-card"></i>
                    @elseif($order->status === 'processing')
                        <i class="fas fa-spinner fa-spin"></i>
                    @else
                        <i class="fas fa-info-circle"></i>
                    @endif
                    @if($order->status === 'confirmed' && $order->payment_status === 'pending')
                        في انتظار الدفع
                    @else
                        {{ __('messages.' . $order->status) }}
                    @endif
                </span>
                
                <span class="status-badge-modern {{ $order->payment_status }}">
                    @if($order->payment_status === 'paid')
                        <i class="fas fa-check-circle"></i>
                    @else
                        <i class="fas fa-times-circle"></i>
                    @endif
                    {{ __('messages.' . $order->payment_status) }}
                </span>
            </div>
        </div>
    </div>
    
    <!-- Main Grid -->
    <div class="order-details-grid">
        <!-- Left Column - Main Content -->
        <div class="space-y-6">
            <!-- Order Information -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <h2 class="card-title">{{ __('messages.order_information') }}</h2>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-hashtag"></i>
                            {{ __('messages.order_number') }}
                        </div>
                        <div class="info-value">#{{ $order->order_number }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-box"></i>
                            {{ __('messages.service') }}
                        </div>
                        <div class="info-value">{{ $order->service_name ?? __('messages.service') }}</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-money-bill-wave"></i>
                            {{ __('messages.amount') }}
                        </div>
                        <div class="info-value amount">{{ number_format($order->total_amount, 2) }} ر.س</div>
                    </div>
                    
                    <div class="info-item">
                        <div class="info-label">
                            <i class="fas fa-calendar-alt"></i>
                            {{ __('messages.order_date') }}
                        </div>
                        <div class="info-value">{{ $order->created_at->format('Y-m-d H:i') }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Documentation Videos -->
            @if($order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0)
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-video"></i>
                    </div>
                    <div>
                        <h2 class="card-title">{{ __('messages.order_documentation') }}</h2>
                        <p class="text-sm text-gray-600 mt-1">فيديوهات توثيق تنفيذ الطلب</p>
                    </div>
                </div>
                
                <div class="documentation-grid">
                    @foreach($order->documentation->where('is_visible_to_customer', true) as $doc)
                    <div class="video-card-modern">
                        <div class="video-header">
                            <div class="video-title-section">
                                <h3 class="video-title">
                                    <i class="fas fa-file-video"></i>
                                    {{ $doc->title ?? 'فيديو توثيق' }}
                                </h3>
                                @if($doc->description)
                                <p class="video-description">{{ $doc->description }}</p>
                                @endif
                                <div class="video-meta-modern">
                                    <span class="meta-badge-modern">
                                        <i class="fas fa-calendar-alt"></i>
                                        {{ $doc->created_at->format('Y-m-d H:i') }}
                                    </span>
                                    @if($doc->formatted_file_size)
                                    <span class="meta-badge-modern">
                                        <i class="fas fa-hdd"></i>
                                        {{ $doc->formatted_file_size }}
                                    </span>
                                    @endif
                                    @if($doc->formatted_duration)
                                    <span class="meta-badge-modern">
                                        <i class="fas fa-clock"></i>
                                        {{ $doc->formatted_duration }}
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="video-actions-modern">
                                <a href="{{ $doc->video_url }}" target="_blank" 
                                   class="video-action-btn-modern open" title="فتح في نافذة جديدة">
                                    <i class="fas fa-external-link-alt"></i>
                                </a>
                                <a href="{{ $doc->video_url }}" download
                                   class="video-action-btn-modern download" title="تحميل الفيديو">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        </div>
                        
                        @if($doc->video_path)
                        <div class="video-player-modern">
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
            @else
            <div class="modern-card">
                <div class="empty-state">
                    <i class="fas fa-video-slash"></i>
                    <h3>لا توجد فيديوهات توثيق</h3>
                    <p>سيتم إضافة فيديوهات توثيق تنفيذ الطلب قريباً</p>
                </div>
            </div>
            @endif
        </div>
        
        <!-- Right Column - Sidebar -->
        <div class="sidebar-modern">
            <!-- Actions Card -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h2 class="card-title">{{ __('messages.actions') }}</h2>
                </div>
                
                <div class="action-buttons-modern">
                    <a href="{{ route('customer.orders.invoice', $order) }}" class="action-btn-modern primary">
                        <i class="fas fa-file-invoice"></i>
                        <span>{{ __('messages.view_invoice') }}</span>
                    </a>
                    <a href="{{ route('customer.orders.invoice.download', $order) }}" class="action-btn-modern success">
                        <i class="fas fa-download"></i>
                        <span>{{ __('messages.download_invoice') }}</span>
                    </a>
                    <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" class="action-btn-modern info">
                        <i class="fas fa-comments"></i>
                        <span>{{ __('messages.contact_about_order') }}</span>
                    </a>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="modern-card">
                <div class="card-header">
                    <div class="card-icon">
                        <i class="fas fa-user"></i>
                    </div>
                    <h2 class="card-title">{{ __('messages.customer_information') }}</h2>
                </div>
                
                <div class="customer-info-modern">
                    <div class="customer-info-item-modern">
                        <div class="customer-info-label-modern">{{ __('messages.name') }}</div>
                        <div class="customer-info-value-modern">{{ $order->customer_name }}</div>
                    </div>
                    <div class="customer-info-item-modern">
                        <div class="customer-info-label-modern">{{ __('messages.email') }}</div>
                        <div class="customer-info-value-modern">{{ $order->customer_email }}</div>
                    </div>
                    @if($order->customer_phone)
                    <div class="customer-info-item-modern">
                        <div class="customer-info-label-modern">{{ __('messages.phone') }}</div>
                        <div class="customer-info-value-modern">{{ $order->customer_phone }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
