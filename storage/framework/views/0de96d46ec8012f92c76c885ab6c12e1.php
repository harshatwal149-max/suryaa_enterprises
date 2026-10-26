<?php
$siteSettings = \App\Models\SiteSetting::first();
$siteName = $siteSettings?->site_name ?: 'Suryaa Enterprises';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?> | <?php echo e($siteName); ?></title>

    <?php if($siteSettings?->favicon_path): ?>
    <link rel="icon" type="image/png" href="<?php echo e(asset('storage/' . $siteSettings->favicon_path)); ?>">
    <?php endif; ?>

    <style>
        :root {
            --green: #022c22;
            --green-light: #065f46;
            --cream: #faf7f1;
            --text: #1c1917;
            --muted: #78716c;
            --border: #e7e5e4;
            --sidebar-width: 256px;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            width: 100%;
            max-width: 100%;
            overflow-x: hidden;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: var(--cream);
            color: var(--text);
            -webkit-text-size-adjust: 100%;
        }

        button, a {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        a {
            text-decoration: none;
        }

        img {
            max-width: 100%;
            height: auto;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 200;
            width: var(--sidebar-width);
            background: var(--green);
            color: #ecfdf5;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
            transform: translateX(0);
            transition: transform .25s ease;
            will-change: transform;
        }

        .sidebar-brand {
            min-height: 82px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255, 255, 255, .09);
            white-space: nowrap;
            overflow: hidden;
            text-decoration: none;
            color: #fff;
        }

        .brand-logo {
            display: block;
            width: 72px;
            height: 48px;
            flex: 0 0 72px;
            object-fit: contain;
            object-position: center;
            background: #fff;
            border-radius: 7px;
            padding: 3px;
        }

        .brand-icon {
            width: 38px;
            height: 38px;
            min-width: 38px;
            display: grid;
            place-items: center;
            background: #f59e0b;
            color: var(--green);
            border-radius: 9px;
            font-size: 19px;
            font-weight: 800;
        }

        .brand-name {
            min-width: 0;
            color: #fff;
            font-size: 14px;
            font-weight: 700;
            line-height: 1.3;
            white-space: normal;
        }

        .sidebar-nav {
            flex: 1;
            min-height: 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
            padding: 16px 12px 12px;
            scrollbar-width: thin;
            scrollbar-color: rgba(255, 255, 255, .25) transparent;
        }

        .nav-item,
        .nav-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 43px;
            padding: 11px 12px;
            margin-bottom: 4px;
            color: rgba(236, 253, 245, .78);
            background: transparent;
            border: 0;
            border-radius: 8px;
            text-align: left;
            font-size: 13px;
            font-weight: 500;
            white-space: nowrap;
            transition: background .2s, color .2s;
        }

        .nav-item:hover,
        .nav-toggle:hover {
            background: rgba(255, 255, 255, .07);
            color: white;
        }

        .nav-item.active,
        .nav-toggle.group-active {
            background: #065f46;
            color: #fff;
        }

        .nav-icon {
            width: 20px;
            height: 20px;
            min-width: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
        }

        .nav-label {
            flex: 1;
        }

        .nav-chevron {
            font-size: 12px;
            transition: transform .2s;
        }

        .nav-toggle.expanded .nav-chevron {
            transform: rotate(90deg);
        }

        .nav-children {
            display: none;
            padding: 3px 0 6px 33px;
        }

        .nav-children.open {
            display: block;
        }

        .nav-child {
            display: block;
            padding: 10px 12px;
            margin-bottom: 2px;
            border-radius: 7px;
            color: rgba(236, 253, 245, .7);
            font-size: 12px;
        }

        .nav-child:hover,
        .nav-child.active {
            background: #065f46;
            color: white;
        }

        .nav-divider {
            height: 1px;
            background: rgba(255, 255, 255, .1);
            margin: 13px 5px;
        }

        .sidebar-logout {
            flex-shrink: 0;
            padding: 12px;
            border-top: 1px solid rgba(255, 255, 255, .12);
            background: var(--green);
        }

        .sidebar-logout form {
            margin: 0;
        }

        .sidebar-logout .logout-button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            min-height: 46px;
            margin: 0;
            padding: 11px 12px;
            border: 1px solid rgba(248, 113, 113, .18);
            border-radius: 9px;
            color: #fecaca;
            background: rgba(127, 29, 29, .12);
            font-weight: 600;
            font-size: 13px;
            transition: all 0.2s ease;
        }

        .sidebar-logout .logout-button:hover {
            color: #fff;
            background: rgba(220, 38, 38, .22);
            border-color: rgba(248, 113, 113, .35);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            z-index: 190;
            background: rgba(28, 25, 23, .45);
        }

        /* Desktop Content Layout */
        .main {
            position: relative;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
            margin-left: var(--sidebar-width) !important;
            transition: width .25s ease, margin-left .25s ease !important;
        }

        body.sidebar-collapsed .main {
            width: 100%;
            margin-left: 0 !important;
        }

        /* Topbar */
        .topbar {
            position: sticky;
            top: 0;
            z-index: 300;
            height: 65px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 0 25px;
            background: rgba(255, 255, 255, .96);
            border-bottom: 1px solid var(--border);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 15px;
            min-width: 0;
        }

        .hamburger {
            position: relative;
            z-index: 310;
            width: 48px;
            height: 48px;
            display: inline-flex !important;
            flex: 0 0 48px;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 5px;
            padding: 0;
            border: 0;
            border-radius: 12px;
            background: #14532d;
            color: #fff;
            cursor: pointer;
            touch-action: manipulation;
            transition: background 0.2s ease;
        }

        .hamburger:hover,
        .hamburger:focus-visible {
            background: #166534;
        }

        .hamburger span {
            display: block;
            width: 24px;
            height: 3px;
            background: #fff;
            border-radius: 2px;
            pointer-events: none;
        }

        .page-title {
            margin: 0;
            font-size: 18px;
            font-weight: 650;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .breadcrumb {
            margin-bottom: 3px;
            color: #a8a29e;
            font-size: 11px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .topbar-right {
            position: relative;
        }

        .user-button {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 8px;
            border: 0;
            border-radius: 30px;
            background: transparent;
            color: #44403c;
        }

        .user-button:hover {
            background: #f5f5f4;
        }

        .user-avatar {
            display: grid;
            place-items: center;
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--green-light);
            color: white;
            font-size: 13px;
            font-weight: 700;
        }

        .user-name {
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 13px;
        }

        .user-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            z-index: 320;
            width: 175px;
            padding: 6px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 11px;
            box-shadow: 0 12px 30px rgba(0, 0, 0, .1);
        }

        .user-dropdown a,
        .user-dropdown button {
            display: block;
            width: 100%;
            padding: 11px;
            border: 0;
            border-radius: 7px;
            background: transparent;
            color: #44403c;
            text-align: left;
            font-size: 13px;
        }

        .user-dropdown a:hover {
            background: #f5f5f4;
        }

        .user-dropdown button {
            color: #dc2626;
        }

        .user-dropdown button:hover {
            background: #fef2f2;
        }

        .hidden {
            display: none !important;
        }

        /* Content & Cards */
        .content {
            padding: clamp(16px, 3vw, 25px);
            width: 100%;
        }

        .welcome-card {
            padding: 24px;
            margin-bottom: 23px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .02);
        }

        .welcome-card h2 {
            margin: 0 0 9px;
            color: var(--green);
            font-size: 22px;
            font-weight: 700;
        }

        .welcome-card p {
            margin: 0;
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .section-heading {
            margin: 0 0 15px;
            font-size: 16px;
            font-weight: 650;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 17px;
            margin-bottom: 24px;
        }

        .stat-card {
            min-width: 0;
            padding: 20px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, .02);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            display: grid;
            place-items: center;
            margin-bottom: 15px;
            border-radius: 10px;
            background: var(--green);
            color: white;
            font-size: 19px;
        }

        .stat-icon.amber { background: #d97706; }
        .stat-icon.stone { background: #57534e; }

        .stat-value {
            margin-bottom: 5px;
            font-size: 26px;
            font-weight: 700;
            color: #1c1917;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 18px;
        }

        .info-card {
            padding: 22px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
        }

        .info-card h3 {
            margin: 0 0 12px;
            font-size: 15px;
        }

        .info-card p {
            margin: 0;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        /* Responsive Data Tables for Admin */
        .table-responsive,
        .overflow-x-auto {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 1rem;
            border-radius: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /* Desktop queries */
        @media (min-width: 901px) {
            .main {
                width: calc(100% - var(--sidebar-width));
                margin-left: var(--sidebar-width) !important;
            }

            body.sidebar-collapsed .sidebar {
                transform: translateX(-100%);
            }

            body.sidebar-collapsed .main {
                width: 100%;
                margin-left: 0 !important;
            }

            body:not(.sidebar-collapsed) .sidebar {
                transform: translateX(0);
            }

            .sidebar-overlay {
                display: none !important;
            }
        }

        /* Mobile and Tablet (<= 900px) */
        @media (max-width: 900px) {
            .main {
                width: 100% !important;
                margin-left: 0 !important;
            }

            .sidebar {
                transform: translateX(-100%);
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.25);
            }

            body.sidebar-open .sidebar {
                transform: translateX(0);
            }

            body.sidebar-open .sidebar-overlay {
                display: block;
            }

            .topbar {
                padding: 0 14px;
                height: 60px;
            }

            .user-name,
            .user-chevron {
                display: none;
            }

            .stats-grid {
                grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
                gap: 12px;
            }

            .stat-card {
                padding: 14px;
            }

            .stat-value {
                font-size: 22px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <a href="<?php echo e(route('admin.dashboard')); ?>" class="sidebar-brand">
            <?php if($siteSettings?->logo_path): ?>
            <img src="<?php echo e(asset('storage/' . $siteSettings->logo_path)); ?>" alt="<?php echo e($siteName); ?>" class="brand-logo">
            <?php else: ?>
            <div class="brand-icon">S</div>
            <?php endif; ?>
            <span class="brand-name"><?php echo e($siteName); ?></span>
        </a>

        <nav class="sidebar-nav">

            <a href="<?php echo e(route('admin.dashboard')); ?>" class="nav-item <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
                <span class="nav-icon">⌂</span>
                <span class="nav-label">Dashboard</span>
            </a>

            <?php
            $catalogActive = request()->routeIs(
                'admin.categories.*',
                'admin.subcategories.*',
                'admin.products.*'
            );
            $salesActive = request()->routeIs('admin.enquiries.*');
            ?>

            <button
                type="button"
                class="nav-toggle <?php echo e($catalogActive ? 'group-active expanded' : ''); ?>"
                data-group="catalog"
                aria-expanded="<?php echo e($catalogActive ? 'true' : 'false'); ?>">
                <span class="nav-icon">◇</span>
                <span class="nav-label">Catalog</span>
                <span class="nav-chevron">›</span>
            </button>

            <div class="nav-children <?php echo e($catalogActive ? 'open' : ''); ?>" id="group-catalog">
                <a href="<?php echo e(route('admin.categories.index')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.categories.*') ? 'active' : ''); ?>">
                    Categories
                </a>
                <a href="<?php echo e(route('admin.subcategories.index')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.subcategories.*') ? 'active' : ''); ?>">
                    Subcategories
                </a>
                <a href="<?php echo e(route('admin.products.index')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.products.*') ? 'active' : ''); ?>">
                    Products
                </a>
            </div>

            
            <a href="<?php echo e(route('admin.theme-colors.edit')); ?>"
                class="nav-item <?php echo e(request()->routeIs('admin.theme-colors.*') ? 'active' : ''); ?>">
                <span class="nav-icon">◉</span>
                <span class="nav-label">Theme Colors</span>
            </a>

            
            <a href="<?php echo e(route('admin.hero-slides.index')); ?>"
                class="nav-item <?php echo e(request()->routeIs('admin.hero-slides.*') ? 'active' : ''); ?>">
                <span class="nav-icon">▧</span>
                <span class="nav-label">Hero Banner / Slider</span>
            </a>

            
            <a href="<?php echo e(route('admin.testimonials.index')); ?>"
                class="nav-item <?php echo e(request()->routeIs('admin.testimonials.*') ? 'active' : ''); ?>">
                <span class="nav-icon">☆</span>
                <span class="nav-label">Testimonials</span>
            </a>

            <div class="nav-divider"></div>

            <?php
            $websiteSettingsActive = request()->routeIs(
                'admin.settings.*',
                'admin.theme-colors.*'
            );
            ?>

            <button
                type="button"
                class="nav-toggle <?php echo e($websiteSettingsActive ? 'group-active expanded' : ''); ?>"
                data-group="website"
                aria-expanded="<?php echo e($websiteSettingsActive ? 'true' : 'false'); ?>">
                <span class="nav-icon">◎</span>
                <span class="nav-label">Website Management</span>
                <span class="nav-chevron">›</span>
            </button>

            <div class="nav-children <?php echo e($websiteSettingsActive ? 'open' : ''); ?>" id="group-website">
                <a href="<?php echo e(route('admin.settings.edit')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.settings.edit', 'admin.settings.update') ? 'active' : ''); ?>">
                    General Settings
                </a>

                <a href="<?php echo e(route('admin.settings.logo')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.settings.logo', 'admin.settings.logo.update') ? 'active' : ''); ?>">
                    Logo &amp; Favicon
                </a>

                <a href="<?php echo e(route('admin.settings.contact')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.settings.contact', 'admin.settings.contact.update') ? 'active' : ''); ?>">
                    Contact Details
                </a>

                <a href="<?php echo e(route('admin.settings.seo')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.settings.seo', 'admin.settings.seo.update') ? 'active' : ''); ?>">
                    SEO Settings
                </a>

                <a href="<?php echo e(route('admin.settings.social')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.settings.social', 'admin.settings.social.update') ? 'active' : ''); ?>">
                    Social Media
                </a>
            </div>

            <button
                type="button"
                class="nav-toggle <?php echo e($salesActive ? 'group-active expanded' : ''); ?>"
                data-group="sales"
                aria-expanded="<?php echo e($salesActive ? 'true' : 'false'); ?>">
                <span class="nav-icon">▣</span>
                <span class="nav-label">Sales</span>
                <span class="nav-chevron">›</span>
            </button>

            <div class="nav-children <?php echo e($salesActive ? 'open' : ''); ?>" id="group-sales">
                <a href="<?php echo e(route('admin.enquiries.index')); ?>"
                    class="nav-child <?php echo e(request()->routeIs('admin.enquiries.*') ? 'active' : ''); ?>">
                    Enquiries
                </a>
                <a href="<?php echo e(route('admin.enquiries.index')); ?>" class="nav-child">Offers</a>
            </div>
        </nav>

        <div class="sidebar-logout">
            <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <button type="submit" class="logout-button">
                    <span class="nav-icon">⇥</span>
                    <span class="nav-label">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <div class="main">

        <header class="topbar">
            <div class="topbar-left">
                <button
                    type="button"
                    class="hamburger"
                    id="hamburger"
                    aria-label="Toggle sidebar"
                    aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <div>
                    <div class="breadcrumb"><?php echo $__env->yieldContent('breadcrumb', 'Admin / Dashboard'); ?></div>
                    <h1 class="page-title"><?php echo $__env->yieldContent('page-heading', 'Dashboard'); ?></h1>
                </div>
            </div>

            <div class="topbar-right">
                <button type="button" class="user-button" id="userButton">
                    <span class="user-avatar">
                        <?php echo e(strtoupper(substr(auth()->user()->name ?? 'A', 0, 1))); ?>

                    </span>

                    <span class="user-name">
                        <?php echo e(auth()->user()->name ?? 'Administrator'); ?>

                    </span>

                    <span class="user-chevron">⌄</span>
                </button>

                <div class="user-dropdown hidden" id="userDropdown">
                    <a href="<?php echo e(route('admin.settings.edit')); ?>">My Profile</a>

                    <form action="<?php echo e(route('admin.logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit">Logout</button>
                    </form>
                </div>
            </div>
        </header>

        
        <?php if(session('success') || session('error') || $errors->any()): ?>
        <div
            id="admin-flash-message"
            hidden
            data-icon="<?php echo e(session('success') ? 'success' : 'error'); ?>"
            data-title="<?php echo e(session('success') ? 'Success!' : (session('error') ? 'Error!' : 'Please check the form')); ?>"
            data-message="<?php echo e(session('success') ?? session('error') ?? $errors->first()); ?>">
        </div>
        <?php endif; ?>

        <main class="content">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>

    <script>
        // Set CSRF token for all jQuery AJAX requests
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        window.alert = function(message) {
            if (window.Swal) {
                return Swal.fire({
                    icon: 'info',
                    title: 'Notice',
                    text: String(message ?? ''),
                    confirmButtonColor: '#065f46'
                });
            }
        };

        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const hamburger = document.getElementById('hamburger');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const userButton = document.getElementById('userButton');
            const userDropdown = document.getElementById('userDropdown');
            const flashMessage = document.getElementById('admin-flash-message');

            if (flashMessage && window.Swal) {
                Swal.fire({
                    icon: flashMessage.dataset.icon || 'info',
                    title: flashMessage.dataset.title || 'Notice',
                    text: flashMessage.dataset.message || '',
                    confirmButtonColor: '#065f46'
                });
            }

            function isMobile() {
                return window.matchMedia('(max-width: 900px)').matches;
            }

            function closeSidebar() {
                body.classList.remove('sidebar-open');
                if (hamburger) hamburger.setAttribute('aria-expanded', 'false');
            }

            if (hamburger) {
                hamburger.addEventListener('click', function(event) {
                    event.preventDefault();
                    event.stopPropagation();

                    if (isMobile()) {
                        const isOpen = body.classList.toggle('sidebar-open');
                        hamburger.setAttribute('aria-expanded', String(isOpen));
                    } else {
                        const isCollapsed = body.classList.toggle('sidebar-collapsed');
                        hamburger.setAttribute('aria-expanded', String(!isCollapsed));
                    }
                });
            }

            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }

            document.querySelectorAll('.nav-toggle').forEach(function(button) {
                button.addEventListener('click', function() {
                    const group = document.getElementById('group-' + button.dataset.group);
                    if (!group) return;
                    const isOpen = group.classList.toggle('open');
                    button.classList.toggle('expanded', isOpen);
                    button.classList.toggle('group-active', isOpen);
                    button.setAttribute('aria-expanded', String(isOpen));
                });
            });

            if (userButton && userDropdown) {
                userButton.addEventListener('click', function(event) {
                    event.stopPropagation();
                    userDropdown.classList.toggle('hidden');
                });

                document.addEventListener('click', function(event) {
                    if (!event.target.closest('.topbar-right')) {
                        userDropdown.classList.add('hidden');
                    }
                });
            }

            window.addEventListener('resize', function() {
                closeSidebar();
                if (!isMobile()) body.classList.remove('sidebar-open');
            });

            document.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    closeSidebar();
                    if (userDropdown) userDropdown.classList.add('hidden');
                }
            });

            // Global AJAX Delete Confirmation with SweetAlert2
            $(document).on('submit', 'form.delete-form, form[data-ajax-delete]', function (e) {
                e.preventDefault();

                const $form = $(this);
                const deleteUrl = $form.attr('action');
                const $targetRow = $form.closest('tr, .item-card');

                Swal.fire({
                    title: 'Are you sure?',
                    text: 'This record will be deleted permanently!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#4b5563',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            type: 'POST',
                            data: $form.serialize(),
                            success: function (response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Record deleted successfully.',
                                    timer: 1600,
                                    showConfirmButton: false
                                });

                                $targetRow.fadeOut(300, function () {
                                    $(this).remove();
                                });
                            },
                            error: function (xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.message || 'Failed to delete record.'
                                });
                            }
                        });
                    }
                });
            });

            // Global AJAX Active / Inactive Status Toggle
            $(document).on('change', 'input[data-ajax-toggle]', function () {
                const $checkbox = $(this);
                const toggleUrl = $checkbox.data('url');
                const isChecked = $checkbox.is(':checked') ? 1 : 0;

                $.ajax({
                    url: toggleUrl,
                    type: 'POST',
                    data: {
                        status: isChecked
                    },
                    success: function (response) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: response.message || 'Status updated successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function (xhr) {
                        $checkbox.prop('checked', !isChecked);
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: xhr.responseJSON?.message || 'Failed to update status.',
                            showConfirmButton: false,
                            timer: 2500
                        });
                    }
                });
            });
        });
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>

</html><?php /**PATH C:\project\suryaa_enterprises\resources\views/admin/layout.blade.php ENDPATH**/ ?>