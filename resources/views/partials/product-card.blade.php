@php
$productUrl = route('products.show', ['slug' => $product->slug]);
$showPrice = $product->priceVisible();
$hasDiscount = $product->hasVisibleDiscount();
$discountPercent = $hasDiscount
    ? (int) round((1 - (float) $product->discount_price / (float) $product->price) * 100)
    : 0;
$inStock = (int) $product->quantity > 0;
@endphp

<article class="se-product-card {{ ($hidden ?? false) ? 'is-hidden' : '' }}" data-category="{{ $product->category_id }}">
    <a href="{{ $productUrl }}" class="se-product-image-link" aria-label="View {{ $product->name }}">
        @if (!empty($product->image))
        <img
            class="se-product-image"
            src="{{ \App\Support\ResizedImage::url($product->image, 640) }}"
            srcset="{{ \App\Support\ResizedImage::srcset($product->image, [320, 640]) }}"
            sizes="(max-width: 700px) 50vw, 320px"
            alt="{{ $product->name }}"
            loading="lazy">
        @else
        <div class="se-product-image-placeholder" aria-hidden="true">
            <i class="fa-solid fa-solar-panel"></i>
        </div>
        @endif

        @if ($discountPercent > 0)
        <span class="se-product-discount-badge">{{ $discountPercent }}% OFF</span>
        @endif

        <span class="se-product-stock {{ $inStock ? 'is-in-stock' : 'is-out-of-stock' }}">
            <span class="se-product-stock-dot" aria-hidden="true"></span>
            {{ $inStock ? 'In Stock' : 'Out of Stock' }}
        </span>

        <span class="se-product-quick-view" aria-hidden="true">
            View product <i class="fa-solid fa-arrow-right"></i>
        </span>
    </a>

    <div class="se-product-card-body">
        <div class="se-product-meta">
            <span class="se-product-category">{{ $product->category?->name ?? 'Solar Product' }}</span>
            @if (!empty($product->brand))
            <span class="se-product-brand">{{ $product->brand }}</span>
            @endif
        </div>

        <h3>
            <a href="{{ $productUrl }}">{{ $product->name }}</a>
        </h3>

        @if (!empty($product->size))
        <p class="se-product-size"><i class="fa-solid fa-bolt" aria-hidden="true"></i> {{ $product->size }}</p>
        @endif

        <div class="se-product-footer">
            @if ($showPrice)
            <div class="se-product-price">
                @if ($hasDiscount)
                <strong>₹{{ number_format((float) $product->discount_price) }}</strong>
                <del>₹{{ number_format((float) $product->price) }}</del>
                <span class="se-product-saving">You save ₹{{ number_format((float) $product->price - (float) $product->discount_price) }}</span>
                @else
                <strong>₹{{ number_format((float) $product->price) }}</strong>
                @endif
            </div>
            @endif

            <div class="se-product-actions">
                <a href="{{ $productUrl }}" class="se-product-details-btn">
                    View Details <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                </a>

                @if (!empty($whatsappNumber))
                <a
                    href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Hello Suryaa Enterprises, I would like to enquire about: ' . $product->name . ' - ' . $productUrl) }}"
                    class="se-product-whatsapp-btn"
                    target="_blank"
                    rel="noopener"
                    title="Enquire on WhatsApp"
                    aria-label="Enquire about {{ $product->name }} on WhatsApp">
                    <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
                </a>
                @endif
            </div>
        </div>
    </div>
</article>

