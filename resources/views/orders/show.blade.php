<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-arabic.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-arabic.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <!-- SEO Component -->
    <x-seo 
        title="تفاصيل الطلب - وسيلة"
        description="عرض تفاصيل طلبك في منصة وسيلة الخيرية. تتبع حالة طلبك وتأكد من وصول تبرعك للمحتاجين."
        keywords="تفاصيل الطلب, وسيلة, تبرع, خير, متابعة الطلب, حالة الدفع"
        image="{{ asset('images/logo-arabic.png') }}"
        url="{{ url()->current() }}"
        type="website"
        author="وسيلة الخيرية"
    />
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=HT+Qays+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-light: #3CA6B4;
            --primary-medium: #08788B;
            --primary-dark: #025469;
            --accent: #DFA340;
        }
        
        body {
            font-family: 'HT Qays Sans', 'Noto Sans Arabic', sans-serif;
        }
        
        .bg-primary-light { background-color: var(--primary-light); }
        .bg-primary-medium { background-color: var(--primary-medium); }
        .bg-primary-dark { background-color: var(--primary-dark); }
        .bg-accent { background-color: var(--accent); }
        
        .text-primary-light { color: var(--primary-light); }
        .text-primary-medium { color: var(--primary-medium); }
        .text-primary-dark { color: var(--primary-dark); }
        .text-accent { color: var(--accent); }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(2, 84, 105, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(2, 84, 105, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">

    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-10 w-auto">
                    </a>
                </div>
                <div class="flex items-center space-x-4 space-x-reverse">
                    <a href="{{ route('home') }}" class="text-primary-medium hover:text-primary-dark transition duration-300">العودة للرئيسية</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
            @endif
        
        <!-- Order Header -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold text-primary-dark mb-2">تفاصيل الطلب</h1>
                    <p class="text-gray-600">
                        رقم الطلب: <span class="font-semibold">{{ $order->order_number }}</span>
                    </p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                        @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                        @elseif($order->status === 'completed') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        @if($order->status === 'pending')
                            في الانتظار
                        @elseif($order->status === 'confirmed')
                            مؤكد
                        @elseif($order->status === 'processing')
                            قيد المعالجة
                        @elseif($order->status === 'completed')
                            مكتمل
                        @else
                            ملغي
                        @endif
                    </span>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Order Items -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-primary-dark mb-4">الخدمات المطلوبة</h2>
                
                @foreach($order->orderItems as $item)
                <div class="border-b border-gray-200 pb-4 mb-4 last:border-b-0 last:pb-0 last:mb-0">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="font-semibold text-gray-900">{{ $item->service->name_ar }}</h3>
                            <p class="text-sm text-gray-600 mt-1">{{ $item->service->description_ar }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                الكمية: {{ $item->quantity }}
                            </p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-primary-dark">
                                {{ number_format($item->total_price, 2) }} ريال
                            </p>
                            <p class="text-sm text-gray-500">
                                {{ number_format($item->unit_price, 2) }} ريال لكل وحدة
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
                
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-primary-dark">المجموع الكلي:</span>
                        <span class="text-xl font-bold text-accent">
                            {{ number_format($order->total_amount, 2) }} ريال
                        </span>
                    </div>
                </div>
            </div>
            
            <!-- Customer Information -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-semibold text-primary-dark mb-4">معلومات العميل</h2>
                
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">الاسم الكامل:</label>
                        <p class="mt-1 text-gray-900">{{ $order->customer_name }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني:</label>
                        <p class="mt-1 text-gray-900">{{ $order->customer_email }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">رقم الهاتف:</label>
                        <p class="mt-1 text-gray-900">{{ $order->customer_phone }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">العنوان:</label>
                        <p class="mt-1 text-gray-900">{{ $order->customer_address }}</p>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700">تاريخ الطلب:</label>
                        <p class="mt-1 text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Payment Information -->
        <div class="bg-white rounded-lg shadow-lg p-6 mt-6">
            <h2 class="text-xl font-semibold text-primary-dark mb-4">معلومات الدفع</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700">حالة الدفع:</label>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium mt-1
                        @if($order->payment_status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->payment_status === 'paid') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        @if($order->payment_status === 'pending')
                            في الانتظار
                        @elseif($order->payment_status === 'paid')
                            مدفوع
                        @else
                            فشل
                        @endif
                    </span>
                </div>
                
                @if($order->payment_method)
                <div>
                    <label class="block text-sm font-medium text-gray-700">طريقة الدفع:</label>
                    <p class="mt-1 text-gray-900">{{ $order->payment_method }}</p>
                </div>
                @endif
            </div>
            
            @if($order->payment_status === 'pending')
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <p class="text-blue-800">
                    سيتم التواصل معك قريباً لتأكيد الطلب وتفاصيل الدفع.
                </p>
            </div>
            @endif
        </div>
        
        <!-- Actions -->
        <div class="mt-6 flex justify-center">
            <a href="{{ route('home') }}" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold">
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
</body>
</html>
