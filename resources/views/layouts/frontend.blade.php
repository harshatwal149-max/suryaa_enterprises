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

    @if($siteSettings?->favicon_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($siteSettings->favicon_path))
    <link rel="icon" href="{{ asset('storage/' . $siteSettings->favicon_path) }}">
    @else
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo-mark.svg') }}">
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

        /* Main content */
        .site-main {
            min-height: 50vh;
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 100%;
            min-width: 0;
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

        @media (max-width: 700px) {

            .footer-container {
                grid-template-columns: 1fr;
                gap: 28px;
            }

            .footer-map iframe {
                height: 220px;
            }
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