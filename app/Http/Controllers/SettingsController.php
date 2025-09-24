<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use App\Models\Setting;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => Setting::get('site_name', config('app.name', 'وسيلة')),
            'site_description' => Setting::get('site_description', 'منصة وسيلة الخيرية للتبرعات والخدمات'),
            'contact_email' => Setting::get('contact_email', 'info@wasila.org'),
            'contact_phone' => Setting::get('contact_phone', '+966 XX XXX XXXX'),
            'address' => Setting::get('address', 'المملكة العربية السعودية'),
            'logo' => Setting::get('logo', 'logo-footer.png'),
            'myfatoorah_api_key' => Setting::get('myfatoorah_api_key', config('myfatoorah.api_key')),
            'myfatoorah_is_test' => Setting::get('myfatoorah_is_test', config('myfatoorah.is_test')),
            'myfatoorah_currency' => Setting::get('myfatoorah_currency', config('myfatoorah.currency', 'SAR')),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'required|string|max:500',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update general settings in database
        Setting::set('site_name', $request->site_name, 'string', 'اسم الموقع');
        Setting::set('site_description', $request->site_description, 'text', 'وصف الموقع');
        Setting::set('contact_email', $request->contact_email, 'string', 'البريد الإلكتروني للتواصل');
        Setting::set('contact_phone', $request->contact_phone, 'string', 'رقم الهاتف');
        Setting::set('address', $request->address, 'text', 'العنوان');

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
            $logoName = 'logo-' . time() . '.' . $logo->getClientOriginalExtension();
            $logo->storeAs('public/images', $logoName);
            Setting::set('logo', $logoName, 'string', 'شعار الموقع');
        }

        // Clear settings cache
        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث الإعدادات بنجاح');
    }

    public function updateMyFatoorah(Request $request)
    {
        $request->validate([
            'myfatoorah_api_key' => 'required|string',
            'myfatoorah_currency' => 'required|string|in:SAR,KWD,AED,EGP,BHD,OMR,JOD,QAR',
            'myfatoorah_is_test' => 'boolean',
        ]);

        // Update MyFatoorah settings in database
        Setting::set('myfatoorah_api_key', $request->myfatoorah_api_key, 'string', 'مفتاح API لبوابة الدفع MyFatoorah');
        Setting::set('myfatoorah_currency', $request->myfatoorah_currency, 'string', 'العملة الافتراضية');
        Setting::set('myfatoorah_is_test', $request->has('myfatoorah_is_test') ? '1' : '0', 'boolean', 'وضع الاختبار لبوابة الدفع');

        // Clear settings cache
        Setting::clearCache();

        return redirect()->route('admin.settings.index')
            ->with('success', 'تم تحديث إعدادات MyFatoorah بنجاح');
    }

    public function backup()
    {
        try {
            // Create database backup
            Artisan::call('backup:run');
            
            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء نسخة احتياطية بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في إنشاء النسخة الاحتياطية: ' . $e->getMessage()
            ]);
        }
    }

    public function clearCache()
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            
            return response()->json([
                'success' => true,
                'message' => 'تم مسح الذاكرة المؤقتة بنجاح'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'فشل في مسح الذاكرة المؤقتة: ' . $e->getMessage()
            ]);
        }
    }

    public function systemInfo()
    {
        $info = [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'غير محدد',
            'database_driver' => config('database.default'),
            'cache_driver' => config('cache.default'),
            'queue_driver' => config('queue.default'),
            'disk_space' => $this->getDiskSpace(),
            'memory_usage' => $this->getMemoryUsage(),
        ];

        return response()->json($info);
    }

    private function getDiskSpace()
    {
        $bytes = disk_free_space(storage_path());
        $totalBytes = disk_total_space(storage_path());
        
        return [
            'free' => $this->formatBytes($bytes),
            'total' => $this->formatBytes($totalBytes),
            'used' => $this->formatBytes($totalBytes - $bytes),
            'percentage' => round((($totalBytes - $bytes) / $totalBytes) * 100, 2)
        ];
    }

    private function getMemoryUsage()
    {
        $memoryUsage = memory_get_usage(true);
        $memoryLimit = ini_get('memory_limit');
        
        return [
            'current' => $this->formatBytes($memoryUsage),
            'limit' => $memoryLimit,
            'percentage' => round(($memoryUsage / $this->parseSize($memoryLimit)) * 100, 2)
        ];
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    private function parseSize($size)
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size);
        $size = preg_replace('/[^0-9\.]/', '', $size);
        
        if ($unit) {
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        }
        
        return round($size);
    }
}
