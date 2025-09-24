#!/bin/bash

# Wasila Website Deployment Script
# Run this script on your server after uploading files

echo "Starting Wasila Website Deployment..."

# Check if we're in the right directory
if [ ! -f "artisan" ]; then
    echo "Error: artisan file not found. Please run this script from the Laravel root directory."
    exit 1
fi

# Set proper permissions
echo "Setting file permissions..."
chmod -R 775 storage/
chmod -R 775 bootstrap/cache/
chmod 644 .env

# Install/update composer dependencies
echo "Installing composer dependencies..."
php composer.phar install --no-dev --optimize-autoloader

# Clear all caches
echo "Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Cache configuration for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running database migrations..."
php artisan migrate --force

# Create storage link
echo "Creating storage link..."
php artisan storage:link

# Test database connection
echo "Testing database connection..."
php artisan tinker --execute="DB::connection()->getPdo(); echo 'Database connection successful!';"

echo "Deployment completed successfully!"
echo "Please test your website at: https://itegypt.org/"
echo "Admin panel: https://itegypt.org/admin/login"
