<?php
    $siteSettings = \App\Models\SiteSetting::first();
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
    );

    $topBarPhones = array_filter([
        $siteSettings?->phone_primary,
        $siteSettings?->phone_secondary,
        $siteSettings?->phone_tertiary,
    ], fn ($phone) => filled($phone));

    $topBarSocials = [
        'facebook_url'  => ['label' => 'Facebook',  'icon' => 'fa-facebook-f'],
        'instagram_url' => ['label' => 'Instagram', 'icon' => 'fa-instagram'],
        'linkedin_url'  => ['label' => 'LinkedIn',  'icon' => 'fa-linkedin-in'],
        'twitter_url'   => ['label' => 'X',         'icon' => 'fa-x-twitter'],
        'youtube_url'   => ['label' => 'YouTube',   'icon' => 'fa-youtube'],
    ];
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" referrerpolicy="no-referrer">

<style>
    /* Suryaa Enterprises header and contact bar */
    .top-contact-bar,
    .top-contact-bar *,
    .site-header,
    .site-header * {
        box-sizing: border-box;
    }

    .top-contact-bar {
        position: relative;
        top: auto;
        z-index: 1001;
        display: block;
        width: 100%;
        margin: 0;
        padding: 0;
        background: #f6f8f5;
        color: #244536;
        border-bottom: 1px solid #e5ebe6;
        font-family: inherit;
    }

    .top-contact-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px 24px;
        width: 100%;
        max-width: 1200px;
        min-height: 34px;
        margin: 0 auto;
        padding: 5px clamp(14px, 3vw, 28px);
    }

    .top-contact-details {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 6px 18px;
        min-width: 0;
    }

    .top-contact-item {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        min-width: 0;
        color: #244536;
        font-size: 12px;
        line-height: 1.5;
        text-decoration: none;
        overflow-wrap: anywhere;
    }

    .top-contact-item i {
        flex: 0 0 auto;
        color: #087443;
    }

    .top-contact-item:hover {
        color: #087443;
    }

    .top-contact-label {
        color: #6c7c72;
        font-size: 11px;
        white-space: nowrap;
    }

    .top-contact-socials {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        flex: 0 0 auto;
        gap: 7px;
    }

    .top-social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 28px;
        height: 28px;
        padding: 0;
        border: 0;
        border-radius: 0;
        background: transparent;
        color: #fff;
        font-size: 17px;
        line-height: 1;
        text-decoration: none;
        transition: color .2s ease, transform .2s ease;
    }

    .top-social-icon:hover {
        color: #d9a441;
        background: transparent;
        transform: translateY(-1px);
    }

    /* Main header */
    .site-header {
        position: relative;
        top: auto;
        z-index: 1000;
        display: block;
        width: 100%;
        margin: 0;
        padding: 9px clamp(14px, 3vw, 42px);
        background: #fff;
        border-bottom: 1px solid #e6ece7;
        box-shadow: 0 5px 18px rgba(15, 52, 33, .07);
        isolation: isolate;
        font-family: inherit;
    }

    .site-header .header-inner {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
        position: relative;
        width: 100%;
        max-width: 1440px;
        min-height: 64px;
        margin: 0 auto;
    }

    .site-header .site-logo {
        display: inline-flex;
        align-items: center;
        flex: 0 1 auto;
        min-width: 0;
        max-width: 45%;
        color: #17352c;
        text-decoration: none;
    }

    .site-header .site-logo img {
        display: block;
        width: auto;
        max-width: 100%;
        max-height: 64px;
        object-fit: contain;
        object-position: left center;
    }

    .site-header .site-name {
        color: #17352c;
        font-size: clamp(18px, 2vw, 25px);
        font-weight: 800;
        line-height: 1.2;
    }

    .site-header .site-nav {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: clamp(15px, 2vw, 30px);
        min-width: 0;
        margin-left: auto;
    }

    .site-header .site-nav > a {
        position: relative;
        display: inline-flex;
        align-items: center;
        min-height: 42px;
        padding: 7px 0;
        color: #244536;
        font-size: 14px;
        font-weight: 650;
        line-height: 1.4;
        text-decoration: none;
        white-space: nowrap;
        transition: color .2s ease;
    }

    .site-header .site-nav > a:not(.admin-dashboard-link)::after {
        position: absolute;
        right: 0;
        bottom: 3px;
        left: 0;
        height: 2px;
        border-radius: 2px;
        background: #087443;
        content: "";
        transform: scaleX(0);
        transform-origin: left;
        transition: transform .2s ease;
    }

    .site-header .site-nav > a:not(.admin-dashboard-link):hover {
        color: #087443;
    }

    .site-header .site-nav > a:not(.admin-dashboard-link):hover::after {
        transform: scaleX(1);
    }

    .site-header .site-nav > a.admin-dashboard-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 42px;
        padding: 0 19px;
        border: 1px solid #d9a441;
        border-radius: 999px;
        background: #d9a441;
        color: #17271d;
        font-size: 13px;
        font-weight: 750;
        text-align: center;
        white-space: nowrap;
        box-shadow: 0 3px 8px rgba(140, 99, 26, .12);
        transition: background .2s ease, transform .2s ease;
    }

    .site-header .site-nav > a.admin-dashboard-link:hover {
        background: #c99331;
        border-color: #c99331;
        transform: translateY(-1px);
    }

    /* Hidden on desktop; shown on tablet/mobile by media query */
    .site-header .mobile-nav-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        flex: 0 0 44px;
        width: 44px;
        min-width: 44px;
        height: 44px;
        min-height: 44px;
        padding: 0;
        margin: 0 0 0 auto;
        border: 0;
        border-radius: 10px;
        background: #087443;
        color: #fff;
        box-shadow: 0 3px 9px rgba(8, 116, 67, .18);
        cursor: pointer;
        flex-direction: column;
        gap: 5px;
        position: relative;
        z-index: 1003;
        appearance: none;
        -webkit-appearance: none;
        -webkit-tap-highlight-color: transparent;
        touch-action: manipulation;
        pointer-events: auto;
        line-height: 1;
    }

    .site-header .mobile-nav-toggle span {
        display: block;
        width: 22px;
        min-width: 22px;
        height: 3px;
        min-height: 3px;
        flex: 0 0 3px;
        margin: 0;
        padding: 0;
        border: 0;
        border-radius: 99px;
        background: #fff;
        opacity: 1;
        transform-origin: center;
        transition: transform .2s ease, opacity .18s ease;
    }

    .site-header .mobile-nav-toggle[aria-expanded="true"] span:nth-child(1) {
        transform: translateY(8px) rotate(45deg);
    }

    .site-header .mobile-nav-toggle[aria-expanded="true"] span:nth-child(2) {
        opacity: 0;
    }

    .site-header .mobile-nav-toggle[aria-expanded="true"] span:nth-child(3) {
        transform: translateY(-8px) rotate(-45deg);
    }

    .site-header .mobile-nav-toggle:focus-visible,
    .site-header .site-nav a:focus-visible,
    .top-contact-bar a:focus-visible {
        outline: 3px solid #d9a441;
        outline-offset: 3px;
    }

    @media (max-width: 1000px) {
        .site-header {
            padding-top: 8px;
            padding-bottom: 8px;
        }

        .site-header .header-inner {
            min-height: 58px;
            gap: 12px;
        }

        .site-header .site-logo {
            max-width: calc(100% - 58px);
        }

        .site-header .site-logo img {
            max-height: 56px;
        }

        .site-header .mobile-nav-toggle {
            display: inline-flex !important;
            visibility: visible !important;
            opacity: 1 !important;
        }

        .site-header #primary-navigation.site-nav {
            display: none !important;
            position: absolute;
            top: calc(100% + 9px);
            right: 0;
            left: 0;
            width: 100%;
            max-height: calc(100vh - 95px);
            max-height: calc(100dvh - 95px);
            overflow-x: hidden;
            overflow-y: auto;
            flex-direction: column;
            align-items: stretch;
            justify-content: flex-start;
            gap: 0;
            margin: 0;
            padding: 10px 16px 16px;
            border: 1px solid #e5ebe6;
            border-top: 3px solid #087443;
            border-radius: 0 0 14px 14px;
            background: #fff;
            box-shadow: 0 16px 35px rgba(10, 42, 27, .15);
            z-index: 1002;
        }

        .site-header #primary-navigation.site-nav.is-open {
            display: flex !important;
            visibility: visible !important;
            opacity: 1 !important;
            pointer-events: auto !important;
        }

        .site-header #primary-navigation.site-nav > a {
            display: flex;
            align-items: center;
            width: 100%;
            min-height: 48px;
            padding: 13px 10px;
            border-bottom: 1px solid #edf1ee;
            color: #244536;
            font-size: 15px;
            font-weight: 650;
            white-space: normal;
        }

        .site-header #primary-navigation.site-nav > a::after {
            display: none !important;
            content: none !important;
        }

        .site-header #primary-navigation.site-nav > a:last-child {
            border-bottom: 0;
        }

        .site-header #primary-navigation.site-nav > a.admin-dashboard-link {
            justify-content: center;
            width: 100%;
            min-height: 46px;
            margin-top: 12px;
            padding: 12px 18px;
            border: 0;
            border-radius: 9px;
            background: #d9a441;
            color: #17271d;
        }
    }

    @media (max-width: 600px) {
        .top-contact-inner {
            min-height: 0;
            flex-direction: column;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
        }

        .top-contact-details {
            justify-content: center;
            width: 100%;
            gap: 5px 14px;
        }

        .top-contact-item {
            justify-content: center;
            font-size: 11px;
            gap: 6px;
        }

        .top-contact-label {
            font-size: 10px;
        }

        .top-contact-socials {
            justify-content: center;
            gap: 7px;
        }

        .top-social-icon {
            width: 23px;
            height: 23px;
            font-size: 11px;
        }

        .site-header {
            padding: 7px 12px;
        }

        .site-header .header-inner {
            min-height: 52px;
            gap: 10px;
        }

        .site-header .site-logo {
            max-width: calc(100% - 54px);
        }

        .site-header .site-logo img {
            max-width: 100%;
            max-height: 48px;
        }

        .site-header .site-name {
            font-size: 18px;
        }

        .site-header .mobile-nav-toggle {
            flex-basis: 42px;
            width: 42px;
            min-width: 42px;
            height: 42px;
            min-height: 42px;
            border-radius: 9px;
        }

        .site-header #primary-navigation.site-nav {
            max-height: calc(100vh - 85px);
            max-height: calc(100dvh - 85px);
            padding: 8px 13px 14px;
        }
    }

    @media (max-width: 360px) {
        .top-contact-details {
            align-items: flex-start;
            flex-direction: column;
        }

        .top-contact-item {
            max-width: 100%;
        }

        .site-header .site-logo img {
            max-height: 42px;
        }
    }

    @media (min-width: 1001px) {
        .site-header .mobile-nav-toggle {
            display: none !important;
        }

        .site-header #primary-navigation.site-nav {
            display: flex !important;
            position: static;
            max-height: none;
            overflow: visible;
            border: 0;
            box-shadow: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .site-header *,
        .top-contact-bar * {
            scroll-behavior: auto !important;
            transition-duration: .01ms !important;
            animation-duration: .01ms !important;
            animation-iteration-count: 1 !important;
        }
    }
