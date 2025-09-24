@extends('admin.layouts.app')

@section('title', 'إدارة المدفوعات - MyFatoorah')
@section('page-title', 'إدارة المدفوعات')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-green-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4zM18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إجمالي المدفوعات</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['total_payments'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-yellow-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">المدفوعات المعلقة</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['pending_payments'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-red-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">المدفوعات الفاشلة</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ $stats['failed_payments'] }}</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-primary-medium">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"/>
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إجمالي الإيرادات</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['total_revenue'], 2) }} ريال</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-accent">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إيرادات اليوم</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['today_revenue'], 2) }} ريال</p>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-blue-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">إيرادات الشهر</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['monthly_revenue'], 2) }} ريال</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-purple-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">متوسط قيمة الطلب</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['average_order_value'], 2) }} ريال</p>
            </div>
        </div>
    </div>
    
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex items-center">
            <div class="p-3 rounded-full bg-orange-500">
                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                </svg>
            </div>
            <div class="ml-4">
                <p class="text-sm font-medium text-gray-600">المبالغ المستردة</p>
                <p class="text-2xl font-semibold text-primary-dark">{{ number_format($stats['total_refunded'], 2) }} ريال</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Revenue Chart -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">إيرادات آخر 30 يوم</h3>
        <div class="chart-container">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    
    <!-- Payment Methods Chart -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">طرق الدفع المستخدمة</h3>
        <div class="chart-container">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
    </div>
</div>

<!-- Payment Methods Statistics -->
@if($payment_methods_stats->count() > 0)
<div class="bg-white rounded-lg shadow-lg card-shadow p-6 mb-8">
    <h3 class="text-lg font-semibold text-primary-dark mb-4">إحصائيات طرق الدفع</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">عدد المعاملات</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">إجمالي المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">متوسط المعاملة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">النسبة المئوية</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($payment_methods_stats as $method)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $method->payment_method }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $method->count }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($method->total, 2) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($method->total / $method->count, 2) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format(($method->count / $stats['total_payments']) * 100, 1) }}%
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif

<!-- Connection Test & Actions -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Connection Test -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-primary-dark">اختبار الاتصال</h3>
            <button onclick="testConnection()" class="btn-primary text-white px-4 py-2 rounded-lg">
                اختبار الاتصال
            </button>
        </div>
        <div id="connection-result" class="hidden p-4 rounded-lg"></div>
    </div>
    
    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h3 class="text-lg font-semibold text-primary-dark mb-4">إجراءات سريعة</h3>
        <div class="space-y-3">
            <a href="{{ route('admin.myfatoorah.transactions') }}" class="block w-full bg-primary-medium text-white px-4 py-2 rounded-lg text-center hover:bg-primary-dark transition duration-300">
                عرض جميع المعاملات
            </a>
            <a href="{{ route('admin.myfatoorah.export') }}" class="block w-full bg-green-600 text-white px-4 py-2 rounded-lg text-center hover:bg-green-700 transition duration-300">
                تصدير المعاملات
            </a>
            <a href="{{ route('admin.myfatoorah.settings') }}" class="block w-full bg-accent text-white px-4 py-2 rounded-lg text-center hover:bg-yellow-600 transition duration-300">
                إعدادات بوابة الدفع
            </a>
        </div>
    </div>
</div>

<!-- Recent Payments -->
<div class="bg-white rounded-lg shadow-lg card-shadow">
    <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold text-primary-dark">المدفوعات الأخيرة</h2>
            <a href="{{ route('admin.myfatoorah.transactions') }}" class="text-primary-medium hover:text-primary-dark text-sm">
                عرض الكل
            </a>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">رقم الطلب</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">العميل</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">المبلغ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">طريقة الدفع</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الحالة</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">التاريخ</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">الإجراءات</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($recent_payments as $payment)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                        {{ $payment->order_number }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->customer_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ number_format($payment->total_amount, 2) }} ريال
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->payment_method ?? 'غير محدد' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($payment->payment_status === 'paid') bg-green-100 text-green-800
                            @elseif($payment->payment_status === 'pending') bg-yellow-100 text-yellow-800
                            @else bg-red-100 text-red-800
                            @endif">
                            @if($payment->payment_status === 'paid')
                                مدفوع
                            @elseif($payment->payment_status === 'pending')
                                في الانتظار
                            @else
                                فشل
                            @endif
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        {{ $payment->updated_at->format('Y-m-d H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <a href="{{ route('admin.myfatoorah.show', $payment) }}" class="text-primary-medium hover:text-primary-dark">
                            عرض التفاصيل
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        لا توجد مدفوعات
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
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: [
            @foreach($daily_revenue as $day)
                '{{ $day->date }}',
            @endforeach
        ],
        datasets: [{
            label: 'الإيرادات (ريال)',
            data: [
                @foreach($daily_revenue as $day)
                    {{ $day->revenue }},
                @endforeach
            ],
            borderColor: '#08788B',
            backgroundColor: 'rgba(8, 120, 139, 0.1)',
            borderWidth: 2,
            fill: true,
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
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return value.toLocaleString() + ' ريال';
                    }
                }
            }
        }
    }
});

// Payment Methods Chart
const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
const paymentMethodsChart = new Chart(paymentMethodsCtx, {
    type: 'doughnut',
    data: {
        labels: [
            @foreach($payment_methods_stats as $method)
                '{{ $method->payment_method }}',
            @endforeach
        ],
        datasets: [{
            data: [
                @foreach($payment_methods_stats as $method)
                    {{ $method->count }},
                @endforeach
            ],
            backgroundColor: [
                '#08788B',
                '#3CA6B4',
                '#DFA340',
                '#025469',
                '#F4B942',
                '#60A5FA',
                '#F87171',
                '#34D399'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    padding: 20,
                    usePointStyle: true
                }
            }
        }
    }
});

function testConnection() {
    const resultDiv = document.getElementById('connection-result');
    resultDiv.classList.remove('hidden');
    resultDiv.innerHTML = '<div class="text-blue-600">جاري اختبار الاتصال...</div>';
    
    fetch('{{ route("admin.myfatoorah.test-connection") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            resultDiv.innerHTML = `
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    <strong>نجح الاتصال!</strong> ${data.message}
                    ${data.payment_methods_count ? `<br>عدد طرق الدفع المتاحة: ${data.payment_methods_count}` : ''}
                </div>
            `;
        } else {
            resultDiv.innerHTML = `
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <strong>فشل الاتصال!</strong> ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        resultDiv.innerHTML = `
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <strong>خطأ!</strong> حدث خطأ أثناء اختبار الاتصال
            </div>
        `;
    });
}
</script>
@endpush
