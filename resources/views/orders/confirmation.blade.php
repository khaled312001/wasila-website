@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة' : 'Order Confirmation - Wasila')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة الخيرية' : 'Order Confirmation - Wasila Charity' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'تأكيد طلبك من وسيلة الخيرية. شكراً لدعمك لمشروعنا الخيري.'
        : 'Confirm your order from Wasila Charity. Thank you for supporting our charitable project.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'تأكيد الطلب, وسيلة, خدمات خيرية, دفع آمن'
        : 'order confirmation, wasila, charity services, secure payment' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/orders/confirmation') }}"
    type="website"
    author="وسيلة الخيرية"
/>
@endpush

@push('styles')
<style>
    /* Confirmation page animations */
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
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .confirmation-animation {
        animation: slideInUp 0.6s ease-out, bounce 0.8s ease-out 0.6s;
    }
    
    .success-icon {
        animation: pulse 2s infinite;
    }
    
    .order-card {
        transition: all 0.3s ease;
    }
    
    .order-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    }
    
    /* Auto redirect countdown */
    .countdown {
        animation: pulse 1s infinite;
    }
</style>
@endpush

@section('content')
<!-- Confirmation Header -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="mb-6">
                <div class="w-24 h-24 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-6 success-icon">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'تم تأكيد طلبك بنجاح!' : 'Your Order is Confirmed!' }}
            </h1>
            <p class="text-lg md:text-xl text-gray-200 max-w-3xl mx-auto">
                {{ app()->getLocale() === 'ar' 
                    ? 'شكراً لك على دعمك لمشروع وسيلة الخيري. سيتم معالجة طلبك قريباً.'
                    : 'Thank you for supporting the Wasila charity project. Your order will be processed soon.' }}
            </p>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-light rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-accent rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-primary-medium rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="order-card bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100 confirmation-animation">
            <!-- Order Header -->
            <div class="bg-gradient-to-r from-primary-light to-primary-medium p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">
                            {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
                        </h2>
                        <p class="text-white/80">
                            {{ app()->getLocale() === 'ar' ? 'رقم الطلب' : 'Order Number' }}: <span class="font-semibold" id="order-number">#{{ session('order_confirmation.order_number', 'N/A') }}</span>
                        </p>
                    </div>
                    <div class="w-16 h-16 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>
            
            <!-- Order Content -->
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Service Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-primary-dark mb-4">
                                {{ app()->getLocale() === 'ar' ? 'معلومات الخدمة' : 'Service Information' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'اسم الخدمة' : 'Service Name' }}
                                    </label>
                                    <p class="text-primary-dark font-semibold" id="service-name">
                                        {{ session('order_confirmation.service_name', 'N/A') }}
                                    </p>
                                </div>
                                
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">
                                            {{ app()->getLocale() === 'ar' ? 'الكمية' : 'Quantity' }}
                                        </label>
                                        <p class="text-primary-dark font-semibold" id="service-quantity">
                                            {{ session('order_confirmation.service_quantity', '1') }}
                                        </p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-600 mb-1">
                                            {{ app()->getLocale() === 'ar' ? 'سعر الوحدة' : 'Unit Price' }}
                                        </label>
                                        <p class="text-primary-dark font-semibold" id="service-price">
                                            {{ number_format(session('order_confirmation.service_price', 0), 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-lg font-semibold text-primary-dark">
                                            {{ app()->getLocale() === 'ar' ? 'المبلغ الإجمالي' : 'Total Amount' }}
                                        </span>
                                        <span class="text-2xl font-bold text-accent" id="order-total">
                                            {{ number_format(session('order_confirmation.total_amount', 0), 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold text-primary-dark mb-4">
                                {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}
                                    </label>
                                    <p class="text-primary-dark font-semibold" id="customer-name">
                                        {{ session('order_confirmation.customer_name', 'N/A') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                                    </label>
                                    <p class="text-primary-dark font-semibold" id="customer-email">
                                        {{ session('order_confirmation.customer_email', 'N/A') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}
                                    </label>
                                    <p class="text-primary-dark font-semibold" id="customer-phone">
                                        {{ session('order_confirmation.customer_phone', 'N/A') }}
                                    </p>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">
                                        {{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}
                                    </label>
                                    <p class="text-primary-dark font-semibold" id="customer-address">
                                        {{ session('order_confirmation.customer_address', 'N/A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-lg p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="text-lg font-semibold text-primary-dark mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'حالة الدفع' : 'Payment Status' }}
                                </h3>
                                <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                    @if(session('order_confirmation.payment_status') === 'paid')
                                        <div class="w-4 h-4 bg-green-500 rounded-full"></div>
                                        <span class="text-green-600 font-semibold">
                                            {{ app()->getLocale() === 'ar' ? 'تم الدفع بنجاح' : 'Payment Successful' }}
                                        </span>
                                    @else
                                        <div class="w-4 h-4 bg-yellow-500 rounded-full"></div>
                                        <span class="text-yellow-600 font-semibold">
                                            {{ app()->getLocale() === 'ar' ? 'في انتظار الدفع' : 'Payment Pending' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            
                            @if(session('order_confirmation.payment_status') === 'paid')
                            <div class="text-right">
                                <p class="text-sm text-gray-600 mb-1">
                                    {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                                </p>
                                <p class="font-semibold text-primary-dark">
                                    {{ session('order_confirmation.payment_method', 'MyFatoorah') }}
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="mt-8 bg-gradient-to-r from-primary-light to-primary-medium rounded-lg p-6 text-white">
                    <h3 class="text-lg font-semibold mb-4">
                        {{ app()->getLocale() === 'ar' ? 'الخطوات التالية' : 'Next Steps' }}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">1</div>
                            <div>
                                <p class="font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'ستتلقى رسالة تأكيد على بريدك الإلكتروني' : 'You will receive a confirmation email' }}
                                </p>
                                <p class="text-sm text-white/80">
                                    {{ app()->getLocale() === 'ar' ? 'تحتوي على تفاصيل طلبك ورقم التتبع' : 'Containing your order details and tracking number' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">2</div>
                            <div>
                                <p class="font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'سيتم معالجة طلبك خلال 24-48 ساعة' : 'Your order will be processed within 24-48 hours' }}
                                </p>
                                <p class="text-sm text-white/80">
                                    {{ app()->getLocale() === 'ar' ? 'وسيتم التواصل معك لتأكيد التفاصيل' : 'We will contact you to confirm the details' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-white bg-opacity-20 rounded-full flex items-center justify-center text-sm font-bold mt-0.5">3</div>
                            <div>
                                <p class="font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'ستتمكن من تتبع حالة طلبك' : 'You can track your order status' }}
                                </p>
                                <p class="text-sm text-white/80">
                                    {{ app()->getLocale() === 'ar' ? 'من خلال رقم الطلب المرفق' : 'Using the order number provided' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('home') }}" 
                       class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition duration-300 text-center">
                        {{ app()->getLocale() === 'ar' ? 'العودة للصفحة الرئيسية' : 'Back to Home Page' }}
                    </a>
                    <a href="{{ route('services') }}" 
                       class="flex-1 px-6 py-3 border-2 border-primary-light text-primary-light rounded-lg hover:bg-primary-light hover:text-white transition duration-300 text-center font-semibold">
                        {{ app()->getLocale() === 'ar' ? 'طلب خدمة أخرى' : 'Order Another Service' }}
                    </a>
                </div>

                <!-- Auto Redirect Notice -->
                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">
                        {{ app()->getLocale() === 'ar' ? 'سيتم توجيهك تلقائياً للصفحة الرئيسية خلال' : 'You will be automatically redirected to the home page in' }}
                        <span class="font-semibold text-primary-dark countdown" id="countdown">10</span>
                        {{ app()->getLocale() === 'ar' ? 'ثانية' : 'seconds' }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Auto redirect countdown
let countdown = 10;
const countdownElement = document.getElementById('countdown');

const timer = setInterval(() => {
    countdown--;
    countdownElement.textContent = countdown;
    
    if (countdown <= 0) {
        clearInterval(timer);
        window.location.href = '{{ route("home") }}';
    }
}, 1000);

// Get order data from URL parameters if not passed from controller
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // If no order data from controller, try to get from URL params
    if (document.getElementById('order-number').textContent.includes('N/A')) {
        const orderNumber = urlParams.get('order_number');
        const serviceName = urlParams.get('service_name');
        const servicePrice = urlParams.get('service_price');
        const serviceQuantity = urlParams.get('service_quantity');
        const customerName = urlParams.get('customer_name');
        const customerEmail = urlParams.get('customer_email');
        const customerPhone = urlParams.get('customer_phone');
        const customerAddress = urlParams.get('customer_address');
        const totalAmount = urlParams.get('total_amount');
        
        if (orderNumber) {
            document.getElementById('order-number').textContent = '#' + orderNumber;
        }
        
        if (serviceName) {
            document.getElementById('service-name').textContent = serviceName;
        }
        
        if (servicePrice && serviceQuantity) {
            const price = parseFloat(servicePrice);
            const quantity = parseInt(serviceQuantity);
            const total = price * quantity;
            
            document.getElementById('service-price').textContent = price.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
            document.getElementById('service-quantity').textContent = quantity;
            document.getElementById('order-total').textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
        }
        
        if (customerName) {
            document.getElementById('customer-name').textContent = customerName;
        }
        
        if (customerEmail) {
            document.getElementById('customer-email').textContent = customerEmail;
        }
        
        if (customerPhone) {
            document.getElementById('customer-phone').textContent = customerPhone;
        }
        
        if (customerAddress) {
            document.getElementById('customer-address').textContent = customerAddress;
        }
        
        if (totalAmount) {
            document.getElementById('order-total').textContent = parseFloat(totalAmount).toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
        }
    }
});
</script>
@endpush