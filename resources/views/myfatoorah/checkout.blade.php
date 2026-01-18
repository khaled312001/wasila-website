<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
        <title>{{ app()->getLocale() === 'ar' ? 'الدفع الآمن - وسيلة' : 'Secure Payment - Wasila' }}</title>
        <link rel="stylesheet" href="{{asset('vendor/myfatoorah/css/style.css')}}"/>
        
        <!-- Error Fixes Script - Must be loaded first -->
        <script src="{{ asset('js/error-fixes.js') }}"></script>
        
        <!-- Tailwind CSS for styling -->
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
            
            .payment-method-item {
                transition: all 0.3s ease;
            }
            
            .payment-method-item:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            }
            
            .payment-method-item.border-primary {
                border-color: var(--primary) !important;
                background-color: rgba(15, 76, 129, 0.05);
            }
            
            .bg-primary-light {
                background-color: rgba(15, 76, 129, 0.05);
            }
        </style>
    </head>

    <body dir="{{App::isLocale('ar') ? 'rtl' : 'ltr'}}" class="bg-gray-50">
        <!-- Header -->
        <header class="gradient-bg text-white py-6">
            <div class="max-w-6xl mx-auto px-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                        <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-12 w-auto">
                        <h1 class="text-2xl font-bold">{{ app()->getLocale() === 'ar' ? 'الدفع الآمن' : 'Secure Payment' }}</h1>
                    </div>
                    <a href="{{ url('/') }}" class="text-white hover:text-gray-200 transition">
                        {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                    </a>
                </div>
            </div>
        </header>

        <!-- Order Summary -->
        @if(isset($order))
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="glass-effect rounded-2xl p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">{{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الخدمة:' : 'Service:' }}</span>
                                <span class="font-semibold">{{ $order->orderItems->first()->service->name_ar ?? 'Service' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}</span>
                                <span class="font-semibold">{{ number_format($order->orderItems->first()->unit_price ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}</span>
                                <span class="font-semibold">{{ $order->orderItems->first()->quantity ?? 1 }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h3 class="font-semibold text-gray-800 mb-3">{{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Info' }}</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الاسم:' : 'Name:' }}</span>
                                <span class="font-semibold">{{ $order->customer_name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'رقم الطلب:' : 'Order #:' }}</span>
                                <span class="font-semibold">{{ $order->order_number }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-bold text-gray-800">{{ app()->getLocale() === 'ar' ? 'المجموع:' : 'Total:' }}</span>
                        <span class="text-xl font-bold text-primary">{{ number_format($order->total_amount, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif
        <!-- Payment Methods Selection -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            @if(empty($availableMethods))
                <div class="glass-effect rounded-2xl p-6 text-center">
                    <div class="text-red-600 mb-4">
                        <i class="fas fa-exclamation-triangle text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد طرق دفع متاحة' : 'No Payment Methods Available' }}
                    </h3>
                    <p class="text-gray-600">
                        {{ app()->getLocale() === 'ar' 
                            ? 'يرجى التواصل مع الدعم لتفعيل طرق الدفع' 
                            : 'Please contact support to enable payment methods' }}
                    </p>
                </div>
            @else
                <div class="glass-effect rounded-2xl p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع' : 'Choose Payment Method' }}
                    </h2>
                    
                    <div class="space-y-4">
                        @foreach($availableMethods as $method)
                            <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-primary transition cursor-pointer payment-method-item"
                                 data-payment-method-id="{{ $method['PaymentMethodId'] }}"
                                 onclick="selectPaymentMethod({{ $method['PaymentMethodId'] }}, '{{ $method['PaymentMethodEn'] ?? $method['PaymentMethodAr'] ?? 'Payment' }}')">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        @if(isset($method['PaymentMethodLogoUrl']))
                                            <img src="{{ $method['PaymentMethodLogoUrl'] }}" 
                                                 alt="{{ $method['PaymentMethodEn'] ?? $method['PaymentMethodAr'] ?? 'Payment' }}" 
                                                 class="w-12 h-12 mr-4">
                                        @endif
                                        <div>
                                            <h3 class="font-semibold text-gray-800">
                                                {{ app()->getLocale() === 'ar' 
                                                    ? ($method['PaymentMethodAr'] ?? $method['PaymentMethodEn'] ?? 'دفع') 
                                                    : ($method['PaymentMethodEn'] ?? $method['PaymentMethodAr'] ?? 'Payment') }}
                                            </h3>
                                            @if(isset($method['PaymentMethodCode']))
                                                <p class="text-sm text-gray-600">{{ $method['PaymentMethodCode'] }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="payment-method-check hidden">
                                        <i class="fas fa-check-circle text-green-500 text-2xl"></i>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <div class="mt-6">
                        <button id="proceed-to-payment-btn" 
                                onclick="proceedToPayment()" 
                                disabled
                                class="w-full bg-primary text-white py-3 px-6 rounded-lg font-semibold hover:bg-primary-dark transition disabled:bg-gray-400 disabled:cursor-not-allowed">
                            {{ app()->getLocale() === 'ar' ? 'المتابعة للدفع' : 'Proceed to Payment' }}
                        </button>
                    </div>
                </div>
            @endif
        </div>

        <script>
            let selectedPaymentMethodId = null;
            
            function selectPaymentMethod(methodId, methodName) {
                // Remove selection from all items
                document.querySelectorAll('.payment-method-item').forEach(item => {
                    item.classList.remove('border-primary', 'bg-primary-light');
                    item.querySelector('.payment-method-check').classList.add('hidden');
                });
                
                // Add selection to clicked item
                const clickedItem = event.currentTarget;
                clickedItem.classList.add('border-primary', 'bg-primary-light');
                clickedItem.querySelector('.payment-method-check').classList.remove('hidden');
                
                selectedPaymentMethodId = methodId;
                document.getElementById('proceed-to-payment-btn').disabled = false;
                
                console.log('Selected payment method:', methodId, methodName);
            }
            
            function proceedToPayment() {
                if (!selectedPaymentMethodId) {
                    alert('{{ app()->getLocale() === "ar" ? "يرجى اختيار طريقة الدفع" : "Please select a payment method" }}');
                    return;
                }
                
                // Show loading
                const btn = document.getElementById('proceed-to-payment-btn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>{{ app()->getLocale() === "ar" ? "جاري التوجيه..." : "Redirecting..." }}';
                
                // Redirect to payment URL
                window.location.href = '{{ route("myfatoorah.index") }}?oid={{ $order->id }}&pmid=' + selectedPaymentMethodId;
            }
        </script>
        
        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة' : 'Wasila' }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
            </div>
        </footer>
    </body>
</html>