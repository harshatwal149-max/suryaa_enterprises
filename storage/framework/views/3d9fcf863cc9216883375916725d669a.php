<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>

<?php
$testimonials = \App\Models\Testimonial::query()
->where('is_active', 1)
->latest()
->take(3)
->get();
?>


<section class="home-hero" id="hero-slider" aria-label="Featured banners">
    <div class="home-hero-track">
        <?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div
            class="home-hero-slide <?php echo e($loop->first ? 'is-active' : ''); ?>"
            style="--hero-image: url('<?php echo e(asset('storage/' . $slide->image_path)); ?>');"
            aria-hidden="<?php echo e($loop->first ? 'false' : 'true'); ?>">
            <div class="home-hero-overlay"></div>
            <div class="home-hero-content">
                <?php if($slide->heading): ?>
                <h1><?php echo e($slide->heading); ?></h1>
                <?php endif; ?>

                <?php if($slide->description): ?>
                <p><?php echo e($slide->description); ?></p>
                <?php endif; ?>

                <?php if($slide->button_text && $slide->button_url): ?>
                <a href="<?php echo e($slide->button_url); ?>" class="home-primary-button">
                    <?php echo e($slide->button_text); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="home-hero-slide home-hero-fallback is-active" aria-hidden="false">
            <div class="home-hero-overlay"></div>
            <div class="home-hero-content">
                <h1>Welcome to Suryaa Enterprises</h1>
                <p>Quality Products and Trusted Service for Your Business.</p>
                <a href="<?php echo e(route('enquiry')); ?>" class="home-primary-button">Send an Enquiry</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <?php if($slides->count() > 1): ?>
    <button type="button" class="hero-slider-button hero-slider-prev" aria-label="Previous banner">&#10094;</button>
    <button type="button" class="hero-slider-button hero-slider-next" aria-label="Next banner">&#10095;</button>

    <div class="hero-slider-dots" aria-label="Choose banner">
        <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <button
            type="button"
            class="hero-slider-dot <?php echo e($loop->first ? 'active' : ''); ?>"
            aria-label="Show banner <?php echo e($loop->iteration); ?>"
            aria-current="<?php echo e($loop->first ? 'true' : 'false'); ?>"></button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</section>


<section class="se-products-section" id="featured-products" aria-labelledby="se-products-title">
    <div class="se-products-container">
        <div class="se-products-heading">
            <div class="se-products-heading-copy">
                <span class="se-products-eyebrow">OUR PRODUCTS</span>
                <h2 id="se-products-title">Explore Our Products</h2>
                <p>Discover quality products selected to support your business needs.</p>
            </div>

            <a href="<?php echo e(route('products.index')); ?>" class="se-products-view-all">
                View All Products <span aria-hidden="true">→</span>
            </a>
        </div>

        <div class="se-products-grid">
            <?php $__empty_1 = true; $__currentLoopData = ($products ?? collect()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <article class="se-product-card">
                <a
                    href="<?php echo e(route('products.show', ['slug' => $product->slug])); ?>"
                    class="se-product-image-link"
                    aria-label="View <?php echo e($product->name); ?>">
                    <?php if(!empty($product->image)): ?>
                    <img
                        class="se-product-image"
                        src="<?php echo e(asset('storage/' . ltrim($product->image, '/'))); ?>"
                        alt="<?php echo e($product->name); ?>"
                        loading="lazy">
                    <?php else: ?>
                    <div class="se-product-image-placeholder" aria-hidden="true">
                        <span><?php echo e(\Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($product->name, 0, 1))); ?></span>
                    </div>
                    <?php endif; ?>

                    <span class="se-product-image-badge">Quality Product</span>
                </a>

                <div class="se-product-card-body">
                    <?php if(!empty($product->sku)): ?>
                    <span class="se-product-sku">SKU: <?php echo e($product->sku); ?></span>
                    <?php endif; ?>

                    <h3>
                        <a href="<?php echo e(route('products.show', ['slug' => $product->slug])); ?>">
                            <?php echo e($product->name); ?>

                        </a>
                    </h3>

                    <?php if(!empty($product->description)): ?>
                    <p class="se-product-description">
                        <?php echo e(\Illuminate\Support\Str::limit(strip_tags($product->description), 92)); ?>

                    </p>
                    <?php else: ?>
                    <p class="se-product-description">Reliable quality for your business requirements.</p>
                    <?php endif; ?>

                    <div class="se-product-card-footer">
                        <div class="se-product-price">
                            <?php if($product->discount_price !== null && (float) $product->discount_price > 0 && (float) $product->discount_price < (float) $product->price): ?>
                                <strong>₹<?php echo e(number_format((float) $product->discount_price, 2)); ?></strong>
                                <del>₹<?php echo e(number_format((float) $product->price, 2)); ?></del>
                                <?php elseif($product->price !== null): ?>
                                <strong>₹<?php echo e(number_format((float) $product->price, 2)); ?></strong>
                                <?php else: ?>
                                <strong>Contact for price</strong>
                                <?php endif; ?>
                        </div>

                        <a
                            href="<?php echo e(route('products.show', ['slug' => $product->slug])); ?>"
                            class="se-product-view-link"
                            aria-label="View details for <?php echo e($product->name); ?>">
                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="se-products-empty">
                <div class="se-products-empty-icon" aria-hidden="true">▧</div>
                <h3>Products Coming Soon</h3>
                <p>We’re updating our product range. Please check back soon or send us an enquiry.</p>
                <a href="<?php echo e(route('enquiry')); ?>" class="se-products-empty-button">Make an Enquiry</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>


