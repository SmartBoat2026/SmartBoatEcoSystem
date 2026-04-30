<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>Smart Wallet Self Selling List</h1>
            <p>Record and track all self-selling wallet balances</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> NEW SELF-SELLING WALLET BALANCE
            </a>
        </div>
    </div>

    
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" id="addModalLabel"
                        style="font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                        <i class="bi bi-receipt me-2"></i>SELF-SELLING WALLET bALANCE  
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="<?php echo e(route('member.smartwallet.buySell.selfSellStore')); ?>" id="sellWalletBalanceForm">
                        <?php echo csrf_field(); ?>

                        <div class="row g-3 mb-3">

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Show Wallet Balance <span class="text-danger">*</span></label>
                                <input type="number" name="wallet_balance" id="requestWalletBalanceInput"
                                       class="form-control form-control-sm" Placeholder="Enter Show Wallet Balance" required>   
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-control form-control-sm" name="payment_method" id="paymentMethod" required>
                                    <option value="">Select Method</option>
                                    <option value="1">UPI Transfer via QR Code</option>
                                    <option value="2">UPI Number</option>
                                    <option value="3">Bank to Bank Transfer</option>
                                    <option value="4">Cash to Bank Transfer</option>
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Mobile Number (optional)</label>
                                <input type="number" id="mobile_number"
                                    class="form-control form-control-sm"
                                    name="mobile_number"
                                    placeholder="Enter mobile number ">
                            </div>
                        </div>
                    </form>
                </div>

                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>                    
                    <button type="button" class="btn btn-primary btn-sm px-4" id="submitSellBtn" disabled>
                        <i class="bi bi-check2-circle me-1"></i>Save Sell Details
                    </button>
                </div>

            </div>
        </div>
    </div>
    

    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Self-Sell Details List </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">                

                <table id="sellWalletBalanceRequestHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>Show Wallet Balance</th>
                            <th>Payment Method</th>
                            <th>Start Date &amp; Time</th>
                            <th>Total Sell Amount</th>
                            <th>Mobile Number</th>
                            <th>Status</th>
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
    $('#addModal').on('show.bs.modal', function () {
        $('#sellWalletBalanceForm')[0].reset();
    });

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------------------------START CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM------------------------------------
    function checkForm() {
        let walletBalance = $('#requestWalletBalanceInput').val().trim();
        let paymentMethod = $('#paymentMethod').val();

        let submitBtn = $('#submitSellBtn');

        let isValid =
            walletBalance !== '' &&
            !isNaN(walletBalance) &&
            parseFloat(walletBalance) > 0 &&
            paymentMethod !== '';

        submitBtn.prop('disabled', !isValid);
    }
    // Page load par check
    checkForm();

    // input events
    $('#requestWalletBalanceInput').on('keyup', function () {
        checkForm();
    });

    $('#paymentMethod').on('change', function () {
        checkForm();
    });

    $('#mobile_number').on('keyup', function () {
        checkForm();
    });
    //--------------------------END CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM-------------------------------------------------------
    //════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------------START NEW SENDING WALLET bALANCE FORM SUBMIT------------------------------------------------------------------------------
    
    $('#submitSellBtn').on('click', function () {
        let form = $('#sellWalletBalanceForm');
        let btn  = $('#submitSellBtn');
        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),

            success: function (res) {   

                btn.prop('disabled', false).html('Sell Wallet Balance');

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    html: res.message
                });
                
                $('#addModal').modal('hide');
                $('#sellWalletBalanceRequestHistoryTable').DataTable().ajax.reload(null, false);

                form[0].reset();
            },

            error: function (xhr) {

                btn.prop('disabled', false).html('Send Wallet Balance');

                let msg = 'Something went wrong!';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: msg
                });
            }
        });

    });
    //---------------------------------------------------------END NEW SENDING WALLET bALANCE FORM SUBMIT------------------------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
     
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ---------------------------------------------- START SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------------------------
    $('#sellWalletBalanceRequestHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "<?php echo e(route('member.smartwallet.buySell.selfSellListData')); ?>",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'show_wallet_balance' },
            { data: 'payment_method' },
            { data: 'created_at' },
            { data: 'total_sell_wallet_balance' },
            { data: 'mobile_number' },
            { data: 'status' },
        ],
        order: [[3, 'desc']],
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
                title:'Sell Wallet History',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Sell Wallet History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
                            return $(node).text().trim();
                        }
                    }
                }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Sell Wallet History',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {
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

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/member/smartwallet/selfSell.blade.php ENDPATH**/ ?>