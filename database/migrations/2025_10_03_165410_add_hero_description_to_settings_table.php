<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // إضافة إعدادات وصف الهيرو
        $heroDescriptionSettings = [
            [
                'key' => 'hero_description_ar', 
                'value' => 'نحن نعمل على توزيع المياه ومنتجات العناية بالمساجد وتوزيع وجبات الطعام وكراسي كبار السن وغيرها من الخدمات الإنسانية', 
                'type' => 'text', 
                'description' => 'وصف الهيرو بالعربية'
            ],
            [
                'key' => 'hero_description_en', 
                'value' => 'We work on distributing water and mosque care products, distributing food meals and chairs for the elderly, and other humanitarian services', 
                'type' => 'text', 
                'description' => 'وصف الهيرو بالإنجليزية'
            ],
        ];
        
        foreach ($heroDescriptionSettings as $setting) {
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
        // حذف إعدادات وصف الهيرو
        DB::table('settings')->whereIn('key', ['hero_description_ar', 'hero_description_en'])->delete();
    }
};