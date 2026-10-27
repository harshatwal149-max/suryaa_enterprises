@extends('admin.layout')

@section('page-title', 'Add Product')
@section('page-heading', 'Add Product')
@section('breadcrumb', 'Admin / Products / Add')

@section('content')

<style>
    .product-form-card {
        background:#fff;
        border:1px solid #e2e8f0;
        border-radius:14px;
        padding:24px;
    }

    .product-form-grid {
        display:grid;
        grid-template-columns:repeat(2,minmax(0,1fr));
        gap:20px;
    }

    .product-field {
        display:flex;
        flex-direction:column;
        gap:7px;
    }

    .product-field.full {
        grid-column:1/-1;
    }

    .product-field label {
        font-size:13px;
        font-weight:600;
        color:#334155;
    }

    .product-field input,
    .product-field select,
    .product-field textarea {
        width:100%;
        min-width:0;
        padding:11px 12px;
        border:1px solid #cbd5e1;
        border-radius:8px;
        background:#fff;
        color:#0f172a;
        font:inherit;
        font-size:14px;
        box-sizing:border-box;
    }

    .product-field textarea {
        min-height:120px;
        resize:vertical;
    }

    .product-field input:focus,
    .product-field select:focus,
    .product-field textarea:focus {
        outline:2px solid #a7f3d0;
        border-color:#047857;
    }

    .product-error {
        color:#b91c1c;
        font-size:12px;
    }

    .product-help {
        color:#64748b;
        font-size:12px;
        line-height:1.5;
    }

    .product-form-actions {
        display:flex;
        gap:10px;
        margin-top:24px;
        flex-wrap:wrap;
    }

    .product-submit,
    .product-cancel {
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:11px 17px;
        border:0;
        border-radius:8px;
        font-weight:600;
        font-size:13px;
        text-decoration:none;
        cursor:pointer;
    }

    .product-submit {
        background:#047857;
        color:white;
    }

    .product-cancel {
        background:#e2e8f0;
        color:#334155;
    }

    .product-checkbox {
        display:flex;
        gap:9px;
        align-items:center;
        font-size:14px;
    }

    .product-checkbox input {
        width:auto;
    }

    @media(max-width:650px) {
        .product-form-grid {
            grid-template-columns:1fr;
        }

        .product-field.full {
            grid-column:auto;
        }

        .product-form-card {
            padding:16px;
        }
    }
</style>

