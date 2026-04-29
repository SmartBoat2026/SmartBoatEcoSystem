<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Manage Product</h1>
            <p>Manage and track all your Products in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Product
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

            
            <div id="bulkActionBar"
                 style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;
                        padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                <span id="selectedCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                <form id="bulkDeleteForm" method="POST" action="<?php echo e(route('product.bulkDelete')); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <div id="bulkDeleteIds"></div>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete selected products? This cannot be undone.')">
                        <i class="bi bi-trash me-1"></i>Delete Selected
                    </button>
                </form>
                <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Clear Selection
                </button>
            </div>

            <table id="productTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAll" style="cursor:pointer;width:15px;height:15px;">
                        </th>
                        <th style="width:50px">#</th>
                        <th>Category</th>
                        <th>Sub-Category</th>
                        <th>Name</th>
                        <th>HSN Code</th>
                        <th>Smart Points</th>
                        <th>Base Price</th>
                        <th>Smart Qty</th>
                        <th>Description</th>
                        <th>Image</th>
                        <th style="width:120px">Status</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        
                        <td class="text-center">
                            <input type="checkbox" class="row-checkbox" value="<?php echo e($product->id); ?>"
                                   style="cursor:pointer;width:15px;height:15px;">
                        </td>
                        <td><?php echo e($i + 1); ?></td>
                        <td><?php echo e($product->category->name ?? '-'); ?></td>
                        <td><?php echo e($product->subcategory->name ?? '-'); ?></td>
                        <td><?php echo e($product->name); ?></td>
                        <td>
                            <span class="badge bg-dark font-monospace" style="letter-spacing:1px; font-size:0.8rem;">
                                <?php echo e($product->hsn_code ?? '-'); ?>

                            </span>
                        </td>
                        <td><?php echo e($product->smart_points); ?></td>
                        <td>₹<?php echo e(number_format($product->base_price, 2)); ?></td>
                        <td><?php echo e($product->qty); ?></td>
                        <td><?php echo e(Str::limit($product->description, 40)); ?></td>
                        <td>
                            <?php if($product->image): ?>
                                <img src="<?php echo e(asset('storage/' . $product->image)); ?>" width="50" height="50"
                                     style="object-fit:cover; border-radius:4px;">
                            <?php else: ?>
                                <span class="text-muted">No Image</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="btn btn-sm toggle-status <?php echo e($product->status == 1 ? 'btn-success' : 'btn-danger'); ?>"
                                data-id="<?php echo e($product->id); ?>">
                                <?php echo e($product->status == 1 ? 'Active' : 'Inactive'); ?>

                            </button>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <button class="btn btn-warning btn-sm px-2 py-1 edit-btn"
                                    data-id="<?php echo e($product->id); ?>"
                                    data-category="<?php echo e($product->category_id); ?>"
                                    data-subcategory="<?php echo e($product->subcategory_id); ?>"
                                    data-name="<?php echo e($product->name); ?>"
                                    data-smart_points="<?php echo e($product->smart_points); ?>"
                                    data-base_price="<?php echo e($product->base_price); ?>"
                                    data-description="<?php echo e($product->description); ?>"
                                    data-bs-toggle="modal" data-bs-target="#editModal">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <a href="<?php echo e(route('product.delete', $product->id)); ?>"
                                   class="btn btn-danger btn-sm px-2 py-1"
                                   onclick="return confirm('Delete this product?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>


    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="<?php echo e(route('product.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add New Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="add_category_id" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sub-Category</label>
                                <select name="subcategory_id" id="add_subcategory_id" class="form-select">
                                    <option value="">-- Select Sub-Category --</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" required placeholder="Enter product name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Base Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" class="form-control"
                                       required min="0" step="0.01" placeholder="0.00">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Smart Points <span class="text-danger">*</span></label>
                                <input type="number" name="smart_points" id="add_smart_points"
                                       class="form-control" required min="0" step="0.01" placeholder="0.00">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Smart Quantity
                                    <small class="text-muted">(auto: Smart Points × 0.1%)</small>
                                </label>
                                <input type="number" id="add_qty_display" class="form-control bg-light"
                                       readonly placeholder="Auto-calculated" step="0.0001">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="3"
                                          placeholder="Enter product description"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    
    <div class="modal fade" id="editModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" id="editForm" action="" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label class="form-label">Category <span class="text-danger">*</span></label>
                                <select name="category_id" id="edit_category_id" class="form-select" required>
                                    <option value="">-- Select Category --</option>
                                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Sub-Category</label>
                                <select name="subcategory_id" id="edit_subcategory_id" class="form-select">
                                    <option value="">-- Select Sub-Category --</option>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Product Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="edit_name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Base Price (₹) <span class="text-danger">*</span></label>
                                <input type="number" name="base_price" id="edit_base_price"
                                       class="form-control" required min="0" step="0.01">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Smart Points <span class="text-danger">*</span></label>
                                <input type="number" name="smart_points" id="edit_smart_points"
                                       class="form-control" required min="0" step="0.01">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Smart Quantity
                                    <small class="text-muted">(auto: Smart Points × 0.1%)</small>
                                </label>
                                <input type="number" id="edit_qty_display" class="form-control bg-light"
                                       readonly step="0.0001">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">HSN Code</label>
                                <input type="text" id="edit_hsn_display" class="form-control bg-light font-monospace fw-bold"
                                       readonly style="letter-spacing:2px;">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">
                                    Image
                                    <small class="text-muted">(leave blank to keep existing)</small>
                                </label>
                                <input type="file" name="image" class="form-control" accept="image/*">
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" id="edit_description" class="form-control" rows="3"></textarea>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Product</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</main>



