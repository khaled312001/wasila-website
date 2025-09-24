@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'طلب خدمة - وسيلة' : 'Order Service - Wasila')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'طلب خدمة - وسيلة الخيرية' : 'Order Service - Wasila Charity' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'اطلب خدمة من وسيلة الخيرية بسهولة وأمان. خدمات إنسانية متكاملة لبناء مجتمع أفضل.'
        : 'Order a service from Wasila Charity easily and securely. Comprehensive humanitarian services for a better society.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'طلب خدمة, وسيلة, خدمات خيرية, دفع آمن, ماي فاتورة'
        : 'order service, wasila, charity services, secure payment, myfatoorah' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/orders/create') }}"
    type="website"
    author="وسيلة الخيرية"
/>
@endpush

@push('styles')
<style>
    /* Beautiful animations */
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
    
    .fade-in-up { animation: fadeInUp 0.8s ease-out; }
    .slide-in-left { animation: slideInLeft 0.8s ease-out; }
    .slide-in-right { animation: slideInRight 0.8s ease-out; }
    .pulse-animation { animation: pulse 2s infinite; }
    .float-animation { animation: float 3s ease-in-out infinite; }
    
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
    
    /* Form styling */
    .form-input {
        background: rgba(255, 255, 255, 0.9);
        border: 2px solid rgba(102, 126, 234, 0.1);
        transition: all 0.3s ease;
    }
    
    .form-input:focus {
        background: rgba(255, 255, 255, 1);
        border-color: #667eea;
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        transform: scale(1.02);
    }
    
    /* Quantity buttons */
    .quantity-btn {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        transition: all 0.2s ease;
    }
    
    .quantity-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
    }
    
    .quantity-btn:active {
        transform: scale(0.95);
    }
    
    /* Success message */
    .success-message {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        animation: slideInUp 0.6s ease-out;
    }
    
    /* Loading animation */
    .loading-spinner {
        animation: spin 1s linear infinite;
    }
    
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
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
<!-- Hero Section -->
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
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
            </div>
            <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'طلب خدمة' : 'Order Service' }}
            </h1>
            <p class="text-xl md:text-2xl text-white/90 max-w-3xl mx-auto leading-relaxed">
                {{ app()->getLocale() === 'ar' 
                    ? 'املأ البيانات التالية لطلب الخدمة المطلوبة'
                    : 'Fill in the following information to order the required service' }}
            </p>
        </div>
    </div>
</section>

