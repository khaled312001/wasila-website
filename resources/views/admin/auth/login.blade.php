<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>تسجيل دخول الإدارة - وسيلة</title>
    
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
            font-family: 'HT Qays Sans', 'Inter', sans-serif;
        }

        /* RTL font override */
        [dir="rtl"] body {
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
            <img src="{{ asset('images/logo-arabic.png') }}" 
                 alt="وسيلة" class="h-16 w-auto mx-auto mb-4">
            <h1 class="text-2xl font-bold text-white">
                لوحة تحكم الإدارة
            </h1>
        </div>
        
        <!-- Login Form -->
        <div class="bg-white rounded-lg shadow-xl p-8">
            <h2 class="text-2xl font-bold text-primary-dark mb-6 text-center">
                تسجيل الدخول
            </h2>
            
            @if(isset($errors) && $errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            <form method="POST" action="/admin/login">
                @csrf
                
                <div class="mb-6">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        البريد الإلكتروني
                    </label>
                    <input type="email" id="email" name="email" value="{{ old('email', '') }}" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        كلمة المرور
                    </label>
                    <input type="password" id="password" name="password" required
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent">
                </div>
                
                <div class="mb-6">
                    <label class="flex items-center">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-primary-medium focus:ring-primary-medium">
                        <span class="ml-2 text-sm text-gray-600">
                            تذكرني
                        </span>
                    </label>
                </div>
                
                <button type="submit" class="btn-primary text-white w-full py-3 rounded-lg font-semibold">
                    تسجيل الدخول
                </button>
            </form>
            
            <div class="mt-6 text-center">
                <a href="/" class="text-primary-medium hover:text-primary-dark transition duration-300">
                    العودة للموقع الرئيسي
                </a>
            </div>
        </div>
        
        
    </div>
</body>
</html>
