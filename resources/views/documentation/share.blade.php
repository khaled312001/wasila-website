<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $documentation->title ?? 'رابط توثيق الطلب' }} - وسيلة</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #08788B;
            --primary-dark: #025469;
            --accent: #DFA340;
            --surface: #ffffff;
            --text: #1f2937;
            --muted: #64748b;
            --border: #e2e8f0;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Noto Sans Arabic", Tahoma, Arial, sans-serif;
            background: #f3f6f8;
            color: var(--text);
            min-height: 100vh;
        }

        .page {
            width: min(980px, calc(100% - 28px));
            margin: 0 auto;
            padding: 28px 0 40px;
        }

        .header {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 18px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            margin-bottom: 16px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand-mark {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
        }

        h1 {
            font-size: 22px;
            line-height: 1.4;
            margin: 0;
            color: var(--primary-dark);
        }

        .subtitle {
            margin: 4px 0 0;
            color: var(--muted);
            font-size: 13px;
        }

        .notice {
            background: #fff7ed;
            color: #9a3412;
            border: 1px solid #fed7aa;
            padding: 14px 16px;
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
            align-items: flex-start;
            font-weight: 700;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 18px;
        }

        .meta {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .meta-item {
            background: #f8fafc;
            border: 1px solid var(--border);
            padding: 12px;
        }

        .meta-label {
            color: var(--muted);
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .meta-value {
            font-size: 14px;
            font-weight: 700;
            color: var(--text);
        }

        .description {
            color: var(--muted);
            line-height: 1.8;
            margin: 0 0 16px;
        }

        .video-frame {
            background: #111827;
            border: 1px solid #111827;
        }

        video, audio {
            display: block;
            width: 100%;
            max-height: 70vh;
            background: #111827;
        }

        .download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 14px;
            padding: 10px 14px;
            color: #fff;
            background: var(--primary);
            text-decoration: none;
            font-weight: 700;
        }

        .download:hover {
            background: var(--primary-dark);
        }

        .footer {
            color: var(--muted);
            font-size: 12px;
            text-align: center;
            margin-top: 18px;
        }

        @media (max-width: 700px) {
            .page {
                width: min(100% - 18px, 980px);
                padding-top: 14px;
            }

            .header {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px;
            }

            h1 { font-size: 18px; }

            .meta {
                grid-template-columns: 1fr;
            }

            .card {
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <main class="page">
        <section class="header">
            <div class="brand">
                <div class="brand-mark">
                    <i class="fas fa-play"></i>
                </div>
                <div>
                    <h1>{{ $documentation->title ?? 'توثيق الطلب' }}</h1>
                    <p class="subtitle">رابط مشاهدة خاص بعميل الطلب من منصة وسيلة</p>
                </div>
            </div>
        </section>

        <div class="notice">
            <i class="fas fa-triangle-exclamation"></i>
            <div>هذا الرابط خاص بك فقط. لا تشارك هذا الرابط مع عملاء آخرين.</div>
        </div>

        <section class="card">
            <div class="meta">
                <div class="meta-item">
                    <div class="meta-label">رقم الطلب</div>
                    <div class="meta-value">{{ $order->order_number ?? 'غير متاح' }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">تاريخ الرفع</div>
                    <div class="meta-value">{{ $documentation->created_at?->format('Y-m-d H:i') }}</div>
                </div>
                <div class="meta-item">
                    <div class="meta-label">حجم الملف</div>
                    <div class="meta-value">{{ $documentation->formatted_file_size ?? 'غير متاح' }}</div>
                </div>
            </div>

            @if($documentation->description)
                <p class="description">{{ $documentation->description }}</p>
            @endif

            <div class="video-frame">
                @if(str_starts_with($documentation->mime_type ?? '', 'audio/'))
                    <audio controls preload="metadata">
                        <source src="{{ $documentation->video_url }}" type="{{ $documentation->mime_type }}">
                        متصفحك لا يدعم تشغيل الملف الصوتي.
                    </audio>
                @else
                    <video controls preload="metadata" playsinline>
                        <source src="{{ $documentation->video_url }}" type="{{ $documentation->mime_type ?: 'video/mp4' }}">
                        <source src="{{ $documentation->video_url }}" type="video/mp4">
                        <source src="{{ $documentation->video_url }}" type="video/webm">
                        متصفحك لا يدعم تشغيل الفيديو.
                    </video>
                @endif
            </div>

            <a class="download" href="{{ $documentation->video_url }}" target="_blank" download>
                <i class="fas fa-download"></i>
                تحميل الملف
            </a>
        </section>

        <div class="footer">وسيلة - رابط توثيق خاص بالعميل</div>
    </main>
</body>
</html>
