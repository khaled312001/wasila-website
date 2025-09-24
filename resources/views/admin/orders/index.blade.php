@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <h2 class="text-lg md:text-xl font-semibold text-primary-dark">جميع الطلبات</h2>
    </div>
    
    <div class="overflow-x-auto mobile-table">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">العميل</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">الخدمات</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">حالة الطلب</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">حالة الدفع</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">التاريخ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="table-row">
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-500 hidden sm:inline">{{ $order->created_at->format('Y-m-d') }}</span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap hidden sm:table-cell">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer_name }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer_email }}</div>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                        @foreach($order->orderItems as $item)
                        <div class="text-xs">{{ $item->service->name_ar }} ({{ $item->quantity }})</div>
                        @endforeach
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ number_format($order->total_amount, 2) }} ريال</span>
                            <span class="text-xs text-gray-500 hidden md:inline">
                                @if($order->payment_status === 'pending')
                                    في الانتظار
                                @elseif($order->payment_status === 'paid')
                                    مدفوع
                                @else
                                    فشل
                                @endif
                            </span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 md:px-2.5 md:py-0.5 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($order->status === 'pending')
                                في الانتظار
                            @elseif($order->status === 'confirmed')
                                مؤكد
                            @elseif($order->status === 'processing')
                                قيد المعالجة
                            @elseif($order->status === 'completed')
                                مكتمل
                            @else
                                ملغي
                            @endif
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap hidden md:table-cell">
                        <span class="inline-flex items-center px-2 py-1 md:px-2.5 md:py-0.5 rounded-full text-xs font-medium
                            @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($order->payment_status === 'pending')
                                في الانتظار
                            @elseif($order->payment_status === 'paid')
                                مدفوع
                            @else
                                فشل
                            @endif
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                        {{ $order->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-enhanced text-primary-medium hover:text-primary-dark px-2 md:px-3 py-1 rounded text-xs md:text-sm">
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-3 md:px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">لا توجد طلبات</p>
                            <p class="text-sm text-gray-400">لم يتم العثور على أي طلبات في النظام</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection
