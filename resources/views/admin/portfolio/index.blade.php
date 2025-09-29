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
                        <div class="mt-3">
                            <a href="{{ route('admin.portfolio.create') }}" class="btn-add-new">
                                <i class="fas fa-plus"></i> إضافة عنصر جديد
                            </a>
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
                                            @if($item->type === 'image')
                                                <img src="{{ asset('storage/' . $item->file_path) }}" alt="{{ $item->title_ar }}" 
                                                     class="media-preview">
                                            @else
                                                <video class="media-preview" controls>
                                                    <source src="{{ asset('storage/' . $item->file_path) }}" type="video/mp4">
                                                </video>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="item-title">{{ $item->title_ar }}</div>
                                            <div class="item-subtitle">{{ $item->title_en }}</div>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $item->type === 'image' ? 'info' : 'warning' }}">
                                                {{ $item->type === 'image' ? 'صورة' : 'فيديو' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->sort_order }}</td>
                                        <td>
                                            <span class="badge badge-{{ $item->is_active ? 'success' : 'danger' }}">
                                                {{ $item->is_active ? 'نشط' : 'غير نشط' }}
                                            </span>
                                        </td>
                                        <td>{{ $item->created_at->format('Y-m-d H:i') }}</td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.portfolio.edit', $item) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.portfolio.destroy', $item) }}" method="POST" 
                                                      style="display: inline-block;" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger">
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
