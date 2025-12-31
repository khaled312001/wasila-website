<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير الطلبات</title>
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
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير الطلبات</h1>
        <p>تاريخ التقرير: {{ date('Y-m-d') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>العميل</th>
                <th>الخدمة</th>
                <th>المبلغ</th>
                <th>الحالة</th>
                <th>تاريخ الطلب</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>#{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->service_name ?? '-' }}</td>
                <td>{{ number_format($order->total_amount, 2) }} ر.س</td>
                <td>{{ $order->status === 'completed' ? 'مكتمل' : ($order->status === 'pending' ? 'معلق' : $order->status) }}</td>
                <td>{{ $order->created_at->format('Y-m-d') }}</td>
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

