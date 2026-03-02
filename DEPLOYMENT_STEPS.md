# خطوات النشر - وظيفة الفاتورة

## الخطوات المطلوبة على السيرفر

### 1. تشغيل Migration (مهم جداً!)
```bash
php artisan migrate
```

هذا سيقوم بإضافة حقل `invoice_path` إلى جدول `orders`.

### 2. التحقق من Migration
```bash
php artisan migrate:status
```

يجب أن ترى migration `2026_01_02_000000_add_invoice_path_to_orders_table` في قائمة المكتملة.

### 3. إنشاء الرابط الرمزي (إذا لم يكن موجوداً)
```bash
php artisan storage:link
```

### 4. التحقق من الصلاحيات
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

### 5. تشغيل اختبار
```bash
php test_invoice_functionality.php
```

## بعد تشغيل Migration

بعد تشغيل `php artisan migrate`، يمكنك:

### أ. اختبار تحميل الفاتورة للطلبات الموجودة

1. اذهب إلى لوحة التحكم > الطلبات
2. افتح أي طلب مدفوع
3. في قسم "معلومات الدفع"، اضغط على "تحميل الفاتورة من MyFatoorah"
4. يجب أن يتم تحميل الفاتورة تلقائياً

### ب. اختبار مع طلب جديد

1. أنشئ طلب جديد
2. ادفع عبر MyFatoorah
3. بعد نجاح الدفع، يجب أن يتم تحميل الفاتورة تلقائياً
4. اذهب إلى لوحة التحكم وتحقق من وجود الفاتورة

## ملاحظات مهمة

1. **Migration ضروري:** بدون تشغيل migration، لن يعمل النظام بشكل صحيح
2. **InvoiceId vs PaymentId:** النظام الآن يحصل على InvoiceId من payment status تلقائياً
3. **السجلات:** جميع العمليات مسجلة في `storage/logs/laravel.log`

## استكشاف الأخطاء

### إذا فشل تحميل الفاتورة:

1. تحقق من السجلات:
```bash
tail -f storage/logs/laravel.log | grep "MyFatoorah"
```

2. تحقق من إعدادات MyFatoorah:
   - API Key صحيح
   - Test Mode يطابق نوع المفتاح

3. جرب تحميل الفاتورة يدوياً من لوحة التحكم
