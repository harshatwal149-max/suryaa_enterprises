@extends('admin.layout')

@section('page-title', 'Products')
@section('page-heading', 'Products')
@section('breadcrumb', 'Admin / Products')

@section('content')

<style>
    .products-wrap {
        display: grid;
        gap: 20px;
    }

    .products-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .products-muted {
        color: #64748b;
        font-size: 13px;
    }

    .products-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 10px 15px;
        border-radius: 9px;
        border: 0;
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        background: #047857;
        color: #fff;
        transition: background .2s ease, transform .2s ease;
        white-space: nowrap;
    }

    .products-btn:hover {
        background: #065f46;
        color: #fff;
        transform: translateY(-1px);
    }

    .products-btn.secondary {
        background: #e2e8f0;
        color: #334155;
    }

    .products-btn.secondary:hover {
        background: #cbd5e1;
        color: #0f172a;
    }

    .products-btn.danger {
        background: #fee2e2;
        color: #b91c1c;
    }

    .products-btn.danger:hover {
        background: #fecaca;
        color: #991b1b;
    }

    .products-table-wrap {
        overflow-x: auto;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
    }

    .products-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 850px;
    }

    .products-table th {
        text-align: left;
        padding: 14px;
        background: #f8fafc;
        color: #475569;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .05em;
        white-space: nowrap;
    }

    .products-table td {
        padding: 14px;
        border-top: 1px solid #f1f5f9;
        font-size: 13px;
        vertical-align: middle;
    }

    .products-table tbody tr:hover {
        background: #fafafa;
    }

    .product-thumb {
        width: 54px;
        height: 54px;
        object-fit: cover;
        border-radius: 8px;
        background: #f1f5f9;
    }

    .product-status-btn {
        border: 0;
        cursor: pointer;
        display: inline-block;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        transition: all .2s ease;
    }

    .product-status-btn.active {
        background: #d1fae5;
        color: #065f46;
    }

    .product-status-btn.inactive {
        background: #fee2e2;
        color: #991b1b;
    }

    .product-status-btn:hover {
        opacity: 0.85;
        transform: translateY(-1px);
    }

    .product-actions {
        display: flex;
        gap: 7px;
        align-items: center;
        flex-wrap: wrap;
    }

    .product-actions form {
        margin: 0;
    }

    .products-alert {
        padding: 12px 15px;
        border-radius: 9px;
        background: #d1fae5;
        color: #065f46;
        font-size: 14px;
    }

    .products-pagination {
        padding: 16px;
    }

    .products-empty {
        text-align: center;
        padding: 45px 20px !important;
    }

    .products-btn.view {
        background: #e0f2fe;
        color: #0369a1;
    }

    .products-btn.view:hover {
        background: #bae6fd;
        color: #075985;
    }

    .products-btn:disabled {
        opacity: .5;
        cursor: not-allowed;
        transform: none;
    }

    .products-check {
        width: 16px;
        height: 16px;
        cursor: pointer;
        accent-color: #047857;
    }

    .products-table tbody tr.is-selected {
        background: #f0fdf4;
    }

    .products-bulk {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
        padding: 12px 14px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 13px;
    }

    .products-bulk select {
        padding: 9px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        font-size: 13px;
        color: #334155;
    }

    .products-bulk-count {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .products-toolbar-actions {
        display: flex;
        align-items: center;
        gap: 14px;
        flex-wrap: wrap;
    }

    .price-switch {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        color: #334155;
        user-select: none;
    }

    .price-switch input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .price-switch-track {
        position: relative;
        width: 40px;
        height: 22px;
        border-radius: 22px;
        background: #cbd5e1;
        transition: background .2s ease;
    }

    .price-switch-track::after {
        position: absolute;
        top: 3px;
        left: 3px;
        width: 16px;
        height: 16px;
        border-radius: 50%;
        background: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .2);
        content: "";
        transition: transform .2s ease;
    }

    .price-switch input:checked + .price-switch-track {
        background: #047857;
    }

    .price-switch input:checked + .price-switch-track::after {
        transform: translateX(18px);
    }

    .price-switch input:focus-visible + .price-switch-track {
        outline: 2px solid #047857;
        outline-offset: 2px;
    }

    .price-hidden-tag {
        display: inline-block;
        margin-top: 4px;
        padding: 2px 8px;
        border-radius: 20px;
        background: #fef3c7;
        color: #92400e;
        font-size: 10px;
        font-weight: 700;
    }

    @media (max-width: 640px) {
        .products-toolbar {
            align-items: flex-start;
        }

        .products-table th,
        .products-table td {
            padding: 11px;
        }
    }
