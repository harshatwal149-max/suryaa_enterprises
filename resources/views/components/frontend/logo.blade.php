@props([
    // "dark" text for light backgrounds (header), "light" text for dark backgrounds (footer).
    'variant' => 'dark',
    'tagline' => true,
])

@php
    // Prefer the logo uploaded in Admin → Settings; fall back to the built-in mark + wordmark.
    $logoSettings = \App\Models\SiteSetting::first();
    $uploadedLogo = $logoSettings?->logo_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($logoSettings->logo_path)
        ? asset('storage/' . $logoSettings->logo_path)
        : null;
    $logoAlt = $logoSettings?->site_name ?: 'Suryaa Enterprises';
@endphp

@once
<style>
    .sx-logo {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        min-width: 0;
        color: inherit;
        text-decoration: none !important;
    }

    .sx-logo-mark {
        display: block;
        flex: 0 0 auto;
        width: 50px;
        height: 50px;
        border-radius: 12px;
        box-shadow: 0 6px 16px rgba(6, 13, 10, .22);
        transition: transform .3s ease;
    }

    .sx-logo:hover .sx-logo-mark {
        transform: rotate(-4deg) scale(1.04);
    }

    .sx-logo-text {
        display: flex;
        flex-direction: column;
        min-width: 0;
        line-height: 1;
    }

    .sx-logo-name {
        color: #0f7a3d;
        font-family: "Arial Black", "Helvetica Neue", Arial, sans-serif;
        font-size: 22px;
        font-weight: 900;
        letter-spacing: .5px;
        white-space: nowrap;
    }

    .sx-logo-name span {
        display: block;
        margin-top: 3px;
        color: #14281e;
        font-family: Arial, Helvetica, sans-serif;
        font-size: 11.5px;
        font-weight: 800;
        letter-spacing: 4.6px;
    }

    .sx-logo-tagline {
        margin-top: 5px;
        color: #b7791f;
        font-size: 10.5px;
        font-style: italic;
        font-weight: 700;
        letter-spacing: .3px;
        white-space: nowrap;
    }

    .sx-logo-light .sx-logo-name {
        color: #4ade80;
    }

    .sx-logo-light .sx-logo-name span {
        color: #fff;
    }

    .sx-logo-light .sx-logo-tagline {
        color: #fbbf24;
    }

    /* Uploaded logo image */
    .sx-logo-img {
        display: block;
        width: auto;
        height: 60px;
        max-width: 100%;
        object-fit: contain;
    }

    /* On dark backgrounds the uploaded logo sits on a white badge */
    .sx-logo-light.sx-logo-uploaded {
        padding: 10px 16px;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(0, 0, 0, .18);
    }

    .sx-logo-light .sx-logo-img {
        height: 54px;
    }

    @media (max-width: 600px) {
        .sx-logo {
            gap: 10px;
        }

        .sx-logo-mark {
            width: 42px;
            height: 42px;
            border-radius: 10px;
        }

        .sx-logo-name {
            font-size: 18px;
        }

        .sx-logo-name span {
            font-size: 9.5px;
            letter-spacing: 3.6px;
        }

        .sx-logo-tagline {
            display: none;
        }

        .sx-logo-img {
            height: 46px;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .sx-logo-mark {
            transition: none;
        }
    }
</style>
@endonce

<a href="{{ route('home') }}" {{ $attributes->class(['sx-logo', 'sx-logo-light' => $variant === 'light', 'sx-logo-uploaded' => $uploadedLogo]) }} aria-label="{{ $logoAlt }} home">
    @if ($uploadedLogo)
    <img class="sx-logo-img" src="{{ $uploadedLogo }}" alt="{{ $logoAlt }}">
    @else
    <img class="sx-logo-mark" src="{{ asset('images/logo-mark.svg') }}" alt="" width="50" height="50">
    <span class="sx-logo-text">
        <span class="sx-logo-name">SURYAA<span>ENTERPRISES</span></span>
        @if ($tagline)
        <span class="sx-logo-tagline">Solar Solutions · Himachal Pradesh</span>
        @endif
    </span>
    @endif
</a>
