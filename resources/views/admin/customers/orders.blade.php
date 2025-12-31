@extends('admin.layouts.app')

@section('title', 'طلبات العميل')
@section('page-title', 'طلبات العميل: ' . $customer->name)

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden">
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-primary-dark">طلبات {{ $customer->name }}</h2>
                <p class="text-gray-600">{{ $customer->email }}</p>
            </div>
            <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-medium hover:text-primary-dark">
                العودة للعميل
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">رقم الطلب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الخدمات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $order->order_number }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        @foreach($order->orderItems as $item)
                        <div>{{ $item->service->name_ar ?? '' }} ({{ $item->quantity }})</div>
                        @endforeach
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($order->total_amount, 2) }} ريال</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs rounded-full {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ $order->status === 'completed' ? 'مكتمل' : 'معلق' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $order->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order) }}" class="text-primary-medium hover:text-primary-dark">
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-8 text-center text-gray-500">لا توجد طلبات</td>
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