<div class="product-form-card">

    <h2 style="font-size:20px;font-weight:700;margin:0 0 6px">
        Add New Product
    </h2>

    <p style="color:#64748b;font-size:13px;margin:0 0 24px">
        Enter the product details and upload product images below.
    </p>

    @if($errors->any())
        <div style="padding:13px;background:#fee2e2;color:#991b1b;border-radius:8px;margin-bottom:20px">
            <strong>Please fix the following errors:</strong>

            <ul style="padding-left:20px;margin:8px 0 0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.products.store') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf

        <div class="product-form-grid">

            {{-- Product Name --}}
            <div class="product-field">
                <label for="name">Product Name *</label>

                <input
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    required
                    maxlength="255"
                    placeholder="e.g. Solar Panel 550W"
                >

                @error('name')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- SKU --}}
            <div class="product-field">
                <label for="sku">SKU / Product Code</label>

                <input
                    id="sku"
                    name="sku"
                    value="{{ old('sku') }}"
                    maxlength="255"
                    placeholder="e.g. SOL-550"
                >

                @error('sku')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Category --}}
            <div class="product-field">
                <label for="category_id">Category *</label>

                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>

                    @foreach($categories as $category)
                        <option
                            value="{{ $category->id }}"
                            @selected(old('category_id') == $category->id)
                        >
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>

                @error('category_id')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Subcategory --}}
            <div class="product-field">
                <label for="subcategory_id">Subcategory</label>

                <select id="subcategory_id" name="subcategory_id">
                    <option value="">No subcategory</option>

                    @foreach($subcategories as $subcategory)
                        <option
                            value="{{ $subcategory->id }}"
                            data-category="{{ $subcategory->category_id }}"
                            @selected(old('subcategory_id') == $subcategory->id)
                        >
                            {{ $subcategory->name }}
                        </option>
                    @endforeach
                </select>

                @error('subcategory_id')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Price --}}
            <div class="product-field">
                <label for="price">Price (₹) *</label>

                <input
                    id="price"
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    value="{{ old('price') }}"
                    required
                >

                @error('price')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Discount --}}
            <div class="product-field">
                <label for="discount_price">Discount Price (₹)</label>

                <input
                    id="discount_price"
                    type="number"
                    name="discount_price"
                    min="0"
                    step="0.01"
                    value="{{ old('discount_price') }}"
                >

                @error('discount_price')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Quantity --}}
            <div class="product-field">
                <label for="quantity">Stock Quantity *</label>

                <input
                    id="quantity"
                    type="number"
                    name="quantity"
                    min="0"
                    value="{{ old('quantity', 0) }}"
                    required
                >

                @error('quantity')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Size --}}
            <div class="product-field">
                <label for="size">Size / Capacity</label>

                <input
                    id="size"
                    name="size"
                    value="{{ old('size') }}"
                    maxlength="255"
                    placeholder="e.g. 550W"
                >

                @error('size')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Brand --}}
            <div class="product-field">
                <label for="brand">Brand</label>

                <input
                    id="brand"
                    name="brand"
                    value="{{ old('brand') }}"
                    maxlength="255"
                    placeholder="Brand name"
                >

                @error('brand')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Main Product Image --}}
            <div class="product-field">
                <label for="image">Main Product Image *</label>

                <input
                    id="image"
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp"
                    required
                >

                <small class="product-help">
                    Main image displayed as the primary product image.
                    JPG, JPEG, PNG or WebP; maximum 2 MB.
                </small>

                @error('image')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Additional Gallery Images --}}
            <div class="product-field full">
                <label for="gallery_images">
                    Additional Product Images (Maximum 5)
                </label>

                <input
                    id="gallery_images"
                    type="file"
                    name="gallery_images[]"
                    accept=".jpg,.jpeg,.png,.webp"
                    multiple
                >

                <small class="product-help">
                    Select up to 5 additional images. These will appear in the
                    product detail page gallery. JPG, JPEG, PNG or WebP;
                    maximum 2 MB per image.
                </small>

                <small
                    id="gallery-count"
                    class="product-help"
                    aria-live="polite"
                >
                    No additional images selected.
                </small>

                @error('gallery_images')
                    <span class="product-error">{{ $message }}</span>
                @enderror

                @error('gallery_images.*')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Description --}}
            <div class="product-field full">
                <label for="description">Description</label>

                <textarea
                    id="description"
                    name="description"
                    placeholder="Product specifications and details..."
                >{{ old('description') }}</textarea>

                @error('description')
                    <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Search engine (SEO) overrides --}}
            <div class="product-field full">
                <label for="meta_title">SEO Title</label>
                <input
                    type="text"
                    id="meta_title"
                    name="meta_title"
                    maxlength="255"
                    placeholder="Leave blank for automatic: product name + Kangra, HP"
                    value="{{ old('meta_title') }}">
                <small class="product-help">Shown as the title in Google results. Best under 60 characters.</small>
                @error('meta_title')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field full">
                <label for="meta_description">SEO Description</label>
                <textarea
                    id="meta_description"
                    name="meta_description"
                    maxlength="320"
                    rows="3"
                    placeholder="Leave blank for an automatic description built from the product details">{{ old('meta_description') }}</textarea>
                <small class="product-help">Shown under the title in Google results. Best 120–160 characters.</small>
                @error('meta_description')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Active Status --}}
            <div class="product-field full">
                <label class="product-checkbox">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked(old('is_active', '1') == '1')
                    >
                    Active product
                </label>
            </div>

            {{-- Price Visibility --}}
            <div class="product-field full">
                <label class="product-checkbox">
                    <input
                        type="checkbox"
                        name="show_price"
                        value="1"
                        @checked(old('show_price', '1') == '1')
                    >
                    Show price on website
                </label>
            </div>

        </div>

        <div class="product-form-actions">
            <button type="submit" class="product-submit">
                Save Product
            </button>

            <a
                href="{{ route('admin.products.index') }}"
                class="product-cancel"
            >
                Cancel
            </a>
        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Filter subcategories by selected category.
    const category = document.getElementById('category_id');
    const subcategory = document.getElementById('subcategory_id');

    function filterSubcategories() {
        const categoryId = category.value;

        Array.from(subcategory.options).forEach(function (option) {
            if (!option.value) return;

            option.hidden = categoryId !== '' &&
                option.dataset.category !== categoryId;

            if (option.hidden && option.selected) {
                subcategory.value = '';
            }
        });
    }

    category.addEventListener('change', filterSubcategories);
    filterSubcategories();

    // Limit the gallery selection to five files.
    const galleryInput = document.getElementById('gallery_images');
    const galleryCount = document.getElementById('gallery-count');

    galleryInput.addEventListener('change', function () {
        const files = Array.from(this.files);

        if (files.length > 5) {
            alert('You can select a maximum of 5 additional images.');
            this.value = '';
            galleryCount.textContent = 'No additional images selected.';
            return;
        }

        if (files.length === 0) {
            galleryCount.textContent = 'No additional images selected.';
        } else {
            galleryCount.textContent =
                files.length + ' additional image(s) selected.';
        }
    });
});
</script>

@endsection
