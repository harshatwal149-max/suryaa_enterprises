@extends('layouts.frontend')

@section('title', 'Write a Review')

@section('content')
<style>
    .review-page {
        background: #f7f8f5;
        padding: 55px 20px 65px;
    }

    .review-card {
        max-width: 760px;
        margin: 0 auto;
        padding: 38px;
        background: #fff;
        border: 1px solid #e5e9e2;
        border-radius: 16px;
        box-shadow: 0 12px 35px rgba(0, 0, 0, .07);
    }

    .review-heading {
        color: #104c32;
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 10px;
    }

    .review-description {
        color: #66736b;
        line-height: 1.7;
        margin-bottom: 28px;
    }

    .review-form-group {
        margin-bottom: 22px;
    }

    .review-form-group label {
        display: block;
        color: #26372d;
        font-weight: 600;
        margin-bottom: 8px;
    }

    .review-input,
    .review-select,
    .review-textarea {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px 14px;
        color: #26372d;
        background: #fff;
        border: 1px solid #cfd8d0;
        border-radius: 8px;
        font: inherit;
        font-size: 15px;
        outline: none;
        transition: border-color .2s, box-shadow .2s;
    }

    .review-input:focus,
    .review-select:focus,
    .review-textarea:focus {
        border-color: #18794e;
        box-shadow: 0 0 0 3px rgba(24, 121, 78, .12);
    }

    .review-textarea {
        min-height: 150px;
        resize: vertical;
    }

    .review-error {
        display: block;
        margin-top: 6px;
        color: #c62828;
        font-size: 13px;
    }

    .review-submit {
        display: inline-block;
        width: 100%;
        padding: 14px 22px;
        color: #17230d;
        background: #dfa83f;
        border: 0;
        border-radius: 9px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .review-submit:hover {
        background: #edb94f;
        transform: translateY(-1px);
    }

    .review-note {
        margin: 17px 0 0;
        text-align: center;
        color: #66736b;
        font-size: 13px;
        line-height: 1.6;
    }

    .review-alert {
        padding: 13px 16px;
        margin-bottom: 22px;
        border-radius: 8px;
        line-height: 1.5;
    }

    .review-alert-success {
        color: #155b36;
        background: #e8f6ed;
        border: 1px solid #b9e3c7;
    }

    .review-alert-error {
        color: #842029;
        background: #fbe9eb;
        border: 1px solid #f1c1c6;
    }

    @media (max-width: 600px) {
        .review-page {
            padding: 30px 14px 40px;
        }

        .review-card {
            padding: 24px 18px;
        }

        .review-heading {
            font-size: 26px;
        }
    }
</style>

<section class="review-page">
    <div class="review-card">

        <h1 class="review-heading">Share Your Experience</h1>

        <p class="review-description">
            Your feedback matters to us. Tell us about your experience
            with Suryaa Enterprises.
        </p>

        @if (session('success'))
        <div class="review-alert review-alert-success" role="status">
            {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="review-alert review-alert-error" role="alert">
            Please correct the errors below and submit the form again.
        </div>
        @endif

        <form action="{{ route('testimonials.store') }}" method="POST">
            @csrf

            <div class="review-form-group">
                <label for="customer_name">Your Name *</label>

                <input
                    type="text"
                    name="customer_name"
                    id="customer_name"
                    class="review-input"
                    value="{{ old('customer_name') }}"
                    maxlength="255"
                    autocomplete="name"
                    required>

                @error('customer_name')
                <span class="review-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="review-form-group">
                <label for="designation">Designation (Optional)</label>

                <input
                    type="text"
                    name="designation"
                    id="designation"
                    class="review-input"
                    value="{{ old('designation') }}"
                    maxlength="255"
                    autocomplete="organization-title">

                @error('designation')
                <span class="review-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="review-form-group">
                <label for="rating">Your Rating *</label>

                <select
                    name="rating"
                    id="rating"
                    class="review-select"
                    required>
                    <option value="">Select a rating</option>

                    @foreach ([1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5] as $rating)
                    <option
                        value="{{ $rating }}"
                        @selected((string) old('rating')===(string) $rating)>
                        {{ number_format($rating, 1) }} / 5 Stars
                    </option>
                    @endforeach
                </select>

                @error('rating')
                <span class="review-error">{{ $message }}</span>
                @enderror
            </div>

            <div class="review-form-group">
                <label for="review">Your Review *</label>

                <textarea
                    name="review"
                    id="review"
                    class="review-textarea"
                    rows="5"
                    maxlength="2000"
                    placeholder="Write about your experience..."
                    required>{{ old('review') }}</textarea>

                @error('review')
                <span class="review-error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="review-submit">
                Submit Review
            </button>

            <p class="review-note">
                Your review will be checked by our team before it is
                published on the website.
            </p>
        </form>
    </div>
</section>
@endsection