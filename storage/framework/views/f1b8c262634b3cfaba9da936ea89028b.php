

<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>My Passive Bonus</h1>
            <p>Track all your passive bonus earnings and rewards</p>
        </div>
    </div>

    
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #1a3a6b;">
                <div class="pb-stat-label">Total Records</div>
                <div class="pb-stat-value" style="color:#1a3a6b;">
                    <?php echo e($bonuses->total()); ?>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #27500a;">
                <div class="pb-stat-label">Total Bonus</div>
                <div class="pb-stat-value" style="color:#27500a;font-size:18px;">
                    ₹<?php echo e(number_format($bonuses->sum('bonus_amount'), 2)); ?>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #3c3489;">
                <div class="pb-stat-label">Active Bonuses</div>
                <div class="pb-stat-value" style="color:#3c3489;">
                    <?php echo e($bonuses->where('status', 1)->count()); ?>

                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="pb-stat-card" style="border-top:3px solid #6c757d;">
                <div class="pb-stat-label">Inactive Bonuses</div>
                <div class="pb-stat-value" style="color:#6c757d;">
                    <?php echo e($bonuses->where('status', 0)->count()); ?>

                </div>
            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-cash-stack me-2"></i>Passive Bonus History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">
                <table id="passiveBonusTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th style="width:46px;">#</th>
                            <th>Bonus Type</th>
                            <th>Quantity</th>
                            <th>Rate</th>
                            <th>Bonus Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $bonuses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bonus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="bonus-row" style="cursor:pointer;"
                            data-type="<?php echo e($bonus->bonus_type); ?>"
                            data-qty="<?php echo e($bonus->total_quantity); ?>"
                            data-rate="<?php echo e($bonus->rate); ?>"
                            data-amount="<?php echo e(number_format($bonus->bonus_amount, 2)); ?>"
                            data-status="<?php echo e($bonus->status); ?>"
                            data-date="<?php echo e(\Carbon\Carbon::parse($bonus->created_at)->format('d M Y, h:i A')); ?>">
                            <td class="text-center text-muted" style="font-size:12px;"><?php echo e($loop->iteration); ?></td>
                            <td>
                                <span class="badge"
                                      style="background:#e6f1fb;color:#0c447c;font-size:11px;padding:4px 10px;border-radius:20px;font-weight:600;">
                                    <?php echo e($bonus->bonus_type); ?>

                                </span>
                            </td>
                            <td style="font-weight:500;color:#495057;"><?php echo e($bonus->total_quantity); ?></td>
                            <td style="font-weight:600;color:#1a3a6b;"><?php echo e($bonus->rate); ?></td>
                            <td style="font-weight:700;color:#27500a;white-space:nowrap;">
                                ₹<?php echo e(number_format($bonus->bonus_amount, 2)); ?>

                            </td>
                            <td>
                                <?php if($bonus->status == 1): ?>
                                    <span class="badge bg-success" style="font-size:11px;padding:4px 10px;">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary" style="font-size:11px;padding:4px 10px;">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:500;color:#333;font-size:12px;">
                                    <?php echo e(\Carbon\Carbon::parse($bonus->created_at)->format('d M Y')); ?>

                                </div>
                                <div style="font-size:11px;color:#adb5bd;">
                                    <?php echo e(\Carbon\Carbon::parse($bonus->created_at)->format('h:i A')); ?>

                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5" style="color:#6c757d;font-size:13px;">
                                <i class="bi bi-cash-coin" style="font-size:30px;display:block;margin-bottom:10px;color:#dee2e6;"></i>
                                No bonus records found.
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    
    <div class="modal fade" id="bonusDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background:#1a3a6b;color:#fff;padding:12px 20px;">
                    <h6 class="modal-title mb-0"
                        style="font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">
                        <i class="bi bi-cash-stack me-2"></i>Bonus Detail
                    </h6>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="detail-field-label">Bonus Type</div>
                            <div id="d-type" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Status</div>
                            <div id="d-status"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Quantity</div>
                            <div id="d-qty" class="detail-field-value" style="color:#333;"></div>
                        </div>
                        <div class="col-6">
                            <div class="detail-field-label">Rate</div>
                            <div id="d-rate" class="detail-field-value" style="color:#1a3a6b;"></div>
                        </div>
                        <div class="col-12">
                            <div style="background:#f4f7fb;border-left:4px solid #27500a;border-radius:0 6px 6px 0;padding:14px 18px;">
                                <div class="detail-field-label">Bonus Amount</div>
                                <div id="d-amount" style="font-size:28px;font-weight:800;color:#27500a;"></div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-field-label">Date &amp; Time</div>
                            <div id="d-date" style="font-size:13px;font-weight:500;color:#333;"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    
    <style>
        .pb-stat-card {
            background:#fff;border:0.5px solid #dee2e6;
            border-radius:8px;padding:16px 18px;
        }
        .pb-stat-label {
            font-size:11px;color:#6c757d;margin-bottom:6px;
            text-transform:uppercase;letter-spacing:.06em;
        }
        .pb-stat-value { font-size:22px;font-weight:700;line-height:1.2; }

        .detail-field-label { font-size:11px;color:#6c757d;margin-bottom:4px; }
        .detail-field-value { font-size:14px;font-weight:700; }

        #passiveBonusTable thead th { background:#2c5f2e;color:#fff;border-color:#2c5f2e; }
        #passiveBonusTable tbody tr:hover { background:#f4f7fb; }

        @media (max-width: 576px) {
            .pb-stat-value { font-size:18px; }
            .pb-stat-label { font-size:10px; }
        }
    </style>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
$(document).ready(function () {

    // ════════════════════════════════════════
    // 1. DATATABLES
    // ════════════════════════════════════════
    <?php if($bonuses->count()): ?>
    $('#passiveBonusTable').DataTable({
        order: [[0, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0] }
        ],
        rowCallback: function (row, data, index) {
            $('td:eq(0)', row).text(index + 1);
        },
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Passive Bonus History',
                exportOptions:{ columns:[0,1,2,3,4,5,6] }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Passive Bonus History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ columns:[0,1,2,3,4,5,6] }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Passive Bonus History',
                exportOptions:{ columns:[0,1,2,3,4,5,6] }
            }
        ],
        language:{
            search:'<i class="bi bi-search"></i>',
            searchPlaceholder:'Search bonuses…',
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
    // 2. ROW CLICK → DETAIL MODAL
    // ════════════════════════════════════════
    $(document).on('click', '#passiveBonusTable tbody .bonus-row', function () {
        var row    = $(this);
        var type   = row.data('type');
        var status = row.data('status');

        $('#d-type').text(type);
        $('#d-qty').text(row.data('qty'));
        $('#d-rate').text(row.data('rate'));
        $('#d-amount').text('₹' + row.data('amount'));
        $('#d-date').text(row.data('date'));
        $('#d-status').html(
            status == 1
            ? '<span class="badge bg-success" style="font-size:11px;">Active</span>'
            : '<span class="badge bg-secondary" style="font-size:11px;">Inactive</span>'
        );

        new bootstrap.Modal(document.getElementById('bonusDetailModal')).show();
    });

});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\backup\16-04-2026\resources\views/member/passivebonus.blade.php ENDPATH**/ ?>