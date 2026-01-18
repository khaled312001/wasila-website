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
                // Loading overlay to show during payment processing
                function showLoadingOverlay(message) {
                    const overlay = document.createElement('div');
                    overlay.id = 'mf-loading-overlay';
                    overlay.style.cssText = `
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0, 0, 0, 0.8);
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                        z-index: 9999;
                        color: white;
                        font-size: 18px;
                        text-align: center;
                    `;
                    overlay.innerHTML = `
                        <div style="background: white; padding: 30px; border-radius: 10px; color: #333; max-width: 400px; margin: 20px;">
                            <div style="margin-bottom: 20px;">
                                <svg class="animate-spin" style="width: 50px; height: 50px; margin: 0 auto; display: block;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </div>
                            <p style="font-size: 16px; margin-bottom: 10px; font-weight: bold;">${message}</p>
                            <p style="font-size: 14px; color: #666;">{{ app()->getLocale() === 'ar' ? 'يرجى الانتظار...' : 'Please wait...' }}</p>
                        </div>
                    `;
                    document.body.appendChild(overlay);
                }
                
                function hideLoadingOverlay() {
                    const overlay = document.getElementById('mf-loading-overlay');
                    if (overlay) {
                        overlay.remove();
                    }
                }
                
                function mfCallback(response) {
                    // Validate response first
                    if (!response) {
                        console.error('MyFatoorah: Invalid payment response - response is null or undefined', response);
                        hideLoadingOverlay();
                        const errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
                        alert(errorMsg);
                        return false;
                    }
                    
                    // Validate paymentId
                    if (!response.paymentId || response.paymentId === '' || response.paymentId === null || response.paymentId === undefined) {
                        console.error('MyFatoorah: Invalid payment response - paymentId is missing or invalid', response);
                        hideLoadingOverlay();
                        const errorMsg = '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الدفع. يرجى المحاولة مرة أخرى." : "An error occurred processing the payment. Please try again." }}';
                        alert(errorMsg);
                        return false;
                    }
                    
                    console.log('MyFatoorah: Payment successful, paymentId:', response.paymentId);
                    
                    // Show success message first
                    const successMessage = document.createElement('div');
                    successMessage.id = 'mf-success-message';
                    successMessage.style.cssText = `
                        position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
                        color: white;
                        padding: 30px 40px;
                        border-radius: 15px;
                        z-index: 10000;
                        text-align: center;
                        box-shadow: 0 10px 40px rgba(0,0,0,0.3);
                        min-width: 300px;
                        max-width: 90%;
                    `;
                    successMessage.innerHTML = `
                        <div style="margin-bottom: 15px;">
                            <svg style="width: 60px; height: 60px; margin: 0 auto; display: block;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <h3 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">
                            {{ app()->getLocale() === "ar" ? "تم الدفع بنجاح!" : "Payment Successful!" }}
                        </h3>
                        <p style="font-size: 14px; opacity: 0.9;">
                            {{ app()->getLocale() === "ar" ? "جاري إعادة التوجيه..." : "Redirecting..." }}
                        </p>
                    `;
                    document.body.appendChild(successMessage);
                    
                    // Wait a moment to allow user to see any OTP or success messages from MyFatoorah
                    // This gives time for OTP popups or bank messages to appear
                    setTimeout(function() {
                        // Show loading overlay with message
                        showLoadingOverlay('{{ app()->getLocale() === "ar" ? "جاري معالجة الدفع..." : "Processing payment..." }}');
                        
                        // Remove success message
                        if (successMessage.parentNode) {
                            successMessage.remove();
                        }
                        
                        // Redirect to MyFatoorah callback with payment ID
                        setTimeout(function() {
                            window.location.href = "{{route('myfatoorah.callback')}}?paymentId=" + response.paymentId;
                        }, 500);
                    }, 3000); // 3 second delay to allow OTP/success messages to appear
                }
                
                // Handle payment errors globally
                window.addEventListener('error', function(e) {
                    if (e.message && (e.message.includes('myFatoorah') || e.message.includes('MyFatoorah'))) {
                        hideLoadingOverlay();
                        console.error('MyFatoorah global error:', e);
                    }
                });
                
                // Handle unhandled promise rejections
                window.addEventListener('unhandledrejection', function(e) {
                    if (e.reason && (e.reason.message && (e.reason.message.includes('myFatoorah') || e.reason.message.includes('MyFatoorah')))) {
                        hideLoadingOverlay();
                        console.error('MyFatoorah unhandled rejection:', e.reason);
                        alert('{{ app()->getLocale() === "ar" ? "حدث خطأ غير متوقع في عملية الدفع. يرجى المحاولة مرة أخرى." : "An unexpected error occurred during payment. Please try again." }}');
                    }
                });
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