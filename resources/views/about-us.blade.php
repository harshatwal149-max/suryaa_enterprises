@extends('layouts.frontend')

@section('content')
@php
$settings = \App\Models\SiteSetting::first();

$heading = $settings?->about_heading ?: 'Reliable Solar Solutions. Built Around Your Needs.';
$description = $settings?->about_description
?: 'Suryaa Enterprises provides dependable solar energy products and practical solutions for residential, commercial and infrastructure requirements.';

$highlights = collect([
$settings?->about_highlight_one ?: 'Solar Water Heaters',
$settings?->about_highlight_two ?: 'Solar Street Lights',
$settings?->about_highlight_three ?: 'Solar Power Plants',
$settings?->about_highlight_four ?: 'Solar Fencing Solutions',
])->filter(fn ($item) => filled($item))->values();

$aboutImage = $settings?->about_image
? asset('storage/' . $settings->about_image)
: asset('images/about-bg.jpg');
@endphp

{{-- CORPORATE HERO --}}
<section class="se-corp-hero">
    <div class="se-corp-hero-overlay"></div>

    <div class="se-corp-container se-corp-hero-inner">
        <div class="se-corp-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <svg viewBox="0 0 24 24" aria-hidden="true">
                <path d="m9 18 6-6-6-6" />
            </svg>
            <span>About Us</span>
        </div>

        <div class="se-corp-hero-copy">
            <span class="se-corp-overline">
                <span></span>
                ABOUT SURYAA ENTERPRISES
            </span>

            <h1>
                Energy Solutions That
                <span>Move Business Forward.</span>
            </h1>

            <p>
                We help customers explore dependable solar products and
                practical energy solutions for their homes, businesses
                and projects.
            </p>

            <div class="se-corp-hero-actions">
                <a href="{{ route('home') }}#products" class="se-corp-button se-corp-button-primary">
                    Explore Our Products
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 12h14M12 5l7 7-7 7" />
                    </svg>
                </a>

                <a href="{{ route('enquiry') }}" class="se-corp-button se-corp-button-ghost">
                    Discuss Your Requirements
                </a>
            </div>
        </div>

    </div>
</section>

{{-- TRUST / VALUE BAR --}}
<section class="se-corp-trust">
    <div class="se-corp-container se-corp-trust-grid">
        <div class="se-corp-trust-item">
            <span class="se-corp-line-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="m12 3 8 4v5c0 5-3.5 8-8 9-4.5-1-8-4-8-9V7l8-4Z" />
                    <path d="m8.5 12 2.3 2.3 4.7-4.8" />
                </svg>
            </span>
            <div>
                <strong>Quality Focus</strong>
                <span>Products selected with care</span>
            </div>
        </div>

        <div class="se-corp-trust-item">
            <span class="se-corp-line-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 21s-8-4.4-8-11a4.5 4.5 0 0 1 8-2.8A4.5 4.5 0 0 1 20 10c0 6.6-8 11-8 11Z" />
                </svg>
            </span>
            <div>
                <strong>Customer Focus</strong>
                <span>Requirements come first</span>
            </div>
        </div>

        <div class="se-corp-trust-item">
            <span class="se-corp-line-icon">
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M12 3v18m0-18C8 3 5 6 5 10c4 0 7-3 7-7Zm0 7c4 0 7 3 7 7-4 0-7-3-7-7Z" />
                    <path d="M5 16c0 2 2 4 7 5" />
                </svg>
            </span>
            <div>
                <strong>Sustainable Thinking</strong>
                <span>Supporting cleaner energy choices</span>
            </div>
        </div>
    </div>
</section>

