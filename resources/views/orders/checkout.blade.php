<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ app()->getLocale() === 'ar' ? 'طلب خدمة - وسيلة' : 'Service Order - Wasila' }}</title>
    
    <!-- Tailwind CSS -->
    <link href="{{ asset('css/tailwind.css') }}" rel="stylesheet">
    
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
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(15, 76, 129, 0.3);
        }
        
        .input-focus:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(56, 182, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="gradient-bg text-white py-6">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                    <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-12 w-auto">
                    <h1 class="text-2xl font-bold">{{ app()->getLocale() === 'ar' ? 'طلب خدمة' : 'Service Order' }}</h1>
                </div>
                <a href="{{ url('/') }}" class="text-white hover:text-gray-200 transition">
                    {{ app()->getLocale() === 'ar' ? 'العودة للرئيسية' : 'Back to Home' }}
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-6xl mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Service Summary -->
            <div class="lg:col-span-1">
                <div class="glass-effect rounded-2xl p-6 sticky top-8">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">
                        {{ app()->getLocale() === 'ar' ? 'ملخص الطلب' : 'Order Summary' }}
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الخدمة:' : 'Service:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-name">{{ $serviceName ?? '' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'السعر:' : 'Price:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-price">{{ number_format($servicePrice ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الكمية:' : 'Quantity:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-quantity">1</span>
                        </div>

                        <div class="flex justify-between items-center py-3 border-b border-gray-200">
                            <span class="text-gray-600">{{ app()->getLocale() === 'ar' ? 'الدولة:' : 'Country:' }}</span>
                            <span class="font-semibold text-gray-800" id="service-country">{{ app()->getLocale() === 'ar' ? 'السعودية' : 'Saudi Arabia' }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center py-4 bg-gray-100 rounded-lg px-4">
                            <span class="text-lg font-bold text-gray-800">{{ app()->getLocale() === 'ar' ? 'المجموع:' : 'Total:' }}</span>
                            <span class="text-xl font-bold text-primary" id="total-amount">{{ number_format($servicePrice ?? 0, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                        </div>
                    </div>
                    
                    @if($serviceDescription)
                    <div class="mt-6">
                        <h3 class="font-semibold text-gray-800 mb-2">{{ app()->getLocale() === 'ar' ? 'الوصف:' : 'Description:' }}</h3>
                        <p class="text-gray-600 text-sm">{{ $serviceDescription }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order Form -->
            <div class="lg:col-span-2">
                <div class="glass-effect rounded-2xl p-8">
                    <h2 class="text-2xl font-bold text-gray-800 mb-8">
                        {{ app()->getLocale() === 'ar' ? 'معلومات الطلب' : 'Order Information' }}
                    </h2>
                    
                    <form id="order-form" class="space-y-6">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $serviceId ?? '' }}">
                        <input type="hidden" name="service_name" value="{{ $serviceName ?? '' }}">
                        <input type="hidden" name="service_price" value="{{ $servicePrice ?? '' }}">
                        <input type="hidden" name="service_description" value="{{ $serviceDescription ?? '' }}">
                        
                        <!-- Customer Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الاسم الكامل *' : 'Full Name *' }}
                                </label>
                                <input type="text" name="customer_name" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل اسمك الكامل' : 'Enter your full name' }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني *' : 'Email Address *' }}
                                </label>
                                <input type="email" name="customer_email" required
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus"
                                       placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل بريدك الإلكتروني' : 'Enter your email address' }}">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الدولة *' : 'Country *' }}
                                </label>
                                <select name="customer_country" required id="customer_country"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الدولة' : 'Select Country' }}</option>
                                    
                                    <!-- Middle East & Gulf Countries -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'دول الخليج والشرق الأوسط' : 'Middle East & Gulf Countries' }}">
                                        <option value="+966_السعودية" data-code="+966" selected>{{ app()->getLocale() === 'ar' ? 'السعودية (+966)' : 'Saudi Arabia (+966)' }}</option>
                                        <option value="+971_الإمارات" data-code="+971">{{ app()->getLocale() === 'ar' ? 'الإمارات العربية المتحدة (+971)' : 'United Arab Emirates (+971)' }}</option>
                                        <option value="+965_الكويت" data-code="+965">{{ app()->getLocale() === 'ar' ? 'الكويت (+965)' : 'Kuwait (+965)' }}</option>
                                        <option value="+974_قطر" data-code="+974">{{ app()->getLocale() === 'ar' ? 'قطر (+974)' : 'Qatar (+974)' }}</option>
                                        <option value="+973_البحرين" data-code="+973">{{ app()->getLocale() === 'ar' ? 'البحرين (+973)' : 'Bahrain (+973)' }}</option>
                                        <option value="+968_عمان" data-code="+968">{{ app()->getLocale() === 'ar' ? 'عمان (+968)' : 'Oman (+968)' }}</option>
                                        <option value="+962_الأردن" data-code="+962">{{ app()->getLocale() === 'ar' ? 'الأردن (+962)' : 'Jordan (+962)' }}</option>
                                        <option value="+20_مصر" data-code="+20">{{ app()->getLocale() === 'ar' ? 'مصر (+20)' : 'Egypt (+20)' }}</option>
                                        <option value="+961_لبنان" data-code="+961">{{ app()->getLocale() === 'ar' ? 'لبنان (+961)' : 'Lebanon (+961)' }}</option>
                                        <option value="+963_سوريا" data-code="+963">{{ app()->getLocale() === 'ar' ? 'سوريا (+963)' : 'Syria (+963)' }}</option>
                                        <option value="+964_العراق" data-code="+964">{{ app()->getLocale() === 'ar' ? 'العراق (+964)' : 'Iraq (+964)' }}</option>
                                        <option value="+967_اليمن" data-code="+967">{{ app()->getLocale() === 'ar' ? 'اليمن (+967)' : 'Yemen (+967)' }}</option>
                                        <option value="+970_فلسطين" data-code="+970">{{ app()->getLocale() === 'ar' ? 'فلسطين (+970)' : 'Palestine (+970)' }}</option>
                                        <option value="+90_تركيا" data-code="+90">{{ app()->getLocale() === 'ar' ? 'تركيا (+90)' : 'Turkey (+90)' }}</option>
                                        <option value="+98_إيران" data-code="+98">{{ app()->getLocale() === 'ar' ? 'إيران (+98)' : 'Iran (+98)' }}</option>
                                        <option value="+93_أفغانستان" data-code="+93">{{ app()->getLocale() === 'ar' ? 'أفغانستان (+93)' : 'Afghanistan (+93)' }}</option>
                                    </optgroup>

                                    <!-- North Africa -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'شمال أفريقيا' : 'North Africa' }}">
                                        <option value="+249_السودان" data-code="+249">{{ app()->getLocale() === 'ar' ? 'السودان (+249)' : 'Sudan (+249)' }}</option>
                                        <option value="+218_ليبيا" data-code="+218">{{ app()->getLocale() === 'ar' ? 'ليبيا (+218)' : 'Libya (+218)' }}</option>
                                        <option value="+216_تونس" data-code="+216">{{ app()->getLocale() === 'ar' ? 'تونس (+216)' : 'Tunisia (+216)' }}</option>
                                        <option value="+213_الجزائر" data-code="+213">{{ app()->getLocale() === 'ar' ? 'الجزائر (+213)' : 'Algeria (+213)' }}</option>
                                        <option value="+212_المغرب" data-code="+212">{{ app()->getLocale() === 'ar' ? 'المغرب (+212)' : 'Morocco (+212)' }}</option>
                                        <option value="+222_موريتانيا" data-code="+222">{{ app()->getLocale() === 'ar' ? 'موريتانيا (+222)' : 'Mauritania (+222)' }}</option>
                                    </optgroup>

                                    <!-- Asia -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'آسيا' : 'Asia' }}">
                                        <option value="+86_الصين" data-code="+86">{{ app()->getLocale() === 'ar' ? 'الصين (+86)' : 'China (+86)' }}</option>
                                        <option value="+91_الهند" data-code="+91">{{ app()->getLocale() === 'ar' ? 'الهند (+91)' : 'India (+91)' }}</option>
                                        <option value="+81_اليابان" data-code="+81">{{ app()->getLocale() === 'ar' ? 'اليابان (+81)' : 'Japan (+81)' }}</option>
                                        <option value="+82_كوريا الجنوبية" data-code="+82">{{ app()->getLocale() === 'ar' ? 'كوريا الجنوبية (+82)' : 'South Korea (+82)' }}</option>
                                        <option value="+65_سنغافورة" data-code="+65">{{ app()->getLocale() === 'ar' ? 'سنغافورة (+65)' : 'Singapore (+65)' }}</option>
                                        <option value="+60_ماليزيا" data-code="+60">{{ app()->getLocale() === 'ar' ? 'ماليزيا (+60)' : 'Malaysia (+60)' }}</option>
                                        <option value="+66_تايلاند" data-code="+66">{{ app()->getLocale() === 'ar' ? 'تايلاند (+66)' : 'Thailand (+66)' }}</option>
                                        <option value="+84_فيتنام" data-code="+84">{{ app()->getLocale() === 'ar' ? 'فيتنام (+84)' : 'Vietnam (+84)' }}</option>
                                        <option value="+63_الفلبين" data-code="+63">{{ app()->getLocale() === 'ar' ? 'الفلبين (+63)' : 'Philippines (+63)' }}</option>
                                        <option value="+62_إندونيسيا" data-code="+62">{{ app()->getLocale() === 'ar' ? 'إندونيسيا (+62)' : 'Indonesia (+62)' }}</option>
                                        <option value="+880_بنغلاديش" data-code="+880">{{ app()->getLocale() === 'ar' ? 'بنغلاديش (+880)' : 'Bangladesh (+880)' }}</option>
                                        <option value="+92_باكستان" data-code="+92">{{ app()->getLocale() === 'ar' ? 'باكستان (+92)' : 'Pakistan (+92)' }}</option>
                                        <option value="+94_سريلانكا" data-code="+94">{{ app()->getLocale() === 'ar' ? 'سريلانكا (+94)' : 'Sri Lanka (+94)' }}</option>
                                        <option value="+977_نيبال" data-code="+977">{{ app()->getLocale() === 'ar' ? 'نيبال (+977)' : 'Nepal (+977)' }}</option>
                                        <option value="+975_بوتان" data-code="+975">{{ app()->getLocale() === 'ar' ? 'بوتان (+975)' : 'Bhutan (+975)' }}</option>
                                        <option value="+960_جزر المالديف" data-code="+960">{{ app()->getLocale() === 'ar' ? 'جزر المالديف (+960)' : 'Maldives (+960)' }}</option>
                                        <option value="+95_ميانمار" data-code="+95">{{ app()->getLocale() === 'ar' ? 'ميانمار (+95)' : 'Myanmar (+95)' }}</option>
                                        <option value="+855_كمبوديا" data-code="+855">{{ app()->getLocale() === 'ar' ? 'كمبوديا (+855)' : 'Cambodia (+855)' }}</option>
                                        <option value="+856_لاوس" data-code="+856">{{ app()->getLocale() === 'ar' ? 'لاوس (+856)' : 'Laos (+856)' }}</option>
                                        <option value="+673_بروناي" data-code="+673">{{ app()->getLocale() === 'ar' ? 'بروناي (+673)' : 'Brunei (+673)' }}</option>
                                    </optgroup>

                                    <!-- Europe -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أوروبا' : 'Europe' }}">
                                        <option value="+44_المملكة المتحدة" data-code="+44">{{ app()->getLocale() === 'ar' ? 'المملكة المتحدة (+44)' : 'United Kingdom (+44)' }}</option>
                                        <option value="+49_ألمانيا" data-code="+49">{{ app()->getLocale() === 'ar' ? 'ألمانيا (+49)' : 'Germany (+49)' }}</option>
                                        <option value="+33_فرنسا" data-code="+33">{{ app()->getLocale() === 'ar' ? 'فرنسا (+33)' : 'France (+33)' }}</option>
                                        <option value="+39_إيطاليا" data-code="+39">{{ app()->getLocale() === 'ar' ? 'إيطاليا (+39)' : 'Italy (+39)' }}</option>
                                        <option value="+34_إسبانيا" data-code="+34">{{ app()->getLocale() === 'ar' ? 'إسبانيا (+34)' : 'Spain (+34)' }}</option>
                                        <option value="+31_هولندا" data-code="+31">{{ app()->getLocale() === 'ar' ? 'هولندا (+31)' : 'Netherlands (+31)' }}</option>
                                        <option value="+32_بلجيكا" data-code="+32">{{ app()->getLocale() === 'ar' ? 'بلجيكا (+32)' : 'Belgium (+32)' }}</option>
                                        <option value="+41_سويسرا" data-code="+41">{{ app()->getLocale() === 'ar' ? 'سويسرا (+41)' : 'Switzerland (+41)' }}</option>
                                        <option value="+43_النمسا" data-code="+43">{{ app()->getLocale() === 'ar' ? 'النمسا (+43)' : 'Austria (+43)' }}</option>
                                        <option value="+46_السويد" data-code="+46">{{ app()->getLocale() === 'ar' ? 'السويد (+46)' : 'Sweden (+46)' }}</option>
                                        <option value="+47_النرويج" data-code="+47">{{ app()->getLocale() === 'ar' ? 'النرويج (+47)' : 'Norway (+47)' }}</option>
                                        <option value="+45_الدنمارك" data-code="+45">{{ app()->getLocale() === 'ar' ? 'الدنمارك (+45)' : 'Denmark (+45)' }}</option>
                                        <option value="+358_فنلندا" data-code="+358">{{ app()->getLocale() === 'ar' ? 'فنلندا (+358)' : 'Finland (+358)' }}</option>
                                        <option value="+48_بولندا" data-code="+48">{{ app()->getLocale() === 'ar' ? 'بولندا (+48)' : 'Poland (+48)' }}</option>
                                        <option value="+420_التشيك" data-code="+420">{{ app()->getLocale() === 'ar' ? 'التشيك (+420)' : 'Czech Republic (+420)' }}</option>
                                        <option value="+36_المجر" data-code="+36">{{ app()->getLocale() === 'ar' ? 'المجر (+36)' : 'Hungary (+36)' }}</option>
                                        <option value="+40_رومانيا" data-code="+40">{{ app()->getLocale() === 'ar' ? 'رومانيا (+40)' : 'Romania (+40)' }}</option>
                                        <option value="+359_بلغاريا" data-code="+359">{{ app()->getLocale() === 'ar' ? 'بلغاريا (+359)' : 'Bulgaria (+359)' }}</option>
                                        <option value="+385_كرواتيا" data-code="+385">{{ app()->getLocale() === 'ar' ? 'كرواتيا (+385)' : 'Croatia (+385)' }}</option>
                                        <option value="+386_سلوفينيا" data-code="+386">{{ app()->getLocale() === 'ar' ? 'سلوفينيا (+386)' : 'Slovenia (+386)' }}</option>
                                        <option value="+421_سلوفاكيا" data-code="+421">{{ app()->getLocale() === 'ar' ? 'سلوفاكيا (+421)' : 'Slovakia (+421)' }}</option>
                                        <option value="+372_إستونيا" data-code="+372">{{ app()->getLocale() === 'ar' ? 'إستونيا (+372)' : 'Estonia (+372)' }}</option>
                                        <option value="+371_لاتفيا" data-code="+371">{{ app()->getLocale() === 'ar' ? 'لاتفيا (+371)' : 'Latvia (+371)' }}</option>
                                        <option value="+370_ليتوانيا" data-code="+370">{{ app()->getLocale() === 'ar' ? 'ليتوانيا (+370)' : 'Lithuania (+370)' }}</option>
                                        <option value="+7_روسيا" data-code="+7">{{ app()->getLocale() === 'ar' ? 'روسيا (+7)' : 'Russia (+7)' }}</option>
                                        <option value="+380_أوكرانيا" data-code="+380">{{ app()->getLocale() === 'ar' ? 'أوكرانيا (+380)' : 'Ukraine (+380)' }}</option>
                                        <option value="+375_بيلاروسيا" data-code="+375">{{ app()->getLocale() === 'ar' ? 'بيلاروسيا (+375)' : 'Belarus (+375)' }}</option>
                                        <option value="+370_ليتوانيا" data-code="+370">{{ app()->getLocale() === 'ar' ? 'ليتوانيا (+370)' : 'Lithuania (+370)' }}</option>
                                        <option value="+30_اليونان" data-code="+30">{{ app()->getLocale() === 'ar' ? 'اليونان (+30)' : 'Greece (+30)' }}</option>
                                        <option value="+351_البرتغال" data-code="+351">{{ app()->getLocale() === 'ar' ? 'البرتغال (+351)' : 'Portugal (+351)' }}</option>
                                        <option value="+353_أيرلندا" data-code="+353">{{ app()->getLocale() === 'ar' ? 'أيرلندا (+353)' : 'Ireland (+353)' }}</option>
                                        <option value="+354_آيسلندا" data-code="+354">{{ app()->getLocale() === 'ar' ? 'آيسلندا (+354)' : 'Iceland (+354)' }}</option>
                                    </optgroup>

                                    <!-- North America -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أمريكا الشمالية' : 'North America' }}">
                                        <option value="+1_الولايات المتحدة" data-code="+1">{{ app()->getLocale() === 'ar' ? 'الولايات المتحدة (+1)' : 'United States (+1)' }}</option>
                                        <option value="+1_كندا" data-code="+1">{{ app()->getLocale() === 'ar' ? 'كندا (+1)' : 'Canada (+1)' }}</option>
                                        <option value="+52_المكسيك" data-code="+52">{{ app()->getLocale() === 'ar' ? 'المكسيك (+52)' : 'Mexico (+52)' }}</option>
                                        <option value="+502_غواتيمالا" data-code="+502">{{ app()->getLocale() === 'ar' ? 'غواتيمالا (+502)' : 'Guatemala (+502)' }}</option>
                                        <option value="+504_هندوراس" data-code="+504">{{ app()->getLocale() === 'ar' ? 'هندوراس (+504)' : 'Honduras (+504)' }}</option>
                                        <option value="+503_السلفادور" data-code="+503">{{ app()->getLocale() === 'ar' ? 'السلفادور (+503)' : 'El Salvador (+503)' }}</option>
                                        <option value="+505_نيكاراغوا" data-code="+505">{{ app()->getLocale() === 'ar' ? 'نيكاراغوا (+505)' : 'Nicaragua (+505)' }}</option>
                                        <option value="+506_كوستاريكا" data-code="+506">{{ app()->getLocale() === 'ar' ? 'كوستاريكا (+506)' : 'Costa Rica (+506)' }}</option>
                                        <option value="+507_بنما" data-code="+507">{{ app()->getLocale() === 'ar' ? 'بنما (+507)' : 'Panama (+507)' }}</option>
                                    </optgroup>

                                    <!-- South America -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أمريكا الجنوبية' : 'South America' }}">
                                        <option value="+55_البرازيل" data-code="+55">{{ app()->getLocale() === 'ar' ? 'البرازيل (+55)' : 'Brazil (+55)' }}</option>
                                        <option value="+54_الأرجنتين" data-code="+54">{{ app()->getLocale() === 'ar' ? 'الأرجنتين (+54)' : 'Argentina (+54)' }}</option>
                                        <option value="+56_تشيلي" data-code="+56">{{ app()->getLocale() === 'ar' ? 'تشيلي (+56)' : 'Chile (+56)' }}</option>
                                        <option value="+57_كولومبيا" data-code="+57">{{ app()->getLocale() === 'ar' ? 'كولومبيا (+57)' : 'Colombia (+57)' }}</option>
                                        <option value="+58_فنزويلا" data-code="+58">{{ app()->getLocale() === 'ar' ? 'فنزويلا (+58)' : 'Venezuela (+58)' }}</option>
                                        <option value="+51_بيرو" data-code="+51">{{ app()->getLocale() === 'ar' ? 'بيرو (+51)' : 'Peru (+51)' }}</option>
                                        <option value="+591_بوليفيا" data-code="+591">{{ app()->getLocale() === 'ar' ? 'بوليفيا (+591)' : 'Bolivia (+591)' }}</option>
                                        <option value="+593_الإكوادور" data-code="+593">{{ app()->getLocale() === 'ar' ? 'الإكوادور (+593)' : 'Ecuador (+593)' }}</option>
                                        <option value="+598_الأوروغواي" data-code="+598">{{ app()->getLocale() === 'ar' ? 'الأوروغواي (+598)' : 'Uruguay (+598)' }}</option>
                                        <option value="+595_الباراغواي" data-code="+595">{{ app()->getLocale() === 'ar' ? 'الباراغواي (+595)' : 'Paraguay (+595)' }}</option>
                                        <option value="+597_سورينام" data-code="+597">{{ app()->getLocale() === 'ar' ? 'سورينام (+597)' : 'Suriname (+597)' }}</option>
                                        <option value="+592_غيانا" data-code="+592">{{ app()->getLocale() === 'ar' ? 'غيانا (+592)' : 'Guyana (+592)' }}</option>
                                    </optgroup>

                                    <!-- Africa -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أفريقيا' : 'Africa' }}">
                                        <option value="+234_نيجيريا" data-code="+234">{{ app()->getLocale() === 'ar' ? 'نيجيريا (+234)' : 'Nigeria (+234)' }}</option>
                                        <option value="+27_جنوب أفريقيا" data-code="+27">{{ app()->getLocale() === 'ar' ? 'جنوب أفريقيا (+27)' : 'South Africa (+27)' }}</option>
                                        <option value="+20_مصر" data-code="+20">{{ app()->getLocale() === 'ar' ? 'مصر (+20)' : 'Egypt (+20)' }}</option>
                                        <option value="+254_كينيا" data-code="+254">{{ app()->getLocale() === 'ar' ? 'كينيا (+254)' : 'Kenya (+254)' }}</option>
                                        <option value="+251_إثيوبيا" data-code="+251">{{ app()->getLocale() === 'ar' ? 'إثيوبيا (+251)' : 'Ethiopia (+251)' }}</option>
                                        <option value="+256_أوغندا" data-code="+256">{{ app()->getLocale() === 'ar' ? 'أوغندا (+256)' : 'Uganda (+256)' }}</option>
                                        <option value="+255_تنزانيا" data-code="+255">{{ app()->getLocale() === 'ar' ? 'تنزانيا (+255)' : 'Tanzania (+255)' }}</option>
                                        <option value="+250_رواندا" data-code="+250">{{ app()->getLocale() === 'ar' ? 'رواندا (+250)' : 'Rwanda (+250)' }}</option>
                                        <option value="+257_بوروندي" data-code="+257">{{ app()->getLocale() === 'ar' ? 'بوروندي (+257)' : 'Burundi (+257)' }}</option>
                                        <option value="+243_جمهورية الكونغو الديمقراطية" data-code="+243">{{ app()->getLocale() === 'ar' ? 'جمهورية الكونغو الديمقراطية (+243)' : 'Democratic Republic of Congo (+243)' }}</option>
                                        <option value="+242_جمهورية الكونغو" data-code="+242">{{ app()->getLocale() === 'ar' ? 'جمهورية الكونغو (+242)' : 'Republic of Congo (+242)' }}</option>
                                        <option value="+237_الكاميرون" data-code="+237">{{ app()->getLocale() === 'ar' ? 'الكاميرون (+237)' : 'Cameroon (+237)' }}</option>
                                        <option value="+236_جمهورية أفريقيا الوسطى" data-code="+236">{{ app()->getLocale() === 'ar' ? 'جمهورية أفريقيا الوسطى (+236)' : 'Central African Republic (+236)' }}</option>
                                        <option value="+235_تشاد" data-code="+235">{{ app()->getLocale() === 'ar' ? 'تشاد (+235)' : 'Chad (+235)' }}</option>
                                        <option value="+225_ساحل العاج" data-code="+225">{{ app()->getLocale() === 'ar' ? 'ساحل العاج (+225)' : 'Ivory Coast (+225)' }}</option>
                                        <option value="+233_غانا" data-code="+233">{{ app()->getLocale() === 'ar' ? 'غانا (+233)' : 'Ghana (+233)' }}</option>
                                        <option value="+229_بنين" data-code="+229">{{ app()->getLocale() === 'ar' ? 'بنين (+229)' : 'Benin (+229)' }}</option>
                                        <option value="+228_توغو" data-code="+228">{{ app()->getLocale() === 'ar' ? 'توغو (+228)' : 'Togo (+228)' }}</option>
                                        <option value="+226_بوركينا فاسو" data-code="+226">{{ app()->getLocale() === 'ar' ? 'بوركينا فاسو (+226)' : 'Burkina Faso (+226)' }}</option>
                                        <option value="+223_مالي" data-code="+223">{{ app()->getLocale() === 'ar' ? 'مالي (+223)' : 'Mali (+223)' }}</option>
                                        <option value="+224_غينيا" data-code="+224">{{ app()->getLocale() === 'ar' ? 'غينيا (+224)' : 'Guinea (+224)' }}</option>
                                        <option value="+245_غينيا بيساو" data-code="+245">{{ app()->getLocale() === 'ar' ? 'غينيا بيساو (+245)' : 'Guinea-Bissau (+245)' }}</option>
                                        <option value="+238_الرأس الأخضر" data-code="+238">{{ app()->getLocale() === 'ar' ? 'الرأس الأخضر (+238)' : 'Cape Verde (+238)' }}</option>
                                        <option value="+220_غامبيا" data-code="+220">{{ app()->getLocale() === 'ar' ? 'غامبيا (+220)' : 'Gambia (+220)' }}</option>
                                        <option value="+221_السنغال" data-code="+221">{{ app()->getLocale() === 'ar' ? 'السنغال (+221)' : 'Senegal (+221)' }}</option>
                                        <option value="+223_مالي" data-code="+223">{{ app()->getLocale() === 'ar' ? 'مالي (+223)' : 'Mali (+223)' }}</option>
                                        <option value="+227_النيجر" data-code="+227">{{ app()->getLocale() === 'ar' ? 'النيجر (+227)' : 'Niger (+227)' }}</option>
                                        <option value="+234_نيجيريا" data-code="+234">{{ app()->getLocale() === 'ar' ? 'نيجيريا (+234)' : 'Nigeria (+234)' }}</option>
                                        <option value="+237_الكاميرون" data-code="+237">{{ app()->getLocale() === 'ar' ? 'الكاميرون (+237)' : 'Cameroon (+237)' }}</option>
                                        <option value="+240_غينيا الاستوائية" data-code="+240">{{ app()->getLocale() === 'ar' ? 'غينيا الاستوائية (+240)' : 'Equatorial Guinea (+240)' }}</option>
                                        <option value="+241_الغابون" data-code="+241">{{ app()->getLocale() === 'ar' ? 'الغابون (+241)' : 'Gabon (+241)' }}</option>
                                        <option value="+239_ساو تومي وبرينسيبي" data-code="+239">{{ app()->getLocale() === 'ar' ? 'ساو تومي وبرينسيبي (+239)' : 'São Tomé and Príncipe (+239)' }}</option>
                                        <option value="+244_أنغولا" data-code="+244">{{ app()->getLocale() === 'ar' ? 'أنغولا (+244)' : 'Angola (+244)' }}</option>
                                        <option value="+260_زامبيا" data-code="+260">{{ app()->getLocale() === 'ar' ? 'زامبيا (+260)' : 'Zambia (+260)' }}</option>
                                        <option value="+263_زيمبابوي" data-code="+263">{{ app()->getLocale() === 'ar' ? 'زيمبابوي (+263)' : 'Zimbabwe (+263)' }}</option>
                                        <option value="+267_بوتسوانا" data-code="+267">{{ app()->getLocale() === 'ar' ? 'بوتسوانا (+267)' : 'Botswana (+267)' }}</option>
                                        <option value="+268_إسواتيني" data-code="+268">{{ app()->getLocale() === 'ar' ? 'إسواتيني (+268)' : 'Eswatini (+268)' }}</option>
                                        <option value="+266_ليسوتو" data-code="+266">{{ app()->getLocale() === 'ar' ? 'ليسوتو (+266)' : 'Lesotho (+266)' }}</option>
                                        <option value="+258_موزمبيق" data-code="+258">{{ app()->getLocale() === 'ar' ? 'موزمبيق (+258)' : 'Mozambique (+258)' }}</option>
                                        <option value="+265_ملاوي" data-code="+265">{{ app()->getLocale() === 'ar' ? 'ملاوي (+265)' : 'Malawi (+265)' }}</option>
                                        <option value="+261_مدغشقر" data-code="+261">{{ app()->getLocale() === 'ar' ? 'مدغشقر (+261)' : 'Madagascar (+261)' }}</option>
                                        <option value="+230_موريشيوس" data-code="+230">{{ app()->getLocale() === 'ar' ? 'موريشيوس (+230)' : 'Mauritius (+230)' }}</option>
                                        <option value="+248_سيشل" data-code="+248">{{ app()->getLocale() === 'ar' ? 'سيشل (+248)' : 'Seychelles (+248)' }}</option>
                                        <option value="+269_جزر القمر" data-code="+269">{{ app()->getLocale() === 'ar' ? 'جزر القمر (+269)' : 'Comoros (+269)' }}</option>
                                        <option value="+253_جيبوتي" data-code="+253">{{ app()->getLocale() === 'ar' ? 'جيبوتي (+253)' : 'Djibouti (+253)' }}</option>
                                        <option value="+252_الصومال" data-code="+252">{{ app()->getLocale() === 'ar' ? 'الصومال (+252)' : 'Somalia (+252)' }}</option>
                                        <option value="+291_إريتريا" data-code="+291">{{ app()->getLocale() === 'ar' ? 'إريتريا (+291)' : 'Eritrea (+291)' }}</option>
                                    </optgroup>

                                    <!-- Oceania -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أوقيانوسيا' : 'Oceania' }}">
                                        <option value="+61_أستراليا" data-code="+61">{{ app()->getLocale() === 'ar' ? 'أستراليا (+61)' : 'Australia (+61)' }}</option>
                                        <option value="+64_نيوزيلندا" data-code="+64">{{ app()->getLocale() === 'ar' ? 'نيوزيلندا (+64)' : 'New Zealand (+64)' }}</option>
                                        <option value="+679_فيجي" data-code="+679">{{ app()->getLocale() === 'ar' ? 'فيجي (+679)' : 'Fiji (+679)' }}</option>
                                        <option value="+685_ساموا" data-code="+685">{{ app()->getLocale() === 'ar' ? 'ساموا (+685)' : 'Samoa (+685)' }}</option>
                                        <option value="+676_تونغا" data-code="+676">{{ app()->getLocale() === 'ar' ? 'تونغا (+676)' : 'Tonga (+676)' }}</option>
                                        <option value="+678_فانواتو" data-code="+678">{{ app()->getLocale() === 'ar' ? 'فانواتو (+678)' : 'Vanuatu (+678)' }}</option>
                                        <option value="+687_كاليدونيا الجديدة" data-code="+687">{{ app()->getLocale() === 'ar' ? 'كاليدونيا الجديدة (+687)' : 'New Caledonia (+687)' }}</option>
                                        <option value="+689_بولينيزيا الفرنسية" data-code="+689">{{ app()->getLocale() === 'ar' ? 'بولينيزيا الفرنسية (+689)' : 'French Polynesia (+689)' }}</option>
                                        <option value="+684_ساموا الأمريكية" data-code="+684">{{ app()->getLocale() === 'ar' ? 'ساموا الأمريكية (+684)' : 'American Samoa (+684)' }}</option>
                                        <option value="+691_ولايات ميكرونيزيا الموحدة" data-code="+691">{{ app()->getLocale() === 'ar' ? 'ولايات ميكرونيزيا الموحدة (+691)' : 'Federated States of Micronesia (+691)' }}</option>
                                        <option value="+692_جزر مارشال" data-code="+692">{{ app()->getLocale() === 'ar' ? 'جزر مارشال (+692)' : 'Marshall Islands (+692)' }}</option>
                                        <option value="+680_بالاو" data-code="+680">{{ app()->getLocale() === 'ar' ? 'بالاو (+680)' : 'Palau (+680)' }}</option>
                                        <option value="+686_كيريباتي" data-code="+686">{{ app()->getLocale() === 'ar' ? 'كيريباتي (+686)' : 'Kiribati (+686)' }}</option>
                                        <option value="+688_توفالو" data-code="+688">{{ app()->getLocale() === 'ar' ? 'توفالو (+688)' : 'Tuvalu (+688)' }}</option>
                                        <option value="+683_نييوي" data-code="+683">{{ app()->getLocale() === 'ar' ? 'نييوي (+683)' : 'Niue (+683)' }}</option>
                                        <option value="+682_جزر كوك" data-code="+682">{{ app()->getLocale() === 'ar' ? 'جزر كوك (+682)' : 'Cook Islands (+682)' }}</option>
                                        <option value="+685_ساموا" data-code="+685">{{ app()->getLocale() === 'ar' ? 'ساموا (+685)' : 'Samoa (+685)' }}</option>
                                    </optgroup>

                                    <!-- Other -->
                                    <optgroup label="{{ app()->getLocale() === 'ar' ? 'أخرى' : 'Other' }}">
                                        <option value="أخرى">{{ app()->getLocale() === 'ar' ? 'أخرى' : 'Other' }}</option>
                                    </optgroup>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'رقم الهاتف *' : 'Phone Number *' }}
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <span id="country_code_display" class="text-gray-500 text-sm">+</span>
                                    </div>
                                    <input type="tel" name="customer_phone" required id="customer_phone"
                                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg input-focus"
                                           placeholder="{{ app()->getLocale() === 'ar' ? 'أدخل رقم هاتفك' : 'Enter your phone number' }}">
                                </div>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ app()->getLocale() === 'ar' ? 'ضرورة وجود واتساب لإرسال فيديو التوثيق' : 'WhatsApp is required for verification video delivery' }}
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ app()->getLocale() === 'ar' ? 'الكمية *' : 'Quantity *' }}
                                </label>
                                <select name="quantity" required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg input-focus">
                                    <option value="">{{ app()->getLocale() === 'ar' ? 'اختر الكمية' : 'Select Quantity' }}</option>
                                    @for($i = 1; $i <= 9; $i++)
                                        <option value="{{ $i }}" {{ $i == 1 ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                        
                        
                        <!-- Payment Method -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                                {{ app()->getLocale() === 'ar' ? 'طريقة الدفع' : 'Payment Method' }}
                            </h3>
                            
                            <div class="space-y-3 mb-6">
                                <!-- MyFatoorah Payment Only -->
                                <div class="border-2 border-green-500 rounded-lg overflow-hidden bg-green-50">
                                    <label class="flex items-center p-6 cursor-pointer hover:bg-green-100 transition">
                                        <input type="radio" name="payment_method" value="myfatoorah" class="mr-4" checked>
                                        <div class="flex items-center">
                                            <img src="{{ asset('images/myfatoorah-logo.svg') }}" alt="MyFatoorah" class="w-10 h-10 mr-4">
                                            <div>
                                                <span class="font-bold text-xl text-green-800">{{ app()->getLocale() === 'ar' ? 'دفع آمن عبر ماي فاتورة' : 'Secure Payment via MyFatoorah' }}</span>
                                                <p class="text-sm text-green-600 mt-1">{{ app()->getLocale() === 'ar' ? 'الطريقة الوحيدة المتاحة للدفع' : 'The only available payment method' }}</p>
                                            </div>
                                        </div>
                                    </label>
                                    
                                    <!-- MyFatoorah Details -->
                                    <div id="myfatoorah-details" class="payment-details p-6 bg-white border-t border-green-200">
                                        <div class="space-y-4">
                                            <div class="flex items-start">
                                                <svg class="w-6 h-6 text-green-500 mt-0.5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                <div>
                                                    <h4 class="text-lg font-bold text-green-800 mb-2">
                                                        {{ app()->getLocale() === 'ar' ? 'دفع آمن ومضمون' : 'Secure & Guaranteed Payment' }}
                                                    </h4>
                                                    <p class="text-sm text-green-700 mb-4">
                                                        {{ app()->getLocale() === 'ar' 
                                                            ? 'ستتمكن من الدفع بجميع البطاقات الائتمانية والخصم والدفع الإلكتروني'
                                                            : 'You can pay with all credit/debit cards and electronic payment methods' }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'البطاقات الائتمانية' : 'Credit Cards' }}</p>
                                                </div>
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'التحويل البنكي' : 'Bank Transfer' }}</p>
                                                </div>
                                                <div class="text-center p-4 bg-green-50 rounded-lg border border-green-200">
                                                    <svg class="w-8 h-8 text-green-600 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                                    </svg>
                                                    <p class="text-sm font-semibold text-green-800">{{ app()->getLocale() === 'ar' ? 'المحافظ الإلكترونية' : 'E-Wallets' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Google Login Button (if not logged in) -->
                        @guest('customer')
                        <div class="mt-6 mb-4">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <p class="text-sm text-blue-800 mb-3 text-center">
                                    {{ app()->getLocale() === 'ar' ? 'يجب تسجيل الدخول قبل إكمال الطلب' : 'You must login before completing the order' }}
                                </p>
                                <a href="{{ route('auth.google') }}" 
                                   class="w-full bg-white border-2 border-gray-300 hover:border-gray-400 text-gray-700 py-3 px-6 rounded-lg font-semibold flex items-center justify-center gap-3 transition-all duration-200 shadow-sm hover:shadow-md">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                                    </svg>
                                    <span>{{ app()->getLocale() === 'ar' ? 'تسجيل الدخول بالجيميل' : 'Sign in with Google' }}</span>
                                </a>
                            </div>
                        </div>
                        @endguest
                        
                        <!-- Submit Button -->
                        <div class="mt-8">
                            @auth('customer')
                            <button type="submit" 
                                    class="btn-primary text-white w-full py-4 px-6 rounded-lg font-semibold text-lg flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                                <span id="submit-text">{{ app()->getLocale() === 'ar' ? 'الدفع الآمن' : 'Secure Payment' }}</span>
                            </button>
                            @else
                            <button type="button" disabled
                                    class="btn-primary text-white w-full py-4 px-6 rounded-lg font-semibold text-lg flex items-center justify-center opacity-50 cursor-not-allowed">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                                </svg>
                                <span>{{ app()->getLocale() === 'ar' ? 'يجب تسجيل الدخول أولاً' : 'Please login first' }}</span>
                            </button>
                            @endauth
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="max-w-6xl mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة الخيرية' : 'Wasila Charity' }}. {{ app()->getLocale() === 'ar' ? 'جميع الحقوق محفوظة' : 'All rights reserved' }}.</p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('order-form');
            const quantitySelect = document.querySelector('select[name="quantity"]');
            const countrySelect = document.querySelector('select[name="customer_country"]');
            const phoneInput = document.getElementById('customer_phone');
            const countryCodeDisplay = document.getElementById('country_code_display');
            const servicePriceElement = document.getElementById('service-price');
            const totalAmountElement = document.getElementById('total-amount');
            const serviceQuantityElement = document.getElementById('service-quantity');
            const serviceCountryElement = document.getElementById('service-country');

            // Country codes mapping
            const countryCodes = {
                // Middle East & Gulf Countries
                '+966_السعودية': '+966',
                '+971_الإمارات': '+971',
                '+965_الكويت': '+965',
                '+974_قطر': '+974',
                '+973_البحرين': '+973',
                '+968_عمان': '+968',
                '+962_الأردن': '+962',
                '+20_مصر': '+20',
                '+961_لبنان': '+961',
                '+963_سوريا': '+963',
                '+964_العراق': '+964',
                '+967_اليمن': '+967',
                '+970_فلسطين': '+970',
                '+90_تركيا': '+90',
                '+98_إيران': '+98',
                '+93_أفغانستان': '+93',
                
                // North Africa
                '+249_السودان': '+249',
                '+218_ليبيا': '+218',
                '+216_تونس': '+216',
                '+213_الجزائر': '+213',
                '+212_المغرب': '+212',
                '+222_موريتانيا': '+222',
                
                // Asia
                '+86_الصين': '+86',
                '+91_الهند': '+91',
                '+81_اليابان': '+81',
                '+82_كوريا الجنوبية': '+82',
                '+65_سنغافورة': '+65',
                '+60_ماليزيا': '+60',
                '+66_تايلاند': '+66',
                '+84_فيتنام': '+84',
                '+63_الفلبين': '+63',
                '+62_إندونيسيا': '+62',
                '+880_بنغلاديش': '+880',
                '+92_باكستان': '+92',
                '+94_سريلانكا': '+94',
                '+977_نيبال': '+977',
                '+975_بوتان': '+975',
                '+960_جزر المالديف': '+960',
                '+95_ميانمار': '+95',
                '+855_كمبوديا': '+855',
                '+856_لاوس': '+856',
                '+673_بروناي': '+673',
                
                // Europe
                '+44_المملكة المتحدة': '+44',
                '+49_ألمانيا': '+49',
                '+33_فرنسا': '+33',
                '+39_إيطاليا': '+39',
                '+34_إسبانيا': '+34',
                '+31_هولندا': '+31',
                '+32_بلجيكا': '+32',
                '+41_سويسرا': '+41',
                '+43_النمسا': '+43',
                '+46_السويد': '+46',
                '+47_النرويج': '+47',
                '+45_الدنمارك': '+45',
                '+358_فنلندا': '+358',
                '+48_بولندا': '+48',
                '+420_التشيك': '+420',
                '+36_المجر': '+36',
                '+40_رومانيا': '+40',
                '+359_بلغاريا': '+359',
                '+385_كرواتيا': '+385',
                '+386_سلوفينيا': '+386',
                '+421_سلوفاكيا': '+421',
                '+372_إستونيا': '+372',
                '+371_لاتفيا': '+371',
                '+370_ليتوانيا': '+370',
                '+7_روسيا': '+7',
                '+380_أوكرانيا': '+380',
                '+375_بيلاروسيا': '+375',
                '+30_اليونان': '+30',
                '+351_البرتغال': '+351',
                '+353_أيرلندا': '+353',
                '+354_آيسلندا': '+354',
                
                // North America
                '+1_الولايات المتحدة': '+1',
                '+1_كندا': '+1',
                '+52_المكسيك': '+52',
                '+502_غواتيمالا': '+502',
                '+504_هندوراس': '+504',
                '+503_السلفادور': '+503',
                '+505_نيكاراغوا': '+505',
                '+506_كوستاريكا': '+506',
                '+507_بنما': '+507',
                
                // South America
                '+55_البرازيل': '+55',
                '+54_الأرجنتين': '+54',
                '+56_تشيلي': '+56',
                '+57_كولومبيا': '+57',
                '+58_فنزويلا': '+58',
                '+51_بيرو': '+51',
                '+591_بوليفيا': '+591',
                '+593_الإكوادور': '+593',
                '+598_الأوروغواي': '+598',
                '+595_الباراغواي': '+595',
                '+597_سورينام': '+597',
                '+592_غيانا': '+592',
                
                // Africa
                '+234_نيجيريا': '+234',
                '+27_جنوب أفريقيا': '+27',
                '+254_كينيا': '+254',
                '+251_إثيوبيا': '+251',
                '+256_أوغندا': '+256',
                '+255_تنزانيا': '+255',
                '+250_رواندا': '+250',
                '+257_بوروندي': '+257',
                '+243_جمهورية الكونغو الديمقراطية': '+243',
                '+242_جمهورية الكونغو': '+242',
                '+237_الكاميرون': '+237',
                '+236_جمهورية أفريقيا الوسطى': '+236',
                '+235_تشاد': '+235',
                '+225_ساحل العاج': '+225',
                '+233_غانا': '+233',
                '+229_بنين': '+229',
                '+228_توغو': '+228',
                '+226_بوركينا فاسو': '+226',
                '+223_مالي': '+223',
                '+224_غينيا': '+224',
                '+245_غينيا بيساو': '+245',
                '+238_الرأس الأخضر': '+238',
                '+220_غامبيا': '+220',
                '+221_السنغال': '+221',
                '+227_النيجر': '+227',
                '+240_غينيا الاستوائية': '+240',
                '+241_الغابون': '+241',
                '+239_ساو تومي وبرينسيبي': '+239',
                '+244_أنغولا': '+244',
                '+260_زامبيا': '+260',
                '+263_زيمبابوي': '+263',
                '+267_بوتسوانا': '+267',
                '+268_إسواتيني': '+268',
                '+266_ليسوتو': '+266',
                '+258_موزمبيق': '+258',
                '+265_ملاوي': '+265',
                '+261_مدغشقر': '+261',
                '+230_موريشيوس': '+230',
                '+248_سيشل': '+248',
                '+269_جزر القمر': '+269',
                '+253_جيبوتي': '+253',
                '+252_الصومال': '+252',
                '+291_إريتريا': '+291',
                
                // Oceania
                '+61_أستراليا': '+61',
                '+64_نيوزيلندا': '+64',
                '+679_فيجي': '+679',
                '+685_ساموا': '+685',
                '+676_تونغا': '+676',
                '+678_فانواتو': '+678',
                '+687_كاليدونيا الجديدة': '+687',
                '+689_بولينيزيا الفرنسية': '+689',
                '+684_ساموا الأمريكية': '+684',
                '+691_ولايات ميكرونيزيا الموحدة': '+691',
                '+692_جزر مارشال': '+692',
                '+680_بالاو': '+680',
                '+686_كيريباتي': '+686',
                '+688_توفالو': '+688',
                '+683_نييوي': '+683',
                '+682_جزر كوك': '+682'
            };

            // Update country code display when country changes
            countrySelect.addEventListener('change', function() {
                const selectedValue = this.value;
                const countryCode = countryCodes[selectedValue] || '+';
                countryCodeDisplay.textContent = countryCode;

                // Update service country display
                const countryText = this.options[this.selectedIndex].text;
                serviceCountryElement.textContent = countryText;
            });

            // Set initial country code display (default to Saudi Arabia)
            const initialCountryCode = countryCodes[countrySelect.value] || '+966';
            countryCodeDisplay.textContent = initialCountryCode;

            // Update total when quantity changes
            quantitySelect.addEventListener('change', function() {
                const quantity = parseInt(this.value) || 1;
                const price = parseFloat(servicePriceElement.textContent.replace(/[^\d.]/g, '')) || 0;
                const total = price * quantity;

                serviceQuantityElement.textContent = quantity;
                totalAmountElement.textContent = total.toFixed(2) + ' {{ app()->getLocale() === "ar" ? "ريال" : "SAR" }}';
            });

            // Update country when country selection changes
            countrySelect.addEventListener('change', function() {
                const countryValue = this.value;
                const countryText = this.options[this.selectedIndex].text;
                serviceCountryElement.textContent = countryText;
            });
            
            // Form submission
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Validate form
                const requiredFields = form.querySelectorAll('[required]');
                let isValid = true;
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.classList.add('border-red-500');
                        isValid = false;
                    } else {
                        field.classList.remove('border-red-500');
                    }
                });
                
                if (!isValid) {
                    showNotification('{{ app()->getLocale() === "ar" ? "يرجى ملء جميع الحقول المطلوبة" : "Please fill in all required fields" }}', 'error');
                    return;
                }
                
                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>{{ app()->getLocale() === "ar" ? "جاري المعالجة..." : "Processing..." }}';
                submitBtn.disabled = true;
                
                // Submit form
                fetch('{{ route("orders.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        service_id: form.querySelector('input[name="service_id"]').value,
                        service_name: form.querySelector('input[name="service_name"]').value,
                        service_price: form.querySelector('input[name="service_price"]').value,
                        service_description: form.querySelector('input[name="service_description"]').value,
                        customer_name: form.querySelector('input[name="customer_name"]').value,
                        customer_email: form.querySelector('input[name="customer_email"]').value,
                        customer_phone: form.querySelector('input[name="customer_phone"]').value,
                        customer_country: form.querySelector('select[name="customer_country"]').value,
                        quantity: form.querySelector('select[name="quantity"]').value,
                        payment_method: 'myfatoorah'
                    })
                })
                .then(response => {
                    // Check if response is JSON
                    const contentType = response.headers.get('content-type');
                    if (contentType && contentType.includes('application/json')) {
                        return response.json();
                    } else {
                        // If not JSON, it might be an error page
                        throw new Error('Server returned non-JSON response');
                    }
                })
                .then(data => {
                    if (data.success) {
                        // Show success notification
                        showNotification(data.message, 'success');
                        
                        // Redirect to MyFatoorah payment page
                        if (data.redirect) {
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 1500);
                        }
                    } else {
                        showNotification(data.message || '{{ app()->getLocale() === "ar" ? "حدث خطأ في معالجة الطلب" : "An error occurred while processing the order" }}', 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('{{ app()->getLocale() === "ar" ? "حدث خطأ في الاتصال. يرجى المحاولة مرة أخرى" : "Connection error occurred. Please try again" }}', 'error');
                })
                .finally(() => {
                    // Reset button state
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                });
            });
            
            // Notification function
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg max-w-sm ${
                    type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
                }`;
                notification.textContent = message;
                
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.remove();
                }, 5000);
            }
        });
    </script>
</body>
</html>