# Wasila Charity Website - Production Setup Guide

## Issue: Images showing 403 errors on production domain

The images work locally but show 403 (Forbidden) errors on `https://wasela.itegypt.org/`. This is a common issue with Laravel storage configuration on production servers.

## Quick Fix Steps

### 1. Upload and Run Deployment Script
1. Upload `deploy-production.php` to your website root directory
2. Run it via browser: `https://wasela.itegypt.org/deploy-production.php`
3. Or run via SSH: `php deploy-production.php`

### 2. Fix Image Database Paths
1. Upload `fix-image-paths.php` to your website root directory  
2. Run it via SSH: `php fix-image-paths.php`
3. Or run via browser: `https://wasela.itegypt.org/fix-image-paths.php`

### 3. Manual Steps (if scripts don't work)

#### Create Storage Symlink
```bash
# SSH into your server and run:
cd /path/to/your/website
rm -f public/storage
ln -s ../storage/app/public public/storage
```

#### Set Correct Permissions
```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod -R 644 storage/app/public/services/*
chmod -R 644 storage/app/public/portfolio/*
```

#### Create .htaccess for Storage
Create `public/storage/.htaccess` with this content:
```apache
<IfModule mod_mime.c>
    AddType image/png .png
    AddType image/jpeg .jpg .jpeg
    AddType image/gif .gif
</IfModule>

<Files "*">
    Order Allow,Deny
    Allow from all
</Files>

Options -Indexes
Options +FollowSymLinks
```

## Testing

After running the fixes, test these URLs:
- `https://wasela.itegypt.org/storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png`
- `https://wasela.itegypt.org/storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png`

## Common Issues & Solutions

### 1. Symlink Not Supported
Some shared hosting providers don't support symlinks. Solutions:
- Copy files instead: `cp -r storage/app/public/* public/storage/`
- Use Laravel's `storage:link` command: `php artisan storage:link`
- Contact hosting provider to enable symlink support

### 2. Permission Issues
```bash
# Set ownership (replace 'username' with your actual username)
chown -R username:username storage/
chown -R username:username public/storage/

# Set permissions
find storage/ -type d -exec chmod 755 {} \;
find storage/ -type f -exec chmod 644 {} \;
```

### 3. Apache Configuration
Add to `.htaccess` in root directory:
```apache
# Enable following symlinks
Options +FollowSymLinks

# Allow access to storage files
<Directory "public/storage">
    Order Allow,Deny
    Allow from all
</Directory>
```

### 4. Nginx Configuration
Add to your nginx config:
```nginx
location /storage {
    alias /path/to/your/website/storage/app/public;
    expires 30d;
    add_header Cache-Control "public, immutable";
}
```

## Environment Configuration

Make sure your `.env` file has:
```env
APP_URL=https://wasela.itegypt.org
FILESYSTEM_DISK=public
```

## Database Configuration

Your current database settings look correct:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u696043789_wasila
DB_USERNAME=u696043789_wasila
DB_PASSWORD=support@Passord123
```

## Clear Caches After Changes

Run these commands after making changes:
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

Or use the `clear-cache.php` script created by the deployment script.

## Contact Hosting Provider If Issues Persist

If images still don't load after trying all steps above, contact your hosting provider (itegypt.org) and ask them to:

1. Enable symlink support for your account
2. Check Apache/Nginx configuration for static file serving
3. Verify file permissions are set correctly
4. Check if there are any security restrictions blocking access to the storage directory

## Files Created by This Setup

- `deploy-production.php` - Automated deployment script
- `fix-image-paths.php` - Database path correction script  
- `clear-cache.php` - Cache clearing utility
- `public/storage/.htaccess` - Storage directory access rules

Delete these files after successful deployment for security.
