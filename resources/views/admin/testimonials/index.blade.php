@extends('admin.layout')

@section('page-title', 'Testimonials')
@section('page-heading', 'Testimonials')
@section('breadcrumb', 'Admin / Testimonials')

@section('content')

<div class="testimonials-page">

    <div class="page-header">
        <div>
            <h1>Testimonials</h1>
            <p>Manage customer reviews displayed on the website.</p>
        </div>

        <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
            + Add Testimonial
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
            <h2>All Testimonials</h2>
            <span>{{ $testimonials->total() }} testimonials</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 180px;">Customer</th>
                        <th style="width: 320px;">Review</th>
                        <th style="width: 130px;">Rating</th>
                        <th style="width: 100px;">Status</th>
                        <th style="width: 140px;">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($testimonials as $testimonial)
                        <tr id="testimonial-row-{{ $testimonial->id }}">
                            <td>{{ $testimonials->firstItem() + $loop->index }}</td>

                            <td>
                                <div class="customer-info">
                                    <strong>{{ $testimonial->customer_name }}</strong>
                                    <small>{{ $testimonial->designation ?: 'No designation' }}</small>
                                </div>
                            </td>

                            <td class="review-cell">
                                <div class="review-text" title="{{ $testimonial->review }}">
                                    {{ \Illuminate\Support\Str::limit($testimonial->review, 90) }}
                                </div>
                            </td>

                            <td>
                                @php
                                    $rating = floatval($testimonial->rating);
                                    $fullStars = floor($rating);
                                    $halfStar = ($rating - $fullStars) >= 0.5 ? 1 : 0;
                                    $emptyStars = max(0, 5 - $fullStars - $halfStar);
                                @endphp
                                <span class="rating">
                                    {{ str_repeat('★', $fullStars) }}{{ $halfStar ? '½' : '' }}{{ str_repeat('☆', $emptyStars) }}
                                </span>
                                <small class="rating-number">{{ number_format($rating, 1) }}/5</small>
                            </td>

                            <td>
                                @if($testimonial->is_active)
                                    <span class="status active">Active</span>
                                @else
                                    <span class="status inactive">Inactive</span>
                                @endif
                            </td>

                            <td>
                                <div class="action-buttons">
                                    <a
                                        href="{{ route('admin.testimonials.edit', $testimonial) }}"
                                        class="btn-edit">
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.testimonials.destroy', $testimonial) }}"
                                        method="POST"
                                        class="delete-testimonial-form"
                                        data-customer="{{ $testimonial->customer_name }}"
                                        data-id="{{ $testimonial->id }}">
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
                            <td colspan="6">
                                <div class="empty-state">
                                    <div class="empty-icon">☆</div>
                                    <h3>No testimonials yet</h3>
                                    <p>Add a genuine customer review to get started.</p>

                                    <a href="{{ route('admin.testimonials.create') }}" class="btn-primary">
                                        + Add First Testimonial
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
            <div class="pagination-wrapper">
                {{ $testimonials->links() }}
            </div>
        @endif
    </div>
</div>

<style>
.testimonials-page {
    width: 100%;
    color: #18352d;
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
    color: #fff;
    padding: 12px 17px;
    border-radius: 9px;
    text-decoration: none;
    font-weight: 600;
    font-size: 13px;
    border: 0;
    cursor: pointer;
    white-space: nowrap;
}

.btn-primary:hover {
    background: #10543c;
    color: #fff;
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

.table-heading > span {
    font-size: 12px;
    color: #687a70;
    background: #f0f6f2;
    padding: 7px 10px;
    border-radius: 20px;
    white-space: nowrap;
}

.table-responsive {
    width: 100%;
    overflow-x: auto;
}

.table-responsive table {
    width: 100%;
    min-width: 900px;
    border-collapse: collapse;
    text-align: left;
    table-layout: fixed;
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

.customer-info {
    min-width: 0;
    word-break: break-word;
}

.customer-info strong,
.customer-info small {
    display: block;
}

.customer-info strong {
    color: #213b30;
    margin-bottom: 5px;
}

.customer-info small {
    color: #87938b;
    font-size: 11px;
}

.review-cell {
    max-width: 320px;
    white-space: normal !important;
    word-break: break-all !important;
    overflow-wrap: anywhere !important;
}

.review-text {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.5;
    word-break: break-all;
    overflow-wrap: anywhere;
}

.rating {
    display: block;
    color: #e6a21a;
    font-size: 16px;
    letter-spacing: 1px;
    white-space: nowrap;
}

.rating-number {
    display: block;
    color: #87938b;
    font-size: 11px;
    margin-top: 3px;
}

.status {
    display: inline-block;
    padding: 6px 10px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 700;
    white-space: nowrap;
}

.status.active {
    background: #e3f6e9;
    color: #187443;
}

.status.inactive {
    background: #fff0e8;
    color: #b85c30;
}

.action-buttons {
    display: flex;
    align-items: center;
    gap: 8px;
    white-space: nowrap;
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
}

.btn-edit {
    background: #edf5ef;
    color: #176b4d;
}

.btn-delete {
    background: #fff0ef;
    color: #bc4545;
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
    font-size: 30px;
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
    document.querySelectorAll('.delete-testimonial-form').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            const customer = form.dataset.customer || 'this testimonial';
            const testimonialId = form.dataset.id;
            const actionUrl = form.action;

            if (typeof Swal === 'undefined') {
                if (confirm('Delete the testimonial from ' + customer + '?')) {
                    form.submit();
                }
                return;
            }

            Swal.fire({
                title: 'Delete Testimonial?',
                text: 'Are you sure you want to delete the review from ' + customer + '?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel',
                reverseButtons: true
            }).then(function (result) {
                if (result.isConfirmed) {
                    const row = document.getElementById('testimonial-row-' + testimonialId);

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
                                text: data.message || 'Testimonial deleted successfully.',
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