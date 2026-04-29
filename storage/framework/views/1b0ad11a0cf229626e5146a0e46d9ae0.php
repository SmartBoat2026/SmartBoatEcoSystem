<?php $__env->startSection('content'); ?>

<main class="main" id="main" role="main">

    <div class="page-header">
        <div class="page-title">
            <h1>Manage Member</h1>
            <p>Manage and track all your members in one place</p>
        </div>
        <div class="page-actions">
            <a href="javascript:void(0)" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addModal">
                <i class="bi bi-plus"></i> New Member
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body table-responsive">

            
            <div id="bulkActionBar"
                 style="display:none;background:#fff3cd;border:1px solid #ffc107;border-radius:6px;
                        padding:10px 16px;margin-bottom:12px;align-items:center;gap:12px;">
                <span id="selectedCount" style="font-size:13px;font-weight:600;color:#856404;">0 selected</span>
                <form id="bulkDeleteForm" method="POST" action="<?php echo e(route('managereport.bulkDelete')); ?>" style="display:inline;">
                    <?php echo csrf_field(); ?>
                    <div id="bulkDeleteIds"></div>
                    <button type="submit" class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete selected members? This cannot be undone.')">
                        <i class="bi bi-trash me-1"></i>Delete Selected
                    </button>
                </form>
                <button type="button" id="clearSelection" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-x me-1"></i>Clear Selection
                </button>
            </div>

            <table id="memberTable" class="table table-bordered table-hover align-middle">
                <thead class="table-primary">
                    <tr>
                        <th style="width:40px;text-align:center;">
                            <input type="checkbox" id="selectAll" style="cursor:pointer;width:15px;height:15px;">
                        </th>
                        <th style="width:50px">#</th>
                        <th>Member ID</th>
                        <th>Member Info</th>
                        <th>Sponsor ID</th>
                        <th>Sponsor Name</th>
                        <th>Joining Date</th>
                        <th>Password</th>
                        <th>Txn Password</th>
                        <th>Registration Amount</th>
                        <th>Smart Wallet Balance</th>
                        <th>Total Smart Point</th>
                        <th>Total Smart Qty</th>
                        <th style="width:160px">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $reports; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="row-checkbox" value="<?php echo e($row->member_id); ?>"
                                       style="cursor:pointer;width:15px;height:15px;">
                            </td>
                            <td><?php echo e($key + 1); ?></td>
                            <td><?php echo e($row->memberID); ?></td>

                            <td>
                                <div class="fw-semibold"><?php echo e($row->name); ?></div>
                                <div class="text-muted small"><i class="bi bi-envelope me-1"></i><?php echo e($row->email ?: '—'); ?></div>
                                <div class="text-muted small"><i class="bi bi-phone me-1"></i><?php echo e($row->phone ?: '—'); ?></div>
                            </td>

                            <td><?php echo e($row->sponser_id); ?></td>
                            <td><?php echo e($row->sponser_name_resolved); ?></td>

                            <td>
                                <?php if($row->joining_member_date): ?>
                                    <?php
                                        $date = $row->joining_member_date;
                                        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $date)) {
                                            $parts = explode('/', $date);
                                            $date = $parts[2] . '-' . $parts[1] . '-' . $parts[0];
                                        }
                                    ?>
                                    <?php echo e(date('d/m/Y H:i', strtotime($date))); ?>

                                <?php else: ?>
                                    —
                                <?php endif; ?>
                            </td>

                            
                            <td>
                                <span class="pass-mask" style="cursor:pointer;letter-spacing:2px;"
                                      data-pass="<?php echo e($row->password); ?>"
                                      title="Click to reveal">••••••••</span>
                            </td>

                            
                            <td>
                                <span class="pass-mask" style="cursor:pointer;letter-spacing:2px;"
                                      data-pass="<?php echo e($row->transaction_password); ?>"
                                      title="Click to reveal">••••••••</span>
                            </td>
                            
                            <td><?php echo e($row->amount); ?></td>
                            <td><?php echo e($row->smart_wallet_balance); ?></td>

                            <td><?php echo e($row->total_smartpoint); ?></td>
                            <td><?php echo e($row->total_smartquantity); ?></td>

                            <td>
                                <div class="d-flex gap-1 justify-content-center">
                                    
                                    <button class="btn btn-sm btn-warning fw-semibold px-2"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editModal<?php echo e($row->member_id); ?>"
                                        title="Edit Record">
                                        <i class="bi bi-pencil-fill"></i>
                                    </button>

                                    
                                    <form method="POST" 
                                        action="<?php echo e(route('managereport.access')); ?>" 
                                        style="display:inline;"
                                        target="_blank">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="member_id" value="<?php echo e($row->member_id); ?>">
                                        <button type="submit"
                                                class="btn btn-sm btn-info fw-semibold px-2 text-white"
                                                title="Access Member Dashboard"
                                                onclick="return confirm('Access dashboard as <?php echo e(addslashes($row->name)); ?> (<?php echo e($row->memberID); ?>)?')">
                                            <i class="bi bi-box-arrow-in-right"></i> Access
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        <!-- EDIT MODAL -->
                        <div class="modal fade" id="editModal<?php echo e($row->member_id); ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" action="<?php echo e(route('managereport.update', $row->member_id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Edit Member — <?php echo e($row->memberID); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">

                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Member ID</label>
                                                <input type="text" name="memberID" value="<?php echo e($row->memberID); ?>" class="form-control" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Full Name</label>
                                                <input type="text" name="name" value="<?php echo e($row->name); ?>" class="form-control" required>
                                            </div>

                                            
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Phone</label>
                                                <?php
                                                    /* Split stored phone into country-code + number for edit modal */
                                                    $storedPhone = $row->phone ?? '';
                                                    $editDialCode = '+91';
                                                    $editNumber   = $storedPhone;
                                                    /* If phone already starts with a known dial code, extract it */
                                                    if (preg_match('/^(\+\d{1,4})\s*(.*)$/', $storedPhone, $m)) {
                                                        $editDialCode = $m[1];
                                                        $editNumber   = $m[2];
                                                    }
                                                ?>
                                                <div class="input-group">
                                                    <select name="phone_code_edit_<?php echo e($row->member_id); ?>"
                                                            class="form-select phone-code-select"
                                                            style="max-width:130px;"
                                                            data-target="editPhone_<?php echo e($row->member_id); ?>">
                                                        <option value="+91"  <?php echo e($editDialCode === '+91'  ? 'selected' : ''); ?>>🇮🇳 +91 India</option>
                                                        <option value="+880" <?php echo e($editDialCode === '+880' ? 'selected' : ''); ?>>🇧🇩 +880 BD</option>
                                                        <option value="+1"   <?php echo e($editDialCode === '+1'   ? 'selected' : ''); ?>>🇺🇸 +1 USA</option>
                                                        <option value="+44"  <?php echo e($editDialCode === '+44'  ? 'selected' : ''); ?>>🇬🇧 +44 UK</option>
                                                        <option value="+971" <?php echo e($editDialCode === '+971' ? 'selected' : ''); ?>>🇦🇪 +971 UAE</option>
                                                        <option value="+966" <?php echo e($editDialCode === '+966' ? 'selected' : ''); ?>>🇸🇦 +966 KSA</option>
                                                        <option value="+60"  <?php echo e($editDialCode === '+60'  ? 'selected' : ''); ?>>🇲🇾 +60 MY</option>
                                                        <option value="+65"  <?php echo e($editDialCode === '+65'  ? 'selected' : ''); ?>>🇸🇬 +65 SG</option>
                                                        <option value="+61"  <?php echo e($editDialCode === '+61'  ? 'selected' : ''); ?>>🇦🇺 +61 AU</option>
                                                        <option value="+49"  <?php echo e($editDialCode === '+49'  ? 'selected' : ''); ?>>🇩🇪 +49 DE</option>
                                                        <option value="+33"  <?php echo e($editDialCode === '+33'  ? 'selected' : ''); ?>>🇫🇷 +33 FR</option>
                                                        <option value="+81"  <?php echo e($editDialCode === '+81'  ? 'selected' : ''); ?>>🇯🇵 +81 JP</option>
                                                        <option value="+86"  <?php echo e($editDialCode === '+86'  ? 'selected' : ''); ?>>🇨🇳 +86 CN</option>
                                                        <option value="+7"   <?php echo e($editDialCode === '+7'   ? 'selected' : ''); ?>>🇷🇺 +7 RU</option>
                                                        <option value="+55"  <?php echo e($editDialCode === '+55'  ? 'selected' : ''); ?>>🇧🇷 +55 BR</option>
                                                        <option value="+92"  <?php echo e($editDialCode === '+92'  ? 'selected' : ''); ?>>🇵🇰 +92 PK</option>
                                                        <option value="+94"  <?php echo e($editDialCode === '+94'  ? 'selected' : ''); ?>>🇱🇰 +94 LK</option>
                                                        <option value="+977" <?php echo e($editDialCode === '+977' ? 'selected' : ''); ?>>🇳🇵 +977 NP</option>
                                                        <option value="+95"  <?php echo e($editDialCode === '+95'  ? 'selected' : ''); ?>>🇲🇲 +95 MM</option>
                                                        <option value="+66"  <?php echo e($editDialCode === '+66'  ? 'selected' : ''); ?>>🇹🇭 +66 TH</option>
                                                    </select>
                                                    <input type="tel"
                                                           id="editPhone_<?php echo e($row->member_id); ?>"
                                                           class="form-control phone-number-input"
                                                           placeholder="Phone number"
                                                           value="<?php echo e($editNumber); ?>">
                                                    <input type="hidden"
                                                           name="phone"
                                                           id="editPhoneHidden_<?php echo e($row->member_id); ?>"
                                                           value="<?php echo e($storedPhone); ?>">
                                                </div>
                                            </div>

                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Email</label>
                                                <input type="email" name="email" value="<?php echo e($row->email); ?>" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Password</label>
                                                <input type="text" name="password" value="<?php echo e($row->password); ?>" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Transaction Password</label>
                                                <input type="text" name="transaction_password" value="<?php echo e($row->transaction_password); ?>" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Sponsor ID</label>
                                                <input type="text" name="sponser_id" value="<?php echo e($row->sponser_id); ?>" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Sponsor Name</label>
                                                <input type="text" name="sponser_name" value="<?php echo e($row->sponser_name_resolved); ?>" class="form-control">
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Joining Date</label>
                                                <input type="date" name="joining_date" value="<?php echo e($row->joining_date); ?>" class="form-control" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label fw-semibold">Smart Point</label>
                                                <input type="number" name="smart_point" value="<?php echo e($row->smart_point); ?>" class="form-control">
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-success">Update Member</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="12" class="text-center text-muted py-4">No Data Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

        </div>
    </div>

    <!-- ADD MODAL -->
    <div class="modal fade" id="addModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <form method="POST" action="<?php echo e(route('managereport.store')); ?>" id="addMemberForm">
                <?php echo csrf_field(); ?>
                <div class="modal-content">

                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="bi bi-person-plus me-2"></i>Add New Member
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Sponsor / Admin ID <span class="text-danger">*</span>
                            </label>

                            <input type="text"
                                   id="sponsorSearch"
                                   class="form-control mb-1"
                                   placeholder="Search by ID, Name, Email or Phone…"
                                   autocomplete="off">

                            <input type="hidden" name="sponser_id"   id="selectedSponsorId"   value="M000001">
                            <input type="hidden" name="sponser_name" id="selectedSponsorName" value="">

                            <div id="sponsorResults"
                                 class="border rounded shadow-sm bg-white"
                                 style="display:none; max-height:200px; overflow-y:auto; position:relative; z-index:9999;">
                            </div>

                            <div id="selectedSponsorBadge" class="mt-1" style="display:none;">
                                <span class="badge bg-success fs-6 py-2 px-3" id="selectedSponsorLabel"></span>
                                <button type="button"
                                        class="btn btn-sm btn-outline-secondary ms-1"
                                        id="clearSponsor">✕ Clear</button>
                            </div>
                            <div class="form-text text-muted">
                                Default: <code>M000001</code> if left blank.
                            </div>
                        </div>

                        
                        <div class="mb-2">
                            <label class="form-label fw-semibold">
                                Full Name <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                        </div>

                        
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Phone</label>
                            <div class="input-group">
                                <select id="addPhoneCode"
                                        class="form-select"
                                        style="max-width:130px;">
                                    <option value="+91"  selected>🇮🇳 +91 India</option>
                                    <option value="+880">🇧🇩 +880 BD</option>
                                    <option value="+1">🇺🇸 +1 USA</option>
                                    <option value="+44">🇬🇧 +44 UK</option>
                                    <option value="+971">🇦🇪 +971 UAE</option>
                                    <option value="+966">🇸🇦 +966 KSA</option>
                                    <option value="+60">🇲🇾 +60 MY</option>
                                    <option value="+65">🇸🇬 +65 SG</option>
                                    <option value="+61">🇦🇺 +61 AU</option>
                                    <option value="+49">🇩🇪 +49 DE</option>
                                    <option value="+33">🇫🇷 +33 FR</option>
                                    <option value="+81">🇯🇵 +81 JP</option>
                                    <option value="+86">🇨🇳 +86 CN</option>
                                    <option value="+7">🇷🇺 +7 RU</option>
                                    <option value="+55">🇧🇷 +55 BR</option>
                                    <option value="+92">🇵🇰 +92 PK</option>
                                    <option value="+94">🇱🇰 +94 LK</option>
                                    <option value="+977">🇳🇵 +977 NP</option>
                                    <option value="+95">🇲🇲 +95 MM</option>
                                    <option value="+66">🇹🇭 +66 TH</option>
                                </select>
                                <input type="tel"
                                       id="addPhoneNumber"
                                       class="form-control"
                                       placeholder="Enter Phone Number">
                                
                                <input type="hidden" name="phone" id="addPhoneHidden">
                            </div>
                        </div>

                        
                        <div class="mb-2">
                            <label class="form-label fw-semibold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Email">
                        </div>

                        
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Age Group</label>
                                <select name="age" class="form-select">
                                    <option value="" selected disabled>Select Age</option>
                                    <option value="Below 18">Below 18</option>
                                    <option value="18-25">18 – 25</option>
                                    <option value="26-35">26 – 35</option>
                                    <option value="36-50">36 – 50</option>
                                    <option value="Above 50">Above 50</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="" selected disabled>Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                        </div>

                        
                        <div class="alert alert-info py-2 small mb-3">
                            <i class="bi bi-info-circle me-1"></i>
                            <strong>Member ID</strong> and <strong>Password</strong>
                            will be auto-generated on save.
                        </div>

                        
                        <div class="card border-warning mb-2">
                            <div class="card-body py-2 px-3">
                                <h6 class="card-title text-warning mb-2">
                                    <i class="bi bi-shield-check me-1"></i>Terms &amp; Conditions
                                </h6>
                                <div style="max-height:120px; overflow-y:auto;
                                            font-size:13px; color:#555; line-height:1.6;"
                                     class="mb-2 border rounded p-2 bg-light">
                                    <p class="mb-1"><strong>1. Eligibility:</strong>
                                       Only individuals aged 18+ may register as members.</p>
                                    <p class="mb-1"><strong>2. Accurate Information:</strong>
                                       All provided information must be truthful and current.</p>
                                    <p class="mb-1"><strong>3. Account Security:</strong>
                                       Members are responsible for maintaining password confidentiality.</p>
                                    <p class="mb-1"><strong>4. Platform Rules:</strong>
                                       Members must comply with SmartBoat Ecosystem policies at all times.</p>
                                    <p class="mb-0"><strong>5. Data Privacy:</strong>
                                       Personal data is handled per our Privacy Policy.</p>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="terms"
                                           id="termsCheck"
                                           value="1"
                                           required>
                                    <label class="form-check-label fw-semibold" for="termsCheck">
                                        I have read and agree to the
                                        <span class="text-primary">Terms &amp; Conditions</span>
                                        <span class="text-danger">*</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <?php $__errorArgs = ['terms'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="alert alert-danger py-1 small"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submitMemberBtn" disabled>
                            <i class="bi bi-person-plus me-1"></i> Register Member
                        </button>
                    </div>

                </div>
            </form>
        </div>
    </div>

</main>

<style>
    /* ── DataTables Buttons styling (matching category page) ── */
    #memberTable_wrapper .dt-buttons { margin-bottom: 8px; }
    #memberTable_wrapper .dt-button {
        font-size: 12px !important;
        padding: 4px 12px !important;
        border-radius: 4px !important;
        border: 1px solid #dee2e6 !important;
        background: #fff !important;
        color: #495057 !important;
        margin-right: 4px !important;
        cursor: pointer;
        transition: background .15s;
    }
    #memberTable_wrapper .dt-button:hover { background: #f0f0f0 !important; }
    #memberTable_wrapper .buttons-pdf    { border-color: #dc3545 !important; color: #dc3545 !important; }
    #memberTable_wrapper .buttons-excel  { border-color: #198754 !important; color: #198754 !important; }
    #memberTable_wrapper .buttons-print  { border-color: #0d6efd !important; color: #0d6efd !important; }
    #memberTable_wrapper .buttons-pdf:hover   { background: #dc3545 !important; color: #fff !important; }
    #memberTable_wrapper .buttons-excel:hover { background: #198754 !important; color: #fff !important; }
    #memberTable_wrapper .buttons-print:hover { background: #0d6efd !important; color: #fff !important; }

    /* ── Bulk bar ── */
    #bulkActionBar.show { display: flex !important; }

    /* ── Selected row highlight ── */
    #memberTable tbody tr.row-selected { background: #e8f4fd !important; }

    /* ── Access button hover ── */
    .btn-info.text-white:hover { opacity: 0.85; }

    /* ── Password mask reveal ── */
    .pass-mask { font-family: monospace; font-size: 13px; }
    .pass-mask.revealed { letter-spacing: normal; color: #dc3545; font-weight: 600; }
</style>

<?php $__env->startPush('scripts'); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

<script>
$(document).ready(function () {

    // ── DataTable (matching category page structure) ──
    $('#memberTable').DataTable({
        pageLength: 10,
        ordering:   true,
        searching:  true,
        responsive: true,
        columnDefs: [
            { orderable: false, searchable: false, targets: [0, 13] }
        ],
        buttons: [
            {
                extend: 'excelHtml5',
                text: '<i class="bi bi-file-earmark-excel me-1"></i>Excel',
                className: 'buttons-excel',
                title: 'Member Report',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                className: 'buttons-pdf',
                title: 'Member Report',
                orientation: 'landscape',
                pageSize: 'A4',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
            },
            {
                extend: 'print',
                text: '<i class="bi bi-printer me-1"></i>Print',
                className: 'buttons-print',
                title: 'Member Report',
                exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12] }
            }
        ],
        language: {
            search:     "🔍 Search:",
            lengthMenu: "Show _MENU_ records",
            info:       "Showing _START_ to _END_ of _TOTAL_ members",
            paginate:   { previous: "← Prev", next: "Next →" }
        },
        dom: "<'row mb-2'<'col-sm-4'l><'col-sm-4'B><'col-sm-4 d-flex justify-content-end'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row mt-2'<'col-sm-5'i><'col-sm-7 d-flex justify-content-end'p>>",
    });

    // ════════════════════════════════════════════════════════════
    // BULK DELETE — checkbox logic (matching category page)
    // ════════════════════════════════════════════════════════════
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

    // Select All
    $('#selectAll').on('change', function () {
        const isChecked = $(this).prop('checked');
        $('.row-checkbox').prop('checked', isChecked);
        $('#memberTable tbody tr').toggleClass('row-selected', isChecked);
        updateBulkBar();
    });

    // Individual row checkbox
    $(document).on('change', '.row-checkbox', function () {
        $(this).closest('tr').toggleClass('row-selected', $(this).prop('checked'));
        const total   = $('.row-checkbox').length;
        const checked = $('.row-checkbox:checked').length;
        $('#selectAll').prop('indeterminate', checked > 0 && checked < total);
        $('#selectAll').prop('checked', checked === total);
        updateBulkBar();
    });

    // Clear selection
    $('#clearSelection').on('click', function () {
        $('.row-checkbox').prop('checked', false);
        $('#memberTable tbody tr').removeClass('row-selected');
        $('#selectAll').prop('checked', false).prop('indeterminate', false);
        updateBulkBar();
    });

    // ════════════════════════════════════════════════════════════
    // PASSWORD MASK — click to toggle reveal / hide
    // ════════════════════════════════════════════════════════════
    $(document).on('click', '.pass-mask', function () {
        const $el = $(this);
        if ($el.hasClass('revealed')) {
            $el.text('••••••••').removeClass('revealed');
            $el.attr('title', 'Click to reveal');
        } else {
            $el.text($el.data('pass')).addClass('revealed');
            $el.attr('title', 'Click to hide');
        }
    });

    // ════════════════════════════════════════════════════════════
    // ADD MODAL — combine country code + number into hidden field
    // ════════════════════════════════════════════════════════════
    function syncAddPhone() {
        const code   = $('#addPhoneCode').val();
        const number = $('#addPhoneNumber').val().trim();
        $('#addPhoneHidden').val(number ? code + number : '');
    }
    $('#addPhoneCode, #addPhoneNumber').on('change input', syncAddPhone);

    // ════════════════════════════════════════════════════════════
    // EDIT MODALS — combine country code + number into hidden field
    // ════════════════════════════════════════════════════════════
    $(document).on('change input', '.phone-code-select, .phone-number-input', function () {
        const $group  = $(this).closest('.input-group');
        const code    = $group.find('.phone-code-select').val();
        const number  = $group.find('.phone-number-input').val().trim();
        const target  = $group.find('.phone-code-select').data('target');
        // Hidden field id = editPhoneHidden_<member_id>
        $('#editPhoneHidden_' + target.replace('editPhone_', '')).val(number ? code + number : '');
    });

});
</script>


