@extends('layouts.frontend')

@php
$seoCategory = $categories->firstWhere('id', (int) request('category'));
$seoPage = $products->currentPage();
$seoHeading = $seoCategory ? \Illuminate\Support\Str::title($seoCategory->name) : 'Solar Products';
$seoTitle = $seoCategory ? \App\Support\Seo::categoryTitle($seoCategory) : 'Solar Products in ' . \App\Support\Seo::AREA;

// Search, price, sort and stock filters create near-duplicate pages: keep them out of the index.
$seoIsFiltered = collect(['search', 'min_price', 'max_price', 'on_sale', 'in_stock', 'sort'])
    ->contains(fn ($key) => filled(request($key)));

$seoCanonical = route('products.index', array_filter([
    'category' => $seoCategory?->id,
    'page' => $seoPage > 1 ? $seoPage : null,
]));
@endphp

@section('title', $seoTitle . ($seoPage > 1 ? ' – Page ' . $seoPage : ''))
@section('meta_description', $seoCategory
    ? \App\Support\Seo::categoryDescription($seoCategory)
    : 'Shop ' . $totalProducts . '+ solar products in Kangra, HP: solar water heaters, rooftop solar systems, street lights, fencing & home lighting. Genuine Super Solar dealer.')
@section('canonical', $seoCanonical)
@if ($seoIsFiltered)
@section('robots', 'noindex,follow')
@endif

@push('schema')
{{ \App\Support\Seo::jsonLd(
    \App\Support\Seo::webPage('CollectionPage', $seoHeading, null, [
        'mainEntity' => \App\Support\Seo::itemList($products, ($seoPage - 1) * $products->perPage()),
    ]),
    \App\Support\Seo::breadcrumbs(array_filter([
        'Home' => route('home'),
        'Products' => route('products.index'),
        $seoHeading => $seoCategory ? route('products.index', ['category' => $seoCategory->id]) : null,
    ]))
) }}
@endpush

@section('content')
@php
$selectedCategory = $categories->firstWhere('id', (int) request('category'));

// Active filter chips: label => query keys removed when the chip is cleared.
$activeFilters = [];

if (filled(request('search'))) {
    $activeFilters['Search: “' . request('search') . '”'] = ['search'];
}

if ($selectedCategory) {
    $activeFilters[\Illuminate\Support\Str::title($selectedCategory->name)] = ['category'];
}

if ($pricesVisible && is_numeric(request('min_price'))) {
    $activeFilters['Min ₹' . number_format((float) request('min_price'))] = ['min_price'];
}

if ($pricesVisible && is_numeric(request('max_price'))) {
    $activeFilters['Max ₹' . number_format((float) request('max_price'))] = ['max_price'];
}

if ($pricesVisible && request()->boolean('on_sale')) {
    $activeFilters['On Sale'] = ['on_sale'];
}

if (request()->boolean('in_stock')) {
    $activeFilters['In Stock'] = ['in_stock'];
}
@endphp

{{-- PAGE BANNER --}}
<section class="pl-banner">
    <div class="pl-container">
        <nav class="pl-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Products</span>
        </nav>
        <h1>Solar Products</h1>
        <p>{{ $totalProducts }} solar products across {{ $categories->count() }} categories, from water heaters to rooftop power plants.</p>
    </div>
</section>