<?php if(($testimonials ?? collect())->isNotEmpty()): ?>
<section class="se-testimonials-section" id="testimonials" aria-labelledby="se-testimonials-title">
    <div class="se-testimonials-container">
        <div class="se-testimonials-heading">
            <span class="se-testimonials-eyebrow">CUSTOMER FEEDBACK</span>
            <h2 id="se-testimonials-title">What Our Customers Say</h2>
            <p>Real feedback from our customers, selected to highlight their experience with Suryaa Enterprises.</p>
        </div>

        <div class="se-testimonials-actions">
            <a href="<?php echo e(route('testimonials.create')); ?>" class="se-write-review-btn">
                <span aria-hidden="true">✍</span> Write a Review
            </a>
        </div>

        <div class="se-testimonials-grid">
            <?php $__currentLoopData = $testimonials->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php
            $ratingValue = max(0, min(5, (float) $testimonial->rating));
            ?>

            <article class="se-testimonial-card">
                <div class="se-testimonial-stars" role="img" aria-label="Rated <?php echo e(number_format($ratingValue, 1)); ?> out of 5 stars">
                    <?php for($star = 1; $star <= 5; $star++): ?>
                        <?php if($ratingValue>= $star): ?>
                        <span class="se-star se-star-full" aria-hidden="true">★</span>
                        <?php elseif($ratingValue >= ($star - 0.5)): ?>
                        <span class="se-star se-star-half" aria-hidden="true">★</span>
                        <?php else: ?>
                        <span class="se-star se-star-empty" aria-hidden="true">★</span>
                        <?php endif; ?>
                        <?php endfor; ?>
                        <span class="se-testimonial-rating"><?php echo e(number_format($ratingValue, 1)); ?>/5</span>
                </div>

                <blockquote class="se-testimonial-review">“<?php echo e($testimonial->review); ?>”</blockquote>

                <div class="se-testimonial-customer">
                    <span class="se-testimonial-avatar" aria-hidden="true"><?php echo e(\Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr(trim($testimonial->customer_name), 0, 1))); ?></span>
                    <div class="se-testimonial-customer-details">
                        <h3><?php echo e($testimonial->customer_name); ?></h3>
                        <?php if($testimonial->designation): ?>
                        <p><?php echo e($testimonial->designation); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </article>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>


