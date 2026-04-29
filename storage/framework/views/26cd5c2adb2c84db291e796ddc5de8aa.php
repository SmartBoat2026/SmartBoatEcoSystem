<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>STP Schedules</h1>
            <p>Manage member STP running schedules</p>
        </div>
        <div class="page-actions">
            <button class="btn-primary-custom" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> Add Schedule
            </button>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-calendar2-check-fill me-2"></i>Member STP Schedules</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">

                
                <div id="bulkActionBar" class="bulk-action-bar">
                    <span id="bulkCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                    <form id="bulkDeleteForm"
                          action="<?php echo e(route('member.memberstpschedules.bulkDelete')); ?>"
                          method="POST" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <div id="bulkDeleteInputs"></div>
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete selected records? This cannot be undone.')">
                            <i class="bi bi-trash me-1"></i>Delete Selected
                        </button>
                    </form>
                    <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x me-1"></i>Clear
                    </button>
                </div>

                <table id="stpSchedulesTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th style="width:40px;text-align:center;">
                                <input type="checkbox" id="selectAll"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </th>
                            <th>#</th>
                            <th>Member</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Running Hrs</th>
                            <th>Per Hr (₹)</th>
                            <th>Per Day (₹)</th>
                            <th>Total (₹)</th>
                            <th>Status</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="<?php echo e($schedule->id); ?>"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </td>
                            <td style="font-size:12px;color:#6c757d;"><?php echo e($index + 1); ?></td>
                            <td>
                                <div style="font-weight:700;color:#1a3a6b;font-size:13px;">
                                    <?php echo e($schedule->member_id); ?>

                                </div>
                                <?php if(isset($memberInfos[$schedule->member_id])): ?>
                                <div style="font-size:12px;color:#333;margin-top:2px;">
                                    <?php echo e($memberInfos[$schedule->member_id]->name); ?>

                                </div>
                                <div style="font-size:11px;color:#6c757d;margin-top:1px;">
                                    <i class="bi bi-telephone-fill me-1" style="font-size:10px;color:#1a3a6b;"></i>
                                    <?php echo e($memberInfos[$schedule->member_id]->phone); ?>

                                </div>
                                <?php endif; ?>
                            </td>
                            <td style="font-size:12px;white-space:nowrap;"><?php echo e($schedule->start_date); ?></td>
                            <td style="font-size:12px;white-space:nowrap;"><?php echo e($schedule->end_date); ?></td>
                            <td style="font-size:12px;max-width:180px;">
                                <span class="badge"
                                      style="background:#e6f1fb;color:#0c447c;font-size:10px;padding:3px 8px;border-radius:20px;white-space:normal;line-height:1.4;">
                                    <?php echo e(Str::limit($schedule->running_hrs, 40)); ?>

                                </span>
                            </td>
                            <td style="font-weight:600;color:#27500a;white-space:nowrap;">
                                ₹<?php echo e(number_format($schedule->per_hrs_amount, 2)); ?>

                            </td>
                            <td style="font-weight:600;color:#27500a;white-space:nowrap;">
                                ₹<?php echo e(number_format($schedule->per_day_amount, 2)); ?>

                            </td>
                            <td style="font-weight:700;color:#1a3a6b;white-space:nowrap;">
                                ₹<?php echo e(number_format($schedule->total_amount, 2)); ?>

                            </td>
                            <td>
                                <form action="<?php echo e(route('member.memberstpschedules.toggleStatus', $schedule->id)); ?>"
                                      method="POST" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit"
                                            class="btn btn-sm <?php echo e($schedule->status == 1 ? 'btn-success' : 'btn-secondary'); ?>"
                                            style="font-size:11px;padding:3px 10px;">
                                        <?php echo e($schedule->status == 1 ? 'Active' : 'Inactive'); ?>

                                    </button>
                                </form>
                            </td>
                            <td style="font-size:11px;color:#6c757d;white-space:nowrap;">
                                <?php echo e(\Carbon\Carbon::parse($schedule->created_at)->format('d M Y')); ?>

                            </td>
                            <td style="white-space:nowrap;">
                                <button class="btn btn-warning btn-sm me-1"
                                        style="font-size:11px;padding:3px 10px;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo e($schedule->id); ?>">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="<?php echo e(route('member.memberstpschedules.delete', $schedule->id)); ?>"
                                      method="POST" style="display:inline;"
                                      onsubmit="return confirm('Delete this schedule?')">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            style="font-size:11px;padding:3px 10px;">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="12" class="text-center py-5" style="color:#6c757d;font-size:13px;">
                                <i class="bi bi-calendar-x" style="font-size:30px;display:block;margin-bottom:10px;color:#dee2e6;"></i>
                                No schedule records found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="modal fade" id="editModal<?php echo e($schedule->id); ?>" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" style="font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                        <i class="bi bi-pencil me-2"></i>Edit Schedule
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('member.memberstpschedules.update', $schedule->id)); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">

                            
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size:12px;color:#1a3a6b;">
                                    Member ID <small class="text-muted fw-normal">(search by ID / Name / Phone)</small>
                                </label>
                                <div class="member-search-wrapper position-relative">
                                    <input type="text"
                                        class="form-control form-control-sm member-search-input"
                                        data-target="edit_member_id_<?php echo e($schedule->id); ?>"
                                        data-info="edit_member_info_<?php echo e($schedule->id); ?>"
                                        placeholder="Type to search member..."
                                        value="<?php echo e($schedule->member_id); ?>"
                                        autocomplete="off">
                                    <div class="member-dropdown list-group position-absolute w-100 shadow"
                                        id="edit_member_dropdown_<?php echo e($schedule->id); ?>"
                                        style="display:none;z-index:9999;max-height:200px;overflow-y:auto;top:100%;left:0;right:0;"></div>
                                </div>
                                                            <input type="text" name="member_id"
                                id="edit_member_id_<?php echo e($schedule->id); ?>"
                                value="<?php echo e($schedule->member_id); ?>"
                                style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none;"
                                required tabindex="-1">
                                <small id="edit_member_info_<?php echo e($schedule->id); ?>" class="text-success" style="font-size:11px;">
                                    <?php if(isset($memberInfos[$schedule->member_id])): ?>
                                        ✅ <?php echo e($memberInfos[$schedule->member_id]->name); ?> | 📞 <?php echo e($memberInfos[$schedule->member_id]->phone); ?>

                                    <?php endif; ?>
                                </small>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label" style="font-size:12px;">Start Date</label>
                                <input type="date" name="start_date"
                                    id="edit_start_date_<?php echo e($schedule->id); ?>"
                                    class="form-control form-control-sm"
                                    value="<?php echo e($schedule->start_date); ?>" required>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label" style="font-size:12px;">End Date</label>
                                <input type="date" name="end_date"
                                    id="edit_end_date_<?php echo e($schedule->id); ?>"
                                    class="form-control form-control-sm"
                                    value="<?php echo e($schedule->end_date); ?>"
                                    min="<?php echo e($schedule->start_date); ?>" required>
                            </div>

                            
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:12px;color:#1a3a6b;">Running Hrs</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input edit-check-all"
                                            type="checkbox"
                                            id="edit_check_all_<?php echo e($schedule->id); ?>"
                                            data-id="<?php echo e($schedule->id); ?>">
                                        <label class="form-check-label fw-bold text-primary"
                                            for="edit_check_all_<?php echo e($schedule->id); ?>"
                                            style="font-size:12px;">
                                            Select All (24hrs)
                                        </label>
                                    </div>
                                </div>
                                <div class="border rounded p-2" style="max-height:180px;overflow-y:auto;background:#f8f9fa;">
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
                                <small id="edit_selected_hrs_label_<?php echo e($schedule->id); ?>" class="text-primary" style="font-size:11px;">
                                    <?php echo e($schedule->running_hrs); ?>

                                </small>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">Per Hrs Amount (₹)</label>
                                <input type="number" name="per_hrs_amount"
                                    id="edit_per_hrs_amount_<?php echo e($schedule->id); ?>"
                                    class="form-control form-control-sm"
                                    value="<?php echo e($schedule->per_hrs_amount); ?>"
                                    min="0" step="0.01" required>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">
                                    Per Day Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="per_day_amount"
                                    id="edit_per_day_amount_<?php echo e($schedule->id); ?>"
                                    class="form-control form-control-sm fw-bold"
                                    style="background:#f8f9fa;"
                                    value="<?php echo e($schedule->per_day_amount); ?>" readonly>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">
                                    Total Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="total_amount"
                                    id="edit_total_amount_<?php echo e($schedule->id); ?>"
                                    class="form-control form-control-sm fw-bold"
                                    style="background:#f8f9fa;"
                                    value="<?php echo e($schedule->total_amount); ?>" readonly>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">Status</label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="1" <?php echo e($schedule->status == 1 ? 'selected' : ''); ?>>Active</option>
                                    <option value="0" <?php echo e($schedule->status == 0 ? 'selected' : ''); ?>>Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check2 me-1"></i>Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" style="font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                        <i class="bi bi-plus-circle me-2"></i>Add Schedule
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('member.memberstpschedules.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="row g-3">

                            
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold" style="font-size:12px;color:#1a3a6b;">
                                    Member ID <small class="text-muted fw-normal">(search by ID / Name / Phone)</small>
                                </label>
                                <div class="member-search-wrapper position-relative">
                                    <input type="text"
                                        class="form-control form-control-sm"
                                        id="add_member_search"
                                        data-target="add_member_id"
                                        data-info="add_member_info"
                                        placeholder="Type to search member..."
                                        value="<?php echo e(session('member_memberID')); ?>"
                                        autocomplete="off">
                                    <div class="list-group position-absolute w-100 shadow"
                                        id="add_member_dropdown"
                                        style="display:none;z-index:9999;max-height:200px;overflow-y:auto;top:100%;left:0;right:0;"></div>
                                </div>
                               <input type="text" name="member_id" id="add_member_id"
                                value="<?php echo e(session('member_memberID')); ?>"
                                style="position:absolute;opacity:0;width:1px;height:1px;pointer-events:none;"
                                required tabindex="-1">
                                <small id="add_member_info" class="text-success" style="font-size:11px;"></small>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label" style="font-size:12px;">Start Date</label>
                                <input type="date" name="start_date" id="add_start_date"
                                    class="form-control form-control-sm" required>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label" style="font-size:12px;">End Date</label>
                                <input type="date" name="end_date" id="add_end_date"
                                    class="form-control form-control-sm" required>
                            </div>

                            
                            <div class="col-12">
                                <label class="form-label fw-semibold" style="font-size:12px;color:#1a3a6b;">Running Hrs</label>
                                <div class="mb-2">
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" id="check_all_hrs">
                                        <label class="form-check-label fw-bold text-primary"
                                            for="check_all_hrs" style="font-size:12px;">
                                            Select All (24hrs)
                                        </label>
                                    </div>
                                </div>
                                <div class="border rounded p-2" style="max-height:180px;overflow-y:auto;background:#f8f9fa;">
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
                                <small class="text-muted" id="selected_hrs_label" style="font-size:11px;">No hours selected</small>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">Per Hrs Amount (₹)</label>
                                <input type="number" name="per_hrs_amount" id="add_per_hrs_amount"
                                    class="form-control form-control-sm"
                                    placeholder="Enter per hr amount"
                                    min="0" step="0.01" required>
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">
                                    Per Day Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="per_day_amount" id="add_per_day_amount"
                                    class="form-control form-control-sm fw-bold"
                                    style="background:#f8f9fa;"
                                    readonly placeholder="Auto calculated">
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">
                                    Total Amount (₹) <small class="text-success fw-semibold">(auto)</small>
                                </label>
                                <input type="text" name="total_amount" id="add_total_amount"
                                    class="form-control form-control-sm fw-bold"
                                    style="background:#f8f9fa;"
                                    readonly placeholder="Auto calculated">
                            </div>

                            
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label" style="font-size:12px;">Status</label>
                                <select name="status" class="form-select form-select-sm" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                        </div>
                    </div>
                    <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary btn-sm px-4">
                            <i class="bi bi-check2 me-1"></i>Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

   

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ════════════════════════════════════════
    // 1. DATATABLES
    // ════════════════════════════════════════
    <?php if($schedules->count()): ?>
    $('#stpSchedulesTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,11] }
        ],
        rowCallback: function (row, data, index) {
            $('td:eq(1)', row).text(index + 1);
        },
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'STP Schedules',
                exportOptions:{ columns:[1,2,3,4,5,6,7,8,9,10] }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'STP Schedules',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ columns:[1,2,3,4,5,6,7,8,9,10] }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'STP Schedules',
                exportOptions:{ columns:[1,2,3,4,5,6,7,8,9,10] }
            }
        ],
        language:{
            search:'<i class="bi bi-search"></i>',
            searchPlaceholder:'Search schedules…',
            lengthMenu:'Show _MENU_ entries',
            info:'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty:'No records found',
            paginate:{ previous:'‹', next:'›' }
        },
        dom:"<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
    <?php endif; ?>

    // ════════════════════════════════════════
    // 2. BULK DELETE
    // ════════════════════════════════════════
    function updateBulkBar() {
        var checked = document.querySelectorAll('.row-checkbox:checked');
        var bar     = document.getElementById('bulkActionBar');
        var inputs  = document.getElementById('bulkDeleteInputs');
        if (checked.length > 0) {
            bar.classList.add('show');
            document.getElementById('bulkCount').textContent = checked.length + ' selected';
            inputs.innerHTML = '';
            checked.forEach(function (cb) {
                var inp = document.createElement('input');
                inp.type  = 'hidden';
                inp.name  = 'ids[]';
                inp.value = cb.value;
                inputs.appendChild(inp);
            });
        } else {
            bar.classList.remove('show');
            inputs.innerHTML = '';
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
            var total   = document.querySelectorAll('.row-checkbox').length;
            var checked = document.querySelectorAll('.row-checkbox:checked').length;
            if (selectAllBox) {
                selectAllBox.checked       = (checked === total);
                selectAllBox.indeterminate = (checked > 0 && checked < total);
            }
            updateBulkBar();
        });
    });

    document.getElementById('clearSelection').addEventListener('click', function () {
        document.querySelectorAll('.row-checkbox').forEach(cb => { cb.checked = false; cb.closest('tr').classList.remove('row-selected'); });
        if (selectAllBox) { selectAllBox.checked = false; selectAllBox.indeterminate = false; }
        updateBulkBar();
    });

    // ════════════════════════════════════════
    // 3. MEMBER SEARCH
    // ════════════════════════════════════════
    var searchUrl    = "<?php echo e(route('member.memberstpschedules.searchMember')); ?>";
    var searchTimers = {};

    function initMemberSearch(inputEl, hiddenId, infoId, dropdownEl) {
        if (!inputEl || !dropdownEl) return;
        var hiddenEl = document.getElementById(hiddenId);
        var infoEl   = document.getElementById(infoId);
        if (!hiddenEl || !infoEl) return;

        document.addEventListener('click', function (e) {
            if (!inputEl.contains(e.target) && !dropdownEl.contains(e.target)) {
                dropdownEl.style.display = 'none';
            }
        });

        inputEl.addEventListener('input', function () {
            var q = this.value.trim();
            hiddenEl.value = ''; infoEl.textContent = ''; infoEl.className = 'text-muted';
            dropdownEl.innerHTML = ''; dropdownEl.style.display = 'none';
            if (q.length < 1) return;
            clearTimeout(searchTimers[dropdownEl.id]);
            searchTimers[dropdownEl.id] = setTimeout(function () {
                dropdownEl.innerHTML     = '<div class="list-group-item text-muted" style="font-size:12px;">Searching…</div>';
                dropdownEl.style.display = 'block';
                fetch(searchUrl + '?q=' + encodeURIComponent(q), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(r => r.json())
                    .then(function (data) {
                        dropdownEl.innerHTML = '';
                        if (!data.length) {
                            dropdownEl.innerHTML     = '<div class="list-group-item text-danger" style="font-size:12px;">No member found</div>';
                            dropdownEl.style.display = 'block';
                            return;
                        }
                        data.forEach(function (m) {
                            var item = document.createElement('button');
                            item.type      = 'button';
                            item.className = 'list-group-item list-group-item-action py-2 px-3';
                            item.style.fontSize = '12px';
                            item.innerHTML = '<span class="fw-bold text-primary">' + escHtml(m.memberID) + '</span>'
                                + ' — ' + escHtml(m.name)
                                + ' <span class="text-muted">(' + escHtml(m.phone) + ')</span>';
                            item.addEventListener('click', function () {
                                inputEl.value            = m.memberID;
                                hiddenEl.value           = m.memberID;
                                infoEl.textContent       = '✅ ' + m.name + ' | ' + m.phone;
                                infoEl.className         = 'text-success';
                                dropdownEl.style.display = 'none';
                            });
                            dropdownEl.appendChild(item);
                        });
                        dropdownEl.style.display = 'block';
                    })
                    .catch(function () {
                        dropdownEl.innerHTML     = '<div class="list-group-item text-danger" style="font-size:12px;">Search error</div>';
                        dropdownEl.style.display = 'block';
                    });
            }, 300);
        });

        inputEl.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') dropdownEl.style.display = 'none';
        });
    }

    function escHtml(str) {
        var d = document.createElement('div');
        d.appendChild(document.createTextNode(str || ''));
        return d.innerHTML;
    }

    // Wire Add modal
    initMemberSearch(
        document.getElementById('add_member_search'),
        'add_member_id', 'add_member_info',
        document.getElementById('add_member_dropdown')
    );

    // Wire Edit modals
    document.querySelectorAll('.member-search-input').forEach(function (inputEl) {
        var targetId = inputEl.getAttribute('data-target');
        var infoId   = inputEl.getAttribute('data-info');
        if (!targetId || targetId === 'add_member_id') return;
        var wrapper    = inputEl.closest('.member-search-wrapper');
        var dropdownEl = wrapper ? wrapper.querySelector('.member-dropdown') : null;
        if (!dropdownEl) return;
        initMemberSearch(inputEl, targetId, infoId, dropdownEl);
    });

    // ════════════════════════════════════════
    // 4. DATE HELPERS
    // ════════════════════════════════════════
    function daysBetween(s, e) {
        if (!s || !e) return 0;
        var diff = Math.round((new Date(e) - new Date(s)) / 86400000) + 1;
        return diff > 0 ? diff : 0;
    }

    // ════════════════════════════════════════
    // 5. ADD MODAL — CHECKBOXES + CALC
    // ════════════════════════════════════════
    var checkAll     = document.getElementById('check_all_hrs');
    var hrCheckboxes = document.querySelectorAll('.hr-checkbox');

    function updateAddHidden() {
        var selected = Array.from(hrCheckboxes).filter(c => c.checked).map(c => c.value);
        var hidden   = document.getElementById('add_running_hrs_hidden');
        var label    = document.getElementById('selected_hrs_label');
        if (selected.length === 0) {
            hidden.value = ''; label.textContent = 'No hours selected'; label.className = 'text-danger';
        } else if (selected.length === 24) {
            hidden.value = 'All (24hrs)'; label.textContent = '✅ All 24 hours selected'; label.className = 'text-success fw-semibold';
        } else {
            hidden.value = selected.join(', '); label.textContent = '✅ ' + selected.length + ' hour(s) selected'; label.className = 'text-primary';
        }
        label.style.fontSize = '11px';
    }

    function addRecalculate() {
        var cnt    = Array.from(hrCheckboxes).filter(c => c.checked).length;
        var perHrs = parseFloat(document.getElementById('add_per_hrs_amount').value) || 0;
        var perDay = cnt * perHrs;
        document.getElementById('add_per_day_amount').value = perDay > 0 ? perDay.toFixed(2) : '';
        var days = daysBetween(document.getElementById('add_start_date').value, document.getElementById('add_end_date').value);
        document.getElementById('add_total_amount').value = (perDay > 0 && days > 0) ? (perDay * days).toFixed(2) : '';
    }

    if (checkAll) {
        checkAll.addEventListener('change', function () {
            hrCheckboxes.forEach(cb => cb.checked = checkAll.checked);
            updateAddHidden(); addRecalculate();
        });
    }

    hrCheckboxes.forEach(function (cb) {
        cb.addEventListener('change', function () {
            var allC  = Array.from(hrCheckboxes).every(c => c.checked);
            var someC = Array.from(hrCheckboxes).some(c => c.checked);
            if (checkAll) { checkAll.checked = allC; checkAll.indeterminate = !allC && someC; }
            updateAddHidden(); addRecalculate();
        });
    });

    var addStartDate = document.getElementById('add_start_date');
    var addEndDate   = document.getElementById('add_end_date');
    if (addStartDate) {
        addStartDate.addEventListener('change', function () {
            addEndDate.min = this.value;
            if (addEndDate.value && addEndDate.value < this.value) addEndDate.value = '';
            addRecalculate();
        });
    }
    if (addEndDate) addEndDate.addEventListener('change', addRecalculate);

    var addPerHrs = document.getElementById('add_per_hrs_amount');
    if (addPerHrs) addPerHrs.addEventListener('input', addRecalculate);

    // Reset on close
    var addModal = document.getElementById('addModal');
    if (addModal) {
        addModal.addEventListener('hidden.bs.modal', function () {
            if (addStartDate) addStartDate.value = '';
            if (addEndDate)   { addEndDate.value = ''; addEndDate.min = ''; }
            hrCheckboxes.forEach(cb => cb.checked = false);
            if (checkAll) { checkAll.checked = false; checkAll.indeterminate = false; }
            document.getElementById('add_running_hrs_hidden').value   = '';
            if (addPerHrs) addPerHrs.value = '';
            document.getElementById('add_per_day_amount').value       = '';
            document.getElementById('add_total_amount').value         = '';
            var lbl = document.getElementById('selected_hrs_label');
            lbl.textContent = 'No hours selected'; lbl.className = 'text-muted'; lbl.style.fontSize = '11px';
            document.getElementById('add_member_search').value        = '';
            document.getElementById('add_member_id').value            = '';
            document.getElementById('add_member_info').textContent    = '';
            document.getElementById('add_member_dropdown').style.display = 'none';
        });
    }

    // ════════════════════════════════════════
    // 6. EDIT MODALS — CHECKBOXES + CALC
    // ════════════════════════════════════════
    <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    (function () {
        var sid    = <?php echo e($schedule->id); ?>;
        var eStart = document.getElementById('edit_start_date_' + sid);
        var eEnd   = document.getElementById('edit_end_date_'   + sid);
        if (!eStart || !eEnd) return;

        eStart.addEventListener('change', function () {
            eEnd.min = this.value;
            if (eEnd.value && eEnd.value < this.value) eEnd.value = '';
            editRecalculate(sid);
        });
        eEnd.addEventListener('change', function () { editRecalculate(sid); });

        var editCheckAll = document.getElementById('edit_check_all_' + sid);
        var editCbs      = document.querySelectorAll('.edit-hr-' + sid);

        if (editCheckAll) {
            var allSaved = Array.from(editCbs).every(c => c.checked);
            editCheckAll.checked       = allSaved;
            editCheckAll.indeterminate = !allSaved && Array.from(editCbs).some(c => c.checked);
            editCheckAll.addEventListener('change', function () {
                editCbs.forEach(cb => cb.checked = editCheckAll.checked);
                updateEditHidden(sid); editRecalculate(sid);
            });
        }

        editCbs.forEach(function (cb) {
            cb.addEventListener('change', function () {
                var allC  = Array.from(editCbs).every(c => c.checked);
                var someC = Array.from(editCbs).some(c => c.checked);
                if (editCheckAll) { editCheckAll.checked = allC; editCheckAll.indeterminate = !allC && someC; }
                updateEditHidden(sid); editRecalculate(sid);
            });
        });

        var perHrsInput = document.getElementById('edit_per_hrs_amount_' + sid);
        if (perHrsInput) perHrsInput.addEventListener('input', function () { editRecalculate(sid); });
    })();
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

    function updateEditHidden(sid) {
        var editCbs  = document.querySelectorAll('.edit-hr-' + sid);
        var selected = Array.from(editCbs).filter(c => c.checked).map(c => c.value);
        var hidden   = document.getElementById('edit_running_hrs_hidden_' + sid);
        var label    = document.getElementById('edit_selected_hrs_label_' + sid);
        if (selected.length === 0) {
            hidden.value = ''; label.textContent = 'No hours selected'; label.className = 'text-danger';
        } else if (selected.length === 24) {
            hidden.value = 'All (24hrs)'; label.textContent = '✅ All 24 hours selected'; label.className = 'text-success fw-semibold';
        } else {
            hidden.value = selected.join(', '); label.textContent = '✅ ' + selected.length + ' hour(s) selected'; label.className = 'text-primary';
        }
        label.style.fontSize = '11px';
    }

    function editRecalculate(sid) {
        var editCbs       = document.querySelectorAll('.edit-hr-' + sid);
        var selectedCount = Array.from(editCbs).filter(c => c.checked).length;
        var perHrs        = parseFloat(document.getElementById('edit_per_hrs_amount_' + sid).value) || 0;
        var perDay        = selectedCount * perHrs;
        document.getElementById('edit_per_day_amount_' + sid).value = perDay > 0 ? perDay.toFixed(2) : '';
        var days = daysBetween(
            document.getElementById('edit_start_date_' + sid).value,
            document.getElementById('edit_end_date_'   + sid).value
        );
        document.getElementById('edit_total_amount_' + sid).value = (perDay > 0 && days > 0) ? (perDay * days).toFixed(2) : '';
    }

});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\Main\resources\views/member/memberstpschedules.blade.php ENDPATH**/ ?>