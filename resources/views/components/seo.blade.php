@props([
    'title' => 'وسيلة - منصة الخير والتبرعات',
    'description' => 'منصة وسيلة الخيرية للتبرعات والخدمات الإنسانية. نساعد في ربط المحتاجين بالمتبرعين لبناء مجتمع أفضل.',
    'keywords' => 'وسيلة, خير, تبرعات, خدمات إنسانية, منصة خيرية, السعودية',
    'image' => null,
    'url' => null,
    'type' => 'website',
    'author' => 'وسيلة الخيرية'
])

@php
    $url = $url ?? request()->url();
    $image = $image ?? asset('images/logo-arabic.png');
    $siteName = 'وسيلة - منصة الخير والتبرعات';
    $currentLocale = app()->getLocale();
@endphp

<!-- Primary Meta Tags -->
<title>{{ $title }} | {{ $siteName }}</title>
<meta name="title" content="{{ $title }}">
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="index, follow">
<meta name="language" content="{{ $currentLocale }}">

<!-- Canonical URL -->
<link rel="canonical" href="{{ $url }}">

<!-- Open Graph / Facebook -->
<meta property="og:type" content="website">
<meta property="og:url" content="{{ $url }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:image" content="{{ $image }}">
<meta property="og:site_name" content="{{ $siteName }}">

<!-- Twitter -->
<meta property="twitter:card" content="summary_large_image">
<meta property="twitter:url" content="{{ $url }}">
<meta property="twitter:title" content="{{ $title }}">
<meta property="twitter:description" content="{{ $description }}">
<meta property="twitter:image" content="{{ $image }}">

<!-- Additional SEO Meta Tags -->
<meta name="theme-color" content="#08788B">
<meta name="geo.region" content="SA">
<meta name="geo.placename" content="Riyadh, Saudi Arabia">

<!-- Favicon and Icons -->
<link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
<link rel="manifest" href="{{ asset('manifest.json') }}">

<!-- Preconnect -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Structured Data -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'Organization',
    'name' => $siteName,
    'alternateName' => 'وسيلة الخيرية',
    'url' => url('/'),
    'logo' => $image,
    'description' => $description,
    'foundingDate' => '2024',
    'address' => [
        '@type' => 'PostalAddress',
        'addressCountry' => 'SA',
        'addressLocality' => 'الرياض',
        'addressRegion' => 'منطقة الرياض'
    ],
    'contactPoint' => [
        '@type' => 'ContactPoint',
        'telephone' => '+966-50-123-4567',
        'contactType' => 'customer service',
        'availableLanguage' => ['Arabic', 'English']
    ],
    'sameAs' => [
        'https://twitter.com/wasila_charity',
        'https://instagram.com/wasila_charity',
        'https://facebook.com/wasila.charity'
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

<!-- NGO Schema -->
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'NGO',
    'name' => $siteName,
    'description' => 'منظمة خيرية غير ربحية تهدف إلى ربط المحتاجين بالمتبرعين وتقديم الخدمات الإنسانية',
    'url' => url('/'),
    'logo' => $image,
    'foundingDate' => '2024',
    'areaServed' => [
        '@type' => 'Country',
        'name' => 'Saudi Arabia'
    ],
    'serviceType' => [
        'خدمات خيرية',
        'تبرعات',
        'مساعدة إنسانية',
        'خدمات اجتماعية'
    ]
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>