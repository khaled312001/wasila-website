# حل مشكلة عدم ظهور صور الخدمات والأعمال على السيرفر

## المشكلة
الصور لا تظهر على السيرفر الأونلاين لأن:
1. الرابط الرمزي (symlink) موجود لكنه معطل أو لا يعمل
2. الصور الموجودة لم تُنسخ إلى `public/storage`

## الحل

### الطريقة الأولى: استخدام أمر Artisan (موصى به)

قم بتشغيل الأمر التالي على السيرفر:

```bash
php artisan storage:sync-images
```

هذا الأمر سيقوم بـ:
- نسخ جميع صور الخدمات من `storage/app/public/services` إلى `public/storage/services`
- نسخ جميع صور الأعمال من `storage/app/public/portfolio` إلى `public/storage/portfolio`

**خيارات إضافية:**
```bash
# مزامنة صور الخدمات فقط
php artisan storage:sync-images --service-only

# مزامنة صور الأعمال فقط
php artisan storage:sync-images --portfolio-only

# إجبار المزامنة حتى لو كانت الملفات موجودة
php artisan storage:sync-images --force
```

### الطريقة الثانية: إصلاح الرابط الرمزي

إذا كان السيرفر يدعم symlinks، يمكنك إصلاح الرابط:

```bash
# حذف الرابط القديم (إذا كان معطلاً)
rm public/storage

# إنشاء رابط رمزي جديد
php artisan storage:link
```

### الطريقة الثالثة: استخدام لوحة التحكم

1. سجل الدخول إلى لوحة التحكم
2. اذهب إلى **إدارة الخدمات** → اضغط على زر **مزامنة الصور**
3. اذهب إلى **إدارة الأعمال** → اضغط على زر **مزامنة الصور**

## التحقق من الحل

بعد تنفيذ الحل، تحقق من:
1. افتح الموقع وتحقق من ظهور صور الخدمات
2. افتح صفحة الأعمال وتحقق من ظهور الصور
3. تحقق من وجود الملفات في `public/storage/services` و `public/storage/portfolio`

## ملاحظات مهمة

- تأكد من أن مجلد `public/storage` موجود وله صلاحيات الكتابة (755)
- تأكد من أن جميع الملفات في `storage/app/public` موجودة
- إذا استمرت المشكلة، تحقق من سجلات Laravel: `storage/logs/laravel.log`

## الأوامر المضافة

تم إضافة الأوامر والوظائف التالية:

1. **أمر Artisan**: `php artisan storage:sync-images`
2. **Method في PortfolioItem**: `getFileUrlAttribute()` و `getThumbnailUrlAttribute()`
3. **Route في لوحة التحكم**: `/admin/portfolio/sync-images`
4. **Route في لوحة التحكم**: `/admin/services/sync-images` (موجود مسبقاً)

## للمستقبل

عند رفع صور جديدة:
- الصور الجديدة ستُنسخ تلقائياً إلى `public/storage`
- لا حاجة لتشغيل الأمر مرة أخرى إلا إذا كانت هناك صور قديمة