</style>


<div class="top-contact-bar">
    <div class="top-contact-inner">
        <div class="top-contact-details">
            <?php if(filled($siteSettings?->email)): ?>
                <a class="top-contact-item" href="mailto:<?php echo e($siteSettings->email); ?>">
                    <i class="fa-regular fa-envelope" aria-hidden="true"></i>
                    <span><?php echo e($siteSettings->email); ?></span>
                </a>
            <?php endif; ?>

            <?php $__currentLoopData = $topBarPhones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $phone): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a class="top-contact-item" href="tel:<?php echo e(preg_replace('/[^0-9+]/', '', $phone)); ?>">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i>
                    <span><?php echo e($phone); ?></span>
                    <?php if($loop->first): ?>
                        <span class="top-contact-label">(Enquiry)</span>
                    <?php endif; ?>
                </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="top-contact-socials" aria-label="Social media links">
            <?php $__currentLoopData = $topBarSocials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field => $social): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if(filled($siteSettings?->{$field})): ?>
                    <a
                        class="top-social-icon"
                        href="<?php echo e($siteSettings->{$field}); ?>"
                        target="_blank"
                        rel="noopener noreferrer"
                        aria-label="<?php echo e($social['label']); ?>"
                        title="<?php echo e($social['label']); ?>">
                        <i class="fa-brands <?php echo e($social['icon']); ?>" aria-hidden="true"></i>
                    </a>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</div>


