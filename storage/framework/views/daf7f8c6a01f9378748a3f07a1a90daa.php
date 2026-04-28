<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>Smart Wallet Receiving List</h1>
            <p>Record and track all receiving wallet balances</p>
        </div>
        
    </div>

   

    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Receiving Request History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">                

                <table id="receivedWalletBalanceHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>Member</th>
                            <th>Date &amp; Time</th>
                            <th>Received Amount</th>
                            <th>Status</th>
                            <th class="text-center" style="width:80px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('chatbox', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php echo $__env->make('chat-script', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
<?php $__env->startPush('scripts'); ?>

<script>
$(document).ready(function () {
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ---------------------------------------------- START SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------------------------
    $('#receivedWalletBalanceHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "<?php echo e(route('member.smartwallet.userToUser.receiverList')); ?>",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'member' },
            { data: 'date' },
            { data: 'amount' },
            { data: 'status' },
            { data: 'actions', orderable:false, searchable:false }
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,5] }
        ],
        
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Receiving Smart Wallet History',
                exportOptions:{ 
                    columns:[0,1,2,3,4] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();

                                return `${name} - ${memberId}`;
                            }

                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Receiving Smart Wallet History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ 
                    columns:[0,1,2,3,4] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();

                                return `${name} - ${memberId}`;
                            }

                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Receiving Smart Wallet History',
                exportOptions:{ 
                    columns:[0,1,2,3,4] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();

                                return `${name} - ${memberId}`;
                            }

                            return $('<div>').html(data).text().trim();
                        }
                    }
                }
            }
        ],
        language:{
            search:'<i class="bi bi-search"></i>',
            searchPlaceholder:'Search requests…',
            lengthMenu:'Show _MENU_ entries',
            info:'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty:'No records found',
            paginate:{ previous:'‹', next:'›' }
        },
        dom:"<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
    // ---------------------------------------------- END SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
        

});

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/member/smartwallet/receiver.blade.php ENDPATH**/ ?>