<?php if(session('new_memberID')): ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {

        var memberName   = <?php echo json_encode(session('new_name'), 15, 512) ?>;
        var memberID     = <?php echo json_encode(session('new_memberID'), 15, 512) ?>;
        var memberPass   = <?php echo json_encode(session('new_pass'), 15, 512) ?>;
        var memberTxn    = <?php echo json_encode(session('new_password'), 15, 512) ?>;
        var memberAge    = <?php echo json_encode(session('new_age'), 15, 512) ?>;
        var memberGender = <?php echo json_encode(session('new_gender'), 15, 512) ?>;

        var html = '<div style="text-align:left; background:#f8f9fa; padding:16px 20px;' +
                   'border-radius:10px; margin-top:10px; font-size:15px; line-height:2.8;">';

        html += '<b style="color:#6c757d;">Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>: <span style="font-weight:600;">' + memberName + '</span><br>';
        html += '<b style="color:#6c757d;">Member ID&nbsp;</b>: <span style="color:#0d6efd; font-weight:700; font-size:18px;">' + memberID + '</span><br>';
        html += '<b style="color:#6c757d;">Password&nbsp;&nbsp;</b>: <span style="color:#fd7e14; font-weight:700; font-size:18px;">' + memberPass + '</span><br>';
        html += '<b style="color:#6c757d;">Txn Pass&nbsp;&nbsp;</b>: <span style="color:#198754; font-weight:700; font-size:16px;">' + memberTxn + '</span>';

        if (memberAge)    html += '<br><b style="color:#6c757d;">Age&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>: <span>' + memberAge + '</span>';
        if (memberGender) html += '<br><b style="color:#6c757d;">Gender&nbsp;&nbsp;&nbsp;</b>: <span>' + memberGender + '</span>';

        html += '</div>';

        Swal.fire({
            icon: 'success',
            title: 'Registration Successful! 🎉',
            html: html,
            confirmButtonText: '✅ OK, Got it!',
            confirmButtonColor: '#0d6efd',
            allowOutsideClick: false,
        });
    });
