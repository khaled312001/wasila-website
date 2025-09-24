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
                                <input type="number" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity', 1) }}"
                                       min="1" 
                                       max="100"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus @error('quantity') border-red-500 @enderror"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل الكمية' : 'Enter quantity' }}"
                                       required>
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
                        
                        <!-- Payment Methods -->
                        <div class="mt-8">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع' : 'Choose Payment Method' }}
                            </h3>
                            
                            <div class="space-y-3 mb-6">
                                <!-- Credit Card -->
                                <div class="border border-gray-300 rounded-lg overflow-hidden">
                                    <label class="flex items-center p-4 cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="payment_method" value="card" class="mr-3" checked>
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                            </svg>
                                            <span class="font-medium">{{ app()->getLocale() === 'ar' ? 'بطاقة ائتمان/خصم' : 'Credit/Debit Card' }}</span>
                                        </div>
                                    </label>
                                    
                                    <!-- Credit Card Details -->
                                    <div id="card-details" class="payment-details p-4 bg-gray-50 border-t border-gray-200">
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'رقم البطاقة *' : 'Card Number *' }}
                                                </label>
                                                <input type="text" name="card_number" placeholder="1234 5678 9012 3456" 
                                                       maxlength="19" pattern="[0-9\s]+"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'اسم حامل البطاقة *' : 'Cardholder Name *' }}
                                                </label>
                                                <input type="text" name="cardholder_name" placeholder="{{ app()->getLocale() === 'ar' ? 'اسم حامل البطاقة' : 'Cardholder Name' }}" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'تاريخ الانتهاء *' : 'Expiry Date *' }}
                                                </label>
                                                <input type="text" name="expiry_date" placeholder="MM/YY" 
                                                       maxlength="5" pattern="[0-9]{2}/[0-9]{2}"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'رمز الأمان *' : 'CVV *' }}
                                                </label>
                                                <input type="text" name="cvv" placeholder="123" 
                                                       maxlength="4" pattern="[0-9]+"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Bank Transfer -->
                                <div class="border border-gray-300 rounded-lg overflow-hidden">
                                    <label class="flex items-center p-4 cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="payment_method" value="bank" class="mr-3">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-green-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium">{{ app()->getLocale() === 'ar' ? 'تحويل بنكي' : 'Bank Transfer' }}</span>
                                        </div>
                                    </label>
                                    
                                    <!-- Bank Transfer Details -->
                                    <div id="bank-details" class="payment-details p-4 bg-gray-50 border-t border-gray-200" style="display: none;">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'اسم البنك *' : 'Bank Name *' }}
                                                </label>
                                                <input type="text" name="bank_name" placeholder="{{ app()->getLocale() === 'ar' ? 'اسم البنك' : 'Bank Name' }}" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'رقم الحساب *' : 'Account Number *' }}
                                                </label>
                                                <input type="text" name="account_number" placeholder="{{ app()->getLocale() === 'ar' ? 'رقم الحساب' : 'Account Number' }}" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'رقم التحويل المرجعي *' : 'Transfer Reference Number *' }}
                                                </label>
                                                <input type="text" name="transfer_reference" placeholder="{{ app()->getLocale() === 'ar' ? 'رقم التحويل المرجعي' : 'Transfer Reference Number' }}" 
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Cash on Delivery -->
                                <div class="border border-gray-300 rounded-lg overflow-hidden">
                                    <label class="flex items-center p-4 cursor-pointer hover:bg-gray-50 transition">
                                        <input type="radio" name="payment_method" value="cod" class="mr-3">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-orange-600 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="font-medium">{{ app()->getLocale() === 'ar' ? 'الدفع عند الاستلام' : 'Cash on Delivery' }}</span>
                                        </div>
                                    </label>
                                    
                                    <!-- COD Details -->
                                    <div id="cod-details" class="payment-details p-4 bg-gray-50 border-t border-gray-200" style="display: none;">
                                        <div class="space-y-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'وقت التسليم المفضل' : 'Preferred Delivery Time' }}
                                                </label>
                                                <select name="delivery_time" class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus">
                                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر وقت التسليم' : 'Select Delivery Time' }}</option>
                                                    <option value="morning">{{ app()->getLocale() === 'ar' ? 'الصباح (8 ص - 12 ظ)' : 'Morning (8 AM - 12 PM)' }}</option>
                                                    <option value="afternoon">{{ app()->getLocale() === 'ar' ? 'بعد الظهر (12 ظ - 5 م)' : 'Afternoon (12 PM - 5 PM)' }}</option>
                                                    <option value="evening">{{ app()->getLocale() === 'ar' ? 'المساء (5 م - 9 م)' : 'Evening (5 PM - 9 PM)' }}</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                                    {{ app()->getLocale() === 'ar' ? 'ملاحظات إضافية' : 'Additional Notes' }}
                                                </label>
                                                <textarea name="delivery_notes" rows="3" placeholder="{{ app()->getLocale() === 'ar' ? 'أي ملاحظات إضافية للتسليم' : 'Any additional delivery notes' }}" 
                                                          class="w-full px-3 py-2 border border-gray-300 rounded-lg input-focus"></textarea>
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
                                <span id="submit-text">{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب' : 'Confirm Order' }}</span>
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
            quantitySelect.addEventListener('input', function() {
                const quantity = parseInt(this.value) || 1;
                const total = servicePrice * quantity;
                
                serviceQuantityElement.textContent = quantity;
                totalAmountElement.textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
            });
            
            // Update submit button text and show/hide payment details based on payment method
            const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
            const paymentDetails = document.querySelectorAll('.payment-details');
            const submitText = document.getElementById('submit-text');
            
            paymentMethods.forEach(method => {
                method.addEventListener('change', function() {
                    // Hide all payment details first
                    paymentDetails.forEach(detail => {
                        detail.style.display = 'none';
                    });
                    
                    // Show selected payment method details
                    if (this.value === 'card') {
                        document.getElementById('card-details').style.display = 'block';
                        submitText.textContent = '{{ app()->getLocale() === "ar" ? "تأكيد الطلب والدفع" : "Confirm Order & Pay" }}';
                    } else if (this.value === 'bank') {
                        document.getElementById('bank-details').style.display = 'block';
                        submitText.textContent = '{{ app()->getLocale() === "ar" ? "تأكيد الطلب" : "Confirm Order" }}';
                    } else if (this.value === 'cod') {
                        document.getElementById('cod-details').style.display = 'block';
                        submitText.textContent = '{{ app()->getLocale() === "ar" ? "تأكيد الطلب" : "Confirm Order" }}';
                    }
                });
            });
            
            // Show card details by default since it's checked
            document.getElementById('card-details').style.display = 'block';
            
            // Format card number input
            const cardNumberInput = document.querySelector('input[name="card_number"]');
            if (cardNumberInput) {
                cardNumberInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\s+/g, '').replace(/[^0-9]/gi, '');
                    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
                    e.target.value = formattedValue;
                });
            }
            
            // Format expiry date input
            const expiryDateInput = document.querySelector('input[name="expiry_date"]');
            if (expiryDateInput) {
                expiryDateInput.addEventListener('input', function(e) {
                    let value = e.target.value.replace(/\D/g, '');
                    if (value.length >= 2) {
                        value = value.substring(0, 2) + '/' + value.substring(2, 4);
                    }
                    e.target.value = value;
                });
            }
            
            // Format CVV input
            const cvvInput = document.querySelector('input[name="cvv"]');
            if (cvvInput) {
                cvvInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/[^0-9]/g, '');
                });
            }
            
            // Form submission
            const form = document.getElementById('order-form');
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const requiredFields = ['customer_name', 'customer_email', 'customer_phone', 'customer_address'];
                let isValid = true;
                
                // التحقق من الحقول الأساسية
                requiredFields.forEach(fieldName => {
                    const field = document.getElementById(fieldName);
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                // التحقق من حقول الدفع بناءً على الطريقة المختارة
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                
                if (selectedPaymentMethod === 'card') {
                    const cardFields = ['card_number', 'cardholder_name', 'expiry_date', 'cvv'];
                    cardFields.forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (!field.value.trim()) {
                            field.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                } else if (selectedPaymentMethod === 'bank') {
                    const bankFields = ['bank_name', 'account_number', 'transfer_reference'];
                    bankFields.forEach(fieldName => {
                        const field = document.querySelector(`[name="${fieldName}"]`);
                        if (!field.value.trim()) {
                            field.classList.add('border-red-500');
                            isValid = false;
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });
                }
                
                if (!isValid) {
                    showNotification('{{ app()->getLocale() === "ar" ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields" }}', 'error');
                    return;
                }
                
                // Get selected payment method
                const selectedPaymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}';
                submitBtn.disabled = true;
                
                // Send form data via AJAX
                const formData = new FormData(form);
                
                fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    if (data.success) {
                        // Show success notification
                        showNotification(data.message, 'success');
                        
                        // Redirect to home page after 3 seconds
                        setTimeout(() => {
                            window.location.href = '{{ route("home") }}';
                        }, 3000);
                    } else {
                        // Show error notification
                        showNotification(data.message || '{{ app()->getLocale() === "ar" ? "حدث خطأ غير متوقع" : "An unexpected error occurred" }}', 'error');
                    }
                })
                .catch(error => {
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Show error notification
                    showNotification('{{ app()->getLocale() === "ar" ? "حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى" : "Connection error. Please try again" }}', 'error');
                    console.error('Error:', error);
                });
            });
            
            // Notification function
            function showNotification(message, type = 'success') {
                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.notification');
                existingNotifications.forEach(notification => notification.remove());
                
                // Create notification element
                const notification = document.createElement('div');
                notification.className = `notification fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-md transform transition-all duration-300 translate-x-full`;
                
                if (type === 'success') {
                    notification.classList.add('bg-green-500', 'text-white');
                } else {
                    notification.classList.add('bg-red-500', 'text-white');
                }
                
                notification.innerHTML = `
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            ${type === 'success' ? 
                                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>' :
                                '<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>'
                            }
                        </svg>
                        <span>${message}</span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                `;
                
                document.body.appendChild(notification);
                
                // Animate in
                setTimeout(() => {
                    notification.classList.remove('translate-x-full');
                }, 100);
                
                // Auto remove after 5 seconds
                setTimeout(() => {
                    notification.classList.add('translate-x-full');
                    setTimeout(() => {
                        if (notification.parentElement) {
                            notification.remove();
                        }
                    }, 300);
                }, 5000);
            }
        });
    </script>
</body>
</html>
