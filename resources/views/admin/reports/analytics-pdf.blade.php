<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير التقارير والإحصائيات</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}');
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Tajawal', Arial, sans-serif;
            direction: rtl;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #08788B;
        }
        .header h1 {
            font-size: 24px;
            color: #025469;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: #f9fafb;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .stat-card h3 {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-card p {
            font-size: 18px;
            font-weight: bold;
            color: #025469;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            text-align: right;
        }
        th {
            background: #08788B;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير التقارير والإحصائيات</h1>
        <p>تاريخ التصدير: {{ date('Y-m-d H:i:s') }}</p>
        <p>الفترة: آخر {{ $dateRange }} يوم</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>إجمالي الطلبات</h3>
            <p>{{ $stats['total_orders'] }}</p>
        </div>
        <div class="stat-card">
            <h3>إجمالي الإيرادات</h3>
            <p>{{ number_format($stats['total_revenue'], 2) }} ريال</p>
        </div>
        <div class="stat-card">
            <h3>إجمالي العملاء</h3>
            <p>{{ $stats['total_customers'] }}</p>
        </div>
        <div class="stat-card">
            <h3>الطلبات حسب الحالة</h3>
            <p>
                @foreach($stats['orders_by_status'] as $status)
                {{ $status->status }}: {{ $status->count }}<br>
                @endforeach
            </p>
        </div>
    </div>
    
    <h2 style="margin-top: 20px; margin-bottom: 10px; color: #025469;">أفضل الخدمات</h2>
    <table>
        <thead>
            <tr>
                <th>اسم الخدمة</th>
                <th>عدد الطلبات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['top_services'] as $service)
            <tr>
                <td>{{ $service->name_ar ?? $service->name }}</td>
                <td>{{ $service->orders_count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2 style="margin-top: 20px; margin-bottom: 10px; color: #025469;">الطلبات</h2>
    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>اسم العميل</th>
                <th>البريد الإلكتروني</th>
                <th>المبلغ</th>
                <th>الحالة</th>
                <th>التاريخ</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_email }}</td>
                <td>{{ number_format($order->total_amount, 2) }} ريال</td>
                <td>{{ $order->status }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام وسيلة الخيرية</p>
    </div>
</body>
</html>

