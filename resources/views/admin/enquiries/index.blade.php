@extends('admin.layout')

@section('page-title', 'Customer Enquiries')
@section('page-heading', 'Customer Enquiries')
@section('breadcrumb', 'Admin / Enquiries')

@section('content')
<div class="enquiries-page">

    <div class="enquiries-header">
        <div>
            <h1>Customer Enquiries</h1>
            <p>View and manage customer queries from your website.</p>
        </div>

        <div class="total-card">
            <span>Total Enquiries</span>
            <strong id="enquiry-total-count">{{ $enquiries->total() }}</strong>
        </div>
    </div>

    @if (session('success'))
    <div class="enquiry-alert enquiry-alert-success">
        {{ session('success') }}
    </div>
    @endif

    @if ($errors->any())
    <div class="enquiry-alert enquiry-alert-error">
        <strong>Please check the following:</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="enquiries-card">
        <div class="enquiries-card-heading">
            <div>
                <h2>All Enquiries</h2>
                <p>Customer contact details and enquiry status</p>
            </div>
            <span class="record-count" id="enquiry-record-badge">{{ $enquiries->total() }} records</span>
        </div>

        @if ($enquiries->count() > 0)
        <div class="enquiries-table-wrap">
            <table class="enquiries-table">
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 170px;">Customer</th>
                        <th style="width: 190px;">Contact Details</th>
                        <th>Message</th>
                        <th style="width: 140px;">Date Received</th>
                        <th style="width: 170px;">Manage Status</th>
                        <th style="width: 80px;">Action</th>
                    </tr>
                </thead>

                <tbody id="enquiries-table-body">
                    @foreach ($enquiries as $enquiry)
                    <tr id="enquiry-row-{{ $enquiry->id }}">
                        <td class="serial-number">
                            {{ $enquiries->firstItem() + $loop->index }}
                        </td>

                        <td>
                            <div class="customer-name">
                                {{ $enquiry->name }}
                            </div>
                            <span class="customer-label">Customer</span>
                        </td>

                        <td>
                            <div class="contact-details">
                                <a href="tel:{{ $enquiry->phone }}">
                                    <span class="contact-icon">☎</span>
                                    {{ $enquiry->phone }}
                                </a>

                                @if ($enquiry->email)
                                <a href="mailto:{{ $enquiry->email }}"
                                    class="email-link">
                                    {{ $enquiry->email }}
                                </a>
                                @endif
                            </div>
                        </td>

                        <td class="message-cell">
                            <div class="enquiry-message">
                                {{ $enquiry->message }}
                            </div>
                        </td>

                        <td class="date-cell">
                            <strong>
                                {{ $enquiry->created_at->format('d M Y') }}
                            </strong>
                            <span>
                                {{ $enquiry->created_at->format('h:i A') }}
                            </span>
                        </td>

                        <td>
                            <form
                                action="{{ route('admin.enquiries.update-status', $enquiry) }}"
                                method="POST"
                                class="status-form"
                                data-id="{{ $enquiry->id }}">

                                @csrf
                                @method('PATCH')

                                <select
                                    name="status"
                                    class="status-select status-{{ $enquiry->status }}"
                                    aria-label="Select enquiry status">

                                    <option value="pending"
                                        @selected($enquiry->status === 'pending')>
                                        Pending
                                    </option>

                                    <option value="contacted"
                                        @selected($enquiry->status === 'contacted')>
                                        Contacted
                                    </option>

                                    <option value="completed"
                                        @selected($enquiry->status === 'completed')>
                                        Completed
                                    </option>
                                </select>

                                <button type="submit" class="status-button">
                                    Update Status
                                </button>
                            </form>
                        </td>

                        <td>
                            <form
                                action="{{ route('admin.enquiries.destroy', $enquiry) }}"
                                method="POST"
                                class="delete-enquiry-form"
                                data-customer="{{ $enquiry->name }}"
                                data-id="{{ $enquiry->id }}">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="btn-delete" title="Delete enquiry">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if ($enquiries->hasPages())
        <div class="enquiries-pagination">
            {{ $enquiries->links() }}
        </div>
        @endif

        @else
        <div class="enquiries-empty">
            <div class="empty-icon">✉</div>
            <h3>No enquiries yet</h3>
            <p>Customer enquiries will appear here after someone submits your website form.</p>
        </div>
        @endif
    </div>
