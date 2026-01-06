<?php

/**
 * سكريبت تحويل دليل لوحة التحكم من HTML إلى PDF
 * 
 * الاستخدام:
 * php convert-to-pdf.php
 */

require __DIR__ . '/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// إعدادات dompdf
$options = new Options();
$options->set('isRemoteEnabled', true);
$options->set('isHtml5ParserEnabled', true);
$options->set('defaultFont', 'DejaVu Sans');
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);

// قراءة ملف HTML
$html = file_get_contents(__DIR__ . '/documentation-admin-panel.html');

// تحميل CSS إضافي للخطوط العربية
$html = str_replace(
    '</head>',
    '<style>
        @font-face {
            font-family: "DejaVu Sans";
            src: url("https://fonts.googleapis.com/css2?family=Noto+Sans+Arabic:wght@300;400;500;600;700&display=swap");
        }
        body {
            font-family: "DejaVu Sans", "Noto Sans Arabic", Arial, sans-serif;
        }
    </style>
    </head>',
    $html
);

// تحميل HTML في dompdf
$dompdf->loadHtml($html);

// إعداد حجم الصفحة
$dompdf->setPaper('A4', 'portrait');

// عرض HTML
$dompdf->render();

// حفظ PDF
$output = $dompdf->output();
file_put_contents(__DIR__ . '/دليل-استخدام-لوحة-التحكم.pdf', $output);

echo "✓ تم إنشاء ملف PDF بنجاح!\n";
echo "✓ الملف: دليل-استخدام-لوحة-التحكم.pdf\n";
