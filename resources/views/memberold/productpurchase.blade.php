@extends('member.layouts.app')

@section('content')
<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Product Purchase</h1>
            <p>Record and track all product purchases</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Purchase Product
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    {{-- @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif --}}

    {{-- ===== PURCHASE FORM MODAL ===== --}}
    <div class="modal fade" id="addModal" tabindex="-1" aria-labelledby="addModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">

                <div class="modal-header" style="background:#1a3a6b;color:#fff;">
                    <h5 class="modal-title" id="addModalLabel" style="font-size:14px;font-weight:600;letter-spacing:.05em;text-transform:uppercase;">
                        Sell To Member
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form method="POST" action="{{ route('member.productpurchase.store') }}" id="purchaseForm">
                        @csrf

                        {{-- Member / Date / Invoice row --}}
                        <div class="row g-3 mb-3">

                            <div class="col-md-3">
                                <label class="form-label" style="font-size:12px;font-weight:600;color:#1a3a6b;">Member Detail</label>
                                <select id="memberDetailType" class="form-select form-select-sm">
                                    <option value="has_id">Do you have Member ID</option>
                                    <option value="no_id">No Member ID (Walk-in)</option>
                                </select>
                            </div>

                            <div class="col-md-3" id="memberIdWrapper">
                                <label class="form-label" style="font-size:12px;font-weight:600;color:#1a3a6b;">Member ID</label>
                                <div style="position:relative;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" id="memberSearchInput"
                                               class="form-control form-control-sm"
                                               placeholder="Search by Name or Member ID"
                                               autocomplete="off">
                                        <span id="memberLookupSpinner" class="input-group-text"
                                              style="display:none;background:#fff;border-left:0;padding:0 6px;">
                                            <span class="spinner-border spinner-border-sm text-primary"
                                                  style="width:.7rem;height:.7rem;"></span>
                                        </span>
                                    </div>
                                    <input type="hidden" name="member_id" id="memberIdInput">
                                    <div id="memberDropdown"
                                         style="display:none;position:absolute;top:100%;left:0;right:0;z-index:9999;
                                                background:#fff;border:1px solid #dee2e6;border-radius:0 0 6px 6px;
                                                box-shadow:0 4px 12px rgba(0,0,0,.1);max-height:220px;overflow-y:auto;">
                                    </div>
                                </div>
                                <div id="memberName" style="font-size:12px;color:#27500a;margin-top:4px;min-height:18px;"></div>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" style="font-size:12px;font-weight:600;color:#1a3a6b;">Date &amp; Time</label>
                                <input type="datetime-local" name="purchase_date" id="purchaseDateInput"
                                       class="form-control form-control-sm"
                                       value="{{ date('Y-m-d\TH:i') }}" required>
                            </div>

                            <div class="col-md-3">
                                <label class="form-label" style="font-size:12px;font-weight:600;color:#1a3a6b;">Invoice Number</label>
                                <input type="text" class="form-control form-control-sm"
                                       value="Auto-generated on save (SBES00001...)" readonly
                                       style="background:#f8f9fa;color:#6c757d;">
                            </div>

                        </div>

                        {{-- Products Table --}}
                        <div class="table-responsive">
                            <table class="table table-bordered mb-0" style="font-size:13px;">
                                <thead style="background:#2c5f2e;color:#fff;">
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th style="min-width:220px;">Product Name</th>
                                        <th>Product HSN</th>
                                        <th>Base Price</th>
                                        <th>DP (Enter Value)</th>
                                        <th>Count (DP / Base Price)</th>
                                        <th>Smart Point</th>
                                        <th>Smart Qty</th>
                                        <th>Total Amount</th>
                                        <th style="width:50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="purchaseRows">
                                    <tr class="purchase-row">
                                        <td class="text-center text-muted row-num">1</td>
                                        <td>
                                            <div class="product-picker-wrapper" style="position:relative;min-width:220px;">
                                                <input type="text"
                                                       class="form-control form-control-sm product-search-input"
                                                       placeholder="🔍 Search product…"
                                                       autocomplete="off"
                                                       style="font-size:12px;">
                                                <input type="hidden" name="product_ids[]" class="product-id-input" required>
                                                <div class="product-dropdown"
                                                     style="display:none;position:absolute;top:100%;left:0;right:0;z-index:9999;
                                                            background:#fff;border:1px solid #dee2e6;border-radius:0 0 6px 6px;
                                                            box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:260px;overflow-y:auto;">
                                                </div>
                                            </div>
                                        </td>
                                        <td class="hsn-cell">—</td>
                                        <td class="base-cell">—</td>
                                        <td>
                                            <input type="number" name="dp[]"
                                                   class="form-control form-control-sm dp-input"
                                                   min="0" step="0.01" placeholder="0.00"
                                                   style="width:110px;" required>
                                        </td>
                                        <td class="count-cell">—</td>
                                        <td class="sp-cell">—</td>
                                        <td class="sq-cell">—</td>
                                        <td class="amount-cell fw-500" style="color:#27500a;">—</td>
                                        <td class="text-center">
                                            <button type="button" class="btn btn-sm btn-danger remove-row"
                                                    style="padding:2px 8px;font-size:12px;">&times;</button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="10" style="padding:8px 12px;">
                                            <button type="button" id="addRow" class="btn btn-sm"
                                                    style="background:#2c5f2e;color:#fff;font-size:12px;">
                                                + Add Row
                                            </button>
                                        </td>
                                    </tr>
                                    <tr style="background:#f8f9fa;">
                                        <td colspan="8" class="text-end fw-500"
                                            style="padding:8px 12px;font-size:13px;">Subtotal</td>
                                        <td id="subtotalCell" class="fw-500"
                                            style="color:#27500a;padding:8px 12px;">₹0.00</td>
                                        <td></td>
                                    </tr>
                                    <tr style="background:#1a3a6b;">
                                        <td colspan="8" class="text-end fw-500"
                                            style="padding:8px 12px;font-size:13px;color:#fff;">Grand Total</td>
                                        <td id="grandTotalCell" class="fw-500"
                                            style="color:#fff;padding:8px 12px;">₹0.00</td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Summary Cards --}}
                        <div class="row g-3 p-3" style="background:#f8f9fa;border-top:0.5px solid #dee2e6;">
                            <div class="col-md-4">
                                <div class="p-3" style="background:#fff;border:0.5px solid #dee2e6;border-radius:8px;">
                                    <div style="font-size:11px;color:#6c757d;margin-bottom:4px;">Total Amount</div>
                                    <div id="summaryTotal" style="font-size:22px;font-weight:500;color:#27500a;">₹0.00</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3" style="background:#fff;border:0.5px solid #dee2e6;border-radius:8px;">
                                    <div style="font-size:11px;color:#6c757d;margin-bottom:4px;">Total Smart Points</div>
                                    <div id="summarySP" style="font-size:22px;font-weight:500;color:#3c3489;">0</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="p-3" style="background:#fff;border:0.5px solid #dee2e6;border-radius:8px;">
                                    <div style="font-size:11px;color:#6c757d;margin-bottom:4px;">Total Smart Qty</div>
                                    <div id="summarySQ" style="font-size:22px;font-weight:500;color:#0c447c;">0.0000</div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>

                <div class="modal-footer" style="border-top:0.5px solid #dee2e6;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="purchaseForm" class="btn btn-primary btn-sm px-4">Create Bill</button>
                </div>

            </div>
        </div>
    </div>

    {{-- ===== PURCHASE HISTORY ===== --}}
    @if($purchases->count())
    <div class="card mb-4">
        <div class="card-header" style="background:#1a3a6b;color:#fff;font-weight:500;font-size:13px;letter-spacing:.05em;text-transform:uppercase;">
            Purchase History
        </div>
        <div class="card-body p-0">
            <div class="table-responsive p-3">

                {{-- ── BULK DELETE BAR ── --}}
                <div id="bulkActionBar" style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                    <span id="selectedCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                    <form id="bulkDeleteForm" method="POST" action="{{ route('member.productpurchase.bulkDelete') }}" style="display:inline;">
                        @csrf
                        <div id="bulkDeleteIds"></div>
                        <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Delete selected purchases? This cannot be undone.')">
                            <i class="bi bi-trash me-1"></i>Delete Selected
                        </button>
                    </form>
                    <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x me-1"></i>Clear Selection
                    </button>
                </div>

                <table id="purchaseHistoryTable" class="table table-bordered mb-0" style="font-size:13px;width:100%;">
                    <thead style="background:#2c5f2e;color:#fff;">
                        <tr>
                            <th style="width:40px;text-align:center;">
                                <input type="checkbox" id="selectAll" style="cursor:pointer;width:15px;height:15px;">
                            </th>
                            <th>#</th>
                            <th>Invoice No</th>
                            <th>Member</th>
                            <th>Date &amp; Time</th>
                            <th>Total Smart Points</th>
                            <th>Total Smart Qty</th>
                            <th>Grand Total</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($purchases as $i => $pur)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="{{ $pur->id }}"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </td>
                            <td>{{ $i + 1 }}</td>
                            <td>
                                <span class="badge" style="background:#e6f1fb;color:#0c447c;font-size:11px;padding:3px 8px;border-radius:20px;">
                                    {{ $pur->invoice_no }}
                                </span>
                            </td>
                            <td>
                                <div style="font-size:13px;font-weight:700;color:#1a3a6b;line-height:1.3;">
                                    {{ $pur->member->name ?? 'Walk-in Customer' }}
                                </div>
                                @if($pur->member_id)
                                    <div style="font-size:11px;color:#0c447c;margin-top:2px;">
                                        <span style="background:#e6f1fb;padding:1px 7px;border-radius:12px;">
                                            {{ $pur->member_id }}
                                        </span>
                                    </div>
                                @endif
                                @if($pur->member->mobile ?? $pur->member->phone ?? null)
                                    <div style="font-size:11px;color:#6c757d;margin-top:2px;">
                                        <i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>
                                        {{ $pur->member->mobile ?? $pur->member->phone }}
                                    </div>
                                @endif
                            </td>
                            <td style="font-size:12px;color:#6c757d;white-space:nowrap;">
                                <div style="font-weight:500;color:#333;">{{ \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y') }}</div>
                                <div style="font-size:11px;color:#adb5bd;">{{ \Carbon\Carbon::parse($pur->purchase_date)->format('h:i A') }}</div>
                            </td>
                            <td>
                                <span class="badge" style="background:#eeedfe;color:#3c3489;font-size:11px;padding:3px 8px;border-radius:20px;">
                                    {{ number_format($pur->total_smartpoint, 4) }}
                                </span>
                            </td>
                            <td>{{ number_format($pur->total_smartquantity, 4) }}</td>
                            <td style="color:#27500a;font-weight:500;">₹{{ number_format($pur->total, 2) }}</td>
                            <td>
                                <span class="badge {{ $pur->status == 1 ? 'bg-success' : 'bg-danger' }}" style="font-size:11px;">
                                    {{ $pur->status == 1 ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="text-center">
                                <button type="button"
                                    class="btn btn-sm view-invoice-btn"
                                    style="font-size:11px;padding:3px 10px;background:#1a3a6b;color:#fff;border-radius:4px;border:none;"
                                    data-invoice="{{ $pur->invoice_no }}"
                                    data-purchase-date="{{ \Carbon\Carbon::parse($pur->purchase_date)->format('d M Y, h:i A') }}"
                                    data-member-id="{{ $pur->member_id ?? '' }}"
                                    data-member-name="{{ $pur->member->name ?? 'Walk-in Customer' }}"
                                    data-member-mobile="{{ $pur->member->mobile ?? $pur->member->phone ?? '' }}"
                                    data-member-email="{{ $pur->member->email ?? '' }}"
                                    data-member-address="{{ $pur->member->address ?? '' }}"
                                    data-total="{{ number_format($pur->total, 2) }}"
                                    data-smartpoint="{{ number_format($pur->total_smartpoint, 4) }}"
                                    data-smartqty="{{ number_format($pur->total_smartquantity, 4) }}"
                                    data-items='{{ json_encode($pur->items->map(function($item) {
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
                                    })) }}'>
                                    <i class="bi bi-receipt me-1"></i>View
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif

