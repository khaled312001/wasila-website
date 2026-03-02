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

                        @if($errors->any())
                            <div class="alert alert-danger m-4">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.content-management.update') }}" method="POST" enctype="multipart/form-data" class="p-4">
                            @csrf

                            <!-- قسم الهيرو -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم الهيرو (الصفحة الرئيسية)</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hero_title_ar">عنوان الهيرو (عربي) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="hero_title_ar" name="hero_title_ar"
                                                       value="{{ old('hero_title_ar', $settings['hero_title_ar'] ?? '') }}" required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hero_title_en">عنوان الهيرو (إنجليزي) <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="hero_title_en" name="hero_title_en"
                                                       value="{{ old('hero_title_en', $settings['hero_title_en'] ?? '') }}" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hero_description_ar">وصف الهيرو (عربي) <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="hero_description_ar" name="hero_description_ar" rows="4" required>{{ old('hero_description_ar', $settings['hero_description_ar'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="hero_description_en">وصف الهيرو (إنجليزي) <span class="text-danger">*</span></label>
                                                <textarea class="form-control" id="hero_description_en" name="hero_description_en" rows="4" required>{{ old('hero_description_en', $settings['hero_description_en'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- فيديو الهيرو -->
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="hero_video">فيديو الهيرو (خلفية الصفحة الرئيسية)</label>
                                                @php $currentVideo = $settings['hero_video'] ?? ''; @endphp
                                                @if($currentVideo)
                                                    <div class="mb-3 p-3 bg-light rounded">
                                                        <p class="text-muted small mb-2">الفيديو الحالي:</p>
                                                        <video controls style="max-width: 320px; max-height: 180px; border-radius: 8px;">
                                                            <source src="{{ $currentVideo }}" type="video/mp4">
                                                        </video>
                                                        <br>
                                                        <button type="button" class="btn btn-sm btn-outline-danger mt-2" onclick="deleteHeroVideo()">
                                                            <i class="fas fa-trash mr-1"></i> إزالة الفيديو المرفوع (والرجوع للفيديو الافتراضي)
                                                        </button>
                                                    </div>
                                                @else
                                                    <div class="mb-2 p-2 bg-info-light rounded" style="background:#e8f4fd; border-radius:6px;">
                                                        <small class="text-muted">
                                                            <i class="fas fa-info-circle"></i>
                                                            يُستخدم حالياً الفيديو الافتراضي: <code>/videos/hero-video.mp4</code>
                                                        </small>
                                                    </div>
                                                @endif
                                                <input type="file" class="form-control-file mt-2" id="hero_video" name="hero_video" accept="video/mp4,video/avi,video/mov,video/wmv">
                                                <small class="form-text text-muted">الحجم الأقصى 100 ميجابايت. الصيغ المدعومة: MP4, AVI, MOV, WMV</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- أزرار الهيرو -->
                                    <hr>
                                    <h5 class="mb-3 text-primary">أزرار قسم الهيرو</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="browse_services_button_text_ar">زر تصفح الخدمات (عربي)</label>
                                                <input type="text" class="form-control" id="browse_services_button_text_ar" name="browse_services_button_text_ar"
                                                       value="{{ old('browse_services_button_text_ar', $settings['browse_services_button_text_ar'] ?? '') }}" placeholder="تصفح الخدمات">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="browse_services_button_text_en">زر تصفح الخدمات (إنجليزي)</label>
                                                <input type="text" class="form-control" id="browse_services_button_text_en" name="browse_services_button_text_en"
                                                       value="{{ old('browse_services_button_text_en', $settings['browse_services_button_text_en'] ?? '') }}" placeholder="Browse Services">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="learn_more_button_text_ar">زر تعرف علينا (عربي)</label>
                                                <input type="text" class="form-control" id="learn_more_button_text_ar" name="learn_more_button_text_ar"
                                                       value="{{ old('learn_more_button_text_ar', $settings['learn_more_button_text_ar'] ?? '') }}" placeholder="تعرف علينا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="learn_more_button_text_en">زر تعرف علينا (إنجليزي)</label>
                                                <input type="text" class="form-control" id="learn_more_button_text_en" name="learn_more_button_text_en"
                                                       value="{{ old('learn_more_button_text_en', $settings['learn_more_button_text_en'] ?? '') }}" placeholder="Learn More">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- معلومات التواصل -->
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
                                                       value="{{ old('contact_email', $settings['contact_email'] ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="contact_phone">رقم الهاتف</label>
                                                <input type="text" class="form-control" id="contact_phone" name="contact_phone"
                                                       value="{{ old('contact_phone', $settings['contact_phone'] ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="whatsapp_link">رابط الواتساب</label>
                                                <input type="url" class="form-control" id="whatsapp_link" name="whatsapp_link"
                                                       value="{{ old('whatsapp_link', $settings['whatsapp_link'] ?? '') }}" placeholder="https://wa.me/966xxxxxxxxx">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم من نحن -->
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
                                                       value="{{ old('about_title_ar', $settings['about_title_ar'] ?? '') }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_title_en">عنوان القسم (إنجليزي)</label>
                                                <input type="text" class="form-control" id="about_title_en" name="about_title_en"
                                                       value="{{ old('about_title_en', $settings['about_title_en'] ?? '') }}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_description_ar">الوصف (عربي)</label>
                                                <textarea class="form-control" id="about_description_ar" name="about_description_ar" rows="4">{{ old('about_description_ar', $settings['about_description_ar'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_description_en">الوصف (إنجليزي)</label>
                                                <textarea class="form-control" id="about_description_en" name="about_description_en" rows="4">{{ old('about_description_en', $settings['about_description_en'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_mission_ar">المهمة (عربي)</label>
                                                <textarea class="form-control" id="about_mission_ar" name="about_mission_ar" rows="3">{{ old('about_mission_ar', $settings['about_mission_ar'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="about_mission_en">المهمة (إنجليزي)</label>
                                                <textarea class="form-control" id="about_mission_en" name="about_mission_en" rows="3">{{ old('about_mission_en', $settings['about_mission_en'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- المميزات -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>المميزات (لماذا وسيلة؟)</h4>
                                </div>
                                <div class="section-body">
                                    @foreach([1, 2, 3] as $i)
                                    <div class="feature-group mb-4 p-3 border rounded">
                                        <h5 class="text-primary mb-3">الميزة {{ $i == 1 ? 'الأولى' : ($i == 2 ? 'الثانية' : 'الثالثة') }}</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>العنوان (عربي)</label>
                                                    <input type="text" class="form-control" name="feature{{ $i }}_title_ar"
                                                           value="{{ old('feature'.$i.'_title_ar', $settings['feature'.$i.'_title_ar'] ?? '') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>العنوان (إنجليزي)</label>
                                                    <input type="text" class="form-control" name="feature{{ $i }}_title_en"
                                                           value="{{ old('feature'.$i.'_title_en', $settings['feature'.$i.'_title_en'] ?? '') }}">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>الوصف (عربي)</label>
                                                    <textarea class="form-control" name="feature{{ $i }}_description_ar" rows="3">{{ old('feature'.$i.'_description_ar', $settings['feature'.$i.'_description_ar'] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>الوصف (إنجليزي)</label>
                                                    <textarea class="form-control" name="feature{{ $i }}_description_en" rows="3">{{ old('feature'.$i.'_description_en', $settings['feature'.$i.'_description_en'] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- قسم الخدمات -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>قسم الخدمات</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_title_ar">عنوان القسم (عربي)</label>
                                                <input type="text" class="form-control" id="services_title_ar" name="services_title_ar"
                                                       value="{{ old('services_title_ar', $settings['services_title_ar'] ?? '') }}" placeholder="خدماتنا المتميزة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_title_en">عنوان القسم (إنجليزي)</label>
                                                <input type="text" class="form-control" id="services_title_en" name="services_title_en"
                                                       value="{{ old('services_title_en', $settings['services_title_en'] ?? '') }}" placeholder="Our Services">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_description_ar">وصف القسم (عربي)</label>
                                                <textarea class="form-control" id="services_description_ar" name="services_description_ar" rows="3">{{ old('services_description_ar', $settings['services_description_ar'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="services_description_en">وصف القسم (إنجليزي)</label>
                                                <textarea class="form-control" id="services_description_en" name="services_description_en" rows="3">{{ old('services_description_en', $settings['services_description_en'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_now_button_text_ar">نص زر الطلب الآن (عربي)</label>
                                                <input type="text" class="form-control" id="order_now_button_text_ar" name="order_now_button_text_ar"
                                                       value="{{ old('order_now_button_text_ar', $settings['order_now_button_text_ar'] ?? '') }}" placeholder="اطلب الآن">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="order_now_button_text_en">نص زر الطلب الآن (إنجليزي)</label>
                                                <input type="text" class="form-control" id="order_now_button_text_en" name="order_now_button_text_en"
                                                       value="{{ old('order_now_button_text_en', $settings['order_now_button_text_en'] ?? '') }}" placeholder="Order Now">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="saudi_riyal_text_ar">نص العملة (عربي)</label>
                                                <input type="text" class="form-control" id="saudi_riyal_text_ar" name="saudi_riyal_text_ar"
                                                       value="{{ old('saudi_riyal_text_ar', $settings['saudi_riyal_text_ar'] ?? '') }}" placeholder="ريال سعودي">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="saudi_riyal_text_en">نص العملة (إنجليزي)</label>
                                                <input type="text" class="form-control" id="saudi_riyal_text_en" name="saudi_riyal_text_en"
                                                       value="{{ old('saudi_riyal_text_en', $settings['saudi_riyal_text_en'] ?? '') }}" placeholder="Saudi Riyal">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- قسم التواصل -->
                            <div class="section-card">
                                <div class="section-header">
                                    <h4>نصوص قسم التواصل</h4>
                                </div>
                                <div class="section-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_title_ar">عنوان قسم التواصل (عربي)</label>
                                                <input type="text" class="form-control" id="contact_us_title_ar" name="contact_us_title_ar"
                                                       value="{{ old('contact_us_title_ar', $settings['contact_us_title_ar'] ?? '') }}" placeholder="تواصل معنا">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_title_en">عنوان قسم التواصل (إنجليزي)</label>
                                                <input type="text" class="form-control" id="contact_us_title_en" name="contact_us_title_en"
                                                       value="{{ old('contact_us_title_en', $settings['contact_us_title_en'] ?? '') }}" placeholder="Contact Us">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_description_ar">وصف قسم التواصل (عربي)</label>
                                                <textarea class="form-control" id="contact_us_description_ar" name="contact_us_description_ar" rows="3">{{ old('contact_us_description_ar', $settings['contact_us_description_ar'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_us_description_en">وصف قسم التواصل (إنجليزي)</label>
                                                <textarea class="form-control" id="contact_us_description_en" name="contact_us_description_en" rows="3">{{ old('contact_us_description_en', $settings['contact_us_description_en'] ?? '') }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_information_title_ar">عنوان معلومات الاتصال (عربي)</label>
                                                <input type="text" class="form-control" id="contact_information_title_ar" name="contact_information_title_ar"
                                                       value="{{ old('contact_information_title_ar', $settings['contact_information_title_ar'] ?? '') }}" placeholder="معلومات الاتصال">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="contact_information_title_en">عنوان معلومات الاتصال (إنجليزي)</label>
                                                <input type="text" class="form-control" id="contact_information_title_en" name="contact_information_title_en"
                                                       value="{{ old('contact_information_title_en', $settings['contact_information_title_en'] ?? '') }}" placeholder="Contact Information">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="send_us_message_title_ar">عنوان أرسل رسالة (عربي)</label>
                                                <input type="text" class="form-control" id="send_us_message_title_ar" name="send_us_message_title_ar"
                                                       value="{{ old('send_us_message_title_ar', $settings['send_us_message_title_ar'] ?? '') }}" placeholder="أرسل لنا رسالة">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="send_us_message_title_en">عنوان أرسل رسالة (إنجليزي)</label>
                                                <input type="text" class="form-control" id="send_us_message_title_en" name="send_us_message_title_en"
                                                       value="{{ old('send_us_message_title_en', $settings['send_us_message_title_en'] ?? '') }}" placeholder="Send Us a Message">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="text-center mt-4 mb-4">
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
function deleteHeroVideo() {
    if (confirm('هل تريد إزالة الفيديو المرفوع والرجوع للفيديو الافتراضي؟')) {
        fetch('{{ route("admin.content-management.delete-file") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ field: 'hero_video' })
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
