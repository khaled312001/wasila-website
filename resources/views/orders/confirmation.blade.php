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

@section('content')
<!-- Confirmation Header -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <div class="mb-6">
                <div class="w-20 h-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'تم تأكيد طلبك!' : 'Your Order is Confirmed!' }}
            </h1>
            <p class="text-xl text-gray-200 max-w-3xl mx-auto">
                {{ app()->getLocale() === 'ar' 
                    ? 'شكراً لك على دعمك لمشروع وسيلة الخيري. سيتم معالجة طلبك قريباً.'
                    : 'Thank you for supporting the Wasila charity project. Your order will be processed soon.' }}
            </p>
        </div>
    </div>
</section>

<!-- Order Details -->
<section class="py-16 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <!-- Order Summary Header -->
            <div class="bg-gradient-to-r from-green-500 to-green-600 p-8 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold mb-2">
                            {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                        </h2>
                        <p class="text-green-100">
                            {{ app()->getLocale() === 'ar' ? 'رقم الطلب' : 'Order Number' }}: <span class="font-semibold" id="order-number">#{{ $orderData['order_number'] ?? 'N/A' }}</span>
                        </p>
                    </div>
                    <div class="text-right">
                        <div class="text-3xl font-bold" id="order-total">
                            {{ number_format($orderData['total_amount'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}
                        </div>
                        <div class="text-green-100 text-sm">
                            {{ app()->getLocale() === 'ar' ? 'المبلغ الإجمالي' : 'Total Amount' }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Details -->
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Service Information -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">
                            {{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <div class="flex justify-between items-start">
                                    <div>
                                        <h4 class="font-semibold text-gray-800">{{ $orderData['service_name'] ?? 'Service' }}</h4>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ app()->getLocale() === 'ar' ? 'الكمية' : 'Quantity' }}: {{ $orderData['service_quantity'] ?? 1 }}
                                        </p>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-semibold text-gray-800">
                                            {{ number_format(($orderData['service_price'] ?? 0) * ($orderData['service_quantity'] ?? 1), 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            {{ number_format($orderData['service_price'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }} × {{ $orderData['service_quantity'] ?? 1 }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div>
                        <h3 class="text-xl font-semibold text-gray-800 mb-6">
                            {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                        </h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $orderData['customer_name'] ?? 'Customer Name' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'ar' ? 'الاسم الكامل' : 'Full Name' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $orderData['customer_email'] ?? 'customer@example.com' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-3 rtl:space-x-reverse">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $orderData['customer_phone'] ?? '+966 50 123 4567' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</p>
                                </div>
                            </div>
                            
                            <div class="flex items-start space-x-3 rtl:space-x-reverse">
                                <div class="w-10 h-10 bg-primary-100 rounded-full flex items-center justify-center mt-1">
                                    <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800">{{ $orderData['customer_address'] ?? 'Customer Address' }}</p>
                                    <p class="text-sm text-gray-600">{{ app()->getLocale() === 'ar' ? 'العنوان' : 'Address' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">
                                {{ app()->getLocale() === 'ar' ? 'حالة الدفع' : 'Payment Status' }}
                            </h3>
                            <div class="flex items-center space-x-2 rtl:space-x-reverse">
                                @if(isset($orderData) && $orderData['payment_status'] === 'paid')
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                    <span class="text-green-600 font-medium">{{ app()->getLocale() === 'ar' ? 'تم الدفع بنجاح' : 'Payment Successful' }}</span>
                                @else
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <span class="text-yellow-600 font-medium">{{ app()->getLocale() === 'ar' ? 'في انتظار الدفع' : 'Payment Pending' }}</span>
                                @endif
                            </div>
                        </div>
                        
                        @if(isset($orderData) && $orderData['payment_status'] === 'paid')
                        <div class="text-right">
                            <p class="text-sm text-gray-600">
                                {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                            </p>
                            <p class="font-semibold text-gray-800">
                                {{ $orderData['payment_method'] ?? 'MyFatoorah' }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Next Steps -->
                <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-blue-800 mb-4">
                        {{ app()->getLocale() === 'ar' ? 'الخطوات التالية' : 'Next Steps' }}
                    </h3>
                    <div class="space-y-3">
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mt-0.5">1</div>
                            <div>
                                <p class="font-medium text-blue-800">
                                    {{ app()->getLocale() === 'ar' ? 'ستتلقى رسالة تأكيد على بريدك الإلكتروني' : 'You will receive a confirmation email' }}
                                </p>
                                <p class="text-sm text-blue-600">
                                    {{ app()->getLocale() === 'ar' ? 'تحتوي على تفاصيل طلبك ورقم التتبع' : 'Containing your order details and tracking number' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mt-0.5">2</div>
                            <div>
                                <p class="font-medium text-blue-800">
                                    {{ app()->getLocale() === 'ar' ? 'سيتم التواصل معك خلال 24 ساعة' : 'We will contact you within 24 hours' }}
                                </p>
                                <p class="text-sm text-blue-600">
                                    {{ app()->getLocale() === 'ar' ? 'لتأكيد تفاصيل الخدمة وترتيب الموعد' : 'To confirm service details and schedule' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-3 rtl:space-x-reverse">
                            <div class="w-6 h-6 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold mt-0.5">3</div>
                            <div>
                                <p class="font-medium text-blue-800">
                                    {{ app()->getLocale() === 'ar' ? 'تقديم الخدمة المطلوبة' : 'Service delivery' }}
                                </p>
                                <p class="text-sm text-blue-600">
                                    {{ app()->getLocale() === 'ar' ? 'بأعلى معايير الجودة والكفاءة' : 'With the highest standards of quality and efficiency' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-8 flex flex-col sm:flex-row gap-4">
                    <a href="{{ route('services') }}" 
                       class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300 text-center font-semibold">
                        {{ app()->getLocale() === 'ar' ? 'طلب خدمة أخرى' : 'Order Another Service' }}
                    </a>
                    <a href="{{ route('home') }}" 
                       class="flex-1 btn-primary text-white px-6 py-3 rounded-lg font-semibold hover:shadow-lg transition duration-300 text-center">
                        {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Information -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-8">
                {{ app()->getLocale() === 'ar' ? 'هل تحتاج مساعدة؟' : 'Need Help?' }}
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'اتصل بنا' : 'Call Us' }}
                    </h3>
                    <p class="text-gray-600">+966 50 123 4567</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'راسلنا' : 'Email Us' }}
                    </h3>
                    <p class="text-gray-600">info@wasila.org</p>
                </div>
                
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'واتساب' : 'WhatsApp' }}
                    </h3>
                    <p class="text-gray-600">+966 50 123 4567</p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
// Get order data from URL parameters if not passed from controller
document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    
    // If no order data from controller, try to get from URL params
    if (!document.getElementById('order-number').textContent.includes('N/A')) {
        return; // Order data already loaded from controller
    }
    
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
        
        document.getElementById('service-price').textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
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
});
</script>
@endpush
