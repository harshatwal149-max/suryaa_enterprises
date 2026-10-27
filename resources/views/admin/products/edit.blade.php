@extends('admin.layout')

@section('page-title', 'Edit Product')
@section('page-heading', 'Edit Product')
@section('breadcrumb', 'Admin / Products / Edit')

@section('content')

<style>
    .product-form-card {
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 14px;
        padding: 24px;
    }

    .product-form-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .product-field {
        display: flex;
        flex-direction: column;
        gap: 7px;
        min-width: 0;
    }

    .product-field.full {
        grid-column: 1 / -1;
    }

    .product-field label {
        font-size: 13px;
        font-weight: 600;
        color: #334155;
    }

    .product-field input:not([type="checkbox"]),
    .product-field select,
    .product-field textarea {
        width: 100%;
        min-width: 0;
        padding: 11px 12px;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        background: #fff;
        color: #0f172a;
        font: inherit;
        font-size: 14px;
        box-sizing: border-box;
    }

    .product-field input:focus,
    .product-field select:focus,
    .product-field textarea:focus {
        outline: 2px solid #a7f3d0;
        border-color: #047857;
    }

    .product-field textarea {
        min-height: 120px;
        resize: vertical;
    }

    .product-error {
        color: #b91c1c;
        font-size: 12px;
    }

    .product-help {
        color: #64748b;
        font-size: 12px;
        line-height: 1.5;
    }

    .product-form-actions {
        display: flex;
        gap: 10px;
        margin-top: 24px;
        flex-wrap: wrap;
    }

    .product-submit,
    .product-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 17px;
        border: 0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 13px;
        text-decoration: none;
        cursor: pointer;
    }

    .product-submit {
        background: #047857;
        color: #fff;
    }

    .product-submit:hover {
        background: #065f46;
    }

    .product-submit:disabled {
        opacity: .65;
        cursor: not-allowed;
    }

    .product-cancel {
        background: #e2e8f0;
        color: #334155;
    }

    .product-current-image {
        width: 120px;
        height: 100px;
        object-fit: cover;
        border-radius: 9px;
        border: 1px solid #e2e8f0;
        margin-bottom: 8px;
    }

    .product-gallery-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(135px, 1fr));
        gap: 14px;
        margin-top: 12px;
    }

    .product-gallery-item {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        padding: 9px;
        min-width: 0;
        transition: opacity 0.3s ease, transform 0.3s ease;
    }

    .product-gallery-image {
        width: 100%;
        height: 105px;
        object-fit: cover;
        border-radius: 7px;
        display: block;
        margin-bottom: 9px;
    }

    .product-gallery-delete {
        width: 100%;
        padding: 8px 9px;
        background: #fee2e2;
        color: #b91c1c;
        border: 1px solid #fecaca;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background .2s ease;
    }

    .product-gallery-delete:hover {
        background: #fecaca;
    }

    .product-checkbox {
        display: flex;
        gap: 9px;
        align-items: center;
        font-size: 14px;
    }

    .product-checkbox input {
        width: auto;
    }

    .product-form-message {
        padding: 13px;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .product-form-message.error {
        background: #fee2e2;
        color: #991b1b;
    }

    .product-form-message.success {
        background: #d1fae5;
        color: #065f46;
    }

    @media (max-width: 650px) {
        .product-form-grid {
            grid-template-columns: 1fr;
        }

        .product-field.full {
            grid-column: auto;
        }

        .product-form-card {
            padding: 16px;
        }
    }
</style>

<div class="product-form-card">

    <h2 style="font-size:20px;font-weight:700;margin:0 0 6px;">
        Edit Product
    </h2>

    <p style="color:#64748b;font-size:13px;margin:0 0 24px;">
        Update {{ $product->name }} details and product images.
    </p>

    @if(session('success'))
    <div class="product-form-message success">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="product-form-message error">
        {{ session('error') }}
    </div>
    @endif

    @if($errors->any())
    <div class="product-form-message error">
        <strong>Please fix the following errors:</strong>
        <ul style="padding-left:20px;margin:8px 0 0;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form
        id="edit-product-form"
        action="{{ route('admin.products.update', $product) }}"
        method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="product-form-grid">

            <div class="product-field">
                <label for="name">Product Name *</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ old('name', $product->name) }}"
                    maxlength="255"
                    required>
                @error('name')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="sku">SKU / Product Code</label>
                <input
                    id="sku"
                    type="text"
                    name="sku"
                    value="{{ old('sku', $product->sku) }}"
                    maxlength="255">
                @error('sku')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="category_id">Category *</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    @foreach($categories as $category)
                    <option
                        value="{{ $category->id }}"
                        @selected(old('category_id', $product->category_id) == $category->id)
                        >
                        {{ $category->name }}
                    </option>
                    @endforeach
                </select>
                @error('category_id')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="subcategory_id">Subcategory</label>
                <select id="subcategory_id" name="subcategory_id">
                    <option value="">No subcategory</option>
                    @foreach($subcategories as $subcategory)
                    <option
                        value="{{ $subcategory->id }}"
                        data-category="{{ $subcategory->category_id }}"
                        @selected(old('subcategory_id', $product->subcategory_id) == $subcategory->id)
                        >
                        {{ $subcategory->name }}
                    </option>
                    @endforeach
                </select>
                @error('subcategory_id')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="price">Price (₹) *</label>
                <input
                    id="price"
                    type="number"
                    name="price"
                    min="0"
                    step="0.01"
                    value="{{ old('price', $product->price) }}"
                    required>
                @error('price')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="discount_price">Discount Price (₹)</label>
                <input
                    id="discount_price"
                    type="number"
                    name="discount_price"
                    min="0"
                    step="0.01"
                    value="{{ old('discount_price', $product->discount_price) }}">
                @error('discount_price')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="quantity">Stock Quantity *</label>
                <input
                    id="quantity"
                    type="number"
                    name="quantity"
                    min="0"
                    step="1"
                    value="{{ old('quantity', $product->quantity) }}"
                    required>
                @error('quantity')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="size">Size / Capacity</label>
                <input
                    id="size"
                    type="text"
                    name="size"
                    value="{{ old('size', $product->size) }}"
                    maxlength="255">
                @error('size')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field">
                <label for="brand">Brand</label>
                <input
                    id="brand"
                    type="text"
                    name="brand"
                    value="{{ old('brand', $product->brand) }}"
                    maxlength="255">
                @error('brand')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Main product image --}}
            <div class="product-field">
                <label for="new_image">Replace Main Product Image</label>

                @if($product->image)
                <img
                    class="product-current-image"
                    src="{{ asset('storage/' . ltrim($product->image, '/')) }}"
                    alt="{{ $product->name }}"
                    loading="lazy">
                @endif

                <input
                    id="new_image"
                    type="file"
                    name="image"
                    accept=".jpg,.jpeg,.png,.webp">

                <small class="product-help">
                    Leave empty to keep the current image. JPG, JPEG, PNG or
                    WebP; maximum 2 MB.
                </small>

                @error('image')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Additional gallery images --}}
            <div class="product-field full">
                <label for="gallery_images">Add Gallery Images (Maximum 5)</label>

                <input
                    id="gallery_images"
                    type="file"
                    name="gallery_images[]"
                    accept=".jpg,.jpeg,.png,.webp"
                    data-existing-count="{{ $product->images->count() }}"
                    multiple>

                <small class="product-help">
                    You can add images up to the total limit of 5 additional
                    gallery images. Maximum 2 MB per image.
                </small>

                <small id="gallery-count" class="product-help" aria-live="polite">
                    No new images selected.
                </small>

                @error('gallery_images')
                <span class="product-error">{{ $message }}</span>
                @enderror

                @error('gallery_images.*')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            {{-- Current gallery --}}
            <div class="product-field full">
                <label>Current Additional Images</label>

                <p id="gallery-usage-text" class="product-help">
                    @if($product->images->isNotEmpty())
                        {{ $product->images->count() }} of 5 gallery images used. Delete an image below to make room for another.
                    @else
                        No additional images uploaded yet.
                    @endif
                </p>

                <div class="product-gallery-grid" id="gallery-items-container">
                    @foreach($product->images as $galleryImage)
                    <div class="product-gallery-item" id="gallery-card-{{ $galleryImage->id }}">
                        <img
                            class="product-gallery-image"
                            src="{{ asset('storage/' . ltrim($galleryImage->image_path, '/')) }}"
                            alt="{{ $product->name }} gallery image"
                            loading="lazy">

                        <button
                            type="button"
                            class="product-gallery-delete ajax-delete-image"
                            data-url="{{ route('admin.products.images.destroy', ['product' => $product->id, 'image' => $galleryImage->id]) }}"
                            data-id="{{ $galleryImage->id }}">
                            Delete Image
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="product-field full">
                <label for="description">Description</label>
                <textarea id="description" name="description">{{ old('description', $product->description) }}</textarea>
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
                    placeholder="Auto: {{ \App\Support\Seo::productTitle($product->replicate()->fill(['meta_title' => null])) }}"
                    value="{{ old('meta_title', $product->meta_title) }}">
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
                    placeholder="Auto: {{ \App\Support\Seo::productDescription($product->replicate()->fill(['meta_description' => null])) }}">{{ old('meta_description', $product->meta_description) }}</textarea>
                <small class="product-help">Shown under the title in Google results. Best 120–160 characters.</small>
                @error('meta_description')
                <span class="product-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="product-field full">
                <label class="product-checkbox">
                    <input
                        type="checkbox"
                        name="is_active"
                        value="1"
                        @checked((bool) old('is_active', $product->is_active))
                    >
                    Active product
                </label>
            </div>

            <div class="product-field full">
                <label class="product-checkbox">
                    <input
                        type="checkbox"
                        name="show_price"
                        value="1"
                        @checked((bool) old('show_price', $product->show_price))
                    >
                    Show price on website
                </label>
            </div>

        </div>

        <div class="product-form-actions">
            <button id="update-product-button" type="submit" class="product-submit">
                Update Product
            </button>

            <a href="{{ route('admin.products.index') }}" class="product-cancel">
                Cancel
            </a>
        </div>
    </form>
