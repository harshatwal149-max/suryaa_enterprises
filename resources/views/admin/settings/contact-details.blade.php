@extends('admin.layout')

@section('page-title', 'Contact Details')
@section('page-heading', 'Contact Details')
@section('breadcrumb', 'Admin / Website Management / Contact Details')

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
        <h2>Contact Details</h2>
        <p>Manage the phone numbers, email, WhatsApp, business address and map shown on your website.</p>
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

    <form action="{{ route('admin.settings.contact.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <section class="settings-card">
            <div class="settings-card-header">
                <h3>Phone &amp; Email</h3>
                <p>Business contact numbers and customer communication details.</p>
            </div>
            <div class="settings-card-body">
                <div class="settings-grid">
                    <div class="settings-field">
                        <label for="phone_primary" class="settings-label">Primary Phone Number</label>
                        <input type="text" name="phone_primary" id="phone_primary" class="settings-input" value="{{ old('phone_primary', $settings?->phone_primary) }}" placeholder="Enter primary phone number">@error('phone_primary')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="phone_secondary" class="settings-label">Secondary Phone Number</label>
                        <input type="text" name="phone_secondary" id="phone_secondary" class="settings-input" value="{{ old('phone_secondary', $settings?->phone_secondary) }}" placeholder="Enter secondary phone number">@error('phone_secondary')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="phone_tertiary" class="settings-label">Third Phone Number</label>
                        <input type="text" name="phone_tertiary" id="phone_tertiary" class="settings-input" value="{{ old('phone_tertiary', $settings?->phone_tertiary) }}" placeholder="Enter third phone number">@error('phone_tertiary')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="email" class="settings-label">Business Email</label>
                        <input type="email" name="email" id="email" class="settings-input" value="{{ old('email', $settings?->email) }}" placeholder="name@example.com">@error('email')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="whatsapp_number" class="settings-label">WhatsApp Number</label>
                        <input type="text" name="whatsapp_number" id="whatsapp_number" class="settings-input" value="{{ old('whatsapp_number', $settings?->whatsapp_number) }}" placeholder="Include country code">@error('whatsapp_number')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                </div>
            </div>
        </section>
        <section class="settings-card">
            <div class="settings-card-header">
                <h3>Enquiry Email Notifications</h3>
                <p>Every new website enquiry is emailed to these addresses.</p>
            </div>
            <div class="settings-card-body">
                <div class="settings-field full">
                    <label for="enquiry_emails" class="settings-label">Send Enquiries To</label>
                    <textarea name="enquiry_emails" id="enquiry_emails" class="settings-input" rows="4" placeholder="owner@example.com&#10;sales@example.com">{{ implode("\n", (array) old('enquiry_emails', $settings?->enquiry_emails)) }}</textarea>
                    <small class="settings-help">One email per line or separated by commas, up to 5. If left empty, enquiries go to the Business Email above.</small>
                </div>
            </div>
        </section>
        <section class="settings-card">
            <div class="settings-card-header">
                <h3>Business Address</h3>
                <p>Address, tax details and Google Maps embed URL.</p>
            </div>
            <div class="settings-card-body">
                <div class="settings-grid">
                    <div class="settings-field full">
                        <label for="address" class="settings-label">Business Address</label>
                        <textarea name="address" id="address" class="settings-input" rows="4" placeholder="Enter complete business address">{{ old('address', $settings?->address) }}</textarea>@error('address')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field">
                        <label for="gst_number" class="settings-label">GST Number</label>
                        <input type="text" name="gst_number" id="gst_number" class="settings-input" value="{{ old('gst_number', $settings?->gst_number) }}" placeholder="Enter GST number">@error('gst_number')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                    <div class="settings-field full">
                        <label for="map_embed_url" class="settings-label">Google Maps Embed URL</label>
                        <input type="url" name="map_embed_url" id="map_embed_url" class="settings-input" value="{{ old('map_embed_url', $settings?->map_embed_url) }}" placeholder="Paste Google Maps embed URL">@error('map_embed_url')<small class="settings-error">{{ $message }}</small>@enderror
                    </div>
                </div>
                <small class="settings-help">For Google Maps, paste the embed URL only, not the full iframe HTML.</small>
            </div>
        </section>
        <div class="settings-actions">
            <button type="submit" class="settings-save-button">Save Contact Details</button>
        </div>
    </form>
</div>

@endsection