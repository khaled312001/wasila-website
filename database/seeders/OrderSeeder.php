<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🗑️  حذف الطلبات القديمة...');
        
        // حذف جميع الطلبات القديمة
        DB::table('order_items')->delete();
        DB::table('orders')->delete();
        
        $this->command->info('✅ تم حذف جميع الطلبات القديمة');
        
        $this->command->info('📦 إنشاء طلبات ديمو جديدة...');
        
        $customers = Customer::all();
        $services = Service::all();
        
        if ($customers->isEmpty() || $services->isEmpty()) {
            $this->command->error('⚠️  يجب إنشاء العملاء والخدمات أولاً');
            $this->command->info('💡 قم بتشغيل: php artisan db:seed --class=DummyDataSeeder');
            return;
        }

        $addresses = [
            'الرياض - حي النرجس - شارع الملك فهد - مبنى 123',
            'جدة - حي الزهراء - شارع التحلية - برج النور',
            'الدمام - حي الفيصلية - شارع الأمير سلطان - مجمع التجارة',
            'المدينة المنورة - حي العقيق - شارع قباء - قصر السلام',
            'مكة المكرمة - حي العزيزية - شارع الحجون - عمارة الأندلس',
            'الطائف - حي الشهداء - شارع الملك عبدالعزيز - مجمع الخير',
            'أبها - حي المطار - شارع الملك فيصل - برج الأصالة',
            'بريدة - حي الصفراء - شارع الملك خالد - مجمع النهضة',
            'خميس مشيط - حي المطار - شارع الملك عبدالله',
            'نجران - حي العليا - شارع الأمير مشعل',
        ];

        $notes = [
            'يرجى التوصيل في الصباح الباكر',
            'التوصيل بعد صلاة العصر مباشرة',
            'يرجى التواصل قبل التوصيل بوقت كاف',
            'لا توجد ملاحظات خاصة',
            'يرجى التأكد من جودة الخدمة المقدمة',
            'شكراً لجهودكم المتميزة',
            'أرجو التوصيل في عطلة نهاية الأسبوع',
            'يرجى التواصل على رقم الجوال فقط',
            null,
            null,
        ];

        $paymentMethods = ['myfatoorah', 'bank_transfer', 'cash', 'credit_card'];
        
        // إنشاء 30-50 طلب متنوع
        $orderCount = rand(30, 50);
        
        for ($i = 0; $i < $orderCount; $i++) {
            $customer = $customers->random();
            $service = $services->random();
            $quantity = rand(1, 5);
            $unitPrice = $service->price;
            $totalPrice = $unitPrice * $quantity;
            
            // توزيع واقعي للحالات
            $statusRand = rand(1, 100);
            if ($statusRand <= 20) {
                $status = 'pending';
            } elseif ($statusRand <= 40) {
                $status = 'confirmed';
            } elseif ($statusRand <= 60) {
                $status = 'processing';
            } elseif ($statusRand <= 85) {
                $status = 'completed';
            } else {
                $status = 'cancelled';
            }
            
            // حالة الدفع بناءً على حالة الطلب
            if ($status === 'completed') {
                $paymentStatus = 'paid';
                $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
            } elseif ($status === 'cancelled') {
                $paymentStatus = rand(0, 1) ? 'failed' : 'pending';
                $paymentMethod = null;
            } else {
                $paymentStatusRand = rand(1, 100);
                if ($paymentStatusRand <= 30) {
                    $paymentStatus = 'paid';
                    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                } elseif ($paymentStatusRand <= 50) {
                    $paymentStatus = 'pending';
                    $paymentMethod = null;
                } else {
                    $paymentStatus = 'failed';
                    $paymentMethod = null;
                }
            }
            
            // تواريخ واقعية (آخر 90 يوم)
            $daysAgo = rand(0, 90);
            $hoursAgo = rand(0, 23);
            $minutesAgo = rand(0, 59);
            $createdAt = Carbon::now()
                ->subDays($daysAgo)
                ->subHours($hoursAgo)
                ->subMinutes($minutesAgo);
            
            $updatedAt = $createdAt->copy()->addHours(rand(1, 72));

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
                    'amount' => $totalPrice,
                ]) : null,
                'notes' => $notes[array_rand($notes)],
                'created_at' => $createdAt,
                'updated_at' => $updatedAt,
            ]);

            // إنشاء عناصر الطلب
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
        
        $this->command->info("✅ تم إنشاء {$orderCount} طلب ديمو بنجاح!");
        $this->command->info("📊 إحصائيات:");
        $this->command->info("   - الطلبات المعلقة: " . Order::where('status', 'pending')->count());
        $this->command->info("   - الطلبات المؤكدة: " . Order::where('status', 'confirmed')->count());
        $this->command->info("   - الطلبات قيد المعالجة: " . Order::where('status', 'processing')->count());
        $this->command->info("   - الطلبات المكتملة: " . Order::where('status', 'completed')->count());
        $this->command->info("   - الطلبات الملغاة: " . Order::where('status', 'cancelled')->count());
        $this->command->info("   - إجمالي المدفوع: " . number_format(Order::where('payment_status', 'paid')->sum('total_amount'), 2) . " ريال");
    }
}

