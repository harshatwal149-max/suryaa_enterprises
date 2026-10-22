@extends('layouts.frontend')

@section('content')
@php
$siteName = $settings?->site_name ?? 'Suryaa Enterprises';
$address = $settings?->address ?? '';
$email = $settings?->email ?? '';
$phones = collect([
$settings?->phone_primary,
$settings?->phone_secondary,
$settings?->phone_tertiary, 
])->filter()->unique();

$mapUrl = $settings?->map_embed_url ?? '';
@endphp

<section class="contact-page">
    <div class="contact-container">

        <nav class="contact-breadcrumb" aria-label="Breadcrumb">
            <a href="{{ url('/') }}">Home</a>
            <span>›</span>
            <span>Contact Us</span>
        </nav>

        <header class="contact-heading">
            <span class="contact-eyebrow">LET'S TALK</span>
            <h1>Contact <span>Our Team</span></h1>
            <p>
                Have a question or a project in mind?
                Share your requirements and our team will get back to you.
            </p>
        </header>

        <div class="contact-grid">

            <div class="contact-form-card">
                <h2>Send an Enquiry</h2>
                <p class="contact-subtitle">
                    Fill in the details below. Email is optional.
                </p>

                @if ($errors->any())
                <div class="contact-error" role="alert">
                    Please check your details and try again.
                </div>
                @endif

                <form action="{{ url('/contact-us') }}" method="POST">
                    @csrf

                    <div class="contact-field">
                        <label for="name">Full Name <span>*</span></label>
                        <input id="name" name="name" type="text"
                            value="{{ old('name') }}" maxlength="100"
                            autocomplete="name" placeholder="Enter your full name" required>
                        @error('name')
                        <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="contact-field">
                        <label for="email">
                            Email Address <span class="optional">(Optional)</span>
                        </label>
                        <input id="email" name="email" type="email"
                            value="{{ old('email') }}" maxlength="150"
                            autocomplete="email" placeholder="you@example.com">
                        @error('email')
                        <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="contact-field">
                        <label for="phone">Phone Number <span>*</span></label>
                        <input id="phone" name="phone" type="tel"
                            value="{{ old('phone') }}" maxlength="30"
                            autocomplete="tel" placeholder="Enter your phone number" required>
                        @error('phone')
                        <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="contact-field">
                        <label for="message">Your Message <span>*</span></label>
                        <textarea id="message" name="message" rows="5"
                            maxlength="5000" placeholder="How can we help you?"
                            required>{{ old('message') }}</textarea>
                        @error('message')
                        <small>{{ $message }}</small>
                        @enderror
                    </div>

                    <button type="submit" class="contact-submit">
                        Send Message <span aria-hidden="true">→</span>
                    </button>

                    <p class="contact-privacy">
                        Your details will be used to respond to your enquiry.
                    </p>
                </form>
            </div>

            <aside class="contact-details-card">
                <span class="contact-eyebrow">GET IN TOUCH</span>
                <h2>Contact Information</h2>
                <p class="contact-subtitle">
                    Reach out to our team using the details below.
                </p>

                <div class="contact-details-list">
                    @if ($address)
                    <div class="contact-detail">
                        <span class="contact-icon" aria-hidden="true">⌖</span>
                        <div>
                            <strong>Our Address</strong>
                            <p>{{ $address }}</p>
                        </div>
                    </div>
                    @endif

                    @if ($email)
                    <div class="contact-detail">
                        <span class="contact-icon" aria-hidden="true">✉</span>
                        <div>
                            <strong>Email Us</strong>
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        </div>
                    </div>
                    @endif

                    @foreach ($phones as $contactPhone)
                    <div class="contact-detail">
                        <span class="contact-icon" aria-hidden="true">☎</span>
                        <div>
                            <strong>Call Us</strong>
                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}">
                                {{ $contactPhone }}
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>

                @if ($mapUrl)
                <div class="contact-map">
                    <iframe
                        src="{{ $mapUrl }}"
                        title="Map showing {{ $siteName }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
                @endif
            </aside>

        </div>
    </div>
