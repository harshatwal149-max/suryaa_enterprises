@extends('admin.layout')

@section('page-title', 'Social Media')
@section('page-heading', 'Social Media')
@section('breadcrumb', 'Admin / Website Management / Social Media')

@section('content')
<style>
    .settings-page {
        width: 100%;
        max-width: 1100px;
        margin: 0 auto
    }

    .settings-intro {
        margin-bottom: 24px
    }

    .settings-intro h2 {
        margin: 0 0 8px;
        color: #022c22;
        font-size: 25px;
        font-weight: 700
    }

    .settings-intro p {
        margin: 0;
        color: #78716c;
        font-size: 13px;
        line-height: 1.7
    }

    .settings-alert {
        padding: 14px 17px;
        margin-bottom: 20px;
        border-radius: 10px;
        font-size: 13px;
        line-height: 1.7
    }

    .settings-alert.success {
        color: #065f46;
        background: #ecfdf5;
        border: 1px solid #a7f3d0
    }

    .settings-alert.error {
        color: #991b1b;
        background: #fef2f2;
        border: 1px solid #fecaca
    }

    .settings-card {
        overflow: hidden;
        background: #fff;
        border: 1px solid #e7e5e4;
        border-radius: 15px;
        box-shadow: 0 3px 12px rgba(0, 0, 0, .035);
        margin-bottom: 22px
    }

    .settings-card-header {
        padding: 21px 25px;
        border-bottom: 1px solid #e7e5e4
    }

    .settings-card-header h3 {
        margin: 0 0 5px;
        color: #1c1917;
        font-size: 17px;
        font-weight: 700
    }

    .settings-card-header p {
        margin: 0;
        color: #78716c;
        font-size: 12px;
        line-height: 1.6
    }

    .settings-card-body {
        padding: 25px
    }

    .settings-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 20px
    }

    .settings-field {
        min-width: 0;
        margin-bottom: 6px
    }

    .settings-field.full {
        grid-column: 1/-1
    }

    .settings-label {
        display: block;
        margin-bottom: 8px;
        color: #292524;
        font-size: 13px;
        font-weight: 700
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
        box-sizing: border-box
    }

    .settings-input:focus {
        border-color: #047857;
        box-shadow: 0 0 0 3px rgba(4, 120, 87, .10)
    }

    textarea.settings-input {
        resize: vertical;
        line-height: 1.6
    }

    .settings-help {
        display: block;
        margin-top: 7px;
        color: #78716c;
        font-size: 11px;
        line-height: 1.7
    }

    .settings-error {
        display: block;
        margin-top: 6px;
        color: #dc2626;
        font-size: 12px
    }

    .settings-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        padding-top: 22px;
        margin-top: 22px;
        border-top: 1px solid #e7e5e4
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
        cursor: pointer
    }

    .settings-save-button:hover {
        background: #065f46
    }

    @media(max-width:650px) {
        .settings-intro h2 {
            font-size: 22px
        }

        .settings-card-header,
        .settings-card-body {
            padding: 18px
        }

        .settings-grid {
            grid-template-columns: 1fr
        }

        .settings-field.full {
            grid-column: auto
        }

        .settings-actions {
            justify-content: stretch
        }

        .settings-save-button {
            width: 100%
        }
    }
</style>
<div class="settings-page">
    <div class="settings-intro">
        <h2>Social Media</h2>
        <p>Manage the social media profile links displayed on your website.</p>
    </div>

    @if (session('success'))
    <div class="settings-alert success">
        <strong>Success!</strong> {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="settings-alert error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
    <div class="settings-alert error">
        <strong>Please fix the following errors:</strong>
        <ul style="margin:8px 0 0;padding-left:20px;">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
    </div>
    @endif

    <form action="{{ route('admin.settings.social.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section class="settings-card">
            <div class="settings-card-header">
                <h3>Social Media Profiles</h3>
                <p>Add full profile URLs. Leave a field empty if your business does not use that platform.</p>
            </div>
            <div class="settings-card-body">
                <div class="settings-grid">
                    <div class="settings-field">
                        <label for="facebook_url" class="settings-label">Facebook Page URL</label>
                        <input type="url" name="facebook_url" id="facebook_url" class="settings-input" value="{{ old('facebook_url', $settings?->facebook_url) }}" placeholder="https://www.facebook.com/your-page">@error('facebook_url')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="instagram_url" class="settings-label">Instagram Profile URL</label>
                        <input type="url" name="instagram_url" id="instagram_url" class="settings-input" value="{{ old('instagram_url', $settings?->instagram_url) }}" placeholder="https://www.instagram.com/your-profile">@error('instagram_url')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="youtube_url" class="settings-label">YouTube Channel URL</label>
                        <input type="url" name="youtube_url" id="youtube_url" class="settings-input" value="{{ old('youtube_url', $settings?->youtube_url) }}" placeholder="https://www.youtube.com/@your-channel">@error('youtube_url')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="linkedin_url" class="settings-label">LinkedIn Company URL</label>
                        <input type="url" name="linkedin_url" id="linkedin_url" class="settings-input" value="{{ old('linkedin_url', $settings?->linkedin_url) }}" placeholder="https://www.linkedin.com/company/your-company">@error('linkedin_url')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <div class="settings-help">Use complete URLs beginning with https://. These links can be displayed by your website theme wherever social links are supported.</div>
            </div>
        </section>
        <div class="settings-actions">
            <button type="submit" class="settings-save-button">Save Social Media</button>
        </div>
    </form>
</div>

@endsection