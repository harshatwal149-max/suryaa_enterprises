@extends('admin.layout')

@section('page-title', 'Dashboard')
@section('page-heading', 'Dashboard')
@section('breadcrumb', 'Admin / Dashboard')

@section('content')
@php
$schema = \Illuminate\Support\Facades\Schema::class;
$db = \Illuminate\Support\Facades\DB::class;

$productTable = collect(['products', 'solar_products'])->first(fn ($table) => $schema::hasTable($table));
$categoryTable = collect(['categories', 'product_categories'])->first(fn ($table) => $schema::hasTable($table));
$subcategoryTable = collect(['subcategories', 'sub_categories', 'sub_categories_table', 'product_subcategories'])
->first(fn ($table) => $schema::hasTable($table));

$totalProducts = $productTable ? $db::table($productTable)->count() : 0;
$totalCategories = $categoryTable ? $db::table($categoryTable)->count() : 0;
$totalSubcategories = $subcategoryTable ? $db::table($subcategoryTable)->count() : 0;

$enquiryTable = collect(['enquiries', 'customer_enquiries', 'contact_enquiries', 'inquiries', 'contacts'])
->first(fn ($table) => $schema::hasTable($table));
$totalEnquiries = $enquiryTable ? \App\Models\Enquiry::notSpam()->count() : 0;
$pendingEnquiries = 0;
if ($enquiryTable && $schema::hasColumn($enquiryTable, 'status')) {
$pendingEnquiries = $db::table($enquiryTable)
->whereIn('status', ['pending', 'new', 'unread', 'open'])->count();
}

$slideTable = collect(['hero_slides', 'hero_banners', 'banners'])->first(fn ($table) => $schema::hasTable($table));
$totalSlides = $slideTable ? $db::table($slideTable)->count() : 0;
$hasProductRoute = \Illuminate\Support\Facades\Route::has('admin.products.index');
$hasProductCreateRoute = \Illuminate\Support\Facades\Route::has('admin.products.create');
$hasCategoryRoute = \Illuminate\Support\Facades\Route::has('admin.categories.index');
$hasSubcategoryRoute = \Illuminate\Support\Facades\Route::has('admin.subcategories.index');
$hasHeroRoute = \Illuminate\Support\Facades\Route::has('admin.hero-slides.index')
|| \Illuminate\Support\Facades\Route::has('admin.hero-banners.index')
|| \Illuminate\Support\Facades\Route::has('admin.banners.index');
$heroRoute = \Illuminate\Support\Facades\Route::has('admin.hero-slides.index') ? 'admin.hero-slides.index' : (\Illuminate\Support\Facades\Route::has('admin.hero-banners.index') ? 'admin.hero-banners.index' : 'admin.banners.index');
$hasEnquiryRoute = \Illuminate\Support\Facades\Route::has('admin.enquiries.index');
$hasSettingsRoute = \Illuminate\Support\Facades\Route::has('admin.settings.edit');
@endphp