<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo e(route('home')); ?>" class="site-logo" aria-label="<?php echo e($siteName); ?> home">
            <?php if($siteSettings?->logo_path): ?>
                <img src="<?php echo e(asset('storage/' . $siteSettings->logo_path)); ?>" alt="<?php echo e($siteName); ?>">
            <?php else: ?>
                <span class="site-name"><?php echo e($siteName); ?></span>
            <?php endif; ?>
        </a>

        <button
            class="mobile-nav-toggle"
            type="button"
            aria-label="Open navigation menu"
            aria-expanded="false"
            aria-controls="primary-navigation"
            title="Open menu"
            onclick="(function(btn){var header=btn.closest('.site-header');var nav=header&&header.querySelector('#primary-navigation');if(!nav)return;var open=!nav.classList.contains('is-open');nav.classList.toggle('is-open',open);btn.setAttribute('aria-expanded',open?'true':'false');btn.setAttribute('aria-label',open?'Close navigation menu':'Open navigation menu');btn.setAttribute('title',open?'Close menu':'Open menu');if(open){nav.style.setProperty('display','flex','important');nav.style.setProperty('visibility','visible','important');nav.style.setProperty('opacity','1','important');nav.style.setProperty('pointer-events','auto','important');}else{nav.style.removeProperty('display');nav.style.removeProperty('visibility');nav.style.removeProperty('opacity');nav.style.removeProperty('pointer-events');}})(this)">
            <span></span>
            <span></span>
            <span></span>
        </button>

        <nav class="site-nav" id="primary-navigation" aria-label="Main navigation">
            <?php $__currentLoopData = $headerLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $link): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $url = trim($link['url']);
                    $href = \Illuminate\Support\Str::startsWith(
                        $url,
                        ['http://', 'https://', '/', '#', 'mailto:', 'tel:']
                    ) ? $url : '/' . $url;
                ?>
                <a href="<?php echo e($href); ?>"><?php echo e($link['label']); ?></a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php if(auth()->check() && auth()->user()->is_admin): ?>
                <a class="admin-dashboard-link" href="<?php echo e(route('admin.dashboard')); ?>">
                    Admin Dashboard
                </a>
            <?php endif; ?>
        </nav>
    </div>