<!-- Order Form Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Summary Card -->
            <div class="lg:col-span-1">
                <div class="beautiful-card rounded-3xl p-8 sticky top-8 slide-in-left">
                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-2" id="service-title">
                            {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                        </h3>
                        <p class="text-gray-600" id="service-description">
                            {{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة المطلوبة' : 'Service details' }}
                        </p>
                    </div>
                    
                    <div class="space-y-6">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ app()->getLocale() === 'ar' ? 'الخدمة' : 'Service' }}
                            </label>
                            <input type="text" id="service_name" readonly 
                                   class="form-input w-full px-4 py-3 rounded-xl text-gray-800 font-medium">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                {{ app()->getLocale() === 'ar' ? 'الكمية' : 'Quantity' }}
                            </label>
                            <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">
                                <button type="button" onclick="decreaseQuantity()" 
                                        class="quantity-btn w-12 h-12 rounded-xl text-white flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </button>
                                <input type="number" id="quantity" name="quantity" min="1" value="1" 
                                       class="w-24 text-center px-4 py-3 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-transparent font-bold text-lg" 
                                       onchange="updateTotal()">
                                <button type="button" onclick="increaseQuantity()" 
                                        class="quantity-btn w-12 h-12 rounded-xl text-white flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div class="bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl p-6 text-white">
                            <div class="text-center">
                                <p class="text-sm opacity-90 mb-2" id="service-quantity">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية: 1' : 'Quantity: 1' }}
                                </p>
                                <p class="text-3xl font-bold" id="total_price">
                                    {{ app()->getLocale() === 'ar' ? '0.00 ريال' : '0.00 SAR' }}
                                </p>
                                <p class="text-sm opacity-90 mt-1">
                                    {{ app()->getLocale() === 'ar' ? 'المبلغ الإجمالي' : 'Total Amount' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <div class="lg:col-span-2">
                <div class="beautiful-card rounded-3xl p-8 slide-in-right">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-gray-800 mb-4">
                            {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                        </h2>
                        <p class="text-gray-600 text-lg">
                            {{ app()->getLocale() === 'ar' 
                                ? 'يرجى ملء جميع البيانات المطلوبة لإتمام الطلب'
                                : 'Please fill in all required information to complete your order' }}
                        </p>
                    </div>

                    <form id="orderForm" method="POST" action="{{ route('orders.store') }}" class="space-y-8">
                        @csrf
                        <input type="hidden" id="service_id" name="service_id">
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }} 
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="customer_name" required 
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}">
                            </div>
                            
                            <div>
                                <label class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} 
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="email" name="customer_email" required 
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email address' }}">
                            </div>
                            
                            <div>
                                <label class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }} 
                                    <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="customer_phone" required 
                                       class="form-input w-full px-6 py-4 rounded-xl text-lg"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number' }}">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-lg font-semibold text-gray-700 mb-3">
                                    {{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }} 
                                    <span class="text-red-500">*</span>
                                </label>
                                <textarea name="customer_address" rows="4" required 
                                          class="form-input w-full px-6 py-4 rounded-xl text-lg resize-none"
                                          placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل عنوانك الكامل' : 'Enter your complete address' }}"></textarea>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-2xl p-6 border border-green-200">
                            <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse mb-4">
                                <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="text-center">
                                    <h3 class="text-lg font-semibold text-gray-800">
                                        {{ app()->getLocale() === 'ar' ? 'دفع آمن ومحمي' : 'Secure Payment' }}
                                    </h3>
                                    <p class="text-sm text-gray-600">
                                        {{ app()->getLocale() === 'ar' ? 'بواسطة ماي فاتورة' : 'Powered by MyFatoorah' }}
                                    </p>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">
                                <img src="{{ asset('images/myfatoorah-logo.png') }}" alt="MyFatoorah" class="h-8" onerror="this.style.display='none'">
                                <span class="text-sm text-gray-500">
                                    {{ app()->getLocale() === 'ar' ? 'مدعوم من' : 'Powered by' }} MyFatoorah
                                </span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row gap-4 pt-6">
                            <a href="{{ route('services') }}" 
                               class="flex-1 px-8 py-4 border-2 border-purple-500 text-purple-500 rounded-xl hover:bg-purple-500 hover:text-white transition duration-300 text-center font-semibold text-lg">
                                {{ app()->getLocale() === 'ar' ? 'العودة للخدمات' : 'Back to Services' }}
                            </a>
                            <button type="submit" 
                                    class="flex-1 button-gradient text-white px-8 py-4 rounded-xl font-semibold text-lg flex items-center justify-center space-x-3 rtl:space-x-reverse">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                <span>{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب والدفع' : 'Confirm Order & Pay' }}</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Success Message -->
<div id="successMessage" class="hidden fixed top-4 right-4 z-50 max-w-md">
    <div class="success-message text-white p-6 rounded-2xl shadow-2xl">
        <div class="flex items-center space-x-4 rtl:space-x-reverse">
            <div class="w-12 h-12 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div>
                <h4 class="font-bold text-lg">{{ app()->getLocale() === 'ar' ? 'تم إرسال الطلب بنجاح!' : 'Order submitted successfully!' }}</h4>
                <p class="text-sm opacity-90">{{ app()->getLocale() === 'ar' ? 'سيتم توجيهك لصفحة الدفع' : 'You will be redirected to the payment page' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
let currentPrice = 0;
let currentService = null;

// Get service data from URL parameters
function getServiceFromUrl() {
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('service_id');
    const serviceName = urlParams.get('service_name');
    const servicePrice = urlParams.get('service_price');
    const serviceDescription = urlParams.get('service_description');
    
    if (serviceId && serviceName && servicePrice) {
        currentPrice = parseFloat(servicePrice);
        currentService = {
            id: serviceId,
            name: serviceName,
            price: currentPrice,
            description: serviceDescription || ''
        };
        
        // Populate form
        document.getElementById('service_id').value = serviceId;
        document.getElementById('service_name').value = serviceName;
        document.getElementById('service-title').textContent = serviceName;
        document.getElementById('service-description').textContent = serviceDescription || '';
        
        // Update total price initially
        updateTotal();
    } else {
        // Redirect to services if no service data
        window.location.href = '{{ route("services") }}';
    }
}

function increaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    quantityInput.value = currentValue + 1;
    updateTotal();
}

function decreaseQuantity() {
    const quantityInput = document.getElementById('quantity');
    const currentValue = parseInt(quantityInput.value);
    if (currentValue > 1) {
        quantityInput.value = currentValue - 1;
        updateTotal();
    }
}

function updateTotal() {
    const quantity = parseInt(document.getElementById('quantity').value);
    const total = currentPrice * quantity;
    
    document.getElementById('total_price').textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
    document.getElementById('service-quantity').textContent = '{{ app()->getLocale() === "ar" ? "الكمية" : "Quantity" }}: ' + quantity;
}

function showSuccessMessage() {
    const message = document.getElementById('successMessage');
    message.classList.remove('hidden');
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        message.classList.add('hidden');
    }, 5000);
}

// Form validation and submission
document.getElementById('orderForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentService) {
        alert('{{ app()->getLocale() === "ar" ? "يرجى اختيار خدمة أولاً" : "Please select a service first" }}');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = `
        <svg class="w-6 h-6 loading-spinner" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        <span>{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}</span>
    `;
    submitBtn.disabled = true;
    
    // Show success message
    showSuccessMessage();
    
    // Redirect to payment page after a short delay
    setTimeout(() => {
        const formData = new FormData(this);
        const params = new URLSearchParams();
        
        // Add form data to URL parameters
        for (let [key, value] of formData.entries()) {
            params.append(key, value);
        }
        
        // Redirect to payment page
        window.location.href = '{{ route("orders.payment") }}?' + params.toString();
    }, 1000);
});

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    getServiceFromUrl();
});
</script>
@endpush