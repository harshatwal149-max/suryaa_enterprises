@extends('layouts.frontend')

@php
$seoDescription = \App\Support\Seo::productDescription($product);
@endphp

@section('title', \App\Support\Seo::productTitle($product))
@section('meta_description', $seoDescription)
@section('canonical', route('products.show', ['slug' => $product->slug]))
@section('og_type', 'product')
@if ($product->image)
@section('og_image', \App\Support\Seo::storageUrl($product->image))
@endif

@push('schema')
{{ \App\Support\Seo::jsonLd(
    \App\Support\Seo::webPage('ItemPage', $product->name, \App\Support\Seo::excerpt($seoDescription), [
        'mainEntity' => ['@id' => route('products.show', ['slug' => $product->slug]) . '#product'],
    ]),
    \App\Support\Seo::product($product),
    \App\Support\Seo::breadcrumbs(array_filter([
        'Home' => route('home'),
        'Products' => route('products.index'),
        ($product->category?->name ?? '') => $product->category ? route('products.index', ['category' => $product->category_id]) : null,
        $product->name => route('products.show', ['slug' => $product->slug]),
    ], fn ($url, $label) => $url && $label !== '', ARRAY_FILTER_USE_BOTH))
) }}
@endpush

@section('content')
@php
$galleryImages = collect([$product->image])
    ->merge($product->images->pluck('image_path'))
    ->filter()
    ->unique()
    ->values()
    ->map(fn ($path) => asset('storage/' . ltrim($path, '/')));

$showPrice = $product->priceVisible();
$hasDiscount = $product->hasVisibleDiscount();
$currentPrice = (float) ($hasDiscount ? $product->discount_price : $product->price);
$discountPercent = $hasDiscount
    ? (int) round((1 - (float) $product->discount_price / (float) $product->price) * 100)
    : 0;
$inStock = (int) $product->quantity > 0;
$categoryName = $product->category ? \Illuminate\Support\Str::title($product->category->name) : null;
$productUrl = route('products.show', ['slug' => $product->slug]);
$telHref = $phoneNumber !== '' ? 'tel:' . preg_replace('/[^\d+]/', '', $phoneNumber) : null;

$whatsappMessage = 'Hello ' . \App\Support\Seo::siteName() . ', I am interested in ' . $product->name
    . ($product->sku ? ' (SKU: ' . $product->sku . ')' : '')
    . '. Please share the best price, availability and installation details. ' . $productUrl;

$defaultEnquiry = 'Hi, I am interested in ' . $product->name
    . '. Please share the best price, availability and installation details.';

$specs = array_filter([
    'Product Code' => $product->sku,
    'Brand' => $product->brand,
    'Capacity / Size' => $product->size,
    'Category' => $categoryName,
    'Subcategory' => $product->subcategory?->name,
    'Availability' => $inStock ? 'In Stock' : 'Out of Stock – enquire for delivery date',
]);
@endphp

