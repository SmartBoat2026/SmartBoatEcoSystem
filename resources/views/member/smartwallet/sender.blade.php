@extends('member.layouts.app')

@section('content')

    {{-- ===== PAGE HEADER ===== --}}
    <div class="page-header">
        <div class="page-title">
            <h1>Smart Wallet Sender List</h1>
            <p>Record and track all sending requests</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> New Request
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
                        <i class="bi bi-receipt me-2"></i>Send Request  
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
                                <label class="form-label fw-label">Member Wallet Balance</label>
                                <input type="text" name="wallet_balance" id="memberwalletBalanceInput"
                                       class="form-control form-control-sm"readonly
                                       style="background:#f8f9fa;color:#6c757d;font-size:11px;">
                            </div>

                            <div class="col-12 col-sm-6 col-md-4">
                                <label class="form-label fw-label">Request Wallet Balance</label>
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                       value="" required name="request_balance" id="requestBalanceInput" placeholder="Enter amount to send Request">
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

    {{-- ===== PURCHASE HISTORY ===== --}}
    @if($senders->count())
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
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete selected purchases? This cannot be undone.')">
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
                        @foreach($senders as $i => $sender)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="{{ $sender->id }}"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </td>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <div style="font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;">
                                    {{ $sender->receiver->name ?? 'Unknown Member' }}
                                </div>
                                @if($sender->receiver_member_id)
                                <div style="font-size:11px;color:#0c447c;margin-top:2px;">
                                    <span style="background:#e6f1fb;padding:1px 7px;border-radius:12px;">
                                        {{ $sender->receiver_member_id }}
                                    </span>
                                </div>
                                @endif
                                @if($sender->receiver->phone ?? $sender->receiver->phone ?? null)
                                <div style="font-size:11px;color:#6c757d;margin-top:2px;">
                                    <i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>
                                    {{ $sender->receiver->mobile ?? $sender->receiver->phone }}
                                </div>
                                @endif
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:500;color:#333;font-size:12px;">
                                    {{ \Carbon\Carbon::parse($sender->created_at)->format('d M Y') }}
                                </div>
                                <div style="font-size:11px;color:#adb5bd;">
                                    {{ \Carbon\Carbon::parse($sender->created_at)->format('h:i A') }}
                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                      style="background:#eeedfe;color:#3c3489;font-size:11px;padding:3px 8px;border-radius:20px;">
                                    {{ number_format($sender->request_balance, 2) }}
                                </span>
                            </td>
                            
                            <td>
                                @php
                                    $statusMap = [
                                        1 => ['text' => 'Pending',   'class' => 'bg-warning'],
                                        2 => ['text' => 'Accepted',  'class' => 'bg-success'],
                                        3 => ['text' => 'Rejected',  'class' => 'bg-danger'],
                                        4 => ['text' => 'Cancelled', 'class' => 'bg-secondary'],
                                    ];

                                    $status = $statusMap[$sender->status] ?? ['text' => 'Unknown', 'class' => 'bg-dark'];
                                @endphp
                                <span class="badge {{ $status['class'] }}" style="font-size:11px;">
                                    {{ $status['text'] }}
                                </span>
                            </td> 
                            <td class="text-center">
                                @if($sender->status == 1)
                                    <button class="btn btn-sm btn-primary edit-btn"
                                        data-id="{{ $sender->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>

                                    <form action="{{ route('member.smartwallet.delete', $sender->id) }}" 
                                        method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure you want to delete this request?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted" style="font-size:11px;">No Action</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @else
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bag-x" style="font-size:40px;color:#dee2e6;display:block;margin-bottom:12px;"></i>
            <p style="color:#6c757d;font-size:13px;margin:0;">No purchase records found. Start by creating a new purchase.</p>
        </div>
    </div>
    @endif

    {{-- ===== INVOICE MODAL ===== --}}
    <div class="modal fade" id="invoiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header" style="background:#1a3a6b;color:#fff;padding:12px 24px;">
                    <h6 class="modal-title mb-0"
                        style="font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">
                        <i class="bi bi-receipt me-2"></i>Invoice / Bill
                    </h6>
                    <div class="d-flex gap-2 align-items-center">
                        <button type="button" class="btn btn-sm btn-light" id="printInvoiceBtn"
                                style="font-size:12px;padding:3px 14px;">
                            <i class="bi bi-printer me-1"></i>Print
                        </button>
                        <button type="button" class="btn-close btn-close-white"
                                data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                </div>

                <div class="modal-body p-0" style="background:#dde3ea;">
                    <div id="invoicePrintArea" class="invoice-print-wrap">

                        <div class="inv-gradient-bar"></div>

                        <div class="inv-body">
                            {{-- Header --}}
                            <div class="inv-header">
                                <div class="inv-brand">
                                    <div class="inv-logo">SB</div>
                                    <div>
                                        <div class="inv-brand-name">SBES</div>
                                        <div class="inv-brand-sub">SmartBoatEcoSystem</div>
                                        <div class="inv-brand-url">
                                            <i class="bi bi-globe2" style="font-size:10px;margin-right:3px;"></i>smartboatecosystem.com
                                        </div>
                                    </div>
                                </div>
                                <div class="inv-title-block">
                                    <div class="inv-title-text">INVOICE</div>
                                    <div id="inv-invoice-no-hero" class="inv-no-hero"></div>
                                </div>
                            </div>

                            <div class="inv-divider"></div>

                            {{-- Bill To / Details --}}
                            <div class="inv-meta-row">
                                <div class="inv-bill-to">
                                    <div class="inv-section-label" style="color:#1a3a6b;">Bill To</div>
                                    <div id="inv-member-name" class="inv-member-name"></div>
                                    <div id="inv-member-id"></div>
                                    <div id="inv-member-mobile" class="inv-meta-line"></div>
                                    <div id="inv-member-email" class="inv-meta-line"></div>
                                    <div id="inv-member-address" class="inv-meta-line"></div>
                                </div>
                                <div class="inv-details-box">
                                    <div class="inv-section-label" style="color:#2c5f2e;">Invoice Details</div>
                                    <table class="inv-detail-table">
                                        <tr>
                                            <td class="inv-dt-label">Invoice No</td>
                                            <td id="inv-invoice-no" class="inv-dt-val" style="color:#1a3a6b;"></td>
                                        </tr>
                                        <tr>
                                            <td class="inv-dt-label">Purchase Date</td>
                                            <td id="inv-purchase-date" class="inv-dt-val"></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {{-- Items Table --}}
                        <div class="inv-items-wrap">
                            <table class="inv-items-table">
                                <thead>
                                    <tr style="background:#1a3a6b;color:#fff;">
                                        <th>#</th>
                                        <th>Product</th>
                                        <th>HSN</th>
                                        <th class="text-end">Base Price</th>
                                        <th class="text-end">DP</th>
                                        <th class="text-end">Count</th>
                                        <th class="text-end">Smart Pts</th>
                                        <th class="text-end">Smart Qty</th>
                                        <th class="text-end">Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="inv-items-body"></tbody>
                            </table>
                        </div>

                        {{-- Grand Total --}}
                        <div class="inv-grand-wrap">
                            <div class="inv-grand-box">
                                <span class="inv-grand-label">Grand Total</span>
                                <span id="inv-grand-total" class="inv-grand-val">₹0.00</span>
                            </div>
                        </div>

                        {{-- Smart + Terms --}}
                        <div class="inv-footer-row">
                            <div class="inv-stat-box" style="border-top-color:#3c3489;">
                                <div class="inv-stat-label" style="color:#3c3489;">Total Smart Points</div>
                                <div id="inv-smart-points" class="inv-stat-val" style="color:#3c3489;"></div>
                            </div>
                            <div class="inv-stat-box" style="border-top-color:#0c447c;">
                                <div class="inv-stat-label" style="color:#0c447c;">Total Smart Qty</div>
                                <div id="inv-smart-qty" class="inv-stat-val" style="color:#0c447c;"></div>
                            </div>
                            <div class="inv-stat-box inv-terms" style="border-top-color:#2c5f2e;">
                                <div class="inv-stat-label" style="color:#2c5f2e;">Terms &amp; Conditions</div>
                                <div class="inv-terms-text">
                                    This invoice is for the amount listed, which is the total cost for the goods
                                    or services provided. Please pay the full amount within the agreed payment period.
                                </div>
                            </div>
                        </div>

                        <div class="inv-bottom-bar">
                            <span style="font-size:10px;color:#6c757d;">Thank you for your business!</span>
                            <span style="font-size:10px;color:#6c757d;">smartboatecosystem.com</span>
                        </div>
                        <div class="inv-gradient-bar"></div>

                    </div>
                </div>

            </div>
        </div>
    </div>



