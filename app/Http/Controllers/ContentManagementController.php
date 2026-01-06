<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ContentManagementController extends Controller
{
    /**
     * عرض صفحة إدارة المحتوى
     */
    public function index()
    {
        $settings = Setting::getAll();
        return view('admin.content-management.index', compact('settings'));
    }

    /**
     * تحديث إعدادات المحتوى
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'hero_title_ar' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_subtitle_ar' => 'required|string|max:500',
            'hero_subtitle_en' => 'required|string|max:500',
            'hero_description_ar' => 'required|string|max:1000',
            'hero_description_en' => 'required|string|max:1000',
            'site_title_ar' => 'required|string|max:255',
            'site_title_en' => 'required|string|max:255',
            'site_description_ar' => 'required|string|max:500',
            'site_description_en' => 'required|string|max:500',
            'contact_email' => 'required|email',
            'contact_phone' => 'required|string',
            'contact_address_ar' => 'required|string',
            'contact_address_en' => 'required|string',
            'whatsapp_link' => 'nullable|url',
            
            // About Us Section
            'about_title_ar' => 'nullable|string|max:255',
            'about_title_en' => 'nullable|string|max:255',
            'about_description_ar' => 'nullable|string',
            'about_description_en' => 'nullable|string',
            'about_mission_ar' => 'nullable|string',
            'about_mission_en' => 'nullable|string',
            
            // Why Choose Us Section
            'why_choose_title_ar' => 'nullable|string|max:255',
            'why_choose_title_en' => 'nullable|string|max:255',
            'why_choose_subtitle_ar' => 'nullable|string',
            'why_choose_subtitle_en' => 'nullable|string',
            
            // Features
            'feature1_title_ar' => 'nullable|string|max:255',
            'feature1_title_en' => 'nullable|string|max:255',
            'feature1_description_ar' => 'nullable|string',
            'feature1_description_en' => 'nullable|string',
            'feature2_title_ar' => 'nullable|string|max:255',
            'feature2_title_en' => 'nullable|string|max:255',
            'feature2_description_ar' => 'nullable|string',
            'feature2_description_en' => 'nullable|string',
            'feature3_title_ar' => 'nullable|string|max:255',
            'feature3_title_en' => 'nullable|string|max:255',
            'feature3_description_ar' => 'nullable|string',
            'feature3_description_en' => 'nullable|string',
            
            // Statistics
            'stat1_number' => 'nullable|string|max:50',
            'stat1_label_ar' => 'nullable|string|max:255',
            'stat1_label_en' => 'nullable|string|max:255',
            'stat2_number' => 'nullable|string|max:50',
            'stat2_label_ar' => 'nullable|string|max:255',
            'stat2_label_en' => 'nullable|string|max:255',
            
            // Services Section
            'services_title_ar' => 'nullable|string|max:255',
            'services_title_en' => 'nullable|string|max:255',
            'services_description_ar' => 'nullable|string',
            'services_description_en' => 'nullable|string',
            'services_subtitle_ar' => 'nullable|string|max:255',
            'services_subtitle_en' => 'nullable|string|max:255',
            'premium_badge_text_ar' => 'nullable|string|max:100',
            'premium_badge_text_en' => 'nullable|string|max:100',
            'order_now_button_text_ar' => 'nullable|string|max:100',
            'order_now_button_text_en' => 'nullable|string|max:100',
            'discover_more_services_title_ar' => 'nullable|string|max:255',
            'discover_more_services_title_en' => 'nullable|string|max:255',
            'discover_more_services_description_ar' => 'nullable|string',
            'discover_more_services_description_en' => 'nullable|string',
            'view_all_services_button_text_ar' => 'nullable|string|max:100',
            'view_all_services_button_text_en' => 'nullable|string|max:100',
            'services_coming_soon_title_ar' => 'nullable|string|max:255',
            'services_coming_soon_title_en' => 'nullable|string|max:255',
            'services_coming_soon_description_ar' => 'nullable|string',
            'services_coming_soon_description_en' => 'nullable|string',
            'saudi_riyal_text_ar' => 'nullable|string|max:50',
            'saudi_riyal_text_en' => 'nullable|string|max:50',
            
            // Contact Section
            'contact_us_title_ar' => 'nullable|string|max:255',
            'contact_us_title_en' => 'nullable|string|max:255',
            'contact_us_description_ar' => 'nullable|string',
            'contact_us_description_en' => 'nullable|string',
            'contact_information_title_ar' => 'nullable|string|max:255',
            'contact_information_title_en' => 'nullable|string|max:255',
            'send_us_message_title_ar' => 'nullable|string|max:255',
            'send_us_message_title_en' => 'nullable|string|max:255',
            'working_hours_ar' => 'nullable|string|max:255',
            'working_hours_en' => 'nullable|string|max:255',
            'our_location_title_ar' => 'nullable|string|max:255',
            'our_location_title_en' => 'nullable|string|max:255',
            'our_location_description_ar' => 'nullable|string|max:255',
            'our_location_description_en' => 'nullable|string|max:255',
            
            // Hero Section Buttons
            'browse_services_button_text_ar' => 'nullable|string|max:100',
            'browse_services_button_text_en' => 'nullable|string|max:100',
            'learn_more_button_text_ar' => 'nullable|string|max:100',
            'learn_more_button_text_en' => 'nullable|string|max:100',
            'our_work_button_text_ar' => 'nullable|string|max:100',
            'our_work_button_text_en' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث النصوص
        $textFields = [
            'hero_title_ar', 'hero_title_en', 'hero_subtitle_ar', 'hero_subtitle_en',
            'hero_description_ar', 'hero_description_en',
            'site_title_ar', 'site_title_en', 'site_description_ar', 'site_description_en',
            'contact_email', 'contact_phone', 'contact_address_ar', 'contact_address_en',
            'whatsapp_link', 'social_facebook', 'social_twitter', 'social_instagram', 'social_linkedin', 'social_youtube',
            
            // About Us Section
            'about_title_ar', 'about_title_en', 'about_description_ar', 'about_description_en',
            'about_mission_ar', 'about_mission_en',
            
            // Why Choose Us Section
            'why_choose_title_ar', 'why_choose_title_en', 'why_choose_subtitle_ar', 'why_choose_subtitle_en',
            
            // Features
            'feature1_title_ar', 'feature1_title_en', 'feature1_description_ar', 'feature1_description_en',
            'feature2_title_ar', 'feature2_title_en', 'feature2_description_ar', 'feature2_description_en',
            'feature3_title_ar', 'feature3_title_en', 'feature3_description_ar', 'feature3_description_en',
            
            // Statistics
            'stat1_number', 'stat1_label_ar', 'stat1_label_en',
            'stat2_number', 'stat2_label_ar', 'stat2_label_en',
            
            // Services Section
            'services_title_ar', 'services_title_en', 'services_description_ar', 'services_description_en',
            'services_subtitle_ar', 'services_subtitle_en',
            'premium_badge_text_ar', 'premium_badge_text_en',
            'order_now_button_text_ar', 'order_now_button_text_en',
            'discover_more_services_title_ar', 'discover_more_services_title_en',
            'discover_more_services_description_ar', 'discover_more_services_description_en',
            'view_all_services_button_text_ar', 'view_all_services_button_text_en',
            'services_coming_soon_title_ar', 'services_coming_soon_title_en',
            'services_coming_soon_description_ar', 'services_coming_soon_description_en',
            'saudi_riyal_text_ar', 'saudi_riyal_text_en',
            
            // Contact Section
            'contact_us_title_ar', 'contact_us_title_en',
            'contact_us_description_ar', 'contact_us_description_en',
            'contact_information_title_ar', 'contact_information_title_en',
            'send_us_message_title_ar', 'send_us_message_title_en',
            'working_hours_ar', 'working_hours_en',
            'our_location_title_ar', 'our_location_title_en',
            'our_location_description_ar', 'our_location_description_en',
            
            // Hero Section Buttons
            'browse_services_button_text_ar', 'browse_services_button_text_en',
            'learn_more_button_text_ar', 'learn_more_button_text_en',
            'our_work_button_text_ar', 'our_work_button_text_en'
        ];

        foreach ($textFields as $field) {
            if ($request->has($field)) {
                Setting::set($field, $request->input($field));
            }
        }

        // معالجة الملفات
        $this->handleFileUploads($request);

        return redirect()->route('admin.content-management.index')
            ->with('success', 'تم تحديث المحتوى بنجاح');
    }

    /**
     * معالجة رفع الملفات
     */
    private function handleFileUploads(Request $request)
    {
        $fileFields = [
            'hero_image' => 'hero',
            'hero_video' => 'hero',
            'logo_ar' => 'logos',
            'logo_en' => 'logos',
            'logo_footer' => 'logos',
            'about_image' => 'about'
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $file = $request->file($field);
                
                // التحقق من نوع الملف
                if ($field === 'hero_video') {
                    $validator = Validator::make(['file' => $file], [
                        'file' => 'required|mimes:mp4,avi,mov,wmv|max:50000'
                    ]);
                } else {
                    $validator = Validator::make(['file' => $file], [
                        'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
                    ]);
                }

                if (!$validator->fails()) {
                    $path = $file->store("public/{$folder}");
                    $url = Storage::url($path);
                    Setting::set($field, $url);
                }
            }
        }
    }

    /**
     * حذف ملف
     */
    public function deleteFile(Request $request)
    {
        $field = $request->input('field');
        $currentValue = Setting::get($field);
        
        if ($currentValue && Storage::exists(str_replace('/storage/', 'public/', $currentValue))) {
            Storage::delete(str_replace('/storage/', 'public/', $currentValue));
        }
        
        Setting::set($field, '');
        
        return response()->json(['success' => true]);
    }
}
