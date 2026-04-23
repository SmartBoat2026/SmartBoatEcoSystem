@extends('member.layouts.app')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header">
        <div class="page-title">
            <h1>Smart Wallet Sending List</h1>
            <p>Record and track all sending wallet balances</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> NEW SENDING WALLET BALANCE
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
                        <i class="bi bi-receipt me-2"></i>SENDING WALLET BALANCE  
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{ route('member.smartwallet.store') }}" id="sendWalletBalanceRequestForm">
                        @csrf

                        <div class="row g-3 mb-3">

                            <div class="col-12 col-md-4" id="memberIdWrapper">
                                <label class="form-label fw-label">Member ID</label>
                                <div style="position:relative;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="memberSearchInputForWalletRequestSend"
                                               class="form-control form-control-sm"
                                               placeholder="Search Name or Member ID"
                                               autocomplete="off">
                                        <span id="memberLookupSpinner" class="input-group-text"
                                              style="display:none;background:#fff;border-left:0;padding:0 6px;">
                                            <span class="spinner-border spinner-border-sm text-primary"
                                                  style="width:.7rem;height:.7rem;"></span>
                                        </span>
                                    </div>
                                    <input type="hidden" name="member_id" id="memberIdInputForWalletRequestSend">
                                    <div id="memberDropdown" class="member-dropdown-list"></div>
                                </div>
                                <div id="memberName" class="member-name-display"></div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Self Wallet Balance</label>
                                <input type="text" name="wallet_balance" id="selfwalletBalanceInput"
                                       class="form-control form-control-sm"readonly
                                       style="background:#f8f9fa;color:#6c757d;font-size:11px;">
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Send Wallet Balance</label>
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       value="" required name="request_balance" id="sendBalanceInput" placeholder="Enter amount to send Request">
                            </div>

                        </div>

                      


                    </form>
                </div>

                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="sendWalletBalanceRequestForm" class="btn btn-primary btn-sm px-4" id="submitSendRequestBtn" disabled>
                        <i class="bi bi-check2-circle me-1"></i>Send Request
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== Send Smart Wallet Request History ===== --}}
    
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Sender Request History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">

                {{-- Bulk Action Bar --}}
                <div id="bulkActionBar" class="bulk-action-bar">
                    <span id="selectedCount" class="bulk-count">0 selected</span>
                    <form id="bulkDeleteForm" method="POST"
                          action="{{ route('member.productpurchase.bulkDelete') }}"
                          style="display:inline;">
                        @csrf
                        <div id="bulkDeleteIds"></div>
                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                            <i class="bi bi-trash me-1"></i>Delete Selected
                        </button>
                    </form>
                    <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x me-1"></i>Clear
                    </button>
                </div>

                <table id="sendWalletBalanceRequestHistoryTable"
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
                            <th>Date &amp; Time</th>
                            <th>Request Amount</th>
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

@push('scripts')

