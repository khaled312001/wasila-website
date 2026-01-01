@extends('admin.layouts.app')

@section('title', 'إدارة معرض الأعمال')

@push('styles')
<link href="{{ asset('css/admin-custom.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="portfolio-container">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-12 col-xl-10">
                <div class="portfolio-card">
                    <div class="portfolio-header">
                        <h1>🎨 إدارة معرض الأعمال</h1>
                        <p>أضف وأدر عناصر معرض الأعمال من الصور والفيديوهات</p>
                        <div class="mt-3 d-flex gap-2 flex-wrap">
                            <a href="{{ route('admin.portfolio.create') }}" class="btn-add-new">
                                <i class="fas fa-plus"></i> إضافة عنصر جديد
                            </a>
                            <form method="POST" action="{{ route('admin.portfolio.add-all-images') }}" class="d-inline" onsubmit="return confirm('هل تريد إضافة جميع الصور الموجودة في مجلد portfolio إلى قاعدة البيانات؟')">
                                @csrf
                                <button type="submit" class="btn-add-new" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%);">
                                    <i class="fas fa-images"></i> إضافة جميع الصور من المجلد
                                </button>
                            </form>
                        </div>
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

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ session('error') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        @if(session('errors') && is_array(session('errors')) && count(session('errors')) > 0)
                            <div class="alert alert-warning alert-dismissible fade show m-4" role="alert">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <strong>تحذيرات:</strong>
                                <ul class="mb-0 mt-2">
                                    @foreach(session('errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="portfolio-table p-4">
                            <div class="table-responsive">
                                <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>الصورة/الفيديو</th>
                                    <th>العنوان</th>
                                    <th>النوع</th>
                                    <th>ترتيب العرض</th>
                                    <th>الحالة</th>
                                    <th>تاريخ الإنشاء</th>
                                    <th>الإجراءات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($portfolioItems as $item)
                                    <tr>
                                        <td>
                                            @php
                                                // Clean file path and get URL
                                                $cleanFilePath = str_replace('storage/', '', $item->file_path);
                                                $fileUrl = \Storage::disk('public')->url($cleanFilePath);
                                                $fileExists = \Storage::disk('public')->exists($cleanFilePath) || file_exists(storage_path('app/public/' . $cleanFilePath));
                                            @endphp
                                            @if($item->type === 'image')
                                                @if($fileExists)
                                                    <img src="{{ $fileUrl }}" alt="{{ $item->title_ar }}" 
                                                         class="media-preview" 
                                                         onerror="this.onerror=null; this.src='{{ asset('images/placeholder-portfolio.png') }}';">
                                                @else
                                                    <div class="media-preview-placeholder">
                                                        <i class="fas fa-image fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            @else
                                                @if($fileExists)
                                                    <video class="media-preview" controls>
                                                        <source src="{{ $fileUrl }}" type="video/mp4">
                                                        <source src="{{ $fileUrl }}" type="video/webm">
                                                    </video>
                                                @else
                                                    <div class="media-preview-placeholder">
                                                        <i class="fas fa-video fa-2x text-muted"></i>
                                                    </div>
                                                @endif
                                            @endif
                                        </td>
                                        <td>
                                            <div class="item-title">{{ $item->title_ar }}</div>
                                            <div class="item-subtitle">{{ $item->title_en }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->type === 'image' ? 'info' : 'warning' }}" 
                                                  style="background: linear-gradient(135deg, {{ $item->type === 'image' ? '#08788B' : '#fbbf24' }} 0%, {{ $item->type === 'image' ? '#3CA6B4' : '#f59e0b' }} 100%); 
                                                         color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-{{ $item->type === 'image' ? 'image' : 'video' }}"></i>
                                                {{ $item->type === 'image' ? 'صورة' : 'فيديو' }}
                                            </span>
                                        </td>
                                        <td>
                                            <span style="font-weight: 600; color: #08788B; font-size: 1.1rem;">{{ $item->sort_order }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}" 
                                                  style="background: linear-gradient(135deg, {{ $item->is_active ? '#28a745' : '#dc3545' }} 0%, {{ $item->is_active ? '#20c997' : '#c82333' }} 100%); 
                                                         color: white; padding: 0.5rem 1rem; border-radius: 20px; font-weight: 600; display: inline-flex; align-items: center; gap: 0.5rem;">
                                                <i class="fas fa-{{ $item->is_active ? 'check-circle' : 'times-circle' }}"></i>
                                                {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td style="color: #6c757d; font-size: 0.9rem;">{{ $item->created_at->format('H:i Y-m-d') }}</td>
                                        <td>
                                            <div class="btn-group" role="group" style="display: flex; gap: 0.5rem;">
                                                <a href="{{ route('admin.portfolio.edit', $item) }}" 
                                                   class="btn btn-sm btn-primary"
                                                   style="background: linear-gradient(135deg, #08788B 0%, #3CA6B4 100%); border: none; border-radius: 8px; padding: 0.5rem 1rem; box-shadow: 0 2px 8px rgba(8, 120, 139, 0.3); color: white;">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.portfolio.destroy', $item) }}" method="POST" 
                                                      style="display: inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger"
                                                            style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); border: none; border-radius: 8px; padding: 0.5rem 1rem; box-shadow: 0 2px 8px rgba(220, 53, 69, 0.3); color: white;">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="empty-state">
                                            <i class="fas fa-images"></i>
                                            <h3>لا توجد عناصر في المعرض</h3>
                                            <p>ابدأ بإضافة عناصر جديدة لعرض أعمالك</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
