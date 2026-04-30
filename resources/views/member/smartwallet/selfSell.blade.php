@extends('member.layouts.app')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
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

    {{-- ===== SENDER REQUEST FORM MODAL ===== --}}
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
                    <form method="POST" action="{{ route('member.smartwallet.buySell.selfSellStore') }}" id="sellWalletBalanceForm" enctype="multipart/form-data">
                        @csrf

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
                            <div class="col-12 col-sm-6 col-md-4" id="qrUploadBox" style="display:none;">
                                <label class="form-label fw-label">QR Image Upload</label>
                                <input type="file" name="qr_image" id="qr_image" class="form-control form-control-sm">
                                <div class="mt-2">
                                    <img id="qrPreview" src="" style="max-width:100px; display:none; border:1px solid #ddd; padding:5px;">
                                </div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4" id="detailsBox" style="display:none;">
                                <label class="form-label fw-label">Payment Details</label>
                                <textarea name="payment_details" id="payment_details"
                                        class="form-control form-control-sm"
                                        placeholder="Enter payment details"></textarea>
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
    

    {{-- ===== Sell Wallet History ===== --}}
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
                            <th>Sell ID</th>
                            <th>Show Wallet Balance</th>
                            <th>Payment Method</th>
                            <th>Start Date &amp; Time</th>
                            <th>Total Sell Amount</th>
                            <th>Mobile Number</th>
                            <th>Payment Details</th>
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
    

@endsection
@push('scripts')

