@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب')

@section('content')
<div class="max-w-6xl mx-auto">

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif
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
                    <label class="block text-sm font-medium text-gray-700">الدولة:</label>
                    <p class="mt-1 text-gray-900">{{ $order->customer_country ?? 'غير محدد' }}</p>
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
    
    <!-- Customer Information Update -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">تحديث بيانات العميل</h2>

        <form method="POST" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">الاسم الكامل</label>
                    <input type="text" id="customer_name" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent" required>
                </div>

                <div>
                    <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني</label>
                    <input type="email" id="customer_email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent" required>
                </div>

                <div>
                    <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="tel" id="customer_phone" name="customer_phone" value="{{ old('customer_phone', $order->customer_phone) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent" required>
                </div>

                <div>
                    <label for="customer_country" class="block text-sm font-medium text-gray-700 mb-2">الدولة</label>
                    <select id="customer_country" name="customer_country"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                        <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                        <option value="السعودية" {{ $order->customer_country === 'السعودية' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'السعودية' : 'Saudi Arabia' }}</option>
                        <option value="الإمارات" {{ $order->customer_country === 'الإمارات' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الإمارات العربية المتحدة' : 'United Arab Emirates' }}</option>
                        <option value="الكويت" {{ $order->customer_country === 'الكويت' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الكويت' : 'Kuwait' }}</option>
                        <option value="قطر" {{ $order->customer_country === 'قطر' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'قطر' : 'Qatar' }}</option>
                        <option value="البحرين" {{ $order->customer_country === 'البحرين' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'البحرين' : 'Bahrain' }}</option>
                        <option value="عمان" {{ $order->customer_country === 'عمان' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'عمان' : 'Oman' }}</option>
                        <option value="الأردن" {{ $order->customer_country === 'الأردن' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الأردن' : 'Jordan' }}</option>
                        <option value="مصر" {{ $order->customer_country === 'مصر' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'مصر' : 'Egypt' }}</option>
                        <option value="لبنان" {{ $order->customer_country === 'لبنان' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'لبنان' : 'Lebanon' }}</option>
                        <option value="سوريا" {{ $order->customer_country === 'سوريا' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'سوريا' : 'Syria' }}</option>
                        <option value="العراق" {{ $order->customer_country === 'العراق' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'العراق' : 'Iraq' }}</option>
                        <option value="اليمن" {{ $order->customer_country === 'اليمن' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                        <option value="السودان" {{ $order->customer_country === 'السودان' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'السودان' : 'Sudan' }}</option>
                        <option value="ليبيا" {{ $order->customer_country === 'ليبيا' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'ليبيا' : 'Libya' }}</option>
                        <option value="تونس" {{ $order->customer_country === 'تونس' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'تونس' : 'Tunisia' }}</option>
                        <option value="الجزائر" {{ $order->customer_country === 'الجزائر' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'الجزائر' : 'Algeria' }}</option>
                        <option value="المغرب" {{ $order->customer_country === 'المغرب' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'المغرب' : 'Morocco' }}</option>
                        <option value="موريتانيا" {{ $order->customer_country === 'موريتانيا' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'موريتانيا' : 'Mauritania' }}</option>
                        <option value="فلسطين" {{ $order->customer_country === 'فلسطين' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'فلسطين' : 'Palestine' }}</option>
                        <option value="تركيا" {{ $order->customer_country === 'تركيا' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'تركيا' : 'Turkey' }}</option>
                        <option value="أخرى" {{ $order->customer_country === 'أخرى' ? 'selected' : '' }}>{{ app()->getLocale() === 'ar' ? 'أخرى' : 'Other' }}</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label for="customer_address" class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                    <textarea id="customer_address" name="customer_address" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">{{ old('customer_address', $order->customer_address) }}</textarea>
                </div>
            </div>
        </form>
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

    <!-- Order Documentation (Video/Audio Upload) -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">توثيق الطلب (فيديو)</h2>
        
        <!-- Upload Form -->
        <form method="POST" action="{{ route('admin.orders.documentation.upload', $order) }}" enctype="multipart/form-data" class="mb-6">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">عنوان الملف <span class="text-red-500">*</span></label>
                    <input type="text" id="title" name="title" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                           placeholder="مثال: توثيق تنفيذ الطلب" required>
                </div>
                
                <div>
                    <label for="video" class="block text-sm font-medium text-gray-700 mb-2">رفع ملف (فيديو/صوت) <span class="text-red-500">*</span></label>
                    <input type="file" id="video" name="video" accept="video/*,audio/*" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent" required>
                    <p class="text-xs text-gray-500 mt-1">الصيغ المدعومة: MP4, MOV, AVI, WMV (حد أقصى 100MB)</p>
                </div>
            </div>
            
            <div class="mt-4">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">وصف الملف (اختياري)</label>
                <textarea id="description" name="description" rows="3"
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                          placeholder="وصف مختصر للملف..."></textarea>
            </div>
            
            <div class="flex justify-end mt-4">
                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                    رفع الملف
                </button>
            </div>
        </form>
        
        <!-- Existing Documentation -->
        @if($order->documentation && $order->documentation->count() > 0)
        <div class="border-t border-gray-200 pt-6 mt-6">
            <h3 class="text-lg font-semibold text-primary-dark mb-4">الملفات المرفوعة</h3>
            <div class="space-y-4">
                @foreach($order->documentation as $doc)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-4">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-900">{{ $doc->title ?? ($doc->description ?: 'ملف توثيق') }}</p>
                            <p class="text-sm text-gray-500">{{ $doc->description ?? '' }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $doc->formatted_file_size ?? '' }} - {{ $doc->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        <a href="{{ $doc->video_url }}" target="_blank" 
                           class="text-primary-medium hover:text-primary-dark">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.documentation.delete', $doc) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700" onclick="return confirm('هل أنت متأكد من حذف هذا الملف؟')">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="text-center py-8 text-gray-500">
            <p>لا توجد ملفات توثيق مرفوعة بعد</p>
        </div>
        @endif
    </div>
</div>
@endsection
