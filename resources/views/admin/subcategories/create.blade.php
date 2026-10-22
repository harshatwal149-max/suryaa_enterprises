
@extends('admin.layout')

@section('page-title', 'Add Subcategory')
@section('page-heading', 'Add Subcategory')
@section('breadcrumb', 'Admin / Subcategories / Add')

@section('content')
<div class="info-card">
    <h3>Add New Subcategory</h3>

    @if ($errors->any())
        <div class="flash-message flash-error">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.subcategories.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:18px;">
            <label for="category_id">Parent Category *</label>

            <select id="category_id"
                    name="category_id"
                    required
                    style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">
                <option value="">-- Select Category --</option>

                @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom:18px;">
            <label for="name">Subcategory Name *</label>
            <input type="text"
                   id="name"
                   name="name"
                   value="{{ old('name') }}"
                   required
                   maxlength="255"
                   placeholder="Enter subcategory name"
                   style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label for="image">Subcategory Image (optional)</label>
            <input type="file"
                   id="image"
                   name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">

            <small style="display:block;margin-top:6px;color:#78716c;">
                Allowed formats: JPG, JPEG, PNG, WEBP. Maximum size: 2 MB.
            </small>
        </div>

        <button type="submit"
                style="background:#022c22;color:white;padding:12px 20px;border:0;border-radius:8px;cursor:pointer;">
            Save Subcategory
        </button>

        <a href="{{ route('admin.subcategories.index') }}"
           style="display:inline-block;margin-left:12px;color:#57534e;">
            Cancel
        </a>
    </form>
</div>
@endsection
