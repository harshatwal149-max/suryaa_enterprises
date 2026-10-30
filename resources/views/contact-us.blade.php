@extends('layouts.frontend')

@section('title', 'Contact Us – Solar Dealer in Kangra, HP')
@section('meta_description', 'Contact ' . \App\Support\Seo::siteName() . ', Kangra, HP for solar water heaters, rooftop solar, street lights & fencing. Call or WhatsApp for a free quote and site survey.')

@push('schema')
{{ \App\Support\Seo::jsonLd(
    \App\Support\Seo::webPage('ContactPage', 'Contact ' . \App\Support\Seo::siteName()),
    \App\Support\Seo::breadcrumbs(['Home' => route('home'), 'Contact Us' => route('contact')])
) }}
@endpush

@section('content')
@php
$siteName = $settings?->site_name ?: 'Suryaa Enterprises';
$address = $settings?->address ?? '';
$email = $settings?->email ?? '';
$phones = collect([
    $settings?->phone_primary,
    $settings?->phone_secondary,
    $settings?->phone_tertiary,
])->filter()->unique()->values();

$mapUrl = $settings?->map_embed_url ?? '';
$directionsUrl = $address
    ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($siteName . ', ' . $address)
    : null;
$telHref = fn ($phone) => 'tel:+91' . substr(preg_replace('/\D+/', '', $phone), -10);

$socials = collect([
    'facebook_url'  => ['Facebook', 'fa-facebook-f'],
    'instagram_url' => ['Instagram', 'fa-instagram'],
    'youtube_url'   => ['YouTube', 'fa-youtube'],
    'linkedin_url'  => ['LinkedIn', 'fa-linkedin-in'],
])->filter(fn ($social, $field) => filled($settings?->{$field}));
@endphp

{{-- BANNER --}}
<section class="ct-banner">
    <div class="ct-container">
        <nav class="ct-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span aria-current="page">Contact Us</span>
        </nav>
        <h1>Let’s Talk Solar</h1>
        <p>Questions about a water heater, rooftop plant or street lights? Call, WhatsApp or send us a message, and our Dharamshala team will get back to you.</p>
    </div>
</section>

{{-- QUICK CONTACT CARDS --}}
<section class="ct-quick" aria-label="Ways to reach us">
    <div class="ct-container ct-quick-grid">
        @if ($phones->isNotEmpty())
        <div class="ct-quick-card">
            <span class="ct-quick-icon"><i class="fa-solid fa-phone-volume" aria-hidden="true"></i></span>
            <h2>Call Us</h2>
            @foreach ($phones as $phone)
            <a href="{{ $telHref($phone) }}">{{ $phone }}</a>
            @endforeach
        </div>
        @endif

        @if ($whatsappNumber !== '')
        <div class="ct-quick-card">
            <span class="ct-quick-icon ct-icon-whatsapp"><i class="fa-brands fa-whatsapp" aria-hidden="true"></i></span>
            <h2>WhatsApp</h2>
            <p>Quick replies on chat</p>
            <a href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Hello Suryaa Enterprises, I have an enquiry.') }}" target="_blank" rel="noopener">
                Start a chat <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
        @endif

        @if ($email)
        <div class="ct-quick-card">
            <span class="ct-quick-icon"><i class="fa-regular fa-envelope" aria-hidden="true"></i></span>
            <h2>Email Us</h2>
            <p>We reply to every message</p>
            <a href="mailto:{{ $email }}" class="ct-break">{{ $email }}</a>
        </div>
        @endif

        @if ($address)
        <div class="ct-quick-card">
            <span class="ct-quick-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
            <h2>Visit Us</h2>
            <p>{{ $address }}</p>
            @if ($directionsUrl)
            <a href="{{ $directionsUrl }}" target="_blank" rel="noopener">
                Get directions <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
            </a>
            @endif
        </div>
        @endif
    </div>
</section>

