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
        $this->info("✅ تمت المزامنة بنجاح!");
        $this->info("   - تم نسخ {$totalSynced} ملف بنجاح");
        if ($totalFailed > 0) {
            $this->warn("   - فشل نسخ {$totalFailed} ملف");
        }

        return Command::SUCCESS;
    }

    /**
     * Sync service images
     */
    private function syncServiceImages($force = false)
    {
        $services = Service::whereNotNull('image')->get();
        $synced = 0;
        $failed = 0;

        $bar = $this->output->createProgressBar($services->count());
        $bar->start();

        foreach ($services as $service) {
            $cleanImage = str_replace('storage/', '', $service->image);
            
            if ($this->copyToPublicStorage($cleanImage, $force)) {
                $synced++;
            } else {
                $failed++;
                Log::warning("Failed to sync service image: {$service->image} (Service ID: {$service->id})");
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("   تم مزامنة {$synced} صورة خدمة" . ($failed > 0 ? " (فشل {$failed})" : ""));

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

        $bar = $this->output->createProgressBar($portfolioItems->count());
        $bar->start();

        foreach ($portfolioItems as $item) {
            $cleanPath = str_replace('storage/', '', $item->file_path);
            $cleanPath = ltrim($cleanPath, '/');
            
            if ($this->copyToPublicStorage($cleanPath, $force)) {
                $synced++;
            } else {
                $failed++;
                Log::warning("Failed to sync portfolio image: {$item->file_path} (Portfolio ID: {$item->id})");
            }

            // Also sync thumbnail if exists
            if ($item->thumbnail_path) {
                $cleanThumbnail = str_replace('storage/', '', $item->thumbnail_path);
                $cleanThumbnail = ltrim($cleanThumbnail, '/');
                $this->copyToPublicStorage($cleanThumbnail, $force);
            }
            
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->line("   تم مزامنة {$synced} صورة عمل" . ($failed > 0 ? " (فشل {$failed})" : ""));

        return ['synced' => $synced, 'failed' => $failed];
    }

    /**
     * Copy file to public/storage directory
     */
    private function copyToPublicStorage($filePath, $force = false)
    {
        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $targetPath = public_path('storage/' . $filePath);
            
            // Check if source file exists
            if (!file_exists($sourcePath)) {
                return false;
            }
            
            // Check if target already exists and force is not set
            if (!$force && file_exists($targetPath)) {
                return true; // Already synced
            }
            
            // Create directory if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                if (!File::makeDirectory($targetDir, 0755, true)) {
                    return false;
                }
            }
            
            // Copy file
            if (!File::copy($sourcePath, $targetPath)) {
                return false;
            }
            
            // Set permissions
            chmod($targetPath, 0644);
            
            return true;
        } catch (\Exception $e) {
            Log::error('Exception in copyToPublicStorage: ' . $e->getMessage() . ' | File: ' . $filePath);
            return false;
        }
    }
}
