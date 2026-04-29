<?php $__env->startSection('content'); ?>

<style>
/* ── TAB HEADER ── */
.profile-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.tab-btn {
    padding: 9px 20px;
    border-radius: 8px;
    border: 1.5px solid #dee2e6;
    background: #fff;
    font-size: 13px;
    font-weight: 600;
    color: #495057;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 7px;
    transition: all .15s;
}
.tab-btn:hover { border-color: #1a3a6b; color: #1a3a6b; }
.tab-btn.active {
    background: #1a3a6b;
    border-color: #1a3a6b;
    color: #fff;
}
.tab-panel { display: none; }
.tab-panel.active { display: block; }

/* ── PROFILE ── */
.profile-header-card {
    background: linear-gradient(135deg, #1a3a6b 0%, #0c2a55 100%);
    border-radius: 12px;
    padding: 28px;
    display: flex;
    align-items: center;
    gap: 22px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.avatar-big {
    width: 72px; height: 72px;
    background: linear-gradient(135deg, #2c5f2e, #27500a);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 28px; font-weight: 800; color: #fff; flex-shrink: 0;
}
.profile-header-info h5 { color: #fff; font-size: 18px; font-weight: 700; margin: 0 0 4px; }
.profile-header-info p  { color: rgba(255,255,255,.65); font-size: 12px; margin: 0; }
.profile-stat-badges    { display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap; }
.psb {
    background: rgba(255,255,255,.12);
    border: 1px solid rgba(255,255,255,.2);
    border-radius: 20px; padding: 4px 14px;
    font-size: 12px; color: #fff; font-weight: 600;
}
.psb.green {
    background: rgba(44,95,46,.4);
    border-color: rgba(77,214,156,.4);
    color: #4dd69c;
}
.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 14px; margin-bottom: 20px;
}
.info-box {
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 10px; padding: 14px 18px;
}
.info-box label {
    font-size: 11px; color: #6c757d; text-transform: uppercase;
    letter-spacing: .06em; font-weight: 600; display: block; margin-bottom: 4px;
}
.info-box span { font-size: 14px; font-weight: 600; color: #212529; }

/* ── QR PAYMENT SECTION ── */
.payment-inline-section {
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 12px; padding: 20px 24px; margin-bottom: 18px;
}
.payment-inline-section h6 { font-size: 14px; font-weight: 700; color: #1a3a6b; margin-bottom: 4px; }
.payment-inline-section p.sub { font-size: 12px; color: #6c757d; margin-bottom: 16px; }
.qr-payment-wrap { display: flex; gap: 20px; flex-wrap: wrap; align-items: flex-start; }
.qr-box {
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 12px; padding: 24px; text-align: center; flex-shrink: 0;
}
.qr-box img { width: 180px; height: 180px; object-fit: contain; }
.payment-info-box {
    background: #fff8e1; border: 1px solid #ffe082;
    border-radius: 12px; padding: 20px 24px; flex: 1; min-width: 220px;
}
.payment-info-box h6  { color: #b45309; font-weight: 700; margin-bottom: 10px; }
.payment-info-box p   { font-size: 13px; color: #374151; margin-bottom: 8px; }
.payment-info-box .phone { font-size: 22px; font-weight: 800; color: #1a3a6b; letter-spacing: .03em; }

/* ── PAYMENT FORM CARD ── */
.payment-form-card {
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 12px; padding: 24px; margin-bottom: 18px;
}
.payment-form-card h6 {
    font-size: 14px; font-weight: 700; color: #1a3a6b;
    margin-bottom: 4px; display: flex; align-items: center; gap: 8px;
}
.payment-form-card p.sub { font-size: 12px; color: #6c757d; margin-bottom: 20px; }

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
    gap: 16px; margin-bottom: 20px;
}
.form-group-custom label {
    font-size: 11px; font-weight: 700; color: #374151;
    text-transform: uppercase; letter-spacing: .05em;
    margin-bottom: 6px; display: block;
}
.form-group-custom input,
.form-group-custom select {
    width: 100%; padding: 10px 14px;
    border: 1.5px solid #dee2e6; border-radius: 8px;
    font-size: 13px; color: #212529; background: #f8f9fa;
    transition: border-color .15s, background .15s;
    outline: none; box-sizing: border-box;
}
.form-group-custom input:focus,
.form-group-custom select:focus {
    border-color: #1a3a6b; background: #fff;
    box-shadow: 0 0 0 3px rgba(26,58,107,.08);
}
.form-group-custom input::placeholder { color: #adb5bd; }

.divider-label {
    display: flex; align-items: center; gap: 12px;
    margin: 20px 0 16px; font-size: 12px; font-weight: 700;
    color: #6c757d; text-transform: uppercase; letter-spacing: .07em;
}
.divider-label::before,
.divider-label::after { content: ''; flex: 1; height: 1px; background: #e9ecef; }

.qr-upload-box {
    border: 2px dashed #dee2e6; border-radius: 10px; padding: 24px;
    text-align: center; background: #f8f9fa; cursor: pointer;
    transition: border-color .15s, background .15s; position: relative;
}
.qr-upload-box:hover { border-color: #1a3a6b; background: #f0f4ff; }
.qr-upload-box input[type="file"] {
    position: absolute; inset: 0; opacity: 0;
    cursor: pointer; width: 100%; height: 100%;
}
.qr-upload-box i { font-size: 28px; color: #1a3a6b; opacity: .5; }
.qr-upload-box p { font-size: 12px; color: #6c757d; margin: 8px 0 0; }
.qr-preview-img {
    width: 100px; height: 100px; object-fit: contain;
    border-radius: 8px; border: 1px solid #e9ecef;
    display: none; margin: 10px auto 0;
}

.btn-save-payment {
    background: linear-gradient(135deg, #1a3a6b, #0c2a55);
    color: #fff; border: none; border-radius: 8px;
    padding: 11px 28px; font-size: 13px; font-weight: 700;
    cursor: pointer; display: inline-flex; align-items: center;
    gap: 8px; transition: opacity .15s;
}
.btn-save-payment:hover { opacity: .88; }

/* ── PAYMENT TABLE ── */
.pay-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.pay-table thead tr { background: #f0f4ff; }
.pay-table thead th {
    padding: 12px 16px; text-align: left; font-size: 12px;
    font-weight: 700; color: #1a3a6b; letter-spacing: .04em;
    border-bottom: 2px solid #bfdbfe;
}
.pay-table tbody td {
    padding: 11px 16px; border-bottom: 1px solid #e9ecef;
    font-weight: 600;
}
.pay-table tbody tr:nth-child(odd)  td { background: #fff; }
.pay-table tbody tr:nth-child(even) td { background: #f8faff; }
.pay-table .lbl { width: 180px; color: #6c757d; font-size: 12px; }
.pay-table .val { color: #212529; }

/* ── TRANSACTION TABLE ── */
.txn-table { width: 100%; border-collapse: collapse; font-size: 13px; }
.txn-table thead th {
    background: #f8f9fa; padding: 10px 14px; font-size: 11px;
    font-weight: 700; text-transform: uppercase; letter-spacing: .06em;
    color: #6c757d; border-bottom: 2px solid #e9ecef; white-space: nowrap;
}
.txn-table tbody td {
    padding: 11px 14px; border-bottom: 1px solid #f1f3f5; vertical-align: middle;
}
.txn-table tbody tr:hover { background: #f8faff; }
.badge-credit         { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.badge-debit          { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.badge-success-status { background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
.empty-txn { text-align: center; padding: 40px; color: #6c757d; font-size: 13px; }

.section-card {
    background: #fff; border: 1px solid #e9ecef;
    border-radius: 12px; padding: 20px 24px; margin-bottom: 18px;
}
.section-card h6    { font-size: 14px; font-weight: 700; color: #1a3a6b; margin-bottom: 4px; }
.section-card p.sub { font-size: 12px; color: #6c757d; margin-bottom: 16px; }
</style>


<div class="profile-tabs">
    <button class="tab-btn active" onclick="switchTab('profile')" id="tab-profile">
        <i class="bi bi-person-circle"></i> Profile Details
    </button>
    <button class="tab-btn" onclick="switchTab('payment')" id="tab-payment">
        <i class="bi bi-credit-card-2-front"></i> Payment Details
    </button>
    <button class="tab-btn" onclick="switchTab('transaction')" id="tab-transaction">
        <i class="bi bi-clock-history"></i> Transaction Details
    </button>
</div>


<div class="tab-panel active" id="panel-profile">

    <div class="profile-header-card">
        <div class="avatar-big"><?php echo e(strtoupper(substr($member->name, 0, 1))); ?></div>
        <div class="profile-header-info">
            <h5><?php echo e($member->name); ?></h5>
            <p><?php echo e($member->memberID); ?></p>
            <div class="profile-stat-badges">
                <span class="psb">Smart Points: <?php echo e(number_format($member->smart_point, 4)); ?></span>
                <span class="psb">Smart Qty: <?php echo e($member->smart_quanity ?: '0.0000'); ?></span>
                <span class="psb green">₹<?php echo e(number_format($smartWalletBalance ?? 0, 2)); ?> Wallet</span>
            </div>
        </div>
    </div>

    <div class="info-grid">
        <div class="info-box"><label>Full Name</label><span><?php echo e($member->name); ?></span></div>
        <div class="info-box"><label>Member ID</label><span><?php echo e($member->memberID); ?></span></div>
        <div class="info-box"><label>Joining Date</label><span><?php echo e($member->joining_date); ?></span></div>
        <div class="info-box"><label>Referral Code</label><span><?php echo e($member->referral_code); ?></span></div>
        <div class="info-box">
            <label>Status</label>
            <span>
                <?php if($member->status == 1): ?>
                    <span class="badge bg-success">Active</span>
                <?php elseif($member->status == 2): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php else: ?>
                    <span class="badge bg-danger">Blocked</span>
                <?php endif; ?>
            </span>
        </div>
        <div class="info-box">
            <label>Smart Wallet Balance</label>
            <span class="text-success">₹<?php echo e(number_format($smartWalletBalance ?? 0, 2)); ?></span>
        </div>
    </div>

    <div class="payment-inline-section">
        <h6><i class="bi bi-qr-code me-1"></i> Payment via QR Code</h6>
        <p class="sub">Scan the QR code to make your payment directly</p>
        <div class="qr-payment-wrap">
            <div class="qr-box">
                <img src="https://smartboatecosystem.com/Main/public/admin/assets/images/HindolMukherjeeQRCode.png"
                     alt="QR Code"
                     onclick="document.getElementById('qrModal').style.display='flex'"
                     style="cursor:pointer;">
                <p style="font-size:11px;color:#6c757d;margin-top:8px;">Tap to zoom</p>
                <button onclick="document.getElementById('qrModal').style.display='flex'"
                        style="margin-top:6px;padding:7px 18px;background:#1a3a6b;color:#fff;
                               border:none;border-radius:6px;font-size:12px;font-weight:600;cursor:pointer;">
                    <i class="bi bi-zoom-in"></i> View Full QR
                </button>
            </div>
            <div class="payment-info-box">
                <h6><i class="bi bi-megaphone-fill me-1"></i> After Payment Instructions</h6>
                <p>After completing your payment via QR code, please contact us immediately at:</p>
                <div class="phone">📞 82502 57091</div>
                <p style="margin-top:8px;font-size:12px;color:#6c757d;">to add wallet amount to your account.</p>
                <hr style="border-color:#ffe082;">
                <p style="font-size:12px;margin:0;">
                    <i class="bi bi-envelope-fill me-1 text-warning"></i>
                    <strong>smartboatofficial@gmail.com</strong>
                </p>
            </div>
        </div>
    </div>

</div>


<div class="tab-panel" id="panel-payment">
    <div class="payment-form-card">

        
        <div style="display:flex;align-items:center;justify-content:space-between;
                    flex-wrap:wrap;gap:10px;margin-bottom:4px;">
            <h6 style="margin:0;font-size:14px;font-weight:700;color:#1a3a6b;">
                <i class="bi bi-bank me-1"></i> Payment Details
            </h6>
            
            <?php if(!$memberPayment): ?>
            <button id="btnAddPayment" onclick="togglePaymentForm()"
                    style="padding:8px 20px;background:#1a3a6b;color:#fff;border:none;
                           border-radius:8px;font-size:12px;font-weight:700;cursor:pointer;
                           display:inline-flex;align-items:center;gap:6px;">
                <i class="bi bi-plus-circle-fill" id="btnAddIcon"></i>
                <span id="btnAddText">Add Details</span>
            </button>
            <?php endif; ?>
        </div>
        <p style="font-size:12px;color:#6c757d;margin-bottom:16px;">
            Your saved bank account, UPI and QR code details
        </p>

        
        <?php if($memberPayment): ?>
        <div style="overflow-x:auto;">
            <table class="pay-table">
                <thead>
                    <tr>
                        <th colspan="2">
                            <i class="bi bi-shield-check me-1"></i> Saved Payment Details
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $payRows = [
                            ['icon' => 'bi-person-fill',  'label' => 'Account Holder', 'value' => $memberPayment->account_holder],
                            ['icon' => 'bi-building',     'label' => 'Bank Name',      'value' => $memberPayment->bank_name],
                            ['icon' => 'bi-hash',         'label' => 'Account No.',    'value' => $memberPayment->account_number],
                            ['icon' => 'bi-code-slash',   'label' => 'IFSC Code',      'value' => $memberPayment->ifsc_code],
                            ['icon' => 'bi-map',          'label' => 'Branch',         'value' => $memberPayment->branch_name],
                            ['icon' => 'bi-credit-card',  'label' => 'Account Type',   'value' => ucfirst($memberPayment->account_type ?? '')],
                            ['icon' => 'bi-at',           'label' => 'UPI ID',         'value' => $memberPayment->upi_id],
                            ['icon' => 'bi-phone-fill',   'label' => 'UPI Mobile',     'value' => $memberPayment->upi_mobile],
                            ['icon' => 'bi-app',          'label' => 'UPI App',        'value' => strtoupper($memberPayment->upi_app ?? '')],
                        ];
                    ?>

                    <?php $__currentLoopData = $payRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php if($row['value']): ?>
                        <tr>
                            <td class="lbl">
                                <i class="bi <?php echo e($row['icon']); ?> me-2" style="color:#1a3a6b;"></i>
                                <?php echo e($row['label']); ?>

                            </td>
                            <td class="val"><?php echo e($row['value']); ?></td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if($memberPayment->qr_code): ?>
                    <tr>
                        <td class="lbl">
                            <i class="bi bi-qr-code me-2" style="color:#1a3a6b;"></i> QR Code
                        </td>
                        <td class="val">
                            <img src="<?php echo e(asset('storage/'.$memberPayment->qr_code)); ?>"
                                 style="width:80px;height:80px;object-fit:contain;
                                        border:1px solid #e9ecef;border-radius:8px;">
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php else: ?>

        
        <div id="emptyState" style="text-align:center;padding:24px 0 10px;color:#6c757d;font-size:13px;">
            <i class="bi bi-credit-card"
               style="font-size:36px;opacity:.25;display:block;margin-bottom:10px;"></i>
            <p style="margin:0;">No payment details saved yet.<br>
               Click <strong>Add Details</strong> to add your bank &amp; UPI info.</p>
        </div>

        
        <div id="paymentFormWrap" style="display:none;margin-top:16px;">
            <form id="paymentDetailsForm" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>

                <div class="divider-label"><i class="bi bi-building me-1"></i> Bank Account Details</div>
                <div class="form-grid">
                    <div class="form-group-custom">
                        <label><i class="bi bi-person me-1"></i> Account Holder Name</label>
                        <input type="text" name="account_holder" placeholder="Enter account holder name">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-hash me-1"></i> Account Number</label>
                        <input type="text" name="account_number" placeholder="Enter account number">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-bank me-1"></i> Bank Name</label>
                        <input type="text" name="bank_name" placeholder="e.g. State Bank of India">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-code-slash me-1"></i> IFSC Code</label>
                        <input type="text" name="ifsc_code" placeholder="e.g. SBIN0001234"
                               style="text-transform:uppercase;">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-map me-1"></i> Branch Name</label>
                        <input type="text" name="branch_name" placeholder="Enter branch name">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-credit-card me-1"></i> Account Type</label>
                        <select name="account_type">
                            <option value="">-- Select Type --</option>
                            <option value="savings">Savings Account</option>
                            <option value="current">Current Account</option>
                        </select>
                    </div>
                </div>

                <div class="divider-label"><i class="bi bi-phone me-1"></i> UPI / Digital Payment</div>
                <div class="form-grid">
                    <div class="form-group-custom">
                        <label><i class="bi bi-at me-1"></i> UPI ID</label>
                        <input type="text" name="upi_id" placeholder="e.g. yourname@upi">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-phone-fill me-1"></i> UPI Registered Mobile</label>
                        <input type="tel" name="upi_mobile" placeholder="Enter UPI linked mobile number">
                    </div>
                    <div class="form-group-custom">
                        <label><i class="bi bi-app me-1"></i> UPI App</label>
                        <select name="upi_app">
                            <option value="">-- Select UPI App --</option>
                            <option value="gpay">Google Pay</option>
                            <option value="phonepe">PhonePe</option>
                            <option value="paytm">Paytm</option>
                            <option value="bhim">BHIM</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>

                <div class="divider-label"><i class="bi bi-qr-code me-1"></i> Personal QR Code</div>
                <div style="max-width:320px;">
                    <div class="qr-upload-box">
                        <input type="file" name="qr_code" accept="image/*" onchange="previewQR(this)">
                        <i class="bi bi-qr-code-scan"></i>
                        <p>Click to upload your QR code
                            <br><span style="font-size:11px;color:#adb5bd;">PNG, JPG up to 2MB</span>
                        </p>
                        <img id="qrPreview" class="qr-preview-img" src="" alt="QR Preview">
                    </div>
                </div>

                <div style="margin-top:28px;display:flex;align-items:center;gap:14px;flex-wrap:wrap;">
                    <button type="button" class="btn-save-payment" onclick="savePaymentDetails()">
                        <i class="bi bi-check-circle-fill"></i> Save Payment Details
                    </button>
                    <button type="button" onclick="togglePaymentForm()"
                            style="padding:10px 22px;background:#f8f9fa;border:1.5px solid #dee2e6;
                                   border-radius:8px;font-size:13px;font-weight:600;
                                   color:#495057;cursor:pointer;">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                    </button>
                    <span id="saveMsg"
                          style="font-size:12px;color:#065f46;font-weight:600;display:none;">
                        <i class="bi bi-check-circle-fill me-1"></i> Saved successfully!
                    </span>
                </div>
            </form>
        </div>

        <?php endif; ?>
    </div>
</div>


<div class="tab-panel" id="panel-transaction">
    <div class="section-card">
        <h6><i class="bi bi-clock-history me-1"></i> Transaction History</h6>
        <p class="sub">All credit &amp; debit transactions linked to your account (<?php echo e($member->memberID); ?>)</p>

        <div style="overflow-x:auto;">
            <table class="txn-table" id="txnTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Action</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $txn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td style="color:#6c757d;"><?php echo e($i + 1); ?></td>
                        <td><?php echo e($txn->action); ?></td>
                        <td style="font-weight:600;">
                            <?php if(strtolower($txn->type) === 'credit'): ?>
                                <span class="text-success">+₹<?php echo e(number_format($txn->amount, 2)); ?></span>
                            <?php else: ?>
                                <span class="text-danger">-₹<?php echo e(number_format($txn->amount, 2)); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(strtolower($txn->type) === 'credit'): ?>
                                <span class="badge-credit">Credit</span>
                            <?php else: ?>
                                <span class="badge-debit">Debit</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if($txn->status == 1): ?>
                                <span class="badge-success-status">Success</span>
                            <?php else: ?>
                                <span style="background:#f3f4f6;color:#6b7280;padding:3px 9px;
                                             border-radius:20px;font-size:11px;font-weight:600;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td style="color:#6c757d;white-space:nowrap;">
                            <?php echo e(\Carbon\Carbon::parse($txn->created_at)->format('d M Y, h:i A')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-txn">
                                <i class="bi bi-inbox"
                                   style="font-size:36px;opacity:.3;display:block;margin-bottom:10px;"></i>
                                <p>No transactions found for <strong><?php echo e($member->memberID); ?></strong></p>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<div id="qrModal" onclick="this.style.display='none'"
     style="display:none;position:fixed;inset:0;z-index:9999;
            background:rgba(0,0,0,0.65);align-items:center;
            justify-content:center;padding:20px;">
    <div onclick="event.stopPropagation()"
         style="background:#fff;border-radius:16px;padding:28px 24px;
                max-width:340px;width:100%;text-align:center;
                box-shadow:0 20px 60px rgba(0,0,0,0.3);">
        <div style="text-align:right;margin-bottom:8px;">
            <span onclick="document.getElementById('qrModal').style.display='none'"
                  style="cursor:pointer;font-size:20px;color:#6c757d;">✕</span>
        </div>
        <img src="https://smartboatecosystem.com/Main/public/admin/assets/images/HindolMukherjeeQRCode.png"
             alt="QR Code"
             style="width:240px;height:240px;object-fit:contain;border-radius:8px;">
        <hr style="margin:16px 0;">
        <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:14px;">
            <p style="font-size:13px;font-weight:700;color:#b45309;margin:0 0 6px;">📢 After Payment</p>
            <p style="font-size:22px;font-weight:800;color:#1a3a6b;margin:8px 0;">📞 82502 57091</p>
            <p style="font-size:11px;color:#6c757d;margin:0;">to add wallet amount to your account.</p>
        </div>
        <button onclick="document.getElementById('qrModal').style.display='none'"
                style="margin-top:16px;width:100%;padding:10px;background:#1a3a6b;
                       color:#fff;border:none;border-radius:8px;font-size:13px;
                       font-weight:600;cursor:pointer;">Close</button>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
/* ── TAB SWITCHING ── */
function switchTab(tab) {
    document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.getElementById('panel-' + tab).classList.add('active');
    document.getElementById('tab-' + tab).classList.add('active');
}

/* ── TOGGLE ADD FORM ── */
var formOpen = false;
function togglePaymentForm() {
    formOpen = !formOpen;
    document.getElementById('paymentFormWrap').style.display = formOpen ? 'block' : 'none';
    document.getElementById('emptyState').style.display      = formOpen ? 'none'  : 'block';
    document.getElementById('btnAddText').textContent        = formOpen ? 'Cancel' : 'Add Details';
    document.getElementById('btnAddIcon').className          = formOpen
        ? 'bi bi-x-circle-fill' : 'bi bi-plus-circle-fill';
}

/* ── QR PREVIEW ── */
function previewQR(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = e => {
            const p = document.getElementById('qrPreview');
            p.src = e.target.result;
            p.style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    }
}

/* ── SAVE ── */
function savePaymentDetails() {
    const formData = new FormData(document.getElementById('paymentDetailsForm'));

    fetch('<?php echo e(route("member.payment.details.store")); ?>', {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>' },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            // Reload to render table view from DB
            window.location.reload();
        } else {
            alert(data.message || 'Failed to save. Please try again.');
        }
    })
    .catch(() => alert('Something went wrong. Please try again.'));
}

/* ── DATATABLES ── */
$(document).ready(function () {
    $('#txnTable').DataTable({
        pageLength: 10,
        order: [[5, 'desc']],
        dom: 'Bfrtip',
        buttons: ['excel', 'pdf', 'print'],
        language: {
            search: "Search transactions:",
            lengthMenu: "Show _MENU_ entries"
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH F:\xampp\htdocs\SmartBoat\ecosystemnew\resources\views/member/profile.blade.php ENDPATH**/ ?>