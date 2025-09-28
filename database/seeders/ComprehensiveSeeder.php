<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Service;
use App\Models\Setting;
use App\Models\PortfolioItem;
use App\Models\ContactMessage;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create missing tables first
        $this->createMissingTables();
        
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables safely
        $this->truncateTableSafely('admins');
        $this->truncateTableSafely('services');
        $this->truncateTableSafely('settings');
        $this->truncateTableSafely('portfolio_items');
        $this->truncateTableSafely('contact_messages');
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Seed Admins
        $this->seedAdmins();
        
        // Seed Services
        $this->seedServices();
        
        // Seed Settings
        $this->seedSettings();
        
        // Seed Portfolio Items
        $this->seedPortfolioItems();
        
        // Seed Contact Messages
        $this->seedContactMessages();
        
        echo "All data seeded successfully!\n";
    }

    private function createMissingTables()
    {
        // Create contact_messages table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('contact_messages')) {
            DB::getSchemaBuilder()->create('contact_messages', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->text('message');
                $table->string('phone')->nullable();
                $table->string('subject')->nullable();
                $table->boolean('is_read')->default(false);
                $table->timestamp('read_at')->nullable();
                $table->timestamps();
            });
            echo "Created contact_messages table\n";
        }
        
        // Create settings table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('settings')) {
            DB::getSchemaBuilder()->create('settings', function ($table) {
                $table->id();
                $table->string('key')->unique();
                $table->text('value')->nullable();
                $table->string('type')->default('string');
                $table->text('description')->nullable();
                $table->timestamps();
            });
            echo "Created settings table\n";
        }
        
        // Create portfolio_items table if it doesn't exist
        if (!DB::getSchemaBuilder()->hasTable('portfolio_items')) {
            DB::getSchemaBuilder()->create('portfolio_items', function ($table) {
                $table->id();
                $table->string('title_ar');
                $table->string('title_en');
                $table->text('description_ar')->nullable();
                $table->text('description_en')->nullable();
                $table->enum('type', ['image', 'video']);
                $table->string('file_path');
                $table->boolean('is_active')->default(true);
                $table->integer('sort_order')->default(0);
                $table->timestamps();
            });
            echo "Created portfolio_items table\n";
        }
    }

    private function truncateTableSafely($tableName)
    {
        if (DB::getSchemaBuilder()->hasTable($tableName)) {
            DB::table($tableName)->truncate();
        }
    }

    private function seedAdmins()
    {
        Admin::create([
            'id' => 1,
            'name' => 'Super Admin',
            'email' => 'admin@wasila.org',
            'email_verified_at' => null,
            'password' => '$2y$12$PnkQS8AidWJq79pvVdQYi./ImVhJA49fxEPUF7M4CZw/Trmg296F.',
            'role' => 'super_admin',
            'is_active' => 1,
            'remember_token' => 'xPAv7K5UrJwVMyVfBjyUtgDt0LHtVdNDmhNPmnB0TpA9ZjU8Y3ehoLf9GESd',
            'created_at' => '2025-09-24 09:56:31',
            'updated_at' => '2025-09-24 09:56:31'
        ]);
    }

    private function seedServices()
    {
        $services = [
            [
                'id' => 1,
                'name_ar' => 'توزيع مياه للمساجد',
                'name_en' => 'Water Distribution for Mosques',
                'description_ar' => 'توزيع المياه النقية والباردة للمساجد لخدمة المصلين',
                'description_en' => 'Distribution of pure and cold water to mosques to serve worshippers',
                'price' => 50.00,
                'image' => 'services/3leViIFIgigeS22qstYpfjOeUCMwq12GjvJG0o4l.png',
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'is_active' => 1,
                'sort_order' => 1,
                'created_at' => '2025-09-24 10:00:00',
                'updated_at' => '2025-09-24 10:00:00'
            ],
            [
                'id' => 2,
                'name_ar' => 'منتجات عناية بالمساجد',
                'name_en' => 'Mosque Care Products',
                'description_ar' => 'منتجات متخصصة للعناية بنظافة وصيانة المساجد',
                'description_en' => 'Specialized products for mosque cleaning and maintenance',
                'price' => 75.00,
                'image' => 'services/mosque-care.png',
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'is_active' => 1,
                'sort_order' => 2,
                'created_at' => '2025-09-24 10:00:00',
                'updated_at' => '2025-09-24 10:00:00'
            ],
            [
                'id' => 3,
                'name_ar' => 'وجبات طعام للمحتاجين',
                'name_en' => 'Meals for the Needy',
                'description_ar' => 'توزيع وجبات الطعام الساخنة للأسر المحتاجة والفقراء',
                'description_en' => 'Distribution of hot meals to needy families and the poor',
                'price' => 25.00,
                'image' => 'services/meals.png',
                'category_ar' => 'المساعدات الغذائية',
                'category_en' => 'Food Aid',
                'is_active' => 1,
                'sort_order' => 3,
                'created_at' => '2025-09-24 10:00:00',
                'updated_at' => '2025-09-24 10:00:00'
            ],
            [
                'id' => 4,
                'name_ar' => 'كراسي كبار السن',
                'name_en' => 'Elderly Chairs',
                'description_ar' => 'توفير كراسي مريحة ومتخصصة لكبار السن',
                'description_en' => 'Providing comfortable and specialized chairs for the elderly',
                'price' => 200.00,
                'image' => 'services/elderly-chairs.png',
                'category_ar' => 'رعاية كبار السن',
                'category_en' => 'Elderly Care',
                'is_active' => 1,
                'sort_order' => 4,
                'created_at' => '2025-09-24 10:00:00',
                'updated_at' => '2025-09-24 10:00:00'
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }

    private function seedSettings()
    {
        $settings = [
            ['key' => 'site_name', 'value' => 'وسيلة', 'type' => 'string', 'description' => 'اسم الموقع'],
            ['key' => 'site_description', 'value' => 'منصة وسيلة الخيرية للتبرعات والخدمات', 'type' => 'text', 'description' => 'وصف الموقع'],
            ['key' => 'contact_email', 'value' => 'info@wasila-charity.com', 'type' => 'string', 'description' => 'البريد الإلكتروني للتواصل'],
            ['key' => 'contact_phone', 'value' => '+966 55 922 9980', 'type' => 'string', 'description' => 'رقم الهاتف للتواصل'],
            ['key' => 'address', 'value' => 'المملكة العربية السعودية', 'type' => 'text', 'description' => 'العنوان'],
            ['key' => 'logo', 'value' => 'logo-footer.png', 'type' => 'string', 'description' => 'شعار الموقع'],
            ['key' => 'myfatoorah_api_key', 'value' => 'rLtt2JWJUMGhpUVvJHQJxh4nxU4gqsx1dqlUYm5twRkx5aHvO3F7Hf8G', 'type' => 'string', 'description' => 'مفتاح API لبوابة الدفع MyFatoorah'],
            ['key' => 'myfatoorah_is_test', 'value' => '1', 'type' => 'boolean', 'description' => 'وضع الاختبار لبوابة الدفع'],
            ['key' => 'myfatoorah_currency', 'value' => 'SAU', 'type' => 'string', 'description' => 'كود الدولة لبوابة الدفع'],
            ['key' => 'hero_title_ar', 'value' => 'وسيلة - مشروع خيري اجتماعي', 'type' => 'string', 'description' => 'عنوان الصفحة الرئيسية بالعربية'],
            ['key' => 'hero_title_en', 'value' => 'Wasila - Social Charity Project', 'type' => 'string', 'description' => 'عنوان الصفحة الرئيسية بالإنجليزية'],
            ['key' => 'hero_subtitle_ar', 'value' => 'وسيلة', 'type' => 'string', 'description' => 'العنوان الفرعي بالعربية'],
            ['key' => 'hero_subtitle_en', 'value' => 'Wasila', 'type' => 'string', 'description' => 'العنوان الفرعي بالإنجليزية'],
            ['key' => 'hero_image', 'value' => '/storage/hero/hero_image.png', 'type' => 'string', 'description' => 'صورة الصفحة الرئيسية'],
            ['key' => 'about_title_ar', 'value' => 'من نحن', 'type' => 'string', 'description' => 'عنوان قسم من نحن بالعربية'],
            ['key' => 'about_title_en', 'value' => 'About Us', 'type' => 'string', 'description' => 'عنوان قسم من نحن بالإنجليزية'],
            ['key' => 'about_description_ar', 'value' => 'وسيلة هو مشروع خيري اجتماعي يهدف إلى تقديم خدمات إنسانية متنوعة للمجتمع. نحن نؤمن بأهمية العمل الخيري والتكافل الاجتماعي في بناء مجتمع أفضل.', 'type' => 'text', 'description' => 'وصف من نحن بالعربية'],
            ['key' => 'about_description_en', 'value' => 'Wasila is a social charity project that aims to provide various humanitarian services to the community. We believe in the importance of charitable work and social solidarity in building a better society.', 'type' => 'text', 'description' => 'وصف من نحن بالإنجليزية'],
            ['key' => 'about_mission_ar', 'value' => 'نعمل على توزيع المياه النقية، منتجات العناية بالمساجد، وجبات الطعام للمحتاجين، وكراسي كبار السن، وغيرها من الخدمات التي تساهم في رفاهية المجتمع.', 'type' => 'text', 'description' => 'رسالتنا بالعربية'],
            ['key' => 'about_mission_en', 'value' => 'We work on distributing pure water, mosque care products, meals for the needy, elderly chairs, and other services that contribute to community welfare.', 'type' => 'text', 'description' => 'رسالتنا بالإنجليزية'],
            ['key' => 'about_image', 'value' => '/storage/about/about_image.png', 'type' => 'string', 'description' => 'صورة قسم من نحن'],
            ['key' => 'feature1_title_ar', 'value' => 'خدمات متنوعة', 'type' => 'string', 'description' => 'عنوان الميزة الأولى بالعربية'],
            ['key' => 'feature1_title_en', 'value' => 'Diverse Services', 'type' => 'string', 'description' => 'عنوان الميزة الأولى بالإنجليزية'],
            ['key' => 'feature1_description_ar', 'value' => 'نقدم مجموعة واسعة من الخدمات الخيرية والاجتماعية مع ضمان الجودة العالية والكفاءة في التنفيذ', 'type' => 'text', 'description' => 'وصف الميزة الأولى بالعربية'],
            ['key' => 'feature1_description_en', 'value' => 'We provide a wide range of charitable and social services with guaranteed high quality and efficiency in implementation', 'type' => 'text', 'description' => 'وصف الميزة الأولى بالإنجليزية'],
            ['key' => 'feature1_icon', 'value' => 'fas fa-hands-helping', 'type' => 'string', 'description' => 'أيقونة الميزة الأولى'],
            ['key' => 'feature2_title_ar', 'value' => 'شفافية كاملة', 'type' => 'string', 'description' => 'عنوان الميزة الثانية بالعربية'],
            ['key' => 'feature2_title_en', 'value' => 'Complete Transparency', 'type' => 'string', 'description' => 'عنوان الميزة الثانية بالإنجليزية'],
            ['key' => 'feature2_description_ar', 'value' => 'نضمن الشفافية الكاملة في جميع عملياتنا وتقديم تقارير دورية عن استخدام التبرعات والخدمات المقدمة', 'type' => 'text', 'description' => 'وصف الميزة الثانية بالعربية'],
            ['key' => 'feature2_description_en', 'value' => 'We ensure complete transparency in all our operations and provide periodic reports on the use of donations and services provided', 'type' => 'text', 'description' => 'وصف الميزة الثانية بالإنجليزية'],
            ['key' => 'feature2_icon', 'value' => 'fas fa-eye', 'type' => 'string', 'description' => 'أيقونة الميزة الثانية'],
            ['key' => 'feature3_title_ar', 'value' => 'تأثير إيجابي', 'type' => 'string', 'description' => 'عنوان الميزة الثالثة بالعربية'],
            ['key' => 'feature3_title_en', 'value' => 'Positive Impact', 'type' => 'string', 'description' => 'عنوان الميزة الثالثة بالإنجليزية'],
            ['key' => 'feature3_description_ar', 'value' => 'نسعى لتحقيق تأثير إيجابي ملموس في المجتمع من خلال خدماتنا المتخصصة والمدروسة بعناية', 'type' => 'text', 'description' => 'وصف الميزة الثالثة بالعربية'],
            ['key' => 'feature3_description_en', 'value' => 'We strive to achieve a tangible positive impact in the community through our specialized and carefully studied services', 'type' => 'text', 'description' => 'وصف الميزة الثالثة بالإنجليزية'],
            ['key' => 'feature3_icon', 'value' => 'fas fa-heart', 'type' => 'string', 'description' => 'أيقونة الميزة الثالثة'],
            ['key' => 'cta_title_ar', 'value' => 'ابدأ رحلتك الخيرية معنا', 'type' => 'string', 'description' => 'عنوان دعوة العمل بالعربية'],
            ['key' => 'cta_title_en', 'value' => 'Start Your Charitable Journey With Us', 'type' => 'string', 'description' => 'عنوان دعوة العمل بالإنجليزية'],
            ['key' => 'cta_description_ar', 'value' => 'انضم إلينا في رحلة العطاء والخير واجعل تبرعك وسيلة لتحقيق التغيير الإيجابي في المجتمع', 'type' => 'text', 'description' => 'وصف دعوة العمل بالعربية'],
            ['key' => 'cta_description_en', 'value' => 'Join us on the journey of giving and goodness and make your donation a means to achieve positive change in society', 'type' => 'text', 'description' => 'وصف دعوة العمل بالإنجليزية'],
            ['key' => 'footer_description_ar', 'value' => 'وسيلة - مشروع خيري اجتماعي يهدف لخدمة المجتمع وتقديم المساعدة للمحتاجين', 'type' => 'text', 'description' => 'وصف التذييل بالعربية'],
            ['key' => 'footer_description_en', 'value' => 'Wasila - A social charity project aimed at serving the community and providing assistance to those in need', 'type' => 'text', 'description' => 'وصف التذييل بالإنجليزية']
        ];

        foreach ($settings as $setting) {
            Setting::create([
                'key' => $setting['key'],
                'value' => $setting['value'],
                'type' => $setting['type'],
                'description' => $setting['description'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    private function seedPortfolioItems()
    {
        $portfolioItems = [
            [
                'id' => 1,
                'title_ar' => 'توزيع مياه المساجد - الرياض',
                'title_en' => 'Mosque Water Distribution - Riyadh',
                'description_ar' => 'مشروع توزيع المياه النقية والباردة على مساجد الرياض خلال فصل الصيف',
                'description_en' => 'Project to distribute pure and cold water to Riyadh mosques during summer',
                'type' => 'image',
                'file_path' => 'portfolio/1.png',
                'is_active' => 1,
                'sort_order' => 1,
                'created_at' => '2025-09-27 15:00:00',
                'updated_at' => '2025-09-27 15:00:00'
            ],
            [
                'id' => 2,
                'title_ar' => 'وجبات الطعام للأسر المحتاجة',
                'title_en' => 'Meals for Needy Families',
                'description_ar' => 'توزيع وجبات الطعام الساخنة على الأسر المحتاجة في الأحياء الفقيرة',
                'description_en' => 'Distribution of hot meals to needy families in poor neighborhoods',
                'type' => 'image',
                'file_path' => 'portfolio/2.png',
                'is_active' => 1,
                'sort_order' => 2,
                'created_at' => '2025-09-27 15:00:00',
                'updated_at' => '2025-09-27 15:00:00'
            ],
            [
                'id' => 3,
                'title_ar' => 'كراسي كبار السن - مشروع الراحة',
                'title_en' => 'Elderly Chairs - Comfort Project',
                'description_ar' => 'توفير كراسي مريحة ومتخصصة لكبار السن في دور الرعاية والمراكز الصحية',
                'description_en' => 'Providing comfortable and specialized chairs for the elderly in care homes and health centers',
                'type' => 'image',
                'file_path' => 'portfolio/3.png',
                'is_active' => 1,
                'sort_order' => 3,
                'created_at' => '2025-09-27 15:00:00',
                'updated_at' => '2025-09-27 15:00:00'
            ],
            [
                'id' => 4,
                'title_ar' => 'منتجات العناية بالمساجد',
                'title_en' => 'Mosque Care Products',
                'description_ar' => 'توفير منتجات التنظيف والعناية المتخصصة للمساجد',
                'description_en' => 'Providing specialized cleaning and care products for mosques',
                'type' => 'image',
                'file_path' => 'portfolio/4.png',
                'is_active' => 1,
                'sort_order' => 4,
                'created_at' => '2025-09-27 15:00:00',
                'updated_at' => '2025-09-27 15:00:00'
            ]
        ];

        foreach ($portfolioItems as $item) {
            PortfolioItem::create($item);
        }
    }

    private function seedContactMessages()
    {
        $messages = [
            [
                'id' => 1,
                'name' => 'أحمد محمد',
                'email' => 'ahmed@example.com',
                'phone' => '+966501234567',
                'subject' => 'استفسار عن خدمات المساجد',
                'message' => 'أود الاستفسار عن خدمات توزيع المياه للمساجد وكيفية التقديم',
                'is_read' => 0,
                'created_at' => '2025-09-27 10:30:00',
                'updated_at' => '2025-09-27 10:30:00'
            ],
            [
                'id' => 2,
                'name' => 'فاطمة علي',
                'email' => 'fatima@example.com',
                'phone' => '+966507654321',
                'subject' => 'طلب مساعدة غذائية',
                'message' => 'نحن أسرة محتاجة ونود الحصول على مساعدة في الوجبات الغذائية',
                'is_read' => 1,
                'created_at' => '2025-09-26 14:15:00',
                'updated_at' => '2025-09-27 09:00:00'
            ],
            [
                'id' => 3,
                'name' => 'محمد السعد',
                'email' => 'mohammed@example.com',
                'phone' => '+966509876543',
                'subject' => 'تبرع لمشاريع الخير',
                'message' => 'أود المساهمة في مشاريعكم الخيرية، كيف يمكنني التبرع؟',
                'is_read' => 0,
                'created_at' => '2025-09-25 16:45:00',
                'updated_at' => '2025-09-25 16:45:00'
            ]
        ];

        foreach ($messages as $message) {
            ContactMessage::create($message);
        }
    }
}
