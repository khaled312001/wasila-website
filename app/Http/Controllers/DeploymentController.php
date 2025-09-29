<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Service;

class DeploymentController extends Controller
{
    /**
     * Fix storage symlink and permissions
     */
    public function fixStorage()
    {
        $results = [];
        $results[] = "=== Wasila Charity - Storage Fix ===";
        
        try {
            // Step 1: Check if storage symlink exists
            $publicStoragePath = public_path('storage');
            $results[] = "1. Checking storage symlink...";
            
            if (is_link($publicStoragePath)) {
                $results[] = "   ✅ Storage symlink exists";
            } elseif (is_dir($publicStoragePath)) {
                $results[] = "   ⚠️ Storage directory exists but is not a symlink";
            } else {
                $results[] = "   ❌ Storage symlink missing";
            }
            
            // Step 2: Create .htaccess for storage
            $results[] = "2. Creating .htaccess for storage...";
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

            if (!is_dir($publicStoragePath)) {
                mkdir($publicStoragePath, 0755, true);
            }
            
            file_put_contents($publicStoragePath . '/.htaccess', $htaccessContent);
            $results[] = "   ✅ Created .htaccess for storage";
            
            // Step 3: Test image files
            $results[] = "3. Testing image files...";
            $testImages = [
                'services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png',
                'services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png',
                'services/dFmRXwOqLlA8muu3Fp8iKyPld0PHe0b89AKGr2ty.png'
            ];
            
            foreach ($testImages as $image) {
                $fullPath = storage_path('app/public/' . $image);
                if (file_exists($fullPath)) {
                    $results[] = "   ✅ Found: " . basename($image);
                } else {
                    $results[] = "   ❌ Missing: " . basename($image);
                }
            }
            
            // Step 4: List actual files in services directory
            $results[] = "4. Actual files in services directory:";
            $servicesPath = storage_path('app/public/services');
            if (is_dir($servicesPath)) {
                $files = scandir($servicesPath);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..' && is_file($servicesPath . '/' . $file)) {
                        $results[] = "   - " . $file;
                    }
                }
            } else {
                $results[] = "   ❌ Services directory not found";
            }
            
            // Step 5: Clear caches
            $results[] = "5. Clearing caches...";
            try {
                \Artisan::call('config:clear');
                \Artisan::call('cache:clear');
                \Artisan::call('view:clear');
                \Artisan::call('route:clear');
                $results[] = "   ✅ All caches cleared";
            } catch (\Exception $e) {
                $results[] = "   ⚠️ Cache clear error: " . $e->getMessage();
            }
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Fix image paths in database
     */
    public function fixImagePaths()
    {
        $results = [];
        $results[] = "=== Fixing Image Paths ===";
        
        try {
            // Get all services
            $services = Service::all();
            $results[] = "Found " . $services->count() . " services";
            
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
            
            $results[] = "Found " . count($actualFiles) . " actual image files";
            foreach ($actualFiles as $file) {
                $results[] = "  - " . $file;
            }
            
            // Map services to actual files
            $updatedCount = 0;
            foreach ($services as $index => $service) {
                if (isset($actualFiles[$index])) {
                    $newImagePath = 'services/' . $actualFiles[$index];
                    
                    $results[] = "Service: {$service->name_en}";
                    $results[] = "  Old path: {$service->image}";
                    $results[] = "  New path: {$newImagePath}";
                    
                    // Update the service
                    $service->update(['image' => $newImagePath]);
                    $updatedCount++;
                    $results[] = "  ✅ Updated";
                } else {
                    $results[] = "Service: {$service->name_en} - ❌ No matching file found";
                }
            }
            
            $results[] = "Updated $updatedCount services with correct image paths";
            
            // Test image accessibility
            $results[] = "Testing image accessibility:";
            foreach (Service::whereNotNull('image')->get() as $service) {
                $fullPath = storage_path('app/public/' . $service->image);
                $publicPath = 'storage/' . $service->image;
                
                if (file_exists($fullPath)) {
                    $results[] = "✅ {$service->name_en}: {$publicPath}";
                } else {
                    $results[] = "❌ {$service->name_en}: {$publicPath} (FILE NOT FOUND)";
                }
            }
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Display deployment tools page
     */
    public function index()
    {
        return view('deployment-tools');
    }
}
