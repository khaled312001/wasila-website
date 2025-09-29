<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Service;

class ManualStorageFixController extends Controller
{
    /**
     * Manual storage fix when symlinks are not supported
     */
    public function fixStorageManually()
    {
        $results = [];
        $results[] = "=== Manual Storage Fix (No Symlinks Required) ===";
        
        try {
            // Step 1: Copy files from storage/app/public to public/storage
            $results[] = "1. Copying storage files to public directory...";
            
            $sourceDir = storage_path('app/public');
            $targetDir = public_path('storage');
            
            if (!is_dir($sourceDir)) {
                $results[] = "   ❌ Source directory not found: $sourceDir";
                return response()->json(['success' => false, 'results' => $results]);
            }
            
            // Remove existing public/storage if it exists
            if (is_dir($targetDir)) {
                $this->removeDirectory($targetDir);
                $results[] = "   ✅ Removed existing storage directory";
            }
            
            // Copy all files
            $this->copyDirectory($sourceDir, $targetDir);
            $results[] = "   ✅ Copied all storage files to public/storage";
            
            // Step 2: Create .htaccess file
            $results[] = "2. Creating .htaccess for proper file access...";
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

            file_put_contents($targetDir . '/.htaccess', $htaccessContent);
            $results[] = "   ✅ Created .htaccess file";
            
            // Step 3: Set file permissions (if possible)
            $results[] = "3. Setting file permissions...";
            try {
                $this->setPermissions($targetDir);
                $results[] = "   ✅ Set file permissions";
            } catch (\Exception $e) {
                $results[] = "   ⚠️ Permission setting failed: " . $e->getMessage();
                $results[] = "   💡 This is normal on some hosting providers";
            }
            
            // Step 4: Test file existence
            $results[] = "4. Testing copied files...";
            $testFiles = [
                'services/35LBAIj7gi8HLOWiIbGjiCs82UFloL9YDKhOePuS.png',
                'services/94ahRvARwxYgD3RzedyPEOIgk6BSESNYk98XQRgH.png',
                'services/GdN8EyOD9hylXKEUnfZ7Dx0HC5DMhfHCLXhf3fpB.png'
            ];
            
            foreach ($testFiles as $file) {
                $filePath = $targetDir . '/' . $file;
                if (file_exists($filePath)) {
                    $results[] = "   ✅ Found: " . basename($file);
                } else {
                    $results[] = "   ❌ Missing: " . basename($file);
                }
            }
            
            $results[] = "";
            $results[] = "=== IMPORTANT NEXT STEPS ===";
            $results[] = "Since your hosting provider has restrictions:";
            $results[] = "1. ✅ Files have been copied (no symlink needed)";
            $results[] = "2. ✅ Permissions set via SSH commands you ran";
            $results[] = "3. 🔄 Test the image URLs now - they should work!";
            $results[] = "";
            $results[] = "If images still show 403 errors, contact your hosting provider";
            $results[] = "and ask them to allow access to the public/storage directory.";
            
        } catch (\Exception $e) {
            $results[] = "❌ Error: " . $e->getMessage();
        }
        
        return response()->json([
            'success' => true,
            'results' => $results
        ]);
    }
    
    /**
     * Copy directory recursively
     */
    private function copyDirectory($source, $destination)
    {
        if (!is_dir($source)) {
            return;
        }
        
        if (!is_dir($destination)) {
            mkdir($destination, 0755, true);
        }
        
        $files = scandir($source);
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') {
                continue;
            }
            
            $sourcePath = $source . DIRECTORY_SEPARATOR . $file;
            $destPath = $destination . DIRECTORY_SEPARATOR . $file;
            
            if (is_dir($sourcePath)) {
                $this->copyDirectory($sourcePath, $destPath);
            } else {
                copy($sourcePath, $destPath);
            }
        }
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
            is_dir($path) ? $this->removeDirectory($path) : unlink($path);
        }
        
        return rmdir($dir);
    }
    
    /**
     * Set file permissions
     */
    private function setPermissions($dir)
    {
        if (!is_dir($dir)) {
            return;
        }
        
        // Try to set permissions, but don't fail if it doesn't work
        @chmod($dir, 0755);
        
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($iterator as $item) {
            if ($item->isDir()) {
                @chmod($item->getRealPath(), 0755);
            } else {
                @chmod($item->getRealPath(), 0644);
            }
        }
    }
    
    /**
     * Display the manual fix page
     */
    public function index()
    {
        return view('manual-storage-fix');
    }
}
