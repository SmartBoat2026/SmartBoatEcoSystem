<?php $__env->startSection('content'); ?>

    
    <div class="page-header">
        <div class="page-title">
            <h1>Product Purchase</h1>
            <p>Record and track all product purchases</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary-custom"
               data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus-lg"></i> New Purchase
            </a>
        </div>
    </div>

    
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" id="addModalLabel"
                        style="font-size:13px;font-weight:700;letter-spacing:.06em;text-transform:uppercase;">
                        <i class="bi bi-receipt me-2"></i>Sell To Member
                    </h5>
                    <button type="button" class="btn-close btn-close-white"
                            data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="<?php echo e(route('member.productpurchase.store')); ?>" id="purchaseForm">
                        <?php echo csrf_field(); ?>

                        
                        <div class="row g-3 mb-3">

                            <div class="col-12 col-md-3">
                                <label class="form-label fw-label">Member Detail</label>
                                <select id="memberDetailType" class="form-select form-select-sm">
                                    <option value="has_id">Do you have Member ID</option>
                                    <option value="no_id">No Member ID (Walk-in)</option>
                                </select>
                            </div>

                            <div class="col-12 col-md-3" id="memberIdWrapper">
                                <label class="form-label fw-label">Member ID</label>
                                <div style="position:relative;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="memberSearchInput"
                                               class="form-control form-control-sm"
                                               placeholder="Search Name or Member ID"
                                               autocomplete="off">
                                        <span id="memberLookupSpinner" class="input-group-text"
                                              style="display:none;background:#fff;border-left:0;padding:0 6px;">
                                            <span class="spinner-border spinner-border-sm text-primary"
                                                  style="width:.7rem;height:.7rem;"></span>
                                        </span>
                                    </div>
                                    <input type="hidden" name="member_id" id="memberIdInput">
                                    <div id="memberDropdown" class="member-dropdown-list"></div>
                                </div>
                                <div id="memberName" class="member-name-display"></div>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-label">Date &amp; Time</label>
                                <input type="datetime-local" name="purchase_date" id="purchaseDateInput"
                                       class="form-control form-control-sm"
                                       value="<?php echo e(date('Y-m-d\TH:i')); ?>" required>
                            </div>

                            <div class="col-12 col-sm-6 col-md-3">
                                <label class="form-label fw-label">Invoice Number</label>
                                <input type="text" class="form-control form-control-sm"
                                       value="Auto-generated on save" readonly
                                       style="background:#f8f9fa;color:#6c757d;font-size:11px;">
                            </div>

                        </div>

                        
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0 purchase-table">
                                <thead style="background:#2c5f2e;color:#fff;">
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th style="min-width:200px;">Product Name</th>
                                        <th style="min-width:90px;">HSN</th>
                                        <th style="min-width:90px;">Base Price</th>
                                        <th style="min-width:120px;">DP (Enter Value)</th>
                                        <th style="min-width:90px;">Count</th>
                                        <th style="min-width:80px;">Smart Pt</th>
                                        <th style="min-width:80px;">Smart Qty</th>
                                        <th style="min-width:90px;">Total</th>
                                        <th style="width:44px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseRows">
                                    <tr class="purchase-row">
                                        <td class="text-center text-muted row-num" style="font-size:12px;">1</td>
                                        <td>
                                            <div class="product-picker-wrapper">
                                                <input type="text"
                                                       class="form-control form-control-sm product-search-input"
                                                       placeholder="🔍 Search product…"
                                                       autocomplete="off" required>
                                                <input type="hidden" name="product_ids[]" class="product-id-input" required>
                                                <div class="product-dropdown"></div>
                                            </div>
                                        </td>
                                        <td class="hsn-cell">—</td>
                                        <td class="base-cell">—</td>
                                        <td>
                                            <input type="number" name="dp[]"
                                                   class="form-control form-control-sm dp-input"
                                                   min="0" step="0.01" placeholder="0.00"
                                                   style="width:100px;" required>
                                        </td>
                                        <td class="count-cell">—</td>
                                        <td class="sp-cell">—</td>
                                        <td class="sq-cell">—</td>
                                        <td class="amount-cell fw-500" style="color:#27500a;white-space:nowrap;">—</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row"
                                                    style="padding:2px 7px;font-size:12px;">&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10" style="padding:8px 12px;">
                                            <button type="button" id="addRow" class="btn btn-sm"
                                                    style="background:#2c5f2e;color:#fff;font-size:12px;">
                                                <i class="bi bi-plus"></i> Add Row
                                            </button>
                                        </td>
                                    </tr>
                                    <tr style="background:#f8f9fa;">
                                        <td colspan="8" class="text-end fw-500"
                                            style="padding:8px 12px;font-size:13px;">Subtotal</td>
                                        <td id="subtotalCell" class="fw-500"
                                            style="color:#27500a;padding:8px 12px;white-space:nowrap;">₹0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr style="background:#1a3a6b;">
                                        <td colspan="8" class="text-end fw-500"
                                            style="padding:8px 12px;font-size:13px;color:#fff;">Grand Total</td>
                                        <td id="grandTotalCell" class="fw-500"
                                            style="color:#fff;padding:8px 12px;white-space:nowrap;">₹0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        
                        <?php if(session()->has('member_logged_in') && session('member_id')): ?>
                        <div class="row g-2 mb-3">
                            <div class="col-12">
                                <div style="background:linear-gradient(90deg,#1a3a6b,#0c447c);border-radius:8px;padding:12px 18px;display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:8px;">
                                    <div style="display:flex;align-items:center;gap:10px;">
                                        <div style="background:rgba(255,255,255,0.15);border-radius:50%;width:36px;height:36px;display:flex;align-items:center;justify-content:center;">
                                            <i class="bi bi-wallet2 text-white" style="font-size:16px;"></i>
                                        </div>
                                        <div>
                                            <div style="font-size:10px;color:rgba(255,255,255,0.65);text-transform:uppercase;letter-spacing:.1em;font-weight:700;">Smart Wallet Balance</div>
                                            <div style="font-size:20px;font-weight:900;color:#fff;line-height:1.1;">₹<?php echo e(number_format($smartWalletBalance ?? 0, 2)); ?></div>
                                        </div>
                                    </div>
                                    <?php if(($smartWalletBalance ?? 0) > 0): ?>
                                        <span style="background:rgba(44,95,46,0.85);color:#c0e8a0;font-size:11px;padding:4px 14px;border-radius:20px;font-weight:700;">
                                            <i class="bi bi-check-circle me-1"></i>Available
                                        </span>
                                    <?php else: ?>
                                        <span style="background:rgba(220,53,69,0.8);color:#ffd0d0;font-size:11px;padding:4px 14px;border-radius:20px;font-weight:700;">
                                            <i class="bi bi-x-circle me-1"></i>No Balance
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>

                            
                            <?php if($errors->has('wallet')): ?>
                            <div class="col-12">
                                <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-0" style="font-size:12px;border-radius:6px;">
                                    <i class="bi bi-exclamation-triangle-fill"></i>
                                    <div><strong>Insufficient Balance!</strong> <?php echo e($errors->first('wallet')); ?></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        

                        
                        <div class="row g-3 p-3" style="background:#f8f9fa;border-top:0.5px solid #dee2e6;">
                            <div class="col-12 col-sm-4">
                                <div class="summary-mini-card">
                                    <div class="smc-label">Total Amount</div>
                                    <div id="summaryTotal" class="smc-value" style="color:#27500a;">₹0.00</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="summary-mini-card">
                                    <div class="smc-label">Total Smart Points</div>
                                    <div id="summarySP" class="smc-value" style="color:#3c3489;">0</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-4">
                                <div class="summary-mini-card">
                                    <div class="smc-label">Total Smart Qty</div>
                                    <div id="summarySQ" class="smc-value" style="color:#0c447c;">0.0000</div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="purchaseForm" class="btn btn-primary btn-sm px-4" id="submitBillBtn" disabled>
                        <i class="bi bi-check2-circle me-1"></i>Create Bill
                    </button>
                </div>

            </div>
        </div>
    </div>

    
    <?php if($purchases->count()): ?>
    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between flex-wrap gap-2"
             style="background:#1a3a6b;color:#fff;">
            <span><i class="bi bi-clock-history me-2"></i>Purchase History</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">

                
                <div id="bulkActionBar" class="bulk-action-bar">
                    <span id="selectedCount" class="bulk-count">0 selected</span>
                    <form id="bulkDeleteForm" method="POST"
                          action="<?php echo e(route('member.productpurchase.bulkDelete')); ?>"
                          style="display:inline;">
                        <?php echo csrf_field(); ?>
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

                <table id="purchaseHistoryTable"
                       class="table table-bordered mb-0"
                       style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th style="width:40px;text-align:center;">
                                <input type="checkbox" id="selectAll"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </th>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Member</th>
                            <th>Date &amp; Time</th>
                            <th>Smart Points</th>
                            <th>Smart Qty</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $pur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="<?php echo e($pur->id); ?>"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </td>
                            <td><?php echo e($i + 1); ?></td>
                            <td>
                                <span class="badge"
                                      style="background:#e6f1fb;color:#0c447c;font-size:11px;padding:3px 8px;border-radius:20px;">
                                    <?php echo e($pur->invoice_no); ?>

                                </span>
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;">
                                    <?php echo e($pur->member->name ?? 'Walk-in Customer'); ?>

                                </div>
                                <?php if($pur->member_id): ?>
                                <div style="font-size:11px;color:#0c447c;margin-top:2px;">
                                    <span style="background:#e6f1fb;padding:1px 7px;border-radius:12px;">
                                        <?php echo e($pur->member_id); ?>

                                    </span>
                                </div>
                                <?php endif; ?>
                                <?php if($pur->member->mobile ?? $pur->member->phone ?? null): ?>
                                <div style="font-size:11px;color:#6c757d;margin-top:2px;">
                                    <i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>
                                    <?php echo e($pur->member->mobile ?? $pur->member->phone); ?>

                                </div>
                                <?php endif; ?>
                            </td>
                            <td style="white-space:nowrap;">
                                <div style="font-weight:500;color:#333;font-size:12px;">
                                    <?php echo e(\Carbon\Carbon::parse($pur->purchase_date)->format('d M Y')); ?>

                                </div>
                                <div style="font-size:11px;color:#adb5bd;">
                                    <?php echo e(\Carbon\Carbon::parse($pur->purchase_date)->format('h:i A')); ?>

                                </div>
                            </td>
                            <td>
                                <span class="badge"
                                      style="background:#eeedfe;color:#3c3489;font-size:11px;padding:3px 8px;border-radius:20px;">
                                    <?php echo e(number_format($pur->total_smartpoint, 4)); ?>

                                </span>
                            </td>
                            <td style="font-size:12px;"><?php echo e(number_format($pur->total_smartquantity, 4)); ?></td>
                            <td style="color:#27500a;font-weight:600;white-space:nowrap;">
                                ₹<?php echo e(number_format($pur->total, 2)); ?>

                            </td>
                            <td>
                                <span class="badge <?php echo e($pur->status == 1 ? 'bg-success' : 'bg-danger'); ?>"
                                      style="font-size:11px;">
                                    <?php echo e($pur->status == 1 ? 'Active' : 'Inactive'); ?>

                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-sm view-invoice-btn"
                                    style="font-size:11px;padding:3px 10px;background:#1a3a6b;color:#fff;border-radius:4px;border:none;white-space:nowrap;"
                                    data-invoice="<?php echo e($pur->invoice_no); ?>"
                                    data-purchase-date="<?php echo e(\Carbon\Carbon::parse($pur->purchase_date)->format('d M Y, h:i A')); ?>"
                                    data-member-id="<?php echo e($pur->member_id ?? ''); ?>"
                                    data-member-name="<?php echo e($pur->member->name ?? 'Walk-in Customer'); ?>"
                                    data-member-mobile="<?php echo e($pur->member->mobile ?? $pur->member->phone ?? ''); ?>"
                                    data-member-email="<?php echo e($pur->member->email ?? ''); ?>"
                                    data-member-address="<?php echo e($pur->member->address ?? ''); ?>"
                                    data-total="<?php echo e(number_format($pur->total, 2)); ?>"
                                    data-smartpoint="<?php echo e(number_format($pur->total_smartpoint, 4)); ?>"
                                    data-smartqty="<?php echo e(number_format($pur->total_smartquantity, 4)); ?>"
                                    data-items='<?php echo e(json_encode($pur->items->map(function($item) {
                                        return [
                                            "name"  => $item->product_name,
                                            "hsn"   => $item->product_hsn ?: "—",
                                            "base"  => number_format($item->product_baseprice, 2),
                                            "dp"    => number_format($item->product_dp, 2),
                                            "count" => number_format($item->product_count, 4),
                                            "sp"    => number_format($item->product_smartpoints, 4),
                                            "sq"    => number_format($item->product_smartqty, 4),
                                            "total" => number_format($item->product_total, 2),
                                        ];
                                    }))); ?>'>
                                    <i class="bi bi-receipt me-1"></i>View
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="bi bi-bag-x" style="font-size:40px;color:#dee2e6;display:block;margin-bottom:12px;"></i>
            <p style="color:#6c757d;font-size:13px;margin:0;">No purchase records found. Start by creating a new purchase.</p>
        </div>
    </div>
    <?php endif; ?>

    
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

                        
                        <div class="inv-grand-wrap">
                            <div class="inv-grand-box">
                                <span class="inv-grand-label">Grand Total</span>
                                <span id="inv-grand-total" class="inv-grand-val">₹0.00</span>
                            </div>
                        </div>

                        
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



