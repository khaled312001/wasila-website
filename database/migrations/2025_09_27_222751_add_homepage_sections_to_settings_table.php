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
        Schema::table('settings', function (Blueprint $table) {
            // About Us Section
            $table->text('about_title_ar')->nullable();
            $table->text('about_title_en')->nullable();
            $table->text('about_description_ar')->nullable();
            $table->text('about_description_en')->nullable();
            $table->text('about_mission_ar')->nullable();
            $table->text('about_mission_en')->nullable();
            $table->string('about_image')->nullable();
            
            // Why Choose Us Section
            $table->text('why_choose_title_ar')->nullable();
            $table->text('why_choose_title_en')->nullable();
            $table->text('why_choose_subtitle_ar')->nullable();
            $table->text('why_choose_subtitle_en')->nullable();
            
            // Feature 1
            $table->text('feature1_title_ar')->nullable();
            $table->text('feature1_title_en')->nullable();
            $table->text('feature1_description_ar')->nullable();
            $table->text('feature1_description_en')->nullable();
            $table->string('feature1_icon')->nullable();
            
            // Feature 2
            $table->text('feature2_title_ar')->nullable();
            $table->text('feature2_title_en')->nullable();
            $table->text('feature2_description_ar')->nullable();
            $table->text('feature2_description_en')->nullable();
            $table->string('feature2_icon')->nullable();
            
            // Feature 3
            $table->text('feature3_title_ar')->nullable();
            $table->text('feature3_title_en')->nullable();
            $table->text('feature3_description_ar')->nullable();
            $table->text('feature3_description_en')->nullable();
            $table->string('feature3_icon')->nullable();
            
            // Statistics
            $table->string('stat1_number')->nullable();
            $table->text('stat1_label_ar')->nullable();
            $table->text('stat1_label_en')->nullable();
            $table->string('stat2_number')->nullable();
            $table->text('stat2_label_ar')->nullable();
            $table->text('stat2_label_en')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('settings', function (Blueprint $table) {
            $table->dropColumn([
                'about_title_ar', 'about_title_en', 'about_description_ar', 'about_description_en',
                'about_mission_ar', 'about_mission_en', 'about_image',
                'why_choose_title_ar', 'why_choose_title_en', 'why_choose_subtitle_ar', 'why_choose_subtitle_en',
                'feature1_title_ar', 'feature1_title_en', 'feature1_description_ar', 'feature1_description_en', 'feature1_icon',
                'feature2_title_ar', 'feature2_title_en', 'feature2_description_ar', 'feature2_description_en', 'feature2_icon',
                'feature3_title_ar', 'feature3_title_en', 'feature3_description_ar', 'feature3_description_en', 'feature3_icon',
                'stat1_number', 'stat1_label_ar', 'stat1_label_en',
                'stat2_number', 'stat2_label_ar', 'stat2_label_en'
            ]);
        });
    }
};
