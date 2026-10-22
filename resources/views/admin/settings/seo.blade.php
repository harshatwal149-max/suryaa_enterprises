@extends('admin.layout')

@section('page-title', 'SEO Settings')
@section('page-heading', 'SEO Settings')
@section('breadcrumb', 'Admin / Website Management / SEO Settings')

@section('content')

@php
    $seoOgImageUrl = !empty($settings?->seo_og_image_path)
        ? asset('storage/' . ltrim($settings->seo_og_image_path, '/'))
        : null;
@endphp

<style>
    .settings-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto;
    }

    .settings-intro {
        margin-bottom: 24px;
    }

    .settings-intro h2 {
        margin: 0 0 8px;
        color: #022c22;
        font-size: 25px;
        font-weight: 700;
    }

    .settings-intro p {
        margin: 0;
        color: #78716c;
        font-size: 13px;
        line-height: 1.7;
    }

    .settings-alert {
        padding: 14px 17px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.7;
    }

    .settings-alert.success {
        color: #065f46;
        background: #ecfdf5;
        border: 1px solid #a7f3d0;
    }

    .settings-alert.error {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca;
    }

    .settings-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        margin-bottom: 22px;
    }

    .settings-card-header {
        padding: 21px 25px;
        border-bottom: 1px solid #e7e5e4;
    }

    .settings-card-header h3 {
        margin: 0 0 5px;
        color: #1c1917;
        font-size: 17px;
        font-weight: 700;
    }

    .settings-card-header p {
        margin: 0;
        color: #78716c;
        font-size: 12px;
        line-height: 1.6;
    }

    .settings-card-body {
        padding: 25px;
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px;
    }

    .settings-field {
        min-width: 0;
        margin-bottom: 6px;
    }

    .settings-field.full {
        grid-column: 1 / -1;
    }

    .settings-label {
        display: block;
        margin-bottom: 8px;
        color: #292524;
        font-size: 13px;
        font-weight: 700;
    }

    .settings-input {
        display: block;
        width: 100%;
        min-height: 44px;
        padding: 11px 13px;
        color: #292524;
        background: #fff;
        border: 1px solid #d6d3d1;
        border-radius: 8px;
        outline: none;
        font: inherit;
        font-size: 13px;
        box-sizing: border-box;
    }

    .settings-input:focus {
        border-color: #047857;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, .10);
    }

    textarea.settings-input {
        resize: vertical;
        line-height: 1.6;
    }

    .settings-help {
        display: block;
        margin-top: 7px;
        color: #78716c;
        font-size: 11px;
        line-height: 1.7;
    }

    .settings-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px;
    }

    .settings-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px solid #e7e5e4;
    }

    .settings-save-button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 43px;
        padding: 11px 21px;
        color: #fff;
        background: #047857;
        border: 1px solid #047857;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .settings-save-button:hover {
        background: #065f46;
    }

    @media (max-width: 650px) {
        .settings-intro h2 {
            font-size: 22px;
        }

        .settings-card-header,
        .settings-card-body {
            padding: 18px;
        }

        .settings-grid {
            grid-template-columns: 1fr;
        }

        .settings-field.full {
            grid-column: auto;
        }

        .settings-actions {
            justify-content: stretch;
        }

        .settings-save-button {
            width: 100%;
        }
    }
</style>

