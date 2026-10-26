

<?php $__env->startSection('page-title', 'Logo & Favicon Settings'); ?>
<?php $__env->startSection('page-heading', 'Logo & Favicon'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <span>Dashboard</span>
    <span>/</span>
    <span>Settings</span>
    <span>/</span>
    <span>Logo & Favicon</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>

<?php
    $logoUrl = !empty($settings?->logo_path)
        ? asset('storage/' . ltrim($settings->logo_path, '/'))
        : null;

    $faviconUrl = !empty($settings?->favicon_path)
        ? asset('storage/' . ltrim($settings->favicon_path, '/'))
        : null;
?>

<style>
    .setting-wrap {
        max-width: 1050px;
        margin: 0 auto;
    }

    .setting-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        padding: 26px;
        margin-bottom: 22px;
        box-shadow: 0 4px 18px rgba(15, 23, 42, .04);
    }

    .setting-title {
        font-size: 19px;
        font-weight: 700;
        color: #172033;
        margin-bottom: 6px;
    }

    .setting-muted {
        font-size: 13px;
        color: #64748b;
        margin-bottom: 22px;
    }

    .setting-field {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: #334155;
        margin-bottom: 9px;
    }

    .setting-input {
        display: block;
        width: 100%;
        box-sizing: border-box;
        padding: 12px;
        border: 1px solid #cbd5e1;
        border-radius: 9px;
        background: #fff;
        color: #0f172a;
    }

    .setting-input:focus {
        outline: none;
        border-color: #0f766e;
        box-shadow: 0 0 0 3px rgba(15, 118, 110, .12);
    }

    .setting-help {
        display: block;
        margin-top: 8px;
        font-size: 12px;
        color: #64748b;
    }

    .setting-preview {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 110px;
        max-width: 300px;
        padding: 18px;
        margin-top: 14px;
        background: #f8fafc;
        border: 1px dashed #cbd5e1;
        border-radius: 10px;
    }

    .setting-preview img {
        max-width: 100%;
        max-height: 100px;
        object-fit: contain;
    }

    .setting-alert {
        padding: 13px 16px;
        margin-bottom: 20px;
        border-radius: 9px;
        font-size: 14px;
    }

    .setting-success {
        background: #dcfce7;
        color: #166534;
    }

    .setting-error {
        background: #fee2e2;
        color: #991b1b;
    }

    .setting-button {
        border: 0;
        border-radius: 9px;
        padding: 12px 22px;
        background: #0f766e;
        color: #fff;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
    }

    .setting-button:hover {
        background: #115e59;
    }

    .setting-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 22px;
    }

    @media (max-width: 640px) {
        .setting-card {
            padding: 18px;
        }

        .setting-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="setting-wrap">

    
    <?php if(session('success')): ?>
        <div class="setting-alert setting-success">
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    
    <?php if($errors->any()): ?>
        <div class="setting-alert setting-error">
            <strong>Please correct the following errors:</strong>

            <ul style="margin: 8px 0 0; padding-left: 20px;">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form
        action="<?php echo e(route('admin.settings.logo.update')); ?>"
        method="POST"
        enctype="multipart/form-data"
    >
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        
        <div class="setting-card">

            <div class="setting-title">
                Website Logo
            </div>

            <div class="setting-muted">
                Upload the logo displayed in your website header and other supported locations.
            </div>

            <label class="setting-field" for="logo">
                Select Logo
            </label>

            <input
                class="setting-input"
                type="file"
                id="logo"
                name="logo"
                accept=".png,.jpg,.jpeg,.webp,.svg,image/png,image/jpeg,image/webp,image/svg+xml"
            >

            <span class="setting-help">
                Supported formats: PNG, JPG, JPEG, WebP and SVG.
                Maximum size: 2 MB. Leave empty to keep the current logo.
            </span>

            <?php if($logoUrl): ?>
                <div class="setting-preview">
                    <img
                        src="<?php echo e($logoUrl); ?>"
                        alt="Current website logo"
                    >
                </div>

                <span class="setting-help">
                    Current logo
                </span>
            <?php else: ?>
                <p class="setting-help">
                    No logo has been uploaded yet.
                </p>
            <?php endif; ?>

        </div>

        
        <div class="setting-card">

            <div class="setting-title">
                Website Favicon
            </div>

            <div class="setting-muted">
                Upload the small icon used in browser tabs and supported bookmarks.
            </div>

            <label class="setting-field" for="favicon">
                Select Favicon
            </label>

            <input
                class="setting-input"
                type="file"
                id="favicon"
                name="favicon"
                accept=".png,.ico,.jpg,.jpeg,.webp,image/png,image/jpeg,image/webp,image/x-icon"
            >

            <span class="setting-help">
                Supported formats: PNG, ICO, JPG, JPEG and WebP.
                Maximum size: 1 MB. Leave empty to keep the current favicon.
            </span>

            <?php if($faviconUrl): ?>
                <div
                    class="setting-preview"
                    style="max-width: 130px; min-height: 100px;"
                >
                    <img
                        src="<?php echo e($faviconUrl); ?>"
                        alt="Current website favicon"
                        style="max-width: 64px; max-height: 64px;"
                    >
                </div>

                <span class="setting-help">
                    Current favicon
                </span>
            <?php else: ?>
                <p class="setting-help">
                    No favicon has been uploaded yet.
                </p>
            <?php endif; ?>

        </div>

        
        <div
            style="display: flex; justify-content: flex-end; margin-bottom: 25px;"
        >
            <button type="submit" class="setting-button">
                Save Logo & Favicon
            </button>
        </div>

    </form>

</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layout', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\project\suryaa_enterprises\resources\views/admin/settings/logo-favicon.blade.php ENDPATH**/ ?>