{{-- COMPANY INTRO --}}
<section class="se-corp-about">
    <div class="se-corp-container se-corp-about-grid">
        <div class="se-corp-visual-column">
            <div class="se-corp-image-frame">
                <img src="{{ $aboutImage }}"
                     alt="Solar energy solutions from Suryaa Enterprises"
                     loading="lazy">
            </div>
            <div class="se-corp-image-footnote">
                <span></span>
                Dependable products. Practical solutions.
            </div>
        </div>

        <div class="se-corp-about-copy">
            <span class="se-corp-section-tag">WHO WE ARE</span>
            <h2>{{ $heading }}</h2>
            <div class="se-corp-description">
                {!! nl2br(e($description)) !!}
            </div>
            <div class="se-corp-highlight-list">
                @foreach($highlights as $highlight)
                <div class="se-corp-highlight">
                    <span class="se-corp-check">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m5 12 4 4L19 6" />
                        </svg>
                    </span>
                    <span>{{ $highlight }}</span>
                </div>
                @endforeach
            </div>
            <a href="{{ route('enquiry') }}" class="se-corp-inline-link">
                Talk to Our Team
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14m-7-7 7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>

{{-- SOLUTIONS --}}
<section class="se-corp-solutions">
    <div class="se-corp-container">
        <div class="se-corp-solutions-heading">
            <div>
                <span class="se-corp-section-tag">OUR SOLUTIONS</span>
                <h2>Products for Different Energy Needs</h2>
                <p>
                    Explore our solar product range and contact our team
                    to discuss your specific application or project.
                </p>
            </div>

            <a href="{{ route('home') }}#products" class="se-corp-all-link">
                View Products
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M7 17 17 7M7 7h10v10" />
                </svg>
            </a>
        </div>

        <div class="se-corp-solution-grid">
            @foreach($highlights as $index => $highlight)
            <article class="se-corp-solution-card">
                <div class="se-corp-solution-top">
                    <span class="se-corp-solution-number">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </span>

                    <span class="se-corp-solution-icon">
                        @if($index === 0)
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <circle cx="12" cy="12" r="4" />
                            <path d="M12 2v2m0 16v2M4.9 4.9l1.5 1.5m11.2 11.2 1.5 1.5M2 12h2m16 0h2M4.9 19.1l1.5-1.5M17.6 6.4l1.5-1.5" />
                        </svg>
                        @elseif($index === 1)
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3v5m0 8v5M4.2 7.5l3.5 2m8.6 5 3.5 2M4 17l4-2m8-5 4-2" />
                            <circle cx="12" cy="12" r="4" />
                        </svg>
                        @elseif($index === 2)
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="m13 2-8 12h6l-1 8 9-13h-6l1-7Z" />
                        </svg>
                        @else
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M12 3 3 7v5c0 5 4 8 9 9 5-1 9-4 9-9V7l-9-4Z" />
                            <path d="m8 12 2.5 2.5L16 9" />
                        </svg>
                        @endif
                    </span>
                </div>

                <h3>{{ $highlight }}</h3>

                <p>
                    Discuss your requirements with our team to explore
                    a suitable product or solution.
                </p>

                <a href="{{ route('enquiry') }}">
                    Make an Enquiry
                    <svg viewBox="0 0 24 24" aria-hidden="true">
                        <path d="M5 12h14m-7-7 7 7-7 7" />
                    </svg>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>

