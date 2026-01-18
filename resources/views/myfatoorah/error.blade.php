<!DOCTYPE html>
<html lang="{{app()->getLocale()}}">
    <head>
        <title>{{ app()->getLocale() === 'ar' ? 'خطأ في الدفع' : 'Payment Error' }}</title>
        <link rel="stylesheet" href="{{asset('vendor/myfatoorah/css/style.css')}}"/>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>

    <body dir="{{App::isLocale('ar') ? 'rtl' : 'ltr'}}" class="bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 py-8">
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <div class="text-red-600 mb-4">
                    <i class="fas fa-exclamation-triangle text-6xl"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-800 mb-4">
                    {{ app()->getLocale() === 'ar' ? 'حدث خطأ في جلب طرق الدفع' : 'Failed to Get Payment Methods' }}
                </h1>
                <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                    <p class="text-red-800 font-semibold">{{ $exMessage }}</p>
                </div>
                <div class="text-sm text-gray-600 mb-6">
                    <p>{{ app()->getLocale() === 'ar' 
                        ? 'يرجى التحقق من إعدادات MyFatoorah أو التواصل مع الدعم' 
                        : 'Please check MyFatoorah settings or contact support' }}</p>
                </div>
                <a href="{{ url('/') }}" class="inline-block bg-primary text-white px-6 py-3 rounded-lg hover:bg-primary-dark transition">
                    {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </body>
</html>
