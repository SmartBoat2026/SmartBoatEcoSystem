<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Panelry Dashboard</title>
    <link rel="shortcut icon" href="<?php echo e(asset('admin/assets/images/favicon.ico')); ?>" type="image/x-icon">
    <link href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/fontawesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/brands.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/solid.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link href="<?php echo e(asset('admin/assets/plugin/select2/css/select2.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/board.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/chat.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/style.css')); ?>" rel="stylesheet">
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
                <p>Sign in to your Panelry account</p>
            </div>

            
            <?php if(session('status')): ?>
                <div class="alert alert-info mb-3" style="padding:10px 14px; border-radius:8px; background:#e0f2fe; color:#0369a1; font-size:14px;">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            
            <?php if($errors->has('username')): ?>
                <div class="alert alert-danger mb-3" style="padding:10px 14px; border-radius:8px; background:#fee2e2; color:#b91c1c; font-size:14px;">
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
                        placeholder="Enter your username"
                        value="<?php echo e(old('username')); ?>"
                        required
                        autocomplete="username"
                    >
                </div>

                <div class="form-group">
                    <label class="form-label" for="loginPassword">Password</label>
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
                    <?php if($errors->has('password')): ?>
                        <div class="error-message" style="display:flex;">
                            <i class="bi bi-exclamation-circle"></i>
                            <span><?php echo e($errors->first('password')); ?></span>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">Remember me for 30 days</label>
                    <a href="forgot-password.html" class="form-link ms-auto">Forgot password?</a>
                </div>

                <button type="submit" class="btn-primary" id="loginBtn">
                    <span>Sign In</span>
                </button>

                <div class="divider">
                    <span>Or continue with</span>
                </div>

                <div class="social-buttons">
                    <button type="button" class="social-btn google">
                        <i class="bi bi-google"></i>
                    </button>
                    <button type="button" class="social-btn github">
                        <i class="bi bi-github"></i>
                    </button>
                    <button type="button" class="social-btn microsoft">
                        <i class="bi bi-microsoft"></i>
                    </button>
                </div>

                <div class="form-links">
                    <p>Don't have an account? <a href="signup.html" class="form-link">Sign up now</a></p>
                </div>

                <a href="/" class="back-link">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to home</span>
                </a>
            </form>
        </div>
    </div>

    <script src="<?php echo e(asset('admin/assets/js/jquery-3.6.0.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/plugin/chart/chart.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/plugin/select2/js/select2.min.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/chart.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/chat.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/board.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/assets/js/script.js')); ?>"></script>
    <script>
        // Password toggle (keep existing functionality)
        document.getElementById('loginPasswordToggle').addEventListener('click', function () {
            const input = document.getElementById('loginPassword');
            const icon = this.querySelector('i');
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
<?php /**PATH E:\xampp\htdocs\ecosystem_modifiedupdate\resources\views/admin/login.blade.php ENDPATH**/ ?>