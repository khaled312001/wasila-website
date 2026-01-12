<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\Service;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Log;

class SyncStorageImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:sync-images 
                            {--force : Force sync even if files already exist}
                            {--service-only : Sync only service images}
                            {--portfolio-only : Sync only portfolio images}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync all images from storage/app/public to public/storage for hosting providers that don\'t support symlinks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('بدء مزامنة الصور إلى public/storage...');
        $this->newLine();

        $force = $this->option('force');
        $serviceOnly = $this->option('service-only');
        $portfolioOnly = $this->option('portfolio-only');

        $totalSynced = 0;
        $totalFailed = 0;

        // Sync Service Images
        if (!$portfolioOnly) {
            $this->info('📦 مزامنة صور الخدمات...');
            $result = $this->syncServiceImages($force);
            $totalSynced += $result['synced'];
            $totalFailed += $result['failed'];
            $this->newLine();
        }

        // Sync Portfolio Images
        if (!$serviceOnly) {
            $this->info('🖼️  مزامنة صور الأعمال...');
            $result = $this->syncPortfolioImages($force);
            $totalSynced += $result['synced'];
            $totalFailed += $result['failed'];
            $this->newLine();
        }

        // Summary
        if ($totalSynced > 0 || $totalFailed == 0) {
            $this->info("✅ تمت المزامنة بنجاح!");
        } else {
            $this->warn("⚠️  انتهت المزامنة مع وجود أخطاء!");
        }
        $this->info("   - تم نسخ {$totalSynced} ملف بنجاح");
        if ($totalFailed > 0) {
            $this->warn("   - فشل نسخ {$totalFailed} ملف");
            $this->line("   - استخدم -v أو -vv لعرض تفاصيل الأخطاء");
            $this->line("   - تحقق من وجود الملفات في storage/app/public");
            $this->line("   - تحقق من صلاحيات الكتابة على public/storage");
        }

        return $totalFailed > 0 ? Command::FAILURE : Command::SUCCESS;
    }

    /**
     * Sync service images
     */
    private function syncServiceImages($force = false)
    {
        $services = Service::whereNotNull('image')->get();
        $synced = 0;
        $failed = 0;
        $failedDetails = [];

        $bar = $this->output->createProgressBar($services->count());
        $bar->start();

        foreach ($services as $service) {
            $cleanImage = str_replace('storage/', '', $service->image);
            $cleanImage = ltrim($cleanImage, '/');
            
            $result = $this->copyToPublicStorage($cleanImage, $force);
            if ($result === true) {
                $synced++;
            } else {
                $failed++;
                $failedDetails[] = [
                    'id' => $service->id,
                    'path' => $service->image,
                    'reason' => $result
                ];
                Log::warning("Failed to sync service image: {$service->image} (Service ID: {$service->id}) - Reason: {$result}");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("   تم مزامنة {$synced} صورة خدمة" . ($failed > 0 ? " (فشل {$failed})" : ""));
        
        if ($failed > 0 && $this->getOutput()->isVerbose()) {
            foreach ($failedDetails as $detail) {
                $this->warn("   - Service ID {$detail['id']}: {$detail['path']} - {$detail['reason']}");
            }
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Sync portfolio images
     */
    private function syncPortfolioImages($force = false)
    {
        $portfolioItems = PortfolioItem::whereNotNull('file_path')->get();
        $synced = 0;
        $failed = 0;
        $failedDetails = [];

        $bar = $this->output->createProgressBar($portfolioItems->count());
        $bar->start();

        foreach ($portfolioItems as $item) {
            $cleanPath = $this->normalizeFilePath($item->file_path, 'portfolio');
            
            $result = $this->copyToPublicStorage($cleanPath, $force);
            if ($result === true) {
                $synced++;
            } else {
                $failed++;
                $failedDetails[] = [
                    'id' => $item->id,
                    'path' => $item->file_path,
                    'reason' => $result
                ];
                Log::warning("Failed to sync portfolio image: {$item->file_path} (Portfolio ID: {$item->id}) - Reason: {$result}");
            }

            // Also sync thumbnail if exists
            if ($item->thumbnail_path) {
                $cleanThumbnail = $this->normalizeFilePath($item->thumbnail_path, 'portfolio');
                $this->copyToPublicStorage($cleanThumbnail, $force);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("   تم مزامنة {$synced} صورة عمل" . ($failed > 0 ? " (فشل {$failed})" : ""));
        
        if ($failed > 0 && $this->getOutput()->isVerbose()) {
            foreach ($failedDetails as $detail) {
                $this->warn("   - Portfolio ID {$detail['id']}: {$detail['path']} - {$detail['reason']}");
            }
        }

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Copy file to public/storage directory
     */
    private function copyToPublicStorage($filePath, $force = false)
    {
        $filePath = $this->normalizeFilePath($filePath, null);
        if (empty($filePath)) {
            return 'مسار الملف غير صحيح';
        }

        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $targetPath = public_path('storage/' . $filePath);
            
            // Check if source file exists
            if (!file_exists($sourcePath)) {
                // Try alternative paths
                $altPaths = [
                    storage_path('app/' . $filePath),
                    base_path('storage/app/public/' . $filePath),
                    public_path('storage/' . $filePath), // Already in public/storage
                ];
                
                $found = false;
                foreach ($altPaths as $altPath) {
                    if (file_exists($altPath)) {
                        $sourcePath = $altPath;
                        $found = true;
                        break;
                    }
                }
                
                if (!$found) {
                    return "الملف غير موجود: " . $filePath;
                }
            }
            
            // Check if target already exists and force is not set
            if (!$force && file_exists($targetPath)) {
                return true; // Already synced
            }
            
            // Create directory if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                if (!File::makeDirectory($targetDir, 0755, true)) {
                    return "فشل إنشاء المجلد: " . $targetDir;
                }
            }
            
            // Check if target directory is writable
            if (!is_writable($targetDir)) {
                return "المجلد غير قابل للكتابة: " . $targetDir;
            }
            
            // Copy file
            if (!File::copy($sourcePath, $targetPath)) {
                return "فشل نسخ الملف من {$sourcePath} إلى {$targetPath}";
            }
            
            // Set permissions
            @chmod($targetPath, 0644);
            
            // Verify the copy was successful
            if (!file_exists($targetPath)) {
                return "فشل التحقق من نسخ الملف";
            }
            
            return true;
        } catch (\Exception $e) {
            $errorMsg = 'Exception: ' . $e->getMessage();
            Log::error('Exception in copyToPublicStorage: ' . $errorMsg . ' | File: ' . $filePath);
            return $errorMsg;
        }
    }

    /**
     * Normalize storage paths by stripping common prefixes
     */
    private function normalizeFilePath(?string $path, ?string $defaultFolder = null): string
    {
        if (empty($path)) {
            return '';
        }

        $cleanPath = str_replace(['storage/', '/storage/', 'public/', '/public/'], '', $path);
        $cleanPath = ltrim($cleanPath, '/');

        // If caller specifies a default folder and no directory exists, prepend it
        if ($defaultFolder && $cleanPath !== '' && strpos($cleanPath, '/') === false) {
            $cleanPath = rtrim($defaultFolder, '/') . '/' . $cleanPath;
        }

        return $cleanPath;
    }
}
