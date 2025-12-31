<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير الإحصائيات</title>
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
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 3px solid #08788B;
        }
        .header h1 {
            font-size: 28px;
            color: #025469;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            border-right: 4px solid #08788B;
        }
        .stat-card h3 {
            color: #025469;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .stat-card .value {
            font-size: 32px;
            font-weight: bold;
            color: #08788B;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: right;
        }
        th {
            background: #08788B;
            color: white;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الإحصائيات الشامل</h1>
        <p>تاريخ التقرير: {{ date('Y-m-d H:i') }}</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>إجمالي الطلبات</h3>
            <div class="value">{{ $stats['total_orders'] }}</div>
        </div>
        <div class="stat-card">
            <h3>إجمالي الإيرادات</h3>
            <div class="value">{{ number_format($stats['total_revenue'], 2) }} ر.س</div>
        </div>
        <div class="stat-card">
            <h3>إجمالي العملاء</h3>
            <div class="value">{{ $stats['total_customers'] }}</div>
        </div>
    </div>
    
    <h2 style="color: #025469; margin: 30px 0 15px;">الطلبات حسب الحالة</h2>
    <table>
        <thead>
            <tr>
                <th>الحالة</th>
                <th>العدد</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['orders_by_status'] as $status)
            <tr>
                <td>{{ $status->status === 'completed' ? 'مكتمل' : ($status->status === 'pending' ? 'معلق' : $status->status) }}</td>
                <td>{{ $status->count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2 style="color: #025469; margin: 30px 0 15px;">أفضل الخدمات</h2>
    <table>
        <thead>
            <tr>
                <th>الخدمة</th>
                <th>عدد الطلبات</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['top_services'] as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->orders_count }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>

