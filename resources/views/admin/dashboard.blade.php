@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@push('styles')
<style>
    .stat-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        right: 0;
        width: 100px;
        height: 100px;
        background: radial-gradient(circle, rgba(8, 120, 139, 0.1) 0%, transparent 70%);
        border-radius: 50%;
        transform: translate(30%, -30%);
    }
    
    .stat-card-modern:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.15);
        border-color: rgba(8, 120, 139, 0.3);
    }
    
    .stat-icon-wrapper {
        width: 56px;
        height: 56px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
        z-index: 1;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin: 0.5rem 0;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #475569;
        font-weight: 600;
    }
    
    .chart-card-modern {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
    }
    
    .chart-card-modern:hover {
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }
    
    .chart-title {
        font-size: 1.25rem;
        font-weight: 700;
        color: #025469;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .chart-title::before {
        content: '';
        width: 4px;
        height: 24px;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        border-radius: 2px;
    }
    
    .service-item-modern {
        padding: 1rem;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8, 120, 139, 0.1);
        transition: all 0.3s ease;
        margin-bottom: 0.75rem;
    }
    
    .service-item-modern:hover {
        transform: translateX(-4px);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.1);
        border-color: rgba(8, 120, 139, 0.3);
    }
    
    .service-rank {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: white;
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
        box-shadow: 0 4px 12px rgba(8, 120, 139, 0.3);
    }
    
    .table-modern {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    }
    
    .table-modern thead {
        background: linear-gradient(135deg, #025469 0%, #08788B 100%);
    }
    
    .table-modern thead th {
        color: white;
        font-weight: 600;
        padding: 1rem 1.5rem;
        text-align: right;
    }
    
    .table-modern tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }
    
    .table-modern tbody tr:hover {
        background: #f8fafc;
        transform: scale(1.01);
    }
    
    .table-modern tbody td {
        padding: 1rem 1.5rem;
        color: #1e293b;
    }
    
    .table-modern thead th {
        color: #ffffff;
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
    }
    
    .status-badge-modern {
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    .pulse-animation {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: .5;
        }
    }
    
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush

@section('content')
<!-- Welcome Section -->
<div class="mb-8 fade-in">
    <div class="rounded-2xl shadow-2xl p-8 relative overflow-hidden" style="background: linear-gradient(135deg, #08788B 0%, #025469 50%, #08788B 100%);">
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full blur-3xl"></div>
        <div class="relative z-10">
            <h1 class="text-3xl font-bold mb-2" style="color: #ffffff;">مرحباً بك في لوحة التحكم</h1>
            <p class="text-lg" style="color: rgba(255, 255, 255, 0.95);">إدارة شاملة لجميع عمليات المنصة</p>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-8">
    <!-- Total Orders -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.1s">
        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #025469 0%, #08788B 100%);">
            <i class="fas fa-shopping-cart text-white text-xl"></i>
        </div>
        <p class="stat-label">إجمالي الطلبات</p>
        <p class="stat-value">{{ $stats['total_orders'] }}</p>
        <div class="flex items-center gap-1 mt-2 text-xs text-green-600">
            <i class="fas fa-arrow-up"></i>
            <span>نشط</span>
        </div>
    </div>
    
    <!-- Pending Orders -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.2s">
        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
            <i class="fas fa-clock text-white text-xl"></i>
        </div>
        <p class="stat-label">الطلبات المعلقة</p>
        <p class="stat-value" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $stats['pending_orders'] }}</p>
        <div class="flex items-center gap-1 mt-2 text-xs text-yellow-600">
            <i class="fas fa-exclamation-circle"></i>
            <span>يتطلب مراجعة</span>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.3s">
        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <i class="fas fa-money-bill-wave text-white text-xl"></i>
        </div>
        <p class="stat-label">إجمالي الإيرادات</p>
        <p class="stat-value" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ number_format($stats['total_revenue'], 2) }}</p>
        <p class="text-xs text-gray-500 mt-1">ريال سعودي</p>
    </div>
    
    <!-- Total Messages -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.4s">
        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);">
            <i class="fas fa-comments text-white text-xl"></i>
        </div>
        <p class="stat-label">إجمالي الرسائل</p>
        <p class="stat-value" style="background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $stats['total_messages'] }}</p>
        <div class="flex items-center gap-1 mt-2 text-xs text-purple-600">
            <i class="fas fa-comment-dots"></i>
            <span>رسائل</span>
        </div>
    </div>
    
    <!-- Unread Messages -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.5s">
        <div class="stat-icon-wrapper pulse-animation" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <i class="fas fa-envelope text-white text-xl"></i>
        </div>
        <p class="stat-label">الرسائل غير المقروءة</p>
        <p class="stat-value" style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $stats['unread_messages'] }}</p>
        @if($stats['unread_messages'] > 0)
        <div class="flex items-center gap-1 mt-2 text-xs" style="color: #08788B;">
            <i class="fas fa-bell"></i>
            <span>جديد</span>
        </div>
        @endif
    </div>
    
    <!-- Active Services -->
    <div class="stat-card-modern fade-in" style="animation-delay: 0.6s">
        <div class="stat-icon-wrapper" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <i class="fas fa-cogs text-white text-xl"></i>
        </div>
        <p class="stat-label">الخدمات النشطة</p>
        <p class="stat-value" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">{{ $stats['active_services'] }}</p>
        <div class="flex items-center gap-1 mt-2 text-xs text-blue-600">
            <i class="fas fa-check-circle"></i>
            <span>نشط</span>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Orders Chart -->
    <div class="chart-card-modern fade-in" style="animation-delay: 0.7s">
        <h3 class="chart-title">إحصائيات الطلبات</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="chart-card-modern fade-in" style="animation-delay: 0.8s">
        <h3 class="chart-title">الإيرادات الشهرية</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Services Performance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Top Services -->
    <div class="chart-card-modern fade-in" style="animation-delay: 0.9s">
        <h3 class="chart-title">أكثر الخدمات طلباً</h3>
        <div class="space-y-3">
            @foreach($topServices as $service)
            <div class="service-item-modern">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="service-rank">{{ $loop->iteration }}</div>
                        <div>
                            <p class="font-semibold text-lg" style="color: #1e293b;">{{ $service->name_ar }}</p>
                            <p class="text-sm mt-1" style="color: #475569;">
                                <i class="fas fa-shopping-cart ml-1"></i>
                                {{ $service->orders_count }} طلب
                            </p>
                        </div>
                    </div>
                    <div class="text-left">
                        <p class="text-lg font-bold text-primary-dark">{{ number_format($service->total_revenue, 2) }}</p>
                        <p class="text-xs" style="color: #64748b;">ريال</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Payment Status -->
    <div class="chart-card-modern fade-in" style="animation-delay: 1s">
        <h3 class="chart-title">حالة المدفوعات</h3>
        <div class="chart-container" style="height: 300px;">
            <canvas id="paymentStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="table-modern fade-in" style="animation-delay: 1.1s">
    <div class="p-6 border-b border-gray-200 bg-gradient-to-r from-gray-50 to-white">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="text-xl font-bold flex items-center gap-2" style="color: #1e293b;">
                <i class="fas fa-list text-primary-medium"></i>
                الطلبات الأخيرة
            </h2>
            <a href="{{ route('admin.orders.index') }}" class="bg-gradient-to-r from-primary-medium to-primary-dark text-white px-6 py-2 rounded-lg font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-200">
                <i class="fas fa-eye ml-2"></i>
                عرض الكل
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-right text-sm font-semibold">رقم الطلب</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold hidden sm:table-cell">العميل</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold">المبلغ</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold">الحالة</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold hidden md:table-cell">التاريخ</th>
                    <th class="px-6 py-4 text-right text-sm font-semibold">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recent_orders as $order)
                <tr>
                    <td class="px-6 py-4">
                        <span class="font-semibold" style="color: #1e293b;">#{{ $order->order_number }}</span>
                    </td>
                    <td class="px-6 py-4 hidden sm:table-cell">
                        <span style="color: #334155;">{{ $order->customer_name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-bold text-primary-dark">{{ number_format($order->total_amount, 2) }} ريال</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="status-badge-modern
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($order->status === 'pending')
                                <i class="fas fa-clock"></i>
                                في الانتظار
                            @elseif($order->status === 'confirmed')
                                <i class="fas fa-check-circle"></i>
                                مؤكد
                            @elseif($order->status === 'processing')
                                <i class="fas fa-spinner"></i>
                                قيد المعالجة
                            @elseif($order->status === 'completed')
                                <i class="fas fa-check-double"></i>
                                مكتمل
                            @else
                                <i class="fas fa-times-circle"></i>
                                ملغي
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 hidden md:table-cell">
                        <span class="text-sm" style="color: #475569;">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.orders.show', $order) }}" class="bg-gradient-to-r from-primary-light to-primary-medium text-white px-4 py-2 rounded-lg text-sm font-semibold hover:shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center gap-2">
                            <i class="fas fa-eye"></i>
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <i class="fas fa-inbox text-4xl mb-4" style="color: #94a3b8;"></i>
                            <p class="text-lg" style="color: #475569;">لا توجد طلبات</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Chart data from server
const completedOrders = {{ $stats['completed_orders'] ?? 0 }};
const pendingOrders = {{ $stats['pending_orders'] ?? 0 }};

// Orders Chart
const ordersCtx = document.getElementById('ordersChart').getContext('2d');
new Chart(ordersCtx, {
    type: 'line',
    data: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        datasets: [{
            label: 'الطلبات',
            data: [12, 19, 3, 5, 2, 3],
            borderColor: '#08788B',
            backgroundColor: 'rgba(8, 120, 139, 0.1)',
            tension: 0.4,
            fill: true,
            pointBackgroundColor: '#08788B',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 6
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
new Chart(revenueCtx, {
    type: 'bar',
    data: {
        labels: ['يناير', 'فبراير', 'مارس', 'أبريل', 'مايو', 'يونيو'],
        datasets: [{
            label: 'الإيرادات (ريال)',
            data: [12000, 19000, 3000, 5000, 2000, 3000],
            backgroundColor: 'rgba(223, 163, 64, 0.8)',
            borderColor: '#DFA340',
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: {
                    color: 'rgba(0, 0, 0, 0.05)'
                }
            },
            x: {
                grid: {
                    display: false
                }
            }
        }
    }
});

// Payment Status Chart
const paymentCtx = document.getElementById('paymentStatusChart').getContext('2d');
new Chart(paymentCtx, {
    type: 'doughnut',
    data: {
        labels: ['مدفوع', 'في الانتظار', 'فشل'],
        datasets: [{
            data: [completedOrders, pendingOrders, 0],
            backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
            borderWidth: 0,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 15,
                    font: {
                        size: 12,
                        weight: 'bold'
                    }
                }
            }
        }
    }
});
</script>
@endpush