</section>

<div class="contact-toast-region" aria-live="polite" aria-atomic="true">
    @if (session('contact_success'))
    <div class="contact-toast success" role="status">
        <span class="toast-icon">✓</span>
        <div>
            <strong>Message Received</strong>
            <p>{{ session('contact_success') }}</p>
        </div>
        <button type="button" class="toast-close" aria-label="Close">×</button>
    </div>
    @elseif (session('contact_error'))
    <div class="contact-toast failure" role="alert">
        <span class="toast-icon">!</span>
        <div>
            <strong>Message Not Sent</strong>
            <p>{{ session('contact_error') }}</p>
        </div>
        <button type="button" class="toast-close" aria-label="Close">×</button>
    </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .contact-page {
        --contact-green: #2f7959;
        --contact-green-dark: #245f46;
        --contact-ink: #173642;
        --contact-muted: #687b82;
        --contact-border: #dfe8e4;
        padding: 35px 0 80px;
        background: #f7f9f8;
        color: var(--contact-ink);
    }

    .contact-container {
        width: min(1160px, calc(100% - 40px));
        margin: auto;
    }

    .contact-breadcrumb {
        display: flex;
        gap: 10px;
        margin-bottom: 35px;
        color: var(--contact-muted);
        font-size: 13px;
    }

    .contact-breadcrumb a {
        color: var(--contact-green);
        text-decoration: none;
    }

    .contact-heading {
        max-width: 700px;
        margin-bottom: 35px;
    }

    .contact-eyebrow {
        color: var(--contact-green);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .contact-heading h1 {
        margin: 12px 0;
        font-size: clamp(34px, 4vw, 48px);
        line-height: 1.15;
        letter-spacing: -1px;
        font-weight: 800;
    }

    .contact-heading h1 span {
        color: var(--contact-green);
    }

    .contact-heading>p,
    .contact-subtitle {
        color: var(--contact-muted);
        font-size: 14px;
        line-height: 1.8;
    }

    .contact-grid {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, .92fr);
        gap: 26px;
        align-items: start;
    }

    .contact-form-card,
    .contact-details-card {
        min-width: 0;
        padding: clamp(22px, 3vw, 36px);
        border: 1px solid var(--contact-border);
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 12px 36px rgba(23, 54, 66, .055);
    }

    .contact-form-card h2,
    .contact-details-card h2 {
        margin: 0;
        font-size: 25px;
        font-weight: 800;
        letter-spacing: -.5px;
    }

    .contact-subtitle {
        margin: 8px 0 25px;
    }

    .contact-form-card form {
        display: grid;
        gap: 19px;
    }

    .contact-field {
        display: grid;
        gap: 8px;
    }

    .contact-field label {
        color: #304b54;
        font-size: 13px;
        font-weight: 700;
    }

    .contact-field label span {
        color: #d44d4d;
    }

    .contact-field label .optional {
        color: #859298;
        font-size: 11px;
        font-weight: 500;
    }

    .contact-field input,
    .contact-field textarea {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #dce5e2;
        border-radius: 7px;
        outline: none;
        background: #fbfcfc;
        color: var(--contact-ink);
        padding: 13px 14px;
        font: inherit;
        font-size: 14px;
        transition: border-color .2s, box-shadow .2s;
    }

    .contact-field input {
        min-height: 49px;
    }

    .contact-field textarea {
        min-height: 140px;
        resize: vertical;
    }

    .contact-field input:focus,
    .contact-field textarea:focus {
        border-color: #6da88a;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(47, 121, 89, .11);
    }

    .contact-field small {
        color: #bd3e3e;
        font-size: 12px;
    }

    .contact-error {
        padding: 12px;
        margin: 15px 0;
        border-radius: 7px;
        background: #fff2f2;
        color: #a83232;
        font-size: 13px;
    }

    .contact-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        min-height: 51px;
        padding: 13px 18px;
        border: 0;
        border-radius: 7px;
        background: var(--contact-green);
        color: #fff;
        font-size: 14px;
        font-weight: 800;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .contact-submit:hover {
        background: var(--contact-green-dark);
        transform: translateY(-1px);
    }

    .contact-privacy {
        margin: -6px 0 0;
        color: #89969a;
        text-align: center;
        font-size: 11px;
    }

    .contact-details-card h2 {
        margin-top: 12px;
    }

    .contact-details-list {
        display: grid;
        gap: 22px;
        margin-top: 25px;
    }

    .contact-detail {
        display: flex;
        align-items: flex-start;
        gap: 14px;
    }

    .contact-icon {
        display: grid;
        place-items: center;
        flex: 0 0 43px;
        width: 43px;
        height: 43px;
        border-radius: 10px;
        background: #eaf4ee;
        color: var(--contact-green);
        font-size: 22px;
    }

    .contact-detail strong {
        display: block;
        margin: 1px 0 5px;
        color: #849298;
        font-size: 11px;
    }

    .contact-detail p,
    .contact-detail a {
        margin: 0;
        color: #2c4650;
        font-size: 14px;
        line-height: 1.75;
        overflow-wrap: anywhere;
        text-decoration: none;
    }

    .contact-detail a:hover {
        color: var(--contact-green);
        text-decoration: underline;
    }

    .contact-map {
        overflow: hidden;
        margin-top: 28px;
        border: 1px solid var(--contact-border);
        border-radius: 9px;
    }

    .contact-map iframe {
        display: block;
        width: 100%;
        height: 260px;
        border: 0;
    }

    .contact-toast-region {
        position: fixed;
        z-index: 9999;
        top: 22px;
        right: 22px;
        width: min(410px, calc(100vw - 32px));
    }

    .contact-toast {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 16px;
        border: 1px solid #cbe7d5;
        border-radius: 11px;
        background: #f0fbf4;
        color: #174c32;
        box-shadow: 0 18px 50px rgba(17, 46, 33, .18);
        animation: contactToastIn .25s ease-out;
    }

    .contact-toast.failure {
        border-color: #f0cccc;
        background: #fff5f5;
        color: #8c2c2c;
    }

    .toast-icon {
        display: grid;
        place-items: center;
        flex: 0 0 27px;
        width: 27px;
        height: 27px;
        border-radius: 50%;
        background: #d2f0dd;
        font-weight: 900;
    }

    .failure .toast-icon {
        background: #f8dede;
    }

    .contact-toast strong {
        display: block;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .contact-toast p {
        margin: 0;
        font-size: 12px;
        line-height: 1.6;
    }

    .toast-close {
        margin-left: auto;
        border: 0;
        background: transparent;
        color: inherit;
        font-size: 23px;
        cursor: pointer;
    }

    @keyframes contactToastIn {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @media(max-width: 850px) {
        .contact-grid {
            grid-template-columns: 1fr;
        }
    }

    @media(max-width: 520px) {
        .contact-page {
            padding: 22px 0 55px;
        }

        .contact-container {
            width: calc(100% - 30px);
        }

        .contact-breadcrumb {
            margin-bottom: 25px;
        }

        .contact-heading {
            margin-bottom: 25px;
        }

        .contact-form-card,
        .contact-details-card {
            padding: 21px 17px;
        }

        .contact-toast-region {
            top: 12px;
            right: 12px;
            width: calc(100vw - 24px);
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const toast = document.querySelector('.contact-toast');
        if (!toast) return;

        const closeToast = function() {
            toast.style.transition = 'opacity .2s ease, transform .2s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-6px)';
            window.setTimeout(() => toast.remove(), 220);
        };

        const closeButton = toast.querySelector('.toast-close');
        if (closeButton) closeButton.addEventListener('click', closeToast);

        window.setTimeout(closeToast, 3000);
    });
</script>
@endpush