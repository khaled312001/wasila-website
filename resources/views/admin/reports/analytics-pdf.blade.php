<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير التقارير والإحصائيات</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: dejavusans, Arial, sans-serif;
            direction: rtl;
            text-align: right;
            font-size: 11px;
            line-height: 1.6;
            color: #1f2937;
        }
        .header {
            text-align: center;
            padding: 14px 18px;
            border-bottom: 3px solid #08788B;
            background: #f8fafc;
            margin-bottom: 14px;
        }
        .header h1 {
            color: #025469;
            font-size: 22px;
            margin: 0 0 6px;
        }
        .muted { color: #64748b; }
        .stats-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 14px;
        }
        .stats-table td {
            width: 20%;
            border: 1px solid #dbe4ea;
            background: #fff;
            padding: 10px;
            vertical-align: top;
        }
        .stat-label {
            color: #64748b;
            font-weight: bold;
            margin-bottom: 4px;
        }
        .stat-value {
            color: #025469;
            font-weight: bold;
            font-size: 19px;
        }
        h2 {
            color: #025469;
            font-size: 15px;
            border-bottom: 2px solid #08788B;
            padding-bottom: 5px;
            margin: 14px 0 8px;
        }
        .data-table th, .data-table td {
            border: 1px solid #e5e7eb;
            padding: 6px 7px;
            font-size: 10px;
        }
        .data-table th {
            background: #08788B;
            color: #fff;
            font-weight: bold;
        }
        .data-table tr:nth-child(even) td { background: #f8fafc; }
        .bar-row {
            width: 100%;
            height: 12px;
            background: #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .bar-fill {
            height: 12px;
            background: #08788B;
            border-radius: 8px;
        }
        .bar-fill.gold { background: #DFA340; }
        .chart-box {
            border: 1px solid #dbe4ea;
            background: #ffffff;
            padding: 10px;
            margin-bottom: 12px;
        }
        .chart-title {
            color: #025469;
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 6px;
        }
        .chart-note {
            color: #64748b;
            font-size: 9px;
            margin-top: 4px;
        }
        .two-col {
            width: 100%;
            border-collapse: collapse;
        }
        .two-col td {
            width: 50%;
            vertical-align: top;
            padding: 0 4px;
        }
        .footer {
            margin-top: 18px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e5e7eb;
            padding-top: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير التقارير والإحصائيات</h1>
        <div>تاريخ التصدير: {{ now()->format('Y-m-d H:i:s') }}</div>
        <div>الفترة: من {{ $startDate->format('Y-m-d') }} إلى {{ $endDate->format('Y-m-d') }}</div>
    </div>

    <table class="stats-table">
        <tr>
            <td>
                <div class="stat-label">إجمالي الطلبات</div>
                <div class="stat-value">{{ number_format($stats['total_orders']) }}</div>
            </td>
            <td>
                <div class="stat-label">الإيرادات المدفوعة</div>
                <div class="stat-value">{{ number_format($stats['total_revenue'], 2) }}</div>
                <div class="muted">ريال سعودي</div>
            </td>
            <td>
                <div class="stat-label">طلبات مدفوعة</div>
                <div class="stat-value">{{ number_format($stats['paid_orders']) }}</div>
            </td>
            <td>
                <div class="stat-label">عملاء فريدين</div>
                <div class="stat-value">{{ number_format($stats['unique_customers']) }}</div>
            </td>
            <td>
                <div class="stat-label">عملاء جدد</div>
                <div class="stat-value">{{ number_format($stats['total_customers']) }}</div>
            </td>
        </tr>
    </table>

    <h2>الرسم البياني للفترة</h2>
    @php
        $maxOrders = max($timeseries['orders'] ?: [0]);
        $maxRevenue = max($timeseries['revenue'] ?: [0]);
        $chartWidth = 920;
        $chartHeight = 250;
        $padLeft = 50;
        $padRight = 24;
        $padTop = 18;
        $padBottom = 38;
        $plotWidth = $chartWidth - $padLeft - $padRight;
        $plotHeight = $chartHeight - $padTop - $padBottom;
        $pointCount = count($timeseries['labels']);
        $barGap = $pointCount > 0 ? max(4, $plotWidth / max(1, $pointCount) * .25) : 4;
        $barWidth = $pointCount > 0 ? max(8, ($plotWidth / max(1, $pointCount)) - $barGap) : 8;
        $ordersPoints = [];
        $labelStep = max(1, (int) ceil(max(1, $pointCount) / 8));
        foreach ($timeseries['labels'] as $i => $label) {
            $slot = $plotWidth / max(1, $pointCount);
            $x = $padLeft + ($slot * $i) + ($slot / 2);
            $ordersValue = $timeseries['orders'][$i] ?? 0;
            $y = $padTop + $plotHeight - ($maxOrders > 0 ? (($ordersValue / $maxOrders) * $plotHeight) : 0);
            $ordersPoints[] = round($x, 2) . ',' . round($y, 2);
        }
    @endphp
    <div class="chart-box">
        <div class="chart-title">الإيرادات مقابل عدد الطلبات</div>
        <svg width="100%" height="{{ $chartHeight }}" viewBox="0 0 {{ $chartWidth }} {{ $chartHeight }}" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="{{ $chartWidth }}" height="{{ $chartHeight }}" fill="#ffffff"/>
            <line x1="{{ $padLeft }}" y1="{{ $padTop + $plotHeight }}" x2="{{ $chartWidth - $padRight }}" y2="{{ $padTop + $plotHeight }}" stroke="#cbd5e1" stroke-width="1"/>
            <line x1="{{ $padLeft }}" y1="{{ $padTop }}" x2="{{ $padLeft }}" y2="{{ $padTop + $plotHeight }}" stroke="#cbd5e1" stroke-width="1"/>
            @for($g = 0; $g <= 4; $g++)
                @php $gy = $padTop + ($plotHeight / 4 * $g); @endphp
                <line x1="{{ $padLeft }}" y1="{{ $gy }}" x2="{{ $chartWidth - $padRight }}" y2="{{ $gy }}" stroke="#eef2f7" stroke-width="1"/>
            @endfor
            @foreach($timeseries['labels'] as $i => $label)
                @php
                    $slot = $plotWidth / max(1, $pointCount);
                    $x = $padLeft + ($slot * $i) + ($barGap / 2);
                    $revenueValue = $timeseries['revenue'][$i] ?? 0;
                    $barHeight = $maxRevenue > 0 ? (($revenueValue / $maxRevenue) * $plotHeight) : 0;
                    $barY = $padTop + $plotHeight - $barHeight;
                @endphp
                <rect x="{{ round($x, 2) }}" y="{{ round($barY, 2) }}" width="{{ round($barWidth, 2) }}" height="{{ round($barHeight, 2) }}" fill="#DFA340"/>
                @if($i % $labelStep === 0 || $i === $pointCount - 1)
                    <text x="{{ round($x + ($barWidth / 2), 2) }}" y="{{ $chartHeight - 16 }}" font-size="9" text-anchor="middle" fill="#64748b">{{ $label }}</text>
                @endif
            @endforeach
            @if(count($ordersPoints) > 1)
                <polyline points="{{ implode(' ', $ordersPoints) }}" fill="none" stroke="#08788B" stroke-width="3"/>
            @endif
            @foreach($ordersPoints as $point)
                @php [$cx, $cy] = explode(',', $point); @endphp
                <circle cx="{{ $cx }}" cy="{{ $cy }}" r="3" fill="#08788B"/>
            @endforeach
            <rect x="{{ $chartWidth - 210 }}" y="12" width="12" height="12" fill="#DFA340"/>
            <text x="{{ $chartWidth - 192 }}" y="22" font-size="10" fill="#334155">الإيرادات المدفوعة</text>
            <line x1="{{ $chartWidth - 110 }}" y1="18" x2="{{ $chartWidth - 86 }}" y2="18" stroke="#08788B" stroke-width="3"/>
            <circle cx="{{ $chartWidth - 98 }}" cy="18" r="3" fill="#08788B"/>
            <text x="{{ $chartWidth - 78 }}" y="22" font-size="10" fill="#334155">عدد الطلبات</text>
        </svg>
        <div class="chart-note">يعرض الرسم الطلبات التي تم تأكيد دفعها فقط.</div>
    </div>

    <div class="chart-box">
        <div class="chart-title">توزيع حالات الطلبات المدفوعة</div>
        @php
            $statusTotal = max(1, $stats['orders_by_status']->sum('count'));
            $statusColors = ['#F59E0B', '#3B82F6', '#8B5CF6', '#10B981', '#EF4444', '#08788B'];
        @endphp
        <svg width="100%" height="{{ max(70, 30 + ($stats['orders_by_status']->count() * 28)) }}" viewBox="0 0 920 {{ max(70, 30 + ($stats['orders_by_status']->count() * 28)) }}" xmlns="http://www.w3.org/2000/svg">
            <rect x="0" y="0" width="920" height="{{ max(70, 30 + ($stats['orders_by_status']->count() * 28)) }}" fill="#ffffff"/>
            @forelse($stats['orders_by_status'] as $i => $status)
                @php
                    $percent = round(($status->count / $statusTotal) * 100, 1);
                    $width = max(4, ($percent / 100) * 620);
                    $y = 18 + ($loop->index * 28);
                    $color = $statusColors[$loop->index % count($statusColors)];
                @endphp
                <text x="900" y="{{ $y + 12 }}" font-size="10" text-anchor="end" fill="#334155">{{ $status->status }}</text>
                <rect x="180" y="{{ $y }}" width="620" height="14" fill="#e5e7eb"/>
                <rect x="{{ 800 - $width }}" y="{{ $y }}" width="{{ $width }}" height="14" fill="{{ $color }}"/>
                <text x="160" y="{{ $y + 12 }}" font-size="10" text-anchor="end" fill="#334155">{{ number_format($status->count) }} ({{ $percent }}%)</text>
            @empty
                <text x="460" y="38" font-size="12" text-anchor="middle" fill="#64748b">لا توجد بيانات</text>
            @endforelse
        </svg>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>الفترة</th>
                <th>عدد الطلبات</th>
                <th>تمثيل الطلبات</th>
                <th>الإيرادات</th>
                <th>تمثيل الإيرادات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($timeseries['labels'] as $i => $label)
                @php
                    $ordersValue = $timeseries['orders'][$i] ?? 0;
                    $revenueValue = $timeseries['revenue'][$i] ?? 0;
                    $ordersWidth = $maxOrders > 0 ? max(2, round(($ordersValue / $maxOrders) * 100)) : 0;
                    $revenueWidth = $maxRevenue > 0 ? max(2, round(($revenueValue / $maxRevenue) * 100)) : 0;
                @endphp
                <tr>
                    <td>{{ $label }}</td>
                    <td>{{ number_format($ordersValue) }}</td>
                    <td><div class="bar-row"><div class="bar-fill" style="width: {{ $ordersWidth }}%"></div></div></td>
                    <td>{{ number_format($revenueValue, 2) }}</td>
                    <td><div class="bar-row"><div class="bar-fill gold" style="width: {{ $revenueWidth }}%"></div></div></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="two-col">
        <tr>
            <td>
                <h2>حالات الطلب</h2>
                <table class="data-table">
                    <thead><tr><th>الحالة</th><th>العدد</th></tr></thead>
                    <tbody>
                        @forelse($stats['orders_by_status'] as $status)
                            <tr><td>{{ $status->status }}</td><td>{{ number_format($status->count) }}</td></tr>
                        @empty
                            <tr><td colspan="2">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
            <td>
                <h2>طرق الدفع</h2>
                <table class="data-table">
                    <thead><tr><th>الطريقة</th><th>العدد</th><th>الإجمالي</th></tr></thead>
                    <tbody>
                        @forelse($stats['payment_methods'] as $method)
                            <tr>
                                <td>{{ $method->payment_method }}</td>
                                <td>{{ number_format($method->count) }}</td>
                                <td>{{ number_format($method->total ?? 0, 2) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3">لا توجد بيانات</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </td>
        </tr>
    </table>

    <h2>أفضل الخدمات</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>#</th>
                <th>اسم الخدمة</th>
                <th>عدد الطلبات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($stats['top_services'] as $service)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $service->name_ar ?? $service->name_en }}</td>
                    <td>{{ number_format($service->orders_count ?? 0) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">لا توجد بيانات</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>الطلبات</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>اسم العميل</th>
                <th>البريد الإلكتروني</th>
                <th>المبلغ</th>
                <th>حالة الطلب</th>
                <th>حالة الدفع</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td dir="ltr">{{ $order->customer_email }}</td>
                    <td>{{ number_format($order->total_amount, 2) }}</td>
                    <td>{{ $order->status }}</td>
                    <td>{{ $order->payment_status }}</td>
                    <td dir="ltr">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                </tr>
            @empty
                <tr><td colspan="7">لا توجد طلبات في الفترة المحددة</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">تم إنشاء هذا التقرير تلقائياً من نظام وسيلة</div>
</body>
</html>
