<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ app()->getLocale() === 'ar' ? 'طلب خدمة - وسيلة' : 'Service Order - Wasila' }}</title>
    
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    
    <style>
        :root {
            --primary: #0f4c81;
            --secondary: #38b6ff;
            --accent: #ff6b35;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 76, 129, 0.3);
        }
        
        .input-focus:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(56, 182, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                    <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-12 w-auto">
                    <h1 class="text-2xl font-bold">{{ app()->getLocale() === 'ar' ? 'طلب خدمة' : 'Service Order' }}</h1>
                </div>
                <a href="{{ url('/') }}" class="text-white hover:text-gray-200 transition">
                    {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Summary -->
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-2xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الخدمة:' : 'Service:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-name">{{ $serviceName ?? '' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-price">{{ number_format($servicePrice ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-quantity">1</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الدولة:' : 'Country:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-country">{{ app()->getLocale() === 'ar' ? 'السعودية' : 'Saudi Arabia' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-4 bg-gray-100 rounded-lg px-4">
                            <span class="text-lg font-bold text-gray-800">{{ app()->getLocale() === 'ar' ? 'المجموع:' : 'Total:' }}</span>
                            <span class="text-xl font-bold text-primary" id="total-amount">{{ number_format($servicePrice ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                        </div>
                    </div>
                    
                    @if($serviceDescription)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">{{ app()->getLocale() === 'ar' ? 'الوصف:' : 'Description:' }}</h3>
                        <p class="text-gray-600 text-sm">{{ $serviceDescription }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Form -->
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-8">
                        {{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Information' }}
                    </h2>
                    
                    <form id="order-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $serviceId ?? '' }}">
                        <input type="hidden" name="service_name" value="{{ $serviceName ?? '' }}">
                        <input type="hidden" name="service_price" value="{{ $servicePrice ?? '' }}">
                        <input type="hidden" name="service_description" value="{{ $serviceDescription ?? '' }}">
                        
                        <!-- Customer Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}
                                </label>
                                <input type="text" name="customer_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email Address *' }}
                                </label>
                                <input type="email" name="customer_email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email address' }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف *' : 'Phone Number *' }}
                                </label>
                                <input type="tel" name="customer_phone" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number' }}">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الدولة *' : 'Country *' }}
                                </label>
                                <select name="customer_country" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                                    <option value="السعودية">{{ app()->getLocale() === 'ar' ? 'السعودية' : 'Saudi Arabia' }}</option>
                                    <option value="الإمارات">{{ app()->getLocale() === 'ar' ? 'الإمارات العربية المتحدة' : 'United Arab Emirates' }}</option>
                                    <option value="الكويت">{{ app()->getLocale() === 'ar' ? 'الكويت' : 'Kuwait' }}</option>
                                    <option value="قطر">{{ app()->getLocale() === 'ar' ? 'قطر' : 'Qatar' }}</option>
                                    <option value="البحرين">{{ app()->getLocale() === 'ar' ? 'البحرين' : 'Bahrain' }}</option>
                                    <option value="عمان">{{ app()->getLocale() === 'ar' ? 'عمان' : 'Oman' }}</option>
                                    <option value="الأردن">{{ app()->getLocale() === 'ar' ? 'الأردن' : 'Jordan' }}</option>
                                    <option value="مصر">{{ app()->getLocale() === 'ar' ? 'مصر' : 'Egypt' }}</option>
                                    <option value="لبنان">{{ app()->getLocale() === 'ar' ? 'لبنان' : 'Lebanon' }}</option>
                                    <option value="سوريا">{{ app()->getLocale() === 'ar' ? 'سوريا' : 'Syria' }}</option>
                                    <option value="العراق">{{ app()->getLocale() === 'ar' ? 'العراق' : 'Iraq' }}</option>
                                    <option value="اليمن">{{ app()->getLocale() === 'ar' ? 'اليمن' : 'Yemen' }}</option>
                                    <option value="السودان">{{ app()->getLocale() === 'ar' ? 'السودان' : 'Sudan' }}</option>
                                    <option value="ليبيا">{{ app()->getLocale() === 'ar' ? 'ليبيا' : 'Libya' }}</option>
                                    <option value="تونس">{{ app()->getLocale() === 'ar' ? 'تونس' : 'Tunisia' }}</option>
                                    <option value="الجزائر">{{ app()->getLocale() === 'ar' ? 'الجزائر' : 'Algeria' }}</option>
                                    <option value="المغرب">{{ app()->getLocale() === 'ar' ? 'المغرب' : 'Morocco' }}</option>
                                    <option value="موريتانيا">{{ app()->getLocale() === 'ar' ? 'موريتانيا' : 'Mauritania' }}</option>
                                    <option value="فلسطين">{{ app()->getLocale() === 'ar' ? 'فلسطين' : 'Palestine' }}</option>
                                    <option value="تركيا">{{ app()->getLocale() === 'ar' ? 'تركيا' : 'Turkey' }}</option>
                                    <option value="أخرى">{{ app()->getLocale() === 'ar' ? 'أخرى' : 'Other' }}</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية *' : 'Quantity *' }}
                                </label>
                                <select name="quantity" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الكمية' : 'Select Quantity' }}</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        <!-- Address -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'العنوان *' : 'Address *' }}
                            </label>
                            <textarea name="customer_address" rows="3" required
                                      class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                      placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل عنوانك الكامل' : 'Enter your complete address' }}"></textarea>
                        </div>
                        
                        <!-- Payment Method -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                            </h3>
                            
                            <div class="space-y-3 mb-6">
                                <!-- MyFatoorah Payment Only -->
                                <div class="border-2 border-green-500 rounded-lg overflow-hidden bg-green-50">
                                    <label class="flex items-center p-6 cursor-pointer hover:bg-green-100 transition">
                                        <input type="radio" name="payment_method" value="myfatoorah" class="mr-4" checked>
                                        <div class="flex items-center">
                                            <img src="{{ asset('images/myfatoorah-logo.svg') }}" alt="MyFatoorah" class="w-10 h-10 mr-4">
                                            <div>
                                                <span class="font-bold text-xl text-green-800">{{ app()->getLocale() === 'ar' ? 'دفع آمن عبر ماي فاتورة' : 'Secure Payment via MyFatoorah' }}</span>
                                                <p class="text-sm text-green-600 mt-1">{{ app()->getLocale() === 'ar' ? 'الطريقة الوحيدة المتاحة للدفع' : 'The only available payment method' }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- MyFatoorah Details -->
                                    <div id="myfatoorah-details" class="payment-details p-6 bg-white border-t border-green-200">
                                        <div class="space-y-4">
                                            <div class="flex items-start">
                                                <svg class="w-6 h-6 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    <h4 class="text-lg font-bold text-green-800 mb-2">
                                                        {{ app()->getLocale() === 'ar' ? 'دفع آمن ومضمون' : 'Secure & Guaranteed Payment' }}
                                                    </h4>
                                                    <p class="text-sm text-green-700 mb-4">
                                                        {{ app()->getLocale() === 'ar' 
                                                            ? 'ستتمكن من الدفع بجميع البطاقات الائتمانية والخصم والدفع الإلكتروني'
                                                            : 'You can pay with all credit/debit cards and electronic payment methods' }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'البطاقات الائتمانية' : 'Credit Cards' }}</p>
                                                </div>
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'التحويل البنكي' : 'Bank Transfer' }}</p>
                                                </div>
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'المحافظ الإلكترونية' : 'E-Wallets' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Submit Button -->
                        <div class="mt-8">
                            <button type="submit" 
                                    class="btn-primary text-white w-full py-4 px-6 rounded-lg font-semibold text-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                                <span id="submit-text">{{ app()->getLocale() === 'ar' ? 'الدفع الآمن' : 'Secure Payment' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة الخيرية' : 'Wasila Charity' }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('order-form');
            const quantitySelect = document.querySelector('select[name="quantity"]');
            const countrySelect = document.querySelector('select[name="customer_country"]');
            const servicePriceElement = document.getElementById('service-price');
            const totalAmountElement = document.getElementById('total-amount');
            const serviceQuantityElement = document.getElementById('service-quantity');
            const serviceCountryElement = document.getElementById('service-country');

            // Update total when quantity changes
            quantitySelect.addEventListener('change', function() {
                const quantity = parseInt(this.value) || 1;
                const price = parseFloat(servicePriceElement.textContent.replace(/[^\d.]/g, '')) || 0;
                const total = price * quantity;

                serviceQuantityElement.textContent = quantity;
                totalAmountElement.textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
            });

            // Update country when country selection changes
            countrySelect.addEventListener('change', function() {
                const countryValue = this.value;
                const countryText = this.options[this.selectedIndex].text;
                serviceCountryElement.textContent = countryText;
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    showNotification('{{ app()->getLocale() === "ar" ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields" }}', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}';
                submitBtn.disabled = true;
                
                // Submit form
                fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        service_id: form.querySelector('input[name="service_id"]').value,
                        service_name: form.querySelector('input[name="service_name"]').value,
                        service_price: form.querySelector('input[name="service_price"]').value,
                        service_description: form.querySelector('input[name="service_description"]').value,
                        customer_name: form.querySelector('input[name="customer_name"]').value,
                        customer_email: form.querySelector('input[name="customer_email"]').value,
                        customer_phone: form.querySelector('input[name="customer_phone"]').value,
                        customer_country: form.querySelector('select[name="customer_country"]').value,
                        customer_address: form.querySelector('textarea[name="customer_address"]').value,
                        quantity: form.querySelector('select[name="quantity"]').value,
                        payment_method: 'myfatoorah'
                    })
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, it might be an error page
                        throw new Error('Server returned non-JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showNotification(data.message, 'success');
                        
                        // Redirect to MyFatoorah payment page
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    } else {
                        showNotification(data.message || '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الطلب" : "An error occurred while processing the order" }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('{{ app()->getLocale() === "ar" ? "حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى" : "Connection error occurred. Please try again" }}', 'error');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
            
            // Notification function
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                    type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>