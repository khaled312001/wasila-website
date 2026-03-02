<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Setting;
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
            // Hero Section
            'hero_title_ar' => 'required|string|max:255',
            'hero_title_en' => 'required|string|max:255',
            'hero_description_ar' => 'required|string|max:1000',
            'hero_description_en' => 'required|string|max:1000',
            'hero_video' => 'nullable|file|mimes:mp4,avi,mov,wmv|max:102400',

            // Contact Info
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string',
            'whatsapp_link' => 'nullable|url',

            // About Section
            'about_title_ar' => 'nullable|string|max:255',
            'about_title_en' => 'nullable|string|max:255',
            'about_description_ar' => 'nullable|string',
            'about_description_en' => 'nullable|string',
            'about_mission_ar' => 'nullable|string',
            'about_mission_en' => 'nullable|string',

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

            // Services Section
            'services_title_ar' => 'nullable|string|max:255',
            'services_title_en' => 'nullable|string|max:255',
            'services_description_ar' => 'nullable|string',
            'services_description_en' => 'nullable|string',
            'order_now_button_text_ar' => 'nullable|string|max:100',
            'order_now_button_text_en' => 'nullable|string|max:100',
            'saudi_riyal_text_ar' => 'nullable|string|max:50',
            'saudi_riyal_text_en' => 'nullable|string|max:50',

            // Hero Buttons
            'browse_services_button_text_ar' => 'nullable|string|max:100',
            'browse_services_button_text_en' => 'nullable|string|max:100',
            'learn_more_button_text_ar' => 'nullable|string|max:100',
            'learn_more_button_text_en' => 'nullable|string|max:100',

            // Contact Section Texts
            'contact_us_title_ar' => 'nullable|string|max:255',
            'contact_us_title_en' => 'nullable|string|max:255',
            'contact_us_description_ar' => 'nullable|string',
            'contact_us_description_en' => 'nullable|string',
            'contact_information_title_ar' => 'nullable|string|max:255',
            'contact_information_title_en' => 'nullable|string|max:255',
            'send_us_message_title_ar' => 'nullable|string|max:255',
            'send_us_message_title_en' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // تحديث النصوص
        $textFields = [
            // Hero Section
            'hero_title_ar', 'hero_title_en',
            'hero_description_ar', 'hero_description_en',

            // Contact Info
            'contact_email', 'contact_phone', 'whatsapp_link',

            // About Section
            'about_title_ar', 'about_title_en',
            'about_description_ar', 'about_description_en',
            'about_mission_ar', 'about_mission_en',

            // Features
            'feature1_title_ar', 'feature1_title_en', 'feature1_description_ar', 'feature1_description_en',
            'feature2_title_ar', 'feature2_title_en', 'feature2_description_ar', 'feature2_description_en',
            'feature3_title_ar', 'feature3_title_en', 'feature3_description_ar', 'feature3_description_en',

            // Services Section
            'services_title_ar', 'services_title_en',
            'services_description_ar', 'services_description_en',
            'order_now_button_text_ar', 'order_now_button_text_en',
            'saudi_riyal_text_ar', 'saudi_riyal_text_en',

            // Hero Buttons
            'browse_services_button_text_ar', 'browse_services_button_text_en',
            'learn_more_button_text_ar', 'learn_more_button_text_en',

            // Contact Section Texts
            'contact_us_title_ar', 'contact_us_title_en',
            'contact_us_description_ar', 'contact_us_description_en',
            'contact_information_title_ar', 'contact_information_title_en',
            'send_us_message_title_ar', 'send_us_message_title_en',
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
        if ($request->hasFile('hero_video')) {
            $file = $request->file('hero_video');
            $filename = 'hero-video.' . $file->getClientOriginalExtension();
            $file->move(public_path('videos'), $filename);
            Setting::set('hero_video', asset('videos/' . $filename));
        }
    }

    /**
     * حذف الفيديو
     */
    public function deleteFile(Request $request)
    {
        $field = $request->input('field');

        if ($field === 'hero_video') {
            $videoPath = public_path('videos/hero-video.mp4');
            // Don't delete the actual file, just clear the setting so the default is used
        }

        Setting::set($field, '');

        return response()->json(['success' => true]);
    }
}
