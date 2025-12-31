<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('messages.register') }} - {{ __('messages.wasila') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <style>
        :root {
            --primary-light: #3CA6B4;
            --primary-medium: #08788B;
            --primary-dark: #025469;
            --gold: #fbbf24;
        }
        
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Inter'" }}, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #334155 100%);
            min-height: 100vh;
        }
        
        .register-container {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
            backdrop-filter: blur(20px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }
        
        .input-field {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .input-field:focus {
            background: white;
            border-color: var(--gold);
            box-shadow: 0 0 0 4px rgba(251, 191, 36, 0.2);
            transform: translateY(-2px);
        }
        
        .btn-register {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-light) 100%);
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3);
        }
        
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(8, 120, 139, 0.4);
        }
        
        .btn-google {
            background: white;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
        
        .btn-google:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body class="flex items-center justify-center p-4 py-8">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <img src="{{ asset('images/logo-arabic.png') }}" 
                 alt="{{ __('messages.wasila') }}" 
                 class="h-20 w-auto mx-auto mb-4 filter brightness-0 invert">
            <h1 class="text-3xl font-bold text-white mb-2">
                {{ __('messages.create_account') }}
            </h1>
            <p class="text-white/80">
                {{ __('messages.join_us_today') }}
            </p>
        </div>
        
        <!-- Register Form -->
        <div class="register-container rounded-3xl p-8">
            <h2 class="text-2xl font-bold text-white mb-6 text-center">
                <i class="fas fa-user-plus mr-2"></i>
                {{ __('messages.register') }}
            </h2>
            
            @if($errors->any())
            <div class="bg-red-500/20 border-2 border-red-500/50 text-red-100 px-4 py-3 rounded-xl mb-6">
                <ul class="list-disc list-inside space-y-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif
            
            @if(session('success'))
            <div class="bg-green-500/20 border-2 border-green-500/50 text-green-100 px-4 py-3 rounded-xl mb-6">
                {{ session('success') }}
            </div>
            @endif
            
            <form method="POST" action="{{ route('customer.register') }}">
                @csrf
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-user mr-2"></i>
                        {{ __('messages.full_name') }}
                    </label>
                    <input type="text" 
                           id="name" 
                           name="name" 
                           value="{{ old('name') }}" 
                           required
                           class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                           placeholder="{{ __('messages.enter_full_name') }}">
                </div>
                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-envelope mr-2"></i>
                        {{ __('messages.email') }}
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}" 
                           required
                           class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                           placeholder="{{ __('messages.enter_email') }}">
                </div>
                
                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-phone mr-2"></i>
                        {{ __('messages.phone') }} ({{ __('messages.optional') }})
                    </label>
                    <input type="tel" 
                           id="phone" 
                           name="phone" 
                           value="{{ old('phone') }}"
                           class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                           placeholder="{{ __('messages.enter_phone') }}">
                </div>
                
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-lock mr-2"></i>
                        {{ __('messages.password') }}
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           required
                           class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                           placeholder="{{ __('messages.enter_password') }}">
                    <p class="text-white/60 text-xs mt-1">{{ __('messages.password_min_8_chars') }}</p>
                </div>
                
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-medium text-white mb-2">
                        <i class="fas fa-lock mr-2"></i>
                        {{ __('messages.confirm_password') }}
                    </label>
                    <input type="password" 
                           id="password_confirmation" 
                           name="password_confirmation" 
                           required
                           class="input-field w-full px-4 py-3 rounded-xl focus:outline-none"
                           placeholder="{{ __('messages.confirm_password') }}">
                </div>
                
                <button type="submit" class="btn-register text-white w-full py-3 rounded-xl font-semibold mb-4">
                    <i class="fas fa-user-plus mr-2"></i>
                    {{ __('messages.register') }}
                </button>
            </form>
            
            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/20"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-transparent text-white/60">{{ __('messages.or') }}</span>
                </div>
            </div>
            
            <!-- Google Register -->
            <a href="{{ route('auth.google') }}" class="btn-google text-gray-700 w-full py-3 rounded-xl font-semibold flex items-center justify-center mb-4">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                {{ __('messages.register_with_google') }}
            </a>
            
            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-white/80">
                    {{ __('messages.already_have_account') }}?
                    <a href="{{ route('customer.login') }}" class="text-white font-semibold hover:text-gold transition">
                        {{ __('messages.login') }}
                    </a>
                </p>
            </div>
            
            <!-- Back to Home -->
            <div class="text-center mt-4">
                <a href="{{ route('home') }}" class="text-white/60 hover:text-white transition text-sm">
                    <i class="fas fa-arrow-{{ app()->getLocale() === 'ar' ? 'right' : 'left' }} mr-2"></i>
                    {{ __('messages.back_to_home') }}
                </a>
            </div>
        </div>
    </div>
</body>
</html>

