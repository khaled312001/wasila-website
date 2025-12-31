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
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top" id="navbar">
        <div class="container">
            <a class="navbar-brand" href="#home">
                <img src="{{ asset('images/logo-arabic.png') }}" alt="شعار وسيلة" height="60">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
                    </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#home">{{ __('messages.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">{{ __('messages.services') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#about">{{ __('messages.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">{{ __('messages.contact') }}</a>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3 ms-3">
                    @guest('customer')
                    <a href="{{ route('auth.google') }}" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>{{ __('messages.login') }}
                    </a>
                    @else
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-primary">
                        <i class="fas fa-user me-2"></i>{{ auth('customer')->user()->name }}
                    </a>
                    @endguest
                    <div class="btn-group">
                        <a href="{{ route('lang.switch', 'ar') }}" class="btn btn-sm {{ app()->getLocale() === 'ar' ? 'btn-primary' : 'btn-outline-primary' }}">عربي</a>
                        <a href="{{ route('lang.switch', 'en') }}" class="btn btn-sm {{ app()->getLocale() === 'en' ? 'btn-primary' : 'btn-outline-primary' }}">EN</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
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
                <a href="#services" class="btn btn-hero btn-hero-primary">
                    <i class="fas fa-list me-2"></i>{{ __('messages.browse_services') }}
                </a>
                <a href="#about" class="btn btn-hero btn-hero-secondary">
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

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="وسيلة" height="80" class="mb-3">
                    <p>{{ __('messages.social_charity_project_aim') }}</p>
                </div>
                <div class="footer-section">
                    <h3>{{ __('messages.quick_links_footer') }}</h3>
                    <a href="#home">{{ __('messages.home_link') }}</a><br>
                    <a href="#services">{{ __('messages.services_link') }}</a><br>
                    <a href="#about">{{ __('messages.about_link') }}</a><br>
                    <a href="#contact">{{ __('messages.contact_link') }}</a>
                </div>
                <div class="footer-section">
                    <h3>{{ __('messages.contact_information_footer') }}</h3>
                        <p>{{ __('messages.email_colon_footer') }} {{ \App\Helpers\SettingsHelper::contactEmail() }}</p>
                        <p>{{ __('messages.phone_colon_footer') }} {{ \App\Helpers\SettingsHelper::contactPhone() }}</p>
                        <p>{{ __('messages.address_colon_footer') }} {{ \App\Helpers\SettingsHelper::address() }}</p>
                    </div>
                </div>
            <div class="footer-bottom">
                <p>&copy; {{ date('Y') }} {{ __('messages.copyright_2025_wasila') }}</p>
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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

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
            const navLinks = document.querySelectorAll('.nav-link');
            
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
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });

        // Scroll to services
        function scrollToServices() {
            document.getElementById('services').scrollIntoView({ behavior: 'smooth' });
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

