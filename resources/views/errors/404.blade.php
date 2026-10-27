@extends('layouts.frontend')

@php
// Error pages render outside the normal controllers, so load what the page needs here.
$nfCategories = \App\Models\Category::query()
    ->whereHas('products', fn ($query) => $query->where('is_active', true))
    ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
    ->orderBy('name')
    ->get();

$nfProducts = \App\Models\Product::with('category')
    ->where('is_active', true)
    ->latest()
    ->take(4)
    ->get();

$whatsappNumber = \App\Support\Seo::settings()?->whatsappDigits() ?? '';
$nfPhone = \App\Support\Seo::settings()?->phone_primary;
@endphp

@section('title', 'Page Not Found')
@section('meta_description', 'The page you are looking for could not be found. Browse solar products from ' . \App\Support\Seo::siteName() . ' or contact us for help.')
@section('robots', 'noindex,follow')

@section('content')
<section class="nf-hero">
    <div class="nf-container">
        <div class="nf-sun" aria-hidden="true">
            <span class="nf-digit">4</span>
            <span class="nf-sun-core">
                <i class="fa-solid fa-sun"></i>
            </span>
            <span class="nf-digit">4</span>
        </div>

        <span class="nf-eyebrow">Error 404</span>
        <h1>This page is off the grid</h1>
        <p class="nf-lead">
            The page you’re looking for may have been moved, renamed or is no longer available.
            Let’s get you back to clean, reliable solar power.
        </p>

        <form action="{{ route('products.index') }}" method="GET" class="nf-search" role="search">
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input type="search" name="search" placeholder="Search solar products, brand or SKU..." aria-label="Search products">
            <button type="submit">Search</button>
        </form>

        <div class="nf-actions">
            <a href="{{ route('home') }}" class="nf-btn nf-btn-primary">
                <i class="fa-solid fa-house" aria-hidden="true"></i> Back to Home
            </a>
            <a href="{{ route('products.index') }}" class="nf-btn nf-btn-light">
                <i class="fa-solid fa-solar-panel" aria-hidden="true"></i> View Products
            </a>
            <a href="{{ route('contact') }}" class="nf-btn nf-btn-ghost">
                <i class="fa-solid fa-headset" aria-hidden="true"></i> Contact Us
            </a>
        </div>
    </div>
</section>

