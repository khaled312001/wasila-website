@extends('layouts.app')

@section('title', __('messages.contact_us'))

@push('head')
<x-seo 
    title="{{ __('messages.contact_us') }}"
    description="{{ __('messages.contact_title') }}"
    keywords="{{ __('messages.site_keywords') }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/contact') }}"
    type="website"
    author="{{ __('messages.site_author') }}"
/>
@endpush

@push('styles')
<style>
    /* Contact Form Success/Error Animations */
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    @keyframes slideInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    @keyframes bounce {
        0%, 20%, 53%, 80%, 100% {
            transform: translate3d(0,0,0);
        }
        40%, 43% {
            transform: translate3d(0, -10px, 0);
        }
        70% {
            transform: translate3d(0, -5px, 0);
        }
        90% {
            transform: translate3d(0, -2px, 0);
        }
    }
    
    .contact-success-animation {
        animation: slideInUp 0.6s ease-out, bounce 0.8s ease-out 0.6s;
    }
    
    .contact-error-animation {
        animation: slideInUp 0.6s ease-out;
    }
    
    /* Enhanced button loading state */
    .btn-loading {
        position: relative;
        overflow: hidden;
    }
    
    .btn-loading::after {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: loading 1.5s infinite;
    }
    
    @keyframes loading {
        0% { left: -100%; }
        100% { left: 100%; }
    }
