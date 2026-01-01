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
    <div class="bg-gradient-to-br from-slate-50 via-blue-50/50 to-indigo-50/30 rounded-3xl shadow-2xl border border-primary-light/20 p-8 md:p-10 mb-8 relative overflow-hidden">
        <!-- Decorative Background Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary-light/8 via-primary-medium/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-72 h-72 bg-gradient-to-tr from-indigo-200/10 via-primary-medium/5 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-r from-primary-light/5 to-transparent rounded-full blur-2xl"></div>
        
        <div class="relative z-10">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-10 gap-4">
                <div class="flex items-center gap-5">
                    <div class="relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-medium via-primary-dark to-indigo-600 rounded-2xl blur-lg opacity-50"></div>
                        <div class="relative bg-gradient-to-br from-primary-medium via-primary-dark to-indigo-600 p-5 rounded-2xl shadow-2xl transform hover:scale-105 hover:rotate-3 transition-all duration-300">
                            <i class="fas fa-video text-white text-3xl"></i>
                        </div>
                    </div>
                    <div>
                        <h2 class="text-3xl md:text-4xl font-extrabold text-gray-900 mb-2 bg-gradient-to-r from-primary-dark via-primary-medium to-indigo-600 bg-clip-text text-transparent">
                            توثيق الطلب (فيديو)
                        </h2>
                        <p class="text-sm md:text-base text-gray-600 flex items-center gap-2 font-medium">
                            <i class="fas fa-info-circle text-primary-medium text-lg"></i>
                            رفع وتوثيق فيديو تنفيذ الطلب للعميل
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Upload Form -->
            <form method="POST" action="{{ route('admin.orders.documentation.upload', $order) }}" enctype="multipart/form-data" class="mb-8 bg-white/95 backdrop-blur-md rounded-2xl p-8 shadow-xl border border-gray-200/50 hover:shadow-2xl transition-all duration-300">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                    <!-- File Title Input -->
                    <div class="space-y-2">
                        <label for="title" class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <div class="bg-gradient-to-br from-primary-light to-primary-medium p-2 rounded-lg shadow-md">
                                <i class="fas fa-heading text-white text-sm"></i>
                            </div>
                            <span>عنوان الملف</span>
                            <span class="text-red-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" id="title" name="title" 
                                   class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-medium/20 focus:border-primary-medium transition-all duration-300 shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400 font-medium"
                                   placeholder="مثال: توثيق تنفيذ الطلب" required>
                            <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-light/0 via-primary-medium/0 to-primary-dark/0 opacity-0 hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                        </div>
                    </div>
                    
                    <!-- File Upload Input -->
                    <div class="space-y-2">
                        <label for="video" class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                            <div class="bg-gradient-to-br from-primary-light to-primary-medium p-2 rounded-lg shadow-md">
                                <i class="fas fa-video text-white text-sm"></i>
                            </div>
                            <span>رفع ملف (فيديو/صوت)</span>
                            <span class="text-red-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <div class="relative group">
                                <input type="file" id="video" name="video" accept="video/*,audio/*" 
                                       class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-medium/20 focus:border-primary-medium transition-all duration-300 shadow-sm hover:shadow-md bg-white text-gray-900 file:mr-4 file:py-3 file:px-6 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-gradient-to-r file:from-primary-light file:via-primary-medium file:to-primary-dark file:text-white hover:file:from-primary-medium hover:file:via-primary-dark hover:file:to-indigo-600 file:shadow-lg file:hover:shadow-xl file:transition-all file:duration-300 file:cursor-pointer" required>
                                <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-light/0 via-primary-medium/0 to-primary-dark/0 opacity-0 group-hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 mt-3 flex items-center gap-2 bg-blue-50/50 px-4 py-2 rounded-lg border border-blue-100">
                            <i class="fas fa-info-circle text-primary-medium text-base"></i>
                            <span class="font-medium">الصيغ المدعومة: MP4, MOV, AVI, WMV</span>
                            <span class="text-primary-dark font-bold">(حد أقصى 100MB)</span>
                        </p>
                    </div>
                </div>
                
                <!-- Description Textarea -->
                <div class="mt-6 space-y-2">
                    <label for="description" class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <div class="bg-gradient-to-br from-primary-light to-primary-medium p-2 rounded-lg shadow-md">
                            <i class="fas fa-align-right text-white text-sm"></i>
                        </div>
                        <span>وصف الملف</span>
                        <span class="text-gray-400 text-xs font-normal">(اختياري)</span>
                    </label>
                    <div class="relative">
                        <textarea id="description" name="description" rows="4"
                                  class="w-full px-5 py-4 border-2 border-gray-200 rounded-xl focus:ring-4 focus:ring-primary-medium/20 focus:border-primary-medium transition-all duration-300 resize-none shadow-sm hover:shadow-md bg-white text-gray-900 placeholder-gray-400 font-medium"
                                  placeholder="وصف مختصر للملف..."></textarea>
                        <div class="absolute inset-0 rounded-xl bg-gradient-to-r from-primary-light/0 via-primary-medium/0 to-primary-dark/0 opacity-0 hover:opacity-5 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                </div>
                
                <!-- Submit Button -->
                <div class="flex justify-end mt-8">
                    <button type="submit" class="group relative bg-gradient-to-r from-primary-medium via-primary-dark to-indigo-600 text-white px-10 py-4 rounded-xl font-bold shadow-xl hover:shadow-2xl transform hover:scale-105 hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-primary-dark via-indigo-600 to-primary-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        <i class="fas fa-upload text-lg relative z-10 transform group-hover:translate-y-[-2px] transition-transform duration-300"></i>
                        <span class="relative z-10">رفع الملف</span>
                        <div class="absolute inset-0 bg-white/20 transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                    </button>
                </div>
            </form>
            
            <!-- Existing Documentation -->
            @if(isset($order->documentation) && $order->documentation && $order->documentation->count() > 0)
            <div class="border-t-2 border-gray-200/50 pt-10 mt-10">
                <div class="flex items-center justify-between mb-8">
                    <h3 class="text-2xl md:text-3xl font-extrabold text-gray-900 flex items-center gap-4">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary-light to-primary-medium rounded-xl blur-md opacity-50"></div>
                            <div class="relative bg-gradient-to-br from-primary-light to-primary-medium p-3 rounded-xl shadow-lg">
                                <i class="fas fa-folder-open text-white text-xl"></i>
                            </div>
                        </div>
                        <span class="bg-gradient-to-r from-primary-dark via-primary-medium to-indigo-600 bg-clip-text text-transparent">
                            الملفات المرفوعة
                        </span>
                        <span class="bg-gradient-to-r from-primary-medium to-primary-dark text-white px-4 py-1.5 rounded-full text-lg font-bold shadow-lg">
                            {{ $order->documentation->count() }}
                        </span>
                    </h3>
                </div>
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8">
                    @foreach($order->documentation as $doc)
                    <div class="bg-white/95 backdrop-blur-sm rounded-2xl shadow-xl border-2 border-gray-200/50 p-6 md:p-8 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2 relative overflow-hidden group">
                        <!-- Hover Effect Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-light/10 via-primary-medium/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-light/20 to-transparent rounded-full blur-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-5">
                                <div class="flex-1 min-w-0">
                                    <h4 class="font-bold text-lg md:text-xl text-gray-900 mb-3 flex items-center gap-3">
                                        <div class="bg-gradient-to-br from-primary-light to-primary-medium p-2.5 rounded-lg shadow-md flex-shrink-0">
                                            <i class="fas fa-file-video text-white text-lg"></i>
                                        </div>
                                        <span class="truncate">{{ $doc->title ?? ($doc->description ?: 'ملف توثيق') }}</span>
                                    </h4>
                                    @if($doc->description)
                                    <p class="text-sm text-gray-600 mb-4 leading-relaxed">{{ $doc->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 text-xs md:text-sm text-gray-600 flex-wrap">
                                        <span class="flex items-center gap-2 bg-gradient-to-r from-gray-50 to-gray-100 px-3 py-2 rounded-lg border border-gray-200 shadow-sm">
                                            <i class="fas fa-calendar text-primary-medium"></i>
                                            <span class="font-medium">{{ $doc->created_at->format('Y-m-d H:i') }}</span>
                                        </span>
                                        @if($doc->formatted_file_size)
                                        <span class="flex items-center gap-2 bg-gradient-to-r from-blue-50 to-indigo-50 px-3 py-2 rounded-lg border border-blue-200 shadow-sm">
                                            <i class="fas fa-hdd text-primary-medium"></i>
                                            <span class="font-medium">{{ $doc->formatted_file_size }}</span>
                                        </span>
                                        @endif
                                        @if($doc->formatted_duration)
                                        <span class="flex items-center gap-2 bg-gradient-to-r from-purple-50 to-pink-50 px-3 py-2 rounded-lg border border-purple-200 shadow-sm">
                                            <i class="fas fa-clock text-primary-medium"></i>
                                            <span class="font-medium">{{ $doc->formatted_duration }}</span>
                                        </span>
                                        @endif
                                        @if($doc->is_visible_to_customer)
                                        <span class="flex items-center gap-2 bg-gradient-to-r from-green-50 to-emerald-50 text-green-700 px-3 py-2 rounded-lg border border-green-200 shadow-sm">
                                            <i class="fas fa-eye text-green-600"></i>
                                            <span class="font-bold">مرئي للعميل</span>
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0 ml-3">
                                    <a href="{{ $doc->video_url }}" target="_blank" 
                                       class="group/btn relative bg-gradient-to-r from-primary-light via-primary-medium to-primary-dark hover:from-primary-medium hover:via-primary-dark hover:to-indigo-600 text-white p-3.5 rounded-xl transition-all duration-300 hover:scale-110 shadow-lg hover:shadow-xl transform hover:rotate-3" title="عرض الفيديو">
                                        <i class="fas fa-eye text-base"></i>
                                        <div class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                    </a>
                                    <form method="POST" action="{{ route('admin.documentation.delete', $doc) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="group/btn relative bg-gradient-to-r from-red-500 via-red-600 to-red-700 hover:from-red-600 hover:via-red-700 hover:to-red-800 text-white p-3.5 rounded-xl transition-all duration-300 hover:scale-110 shadow-lg hover:shadow-xl transform hover:rotate-3" 
                                                onclick="return confirm('هل أنت متأكد من حذف هذا الملف؟')" title="حذف">
                                            <i class="fas fa-trash text-base"></i>
                                            <div class="absolute inset-0 bg-white/20 rounded-xl opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($doc->video_path)
                            <div class="bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 rounded-2xl overflow-hidden shadow-2xl border-2 border-gray-700/50 relative group/video">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover/video:opacity-100 transition-opacity duration-300 z-10 pointer-events-none"></div>
                                <video controls class="w-full relative z-0" style="max-height: 400px; min-height: 300px;" preload="metadata" playsinline webkit-playsinline>
                                    <source src="{{ $doc->video_url }}" type="video/mp4">
                                    <source src="{{ $doc->video_url }}" type="video/webm">
                                    <source src="{{ $doc->video_url }}" type="video/ogg">
                                    <div class="text-white p-6 text-center bg-gray-900/90">
                                        <i class="fas fa-exclamation-triangle text-3xl mb-3 text-yellow-400"></i>
                                        <p class="mb-2">متصفحك لا يدعم تشغيل الفيديو.</p>
                                        <a href="{{ $doc->video_url }}" class="text-blue-400 underline hover:text-blue-300 font-medium inline-flex items-center gap-2" download>
                                            <i class="fas fa-download"></i>
                                            اضغط هنا لتحميل الفيديو
                                        </a>
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
            <div class="text-center py-20 bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/20 rounded-2xl border-2 border-dashed border-gray-300/50 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-light/5 to-transparent"></div>
                <div class="relative z-10">
                    <div class="relative inline-block mb-6">
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-light to-primary-medium rounded-full blur-2xl opacity-30"></div>
                        <div class="relative bg-white rounded-full p-8 w-32 h-32 mx-auto shadow-2xl flex items-center justify-center transform hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-video text-5xl text-gray-400"></i>
                        </div>
                    </div>
                    <p class="text-gray-700 text-xl font-bold mb-2">لا توجد ملفات توثيق مرفوعة بعد</p>
                    <p class="text-gray-500 text-base">قم برفع أول ملف توثيق للطلب أعلاه</p>
                </div>
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
