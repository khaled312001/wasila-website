@extends('layouts.app')

@section('title', __('messages.hero_title') . ' - ' . __('messages.hero_subtitle'))

@push('head')
<x-seo 
    title="{{ __('messages.site_title') }}"
    description="{{ __('messages.site_description') }}"
    keywords="{{ __('messages.site_keywords') }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/') }}"
    type="website"
    author="{{ __('messages.site_author') }}"
/>
@endpush

@push('styles')
<link href="{{ asset('css/landing-custom.css') }}" rel="stylesheet">
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
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%) !important;
        color: white !important;
        border: none !important;
        text-decoration: none !important;
        font-weight: 600 !important;
        padding: 0.75rem 2rem !important;
        border-radius: 0.5rem !important;
        display: inline-block !important;
        transition: all 0.3s ease !important;
        box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3) !important;
        font-family: 'Tajawal', 'Inter', sans-serif !important;
    }
    
    .btn-accent, a.btn-accent, button.btn-accent,
    .inline-block.btn-accent {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%) !important;
        box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3) !important;
    }
    
    /* Hover effects */
    .btn-primary:hover, .btn-accent:hover,
    a.btn-primary:hover, a.btn-accent:hover,
    button.btn-primary:hover, button.btn-accent:hover {
        transform: translateY(-2px) !important;
        box-shadow: 0 10px 25px rgba(8, 120, 139, 0.4) !important;
        background: linear-gradient(135deg, #065a6b 0%, #2a8a96 100%) !important;
        color: white !important;
    }
    
    /* Override any white backgrounds */
    .bg-white.btn-primary, .bg-white.btn-accent,
    a.bg-white.btn-primary, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%) !important;
    }
    
    .bg-white.btn-accent, a.bg-white.btn-accent {
        background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%) !important;
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
<section class="hero-section text-white py-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="hero-content text-center">
            <h1 class="hero-title" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.hero_title') }} - {{ __('messages.hero_subtitle') }}
            </h1>
            <p class="hero-subtitle max-w-4xl mx-auto">
                {{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'hero_description_ar' : 'hero_description_en', __('messages.hero_description')) }}
            </p>
            <div class="hero-buttons flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('services') }}" class="btn-primary-enhanced">
                    {{ __('messages.browse_services') }}
                </a>
                <a href="{{ route('portfolio') }}" class="btn-secondary-enhanced">
                    {{ __('messages.our_work') }}
                </a>
                <a href="#about" class="btn-primary-enhanced">
                    {{ __('messages.learn_more') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="services-section">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-light rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="background-color: #10b981; animation-delay: 2s;"></div>
        <div class="absolute -bottom-8 left-20 w-72 h-72 bg-primary-medium rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 4s;"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Section Header -->
        <div class="text-center mb-16">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-primary-light to-primary-medium rounded-full mb-6">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h2 class="text-xl md:text-2xl lg:text-2xl font-bold text-primary-dark mb-6" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.services_title') }}
            </h2>
            <p class="text-base md:text-lg text-gray-600 max-w-4xl mx-auto leading-relaxed" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.services_description') }}
            </p>
        </div>
        
        @if($services->count() > 0)
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($services as $index => $service)
            <a href="{{ route('orders.checkout', [
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'service_description' => $service->description
            ]) }}" class="service-card group relative block" style="height: 100%; display: flex; flex-direction: column;">
                <!-- Card Badge -->
                <div class="absolute top-4 right-4 z-10">
                    <span class="text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                        {{ __('messages.premium') }}
                    </span>
                </div>
                
                <!-- Image Section -->
                <div class="relative overflow-hidden">
                    @if($service->image)
                    @php
                        $imageUrl = \Storage::disk('public')->exists($service->image) 
                            ? \Storage::disk('public')->url($service->image)
                            : asset('storage/' . (str_starts_with($service->image, 'storage/') ? substr($service->image, 8) : $service->image));
                    @endphp
                    <img src="{{ $imageUrl }}" 
                         alt="{{ $service->name }} - {{ __('messages.charity_service_from_wasila') }}" 
                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500" 
                         loading="lazy"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-56 bg-gradient-to-br from-primary-light via-primary-medium to-accent flex items-center justify-center relative\'><div class=\'absolute inset-0 bg-black opacity-20\'></div><svg class=\'w-20 h-20 text-white relative z-10\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg></div>';">
                    @else
                    <div class="w-full h-56 bg-gradient-to-br from-primary-light via-primary-medium to-accent flex items-center justify-center relative">
                        <div class="absolute inset-0 bg-black opacity-20"></div>
                        <svg class="w-20 h-20 text-white relative z-10" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    @endif
                    
                    <!-- Overlay Gradient -->
                    <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                </div>
                
                <!-- Content Section -->
                <div class="p-6 flex-grow flex flex-col">
                    <!-- Service Icon -->
                    <div class="service-icon mb-4">
                        <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    
                    <!-- Service Title -->
                    <h3 class="service-title text-lg font-semibold mb-3" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                        {{ $service->name }}
                    </h3>
                    
                    <!-- Service Description -->
                    <p class="service-description text-sm text-gray-600 mb-4 flex-grow" style="font-family: 'Tajawal', 'Inter', sans-serif; line-height: 1.6;">
                        {{ $service->description }}
                    </p>
                    
                    <!-- Price and Action -->
                    <div class="flex items-center justify-between mt-auto pt-4 border-t border-gray-200">
                        <div class="flex flex-col">
                            <span class="text-xl font-bold mb-1" style="color: #08788B;">
                                {{ number_format($service->price, 2) }}
                            </span>
                            <span class="text-xs text-gray-500">
                                {{ __('messages.saudi_riyal') }}
                            </span>
                        </div>
                        <button class="bg-gradient-to-r from-primary-medium to-primary-light hover:from-primary-dark hover:to-primary-medium text-white px-4 py-2 rounded-lg font-semibold text-sm transition-all duration-300 transform hover:scale-105 shadow-md hover:shadow-lg" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                            {{ __('messages.order_now') }}
                        </button>
                    </div>
                </div>
                
                <!-- Hover Effect Border -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-primary-light transition-colors duration-300 pointer-events-none"></div>
            </a>
            @endforeach
        </div>
        
        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-primary-light to-primary-medium rounded-2xl p-8 md:p-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-white opacity-10"></div>
                <div class="relative z-10">
                    <h3 class="text-lg md:text-xl lg:text-2xl font-bold text-white mb-4" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                        {{ __('messages.discover_more_services') }}
                    </h3>
                    <p class="text-base md:text-lg text-white/90 mb-8 max-w-3xl mx-auto" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                        {{ __('messages.discover_more_description') }}
                    </p>
                    <a href="{{ route('services') }}" 
                       class="btn-primary-enhanced">
                        <span>{{ __('messages.view_all_services') }}</span>
                    </a>
                </div>
            </div>
        </div>
        @else
        <!-- Empty State -->
        <div class="text-center py-20">
            <div class="w-32 h-32 bg-gradient-to-r from-primary-light to-primary-medium rounded-full flex items-center justify-center mx-auto mb-8">
                <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-lg md:text-xl font-bold text-primary-dark mb-4" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.services_coming_soon') }}
            </h3>
            <p class="text-base md:text-lg text-gray-600 max-w-3xl mx-auto" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.services_coming_soon_description') }}
            </p>
        </div>
        @endif
    </div>