{{-- FORM + INFO --}}
<section class="ct-main">
    <div class="ct-container ct-main-grid">
        <div class="ct-form-card" id="contact-form">
            <div class="ct-form-head">
                <span class="ct-eyebrow">SEND A MESSAGE</span>
                <h2>Tell us what you need</h2>
                <p>Fill in your details and we’ll call you back. Email is optional.</p>
            </div>

            <form action="{{ route('contact.store') }}" method="POST" class="ct-form" id="ct-form" novalidate>
                @csrf

                {{-- Honeypot for spam bots; hidden from people and screen readers --}}
                <div aria-hidden="true" style="position:absolute;left:-9999px;">
                    <label>Website <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
                </div>

                <div class="ct-row">
                    <div class="ct-field {{ $errors->has('name') ? 'has-error' : '' }}">
                        <input id="ct-name" name="name" type="text" value="{{ old('name') }}" maxlength="100" autocomplete="name" placeholder=" " required>
                        <label for="ct-name"><i class="fa-regular fa-user" aria-hidden="true"></i> Full Name *</label>
                        <small class="ct-error" data-error-for="name">{{ $errors->first('name') }}</small>
                    </div>

                    <div class="ct-field {{ $errors->has('phone') ? 'has-error' : '' }}">
                        <input id="ct-phone" name="phone" type="tel" value="{{ old('phone') }}" maxlength="30" autocomplete="tel" inputmode="tel" placeholder=" " required>
                        <label for="ct-phone"><i class="fa-solid fa-phone" aria-hidden="true"></i> Phone Number *</label>
                        <small class="ct-error" data-error-for="phone">{{ $errors->first('phone') }}</small>
                    </div>
                </div>

                <div class="ct-row">
                    <div class="ct-field {{ $errors->has('email') ? 'has-error' : '' }}">
                        <input id="ct-email" name="email" type="email" value="{{ old('email') }}" maxlength="150" autocomplete="email" placeholder=" ">
                        <label for="ct-email"><i class="fa-regular fa-envelope" aria-hidden="true"></i> Email (optional)</label>
                        <small class="ct-error" data-error-for="email">{{ $errors->first('email') }}</small>
                    </div>

                    <div class="ct-field ct-field-select">
                        <select id="ct-interest" name="interest">
                            <option value="" @selected(!old('interest'))>General enquiry</option>
                            @foreach ($categories as $category)
                            @php $label = \Illuminate\Support\Str::title($category->name); @endphp
                            <option value="{{ $label }}" @selected(old('interest') === $label)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label for="ct-interest" class="is-floating"><i class="fa-solid fa-solar-panel" aria-hidden="true"></i> Interested in</label>
                        <i class="fa-solid fa-chevron-down ct-select-arrow" aria-hidden="true"></i>
                    </div>
                </div>

                <div class="ct-field ct-field-textarea {{ $errors->has('message') ? 'has-error' : '' }}">
                    <textarea id="ct-message" name="message" rows="5" maxlength="5000" placeholder=" " required>{{ old('message') }}</textarea>
                    <label for="ct-message"><i class="fa-regular fa-message" aria-hidden="true"></i> Your Message *</label>
                    <span class="ct-counter" aria-hidden="true"><span data-counter>0</span>/5000</span>
                    <small class="ct-error" data-error-for="message">{{ $errors->first('message') }}</small>
                </div>

                <button type="submit" class="ct-submit">
                    <span>Send Message</span>
                    <span class="ct-spinner" aria-hidden="true"></span>
                    <i class="fa-solid fa-paper-plane ct-submit-icon" aria-hidden="true"></i>
                </button>

                <p class="ct-privacy"><i class="fa-solid fa-lock" aria-hidden="true"></i> Your details are only used to respond to your enquiry.</p>
            </form>

            <div class="ct-success" role="status" aria-live="polite" hidden>
                <svg class="ct-success-check" viewBox="0 0 52 52" aria-hidden="true">
                    <circle cx="26" cy="26" r="24" fill="none" />
                    <path fill="none" d="M15 27l7 7 15-15" />
                </svg>
                <h3>Message sent!</h3>
                <p data-success-text>Thank you! Our team will contact you shortly.</p>
                <button type="button" class="ct-success-reset">Send another message</button>
            </div>
        </div>

        <aside class="ct-info">
            <div class="ct-info-card ct-info-dark">
                <span class="ct-eyebrow ct-eyebrow-gold">TALK TO US DIRECTLY</span>
                <h2>Narender Singh</h2>
                <p class="ct-info-role">{{ $siteName }}, Dharamshala</p>

                <ul class="ct-info-list">
                    <li><i class="fa-solid fa-award" aria-hidden="true"></i> ISO 9001:2015 Certified Company</li>
                    <li><i class="fa-solid fa-handshake" aria-hidden="true"></i> Authorised Super Solar Distributor, H.P.</li>
                    <li><i class="fa-solid fa-solar-panel" aria-hidden="true"></i> All kinds of solar equipment</li>
                    @if (filled($settings?->gst_number))
                    <li><i class="fa-solid fa-file-invoice" aria-hidden="true"></i> GSTIN: {{ $settings->gst_number }}</li>
                    @endif
                </ul>

                <div class="ct-info-actions">
                    @if ($phones->isNotEmpty())
                    <a href="{{ $telHref($phones->first()) }}" class="ct-btn ct-btn-gold">
                        <i class="fa-solid fa-phone" aria-hidden="true"></i> Call {{ $phones->first() }}
                    </a>
                    @endif
                    @if ($whatsappNumber !== '')
                    <a href="https://wa.me/{{ $whatsappNumber }}" class="ct-btn ct-btn-whatsapp" target="_blank" rel="noopener">
                        <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> WhatsApp
                    </a>
                    @endif
                </div>

                @if ($socials->isNotEmpty())
                <div class="ct-socials">
                    <span>Follow us</span>
                    @foreach ($socials as $field => [$label, $icon])
                    <a href="{{ $settings->{$field} }}" target="_blank" rel="noopener noreferrer" aria-label="{{ $label }}">
                        <i class="fa-brands {{ $icon }}" aria-hidden="true"></i>
                    </a>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="ct-info-card">
                <h3><i class="fa-solid fa-route" aria-hidden="true"></i> What happens next?</h3>
                <ol class="ct-steps">
                    <li><strong>We call you back</strong> to understand your requirement.</li>
                    <li><strong>We suggest the right product</strong> and capacity with a clear quote.</li>
                    <li><strong>We supply and install</strong> at your home, farm or business.</li>
                </ol>
            </div>
        </aside>
    </div>
