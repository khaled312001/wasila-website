# مشكلة تحميل الفاتورة من MyFatoorah

## المشكلة الحالية

تم الحصول على `InvoiceId` بنجاح (65646318) لكن تحميل الفاتورة من URL فشل.

## الأسباب المحتملة

1. **وضع الإنتاج:** في وضع الإنتاج، قد تحتاج الفواتير لـ authentication
2. **URL مختلف:** قد يكون URL الفاتورة مختلف في وضع الإنتاج
3. **صلاحيات:** قد تحتاج صلاحيات خاصة لتحميل الفاتورة

## الحلول المطبقة

### 1. محاولة عدة URLs
الكود الآن يحاول 3 صيغ مختلفة:
- `{portal}/invoice/{InvoiceId}/pdf`
- `{portal}/invoices/{InvoiceId}/pdf`
- `{portal}/api/v2/Invoices/{InvoiceId}/pdf`

### 2. Authentication Headers
يحاول مع وبدون authentication headers

### 3. Logging مفصل
جميع المحاولات مسجلة في `storage/logs/laravel.log`

## الحل البديل: استخدام رابط الفاتورة مباشرة

إذا فشل التحميل التلقائي، يمكنك:

### أ. إضافة رابط الفاتورة في قاعدة البيانات

بدلاً من تحميل الفاتورة، يمكن حفظ رابط الفاتورة:

```php
// في MyFatoorahController
$invoiceUrl = rtrim($portalBase, '/') . '/invoice/' . $invoiceId;
$order->update([
    'invoice_path' => $invoiceUrl, // حفظ URL بدلاً من مسار الملف
    'invoice_type' => 'url' // إضافة حقل جديد للتمييز
]);
```

### ب. عرض رابط الفاتورة في الصفحة

في `resources/views/admin/orders/show.blade.php`:

```blade
@if($order->invoice_path)
    @if(strpos($order->invoice_path, 'http') === 0)
        {{-- رابط URL --}}
        <a href="{{ $order->invoice_path }}" target="_blank">عرض الفاتورة في MyFatoorah</a>
    @else
        {{-- ملف محلي --}}
        <a href="{{ Storage::url($order->invoice_path) }}" target="_blank">عرض الفاتورة</a>
    @endif
@endif
```

## التحقق من السجلات

تحقق من السجلات لمعرفة السبب الدقيق:

```bash
tail -f storage/logs/laravel.log | grep "MyFatoorah"
```

ابحث عن:
- `MyFatoorah: Failed to download invoice from all URLs`
- `status_code` - ما هو رمز الخطأ؟
- `response_body` - ما هي رسالة الخطأ؟

## اختبار يدوي

جرب فتح رابط الفاتورة مباشرة في المتصفح:

```
https://portal.myfatoorah.com/invoice/65646318
```

أو:

```
https://portal.myfatoorah.com/invoice/65646318/pdf
```

إذا عمل في المتصفح لكن لا يعمل في الكود، المشكلة قد تكون في:
- User-Agent headers
- Cookies/Session
- CORS

## الحل الموصى به

إذا استمرت المشكلة، استخدم **حفظ رابط الفاتورة** بدلاً من تحميل الملف:

1. احفظ `invoice_url` في قاعدة البيانات
2. اعرض رابط مباشر للفاتورة في MyFatoorah
3. هذا أسهل وأكثر موثوقية

## الخطوات التالية

1. تحقق من السجلات لمعرفة السبب الدقيق
2. جرب رابط الفاتورة يدوياً في المتصفح
3. إذا لم يعمل، استخدم الحل البديل (حفظ URL)
