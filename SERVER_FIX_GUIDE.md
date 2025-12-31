# Server Fix Guide - 500 Internal Server Error

## Problem Summary
The website is showing a 500 Internal Server Error. The main issue was a foreign key constraint error in the migration `2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php`.

## What Has Been Fixed Locally

1. **Migration File Fixed**: The migration file has been updated to:
   - Handle orphaned records before adding foreign key
   - Check for existing foreign keys
   - Use `onDelete('set null')` instead of `cascade` to prevent data loss
   - Ensure both tables use InnoDB engine
   - Properly handle errors without failing the migration

## Steps to Fix on Server

### Step 1: SSH into Server
```bash
ssh -p 65002 u696043789@212.85.28.110
cd ~/domains/itegypt.org/public_html/wasela
```

### Step 2: Run the Fix Script
Upload the `fix_server.sh` script to the server and run it:
```bash
chmod +x fix_server.sh
./fix_server.sh
```

Or run these commands manually:

### Step 3: Remove Migration from Database (if exists)
```bash
php artisan tinker --execute="
\$migration = '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table';
DB::table('migrations')->where('migration', \$migration)->delete();
echo 'Migration removed from database';
"
```

### Step 4: Drop Foreign Key Constraint (if exists)
```bash
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
        DB::statement('ALTER TABLE customer_messages DROP FOREIGN KEY ' . \$constraints[0]->CONSTRAINT_NAME);
        echo 'Foreign key dropped';
    } else {
        echo 'No foreign key found';
    }
} catch (Exception \$e) {
    echo 'Error: ' . \$e->getMessage();
}
"
```

### Step 5: Upload Fixed Migration File
Upload the fixed migration file from your local machine:
```bash
# On your local machine, upload the file:
scp -P 65002 wasila-charity/database/migrations/2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table.php \
    u696043789@212.85.28.110:~/domains/itegypt.org/public_html/wasela/database/migrations/
```

### Step 6: Verify .env File
Check that APP_URL is set correctly:
```bash
grep "APP_URL" .env
```

It should be:
```
APP_URL=https://wasela.itegypt.org/
```

If it's empty or wrong, fix it:
```bash
nano .env
# Set: APP_URL=https://wasela.itegypt.org/
```

### Step 7: Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 8: Fix Permissions
```bash
chmod -R 775 storage bootstrap/cache
chown -R $(whoami):$(whoami) storage bootstrap/cache
```

### Step 9: Check Latest Errors
```bash
tail -100 storage/logs/laravel.log | grep -E "ERROR|Exception|Fatal" | tail -30
```

### Step 10: Test the Application
```bash
# Test via curl
curl -I https://wasela.itegypt.org/

# Or test a route
php artisan route:list | head -5
```

## If Still Getting 500 Error

### Check Laravel Logs
```bash
tail -200 storage/logs/laravel.log
```

### Enable Debug Mode Temporarily
```bash
# Edit .env
nano .env
# Change: APP_DEBUG=true
# Then clear config cache
php artisan config:clear
```

### Check PHP Error Log
```bash
tail -50 /home/u696043789/logs/error.log
# Or check your hosting panel for PHP error logs
```

### Verify Database Connection
```bash
php artisan tinker --execute="
try {
    DB::connection()->getPdo();
    echo 'Database: OK';
} catch (Exception \$e) {
    echo 'Database Error: ' . \$e->getMessage();
}
"
```

## Expected Result

After running these steps, the website should:
- Load without 500 errors
- Show the homepage correctly
- All routes should work

## Notes

- The migration file is now safe and won't cause errors even if it runs
- The foreign key constraint is optional - the application will work without it
- If the foreign key can't be created, it will be logged but won't stop the application

