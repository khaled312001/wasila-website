# Wasila Website Deployment Guide

## Current Issues Identified

1. **Missing .env file** - Critical for Laravel to work
2. **Database connection** - Need to verify MySQL connection
3. **File permissions** - Storage and cache directories need proper permissions
4. **Composer dependencies** - Need to install/update

## Step-by-Step Deployment Instructions

### 1. Fix File Structure (CRITICAL - Fixes 403 Forbidden Error)

SSH into your server and navigate to your Laravel project directory:

```bash
ssh -p 65002 u696043789@212.85.28.110
cd ~/domains/itegypt.org/public_html/
```

**IMPORTANT**: If you cloned the repository into a subdirectory (like `wasila-website`), you need to move all files to the root of `public_html`:

```bash
# Move all Laravel files from wasila-website subdirectory to the root
mv wasila-website/* .
mv wasila-website/.* . 2>/dev/null || true

# Remove the empty wasila-website directory
rmdir wasila-website
```

### 2. Create .env File on Server

Create the `.env` file with your configuration:

```bash
nano .env
```

Copy and paste this content:

```env
APP_NAME=Laravel
APP_ENV=production
APP_KEY=base64:+FgmAsLRxbn1Bp7krO167Y7K7EsXwuoYQtJBJt/uncs=
APP_DEBUG=false
APP_URL=https://itegypt.org/

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u696043789_wasila
DB_USERNAME=u696043789_wasila
DB_PASSWORD=support@Passord123

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_SCHEME=null
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false
MYFATOORAH_TEST_MODE=true
MYFATOORAH_COUNTRY_ISO=SAU
```

Save the file (Ctrl+X, then Y, then Enter).

### 3. Set Proper File Permissions

```bash
# Set permissions for storage and cache directories
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/

# Set ownership (replace with your actual user/group)
chown -R u696043789:u696043789 storage/
chown -R u696043789:u696043789 bootstrap/cache/
```

### 4. Install/Update Composer Dependencies

```bash
# Install composer if not already installed
php composer.phar install --no-dev --optimize-autoloader
```

### 5. Clear and Cache Configuration

```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Run Database Migrations

```bash
# Run migrations to create database tables
php artisan migrate --force
```

### 7. Create Storage Link (if needed)

```bash
# Create symbolic link for storage
php artisan storage:link
```

### 8. Test Database Connection

```bash
# Test database connection
php artisan tinker
# In tinker, run: DB::connection()->getPdo();
# Exit with: exit
```

### 9. Check Laravel Logs

```bash
# Check for any errors
tail -f storage/logs/laravel.log
```

## Important Notes

1. **APP_DEBUG=false** - Set to false in production for security
2. **APP_ENV=production** - Set to production environment
3. **LOG_LEVEL=error** - Only log errors in production
4. **Database credentials** - Verify these match your hosting provider's MySQL settings

## Common Issues and Solutions

### 403 Forbidden Error
- **MOST COMMON CAUSE**: Laravel files are in a subdirectory instead of the root of public_html
- **SOLUTION**: Move all files from `wasila-website/` subdirectory to the root of `public_html/`
- Check file permissions on storage/ and bootstrap/cache/
- Ensure .env file exists and is readable
- Verify web server can access the files

### 500 Internal Server Error
- Check Laravel logs: `tail -f storage/logs/laravel.log`
- Verify database connection
- Ensure all required PHP extensions are installed

### Database Connection Issues
- Verify database credentials in .env
- Check if MySQL service is running
- Ensure database exists and user has proper permissions

### SSL Certificate Issues
- **ERR_CERT_DATE_INVALID**: SSL certificate has expired or invalid date
- **Solution**: Contact hosting provider to renew/repair SSL certificate
- **Temporary workaround**: Use HTTP instead of HTTPS or bypass browser warning
- **Test with**: http://itegypt.org/ (instead of https://)

## After Deployment

1. Test the website: https://itegypt.org/
2. Test admin login: https://itegypt.org/admin/login
3. Check all functionality works properly
4. Monitor logs for any errors

## Security Recommendations

1. Change default admin password
2. Set APP_DEBUG=false in production
3. Use HTTPS (already configured)
4. Regular backups of database and files
5. Keep Laravel and dependencies updated
