@extends('admin.layouts.app')

@section('title', 'معاملات الدفع')
@section('page-title', 'معاملات الدفع')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow-lg card-shadow mb-6 mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <h3 class="text-base md:text-lg font-semibold text-primary-dark">تصفية المعاملات</h3>
    </div>
    <form method="GET" action="{{ route('admin.myfatoorah.transactions') }}" class="p-4 md:p-6 mobile-form">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 mb-2">حالة الدفع</label>
                <select id="status" name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                    <option value="">جميع الحالات</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>مدفوع</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>في الانتظار</option>
                    <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>فشل</option>
                    <option value="refunded" {{ request('status') == 'refunded' ? 'selected' : '' }}>مسترد</option>
                </select>
            </div>
            
            <div>
                <label for="date_from" class="block text-sm font-medium text-gray-700 mb-2">من تاريخ</label>
                <input type="date" 
                       id="date_from" 
                       name="date_from" 
                       value="{{ request('date_from') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
            </div>
            
            <div>
                <label for="date_to" class="block text-sm font-medium text-gray-700 mb-2">إلى تاريخ</label>
                <input type="date" 
                       id="date_to" 
                       name="date_to" 
                       value="{{ request('date_to') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
            </div>
            
            <div class="flex items-end sm:col-span-2 lg:col-span-1">
                <button type="submit" class="w-full bg-primary-medium text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition duration-300 mobile-btn">
                    تصفية
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Export Button -->
<div class="mb-6">
    <a href="{{ route('admin.myfatoorah.export', request()->query()) }}" 
       class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition duration-300 mobile-btn">
        تصدير النتائج
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg card-shadow mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="text-lg md:text-xl font-semibold text-primary-dark">جميع معاملات الدفع</h2>
            <div class="text-sm text-gray-600">
                إجمالي النتائج: {{ $transactions->total() }}
            </div>
        </div>
    </div>
    <div class="overflow-x-auto mobile-table">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">العميل</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">طريقة الدفع</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">مرجع الدفع</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">التاريخ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($transactions as $transaction)
                <tr class="table-row">
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ $transaction->order_number }}</span>
                            <span class="text-xs text-gray-500 hidden sm:inline">{{ $transaction->customer_name }}</span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                        {{ $transaction->customer_name }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ number_format($transaction->total_amount, 2) }} ريال</span>
                            <span class="text-xs text-gray-500 hidden md:inline">{{ $transaction->payment_method ?? 'غير محدد' }}</span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                        {{ $transaction->payment_method ?? 'غير محدد' }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-500 hidden lg:table-cell">
                        {{ $transaction->payment_reference ? substr($transaction->payment_reference, 0, 20) . '...' : 'غير متوفر' }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 md:px-2.5 md:py-0.5 rounded-full text-xs font-medium
                            @if($transaction->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($transaction->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($transaction->payment_status === 'paid')
                                مدفوع
                            @elseif($transaction->payment_status === 'pending')
                                في الانتظار
                            @else
                                فشل
                            @endif
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                        {{ $transaction->updated_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.myfatoorah.show', $transaction) }}" class="btn-enhanced text-primary-medium hover:text-primary-dark px-2 md:px-3 py-1 rounded text-xs md:text-sm">
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-3 md:px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                            </svg>
                            <p class="text-lg font-medium">لا توجد معاملات</p>
                            <p class="text-sm text-gray-400">لم يتم العثور على أي معاملات في النظام</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($transactions->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
