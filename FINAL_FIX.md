# Final Fix Guide - 403 and Migration Issues

## Issues Identified:
1. ✅ Foreign key constraint dropped successfully
2. ⚠️ HTTP 403 Forbidden - Web server configuration issue
3. ⚠️ Migration still trying to run with old code

## Step-by-Step Fix

### Step 1: Remove Migration from Database
```bash
cd ~/domains/itegypt.org/public_html/wasela

php artisan tinker --execute="
\$migration = '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table';
DB::table('migrations')->where('migration', \$migration)->delete();
echo 'Migration removed';
"
```

### Step 2: Pull Latest Code (with fixed migration)
```bash
git pull origin main
```

### Step 3: Clear All Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### Step 4: Fix 403 Error - Check Web Server Configuration

The 403 error suggests the web server isn't pointing to the `public` directory. Check:

#### Option A: If using subdomain `wasela.itegypt.org`
The document root should point to: `~/domains/itegypt.org/public_html/wasela/public`

Check your hosting panel:
- Go to Subdomain settings
- Ensure Document Root is: `/home/u696043789/domains/itegypt.org/public_html/wasela/public`

#### Option B: If using main domain with subdirectory
Create a `.htaccess` in the root `wasela` directory:

```bash
cd ~/domains/itegypt.org/public_html/wasela
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
EOF
```

### Step 5: Verify Public Directory Structure
```bash
ls -la public/
# Should show: index.php, .htaccess, and other files
```

### Step 6: Test the Website
```bash
curl -I https://wasela.itegypt.org/
# Should return HTTP 200, not 403
```

### Step 7: If Still 403 - Check Permissions
```bash
chmod 755 public
chmod 644 public/index.php
chmod 644 public/.htaccess
chmod -R 775 storage bootstrap/cache
```

### Step 8: Check Laravel Logs
```bash
tail -50 storage/logs/laravel.log
```

## Quick Fix Script

Run this complete fix:

```bash
cd ~/domains/itegypt.org/public_html/wasela

# 1. Remove migration
php artisan tinker --execute="DB::table('migrations')->where('migration', '2025_12_31_171320_add_order_id_foreign_key_to_customer_messages_table')->delete(); echo 'Done';"

# 2. Pull latest code
git pull origin main

# 3. Clear caches
php artisan optimize:clear

# 4. Fix permissions
chmod -R 775 storage bootstrap/cache
chmod 755 public
chmod 644 public/index.php public/.htaccess

# 5. Create root .htaccess if needed
if [ ! -f .htaccess ]; then
    echo '<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>' > .htaccess
fi

# 6. Test
curl -I https://wasela.itegypt.org/
```

## Expected Results

After running these steps:
- ✅ Migration removed from database
- ✅ Latest code with fixed migration pulled
- ✅ All caches cleared
- ✅ Website should return HTTP 200 instead of 403

## If Still Getting 403

The issue is likely in your hosting panel configuration. You need to:

1. **Check Subdomain Settings**: 
   - Login to your hosting control panel (hPanel)
   - Go to Subdomains
   - Find `wasela.itegypt.org`
   - Check the Document Root path
   - It should be: `/home/u696043789/domains/itegypt.org/public_html/wasela/public`
   - If it's pointing to `/wasela` or `/wasela/`, change it to `/wasela/public`

2. **Or use the root .htaccess method** (already created in step 4)

