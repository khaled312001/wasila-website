@extends('admin.layouts.app')

@section('title', 'التقارير والإحصائيات')
@section('page-title', 'التقارير والإحصائيات')

@section('content')
<!-- Date Range Filter -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-8">
    <div class="flex justify-between items-center">
        <h3 class="text-lg font-semibold text-primary-dark">فلترة البيانات</h3>
        <div class="flex space-x-4 space-x-reverse">
            <select id="dateRange" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-medium">
                <option value="7" {{ $dateRange == '7' ? 'selected' : '' }}>آخر 7 أيام</option>
                <option value="30" {{ $dateRange == '30' ? 'selected' : '' }}>آخر 30 يوم</option>
                <option value="90" {{ $dateRange == '90' ? 'selected' : '' }}>آخر 90 يوم</option>
                <option value="365" {{ $dateRange == '365' ? 'selected' : '' }}>آخر سنة</option>
            </select>
            <div class="flex gap-2">
                <a href="{{ route('admin.analytics.export', ['format' => 'excel', 'date_range' => $dateRange]) }}" 
                   class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                    تصدير Excel
                </a>
                <a href="{{ route('admin.analytics.export', ['format' => 'pdf', 'date_range' => $dateRange]) }}" 
                   class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V7.414A2 2 0 0015.414 6L12 2.586A2 2 0 0010.586 2H6zm5 6a1 1 0 10-2 0v3.586l-1.293-1.293a1 1 0 10-1.414 1.414l3 3a1 1 0 001.414 0l3-3a1 1 0 00-1.414-1.414L11 11.586V8z" clip-rule="evenodd"/>
                    </svg>
                    تصدير PDF
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Overall Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-primary-light">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إجمالي الطلبات</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['total_orders'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-accent">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['total_revenue'] ?? 0, 2) }} ريال</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إجمالي العملاء</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['total_users'] ?? 0 }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">الخدمات النشطة</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['total_services'] ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Monthly Revenue Chart -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">الإيرادات الشهرية</h3>
        <div class="chart-container">
            <canvas id="monthlyRevenueChart"></canvas>
        </div>
    </div>
    
    <!-- Orders by Status -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">الطلبات حسب الحالة</h3>
        <div class="chart-container">
            <canvas id="ordersByStatusChart"></canvas>
        </div>
    </div>
</div>

<!-- Daily Orders and Payment Methods -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Daily Orders -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">الطلبات اليومية</h3>
        <div class="chart-container">
            <canvas id="dailyOrdersChart"></canvas>
        </div>
    </div>
    
    <!-- Payment Methods -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">طرق الدفع</h3>
        <div class="chart-container">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
    </div>
</div>

<!-- Top Services -->
<div class="bg-white rounded-lg shadow-lg card-shadow p-6">
    <h3 class="text-lg font-semibold text-primary-dark mb-4">أكثر الخدمات طلباً</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الخدمة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد الطلبات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي الإيرادات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">متوسط الطلب</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($topServices ?? [] as $service)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $service->name_ar }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $service->order_items_count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($service->order_items_sum_total_price ?? 0, 2) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $service->order_items_count > 0 ? number_format(($service->order_items_sum_total_price ?? 0) / $service->order_items_count, 2) : 0 }} ريال
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                        لا توجد بيانات
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
// Monthly Revenue Chart
const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
new Chart(monthlyRevenueCtx, {
    type: 'line',
    data: {
        labels: {!! json_encode(($monthlyRevenue ?? collect())->pluck('month')->map(function($month) {
            return \Carbon\Carbon::create()->month($month)->format('M');
        })) !!},
        datasets: [{
            label: 'الإيرادات (ريال)',
            data: {!! json_encode(($monthlyRevenue ?? collect())->pluck('total')) !!},
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

// Orders by Status Chart
const ordersByStatusCtx = document.getElementById('ordersByStatusChart').getContext('2d');
new Chart(ordersByStatusCtx, {
    type: 'doughnut',
    data: {
        labels: {!! json_encode(($ordersByStatus ?? collect())->pluck('status')) !!},
        datasets: [{
            data: {!! json_encode(($ordersByStatus ?? collect())->pluck('count')) !!},
            backgroundColor: ['#F59E0B', '#3B82F6', '#10B981', '#EF4444', '#8B5CF6'],
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

// Daily Orders Chart
const dailyOrdersCtx = document.getElementById('dailyOrdersChart').getContext('2d');
new Chart(dailyOrdersCtx, {
    type: 'bar',
    data: {
        labels: {!! json_encode(($dailyOrders ?? collect())->pluck('date')) !!},
        datasets: [{
            label: 'عدد الطلبات',
            data: {!! json_encode(($dailyOrders ?? collect())->pluck('count')) !!},
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

// Payment Methods Chart
const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
new Chart(paymentMethodsCtx, {
    type: 'pie',
    data: {
        labels: {!! json_encode(($paymentMethods ?? collect())->pluck('payment_method')) !!},
        datasets: [{
            data: {!! json_encode(($paymentMethods ?? collect())->pluck('count')) !!},
            backgroundColor: ['#10B981', '#3B82F6', '#F59E0B', '#EF4444'],
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

// Date range change
document.getElementById('dateRange').addEventListener('change', function() {
    const dateRange = this.value;
    window.location.href = '{{ route("admin.analytics.index") }}?date_range=' + dateRange;
});

// Export buttons are now direct links, no JavaScript needed
</script>
@endpush