@endsection

@push('scripts')

<script>
$(document).ready(function () {

    $('#addModal').on('shown.bs.modal', function () {
        $('#sendWalletBalanceRequestForm')[0].reset();

        $('#memberName').html('');
        $('#memberwalletBalanceInput').val('');
        $('#memberIdInputForWalletRequestSend').val('');
        $('#submitSendRequestBtn').prop('disabled', true);

        $('#memberDropdown').hide();
        loadMembers();
    });

    function loadMembers() {
        $.ajax({
            url: "{{ route('member.smartwallet.member') }}",
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

                memberCache = data.results || []; // ✅ cache store

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
                        data-name="${m.name}"
                        data-balance="${m.smart_wallet_balance ?? 0}">

                        <div>
                            <div style="font-weight:600;color:#1a3a6b;font-size:13px;">
                                ${m.name}
                            </div>
                            <div style="font-size:11px;color:#6c757d;">
                                ${m.phone ?? ''}
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
    $(document).on('click', '.member-item', function () {

        let id      = $(this).data('id');
        let name    = $(this).data('name');
        let balance = $(this).data('balance') ?? 0;

        $('#memberSearchInputForWalletRequestSend').val(name + ' - ' + id);
        $('#memberIdInputForWalletRequestSend').val(id);

        $('#memberwalletBalanceInput').val(parseFloat(balance).toFixed(2));

        $('#memberDropdown').hide();

        $('#memberName').html(
            `<span style="color:#27500a;font-weight:600;">✔ ${name} | ${id}</span>`
        );
    });
    $(document).on('input', '#requestBalanceInput', function () {

        let requestAmount = parseFloat($(this).val()) || 0;
        let walletBalance = parseFloat($('#memberwalletBalanceInput').val()) || 0;

        if (requestAmount > walletBalance) {
            toastr.error('Request amount cannot be greater than wallet balance');

            $(this).val(walletBalance); 
        }
    });

    function checkForm() {
        let memberID = $('#memberIdInputForWalletRequestSend').val().trim();
        let requestAmount = $('#requestBalanceInput').val().trim();        
        let walletBalance = parseFloat($('#memberwalletBalanceInput').val()) || 0;

        if (memberID !== '' && requestAmount !== '' && parseFloat(requestAmount) > 0 && parseFloat(requestAmount) <= walletBalance) {
            $('#submitSendRequestBtn').prop('disabled', false);
        } else {
            $('#submitSendRequestBtn').prop('disabled', true);
        }
    }

    // Page load par check
    checkForm();

    //  
    $('#memberDropdown').on('click', function () {
        checkForm();
    });
    $('#requestBalanceInput').on('keyup change', function () {
        checkForm();
    });

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

                    // reset form
                    form[0].reset();
                    $('#memberName').html('');
                    $('#memberwalletBalanceInput').val('');
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


    @if(session('error') || $errors->any())
        new bootstrap.Modal(document.getElementById('addModal')).show();
    @endif

    

    // ════════════════════════════════════════
    // 1. DATATABLES
    // ════════════════════════════════════════
    @if($senders->count())
    var histTable = $('#sendWalletBalanceRequestHistoryTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,6] }
        ],
        rowCallback: function (row, data, index) {
            $('td:eq(1)', row).text(index + 1);
        },
        buttons: [
            {
                extend:'excelHtml5',
                text:'<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className:'buttons-excel',
                title:'Purchase History',
                exportOptions:{ columns:[1,2,3,4,5,6] }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Send Smart Wallet Request History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ columns:[1,2,3,4,5,6] }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Send Smart Wallet Request History',
                exportOptions:{ columns:[1,2,3,4,5,6] }
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
    @endif

    // ════════════════════════════════════════
    // 2. BULK DELETE
    // ════════════════════════════════════════
    function updateBulkBar() {
        const checked = $('.row-checkbox:checked');
        if (checked.length > 0) {
            $('#bulkActionBar').addClass('show');
            $('#selectedCount').text(checked.length + ' selected');
            let inputs = '';
            checked.each(function () {
                inputs += `<input type="hidden" name="ids[]" value="${$(this).val()}">`;
            });
            $('#bulkDeleteIds').html(inputs);
        } else {
            $('#bulkActionBar').removeClass('show');
            $('#bulkDeleteIds').html('');
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

    // ════════════════════════════════════════
    // 3. MEMBER LIVE SEARCH
    // ════════════════════════════════════════
    let memberTimer = null;

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#memberIdWrapper').length) $('#memberDropdown').hide();
    });


    $('#memberSearchInputForWalletRequestSend').on('input keydown', function () {

    clearTimeout(memberTimer);

    $('#memberwalletBalanceInput').val('');
    $('#requestBalanceInput').val('');

    const val = $(this).val().trim().toLowerCase();

    $('#memberName').html('<span style="color:#6c757d;font-size:11px;">Searching…</span>');
    $('#memberLookupSpinner').show();
    $('#submitSendRequestBtn').prop('disabled', true);

    memberTimer = setTimeout(function () {

        $('#memberLookupSpinner').hide();

        if (!val) {
            renderMemberDropdown(memberCache);
            return;
        }

        let filtered = memberCache.filter(m =>
            (m.name && m.name.toLowerCase().includes(val)) ||
            (m.memberID && m.memberID.toLowerCase().includes(val)) ||
            (m.phone && m.phone.includes(val))
        );

        renderMemberDropdown(filtered);

    }, 300);
});
$(document).on('click', '.member-item, .member-result-item', function () {

    const id      = $(this).data('id');
    const name    = $(this).data('name');
    const balance = $(this).data('balance') ?? 0;

    $('#memberSearchInputForWalletRequestSend').val(name + ' — ' + id);
    $('#memberIdInputForWalletRequestSend').val(id);

    $('#memberwalletBalanceInput').val(parseFloat(balance).toFixed(2));

    $('#memberName').html(
        `<span style="color:#27500a;">✔ ${name} | ${id}</span>`
    );

    $('#memberDropdown').hide();
});
});
</script>
@endpush
