<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'لوحة التحكم') - وسيلة</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700" rel="stylesheet" />
    
    <!-- Error Fixes Script - Must be loaded first -->
    <script src="{{ asset('js/error-fixes.js') }}"></script>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Admin Mobile CSS -->
    <link href="{{ asset('css/admin-mobile.css') }}" rel="stylesheet">
    
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
        
        .sidebar {
            transition: all 0.3s ease;
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .main-content {
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-right: 80px;
        }
        
        .sidebar-item {
            transition: all 0.3s ease;
        }
        
        .sidebar-item:hover {
            background-color: rgba(60, 166, 180, 0.1);
        }
        
        .sidebar-item.active {
            background-color: var(--primary-medium);
            color: white;
        }
        
        .chart-container {
            position: relative;
            height: 300px;
        }
        
        /* Enhanced Dashboard Animations */
        .dashboard-card {
            transition: all 0.3s ease;
            animation: slideInUp 0.6s ease-out;
        }
        
        .dashboard-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(2, 84, 105, 0.15);
        }
        
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(8, 120, 139, 0.1);
            transition: all 0.3s ease;
            animation: fadeInScale 0.8s ease-out;
        }
        
        .stat-card:hover {
            transform: translateY(-3px) scale(1.02);
            box-shadow: 0 15px 30px rgba(8, 120, 139, 0.2);
            border-color: rgba(8, 120, 139, 0.3);
        }
        
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: right 0.5s;
        }
        
        .sidebar-item:hover::before {
            right: 100%;
        }
        
        .sidebar-item:hover {
            transform: translateX(-5px);
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 5px 15px rgba(8, 120, 139, 0.3);
        }
        
        .chart-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border: 1px solid rgba(8, 120, 139, 0.1);
            transition: all 0.3s ease;
            animation: slideInRight 0.8s ease-out;
        }
        
        .chart-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8, 120, 139, 0.15);
        }
        
        .table-row {
            transition: all 0.2s ease;
        }
        
        .table-row:hover {
            background-color: rgba(8, 120, 139, 0.05);
            transform: scale(1.01);
        }
        
        .btn-enhanced {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-enhanced::before {
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
        
        .btn-enhanced:hover::before {
            width: 300px;
            height: 300px;
        }
        
        .btn-enhanced:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(8, 120, 139, 0.3);
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
        
        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
        
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        /* Enhanced Color Scheme */
        .bg-primary-gradient {
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary-medium) 50%, var(--primary-dark) 100%);
        }
        
        .bg-accent-gradient {
            background: linear-gradient(135deg, var(--accent) 0%, #F4B942 100%);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, var(--primary-medium) 0%, var(--primary-dark) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        /* Mobile Responsive Enhancements */
        @media (max-width: 768px) {
            .dashboard-card {
                margin-bottom: 1rem;
            }
            
            .stat-card {
                margin-bottom: 0.5rem;
            }
            
            .chart-container {
                height: 250px;
            }
            
            /* Mobile Sidebar */
            .sidebar {
                transform: translateX(100%);
                transition: transform 0.3s ease-in-out;
            }
            
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 30;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }
            
            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
            /* Mobile Main Content */
            .main-content {
                margin-right: 0 !important;
                width: 100%;
            }
            
            /* Mobile Header */
            .mobile-header {
                padding: 1rem;
            }
            
            .mobile-header h1 {
                font-size: 1.25rem;
            }
            
            /* Mobile Cards */
            .mobile-card {
                margin-bottom: 1rem;
                padding: 1rem;
            }
            
            /* Mobile Tables */
            .mobile-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
            
            .mobile-table table {
                min-width: 600px;
            }
            
            /* Mobile Forms */
            .mobile-form {
                padding: 1rem;
            }
            
            .mobile-form .form-group {
                margin-bottom: 1rem;
            }
            
            /* Mobile Buttons */
            .mobile-btn {
                width: 100%;
                padding: 0.75rem;
                font-size: 1rem;
            }
            
            /* Mobile Navigation Items */
            .sidebar-item {
                padding: 1rem 1.5rem;
                font-size: 1rem;
            }
            
            .sidebar-item svg {
                width: 1.5rem;
                height: 1.5rem;
            }
        }
        
        /* Tablet Responsive */
        @media (max-width: 1024px) and (min-width: 769px) {
            .sidebar {
                width: 240px;
            }
            
            .main-content {
                margin-right: 240px;
            }
            
            .sidebar.collapsed {
                width: 60px;
            }
            
            .main-content.expanded {
                margin-right: 60px;
            }
        }
        
        /* Small Mobile */
        @media (max-width: 480px) {
            .mobile-header {
                padding: 0.75rem;
            }
            
            .mobile-header h1 {
                font-size: 1.125rem;
            }
            
            .mobile-card {
                padding: 0.75rem;
            }
            
            .sidebar-item {
                padding: 0.875rem 1.25rem;
                font-size: 0.9rem;
            }
            
            .sidebar-item svg {
                width: 1.25rem;
                height: 1.25rem;
            }
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeMobileSidebar()"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar bg-white shadow-lg w-64 fixed right-0 top-0 h-full z-40">
            <!-- Logo -->
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center">
                    <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-10 w-auto">
                    <span class="sidebar-text mr-3 text-xl font-bold text-primary-dark">وسيلة</span>
                </div>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-6">
                <div class="px-4">
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span class="sidebar-text">لوحة التحكم</span>
                    </a>
                    
                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span class="sidebar-text">الطلبات</span>
                    </a>
                    
                    <!-- Services -->
                    <a href="{{ route('admin.services.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sidebar-text">الخدمات</span>
                    </a>
                    
                    <!-- Contact Messages -->
                    <a href="{{ route('admin.contact-messages.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="sidebar-text">الرسائل</span>
                        <span id="unread-count" class="sidebar-text bg-red-500 text-white text-xs rounded-full px-2 py-1 mr-2 hidden">0</span>
                    </a>
                    
                    <!-- MyFatoorah Management -->
                    <a href="{{ route('admin.myfatoorah.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.myfatoorah.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                        </svg>
                        <span class="sidebar-text">إدارة المدفوعات</span>
                    </a>
                    
                    
                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        <span class="sidebar-text">التقارير والإحصائيات</span>
                    </a>
                    
                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sidebar-text">الإعدادات</span>
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-gray-200 my-4"></div>
                    
                    <!-- Website -->
                    <a href="{{ route('home') }}"  target="_blank"
                       class="sidebar-item flex items-center px-4 py-3 text-gray-700 rounded-lg mb-2">
                        <svg class="w-5 h-5 ml-3" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        <span class="sidebar-text">الموقع الرئيسي</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="main-content" class="main-content flex-1 mr-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200 mobile-header">
                <div class="flex justify-between items-center px-4 md:px-6 py-3 md:py-4">
                    <div class="flex items-center">
                        <button onclick="toggleMobileSidebar()" class="text-gray-500 hover:text-gray-700 ml-2 md:ml-4 lg:hidden">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <button onclick="toggleSidebar()" class="text-gray-500 hover:text-gray-700 ml-2 md:ml-4 hidden lg:block">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-lg md:text-2xl font-semibold text-gray-800">@yield('page-title', 'لوحة التحكم')</h1>
                    </div>
                    
                    <div class="flex items-center gap-2 md:gap-4 flex-row-reverse">
                        <div class="hidden sm:flex items-center bg-gray-100 rounded-full px-3 md:px-4 py-2 shadow-sm">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-primary-medium ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            <span class="text-xs md:text-sm font-medium text-gray-700">مرحباً، {{ Auth::guard('admin')->user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 md:gap-2 text-gray-500 hover:text-red-600 transition duration-300 bg-gray-100 rounded-full px-2 md:px-3 py-2 shadow-sm" title="تسجيل الخروج">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                                </svg>
                                <span class="hidden md:inline text-sm font-medium">تسجيل الخروج</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>
            
            <!-- Page Content -->
            <main class="p-3 md:p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('success') }}
                </div>
                @endif
                
                @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
                @endif
                
                @yield('content')
            </main>
        </div>
    </div>
    
    <!-- Admin Mobile JS -->
    <script src="{{ asset('js/admin-mobile.js') }}"></script>
    
    <!-- Scripts -->
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            
            sidebar.classList.toggle('collapsed');
            mainContent.classList.toggle('expanded');
        }
        
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.toggle('mobile-open');
            overlay.classList.toggle('active');
        }
        
        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
        }
        
        // Close mobile sidebar when clicking on navigation items
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeMobileSidebar();
                    }
                });
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            
            if (window.innerWidth > 768) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
            }
        });
        
        // Auto-hide alerts
        setTimeout(function() {
            const alerts = document.querySelectorAll('.bg-green-100, .bg-red-100');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
        
        // Update unread messages count
        function updateUnreadCount() {
            fetch('{{ route("admin.contact-messages.unread-count") }}')
                .then(response => response.json())
                .then(data => {
                    const unreadCountElement = document.getElementById('unread-count');
                    if (data.count > 0) {
                        unreadCountElement.textContent = data.count;
                        unreadCountElement.classList.remove('hidden');
                    } else {
                        unreadCountElement.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error fetching unread count:', error));
        }
        
        // Update unread count on page load
        updateUnreadCount();
        
        // Update unread count every 30 seconds
        setInterval(updateUnreadCount, 30000);
    </script>
    
    @stack('scripts')
</body>
</html>
