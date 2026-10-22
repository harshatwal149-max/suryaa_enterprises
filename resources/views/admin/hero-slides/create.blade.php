@extends('admin.layout')

@section('page-title', 'Add Hero Slide')
@section('page-heading', 'Add Hero Slide')
@section('breadcrumb', 'Admin / Hero Slides / Add')

@section('content')

<div class="slide-form-page">

    <div class="form-header">
        <div>
            <h1>Add Hero Slide</h1>
            <p>Create a banner for your website homepage.</p>
        </div>

        <a href="{{ route('admin.hero-slides.index') }}" class="btn-back">
            ← Back to Slides
        </a>
    </div>

    @if($errors->any())
        <div class="alert-error">
            <strong>Please correct these errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.hero-slides.store') }}"
        method="POST"
        enctype="multipart/form-data">

        @csrf

        <div class="form-card">
            <div class="card-heading">
                <h2>Banner Information</h2>
                <p>Upload the image and enter the content displayed on it.</p>
            </div>

            <div class="form-body">

                <div class="form-group">
                    <label for="image">Banner Image <span>*</span></label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,.webp"
                        required>

                    <small>Allowed: JPG, JPEG, PNG or WEBP. Maximum size: 5 MB.</small>

                    <div id="image-preview-wrapper" class="image-preview-wrapper" hidden>
                        <img id="image-preview" src="" alt="Banner preview">
                    </div>

                    @error('image')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="heading">Heading</label>
                    <input
                        type="text"
                        id="heading"
                        name="heading"
                        value="{{ old('heading') }}"
                        maxlength="255"
                        placeholder="e.g. Power Your Future with Solar">

                    @error('heading')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        maxlength="5000"
                        placeholder="Enter a short banner description">{{ old('description') }}</textarea>

                    @error('description')
                        <small class="field-error">{{ $message }}</small>
                    @enderror
                </div>

                <div class="form-row">

                    <div class="form-group">
                        <label for="button_text">Button Text</label>
                        <input
                            type="text"
                            id="button_text"
                            name="button_text"
                            value="{{ old('button_text') }}"
                            maxlength="100"
                            placeholder="e.g. Explore Products">

                        @error('button_text')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="button_url">Button URL</label>
                        <input
                            type="text"
                            id="button_url"
                            name="button_url"
                            value="{{ old('button_url') }}"
                            maxlength="2048"
                            placeholder="/products or https://example.com">

                        @error('button_url')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

            </div>
        </div>

        <div class="form-card settings-card">
            <div class="card-heading">
                <h2>Display Settings</h2>
                <p>Control the slide order and whether it appears on the website.</p>
            </div>

            <div class="form-body">

                <div class="form-row">

                    <div class="form-group">
                        <label for="sort_order">Display Order <span>*</span></label>
                        <input
                            type="number"
                            id="sort_order"
                            name="sort_order"
                            min="0"
                            value="{{ old('sort_order', 0) }}"
                            required>

                        <small>Lower numbers appear first.</small>

                        @error('sort_order')
                            <small class="field-error">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Slide Status</label>

                        <label class="status-toggle">
                            <input
                                type="checkbox"
                                name="is_active"
                                value="1"
                                {{ old('is_active', '1') ? 'checked' : '' }}>

                            <span>Active — show this slide on the website</span>
                        </label>
                    </div>

                </div>

            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.hero-slides.index') }}" class="btn-cancel">
                Cancel
            </a>

            <button type="submit" class="btn-save">
                Save Hero Slide
            </button>
        </div>

    </form>

</div>

