@extends('customer.layouts.app')

@section('title', __('messages.my_orders'))
@section('page-title', __('messages.my_orders'))
@section('page-subtitle', __('messages.view_all_your_orders'))

@push('styles')
<style>
    /* Orders Container */
    .orders-container {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e5e7eb;
    }
    
    /* Header Section */
    .orders-header {
        background: linear-gradient(135deg, #08788B 0%, #025469 50%, #4f46e5 100%);
        padding: 2rem;
        color: white;
        position: relative;
        overflow: hidden;
    }
    
    .orders-header::before {
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
    
    .orders-header-content {
        position: relative;
        z-index: 10;
    }
    
    .orders-header-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        animation: float 3s ease-in-out infinite;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    /* Table Styles */
    .orders-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .orders-table thead {
        background: linear-gradient(to bottom, #f8fafc 0%, #f1f5f9 100%);
    }
    
    .orders-table thead th {
        padding: 1.25rem 1.5rem;
        text-align: right;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;
    }
    
    .orders-table tbody tr {
        transition: all 0.3s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .orders-table tbody tr:hover {
        background: linear-gradient(to right, #f8fafc 0%, #ffffff 100%);
        transform: translateX(-4px);
        box-shadow: -4px 0 12px rgba(8, 120, 139, 0.1);
    }
    
    .orders-table tbody td {
        padding: 1.5rem 1.5rem;
        vertical-align: middle;
    }
    
    /* Order Number */
    .order-number {
        font-size: 1rem;
        font-weight: 700;
        color: #08788B;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-number::before {
        content: '#';
        color: #94a3b8;
        font-weight: 400;
    }
    
    /* Service Info */
    .service-info {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .service-name {
        font-size: 0.95rem;
        font-weight: 600;
        color: #1e293b;
    }
    
    .service-description {
        font-size: 0.85rem;
        color: #64748b;
        line-height: 1.4;
    }
    
    /* Amount */
    .order-amount {
        font-size: 1.1rem;
        font-weight: 700;
        color: #08788B;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    
    .order-amount::after {
        content: 'ر.س';
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        margin-right: 0.25rem;
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
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
    
    .status-badge.cancelled {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    
    /* Documentation Badge */
    .doc-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .doc-badge.available {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .doc-badge.available:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
    }
    
    .doc-badge.unavailable {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
    }
    
    /* Date */
    .order-date {
        font-size: 0.9rem;
        color: #64748b;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-date i {
        color: #94a3b8;
    }
    
    /* Actions */
    .order-actions {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .action-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.625rem 1.25rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        transition: all 0.3s ease;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    
    .action-btn.view {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(8, 120, 139, 0.3);
    }
    
    .action-btn.view:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.4);
    }
    
    .action-btn.invoice {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }
    
    .action-btn.invoice:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }
    
    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: linear-gradient(to bottom, #f8fafc 0%, #ffffff 100%);
    }
    
    .empty-state-icon {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 2rem;
        box-shadow: 0 10px 30px rgba(8, 120, 139, 0.2);
        animation: float 3s ease-in-out infinite;
    }
    
    .empty-state-icon svg {
        width: 60px;
        height: 60px;
        color: white;
    }
    
    .empty-state-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    
    .empty-state-description {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 2rem;
    }
    
    .empty-state-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 2rem;
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
        border-radius: 1rem;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
        transition: all 0.3s ease;
    }
    
    .empty-state-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(8, 120, 139, 0.4);
    }
    
    /* Pagination */
    .pagination-wrapper {
        padding: 1.5rem;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
    }
    
    /* Responsive Design */
    @media (max-width: 1024px) {
        .orders-table {
            display: block;
            overflow-x: auto;
            white-space: nowrap;
        }
        
        .orders-table thead,
        .orders-table tbody,
        .orders-table tr,
        .orders-table td {
            display: block;
        }
        
        .orders-table thead {
            display: none;
        }
        
        .orders-table tbody tr {
            margin-bottom: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 1rem;
            background: white;
        }
        
        .orders-table tbody td {
            padding: 0.75rem 0;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .orders-table tbody td::before {
            content: attr(data-label);
            font-weight: 600;
            color: #475569;
            font-size: 0.85rem;
        }
        
        .order-actions {
            flex-direction: column;
            width: 100%;
            gap: 0.75rem;
        }
        
        .action-btn {
            width: 100%;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .orders-header {
            padding: 1.5rem;
        }
        
        .orders-header h2 {
            font-size: 1.75rem !important;
        }
        
        .orders-header p {
            font-size: 1rem !important;
        }
        
        .orders-header-icon {
            width: 50px;
            height: 50px;
        }
        
        .orders-table {
            display: block;
        }
        
        .orders-table thead {
            display: none;
        }
        
        .orders-table tbody {
            display: block;
        }
        
        .orders-table tbody tr {
            display: block;
            margin-bottom: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            padding: 1rem;
            background: white;
        }
        
        .orders-table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border: none;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .orders-table tbody td:last-child {
            border-bottom: none;
        }
        
        .orders-table tbody td::before {
            content: attr(data-label);
            font-weight: 700;
            color: #475569;
            margin-left: 1rem;
        }
        
        .empty-state {
            padding: 3rem 1rem;
        }
        
        .empty-state-icon {
            width: 100px;
            height: 100px;
        }
    }
    
    @media (max-width: 640px) {
        .orders-header {
            padding: 1.25rem;
        }
        
        .orders-header h2 {
            font-size: 1.5rem !important;
        }
        
        .orders-header p {
            font-size: 0.9375rem !important;
        }
        
        .orders-header-icon {
            width: 45px;
            height: 45px;
        }
        
        .orders-table tbody tr {
            padding: 0.875rem;
        }
        
        .orders-table tbody td {
            padding: 0.625rem 0;
            font-size: 0.875rem;
        }
        
        .status-badge {
            font-size: 0.75rem;
            padding: 0.375rem 0.75rem;
        }
    }
    
    @media (max-width: 480px) {
        .orders-header {
            padding: 1rem;
        }
        
        .orders-header h2 {
            font-size: 1.25rem !important;
        }
        
        .orders-header p {
            font-size: 0.875rem !important;
        }
        
        .orders-table tbody tr {
            padding: 0.75rem;
        }
    }
    
    /* Loading Animation */
    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }
    
    .loading-row {
        animation: shimmer 2s infinite;
        background: linear-gradient(to right, #f1f5f9 0%, #e2e8f0 50%, #f1f5f9 100%);
        background-size: 1000px 100%;
    }
</style>
@endpush

@section('content')
<div class="orders-container">
    <!-- Header -->
    <div class="orders-header">
        <div class="orders-header-content">
            <div class="orders-header-icon">
                <i class="fas fa-shopping-bag text-2xl text-white"></i>
            </div>
            <h2 class="text-3xl font-bold text-white mb-2">{{ __('messages.all_orders') }}</h2>
            <p class="text-white/90 text-lg">{{ __('messages.manage_and_track_your_orders') }}</p>
        </div>
    </div>

    @if($orders->count() > 0)
    <!-- Orders Table -->
    <div class="overflow-x-auto">
        <table class="orders-table">
            <thead>
                <tr>
                    <th>{{ __('messages.order_number') }}</th>
                    <th>{{ __('messages.service') }}</th>
                    <th>{{ __('messages.amount') }}</th>
                    <th>{{ __('messages.status') }}</th>
                    <th>
                        <i class="fas fa-video ml-1"></i>
                        توثيق
                    </th>
                    <th>{{ __('messages.date') }}</th>
                    <th>{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td data-label="{{ __('messages.order_number') }}">
                        <div class="order-number">{{ $order->order_number }}</div>
                    </td>
                    <td data-label="{{ __('messages.service') }}">
                        <div class="service-info">
                            <div class="service-name">{{ $order->service_name ?? __('messages.service') }}</div>
                            @if($order->service_description)
                            <div class="service-description">{{ Str::limit($order->service_description, 60) }}</div>
                            @endif
                        </div>
                    </td>
                    <td data-label="{{ __('messages.amount') }}">
                        <div class="order-amount">{{ number_format($order->total_amount, 2) }}</div>
                    </td>
                    <td data-label="{{ __('messages.status') }}">
                        <span class="status-badge {{ $order->status }}">
                            @if($order->status === 'completed')
                                <i class="fas fa-check-circle"></i>
                            @elseif($order->status === 'pending')
                                <i class="fas fa-clock"></i>
                            @elseif($order->status === 'confirmed' && $order->payment_status === 'pending')
                                <i class="fas fa-credit-card"></i>
                            @elseif($order->status === 'processing')
                                <i class="fas fa-spinner fa-spin"></i>
                            @else
                                <i class="fas fa-times-circle"></i>
                            @endif
                            @if($order->status === 'confirmed' && $order->payment_status === 'pending')
                                في انتظار الدفع
                            @else
                                {{ __('messages.' . $order->status) }}
                            @endif
                        </span>
                    </td>
                    <td data-label="توثيق">
                        @php
                            $hasDocumentation = $order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0;
                        @endphp
                        @if($hasDocumentation)
                        <span class="doc-badge available" title="يوجد فيديو توثيق">
                            <i class="fas fa-video"></i>
                            متوفر
                        </span>
                        @else
                        <span class="doc-badge unavailable" title="لا يوجد فيديو توثيق">
                            <i class="fas fa-video-slash"></i>
                            غير متوفر
                        </span>
                        @endif
                    </td>
                    <td data-label="{{ __('messages.date') }}">
                        <div class="order-date">
                            <i class="fas fa-calendar-alt"></i>
                            <span>{{ $order->created_at->format('Y-m-d') }}</span>
                        </div>
                    </td>
                    <td data-label="{{ __('messages.actions') }}">
                        <div class="order-actions">
                            <a href="{{ route('customer.orders.show', $order) }}" class="action-btn view">
                                <i class="fas fa-eye"></i>
                                {{ __('messages.view') }}
                            </a>
                            <a href="{{ route('customer.orders.invoice', $order) }}" class="action-btn invoice">
                                <i class="fas fa-file-invoice"></i>
                                {{ __('messages.invoice') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
    @else
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
            </svg>
        </div>
        <h3 class="empty-state-title">{{ __('messages.no_orders_yet') }}</h3>
        <p class="empty-state-description">{{ __('messages.start_ordering_services') }}</p>
        <a href="{{ route('services') }}" class="empty-state-btn">
            <i class="fas fa-shopping-bag"></i>
            {{ __('messages.browse_services') }}
        </a>
    </div>
    @endif
</div>
@endsection
