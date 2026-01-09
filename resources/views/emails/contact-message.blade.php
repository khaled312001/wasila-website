<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رسالة جديدة من نموذج التواصل</title>
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
            width: 120px;
            flex-shrink: 0;
        }
        .info-value {
            color: #333;
            flex: 1;
        }
        .message-box {
            background-color: #f9fafb;
            border-right: 4px solid #10b981;
            padding: 20px;
            border-radius: 4px;
            margin-top: 20px;
        }
        .message-box p {
            margin: 0;
            white-space: pre-wrap;
            word-wrap: break-word;
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
            <h1>رسالة جديدة من نموذج التواصل</h1>
        </div>

        <div class="info-section">
            <div class="info-row">
                <span class="info-label">الاسم:</span>
                <span class="info-value">{{ $name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">البريد الإلكتروني:</span>
                <span class="info-value">
                    <a href="mailto:{{ $email }}" style="color: #059669; text-decoration: none;">{{ $email }}</a>
                </span>
            </div>
            @if($phone)
            <div class="info-row">
                <span class="info-label">رقم الهاتف:</span>
                <span class="info-value">
                    <a href="tel:{{ $phone }}" style="color: #059669; text-decoration: none;">{{ $phone }}</a>
                </span>
            </div>
            @endif
            @if($subject)
            <div class="info-row">
                <span class="info-label">الموضوع:</span>
                <span class="info-value">{{ $subject }}</span>
            </div>
            @endif
            <div class="info-row">
                <span class="info-label">التاريخ والوقت:</span>
                <span class="info-value">{{ $created_at->format('Y-m-d H:i:s') }}</span>
            </div>
        </div>

        <div class="message-box">
            <strong style="color: #059669; display: block; margin-bottom: 10px;">الرسالة:</strong>
            <p>{{ $message }}</p>
        </div>

        <div class="footer">
            <p>تم إرسال هذه الرسالة تلقائياً من نموذج التواصل في موقع منصة وسيلة</p>
            <p style="margin-top: 10px;">يرجى الرد على هذه الرسالة في أقرب وقت ممكن</p>
        </div>
    </div>
</body>
</html>

