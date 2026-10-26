

<?php $__env->startSection('title', 'Our Products | Suryaa Enterprises'); ?>

<?php $__env->startSection('content'); ?>
<style>
    .products-page {
        --product-green: #17663b;
        --product-dark: #123c29;
        background: #f7f9f7;
        padding: 55px 0 70px;
        min-height: 60vh;
    }

    .products-container {
        width: min(1180px, calc(100% - 36px));
        margin: 0 auto;
    }

    .products-heading {
        text-align: center;
        margin-bottom: 30px;
    }

    .products-heading h1 {
        color: var(--product-dark);
        font-size: clamp(30px, 4vw, 43px);
        font-weight: 800;
        margin: 0 0 10px;
    }

    .products-heading p {
        color: #65746a;
        margin: 0;
        line-height: 1.7;
    }

    .product-filters {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(180px, 240px) auto;
        gap: 12px;
        margin: 30px 0;
        padding: 18px;
        background: #fff;
        border: 1px solid #e3eae4;
        border-radius: 14px;
    }

    .product-filters input,
    .product-filters select {
        width: 100%;
        min-width: 0;
        min-height: 46px;
        padding: 10px 13px;
        border: 1px solid #d9e2da;
        border-radius: 8px;
        background: #fff;
        color: #26382b;
        font: inherit;
    }

    .product-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        min-height: 42px;
        padding: 10px 14px;
        border: 0;
        border-radius: 8px;
        text-decoration: none;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        transition: .2s ease;
    }

    .product-button:hover {
        transform: translateY(-1px);
        filter: brightness(.95);
    }

    .product-search-button {
        background: var(--product-green);
        color: #fff;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 24px;
    }

    .product-card {
        display: flex;
        flex-direction: column;
        min-width: 0;
        overflow: hidden;
        background: #fff;
        border: 1px solid #e5ebe5;
        border-radius: 14px;
        box-shadow: 0 5px 20px rgb(18 60 41 / 5%);
        transition: transform .2s, box-shadow .2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgb(18 60 41 / 10%);
    }

    .product-image-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 230px;
        padding: 18px;
        background: #f0f5f0;
    }

    .product-image-link {
        display: block;
        width: 100%;
        height: 100%;
    }

    .product-image {
        display: block;
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .product-image-placeholder {
        color: #7a897d;
        text-align: center;
    }

    .product-card-content {
        display: flex;
        flex: 1;
        flex-direction: column;
        padding: 20px;
    }

    .product-category {
        color: var(--product-green);
        font-size: 12px;
        font-weight: 800;
        letter-spacing: .6px;
        text-transform: uppercase;
        margin-bottom: 8px;
    }

    .product-name {
        color: #1b3022;
        font-size: 19px;
        line-height: 1.4;
        font-weight: 750;
        margin: 0 0 10px;
        overflow-wrap: anywhere;
    }

    .product-name a {
        color: inherit;
        text-decoration: none;
    }

    .product-name a:hover {
        color: var(--product-green);
    }

    .product-description {
        color: #69756c;
        font-size: 14px;
        line-height: 1.7;
        margin: 0 0 14px;
        overflow-wrap: anywhere;
    }

    .product-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }

    .product-meta span {
        padding: 5px 9px;
        background: #f0f5f0;
        border-radius: 6px;
        color: #526357;
        font-size: 12px;
    }

    .product-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 9px;
        margin-top: auto;
    }

    .product-enquiry {
        background: var(--product-green);
        color: #fff;
    }

    .product-whatsapp {
        background: #128c4a;
        color: #fff;
    }

    .product-actions .product-button {
        padding: 10px 8px;
        font-size: 13px;
    }

    .products-empty {
        padding: 45px 20px;
        background: #fff;
        border: 1px solid #e5ebe5;
        border-radius: 14px;
        text-align: center;
        color: #65746a;
    }

    .products-empty h2 {
        color: var(--product-dark);
        margin-top: 0;
    }

    .products-pagination {
        display: flex;
        justify-content: center;
        margin-top: 35px;
        overflow-x: auto;
    }

    .products-pagination nav>div:first-child {
        margin-bottom: 12px;
    }

    .products-pagination svg {
        width: 18px;
        height: 18px;
    }

    .products-pagination a,
    .products-pagination span[aria-current="page"] span,
    .products-pagination span[aria-disabled="true"] span {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 38px;
        min-height: 38px;
        margin: 2px;
        padding: 6px 11px;
        border: 1px solid #dce5dd;
        border-radius: 7px;
        background: #fff;
        color: var(--product-green);
        text-decoration: none;
    }

    .products-pagination span[aria-current="page"] span {
        background: var(--product-green);
        color: #fff;
        border-color: var(--product-green);
    }

    @media (max-width: 900px) {
        .product-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .product-filters {
            grid-template-columns: 1fr 1fr;
        }

        .product-filters input {
            grid-column: 1 / -1;
        }
    }

    @media (max-width: 560px) {
        .products-page {
            padding: 35px 0 45px;
        }

        .products-container {
            width: calc(100% - 24px);
        }

        .product-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
        }

        .product-image-wrap {
            height: 220px;
        }

        .product-filters {
            grid-template-columns: minmax(0, 1fr);
            padding: 13px;
        }

        .product-filters input {
            grid-column: auto;
        }

        .product-actions {
            grid-template-columns: 1fr 1fr;
        }
    }
