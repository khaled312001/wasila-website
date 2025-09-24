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
    /* Contact Form Success/Error Animations */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -10px, 0);
        }
        70% {
            transform: translate3d(0, -5px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }
    
    .contact-success-animation {
        animation: slideInUp 0.6s ease-out, bounce 0.8s ease-out 0.6s;
    }
    
    .contact-error-animation {
        animation: slideInUp 0.6s ease-out;
    }
    
    /* Enhanced button loading state */
    .btn-loading {
        position: relative;
        overflow: hidden;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>
<style>
    /* Force button colors with maximum specificity */
    .btn-primary, .btn-accent, 
    a.btn-primary, a.btn-accent,
    button.btn-primary, button.btn-accent,
    .inline-block.btn-primary, .inline-block.btn-accent {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        color: white !important;
        border: none !important;
        text-decoration: none !important;
        font-weight: 600 !important;
        padding: 0.75rem 2rem !important;
        border-radius: 0.5rem !important;
        display: inline-block !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3) !important;
    }
    
    .btn-accent, a.btn-accent, button.btn-accent,
    .inline-block.btn-accent {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
    }
    
    /* Hover effects */
    .btn-primary:hover, .btn-accent:hover,
    a.btn-primary:hover, a.btn-accent:hover,
    button.btn-primary:hover, button.btn-accent:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
        color: white !important;
    }
    
    /* Override any white backgrounds */
    .bg-white.btn-primary, .bg-white.btn-accent,
    a.bg-white.btn-primary, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }
    
    .bg-white.btn-accent, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    }
    
    /* Ensure text is always white */
    .btn-primary, .btn-accent,
    a.btn-primary, a.btn-accent,
    button.btn-primary, button.btn-accent,
    .btn-primary *, .btn-accent *,
    a.btn-primary *, a.btn-accent *,
    button.btn-primary *, button.btn-accent * {
        color: white !important;
    }
    
    /* Optimized font sizes for better readability */
    h1 {
        font-size: 2.5rem !important;
        line-height: 1.2 !important;
    }
    
    @media (min-width: 768px) {
        h1 {
            font-size: 3rem !important;
        }
    }
    
    @media (min-width: 1024px) {
        h1 {
            font-size: 3.5rem !important;
        }
    }
    
    h2 {
        font-size: 2rem !important;
        line-height: 1.3 !important;
    }
    
    @media (min-width: 768px) {
        h2 {
            font-size: 2.5rem !important;
        }
    }
    
    @media (min-width: 1024px) {
        h2 {
            font-size: 3rem !important;
        }
    }
    
    h3 {
        font-size: 1.5rem !important;
        line-height: 1.3 !important;
    }
    
    @media (min-width: 768px) {
        h3 {
            font-size: 1.75rem !important;
        }
    }
    
    @media (min-width: 1024px) {
        h3 {
            font-size: 2rem !important;
        }
    }
    
    p {
        font-size: 1rem !important;
        line-height: 1.6 !important;
    }
    
    @media (min-width: 768px) {
        p {
            font-size: 1.125rem !important;
        }
    }
    
    /* Remove SVG from buttons and make text normal */
    .btn-primary svg, .btn-accent svg,
    a.btn-primary svg, a.btn-accent svg,
    button.btn-primary svg, button.btn-accent svg {
        display: none !important;
    }
    
    /* Make button text normal colored */
    .btn-primary, .btn-accent,
    a.btn-primary, a.btn-accent,
    button.btn-primary, button.btn-accent {
        color: white !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    
    /* Remove flex from button spans */
    .btn-primary span, .btn-accent span,
    a.btn-primary span, a.btn-accent span,
    button.btn-primary span, button.btn-accent span {
        display: inline !important;
    }
</style>
@endpush

@section('content')
<!-- Order Header -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'طلب خدمة' : 'Order Service' }}
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-8 text-gray-200 max-w-4xl mx-auto">
                {{ app()->getLocale() === 'ar' 
                    ? 'املأ البيانات التالية لطلب الخدمة المطلوبة'
                    : 'Fill in the following information to order the required service' }}
            </p>
        </div>
    </div>
</section>

