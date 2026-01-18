<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
        <title>{{ app()->getLocale() === 'ar' ? 'الدفع الآمن - وسيلة' : 'Secure Payment - Wasila' }}</title>
        <link rel="stylesheet" href="{{asset('vendor/myfatoorah/css/style.css')}}"/>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body dir="{{App::isLocale('ar') ? 'rtl' : 'ltr'}}" class="bg-gray-50">
        <!-- Header -->
        <header class="bg-gradient-to-r from-blue-900 to-blue-600 text-white py-6">
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
            <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
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
                        <span class="text-xl font-bold text-blue-600">{{ number_format($order->total_amount, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- MyFatoorah Payment Methods -->
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6">
                    {{ app()->getLocale() === 'ar' ? 'اختر طريقة الدفع' : 'Choose Payment Method' }}
                </h2>
                
                <!-- MyFatoorah Embedded Payment Sections -->
                @include('myfatoorah.includes.sectionCards')
                @include('myfatoorah.includes.sectionGooglePay')
                @include('myfatoorah.includes.sectionApplePay')
                @include('myfatoorah.includes.sectionForm')
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة' : 'Wasila' }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
            </div>
        </footer>
    </body>
</html>
