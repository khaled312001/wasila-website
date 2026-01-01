@extends('admin.layouts.app')

@section('title', 'إدارة الطلبات')
@section('page-title', 'إدارة الطلبات')

@section('content')
<!-- Advanced Filters -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-primary-dark">فلترة متقدمة</h3>
        <button onclick="toggleFilters()" class="text-primary-medium hover:text-primary-dark">
            <svg id="filter-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"/>
            </svg>
        </button>
    </div>
    
    <form id="filter-form" method="GET" action="{{ route('admin.orders.index') }}" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">رقم الطلب</label>
                <input type="text" name="order_number" value="{{ request('order_number') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">اسم العميل</label>
                <input type="text" name="customer_name" value="{{ request('customer_name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                <input type="email" name="customer_email" value="{{ request('customer_email') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">حالة الطلب</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>مكتمل</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">حالة الدفع</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
                    <option value="">الكل</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>فشل</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">طريقة الدفع</label>
                <select name="payment_method" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
                    <option value="">الكل</option>
                    <option value="myfatoorah" {{ request('payment_method') == 'myfatoorah' ? 'selected' : '' }}>MyFatoorah</option>
                    <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>تحويل بنكي</option>
                    <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>نقدي</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">من تاريخ</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">إلى تاريخ</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأدنى للمبلغ</label>
                <input type="number" step="0.01" name="amount_min" value="{{ request('amount_min') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الحد الأقصى للمبلغ</label>
                <input type="number" step="0.01" name="amount_max" value="{{ request('amount_max') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
        </div>
        
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-primary-medium text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                تطبيق الفلترة
            </button>
            <a href="{{ route('admin.orders.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-lg md:text-xl font-semibold text-primary-dark">جميع الطلبات</h2>
                @if(isset($orders) && $orders->total() > 0)
                <p class="text-sm text-gray-500 mt-1">إجمالي الطلبات: {{ $orders->total() }}</p>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.orders.export.excel', request()->query()) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    تصدير Excel
                </a>
                <a href="{{ route('admin.orders.export.pdf', request()->query()) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors duration-200 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                    تصدير PDF
                </a>
            </div>
        </div>
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
                        <div class="text-xs">{{ $item->service->name_ar ?? 'خدمة محذوفة' }} ({{ $item->quantity }})</div>
                        @endforeach
                        @if($order->orderItems->isEmpty())
                        <span class="text-xs text-gray-400">لا توجد خدمات</span>
                        @endif
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
                            @if(isset($error))
                            <p class="text-sm text-red-500 mt-2">{{ $error }}</p>
                            @endif
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

<script>
function toggleFilters() {
    const form = document.getElementById('filter-form');
    const icon = document.getElementById('filter-icon');
    form.classList.toggle('hidden');
    if (form.classList.contains('hidden')) {
        icon.style.transform = 'rotate(0deg)';
    } else {
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>
@endsection