</style>

<section class="products-page">
    <div class="products-container">

        <header class="products-heading">
            <h1>Our Products</h1>
            <p>Explore our product range and contact our team for enquiries.</p>
        </header>

        <form action="<?php echo e(route('products.index')); ?>" method="GET" class="product-filters">
            <input
                type="search"
                name="search"
                value="<?php echo e(request('search')); ?>"
                placeholder="Search products, brand or SKU..."
                aria-label="Search products">

            <select name="category" aria-label="Filter by category">
                <option value="">All Categories</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option
                    value="<?php echo e($category->id); ?>"
                    <?php if((string) request('category')===(string) $category->id): echo 'selected'; endif; ?>
                    >
                    <?php echo e($category->name); ?>

                </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit" class="product-button product-search-button">
                Search Products
            </button>
        </form>

        <div id="products-results" aria-live="polite" aria-busy="false">
            <?php if($products->count()): ?>
            <div class="product-grid">
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <article class="product-card">

                    <div class="product-image-wrap">
                        <?php if($product->image): ?>
                        <a
                            href="<?php echo e(route('products.show', ['slug' => $product->slug])); ?>"
                            class="product-image-link"
                            aria-label="View <?php echo e($product->name); ?> details">
                            <img
                                class="product-image"
                                src="<?php echo e(asset('storage/' . ltrim($product->image, '/'))); ?>"
                                alt="<?php echo e($product->name); ?>"
                                loading="lazy">
                        </a>
                        <?php else: ?>
                        <div class="product-image-placeholder">
                            Product image unavailable
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="product-card-content">
                        <div class="product-category">
                            <?php echo e($product->category?->name ?? 'Our Products'); ?>

                        </div>

                        <h2 class="product-name">
                            <a href="<?php echo e(route('products.show', ['slug' => $product->slug])); ?>">
                                <?php echo e($product->name); ?>

                            </a>
                        </h2>

                        <?php if($product->description): ?>
                        <p class="product-description">
                            <?php echo e(\Illuminate\Support\Str::limit(strip_tags($product->description), 125)); ?>

                        </p>
                        <?php endif; ?>

                        <div class="product-meta">
                            <?php if($product->brand): ?>
                            <span>Brand: <?php echo e($product->brand); ?></span>
                            <?php endif; ?>

                            <?php if($product->size): ?>
                            <span>Size: <?php echo e($product->size); ?></span>
                            <?php endif; ?>

                            <?php if($product->sku): ?>
                            <span>SKU: <?php echo e($product->sku); ?></span>
                            <?php endif; ?>
                        </div>

                        <div class="product-actions">
                            <a
                                class="product-button product-enquiry"
                                href="<?php echo e(route('enquiry', ['product' => $product->name])); ?>">
                                Send Enquiry
                            </a>

                            <?php if(!empty($whatsappNumber)): ?>
                            <a
                                class="product-button product-whatsapp"
                                href="https://wa.me/<?php echo e(preg_replace('/\D+/', '', $whatsappNumber)); ?>?text=<?php echo e(rawurlencode('Hello Suryaa Enterprises, I would like to enquire about: ' . $product->name . ' - ' . url('/products/' . $product->slug))); ?>"
                                target="_blank"
                                rel="noopener noreferrer">
                                WhatsApp
                            </a>
                            <?php else: ?>
                            <a class="product-button product-whatsapp" href="<?php echo e(route('contact')); ?>">
                                Contact Us
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </article>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            <div class="products-pagination">
                <?php echo e($products->withQueryString()->links()); ?>

            </div>
            <?php else: ?>
            <div class="products-empty">
                <h2>No products found</h2>
                <p>Try another search term or select a different category.</p>

                <a href="<?php echo e(route('products.index')); ?>" class="product-button product-search-button">
                    View All Products
                </a>
            </div>
            <?php endif; ?>
        </div>

    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('.product-filters');
        const results = document.getElementById('products-results');
        if (!form || !results) return;

        const searchInput = form.querySelector('input[name="search"]');
        const categorySelect = form.querySelector('select[name="category"]');
        let debounceTimer;
        let activeController = null;

        async function loadProducts(url, updateHistory = true) {
            if (activeController) activeController.abort();
            activeController = new AbortController();
            results.setAttribute('aria-busy', 'true');
            results.style.opacity = '0.55';

            try {
                const response = await fetch(url, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    },
                    signal: activeController.signal
                });
                if (!response.ok) throw new Error('Unable to load products.');

                const html = await response.text();
                const parsed = new DOMParser().parseFromString(html, 'text/html');
                const newResults = parsed.getElementById('products-results');
                if (!newResults) throw new Error('Product results were not found in the response.');

                results.innerHTML = newResults.innerHTML;
                if (updateHistory) history.pushState({}, '', url);
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error(error);
                    // Keep the existing results visible if the request fails.
                }
            } finally {
                results.setAttribute('aria-busy', 'false');
                results.style.opacity = '';
            }
        }

        function buildUrl(pageUrl = null) {
            const url = new URL(pageUrl || form.action, window.location.origin);
            const search = searchInput.value.trim();
            const category = categorySelect.value;
            url.searchParams.delete('page');
            if (search) url.searchParams.set('search', search);
            else url.searchParams.delete('search');
            if (category) url.searchParams.set('category', category);
            else url.searchParams.delete('category');
            return url;
        }

        form.addEventListener('submit', function(event) {
            event.preventDefault();
            loadProducts(buildUrl().toString());
        });

        categorySelect.addEventListener('change', function() {
            loadProducts(buildUrl().toString());
        });

        searchInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(function() {
                loadProducts(buildUrl().toString());
            }, 350);
        });

        results.addEventListener('click', function(event) {
            const link = event.target.closest('.products-pagination a');
            if (!link) return;
            const url = new URL(link.href, window.location.origin);
            if (url.origin !== window.location.origin || url.pathname !== window.location.pathname) return;
            event.preventDefault();
            // Keep the current search and category filters on pagination clicks.
            const current = buildUrl(link.href);
            loadProducts(current.toString());
            window.scrollTo({
                top: form.getBoundingClientRect().top + window.scrollY - 100,
                behavior: 'smooth'
            });
        });

        window.addEventListener('popstate', function() {
            const url = new URL(window.location.href);
            searchInput.value = url.searchParams.get('search') || '';
            categorySelect.value = url.searchParams.get('category') || '';
            loadProducts(url.toString(), false);
        });
    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.frontend', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\project\suryaa_enterprises\resources\views/Products/index.blade.php ENDPATH**/ ?>