<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>

<script>
$(document).ready(function () {

    function checkForm() {
        let memberID = $('#memberIdInput').val().trim();

        if (memberID !== '') {
            $('#submitBillBtn').prop('disabled', false);
        } else {
            $('#submitBillBtn').prop('disabled', true);
        }
    }

    // Page load par check
    checkForm();

    // ✅ THIS IS MISSING
    $('#memberDropdown').on('click', function () {
        checkForm();
    });

});
</script>


<script>
$(document).ready(function () {

    <?php if(session('error') || $errors->any()): ?>
        new bootstrap.Modal(document.getElementById('addModal')).show();
    <?php endif; ?>

    const allProducts = <?php echo json_encode($productsForJs, 15, 512) ?>;

    // ════════════════════════════════════════
    // 1. DATATABLES
    // ════════════════════════════════════════
    <?php if($purchases->count()): ?>
    var histTable = $('#purchaseHistoryTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10,25,50,100,-1],[10,25,50,100,'All']],
        columnDefs: [
            { orderable:false, searchable:false, targets:[0,9] }
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
                exportOptions:{ columns:[1,2,3,4,5,6,7,8] }
            },
            {
                extend:'pdfHtml5',
                text:'<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className:'buttons-pdf',
                title:'Purchase History',
                orientation:'landscape',
                pageSize:'A4',
                exportOptions:{ columns:[1,2,3,4,5,6,7,8] }
            },
            {
                extend:'print',
                text:'<i class="bi bi-printer me-1"></i>Print',
                className:'buttons-print',
                title:'Purchase History',
                exportOptions:{ columns:[1,2,3,4,5,6,7,8] }
            }
        ],
        language:{
            search:'<i class="bi bi-search"></i>',
            searchPlaceholder:'Search purchases…',
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
        $('#purchaseHistoryTable tbody tr').toggleClass('row-selected', c);
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
        $('#purchaseHistoryTable tbody tr').removeClass('row-selected');
        updateBulkBar();
    });

    // ════════════════════════════════════════
    // 3. MEMBER LIVE SEARCH
    // ════════════════════════════════════════
    let memberTimer = null;

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#memberIdWrapper').length) $('#memberDropdown').hide();
    });

    $('#memberSearchInput').on('input', function () {
        clearTimeout(memberTimer);
        const val = $(this).val().trim();
        if (!val) {
            $('#memberIdInput').val('');
            $('#memberName').text('');
            $('#memberDropdown').hide();
            return;
        }
        $('#memberName').html('<span style="color:#6c757d;font-size:11px;">Searching…</span>');
        $('#memberLookupSpinner').show();
        $('#submitBillBtn').prop('disabled', true);
        memberTimer = setTimeout(function () {
            $.get("<?php echo e(route('member.productpurchase.member')); ?>", { member_id: val })
                .done(function (data) {
                    $('#memberLookupSpinner').hide();
                    $('#memberDropdown').empty().show();
                    if (data.results && data.results.length > 0) {
                        $.each(data.results, function (i, m) {
                            const item = $(`
                                <div class="member-result-item"
                                    style="padding:8px 12px;cursor:pointer;border-bottom:1px solid #f0f0f0;
                                           font-size:12px;display:flex;justify-content:space-between;align-items:center;"
                                    data-id="${m.memberID}" data-name="${m.name}">
                                    <div>
                                        <div style="font-weight:700;color:#1a3a6b;">${m.name}</div>
                                        <div style="font-size:11px;color:#6c757d;">${m.phone ?? ''}</div>
                                    </div>
                                    <span style="background:#e6f1fb;color:#0c447c;padding:2px 8px;
                                                 border-radius:12px;font-size:11px;font-weight:600;">
                                        ${m.memberID}
                                    </span>
                                </div>`);
                            item.on('mouseenter', function () { $(this).css('background','#f0f6ff'); })
                                .on('mouseleave', function () { $(this).css('background','#fff'); })
                                .on('click', function () {
                                    const selId   = $(this).data('id');
                                    const selName = $(this).data('name');
                                    $('#memberSearchInput').val(selName + '  —  ' + selId);
                                    $('#memberIdInput').val(selId);

                                    $('#memberName').html(`<span style="color:#27500a;">✔ ${selName} &nbsp;|&nbsp; ${selId}</span>`);
                                    $('#memberDropdown').hide();
                                });
                            $('#memberDropdown').append(item);
                        });
                    } else {
                        $('#memberDropdown').html('<div style="padding:10px 12px;font-size:12px;color:#dc3545;">✘ No member found</div>');
                        $('#memberName').html('<span style="color:#dc3545;">No member found</span>');
                    }
                })
                .fail(function () {
                    $('#memberLookupSpinner').hide();
                    $('#memberName').html('<span style="color:#dc3545;">✘ Lookup failed</span>');
                });
        }, 400);
    });

    $('#memberSearchInput').on('keydown', function (e) {
        const items  = $('#memberDropdown .member-result-item');
        const active = $('#memberDropdown .member-result-item.active');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active.length) items.first().addClass('active').css('background','#f0f6ff');
            else active.removeClass('active').css('background','#fff').next().addClass('active').css('background','#f0f6ff');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active.length) active.removeClass('active').css('background','#fff').prev().addClass('active').css('background','#f0f6ff');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (active.length) active.trigger('click');
        } else if (e.key === 'Escape') {
            $('#memberDropdown').hide();
        }
    });

    $('#memberDetailType').on('change', function () {
        if ($(this).val() === 'no_id') {
            $('#memberIdWrapper').hide();
            $('#memberSearchInput').val('');
            $('#memberIdInput').val('').prop('required', false);
            $('#memberName').html('<span style="color:#6c757d;">Walk-in customer</span>');
            $('#memberDropdown').hide();
        } else {
            $('#memberIdWrapper').show();
            $('#memberSearchInput').val('');
            $('#memberIdInput').val('');
            $('#memberName').text('');
        }
    });

    // ════════════════════════════════════════
    // 4. INVOICE MODAL
    // ════════════════════════════════════════
    $(document).on('click', '.view-invoice-btn', function () {
        const btn          = $(this);
        const invoiceNo    = btn.data('invoice');
        const purchaseDate = btn.data('purchase-date');
        const mName        = btn.data('member-name')    || 'Walk-in Customer';
        const mId          = btn.data('member-id')      || '';
        const mMobile      = btn.data('member-mobile')  || '';
        const mEmail       = btn.data('member-email')   || '';
        const mAddress     = btn.data('member-address') || '';
        const grandTotal   = btn.data('total');
        const smartPoint   = btn.data('smartpoint');
        const smartQty     = btn.data('smartqty');
        const items        = btn.data('items');

        $('#inv-invoice-no-hero').text(invoiceNo);
        $('#inv-invoice-no').text(invoiceNo);
        $('#inv-purchase-date').text(purchaseDate);
        $('#inv-member-name').text(mName);
        $('#inv-member-id').html(mId ? `<span style="background:#e6f1fb;color:#0c447c;padding:1px 8px;border-radius:12px;font-size:11px;">ID: ${mId}</span>` : '');
        $('#inv-member-mobile').html(mMobile  ? `<i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>${mMobile}`  : '');
        $('#inv-member-email').html(mEmail    ? `<i class="bi bi-envelope-fill me-1"  style="color:#1a3a6b;font-size:10px;"></i>${mEmail}`    : '');
        $('#inv-member-address').html(mAddress? `<i class="bi bi-geo-alt-fill me-1"   style="color:#1a3a6b;font-size:10px;"></i>${mAddress}`  : '');
        $('#inv-grand-total').text('₹' + grandTotal);
        $('#inv-smart-points').text(smartPoint);
        $('#inv-smart-qty').text(smartQty);

        let tbody = '';
        $.each(items, function (idx, item) {
            const rowBg = (idx % 2 === 1) ? '#f4f7fb' : '#ffffff';
            tbody += `<tr style="background:${rowBg};border-bottom:1px solid #e9ecef;">
                <td style="padding:9px 12px;color:#adb5bd;font-weight:700;font-size:11px;">${String(idx+1).padStart(2,'0')}</td>
                <td style="padding:9px 12px;font-weight:700;color:#111;">${item.name}</td>
                <td style="padding:9px 12px;color:#6c757d;">${item.hsn}</td>
                <td style="padding:9px 12px;text-align:right;color:#495057;">₹${item.base}</td>
                <td style="padding:9px 12px;text-align:right;font-weight:700;color:#1a3a6b;">₹${item.dp}</td>
                <td style="padding:9px 12px;text-align:right;color:#495057;">${item.count}</td>
                <td style="padding:9px 12px;text-align:right;color:#3c3489;font-weight:600;">${item.sp}</td>
                <td style="padding:9px 12px;text-align:right;color:#0c447c;font-weight:600;">${item.sq}</td>
                <td style="padding:9px 12px;text-align:right;font-weight:800;color:#27500a;">₹${item.total}</td>
            </tr>`;
        });
        $('#inv-items-body').html(tbody);

        new bootstrap.Modal(document.getElementById('invoiceModal')).show();
    });

    $('#printInvoiceBtn').on('click', function () { window.print(); });

    // ════════════════════════════════════════
    // 5. PRODUCT PICKER
    // ════════════════════════════════════════
    function getUsedIds(excludeWrapper) {
        let used = [];
        $('#purchaseRows .purchase-row').each(function () {
            const w   = $(this).find('.product-picker-wrapper');
            const val = w.find('.product-id-input').val();
            if (val && w[0] !== excludeWrapper[0]) used.push(parseInt(val));
        });
        return used;
    }

    function buildDropdown(wrapper, query) {
        const dd      = wrapper.find('.product-dropdown');
        const usedIds = getUsedIds(wrapper);
        const q       = query.toLowerCase().trim();
        const filtered = allProducts.filter(function (p) {
            if (usedIds.includes(p.id)) return false;
            if (!q) return true;
            return p.name.toLowerCase().includes(q)
                || p.cat.toLowerCase().includes(q)
                || p.sub.toLowerCase().includes(q)
                || p.hsn.toLowerCase().includes(q);
        });
        dd.empty();
        if (filtered.length === 0) {
            dd.html('<div class="prod-no-result">✘ No product found</div>').show();
            return;
        }
        filtered.forEach(function (p) {
            const catBadge = p.cat ? `<span class="badge-cat">${p.cat}</span>` : '';
            const subBadge = p.sub ? `<span class="badge-sub">${p.sub}</span>` : '';
            const item = $(`
                <div class="prod-item" data-id="${p.id}">
                    <div class="prod-name">${p.name}</div>
                    <div class="prod-meta">${catBadge}${subBadge}
                        <span style="color:#adb5bd;margin-left:4px;">HSN: ${p.hsn} &nbsp;|&nbsp; ₹${parseFloat(p.base).toFixed(2)}</span>
                    </div>
                </div>`);
            item.on('click', function () { selectProduct(wrapper, p); });
            dd.append(item);
        });
        dd.show();
    }

    function selectProduct(wrapper, p) {
        wrapper.find('.product-search-input').val(
            p.name + (p.cat ? ' [' + p.cat + (p.sub ? ' › ' + p.sub : '') + ']' : '')
        );
        wrapper.find('.product-id-input').val(p.id);
        wrapper.find('.product-dropdown').hide();
        calcRowFromProduct(wrapper.closest('tr'), p);
        recalcTotals();
    }

    function calcRowFromProduct(row, p) {
        const base  = parseFloat(p.base) || 0;
        const sp    = parseFloat(p.sp)   || 0;
        const hsn   = p.hsn || '—';
        const dp    = parseFloat(row.find('.dp-input').val()) || 0;
        const count = base > 0 ? dp / base : 0;
        const sq    = sp * 0.001;
        row.find('.hsn-cell').html(`<span class="badge" style="background:#e6f1fb;color:#0c447c;font-size:11px;padding:2px 8px;border-radius:20px;">${hsn}</span>`);
        row.find('.base-cell').text('₹' + base.toFixed(2));
        row.find('.sp-cell').html(`<span class="badge" style="background:#eeedfe;color:#3c3489;font-size:11px;padding:2px 8px;border-radius:20px;">${sp}</span>`);
        row.find('.sq-cell').text(sq.toFixed(4));
        row.find('.count-cell').text(count > 0 ? count.toFixed(4) : '—');
        row.find('.amount-cell').text(dp > 0 ? '₹' + dp.toFixed(2) : '—');
    }

    function calcRow(row) {
        const pid = row.find('.product-id-input').val();
        if (!pid) return;
        const p = allProducts.find(x => x.id == pid);
        if (p) calcRowFromProduct(row, p);
    }

    function recalcTotals() {
        let total = 0, totalSP = 0, totalSQ = 0;
        $('#purchaseRows .purchase-row').each(function () {
            const pid = $(this).find('.product-id-input').val();
            const dp  = parseFloat($(this).find('.dp-input').val()) || 0;
            if (!pid) return;
            const p  = allProducts.find(x => x.id == pid);
            if (!p) return;
            const sp = parseFloat(p.sp) || 0;
            total   += dp;
            totalSP += dp * sp;
            totalSQ += dp * sp * 0.001;
        });
        $('#subtotalCell, #grandTotalCell').text('₹' + total.toFixed(2));
        $('#summaryTotal').text('₹' + total.toFixed(2));
        $('#summarySP').text(totalSP.toFixed(4));
        $('#summarySQ').text(totalSQ.toFixed(4));
    }

    $(document).on('input', '.product-search-input', function () {
        const wrapper = $(this).closest('.product-picker-wrapper');
        wrapper.find('.product-id-input').val('');
        buildDropdown(wrapper, $(this).val());
    });
    $(document).on('focus', '.product-search-input', function () {
        buildDropdown($(this).closest('.product-picker-wrapper'), $(this).val());
    });
    $(document).on('keydown', '.product-search-input', function (e) {
        const wrapper = $(this).closest('.product-picker-wrapper');
        const dd      = wrapper.find('.product-dropdown');
        const items   = dd.find('.prod-item');
        const active  = dd.find('.prod-item.active');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active.length) items.first().addClass('active');
            else active.removeClass('active').next('.prod-item').addClass('active');
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active.length) active.removeClass('active').prev('.prod-item').addClass('active');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (active.length) {
                const p = allProducts.find(x => x.id == active.data('id'));
                if (p) selectProduct(wrapper, p);
            }
        } else if (e.key === 'Escape') { dd.hide(); }
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.product-picker-wrapper').length) $('.product-dropdown').hide();
    });
    $(document).on('input', '.dp-input', function () {
        calcRow($(this).closest('tr'));
        recalcTotals();
    });

    // ════════════════════════════════════════
    // 6. ADD / REMOVE ROWS
    // ════════════════════════════════════════
    function makeRowHtml(rowNum) {
        return `<tr class="purchase-row">
            <td class="text-center text-muted row-num" style="font-size:12px;">${rowNum}</td>
            <td>
                <div class="product-picker-wrapper">
                    <input type="text" class="form-control form-control-sm product-search-input"
                           placeholder="🔍 Search product…" autocomplete="off">
                    <input type="hidden" name="product_ids[]" class="product-id-input" required>
                    <div class="product-dropdown"></div>
                </div>
            </td>
            <td class="hsn-cell">—</td>
            <td class="base-cell">—</td>
            <td>
                <input type="number" name="dp[]" class="form-control form-control-sm dp-input"
                    min="0" step="0.01" placeholder="0.00" style="width:100px;" required>
            </td>
            <td class="count-cell">—</td>
            <td class="sp-cell">—</td>
            <td class="sq-cell">—</td>
            <td class="amount-cell fw-500" style="color:#27500a;white-space:nowrap;">—</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row"
                        style="padding:2px 7px;font-size:12px;">&times;</button>
            </td>
        </tr>`;
    }

    $('#addRow').on('click', function () {
        $('#purchaseRows').append(makeRowHtml($('#purchaseRows .purchase-row').length + 1));
        renumberRows();
    });
    $(document).on('click', '.remove-row', function () {
        if ($('#purchaseRows .purchase-row').length > 1) {
            $(this).closest('tr').remove();
            renumberRows();
            recalcTotals();
        }
    });
    function renumberRows() {
        $('#purchaseRows .purchase-row').each(function (i) {
            $(this).find('.row-num').text(i + 1);
        });
    }



});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\Main\resources\views/member/productpurchase.blade.php ENDPATH**/ ?>