<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
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
        title="{{ __('messages.site_title') }}"
        description="{{ __('messages.site_description') }}"
        keywords="{{ __('messages.site_keywords') }}"
        image="{{ asset('images/logo-arabic.png') }}"
        url="{{ url('/') }}"
        type="website"
        author="{{ __('messages.site_author') }}"
    />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Error Fixes Script -->
    <script src="{{ asset('js/error-fixes.js') }}"></script>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- AOS Animation Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/wasila.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wasila-header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/wasila-footer.css') }}">
    @php
        $cssFile = public_path('css/single-page.css');
        $cssContent = file_exists($cssFile) ? file_get_contents($cssFile) : '';
    @endphp
    @if($cssContent)
    <style>{!! $cssContent !!}</style>
    @else
    <link rel="stylesheet" href="{{ asset('css/single-page.css') }}">
    @endif
</head>
<body>
    <!-- Main Header -->
    <header class="wasila-header">
        <div class="wasila-header-container">
            <div class="wasila-header-logo">
                <a href="{{ url('/') }}#home" class="wasila-logo-link">
                    <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة الخيرية" class="wasila-logo-img">
                </a>
            </div>
            
            <nav class="wasila-header-nav">
                <ul class="wasila-nav-list">
                    <li class="wasila-nav-item">
                        <a href="{{ url('/') }}#home" class="wasila-nav-link">
                            <i class="wasila-nav-icon fas fa-home"></i>
                            <span>{{ __('messages.home') }}</span>
                        </a>
                    </li>
                    <li class="wasila-nav-item">
                        <a href="{{ url('/') }}#services" class="wasila-nav-link">
                            <i class="wasila-nav-icon fas fa-concierge-bell"></i>
                            <span>{{ __('messages.services') }}</span>
                        </a>
                    </li>
                    <li class="wasila-nav-item">
                        <a href="{{ url('/') }}#about" class="wasila-nav-link">
                            <i class="wasila-nav-icon fas fa-info-circle"></i>
                            <span>{{ __('messages.about') }}</span>
                        </a>
                    </li>
                    <li class="wasila-nav-item">
                        <a href="{{ url('/') }}#contact" class="wasila-nav-link">
                            <i class="wasila-nav-icon fas fa-envelope"></i>
                            <span>{{ __('messages.contact') }}</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="wasila-header-actions">
                @guest('customer')
                <a href="{{ route('auth.google') }}" class="wasila-btn wasila-btn-login">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>{{ __('messages.login') }}</span>
                </a>
                @else
                <a href="{{ route('customer.dashboard') }}" class="wasila-btn wasila-btn-user">
                    <i class="fas fa-user-circle"></i>
                    <span>{{ auth('customer')->user()->name }}</span>
                </a>
                @endguest
                
                <div class="wasila-lang-switcher">
                    <a href="{{ route('lang.switch', 'ar') }}" class="wasila-lang-btn {{ app()->getLocale() === 'ar' ? 'wasila-lang-active' : '' }}">
                        عربي
                    </a>
                    <a href="{{ route('lang.switch', 'en') }}" class="wasila-lang-btn {{ app()->getLocale() === 'en' ? 'wasila-lang-active' : '' }}">
                        EN
                    </a>
                </div>
                
                <button class="wasila-menu-toggle" id="wasilaMenuToggle">
                    <span class="wasila-menu-line"></span>
                    <span class="wasila-menu-line"></span>
                    <span class="wasila-menu-line"></span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="wasila-mobile-menu" id="wasilaMobileMenu">
            <ul class="wasila-mobile-nav-list">
                <li><a href="{{ url('/') }}#home"><i class="fas fa-home"></i> {{ __('messages.home') }}</a></li>
                <li><a href="{{ url('/') }}#services"><i class="fas fa-concierge-bell"></i> {{ __('messages.services') }}</a></li>
                <li><a href="{{ url('/') }}#about"><i class="fas fa-info-circle"></i> {{ __('messages.about') }}</a></li>
                <li><a href="{{ url('/') }}#contact"><i class="fas fa-envelope"></i> {{ __('messages.contact') }}</a></li>
                <li class="wasila-mobile-divider"></li>
                @guest('customer')
                <li><a href="{{ route('auth.google') }}"><i class="fas fa-sign-in-alt"></i> {{ __('messages.login') }}</a></li>
                @else
                <li><a href="{{ route('customer.dashboard') }}"><i class="fas fa-user-circle"></i> {{ auth('customer')->user()->name }}</a></li>
                @endguest
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section id="home" class="hero-section" style="margin-top: 70px;">
        <div class="hero-video-container">
                <iframe 
                    id="heroVideo"
                    src="https://www.youtube.com/embed/gPfaDls9eno?autoplay=1&mute=1&loop=1&playlist=gPfaDls9eno&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&fs=0&disablekb=1&start=0&end=60"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                allowfullscreen>
                </iframe>
            </div>
        <div class="hero-overlay"></div>
        <div class="hero-content" data-aos="fade-up">
            <h1 class="hero-title">
                            {{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'hero_title_ar' : 'hero_title_en', __('messages.hero_title') . ' - ' . __('messages.hero_subtitle')) }}
                </h1>
            <p class="hero-subtitle">
                    {{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'hero_description_ar' : 'hero_description_en', __('messages.hero_description')) }}
                </p>
            <div class="hero-buttons">
                <a href="{{ url('/') }}#services" class="btn btn-hero btn-hero-primary">
                    <i class="fas fa-list me-2"></i>{{ __('messages.browse_services') }}
                </a>
                <a href="{{ url('/') }}#about" class="btn btn-hero btn-hero-secondary">
                    <i class="fas fa-info-circle me-2"></i>{{ __('messages.learn_more') }}
                </a>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="section services-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">{{ __('messages.services_subtitle') }}</h2>
                <p class="section-subtitle">{{ __('messages.services_description') }}</p>
        </div>
            @if($services->count() > 0)
            <div class="row g-4">
                @foreach($services as $service)
                <div class="col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <a href="{{ route('orders.checkout') }}?service_id={{ $service->id }}&service_name={{ urlencode(app()->getLocale() === 'en' ? $service->name_en : $service->name_ar) }}&service_price={{ $service->price }}&service_description={{ urlencode(app()->getLocale() === 'en' ? $service->description_en : $service->description_ar) }}" class="text-decoration-none">
                        <div class="service-card">
                            <div class="service-image-wrapper" style="overflow: hidden; height: 250px;">
                        @if($service->image)
                                <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="service-image">
                        @else
                                <img src="{{ asset('images/' . (($loop->index % 12) + 1) . '.png') }}" alt="{{ $service->name }}" class="service-image">
                        @endif
                            </div>
                            <div class="service-body">
                                <h3 class="service-title">{{ app()->getLocale() === 'en' ? $service->name_en : $service->name_ar }}</h3>
                                <p class="service-description">{{ app()->getLocale() === 'en' ? $service->description_en : $service->description_ar }}</p>
                                <div class="service-price">{{ number_format($service->price, 2) }} {{ __('messages.currency') }}</div>
                                <button class="btn btn-service">{{ __('messages.order_now') }}</button>
                        </div>
                    </div>
                </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">{{ __('messages.no_services') }}</p>
            </div>
            @endif
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="section about-section">
        <div class="container">
            <div class="about-content">
                <div class="about-text" data-aos="fade-right">
                    <h2>{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'about_title_ar' : 'about_title_en', __('messages.about_title')) }}</h2>
                    <p>{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'about_description_ar' : 'about_description_en', __('messages.about_description')) }}</p>
                    <p>{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'about_mission_ar' : 'about_mission_en', __('messages.about_mission')) }}</p>
                    <div class="stats-grid">
                        <div class="stat-card" data-aos="zoom-in" data-aos-delay="200">
                            <div class="stat-number">{{ \App\Models\Setting::get('stat1_number', '500+') }}</div>
                            <div class="stat-label">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'stat1_label_ar' : 'stat1_label_en', __('messages.services_provided')) }}</div>
        </div>
                        <div class="stat-card" data-aos="zoom-in" data-aos-delay="400">
                            <div class="stat-number">{{ \App\Models\Setting::get('stat2_number', '1000+') }}</div>
                            <div class="stat-label">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'stat2_label_ar' : 'stat2_label_en', __('messages.beneficiaries')) }}</div>
                    </div>
                    </div>
                        </div>
                <div class="about-image" data-aos="fade-left">
                    <img src="{{ asset('images/39.png') }}" alt="عن وسيلة" class="img-fluid rounded-4 shadow-lg">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section features-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'why_choose_title_ar' : 'why_choose_title_en', __('messages.features_subtitle')) }}</h2>
                <p class="section-subtitle">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'why_choose_subtitle_ar' : 'why_choose_subtitle_en', __('messages.features_description')) }}</p>
        </div>
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-hands-helping"></i>
                </div>
                        <h3 class="feature-title">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature1_title_ar' : 'feature1_title_en', __('messages.diverse_services')) }}</h3>
                        <p class="feature-description">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature1_description_ar' : 'feature1_description_en', __('messages.diverse_services_description_extended')) }}</p>
            </div>
                        </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-users"></i>
                    </div>
                        <h3 class="feature-title">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature2_title_ar' : 'feature2_title_en', __('messages.specialized_team')) }}</h3>
                        <p class="feature-description">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature2_description_ar' : 'feature2_description_en', __('messages.specialized_team_description_extended')) }}</p>
                </div>
                        </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="fas fa-heart"></i>
                    </div>
                        <h3 class="feature-title">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature3_title_ar' : 'feature3_title_en', __('messages.positive_impact')) }}</h3>
                        <p class="feature-description">{{ \App\Models\Setting::get(app()->getLocale() === 'ar' ? 'feature3_description_ar' : 'feature3_description_en', __('messages.positive_impact_description_extended')) }}</p>
                </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="section gallery-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title">{{ __('messages.our_work') }}</h2>
                <p class="section-subtitle">{{ __('messages.discover_images_from_activities') }}</p>
        </div>
            @php
                    $portfolioItems = \App\Models\PortfolioItem::active()->ordered()->take(12)->get();
                @endphp
                @if($portfolioItems->count() > 0)
            <div class="gallery-grid">
                    @foreach($portfolioItems as $index => $item)
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="{{ $index * 50 }}" onclick="openLightbox({{ $index + 1 }}, '{{ $item->type }}', '{{ asset('storage/' . $item->file_path) }}', '{{ $item->title_ar }}')">
                            @if($item->type === 'image')
                    <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title_ar }}">
                            @else
                    <video muted>
                                    <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                                </video>
                            @endif
                            <div class="gallery-overlay">
                        <i class="fas fa-{{ $item->type === 'image' ? 'image' : 'play' }} fa-3x text-white"></i>
                                    </div>
                                </div>
                @endforeach
                            </div>
                                @else
            <div class="gallery-grid">
                    @for($i = 1; $i <= 12; $i++)
                <div class="gallery-item" data-aos="zoom-in" data-aos-delay="{{ $i * 50 }}" onclick="openLightbox({{ $i }}, 'image', '{{ asset('images/' . $i . '.png') }}', 'صورة {{ $i }}')">
                    <img src="{{ asset('images/' . $i . '.png') }}" alt="صورة {{ $i }}">
                            <div class="gallery-overlay">
                        <i class="fas fa-image fa-3x text-white"></i>
                        </div>
                    </div>
                    @endfor
            </div>
                @endif
        </div>
    </section>
    
    <!-- Contact Section -->
    <section id="contact" class="section contact-section">
        <div class="container">
            <div class="text-center mb-5" data-aos="fade-up">
                <h2 class="section-title text-white">{{ __('messages.contact_us') }}</h2>
                <p class="section-subtitle text-white">{{ __('messages.contact_us_description') }}</p>
                </div>
            <div class="row">
                <div class="col-lg-6 mb-4" data-aos="fade-right">
                    <div class="contact-container">
                        <h3 class="mb-4">{{ __('messages.contact_title') }}</h3>
                        <div class="contact-info">
                            <div class="contact-icon">
                                <i class="fas fa-envelope"></i>
                </div>
                        <div>
                                <h5>{{ __('messages.contact_email') }}</h5>
                                <p>{{ \App\Helpers\SettingsHelper::contactEmail() }}</p>
                                </div>
                                </div>
                        <div class="contact-info">
                            <div class="contact-icon">
                                <i class="fas fa-phone"></i>
                                </div>
                                <div>
                                <h5>{{ __('messages.phone_label') }}</h5>
                                <p>{{ \App\Helpers\SettingsHelper::contactPhone() }}</p>
                                </div>
                            </div>
                        <div class="contact-info">
                            <div class="contact-icon">
                                <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                <h5>{{ __('messages.address_label') }}</h5>
                                <p>{{ \App\Helpers\SettingsHelper::address() }}</p>
                                </div>
                            </div>
                        </div>
                            </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="contact-container">
                        <h3 class="mb-4">{{ __('messages.send_us_message_title') }}</h3>
                        <div id="contactSuccess" class="alert alert-success d-none">{{ __('messages.message_sent_success') }}</div>
                        <div id="contactError" class="alert alert-danger d-none">{{ __('messages.message_send_error') }}</div>
                        <form class="contact-form" id="contactForm" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <input type="text" name="name" placeholder="{{ __('messages.full_name_label') }}" required class="form-control">
                            <input type="email" name="email" placeholder="{{ __('messages.email_input_label') }}" required class="form-control">
                            <input type="tel" name="phone" placeholder="{{ __('messages.phone_number_label') }}" required class="form-control">
                            <input type="text" name="subject" placeholder="{{ __('messages.subject_optional_label') }}" class="form-control">
                            <textarea name="message" rows="5" placeholder="{{ __('messages.write_message_placeholder') }}" required class="form-control"></textarea>
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>{{ __('messages.send_message_button') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main Footer -->
    <footer class="wasila-footer">
        <div class="wasila-footer-container">
            <div class="wasila-footer-grid">
                <div class="wasila-footer-col wasila-footer-about">
                    <div class="wasila-footer-logo">
                        <img src="{{ asset('images/logo-footer.png') }}" alt="وسيلة الخيرية" class="wasila-footer-logo-img">
                    </div>
                    <p class="wasila-footer-desc">{{ __('messages.social_charity_project_aim') }}</p>
                    <div class="wasila-footer-social">
                        <a href="#" class="wasila-social-link" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="wasila-social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="wasila-social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="https://wa.me/966501234567" class="wasila-social-link" aria-label="WhatsApp">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                    </div>
                </div>
                
                <div class="wasila-footer-col wasila-footer-links">
                    <h4 class="wasila-footer-title">
                        <i class="fas fa-link wasila-footer-title-icon"></i>
                        {{ __('messages.quick_links_footer') }}
                    </h4>
                    <ul class="wasila-footer-list">
                        <li><a href="{{ url('/') }}#home"><i class="fas fa-chevron-left"></i> {{ __('messages.home_link') }}</a></li>
                        <li><a href="{{ url('/') }}#services"><i class="fas fa-chevron-left"></i> {{ __('messages.services_link') }}</a></li>
                        <li><a href="{{ url('/') }}#about"><i class="fas fa-chevron-left"></i> {{ __('messages.about_link') }}</a></li>
                        <li><a href="{{ url('/') }}#contact"><i class="fas fa-chevron-left"></i> {{ __('messages.contact_link') }}</a></li>
                    </ul>
                </div>
                
                <div class="wasila-footer-col wasila-footer-contact">
                    <h4 class="wasila-footer-title">
                        <i class="fas fa-address-card wasila-footer-title-icon"></i>
                        {{ __('messages.contact_information_footer') }}
                    </h4>
                    <ul class="wasila-footer-list">
                        <li class="wasila-footer-contact-item">
                            <i class="wasila-footer-contact-icon fas fa-envelope"></i>
                            <div>
                                <span class="wasila-footer-contact-label">{{ __('messages.email_colon_footer') }}</span>
                                <a href="mailto:{{ \App\Helpers\SettingsHelper::contactEmail() }}" class="wasila-footer-contact-value">{{ \App\Helpers\SettingsHelper::contactEmail() }}</a>
                            </div>
                        </li>
                        <li class="wasila-footer-contact-item">
                            <i class="wasila-footer-contact-icon fas fa-phone"></i>
                            <div>
                                <span class="wasila-footer-contact-label">{{ __('messages.phone_colon_footer') }}</span>
                                <a href="tel:{{ \App\Helpers\SettingsHelper::contactPhone() }}" class="wasila-footer-contact-value">{{ \App\Helpers\SettingsHelper::contactPhone() }}</a>
                            </div>
                        </li>
                        <li class="wasila-footer-contact-item">
                            <i class="wasila-footer-contact-icon fas fa-map-marker-alt"></i>
                            <div>
                                <span class="wasila-footer-contact-label">{{ __('messages.address_colon_footer') }}</span>
                                <span class="wasila-footer-contact-value">{{ \App\Helpers\SettingsHelper::address() }}</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="wasila-footer-bottom">
                <div class="wasila-footer-bottom-content">
                    <p class="wasila-footer-copyright">
                        <i class="fas fa-copyright"></i>
                        {{ date('Y') }} {{ __('messages.copyright_2025_wasila') }}
                    </p>
                    <p class="wasila-footer-made">
                        <i class="fas fa-heart"></i>
                        صنع بـ
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Floating Buttons -->
    <div class="floating-btn">
        <button onclick="scrollToServices()" class="btn btn-primary btn-lg rounded-pill shadow-lg">
            <i class="fas fa-shopping-cart me-2"></i>{{ __('messages.request_service_footer') }}
        </button>
    </div>

    <div class="whatsapp-btn">
        <a href="https://wa.me/966501234567?text={{ urlencode(__('messages.whatsapp_message', ['default' => 'مرحباً، أريد الاستفسار عن خدمات وسيلة الخيرية'])) }}" target="_blank" class="btn btn-success btn-lg rounded-pill shadow-lg">
            <i class="fab fa-whatsapp me-2"></i>{{ __('messages.whatsapp_footer') }}
        </a>
    </div>

    <!-- Lightbox -->
    <div class="lightbox" id="lightbox">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <div class="lightbox-content">
            <img id="lightbox-image" src="" alt="" class="img-fluid">
            <video id="lightbox-video" controls class="d-none">
                <source src="" type="video/mp4">
            </video>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        // Initialize AOS
        AOS.init({
            duration: 1000,
            once: true,
            offset: 100
        });

        // Wasila Header scroll effect
        window.addEventListener('scroll', function() {
            const header = document.querySelector('.wasila-header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
        
        // Mobile menu toggle
        const menuToggle = document.getElementById('wasilaMenuToggle');
        const mobileMenu = document.getElementById('wasilaMobileMenu');
        
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function() {
                menuToggle.classList.toggle('active');
                mobileMenu.classList.toggle('active');
            });
            
            // Close menu when clicking on a link
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    menuToggle.classList.remove('active');
                    mobileMenu.classList.remove('active');
                });
            });
        }

        // Smooth scroll
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                        e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Active nav link
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('section[id]');
            const navLinks = document.querySelectorAll('.wasila-nav-link');
            
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (window.pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });
            
            navLinks.forEach(link => {
                link.classList.remove('active');
                const href = link.getAttribute('href');
                if (href && href.includes('#' + current)) {
                    link.classList.add('active');
                }
            });
        });

        // Scroll to services
        function scrollToServices() {
            const servicesSection = document.getElementById('services');
            if (servicesSection) {
                servicesSection.scrollIntoView({ behavior: 'smooth' });
            } else {
                window.location.href = '{{ url("/") }}#services';
            }
        }

        // Lightbox functions
        function openLightbox(index, type, path, title) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const lightboxVideo = document.getElementById('lightbox-video');
            
            lightbox.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (type === 'image') {
                lightboxImage.src = path;
                lightboxImage.alt = title;
                lightboxImage.classList.remove('d-none');
                lightboxVideo.classList.add('d-none');
            } else {
                lightboxVideo.src = path;
                lightboxVideo.classList.remove('d-none');
                lightboxImage.classList.add('d-none');
            }
        }

        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        // Close lightbox on background click
        document.getElementById('lightbox').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLightbox();
            }
        });

        // Contact form
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = form.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>جاري الإرسال...';
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            fetch('{{ route("contact.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('contactSuccess').classList.remove('d-none');
                    document.getElementById('contactError').classList.add('d-none');
                    form.reset();
                } else {
                    throw new Error(data.message || 'حدث خطأ');
                }
            })
            .catch(error => {
                document.getElementById('contactError').classList.remove('d-none');
                document.getElementById('contactSuccess').classList.add('d-none');
            })
            .finally(() => {
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
            });
        });

        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.fade-in').forEach(el => {
            observer.observe(el);
        });
    </script>
</body>
</html>

