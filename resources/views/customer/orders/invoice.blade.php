@extends('customer.layouts.app')

@section('title', __('messages.invoice'))
@section('page-title', __('messages.invoice'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@section('content')
<div class="dashboard-card">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">{{ __('messages.invoice') }}</h1>
            <p class="text-gray-600 mt-2">{{ __('messages.order_number') }}: #{{ $order->order_number }}</p>
        </div>
        <a href="{{ route('customer.orders.invoice.download', $order) }}" class="btn-primary">
            {{ __('messages.download_pdf') }}
        </a>
    </div>
    
    <div class="border-t border-b border-gray-200 py-6 mb-6">
        <div class="grid grid-cols-2 gap-6">
            <div>
                <h3 class="font-semibold text-gray-900 mb-2">{{ __('messages.bill_to') }}</h3>
                <p class="text-gray-700">{{ $order->customer_name }}</p>
                <p class="text-gray-700">{{ $order->customer_email }}</p>
                @if($order->customer_phone)
                <p class="text-gray-700">{{ $order->customer_phone }}</p>
                @endif
            </div>
            <div class="text-right">
                <h3 class="font-semibold text-gray-900 mb-2">{{ __('messages.invoice_details') }}</h3>
                <p class="text-gray-700">{{ __('messages.date') }}: {{ $order->created_at->format('Y-m-d') }}</p>
                <p class="text-gray-700">{{ __('messages.status') }}: {{ __('messages.' . $order->status) }}</p>
                <p class="text-gray-700">{{ __('messages.payment_status') }}: {{ __('messages.' . $order->payment_status) }}</p>
            </div>
        </div>
    </div>
    
    <table class="w-full mb-6">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.service') }}</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.description') }}</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">{{ __('messages.amount') }}</th>
            </tr>
        </thead>
        <tbody>
            <tr class="border-b border-gray-200">
                <td class="px-6 py-4 font-semibold text-gray-900">{{ $order->service_name ?? __('messages.service') }}</td>
                <td class="px-6 py-4 text-gray-700">{{ $order->service_description ?? '-' }}</td>
                <td class="px-6 py-4 font-semibold text-gray-900">{{ number_format($order->total_amount, 2) }} ر.س</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2" class="px-6 py-4 text-right font-bold text-lg">{{ __('messages.total') }}:</td>
                <td class="px-6 py-4 font-bold text-lg text-green-600">{{ number_format($order->total_amount, 2) }} ر.س</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="bg-gray-50 rounded-lg p-6">
        <h3 class="font-semibold text-gray-900 mb-2">{{ __('messages.payment_method') }}</h3>
        <p class="text-gray-700">{{ $order->payment_method }}</p>
    </div>
</div>
@endsection

