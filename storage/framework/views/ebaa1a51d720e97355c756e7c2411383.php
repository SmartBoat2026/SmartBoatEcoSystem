<?php $__env->startSection('content'); ?>
<style>
  .profile-wrap { display: grid; grid-template-columns: 300px minmax(0,1fr); gap: 16px; padding: 1rem 0; font-size: 13px; }
  .left-col { display: flex; flex-direction: column; gap: 16px; }
  .p-card { background: var(--bs-white); border: 0.5px solid #dee2e6; border-radius: 12px; overflow: hidden; }
  .avatar-card { padding: 24px 20px 20px; text-align: center; }
  .avatar-circle { width: 80px; height: 80px; border-radius: 50%; background: #1a3a6b; display: flex; align-items: center; justify-content: center; margin: 0 auto 14px; font-size: 28px; font-weight: 500; color: #e6f1fb; }
  .stat-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; margin-top: 12px; }
  .stat-box { background: #f8f9fa; border-radius: 8px; padding: 10px 12px; }
  .stat-label { font-size: 11px; color: #6c757d; margin: 0 0 3px; }
  .stat-val { font-size: 15px; font-weight: 600; margin: 0; }
  .doc-card { padding: 16px 20px; }
  .doc-title { font-size: 11px; font-weight: 600; color: #6c757d; margin: 0 0 12px; text-transform: uppercase; letter-spacing: .06em; }
  .qr-wrap { margin-top: 12px; border-radius: 8px; border: 0.5px solid #dee2e6; background: #f8f9fa; display: flex; align-items: center; justify-content: center; padding: 14px; }
  .qr-label { font-size: 11px; color: #6c757d; text-align: center; margin-top: 6px; }
  .right-card { padding: 22px 26px; }
  .wallet-strip { display: flex; align-items: center; justify-content: space-between; background: #f8f9fa; border-radius: 8px; padding: 12px 16px; margin-bottom: 18px; }
  .txn-table { width: 100%; border-collapse: collapse; font-size: 12px; }
  .txn-table thead th { background: #f8f9fa; color: #6c757d; font-weight: 600; padding: 9px 12px; text-align: left; border-bottom: 1px solid #dee2e6; font-size: 11px; text-transform: uppercase; letter-spacing: .05em; white-space: nowrap; }
  .txn-table tbody td { padding: 10px 12px; border-bottom: 0.5px solid #f0f0f0; vertical-align: middle; }
  .txn-table tbody tr:last-child td { border-bottom: none; }
  .txn-table tbody tr:hover { background: #fafafa; }
  .badge-credit { background: #d1fae5; color: #065f46; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .badge-debit  { background: #fee2e2; color: #991b1b; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .badge-success-status { background: #dbeafe; color: #1e40af; padding: 3px 9px; border-radius: 20px; font-size: 11px; font-weight: 600; }
  .empty-txn { text-align: center; padding: 40px 20px; color: #6c757d; }
  .empty-txn p { font-size: 13px; margin: 0; }
  @media (max-width: 767px) { .profile-wrap { grid-template-columns: 1fr; } }
</style>

<div class="profile-wrap">

  
  <div class="left-col">

    
    <div class="p-card avatar-card">
      <div class="avatar-circle"><?php echo e(strtoupper(substr($member->name, 0, 1))); ?></div>
      <h6 class="fw-bold mb-0"><?php echo e($member->name); ?></h6>
      <p class="text-muted mb-3" style="font-size:12px;"><?php echo e($member->memberID); ?></p>
      <div class="stat-grid">
        <div class="stat-box">
          <p class="stat-label">Smart Points</p>
          <p class="stat-val text-success"><?php echo e(number_format($member->smart_point, 4)); ?></p>
        </div>
        <div class="stat-box">
          <p class="stat-label">Smart Qty</p>
          <p class="stat-val text-primary"><?php echo e($member->smart_quanity ?: '0.0000'); ?></p>
        </div>
        <div class="stat-box" style="grid-column:span 2;">
          <p class="stat-label">Smart Wallet Balance</p>
          <p class="stat-val text-success">₹<?php echo e(number_format($smartWalletBalance ?? 0, 2)); ?></p>
        </div>
      </div>
    </div>



<div class="p-card doc-card">
  <p class="doc-title mt-3">QR Code</p>
  <div class="qr-wrap" onclick="document.getElementById('qrModal').style.display='flex'"
       style="cursor:pointer;position:relative;">
    <img src="https://smartboatecosystem.com/Main/public/admin/assets/images/HindolMukherjeeQRCode.png"
         alt="QR Code"
         style="width:200px;height:200px;object-fit:contain;">
    <span style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.45);color:#fff;
                 font-size:10px;padding:2px 7px;border-radius:20px;">
      🔍 Tap to view
    </span>
  </div>
  <p class="qr-label">Admin QR Code &nbsp;·&nbsp; Tap to zoom</p>
</div>


<div id="qrModal"
     onclick="this.style.display='none'"
     style="display:none;position:fixed;inset:0;z-index:9999;
            background:rgba(0,0,0,0.65);
            align-items:center;justify-content:center;flex-direction:column;
            padding:20px;">

  <div onclick="event.stopPropagation()"
       style="background:#fff;border-radius:16px;padding:28px 24px;
              max-width:340px;width:100%;text-align:center;
              box-shadow:0 20px 60px rgba(0,0,0,0.3);">

    
    <div style="text-align:right;margin-bottom:8px;">
      <span onclick="document.getElementById('qrModal').style.display='none'"
            style="cursor:pointer;font-size:20px;color:#6c757d;line-height:1;">✕</span>
    </div>

    
    <img src="https://smartboatecosystem.com/Main/public/admin/assets/images/HindolMukherjeeQRCode.png"
         alt="QR Code"
         style="width:240px;height:240px;object-fit:contain;border-radius:8px;">

    
    <hr style="margin:16px 0;border-color:#dee2e6;">

    
    <div style="background:#fff8e1;border:1px solid #ffe082;border-radius:10px;padding:14px 16px;">
      <p style="font-size:13px;font-weight:700;color:#b45309;margin:0 0 6px;">
        📢 After Payment
      </p>
      <p style="font-size:13px;color:#374151;margin:0;line-height:1.6;">
        After completing your payment, please contact us at:
      </p>
      <p style="font-size:18px;font-weight:700;color:#1a3a6b;margin:8px 0 0;letter-spacing:.03em;">
        📞 82502 57091
      </p>
      <p style="font-size:11px;color:#6c757d;margin:4px 0 0;">
        to add wallet amount to your account.
      </p>
    </div>

    
    <button onclick="document.getElementById('qrModal').style.display='none'"
            style="margin-top:16px;width:100%;padding:10px;background:#1a3a6b;
                   color:#fff;border:none;border-radius:8px;font-size:13px;
                   font-weight:600;cursor:pointer;">
      Close
    </button>

  </div>
</div>

  </div>

  
  <div class="p-card right-card">

    <h6 class="fw-bold mb-1" style="color:#1a3a6b;">Member Profile</h6>
    <p class="text-muted mb-3" style="font-size:12px;">Personal &amp; account information</p>

    
    <div class="wallet-strip">
      <div>
        <p style="font-size:11px;color:#6c757d;margin:0 0 2px;">Joining Date</p>
        <p style="font-size:14px;font-weight:600;margin:0;"><?php echo e($member->joining_date); ?></p>
      </div>
      <div>
        <p style="font-size:11px;color:#6c757d;margin:0 0 2px;">Referral Code</p>
        <p style="font-size:14px;font-weight:600;margin:0;"><?php echo e($member->referral_code); ?></p>
      </div>
      <div class="text-end">
        <p style="font-size:11px;color:#6c757d;margin:0 0 4px;">Status</p>
        <?php if($member->status == 1): ?>
          <span class="badge bg-success">Active</span>
        <?php elseif($member->status == 2): ?>
          <span class="badge bg-warning text-dark">Pending</span>
        <?php else: ?>
          <span class="badge bg-danger">Blocked</span>
        <?php endif; ?>
      </div>
    </div>

    
    <h6 class="fw-bold mb-1" style="color:#1a3a6b;font-size:13px;">Transaction History</h6>
    <p class="text-muted mb-3" style="font-size:11px;">
      All credit &amp; debit transactions linked to your account (<?php echo e($member->memberID); ?>)
    </p>

    
    <div style="overflow-x:auto;">
      <table class="txn-table">
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
            <td style="color:#6c757d;width:40px;"><?php echo e($i + 1); ?></td>

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
                <span style="background:#f3f4f6;color:#6b7280;padding:3px 9px;border-radius:20px;font-size:11px;font-weight:600;">Pending</span>
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
                <svg width="40" height="40" fill="none" viewBox="0 0 24 24" stroke="#6c757d" style="margin-bottom:10px;opacity:.4;">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M9 17v-6a2 2 0 012-2h2a2 2 0 012 2v6m-6 0h6M3 17h18"/>
                </svg>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/member/profile.blade.php ENDPATH**/ ?>