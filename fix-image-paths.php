<?php
/**
 * Fix Image Paths Script for Wasila Charity Website
 * This script updates database image paths to match actual files
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Service;
use Illuminate\Support\Facades\Storage;

echo "=== Fixing Image Paths ===\n\n";

// Get all services
$services = Service::all();

echo "Found " . $services->count() . " services\n\n";

// Get actual files in storage/app/public/services
$actualFiles = [];
$servicesPath = storage_path('app/public/services');
if (is_dir($servicesPath)) {
    $files = scandir($servicesPath);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && is_file($servicesPath . '/' . $file)) {
            $actualFiles[] = $file;
        }
    }
}

echo "Found " . count($actualFiles) . " actual image files:\n";
foreach ($actualFiles as $file) {
    echo "  - $file\n";
}
echo "\n";

// Map services to actual files
$updatedCount = 0;
foreach ($services as $index => $service) {
    if (isset($actualFiles[$index])) {
        $newImagePath = 'services/' . $actualFiles[$index];
        
        echo "Service: {$service->name_en}\n";
        echo "  Old path: {$service->image}\n";
        echo "  New path: {$newImagePath}\n";
        
        // Update the service
        $service->update(['image' => $newImagePath]);
        $updatedCount++;
        echo "  ✅ Updated\n\n";
    } else {
        echo "Service: {$service->name_en}\n";
        echo "  ❌ No matching file found\n\n";
    }
}

echo "Updated $updatedCount services with correct image paths.\n\n";

// Test image accessibility
echo "Testing image accessibility...\n";
foreach (Service::whereNotNull('image')->get() as $service) {
    $fullPath = storage_path('app/public/' . $service->image);
    $publicPath = 'storage/' . $service->image;
    
    if (file_exists($fullPath)) {
        echo "✅ {$service->name_en}: {$publicPath}\n";
    } else {
        echo "❌ {$service->name_en}: {$publicPath} (FILE NOT FOUND)\n";
    }
}

echo "\n=== Image Path Fix Complete ===\n";
?>