</main>

{{-- ===== INVOICE MODAL ===== --}}
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:8px;overflow:hidden;">

            <div class="modal-header" style="background:#1a3a6b;color:#fff;padding:12px 24px;">
                <h6 class="modal-title mb-0" style="font-size:13px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;">
                    <i class="bi bi-receipt me-2"></i>Invoice / Bill
                </h6>
                <div class="d-flex gap-2 align-items-center">
                    <button type="button" class="btn btn-sm btn-light" id="printInvoiceBtn"
                            style="font-size:12px;padding:3px 14px;">
                        <i class="bi bi-printer me-1"></i>Print
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>

            <div class="modal-body p-0" style="background:#dde3ea;">
                <div id="invoicePrintArea"
                     style="background:#fff;margin:24px auto;max-width:880px;font-family:'Segoe UI',Arial,sans-serif;border-radius:6px;overflow:hidden;box-shadow:0 4px 24px rgba(0,0,0,.13);">

                    <div style="height:6px;background:linear-gradient(90deg,#1a3a6b 0%,#2c5f2e 50%,#0c447c 100%);"></div>

                    <div style="padding:40px 52px 0 52px;">
                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:28px;">
                            <div>
                                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;">
                                    <div style="width:42px;height:42px;background:#1a3a6b;border-radius:8px;display:flex;align-items:center;justify-content:center;">
                                        <span style="color:#fff;font-size:15px;font-weight:900;letter-spacing:-1px;">SB</span>
                                    </div>
                                    <div>
                                        <div style="font-size:22px;font-weight:900;color:#1a3a6b;letter-spacing:-0.5px;line-height:1;">SBES</div>
                                        <div style="font-size:9px;color:#6c757d;text-transform:uppercase;letter-spacing:.18em;">SmartBoatEcoSystem</div>
                                    </div>
                                </div>
                                <div style="font-size:11px;color:#6c757d;margin-top:4px;">
                                    <i class="bi bi-globe2" style="margin-right:4px;"></i>smartboatecosystem.com
                                </div>
                            </div>
                            <div style="text-align:right;">
                                <div style="font-size:38px;font-weight:900;color:#1a3a6b;letter-spacing:4px;line-height:1;text-transform:uppercase;">INVOICE</div>
                                <div id="inv-invoice-no-hero" style="font-size:13px;font-weight:700;color:#0c447c;margin-top:6px;letter-spacing:.06em;"></div>
                            </div>
                        </div>

                        <div style="height:2px;background:linear-gradient(90deg,#1a3a6b,#2c5f2e);border-radius:2px;margin-bottom:28px;"></div>

                        <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:32px;gap:24px;">
                            <div style="flex:1;background:#f4f7fb;border-left:4px solid #1a3a6b;border-radius:0 6px 6px 0;padding:16px 20px;">
                                <div style="font-size:9px;font-weight:800;color:#1a3a6b;text-transform:uppercase;letter-spacing:.18em;margin-bottom:10px;">Bill To</div>
                                <div id="inv-member-name" style="font-size:16px;font-weight:800;color:#111;margin-bottom:6px;"></div>
                                <div id="inv-member-id" style="margin-bottom:3px;"></div>
                                <div id="inv-member-mobile" style="font-size:12px;color:#495057;margin-bottom:3px;"></div>
                                <div id="inv-member-email" style="font-size:12px;color:#495057;margin-bottom:3px;"></div>
                                <div id="inv-member-address" style="font-size:12px;color:#495057;"></div>
                            </div>
                            <div style="min-width:240px;background:#f4f7fb;border-right:4px solid #2c5f2e;border-radius:6px 0 0 6px;padding:16px 20px;">
                                <div style="font-size:9px;font-weight:800;color:#2c5f2e;text-transform:uppercase;letter-spacing:.18em;margin-bottom:10px;">Invoice Details</div>
                                <table style="font-size:12px;border-collapse:collapse;width:100%;">
                                    <tr>
                                        <td style="color:#6c757d;padding:4px 12px 4px 0;white-space:nowrap;font-weight:500;">Invoice No</td>
                                        <td id="inv-invoice-no" style="font-weight:800;color:#1a3a6b;text-align:right;"></td>
                                    </tr>
                                    <tr>
                                        <td style="color:#6c757d;padding:4px 12px 4px 0;font-weight:500;">Purchase Date</td>
                                        <td id="inv-purchase-date" style="font-weight:700;color:#111;text-align:right;"></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- Items Table --}}
                    <div style="padding:0 52px;">
                        <table style="width:100%;border-collapse:collapse;font-size:12px;margin-bottom:0;">
                            <thead>
                                <tr style="background:#1a3a6b;color:#fff;">
                                    <th style="padding:11px 12px;text-align:left;font-weight:600;letter-spacing:.04em;">#</th>
                                    <th style="padding:11px 12px;text-align:left;font-weight:600;letter-spacing:.04em;">Product</th>
                                    <th style="padding:11px 12px;text-align:left;font-weight:600;letter-spacing:.04em;">HSN</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">Base Price</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">DP</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">Count</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">Smart Pts</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">Smart Qty</th>
                                    <th style="padding:11px 12px;text-align:right;font-weight:600;letter-spacing:.04em;">Amount</th>
                                </tr>
                            </thead>
                            <tbody id="inv-items-body"></tbody>
                        </table>
                    </div>

                    {{-- Grand Total --}}
                    <div style="padding:0 52px;margin-top:0;">
                        <div style="display:flex;justify-content:flex-end;">
                            <div style="background:#2c5f2e;color:#fff;padding:13px 28px;display:flex;align-items:center;gap:48px;min-width:300px;border-radius:0 0 4px 4px;">
                                <span style="font-size:11px;font-weight:700;letter-spacing:.12em;text-transform:uppercase;opacity:.85;">Grand Total</span>
                                <span id="inv-grand-total" style="font-size:22px;font-weight:900;margin-left:auto;">₹0.00</span>
                            </div>
                        </div>
                    </div>

                    {{-- Smart Points + Qty + Terms --}}
                    <div style="display:flex;gap:16px;padding:24px 52px;margin-top:8px;">
                        <div style="flex:1;border:1.5px solid #e0e7ef;border-top:3px solid #3c3489;border-radius:6px;padding:14px 18px;background:#fafbff;">
                            <div style="font-size:9px;color:#3c3489;text-transform:uppercase;letter-spacing:.14em;margin-bottom:6px;font-weight:800;">Total Smart Points</div>
                            <div id="inv-smart-points" style="font-size:22px;font-weight:900;color:#3c3489;"></div>
                        </div>
                        <div style="flex:1;border:1.5px solid #e0e7ef;border-top:3px solid #0c447c;border-radius:6px;padding:14px 18px;background:#f6faff;">
                            <div style="font-size:9px;color:#0c447c;text-transform:uppercase;letter-spacing:.14em;margin-bottom:6px;font-weight:800;">Total Smart Qty</div>
                            <div id="inv-smart-qty" style="font-size:22px;font-weight:900;color:#0c447c;"></div>
                        </div>
                        <div style="flex:2;border:1.5px solid #e0e7ef;border-top:3px solid #2c5f2e;border-radius:6px;padding:14px 18px;background:#f6fff7;">
                            <div style="font-size:9px;color:#2c5f2e;text-transform:uppercase;letter-spacing:.14em;margin-bottom:6px;font-weight:800;">Terms &amp; Conditions</div>
                            <div style="font-size:11px;color:#495057;line-height:1.7;">
                                This invoice is for the amount listed, which is the total cost for the goods or services provided.
                                Please pay the full amount within the agreed payment period.
                            </div>
                        </div>
                    </div>

                    <div style="background:#f4f7fb;border-top:1px solid #e0e7ef;padding:12px 52px;display:flex;justify-content:space-between;align-items:center;">
                        <div style="font-size:10px;color:#6c757d;">Thank you for your business!</div>
                        <div style="font-size:10px;color:#6c757d;">smartboatecosystem.com</div>
                    </div>

                    <div style="height:5px;background:linear-gradient(90deg,#1a3a6b 0%,#2c5f2e 50%,#0c447c 100%);"></div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Print Styles --}}
