<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Service;
use App\Models\CustomerMessage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إنشاء عملاء وهميين
        $customers = [
            [
                'name' => 'أحمد محمد العلي',
                'email' => 'ahmed@example.com',
                'google_id' => 'google_' . Str::random(21),
                'avatar' => 'https://ui-avatars.com/api/?name=أحمد+محمد&background=08788B&color=fff&size=128',
                'phone' => '+966501234567',
                'address' => 'الرياض، حي النرجس',
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'فاطمة علي السالم',
                'email' => 'fatima@example.com',
                'google_id' => 'google_' . Str::random(21),
                'avatar' => 'https://ui-avatars.com/api/?name=فاطمة+علي&background=3CA6B4&color=fff&size=128',
                'phone' => '+966507654321',
                'address' => 'جدة، حي الزهراء',
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'محمد عبدالله الحسن',
                'email' => 'mohammed@example.com',
                'google_id' => 'google_' . Str::random(21),
                'avatar' => 'https://ui-avatars.com/api/?name=محمد+عبدالله&background=10b981&color=fff&size=128',
                'phone' => '+966509876543',
                'address' => 'الدمام، حي الفيصلية',
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'سارة أحمد المطيري',
                'email' => 'sara@example.com',
                'google_id' => 'google_' . Str::random(21),
                'avatar' => 'https://ui-avatars.com/api/?name=سارة+أحمد&background=08788B&color=fff&size=128',
                'phone' => '+966502345678',
                'address' => 'مكة المكرمة، حي العزيزية',
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'خالد سعد الدوسري',
                'email' => 'khaled@example.com',
                'google_id' => 'google_' . Str::random(21),
                'avatar' => 'https://ui-avatars.com/api/?name=خالد+سعد&background=3CA6B4&color=fff&size=128',
                'phone' => '+966503456789',
                'address' => 'الطائف، حي الشهداء',
                'locale' => 'ar',
                'email_verified_at' => now(),
            ],
        ];

        $createdCustomers = [];
        
        foreach ($customers as $customerData) {
            $customer = Customer::create($customerData);
            $createdCustomers[] = $customer;
            
            // إنشاء طلبات وهمية لكل عميل
            $services = Service::take(3)->get();
            
            if ($services->count() > 0) {
                foreach ($services->take(rand(1, 3)) as $service) {
                    $order = Order::create([
                        'customer_id' => $customer->id,
                        'order_number' => 'WAS-' . strtoupper(Str::random(8)),
                        'customer_name' => $customer->name,
                        'customer_email' => $customer->email,
                        'customer_phone' => $customer->phone,
                        'customer_country' => 'السعودية',
                        'country_code' => '+966',
                        'full_phone_number' => $customer->phone,
                        'customer_address' => $customer->address,
                        'total_amount' => $service->price * rand(1, 3),
                        'status' => collect(['pending', 'confirmed', 'processing', 'completed'])->random(),
                        'payment_status' => collect(['pending', 'paid', 'failed'])->random(),
                        'payment_method' => collect(['myfatoorah', 'bank_transfer', 'cash'])->random(),
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'service_id' => $service->id,
                        'quantity' => rand(1, 3),
                        'unit_price' => $service->price,
                        'total_price' => $service->price * rand(1, 3),
                    ]);
                }
            }
            
            // إنشاء رسائل وهمية
            if (rand(0, 1)) {
                CustomerMessage::create([
                    'customer_id' => $customer->id,
                    'order_id' => $customer->orders()->first()?->id,
                    'message' => 'شكراً لكم على الخدمة المميزة. أود الاستفسار عن حالة طلبي.',
                    'sender_type' => 'customer',
                    'is_read' => rand(0, 1),
                    'created_at' => now()->subDays(rand(1, 7)),
                ]);
            }
        }
        
        // عرض معلومات الدخول
        echo "\n✅ تم إنشاء " . count($createdCustomers) . " عميل وهمي:\n\n";
        
        foreach ($createdCustomers as $index => $customer) {
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n";
            echo "👤 العميل #" . ($index + 1) . ":\n";
            echo "   الاسم: {$customer->name}\n";
            echo "   البريد: {$customer->email}\n";
            echo "   الهاتف: {$customer->phone}\n";
            echo "   عدد الطلبات: " . $customer->orders()->count() . "\n";
            echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n\n";
        }
        
        echo "🔗 للدخول كعميل:\n";
        echo "   1. استخدم زر تسجيل الدخول بالجيميل في الموقع\n";
        echo "   2. أو استخدم البريد: {$createdCustomers[0]->email}\n";
        echo "   3. رابط لوحة التحكم: /customer/dashboard\n\n";
    }
}

