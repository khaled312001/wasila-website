<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ app()->getLocale() === 'ar' ? 'طلب خدمة - وسيلة' : 'Service Order - Wasila' }}</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
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
                <a href="{{ route('services') }}" class="text-white hover:text-gray-200 transition">
                    {{ app()->getLocale() === 'ar' ? 'العودة للخدمات' : 'Back to Services' }}
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
                        {{ app()->getLocale() === 'ar' ? 'بيانات الطلب' : 'Order Information' }}
                    </h2>
                    
                    <form action="{{ route('orders.store') }}" method="POST" id="order-form">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $serviceId }}">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Customer Name -->
                            <div class="md:col-span-2">
                                <label for="customer_name" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}
                                </label>
                                <input type="text" 
                                       id="customer_name" 
                                       name="customer_name" 
                                       value="{{ old('customer_name') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('customer_name') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}"
                                       required>
                                @error('customer_name')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Customer Email -->
                            <div>
                                <label for="customer_email" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email *' }}
                                </label>
                                <input type="email" 
                                       id="customer_email" 
                                       name="customer_email" 
                                       value="{{ old('customer_email') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('customer_email') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email' }}"
                                       required>
                                @error('customer_email')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Customer Phone -->
                            <div>
                                <label for="customer_phone" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف *' : 'Phone *' }}
                                </label>
                                <input type="tel" 
                                       id="customer_phone" 
                                       name="customer_phone" 
                                       value="{{ old('customer_phone') }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('customer_phone') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone' }}"
                                       required>
                                @error('customer_phone')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Customer Address -->
                            <div class="md:col-span-2">
                                <label for="customer_address" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'العنوان *' : 'Address *' }}
                                </label>
                                <textarea id="customer_address" 
                                          name="customer_address" 
                                          rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('customer_address') border-red-500 @enderror"
                                          placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل عنوانك الكامل' : 'Enter your full address' }}"
                                          required>{{ old('customer_address') }}</textarea>
                                @error('customer_address')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <!-- Quantity -->
                            <div>
                                <label for="quantity" class="block text-sm font-semibold text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية *' : 'Quantity *' }}
                                </label>
                                <select id="quantity" 
                                        name="quantity" 
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('quantity') border-red-500 @enderror"
                                        required>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ old('quantity', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                                @error('quantity')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Payment Notice -->
                        <div class="mt-8 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-start">
                                <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="text-sm font-semibold text-blue-800 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'دفع آمن' : 'Secure Payment' }}
                                    </h4>
                                    <p class="text-xs text-blue-700">
                                        {{ app()->getLocale() === 'ar' 
                                            ? 'سيتم توجيهك لبوابة دفع آمنة لإتمام عملية الدفع'
                                            : 'You will be redirected to a secure payment gateway to complete your payment' }}
                                    </p>
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
                                {{ app()->getLocale() === 'ar' ? 'تأكيد الطلب والدفع' : 'Confirm Order & Pay' }}
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
            const quantitySelect = document.getElementById('quantity');
            const servicePrice = parseFloat('{{ $servicePrice ?? 0 }}');
            const totalAmountElement = document.getElementById('total-amount');
            const serviceQuantityElement = document.getElementById('service-quantity');
            
            // Update total when quantity changes
            quantitySelect.addEventListener('change', function() {
                const quantity = parseInt(this.value);
                const total = servicePrice * quantity;
                
                serviceQuantityElement.textContent = quantity;
                totalAmountElement.textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
            });
            
            // Form validation
            const form = document.getElementById('order-form');
            form.addEventListener('submit', function(e) {
                const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'customer_address'];
                let isValid = true;
                
                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('{{ app()->getLocale() === "ar" ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields" }}');
                }
            });
        });
    </script>
</body>
</html>
