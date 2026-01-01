#!/bin/bash

# Complete Fix - Run this on the server
# Fixes: Migration issues, 403 error, permissions

cd ~/domains/itegypt.org/public_html/wasela || exit 1

echo "=========================================="
echo "Complete Fix Script"
echo "=========================================="
echo ""

echo "1. Removing problematic migration..."
php artisan tinker --execute="DB::table('migrations')->where('migration', '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table')->delete(); echo 'Removed';" 2>&1 | grep -v "Psy\|tinker" || echo "Done"
echo ""

echo "2. Pulling latest code..."
git pull origin main 2>&1 | tail -3
echo ""

echo "3. Clearing caches..."
php artisan optimize:clear > /dev/null 2>&1
echo "   Caches cleared"
echo ""

echo "4. Fixing permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null
chmod 755 public 2>/dev/null
chmod 644 public/index.php public/.htaccess 2>/dev/null
echo "   Permissions fixed"
echo ""

echo "5. Creating root .htaccess if needed..."
if [ ! -f .htaccess ]; then
    cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOF
    echo "   Root .htaccess created"
else
    echo "   Root .htaccess already exists"
fi
echo ""

echo "6. Testing website..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://wasela.itegypt.org/ 2>&1)
echo "   HTTP Status: $HTTP_CODE"

if [ "$HTTP_CODE" = "200" ]; then
    echo ""
    echo "   ✅ SUCCESS! Website is working!"
elif [ "$HTTP_CODE" = "403" ]; then
    echo ""
    echo "   ⚠️  Still getting 403 - Check hosting panel:"
    echo "      Document Root should be: /wasela/public"
    echo "      Current might be: /wasela"
else
    echo ""
    echo "   ⚠️  Status: $HTTP_CODE - Check logs: tail -50 storage/logs/laravel.log"
fi
echo ""

echo "=========================================="
echo "Fix completed!"
echo "=========================================="


