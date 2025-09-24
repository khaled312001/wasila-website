@extends('admin.layouts.app')

@section('title', 'إضافة خدمة جديدة')
@section('page-title', 'إضافة خدمة جديدة')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg card-shadow p-6">
        <h2 class="text-2xl font-bold text-primary-dark mb-6">إضافة خدمة جديدة</h2>
        
        <form method="POST" action="{{ route('admin.services.store') }}" enctype="multipart/form-data">
            @csrf
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Arabic Name -->
                <div>
                    <label for="name_ar" class="block text-sm font-medium text-gray-700 mb-2">الاسم بالعربية <span class="text-red-500">*</span></label>
                    <input type="text" id="name_ar" name="name_ar" value="{{ old('name_ar') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('name_ar') border-red-500 @enderror">
                    @error('name_ar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- English Name -->
                <div>
                    <label for="name_en" class="block text-sm font-medium text-gray-700 mb-2">الاسم بالإنجليزية <span class="text-red-500">*</span></label>
                    <input type="text" id="name_en" name="name_en" value="{{ old('name_en') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('name_en') border-red-500 @enderror">
                    @error('name_en')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Arabic Description -->
                <div class="md:col-span-2">
                    <label for="description_ar" class="block text-sm font-medium text-gray-700 mb-2">الوصف بالعربية <span class="text-red-500">*</span></label>
                    <textarea id="description_ar" name="description_ar" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('description_ar') border-red-500 @enderror">{{ old('description_ar') }}</textarea>
                    @error('description_ar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- English Description -->
                <div class="md:col-span-2">
                    <label for="description_en" class="block text-sm font-medium text-gray-700 mb-2">الوصف بالإنجليزية <span class="text-red-500">*</span></label>
                    <textarea id="description_en" name="description_en" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('description_en') border-red-500 @enderror">{{ old('description_en') }}</textarea>
                    @error('description_en')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Price -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">السعر (ريال) <span class="text-red-500">*</span></label>
                    <input type="number" id="price" name="price" step="0.01" min="0" value="{{ old('price') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('price') border-red-500 @enderror">
                    @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Sort Order -->
                <div>
                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">ترتيب العرض</label>
                    <input type="number" id="sort_order" name="sort_order" min="0" value="{{ old('sort_order', 0) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('sort_order') border-red-500 @enderror">
                    @error('sort_order')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Arabic Category -->
                <div>
                    <label for="category_ar" class="block text-sm font-medium text-gray-700 mb-2">الفئة بالعربية <span class="text-red-500">*</span></label>
                    <input type="text" id="category_ar" name="category_ar" value="{{ old('category_ar') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('category_ar') border-red-500 @enderror">
                    @error('category_ar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- English Category -->
                <div>
                    <label for="category_en" class="block text-sm font-medium text-gray-700 mb-2">الفئة بالإنجليزية <span class="text-red-500">*</span></label>
                    <input type="text" id="category_en" name="category_en" value="{{ old('category_en') }}" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('category_en') border-red-500 @enderror">
                    @error('category_en')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image -->
                <div class="md:col-span-2">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">صورة الخدمة</label>
                    <input type="file" id="image" name="image" accept="image/*"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-medium focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Status -->
                <div class="md:col-span-2">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }}
                               class="rounded border-gray-300 text-primary-medium focus:ring-primary-medium">
                        <span class="mr-2 text-sm text-gray-600">نشط</span>
                    </label>
                </div>
            </div>
            
            <div class="flex justify-end space-x-4 space-x-reverse mt-6">
                <a href="{{ route('admin.services.index') }}" 
                   class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300">
                    إلغاء
                </a>
                <button type="submit" class="btn-primary text-white px-6 py-2 rounded-lg font-semibold">
                    حفظ الخدمة
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
