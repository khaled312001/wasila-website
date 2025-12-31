#!/bin/bash

# Quick Fix Script for Wasila Website
# Run this on the server

echo "=========================================="
echo "Wasila Website Quick Fix"
echo "=========================================="
echo ""

# Navigate to project
cd ~/domains/itegypt.org/public_html/wasela || {
    echo "ERROR: Could not find wasela directory"
    exit 1
}

echo "1. Current directory: $(pwd)"
echo ""

echo "2. Checking latest Laravel errors..."
if [ -f "storage/logs/laravel.log" ]; then
    tail -100 storage/logs/laravel.log | grep -E "ERROR|Exception|Fatal" | tail -30
else
    echo "   No Laravel log file found"
fi
echo ""

echo "3. Verifying .env APP_URL..."
if [ -f ".env" ]; then
    grep "APP_URL" .env
else
    echo "   ERROR: .env file not found!"
    exit 1
fi
echo ""

echo "4. Clearing all caches..."
php artisan config:clear 2>&1
php artisan cache:clear 2>&1
php artisan route:clear 2>&1
php artisan view:clear 2>&1
php artisan optimize:clear 2>&1
echo "   Caches cleared"
echo ""

echo "5. Checking for foreign key constraints..."
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
        echo 'Found foreign key: ' . \$constraints[0]->CONSTRAINT_NAME . PHP_EOL;
        try {
            DB::statement('ALTER TABLE customer_messages DROP FOREIGN KEY ' . \$constraints[0]->CONSTRAINT_NAME);
            echo 'Foreign key dropped successfully' . PHP_EOL;
        } catch (Exception \$e) {
            echo 'Could not drop: ' . \$e->getMessage() . PHP_EOL;
        }
    } else {
        echo 'No foreign key constraint found (OK)' . PHP_EOL;
    }
} catch (Exception \$e) {
    echo 'Error checking constraints: ' . \$e->getMessage() . PHP_EOL;
}
" 2>&1
echo ""

echo "6. Checking database connection..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database connection: OK' . PHP_EOL;
} catch (Exception \$e) {
    echo 'Database connection: FAILED - ' . \$e->getMessage() . PHP_EOL;
}
" 2>&1
echo ""

echo "7. Testing routes..."
php artisan route:list 2>&1 | head -5
echo ""

echo "8. Fixing permissions..."
chmod -R 775 storage bootstrap/cache 2>&1
echo "   Permissions updated"
echo ""

echo "9. Testing website..."
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" https://wasela.itegypt.org/ 2>&1
echo ""

echo "=========================================="
echo "Quick fix completed!"
echo "=========================================="
echo ""
echo "If still getting 500 error, check:"
echo "  tail -200 storage/logs/laravel.log"
echo ""

