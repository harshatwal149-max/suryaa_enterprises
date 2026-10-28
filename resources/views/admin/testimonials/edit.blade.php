
@extends('admin.layout')

@section('page-title', 'Edit Testimonial')
@section('page-heading', 'Edit Testimonial')
@section('breadcrumb', 'Admin / Testimonials / Edit')

@section('content')

<div class="welcome-card">
    <h2>Edit Testimonial</h2>
    <p>Update the customer's review, star rating and status.</p>
</div>

@if ($errors->any())
    <div class="flash-message flash-error">
        <strong>Please fix the following errors:</strong>
        <ul style="margin:10px 0 0; padding-left:20px;">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div style="max-width:900px; background:#fff; border:1px solid #e7e5e4; border-radius:14px; padding:25px;">

    <form action="{{ route('admin.testimonials.update', $testimonial) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Customer Name --}}
        <div style="margin-bottom:22px;">
            <label for="customer_name"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Customer Name <span style="color:#dc2626;">*</span>
            </label>

            <input
                type="text"
                id="customer_name"
                name="customer_name"
                value="{{ old('customer_name', $testimonial->customer_name) }}"
                placeholder="Enter customer name"
                maxlength="255"
                required
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px;">

            @error('customer_name')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        {{-- Designation --}}
        <div style="margin-bottom:22px;">
            <label for="designation"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Designation
            </label>

            <input
                type="text"
                id="designation"
                name="designation"
                value="{{ old('designation', $testimonial->designation) }}"
                placeholder="e.g. Business Owner"
                maxlength="255"
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px;">

            @error('designation')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        {{-- Customer Review --}}
        <div style="margin-bottom:22px;">
            <label for="review"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Customer Review <span style="color:#dc2626;">*</span>
            </label>

            <textarea
                id="review"
                name="review"
                rows="6"
                maxlength="5000"
                required
                placeholder="Write the customer's review..."
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px; resize:vertical;">{{ old('review', $testimonial->review) }}</textarea>

            @error('review')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror
        </div>

        {{-- Star Rating --}}
        <div style="margin-bottom:22px;">
            <label for="rating"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Star Rating <span style="color:#dc2626;">*</span>
            </label>

            @php
                $ratingOptions = [
                    '1.0' => '1.0 / 5 — ★',
                    '1.5' => '1.5 / 5 — ★½',
                    '2.0' => '2.0 / 5 — ★★',
                    '2.5' => '2.5 / 5 — ★★½',
                    '3.0' => '3.0 / 5 — ★★★',
                    '3.5' => '3.5 / 5 — ★★★½',
                    '4.0' => '4.0 / 5 — ★★★★',
                    '4.5' => '4.5 / 5 — ★★★★½',
                    '5.0' => '5.0 / 5 — ★★★★★',
                ];

                $selectedRating = number_format(
                    (float) old('rating', $testimonial->rating),
                    1,
                    '.',
                    ''
                );
            @endphp

            <select
                id="rating"
                name="rating"
                required
                class="@error('rating') is-invalid @enderror"
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; background:#fff; font-size:14px;">

                @foreach ($ratingOptions as $value => $label)
                    <option
                        value="{{ $value }}"
                        {{ $selectedRating === $value ? 'selected' : '' }}>
                        {{ $label }}
                    </option>
                @endforeach
            </select>

            @error('rating')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror

            <small style="display:block; margin-top:7px; color:#78716c; font-size:12px;">
                Select a rating between 1 and 5, including half-star ratings such as 3.5 or 4.5.
            </small>
        </div>

        {{-- Status --}}
        <div style="margin-bottom:25px;">
            <label for="is_active"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Status <span style="color:#dc2626;">*</span>
            </label>

            <select
                id="is_active"
                name="is_active"
                required
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; background:#fff; font-size:14px;">

                <option value="1"
                    {{ (string) old('is_active', (int) $testimonial->is_active) === '1' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0"
                    {{ (string) old('is_active', (int) $testimonial->is_active) === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            @error('is_active')
                <small style="display:block; margin-top:6px; color:#dc2626;">
                    {{ $message }}
                </small>
            @enderror

            <small style="display:block; margin-top:7px; color:#78716c; font-size:12px;">
                Inactive testimonials should not be displayed on the website.
            </small>
        </div>

        {{-- Buttons --}}
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <button
                type="submit"
                style="padding:12px 22px; border:0; border-radius:8px; background:#065f46; color:#fff; font-size:13px; font-weight:600; cursor:pointer;">
                Update Testimonial
            </button>

            <a
                href="{{ route('admin.testimonials.index') }}"
                style="display:inline-block; padding:12px 22px; border-radius:8px; background:#f5f5f4; color:#44403c; font-size:13px; font-weight:600; text-decoration:none;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection
