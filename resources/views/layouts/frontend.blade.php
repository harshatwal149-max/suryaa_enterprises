@php
$siteSettings = \App\Models\SiteSetting::first();

$siteName = $siteSettings?->site_name ?: 'Suryaa Enterprises';

$primaryColor = $siteSettings?->primary_color ?: '#047857';
$secondaryColor = $siteSettings?->secondary_color ?: '#022c22';
$headerBgColor = $siteSettings?->header_bg_color ?: '#ffffff';
$headerTextColor = $siteSettings?->header_text_color ?: '#292524';
$footerBgColor = $siteSettings?->footer_bg_color ?: '#022c22';
$footerTextColor = $siteSettings?->footer_text_color ?: '#ffffff';

$floatingWhatsappNumber = preg_replace(
    '/\D+/',
    '',
    (string) ($siteSettings?->whatsapp_number ?? '')
);

$hideFloatingButtons = request()->routeIs('testimonials.create');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettings?->seo_title ?: $siteName)</title>

    @if($siteSettings?->favicon_path)
    <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon_path) }}">
    @endif

    @if($siteSettings?->seo_description)
    <meta name="description" content="{{ $siteSettings->seo_description }}">
    @endif

    @if($siteSettings?->seo_keywords)
    <meta name="keywords" content="{{ $siteSettings->seo_keywords }}">
    @endif

    @if($siteSettings?->default_meta_robots)
    <meta name="robots" content="{{ $siteSettings->default_meta_robots }}">
    @endif

    @if($siteSettings?->seo_og_image_path)
    <meta property="og:image" content="{{ asset('storage/' . $siteSettings->seo_og_image_path) }}">
    @endif

    {{-- Application CSS and JavaScript --}}
    @vite([
        'resources/css/app.css',
        'resources/css/frontend.css',
        'resources/js/app.js'
    ])

    {{-- Font Awesome: header, footer and floating button icons --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

    {{-- Toastr CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <style>
        :root {
            --primary-color: {{ $primaryColor }};
            --secondary-color: {{ $secondaryColor }};
            --header-bg-color: {{ $headerBgColor }};
            --header-text-color: {{ $headerTextColor }};
            --footer-bg-color: {{ $footerBgColor }};
            --footer-text-color: {{ $footerTextColor }};
            --hero-bg-color: {{ $primaryColor }};
        }

        * {
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
            overflow-x: clip;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            color: #292524;
            overflow-x: clip;
        }

        img {
            max-width: 100%;
        }

        /* Restore the contact/social topbar and keep it above the main header. */
        .top-contact-bar {
            display: block !important;
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            min-height: 42px;
            height: auto;
            z-index: 10001 !important;
            background: var(--primary-color, #047857) !important;
            color: #ffffff !important;
        }

        /* Keep the main navigation fixed during scrolling, below the topbar. */
        .site-header,
        #site-header,
        .header-area {
            position: fixed !important;
            top: 42px !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
            z-index: 10000 !important;
            background: var(--header-bg-color, #ffffff) !important;
            opacity: 1 !important;
            box-shadow: 0 2px 14px rgba(0, 0, 0, 0.08) !important;
            transform: none !important;
            transition: none !important;
            animation: none !important;
        }

        .site-header-wrapper {
            position: static !important;
            transform: none !important;
        }

        .site-header {
            margin: 0 !important;
        }

        .site-header .header-inner {
            background-color: var(--header-bg-color, #ffffff) !important;
        }

        /* Header Inner Structure */
        .site-header {
            width: 100%;
            padding: 14px 40px;
            color: var(--header-text-color);
            border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        }

        .header-inner {
            max-width: 1200px;
            min-height: 60px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .site-logo {
            display: inline-flex;
            align-items: center;
            min-width: 0;
            text-decoration: none;
        }

        .site-logo img {
            display: block;
            max-width: 220px;
            max-height: 80px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .site-name {
            color: var(--header-text-color);
            font-size: 24px;
            font-weight: 700;
        }

        .site-nav {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-wrap: wrap;
            gap: 22px;
        }

        .site-nav a {
            color: var(--header-text-color);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            transition: color 0.2s ease;
        }

        .site-nav a:hover {
            color: var(--primary-color);
        }

        /* Main content */
        .site-main {
            min-height: 50vh;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 100%;
            min-width: 0;
            padding-top: 152px;
        }

        .site-main > * {
            max-width: 100%;
        }

        /* Footer */
        .site-footer {
            padding: 40px 20px 24px;
            background-color: var(--footer-bg-color) !important;
            color: var(--footer-text-color) !important;
            position: relative;
            z-index: 2;
        }

        .footer-container {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 32px;
            text-align: left;
        }

        .footer-container h3 {
            margin: 0 0 16px;
            font-size: 18px;
            color: var(--footer-text-color);
        }

        .footer-about p,
        .footer-contact p {
            margin: 0 0 12px;
            color: var(--footer-text-color);
            font-size: 14px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .footer-logo {
            display: inline-flex;
            margin-bottom: 16px;
            color: var(--footer-text-color);
            font-size: 22px;
            font-weight: 700;
            text-decoration: none;
        }

        .footer-logo img {
            display: block;
            max-width: 200px;
            max-height: 75px;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .footer-contact a,
        .footer-social a {
            color: var(--footer-text-color);
            text-decoration: underline;
            text-underline-offset: 3px;
            overflow-wrap: anywhere;
        }

        .footer-contact a:hover,
        .footer-social a:hover {
            color: var(--primary-color);
        }

        .footer-social {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 12px;
        }

        .footer-map iframe {
            display: block;
            width: 100%;
            max-width: 100%;
            border: 0;
        }

        .footer-bottom {
            max-width: 1200px;
            margin: 28px auto 0;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }

        .footer-bottom p {
            margin: 0;
            color: var(--footer-text-color);
            font-size: 13px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        /* Top contact bar */
        .top-contact-bar {
            background-color: var(--primary-color, #047857) !important;
            color: #ffffff !important;
            position: relative;
            z-index: 1001;
        }

        .top-contact-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 28px;
            padding: 8px 4%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .top-contact-details {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px 26px;
            min-width: 0;
        }

        .top-contact-item {
            display: inline-flex;
            align-items: center;
            gap: 9px;
            color: inherit;
            font-size: 13px;
            line-height: 1.6;
            text-decoration: none;
        }

        .top-contact-item i {
            flex-shrink: 0;
        }

        .top-contact-socials {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            flex-shrink: 0;
            gap: 19px;
        }

        .top-social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 23px;
            height: 28px;
            font-size: 16px;
            text-decoration: none;
            transition: opacity 0.2s ease, transform 0.2s ease;
        }

        .top-social-icon:hover {
            opacity: 0.75;
            transform: translateY(-2px);
        }

        .top-social-icon.social-facebook { color: #1877F2; }
        .top-social-icon.social-instagram { color: #E4405F; }
        .top-social-icon.social-linkedin { color: #0A66C2; }
        .top-social-icon.social-x { color: #111111; }
        .top-social-icon.social-youtube { color: #FF0000; }

        /* Floating WhatsApp and Back-to-top buttons */
        .floating-whatsapp,
        .back-to-top {
            position: fixed;
            right: 22px;
            z-index: 1100;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 48px;
            height: 48px;
            border: none;
            border-radius: 50%;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.18);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transform: translateY(12px);
            transition: opacity 0.25s ease, visibility 0.25s ease, transform 0.25s ease;
        }

        .floating-whatsapp.is-visible,
        .back-to-top.is-visible {
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
            transform: translateY(0);
        }

        .floating-whatsapp {
            bottom: 22px;
            background: #25D366;
            color: #ffffff;
            font-size: 26px;
            text-decoration: none;
        }

        .back-to-top {
            bottom: 82px;
            background: var(--primary-color, #047857);
            color: #ffffff;
            font-size: 18px;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .top-contact-inner {
                padding-right: 22px;
                padding-left: 22px;
                flex-wrap: wrap;
                gap: 12px 20px;
            }

            .top-contact-details {
                gap: 10px 18px;
            }

            .site-header {
                padding-right: 24px;
                padding-left: 24px;
            }

            .header-inner {
                gap: 18px;
            }

            .site-nav {
                gap: 16px;
            }
        }

        @media (max-width: 700px) {
            .site-header {
                padding: 14px 18px;
            }

            .top-contact-bar {
                max-height: 88px;
                overflow-y: auto;
            }

            .site-header,
            #site-header,
            .header-area {
                top: 88px !important;
            }

            .site-main {
                padding-top: 190px;
            }

            .header-inner {
                flex-direction: column;
                justify-content: center;
                gap: 16px;
            }

            .site-logo img {
                max-width: 190px;
                max-height: 65px;
            }

            .site-name {
                font-size: 21px;
            }

            .site-nav {
                justify-content: center;
                gap: 12px 18px;
            }

            .site-nav a {
                font-size: 13px;
            }

            .footer-container {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .footer-map iframe {
                height: 220px;
            }

            .top-contact-inner {
                flex-direction: column;
                align-items: flex-start;
                padding: 12px 16px;
                gap: 12px;
            }

            .top-contact-details {
                flex-direction: column;
                align-items: flex-start;
                gap: 9px;
                width: 100%;
            }

            .top-contact-item {
                max-width: 100%;
                overflow-wrap: anywhere;
            }

            .top-contact-socials {
                justify-content: flex-start;
                gap: 20px;
            }
        }

        @media (max-width: 380px) {
            .site-header {
                padding-right: 12px;
                padding-left: 12px;
            }

            .site-main {
                padding-top: 180px;
            }

            .site-nav {
                gap: 10px 14px;
            }

            .top-contact-inner {
                padding-right: 12px;
                padding-left: 12px;
            }
        }

        /* Admin-controlled theme colors */
        .top-contact-bar .top-contact-item,
        .top-contact-bar .top-contact-item a,
        .top-contact-bar .top-contact-item span,
        .top-contact-bar .top-contact-item i {
            color: inherit !important;
        }

        .site-header .site-name,
        .site-header .site-nav a,
        .site-header .site-logo,
        .site-header .site-logo span {
            color: var(--header-text-color, #292524) !important;
        }

        .site-footer .footer-container h3,
        .site-footer .footer-logo,
        .site-footer .footer-description,
        .site-footer .footer-link-list a,
        .site-footer .footer-contact,
        .site-footer .footer-contact a,
        .site-footer .footer-reach,
        .site-footer .footer-bottom p {
            color: var(--footer-text-color, #ffffff) !important;
        }
    </style>

    @stack('styles')
</head>

<body>

    {{-- Reusable Header Component --}}
    <x-frontend.header />

    {{-- Page Content --}}
    <main class="site-main">
        @yield('content')
    </main>

    {{-- Reusable Footer Component --}}
    <x-frontend.footer />

    {{-- Hide floating buttons on the Write Review page only --}}
    @unless ($hideFloatingButtons)
        @if ($floatingWhatsappNumber !== '')
        <a
            href="https://wa.me/{{ $floatingWhatsappNumber }}"
            class="floating-whatsapp"
            id="floatingWhatsapp"
            target="_blank"
            rel="noopener noreferrer"
            aria-label="Chat with Suryaa Enterprises on WhatsApp"
            title="Chat on WhatsApp">
            <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
        </a>
        @endif

        <button
            type="button"
            class="back-to-top"
            id="backToTop"
            aria-label="Back to top"
            title="Back to top">
            <i class="fa-solid fa-arrow-up" aria-hidden="true"></i>
        </button>

        <script>
            (() => {
                const whatsappButton = document.getElementById('floatingWhatsapp');
                const backToTopButton = document.getElementById('backToTop');
                const revealAfter = 180;

                const updateFloatingButtons = () => {
                    const showButtons = window.scrollY > revealAfter;
                    [whatsappButton, backToTopButton].forEach((button) => {
                        if (button) {
                            button.classList.toggle('is-visible', showButtons);
                        }
                    });
                };

                if (backToTopButton) {
                    backToTopButton.addEventListener('click', () => {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth'
                        });
                    });
                }

                window.addEventListener('scroll', updateFloatingButtons, {
                    passive: true
                });
                window.addEventListener('load', updateFloatingButtons);
                updateFloatingButtons();
            })();
        </script>
    @endunless

    {{-- jQuery and Toastr JavaScript --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    {{-- Global AJAX CSRF Setup and Flash Notifications --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof window.toastr === 'undefined') {
                return;
            }

            toastr.options = {
                closeButton: true,
                progressBar: true,
                positionClass: 'toast-top-right',
                timeOut: 4000,
                extendedTimeOut: 1000,
                preventDuplicates: true,
                newestOnTop: true
            };

            @if (session()->has('success'))
                toastr.success(@json(session('success')), 'Success');
            @endif

            @if (session()->has('error'))
                toastr.error(@json(session('error')), 'Error');
            @endif

            @if ($errors->any())
                toastr.error(@json($errors->first()), 'Validation Error');
            @endif
        });
    </script>

    @stack('scripts')

</body>

</html>