@extends('admin.layouts.app')

@section('title', 'إضافة عنصر جديد للمعرض')

@push('styles')
<link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="portfolio-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-8">
                <div class="portfolio-card">
                    <div class="portfolio-header">
                        <h1>➕ إضافة عنصر جديد للمعرض</h1>
                        <p>أضف صورة أو فيديو جديد لعرض أعمالك</p>
                        <div class="mt-3">
                            <a href="{{ route('admin.portfolio.index') }}" class="btn-add-new" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%);">
                                <i class="fas fa-arrow-left"></i> العودة للمعرض
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        @if($errors->any())
                            <div class="alert alert-danger m-4">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>يرجى تصحيح الأخطاء التالية:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.portfolio.store') }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_ar">العنوان (عربي) *</label>
                                    <input type="text" class="form-control" id="title_ar" name="title_ar" 
                                           value="{{ old('title_ar') }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title_en">العنوان (إنجليزي) *</label>
                                    <input type="text" class="form-control" id="title_en" name="title_en" 
                                           value="{{ old('title_en') }}" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_ar">الوصف (عربي)</label>
                                    <textarea class="form-control" id="description_ar" name="description_ar" rows="4">{{ old('description_ar') }}</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="description_en">الوصف (إنجليزي)</label>
                                    <textarea class="form-control" id="description_en" name="description_en" rows="4">{{ old('description_en') }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="type">نوع المحتوى *</label>
                                    <select class="form-control" id="type" name="type" required onchange="toggleFileInput()">
                                        <option value="">اختر النوع</option>
                                        <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>صورة</option>
                                        <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>فيديو</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="sort_order">ترتيب العرض</label>
                                    <input type="number" class="form-control" id="sort_order" name="sort_order" 
                                           value="{{ old('sort_order', 0) }}" min="0">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="file">الملف *</label>
                            <input type="file" class="form-control-file" id="file" name="file" required>
                            <small class="form-text text-muted" id="file-help">
                                اختر نوع المحتوى أولاً
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_active" name="is_active" 
                                       {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    نشط
                                </label>
                            </div>
                        </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-plus mr-2"></i>إضافة العنصر
                                </button>
                                <a href="{{ route('admin.portfolio.index') }}" class="btn-add-new" style="background: linear-gradient(135deg, #6c757d 0%, #495057 100%); margin-right: 1rem;">
                                    <i class="fas fa-times mr-1"></i>إلغاء
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleFileInput() {
    const type = document.getElementById('type').value;
    const fileInput = document.getElementById('file');
    const fileHelp = document.getElementById('file-help');
    
    if (type === 'image') {
        fileInput.accept = 'image/*';
        fileHelp.textContent = 'الصور المسموحة: JPG, PNG, GIF, SVG (حد أقصى 2MB)';
    } else if (type === 'video') {
        fileInput.accept = 'video/*';
        fileHelp.textContent = 'الفيديوهات المسموحة: MP4, AVI, MOV, WMV (حد أقصى 50MB)';
    } else {
        fileInput.accept = '';
        fileHelp.textContent = 'اختر نوع المحتوى أولاً';
    }
}
</script>
@endsection