<section class="se-enquiry-section" id="enquiry">
    <div class="se-enquiry-container">
        <div class="se-enquiry-info">
            <span class="se-enquiry-label">LET'S WORK TOGETHER</span>
            <h2>Let's Discuss Your Business Requirements</h2>
            <p class="se-enquiry-description">
                Looking for reliable products or business solutions?
                Send us your enquiry. Our team will get in touch with you
                to understand your requirements.
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
        </div>

        <div class="se-enquiry-form-card">
            <div class="se-form-heading">
                <h3>Send an Enquiry</h3>
                <p>Enter your details and we'll connect with you.</p>
            </div>

            <?php if($errors->any()): ?>
            <div class="se-form-errors" role="alert">
                <strong>Please correct the following errors:</strong>
                <ul>
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            </div>
            <?php endif; ?>

            <form action="<?php echo e(route('enquiry.store')); ?>" method="POST" class="se-enquiry-form">
                <?php echo csrf_field(); ?>

                <div class="se-form-field">
                    <label for="enquiry-name">Full Name <span class="se-required">*</span></label>
                    <input type="text" id="enquiry-name" name="name" value="<?php echo e(old('name')); ?>" placeholder="Enter your full name" maxlength="100" autocomplete="name" required aria-invalid="<?php echo e($errors->has('name') ? 'true' : 'false'); ?>">
                    <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="se-field-error"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="se-form-field">
                    <label for="enquiry-phone">Phone Number <span class="se-required">*</span></label>
                    <input type="tel" id="enquiry-phone" name="phone" value="<?php echo e(old('phone')); ?>" placeholder="Enter your phone number" maxlength="20" autocomplete="tel" required aria-invalid="<?php echo e($errors->has('phone') ? 'true' : 'false'); ?>">
                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="se-field-error"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="se-form-field">
                    <label for="enquiry-email">Email Address <span class="se-optional">(Optional)</span></label>
                    <input type="email" id="enquiry-email" name="email" value="<?php echo e(old('email')); ?>" placeholder="Enter your email address" maxlength="255" autocomplete="email" aria-invalid="<?php echo e($errors->has('email') ? 'true' : 'false'); ?>">
                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="se-field-error"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="se-form-field">
                    <label for="enquiry-message">Your Message <span class="se-required">*</span></label>
                    <textarea id="enquiry-message" name="message" rows="5" maxlength="5000" placeholder="Tell us about your requirements..." required aria-invalid="<?php echo e($errors->has('message') ? 'true' : 'false'); ?>"><?php echo e(old('message')); ?></textarea>
                    <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <small class="se-field-error"><?php echo e($message); ?></small> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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


<?php if(session('success') || $errors->any()): ?>
<div
    id="enquiryToast"
    class="se-toast <?php echo e(session('success') ? 'se-toast-success' : 'se-toast-error'); ?>"
    role="<?php echo e(session('success') ? 'status' : 'alert'); ?>"
    aria-live="polite">
    <span class="se-toast-icon" aria-hidden="true"><?php echo e(session('success') ? '✓' : '!'); ?></span>
    <div class="se-toast-content">
        <strong><?php echo e(session('success') ? 'Thank You!' : 'Check Your Details'); ?></strong>
        <span><?php echo e(session('success') ?? 'Please correct the errors in the form.'); ?></span>
    </div>
    <button type="button" class="se-toast-close" aria-label="Close notification">&times;</button>
    <div class="se-toast-progress"></div>
