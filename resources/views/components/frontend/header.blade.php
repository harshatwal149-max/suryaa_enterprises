@php
    $siteSettings = \App\Models\SiteSetting::current();
    $siteName = $siteSettings?->site_name ?: 'Suryaa Enterprises';

    $headerLinks = collect($siteSettings?->header_links ?: [
        ['label' => 'Home', 'url' => '/', 'active' => true],
        ['label' => 'About Us', 'url' => '/about-us', 'active' => true],
        ['label' => 'Products', 'url' => '/products', 'active' => true],
        ['label' => 'Contact Us', 'url' => '/contact-us', 'active' => true],
    ])->filter(fn ($link) =>
        is_array($link)
        && !empty($link['active'])
        && !empty($link['label'])
        && !empty($link['url'])
    )->map(function ($link) {
        $url = trim($link['url']);
        $href = \Illuminate\Support\Str::startsWith($url, ['http://', 'https://', '/', '#', 'mailto:', 'tel:'])
            ? $url
            : '/' . $url;
        $path = trim((string) parse_url($href, PHP_URL_PATH), '/');

        return $link + [
            'href' => $href,
            'is_current' => $path === '' ? request()->is('/') : request()->is($path, $path . '/*'),
            'is_products' => $path === 'products',
        ];
    });

    $topBarPhones = array_filter([
        $siteSettings?->phone_primary,
        $siteSettings?->phone_secondary,
        $siteSettings?->phone_tertiary,
    ], fn ($phone) => filled($phone));

    $primaryPhone = reset($topBarPhones) ?: null;
    $whatsappDigits = $siteSettings?->whatsappDigits() ?? '';

    $topBarSocials = [
        'facebook_url'  => ['label' => 'Facebook',  'icon' => 'fa-facebook-f'],
        'instagram_url' => ['label' => 'Instagram', 'icon' => 'fa-instagram'],
        'linkedin_url'  => ['label' => 'LinkedIn',  'icon' => 'fa-linkedin-in'],
        'twitter_url'   => ['label' => 'X',         'icon' => 'fa-x-twitter'],
        'youtube_url'   => ['label' => 'YouTube',   'icon' => 'fa-youtube'],
    ];

    // Categories for the Products dropdown and the mobile drawer.
    $menuCategories = \App\Models\Category::query()
        ->whereHas('products', fn ($query) => $query->where('is_active', true))
        ->withCount(['products' => fn ($query) => $query->where('is_active', true)])
        ->orderBy('name')
        ->get();

    $quoteUrl = request()->routeIs('home') ? '#hero-quote' : route('enquiry');
    $telHref = fn ($phone) => 'tel:+91' . substr(preg_replace('/\D+/', '', $phone), -10);
@endphp

