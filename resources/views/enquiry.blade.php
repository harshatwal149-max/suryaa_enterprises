@extends('layouts.frontend')

@section('title', 'Send an Enquiry')

@section('content')
<section class="enquiry-page">
    <div class="enquiry-container">
        <div class="enquiry-intro">
            <span>LET'S DISCUSS YOUR PROJECT</span>
            <h1>Tell Us What Your Business Needs.</h1>
            <p>
                Share your product requirements or project details.
                Our team will help you find the right solution.
            </p>
        </div>

        @if (session('success'))
            <div class="enquiry-alert success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="enquiry-alert error">
                Please check the form fields and try again.
            </div>
        @endif

        <form action="{{ route('enquiry.store') }}"
              method="POST"
              class="enquiry-form">
            @csrf

            <div class="enquiry-fields">

                <div class="enquiry-field">
                    <label for="name">Full Name *</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        placeholder="Enter your full name"
                        maxlength="100"
                        required>
                    @error('name')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="enquiry-field">
                    <label for="phone">Phone Number *</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        placeholder="Enter your phone number"
                        maxlength="20"
                        required>
                    @error('phone')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="enquiry-field full-width">
                    <label for="email">
                        Email Address <span>(Optional)</span>
                    </label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Enter your email address"
                        maxlength="255">
                    @error('email')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                {{-- Product of Interest --}}
                <div class="enquiry-field full-width">
                    <label for="product">
                        Product of Interest
                        <span>(Optional)</span>
                    </label>
                    <input
                        type="text"
                        id="product"
                        name="product"
                        value="{{ old('product', request('product')) }}"
                        placeholder="Which product are you enquiring about?"
                        maxlength="255">
                    @error('product')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

                <div class="enquiry-field full-width">
                    <label for="message">Your Requirements *</label>
                    <textarea
                        id="message"
                        name="message"
                        rows="5"
                        maxlength="5000"
                        placeholder="Tell us about your product or project requirements..."
                        required>{{ old('message') }}</textarea>
                    @error('message')
                        <small>{{ $message }}</small>
                    @enderror
                </div>

            </div>

            <button type="submit" class="enquiry-submit">
                Submit Enquiry <span aria-hidden="true">→</span>
            </button>

            <p class="enquiry-note">* Required fields</p>
        </form>
    </div>
</section>
@endsection

@push('styles')
<style>
    .enquiry-page {
        padding: 70px 20px 90px;
        background: #f4f7f8;
    }

    .enquiry-container {
        max-width: 900px;
        margin: 0 auto;
        padding: 45px;
        background: #fff;
        border: 1px solid #e1e8eb;
        border-radius: 22px;
        box-shadow: 0 12px 40px rgba(20, 55, 65, 0.08);
    }

    .enquiry-intro {
        margin-bottom: 32px;
    }

    .enquiry-intro > span {
        color: #08745f;
        font-size: 12px;
        font-weight: 800;
        letter-spacing: 2px;
    }

    .enquiry-intro h1 {
        margin: 12px 0;
        color: #183f49;
        font-size: clamp(28px, 4vw, 42px);
        line-height: 1.2;
        font-weight: 800;
    }

    .enquiry-intro p {
        color: #65757b;
        line-height: 1.8;
    }

    .enquiry-fields {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 23px;
    }

    .enquiry-field {
        display: flex;
        flex-direction: column;
        gap: 9px;
        min-width: 0;
    }

    .enquiry-field.full-width {
        grid-column: 1 / -1;
    }

    .enquiry-field label {
        color: #243f47;
        font-size: 14px;
        font-weight: 700;
    }

    .enquiry-field label span {
        color: #7a898e;
        font-weight: 400;
    }

    .enquiry-field input,
    .enquiry-field textarea {
        width: 100%;
        box-sizing: border-box;
        padding: 14px 16px;
        color: #233e46;
        background: #fbfcfc;
        border: 1px solid #d8e1e4;
        border-radius: 10px;
        outline: none;
        font: inherit;
        transition: border-color .2s, box-shadow .2s;
    }

    .enquiry-field input:focus,
    .enquiry-field textarea:focus {
        border-color: #08745f;
        box-shadow: 0 0 0 3px rgba(8, 116, 95, .1);
    }

    .enquiry-field textarea {
        resize: vertical;
        min-height: 140px;
    }

    .enquiry-field small {
        color: #c0392b;
    }

    .enquiry-submit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 22px;
        margin-top: 28px;
        padding: 15px 24px;
        color: #fff;
        background: #005443;
        border: 0;
        border-radius: 9px;
        font-size: 15px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s, transform .2s;
    }

    .enquiry-submit:hover {
        background: #08745f;
        transform: translateY(-2px);
    }

    .enquiry-note {
        margin-top: 14px;
        color: #7a898e;
        font-size: 12px;
    }

    .enquiry-alert {
        margin-bottom: 22px;
        padding: 14px 17px;
        border-radius: 9px;
    }

    .enquiry-alert.success {
        color: #176344;
        background: #eaf7ef;
    }

    .enquiry-alert.error {
        color: #963c35;
        background: #fff0ef;
    }

    @media (max-width: 600px) {
        .enquiry-page {
            padding: 35px 14px 55px;
        }

        .enquiry-container {
            padding: 25px 18px;
            border-radius: 16px;
        }

        .enquiry-fields {
            grid-template-columns: 1fr;
            gap: 19px;
        }

        .enquiry-field.full-width {
            grid-column: auto;
        }

        .enquiry-submit {
            width: 100%;
        }
    }
</style>
@endpush
