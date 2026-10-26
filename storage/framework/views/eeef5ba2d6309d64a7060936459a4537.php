

<?php $__env->startSection('page-title', 'Hero Slides'); ?>
<?php $__env->startSection('page-heading', 'Hero Slides'); ?>
<?php $__env->startSection('breadcrumb', 'Admin / Hero Slides'); ?>

<?php $__env->startSection('content'); ?>

<div class="slides-page">

    <div class="page-header">
        <div>
            <h1>Hero Slides</h1>
            <p>Manage homepage banners, headings and buttons.</p>
        </div>

        <a href="<?php echo e(route('admin.hero-slides.create')); ?>" class="btn-primary">
            + Add Slide
        </a>
    </div>

    <?php if(session('success')): ?>
    <div class="alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
    <div class="alert-error">
        <ul>
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <?php endif; ?>

    <div class="table-card">

        <div class="table-heading">
            <h2>All Hero Slides</h2>
            <span id="slides-total-badge"><?php echo e($slides->total()); ?> slides</span>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px;">#</th>
                        <th style="width: 150px;">Banner</th>
                        <th>Heading / Content</th>
                        <th style="width: 90px;">Order</th>
                        <th style="width: 110px;">Status</th>
                        <th style="width: 150px;">Actions</th>
                    </tr>
                </thead>

                <tbody id="slides-table-body">
                    <?php $__empty_1 = true; $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr id="slide-row-<?php echo e($slide->id); ?>">
                        <td><?php echo e($slides->firstItem() + $loop->index); ?></td>

                        <td>
                            <img
                                src="<?php echo e(asset('storage/' . $slide->image_path)); ?>"
                                alt="<?php echo e($slide->heading ?: 'Hero banner'); ?>"
                                class="slide-image">
                        </td>

                        <td>
                            <div class="slide-info">
                                <strong>
                                    <?php echo e($slide->heading ?: 'No heading'); ?>

                                </strong>

                                <small>
                                    <?php echo e(\Illuminate\Support\Str::limit($slide->description, 65) ?: 'No description'); ?>

                                </small>

                                <?php if($slide->button_text): ?>
                                <small class="button-detail">
                                    Button: <?php echo e($slide->button_text); ?>

                                </small>
                                <?php endif; ?>
                            </div>
                        </td>

                        <td><?php echo e($slide->sort_order); ?></td>

                        <td>
                            <button
                                type="button"
                                class="slide-status-btn status <?php echo e($slide->is_active ? 'active' : 'inactive'); ?>"
                                data-url="<?php echo e(route('admin.hero-slides.toggle-status', $slide)); ?>"
                                title="Click to toggle status">
                                <?php echo e($slide->is_active ? 'Active' : 'Inactive'); ?>

                            </button>
                        </td>

                        <td>
                            <div class="action-buttons">

                                <a
                                    href="<?php echo e(route('admin.hero-slides.edit', $slide)); ?>"
                                    class="btn-edit">
                                    Edit
                                </a>

                                <form
                                    action="<?php echo e(route('admin.hero-slides.destroy', $slide)); ?>"
                                    method="POST"
                                    class="delete-slide-form"
                                    data-heading="<?php echo e($slide->heading ?: 'this slide'); ?>"
                                    data-id="<?php echo e($slide->id); ?>">

                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>

                                    <button type="submit" class="btn-delete">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr id="empty-slides-row">
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-icon">▧</div>

                                <h3>No hero slides yet</h3>

                                <p>
                                    Add your first homepage banner to get started.
                                </p>

                                <a
                                    href="<?php echo e(route('admin.hero-slides.create')); ?>"
                                    class="btn-primary">
                                    + Add First Slide
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($slides->hasPages()): ?>
        <div class="pagination-wrapper">
            <?php echo e($slides->links()); ?>

        </div>
        <?php endif; ?>

    </div>
</div>

<style>
    .slides-page {
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
        color: #fff;
        padding: 12px 17px;
        border-radius: 9px;
        text-decoration: none;
        font-weight: 600;
        font-size: 13px;
        border: 0;
        cursor: pointer;
        white-space: nowrap;
        transition: background .2s ease;
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

    .table-heading span {
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

    .slide-image {
        display: block;
        width: 125px;
        height: 70px;
        object-fit: cover;
        border-radius: 9px;
        background: #f0f4f1;
        border: 1px solid #e7eee9;
    }

    .slide-info {
        min-width: 180px;
        max-width: 290px;
    }

    .slide-info strong {
        display: block;
        color: #213b30;
        font-size: 13px;
        margin-bottom: 5px;
    }

    .slide-info small {
        display: block;
        color: #87938b;
        font-size: 11px;
        line-height: 1.5;
    }

    .slide-info .button-detail {
        color: #176b4d;
        margin-top: 4px;
    }

    .slide-status-btn {
        border: 0;
        cursor: pointer;
        display: inline-block;
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
        white-space: nowrap;
        transition: all .2s ease;
    }

    .slide-status-btn.active {
        background: #e3f6e9;
        color: #187443;
    }

    .slide-status-btn.inactive {
        background: #fff0e8;
        color: #b85c30;
    }

    .slide-status-btn:hover {
        opacity: 0.85;
        transform: translateY(-1px);
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
        transition: opacity .2s ease;
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
    document.addEventListener('DOMContentLoaded', function() {
        // 1. AJAX Status Toggle for Hero Slides
        document.querySelectorAll('.slide-status-btn').forEach(function(button) {
            button.addEventListener('click', function() {
                var btn = this;
                var toggleUrl = btn.dataset.url;
                btn.disabled = true;

                fetch(toggleUrl, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(function(res) {
                        return res.json();
                    })
                    .then(function(data) {
                        if (data.success) {
                            if (data.is_active) {
                                btn.classList.remove('inactive');
                                btn.classList.add('active');
                                btn.textContent = 'Active';
                            } else {
                                btn.classList.remove('active');
                                btn.classList.add('inactive');
                                btn.textContent = 'Inactive';
                            }

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
                    .catch(function() {
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
                    .finally(function() {
                        btn.disabled = false;
                    });
            });
        });

        // 2. AJAX Hero Slide Deletion with SweetAlert2
        document.querySelectorAll('.delete-slide-form').forEach(function(form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                var heading = form.dataset.heading || 'this slide';
                var slideId = form.dataset.id;
                var actionUrl = form.action;

                if (typeof Swal === 'undefined') {
                    if (confirm('Are you sure you want to delete "' + heading + '"?')) {
                        form.submit();
                    }
                    return;
                }

                Swal.fire({
                    title: 'Delete Hero Slide?',
                    text: 'Are you sure you want to delete "' + heading + '"?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true
                }).then(function(result) {
                    if (result.isConfirmed) {
                        var row = document.getElementById('slide-row-' + slideId);

                        fetch(actionUrl, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: new URLSearchParams(new FormData(form))
                            })
                            .then(function(res) {
                                return res.json();
                            })
                            .then(function(data) {
                                if (data.success) {
                                    if (row) {
                                        row.style.transition = 'opacity 0.35s ease';
                                        row.style.opacity = '0';
                                        setTimeout(function() {
                                            row.remove();
                                        }, 350);
                                    }

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Deleted!',
                                        text: data.message || 'Hero slide deleted successfully.',
                                        timer: 1600,
                                        showConfirmButton: false
                                    });
                                } else {
                                    throw new Error(data.message || 'Failed to delete');
                                }
                            })
                            .catch(function(err) {
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

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\project\suryaa_enterprises\resources\views/admin/hero-slides/index.blade.php ENDPATH**/ ?>