<style>
    /* Suryaa Enterprises sticky header: top bar + main bar + mobile drawer */
    .sx-header-wrap,
    .sx-header-wrap *,
    .sx-drawer,
    .sx-drawer * {
        box-sizing: border-box;
    }

    .sx-header-wrap {
        position: sticky;
        top: 0;
        z-index: 10000;
        width: 100%;
        font-family: inherit;
    }

    .sx-container {
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    /* Top bar (collapses once the page is scrolled) */
    .sx-topbar {
        max-height: 44px;
        overflow: hidden;
        background: #022c22;
        color: rgba(255, 255, 255, .82);
        font-size: 12.5px;
        transition: max-height .3s ease;
    }

    .sx-header-wrap.is-scrolled .sx-topbar {
        max-height: 0;
    }

    .sx-topbar-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        min-height: 40px;
    }

    .sx-topbar-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .sx-topbar-badge i {
        color: #fbbf24;
    }

    .sx-topbar-links {
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 20px;
    }

    .sx-topbar-links a {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        color: rgba(255, 255, 255, .85) !important;
        text-decoration: none !important;
        white-space: nowrap;
        transition: color .2s ease;
    }

    .sx-topbar-links a:hover {
        color: #fbbf24 !important;
    }

    .sx-topbar-links i {
        color: #fbbf24;
    }

    .sx-topbar-socials {
        display: flex;
        gap: 6px;
        padding-left: 16px;
        border-left: 1px solid rgba(255, 255, 255, .18);
    }

    .sx-topbar-socials a {
        display: grid;
        width: 26px;
        height: 26px;
        place-items: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08);
    }

    .sx-topbar-socials a:hover {
        background: #fbbf24;
    }

    .sx-topbar-socials a:hover i {
        color: #422006;
    }

    /* Main bar */
    .sx-header {
        position: relative;
        will-change: auto;
        background: rgba(255, 255, 255, .97);
        border-bottom: 1px solid #e6ece7;
        backdrop-filter: saturate(1.6) blur(10px);
        transition: box-shadow .3s ease;
    }

    .sx-header-wrap.is-scrolled .sx-header {
        box-shadow: 0 10px 30px rgba(10, 42, 27, .1);
    }

    .sx-header-inner {
        display: flex;
        align-items: center;
        gap: 28px;
        min-height: 84px;
        transition: min-height .3s ease;
    }

    .sx-header-wrap.is-scrolled .sx-header-inner {
        min-height: 70px;
    }

    .sx-nav {
        display: flex;
        align-items: center;
        gap: 4px;
        margin-left: auto;
    }

    .sx-nav-item {
        position: relative;
        display: flex;
        align-items: center;
    }

    .sx-nav-link {
        position: relative;
        display: inline-flex;
        align-items: center;
        min-height: 44px;
        padding: 10px 14px;
        border-radius: 10px;
        color: #20362a !important;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none !important;
        white-space: nowrap;
        transition: color .2s ease, background .2s ease;
    }

    .sx-nav-link::after {
        position: absolute;
        right: 14px;
        bottom: 6px;
        left: 14px;
        height: 2.5px;
        border-radius: 3px;
        background: linear-gradient(90deg, #0f7a3d, #fbbf24);
        content: "";
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .25s ease;
    }

    .sx-nav-link:hover,
    .sx-nav-link.is-current {
        color: #0f7a3d !important;
    }

    .sx-nav-link:hover::after,
    .sx-nav-link.is-current::after {
        transform: scaleX(1);
    }

    .sx-has-dropdown .sx-nav-link {
        padding-right: 4px;
    }

    .sx-has-dropdown .sx-nav-link::after {
        right: 4px;
    }

    .sx-dd-toggle {
        display: grid;
        width: 26px;
        height: 30px;
        place-items: center;
        padding: 0;
        border: 0;
        background: transparent;
        color: #20362a;
        font-size: 11px;
        cursor: pointer;
        transition: transform .25s ease, color .2s ease;
    }

    .sx-has-dropdown:hover .sx-dd-toggle,
    .sx-has-dropdown.is-open .sx-dd-toggle {
        color: #0f7a3d;
        transform: rotate(180deg);
    }

    /* Products mega dropdown */
    .sx-dropdown {
        position: absolute;
        top: calc(100% + 14px);
        left: 50%;
        display: grid;
        grid-template-columns: minmax(0, 1fr) 220px;
        gap: 18px;
        width: 560px;
        padding: 18px;
        border: 1px solid #e5ece7;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 24px 60px rgba(10, 42, 27, .16);
        opacity: 0;
        visibility: hidden;
        transform: translate(-50%, 10px);
        transition: opacity .22s ease, transform .22s ease, visibility .22s;
    }

    /* Invisible bridge so the menu stays open while the pointer crosses the gap */
    .sx-dropdown::before {
        position: absolute;
        top: -16px;
        right: 0;
        left: 0;
        height: 16px;
        content: "";
    }

    .sx-has-dropdown:hover .sx-dropdown,
    .sx-has-dropdown:focus-within .sx-dropdown,
    .sx-has-dropdown.is-open .sx-dropdown {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, 0);
    }

    .sx-dd-list {
        display: grid;
        gap: 2px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .sx-dd-list a {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 10px 12px;
        border-radius: 12px;
        color: #20362a !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease;
    }

    .sx-dd-list a:hover {
        background: #f0f7f2;
        color: #0f7a3d !important;
    }

    .sx-dd-icon {
        display: grid;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        place-items: center;
        border-radius: 10px;
        background: #eaf5ed;
        color: #0f7a3d;
        font-size: 15px;
    }

    .sx-dd-list a:hover .sx-dd-icon {
        background: #0f7a3d;
        color: #fff;
    }

    .sx-dd-name {
        flex: 1;
    }

    .sx-dd-count {
        color: #89958e;
        font-size: 12px;
        font-weight: 600;
    }

    .sx-dd-all {
        margin-top: 6px;
        border-top: 1px solid #edf1ee;
        padding-top: 6px;
    }

    .sx-dd-promo {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 18px;
        border-radius: 14px;
        background:
            radial-gradient(circle at 85% 15%, rgba(251, 191, 36, .45), transparent 45%),
            linear-gradient(150deg, #065f46, #022c22);
        color: #fff;
    }

    .sx-dd-promo i {
        margin-bottom: auto;
        color: #fbbf24;
        font-size: 26px;
    }

    .sx-dd-promo strong {
        margin: 18px 0 6px;
        font-size: 16px;
        line-height: 1.3;
    }

    .sx-dd-promo p {
        margin: 0 0 14px;
        color: rgba(255, 255, 255, .78);
        font-size: 12.5px;
        line-height: 1.55;
    }

    .sx-dd-promo a {
        align-self: flex-start;
        padding: 8px 14px;
        border-radius: 8px;
        background: #fbbf24;
        color: #422006 !important;
        font-size: 12.5px;
        font-weight: 800;
        text-decoration: none !important;
    }

    /* Right-hand actions */
    .sx-actions {
        display: flex;
        flex: 0 0 auto;
        align-items: center;
        gap: 16px;
    }

    .sx-call {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #20362a !important;
        text-decoration: none !important;
    }

    .sx-call-icon {
        position: relative;
        display: grid;
        width: 44px;
        height: 44px;
        place-items: center;
        border-radius: 50%;
        background: #eaf5ed;
        color: #0f7a3d;
        font-size: 16px;
    }

    /* Soft pulse ring around the phone icon */
    .sx-call-icon::after {
        position: absolute;
        inset: 0;
        border: 2px solid #0f7a3d;
        border-radius: 50%;
        content: "";
        opacity: 0;
        animation: sxPulse 2.4s ease-out infinite;
    }

    @keyframes sxPulse {
        0% {
            opacity: .6;
            transform: scale(1);
        }

        100% {
            opacity: 0;
            transform: scale(1.5);
        }
    }

    .sx-call-text {
        display: flex;
        flex-direction: column;
        line-height: 1.25;
    }

    .sx-call-text small {
        color: #718078;
        font-size: 11.5px;
    }

    .sx-call-text strong {
        font-size: 15px;
        font-weight: 800;
    }

    .sx-call:hover strong {
        color: #0f7a3d;
    }

    .sx-quote-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 9px;
        min-height: 46px;
        padding: 12px 20px;
        overflow: hidden;
        border-radius: 12px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006 !important;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none !important;
        white-space: nowrap;
        box-shadow: 0 8px 20px rgba(245, 158, 11, .3);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .sx-quote-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px rgba(245, 158, 11, .4);
    }

    .sx-admin-link {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        min-height: 40px;
        padding: 8px 14px;
        border: 1px solid #d9e2db;
        border-radius: 10px;
        color: #20362a !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .sx-admin-link:hover {
        border-color: #0f7a3d;
        color: #0f7a3d !important;
    }

    .sx-menu-btn {
        display: none;
        width: 46px;
        height: 46px;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 0;
        border: 0;
        border-radius: 12px;
        background: #0f7a3d;
        cursor: pointer;
    }

    .sx-menu-btn span {
        display: block;
        width: 20px;
        height: 2.5px;
        border-radius: 3px;
        background: #fff;
    }

    .sx-menu-btn span:nth-child(2) {
        width: 14px;
        margin-left: 6px;
    }

    .sx-header-wrap a:focus-visible,
    .sx-header-wrap button:focus-visible,
    .sx-drawer a:focus-visible,
    .sx-drawer button:focus-visible,
    .sx-drawer summary:focus-visible {
        outline: 3px solid #fbbf24;
        outline-offset: 2px;
    }

    /* Mobile drawer */
    .sx-drawer-backdrop {
        position: fixed;
        inset: 0;
        z-index: 10001;
        background: rgba(2, 20, 14, .55);
        opacity: 0;
        visibility: hidden;
        transition: opacity .3s ease, visibility .3s;
    }

    .sx-drawer {
        position: fixed;
        top: 0;
        right: 0;
        bottom: 0;
        z-index: 10002;
        display: flex;
        flex-direction: column;
        width: min(380px, 90vw);
        overflow-y: auto;
        background: #fff;
        transform: translateX(100%);
        transition: transform .35s cubic-bezier(.2, .8, .2, 1);
        visibility: hidden;
    }

    .sx-drawer-open .sx-drawer {
        transform: none;
        visibility: visible;
    }

    .sx-drawer-open .sx-drawer-backdrop {
        opacity: 1;
        visibility: visible;
    }

    .sx-drawer-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 16px 18px;
        border-bottom: 1px solid #edf1ee;
    }

    .sx-drawer-close {
        display: grid;
        flex: 0 0 40px;
        width: 40px;
        height: 40px;
        place-items: center;
        border: 0;
        border-radius: 50%;
        background: #f0f4f1;
        color: #20362a;
        font-size: 18px;
        cursor: pointer;
    }

    .sx-drawer-nav {
        padding: 10px 18px;
    }

    .sx-drawer-nav > a,
    .sx-drawer-nav summary {
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 52px;
        padding: 12px 4px;
        border-bottom: 1px solid #edf1ee;
        color: #20362a !important;
        font-size: 16px;
        font-weight: 700;
        text-decoration: none !important;
        list-style: none;
        cursor: pointer;
    }

    .sx-drawer-nav summary::-webkit-details-marker {
        display: none;
    }

    .sx-drawer-nav a.is-current,
    .sx-drawer-nav details.is-current summary {
        color: #0f7a3d !important;
    }

    .sx-drawer-nav summary i {
        font-size: 12px;
        transition: transform .25s ease;
    }

    .sx-drawer-nav details[open] summary i {
        transform: rotate(180deg);
    }

    .sx-drawer-sub {
        display: grid;
        gap: 2px;
        padding: 8px 0 10px;
        border-bottom: 1px solid #edf1ee;
    }

    .sx-drawer-sub a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 12px;
        border-radius: 10px;
        color: #30443a !important;
        font-size: 14.5px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .sx-drawer-sub a:hover {
        background: #f0f7f2;
    }

    .sx-drawer-sub small {
        color: #89958e;
    }

    .sx-drawer-contact {
        display: grid;
        gap: 10px;
        margin-top: auto;
        padding: 18px;
        background: #f5f8f6;
    }

    .sx-drawer-contact a {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 48px;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none !important;
    }

    .sx-drawer-call {
        background: #0f7a3d;
        color: #fff !important;
    }

    .sx-drawer-whatsapp {
        background: #25d366;
        color: #fff !important;
    }

    .sx-drawer-quote {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006 !important;
    }

    .sx-drawer-contact p {
        margin: 6px 0 0;
        color: #65756d;
        font-size: 12.5px;
        line-height: 1.6;
        text-align: center;
    }

    @media (max-width: 1240px) {
        .sx-call-text {
            display: none;
        }
    }

    @media (max-width: 1080px) {
        .sx-nav,
        .sx-call,
        .sx-admin-link {
            display: none;
        }

        .sx-actions {
            margin-left: auto;
        }

        .sx-menu-btn {
            display: flex;
        }
    }

    @media (max-width: 760px) {
        .sx-topbar-badge {
            display: none;
        }

        .sx-topbar-inner {
            justify-content: center;
        }

        .sx-topbar-links .sx-topbar-email,
        .sx-topbar-links .sx-topbar-phone + .sx-topbar-phone {
            display: none;
        }
    }

    @media (max-width: 600px) {
        .sx-container {
            width: calc(100% - 28px);
        }

        .sx-header-inner,
        .sx-header-wrap.is-scrolled .sx-header-inner {
            min-height: 66px;
            gap: 12px;
        }

        .sx-quote-btn {
            display: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .sx-header-wrap *,
        .sx-drawer,
        .sx-drawer-backdrop {
            transition: none !important;
            animation: none !important;
        }
    }
</style>

<div class="sx-header-wrap" id="sx-header">
    {{-- TOP BAR --}}
    <div class="sx-topbar">
        <div class="sx-container sx-topbar-inner">
            <span class="sx-topbar-badge">
                <i class="fa-solid fa-award" aria-hidden="true"></i>
                ISO 9001:2015 Certified · Authorised Super Solar Distributor, H.P.
            </span>

            <div class="sx-topbar-links">
                @if (filled($siteSettings?->email))
                <a class="sx-topbar-email" href="mailto:{{ $siteSettings->email }}">
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i> {{ $siteSettings->email }}
                </a>
                @endif

                @foreach ($topBarPhones as $phone)
                <a class="sx-topbar-phone" href="{{ $telHref($phone) }}">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i> {{ $phone }}
                </a>
                @endforeach

                @php
                $socialLinks = collect($topBarSocials)->filter(fn ($social, $field) => filled($siteSettings?->{$field}));
                @endphp
                @if ($socialLinks->isNotEmpty())
                <div class="sx-topbar-socials" aria-label="Social media links">
                    @foreach ($socialLinks as $field => $social)
                    <a href="{{ $siteSettings->{$field} }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $social['label'] }}" title="{{ $social['label'] }}">
                        <i class="fa-brands {{ $social['icon'] }}" aria-hidden="true"></i>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MAIN BAR --}}
    <header class="sx-header">
        <div class="sx-container sx-header-inner">
            <x-frontend.logo />

            <nav class="sx-nav" aria-label="Main navigation">
                @foreach ($headerLinks as $link)
                @if ($link['is_products'] && $menuCategories->isNotEmpty())
                <div class="sx-nav-item sx-has-dropdown">
                    <a href="{{ $link['href'] }}" class="sx-nav-link {{ $link['is_current'] ? 'is-current' : '' }}" @if ($link['is_current']) aria-current="page" @endif>{{ $link['label'] }}</a>
                    <button type="button" class="sx-dd-toggle" aria-expanded="false" aria-controls="sx-products-menu" aria-label="Show product categories">
                        <i class="fa-solid fa-chevron-down" aria-hidden="true"></i>
                    </button>

                    <div class="sx-dropdown" id="sx-products-menu">
                        <ul class="sx-dd-list">
                            @foreach ($menuCategories as $category)
                            @php
                            $icon = match ($category->slug) {
                                'solar-water-heater' => 'fa-temperature-arrow-up',
                                'solar-street-lights' => 'fa-road',
                                'solar-power-plants' => 'fa-solar-panel',
                                'solar-fencing' => 'fa-shield-halved',
                                'solar-home-lighting' => 'fa-lightbulb',
                                default => 'fa-sun',
                            };
                            @endphp
                            <li>
                                <a href="{{ route('products.index', ['category' => $category->id]) }}">
                                    <span class="sx-dd-icon"><i class="fa-solid {{ $icon }}" aria-hidden="true"></i></span>
                                    <span class="sx-dd-name">{{ \Illuminate\Support\Str::title($category->name) }}</span>
                                    <span class="sx-dd-count">{{ $category->products_count }}</span>
                                </a>
                            </li>
                            @endforeach
                            <li class="sx-dd-all">
                                <a href="{{ route('products.index') }}">
                                    <span class="sx-dd-icon"><i class="fa-solid fa-grip" aria-hidden="true"></i></span>
                                    <span class="sx-dd-name">View All Products</span>
                                    <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                                </a>
                            </li>
                        </ul>

                        <div class="sx-dd-promo">
                            <i class="fa-solid fa-sun" aria-hidden="true"></i>
                            <strong>Not sure what you need?</strong>
                            <p>Tell us about your home or site and we’ll suggest the right system.</p>
                            <a href="{{ $quoteUrl }}">Get a Free Quote</a>
                        </div>
                    </div>
                </div>
                @else
                <div class="sx-nav-item">
                    <a href="{{ $link['href'] }}" class="sx-nav-link {{ $link['is_current'] ? 'is-current' : '' }}" @if ($link['is_current']) aria-current="page" @endif>{{ $link['label'] }}</a>
                </div>
                @endif
                @endforeach
            </nav>

            <div class="sx-actions">
                @if ($primaryPhone)
                <a href="{{ $telHref($primaryPhone) }}" class="sx-call" aria-label="Call {{ $primaryPhone }}">
                    <span class="sx-call-icon"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i></span>
                    <span class="sx-call-text">
                        <small>Call us anytime</small>
                        <strong>{{ $primaryPhone }}</strong>
                    </span>
                </a>
                @endif

                @if (auth()->check() && auth()->user()->is_admin)
                <a class="sx-admin-link" href="{{ route('admin.dashboard') }}">
                    <i class="fa-solid fa-gauge" aria-hidden="true"></i> Admin
                </a>
                @endif

                <a href="{{ $quoteUrl }}" class="sx-quote-btn">
                    Get a Quote <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>

                <button type="button" class="sx-menu-btn" aria-label="Open menu" aria-expanded="false" aria-controls="sx-drawer">
                    <span></span><span></span><span></span>
                </button>
            </div>
        </div>
    </header>