<style>
    @media print {
        body > *:not(#invoiceModal) { display: none !important; }
        #invoiceModal              { display: block !important; position: static !important; }
        .modal-dialog              { max-width: 100% !important; margin: 0 !important; }
        .modal-header,
        .modal-backdrop            { display: none !important; }
        #invoicePrintArea          {
            box-shadow: none !important;
            margin: 0 !important;
            max-width: 100% !important;
            border-radius: 0 !important;
        }
    }

    #purchaseHistoryTable_wrapper .dataTables_length label,
    #purchaseHistoryTable_wrapper .dataTables_filter label,
    #purchaseHistoryTable_wrapper .dataTables_info,
    #purchaseHistoryTable_wrapper .dataTables_paginate { font-size:12px;color:#495057; }
    #purchaseHistoryTable_wrapper .dataTables_filter input {
        border:1px solid #dee2e6;border-radius:4px;padding:3px 8px;font-size:12px;margin-left:6px; }
    #purchaseHistoryTable_wrapper .dataTables_length select {
        border:1px solid #dee2e6;border-radius:4px;padding:2px 6px;font-size:12px;margin:0 4px; }
    #purchaseHistoryTable_wrapper .paginate_button { font-size:12px !important;padding:3px 8px !important; }
    #purchaseHistoryTable_wrapper .paginate_button.current,
    #purchaseHistoryTable_wrapper .paginate_button.current:hover {
        background:#1a3a6b !important;border-color:#1a3a6b !important;color:#fff !important;border-radius:4px; }
    #purchaseHistoryTable thead th { background:#2c5f2e;color:#fff;border-color:#2c5f2e; }
    #purchaseHistoryTable_wrapper .dataTables_filter { margin-bottom:8px; }
    #purchaseHistoryTable_wrapper .dt-buttons { margin-bottom:8px; }
    #purchaseHistoryTable_wrapper .dt-button {
        font-size:12px !important;padding:4px 12px !important;border-radius:4px !important;
        border:1px solid #dee2e6 !important;background:#fff !important;
        color:#495057 !important;margin-right:4px !important;cursor:pointer;transition:background .15s; }
    #purchaseHistoryTable_wrapper .dt-button:hover { background:#f0f0f0 !important;color:#1a3a6b !important; }
    #purchaseHistoryTable_wrapper .buttons-pdf    { border-color:#dc3545 !important;color:#dc3545 !important; }
    #purchaseHistoryTable_wrapper .buttons-excel  { border-color:#198754 !important;color:#198754 !important; }
    #purchaseHistoryTable_wrapper .buttons-print  { border-color:#1a3a6b !important;color:#1a3a6b !important; }
    #purchaseHistoryTable_wrapper .buttons-pdf:hover   { background:#dc3545 !important;color:#fff !important; }
    #purchaseHistoryTable_wrapper .buttons-excel:hover { background:#198754 !important;color:#fff !important; }
    #purchaseHistoryTable_wrapper .buttons-print:hover { background:#1a3a6b !important;color:#fff !important; }
    #bulkActionBar { display:none; }
    #bulkActionBar.show { display:flex !important; }
    #purchaseHistoryTable tbody tr.row-selected { background:#e8f4fd !important; }

    .product-dropdown .prod-item {
        padding:8px 12px;cursor:pointer;border-bottom:1px solid #f0f0f0;font-size:12px;transition:background .12s; }
    .product-dropdown .prod-item:hover,
    .product-dropdown .prod-item.active { background:#e8f2ff; }
    .product-dropdown .prod-item .prod-name { font-weight:700;color:#1a3a6b;font-size:12px; }
    .product-dropdown .prod-item .prod-meta { font-size:10px;color:#6c757d;margin-top:2px; }
    .product-dropdown .prod-item .prod-meta .badge-cat {
        display:inline-block;background:#e6f1fb;color:#0c447c;border-radius:10px;
        padding:1px 7px;font-size:10px;font-weight:600;margin-right:4px; }
    .product-dropdown .prod-item .prod-meta .badge-sub {
        display:inline-block;background:#eeedfe;color:#3c3489;border-radius:10px;
        padding:1px 7px;font-size:10px;font-weight:600; }
    .product-dropdown .prod-no-result { padding:10px 12px;font-size:12px;color:#dc3545; }
    .product-search-input { border:1px solid #dee2e6 !important;border-radius:4px !important; }
    .product-search-input:focus { border-color:#1a3a6b !important;box-shadow:0 0 0 2px rgba(26,58,107,.12) !important; }
</style>

@endsection

@push('scripts')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {

    // ── Auto-reopen modal if validation failed ──────────────────────
    @if(session('error') || $errors->any())
        var addModal = new bootstrap.Modal(document.getElementById('addModal'));
        addModal.show();
    @endif

    const allProducts = @json($productsForJs);

    // ════════════════════════════════════════════════════════════════
    // 1. DATATABLES INIT
    // ════════════════════════════════════════════════════════════════
    @if($purchases->count())
    $('#purchaseHistoryTable').DataTable({
        order: [[1, 'asc']],
        pageLength: 25,
        lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, 'All']],
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 9] }
        ],
        rowCallback: function(row, data, index) {
            $('td:eq(1)', row).text(index + 1);
        },
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'Purchase History',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'Purchase History',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'Purchase History',
                exportOptions: { columns: [1,2,3,4,5,6,7,8] }
            }
        ],
        language: {
            search: '<i class="bi bi-search"></i>',
            searchPlaceholder: 'Search purchases…',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ records',
            infoEmpty: 'No records found',
            paginate: { previous: '‹', next: '›' }
        },
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
            "<'row'<'col-sm-12'tr>>" +
            "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });
    @endif

    // ════════════════════════════════════════════════════════════════
    // 2. BULK DELETE
    // ════════════════════════════════════════════════════════════════
    function updateBulkBar() {
        const checked = $('.row-checkbox:checked');
        const count   = checked.length;
        if (count > 0) {
            $('#bulkActionBar').addClass('show');
            $('#selectedCount').text(count + ' selected');
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
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        $('#purchaseHistoryTable tbody tr').toggleClass('row-selected', isChecked);
        updateBulkBar();
    });

    $(document).on('change', '.row-checkbox', function () {
        $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
        const total   = $('.row-checkbox').length;
        const checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
        $('#selectAll').prop('checked', checked === total);
        updateBulkBar();
    });

    $('#clearSelection').on('click', function () {
        $('.row-checkbox').prop('checked', false);
        $('#purchaseHistoryTable tbody tr').removeClass('row-selected');
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        updateBulkBar();
    });

    // ════════════════════════════════════════════════════════════════
    // 3. MEMBER LIVE SEARCH
    // ════════════════════════════════════════════════════════════════
    let memberTimer = null;

    $(document).on('click', function (e) {
        if (!$(e.target).closest('#memberIdWrapper').length) {
            $('#memberDropdown').hide();
        }
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
        memberTimer = setTimeout(function () {
            $.get("{{ route('member.productpurchase.member') }}", { member_id: val })
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
                            item.on('mouseenter', function () { $(this).css('background','#f0f6ff'); });
                            item.on('mouseleave', function () { $(this).css('background','#fff'); });
                            item.on('click', function () {
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
                    $('#memberDropdown').hide();
                    $('#memberName').html('<span style="color:#dc3545;">✘ Lookup failed</span>');
                });
        }, 400);
    });

    $('#memberSearchInput').on('keydown', function (e) {
        const items  = $('#memberDropdown .member-result-item');
        const active = $('#memberDropdown .member-result-item.active');
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active.length) { items.first().addClass('active').css('background','#f0f6ff'); }
            else { active.removeClass('active').css('background','#fff').next().addClass('active').css('background','#f0f6ff'); }
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active.length) { active.removeClass('active').css('background','#fff').prev().addClass('active').css('background','#f0f6ff'); }
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

    // ════════════════════════════════════════════════════════════════
    // 4. INVOICE MODAL — populate & open
    // ════════════════════════════════════════════════════════════════
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

        // Populate Invoice Details
        $('#inv-invoice-no-hero').text(invoiceNo);
        $('#inv-invoice-no').text(invoiceNo);
        $('#inv-purchase-date').text(purchaseDate);
        $('#inv-member-name').text(mName);
        $('#inv-member-id').html(     mId      ? `<span style="background:#e6f1fb;color:#0c447c;padding:1px 8px;border-radius:12px;font-size:11px;">ID: ${mId}</span>`  : '');
        $('#inv-member-mobile').html( mMobile  ? `<i class="bi bi-telephone-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>${mMobile}`  : '');
        $('#inv-member-email').html(  mEmail   ? `<i class="bi bi-envelope-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>${mEmail}`    : '');
        $('#inv-member-address').html(mAddress ? `<i class="bi bi-geo-alt-fill me-1" style="color:#1a3a6b;font-size:10px;"></i>${mAddress}`   : '');
        $('#inv-grand-total').text('₹' + grandTotal);
        $('#inv-smart-points').text(smartPoint);
        $('#inv-smart-qty').text(smartQty);

        // Build items table rows
        let tbody = '';
        $.each(items, function (idx, item) {
            const rowBg = (idx % 2 === 1) ? '#f4f7fb' : '#ffffff';
            tbody += `
            <tr style="background:${rowBg};border-bottom:1px solid #e9ecef;">
                <td style="padding:10px 12px;color:#adb5bd;font-weight:700;font-size:11px;">${String(idx+1).padStart(2,'0')}</td>
                <td style="padding:10px 12px;font-weight:700;color:#111;">${item.name}</td>
                <td style="padding:10px 12px;color:#6c757d;">${item.hsn}</td>
                <td style="padding:10px 12px;text-align:right;color:#495057;">₹${item.base}</td>
                <td style="padding:10px 12px;text-align:right;font-weight:700;color:#1a3a6b;">₹${item.dp}</td>
                <td style="padding:10px 12px;text-align:right;color:#495057;">${item.count}</td>
                <td style="padding:10px 12px;text-align:right;color:#3c3489;font-weight:600;">${item.sp}</td>
                <td style="padding:10px 12px;text-align:right;color:#0c447c;font-weight:600;">${item.sq}</td>
                <td style="padding:10px 12px;text-align:right;font-weight:800;color:#27500a;">₹${item.total}</td>
            </tr>`;
        });
        $('#inv-items-body').html(tbody);

        const invoiceModal = new bootstrap.Modal(document.getElementById('invoiceModal'));
        invoiceModal.show();
    });

    $('#printInvoiceBtn').on('click', function () { window.print(); });

    // ════════════════════════════════════════════════════════════════
    // 5. PRODUCT PICKER
    // ════════════════════════════════════════════════════════════════
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
        const p = allProducts.find(function(x){ return x.id == pid; });
        if (p) calcRowFromProduct(row, p);
    }

    function recalcTotals() {
        let total = 0, totalSP = 0, totalSQ = 0;
        $('#purchaseRows .purchase-row').each(function () {
            const pid = $(this).find('.product-id-input').val();
            const dp  = parseFloat($(this).find('.dp-input').val()) || 0;
            if (!pid) return;
            const p = allProducts.find(function(x){ return x.id == pid; });
            if (!p) return;
            const sp = parseFloat(p.sp) || 0;
            const sq = sp * 0.001;
            total   += dp;
            totalSP += dp * sp;
            totalSQ += dp * sq;
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
        const wrapper = $(this).closest('.product-picker-wrapper');
        buildDropdown(wrapper, $(this).val());
    });

    $(document).on('keydown', '.product-search-input', function (e) {
        const wrapper = $(this).closest('.product-picker-wrapper');
        const dd      = wrapper.find('.product-dropdown');
        const items   = dd.find('.prod-item');
        const active  = dd.find('.prod-item.active');

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (!active.length) { items.first().addClass('active'); }
            else { active.removeClass('active').next('.prod-item').addClass('active'); }
            const nowActive = dd.find('.prod-item.active');
            if (nowActive.length) dd.scrollTop(dd.scrollTop() + nowActive.position().top - dd.height()/2);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (active.length) active.removeClass('active').prev('.prod-item').addClass('active');
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (active.length) {
                const pid = active.data('id');
                const p   = allProducts.find(function(x){ return x.id == pid; });
                if (p) selectProduct(wrapper, p);
            }
        } else if (e.key === 'Escape') {
            dd.hide();
        }
    });

    $(document).on('click', function (e) {
        if (!$(e.target).closest('.product-picker-wrapper').length) {
            $('.product-dropdown').hide();
        }
    });

    $(document).on('input', '.dp-input', function () {
        calcRow($(this).closest('tr'));
        recalcTotals();
    });

    // ════════════════════════════════════════════════════════════════
    // 6. ADD / REMOVE ROWS
    // ════════════════════════════════════════════════════════════════
    function makeRowHtml(rowNum) {
        return `
        <tr class="purchase-row">
            <td class="text-center text-muted row-num">${rowNum}</td>
            <td>
                <div class="product-picker-wrapper" style="position:relative;min-width:220px;">
                    <input type="text"
                           class="form-control form-control-sm product-search-input"
                           placeholder="🔍 Search product…"
                           autocomplete="off"
                           style="font-size:12px;">
                    <input type="hidden" name="product_ids[]" class="product-id-input" required>
                    <div class="product-dropdown"
                         style="display:none;position:absolute;top:100%;left:0;right:0;z-index:9999;
                                background:#fff;border:1px solid #dee2e6;border-radius:0 0 6px 6px;
                                box-shadow:0 4px 12px rgba(0,0,0,.12);max-height:260px;overflow-y:auto;">
                    </div>
                </div>
            </td>
            <td class="hsn-cell">—</td>
            <td class="base-cell">—</td>
            <td>
                <input type="number" name="dp[]" class="form-control form-control-sm dp-input"
                    min="0" step="0.01" placeholder="0.00" style="width:110px;" required>
            </td>
            <td class="count-cell">—</td>
            <td class="sp-cell">—</td>
            <td class="sq-cell">—</td>
            <td class="amount-cell fw-500" style="color:#27500a;">—</td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-danger remove-row" style="padding:2px 8px;font-size:12px;">&times;</button>
            </td>
        </tr>`;
    }

    $('#addRow').on('click', function () {
        const rowCount = $('#purchaseRows .purchase-row').length + 1;
        $('#purchaseRows').append(makeRowHtml(rowCount));
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
@endpush
