@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'صفحة الدفع - وسيلة' : 'Payment Page - Wasila')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'صفحة الدفع - وسيلة الخيرية' : 'Payment Page - Wasila Charity' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'أكمل عملية الدفع بأمان وسهولة من خلال ماي فاتورة'
        : 'Complete your payment securely and easily through MyFatoorah' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'دفع آمن, ماي فاتورة, وسيلة, خدمات خيرية'
        : 'secure payment, myfatoorah, wasila, charity services' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/orders/payment') }}"
    type="website"
    author="وسيلة الخيرية"
/>
@endpush

@push('styles')
<style>
    /* Payment page animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    @keyframes pulse {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.05); }
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
    
    .fade-in-up { animation: fadeInUp 0.8s ease-out; }
    .slide-in-left { animation: slideInLeft 0.8s ease-out; }
    .slide-in-right { animation: slideInRight 0.8s ease-out; }
    .pulse-animation { animation: pulse 2s infinite; }
    .float-animation { animation: float 3s ease-in-out infinite; }
    .loading-spinner { animation: spin 1s linear infinite; }
    
    /* Beautiful gradients */
    .hero-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    .card-gradient {
        background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
    }
    
    .button-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.3s ease;
    }
    
    .button-gradient:hover {
        background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.4);
    }
    
    /* Beautiful cards */
    .beautiful-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    
    .beautiful-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    /* Payment methods */
    .payment-method {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    
    .payment-method:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    
    .payment-method.selected {
        border-color: #667eea;
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    }
    
    /* Background patterns */
    .bg-pattern {
        background-image: 
            radial-gradient(circle at 25% 25%, rgba(102, 126, 234, 0.1) 0%, transparent 50%),
            radial-gradient(circle at 75% 75%, rgba(118, 75, 162, 0.1) 0%, transparent 50%);
    }
</style>
@endpush

@section('content')
<!-- Payment Header -->
<section class="hero-gradient text-white py-20 relative overflow-hidden">
    <!-- Background Pattern -->
    <div class="absolute inset-0 bg-pattern"></div>
    
    <!-- Floating Elements -->
    <div class="absolute top-10 left-10 w-20 h-20 bg-white bg-opacity-10 rounded-full float-animation"></div>
    <div class="absolute top-20 right-20 w-16 h-16 bg-white bg-opacity-10 rounded-full float-animation" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-20 left-1/4 w-12 h-12 bg-white bg-opacity-10 rounded-full float-animation" style="animation-delay: 2s;"></div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center fade-in-up">
            <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-8 pulse-animation">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'صفحة الدفع' : 'Payment Page' }}
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                {{ app()->getLocale() === 'ar' 
                    ? 'أكمل عملية الدفع بأمان وسهولة'
                    : 'Complete your payment securely and easily' }}
            </p>
        </div>
    </div>
</section>

<!-- Payment Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="beautiful-card rounded-3xl p-8 sticky top-8 slide-in-left">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">
                            {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                        </h3>
                    </div>
                    
                    <div class="space-y-6">
                        <div class="bg-gray-50 rounded-xl p-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الخدمة' : 'Service' }}</span>
                                <span class="font-semibold" id="service-name">-</span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الكمية' : 'Quantity' }}</span>
                                <span class="font-semibold" id="service-quantity">1</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'سعر الوحدة' : 'Unit Price' }}</span>
                                <span class="font-semibold" id="unit-price">0.00 {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl p-6 text-white">
                            <div class="text-center">
                                <p class="text-sm opacity-90 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'المبلغ الإجمالي' : 'Total Amount' }}
                                </p>
                                <p class="text-3xl font-bold" id="total-amount">
                                    0.00 {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-green-800">
                                        {{ app()->getLocale() === 'ar' ? 'دفع آمن' : 'Secure Payment' }}
                                    </p>
                                    <p class="text-xs text-green-600">
                                        {{ app()->getLocale() === 'ar' ? 'محمي بواسطة ماي فاتورة' : 'Protected by MyFatoorah' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Methods -->
            <div class="lg:col-span-2">
                <div class="beautiful-card rounded-3xl p-8 slide-in-right">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">
                            {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع' : 'Choose Payment Method' }}
                        </h2>
                        <p class="text-gray-600 text-lg">
                            {{ app()->getLocale() === 'ar' 
                                ? 'اختر الطريقة المناسبة لك لإتمام عملية الدفع'
                                : 'Choose the method that suits you to complete the payment' }}
                        </p>
                    </div>

                    <!-- Payment Methods Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Credit Card -->
                        <div class="payment-method border-2 border-gray-200 rounded-2xl p-6 text-center" onclick="selectPaymentMethod('card')">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'بطاقة ائتمان' : 'Credit Card' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? 'فيزا، ماستركارد، أمريكان إكسبريس' : 'Visa, Mastercard, American Express' }}
                            </p>
                        </div>

                        <!-- Bank Transfer -->
                        <div class="payment-method border-2 border-gray-200 rounded-2xl p-6 text-center" onclick="selectPaymentMethod('bank')">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'تحويل بنكي' : 'Bank Transfer' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? 'تحويل مباشر من حسابك البنكي' : 'Direct transfer from your bank account' }}
                            </p>
                        </div>

                        <!-- Apple Pay -->
                        <div class="payment-method border-2 border-gray-200 rounded-2xl p-6 text-center" onclick="selectPaymentMethod('apple')">
                            <div class="w-16 h-16 bg-gradient-to-r from-gray-800 to-gray-900 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'آبل باي' : 'Apple Pay' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? 'دفع سريع وآمن' : 'Fast and secure payment' }}
                            </p>
                        </div>

                        <!-- STC Pay -->
                        <div class="payment-method border-2 border-gray-200 rounded-2xl p-6 text-center" onclick="selectPaymentMethod('stc')">
                            <div class="w-16 h-16 bg-gradient-to-r from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'STC Pay' : 'STC Pay' }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? 'محفظة إلكترونية سعودية' : 'Saudi electronic wallet' }}
                            </p>
                        </div>
                    </div>

                    <!-- Payment Button -->
                    <div class="text-center">
                        <button id="payButton" onclick="processPayment()" 
                                class="button-gradient text-white px-12 py-4 rounded-xl font-semibold text-lg flex items-center justify-center space-x-3 rtl:space-x-reverse mx-auto disabled:opacity-50 disabled:cursor-not-allowed"
                                disabled>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'إتمام الدفع' : 'Complete Payment' }}</span>
                        </button>
                        
                        <p class="text-sm text-gray-500 mt-4">
                            {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع أولاً' : 'Please select a payment method first' }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Loading Overlay -->
<div id="loadingOverlay" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl p-8 text-center max-w-md mx-4">
        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </div>
        <h3 class="text-xl font-semibold text-gray-800 mb-2">
            {{ app()->getLocale() === 'ar' ? 'جاري المعالجة...' : 'Processing...' }}
        </h3>
        <p class="text-gray-600">
            {{ app()->getLocale() === 'ar' ? 'يرجى الانتظار أثناء توجيهك لصفحة الدفع' : 'Please wait while we redirect you to the payment page' }}
        </p>
    </div>
</div>
@endsection

@push('scripts')
<script>
let selectedPaymentMethod = null;
let orderData = null;

// Get order data from URL parameters
function getOrderData() {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    const serviceName = urlParams.get('service_name');
    const servicePrice = urlParams.get('service_price');
    const serviceQuantity = urlParams.get('service_quantity');
    const customerName = urlParams.get('customer_name');
    const customerEmail = urlParams.get('customer_email');
    const customerPhone = urlParams.get('customer_phone');
    const customerAddress = urlParams.get('customer_address');
    
    if (serviceId && serviceName && servicePrice) {
        const price = parseFloat(servicePrice);
        const quantity = parseInt(serviceQuantity) || 1;
        const total = price * quantity;
        
        orderData = {
            serviceId,
            serviceName,
            servicePrice: price,
            serviceQuantity: quantity,
            customerName,
            customerEmail,
            customerPhone,
            customerAddress,
            totalAmount: total
        };
        
        // Update UI
        document.getElementById('service-name').textContent = serviceName;
        document.getElementById('service-quantity').textContent = quantity;
        document.getElementById('unit-price').textContent = price.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
        document.getElementById('total-amount').textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
    } else {
        // Redirect to order page if no data
        window.location.href = '{{ route("orders.create") }}';
    }
}

function selectPaymentMethod(method) {
    // Remove previous selection
    document.querySelectorAll('.payment-method').forEach(el => {
        el.classList.remove('selected');
    });
    
    // Add selection to clicked method
    event.currentTarget.classList.add('selected');
    selectedPaymentMethod = method;
    
    // Enable pay button
    const payButton = document.getElementById('payButton');
    payButton.disabled = false;
    payButton.classList.remove('opacity-50', 'cursor-not-allowed');
}

function processPayment() {
    if (!selectedPaymentMethod || !orderData) {
        alert('{{ app()->getLocale() === "ar" ? "يرجى اختيار طريقة الدفع أولاً" : "Please select a payment method first" }}');
        return;
    }
    
    // Show loading overlay
    document.getElementById('loadingOverlay').classList.remove('hidden');
    
    // Create form data
    const formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('service_id', orderData.serviceId);
    formData.append('quantity', orderData.serviceQuantity);
    formData.append('customer_name', orderData.customerName);
    formData.append('customer_email', orderData.customerEmail);
    formData.append('customer_phone', orderData.customerPhone);
    formData.append('customer_address', orderData.customerAddress);
    formData.append('payment_method', selectedPaymentMethod);
    
    // Submit to order store endpoint
    fetch('{{ route("orders.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'Accept': 'application/json',
        }
    })
    .then(response => {
        if (response.redirected) {
            // Redirect to MyFatoorah payment page
            window.location.href = response.url;
        } else {
            throw new Error('Payment processing failed');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('loadingOverlay').classList.add('hidden');
        alert('{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred while processing payment. Please try again." }}');
    });
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    getOrderData();
});
</script>
@endpush
