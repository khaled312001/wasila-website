@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Order Header -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-2xl font-bold text-primary-dark mb-2">تفاصيل الطلب</h1>
                <p class="text-gray-600">
                    رقم الطلب: <span class="font-semibold">{{ $order->order_number }}</span>
                </p>
            </div>
            <div class="text-left">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
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
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
            <h2 class="text-xl font-semibold text-primary-dark mb-4">الخدمات المطلوبة</h2>
            
            @foreach($order->orderItems as $item)
            <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-900">{{ $item->service->name_ar }}</h3>
                        <p class="text-sm text-gray-600 mt-1">{{ $item->service->description_ar }}</p>
                        <p class="text-sm text-gray-500 mt-2">
                            الكمية: {{ $item->quantity }}
                        </p>
                    </div>
                    <div class="text-left">
                        <p class="font-semibold text-primary-dark">
                            {{ number_format($item->total_price, 2) }} ريال
                        </p>
                        <p class="text-sm text-gray-500">
                            {{ number_format($item->unit_price, 2) }} ريال لكل وحدة
                        </p>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="border-t border-gray-200 pt-4 mt-4">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold text-primary-dark">المجموع الكلي:</span>
                    <span class="text-xl font-bold text-accent">
                        {{ number_format($order->total_amount, 2) }} ريال
                    </span>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
            <h2 class="text-xl font-semibold text-primary-dark mb-4">معلومات العميل</h2>
            
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">الاسم الكامل:</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_name }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني:</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_email }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">رقم الهاتف:</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">العنوان:</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_address }}</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">تاريخ الطلب:</label>
                    <p class="mt-1 text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Payment Information -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">معلومات الدفع</h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700">حالة الدفع:</label>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
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
            </div>
            
            @if($order->payment_method)
            <div>
                <label class="block text-sm font-medium text-gray-700">طريقة الدفع:</label>
                <p class="mt-1 text-gray-900">{{ $order->payment_method }}</p>
            </div>
            @endif
            
            @if($order->payment_reference)
            <div>
                <label class="block text-sm font-medium text-gray-700">مرجع الدفع:</label>
                <p class="mt-1 text-gray-900">{{ $order->payment_reference }}</p>
            </div>
            @endif
        </div>
    </div>
    
    <!-- Order Status Update -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">تحديث حالة الطلب</h2>
        
        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الطلب</label>
                    <select id="status" name="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>مؤكد</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>قيد المعالجة</option>
                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>مكتمل</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                
                <div>
                    <label for="payment_status" class="block text-sm font-medium text-gray-700 mb-2">حالة الدفع</label>
                    <select id="payment_status" name="payment_status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                        <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>في الانتظار</option>
                        <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>مدفوع</option>
                        <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>فشل</option>
                    </select>
                </div>
            </div>
            
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">ملاحظات</label>
                <textarea id="notes" name="notes" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">{{ old('notes', $order->notes) }}</textarea>
            </div>
            
            <div class="flex justify-end mt-6">
                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                    حفظ التغييرات
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
