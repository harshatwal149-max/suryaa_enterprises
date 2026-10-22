
@extends('admin.layout')

@section('page-title', 'Edit Subcategory')
@section('page-heading', 'Edit Subcategory')
@section('breadcrumb', 'Admin / Subcategories / Edit')

@section('content')
<style>
    .subcategory-edit-page {
        max-width: 1000px;
        margin: 0 auto;
        padding: 32px;
        color: #202938;
    }

    .subcategory-edit-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        margin-bottom: 28px;
        flex-wrap: wrap;
    }

    .subcategory-edit-header h1 {
        font-size: 30px;
        margin: 0 0 8px;
        font-weight: 700;
    }

    .subcategory-edit-header p {
        color: #6b7280;
        margin: 0;
        font-size: 15px;
    }

    .subcategory-edit-btn {
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

    .subcategory-edit-btn-back {
        background: #fff;
        color: #374151;
        border-color: #d1d5db;
    }

    .subcategory-edit-btn-back:hover {
        background: #f3f4f6;
    }

    .subcategory-edit-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 28px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .subcategory-edit-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    .subcategory-edit-field {
        margin-bottom: 22px;
        min-width: 0;
    }

    .subcategory-edit-field.full-width {
        grid-column: 1 / -1;
    }

    .subcategory-edit-field label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 9px;
        color: #374151;
    }

    .subcategory-edit-field input,
    .subcategory-edit-field select {
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

    .subcategory-edit-field input[type="file"] {
        padding: 12px;
    }

    .subcategory-edit-field input:focus,
    .subcategory-edit-field select:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37, 99, 235, .12);
    }

    .subcategory-edit-help {
        display: block;
        margin-top: 7px;
        font-size: 12px;
        line-height: 1.5;
        color: #6b7280;
    }

    .subcategory-edit-current-image {
        display: block;
        max-width: 180px;
        max-height: 140px;
        object-fit: contain;
        padding: 8px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        margin-bottom: 12px;
    }

    .subcategory-edit-errors {
        margin-bottom: 20px;
        padding: 14px 18px;
        border: 1px solid #fecaca;
        border-radius: 8px;
        color: #b91c1c;
        background: #fef2f2;
    }

    .subcategory-edit-errors ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .subcategory-edit-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        border-top: 1px solid #e5e7eb;
        padding-top: 22px;
        margin-top: 8px;
    }

    .subcategory-edit-btn-save {
        color: #fff;
        background: #2563eb;
    }

    .subcategory-edit-btn-save:hover {
        background: #1d4ed8;
    }

    .subcategory-edit-btn-cancel {
        color: #374151;
        background: #f3f4f6;
    }

    @media (max-width: 650px) {
        .subcategory-edit-page {
            padding: 20px 14px;
        }

        .subcategory-edit-card {
            padding: 20px 16px;
        }

        .subcategory-edit-grid {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .subcategory-edit-field.full-width {
            grid-column: auto;
        }

        .subcategory-edit-header h1 {
            font-size: 25px;
        }
    }
</style>

<div class="subcategory-edit-page">
    <div class="subcategory-edit-header">
        <div>
            <h1>Edit Subcategory</h1>
            <p>Update the parent category, name and image.</p>
        </div>

        <a href="{{ route('admin.subcategories.index') }}"
           class="subcategory-edit-btn subcategory-edit-btn-back">
            ← Back to Subcategories
        </a>
    </div>

    @if ($errors->any())
        <div class="subcategory-edit-errors">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="subcategory-edit-card">
        <form
            action="{{ route('admin.subcategories.update', $subcategory) }}"
            method="POST"
            enctype="multipart/form-data"
        >
            @csrf
            @method('PUT')

            <div class="subcategory-edit-grid">
                <div class="subcategory-edit-field">
                    <label for="category_id">Parent Category *</label>

                    <select id="category_id" name="category_id" required>
                        <option value="">-- Select Category --</option>

                        @foreach ($categories as $category)
                            <option
                                value="{{ $category->id }}"
                                @selected(
                                    (string) old('category_id', $subcategory->category_id)
                                    === (string) $category->id
                                )
                            >
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="subcategory-edit-field">
                    <label for="name">Subcategory Name *</label>

                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $subcategory->name) }}"
                        placeholder="Enter subcategory name"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="subcategory-edit-field full-width">
                    <label>Current Image</label>

                    @if ($subcategory->image)
                        <img
                            src="{{ asset('storage/' . $subcategory->image) }}"
                            alt="{{ $subcategory->name }}"
                            class="subcategory-edit-current-image"
                        >
                    @else
                        <p class="subcategory-edit-help">
                            No image uploaded for this subcategory.
                        </p>
                    @endif

                    <label for="image">Upload New Image (optional)</label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                    >

                    <small class="subcategory-edit-help">
                        JPG, JPEG, PNG or WEBP. Maximum 2 MB.
                        Leave empty to keep the current image.
                    </small>
                </div>
            </div>

            <div class="subcategory-edit-actions">
                <button
                    type="submit"
                    class="subcategory-edit-btn subcategory-edit-btn-save"
                >
                    Save Changes
                </button>

                <a
                    href="{{ route('admin.subcategories.index') }}"
                    class="subcategory-edit-btn subcategory-edit-btn-cancel"
                >
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
