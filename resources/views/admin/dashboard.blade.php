@extends('admin.layouts.app')

@section('title', 'لوحة التحكم')
@section('page-title', 'لوحة التحكم')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 md:gap-6 mb-6 md:mb-8">
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.1s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-primary-gradient">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">إجمالي الطلبات</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ $stats['total_orders'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.2s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-accent-gradient">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">الطلبات المعلقة</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ $stats['pending_orders'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.3s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-gradient-to-r from-green-400 to-green-600">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">إجمالي الإيرادات</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ number_format($stats['total_revenue'], 2) }} ريال</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.4s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-gradient-to-r from-purple-400 to-purple-600">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">إجمالي الرسائل</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ $stats['total_messages'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.5s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-gradient-to-r from-red-400 to-red-600">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">الرسائل غير المقروءة</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ $stats['unread_messages'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="stat-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.4s">
        <div class="flex items-center">
            <div class="p-2 md:p-3 rounded-full bg-gradient-to-r from-blue-400 to-blue-600">
                <svg class="w-5 h-5 md:w-6 md:h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-3 md:ml-4">
                <p class="text-xs md:text-sm font-medium text-gray-600">الخدمات النشطة</p>
                <p class="text-lg md:text-2xl font-semibold text-gradient">{{ $stats['active_services'] }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
    <!-- Orders Chart -->
    <div class="chart-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.5s">
        <h3 class="text-base md:text-lg font-semibold text-gradient mb-3 md:mb-4">إحصائيات الطلبات</h3>
        <div class="chart-container">
            <canvas id="ordersChart"></canvas>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="chart-card rounded-lg shadow-lg p-4 md:p-6 mobile-card" style="animation-delay: 0.6s">
        <h3 class="text-base md:text-lg font-semibold text-gradient mb-3 md:mb-4">الإيرادات الشهرية</h3>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
</div>

<!-- Services Performance -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
    <!-- Top Services -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-4 md:p-6 mobile-card">
        <h3 class="text-base md:text-lg font-semibold text-primary-dark mb-3 md:mb-4">أكثر الخدمات طلباً</h3>
        <div class="space-y-4">
            @foreach($topServices as $service)
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary-light rounded-full flex items-center justify-center ml-3">
                        <span class="text-white font-semibold">{{ $loop->iteration }}</span>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">{{ $service->name_ar }}</p>
                        <p class="text-sm text-gray-500">{{ $service->orders_count }} طلب</p>
                    </div>
                </div>
                <span class="text-accent font-semibold">{{ number_format($service->total_revenue, 2) }} ريال</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Payment Status -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-4 md:p-6 mobile-card">
        <h3 class="text-base md:text-lg font-semibold text-primary-dark mb-3 md:mb-4">حالة المدفوعات</h3>
        <div class="chart-container">
            <canvas id="paymentStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Orders -->
<div class="dashboard-card bg-white rounded-lg shadow-lg mobile-card">
    <div class="p-4 md:p-6 border-b border-gray-200">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h2 class="text-lg md:text-xl font-semibold text-gradient">الطلبات الأخيرة</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn-enhanced text-primary-medium hover:text-primary-dark text-sm px-3 md:px-4 py-2 rounded-lg mobile-btn">
                عرض الكل
            </a>
        </div>
    </div>
    <div class="overflow-x-auto mobile-table">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
                <tr>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden sm:table-cell">العميل</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider hidden md:table-cell">التاريخ</th>
                    <th class="px-3 md:px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recent_orders as $order)
                <tr class="table-row">
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $order->order_number }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden sm:table-cell">
                        {{ $order->customer_name }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($order->total_amount, 2) }} ريال
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2 py-1 md:px-2.5 md:py-0.5 rounded-full text-xs font-medium
                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status === 'confirmed') bg-blue-100 text-blue-800
                            @elseif($order->status === 'processing') bg-purple-100 text-purple-800
                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($order->status === 'pending')
                                في الانتظار
                            @elseif($order->status === 'confirmed')
                                مؤكد
                            @elseif($order->status === 'processing')
                                قيد المعالجة
                            @elseif($order->status === 'completed')
                                مكتمل
                            @else
                                ملغي
                            @endif
                        </span>
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm text-gray-900 hidden md:table-cell">
                        {{ $order->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-3 md:px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-enhanced text-primary-medium hover:text-primary-dark px-2 md:px-3 py-1 rounded text-xs md:text-sm">
                            عرض
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        لا توجد طلبات
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
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
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
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
            backgroundColor: '#DFA340',
            borderColor: '#DFA340',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
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
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>
@endpush