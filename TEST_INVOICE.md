# دليل اختبار وظيفة الفاتورة

## الخطوات المطلوبة قبل الاختبار

### 1. تشغيل Migration
```bash
php artisan migrate
```

### 2. إنشاء الرابط الرمزي للتخزين
```bash
php artisan storage:link
```

### 3. التأكد من إعدادات MyFatoorah
تأكد من وجود الإعدادات التالية في ملف `.env`:
```
MYFATOORAH_API_KEY=your_api_key_here
MYFATOORAH_TEST_MODE=true
```

## طرق الاختبار

### الطريقة 1: اختبار تلقائي
قم بتشغيل ملف الاختبار:
```bash
php test_invoice_functionality.php
```

### الطريقة 2: اختبار يدوي

#### أ. اختبار تحميل الفاتورة عند الدفع الناجح

1. **إنشاء طلب جديد:**
   - اذهب إلى صفحة الخدمات
   - اختر خدمة
   - املأ بيانات الطلب
   - اختر طريقة الدفع "MyFatoorah"

2. **إتمام الدفع:**
   - أكمل عملية الدفع في MyFatoorah
   - بعد نجاح الدفع، سيتم توجيهك إلى صفحة التأكيد

3. **التحقق من النتائج:**
   - اذهب إلى لوحة التحكم > الطلبات
   - افتح الطلب المدفوع
   - يجب أن ترى قسم "الفاتورة" مع أزرار "عرض الفاتورة" و "تحميل الفاتورة"

#### ب. التحقق من الإيميل

1. **التحقق من إرسال الإيميل:**
   - بعد نجاح الدفع، يجب أن يتلقى الإدمن إيميل
   - يجب أن يكون الإيميل يحتوي على الفاتورة كمرفق PDF

2. **التحقق من محتوى الإيميل:**
   - افتح الإيميل
   - تحقق من وجود مرفق PDF باسم `invoice-ORDER_NUMBER.pdf`

#### ج. التحقق من قاعدة البيانات

```sql
SELECT id, order_number, payment_status, payment_reference, invoice_path 
FROM orders 
WHERE payment_status = 'paid' 
ORDER BY created_at DESC 
LIMIT 5;
```

يجب أن ترى:
- `payment_status` = 'paid'
- `payment_reference` يحتوي على رقم الفاتورة من MyFatoorah
- `invoice_path` يحتوي على مسار الفاتورة المحفوظة

#### د. التحقق من الملفات

```bash
# في Linux/Mac
ls -lh storage/app/public/invoices/

# في Windows
dir storage\app\public\invoices\
```

يجب أن ترى ملفات PDF للفواتير المحملة.

## اختبارات إضافية

### اختبار مع طلب موجود

إذا كان لديك طلب مدفوع بالفعل بدون فاتورة:

1. اذهب إلى لوحة التحكم > الطلبات
2. افتح الطلب
3. إذا كان `payment_reference` موجوداً، يمكنك تحميل الفاتورة يدوياً

### اختبار API مباشرة

يمكنك اختبار تحميل الفاتورة مباشرة من MyFatoorah:

```php
use App\Models\Order;
use App\Http\Controllers\MyFatoorahController;

$order = Order::find(ORDER_ID);
$invoiceId = $order->payment_reference;
$invoicePath = MyFatoorahController::downloadInvoiceFromMyFatoorah($invoiceId, $order);

if ($invoicePath) {
    echo "تم تحميل الفاتورة: " . $invoicePath;
} else {
    echo "فشل تحميل الفاتورة";
}
```

## المشاكل الشائعة وحلولها

### 1. الفاتورة لا تظهر في صفحة الطلبات

**التحقق:**
- تأكد من أن `invoice_path` موجود في قاعدة البيانات
- تأكد من أن الملف موجود في `storage/app/public/invoices/`
- تأكد من وجود الرابط الرمزي: `php artisan storage:link`

### 2. الفاتورة لا تُرفق في الإيميل

**التحقق:**
- تأكد من أن `invoice_path` موجود في الطلب
- تحقق من سجلات Laravel: `storage/logs/laravel.log`
- تأكد من إعدادات البريد الإلكتروني

### 3. فشل تحميل الفاتورة من MyFatoorah

**التحقق:**
- تأكد من صحة `InvoiceId`
- تحقق من إعدادات MyFatoorah (API Key, Test Mode)
- تحقق من سجلات Laravel للأخطاء

### 4. Migration لا يعمل

**الحل:**
```bash
php artisan migrate:status
php artisan migrate
```

## سجلات الاختبار

جميع العمليات يتم تسجيلها في:
- `storage/logs/laravel.log`

ابحث عن:
- `MyFatoorah: Downloading invoice`
- `MyFatoorah: Invoice downloaded and saved successfully`
- `Failed to send order email`

## ملاحظات

1. **في وضع الاختبار (Test Mode):**
   - قد لا تكون جميع الفواتير متاحة للتحميل
   - بعض الفواتير قد تحتاج وقتاً قبل أن تصبح متاحة

2. **الأمان:**
   - الفواتير محفوظة في `storage/app/public/invoices/`
   - تأكد من أن المجلد محمي بشكل صحيح

3. **الأداء:**
   - تحميل الفاتورة قد يستغرق بضع ثوانٍ
   - إذا فشل التحميل، سيتم تسجيل الخطأ في السجلات
