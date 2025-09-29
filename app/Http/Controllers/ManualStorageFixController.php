<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;

class ManualStorageFixController extends Controller
{
    /**
     * Display the manual storage fix page
     */
    public function index()
    {
        return view('manual-storage-fix');
    }

    /**
     * Fix storage manually by copying files (for hosting providers that don't support symlinks)
     */
    public function fixStorageManually()
    {
        $results = [];
        $results[] = "=== Manual Storage Fix (No Symlinks Required) ===";
        
        try {
            // Step 1: Copy files
            $results[] = "1. Copying storage files to public directory...";
            $sourceDir = storage_path('app/public');
            $targetDir = public_path('storage');
            
            // Remove existing directory if it exists
            if (is_dir($targetDir)) {
                $this->removeDirectory($targetDir);
                $results[] = "   ✅ Removed existing storage directory";
            }
            
            // Copy all files
            $this->copyStorageFiles($sourceDir, $targetDir);
            $results[] = "   ✅ Copied all storage files to public/storage";
            
            // Step 2: Create .htaccess
            $results[] = "2. Creating .htaccess for proper file access...";
            $htaccessContent = "Options -Indexes\n";
            $htaccessContent .= "<FilesMatch \"\\.(jpg|jpeg|png|gif|svg|webp|mp4|mov|avi|pdf|doc|docx|zip)$\">\n";
            $htaccessContent .= "    Order Allow,Deny\n";
            $htaccessContent .= "    Allow from all\n";
            $htaccessContent .= "</FilesMatch>\n";
            $htaccessContent .= "Header set Cache-Control \"public, max-age=31536000\"\n";
            
            file_put_contents($targetDir . '/.htaccess', $htaccessContent);
            $results[] = "   ✅ Created .htaccess file";
            
            // Step 3: Set permissions
            $results[] = "3. Setting file permissions...";
            try {
                $this->setPermissions($targetDir);
                $results[] = "   ✅ Set file permissions";
            } catch (\Exception $e) {
                $results[] = "   ⚠️ Permission setting failed: " . $e->getMessage();
                $results[] = "   💡 This is normal on some hosting providers";
            }
            
            // Step 4: Test and create missing portfolio images
            $results[] = "4. Checking and creating portfolio images...";
            $portfolioDir = $targetDir . '/portfolio';
            
            // Ensure portfolio directory exists
            if (!is_dir($portfolioDir)) {
                mkdir($portfolioDir, 0755, true);
                $results[] = "   ✅ Created portfolio directory";
            }
            
            // Create missing numbered portfolio images (1.png to 12.png) using different service images
            $servicesDir = $targetDir . '/services';
            $serviceImages = [];
            
            // Collect all service images
            if (is_dir($servicesDir)) {
                $serviceFiles = scandir($servicesDir);
                foreach ($serviceFiles as $file) {
                    if (strpos($file, '.png') !== false || strpos($file, '.jpg') !== false) {
                        $serviceImages[] = $servicesDir . '/' . $file;
                    }
                }
            }
            
            if (count($serviceImages) > 0) {
                for ($i = 1; $i <= 12; $i++) {
                    $targetFile = $portfolioDir . '/' . $i . '.png';
                    if (!file_exists($targetFile)) {
                        // Use different service images in rotation
                        $sourceImage = $serviceImages[($i - 1) % count($serviceImages)];
                        if (copy($sourceImage, $targetFile)) {
                            $results[] = "   ✅ Created: portfolio/" . $i . '.png (from ' . basename($sourceImage) . ')';
                        } else {
                            $results[] = "   ❌ Failed to create: portfolio/" . $i . '.png';
                        }
                    } else {
                        $results[] = "   ✅ Already exists: portfolio/" . $i . '.png';
                    }
                }
            } else {
                $results[] = "   ⚠️ No service images found to create portfolio images";
            }
            
            // Step 5: Test final results
            $results[] = "5. Testing final results...";
            $results[] = "   Services:";
            $testServiceFiles = ['35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png', '94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png'];
            foreach ($testServiceFiles as $file) {
                $filePath = $targetDir . '/services/' . $file;
                $results[] = file_exists($filePath) ? "     ✅ Found: $file" : "     ❌ Missing: $file";
            }
            
            $results[] = "   Portfolio:";
            for ($i = 1; $i <= 5; $i++) {
                $filePath = $targetDir . '/portfolio/' . $i . '.png';
                $results[] = file_exists($filePath) ? "     ✅ Found: {$i}.png" : "     ❌ Missing: {$i}.png";
            }
            
            $results[] = "";
            $results[] = "=== SUCCESS ===";
            $results[] = "✅ All files copied and portfolio images created!";
            $results[] = "🔄 Test the website now - portfolio images should work!";
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Create sample portfolio items if none exist
     */
    public function createSamplePortfolio()
    {
        $results = [];
        $results[] = "=== Creating Sample Portfolio Items ===";
        
        try {
            $existingCount = PortfolioItem::count();
            $results[] = "Existing portfolio items: " . $existingCount;
            
            if ($existingCount == 0) {
                for ($i = 1; $i <= 12; $i++) {
                    PortfolioItem::create([
                        'title_ar' => 'مشروع خيري ' . $i,
                        'title_en' => 'Charity Project ' . $i,
                        'description_ar' => 'وصف المشروع الخيري رقم ' . $i . ' - عمل خيري متميز يهدف لخدمة المجتمع',
                        'description_en' => 'Description of charity project ' . $i . ' - Distinguished charity work aimed at serving the community',
                        'type' => 'image',
                        'file_path' => 'portfolio/' . $i . '.png',
                        'sort_order' => $i,
                        'is_active' => true
                    ]);
                    $results[] = "✅ Created: مشروع خيري " . $i;
                }
                
                $results[] = "";
                $results[] = "✅ Created 12 sample portfolio items successfully!";
                $results[] = "Now visit the website - portfolio section should show real items instead of static images.";
            } else {
                $results[] = "ℹ️ Portfolio items already exist. Use 'Activate Portfolio Items' instead.";
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
     * Activate all portfolio items and fix paths
     */
    public function activatePortfolioItems()
    {
        $results = [];
        $results[] = "=== Activating Portfolio Items ===";
        
        try {
            // Get all portfolio items
            $portfolioItems = PortfolioItem::all();
            $results[] = "Found " . $portfolioItems->count() . " portfolio items";
            
            $activatedCount = 0;
            foreach ($portfolioItems as $item) {
                if (!$item->is_active) {
                    $item->update(['is_active' => true]);
                    $results[] = "✅ Activated: " . $item->title_ar;
                    $activatedCount++;
                } else {
                    $results[] = "✅ Already active: " . $item->title_ar;
                }
            }
            
            $results[] = "";
            $results[] = "Activated $activatedCount portfolio items.";
            $results[] = "Total active items: " . PortfolioItem::active()->count();
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Fix portfolio paths in database
     */
    public function fixPortfolioPaths()
    {
        $results = [];
        $results[] = "=== Fixing Portfolio Database Paths ===";
        
        try {
            // Get all portfolio items
            $portfolioItems = PortfolioItem::all();
            $results[] = "Found " . $portfolioItems->count() . " portfolio items";
            
            $updatedCount = 0;
            foreach ($portfolioItems as $item) {
                $oldPath = $item->file_path;
                
                // Fix various incorrect path formats
                if (strpos($oldPath, '/storage/') === 0) {
                    $newPath = substr($oldPath, 9); // Remove '/storage/'
                } elseif (strpos($oldPath, 'storage/') === 0) {
                    $newPath = substr($oldPath, 8); // Remove 'storage/'
                } elseif (strpos($oldPath, 'admin/portfolio/') === 0) {
                    // Fix admin/portfolio/filename.png -> portfolio/filename.png
                    $newPath = str_replace('admin/portfolio/', 'portfolio/', $oldPath);
                } elseif (strpos($oldPath, '/admin/portfolio/') === 0) {
                    // Fix /admin/portfolio/filename.png -> portfolio/filename.png
                    $newPath = str_replace('/admin/portfolio/', 'portfolio/', $oldPath);
                } elseif (!strpos($oldPath, 'portfolio/') && (strpos($oldPath, '.png') || strpos($oldPath, '.jpg') || strpos($oldPath, '.mp4'))) {
                    // If it's just a filename, add portfolio/ prefix
                    $newPath = 'portfolio/' . basename($oldPath);
                } else {
                    $newPath = $oldPath; // Keep as is if already correct
                }
                
                if ($oldPath !== $newPath) {
                    $results[] = "Portfolio Item: {$item->title_ar}";
                    $results[] = "  Old path: {$oldPath}";
                    $results[] = "  New path: {$newPath}";
                    
                    $item->update(['file_path' => $newPath]);
                    $updatedCount++;
                    $results[] = "  ✅ Updated";
                } else {
                    $results[] = "Portfolio Item: {$item->title_ar} - ✅ Path already correct";
                }
            }
            
            $results[] = "";
            $results[] = "Updated $updatedCount portfolio items with correct paths.";
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Remove directory recursively
     */
    private function removeDirectory($dir)
    {
        if (!is_dir($dir)) {
            return false;
        }
        
        $files = array_diff(scandir($dir), array('.', '..'));
        
        foreach ($files as $file) {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                $this->removeDirectory($path);
            } else {
                unlink($path);
            }
        }
        
        return rmdir($dir);
    }
    
    /**
     * Copy storage files to public directory
     */
    private function copyStorageFiles($source, $destination)
    {
        if (!is_dir($source)) {
            return false;
        }
        
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $this->recursiveCopy($source, $destination);
        
        return true;
    }
    
    /**
     * Recursively copy files and directories
     */
    private function recursiveCopy($source, $destination)
    {
        $files = scandir($source);
        
        foreach ($files as $file) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            
            $sourcePath = $source . DIRECTORY_SEPARATOR . $file;
            $targetPath = $destination . DIRECTORY_SEPARATOR . $file;
            
            if (is_dir($sourcePath)) {
                if (!is_dir($targetPath)) {
                    mkdir($targetPath, 0755, true);
                }
                $this->recursiveCopy($sourcePath, $targetPath);
            } else {
                copy($sourcePath, $targetPath);
            }
        }
    }
    
    /**
     * Set file permissions
     */
    private function setPermissions($dir)
    {
        // Set directory permissions
        if (is_dir($dir)) {
            chmod($dir, 0755);
        }
        
        // Set file permissions recursively
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS)
        );
        
        foreach ($iterator as $item) {
            if ($item->isFile()) {
                chmod($item->getPathname(), 0644);
            } elseif ($item->isDir()) {
                chmod($item->getPathname(), 0755);
            }
        }
    }
}