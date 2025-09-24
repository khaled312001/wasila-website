@extends('layouts.app')

@section('title', app()->getLocale() === 'ar' ? 'خدمات وسيلة' : 'Wasila Services')

@push('head')
<x-seo 
    title="{{ app()->getLocale() === 'ar' ? 'خدمات وسيلة الخيرية' : 'Wasila Charity Services' }}"
    description="{{ app()->getLocale() === 'ar' 
        ? 'اكتشف مجموعة متنوعة من الخدمات الخيرية والاجتماعية التي تقدمها وسيلة للمجتمع. خدمات إنسانية متكاملة لبناء مجتمع أفضل.'
        : 'Discover our diverse range of charitable and social services for the community. Comprehensive humanitarian services for a better society.' }}"
    keywords="{{ app()->getLocale() === 'ar' 
        ? 'خدمات وسيلة, خدمات خيرية, خدمات اجتماعية, مساعدات إنسانية, تطوع, إغاثة, دعم المجتمع'
        : 'wasila services, charity services, social services, humanitarian aid, volunteer, relief, community support' }}"
    image="{{ asset('images/logo-arabic.png') }}"
    url="{{ url('/services') }}"
    type="website"
    author="وسيلة الخيرية"
/>
@endpush

@section('content')
<!-- Services Header -->
<section class="gradient-bg text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center">
            <h1 class="text-2xl md:text-3xl font-bold mb-6">
                {{ app()->getLocale() === 'ar' ? 'خدمات وسيلة' : 'Wasila Services' }}
            </h1>
            <p class="text-base md:text-lg text-gray-200 max-w-3xl mx-auto">
                {{ app()->getLocale() === 'ar' 
                    ? 'اكتشف مجموعة متنوعة من الخدمات الخيرية والاجتماعية التي نقدمها للمجتمع'
                    : 'Discover our diverse range of charitable and social services for the community' }}
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
                <h2 class="text-xl md:text-2xl font-bold text-primary-dark mb-8 text-center">
                    {{ $category }}
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach($categoryServices as $service)
                    <div class="bg-white rounded-lg shadow-lg card-shadow overflow-hidden hover:shadow-xl transition duration-300">
                        @if($service->image)
                        <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name_ar }} - خدمة خيرية من وسيلة" class="w-full h-48 object-cover" loading="lazy">
                        @else
                        <div class="w-full h-48 bg-gradient-to-br from-primary-light to-primary-medium flex items-center justify-center">
                            <svg class="w-16 h-16 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        @endif
                        
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-primary-dark mb-3">{{ $service->name }}</h3>
                            <p class="text-sm text-gray-600 mb-4 line-clamp-3">{{ $service->description }}</p>
                            
                            <div class="flex justify-between items-center mb-4">
                                <span class="text-xl font-bold text-accent">{{ number_format($service->price, 2) }} {{ app()->getLocale() === 'ar' ? 'ريال' : 'SAR' }}</span>
                            </div>
                            
                            <a href="{{ route('orders.create') }}?service_id={{ $service->id }}&service_name={{ urlencode($service->name) }}&service_price={{ $service->price }}&service_description={{ urlencode($service->description) }}" 
                               class="btn-primary text-white px-6 py-2 rounded-lg font-semibold w-full text-center block hover:shadow-lg transition duration-300">
                                {{ app()->getLocale() === 'ar' ? 'اطلب الآن' : 'Order Now' }}
                            </a>
                        </div>
                    </div>
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
            <h3 class="text-lg font-semibold text-gray-600 mb-4">
                {{ app()->getLocale() === 'ar' ? 'لا توجد خدمات متاحة حالياً' : 'No Services Available' }}
            </h3>
            <p class="text-base text-gray-500">
                {{ app()->getLocale() === 'ar' ? 'سيتم إضافة الخدمات قريباً' : 'Services will be added soon' }}
            </p>
        </div>
        @endif
    </div>
</section>

@endsection