@if ($nfCategories->isNotEmpty())
<section class="nf-section">
    <div class="nf-container">
        <div class="nf-heading">
            <h2>Browse by category</h2>
            <p>Find the right solar solution for your home, farm or business.</p>
        </div>

        <div class="nf-categories">
            @foreach ($nfCategories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="nf-category">
                <span class="nf-category-icon" aria-hidden="true"><i class="fa-solid fa-bolt"></i></span>
                <span class="nf-category-name">{{ \Illuminate\Support\Str::title($category->name) }}</span>
                <span class="nf-category-count">{{ $category->products_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if ($nfProducts->isNotEmpty())
<section class="nf-section nf-section-alt">
    <div class="nf-container">
        <div class="nf-heading nf-heading-row">
            <div>
                <h2>Popular solar products</h2>
                <p>Our latest products, ready for enquiry.</p>
            </div>
            <a href="{{ route('products.index') }}" class="nf-link">
                View all products <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="nf-products">
            @foreach ($nfProducts as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="nf-help">
    <div class="nf-container nf-help-inner">
        <div>
            <h2>Can’t find what you need?</h2>
            <p>Tell us your requirement and our team will suggest the right product and capacity.</p>
        </div>
        <div class="nf-help-actions">
            @if (filled($nfPhone))
            <a href="tel:{{ preg_replace('/[^\d+]/', '', $nfPhone) }}" class="nf-btn nf-btn-light">
                <i class="fa-solid fa-phone" aria-hidden="true"></i> {{ $nfPhone }}
            </a>
            @endif
            @if ($whatsappNumber !== '')
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Hello, I could not find a page on your website. Can you help me?') }}" class="nf-btn nf-btn-whatsapp" target="_blank" rel="noopener">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp Us
            </a>
            @endif
            <a href="{{ route('enquiry') }}" class="nf-btn nf-btn-primary">
                Send an Enquiry <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    .nf-hero,
    .nf-hero *,
    .nf-section,
    .nf-section *,
    .nf-help,
    .nf-help * {
        box-sizing: border-box;
    }

    .nf-container {
        width: min(1240px, calc(100% - 32px));
        margin: 0 auto;
    }

    /* Hero */
    .nf-hero {
        position: relative;
        overflow: hidden;
        padding: 72px 0 80px;
        background:
            radial-gradient(circle at 50% 0%, rgba(251, 191, 36, .3), transparent 45%),
            linear-gradient(160deg, #022c22 0%, #065f46 100%);
        color: #fff;
        text-align: center;
    }

    .nf-hero::after {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        height: 60px;
        background: linear-gradient(180deg, transparent, rgba(2, 44, 34, .35));
        content: "";
        pointer-events: none;
    }

    .nf-sun {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 14px;
        margin-bottom: 18px;
    }

    .nf-digit {
        font-size: clamp(88px, 16vw, 160px);
        font-weight: 900;
        line-height: 1;
        letter-spacing: -4px;
        background: linear-gradient(180deg, #ffffff 30%, #a7f3d0);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
    }

    .nf-sun-core {
        display: grid;
        width: clamp(84px, 14vw, 140px);
        height: clamp(84px, 14vw, 140px);
        place-items: center;
        border-radius: 50%;
        background: radial-gradient(circle, #fde68a 0%, #fbbf24 55%, #f59e0b 100%);
        box-shadow: 0 0 0 10px rgba(251, 191, 36, .15), 0 0 60px rgba(251, 191, 36, .55);
        color: #92400e;
        font-size: clamp(40px, 7vw, 66px);
    }

    .nf-sun-core i {
        animation: nfSpin 18s linear infinite;
    }

    @keyframes nfSpin {
        to {
            transform: rotate(360deg);
        }
    }

    .nf-eyebrow {
        display: inline-block;
        margin-bottom: 12px;
        padding: 6px 14px;
        border: 1px solid rgba(255, 255, 255, .25);
        border-radius: 999px;
        background: rgba(255, 255, 255, .08);
        color: #fde68a;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
    }

    .nf-hero h1 {
        margin: 0 0 14px;
        color: #fff;
        font-size: clamp(28px, 4.5vw, 44px);
        font-weight: 800;
        line-height: 1.15;
    }

    .nf-lead {
        max-width: 620px;
        margin: 0 auto 28px;
        color: rgba(255, 255, 255, .82);
        font-size: 16px;
        line-height: 1.7;
    }

    .nf-search {
        display: flex;
        align-items: center;
        max-width: 560px;
        margin: 0 auto 26px;
        padding: 6px 6px 6px 18px;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 18px 40px rgba(0, 0, 0, .2);
    }

    .nf-search i {
        color: #94a3b8;
    }

    .nf-search input {
        flex: 1;
        min-width: 0;
        padding: 12px;
        border: 0;
        outline: 0;
        background: transparent;
        color: #1f2937;
        font-size: 15px;
    }

    .nf-search button {
        padding: 12px 22px;
        border: 0;
        border-radius: 10px;
        background: var(--primary-color, #047857);
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: filter .2s ease;
    }

    .nf-search button:hover {
        filter: brightness(.9);
    }

    .nf-actions,
    .nf-help-actions {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 12px;
    }

    .nf-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 46px;
        padding: 12px 20px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        white-space: nowrap;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease;
    }

    .nf-btn:hover {
        transform: translateY(-2px);
    }

    .nf-btn-primary {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006 !important;
        box-shadow: 0 8px 20px rgba(245, 158, 11, .3);
    }

    .nf-btn-light {
        background: #fff;
        color: #065f46 !important;
    }

    .nf-btn-ghost {
        border-color: rgba(255, 255, 255, .35);
        background: transparent;
        color: #fff !important;
    }

    .nf-btn-ghost:hover {
        background: rgba(255, 255, 255, .1);
    }

    .nf-btn-whatsapp {
        background: #25d366;
        color: #fff !important;
    }

    /* Sections */
    .nf-section {
        padding: 64px 0;
        background: #fff;
    }

    .nf-section-alt {
        background: #f6f9f7;
    }

    .nf-heading {
        margin-bottom: 28px;
        text-align: center;
    }

    .nf-heading h2,
    .nf-help h2 {
        margin: 0 0 8px;
        color: #18291f;
        font-size: clamp(22px, 3vw, 30px);
        font-weight: 800;
    }

    .nf-heading p,
    .nf-help p {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .nf-heading-row {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        flex-wrap: wrap;
        text-align: left;
    }

    .nf-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: var(--primary-color, #047857) !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .nf-categories {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 14px;
    }

    .nf-category {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 16px;
        border: 1px solid #e6ece8;
        border-radius: 14px;
        background: #fff;
        color: #18291f !important;
        text-decoration: none !important;
        transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
    }

    .nf-category:hover {
        transform: translateY(-3px);
        border-color: #a7d7b8;
        box-shadow: 0 12px 26px rgba(16, 40, 30, .08);
    }

    .nf-category-icon {
        display: grid;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        place-items: center;
        border-radius: 12px;
        background: #fff8e8;
        color: #f59e0b;
        font-size: 17px;
    }

    .nf-category-name {
        flex: 1;
        min-width: 0;
        font-size: 15px;
        font-weight: 700;
    }

    .nf-category-count {
        padding: 3px 9px;
        border-radius: 999px;
        background: #ecfdf3;
        color: #047857;
        font-size: 12px;
        font-weight: 700;
    }

    .nf-products {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    /* Help strip */
    .nf-help {
        padding: 44px 0;
        background: #fff;
        border-top: 1px solid #edf1ee;
    }

    .nf-help-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        flex-wrap: wrap;
        padding: 28px 32px;
        border-radius: 20px;
        background:
            radial-gradient(circle at 92% 20%, rgba(251, 191, 36, .25), transparent 40%),
            #f0f7f2;
    }

    @media (max-width: 1024px) {
        .nf-products {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 600px) {
        .nf-hero {
            padding: 52px 0 60px;
        }

        .nf-sun {
            gap: 8px;
        }

        .nf-search {
            padding-left: 14px;
        }

        .nf-search button {
            padding: 11px 14px;
        }

        .nf-actions .nf-btn,
        .nf-help-actions .nf-btn {
            flex: 1 1 100%;
        }

        .nf-section {
            padding: 44px 0;
        }

        .nf-products {
            gap: 12px;
        }

        .nf-help-inner {
            padding: 22px 18px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .nf-sun-core i {
            animation: none;
        }

        .nf-btn,
        .nf-category {
            transition: none;
        }
    }
</style>
@endpush
