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
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    
    <!-- Error Fixes Script - Must be loaded first -->
    <script src="{{ asset('js/error-fixes.js') }}"></script>
    
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
        
        .smooth-scroll {
            scroll-behavior: smooth;
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
        
        /* Increase font sizes for section titles */
        h1 {
            font-size: 4rem !important;
            line-height: 1.2 !important;
        }
        
        @media (min-width: 768px) {
            h1 {
                font-size: 5rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h1 {
                font-size: 6rem !important;
            }
        }
        
        h2 {
            font-size: 3.5rem !important;
            line-height: 1.3 !important;
        }
        
        @media (min-width: 768px) {
            h2 {
                font-size: 4rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h2 {
                font-size: 4.5rem !important;
            }
        }
        
        h3 {
            font-size: 2.5rem !important;
            line-height: 1.3 !important;
        }
        
        @media (min-width: 768px) {
            h3 {
                font-size: 3rem !important;
            }
        }
        
        @media (min-width: 1024px) {
            h3 {
                font-size: 3.5rem !important;
            }
        }
        
        p {
            font-size: 1.5rem !important;
            line-height: 1.6 !important;
        }
        
        @media (min-width: 768px) {
            p {
                font-size: 1.75rem !important;
            }
        }
        
        .section-padding {
            padding: 80px 0;
        }
        
        .floating-btn {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 1000;
            animation: float 3s ease-in-out infinite;
        }
        
        .whatsapp-btn {
            position: fixed;
            bottom: 30px;
            left: 30px;
            z-index: 1000;
            animation: pulse 2s infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        
        .order-modal {
            backdrop-filter: blur(5px);
        }
        
        /* Advanced Animations */
        .animate-fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        
        .animate-slide-in-left {
            animation: slideInLeft 1s ease-out 0.3s both;
        }
        
        .animate-slide-in-right {
            animation: slideInRight 1s ease-out 0.6s both;
        }
        
        .animate-fade-in-delay {
            animation: fadeInUp 1s ease-out 0.9s both;
        }
        
        .animate-bounce-in {
            animation: bounceIn 1s ease-out 1.2s both;
        }
        
        .animate-float-in {
            animation: floatIn 0.8s ease-out;
        }
        
        .animate-scale-in {
            animation: scaleIn 0.6s ease-out;
        }
        
        .animate-slide-up {
            animation: slideUp 0.8s ease-out;
        }
        
        .animate-rotate-in {
            animation: rotateIn 1s ease-out;
        }
        
        .animate-flip-in {
            animation: flipIn 0.8s ease-out;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }
            50% {
                opacity: 1;
                transform: scale(1.05);
            }
            70% {
                transform: scale(0.9);
            }
            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes floatIn {
            from {
                opacity: 0;
                transform: translateY(20px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes rotateIn {
            from {
                opacity: 0;
                transform: rotate(-180deg) scale(0.8);
            }
            to {
                opacity: 1;
                transform: rotate(0deg) scale(1);
            }
        }
        
        @keyframes flipIn {
            from {
                opacity: 0;
                transform: perspective(400px) rotateY(90deg);
            }
            to {
                opacity: 1;
                transform: perspective(400px) rotateY(0deg);
            }
        }
        
        /* Hover Effects */
        .hover-lift {
            transition: all 0.3s ease;
        }
        
        .hover-lift:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        
        .hover-glow {
            transition: all 0.3s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 30px rgba(8, 120, 139, 0.5);
        }
        
        .hover-rotate {
            transition: all 0.3s ease;
        }
        
        .hover-rotate:hover {
            transform: rotate(5deg) scale(1.05);
        }
        
        /* Video Background Styles */
        .video-background {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translateX(-50%) translateY(-50%);
            z-index: -1;
        }
        
        .video-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }
        
        .video-container iframe {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            transform: translateX(-50%) translateY(-50%) scale(1.1);
            z-index: 1;
        }
        
        /* Mobile Optimizations */
        @media (max-width: 768px) {
            .floating-btn, .whatsapp-btn {
                bottom: 20px;
                right: 20px;
                left: 20px;
            }
            
            .whatsapp-btn {
                right: auto;
                left: 20px;
            }
            
            .video-container iframe {
                transform: translateX(-50%) translateY(-50%) scale(1.2);
            }
        }
        
        @media (max-width: 480px) {
            .video-container iframe {
                transform: translateX(-50%) translateY(-50%) scale(1.3);
            }
        }
        
        /* Image Slideshow Fallback */
        .slideshow-container {
            position: relative;
            width: 100%;
            height: 100%;
        }
        
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            opacity: 0;
            transition: opacity 3s ease-in-out;
        }
        
        .slide.active {
            opacity: 1;
        }
        
        .slide-1 { background-image: url('{{ asset('images/1.png') }}'); }
        .slide-2 { background-image: url('{{ asset('images/2.png') }}'); }
        .slide-3 { background-image: url('{{ asset('images/3.png') }}'); }
        .slide-4 { background-image: url('{{ asset('images/4.png') }}'); }
        .slide-5 { background-image: url('{{ asset('images/5.png') }}'); }
        
        /* Enhanced Navigation Styles */
        .nav-link:hover .nav-link-underline {
            width: 100%;
        }
        
        .hamburger {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
        }
        
        .hamburger-line {
            display: block;
            height: 2px;
            width: 100%;
            background-color: currentColor;
            border-radius: 1px;
            transition: all 0.3s ease;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }
        
        /* Enhanced Card Styles */
        .service-card {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(8, 120, 139, 0.1);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .service-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(8, 120, 139, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .service-card:hover::before {
            left: 100%;
        }
        
        .service-card:hover {
            transform: translateY(-10px) scale(1.02);
            box-shadow: 0 25px 50px rgba(8, 120, 139, 0.2);
            border-color: rgba(8, 120, 139, 0.3);
        }
        
        .service-card-image {
            position: relative;
            overflow: hidden;
        }
        
        .service-card-image img {
            transition: transform 0.6s ease;
        }
        
        .service-card:hover .service-card-image img {
            transform: scale(1.1);
        }
        
        .service-card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, rgba(8, 120, 139, 0.8), rgba(223, 163, 64, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .service-card:hover .service-card-overlay {
            opacity: 1;
        }
        
        /* Enhanced Gallery Styles */
        .gallery-item {
            position: relative;
            overflow: hidden;
            border-radius: 12px;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            cursor: pointer;
        }
        
        .gallery-item::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(45deg, rgba(8, 120, 139, 0.8), rgba(223, 163, 64, 0.8));
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 2;
        }
        
        .gallery-item:hover::before {
            opacity: 1;
        }
        
        .gallery-item img {
            transition: transform 0.6s ease;
        }
        
        .gallery-item:hover img {
            transform: scale(1.15);
        }
        
        .gallery-overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 3;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay {
            opacity: 1;
        }
        
        .gallery-overlay svg {
            transform: scale(0.8);
            transition: transform 0.3s ease;
        }
        
        .gallery-item:hover .gallery-overlay svg {
            transform: scale(1);
        }
        
        /* Enhanced Lightbox Styles */
        #lightbox {
            backdrop-filter: blur(10px);
            animation: fadeIn 0.3s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        #lightbox-image {
            animation: zoomIn 0.3s ease-out;
        }
        
        @keyframes zoomIn {
            from { 
                opacity: 0;
                transform: scale(0.8);
            }
            to { 
                opacity: 1;
                transform: scale(1);
            }
        }
        
        .thumbnail-btn {
            transition: all 0.3s ease;
            opacity: 0.7;
        }
        
        .thumbnail-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }
        
        .thumbnail-btn.border-white {
            opacity: 1;
            transform: scale(1.05);
        }
        
        /* Navigation buttons hover effects */
        #lightbox button {
            transition: all 0.3s ease;
        }
        
        #lightbox button:hover {
            transform: scale(1.1);
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            #lightbox .absolute.left-6,
            #lightbox .absolute.right-6 {
                left: 1rem;
                right: 1rem;
            }
            
            .thumbnail-btn {
                width: 3rem;
                height: 3rem;
            }
            
            #lightbox .absolute.bottom-6 {
                bottom: 1rem;
            }
        }
        
        /* Advanced Animations */
        .animate-slide-in-up-delayed {
            animation: slideInUp 0.8s ease-out;
        }
        
        .animate-fade-in-scale {
            animation: fadeInScale 0.6s ease-out;
        }
        
        .animate-rotate-in-delayed {
            animation: rotateIn 1s ease-out 0.3s both;
        }
        
        .animate-bounce-in-delayed {
            animation: bounceIn 0.8s ease-out 0.6s both;
        }
        
        .animate-slide-in-left-delayed {
            animation: slideInLeft 0.8s ease-out 0.9s both;
        }
        
        .animate-slide-in-right-delayed {
            animation: slideInRight 0.8s ease-out 1.2s both;
        }
        
        /* Staggered Animation */
        .stagger-animation > * {
            opacity: 0;
            transform: translateY(30px);
            animation: staggerFadeIn 0.6s ease-out forwards;
        }
        
        .stagger-animation > *:nth-child(1) { animation-delay: 0.1s; }
        .stagger-animation > *:nth-child(2) { animation-delay: 0.2s; }
        .stagger-animation > *:nth-child(3) { animation-delay: 0.3s; }
        .stagger-animation > *:nth-child(4) { animation-delay: 0.4s; }
        .stagger-animation > *:nth-child(5) { animation-delay: 0.5s; }
        .stagger-animation > *:nth-child(6) { animation-delay: 0.6s; }
        
        @keyframes staggerFadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Parallax Effect */
        .parallax-element {
            transform: translateZ(0);
            will-change: transform;
        }
        
        /* Loading Animation */
        .loading-shimmer {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }
        
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        /* Enhanced Feature Cards */
        .feature-card {
            position: relative;
            overflow: hidden;
        }
        
        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(8, 120, 139, 0.1), transparent);
            transition: left 0.6s;
        }
        
        .feature-card:hover::before {
            left: 100%;
        }
        
        /* Enhanced Button Styles */
        .btn-enhanced {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-enhanced::after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }
        
        .btn-enhanced:hover::after {
            width: 300px;
            height: 300px;
        }
        
        /* New Modern Button Effects */
        .modern-btn {
            position: relative;
            overflow: hidden;
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 50%, var(--accent) 100%);
            border: 2px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            box-shadow: 
                0 8px 32px rgba(8, 120, 139, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .modern-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.6s;
        }
        
        .modern-btn:hover::before {
            left: 100%;
        }
        
        .modern-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 
                0 20px 40px rgba(8, 120, 139, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
        }
        
        .modern-btn:active {
            transform: translateY(-1px) scale(1.02);
        }
        
        /* Glowing Text Effect */
        .glow-text {
            text-shadow: 
                0 0 10px rgba(60, 166, 180, 0.5),
                0 0 20px rgba(60, 166, 180, 0.3),
                0 0 30px rgba(60, 166, 180, 0.2);
            animation: glow 2s ease-in-out infinite alternate;
        }
        
        @keyframes glow {
            from {
                text-shadow: 
                    0 0 10px rgba(60, 166, 180, 0.5),
                    0 0 20px rgba(60, 166, 180, 0.3),
                    0 0 30px rgba(60, 166, 180, 0.2);
            }
            to {
                text-shadow: 
                    0 0 20px rgba(60, 166, 180, 0.8),
                    0 0 30px rgba(60, 166, 180, 0.5),
                    0 0 40px rgba(60, 166, 180, 0.3);
            }
        }
        
        /* Enhanced Scroll Animations */
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        
        /* Enhanced Hover Effects */
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(8, 120, 139, 0.15);
        }
        
        .hover-glow {
            transition: all 0.3s ease;
        }
        
        .hover-glow:hover {
            box-shadow: 0 0 20px rgba(8, 120, 139, 0.3);
        }
        
        /* Enhanced Text Effects */
        .text-glow {
            text-shadow: 0 0 10px rgba(8, 120, 139, 0.3);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Enhanced Mobile Responsiveness */
        @media (max-width: 768px) {
            .feature-card {
                margin-bottom: 1rem;
            }
            
            .service-card {
                margin-bottom: 1rem;
            }
            
            .gallery-item {
                margin-bottom: 0.5rem;
            }
            
            .hamburger {
                width: 20px;
                height: 16px;
            }
            
            .nav-link {
                font-size: 0.9rem;
            }
        }
        
        @media (max-width: 480px) {
            .feature-card {
                padding: 1.5rem;
            }
            
            .service-card {
                padding: 1rem;
            }
            
            .gallery-item img {
                height: 120px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 smooth-scroll">
    <!-- Enhanced Navigation -->
    <nav class="bg-white/95 backdrop-blur-md shadow-lg sticky top-0 z-50 transition-all duration-300" id="navbar">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Enhanced Logo -->
                <div class="flex items-center">
                    <a href="#home" class="flex items-center group">
                        <div class="relative">
                            <img src="{{ asset('images/logo-arabic.png') }}" alt="شعار وسيلة الخيرية" class="h-12 w-auto transition-transform duration-300 group-hover:scale-110">
                            <div class="absolute inset-0 bg-primary-light opacity-0 group-hover:opacity-20 rounded-full transition-opacity duration-300"></div>
                        </div>
                        <span class="mr-3 text-xl font-bold text-primary-dark group-hover:text-primary-medium transition-colors duration-300">وسيلة</span>
                    </a>
                </div>
                
                <!-- Enhanced Navigation Links -->
                <div class="hidden md:flex items-center space-x-8 space-x-reverse">
                    <a href="#home" class="nav-link relative text-gray-700 hover:text-primary-medium transition-all duration-300 font-medium">
                        <span class="relative z-10">{{ __('messages.home') }}</span>
                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-medium transition-all duration-300 nav-link-underline"></div>
                    </a>
                    <a href="#services" class="nav-link relative text-gray-700 hover:text-primary-medium transition-all duration-300 font-medium">
                        <span class="relative z-10">{{ __('messages.services') }}</span>
                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-medium transition-all duration-300 nav-link-underline"></div>
                    </a>
                    <a href="#about" class="nav-link relative text-gray-700 hover:text-primary-medium transition-all duration-300 font-medium">
                        <span class="relative z-10">{{ __('messages.about') }}</span>
                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-medium transition-all duration-300 nav-link-underline"></div>
                    </a>
                    <a href="#contact" class="nav-link relative text-gray-700 hover:text-primary-medium transition-all duration-300 font-medium">
                        <span class="relative z-10">اتصل بنا</span>
                        <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-primary-medium transition-all duration-300 nav-link-underline"></div>
                    </a>
                </div>
                
                <!-- Enhanced Language Switcher -->
                <div class="flex items-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                    <div class="flex items-center bg-gray-100 rounded-full p-1">
                        <a href="{{ route('lang.switch', 'ar') }}" 
                           class="text-sm px-3 py-1 rounded-full transition-all duration-300 {{ app()->getLocale() === 'ar' ? 'text-primary-medium font-semibold bg-white shadow-sm' : 'text-gray-500 hover:bg-white hover:text-primary-medium' }}"
                           onclick="switchLanguage('ar', event)">
                            العربية
                        </a>
                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="text-sm px-3 py-1 rounded-full transition-all duration-300 {{ app()->getLocale() === 'en' ? 'text-primary-medium font-semibold bg-white shadow-sm' : 'text-gray-500 hover:bg-white hover:text-primary-medium' }}"
                           onclick="switchLanguage('en', event)">
                            English
                        </a>
                    </div>
                </div>
                
                <!-- Enhanced Mobile menu button -->
                <div class="md:hidden">
                    <button type="button" class="relative w-8 h-8 flex items-center justify-center text-gray-700 hover:text-primary-medium focus:outline-none transition-all duration-300" onclick="toggleMobileMenu()">
                        <span class="sr-only">فتح القائمة</span>
                        <div class="hamburger">
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                            <span class="hamburger-line"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Enhanced Mobile Menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white/95 backdrop-blur-md border-t border-gray-200 shadow-lg">
            <div class="px-4 pt-4 pb-6 space-y-2">
                <a href="#home" class="block px-4 py-3 text-gray-700 hover:text-primary-medium hover:bg-primary-light/10 rounded-lg transition-all duration-300 font-medium">{{ __('messages.home') }}</a>
                <a href="#services" class="block px-4 py-3 text-gray-700 hover:text-primary-medium hover:bg-primary-light/10 rounded-lg transition-all duration-300 font-medium">{{ __('messages.services') }}</a>
                <a href="#about" class="block px-4 py-3 text-gray-700 hover:text-primary-medium hover:bg-primary-light/10 rounded-lg transition-all duration-300 font-medium">{{ __('messages.about') }}</a>
                <a href="#contact" class="block px-4 py-3 text-gray-700 hover:text-primary-medium hover:bg-primary-light/10 rounded-lg transition-all duration-300 font-medium">{{ __('messages.contact') }}</a>
                
                <!-- Mobile Language Switcher -->
                <div class="border-t border-gray-200 pt-4 mt-4">
                    <div class="flex items-center justify-center space-x-4 {{ app()->getLocale() === 'ar' ? 'space-x-reverse' : '' }}">
                        <a href="{{ route('lang.switch', 'ar') }}" 
                           class="text-sm px-4 py-2 rounded-lg transition-all duration-300 {{ app()->getLocale() === 'ar' ? 'text-primary-medium font-semibold bg-primary-light/10' : 'text-gray-500 hover:bg-primary-light/10 hover:text-primary-medium' }}"
                           onclick="switchLanguage('ar', event)">
                            {{ __('messages.arabic') }}
                        </a>
                        <span class="text-gray-300">|</span>
                        <a href="{{ route('lang.switch', 'en') }}" 
                           class="text-sm px-4 py-2 rounded-lg transition-all duration-300 {{ app()->getLocale() === 'en' ? 'text-primary-medium font-semibold bg-primary-light/10' : 'text-gray-500 hover:bg-primary-light/10 hover:text-primary-medium' }}"
                           onclick="switchLanguage('en', event)">
                            {{ __('messages.english') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Video Background -->
    <section id="home" class="relative h-screen flex items-center justify-center overflow-hidden">
        <!-- Video Background -->
        <div class="absolute inset-0 z-0">
            <!-- YouTube Video as Background -->
            <div class="video-container">
                <iframe 
                    id="heroVideo"
                    src="https://www.youtube.com/embed/gPfaDls9eno?autoplay=1&mute=1&loop=1&playlist=gPfaDls9eno&controls=0&showinfo=0&rel=0&modestbranding=1&iv_load_policy=3&fs=0&disablekb=1&start=0&end=60"
                    frameborder="0"
                    allow="autoplay; encrypted-media"
                    allowfullscreen
                    onload="checkVideoLoad()"
                    onerror="showSlideshow()">
                </iframe>
            </div>
            
            <!-- Slideshow Fallback -->
            <div id="slideshow" class="slideshow-container" style="display: none;">
                <div class="slide slide-1 active"></div>
                <div class="slide slide-2"></div>
                <div class="slide slide-3"></div>
                <div class="slide slide-4"></div>
                <div class="slide slide-5"></div>
            </div>
            
            <!-- Overlay -->
            <div class="absolute inset-0 bg-black bg-opacity-60 z-10"></div>
        </div>
        
        <!-- Content -->
        <div class="relative z-10 text-center text-white max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="animate-fade-in-up">
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-6 animate-slide-in-left">
                    <span class="relative inline-block">
                        <span class="relative z-10 text-transparent bg-clip-text bg-gradient-to-r from-white via-primary-light to-accent font-black text-6xl md:text-7xl lg:text-8xl glow-text">
                            {{ __('messages.hero_title') }}
                        </span>
                        <span class="absolute inset-0 bg-gradient-to-r from-white/20 via-primary-light/20 to-accent/20 blur-xl transform scale-110"></span>
                    </span>
                    <br>
                    <span class="text-3xl md:text-4xl lg:text-5xl font-light animate-slide-in-right">{{ __('messages.hero_subtitle') }}</span>
                </h1>
                <p class="text-2xl md:text-3xl lg:text-4xl mb-8 text-gray-200 max-w-5xl mx-auto animate-fade-in-delay">
                    {{ __('messages.hero_description') }}
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center animate-bounce-in">
                    <a href="#services" class="btn-primary text-white px-8 py-4 rounded-lg font-semibold inline-block transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('messages.browse_services') }}
                        </span>
                    </a>
                    <a href="#about" class="btn-accent text-white px-8 py-4 rounded-lg font-semibold inline-block transform hover:scale-105 transition-all duration-300 shadow-lg hover:shadow-xl">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            تعرف علينا
                        </span>
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Enhanced Services Section -->
    <section id="services" class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-0 w-96 h-96 bg-primary-light rounded-full -translate-x-48 -translate-y-48"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-accent rounded-full translate-x-48 translate-y-48"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Enhanced Header -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-primary-light/10 text-primary-medium">
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('messages.services_title') }}
                    </span>
                </div>
                <h2 class="text-5xl md:text-6xl lg:text-7xl font-bold text-primary-dark mb-6 animate-slide-in-left">
                    <span class="relative inline-block">
                        <span class="relative z-10 bg-gradient-to-r from-primary-light via-primary-medium to-accent bg-clip-text text-transparent font-black text-6xl md:text-7xl lg:text-8xl glow-text">
                            {{ __('messages.services_subtitle') }}
                        </span>
                        <span class="absolute inset-0 bg-gradient-to-r from-primary-light/20 via-primary-medium/20 to-accent/20 blur-xl transform scale-110"></span>
                    </span>
                </h2>
                <p class="text-2xl md:text-3xl text-gray-600 max-w-4xl mx-auto animate-slide-in-right leading-relaxed">
                    {{ __('messages.services_description') }}
                </p>
            </div>
            
            @if($services->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 stagger-animation">
                @foreach($services as $service)
                <div class="service-card rounded-2xl shadow-lg overflow-hidden group">
                    <!-- Enhanced Image Section -->
                    <div class="service-card-image relative h-56">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name_ar }} - خدمة خيرية من وسيلة" class="w-full h-full object-cover" loading="lazy">
                        @else
                        <div class="w-full h-full bg-gradient-to-br from-primary-light to-primary-medium flex items-center justify-center relative">
                            <img src="{{ asset('images/' . (($loop->index % 12) + 1) . '.png') }}" alt="{{ $service->name_ar }} - صورة توضيحية" class="w-full h-full object-cover opacity-40" loading="lazy">
                            <div class="absolute inset-0 flex items-center justify-center">
                                <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center backdrop-blur-sm">
                                    <svg class="w-10 h-10 text-white drop-shadow-lg" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Enhanced Overlay -->
                        <div class="service-card-overlay">
                            <div class="text-center text-white">
                                <svg class="w-12 h-12 mx-auto mb-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                                </svg>
                                <p class="text-sm font-medium">اضغط للطلب</p>
                            </div>
                        </div>
                        
                        <!-- Price Badge -->
                        <div class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full px-3 py-1 shadow-lg">
                            <span class="text-sm font-bold text-primary-dark">{{ number_format($service->price, 2) }} {{ __('messages.currency') }}</span>
                        </div>
                    </div>
                    
                    <!-- Enhanced Content Section -->
                    <div class="p-6">
                        <div class="mb-4">
                            <h3 class="text-xl font-bold text-primary-dark mb-3 group-hover:text-primary-medium transition-colors duration-300">
                                {{ $service->name_ar }}
                            </h3>
                            <p class="text-gray-600 leading-relaxed line-clamp-3">
                                {{ $service->description_ar }}
                            </p>
                        </div>
                        
                        <!-- Enhanced Action Button -->
                        <a href="{{ route('orders.checkout') }}?service_id={{ $service->id }}&service_name={{ urlencode($service->name_ar) }}&service_price={{ $service->price }}&service_description={{ urlencode($service->description_ar) }}" 
                           class="w-full modern-btn text-white px-6 py-4 rounded-2xl font-bold text-lg flex items-center justify-center group">
                            <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:rotate-12" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                            </svg>
                            <span>{{ __('messages.order_now') }}</span>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-16">
                <div class="inline-block p-8 bg-white rounded-2xl shadow-lg">
                    <div class="text-gray-400 mb-6 animate-rotate-in">
                        <svg class="w-20 h-20 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">{{ __('messages.no_services') }}</h3>
                    <p class="text-gray-500">{{ __('messages.no_services_description') }}</p>
                </div>
            </div>
            @endif
        </div>
    </section>

    <!-- Enhanced About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-1/3 right-0 w-96 h-96 bg-primary-light rounded-full translate-x-48"></div>
            <div class="absolute bottom-1/3 left-0 w-96 h-96 bg-accent rounded-full -translate-x-48"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- Enhanced Content -->
                <div class="order-2 lg:order-1">
                    <div class="inline-block mb-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-primary-light/10 text-primary-medium">
                            <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            {{ __('messages.about_title') }}
                        </span>
                    </div>
                    
                    <h2 class="text-5xl md:text-6xl lg:text-7xl font-bold text-primary-dark mb-8">
                        <span class="relative inline-block">
                            <span class="relative z-10 bg-gradient-to-r from-primary-light via-primary-medium to-accent bg-clip-text text-transparent font-black text-6xl md:text-7xl lg:text-8xl glow-text">
                                {{ __('messages.about_title') }}
                            </span>
                            <span class="absolute inset-0 bg-gradient-to-r from-primary-light/20 via-primary-medium/20 to-accent/20 blur-xl transform scale-110"></span>
                        </span>
                    </h2>
                    
                    <div class="space-y-6">
                        <p class="text-2xl md:text-3xl text-gray-600 leading-relaxed">
                            {{ __('messages.about_description') }}
                        </p>
                        
                        <p class="text-xl md:text-2xl text-gray-600 leading-relaxed">
                            {{ __('messages.about_mission') }}
                        </p>
                    </div>
                    
                    <!-- Enhanced Stats -->
                    <div class="grid grid-cols-2 gap-6 my-8">
                        <div class="text-center p-6 bg-white rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300">
                            <div class="text-5xl md:text-6xl font-bold text-primary-medium mb-2">500+</div>
                            <div class="text-lg md:text-xl text-gray-600 font-medium">{{ __('messages.services_provided') }}</div>
                        </div>
                        <div class="text-center p-6 bg-white rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300">
                            <div class="text-5xl md:text-6xl font-bold text-accent mb-2">1000+</div>
                            <div class="text-lg md:text-xl text-gray-600 font-medium">{{ __('messages.beneficiaries') }}</div>
                        </div>
                    </div>
                    
                    <!-- Enhanced Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="#services" class="modern-btn text-white px-10 py-5 rounded-2xl font-bold text-xl text-center flex items-center justify-center group">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ __('messages.our_services') }}
                        </a>
                        <a href="#contact" class="modern-btn text-white px-10 py-5 rounded-2xl font-bold text-xl text-center flex items-center justify-center group" style="background: linear-gradient(135deg, var(--accent) 0%, #fbbf24 50%, #f59e0b 100%);">
                            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                            </svg>
                            {{ __('messages.contact_us') }}
                        </a>
                    </div>
                </div>
                
                <!-- Enhanced Image Section -->
                <div class="order-1 lg:order-2 relative">
                    <div class="relative group">
                        <!-- Main Image -->
                        <div class="relative overflow-hidden rounded-2xl shadow-2xl">
                            <img src="{{ asset('images/39.png') }}" alt="صورة توضيحية لمشروع وسيلة الخيري" class="w-full h-96 object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                        
                        <!-- Floating Cards -->
                        <div class="absolute -top-6 -right-6 bg-white rounded-xl shadow-lg p-4 transform rotate-6 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-primary-light rounded-full flex items-center justify-center ml-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-primary-dark">خدمات متميزة</div>
                                    <div class="text-xs text-gray-500">للمجتمع</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-xl shadow-lg p-4 transform -rotate-6 hover:rotate-0 transition-transform duration-300">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-accent rounded-full flex items-center justify-center ml-3">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-primary-dark">فريق متخصص</div>
                                    <div class="text-xs text-gray-500">خبرة عالية</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Features Section -->
    <section class="py-20 bg-gradient-to-br from-white to-gray-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/2 w-96 h-96 bg-primary-light rounded-full -translate-x-48 -translate-y-48"></div>
            <div class="absolute bottom-0 right-1/2 w-96 h-96 bg-accent rounded-full translate-x-48 translate-y-48"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-6 sm:px-6 lg:px-8 relative">
            <!-- Enhanced Header -->
            <div class="text-center mb-12">
                <div class="inline-block mb-3">
                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium bg-accent/10 text-accent animate-pulse">
                        <svg class="w-3 h-3 ml-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        مميزاتنا
                    </span>
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-primary-dark mb-4 animate-fade-in-up">
                        لماذا تختار وسيلة؟
                </h2>
                <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed animate-fade-in-up animation-delay-200">
                    نتميز بتقديم خدمات خيرية متميزة مع ضمان الجودة والشفافية في جميع أعمالنا
                </p>
            </div>
            
            <!-- Enhanced Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="feature-card group text-center p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-3 hover:scale-105 animate-fade-in-up animation-delay-300 border border-white/20">
                    <div class="relative mb-4">
                      
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-accent rounded-full flex items-center justify-center animate-bounce">
                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary-dark mb-3 group-hover:text-primary-medium transition-colors duration-300">خدمات متنوعة</h3>
                    <p class="text-sm md:text-base text-gray-600 leading-relaxed">نقدم مجموعة واسعة من الخدمات الخيرية والاجتماعية مع ضمان الجودة العالية والكفاءة في التنفيذ</p>
                </div>
                
                <!-- Card 2 -->
                <div class="feature-card group text-center p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-3 hover:scale-105 animate-fade-in-up animation-delay-500 border border-white/20">
                    <div class="relative mb-4">
                       
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-accent rounded-full flex items-center justify-center animate-bounce animation-delay-100">
                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary-dark mb-3 group-hover:text-primary-medium transition-colors duration-300">فريق متخصص</h3>
                    <p class="text-sm md:text-base text-gray-600 leading-relaxed">فريق من المتخصصين في العمل الخيري والاجتماعي مع خبرة واسعة في مجال خدمة المجتمع</p>
                </div>
                
                <!-- Card 3 -->
                <div class="feature-card group text-center p-6 bg-white/80 backdrop-blur-sm rounded-xl shadow-lg hover:shadow-2xl transition-all duration-700 transform hover:-translate-y-3 hover:scale-105 animate-fade-in-up animation-delay-700 border border-white/20">
                    <div class="relative mb-4">
                       
                        <div class="absolute -top-1 -right-1 w-5 h-5 bg-primary-medium rounded-full flex items-center justify-center animate-bounce animation-delay-200">
                            <svg class="w-2.5 h-2.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-bold text-primary-dark mb-3 group-hover:text-primary-medium transition-colors duration-300">تأثير إيجابي</h3>
                    <p class="text-sm md:text-base text-gray-600 leading-relaxed">نساهم في إحداث تأثير إيجابي في المجتمع مع تقديم تقارير دورية عن المشاريع والأنشطة</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Enhanced Gallery Section -->
    <section class="py-20 bg-gradient-to-br from-white to-gray-50 relative overflow-hidden">
        <!-- Background Elements -->
        <div class="absolute inset-0 opacity-5">
            <div class="absolute top-1/4 left-0 w-72 h-72 bg-primary-light rounded-full -translate-x-36"></div>
            <div class="absolute bottom-1/4 right-0 w-72 h-72 bg-accent rounded-full translate-x-36"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Enhanced Header -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-accent/10 text-accent">
                        <svg class="w-4 h-4 ml-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
                        </svg>
                        معرض الصور
                    </span>
                </div>
                <h2 class="text-4xl md:text-5xl font-bold text-primary-dark mb-6">
                    <span class="relative inline-block">
                        <span class="relative z-10 bg-gradient-to-r from-primary-light via-primary-medium to-accent bg-clip-text text-transparent font-black text-5xl md:text-6xl lg:text-7xl glow-text">
                            معرض الصور
                        </span>
                        <span class="absolute inset-0 bg-gradient-to-r from-primary-light/20 via-primary-medium/20 to-accent/20 blur-xl transform scale-110"></span>
                    </span>
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                    اكتشف صوراً من أنشطتنا الخيرية وخدماتنا المتنوعة التي نقدمها للمجتمع
                </p>
            </div>
            
            <!-- Enhanced Gallery Grid -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-6">
                @for($i = 1; $i <= 12; $i++)
                <div class="gallery-item group cursor-pointer" onclick="openLightbox({{ $i }})">
                    <div class="relative overflow-hidden rounded-2xl shadow-lg">
                        <img src="{{ asset('images/' . $i . '.png') }}" alt="صورة توضيحية {{ $i }} لمشروع وسيلة الخيري" 
                             class="w-full h-40 object-cover transition-transform duration-500">
                        
                        <!-- Enhanced Overlay -->
                        <div class="gallery-overlay">
                            <div class="text-center text-white">
                                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-2 backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium">عرض الصورة</p>
                            </div>
                        </div>
                        
                        <!-- Image Number Badge -->
                        <div class="absolute top-3 right-3 bg-black/50 backdrop-blur-sm rounded-full w-8 h-8 flex items-center justify-center">
                            <span class="text-white text-xs font-bold">{{ $i }}</span>
                        </div>
                    </div>
                </div>
                @endfor
            </div>
            
            <!-- View More Button -->
           
        </div>
    </section>
    
    <!-- Enhanced Lightbox Modal -->
    <div id="lightbox" class="fixed inset-0 bg-black/95 z-50 hidden items-center justify-center p-4 backdrop-blur-sm">
        <div class="relative max-w-6xl max-h-full w-full h-full flex items-center justify-center">
            <!-- Close Button -->
            <button onclick="closeLightbox()" class="absolute top-6 right-6 text-white hover:text-gray-300 transition-colors duration-300 z-20 bg-black/50 rounded-full p-2 backdrop-blur-sm">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>
            
            <!-- Previous Button -->
            <button onclick="previousImage()" class="absolute left-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors duration-300 z-20 bg-black/50 rounded-full p-3 backdrop-blur-sm hover:bg-black/70">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            
            <!-- Next Button -->
            <button onclick="nextImage()" class="absolute right-6 top-1/2 transform -translate-y-1/2 text-white hover:text-gray-300 transition-colors duration-300 z-20 bg-black/50 rounded-full p-3 backdrop-blur-sm hover:bg-black/70">
                <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>
            </button>
            
            <!-- Main Image Container -->
            <div class="relative max-w-5xl max-h-full flex items-center justify-center">
                <img id="lightbox-image" src="" alt="صورة معرض وسيلة الخيرية" class="max-w-full max-h-full rounded-lg shadow-2xl transition-all duration-300">
                
                <!-- Image Counter -->
                <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 bg-black/70 text-white px-4 py-2 rounded-full backdrop-blur-sm">
                    <span id="image-counter">1 / 12</span>
                </div>
            </div>
            
            <!-- Thumbnail Navigation -->
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 flex space-x-2 space-x-reverse max-w-4xl overflow-x-auto pb-2">
                @for($i = 1; $i <= 12; $i++)
                <button onclick="goToImage({{ $i }})" class="thumbnail-btn flex-shrink-0 w-16 h-16 rounded-lg overflow-hidden border-2 border-transparent hover:border-white transition-all duration-300">
                    <img src="{{ asset('images/' . $i . '.png') }}" alt="صورة {{ $i }}" class="w-full h-full object-cover">
                </button>
                @endfor
            </div>
        </div>
    </div>

    <!-- Enhanced Contact Section -->
    <section id="contact" class="py-20 bg-gradient-to-br from-[#0a2540] via-[#1e3a8a] to-[#fbbf24] relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute top-0 left-0 w-96 h-96 bg-gradient-to-br from-[#fbbf24] via-[#f59e42] to-[#1e3a8a] rounded-full -translate-x-48 -translate-y-48 blur-2xl"></div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-gradient-to-tr from-[#fffbe6] via-[#fbbf24] to-[#1e3a8a] rounded-full translate-x-48 translate-y-48 blur-2xl"></div>
        </div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <!-- Enhanced Header -->
            <div class="text-center mb-16">
                <div class="inline-block mb-4">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-gradient-to-r from-[#fbbf24]/30 via-[#1e3a8a]/20 to-[#f59e42]/30 text-[#fbbf24] shadow-lg">
                        <svg class="w-6 h-6 mr-2 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="font-bold">تواصل معنا</span>
                    </span>
                </div>
                <h2 class="text-5xl md:text-6xl lg:text-7xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-[#fffbe6] via-[#fbbf24] to-[#1e3a8a] mb-6 drop-shadow-lg">
                    <span class="relative inline-block">
                        <span class="relative z-10 font-black glow-text">تواصل معنا</span>
                        <span class="absolute inset-0 bg-gradient-to-r from-[#fffbe6]/30 via-[#fbbf24]/20 to-[#1e3a8a]/20 blur-2xl scale-110"></span>
                    </span>
                </h2>
                <p class="text-lg text-[#f3f4f6] font-medium drop-shadow">
                    نحن هنا لمساعدتك في أي استفسار أو طلب خدمة. تواصل معنا وسنكون سعداء لخدمتك
                </p>
            </div>
            
            <!-- Enhanced Contact Grid - Single Container -->
            <div class="max-w-6xl mx-auto">
                <!-- Single Modern Contact Container -->
                <div class="bg-gradient-to-br from-[#1e3a8a]/80 via-[#fbbf24]/10 to-[#fffbe6]/10 backdrop-blur-2xl rounded-3xl p-12 border border-[#fbbf24]/30 shadow-2xl">
                    <!-- Contact Content Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                        <!-- Contact Information -->
                        <div>
                            <h3 class="text-3xl font-bold text-[#fbbf24] mb-8">{{ __('messages.contact_title') }}</h3>
                            
                            <div class="space-y-6">
                            <!-- Email -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-br from-[#fbbf24] via-[#f59e42] to-[#1e3a8a] rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-[#fffbe6] mb-1">البريد الإلكتروني</h4>
                                    <p class="text-[#fbbf24] text-lg font-bold">{{ \App\Helpers\SettingsHelper::contactEmail() }}</p>
                                </div>
                            </div>
                            
                            <!-- Phone -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-br from-[#1e3a8a] via-[#fbbf24] to-[#f59e42] rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-[#fffbe6] mb-1">الهاتف</h4>
                                    <p class="text-[#fbbf24] text-lg font-bold">{{ \App\Helpers\SettingsHelper::contactPhone() }}</p>
                                </div>
                            </div>
                            
                            <!-- Location -->
                            <div class="flex items-center group">
                                <div class="w-16 h-16 bg-gradient-to-br from-[#fbbf24] via-[#fffbe6] to-[#1e3a8a] rounded-2xl flex items-center justify-center ml-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-lg font-semibold text-[#fffbe6] mb-1">العنوان</h4>
                                    <p class="text-[#fbbf24] text-lg font-bold">{{ \App\Helpers\SettingsHelper::address() }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Social Media Links -->
                        <div class="mt-8 pt-8 border-t border-[#fbbf24]/30">
                            <h4 class="text-xl font-semibold text-[#fbbf24] mb-4 text-center">تابعنا على</h4>
                            <div class="flex justify-center space-x-4 space-x-reverse">
                                <a href="#" class="w-12 h-12 bg-gradient-to-br from-[#1e3a8a] via-[#fbbf24] to-[#f59e42] rounded-full flex items-center justify-center hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gradient-to-br from-[#fbbf24] via-[#f59e42] to-[#1e3a8a] rounded-full flex items-center justify-center hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                    </svg>
                                </a>
                                <a href="#" class="w-12 h-12 bg-gradient-to-br from-[#fffbe6] via-[#fbbf24] to-[#1e3a8a] rounded-full flex items-center justify-center hover:scale-110 transition-transform duration-300 shadow-lg">
                                    <svg class="w-6 h-6 text-[#1e3a8a]" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.746-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                    </svg>
                                </a>
                            </div>
                            </div>
                        </div>
                        
                        <!-- Contact Form -->
                        <div class="w-full max-w-4xl mx-auto">
                        <h3 class="text-3xl md:text-4xl font-bold text-[#fbbf24] mb-8 text-center">أرسل لنا رسالة</h3>
                        
                        <!-- Success Message -->
                        <div id="contactSuccess" class="hidden mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>تم إرسال رسالتك بنجاح. سنتواصل معك قريباً.</span>
                            </div>
                        </div>
                        
                        <!-- Error Message -->
                        <div id="contactError" class="hidden mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span>حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.</span>
                            </div>
                        </div>
                        
                        <form class="space-y-6" id="contactForm" method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <div class="relative">
                                <input type="text" name="name" placeholder="الاسم الكامل" required
                                       class="w-full px-6 py-4 rounded-2xl bg-white/90 border border-[#fbbf24]/40 text-black placeholder-[#fbbf24] placeholder:text-right focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all duration-300 backdrop-blur-sm font-medium shadow-lg">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <input type="email" name="email" placeholder="البريد الإلكتروني" required
                                       class="w-full px-6 py-4 rounded-2xl bg-white/90 border border-[#fbbf24]/40 text-black placeholder-[#fbbf24] placeholder:text-right focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all duration-300 backdrop-blur-sm font-medium shadow-lg">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <input type="tel" name="phone" placeholder="رقم الهاتف" required
                                       class="w-full px-6 py-4 rounded-2xl bg-white/90 border border-[#fbbf24]/40 text-black placeholder-[#fbbf24] placeholder:text-right focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all duration-300 backdrop-blur-sm font-medium shadow-lg">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <input type="text" name="subject" placeholder="الموضوع (اختياري)"
                                       class="w-full px-6 py-4 rounded-2xl bg-white/90 border border-[#fbbf24]/40 text-black placeholder-[#fbbf24] placeholder:text-right focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all duration-300 backdrop-blur-sm font-medium shadow-lg">
                                <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                    <svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2v1a1 1 0 001 1h6a1 1 0 001-1V3a2 2 0 012 2v6a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="relative">
                                <textarea name="message" rows="5" placeholder="اكتب رسالتك هنا..." required
                                          class="w-full px-6 py-4 rounded-2xl bg-white/90 border border-[#fbbf24]/40 text-black placeholder-[#fbbf24] placeholder:text-right focus:outline-none focus:ring-2 focus:ring-[#fbbf24] focus:border-transparent transition-all duration-300 backdrop-blur-sm resize-none font-medium shadow-lg"></textarea>
                                <div class="absolute top-4 right-4">
                                    <svg class="w-5 h-5 text-[#fbbf24]" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.369 4.369 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
                                    </svg>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-[#fbbf24] via-[#f59e42] to-[#1e3a8a] text-white px-8 py-4 rounded-2xl font-bold text-lg flex items-center justify-center group shadow-xl hover:from-[#1e3a8a] hover:to-[#fbbf24] transition-all duration-300">
                                <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1 text-[#fffbe6]" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"/>
                                </svg>
                                <span>إرسال الرسالة</span>
                            </button>
                        </form>
                    </div>
                </div>
                
               
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-primary-dark text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Logo and Description -->
                <div class="col-span-1">
                    <img src="{{ asset('images/logo-footer.png') }}" alt="شعار وسيلة الخيرية" class="h-12 w-auto mb-4">
                    <p class="text-gray-300 leading-relaxed">
                        مشروع خيري اجتماعي يهدف إلى توزيع المياه ومنتجات العناية بالمساجد وتوزيع وجبات الطعام وكراسي كبار السن وغيرها من الخدمات الإنسانية.
                    </p>
                </div>
                
                <!-- Quick Links -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">روابط سريعة</h3>
                    <ul class="space-y-2">
                        <li><a href="#home" class="text-gray-300 hover:text-white transition duration-300">الرئيسية</a></li>
                        <li><a href="#services" class="text-gray-300 hover:text-white transition duration-300">الخدمات</a></li>
                        <li><a href="#about" class="text-gray-300 hover:text-white transition duration-300">من نحن</a></li>
                        <li><a href="#contact" class="text-gray-300 hover:text-white transition duration-300">اتصل بنا</a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="col-span-1">
                    <h3 class="text-lg font-semibold mb-4">معلومات الاتصال</h3>
                    <div class="space-y-2 text-gray-300">
                        <p>البريد الإلكتروني: {{ \App\Helpers\SettingsHelper::contactEmail() }}</p>
                        <p>الهاتف: {{ \App\Helpers\SettingsHelper::contactPhone() }}</p>
                        <p>العنوان: {{ \App\Helpers\SettingsHelper::address() }}</p>
                    </div>
                </div>
            </div>
            
            <div class="border-t border-gray-600 mt-8 pt-8 text-center text-gray-300">
                <p>&copy; {{ date('Y') }} وسيلة. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <!-- Floating Order Button -->
    <div class="floating-btn">
        <button onclick="scrollToServices()" class="btn-accent text-white px-6 py-3 rounded-full font-semibold shadow-lg transform hover:scale-110 transition-all duration-300">
            <span class="flex items-center">
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
                اطلب خدمة
            </span>
        </button>
    </div>

    <!-- WhatsApp Floating Button -->
    <div class="whatsapp-btn">
        <a href="https://wa.me/966501234567?text=مرحباً، أريد الاستفسار عن خدمات وسيلة الخيرية" 
           target="_blank" 
           class="bg-green-500 hover:bg-green-600 text-white px-6 py-3 rounded-full font-semibold shadow-lg transform hover:scale-110 transition-all duration-300 flex items-center">
            <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 24 24">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
            </svg>
            واتساب
        </a>
    </div>


    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        function switchLanguage(locale, event) {
            event.preventDefault();
            
            // Show loading indicator
            const link = event.target;
            const originalText = link.textContent;
            link.textContent = locale === 'ar' ? '{{ __('messages.loading') }}' : '{{ __('messages.loading') }}';
            
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

        function scrollToServices() {
            document.getElementById('services').scrollIntoView({ behavior: 'smooth' });
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                }
            });
        });

        // Close mobile menu when clicking on a link
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', () => {
                document.getElementById('mobile-menu').classList.add('hidden');
            });
        });

        // Navbar background change on scroll
        window.addEventListener('scroll', () => {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('bg-white', 'shadow-lg');
            } else {
                navbar.classList.remove('bg-white', 'shadow-lg');
            }
        });
        
        // Video and Slideshow Management
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slide');
        let slideshowInterval;
        
        function checkVideoLoad() {
            setTimeout(() => {
                const video = document.getElementById('heroVideo');
                if (!video || video.offsetHeight === 0) {
                    showSlideshow();
                }
            }, 3000);
        }
        
        function showSlideshow() {
            const video = document.getElementById('heroVideo');
            const slideshow = document.getElementById('slideshow');
            
            if (video) {
                video.style.display = 'none';
            }
            if (slideshow) {
                slideshow.style.display = 'block';
                startSlideshow();
            }
        }
        
        function startSlideshow() {
            slideshowInterval = setInterval(() => {
                slides[currentSlide].classList.remove('active');
                currentSlide = (currentSlide + 1) % slides.length;
                slides[currentSlide].classList.add('active');
            }, 4000);
        }
        
        function stopSlideshow() {
            if (slideshowInterval) {
                clearInterval(slideshowInterval);
            }
        }
        
        // Initialize slideshow as fallback
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const video = document.getElementById('heroVideo');
                if (!video || video.offsetHeight === 0) {
                    showSlideshow();
                }
            }, 2000);
        });
        
        // Enhanced Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const hamburger = document.querySelector('.hamburger');
            
            mobileMenu.classList.toggle('hidden');
            hamburger.classList.toggle('active');
        }
        
        // Enhanced Lightbox Functions
        let currentImageIndex = 1;
        const totalImages = 12;
        
        function openLightbox(imageNumber) {
            currentImageIndex = imageNumber;
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightbox-image');
            const imageCounter = document.getElementById('image-counter');
            
            lightboxImage.src = `{{ asset('images/') }}/${imageNumber}.png`;
            lightboxImage.alt = `صورة ${imageNumber}`;
            imageCounter.textContent = `${imageNumber} / ${totalImages}`;
            
            // Update thumbnail active state
            updateThumbnailActive(imageNumber);
            
            lightbox.classList.remove('hidden');
            lightbox.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }
        
        function closeLightbox() {
            const lightbox = document.getElementById('lightbox');
            lightbox.classList.add('hidden');
            lightbox.classList.remove('flex');
            document.body.style.overflow = 'auto';
        }
        
        function nextImage() {
            currentImageIndex = currentImageIndex >= totalImages ? 1 : currentImageIndex + 1;
            updateLightboxImage();
        }
        
        function previousImage() {
            currentImageIndex = currentImageIndex <= 1 ? totalImages : currentImageIndex - 1;
            updateLightboxImage();
        }
        
        function goToImage(imageNumber) {
            currentImageIndex = imageNumber;
            updateLightboxImage();
        }
        
        function updateLightboxImage() {
            const lightboxImage = document.getElementById('lightbox-image');
            const imageCounter = document.getElementById('image-counter');
            
            lightboxImage.src = `{{ asset('images/') }}/${currentImageIndex}.png`;
            lightboxImage.alt = `صورة ${currentImageIndex}`;
            imageCounter.textContent = `${currentImageIndex} / ${totalImages}`;
            
            // Update thumbnail active state
            updateThumbnailActive(currentImageIndex);
        }
        
        function updateThumbnailActive(activeIndex) {
            const thumbnails = document.querySelectorAll('.thumbnail-btn');
            thumbnails.forEach((thumb, index) => {
                if (index + 1 === activeIndex) {
                    thumb.classList.add('border-white', 'ring-2', 'ring-white/50');
                    thumb.classList.remove('border-transparent');
                } else {
                    thumb.classList.remove('border-white', 'ring-2', 'ring-white/50');
                    thumb.classList.add('border-transparent');
                }
            });
        }
        
        // Enhanced keyboard navigation
        document.addEventListener('keydown', (e) => {
            const lightbox = document.getElementById('lightbox');
            if (lightbox.classList.contains('flex')) {
                switch(e.key) {
                    case 'Escape':
                        closeLightbox();
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        e.preventDefault();
                        nextImage();
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        e.preventDefault();
                        previousImage();
                        break;
                    case ' ':
                        e.preventDefault();
                        nextImage();
                        break;
                }
            }
        });
        
        // Close lightbox on background click
        document.getElementById('lightbox').addEventListener('click', (e) => {
            if (e.target.id === 'lightbox') {
                closeLightbox();
            }
        });
        
        // Touch/swipe support for mobile
        let touchStartX = 0;
        let touchEndX = 0;
        
        document.getElementById('lightbox').addEventListener('touchstart', (e) => {
            touchStartX = e.changedTouches[0].screenX;
        });
        
        document.getElementById('lightbox').addEventListener('touchend', (e) => {
            touchEndX = e.changedTouches[0].screenX;
            handleSwipe();
        });
        
        function handleSwipe() {
            const swipeThreshold = 50;
            const diff = touchStartX - touchEndX;
            
            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    // Swipe left - next image
                    nextImage();
                } else {
                    // Swipe right - previous image
                    previousImage();
                }
            }
        }
        
        // Enhanced Scroll Animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);
        
        // Observe elements for animation
        document.addEventListener('DOMContentLoaded', () => {
            const animateElements = document.querySelectorAll('.service-card, .gallery-item');
            animateElements.forEach(el => observer.observe(el));
        });
        
        // Parallax Effect
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.parallax-element');
            
            parallaxElements.forEach(element => {
                const speed = element.dataset.speed || 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
        
        // Enhanced Navbar Active Link
        function updateActiveNavLink() {
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
                if (link.getAttribute('href') === `#${current}`) {
                    link.classList.add('active');
                }
            });
        }
        
        window.addEventListener('scroll', updateActiveNavLink);
        
        // Smooth scrolling for navigation links
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
        
        // Loading Animation
        function showLoadingAnimation() {
            const loadingElements = document.querySelectorAll('.loading-shimmer');
            loadingElements.forEach(el => {
                el.style.display = 'block';
            });
        }
        
        function hideLoadingAnimation() {
            const loadingElements = document.querySelectorAll('.loading-shimmer');
            loadingElements.forEach(el => {
                el.style.display = 'none';
            });
        }
        
        // Enhanced Button Interactions
        document.querySelectorAll('button, .btn-primary, .btn-accent').forEach(button => {
            button.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-2px) scale(1.05)';
            });
            
            button.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });
        
        // Interactive Map Functions
        function zoomIn() {
            const map = document.getElementById('map');
            map.style.transform = 'scale(1.1)';
            setTimeout(() => {
                map.style.transform = 'scale(1)';
            }, 300);
        }
        
        function zoomOut() {
            const map = document.getElementById('map');
            map.style.transform = 'scale(0.9)';
            setTimeout(() => {
                map.style.transform = 'scale(1)';
            }, 300);
        }
        
        // Contact Form Enhancement
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Hide previous messages
            document.getElementById('contactSuccess').classList.add('hidden');
            document.getElementById('contactError').classList.add('hidden');
            
            // Add loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            
            submitBtn.innerHTML = `
                <svg class="animate-spin w-5 h-5 ml-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>جاري الإرسال...</span>
            `;
            submitBtn.disabled = true;
            
            // Get form data
            const formData = new FormData(this);
            
            // Send AJAX request
            fetch('{{ route("contact.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Show success message
                    document.getElementById('contactSuccess').classList.remove('hidden');
                    
                    // Reset form
                    this.reset();
                    
                    // Reset button
                    submitBtn.innerHTML = originalText;
                    submitBtn.disabled = false;
                    
                    // Scroll to success message
                    document.getElementById('contactSuccess').scrollIntoView({ behavior: 'smooth', block: 'center' });
                } else {
                    throw new Error(data.message || 'حدث خطأ أثناء إرسال الرسالة');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                document.getElementById('contactError').classList.remove('hidden');
                
                // Reset button
                submitBtn.innerHTML = originalText;
                submitBtn.disabled = false;
                
                // Scroll to error message
                document.getElementById('contactError').scrollIntoView({ behavior: 'smooth', block: 'center' });
            });
        });
    </script>
</body>
</html>
