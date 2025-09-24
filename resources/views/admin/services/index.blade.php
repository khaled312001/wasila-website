@extends('admin.layouts.app')

@section('title', 'إدارة الخدمات')
@section('page-title', 'إدارة الخدمات')

@section('content')
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <h2 class="text-xl md:text-2xl font-bold text-primary-dark">الخدمات</h2>
    <a href="{{ route('admin.services.create') }}" class="btn-primary text-white px-4 py-2 rounded-lg font-semibold mobile-btn">
        إضافة خدمة جديدة
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden mobile-card">
    <div class="overflow-x-auto mobile-table">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الصورة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الاسم</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">الفئة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">السعر</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden lg:table-cell">ترتيب العرض</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($services as $service)
                <tr class="table-row">
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name_ar }}" class="h-10 w-10 md:h-12 md:w-12 rounded-lg object-cover">
                        @else
                        <div class="h-10 w-10 md:h-12 md:w-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                            </svg>
                        </div>
                        @endif
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $service->name_ar }}</div>
                            <div class="text-xs text-gray-500 hidden md:inline">{{ $service->name_en }}</div>
                            <div class="text-xs text-gray-500 md:hidden">{{ $service->category_ar }}</div>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                        {{ $service->category_ar }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex flex-col">
                            <span class="font-semibold">{{ number_format($service->price, 2) }} ريال</span>
                            <span class="text-xs text-gray-500 hidden lg:inline">ترتيب: {{ $service->sort_order }}</span>
                        </div>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 md:px-2.5 md:py-0.5 rounded-full text-xs font-medium
                            {{ $service->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $service->is_active ? 'نشط' : 'غير نشط' }}
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden lg:table-cell">
                        {{ $service->sort_order }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <div class="flex flex-col sm:flex-row gap-1 sm:gap-2">
                            <a href="{{ route('admin.services.edit', $service) }}" class="btn-enhanced text-primary-medium hover:text-primary-dark px-2 py-1 rounded text-xs">
                                تعديل
                            </a>
                            <form method="POST" action="{{ route('admin.services.destroy', $service) }}" class="inline" onsubmit="return confirm('هل أنت متأكد من حذف هذه الخدمة؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-enhanced text-red-600 hover:text-red-900 px-2 py-1 rounded text-xs">
                                    حذف
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-3 md:px-6 py-8 text-center text-gray-500">
                        <div class="flex flex-col items-center">
                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            <p class="text-lg font-medium">لا توجد خدمات</p>
                            <p class="text-sm text-gray-400">لم يتم العثور على أي خدمات في النظام</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