</section>

<!-- About Section -->
<section id="about" class="about-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="about-content">
            <h2 class="about-title text-2xl md:text-3xl" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.about_title') }}
            </h2>
            <p class="about-description text-base md:text-lg" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.about_description') }}
            </p>
            <p class="about-description text-base md:text-lg" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.about_mission') }}
            </p>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-primary-medium mb-2">{{ __('messages.services_provided_number') }}</div>
                    <div class="text-sm md:text-base text-gray-600">{{ __('messages.services_provided') }}</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl md:text-3xl font-bold text-primary-medium mb-2">{{ __('messages.beneficiaries_number') }}</div>
                    <div class="text-sm md:text-base text-gray-600">{{ __('messages.beneficiaries') }}</div>
                </div>
            </div>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('services') }}" class="btn-primary-enhanced">
                    {{ __('messages.our_services') }}
                </a>
                <a href="#contact" class="btn-secondary-enhanced">
                    {{ __('messages.contact_us') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-lg md:text-xl lg:text-2xl font-bold text-primary-dark mb-4" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.features_subtitle') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-base md:text-lg font-semibold text-primary-dark mb-3" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                    {{ __('messages.diverse_services') }}
                </h3>
                <p class="text-sm md:text-base text-gray-600" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                    {{ __('messages.diverse_services_description') }}
                </p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-medium rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-primary-dark mb-3">
                    {{ __('messages.specialized_team') }}
                </h3>
                <p class="text-sm md:text-base text-gray-600">
                    {{ __('messages.expert_team_description') }}
                </p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4" style="background-color: #10b981;">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-primary-dark mb-3">
                    {{ __('messages.positive_impact') }}
                </h3>
                <p class="text-sm md:text-base text-gray-600">
                    {{ __('messages.positive_impact_description') }}
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="contact-content">
            <h2 class="contact-title text-2xl md:text-3xl" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.contact_us_title') }}
            </h2>
            <p class="text-base md:text-lg mb-8 max-w-3xl mx-auto text-center" style="color: #1f2937;">
                {{ __('messages.contact_us_description') }}
            </p>
        
        <!-- Contact Content Grid -->
        <div class="row mb-16">
            <!-- Contact Information Card -->
            <div class="col col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon icon-lg mx-auto">
                            <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 mb-4">
                            {{ __('messages.contact_information') }}
                        </h3>
                        <p class="text-gray-600 mb-6">
                            {{ __('messages.contact_us_description') }}
                        </p>
                        
                        <!-- Email -->
                        <div class="contact-item">
                            <div class="contact-icon email">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                            </div>
                            <div class="contact-info">
                                <h4>{{ __('messages.contact_email') }}</h4>
                                <a href="mailto:{{ __('messages.contact_email_value') }}">{{ __('messages.contact_email_value') }}</a>
                            </div>
                        </div>
                        
                        <!-- Phone -->
                        <div class="contact-item">
                            <div class="contact-icon phone">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                </svg>
                            </div>
                            <div class="contact-info">
                                <h4>{{ __('messages.contact_phone') }}</h4>
                                <a href="tel:{{ __('messages.contact_phone_value') }}">{{ __('messages.contact_phone_value') }}</a>
                            </div>
                        </div>
                        
                        <!-- Location -->
                        <div class="contact-item">
                            <div class="contact-icon location">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="contact-info">
                                <h4>{{ __('messages.contact_address') }}</h4>
                                <p>{{ __('messages.contact_address_value') }}</p>
                            </div>
                        </div>
                        
                        <!-- Working Hours -->
                        <div class="contact-item">
                            <div class="contact-icon clock">
                                <svg width="24" height="24" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="contact-info">
                                <h4>{{ __('messages.working_hours') }}</h4>
                                <p>{{ __('messages.working_hours') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Contact Form Card -->
            <div class="col col-lg-6 mb-4">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="icon icon-lg mx-auto">
                            <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 mb-4">
                            {{ __('messages.send_us_message_title') }}
                        </h3>
                        <p class="text-gray-600 mb-6">
                            {{ __('messages.contact_us_description') }}
                        </p>
                        
                        <form id="contactForm" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <div class="row">
                                <div class="col col-6 mb-4">
                                    <input type="text" name="name" placeholder="{{ __('messages.full_name_label') }}" 
                                           class="form-control" required>
                                </div>
                                <div class="col col-6 mb-4">
                                    <input type="email" name="email" placeholder="{{ __('messages.email_input_label') }}" 
                                           class="form-control" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col col-6 mb-4">
                                    <input type="tel" name="phone" placeholder="{{ __('messages.phone_number_label') }}" 
                                           class="form-control">
                                </div>
                                <div class="col col-6 mb-4">
                                    <input type="text" name="subject" placeholder="{{ __('messages.subject_optional_label') }}" 
                                           class="form-control">
                                </div>
                            </div>
                            <div class="form-group">
                                <textarea name="message" placeholder="{{ __('messages.write_message_placeholder') }}" 
                                          class="form-control" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg btn-full">
                                {{ __('messages.send_message_button') }}
                            </button>
                        </form>
                        
                        <!-- Success/Error Messages -->
                        <div id="contactMessage" class="mt-6 d-none">
                            <div id="contactSuccess" class="d-none">
                                <div class="d-flex align-center p-4 rounded-lg" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border: 2px solid #86efac; color: #166534;">
                                    <div class="icon icon-sm" style="background-color: #10b981; margin-left: 1rem;">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold mb-1">{{ __('messages.message_sent_successfully') }}</p>
                                        <p class="text-sm">{{ __('messages.thank_you_contact') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div id="contactError" class="d-none">
                                <div class="d-flex align-center p-4 rounded-lg" style="background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%); border: 2px solid #fca5a5; color: #dc2626;">
                                    <div class="icon icon-sm" style="background-color: #ef4444; margin-left: 1rem;">
                                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-bold mb-1">{{ __('messages.error_occurred') }}</p>
                                        <p class="text-sm">{{ __('messages.error_sending_message') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section -->
        <div class="card">
            <div class="card-header text-center">
                <div class="icon icon-lg mx-auto">
                    <svg width="32" height="32" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <h3 class="text-gray-800 mb-4">
                    {{ __('messages.our_location') }}
                </h3>
                <p class="text-gray-600">
                    {{ __('messages.riyadh_saudi_arabia') }}
                </p>
            </div>
            
            <!-- Interactive Map -->
            <div class="map-container">
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3624.5!2d46.6753!3d24.7136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e2ee2b5b5b5b5b5%3A0x3e2ee2b5b5b5b5b5!2sRiyadh%2C%20Saudi%20Arabia!5e0!3m2!1sen!2s!4v1234567890123!5m2!1sen!2s" 
                    width="100%" 
                    height="100%" 
                    style="border:0;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                
                <!-- Map Overlay with Location Info -->
                <div class="map-overlay">
                    <div class="location-marker">
                        <div class="marker-icon">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="marker-info">
                            <h5>{{ app()->getLocale() === 'ar' ? 'وسيلة الخيرية' : 'Wasila Charity' }}</h5>
                            <p>{{ app()->getLocale() === 'ar' ? 'الرياض، السعودية' : 'Riyadh, Saudi Arabia' }}</p>
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
    console.log('Contact form script loaded');
    
    const contactForm = document.getElementById('contactForm');
    const contactMessage = document.getElementById('contactMessage');
    const contactSuccess = document.getElementById('contactSuccess');
    const contactError = document.getElementById('contactError');
    
    if (!contactForm) {
        console.error('Contact form not found');
        return;
    }
    
    console.log('Contact form found, adding event listener');
    
    contactForm.addEventListener('submit', function(e) {
        console.log('Form submitted, preventing default');
        e.preventDefault();
        e.stopPropagation();
        
        // Hide previous messages with animation
        contactMessage.classList.add('hidden');
        contactSuccess.classList.add('hidden');
        contactError.classList.add('hidden');
        
        // Get form data
        const formData = new FormData(contactForm);
        
        // Show loading state
        const submitButton = contactForm.querySelector('button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ app()->getLocale() === "ar" ? "جاري الإرسال..." : "Sending..." }}
        `;
        submitButton.disabled = true;
        submitButton.classList.add('btn-loading');
        
        // Send AJAX request
        console.log('Sending AJAX request to:', '{{ route("contact.store") }}');
        
        fetch('{{ route("contact.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => {
            console.log('Response received:', response);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Data received:', data);
            if (data.success) {
                console.log('Success! Showing success message');
                
                // Show success message with animation
                contactSuccess.classList.remove('hidden');
                contactMessage.classList.remove('hidden');
                
                // Add success animation
                contactSuccess.classList.add('contact-success-animation');
                
                // Reset form
                contactForm.reset();
                
                // Scroll to success message
                contactMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Auto-hide success message after 6 seconds
                setTimeout(() => {
                    contactSuccess.style.transition = 'all 0.5s ease';
                    contactSuccess.style.opacity = '0';
                    contactSuccess.style.transform = 'translateY(-20px) scale(0.95)';
                    setTimeout(() => {
                        contactMessage.classList.add('hidden');
                        // Reset styles
                        contactSuccess.classList.remove('contact-success-animation');
                        contactSuccess.style.transition = '';
                        contactSuccess.style.transform = '';
                        contactSuccess.style.opacity = '';
                    }, 500);
                }, 6000);
                
            } else {
                console.log('Error in response:', data);
                
                // Show error message
                contactError.classList.remove('hidden');
                contactMessage.classList.remove('hidden');
                
                // Add error animation
                contactError.classList.add('contact-error-animation');
                
                // Scroll to error message
                contactMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        })
        .catch(error => {
            console.error('Error occurred:', error);
            
            // Show error message
            contactError.classList.remove('hidden');
            contactMessage.classList.remove('hidden');
            
            // Add error animation
            contactError.classList.add('contact-error-animation');
            
            // Scroll to error message
            contactMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
            // Log error for debugging
            console.log('Error details:', error);
        })
        .finally(() => {
            // Reset button state
            submitButton.innerHTML = originalText;
            submitButton.disabled = false;
            submitButton.classList.remove('btn-loading');
        });
    });
    
    // Add smooth scroll behavior for better UX
    document.documentElement.style.scrollBehavior = 'smooth';
    
    // Test if everything is working
    console.log('Contact form setup complete');
});
</script>
@endpush

