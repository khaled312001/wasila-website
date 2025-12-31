@extends('admin.layouts.app')

@section('title', 'تفاصيل العميل')
@section('page-title', 'تفاصيل العميل')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <!-- Customer Info -->
    <div class="lg:col-span-2 bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center mb-6">
            @if($customer->avatar)
            <img src="{{ $customer->avatar }}" alt="{{ $customer->name }}" class="h-20 w-20 rounded-full ml-4">
            @else
            <div class="h-20 w-20 rounded-full bg-primary-light flex items-center justify-center ml-4">
                <span class="text-white text-2xl font-semibold">{{ substr($customer->name, 0, 1) }}</span>
            </div>
            @endif
            <div>
                <h2 class="text-2xl font-bold text-primary-dark">{{ $customer->name }}</h2>
                <p class="text-gray-600">{{ $customer->email }}</p>
            </div>
        </div>
        
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="text-sm font-medium text-gray-700">الهاتف</label>
                <p class="text-gray-900">{{ $customer->phone ?? 'غير محدد' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">العنوان</label>
                <p class="text-gray-900">{{ $customer->address ?? 'غير محدد' }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">تاريخ التسجيل</label>
                <p class="text-gray-900">{{ $customer->created_at->format('Y-m-d H:i') }}</p>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">آخر نشاط</label>
                <p class="text-gray-900">{{ $customer->updated_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>
    </div>
    
    <!-- Stats -->
    <div class="space-y-6">
        <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
            <h3 class="text-lg font-semibold text-primary-dark mb-4">الإحصائيات</h3>
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600">إجمالي الطلبات</p>
                    <p class="text-2xl font-bold text-primary-dark">{{ $stats['total_orders'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">إجمالي الإنفاق</p>
                    <p class="text-2xl font-bold text-green-600">{{ number_format($stats['total_spent'], 2) }} ريال</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">طلبات معلقة</p>
                    <p class="text-2xl font-bold text-yellow-600">{{ $stats['pending_orders'] }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">طلبات مكتملة</p>
                    <p class="text-2xl font-bold text-green-600">{{ $stats['completed_orders'] }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tabs -->
<div class="bg-white rounded-lg shadow-lg card-shadow">
    <div class="border-b border-gray-200">
        <nav class="flex -mb-px">
            <a href="#orders" onclick="showTab('orders')" class="tab-link active py-4 px-6 text-sm font-medium border-b-2 border-primary-medium text-primary-medium">
                الطلبات
            </a>
            <a href="#messages" onclick="showTab('messages')" class="tab-link py-4 px-6 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700">
                الرسائل
            </a>
        </nav>
    </div>
    
    <div id="orders-tab" class="tab-content p-6">
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
                    @forelse($customer->orders as $order)
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
    </div>
    
    <div id="messages-tab" class="tab-content p-6 hidden">
        <div class="space-y-4">
            @forelse($customer->messages as $message)
            <div class="border border-gray-200 rounded-lg p-4 {{ $message->sender_type === 'admin' ? 'bg-blue-50' : 'bg-gray-50' }}">
                <div class="flex justify-between items-start mb-2">
                    <div>
                        <p class="font-semibold">{{ $message->sender_type === 'admin' ? 'الإدارة' : $customer->name }}</p>
                        <p class="text-sm text-gray-600">{{ $message->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
                <p class="text-gray-900">{{ $message->message }}</p>
            </div>
            @empty
            <p class="text-center text-gray-500 py-8">لا توجد رسائل</p>
            @endforelse
        </div>
    </div>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
    document.querySelectorAll('.tab-link').forEach(link => {
        link.classList.remove('active', 'border-primary-medium', 'text-primary-medium');
        link.classList.add('border-transparent', 'text-gray-500');
    });
    
    document.getElementById(tab + '-tab').classList.remove('hidden');
    event.target.classList.add('active', 'border-primary-medium', 'text-primary-medium');
    event.target.classList.remove('border-transparent', 'text-gray-500');
}
</script>
@endsection

