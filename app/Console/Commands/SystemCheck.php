<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Customer;
use App\Models\Service;
use App\Models\CustomerMessage;
use App\Models\OrderDocumentation;

class SystemCheck extends Command
{
    protected $signature = 'system:check';
    protected $description = 'فحص شامل للنظام - Backend, Frontend, Database, API';

    public function handle()
    {
        $this->info('🔍 بدء الفحص الشامل للنظام...');
        $this->newLine();

        // 1. Database Connection Check
        $this->checkDatabaseConnection();

        // 2. Database Tables Check
        $this->checkDatabaseTables();

        // 3. Models Check
        $this->checkModels();

        // 4. Routes Check
        $this->checkRoutes();

        // 5. Storage Check
        $this->checkStorage();

        // 6. Configuration Check
        $this->checkConfiguration();

        $this->newLine();
        $this->info('✅ اكتمل الفحص الشامل!');
    }

    private function checkDatabaseConnection()
    {
        $this->info('📊 فحص الاتصال بقاعدة البيانات...');
        
        try {
            DB::connection()->getPdo();
            $this->line('   ✅ الاتصال بقاعدة البيانات ناجح');
            
            $dbName = DB::connection()->getDatabaseName();
            $this->line("   📌 اسم قاعدة البيانات: {$dbName}");
        } catch (\Exception $e) {
            $this->error('   ❌ فشل الاتصال بقاعدة البيانات: ' . $e->getMessage());
        }
        
        $this->newLine();
    }

    private function checkDatabaseTables()
    {
        $this->info('🗄️  فحص جداول قاعدة البيانات...');
        
        $requiredTables = [
            'orders',
            'customers',
            'services',
            'order_items',
            'customer_messages',
            'order_documentation',
            'users',
            'contact_messages',
        ];

        $missingTables = [];
        foreach ($requiredTables as $table) {
            if (Schema::hasTable($table)) {
                $count = DB::table($table)->count();
                $this->line("   ✅ جدول {$table}: موجود ({$count} سجل)");
            } else {
                $missingTables[] = $table;
                $this->error("   ❌ جدول {$table}: غير موجود");
            }
        }

        // Check for new columns in customer_messages
        if (Schema::hasTable('customer_messages')) {
            $columns = Schema::getColumnListing('customer_messages');
            $requiredColumns = ['file_path', 'file_name', 'file_type', 'file_size', 'mime_type'];
            $missingColumns = array_diff($requiredColumns, $columns);
            
            if (empty($missingColumns)) {
                $this->line("   ✅ جميع الأعمدة المطلوبة موجودة في customer_messages");
            } else {
                $this->warn("   ⚠️  أعمدة مفقودة في customer_messages: " . implode(', ', $missingColumns));
                $this->line("   💡 قم بتشغيل: php artisan migrate");
            }
        }

        if (!empty($missingTables)) {
            $this->warn("   ⚠️  جداول مفقودة: " . implode(', ', $missingTables));
            $this->line("   💡 قم بتشغيل: php artisan migrate");
        }

        $this->newLine();
    }

    private function checkModels()
    {
        $this->info('🔧 فحص النماذج (Models)...');
        
        try {
            // Check Order Model
            $ordersCount = Order::count();
            $this->line("   ✅ Order Model: يعمل ({$ordersCount} طلب)");
            
            // Check Customer Model
            $customersCount = Customer::count();
            $this->line("   ✅ Customer Model: يعمل ({$customersCount} عميل)");
            
            // Check Service Model
            $servicesCount = Service::count();
            $this->line("   ✅ Service Model: يعمل ({$servicesCount} خدمة)");
            
            // Check CustomerMessage Model
            $messagesCount = CustomerMessage::count();
            $this->line("   ✅ CustomerMessage Model: يعمل ({$messagesCount} رسالة)");
            
            // Check OrderDocumentation Model
            $docsCount = OrderDocumentation::count();
            $this->line("   ✅ OrderDocumentation Model: يعمل ({$docsCount} ملف توثيق)");
            
            // Test Relationships
            if ($ordersCount > 0) {
                $order = Order::first();
                if ($order->customer) {
                    $this->line("   ✅ Order->Customer Relationship: يعمل");
                }
                if ($order->documentation) {
                    $this->line("   ✅ Order->Documentation Relationship: يعمل");
                }
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ خطأ في النماذج: ' . $e->getMessage());
        }
        
        $this->newLine();
    }

    private function checkRoutes()
    {
        $this->info('🛣️  فحص المسارات (Routes)...');
        
        $requiredRoutes = [
            'admin.dashboard',
            'admin.orders.index',
            'admin.orders.show',
            'customer.dashboard',
            'customer.orders.index',
            'customer.orders.show',
            'customer.messages.index',
            'customer.messages.store',
            'customer.messages.get',
            'admin.customers.messages.get',
            'admin.customers.messages.send',
        ];

        $routes = \Route::getRoutes();
        $missingRoutes = [];
        
        foreach ($requiredRoutes as $routeName) {
            if ($routes->getByName($routeName)) {
                $this->line("   ✅ Route: {$routeName}");
            } else {
                $missingRoutes[] = $routeName;
                $this->error("   ❌ Route: {$routeName} - غير موجود");
            }
        }

        if (!empty($missingRoutes)) {
            $this->warn("   ⚠️  مسارات مفقودة: " . implode(', ', $missingRoutes));
        }
        
        $this->newLine();
    }

    private function checkStorage()
    {
        $this->info('💾 فحص التخزين (Storage)...');
        
        $storagePaths = [
            'storage/app/public',
            'storage/app/public/documentation',
            'storage/app/public/customer-messages',
            'storage/logs',
        ];

        foreach ($storagePaths as $path) {
            $fullPath = storage_path($path);
            if (file_exists($fullPath)) {
                $this->line("   ✅ {$path}: موجود");
            } else {
                $this->warn("   ⚠️  {$path}: غير موجود");
                if (!file_exists(dirname($fullPath))) {
                    mkdir(dirname($fullPath), 0755, true);
                }
                mkdir($fullPath, 0755, true);
                $this->line("   ✅ تم إنشاء: {$path}");
            }
        }

        // Check storage link
        $publicLink = public_path('storage');
        if (file_exists($publicLink) || is_link($publicLink)) {
            $this->line("   ✅ Storage Link: موجود");
        } else {
            $this->warn("   ⚠️  Storage Link: غير موجود");
            $this->line("   💡 قم بتشغيل: php artisan storage:link");
        }
        
        $this->newLine();
    }

    private function checkConfiguration()
    {
        $this->info('⚙️  فحص الإعدادات (Configuration)...');
        
        // Check .env file
        if (file_exists(base_path('.env'))) {
            $this->line("   ✅ ملف .env: موجود");
        } else {
            $this->error("   ❌ ملف .env: غير موجود");
        }

        // Check APP_KEY
        if (config('app.key')) {
            $this->line("   ✅ APP_KEY: موجود");
        } else {
            $this->error("   ❌ APP_KEY: غير موجود");
            $this->line("   💡 قم بتشغيل: php artisan key:generate");
        }

        // Check Database Config
        $dbConnection = config('database.default');
        $this->line("   ✅ Database Connection: {$dbConnection}");

        // Check Cache
        try {
            cache()->put('test', 'value', 1);
            cache()->get('test');
            $this->line("   ✅ Cache: يعمل");
        } catch (\Exception $e) {
            $this->error("   ❌ Cache: لا يعمل - " . $e->getMessage());
        }
        
        $this->newLine();
    }
}

