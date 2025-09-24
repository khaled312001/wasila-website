@extends('admin.layouts.app')

@section('title', 'إعدادات بوابة الدفع - MyFatoorah')
@section('page-title', 'إعدادات بوابة الدفع')

@section('content')
<div class="bg-white rounded-lg shadow-lg card-shadow">
    <div class="p-6 border-b border-gray-200">
        <h2 class="text-xl font-semibold text-primary-dark">إعدادات بوابة الدفع MyFatoorah</h2>
        <p class="text-gray-600 mt-2">قم بتكوين إعدادات بوابة الدفع للتحكم في عمليات الدفع</p>
    </div>
    
    <form action="{{ route('admin.myfatoorah.settings.update') }}" method="POST" class="p-6">
        @csrf
        
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
                           name="api_key" 
                           value="{{ $config['api_key'] ?? '' }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                           placeholder="أدخل مفتاح API الخاص بك"
                           required>
                    @error('api_key')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div>
                    <label for="country_iso" class="block text-sm font-medium text-gray-700 mb-2">
                        رمز البلد
                    </label>
                    <select id="country_iso" 
                            name="country_iso" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                            required>
                        <option value="SA" {{ ($config['country_iso'] ?? '') == 'SA' ? 'selected' : '' }}>السعودية (SA)</option>
                        <option value="AE" {{ ($config['country_iso'] ?? '') == 'AE' ? 'selected' : '' }}>الإمارات (AE)</option>
                        <option value="KW" {{ ($config['country_iso'] ?? '') == 'KW' ? 'selected' : '' }}>الكويت (KW)</option>
                        <option value="BH" {{ ($config['country_iso'] ?? '') == 'BH' ? 'selected' : '' }}>البحرين (BH)</option>
                        <option value="QA" {{ ($config['country_iso'] ?? '') == 'QA' ? 'selected' : '' }}>قطر (QA)</option>
                        <option value="OM" {{ ($config['country_iso'] ?? '') == 'OM' ? 'selected' : '' }}>عمان (OM)</option>
                        <option value="JO" {{ ($config['country_iso'] ?? '') == 'JO' ? 'selected' : '' }}>الأردن (JO)</option>
                        <option value="LB" {{ ($config['country_iso'] ?? '') == 'LB' ? 'selected' : '' }}>لبنان (LB)</option>
                        <option value="EG" {{ ($config['country_iso'] ?? '') == 'EG' ? 'selected' : '' }}>مصر (EG)</option>
                    </select>
                    @error('country_iso')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                <div>
                    <label for="currency_iso" class="block text-sm font-medium text-gray-700 mb-2">
                        رمز العملة
                    </label>
                    <select id="currency_iso" 
                            name="currency_iso" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent"
                            required>
                        <option value="SAR" {{ ($config['currency_iso'] ?? '') == 'SAR' ? 'selected' : '' }}>الريال السعودي (SAR)</option>
                        <option value="AED" {{ ($config['currency_iso'] ?? '') == 'AED' ? 'selected' : '' }}>الدرهم الإماراتي (AED)</option>
                        <option value="KWD" {{ ($config['currency_iso'] ?? '') == 'KWD' ? 'selected' : '' }}>الدينار الكويتي (KWD)</option>
                        <option value="BHD" {{ ($config['currency_iso'] ?? '') == 'BHD' ? 'selected' : '' }}>الدينار البحريني (BHD)</option>
                        <option value="QAR" {{ ($config['currency_iso'] ?? '') == 'QAR' ? 'selected' : '' }}>الريال القطري (QAR)</option>
                        <option value="OMR" {{ ($config['currency_iso'] ?? '') == 'OMR' ? 'selected' : '' }}>الريال العماني (OMR)</option>
                        <option value="JOD" {{ ($config['currency_iso'] ?? '') == 'JOD' ? 'selected' : '' }}>الدينار الأردني (JOD)</option>
                        <option value="LBP" {{ ($config['currency_iso'] ?? '') == 'LBP' ? 'selected' : '' }}>الليرة اللبنانية (LBP)</option>
                        <option value="EGP" {{ ($config['currency_iso'] ?? '') == 'EGP' ? 'selected' : '' }}>الجنيه المصري (EGP)</option>
                        <option value="USD" {{ ($config['currency_iso'] ?? '') == 'USD' ? 'selected' : '' }}>الدولار الأمريكي (USD)</option>
                    </select>
                    @error('currency_iso')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <div class="flex items-center">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="is_test" 
                               name="is_test" 
                               value="1"
                               {{ ($config['is_test'] ?? false) ? 'checked' : '' }}
                               class="h-4 w-4 text-primary-medium focus:ring-primary-medium border-gray-300 rounded">
                        <label for="is_test" class="mr-2 block text-sm text-gray-700">
                            وضع الاختبار
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
                               value="{{ route('payment.callback') }}" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg bg-gray-100 text-gray-600">
                        <button type="button" 
                                onclick="copyToClipboard('{{ route('payment.callback') }}')"
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
                               value="{{ route('payment.error') }}" 
                               readonly
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-l-lg bg-gray-100 text-gray-600">
                        <button type="button" 
                                onclick="copyToClipboard('{{ route('payment.error') }}')"
                                class="px-4 py-2 bg-primary-medium text-white rounded-r-lg hover:bg-primary-dark transition duration-300">
                            نسخ
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Save Button -->
        <div class="flex justify-end">
            <button type="submit" 
                    class="bg-primary-medium text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition duration-300">
                حفظ الإعدادات
            </button>
        </div>
    </form>
</div>
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
