<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'site_name',
                'value' => 'وسيلة',
                'type' => 'string',
                'description' => 'اسم الموقع'
            ],
            [
                'key' => 'site_description',
                'value' => 'منصة وسيلة الخيرية للتبرعات والخدمات',
                'type' => 'text',
                'description' => 'وصف الموقع'
            ],
            [
                'key' => 'contact_email',
                'value' => 'info@wasila.org',
                'type' => 'string',
                'description' => 'البريد الإلكتروني للتواصل'
            ],
            [
                'key' => 'contact_phone',
                'value' => '+966 XX XXX XXXX',
                'type' => 'string',
                'description' => 'رقم الهاتف'
            ],
            [
                'key' => 'address',
                'value' => 'المملكة العربية السعودية',
                'type' => 'text',
                'description' => 'العنوان'
            ],
            [
                'key' => 'logo',
                'value' => 'logo-footer.png',
                'type' => 'string',
                'description' => 'شعار الموقع'
            ],
            [
                'key' => 'myfatoorah_api_key',
                'value' => '',
                'type' => 'string',
                'description' => 'مفتاح API لبوابة الدفع MyFatoorah'
            ],
            [
                'key' => 'myfatoorah_is_test',
                'value' => '1',
                'type' => 'boolean',
                'description' => 'وضع الاختبار لبوابة الدفع'
            ],
            [
                'key' => 'myfatoorah_currency',
                'value' => 'SAR',
                'type' => 'string',
                'description' => 'العملة الافتراضية'
            ]
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}
