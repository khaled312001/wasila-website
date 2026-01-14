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
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            {{ app()->getLocale() === 'ar' ? 'right' : 'left' }}: 0;
            width: 280px;
            height: 100vh;
            background: linear-gradient(180deg, #025469 0%, #08788B 100%);
            padding: 2rem 0;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar-logo {
            padding: 0 2rem 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            margin-bottom: 2rem;
        }
        
        .sidebar-menu {
            padding: 0 1rem;
        }
        
        .sidebar-menu-item {
            display: flex;
            align-items: center;
            padding: 1rem 1.5rem;
            color: rgba(255, 255, 255, 0.8) !important;
            text-decoration: none;
            border-radius: 0.75rem;
            margin-bottom: 0.5rem;
            transition: all 0.3s ease;
            background: transparent;
            border: none;
            cursor: pointer;
            width: 100%;
            text-align: {{ app()->getLocale() === 'ar' ? 'right' : 'left' }};
        }
        
        .sidebar-menu-item:hover,
        .sidebar-menu-item.active {
            background: rgba(255, 255, 255, 0.1) !important;
            color: white !important;
            transform: translateX({{ app()->getLocale() === 'ar' ? '-5px' : '5px' }});
        }
        
        .sidebar-menu-item svg {
            width: 24px;
            height: 24px;
            {{ app()->getLocale() === 'ar' ? 'margin-left' : 'margin-right' }}: 1rem;
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        .sidebar-menu-item:hover svg,
        .sidebar-menu-item.active svg {
            color: white !important;
        }
        
        /* Logout Button Specific Styles */
        form .sidebar-menu-item {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        form .sidebar-menu-item span {
            color: rgba(255, 255, 255, 0.8) !important;
        }
        
        form .sidebar-menu-item:hover {
            color: white !important;
            background: rgba(255, 255, 255, 0.1) !important;
        }
        
        form .sidebar-menu-item:hover span {
            color: white !important;
        }
        
        form .sidebar-menu-item:hover svg {
            color: white !important;
        }
        
        /* Main Content */
        .main-content {
            {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: 280px;
            padding: 2rem;
            min-height: 100vh;
        }
        
        /* Top Bar */
        .top-bar {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 1rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
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
                width: 260px;
            }
            
            .main-content {
                {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: 260px;
                padding: 1.5rem;
            }
        }
        
        @media (max-width: 768px) {
            .sidebar {
                width: 280px;
                transform: translateX({{ app()->getLocale() === 'ar' ? '100%' : '-100%' }});
                transition: transform 0.3s ease;
                box-shadow: 4px 0 20px rgba(0, 0, 0, 0.2);
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .main-content {
                {{ app()->getLocale() === 'ar' ? 'margin-right' : 'margin-left' }}: 0;
                padding: 1rem;
            }
            
            .sidebar-logo {
                padding: 0 1.5rem 1.5rem;
            }
            
            .sidebar-logo img {
                height: 80px !important;
            }
            
            .sidebar-logo h3 {
                font-size: 1.125rem !important;
                margin-top: 0.75rem !important;
            }
            
            .sidebar-logo p {
                font-size: 0.8125rem !important;
            }
            
            .sidebar-menu-item {
                padding: 0.875rem 1.25rem;
                font-size: 0.9375rem;
            }
            
            .mobile-menu-btn {
                display: flex !important;
                align-items: center;
                justify-content: center;
            }
            
            .top-bar {
                padding: 1rem 1.25rem;
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
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
                padding: 0 1rem 1rem;
            }
            
            .sidebar-logo img {
                height: 70px !important;
            }
            
            .sidebar-menu-item {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .mobile-menu-btn {
                width: 56px;
                height: 56px;
                bottom: 1.5rem;
                {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 1.5rem;
            }
        }
        
        .mobile-menu-btn {
            display: none;
            position: fixed;
            bottom: 2rem;
            {{ app()->getLocale() === 'ar' ? 'left' : 'right' }}: 2rem;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #3CA6B4 0%, #08788B 100%);
            border-radius: 50%;
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
        
        .mobile-menu-btn i {
            font-size: 1.5rem;
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
    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-logo">
            <img src="{{ asset('images/logo-footer.png') }}" alt="{{ __('messages.wasila') }}" style="height: 100px; width: auto;">
            <h3 class="text-white text-xl font-bold mt-4">{{ auth('customer')->user()->name }}</h3>
            <p class="text-white text-sm opacity-80">{{ auth('customer')->user()->email }}</p>
        </div>
        
        <nav class="sidebar-menu">
            <a href="{{ route('customer.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                </svg>
                {{ __('messages.dashboard') }}
            </a>
            
            <a href="{{ route('customer.orders.index') }}" class="sidebar-menu-item {{ request()->routeIs('customer.orders.*') ? 'active' : '' }}">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                </svg>
                {{ __('messages.my_orders') }}
            </a>
            
            <a href="{{ route('customer.messages.index') }}" class="sidebar-menu-item {{ request()->routeIs('customer.messages.*') ? 'active' : '' }}">
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                    <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                </svg>
                {{ __('messages.messages') }}
                @if(auth('customer')->user()->unreadMessages()->count() > 0)
                <span class="bg-red-500 text-white text-xs rounded-full px-2 py-1 {{ app()->getLocale() === 'ar' ? 'mr-auto' : 'ml-auto' }}">
                    {{ auth('customer')->user()->unreadMessages()->count() }}
                </span>
                @endif
            </a>
            
            <form action="{{ route('customer.logout') }}" method="POST" class="mt-8">
                @csrf
                <button type="submit" class="sidebar-menu-item w-full" style="color: rgba(255, 255, 255, 0.8) !important;">
                    <svg fill="currentColor" viewBox="0 0 20 20" style="color: rgba(255, 255, 255, 0.8) !important;">
                        <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z" clip-rule="evenodd"/>
                    </svg>
                    <span style="color: rgba(255, 255, 255, 0.8) !important;">{{ __('messages.logout') }}</span>
                </button>
            </form>
        </nav>
    </aside>
    
    <!-- Sidebar Overlay -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>
    
    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" onclick="toggleSidebar()" id="mobileMenuBtn">
        <i class="fas fa-bars"></i>
    </button>
    
    <!-- Main Content -->
    <main class="main-content">
        <!-- Top Bar -->
        <div class="top-bar">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">@yield('page-title', __('messages.dashboard'))</h1>
                @hasSection('page-subtitle')
                <p class="text-gray-600 text-sm mt-1">@yield('page-subtitle')</p>
                @endif
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-primary-medium hover:text-primary-dark">
                    {{ __('messages.back_to_home') }}
                </a>
            </div>
        </div>
        
        <!-- Alerts -->
        @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-4 rounded">
            <p class="text-green-700">{{ session('success') }}</p>
        </div>
        @endif
        
        @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-4 rounded">
            <p class="text-red-700">{{ session('error') }}</p>
        </div>
        @endif
        
        <!-- Page Content -->
        @yield('content')
    </main>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
            
            // Change icon
            if (sidebar.classList.contains('mobile-open')) {
                menuBtn.innerHTML = '<i class="fas fa-times"></i>';
            } else {
                menuBtn.innerHTML = '<i class="fas fa-bars"></i>';
            }
        }
        
        // Close sidebar when clicking overlay
        document.getElementById('sidebarOverlay').addEventListener('click', function() {
            toggleSidebar();
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            const menuBtn = document.getElementById('mobileMenuBtn');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
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

