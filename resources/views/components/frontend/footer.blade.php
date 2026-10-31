@php
$siteSettings = \App\Models\SiteSetting::current();
$siteName = $siteSettings?->site_name ?: 'Suryaa Enterprises';

$footerPhones = array_filter([
$siteSettings?->phone_primary,
$siteSettings?->phone_secondary,
$siteSettings?->phone_tertiary,
], fn ($phone) => filled($phone));

$footerSocials = [
'facebook_url' => 'Facebook',
'instagram_url' => 'Instagram',
'youtube_url' => 'YouTube',
'linkedin_url' => 'LinkedIn',
];

$exploreLinks = collect($siteSettings?->header_links ?: [
['label' => 'Home', 'url' => '/', 'active' => true],
['label' => 'About Us', 'url' => '/about-us', 'active' => true],
['label' => 'Products', 'url' => '/products', 'active' => true],
['label' => 'Contact Us', 'url' => '/contact-us', 'active' => true],
])->filter(fn ($link) =>
is_array($link) &&
!empty($link['active']) &&
!empty($link['label']) &&
!empty($link['url'])
);

$normalizeUrl = fn ($url) =>
\Illuminate\Support\Str::startsWith(
$url,
['http://', 'https://', '/', '#', 'mailto:', 'tel:']
) ? $url : '/' . $url;

$mapUrl = trim((string) ($siteSettings?->map_embed_url ?? ''));
@endphp

