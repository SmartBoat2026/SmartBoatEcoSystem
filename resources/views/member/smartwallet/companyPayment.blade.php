@extends('member.layouts.app')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header">
        <div class="page-title">
            <h1>Company Payment Submission List</h1>
            <p>Record and track all company payment submissions</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> NEW COMPANY PAYMENT SUBMISSION
            </a>
        </div>
    </div>

    {{-- ===== SENDER REQUEST FORM MODAL ===== --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <!-- HEADER -->
                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" style="font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                        <i class="bi bi-receipt me-2"></i> COMPANY PAYMENT SUBMISSION
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>

                <!-- BODY -->
                <div class="modal-body">
                    <div class="row g-4">

                        <!-- LEFT SIDE -->
                        <div class="col-md-5">

                            <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.06);">

                                <h6 style="font-weight:700;color:#1a3a6b;">
                                    <i class="bi bi-qr-code"></i> Scan & Pay
                                </h6>

                                <p style="font-size:12px;color:#6c757d;">
                                    Scan QR and complete payment
                                </p>

                                <div style="text-align:center;margin:15px 0;">
                                    <img src="{{ asset('public/admin/assets/images/HindolMukherjeeQRCode.png') }}"
                                        style="width:180px;border-radius:10px;cursor:pointer;"
                                        onclick="openQrModal(this.src)">
                                    <p style="font-size:11px;color:#888;margin-top:6px;">Click to enlarge</p>
                                </div>

                                <hr>

                                <div style="font-size:12px;line-height:1.8;color:#444;">

                                    <p id="adminPhone"><b>📞 Contact:</b> 82502 57091</p>
                                    <p id="adminEmail"><b>📧 Email:</b> smartboatofficial@gmail.com</p>

                                    <p style="color:#dc3545;font-weight:600;margin-top:10px;">
                                        After payment upload screenshot & transaction ID
                                    </p>

                                </div>

                            </div>

                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-7">

                            <form method="POST" action="{{ route('member.smartwallet.companyPayment.store') }}" 
                                enctype="multipart/form-data" id="CompanyPaymentSubmissionForm">
                                @csrf

                                <div style="background:#fff;border-radius:12px;padding:16px;box-shadow:0 2px 10px rgba(0,0,0,.06);">

                                    <h6 style="font-weight:700;color:#1a3a6b;">
                                        Payment Details
                                    </h6>

                                    <div class="row g-3 mt-1">

                                        <div class="col-12">
                                            <label class="form-label">Member ID</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" id="selfMemberIdInput" name="member_id"  required readonly>
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Payment Amount</label><span class="text-danger">*</span>
                                            <input type="number" step="0.01" class="form-control" name="amount" placeholder="Enter amount">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Transaction ID</label><span class="text-danger">*</span>
                                            <input type="text" class="form-control" name="transaction_id" placeholder="Enter transaction ID">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Payment Screenshot</label><span class="text-danger">*</span>
                                            <input type="file" class="form-control" name="qr_file">
                                        </div>

                                        <div class="col-12">
                                            <label class="form-label">Comment (Optional)</label>
                                            <textarea class="form-control" name="comment" rows="2"></textarea>
                                        </div>

                                    </div>

                                    

                                </div>

                            </form>

                        </div>

                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="submitCompanyPaymentSubmission" class="btn btn-primary btn-sm px-4" disabled>
                        <i class="bi bi-check-circle-fill me-2"></i> Submit Payment Request
                    </button>
                </div>

            </div>
        </div>
    </div>
    
    {{-- QR CODE MODAL --}}
    <div id="qrModal"
     style="display:none;position:fixed;inset:0;
            background:rgba(0,0,0,.85);
            z-index:9999;
            align-items:center;
            justify-content:center;">

        <div style="position:relative;">

            <img id="qrModalImg"
                style="max-width:90vw;max-height:90vh;
                        background:#fff;
                        padding:10px;
                        border-radius:12px;">

            <button onclick="closeQrModal()"
                    style="position:absolute;top:-12px;right:-12px;
                        width:34px;height:34px;border-radius:50%;
                        background:#dc3545;color:#fff;border:none;">
                ×
            </button>

        </div>
    </div>
    {{--==== TRANSACTION PASSWORD ==== --}}
    <div class="modal fade" id="transactionPasswordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Transaction Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <input type="password" id="transactionPasswordInput" class="form-control" placeholder="Enter password">
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" id="confirmTransactionPasswordBtn" class="btn btn-primary">Submit</button>
            </div>

            </div>
        </div>
    </div>

    {{-- ===== Payment Submission History ===== --}}
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Sender Request History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">                

                <table id="sendCompanyPaymentSubmissionHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th>#</th>
                            <th>Date &amp; Time</th>
                            <th>Sent Amount</th>
                            <th>Transaction ID</th>
                            <th>Payment Screenshot</th>
                            <th>Comments</th>
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
    

@endsection
@include('chatbox')
@include('chat-script')
@push('scripts')

<script>
    function openQrModal(src) {
        document.getElementById('qrModalImg').src = src;
        document.getElementById('qrModal').style.display = 'flex';
    }

    function closeQrModal() {
        document.getElementById('qrModal').style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        let modal = document.getElementById('qrModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
$(document).ready(function () {
    
    //═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------ START OPEN NEW SENDING WALLET bALANCE MODAL----------------------------------------------------------------
    $('#addModal').on('hidden.bs.modal', function () {

        let form = $(this).find('form')[0];
        if (form) form.reset();

    });
    $('#addModal').on('shown.bs.modal', function () {
         $.ajax({
            url: "{{ route('member.smartwallet.companyPayment.loadModelOpenData') }}",
            type: "GET",
            dataType: "json",

            beforeSend: function () {
                $('#memberDropdown').html(`
                    <div style="padding:10px;font-size:12px;color:#6c757d;">
                        Loading members...
                    </div>
                `).show();
            },

            success: function (data) {
                $('#adminPhone').html('<b>📞 Contact:</b> ' + data.adminPh );
                $('#adminEmail').html('<b>✉️ Email:</b> ' + data.adminEmail );
                $('#selfMemberIdInput').val(data.sessionMemberId);                
            },

            error: function () {
                $('#memberDropdown').html(`
                    <div style="padding:10px;color:#dc3545;font-size:12px;">
                        Something went wrong. Try again.
                    </div>
                `).show();
            }
        });
    });
   
    // -----------------------------END OPEN NEW REQUEST MODAL----------------------------------------------------------------
    // ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
     

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //--------------------------START CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM--------------------------------------------
    function checkForm() {
        let memberId = $('input[name="member_id"]').val();
        let amount = $('input[name="amount"]').val();
        let txnId = $('input[name="transaction_id"]').val();
        let file = $('input[name="qr_file"]')[0].files.length;

        if ( memberId.trim() !== '' && amount !== '' && parseFloat(amount) > 0 && txnId.trim() !== '' && file > 0
        ) {
            $('#submitCompanyPaymentSubmission').prop('disabled', false);
        } else {
            $('#submitCompanyPaymentSubmission').prop('disabled', true);
        }
    }
    // Page load par check
    checkForm();
   
    $('input[name="amount"], input[name="transaction_id"], input[name="qr_file"]').on('keyup change', function () {
        checkForm();
    });
    //--------------------------END CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM------------------------------------------------
    // ══════════════════════════════════════════════════════════════════════════════════════════════════

    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------------START NEW SENDING WALLET bALANCE FORM SUBMIT------------------------------------------------------------------------------
    

    $('#submitCompanyPaymentSubmission').on('click', function () {
        let form = $('#CompanyPaymentSubmissionForm')[0];
        let btn = $(this);

        let formData = new FormData(form);

        $.ajax({
            url: $(form).attr('action'),
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,

            beforeSend: function () {
                btn.prop('disabled', true).html('Submitting...');
            },

            success: function (res) {

                btn.prop('disabled', false).html('Send Wallet Balance');

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: res.message
                });

                $('#addModal').modal('hide');
                $('#sendCompanyPaymentSubmissionHistoryTable').DataTable().ajax.reload(null, false);
                form.reset();

            },

            error: function (xhr) {

                btn.prop('disabled', false).html('Send Wallet Balance');

                let msg = 'Something went wrong!';

                if (xhr.responseJSON?.message) {
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
    //--------------------------------END NEW SENDING WALLET bALANCE FORM SUBMIT--------------------------------------------------------
    // ═══════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
     
    // ═════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // --------------------------- START SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------
    $('#sendCompanyPaymentSubmissionHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('member.smartwallet.companyPayment.list') }}",
        columns: [
            { data: 'DT_RowIndex' },
            { data: 'date' },
            { data: 'amount' },
            { data: 'transaction_id' },
            { data: 'qr_file' },
            { data: 'comment' },
            { data: 'status' },
            { data: 'actions', orderable:false, searchable:false }
        ],
        order: [[2, 'desc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,7] }
        ],
        
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Company Payment Submission Request History',
                exportOptions:{ 
                    columns:[0,1,2,3,5,6] ,
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
                title:'Company Payment Submission Request History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ 
                    columns:[0,1,2,3,5,6] ,
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
                title:'Company Payment Submission Request History',
                exportOptions:{ 
                    columns:[0,1,2,3,5,6] ,
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
    // ------------------------ END SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ---------------------------START MEMBER NAME SEARCH------------------------------------------------------------------------------
    $(document).on('keyup', '#memberSearchInputForWalletRequestSend', function () {

        let keyword = $(this).val().toLowerCase().trim();

        if (!keyword) {
            renderMemberDropdown(memberCache);
            return;
        }

        let filtered = memberCache.filter(m => {

            let name = (m.name || '').toLowerCase();
            let id = (m.memberID || '').toLowerCase();

            return name.includes(keyword) || id.includes(keyword);
        });

        renderMemberDropdown(filtered);
    });
    // ---------------------------------------------------------END MEMBER NAME SEARCH------------------------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    

    

});

</script>
@endpush
