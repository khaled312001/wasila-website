<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير الإحصائيات</title>
    <style>
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'DejaVu Sans';
            src: url('{{ storage_path('fonts/DejaVuSans-Bold.ttf') }}');
            font-weight: bold;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            direction: rtl;
            padding: 30px;
            font-size: 14px;
            line-height: 1.8;
            color: #333;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 25px;
            border-bottom: 3px solid #08788B;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .header h1 {
            font-size: 36px;
            font-weight: bold;
            color: #025469;
            margin-bottom: 10px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);
            letter-spacing: 0.5px;
        }
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        .stat-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 25px;
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            border-right: 4px solid #08788B;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .stat-card h3 {
            color: #374151;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }
        .stat-card .value {
            font-size: 36px;
            font-weight: bold;
            color: #08788B;
            text-align: right;
            line-height: 1.2;
        }
        h2 {
            font-size: 22px;
            font-weight: bold;
            color: #025469;
            margin: 30px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #08788B;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            text-align: right;
            font-size: 14px;
        }
        th {
            background: linear-gradient(135deg, #08788B 0%, #025469 100%);
            color: white;
            font-weight: bold;
            font-size: 15px;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.2);
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        tr:nth-child(odd) {
            background: #ffffff;
        }
        td {
            color: #374151;
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

