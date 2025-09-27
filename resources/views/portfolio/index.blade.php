@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'أعمالنا' : 'Our Work')

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h1 class="text-3xl md:text-4xl font-bold text-primary">
                    {{ app()->getLocale() === 'ar' ? 'أعمالنا' : 'Our Work' }}
                </h1>
                <p class="lead text-muted">
                    {{ app()->getLocale() === 'ar' ? 'تعرف على بعض أعمالنا وإنجازاتنا في مجال الخير والعطاء' : 'Discover some of our work and achievements in the field of charity and giving' }}
                </p>
            </div>
        </div>

        @if($portfolioItems->count() > 0)
            <div class="row">
                @foreach($portfolioItems as $item)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100 shadow-sm">
                            <div class="card-img-top position-relative" style="height: 250px; overflow: hidden;">
                                @if($item->type === 'image')
                                    <img src="{{ $item->file_path }}" alt="{{ $item->title }}" 
                                         class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <video class="w-100 h-100" style="object-fit: cover;" controls>
                                        <source src="{{ $item->file_path }}" type="video/mp4">
                                        {{ app()->getLocale() === 'ar' ? 'متصفحك لا يدعم تشغيل الفيديو' : 'Your browser does not support video playback' }}
                                    </video>
                                @endif
                                <div class="position-absolute top-0 end-0 m-2">
                                    <span class="badge badge-{{ $item->type === 'image' ? 'info' : 'warning' }} px-3 py-2">
                                        <i class="fas fa-{{ $item->type === 'image' ? 'image' : 'video' }}"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title font-weight-bold">{{ $item->title }}</h5>
                                @if($item->description)
                                    <p class="card-text text-muted flex-grow-1">{{ $item->description }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="row">
                <div class="col-12 text-center">
                    <div class="py-5">
                        <i class="fas fa-images fa-5x text-muted mb-4"></i>
                        <h3 class="text-muted">
                            {{ app()->getLocale() === 'ar' ? 'لا توجد أعمال متاحة حالياً' : 'No work available at the moment' }}
                        </h3>
                        <p class="text-muted">
                            {{ app()->getLocale() === 'ar' ? 'سنقوم بإضافة أعمالنا قريباً' : 'We will add our work soon' }}
                        </p>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15) !important;
}

.card-img-top {
    transition: transform 0.3s ease;
}

.card:hover .card-img-top {
    transform: scale(1.05);
}

.badge {
    font-size: 0.8rem;
    backdrop-filter: blur(10px);
    background-color: rgba(255, 255, 255, 0.9) !important;
    color: #333 !important;
}
</style>
@endsection
