<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Admin;
use App\Models\Customer;
use App\Models\Service;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ContactMessage;
use App\Models\CustomerMessage;
use App\Models\OrderDocumentation;
use Carbon\Carbon;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🌱 بدء إنشاء البيانات الوهمية...');
        
        // Seed Users (Admins)
        $this->seedUsers();
        
        // Seed Customers
        $this->seedCustomers();
        
        // Seed Services
        $this->seedServices();
        
        // Seed Orders
        $this->seedOrders();
        
        // Seed Contact Messages
        $this->seedContactMessages();
        
        // Seed Customer Messages
        $this->seedCustomerMessages();
        
        $this->command->info('✅ تم إنشاء البيانات الوهمية بنجاح!');
    }

    private function seedUsers()
    {
        $this->command->info('👤 إنشاء المستخدمين...');
        
        $admins = [
            [
                'name' => 'مدير النظام',
                'email' => 'admin@wasila.org',
                'password' => Hash::make('password'),
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'name' => 'أحمد محمد',
                'email' => 'ahmed@wasila.org',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::firstOrCreate(['email' => $admin['email']], $admin);
        }
        
        $this->command->info('✅ تم إنشاء ' . count($admins) . ' مستخدم');
    }

    private function seedCustomers()
    {
        $this->command->info('👥 إنشاء العملاء...');
        
        $customers = [
            [
                'name' => 'محمد أحمد العلي',
                'email' => 'mohammed.ali@example.com',
                'phone' => '+966501234567',
                'password' => Hash::make('password'),
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'فاطمة سالم',
                'email' => 'fatima.salem@example.com',
                'phone' => '+966507654321',
                'password' => Hash::make('password'),
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'خالد عبدالله',
                'email' => 'khalid.abdullah@example.com',
                'phone' => '+966509876543',
                'password' => Hash::make('password'),
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'سارة محمد',
                'email' => 'sara.mohammed@example.com',
                'phone' => '+966501112233',
                'password' => Hash::make('password'),
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'عبدالرحمن حسن',
                'email' => 'abdulrahman.hassan@example.com',
                'phone' => '+966504445566',
                'password' => Hash::make('password'),
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
        ];

        foreach ($customers as $customer) {
            Customer::firstOrCreate(['email' => $customer['email']], $customer);
        }
        
        $this->command->info('✅ تم إنشاء ' . count($customers) . ' عميل');
    }

    private function seedServices()
    {
        $this->command->info('🛍️ إنشاء الخدمات...');
        
        if (Service::count() > 0) {
            $this->command->info('⚠️  الخدمات موجودة بالفعل');
            return;
        }

        $services = [
            [
                'name_ar' => 'توزيع المياه للمساجد',
                'name_en' => 'Water Distribution for Mosques',
                'description_ar' => 'خدمة توزيع المياه النقية للمساجد في جميع أنحاء المملكة',
                'description_en' => 'Pure water distribution service for mosques across the Kingdom',
                'price' => 500.00,
                'category_ar' => 'خدمات اجتماعية',
                'category_en' => 'Social Services',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name_ar' => 'وجبات إفطار رمضان',
                'name_en' => 'Ramadan Iftar Meals',
                'description_ar' => 'توزيع وجبات إفطار للمحتاجين خلال شهر رمضان المبارك',
                'description_en' => 'Iftar meal distribution for the needy during the holy month of Ramadan',
                'price' => 300.00,
                'category_ar' => 'خدمات اجتماعية',
                'category_en' => 'Social Services',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name_ar' => 'كفالة أيتام',
                'name_en' => 'Orphan Sponsorship',
                'description_ar' => 'برنامج كفالة الأيتام وتوفير الرعاية الشاملة لهم',
                'description_en' => 'Orphan sponsorship program providing comprehensive care',
                'price' => 1000.00,
                'category_ar' => 'خدمات اجتماعية',
                'category_en' => 'Social Services',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name_ar' => 'مساعدة طبية',
                'name_en' => 'Medical Assistance',
                'description_ar' => 'توفير المساعدة الطبية للمحتاجين',
                'description_en' => 'Providing medical assistance to the needy',
                'price' => 2000.00,
                'category_ar' => 'خدمات إنسانية',
                'category_en' => 'Humanitarian Services',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name_ar' => 'مساعدة تعليمية',
                'name_en' => 'Educational Support',
                'description_ar' => 'دعم التعليم وتوفير المستلزمات الدراسية',
                'description_en' => 'Educational support and providing school supplies',
                'price' => 800.00,
                'category_ar' => 'خدمات إنسانية',
                'category_en' => 'Humanitarian Services',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
        
        $this->command->info('✅ تم إنشاء ' . count($services) . ' خدمة');
    }

    private function seedOrders()
    {
        $this->command->info('📦 إنشاء الطلبات...');
        
        $customers = Customer::all();
        $services = Service::all();
        
        if ($customers->isEmpty() || $services->isEmpty()) {
            $this->command->error('⚠️  يجب إنشاء العملاء والخدمات أولاً');
            return;
        }

        // Clear existing orders if any
        DB::table('order_items')->delete();
        DB::table('orders')->delete();

        $statuses = ['pending', 'confirmed', 'processing', 'completed', 'cancelled'];
        $paymentStatuses = ['pending', 'paid', 'failed'];
        $paymentMethods = ['credit_card', 'bank_transfer', 'cash', 'myfatoorah'];
        
        $addresses = [
            'الرياض - حي النرجس - شارع الملك فهد',
            'جدة - حي الزهراء - شارع التحلية',
            'الدمام - حي الفيصلية - شارع الأمير سلطان',
            'المدينة المنورة - حي العقيق - شارع قباء',
            'مكة المكرمة - حي العزيزية - شارع الحجون',
            'الطائف - حي الشهداء - شارع الملك عبدالعزيز',
            'أبها - حي المطار - شارع الملك فيصل',
            'بريدة - حي الصفراء - شارع الملك خالد',
        ];

        $notes = [
            'يرجى التوصيل في الصباح',
            'التوصيل بعد صلاة العصر',
            'يرجى التواصل قبل التوصيل',
            'لا توجد ملاحظات خاصة',
            'يرجى التأكد من جودة الخدمة',
            'شكراً لجهودكم',
            null,
            null,
        ];

        // Create 50 orders with diverse data
        for ($i = 0; $i < 50; $i++) {
            $customer = $customers->random();
            $service = $services->random();
            $quantity = rand(1, 5);
            $unitPrice = $service->price;
            $totalPrice = $unitPrice * $quantity;
            
            // More realistic status distribution
            $statusWeights = [
                'pending' => 15,
                'confirmed' => 10,
                'processing' => 10,
                'completed' => 12,
                'cancelled' => 3,
            ];
            $status = $this->weightedRandom($statusWeights);
            
            // Payment status based on order status
            if ($status === 'completed') {
                $paymentStatus = 'paid';
            } elseif ($status === 'cancelled') {
                $paymentStatus = rand(0, 1) ? 'failed' : 'pending';
            } else {
                $paymentStatus = $paymentStatuses[array_rand($paymentStatuses)];
            }
            
            $paymentMethod = $paymentStatus === 'paid' 
                ? $paymentMethods[array_rand($paymentMethods)]
                : ($paymentStatus === 'failed' ? null : null);
            
            // Create order with realistic dates
            $daysAgo = rand(0, 90);
            $hoursAgo = rand(0, 23);
            $createdAt = Carbon::now()->subDays($daysAgo)->subHours($hoursAgo);
            $updatedAt = $createdAt->copy()->addHours(rand(1, 48));

            $order = Order::create([
                'order_number' => 'WAS-' . strtoupper(uniqid()),
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => $customer->phone ?? '+96650' . rand(1000000, 9999999),
                'customer_country' => 'SA',
                'country_code' => '+966',
                'full_phone_number' => $customer->phone ?? '+96650' . rand(1000000, 9999999),
                'customer_address' => $addresses[array_rand($addresses)],
                'total_amount' => $totalPrice,
                'status' => $status,
                'payment_status' => $paymentStatus,
                'payment_method' => $paymentMethod,
                'payment_reference' => $paymentStatus === 'paid' ? 'REF-' . strtoupper(uniqid()) : null,
                'payment_details' => $paymentStatus === 'paid' ? json_encode([
                    'transaction_id' => 'TXN-' . strtoupper(uniqid()),
                    'payment_date' => $createdAt->toDateTimeString(),
                    'method' => $paymentMethod,
                ]) : null,
                'notes' => $notes[array_rand($notes)],
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            // Create order items
            OrderItem::create([
                'order_id' => $order->id,
                'service_id' => $service->id,
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'total_price' => $totalPrice,
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);
        }
        
        $this->command->info('✅ تم إنشاء 50 طلب مع تفاصيل متنوعة');
    }

    /**
     * Helper function for weighted random selection
     */
    private function weightedRandom(array $weights)
    {
        $total = array_sum($weights);
        $random = rand(1, $total);
        $current = 0;
        
        foreach ($weights as $key => $weight) {
            $current += $weight;
            if ($random <= $current) {
                return $key;
            }
        }
        
        return array_key_first($weights);
    }

    private function seedContactMessages()
    {
        $this->command->info('📧 إنشاء رسائل التواصل...');
        
        $subjects = [
            'استفسار عن خدمات المساجد',
            'طلب مساعدة غذائية',
            'استفسار عن المشاريع',
            'استفسار عن كفالة الأيتام',
            'طلب مساعدة طبية',
            'استفسار عام',
            'شكر وتقدير',
            'اقتراحات وتحسينات',
        ];

        $messages = [
            'أود الاستفسار عن خدمات توزيع المياه للمساجد وكيفية التقديم',
            'نحن أسرة محتاجة ونود الحصول على مساعدة في الوجبات الغذائية',
            'أود الاستفسار عن مشاريعكم، كيف يمكنني المساهمة؟',
            'أريد معرفة تفاصيل برنامج كفالة الأيتام',
            'نحتاج مساعدة طبية عاجلة، كيف يمكن التقديم؟',
            'شكراً لكم على جهودكم في خدمة المجتمع',
            'أود تقديم اقتراحات لتحسين الخدمات',
            'متى يمكنني الحصول على المساعدة التعليمية؟',
        ];

        $names = [
            'أحمد محمد العلي',
            'فاطمة سالم',
            'محمد السعد',
            'سارة أحمد',
            'خالد عبدالله',
            'نورا حسن',
            'عبدالرحمن علي',
            'مريم محمد',
            'يوسف أحمد',
            'ليلى سالم',
        ];

        $emails = [
            'ahmed@example.com',
            'fatima@example.com',
            'mohammed@example.com',
            'sara@example.com',
            'khalid@example.com',
            'nora@example.com',
            'abdulrahman@example.com',
            'mariam@example.com',
            'youssef@example.com',
            'layla@example.com',
        ];

        for ($i = 0; $i < 30; $i++) {
            $isRead = rand(0, 1);
            $createdAt = Carbon::now()->subDays(rand(0, 60))->subHours(rand(0, 23));
            
            ContactMessage::create([
                'name' => $names[array_rand($names)],
                'email' => $emails[array_rand($emails)],
                'phone' => '+96650' . rand(1000000, 9999999),
                'subject' => $subjects[array_rand($subjects)],
                'message' => $messages[array_rand($messages)],
                'is_read' => $isRead,
                'read_at' => $isRead ? $createdAt->addHours(rand(1, 24)) : null,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }
        
        $this->command->info('✅ تم إنشاء 30 رسالة تواصل');
    }

    private function seedCustomerMessages()
    {
        $this->command->info('💬 إنشاء رسائل العملاء...');
        
        $customers = Customer::all();
        $orders = Order::all();
        $admins = Admin::all();
        
        if ($customers->isEmpty() || $admins->isEmpty()) {
            $this->command->error('⚠️  يجب إنشاء العملاء والمستخدمين أولاً');
            return;
        }

        $customerMessages = [
            'مرحباً، أريد الاستفسار عن حالة طلبي',
            'شكراً لكم على الخدمة الممتازة',
            'متى سيتم تسليم الطلب؟',
            'أريد تعديل بيانات الطلب',
            'هل يمكن إضافة خدمة أخرى؟',
        ];

        $adminMessages = [
            'تم استلام طلبك وسيتم معالجته قريباً',
            'شكراً لثقتك بنا',
            'تم تحديث حالة الطلب',
            'سيتم التواصل معك قريباً',
            'تم إرسال الطلب بنجاح',
        ];

        foreach ($customers as $customer) {
            $customerOrders = $orders->where('customer_id', $customer->id);
            
            for ($i = 0; $i < rand(3, 8); $i++) {
                $order = $customerOrders->isNotEmpty() ? $customerOrders->random() : null;
                $senderType = rand(0, 1) ? 'customer' : 'admin';
                $isRead = $senderType === 'admin' ? rand(0, 1) : true;
                
                $message = $senderType === 'customer' 
                    ? $customerMessages[array_rand($customerMessages)]
                    : $adminMessages[array_rand($adminMessages)];
                
                $createdAt = Carbon::now()->subDays(rand(0, 30))->subHours(rand(0, 23));
                
                CustomerMessage::create([
                    'customer_id' => $customer->id,
                    'order_id' => $order ? $order->id : null,
                    'message' => $message,
                    'sender_type' => $senderType,
                    'admin_id' => $senderType === 'admin' ? $admins->random()->id : null,
                    'is_read' => $isRead,
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);
            }
        }
        
        $this->command->info('✅ تم إنشاء رسائل العملاء');
    }
}

