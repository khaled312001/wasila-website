@extends('customer.layouts.app')

@section('title', __('messages.customer_dashboard'))
@section('page-title', __('messages.dashboard'))
@section('page-subtitle', '')

@push('styles')
<style>
    .stat-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 2px solid transparent;
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(8, 120, 139, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .stat-card-modern:hover {
        transform: translateY(-12px) scale(1.02);
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
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
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        position: relative;
        z-index: 1;
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(10px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    
    .stat-card-modern.primary .stat-icon-wrapper,
    .stat-card-modern.warning .stat-icon-wrapper,
    .stat-card-modern.success .stat-icon-wrapper,
    .stat-card-modern.purple .stat-icon-wrapper {
        background: rgba(255, 255, 255, 0.3);
    }
    
    .stat-value {
        font-size: 2.5rem;
        font-weight: 800;
        margin: 0.75rem 0;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.95rem;
        opacity: 0.9;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .dashboard-card-modern:hover {
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .card-header-modern {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(8, 120, 139, 0.1);
    }
    
    .card-title-modern {
        font-size: 1.5rem;
        font-weight: 700;
        color: #025469;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .card-title-modern::before {
        content: '';
        width: 4px;
        height: 28px;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        border-radius: 2px;
    }
    
    .order-item-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
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
        width: 4px;
        height: 100%;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .order-item-modern:hover {
        transform: translateX(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: rgba(8, 120, 139, 0.3);
    }
    
    .order-item-modern:hover::before {
        transform: scaleY(1);
    }
    
    .message-item-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
        position: relative;
    }
    
    .message-item-modern:hover {
        transform: translateX(-8px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.12);
        border-color: rgba(8, 120, 139, 0.3);
    }
    
    .status-badge-modern {
        padding: 0.625rem 1.25rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }
    
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
    
    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
    }
    
    .empty-state-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 1.5rem;
        background: linear-gradient(135deg, rgba(8, 120, 139, 0.1) 0%, rgba(8, 120, 139, 0.05) 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="mb-8 fade-in">
    <div class="bg-gradient-to-r from-primary-medium via-primary-dark to-indigo-600 rounded-2xl shadow-2xl p-8 text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2 text-white">{{ __('messages.welcome_back') }}, {{ $customer->name }}!</h1>
            <p class="text-white text-lg opacity-90">{{ __('messages.dashboard_subtitle') }}</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card-modern primary fade-in" style="animation-delay: 0.1s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-shopping-cart text-white text-2xl"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.total_orders') }}</h3>
        <p class="stat-value">{{ $stats['total_orders'] }}</p>
        <div class="flex items-center gap-2 mt-3 text-sm opacity-90">
            <i class="fas fa-chart-line"></i>
            <span>جميع الطلبات</span>
        </div>
    </div>

    <!-- Pending Orders -->
    <div class="stat-card-modern warning fade-in" style="animation-delay: 0.2s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-clock text-white text-2xl"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.pending_orders') }}</h3>
        <p class="stat-value">{{ $stats['pending_orders'] }}</p>
        <div class="flex items-center gap-2 mt-3 text-sm opacity-90">
            <i class="fas fa-hourglass-half"></i>
            <span>قيد المراجعة</span>
        </div>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card-modern success fade-in" style="animation-delay: 0.3s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-check-circle text-white text-2xl"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.completed_orders') }}</h3>
        <p class="stat-value">{{ $stats['completed_orders'] }}</p>
        <div class="flex items-center gap-2 mt-3 text-sm opacity-90">
            <i class="fas fa-check-double"></i>
            <span>مكتملة</span>
        </div>
    </div>

    <!-- Total Spent -->
    <div class="stat-card-modern purple fade-in" style="animation-delay: 0.4s">
        <div class="stat-icon-wrapper">
            <i class="fas fa-money-bill-wave text-white text-2xl"></i>
        </div>
        <h3 class="stat-label">{{ __('messages.total_spent') }}</h3>
        <p class="stat-value">{{ number_format($stats['total_spent'], 2) }}</p>
        <p class="text-sm opacity-90 mt-2">ريال سعودي</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="dashboard-card-modern fade-in" style="animation-delay: 0.5s">
        <div class="card-header-modern">
            <h2 class="card-title-modern">
                <i class="fas fa-shopping-bag text-primary-medium"></i>
                {{ __('messages.recent_orders') }}
            </h2>
            <a href="{{ route('customer.orders.index') }}" class="bg-gradient-to-r from-primary-light to-primary-medium text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200 text-sm">
                <i class="fas fa-eye ml-2"></i>
                {{ __('messages.view_all') }}
            </a>
        </div>
        
        @if($recentOrders->count() > 0)
        <div class="space-y-3">
            @foreach($recentOrders as $order)
            <div class="order-item-modern">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $order->service_name ?? __('messages.service') }}</h3>
                        <p class="text-sm text-gray-600">
                            <i class="fas fa-hashtag ml-1"></i>
                            {{ $order->order_number }}
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
                        @else
                            <i class="fas fa-info-circle"></i>
                        @endif
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <p class="text-sm text-gray-600">
                        <i class="fas fa-calendar ml-1"></i>
                        {{ $order->created_at->format('Y-m-d') }}
                    </p>
                    <a href="{{ route('customer.orders.show', $order) }}" class="bg-gradient-to-r from-primary-light to-primary-medium text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center gap-2">
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
                <i class="fas fa-shopping-cart text-4xl text-primary-medium"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_orders_yet') }}</p>
            <a href="{{ route('services') }}" class="bg-gradient-to-r from-primary-medium to-primary-dark text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200 inline-block mt-4">
                <i class="fas fa-search ml-2"></i>
                {{ __('messages.browse_services') }}
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Messages -->
    <div class="dashboard-card-modern fade-in" style="animation-delay: 0.6s">
        <div class="card-header-modern">
            <h2 class="card-title-modern">
                <i class="fas fa-comments text-primary-medium"></i>
                {{ __('messages.recent_messages') }}
            </h2>
            <div class="flex items-center gap-3">
                @if($stats['unread_messages'] > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-3 py-1 font-bold animate-pulse">
                    {{ $stats['unread_messages'] }}
                </span>
                @endif
                <a href="{{ route('customer.messages.index') }}" class="bg-gradient-to-r from-primary-light to-primary-medium text-white px-5 py-2 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200 text-sm">
                    <i class="fas fa-comments ml-2"></i>
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
                            <span class="text-sm font-bold
                                @if($message->sender_type === 'admin') text-primary-medium
                                @else text-gray-600
                                @endif">
                                @if($message->sender_type === 'admin')
                                    <i class="fas fa-user-shield ml-1"></i>
                                    {{ $message->admin->name ?? __('messages.admin') }}
                                @else
                                    <i class="fas fa-user ml-1"></i>
                                    {{ __('messages.you') }}
                                @endif
                            </span>
                            @if(!$message->is_read && $message->sender_type === 'admin')
                            <span class="w-2 h-2 bg-red-500 rounded-full animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 line-clamp-2 leading-relaxed">{{ Str::limit($message->message, 100) }}</p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500">
                        <i class="fas fa-clock ml-1"></i>
                        {{ $message->created_at->diffForHumans() }}
                    </p>
                    <a href="{{ route('customer.messages.index') }}" class="text-primary-medium hover:text-primary-dark text-sm font-semibold">
                        <i class="fas fa-arrow-left ml-1"></i>
                        رد
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="empty-state">
            <div class="empty-state-icon">
                <i class="fas fa-comments text-4xl text-primary-medium"></i>
            </div>
            <p class="text-gray-600 text-lg font-semibold mb-2">{{ __('messages.no_messages_yet') }}</p>
            <p class="text-gray-500 text-sm">ابدأ محادثة مع فريق الدعم</p>
        </div>
        @endif
    </div>
</div>
@endsection

