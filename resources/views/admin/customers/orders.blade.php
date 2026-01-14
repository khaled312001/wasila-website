@extends('admin.layouts.app')

@section('title', 'طلبات العميل')
@section('page-title', 'طلبات العميل: ' . $customer->name)

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">
            <div>
                <h2 class="text-lg md:text-xl font-semibold text-primary-dark">طلبات {{ $customer->name }}</h2>
                <p class="text-sm md:text-base text-gray-600">{{ $customer->email }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-medium hover:text-primary-dark text-sm md:text-base">
                العودة للعميل
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto mobile-table">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الطلب</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden md:table-cell">الخدمات</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase hidden lg:table-cell">التاريخ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr class="table-row">
                    <td class="px-3 md:px-6 py-4 text-sm font-medium text-gray-900" data-label="رقم الطلب">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ $order->order_number }}</span>
                            <span class="text-xs text-gray-500 md:hidden">
                                @foreach($order->orderItems as $item)
                                {{ $item->service->name_ar ?? '' }} ({{ $item->quantity }})
                                @endforeach
                            </span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 text-sm text-gray-900 hidden md:table-cell" data-label="الخدمات">
                        @foreach($order->orderItems as $item)
                        <div class="text-xs">{{ $item->service->name_ar ?? '' }} ({{ $item->quantity }})</div>
                        @endforeach
                    </td>
                    <td class="px-3 md:px-6 py-4 text-sm text-gray-900" data-label="المبلغ">
                        <span class="font-semibold">{{ number_format($order->total_amount, 2) }} ريال</span>
                    </td>
                    <td class="px-3 md:px-6 py-4" data-label="الحالة">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status === 'completed' ? 'مكتمل' : 'معلق' }}
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 text-sm text-gray-900 hidden lg:table-cell" data-label="التاريخ">
                        {{ $order->created_at->format('Y-m-d') }}
                    </td>
                    <td class="px-3 md:px-6 py-4 text-sm font-medium" data-label="الإجراءات">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-medium hover:text-primary-dark px-2 py-1 rounded text-xs md:text-sm">
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-3 md:px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-lg font-medium">لا توجد طلبات</p>
                            <p class="text-sm text-gray-400">لم يتم العثور على أي طلبات</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
    <div class="px-3 md:px-6 py-4 border-t border-gray-200">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

