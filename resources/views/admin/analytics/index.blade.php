@extends('admin.layouts.app')

@section('title', 'التقارير المتقدمة')
@section('page-title', 'التقارير والتحليلات')

@push('styles')
<style>
    .rep-toolbar {
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        border: 1px solid rgba(8,120,139,.1);
        border-radius: 16px;
        padding: 1rem 1.25rem;
        box-shadow: 0 6px 22px rgba(0,0,0,.05);
        margin-bottom: 1.5rem;
    }
    .rep-period-pills { display: flex; flex-wrap: wrap; gap: .4rem; }
    .rep-pill {
        padding: .55rem 1rem;
        border-radius: 10px;
        background: #f1f5f9;
        color: #1e293b;
        font-weight: 600;
        font-size: .9rem;
        cursor: pointer; user-select: none;
        border: 1.5px solid transparent;
        transition: all .2s;
        display: inline-flex; align-items: center; gap: .35rem;
    }
    .rep-pill:hover { background: #e2e8f0; }
    .rep-pill.active {
        background: linear-gradient(135deg, #08788B, #025469);
        color: #fff; border-color: #025469;
        box-shadow: 0 4px 12px rgba(8,120,139,.35);
    }

    .rep-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: .75rem; margin-top: .85rem; }
    .rep-field label { display:block; font-weight: 600; color: #475569; font-size: .8rem; margin-bottom: .25rem; }
    .rep-field input, .rep-field select {
        width: 100%;
        padding: .55rem .75rem;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        font-size: .9rem;
    }
    .rep-field input:focus, .rep-field select:focus {
        outline: none;
        border-color: #08788B;
        box-shadow: 0 0 0 3px rgba(8,120,139,.15);
    }
    .rep-actions {
        display: flex; gap: .5rem; flex-wrap: wrap;
        align-items: flex-end; margin-top: .85rem;
    }
    .btn-rep {
        padding: .6rem 1rem;
        border-radius: 10px; font-weight: 700; font-size: .9rem;
        display: inline-flex; align-items: center; gap: .4rem;
        border: 0; cursor: pointer; text-decoration: none;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .btn-rep:hover { transform: translateY(-1px); }
    .btn-rep-primary { background: linear-gradient(135deg, #08788B, #025469); color: #fff; }
    .btn-rep-ghost   { background: #f1f5f9; color: #1e293b; }
    .btn-rep-success { background: linear-gradient(135deg, #10b981, #059669); color: #fff; }
    .btn-rep-danger  { background: linear-gradient(135deg, #ef4444, #dc2626); color: #fff; }

    .rep-summary {
        font-size: .85rem; color: #64748b;
        margin-top: .65rem;
        padding-top: .65rem;
        border-top: 1px dashed #e2e8f0;
    }
    .rep-summary strong { color: #08788B; }

    .kpi-grid {
        display: grid; gap: 1rem;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        margin-bottom: 1.5rem;
    }
    .kpi-card {
        background: #fff; border-radius: 14px; padding: 1rem 1.25rem;
        border: 1px solid #e6eef1;
        box-shadow: 0 4px 16px rgba(0,0,0,.05);
        position: relative; overflow: hidden;
    }
    .kpi-card::before {
        content:''; position:absolute; inset:0 0 auto 0; height: 4px;
        background: linear-gradient(135deg, #08788B, #025469);
    }
    .kpi-label { font-size: .8rem; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: .3px; }
    .kpi-value { font-size: 1.85rem; font-weight: 800; color: #025469; margin: .35rem 0 .15rem; }
    .kpi-meta  { font-size: .85rem; color: #475569; }
    .kpi-trend { font-size: .8rem; font-weight: 700; display: inline-flex; align-items: center; gap: .25rem; padding: 2px 8px; border-radius: 6px; }
    .kpi-trend.up   { background: #d1fae5; color: #065f46; }
    .kpi-trend.down { background: #fee2e2; color: #991b1b; }
    .kpi-trend.flat { background: #f1f5f9; color: #475569; }

    .chart-card {
        background: #fff; border-radius: 16px; padding: 1.25rem;
        border: 1px solid #e6eef1; box-shadow: 0 4px 16px rgba(0,0,0,.05);
        margin-bottom: 1.25rem;
    }
    .print-chart-image { display: none; width: 100%; max-height: 330px; object-fit: contain; }
    .chart-card h3 {
        font-size: 1.05rem; font-weight: 700; color: #025469;
        margin: 0 0 1rem; display: flex; align-items: center; gap: .55rem;
    }
    .chart-card h3::before {
        content: ''; width: 4px; height: 22px; border-radius: 4px;
        background: linear-gradient(135deg, #08788B, #025469);
    }

    .grid-2 { display: grid; grid-template-columns: 2fr 1fr; gap: 1.25rem; }
    @media (max-width: 1024px) { .grid-2 { grid-template-columns: 1fr; } }

    table.rep-table { width:100%; border-collapse: separate; border-spacing: 0; }
    table.rep-table th, table.rep-table td {
        padding: .7rem .9rem;
        text-align: right; font-size: .9rem;
        border-bottom: 1px solid #f1f5f9;
    }
    table.rep-table thead th {
        background: #f8fafc; color: #475569;
        font-weight: 700; text-transform: uppercase; font-size: .75rem;
        letter-spacing: .3px;
    }
    table.rep-table tbody tr:hover { background: #f8fafc; }

    .badge-soft {
        padding: 3px 9px; border-radius: 999px;
        font-size: .75rem; font-weight: 700;
        display: inline-block;
    }
    .b-pending  { background: #fef3c7; color: #92400e; }
    .b-paid     { background: #d1fae5; color: #065f46; }
    .b-failed   { background: #fee2e2; color: #991b1b; }
    .b-confirmed{ background: #dbeafe; color: #1e40af; }
    .b-completed{ background: #d1fae5; color: #065f46; }
    .b-cancelled{ background: #fee2e2; color: #991b1b; }
    .b-processing{ background: #ede9fe; color: #5b21b6; }
</style>
@endpush

@section('content')
<form id="repForm" method="GET" action="{{ route('admin.analytics.index') }}">
    <!-- Filters toolbar -->
    <div class="rep-toolbar">
        <div class="flex flex-wrap items-center gap-3 justify-between mb-2">
            <div class="rep-period-pills">
                @php $period = $filters['period'] ?? 'monthly'; @endphp
                <span class="rep-pill {{ $period === 'daily' ? 'active' : '' }}" data-period="daily"><i class="fas fa-clock"></i> اليوم</span>
                <span class="rep-pill {{ $period === 'weekly' ? 'active' : '' }}" data-period="weekly"><i class="fas fa-calendar-week"></i> أسبوعي</span>
                <span class="rep-pill {{ $period === 'monthly' ? 'active' : '' }}" data-period="monthly"><i class="fas fa-calendar-alt"></i> شهري</span>
                <span class="rep-pill {{ $period === 'yearly' ? 'active' : '' }}" data-period="yearly"><i class="fas fa-calendar"></i> سنوي</span>
                <span class="rep-pill {{ $period === 'custom' ? 'active' : '' }}" data-period="custom"><i class="fas fa-sliders-h"></i> مخصّص</span>
            </div>
            <div class="text-sm" style="color:#64748b">
                <i class="fas fa-info-circle"></i>
                <span>{{ $bucketLabel }}</span>
            </div>
        </div>
        <input type="hidden" name="period" id="periodInput" value="{{ $period }}">

        <div class="rep-row">
            <div class="rep-field" id="dateFromWrap" style="{{ $period === 'custom' ? '' : 'display:none' }}">
                <label>من تاريخ</label>
                <input type="date" name="from" value="{{ $filters['from'] }}">
            </div>
            <div class="rep-field" id="dateToWrap" style="{{ $period === 'custom' ? '' : 'display:none' }}">
                <label>إلى تاريخ</label>
                <input type="date" name="to" value="{{ $filters['to'] }}">
            </div>
            <div class="rep-field" id="monthWrap" style="{{ $period === 'monthly' ? '' : 'display:none' }}">
                <label>اختر الشهر</label>
                <input type="month" name="month" value="{{ $filters['month'] ?? now()->format('Y-m') }}" max="{{ now()->format('Y-m') }}">
            </div>
            <div class="rep-field">
                <label>الخدمة</label>
                <select name="service_id">
                    <option value="">— الكل —</option>
                    @foreach($services as $svc)
                        <option value="{{ $svc->id }}" {{ ($filters['service_id'] ?? '') == $svc->id ? 'selected' : '' }}>{{ $svc->name_ar }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rep-field">
                <label>حالة الطلب</label>
                <select name="status">
                    <option value="">— الكل —</option>
                    @foreach(['pending'=>'في الانتظار','confirmed'=>'مؤكد','processing'=>'قيد المعالجة','completed'=>'مكتمل','cancelled'=>'ملغي'] as $k=>$v)
                        <option value="{{ $k }}" {{ ($filters['status'] ?? '')===$k ? 'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
            <div class="rep-field">
                <label>حالة الدفع</label>
                <input type="hidden" name="payment_status" value="paid">
                <select disabled>
                    <option value="paid" selected>مدفوع فقط</option>
                </select>
            </div>
            <div class="rep-field">
                <label>طريقة الدفع</label>
                <select name="payment_method">
                    <option value="">— الكل —</option>
                    @foreach(['MyFatoorah'=>'MyFatoorah','cod'=>'الدفع عند الاستلام','bank'=>'تحويل بنكي'] as $k=>$v)
                        <option value="{{ $k }}" {{ ($filters['payment_method'] ?? '')===$k ? 'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="rep-actions">
            <button type="submit" class="btn-rep btn-rep-primary"><i class="fas fa-filter"></i> تطبيق الفلاتر</button>
            <a href="{{ route('admin.analytics.index') }}" class="btn-rep btn-rep-ghost"><i class="fas fa-undo"></i> إعادة ضبط</a>
            <a id="exportExcel" href="#" class="btn-rep btn-rep-success"><i class="fas fa-file-excel"></i> تصدير Excel</a>
            <a id="exportPdf"   href="#" class="btn-rep btn-rep-danger"><i class="fas fa-file-pdf"></i> تصدير PDF</a>
            <button type="button" id="printReport" class="btn-rep btn-rep-ghost"><i class="fas fa-print"></i> طباعة</button>
        </div>

        <div class="rep-summary">
            <strong>الفترة:</strong>
            من <strong>{{ $startDate->format('Y-m-d') }}</strong>
            إلى <strong>{{ $endDate->format('Y-m-d') }}</strong>
        </div>
    </div>
</form>

<!-- KPIs -->
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">إجمالي الطلبات</div>
        <div class="kpi-value">{{ number_format($kpis['total_orders']) }}</div>
        <div class="kpi-meta">
            @php $tr = $kpis['orders_change_pct']; $cls = $tr > 0 ? 'up' : ($tr < 0 ? 'down' : 'flat'); @endphp
            <span class="kpi-trend {{ $cls }}">
                <i class="fas fa-{{ $tr > 0 ? 'arrow-up' : ($tr < 0 ? 'arrow-down' : 'minus') }}"></i>
                {{ $tr }}%
            </span>
            <span style="margin-inline-start:6px">مقارنة بالفترة السابقة</span>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">الإيرادات (مدفوع)</div>
        <div class="kpi-value">{{ number_format($kpis['total_revenue'], 2) }}</div>
        <div class="kpi-meta">
            @php $tr = $kpis['revenue_change_pct']; $cls = $tr > 0 ? 'up' : ($tr < 0 ? 'down' : 'flat'); @endphp
            <span class="kpi-trend {{ $cls }}">
                <i class="fas fa-{{ $tr > 0 ? 'arrow-up' : ($tr < 0 ? 'arrow-down' : 'minus') }}"></i>
                {{ $tr }}%
            </span>
            <span style="margin-inline-start:6px">ريال سعودي</span>
        </div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">طلبات مدفوعة</div>
        <div class="kpi-value">{{ number_format($kpis['paid_orders']) }}</div>
        <div class="kpi-meta">معدّل التحويل: <strong>{{ $kpis['conversion_rate'] }}%</strong></div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">متوسط قيمة الطلب</div>
        <div class="kpi-value">{{ number_format($kpis['avg_order_value'], 2) }}</div>
        <div class="kpi-meta">ريال / طلب مدفوع</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">عملاء فريدين</div>
        <div class="kpi-value">{{ number_format($kpis['unique_customers']) }}</div>
        <div class="kpi-meta">+ <strong>{{ $kpis['new_customers'] }}</strong> عميل جديد</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">معلّقة / مكتمل / ملغى</div>
        <div class="kpi-value" style="font-size:1.4rem">
            {{ $kpis['pending_orders'] }} <span style="opacity:.4">/</span>
            {{ $kpis['completed_orders'] }} <span style="opacity:.4">/</span>
            {{ $kpis['cancelled_orders'] }}
        </div>
        <div class="kpi-meta">داخل الفترة المحددة</div>
    </div>
</div>

<!-- Time series + status -->
<div class="grid-2">
    <div class="chart-card">
        <h3>الإيرادات مقابل عدد الطلبات</h3>
        <div style="height: 320px"><canvas id="tsChart"></canvas></div>
    </div>
    <div class="chart-card">
        <h3>توزيع حالات الطلب</h3>
        <div style="height: 320px"><canvas id="statusChart"></canvas></div>
    </div>
</div>

<div class="grid-2">
    <div class="chart-card">
        <h3>أكثر 10 خدمات</h3>
        @if($topServices->count() > 0)
        <table class="rep-table">
            <thead>
                <tr>
                    <th>#</th><th>الخدمة</th><th>عدد الطلبات</th><th>الإيرادات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topServices as $svc)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $svc->name_ar }}</td>
                    <td>{{ number_format($svc->orders_count) }}</td>
                    <td>{{ number_format($svc->revenue ?? 0, 2) }} ر.س</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8">لا توجد بيانات في الفترة المحدّدة.</p>
        @endif
    </div>
    <div class="chart-card">
        <h3>توزيع طرق الدفع</h3>
        @if($paymentMethods->count() > 0)
        <table class="rep-table">
            <thead><tr><th>الطريقة</th><th>عدد</th><th>الإجمالي</th></tr></thead>
            <tbody>
                @foreach($paymentMethods as $pm)
                <tr>
                    <td>{{ $pm->payment_method }}</td>
                    <td>{{ number_format($pm->count) }}</td>
                    <td>{{ number_format($pm->total ?? 0, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8">لا توجد بيانات.</p>
        @endif
    </div>
</div>

<div class="grid-2">
    <div class="chart-card">
        <h3>أكثر العملاء إنفاقاً</h3>
        @if($topCustomers->count() > 0)
        <table class="rep-table">
            <thead><tr><th>#</th><th>العميل</th><th>طلبات</th><th>الإيرادات</th></tr></thead>
            <tbody>
                @foreach($topCustomers as $c)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $c->customer_name }}<br><small style="color:#94a3b8">{{ $c->customer_email }}</small></td>
                    <td>{{ number_format($c->orders) }}</td>
                    <td>{{ number_format($c->revenue, 2) }} ر.س</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8">لا توجد بيانات.</p>
        @endif
    </div>
    <div class="chart-card">
        <h3>أحدث 15 طلب</h3>
        @if($recentOrders->count() > 0)
        <table class="rep-table">
            <thead><tr><th>الطلب</th><th>العميل</th><th>المبلغ</th><th>الحالة</th></tr></thead>
            <tbody>
                @foreach($recentOrders as $o)
                <tr>
                    <td>#{{ $o->order_number }}<br><small style="color:#94a3b8">{{ $o->created_at->format('Y-m-d H:i') }}</small></td>
                    <td>{{ $o->customer_name }}</td>
                    <td>{{ number_format($o->total_amount, 2) }}</td>
                    <td><span class="badge-soft b-{{ $o->status }}">{{ $o->status }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p style="color:#94a3b8">لا توجد طلبات.</p>
        @endif
    </div>
</div>

@php
    $allStatuses = ['pending','confirmed','processing','completed','cancelled'];
    $statusLabels = ['pending'=>'في الانتظار','confirmed'=>'مؤكد','processing'=>'قيد المعالجة','completed'=>'مكتمل','cancelled'=>'ملغي'];
    $statusValues = [];
    $statusLabelsOut = [];
    foreach ($allStatuses as $s) {
        if (($statusBreakdown[$s] ?? 0) > 0) {
            $statusValues[] = $statusBreakdown[$s];
            $statusLabelsOut[] = $statusLabels[$s];
        }
    }
@endphp
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
(function() {
    // Period pills toggle custom date range
    const pills = document.querySelectorAll('.rep-pill');
    const periodInput = document.getElementById('periodInput');
    const fromWrap = document.getElementById('dateFromWrap');
    const toWrap   = document.getElementById('dateToWrap');
    const monthWrap = document.getElementById('monthWrap');
    pills.forEach(p => p.addEventListener('click', () => {
        pills.forEach(x => x.classList.remove('active'));
        p.classList.add('active');
        const v = p.dataset.period;
        periodInput.value = v;
        if (v === 'custom') {
            fromWrap.style.display = '';
            toWrap.style.display = '';
            monthWrap.style.display = 'none';
        } else if (v === 'monthly') {
            fromWrap.style.display = 'none';
            toWrap.style.display = 'none';
            monthWrap.style.display = '';
        } else {
            fromWrap.style.display = 'none';
            toWrap.style.display = 'none';
            monthWrap.style.display = 'none';
        }
    }));

    // Export buttons preserve current filter state
    const form = document.getElementById('repForm');
    function buildExportUrl(format) {
        const params = new URLSearchParams(new FormData(form));
        params.set('format', format);
        return '{{ route("admin.analytics.export") }}?' + params.toString();
    }
    document.getElementById('exportExcel').addEventListener('click', e => {
        e.preventDefault();
        window.location.href = buildExportUrl('excel');
    });
    document.getElementById('exportPdf').addEventListener('click', e => {
        e.preventDefault();
        window.location.href = buildExportUrl('pdf');
    });
    document.getElementById('printReport').addEventListener('click', () => window.print());

    // Charts
    const ts = @json($timeseries);
    const tsCtx = document.getElementById('tsChart').getContext('2d');
    const tsChart = new Chart(tsCtx, {
        data: {
            labels: ts.labels,
            datasets: [
                {
                    type: 'bar',
                    label: 'الإيرادات (ريال)',
                    data: ts.revenue,
                    yAxisID: 'y1',
                    backgroundColor: 'rgba(223, 163, 64, .55)',
                    borderColor: '#DFA340',
                    borderWidth: 1,
                    borderRadius: 6,
                },
                {
                    type: 'line',
                    label: 'عدد الطلبات',
                    data: ts.orders,
                    yAxisID: 'y2',
                    borderColor: '#08788B',
                    backgroundColor: 'rgba(8, 120, 139, .12)',
                    tension: .35,
                    pointRadius: 3,
                    pointBackgroundColor: '#08788B',
                    fill: true,
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: { legend: { position: 'bottom' } },
            scales: {
                y1: { beginAtZero: true, position: 'left',  title: { display: true, text: 'ريال' } },
                y2: { beginAtZero: true, position: 'right', grid: { display: false }, ticks: { precision: 0 }, title: { display: true, text: 'طلب' } },
                x:  { grid: { display: false } }
            }
        }
    });

    const statusValues = @json($statusValues);
    const statusLabels = @json($statusLabelsOut);
    const statusChart = new Chart(document.getElementById('statusChart').getContext('2d'), {
        type: 'doughnut',
        data: {
            labels: statusLabels.length ? statusLabels : ['لا توجد بيانات'],
            datasets: [{
                data: statusValues.length ? statusValues : [1],
                backgroundColor: ['#F59E0B','#3B82F6','#8B5CF6','#10B981','#EF4444'],
                borderWidth: 0,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            cutout: '62%',
            plugins: { legend: { position: 'bottom' } }
        }
    });

    function syncPrintChartImages() {
        [tsChart, statusChart].forEach(chart => {
            const canvas = chart.canvas;
            let image = canvas.parentNode.querySelector('.print-chart-image');
            if (!image) {
                image = document.createElement('img');
                image.className = 'print-chart-image';
                image.alt = canvas.getAttribute('aria-label') || 'رسم بياني';
                canvas.parentNode.appendChild(image);
            }
            image.src = chart.toBase64Image('image/png', 1);
        });
    }

    setTimeout(syncPrintChartImages, 500);
    window.addEventListener('beforeprint', () => {
        tsChart.resize(960, 320);
        statusChart.resize(420, 320);
        syncPrintChartImages();
    });
    window.addEventListener('afterprint', () => {
        tsChart.resize();
        statusChart.resize();
        setTimeout(syncPrintChartImages, 250);
    });
})();
</script>
<style>
@media print {
    @page { size: A4 landscape; margin: 10mm; }
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    body { background: #fff !important; }
    .sidebar, header, .rep-actions, .whatsapp-btn, .breadcrumb, nav { display: none !important; }
    .main-content, main, .content-wrapper { margin: 0 !important; padding: 0 !important; width: 100% !important; max-width: none !important; }
    .rep-toolbar {
        box-shadow: none !important;
        border-radius: 8px !important;
        padding: 10px 14px !important;
        margin-bottom: 12px !important;
    }
    .rep-row { display: none !important; }
    .kpi-grid {
        grid-template-columns: repeat(3, 1fr) !important;
        gap: 8px !important;
        margin-bottom: 12px !important;
    }
    .kpi-card {
        box-shadow: none !important;
        border-radius: 8px !important;
        padding: 10px 12px !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    .kpi-value { font-size: 1.35rem !important; }
    .grid-2 {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 10px !important;
    }
    .chart-card {
        box-shadow: none !important;
        border-radius: 8px !important;
        padding: 10px !important;
        margin-bottom: 10px !important;
        break-inside: avoid;
        page-break-inside: avoid;
    }
    .chart-card canvas { display: none !important; }
    .print-chart-image { display: block !important; }
    table.rep-table th, table.rep-table td {
        padding: 5px 7px !important;
        font-size: 10px !important;
    }
}
</style>
@endpush