{{-- BUSINESS ENQUIRY CTA --}}
<section class="se-corp-cta-section">
    <div class="se-corp-container">
        <div class="se-corp-cta">
            <div class="se-corp-cta-content">
                <span class="se-corp-cta-label">LET'S DISCUSS YOUR PROJECT</span>
                <h2>Tell Us What Your Business Needs.</h2>
                <p>
                    Share your product requirements or project details.
                    Our team can help you explore the available options.
                </p>
            </div>

            <a href="{{ route('enquiry') }}" class="se-corp-button se-corp-button-cta">
                Send an Enquiry
                <svg viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5 12h14m-7-7 7 7-7 7" />
                </svg>
            </a>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
    /* =========================================================
   SURYAA ENTERPRISES — CORPORATE ABOUT PAGE
   Palette: navy, white, slate, restrained green
========================================================= */
    .se-corp-container {
        width: min(1200px, calc(100% - 48px));
        margin-inline: auto;
    }

    .se-corp-hero {
        position: relative;
        isolation: isolate;
        min-height: 500px;
        display: flex;
        align-items: center;
        overflow: hidden;
        background-color: #163e36;
        background-image: url('{{ $aboutImage }}');
        background-position: center center;
        background-size: 100% 100%;
        background-repeat: no-repeat;
        color: #fff;
    }

    .se-corp-hero-overlay {
        position: absolute;
        z-index: -1;
        inset: 0;
        background: linear-gradient(90deg,
                rgba(14, 35, 48, .84) 0%,
                rgba(14, 43, 53, .70) 48%,
                rgba(14, 43, 53, .40) 100%);
    }

    .se-corp-hero-inner {
        position: relative;
        padding-block: 45px 80px;
    }

    .se-corp-breadcrumb {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-bottom: 48px;
        color: rgba(255, 255, 255, .78);
        font-size: 13px;
    }

    .se-corp-breadcrumb a {
        color: #fff;
        text-decoration: none;
    }

    .se-corp-breadcrumb svg {
        width: 13px;
        height: 13px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
    }

    .se-corp-overline {
        display: flex;
        align-items: center;
        gap: 11px;
        color: #c8e2d2;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .se-corp-overline>span {
        width: 27px;
        height: 2px;
        background: #7cbea0;
    }

    .se-corp-hero-copy {
        max-width: 780px;
    }

    .se-corp-hero h1 {
        margin: 20px 0 18px;
        color: #fff;
        font-size: clamp(36px, 5vw, 62px);
        line-height: 1.12;
        font-weight: 800;
        letter-spacing: -1.3px;
    }

    .se-corp-hero h1 span {
        display: block;
        color: #c8e2d2;
    }

    .se-corp-hero-copy>p {
        max-width: 650px;
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 15px;
        line-height: 1.9;
    }

    .se-corp-hero-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 28px;
    }

    .se-corp-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 17px;
        min-height: 48px;
        padding: 13px 19px;
        border: 1px solid transparent;
        border-radius: 5px;
        font-size: 12px;
        font-weight: 750;
        text-decoration: none;
        transition: background .2s, border-color .2s, transform .2s;
    }

    .se-corp-button:hover {
        transform: translateY(-2px);
    }

    .se-corp-button svg,
    .se-corp-all-link svg,
    .se-corp-inline-link svg,
    .se-corp-solution-card a svg {
        width: 17px;
        height: 17px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.8;
        stroke-linecap: round;
        stroke-linejoin: round;
        flex-shrink: 0;
    }

    .se-corp-button-primary {
        background: #fff;
        color: #173d36;
    }

    .se-corp-button-primary:hover {
        background: #eaf4ef;
        color: #173d36;
    }

    .se-corp-button-ghost {
        border-color: rgba(255, 255, 255, .45);
        color: #fff;
        background: rgba(255, 255, 255, .04);
    }

    .se-corp-button-ghost:hover {
        border-color: #fff;
        color: #fff;
    }

    /* TRUST STRIP */
    .se-corp-trust {
        background: #fff;
        border-bottom: 1px solid #e6ebee;
    }

    .se-corp-trust-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        padding-block: 23px;
    }

    .se-corp-trust-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 13px;
        padding: 5px 20px;
        border-right: 1px solid #e5eaed;
    }

    .se-corp-trust-item:first-child {
        justify-content: flex-start;
        padding-left: 0;
    }

    .se-corp-trust-item:last-child {
        justify-content: flex-end;
        padding-right: 0;
        border-right: 0;
    }

    .se-corp-line-icon {
        display: grid;
        place-items: center;
        flex: 0 0 42px;
        width: 42px;
        height: 42px;
        border-radius: 7px;
        background: #edf4f1;
        color: #28664f;
    }

    .se-corp-line-icon svg {
        width: 21px;
        height: 21px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.6;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .se-corp-trust-item strong,
    .se-corp-trust-item div span {
        display: block;
    }

    .se-corp-trust-item strong {
        color: #243b48;
        font-size: 12px;
    }

    .se-corp-trust-item div span {
        margin-top: 5px;
        color: #76838a;
        font-size: 11px;
        line-height: 1.5;
    }

    /* ABOUT COMPANY */
    .se-corp-about {
        padding-block: 88px;
        background: #fff;
    }

    .se-corp-about-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        align-items: center;
        gap: 70px;
    }

    .se-corp-visual-column {
        min-width: 0;
    }

    .se-corp-image-frame {
        position: relative;
        width: 100%;
        overflow: hidden;
        border-radius: 10px;
        background: #f5f7f6;
    }

    /* Show the entire original image without cropping top or bottom. */
    .se-corp-image-frame>img {
        display: block;
        width: 100%;
        max-width: 100%;
        height: auto !important;
        max-height: none;
        object-fit: contain;
        object-position: center;
    }

    .se-corp-image-footnote {
        display: flex;
        align-items: center;
        gap: 9px;
        margin-top: 14px;
        color: #738087;
        font-size: 11px;
    }

    .se-corp-image-footnote>span {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #52856d;
    }

    .se-corp-section-tag {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        color: #39765c;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .se-corp-section-tag::before {
        content: "";
        width: 23px;
        height: 2px;
        background: #739d87;
    }

    .se-corp-about-copy h2 {
        margin: 17px 0;
        color: #1c3542;
        font-size: clamp(28px, 3vw, 41px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.8px;
    }

    .se-corp-description {
        color: #68757d;
        font-size: 14px;
        line-height: 1.95;
        overflow-wrap: anywhere;
    }

    .se-corp-highlight-list {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px 16px;
        margin-top: 25px;
    }

    .se-corp-highlight {
        display: flex;
        align-items: flex-start;
        gap: 10px;
        color: #344953;
        font-size: 12px;
        font-weight: 650;
        line-height: 1.6;
    }

    .se-corp-check {
        display: grid;
        place-items: center;
        flex: 0 0 22px;
        width: 22px;
        height: 22px;
        border: 1px solid #d9e8df;
        border-radius: 50%;
        background: #f1f7f3;
        color: #367454;
    }

    .se-corp-check svg {
        width: 13px;
        height: 13px;
        fill: none;
        stroke: currentColor;
        stroke-width: 2;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .se-corp-inline-link {
        display: inline-flex;
        align-items: center;
        gap: 13px;
        margin-top: 28px;
        padding-bottom: 7px;
        border-bottom: 1px solid #b8c9c1;
        color: #28664f;
        font-size: 12px;
        font-weight: 800;
        text-decoration: none;
    }

    .se-corp-inline-link:hover {
        color: #173f32;
        border-color: #173f32;
    }

    /* PRODUCT/SERVICE CARDS */
    .se-corp-solutions {
        padding-block: 74px 82px;
        background: #f5f7f8;
        border-block: 1px solid #e7ecee;
    }

    .se-corp-solutions-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 25px;
        margin-bottom: 32px;
    }

    .se-corp-solutions-heading h2 {
        margin: 13px 0 10px;
        color: #1c3542;
        font-size: clamp(26px, 3vw, 38px);
        font-weight: 800;
        line-height: 1.23;
        letter-spacing: -.5px;
    }

    .se-corp-solutions-heading p {
        max-width: 630px;
        margin: 0;
        color: #718087;
        font-size: 13px;
        line-height: 1.8;
    }

    .se-corp-all-link {
        display: inline-flex;
        align-items: center;
        gap: 13px;
        flex-shrink: 0;
        padding: 12px 15px;
        border: 1px solid #d5dfe3;
        border-radius: 5px;
        background: #fff;
        color: #2a5e4a;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
    }

    .se-corp-all-link:hover {
        border-color: #91b3a0;
        background: #f0f6f2;
    }

    .se-corp-solution-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 16px;
    }

    .se-corp-solution-card {
        display: flex;
        flex-direction: column;
        min-height: 255px;
        padding: 21px 19px;
        border: 1px solid #e1e8eb;
        border-radius: 7px;
        background: #fff;
        transition: transform .2s, box-shadow .2s, border-color .2s;
    }

    .se-corp-solution-card:hover {
        transform: translateY(-4px);
        border-color: #b9d0c3;
        box-shadow: 0 13px 30px rgba(24, 55, 67, .07);
    }

    .se-corp-solution-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 23px;
    }

    .se-corp-solution-number {
        color: #91a0a5;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 1.2px;
    }

    .se-corp-solution-icon {
        display: grid;
        place-items: center;
        width: 43px;
        height: 43px;
        border: 1px solid #dfe9e3;
        border-radius: 7px;
        background: #f0f6f2;
        color: #326e54;
    }

    .se-corp-solution-icon svg {
        width: 23px;
        height: 23px;
        fill: none;
        stroke: currentColor;
        stroke-width: 1.5;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .se-corp-solution-card h3 {
        margin: 0 0 10px;
        color: #263f4b;
        font-size: 15px;
        font-weight: 750;
        line-height: 1.5;
    }

    .se-corp-solution-card>p {
        flex: 1;
        margin: 0 0 20px;
        color: #77838a;
        font-size: 12px;
        line-height: 1.8;
    }

    .se-corp-solution-card>a {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        padding-top: 13px;
        border-top: 1px solid #e9edef;
        color: #326e54;
        font-size: 11px;
        font-weight: 800;
        text-decoration: none;
    }

    /* ENQUIRY CTA */
    .se-corp-cta-section {
        padding-block: 72px;
        background: #fff;
    }

    .se-corp-cta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 35px;
        padding: 40px 42px;
        border-radius: 8px;
        background: #183d49;
    }

    .se-corp-cta-content {
        max-width: 700px;
    }

    .se-corp-cta-label {
        color: #c8e2d2;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .se-corp-cta h2 {
        margin: 12px 0;
        color: #fff;
        font-size: clamp(25px, 3vw, 35px);
        font-weight: 800;
        line-height: 1.25;
    }

    .se-corp-cta p {
        margin: 0;
        color: rgba(255, 255, 255, .76);
        font-size: 13px;
        line-height: 1.9;
    }

    .se-corp-button-cta {
        flex-shrink: 0;
        background: #fff;
        color: #183d49;
    }

    .se-corp-button-cta:hover {
        background: #eaf4ef;
        color: #183d49;
    }

    /* RESPONSIVE */
    @media (max-width: 1000px) {
        .se-corp-about-grid {
            gap: 38px;
        }

        .se-corp-solution-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .se-corp-trust-item {
            padding-inline: 12px;
        }
    }

    @media (max-width: 700px) {
        .se-corp-container {
            width: calc(100% - 36px);
        }

        .se-corp-hero {
            min-height: 0;
            background-position: center center;
            background-size: 100% 100%;
        }

        .se-corp-hero-inner {
            padding-block: 30px 100px;
        }

        .se-corp-breadcrumb {
            margin-bottom: 36px;
        }

        .se-corp-hero h1 {
            font-size: clamp(34px, 8vw, 48px);
            letter-spacing: -.6px;
        }

        .se-corp-hero-copy>p {
            font-size: 14px;
        }

        .se-corp-trust-grid {
            grid-template-columns: 1fr;
            padding-block: 10px;
        }

        .se-corp-trust-item,
        .se-corp-trust-item:first-child,
        .se-corp-trust-item:last-child {
            justify-content: flex-start;
            padding: 13px 0;
            border-right: 0;
            border-bottom: 1px solid #e5eaed;
        }

        .se-corp-trust-item:last-child {
            border-bottom: 0;
        }

        .se-corp-about {
            padding-block: 52px;
        }

        .se-corp-about-grid {
            grid-template-columns: 1fr;
            gap: 35px;
        }

        .se-corp-solutions {
            padding-block: 52px;
        }

        .se-corp-solutions-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 18px;
        }

        .se-corp-solution-grid {
            gap: 12px;
        }

        .se-corp-solution-card {
            min-height: 245px;
            padding: 17px 14px;
        }

        .se-corp-cta-section {
            padding-block: 42px;
        }

        .se-corp-cta {
            align-items: flex-start;
            flex-direction: column;
            gap: 23px;
            padding: 29px 23px;
        }
    }

    @media (max-width: 420px) {

        .se-corp-highlight-list,
        .se-corp-solution-grid {
            grid-template-columns: 1fr;
        }

        .se-corp-solution-card {
            min-height: 0;
        }

        .se-corp-hero-actions {
            align-items: stretch;
            flex-direction: column;
        }

        .se-corp-button {
            justify-content: space-between;
        }

    }
</style>
@endpush