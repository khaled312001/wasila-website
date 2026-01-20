# تشغيل Migration محدد فقط

## الطريقة 1: استخدام --path (موصى بها)

### على Linux/Mac:
```bash
php artisan migrate --path=database/migrations/2026_01_02_000000_add_invoice_path_to_orders_table.php
```

### على Windows:
```bash
php artisan migrate --path=database/migrations/2026_01_02_000000_add_invoice_path_to_orders_table.php
```

### أو استخدم الملف الجاهز:
```bash
# Linux/Mac
bash run_invoice_migration.sh

# Windows
run_invoice_migration.bat
```

## الطريقة 2: استخدام --step=1

هذا سيشغل migration واحد فقط (الأول في قائمة الانتظار):

```bash
php artisan migrate --step=1
```

⚠️ **تحذير:** هذا قد يشغل migration آخر إذا كان هناك migrations أخرى في قائمة الانتظار.

## الطريقة 3: التحقق أولاً

قبل التشغيل، تحقق من حالة migrations:

```bash
php artisan migrate:status
```

ابحث عن:
```
[ ] 2026_01_02_000000_add_invoice_path_to_orders_table
```

إذا كان `[ ]` (غير مكتمل)، يمكنك تشغيله.

## الطريقة 4: Rollback محدد (إذا أردت التراجع)

إذا أردت التراجع عن migration محدد فقط:

```bash
php artisan migrate:rollback --path=database/migrations/2026_01_02_000000_add_invoice_path_to_orders_table.php
```

## التحقق من النجاح

بعد التشغيل، تحقق من:

```bash
php artisan migrate:status
```

يجب أن ترى:
```
[✓] 2026_01_02_000000_add_invoice_path_to_orders_table
```

أو تحقق من قاعدة البيانات مباشرة:

```sql
DESCRIBE orders;
```

يجب أن ترى حقل `invoice_path` في القائمة.

## ملاحظات

- استخدام `--path` هو الأكثر أماناً لأنه يشغل migration محدد فقط
- لن يؤثر على migrations أخرى
- لن يفقد أي بيانات موجودة
- فقط سيضيف عمود جديد `invoice_path` إلى جدول `orders`
