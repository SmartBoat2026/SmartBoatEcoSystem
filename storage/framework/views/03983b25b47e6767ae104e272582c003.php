<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Member Payment Requests</h1>
            <p>Manage and track all your smart wallet payment requests in one place</p>
        </div>
       
    </div>

    

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            <table id="transitionTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>

                        <th style="width:50px">#</th>
                        <th>Member ID</th>
                        <th>Member Info</th>
                        <th>Payment Balance</th>
                        <th>Payment Screenshots</th>
                        <th>Date</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    
                </tbody>
            </table>

        </div>
    </div>

   

</main>

<?php echo $__env->make('chatbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('chat-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
$(document).ready(function () {

    let table = $('#transitionTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: {
            url: "<?php echo e(route('smartwallet.memberRequest.list')); ?>",
            type: "GET"
        },
        columns: [
            { data: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'member_id' },
            { data: 'member_info' },
            { data: 'amount' },
            { data: 'qr_file' },
            { data: 'created_at' },
            { data: 'status' },
            { data: 'action', orderable: false, searchable: false }
        ],
        pageLength: 10,
        responsive: true,
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 4, 7] }
        ],
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
        buttons: [
            { extend: 'excelHtml5', text: 'Excel', className: 'buttons-excel', title: 'Smart Wallet Request' },
            { extend: 'pdfHtml5', text: 'PDF', className: 'buttons-pdf', title: 'Smart Wallet Request' },
            { extend: 'print', text: 'Print', className: 'buttons-print', title: 'Smart Wallet Request' }
        ]
    });

    $(document).on('click', '.status-btn', function () {

    let id = $(this).data('id');
    let status = $(this).data('status');

    Swal.fire({
        title: "Are you sure?",
        text: "You want to change status?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#0d6efd",
        cancelButtonColor: "#dc3545",
        confirmButtonText: "Yes, change it!"
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: "<?php echo e(route('smartwallet.memberRequest.statusUpdate', ':id')); ?>".replace(':id', id),
                type: "POST",
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    status: status
                },
                success: function () {

                    Swal.fire({
                        icon: "success",
                        title: "Updated!",
                        text: "Status has been changed successfully",
                        timer: 1500,
                        showConfirmButton: false
                    });

                    $('#transitionTable').DataTable().ajax.reload(null, false);
                }
            });

        }

    });

});

});
</script>





<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\resources\views/admin/smartwallet_memberRequest.blade.php ENDPATH**/ ?>