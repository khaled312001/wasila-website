@extends('customer.layouts.app')

@section('title', __('messages.customer_dashboard'))
@section('page-title', __('messages.dashboard'))

@push('styles')
<style>
    /* Welcome Banner Enhanced */
    .welcome-banner {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-banner::after {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }
    
    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .welcome-text {
        color: #ffffff;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
    }
    
    .welcome-icon {
        font-size: 3rem;
        opacity: 0.2;
        position: absolute;
        left: 2rem;
        top: 50%;
        transform: translateY(-50%);
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(-50%) translateY(0px); }
        50% { transform: translateY(-50%) translateY(-10px); }
    }
    
    /* Stat Cards Enhanced */
    .stat-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid transparent;
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        cursor: pointer;
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 150px;
        height: 150px;
        background: radial-gradient(circle, rgba(8, 120, 139, 0.15) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
        transition: all 0.4s ease;
    }
    
    .stat-card-modern:hover::before {
        width: 200px;
        height: 200px;
        opacity: 0.3;
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-modern.primary {
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        color: white;
    }
    
    .stat-card-modern.warning {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .stat-card-modern.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .stat-card-modern.purple {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .stat-icon-wrapper {
        width: 72px;
        height: 72px;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }
    
    .stat-card-modern:hover .stat-icon-wrapper {
        transform: scale(1.1) rotate(5deg);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.3);
    }
    
    .stat-card-modern.primary .stat-icon-wrapper,
    .stat-card-modern.warning .stat-icon-wrapper,
    .stat-card-modern.success .stat-icon-wrapper,
    .stat-card-modern.purple .stat-icon-wrapper {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .stat-icon-wrapper i {
        font-size: 2rem;
        color: white;
    }
    
    .stat-value {
        font-size: 2.75rem;
        font-weight: 900;
        margin: 0.75rem 0;
        line-height: 1;
        letter-spacing: -1px;
    }
    
    .stat-label {
        font-size: 1rem;
        opacity: 0.95;
        font-weight: 600;
        margin-bottom: 0.5rem;
        letter-spacing: 0.3px;
    }
    
    .stat-footer {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 1rem;
        font-size: 0.875rem;
        opacity: 0.9;
    }
    
    /* Dashboard Cards Enhanced */
    .dashboard-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.12);
        border-radius: 24px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .dashboard-card-modern:hover {
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.12);
        transform: translateY(-2px);
    }
    
    .card-header-modern {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1.25rem;
        border-bottom: 2px solid rgba(8, 120, 139, 0.1);
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .card-title-modern {
        font-size: 1.5rem;
        font-weight: 700;
        color: #025469;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .card-title-modern i {
        font-size: 1.5rem;
        color: #08788B;
    }
    
    .card-title-modern::before {
        content: '';
        width: 5px;
        height: 32px;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        border-radius: 3px;
    }
    
    /* Order Items Enhanced */
    .order-item-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 18px;
        padding: 1.75rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }
    
    .order-item-modern::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .order-item-modern:hover {
        transform: translateX(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: rgba(8, 120, 139, 0.25);
    }
    
    .order-item-modern:hover::before {
        transform: scaleY(1);
    }
    
    /* Message Items Enhanced */
    .message-item-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 18px;
        padding: 1.75rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .message-item-modern:hover {
        transform: translateX(-8px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border-color: rgba(8, 120, 139, 0.25);
    }
    
    /* Status Badge Enhanced */
    .status-badge-modern {
        padding: 0.625rem 1.25rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }
    
    .status-badge-modern:hover {
        transform: scale(1.05);
    }
    
    /* Buttons Enhanced */
    .btn-primary-gradient {
        background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
        color: white !important;
        padding: 0.625rem 1.5rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 0.875rem;
        border: none;
        box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-primary-gradient:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 25px rgba(8, 120, 139, 0.4);
        color: white !important;
    }
    
    .btn-primary-gradient i {
        font-size: 0.875rem;
        color: white;
    }
    
    .text-primary-medium {
        color: #08788B !important;
    }
    
    .text-primary-dark {
        color: #025469 !important;
    }
    
    .bg-primary-medium {
        background-color: #08788B !important;
    }
    
    .bg-primary-dark {
        background-color: #025469 !important;
    }
    
    /* Animations */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Empty State Enhanced */
    .empty-state {
        text-align: center;
        padding: 4rem 1rem;
    }
    
    .empty-state-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(8, 120, 139, 0.15) 0%, rgba(8, 120, 139, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .empty-state-icon:hover {
        transform: scale(1.1) rotate(5deg);
    }
    
    .empty-state-icon i {
        font-size: 3rem;
        color: #08788B;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .welcome-icon {
            display: none;
        }
        
        .stat-card-modern {
            padding: 1.5rem;
        }
        
        .stat-icon-wrapper {
            width: 60px;
            height: 60px;
        }
        
        .stat-icon-wrapper i {
            font-size: 1.5rem;
        }
        
        .stat-value {
            font-size: 2rem;
        }
        
        .card-header-modern {
            flex-direction: column;
            align-items: flex-start;
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 fade-in">
    <div class="welcome-banner rounded-2xl shadow-2xl p-8 relative overflow-hidden">
        <i class="fas fa-hand-sparkles welcome-icon"></i>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative z-10 flex items-center justify-between">
            <div class="flex-1">
                <h1 class="text-4xl font-bold mb-3 welcome-text">
                    <i class="fas fa-smile ml-2"></i>
                    {{ __('messages.welcome_back') }}, {{ $customer->name }}!
                </h1>
                <p class="welcome-text text-xl opacity-95 flex items-center gap-2">
                    <i class="fas fa-tachometer-alt"></i>
                    {{ __('messages.dashboard_subtitle') }}
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card-modern primary fade-in" style="animation-delay: 0.1s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.total_orders') }}</h3>
        <p class="stat-value">{{ $stats['total_orders'] }}</p>
        <div class="stat-footer">
            <i class="fas fa-chart-line"></i>
            <span>جميع الطلبات</span>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="stat-card-modern warning fade-in" style="animation-delay: 0.2s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-clock"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.pending_orders') }}</h3>
        <p class="stat-value">{{ $stats['pending_orders'] }}</p>
        <div class="stat-footer">
            <i class="fas fa-hourglass-half"></i>
            <span>قيد المراجعة</span>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card-modern success fade-in" style="animation-delay: 0.3s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-check-circle"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.completed_orders') }}</h3>
        <p class="stat-value">{{ $stats['completed_orders'] }}</p>
        <div class="stat-footer">
            <i class="fas fa-check-double"></i>
            <span>مكتملة</span>
        </div>
    </div>

    <!-- Total Spent -->
    <div class="stat-card-modern purple fade-in" style="animation-delay: 0.4s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-coins"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.total_spent') }}</h3>
        <p class="stat-value">{{ number_format($stats['total_spent'], 2) }}</p>
        <div class="stat-footer">
            <i class="fas fa-coins"></i>
            <span>ريال سعودي</span>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="dashboard-card-modern fade-in" style="animation-delay: 0.5s">
        <div class="card-header-modern">
            <h2 class="card-title-modern">
                <i class="fas fa-shopping-bag"></i>
                {{ __('messages.recent_orders') }}
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="btn-primary-gradient">
                <i class="fas fa-eye"></i>
                {{ __('messages.view_all') }}
            </a>
        </div>
        
        @if($recentOrders->count() > 0)
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="order-item-modern">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-lg mb-2 flex items-center gap-2">
                            <i class="fas fa-box text-primary-medium"></i>
                            {{ $order->service_name ?? __('messages.service') }}
                        </h3>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-hashtag text-primary-medium"></i>
                            <span>{{ $order->order_number }}</span>
                        </p>
                    </div>
                    <span class="status-badge-modern
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @if($order->status === 'completed')
                            <i class="fas fa-check-circle"></i>
                        @elseif($order->status === 'pending')
                            <i class="fas fa-clock"></i>
                        @elseif($order->status === 'confirmed' && $order->payment_status === 'pending')
                            <i class="fas fa-credit-card"></i>
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
                    <a href="{{ route('customer.orders.show', $order) }}" class="btn-primary-gradient">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('messages.view_details') }}
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_orders_yet') }}</p>
            <a href="{{ route('services') }}" class="btn-primary-gradient mt-4">
                <i class="fas fa-search"></i>
                {{ __('messages.browse_services') }}
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Messages -->
    <div class="dashboard-card-modern fade-in" style="animation-delay: 0.6s">
        <div class="card-header-modern">
            <h2 class="card-title-modern">
                <i class="fas fa-comments"></i>
                {{ __('messages.recent_messages') }}
            </h2>
            <div class="flex items-center gap-3">
                @if($stats['unread_messages'] > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-3 py-1.5 font-bold animate-pulse flex items-center gap-1">
                    <i class="fas fa-bell"></i>
                    {{ $stats['unread_messages'] }}
                </span>
                @endif
                <a href="{{ route('customer.messages.index') }}" class="btn-primary-gradient">
                    <i class="fas fa-comments"></i>
                    {{ __('messages.open_chat') }}
                </a>
            </div>
        </div>
        
        @if($recentMessages->count() > 0)
        <div class="space-y-3">
            @foreach($recentMessages as $message)
            <div class="message-item-modern">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-sm font-bold flex items-center gap-2
                                @if($message->sender_type === 'admin') text-primary-medium
                                @else text-gray-600
                                @endif">
                                @if($message->sender_type === 'admin')
                                    <i class="fas fa-user-shield"></i>
                                    {{ $message->admin->name ?? __('messages.admin') }}
                                @else
                                    <i class="fas fa-user"></i>
                                    {{ __('messages.you') }}
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
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-comments"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_messages_yet') }}</p>
            <p class="text-gray-500 text-sm flex items-center justify-center gap-2">
                <i class="fas fa-info-circle"></i>
                ابدأ محادثة مع فريق الدعم
            </p>
        </div>
        @endif
    </div>
</div>
@endsection
