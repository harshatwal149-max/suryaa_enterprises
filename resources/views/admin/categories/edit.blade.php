@extends('admin.layout')

@section('content')
<style>
    .category-edit-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px;
        color: #202938;
    }

    .category-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .category-edit-header h1 {
        font-size: 30px;
        margin: 0 0 8px;
        font-weight: 700;
    }

    .category-edit-header p {
        color: #6b7280;
        margin: 0;
        font-size: 15px;
    }

    .category-edit-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 18px;
        border: 1px solid transparent;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: .2s ease;
    }

    .category-edit-btn-back {
        background: #fff;
        color: #374151;
        border-color: #d1d5db;
    }

    .category-edit-btn-back:hover {
        background: #f3f4f6;
    }

    .category-edit-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .category-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .category-edit-field {
        margin-bottom: 22px;
        min-width: 0;
    }

    .category-edit-field.full-width {
        grid-column: 1 / -1;
    }

    .category-edit-field label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #374151;
    }

    .category-edit-field input:not([type="checkbox"]):not([type="file"]),
    .category-edit-field textarea,
    .category-edit-field input[type="file"] {
        display: block;
        width: 100%;
        box-sizing: border-box;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: #fff;
        color: #111827;
        padding: 12px 14px;
        font: inherit;
        font-size: 14px;
        outline: none;
    }

    .category-edit-field input:focus,
    .category-edit-field textarea:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .category-edit-field textarea {
        min-height: 120px;
        resize: vertical;
    }

    .category-edit-help {
        display: block;
        margin-top: 7px;
        font-size: 12px;
        line-height: 1.5;
        color: #6b7280;
    }

    .category-edit-current-image {
        display: block;
        max-width: 180px;
        max-height: 140px;
        object-fit: contain;
        padding: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .category-edit-status {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 15px;
        background: #f9fafb;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
    }

    .category-edit-status input {
        width: 17px;
        height: 17px;
        cursor: pointer;
    }

    .category-edit-status label {
        margin: 0;
        cursor: pointer;
    }

    .category-edit-errors {
        margin-bottom: 20px;
        padding: 14px 18px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #b91c1c;
        background: #fef2f2;
    }

    .category-edit-errors ul {
        margin: 0;
        padding-left: 20px;
    }

    .category-edit-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        border-top: 1px solid #e5e7eb;
        padding-top: 22px;
        margin-top: 8px;
    }

    .category-edit-btn-save {
        color: #fff;
        background: #2563eb;
    }

    .category-edit-btn-save:hover {
        background: #1d4ed8;
    }

    .category-edit-btn-cancel {
        color: #374151;
        background: #f3f4f6;
    }

    @media (max-width: 650px) {
        .category-edit-page {
            padding: 20px 14px;
        }

        .category-edit-card {
            padding: 20px 16px;
        }

        .category-edit-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .category-edit-field.full-width {
            grid-column: auto;
        }

        .category-edit-header h1 {
            font-size: 25px;
        }
    }
</style>

<div class="category-edit-page">

    <div class="category-edit-header">
        <div>
            <h1>Edit Category</h1>
            <p>Update your category details, image and status.</p>
        </div>

        <a href="{{ route('admin.categories.index') }}"
           class="category-edit-btn category-edit-btn-back">
            ← Back to Categories
        </a>
    </div>

    @if ($errors->any())
        <div class="category-edit-errors">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="category-edit-card">
        <form
            action="{{ route('admin.categories.update', $category) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <div class="category-edit-grid">

                <div class="category-edit-field">
                    <label for="name">Category Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $category->name) }}"
                        placeholder="Enter category name"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="category-edit-field">
                    <label for="slug">Category Slug</label>
                    <input
                        type="text"
                        id="slug"
                        name="slug"
                        value="{{ old('slug', $category->slug) }}"
                        placeholder="e.g. fresh-fruits"
                        maxlength="255"
                    >
                    <small class="category-edit-help">
                        URL-friendly name for this category.
                    </small>
                </div>

                <div class="category-edit-field full-width">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        placeholder="Enter category description"
                    >{{ old('description', $category->description) }}</textarea>
                </div>

                <div class="category-edit-field full-width">
                    <label for="image">Category Image</label>

                    @if ($category->image)
                        <img
                            src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            class="category-edit-current-image"
                        >
                    @endif

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="category-edit-help">
                        JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                        Leave empty to keep the current image.
                    </small>
                </div>

                <div class="category-edit-field full-width">
                    <div class="category-edit-status">
                        <input type="hidden" name="is_active" value="0">

                        <input
                            type="checkbox"
                            id="is_active"
                            name="is_active"
                            value="1"
                            @checked((bool) old('is_active', $category->is_active))
                        >

                        <label for="is_active">Active Category</label>
                    </div>
                </div>

            </div>

            <div class="category-edit-actions">
                <button
                    type="submit"
                    class="category-edit-btn category-edit-btn-save"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('admin.categories.index') }}"
                    class="category-edit-btn category-edit-btn-cancel"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
