<?php $__env->startSection('content'); ?>
<main class="main" id="main" role="main">
    <div class="page-header">
        <div class="page-title">
            <h1><?php echo e($staff ? 'Edit staff' : 'Add staff'); ?></h1>
            <p>Set login details and tick the admin features this user may open.</p>
        </div>
        <div class="page-actions">
            <a href="<?php echo e(route('admin.staff.index')); ?>" class="btn-secondary">Back to list</a>
        </div>
    </div>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $e): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($e); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <form method="post" action="<?php echo e($staff ? route('admin.staff.update', $staff->id) : route('admin.staff.store')); ?>">
                <?php echo csrf_field(); ?>
                <?php if($staff): ?>
                    <?php echo method_field('PUT'); ?>
                <?php endif; ?>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required
                               value="<?php echo e(old('name', $staff->name ?? '')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control" required autocomplete="username"
                               value="<?php echo e(old('username', $staff->username ?? '')); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password <?php echo e($staff ? '(leave blank to keep current)' : ''); ?></label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password"
                               <?php echo e($staff ? '' : 'required'); ?>>
                    </div>
                    <?php if($staff): ?>
                        <div class="col-md-6 d-flex align-items-end">
                            <input type="hidden" name="is_active" value="0">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active"
                                       <?php echo e(old('is_active', $staff->is_active ? '1' : '0') === '1' ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="is_active">Account active</label>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <h5 class="mb-3">Feature permissions</h5>
                <div class="row g-2">
                    <?php $__currentLoopData = $modules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="permissions[]" value="<?php echo e($key); ?>"
                                       id="perm_<?php echo e($key); ?>"
                                    <?php echo e(in_array($key, old('permissions', $selected), true) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="perm_<?php echo e($key); ?>"><?php echo e($label); ?></label>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary"><?php echo e($staff ? 'Update' : 'Create'); ?> staff</button>
                </div>
            </form>
        </div>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Applications/XAMPP/xamppfiles/htdocs/ecosystemneww/resources/views/admin/staff/form.blade.php ENDPATH**/ ?>