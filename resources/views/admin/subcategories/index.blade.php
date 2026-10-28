@extends('admin.layout')

@section('content')
<div class="categories-page">

    <div class="page-header">
        <div>
            <h1>Subcategories</h1>
            <p>Manage your product subcategories from here.</p>
        </div>

        <a href="{{ route('admin.subcategories.create') }}" class="btn-primary">
            + Add Subcategory
        </a>
    </div>

    @if(session('success'))
        <div
            id="subcategory-success-message"
            data-message="{{ session('success') }}"
            hidden>
        </div>
    @endif

    @if($errors->any())
        <div class="alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="table-card">
        <div class="table-heading">
            <h2>All Subcategories</h2>
            <span>{{ $subcategories->total() }} subcategories</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subcategory</th>
                        <th>Parent Category</th>
                        <th>Slug</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($subcategories as $subcategory)
                        <tr id="subcategory-row-{{ $subcategory->id }}">
                            <td>
                                {{ $subcategories->firstItem() + $loop->index }}
                            </td>

                            <td>
                                <div class="category-info">
                                    @if($subcategory->image)
                                        <img
                                            src="{{ asset('storage/' . $subcategory->image) }}"
                                            alt="{{ $subcategory->name }}"
                                            class="category-image">
                                    @else
                                        <div class="category-placeholder">
                                            {{ strtoupper(substr($subcategory->name, 0, 1)) }}
                                        </div>
                                    @endif

                                    <div>
                                        <strong>{{ $subcategory->name }}</strong>
                                        <small>Subcategory</small>
                                    </div>
                                </div>
                            </td>

                            <td>
                                {{ $subcategory->category?->name ?? 'Category unavailable' }}
                            </td>

                            <td>{{ $subcategory->slug }}</td>

                            <td>
                                <div class="status-wrapper">
                                    <span class="status-badge {{ $subcategory->is_active ? 'status-active' : 'status-inactive' }}">
                                        <span class="status-dot"></span>
                                        {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </td>

                            <td>
                                {{ $subcategory->created_at?->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                <div class="action-buttons">

                                    <form
                                        action="{{ route('admin.subcategories.toggle-status', $subcategory) }}"
                                        method="POST"
                                        class="status-form"
                                        data-name="{{ $subcategory->name }}"
                                        data-active="{{ $subcategory->is_active ? '1' : '0' }}">
                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn-status {{ $subcategory->is_active ? 'btn-deactivate' : 'btn-activate' }}">
                                            {{ $subcategory->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>

                                    <a
                                        href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                        class="btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.subcategories.destroy', $subcategory) }}"
                                        method="POST"
                                        class="delete-subcategory-form"
                                        data-name="{{ $subcategory->name }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="btn-delete">
                                            Delete
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7">
                                <div class="empty-state">
                                    <div class="empty-icon">▤</div>
                                    <h3>No subcategories yet</h3>
                                    <p>Create your first subcategory to get started.</p>

                                    <a
                                        href="{{ route('admin.subcategories.create') }}"
                                        class="btn-primary">
                                        + Add First Subcategory
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subcategories->hasPages())
            <div class="pagination-wrapper">
                {{ $subcategories->links() }}
            </div>
        @endif
    </div>
</div>

<style>
    .categories-page {
        color: #18352d;
        width: 100%;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
        margin-bottom: 26px;
        flex-wrap: wrap;
    }

    .page-header h1 {
        font-size: 28px;
        margin: 0 0 7px;
        font-weight: 750;
    }

    .page-header p {
        color: #718078;
        margin: 0;
        font-size: 14px;
    }

    .btn-primary {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #176b4d;
        color: white;
        padding: 12px 17px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: 0;
        cursor: pointer;
        white-space: nowrap;
        transition: background 0.2s ease;
    }

    .btn-primary:hover {
        background: #10543c;
        color: white;
    }

    .table-card {
        background: #fff;
        border: 1px solid #e7eee9;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(20, 60, 42, .035);
    }

    .table-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        padding: 21px 23px;
        border-bottom: 1px solid #edf1ee;
    }

    .table-heading h2 {
        font-size: 17px;
        margin: 0;
        font-weight: 700;
    }

    .table-heading span {
        font-size: 12px;
        color: #687a70;
        background: #f0f6f2;
        padding: 7px 10px;
        border-radius: 20px;
    }

    .table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .table-responsive table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        text-align: left;
    }

    .table-responsive th {
        background: #f8faf8;
        color: #748078;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: .6px;
        padding: 15px 18px;
        font-weight: 700;
        white-space: nowrap;
    }

    .table-responsive td {
        padding: 16px 18px;
        border-bottom: 1px solid #edf1ee;
        font-size: 13px;
        color: #46574d;
        vertical-align: middle;
    }

    .table-responsive tbody tr:last-child td {
        border-bottom: 0;
    }

    .table-responsive tbody tr:hover {
        background: #fbfdfb;
    }

    .category-info {
        display: flex;
        align-items: center;
        gap: 12px;
        min-width: 190px;
    }

    .category-info strong {
        display: block;
        color: #213b30;
        font-size: 13px;
        margin-bottom: 4px;
    }

    .category-info small {
        display: block;
        color: #87938b;
        font-size: 11px;
    }

    .category-image,
    .category-placeholder {
        width: 43px;
        height: 43px;
        flex-shrink: 0;
        border-radius: 10px;
        object-fit: cover;
    }

    .category-placeholder {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #e8f3eb;
        color: #176b4d;
        font-weight: 750;
        font-size: 17px;
    }

    .status-wrapper {
        display: flex;
        align-items: center;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        padding: 7px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .status-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        display: inline-block;
    }

    .status-active {
        background: #e3f6e9;
        color: #187443;
    }

    .status-active .status-dot {
        background: #198754;
    }

    .status-inactive {
        background: #fff0e8;
        color: #b85c30;
    }

    .status-inactive .status-dot {
        background: #dc6b35;
    }

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
        flex-wrap: wrap;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn-edit,
    .btn-delete,
    .btn-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        border-radius: 7px;
        padding: 8px 11px;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: opacity 0.2s, background 0.2s;
    }

    .btn-edit {
        background: #edf5ef;
        color: #176b4d;
    }

    .btn-delete {
        background: #fff0ef;
        color: #bc4545;
    }

    .btn-activate {
        background: #e3f6e9;
        color: #187443;
    }

    .btn-deactivate {
        background: #fff0e8;
        color: #b85c30;
    }

    .btn-edit:hover,
    .btn-delete:hover,
    .btn-status:hover {
        opacity: .8;
    }

    .alert-error {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #a52d2d;
        background: #fff0f0;
        border: 1px solid #f3d0d0;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 18px;
    }

    .empty-state {
        text-align: center;
        padding: 55px 20px;
    }

    .empty-icon {
        display: inline-flex;
        width: 55px;
        height: 55px;
        align-items: center;
        justify-content: center;
        background: #e8f3eb;
        color: #176b4d;
        border-radius: 15px;
        font-size: 27px;
        margin-bottom: 15px;
    }

    .empty-state h3 {
        margin: 0 0 8px;
        color: #213b30;
        font-size: 18px;
    }

    .empty-state p {
        color: #7b8980;
        font-size: 13px;
        margin: 0 0 20px;
    }

    .pagination-wrapper {
        padding: 18px 22px;
        border-top: 1px solid #edf1ee;
        overflow-x: auto;
    }

    .pagination-wrapper nav {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
    }

    @media (max-width: 600px) {
        .page-header h1 {
            font-size: 23px;
        }

        .page-header .btn-primary {
            width: 100%;
        }

        .table-heading {
            padding: 17px;
        }
    }
</style>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // Show Laravel success messages with SweetAlert2.
    const successElement = document.getElementById(
        'subcategory-success-message'
    );

    if (successElement && typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: successElement.dataset.message,
            confirmButtonColor: '#176b4d',
            confirmButtonText: 'OK'
        });
    }

    // Activate / Deactivate confirmation.
    document.querySelectorAll('.status-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const isActive = form.dataset.active === '1';
            const name = form.dataset.name || 'this subcategory';

            Swal.fire({
                title: isActive
                    ? 'Deactivate Subcategory?'
                    : 'Activate Subcategory?',

                text: isActive
                    ? 'Are you sure you want to deactivate "' + name + '"?'
                    : 'Are you sure you want to activate "' + name + '"?',

                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: isActive ? '#dc3545' : '#198754',
                cancelButtonColor: '#6c757d',

                confirmButtonText: isActive
                    ? 'Yes, Deactivate'
                    : 'Yes, Activate',

                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Delete confirmation.
    document.querySelectorAll('.delete-subcategory-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const name = form.dataset.name || 'this subcategory';

            Swal.fire({
                title: 'Delete Subcategory?',
                text: 'Are you sure you want to delete "' + name + '"? This action cannot be undone.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Delete',
                cancelButtonText: 'Cancel',
                reverseButtons: true,
                focusCancel: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

});
</script>
@endsection