<script>
$(document).ready(function () {
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------------------------------------------ START OPEN NEW REQUEST MODAL----------------------------------------------------------------
    $('#addModal').on('shown.bs.modal', function () {
        $('#sendWalletBalanceRequestForm')[0].reset();

        $('#memberName').html('');
        $('#selfwalletBalanceInput').val('');
        $('#memberIdInputForWalletRequestSend').val('');
        $('#submitSendRequestBtn').prop('disabled', true);

        $('#memberDropdown').hide();
        loadMembers();
    });

    function loadMembers() {
        $.ajax({
            url: "{{ route('member.smartwallet.userToUser.members') }}",
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

                memberCache = data.results || []; //  cache store
                $('#selfwalletBalanceInput').val(data.selfwalletBalance);
                renderMemberDropdown(memberCache);
            },

            error: function () {
                $('#memberDropdown').html(`
                    <div style="padding:10px;color:#dc3545;font-size:12px;">
                        Something went wrong. Try again.
                    </div>
                `).show();
            }
        });
    }
    function renderMemberDropdown(list) {

        let html = '';

        if (list.length > 0) {

            $.each(list, function (i, m) {

                html += `
                    <div class="member-item"
                        style="padding:10px 12px;border-bottom:1px solid #eee;cursor:pointer;
                            display:flex;justify-content:space-between;align-items:center;"
                        data-id="${m.memberID}"
                        data-name="${m.name}">

                        <div>
                            <div style="font-weight:600;color:#1a3a6b;font-size:13px;">
                                ${m.name}
                            </div>                           
                        </div>

                        <span style="background:#e6f1fb;color:#0c447c;
                                    padding:2px 8px;border-radius:12px;
                                    font-size:11px;font-weight:600;">
                            ${m.memberID}
                        </span>

                    </div>
                `;
            });

        } else {
            html = `<div style="padding:10px;color:#dc3545;font-size:12px;">No members found</div>`;
        }

        $('#memberDropdown').html(html).show();
    }
    // ------------------------------------------------END OPEN NEW REQUEST MODAL----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
   
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------------------------------------------START CHOSSE MEMBER FROM LIST ON NEW REQUEST MODAL----------------------------------------------------------------
    $(document).on('click', '.member-item', function () {

        let id      = $(this).data('id');
        let name    = $(this).data('name');
        let balance = $(this).data('balance') ?? 0;

        $('#memberSearchInputForWalletRequestSend').val(name + ' - ' + id);
        $('#memberIdInputForWalletRequestSend').val(id);

        // $('#selfwalletBalanceInput').val(parseFloat(balance).toFixed(2));

        $('#memberDropdown').hide();

        $('#memberName').html(
            `<span style="color:#27500a;font-weight:600;">✔ ${name} | ${id}</span>`
        );
    });
    // ------------------------------------------------END CHOSSE MEMBER FROM LIST ON NEW REQUEST MODAL----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ------------------------------------------------START CHECKING VALID REQUESTBALANCE ON NEW REQUEST MODAL----------------------------------------------------------------
    $(document).on('input', '#sendBalanceInput', function () {

        let requestAmount = parseFloat($(this).val()) || 0;
        let walletBalance = parseFloat($('#selfwalletBalanceInput').val()) || 0;

        if (requestAmount > walletBalance) {
            toastr.error('Request amount cannot be greater than wallet balance');

            $(this).val(walletBalance); 
        }
    });
    // ------------------------------------------------END CHECKING VALID REQUESTBALANCE ON NEW REQUEST MODAL----------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
   

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //----------------------------------------------START CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM-------------------------------------------------------
    function checkForm() {
        let memberID = $('#memberIdInputForWalletRequestSend').val().trim();
        let requestAmount = $('#sendBalanceInput').val().trim();        
        let walletBalance = parseFloat($('#selfwalletBalanceInput').val()) || 0;

        if (memberID !== '' && requestAmount !== '' && parseFloat(requestAmount) > 0 && parseFloat(requestAmount) <= walletBalance) {
            $('#submitSendRequestBtn').prop('disabled', false);
        } else {
            $('#submitSendRequestBtn').prop('disabled', true);
        }
    }
    // Page load par check
    checkForm();
    $('#memberDropdown').on('click', function () {
        checkForm();
    });
    $('#sendBalanceInput').on('keyup change', function () {
        checkForm();
    });
    //----------------------------------------------END CHECK FORM DATA FOR ENABLE/DISABLE SUBMIT BUTTOM-------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //---------------------------------------------------------START NEW REQUEST FORM SUBMIT------------------------------------------------------------------------------
    $('#sendWalletBalanceRequestForm').on('submit', function (e) {
        e.preventDefault();

        let form = $(this);
        let btn  = $('#submitSendRequestBtn');

        btn.prop('disabled', true).html('Processing...');

        $.ajax({
            url: form.attr('action'),
            type: "POST",
            data: form.serialize(),
            dataType: "json",

            success: function (res) {

                btn.prop('disabled', false).html('Send Request');
                let modal = bootstrap.Modal.getInstance(document.getElementById('addModal'));
                modal.hide();

                if (res.status) {

                    //  SUCCESS POPUP (FULL SMS)
                    Swal.fire({
                        icon: 'success',
                        title: 'Request Sent Successfully',
                        html: `<div style="font-size:14px;line-height:1.6;text-align:left;">
                                    ${res.message}
                               </div>`,
                        confirmButtonColor: '#1a3a6b'
                    });

                    $('#sendWalletBalanceRequestHistoryTable').DataTable().ajax.reload(null, false);
                    // reset form
                    form[0].reset();
                    $('#memberName').html('');
                    $('#selfwalletBalanceInput').val('');
                    $('#memberIdInputForWalletRequestSend').val('');
                    $('#submitSendRequestBtn').prop('disabled', true);
                }
            },

            error: function (xhr) {

                btn.prop('disabled', false).html('Send Request');

                //  Validation error (422)
                if (xhr.status === 422) {

                    let errors = xhr.responseJSON.errors;

                    $.each(errors, function (key, val) {
                        toastr.error(val[0]);
                    });

                } else {
                    toastr.error('Something went wrong!');
                }
            }
        });
    });
    //---------------------------------------------------------END NEW REQUEST FORM SUBMIT------------------------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
     
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ---------------------------------------------- START SECTION DATABASE CALL , EXCEL , PDF DOWNLOAD , SEARCHING , PRINT ----------------------------------
    $('#sendWalletBalanceRequestHistoryTable').DataTable({
        processing: true,
        serverSide: false,
        ajax: "{{ route('member.smartwallet.senderList') }}",
        columns: [
            { data: 'checkbox', orderable:false, searchable:false },
            { data: 'DT_RowIndex' },
            { data: 'member' },
            // { data: 'member_text', visible: false },
            { data: 'date' },
            { data: 'amount' },
            { data: 'status' },
            { data: 'actions', orderable:false, searchable:false }
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
                title:'Send Smart Wallet Request History',
                exportOptions:{ 
                    columns:[1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();
                                let phone = $(node).find('div').eq(2).text().trim();

                                return `${name} - ${memberId} - ${phone}`;
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
                title:'Send Smart Wallet Request History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ 
                    columns:[1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();
                                let phone = $(node).find('div').eq(2).text().trim();

                                return `${name} - ${memberId} - ${phone}`;
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
                title:'Send Smart Wallet Request History',
                exportOptions:{ 
                    columns:[1,2,3,4,5] ,
                    format: {
                        body: function (data, row, column, node) {

                            // Column 2 (index 1 or 2 depending on your table setup)
                            if (column === 1) {

                                // Convert HTML to text
                                let name = $(node).find('div').eq(0).text().trim();
                                let memberId = $(node).find('div').eq(1).text().trim();
                                let phone = $(node).find('div').eq(2).text().trim();

                                return `${name} - ${memberId} - ${phone}`;
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
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ----------------------------------------------------------------------------- START SINGLE ROW DELETE -------------------------------------------------
    $(document).on('click', '.delete-btn', function () {

        let id = $(this).data('id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.deleteOne', ['id' => '__ID__']) }}".replace('__ID__', id),
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },

                    success: function (res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message || 'Record deleted successfully',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#sendWalletBalanceRequestHistoryTable').DataTable().ajax.reload(null, false);

                    },

                    error: function () {

                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Something went wrong!'
                        });

                    }
                });

            }

        });

    });
    // ----------------------------------------------------------------------------- END SINGLE ROW DELETE ---------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    

    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    // ----------------------------------------------------------START CHECK BOX SELECTION---------------------------------------------------------------------------
    function updateBulkBar() {

        const checked = $('.row-checkbox:checked');

        if (checked.length > 0) {

            $('#bulkActionBar').addClass('show');
            $('#selectedCount').text(checked.length + ' selected');

            $('#bulkDeleteBtn').prop('disabled', false);

        } else {

            $('#bulkActionBar').removeClass('show');
            $('#selectedCount').text('0 selected');

            $('#bulkDeleteBtn').prop('disabled', true);
        }
    }

    $('#selectAll').on('change', function () {
        const c = $(this).prop('checked');
        $('.row-checkbox').prop('checked', c);
        $('#sendWalletBalanceRequestHistoryTable tbody tr').toggleClass('row-selected', c);
        updateBulkBar();
    });

    $(document).on('change', '.row-checkbox', function () {
        $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
        const total   = $('.row-checkbox').length;
        const checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checked > 0 && checked < total)
                       .prop('checked', checked === total);
        updateBulkBar();
    });

    $('#clearSelection').on('click', function () {
        $('.row-checkbox, #selectAll').prop('checked', false).prop('indeterminate', false);
        $('#sendWalletBalanceRequestHistoryTable tbody tr').removeClass('row-selected');
        updateBulkBar();
    });
    // ----------------------------------------------------------END CHECK BOX SELECTION---------------------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
    //----------------------------------------------------------START BULK DELETE----------------------------------------------------------------
    $('#bulkDeleteBtn').on('click', function () {

        let ids = [];

        $('.row-checkbox:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            Swal.fire({
                icon: 'warning',
                title: 'No items selected'
            });
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: ids.length + " items will be deleted!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {

            if (result.isConfirmed) {

                $.ajax({
                    url: "{{ route('member.smartwallet.bulkDelete') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        ids: ids
                    },

                    success: function (res) {

                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: res.message,
                            timer: 1500,
                            showConfirmButton: false
                        });

                        $('#sendWalletBalanceRequestHistoryTable').DataTable().ajax.reload(null, false);

                        // reset selection
                        $('.row-checkbox, #selectAll').prop('checked', false).prop('indeterminate', false);
                        $('#bulkActionBar').removeClass('show');

                    },

                    error: function () {

                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Something went wrong!'
                        });

                    }
                });

            }

        });

    });
    //----------------------------------------------------------END BULK DELETE-------------------------------------------------------------------
    // ════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════════
       


});

</script>
@endpush