</style>

<div class="products-wrap">

    {{-- Fallback alerts --}}
    @if (session('success'))
    <div class="products-alert">
        {{ session('success') }}
    </div>
    @endif

    @if (session('error'))
    <div class="products-alert"
        style="background:#fee2e2;color:#991b1b;">
        {{ session('error') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="products-alert"
        style="background:#fee2e2;color:#991b1b;">
        Please check the form errors and try again.
        <ul style="margin:8px 0 0;padding-left:20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Page heading --}}
    <div class="products-toolbar">
        <div>
            <h2 style="font-size:20px;font-weight:700;margin:0 0 5px;">
                All Products
            </h2>

            <p class="products-muted" style="margin:0;">
                Manage your solar products, prices, stock and images.
            </p>
        </div>

        <div class="products-toolbar-actions">
            <label class="price-switch" title="Show or hide prices for every product on the website">
                <input
                    type="checkbox"
                    id="price-visibility-toggle"
                    data-url="{{ route('admin.products.price-visibility') }}"
                    @checked($pricesVisible)>
                <span class="price-switch-track" aria-hidden="true"></span>
                <span class="price-switch-label">Show prices on website</span>
            </label>

            <a href="{{ route('admin.products.create') }}"
                class="products-btn">
                + Add Product
            </a>
        </div>
    </div>

    @unless ($pricesVisible)
    <div class="products-alert" id="prices-off-notice" style="background:#fef3c7;color:#92400e;">
        Prices are hidden for <strong>all products</strong> on the website. The per-product price setting applies only when this switch is on.
    </div>
    @endunless

    {{-- Bulk actions --}}
    @if ($products->isNotEmpty())
    <div class="products-bulk" id="products-bulk" data-url="{{ route('admin.products.bulk') }}">
        <span class="products-bulk-count" id="bulk-count">0 selected</span>

        <select id="bulk-action" aria-label="Bulk action">
            <option value="">Bulk action…</option>
            <option value="activate">Mark as Active</option>
            <option value="deactivate">Mark as Inactive</option>
            <option value="show_price">Show price on website</option>
            <option value="hide_price">Hide price on website</option>
            <option value="category">Move to category…</option>
            <option value="delete">Delete selected</option>
        </select>

        <select id="bulk-category" aria-label="Category" hidden>
            <option value="">Choose category</option>
            @foreach ($categories as $category)
            <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>

        <button type="button" class="products-btn" id="bulk-apply" disabled>Apply</button>
    </div>
    @endif

    {{-- Products table --}}
    <div class="products-table-wrap">
        <table class="products-table">
            <thead>
                <tr>
                    <th style="width:36px;">
                        <input type="checkbox" class="products-check" id="bulk-select-all" aria-label="Select all products on this page">
                    </th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>SKU</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($products as $product)
                <tr id="product-row-{{ $product->id }}">
                    <td>
                        <input type="checkbox" class="products-check bulk-item" value="{{ $product->id }}" aria-label="Select {{ $product->name }}">
                    </td>

                    {{-- Product image --}}
                    <td>
                        @if ($product->image)
                        <img
                            class="product-thumb"
                            src="{{ asset('storage/' . ltrim($product->image, '/')) }}"
                            alt="{{ $product->name }}"
                            loading="lazy"
                            onerror="this.style.display='none';this.nextElementSibling.style.display='grid';">
                        <div class="product-thumb"
                            style="display:none;place-items:center;">
                            ◇
                        </div>
                        @else
                        <div class="product-thumb"
                            style="display:grid;place-items:center;">
                            ◇
                        </div>
                        @endif
                    </td>

                    {{-- Product name and brand --}}
                    <td>
                        <strong>{{ $product->name }}</strong>

                        @if ($product->brand)
                        <div class="products-muted">
                            {{ $product->brand }}
                        </div>
                        @endif
                    </td>

                    {{-- Category and subcategory --}}
                    <td>
                        {{ $product->category?->name ?? '—' }}

                        @if ($product->subcategory)
                        <div class="products-muted">
                            {{ $product->subcategory->name }}
                        </div>
                        @endif
                    </td>

                    {{-- SKU --}}
                    <td>{{ $product->sku ?: '—' }}</td>

                    {{-- Price --}}
                    <td>
                        @if ($product->discount_price !== null)
                        <strong>
                            ₹{{ number_format((float) $product->discount_price, 2) }}
                        </strong>

                        <div class="products-muted">
                            <s>
                                ₹{{ number_format((float) $product->price, 2) }}
                            </s>
                        </div>
                        @else
                        ₹{{ number_format((float) $product->price, 2) }}
                        @endif

                        @unless ($product->show_price)
                        <div><span class="price-hidden-tag">Hidden on site</span></div>
                        @endunless
                    </td>

                    {{-- Stock --}}
                    <td>{{ $product->quantity }}</td>

                    {{-- Status Toggle Button --}}
                    <td>
                        <button
                            type="button"
                            class="product-status-btn {{ $product->is_active ? 'active' : 'inactive' }}"
                            data-url="{{ route('admin.products.toggle-status', $product) }}"
                            title="Click to toggle status">
                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                        </button>
                    </td>

                    {{-- Actions --}}
                    <td>
                        <div class="product-actions">
                            <a
                                class="products-btn view"
                                href="{{ route('products.show', ['slug' => $product->slug]) }}"
                                target="_blank"
                                rel="noopener"
                                title="Open on website">
                                View
                            </a>

                            <a
                                class="products-btn secondary"
                                href="{{ route('admin.products.edit', $product) }}">
                                Edit
                            </a>

                            <form
                                method="POST"
                                action="{{ route('admin.products.destroy', $product) }}"
                                class="delete-product-form"
                                data-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="products-btn danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="products-empty">
                        <strong>No products found</strong>

                        <p class="products-muted">
                            Add your first product to get started.
                        </p>

                        <a href="{{ route('admin.products.create') }}"
                            class="products-btn">
                            + Add First Product
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="products-pagination">
            {{ $products->links() }}
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. AJAX Status Toggle for Products
        document.querySelectorAll('.product-status-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var btn = this;
                var toggleUrl = btn.dataset.url;
                btn.disabled = true;

                fetch(toggleUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (data.success) {
                        if (data.is_active) {
                            btn.classList.remove('inactive');
                            btn.classList.add('active');
                            btn.textContent = 'Active';
                        } else {
                            btn.classList.remove('active');
                            btn.classList.add('inactive');
                            btn.textContent = 'Inactive';
                        }

                        if (window.Swal) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message || 'Status updated!',
                                showConfirmButton: false,
                                timer: 1800
                            });
                        }
                    }
                })
                .catch(function() {
                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: 'Failed to update status',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    }
                })
                .finally(function() {
                    btn.disabled = false;
                });
            });
        });

        // Site-wide price visibility switch
        var priceToggle = document.getElementById('price-visibility-toggle');
        if (priceToggle) {
            priceToggle.addEventListener('change', function() {
                priceToggle.disabled = true;

                fetch(priceToggle.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(function(res) { return res.json(); })
                .then(function(data) {
                    if (!data.success) throw new Error(data.message);
                    // Reload so the notice and price tags match the new setting.
                    if (window.Swal) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: data.message, showConfirmButton: false, timer: 1500 });
                    }
                    setTimeout(function() { window.location.reload(); }, 900);
                })
                .catch(function() {
                    priceToggle.checked = !priceToggle.checked;
                    priceToggle.disabled = false;
                    if (window.Swal) {
                        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: 'Could not change price visibility', showConfirmButton: false, timer: 2000 });
                    }
                });
            });
        }

        // 2. Bulk actions on the selected rows
        var bulkBar = document.getElementById('products-bulk');
        if (bulkBar) {
            var selectAll = document.getElementById('bulk-select-all');
            var items = Array.prototype.slice.call(document.querySelectorAll('.bulk-item'));
            var actionSelect = document.getElementById('bulk-action');
            var categorySelect = document.getElementById('bulk-category');
            var applyBtn = document.getElementById('bulk-apply');
            var countLabel = document.getElementById('bulk-count');

            var selectedIds = function() {
                return items.filter(function(cb) { return cb.checked; }).map(function(cb) { return cb.value; });
            };

            var refresh = function() {
                var ids = selectedIds();
                countLabel.textContent = ids.length + ' selected';
                selectAll.checked = ids.length > 0 && ids.length === items.length;
                selectAll.indeterminate = ids.length > 0 && ids.length < items.length;
                items.forEach(function(cb) {
                    cb.closest('tr').classList.toggle('is-selected', cb.checked);
                });
                categorySelect.hidden = actionSelect.value !== 'category';
                applyBtn.disabled = ids.length === 0 || !actionSelect.value
                    || (actionSelect.value === 'category' && !categorySelect.value);
            };

            selectAll.addEventListener('change', function() {
                items.forEach(function(cb) { cb.checked = selectAll.checked; });
                refresh();
            });
            items.forEach(function(cb) { cb.addEventListener('change', refresh); });
            actionSelect.addEventListener('change', refresh);
            categorySelect.addEventListener('change', refresh);

            var notify = function(icon, title) {
                if (window.Swal) {
                    Swal.fire({ toast: true, position: 'top-end', icon: icon, title: title, showConfirmButton: false, timer: 2000 });
                } else {
                    alert(title);
                }
            };

            var runBulk = function() {
                var body = new URLSearchParams();
                selectedIds().forEach(function(id) { body.append('ids[]', id); });
                body.append('action', actionSelect.value);
                if (actionSelect.value === 'category') body.append('category_id', categorySelect.value);

                applyBtn.disabled = true;

                fetch(bulkBar.dataset.url, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    body: body
                })
                .then(function(res) {
                    return res.json().then(function(data) {
                        if (!res.ok || !data.success) throw new Error(data.message || 'Bulk action failed');
                        return data;
                    });
                })
                .then(function(data) {
                    notify('success', data.message);
                    // Reload so prices, statuses and pagination reflect the change.
                    setTimeout(function() { window.location.reload(); }, 900);
                })
                .catch(function(err) {
                    notify('error', err.message || 'Something went wrong.');
                    refresh();
                });
            };

            applyBtn.addEventListener('click', function() {
                var count = selectedIds().length;

                if (actionSelect.value !== 'delete') {
                    runBulk();
                    return;
                }

                var question = 'Delete ' + count + ' product(s)? This action cannot be undone.';

                if (typeof window.Swal === 'undefined') {
                    if (confirm(question)) runBulk();
                    return;
                }

                Swal.fire({
                    title: 'Delete selected products?',
                    text: question,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete them',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function(result) {
                    if (result.isConfirmed) runBulk();
                });
            });

            refresh();
        }

        // 3. AJAX Product Deletion with SweetAlert2
        document.querySelectorAll('.delete-product-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                var productName = form.dataset.productName || 'this product';
                var productId = form.dataset.id;
                var actionUrl = form.action;

                if (typeof window.Swal === 'undefined') {
                    if (confirm('Are you sure you want to delete "' + productName + '"?')) {
                        form.submit();
                    }
                    return;
                }

                window.Swal.fire({
                    title: 'Delete product?',
                    text: 'Are you sure you want to delete "' + productName + '"? This action cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true,
                    focusCancel: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var row = document.getElementById('product-row-' + productId);

                        fetch(actionUrl, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams(new FormData(form))
                        })
                        .then(function(res) { return res.json(); })
                        .then(function(data) {
                            if (data.success) {
                                if (row) {
                                    row.style.transition = 'opacity 0.35s ease';
                                    row.style.opacity = '0';
                                    setTimeout(function() { row.remove(); }, 350);
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: data.message || 'Product deleted successfully.',
                                    timer: 1600,
                                    showConfirmButton: false
                                });
                            } else {
                                throw new Error(data.message || 'Failed to delete');
                            }
                        })
                        .catch(function(err) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: err.message || 'Something went wrong.'
                            });
                        });
                    }
                });
            });
        });
    });
</script>

@endsection