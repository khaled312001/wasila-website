@extends('admin.layouts.app')

@section('title', 'تفاصيل الطلب')
@section('page-title', 'تفاصيل الطلب')

@push('styles')
<link href="{{ asset('css/order-documentation.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="max-w-6xl mx-auto">

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Order Documentation Section - New Design -->
    <div class="doc-section-new">
        <div class="doc-container-new">
            <!-- Header -->
            <div class="doc-header-new">
                <div class="doc-header-icon-new">
                    <i class="fas fa-video"></i>
                </div>
                <div>
                    <h2 class="doc-title-new">توثيق الطلب (فيديو)</h2>
                    <p class="doc-subtitle-new">رفع وتوثيق فيديو تنفيذ الطلب للعميل</p>
                </div>
            </div>

            <!-- Upload Form -->
            <form method="POST" action="{{ route('admin.orders.documentation.upload', $order) }}" enctype="multipart/form-data" class="doc-form-new">
                @csrf
                <div class="doc-form-row-new">
                    <div class="doc-form-group-new">
                        <label for="title" class="doc-label-new">
                            <i class="fas fa-heading"></i>
                            عنوان الملف <span class="doc-required-new">*</span>
                        </label>
                        <input type="text" id="title" name="title" class="doc-input-new" placeholder="مثال: توثيق تنفيذ الطلب" required>
                    </div>
                    
                    <div class="doc-form-group-new">
                        <label for="video" class="doc-label-new">
                            <i class="fas fa-video"></i>
                            رفع ملف (فيديو/صوت) <span class="doc-required-new">*</span>
                        </label>
                        <input type="file" id="video" name="video" accept="video/*,audio/*" class="doc-file-input-new" required>
                        <p class="doc-file-hint-new">
                            <i class="fas fa-info-circle"></i>
                            الصيغ المدعومة: MP4, MOV, AVI, WMV (حد أقصى 100MB)
                        </p>
                    </div>
                </div>

                <div class="doc-form-group-new">
                    <label for="description" class="doc-label-new">
                        <i class="fas fa-align-right"></i>
                        وصف الملف <span class="doc-optional-new">(اختياري)</span>
                    </label>
                    <textarea id="description" name="description" rows="4" class="doc-textarea-new" placeholder="وصف مختصر للملف..."></textarea>
                </div>

                <div class="doc-submit-wrapper-new">
                    <button type="submit" class="doc-submit-btn-new">
                        <i class="fas fa-upload"></i>
                        رفع الملف
                    </button>
                </div>
            </form>

            <!-- Files List -->
            @if(isset($order->documentation) && $order->documentation && $order->documentation->count() > 0)
            <div class="doc-files-section-new">
                <div class="doc-files-header-new">
                    <h3 class="doc-files-title-new">
                        <i class="fas fa-folder-open"></i>
                        الملفات المرفوعة
                        <span class="doc-files-count-new">{{ $order->documentation->count() }}</span>
                    </h3>
                </div>
                <div class="doc-files-grid-new">
                    @foreach($order->documentation as $doc)
                    <div class="doc-file-card-new">
                        <div class="doc-file-header-new">
                            <div class="doc-file-info-new">
                                <h4 class="doc-file-title-new">
                                    <i class="fas fa-file-video"></i>
                                    {{ $doc->title ?? ($doc->description ?: 'ملف توثيق') }}
                                </h4>
                                @if($doc->description)
                                <p class="doc-file-desc-new">{{ $doc->description }}</p>
                                @endif
                                <div class="doc-file-meta-new">
                                    <span class="doc-meta-item-new">
                                        <i class="fas fa-calendar"></i>
                                        {{ $doc->created_at->format('Y-m-d H:i') }}
                                    </span>
                                    @if($doc->formatted_file_size)
                                    <span class="doc-meta-item-new doc-meta-blue-new">
                                        <i class="fas fa-hdd"></i>
                                        {{ $doc->formatted_file_size }}
                                    </span>
                                    @endif
                                    @if($doc->is_visible_to_customer)
                                    <span class="doc-meta-item-new doc-meta-green-new">
                                        <i class="fas fa-eye"></i>
                                        مرئي للعميل
                                    </span>
                                    @endif
                                </div>
                            </div>
                            <div class="doc-file-actions-new">
                                <a href="{{ $doc->video_url }}" target="_blank" class="doc-action-btn-new doc-action-view-new" title="عرض">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.documentation.delete', $doc) }}" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="doc-action-btn-new doc-action-delete-new" 
                                            onclick="return confirm('هل أنت متأكد من حذف هذا الملف؟')" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        @if($doc->video_path)
                        <div class="doc-video-wrapper-new">
                            <video controls class="doc-video-new" preload="metadata" playsinline webkit-playsinline>
                                <source src="{{ $doc->video_url }}" type="video/mp4">
                                <source src="{{ $doc->video_url }}" type="video/webm">
                                <source src="{{ $doc->video_url }}" type="video/ogg">
                                <div style="color: white; padding: 1.5rem; text-align: center; background: rgba(17, 24, 39, 0.9);">
                                    <i class="fas fa-exclamation-triangle" style="font-size: 1.875rem; margin-bottom: 0.75rem; color: #fbbf24;"></i>
                                    <p style="margin-bottom: 0.5rem;">متصفحك لا يدعم تشغيل الفيديو.</p>
                                    <a href="{{ $doc->video_url }}" style="color: #667eea; text-decoration: underline; font-weight: 500; display: inline-flex; align-items: center; gap: 0.5rem;" download>
                                        <i class="fas fa-download"></i>
                                        اضغط هنا لتحميل الفيديو
                                    </a>
                                </div>
                            </video>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @else
            <div class="doc-empty-new">
                <div class="doc-empty-icon-new">
                    <i class="fas fa-video"></i>
                </div>
                <p class="doc-empty-title-new">لا توجد ملفات توثيق مرفوعة بعد</p>
                <p class="doc-empty-text-new">قم برفع أول ملف توثيق للطلب أعلاه</p>
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
                    @elseif($order->status === 'confirmed' && $order->payment_status === 'pending')
                        في انتظار الدفع
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
    
    <!-- Invoice Section -->
    @if($order->payment_status === 'paid' && $order->invoice_path)
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">الفاتورة</h2>
        
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-600 mb-2">تم تحميل الفاتورة من MyFatoorah</p>
                <p class="text-sm text-gray-500">يمكنك عرض أو تحميل الفاتورة من الرابط أدناه</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ asset('storage/' . $order->invoice_path) }}" target="_blank" 
                   class="inline-flex items-center px-4 py-2 bg-primary-medium text-white rounded-lg hover:bg-primary-dark transition-colors">
                    <i class="fas fa-eye ml-2"></i>
                    عرض الفاتورة
                </a>
                <a href="{{ asset('storage/' . $order->invoice_path) }}" download 
                   class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-download ml-2"></i>
                    تحميل الفاتورة
                </a>
            </div>
        </div>
    </div>
    @elseif($order->payment_status === 'paid' && $order->payment_reference)
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6 mt-6">
        <h2 class="text-xl font-semibold text-primary-dark mb-4">الفاتورة</h2>
        
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-yellow-800">
                <i class="fas fa-exclamation-triangle ml-2"></i>
                الفاتورة غير متوفرة محلياً. يمكنك الحصول عليها من MyFatoorah باستخدام رقم المرجع: 
                <strong>{{ $order->payment_reference }}</strong>
            </p>
        </div>
    </div>
    @endif
    
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
