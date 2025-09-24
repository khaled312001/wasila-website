# إعداد ماي فاتورة - MyFatoorah Setup

## متطلبات الإعداد

### 1. الحصول على API Key من ماي فاتورة

1. قم بإنشاء حساب في [ماي فاتورة](https://myfatoorah.com)
2. انتقل إلى لوحة التحكم
3. احصل على API Key من قسم الإعدادات
4. للاختبار، استخدم Test API Key
5. للإنتاج، استخدم Live API Key

### 2. إعداد متغيرات البيئة

أضف المتغيرات التالية إلى ملف `.env`:

```env
# MyFatoorah Payment Gateway Configuration
MYFATOORAH_API_KEY=your_myfatoorah_api_key_here
MYFATOORAH_TEST_MODE=true
MYFATOORAH_COUNTRY_ISO=SAU
MYFATOORAH_WEBHOOK_SECRET_KEY=your_webhook_secret_key_here
```

### 3. إعداد Webhook (اختياري)

1. في لوحة تحكم ماي فاتورة، انتقل إلى إعدادات Webhook
2. أضف الرابط التالي: `https://yourdomain.com/myfatoorah/webhook`
3. احصل على Webhook Secret Key وأضفه إلى `.env`

### 4. اختبار النظام

1. تأكد من أن `MYFATOORAH_TEST_MODE=true` للاختبار
2. استخدم بطاقات اختبار من ماي فاتورة
3. اختبر عملية الدفع الكاملة

### 5. الانتقال للإنتاج

1. غيّر `MYFATOORAH_TEST_MODE=false`
2. استخدم Live API Key
3. تأكد من إعداد Webhook للإنتاج

## ملاحظات مهمة

- تأكد من أن جميع الروابط تعمل بشكل صحيح
- اختبر عملية الدفع في بيئة الاختبار أولاً
- احتفظ بنسخة احتياطية من إعدادات الإنتاج
- راقب سجلات الأخطاء بانتظام

## استكشاف الأخطاء

### مشاكل شائعة:

1. **خطأ في API Key**: تأكد من صحة المفتاح
2. **فشل في الاتصال**: تحقق من إعدادات الشبكة
3. **مشاكل في Webhook**: تأكد من صحة الرابط والمفتاح

### سجلات الأخطاء:

تحقق من ملفات السجل في `storage/logs/` لمعرفة تفاصيل الأخطاء.
