<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class PortfolioController extends Controller
{
    /**
     * عرض معرض الأعمال للزوار
     */
    public function index()
    {
        $portfolioItems = PortfolioItem::active()->ordered()->get();
        return view('portfolio.index', compact('portfolioItems'));
    }

    /**
     * عرض إدارة معرض الأعمال في لوحة التحكم
     */
    public function adminIndex()
    {
        $portfolioItems = PortfolioItem::ordered()->get();
        return view('admin.portfolio.index', compact('portfolioItems'));
    }

    /**
     * عرض نموذج إنشاء عنصر جديد
     */
    public function create()
    {
        return view('admin.portfolio.create');
    }

    /**
     * حفظ عنصر جديد
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type' => 'required|in:image,video',
            'file' => 'required|file',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $file = $request->file('file');
        $type = $request->input('type');
        
        // التحقق من نوع الملف
        if ($type === 'video') {
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|mimes:mp4,avi,mov,wmv,webm|max:51200' // 50MB
            ]);
        } else {
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240' // 10MB
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // رفع الملف إلى مجلد portfolio
        $path = $file->store('public/portfolio');
        // إزالة 'public/' من المسار للحصول على 'portfolio/filename'
        $relativePath = str_replace('public/', '', $path);
        
        // Copy file to public/storage for hosting providers that don't support symlinks
        $this->copyToPublicStorage($relativePath);

        // إنشاء thumbnail للفيديو
        $thumbnailPath = null;
        if ($type === 'video') {
            $thumbnailPath = $this->generateVideoThumbnail($file);
        }

        PortfolioItem::create([
            'title_ar' => $request->input('title_ar'),
            'title_en' => $request->input('title_en'),
            'description_ar' => $request->input('description_ar'),
            'description_en' => $request->input('description_en'),
            'type' => $type,
            'file_path' => $relativePath,
            'thumbnail_path' => $thumbnailPath,
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => $request->has('is_active')
        ]);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'تم إضافة العنصر بنجاح');
    }

    /**
     * عرض نموذج تعديل عنصر
     */
    public function edit(PortfolioItem $portfolioItem)
    {
        return view('admin.portfolio.edit', compact('portfolioItem'));
    }

    /**
     * تحديث عنصر
     */
    public function update(Request $request, PortfolioItem $portfolioItem)
    {
        $validator = Validator::make($request->all(), [
            'title_ar' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'type' => 'required|in:image,video',
            'file' => 'nullable|file',
            'sort_order' => 'nullable|integer|min:0'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $data = [
            'title_ar' => $request->input('title_ar'),
            'title_en' => $request->input('title_en'),
            'description_ar' => $request->input('description_ar'),
            'description_en' => $request->input('description_en'),
            'type' => $request->input('type'),
            'sort_order' => $request->input('sort_order', 0),
            'is_active' => $request->has('is_active')
        ];

        // إذا تم رفع ملف جديد
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $type = $request->input('type');
            
            // التحقق من نوع الملف
            if ($type === 'video') {
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|mimes:mp4,avi,mov,wmv,webm|max:51200' // 50MB
                ]);
            } else {
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp|max:10240' // 10MB
                ]);
            }

            if (!$validator->fails()) {
                // حذف الملف القديم
                if ($portfolioItem->file_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $portfolioItem->file_path));
                }
                if ($portfolioItem->thumbnail_path) {
                    Storage::delete(str_replace('/storage/', 'public/', $portfolioItem->thumbnail_path));
                }

                // رفع الملف الجديد إلى مجلد portfolio
                $path = $file->store('public/portfolio');
                // إزالة 'public/' من المسار للحصول على 'portfolio/filename'
                $data['file_path'] = str_replace('public/', '', $path);
                
                // Copy file to public/storage for hosting providers that don't support symlinks
                $this->copyToPublicStorage($data['file_path']);

                // إنشاء thumbnail للفيديو
                if ($type === 'video') {
                    $data['thumbnail_path'] = $this->generateVideoThumbnail($file);
                }
            }
        }

        $portfolioItem->update($data);

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'تم تحديث العنصر بنجاح');
    }

    /**
     * حذف عنصر
     */
    public function destroy(PortfolioItem $portfolioItem)
    {
        // حذف الملفات
        if ($portfolioItem->file_path) {
            Storage::delete(str_replace('/storage/', 'public/', $portfolioItem->file_path));
        }
        if ($portfolioItem->thumbnail_path) {
            Storage::delete(str_replace('/storage/', 'public/', $portfolioItem->thumbnail_path));
        }

        $portfolioItem->delete();

        return redirect()->route('admin.portfolio.index')
            ->with('success', 'تم حذف العنصر بنجاح');
    }

    /**
     * إنشاء thumbnail للفيديو
     */
    private function generateVideoThumbnail($videoFile)
    {
        // هذا يتطلب تثبيت FFmpeg
        // للآن سنستخدم صورة افتراضية
        return null;
    }
    
    /**
     * Copy file to public/storage directory for hosting providers that don't support symlinks
     */
    private function copyToPublicStorage($filePath)
    {
        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $targetPath = public_path('storage/' . $filePath);
            
            // Create directory if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0755, true);
                // Set directory permissions
                chmod($targetDir, 0755);
            }
            
            // Copy file if source exists
            if (file_exists($sourcePath)) {
                // Ensure target directory exists
                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0755, true);
                }
                
                copy($sourcePath, $targetPath);
                
                // Set file permissions (readable by web server)
                chmod($targetPath, 0644);
                
                Log::info('File copied to public storage: ' . $filePath);
            } else {
                Log::warning('Source file not found: ' . $sourcePath);
            }
        } catch (\Exception $e) {
            // Log error but don't break the flow
            Log::error('Failed to copy file to public storage: ' . $e->getMessage(), [
                'file_path' => $filePath,
                'source' => $sourcePath ?? 'N/A',
                'target' => $targetPath ?? 'N/A'
            ]);
        }
    }
    
    /**
     * إضافة جميع الصور الموجودة في مجلد portfolio إلى قاعدة البيانات
     */
    public function addAllImagesFromFolder(Request $request)
    {
        try {
            // محاولة عدة مسارات محتملة
            $possibleDirs = [
                storage_path('app/public/portfolio'),
                public_path('storage/portfolio'),
                storage_path('app/public/portfolio'),
            ];
            
            $portfolioDir = null;
            foreach ($possibleDirs as $dir) {
                if (is_dir($dir)) {
                    $portfolioDir = $dir;
                    break;
                }
            }
            
            if (!$portfolioDir || !is_dir($portfolioDir)) {
                throw new \Exception('مجلد portfolio غير موجود في: ' . implode(', ', $possibleDirs));
            }
            
            $addedCount = 0;
            $skippedCount = 0;
            $errors = [];
            
            // الحصول على جميع ملفات الصور من المجلد
            $imageFiles = glob($portfolioDir . '/*.{png,jpg,jpeg,gif,webp}', GLOB_BRACE);
            
            // ترتيب الملفات حسب الرقم في الاسم
            usort($imageFiles, function($a, $b) {
                $aNum = intval(preg_replace('/[^0-9]/', '', basename($a)));
                $bNum = intval(preg_replace('/[^0-9]/', '', basename($b)));
                return $aNum <=> $bNum;
            });
            
            foreach ($imageFiles as $imageFile) {
                $fileName = basename($imageFile);
                
                // تخطي ملفات اللوجو والعينات
                if (strpos($fileName, 'logo') !== false || 
                    strpos($fileName, 'sample') !== false) {
                    continue;
                }
                
                $relativePath = 'portfolio/' . $fileName;
                
                // التحقق من وجود العنصر في قاعدة البيانات
                $existingItem = PortfolioItem::where('file_path', $relativePath)->first();
                
                if ($existingItem) {
                    $skippedCount++;
                    continue;
                }
                
                // استخراج الرقم من اسم الملف
                $fileNumber = intval(preg_replace('/[^0-9]/', '', $fileName));
                
                // إنشاء عنصر جديد
                try {
                    PortfolioItem::create([
                        'title_ar' => 'صورة من أعمالنا ' . $fileNumber,
                        'title_en' => 'Our Work Image ' . $fileNumber,
                        'description_ar' => 'صورة توثيقية من أنشطة ومشاريع وسيلة',
                        'description_en' => 'Documentary image from Wasila activities and projects',
                        'type' => 'image',
                        'file_path' => $relativePath,
                        'sort_order' => $fileNumber,
                        'is_active' => true
                    ]);
                    
                    // نسخ الملف إلى public/storage
                    $this->copyToPublicStorage($relativePath);
                    
                    $addedCount++;
                } catch (\Exception $e) {
                    $errors[] = "خطأ في إضافة {$fileName}: " . $e->getMessage();
                    Log::error('Failed to add portfolio image: ' . $fileName, [
                        'error' => $e->getMessage()
                    ]);
                }
            }
            
            $message = "تم إضافة {$addedCount} صورة بنجاح";
            if ($skippedCount > 0) {
                $message .= " (تم تخطي {$skippedCount} صورة موجودة مسبقاً)";
            }
            if (count($errors) > 0) {
                $message .= " (حدثت " . count($errors) . " أخطاء)";
            }
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'added_count' => $addedCount,
                    'skipped_count' => $skippedCount,
                    'errors' => $errors
                ]);
            }
            
            return redirect()->route('admin.portfolio.index')
                ->with('success', $message)
                ->with('errors', $errors);
                
        } catch (\Exception $e) {
            Log::error('Failed to add portfolio images from folder: ' . $e->getMessage());
            
            $errorMessage = 'حدث خطأ أثناء إضافة الصور: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.portfolio.index')
                ->with('error', $errorMessage);
        }
    }

    /**
     * مزامنة جميع صور الأعمال إلى public/storage
     */
    public function syncAllImages(Request $request)
    {
        try {
            $portfolioItems = PortfolioItem::whereNotNull('file_path')->get();
            $successCount = 0;
            $failCount = 0;
            
            foreach ($portfolioItems as $item) {
                $cleanPath = str_replace('storage/', '', $item->file_path);
                $cleanPath = ltrim($cleanPath, '/');
                
                if ($this->copyToPublicStorage($cleanPath)) {
                    $successCount++;
                } else {
                    $failCount++;
                    Log::warning('Failed to sync image for portfolio ID: ' . $item->id . ' - Image: ' . $item->file_path);
                }

                // Also sync thumbnail if exists
                if ($item->thumbnail_path) {
                    $cleanThumbnail = str_replace('storage/', '', $item->thumbnail_path);
                    $cleanThumbnail = ltrim($cleanThumbnail, '/');
                    $this->copyToPublicStorage($cleanThumbnail);
                }
            }
            
            $message = "تم نسخ {$successCount} صورة بنجاح" . ($failCount > 0 ? " وفشل نسخ {$failCount} صورة" : "");
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => $message,
                    'success_count' => $successCount,
                    'fail_count' => $failCount
                ]);
            }
            
            return redirect()->route('admin.portfolio.index')
                ->with('success', $message);
        } catch (\Exception $e) {
            Log::error('Failed to sync portfolio images: ' . $e->getMessage());
            
            $errorMessage = 'حدث خطأ أثناء مزامنة الصور: ' . $e->getMessage();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage
                ], 500);
            }
            
            return redirect()->route('admin.portfolio.index')
                ->with('error', $errorMessage);
        }
    }
}
