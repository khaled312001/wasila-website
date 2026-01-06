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
        <div class="mf-payment-methods-container" id="mf-noPaymentGateways">
            <div class="mf-danger-text">
                {{__('myfatoorah.noPaymentGateways')}}
            </div>
        </div>
        <div class="mf-payment-methods-container" id="mf-paymentGateways" >
            <div class="mf-grey-text">
                {{__('myfatoorah.howWouldYouLikeToPay')}}
            </div>

            <!-- Google Pay & Apple Pay -->
            <div id="mf-sectionButtons">
                <!-- Apple Pay -->
                @if(!empty($paymentMethods['ap']))
                <div id="mf-sectionAP">
                    <div id="mf-ap-element" style="height: 40px;"></div>
                </div>
                @endif
                <!-- Google Pay -->
                @if(!empty($paymentMethods['gp']))
                <div id="mf-sectionGP">
                    <div id="mf-gp-element"></div>
                </div>
                @endif
            </div>

            @if(!empty($paymentMethods['cards'] ))
            <div id="mf-sectionCard">
                <div class="mf-divider card-divider" id="mf-payWith-cardDivider">
                    <span class="mf-divider-span" id="mf-payWith-divider">
                        <span id="mf-or-cardsDivider">
                            {{!empty($paymentMethods['ap'] ) || !empty($paymentMethods['gp'] ) ? __('myfatoorah.or') : ''}}
                        </span>
                        {{__('myfatoorah.payWith')}}
                    </span>
                </div>
                <div id="mf-cards">
                    @include('myfatoorah.includes.sectionCards')
                </div>
            </div>
            @endif

            <!-- Payment Form -->
            @if(!empty($paymentMethods['form']))
            <div class="mf-divider">
                <span class="mf-divider-span">
                    <span id="mf-or-formDivider">
                        {{!empty($paymentMethods['cards'] ) || !empty($paymentMethods['ap'] ) || !empty($paymentMethods['gp'] ) ? __('myfatoorah.or') :''}}
                    </span>
                    {{__('myfatoorah.insertCardDetails')}}
                </span>
            </div>
            <div id="mf-form-element" style="width:99%; max-width:800px; padding: 0rem 0.2rem"></div>

            <button class="mf-btn mf-pay-now-btn" onclick="submit()" type="button" style="
                    border: none; border-radius: 8px;
                    padding: 7px 3px; background-color: #0293cc">
                <span class="mf-pay-now-span">
                    {{__('myfatoorah.payNow')}}
                </span>
            </button>
            @endif

            <script src="{{asset('vendor/myfatoorah/js/checkout.js')}}"></script>
            <script>
                function mfCallback(response) {
                    // Redirect to MyFatoorah callback with payment ID
                    window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + response.paymentId;
                }
            </script>

            <!-- Google Pay Scripts -->
            @if(!empty($paymentMethods['gp']))
            @include('myfatoorah.includes.sectionGooglePay')
            @endif

            <!-- Apple Pay Scripts -->
            @if(!empty($paymentMethods['ap']))
            @include('myfatoorah.includes.sectionApplePay')
            @endif

            <!-- Payment Form Scripts -->
            @if(!empty($paymentMethods['form']))
            @include('myfatoorah.includes.sectionForm')
            @endif
        </div>
        
        <!-- Footer -->
        <footer class="bg-gray-800 text-white py-8 mt-16">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <p>&copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة' : 'Wasila' }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
            </div>
        </footer>
    </body>
</html>