<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>Sent Request For Buy List</h1>
            <p>Record and track all Sent Request For Buy</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> NEW SENT REQUEST FOR BUY
            </a>
        </div>
    </div>

    
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <!-- HEADER (unchanged) -->
                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title">Sent Request For Buy</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body py-2">

                    <!-- FORM -->
                    <form id="sentRequestBuy" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <div class="row g-2 align-items-end">
                            <div class="col-md-10">
                                <label class="form-label mb-1">Request Wallet Balance</label>
                                <input type="number" name="wallet_balance"
                                    id="requestWalletBalanceInput"
                                    class="form-control form-control-sm"
                                    placeholder="Enter amount" required>
                            </div>
                            <div class="col-md-2">
                                <button type="button"
                                        class="btn btn-primary btn-sm w-100"
                                        id="submitSentRequestBuyBtn" disabled>
                                    Show Sellers
                                </button>
                            </div>
                        </div>
                    

                        <!-- SELLER SECTION -->
                        <div id="sellerSection" style="display:none;">

                            <!-- FILTER -->
                            <div class="row g-2 mb-2">
                                <div class="col-md-12 d-flex align-items-center gap-2">
                                    <select id="paymentFilter" name="payment_method"
                                            class="form-select form-select-sm">
                                        <option value="">All Payment Methods</option>
                                        <option value="1">UPI Transfer via QR Code</option>
                                        <option value="2">UPI Number</option>
                                        <option value="3">Bank to Bank Transfer</option>
                                        <option value="4">Cash to Bank Transfer</option>
                                    </select>
                                </div>
                            </div>

                            <!-- TABLE -->
                            <div class="table-responsive">
                                <table id="sellerTable"
                                    class="table table-sm table-bordered table-hover align-middle mb-0">
                                    <thead class="table-success">
                                        <tr>
                                            <th style="width:50px;">
                                                <input type="checkbox" id="selectAll">
                                            </th>
                                            <th>Sell ID</th>
                                            <th>Seller Name</th>
                                            <th>Mobile Number</th>
                                            <th>Payment Method</th>
                                            <th>Balance</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>

                        </div>
                    </form>

                </div>

                <!-- FOOTER (unchanged) -->
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button class="btn btn-primary btn-sm" id="sendRequestBtn" style="display:none;">
                        Send Request
                    </button>
                </div>

            </div>
        </div>
    </div>
  
    
    <div class="modal fade" id="transferMoneyModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title">
                        <i class="bi bi-cash-coin me-2"></i> Transfer Payment
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <div class="row g-3">

                        <!-- LEFT SIDE -->
                        <div class="col-md-5" id="paymentInfoSection">

                            <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.06);">

                                <h6 style="font-weight:700;color:#1a3a6b;">
                                    <i class="bi bi-info-circle"></i> Payment Details
                                </h6>

                                <div id="paymentContent"></div>

                            </div>

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-7">

                            <form method="POST" action="#" id="transferMoneyForm" enctype="multipart/form-data">
                                <?php echo csrf_field(); ?>

                                <input type="hidden" name="rfb_id" id="transferRfbId">

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <label class="form-label">Member ID</label>
                                        <input type="text" class="form-control" id="transferMemberId" name="member_id" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Amount</label>
                                        <input type="number" step="0.01" class="form-control" name="amount" id="transferAmount" readonly>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Transaction ID</label>
                                        <input type="text" class="form-control" name="transaction_id" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Upload Screenshot</label>
                                        <input type="file" class="form-control" name="screenshot" required>
                                    </div>

                                    <div class="col-md-12">
                                        <label class="form-label">Note</label>
                                        <textarea class="form-control" name="note" rows="2"></textarea>
                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm px-4" id="submitTransferMoney">
                        <i class="bi bi-send-check me-2"></i> Submit Transfer
                    </button>
                </div>

            </div>
        </div>
    </div>
    
    
    <div class="modal fade" id="viewSellerModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title">Seller List</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="table-responsive">
                        <table class="table table-bordered table-sm align-middle">
                            <thead class="table-success">
                                <tr>
                                    <th>Sell ID</th>
                                    <th>Seller Details</th>
                                    <th>Status</th>
                                    <th>Contact Number</th>
                                    <th>Smart Boat Chat</th>

                                </tr>
                            </thead>

                            <tbody id="sellerListBody">
                                <!-- AJAX data load হবে -->
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Sent Request For Buy List </span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">                

                <table id="sentRfbHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>RFB ID</th>
                            <th>Requested Wallet Balance</th>
                            <th>RFB Date &amp; Time</th>
                            <th>Total No of Sellers</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
