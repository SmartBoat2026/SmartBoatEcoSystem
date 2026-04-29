<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SBES Dashboard</title>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
</head>
<body>

         <?php echo $__env->make('admin.layouts.nav-top', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>;


    <?php echo $__env->make('admin.layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>;

    <?php echo $__env->yieldContent('content'); ?>

    <footer class="footer">
        © 2025 Smart BoatEcosystem — All Rights Reserved
    </footer>
    <script  src="<?php echo e(asset('admin/assets/js/jquery-3.6.0.min.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/plugin/chart/chart.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/plugin/select2/js/select2.min.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/js/chart.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/js/chat.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/js/board.js')); ?>"></script>
    <script  src="<?php echo e(asset('admin/assets/js/script.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>  
</body>
</html>
<?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/admin/layouts/app.blade.php ENDPATH**/ ?>