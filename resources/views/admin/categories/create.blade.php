
@extends('admin.layout')

@section('page-title', 'Add Category')
@section('page-heading', 'Add Category')
@section('breadcrumb', 'Admin / Categories / Add')

@section('content')
<div class="info-card">
    <h3>Add New Category</h3>

    @if ($errors->any())
        <div class="flash-message flash-error">
            <ul style="margin:0; padding-left:20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.categories.store') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf

        <div style="margin-bottom:18px;">
            <label for="name">Category Name *</label>
            <input type="text" id="name" name="name"
                   value="{{ old('name') }}" required
                   style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label for="slug">Slug (optional)</label>
            <input type="text" id="slug" name="slug"
                   value="{{ old('slug') }}"
                   style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">
        </div>

        <div style="margin-bottom:18px;">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4"
                      style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">{{ old('description') }}</textarea>
        </div>

        <div style="margin-bottom:18px;">
            <label for="image">Category Image (optional)</label>
            <input type="file" id="image" name="image"
                   accept=".jpg,.jpeg,.png,.webp"
                   style="display:block;width:100%;padding:12px;margin-top:8px;border:1px solid #e7e5e4;border-radius:8px;">
        </div>

        <div style="margin-bottom:22px;">
            <label>
                <input type="checkbox" name="is_active" value="1"
                    {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                Active category
            </label>
        </div>

        <button type="submit"
                style="background:#022c22;color:white;padding:12px 20px;border:0;border-radius:8px;cursor:pointer;">
            Save Category
        </button>

        <a href="{{ route('admin.categories.index') }}"
           style="display:inline-block;margin-left:12px;color:#57534e;">
            Cancel
        </a>
    </form>
</div>
@endsection