</div>

{{-- MOBILE DRAWER --}}
<div class="sx-drawer-backdrop" data-drawer-close aria-hidden="true"></div>
<aside class="sx-drawer" id="sx-drawer" aria-label="Mobile menu" aria-hidden="true">
    <div class="sx-drawer-head">
        <x-frontend.logo :tagline="false" />
        <button type="button" class="sx-drawer-close" data-drawer-close aria-label="Close menu">
            <i class="fa-solid fa-xmark" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="sx-drawer-nav" aria-label="Mobile navigation">
        @foreach ($headerLinks as $link)
        @if ($link['is_products'] && $menuCategories->isNotEmpty())
        <details class="{{ $link['is_current'] ? 'is-current' : '' }}" @if ($link['is_current']) open @endif>
            <summary>{{ $link['label'] }} <i class="fa-solid fa-chevron-down" aria-hidden="true"></i></summary>
            <div class="sx-drawer-sub">
                <a href="{{ route('products.index') }}">All Products <small>{{ $menuCategories->sum('products_count') }}</small></a>
                @foreach ($menuCategories as $category)
                <a href="{{ route('products.index', ['category' => $category->id]) }}">
                    {{ \Illuminate\Support\Str::title($category->name) }} <small>{{ $category->products_count }}</small>
                </a>
                @endforeach
            </div>
        </details>
        @else
        <a href="{{ $link['href'] }}" class="{{ $link['is_current'] ? 'is-current' : '' }}">{{ $link['label'] }}</a>
        @endif
        @endforeach

        @if (auth()->check() && auth()->user()->is_admin)
        <a href="{{ route('admin.dashboard') }}">Admin Dashboard</a>
        @endif
    </nav>

    <div class="sx-drawer-contact">
        <a href="{{ $quoteUrl }}" class="sx-drawer-quote" data-drawer-close>
            <i class="fa-solid fa-bolt" aria-hidden="true"></i> Get a Free Quote
        </a>
        @if ($primaryPhone)
        <a href="{{ $telHref($primaryPhone) }}" class="sx-drawer-call">
            <i class="fa-solid fa-phone" aria-hidden="true"></i> Call {{ $primaryPhone }}
        </a>
        @endif
        @if ($whatsappDigits !== '')
        <a href="https://wa.me/{{ $whatsappDigits }}" class="sx-drawer-whatsapp" target="_blank" rel="noopener">
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
        </a>
        @endif
        @if (filled($siteSettings?->address))
        <p><i class="fa-solid fa-location-dot" aria-hidden="true"></i> {{ $siteSettings->address }}</p>
        @endif
    </div>
