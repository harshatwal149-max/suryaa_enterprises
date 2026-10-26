@php
$productUrl = route('products.show', ['slug' => $product->slug]);
$hasDiscount = $product->discount_price !== null
    && (float) $product->discount_price > 0
    && (float) $product->discount_price < (float) $product->price;
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
            src="{{ asset('storage/' . ltrim($product->image, '/')) }}"
            alt="{{ $product->name }}"
            loading="lazy">
        @else
        <div class="se-product-image-placeholder" aria-hidden="true">
            <span>{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($product->name, 0, 1)) }}</span>
        </div>
        @endif

        @if ($discountPercent > 0)
        <span class="se-product-discount-badge">{{ $discountPercent }}% OFF</span>
        @endif

        <span class="se-product-stock {{ $inStock ? 'is-in-stock' : 'is-out-of-stock' }}">
            {{ $inStock ? 'In Stock' : 'Out of Stock' }}
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

        <div class="se-product-price">
            @if ($hasDiscount)
            <strong>₹{{ number_format((float) $product->discount_price) }}</strong>
            <del>₹{{ number_format((float) $product->price) }}</del>
            <span class="se-product-saving">Save ₹{{ number_format((float) $product->price - (float) $product->discount_price) }}</span>
            @elseif ((float) $product->price > 0)
            <strong>₹{{ number_format((float) $product->price) }}</strong>
            @else
            <strong>Contact for price</strong>
            @endif
        </div>

        <div class="se-product-actions">
            <a href="{{ $productUrl }}" class="se-product-details-btn">View Details</a>

            @if (!empty($whatsappNumber))
            <a
                href="https://wa.me/{{ $whatsappNumber }}?text={{ rawurlencode('Hello Suryaa Enterprises, I would like to enquire about: ' . $product->name . ' - ' . $productUrl) }}"
                class="se-product-whatsapp-btn"
                target="_blank"
                rel="noopener"
                aria-label="Enquire about {{ $product->name }} on WhatsApp">
                <i class="fa-brands fa-whatsapp" aria-hidden="true"></i>
            </a>
            @endif
        </div>
    </div>
</article>

@once
@push('styles')
<style>
    .se-product-card {
        display: flex;
        min-width: 0;
        flex-direction: column;
        overflow: hidden;
        border: 1px solid #e5ece7;
        border-radius: 14px;
        background: #fff;
        box-shadow: 0 8px 24px rgba(23, 45, 37, .05);
        transition: transform .25s ease, box-shadow .25s ease, border-color .25s ease;
    }

    .se-product-card:hover {
        transform: translateY(-4px);
        border-color: #cfe3d6;
        box-shadow: 0 16px 34px rgba(23, 45, 37, .1);
    }

    .se-product-image-link {
        position: relative;
        display: block;
        height: 230px;
        overflow: hidden;
        background: #f5f8f6;
        text-decoration: none !important;
    }

    .se-product-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform .35s ease;
    }

    .se-product-card:hover .se-product-image {
        transform: scale(1.04);
    }

    .se-product-image-placeholder {
        display: grid;
        width: 100%;
        height: 100%;
        place-items: center;
        background: linear-gradient(145deg, #edf6ef, #f8faf8);
        color: #047857;
    }

    .se-product-image-placeholder span {
        display: grid;
        width: 78px;
        height: 78px;
        place-items: center;
        border: 1px solid #d1e8d8;
        border-radius: 50%;
        background: #fff;
        font-size: 32px;
        font-weight: 800;
    }

    .se-product-card-body {
        display: flex;
        flex: 1;
        min-width: 0;
        flex-direction: column;
        padding: 20px;
    }

    .se-product-card-body h3 {
        margin: 0 0 10px;
        font-size: 17px;
        font-weight: 800;
        line-height: 1.45;
        overflow-wrap: anywhere;
    }

    .se-product-card-body h3 a {
        color: #20362a !important;
        text-decoration: none !important;
    }

    .se-product-card-body h3 a:hover {
        color: #047857 !important;
    }

    .se-product-price {
        display: flex;
        min-width: 0;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 6px;
    }

    .se-product-price strong {
        color: #047857;
        font-size: 16px;
        font-weight: 800;
        overflow-wrap: anywhere;
    }

    .se-product-price del {
        color: #9aa49e;
        font-size: 11px;
    }

    .se-product-card.is-hidden {
        display: none;
    }

    .se-product-discount-badge,
        .se-product-stock {
        position: absolute;
        top: 12px;
        padding: 5px 8px;
        border-radius: 6px;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .3px;
    }

    .se-product-discount-badge {
        left: 12px;
        background: #dc2626;
        color: #fff;
    }

    .se-product-stock {
        right: 12px;
        background: rgba(255, 255, 255, .95);
    }

    .se-product-stock.is-in-stock {
        color: #15803d;
    }

    .se-product-stock.is-out-of-stock {
        color: #b91c1c;
    }

    .se-product-meta {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        justify-content: space-between;
        gap: 6px;
        margin-bottom: 8px;
    }

    .se-product-category {
        color: var(--primary-color, #047857);
        font-size: 11px;
        font-weight: 800;
        letter-spacing: .4px;
        text-transform: uppercase;
    }

    .se-product-brand {
        padding: 2px 7px;
        border-radius: 4px;
        background: #f3f5f4;
        color: #65756d;
        font-size: 10px;
        font-weight: 700;
    }

    .se-product-size {
        margin: 0 0 12px;
        color: #65756d;
        font-size: 12px;
    }

    .se-product-size i {
        color: #f59e0b;
    }

    .se-product-price {
        margin: auto 0 14px;
    }

    .se-product-saving {
        flex-basis: 100%;
        color: #15803d;
        font-size: 11px;
        font-weight: 700;
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
        min-height: 40px;
        padding: 9px 12px;
        border-radius: 8px;
        background: var(--primary-color, #047857);
        color: #fff !important;
        font-size: 13px;
        font-weight: 700;
        text-decoration: none !important;
        transition: filter .2s ease;
    }

    .se-product-details-btn:hover {
        filter: brightness(.85);
    }

    .se-product-whatsapp-btn {
        display: grid;
        flex: 0 0 40px;
        height: 40px;
        place-items: center;
        border-radius: 8px;
        background: #25d366;
        color: #fff !important;
        font-size: 20px;
        text-decoration: none !important;
        transition: background .2s ease;
    }

    .se-product-whatsapp-btn:hover {
        background: #1da851;
    }

    @media (max-width: 1050px) {
        .se-product-image-link {
            height: 215px;
        }
    }

    @media (max-width: 768px) {
        .se-product-image-link {
            height: 190px;
        }

        .se-product-card-body {
            padding: 16px;
        }
    }

    @media (max-width: 560px) {
        .se-product-image-link {
            height: 150px;
        }

        .se-product-card-body {
            padding: 12px;
        }

        .se-product-card-body h3 {
            font-size: 14px;
        }

        .se-product-price strong {
            font-size: 13px;
        }

        .se-product-price del {
            font-size: 10px;
        }

        .se-product-discount-badge,
            .se-product-stock {
            top: 8px;
            padding: 4px 6px;
            font-size: 9px;
        }

        .se-product-discount-badge {
            left: 8px;
        }

        .se-product-stock {
            right: 8px;
        }

        .se-product-details-btn {
            font-size: 12px;
        }

        .se-product-whatsapp-btn {
            flex-basis: 36px;
            height: 36px;
        }
    }

    @media (max-width: 360px) {
        .se-product-image-link {
            height: 210px;
        }

        .se-product-card-body h3 {
            font-size: 16px;
        }
    }
</style>
@endpush
@endonce
