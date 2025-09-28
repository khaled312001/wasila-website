<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PortfolioItem;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
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
                'file' => 'required|mimes:mp4,avi,mov,wmv|max:50000'
            ]);
        } else {
            $validator = Validator::make(['file' => $file], [
                'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);
        }

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // رفع الملف
        $path = $file->store('public/portfolio');
        $url = Storage::url($path);

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
            'file_path' => $url,
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
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean'
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
                    'file' => 'required|mimes:mp4,avi,mov,wmv|max:50000'
                ]);
            } else {
                $validator = Validator::make(['file' => $file], [
                    'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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

                // رفع الملف الجديد
                $path = $file->store('public/portfolio');
                $data['file_path'] = Storage::url($path);

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
}
