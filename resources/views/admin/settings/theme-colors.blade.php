
@extends('admin.layout')

@section('page-title', 'Theme Colors')
@section('page-heading', 'Theme Colors')
@section('breadcrumb', 'Admin / Website Design / Theme Colors')

@section('content')
<div style="max-width:1000px;margin:0 auto;">

    <div style="margin-bottom:24px;">
        <h2 style="margin:0 0 8px;color:#022c22;font-size:25px;">
            Theme Colors
        </h2>
        <p style="margin:0;color:#78716c;font-size:13px;line-height:1.7;">
            Manage your website's primary, secondary, header and footer colors.
        </p>
    </div>

    @if(session('success'))
        <div style="padding:14px;margin-bottom:20px;background:#ecfdf5;color:#065f46;border:1px solid #a7f3d0;border-radius:9px;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="padding:14px;margin-bottom:20px;background:#fef2f2;color:#991b1b;border:1px solid #fecaca;border-radius:9px;">
            <strong>Please correct these errors:</strong>
            <ul style="margin:8px 0 0;padding-left:20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.theme-colors.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:18px;">

            @php
                $colors = [
                    'primary_color' => [
                        'label' => 'Primary Color',
                        'default' => '#047857',
                        'description' => 'Main brand and accent color.',
                    ],
                    'secondary_color' => [
                        'label' => 'Secondary Color',
                        'default' => '#022c22',
                        'description' => 'Secondary brand color.',
                    ],
                    'header_bg_color' => [
                        'label' => 'Header Background',
                        'default' => '#ffffff',
                        'description' => 'Background of the website header.',
                    ],
                    'header_text_color' => [
                        'label' => 'Header Text',
                        'default' => '#292524',
                        'description' => 'Text and links in the header.',
                    ],
                    'footer_bg_color' => [
                        'label' => 'Footer Background',
                        'default' => '#022c22',
                        'description' => 'Background of the website footer.',
                    ],
                    'footer_text_color' => [
                        'label' => 'Footer Text',
                        'default' => '#ffffff',
                        'description' => 'Text displayed in the footer.',
                    ],
                ];
            @endphp

            @foreach($colors as $name => $color)
                <div style="padding:20px;background:#fff;border:1px solid #e7e5e4;border-radius:13px;box-shadow:0 2px 8px rgba(0,0,0,.025);">
                    <label for="{{ $name }}" style="display:block;margin-bottom:8px;font-size:14px;font-weight:700;color:#292524;">
                        {{ $color['label'] }}
                    </label>

                    <p style="margin:0 0 14px;color:#78716c;font-size:12px;">
                        {{ $color['description'] }}
                    </p>

                    <div style="display:flex;align-items:center;gap:12px;">
                        <input
                            type="color"
                            id="{{ $name }}"
                            name="{{ $name }}"
                            value="{{ old($name, $settings?->{$name} ?: $color['default']) }}"
                            required
                            style="width:58px;height:45px;padding:3px;border:1px solid #d6d3d1;border-radius:8px;background:#fff;cursor:pointer;"
                        >

                        <input
                            type="text"
                            value="{{ old($name, $settings?->{$name} ?: $color['default']) }}"
                            readonly
                            aria-label="{{ $color['label'] }} hexadecimal value"
                            data-color-value="{{ $name }}"
                            style="width:120px;padding:10px;border:1px solid #d6d3d1;border-radius:7px;font-size:13px;"
                        >
                    </div>
                </div>
            @endforeach

        </div>

        <div style="display:flex;justify-content:flex-end;margin-top:22px;padding-top:20px;border-top:1px solid #e7e5e4;">
            <button type="submit" style="padding:12px 22px;background:#047857;color:white;border:0;border-radius:8px;font-weight:700;cursor:pointer;">
                Save Theme Colors
            </button>
        </div>
    </form>

    <div style="margin-top:25px;padding:20px;background:#fff;border:1px solid #e7e5e4;border-radius:13px;">
        <h3 style="margin:0 0 15px;font-size:16px;">Color Preview</h3>
        <div id="themePreview" style="overflow:hidden;border:1px solid #e7e5e4;border-radius:9px;">
            <div id="previewHeader" style="padding:18px;font-weight:700;">
                Suryaa Enterprises — Website Header
            </div>
            <div style="padding:25px 18px;background:#faf7f1;color:#292524;">
                <h3 style="margin:0 0 8px;">Solar Solutions for Your Home</h3>
                <p style="margin:0 0 16px;">Preview how your selected theme colors look.</p>
                <button id="previewButton" type="button" style="padding:10px 16px;border:0;border-radius:6px;color:white;">
                    Explore Products
                </button>
            </div>
            <div id="previewFooter" style="padding:18px;">
                Website Footer Preview
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const names = [
        'primary_color',
        'secondary_color',
        'header_bg_color',
        'header_text_color',
        'footer_bg_color',
        'footer_text_color'
    ];

    const defaults = {
        primary_color: '#047857',
        secondary_color: '#022c22',
        header_bg_color: '#ffffff',
        header_text_color: '#292524',
        footer_bg_color: '#022c22',
        footer_text_color: '#ffffff'
    };

    function updatePreview() {
        const colors = {};

        names.forEach(function (name) {
            const picker = document.getElementById(name);
            const valueBox = document.querySelector('[data-color-value="' + name + '"]');

            colors[name] = picker.value;
            valueBox.value = picker.value.toUpperCase();
        });

        document.getElementById('previewHeader').style.background = colors.header_bg_color;
        document.getElementById('previewHeader').style.color = colors.header_text_color;

        document.getElementById('previewFooter').style.background = colors.footer_bg_color;
        document.getElementById('previewFooter').style.color = colors.footer_text_color;

        document.getElementById('previewButton').style.background = colors.primary_color;
    }

    names.forEach(function (name) {
        document.getElementById(name).addEventListener('input', updatePreview);
    });

    updatePreview();
});
</script>
@endsection