{{-- PAGE BANNER (matches the products listing banner) --}}
<section class="pd-banner">
    <div class="pd-container">
        <nav class="pd-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <a href="{{ route('products.index') }}">Products</a>
            @if ($product->category)
            <span aria-hidden="true">/</span>
            <a href="{{ route('products.index', ['category' => $product->category_id]) }}">{{ $categoryName }}</a>
            @endif
            <span aria-hidden="true">/</span>
            <span aria-current="page" class="pd-breadcrumb-current">{{ $product->name }}</span>
        </nav>
        {{-- Visual heading only; the product name below stays the page's single <h1>. --}}
        <p class="pd-banner-title">{{ $categoryName ?? 'Solar Products' }}</p>
        <p class="pd-banner-text">
            {{ $product->category?->description
                ? \App\Support\Seo::excerpt($product->category->description, 160)
                : 'Genuine solar products with expert installation and after-sales support.' }}
        </p>
    </div>
</section>

{{-- PRODUCT HERO --}}
<section class="pd-main">
    <div class="pd-container pd-grid">

        {{-- Gallery --}}
        <div class="pd-gallery">
            <div class="pd-stage">
                @if ($galleryImages->isNotEmpty())
                <img id="pd-main-image" src="{{ $galleryImages->first() }}" alt="{{ $product->name }}" fetchpriority="high">
                @else
                <div class="pd-stage-empty" aria-hidden="true"><i class="fa-solid fa-solar-panel"></i></div>
                @endif

                @if ($discountPercent > 0)
                <span class="pd-badge pd-badge-sale">{{ $discountPercent }}% OFF</span>
                @endif

                @if ($galleryImages->count() > 1)
                <button type="button" class="pd-arrow pd-arrow-prev" data-gallery-prev aria-label="Previous image"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                <button type="button" class="pd-arrow pd-arrow-next" data-gallery-next aria-label="Next image"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
                <span class="pd-counter" id="pd-counter">1 / {{ $galleryImages->count() }}</span>
                @endif
            </div>

            @if ($galleryImages->count() > 1)
            <div class="pd-thumbs" role="list">
                @foreach ($galleryImages as $imageUrl)
                <button type="button"
                    class="pd-thumb {{ $loop->first ? 'is-active' : '' }}"
                    data-gallery-thumb="{{ $loop->index }}"
                    data-src="{{ $imageUrl }}"
                    aria-label="Show image {{ $loop->iteration }}"
                    aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                    <img src="{{ $imageUrl }}" alt="" loading="lazy">
                </button>
                @endforeach
            </div>
            @endif
        </div>

        {{-- Summary --}}
        <div class="pd-summary">
            <div class="pd-tags">
                @if ($categoryName)
                <a href="{{ route('products.index', ['category' => $product->category_id]) }}" class="pd-tag">{{ $categoryName }}</a>
                @endif
                @if ($product->brand)
                <span class="pd-tag pd-tag-muted"><i class="fa-solid fa-certificate" aria-hidden="true"></i> {{ $product->brand }}</span>
                @endif
            </div>

            <h1 class="pd-title">{{ $product->name }}</h1>

            <div class="pd-meta">
                <span class="pd-stock {{ $inStock ? 'is-in' : 'is-out' }}">
                    <span class="pd-dot" aria-hidden="true"></span>
                    {{ $inStock ? 'In Stock' : 'Out of Stock' }}
                </span>
                @if ($product->size)
                <span class="pd-chip"><i class="fa-solid fa-bolt" aria-hidden="true"></i> {{ $product->size }}</span>
                @endif
                @if ($product->sku)
                <span class="pd-sku">SKU: {{ $product->sku }}</span>
                @endif
            </div>

            @if ($showPrice)
            <div class="pd-price">
                <strong>₹{{ number_format($currentPrice) }}</strong>
                @if ($hasDiscount)
                <del>₹{{ number_format((float) $product->price) }}</del>
                <span class="pd-save">Save ₹{{ number_format((float) $product->price - $currentPrice) }}</span>
                @endif
            </div>
            @endif

            @if ($product->description)
            <p class="pd-lead">{{ \App\Support\Seo::excerpt($product->description, 220) }}</p>
            @endif

            {{-- Call to action --}}
            <div class="pd-cta">
                <div class="pd-cta-copy">
                    <h2>Get the best price on this product</h2>
                    <p>Free quote, site survey and expert advice from our solar team. No obligation.</p>
                </div>

                <div class="pd-cta-buttons">
                    <a href="#pd-enquiry" class="pd-btn pd-btn-primary" data-scroll-enquiry>
                        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Send Enquiry
                    </a>

                    @if (!empty($whatsappNumber))
                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($whatsappMessage) }}" class="pd-btn pd-btn-whatsapp" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp
                    </a>
                    @endif

                    @if ($telHref)
                    <a href="{{ $telHref }}" class="pd-btn pd-btn-outline">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i> {{ $phoneNumber }}
                    </a>
                    @endif
                </div>
            </div>

            <ul class="pd-trust">
                <li><i class="fa-solid fa-award" aria-hidden="true"></i><span>ISO 9001:2015 Certified</span></li>
                <li><i class="fa-solid fa-shield-halved" aria-hidden="true"></i><span>Authorised Super Solar Distributor</span></li>
                <li><i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i><span>Expert Installation</span></li>
                <li><i class="fa-solid fa-headset" aria-hidden="true"></i><span>After-Sales Support</span></li>
            </ul>
        </div>
    </div>
</section>

