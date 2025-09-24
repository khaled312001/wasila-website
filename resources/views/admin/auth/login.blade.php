<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ app()->getLocale() === 'ar' ? 'تسجيل دخول الإدارة' : 'Admin Login' }} - وسيلة</title>
    
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
            font-family: 'HT Qays Sans', {{ app()->getLocale() === 'ar' ? "'Noto Sans Arabic', sans-serif" : "'Inter', sans-serif" }};
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
<body class="gradient-bg min-h-screen flex items-center justify-center">
    <div class="max-w-md w-full mx-4">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/' . (app()->getLocale() === 'ar' ? 'logo-arabic.png' : 'logo-english.png')) }}" 
                 alt="وسيلة" class="h-16 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-white">
                {{ app()->getLocale() === 'ar' ? 'لوحة تحكم الإدارة' : 'Admin Dashboard' }}
            </h1>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold text-primary-dark mb-6 text-center">
                {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
            </h2>
            
            @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form method="POST" action="{{ route('admin.login.post') }}">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }}
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('email') border-red-500 @enderror">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        {{ app()->getLocale() === 'ar' ? 'كلمة المرور' : 'Password' }}
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('password') border-red-500 @enderror">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-medium focus:ring-primary-medium">
                        <span class="ml-2 text-sm text-gray-600">
                            {{ app()->getLocale() === 'ar' ? 'تذكرني' : 'Remember Me' }}
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary text-white w-full py-3 rounded-lg font-semibold">
                    {{ app()->getLocale() === 'ar' ? 'تسجيل الدخول' : 'Login' }}
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="{{ route('home') }}" class="text-primary-medium hover:text-primary-dark transition duration-300">
                    {{ app()->getLocale() === 'ar' ? 'العودة للموقع الرئيسي' : 'Back to Main Website' }}
                </a>
            </div>
        </div>
        
        <!-- Demo Credentials -->
        <div class="mt-6 bg-white bg-opacity-20 rounded-lg p-4">
            <h3 class="text-white font-semibold mb-2">
                {{ app()->getLocale() === 'ar' ? 'بيانات الدخول التجريبية:' : 'Demo Credentials:' }}
            </h3>
            <p class="text-white text-sm">
                {{ app()->getLocale() === 'ar' ? 'البريد الإلكتروني:' : 'Email:' }} admin@wasila.org<br>
                {{ app()->getLocale() === 'ar' ? 'كلمة المرور:' : 'Password:' }} password
            </p>
        </div>
    </div>
</body>
</html>