</section>

{{-- MAP --}}
@if ($mapUrl)
<section class="ct-map-section" aria-label="Location map">
    <div class="ct-container">
        <div class="ct-map">
            <iframe
                src="{{ $mapUrl }}"
                title="Map showing {{ $siteName }}"
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                allowfullscreen></iframe>

            @if ($address)
            <div class="ct-map-card">
                <x-frontend.logo :tagline="false" />
                <p>{{ $address }}</p>
                @if ($directionsUrl)
                <a href="{{ $directionsUrl }}" target="_blank" rel="noopener" class="ct-btn ct-btn-green">
                    <i class="fa-solid fa-diamond-turn-right" aria-hidden="true"></i> Get Directions
                </a>
                @endif
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- Fallback toast for non-JavaScript submissions --}}
@if (session('contact_success'))
<div class="ct-toast" role="status">
    <i class="fa-solid fa-circle-check" aria-hidden="true"></i>
    <span>{{ session('contact_success') }}</span>
    <button type="button" class="ct-toast-close" aria-label="Close">&times;</button>
</div>
@endif
@endsection

@push('styles')
<style>
    .ct-banner,
    .ct-banner *,
    .ct-quick *,
    .ct-main *,
    .ct-map-section *,
    .ct-toast {
        box-sizing: border-box;
    }

    .ct-container {
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
    }

    /* Banner */
    .ct-banner {
        padding: 52px 0 110px;
        background:
            radial-gradient(circle at 88% 20%, rgba(251, 191, 36, .28), transparent 34%),
            linear-gradient(120deg, #022c22 0%, #065f46 100%);
        color: #fff;
    }

    .ct-breadcrumb {
        display: flex;
        gap: 8px;
        margin-bottom: 14px;
        color: rgba(255, 255, 255, .7);
        font-size: 13px;
    }

    .ct-breadcrumb a {
        color: rgba(255, 255, 255, .85) !important;
        text-decoration: none !important;
    }

    .ct-breadcrumb a:hover {
        color: #fbbf24 !important;
    }

    .ct-banner h1 {
        margin: 0 0 12px;
        color: #fff;
        font-size: clamp(32px, 4.4vw, 50px);
        font-weight: 800;
        line-height: 1.1;
    }

    .ct-banner p {
        max-width: 640px;
        margin: 0;
        color: rgba(255, 255, 255, .82);
        font-size: 16px;
        line-height: 1.7;
    }

    /* Quick contact cards overlap the banner */
    .ct-quick {
        position: relative;
        z-index: 2;
        margin-top: -70px;
    }

    .ct-quick-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 18px;
    }

    .ct-quick-card {
        display: flex;
        flex-direction: column;
        gap: 6px;
        min-width: 0;
        padding: 24px;
        border: 1px solid #e5ece7;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 18px 40px rgba(10, 42, 27, .1);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .ct-quick-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 24px 50px rgba(10, 42, 27, .14);
    }

    .ct-quick-icon {
        display: grid;
        width: 50px;
        height: 50px;
        margin-bottom: 8px;
        place-items: center;
        border-radius: 14px;
        background: #eaf5ed;
        color: #0f7a3d;
        font-size: 20px;
        transition: background .25s ease, color .25s ease, transform .25s ease;
    }

    .ct-quick-card:hover .ct-quick-icon {
        background: #0f7a3d;
        color: #fff;
        transform: rotate(-6deg);
    }

    .ct-icon-whatsapp,
    .ct-quick-card:hover .ct-icon-whatsapp {
        background: #25d366;
        color: #fff;
    }

    .ct-quick-card h2 {
        margin: 0;
        color: #172d25;
        font-size: 18px;
        font-weight: 800;
    }

    .ct-quick-card p {
        margin: 0;
        color: #65756d;
        font-size: 14px;
        line-height: 1.6;
    }

    .ct-quick-card a {
        color: #0f7a3d !important;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none !important;
    }

    .ct-quick-card a:hover {
        text-decoration: underline !important;
    }

    .ct-quick-card a i {
        margin-left: 4px;
        font-size: 12px;
    }

    .ct-break {
        overflow-wrap: anywhere;
    }

    /* Main section */
    .ct-main {
        padding: 56px 0 72px;
    }

    .ct-main-grid {
        display: grid;
        grid-template-columns: minmax(0, 1.35fr) minmax(0, 1fr);
        align-items: start;
        gap: 28px;
    }

    .ct-eyebrow {
        display: inline-block;
        margin-bottom: 10px;
        color: #0f7a3d;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .ct-eyebrow-gold {
        color: #fbbf24;
    }

    .ct-form-card {
        padding: clamp(24px, 3vw, 40px);
        border: 1px solid #e5ece7;
        border-radius: 20px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(10, 42, 27, .07);
        scroll-margin-top: 120px;
    }

    .ct-form-head h2 {
        margin: 0 0 8px;
        color: #172d25;
        font-size: clamp(24px, 2.6vw, 32px);
        font-weight: 800;
    }

    .ct-form-head p {
        margin: 0 0 26px;
        color: #718078;
        font-size: 14px;
    }

    .ct-form {
        display: grid;
        gap: 16px;
    }

    .ct-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
    }

    /* Floating-label fields */
    .ct-field {
        position: relative;
        min-width: 0;
    }

    .ct-field input,
    .ct-field select,
    .ct-field textarea {
        display: block;
        width: 100%;
        margin: 0;
        padding: 26px 16px 9px;
        border: 1.5px solid #dce5de;
        border-radius: 12px;
        outline: none;
        background: #f9fbfa;
        color: #172d25;
        font: inherit;
        font-size: 15px;
        transition: border-color .25s ease, background .25s ease, box-shadow .25s ease;
        appearance: none;
    }

    .ct-field input,
    .ct-field select {
        height: 60px;
    }

    .ct-field select {
        padding-right: 40px;
        cursor: pointer;
    }

    .ct-field textarea {
        min-height: 150px;
        padding-top: 30px;
        resize: vertical;
    }

    .ct-field label {
        position: absolute;
        top: 30px;
        left: 17px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: #7b8a82;
        font-size: 15px;
        pointer-events: none;
        transform: translateY(-50%);
        transition: top .2s ease, font-size .2s ease, color .2s ease;
    }

    .ct-field label i {
        color: #9aa7a0;
        transition: color .2s ease;
    }

    .ct-field input:focus + label,
    .ct-field input:not(:placeholder-shown) + label,
    .ct-field textarea:focus + label,
    .ct-field textarea:not(:placeholder-shown) + label,
    .ct-field label.is-floating {
        top: 16px;
        color: #0f7a3d;
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .ct-field input:focus + label i,
    .ct-field textarea:focus + label i,
    .ct-field select:focus + label i {
        color: #0f7a3d;
    }

    .ct-field input:focus,
    .ct-field select:focus,
    .ct-field textarea:focus {
        border-color: #0f7a3d;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(15, 122, 61, .1);
    }

    .ct-select-arrow {
        position: absolute;
        top: 30px;
        right: 16px;
        color: #7b8a82;
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
        transition: transform .25s ease;
    }

    .ct-field select:focus ~ .ct-select-arrow {
        color: #0f7a3d;
        transform: translateY(-50%) rotate(180deg);
    }

    .ct-counter {
        position: absolute;
        right: 14px;
        bottom: 10px;
        color: #a1aca5;
        font-size: 11px;
        pointer-events: none;
    }

    .ct-field-textarea:has(.ct-error:not(:empty)) .ct-counter {
        bottom: 32px;
    }

    .ct-field.has-error input,
    .ct-field.has-error textarea {
        border-color: #ef4444;
        background: #fffafa;
        animation: ctShake .4s ease;
    }

    .ct-error {
        display: block;
        margin-top: 6px;
        color: #b91c1c;
        font-size: 12px;
    }

    .ct-error:empty {
        display: none;
    }

    @keyframes ctShake {

        25%,
        75% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }
    }

    .ct-submit {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 58px;
        overflow: hidden;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #0f7a3d, #065f46);
        color: #fff;
        font: inherit;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 12px 28px rgba(15, 122, 61, .28);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .ct-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 16px 34px rgba(15, 122, 61, .36);
    }

    .ct-submit-icon {
        transition: transform .3s ease;
    }

    .ct-submit:hover .ct-submit-icon {
        transform: translate(4px, -3px) rotate(8deg);
    }

    .ct-spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255, 255, 255, .35);
        border-top-color: #fff;
        border-radius: 50%;
        animation: ctSpin .7s linear infinite;
    }

    .ct-submit.is-loading {
        cursor: wait;
    }

    .ct-submit.is-loading .ct-spinner {
        display: block;
    }

    .ct-submit.is-loading .ct-submit-icon {
        display: none;
    }

    @keyframes ctSpin {
        to {
            transform: rotate(360deg);
        }
    }

    .ct-privacy {
        margin: 0;
        color: #89958e;
        font-size: 12px;
        text-align: center;
    }

    .ct-privacy i {
        margin-right: 4px;
    }

    .ct-success {
        padding: 30px 0 10px;
        text-align: center;
        animation: ctRise .6s ease both;
    }

    .ct-success-check {
        width: 84px;
        height: 84px;
        margin-bottom: 14px;
        stroke: #0f7a3d;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .ct-success-check circle {
        stroke-dasharray: 152;
        stroke-dashoffset: 152;
        animation: ctDraw .7s ease forwards;
    }

    .ct-success-check path {
        stroke-dasharray: 40;
        stroke-dashoffset: 40;
        animation: ctDraw .45s ease .6s forwards;
    }

    @keyframes ctDraw {
        to {
            stroke-dashoffset: 0;
        }
    }

    @keyframes ctRise {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .ct-success h3 {
        margin: 0 0 8px;
        color: #172d25;
        font-size: 24px;
    }

    .ct-success p {
        margin: 0 auto 22px;
        max-width: 380px;
        color: #65756d;
        font-size: 15px;
        line-height: 1.6;
    }

    .ct-success-reset {
        padding: 11px 20px;
        border: 1.5px solid #cfe3d6;
        border-radius: 10px;
        background: #fff;
        color: #0f7a3d;
        font: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .ct-success-reset:hover {
        background: #f0f7f2;
    }

    /* Info column */
    .ct-info {
        display: grid;
        gap: 20px;
    }

    .ct-info-card {
        padding: 28px;
        border: 1px solid #e5ece7;
        border-radius: 20px;
        background: #fff;
    }

    .ct-info-dark {
        border: 0;
        background:
            radial-gradient(circle at 90% 10%, rgba(251, 191, 36, .25), transparent 40%),
            linear-gradient(150deg, #065f46, #022c22);
        color: #fff;
    }

    .ct-info-dark h2 {
        margin: 0;
        color: #fff;
        font-size: 26px;
        font-weight: 800;
    }

    .ct-info-role {
        margin: 4px 0 20px;
        color: rgba(255, 255, 255, .7);
        font-size: 14px;
    }

    .ct-info-list {
        display: grid;
        gap: 12px;
        margin: 0 0 24px;
        padding: 0;
        list-style: none;
        font-size: 14px;
    }

    .ct-info-list i {
        width: 22px;
        color: #fbbf24;
    }

    .ct-info-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .ct-btn {
        display: inline-flex;
        flex: 1 1 auto;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 48px;
        padding: 12px 18px;
        border-radius: 12px;
        font-size: 14px;
        font-weight: 800;
        text-decoration: none !important;
        white-space: nowrap;
        transition: transform .2s ease, filter .2s ease;
    }

    .ct-btn:hover {
        transform: translateY(-2px);
        filter: brightness(.95);
    }

    .ct-btn-gold {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006 !important;
    }

    .ct-btn-whatsapp {
        background: #25d366;
        color: #fff !important;
    }

    .ct-btn-green {
        background: #0f7a3d;
        color: #fff !important;
    }

    .ct-socials {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 22px;
        padding-top: 18px;
        border-top: 1px solid rgba(255, 255, 255, .15);
        color: rgba(255, 255, 255, .7);
        font-size: 13px;
    }

    .ct-socials span {
        margin-right: auto;
    }

    .ct-socials a {
        display: grid;
        width: 36px;
        height: 36px;
        place-items: center;
        border-radius: 50%;
        background: rgba(255, 255, 255, .1);
        color: #fff !important;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease;
    }

    .ct-socials a:hover {
        background: #fbbf24;
        color: #422006 !important;
    }

    .ct-info-card h3 {
        margin: 0 0 16px;
        color: #172d25;
        font-size: 17px;
        font-weight: 800;
    }

    .ct-info-card h3 i {
        margin-right: 6px;
        color: #0f7a3d;
    }

    .ct-steps {
        display: grid;
        gap: 14px;
        margin: 0;
        padding: 0;
        list-style: none;
        counter-reset: ct-step;
    }

    .ct-steps li {
        position: relative;
        padding-left: 44px;
        color: #65756d;
        font-size: 14px;
        line-height: 1.6;
        counter-increment: ct-step;
    }

    .ct-steps li::before {
        position: absolute;
        top: 0;
        left: 0;
        display: grid;
        width: 30px;
        height: 30px;
        place-items: center;
        border-radius: 50%;
        background: #eaf5ed;
        color: #0f7a3d;
        content: counter(ct-step);
        font-size: 13px;
        font-weight: 800;
    }

    .ct-steps strong {
        color: #20362a;
    }

    /* Map */
    .ct-map-section {
        padding: 0 0 80px;
    }

    .ct-map {
        position: relative;
        height: 440px;
        overflow: hidden;
        border-radius: 22px;
        background: #e5ece7;
        box-shadow: 0 18px 50px rgba(10, 42, 27, .08);
    }

    .ct-map iframe {
        display: block;
        width: 100%;
        height: 100%;
        border: 0;
    }

    .ct-map-card {
        position: absolute;
        top: 24px;
        left: 24px;
        display: grid;
        gap: 12px;
        width: min(340px, calc(100% - 48px));
        padding: 22px;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 18px 40px rgba(0, 0, 0, .16);
    }

    .ct-map-card p {
        margin: 0;
        color: #4b5a52;
        font-size: 14px;
        line-height: 1.6;
    }

    .ct-map-card .ct-btn {
        justify-self: start;
    }

    /* Toast (non-JS fallback) */
    .ct-toast {
        position: fixed;
        top: 110px;
        right: 24px;
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        max-width: min(420px, calc(100vw - 32px));
        padding: 16px 18px;
        border: 1px solid #bbf7d0;
        border-radius: 12px;
        background: #fff;
        color: #166534;
        box-shadow: 0 12px 35px rgba(0, 0, 0, .16);
        font-size: 14px;
    }

    .ct-toast i {
        font-size: 22px;
    }

    .ct-toast-close {
        border: 0;
        background: transparent;
        color: #64748b;
        font-size: 22px;
        cursor: pointer;
    }

    .ct-quick-card a:focus-visible,
    .ct-btn:focus-visible,
    .ct-submit:focus-visible,
    .ct-success-reset:focus-visible,
    .ct-socials a:focus-visible {
        outline: 3px solid #fbbf24;
        outline-offset: 3px;
    }

    @media (max-width: 960px) {
        .ct-main-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 640px) {
        .ct-container {
            width: calc(100% - 28px);
        }

        .ct-banner {
            padding: 36px 0 96px;
        }

        .ct-banner p {
            font-size: 15px;
        }

        .ct-quick-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .ct-quick-card {
            padding: 18px;
        }

        .ct-quick-card h2 {
            font-size: 16px;
        }

        .ct-quick-card p,
        .ct-quick-card a {
            font-size: 13px;
        }

        .ct-main {
            padding: 32px 0 48px;
        }

        .ct-row {
            grid-template-columns: minmax(0, 1fr);
        }

        .ct-info-card {
            padding: 22px;
        }

        .ct-map-section {
            padding-bottom: 48px;
        }

        .ct-map {
            display: flex;
            flex-direction: column;
            height: auto;
        }

        .ct-map iframe {
            height: 300px;
        }

        .ct-map-card {
            position: static;
            width: 100%;
            border-radius: 0;
            box-shadow: none;
        }
    }

    @media (max-width: 380px) {
        .ct-quick-grid {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .ct-quick-card,
        .ct-quick-icon,
        .ct-field input,
        .ct-field textarea,
        .ct-field label,
        .ct-submit,
        .ct-btn,
        .ct-success,
        .ct-success-check circle,
        .ct-success-check path {
            transition: none !important;
            animation: none !important;
        }

        .ct-success-check circle,
        .ct-success-check path {
            stroke-dashoffset: 0;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('ct-form');
        if (!form) return;

        const field = (name) => form.elements.namedItem(name);
        const submitButton = form.querySelector('.ct-submit');
        const success = document.querySelector('.ct-success');
        const counter = form.querySelector('[data-counter]');
        const message = field('message');

        function setError(name, text) {
            const wrapper = field(name).closest('.ct-field');
            wrapper.classList.toggle('has-error', Boolean(text));
            wrapper.querySelector('.ct-error').textContent = text || '';
        }

        function updateCounter() {
            counter.textContent = message.value.length;
        }

        function validate() {
            const emailValue = field('email').value.trim();
            const errors = {
                name: field('name').value.trim() ? '' : 'Please enter your name.',
                phone: field('phone').value.replace(/\D+/g, '').length >= 10 ? '' : 'Please enter a valid 10-digit phone number.',
                email: !emailValue || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue) ? '' : 'Please enter a valid email address.',
                message: message.value.trim() ? '' : 'Please tell us how we can help.'
            };

            Object.entries(errors).forEach(([name, text]) => setError(name, text));

            const firstInvalid = Object.keys(errors).find((name) => errors[name]);
            if (firstInvalid) field(firstInvalid).focus();

            return !firstInvalid;
        }

        message.addEventListener('input', updateCounter);
        updateCounter();

        form.querySelectorAll('input, textarea').forEach(function(input) {
            input.addEventListener('input', () => setError(input.name, ''));
        });

        form.addEventListener('submit', async function(event) {
            event.preventDefault();
            if (!validate()) return;

            submitButton.classList.add('is-loading');
            submitButton.disabled = true;

            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                });

                if (response.status === 422) {
                    const data = await response.json();
                    Object.entries(data.errors || {}).forEach(function([name, messages]) {
                        if (field(name)) setError(name, messages[0]);
                    });
                    return;
                }

                if (!response.ok) throw new Error('Request failed');

                const data = await response.json();
                success.querySelector('[data-success-text]').textContent = data.message;
                form.hidden = true;
                form.closest('.ct-form-card').querySelector('.ct-form-head').hidden = true;
                success.hidden = false;
            } catch (error) {
                // Fall back to a normal form post if AJAX fails.
                HTMLFormElement.prototype.submit.call(form);
            } finally {
                submitButton.classList.remove('is-loading');
                submitButton.disabled = false;
            }
        });

        success.querySelector('.ct-success-reset').addEventListener('click', function() {
            form.reset();
            updateCounter();
            success.hidden = true;
            form.hidden = false;
            form.closest('.ct-form-card').querySelector('.ct-form-head').hidden = false;
            field('name').focus();
        });

        // Non-JS fallback toast.
        const toast = document.querySelector('.ct-toast');
        if (toast) {
            const close = () => toast.remove();
            toast.querySelector('.ct-toast-close').addEventListener('click', close);
            window.setTimeout(close, 5000);
        }
    });
</script>
@endpush
