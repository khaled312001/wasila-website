@extends('admin.layouts.app')

@section('title', 'الإعدادات')
@section('page-title', 'الإعدادات')

@section('content')
<div class="w-full">
    <!-- General Settings -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-4 md:p-6 mobile-card">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">الإعدادات العامة</h3>
        <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">اسم الموقع</label>
                    <input type="text" name="site_name" value="{{ $settings['site_name'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">وصف الموقع</label>
                    <textarea name="site_description" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">{{ $settings['site_description'] }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">البريد الإلكتروني للتواصل</label>
                    <input type="email" name="contact_email" value="{{ $settings['contact_email'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">رقم الهاتف</label>
                    <input type="text" name="contact_phone" value="{{ $settings['contact_phone'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">العنوان</label>
                    <textarea name="address" rows="2" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">{{ $settings['address'] }}</textarea>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">شعار الموقع</label>
                    <input type="file" name="logo" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                </div>
                
                <button type="submit" class="w-full btn-primary text-white px-4 py-2 rounded-lg mobile-btn">
                    حفظ الإعدادات العامة
                </button>
            </div>
        </form>
    </div>

    <!-- MyFatoorah Settings -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-4 md:p-6 mobile-card mt-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">إعدادات بوابة الدفع MyFatoorah</h3>
        <form action="{{ route('admin.settings.update-myfatoorah') }}" method="POST">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">مفتاح API</label>
                    <input type="text" name="myfatoorah_api_key" value="{{ $settings['myfatoorah_api_key'] }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">العملة</label>
                    <select name="myfatoorah_currency" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                        <option value="SAR" {{ $settings['myfatoorah_currency'] == 'SAR' ? 'selected' : '' }}>ريال سعودي (SAR)</option>
                        <option value="KWD" {{ $settings['myfatoorah_currency'] == 'KWD' ? 'selected' : '' }}>دينار كويتي (KWD)</option>
                        <option value="AED" {{ $settings['myfatoorah_currency'] == 'AED' ? 'selected' : '' }}>درهم إماراتي (AED)</option>
                        <option value="EGP" {{ $settings['myfatoorah_currency'] == 'EGP' ? 'selected' : '' }}>جنيه مصري (EGP)</option>
                        <option value="BHD" {{ $settings['myfatoorah_currency'] == 'BHD' ? 'selected' : '' }}>دينار بحريني (BHD)</option>
                        <option value="OMR" {{ $settings['myfatoorah_currency'] == 'OMR' ? 'selected' : '' }}>ريال عماني (OMR)</option>
                        <option value="JOD" {{ $settings['myfatoorah_currency'] == 'JOD' ? 'selected' : '' }}>دينار أردني (JOD)</option>
                        <option value="QAR" {{ $settings['myfatoorah_currency'] == 'QAR' ? 'selected' : '' }}>ريال قطري (QAR)</option>
                    </select>
                </div>
                
                <div class="flex items-center">
                    <input type="checkbox" name="myfatoorah_is_test" value="1" {{ $settings['myfatoorah_is_test'] ? 'checked' : '' }}
                           class="h-4 w-4 text-primary-medium focus:ring-primary-medium border-gray-300 rounded">
                    <label class="mr-2 block text-sm text-gray-700">وضع الاختبار</label>
                </div>
                
                <button type="submit" class="w-full btn-primary text-white px-4 py-2 rounded-lg mobile-btn">
                    حفظ إعدادات MyFatoorah
                </button>
            </div>
        </form>
    </div>

   

<!-- System Info Modal -->
<div id="systemInfoModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold text-primary-dark">معلومات النظام</h3>
                    <button onclick="hideSystemInfo()" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div id="systemInfoContent" class="space-y-2">
                    <div class="text-center text-gray-500">جاري تحميل المعلومات...</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function clearCache() {
    if (confirm('هل أنت متأكد من مسح الذاكرة المؤقتة؟')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم مسح الذاكرة المؤقتة بنجاح');
            } else {
                alert('فشل في مسح الذاكرة المؤقتة: ' + data.message);
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء مسح الذاكرة المؤقتة');
        });
    }
}

function createBackup() {
    if (confirm('هل تريد إنشاء نسخة احتياطية؟')) {
        fetch('{{ route("admin.settings.backup") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('تم إنشاء النسخة الاحتياطية بنجاح');
            } else {
                alert('فشل في إنشاء النسخة الاحتياطية: ' + data.message);
            }
        })
        .catch(error => {
            alert('حدث خطأ أثناء إنشاء النسخة الاحتياطية');
        });
    }
}

function showSystemInfo() {
    document.getElementById('systemInfoModal').classList.remove('hidden');
    
    fetch('{{ route("admin.settings.system-info") }}')
        .then(response => response.json())
        .then(data => {
            const content = document.getElementById('systemInfoContent');
            content.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="flex justify-between">
                        <span class="text-gray-600">إصدار PHP:</span>
                        <span class="font-medium">${data.php_version}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">إصدار Laravel:</span>
                        <span class="font-medium">${data.laravel_version}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">خادم الويب:</span>
                        <span class="font-medium">${data.server_software}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">قاعدة البيانات:</span>
                        <span class="font-medium">${data.database_driver}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">الذاكرة المؤقتة:</span>
                        <span class="font-medium">${data.cache_driver}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">طابور المهام:</span>
                        <span class="font-medium">${data.queue_driver}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">مساحة القرص:</span>
                        <span class="font-medium">${data.disk_space.used} / ${data.disk_space.total} (${data.disk_space.percentage}%)</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">استخدام الذاكرة:</span>
                        <span class="font-medium">${data.memory_usage.current} / ${data.memory_usage.limit} (${data.memory_usage.percentage}%)</span>
                    </div>
                </div>
            `;
        })
        .catch(error => {
            document.getElementById('systemInfoContent').innerHTML = 
                '<div class="text-red-500">فشل في تحميل معلومات النظام</div>';
        });
}

function hideSystemInfo() {
    document.getElementById('systemInfoModal').classList.add('hidden');
}
</script>
@endpush
