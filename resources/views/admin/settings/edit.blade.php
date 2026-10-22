@extends('admin.layout')

@section('page-title', 'General Settings')
@section('page-heading', 'General Settings')
@section('breadcrumb', 'Admin / Website Management / General Settings')

@section('content')

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
    }

    .settings-card-header {
        padding: 21px 25px;
        background: #fff;
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

    .settings-field {
        margin-bottom: 24px;
    }

    .settings-label {
        display: block;
        margin-bottom: 9px;
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
        font-family: inherit;
        font-size: 13px;
        transition: border-color .2s, box-shadow .2s;
        box-sizing: border-box;
    }

    .settings-input:focus {
        border-color: #047857;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, .10);
    }

    .settings-help {
        display: block;
        margin-top: 8px;
        color: #78716c;
        font-size: 11px;
        line-height: 1.7;
    }

    .settings-section-title {
        margin: 0 0 7px;
        color: #022c22;
        font-size: 15px;
        font-weight: 700;
    }

    .settings-section-description {
        margin: 0 0 20px;
        color: #78716c;
        font-size: 12px;
        line-height: 1.7;
    }

    .settings-divider {
        height: 1px;
        margin: 28px 0;
        background: #e7e5e4;
        border: 0;
    }

    .settings-actions {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 22px;
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
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .settings-save-button:hover {
        background: #065f46;
        transform: translateY(-1px);
    }

    .settings-save-button:disabled {
        opacity: .65;
        cursor: wait;
        transform: none;
    }

    .settings-note {
        padding: 14px 16px;
        margin-bottom: 24px;
        color: #44403c;
        background: #fafaf9;
        border: 1px solid #e7e5e4;
        border-radius: 10px;
        font-size: 12px;
        line-height: 1.8;
    }

    .settings-note strong {
        color: #022c22;
    }

    @media (max-width: 650px) {
        .settings-intro h2 {
            font-size: 22px;
        }

        .settings-card-header,
        .settings-card-body {
            padding: 18px;
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

    {{-- Page Heading --}}
    <div class="settings-intro">
        <h2>General Settings</h2>
        <p>
            Manage your website name, About Us content and footer text from this page.
        </p>
    </div>

    {{-- Success Message --}}
    @if (session('success'))
    <div class="settings-alert success" role="status">
        <strong>Success!</strong>
        {{ session('success') }}
    </div>
    @endif

    {{-- Validation Errors --}}
    @if ($errors->any())
    <div class="settings-alert error" role="alert">
        <strong>Please fix the following errors:</strong>
        <ul style="margin: 8px 0 0; padding-left: 20px;">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="settings-card">

        <div class="settings-card-header">
            <h3>Website Information</h3>
            <p>Only general website content is managed here. Other settings have their own pages.</p>
        </div>

        <div class="settings-card-body">

            <form
                action="{{ route('admin.settings.update') }}"
                method="POST"
                enctype="multipart/form-data"
                id="websiteSettingsForm">
                @csrf
                @method('PUT')

                {{-- Website Identity --}}
                <h4 class="settings-section-title">Website Identity</h4>
                <p class="settings-section-description">
                    Set the name used to identify your business across the website.
                </p>

                <div class="settings-field">
                    <label for="site_name" class="settings-label">
                        Website Name <span style="color: #dc2626;">*</span>
                    </label>
                    <input
                        type="text"
                        name="site_name"
                        id="site_name"
                        class="settings-input"
                        value="{{ old('site_name', $settings?->site_name ?? 'Suryaa Enterprises') }}"
                        placeholder="Enter your website name"
                        maxlength="255"
                        required>
                    @error('site_name')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <hr class="settings-divider">

                {{-- About Us Settings --}}
                <h4 class="settings-section-title">About Us Settings</h4>
                <p class="settings-section-description">
                    Manage the About Us content and highlights displayed on your website.
                </p>

                <div class="settings-field">
                    <label for="about_heading" class="settings-label">About Us Heading</label>
                    <input
                        type="text"
                        name="about_heading"
                        id="about_heading"
                        class="settings-input"
                        value="{{ old('about_heading', $settings?->about_heading ?? 'About Suryaa Enterprises') }}"
                        maxlength="255"
                        placeholder="About Suryaa Enterprises">
                    @error('about_heading')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="settings-field">
                    <label for="about_description" class="settings-label">About Us Description</label>
                    <textarea
                        name="about_description"
                        id="about_description"
                        class="settings-input"
                        rows="6"
                        maxlength="10000"
                        placeholder="Write about your business, experience and solar solutions...">{{ old('about_description', $settings?->about_description) }}</textarea>
                    @error('about_description')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                    <small class="settings-help">
                        Write a clear introduction to your business and services.
                    </small>
                </div>

                {{-- About Us Image Upload --}}
                <div class="settings-field">
                    <label for="about_image" class="settings-label">About Us Image</label>

                    @if ($settings?->about_image)
                    <div style="margin-bottom: 14px;">
                        <img
                            src="{{ asset('storage/' . $settings->about_image) }}"
                            alt="Current About Us image"
                            style="display:block; width:100%; max-width:420px; max-height:260px; object-fit:cover; border:1px solid #e7e5e4; border-radius:10px;">
                        <small class="settings-help">Current image</small>
                    </div>
                    @endif

                    <input
                        type="file"
                        name="about_image"
                        id="about_image"
                        class="settings-input"
                        accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                    <small class="settings-help">
                        Upload a JPG, JPEG, PNG or WebP image. Maximum size: 4 MB. Leave empty to keep the current image.
                    </small>
                    @error('about_image')
                    <small class="settings-help" style="color:#dc2626;">{{ $message }}</small>
                    @enderror

                    <div id="aboutImagePreviewWrap" style="display:none; margin-top:14px;">
                        <img id="aboutImagePreview" src="" alt="Selected About Us image preview" style="display:block; width:100%; max-width:420px; max-height:260px; object-fit:cover; border:1px solid #e7e5e4; border-radius:10px;">
                        <small class="settings-help">New image preview</small>
                    </div>
                </div>

                <div class="settings-field">
                    <label for="about_highlight_one" class="settings-label">Highlight 1</label>
                    <input
                        type="text"
                        name="about_highlight_one"
                        id="about_highlight_one"
                        class="settings-input"
                        value="{{ old('about_highlight_one', $settings?->about_highlight_one) }}"
                        maxlength="255"
                        placeholder="e.g. Quality Solar Solutions">
                    @error('about_highlight_one')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="settings-field">
                    <label for="about_highlight_two" class="settings-label">Highlight 2</label>
                    <input
                        type="text"
                        name="about_highlight_two"
                        id="about_highlight_two"
                        class="settings-input"
                        value="{{ old('about_highlight_two', $settings?->about_highlight_two) }}"
                        maxlength="255"
                        placeholder="e.g. Reliable Customer Support">
                    @error('about_highlight_two')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="settings-field">
                    <label for="about_highlight_three" class="settings-label">Highlight 3</label>
                    <input
                        type="text"
                        name="about_highlight_three"
                        id="about_highlight_three"
                        class="settings-input"
                        value="{{ old('about_highlight_three', $settings?->about_highlight_three) }}"
                        maxlength="255"
                        placeholder="e.g. Trusted Solar Products">
                    @error('about_highlight_three')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="settings-field">
                    <label for="about_highlight_four" class="settings-label">
                        Highlight 4
                    </label>

                    <input
                        type="text"
                        name="about_highlight_four"
                        id="about_highlight_four"
                        class="settings-input"
                        value="{{ old('about_highlight_four', $settings?->about_highlight_four) }}"
                        maxlength="255"
                        placeholder="e.g. Expert Solar Installation">

                    @error('about_highlight_four')
                    <small class="settings-help" style="color: #dc2626;">
                        {{ $message }}
                    </small>
                    @enderror
                </div>

                <hr class="settings-divider">

                {{-- Footer Settings --}}
                <h4 class="settings-section-title">Footer Settings</h4>
                <p class="settings-section-description">
                    Manage the short description and copyright line displayed in the website footer.
                </p>

                <div class="settings-field">
                    <label for="footer_description" class="settings-label">Footer Description</label>
                    <textarea
                        name="footer_description"
                        id="footer_description"
                        class="settings-input"
                        rows="4"
                        maxlength="5000"
                        placeholder="Write a short description of your business">{{ old('footer_description', $settings?->footer_description) }}</textarea>
                    @error('footer_description')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                <div class="settings-field">
                    <label for="copyright_text" class="settings-label">Copyright Text</label>
                    <input
                        type="text"
                        name="copyright_text"
                        id="copyright_text"
                        class="settings-input"
                        value="{{ old('copyright_text', $settings?->copyright_text) }}"
                        maxlength="255"
                        placeholder="© 2026 Suryaa Enterprises. All rights reserved.">
                    @error('copyright_text')
                    <small class="settings-help" style="color: #dc2626;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Save Button --}}
                <div class="settings-actions">
                    <button
                        type="submit"
                        class="settings-save-button"
                        id="saveSettingsButton">
                        Save General Settings
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('websiteSettingsForm');
        const saveButton = document.getElementById('saveSettingsButton');
        const aboutImageInput = document.getElementById('about_image');
        const aboutImagePreviewWrap = document.getElementById('aboutImagePreviewWrap');
        const aboutImagePreview = document.getElementById('aboutImagePreview');

        if (aboutImageInput && aboutImagePreviewWrap && aboutImagePreview) {
            aboutImageInput.addEventListener('change', function() {
                const file = this.files && this.files[0];

                if (!file) {
                    aboutImagePreview.removeAttribute('src');
                    aboutImagePreviewWrap.style.display = 'none';
                    return;
                }

                if (!file.type.startsWith('image/')) {
                    this.value = '';
                    aboutImagePreview.removeAttribute('src');
                    aboutImagePreviewWrap.style.display = 'none';
                    return;
                }

                aboutImagePreview.src = URL.createObjectURL(file);
                aboutImagePreviewWrap.style.display = 'block';
            });
        }

        if (!form || !saveButton) {
            return;
        }

        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                form.reportValidity();
                return;
            }

            saveButton.disabled = true;
            saveButton.textContent = 'Saving...';
        });
    });
</script>

@endsection