<style>
    .dashboard-page {
        display: grid;
        gap: 24px;
        min-width: 0;
        color: #17211d
    }

    .dashboard-welcome {
        position: relative;
        isolation: isolate;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 24px;
        min-height: 190px;
        padding: 32px 34px;
        border: 1px solid #0b5b43;
        border-radius: 20px;
        background: linear-gradient(115deg, #073b2e 0%, #08664a 58%, #13835c 100%);
        color: #fff;
        box-shadow: 0 16px 38px rgba(6, 78, 59, .14)
    }

    .dashboard-welcome:before {
        content: "";
        position: absolute;
        z-index: -1;
        width: 320px;
        height: 320px;
        right: 5%;
        top: -190px;
        border: 1px solid rgba(255, 255, 255, .15);
        border-radius: 50%;
        box-shadow: 0 0 0 35px rgba(255, 255, 255, .045), 0 0 0 75px rgba(255, 255, 255, .035)
    }

    .dashboard-welcome:after {
        content: "☀";
        position: absolute;
        z-index: -1;
        right: 7%;
        bottom: -45px;
        font-size: 190px;
        line-height: 1;
        color: rgba(255, 255, 255, .07)
    }

    .dashboard-welcome-copy {
        max-width: 680px;
        position: relative;
        z-index: 1
    }

    .dashboard-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin: 0 0 13px;
        color: #b7f7d5;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.6px;
        text-transform: uppercase
    }

    .dashboard-welcome h2 {
        margin: 0 0 11px;
        color: #fff;
        font-size: clamp(25px, 3vw, 34px);
        font-weight: 750;
        line-height: 1.2;
        letter-spacing: -.8px
    }

    .dashboard-welcome p {
        max-width: 600px;
        margin: 0;
        color: rgba(255, 255, 255, .8);
        font-size: 14px;
        line-height: 1.75
    }

    .dashboard-date {
        z-index: 1;
        flex-shrink: 0;
        padding: 13px 16px;
        border: 1px solid rgba(255, 255, 255, .2);
        border-radius: 12px;
        background: rgba(255, 255, 255, .1);
        color: #f0fdf4;
        font-size: 12px;
        font-weight: 650;
        white-space: nowrap;
        backdrop-filter: blur(8px)
    }

    .dashboard-section-head {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 15px
    }

    .dashboard-section-head h2 {
        margin: 0;
        color: #17211d;
        font-size: 20px;
        font-weight: 750;
        letter-spacing: -.4px
    }

    .dashboard-section-head p {
        margin: 5px 0 0;
        color: #7b8780;
        font-size: 12px
    }

    .dashboard-stats {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px
    }

    .dashboard-stat {
        position: relative;
        min-width: 0;
        padding: 22px 21px 20px;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(20, 46, 32, .035);
        transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease
    }

    .dashboard-stat:hover {
        transform: translateY(-3px);
        border-color: #cfe5d7;
        box-shadow: 0 14px 28px rgba(20, 46, 32, .08)
    }

    .dashboard-stat-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px
    }

    .dashboard-stat-icon {
        display: grid;
        place-items: center;
        width: 46px;
        height: 46px;
        border-radius: 13px;
        font-size: 22px;
        font-weight: 700
    }

    .dashboard-stat-icon.green {
        background: #e0f6e9;
        color: #087443
    }

    .dashboard-stat-icon.blue {
        background: #e4efff;
        color: #285cb8
    }

    .dashboard-stat-icon.amber {
        background: #fff2d8;
        color: #a45b06
    }

    .dashboard-stat-icon.violet {
        background: #eee8ff;
        color: #6c45b5
    }

    .dashboard-stat-note {
        font-size: 11px;
        color: #849087
    }

    .dashboard-stat-value {
        margin: 23px 0 7px;
        color: #17211d;
        font-size: 35px;
        font-weight: 800;
        line-height: 1;
        letter-spacing: -1.1px;
        overflow-wrap: anywhere
    }

    .dashboard-stat-label {
        font-size: 13px;
        font-weight: 700;
        color: #47554c
    }

    .dashboard-stat-note:last-child {
        margin-top: 9px;
        line-height: 1.5
    }

    .dashboard-stat-link {
        position: absolute;
        inset: 0;
        border-radius: 16px
    }

    .dashboard-stat:focus-within {
        outline: 2px solid #15945f;
        outline-offset: 3px
    }

    .dashboard-lower-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.1fr) minmax(300px, .9fr);
        gap: 18px
    }

    .dashboard-panel {
        min-width: 0;
        padding: 23px;
        border: 1px solid #e8eee9;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 4px 16px rgba(20, 46, 32, .03)
    }

    .dashboard-panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px
    }

    .dashboard-panel-head h3 {
        margin: 0;
        color: #25342b;
        font-size: 15px;
        font-weight: 750
    }

    .dashboard-panel-head p {
        margin: 5px 0 0;
        color: #89938c;
        font-size: 11px
    }

    .dashboard-text-link {
        color: #08794b;
        font-size: 12px;
        font-weight: 750;
        white-space: nowrap
    }

    .dashboard-text-link:hover {
        color: #075e3b;
        text-decoration: underline
    }

    .dashboard-metric-row {
        display: flex;
        align-items: center;
        gap: 13px;
        padding: 14px 0;
        border-top: 1px solid #f0f3f0
    }

    .dashboard-metric-row:first-of-type {
        border-top: 0
    }

    .dashboard-mini-icon {
        display: grid;
        place-items: center;
        flex-shrink: 0;
        width: 40px;
        height: 40px;
        border-radius: 12px;
        background: #f0f7f2;
        color: #13794d;
        font-size: 18px
    }

    .dashboard-metric-copy {
        flex: 1;
        min-width: 0
    }

    .dashboard-metric-copy strong {
        display: block;
        color: #29372f;
        font-size: 13px;
        font-weight: 700
    }

    .dashboard-metric-copy span {
        display: block;
        margin-top: 4px;
        color: #8a958d;
        font-size: 11px
    }

    .dashboard-metric-count {
        color: #25342b;
        font-size: 18px;
        font-weight: 800;
        font-variant-numeric: tabular-nums
    }

    .dashboard-actions {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 11px
    }

    .dashboard-action {
        display: flex;
        align-items: center;
        gap: 11px;
        min-height: 66px;
        padding: 13px;
        border: 1px solid #e8eee9;
        border-radius: 12px;
        background: #fff;
        color: #3c4a41;
        transition: background .18s, border-color .18s, transform .18s
    }

    .dashboard-action:hover {
        transform: translateY(-1px);
        border-color: #b8dfc7;
        background: #f5fbf7
    }

    .dashboard-action-icon {
        display: grid;
        place-items: center;
        width: 36px;
        height: 36px;
        flex-shrink: 0;
        border-radius: 10px;
        background: #e8f7ed;
        color: #08794b;
        font-size: 18px
    }

    .dashboard-action-label {
        font-size: 12px;
        font-weight: 750;
        line-height: 1.4
    }

    .dashboard-empty-note {
        padding: 14px;
        border-radius: 10px;
        background: #f7faf8;
        color: #78847b;
        font-size: 12px;
        line-height: 1.6
    }

    @media(max-width:1120px) {
        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr))
        }
    }

    @media(max-width:760px) {
        .dashboard-welcome {
            align-items: flex-start;
            flex-direction: column;
            padding: 25px 23px
        }

        .dashboard-date {
            white-space: normal
        }

        .dashboard-lower-grid {
            grid-template-columns: 1fr
        }

        .dashboard-panel {
            padding: 19px
        }
    }

    @media(max-width:460px) {
        .dashboard-page {
            gap: 19px
        }

        .dashboard-stats {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px
        }

        .dashboard-stat {
            padding: 15px
        }

        .dashboard-stat-value {
            margin-top: 17px;
            font-size: 29px
        }

        .dashboard-stat-icon {
            width: 39px;
            height: 39px
        }

        .dashboard-actions {
            grid-template-columns: 1fr
        }

        .dashboard-section-head h2 {
            font-size: 18px
        }
    }