</div>
<?php endif; ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
    /* =========================
       HERO SLIDER
    ========================= */
    .home-hero,
    .home-hero * {
        box-sizing: border-box;
    }

    .home-hero {
        position: relative;
        isolation: isolate;
        display: block;
        width: 100%;
        max-width: 100%;
        min-width: 0;
        aspect-ratio: 1900 / 714;
        margin: 0 0 36px;
        overflow: hidden;
        background: #fff;
    }

    .home-hero-track {
        position: relative;
        width: 100%;
        height: 100%;
        min-width: 0;
        overflow: hidden;
    }

    .home-hero-slide {
        position: absolute;
        inset: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        height: 100%;
        min-width: 0;
        padding: clamp(20px, 4vw, 64px) clamp(42px, 7vw, 96px);
        overflow: hidden;
        overflow-wrap: anywhere;
        background-color: #fff;
        background-image: var(--hero-image);
        background-repeat: no-repeat;
        background-position: center;
        background-size: contain;
        opacity: 0;
        visibility: hidden;
        transition: opacity .45s ease, visibility .45s ease;
    }

    .home-hero-slide.is-active {
        z-index: 2;
        opacity: 1;
        visibility: visible;
    }

    .home-hero-overlay {
        position: absolute;
        inset: 0;
        z-index: 0;
        pointer-events: none;
        background: transparent;
    }

    .home-hero-content {
        position: relative;
        z-index: 1;
        width: min(100%, 1000px);
        max-width: 100%;
        min-width: 0;
        margin: 0 auto;
        overflow-wrap: anywhere;
        text-align: center;
        color: #fff;
        text-shadow: 0 2px 12px rgba(0, 0, 0, .4);
    }

    .home-hero-content h1 {
        max-width: 100%;
        margin: 0 0 18px;
        color: inherit;
        font-size: clamp(30px, 4.5vw, 60px);
        font-weight: 800;
        line-height: 1.12;
        overflow-wrap: anywhere;
    }

    .home-hero-content p {
        max-width: 820px;
        margin: 0 auto 26px;
        color: inherit;
        font-size: clamp(15px, 2vw, 24px);
        line-height: 1.55;
        overflow-wrap: anywhere;
    }

    .home-primary-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        max-width: 100%;
        min-height: 48px;
        padding: 13px 28px;
        border: 0;
        border-radius: 8px;
        background: #047857;
        color: #fff !important;
        font-size: 15px;
        font-weight: 700;
        line-height: 1.35;
        text-align: center;
        text-decoration: none !important;
        overflow-wrap: anywhere;
        box-shadow: 0 4px 15px rgba(4, 120, 87, .28);
        transition: background .2s ease, transform .2s ease;
    }

    .home-primary-button:hover {
        background: #022c22;
        transform: translateY(-2px);
    }

    .hero-slider-button {
        position: absolute;
        top: 50%;
        z-index: 4;
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        padding: 0;
        transform: translateY(-50%);
        border: 1px solid rgba(255, 255, 255, .6);
        border-radius: 50%;
        background: rgba(2, 44, 34, .65);
        color: #fff;
        font-size: 18px;
        line-height: 1;
        cursor: pointer;
        transition: background .2s ease;
    }

    .hero-slider-button:hover {
        background: #047857;
    }

    .hero-slider-prev {
        left: 18px;
    }

    .hero-slider-next {
        right: 18px;
    }

    .hero-slider-dots {
        position: absolute;
        right: 0;
        bottom: 16px;
        left: 0;
        z-index: 4;
        display: flex;
        justify-content: center;
        gap: 8px;
    }

    .hero-slider-dot {
        width: 9px;
        height: 9px;
        padding: 0;
        border: 0;
        border-radius: 99px;
        background: rgba(255, 255, 255, .65);
        cursor: pointer;
        transition: width .2s ease, background .2s ease;
    }

    .hero-slider-dot.active {
        width: 24px;
        background: #047857;
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
        padding: 18px;
        object-fit: contain;
        transition: transform .35s ease;
    }

    .se-product-card:hover .se-product-image {
        transform: scale(1.04);
    }

    .se-product-image-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        max-width: calc(100% - 24px);
        padding: 6px 8px;
        border: 1px solid rgba(4, 120, 87, .12);
        border-radius: 5px;
        background: rgba(255, 255, 255, .95);
        color: #047857;
        font-size: 10px;
        font-weight: 800;
        letter-spacing: .2px;
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

    .se-product-sku {
        margin-bottom: 8px;
        color: #89958e;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .5px;
        text-transform: uppercase;
        overflow-wrap: anywhere;
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

    .se-product-description {
        display: -webkit-box;
        min-height: 44px;
        margin: 0 0 19px;
        overflow: hidden;
        color: #718078;
        font-size: 12px;
        line-height: 1.8;
        overflow-wrap: anywhere;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .se-product-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #edf1ee;
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

    .se-product-view-link {
        display: grid;
        flex: 0 0 36px;
        width: 36px;
        height: 36px;
        place-items: center;
        border-radius: 50%;
        background: #eaf5ed;
        color: #047857 !important;
        font-size: 19px;
        text-decoration: none !important;
        transition: background .2s ease, color .2s ease;
    }

    .se-product-view-link:hover {
        background: #047857;
        color: #fff !important;
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
       RESPONSIVE BREAKPOINTS
    ========================= */
    @media (max-width: 1050px) {
        .se-products-grid {
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .se-product-image-link {
            height: 215px;
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
        .home-hero {
            width: 100%;
            max-width: 100%;
            aspect-ratio: 1900 / 900;
            margin-bottom: 28px;
        }

        .home-hero-slide {
            padding: 20px 42px 42px;
        }

        .home-hero-content h1 {
            font-size: clamp(25px, 5.5vw, 38px);
            margin-bottom: 12px;
        }

        .home-hero-content p {
            font-size: clamp(13px, 2.8vw, 16px);
            margin-bottom: 16px;
        }

        .home-primary-button {
            min-height: 44px;
            padding: 11px 18px;
            font-size: 14px;
        }

        .hero-slider-button {
            width: 34px;
            height: 34px;
            font-size: 15px;
        }

        .hero-slider-prev {
            left: 8px;
        }

        .hero-slider-next {
            right: 8px;
        }

        .hero-slider-dots {
            bottom: 10px;
        }

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

        .se-product-image-link {
            height: 190px;
        }

        .se-product-card-body {
            padding: 16px;
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
        .home-hero {
            aspect-ratio: auto;
            min-height: 330px;
        }

        .home-hero-track {
            min-height: 330px;
        }

        .home-hero-slide {
            min-height: 330px;
            padding: 40px 38px 48px;
            background-position: center;
        }

        .home-hero-content h1 {
            font-size: clamp(25px, 7vw, 34px);
            line-height: 1.15;
            margin-bottom: 12px;
        }

        .home-hero-content p {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 18px;
        }

        .hero-slider-dots {
            bottom: 12px;
        }

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

        .se-product-image-link {
            height: 150px;
        }

        .se-product-image {
            padding: 10px;
        }

        .se-product-image-badge {
            top: 8px;
            left: 8px;
            padding: 5px 6px;
            font-size: 9px;
        }

        .se-product-card-body {
            padding: 12px;
        }

        .se-product-card-body h3 {
            font-size: 14px;
        }

        .se-product-description {
            min-height: 40px;
            margin-bottom: 13px;
            font-size: 11px;
        }

        .se-product-price strong {
            font-size: 13px;
        }

        .se-product-price del {
            font-size: 10px;
        }

        .se-product-view-link {
            flex-basis: 30px;
            width: 30px;
            height: 30px;
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

        .home-hero,
        .home-hero-track,
        .home-hero-slide {
            min-height: 350px;
        }

        .home-hero-slide {
            padding-right: 32px;
            padding-left: 32px;
        }

        .hero-slider-button {
            width: 29px;
            height: 29px;
            font-size: 13px;
        }

        .hero-slider-prev {
            left: 5px;
        }

        .hero-slider-next {
            right: 5px;
        }

        .se-products-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .se-product-image-link {
            height: 210px;
        }

        .se-product-card-body h3 {
            font-size: 16px;
        }

        .se-product-description {
            font-size: 12px;
        }
    }

    @media (prefers-reduced-motion: reduce) {

        .home-hero-slide,
        .home-primary-button,
        .hero-slider-button,
        .hero-slider-dot,
        .se-product-card,
        .se-product-image,
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
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const hero = document.getElementById('hero-slider');

        if (hero) {
            const slides = Array.from(hero.querySelectorAll('.home-hero-slide'));
            const dots = Array.from(hero.querySelectorAll('.hero-slider-dot'));
            const prev = hero.querySelector('.hero-slider-prev');
            const next = hero.querySelector('.hero-slider-next');
            const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            let current = Math.max(0, slides.findIndex(slide => slide.classList.contains('is-active')));
            let autoSlideTimer = null;

            function showSlide(index) {
                if (!slides.length) return;

                current = (index + slides.length) % slides.length;

                slides.forEach((slide, i) => {
                    const active = i === current;
                    slide.classList.toggle('is-active', active);
                    slide.setAttribute('aria-hidden', active ? 'false' : 'true');
                });

                dots.forEach((dot, i) => {
                    const active = i === current;
                    dot.classList.toggle('active', active);
                    dot.setAttribute('aria-current', active ? 'true' : 'false');
                });
            }

            function stopAutoSlide() {
                if (autoSlideTimer !== null) {
                    window.clearInterval(autoSlideTimer);
                    autoSlideTimer = null;
                }
            }

            function startAutoSlide() {
                stopAutoSlide();

                if (slides.length <= 1 || reduceMotion || document.hidden) return;

                autoSlideTimer = window.setInterval(function() {
                    showSlide(current + 1);
                }, 5000);
            }

            if (prev) {
                prev.addEventListener('click', function() {
                    showSlide(current - 1);
                    startAutoSlide();
                });
            }

            if (next) {
                next.addEventListener('click', function() {
                    showSlide(current + 1);
                    startAutoSlide();
                });
            }

            dots.forEach(function(dot, index) {
                dot.addEventListener('click', function() {
                    showSlide(index);
                    startAutoSlide();
                });
            });

            hero.addEventListener('mouseenter', stopAutoSlide);
            hero.addEventListener('mouseleave', startAutoSlide);

            document.addEventListener('visibilitychange', function() {
                if (document.hidden) {
                    stopAutoSlide();
                } else {
                    startAutoSlide();
                }
            });

            showSlide(current);
            startAutoSlide();
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
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\project\suryaa_enterprises\resources\views/welcome.blade.php ENDPATH**/ ?>