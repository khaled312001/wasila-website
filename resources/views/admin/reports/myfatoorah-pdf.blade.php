<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير معاملات MyFatoorah</title>
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
        <h1>تقرير معاملات MyFatoorah</h1>
        <p>تاريخ التصدير: {{ date('Y-m-d H:i:s') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>رقم الطلب</th>
                <th>اسم العميل</th>
                <th>البريد الإلكتروني</th>
                <th>رقم الهاتف</th>
                <th>المبلغ</th>
                <th>طريقة الدفع</th>
                <th>حالة الدفع</th>
                <th>تاريخ الطلب</th>
                <th>مرجع الدفع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->order_number }}</td>
                <td>{{ $transaction->customer_name }}</td>
                <td>{{ $transaction->customer_email }}</td>
                <td>{{ $transaction->customer_phone }}</td>
                <td>{{ number_format($transaction->total_amount, 2) }} ريال</td>
                <td>{{ $transaction->payment_method ?? 'غير محدد' }}</td>
                <td>
                    @if($transaction->payment_status === 'paid')
                        مدفوع
                    @elseif($transaction->payment_status === 'pending')
                        في الانتظار
                    @else
                        فشل
                    @endif
                </td>
                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                <td>{{ $transaction->payment_reference ?? 'غير محدد' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p>تم إنشاء هذا التقرير تلقائياً من نظام وسيلة الخيرية</p>
        <p>إجمالي المعاملات: {{ $transactions->count() }}</p>
        <p>إجمالي المبلغ: {{ number_format($transactions->sum('total_amount'), 2) }} ريال</p>
    </div>
</body>
</html>

