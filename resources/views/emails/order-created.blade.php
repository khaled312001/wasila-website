<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلب جديد - {{ $orderNumber }}</title>
    <style>
        body {
            font-family: 'Tajawal', 'Arial', sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            padding: 20px;
            border-radius: 8px 8px 0 0;
            margin: -30px -30px 30px -30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: bold;
            color: #059669;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .order-items {
            background-color: #f9fafb;
            border-right: 4px solid #10b981;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .order-item {
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .order-item:last-child {
            border-bottom: none;
        }
        .total-amount {
            background-color: #10b981;
            color: white;
            padding: 15px;
            border-radius: 4px;
            margin-top: 20px;
            text-align: center;
            font-size: 20px;
            font-weight: bold;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .status-paid {
            background-color: #10b981;
            color: white;
        }
        .status-pending {
            background-color: #f59e0b;
            color: white;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>طلب جديد - {{ $orderNumber }}</h1>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">رقم الطلب:</span>
                <span class="info-value"><strong>{{ $orderNumber }}</strong></span>
            </div>
            <div class="info-row">
                <span class="info-label">اسم العميل:</span>
                <span class="info-value">{{ $customerName }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">
                    <a href="mailto:{{ $customerEmail }}" style="color: #059669; text-decoration: none;">{{ $customerEmail }}</a>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">رقم الهاتف:</span>
                <span class="info-value">
                    <a href="tel:{{ $customerPhone }}" style="color: #059669; text-decoration: none;">{{ $customerPhone }}</a>
                </span>
            </div>
            @if($customerAddress)
            <div class="info-row">
                <span class="info-label">العنوان:</span>
                <span class="info-value">{{ $customerAddress }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">طريقة الدفع:</span>
                <span class="info-value">{{ $paymentMethod }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">حالة الدفع:</span>
                <span class="info-value">
                    <span class="status-badge {{ $paymentStatus === 'paid' ? 'status-paid' : 'status-pending' }}">
                        {{ $paymentStatus === 'paid' ? 'مدفوع' : 'في الانتظار' }}
                    </span>
                </span>
            </div>
            <div class="info-row">
                <span class="info-label">التاريخ والوقت:</span>
                <span class="info-value">{{ $createdAt->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>

        <div class="order-items">
            <strong style="color: #059669; display: block; margin-bottom: 15px;">تفاصيل الطلب:</strong>
            @foreach($orderItems as $item)
            <div class="order-item">
                <div style="display: flex; justify-content: space-between;">
                    <span><strong>{{ $item->service->name_ar ?? 'خدمة محذوفة' }}</strong> × {{ $item->quantity }}</span>
                    <span>{{ number_format($item->total_price, 2) }} ريال</span>
                </div>
            </div>
            @endforeach
        </div>

        <div class="total-amount">
            الإجمالي: {{ number_format($totalAmount, 2) }} ريال
        </div>

        <div class="footer">
            <p>تم إرسال هذه الرسالة تلقائياً من نظام إدارة الطلبات</p>
            <p style="margin-top: 10px;">يرجى مراجعة الطلب في لوحة الإدارة</p>
        </div>
    </div>
</body>
</html>
