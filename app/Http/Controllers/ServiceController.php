<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        
        $data = $request->all();
        
        if ($request->hasFile('image')) {
            // Delete old image if it exists
            if ($service->image && \Storage::disk('public')->exists($service->image)) {
                \Storage::disk('public')->delete($service->image);
            }
            $data['image'] = $request->file('image')->store('services', 'public');
        }
        
        // Set default values
        $data['is_active'] = $request->has('is_active') ? true : false;
        $data['sort_order'] = $data['sort_order'] ?? 0;
        
        $service->update($data);
        
        return redirect()->route('admin.services.index')
            ->with('success', 'تم تحديث الخدمة بنجاح.');
    }
    
    public function destroy(Service $service)
    {
        // Delete the image file if it exists
        if ($service->image && \Storage::disk('public')->exists($service->image)) {
            \Storage::disk('public')->delete($service->image);
        }
        
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'تم حذف الخدمة بنجاح.');
    }
}