</header>

<script>
    (() => {
        const initHeaderNavigation = () => {
            const header = document.querySelector('.site-header');
            if (!header || header.dataset.navInitialized === 'true') return;

            const toggle = header.querySelector('.mobile-nav-toggle');
            const nav = header.querySelector('#primary-navigation');
            if (!toggle || !nav) return;

            header.dataset.navInitialized = 'true';
            const mobileQuery = window.matchMedia('(max-width: 1000px)');

            const closeMenu = () => {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
                toggle.setAttribute('aria-label', 'Open navigation menu');
                toggle.setAttribute('title', 'Open menu');
            };

            const openMenu = () => {
                nav.classList.add('is-open');
                toggle.setAttribute('aria-expanded', 'true');
                toggle.setAttribute('aria-label', 'Close navigation menu');
                toggle.setAttribute('title', 'Close menu');
            };

            toggle.addEventListener('click', (event) => {
                event.preventDefault();
                event.stopPropagation();

                if (toggle.getAttribute('aria-expanded') === 'true') {
                    closeMenu();
                } else {
                    openMenu();
                }
            });

            nav.querySelectorAll('a').forEach((link) => {
                link.addEventListener('click', closeMenu);
            });

            document.addEventListener('click', (event) => {
                if (!mobileQuery.matches || !nav.classList.contains('is-open')) return;
                if (!header.contains(event.target)) closeMenu();
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && nav.classList.contains('is-open')) {
                    closeMenu();
                    toggle.focus();
                }
            });

            const handleBreakpointChange = () => {
                closeMenu();
            };

            if (typeof mobileQuery.addEventListener === 'function') {
                mobileQuery.addEventListener('change', handleBreakpointChange);
            } else if (typeof mobileQuery.addListener === 'function') {
                mobileQuery.addListener(handleBreakpointChange);
            }
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initHeaderNavigation, { once: true });
        } else {
            initHeaderNavigation();
        }
    })();
</script>
<?php /**PATH C:\project\suryaa_enterprises\resources\views/components/frontend/header.blade.php ENDPATH**/ ?>