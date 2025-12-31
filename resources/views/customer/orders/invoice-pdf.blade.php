<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فاتورة #{{ $order->order_number }}</title>
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
            text-align: right;
            color: #333;
            padding: 40px;
            background: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #08788B;
        }
        .header h1 {
            font-size: 32px;
            color: #025469;
            margin-bottom: 10px;
        }
        .header p {
            color: #666;
            font-size: 16px;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 20px;
            background: #f8fafc;
            border-radius: 8px;
        }
        .info-section h3 {
            font-size: 18px;
            color: #025469;
            margin-bottom: 10px;
        }
        .info-section p {
            margin: 5px 0;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
        }
        th, td {
            padding: 15px;
            text-align: right;
            border: 1px solid #ddd;
        }
        th {
            background: linear-gradient(135deg, #08788B 0%, #025469 100%);
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background: #f9fafb;
        }
        .total-row {
            background: #f0f9ff !important;
            font-weight: bold;
            font-size: 18px;
        }
        .total-row td {
            border-top: 2px solid #08788B;
        }
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .payment-info {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .payment-info h3 {
            color: #025469;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>فاتورة رقم: #{{ $order->order_number }}</h1>
        <p>جمعية وسيلة الخيرية</p>
    </div>
    
    <div class="invoice-info">
        <div class="info-section">
            <h3>العميل:</h3>
            <p><strong>الاسم:</strong> {{ $order->customer_name }}</p>
            <p><strong>البريد:</strong> {{ $order->customer_email }}</p>
            @if($order->customer_phone)
            <p><strong>الهاتف:</strong> {{ $order->customer_phone }}</p>
            @endif
        </div>
        <div class="info-section">
            <h3>معلومات الفاتورة:</h3>
            <p><strong>التاريخ:</strong> {{ $order->created_at->format('Y-m-d') }}</p>
            <p><strong>الحالة:</strong> {{ $order->status === 'completed' ? 'مكتمل' : ($order->status === 'pending' ? 'معلق' : $order->status) }}</p>
            <p><strong>حالة الدفع:</strong> {{ $order->payment_status === 'paid' ? 'مدفوع' : 'غير مدفوع' }}</p>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>الخدمة</th>
                <th>الوصف</th>
                <th>المبلغ</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $order->service_name ?? 'خدمة' }}</td>
                <td>{{ $order->service_description ?? '-' }}</td>
                <td>{{ number_format($order->total_amount, 2) }} ريال</td>
            </tr>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="2" style="text-align: left;">الإجمالي:</td>
                <td>{{ number_format($order->total_amount, 2) }} ريال سعودي</td>
            </tr>
        </tfoot>
    </table>
    
    <div class="payment-info">
        <h3>طريقة الدفع:</h3>
        <p>{{ $order->payment_method }}</p>
    </div>
    
    <div class="footer">
        <p>شكراً لثقتك بجمعية وسيلة الخيرية</p>
        <p>© {{ date('Y') }} جميع الحقوق محفوظة</p>
    </div>
</body>
</html>

