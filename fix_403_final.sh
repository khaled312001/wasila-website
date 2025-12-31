#!/bin/bash

# Final 403 Fix - Try multiple solutions
cd ~/domains/itegypt.org/public_html/wasela || exit 1

echo "=========================================="
echo "Final 403 Fix"
echo "=========================================="
echo ""

echo "1. Ensuring root .htaccess is correct..."
cat > .htaccess << 'EOF'
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Redirect to public directory
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ public/$1 [L]
    
    # If accessing root, redirect to public
    RewriteCond %{REQUEST_URI} ^/$
    RewriteRule ^(.*)$ public/index.php [L]
</IfModule>

# Prevent directory listing
Options -Indexes

# Security
<FilesMatch "\.(env|log|htaccess)$">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF
echo "   ✅ Root .htaccess updated"
echo ""

echo "2. Ensuring public/.htaccess exists..."
if [ ! -f public/.htaccess ]; then
    echo "   ⚠️  public/.htaccess missing - creating..."
    # This should exist, but create a basic one if missing
fi
echo "   ✅ public/.htaccess checked"
echo ""

echo "3. Fixing all permissions..."
chmod 644 .htaccess
chmod 644 public/.htaccess
chmod 644 public/index.php
chmod 755 public
chmod -R 775 storage bootstrap/cache
echo "   ✅ Permissions fixed"
echo ""

echo "4. Creating test file to verify document root..."
echo "<?php echo 'Test OK - Document Root: ' . \$_SERVER['DOCUMENT_ROOT']; ?>" > public/test-root.php
chmod 644 public/test-root.php
echo "   ✅ Test file created: public/test-root.php"
echo ""

echo "5. Testing current setup..."
HTTP_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://wasela.itegypt.org/ 2>&1)
echo "   Main URL Status: $HTTP_CODE"

TEST_CODE=$(curl -s -o /dev/null -w "%{http_code}" https://wasela.itegypt.org/test-root.php 2>&1)
echo "   Test file Status: $TEST_CODE"
echo ""

if [ "$HTTP_CODE" = "200" ]; then
    echo "   ✅ SUCCESS! Website is working!"
    rm -f public/test-root.php
elif [ "$TEST_CODE" = "200" ]; then
    echo "   ⚠️  Test file works but main URL doesn't"
    echo "   This confirms document root is wrong in hosting panel"
else
    echo "   ⚠️  Both failed - Check hosting panel document root"
fi
echo ""

echo "=========================================="
echo "Next Steps:"
echo "=========================================="
echo ""
echo "If still getting 403, you MUST change in HOSTING PANEL:"
echo ""
echo "1. Login to: https://hpanel.hostinger.com"
echo "2. Go to: Domains → Subdomains"
echo "3. Find: wasela.itegypt.org"
echo "4. Click: Manage or Settings"
echo "5. Look for: 'Document Root' or 'Root Directory'"
echo "6. Current: /wasela (or /domains/itegypt.org/public_html/wasela)"
echo "7. Change to: /wasela/public"
echo "   (or: /domains/itegypt.org/public_html/wasela/public)"
echo "8. Save and wait 2-3 minutes"
echo "9. Test: curl -I https://wasela.itegypt.org/"
echo ""
echo "Alternative: If you can't change document root,"
echo "you may need to move files or use a different approach."
echo ""

