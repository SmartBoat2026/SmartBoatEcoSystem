<?php $__env->startSection('content'); ?>
<main class="main" id="main" role="main">
    <div class="page-header">
        <div class="page-title">
            <h1>Staff management</h1>
            <p>Create staff logins with access only to selected admin features.</p>
        </div>
        <div class="page-actions">
            <a href="<?php echo e(route('admin.staff.create')); ?>" class="btn-primary">
                <i class="bi bi-plus"></i> Add staff
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Username</th>
                        <th>Features</th>
                        <th>Status</th>
                        <th style="width:200px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $staff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($row->id); ?></td>
                            <td><?php echo e($row->name); ?></td>
                            <td><?php echo e($row->username); ?></td>
                            <td><small class="text-muted"><?php echo e(count($row->permissions ?? [])); ?> selected</small></td>
                            <td>
                                <?php if($row->is_active): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.staff.edit', $row->id)); ?>" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="<?php echo e(route('admin.staff.destroy', $row->id)); ?>" method="post" class="d-inline"
                                      onsubmit="return confirm('Delete this staff account?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No staff yet. Click &quot;Add staff&quot; to create one.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/ecosystemneww/resources/views/admin/staff/index.blade.php ENDPATH**/ ?>