</style>

<div class="dashboard-page">
    <section class="dashboard-welcome">
        <div class="dashboard-welcome-copy">
            <p class="dashboard-eyebrow"><span aria-hidden="true">✦</span> Admin overview</p>
            <h2>Welcome to Suryaa Enterprises</h2>
        </div>
        <div class="dashboard-date">{{ now()->format('D, d M Y') }}</div>
    </section>

    <section aria-labelledby="dashboard-overview-title">
        <div class="dashboard-section-head">
            <div>
                <h2 id="dashboard-overview-title">Business overview</h2>
                <p>Live totals from your database</p>
            </div>
        </div>
        <div class="dashboard-stats">
            <article class="dashboard-stat">
                <div class="dashboard-stat-top"><span class="dashboard-stat-icon green" aria-hidden="true">▧</span><span class="dashboard-stat-note">Inventory</span></div>
                <div class="dashboard-stat-value">{{ number_format($totalProducts) }}</div>
                <div class="dashboard-stat-label">Total Products</div>@if($hasProductRoute)<a class="dashboard-stat-link" href="{{ route('admin.products.index') }}" aria-label="View all products"></a>@endif
            </article>
            <article class="dashboard-stat">
                <div class="dashboard-stat-top"><span class="dashboard-stat-icon blue" aria-hidden="true">▦</span><span class="dashboard-stat-note">Catalog</span></div>
                <div class="dashboard-stat-value">{{ number_format($totalCategories) }}</div>
                <div class="dashboard-stat-label">Categories</div>
                <div class="dashboard-stat-note">{{ number_format($totalSubcategories) }} subcategories</div>@if($hasCategoryRoute)<a class="dashboard-stat-link" href="{{ route('admin.categories.index') }}" aria-label="View all categories"></a>@endif
            </article>
            <article class="dashboard-stat">
                <div class="dashboard-stat-top"><span class="dashboard-stat-icon amber" aria-hidden="true">✉</span><span class="dashboard-stat-note">Customer support</span></div>
                <div class="dashboard-stat-value">{{ number_format($totalEnquiries) }}</div>
                <div class="dashboard-stat-label">Customer Enquiries</div>
                <div class="dashboard-stat-note">{{ number_format($pendingEnquiries) }} pending / new, where status is available</div>@if($hasEnquiryRoute)<a class="dashboard-stat-link" href="{{ route('admin.enquiries.index') }}" aria-label="View customer enquiries"></a>@endif
            </article>
            <article class="dashboard-stat">
                <div class="dashboard-stat-top"><span class="dashboard-stat-icon violet" aria-hidden="true">◈</span><span class="dashboard-stat-note">Website</span></div>
                <div class="dashboard-stat-value">{{ $hasHeroRoute ? number_format($totalSlides) : '—' }}</div>
                <div class="dashboard-stat-label">Homepage Slides</div>
                <div class="dashboard-stat-note">{{ $hasHeroRoute ? 'Manage homepage banners' : 'Banner management route not configured' }}</div>@if($hasHeroRoute)<a class="dashboard-stat-link" href="{{ route($heroRoute) }}" aria-label="Manage homepage slides"></a>@endif
            </article>
        </div>
    </section>

    <section class="dashboard-lower-grid">
        <div class="dashboard-panel">
            <div class="dashboard-panel-head">
                <div>
                    <h3>Catalog summary</h3>
                    <p>Quick look at your product catalogue</p>
                </div>@if($hasProductRoute)<a class="dashboard-text-link" href="{{ route('admin.products.index') }}">View products →</a>@endif
            </div>

            <div class="dashboard-metric-row"><span class="dashboard-mini-icon" aria-hidden="true">▦</span>
                <div class="dashboard-metric-copy"><strong>Categories</strong><span>Top-level product groups</span></div><span class="dashboard-metric-count">{{ number_format($totalCategories) }}</span>
            </div>
            <div class="dashboard-metric-row"><span class="dashboard-mini-icon" aria-hidden="true">▤</span>
                <div class="dashboard-metric-copy"><strong>Subcategories</strong><span>Detailed catalogue groups</span></div><span class="dashboard-metric-count">{{ number_format($totalSubcategories) }}</span>
            </div>

            <div class="dashboard-metric-row"><span class="dashboard-mini-icon" aria-hidden="true">▧</span>
                <div class="dashboard-metric-copy"><strong>Products</strong><span>All products currently stored</span></div><span class="dashboard-metric-count">{{ number_format($totalProducts) }}</span>
            </div>
        </div>
        <div class="dashboard-panel">
            <div class="dashboard-panel-head">
                <div>
                    <h3>Quick actions</h3>
                    <p>Jump directly to common tasks</p>
                </div>
            </div>
            <div class="dashboard-actions">
                @if($hasProductCreateRoute)<a class="dashboard-action" href="{{ route('admin.products.create') }}"><span class="dashboard-action-icon" aria-hidden="true">＋</span><span class="dashboard-action-label">Add product</span></a>@elseif($hasProductRoute)<a class="dashboard-action" href="{{ route('admin.products.index') }}"><span class="dashboard-action-icon" aria-hidden="true">▧</span><span class="dashboard-action-label">Manage products</span></a>@endif
                @if($hasCategoryRoute)<a class="dashboard-action" href="{{ route('admin.categories.index') }}"><span class="dashboard-action-icon" aria-hidden="true">▦</span><span class="dashboard-action-label">Manage Categories</span></a>@endif
                @if($hasSubcategoryRoute)<a class="dashboard-action" href="{{ route('admin.subcategories.index') }}"><span class="dashboard-action-icon" aria-hidden="true">▤</span><span class="dashboard-action-label">Manage Subcategories</span></a>@endif
                @if($hasHeroRoute)<a class="dashboard-action" href="{{ route($heroRoute) }}"><span class="dashboard-action-icon" aria-hidden="true">◈</span><span class="dashboard-action-label">Homepage banners</span></a>@endif
                @if($hasEnquiryRoute)<a class="dashboard-action" href="{{ route('admin.enquiries.index') }}"><span class="dashboard-action-icon" aria-hidden="true">✉</span><span class="dashboard-action-label">View enquiries</span></a>@endif
                @if($hasSettingsRoute)<a class="dashboard-action" href="{{ route('admin.settings.edit') }}"><span class="dashboard-action-icon" aria-hidden="true">⚙</span><span class="dashboard-action-label">Website settings</span></a>@endif
            </div>
        </div>
    </section>
</div>
@endsection