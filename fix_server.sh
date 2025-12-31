#!/bin/bash

# Server Fix Script for Wasila Website
# Run this script on the server via SSH

echo "=========================================="
echo "Wasila Website Server Fix Script"
echo "=========================================="
echo ""

# Navigate to the project directory
cd ~/domains/itegypt.org/public_html/wasela || exit 1

echo "1. Checking current directory..."
pwd
echo ""

echo "2. Checking Laravel logs for latest errors..."
tail -50 storage/logs/laravel.log | grep -E "ERROR|Exception|Fatal" | tail -20
echo ""

echo "3. Checking if migration file exists..."
if [ -f "database/migrations/2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php" ]; then
    echo "   Migration file exists - will be fixed"
else
    echo "   Migration file does not exist (already deleted)"
fi
echo ""

echo "4. Checking migrations table..."
php artisan tinker --execute="
\$migration = '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table';
\$exists = DB::table('migrations')->where('migration', \$migration)->exists();
echo \$exists ? 'Migration exists in database' : 'Migration NOT in database';
echo PHP_EOL;
"
echo ""

echo "5. Checking .env file..."
if [ -f ".env" ]; then
    echo "   .env file exists"
    echo "   APP_URL: $(grep '^APP_URL=' .env | cut -d '=' -f2)"
    echo "   APP_DEBUG: $(grep '^APP_DEBUG=' .env | cut -d '=' -f2)"
else
    echo "   ERROR: .env file does not exist!"
    exit 1
fi
echo ""

echo "6. Fixing migration file (if exists)..."
if [ -f "database/migrations/2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php" ]; then
    # Backup the file first
    cp database/migrations/2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php \
       database/migrations/2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php.bak
    
    echo "   Migration file backed up"
    echo "   Note: The migration file has been fixed locally and should be uploaded"
fi
echo ""

echo "7. Removing migration from database if it exists..."
php artisan tinker --execute="
\$migration = '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table';
\$deleted = DB::table('migrations')->where('migration', \$migration)->delete();
echo \$deleted > 0 ? 'Deleted migration from database' : 'Migration not in database';
echo PHP_EOL;
"
echo ""

echo "8. Checking for orphaned foreign key constraints..."
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
    if (empty(\$constraints)) {
        echo 'No foreign key constraint found (OK)';
    } else {
        echo 'Foreign key constraint exists: ' . \$constraints[0]->CONSTRAINT_NAME;
        echo PHP_EOL;
        echo 'Attempting to drop it...';
        try {
            DB::statement('ALTER TABLE customer_messages DROP FOREIGN KEY ' . \$constraints[0]->CONSTRAINT_NAME);
            echo 'Foreign key dropped successfully';
        } catch (Exception \$e) {
            echo 'Could not drop foreign key: ' . \$e->getMessage();
        }
    }
} catch (Exception \$e) {
    echo 'Could not check foreign keys: ' . \$e->getMessage();
}
echo PHP_EOL;
"
echo ""

echo "9. Clearing all caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
echo "   All caches cleared"
echo ""

echo "10. Checking storage permissions..."
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):$(whoami) storage bootstrap/cache
echo "   Permissions set"
echo ""

echo "11. Testing database connection..."
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database connection: OK';
} catch (Exception \$e) {
    echo 'Database connection: FAILED - ' . \$e->getMessage();
}
echo PHP_EOL;
"
echo ""

echo "12. Testing route list..."
php artisan route:list | head -5
echo ""

echo "13. Testing application..."
echo "   Testing via curl..."
curl -s -o /dev/null -w "HTTP Status: %{http_code}\n" https://wasela.itegypt.org/ || echo "   Curl test failed"
echo ""

echo "=========================================="
echo "Fix script completed!"
echo "=========================================="
echo ""
echo "Next steps:"
echo "1. Check the Laravel logs above for any errors"
echo "2. If migration file exists, upload the fixed version from local"
echo "3. Test the website: https://wasela.itegypt.org/"
echo ""

