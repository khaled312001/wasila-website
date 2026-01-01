<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير التقارير والإحصائيات</title>
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
            unicode-bidi: bidi-override;
            text-align: right;
            writing-mode: horizontal-tb;
            font-size: 14px;
            line-height: 1.8;
            color: #333;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        * {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: right;
        }
        h1, h2, h3, h4, h5, h6, p, div, span, td, th, li {
            unicode-bidi: bidi-override;
            direction: rtl;
            text-align: right;
        }
        .arabic-text {
            direction: rtl;
            unicode-bidi: bidi-override;
            text-align: right;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #08788B;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }
        .header h1 {
            font-size: 32px;
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
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-right: 4px solid #08788B;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }
        .stat-card h3 {
            font-size: 16px;
            font-weight: bold;
            color: #374151;
            margin-bottom: 10px;
            text-align: right;
        }
        .stat-card p {
            font-size: 28px;
            font-weight: bold;
            color: #08788B;
            text-align: right;
            line-height: 1.2;
        }
        h2 {
            font-size: 20px;
            font-weight: bold;
            color: #025469;
            margin: 25px 0 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #08788B;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 12px 15px;
            border: 1px solid #e5e7eb;
            text-align: right;
            font-size: 13px;
        }
        th {
            background: linear-gradient(135deg, #08788B 0%, #025469 100%);
            color: white;
            font-weight: bold;
            font-size: 14px;
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
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            border-top: 2px solid #e5e7eb;
            padding-top: 15px;
            background: #f9fafb;
            padding: 15px;
            border-radius: 8px;
        }
        .footer p {
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Helpers\PdfHelper::fixArabic('تقرير التقارير والإحصائيات') }}</h1>
        <p>{{ \App\Helpers\PdfHelper::fixArabic('تاريخ التصدير: ') }}{{ date('Y-m-d H:i:s') }}</p>
        <p>{{ \App\Helpers\PdfHelper::fixArabic('الفترة: آخر ') }}{{ $dateRange }} {{ \App\Helpers\PdfHelper::fixArabic('يوم') }}</p>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <h3>{{ \App\Helpers\PdfHelper::fixArabic('إجمالي الطلبات') }}</h3>
            <p>{{ $stats['total_orders'] }}</p>
        </div>
        <div class="stat-card">
            <h3>{{ \App\Helpers\PdfHelper::fixArabic('إجمالي الإيرادات') }}</h3>
            <p>{{ number_format($stats['total_revenue'], 2) }} {{ \App\Helpers\PdfHelper::fixArabic('ريال') }}</p>
        </div>
        <div class="stat-card">
            <h3>{{ \App\Helpers\PdfHelper::fixArabic('إجمالي العملاء') }}</h3>
            <p>{{ $stats['total_customers'] }}</p>
        </div>
        <div class="stat-card">
            <h3>{{ \App\Helpers\PdfHelper::fixArabic('الطلبات حسب الحالة') }}</h3>
            <p>
                @foreach($stats['orders_by_status'] as $status)
                {{ \App\Helpers\PdfHelper::fixArabic($status->status) }}: {{ $status->count }}<br>
                @endforeach
            </p>
        </div>
    </div>
    
    <h2 style="margin-top: 20px; margin-bottom: 10px; color: #025469;">{{ \App\Helpers\PdfHelper::fixArabic('أفضل الخدمات') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('اسم الخدمة') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('عدد الطلبات') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($stats['top_services'] as $service)
            <tr>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($service->name_ar ?? $service->name) }}</td>
                <td>{{ $service->orders_count ?? 0 }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <h2 style="margin-top: 20px; margin-bottom: 10px; color: #025469;">{{ \App\Helpers\PdfHelper::fixArabic('الطلبات') }}</h2>
    <table>
        <thead>
            <tr>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('رقم الطلب') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('اسم العميل') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('البريد الإلكتروني') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('المبلغ') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('الحالة') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('التاريخ') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->order_number) }}</td>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->customer_name) }}</td>
                <td dir="ltr">{{ $order->customer_email }}</td>
                <td>{{ number_format($order->total_amount, 2) }} {{ \App\Helpers\PdfHelper::fixArabic('ريال') }}</td>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->status) }}</td>
                <td dir="ltr">{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>{{ \App\Helpers\PdfHelper::fixArabic('تم إنشاء هذا التقرير تلقائياً من نظام وسيلة الخيرية') }}</p>
    </div>
</body>
</html>

