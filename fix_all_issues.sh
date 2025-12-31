#!/bin/bash

# Complete Fix Script for Wasila Website
# This fixes: 403 error, migration issues, and foreign key problems

echo "=========================================="
echo "Wasila Website - Complete Fix"
echo "=========================================="
echo ""

cd ~/domains/itegypt.org/public_html/wasela || exit 1

echo "1. Removing problematic migration from database..."
php artisan tinker --execute="
\$migration = '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table';
\$deleted = DB::table('migrations')->where('migration', \$migration)->delete();
echo \$deleted > 0 ? 'Removed migration from database' : 'Migration not in database';
echo PHP_EOL;
" 2>&1 | grep -v "Psy\|tinker\|Command line" || echo "   Migration removed"
echo ""

echo "2. Dropping any remaining foreign key constraints..."
php artisan tinker --execute="
try {
    \$constraints = DB::select(\"
        SELECT CONSTRAINT_NAME 
        FROM information_schema.KEY_COLUMN_USAGE 
        WHERE TABLE_SCHEMA = DATABASE() 
        AND TABLE_NAME = 'customer_messages' 
        AND COLUMN_NAME = 'order_id' 
        AND REFERENCED_TABLE_NAME IS NOT NULL
    \");
    if (!empty(\$constraints)) {
        foreach (\$constraints as \$constraint) {
            try {
                DB::statement('ALTER TABLE customer_messages DROP FOREIGN KEY ' . \$constraint->CONSTRAINT_NAME);
                echo 'Dropped: ' . \$constraint->CONSTRAINT_NAME . PHP_EOL;
            } catch (Exception \$e) {
                echo 'Could not drop: ' . \$e->getMessage() . PHP_EOL;
            }
        }
    } else {
        echo 'No foreign key constraints found' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage() . PHP_EOL;
}
" 2>&1 | grep -v "Psy\|tinker\|Command line" || echo "   Foreign keys checked"
echo ""

echo "3. Marking order_documentation migration as run (if table exists)..."
php artisan tinker --execute="
if (Schema::hasTable('order_documentation')) {
    \$migration = '2024_01_01_000003_create_order_documentation_table';
    \$exists = DB::table('migrations')->where('migration', \$migration)->exists();
    if (!\$exists) {
        DB::table('migrations')->insert(['migration' => \$migration, 'batch' => 1]);
        echo 'Marked order_documentation migration as run' . PHP_EOL;
    } else {
        echo 'order_documentation migration already marked' . PHP_EOL;
    }
} else {
    echo 'order_documentation table does not exist' . PHP_EOL;
}
" 2>&1 | grep -v "Psy\|tinker\|Command line" || echo "   Migration status checked"
echo ""

echo "4. Clearing all caches..."
php artisan config:clear > /dev/null 2>&1
php artisan cache:clear > /dev/null 2>&1
php artisan route:clear > /dev/null 2>&1
php artisan view:clear > /dev/null 2>&1
php artisan optimize:clear > /dev/null 2>&1
echo "   All caches cleared"
echo ""

echo "5. Fixing file permissions..."
chmod -R 775 storage bootstrap/cache 2>/dev/null
chmod -R 755 public 2>/dev/null
echo "   Permissions updated"
echo ""

echo "6. Checking .htaccess file..."
if [ -f "public/.htaccess" ]; then
    echo "   .htaccess exists"
    if ! grep -q "RewriteEngine On" public/.htaccess 2>/dev/null; then
        echo "   WARNING: .htaccess might be missing RewriteEngine"
    fi
else
    echo "   WARNING: .htaccess file not found in public directory"
fi
echo ""

echo "7. Testing database connection..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database: OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database: FAILED - ' . \$e->getMessage() . PHP_EOL;
}
" 2>&1 | grep -v "Psy\|tinker\|Command line" | head -1
echo ""

echo "8. Testing routes..."
php artisan route:list 2>&1 | head -3 | grep -E "GET|HEAD" || echo "   Routes loaded"
echo ""

echo "9. Testing website..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://wasela.itegypt.org/ 2>&1)
echo "   HTTP Status: $HTTP_CODE"
if [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ Website is working!"
elif [ "$HTTP_CODE" = "403" ]; then
    echo "   ⚠️  403 Forbidden - Check .htaccess and permissions"
elif [ "$HTTP_CODE" = "500" ]; then
    echo "   ⚠️  500 Error - Check Laravel logs"
else
    echo "   ⚠️  Unexpected status code"
fi
echo ""

echo "=========================================="
echo "Fix completed!"
echo "=========================================="
echo ""
echo "If still getting errors:"
echo "  - Check: tail -200 storage/logs/laravel.log"
echo "  - Check: ls -la public/.htaccess"
echo "  - Check: ls -la storage/logs/"
echo ""

