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
            color: #333;
            padding: 40px;
            background: #fff;
            font-size: 14px;
            line-height: 1.8;
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
        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px;
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
            color: #666;
            font-size: 16px;
            font-weight: 500;
        }
        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
            padding: 25px;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 10px;
            border: 2px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .info-section h3 {
            font-size: 20px;
            font-weight: bold;
            color: #025469;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 2px solid #08788B;
        }
        .info-section p {
            margin: 8px 0;
            color: #374151;
            font-size: 14px;
        }
        .info-section strong {
            color: #08788B;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 30px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            border-radius: 8px;
            overflow: hidden;
        }
        th, td {
            padding: 16px 18px;
            text-align: right;
            border: 1px solid #e5e7eb;
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
        .total-row {
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%) !important;
            font-weight: bold;
            font-size: 20px;
        }
        .total-row td {
            border-top: 3px solid #08788B;
            color: #025469;
            font-size: 18px;
        }
        .footer {
            margin-top: 40px;
            padding: 25px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            background: #f9fafb;
            border-radius: 8px;
        }
        .footer p {
            margin: 8px 0;
            font-weight: 500;
        }
        .payment-info {
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            padding: 25px;
            border-radius: 10px;
            margin-top: 30px;
            border: 2px solid #e5e7eb;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        .payment-info h3 {
            color: #025469;
            margin-bottom: 15px;
            font-size: 18px;
            font-weight: bold;
            padding-bottom: 8px;
            border-bottom: 2px solid #08788B;
        }
        .payment-info p {
            color: #374151;
            font-size: 15px;
            font-weight: 500;
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

