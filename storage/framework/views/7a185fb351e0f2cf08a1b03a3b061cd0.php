<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>STP Schedules</h1>
            <p>Manage member STP running schedules</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> Add Schedule
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

            
            <div id="bulkActionBar"
                 style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;
                        padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                <span id="bulkCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                <form id="bulkDeleteForm"
                      action="<?php echo e(route('stpschedule.bulkDelete')); ?>"
                      method="POST" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <div id="bulkDeleteInputs"></div>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete selected records? This cannot be undone.')">
                        <i class="bi bi-trash me-1"></i>Delete Selected
                    </button>
                </form>
                <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Clear Selection
                </button>
            </div>

            <table id="stpSchedulesTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAll" style="cursor:pointer;width:15px;height:15px;">
                        </th>
                        <th style="width:50px">#</th>
                        <th>Member</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Running Hrs</th>
                        <th>Per Hr (₹)</th>
                        <th>Per Day (₹)</th>
                        <th>Total (₹)</th>
                        <th style="width:120px">Status</th>
                        <th style="width:120px">Created At</th>
                        <th style="width:100px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td class="text-center">
                            <input type="checkbox" class="row-checkbox" value="<?php echo e($schedule->id); ?>"
                                   style="cursor:pointer;width:15px;height:15px;">
                        </td>
                        <td><?php echo e($index + 1); ?></td>
                        <td>
                            <div style="font-weight:700;color:#1a3a6b;"><?php echo e($schedule->member_id); ?></div>
                            <?php if(isset($memberInfos[$schedule->member_id])): ?>
                            <div style="color:#333;margin-top:2px;">
                                <?php echo e($memberInfos[$schedule->member_id]->name); ?>

                            </div>
                            <div style="font-size:11px;color:#6c757d;margin-top:1px;">
                                <i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;"></i>
                                <?php echo e($memberInfos[$schedule->member_id]->phone); ?>

                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="white-space:nowrap;"><?php echo e($schedule->start_date); ?></td>
                        <td style="white-space:nowrap;"><?php echo e($schedule->end_date); ?></td>
                        <td><?php echo e(Str::limit($schedule->running_hrs, 40)); ?></td>
                        <td style="white-space:nowrap;">₹<?php echo e(number_format($schedule->per_hrs_amount, 2)); ?></td>
                        <td style="white-space:nowrap;">₹<?php echo e(number_format($schedule->per_day_amount, 2)); ?></td>
                        <td style="white-space:nowrap;font-weight:700;">₹<?php echo e(number_format($schedule->total_amount, 2)); ?></td>
                        <td>
                            <form action="<?php echo e(route('stpschedule.toggleStatus', $schedule->id)); ?>"
                                  method="POST" style="display:inline;">
                                <?php echo csrf_field(); ?>
                                <button type="submit"
                                        class="btn btn-sm <?php echo e($schedule->status == 1 ? 'btn-success' : 'btn-secondary'); ?>"
                                        style="font-size:11px;padding:3px 10px;min-width:62px;">
                                    <?php echo e($schedule->status == 1 ? 'Active' : 'Inactive'); ?>

                                </button>
                            </form>
                        </td>
                        <td style="white-space:nowrap;color:#6c757d;">
                            <?php echo e(\Carbon\Carbon::parse($schedule->created_at)->format('d M Y')); ?>

                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-warning px-2 py-1"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo e($schedule->id); ?>">
                                    <i class="bi bi-pencil-fill"></i>
                                </button>
                                <form action="<?php echo e(route('stpschedule.delete', $schedule->id)); ?>"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this schedule?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-danger px-2 py-1">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            No schedule records found.
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>


    
    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editModal<?php echo e($schedule->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('stpschedule.update', $schedule->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">
                                    Member ID <small class="text-muted fw-normal">(search by ID / Name / Phone)</small>
                                </label>
                                <div class="member-search-wrapper position-relative">
                                    <input type="text"
                                        class="form-control member-search-input"
                                        data-target="edit_member_id_<?php echo e($schedule->id); ?>"
                                        data-info="edit_member_info_<?php echo e($schedule->id); ?>"
                                        placeholder="Type to search member..."
                                        value="<?php echo e($schedule->member_id); ?>"
                                        autocomplete="off">
                                    <div class="member-dropdown list-group position-absolute w-100 shadow"
                                        id="edit_member_dropdown_<?php echo e($schedule->id); ?>"
                                        style="display:none;z-index:9999;max-height:200px;overflow-y:auto;top:100%;left:0;right:0;"></div>
                                </div>
                                <input type="hidden" name="member_id"
                                       id="edit_member_id_<?php echo e($schedule->id); ?>"
                                       value="<?php echo e($schedule->member_id); ?>" required>
                                <small id="edit_member_info_<?php echo e($schedule->id); ?>" class="text-success" style="font-size:11px;">
                                    <?php if(isset($memberInfos[$schedule->member_id])): ?>
                                        ✅ <?php echo e($memberInfos[$schedule->member_id]->name); ?> | 📞 <?php echo e($memberInfos[$schedule->member_id]->phone); ?>

                                    <?php endif; ?>
                                </small>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-bold">Start Date</label>
                                <input type="date" name="start_date"
                                       id="edit_start_date_<?php echo e($schedule->id); ?>"
                                       class="form-control"
                                       value="<?php echo e($schedule->start_date); ?>" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-bold">End Date</label>
                                <input type="date" name="end_date"
                                       id="edit_end_date_<?php echo e($schedule->id); ?>"
                                       class="form-control"
                                       value="<?php echo e($schedule->end_date); ?>"
                                       min="<?php echo e($schedule->start_date); ?>" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Running Hrs</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-check-all" type="checkbox"
                                               id="edit_check_all_<?php echo e($schedule->id); ?>"
                                               data-id="<?php echo e($schedule->id); ?>">
                                        <label class="form-check-label fw-bold text-primary"
                                               for="edit_check_all_<?php echo e($schedule->id); ?>" style="font-size:12px;">
                                            Select All (24hrs)
                                        </label>
                                    </div>
                                </div>
                                <div class="border rounded p-2"
                                     style="max-height:180px;overflow-y:auto;background:#f8f9fa;">
                                    <div class="row g-1">
                                        <?php for($h = 0; $h < 24; $h++): ?>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input edit-hr-<?php echo e($schedule->id); ?>"
                                                       type="checkbox"
                                                       value="<?php echo e($h); ?>hrs-<?php echo e($h+1); ?>hrs"
                                                       id="edit_hr_<?php echo e($schedule->id); ?>_<?php echo e($h); ?>"
                                                       data-id="<?php echo e($schedule->id); ?>"
                                                       <?php
                                                           $slot    = $h.'hrs-'.($h+1).'hrs';
                                                           $saved   = $schedule->running_hrs;
                                                           $checked = ($saved === 'All (24hrs)' || str_contains($saved, $slot));
                                                       ?>
                                                       <?php echo e($checked ? 'checked' : ''); ?>>
                                                <label class="form-check-label"
                                                       for="edit_hr_<?php echo e($schedule->id); ?>_<?php echo e($h); ?>"
                                                       style="font-size:11px;">
                                                    <?php echo e($h); ?>hrs - <?php echo e($h+1); ?>hrs
                                                </label>
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="running_hrs"
                                       id="edit_running_hrs_hidden_<?php echo e($schedule->id); ?>"
                                       value="<?php echo e($schedule->running_hrs); ?>">
                                <small id="edit_selected_hrs_label_<?php echo e($schedule->id); ?>"
                                       class="text-primary" style="font-size:11px;">
                                    <?php echo e($schedule->running_hrs); ?>

                                </small>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">Per Hrs Amount (₹)</label>
                                <input type="number" name="per_hrs_amount"
                                       id="edit_per_hrs_amount_<?php echo e($schedule->id); ?>"
                                       class="form-control"
                                       value="<?php echo e($schedule->per_hrs_amount); ?>"
                                       min="0" step="0.01" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">
                                    Per Day Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="per_day_amount"
                                       id="edit_per_day_amount_<?php echo e($schedule->id); ?>"
                                       class="form-control fw-bold"
                                       style="background:#f8f9fa;"
                                       value="<?php echo e($schedule->per_day_amount); ?>" readonly>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">
                                    Total Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="total_amount"
                                       id="edit_total_amount_<?php echo e($schedule->id); ?>"
                                       class="form-control fw-bold"
                                       style="background:#f8f9fa;"
                                       value="<?php echo e($schedule->total_amount); ?>" readonly>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1" <?php echo e($schedule->status == 1 ? 'selected' : ''); ?>>Active</option>
                                    <option value="0" <?php echo e($schedule->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Schedule</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('stpschedule.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-bold">
                                    Member ID <small class="text-muted fw-normal">(search by ID / Name / Phone)</small>
                                </label>
                                <div class="member-search-wrapper position-relative">
                                    <input type="text"
                                           class="form-control"
                                           id="add_member_search"
                                           placeholder="Type to search member..."
                                           autocomplete="off">
                                    <div class="list-group position-absolute w-100 shadow"
                                         id="add_member_dropdown"
                                         style="display:none;z-index:9999;max-height:200px;overflow-y:auto;top:100%;left:0;right:0;"></div>
                                </div>
                                <input type="hidden" name="member_id" id="add_member_id" value="" required>
                                <small id="add_member_info" class="text-success" style="font-size:11px;"></small>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-bold">Start Date</label>
                                <input type="date" name="start_date" id="add_start_date"
                                       class="form-control" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-bold">End Date</label>
                                <input type="date" name="end_date" id="add_end_date"
                                       class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-bold">Running Hrs</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check_all_hrs">
                                        <label class="form-check-label fw-bold text-primary"
                                               for="check_all_hrs" style="font-size:12px;">
                                            Select All (24hrs)
                                        </label>
                                    </div>
                                </div>
                                <div class="border rounded p-2"
                                     style="max-height:180px;overflow-y:auto;background:#f8f9fa;">
                                    <div class="row g-1">
                                        <?php for($h = 0; $h < 24; $h++): ?>
                                        <div class="col-6 col-sm-4 col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input hr-checkbox"
                                                       type="checkbox"
                                                       value="<?php echo e($h); ?>hrs-<?php echo e($h+1); ?>hrs"
                                                       id="hr_<?php echo e($h); ?>">
                                                <label class="form-check-label"
                                                       for="hr_<?php echo e($h); ?>" style="font-size:11px;">
                                                    <?php echo e($h); ?>hrs - <?php echo e($h+1); ?>hrs
                                                </label>
                                            </div>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <input type="hidden" name="running_hrs" id="add_running_hrs_hidden">
                                <small class="text-muted" id="selected_hrs_label" style="font-size:11px;">
                                    No hours selected
                                </small>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">Per Hrs Amount (₹)</label>
                                <input type="number" name="per_hrs_amount" id="add_per_hrs_amount"
                                       class="form-control"
                                       placeholder="Enter per hr amount" min="0" step="0.01" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">
                                    Per Day Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="per_day_amount" id="add_per_day_amount"
                                       class="form-control fw-bold"
                                       style="background:#f8f9fa;" readonly placeholder="Auto calculated">
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">
                                    Total Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="total_amount" id="add_total_amount"
                                       class="form-control fw-bold"
                                       style="background:#f8f9fa;" readonly placeholder="Auto calculated">
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-bold">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>



<?php $__env->startPush('scripts'); ?>


<script>
document.addEventListener("DOMContentLoaded", function () {

    // ════════════════════════════════════════════════
    // 1. DATATABLES INIT
    // ════════════════════════════════════════════════
    <?php if($schedules->count()): ?>
    $('#stpSchedulesTable').DataTable({
        pageLength : 10,
        ordering   : true,
        searching  : true,
        responsive : true,
        columnDefs : [{ orderable: false, searchable: false, targets: [0, 11] }],
        rowCallback: function (row, data, index) {
            $('td:eq(1)', row).text(index + 1);
        },
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'STP Schedules',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'STP Schedules',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'STP Schedules',
                exportOptions: { columns: [1,2,3,4,5,6,7,8,9,10] }
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
    <?php endif; ?>

    // ════════════════════════════════════════════════
    // 2. BULK DELETE
    // ════════════════════════════════════════════════
    function updateBulkBar() {
        var checked = document.querySelectorAll('.row-checkbox:checked');
        var bar     = document.getElementById('bulkActionBar');
        var inputs  = document.getElementById('bulkDeleteInputs');
        inputs.innerHTML = '';
        if (checked.length > 0) {
            bar.classList.add('show');
            document.getElementById('bulkCount').textContent = checked.length + ' selected';
            checked.forEach(function (cb) {
                var inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = 'ids[]'; inp.value = cb.value;
                inputs.appendChild(inp);
            });
        } else {
            bar.classList.remove('show');
        }
    }

    var selectAllBox = document.getElementById('selectAll');
    if (selectAllBox) {
        selectAllBox.addEventListener('change', function () {
            document.querySelectorAll('.row-checkbox').forEach(function (cb) {
                cb.checked = selectAllBox.checked;
                cb.closest('tr').classList.toggle('row-selected', selectAllBox.checked);
            });
            updateBulkBar();
        });
    }
    document.querySelectorAll('.row-checkbox').forEach(function (cb) {
        cb.addEventListener('change', function () {
            cb.closest('tr').classList.toggle('row-selected', cb.checked);
            var t = document.querySelectorAll('.row-checkbox').length;
            var c = document.querySelectorAll('.row-checkbox:checked').length;
            if (selectAllBox) { selectAllBox.checked = (c===t); selectAllBox.indeterminate = (c>0&&c<t); }
            updateBulkBar();
        });
    });
    var clrBtn = document.getElementById('clearSelection');
    if (clrBtn) {
        clrBtn.addEventListener('click', function () {
            document.querySelectorAll('.row-checkbox').forEach(function (cb) {
                cb.checked = false; cb.closest('tr').classList.remove('row-selected');
            });
            if (selectAllBox) { selectAllBox.checked = false; selectAllBox.indeterminate = false; }
            updateBulkBar();
        });
    }

    // ════════════════════════════════════════════════
    // 3. MEMBER SEARCH
    // ════════════════════════════════════════════════
    var searchUrl    = "<?php echo e(route('stpschedule.searchMember')); ?>";
    var searchTimers = {};

    function initMemberSearch(inputEl, hiddenId, infoId, dropdownEl) {
        if (!inputEl || !dropdownEl) return;
        var hiddenEl = document.getElementById(hiddenId);
        var infoEl   = document.getElementById(infoId);
        if (!hiddenEl || !infoEl) return;

        document.addEventListener('click', function (e) {
            if (!inputEl.contains(e.target) && !dropdownEl.contains(e.target))
                dropdownEl.style.display = 'none';
        });
        inputEl.addEventListener('input', function () {
            var q = this.value.trim();
            hiddenEl.value = ''; infoEl.textContent = ''; infoEl.className = 'text-muted';
            dropdownEl.innerHTML = ''; dropdownEl.style.display = 'none';
            if (q.length < 1) return;
            clearTimeout(searchTimers[dropdownEl.id]);
            searchTimers[dropdownEl.id] = setTimeout(function () {
                dropdownEl.innerHTML = '<div class="list-group-item text-muted" style="font-size:12px;">Searching…</div>';
                dropdownEl.style.display = 'block';
                fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers:{ 'X-Requested-With':'XMLHttpRequest' } })
                .then(function (r) { return r.json(); })
                .then(function (data) {
                    dropdownEl.innerHTML = '';
                    if (!data.length) {
                        dropdownEl.innerHTML = '<div class="list-group-item text-danger" style="font-size:12px;">No member found</div>';
                        dropdownEl.style.display = 'block'; return;
                    }
                    data.forEach(function (m) {
                        var item = document.createElement('button');
                        item.type = 'button'; item.className = 'list-group-item list-group-item-action py-2 px-3';
                        item.style.fontSize = '12px';
                        item.innerHTML = '<span class="fw-bold text-primary">' + esc(m.memberID) + '</span>'
                                       + ' — ' + esc(m.name)
                                       + ' <span class="text-muted">(' + esc(m.phone) + ')</span>';
                        item.addEventListener('click', function () {
                            inputEl.value = m.memberID; hiddenEl.value = m.memberID;
                            infoEl.textContent = '✅ ' + m.name + ' | ' + m.phone;
                            infoEl.className = 'text-success'; dropdownEl.style.display = 'none';
                        });
                        dropdownEl.appendChild(item);
                    });
                    dropdownEl.style.display = 'block';
                })
                .catch(function () {
                    dropdownEl.innerHTML = '<div class="list-group-item text-danger" style="font-size:12px;">Search error</div>';
                    dropdownEl.style.display = 'block';
                });
            }, 300);
        });
        inputEl.addEventListener('keydown', function (e) { if (e.key === 'Escape') dropdownEl.style.display = 'none'; });
    }

    function esc(str) { var d = document.createElement('div'); d.appendChild(document.createTextNode(str||'')); return d.innerHTML; }

    initMemberSearch(
        document.getElementById('add_member_search'),
        'add_member_id', 'add_member_info',
        document.getElementById('add_member_dropdown')
    );
    document.querySelectorAll('.member-search-input').forEach(function (inputEl) {
        var targetId = inputEl.getAttribute('data-target');
        var infoId   = inputEl.getAttribute('data-info');
        if (!targetId) return;
        var wrapper    = inputEl.closest('.member-search-wrapper');
        var dropdownEl = wrapper ? wrapper.querySelector('.member-dropdown') : null;
        if (!dropdownEl) return;
        initMemberSearch(inputEl, targetId, infoId, dropdownEl);
    });

    // ════════════════════════════════════════════════
    // 4. HELPER
    // ════════════════════════════════════════════════
    function daysBetween(s, e) {
        if (!s || !e) return 0;
        var d = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
        return d > 0 ? d : 0;
    }

    // ════════════════════════════════════════════════
    // 5. ADD MODAL — checkboxes + calc
    // ════════════════════════════════════════════════
    var checkAll     = document.getElementById('check_all_hrs');
    var hrCheckboxes = document.querySelectorAll('.hr-checkbox');

    function updateAddHidden() {
        var sel = Array.from(hrCheckboxes).filter(function(c){return c.checked;}).map(function(c){return c.value;});
        var h   = document.getElementById('add_running_hrs_hidden');
        var lbl = document.getElementById('selected_hrs_label');
        if (sel.length === 0)    { h.value=''; lbl.textContent='No hours selected'; lbl.className='text-danger'; }
        else if (sel.length===24){ h.value='All (24hrs)'; lbl.textContent='✅ All 24 hours selected'; lbl.className='text-success fw-semibold'; }
        else { h.value=sel.join(', '); lbl.textContent='✅ '+sel.length+' hour(s) selected'; lbl.className='text-primary'; }
        lbl.style.fontSize='11px';
    }
    function addCalc() {
        var cnt  = Array.from(hrCheckboxes).filter(function(c){return c.checked;}).length;
        var pHrs = parseFloat(document.getElementById('add_per_hrs_amount').value)||0;
        var pDay = cnt * pHrs;
        document.getElementById('add_per_day_amount').value = pDay>0 ? pDay.toFixed(2) : '';
        var days = daysBetween(document.getElementById('add_start_date').value, document.getElementById('add_end_date').value);
        document.getElementById('add_total_amount').value = (pDay>0&&days>0) ? (pDay*days).toFixed(2) : '';
    }
    if (checkAll) {
        checkAll.addEventListener('change', function () {
            hrCheckboxes.forEach(function(cb){cb.checked=checkAll.checked;}); updateAddHidden(); addCalc();
        });
    }
    hrCheckboxes.forEach(function(cb) {
        cb.addEventListener('change', function () {
            var a=Array.from(hrCheckboxes).every(function(c){return c.checked;});
            var s=Array.from(hrCheckboxes).some(function(c){return c.checked;});
            if (checkAll){checkAll.checked=a; checkAll.indeterminate=!a&&s;}
            updateAddHidden(); addCalc();
        });
    });
    var aS=document.getElementById('add_start_date'), aE=document.getElementById('add_end_date'), aP=document.getElementById('add_per_hrs_amount');
    if (aS) aS.addEventListener('change', function(){if(aE){aE.min=this.value;if(aE.value<this.value)aE.value='';}addCalc();});
    if (aE) aE.addEventListener('change', addCalc);
    if (aP) aP.addEventListener('input',  addCalc);

    var addModalEl = document.getElementById('addModal');
    if (addModalEl) {
        addModalEl.addEventListener('hidden.bs.modal', function () {
            if(aS)aS.value=''; if(aE){aE.value='';aE.min='';} if(aP)aP.value='';
            hrCheckboxes.forEach(function(cb){cb.checked=false;});
            if(checkAll){checkAll.checked=false;checkAll.indeterminate=false;}
            var h=document.getElementById('add_running_hrs_hidden'); if(h)h.value='';
            var pd=document.getElementById('add_per_day_amount'); if(pd)pd.value='';
            var ta=document.getElementById('add_total_amount');   if(ta)ta.value='';
            var lbl=document.getElementById('selected_hrs_label');
            if(lbl){lbl.textContent='No hours selected';lbl.className='text-muted';}
            var si=document.getElementById('add_member_search'); if(si)si.value='';
            var hi=document.getElementById('add_member_id');     if(hi)hi.value='';
            var ii=document.getElementById('add_member_info');   if(ii)ii.textContent='';
            var di=document.getElementById('add_member_dropdown'); if(di)di.style.display='none';
        });
    }

    // ════════════════════════════════════════════════
    // 6. EDIT MODALS — checkboxes + calc
    // ════════════════════════════════════════════════
    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    (function () {
        var sid  = <?php echo e($schedule->id); ?>;
        var eS   = document.getElementById('edit_start_date_'    +sid);
        var eE   = document.getElementById('edit_end_date_'      +sid);
        var eP   = document.getElementById('edit_per_hrs_amount_'+sid);
        var ePD  = document.getElementById('edit_per_day_amount_'+sid);
        var eTA  = document.getElementById('edit_total_amount_'  +sid);
        var eCbs = document.querySelectorAll('.edit-hr-'+sid);
        var eCA  = document.getElementById('edit_check_all_'     +sid);
        var eH   = document.getElementById('edit_running_hrs_hidden_' +sid);
        var eLbl = document.getElementById('edit_selected_hrs_label_' +sid);
        if (!eS||!eE) return;

        function eCalc() {
            var cnt  = Array.from(eCbs).filter(function(c){return c.checked;}).length;
            var pHrs = parseFloat(eP?eP.value:0)||0;
            var pDay = cnt*pHrs;
            if(ePD) ePD.value = pDay>0 ? pDay.toFixed(2) : '';
            var days = daysBetween(eS.value, eE.value);
            if(eTA) eTA.value = (pDay>0&&days>0) ? (pDay*days).toFixed(2) : '';
        }
        function eUpdateHidden() {
            var sel = Array.from(eCbs).filter(function(c){return c.checked;}).map(function(c){return c.value;});
            if(!eH||!eLbl) return;
            if(sel.length===0){eH.value='';eLbl.textContent='No hours selected';eLbl.className='text-danger';}
            else if(sel.length===24){eH.value='All (24hrs)';eLbl.textContent='✅ All 24 hours selected';eLbl.className='text-success fw-semibold';}
            else{eH.value=sel.join(', ');eLbl.textContent='✅ '+sel.length+' hour(s) selected';eLbl.className='text-primary';}
            eLbl.style.fontSize='11px';
        }
        eS.addEventListener('change', function(){eE.min=this.value;if(eE.value&&eE.value<this.value)eE.value='';eCalc();});
        eE.addEventListener('change', eCalc);
        if(eP) eP.addEventListener('input', eCalc);
        if(eCA){
            var allSaved=Array.from(eCbs).every(function(c){return c.checked;});
            eCA.checked=allSaved; eCA.indeterminate=!allSaved&&Array.from(eCbs).some(function(c){return c.checked;});
            eCA.addEventListener('change', function(){eCbs.forEach(function(cb){cb.checked=eCA.checked;});eUpdateHidden();eCalc();});
        }
        eCbs.forEach(function(cb){
            cb.addEventListener('change', function(){
                var a=Array.from(eCbs).every(function(c){return c.checked;});
                var s=Array.from(eCbs).some(function(c){return c.checked;});
                if(eCA){eCA.checked=a;eCA.indeterminate=!a&&s;}
                eUpdateHidden(); eCalc();
            });
        });
    })();
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\smartboatTourProject\resources\views/admin/stpschedule.blade.php ENDPATH**/ ?>