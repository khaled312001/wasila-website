<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'name_ar' => 'توزيع مياه للمساجد',
                'name_en' => 'Water Distribution for Mosques',
                'description_ar' => 'توزيع المياه النقية والباردة للمساجد لخدمة المصلين',
                'description_en' => 'Distribution of pure and cold water to mosques to serve worshippers',
                'price' => 50.00,
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name_ar' => 'منتجات عناية بالمساجد',
                'name_en' => 'Mosque Care Products',
                'description_ar' => 'توفير منتجات التنظيف والعناية بالمساجد',
                'description_en' => 'Providing cleaning and care products for mosques',
                'price' => 100.00,
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name_ar' => 'توزيع وجبات طعام',
                'name_en' => 'Food Meal Distribution',
                'description_ar' => 'توزيع وجبات طعام ساخنة للمحتاجين والأسر الفقيرة',
                'description_en' => 'Distribution of hot meals to the needy and poor families',
                'price' => 25.00,
                'category_ar' => 'خدمات الطعام',
                'category_en' => 'Food Services',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name_ar' => 'كراسي كبار السن',
                'name_en' => 'Elderly Chairs',
                'description_ar' => 'توفير كراسي مريحة لكبار السن في المساجد',
                'description_en' => 'Providing comfortable chairs for the elderly in mosques',
                'price' => 150.00,
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name_ar' => 'سجاد للمساجد',
                'name_en' => 'Mosque Carpets',
                'description_ar' => 'توفير سجاد نظيف ومريح للمساجد',
                'description_en' => 'Providing clean and comfortable carpets for mosques',
                'price' => 200.00,
                'category_ar' => 'خدمات المساجد',
                'category_en' => 'Mosque Services',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name_ar' => 'مصاحف وكتيبات دينية',
                'name_en' => 'Religious Books and Pamphlets',
                'description_ar' => 'توزيع المصاحف والكتيبات الدينية',
                'description_en' => 'Distribution of religious books and pamphlets',
                'price' => 30.00,
                'category_ar' => 'خدمات دينية',
                'category_en' => 'Religious Services',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
