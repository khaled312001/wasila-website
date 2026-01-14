<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('messages.customer_dashboard')) - {{ __('messages.wasila') }}</title>
    
    <!-- Fonts - Tajawal for Arabic -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/wasila.css') }}">
    
    <style>
        :root {
            --primary-light: #3CA6B4;
            --primary-medium: #08788B;
            --primary-dark: #025469;
            --accent: #10b981;
        }
        
        body {
            font-family: {{ app()->getLocale() === 'ar' ? "'Tajawal'" : "'Inter'" }}, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }
        
        body.sidebar-open {
            overflow: hidden;
        }
        
        /* Layout Container */
        .layout-container {
            display: flex;
            min-height: 100vh;
            flex-direction: row-reverse; /* Sidebar always on right */
        }
        
        /* Sidebar Styles - Modern Design */
        .sidebar {
            width: 300px;
            min-width: 300px;
            height: 100vh;
            background: linear-gradient(180deg, #025469 0%, #08788B 50%, #3CA6B4 100%);
            padding: 0;
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: -4px 0 30px rgba(0, 0, 0, 0.15);
            position: sticky;
            top: 0;
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        
        /* Main Content */
        .main-content {
            flex: 1;
            padding: 0;
            min-height: 100vh;
            width: 100%;
            max-width: calc(100vw - 300px);
        }
        
        /* Top Navigation Bar */
        .top-navbar {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            border-bottom: 2px solid rgba(8, 120, 139, 0.2);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 30;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .top-navbar-content {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .top-navbar-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #025469;
            margin: 0;
        }
        
        .top-navbar-user {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: linear-gradient(135deg, rgba(8, 120, 139, 0.1) 0%, rgba(60, 166, 180, 0.1) 100%);
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            border: 1px solid rgba(8, 120, 139, 0.2);
        }
        
        .top-navbar-user-info {
            display: flex;
            flex-direction: column;
        }
        
        .top-navbar-user-name {
            font-size: 0.875rem;
            font-weight: 600;
            color: #025469;
        }
        
        .top-navbar-user-email {
            font-size: 0.75rem;
            color: #64748b;
        }
        
        .sidebar::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at top right, rgba(255, 255, 255, 0.1) 0%, transparent 50%);
            pointer-events: none;
        }
        
        /* Custom Scrollbar */
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .sidebar-logo {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.15);
            margin-bottom: 1.5rem;
            position: relative;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }
        
        .sidebar-logo img {
            height: 80px;
            width: auto;
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }
        
        .sidebar-logo:hover img {
            transform: scale(1.05);
        }
        
        .sidebar-user-info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-user-name {
            font-size: 1.125rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-user-name i {
            font-size: 1rem;
            opacity: 0.9;
        }
        
        .sidebar-user-email {
            font-size: 0.875rem;
            color: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-user-email i {
            font-size: 0.75rem;
        }
        
        .sidebar-menu {
            padding: 0 1rem 1rem;
            flex: 1;
        }
        
        .sidebar-menu-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            color: rgba(255, 255, 255, 0.85) !important;
            text-decoration: none;
            border-radius: 1rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: right;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-menu-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 4px;
            height: 100%;
            background: white;
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }
        
        .sidebar-menu-item:hover,
        .sidebar-menu-item.active {
            background: rgba(255, 255, 255, 0.15) !important;
            color: white !important;
            transform: translateX(-8px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        
        .sidebar-menu-item.active::before {
            transform: scaleY(1);
        }
        
        .sidebar-menu-item-content {
            display: flex;
            align-items: center;
            flex: 1;
        }
        
        .sidebar-menu-item svg,
        .sidebar-menu-item i {
            width: 22px;
            height: 22px;
            margin-left: 1rem;
            color: rgba(255, 255, 255, 0.85) !important;
            transition: all 0.3s ease;
        }
        
        .sidebar-menu-item:hover svg,
        .sidebar-menu-item:hover i,
        .sidebar-menu-item.active svg,
        .sidebar-menu-item.active i {
            color: white !important;
            transform: scale(1.1);
        }
        
        .sidebar-menu-item-text {
            font-weight: 500;
            font-size: 0.9375rem;
        }
        
        .sidebar-menu-badge {
            background: rgba(239, 68, 68, 0.9);
            color: white;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.25rem 0.5rem;
            border-radius: 1rem;
            min-width: 20px;
            text-align: center;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }
        
        /* Logout Button Specific Styles */
        .sidebar-logout-section {
            margin-top: auto;
            padding: 1rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            background: rgba(0, 0, 0, 0.1);
        }
        
        form .sidebar-menu-item {
            color: rgba(255, 255, 255, 0.85) !important;
            background: rgba(239, 68, 68, 0.1) !important;
        }
        
        form .sidebar-menu-item span {
            color: rgba(255, 255, 255, 0.85) !important;
        }
        
        form .sidebar-menu-item:hover {
            color: white !important;
            background: rgba(239, 68, 68, 0.2) !important;
        }
        
        form .sidebar-menu-item:hover span {
            color: white !important;
        }
        
        form .sidebar-menu-item:hover svg,
        form .sidebar-menu-item:hover i {
            color: white !important;
        }
        
        
        
        /* Cards */
        .dashboard-card {
            background: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        
        .dashboard-card:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-5px);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
            color: white;
            border-radius: 1rem;
            padding: 2rem;
            box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3);
        }
        
        /* Buttons */
        .btn-primary {
            background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
            color: white;
            padding: 0.75rem 2rem;
            border-radius: 0.75rem;
            border: none;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(8, 120, 139, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(8, 120, 139, 0.4);
        }
        
        /* Mobile Responsive */
        @media (max-width: 1024px) {
            .sidebar {
                width: 280px;
                min-width: 280px;
            }
            
            .main-content {
                max-width: calc(100vw - 280px);
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .layout-container {
                position: relative;
            }
            
            .sidebar {
                width: 280px;
                position: fixed;
                top: 0;
                right: 0;
                transform: translateX(100%);
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
                z-index: 1000;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            
            .top-navbar {
                padding: 1rem;
                padding-top: 4.5rem;
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .top-navbar-content {
                width: 100%;
                justify-content: space-between;
            }
            
            .top-navbar-title {
                font-size: 1.25rem;
            }
            
            .top-navbar-user {
                width: 100%;
                justify-content: flex-start;
            }
            
            .sidebar-logo {
                padding: 1.5rem;
            }
            
            .sidebar-logo img {
                height: 70px !important;
            }
            
            .sidebar-user-name {
                font-size: 1rem !important;
            }
            
            .sidebar-user-email {
                font-size: 0.8125rem !important;
            }
            
            .sidebar-menu-item {
                padding: 0.875rem 1rem;
                font-size: 0.9375rem;
            }
            
            .sidebar-menu-item-text {
                font-size: 0.875rem;
            }
            
            .mobile-menu-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            
            
            /* Close button in sidebar for mobile */
            .sidebar-close-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 40px;
                height: 40px;
                margin: 1rem auto;
                background: rgba(255, 255, 255, 0.2);
                border-radius: 10px;
                color: white;
                border: none;
                cursor: pointer;
                transition: all 0.3s ease;
            }
            
            .sidebar-close-btn:hover {
                background: rgba(255, 255, 255, 0.3);
                transform: scale(1.1);
            }
        }
        
        @media (max-width: 640px) {
            .sidebar {
                width: 100%;
            }
            
            .main-content {
                padding: 0.75rem;
            }
            
            .sidebar-logo {
                padding: 1.25rem;
            }
            
            .sidebar-logo img {
                height: 60px !important;
            }
            
            .sidebar-user-name {
                font-size: 0.9375rem !important;
            }
            
            .sidebar-user-email {
                font-size: 0.75rem !important;
            }
            
            .sidebar-menu-item {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .sidebar-menu-item-text {
                font-size: 0.8125rem;
            }
            
            .sidebar-logout-section {
                padding: 0.75rem;
            }
            
            .mobile-menu-btn {
                width: 44px;
                height: 44px;
                top: 0.75rem;
                right: 0.75rem;
            }
            
            .mobile-menu-btn i {
                font-size: 1.125rem;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 1rem;
            right: 1rem;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(8, 120, 139, 0.4);
            z-index: 999;
            border: none;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .mobile-menu-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(8, 120, 139, 0.5);
        }
        
        .mobile-menu-btn:active {
            transform: scale(0.95);
        }
        
        .mobile-menu-btn.active {
            background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
        }
        
        .mobile-menu-btn i {
            font-size: 1.25rem;
            transition: transform 0.3s ease;
        }
        
        .mobile-menu-btn.active i.fa-bars {
            transform: rotate(90deg);
        }
        
        
        /* Overlay for mobile sidebar */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 998;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        @media (max-width: 768px) {
            .sidebar-overlay.active {
                display: block;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Layout Container -->
    <div class="layout-container">
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Navigation Bar -->
        <header class="top-navbar">
            <div class="top-navbar-content">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="text-primary-medium hover:text-primary-dark hidden lg:block transition-colors duration-300 p-2 rounded-lg hover:bg-primary-medium/10">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <h1 class="top-navbar-title">@yield('page-title', __('messages.dashboard'))</h1>
                </div>
                
                <div class="flex items-center gap-3">
                    <div class="top-navbar-user">
                        <svg class="w-5 h-5 text-primary-medium" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 9a3 3 0 100-6 3 3 0 000 6z"/>
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd"/>
                        </svg>
                        <div class="top-navbar-user-info">
                            <span class="top-navbar-user-name">{{ auth('customer')->user()->name }}</span>
                            <span class="top-navbar-user-email hidden sm:inline">{{ auth('customer')->user()->email }}</span>
                        </div>
                    </div>
                    
                    <a href="{{ route('home') }}" class="text-primary-medium hover:text-primary-dark hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-primary-medium/10 transition-colors">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        <span class="text-sm font-medium">{{ __('messages.back_to_home') }}</span>
                    </a>
                    
                    <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 text-white bg-gradient-to-l from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-300 rounded-full px-3 py-2 shadow-md hover:shadow-lg transform hover:scale-105" title="{{ __('messages.logout') }}">
                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                            </svg>
                            <span class="hidden md:inline text-sm font-semibold">{{ __('messages.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </header>
        
        <!-- Page Content -->
        <div class="p-3 md:p-6">
            <!-- Alerts -->
            @if(session('success'))
            <div class="bg-green-50 border-r-4 border-green-400 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-400 ml-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
            @endif
            
            @if(session('error'))
            <div class="bg-red-50 border-r-4 border-red-400 p-4 mb-6 rounded-lg">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-red-400 ml-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
            @endif
            
            @yield('content')
        </div>
    </main>
    
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <!-- Close Button for Mobile -->
        <button onclick="toggleSidebar()" class="sidebar-close-btn" id="sidebarCloseBtn" aria-label="إغلاق القائمة">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
        
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-footer.png') }}" alt="{{ __('messages.wasila') }}">
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">
                    <i class="fas fa-user-circle"></i>
                    {{ auth('customer')->user()->name }}
                </div>
                <div class="sidebar-user-email">
                    <i class="fas fa-envelope"></i>
                    {{ auth('customer')->user()->email }}
                </div>
            </div>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('customer.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <div class="sidebar-menu-item-content">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-menu-item-text">{{ __('messages.dashboard') }}</span>
                </div>
            </a>
            
            <a href="{{ route('customer.orders.index') }}" class="sidebar-menu-item {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                <div class="sidebar-menu-item-content">
                    <i class="fas fa-shopping-bag"></i>
                    <span class="sidebar-menu-item-text">{{ __('messages.my_orders') }}</span>
                </div>
            </a>
            
            <a href="{{ route('customer.messages.index') }}" class="sidebar-menu-item {{ request()->routeIs('customer.messages.*') ? 'active' : '' }}">
                <div class="sidebar-menu-item-content">
                    <i class="fas fa-comments"></i>
                    <span class="sidebar-menu-item-text">{{ __('messages.messages') }}</span>
                </div>
                @if(auth('customer')->user()->unreadMessages()->count() > 0)
                <span class="sidebar-menu-badge">
                    {{ auth('customer')->user()->unreadMessages()->count() }}
                </span>
                @endif
            </a>
        </nav>
        
        <div class="sidebar-logout-section">
            <form action="{{ route('customer.logout') }}" method="POST">
                @csrf
                <button type="submit" class="sidebar-menu-item w-full">
                    <div class="sidebar-menu-item-content">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="sidebar-menu-item-text">{{ __('messages.logout') }}</span>
                    </div>
                </button>
            </form>
        </div>
    </aside>
    </div>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            const body = document.body;
            
            const isOpen = sidebar.classList.contains('mobile-open');
            
            if (isOpen) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                menuBtn.classList.remove('active');
                body.classList.remove('sidebar-open');
                body.style.overflow = '';
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            } else {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                menuBtn.classList.add('active');
                body.classList.add('sidebar-open');
                body.style.overflow = 'hidden';
                menuBtn.innerHTML = '<i class="fas fa-times"></i>';
            }
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            toggleSidebar();
        });
        
        // Close sidebar when clicking navigation items on mobile
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-menu-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        setTimeout(() => toggleSidebar(), 300);
                    }
                });
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            const body = document.body;
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                menuBtn.classList.remove('active');
                body.style.overflow = '';
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        });
        
        // Prevent body scroll when sidebar is open on mobile
        const sidebar = document.getElementById('sidebar');
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'class') {
                    if (sidebar.classList.contains('mobile-open') && window.innerWidth <= 768) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                }
            });
        });
        
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['class']
        });
    </script>
    
    @stack('scripts')
</body>
</html>

