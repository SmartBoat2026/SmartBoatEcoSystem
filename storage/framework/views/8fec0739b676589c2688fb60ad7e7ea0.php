
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SmartBoatEcosystem</title>
    <link rel="shortcut icon" href="<?php echo e(asset('admin/assets/images/favicon.ico')); ?>" type="image/x-icon">
    <link href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?php echo e(asset('admin/assets/css/style.css')); ?>" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            background: #0a0e1a;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', sans-serif;
            color: #e2e8f0;
        }

        .theme-toggle {
            position: fixed;
            top: 16px;
            right: 16px;
            z-index: 999;
        }

        .theme-btn {
            background: #1e293b;
            border: 1px solid #334155;
            color: #94a3b8;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            transition: all 0.2s;
        }

        .theme-btn:hover {
            background: #334155;
            color: #e2e8f0;
        }

        .auth-container {
            width: 100%;
            max-width: 480px;
            padding: 16px;
        }

        .form-side {
            background: #111827;
            border: 1px solid #1e293b;
            border-radius: 16px;
            padding: 36px 32px;
        }

        .form-header {
            text-align: center;
            margin-bottom: 24px;
        }

        .form-header h2 {
            font-size: 22px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 6px;
        }

        .form-header p {
            font-size: 13px;
            color: #64748b;
        }

        .login-hint {
            text-align: center;
            margin-bottom: 16px;
            font-size: 13px;
            color: #64748b;
            background: #1e293b;
            border-radius: 8px;
            padding: 10px 14px;
        }

        .alert {
            padding: 10px 14px;
            border-radius: 8px;
            font-size: 14px;
            margin-bottom: 14px;
        }

        .alert-info {
            background: #0c1e3d;
            color: #60a5fa;
        }

        .alert-danger {
            background: #2d1515;
            color: #f87171;
        }

        .form-group {
            margin-bottom: 18px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 500;
            color: #94a3b8;
            margin-bottom: 7px;
        }

        .form-input {
            width: 100%;
            background: #1e293b;
            border: 1px solid #334155;
            border-radius: 8px;
            padding: 10px 14px;
            color: #e2e8f0;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .form-input::placeholder {
            color: #475569;
        }

        .form-input:focus {
            border-color: #3b82f6;
        }

        .form-input.is-invalid {
            border-color: #ef4444;
        }

        .password-wrap {
            position: relative;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #64748b;
            cursor: pointer;
            font-size: 16px;
            line-height: 1;
        }

        .password-toggle:hover {
            color: #94a3b8;
        }

        .error-message {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 12px;
            color: #f87171;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }

        .form-check-input {
            width: 15px;
            height: 15px;
            accent-color: #3b82f6;
            cursor: pointer;
        }

        .form-check-label {
            font-size: 13px;
            color: #64748b;
            cursor: pointer;
        }

        .btn-primary {
            width: 100%;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 11px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 12px;
        }

        .btn-primary:hover {
            background: #1d4ed8;
        }

        .divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px;
            color: #475569;
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: #1e293b;
        }

        .btn-register {
            width: 100%;
            background: transparent;
            color: #3b82f6;
            border: 1px solid #3b82f6;
            border-radius: 8px;
            padding: 11px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-register:hover {
            background: #1e3a5f;
            color: #60a5fa;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Theme Toggle -->
    <div class="theme-toggle">
        <button class="theme-btn" id="themeToggle">
            <i class="bi bi-sun" id="themeIcon"></i>
        </button>
    </div>

    <div class="auth-container" id="loginPage">
        <div class="form-side">

            <div class="form-header">
                <h2>Welcome Back</h2>
                <p>Sign in to your SmartBoatEcosystem Account</p>
            </div>

            
            <div class="login-hint">
                <i class="bi bi-info-circle me-1"></i>
                Admin: use <strong>username</strong> &nbsp;|&nbsp;
                Member: use <strong>Member ID</strong>
            </div>

            
            <?php if(session('status')): ?>
                <div class="alert alert-info mb-3">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            
            <?php if($errors->has('username')): ?>
                <div class="alert alert-danger mb-3">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <?php echo e($errors->first('username')); ?>

                </div>
            <?php endif; ?>

            <form class="auth-form" id="loginForm" method="POST" action="<?php echo e(route('login.post')); ?>">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label" for="loginUsername">Username</label>
                    <input
                        type="text"
                        class="form-input <?php echo e($errors->has('username') ? 'is-invalid' : ''); ?>"
                        id="loginUsername"
                        name="username"
                        placeholder="Enter username or Member ID (e.g. SB3891186636)"
                        value="<?php echo e(old('username')); ?>"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="loginPassword">Password</label>
                    <div class="password-wrap">
                        <input
                            type="password"
                            class="form-input"
                            id="loginPassword"
                            name="password"
                            placeholder="Enter your password"
                            required
                            autocomplete="current-password"
                        >
                        <button type="button" class="password-toggle" id="loginPasswordToggle">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    <?php if($errors->has('password')): ?>
                        <div class="error-message">
                            <i class="bi bi-exclamation-circle"></i>
                            <span><?php echo e($errors->first('password')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe" name="remember">
                    <label class="form-check-label" for="rememberMe">Remember me for 30 days</label>
                </div>

                <button type="submit" class="btn-primary" id="loginBtn">
                    <i class="bi bi-box-arrow-in-right"></i>
                    <span>Sign In</span>
                </button>

                <div class="divider">or</div>

                <a href="<?php echo e(route('register')); ?>" class="btn-register">
                    <i class="bi bi-person-plus"></i>
                    <span>Register as Member</span>
                </a>

            </form>
        </div>
    </div>

    <script src="<?php echo e(asset('admin/assets/js/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/script.js')); ?>"></script>
    <script>
        document.getElementById('loginPasswordToggle').addEventListener('click', function () {
            const input = document.getElementById('loginPassword');
            const icon  = this.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.replace('bi-eye', 'bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.replace('bi-eye-slash', 'bi-eye');
            }
        });
    </script>
</body>
</html>
<?php /**PATH F:\xampp\htdocs\smartboatTourProject\resources\views/admin/login.blade.php ENDPATH**/ ?>