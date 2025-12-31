<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير العملاء</title>
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
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>تقرير العملاء</h1>
        <p>تاريخ التصدير: {{ date('Y-m-d H:i:s') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الاسم</th>
                <th>البريد الإلكتروني</th>
                <th>الهاتف</th>
                <th>العنوان</th>
                <th>عدد الطلبات</th>
                <th>عدد الرسائل</th>
                <th>تاريخ التسجيل</th>
            </tr>
        </thead>
        <tbody>
            @foreach($customers as $customer)
            <tr>
                <td>{{ $customer->name }}</td>
                <td>{{ $customer->email }}</td>
                <td>{{ $customer->phone ?? 'غير محدد' }}</td>
                <td>{{ $customer->address ?? 'غير محدد' }}</td>
                <td>{{ $customer->orders_count }}</td>
                <td>{{ $customer->messages_count }}</td>
                <td>{{ $customer->created_at->format('Y-m-d') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام وسيلة الخيرية</p>
        <p>إجمالي العملاء: {{ $customers->count() }}</p>
    </div>
</body>
</html>

