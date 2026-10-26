
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login | Suryaa Enterprises</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: Arial, sans-serif;
            background: #f1f5f9;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            padding: 36px;
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 35px rgba(0, 0, 0, .08);
        }

        .brand {
            text-align: center;
            margin-bottom: 28px;
        }

        .brand h1 {
            margin: 0 0 8px;
            color: #123b2a;
            font-size: 27px;
        }

        .brand p {
            margin: 0;
            color: #64748b;
            font-size: 14px;
        }

        label {
            display: block;
            margin: 18px 0 8px;
            color: #334155;
            font-size: 14px;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            font-size: 15px;
        }

        input:focus {
            outline: 2px solid #b7dfc5;
            border-color: #237748;
        }

        button {
            width: 100%;
            margin-top: 24px;
            padding: 14px;
            border: 0;
            border-radius: 7px;
            background: #17663b;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
        }

        button:hover {
            background: #104d2c;
        }

        .error {
            margin-top: 16px;
            padding: 12px;
            border-radius: 7px;
            background: #fef2f2;
            color: #b91c1c;
            font-size: 14px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <main class="login-card">
        <div class="brand">
            <h1>Suryaa Enterprises</h1>
            <p>Admin Panel Login</p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.login.submit')); ?>">
            <?php echo csrf_field(); ?>

            <label for="email">Email Address</label>
            <input
                id="email"
                type="email"
                name="email"
                value="<?php echo e(old('email')); ?>"
                placeholder="Enter admin email"
                autocomplete="username"
                required
                autofocus
            >

            <label for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Enter password"
                autocomplete="current-password"
                required
            >

            <?php if($errors->any()): ?>
                <div class="error">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <button type="submit">Login to Dashboard</button>
        </form>

        <div class="footer">
            &copy; <?php echo e(date('Y')); ?> Suryaa Enterprises
        </div>
    </main>
</body>
</html>
<?php /**PATH C:\project\suryaa_enterprises\resources\views/admin/login.blade.php ENDPATH**/ ?>