<style>
    .slide-form-page {
        width: 100%;
        color: #18352d;
    }

    .form-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        margin-bottom: 24px;
    }

    .form-header h1 {
        margin: 0 0 7px;
        font-size: 28px;
        font-weight: 750;
    }

    .form-header p {
        margin: 0;
        color: #718078;
        font-size: 14px;
    }

    .btn-back,
    .btn-cancel {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 11px 15px;
        border-radius: 9px;
        text-decoration: none;
        font-size: 13px;
        font-weight: 650;
        white-space: nowrap;
    }

    .btn-back {
        background: #edf5ef;
        color: #176b4d;
    }

    .btn-cancel {
        background: #f1f4f2;
        color: #526259;
    }

    .form-card {
        background: #fff;
        border: 1px solid #e7eee9;
        border-radius: 14px;
        margin-bottom: 20px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(20, 60, 42, .035);
    }

    .card-heading {
        padding: 21px 24px;
        border-bottom: 1px solid #edf1ee;
    }

    .card-heading h2 {
        margin: 0 0 6px;
        font-size: 17px;
        font-weight: 700;
    }

    .card-heading p {
        margin: 0;
        color: #7b8980;
        font-size: 12px;
    }

    .form-body {
        padding: 24px;
    }

    .form-row {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .form-group {
        margin-bottom: 22px;
        min-width: 0;
    }

    .form-body > .form-group:last-child,
    .form-row:last-child .form-group {
        margin-bottom: 0;
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #30483c;
        font-size: 13px;
        font-weight: 650;
    }

    .form-group label span {
        color: #dc2626;
    }

    .form-group input:not([type="checkbox"]),
    .form-group textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 12px 13px;
        border: 1px solid #dce6df;
        border-radius: 8px;
        background: #fff;
        color: #24392e;
        font: inherit;
        font-size: 13px;
        outline: none;
    }

    .form-group input:focus,
    .form-group textarea:focus {
        border-color: #176b4d;
        box-shadow: 0 0 0 3px rgba(23, 107, 77, .09);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .form-group input[type="file"] {
        padding: 9px;
    }

    .form-group small {
        display: block;
        margin-top: 7px;
        color: #849087;
        font-size: 11px;
        line-height: 1.5;
    }

    .form-group .field-error {
        color: #bc4545;
    }

    .image-preview-wrapper {
        margin-top: 14px;
    }

    .image-preview-wrapper img {
        display: block;
        width: 100%;
        max-width: 480px;
        max-height: 220px;
        object-fit: contain;
        object-position: left;
        border: 1px solid #e7eee9;
        border-radius: 9px;
        background: #f8faf8;
    }

    .status-toggle {
        display: flex !important;
        align-items: center;
        gap: 10px;
        padding: 13px;
        border: 1px solid #dce6df;
        border-radius: 8px;
        background: #f8fbf8;
        cursor: pointer;
        line-height: 1.5;
    }

    .status-toggle input {
        width: 16px;
        height: 16px;
        accent-color: #176b4d;
        flex-shrink: 0;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 24px;
    }

    .btn-save {
        border: 0;
        border-radius: 9px;
        padding: 12px 20px;
        background: #176b4d;
        color: #fff;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-save:hover {
        background: #10543c;
    }

    .alert-error {
        padding: 13px 16px;
        margin-bottom: 20px;
        border: 1px solid #f3d0d0;
        border-radius: 9px;
        background: #fff0f0;
        color: #a52d2d;
        font-size: 13px;
    }

    .alert-error ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    @media (max-width: 650px) {
        .form-header h1 {
            font-size: 23px;
        }

        .form-row {
            grid-template-columns: 1fr;
            gap: 0;
        }

        .card-heading,
        .form-body {
            padding: 18px;
        }

        .form-actions {
            flex-direction: column-reverse;
            align-items: stretch;
        }

        .form-actions a,
        .form-actions button {
            width: 100%;
            box-sizing: border-box;
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('image');
    const previewWrapper = document.getElementById('image-preview-wrapper');
    const previewImage = document.getElementById('image-preview');

    if (!imageInput || !previewWrapper || !previewImage) {
        return;
    }

    let previewUrl = null;

    imageInput.addEventListener('change', function () {
        if (previewUrl) {
            URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        const file = this.files && this.files[0];

        if (!file) {
            previewWrapper.hidden = true;
            previewImage.removeAttribute('src');
            return;
        }

        if (!file.type.startsWith('image/')) {
            previewWrapper.hidden = true;
            previewImage.removeAttribute('src');
            return;
        }

        previewUrl = URL.createObjectURL(file);
        previewImage.src = previewUrl;
        previewWrapper.hidden = false;
    });
});
</script>

@endsection
