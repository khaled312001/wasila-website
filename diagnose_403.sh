#!/bin/bash

# Diagnose 403 Error
cd ~/domains/itegypt.org/public_html/wasela || exit 1

echo "=========================================="
echo "403 Error Diagnosis"
echo "=========================================="
echo ""

echo "1. Checking root .htaccess..."
if [ -f .htaccess ]; then
    echo "   Content:"
    cat .htaccess
    echo ""
else
    echo "   ❌ Root .htaccess not found!"
fi

echo "2. Checking public/.htaccess..."
if [ -f public/.htaccess ]; then
    echo "   ✅ public/.htaccess exists"
    head -5 public/.htaccess
    echo ""
else
    echo "   ❌ public/.htaccess not found!"
fi

echo "3. Checking public/index.php..."
if [ -f public/index.php ]; then
    echo "   ✅ public/index.php exists"
    ls -lh public/index.php
    echo ""
else
    echo "   ❌ public/index.php not found!"
fi

echo "4. Testing direct access to public/index.php..."
php public/index.php 2>&1 | head -10
echo ""

echo "5. Checking directory structure..."
echo "   Current directory: $(pwd)"
echo "   Public directory: $(ls -ld public 2>/dev/null | awk '{print $9, $1}')"
echo ""

echo "6. Testing with different URLs..."
echo "   Testing: https://wasela.itegypt.org/"
curl -s -o /dev/null -w "   Status: %{http_code}\n" https://wasela.itegypt.org/
echo "   Testing: https://wasela.itegypt.org/public/"
curl -s -o /dev/null -w "   Status: %{http_code}\n" https://wasela.itegypt.org/public/
echo "   Testing: https://wasela.itegypt.org/public/index.php"
curl -s -o /dev/null -w "   Status: %{http_code}\n" https://wasela.itegypt.org/public/index.php
echo ""

echo "7. Checking file permissions..."
ls -la .htaccess public/.htaccess public/index.php 2>/dev/null
echo ""

echo "=========================================="
echo "Diagnosis Complete"
echo "=========================================="
echo ""
echo "If all files exist and permissions are correct,"
echo "the issue is in your HOSTING PANEL configuration."
echo ""
echo "ACTION REQUIRED:"
echo "1. Login to hPanel (Hostinger)"
echo "2. Go to: Subdomains"
echo "3. Find: wasela.itegypt.org"
echo "4. Click: Manage or Edit"
echo "5. Change Document Root from: /wasela"
echo "   To: /wasela/public"
echo "6. Save and wait 2-3 minutes"
echo "7. Test again: curl -I https://wasela.itegypt.org/"
echo ""