<?php $__env->startPush('scripts'); ?>


<script>
$(document).ready(function () {

    // ── DataTable ──────────────────────────────────────────────
    $('#productTable').DataTable({
        pageLength: 10,
        ordering:   true,
        searching:  true,
        responsive: true,
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 9, 10, 11, 12] }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'Manage Products',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,11] }  // skip checkbox & action cols
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'Manage Products',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,11] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'Manage Products',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,11] }
            }
        ],
        language: {
            search:     "🔍 Search:",
            lengthMenu: "Show _MENU_ records",
            info:       "Showing _START_ to _END_ of _TOTAL_ records",
            paginate:   { previous: "← Prev", next: "Next →" }
        },
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });

    // ════════════════════════════════════════════════════════════
    // BULK DELETE — checkbox logic
    // ════════════════════════════════════════════════════════════
    function updateBulkBar() {
        const checked = $('.row-checkbox:checked');
        const count   = checked.length;

        if (count > 0) {
            $('#bulkActionBar').addClass('show');
            $('#selectedCount').text(count + ' selected');
            let inputs = '';
            checked.each(function () {
                inputs += `<input type="hidden" name="ids[]" value="${$(this).val()}">`;
            });
            $('#bulkDeleteIds').html(inputs);
        } else {
            $('#bulkActionBar').removeClass('show');
            $('#bulkDeleteIds').html('');
        }
    }

    // Select All — works across ALL DataTable pages
    $('#selectAll').on('change', function () {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        $('#productTable tbody tr').toggleClass('row-selected', isChecked);
        updateBulkBar();
    });

    // Individual row checkbox
    $(document).on('change', '.row-checkbox', function () {
        $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
        const total   = $('.row-checkbox').length;
        const checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
        $('#selectAll').prop('checked', checked === total);
        updateBulkBar();
    });

    // Clear selection
    $('#clearSelection').on('click', function () {
        $('.row-checkbox').prop('checked', false);
        $('#productTable tbody tr').removeClass('row-selected');
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        updateBulkBar();
    });

    // ── Auto-calculate Qty = Smart Points × 0.001 ──────────────
    function calcQty(smartPoints) {
        const val = parseFloat(smartPoints);
        return isNaN(val) ? '' : (val * 0.001).toFixed(4);
    }

    $('#add_smart_points').on('input', function () {
        $('#add_qty_display').val(calcQty($(this).val()));
    });

    $('#edit_smart_points').on('input', function () {
        $('#edit_qty_display').val(calcQty($(this).val()));
    });

    // ── Helper: Load Subcategories via AJAX ────────────────────
    function loadSubcategories(categoryId, targetSelect, selectedId = null) {
        const $select = $(targetSelect);
        $select.empty().append('<option value="">-- Loading... --</option>');

        if (!categoryId) {
            $select.empty().append('<option value="">-- Select Sub-Category --</option>');
            return;
        }

        $.get('<?php echo e(route("product.subcategories", ":id")); ?>'.replace(':id', categoryId), function (data) {
            $select.empty().append('<option value="">-- Select Sub-Category --</option>');
            $.each(data, function (i, sub) {
                const selected = (sub.id == selectedId) ? 'selected' : '';
                $select.append(`<option value="${sub.id}" ${selected}>${sub.name}</option>`);
            });
        }).fail(function () {
            $select.empty().append('<option value="">-- No Sub-Categories --</option>');
        });
    }

    $('#add_category_id').on('change', function () {
        loadSubcategories($(this).val(), '#add_subcategory_id');
    });

    $('#edit_category_id').on('change', function () {
        loadSubcategories($(this).val(), '#edit_subcategory_id');
    });

    // ── EDIT Button Click: Populate Modal ─────────────────────
    $(document).on('click', '.edit-btn', function () {
        const btn           = $(this);
        const id            = btn.data('id');
        const categoryId    = btn.data('category');
        const subcategoryId = btn.data('subcategory');
        const smartPoints   = btn.data('smart_points');

        const hsnDisplay = 'HSN' + String(id).padStart(7, '0');

        $('#editForm').attr('action', '<?php echo e(route("product.update", ":id")); ?>'.replace(':id', id));
        $('#edit_name').val(btn.data('name'));
        $('#edit_smart_points').val(smartPoints);
        $('#edit_base_price').val(btn.data('base_price'));
        $('#edit_qty_display').val(calcQty(smartPoints));
        $('#edit_description').val(btn.data('description'));
        $('#edit_category_id').val(categoryId);
        $('#edit_hsn_display').val(hsnDisplay);

        loadSubcategories(categoryId, '#edit_subcategory_id', subcategoryId);
    });

    // ── Toggle Status ──────────────────────────────────────────
    $(document).on('click', '.toggle-status', function () {
        const btn = $(this);
        const id  = btn.data('id');

        $.ajax({
            url: '<?php echo e(route("product.toggleStatus", ":id")); ?>'.replace(':id', id),
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\resources\views/admin/product.blade.php ENDPATH**/ ?>