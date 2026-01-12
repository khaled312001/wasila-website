@extends('customer.layouts.app')

@section('title', __('messages.invoice'))
@section('page-title', __('messages.invoice'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@push('styles')
<style>
    .invoice-container {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border-radius: 24px;
        padding: 2.5rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
    }
    
    .invoice-header {
        border-bottom: 3px solid rgba(8, 120, 139, 0.1);
        padding-bottom: 2rem;
        margin-bottom: 2rem;
    }
    
    .invoice-title {
        font-size: 2.5rem;
        font-weight: 900;
        color: #025469;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    
    .invoice-title i {
        font-size: 2rem;
        color: #08788B;
    }
    
    .invoice-info-section {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(8, 120, 139, 0.1);
    }
    
    .invoice-info-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #025469;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .invoice-info-title i {
        color: #08788B;
    }
    
    .invoice-info-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
        color: #374151;
    }
    
    .invoice-info-item i {
        color: #08788B;
        width: 20px;
    }
    
    .invoice-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        margin: 2rem 0;
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
    }
    
    .invoice-table thead {
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        color: white;
    }
    
    .invoice-table thead th {
        padding: 1rem 1.5rem;
        text-align: right;
        font-weight: 600;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .invoice-table tbody tr {
        border-bottom: 1px solid #e5e7eb;
        transition: background-color 0.2s ease;
    }
    
    .invoice-table tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .invoice-table tbody td {
        padding: 1.25rem 1.5rem;
        color: #374151;
    }
    
    .invoice-table tbody td:first-child {
        font-weight: 600;
        color: #025469;
    }
    
    .invoice-table tfoot {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
    }
    
    .invoice-table tfoot td {
        padding: 1.5rem;
        font-weight: 700;
        font-size: 1.25rem;
        color: #059669;
    }
    
    .invoice-table tfoot td:first-child {
        text-align: right;
    }
    
    .payment-method-card {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        border-radius: 16px;
        padding: 1.5rem;
        border: 1px solid rgba(8, 120, 139, 0.1);
        margin-top: 2rem;
    }
    
    .payment-method-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #025469;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .payment-method-title i {
        color: #08788B;
    }
    
    .btn-download-pdf {
        background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
        color: white;
        padding: 0.875rem 2rem;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        border: none;
        box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3);
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        text-decoration: none;
    }
    
    .btn-download-pdf:hover {
        transform: translateY(-2px) scale(1.02);
        box-shadow: 0 8px 25px rgba(8, 120, 139, 0.4);
        color: white;
    }
    
    .btn-download-pdf i {
        font-size: 1.125rem;
    }
    
    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
    }
    
    .status-badge.pending {
        background-color: #fef3c7;
        color: #92400e;
    }
    
    .status-badge.completed {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.processing {
        background-color: #dbeafe;
        color: #1e40af;
    }
    
    .status-badge.paid {
        background-color: #d1fae5;
        color: #065f46;
    }
    
    .status-badge.unpaid {
        background-color: #fee2e2;
        color: #991b1b;
    }
    
    .status-badge.refunded {
        background-color: #f3e8ff;
        color: #6b21a8;
    }
    
    .status-badge.cancelled {
        background-color: #f3f4f6;
        color: #374151;
    }
    
    @media (max-width: 768px) {
        .invoice-container {
            padding: 1.5rem;
        }
        
        .invoice-title {
            font-size: 1.75rem;
        }
        
        .invoice-table {
            font-size: 0.875rem;
        }
        
        .invoice-table thead th,
        .invoice-table tbody td {
            padding: 0.75rem 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="invoice-container">
    <!-- Invoice Header -->
    <div class="invoice-header">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <h1 class="invoice-title">
                    <i class="fas fa-file-invoice"></i>
                    {{ __('messages.invoice') }}
                </h1>
                <p class="text-gray-600 mt-2 flex items-center gap-2">
                    <i class="fas fa-hashtag text-primary-medium"></i>
                    <span>{{ __('messages.order_number') }}: #{{ $order->order_number }}</span>
                </p>
            </div>
            <a href="{{ route('customer.orders.invoice.download', $order) }}" class="btn-download-pdf">
                <i class="fas fa-download"></i>
                {{ __('messages.download_pdf') }}
            </a>
        </div>
    </div>
    
    <!-- Invoice Info Sections -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <!-- Bill To -->
        <div class="invoice-info-section">
            <h3 class="invoice-info-title">
                <i class="fas fa-user"></i>
                {{ __('messages.bill_to') }}
            </h3>
            <div class="space-y-2">
                <div class="invoice-info-item">
                    <i class="fas fa-user-circle"></i>
                    <span class="font-semibold">{{ $order->customer_name }}</span>
                </div>
                <div class="invoice-info-item">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $order->customer_email }}</span>
                </div>
                @if($order->customer_phone)
                <div class="invoice-info-item">
                    <i class="fas fa-phone"></i>
                    <span>{{ $order->customer_phone }}</span>
                </div>
                @endif
                @if($order->customer_address)
                <div class="invoice-info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>{{ $order->customer_address }}</span>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Invoice Details -->
        <div class="invoice-info-section">
            <h3 class="invoice-info-title">
                <i class="fas fa-info-circle"></i>
                {{ __('messages.invoice_details') }}
            </h3>
            <div class="space-y-2">
                <div class="invoice-info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <span><strong>{{ __('messages.date') }}:</strong> {{ $order->created_at->format('Y-m-d') }}</span>
                </div>
                <div class="invoice-info-item">
                    <i class="fas fa-tag"></i>
                    <span><strong>{{ __('messages.status') }}:</strong> 
                        <span class="status-badge {{ $order->status }}">
                            <i class="fas fa-{{ $order->status === 'completed' ? 'check-circle' : ($order->status === 'pending' ? 'clock' : 'cog') }}"></i>
                            {{ __('messages.' . $order->status) }}
                        </span>
                    </span>
                </div>
                <div class="invoice-info-item">
                    <i class="fas fa-credit-card"></i>
                    <span><strong>{{ __('messages.payment_status') }}:</strong> 
                        <span class="status-badge {{ $order->payment_status }}">
                            <i class="fas fa-{{ $order->payment_status === 'paid' ? 'check-circle' : ($order->payment_status === 'unpaid' ? 'times-circle' : 'undo') }}"></i>
                            {{ __('messages.' . $order->payment_status) }}
                        </span>
                    </span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Invoice Table -->
    <table class="invoice-table">
        <thead>
            <tr>
                <th>
                    <i class="fas fa-box ml-2"></i>
                    {{ __('messages.service') }}
                </th>
                <th>
                    <i class="fas fa-align-right ml-2"></i>
                    {{ __('messages.description') }}
                </th>
                <th>
                    <i class="fas fa-coins ml-2"></i>
                    {{ __('messages.amount') }}
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="font-semibold">{{ $order->service_name ?? __('messages.service') }}</td>
                <td>{{ $order->service_description ?? '-' }}</td>
                <td class="font-semibold">{{ number_format($order->total_amount, 2) }} ر.س</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="text-right">
                    <i class="fas fa-calculator ml-2"></i>
                    {{ __('messages.total') }}:
                </td>
                <td>{{ number_format($order->total_amount, 2) }} ر.س</td>
            </tr>
        </tfoot>
    </table>
    
    <!-- Payment Method -->
    <div class="payment-method-card">
        <h3 class="payment-method-title">
            <i class="fas fa-credit-card"></i>
            {{ __('messages.payment_method') }}
        </h3>
        <p class="text-gray-700 flex items-center gap-2">
            <i class="fas fa-wallet text-primary-medium"></i>
            <span>{{ $order->payment_method ?? __('messages.unpaid') }}</span>
        </p>
        @if($order->payment_reference)
        <p class="text-gray-600 text-sm mt-2 flex items-center gap-2">
            <i class="fas fa-hashtag text-primary-medium"></i>
            <span>{{ __('messages.payment_reference') }}: {{ $order->payment_reference }}</span>
        </p>
        @endif
    </div>
</div>
@endsection
