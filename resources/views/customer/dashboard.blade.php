@extends('customer.layouts.app')

@section('title', __('messages.customer_dashboard'))
@section('page-title', __('messages.dashboard'))

@push('styles')
<style>
    /* Welcome Banner - Ultra Modern */
    .welcome-banner-modern {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #3CA6B4 100%);
        border-radius: 1.5rem;
        padding: 3rem;
        color: white;
        box-shadow: 0 20px 60px rgba(8, 120, 139, 0.3);
        position: relative;
        overflow: hidden;
        margin-bottom: 2rem;
    }
    
    .welcome-banner-modern::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.15) 0%, transparent 70%);
        animation: rotate 30s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .welcome-content {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 2rem;
    }
    
    .welcome-text-section h1 {
        font-size: 2.5rem;
        font-weight: 900;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .welcome-text-section h1 i {
        font-size: 2rem;
        animation: wave 2s ease-in-out infinite;
    }
    
    @keyframes wave {
        0%, 100% { transform: rotate(0deg); }
        25% { transform: rotate(20deg); }
        75% { transform: rotate(-20deg); }
    }
    
    .welcome-text-section p {
        font-size: 1.25rem;
        opacity: 0.95;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .welcome-icon-large {
        width: 120px;
        height: 120px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        animation: float 3s ease-in-out infinite;
        flex-shrink: 0;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }
    
    /* Stat Cards - Enhanced */
    .stat-card-ultra {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid #e5e7eb;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .stat-card-ultra::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, #08788B 0%, #4f46e5 100%);
        transform: scaleX(0);
        transition: transform 0.4s ease;
    }
    
    .stat-card-ultra:hover::before {
        transform: scaleX(1);
    }
    
    .stat-card-ultra:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-ultra.primary {
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        color: white;
        border-color: transparent;
    }
    
    .stat-card-ultra.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
        border-color: transparent;
    }
    
    .stat-card-ultra.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border-color: transparent;
    }
    
    .stat-card-ultra.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
        border-color: transparent;
    }
    
    .stat-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
    }
    
    .stat-icon-box {
        width: 64px;
        height: 64px;
        border-radius: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.75rem;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }
    
    .stat-card-ultra:hover .stat-icon-box {
        transform: scale(1.1) rotate(5deg);
    }
    
    .stat-value-modern {
        font-size: 3rem;
        font-weight: 900;
        line-height: 1;
        margin-bottom: 0.5rem;
        letter-spacing: -2px;
    }
    
    .stat-label-modern {
        font-size: 1rem;
        opacity: 0.9;
        font-weight: 600;
        margin-bottom: 0.75rem;
    }
    
    .stat-footer-modern {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.875rem;
        opacity: 0.85;
        padding-top: 0.75rem;
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    /* Dashboard Cards - Ultra Modern */
    .dashboard-card-ultra {
        background: white;
        border-radius: 1.5rem;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .dashboard-card-ultra::before {
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
    
    .dashboard-card-ultra:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
    }
    
    .dashboard-card-ultra:hover::before {
        transform: scaleX(1);
    }
    
    .card-header-ultra {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid #f1f5f9;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .card-title-ultra {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .card-title-ultra i {
        font-size: 1.5rem;
        color: #08788B;
    }
    
    /* Order Item - Ultra Modern */
    .order-item-ultra {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .order-item-ultra::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .order-item-ultra:hover {
        transform: translateX(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }
    
    .order-item-ultra:hover::before {
        transform: scaleY(1);
    }
    
    /* Message Item - Ultra Modern */
    .message-item-ultra {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid #e5e7eb;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .message-item-ultra:hover {
        transform: translateX(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: #cbd5e1;
    }
    
    /* Status Badge - Ultra Modern */
    .status-badge-ultra {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.8125rem;
        font-weight: 600;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .status-badge-ultra.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .status-badge-ultra.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .status-badge-ultra.processing {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .status-badge-ultra.confirmed {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    /* Button - Ultra Modern */
    .btn-ultra {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.5rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
    .btn-ultra:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
    
    .btn-ultra.primary {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
    }
    
    .btn-ultra.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    /* Empty State - Ultra Modern */
    .empty-state-ultra {
        text-align: center;
        padding: 3rem 2rem;
    }
    
    .empty-state-icon-ultra {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(8, 120, 139, 0.1) 0%, rgba(8, 120, 139, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .empty-state-icon-ultra:hover {
        transform: scale(1.1) rotate(5deg);
    }
    
    .empty-state-icon-ultra i {
        font-size: 2.5rem;
        color: #08788B;
    }
    
    /* Animations */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Responsive */
    @media (max-width: 1024px) {
        .welcome-banner-modern {
            padding: 2rem;
        }
        
        .welcome-text-section h1 {
            font-size: 2rem;
        }
        
        .welcome-icon-large {
            width: 100px;
            height: 100px;
            font-size: 2.5rem;
        }
        
        .stat-card-ultra {
            padding: 1.5rem;
        }
        
        .stat-value-modern {
            font-size: 2.5rem;
        }
    }
    
    @media (max-width: 768px) {
        .welcome-banner-modern {
            padding: 1.5rem;
        }
        
        .welcome-content {
            flex-direction: column;
            text-align: center;
        }
        
        .welcome-text-section h1 {
            font-size: 1.75rem;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .welcome-text-section p {
            font-size: 1rem;
            justify-content: center;
        }
        
        .welcome-icon-large {
            width: 80px;
            height: 80px;
            font-size: 2rem;
        }
        
        .stat-card-ultra {
            padding: 1.25rem;
        }
        
        .stat-icon-box {
            width: 56px;
            height: 56px;
            font-size: 1.5rem;
        }
        
        .stat-value-modern {
            font-size: 2rem;
        }
        
        .card-header-ultra {
            flex-direction: column;
            align-items: flex-start;
        }
        
        .dashboard-card-ultra {
            padding: 1.5rem;
        }
    }
    
    @media (max-width: 640px) {
        .welcome-banner-modern {
            padding: 1.25rem;
        }
        
        .welcome-text-section h1 {
            font-size: 1.5rem;
        }
        
        .stat-value-modern {
            font-size: 1.75rem;
        }
        
        .dashboard-card-ultra {
            padding: 1.25rem;
        }
        
        .order-item-ultra,
        .message-item-ultra {
            padding: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="welcome-banner-modern fade-in-up" style="animation-delay: 0.1s">
    <div class="welcome-content">
        <div class="welcome-text-section">
            <h1>
                <i class="fas fa-hand-sparkles"></i>
                <span>{{ __('messages.welcome_back') }}, {{ $customer->name }}!</span>
            </h1>
            <p>
                <i class="fas fa-tachometer-alt"></i>
                <span>{{ __('messages.dashboard_subtitle') }}</span>
            </p>
        </div>
        <div class="welcome-icon-large">
            <i class="fas fa-chart-line"></i>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card-ultra primary fade-in-up" style="animation-delay: 0.2s">
        <div class="stat-header">
            <div class="stat-icon-box">
                <i class="fas fa-shopping-bag"></i>
            </div>
        </div>
        <div class="stat-value-modern">{{ $stats['total_orders'] }}</div>
        <div class="stat-label-modern">{{ __('messages.total_orders') }}</div>
        <div class="stat-footer-modern">
            <i class="fas fa-chart-line"></i>
            <span>جميع الطلبات</span>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="stat-card-ultra warning fade-in-up" style="animation-delay: 0.3s">
        <div class="stat-header">
            <div class="stat-icon-box">
                <i class="fas fa-clock"></i>
            </div>
        </div>
        <div class="stat-value-modern">{{ $stats['pending_orders'] }}</div>
        <div class="stat-label-modern">{{ __('messages.pending_orders') }}</div>
        <div class="stat-footer-modern">
            <i class="fas fa-hourglass-half"></i>
            <span>قيد المراجعة</span>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card-ultra success fade-in-up" style="animation-delay: 0.4s">
        <div class="stat-header">
            <div class="stat-icon-box">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
        <div class="stat-value-modern">{{ $stats['completed_orders'] }}</div>
        <div class="stat-label-modern">{{ __('messages.completed_orders') }}</div>
        <div class="stat-footer-modern">
            <i class="fas fa-check-double"></i>
            <span>مكتملة</span>
        </div>
    </div>

    <!-- Total Spent -->
    <div class="stat-card-ultra purple fade-in-up" style="animation-delay: 0.5s">
        <div class="stat-header">
            <div class="stat-icon-box">
                <i class="fas fa-coins"></i>
            </div>
        </div>
        <div class="stat-value-modern">{{ number_format($stats['total_spent'], 2) }}</div>
        <div class="stat-label-modern">{{ __('messages.total_spent') }}</div>
        <div class="stat-footer-modern">
            <i class="fas fa-coins"></i>
            <span>ريال سعودي</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="dashboard-card-ultra fade-in-up" style="animation-delay: 0.6s">
        <div class="card-header-ultra">
            <h2 class="card-title-ultra">
                <i class="fas fa-shopping-bag"></i>
                <span>{{ __('messages.recent_orders') }}</span>
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="btn-ultra primary">
                <i class="fas fa-eye"></i>
                <span>{{ __('messages.view_all') }}</span>
            </a>
        </div>
        
        @if($recentOrders->count() > 0)
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="order-item-ultra">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-lg mb-2 flex items-center gap-2">
                            <i class="fas fa-box text-primary-medium"></i>
                            <span>{{ $order->service_name ?? __('messages.service') }}</span>
                        </h3>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-hashtag text-primary-medium"></i>
                            <span>#{{ $order->order_number }}</span>
                        </p>
                    </div>
                    <span class="status-badge-ultra {{ $order->status }}">
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
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-primary-medium"></i>
                        <span>{{ $order->created_at->format('Y-m-d') }}</span>
                    </p>
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn-ultra primary">
                        <i class="fas fa-arrow-left"></i>
                        <span>{{ __('messages.view_details') }}</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state-ultra">
            <div class="empty-state-icon-ultra">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_orders_yet') }}</p>
            <a href="{{ route('services') }}" class="btn-ultra primary mt-4">
                <i class="fas fa-search"></i>
                <span>{{ __('messages.browse_services') }}</span>
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Messages -->
    <div class="dashboard-card-ultra fade-in-up" style="animation-delay: 0.7s">
        <div class="card-header-ultra">
            <h2 class="card-title-ultra">
                <i class="fas fa-comments"></i>
                <span>{{ __('messages.recent_messages') }}</span>
            </h2>
            <div class="flex items-center gap-3">
                @if($stats['unread_messages'] > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-3 py-1.5 font-bold animate-pulse flex items-center gap-1">
                    <i class="fas fa-bell"></i>
                    <span>{{ $stats['unread_messages'] }}</span>
                </span>
                @endif
                <a href="{{ route('customer.messages.index') }}" class="btn-ultra primary">
                    <i class="fas fa-comments"></i>
                    <span>{{ __('messages.open_chat') }}</span>
                </a>
            </div>
        </div>
        
        @if($recentMessages->count() > 0)
        <div class="space-y-3">
            @foreach($recentMessages as $message)
            <div class="message-item-ultra">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-bold flex items-center gap-2
                                @if($message->sender_type === 'admin') text-primary-medium
                                @else text-gray-600
                                @endif">
                                @if($message->sender_type === 'admin')
                                    <i class="fas fa-user-shield"></i>
                                    <span>{{ $message->admin->name ?? __('messages.admin') }}</span>
                                @else
                                    <i class="fas fa-user"></i>
                                    <span>{{ __('messages.you') }}</span>
                                @endif
                            </span>
                            @if(!$message->is_read && $message->sender_type === 'admin')
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 line-clamp-2 leading-relaxed flex items-start gap-2">
                            <i class="fas fa-quote-right text-primary-medium mt-1 text-xs"></i>
                            <span>{{ Str::limit($message->message, 100) }}</span>
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500 flex items-center gap-2">
                        <i class="fas fa-clock text-primary-medium"></i>
                        <span>{{ $message->created_at->diffForHumans() }}</span>
                    </p>
                    <a href="{{ route('customer.messages.index') }}" class="text-primary-medium hover:text-primary-dark text-sm font-semibold flex items-center gap-2 transition-colors">
                        <i class="fas fa-reply"></i>
                        <span>رد</span>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state-ultra">
            <div class="empty-state-icon-ultra">
                <i class="fas fa-comments"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_messages_yet') }}</p>
            <p class="text-gray-500 text-sm flex items-center justify-center gap-2">
                <i class="fas fa-info-circle"></i>
                <span>ابدأ محادثة مع فريق الدعم</span>
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