{{-- DETAILS + ENQUIRY FORM --}}
<section class="pd-details">
    <div class="pd-container pd-details-grid">
        <div class="pd-info">
            @if ($product->description)
            <div class="pd-card">
                <h2 class="pd-card-title"><i class="fa-solid fa-align-left" aria-hidden="true"></i> Product Overview</h2>
                <div class="pd-description">{!! nl2br(e($product->description)) !!}</div>
            </div>
            @endif

            <div class="pd-card">
                <h2 class="pd-card-title"><i class="fa-solid fa-list-check" aria-hidden="true"></i> Specifications</h2>
                <dl class="pd-specs">
                    @foreach ($specs as $label => $value)
                    <div>
                        <dt>{{ $label }}</dt>
                        <dd>{{ $value }}</dd>
                    </div>
                    @endforeach
                </dl>
            </div>

            <div class="pd-card pd-steps">
                <h2 class="pd-card-title"><i class="fa-solid fa-route" aria-hidden="true"></i> How it works</h2>
                <ol>
                    <li><span>1</span><div><strong>Send your enquiry</strong><p>Share your requirement using the form or WhatsApp.</p></div></li>
                    <li><span>2</span><div><strong>Get expert advice</strong><p>We suggest the right capacity and share the best quote.</p></div></li>
                    <li><span>3</span><div><strong>Delivery &amp; installation</strong><p>Our trained team installs it and supports you after sales.</p></div></li>
                </ol>
            </div>
        </div>

        <aside class="pd-enquiry" id="pd-enquiry" aria-labelledby="pd-enquiry-title">
            <div class="pd-enquiry-card">
                <div class="pd-enquiry-head">
                    <span class="pd-enquiry-icon" aria-hidden="true"><i class="fa-solid fa-solar-panel"></i></span>
                    <div>
                        <h2 id="pd-enquiry-title">Enquire about this product</h2>
                        <p>Tell us what you need and our solar expert will call you back with the best price.</p>
                    </div>
                </div>

                <div class="pd-enquiry-product">
                    @if ($galleryImages->isNotEmpty())
                    <img src="{{ $galleryImages->first() }}" alt="" loading="lazy">
                    @endif
                    <div>
                        <strong>{{ $product->name }}</strong>
                        @if ($product->size)<small>{{ $product->size }}</small>@endif
                    </div>
                </div>

                <form action="{{ route('enquiry.store') }}" method="POST" class="pd-form" id="pd-enquiry-form" novalidate>
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    {{-- Honeypot for spam bots; hidden from people and screen readers --}}
                    <div class="pd-hp" aria-hidden="true">
                        <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                    </div>

                    <div class="pd-field">
                        <label for="pd-name">Your Name <span aria-hidden="true">*</span></label>
                        <input type="text" id="pd-name" name="name" required maxlength="100" autocomplete="name" placeholder="e.g. Rahul Sharma">
                        <span class="pd-error" data-error-for="name"></span>
                    </div>

                    <div class="pd-field">
                        <label for="pd-phone">Phone Number <span aria-hidden="true">*</span></label>
                        <input type="tel" id="pd-phone" name="phone" required maxlength="20" autocomplete="tel" inputmode="tel" placeholder="10-digit mobile number">
                        <span class="pd-error" data-error-for="phone"></span>
                    </div>

                    <div class="pd-field">
                        <label for="pd-email">Email <small>(optional)</small></label>
                        <input type="email" id="pd-email" name="email" maxlength="255" autocomplete="email" placeholder="you@example.com">
                        <span class="pd-error" data-error-for="email"></span>
                    </div>

                    <div class="pd-field">
                        <label for="pd-message">Your Requirement <span aria-hidden="true">*</span></label>
                        <textarea id="pd-message" name="message" required rows="4" maxlength="5000">{{ $defaultEnquiry }}</textarea>
                        <span class="pd-error" data-error-for="message"></span>
                    </div>

                    <button type="submit" class="pd-btn pd-btn-primary pd-submit">
                        <span class="pd-submit-text"><i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Get Best Price</span>
                        <span class="pd-submit-loading" aria-hidden="true"><i class="fa-solid fa-spinner fa-spin"></i> Sending…</span>
                    </button>

                    <p class="pd-privacy"><i class="fa-solid fa-lock" aria-hidden="true"></i> Your details are safe with us. No spam, ever.</p>
                </form>

                <div class="pd-success" id="pd-enquiry-success" hidden tabindex="-1">
                    <span class="pd-success-icon" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
                    <h3>Thank you! Enquiry sent.</h3>
                    <p>Our solar expert will contact you shortly about <strong>{{ $product->name }}</strong>.</p>
                    @if (!empty($whatsappNumber))
                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($whatsappMessage) }}" class="pd-btn pd-btn-whatsapp" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Need it faster? WhatsApp us
                    </a>
                    @endif
                    <button type="button" class="pd-link-btn" data-enquiry-reset>Send another enquiry</button>
                </div>
            </div>
        </aside>
    </div>
</section>

{{-- RELATED PRODUCTS --}}
@if ($relatedProducts->isNotEmpty())
<section class="pd-related" aria-labelledby="pd-related-title">
    <div class="pd-container">
        <div class="pd-related-head">
            <div>
                <span class="pd-eyebrow">You may also like</span>
                <h2 id="pd-related-title">Related Products</h2>
                <p>More solar solutions{{ $categoryName ? ' from ' . $categoryName : '' }} chosen for you.</p>
            </div>
            <a href="{{ $product->category ? route('products.index', ['category' => $product->category_id]) : route('products.index') }}" class="pd-related-all">
                View all <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="pd-related-grid">
            @foreach ($relatedProducts as $related)
            @include('partials.product-card', ['product' => $related])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- MOBILE ACTION BAR --}}
