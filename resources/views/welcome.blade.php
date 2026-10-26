@extends('layouts.frontend')

@section('title', 'Home')

@section('content')

{{-- HERO CAROUSEL + QUICK QUOTE FORM --}}
<section class="sh-hero" id="hero-slider" aria-roledescription="carousel" aria-label="Featured solar solutions" data-interval="6500">
    <div class="sh-slides">
        @foreach ($heroSlides as $slide)
        <div class="sh-slide {{ $loop->first ? 'is-active' : '' }}" aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
            @if ($slide['image'])
            <img class="sh-slide-bg" src="{{ asset('storage/' . ltrim($slide['image'], '/')) }}" alt="" decoding="async" @if ($loop->first) fetchpriority="high" @endif>
            @endif
        </div>
        @endforeach
        <div class="sh-overlay" aria-hidden="true"></div>
    </div>

    <div class="sh-inner">
        <div class="sh-copy">
            @foreach ($heroSlides as $slide)
            <div
                class="sh-copy-item {{ $loop->first ? 'is-active' : '' }}"
                role="group"
                aria-roledescription="slide"
                aria-label="{{ $loop->iteration }} of {{ count($heroSlides) }}"
                aria-hidden="{{ $loop->first ? 'false' : 'true' }}">
                @if ($slide['kicker'])
                <span class="sh-kicker"><i class="fa-solid fa-sun" aria-hidden="true"></i> {{ $slide['kicker'] }}</span>
                @endif

                @if ($loop->first)
                <h1 class="sh-heading">{{ $slide['heading'] }}</h1>
                @else
                <h2 class="sh-heading">{{ $slide['heading'] }}</h2>
                @endif

                @if ($slide['description'])
                <p class="sh-description">{{ $slide['description'] }}</p>
                @endif

                <div class="sh-actions">
                    @if ($slide['primary'])
                    <a href="{{ $slide['primary']['url'] }}" class="sh-btn sh-btn-primary" @unless ($loop->first) tabindex="-1" @endunless>
                        {{ $slide['primary']['text'] }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    @endif
                    @if ($slide['secondary'])
                    <a href="{{ $slide['secondary']['url'] }}" class="sh-btn sh-btn-ghost" @unless ($loop->first) tabindex="-1" @endunless>
                        {{ $slide['secondary']['text'] }}
                    </a>
                    @endif
                </div>
            </div>
            @endforeach

            <ul class="sh-badges" aria-label="Why choose us">
                <li><i class="fa-solid fa-award" aria-hidden="true"></i> ISO 9001:2015</li>
                <li><i class="fa-solid fa-handshake" aria-hidden="true"></i> Authorised Distributor</li>
                <li><i class="fa-solid fa-location-dot" aria-hidden="true"></i> Dharamshala, Kangra</li>
            </ul>
        </div>

        {{-- Quick quote form (posts to the same enquiry endpoint as the main form) --}}
        <div class="sh-form-card" id="hero-quote">
            <div class="sh-form-glow" aria-hidden="true"></div>

            <div class="sh-form-body">
                <div class="sh-form-head">
                    <span class="sh-form-icon"><i class="fa-solid fa-bolt" aria-hidden="true"></i></span>
                    <div>
                        <h2>Get a Free Quote</h2>
                        <p>We’ll call you back within 24 hours.</p>
                    </div>
                </div>

                <form action="{{ route('enquiry.store') }}" method="POST" class="sh-form" id="sh-quote-form" novalidate>
                    @csrf

                    <div class="sh-field">
                        <input type="text" id="sh-name" name="name" placeholder=" " maxlength="100" autocomplete="name" required>
                        <label for="sh-name"><i class="fa-regular fa-user" aria-hidden="true"></i> Full Name</label>
                        <small class="sh-field-error" data-error-for="name"></small>
                    </div>

                    <div class="sh-field">
                        <input type="tel" id="sh-phone" name="phone" placeholder=" " maxlength="20" autocomplete="tel" inputmode="tel" pattern="[0-9+\-\s]{10,20}" required>
                        <label for="sh-phone"><i class="fa-solid fa-phone" aria-hidden="true"></i> Phone Number</label>
                        <small class="sh-field-error" data-error-for="phone"></small>
                    </div>

                    <div class="sh-field sh-field-select">
                        <select id="sh-interest" name="message" required>
                            <option value="" disabled selected hidden></option>
                            @foreach ($categories as $category)
                            <option value="Quote request: {{ \Illuminate\Support\Str::title($category->name) }}">{{ \Illuminate\Support\Str::title($category->name) }}</option>
                            @endforeach
                            <option value="Quote request: Not sure, need advice">Not sure, need advice</option>
                        </select>
                        <label for="sh-interest"><i class="fa-solid fa-solar-panel" aria-hidden="true"></i> I’m interested in</label>
                        <i class="fa-solid fa-chevron-down sh-select-arrow" aria-hidden="true"></i>
                        <small class="sh-field-error" data-error-for="message"></small>
                    </div>

                    <button type="submit" class="sh-submit">
                        <span class="sh-submit-text">Request Call Back</span>
                        <span class="sh-spinner" aria-hidden="true"></span>
                        <i class="fa-solid fa-arrow-right sh-submit-arrow" aria-hidden="true"></i>
                    </button>

                    <p class="sh-form-note"><i class="fa-solid fa-lock" aria-hidden="true"></i> Your details are only used to contact you.</p>
                </form>

                <div class="sh-success" role="status" aria-live="polite" hidden>
                    <svg class="sh-success-check" viewBox="0 0 52 52" aria-hidden="true">
                        <circle cx="26" cy="26" r="24" fill="none" />
                        <path fill="none" d="M15 27l7 7 15-15" />
                    </svg>
                    <h3>Thank you!</h3>
                    <p>Your request has been received. Our team will call you shortly.</p>
                    <button type="button" class="sh-success-reset">Send another request</button>
                </div>
            </div>
        </div>
    </div>

    @if (count($heroSlides) > 1)
    <div class="sh-nav">
        <div class="sh-tabs" role="tablist" aria-label="Choose slide">
            @foreach ($heroSlides as $slide)
            <button type="button" class="sh-tab {{ $loop->first ? 'is-active' : '' }}" role="tab" aria-selected="{{ $loop->first ? 'true' : 'false' }}" aria-label="Show slide {{ $loop->iteration }}: {{ $slide['label'] }}">
                <span class="sh-tab-num">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="sh-tab-label">{{ $slide['label'] }}</span>
                <span class="sh-tab-progress" aria-hidden="true"><span></span></span>
            </button>
            @endforeach
        </div>

        <div class="sh-arrows">
            <button type="button" class="sh-arrow" data-hero-prev aria-label="Previous slide"><i class="fa-solid fa-arrow-left" aria-hidden="true"></i></button>
            <button type="button" class="sh-arrow" data-hero-next aria-label="Next slide"><i class="fa-solid fa-arrow-right" aria-hidden="true"></i></button>
        </div>
    </div>
    @endif
</section>

{{-- TRUST STRIP --}}
<section class="se-trust-strip" aria-label="Why customers trust us">
    <div class="se-trust-container">
        <div class="se-trust-item">
            <i class="fa-solid fa-award" aria-hidden="true"></i>
            <div><strong>ISO 9001:2015</strong><span>Certified Company</span></div>
        </div>
        <div class="se-trust-item">
            <i class="fa-solid fa-handshake" aria-hidden="true"></i>
            <div><strong>Authorised Distributor</strong><span>Super Solar, Himachal Pradesh</span></div>
        </div>
        <div class="se-trust-item">
            <i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i>
            <div><strong>Installation Support</strong><span>Site survey to commissioning</span></div>
        </div>
        <div class="se-trust-item">
            <i class="fa-solid fa-headset" aria-hidden="true"></i>
            <div><strong>After-Sales Service</strong><span>Local support in Kangra</span></div>
        </div>
    </div>
</section>

{{-- SHOP BY CATEGORY --}}
@if ($categories->isNotEmpty())
<section class="se-categories-section" id="categories" aria-labelledby="se-categories-title">
    <div class="se-products-container">
        <div class="se-section-heading se-section-heading-center">
            <span class="se-products-eyebrow">SHOP BY CATEGORY</span>
            <h2 id="se-categories-title">All Kinds of Solar Equipment</h2>
            <p>From rooftop power plants to solar lanterns, find the right solution for your home, farm or business.</p>
        </div>

        <div class="se-categories-grid">
            @foreach ($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id]) }}" class="se-category-card">
                <div class="se-category-image">
                    @if ($category->cover_image)
                    <img src="{{ asset('storage/' . ltrim($category->cover_image, '/')) }}" alt="{{ $category->name }}" loading="lazy">
                    @else
                    <i class="fa-solid fa-solar-panel" aria-hidden="true"></i>
                    @endif
                </div>
                <div class="se-category-body">
                    <h3>{{ \Illuminate\Support\Str::title($category->name) }}</h3>
                    <span>{{ $category->products_count }} {{ \Illuminate\Support\Str::plural('Product', $category->products_count) }} <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- FEATURED PRODUCTS WITH CATEGORY TABS --}}
<section class="se-products-section" id="featured-products" aria-labelledby="se-products-title">
    <div class="se-products-container">
        <div class="se-products-heading">
            <div class="se-products-heading-copy">
                <span class="se-products-eyebrow">OUR PRODUCTS</span>
                <h2 id="se-products-title">Featured Solar Products</h2>
                <p>Quality solar equipment with transparent pricing. Enquire on WhatsApp for the best quote.</p>
            </div>

            <a href="{{ route('products.index') }}" class="se-products-view-all">
                View All Products <span aria-hidden="true">→</span>
            </a>
        </div>

        @if ($products->isNotEmpty() && $categories->count() > 1)
        <div class="se-product-tabs" role="tablist" aria-label="Filter products by category">
            <button type="button" class="se-product-tab is-active" role="tab" aria-selected="true" data-filter="all">All</button>
            @foreach ($categories as $category)
            <button type="button" class="se-product-tab" role="tab" aria-selected="false" data-filter="{{ $category->id }}">
                {{ \Illuminate\Support\Str::title($category->name) }}
            </button>
            @endforeach
        </div>
        @endif

        <div class="se-products-grid" id="se-featured-grid" data-limit="8">
            @forelse ($products as $product)
            @include('partials.product-card', ['product' => $product, 'hidden' => $loop->index >= 8])
            @empty
            <div class="se-products-empty">
                <div class="se-products-empty-icon" aria-hidden="true">▧</div>
                <h3>Products Coming Soon</h3>
                <p>We’re updating our product range. Please check back soon or send us an enquiry.</p>
                <a href="{{ route('enquiry') }}" class="se-products-empty-button">Make an Enquiry</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

{{-- PROMO BANNERS --}}
@php
$plantsCategory = $categories->firstWhere('slug', 'solar-power-plants');
$heaterCategory = $categories->firstWhere('slug', 'solar-water-heater');
@endphp
@if ($plantsCategory || $heaterCategory)
<section class="se-promo-section" aria-label="Offers">
    <div class="se-promo-container">
        @if ($plantsCategory)
        <a href="{{ route('products.index', ['category' => $plantsCategory->id]) }}" class="se-promo-card se-promo-card-dark"
            @if ($plantsCategory->cover_image) style="--promo-image: url('{{ asset('storage/' . ltrim($plantsCategory->cover_image, '/')) }}')" @endif>
            <span class="se-promo-tag">PM Surya Ghar Yojana</span>
            <h3>Rooftop Solar with Government Subsidy</h3>
            <p>Cut your electricity bill with an on-grid rooftop system. We help with net metering and subsidy paperwork.</p>
            <span class="se-promo-link">Explore Solar Plants <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        @endif

        @if ($heaterCategory)
        <a href="{{ route('products.index', ['category' => $heaterCategory->id]) }}" class="se-promo-card se-promo-card-warm"
            @if ($heaterCategory->cover_image) style="--promo-image: url('{{ asset('storage/' . ltrim($heaterCategory->cover_image, '/')) }}')" @endif>
            <span class="se-promo-tag">Built for Hill Winters</span>
            <h3>Glass Line Solar Water Heaters</h3>
            <p>Corrosion-resistant tanks and high-efficiency tubes for reliable hot water all year round.</p>
            <span class="se-promo-link">Shop Water Heaters <i class="fa-solid fa-arrow-right" aria-hidden="true"></i></span>
        </a>
        @endif
    </div>
</section>
@endif

{{-- TOP DEALS CAROUSEL --}}
@if ($deals->isNotEmpty())
<section class="se-deals-section" id="top-deals" aria-labelledby="se-deals-title">
    <div class="se-products-container">
        <div class="se-products-heading">
            <div class="se-products-heading-copy">
                <span class="se-products-eyebrow">LIMITED PERIOD</span>
                <h2 id="se-deals-title">Top Deals on Solar</h2>
                <p>Our best prices right now. Stock is limited, so enquire early.</p>
            </div>

            <div class="se-carousel-controls">
                <button type="button" class="se-carousel-btn" data-carousel-prev aria-label="Previous deals"><i class="fa-solid fa-chevron-left" aria-hidden="true"></i></button>
                <button type="button" class="se-carousel-btn" data-carousel-next aria-label="Next deals"><i class="fa-solid fa-chevron-right" aria-hidden="true"></i></button>
            </div>
        </div>

        <div class="se-deals-track" id="se-deals-track" tabindex="0" aria-label="Top deals, scroll horizontally">
            @foreach ($deals as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- WHY CHOOSE US --}}
<section class="se-why-section" aria-labelledby="se-why-title">
    <div class="se-products-container se-why-container">
        <div class="se-why-copy">
            <span class="se-products-eyebrow">WHY SURYAA ENTERPRISES</span>
            <h2 id="se-why-title">Your Trusted Solar Partner in Himachal</h2>
            <p>We supply, install and service solar equipment across Kangra and Himachal Pradesh, with genuine products and honest advice.</p>

            <div class="se-stats">
                <div class="se-stat"><strong>{{ $stats['products'] }}+</strong><span>Solar Products</span></div>
                <div class="se-stat"><strong>{{ $stats['categories'] }}</strong><span>Product Categories</span></div>
                <div class="se-stat"><strong>ISO</strong><span>9001:2015 Certified</span></div>
                @if ($stats['reviews'] > 0)
                <div class="se-stat"><strong>{{ $stats['reviews'] }}</strong><span>Customer Reviews</span></div>
                @endif
            </div>
        </div>

        <ol class="se-steps">
            <li class="se-step">
                <span class="se-step-icon"><i class="fa-solid fa-comments" aria-hidden="true"></i></span>
                <div><h3>Share Your Requirement</h3><p>Call, WhatsApp or send an enquiry with your needs.</p></div>
            </li>
            <li class="se-step">
                <span class="se-step-icon"><i class="fa-solid fa-ruler-combined" aria-hidden="true"></i></span>
                <div><h3>Site Survey &amp; Quote</h3><p>We check your site and suggest the right capacity and price.</p></div>
            </li>
            <li class="se-step">
                <span class="se-step-icon"><i class="fa-solid fa-solar-panel" aria-hidden="true"></i></span>
                <div><h3>Installation</h3><p>Our team installs and commissions the system.</p></div>
            </li>
            <li class="se-step">
                <span class="se-step-icon"><i class="fa-solid fa-shield-halved" aria-hidden="true"></i></span>
                <div><h3>Warranty &amp; Service</h3><p>Local after-sales support whenever you need it.</p></div>
            </li>
        </ol>
    </div>
</section>

{{-- TESTIMONIALS --}}
@if (($testimonials ?? collect())->isNotEmpty())
<section class="se-testimonials-section" id="testimonials" aria-labelledby="se-testimonials-title">
    <div class="se-testimonials-container">
        <div class="se-testimonials-heading">
            <span class="se-testimonials-eyebrow">CUSTOMER FEEDBACK</span>
            <h2 id="se-testimonials-title">What Our Customers Say</h2>
            <p>Real feedback from our customers, selected to highlight their experience with Suryaa Enterprises.</p>
        </div>

        <div class="se-testimonials-actions">
            <a href="{{ route('testimonials.create') }}" class="se-write-review-btn">
                <span aria-hidden="true">✍</span> Write a Review
            </a>
        </div>

        <div class="se-testimonials-grid">
            @foreach ($testimonials->take(3) as $testimonial)
            @php
            $ratingValue = max(0, min(5, (float) $testimonial->rating));
            @endphp

            <article class="se-testimonial-card">
                <div class="se-testimonial-stars" role="img" aria-label="Rated {{ number_format($ratingValue, 1) }} out of 5 stars">
                    @for ($star = 1; $star <= 5; $star++)
                        @if ($ratingValue>= $star)
                        <span class="se-star se-star-full" aria-hidden="true">★</span>
                        @elseif ($ratingValue >= ($star - 0.5))
                        <span class="se-star se-star-half" aria-hidden="true">★</span>
                        @else
                        <span class="se-star se-star-empty" aria-hidden="true">★</span>
                        @endif
                        @endfor
                        <span class="se-testimonial-rating">{{ number_format($ratingValue, 1) }}/5</span>
                </div>

                <blockquote class="se-testimonial-review">“{{ $testimonial->review }}”</blockquote>

                <div class="se-testimonial-customer">
                    <span class="se-testimonial-avatar" aria-hidden="true">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(trim($testimonial->customer_name), 0, 1)) }}</span>
                    <div class="se-testimonial-customer-details">
                        <h3>{{ $testimonial->customer_name }}</h3>
                        @if ($testimonial->designation)
                        <p>{{ $testimonial->designation }}</p>
                        @endif
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ENQUIRY --}}
<section class="se-enquiry-section" id="enquiry">
    <div class="se-enquiry-container">
        <div class="se-enquiry-info">
            <span class="se-enquiry-label">LET'S WORK TOGETHER</span>
            <h2>Get a Free Quote for Your Solar Needs</h2>
            <p class="se-enquiry-description">
                Planning a solar water heater, rooftop plant or street lights?
                Send us your enquiry and our team will call you back with
                the right product and price.
            </p>

            <div class="se-enquiry-points">
                <div class="se-enquiry-point">
                    <span class="se-point-check">✓</span>
                    <span>Product and service enquiries</span>
                </div>
                <div class="se-enquiry-point">
                    <span class="se-point-check">✓</span>
                    <span>Professional support from our team</span>
                </div>
                <div class="se-enquiry-point">
                    <span class="se-point-check">✓</span>
                    <span>Solutions tailored to your requirements</span>
                </div>
            </div>

            <div class="se-quick-contact">
                @if ($phoneNumber !== '')
                <a href="tel:+91{{ substr(preg_replace('/\D+/', '', $phoneNumber), -10) }}" class="se-quick-contact-btn se-quick-call">
                    <i class="fa-solid fa-phone" aria-hidden="true"></i> {{ $phoneNumber }}
                </a>
                @endif
                @if (!empty($whatsappNumber))
                <a href="https://wa.me/{{ $whatsappNumber }}" class="se-quick-contact-btn se-quick-whatsapp" target="_blank" rel="noopener">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                </a>
                @endif
            </div>
        </div>

        <div class="se-enquiry-form-card">
            <div class="se-form-heading">
                <h3>Send an Enquiry</h3>
                <p>Enter your details and we'll connect with you.</p>
            </div>

            @if ($errors->any())
            <div class="se-form-errors" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('enquiry.store') }}" method="POST" class="se-enquiry-form">
                @csrf

                <div class="se-form-field">
                    <label for="enquiry-name">Full Name <span class="se-required">*</span></label>
                    <input type="text" id="enquiry-name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" maxlength="100" autocomplete="name" required aria-invalid="{{ $errors->has('name') ? 'true' : 'false' }}">
                    @error('name') <small class="se-field-error">{{ $message }}</small> @enderror
                </div>

                <div class="se-form-field">
                    <label for="enquiry-phone">Phone Number <span class="se-required">*</span></label>
                    <input type="tel" id="enquiry-phone" name="phone" value="{{ old('phone') }}" placeholder="Enter your phone number" maxlength="20" autocomplete="tel" required aria-invalid="{{ $errors->has('phone') ? 'true' : 'false' }}">
                    @error('phone') <small class="se-field-error">{{ $message }}</small> @enderror
                </div>

                <div class="se-form-field">
                    <label for="enquiry-email">Email Address <span class="se-optional">(Optional)</span></label>
                    <input type="email" id="enquiry-email" name="email" value="{{ old('email') }}" placeholder="Enter your email address" maxlength="255" autocomplete="email" aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    @error('email') <small class="se-field-error">{{ $message }}</small> @enderror
                </div>

                <div class="se-form-field">
                    <label for="enquiry-message">Your Message <span class="se-required">*</span></label>
                    <textarea id="enquiry-message" name="message" rows="5" maxlength="5000" placeholder="Tell us about your requirements..." required aria-invalid="{{ $errors->has('message') ? 'true' : 'false' }}">{{ old('message') }}</textarea>
                    @error('message') <small class="se-field-error">{{ $message }}</small> @enderror
                </div>

                <button type="submit" class="se-enquiry-submit">
                    <span>Submit Enquiry</span>
                    <span class="se-submit-arrow" aria-hidden="true">→</span>
                </button>

                <p class="se-form-footnote"><span class="se-required">*</span> Required fields</p>
            </form>
        </div>
    </div>
