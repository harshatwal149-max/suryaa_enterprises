{{--
    Meta tags, social cards and site-wide structured data.
    Pages set: @section('title'), @section('meta_description'), and optionally
    @section('robots'), @section('canonical'), @section('og_type'), @section('og_image'),
    plus extra JSON-LD via @push('schema').
--}}
@use('App\Support\Seo')
@php
$seoSettings = Seo::settings();
$seoSiteName = Seo::siteName();

// @section('x', 'value') stores escaped text, so decode before it is escaped again on output.
$sectionText = fn (string $name) => trim(html_entity_decode($__env->yieldContent($name), ENT_QUOTES | ENT_HTML5, 'UTF-8'));

$pageTitle = $sectionText('title');
// Brand suffix only when it still fits in Google's ~65 character title width.
$fullTitle = match (true) {
    $pageTitle === '' || request()->routeIs('home') => $seoSettings?->seo_title ?: Seo::DEFAULT_TITLE,
    \Illuminate\Support\Str::contains($pageTitle, $seoSiteName),
    \Illuminate\Support\Str::length($pageTitle . ' | ' . $seoSiteName) > 65 => $pageTitle,
    default => $pageTitle . ' | ' . $seoSiteName,
};

$metaDescription = Seo::excerpt(
    $sectionText('meta_description') ?: ($seoSettings?->seo_description ?: Seo::DEFAULT_DESCRIPTION)
);

$metaRobots = $sectionText('robots') ?: ($seoSettings?->default_meta_robots ?: 'index,follow');
$metaRobots = str_replace(' ', '', $metaRobots);
if (\Illuminate\Support\Str::startsWith($metaRobots, 'index')) {
    // Allow large image previews and full snippets in search results.
    $metaRobots .= ',max-image-preview:large,max-snippet:-1,max-video-preview:-1';
}

$canonicalUrl = $sectionText('canonical') ?: url()->current();
$ogType = $sectionText('og_type') ?: 'website';
$ogImage = $sectionText('og_image') ?: Seo::defaultImageUrl();
@endphp

<title>{{ $fullTitle }}</title>

@if ($metaDescription !== '')
<meta name="description" content="{{ $metaDescription }}">
@endif

<meta name="keywords" content="{{ $seoSettings?->seo_keywords ?: Seo::DEFAULT_KEYWORDS }}">
<meta name="geo.region" content="IN-HP">
<meta name="geo.placename" content="{{ Seo::AREA }}">

<meta name="robots" content="{{ $metaRobots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">
<meta name="author" content="{{ $seoSiteName }}">
<meta name="theme-color" content="{{ $seoSettings?->primary_color ?: '#047857' }}">
<meta name="format-detection" content="telephone=yes">

{{-- Open Graph (Facebook, WhatsApp, LinkedIn) --}}
<meta property="og:locale" content="en_IN">
<meta property="og:type" content="{{ $ogType }}">
<meta property="og:site_name" content="{{ $seoSiteName }}">
<meta property="og:title" content="{{ $fullTitle }}">
@if ($metaDescription !== '')
<meta property="og:description" content="{{ $metaDescription }}">
@endif
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:image" content="{{ $ogImage }}">
<meta property="og:image:alt" content="{{ $pageTitle ?: $seoSiteName }}">

{{-- Twitter / X --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $fullTitle }}">
@if ($metaDescription !== '')
<meta name="twitter:description" content="{{ $metaDescription }}">
@endif
<meta name="twitter:image" content="{{ $ogImage }}">

<link rel="sitemap" type="application/xml" title="Sitemap" href="{{ route('sitemap') }}">

{{-- Structured data present on every page --}}
{{ Seo::jsonLd(Seo::organization(), Seo::website()) }}

@stack('schema')
