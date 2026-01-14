@extends('customer.layouts.app')

@section('title', __('messages.my_orders'))
@section('page-title', __('messages.my_orders'))
@section('page-subtitle', __('messages.view_all_your_orders'))

@push('styles')
<style>
    /* Page Container */
    .orders-page {
        max-width: 1400px;
        margin: 0 auto;
    }
    
    /* Header Card */
    .orders-header-card {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        border-radius: 1.5rem;
        padding: 2.5rem;
        color: white;
        box-shadow: 0 20px 60px rgba(8, 120, 139, 0.3);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .orders-header-card::before {
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
    
    .orders-header-content {
        position: relative;
        z-index: 10;
        display: flex;
        align-items: center;
        gap: 2rem;
    }
    
    .orders-header-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        animation: float 3s ease-in-out infinite;
        flex-shrink: 0;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    .orders-header-text h2 {
        font-size: 2rem;
        font-weight: 900;
        margin-bottom: 0.5rem;
    }
    
    .orders-header-text p {
        font-size: 1.125rem;
        opacity: 0.9;
    }
    
    /* Orders Grid */
    .orders-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    @media (min-width: 768px) {
        .orders-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (min-width: 1200px) {
        .orders-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }
    
    /* Order Card */
    .order-card {
        background: white;
        border-radius: 1.25rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }
    
    .order-card::before {
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
    
    .order-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
    }
    
    .order-card:hover::before {
        transform: scaleX(1);
    }
    
    .order-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }
    
    .order-number-section {
        flex: 1;
    }
    
    .order-number {
        font-size: 1.5rem;
        font-weight: 900;
        color: #08788B;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .order-number::before {
        content: '#';
        color: #94a3b8;
        font-weight: 400;
    }
    
    .order-date-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.375rem 0.875rem;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 0.75rem;
        font-size: 0.8125rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .order-date-badge i {
        color: #94a3b8;
    }
    
    .order-status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.8125rem;
        font-weight: 600;
        text-transform: capitalize;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        white-space: nowrap;
    }
    
    .order-status-badge.completed {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    .order-status-badge.pending {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        color: white;
    }
    
    .order-status-badge.processing {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    
    .order-status-badge.confirmed {
        background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
        color: white;
    }
    
    .order-status-badge.cancelled {
        background: linear-gradient(135deg, #6b7280 0%, #4b5563 100%);
        color: white;
    }
    
    .order-body {
        flex: 1;
        margin-bottom: 1.5rem;
    }
    
    .service-section {
        margin-bottom: 1rem;
    }
    
    .service-name {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .service-name i {
        color: #08788B;
        font-size: 1rem;
    }
    
    .service-description {
        font-size: 0.875rem;
        color: #64748b;
        line-height: 1.5;
    }
    
    .order-amount-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 0.75rem;
        border: 1px solid #e2e8f0;
        margin-bottom: 1rem;
    }
    
    .amount-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    
    .amount-value {
        font-size: 1.5rem;
        font-weight: 900;
        color: #10b981;
        display: flex;
        align-items: baseline;
        gap: 0.25rem;
    }
    
    .amount-currency {
        font-size: 1rem;
        font-weight: 600;
        color: #64748b;
    }
    
    .documentation-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.8125rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
    
    .documentation-badge.available {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        box-shadow: 0 2px 8px rgba(59, 130, 246, 0.3);
    }
    
    .documentation-badge.unavailable {
        background: #f1f5f9;
        color: #94a3b8;
        border: 1px solid #e2e8f0;
    }
    
    .order-card-footer {
        display: flex;
        gap: 0.75rem;
        margin-top: auto;
        padding-top: 1rem;
        border-top: 1px solid #f1f5f9;
    }
    
    .order-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 0.75rem 1rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }
    
    .order-action-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .order-action-btn.primary {
        background: linear-gradient(135deg, #08788B 0%, #4f46e5 100%);
        color: white;
    }
    
    .order-action-btn.success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    
    /* Empty State */
    .empty-state-modern {
        background: white;
        border-radius: 1.5rem;
        padding: 4rem 2rem;
        text-align: center;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    .empty-state-icon-modern {
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
    
    .empty-state-icon-modern i {
        font-size: 3rem;
        color: white;
    }
    
    .empty-state-title-modern {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.75rem;
    }
    
    .empty-state-description-modern {
        font-size: 1.125rem;
        color: #64748b;
        margin-bottom: 2rem;
    }
    
    .empty-state-btn-modern {
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
    
    .empty-state-btn-modern:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(8, 120, 139, 0.4);
    }
    
    /* Pagination */
    .pagination-wrapper-modern {
        margin-top: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
    }
    
    /* Stats Cards */
    .orders-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .stat-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid #e5e7eb;
        text-align: center;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 900;
        color: #08788B;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #64748b;
        font-weight: 500;
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .orders-header-card {
            padding: 1.5rem;
        }
        
        .orders-header-content {
            flex-direction: column;
            text-align: center;
            gap: 1rem;
        }
        
        .orders-header-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
        }
        
        .orders-header-text h2 {
            font-size: 1.5rem;
        }
        
        .orders-header-text p {
            font-size: 1rem;
        }
        
        .orders-grid {
            grid-template-columns: 1fr;
        }
        
        .order-card {
            padding: 1.25rem;
        }
        
        .order-number {
            font-size: 1.25rem;
        }
        
        .order-card-footer {
            flex-direction: column;
        }
        
        .order-action-btn {
            width: 100%;
        }
        
        .orders-stats {
            grid-template-columns: repeat(2, 1fr);
        }
    }
    
    @media (max-width: 640px) {
        .orders-header-card {
            padding: 1.25rem;
        }
        
        .orders-header-text h2 {
            font-size: 1.25rem;
        }
        
        .empty-state-modern {
            padding: 3rem 1.5rem;
        }
        
        .empty-state-icon-modern {
            width: 100px;
            height: 100px;
        }
        
        .empty-state-icon-modern i {
            font-size: 2.5rem;
        }
        
        .orders-stats {
            grid-template-columns: 1fr;
        }
    }
</style>
@endpush

@section('content')
<div class="orders-page">
    <!-- Header Card -->
    <div class="orders-header-card">
        <div class="orders-header-content">
            <div class="orders-header-icon">
                <i class="fas fa-shopping-bag"></i>
            </div>
            <div class="orders-header-text">
                <h2>{{ __('messages.all_orders') }}</h2>
                <p>{{ __('messages.manage_and_track_your_orders') }}</p>
            </div>
        </div>
    </div>
    
    @if($orders->count() > 0)
    <!-- Stats Cards -->
    <div class="orders-stats">
        <div class="stat-card">
            <div class="stat-value">{{ $orders->total() }}</div>
            <div class="stat-label">إجمالي الطلبات</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $orders->where('status', 'completed')->count() }}</div>
            <div class="stat-label">مكتملة</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $orders->where('status', 'processing')->count() }}</div>
            <div class="stat-label">قيد التنفيذ</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $orders->where('payment_status', 'paid')->count() }}</div>
            <div class="stat-label">مدفوعة</div>
        </div>
    </div>
    
    <!-- Orders Grid -->
    <div class="orders-grid">
        @foreach($orders as $order)
        <div class="order-card">
            <div class="order-card-header">
                <div class="order-number-section">
                    <div class="order-number">{{ $order->order_number }}</div>
                    <div class="order-date-badge">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ $order->created_at->format('Y-m-d') }}</span>
                    </div>
                </div>
                <span class="order-status-badge {{ $order->status }}">
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
            
            <div class="order-body">
                <div class="service-section">
                    <div class="service-name">
                        <i class="fas fa-box"></i>
                        {{ $order->service_name ?? __('messages.service') }}
                    </div>
                    @if($order->service_description)
                    <div class="service-description">{{ Str::limit($order->service_description, 80) }}</div>
                    @endif
                </div>
                
                <div class="order-amount-section">
                    <div class="amount-label">{{ __('messages.amount') }}</div>
                    <div class="amount-value">
                        {{ number_format($order->total_amount, 2) }}
                        <span class="amount-currency">ر.س</span>
                    </div>
                </div>
                
                @php
                    $hasDocumentation = $order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0;
                @endphp
                @if($hasDocumentation)
                <span class="documentation-badge available">
                    <i class="fas fa-video"></i>
                    فيديو توثيق متوفر
                </span>
                @else
                <span class="documentation-badge unavailable">
                    <i class="fas fa-video-slash"></i>
                    لا يوجد توثيق
                </span>
                @endif
            </div>
            
            <div class="order-card-footer">
                <a href="{{ route('customer.orders.show', $order) }}" class="order-action-btn primary">
                    <i class="fas fa-eye"></i>
                    <span>عرض</span>
                </a>
                <a href="{{ route('customer.orders.invoice', $order) }}" class="order-action-btn success">
                    <i class="fas fa-file-invoice"></i>
                    <span>فاتورة</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    
    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper-modern">
        {{ $orders->links() }}
    </div>
    @endif
    
    @else
    <!-- Empty State -->
    <div class="empty-state-modern">
        <div class="empty-state-icon-modern">
            <i class="fas fa-shopping-bag"></i>
        </div>
        <h3 class="empty-state-title-modern">{{ __('messages.no_orders_yet') }}</h3>
        <p class="empty-state-description-modern">{{ __('messages.start_ordering_services') }}</p>
        <a href="{{ route('services') }}" class="empty-state-btn-modern">
            <i class="fas fa-shopping-bag"></i>
            <span>{{ __('messages.browse_services') }}</span>
        </a>
    </div>
    @endif
</div>
@endsection
