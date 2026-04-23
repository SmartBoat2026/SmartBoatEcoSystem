
<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Manage Category</h1>
            <p>Manage and track all your Category & Sub-category in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Record
            </a>
        </div>
    </div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>
    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table id="categoryTable" class="table table-bordered table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Category Name</th>
                        <th>Subcategory</th>
                        <th style="width:120px">Status</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="table-light">

                        
                        <td><?php echo e($index + 1); ?></td>

                        
                        <td><strong><?php echo e($cat->name); ?></strong></td>

                        
                        <td>
                            <?php if($cat->subcategories->count() > 0): ?>
                                <div class="d-flex flex-wrap gap-1">
                                    <?php $__currentLoopData = $cat->subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="badge bg-secondary fs-6 fw-normal px-2 py-1">
                                            <?php echo e($sub->name); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <span class="text-muted fst-italic">— No subcategory yet</span>
                            <?php endif; ?>
                        </td>

                        
                        <td>
                            <button
                                class="btn btn-sm toggle-status <?php echo e($cat->status == 1 ? 'btn-success' : 'btn-danger'); ?>"
                                data-id="<?php echo e($cat->id); ?>"
                                title="Click to toggle">
                                <?php echo e($cat->status == 1 ? 'Active' : 'Inactive'); ?>

                            </button>
                        </td>

                        
                        <td>
                            <div class="d-flex gap-1 flex-nowrap">
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editModal"
                                    data-id="<?php echo e($cat->id); ?>"
                                    data-name="<?php echo e($cat->name); ?>"
                                    data-parent="<?php echo e($cat->parent_id); ?>">
                                    <i class="bi bi-pencil-fill"></i> Edit
                                </button>
                                <a href="<?php echo e(route('category.delete', $cat->id)); ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this category and ALL its subcategories?')">
                                    <i class="bi bi-trash-fill"></i> Delete
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                No records found. Click <strong>+ New Record</strong> to add a Category first.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>


    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('category.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <select id="add_type" name="type" class="form-select">
                                <option value="category">Category</option>
                                <?php if($parentOptions->count() > 0): ?>
                                    <option value="subcategory">Subcategory</option>
                                <?php endif; ?>
                            </select>
                            <?php if($parentOptions->count() === 0): ?>
                                <div class="form-text text-warning">
                                    <i class="bi bi-info-circle"></i>
                                    No categories exist yet. Please create a <strong>Category</strong> first.
                                </div>
                            <?php endif; ?>
                        </div>

                        
                        <?php if($parentOptions->count() > 0): ?>
                        <div class="mb-3" id="add_parent_wrap" style="display:none;">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="add_parent_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                <?php $__currentLoopData = $parentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($opt->id); ?>"><?php echo e($opt->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Enter name" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" id="editForm" action="">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Type</label>
                            <select id="edit_type" class="form-select">
                                <option value="category">Category</option>
                                <?php if($parentOptions->count() > 0): ?>
                                    <option value="subcategory">Subcategory</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        
                        <?php if($parentOptions->count() > 0): ?>
                        <div class="mb-3" id="edit_parent_wrap" style="display:none;">
                            <label class="form-label fw-bold">Category <span class="text-danger">*</span></label>
                            <select name="parent_id" id="edit_parent_id" class="form-select">
                                <option value="">-- Select Category --</option>
                                <?php $__currentLoopData = $parentOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($opt->id); ?>"><?php echo e($opt->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <?php endif; ?>

                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="edit_name" class="form-control" required>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</main>


<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function () {

    // ── DataTable ──
    $('#categoryTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [2, 3, 4] }
        ],
        language: {
            search: "🔍 Search:",
            lengthMenu: "Show _MENU_ records",
            info: "Showing _START_ to _END_ of _TOTAL_ records",
            paginate: { previous: "← Prev", next: "Next →" }
        }
    });

    // ── ADD MODAL: Type toggle ──
    $('#add_type').on('change', function () {
        if ($(this).val() === 'subcategory') {
            $('#add_parent_wrap').show();
            $('#add_parent_id').attr('required', true);
        } else {
            $('#add_parent_wrap').hide();
            $('#add_parent_id').removeAttr('required').val('');
        }
    });

    // ── EDIT MODAL: Populate on open ──
    $('#editModal').on('show.bs.modal', function (e) {
        const btn      = $(e.relatedTarget);
        const id       = btn.data('id');
        const name     = btn.data('name');
        const parentId = btn.data('parent');

        $('#editForm').attr('action', '/category/update/' + id);
        $('#edit_name').val(name);

        if (parentId) {
            $('#edit_type').val('subcategory');
            $('#edit_parent_wrap').show();
            $('#edit_parent_id').attr('required', true).val(parentId);
        } else {
            $('#edit_type').val('category');
            $('#edit_parent_wrap').hide();
            $('#edit_parent_id').removeAttr('required').val('');
        }
    });

    // ── EDIT MODAL: Type toggle ──
    $('#edit_type').on('change', function () {
        if ($(this).val() === 'subcategory') {
            $('#edit_parent_wrap').show();
            $('#edit_parent_id').attr('required', true);
        } else {
            $('#edit_parent_wrap').hide();
            $('#edit_parent_id').removeAttr('required').val('');
        }
    });

    // ══════════════════════════════════════
    //  TOGGLE STATUS — AJAX
    // ══════════════════════════════════════
    $(document).on('click', '.toggle-status', function () {
        const btn = $(this);
        const id  = btn.data('id');

        $.ajax({
            url:  '/category/toggle-status/' + id,
            type: 'POST',
            data: { _token: '<?php echo e(csrf_token()); ?>' },
            success: function (res) {
                if (res.status == 1) {
                    btn.removeClass('btn-danger').addClass('btn-success').text('Active');
                } else {
                    btn.removeClass('btn-success').addClass('btn-danger').text('Inactive');
                }
            },
            error: function () {
                alert('Something went wrong. Please try again.');
            }
        });
    });

});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\ecosystem_modifiedupdate\resources\views/admin/category.blade.php ENDPATH**/ ?>