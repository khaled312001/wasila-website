@extends('customer.layouts.app')

@section('title', __('messages.order_details'))
@section('page-title', __('messages.order_details'))
@section('page-subtitle', __('messages.order_number') . ': #' . $order->order_number)

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Order Details -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Documentation Videos - FIRST SECTION -->
        @if($order->documentation && $order->documentation->where('is_visible_to_customer', true)->count() > 0)
        <div class="dashboard-card bg-gradient-to-br from-white via-blue-50 to-indigo-50 border-2 border-primary-light/30 shadow-2xl relative overflow-hidden">
            <!-- Decorative Background Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-light/10 to-transparent rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-gradient-to-tr from-primary-medium/10 to-transparent rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="bg-gradient-to-br from-primary-medium via-primary-dark to-indigo-600 p-4 rounded-xl shadow-xl transform hover:scale-105 transition-transform duration-300">
                        <i class="fas fa-video text-white text-2xl"></i>
                    </div>
                    <div>
                        <h2 class="text-3xl font-bold text-primary-dark mb-1">{{ __('messages.order_documentation') }}</h2>
                        <p class="text-sm text-gray-600 flex items-center gap-2">
                            <i class="fas fa-info-circle text-primary-medium"></i>
                            فيديوهات توثيق تنفيذ الطلب
                        </p>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-6">
                    @foreach($order->documentation->where('is_visible_to_customer', true) as $doc)
                    <div class="bg-white rounded-xl shadow-lg border-2 border-gray-200 p-6 hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 relative overflow-hidden group">
                        <!-- Hover Effect Background -->
                        <div class="absolute inset-0 bg-gradient-to-br from-primary-light/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="font-bold text-xl text-gray-900 mb-2 flex items-center gap-2">
                                        <i class="fas fa-file-video text-primary-medium text-xl"></i>
                                        {{ $doc->title ?? 'فيديو توثيق' }}
                                    </h3>
                                    @if($doc->description)
                                    <p class="text-sm text-gray-600 mb-3">{{ $doc->description }}</p>
                                    @endif
                                    <div class="flex items-center gap-3 text-xs text-gray-500 flex-wrap">
                                        <span class="flex items-center gap-1 bg-gray-100 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-calendar text-primary-medium"></i>
                                            {{ $doc->created_at->format('Y-m-d H:i') }}
                                        </span>
                                        @if($doc->formatted_file_size)
                                        <span class="flex items-center gap-1 bg-gray-100 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-hdd text-primary-medium"></i>
                                            {{ $doc->formatted_file_size }}
                                        </span>
                                        @endif
                                        @if($doc->formatted_duration)
                                        <span class="flex items-center gap-1 bg-gray-100 px-3 py-1.5 rounded-lg">
                                            <i class="fas fa-clock text-primary-medium"></i>
                                            {{ $doc->formatted_duration }}
                                        </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <a href="{{ $doc->video_url }}" target="_blank" 
                                       class="bg-gradient-to-r from-primary-light to-primary-medium hover:from-primary-medium hover:to-primary-dark text-white p-3 rounded-lg transition-all duration-200 hover:scale-110 shadow-md hover:shadow-lg" title="فتح في نافذة جديدة">
                                        <i class="fas fa-external-link-alt"></i>
                                    </a>
                                    <a href="{{ $doc->video_url }}" download
                                       class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white p-3 rounded-lg transition-all duration-200 hover:scale-110 shadow-md hover:shadow-lg" title="تحميل الفيديو">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @if($doc->video_path)
                            <div class="bg-gradient-to-br from-gray-900 to-gray-800 rounded-xl overflow-hidden shadow-2xl border-2 border-gray-700 relative">
                                <video controls class="w-full" style="max-height: 600px; min-height: 400px;" preload="metadata" playsinline webkit-playsinline data-doc-id="{{ $doc->id }}">
                                    <source src="{{ $doc->video_url }}" type="video/mp4">
                                    <source src="{{ $doc->video_url }}" type="video/webm">
                                    <source src="{{ $doc->video_url }}" type="video/ogg">
                                    <div class="text-white p-8 text-center bg-gray-900">
                                        <i class="fas fa-exclamation-triangle text-4xl mb-4 text-yellow-400"></i>
                                        <p class="text-lg mb-4">متصفحك لا يدعم تشغيل الفيديو</p>
                                        <a href="{{ $doc->video_url }}" class="inline-block bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-200 shadow-lg hover:shadow-xl" download>
                                            <i class="fas fa-download ml-2"></i>
                                            اضغط هنا لتحميل الفيديو
                                        </a>
                                    </div>
                                </video>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Order Info -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.order_information') }}</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.order_number') }}:</span>
                    <span class="font-semibold text-gray-900">#{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.service') }}:</span>
                    <span class="font-semibold text-gray-900">{{ $order->service_name ?? __('messages.service') }}</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.amount') }}:</span>
                    <span class="font-semibold text-green-600 text-lg">{{ number_format($order->total_amount, 2) }} ر.س</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.status') }}:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'processing') bg-blue-100 text-blue-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ __('messages.' . $order->status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-200">
                    <span class="text-gray-600">{{ __('messages.payment_status') }}:</span>
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($order->payment_status === 'paid') bg-green-100 text-green-800
                        @else bg-red-100 text-red-800
                        @endif">
                        {{ __('messages.' . $order->payment_status) }}
                    </span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-gray-600">{{ __('messages.order_date') }}:</span>
                    <span class="font-semibold text-gray-900">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.actions') }}</h2>
            <div class="space-y-3">
                <a href="{{ route('customer.orders.invoice', $order) }}" class="btn-primary w-full text-center block">
                    {{ __('messages.view_invoice') }}
                </a>
                <a href="{{ route('customer.orders.invoice.download', $order) }}" class="bg-green-600 hover:bg-green-700 text-white w-full py-3 px-4 rounded-lg text-center block font-semibold transition-colors">
                    {{ __('messages.download_invoice') }}
                </a>
                <a href="{{ route('customer.messages.index', ['order_id' => $order->id]) }}" class="bg-blue-600 hover:bg-blue-700 text-white w-full py-3 px-4 rounded-lg text-center block font-semibold transition-colors">
                    {{ __('messages.contact_about_order') }}
                </a>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="dashboard-card">
            <h2 class="text-xl font-bold text-gray-800 mb-4">{{ __('messages.customer_information') }}</h2>
            <div class="space-y-3">
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.name') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                </div>
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.email') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_email }}</p>
                </div>
                @if($order->customer_phone)
                <div>
                    <span class="text-sm text-gray-600">{{ __('messages.phone') }}:</span>
                    <p class="font-semibold text-gray-900">{{ $order->customer_phone }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enhance video player experience for customers
    document.addEventListener('DOMContentLoaded', function() {
        const videos = document.querySelectorAll('video');
        videos.forEach(video => {
            // Add error handling
            video.addEventListener('error', function(e) {
                console.error('Video error:', e);
                const errorDiv = document.createElement('div');
                errorDiv.className = 'text-white p-6 text-center bg-red-600 rounded-lg';
                errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle text-3xl mb-3"></i><p class="text-lg">حدث خطأ في تحميل الفيديو</p><p class="text-sm mt-2">يرجى المحاولة مرة أخرى أو تحميل الفيديو</p>';
                video.parentElement.appendChild(errorDiv);
            });
            
            // Track video view
            video.addEventListener('play', function() {
                // Mark video as viewed (optional - can send to server)
                if (video.dataset.docId) {
                    // Could send AJAX request to mark as viewed
                    console.log('Video viewed:', video.dataset.docId);
                }
            });
            
            // Add loading indicator
            video.addEventListener('loadstart', function() {
                const loadingDiv = document.createElement('div');
                loadingDiv.className = 'absolute inset-0 flex items-center justify-center bg-gray-900 bg-opacity-75 rounded-xl';
                loadingDiv.id = 'loading-' + (video.id || Math.random());
                loadingDiv.innerHTML = '<div class="text-white text-center"><i class="fas fa-spinner fa-spin text-4xl mb-3"></i><p>جاري تحميل الفيديو...</p></div>';
                video.parentElement.style.position = 'relative';
                video.parentElement.appendChild(loadingDiv);
            });
            
            // Remove loading indicator when video can play
            video.addEventListener('canplay', function() {
                const loadingDiv = video.parentElement.querySelector('[id^="loading-"]');
                if (loadingDiv) {
                    loadingDiv.style.transition = 'opacity 0.3s';
                    loadingDiv.style.opacity = '0';
                    setTimeout(() => loadingDiv.remove(), 300);
                }
            });
        });
    });
</script>
@endpush
@endsection

