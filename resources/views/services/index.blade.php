@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'خدمات وسيلة' : 'Wasila Services')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'خدمات وسيلة' : 'Wasila Services' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'اكتشف مجموعة متنوعة من الخدمات الاجتماعية التي تقدمها وسيلة للمجتمع. خدمات إنسانية متكاملة لبناء مجتمع أفضل.'
        : 'Discover our diverse range of social services for the community. Comprehensive humanitarian services for a better society.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'خدمات وسيلة, خدمات اجتماعية, مساعدات إنسانية, تطوع, إغاثة, دعم المجتمع'
        : 'wasila services, social services, humanitarian aid, volunteer, relief, community support' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/services') }}"
    type="website"
    author="وسيلة"
/>
@endpush

@section('content')
<!-- Services Header -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-lg md:text-xl font-bold mb-6" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.wasila_services_title') }}
            </h1>
            <p class="text-sm md:text-base text-gray-200 max-w-3xl mx-auto" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ __('messages.discover_diverse_range') }}
            </p>
        </div>
    </div>
</section>

<!-- Services Content -->
<section class="py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($services->count() > 0)
            @foreach($services as $category => $categoryServices)
            <div class="mb-16">
                <h2 class="text-base md:text-lg font-bold text-primary-dark mb-8 text-center" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                    {{ $category }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($categoryServices as $service)
                    <a href="{{ route('orders.checkout') }}?service_id={{ $service->id }}&service_name={{ urlencode($service->name) }}&service_price={{ $service->price }}&service_description={{ urlencode($service->description) }}" 
                       class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden hover:shadow-xl transition duration-300 block cursor-pointer h-full flex flex-col">
                        @if($service->image_url)
                        <img src="{{ $service->image_url }}" alt="{{ $service->name }} - {{ __('messages.charity_service_from_wasila') }}" class="w-full h-48 object-cover" loading="lazy" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-48 bg-gradient-to-br from-primary-light to-primary-medium flex items-center justify-center\'><svg class=\'w-16 h-16 text-white\' fill=\'currentColor\' viewBox=\'0 0 20 20\'><path d=\'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z\'/></svg></div>';">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-primary-light to-primary-medium flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="p-6 flex-grow flex flex-col">
                            <h3 class="text-lg font-semibold text-primary-dark mb-3" style="font-family: 'Tajawal', 'Inter', sans-serif;">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3 flex-grow" style="font-family: 'Tajawal', 'Inter', sans-serif; line-height: 1.6;">{{ $service->description }}</p>
                            
                            <div class="flex justify-between items-center mb-4 pt-4 border-t border-gray-200">
                                <span class="text-xl font-bold" style="color: #08788B;">{{ number_format($service->price, 2) }} {{ __('messages.currency') }}</span>
                            </div>
                            
                            <button class="bg-gradient-to-r from-primary-medium to-primary-light hover:from-primary-dark hover:to-primary-medium text-white px-6 py-2 rounded-lg font-semibold w-full text-center block hover:shadow-lg transition duration-300 transform hover:scale-105" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                                {{ __('messages.order_now') }}
                            </button>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endforeach
        @else
        <div class="text-center py-16">
            <div class="text-gray-400 mb-6">
                <svg class="w-24 h-24 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h3 class="text-base font-semibold text-gray-600 mb-4" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ app()->getLocale() === 'ar' ? 'لا توجد خدمات متاحة حالياً' : 'No Services Available' }}
            </h3>
            <p class="text-sm text-gray-500" style="font-family: 'Tajawal', 'Inter', sans-serif;">
                {{ app()->getLocale() === 'ar' ? 'سيتم إضافة الخدمات قريباً' : 'Services will be added soon' }}
            </p>
        </div>
        @endif
    </div>
</section>

@endsection