$(document).ready(function () {   
    $('#addModal').on('show.bs.modal', function () {
        $('#sentRequestBuy')[0].reset();
        $('#paymentFilter').hide();
        $('#sellerSection').hide();
        $('#sendRequestBtn').hide();
        $('#edit_rfb_id').remove();
        if ($.fn.DataTable.isDataTable('#sellerTable')) {
            $('#sellerTable').DataTable().columns.adjust();
        }
    });
    function checkForm() {
        let walletBalance = $('#requestWalletBalanceInput').val().trim();
        let submitBtn = $('#submitSentRequestBuyBtn');

        let isValid =
            walletBalance !== '' &&
            !isNaN(walletBalance) &&
            parseFloat(walletBalance) > 0;

        submitBtn.prop('disabled', !isValid);
    }
    // Page load par check
    checkForm();

    // input events
    $('#requestWalletBalanceInput').on('keyup', function () {
        checkForm();
    });

        
    $('#submitSentRequestBuyBtn').on('click', function () {

        let form = $('#sentRequestBuy');
        

        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.fetchSellerData')); ?>",
            type: "POST",
            data: form.serialize(),

            success: function (res) {

                // show table section
                $('#sellerSection').show();
                $('#sendRequestBtn').show();
                $('#selectAll').prop('checked', false);
                $('#sendRequestBtn').prop('disabled', true);
                $('#sendRequestBtn').text('Save Request').show();
                loadSellerTable(res.data);
            },

            error: function (xhr) {
                Swal.fire('Error', xhr.responseJSON?.message || 'Error', 'error');
            }
        });
    });
    
    $('#sentRfbHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "<?php echo e(route('member.smartwallet.buySell.rfbListData')); ?>",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'rfb_id' },
            { data: 'amount' },
            { data: 'created_at' },
            { data: 'no_of_sellers' },
            { data: 'status' },
            { data: 'actions' }
        ],
        order: [[3, 'desc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,6] }
        ],
        
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Request For Buy History',
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
                title:'Request For Buy History',
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
                title:'Request For Buy History',
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
       
    function loadSellerTable(data) {

        if ($.fn.DataTable.isDataTable('#sellerTable')) {
            $('#sellerTable').DataTable().clear().destroy();
        }

        $('#sellerTable').DataTable({
            data: data,
            columns: [
                { data: 'checkbox'},
                { data: 'sell_id' },
                { data: 'name' },
                { data: 'mobile_number' },
                { data: 'payment_method' },
                { data: 'show_wallet_balance' }
            ]
        });
        let checked = $('.row-checkbox:checked').length;
        $('#sendRequestBtn').prop('disabled', checked === 0);

        setTimeout(function () {

            let total = $('.row-checkbox').length;
            let checked = $('.row-checkbox:checked').length;

            // select all checkbox update
            $('#selectAll').prop('checked', total > 0 && total === checked);

            // button enable/disable
            $('#sendRequestBtn').prop('disabled', checked === 0);

        }, 100);
        $('#edit_rfb_id').val() ? $('#sendRequestBtn').text('Update Request') : $('#sendRequestBtn').text('Save Request');
    }
    $('#paymentFilter').on('change', function () {
        $('#sellerSearch').val('');
        $('#submitSentRequestBuyBtn').trigger('click');
    });
    // Select All
    $(document).on('change', '#selectAll', function () {
        $('.row-checkbox').prop('checked', $(this).prop('checked'));
        $('#sendRequestBtn').prop('disabled', $('.row-checkbox:checked').length === 0);
    });

    // Individual checkbox change
    $(document).on('change', '.row-checkbox', function () {
        let total = $('.row-checkbox').length;
        let checked = $('.row-checkbox:checked').length;

        $('#selectAll').prop('checked', total === checked);
        $('#sendRequestBtn').prop('disabled', checked === 0);
    });


    $('#sendRequestBtn').on('click', function () {
        let selected = [];
        let editId = $('#edit_rfb_id').val();
        let amount = $('#requestWalletBalanceInput').val();
        if (!amount || amount <= 0) {
            Swal.fire('Error', 'Please enter valid amount', 'error');
            return;
        }
        $('.row-checkbox:checked').each(function () {
            selected.push($(this).val());
        });
        if (selected.length === 0) {
            Swal.fire('Error', 'Please select at least one seller', 'error');
            return;
        }
        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.sendRequestForBuyStore')); ?>",
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>",
                sellers: selected,
                amount: amount,
                edit_rfb_id: editId
            },
            success: function (res) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    html: res.message
                });

                $('#addModal').modal('hide');
                $('#sellerTable tbody').html('');
                $('#requestWalletBalanceInput').val('');
                $('#sellerSection').hide();
                $('#sendRequestBtn').hide();
                $('#sentRfbHistoryTable').DataTable().ajax.reload();
            },
            error: function () {
                Swal.fire('Error', 'Something went wrong', 'error');
            }
        });
    });
      
    $(document).on('click', '.edit-btn', function () {
        let rfbId = $(this).data('rfb-id');
        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.fetchSellerData', ':id')); ?>".replace(':id', rfbId),
            type: "POST",
            data: {
                _token: "<?php echo e(csrf_token()); ?>"
            },
            success: function (res) {
                $('#addModal').modal('show');
                // set amount
                $('#requestWalletBalanceInput').val(res.amount);

                // show section
                $('#sellerSection').show();
                $('#sendRequestBtn').show();

                // load table with checked sellers
                $('#addModal').one('shown.bs.modal', function () {
                    loadSellerTable(res.data);
                });

                // store edit id
                $('#edit_rfb_id').remove();
                $('#sentRequestBuy').append(
                    '<input type="hidden" id="edit_rfb_id" name="edit_rfb_id" value="'+rfbId+'">'
                );
                $('#sendRequestBtn').text('Update Request').show();              

                
                
            }
        });
    });

    $(document).on('click', '.view-btn', function () {
        let rfbId = $(this).data('rfb-id');
        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.rfbSellerList')); ?>",
            type: "GET",
            data: { rfb_id: rfbId },

            success: function (res) {

                let html = '';

                res.data.forEach(function (row) {

                    html += `
                        <tr>
                            <td>
                                <span class="badge" style="background:#eeedfe;color:#3c3489;">${row.sell_id}</span>
                            </td>

                            <td>
                                <div style="font-weight:600">${row.name}</div>
                            </td>

                            <td style="font-weight:600;font-size:14px;">
                                ${row.status == 1 
                                    ? '<span class="badge text-primary">Request Received</span>'
                                    : row.status == 2
                                        ? '<span class="badge text-success">Request Accepted</span>'
                                        : row.status == 3
                                            ? '<span class="badge text-warning text-dark">Closed Request</span>'
                                            : row.status == 4
                                                ? '<span class="badge text-danger">Closed Sell</span>'
                                                : '<span class="badge text-secondary">Unknown</span>'
                                }
                            </td>

                            <td>${row.mobile_number}</td>
                            <td>
                                ${row.actions}
                            </td>
                        </tr>
                    `;
                });

                $('#sellerListBody').html(html);

                $('#viewSellerModal').modal('show');
            }
        });
    });
    
    $(document).on('click', '.transfer-btn', function () {

        let rfbId = $(this).data('rfb-id');
        let btn = $(this);

        btn.prop('disabled', true).html('Loading...');

        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.sellerAcceptDetails', ':id')); ?>".replace(':id', rfbId),
            type: "GET",

            success: function (res) {

                $('#transferMoneyModal').modal('show');

                $('#transferRfbId').val(rfbId);
                $('#transferMemberId').val(res.transferMemberId);
                $('#transferAmount').val(res.amount ?? 0);

                let m = res.payment_method;

                let methodText =
                    m == 1 ? "UPI Transfer via QR Code" :
                    m == 2 ? "UPI Number" :
                    m == 3 ? "Bank to Bank Transfer" :
                    m == 4 ? "Cash to Bank Transfer" :
                    "Unknown";

                let html = `<p><b>Payment Method:</b> ${methodText}</p>`;

                if (m == 1 && res.qr_image) {
                    html += `${res.qr_image}`;
                } else {
                    html += `<div>${res.payment_details ?? ''}</div>`;
                }

                html += `<div class="mt-2"><b>Amount:</b> ${res.amount ?? 0}</div>`;

                $('#paymentContent').html(html);
            },

            error: function () {
                Swal.fire('Error', 'Something went wrong', 'error');
            },

            complete: function () {
                btn.prop('disabled', false).html('<i class="bi bi-cash-coin"></i> Transfer');
            }
        });

    });

    $(document).on('click', '#submitTransferMoney', function () {
        let btn = $(this);
        let formData = new FormData($('#transferMoneyForm')[0]);
        btn.prop('disabled', true).html('Processing...');
        $.ajax({
            url: "<?php echo e(route('member.smartwallet.buySell.transferMoneyStore')); ?>",
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            success: function (res) {

                Swal.fire('Success', res.message, 'success');

                $('#transferMoneyModal').modal('hide');
                $('#transferMoneyForm')[0].reset();

                $('#sentRfbHistoryTable').DataTable().ajax.reload();

            },

            error: function (xhr) {

                Swal.fire(
                    'Error',
                    xhr.responseJSON?.message || 'Something went wrong',
                    'error'
                );

            },

            complete: function () {
                btn.prop('disabled', false).html('<i class="bi bi-send-check me-2"></i> Submit Transfer');
            }
        });
    });

});

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\Main\resources\views/member/smartwallet/sendRequestForBuy.blade.php ENDPATH**/ ?>