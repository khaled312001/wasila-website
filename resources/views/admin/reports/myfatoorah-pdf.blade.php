<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <title>تقرير معاملات MyFatoorah</title>
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
        @font-face {
            font-family: 'Arabic';
            src: url('{{ storage_path('fonts/DejaVuSans.ttf') }}');
            font-weight: normal;
            font-style: normal;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', 'Arabic', Arial, sans-serif;
            direction: rtl;
            unicode-bidi: embed;
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
            unicode-bidi: embed;
            direction: rtl;
            text-align: right;
        }
        h1, h2, h3, h4, h5, h6, p, div, span, td, th, li {
            unicode-bidi: embed;
            direction: rtl;
            text-align: right;
        }
        .arabic-text {
            direction: rtl;
            unicode-bidi: embed;
            text-align: right;
            font-family: 'DejaVu Sans', 'Arabic', Arial, sans-serif;
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
        .header p {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
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
            margin-top: 40px;
            text-align: center;
            font-size: 13px;
            color: #6b7280;
            border-top: 2px solid #e5e7eb;
            padding: 20px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .footer p {
            margin: 8px 0;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1 class="arabic-text">&#x200F;تقرير معاملات MyFatoorah</h1>
        <p class="arabic-text">&#x200F;تاريخ التصدير: {{ date('Y-m-d H:i:s') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th class="arabic-text">&#x200F;رقم الطلب</th>
                <th class="arabic-text">&#x200F;اسم العميل</th>
                <th class="arabic-text">&#x200F;البريد الإلكتروني</th>
                <th class="arabic-text">&#x200F;رقم الهاتف</th>
                <th class="arabic-text">&#x200F;المبلغ</th>
                <th class="arabic-text">&#x200F;طريقة الدفع</th>
                <th class="arabic-text">&#x200F;حالة الدفع</th>
                <th class="arabic-text">&#x200F;تاريخ الطلب</th>
                <th class="arabic-text">&#x200F;مرجع الدفع</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td class="arabic-text">{{ $transaction->order_number }}</td>
                <td class="arabic-text">&#x200F;{{ $transaction->customer_name }}</td>
                <td>{{ $transaction->customer_email }}</td>
                <td>{{ $transaction->customer_phone }}</td>
                <td class="arabic-text">&#x200F;{{ number_format($transaction->total_amount, 2) }} ريال</td>
                <td class="arabic-text">&#x200F;{{ $transaction->payment_method ?? 'غير محدد' }}</td>
                <td class="arabic-text">
                    @if($transaction->payment_status === 'paid')
                        &#x200F;مدفوع
                    @elseif($transaction->payment_status === 'pending')
                        &#x200F;في الانتظار
                    @else
                        &#x200F;فشل
                    @endif
                </td>
                <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                <td class="arabic-text">&#x200F;{{ $transaction->payment_reference ?? 'غير محدد' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        <p class="arabic-text">&#x200F;تم إنشاء هذا التقرير تلقائياً من نظام وسيلة الخيرية</p>
        <p class="arabic-text">&#x200F;إجمالي المعاملات: {{ $transactions->count() }}</p>
        <p class="arabic-text">&#x200F;إجمالي المبلغ: {{ number_format($transactions->sum('total_amount'), 2) }} ريال</p>
    </div>
</body>
</html>

