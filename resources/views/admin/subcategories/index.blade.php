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
        <div class="alert-success">{{ session('success') }}</div>
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
            <span id="subcategory-total-badge">{{ $subcategories->total() }} subcategories</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Subcategory</th>
                        <th>Parent Category</th>
                        <th>Slug</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody id="subcategory-table-body">
                    @forelse($subcategories as $subcategory)
                        <tr id="subcategory-row-{{ $subcategory->id }}">
                            <td>{{ $subcategories->firstItem() + $loop->index }}</td>

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
                                {{ $subcategory->created_at?->format('d M Y') ?? '—' }}
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a
                                        href="{{ route('admin.subcategories.edit', $subcategory) }}"
                                        class="btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.subcategories.destroy', $subcategory) }}"
                                        method="POST"
                                        class="delete-subcategory-form"
                                        data-subcategory="{{ $subcategory->name }}"
                                        data-id="{{ $subcategory->id }}">
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
                        <tr id="empty-subcategories-row">
                            <td colspan="6">
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
        min-width: 850px;
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

    .action-buttons {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons form {
        margin: 0;
    }

    .btn-edit,
    .btn-delete {
        display: inline-block;
        border: 0;
        border-radius: 7px;
        padding: 8px 11px;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        text-decoration: none;
        white-space: nowrap;
        transition: opacity 0.2s;
    }

    .btn-edit {
        background: #edf5ef;
        color: #176b4d;
    }

    .btn-delete {
        background: #fff0ef;
        color: #bc4545;
    }

    .btn-edit:hover,
    .btn-delete:hover {
        opacity: 0.85;
    }

    .alert-success,
    .alert-error {
        padding: 13px 16px;
        border-radius: 9px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .alert-success {
        color: #176b4d;
        background: #e8f7ed;
        border: 1px solid #c9ebd3;
    }

    .alert-error {
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-subcategory-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const name = form.dataset.subcategory || 'this subcategory';
            const subcategoryId = form.dataset.id;
            const actionUrl = form.action;

            if (typeof Swal === 'undefined') {
                if (confirm('Are you sure you want to delete "' + name + '"?')) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Delete Subcategory?',
                text: 'Are you sure you want to delete "' + name + '"?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    const row = document.getElementById('subcategory-row-' + subcategoryId);

                    fetch(actionUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams(new FormData(form))
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (row) {
                                row.style.transition = 'opacity 0.35s ease';
                                row.style.opacity = '0';
                                setTimeout(() => row.remove(), 350);
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Deleted!',
                                text: data.message || 'Subcategory deleted successfully.',
                                timer: 1600,
                                showConfirmButton: false
                            });
                        } else {
                            throw new Error(data.message || 'Failed to delete');
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: err.message || 'Something went wrong.'
                        });
                    });
                }
            });
        });
    });
});
</script>
@endsection