</script>
<?php endif; ?>


<script>
(function () {
    const searchInput = document.getElementById('sponsorSearch');
    const resultsBox  = document.getElementById('sponsorResults');
    const hiddenId    = document.getElementById('selectedSponsorId');
    const hiddenName  = document.getElementById('selectedSponsorName');
    const badge       = document.getElementById('selectedSponsorBadge');
    const badgeLabel  = document.getElementById('selectedSponsorLabel');
    const clearBtn    = document.getElementById('clearSponsor');
    const termsCheck  = document.getElementById('termsCheck');
    const submitBtn   = document.getElementById('submitMemberBtn');

    // Enable/disable Register button based on Terms checkbox
    termsCheck.addEventListener('change', function () {
        submitBtn.disabled = !this.checked;
    });

    // Live search with 300ms debounce
    let debounceTimer;
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        const q = this.value.trim();

        if (q.length < 2) {
            resultsBox.style.display = 'none';
            resultsBox.innerHTML = '';
            return;
        }

        debounceTimer = setTimeout(() => {
            fetch(`<?php echo e(route('managereport.member-search')); ?>?q=${encodeURIComponent(q)}`)
                .then(r => r.json())
                .then(data => {
                    resultsBox.innerHTML = '';
                    if (!data.length) {
                        resultsBox.innerHTML =
                            '<div class="px-3 py-2 text-muted small">No matches found.</div>';
                        resultsBox.style.display = 'block';
                        return;
                    }
                    data.forEach(member => {
                        const item = document.createElement('div');
                        item.className = 'px-3 py-2 border-bottom sponsor-item';
                        item.style.cursor = 'pointer';
                        item.innerHTML =
                            `<strong>${member.memberID}</strong> — ${member.name}` +
                            `<br><small class="text-muted">` +
                            `${member.email || ''} ${member.phone ? '· ' + member.phone : ''}` +
                            `</small>`;

                        item.addEventListener('mouseenter', () => item.style.background = '#e9f3ff');
                        item.addEventListener('mouseleave', () => item.style.background = '');
                        item.addEventListener('click', () => {
                            hiddenId.value   = member.memberID;
                            hiddenName.value = member.name;
                            badgeLabel.textContent = `✔ ${member.memberID} — ${member.name}`;
                            badge.style.display    = 'inline-block';
                            searchInput.value        = '';
                            resultsBox.style.display = 'none';
                            resultsBox.innerHTML     = '';
                        });
                        resultsBox.appendChild(item);
                    });
                    resultsBox.style.display = 'block';
                })
                .catch(() => {
                    resultsBox.innerHTML =
                        '<div class="px-3 py-2 text-danger small">Search failed. Try again.</div>';
                    resultsBox.style.display = 'block';
                });
        }, 300);
    });

    // Clear sponsor
    clearBtn.addEventListener('click', () => {
        hiddenId.value         = 'M000001';
        hiddenName.value       = '';
        badge.style.display    = 'none';
        badgeLabel.textContent = '';
        searchInput.value      = '';
    });

    // Close dropdown on outside click
    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.style.display = 'none';
        }
    });

    // Reset entire modal on close
    document.getElementById('addModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('addMemberForm').reset();
        hiddenId.value           = 'M000001';
        hiddenName.value         = '';
        badge.style.display      = 'none';
        badgeLabel.textContent   = '';
        resultsBox.style.display = 'none';
        submitBtn.disabled       = true;
        // Reset phone hidden field
        document.getElementById('addPhoneHidden').value = '';
    });
})();
</script>
<?php $__env->stopPush(); ?>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/admin/managereport.blade.php ENDPATH**/ ?>