<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use App\Models\PortfolioItem;

class CheckStorageFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:check-files';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check which files exist in storage and which are missing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('فحص الملفات في التخزين...');
        $this->newLine();

        // Check Service Images
        $this->info('📦 فحص صور الخدمات:');
        $services = Service::whereNotNull('image')->get();
        $serviceExists = 0;
        $serviceMissing = 0;
        
        foreach ($services as $service) {
            $cleanImage = str_replace('storage/', '', $service->image);
            $cleanImage = ltrim($cleanImage, '/');
            
            $paths = [
                'storage/app/public' => storage_path('app/public/' . $cleanImage),
                'public/storage' => public_path('storage/' . $cleanImage),
            ];
            
            $found = false;
            foreach ($paths as $location => $path) {
                if (file_exists($path)) {
                    $this->line("   ✅ Service ID {$service->id}: موجود في {$location}");
                    $found = true;
                    break;
                }
            }
            
            if ($found) {
                $serviceExists++;
            } else {
                $serviceMissing++;
                $this->warn("   ❌ Service ID {$service->id}: غير موجود - {$service->image}");
            }
        }
        
        $this->line("   المجموع: {$serviceExists} موجود، {$serviceMissing} مفقود");
        $this->newLine();

        // Check Portfolio Images
        $this->info('🖼️  فحص صور الأعمال:');
        $portfolioItems = PortfolioItem::whereNotNull('file_path')->get();
        $portfolioExists = 0;
        $portfolioMissing = 0;
        
        foreach ($portfolioItems as $item) {
            $cleanPath = str_replace('storage/', '', $item->file_path);
            $cleanPath = ltrim($cleanPath, '/');
            
            $paths = [
                'storage/app/public' => storage_path('app/public/' . $cleanPath),
                'public/storage' => public_path('storage/' . $cleanPath),
            ];
            
            $found = false;
            foreach ($paths as $location => $path) {
                if (file_exists($path)) {
                    $this->line("   ✅ Portfolio ID {$item->id}: موجود في {$location}");
                    $found = true;
                    break;
                }
            }
            
            if ($found) {
                $portfolioExists++;
            } else {
                $portfolioMissing++;
                $this->warn("   ❌ Portfolio ID {$item->id}: غير موجود - {$item->file_path}");
            }
        }
        
        $this->line("   المجموع: {$portfolioExists} موجود، {$portfolioMissing} مفقود");
        $this->newLine();

        // Summary
        $totalExists = $serviceExists + $portfolioExists;
        $totalMissing = $serviceMissing + $portfolioMissing;
        
        if ($totalMissing == 0) {
            $this->info("✅ جميع الملفات موجودة!");
        } else {
            $this->warn("⚠️  يوجد {$totalMissing} ملف مفقود من أصل " . ($totalExists + $totalMissing));
        }

        // Check directories
        $this->newLine();
        $this->info('📁 فحص المجلدات:');
        
        $dirs = [
            'storage/app/public/services' => storage_path('app/public/services'),
            'storage/app/public/portfolio' => storage_path('app/public/portfolio'),
            'public/storage/services' => public_path('storage/services'),
            'public/storage/portfolio' => public_path('storage/portfolio'),
        ];
        
        foreach ($dirs as $name => $path) {
            if (is_dir($path)) {
                $fileCount = count(glob($path . '/*'));
                $this->line("   ✅ {$name}: موجود ({$fileCount} ملف)");
            } else {
                $this->warn("   ❌ {$name}: غير موجود");
            }
        }

        return Command::SUCCESS;
    }
}
