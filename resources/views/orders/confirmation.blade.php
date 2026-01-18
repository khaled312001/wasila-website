@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة' : 'Order Confirmation - Wasila')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'تأكيد الطلب - وسيلة' : 'Order Confirmation - Wasila' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'تم تأكيد طلبك بنجاح. شكراً لك على دعمك لمشروع وسيلة.'
        : 'Your order has been confirmed successfully. Thank you for supporting Wasila.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'تأكيد الطلب, وسيلة, شكر'
        : 'order confirmation, wasila, thank you' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/orders/confirmation') }}"
    type="website"
    author="وسيلة"
/>
@endpush

@push('styles')
<link href="{{ asset('css/landing-custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<!-- Confirmation Header -->
<section class="gradient-bg text-white py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            @php
                // Check for flash messages
                $successMessage = session('success');
                $errorMessage = session('error');
                $infoMessage = session('info');
                
                // If no flash messages but we have order data, determine status from order
                if (!$successMessage && !$errorMessage && !$infoMessage && isset($orderData)) {
                    if (isset($orderData['payment_status'])) {
                        if ($orderData['payment_status'] === 'paid') {
                            $successMessage = 'تم الدفع بنجاح! شكراً لك على دعمك لمشروع وسيلة الخيري.';
                        } elseif ($orderData['payment_status'] === 'failed') {
                            $errorMessage = 'فشل في معالجة الدفع. يرجى المحاولة مرة أخرى أو التواصل معنا.';
                        } elseif ($orderData['payment_status'] === 'pending') {
                            $infoMessage = 'تم استلام طلبك بنجاح. في انتظار تأكيد الدفع.';
                        }
                    }
                }
            @endphp
            
            @if($successMessage)
                <div class="w-20 h-20 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'تم تأكيد طلبك بنجاح!' : 'Order Confirmed Successfully!' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ $successMessage }}
                </p>
            @elseif($errorMessage)
                <div class="w-20 h-20 bg-red-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'حدث خطأ في الدفع' : 'Payment Error' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ $errorMessage }}
                </p>
            @elseif($infoMessage)
                <div class="w-20 h-20 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h1 class="text-2xl md:text-3xl font-bold mb-6">
                    {{ app()->getLocale() === 'ar' ? 'في انتظار التأكيد' : 'Pending Confirmation' }}
                </h1>
                <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                    {{ $infoMessage }}
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
<section class="py-12 md:py-16 bg-gray-50">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Order Header -->
            <div class="gradient-bg px-6 py-5">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl md:text-2xl font-bold text-white">
                        {{ app()->getLocale() === 'ar' ? 'تفاصيل الطلب' : 'Order Details' }}
                    </h2>
                    @if(($orderData['payment_status'] ?? '') === 'paid')
                    <div class="flex items-center gap-2 bg-white/20 px-4 py-2 rounded-lg">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-white font-semibold">{{ app()->getLocale() === 'ar' ? 'مدفوع' : 'Paid' }}</span>
                    </div>
                    @endif
                </div>
            </div>
            
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12">
                    <!-- Order Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-primary-dark mb-4 pb-2 border-b-2 border-primary-light">
                                {{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Information' }}
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الطلب:' : 'Order Number:' }}
                                </span>
                                <span class="font-bold text-primary-dark text-lg">{{ $orderData['order_number'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'تاريخ الطلب:' : 'Order Date:' }}
                                </span>
                                <span class="font-semibold text-gray-800">{{ now()->format('Y-m-d H:i') }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'حالة الدفع:' : 'Payment Status:' }}
                                </span>
                                <span class="font-semibold">
                                    @if(($orderData['payment_status'] ?? '') === 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            {{ app()->getLocale() === 'ar' ? 'مدفوع' : 'Paid' }}
                                        </span>
                                    @elseif(($orderData['payment_status'] ?? '') === 'failed')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-red-100 text-red-800">
                                            {{ app()->getLocale() === 'ar' ? 'فشل الدفع' : 'Payment Failed' }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm bg-yellow-100 text-yellow-800">
                                            {{ app()->getLocale() === 'ar' ? 'في الانتظار' : 'Pending' }}
                                        </span>
                                    @endif
                                </span>
                            </div>
                            
                            @if(isset($orderData['payment_method']))
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'طريقة الدفع:' : 'Payment Method:' }}
                                </span>
                                <span class="font-semibold text-gray-800">{{ $orderData['payment_method'] }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Service Information -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg md:text-xl font-bold text-primary-dark mb-4 pb-2 border-b-2 border-primary-light">
                                {{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}
                            </h3>
                        </div>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'اسم الخدمة:' : 'Service Name:' }}
                                </span>
                                <span class="font-semibold text-primary-dark">{{ $orderData['service_name'] ?? 'N/A' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}
                                </span>
                                <span class="font-semibold text-gray-800">{{ number_format($orderData['service_price'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                <span class="text-gray-700 font-medium">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}
                                </span>
                                <span class="font-semibold text-gray-800">{{ $orderData['service_quantity'] ?? 1 }}</span>
                            </div>
                            
                            <div class="flex justify-between items-center p-4 bg-gradient-to-r from-primary-light to-primary-medium rounded-lg mt-4">
                                <span class="text-white font-bold text-lg">
                                    {{ app()->getLocale() === 'ar' ? 'المجموع:' : 'Total:' }}
                                </span>
                                <span class="font-bold text-white text-xl">{{ number_format($orderData['total_amount'] ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Customer Information -->
        <div class="bg-white rounded-xl shadow-xl mt-8 overflow-hidden border border-gray-100">
            <div class="gradient-bg px-6 py-5">
                <h2 class="text-xl md:text-2xl font-bold text-white">
                    {{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}
                </h2>
            </div>
            
            <div class="p-6 md:p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-bold text-primary-dark mb-2">
                            {{ app()->getLocale() === 'ar' ? 'الاسم:' : 'Name:' }}
                        </label>
                        <p class="text-gray-800 font-medium">{{ $orderData['customer_name'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-bold text-primary-dark mb-2">
                            {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني:' : 'Email:' }}
                        </label>
                        <p class="text-gray-800 font-medium break-all">{{ $orderData['customer_email'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-bold text-primary-dark mb-2">
                            {{ app()->getLocale() === 'ar' ? 'رقم الهاتف:' : 'Phone:' }}
                        </label>
                        <p class="text-gray-800 font-medium">{{ $orderData['customer_phone'] ?? 'N/A' }}</p>
                    </div>
                    
                    <div class="p-4 bg-gray-50 rounded-lg">
                        <label class="block text-sm font-bold text-primary-dark mb-2">
                            {{ app()->getLocale() === 'ar' ? 'العنوان:' : 'Address:' }}
                        </label>
                        <p class="text-gray-800 font-medium">{{ $orderData['customer_address'] ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center">
            @if(($orderData['payment_status'] ?? '') === 'paid')
            <button onclick="printInvoice()" 
                    class="btn-primary text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-xl text-center flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd"/>
                </svg>
                {{ app()->getLocale() === 'ar' ? 'طباعة الفاتورة' : 'Print Invoice' }}
            </button>
            @endif
            
            <a href="{{ app()->getLocale() === 'ar' ? route('home') : route('home.en') }}" 
               class="btn-primary text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-xl text-center">
                {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
            </a>
            
            <a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" 
               class="btn-accent text-white font-semibold py-3 px-8 rounded-lg transition duration-300 hover:shadow-xl text-center">
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
@if($successMessage ?? session('success'))
<section class="py-12 bg-primary-light">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-2xl font-bold text-white mb-4">
            {{ app()->getLocale() === 'ar' ? 'شكراً لك على دعمك!' : 'Thank You for Your Support!' }}
        </h2>
        <p class="text-lg text-gray-200 max-w-3xl mx-auto">
            {{ app()->getLocale() === 'ar' 
                ? 'دعمك يساعدنا في تقديم المزيد من الخدمات للمجتمع. سنتواصل معك قريباً لتأكيد تفاصيل الطلب.'
                : 'Your support helps us provide more services to the community. We will contact you soon to confirm the order details.' }}
        </p>
    </div>
</section>
@endif

@endsection

@push('scripts')
<script>
function printInvoice() {
    // Create a new window for printing
    const printWindow = window.open('', '_blank');
    
    // Get the order data from the page
    const orderData = @json($orderData ?? []);
    
    // Create the invoice HTML
    const invoiceHTML = `
        <!DOCTYPE html>
        <html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>{{ app()->getLocale() === 'ar' ? 'فاتورة وسيلة' : 'Wasila Invoice' }}</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 20px;
                    background: white;
                    color: #333;
                }
                .invoice-header {
                    text-align: center;
                    border-bottom: 3px solid #0f4c81;
                    padding-bottom: 20px;
                    margin-bottom: 30px;
                }
                .invoice-title {
                    font-size: 28px;
                    font-weight: bold;
                    color: #0f4c81;
                    margin-bottom: 10px;
                }
                .invoice-subtitle {
                    font-size: 16px;
                    color: #666;
                }
                .invoice-details {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 30px;
                }
                .invoice-section {
                    flex: 1;
                    margin: 0 10px;
                }
                .section-title {
                    font-size: 18px;
                    font-weight: bold;
                    color: #0f4c81;
                    margin-bottom: 15px;
                    border-bottom: 2px solid #38b6ff;
                    padding-bottom: 5px;
                }
                .detail-row {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 8px;
                    padding: 5px 0;
                }
                .detail-label {
                    font-weight: bold;
                    color: #555;
                }
                .detail-value {
                    color: #333;
                }
                .total-section {
                    background: #f8f9fa;
                    padding: 20px;
                    border-radius: 8px;
                    margin-top: 20px;
                }
                .total-row {
                    display: flex;
                    justify-content: space-between;
                    font-size: 18px;
                    font-weight: bold;
                    color: #0f4c81;
                    border-top: 2px solid #38b6ff;
                    padding-top: 10px;
                }
                .status-paid {
                    color: #28a745;
                    font-weight: bold;
                }
                .footer {
                    text-align: center;
                    margin-top: 40px;
                    padding-top: 20px;
                    border-top: 1px solid #ddd;
                    color: #666;
                }
                @media print {
                    body { margin: 0; }
                    .no-print { display: none; }
                }
            </style>
        </head>
        <body>
            <div class="invoice-header">
                <div class="invoice-title">{{ app()->getLocale() === 'ar' ? 'وسيلة' : 'Wasila' }}</div>
                <div class="invoice-subtitle">{{ app()->getLocale() === 'ar' ? 'فاتورة الدفع' : 'Payment Invoice' }}</div>
            </div>
            
            <div class="invoice-details">
                <div class="invoice-section">
                    <div class="section-title">{{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Information' }}</div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'رقم الطلب:' : 'Order Number:' }}</span>
                        <span class="detail-value">${orderData.order_number || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'تاريخ الطلب:' : 'Order Date:' }}</span>
                        <span class="detail-value">${new Date().toLocaleDateString('{{ app()->getLocale() === "ar" ? "ar-SA" : "en-US" }}')}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'حالة الدفع:' : 'Payment Status:' }}</span>
                        <span class="detail-value status-paid">{{ app()->getLocale() === 'ar' ? 'مدفوع' : 'Paid' }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'طريقة الدفع:' : 'Payment Method:' }}</span>
                        <span class="detail-value">${orderData.payment_method || 'MyFatoorah'}</span>
                    </div>
                </div>
                
                <div class="invoice-section">
                    <div class="section-title">{{ app()->getLocale() === 'ar' ? 'معلومات العميل' : 'Customer Information' }}</div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'الاسم:' : 'Name:' }}</span>
                        <span class="detail-value">${orderData.customer_name || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني:' : 'Email:' }}</span>
                        <span class="detail-value">${orderData.customer_email || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'رقم الهاتف:' : 'Phone:' }}</span>
                        <span class="detail-value">${orderData.customer_phone || 'N/A'}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'العنوان:' : 'Address:' }}</span>
                        <span class="detail-value">${orderData.customer_address || 'N/A'}</span>
                    </div>
                </div>
            </div>
            
            <div class="invoice-section">
                <div class="section-title">{{ app()->getLocale() === 'ar' ? 'تفاصيل الخدمة' : 'Service Details' }}</div>
                <div class="detail-row">
                    <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'اسم الخدمة:' : 'Service Name:' }}</span>
                    <span class="detail-value">${orderData.service_name || 'N/A'}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}</span>
                    <span class="detail-value">${(orderData.service_price || 0).toFixed(2)} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">{{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}</span>
                    <span class="detail-value">${orderData.service_quantity || 1}</span>
                </div>
            </div>
            
            <div class="total-section">
                <div class="total-row">
                    <span>{{ app()->getLocale() === 'ar' ? 'المجموع الكلي:' : 'Total Amount:' }}</span>
                    <span>${(orderData.total_amount || 0).toFixed(2)} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                </div>
            </div>
            
            <div class="footer">
                <p>{{ app()->getLocale() === 'ar' ? 'شكراً لك على دعمك لمشروع وسيلة' : 'Thank you for supporting Wasila' }}</p>
                <p>{{ app()->getLocale() === 'ar' ? 'هذه الفاتورة صالحة كإيصال دفع' : 'This invoice is valid as a payment receipt' }}</p>
            </div>
        </body>
        </html>
    `;
    
    // Write the HTML to the new window
    printWindow.document.write(invoiceHTML);
    printWindow.document.close();
    
    // Wait for the content to load, then print
    printWindow.onload = function() {
        printWindow.print();
        printWindow.close();
    };
}
</script>
@endpush