</div>

<style>
    .enquiries-page {
        width: 100%;
        max-width: 100%;
        color: #18352d;
        box-sizing: border-box;
    }

    .enquiries-page * {
        box-sizing: border-box;
    }

    .enquiries-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 25px;
    }

    .enquiries-header h1 {
        font-size: 28px;
        line-height: 1.3;
        font-weight: 750;
        color: #18352d;
        margin: 0 0 7px;
    }

    .enquiries-header p {
        color: #78877e;
        font-size: 14px;
        margin: 0;
    }

    .total-card {
        min-width: 155px;
        padding: 15px 20px;
        background: #eaf5ed;
        border: 1px solid #d4e9d9;
        border-radius: 12px;
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .total-card span {
        font-size: 12px;
        color: #547260;
    }

    .total-card strong {
        font-size: 25px;
        color: #176b4d;
        line-height: 1.2;
    }

    .enquiry-alert {
        padding: 14px 17px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 13px;
    }

    .enquiry-alert-success {
        color: #176b4d;
        background: #e8f7ed;
        border: 1px solid #c9ebd3;
    }

    .enquiry-alert-error {
        color: #a52d2d;
        background: #fff0f0;
        border: 1px solid #f3d0d0;
    }

    .enquiry-alert ul {
        margin: 8px 0 0;
        padding-left: 20px;
    }

    .enquiries-card {
        width: 100%;
        min-width: 0;
        background: #fff;
        border: 1px solid #e6eee8;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 24px rgba(20, 60, 42, .045);
    }

    .enquiries-card-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 15px;
        flex-wrap: wrap;
        padding: 22px 24px;
        border-bottom: 1px solid #edf1ee;
    }

    .enquiries-card-heading h2 {
        font-size: 17px;
        font-weight: 750;
        color: #203b2f;
        margin: 0 0 5px;
    }

    .enquiries-card-heading p {
        margin: 0;
        font-size: 12px;
        color: #87938b;
    }

    .record-count {
        display: inline-flex;
        padding: 7px 11px;
        border-radius: 20px;
        background: #f0f6f2;
        color: #176b4d;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
    }

    .enquiries-table-wrap {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .enquiries-table {
        width: 100%;
        min-width: 1050px;
        border-collapse: collapse;
        text-align: left;
        margin: 0;
    }

    .enquiries-table thead th {
        background: #f8faf8;
        color: #748078;
        padding: 15px 16px;
        font-size: 10px;
        font-weight: 750;
        text-transform: uppercase;
        letter-spacing: .55px;
        white-space: nowrap;
        border-bottom: 1px solid #e9efea;
    }

    .enquiries-table tbody td {
        padding: 18px 16px;
        border-bottom: 1px solid #edf1ee;
        font-size: 12px;
        color: #46574d;
        vertical-align: middle;
    }

    .enquiries-table tbody tr:last-child td {
        border-bottom: none;
    }

    .enquiries-table tbody tr:hover {
        background: #fbfdfb;
    }

    .serial-number {
        color: #829087 !important;
        font-weight: 700;
        width: 45px;
    }

    .customer-name {
        font-weight: 750;
        color: #213b30;
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .customer-label {
        display: inline-block;
        margin-top: 5px;
        color: #87938b;
        font-size: 10px;
    }

    .contact-details {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 7px;
        min-width: 145px;
    }

    .contact-details a {
        color: #176b4d;
        text-decoration: none;
        font-weight: 650;
        overflow-wrap: anywhere;
    }

    .contact-details a:hover {
        text-decoration: underline;
    }

    .contact-icon {
        margin-right: 4px;
    }

    .contact-details .email-link {
        color: #718078;
        font-size: 11px;
        font-weight: 400;
    }

    .message-cell {
        min-width: 220px;
        max-width: 320px;
    }

    .enquiry-message {
        white-space: pre-wrap;
        overflow-wrap: anywhere;
        line-height: 1.65;
        color: #526258;
    }

    .date-cell {
        min-width: 115px;
        white-space: nowrap;
    }

    .date-cell strong,
    .date-cell span {
        display: block;
    }

    .date-cell strong {
        color: #405448;
        font-size: 12px;
        font-weight: 650;
    }

    .date-cell span {
        margin-top: 5px;
        color: #8a968e;
        font-size: 11px;
    }

    .status-form {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 140px;
        margin: 0;
    }

    .status-select {
        width: 100%;
        padding: 8px 10px;
        border-radius: 8px;
        border: 1px solid #dce7de;
        background-color: #fff;
        color: #355143;
        font-size: 12px;
        outline: none;
        cursor: pointer;
        transition: all .2s ease;
    }

    .status-select:focus {
        border-color: #176b4d;
        box-shadow: 0 0 0 3px rgba(23, 107, 77, .10);
    }

    .status-select.status-pending {
        background-color: #fff8e6;
        border-color: #f0dfac;
        color: #946b12;
    }

    .status-select.status-contacted {
        background-color: #edf4ff;
        border-color: #cbdcf8;
        color: #315da0;
    }

    .status-select.status-completed {
        background-color: #e8f7ed;
        border-color: #c9ebd3;
        color: #176b4d;
    }

    .status-button {
        width: 100%;
        padding: 8px 10px;
        border: 0;
        border-radius: 8px;
        background: #176b4d;
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        cursor: pointer;
        transition: background .2s ease;
    }

    .status-button:hover {
        background: #10543c;
    }

    .btn-delete {
        border: 0;
        background: #fff0ef;
        color: #bc4545;
        padding: 8px 11px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 650;
        cursor: pointer;
        transition: opacity .2s;
    }

    .btn-delete:hover {
        opacity: 0.85;
    }

    .enquiries-pagination {
        padding: 18px 22px;
        border-top: 1px solid #edf1ee;
        overflow-x: auto;
    }

    .enquiries-empty {
        padding: 65px 20px;
        text-align: center;
    }

    .empty-icon {
        width: 58px;
        height: 58px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        background: #e8f3eb;
        color: #176b4d;
        font-size: 25px;
        margin-bottom: 15px;
    }

    .enquiries-empty h3 {
        margin: 0 0 8px;
        color: #213b30;
        font-size: 18px;
    }

    .enquiries-empty p {
        max-width: 430px;
        margin: 0 auto;
        color: #7b8980;
        font-size: 13px;
        line-height: 1.7;
    }

    @media (max-width: 768px) {
        .enquiries-header h1 {
            font-size: 23px;
        }

        .total-card {
            min-width: 125px;
            padding: 12px 15px;
        }

        .enquiries-card-heading {
            padding: 18px;
        }

        .enquiries-table thead th,
        .enquiries-table tbody td {
            padding: 13px 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. AJAX Auto Status Update on Select Change
        document.querySelectorAll('.status-form').forEach(function(form) {
            const select = form.querySelector('.status-select');

            function updateStatusAjax(selectedStatus) {
                select.disabled = true;

                fetch(form.action, {
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
                            select.classList.remove('status-pending', 'status-contacted', 'status-completed');
                            select.classList.add('status-' + selectedStatus);

                            if (window.Swal) {
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message || 'Status updated!',
                                    showConfirmButton: false,
                                    timer: 1800
                                });
                            }
                        }
                    })
                    .catch(() => {
                        if (window.Swal) {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: 'Failed to update status',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        }
                    })
                    .finally(() => {
                        select.disabled = false;
                    });
            }

            select.addEventListener('change', function() {
                updateStatusAjax(this.value);
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                updateStatusAjax(select.value);
            });
        });

        // 2. AJAX Delete Enquiry with SweetAlert2
        document.querySelectorAll('.delete-enquiry-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const customer = form.dataset.customer || 'this enquiry';
                const enquiryId = form.dataset.id;
                const actionUrl = form.action;

                if (typeof Swal === 'undefined') {
                    if (confirm('Are you sure you want to delete the enquiry from ' + customer + '?')) {
                        form.submit();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Delete Enquiry?',
                    text: 'Are you sure you want to delete the enquiry from "' + customer + '"?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        const row = document.getElementById('enquiry-row-' + enquiryId);

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
                                        text: data.message || 'Enquiry deleted successfully.',
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