<div class="settings-page">

    {{-- Page introduction --}}
    <div class="settings-intro">
        <h2>SEO Settings</h2>

        <p>
            Manage search engine metadata and the image displayed when your website is shared.
        </p>
    </div>

    {{-- Success message --}}
    @if (session('success'))
        <div class="settings-alert success">
            <strong>Success!</strong>
            {{ session('success') }}
        </div>
    @endif

    {{-- General error message --}}
    @if (session('error'))
        <div class="settings-alert error">
            {{ session('error') }}
        </div>
    @endif

    {{-- Validation errors --}}
    @if ($errors->any())
        <div class="settings-alert error">
            <strong>Please fix the following errors:</strong>

            <ul style="margin: 8px 0 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form
        action="{{ route('admin.settings.seo.update') }}"
        method="POST"
        enctype="multipart/form-data"
    >
        @csrf
        @method('PUT')

        {{-- Search engine metadata --}}
        <section class="settings-card">

            <div class="settings-card-header">
                <h3>Search Engine Metadata</h3>

                <p>
                    Set the default title, description, keywords and indexing preference.
                </p>
            </div>

            <div class="settings-card-body">

                <div class="settings-grid">

                    {{-- SEO title --}}
                    <div class="settings-field full">
                        <label for="seo_title" class="settings-label">
                            SEO Title
                        </label>

                        <input
                            type="text"
                            name="seo_title"
                            id="seo_title"
                            class="settings-input"
                            value="{{ old('seo_title', $settings?->seo_title) }}"
                            placeholder="Enter SEO title"
                            maxlength="255"
                        >

                        @error('seo_title')
                            <small class="settings-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Meta description --}}
                    <div class="settings-field full">
                        <label for="seo_description" class="settings-label">
                            Meta Description
                        </label>

                        <textarea
                            name="seo_description"
                            id="seo_description"
                            class="settings-input"
                            rows="4"
                            placeholder="Describe your website for search engines"
                            maxlength="5000"
                        >{{ old('seo_description', $settings?->seo_description) }}</textarea>

                        @error('seo_description')
                            <small class="settings-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- SEO keywords --}}
                    <div class="settings-field full">
                        <label for="seo_keywords" class="settings-label">
                            SEO Keywords
                        </label>

                        <textarea
                            name="seo_keywords"
                            id="seo_keywords"
                            class="settings-input"
                            rows="4"
                            placeholder="solar panels, solar products, solar solutions"
                            maxlength="5000"
                        >{{ old('seo_keywords', $settings?->seo_keywords) }}</textarea>

                        @error('seo_keywords')
                            <small class="settings-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                    {{-- Search engine visibility --}}
                    <div class="settings-field full">
                        <label for="default_meta_robots" class="settings-label">
                            Search Engine Visibility
                        </label>

                        <select
                            name="default_meta_robots"
                            id="default_meta_robots"
                            class="settings-input"
                        >
                            <option
                                value="index,follow"
                                @selected(old('default_meta_robots', $settings?->default_meta_robots ?? 'index,follow') === 'index,follow')
                            >
                                Index and Follow
                            </option>

                            <option
                                value="noindex,nofollow"
                                @selected(old('default_meta_robots', $settings?->default_meta_robots) === 'noindex,nofollow')
                            >
                                No Index and No Follow
                            </option>

                            <option
                                value="noindex,follow"
                                @selected(old('default_meta_robots', $settings?->default_meta_robots) === 'noindex,follow')
                            >
                                No Index, Follow Links
                            </option>

                            <option
                                value="index,nofollow"
                                @selected(old('default_meta_robots', $settings?->default_meta_robots) === 'index,nofollow')
                            >
                                Index, No Follow Links
                            </option>
                        </select>

                        @error('default_meta_robots')
                            <small class="settings-error">
                                {{ $message }}
                            </small>
                        @enderror
                    </div>

                </div>

            </div>
        </section>

        {{-- Social sharing image --}}
        <section class="settings-card">

            <div class="settings-card-header">
                <h3>Social Sharing Image</h3>

                <p>
                    Optional image used when a page from your website is shared.
                </p>
            </div>

            <div class="settings-card-body">

                {{-- Current sharing image --}}
                @if ($seoOgImageUrl)
                    <div class="settings-field">

                        <label class="settings-label">
                            Current Sharing Image
                        </label>

                        <div
                            style="display: flex; align-items: center; justify-content: center; max-width: 360px; min-height: 130px; padding: 12px; background: #fafaf9; border: 1px dashed #d6d3d1; border-radius: 10px; margin-bottom: 14px;"
                        >
                            <img
                                src="{{ $seoOgImageUrl }}"
                                alt="Current SEO sharing image"
                                style="max-width: 100%; max-height: 180px; object-fit: contain;"
                            >
                        </div>

                    </div>
                @endif

                {{-- Upload sharing image --}}
                <div class="settings-field">

                    <label for="seo_og_image" class="settings-label">
                        Upload Sharing Image
                    </label>

                    <input
                        type="file"
                        name="seo_og_image"
                        id="seo_og_image"
                        class="settings-input"
                        accept=".png,.jpg,.jpeg,.webp"
                    >

                    <small class="settings-help">
                        PNG, JPG, JPEG or WEBP. Maximum 2 MB.
                        Leave empty to keep the current image.
                    </small>

                    @error('seo_og_image')
                        <small class="settings-error">
                            {{ $message }}
                        </small>
                    @enderror

                </div>

                {{-- New image preview --}}
                <div
                    id="seoOgPreviewContainer"
                    style="display: none; align-items: center; justify-content: center; max-width: 360px; min-height: 130px; padding: 12px; background: #fafaf9; border: 1px dashed #d6d3d1; border-radius: 10px; margin-top: 14px;"
                >
                    <img
                        id="seoOgPreview"
                        src=""
                        alt="New SEO sharing image preview"
                        style="max-width: 100%; max-height: 180px; object-fit: contain;"
                    >
                </div>

            </div>
        </section>

        {{-- Save settings --}}
        <div class="settings-actions">
            <button type="submit" class="settings-save-button">
                Save SEO Settings
            </button>
        </div>

    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const input = document.getElementById('seo_og_image');
        const box = document.getElementById('seoOgPreviewContainer');
        const img = document.getElementById('seoOgPreview');

        if (!input || !box || !img) {
            return;
        }

        let previewUrl = null;

        input.addEventListener('change', function () {
            if (previewUrl) {
                URL.revokeObjectURL(previewUrl);
                previewUrl = null;
            }

            const file = input.files && input.files[0];

            if (!file) {
                box.style.display = 'none';
                img.removeAttribute('src');
                return;
            }

            if (!file.type.startsWith('image/')) {
                box.style.display = 'none';
                img.removeAttribute('src');
                return;
            }

            previewUrl = URL.createObjectURL(file);
            img.src = previewUrl;
            box.style.display = 'flex';
        });
    });
</script>

@endsection
