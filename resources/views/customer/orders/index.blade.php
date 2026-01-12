@extends('customer.layouts.app')

@section('title', __('messages.my_orders'))
@section('page-title', __('messages.my_orders'))
@section('page-subtitle', __('messages.view_all_your_orders'))

@section('content')
<div class="dashboard-card">
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ __('messages.all_orders') }}</h2>
        <p class="text-gray-600">{{ __('messages.manage_and_track_your_orders') }}</p>
    </div>

    @if($orders->count() > 0)
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.order_number') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.service') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.amount') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.status') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                        <i class="fas fa-video ml-1"></i>
                        توثيق
                    </th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.date') }}</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900">#{{ $order->order_number }}</div>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $order->service_name ?? __('messages.service') }}</div>
                        <div class="text-sm text-gray-500">{{ Str::limit($order->service_description ?? '', 50) }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-semibold text-gray-900">{{ number_format($order->total_amount, 2) }} ر.س</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($order->status === 'completed') bg-green-100 text-green-800
                            @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                            @else bg-gray-100 text-gray-800
                            @endif">
                            {{ __('messages.' . $order->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $hasDocumentation = $order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0;
                        @endphp
                        @if($hasDocumentation)
                        <div class="flex items-center gap-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800" title="يوجد فيديو توثيق">
                                <i class="fas fa-video ml-1"></i>
                                متوفر
                            </span>
                        </div>
                        @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-500" title="لا يوجد فيديو توثيق">
                            <i class="fas fa-video-slash ml-1"></i>
                            غير متوفر
                        </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('customer.orders.show', $order) }}" class="text-primary-medium hover:text-primary-dark">
                                {{ __('messages.view') }}
                            </a>
                            <span class="text-gray-300">|</span>
                            <a href="{{ route('customer.orders.invoice', $order) }}" class="text-green-600 hover:text-green-800">
                                {{ __('messages.invoice') }}
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-24 h-24 text-gray-400 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
        </svg>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ __('messages.no_orders_yet') }}</h3>
        <p class="text-gray-600 mb-6">{{ __('messages.start_ordering_services') }}</p>
        <a href="{{ route('services') }}" class="btn-primary inline-block">
            {{ __('messages.browse_services') }}
        </a>
    </div>
    @endif
</div>
@endsection

