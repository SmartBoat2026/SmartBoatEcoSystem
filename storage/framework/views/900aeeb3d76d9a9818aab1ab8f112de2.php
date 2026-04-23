<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBES Dashboard</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo e(asset('admin/assets/images/favicon.ico')); ?>">

    <!-- CSS -->
    <link href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/fontawesome.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/brands.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/icons/fontawesome/css/solid.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/plugin/select2/css/select2.min.css')); ?>" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo e(asset('admin/assets/css/board.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/chat.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/admin/styles.css')); ?>" rel="stylesheet">

    <!-- DataTables CSS -->
    <link href="<?php echo e(asset('admin/assets/css/admin/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/admin/buttons.dataTables.min.css')); ?>" rel="stylesheet">

    <!-- Bootstrap Icons (local) -->
    <link href="<?php echo e(asset('admin/assets/css/admin/bootstrap-icons.css')); ?>" rel="stylesheet">

</head>

<body>

<?php echo $__env->make('admin.layouts.nav-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('admin.layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php echo $__env->yieldContent('content'); ?>

<footer class="footer">
    © 2025 Smart BoatEcosystem — All Rights Reserved
</footer>

<!-- ================= JS ================= -->

<!-- jQuery -->
<script src="<?php echo e(asset('admin/assets/js/jquery-3.6.0.min.js')); ?>"></script>

<!-- Bootstrap -->
<script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>

<!-- Plugins -->
<script src="<?php echo e(asset('admin/assets/plugin/chart/chart.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/plugin/select2/js/select2.min.js')); ?>"></script>

<!-- Custom JS -->
<script src="<?php echo e(asset('admin/assets/js/chart.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/chat.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/board.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/script.js')); ?>"></script>

<!-- DataTables Core -->
<script src="<?php echo e(asset('admin/assets/js/admin/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/dataTables.bootstrap5.min.js')); ?>"></script>

<!-- Export Dependencies -->
<script src="<?php echo e(asset('admin/assets/js/admin/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/vfs_fonts.js')); ?>"></script>

<!-- DataTables Buttons -->
<script src="<?php echo e(asset('admin/assets/js/admin/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/buttons.print.min.js')); ?>"></script>

<!-- SweetAlert -->
<script src="<?php echo e(asset('admin/assets/js/admin/sweetalert2@11.js')); ?>"></script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html><?php /**PATH F:\xampp\htdocs\smartboatTourProject\resources\views/admin/layouts/app.blade.php ENDPATH**/ ?>