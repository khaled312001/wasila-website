#!/bin/bash
# تشغيل migration محدد فقط لإضافة حقل invoice_path

echo "=========================================="
echo "تشغيل Migration لإضافة حقل invoice_path"
echo "=========================================="
echo ""

# تشغيل migration محدد
php artisan migrate --path=database/migrations/2026_01_02_000000_add_invoice_path_to_orders_table.php

echo ""
echo "=========================================="
echo "تم الانتهاء!"
echo "=========================================="