<div class="pd-mobile-bar" role="region" aria-label="Quick enquiry">
    @if ($telHref)
    <a href="{{ $telHref }}" class="pd-mobile-icon" aria-label="Call us"><i class="fa-solid fa-phone"></i></a>
    @endif
    @if (!empty($whatsappNumber))
    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode($whatsappMessage) }}" class="pd-mobile-icon is-whatsapp" target="_blank" rel="noopener" aria-label="WhatsApp us"><i class="fa-brands fa-whatsapp"></i></a>
    @endif
    <a href="#pd-enquiry" class="pd-btn pd-btn-primary" data-scroll-enquiry>
        <i class="fa-solid fa-paper-plane" aria-hidden="true"></i> Get Best Price
    </a>
</div>
@endsection

@push('styles')
<style>
    .pd-main, .pd-main *, .pd-details, .pd-details *,
    .pd-related, .pd-related *, .pd-mobile-bar, .pd-mobile-bar * {
        box-sizing: border-box;
    }

    .pd-container {
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    /* Banner + breadcrumb: same look as the products listing page */
    .pd-banner {
        padding: 48px 0 52px;
        background:
            radial-gradient(circle at 88% 20%, rgba(251, 191, 36, .28), transparent 34%),
            linear-gradient(120deg, #022c22 0%, #065f46 100%);
        color: #fff;
    }

    .pd-banner *,
    .pd-banner {
        box-sizing: border-box;
    }

    .pd-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        color: rgba(255, 255, 255, .7);
        font-size: 13px;
    }

    .pd-breadcrumb a {
        color: rgba(255, 255, 255, .85) !important;
        text-decoration: none !important;
    }

    .pd-breadcrumb a:hover {
        color: #fbbf24 !important;
    }

    .pd-breadcrumb-current {
        overflow: hidden;
        max-width: 100%;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .pd-banner-title {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(30px, 4vw, 44px);
        font-weight: 800;
        line-height: 1.15;
    }

    .pd-banner-text {
        max-width: 640px;
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 15px;
        line-height: 1.7;
    }

    /* Hero grid */
    .pd-main {
        padding: 36px 0 44px;
        background: #fff;
    }

    .pd-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(0, 1fr);
        gap: 48px;
        align-items: start;
    }

    /* Gallery */
    .pd-gallery {
        position: sticky;
        top: 100px;
    }

    .pd-stage {
        position: relative;
        display: grid;
        aspect-ratio: 1 / 1;
        place-items: center;
        overflow: hidden;
        border: 1px solid #e6ece8;
        border-radius: 20px;
        background: radial-gradient(circle at 50% 35%, #ffffff 0%, #f1f6f3 100%);
    }

    .pd-stage img {
        width: 100%;
        height: 100%;
        padding: 24px;
        object-fit: contain;
        transition: opacity .25s ease, transform .4s ease;
    }

    .pd-stage:hover img {
        transform: scale(1.04);
    }

    .pd-stage img.is-fading {
        opacity: 0;
    }

    .pd-stage-empty {
        color: #b7d9c3;
        font-size: 90px;
    }

    .pd-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        padding: 6px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 800;
    }

    .pd-badge-sale {
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: #fff;
        box-shadow: 0 6px 16px rgba(234, 88, 12, .3);
    }

    .pd-arrow {
        position: absolute;
        top: 50%;
        display: grid;
        width: 42px;
        height: 42px;
        place-items: center;
        border: 1px solid #e2e8f0;
        border-radius: 50%;
        background: rgba(255, 255, 255, .95);
        color: #18291f;
        cursor: pointer;
        box-shadow: 0 6px 16px rgba(0, 0, 0, .08);
        transform: translateY(-50%);
        transition: background .2s ease, color .2s ease;
    }

    .pd-arrow:hover {
        background: #047857;
        color: #fff;
    }

    .pd-arrow-prev { left: 14px; }
    .pd-arrow-next { right: 14px; }

    .pd-counter {
        position: absolute;
        right: 16px;
        bottom: 14px;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(15, 23, 42, .65);
        color: #fff;
        font-size: 12px;
        font-weight: 600;
    }

    .pd-thumbs {
        display: flex;
        gap: 10px;
        margin-top: 14px;
        overflow-x: auto;
        padding-bottom: 4px;
        scrollbar-width: thin;
    }

    .pd-thumb {
        flex: 0 0 76px;
        height: 76px;
        padding: 4px;
        overflow: hidden;
        border: 2px solid #e6ece8;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        transition: border-color .2s ease, transform .2s ease;
    }

    .pd-thumb img {
        width: 100%;
        height: 100%;
        border-radius: 8px;
        object-fit: cover;
    }

    .pd-thumb:hover { transform: translateY(-2px); }

    .pd-thumb.is-active {
        border-color: #047857;
    }

    /* Summary */
    .pd-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
    }

    .pd-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        border-radius: 999px;
        background: #e8f6ee;
        color: #047857 !important;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .3px;
        text-decoration: none !important;
        text-transform: uppercase;
    }

    .pd-tag-muted {
        border: 1px solid #e6ece8;
        background: #fff;
        color: #475569 !important;
        text-transform: none;
    }

    .pd-tag-muted i { color: #f59e0b; }

    .pd-title {
        margin: 0 0 14px;
        color: #12241a;
        font-size: clamp(26px, 3.2vw, 36px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.3px;
    }

    .pd-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 10px 14px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .pd-stock {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        font-weight: 700;
    }

    .pd-stock.is-in { color: #15803d; }
    .pd-stock.is-out { color: #b91c1c; }

    .pd-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: currentColor;
        box-shadow: 0 0 0 4px color-mix(in srgb, currentColor 18%, transparent);
    }

    .pd-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #fff8e8;
        color: #7a5a12;
        font-weight: 700;
    }

    .pd-chip i { color: #f59e0b; }

    .pd-sku {
        color: #64748b;
    }

    .pd-price {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 6px 12px;
        margin-bottom: 18px;
        padding: 16px 18px;
        border-radius: 14px;
        background: #f7faf8;
    }

    .pd-price strong {
        color: #12241a;
        font-size: 32px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .pd-price del {
        color: #94a3b8;
        font-size: 17px;
    }

    .pd-save {
        padding: 4px 10px;
        border-radius: 999px;
        background: #dcfce7;
        color: #15803d;
        font-size: 13px;
        font-weight: 700;
    }

    .pd-lead {
        margin: 0 0 22px;
        color: #475569;
        font-size: 15.5px;
        line-height: 1.75;
    }

    /* CTA */
    .pd-cta {
        padding: 20px;
        border: 1px solid #d5ebdd;
        border-radius: 16px;
        background:
            radial-gradient(circle at 100% 0%, rgba(251, 191, 36, .2), transparent 45%),
            linear-gradient(135deg, #f0faf4, #ffffff);
    }

    .pd-cta-copy h2 {
        margin: 0 0 4px;
        color: #12241a;
        font-size: 18px;
        font-weight: 800;
    }

    .pd-cta-copy p {
        margin: 0 0 16px;
        color: #5b6b63;
        font-size: 14px;
    }

    .pd-cta-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .pd-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 48px;
        padding: 12px 20px;
        border: 1px solid transparent;
        border-radius: 12px;
        font-size: 14.5px;
        font-weight: 700;
        line-height: 1.2;
        text-decoration: none !important;
        cursor: pointer;
        transition: transform .2s ease, box-shadow .2s ease, background .2s ease, filter .2s ease;
    }

    .pd-btn:hover {
        transform: translateY(-2px);
    }

    .pd-btn-primary {
        flex: 1 1 180px;
        background: var(--primary-color, #047857);
        color: #fff !important;
        box-shadow: 0 10px 22px rgba(4, 120, 87, .25);
    }

    .pd-btn-primary:hover {
        filter: brightness(.92);
    }

    .pd-btn-whatsapp {
        background: #25d366;
        color: #fff !important;
        box-shadow: 0 10px 22px rgba(37, 211, 102, .25);
    }

    .pd-btn-outline {
        border-color: #cfe3d6;
        background: #fff;
        color: #12241a !important;
    }

    .pd-btn-outline i { color: #047857; }

    .pd-trust {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 10px;
        margin: 18px 0 0;
        padding: 0;
        list-style: none;
    }

    .pd-trust li {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        border: 1px solid #edf1ee;
        border-radius: 12px;
        color: #334155;
        font-size: 13px;
        font-weight: 600;
    }

    .pd-trust i {
        display: grid;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 10px;
        background: #e8f6ee;
        color: #047857;
    }

    /* Details + enquiry */
    .pd-details {
        padding: 48px 0 56px;
        background: #f6f9f7;
    }

    .pd-details-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 400px;
        gap: 28px;
        align-items: start;
    }

    .pd-info {
        display: grid;
        gap: 20px;
    }

    .pd-card {
        padding: 26px;
        border: 1px solid #e6ece8;
        border-radius: 18px;
        background: #fff;
    }

    .pd-card-title {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 0 0 16px;
        color: #12241a;
        font-size: 19px;
        font-weight: 800;
    }

    .pd-card-title i {
        color: #f59e0b;
        font-size: 16px;
    }

    .pd-description {
        color: #475569;
        font-size: 15px;
        line-height: 1.8;
    }

    .pd-specs {
        display: grid;
        margin: 0;
        overflow: hidden;
        border: 1px solid #edf1ee;
        border-radius: 12px;
    }

    .pd-specs div {
        display: grid;
        grid-template-columns: 180px minmax(0, 1fr);
    }

    .pd-specs div:nth-child(odd) {
        background: #f9fbfa;
    }

    .pd-specs div + div {
        border-top: 1px solid #edf1ee;
    }

    .pd-specs dt,
    .pd-specs dd {
        margin: 0;
        padding: 13px 16px;
        font-size: 14px;
    }

    .pd-specs dt {
        color: #64748b;
        font-weight: 600;
    }

    .pd-specs dd {
        color: #12241a;
        font-weight: 700;
        overflow-wrap: anywhere;
    }

    .pd-steps ol {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .pd-steps li {
        display: flex;
        gap: 12px;
    }

    .pd-steps li > span {
        display: grid;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 50%;
        background: #047857;
        color: #fff;
        font-weight: 800;
    }

    .pd-steps strong {
        display: block;
        margin-bottom: 4px;
        color: #12241a;
        font-size: 14.5px;
    }

    .pd-steps p {
        margin: 0;
        color: #64748b;
        font-size: 13px;
        line-height: 1.55;
    }

    /* Enquiry card */
    .pd-enquiry {
        position: sticky;
        top: 100px;
        scroll-margin-top: 100px;
    }

    .pd-enquiry-card {
        overflow: hidden;
        border: 1px solid #d5ebdd;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 20px 44px rgba(16, 40, 30, .1);
    }

    .pd-enquiry-head {
        display: flex;
        gap: 14px;
        padding: 22px 22px 18px;
        background:
            radial-gradient(circle at 100% 0%, rgba(251, 191, 36, .35), transparent 50%),
            linear-gradient(135deg, #022c22, #065f46);
        color: #fff;
    }

    .pd-enquiry-icon {
        display: grid;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        place-items: center;
        border-radius: 14px;
        background: #fbbf24;
        color: #422006;
        font-size: 20px;
    }

    .pd-enquiry-head h2 {
        margin: 0 0 4px;
        color: #fff;
        font-size: 18px;
        font-weight: 800;
    }

    .pd-enquiry-head p {
        margin: 0;
        color: rgba(255, 255, 255, .8);
        font-size: 13px;
        line-height: 1.5;
    }

    .pd-enquiry-product {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 16px 22px 0;
        padding: 10px;
        border: 1px dashed #cfe3d6;
        border-radius: 12px;
        background: #f7faf8;
    }

    .pd-enquiry-product img {
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        border-radius: 8px;
        background: #fff;
        object-fit: cover;
    }

    .pd-enquiry-product strong {
        display: block;
        color: #12241a;
        font-size: 13.5px;
        line-height: 1.35;
    }

    .pd-enquiry-product small {
        color: #7a5a12;
        font-size: 12px;
        font-weight: 600;
    }

    .pd-form {
        display: grid;
        gap: 14px;
        padding: 18px 22px 22px;
    }

    .pd-hp {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
        overflow: hidden;
    }

    .pd-field label {
        display: block;
        margin-bottom: 6px;
        color: #334155;
        font-size: 13px;
        font-weight: 700;
    }

    .pd-field label span { color: #dc2626; }

    .pd-field label small {
        color: #94a3b8;
        font-weight: 500;
    }

    .pd-field input,
    .pd-field textarea {
        width: 100%;
        padding: 12px 14px;
        border: 1px solid #d8e2dc;
        border-radius: 10px;
        background: #fff;
        color: #12241a;
        font: inherit;
        font-size: 14px;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .pd-field textarea {
        resize: vertical;
        min-height: 96px;
        line-height: 1.55;
    }

    .pd-field input:focus,
    .pd-field textarea:focus {
        border-color: #047857;
        outline: 0;
        box-shadow: 0 0 0 4px rgba(4, 120, 87, .12);
    }

    .pd-field.has-error input,
    .pd-field.has-error textarea {
        border-color: #dc2626;
    }

    .pd-error {
        display: block;
        margin-top: 5px;
        color: #dc2626;
        font-size: 12px;
        font-weight: 600;
    }

    .pd-error:empty { display: none; }

    .pd-submit {
        width: 100%;
        border: 0;
        font-size: 15px;
    }

    .pd-submit-loading { display: none; }
    .pd-submit.is-loading .pd-submit-text { display: none; }
    .pd-submit.is-loading .pd-submit-loading { display: inline-flex; gap: 8px; align-items: center; }

    .pd-submit:disabled {
        cursor: wait;
        opacity: .8;
        transform: none;
    }

    .pd-privacy {
        margin: 0;
        color: #94a3b8;
        font-size: 12px;
        text-align: center;
    }

    .pd-success {
        padding: 34px 24px 28px;
        text-align: center;
    }

    .pd-success:focus { outline: 0; }

    .pd-success-icon {
        display: inline-grid;
        width: 64px;
        height: 64px;
        place-items: center;
        margin-bottom: 14px;
        border-radius: 50%;
        background: #dcfce7;
        color: #15803d;
        font-size: 28px;
        animation: pdPop .4s ease;
    }

    @keyframes pdPop {
        from { transform: scale(.5); opacity: 0; }
        to { transform: scale(1); opacity: 1; }
    }

    .pd-success h3 {
        margin: 0 0 8px;
        color: #12241a;
        font-size: 20px;
    }

    .pd-success p {
        margin: 0 0 18px;
        color: #64748b;
        font-size: 14px;
        line-height: 1.6;
    }

    .pd-link-btn {
        display: block;
        margin: 14px auto 0;
        border: 0;
        background: none;
        color: #047857;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: underline;
    }

    /* Related */
    .pd-related {
        padding: 56px 0 64px;
        background: #fff;
    }

    .pd-related-head {
        display: flex;
        flex-wrap: wrap;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 26px;
    }

    .pd-eyebrow {
        display: inline-block;
        margin-bottom: 6px;
        color: #d97706;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1.4px;
        text-transform: uppercase;
    }

    .pd-related-head h2 {
        margin: 0 0 6px;
        color: #12241a;
        font-size: clamp(24px, 3vw, 32px);
        font-weight: 800;
    }

    .pd-related-head p {
        margin: 0;
        color: #64748b;
        font-size: 15px;
    }

    .pd-related-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border: 1px solid #cfe3d6;
        border-radius: 999px;
        color: #047857 !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease;
    }

    .pd-related-all:hover {
        background: #047857;
        color: #fff !important;
    }

    .pd-related-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 22px;
    }

    /* Mobile action bar */
    .pd-mobile-bar {
        display: none;
    }

    @media (max-width: 1080px) {
        .pd-details-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .pd-enquiry {
            position: static;
        }

        .pd-related-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .pd-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 28px;
        }

        .pd-gallery {
            position: static;
        }

        .pd-stage {
            aspect-ratio: 4 / 3;
        }

        .pd-steps ol {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 640px) {
        .pd-container {
            width: calc(100% - 28px);
        }

        .pd-banner {
            padding: 34px 0 38px;
        }

        .pd-main {
            padding: 20px 0 32px;
        }

        .pd-stage img {
            padding: 14px;
        }

        .pd-arrow {
            width: 36px;
            height: 36px;
        }

        .pd-thumb {
            flex-basis: 62px;
            height: 62px;
        }

        .pd-price strong {
            font-size: 26px;
        }

        .pd-cta-buttons .pd-btn {
            flex: 1 1 100%;
        }

        .pd-trust {
            grid-template-columns: minmax(0, 1fr);
        }

        .pd-card {
            padding: 18px;
        }

        .pd-specs div {
            grid-template-columns: minmax(0, 1fr);
        }

        .pd-specs dt {
            padding-bottom: 0;
        }

        .pd-specs dd {
            padding-top: 4px;
        }

        .pd-enquiry-head,
        .pd-form {
            padding-right: 16px;
            padding-left: 16px;
        }

        .pd-enquiry-product {
            margin-right: 16px;
            margin-left: 16px;
        }

        .pd-related-grid {
            gap: 12px;
        }

        /* Sticky quick-action bar; page gets bottom room so the footer isn't covered */
        .pd-mobile-bar {
            position: fixed;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: 900;
            display: flex;
            gap: 10px;
            padding: 10px 14px calc(10px + env(safe-area-inset-bottom));
            border-top: 1px solid #e6ece8;
            background: rgba(255, 255, 255, .97);
            box-shadow: 0 -8px 24px rgba(0, 0, 0, .08);
            backdrop-filter: blur(8px);
        }

        .pd-mobile-bar .pd-btn {
            flex: 1;
            min-height: 46px;
            box-shadow: none;
        }

        .pd-mobile-icon {
            display: grid;
            flex: 0 0 46px;
            height: 46px;
            place-items: center;
            border: 1px solid #cfe3d6;
            border-radius: 12px;
            color: #047857 !important;
            font-size: 18px;
            text-decoration: none !important;
        }

        .pd-mobile-icon.is-whatsapp {
            border-color: #25d366;
            background: #25d366;
            color: #fff !important;
        }

        body {
            padding-bottom: 70px;
        }

        /* The action bar already has WhatsApp; lift back-to-top above the bar */
        .floating-whatsapp {
            display: none !important;
        }

        .back-to-top {
            bottom: 86px !important;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .pd-stage img,
        .pd-btn,
        .pd-thumb,
        .pd-success-icon {
            transition: none !important;
            animation: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    (function () {
        // Image gallery: thumbnails, arrows, keyboard and swipe.
        var mainImage = document.getElementById('pd-main-image');
        var thumbs = Array.prototype.slice.call(document.querySelectorAll('[data-gallery-thumb]'));
        var counter = document.getElementById('pd-counter');
        var current = 0;

        function show(index) {
            if (!mainImage || thumbs.length < 2) return;
            current = (index + thumbs.length) % thumbs.length;
            var src = thumbs[current].dataset.src;

            mainImage.classList.add('is-fading');
            setTimeout(function () {
                mainImage.src = src;
                mainImage.classList.remove('is-fading');
            }, 150);

            thumbs.forEach(function (thumb, i) {
                thumb.classList.toggle('is-active', i === current);
                thumb.setAttribute('aria-pressed', i === current ? 'true' : 'false');
            });

            thumbs[current].scrollIntoView({ block: 'nearest', inline: 'nearest', behavior: 'smooth' });
            if (counter) counter.textContent = (current + 1) + ' / ' + thumbs.length;
        }

        thumbs.forEach(function (thumb, i) {
            thumb.addEventListener('click', function () { show(i); });
        });

        var prev = document.querySelector('[data-gallery-prev]');
        var next = document.querySelector('[data-gallery-next]');
        if (prev) prev.addEventListener('click', function () { show(current - 1); });
        if (next) next.addEventListener('click', function () { show(current + 1); });

        var stage = document.querySelector('.pd-stage');
        if (stage && thumbs.length > 1) {
            var startX = null;
            stage.addEventListener('touchstart', function (e) { startX = e.touches[0].clientX; }, { passive: true });
            stage.addEventListener('touchend', function (e) {
                if (startX === null) return;
                var dx = e.changedTouches[0].clientX - startX;
                if (Math.abs(dx) > 40) show(current + (dx < 0 ? 1 : -1));
                startX = null;
            });
        }

        // "Send Enquiry" buttons scroll to the form and focus the first field.
        var form = document.getElementById('pd-enquiry-form');
        document.querySelectorAll('[data-scroll-enquiry]').forEach(function (link) {
            link.addEventListener('click', function (e) {
                var target = document.getElementById('pd-enquiry');
                if (!target) return;
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                if (form && !form.hidden) {
                    setTimeout(function () { form.querySelector('[name="name"]').focus({ preventScroll: true }); }, 500);
                }
            });
        });

        // Enquiry form: validate, submit with AJAX, show success state.
        if (!form) return;

        // form.elements, not form.name: "name" is also a built-in property of <form>.
        var field = function (name) { return form.elements.namedItem(name); };
        var success = document.getElementById('pd-enquiry-success');
        var submit = form.querySelector('.pd-submit');
        var defaultMessage = field('message').value;

        function setError(name, message) {
            var slot = form.querySelector('[data-error-for="' + name + '"]');
            if (!slot) return;
            slot.textContent = message || '';
            slot.closest('.pd-field').classList.toggle('has-error', !!message);
        }

        function clearErrors() {
            ['name', 'phone', 'email', 'message'].forEach(function (name) { setError(name, ''); });
        }

        function validate() {
            clearErrors();
            var ok = true;
            var name = field('name').value.trim();
            var phone = field('phone').value.trim();
            var email = field('email').value.trim();
            var message = field('message').value.trim();

            if (name.length < 2) { setError('name', 'Please enter your name.'); ok = false; }
            if (!/^[0-9+\-\s()]{7,20}$/.test(phone) || phone.replace(/\D/g, '').length < 10) {
                setError('phone', 'Please enter a valid 10-digit phone number.'); ok = false;
            }
            if (email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) { setError('email', 'Please enter a valid email address.'); ok = false; }
            if (message.length < 5) { setError('message', 'Please tell us your requirement.'); ok = false; }

            if (!ok) {
                var firstError = form.querySelector('.has-error input, .has-error textarea');
                if (firstError) firstError.focus();
            }
            return ok;
        }

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            if (!validate()) return;

            submit.disabled = true;
            submit.classList.add('is-loading');

            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: new FormData(form)
            })
            .then(function (res) {
                return res.json().catch(function () { return {}; }).then(function (data) {
                    return { status: res.status, data: data };
                });
            })
            .then(function (result) {
                if (result.status === 422 && result.data.errors) {
                    Object.keys(result.data.errors).forEach(function (key) { setError(key, result.data.errors[key][0]); });
                    return;
                }
                if (result.status === 429) {
                    throw new Error('Too many attempts. Please wait a minute and try again.');
                }
                if (!result.data.success) {
                    throw new Error(result.data.message || 'Something went wrong. Please try again.');
                }

                form.hidden = true;
                success.hidden = false;
                success.focus();
            })
            .catch(function (err) {
                if (window.toastr) {
                    toastr.error(err.message || 'Could not send your enquiry. Please try again or WhatsApp us.');
                } else {
                    alert(err.message || 'Could not send your enquiry. Please try again.');
                }
            })
            .finally(function () {
                submit.disabled = false;
                submit.classList.remove('is-loading');
            });
        });

        form.querySelectorAll('input, textarea').forEach(function (field) {
            field.addEventListener('input', function () { setError(field.name, ''); });
        });

        var reset = document.querySelector('[data-enquiry-reset]');
        if (reset) {
            reset.addEventListener('click', function () {
                form.reset();
                field('message').value = defaultMessage;
                clearErrors();
                success.hidden = true;
                form.hidden = false;
                field('name').focus();
            });
        }
    })();
</script>
@endpush