<footer class="site-footer" id="site-footer">
    <div class="footer-container">

        {{-- COLUMN 1: COMPANY --}}
        <div class="footer-about">
            <x-frontend.logo variant="light" class="footer-logo" />

            <p class="footer-description">
                {{ $siteSettings?->footer_description
                    ?: ($siteName . ' — Super Solar Energy for a Changing World. ISO 9001:2015 Certified Company. Authorised Distributors in Himachal Pradesh. All Kinds of Solar Equipment.') }}
            </p>

            {{-- SOCIAL MEDIA ICONS --}}
            <div class="footer-social-icons" aria-label="Social media links">
                @foreach($footerSocials as $field => $label)
                @if(filled($siteSettings?->{$field}))
                <a
                    class="footer-social-icon footer-social-icon--{{ strtolower($label) }}"
                    href="{{ $siteSettings->{$field} }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    aria-label="{{ $label }}"
                    title="{{ $label }}">

                    @switch($label)

                    @case('Facebook')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M13.5 21v-8.2h2.8l.4-3.2h-3.2V7.5c0-.9.3-1.6 1.6-1.6h1.7V3.1c-.3 0-1.4-.1-2.6-.1-2.6 0-4.4 1.6-4.4 4.5v2.1H7v3.2h2.8V21h3.7z" />
                    </svg>
                    @break

                    @case('Instagram')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <rect x="3.2" y="3.2"
                            width="17.6" height="17.6" rx="5.2"
                            fill="none" stroke="currentColor"
                            stroke-width="2" />
                        <circle cx="12" cy="12" r="4.1"
                            fill="none" stroke="currentColor"
                            stroke-width="2" />
                        <circle cx="17.6" cy="6.7" r="1.25"
                            fill="currentColor" />
                    </svg>
                    @break

                    @case('YouTube')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M23.5 6.2a3 3 0 0 0-2.1-2.1C19.5 3.6 12 3.6 12 3.6s-7.5 0-9.4.5A3 3 0 0 0 .5 6.2 31 31 0 0 0 0 12a31 31 0 0 0 .5 5.8 3 3 0 0 0 2.1 2.1c1.9.5 9.4.5 9.4.5s7.5 0 9.4-.5a3 3 0 0 0 2.1-2.1A31 31 0 0 0 24 12a31 31 0 0 0-.5-5.8ZM9.6 15.6V8.4l6.2 3.6-6.2 3.6Z" />
                    </svg>
                    @break

                    @case('LinkedIn')
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path fill="currentColor"
                            d="M5.2 3.4a2.1 2.1 0 1 0 0 4.2 2.1 2.1 0 0 0 0-4.2ZM3.4 9h3.6v11.6H3.4V9Zm5.8 0h3.5v1.6h.1a3.8 3.8 0 0 1 3.4-1.9c3.6 0 4.3 2.4 4.3 5.5v6.4h-3.6v-5.7c0-1.4 0-3.1-1.9-3.1s-2.2 1.5-2.2 3v5.8H9.2V9Z" />
                    </svg>
                    @break

                    @endswitch
                </a>
                @endif
                @endforeach
            </div>
        </div>

        {{-- COLUMN 2: EXPLORE --}}
        <div class="footer-links footer-explore">
            <h3>Explore</h3>

            <nav class="footer-link-list" aria-label="Footer navigation">
                @foreach($exploreLinks as $link)
                <a href="{{ $normalizeUrl($link['url']) }}">
                    {{ $link['label'] }}
                </a>
                @endforeach
            </nav>
        </div>

        {{-- COLUMN 3: CONNECT --}}
        <div class="footer-contact" id="contact">
            <h3>Connect</h3>

            @if(filled($siteSettings?->email))
            <div class="footer-contact-item">
                <span class="footer-contact-label">Email</span>
                <div class="footer-contact-value">
                    <a href="mailto:{{ $siteSettings->email }}">
                        {{ $siteSettings->email }}
                    </a>
                </div>
            </div>
            @endif

            @if(count($footerPhones))
            <div class="footer-contact-item">
                <span class="footer-contact-label">Phone</span>
                <div class="footer-contact-value footer-phone-values">
                    @foreach($footerPhones as $phone)
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $phone) }}">
                        {{ $phone }}
                    </a>
                    @endforeach
                </div>
            </div>
            @endif

            @if(filled($siteSettings?->whatsapp_number))
            <div class="footer-contact-item">
                <span class="footer-contact-label">WhatsApp</span>
                <div class="footer-contact-value">
                    <a
                        href="https://wa.me/{{ preg_replace('/\D/', '', $siteSettings->whatsapp_number) }}"
                        target="_blank"
                        rel="noopener noreferrer">
                        {{ $siteSettings->whatsapp_number }}
                    </a>
                </div>
            </div>
            @endif

            @if(filled($siteSettings?->address))
            <div class="footer-contact-item">
                <span class="footer-contact-label">Address</span>
                <div class="footer-contact-value">
                    <p>{{ $siteSettings->address }}</p>
                </div>
            </div>
            @endif

            @if(filled($siteSettings?->gst_number))
            <div class="footer-contact-item">
                <span class="footer-contact-label">GST Number</span>
                <div class="footer-contact-value">
                    <p>{{ $siteSettings->gst_number }}</p>
                </div>
            </div>
            @endif
        </div>

        {{-- COLUMN 4: MAP --}}
        @if($mapUrl !== '')
        <div class="footer-reach">
            <h3>Reach Us</h3>

            <div class="footer-map">
                <iframe
                    src="{{ $mapUrl }}"
                    title="{{ $siteName }} location on Google Maps"
                    loading="lazy"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allowfullscreen>
                </iframe>
            </div>
        </div>
        @endif

    </div>

    {{-- COPYRIGHT --}}
    <div class="footer-bottom">
        <p>
            {{ $siteSettings?->copyright_text
                ?: ('© ' . date('Y') . ' ' . $siteName . '. All rights reserved.') }}
        </p>

        <span class="footer-credit">Brute Corp</span>   
    </div>
</footer>

