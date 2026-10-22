@extends('admin.layout')

@section('page-title', 'Add Testimonial')
@section('page-heading', 'Add Testimonial')
@section('breadcrumb', 'Admin / Testimonials / Add')

@section('content')

<div class="welcome-card">
    <h2>Add Testimonial</h2>
    <p>Add a customer review and select a rating from 1 to 5 stars.</p>
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

    <form action="{{ route('admin.testimonials.store') }}" method="POST">
        @csrf

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
                value="{{ old('customer_name') }}"
                placeholder="Enter customer name"
                maxlength="255"
                required
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px;">
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
                value="{{ old('designation') }}"
                placeholder="e.g. Business Owner"
                maxlength="255"
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px;">
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
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; font-size:14px; resize:vertical;">{{ old('review') }}</textarea>
        </div>

        {{-- Star Rating --}}
        <div style="margin-bottom:22px;">
            <label for="rating"
                style="display:block; margin-bottom:8px; font-size:13px; font-weight:600;">
                Star Rating <span style="color:#dc2626;">*</span>
            </label>

            @php
                $selectedRating = (string) old('rating', '5');
            @endphp

            <select
                id="rating"
                name="rating"
                required
                style="width:100%; padding:12px 14px; border:1px solid #d6d3d1; border-radius:8px; background:#fff; font-size:14px;">

                @foreach ([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $rating)
                    <option
                        value="{{ $rating }}"
                        {{ $selectedRating === (string) $rating ? 'selected' : '' }}>
                        {{ number_format($rating, 1) }} / 5
                        — {{ str_repeat('★', (int) $rating) }}{{ $rating - (int) $rating === 0.5 ? '½' : '' }}
                    </option>
                @endforeach
            </select>

            <small style="display:block; margin-top:7px; color:#78716c; font-size:12px;">
                Select a rating from 1 to 5, including half-star ratings such as 3.5 or 4.5.
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
                    {{ (string) old('is_active', '1') === '1' ? 'selected' : '' }}>
                    Active
                </option>

                <option value="0"
                    {{ (string) old('is_active', '1') === '0' ? 'selected' : '' }}>
                    Inactive
                </option>
            </select>

            <small style="display:block; margin-top:7px; color:#78716c; font-size:12px;">
                Active testimonials can be displayed on the website.
            </small>
        </div>

        {{-- Buttons --}}
        <div style="display:flex; flex-wrap:wrap; gap:10px;">
            <button
                type="submit"
                style="padding:12px 22px; border:0; border-radius:8px; background:#065f46; color:#fff; font-size:13px; font-weight:600;">
                Save Testimonial
            </button>

            <a
                href="{{ route('admin.testimonials.index') }}"
                style="display:inline-block; padding:12px 22px; border-radius:8px; background:#f5f5f4; color:#44403c; font-size:13px; font-weight:600;">
                Cancel
            </a>
        </div>

    </form>
</div>

@endsection