@once
@push('styles')
<style>
    .se-product-card {
        position: relative;
        display: flex;
        min-width: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e6ece8;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 1px 2px rgba(16, 40, 30, .04), 0 6px 18px rgba(16, 40, 30, .05);
        transition: transform .3s ease, box-shadow .3s ease, border-color .3s ease;
    }

    .se-product-card:hover {
        transform: translateY(-5px);
        border-color: #cde4d5;
        box-shadow: 0 2px 4px rgba(16, 40, 30, .04), 0 20px 40px rgba(16, 40, 30, .12);
    }

    .se-product-card.is-hidden {
        display: none;
    }

    /* Image */
    .se-product-image-link {
        position: relative;
        display: block;
        aspect-ratio: 4 / 3;
        overflow: hidden;
        background: #f3f7f4;
        text-decoration: none !important;
    }

    .se-product-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .5s ease;
    }

    .se-product-card:hover .se-product-image {
        transform: scale(1.06);
    }

    /* Soft shade at the bottom so the hover label stays readable */
    .se-product-image-link::after {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, transparent 55%, rgba(6, 40, 28, .55));
        content: "";
        opacity: 0;
        transition: opacity .3s ease;
    }

    .se-product-card:hover .se-product-image-link::after {
        opacity: 1;
    }

    .se-product-image-placeholder {
        display: grid;
        width: 100%;
        height: 100%;
        place-items: center;
        background: linear-gradient(145deg, #e8f4ec, #f7faf8);
        color: #9ccbad;
        font-size: 46px;
    }

    .se-product-discount-badge,
    .se-product-stock {
        position: absolute;
        z-index: 1;
        top: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        line-height: 1.2;
        letter-spacing: .2px;
    }

    .se-product-discount-badge {
        left: 12px;
        background: linear-gradient(135deg, #f59e0b, #ea580c);
        color: #fff;
        box-shadow: 0 4px 12px rgba(234, 88, 12, .3);
    }

    .se-product-stock {
        right: 12px;
        background: rgba(255, 255, 255, .92);
        box-shadow: 0 2px 8px rgba(0, 0, 0, .08);
        backdrop-filter: blur(6px);
    }

    .se-product-stock-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: currentColor;
    }

    .se-product-stock.is-in-stock {
        color: #15803d;
    }

    .se-product-stock.is-out-of-stock {
        color: #b91c1c;
    }

    .se-product-quick-view {
        position: absolute;
        z-index: 1;
        right: 14px;
        bottom: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #fff;
        font-size: 12px;
        font-weight: 700;
        opacity: 0;
        transform: translateY(6px);
        transition: opacity .3s ease, transform .3s ease;
    }

    .se-product-card:hover .se-product-quick-view {
        opacity: 1;
        transform: none;
    }

    /* Body */
    .se-product-card-body {
        display: flex;
        flex: 1;
        min-width: 0;
        flex-direction: column;
        padding: 18px 18px 20px;
    }

    .se-product-meta {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        min-width: 0;
        margin-bottom: 8px;
    }

    .se-product-category {
        min-width: 0;
        overflow: hidden;
        color: var(--primary-color, #047857);
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .6px;
        text-overflow: ellipsis;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .se-product-brand {
        flex: 0 0 auto;
        padding: 3px 8px;
        border: 1px solid #e6ece8;
        border-radius: 999px;
        color: #5f6f67;
        font-size: 10.5px;
        font-weight: 600;
    }

    .se-product-card-body h3 {
        display: -webkit-box;
        min-height: 2.8em;
        margin: 0 0 10px;
        overflow: hidden;
        font-size: 16px;
        font-weight: 700;
        letter-spacing: normal;
        line-height: 1.4;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .se-product-card-body h3 a {
        color: #18291f !important;
        text-decoration: none !important;
        transition: color .2s ease;
    }

    .se-product-card-body h3 a:hover {
        color: #047857 !important;
    }

    .se-product-size {
        display: inline-flex;
        align-self: flex-start;
        align-items: center;
        gap: 6px;
        margin: 0 0 14px;
        padding: 4px 10px;
        border-radius: 8px;
        background: #fff8e8;
        color: #7a5a12;
        font-size: 12px;
        font-weight: 600;
    }

    .se-product-size i {
        color: #f59e0b;
    }

    /* Price + actions pinned to the bottom so cards line up in a row */
    .se-product-footer {
        display: grid;
        gap: 14px;
        margin-top: auto;
    }

    .se-product-price {
        display: flex;
        min-width: 0;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 4px 8px;
        padding-top: 12px;
        border-top: 1px dashed #e3e9e5;
    }

    .se-product-price strong {
        color: #18291f;
        font-size: 20px;
        font-weight: 800;
        letter-spacing: -.2px;
        overflow-wrap: anywhere;
    }

    .se-product-price del {
        color: #98a39d;
        font-size: 13px;
    }

    .se-product-saving {
        flex-basis: 100%;
        color: #15803d;
        font-size: 12px;
        font-weight: 600;
    }

    .se-product-actions {
        display: flex;
        gap: 8px;
    }

    .se-product-details-btn {
        display: inline-flex;
        flex: 1;
        align-items: center;
        justify-content: center;
        gap: 8px;
        min-height: 42px;
        padding: 10px 14px;
        border-radius: 10px;
        background: var(--primary-color, #047857);
        color: #fff !important;
        font-size: 13.5px;
        font-weight: 700;
        text-decoration: none !important;
        transition: filter .2s ease, box-shadow .2s ease;
    }

    .se-product-details-btn i {
        font-size: 12px;
        transition: transform .2s ease;
    }

    .se-product-details-btn:hover {
        filter: brightness(.9);
        box-shadow: 0 6px 16px rgba(4, 120, 87, .25);
    }

    .se-product-details-btn:hover i {
        transform: translateX(3px);
    }

    .se-product-whatsapp-btn {
        display: grid;
        flex: 0 0 42px;
        height: 42px;
        place-items: center;
        border: 1px solid #bbf0cf;
        border-radius: 10px;
        background: #ecfdf3;
        color: #16a34a !important;
        font-size: 20px;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease, border-color .2s ease;
    }

    .se-product-whatsapp-btn:hover {
        border-color: #25d366;
        background: #25d366;
        color: #fff !important;
    }

    .se-product-details-btn:focus-visible,
    .se-product-whatsapp-btn:focus-visible,
    .se-product-image-link:focus-visible {
        outline: 3px solid #8be0ac;
        outline-offset: 2px;
    }

    @media (max-width: 768px) {
        .se-product-card-body {
            padding: 15px 15px 16px;
        }

        .se-product-card-body h3 {
            font-size: 15px;
        }

        .se-product-price strong {
            font-size: 18px;
        }
    }

    @media (max-width: 560px) {
        .se-product-card-body {
            padding: 12px;
        }

        .se-product-meta {
            margin-bottom: 6px;
        }

        .se-product-brand {
            display: none;
        }

        .se-product-card-body h3 {
            font-size: 14px;
        }

        .se-product-size {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .se-product-footer {
            gap: 10px;
        }

        .se-product-price strong {
            font-size: 16px;
        }

        .se-product-price del,
        .se-product-saving {
            font-size: 11px;
        }

        .se-product-discount-badge,
        .se-product-stock {
            top: 8px;
            padding: 4px 8px;
            font-size: 10px;
        }

        .se-product-discount-badge {
            left: 8px;
        }

        .se-product-stock {
            right: 8px;
        }

        .se-product-quick-view {
            display: none;
        }

        .se-product-details-btn {
            min-height: 38px;
            padding: 8px 10px;
            font-size: 12px;
        }

        .se-product-whatsapp-btn {
            flex-basis: 38px;
            height: 38px;
            font-size: 18px;
        }
    }

    @media (hover: none) {
        .se-product-card:hover {
            transform: none;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .se-product-card,
        .se-product-image,
        .se-product-quick-view,
        .se-product-details-btn i {
            transition: none !important;
        }
    }
</style>
@endpush
@endonce