<!-- Order Form -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-light rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-accent rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-primary-medium rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
            <!-- Service Info Card -->
            <div class="bg-gradient-to-r from-primary-light to-primary-medium p-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-white opacity-10"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-2xl md:text-3xl font-bold mb-2" id="service-title">
                                {{ app()->getLocale() === 'ar' ? 'اختر خدمة' : 'Select Service' }}
                            </h2>
                            <p class="text-white/90 text-lg" id="service-description">
                                {{ app()->getLocale() === 'ar' ? 'يرجى اختيار الخدمة المطلوبة' : 'Please select the required service' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <div class="text-3xl md:text-4xl font-bold" id="service-price">
                                {{ app()->getLocale() === 'ar' ? '0.00 ريال' : '0.00 SAR' }}
                            </div>
                            <div class="text-white/90 text-sm md:text-base" id="service-quantity">
                                {{ app()->getLocale() === 'ar' ? 'الكمية: 1' : 'Quantity: 1' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Form -->
            <div class="p-8 md:p-12">
                <form id="orderForm" method="POST" action="{{ route('orders.store') }}" class="space-y-8">
                    @csrf
                    <input type="hidden" id="service_id" name="service_id">
                    
                    <!-- Service Selection -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                        <!-- Left Column - Service Details -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-lg md:text-xl font-bold text-primary-dark mb-4">
                                    {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
                                </label>
                                
                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-base md:text-lg font-semibold text-primary-dark mb-3">
                                            {{ app()->getLocale() === 'ar' ? 'الخدمة المطلوبة' : 'Required Service' }}
                                        </label>
                                        <input type="text" id="service_name" readonly 
                                               class="w-full px-4 py-4 border border-gray-300 rounded-xl bg-gray-50 text-primary-dark font-semibold text-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent transition duration-200">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-base md:text-lg font-semibold text-primary-dark mb-3">
                                            {{ app()->getLocale() === 'ar' ? 'الكمية' : 'Quantity' }}
                                        </label>
                                        <div class="flex items-center space-x-4 rtl:space-x-reverse">
                                            <button type="button" onclick="decreaseQuantity()" 
                                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-light to-primary-medium hover:from-primary-medium hover:to-primary-dark text-white flex items-center justify-center transition duration-300 transform hover:scale-105 shadow-lg">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                                </svg>
                                            </button>
                                            <input type="number" id="quantity" name="quantity" min="1" value="1" 
                                                   class="w-24 text-center px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-primary-medium focus:border-transparent font-bold text-lg" 
                                                   onchange="updateTotal()">
                                            <button type="button" onclick="increaseQuantity()" 
                                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-primary-light to-primary-medium hover:from-primary-medium hover:to-primary-dark text-white flex items-center justify-center transition duration-300 transform hover:scale-105 shadow-lg">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-base md:text-lg font-semibold text-primary-dark mb-3">
                                            {{ app()->getLocale() === 'ar' ? 'المبلغ الإجمالي' : 'Total Amount' }}
                                        </label>
                                        <div class="bg-gradient-to-r from-primary-light to-primary-medium text-white rounded-xl p-6 shadow-lg">
                                            <div class="text-3xl md:text-4xl font-bold" id="total_price">
                                                {{ app()->getLocale() === 'ar' ? '0.00 ريال' : '0.00 SAR' }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column - Customer Information -->
                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-semibold text-primary-dark mb-3">
                                    {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                                </label>
                                
                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-primary-dark mb-2">
                                            {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="customer_name" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent transition duration-200"
                                               placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-primary-dark mb-2">
                                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="email" name="customer_email" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent transition duration-200"
                                               placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email address' }}">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-primary-dark mb-2">
                                            {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }} <span class="text-red-500">*</span>
                                        </label>
                                        <input type="tel" name="customer_phone" required 
                                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent transition duration-200"
                                               placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number' }}">
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-primary-dark mb-2">
                                            {{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }} <span class="text-red-500">*</span>
                                        </label>
                                        <textarea name="customer_address" rows="4" required 
                                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent transition duration-200 resize-none"
                                                  placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل عنوانك الكامل' : 'Enter your complete address' }}"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex items-center justify-center space-x-3 rtl:space-x-reverse mb-4">
                            <svg class="w-6 h-6 text-primary-medium" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-sm font-medium text-primary-dark">
                                {{ app()->getLocale() === 'ar' ? 'دفع آمن ومحمي بواسطة ماي فاتورة' : 'Secure payment protected by MyFatoorah' }}
                            </span>
                        </div>
                        
                        <div class="flex items-center justify-center space-x-4 rtl:space-x-reverse">
                            <img src="{{ asset('images/myfatoorah-logo.png') }}" alt="MyFatoorah" class="h-8" onerror="this.style.display='none'">
                            <span class="text-xs text-gray-500">
                                {{ app()->getLocale() === 'ar' ? 'مدعوم من' : 'Powered by' }} MyFatoorah
                            </span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4 pt-6">
                        <a href="{{ route('services') }}" 
                           class="flex-1 px-6 py-3 border border-primary-medium text-primary-medium rounded-lg hover:bg-primary-light hover:text-white transition duration-300 text-center font-semibold">
                            {{ app()->getLocale() === 'ar' ? 'العودة للخدمات' : 'Back to Services' }}
                        </a>
                        <button type="submit" 
                                class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition duration-300 flex items-center justify-center space-x-2 rtl:space-x-reverse">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                            <span>{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب والدفع' : 'Confirm Order & Pay' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
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
        document.getElementById('service-price').textContent = currentPrice.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
        
        // Update total price initially
        updateTotal();
        
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
    
    // Update service price display
    document.getElementById('service-price').textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
}

// Form validation
document.getElementById('orderForm').addEventListener('submit', function(e) {
    if (!currentService) {
        e.preventDefault();
        alert('{{ app()->getLocale() === "ar" ? "يرجى اختيار خدمة أولاً" : "Please select a service first" }}');
        return false;
    }
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing