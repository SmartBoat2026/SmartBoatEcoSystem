
<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Member Activation Requests</h1>
            <p>Manage and track all your member activation requests in one place</p>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table id="memberTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        
                        <th style="width:50px">#</th>
                        <th>Member ID</th>
                        <th>Member Info</th>
                        <th>Sponsor ID</th>
                        <th>Amount</th>
                        <th>Upload File</th>
                        <th>UTR No</th>
                        <th>Message</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($row->memberID); ?></td>

                            <td>
                                <div class="fw-semibold"><?php echo e($row->name); ?></div>
                                <div class="text-muted small"><i class="bi bi-envelope me-1"></i><?php echo e($row->email ?: '—'); ?></div>
                                <div class="text-muted small"><i class="bi bi-phone me-1"></i><?php echo e($row->phone ?: '—'); ?></div>
                            </td>

                            <td><?php echo e($row->sponser_id); ?></td>
                            <td><?php echo e($row->amount); ?></td>
                            <!--<td><img src="<?php echo e(asset('public/storage/'.$row->verification_payment_screenshot)); ?>" width="200"></td>-->
                            <td>
                                <?php if(!empty($row->verification_payment_screenshot) && file_exists(public_path('storage/'.$row->verification_payment_screenshot))): ?>
                                    <img src="<?php echo e(asset('public/storage/'.$row->verification_payment_screenshot)); ?>" width="100"
                                    style="cursor:pointer"  onclick="openImageModal('<?php echo e(asset('public/storage/'.$row->verification_payment_screenshot)); ?>')">
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($row->payment_utr_no); ?></td>
                            <td><?php echo e($row->verification_message); ?></td>
                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    <button
                                        class="btn btn-sm toggle-status btn-success"
                                        data-id="<?php echo e($row->member_id); ?>"
                                        data-status="1">
                                        Approve
                                    </button>
                                    <button
                                        class="btn btn-sm toggle-status btn-danger"
                                        data-id="<?php echo e($row->member_id); ?>"
                                        data-status="3">
                                        Reject
                                    </button>
                                </div>
                            </td>
                            
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>

        </div>
    </div>

    
    <div id="imageModal" style="display:none; position:fixed; z-index:9999; padding-top:50px; left:0; top:0; width:100%; height:100%; background:rgba(0,0,0,0.8);">
        <span onclick="closeModal()" style="position:absolute; top:20px; right:35px; color:#fff; font-size:40px; cursor:pointer;">&times;</span>
        
        <img id="modalImage" style="display:block; margin:auto; max-width:90%; max-height:80%;">
    </div>

</main>

<style>
    /* ── DataTables Buttons styling (matching category page) ── */
    #memberTable_wrapper .dt-buttons { margin-bottom: 8px; }
    #memberTable_wrapper .dt-button {
        font-size: 12px !important;
        padding: 4px 12px !important;
        border-radius: 4px !important;
        border: 1px solid #dee2e6 !important;
        background: #fff !important;
        color: #495057 !important;
        margin-right: 4px !important;
        cursor: pointer;
        transition: background .15s;
    }
    #memberTable_wrapper .dt-button:hover { background: #f0f0f0 !important; }
    #memberTable_wrapper .buttons-pdf    { border-color: #dc3545 !important; color: #dc3545 !important; }
    #memberTable_wrapper .buttons-excel  { border-color: #198754 !important; color: #198754 !important; }
    #memberTable_wrapper .buttons-print  { border-color: #0d6efd !important; color: #0d6efd !important; }
    #memberTable_wrapper .buttons-pdf:hover   { background: #dc3545 !important; color: #fff !important; }
    #memberTable_wrapper .buttons-excel:hover { background: #198754 !important; color: #fff !important; }
    #memberTable_wrapper .buttons-print:hover { background: #0d6efd !important; color: #fff !important; }

    /* ── Bulk bar ── */
    #bulkActionBar.show { display: flex !important; }

    /* ── Selected row highlight ── */
    #memberTable tbody tr.row-selected { background: #e8f4fd !important; }

    /* ── Access button hover ── */
    .btn-info.text-white:hover { opacity: 0.85; }

    /* ── Password mask reveal ── */
    .pass-mask { font-family: monospace; font-size: 13px; }
    .pass-mask.revealed { letter-spacing: normal; color: #dc3545; font-weight: 600; }
</style>

<?php $__env->startPush('scripts'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).on('click', '.toggle-status', function () {
    const btn = $(this);
    const id = btn.data('id');
    const status = btn.data('status'); // 👈 NEW VALUE

    $.ajax({
        url: "<?php echo e(route('managereport.toggleStatus', ':id')); ?>".replace(':id', id),
        type: 'POST',
        data: {
            status: status, // 👈 send to backend
            _token: '<?php echo e(csrf_token()); ?>'
        },
        success: function (res) {
            alert(res.message);
            location.reload();
        },
        error: function () {
            alert('Something went wrong.');
        }
    });
});
</script>

<script>
function openImageModal(src) {
    document.getElementById("imageModal").style.display = "block";
    document.getElementById("modalImage").src = src;
}

function closeModal() {
    document.getElementById("imageModal").style.display = "none";
}
</script>

<script>
$(document).ready(function () {

    let table = $('#memberTable').DataTable({
        pageLength: 10,
        ordering: true,
        searching: true,
        responsive: true,

        // ✅ Correct column handling
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, -1] } // # and Action
        ],

        // ✅ Buttons
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",

        buttons: [
            {
                extend: 'excelHtml5',
                text: '📊 Excel',
                className: 'buttons-excel',
                title: 'Member Report',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)' // auto exclude # & Action
                }
            },
            {
                extend: 'pdfHtml5',
                text: '📄 PDF',
                className: 'buttons-pdf',
                title: 'Member Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)'
                }
            },
            {
                extend: 'print',
                text: '🖨 Print',
                className: 'buttons-print',
                title: 'Member Report',
                exportOptions: {
                    columns: ':not(:first-child):not(:last-child)'
                }
            }
        ],

        // ✅ UI Text
        language: {
            search: "🔍 Search:",
            emptyTable: "No Data Found",
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\xampp\htdocs\backup\16-04-2026\resources\views/admin/memberactive.blade.php ENDPATH**/ ?>