</style>
<style>
    /* Force button colors with maximum specificity */
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
        box-shadow: 0 4px 15px rgba(30, 64, 175, 0.3) !important;
    }
    
    .btn-accent, a.btn-accent, button.btn-accent,
    .inline-block.btn-accent {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3) !important;
    }
    
    /* Hover effects */
    .btn-primary:hover, .btn-accent:hover,
    a.btn-primary:hover, a.btn-accent:hover,
    button.btn-primary:hover, button.btn-accent:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3) !important;
        color: white !important;
    }
    
    /* Override any white backgrounds */
    .bg-white.btn-primary, .bg-white.btn-accent,
    a.bg-white.btn-primary, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%) !important;
    }
    
    .bg-white.btn-accent, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
    }
    
    /* Ensure text is always white */
    .btn-primary, .btn-accent,
    a.btn-primary, a.btn-accent,
    button.btn-primary, button.btn-accent,
    .btn-primary *, .btn-accent *,
    a.btn-primary *, a.btn-accent *,
    button.btn-primary *, button.btn-accent * {
        color: white !important;
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
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="gradient-bg text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold mb-6">
                {{ __('messages.contact_us') }}
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-8 text-gray-200 max-w-4xl mx-auto">
                {{ __('messages.contact_title') }}
            </p>
        </div>
    </div>
</section>

<!-- Contact Content Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-96 h-96 bg-accent rounded-full -translate-x-48 -translate-y-48 animate-pulse"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-light rounded-full translate-x-48 translate-y-48 animate-pulse"></div>
        <div class="absolute top-1/2 left-1/2 w-64 h-64 bg-accent/30 rounded-full -translate-x-32 -translate-y-32 animate-pulse"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <div class="max-w-6xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <!-- Contact Information -->
                <div class="space-y-8">
                    <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-2xl">
                        <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center">
                            {{ __('messages.contact_title') }}
                        </h3>
                        
                        <div class="space-y-6">
                            <!-- Email -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-r from-primary-light to-accent rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ __('messages.contact_email') }}
                                    </h4>
                                    <a href="mailto:{{ \App\Helpers\SettingsHelper::contactEmail() }}" class="text-gray-700 text-lg hover:text-accent transition-colors duration-300">
                                        {{ \App\Helpers\SettingsHelper::contactEmail() }}
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Phone -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-r from-accent to-yellow-400 rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ __('messages.contact_phone') }}
                                    </h4>
                                    <a href="tel:{{ \App\Helpers\SettingsHelper::contactPhone() }}" class="text-gray-700 text-lg hover:text-accent transition-colors duration-300">
                                        {{ \App\Helpers\SettingsHelper::contactPhone() }}
                                    </a>
                                </div>
                            </div>
                            
                            <!-- Location -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-r from-primary-medium to-primary-light rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-gray-900 mb-1">
                                        {{ __('messages.contact_address') }}
                                    </h4>
                                    <p class="text-gray-700 text-lg">{{ \App\Helpers\SettingsHelper::address() }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Social Media -->
                    <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-2xl">
                        <h4 class="text-xl font-semibold text-gray-900 mb-6 text-center">
                            {{ __('messages.follow_us_on') }}
                        </h4>
                        <div class="flex justify-center space-x-6 space-x-reverse">
                            <!-- Facebook -->
                            <a href="#" class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center hover:bg-blue-700 hover:scale-110 transition-all duration-300 shadow-lg border-2 border-blue-500">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                </svg>
                            </a>
                            
                            <!-- Twitter -->
                            <a href="#" class="w-16 h-16 bg-sky-500 rounded-full flex items-center justify-center hover:bg-sky-600 hover:scale-110 transition-all duration-300 shadow-lg border-2 border-sky-400">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/>
                                </svg>
                            </a>
                            
                            <!-- Instagram -->
                            <a href="#" class="w-16 h-16 bg-gradient-to-r from-pink-500 to-purple-600 rounded-full flex items-center justify-center hover:from-pink-600 hover:to-purple-700 hover:scale-110 transition-all duration-300 shadow-lg border-2 border-pink-400">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                            
                            <!-- WhatsApp -->
                            <a href="#" class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center hover:bg-green-600 hover:scale-110 transition-all duration-300 shadow-lg border-2 border-green-400">
                                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Form -->
                <div class="bg-white rounded-3xl p-8 border border-gray-200 shadow-2xl">
                    <h3 class="text-2xl md:text-3xl font-bold text-gray-900 mb-8 text-center">
                        {{ __('messages.send_us_message') }}
                    </h3>
                    <form id="contactForm" class="space-y-6" method="POST" action="{{ route('contact.store') }}">
                        @csrf
                        <div class="relative">
                            <input type="text" name="name" placeholder="{{ __('messages.name') }}" required
                                   class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <input type="email" name="email" placeholder="{{ __('messages.email') }}" required
                                   class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <input type="tel" name="phone" placeholder="{{ __('messages.contact_phone') }}" required
                                   class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <input type="text" name="subject" placeholder="{{ __('messages.subject_optional') }}"
                                   class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300">
                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8zm0 4a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1v-2z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </div>
                        
                        <div class="relative">
                            <textarea name="message" rows="5" placeholder="{{ __('messages.message') }}" required
                                      class="w-full px-6 py-4 rounded-2xl bg-gray-50 border border-gray-300 text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-accent focus:border-transparent transition-all duration-300 resize-none"></textarea>
                            <div class="absolute top-4 right-4">
                                <svg class="w-5 h-5 text-accent" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                </svg>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full btn-primary text-white px-8 py-4 rounded-2xl font-bold text-lg flex items-center justify-center group hover:scale-105 transition-all duration-300 shadow-lg">
                            <span>{{ __('messages.send_message') }}</span>
                        </button>
                    </form>
                    
                    <!-- Success/Error Messages -->
                    <div id="contactMessage" class="mt-6 hidden">
                        <div id="contactSuccess" class="bg-gradient-to-r from-green-50 to-green-100 border-2 border-green-300 text-green-800 px-6 py-5 rounded-xl hidden flex items-center shadow-lg">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mr-4">
                                <p class="font-bold text-xl text-green-800">{{ __('messages.message_sent_successfully') }}</p>
                                <p class="text-green-700 mt-1">{{ __('messages.thank_you_contact') }}</p>
                            </div>
                        </div>
                        <div id="contactError" class="bg-gradient-to-r from-red-50 to-red-100 border-2 border-red-300 text-red-800 px-6 py-5 rounded-xl hidden flex items-center shadow-lg">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center">
                                    <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="mr-4">
                                <p class="font-bold text-xl text-red-800">{{ __('messages.error_occurred') }}</p>
                                <p class="text-red-700 mt-1">{{ __('messages.error_sending_message') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contactForm = document.getElementById('contactForm');
    const contactMessage = document.getElementById('contactMessage');
    const contactSuccess = document.getElementById('contactSuccess');
    const contactError = document.getElementById('contactError');

    if (contactForm) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide previous messages
            contactMessage.classList.add('hidden');
            contactSuccess.classList.add('hidden');
            contactError.classList.add('hidden');
            
            // Get form data
            const formData = new FormData(contactForm);
            
            // Show loading state
            const submitBtn = contactForm.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span>{{ __("messages.sending") }}</span>';
            submitBtn.disabled = true;
            submitBtn.classList.add('btn-loading');
            
            // Send AJAX request
            fetch(contactForm.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                contactMessage.classList.remove('hidden');
                if (data.success) {
                    contactSuccess.classList.remove('hidden');
                    contactSuccess.classList.add('contact-success-animation');
                    contactForm.reset();
                } else {
                    contactError.classList.remove('hidden');
                    contactError.classList.add('contact-error-animation');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                contactMessage.classList.remove('hidden');
                contactError.classList.remove('hidden');
                contactError.classList.add('contact-error-animation');
            })
            .finally(() => {
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                submitBtn.classList.remove('btn-loading');
            });
        });
    }
});
</script>
@endpush