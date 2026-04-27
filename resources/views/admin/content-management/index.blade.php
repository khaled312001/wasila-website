@extends('admin.layouts.app')

@section('title', 'إدارة المحتوى')
@section('page-title', 'إدارة المحتوى')

@push('styles')
<link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
<style>
    .cm-wrapper { max-width: 1280px; margin: 0 auto; }
    .cm-hero {
        background: linear-gradient(135deg, #025469 0%, #08788B 50%, #3CA6B4 100%);
        color: #fff;
        padding: 1.75rem 2rem;
        border-radius: 18px;
        margin-bottom: 1.5rem;
        position: relative;
        overflow: hidden;
        box-shadow: 0 12px 40px rgba(2,84,105,0.25);
    }
    .cm-hero::before {
        content: '';
        position: absolute; inset: -40% -20% auto auto;
        width: 320px; height: 320px;
        background: radial-gradient(circle, rgba(255,255,255,0.18) 0%, transparent 70%);
        border-radius: 50%;
    }
    .cm-hero h1 { font-size: 1.75rem; font-weight: 800; margin: 0; }
    .cm-hero p { margin: .25rem 0 0; opacity: .92; }

    .cm-tabs {
        display: flex; flex-wrap: wrap; gap: .5rem;
        background: #fff; padding: .75rem;
        border-radius: 14px;
        box-shadow: 0 4px 18px rgba(0,0,0,.06);
        margin-bottom: 1.25rem;
        position: sticky; top: 70px; z-index: 20;
        border: 1px solid #e6eef1;
    }
    .cm-tab {
        background: #f1f5f9; color: #1e293b; border: 0;
        padding: .55rem 1rem; border-radius: 10px;
        font-weight: 600; cursor: pointer; font-size: .9rem;
        display: inline-flex; align-items: center; gap: .4rem;
        transition: all .25s ease;
    }
    .cm-tab i { font-size: .85rem; }
    .cm-tab:hover { background: #e2e8f0; }
    .cm-tab.active {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        color: #fff; box-shadow: 0 4px 14px rgba(8,120,139,.35);
    }

    .cm-section { display: none; }
    .cm-section.active { display: block; animation: cmFade .35s ease; }
    @keyframes cmFade { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: none; } }

    .cm-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 6px 22px rgba(0,0,0,.06);
        border: 1px solid #e6eef1;
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    .cm-card-header {
        background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e6eef1;
        display: flex; align-items: center; gap: .75rem;
    }
    .cm-card-header i {
        width: 38px; height: 38px;
        display: inline-flex; align-items: center; justify-content: center;
        background: linear-gradient(135deg, #08788B, #025469);
        color: #fff; border-radius: 10px;
        box-shadow: 0 4px 12px rgba(8,120,139,.3);
    }
    .cm-card-header h4 { margin: 0; color: #0f172a; font-weight: 700; font-size: 1.1rem; }
    .cm-card-header small { display: block; color: #64748b; font-size: .8rem; margin-top: 2px; }
    .cm-card-body { padding: 1.5rem; }

    .cm-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem 1.25rem; }
    @media (max-width: 768px) { .cm-grid { grid-template-columns: 1fr; } }

    .cm-field label {
        display: block; font-weight: 600; color: #334155;
        margin-bottom: .4rem; font-size: .9rem;
    }
    .cm-field label .lang-badge {
        display: inline-block; font-size: .7rem; font-weight: 700;
        padding: 2px 8px; border-radius: 8px; margin-inline-start: .35rem;
        background: #e0f2fe; color: #0369a1;
    }
    .cm-field label .lang-badge.en { background: #fef3c7; color: #92400e; }
    .cm-field input[type="text"],
    .cm-field input[type="email"],
    .cm-field input[type="url"],
    .cm-field input[type="tel"],
    .cm-field textarea {
        width: 100%;
        border: 1.5px solid #e2e8f0;
        border-radius: 10px;
        padding: .65rem .9rem;
        font-size: .95rem;
        background: #f8fafc;
        transition: all .25s ease;
        font-family: inherit;
    }
    .cm-field input:focus,
    .cm-field textarea:focus {
        outline: none;
        border-color: #08788B;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(8,120,139,.12);
    }
    .cm-field textarea { resize: vertical; min-height: 90px; }
    .cm-required { color: #ef4444; }

    .cm-feature-block {
        background: linear-gradient(135deg, #f8fafc 0%, #fff 100%);
        border: 1px solid #e6eef1; border-radius: 12px;
        padding: 1.25rem; margin-bottom: 1rem;
    }
    .cm-feature-title {
        color: #08788B; font-weight: 700; font-size: 1rem;
        margin: 0 0 .85rem; display: flex; align-items: center; gap: .5rem;
    }

    .cm-radio-group { display: flex; gap: .75rem; flex-wrap: wrap; margin-bottom: 1rem; }
    .cm-radio {
        flex: 1; min-width: 200px;
        background: #fff; border: 2px solid #e2e8f0; border-radius: 12px;
        padding: .85rem 1rem; cursor: pointer;
        display: flex; align-items: center; gap: .75rem;
        transition: all .25s ease;
    }
    .cm-radio input { accent-color: #08788B; transform: scale(1.2); }
    .cm-radio:hover { border-color: #94a3b8; }
    .cm-radio.checked { border-color: #08788B; background: #ecfeff; box-shadow: 0 4px 14px rgba(8,120,139,.18); }
    .cm-radio i { color: #08788B; font-size: 1.1rem; }

    .cm-video-pane { display: none; }
    .cm-video-pane.active { display: block; animation: cmFade .25s ease; }

    .cm-current-video {
        background: #f8fafc; border: 1px dashed #cbd5e1;
        border-radius: 12px; padding: 1rem; margin-bottom: 1rem;
    }
    .cm-current-video video, .cm-current-video iframe {
        max-width: 100%; border-radius: 8px;
    }
    .cm-info-banner {
        background: #ecfeff; border: 1px solid #a5f3fc; border-radius: 10px;
        padding: .65rem .85rem; color: #0e7490; font-size: .85rem;
        display: flex; align-items: center; gap: .5rem;
    }

    .cm-file-input {
        display: block; width: 100%;
        border: 2px dashed #cbd5e1; background: #f8fafc;
        border-radius: 12px; padding: 1rem; cursor: pointer;
        transition: all .25s ease;
    }
    .cm-file-input:hover { border-color: #08788B; background: #ecfeff; }

    .cm-actions {
        position: sticky; bottom: 0;
        background: linear-gradient(180deg, transparent 0%, #f3f4f6 30%);
        padding: 1.25rem 0 .5rem; margin-top: 1rem;
        z-index: 10;
    }
    .cm-actions-inner {
        display: flex; justify-content: center; gap: .75rem; flex-wrap: wrap;
    }
    .btn-cm-save {
        background: linear-gradient(135deg, #08788B 0%, #025469 100%);
        color: #fff; border: none;
        padding: .85rem 2.5rem; border-radius: 12px;
        font-size: 1rem; font-weight: 700;
        box-shadow: 0 8px 24px rgba(8,120,139,.35);
        transition: all .25s ease;
        display: inline-flex; align-items: center; gap: .5rem;
        cursor: pointer;
    }
    .btn-cm-save:hover { transform: translateY(-2px); box-shadow: 0 12px 32px rgba(8,120,139,.45); }
    .btn-cm-cancel {
        background: #fff; color: #475569; border: 1.5px solid #e2e8f0;
        padding: .85rem 1.75rem; border-radius: 12px; font-weight: 600;
        text-decoration: none;
        display: inline-flex; align-items: center; gap: .5rem;
    }

    .alert-cm {
        border-radius: 12px; border: 0;
        padding: 1rem 1.25rem; margin-bottom: 1.25rem;
        display: flex; align-items: center; gap: .65rem;
        font-weight: 600;
    }
    .alert-cm-success { background: #d1fae5; color: #065f46; }
    .alert-cm-danger  { background: #fee2e2; color: #991b1b; }

    .cm-readonly-info {
        background: linear-gradient(135deg, #fff7ed 0%, #fffbeb 100%);
        border: 1px solid #fed7aa; border-radius: 10px;
        padding: .85rem 1rem; color: #9a3412; font-size: .9rem;
        display: flex; align-items: flex-start; gap: .55rem;
    }
    .cm-readonly-info a { color: #c2410c; font-weight: 700; text-decoration: underline; }
</style>
@endpush

@section('content')
<div class="cm-wrapper">
    <div class="cm-hero">
        <h1><i class="fas fa-paint-brush ml-2"></i> إدارة محتوى الصفحة الرئيسية</h1>
        <p>قم بتخصيص جميع نصوص ووسائط الموقع. الأقسام أدناه مرتبة بنفس ترتيب ظهورها في الصفحة الرئيسية.</p>
    </div>

    @if(session('success'))
        <div class="alert-cm alert-cm-success">
            <i class="fas fa-check-circle"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if($errors->any())
        <div class="alert-cm alert-cm-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <div>
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Tabs - in same order as the home page sections -->
    <div class="cm-tabs" role="tablist">
        <button type="button" class="cm-tab active" data-tab="hero"><i class="fas fa-home"></i> الهيرو</button>
        <button type="button" class="cm-tab" data-tab="services"><i class="fas fa-cogs"></i> الخدمات</button>
        <button type="button" class="cm-tab" data-tab="about"><i class="fas fa-info-circle"></i> من نحن</button>
        <button type="button" class="cm-tab" data-tab="features"><i class="fas fa-star"></i> المميزات</button>
        <button type="button" class="cm-tab" data-tab="our-work"><i class="fas fa-images"></i> أعمالنا</button>
        <button type="button" class="cm-tab" data-tab="contact"><i class="fas fa-envelope"></i> التواصل</button>
    </div>

    <form action="{{ route('admin.content-management.update') }}" method="POST" enctype="multipart/form-data" id="cmForm">
        @csrf

        <!-- 1) HERO -->
        <div class="cm-section active" data-section="hero">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-home"></i>
                    <div>
                        <h4>قسم الهيرو</h4>
                        <small>أول قسم يظهر للزائر — عنوان ووصف وأزرار وفيديو خلفية</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>عنوان الهيرو <span class="lang-badge">عربي</span> <span class="cm-required">*</span></label>
                            <input type="text" name="hero_title_ar" value="{{ old('hero_title_ar', $settings['hero_title_ar'] ?? '') }}" required>
                        </div>
                        <div class="cm-field">
                            <label>Hero Title <span class="lang-badge en">EN</span> <span class="cm-required">*</span></label>
                            <input type="text" name="hero_title_en" value="{{ old('hero_title_en', $settings['hero_title_en'] ?? '') }}" required>
                        </div>
                        <div class="cm-field">
                            <label>وصف الهيرو <span class="lang-badge">عربي</span> <span class="cm-required">*</span></label>
                            <textarea name="hero_description_ar" rows="3" required>{{ old('hero_description_ar', $settings['hero_description_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>Hero Description <span class="lang-badge en">EN</span> <span class="cm-required">*</span></label>
                            <textarea name="hero_description_en" rows="3" required>{{ old('hero_description_en', $settings['hero_description_en'] ?? '') }}</textarea>
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #e6eef1; margin: 1.5rem 0;">
                    <h5 style="font-weight: 700; color: #08788B; margin-bottom: .85rem;"><i class="fas fa-mouse-pointer ml-1"></i> أزرار الهيرو</h5>
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>زر تصفح الخدمات <span class="lang-badge">عربي</span></label>
                            <input type="text" name="browse_services_button_text_ar" value="{{ old('browse_services_button_text_ar', $settings['browse_services_button_text_ar'] ?? '') }}" placeholder="تصفح الخدمات">
                        </div>
                        <div class="cm-field">
                            <label>Browse Services Button <span class="lang-badge en">EN</span></label>
                            <input type="text" name="browse_services_button_text_en" value="{{ old('browse_services_button_text_en', $settings['browse_services_button_text_en'] ?? '') }}" placeholder="Browse Services">
                        </div>
                        <div class="cm-field">
                            <label>زر تعرّف علينا <span class="lang-badge">عربي</span></label>
                            <input type="text" name="learn_more_button_text_ar" value="{{ old('learn_more_button_text_ar', $settings['learn_more_button_text_ar'] ?? '') }}" placeholder="تعرّف علينا">
                        </div>
                        <div class="cm-field">
                            <label>Learn More Button <span class="lang-badge en">EN</span></label>
                            <input type="text" name="learn_more_button_text_en" value="{{ old('learn_more_button_text_en', $settings['learn_more_button_text_en'] ?? '') }}" placeholder="Learn More">
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1px solid #e6eef1; margin: 1.5rem 0;">
                    <h5 style="font-weight: 700; color: #08788B; margin-bottom: .85rem;"><i class="fas fa-video ml-1"></i> فيديو خلفية الهيرو</h5>

                    @php
                        $heroVideoType = old('hero_video_type', $settings['hero_video_type'] ?? 'file');
                        $heroVideoYoutube = old('hero_video_youtube', $settings['hero_video_youtube'] ?? '');
                        $heroVideoFile = $settings['hero_video'] ?? '';
                    @endphp

                    <div class="cm-radio-group">
                        <label class="cm-radio {{ $heroVideoType === 'file' ? 'checked' : '' }}">
                            <input type="radio" name="hero_video_type" value="file" {{ $heroVideoType === 'file' ? 'checked' : '' }}>
                            <i class="fas fa-upload"></i>
                            <div>
                                <strong>رفع فيديو</strong>
                                <small style="display:block; color:#64748b;">صيغة MP4 / MOV (حد أقصى 100MB)</small>
                            </div>
                        </label>
                        <label class="cm-radio {{ $heroVideoType === 'youtube' ? 'checked' : '' }}">
                            <input type="radio" name="hero_video_type" value="youtube" {{ $heroVideoType === 'youtube' ? 'checked' : '' }}>
                            <i class="fab fa-youtube" style="color:#dc2626;"></i>
                            <div>
                                <strong>رابط يوتيوب</strong>
                                <small style="display:block; color:#64748b;">الصق رابط الفيديو من YouTube</small>
                            </div>
                        </label>
                    </div>

                    <!-- File pane -->
                    <div class="cm-video-pane {{ $heroVideoType === 'file' ? 'active' : '' }}" data-pane="file">
                        @if($heroVideoFile)
                            <div class="cm-current-video">
                                <p style="margin:0 0 .5rem; color:#475569; font-weight:600;">الفيديو الحالي المرفوع:</p>
                                <video controls style="max-width: 360px; max-height: 200px;">
                                    <source src="{{ $heroVideoFile }}" type="video/mp4">
                                </video>
                                <div style="margin-top:.65rem;">
                                    <button type="button" class="btn-cm-cancel" onclick="deleteHeroVideo()" style="background:#fee2e2; color:#991b1b; border-color:#fecaca;">
                                        <i class="fas fa-trash"></i> إزالة الفيديو المرفوع
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="cm-info-banner">
                                <i class="fas fa-info-circle"></i>
                                لا يوجد فيديو مرفوع — سيتم استخدام الفيديو الافتراضي <code style="background:#fff;padding:2px 6px;border-radius:4px;">/videos/hero-video.mp4</code>
                            </div>
                        @endif
                        <label class="cm-file-input" style="margin-top:.75rem;">
                            <i class="fas fa-cloud-upload-alt" style="font-size:1.5rem; color:#08788B;"></i>
                            <span style="margin-inline-start:.5rem; font-weight:600; color:#475569;">اختر ملف فيديو لرفعه</span>
                            <input type="file" name="hero_video" accept="video/mp4,video/avi,video/mov,video/wmv" style="display:block; margin-top:.5rem;">
                        </label>
                    </div>

                    <!-- YouTube pane -->
                    <div class="cm-video-pane {{ $heroVideoType === 'youtube' ? 'active' : '' }}" data-pane="youtube">
                        <div class="cm-field">
                            <label><i class="fab fa-youtube" style="color:#dc2626;"></i> رابط فيديو يوتيوب</label>
                            <input type="text" name="hero_video_youtube" id="hero_video_youtube"
                                   value="{{ $heroVideoYoutube }}"
                                   placeholder="https://youtu.be/uK3oRpkCFU8 أو https://www.youtube.com/watch?v=...">
                            <small style="display:block; color:#64748b; margin-top:.4rem;">يدعم الصيغ: youtu.be/ID — youtube.com/watch?v=ID — youtube.com/embed/ID</small>
                        </div>
                        @if($heroVideoYoutube)
                            <div class="cm-current-video">
                                <p style="margin:0 0 .5rem; color:#475569; font-weight:600;">معاينة الفيديو الحالي:</p>
                                @php
                                    // Simple parser for preview
                                    $previewId = '';
                                    if (preg_match('~(?:youtu\.be/|youtube\.com/(?:watch\?v=|embed/|v/|shorts/))([A-Za-z0-9_-]{6,})~', $heroVideoYoutube, $m)) {
                                        $previewId = $m[1];
                                    }
                                @endphp
                                @if($previewId)
                                    <iframe width="360" height="200" src="https://www.youtube.com/embed/{{ $previewId }}" frameborder="0" allowfullscreen></iframe>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- 2) SERVICES -->
        <div class="cm-section" data-section="services">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-cogs"></i>
                    <div>
                        <h4>قسم الخدمات</h4>
                        <small>عنوان القسم وأزراره — لإدارة الخدمات نفسها استخدم صفحة الخدمات</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-readonly-info" style="margin-bottom:1.25rem;">
                        <i class="fas fa-lightbulb" style="margin-top:2px;"></i>
                        <div>لإضافة / تعديل / حذف الخدمات نفسها، انتقل إلى <a href="{{ route('admin.services.index') }}">صفحة إدارة الخدمات</a>.</div>
                    </div>
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>عنوان قسم الخدمات <span class="lang-badge">عربي</span></label>
                            <input type="text" name="services_title_ar" value="{{ old('services_title_ar', $settings['services_title_ar'] ?? '') }}" placeholder="خدماتنا المتميزة">
                        </div>
                        <div class="cm-field">
                            <label>Services Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="services_title_en" value="{{ old('services_title_en', $settings['services_title_en'] ?? '') }}" placeholder="Our Services">
                        </div>
                        <div class="cm-field">
                            <label>وصف القسم <span class="lang-badge">عربي</span></label>
                            <textarea name="services_description_ar" rows="3">{{ old('services_description_ar', $settings['services_description_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>Services Description <span class="lang-badge en">EN</span></label>
                            <textarea name="services_description_en" rows="3">{{ old('services_description_en', $settings['services_description_en'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>نص زر «اطلب الآن» <span class="lang-badge">عربي</span></label>
                            <input type="text" name="order_now_button_text_ar" value="{{ old('order_now_button_text_ar', $settings['order_now_button_text_ar'] ?? '') }}" placeholder="اطلب الآن">
                        </div>
                        <div class="cm-field">
                            <label>"Order Now" Button <span class="lang-badge en">EN</span></label>
                            <input type="text" name="order_now_button_text_en" value="{{ old('order_now_button_text_en', $settings['order_now_button_text_en'] ?? '') }}" placeholder="Order Now">
                        </div>
                        <div class="cm-field">
                            <label>نص العملة <span class="lang-badge">عربي</span></label>
                            <input type="text" name="saudi_riyal_text_ar" value="{{ old('saudi_riyal_text_ar', $settings['saudi_riyal_text_ar'] ?? '') }}" placeholder="ريال سعودي">
                        </div>
                        <div class="cm-field">
                            <label>Currency Text <span class="lang-badge en">EN</span></label>
                            <input type="text" name="saudi_riyal_text_en" value="{{ old('saudi_riyal_text_en', $settings['saudi_riyal_text_en'] ?? '') }}" placeholder="SAR">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 3) ABOUT -->
        <div class="cm-section" data-section="about">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <h4>قسم من نحن</h4>
                        <small>تعريف بالمنصة ومهمتها</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>عنوان القسم <span class="lang-badge">عربي</span></label>
                            <input type="text" name="about_title_ar" value="{{ old('about_title_ar', $settings['about_title_ar'] ?? '') }}">
                        </div>
                        <div class="cm-field">
                            <label>About Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="about_title_en" value="{{ old('about_title_en', $settings['about_title_en'] ?? '') }}">
                        </div>
                        <div class="cm-field">
                            <label>الوصف <span class="lang-badge">عربي</span></label>
                            <textarea name="about_description_ar" rows="4">{{ old('about_description_ar', $settings['about_description_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>About Description <span class="lang-badge en">EN</span></label>
                            <textarea name="about_description_en" rows="4">{{ old('about_description_en', $settings['about_description_en'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>المهمة <span class="lang-badge">عربي</span></label>
                            <textarea name="about_mission_ar" rows="3">{{ old('about_mission_ar', $settings['about_mission_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>Mission <span class="lang-badge en">EN</span></label>
                            <textarea name="about_mission_en" rows="3">{{ old('about_mission_en', $settings['about_mission_en'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 4) FEATURES -->
        <div class="cm-section" data-section="features">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-star"></i>
                    <div>
                        <h4>قسم المميزات</h4>
                        <small>ثلاث مميزات أساسية تظهر بعد قسم «من نحن»</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    @foreach([1, 2, 3] as $i)
                        @php $titles = [1=>'الميزة الأولى', 2=>'الميزة الثانية', 3=>'الميزة الثالثة']; @endphp
                        <div class="cm-feature-block">
                            <h6 class="cm-feature-title"><i class="fas fa-check-circle"></i> {{ $titles[$i] }}</h6>
                            <div class="cm-grid">
                                <div class="cm-field">
                                    <label>العنوان <span class="lang-badge">عربي</span></label>
                                    <input type="text" name="feature{{ $i }}_title_ar" value="{{ old('feature'.$i.'_title_ar', $settings['feature'.$i.'_title_ar'] ?? '') }}">
                                </div>
                                <div class="cm-field">
                                    <label>Title <span class="lang-badge en">EN</span></label>
                                    <input type="text" name="feature{{ $i }}_title_en" value="{{ old('feature'.$i.'_title_en', $settings['feature'.$i.'_title_en'] ?? '') }}">
                                </div>
                                <div class="cm-field">
                                    <label>الوصف <span class="lang-badge">عربي</span></label>
                                    <textarea name="feature{{ $i }}_description_ar" rows="3">{{ old('feature'.$i.'_description_ar', $settings['feature'.$i.'_description_ar'] ?? '') }}</textarea>
                                </div>
                                <div class="cm-field">
                                    <label>Description <span class="lang-badge en">EN</span></label>
                                    <textarea name="feature{{ $i }}_description_en" rows="3">{{ old('feature'.$i.'_description_en', $settings['feature'.$i.'_description_en'] ?? '') }}</textarea>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- 5) OUR WORK -->
        <div class="cm-section" data-section="our-work">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-images"></i>
                    <div>
                        <h4>قسم أعمالنا</h4>
                        <small>عنوان القسم — لإدارة الصور والفيديوهات استخدم صفحة معرض الأعمال</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-readonly-info" style="margin-bottom:1.25rem;">
                        <i class="fas fa-lightbulb" style="margin-top:2px;"></i>
                        <div>لإدارة عناصر معرض الأعمال (الصور / الفيديوهات), انتقل إلى <a href="{{ route('admin.portfolio.index') }}">صفحة معرض الأعمال</a>.</div>
                    </div>
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>عنوان القسم <span class="lang-badge">عربي</span></label>
                            <input type="text" name="our_work_title_ar" value="{{ old('our_work_title_ar', $settings['our_work_title_ar'] ?? '') }}" placeholder="أعمالنا">
                        </div>
                        <div class="cm-field">
                            <label>Our Work Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="our_work_title_en" value="{{ old('our_work_title_en', $settings['our_work_title_en'] ?? '') }}" placeholder="Our Work">
                        </div>
                        <div class="cm-field">
                            <label>وصف القسم <span class="lang-badge">عربي</span></label>
                            <textarea name="our_work_description_ar" rows="2">{{ old('our_work_description_ar', $settings['our_work_description_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>Description <span class="lang-badge en">EN</span></label>
                            <textarea name="our_work_description_en" rows="2">{{ old('our_work_description_en', $settings['our_work_description_en'] ?? '') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- 6) CONTACT -->
        <div class="cm-section" data-section="contact">
            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-address-book"></i>
                    <div>
                        <h4>معلومات التواصل</h4>
                        <small>تستخدم في الفوتر والقسم السفلي بالموقع</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>البريد الإلكتروني</label>
                            <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                        </div>
                        <div class="cm-field">
                            <label>رقم الهاتف</label>
                            <input type="text" name="contact_phone" value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                        </div>
                        <div class="cm-field" style="grid-column: span 2;">
                            <label>رابط الواتساب</label>
                            <input type="url" name="whatsapp_link" value="{{ old('whatsapp_link', $settings['whatsapp_link'] ?? '') }}" placeholder="https://wa.me/9665xxxxxxxx">
                        </div>
                    </div>
                </div>
            </div>

            <div class="cm-card">
                <div class="cm-card-header">
                    <i class="fas fa-envelope"></i>
                    <div>
                        <h4>نصوص قسم التواصل</h4>
                        <small>العناوين الظاهرة في قسم «تواصل معنا»</small>
                    </div>
                </div>
                <div class="cm-card-body">
                    <div class="cm-grid">
                        <div class="cm-field">
                            <label>عنوان القسم <span class="lang-badge">عربي</span></label>
                            <input type="text" name="contact_us_title_ar" value="{{ old('contact_us_title_ar', $settings['contact_us_title_ar'] ?? '') }}" placeholder="تواصل معنا">
                        </div>
                        <div class="cm-field">
                            <label>Contact Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="contact_us_title_en" value="{{ old('contact_us_title_en', $settings['contact_us_title_en'] ?? '') }}" placeholder="Contact Us">
                        </div>
                        <div class="cm-field">
                            <label>وصف القسم <span class="lang-badge">عربي</span></label>
                            <textarea name="contact_us_description_ar" rows="2">{{ old('contact_us_description_ar', $settings['contact_us_description_ar'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>Description <span class="lang-badge en">EN</span></label>
                            <textarea name="contact_us_description_en" rows="2">{{ old('contact_us_description_en', $settings['contact_us_description_en'] ?? '') }}</textarea>
                        </div>
                        <div class="cm-field">
                            <label>عنوان «معلومات الاتصال» <span class="lang-badge">عربي</span></label>
                            <input type="text" name="contact_information_title_ar" value="{{ old('contact_information_title_ar', $settings['contact_information_title_ar'] ?? '') }}" placeholder="معلومات الاتصال">
                        </div>
                        <div class="cm-field">
                            <label>"Contact Information" Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="contact_information_title_en" value="{{ old('contact_information_title_en', $settings['contact_information_title_en'] ?? '') }}" placeholder="Contact Information">
                        </div>
                        <div class="cm-field">
                            <label>عنوان «أرسل لنا رسالة» <span class="lang-badge">عربي</span></label>
                            <input type="text" name="send_us_message_title_ar" value="{{ old('send_us_message_title_ar', $settings['send_us_message_title_ar'] ?? '') }}" placeholder="أرسل لنا رسالة">
                        </div>
                        <div class="cm-field">
                            <label>"Send Us a Message" Title <span class="lang-badge en">EN</span></label>
                            <input type="text" name="send_us_message_title_en" value="{{ old('send_us_message_title_en', $settings['send_us_message_title_en'] ?? '') }}" placeholder="Send Us a Message">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="cm-actions">
            <div class="cm-actions-inner">
                <a href="{{ route('home') }}" target="_blank" class="btn-cm-cancel">
                    <i class="fas fa-eye"></i> معاينة الموقع
                </a>
                <button type="submit" class="btn-cm-save">
                    <i class="fas fa-save"></i> حفظ جميع التغييرات
                </button>
            </div>
        </div>
    </form>
</div>

<script>
(function() {
    // Tabs
    const tabs = document.querySelectorAll('.cm-tab');
    const sections = document.querySelectorAll('.cm-section');
    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            sections.forEach(s => s.classList.remove('active'));
            tab.classList.add('active');
            const target = tab.dataset.tab;
            const sec = document.querySelector('.cm-section[data-section="' + target + '"]');
            if (sec) sec.classList.add('active');
            // Smooth scroll up to top of form
            window.scrollTo({ top: document.querySelector('.cm-tabs').offsetTop - 90, behavior: 'smooth' });
        });
    });

    // Hero video type radio toggle
    const radios = document.querySelectorAll('input[name="hero_video_type"]');
    const panes = document.querySelectorAll('.cm-video-pane');
    function setVideoPane(value) {
        panes.forEach(p => p.classList.toggle('active', p.dataset.pane === value));
        document.querySelectorAll('.cm-radio').forEach(r => {
            const inp = r.querySelector('input[type=radio]');
            r.classList.toggle('checked', inp && inp.checked);
        });
    }
    radios.forEach(r => r.addEventListener('change', () => setVideoPane(r.value)));
})();

function deleteHeroVideo() {
    if (!confirm('هل تريد إزالة الفيديو المرفوع والرجوع للفيديو الافتراضي؟')) return;
    fetch('{{ route("admin.content-management.delete-file") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ field: 'hero_video' })
    })
    .then(r => r.json())
    .then(d => { if (d.success) location.reload(); });
}
</script>
@endsection
