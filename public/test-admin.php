<?php

require_once '../vendor/autoload.php';

$app = require_once '../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;

// محاكاة ما يحدث في الـ controller
$settings = Setting::getAll();

?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>اختبار بيانات الإدارة</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .section { border: 1px solid #ccc; padding: 15px; margin: 10px 0; }
        .field { margin: 10px 0; }
        label { font-weight: bold; display: block; }
        input, textarea { width: 100%; padding: 5px; margin: 5px 0; }
        textarea { height: 100px; }
    </style>
</head>
<body>
    <h1>اختبار بيانات إدارة المحتوى</h1>
    
    <div class="section">
        <h2>قسم من نحن</h2>
        <div class="field">
            <label>عنوان القسم (عربي):</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['about_title_ar'] ?? ''); ?>">
        </div>
        <div class="field">
            <label>الوصف (عربي):</label>
            <textarea><?php echo htmlspecialchars($settings['about_description_ar'] ?? ''); ?></textarea>
        </div>
        <div class="field">
            <label>المهمة (عربي):</label>
            <textarea><?php echo htmlspecialchars($settings['about_mission_ar'] ?? ''); ?></textarea>
        </div>
    </div>
    
    <div class="section">
        <h2>قسم لماذا تختار وسيلة؟</h2>
        <div class="field">
            <label>عنوان القسم (عربي):</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['why_choose_title_ar'] ?? ''); ?>">
        </div>
        <div class="field">
            <label>العنوان الفرعي (عربي):</label>
            <textarea><?php echo htmlspecialchars($settings['why_choose_subtitle_ar'] ?? ''); ?></textarea>
        </div>
    </div>
    
    <div class="section">
        <h2>المميزات</h2>
        <div class="field">
            <label>الميزة الأولى - العنوان:</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['feature1_title_ar'] ?? ''); ?>">
        </div>
        <div class="field">
            <label>الميزة الأولى - الوصف:</label>
            <textarea><?php echo htmlspecialchars($settings['feature1_description_ar'] ?? ''); ?></textarea>
        </div>
    </div>
    
    <div class="section">
        <h2>الإحصائيات</h2>
        <div class="field">
            <label>الإحصائية الأولى - الرقم:</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['stat1_number'] ?? ''); ?>">
        </div>
        <div class="field">
            <label>الإحصائية الأولى - التسمية:</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['stat1_label_ar'] ?? ''); ?>">
        </div>
    </div>
    
    <div class="section">
        <h2>الصور</h2>
        <div class="field">
            <label>صورة الهيرو:</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['hero_image'] ?? ''); ?>">
        </div>
        <div class="field">
            <label>صورة من نحن:</label>
            <input type="text" value="<?php echo htmlspecialchars($settings['about_image'] ?? ''); ?>">
        </div>
    </div>
    
    <p><strong>إجمالي الإعدادات:</strong> <?php echo count($settings); ?></p>
</body>
</html>
