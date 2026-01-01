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

    <!-- Order Documentation (Video/Audio Upload) - FIRST SECTION -->
    <div class="bg-gradient-to-br from-white via-blue-50 to-indigo-50 rounded-2xl shadow-2xl border-2 border-primary-light/30 p-8 mb-8 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-light/10 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-primary-medium/10 to-transparent rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="bg-gradient-to-br from-primary-medium via-primary-dark to-indigo-600 p-4 rounded-xl shadow-xl transform hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-video text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-primary-dark mb-1">توثيق الطلب (فيديو)</h2>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary-medium"></i>
                            رفع وتوثيق فيديو تنفيذ الطلب للعميل
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Upload Form -->
            <form method="POST" action="{{ route('admin.orders.documentation.upload', $order) }}" enctype="multipart/form-data" class="mb-8 bg-white/90 backdrop-blur-sm rounded-xl p-6 shadow-lg border border-gray-200">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-heading text-primary-medium"></i>
                            عنوان الملف <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" 
                               class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-primary-medium transition-all shadow-sm hover:shadow-md"
                               placeholder="مثال: توثيق تنفيذ الطلب" required>
                    </div>
                    
                    <div>
                        <label for="video" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                            <i class="fas fa-video text-primary-medium"></i>
                            رفع ملف (فيديو/صوت) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="file" id="video" name="video" accept="video/*,audio/*" 
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-primary-medium transition-all file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-gradient-to-r file:from-primary-light file:to-primary-medium file:text-white hover:file:from-primary-medium hover:file:to-primary-dark shadow-sm hover:shadow-md" required>
                        </div>
                        <p class="text-xs text-gray-500 mt-2 flex items-center gap-1">
                            <i class="fas fa-info-circle text-primary-medium"></i>
                            الصيغ المدعومة: MP4, MOV, AVI, WMV (حد أقصى 100MB)
                        </p>
                    </div>
                </div>
                
                <div class="mt-4">
                    <label for="description" class="block text-sm font-bold text-gray-700 mb-2 flex items-center gap-2">
                        <i class="fas fa-align-right text-primary-medium"></i>
                        وصف الملف (اختياري)
                    </label>
                    <textarea id="description" name="description" rows="3"
                              class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-primary-medium transition-all resize-none shadow-sm hover:shadow-md"
                              placeholder="وصف مختصر للملف..."></textarea>
                </div>
                
                <div class="flex justify-end mt-6">
                    <button type="submit" class="bg-gradient-to-r from-primary-medium via-primary-dark to-indigo-600 text-white px-8 py-3 rounded-lg font-bold shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                        <i class="fas fa-upload"></i>
                        رفع الملف
                    </button>
                </div>
            </form>
            
            <!-- Existing Documentation -->
            @if(isset($order->documentation) && $order->documentation && $order->documentation->count() > 0)
            <div class="border-t-2 border-gray-200 pt-8 mt-8">
                <h3 class="text-2xl font-bold text-primary-dark mb-6 flex items-center gap-3">
                    <div class="bg-gradient-to-br from-primary-light to-primary-medium p-2 rounded-lg">
                        <i class="fas fa-folder-open text-white"></i>
                    </div>
                    الملفات المرفوعة ({{ $order->documentation->count() }})
                </h3>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach($order->documentation as $doc)
                    <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
                        <!-- Hover Effect Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-light/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg text-gray-900 mb-2 flex items-center gap-2">
                                        <i class="fas fa-file-video text-primary-medium text-xl"></i>
                                        {{ $doc->title ?? ($doc->description ?: 'ملف توثيق') }}
                                    </h4>
                                    @if($doc->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $doc->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-4 text-xs text-gray-500 flex-wrap">
                                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
                                            <i class="fas fa-calendar text-primary-medium"></i>
                                            {{ $doc->created_at->format('Y-m-d H:i') }}
                                        </span>
                                        @if($doc->formatted_file_size)
                                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
                                            <i class="fas fa-hdd text-primary-medium"></i>
                                            {{ $doc->formatted_file_size }}
                                        </span>
                                        @endif
                                        @if($doc->formatted_duration)
                                        <span class="flex items-center gap-1 bg-gray-100 px-2 py-1 rounded">
                                            <i class="fas fa-clock text-primary-medium"></i>
                                            {{ $doc->formatted_duration }}
                                        </span>
                                        @endif
                                        @if($doc->is_visible_to_customer)
                                        <span class="flex items-center gap-1 bg-green-100 text-green-700 px-2 py-1 rounded">
                                            <i class="fas fa-eye text-green-600"></i>
                                            مرئي للعميل
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $doc->video_url }}" target="_blank" 
                                       class="bg-gradient-to-r from-primary-light to-primary-medium hover:from-primary-medium hover:to-primary-dark text-white p-3 rounded-lg transition-all duration-200 hover:scale-110 shadow-md hover:shadow-lg" title="عرض الفيديو">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.documentation.delete', $doc) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white p-3 rounded-lg transition-all duration-200 hover:scale-110 shadow-md hover:shadow-lg" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الملف؟')" title="حذف">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($doc->video_path)
                            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl overflow-hidden shadow-2xl border-2 border-gray-700 relative">
                                <video controls class="w-full" style="max-height: 400px; min-height: 300px;" preload="metadata" playsinline webkit-playsinline>
                                    <source src="{{ $doc->video_url }}" type="video/mp4">
                                    <source src="{{ $doc->video_url }}" type="video/webm">
                                    <source src="{{ $doc->video_url }}" type="video/ogg">
                                    <div class="text-white p-4 text-center">
                                        متصفحك لا يدعم تشغيل الفيديو. 
                                        <a href="{{ $doc->video_url }}" class="text-blue-400 underline hover:text-blue-300" download>اضغط هنا لتحميل الفيديو</a>
                                    </div>
                                </video>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="text-center py-16 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border-2 border-dashed border-gray-300">
                <div class="bg-white rounded-full p-6 w-24 h-24 mx-auto mb-4 shadow-lg flex items-center justify-center">
                    <i class="fas fa-video text-4xl text-gray-400"></i>
                </div>
                <p class="text-gray-600 text-lg font-semibold mb-2">لا توجد ملفات توثيق مرفوعة بعد</p>
                <p class="text-gray-400 text-sm">قم برفع أول ملف توثيق للطلب</p>
            </div>
            @endif
        </div>
    </div>

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

</div>

@push('scripts')
<script>
    // Enhance video player experience
    document.addEventListener('DOMContentLoaded', function() {
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            // Add error handling
            video.addEventListener('error', function(e) {
                console.error('Video error:', e);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-white p-4 text-center bg-red-600 rounded';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>حدث خطأ في تحميل الفيديو. يرجى المحاولة مرة أخرى.';
                video.parentElement.appendChild(errorDiv);
            });
            
            // Add loading indicator
            video.addEventListener('loadstart', function() {
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 rounded';
                loadingDiv.id = 'loading-' + video.id;
                loadingDiv.innerHTML = '<div class="text-white"><i class="fas fa-spinner fa-spin text-3xl"></i></div>';
                video.parentElement.style.position = 'relative';
                video.parentElement.appendChild(loadingDiv);
            });
            
            // Remove loading indicator when video can play
            video.addEventListener('canplay', function() {
                const loadingDiv = video.parentElement.querySelector('#loading-' + video.id);
                if (loadingDiv) {
                    loadingDiv.remove();
                }
            });
        });
    });
</script>
@endpush
@endsection
