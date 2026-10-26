

<?php $__env->startSection('content'); ?>

<?php
$galleryImages = collect();

if ($product->image) {
$galleryImages->push($product->image);
}

foreach ($product->images as $galleryImage) {
if ($galleryImage->image_path) {
$galleryImages->push($galleryImage->image_path);
}
}

$galleryImages = $galleryImages->filter()->unique()->values();
$currentPrice = (float) ($product->discount_price ?? $product->price);
$hasDiscount = $product->discount_price !== null
&& (float) $product->discount_price < (float) $product->price;
    $whatsappMessage = 'Hello, I am interested in ' . $product->name . '. Please share more details.';
    ?>

    <style>
        .product-detail-page {
            max-width: 1200px;
            margin: 38px auto;
            padding: 0 20px;
            color: #1e293b
        }

        .product-back-link {
            display: inline-block;
            margin-bottom: 24px;
            color: #047857;
            text-decoration: none;
            font-weight: 600
        }

        .product-back-link:hover {
            color: #065f46;
            text-decoration: underline
        }

        .product-detail-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 38px;
            align-items: start
        }

        .product-detail-gallery,
        .product-detail-content {
            min-width: 0
        }

        .product-gallery-main {
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 400px;
            padding: 20px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            box-sizing: border-box
        }

        .product-gallery-main img {
            display: block;
            width: 100%;
            height: 400px;
            object-fit: contain
        }

        .product-gallery-placeholder {
            color: #64748b;
            text-align: center
        }

        .product-slider-arrow {
            position: absolute;
            top: 50%;
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 42px;
            height: 42px;
            border: 1px solid #e2e8f0;
            border-radius: 50%;
            background: #fff;
            color: #064e3b;
            font-size: 25px;
            line-height: 1;
            cursor: pointer;
            transform: translateY(-50%);
            box-shadow: 0 3px 12px #0f172a12
        }

        .product-slider-arrow:hover {
            background: #047857;
            color: #fff
        }

        .product-slider-prev {
            left: 12px
        }

        .product-slider-next {
            right: 12px
        }

        .product-gallery-thumbnails {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 14px
        }

        .product-gallery-thumb {
            width: 76px;
            height: 76px;
            padding: 4px;
            border: 2px solid #e2e8f0;
            border-radius: 9px;
            background: #fff;
            cursor: pointer;
            overflow: hidden
        }

        .product-gallery-thumb.active,
        .product-gallery-thumb:focus-visible {
            border-color: #047857;
            outline: 2px solid #a7f3d0;
            outline-offset: 1px
        }

        .product-gallery-thumb img {
            display: block;
            width: 100%;
            height: 100%;
            object-fit: contain
        }

        .product-detail-category {
            display: inline-block;
            margin-bottom: 12px;
            padding: 6px 12px;
            border-radius: 20px;
            background: #dcfce7;
            color: #166534;
            font-size: 12px;
            font-weight: 600
        }

        .product-detail-title {
            margin: 0 0 16px;
            font-size: clamp(26px, 3vw, 36px);
            line-height: 1.25;
            font-weight: 750;
            overflow-wrap: anywhere
        }

        .product-detail-price {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin: 20px 0
        }

        .product-detail-current-price {
            color: #047857;
            font-size: 30px;
            font-weight: 750
        }

        .product-detail-old-price {
            color: #94a3b8;
            font-size: 18px;
            text-decoration: line-through
        }

        .product-detail-info {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin: 22px 0
        }

        .product-detail-info-item {
            min-width: 0;
            padding: 13px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #f8fafc;
            overflow-wrap: anywhere
        }

        .product-detail-info-item small {
            display: block;
            margin-bottom: 5px;
            color: #64748b;
            font-size: 12px
        }

        .product-detail-info-item strong {
            font-size: 14px
        }

        .product-detail-description {
            margin-top: 24px;
            color: #475569;
            line-height: 1.8;
            overflow-wrap: anywhere
        }

        .product-detail-description h2 {
            margin: 0 0 10px;
            color: #0f172a;
            font-size: 20px;
            font-weight: 700
        }

        .product-description-text {
            margin: 0;
            white-space: pre-line;
            overflow-wrap: anywhere
        }

        .product-description-text.is-collapsed {
            display: -webkit-box;
            -webkit-box-orient: vertical;
            -webkit-line-clamp: 5;
            line-clamp: 5;
            overflow: hidden
        }

        .product-description-toggle {
            display: none;
            margin-top: 8px;
            padding: 0;
            border: 0;
            background: transparent;
            color: #047857;
            font: inherit;
            font-weight: 700;
            cursor: pointer
        }

        .product-description-toggle:hover {
            color: #065f46;
            text-decoration: underline
        }

        .product-detail-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 25px
        }

        .product-detail-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 20px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            font-size: 14px;
            font-weight: 650;
            text-decoration: none;
            cursor: pointer;
            transition: background-color .2s, transform .2s
        }

        .product-detail-btn:hover {
            color: #fff;
            transform: translateY(-1px)
        }

        .product-enquiry-btn {
            background: #047857
        }

        .product-enquiry-btn:hover {
            background: #065f46
        }

        .product-whatsapp-btn {
            background: #16a34a
        }

        .product-whatsapp-btn:hover {
            background: #15803d
        }

        .related-products-section {
            max-width: 1200px;
            margin: 55px auto;
            padding: 0 20px
        }

        .related-products-heading {
            margin-bottom: 24px
        }

        .related-products-heading h2 {
            margin: 0 0 8px;
            color: #0f172a;
            font-size: clamp(24px, 3vw, 30px);
            font-weight: 750
        }

        .related-products-heading p {
            margin: 0;
            color: #64748b
        }

        .related-products-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 20px
        }

        .related-product-card {
            display: flex;
            flex-direction: column;
            min-width: 0;
            overflow: hidden;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            background: #fff;
            color: #1e293b;
            text-decoration: none;
            transition: transform .2s, box-shadow .2s
        }

        .related-product-card:hover {
            color: #1e293b;
            transform: translateY(-4px);
            box-shadow: 0 8px 24px #0f172a12
        }

        .related-product-image,
        .related-product-no-image {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 190px;
            background: #f8fafc
        }

        .related-product-image {
            object-fit: contain;
            padding: 12px;
            box-sizing: border-box
        }

        .related-product-no-image {
            color: #64748b;
            font-size: 13px
        }

        .related-product-content {
            display: flex;
            flex: 1;
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
            padding: 16px
        }

        .related-product-content h3 {
            margin: 0;
            font-size: 16px;
            line-height: 1.5;
            overflow-wrap: anywhere
        }

        .related-product-price {
            color: #047857;
            font-size: 18px;
            font-weight: 750
        }

        .related-product-old-price {
            margin-left: 6px;
            color: #94a3b8;
            font-size: 13px;
            font-weight: 400;
            text-decoration: line-through
        }

        .related-product-view {
            margin-top: auto;
            padding-top: 5px;
            color: #047857;
            font-size: 13px;
            font-weight: 700
        }

        @media(max-width:900px) {
            .related-products-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media(max-width:750px) {
            .product-detail-grid {
                grid-template-columns: 1fr;
                gap: 28px
            }

            .product-gallery-main {
                min-height: 300px
            }

            .product-gallery-main img {
                height: 300px
            }
        }

        @media(max-width:480px) {

            .product-detail-page,
            .related-products-section {
                padding: 0 14px
            }

            .product-gallery-main {
                padding: 12px
            }

            .product-detail-info {
                grid-template-columns: 1fr 1fr
            }

            .product-detail-actions {
                flex-direction: column
            }

            .product-detail-btn {
                width: 100%;
                box-sizing: border-box
            }

            .related-products-grid {
                gap: 10px
            }

            .related-product-image,
            .related-product-no-image {
                height: 135px
            }

            .related-product-content {
                padding: 10px
            }

            .related-product-content h3 {
                font-size: 14px
            }

            .related-product-price {
                font-size: 15px
            }
        }
    </style>

    <div class="product-detail-page">
        <a href="<?php echo e(route('products.index')); ?>" class="product-back-link">&larr; Back to Products</a>

        <div class="product-detail-grid">
            <div class="product-detail-gallery">
                <div class="product-gallery-main">
                    <?php if($galleryImages->isNotEmpty()): ?>
                    <img id="mainProductImage"
                        src="<?php echo e(asset('storage/' . ltrim($galleryImages->first(), '/'))); ?>"
                        alt="<?php echo e($product->name); ?>">

                    <?php if($galleryImages->count() > 1): ?>
                    <button type="button" class="product-slider-arrow product-slider-prev" id="productSliderPrev" aria-label="Previous product image">&lsaquo;</button>
                    <button type="button" class="product-slider-arrow product-slider-next" id="productSliderNext" aria-label="Next product image">&rsaquo;</button>
                    <?php endif; ?>
                    <?php else: ?>
                    <div class="product-gallery-placeholder">No product image available</div>
                    <?php endif; ?>
                </div>

                <?php if($galleryImages->count() > 1): ?>
                <div class="product-gallery-thumbnails">
                    <?php $__currentLoopData = $galleryImages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $imagePath): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <button type="button"
                        class="product-gallery-thumb <?php echo e($loop->first ? 'active' : ''); ?>"
                        data-index="<?php echo e($loop->index); ?>"
                        data-image="<?php echo e(asset('storage/' . ltrim($imagePath, '/'))); ?>"
                        aria-label="View product image <?php echo e($loop->iteration); ?>"
                        aria-pressed="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                        <img src="<?php echo e(asset('storage/' . ltrim($imagePath, '/'))); ?>"
                            alt="<?php echo e($product->name); ?> thumbnail <?php echo e($loop->iteration); ?>"
                            loading="lazy">
                    </button>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="product-detail-content">
                <?php if($product->category): ?>
                <span class="product-detail-category"><?php echo e($product->category->name); ?></span>
                <?php endif; ?>

                <h1 class="product-detail-title"><?php echo e($product->name); ?></h1>

                <div class="product-detail-price">
                    <span class="product-detail-current-price">₹<?php echo e(number_format($currentPrice, 2)); ?></span>
                    <?php if($hasDiscount): ?>
                    <span class="product-detail-old-price">₹<?php echo e(number_format((float) $product->price, 2)); ?></span>
                    <?php endif; ?>
                </div>

                <div class="product-detail-info">
                    <?php if($product->sku): ?>
                    <div class="product-detail-info-item"><small>Product Code / SKU</small><strong><?php echo e($product->sku); ?></strong></div>
                    <?php endif; ?>
                    <?php if($product->brand): ?>
                    <div class="product-detail-info-item"><small>Brand</small><strong><?php echo e($product->brand); ?></strong></div>
                    <?php endif; ?>
                    <?php if($product->size): ?>
                    <div class="product-detail-info-item"><small>Size / Capacity</small><strong><?php echo e($product->size); ?></strong></div>
                    <?php endif; ?>
                    <?php if($product->subcategory): ?>
                    <div class="product-detail-info-item"><small>Subcategory</small><strong><?php echo e($product->subcategory->name); ?></strong></div>
                    <?php endif; ?>
                    <div class="product-detail-info-item">
                        <small>Availability</small>
                        <strong><?php echo e($product->quantity > 0 ? 'In Stock' : 'Out of Stock'); ?></strong>
                    </div>
                </div>

                <?php if($product->description): ?>
                <div class="product-detail-description">
                    <h2>Product Description</h2>
                    <p id="productDescriptionText" class="product-description-text is-collapsed"><?php echo e($product->description); ?></p>
                    <button type="button" id="productDescriptionToggle" class="product-description-toggle"
                        aria-expanded="false" aria-controls="productDescriptionText">Read More</button>
                </div>
                <?php endif; ?>

                <div class="product-detail-actions">
                    <a href="<?php echo e(route('enquiry', ['product' => $product->name])); ?>" class="product-detail-btn product-enquiry-btn">Send Enquiry</a>

                    <?php if(!empty($whatsappNumber)): ?>
                    <a href="https://wa.me/<?php echo e($whatsappNumber); ?>?text=<?php echo e(urlencode($whatsappMessage)); ?>"
                        class="product-detail-btn product-whatsapp-btn"
                        target="_blank" rel="noopener noreferrer">Enquire on WhatsApp</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if($relatedProducts->isNotEmpty()): ?>
    <section class="related-products-section">
        <div class="related-products-heading">
            <h2>Related Products</h2>
            <p>Explore more products from the same category.</p>
        </div>

        <div class="related-products-grid">
            <?php $__currentLoopData = $relatedProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $related): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <a href="<?php echo e(route('products.show', ['slug' => $related->slug])); ?>" class="related-product-card">
                <?php if($related->image): ?>
                <img class="related-product-image"
                    src="<?php echo e(asset('storage/' . ltrim($related->image, '/'))); ?>"
                    alt="<?php echo e($related->name); ?>" loading="lazy">
                <?php else: ?>
                <div class="related-product-no-image">No image available</div>
                <?php endif; ?>

                <div class="related-product-content">
                    <h3><?php echo e($related->name); ?></h3>
                    <div class="related-product-price">
                        ₹<?php echo e(number_format((float) ($related->discount_price ?? $related->price), 2)); ?>

                        <?php if($related->discount_price !== null && (float) $related->discount_price < (float) $related->price): ?>
                            <span class="related-product-old-price">₹<?php echo e(number_format((float) $related->price, 2)); ?></span>
                            <?php endif; ?>
                    </div>
                    <span class="related-product-view">View Details &rarr;</span>
                </div>
            </a>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </section>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const description = document.getElementById('productDescriptionText');
            const descriptionToggle = document.getElementById('productDescriptionToggle');

            if (description && descriptionToggle) {
                requestAnimationFrame(function() {
                    const isLong = description.scrollHeight > description.clientHeight + 2;

                    if (isLong) {
                        descriptionToggle.style.display = 'inline-block';
                    } else {
                        description.classList.remove('is-collapsed');
                        descriptionToggle.style.display = 'none';
                    }
                });

                descriptionToggle.addEventListener('click', function() {
                    const isExpanded = descriptionToggle.getAttribute('aria-expanded') === 'true';
                    description.classList.toggle('is-collapsed', isExpanded);
                    descriptionToggle.setAttribute('aria-expanded', isExpanded ? 'false' : 'true');
                    descriptionToggle.textContent = isExpanded ? 'Read More' : 'Read Less';
                });
            }

            const mainImage = document.getElementById('mainProductImage');
            const thumbnails = Array.from(document.querySelectorAll('.product-gallery-thumb'));

            if (!mainImage || thumbnails.length === 0) {
                return;
            }

            let currentIndex = 0;

            function showImage(index) {
                currentIndex = (index + thumbnails.length) % thumbnails.length;
                const selectedThumbnail = thumbnails[currentIndex];

                if (selectedThumbnail.dataset.image) {
                    mainImage.src = selectedThumbnail.dataset.image;
                }

                thumbnails.forEach(function(thumbnail, thumbnailIndex) {
                    const isActive = thumbnailIndex === currentIndex;
                    thumbnail.classList.toggle('active', isActive);
                    thumbnail.setAttribute('aria-pressed', isActive ? 'true' : 'false');
                });
            }

            thumbnails.forEach(function(thumbnail, index) {
                thumbnail.addEventListener('click', function() {
                    showImage(index);
                });
            });

            const previousButton = document.getElementById('productSliderPrev');
            const nextButton = document.getElementById('productSliderNext');

            if (previousButton) {
                previousButton.addEventListener('click', function() {
                    showImage(currentIndex - 1);
                });
            }

            if (nextButton) {
                nextButton.addEventListener('click', function() {
                    showImage(currentIndex + 1);
                });
            }
        });
    </script>

    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\project\suryaa_enterprises\resources\views/products/show.blade.php ENDPATH**/ ?>