@extends('admin.layouts.app')

@section('title', 'تفاصيل المعاملة')
@section('page-title', 'تفاصيل المعاملة')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Order Details -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">تفاصيل الطلب</h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-600">رقم الطلب:</span>
                <span class="font-medium">{{ $order->order_number }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">اسم العميل:</span>
                <span class="font-medium">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">البريد الإلكتروني:</span>
                <span class="font-medium">{{ $order->customer_email }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">رقم الهاتف:</span>
                <span class="font-medium">{{ $order->customer_phone }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">العنوان:</span>
                <span class="font-medium">{{ $order->customer_address }}</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">إجمالي المبلغ:</span>
                <span class="font-medium text-primary-dark">{{ number_format($order->total_amount, 2) }} ريال</span>
            </div>
            <div class="flex justify-between">
                <span class="text-gray-600">حالة الطلب:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
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
            <div class="flex justify-between">
                <span class="text-gray-600">تاريخ الطلب:</span>
                <span class="font-medium">{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">تفاصيل الدفع</h3>
        <div class="space-y-4">
            <div class="flex justify-between">
                <span class="text-gray-600">حالة الدفع:</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($order->payment_status === 'paid') bg-green-100 text-green-800
                    @elseif($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                    @else bg-red-100 text-red-800
                    @endif">
                    @if($order->payment_status === 'paid')
                        مدفوع
                    @elseif($order->payment_status === 'pending')
                        في الانتظار
                    @else
                        فشل
                    @endif
                </span>
            </div>
            @if($order->payment_method)
            <div class="flex justify-between">
                <span class="text-gray-600">طريقة الدفع:</span>
                <span class="font-medium">{{ $order->payment_method }}</span>
            </div>
            @endif
            @if($order->payment_reference)
            <div class="flex justify-between">
                <span class="text-gray-600">مرجع الدفع:</span>
                <span class="font-medium text-sm">{{ $order->payment_reference }}</span>
            </div>
            @endif
            @if($order->refund_amount)
            <div class="flex justify-between">
                <span class="text-gray-600">مبلغ الاسترداد:</span>
                <span class="font-medium text-red-600">{{ number_format($order->refund_amount, 2) }} ريال</span>
            </div>
            @endif
            @if($order->refund_reason)
            <div class="flex justify-between">
                <span class="text-gray-600">سبب الاسترداد:</span>
                <span class="font-medium">{{ $order->refund_reason }}</span>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Order Items -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
    <h3 class="text-lg font-semibold text-primary-dark mb-4">عناصر الطلب</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخدمة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الكمية</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">سعر الوحدة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجمالي</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($order->orderItems as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $item->service->name_ar }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $item->quantity }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($item->unit_price, 2) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($item->total_price, 2) }} ريال
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- MyFatoorah Payment Details -->
@if($paymentDetails)
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
    <h3 class="text-lg font-semibold text-primary-dark mb-4">تفاصيل MyFatoorah</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @if(isset($paymentDetails['InvoiceId']))
        <div class="flex justify-between">
            <span class="text-gray-600">معرف الفاتورة:</span>
            <span class="font-medium">{{ $paymentDetails['InvoiceId'] }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['InvoiceStatus']))
        <div class="flex justify-between">
            <span class="text-gray-600">حالة الفاتورة:</span>
            <span class="font-medium">{{ $paymentDetails['InvoiceStatus'] }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['InvoiceValue']))
        <div class="flex justify-between">
            <span class="text-gray-600">قيمة الفاتورة:</span>
            <span class="font-medium">{{ $paymentDetails['InvoiceValue'] }} {{ $paymentDetails['InvoiceDisplayValue'] ?? '' }}</span>
        </div>
        @endif
        @if(isset($paymentDetails['CreatedDate']))
        <div class="flex justify-between">
            <span class="text-gray-600">تاريخ الإنشاء:</span>
            <span class="font-medium">{{ $paymentDetails['CreatedDate'] }}</span>
        </div>
        @endif
    </div>
</div>
@endif

<!-- Actions -->
<div class="flex justify-end space-x-4 space-x-reverse mt-6">
    <a href="{{ route('admin.myfatoorah.transactions') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
        العودة للقائمة
    </a>
    @if($order->payment_status === 'paid' && !$order->refund_amount)
    <button onclick="showRefundModal()" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
        استرداد المبلغ
    </button>
    @endif
</div>

<!-- Refund Modal -->
<div id="refundModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="p-6">
                <h3 class="text-lg font-semibold text-primary-dark mb-4">استرداد المبلغ</h3>
                <form action="{{ route('admin.myfatoorah.refund', $order) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">مبلغ الاسترداد</label>
                        <input type="number" name="amount" step="0.01" max="{{ $order->total_amount }}" 
                               value="{{ $order->total_amount }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">سبب الاسترداد</label>
                        <textarea name="reason" rows="3" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium"></textarea>
                    </div>
                    <div class="flex justify-end space-x-4 space-x-reverse">
                        <button type="button" onclick="hideRefundModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                            إلغاء
                        </button>
                        <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600">
                            تأكيد الاسترداد
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function showRefundModal() {
    document.getElementById('refundModal').classList.remove('hidden');
}

function hideRefundModal() {
    document.getElementById('refundModal').classList.add('hidden');
}
</script>
@endpush
