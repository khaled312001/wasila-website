@extends('customer.layouts.app')

@section('title', __('messages.customer_dashboard'))
@section('page-title', __('messages.dashboard'))
@section('page-subtitle', __('messages.welcome_back') . ' ' . $customer->name)

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
            </div>
        </div>
        <h3 class="text-white/80 text-sm mb-2">{{ __('messages.total_orders') }}</h3>
        <p class="text-3xl font-bold text-white">{{ $stats['total_orders'] }}</p>
    </div>

    <!-- Pending Orders -->
    <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <h3 class="text-white/80 text-sm mb-2">{{ __('messages.pending_orders') }}</h3>
        <p class="text-3xl font-bold text-white">{{ $stats['pending_orders'] }}</p>
    </div>

    <!-- Completed Orders -->
    <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <h3 class="text-white/80 text-sm mb-2">{{ __('messages.completed_orders') }}</h3>
        <p class="text-3xl font-bold text-white">{{ $stats['completed_orders'] }}</p>
    </div>

    <!-- Total Spent -->
    <div class="stat-card" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
            </div>
        </div>
        <h3 class="text-white/80 text-sm mb-2">{{ __('messages.total_spent') }}</h3>
        <p class="text-3xl font-bold text-white">{{ number_format($stats['total_spent'], 2) }} ر.س</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Orders -->
    <div class="dashboard-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ __('messages.recent_orders') }}</h2>
            <a href="{{ route('customer.orders.index') }}" class="text-primary-medium hover:text-primary-dark text-sm">
                {{ __('messages.view_all') }}
            </a>
        </div>
        
        @if($recentOrders->count() > 0)
        <div class="space-y-4">
            @foreach($recentOrders as $order)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-800">{{ $order->service_name ?? __('messages.service') }}</h3>
                        <p class="text-sm text-gray-600">{{ $order->order_number }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="flex items-center justify-between mt-3">
                    <p class="text-sm text-gray-600">{{ $order->created_at->format('Y-m-d') }}</p>
                    <a href="{{ route('customer.orders.show', $order) }}" class="text-primary-medium hover:text-primary-dark text-sm font-semibold">
                        {{ __('messages.view_details') }} →
                    </a>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
            <p class="text-gray-600">{{ __('messages.no_orders_yet') }}</p>
            <a href="{{ route('services') }}" class="btn-primary mt-4 inline-block">
                {{ __('messages.browse_services') }}
            </a>
        </div>
        @endif
    </div>

    <!-- Recent Messages -->
    <div class="dashboard-card">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">{{ __('messages.recent_messages') }}</h2>
            <a href="{{ route('customer.messages.index') }}" class="text-primary-medium hover:text-primary-dark text-sm">
                {{ __('messages.view_all') }}
            </a>
            @if($stats['unread_messages'] > 0)
            <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1">
                {{ $stats['unread_messages'] }}
            </span>
            @endif
        </div>
        
        @if($recentMessages->count() > 0)
        <div class="space-y-4">
            @foreach($recentMessages as $message)
            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-2">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs font-semibold
                                @if($message->sender_type === 'admin') text-primary-medium
                                @else text-gray-600
                                @endif">
                                @if($message->sender_type === 'admin')
                                    {{ $message->admin->name ?? __('messages.admin') }}
                                @else
                                    {{ __('messages.you') }}
                                @endif
                            </span>
                            @if(!$message->is_read && $message->sender_type === 'admin')
                            <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-700 line-clamp-2">{{ Str::limit($message->message, 100) }}</p>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">{{ $message->created_at->diffForHumans() }}</p>
            </div>
            @endforeach
        </div>
        @else
        <div class="text-center py-8">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
            </svg>
            <p class="text-gray-600">{{ __('messages.no_messages_yet') }}</p>
        </div>
        @endif
    </div>
</div>
@endsection

