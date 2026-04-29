
<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Manage Member</h1>
            <p>Manage and track all your report in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Record
            </a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover" id="memberTable">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Member ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Sponsor ID</th>
                        <th>Sponsor Name</th>
                        <th>Joining Date</th>
                        <th>Smart Point</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($row->memberID); ?></td>
                            <td><?php echo e($row->name); ?></td>
                            <td><?php echo e($row->phone); ?></td>
                            <td><?php echo e($row->email); ?></td>
                            <td><?php echo e($row->sponser_id); ?></td>
                            <td><?php echo e($row->sponser_name); ?></td>
                            <td><?php echo e($row->joining_date); ?></td>
                            <td><?php echo e($row->smart_point); ?></td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <!-- Edit Button -->
                                    <button class="btn btn-sm btn-warning fw-semibold px-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo e($row->member_id); ?>"
                                        title="Edit Record">
                                        <i class="bi bi-pencil-square me-1"></i> Edit
                                    </button>

                                    <!-- Delete Button -->
                                    <a href="<?php echo e(route('managereport.delete', $row->member_id)); ?>"
                                        class="btn btn-sm btn-danger fw-semibold px-3"
                                        onclick="return confirm('Are you sure you want to delete this record?')"
                                        title="Delete Record">
                                        <i class="bi bi-trash me-1"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>

                        <!-- EDIT MODAL -->
                        <div class="modal fade" id="editModal<?php echo e($row->member_id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="<?php echo e(route('managereport.update', $row->member_id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5>Edit Record</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text"   name="name"        value="<?php echo e($row->name); ?>"        class="form-control mb-2" placeholder="Name">
                                            <input type="text"   name="phone"       value="<?php echo e($row->phone); ?>"       class="form-control mb-2" placeholder="Phone">
                                            <input type="email"  name="email"       value="<?php echo e($row->email); ?>"       class="form-control mb-2" placeholder="Email">
                                            <input type="number" name="smart_point" value="<?php echo e($row->smart_point); ?>" class="form-control mb-2" placeholder="Smart Point">
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="10" class="text-center">No Data Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="<?php echo e(route('managereport.store')); ?>">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Add New Record</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text"   name="memberID"             class="form-control mb-2" placeholder="Member ID" required>
                        <input type="text"   name="name"                 class="form-control mb-2" placeholder="Full Name" required>
                        <input type="text"   name="phone"                class="form-control mb-2" placeholder="Phone">
                        <input type="email"  name="email"                class="form-control mb-2" placeholder="Email">
                        <input type="text"   name="password"             class="form-control mb-2" placeholder="Password">
                        <input type="text"   name="transaction_password" class="form-control mb-2" placeholder="Transaction Password">
                        <input type="text"   name="sponser_id"           class="form-control mb-2" placeholder="Sponsor ID">
                        <input type="text"   name="sponser_name"         class="form-control mb-2" placeholder="Sponsor Name">
                        <input type="date"   name="joining_date"         class="form-control mb-2" required>
                        <input type="number" name="smart_point"          class="form-control mb-2" placeholder="Smart Point" value="0">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Record</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</main>


<?php $__env->startPush('scripts'); ?>
<script>
    $(document).ready(function () {
        $('#memberTable').DataTable({
            pageLength: 10,
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [
                { orderable: false, targets: 9 } // Disable sort on Action column
            ],
            language: {
                search: "🔍 Search:",
                lengthMenu: "Show _MENU_ records",
                info: "Showing _START_ to _END_ of _TOTAL_ members",
                paginate: {
                    previous: "← Prev",
                    next: "Next →"
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\ecosystem_modifiedupdate\resources\views/admin/managereport.blade.php ENDPATH**/ ?>