<style>
    /* Footer layout: four balanced columns on desktop, stacked on small screens. */
    .site-footer,
    .site-footer * {
        box-sizing: border-box;
    }

    .site-footer .footer-container {
        display: grid;
        grid-template-columns: minmax(0, 1.05fr) minmax(120px, .7fr) minmax(0, 1.25fr) minmax(0, 1fr);
        align-items: start;
        gap: 30px;
        width: 100%;
        max-width: 1280px;
        margin: 0 auto;
        text-align: left;
    }

    .site-footer .footer-about,
    .site-footer .footer-links,
    .site-footer .footer-contact,
    .site-footer .footer-reach {
        min-width: 0;
        width: 100%;
    }

    .site-footer .footer-contact-item {
        display: grid !important;
        grid-template-columns: 112px minmax(0, 1fr) !important;
        align-items: start !important;
        column-gap: 12px !important;
        row-gap: 5px;
        width: 100%;
        min-width: 0;
        margin: 0 0 18px;
    }

    .site-footer .footer-contact-label {
        display: block;
        min-width: 0;
        margin: 0;
        white-space: normal;
        line-height: 1.7;
    }

    .site-footer .footer-contact-value {
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 8px 12px;
        min-width: 0;
        max-width: 100%;
        margin: 0;
        line-height: 1.7;
    }

    .site-footer .footer-contact-value a,
    .site-footer .footer-contact-value p {
        min-width: 0;
        max-width: 100%;
        margin: 0;
        overflow-wrap: anywhere;
        word-break: normal;
    }

    .site-footer .footer-phone-values {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .site-footer .footer-phone-values a {
        display: inline-block;
        max-width: 100%;
        white-space: normal;
        overflow-wrap: anywhere;
    }

    .site-footer .footer-map iframe {
        width: 100%;
        min-height: 220px;
        border: 0;
        border-radius: 8px;
    }

    .site-footer .footer-social-icons {
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 18px;
    }

    .site-footer .footer-social-icon {
        width: 42px;
        height: 42px;
        flex: 0 0 42px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, .16);
        background: rgba(255, 255, 255, .07);
        text-decoration: none;
        transition: transform .2s ease, background .2s ease, color .2s ease, box-shadow .2s ease;
    }

    .site-footer .footer-social-icon svg {
        display: block;
        width: 21px;
        height: 21px;
    }

    .site-footer .footer-social-icon--facebook {
        color: #1877F2;
    }

    .site-footer .footer-social-icon--instagram {
        color: #E4405F;
    }

    .site-footer .footer-social-icon--youtube {
        color: #FF0033;
    }

    .site-footer .footer-social-icon--linkedin {
        color: #0A66C2;
    }

    .site-footer .footer-social-icon--facebook:hover {
        background: #1877F2;
        color: #fff;
    }

    .site-footer .footer-social-icon--instagram:hover {
        background: linear-gradient(45deg, #FEDA75, #FA7E1E, #D62976, #962FBF, #4F5BD5);
        color: #fff;
    }

    .site-footer .footer-social-icon--youtube:hover {
        background: #FF0033;
        color: #fff;
    }

    .site-footer .footer-social-icon--linkedin:hover {
        background: #0A66C2;
        color: #fff;
    }

    .site-footer .footer-social-icon:hover {
        transform: translateY(-3px);
    }

    .site-footer .footer-social-icon:focus-visible {
        outline: 3px solid currentColor;
        outline-offset: 3px;
    }

    @media (max-width: 1100px) {
        .site-footer .footer-container {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 30px 26px;
        }

        .site-footer .footer-contact-item {
            grid-template-columns: 105px minmax(0, 1fr) !important;
            column-gap: 10px !important;
        }
    }

    @media (max-width: 600px) {
        .site-footer {
            padding: 32px 16px 22px;
        }

        .site-footer .footer-container {
            grid-template-columns: minmax(0, 1fr);
            gap: 28px;
        }

        .site-footer .footer-contact-item {
            grid-template-columns: 105px minmax(0, 1fr) !important;
            column-gap: 10px !important;
            margin-bottom: 16px;
        }

        .site-footer .footer-contact-value {
            overflow-wrap: anywhere;
        }

        .site-footer .footer-social-icons {
            gap: 10px;
        }

        .site-footer .footer-social-icon {
            width: 40px;
            height: 40px;
            flex-basis: 40px;
        }

        .site-footer .footer-map iframe {
            min-height: 200px;
        }

        .site-footer .footer-bottom {
            display: flex;
            flex-wrap: wrap;
            gap: 8px 16px;
            align-items: flex-start;
        }
    }

    @media (max-width: 360px) {
        .site-footer .footer-contact-item {
            grid-template-columns: minmax(0, 1fr) !important;
            row-gap: 3px;
        }

        .site-footer .footer-contact-label {
            font-weight: 600;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .site-footer .footer-social-icon {
            transition: none;
        }
    }
</style>