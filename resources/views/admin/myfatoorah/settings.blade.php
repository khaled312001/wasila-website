@extends('admin.layouts.app')

@section('title', 'إعدادات بوابة الدفع - MyFatoorah')
@section('page-title', 'إعدادات بوابة الدفع')

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-primary-dark">إعدادات بوابة الدفع MyFatoorah</h2>
        <p class="text-gray-600 mt-2">قم بتكوين إعدادات بوابة الدفع للتحكم في عمليات الدفع</p>
    </div>
    
    <div class="p-6">
        <!-- Notice -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
            <p class="text-sm text-blue-800">
                <i class="fas fa-info-circle ml-1"></i>
                <strong>ملاحظة:</strong> هذه الإعدادات تُقرأ مباشرة من ملف <code>.env</code>. لتعديلها، قم بتعديل الملف مباشرة على السيرفر ثم قم بتشغيل <code>php artisan config:clear</code>
            </p>
        </div>
        
        <!-- API Configuration -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-primary-dark mb-4">إعدادات API</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="api_key" class="block text-sm font-medium text-gray-700 mb-2">
                        مفتاح API
                    </label>
                    <input type="text" 
                           id="api_key" 
                           value="{{ $config['api_key'] ?? '' }}"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">من ملف .env: MYFATOORAH_API_KEY</p>
                </div>
                
                <div>
                    <label for="country_iso" class="block text-sm font-medium text-gray-700 mb-2">
                        رمز البلد
                    </label>
                    <input type="text" 
                           id="country_iso" 
                           value="{{ $config['country_iso'] ?? 'SA' }}"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">من ملف .env: MYFATOORAH_COUNTRY_ISO</p>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="currency_iso" class="block text-sm font-medium text-gray-700 mb-2">
                        رمز العملة
                    </label>
                    <input type="text" 
                           id="currency_iso" 
                           value="{{ $config['currency_iso'] ?? 'SAR' }}"
                           readonly
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50 text-gray-600 cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">من ملف .env: MYFATOORAH_CURRENCY</p>
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_test" 
                               {{ ($config['is_test'] ?? false) ? 'checked' : '' }}
                               disabled
                               class="h-4 w-4 text-primary-medium focus:ring-primary-medium border-gray-300 rounded bg-gray-50">
                        <label for="is_test" class="mr-2 block text-sm text-gray-700">
                            وضع الاختبار
                            <span class="text-xs text-gray-500 block">من ملف .env: MYFATOORAH_TEST_MODE={{ ($config['is_test'] ?? false) ? 'true' : 'false' }}</span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Methods Configuration -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-primary-dark mb-4">طرق الدفع المتاحة</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm text-gray-600 mb-4">
                    سيتم جلب طرق الدفع المتاحة تلقائياً من MyFatoorah بناءً على إعداداتك
                </p>
                <button type="button" 
                        onclick="loadPaymentMethods()" 
                        class="bg-primary-medium text-white px-4 py-2 rounded-lg hover:bg-primary-dark transition duration-300">
                    جلب طرق الدفع المتاحة
                </button>
                <div id="payment-methods-result" class="mt-4 hidden"></div>
            </div>
        </div>
        
        <!-- Webhook Configuration -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-primary-dark mb-4">إعدادات Webhook</h3>
            <div class="bg-gray-50 p-4 rounded-lg">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        رابط Callback
                    </label>
                    <div class="flex">
                        <input type="text" 
                               value="{{ route('myfatoorah.callback') }}" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg bg-gray-100 text-gray-600">
                        <button type="button" 
                                onclick="copyToClipboard('{{ route('myfatoorah.callback') }}')"
                                class="px-4 py-2 bg-primary-medium text-white rounded-r-lg hover:bg-primary-dark transition duration-300">
                            نسخ
                        </button>
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        رابط Error
                    </label>
                    <div class="flex">
                        <input type="text" 
                               value="{{ route('myfatoorah.callback') }}" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg bg-gray-100 text-gray-600">
                        <button type="button" 
                                onclick="copyToClipboard('{{ route('myfatoorah.callback') }}')"
                                class="px-4 py-2 bg-primary-medium text-white rounded-r-lg hover:bg-primary-dark transition duration-300">
                            نسخ
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Warning -->
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm text-yellow-800">
                <i class="fas fa-exclamation-triangle ml-1"></i>
                <strong>تعديل الإعدادات:</strong> لتعديل هذه الإعدادات، قم بتعديل ملف <code>.env</code> على السيرفر ثم قم بتشغيل <code>php artisan config:clear</code>
            </p>
        </div>
    </div>
</div>

<!-- Debug Information -->
@if(isset($debug))
<div class="bg-gray-100 rounded-lg p-4 mt-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-3">معلومات التشخيص</h3>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
        <div>
            <strong>رمز البلد الحالي (vcCode):</strong> 
            <span class="text-blue-600 font-mono">{{ $debug['current_vccode'] }}</span>
        </div>
        <div>
            <strong>البلد المحدد:</strong> 
            <span class="text-green-600">{{ $config['country_iso'] }}</span>
        </div>
    </div>
    <div class="mt-3">
        <strong>خريطة تحويل رموز البلد:</strong>
        <div class="mt-2 grid grid-cols-2 md:grid-cols-4 gap-2 text-xs">
            @foreach($debug['country_mapping'] as $iso => $vccode)
            <div class="bg-white p-2 rounded border">
                <span class="font-mono">{{ $iso }}</span> → 
                <span class="font-mono text-blue-600">{{ $vccode }}</span>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function loadPaymentMethods() {
    const resultDiv = document.getElementById('payment-methods-result');
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="text-blue-600">جاري جلب طرق الدفع...</div>';
    
    fetch('{{ route("admin.myfatoorah.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.payment_methods) {
            let methodsHtml = '<div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">';
            methodsHtml += '<strong>تم جلب طرق الدفع بنجاح!</strong><br>';
            methodsHtml += `عدد طرق الدفع المتاحة: ${data.payment_methods.length}`;
            methodsHtml += '</div>';
            
            methodsHtml += '<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">';
            data.payment_methods.forEach(method => {
                methodsHtml += `
                    <div class="bg-white p-3 rounded border">
                        <div class="flex items-center">
                            <img src="${method.ImageUrl}" alt="${method.PaymentMethodAr}" class="w-8 h-8 ml-2">
                            <div>
                                <p class="font-medium text-sm">${method.PaymentMethodAr}</p>
                                <p class="text-xs text-gray-500">${method.PaymentMethodEn}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            methodsHtml += '</div>';
            
            resultDiv.innerHTML = methodsHtml;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>فشل في جلب طرق الدفع!</strong> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>خطأ!</strong> حدث خطأ أثناء جلب طرق الدفع
            </div>
        `;
    });
}

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success message
        const button = event.target;
        const originalText = button.textContent;
        button.textContent = 'تم النسخ!';
        button.classList.add('bg-green-600');
        button.classList.remove('bg-primary-medium');
        
        setTimeout(() => {
            button.textContent = originalText;
            button.classList.remove('bg-green-600');
            button.classList.add('bg-primary-medium');
        }, 2000);
    }).catch(function(err) {
        console.error('Could not copy text: ', err);
    });
}
</script>
@endpush
