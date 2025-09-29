<?php
/**
 * Production Deployment Script for Wasila Charity Website
 * Run this script after uploading files to production server
 */

echo "=== Wasila Charity - Production Deployment Script ===\n\n";

// Step 1: Create storage symlink
echo "1. Creating storage symlink...\n";
if (file_exists('public/storage')) {
    unlink('public/storage');
    echo "   - Removed existing storage symlink\n";
}

if (symlink('../storage/app/public', 'public/storage')) {
    echo "   ✅ Storage symlink created successfully\n";
} else {
    echo "   ❌ Failed to create storage symlink\n";
    echo "   Manual fix: Create symlink from public/storage to ../storage/app/public\n";
}

// Step 2: Set directory permissions
echo "\n2. Setting directory permissions...\n";
$directories = [
    'storage',
    'storage/app',
    'storage/app/public',
    'storage/app/public/services',
    'storage/app/public/portfolio',
    'storage/framework',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache',
    'public/storage'
];

foreach ($directories as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "   ✅ Set permissions for: $dir\n";
    } else {
        mkdir($dir, 0755, true);
        chmod($dir, 0755);
        echo "   ✅ Created and set permissions for: $dir\n";
    }
}

// Step 3: Set file permissions for images
echo "\n3. Setting file permissions for images...\n";
$imageDirectories = ['storage/app/public/services', 'storage/app/public/portfolio'];
foreach ($imageDirectories as $imageDir) {
    if (is_dir($imageDir)) {
        $files = glob($imageDir . '/*');
        foreach ($files as $file) {
            if (is_file($file)) {
                chmod($file, 0644);
            }
        }
        echo "   ✅ Set permissions for files in: $imageDir\n";
    }
}

// Step 4: Create .htaccess for storage directory
echo "\n4. Creating .htaccess for storage access...\n";
$htaccessContent = '<IfModule mod_mime.c>
    AddType image/png .png
    AddType image/jpeg .jpg .jpeg
    AddType image/gif .gif
    AddType image/svg+xml .svg
    AddType image/webp .webp
</IfModule>

<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/svg+xml "access plus 1 month"
    ExpiresByType image/webp "access plus 1 month"
</IfModule>

<Files "*">
    Order Allow,Deny
    Allow from all
</Files>

Options -Indexes
Options +FollowSymLinks

<IfModule mod_headers.c>
    Header set Accept-Ranges bytes
    Header set Access-Control-Allow-Origin "*"
</IfModule>';

file_put_contents('public/storage/.htaccess', $htaccessContent);
echo "   ✅ Created .htaccess for storage directory\n";

// Step 5: Test image accessibility
echo "\n5. Testing image accessibility...\n";
$testImages = [
    'public/storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png',
    'public/storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png'
];

foreach ($testImages as $image) {
    if (file_exists($image)) {
        echo "   ✅ Image exists: " . basename($image) . "\n";
    } else {
        echo "   ❌ Image missing: " . basename($image) . "\n";
    }
}

// Step 6: Check Laravel configuration
echo "\n6. Checking Laravel configuration...\n";
if (file_exists('.env')) {
    echo "   ✅ .env file exists\n";
    
    // Check if APP_URL is set correctly
    $envContent = file_get_contents('.env');
    if (strpos($envContent, 'APP_URL=https://wasela.itegypt.org') !== false) {
        echo "   ✅ APP_URL is set correctly\n";
    } else {
        echo "   ⚠️  Check APP_URL in .env file\n";
    }
} else {
    echo "   ❌ .env file missing\n";
}

// Step 7: Clear caches
echo "\n7. Clearing caches...\n";
if (function_exists('opcache_reset')) {
    opcache_reset();
    echo "   ✅ OPCache cleared\n";
}

// Create cache clear script
$cacheClearScript = '<?php
// Clear Laravel caches
exec("php artisan config:clear");
exec("php artisan cache:clear");
exec("php artisan view:clear");
exec("php artisan route:clear");
echo "All caches cleared!";
?>';
file_put_contents('clear-cache.php', $cacheClearScript);
echo "   ✅ Created clear-cache.php script\n";

echo "\n=== Deployment Complete! ===\n";
echo "\nNext steps:\n";
echo "1. Run: php clear-cache.php\n";
echo "2. Check that images load at: https://wasela.itegypt.org/storage/services/[filename]\n";
echo "3. If images still don't load, contact your hosting provider about:\n";
echo "   - Enabling symlink support\n";
echo "   - Setting proper file permissions\n";
echo "   - Checking Apache/Nginx configuration\n";
echo "\nTest URLs:\n";
echo "- https://wasela.itegypt.org/storage/services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png\n";
echo "- https://wasela.itegypt.org/storage/services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png\n";
?>