</aside>

<script>
    (() => {
        const init = () => {
            const wrap = document.getElementById('sx-header');
            if (!wrap || wrap.dataset.ready === 'true') return;
            wrap.dataset.ready = 'true';

            // Shrink the header and hide the top bar once the page scrolls.
            // Separate on/off thresholds so the height change of the collapsing
            // header can't push scrollY back across the line and cause flicker.
            const onScroll = () => {
                const scrolled = wrap.classList.contains('is-scrolled');
                if (!scrolled && window.scrollY > 80) wrap.classList.add('is-scrolled');
                else if (scrolled && window.scrollY < 10) wrap.classList.remove('is-scrolled');
            };
            onScroll();
            window.addEventListener('scroll', onScroll, { passive: true });

            // Products dropdown: click/keyboard toggle in addition to hover.
            const dropdown = wrap.querySelector('.sx-has-dropdown');
            if (dropdown) {
                const toggle = dropdown.querySelector('.sx-dd-toggle');
                const setOpen = (open) => {
                    dropdown.classList.toggle('is-open', open);
                    toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
                };

                toggle.addEventListener('click', (event) => {
                    event.stopPropagation();
                    setOpen(!dropdown.classList.contains('is-open'));
                });

                document.addEventListener('click', (event) => {
                    if (!dropdown.contains(event.target)) setOpen(false);
                });

                dropdown.addEventListener('keydown', (event) => {
                    if (event.key === 'Escape') {
                        setOpen(false);
                        toggle.focus();
                    }
                });
            }

            // Mobile drawer.
            const drawer = document.getElementById('sx-drawer');
            const menuButton = wrap.querySelector('.sx-menu-btn');

            const setDrawer = (open) => {
                document.body.classList.toggle('sx-drawer-open', open);
                document.body.style.overflow = open ? 'hidden' : '';
                drawer.setAttribute('aria-hidden', open ? 'false' : 'true');
                menuButton.setAttribute('aria-expanded', open ? 'true' : 'false');
                if (open) drawer.querySelector('.sx-drawer-close').focus();
                else menuButton.focus({ preventScroll: true });
            };

            menuButton.addEventListener('click', () => setDrawer(true));

            document.querySelectorAll('[data-drawer-close]').forEach((element) => {
                element.addEventListener('click', () => setDrawer(false));
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && document.body.classList.contains('sx-drawer-open')) setDrawer(false);
            });

            window.matchMedia('(min-width: 1081px)').addEventListener('change', (query) => {
                if (query.matches && document.body.classList.contains('sx-drawer-open')) setDrawer(false);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init, { once: true });
        } else {
            init();
        }
    })();
</script>