</div>

<div
    id="product-form-notifications"
    hidden
    data-success="{{ session('success', '') }}"
    data-error="{{ session('error', '') }}"
    data-validation-error="{{ $errors->first() }}"></div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const category = document.getElementById('category_id');
        const subcategory = document.getElementById('subcategory_id');

        // Filter subcategories according to selected category.
        function filterSubcategories() {
            if (!category || !subcategory) return;

            const categoryId = category.value;

            Array.from(subcategory.options).forEach(function(option) {
                if (!option.value) return;

                const shouldHide =
                    categoryId !== '' &&
                    option.dataset.category !== categoryId;

                option.hidden = shouldHide;
                option.disabled = shouldHide;

                if (shouldHide && option.selected) {
                    subcategory.value = '';
                }
            });
        }

        if (category && subcategory) {
            category.addEventListener('change', filterSubcategories);
            filterSubcategories();
        }

        // Gallery file count and limit validation.
        const galleryInput = document.getElementById('gallery_images');
        const galleryCount = document.getElementById('gallery-count');
        const galleryUsageText = document.getElementById('gallery-usage-text');

        function getRemainingSlots() {
            const existingImages = Number(galleryInput.dataset.existingCount || 0);
            return Math.max(0, 5 - existingImages);
        }

        if (galleryInput && galleryCount) {
            galleryInput.addEventListener('change', function() {
                const files = Array.from(this.files || []);
                const remainingSlots = getRemainingSlots();
                const existingImages = Number(galleryInput.dataset.existingCount || 0);

                if (files.length > remainingSlots) {
                    const message =
                        'You already have ' + existingImages +
                        ' gallery image(s). You can add only ' +
                        remainingSlots + ' more.';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Gallery limit reached',
                            text: message,
                            icon: 'warning',
                            confirmButtonColor: '#047857'
                        });
                    } else {
                        alert(message);
                    }

                    this.value = '';
                    galleryCount.textContent = 'No new images selected.';
                    return;
                }

                const oversized = files.find(function(file) {
                    return file.size > (2 * 1024 * 1024);
                });

                if (oversized) {
                    const message = '"' + oversized.name +
                        '" exceeds the 2 MB file size limit.';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Image too large',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#b91c1c'
                        });
                    } else {
                        alert(message);
                    }

                    this.value = '';
                    galleryCount.textContent = 'No new images selected.';
                    return;
                }

                galleryCount.textContent = files.length ?
                    files.length + ' new image(s) selected.' :
                    'No new images selected.';
            });
        }

        // Main image size validation.
        const mainImageInput = document.getElementById('new_image');

        if (mainImageInput) {
            mainImageInput.addEventListener('change', function() {
                const file = this.files && this.files[0];
                if (!file) return;

                const allowedTypes = [
                    'image/jpeg',
                    'image/png',
                    'image/webp'
                ];

                if (!allowedTypes.includes(file.type) || file.size > (2 * 1024 * 1024)) {
                    const message = !allowedTypes.includes(file.type) ?
                        'Please select a JPG, PNG or WebP image.' :
                        'The main image must not exceed 2 MB.';

                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Invalid image',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#b91c1c'
                        });
                    } else {
                        alert(message);
                    }

                    this.value = '';
                }
            });
        }

        // AJAX gallery image deletion with SweetAlert2
        document.querySelectorAll('.ajax-delete-image').forEach(function(button) {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const imageId = this.dataset.id;
                const deleteUrl = this.dataset.url;
                const card = document.getElementById('gallery-card-' + imageId);

                const executeDelete = () => {
                    button.disabled = true;
                    button.textContent = 'Deleting...';

                    fetch(deleteUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: '_method=DELETE'
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (card) {
                                card.style.opacity = '0';
                                card.style.transform = 'scale(0.9)';
                                setTimeout(() => {
                                    card.remove();

                                    // Update existing image counter in DOM
                                    const currentCount = Math.max(0, Number(galleryInput.dataset.existingCount || 1) - 1);
                                    galleryInput.dataset.existingCount = currentCount;

                                    if (galleryUsageText) {
                                        galleryUsageText.textContent = currentCount > 0 ?
                                            currentCount + ' of 5 gallery images used. Delete an image below to make room for another.' :
                                            'No additional images uploaded yet.';
                                    }
                                }, 300);
                            }

                            if (window.Swal) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message || 'Gallery image deleted.',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                            }
                        } else {
                            throw new Error(data.message || 'Failed to delete image');
                        }
                    })
                    .catch(err => {
                        button.disabled = false;
                        button.textContent = 'Delete Image';
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: err.message || 'Something went wrong.'
                            });
                        }
                    });
                };

                if (typeof Swal === 'undefined') {
                    if (confirm('Delete this gallery image permanently?')) {
                        executeDelete();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Delete gallery image?',
                    text: 'This image will be permanently removed from the server.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, delete it',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#b91c1c',
                    cancelButtonColor: '#64748b',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        executeDelete();
                    }
                });
            });
        });

        // Prevent accidental double submission of the update form.
        const updateForm = document.getElementById('edit-product-form');
        const updateButton = document.getElementById('update-product-button');

        if (updateForm && updateButton) {
            updateForm.addEventListener('submit', function() {
                if (!updateForm.checkValidity()) return;
                updateButton.disabled = true;
                updateButton.textContent = 'Updating...';
            });
        }

        // Display server-side flash messages
        const notificationElement = document.getElementById('product-form-notifications');

        if (notificationElement && typeof Swal !== 'undefined') {
            const successMessage = notificationElement.dataset.success || '';
            const errorMessage = notificationElement.dataset.error || '';
            const validationMessage = notificationElement.dataset.validationError || '';

            if (successMessage) {
                Swal.fire({
                    title: 'Success!',
                    text: successMessage,
                    icon: 'success',
                    confirmButtonColor: '#047857',
                    timer: 3000,
                    timerProgressBar: true
                });
            } else if (errorMessage) {
                Swal.fire({
                    title: 'Error!',
                    text: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#b91c1c'
                });
            } else if (validationMessage) {
                Swal.fire({
                    title: 'Please check',
                    text: validationMessage,
                    icon: 'error',
                    confirmButtonColor: '#b91c1c'
                });
            }
        }
    });
</script>

@endsection