</section>

{{-- SUCCESS / ERROR TOAST --}}
@if (session('success') || $errors->any())
<div
    id="enquiryToast"
    class="se-toast {{ session('success') ? 'se-toast-success' : 'se-toast-error' }}"
    role="{{ session('success') ? 'status' : 'alert' }}"
    aria-live="polite">
    <span class="se-toast-icon" aria-hidden="true">{{ session('success') ? '✓' : '!' }}</span>
    <div class="se-toast-content">
        <strong>{{ session('success') ? 'Thank You!' : 'Check Your Details' }}</strong>
        <span>{{ session('success') ?? 'Please correct the errors in the form.' }}</span>
    </div>
    <button type="button" class="se-toast-close" aria-label="Close notification">&times;</button>
    <div class="se-toast-progress"></div>
</div>
@endif

@endsection

@push('styles')
<style>
    /* =========================
       HERO CAROUSEL + QUICK QUOTE FORM
    ========================= */
    .sh-hero,
    .sh-hero * {
        box-sizing: border-box;
    }

    .sh-hero {
        --hero-interval: 6500ms;
        --hero-accent: #fbbf24;
        position: relative;
        isolation: isolate;
        display: flex;
        flex-direction: column;
        min-height: clamp(620px, 84vh, 780px);
        margin: 0 0 36px;
        overflow: hidden;
        background: #022c22;
        color: #fff;
    }

    /* Slides: diagonal wipe reveal + slow zoom */
    .sh-slides {
        position: absolute;
        inset: 0;
        z-index: 0;
    }

    .sh-slide {
        position: absolute;
        inset: 0;
        z-index: 0;
        overflow: hidden;
        visibility: hidden;
        background: linear-gradient(135deg, #064e3b, #022c22);
    }

    .sh-slide.is-prev {
        z-index: 1;
        visibility: visible;
    }

    .sh-slide.is-active {
        z-index: 2;
        visibility: visible;
    }

    .sh-slide.is-entering {
        animation: shWipe 1.15s cubic-bezier(.77, 0, .18, 1) both;
    }

    .sh-slide.is-entering.is-reverse {
        animation-name: shWipeReverse;
    }

    .sh-slide-bg {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transform-origin: 70% 50%;
    }

    .sh-slide.is-active .sh-slide-bg {
        animation: shZoom 9s ease-out both;
    }

    .sh-overlay {
        position: absolute;
        inset: 0;
        z-index: 3;
        pointer-events: none;
        background:
            radial-gradient(circle at 82% 12%, rgba(251, 191, 36, .22), transparent 32%),
            linear-gradient(0deg, rgba(2, 30, 24, .85) 0%, transparent 28%),
            linear-gradient(90deg, rgba(2, 30, 24, .94) 0%, rgba(2, 44, 34, .8) 38%, rgba(2, 44, 34, .35) 70%, rgba(2, 44, 34, .55) 100%);
    }

    @keyframes shWipe {
        from {
            clip-path: polygon(115% 0, 115% 0, 100% 100%, 100% 100%);
        }

        to {
            clip-path: polygon(0 0, 115% 0, 100% 100%, -15% 100%);
        }
    }

    @keyframes shWipeReverse {
        from {
            clip-path: polygon(0 0, 0 0, -15% 100%, -15% 100%);
        }

        to {
            clip-path: polygon(0 0, 115% 0, 100% 100%, -15% 100%);
        }
    }

    @keyframes shZoom {
        from {
            transform: scale(1.16);
        }

        to {
            transform: scale(1);
        }
    }

    /* Content grid: copy on the left, form on the right */
    .sh-inner {
        position: relative;
        z-index: 4;
        display: grid;
        flex: 1;
        grid-template-columns: minmax(0, 1fr) 400px;
        align-items: center;
        gap: 56px;
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
        padding: 64px 0 128px;
    }

    .sh-copy {
        display: grid;
        min-width: 0;
    }

    .sh-copy-item {
        grid-area: 1 / 1;
        align-self: center;
        min-width: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .sh-copy-item.is-active,
    .sh-copy-item.is-leaving {
        visibility: visible;
    }

    .sh-copy-item.is-active {
        pointer-events: auto;
    }

    .sh-copy-item > * {
        opacity: 0;
    }

    .sh-copy-item.is-active > * {
        animation: shRise .85s cubic-bezier(.2, .8, .2, 1) both;
    }

    .sh-copy-item.is-active > :nth-child(1) {
        animation-delay: .25s;
    }

    .sh-copy-item.is-active > :nth-child(2) {
        animation-delay: .4s;
    }

    .sh-copy-item.is-active > :nth-child(3) {
        animation-delay: .55s;
    }

    .sh-copy-item.is-active > :nth-child(4) {
        animation-delay: .7s;
    }

    .sh-copy-item.is-leaving > * {
        animation: shFade .4s ease both;
    }

    @keyframes shRise {
        from {
            opacity: 0;
            transform: translateY(32px);
            filter: blur(6px);
        }

        to {
            opacity: 1;
            transform: none;
            filter: none;
        }
    }

    @keyframes shFade {
        from {
            opacity: 1;
        }

        to {
            opacity: 0;
            transform: translateY(-18px);
        }
    }

    .sh-kicker {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        padding: 7px 14px;
        border: 1px solid rgba(251, 191, 36, .45);
        border-radius: 999px;
        background: rgba(251, 191, 36, .14);
        color: #fde68a;
        font-size: 13px;
        font-weight: 700;
        backdrop-filter: blur(6px);
    }

    .sh-kicker i {
        color: var(--hero-accent);
        animation: shSpin 12s linear infinite;
    }

    .sh-heading {
        max-width: 640px;
        margin: 0 0 18px;
        color: #fff;
        font-size: clamp(36px, 5vw, 64px);
        font-weight: 800;
        line-height: 1.05;
        letter-spacing: -1.2px;
        text-wrap: balance;
    }

    .sh-description {
        max-width: 560px;
        margin: 0 0 30px;
        color: rgba(255, 255, 255, .84);
        font-size: clamp(15px, 1.4vw, 18px);
        line-height: 1.7;
    }

    .sh-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
    }

    .sh-btn {
        position: relative;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 52px;
        padding: 14px 26px;
        overflow: hidden;
        border-radius: 12px;
        font-size: 15px;
        font-weight: 800;
        text-decoration: none !important;
        transition: transform .25s ease, background .25s ease, box-shadow .25s ease;
    }

    .sh-btn:hover {
        transform: translateY(-2px);
    }

    .sh-btn-primary {
        background: var(--hero-accent);
        color: #422006 !important;
        box-shadow: 0 10px 30px rgba(251, 191, 36, .3);
    }

    /* Light sweep across the primary button on hover */
    .sh-btn-primary::after {
        position: absolute;
        top: 0;
        left: -80%;
        width: 50%;
        height: 100%;
        content: "";
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .55), transparent);
        transform: skewX(-20deg);
        transition: left .6s ease;
    }

    .sh-btn-primary:hover::after {
        left: 130%;
    }

    .sh-btn-primary i {
        transition: transform .25s ease;
    }

    .sh-btn-primary:hover i {
        transform: translateX(4px);
    }

    .sh-btn-ghost {
        border: 1px solid rgba(255, 255, 255, .55);
        color: #fff !important;
        backdrop-filter: blur(6px);
    }

    .sh-btn-ghost:hover {
        background: rgba(255, 255, 255, .12);
    }

    .sh-badges {
        display: flex;
        flex-wrap: wrap;
        gap: 10px 22px;
        margin: 40px 0 0;
        padding: 0;
        color: rgba(255, 255, 255, .78);
        font-size: 13px;
        font-weight: 600;
        list-style: none;
    }

    .sh-badges i {
        margin-right: 6px;
        color: var(--hero-accent);
    }

    /* Form card with an animated glowing border */
    .sh-form-card {
        position: relative;
        padding: 1.5px;
        overflow: hidden;
        border-radius: 22px;
        box-shadow: 0 30px 80px rgba(0, 0, 0, .4);
        animation: shCardIn 1s cubic-bezier(.2, .8, .2, 1) .5s both;
        scroll-margin-top: 140px;
    }

    .sh-form-glow {
        position: absolute;
        inset: -60%;
        background: conic-gradient(from 0deg, rgba(255, 255, 255, .08) 0 62%, #fbbf24 75%, #34d399 88%, rgba(255, 255, 255, .08) 100%);
        animation: shSpin 6s linear infinite;
    }

    .sh-form-body {
        position: relative;
        padding: 30px 28px 26px;
        border-radius: 20.5px;
        background: linear-gradient(160deg, rgba(6, 50, 39, .97), rgba(3, 30, 24, .97));
    }

    @keyframes shSpin {
        to {
            transform: rotate(360deg);
        }
    }

    @keyframes shCardIn {
        from {
            opacity: 0;
            transform: translateY(40px) scale(.97);
        }

        to {
            opacity: 1;
            transform: none;
        }
    }

    .sh-form-head {
        display: flex;
        align-items: center;
        gap: 14px;
        margin-bottom: 24px;
    }

    .sh-form-icon {
        display: grid;
        flex: 0 0 48px;
        width: 48px;
        height: 48px;
        place-items: center;
        border-radius: 14px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006;
        font-size: 20px;
        box-shadow: 0 8px 22px rgba(251, 191, 36, .35);
    }

    .sh-form-head h2 {
        margin: 0 0 3px;
        color: #fff;
        font-size: 22px;
        font-weight: 800;
    }

    .sh-form-head p {
        margin: 0;
        color: rgba(255, 255, 255, .65);
        font-size: 13px;
    }

    .sh-form {
        display: grid;
        gap: 14px;
    }

    /* Floating-label fields */
    .sh-field {
        position: relative;
    }

    .sh-field input,
    .sh-field select {
        display: block;
        width: 100%;
        height: 58px;
        margin: 0;
        padding: 24px 16px 8px;
        border: 1px solid rgba(255, 255, 255, .16);
        border-radius: 12px;
        outline: none;
        background: rgba(255, 255, 255, .06);
        color: #fff;
        font: inherit;
        font-size: 15px;
        transition: border-color .25s ease, background .25s ease, box-shadow .25s ease;
        appearance: none;
    }

    .sh-field select {
        padding-right: 40px;
        cursor: pointer;
    }

    .sh-field select option {
        color: #1f2937;
    }

    .sh-field label {
        position: absolute;
        top: 29px;
        left: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        color: rgba(255, 255, 255, .6);
        font-size: 15px;
        pointer-events: none;
        transform: translateY(-50%);
        transform-origin: left center;
        transition: top .22s ease, font-size .22s ease, color .22s ease;
    }

    .sh-field input:focus,
    .sh-field select:focus {
        border-color: var(--hero-accent);
        background: rgba(255, 255, 255, .1);
        box-shadow: 0 0 0 4px rgba(251, 191, 36, .14);
    }

    .sh-field input:focus + label,
    .sh-field input:not(:placeholder-shown) + label,
    .sh-field select:focus + label,
    .sh-field select:valid + label {
        top: 16px;
        color: var(--hero-accent);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .sh-select-arrow {
        position: absolute;
        top: 29px;
        right: 16px;
        color: rgba(255, 255, 255, .6);
        font-size: 12px;
        pointer-events: none;
        transform: translateY(-50%);
        transition: transform .25s ease;
    }

    .sh-field select:focus ~ .sh-select-arrow {
        color: var(--hero-accent);
        transform: translateY(-50%) rotate(180deg);
    }

    .sh-field.has-error input,
    .sh-field.has-error select {
        border-color: #f87171;
        animation: shShake .4s ease;
    }

    .sh-field-error {
        display: block;
        margin-top: 5px;
        color: #fca5a5;
        font-size: 12px;
    }

    .sh-field-error:empty {
        display: none;
    }

    @keyframes shShake {

        25%,
        75% {
            transform: translateX(-4px);
        }

        50% {
            transform: translateX(4px);
        }
    }

    .sh-submit {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        width: 100%;
        min-height: 56px;
        margin-top: 4px;
        overflow: hidden;
        border: 0;
        border-radius: 12px;
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: #422006;
        font: inherit;
        font-size: 16px;
        font-weight: 800;
        cursor: pointer;
        box-shadow: 0 10px 28px rgba(251, 191, 36, .3);
        transition: transform .25s ease, box-shadow .25s ease;
    }

    .sh-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 16px 36px rgba(251, 191, 36, .4);
    }

    .sh-submit::after {
        position: absolute;
        top: 0;
        left: -80%;
        width: 50%;
        height: 100%;
        content: "";
        background: linear-gradient(100deg, transparent, rgba(255, 255, 255, .6), transparent);
        transform: skewX(-20deg);
        animation: shSweep 3.5s ease-in-out infinite;
    }

    @keyframes shSweep {

        0%,
        60% {
            left: -80%;
        }

        100% {
            left: 130%;
        }
    }

    .sh-submit-arrow {
        transition: transform .25s ease;
    }

    .sh-submit:hover .sh-submit-arrow {
        transform: translateX(4px);
    }

    .sh-spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(66, 32, 6, .3);
        border-top-color: #422006;
        border-radius: 50%;
        animation: shSpin .7s linear infinite;
    }

    .sh-submit.is-loading {
        cursor: wait;
    }

    .sh-submit.is-loading .sh-spinner {
        display: block;
    }

    .sh-submit.is-loading .sh-submit-arrow {
        display: none;
    }

    .sh-submit:focus-visible,
    .sh-arrow:focus-visible,
    .sh-tab:focus-visible,
    .sh-btn:focus-visible {
        outline: 3px solid #fde68a;
        outline-offset: 3px;
    }

    .sh-form-note {
        margin: 2px 0 0;
        color: rgba(255, 255, 255, .5);
        font-size: 11px;
        text-align: center;
    }

    .sh-form-note i {
        margin-right: 4px;
    }

    /* Success state with a drawn checkmark */
    .sh-success {
        padding: 18px 0 6px;
        text-align: center;
        animation: shRise .6s ease both;
    }

    .sh-success-check {
        width: 76px;
        height: 76px;
        margin-bottom: 14px;
        stroke: #34d399;
        stroke-width: 3;
        stroke-linecap: round;
        stroke-linejoin: round;
    }

    .sh-success-check circle {
        stroke-dasharray: 152;
        stroke-dashoffset: 152;
        animation: shDraw .7s ease forwards;
    }

    .sh-success-check path {
        stroke-dasharray: 40;
        stroke-dashoffset: 40;
        animation: shDraw .45s ease .6s forwards;
    }

    @keyframes shDraw {
        to {
            stroke-dashoffset: 0;
        }
    }

    .sh-success h3 {
        margin: 0 0 8px;
        color: #fff;
        font-size: 22px;
    }

    .sh-success p {
        margin: 0 0 20px;
        color: rgba(255, 255, 255, .75);
        font-size: 14px;
        line-height: 1.6;
    }

    .sh-success-reset {
        padding: 10px 18px;
        border: 1px solid rgba(255, 255, 255, .3);
        border-radius: 10px;
        background: transparent;
        color: #fff;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .sh-success-reset:hover {
        background: rgba(255, 255, 255, .1);
    }

    /* Bottom navigation: numbered tabs with autoplay progress */
    .sh-nav {
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
        z-index: 5;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        width: min(1240px, calc(100% - 48px));
        margin: 0 auto;
        padding-bottom: 30px;
    }

    .sh-tabs {
        display: flex;
        flex: 1;
        gap: 6px;
        min-width: 0;
        max-width: 760px;
    }

    .sh-tab {
        position: relative;
        display: flex;
        flex: 1;
        flex-direction: column;
        align-items: flex-start;
        gap: 4px;
        min-width: 0;
        padding: 16px 10px 0 0;
        border: 0;
        background: transparent;
        color: rgba(255, 255, 255, .55);
        font: inherit;
        text-align: left;
        cursor: pointer;
        transition: color .25s ease;
    }

    .sh-tab:hover,
    .sh-tab.is-active {
        color: #fff;
    }

    .sh-tab-num {
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 1px;
    }

    .sh-tab.is-active .sh-tab-num {
        color: var(--hero-accent);
    }

    .sh-tab-label {
        max-width: 100%;
        overflow: hidden;
        font-size: 13px;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .sh-tab-progress {
        position: absolute;
        top: 0;
        right: 10px;
        left: 0;
        height: 3px;
        overflow: hidden;
        border-radius: 3px;
        background: rgba(255, 255, 255, .22);
    }

    .sh-tab-progress span {
        display: block;
        width: 100%;
        height: 100%;
        background: var(--hero-accent);
        transform: scaleX(0);
        transform-origin: left;
    }

    /* The active tab's fill drives autoplay: the slider advances on animationend. */
    .sh-tab.is-active .sh-tab-progress span {
        animation: shProgress var(--hero-interval) linear forwards;
    }

    .sh-hero.is-paused .sh-tab.is-active .sh-tab-progress span {
        animation-play-state: paused;
    }

    .sh-hero.is-static .sh-tab.is-active .sh-tab-progress span {
        animation: none;
        transform: scaleX(1);
    }

    @keyframes shProgress {
        to {
            transform: scaleX(1);
        }
    }

    .sh-arrows {
        display: flex;
        gap: 10px;
    }

    .sh-arrow {
        display: grid;
        width: 50px;
        height: 50px;
        place-items: center;
        border: 1px solid rgba(255, 255, 255, .35);
        border-radius: 50%;
        background: rgba(255, 255, 255, .06);
        color: #fff;
        font-size: 16px;
        cursor: pointer;
        backdrop-filter: blur(6px);
        transition: background .25s ease, color .25s ease, border-color .25s ease, transform .25s ease;
    }

    .sh-arrow:hover {
        border-color: var(--hero-accent);
        background: var(--hero-accent);
        color: #422006;
        transform: scale(1.06);
    }

    @media (max-width: 1100px) {
        .sh-inner {
            grid-template-columns: minmax(0, 1fr) 360px;
            gap: 32px;
        }
    }

    /* Tablet / mobile: copy, then slide tabs, then the form, stacked */
    @media (max-width: 900px) {
        .sh-hero {
            min-height: 0;
        }

        .sh-inner {
            display: contents;
        }

        .sh-copy,
        .sh-nav,
        .sh-form-card {
            position: relative;
            z-index: 4;
            width: calc(100% - 40px);
            margin-right: auto;
            margin-left: auto;
        }

        .sh-copy {
            order: 1;
            padding: 56px 0 28px;
        }

        .sh-nav {
            order: 2;
            padding-bottom: 28px;
        }

        .sh-form-card {
            order: 3;
            max-width: 560px;
            margin-bottom: 40px;
        }

        .sh-overlay {
            background:
                radial-gradient(circle at 85% 8%, rgba(251, 191, 36, .2), transparent 35%),
                linear-gradient(180deg, rgba(2, 44, 34, .72) 0%, rgba(2, 30, 24, .9) 45%, #022c22 75%);
        }

        .sh-badges {
            margin-top: 28px;
        }
    }

    @media (max-width: 560px) {
        .sh-copy {
            padding-top: 40px;
        }

        .sh-kicker {
            font-size: 11px;
        }

        .sh-heading {
            font-size: clamp(30px, 9vw, 38px);
            letter-spacing: -.5px;
        }

        .sh-description {
            margin-bottom: 24px;
            font-size: 15px;
        }

        .sh-btn {
            flex: 1 1 auto;
            min-height: 48px;
            padding: 12px 18px;
            font-size: 14px;
        }

        .sh-tab-label,
        .sh-arrows {
            display: none;
        }

        .sh-tab {
            align-items: center;
        }

        .sh-tab-progress {
            right: 4px;
            left: 4px;
        }

        .sh-form-body {
            padding: 24px 18px 20px;
        }

        .sh-form-head h2 {
            font-size: 20px;
        }
    }

    /* =========================
       FEATURED PRODUCTS
    ========================= */
    .se-products-section,
    .se-products-section * {
        box-sizing: border-box;
    }

    .se-products-section {
        padding: 76px 24px 84px;
        background: #fff;
    }

    .se-products-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .se-products-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 24px;
        margin-bottom: 34px;
    }

    .se-products-heading-copy {
        min-width: 0;
    }

    .se-products-eyebrow,
    .se-testimonials-eyebrow {
        display: inline-block;
        margin-bottom: 12px;
        color: #047857;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .se-products-heading h2,
    .se-testimonials-heading h2 {
        margin: 0 0 12px;
        color: #172d25;
        font-size: clamp(30px, 3.4vw, 43px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.6px;
        overflow-wrap: anywhere;
    }

    .se-products-heading p,
    .se-testimonials-heading p {
        max-width: 620px;
        margin: 0;
        color: #718078;
        font-size: 15px;
        line-height: 1.8;
    }

    .se-products-view-all {
        display: inline-flex;
        flex: 0 0 auto;
        align-items: center;
        justify-content: center;
        gap: 10px;
        min-height: 44px;
        padding: 11px 16px;
        border: 1px solid #047857;
        border-radius: 8px;
        color: #047857 !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease, transform .2s ease;
    }

    .se-products-view-all:hover {
        transform: translateY(-2px);
        background: #047857;
        color: #fff !important;
    }

    .se-products-grid {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 24px;
    }

    .se-products-empty {
        grid-column: 1 / -1;
        padding: 44px 22px;
        border: 1px dashed #cddbd1;
        border-radius: 14px;
        background: #f8fbf8;
        text-align: center;
    }

    .se-products-empty-icon {
        margin-bottom: 12px;
        color: #047857;
        font-size: 38px;
    }

    .se-products-empty h3 {
        margin: 0 0 9px;
        color: #20362a;
        font-size: 22px;
        font-weight: 800;
    }

    .se-products-empty p {
        max-width: 500px;
        margin: 0 auto 20px;
        color: #718078;
        font-size: 14px;
        line-height: 1.8;
    }

    .se-products-empty-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        padding: 11px 18px;
        border-radius: 7px;
        background: #047857;
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
    }

    /* =========================
       TESTIMONIALS
    ========================= */
    .se-testimonials-section,
    .se-testimonials-section * {
        box-sizing: border-box;
    }

    .se-testimonials-section {
        padding: 76px 24px 84px;
        background: #f5f8f6;
    }

    .se-testimonials-container {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
    }

    .se-testimonials-heading {
        max-width: 720px;
        margin: 0 auto 30px;
        text-align: center;
    }

    .se-testimonials-heading p {
        margin-right: auto;
        margin-left: auto;
    }

    .se-testimonials-actions {
        display: flex;
        justify-content: center;
        margin: 0 0 32px;
    }

    .se-write-review-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        max-width: 100%;
        padding: 13px 24px;
        border: 1px solid #dfa83f;
        border-radius: 8px;
        background: #dfa83f;
        color: #17230d !important;
        font-size: 15px;
        font-weight: 700;
        text-decoration: none !important;
        transition: background .2s ease, transform .2s ease;
    }

    .se-write-review-btn:hover {
        transform: translateY(-2px);
        background: #c99027;
    }

    .se-testimonials-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    .se-testimonial-card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        padding: 26px;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(23, 45, 37, .045);
    }

    .se-testimonial-stars {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 3px;
        margin-bottom: 18px;
    }

    .se-star {
        color: #d6dcd8;
        font-size: 22px;
        line-height: 1;
    }

    .se-star-full {
        color: #f5b301;
    }

    .se-star-half {
        color: #f5b301;
        background: linear-gradient(90deg, #f5b301 50%, #d6dcd8 50%);
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .se-testimonial-rating {
        margin-left: 7px;
        color: #65756d;
        font-size: 12px;
        font-weight: 700;
    }

    .se-testimonial-review {
        flex: 1;
        margin: 0 0 24px;
        color: #526158;
        font-size: 14px;
        line-height: 1.9;
        overflow-wrap: anywhere;
    }

    .se-testimonial-customer {
        display: flex;
        align-items: center;
        gap: 12px;
        padding-top: 18px;
        border-top: 1px solid #edf1ee;
    }

    .se-testimonial-avatar {
        display: grid;
        flex: 0 0 44px;
        width: 44px;
        height: 44px;
        place-items: center;
        border-radius: 50%;
        background: #eaf5ed;
        color: #047857;
        font-size: 17px;
        font-weight: 800;
    }

    .se-testimonial-customer-details {
        min-width: 0;
    }

    .se-testimonial-customer h3 {
        margin: 0 0 4px;
        color: #20362a;
        font-size: 14px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .se-testimonial-customer p {
        margin: 0;
        color: #89958e;
        font-size: 12px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    /* =========================
       ENQUIRY SECTION
    ========================= */
    .se-enquiry-section,
    .se-enquiry-section * {
        box-sizing: border-box;
    }

    .se-enquiry-section {
        padding: 80px 24px;
        background: #f5f8f7;
        scroll-margin-top: 90px;
    }

    .se-enquiry-container {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, .92fr);
        align-items: center;
        gap: clamp(32px, 5vw, 72px);
        width: 100%;
        max-width: 1160px;
        margin: 0 auto;
    }

    .se-enquiry-info {
        min-width: 0;
    }

    .se-enquiry-label {
        display: inline-block;
        margin-bottom: 18px;
        color: #047857;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .se-enquiry-info h2 {
        max-width: 510px;
        margin: 0 0 22px;
        color: #172d25;
        font-size: clamp(32px, 3.6vw, 46px);
        font-weight: 800;
        line-height: 1.2;
        letter-spacing: -.7px;
        overflow-wrap: anywhere;
    }

    .se-enquiry-description {
        max-width: 490px;
        margin: 0;
        color: #65756d;
        font-size: 15px;
        line-height: 1.9;
    }

    .se-enquiry-points {
        display: grid;
        gap: 16px;
        margin-top: 30px;
    }

    .se-enquiry-point {
        display: flex;
        align-items: center;
        gap: 13px;
        color: #30443a;
        font-size: 14px;
        font-weight: 600;
        line-height: 1.6;
    }

    .se-point-check {
        display: inline-grid;
        flex: 0 0 28px;
        width: 28px;
        height: 28px;
        place-items: center;
        border-radius: 50%;
        background: #dff4e7;
        color: #047857;
        font-size: 15px;
        font-weight: 800;
    }

    .se-enquiry-form-card {
        min-width: 0;
        padding: clamp(22px, 3vw, 36px);
        border: 1px solid #e7eee9;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 18px 50px rgba(27, 57, 39, .08);
    }

    .se-form-heading {
        margin-bottom: 26px;
    }

    .se-form-heading h3 {
        margin: 0 0 9px;
        color: #172d25;
        font-size: 27px;
        font-weight: 800;
        line-height: 1.3;
    }

    .se-form-heading p {
        margin: 0;
        color: #77857d;
        font-size: 13px;
        line-height: 1.7;
    }

    .se-enquiry-form {
        display: grid;
        gap: 19px;
    }

    .se-form-field {
        display: flex;
        min-width: 0;
        flex-direction: column;
        gap: 9px;
    }

    .se-form-field label {
        color: #293c31;
        font-size: 13px;
        font-weight: 700;
    }

    .se-required {
        color: #d63333;
    }

    .se-optional {
        color: #89958e;
        font-size: 11px;
        font-weight: 500;
    }

    .se-form-field input,
    .se-form-field textarea {
        display: block;
        width: 100%;
        min-width: 0;
        margin: 0;
        padding: 13px 14px;
        border: 1px solid #dce5de;
        border-radius: 8px;
        outline: none;
        background: #fff;
        color: #20362a;
        font: inherit;
        font-size: 14px;
        line-height: 1.5;
        transition: border-color .2s ease, box-shadow .2s ease;
    }

    .se-form-field input {
        min-height: 48px;
    }

    .se-form-field textarea {
        min-height: 130px;
        resize: vertical;
    }

    .se-form-field input::placeholder,
    .se-form-field textarea::placeholder {
        color: #a1aca5;
        opacity: 1;
    }

    .se-form-field input:focus,
    .se-form-field textarea:focus {
        border-color: #047857;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, .11);
    }

    .se-form-field input[aria-invalid="true"],
    .se-form-field textarea[aria-invalid="true"] {
        border-color: #dc6262;
    }

    .se-field-error {
        color: #b42323;
        font-size: 12px;
        line-height: 1.5;
    }

    .se-enquiry-submit {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 12px;
        width: 100%;
        min-height: 52px;
        margin-top: 2px;
        padding: 14px 20px;
        border: 0;
        border-radius: 8px;
        background: #047857;
        color: #fff;
        font-family: inherit;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease, transform .2s ease;
    }

    .se-enquiry-submit:hover {
        transform: translateY(-1px);
        background: #022c22;
    }

    .se-enquiry-submit:focus-visible,
    .se-toast-close:focus-visible {
        outline: 3px solid #8be0ac;
        outline-offset: 3px;
    }

    .se-submit-arrow {
        font-size: 20px;
        line-height: 1;
    }

    .se-form-footnote {
        margin: -7px 0 0;
        color: #89958e;
        font-size: 11px;
        text-align: center;
    }

    .se-form-errors {
        margin-bottom: 22px;
        padding: 14px 16px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        background: #fef2f2;
        color: #991b1b;
        font-size: 13px;
        line-height: 1.6;
    }

    .se-form-errors ul {
        margin: 7px 0 0;
        padding-left: 20px;
    }

    .se-form-errors li+li {
        margin-top: 4px;
    }

    /* Toast notification */
    .se-toast {
        position: fixed;
        top: 24px;
        right: 24px;
        z-index: 99999;
        display: flex;
        align-items: center;
        gap: 12px;
        width: min(420px, calc(100vw - 32px));
        box-sizing: border-box;
        padding: 16px 18px 19px;
        overflow: hidden;
        border: 1px solid transparent;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 12px 35px rgba(0, 0, 0, .16);
        font-family: inherit;
        animation: seToastEnter .3s ease-out both;
    }

    .se-toast-success {
        border-color: #bbf7d0;
        color: #166534;
    }

    .se-toast-error {
        border-color: #fecaca;
        color: #991b1b;
    }

    .se-toast-icon {
        display: grid;
        flex: 0 0 34px;
        width: 34px;
        height: 34px;
        place-items: center;
        border-radius: 50%;
        color: #fff;
        font-size: 19px;
        font-weight: 700;
    }

    .se-toast-success .se-toast-icon {
        background: #16a34a;
    }

    .se-toast-error .se-toast-icon {
        background: #dc2626;
    }

    .se-toast-content {
        display: flex;
        flex: 1;
        min-width: 0;
        flex-direction: column;
        gap: 4px;
    }

    .se-toast-content strong {
        font-size: 15px;
        font-weight: 700;
    }

    .se-toast-content>span {
        color: #475569;
        font-size: 13px;
        line-height: 1.5;
        overflow-wrap: anywhere;
    }

    .se-toast-close {
        flex-shrink: 0;
        align-self: flex-start;
        padding: 0;
        border: 0;
        background: transparent;
        color: #64748b;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
    }

    .se-toast-progress {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 3px;
        transform-origin: left;
        animation: seToastCountdown 3.5s linear forwards;
    }

    .se-toast-success .se-toast-progress {
        background: #16a34a;
    }

    .se-toast-error .se-toast-progress {
        background: #dc2626;
    }

    .se-toast.se-toast-leaving {
        animation: seToastExit .25s ease-in forwards;
    }

    @keyframes seToastEnter {
        from {
            opacity: 0;
            transform: translateX(24px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes seToastExit {
        to {
            opacity: 0;
            transform: translateX(24px);
        }
    }

    @keyframes seToastCountdown {
        from {
            transform: scaleX(1);
        }

        to {
            transform: scaleX(0);
        }
    }

    /* =========================
       TRUST STRIP
    ========================= */
    .se-trust-strip,
    .se-trust-strip *,
    .se-categories-section *,
    .se-promo-section *,
    .se-deals-section *,
    .se-why-section * {
        box-sizing: border-box;
    }

    .se-trust-strip {
        padding: 0 24px;
        margin: -8px 0 0;
    }

    .se-trust-container {
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        max-width: 1200px;
        margin: 0 auto;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 10px 30px rgba(23, 45, 37, .06);
    }

    .se-trust-item {
        display: flex;
        align-items: center;
        gap: 14px;
        min-width: 0;
        padding: 20px 22px;
    }

    .se-trust-item + .se-trust-item {
        border-left: 1px solid #edf1ee;
    }

    .se-trust-item > i {
        display: grid;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        place-items: center;
        border-radius: 12px;
        background: #eaf5ed;
        color: var(--primary-color, #047857);
        font-size: 20px;
    }

    .se-trust-item strong {
        display: block;
        color: #172d25;
        font-size: 14px;
        font-weight: 800;
    }

    .se-trust-item span {
        display: block;
        margin-top: 3px;
        color: #718078;
        font-size: 12px;
        line-height: 1.4;
    }

    /* =========================
       SHOP BY CATEGORY
    ========================= */
    .se-categories-section {
        padding: 72px 24px 8px;
        background: #fff;
    }

    .se-section-heading-center {
        max-width: 680px;
        margin: 0 auto 34px;
        text-align: center;
    }

    .se-section-heading-center h2 {
        margin: 0 0 12px;
        color: #172d25;
        font-size: clamp(28px, 3.2vw, 40px);
        font-weight: 800;
        line-height: 1.2;
    }

    .se-section-heading-center p {
        margin: 0;
        color: #718078;
        font-size: 15px;
        line-height: 1.8;
    }

    .se-categories-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(190px, 1fr));
        gap: 20px;
    }

    .se-category-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
        text-decoration: none !important;
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .se-category-card:hover {
        transform: translateY(-4px);
        border-color: #cfe3d6;
        box-shadow: 0 16px 34px rgba(23, 45, 37, .1);
    }

    .se-category-image {
        display: grid;
        height: 150px;
        place-items: center;
        overflow: hidden;
        background: #f0f6f2;
        color: var(--primary-color, #047857);
        font-size: 42px;
    }

    .se-category-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .se-category-card:hover .se-category-image img {
        transform: scale(1.06);
    }

    .se-category-body {
        padding: 14px 16px 16px;
    }

    .se-category-body h3 {
        margin: 0 0 6px;
        color: #20362a;
        font-size: 15px;
        font-weight: 800;
        line-height: 1.35;
    }

    .se-category-body span {
        color: var(--primary-color, #047857);
        font-size: 12px;
        font-weight: 700;
    }

    .se-category-body span i {
        margin-left: 4px;
        font-size: 10px;
        transition: transform .2s ease;
    }

    .se-category-card:hover .se-category-body span i {
        transform: translateX(3px);
    }
    /* =========================
       PRODUCT TABS
    ========================= */

    .se-product-tabs {
        display: flex;
        gap: 10px;
        margin: -8px 0 28px;
        padding-bottom: 4px;
        overflow-x: auto;
        scrollbar-width: none;
    }

    .se-product-tabs::-webkit-scrollbar {
        display: none;
    }

    .se-product-tab {
        flex: 0 0 auto;
        min-height: 40px;
        padding: 9px 18px;
        border: 1px solid #dce5de;
        border-radius: 999px;
        background: #fff;
        color: #30443a;
        font: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .se-product-tab:hover {
        border-color: var(--primary-color, #047857);
        color: var(--primary-color, #047857);
    }

    .se-product-tab.is-active {
        border-color: var(--primary-color, #047857);
        background: var(--primary-color, #047857);
        color: #fff;
    }

    /* =========================
       PROMO BANNERS
    ========================= */
    .se-promo-section {
        padding: 0 24px 76px;
        background: #fff;
    }

    .se-promo-container {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .se-promo-card {
        position: relative;
        display: flex;
        min-height: 280px;
        flex-direction: column;
        justify-content: flex-end;
        padding: 32px;
        overflow: hidden;
        border-radius: 18px;
        background-color: #172d25;
        background-image: var(--promo-image, none);
        background-position: center;
        background-size: cover;
        color: #fff !important;
        text-decoration: none !important;
        isolation: isolate;
    }

    .se-promo-card::before {
        position: absolute;
        inset: 0;
        z-index: -1;
        content: "";
        transition: opacity .3s ease;
    }

    .se-promo-card-dark::before {
        background: linear-gradient(100deg, rgba(2, 44, 34, .94) 0%, rgba(2, 44, 34, .7) 55%, rgba(2, 44, 34, .25) 100%);
    }

    .se-promo-card-warm::before {
        background: linear-gradient(100deg, rgba(124, 45, 18, .94) 0%, rgba(154, 52, 18, .72) 55%, rgba(154, 52, 18, .25) 100%);
    }

    .se-promo-card:hover::before {
        opacity: .9;
    }

    .se-promo-tag {
        align-self: flex-start;
        margin-bottom: 14px;
        padding: 5px 10px;
        border-radius: 999px;
        background: #fbbf24;
        color: #422006;
        font-size: 11px;
        font-weight: 800;
    }

    .se-promo-card h3 {
        max-width: 420px;
        margin: 0 0 10px;
        color: #fff;
        font-size: clamp(22px, 2.4vw, 30px);
        font-weight: 800;
        line-height: 1.2;
    }

    .se-promo-card p {
        max-width: 420px;
        margin: 0 0 18px;
        color: rgba(255, 255, 255, .85);
        font-size: 14px;
        line-height: 1.7;
    }

    .se-promo-link {
        font-size: 14px;
        font-weight: 800;
    }

    .se-promo-link i {
        margin-left: 6px;
        transition: transform .2s ease;
    }

    .se-promo-card:hover .se-promo-link i {
        transform: translateX(4px);
    }

    /* =========================
       TOP DEALS CAROUSEL
    ========================= */
    .se-deals-section {
        padding: 72px 24px 80px;
        background: linear-gradient(180deg, #fffbeb 0%, #fff 100%);
    }

    .se-carousel-controls {
        display: flex;
        flex: 0 0 auto;
        gap: 10px;
    }

    .se-carousel-btn {
        display: grid;
        width: 44px;
        height: 44px;
        place-items: center;
        border: 1px solid #dce5de;
        border-radius: 50%;
        background: #fff;
        color: #20362a;
        cursor: pointer;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .se-carousel-btn:hover:not(:disabled) {
        border-color: var(--primary-color, #047857);
        background: var(--primary-color, #047857);
        color: #fff;
    }

    .se-carousel-btn:disabled {
        opacity: .4;
        cursor: default;
    }

    .se-deals-track {
        display: grid;
        grid-auto-columns: calc((100% - 72px) / 4);
        grid-auto-flow: column;
        gap: 24px;
        padding: 4px 2px 18px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scroll-snap-type: x mandatory;
        scrollbar-width: thin;
    }

    .se-deals-track > .se-product-card {
        scroll-snap-align: start;
    }

    .se-deals-track:focus-visible {
        outline: 3px solid #8be0ac;
        outline-offset: 4px;
    }

    /* =========================
       WHY CHOOSE US
    ========================= */
    .se-why-section {
        padding: 80px 24px;
        background: #022c22;
        color: #fff;
    }

    .se-why-container {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
        align-items: center;
        gap: clamp(32px, 5vw, 72px);
    }

    .se-why-copy .se-products-eyebrow {
        color: #fbbf24;
    }

    .se-why-copy h2 {
        margin: 0 0 16px;
        color: #fff;
        font-size: clamp(28px, 3.2vw, 40px);
        font-weight: 800;
        line-height: 1.2;
    }

    .se-why-copy > p {
        margin: 0 0 30px;
        color: rgba(255, 255, 255, .78);
        font-size: 15px;
        line-height: 1.8;
    }

    .se-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 14px;
    }

    .se-stat {
        padding: 18px;
        border: 1px solid rgba(255, 255, 255, .12);
        border-radius: 12px;
        background: rgba(255, 255, 255, .05);
    }

    .se-stat strong {
        display: block;
        color: #fbbf24;
        font-size: 28px;
        font-weight: 800;
        line-height: 1.1;
    }

    .se-stat span {
        display: block;
        margin-top: 5px;
        color: rgba(255, 255, 255, .75);
        font-size: 13px;
    }

    .se-steps {
        display: grid;
        gap: 14px;
        margin: 0;
        padding: 0;
        list-style: none;
        counter-reset: step;
    }

    .se-step {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        padding: 18px 20px;
        border-radius: 14px;
        background: #fff;
        color: #20362a;
        counter-increment: step;
    }

    .se-step-icon {
        position: relative;
        display: grid;
        flex: 0 0 46px;
        width: 46px;
        height: 46px;
        place-items: center;
        border-radius: 12px;
        background: #eaf5ed;
        color: var(--primary-color, #047857);
        font-size: 19px;
    }

    .se-step-icon::after {
        position: absolute;
        top: -7px;
        right: -7px;
        display: grid;
        width: 20px;
        height: 20px;
        place-items: center;
        border-radius: 50%;
        background: #fbbf24;
        color: #422006;
        content: counter(step);
        font-size: 11px;
        font-weight: 800;
    }

    .se-step h3 {
        margin: 0 0 4px;
        font-size: 15px;
        font-weight: 800;
    }

    .se-step p {
        margin: 0;
        color: #65756d;
        font-size: 13px;
        line-height: 1.6;
    }

    /* Quick contact buttons next to the enquiry form */
    .se-quick-contact {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 30px;
    }

    .se-quick-contact-btn {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        min-height: 48px;
        padding: 12px 20px;
        border-radius: 8px;
        color: #fff !important;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        transition: transform .2s ease, filter .2s ease;
    }

    .se-quick-contact-btn:hover {
        transform: translateY(-2px);
        filter: brightness(.92);
    }

    .se-quick-call {
        background: var(--primary-color, #047857);
    }

    .se-quick-whatsapp {
        background: #25d366;
    }

    /* =========================
       RESPONSIVE BREAKPOINTS
    ========================= */
    @media (max-width: 1050px) {
        .se-products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }
    }

    @media (max-width: 900px) {
        .se-testimonials-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .se-enquiry-section {
            padding: 62px 22px;
        }

        .se-enquiry-container {
            max-width: 700px;
            grid-template-columns: minmax(0, 1fr);
            gap: 34px;
        }

        .se-enquiry-info h2 {
            max-width: 600px;
        }

        .se-enquiry-form-card {
            width: 100%;
        }
    }

    @media (max-width: 768px) {

        .se-products-section {
            padding: 56px 20px 62px;
        }

        .se-products-heading {
            align-items: flex-start;
            flex-direction: column;
            gap: 17px;
            margin-bottom: 25px;
        }

        .se-products-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .se-testimonials-section {
            padding: 56px 20px 62px;
        }

        .se-testimonials-heading {
            margin-bottom: 24px;
        }

        .se-testimonials-heading p {
            font-size: 14px;
        }

        .se-testimonials-actions {
            margin-bottom: 25px;
        }

        .se-testimonial-card {
            padding: 22px;
        }

        .se-enquiry-section {
            padding: 55px 20px;
        }
    }

    @media (max-width: 560px) {

        .se-products-section {
            padding: 44px 14px 50px;
        }

        .se-products-heading h2,
        .se-testimonials-heading h2 {
            font-size: 29px;
        }

        .se-products-heading p {
            font-size: 13px;
        }

        .se-products-view-all {
            max-width: 100%;
            white-space: normal;
        }

        .se-products-grid {
            gap: 12px;
        }

        .se-testimonials-section {
            padding: 44px 14px 50px;
        }

        .se-testimonials-heading p {
            font-size: 13px;
        }

        .se-testimonials-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 14px;
        }

        .se-testimonial-card {
            padding: 21px;
        }

        .se-write-review-btn {
            font-size: 14px;
        }

        .se-enquiry-section {
            padding: 44px 15px;
        }

        .se-enquiry-container {
            gap: 25px;
        }

        .se-enquiry-info h2 {
            font-size: clamp(28px, 7.5vw, 34px);
        }

        .se-enquiry-description {
            font-size: 14px;
        }

        .se-enquiry-points {
            gap: 13px;
            margin-top: 23px;
        }

        .se-enquiry-point {
            align-items: flex-start;
            font-size: 13px;
        }

        .se-enquiry-form-card {
            padding: 23px 17px;
            border-radius: 13px;
        }

        .se-form-heading h3 {
            font-size: 23px;
        }

        .se-enquiry-form {
            gap: 17px;
        }

        .se-toast {
            top: 12px;
            right: 12px;
            width: calc(100vw - 24px);
            padding: 13px 14px 16px;
        }
    }

    @media (max-width: 360px) {

        .se-products-grid {
            grid-template-columns: minmax(0, 1fr);
        }

    }

    @media (max-width: 1050px) {
        .se-trust-container {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .se-trust-item:nth-child(3) {
            border-left: 0;
        }

        .se-trust-item:nth-child(n + 3) {
            border-top: 1px solid #edf1ee;
        }

        .se-deals-track {
            grid-auto-columns: calc((100% - 40px) / 3);
            gap: 20px;
        }
    }

    @media (max-width: 900px) {
        .se-why-container {
            grid-template-columns: minmax(0, 1fr);
        }
    }

    @media (max-width: 768px) {
        .se-categories-section {
            padding: 54px 20px 4px;
        }

        .se-categories-grid {
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 14px;
        }

        .se-category-image {
            height: 120px;
        }

        .se-deals-section,
        .se-why-section {
            padding: 56px 20px;
        }

        .se-deals-track {
            grid-auto-columns: calc((100% - 16px) / 2);
            gap: 16px;
        }

        .se-promo-section {
            padding: 0 20px 56px;
        }
    }

    @media (max-width: 560px) {
        .se-trust-strip {
            padding: 0 14px;
        }

        .se-trust-container {
            grid-template-columns: minmax(0, 1fr);
        }

        .se-trust-item {
            padding: 14px 16px;
        }

        .se-trust-item + .se-trust-item {
            border-top: 1px solid #edf1ee;
            border-left: 0;
        }

        .se-categories-section {
            padding: 44px 14px 0;
        }

        .se-promo-section {
            padding: 0 14px 44px;
        }

        .se-promo-container {
            grid-template-columns: minmax(0, 1fr);
        }

        .se-promo-card {
            min-height: 240px;
            padding: 24px;
        }

        .se-deals-section,
        .se-why-section {
            padding: 44px 14px;
        }

        .se-deals-track {
            grid-auto-columns: 78%;
            gap: 12px;
        }

        .se-carousel-controls {
            display: none;
        }

        .se-stat strong {
            font-size: 24px;
        }

        .se-quick-contact-btn {
            flex: 1 1 100%;
            justify-content: center;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .sh-slide.is-entering,
        .sh-slide.is-active .sh-slide-bg,
        .sh-copy-item.is-active > *,
        .sh-copy-item.is-leaving > *,
        .sh-kicker i,
        .sh-form-glow,
        .sh-form-card,
        .sh-submit::after {
            animation: none !important;
        }

        .sh-copy-item.is-active > * {
            opacity: 1;
        }

        .sh-copy-item.is-leaving {
            visibility: hidden;
        }

        .se-product-card,
        .se-product-image,
        .se-category-card,
        .se-category-image img,
        .se-promo-card::before,
        .se-quick-contact-btn,
        .se-products-view-all,
        .se-write-review-btn,
        .se-enquiry-submit {
            transition: none !important;
        }

        .se-toast,
        .se-toast-progress {
            animation-duration: .01ms !important;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hero carousel: wipe transition, staggered copy, tab progress drives autoplay.
        const hero = document.getElementById('hero-slider');

        if (hero) {
            const slides = Array.from(hero.querySelectorAll('.sh-slide'));
            const items = Array.from(hero.querySelectorAll('.sh-copy-item'));
            const tabs = Array.from(hero.querySelectorAll('.sh-tab'));
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            let current = 0;

            hero.style.setProperty('--hero-interval', (parseInt(hero.dataset.interval, 10) || 6500) + 'ms');

            if (reduceMotion) hero.classList.add('is-static');

            function restartAnimation(element, className) {
                element.classList.remove(className);
                void element.offsetWidth;
                element.classList.add(className);
            }

            function goTo(index, direction) {
                if (slides.length < 2) return;

                const next = (index + slides.length) % slides.length;
                if (next === current) return;

                const previous = current;
                current = next;

                slides.forEach(function(slide, i) {
                    slide.classList.remove('is-prev', 'is-entering', 'is-reverse');
                    slide.classList.toggle('is-active', i === next);
                    slide.setAttribute('aria-hidden', i === next ? 'false' : 'true');
                });

                slides[previous].classList.add('is-prev');
                slides[next].classList.toggle('is-reverse', direction < 0);
                restartAnimation(slides[next], 'is-entering');

                items.forEach(function(item, i) {
                    const active = i === next;
                    item.classList.toggle('is-active', active);
                    item.classList.toggle('is-leaving', i === previous);
                    item.setAttribute('aria-hidden', active ? 'false' : 'true');
                    item.querySelectorAll('a').forEach(function(link) {
                        if (active) link.removeAttribute('tabindex');
                        else link.setAttribute('tabindex', '-1');
                    });
                });

                window.setTimeout(function() {
                    items[previous].classList.remove('is-leaving');
                }, 420);

                tabs.forEach(function(tab, i) {
                    tab.classList.remove('is-active');
                    tab.setAttribute('aria-selected', i === next ? 'true' : 'false');
                });

                if (tabs[next]) restartAnimation(tabs[next], 'is-active');
            }

            // Clean up the outgoing slide once the wipe finishes.
            slides.forEach(function(slide) {
                slide.addEventListener('animationend', function(event) {
                    if (event.target !== slide) return;
                    slide.classList.remove('is-entering');
                    slides.forEach(function(other) {
                        other.classList.remove('is-prev');
                    });
                });
            });

            tabs.forEach(function(tab, i) {
                tab.addEventListener('click', function() {
                    goTo(i, i < current ? -1 : 1);
                });

                // Autoplay: advance when the active tab's progress bar fills.
                tab.querySelector('.sh-tab-progress span').addEventListener('animationend', function() {
                    if (tab.classList.contains('is-active')) goTo(current + 1, 1);
                });

                tab.addEventListener('keydown', function(event) {
                    if (event.key === 'ArrowRight' || event.key === 'ArrowLeft') {
                        const step = event.key === 'ArrowRight' ? 1 : -1;
                        goTo(current + step, step);
                        tabs[current].focus();
                    }
                });
            });

            const prevButton = hero.querySelector('[data-hero-prev]');
            const nextButton = hero.querySelector('[data-hero-next]');
            if (prevButton) prevButton.addEventListener('click', function() { goTo(current - 1, -1); });
            if (nextButton) nextButton.addEventListener('click', function() { goTo(current + 1, 1); });

            // Pause while the visitor hovers, types in the form, or the tab is hidden.
            function setPaused() {
                const paused = hero.matches(':hover') || hero.contains(document.activeElement) || document.hidden;
                hero.classList.toggle('is-paused', paused);
            }

            hero.addEventListener('mouseenter', setPaused);
            hero.addEventListener('mouseleave', setPaused);
            hero.addEventListener('focusin', setPaused);
            hero.addEventListener('focusout', function() { window.setTimeout(setPaused, 0); });
            document.addEventListener('visibilitychange', setPaused);

            // Swipe on touch devices (ignored inside the form).
            let touchStartX = null;

            hero.addEventListener('touchstart', function(event) {
                touchStartX = event.target.closest('.sh-form-card') ? null : event.touches[0].clientX;
            }, { passive: true });

            hero.addEventListener('touchend', function(event) {
                if (touchStartX === null) return;
                const deltaX = event.changedTouches[0].clientX - touchStartX;
                if (Math.abs(deltaX) > 50) goTo(current + (deltaX < 0 ? 1 : -1), deltaX < 0 ? 1 : -1);
                touchStartX = null;
            }, { passive: true });

            // "Get a Free Quote" buttons focus the hero form.
            hero.querySelectorAll('a[href="#hero-quote"]').forEach(function(link) {
                link.addEventListener('click', function(event) {
                    event.preventDefault();
                    const card = document.getElementById('hero-quote');
                    card.scrollIntoView({ behavior: reduceMotion ? 'auto' : 'smooth', block: 'center' });
                    const firstInput = card.querySelector('input:not([type="hidden"])');
                    if (firstInput) firstInput.focus({ preventScroll: true });
                });
            });
        }

        // Hero quick-quote form: client checks, AJAX submit, animated success state.
        const quoteForm = document.getElementById('sh-quote-form');

        if (quoteForm) {
            const submitButton = quoteForm.querySelector('.sh-submit');
            const success = document.querySelector('.sh-success');

            function setError(name, message) {
                const field = quoteForm.querySelector('[name="' + name + '"]').closest('.sh-field');
                const error = field.querySelector('.sh-field-error');
                field.classList.toggle('has-error', Boolean(message));
                error.textContent = message || '';
            }

            function validate() {
                const name = quoteForm.elements.namedItem('name').value.trim();
                const phoneDigits = quoteForm.elements.namedItem('phone').value.replace(/\D+/g, '');
                const errors = {
                    name: name ? '' : 'Please enter your name.',
                    phone: phoneDigits.length >= 10 ? '' : 'Please enter a valid 10-digit phone number.',
                    message: quoteForm.elements.namedItem('message').value ? '' : 'Please choose what you are interested in.'
                };

                Object.keys(errors).forEach(function(key) {
                    setError(key, errors[key]);
                });

                return Object.values(errors).every(function(message) {
                    return message === '';
                });
            }

            quoteForm.querySelectorAll('input, select').forEach(function(input) {
                input.addEventListener('input', function() {
                    setError(input.name, '');
                });
            });

            quoteForm.addEventListener('submit', async function(event) {
                event.preventDefault();
                if (!validate()) return;

                submitButton.classList.add('is-loading');
                submitButton.disabled = true;

                try {
                    const response = await fetch(quoteForm.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new FormData(quoteForm)
                    });

                    if (response.status === 422) {
                        const data = await response.json();
                        Object.entries(data.errors || {}).forEach(function(entry) {
                            if (quoteForm.querySelector('[name="' + entry[0] + '"]')) setError(entry[0], entry[1][0]);
                        });
                        return;
                    }

                    if (!response.ok) throw new Error('Request failed');

                    quoteForm.hidden = true;
                    success.hidden = false;
                } catch (error) {
                    // Fall back to a normal form post if AJAX fails.
                    HTMLFormElement.prototype.submit.call(quoteForm);
                } finally {
                    submitButton.classList.remove('is-loading');
                    submitButton.disabled = false;
                }
            });

            success.querySelector('.sh-success-reset').addEventListener('click', function() {
                quoteForm.reset();
                success.hidden = true;
                quoteForm.hidden = false;
                quoteForm.elements.namedItem('name').focus();
            });
        }

        // Featured products: category tabs, showing at most `data-limit` cards.
        const featuredGrid = document.getElementById('se-featured-grid');
        const productTabs = Array.from(document.querySelectorAll('.se-product-tab'));

        if (featuredGrid) {
            const cards = Array.from(featuredGrid.querySelectorAll('.se-product-card'));
            const limit = parseInt(featuredGrid.dataset.limit, 10) || cards.length;

            function filterProducts(filter) {
                let shown = 0;

                cards.forEach(function(card) {
                    const matches = filter === 'all' || card.dataset.category === filter;
                    const visible = matches && shown < limit;

                    card.classList.toggle('is-hidden', !visible);

                    if (visible) shown++;
                });

                productTabs.forEach(function(tab) {
                    const active = tab.dataset.filter === filter;
                    tab.classList.toggle('is-active', active);
                    tab.setAttribute('aria-selected', active ? 'true' : 'false');
                });
            }

            productTabs.forEach(function(tab) {
                tab.addEventListener('click', function() {
                    filterProducts(tab.dataset.filter);
                });
            });

            filterProducts('all');
        }

        // Top deals carousel arrows.
        const dealsTrack = document.getElementById('se-deals-track');

        if (dealsTrack) {
            const prevDeal = document.querySelector('[data-carousel-prev]');
            const nextDeal = document.querySelector('[data-carousel-next]');

            function updateDealButtons() {
                const maxScroll = dealsTrack.scrollWidth - dealsTrack.clientWidth - 2;

                if (prevDeal) prevDeal.disabled = dealsTrack.scrollLeft <= 2;
                if (nextDeal) nextDeal.disabled = dealsTrack.scrollLeft >= maxScroll;
            }

            function scrollDeals(direction) {
                const card = dealsTrack.querySelector('.se-product-card');
                const step = card ? card.getBoundingClientRect().width + 24 : dealsTrack.clientWidth;

                dealsTrack.scrollBy({
                    left: direction * step,
                    behavior: window.matchMedia('(prefers-reduced-motion: reduce)').matches ? 'auto' : 'smooth'
                });
            }

            if (prevDeal) prevDeal.addEventListener('click', function() { scrollDeals(-1); });
            if (nextDeal) nextDeal.addEventListener('click', function() { scrollDeals(1); });

            dealsTrack.addEventListener('scroll', updateDealButtons, { passive: true });
            window.addEventListener('resize', updateDealButtons);
            updateDealButtons();
        }

        const toast = document.getElementById('enquiryToast');

        if (toast) {
            let dismissed = false;
            let closeTimer = null;

            function closeToast() {
                if (dismissed) return;
                dismissed = true;

                if (closeTimer !== null) {
                    window.clearTimeout(closeTimer);
                }

                toast.classList.add('se-toast-leaving');

                window.setTimeout(function() {
                    toast.remove();
                }, 260);
            }

            const closeButton = toast.querySelector('.se-toast-close');

            if (closeButton) {
                closeButton.addEventListener('click', closeToast);
            }

            closeTimer = window.setTimeout(closeToast, 3500);
        }

        const enquirySection = document.getElementById('enquiry');
        const hasFormErrors = document.querySelector('.se-form-errors');

        if (enquirySection && hasFormErrors) {
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            window.requestAnimationFrame(function() {
                enquirySection.scrollIntoView({
                    behavior: prefersReducedMotion ? 'auto' : 'smooth',
                    block: 'start'
                });
            });
        }
    });
</script>
@endpush