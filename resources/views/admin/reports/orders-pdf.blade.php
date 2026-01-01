<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير الطلبات</title>
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
            padding: 30px;
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
            padding: 25px;
            border-bottom: 3px solid #08788B;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
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
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ \App\Helpers\PdfHelper::fixArabic('تقرير الطلبات') }}</h1>
        <p>{{ \App\Helpers\PdfHelper::fixArabic('تاريخ التقرير: ') }}{{ date('Y-m-d') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('رقم الطلب') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('العميل') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('الخدمة') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('المبلغ') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('الحالة') }}</th>
                <th>{{ \App\Helpers\PdfHelper::fixArabic('تاريخ الطلب') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ \App\Helpers\PdfHelper::fixArabic($order->order_number) }}</td>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->customer_name) }}</td>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->service_name ?? '-') }}</td>
                <td>{{ number_format($order->total_amount, 2) }} {{ \App\Helpers\PdfHelper::fixArabic('ر.س') }}</td>
                <td>{{ \App\Helpers\PdfHelper::fixArabic($order->status === 'completed' ? 'مكتمل' : ($order->status === 'pending' ? 'معلق' : $order->status)) }}</td>
                <td dir="ltr">{{ $order->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>إجمالي الطلبات: {{ $orders->count() }}</p>
        <p>إجمالي المبلغ: {{ number_format($orders->sum('total_amount'), 2) }} ر.س</p>
    </div>
</body>
</html>

