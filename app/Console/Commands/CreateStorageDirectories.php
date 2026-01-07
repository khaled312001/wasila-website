<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class CreateStorageDirectories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'storage:create-directories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create required storage directories if they don\'t exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('إنشاء المجلدات المطلوبة...');
        $this->newLine();

        $directories = [
            'storage/app/public' => storage_path('app/public'),
            'storage/app/public/services' => storage_path('app/public/services'),
            'storage/app/public/portfolio' => storage_path('app/public/portfolio'),
            'public/storage' => public_path('storage'),
            'public/storage/services' => public_path('storage/services'),
            'public/storage/portfolio' => public_path('storage/portfolio'),
        ];

        $created = 0;
        $exists = 0;

        foreach ($directories as $name => $path) {
            if (!is_dir($path)) {
                if (File::makeDirectory($path, 0755, true)) {
                    $this->info("   ✅ تم إنشاء: {$name}");
                    $created++;
                } else {
                    $this->error("   ❌ فشل إنشاء: {$name}");
                }
            } else {
                $this->line("   ℹ️  موجود بالفعل: {$name}");
                $exists++;
            }
        }

        $this->newLine();
        $this->info("✅ تم إنشاء {$created} مجلد" . ($exists > 0 ? " ({$exists} موجود مسبقاً)" : ""));

        // Set permissions
        $this->newLine();
        $this->info('تعيين الصلاحيات...');
        
        foreach ($directories as $name => $path) {
            if (is_dir($path)) {
                @chmod($path, 0755);
                $this->line("   ✅ {$name}: 755");
            }
        }

        return Command::SUCCESS;
    }
}
