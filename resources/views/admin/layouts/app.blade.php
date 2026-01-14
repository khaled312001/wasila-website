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
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Admin Mobile CSS -->
    <link href="{{ asset('css/admin-mobile.css') }}" rel="stylesheet">
    
    <!-- Custom Admin CSS -->
    @stack('styles')
    
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
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: linear-gradient(180deg, #025469 0%, #08788B 50%, #3CA6B4 100%);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            overflow-x: hidden;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 6px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        .sidebar.collapsed {
            width: 80px;
        }
        
        .sidebar.collapsed .sidebar-text {
            display: none;
        }
        
        .sidebar.collapsed .sidebar-logo-text {
            display: none;
        }
        
        .main-content {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .main-content.expanded {
            margin-right: 80px;
        }
        
        .sidebar-logo {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 2px solid rgba(255, 255, 255, 0.2);
            padding: 1.5rem;
        }
        
        .sidebar-logo img {
            filter: brightness(0) invert(1);
            transition: transform 0.3s ease;
        }
        
        .sidebar-logo:hover img {
            transform: scale(1.1);
        }
        
        .sidebar-item {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            color: rgba(255, 255, 255, 0.9);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            font-weight: 500;
        }
        
        .sidebar-item::before {
            content: '';
            position: absolute;
            top: 0;
            right: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: right 0.5s ease;
        }
        
        .sidebar-item:hover::before {
            right: 100%;
        }
        
        .sidebar-item:hover {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            transform: translateX(-5px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.25) 0%, rgba(255, 255, 255, 0.15) 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            border-right: 4px solid white;
        }
        
        .sidebar-item.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 60%;
            background: white;
            border-radius: 0 4px 4px 0;
        }
        
        .sidebar-item svg {
            transition: all 0.3s ease;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }
        
        .sidebar-item:hover svg,
        .sidebar-item.active svg {
            transform: scale(1.15);
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.2));
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
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
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
                background-color: rgba(0, 0, 0, 0.6);
                backdrop-filter: blur(4px);
                z-index: 30;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }
            
            .sidebar-overlay.active {
                opacity: 1;
                visibility: visible;
            }
            
        /* Mobile Sidebar Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 35;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .sidebar-overlay.active {
            display: block;
            opacity: 1;
        }
        
        /* Mobile Sidebar */
        .sidebar {
            transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: -4px 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar.mobile-open {
            transform: translateX(0);
        }
        
        /* Mobile Menu Button */
        .mobile-menu-toggle {
            position: fixed;
            top: 1rem;
            right: 1rem;
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%);
            border-radius: 12px;
            border: none;
            color: white;
            cursor: pointer;
            z-index: 50;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(8, 120, 139, 0.4);
            transition: all 0.3s ease;
        }
        
        .mobile-menu-toggle:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 20px rgba(8, 120, 139, 0.5);
        }
        
        .mobile-menu-toggle:active {
            transform: scale(0.95);
        }
        
        .mobile-menu-toggle svg {
            width: 24px;
            height: 24px;
            transition: transform 0.3s ease;
        }
        
        .mobile-menu-toggle.active svg {
            transform: rotate(90deg);
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
            
            /* Mobile Tables - Enhanced Responsive */
            .mobile-table {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
                width: 100%;
                position: relative;
            }
            
            .mobile-table::-webkit-scrollbar {
                height: 8px;
            }
            
            .mobile-table::-webkit-scrollbar-track {
                background: #f1f5f9;
                border-radius: 4px;
            }
            
            .mobile-table::-webkit-scrollbar-thumb {
                background: #08788B;
                border-radius: 4px;
            }
            
            .mobile-table::-webkit-scrollbar-thumb:hover {
                background: #065a6b;
            }
            
            .mobile-table table {
                width: 100%;
                min-width: 100%;
                border-collapse: collapse;
            }
            
            /* Card-based table view for mobile - Only when data-label exists */
            @media (max-width: 768px) {
                .mobile-table table {
                    display: block;
                    width: 100%;
                }
                
                .mobile-table thead {
                    display: none;
                }
                
                .mobile-table tbody {
                    display: block;
                    width: 100%;
                }
                
                .mobile-table tr {
                    display: block;
                    border: 1px solid #e2e8f0;
                    border-radius: 8px;
                    margin-bottom: 1rem;
                    padding: 1rem;
                    background: white;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                    width: 100%;
                }
                
                .mobile-table td {
                    display: block;
                    border: none;
                    position: relative;
                    padding-right: 50% !important;
                    padding-top: 0.5rem !important;
                    padding-bottom: 0.5rem !important;
                    padding-left: 0.5rem !important;
                    text-align: right;
                    width: 100%;
                }
                
                .mobile-table td:before {
                    content: attr(data-label);
                    position: absolute;
                    right: 0;
                    font-weight: 600;
                    color: #08788B;
                    font-size: 0.75rem;
                    text-transform: uppercase;
                    white-space: nowrap;
                }
                
                .mobile-table td:empty:before {
                    content: '';
                }
                
                /* Hide checkbox column label on mobile */
                .mobile-table td:first-child:before {
                    content: '';
                }
                
            }
            
            /* Keep table view for larger screens */
            @media (min-width: 769px) {
                .mobile-table table {
                    display: table;
                }
                
                .mobile-table thead {
                    display: table-header-group;
                }
                
                .mobile-table tbody {
                    display: table-row-group;
                }
                
                .mobile-table tr {
                    display: table-row;
                    border: none;
                    padding: 0;
                    margin: 0;
                    box-shadow: none;
                }
                
                .mobile-table th,
                .mobile-table td {
                    display: table-cell;
                    padding: 0.75rem 1rem;
                    border: 1px solid #e2e8f0;
                }
                
                .mobile-table td:before {
                    content: none;
                }
            }
            
            /* Tablet view - keep table but optimize */
            @media (min-width: 769px) and (max-width: 1024px) {
                .mobile-table table {
                    min-width: 100%;
                }
                
                .mobile-table th,
                .mobile-table td {
                    padding: 0.5rem 0.75rem;
                    font-size: 0.875rem;
                }
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
            
            .mobile-menu-toggle {
                width: 44px;
                height: 44px;
                top: 0.75rem;
                right: 0.75rem;
            }
            
            .mobile-menu-toggle svg {
                width: 20px;
                height: 20px;
            }
            
            .sidebar {
                width: 100%;
            }
        }
        
        /* Prevent body scroll when sidebar is open */
        body.sidebar-open {
            overflow: hidden;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Mobile Menu Toggle Button -->
        <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden" onclick="toggleMobileSidebar()" aria-label="فتح/إغلاق القائمة">
            <svg id="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        
        <!-- Sidebar Overlay for Mobile -->
        <div id="sidebar-overlay" class="sidebar-overlay" onclick="closeMobileSidebar()"></div>
        
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 fixed right-0 top-0 h-full z-40 lg:translate-x-0">
            <!-- Close Button for Mobile -->
            <button onclick="closeMobileSidebar()" class="sidebar-close-btn lg:hidden" aria-label="إغلاق القائمة">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
            
            <!-- Logo -->
            <div class="sidebar-logo">
                <div class="flex items-center justify-center">
                    <img src="{{ asset('images/logo-arabic.png') }}" alt="وسيلة" class="h-12 w-auto">
                    <span class="sidebar-logo-text sidebar-text mr-3 text-xl font-bold text-white">وسيلة</span>
                </div>
                <p class="sidebar-logo-text sidebar-text text-white/80 text-sm mt-2 text-center">لوحة تحكم الإدارة</p>
            </div>
            
            <!-- Navigation -->
            <nav class="mt-4 px-3">
                <div>
                    <!-- Dashboard -->
                    <a href="{{ route('admin.dashboard') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        <span class="sidebar-text text-sm">لوحة التحكم</span>
                    </a>
                    
                    <!-- Orders -->
                    <a href="{{ route('admin.orders.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 1a1 1 0 000 2h1.22l.305 1.222a.997.997 0 00.01.042l1.358 5.43-.893.892C3.74 11.846 4.632 14 6.414 14H15a1 1 0 000-2H6.414l1-1H14a1 1 0 00.894-.553l3-6A1 1 0 0017 3H6.28l-.31-1.243A1 1 0 005 1H3zM16 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM6.5 18a1.5 1.5 0 100-3 1.5 1.5 0 000 3z"/>
                        </svg>
                        <span class="sidebar-text text-sm">الطلبات</span>
                    </a>
                    
                    <!-- Customers -->
                    <a href="{{ route('admin.customers.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                        <span class="sidebar-text text-sm">العملاء</span>
                    </a>
                    
                    <!-- Services -->
                    <a href="{{ route('admin.services.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.services.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="sidebar-text text-sm">الخدمات</span>
                    </a>
                    
                    <!-- Content Management -->
                    <a href="{{ route('admin.content-management.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.content-management.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"/>
                        </svg>
                        <span class="sidebar-text text-sm">إدارة المحتوى</span>
                    </a>
                    
                    <!-- Portfolio -->
                    <a href="{{ route('admin.portfolio.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.portfolio.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"/>
                        </svg>
                        <span class="sidebar-text text-sm">معرض الأعمال</span>
                    </a>
                    
                    <!-- Contact Messages -->
                    <a href="{{ route('admin.contact-messages.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.contact-messages.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <span class="sidebar-text text-sm flex-1">رسائل التواصل</span>
                        <span id="unread-count" class="sidebar-text bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1 min-w-[20px] text-center hidden">0</span>
                    </a>
                    
                    <!-- Customer Messages -->
                    <a href="{{ route('admin.customer.messages') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.customer.messages') || request()->routeIs('admin.customer.messages.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 5a2 2 0 012-2h7a2 2 0 012 2v4a2 2 0 01-2 2H9l-3 3v-3H4a2 2 0 01-2-2V5z"/>
                            <path d="M15 7v2a4 4 0 01-4 4H9.828l-1.766 1.767c.28.149.599.233.938.233h2l3 3v-3h2a2 2 0 002-2V9a2 2 0 00-2-2h-1z"/>
                        </svg>
                        <span class="sidebar-text text-sm">رسائل العملاء</span>
                    </a>
                    
                    <!-- MyFatoorah Management -->
                    <a href="{{ route('admin.myfatoorah.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.myfatoorah.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                        </svg>
                        <span class="sidebar-text text-sm">إدارة المدفوعات</span>
                    </a>
                    
                    <!-- Analytics -->
                    <a href="{{ route('admin.analytics.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.analytics.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                        <span class="sidebar-text text-sm">التقارير والإحصائيات</span>
                    </a>
                    
                    <!-- Settings -->
                    <a href="{{ route('admin.settings.index') }}" 
                       class="sidebar-item flex items-center px-4 py-3 {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M11.49 3.17c-.38-1.56-2.6-1.56-2.98 0a1.532 1.532 0 01-2.286.948c-1.372-.836-2.942.734-2.106 2.106.54.886.061 2.042-.947 2.287-1.561.379-1.561 2.6 0 2.978a1.532 1.532 0 01.947 2.287c-.836 1.372.734 2.942 2.106 2.106a1.532 1.532 0 012.287.947c.379 1.561 2.6 1.561 2.978 0a1.533 1.533 0 012.287-.947c1.372.836 2.942-.734 2.106-2.106a1.533 1.533 0 01.947-2.287c1.561-.379 1.561-2.6 0-2.978a1.532 1.532 0 01-.947-2.287c.836-1.372-.734-2.942-2.106-2.106a1.532 1.532 0 01-2.287-.947zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                        </svg>
                        <span class="sidebar-text text-sm">الإعدادات</span>
                    </a>
                    
                    <!-- Divider -->
                    <div class="border-t border-white/20 my-4"></div>
                    
                    <!-- Website -->
                    <a href="{{ route('home') }}" target="_blank"
                       class="sidebar-item flex items-center px-4 py-3">
                        <svg class="w-6 h-6 ml-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
                        </svg>
                        <span class="sidebar-text text-sm">الموقع الرئيسي</span>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Main Content -->
        <div id="main-content" class="main-content flex-1 mr-64">
            <!-- Top Navigation -->
            <header class="bg-white shadow-lg border-b-2 border-primary-medium/20 mobile-header sticky top-0 z-30">
                <div class="flex justify-between items-center px-4 md:px-6 py-3 md:py-4">
                    <div class="flex items-center">
                        <button onclick="toggleSidebar()" class="text-primary-medium hover:text-primary-dark ml-2 md:ml-4 hidden lg:block transition-colors duration-300 p-2 rounded-lg hover:bg-primary-medium/10">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        <h1 class="text-lg md:text-2xl font-bold text-primary-dark ml-3">@yield('page-title', 'لوحة التحكم')</h1>
                    </div>
                    
                    <div class="flex items-center gap-2 md:gap-4 flex-row-reverse">
                        <div class="hidden sm:flex items-center bg-gradient-to-l from-primary-medium/10 to-primary-light/10 rounded-full px-3 md:px-4 py-2 shadow-md border border-primary-medium/20">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-primary-medium ml-2" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                            </svg>
                            <span class="text-xs md:text-sm font-semibold text-primary-dark">مرحباً، {{ Auth::guard('admin')->user()->name }}</span>
                        </div>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center gap-1 md:gap-2 text-white bg-gradient-to-l from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 transition-all duration-300 rounded-full px-3 md:px-4 py-2 shadow-md hover:shadow-lg transform hover:scale-105" title="تسجيل الخروج">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H7a2 2 0 01-2-2V7a2 2 0 012-2h4a2 2 0 012 2v1"/>
                                </svg>
                                <span class="hidden md:inline text-sm font-semibold">تسجيل الخروج</span>
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
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const menuIcon = document.getElementById('menu-icon');
            const body = document.body;
            
            const isOpen = sidebar.classList.contains('mobile-open');
            
            if (isOpen) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                menuToggle.classList.remove('active');
                body.classList.remove('sidebar-open');
                body.style.overflow = '';
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
            } else {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                menuToggle.classList.add('active');
                body.classList.add('sidebar-open');
                body.style.overflow = 'hidden';
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>';
            }
        }
        
        function closeMobileSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const menuIcon = document.getElementById('menu-icon');
            const body = document.body;
            
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            menuToggle.classList.remove('active');
            body.classList.remove('sidebar-open');
            body.style.overflow = '';
            menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
        }
        
        // Close mobile sidebar when clicking on navigation items
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarItems = document.querySelectorAll('.sidebar-item');
            sidebarItems.forEach(item => {
                item.addEventListener('click', function() {
                    if (window.innerWidth <= 1024) {
                        closeMobileSidebar();
                    }
                });
            });
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebar-overlay');
            const menuToggle = document.getElementById('mobile-menu-toggle');
            const menuIcon = document.getElementById('menu-icon');
            const body = document.body;
            
            if (window.innerWidth > 1024) {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                menuToggle.classList.remove('active');
                body.classList.remove('sidebar-open');
                body.style.overflow = '';
                menuIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>';
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