<script>
$(document).ready(function () {   
    $('#addModal').on('show.bs.modal', function () {
        $('#sellWalletBalanceForm')[0].reset();
        $('#requestWalletBalanceInput').val('');

        $('#mobile_number').val('');
        
        $('#payment_details').val('');
        $('#qrUploadBox').hide();
        $('#detailsBox').hide();
        
    });
    $('#addModal').on('hidden.bs.modal', function () {
        $('#sellWalletBalanceForm')[0].reset();
        $('#edit_id').remove();
        $('#submitSellBtn')
            .html('<i class="bi bi-check2-circle me-1"></i>Save Sell Details')
            .prop('disabled', true);
        $('#qrPreview').hide().attr('src', '');
        $('#qrUploadBox').hide();
        $('#detailsBox').hide();
    });
    function togglePaymentFields() {
        let method = $('#paymentMethod').val();

        if (method == 1) {
            $('#qrUploadBox').show();
            $('#detailsBox').hide();
        } else {
            $('#qrUploadBox').hide();
            $('#detailsBox').show();
        }
    }
    $('#qr_image').on('change', function (e) {
        let reader = new FileReader();

        reader.onload = function (e) {
            $('#qrPreview').attr('src', e.target.result).show();
        }

        reader.readAsDataURL(this.files[0]);
    });

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------------------------START CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM------------------------------------
    function checkForm() {
        let walletBalance = $('#requestWalletBalanceInput').val().trim();
        let paymentMethod = $('#paymentMethod').val();

        let qr = $('#qr_image').val();
        let details = $('#payment_details').val().trim();

        let submitBtn = $('#submitSellBtn');

        let isValid = false;

        if (
            walletBalance !== '' &&
            !isNaN(walletBalance) &&
            parseFloat(walletBalance) > 0 &&
            paymentMethod !== ''
        ) {

            if (paymentMethod == 1) {
                isValid = (qr !== '');
            } else {
                isValid = (details !== '');
            }
        }

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
        togglePaymentFields();
    });

    $('#mobile_number').on('keyup', function () {
        checkForm();
    });
    $('#qr_image').on('change', function () {
        checkForm();
    });

    $('#payment_details').on('keyup', function () {
        checkForm();
    });
    //--------------------------END CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM-------------------------------------------------------
    //════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------------START NEW SENDING WALLET bALANCE FORM SUBMIT------------------------------------------------------------------------------
    
    $('#submitSellBtn').on('click', function () {
        let form = $('#sellWalletBalanceForm')[0];
        let btn  = $('#submitSellBtn');
        let formData = new FormData(form);
        $.ajax({
            url: $('#sellWalletBalanceForm').attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

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
        serverSide: true,
        ajax: "{{ route('member.smartwallet.buySell.selfSellListData') }}",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'sell_id' },
            { data: 'show_wallet_balance' },
            { data: 'payment_method' },
            { data: 'created_at' },
            { data: 'total_sell_wallet_balance' },
            { data: 'mobile_number' },
            { data: 'payment_details' },
            { data: 'status' },
            { data: 'actions' }
        ],
        order: [[1, 'desc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,8] }
        ],
        
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Sell Wallet History',
                exportOptions:{ 
                    columns:[0,1,2,3,4,5,6,7,8] ,
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
                    columns:[0,1,2,3,4,5,6,7,8] ,
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
                    columns:[0,1,2,3,4,5,6,7,8] ,
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
    
  
    $(document).on('click', '.cancelled-self-sell', function () {

        let sellId = $(this).data('sell-history-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This sell will be closed!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Close it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.buySell.selfSellCancel', ['id' => ':id']) }}".replace(':id', sellId),
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (res) {

                        Swal.fire({
                            title: 'Cancelled!',
                            text: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        $('#sellWalletBalanceRequestHistoryTable').DataTable().ajax.reload(null, false);
                    },

                    error: function () {
                        Swal.fire('Error', 'Something went wrong', 'error');
                    }
                });
            }
        });
    });
    $(document).on('click', '.renew-btn', function () {
        let sellId = $(this).data('sell-history-id');
        let wallet_balance = $(this).data('wallet-balance');
        let payment_method = $(this).data('payment-method');
        let mobile_number = $(this).data('mobile-number');
        let qr_image = $(this).data('qr-image');
        let payment_details = $(this).data('payment-details');

        Swal.fire({
            title: 'Renew this request?',
            text: "Your sell details will be reactivated!",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, Renew'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.buySell.selfSellStore') }}",
                    type: "POST",
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        wallet_balance: wallet_balance,
                        payment_method: payment_method,
                        mobile_number: mobile_number,
                        payment_details: payment_details,
                        qr_image: qr_image,
                        type:'r'
                    },
                    success: function (res) {

                        Swal.fire({
                            title: 'Renewed!',
                            text: res.message,
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        });

                        location.reload();
                    },

                    error: function (xhr) {

                        let message = 'Something went wrong';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            message = xhr.responseJSON.message;
                        }

                        Swal.fire({
                            title: 'Oops!',
                            text: message,
                            icon: 'error',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                });

            }
        });
    });
    $(document).on('click', '.edit-btn', function () {
        let id = $(this).data('sell-history-id');
        $.ajax({
            url: "{{ route('member.smartwallet.buySell.selfSellShowData', ['id' => ':id']) }}".replace(':id', id),
            type: "GET",
            success: function (res) {

                // show modal
                $('#addModal').modal('show');
                // fill values (FIXED IDs)
                $('#addModal').one('shown.bs.modal', function () {

                    $('#requestWalletBalanceInput').val(res.show_wallet_balance ?? '');

                    $('#paymentMethod').val(String(res.payment_method)).trigger('change');

                    $('#mobile_number').val(res.mobile_number ?? '');

                    if (res.payment_method == 1) {

                        // QR SHOW
                        if (res.qr_image) {
                            
                                $('#qrPreview')
                                    .attr('src',res.qr_image_url)
                                    .show();
                            } else {
                                $('#qrPreview').hide();
                            }

                            $('#payment_details').val('');

                    } else {

                        // DETAILS SHOW
                        $('#payment_details').val(res.payment_details ?? '');

                        $('#qrPreview').hide().attr('src', '');
                    }
                    checkForm();

                });
                // add hidden edit id
                $('#edit_id').remove();
                $('#sellWalletBalanceForm').append(
                    '<input type="hidden" id="edit_id" name="edit_id" value="'+id+'">'
                );

                // change button text
                $('#submitSellBtn')
                    .html('<i class="bi bi-check2-circle me-1"></i>Update Sell Details')
                    .prop('disabled', false);

                
            }
        });

    });

    

});

</script>
@endpush
