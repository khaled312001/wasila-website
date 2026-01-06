@extends('admin.layouts.app')

@section('title', 'إدارة المحتوى')

@push('styles')
<link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="content-management-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="content-management-card">
                    <div class="content-management-header">
                        <h1>🎨 إدارة محتوى الموقع</h1>
                        <p>قم بتخصيص وتحديث جميع عناصر موقعك من هنا</p>
                    </div>
                    <div class="card-body p-0">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                                <i class="fas fa-check-circle mr-2"></i>
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <form action="{{ route('admin.content-management.update') }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf
                            
                            <!-- Hero Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم الهيرو</h4>
                                </div>
                                <div class="section-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_title_ar">عنوان الهيرو (عربي)</label>
                                            <input type="text" class="form-control" id="hero_title_ar" name="hero_title_ar" 
                                                   value="{{ $settings['hero_title_ar'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_title_en">عنوان الهيرو (إنجليزي)</label>
                                            <input type="text" class="form-control" id="hero_title_en" name="hero_title_en" 
                                                   value="{{ $settings['hero_title_en'] ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_subtitle_ar">العنوان الفرعي (عربي)</label>
                                            <textarea class="form-control" id="hero_subtitle_ar" name="hero_subtitle_ar" rows="3" required>{{ $settings['hero_subtitle_ar'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_subtitle_en">العنوان الفرعي (إنجليزي)</label>
                                            <textarea class="form-control" id="hero_subtitle_en" name="hero_subtitle_en" rows="3" required>{{ $settings['hero_subtitle_en'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_description_ar">وصف الهيرو (عربي)</label>
                                            <textarea class="form-control" id="hero_description_ar" name="hero_description_ar" rows="4" required>{{ $settings['hero_description_ar'] ?? 'نحن نعمل على توزيع المياه ومنتجات العناية بالمساجد وتوزيع وجبات الطعام وكراسي كبار السن وغيرها من الخدمات' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_description_en">وصف الهيرو (إنجليزي)</label>
                                            <textarea class="form-control" id="hero_description_en" name="hero_description_en" rows="4" required>{{ $settings['hero_description_en'] ?? 'We work on distributing water and mosque care products, distributing food meals and chairs for the elderly, and other humanitarian services' }}</textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_image">صورة الهيرو</label>
                                            <input type="file" class="form-control-file" id="hero_image" name="hero_image" accept="image/*">
                                            @if(isset($settings['hero_image']) && $settings['hero_image'])
                                                <div class="file-preview">
                                                    <img src="{{ $settings['hero_image'] }}" alt="Hero Image" style="max-width: 200px; max-height: 150px;">
                                                    <br>
                                                    <button type="button" class="btn-delete-file" onclick="deleteFile('hero_image')">
                                                        <i class="fas fa-trash mr-1"></i>حذف الصورة
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="hero_video">فيديو الهيرو</label>
                                            <input type="file" class="form-control-file" id="hero_video" name="hero_video" accept="video/*">
                                            @if(isset($settings['hero_video']) && $settings['hero_video'])
                                                <div class="file-preview">
                                                    <video controls style="max-width: 200px; max-height: 150px;">
                                                        <source src="{{ $settings['hero_video'] }}" type="video/mp4">
                                                    </video>
                                                    <br>
                                                    <button type="button" class="btn-delete-file" onclick="deleteFile('hero_video')">
                                                        <i class="fas fa-trash mr-1"></i>حذف الفيديو
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Logo Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>اللوجوهات</h4>
                                </div>
                                <div class="section-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo_ar">اللوجو (عربي)</label>
                                            <input type="file" class="form-control-file" id="logo_ar" name="logo_ar" accept="image/*">
                                            @if(isset($settings['logo_ar']) && $settings['logo_ar'])
                                                <div class="mt-2">
                                                    <img src="{{ $settings['logo_ar'] }}" alt="Arabic Logo" style="max-width: 150px; max-height: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger ml-2" onclick="deleteFile('logo_ar')">حذف</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo_en">اللوجو (إنجليزي)</label>
                                            <input type="file" class="form-control-file" id="logo_en" name="logo_en" accept="image/*">
                                            @if(isset($settings['logo_en']) && $settings['logo_en'])
                                                <div class="mt-2">
                                                    <img src="{{ $settings['logo_en'] }}" alt="English Logo" style="max-width: 150px; max-height: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger ml-2" onclick="deleteFile('logo_en')">حذف</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="logo_footer">لوجو الفوتر</label>
                                            <input type="file" class="form-control-file" id="logo_footer" name="logo_footer" accept="image/*">
                                            @if(isset($settings['logo_footer']) && $settings['logo_footer'])
                                                <div class="mt-2">
                                                    <img src="{{ $settings['logo_footer'] }}" alt="Footer Logo" style="max-width: 150px; max-height: 100px;">
                                                    <button type="button" class="btn btn-sm btn-danger ml-2" onclick="deleteFile('logo_footer')">حذف</button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Site Information -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>معلومات الموقع</h4>
                                </div>
                                <div class="section-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_title_ar">عنوان الموقع (عربي)</label>
                                            <input type="text" class="form-control" id="site_title_ar" name="site_title_ar" 
                                                   value="{{ $settings['site_title_ar'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_title_en">عنوان الموقع (إنجليزي)</label>
                                            <input type="text" class="form-control" id="site_title_en" name="site_title_en" 
                                                   value="{{ $settings['site_title_en'] ?? '' }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_description_ar">وصف الموقع (عربي)</label>
                                            <textarea class="form-control" id="site_description_ar" name="site_description_ar" rows="3" required>{{ $settings['site_description_ar'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="site_description_en">وصف الموقع (إنجليزي)</label>
                                            <textarea class="form-control" id="site_description_en" name="site_description_en" rows="3" required>{{ $settings['site_description_en'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- Contact Information -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>معلومات التواصل</h4>
                                </div>
                                <div class="section-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_email">البريد الإلكتروني</label>
                                            <input type="email" class="form-control" id="contact_email" name="contact_email" 
                                                   value="{{ $settings['contact_email'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact_phone">رقم الهاتف</label>
                                            <input type="text" class="form-control" id="contact_phone" name="contact_phone" 
                                                   value="{{ $settings['contact_phone'] ?? '' }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="whatsapp_link">رابط الواتساب</label>
                                            <input type="url" class="form-control" id="whatsapp_link" name="whatsapp_link" 
                                                   value="{{ $settings['whatsapp_link'] ?? '' }}" placeholder="https://wa.me/966559229980">
                                            <small class="form-text text-muted">رابط الواتساب للتواصل المباشر</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_address_ar">العنوان (عربي)</label>
                                            <textarea class="form-control" id="contact_address_ar" name="contact_address_ar" rows="3" required>{{ $settings['contact_address_ar'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact_address_en">العنوان (إنجليزي)</label>
                                            <textarea class="form-control" id="contact_address_en" name="contact_address_en" rows="3" required>{{ $settings['contact_address_en'] ?? '' }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <!-- About Us Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم من نحن</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_title_ar">عنوان القسم (عربي)</label>
                                                <input type="text" class="form-control" id="about_title_ar" name="about_title_ar" 
                                                       value="{{ $settings['about_title_ar'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_title_en">عنوان القسم (إنجليزي)</label>
                                                <input type="text" class="form-control" id="about_title_en" name="about_title_en" 
                                                       value="{{ $settings['about_title_en'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_description_ar">الوصف (عربي)</label>
                                                <textarea class="form-control" id="about_description_ar" name="about_description_ar" rows="4">{{ $settings['about_description_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_description_en">الوصف (إنجليزي)</label>
                                                <textarea class="form-control" id="about_description_en" name="about_description_en" rows="4">{{ $settings['about_description_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_mission_ar">المهمة (عربي)</label>
                                                <textarea class="form-control" id="about_mission_ar" name="about_mission_ar" rows="3">{{ $settings['about_mission_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_mission_en">المهمة (إنجليزي)</label>
                                                <textarea class="form-control" id="about_mission_en" name="about_mission_en" rows="3">{{ $settings['about_mission_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_image">صورة القسم</label>
                                                <input type="file" class="form-control-file" id="about_image" name="about_image" accept="image/*">
                                                @if(isset($settings['about_image']) && $settings['about_image'])
                                                    <div class="file-preview">
                                                        <img src="{{ $settings['about_image'] }}" alt="About Image" style="max-width: 200px; max-height: 150px;">
                                                        <br>
                                                        <button type="button" class="btn-delete-file" onclick="deleteFile('about_image')">
                                                            <i class="fas fa-trash mr-1"></i>حذف الصورة
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Why Choose Us Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم لماذا تختار وسيلة؟</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="why_choose_title_ar">عنوان القسم (عربي)</label>
                                                <input type="text" class="form-control" id="why_choose_title_ar" name="why_choose_title_ar" 
                                                       value="{{ $settings['why_choose_title_ar'] ?? '' }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="why_choose_title_en">عنوان القسم (إنجليزي)</label>
                                                <input type="text" class="form-control" id="why_choose_title_en" name="why_choose_title_en" 
                                                       value="{{ $settings['why_choose_title_en'] ?? '' }}">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="why_choose_subtitle_ar">العنوان الفرعي (عربي)</label>
                                                <textarea class="form-control" id="why_choose_subtitle_ar" name="why_choose_subtitle_ar" rows="3">{{ $settings['why_choose_subtitle_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="why_choose_subtitle_en">العنوان الفرعي (إنجليزي)</label>
                                                <textarea class="form-control" id="why_choose_subtitle_en" name="why_choose_subtitle_en" rows="3">{{ $settings['why_choose_subtitle_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Features Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>المميزات</h4>
                                </div>
                                <div class="section-body">
                                    <!-- Feature 1 -->
                                    <div class="feature-group mb-4 p-3 border rounded">
                                        <h5 class="text-primary mb-3">الميزة الأولى</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature1_title_ar">العنوان (عربي)</label>
                                                    <input type="text" class="form-control" id="feature1_title_ar" name="feature1_title_ar" 
                                                           value="{{ $settings['feature1_title_ar'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature1_title_en">العنوان (إنجليزي)</label>
                                                    <input type="text" class="form-control" id="feature1_title_en" name="feature1_title_en" 
                                                           value="{{ $settings['feature1_title_en'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature1_description_ar">الوصف (عربي)</label>
                                                    <textarea class="form-control" id="feature1_description_ar" name="feature1_description_ar" rows="3">{{ $settings['feature1_description_ar'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature1_description_en">الوصف (إنجليزي)</label>
                                                    <textarea class="form-control" id="feature1_description_en" name="feature1_description_en" rows="3">{{ $settings['feature1_description_en'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Feature 2 -->
                                    <div class="feature-group mb-4 p-3 border rounded">
                                        <h5 class="text-primary mb-3">الميزة الثانية</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature2_title_ar">العنوان (عربي)</label>
                                                    <input type="text" class="form-control" id="feature2_title_ar" name="feature2_title_ar" 
                                                           value="{{ $settings['feature2_title_ar'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature2_title_en">العنوان (إنجليزي)</label>
                                                    <input type="text" class="form-control" id="feature2_title_en" name="feature2_title_en" 
                                                           value="{{ $settings['feature2_title_en'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature2_description_ar">الوصف (عربي)</label>
                                                    <textarea class="form-control" id="feature2_description_ar" name="feature2_description_ar" rows="3">{{ $settings['feature2_description_ar'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature2_description_en">الوصف (إنجليزي)</label>
                                                    <textarea class="form-control" id="feature2_description_en" name="feature2_description_en" rows="3">{{ $settings['feature2_description_en'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Feature 3 -->
                                    <div class="feature-group mb-4 p-3 border rounded">
                                        <h5 class="text-primary mb-3">الميزة الثالثة</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature3_title_ar">العنوان (عربي)</label>
                                                    <input type="text" class="form-control" id="feature3_title_ar" name="feature3_title_ar" 
                                                           value="{{ $settings['feature3_title_ar'] ?? '' }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature3_title_en">العنوان (إنجليزي)</label>
                                                    <input type="text" class="form-control" id="feature3_title_en" name="feature3_title_en" 
                                                           value="{{ $settings['feature3_title_en'] ?? '' }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature3_description_ar">الوصف (عربي)</label>
                                                    <textarea class="form-control" id="feature3_description_ar" name="feature3_description_ar" rows="3">{{ $settings['feature3_description_ar'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="feature3_description_en">الوصف (إنجليزي)</label>
                                                    <textarea class="form-control" id="feature3_description_en" name="feature3_description_en" rows="3">{{ $settings['feature3_description_en'] ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Services Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم الخدمات</h4>
                                    <p class="text-muted small">يمكنك تعديل جميع النصوص في قسم الخدمات</p>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_title_ar">عنوان القسم (عربي)</label>
                                                <input type="text" class="form-control" id="services_title_ar" name="services_title_ar" 
                                                       value="{{ $settings['services_title_ar'] ?? '' }}" placeholder="خدماتنا المتميزة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_title_en">عنوان القسم (إنجليزي)</label>
                                                <input type="text" class="form-control" id="services_title_en" name="services_title_en" 
                                                       value="{{ $settings['services_title_en'] ?? '' }}" placeholder="Our Services">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_subtitle_ar">العنوان الفرعي (عربي)</label>
                                                <input type="text" class="form-control" id="services_subtitle_ar" name="services_subtitle_ar" 
                                                       value="{{ $settings['services_subtitle_ar'] ?? '' }}" placeholder="خدماتنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_subtitle_en">العنوان الفرعي (إنجليزي)</label>
                                                <input type="text" class="form-control" id="services_subtitle_en" name="services_subtitle_en" 
                                                       value="{{ $settings['services_subtitle_en'] ?? '' }}" placeholder="Services">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_description_ar">وصف القسم (عربي)</label>
                                                <textarea class="form-control" id="services_description_ar" name="services_description_ar" rows="4" placeholder="نقدم مجموعة متنوعة من الخدمات الاجتماعية المتميزة لخدمة المجتمع وتقديم المساعدة للمحتاجين">{{ $settings['services_description_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_description_en">وصف القسم (إنجليزي)</label>
                                                <textarea class="form-control" id="services_description_en" name="services_description_en" rows="4" placeholder="We offer a variety of distinguished charitable and social services to serve the community and provide assistance to those in need">{{ $settings['services_description_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    
                                    <h5 class="mb-3 text-primary">نصوص الأزرار والعناصر</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="premium_badge_text_ar">نص شارة المميز (عربي)</label>
                                                <input type="text" class="form-control" id="premium_badge_text_ar" name="premium_badge_text_ar" 
                                                       value="{{ $settings['premium_badge_text_ar'] ?? '' }}" placeholder="خدمة متميزة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="premium_badge_text_en">نص شارة المميز (إنجليزي)</label>
                                                <input type="text" class="form-control" id="premium_badge_text_en" name="premium_badge_text_en" 
                                                       value="{{ $settings['premium_badge_text_en'] ?? '' }}" placeholder="Premium">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_now_button_text_ar">نص زر الطلب الآن (عربي)</label>
                                                <input type="text" class="form-control" id="order_now_button_text_ar" name="order_now_button_text_ar" 
                                                       value="{{ $settings['order_now_button_text_ar'] ?? '' }}" placeholder="اطلب الآن">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_now_button_text_en">نص زر الطلب الآن (إنجليزي)</label>
                                                <input type="text" class="form-control" id="order_now_button_text_en" name="order_now_button_text_en" 
                                                       value="{{ $settings['order_now_button_text_en'] ?? '' }}" placeholder="Order Now">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="saudi_riyal_text_ar">نص العملة (عربي)</label>
                                                <input type="text" class="form-control" id="saudi_riyal_text_ar" name="saudi_riyal_text_ar" 
                                                       value="{{ $settings['saudi_riyal_text_ar'] ?? '' }}" placeholder="ريال سعودي">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="saudi_riyal_text_en">نص العملة (إنجليزي)</label>
                                                <input type="text" class="form-control" id="saudi_riyal_text_en" name="saudi_riyal_text_en" 
                                                       value="{{ $settings['saudi_riyal_text_en'] ?? '' }}" placeholder="Saudi Riyal">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    
                                    <h5 class="mb-3 text-primary">قسم اكتشف المزيد</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="discover_more_services_title_ar">عنوان اكتشف المزيد (عربي)</label>
                                                <input type="text" class="form-control" id="discover_more_services_title_ar" name="discover_more_services_title_ar" 
                                                       value="{{ $settings['discover_more_services_title_ar'] ?? '' }}" placeholder="اكتشف المزيد من خدماتنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="discover_more_services_title_en">عنوان اكتشف المزيد (إنجليزي)</label>
                                                <input type="text" class="form-control" id="discover_more_services_title_en" name="discover_more_services_title_en" 
                                                       value="{{ $settings['discover_more_services_title_en'] ?? '' }}" placeholder="Discover More Services">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="discover_more_services_description_ar">وصف اكتشف المزيد (عربي)</label>
                                                <textarea class="form-control" id="discover_more_services_description_ar" name="discover_more_services_description_ar" rows="3" placeholder="تصفح مجموعتنا الكاملة من الخدمات الاجتماعية المصممة لخدمة المجتمع">{{ $settings['discover_more_services_description_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="discover_more_services_description_en">وصف اكتشف المزيد (إنجليزي)</label>
                                                <textarea class="form-control" id="discover_more_services_description_en" name="discover_more_services_description_en" rows="3" placeholder="Browse our complete collection of charitable and social services designed to serve the community">{{ $settings['discover_more_services_description_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="view_all_services_button_text_ar">نص زر عرض جميع الخدمات (عربي)</label>
                                                <input type="text" class="form-control" id="view_all_services_button_text_ar" name="view_all_services_button_text_ar" 
                                                       value="{{ $settings['view_all_services_button_text_ar'] ?? '' }}" placeholder="عرض جميع الخدمات">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="view_all_services_button_text_en">نص زر عرض جميع الخدمات (إنجليزي)</label>
                                                <input type="text" class="form-control" id="view_all_services_button_text_en" name="view_all_services_button_text_en" 
                                                       value="{{ $settings['view_all_services_button_text_en'] ?? '' }}" placeholder="View All Services">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <hr class="my-4">
                                    
                                    <h5 class="mb-3 text-primary">حالة عدم وجود خدمات</h5>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_coming_soon_title_ar">عنوان قادمة قريباً (عربي)</label>
                                                <input type="text" class="form-control" id="services_coming_soon_title_ar" name="services_coming_soon_title_ar" 
                                                       value="{{ $settings['services_coming_soon_title_ar'] ?? '' }}" placeholder="خدماتنا قادمة قريباً">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_coming_soon_title_en">عنوان قادمة قريباً (إنجليزي)</label>
                                                <input type="text" class="form-control" id="services_coming_soon_title_en" name="services_coming_soon_title_en" 
                                                       value="{{ $settings['services_coming_soon_title_en'] ?? '' }}" placeholder="Our Services Coming Soon">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_coming_soon_description_ar">وصف قادمة قريباً (عربي)</label>
                                                <textarea class="form-control" id="services_coming_soon_description_ar" name="services_coming_soon_description_ar" rows="3" placeholder="نعمل حالياً على إعداد مجموعة شاملة من الخدمات الاجتماعية لخدمتكم">{{ $settings['services_coming_soon_description_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_coming_soon_description_en">وصف قادمة قريباً (إنجليزي)</label>
                                                <textarea class="form-control" id="services_coming_soon_description_en" name="services_coming_soon_description_en" rows="3" placeholder="We are currently preparing a comprehensive set of charitable and social services for you">{{ $settings['services_coming_soon_description_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم التواصل</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_title_ar">عنوان قسم التواصل (عربي)</label>
                                                <input type="text" class="form-control" id="contact_us_title_ar" name="contact_us_title_ar" 
                                                       value="{{ $settings['contact_us_title_ar'] ?? '' }}" placeholder="تواصل معنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_title_en">عنوان قسم التواصل (إنجليزي)</label>
                                                <input type="text" class="form-control" id="contact_us_title_en" name="contact_us_title_en" 
                                                       value="{{ $settings['contact_us_title_en'] ?? '' }}" placeholder="Contact Us">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_description_ar">وصف قسم التواصل (عربي)</label>
                                                <textarea class="form-control" id="contact_us_description_ar" name="contact_us_description_ar" rows="3" placeholder="نحن هنا لمساعدتك في أي استفسار أو طلب خدمة">{{ $settings['contact_us_description_ar'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_description_en">وصف قسم التواصل (إنجليزي)</label>
                                                <textarea class="form-control" id="contact_us_description_en" name="contact_us_description_en" rows="3" placeholder="We are here to help you with any inquiry or service request">{{ $settings['contact_us_description_en'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_information_title_ar">عنوان معلومات الاتصال (عربي)</label>
                                                <input type="text" class="form-control" id="contact_information_title_ar" name="contact_information_title_ar" 
                                                       value="{{ $settings['contact_information_title_ar'] ?? '' }}" placeholder="معلومات الاتصال">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_information_title_en">عنوان معلومات الاتصال (إنجليزي)</label>
                                                <input type="text" class="form-control" id="contact_information_title_en" name="contact_information_title_en" 
                                                       value="{{ $settings['contact_information_title_en'] ?? '' }}" placeholder="Contact Information">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="send_us_message_title_ar">عنوان أرسل لنا رسالة (عربي)</label>
                                                <input type="text" class="form-control" id="send_us_message_title_ar" name="send_us_message_title_ar" 
                                                       value="{{ $settings['send_us_message_title_ar'] ?? '' }}" placeholder="أرسل لنا رسالة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="send_us_message_title_en">عنوان أرسل لنا رسالة (إنجليزي)</label>
                                                <input type="text" class="form-control" id="send_us_message_title_en" name="send_us_message_title_en" 
                                                       value="{{ $settings['send_us_message_title_en'] ?? '' }}" placeholder="Send Us a Message">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="working_hours_ar">ساعات العمل (عربي)</label>
                                                <input type="text" class="form-control" id="working_hours_ar" name="working_hours_ar" 
                                                       value="{{ $settings['working_hours_ar'] ?? '' }}" placeholder="ساعات العمل: 8:00 ص - 6:00 م">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="working_hours_en">ساعات العمل (إنجليزي)</label>
                                                <input type="text" class="form-control" id="working_hours_en" name="working_hours_en" 
                                                       value="{{ $settings['working_hours_en'] ?? '' }}" placeholder="Working Hours: 8:00 AM - 6:00 PM">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_location_title_ar">عنوان موقعنا (عربي)</label>
                                                <input type="text" class="form-control" id="our_location_title_ar" name="our_location_title_ar" 
                                                       value="{{ $settings['our_location_title_ar'] ?? '' }}" placeholder="موقعنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_location_title_en">عنوان موقعنا (إنجليزي)</label>
                                                <input type="text" class="form-control" id="our_location_title_en" name="our_location_title_en" 
                                                       value="{{ $settings['our_location_title_en'] ?? '' }}" placeholder="Our Location">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_location_description_ar">وصف الموقع (عربي)</label>
                                                <input type="text" class="form-control" id="our_location_description_ar" name="our_location_description_ar" 
                                                       value="{{ $settings['our_location_description_ar'] ?? '' }}" placeholder="الرياض، المملكة العربية السعودية">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_location_description_en">وصف الموقع (إنجليزي)</label>
                                                <input type="text" class="form-control" id="our_location_description_en" name="our_location_description_en" 
                                                       value="{{ $settings['our_location_description_en'] ?? '' }}" placeholder="Riyadh, Saudi Arabia">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hero Buttons Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>أزرار قسم الهيرو</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="browse_services_button_text_ar">نص زر تصفح الخدمات (عربي)</label>
                                                <input type="text" class="form-control" id="browse_services_button_text_ar" name="browse_services_button_text_ar" 
                                                       value="{{ $settings['browse_services_button_text_ar'] ?? '' }}" placeholder="تصفح الخدمات">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="browse_services_button_text_en">نص زر تصفح الخدمات (إنجليزي)</label>
                                                <input type="text" class="form-control" id="browse_services_button_text_en" name="browse_services_button_text_en" 
                                                       value="{{ $settings['browse_services_button_text_en'] ?? '' }}" placeholder="Browse Services">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="learn_more_button_text_ar">نص زر تعرف علينا (عربي)</label>
                                                <input type="text" class="form-control" id="learn_more_button_text_ar" name="learn_more_button_text_ar" 
                                                       value="{{ $settings['learn_more_button_text_ar'] ?? '' }}" placeholder="تعرف علينا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="learn_more_button_text_en">نص زر تعرف علينا (إنجليزي)</label>
                                                <input type="text" class="form-control" id="learn_more_button_text_en" name="learn_more_button_text_en" 
                                                       value="{{ $settings['learn_more_button_text_en'] ?? '' }}" placeholder="Learn More">
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_work_button_text_ar">نص زر أعمالنا (عربي)</label>
                                                <input type="text" class="form-control" id="our_work_button_text_ar" name="our_work_button_text_ar" 
                                                       value="{{ $settings['our_work_button_text_ar'] ?? '' }}" placeholder="أعمالنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="our_work_button_text_en">نص زر أعمالنا (إنجليزي)</label>
                                                <input type="text" class="form-control" id="our_work_button_text_en" name="our_work_button_text_en" 
                                                       value="{{ $settings['our_work_button_text_en'] ?? '' }}" placeholder="Our Work">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Statistics Section -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>الإحصائيات</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="stat-group p-3 border rounded">
                                                <h5 class="text-primary mb-3">الإحصائية الأولى</h5>
                                                <div class="form-group">
                                                    <label for="stat1_number">الرقم</label>
                                                    <input type="text" class="form-control" id="stat1_number" name="stat1_number" 
                                                           value="{{ $settings['stat1_number'] ?? '' }}" placeholder="500+">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stat1_label_ar">التسمية (عربي)</label>
                                                            <input type="text" class="form-control" id="stat1_label_ar" name="stat1_label_ar" 
                                                                   value="{{ $settings['stat1_label_ar'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stat1_label_en">التسمية (إنجليزي)</label>
                                                            <input type="text" class="form-control" id="stat1_label_en" name="stat1_label_en" 
                                                                   value="{{ $settings['stat1_label_en'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="stat-group p-3 border rounded">
                                                <h5 class="text-primary mb-3">الإحصائية الثانية</h5>
                                                <div class="form-group">
                                                    <label for="stat2_number">الرقم</label>
                                                    <input type="text" class="form-control" id="stat2_number" name="stat2_number" 
                                                           value="{{ $settings['stat2_number'] ?? '' }}" placeholder="1000+">
                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stat2_label_ar">التسمية (عربي)</label>
                                                            <input type="text" class="form-control" id="stat2_label_ar" name="stat2_label_ar" 
                                                                   value="{{ $settings['stat2_label_ar'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="stat2_label_en">التسمية (إنجليزي)</label>
                                                            <input type="text" class="form-control" id="stat2_label_en" name="stat2_label_en" 
                                                                   value="{{ $settings['stat2_label_en'] ?? '' }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Social Media -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>وسائل التواصل الاجتماعي</h4>
                                </div>
                                <div class="section-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_facebook">فيسبوك</label>
                                            <input type="url" class="form-control" id="social_facebook" name="social_facebook" 
                                                   value="{{ $settings['social_facebook'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_twitter">تويتر</label>
                                            <input type="url" class="form-control" id="social_twitter" name="social_twitter" 
                                                   value="{{ $settings['social_twitter'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_instagram">إنستغرام</label>
                                            <input type="url" class="form-control" id="social_instagram" name="social_instagram" 
                                                   value="{{ $settings['social_instagram'] ?? '' }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_linkedin">لينكد إن</label>
                                            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" 
                                                   value="{{ $settings['social_linkedin'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="social_youtube">يوتيوب</label>
                                            <input type="url" class="form-control" id="social_youtube" name="social_youtube" 
                                                   value="{{ $settings['social_youtube'] ?? '' }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn-save">
                                    <i class="fas fa-save mr-2"></i>حفظ جميع التغييرات
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteFile(field) {
    if (confirm('هل أنت متأكد من حذف هذا الملف؟')) {
        fetch('{{ route("admin.content-management.delete-file") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field: field })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            }
        });
    }
}
</script>
@endsection