<section class="pl-page">
    <div class="pl-container">
        <form action="{{ route('products.index') }}" method="GET" class="pl-layout" id="pl-filter-form">

            {{-- FILTER SIDEBAR (drawer on mobile) --}}
            <aside class="pl-sidebar" id="pl-sidebar" aria-label="Product filters">
                <div class="pl-sidebar-header">
                    <h2>Filters</h2>
                    <button type="button" class="pl-sidebar-close" data-filter-close aria-label="Close filters">
                        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                    </button>
                </div>

                <div class="pl-filter-group">
                    <h3>Categories</h3>
                    <label class="pl-radio">
                        <input type="radio" name="category" value="" @checked(!$selectedCategory)>
                        <span class="pl-radio-label">All Products</span>
                        <span class="pl-count">{{ $totalProducts }}</span>
                    </label>
                    @foreach ($categories as $category)
                    <label class="pl-radio">
                        <input type="radio" name="category" value="{{ $category->id }}" @checked($selectedCategory?->id === $category->id)>
                        <span class="pl-radio-label">{{ \Illuminate\Support\Str::title($category->name) }}</span>
                        <span class="pl-count">{{ $category->products_count }}</span>
                    </label>
                    @endforeach
                </div>

                @if ($pricesVisible)
                <div class="pl-filter-group">
                    <h3>Price (₹)</h3>
                    <div class="pl-price-inputs">
                        <input type="number" name="min_price" min="0" step="100" inputmode="numeric" placeholder="Min" value="{{ request('min_price') }}" aria-label="Minimum price">
                        <span aria-hidden="true">–</span>
                        <input type="number" name="max_price" min="0" step="100" inputmode="numeric" placeholder="Max" value="{{ request('max_price') }}" aria-label="Maximum price">
                    </div>
                    <button type="submit" class="pl-apply-btn">Apply</button>
                </div>
                @endif

                <div class="pl-filter-group">
                    <h3>Availability</h3>
                    @if ($pricesVisible)
                    <label class="pl-check">
                        <input type="checkbox" name="on_sale" value="1" @checked(request()->boolean('on_sale'))>
                        <span>On Sale</span>
                    </label>
                    @endif
                    <label class="pl-check">
                        <input type="checkbox" name="in_stock" value="1" @checked(request()->boolean('in_stock'))>
                        <span>In Stock</span>
                    </label>
                </div>

                @if (!empty($whatsappNumber))
                <div class="pl-help-card">
                    <i class="fa-solid fa-headset" aria-hidden="true"></i>
                    <h3>Need help choosing?</h3>
                    <p>Tell us your requirement and we’ll suggest the right product and capacity.</p>
                    <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Hello Suryaa Enterprises, I need help choosing a solar product.') }}" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat with us
                    </a>
                </div>
                @endif

                <button type="button" class="pl-show-results" data-filter-close>Show Results</button>
            </aside>

            <div class="pl-sidebar-backdrop" data-filter-close aria-hidden="true"></div>

            {{-- MAIN COLUMN --}}
            <div class="pl-main">
                <div class="pl-toolbar">
                    <div class="pl-search">
                        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
                        <input
                            type="search"
                            name="search"
                            value="{{ request('search') }}"
                            placeholder="Search products, brand or SKU..."
                            aria-label="Search products">
                    </div>

                    <button type="button" class="pl-filter-toggle" data-filter-open aria-controls="pl-sidebar">
                        <i class="fa-solid fa-sliders" aria-hidden="true"></i> Filters
                        @if (count($activeFilters))
                        <span class="pl-filter-badge">{{ count($activeFilters) }}</span>
                        @endif
                    </button>

                    <label class="pl-sort">
                        <span>Sort by</span>
                        <select name="sort" aria-label="Sort products">
                            @foreach ($sortOptions as $value => $label)
                            <option value="{{ $value }}" @selected($sort === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </label>
                </div>

                <div id="products-results" aria-live="polite" aria-busy="false">
                    <div class="pl-results-bar">
                        <p class="pl-results-count">
                            @if ($products->total())
                            Showing <strong>{{ $products->firstItem() }}–{{ $products->lastItem() }}</strong> of <strong>{{ $products->total() }}</strong> products
                            @else
                            No products match your filters
                            @endif
                        </p>

                        @if (count($activeFilters))
                        <div class="pl-chips">
                            @foreach ($activeFilters as $label => $keys)
                            <a href="{{ route('products.index', request()->except(array_merge($keys, ['page']))) }}" class="pl-chip" data-results-link aria-label="Remove filter {{ $label }}">
                                {{ $label }} <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                            </a>
                            @endforeach
                            <a href="{{ route('products.index', request()->only('sort')) }}" class="pl-chip-clear" data-results-link>Clear all</a>
                        </div>
                        @endif
                    </div>

                    @if ($products->count())
                    <div class="pl-grid">
                        @foreach ($products as $product)
                        @include('partials.product-card', ['product' => $product])
                        @endforeach
                    </div>

                    @if ($products->hasPages())
                    <nav class="pl-pagination" aria-label="Product pages">
                        @if ($products->onFirstPage())
                        <span class="pl-page-link is-disabled" aria-disabled="true"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Prev</span>
                        @else
                        <a href="{{ $products->previousPageUrl() }}" class="pl-page-link" data-results-link rel="prev"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i> Prev</a>
                        @endif

                        @foreach (range(1, $products->lastPage()) as $page)
                        @if ($page === 1 || $page === $products->lastPage() || abs($page - $products->currentPage()) <= 1)
                        @if ($page === $products->currentPage())
                        <span class="pl-page-link is-active" aria-current="page">{{ $page }}</span>
                        @else
                        <a href="{{ $products->url($page) }}" class="pl-page-link" data-results-link>{{ $page }}</a>
                        @endif
                        @elseif (abs($page - $products->currentPage()) === 2)
                        <span class="pl-page-gap" aria-hidden="true">…</span>
                        @endif
                        @endforeach

                        @if ($products->hasMorePages())
                        <a href="{{ $products->nextPageUrl() }}" class="pl-page-link" data-results-link rel="next">Next <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></a>
                        @else
                        <span class="pl-page-link is-disabled" aria-disabled="true">Next <i class="fa-solid fa-chevron-right" aria-hidden="true"></i></span>
                        @endif
                    </nav>
                    @endif
                    @else
                    <div class="pl-empty">
                        <i class="fa-solid fa-solar-panel" aria-hidden="true"></i>
                        <h2>No products found</h2>
                        <p>Try a different search term, widen the price range or clear your filters.</p>
                        <a href="{{ route('products.index') }}" class="pl-empty-btn" data-results-link>View All Products</a>
                    </div>
                    @endif
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
    .pl-banner,
    .pl-banner *,
    .pl-page,
    .pl-page * {
        box-sizing: border-box;
    }

    .pl-container {
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    /* =========================
       BANNER
    ========================= */
    .pl-banner {
        padding: 48px 0 52px;
        background:
            radial-gradient(circle at 88% 20%, rgba(251, 191, 36, .28), transparent 34%),
            linear-gradient(120deg, #022c22 0%, #065f46 100%);
        color: #fff;
    }

    .pl-breadcrumb {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 14px;
        color: rgba(255, 255, 255, .7);
        font-size: 13px;
    }

    .pl-breadcrumb a {
        color: rgba(255, 255, 255, .85) !important;
        text-decoration: none !important;
    }

    .pl-breadcrumb a:hover {
        color: #fbbf24 !important;
    }

    .pl-banner h1 {
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(30px, 4vw, 44px);
        font-weight: 800;
        line-height: 1.15;
    }

    .pl-banner p {
        max-width: 640px;
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 15px;
        line-height: 1.7;
    }

    /* =========================
       LAYOUT
    ========================= */
    .pl-page {
        padding: 36px 0 76px;
        background: #f5f8f6;
    }

    .pl-layout {
        display: grid;
        grid-template-columns: 260px minmax(0, 1fr);
        align-items: start;
        gap: 28px;
    }

    .pl-main {
        min-width: 0;
    }

    /* =========================
       SIDEBAR
    ========================= */
    .pl-sidebar {
        position: sticky;
        top: 140px;
        padding: 6px 20px 20px;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
    }

    .pl-sidebar-header {
        display: none;
    }

    .pl-filter-group {
        padding: 18px 0;
        border-bottom: 1px solid #edf1ee;
    }

    .pl-filter-group h3 {
        margin: 0 0 12px;
        color: #172d25;
        font-size: 14px;
        font-weight: 800;
        letter-spacing: .3px;
        text-transform: uppercase;
    }

    .pl-radio,
    .pl-check {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 7px 8px;
        margin: 0 -8px;
        border-radius: 8px;
        color: #30443a;
        font-size: 14px;
        cursor: pointer;
        transition: background .15s ease;
    }

    .pl-radio:hover,
    .pl-check:hover {
        background: #f3f7f4;
    }

    .pl-radio input,
    .pl-check input {
        width: 17px;
        height: 17px;
        margin: 0;
        accent-color: var(--primary-color, #047857);
    }

    .pl-radio-label {
        flex: 1;
        min-width: 0;
    }

    .pl-radio input:checked ~ .pl-radio-label {
        color: var(--primary-color, #047857);
        font-weight: 700;
    }

    .pl-count {
        min-width: 26px;
        padding: 2px 7px;
        border-radius: 999px;
        background: #f0f4f1;
        color: #65756d;
        font-size: 11px;
        font-weight: 700;
        text-align: center;
    }

    .pl-price-inputs {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #89958e;
    }

    .pl-price-inputs input {
        width: 100%;
        min-width: 0;
        min-height: 40px;
        padding: 8px 10px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        color: #20362a;
        font: inherit;
        font-size: 14px;
    }

    .pl-price-inputs input:focus,
    .pl-search input:focus,
    .pl-sort select:focus {
        border-color: var(--primary-color, #047857);
        outline: none;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, .12);
    }

    .pl-apply-btn,
    .pl-show-results {
        width: 100%;
        min-height: 40px;
        margin-top: 10px;
        border: 0;
        border-radius: 8px;
        background: var(--primary-color, #047857);
        color: #fff;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .pl-apply-btn:hover,
    .pl-show-results:hover {
        filter: brightness(.9);
    }

    .pl-show-results {
        display: none;
    }

    .pl-help-card {
        margin-top: 20px;
        padding: 18px;
        border-radius: 12px;
        background: #022c22;
        color: #fff;
    }

    .pl-help-card > i {
        color: #fbbf24;
        font-size: 22px;
    }

    .pl-help-card h3 {
        margin: 10px 0 6px;
        color: #fff;
        font-size: 15px;
        font-weight: 800;
    }

    .pl-help-card p {
        margin: 0 0 14px;
        color: rgba(255, 255, 255, .75);
        font-size: 13px;
        line-height: 1.6;
    }

    .pl-help-card a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 14px;
        border-radius: 8px;
        background: #25d366;
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .pl-sidebar-backdrop {
        display: none;
    }

    /* =========================
       TOOLBAR
    ========================= */
    .pl-toolbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 16px;
        padding: 12px;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
    }

    .pl-search {
        position: relative;
        flex: 1;
        min-width: 0;
    }

    .pl-search i {
        position: absolute;
        top: 50%;
        left: 14px;
        transform: translateY(-50%);
        color: #89958e;
        font-size: 14px;
    }

    .pl-search input {
        width: 100%;
        min-height: 44px;
        padding: 10px 14px 10px 40px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        color: #20362a;
        font: inherit;
        font-size: 14px;
    }

    .pl-sort {
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 8px;
        color: #65756d;
        font-size: 13px;
        font-weight: 600;
    }

    .pl-sort select {
        min-height: 44px;
        padding: 8px 12px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        background: #fff;
        color: #20362a;
        font: inherit;
        font-size: 14px;
        cursor: pointer;
    }

    .pl-filter-toggle {
        position: relative;
        display: none;
        align-items: center;
        gap: 8px;
        min-height: 44px;
        padding: 10px 14px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        background: #fff;
        color: #20362a;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .pl-filter-badge {
        display: grid;
        min-width: 20px;
        height: 20px;
        place-items: center;
        border-radius: 999px;
        background: var(--primary-color, #047857);
        color: #fff;
        font-size: 11px;
    }

    /* =========================
       RESULTS
    ========================= */
    #products-results {
        transition: opacity .2s ease;
    }

    .pl-results-bar {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .pl-results-count {
        margin: 0;
        color: #65756d;
        font-size: 14px;
    }

    .pl-results-count strong {
        color: #172d25;
    }

    .pl-chips {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 8px;
    }

    .pl-chip {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border: 1px solid #cfe3d6;
        border-radius: 999px;
        background: #eaf5ed;
        color: var(--primary-color, #047857) !important;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .pl-chip:hover {
        border-color: #dc2626;
        background: #fef2f2;
        color: #b91c1c !important;
    }

    .pl-chip-clear {
        color: #b91c1c !important;
        font-size: 12px;
        font-weight: 700;
    }

    .pl-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 22px;
    }

    .pl-pagination {
        display: flex;
        flex-wrap: wrap;
        justify-content: center;
        gap: 8px;
        margin-top: 36px;
    }

    .pl-page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        min-width: 42px;
        min-height: 42px;
        padding: 8px 14px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        background: #fff;
        color: #20362a !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
    }

    a.pl-page-link:hover {
        border-color: var(--primary-color, #047857);
        color: var(--primary-color, #047857) !important;
    }

    .pl-page-link.is-active {
        border-color: var(--primary-color, #047857);
        background: var(--primary-color, #047857);
        color: #fff !important;
    }

    .pl-page-link.is-disabled {
        opacity: .45;
    }

    .pl-page-gap {
        align-self: center;
        color: #89958e;
    }

    .pl-empty {
        padding: 56px 24px;
        border: 1px dashed #cddbd1;
        border-radius: 14px;
        background: #fff;
        text-align: center;
    }

    .pl-empty > i {
        color: var(--primary-color, #047857);
        font-size: 40px;
    }

    .pl-empty h2 {
        margin: 14px 0 8px;
        color: #172d25;
        font-size: 22px;
    }

    .pl-empty p {
        margin: 0 0 20px;
        color: #718078;
        font-size: 14px;
    }

    .pl-empty-btn {
        display: inline-flex;
        padding: 11px 20px;
        border-radius: 8px;
        background: var(--primary-color, #047857);
        color: #fff !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
    }

    /* =========================
       RESPONSIVE
    ========================= */
    @media (max-width: 1100px) {
        .pl-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 900px) {
        .pl-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        /* Sidebar becomes a slide-in drawer. */
        .pl-sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 100001;
            width: min(340px, 88vw);
            padding: 0 20px 24px;
            overflow-y: auto;
            border: 0;
            border-radius: 0;
            transform: translateX(-100%);
            transition: transform .3s ease;
        }

        .pl-filters-open .pl-sidebar {
            transform: translateX(0);
        }

        .pl-sidebar-header {
            position: sticky;
            top: 0;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0 12px;
            border-bottom: 1px solid #edf1ee;
            background: #fff;
        }

        .pl-sidebar-header h2 {
            margin: 0;
            color: #172d25;
            font-size: 20px;
        }

        .pl-sidebar-close {
            width: 38px;
            height: 38px;
            border: 0;
            border-radius: 50%;
            background: #f0f4f1;
            color: #20362a;
            font-size: 18px;
            cursor: pointer;
        }

        .pl-show-results {
            display: block;
            margin-top: 20px;
        }

        .pl-sidebar-backdrop {
            position: fixed;
            inset: 0;
            z-index: 100000;
            display: block;
            background: rgba(2, 20, 14, .5);
            opacity: 0;
            pointer-events: none;
            transition: opacity .3s ease;
        }

        .pl-filters-open .pl-sidebar-backdrop {
            opacity: 1;
            pointer-events: auto;
        }

        .pl-filter-toggle {
            display: inline-flex;
        }

        .pl-sort span {
            display: none;
        }
    }

    @media (max-width: 640px) {
        .pl-container {
            width: calc(100% - 28px);
        }

        .pl-banner {
            padding: 34px 0 38px;
        }

        .pl-page {
            padding: 20px 0 50px;
        }

        .pl-toolbar {
            flex-wrap: wrap;
            padding: 10px;
        }

        .pl-search {
            flex-basis: 100%;
        }

        .pl-filter-toggle,
        .pl-sort {
            flex: 1 1 0;
        }

        .pl-filter-toggle {
            justify-content: center;
        }

        .pl-sort select {
            width: 100%;
        }

        .pl-grid {
            gap: 12px;
        }

    }

    @media (max-width: 360px) {
        .pl-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .pl-sidebar,
        .pl-sidebar-backdrop,
        #products-results {
            transition: none !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('pl-filter-form');
        const results = document.getElementById('products-results');
        if (!form || !results) return;

        const searchInput = form.querySelector('input[name="search"]');
        const priceInputs = form.querySelectorAll('input[name="min_price"], input[name="max_price"]');
        const filterToggle = form.querySelector('.pl-filter-toggle');
        let debounceTimer;
        let activeController = null;

        // Build the listing URL from the current form values (empty values dropped).
        function urlFromForm() {
            const url = new URL(form.action, window.location.origin);

            new FormData(form).forEach(function(value, key) {
                value = String(value).trim();
                if (value !== '' && !(key === 'sort' && value === 'latest')) {
                    url.searchParams.set(key, value);
                }
            });

            return url.toString();
        }

        // Reflect a URL's query string back into the form controls.
        function syncFormFromUrl(urlString) {
            const params = new URL(urlString, window.location.origin).searchParams;

            form.querySelectorAll('input[name="category"]').forEach(function(radio) {
                radio.checked = radio.value === (params.get('category') || '');
            });

            form.querySelectorAll('input[type="checkbox"]').forEach(function(box) {
                box.checked = params.get(box.name) === '1';
            });

            ['search', 'min_price', 'max_price'].forEach(function(name) {
                const input = form.querySelector('[name="' + name + '"]');
                if (input) input.value = params.get(name) || '';
            });

            form.querySelector('select[name="sort"]').value = params.get('sort') || 'latest';
        }

        function updateFilterBadge(doc) {
            const fresh = doc.querySelector('.pl-filter-toggle');
            if (fresh && filterToggle) filterToggle.innerHTML = fresh.innerHTML;
        }

        async function loadProducts(url, updateHistory = true) {
            if (activeController) activeController.abort();
            activeController = new AbortController();
            results.setAttribute('aria-busy', 'true');
            results.style.opacity = '0.55';

            try {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    signal: activeController.signal
                });
                if (!response.ok) throw new Error('Unable to load products.');

                const parsed = new DOMParser().parseFromString(await response.text(), 'text/html');
                const newResults = parsed.getElementById('products-results');
                if (!newResults) throw new Error('Product results were not found in the response.');

                results.innerHTML = newResults.innerHTML;
                updateFilterBadge(parsed);
                if (updateHistory) history.pushState({}, '', url);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    // Fall back to a normal page load if the AJAX request fails.
                    window.location.href = url;
                }
            } finally {
                results.setAttribute('aria-busy', 'false');
                results.style.opacity = '';
            }
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            loadProducts(urlFromForm());
        });

        form.addEventListener('change', function(event) {
            // Price inputs apply on change (blur/enter) like the rest of the controls.
            if (event.target.name === 'search') return;
            loadProducts(urlFromForm());
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                loadProducts(urlFromForm());
            }, 350);
        });

        priceInputs.forEach(function(input) {
            input.addEventListener('keydown', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    loadProducts(urlFromForm());
                }
            });
        });

        // Pagination, filter chips and "clear" links load in place and keep the form in sync.
        results.addEventListener('click', function(event) {
            const link = event.target.closest('a[data-results-link]');
            if (!link) return;

            event.preventDefault();
            syncFormFromUrl(link.href);
            loadProducts(link.href);

            if (link.closest('.pl-pagination')) {
                window.scrollTo({
                    top: form.getBoundingClientRect().top + window.scrollY - 160,
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
                });
            }
        });

        window.addEventListener('popstate', function() {
            syncFormFromUrl(window.location.href);
            loadProducts(window.location.href, false);
        });

        // Mobile filter drawer.
        function setDrawer(open) {
            document.body.classList.toggle('pl-filters-open', open);
            document.body.style.overflow = open ? 'hidden' : '';
        }

        form.querySelectorAll('[data-filter-open]').forEach(function(button) {
            button.addEventListener('click', function() { setDrawer(true); });
        });

        form.querySelectorAll('[data-filter-close]').forEach(function(button) {
            button.addEventListener('click', function() { setDrawer(false); });
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') setDrawer(false);
        });
    });
</script>
@endpush
