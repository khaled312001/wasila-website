@extends('customer.layouts.app')

@section('title', __('messages.order_details'))
@section('page-title', __('messages.order_details'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Order Info -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.order_information') }}</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.order_number') }}:</span>
                    <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.service') }}:</span>
                    <span class="font-semibold text-gray-900">{{ $order->service_name ?? __('messages.service') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.amount') }}:</span>
                    <span class="font-semibold text-green-600 text-lg">{{ number_format($order->total_amount, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.status') }}:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.payment_status') }}:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ __('messages.' . $order->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">{{ __('messages.order_date') }}:</span>
                    <span class="font-semibold text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>

        <!-- Documentation Videos -->
        @if($order->documentation->count() > 0)
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.order_documentation') }}</h2>
            <div class="space-y-4">
                @foreach($order->documentation as $doc)
                <div class="border border-gray-200 rounded-lg p-4">
                    <div class="flex items-start justify-between mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-1">{{ $doc->title }}</h3>
                            @if($doc->description)
                            <p class="text-sm text-gray-600">{{ $doc->description }}</p>
                            @endif
                        </div>
                        <span class="text-xs text-gray-500">{{ $doc->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="bg-gray-100 rounded-lg p-4 mb-3">
                        <video controls class="w-full rounded-lg" poster="{{ $doc->thumbnail_url }}">
                            <source src="{{ $doc->video_url }}" type="video/mp4">
                            {{ __('messages.browser_not_support_video') }}
                        </video>
                    </div>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        @if($doc->duration)
                        <span>⏱️ {{ $doc->formatted_duration }}</span>
                        @endif
                        @if($doc->file_size)
                        <span>📦 {{ $doc->formatted_file_size }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.actions') }}</h2>
            <div class="space-y-3">
                <a href="{{ route('customer.orders.invoice', $order) }}" class="btn-primary w-full text-center block">
                    {{ __('messages.view_invoice') }}
                </a>
                <a href="{{ route('customer.orders.invoice.download', $order) }}" class="bg-green-600 hover:bg-green-700 text-white w-full py-3 px-4 rounded-lg text-center block font-semibold transition-colors">
                    {{ __('messages.download_invoice') }}
                </a>
                <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white w-full py-3 px-4 rounded-lg text-center block font-semibold transition-colors">
                    {{ __('messages.contact_about_order') }}
                </a>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.customer_information') }}</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.name') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.email') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_email }}</p>
                </div>
                @if($order->customer_phone)
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.phone') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

