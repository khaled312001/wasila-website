@extends('admin.layouts.app')

@section('title', 'إدارة العملاء')
@section('page-title', 'إدارة العملاء')

@section('content')
<!-- Filters -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-semibold text-primary-dark">فلترة العملاء</h3>
        <button onclick="toggleFilters()" class="text-primary-medium hover:text-primary-dark">
            <svg id="filter-icon" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M3 3a1 1 0 011-1h12a1 1 0 011 1v3a1 1 0 01-.293.707L12 11.414V15a1 1 0 01-.293.707l-2 2A1 1 0 018 17v-5.586L3.293 6.707A1 1 0 013 6V3z"/>
            </svg>
        </button>
    </div>
    
    <form id="filter-form" method="GET" action="{{ route('admin.customers.index') }}" class="hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">الاسم</label>
                <input type="text" name="name" value="{{ request('name') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ request('email') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium">
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
        </div>
        
        <div class="flex gap-2 mt-4">
            <button type="submit" class="bg-primary-medium text-white px-6 py-2 rounded-lg hover:bg-primary-dark">
                تطبيق الفلترة
            </button>
            <a href="{{ route('admin.customers.index') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                إعادة تعيين
            </a>
        </div>
    </form>
</div>

<!-- Export Buttons -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-6">
    <div class="flex justify-end gap-2">
        <a href="{{ route('admin.customers.export.excel', request()->query()) }}" 
           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
            تصدير Excel
        </a>
        <a href="{{ route('admin.customers.export.pdf', request()->query()) }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
            </svg>
            تصدير PDF
        </a>
    </div>
</div>

<!-- Customers Table -->
<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الاسم</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">البريد الإلكتروني</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الهاتف</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الطلبات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">عدد الرسائل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">تاريخ التسجيل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($customers as $customer)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            @if($customer->avatar)
                            <img src="{{ $customer->avatar }}" alt="{{ $customer->name }}" class="h-10 w-10 rounded-full ml-3">
                            @else
                            <div class="h-10 w-10 rounded-full bg-primary-light flex items-center justify-center ml-3">
                                <span class="text-white font-semibold">{{ substr($customer->name, 0, 1) }}</span>
                            </div>
                            @endif
                            <div>
                                <div class="text-sm font-medium text-gray-900">{{ $customer->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->email }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->phone ?? 'غير محدد' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->orders_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->messages_count }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $customer->created_at->format('Y-m-d') }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.customers.show', $customer) }}" class="text-primary-medium hover:text-primary-dark">
                            عرض التفاصيل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                        لا توجد عملاء
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($customers->hasPages())
    <div class="px-6 py-4 border-t border-gray-200">
        {{ $customers->links() }}
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

