<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use App\Models\Service;

class ServiceController extends Controller
{
    // Public methods
    public function publicIndex()
    {
        $services = Service::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category');
            
        return view('services.index', compact('services'));
    }
    
    public function show(Service $service)
    {
        return view('services.show', compact('service'));
    }
    
    // Admin methods
    public function index()
    {
        $services = Service::orderBy('sort_order')->get();
        return view('admin.services.index', compact('services'));
    }
    
    public function create()
    {
        return view('admin.services.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_ar' => 'required|string|max:255',
            'category_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'sort_order' => 'nullable|integer|min:0'
        ]);
        
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('services', 'public');
            
            // Copy file to public/storage for hosting providers that don't support symlinks
            $copied = $this->copyToPublicStorage($data['image']);
            if (!$copied) {
                Log::warning('Failed to copy service image to public storage, but continuing: ' . $data['image']);
            }
        }
        
        // Set default values
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        Service::create($data);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'تم إنشاء الخدمة بنجاح.');
    }
    
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }
    
    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'description_ar' => 'required|string',
            'description_en' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_ar' => 'required|string|max:255',
            'category_en' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:30720',
            'sort_order' => 'nullable|integer|min:0'
        ]);
        
        $data = [
            'name_ar' => $request->input('name_ar'),
            'name_en' => $request->input('name_en'),
            'description_ar' => $request->input('description_ar'),
            'description_en' => $request->input('description_en'),
            'price' => $request->input('price'),
            'category_ar' => $request->input('category_ar'),
            'category_en' => $request->input('category_en'),
            'is_active' => $request->has('is_active'),
            'sort_order' => $request->input('sort_order', 0)
        ];
        
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
                // Also delete from public/storage
                $this->deleteFromPublicStorage($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
            
            // Copy file to public/storage for hosting providers that don't support symlinks
            $copied = $this->copyToPublicStorage($data['image']);
            if (!$copied) {
                Log::warning('Failed to copy service image to public storage, but continuing: ' . $data['image']);
            }
        }
        
        $service->update($data);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح.');
    }
    
    public function destroy(Service $service)
    {
        // Delete the image file if it exists
        if ($service->image && Storage::disk('public')->exists($service->image)) {
            Storage::disk('public')->delete($service->image);
            // Also delete from public/storage
            $this->deleteFromPublicStorage($service->image);
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح.');
    }
    
    /**
     * Copy file to public/storage directory for hosting providers that don't support symlinks
     */
    private function copyToPublicStorage($filePath)
    {
        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $targetPath = public_path('storage/' . $filePath);
            
            // Check if source file exists
            if (!file_exists($sourcePath)) {
                Log::warning('Source file does not exist: ' . $sourcePath);
                return false;
            }
            
            // Create directory if it doesn't exist
            $targetDir = dirname($targetPath);
            if (!is_dir($targetDir)) {
                if (!mkdir($targetDir, 0755, true)) {
                    Log::error('Failed to create directory: ' . $targetDir);
                    return false;
                }
            }
            
            // Copy file
            if (!copy($sourcePath, $targetPath)) {
                Log::error('Failed to copy file from ' . $sourcePath . ' to ' . $targetPath);
                return false;
            }
            
            // Set permissions
            chmod($targetPath, 0644);
            
            // Verify the copy was successful
            if (!file_exists($targetPath)) {
                Log::error('File copy verification failed: ' . $targetPath);
                return false;
            }
            
            Log::info('Successfully copied file to public storage: ' . $filePath);
            return true;
        } catch (\Exception $e) {
            Log::error('Exception in copyToPublicStorage: ' . $e->getMessage() . ' | File: ' . $filePath);
            return false;
        }
    }
    
    /**
     * Delete file from public/storage directory
     */
    private function deleteFromPublicStorage($filePath)
    {
        try {
            $targetPath = public_path('storage/' . $filePath);
            if (file_exists($targetPath)) {
                unlink($targetPath);
                Log::info('Deleted file from public storage: ' . $filePath);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to delete file from public storage: ' . $e->getMessage());
        }
    }
    
    /**
     * Copy all existing service images to public/storage
     * This is useful for fixing images after deployment or when symlinks don't work
     */
    public function syncAllImages(Request $request)
    {
        $services = Service::whereNotNull('image')->get();
        $successCount = 0;
        $failCount = 0;
        
        foreach ($services as $service) {
            if ($this->copyToPublicStorage($service->image)) {
                $successCount++;
            } else {
                $failCount++;
                Log::warning('Failed to sync image for service ID: ' . $service->id . ' - Image: ' . $service->image);
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
        
        return redirect()->route('admin.services.index')
            ->with('success', $message);
    }
}
