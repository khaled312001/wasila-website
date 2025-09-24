@extends('layouts.app')

@section('title', __('messages.site_title'))

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
                {{ app()->getLocale() === 'ar' ? 'وسيلة - ' : 'Wasila - ' }}{{ __('messages.hero_subtitle') }}
            </h1>
            <p class="text-lg md:text-xl lg:text-2xl mb-8 text-gray-200 max-w-4xl mx-auto">
                {{ __('messages.hero_description') }}
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" class="btn-primary text-white px-8 py-3 rounded-lg font-semibold inline-block">
                    {{ __('messages.browse_services') }}
                </a>
                <a href="#about" class="btn-accent text-white px-8 py-3 rounded-lg font-semibold inline-block">
                    {{ __('messages.learn_more') }}
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="py-20 bg-gradient-to-br from-gray-50 via-white to-gray-50 relative overflow-hidden">
    <!-- Background Elements -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-20 left-10 w-72 h-72 bg-primary-light rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-40 right-10 w-72 h-72 bg-accent rounded-full mix-blend-multiply filter blur-xl animate-pulse" style="animation-delay: 2s;"></div>
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
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary-dark mb-6">
                {{ __('messages.premium_services') }}
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                {{ __('messages.premium_services_description') }}
            </p>
        </div>
        
        @if($services->count() > 0)
        <!-- Services Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            @foreach($services as $index => $service)
            <div class="group relative bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 overflow-hidden border border-gray-100">
                <!-- Card Badge -->
                <div class="absolute top-4 right-4 z-10">
                    <span class="bg-gradient-to-r from-accent to-primary-medium text-white text-xs font-semibold px-3 py-1 rounded-full shadow-lg">
                        {{ __('messages.premium') }}
                    </span>
                </div>
                
                <!-- Image Section -->
                <div class="relative overflow-hidden">
                    @if($service->image)
                    <img src="{{ asset('storage/' . $service->image) }}" 
                         alt="{{ $service->name }} - {{ __('messages.charity_service_from_wasila') }}" 
                         class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-500" 
                         loading="lazy">
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
                <div class="p-8">
                    <!-- Service Icon -->
                    <div class="w-12 h-12 bg-gradient-to-r from-primary-light to-primary-medium rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform duration-300">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    
                    <!-- Service Title -->
                    <h3 class="text-xl md:text-2xl font-bold text-primary-dark mb-3 group-hover:text-primary-medium transition-colors duration-300">
                        {{ $service->name }}
                    </h3>
                    
                    <!-- Service Description -->
                    <p class="text-base md:text-lg text-gray-600 mb-6 line-clamp-3 leading-relaxed">
                        {{ $service->description }}
                    </p>
                    
                    <!-- Price and Action -->
                    <div class="flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-2xl md:text-3xl font-bold text-accent mb-1">
                                {{ number_format($service->price, 2) }}
                            </span>
                            <span class="text-sm md:text-base text-gray-500">
                                {{ __('messages.saudi_riyal') }}
                            </span>
                        </div>
                        <a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" 
                           class="bg-gradient-to-r from-primary-medium to-primary-dark text-white px-6 py-3 rounded-xl font-semibold hover:from-primary-dark hover:to-accent transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">
                            {{ __('messages.order_now') }}
                        </a>
                    </div>
                </div>
                
                <!-- Hover Effect Border -->
                <div class="absolute inset-0 rounded-2xl border-2 border-transparent group-hover:border-primary-light transition-colors duration-300 pointer-events-none"></div>
            </div>
            @endforeach
        </div>
        
        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-gradient-to-r from-primary-light to-primary-medium rounded-2xl p-8 md:p-12 relative overflow-hidden">
                <div class="absolute inset-0 bg-white opacity-10"></div>
                <div class="relative z-10">
                    <h3 class="text-2xl md:text-3xl lg:text-4xl font-bold text-white mb-4">
                        {{ __('messages.discover_more_services') }}
                    </h3>
                    <p class="text-lg md:text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                        {{ __('messages.discover_more_description') }}
                    </p>
                    <a href="{{ app()->getLocale() === 'ar' ? route('services') : route('services.en') }}" 
                       class="inline-flex items-center bg-white text-primary-dark px-8 py-4 rounded-xl font-bold text-lg md:text-xl hover:bg-gray-100 transition-all duration-300 transform hover:scale-105 shadow-lg">
                        <span>{{ __('messages.view_all_services') }}</span>
                        <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
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
            <h3 class="text-2xl md:text-3xl font-bold text-primary-dark mb-4">
                {{ __('messages.services_coming_soon') }}
            </h3>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto">
                {{ __('messages.services_coming_soon_description') }}
            </p>
        </div>
        @endif
    </div>
</section>

<!-- About Section -->
<section id="about" class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-primary-dark mb-6">
                    {{ __('messages.about_title') }}
                </h2>
                <p class="text-base md:text-lg text-gray-600 mb-6">
                    {{ __('messages.about_description') }}
                </p>
                <p class="text-base md:text-lg text-gray-600 mb-8">
                    {{ __('messages.about_mission') }}
                </p>
                <div class="grid grid-cols-2 gap-6">
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary-medium mb-2">500+</div>
                        <div class="text-sm md:text-base text-gray-600">{{ __('messages.services_provided') }}</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl md:text-3xl font-bold text-primary-medium mb-2">1000+</div>
                        <div class="text-sm md:text-base text-gray-600">{{ __('messages.beneficiaries') }}</div>
                    </div>
                </div>
            </div>
            <div class="relative">
                <img src="{{ asset('images/1.png') }}" alt="{{ __('messages.wasila_charity_project_image') }}" class="rounded-lg shadow-lg" loading="lazy">
                <div class="absolute -bottom-6 -right-6 bg-accent text-white p-4 rounded-lg">
                    <div class="text-lg md:text-xl font-bold">{{ __('messages.working_for_community') }}</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
            <h2 class="text-2xl md:text-3xl lg:text-4xl font-bold text-primary-dark mb-4">
                {{ __('messages.why_choose_wasila') }}
            </h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-primary-light rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg md:text-xl font-semibold text-primary-dark mb-3">
                    {{ __('messages.diverse_services') }}
                </h3>
                <p class="text-sm md:text-base text-gray-600">
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
                    {{ __('messages.expert_team') }}
                </h3>
                <p class="text-sm md:text-base text-gray-600">
                    {{ __('messages.expert_team_description') }}
                </p>
            </div>
            
            <div class="text-center p-6">
                <div class="w-16 h-16 bg-accent rounded-full flex items-center justify-center mx-auto mb-4">
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
@include('components.contact-section')

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
            {{ __('messages.sending') }}
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

