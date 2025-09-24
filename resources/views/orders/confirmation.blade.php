@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة' : 'Order Confirmation - Wasila')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة الخيرية' : 'Order Confirmation - Wasila Charity' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'تم تأكيد طلبك بنجاح. شكراً لك على دعمك لمشروع وسيلة الخيري.'
        : 'Your order has been confirmed successfully. Thank you for supporting Wasila Charity.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'تأكيد الطلب, وسيلة, خدمات خيرية, شكر'
        : 'order confirmation, wasila, charity services, thank you' }}"
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
            @if(session('success'))
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'تم تأكيد طلبك بنجاح!' : 'Order Confirmed Successfully!' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ session('success') }}
                </p>
            @elseif(session('error'))
                <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'حدث خطأ في الدفع' : 'Payment Error' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ session('error') }}
                </p>
            @elseif(session('info'))
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'في انتظار التأكيد' : 'Pending Confirmation' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ session('info') }}
                </p>
            @else
                <div class="w-20 h-20 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'تأكيد الطلب' : 'Order Confirmation' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ app()->getLocale() === 'ar' 
                        ? 'شكراً لك على طلبك. سيتم معالجة طلبك قريباً'
                        : 'Thank you for your order. Your order will be processed soon' }}
                </p>
            @endif
        </div>
    </div>
</section>

<!-- Order Details -->
@if(isset($orderData) && $orderData)
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <!-- Order Header -->
            <div class="bg-primary-light px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Order Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-primary-dark mb-4">
                            {{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Information' }}
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الطلب:' : 'Order Number:' }}
                                </span>
                                <span class="font-semibold text-primary-dark">{{ $orderData['order_number'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'تاريخ الطلب:' : 'Order Date:' }}
                                </span>
                                <span class="font-semibold">{{ now()->format('Y-m-d H:i') }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'حالة الدفع:' : 'Payment Status:' }}
                                </span>
                                <span class="font-semibold">
                                    @if(($orderData['payment_status'] ?? '') === 'paid')
                                        <span class="text-green-600">
                                            {{ app()->getLocale() === 'ar' ? 'مدفوع' : 'Paid' }}
                                        </span>
                                    @elseif(($orderData['payment_status'] ?? '') === 'failed')
                                        <span class="text-red-600">
                                            {{ app()->getLocale() === 'ar' ? 'فشل الدفع' : 'Payment Failed' }}
                                        </span>
                                    @else
                                        <span class="text-yellow-600">
                                            {{ app()->getLocale() === 'ar' ? 'في الانتظار' : 'Pending' }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            
                            @if(isset($orderData['payment_method']))
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'طريقة الدفع:' : 'Payment Method:' }}
                                </span>
                                <span class="font-semibold">{{ $orderData['payment_method'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Service Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-primary-dark mb-4">
                            {{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}
                        </h3>
                        
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'اسم الخدمة:' : 'Service Name:' }}
                                </span>
                                <span class="font-semibold text-primary-dark">{{ $orderData['service_name'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}
                                </span>
                                <span class="font-semibold">{{ number_format($orderData['service_price'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                            
                            <div class="flex justify-between">
                                <span class="text-gray-600">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}
                                </span>
                                <span class="font-semibold">{{ $orderData['service_quantity'] ?? 1 }}</span>
                            </div>
                            
                            <div class="flex justify-between border-t pt-3">
                                <span class="text-gray-600 font-semibold">
                                    {{ app()->getLocale() === 'ar' ? 'المجموع:' : 'Total:' }}
                                </span>
                                <span class="font-bold text-accent text-lg">{{ number_format($orderData['total_amount'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="bg-white rounded-lg shadow-lg mt-8 overflow-hidden">
            <div class="bg-primary-light px-6 py-4">
                <h2 class="text-xl font-bold text-white">
                    {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                </h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ app()->getLocale() === 'ar' ? 'الاسم:' : 'Name:' }}
                        </label>
                        <p class="text-gray-900">{{ $orderData['customer_name'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني:' : 'Email:' }}
                        </label>
                        <p class="text-gray-900">{{ $orderData['customer_email'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ app()->getLocale() === 'ar' ? 'رقم الهاتف:' : 'Phone:' }}
                        </label>
                        <p class="text-gray-900">{{ $orderData['customer_phone'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">
                            {{ app()->getLocale() === 'ar' ? 'العنوان:' : 'Address:' }}
                        </label>
                        <p class="text-gray-900">{{ $orderData['customer_address'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('home') }}" 
               class="bg-primary-medium hover:bg-primary-dark text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-lg text-center">
                {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
            </a>
            
            <a href="{{ route('services') }}" 
               class="bg-accent hover:bg-accent-dark text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-lg text-center">
                {{ app()->getLocale() === 'ar' ? 'تصفح المزيد من الخدمات' : 'Browse More Services' }}
            </a>
        </div>
    </div>
</section>
@else
<!-- No Order Data -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="w-20 h-20 bg-gray-300 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            </div>
            
            <h2 class="text-xl font-bold text-gray-800 mb-4">
                {{ app()->getLocale() === 'ar' ? 'لا توجد بيانات طلب' : 'No Order Data' }}
            </h2>
            
            <p class="text-gray-600 mb-6">
                {{ app()->getLocale() === 'ar' 
                    ? 'لم يتم العثور على بيانات الطلب. يرجى التأكد من صحة الرابط أو التواصل معنا.'
                    : 'No order data found. Please check the link or contact us.' }}
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('home') }}" 
                   class="bg-primary-medium hover:bg-primary-dark text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-lg text-center">
                    {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
                
                <a href="{{ route('services') }}" 
                   class="bg-accent hover:bg-accent-dark text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-lg text-center">
                    {{ app()->getLocale() === 'ar' ? 'تصفح الخدمات' : 'Browse Services' }}
                </a>
            </div>
        </div>
    </div>
</section>
@endif

<!-- Thank You Message -->
@if(session('success'))
<section class="py-12 bg-primary-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-4">
            {{ app()->getLocale() === 'ar' ? 'شكراً لك على دعمك!' : 'Thank You for Your Support!' }}
        </h2>
        <p class="text-lg text-gray-200 max-w-3xl mx-auto">
            {{ app()->getLocale() === 'ar' 
                ? 'دعمك يساعدنا في تقديم المزيد من الخدمات الخيرية للمجتمع. سنتواصل معك قريباً لتأكيد تفاصيل الطلب.'
                : 'Your support helps us provide more charity services to the community. We will contact you soon to confirm the order details.' }}
        </p>
    </div>
</section>
@endif

@endsection
