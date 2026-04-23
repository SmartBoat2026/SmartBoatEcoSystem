<?php $__env->startSection('content'); ?>


<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h4 class="mb-0 fw-bold" style="color:#1a3c5e;">
            Welcome back, <?php echo e($member->name); ?> 👋
        </h4>
        <small class="text-muted">Member ID: <strong><?php echo e($member->memberID); ?></strong>
            &nbsp;|&nbsp; Joined: <?php echo e($member->joining_date); ?>

            &nbsp;|&nbsp; Sponsor: <?php echo e($member->sponser_id); ?>

        </small>
    </div>
</div>


<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card card-green">
            <div class="stat-label">Smart Points</div>
            <div class="stat-value"><?php echo e(number_format($member->smart_point, 4)); ?></div>
            <i class="bi bi-star-fill stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-blue">
            <div class="stat-label">Smart Quantity</div>
            <div class="stat-value"><?php echo e($member->smart_quanity ?: '0.0000'); ?></div>
            <i class="bi bi-box-seam stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-purple">
            <div class="stat-label">Total Purchases</div>
            <div class="stat-value"><?php echo e($purchases->count()); ?></div>
            <i class="bi bi-cart-check stat-icon"></i>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card card-orange">
            <div class="stat-label">Total Spent</div>
            <div class="stat-value">
                ₹<?php echo e(number_format($purchases->sum('amount') ?? 0, 2)); ?>

            </div>
            <i class="bi bi-currency-rupee stat-icon"></i>
        </div>
    </div>
</div>


<div class="card border-0 shadow-sm rounded-3">
    <div class="card-header bg-white border-0 pt-3 pb-0 d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0" style="color:#1a3c5e;"><i class="bi bi-receipt me-2"></i>My Purchases & Invoices</h6>
    </div>
    <div class="card-body p-0">
        <?php if($purchases->isEmpty()): ?>
            <div class="text-center py-5 text-muted">
                <i class="bi bi-inbox" style="font-size:2.5rem;"></i>
                <p class="mt-2">No purchases found.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead style="background:#f8f9fa; font-size:.83rem; text-transform:uppercase; letter-spacing:.5px;">
                        <tr>
                            <th class="px-3">#</th>
                            <th>Invoice No</th>
                            <th>Product</th>
                            <th>Amount</th>
                            <th>Smart Pts</th>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $purchases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="px-3 text-muted"><?php echo e($i + 1); ?></td>
                            <td><code style="font-size:.82rem;"><?php echo e($p->invoice_no ?? 'N/A'); ?></code></td>
                            <td><?php echo e($p->product_name ?? '-'); ?></td>
                            <td class="fw-bold text-success">₹<?php echo e(number_format($p->amount ?? 0, 2)); ?></td>
                            <td><?php echo e($p->smart_point ?? '0.0000'); ?></td>
                            <td style="font-size:.82rem;"><?php echo e($p->purchase_date ?? '-'); ?></td>
                            <td>
                                <span class="badge" style="background:#1db87a; font-size:.75rem;">Active</span>
                            </td>
                            <td>
                                <?php if(!empty($p->invoice_no)): ?>
                                    <a href="<?php echo e(route('member.invoice', $p->invoice_no)); ?>"
                                       class="btn btn-sm btn-outline-primary" style="font-size:.78rem;">
                                        <i class="bi bi-eye me-1"></i>View
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted" style="font-size:.8rem;">—</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('member.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/member/dashboard.blade.php ENDPATH**/ ?>