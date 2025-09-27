<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعدادات إدارة المحتوى
        $contentSettings = [
            // إعدادات الهيرو
            ['key' => 'hero_title_ar', 'value' => 'مرحباً بكم في وسيلة الخير', 'type' => 'text', 'description' => 'عنوان الهيرو بالعربية'],
            ['key' => 'hero_title_en', 'value' => 'Welcome to Wasila Charity', 'type' => 'text', 'description' => 'عنوان الهيرو بالإنجليزية'],
            ['key' => 'hero_subtitle_ar', 'value' => 'نحن نساعد في ربط المحتاجين بالخيرين', 'type' => 'text', 'description' => 'العنوان الفرعي للهيرو بالعربية'],
            ['key' => 'hero_subtitle_en', 'value' => 'We help connect those in need with donors', 'type' => 'text', 'description' => 'العنوان الفرعي للهيرو بالإنجليزية'],
            ['key' => 'hero_image', 'value' => '', 'type' => 'image', 'description' => 'صورة الهيرو'],
            ['key' => 'hero_video', 'value' => '', 'type' => 'video', 'description' => 'فيديو الهيرو'],
            
            // إعدادات اللوجو
            ['key' => 'logo_ar', 'value' => '', 'type' => 'image', 'description' => 'اللوجو بالعربية'],
            ['key' => 'logo_en', 'value' => '', 'type' => 'image', 'description' => 'اللوجو بالإنجليزية'],
            ['key' => 'logo_footer', 'value' => '', 'type' => 'image', 'description' => 'لوجو الفوتر'],
            
            // إعدادات المحتوى العام
            ['key' => 'site_title_ar', 'value' => 'وسيلة الخير', 'type' => 'text', 'description' => 'عنوان الموقع بالعربية'],
            ['key' => 'site_title_en', 'value' => 'Wasila Charity', 'type' => 'text', 'description' => 'عنوان الموقع بالإنجليزية'],
            ['key' => 'site_description_ar', 'value' => 'منصة خيرية لربط المحتاجين بالخيرين', 'type' => 'text', 'description' => 'وصف الموقع بالعربية'],
            ['key' => 'site_description_en', 'value' => 'Charity platform connecting those in need with donors', 'type' => 'text', 'description' => 'وصف الموقع بالإنجليزية'],
            
            // إعدادات التواصل
            ['key' => 'contact_email', 'value' => 'info@wasila-charity.com', 'type' => 'email', 'description' => 'البريد الإلكتروني للتواصل'],
            ['key' => 'contact_phone', 'value' => '+966501234567', 'type' => 'text', 'description' => 'رقم الهاتف للتواصل'],
            ['key' => 'contact_address_ar', 'value' => 'الرياض، المملكة العربية السعودية', 'type' => 'text', 'description' => 'العنوان بالعربية'],
            ['key' => 'contact_address_en', 'value' => 'Riyadh, Saudi Arabia', 'type' => 'text', 'description' => 'العنوان بالإنجليزية'],
            
            // إعدادات وسائل التواصل الاجتماعي
            ['key' => 'social_facebook', 'value' => '', 'type' => 'url', 'description' => 'رابط فيسبوك'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'url', 'description' => 'رابط تويتر'],
            ['key' => 'social_instagram', 'value' => '', 'type' => 'url', 'description' => 'رابط إنستغرام'],
            ['key' => 'social_linkedin', 'value' => '', 'type' => 'url', 'description' => 'رابط لينكد إن'],
            ['key' => 'social_youtube', 'value' => '', 'type' => 'url', 'description' => 'رابط يوتيوب'],
        ];
        
        foreach ($contentSettings as $setting) {
            DB::table('settings')->updateOrInsert(
                ['key' => $setting['key']],
                $setting
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // حذف إعدادات إدارة المحتوى
        $contentSettings = [
            'hero_title_ar', 'hero_title_en', 'hero_subtitle_ar', 'hero_subtitle_en',
            'hero_image', 'hero_video', 'logo_ar', 'logo_en', 'logo_footer',
            'site_title_ar', 'site_title_en', 'site_description_ar', 'site_description_en',
            'contact_email', 'contact_phone', 'contact_address_ar', 'contact_address_en',
            'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin', 'social_youtube'
        ];
        
        DB::table('settings')->whereIn('key', $contentSettings)->delete();
    }
};
