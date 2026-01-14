<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من العميل</title>
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
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
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
            color: #2563eb;
            width: 140px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .message-box {
            background-color: #f9fafb;
            border-right: 4px solid #3b82f6;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .message-box p {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .file-info {
            background-color: #eff6ff;
            padding: 15px;
            border-radius: 4px;
            margin-top: 15px;
            border-right: 3px solid #3b82f6;
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
            <h1>رسالة جديدة من العميل</h1>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">اسم العميل:</span>
                <span class="info-value">{{ $customer->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">
                    <a href="mailto:{{ $customer->email }}" style="color: #2563eb; text-decoration: none;">{{ $customer->email }}</a>
                </span>
            </div>
            @if($order)
            <div class="info-row">
                <span class="info-label">رقم الطلب:</span>
                <span class="info-value"><strong>{{ $order->order_number }}</strong></span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">التاريخ والوقت:</span>
                <span class="info-value">{{ $createdAt->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>

        @if($messageText)
        <div class="message-box">
            <strong style="color: #2563eb; display: block; margin-bottom: 10px;">الرسالة:</strong>
            <p>{{ $messageText }}</p>
        </div>
        @endif

        @if($hasFile)
        <div class="file-info">
            <strong style="color: #2563eb; display: block; margin-bottom: 5px;">ملف مرفق:</strong>
            <p style="margin: 0;">
                <strong>{{ $fileName }}</strong>
                @if($fileType)
                <br><span style="color: #6b7280; font-size: 14px;">نوع الملف: {{ $fileType }}</span>
                @endif
            </p>
        </div>
        @endif

        <div class="footer">
            <p>تم إرسال هذه الرسالة تلقائياً من نظام إدارة الرسائل</p>
            <p style="margin-top: 10px;">يرجى الرد على هذه الرسالة في أقرب وقت ممكن</p>
        </div>
    </div>
</body>
</html>
