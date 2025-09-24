<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.site_title'))</title>
    
    <!-- Favicon and Icons -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/logo-arabic.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/logo-arabic.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-arabic.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    
    <!-- Default SEO -->
    <x-seo 
        title="@yield('title', __('messages.site_title'))"
        description="{{ __('messages.site_description') }}"
        keywords="{{ __('messages.site_keywords') }}"
        image="{{ asset('images/logo-arabic.png') }}"
        url="{{ url()->current() }}"
        type="website"
        author="{{ __('messages.site_author') }}"
    />
    
    @stack('head')
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    
    <!-- Error Fixes Script - Must be loaded first -->
    <script src="{{ asset('js/error-fixes.js') }}"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/wasila.css') }}">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-light: #3CA6B4;
            --primary-medium: #08788B;
            --primary-dark: #025469;
            --accent: #DFA340;
        }
        
        body {
            font-family: 'Noto Sans Arabic', 'Inter', sans-serif;
        }
        
        .bg-primary-light { background-color: var(--primary-light); }
        .bg-primary-medium { background-color: var(--primary-medium); }
        .bg-primary-dark { background-color: var(--primary-dark); }
        .bg-accent { background-color: var(--accent); }
        
        .text-primary-light { color: var(--primary-light); }
        .text-primary-medium { color: var(--primary-medium); }
        .text-primary-dark { color: var(--primary-dark); }
        .text-accent { color: var(--accent); }
        
        .border-primary-light { border-color: var(--primary-light); }
        .border-primary-medium { border-color: var(--primary-medium); }
        .border-primary-dark { border-color: var(--primary-dark); }
        .border-accent { border-color: var(--accent); }
        
        .hover\:bg-primary-light:hover { background-color: var(--primary-light); }
        .hover\:bg-primary-medium:hover { background-color: var(--primary-medium); }
        .hover\:bg-primary-dark:hover { background-color: var(--primary-dark); }
        .hover\:bg-accent:hover { background-color: var(--accent); }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
        }
        
        .card-shadow {
            box-shadow: 0 10px 25px rgba(2, 84, 105, 0.1);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3);
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(30, 64, 175, 0.4);
            color: white !important;
        }
        
        .btn-accent {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white !important;
            border: none;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }
        
        .btn-accent:hover {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(220, 38, 38, 0.4);
            color: white !important;
        }
        
        .rtl {
            direction: rtl;
        }
        
        .ltr {
            direction: ltr;
        }
        
        /* Additional button styles for better visibility */
        button, .btn, input[type="submit"], input[type="button"] {
            color: white !important;
            font-weight: 600;
        }
        
        .bg-gradient-to-r {
            color: white !important;
        }
        
        /* Ensure all buttons have proper contrast */
        .text-white {
            color: white !important;
        }
        
        /* Force button colors with higher specificity */
        .btn-primary, .btn-accent, 
        a.btn-primary, a.btn-accent,
        button.btn-primary, button.btn-accent {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            color: white !important;
            border: none !important;
            text-decoration: none !important;
        }
        
        .btn-accent, a.btn-accent, button.btn-accent {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        }
        
        /* Optimized font sizes for better readability */
        h1 {
            font-size: 2.5rem !important;
            line-height: 1.2 !important;
        }
        
        @media (min-width: 768px) {
            h1 {
                font-size: 3rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h1 {
                font-size: 3.5rem !important;
            }
        }
        
        h2 {
            font-size: 2rem !important;
            line-height: 1.3 !important;
        }
        
        @media (min-width: 768px) {
            h2 {
                font-size: 2.5rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h2 {
                font-size: 3rem !important;
            }
        }
        
        h3 {
            font-size: 1.5rem !important;
            line-height: 1.3 !important;
        }
        
        @media (min-width: 768px) {
            h3 {
                font-size: 1.75rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h3 {
                font-size: 2rem !important;
            }
        }
        
        p {
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }
        
        @media (min-width: 768px) {
            p {
                font-size: 1.125rem !important;
            }
        }
        
        /* Remove SVG from buttons and make text normal */
        .btn-primary svg, .btn-accent svg,
        a.btn-primary svg, a.btn-accent svg,
        button.btn-primary svg, button.btn-accent svg {
            display: none !important;
        }
        
        /* Make button text normal colored */
        .btn-primary, .btn-accent,
        a.btn-primary, a.btn-accent,
        button.btn-primary, button.btn-accent {
            color: white !important;
            font-weight: 600 !important;
            text-decoration: none !important;
        }
        
        /* Remove flex from button spans */
        .btn-primary span, .btn-accent span,
        a.btn-primary span, a.btn-accent span,
        button.btn-primary span, button.btn-accent span {
            display: inline !important;
        }
        
        /* Additional button overrides for all button types */
        a[class*="btn"], button[class*="btn"], 
        .inline-block[class*="btn"], 
        .px-8.py-3[class*="btn"] {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            color: white !important;
            border: none !important;
            text-decoration: none !important;
            display: inline-block !important;
        }
        
        /* Specific overrides for accent buttons */
        .btn-accent, a.btn-accent, button.btn-accent,
        a[class*="btn-accent"], button[class*="btn-accent"] {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        }
        
        /* Override any white backgrounds on buttons */
        .bg-white, .bg-gray-50, .bg-transparent {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
        }
        
        /* Ensure text is always white on buttons */
        .btn-primary *, .btn-accent *,
        a.btn-primary *, a.btn-accent *,
        button.btn-primary *, button.btn-accent * {
            color: white !important;
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center">
                        <img src="{{ asset('images/' . (app()->getLocale() === 'ar' ? 'logo-arabic.png' : 'logo-english.png')) }}" style="height: 60px; width: auto;"
                             alt="وسيلة" class="h-10 w-auto">
                    </a>
                </div>
                
                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                    <a href="{{ app()->getLocale() === 'ar' ? route('home') : route('home.en') }}" class="text-gray-700 hover:text-primary-medium transition duration-300">
                        {{ __('messages.home') }}
                    </a>
                    <a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" class="text-gray-700 hover:text-primary-medium transition duration-300">
                        {{ __('messages.services') }}
                    </a>
                    <a href="#about" class="text-gray-700 hover:text-primary-medium transition duration-300">
                        {{ __('messages.about') }}
                    </a>
                    <a href="#contact" class="text-gray-700 hover:text-primary-medium transition duration-300">
                        {{ __('messages.contact') }}
                    </a>
                </div>
                
                <!-- Language Switcher -->
                <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                    <a href="{{ route('lang.switch', 'ar') }}" 
                       class="text-sm {{ app()->getLocale() === 'ar' ? 'text-primary-medium font-semibold' : 'text-gray-500' }}"
                       onclick="switchLanguage('ar', event)">
                        {{ __('messages.arabic') }}
                    </a>
                    <span class="text-gray-300">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" 
                       class="text-sm {{ app()->getLocale() === 'en' ? 'text-primary-medium font-semibold' : 'text-gray-500' }}"
                       onclick="switchLanguage('en', event)">
                        {{ __('messages.english') }}
                    </a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="text-gray-700 hover:text-primary-medium focus:outline-none focus:text-primary-medium">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gradient-to-br from-primary-dark via-primary-medium to-primary-dark text-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-0 w-full h-full bg-[url('data:image/svg+xml,%3Csvg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"%3E%3Cg fill="none" fill-rule="evenodd"%3E%3Cg fill="%23ffffff" fill-opacity="0.1"%3E%3Ccircle cx="30" cy="30" r="2"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 relative z-10">
            <!-- Main Footer Content -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-12">
                <!-- Logo and Description -->
                <div class="lg:col-span-2">
                    <div class="flex items-center mb-6">
                        <img src="{{ asset('images/logo-footer.png') }}" alt="وسيلة" class="h-16 w-auto mr-4">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ app()->getLocale() === 'ar' ? 'وسيلة' : 'Wasila' }}</h3>
                            <p class="text-accent text-sm font-medium">{{ __('messages.hero_subtitle') }}</p>
                        </div>
                    </div>
                    <p class="text-gray-300 leading-relaxed text-lg mb-6 max-w-md">
                        {{ __('messages.hero_description') }}
                    </p>
                    
                    <!-- Social Media Links -->
                    <div class="flex space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                            </svg>
                        </a>
                        <a href="#" class="w-10 h-10 bg-white/10 rounded-full flex items-center justify-center hover:bg-accent transition-colors duration-300">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-6 text-white">
                        {{ __('messages.quick_links') }}
                    </h3>
                    <ul class="space-y-3">
                        <li><a href="{{ app()->getLocale() === 'ar' ? route('home') : route('home.en') }}" class="text-gray-300 hover:text-accent transition duration-300 flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('messages.home') }}
                        </a></li>
                        <li><a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" class="text-gray-300 hover:text-accent transition duration-300 flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('messages.services') }}
                        </a></li>
                        <li><a href="#about" class="text-gray-300 hover:text-accent transition duration-300 flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('messages.about') }}
                        </a></li>
                        <li><a href="#contact" class="text-gray-300 hover:text-accent transition duration-300 flex items-center group">
                            <svg class="w-4 h-4 mr-2 group-hover:translate-x-1 transition-transform duration-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            {{ __('messages.contact') }}
                        </a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-6 text-white">
                        {{ __('messages.contact_title') }}
                    </h3>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-accent mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            <div>
                                <p class="text-gray-300 font-medium">{{ __('messages.contact_email') }}</p>
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::contactEmail() }}" class="text-gray-400 hover:text-accent transition duration-300">{{ \App\Helpers\SettingsHelper::contactEmail() }}</a>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-accent mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                            </svg>
                            <div>
                                <p class="text-gray-300 font-medium">{{ __('messages.contact_phone') }}</p>
                                <a href="tel:{{ \App\Helpers\SettingsHelper::contactPhone() }}" class="text-gray-400 hover:text-accent transition duration-300">{{ \App\Helpers\SettingsHelper::contactPhone() }}</a>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-accent mr-3 mt-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-gray-300 font-medium">{{ __('messages.contact_address') }}</p>
                                <p class="text-gray-400">{{ \App\Helpers\SettingsHelper::address() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bottom Section -->
            <div class="border-t border-white/20 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-gray-300 text-lg">
                            &copy; {{ date('Y') }} {{ app()->getLocale() === 'ar' ? 'وسيلة. ' : 'Wasila. ' }}{{ __('messages.all_rights_reserved') }}
                        </p>
                    </div>
                   
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    
    <script>
        function switchLanguage(locale, event) {
            event.preventDefault();
            
            // Show loading indicator
            const link = event.target;
            const originalText = link.textContent;
            link.textContent = locale === 'ar' ? 'جاري التحميل...' : 'Loading...';
            
            // Create form to submit language change
            const form = document.createElement('form');
            form.method = 'GET';
            form.action = '{{ route("lang.switch", ":locale") }}'.replace(':locale', locale);
            
            // Add CSRF token if needed
            const token = document.querySelector('meta[name="csrf-token"]');
            if (token) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = '_token';
                input.value = token.content;
                form.appendChild(input);
            }
            
            document.body.appendChild(form);
            form.submit();
        }
    </script>
    
    @stack('scripts')
    
    <!-- Additional CSS to force button colors -->
    <style>
        /* Force all buttons to have proper colors */
        .btn-primary, .btn-accent, 
        a.btn-primary, a.btn-accent,
        button.btn-primary, button.btn-accent,
        .inline-block.btn-primary, .inline-block.btn-accent {
            background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
            color: white !important;
            border: none !important;
            text-decoration: none !important;
            font-weight: 600 !important;
            padding: 0.75rem 2rem !important;
            border-radius: 0.5rem !important;
            display: inline-block !important;
            transition: all 0.3s ease !important;
        }
        
        .btn-accent, a.btn-accent, button.btn-accent,
        .inline-block.btn-accent {
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        }
        
        /* Hover effects */
        .btn-primary:hover, .btn-accent:hover,
        a.btn-primary:hover, a.btn-accent:hover,
        button.btn-primary:hover, button.btn-accent:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
            color: white !important;
        }
        
        /* Override any conflicting styles */
        .text-white {
            color: white !important;
        }
        
        /* Make sure all text in buttons is white */
        .btn-primary, .btn-accent,
        a.btn-primary, a.btn-accent,
        button.btn-primary, button.btn-accent {
            color: white !important;
        }
        
        .btn-primary *, .btn-accent *,
        a.btn-primary *, a.btn-accent *,
        button.btn-primary *, button.btn-accent * {
            color: white !important;
        }
    </style>
    
    @stack('scripts')
</body>
</html>
