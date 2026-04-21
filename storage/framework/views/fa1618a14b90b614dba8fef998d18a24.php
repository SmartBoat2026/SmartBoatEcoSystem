<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Panel — SmartBoatEcosystem</title>
    <link rel="shortcut icon" href="<?php echo e(asset('admin/assets/images/favicon.ico')); ?>">

    
    <link href="<?php echo e(asset('admin/assets/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/user/styles.css')); ?>" rel="stylesheet">

    
    <link href="<?php echo e(asset('admin/assets/css/admin/bootstrap-icons.css')); ?>" rel="stylesheet">

    
    <link href="<?php echo e(asset('admin/assets/css/admin/dataTables.bootstrap5.min.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/assets/css/admin/buttons.dataTables.min.css')); ?>" rel="stylesheet">


    <style>
        *, *::before, *::after { box-sizing: border-box; }

        :root {
            --navy:       #1a3a6b;
            --navy-dark:  #0c2a55;
            --navy-light: #e6f1fb;
            --green:      #2c5f2e;
            --green-dark: #1e4520;
            --green-light:#eaf3de;
            --teal:       #0c447c;
            --sidebar-w:  250px;
            --topbar-h:   58px;
            --font:       'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        /* ── RESET ── */
        body {
            font-family: var(--font);
            background: #f0f4f9;
            margin: 0;
            overflow-x: hidden;
            color: #212529;
        }

        a { text-decoration: none; }

        /* ════════════════════════════════
           TOPBAR
        ════════════════════════════════ */
        .sb-topbar {
            position: fixed;
            top: 0; left: 0; right: 0;
            height: var(--topbar-h);
            background: linear-gradient(135deg, #1a3a6b 0%, #0c2a55 100%);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px 0 16px;
            z-index: 1050;
            box-shadow: 0 2px 12px rgba(0,0,0,.25);
        }

        .sb-topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .sb-toggle {
            width: 36px; height: 36px;
            border: none;
            background: rgba(255,255,255,.12);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #fff;
            font-size: 18px;
            transition: background .15s;
            flex-shrink: 0;
        }
        .sb-toggle:hover { background: rgba(255,255,255,.22); }

        .sb-brand {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sb-brand-logo {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #2c5f2e, #27500a);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 900;
            color: #fff;
            letter-spacing: -1px;
            flex-shrink: 0;
        }
        .sb-brand-name {
            font-size: 17px;
            font-weight: 800;
            color: #fff;
            letter-spacing: -.3px;
            line-height: 1;
        }
        .sb-brand-sub {
            font-size: 9px;
            color: rgba(255,255,255,.55);
            text-transform: uppercase;
            letter-spacing: .18em;
            margin-top: 1px;
        }

        .sb-topbar-right {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .sb-member-badge {
            background: rgba(255,255,255,.12);
            border: 1px solid rgba(255,255,255,.2);
            border-radius: 20px;
            padding: 4px 12px;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            letter-spacing: .04em;
            white-space: nowrap;
        }

        .sb-member-name {
            font-size: 12px;
            color: rgba(255,255,255,.85);
            font-weight: 500;
            white-space: nowrap;
            max-width: 140px;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .sb-logout-btn {
            width: 34px; height: 34px;
            background: rgba(220,53,69,.2);
            border: 1px solid rgba(220,53,69,.35);
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            color: #ff8a8a;
            font-size: 15px;
            transition: background .15s;
        }
        .sb-logout-btn:hover { background: rgba(220,53,69,.4); color: #fff; }

        /* ════════════════════════════════
           SIDEBAR
        ════════════════════════════════ */
        .sb-sidebar {
            position: fixed;
            top: var(--topbar-h);
            left: 0;
            width: var(--sidebar-w);
            height: calc(100vh - var(--topbar-h));
            background: #0d2137;
            overflow-y: auto;
            overflow-x: hidden;
            z-index: 1040;
            transition: transform .28s cubic-bezier(.4,0,.2,1),
                        width .28s cubic-bezier(.4,0,.2,1);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.1) transparent;
        }
        .sb-sidebar::-webkit-scrollbar { width: 4px; }
        .sb-sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,.1); border-radius: 2px; }

        /* Collapsed state (desktop) */
        .sb-sidebar.collapsed {
            width: 62px;
        }
        .sb-sidebar.collapsed .nav-label,
        .sb-sidebar.collapsed .nav-arrow,
        .sb-sidebar.collapsed .sb-section-title,
        .sb-sidebar.collapsed .nav-sub { display: none !important; }
        .sb-sidebar.collapsed .nav-link { justify-content: center; padding: 12px 0; }
        .sb-sidebar.collapsed .nav-link i { margin: 0; font-size: 18px; }
        .sb-sidebar.collapsed .sub-menu { display: none !important; }

        /* Mobile hidden */
        @media (max-width: 768px) {
            .sb-sidebar { transform: translateX(-100%); width: var(--sidebar-w) !important; }
            .sb-sidebar.mobile-open { transform: translateX(0); }
        }

        .sb-section-title {
            font-size: 9px;
            font-weight: 700;
            color: rgba(255,255,255,.3);
            text-transform: uppercase;
            letter-spacing: .18em;
            padding: 16px 18px 6px;
            transition: opacity .2s;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 11px 18px;
            color: rgba(168,192,214,.85);
            font-size: 13px;
            font-weight: 500;
            transition: background .14s, color .14s;
            position: relative;
            white-space: nowrap;
            cursor: pointer;
            border: none;
            background: transparent;
            width: 100%;
        }
        .nav-link i {
            font-size: 15px;
            flex-shrink: 0;
            width: 20px;
            text-align: center;
            transition: color .14s;
        }
        .nav-label { flex: 1; }
        .nav-arrow {
            font-size: 11px;
            transition: transform .2s;
            margin-left: auto;
        }
        .nav-link:hover {
            background: rgba(255,255,255,.07);
            color: #fff;
        }
        .nav-link.active {
            background: rgba(255,255,255,.1);
            color: #fff;
        }
        .nav-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: #4dd69c;
            border-radius: 0 2px 2px 0;
        }
        .nav-link[aria-expanded="true"] .nav-arrow { transform: rotate(180deg); }

        /* Sub-menu */
        .sub-menu {
            background: rgba(0,0,0,.18);
            overflow: hidden;
        }
        .sub-menu .nav-link {
            font-size: 12px;
            padding: 9px 18px 9px 46px;
            color: rgba(168,192,214,.7);
        }
        .sub-menu .nav-link:hover { color: #fff; }
        .sub-menu .nav-link.active {
            color: #4dd69c;
            background: rgba(77,214,156,.06);
        }
        .sub-menu .nav-link.active::before { background: #4dd69c; }

        /* Sidebar tooltip on collapse */
        .sb-sidebar.collapsed .nav-link[data-bs-title]:hover::after {
            content: attr(data-bs-title);
            position: absolute;
            left: 62px;
            top: 50%;
            transform: translateY(-50%);
            background: #1a3a6b;
            color: #fff;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 4px;
            white-space: nowrap;
            z-index: 9999;
            pointer-events: none;
        }

        /* ════════════════════════════════
           MAIN CONTENT
        ════════════════════════════════ */
        .sb-main {
            margin-left: var(--sidebar-w);
            margin-top: var(--topbar-h);
            padding: 24px;
            min-height: calc(100vh - var(--topbar-h));
            transition: margin-left .28s cubic-bezier(.4,0,.2,1);
        }
        .sb-main.sidebar-collapsed { margin-left: 62px; }

        @media (max-width: 768px) {
            .sb-main { margin-left: 0 !important; padding: 16px; }
            .sb-member-name { display: none; }
        }

        /* Overlay for mobile sidebar */
        .sb-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1039;
        }
        .sb-overlay.active { display: block; }

        /* ════════════════════════════════
           ALERTS
        ════════════════════════════════ */
        .alert {
            border-radius: 8px;
            font-size: 13px;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
            border: none;
        }
        .alert-success { background: #d1f0d8; color: #1a5928; }
        .alert-danger  { background: #fde8e8; color: #7a1e1e; }

        /* ════════════════════════════════
           PAGE HEADER
        ════════════════════════════════ */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 22px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .page-title h1 {
            font-size: 20px;
            font-weight: 700;
            color: var(--navy);
            margin: 0;
            line-height: 1.3;
        }
        .page-title p {
            font-size: 12px;
            color: #6c757d;
            margin: 3px 0 0 0;
        }
        .page-actions { display: flex; gap: 8px; flex-wrap: wrap; }

        /* ════════════════════════════════
           BUTTONS
        ════════════════════════════════ */
        .btn-primary-custom {
            background: var(--navy);
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            transition: background .15s;
        }
        .btn-primary-custom:hover { background: var(--navy-dark); color: #fff; }

        /* ════════════════════════════════
           CARDS
        ════════════════════════════════ */
        .card {
            border: 0.5px solid #dee2e6;
            border-radius: 10px;
            background: #fff;
            box-shadow: 0 1px 6px rgba(0,0,0,.05);
        }
        .card-header {
            border-radius: 10px 10px 0 0 !important;
            padding: 13px 18px;
            font-size: 13px;
            font-weight: 600;
        }

        /* ════════════════════════════════
           DATATABLES GLOBAL OVERRIDES
        ════════════════════════════════ */
        .dataTables_wrapper .dataTables_length label,
        .dataTables_wrapper .dataTables_filter label,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate { font-size:12px; color:#495057; }
        .dataTables_wrapper .dataTables_filter input {
            border:1px solid #dee2e6; border-radius:4px; padding:3px 8px;
            font-size:12px; margin-left:6px; }
        .dataTables_wrapper .dataTables_length select {
            border:1px solid #dee2e6; border-radius:4px; padding:2px 6px;
            font-size:12px; margin:0 4px; }
        .dataTables_wrapper .paginate_button { font-size:12px !important; padding:3px 8px !important; }
        .dataTables_wrapper .paginate_button.current,
        .dataTables_wrapper .paginate_button.current:hover {
            background: var(--navy) !important; border-color: var(--navy) !important;
            color:#fff !important; border-radius:4px; }
        .dataTables_wrapper .dt-button {
            font-size:12px !important; padding:4px 12px !important; border-radius:4px !important;
            border:1px solid #dee2e6 !important; background:#fff !important;
            color:#495057 !important; margin-right:4px !important; cursor:pointer; }
        .dataTables_wrapper .dt-button:hover { background:#f0f0f0 !important; color: var(--navy) !important; }
        .dataTables_wrapper .buttons-pdf    { border-color:#dc3545 !important; color:#dc3545 !important; }
        .dataTables_wrapper .buttons-excel  { border-color:#198754 !important; color:#198754 !important; }
        .dataTables_wrapper .buttons-print  { border-color: var(--navy) !important; color: var(--navy) !important; }
        .dataTables_wrapper .buttons-pdf:hover   { background:#dc3545 !important; color:#fff !important; }
        .dataTables_wrapper .buttons-excel:hover { background:#198754 !important; color:#fff !important; }
        .dataTables_wrapper .buttons-print:hover { background: var(--navy) !important; color:#fff !important; }

        /* ════════════════════════════════
           MODAL IMPROVEMENTS
        ════════════════════════════════ */
        .modal-content { border: none; border-radius: 10px; overflow: hidden; }
        .modal-backdrop { z-index: 1045; }
        .modal { z-index: 1055; }

        /* ════════════════════════════════
           APPROVAL POPUP
        ════════════════════════════════ */
        .approval-card {
            background: #fff;
            border-radius: 12px;
            padding: 36px 32px;
            text-align: center;
            max-width: 380px;
            margin: auto;
        }
        .approval-icon {
            font-size: 48px;
            margin-bottom: 12px;
        }

        /* ════════════════════════════════
           RESPONSIVE TABLE
        ════════════════════════════════ */
        .table { font-size: 13px; }
        .table thead th { font-size: 12px; font-weight: 600; white-space: nowrap; }
        .table td { vertical-align: middle; }

        /* ════════════════════════════════
           PRINT
        ════════════════════════════════ */
        @media print {
            .sb-topbar, .sb-sidebar, .sb-overlay,
            .page-header .page-actions,
            .dt-buttons, .dataTables_length,
            .dataTables_filter, .dataTables_info,
            .dataTables_paginate { display: none !important; }
            .sb-main { margin: 0 !important; padding: 0 !important; }
            .card { box-shadow: none !important; border: 1px solid #dee2e6 !important; }
        }

        @media (max-width: 768px) {
            .sb-brand-sub {
                display: none;
            }
            .sb-brand-name {
                display: none;
            }
            .hideinmobile {
                display: none;
            }


        }
    </style>
</head>

<body>


<header class="sb-topbar">
    <div class="sb-topbar-left">
        <button class="sb-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="sb-brand">
            <div class="sb-brand-logo">SB</div>
            <div>
                <div class="sb-brand-name">SBES</div>
                <div class="sb-brand-sub">SmartBoatEcoSystem</div>
            </div>
        </div>
    </div>

    <div class="sb-topbar-right">
        <?php if(session('member_memberID')): ?>
        <span class="sb-member-badge hideinmobile">
            <i class="bi bi-person-badge me-1"></i><?php echo e(session('member_memberID')); ?>

        </span>
        <?php endif; ?>


        <?php if(isset($smartWalletBalance)): ?>
        <span class="sb-member-badge" style="
            background: rgba(44,95,46,.35);
            border-color: rgba(77,214,156,.4);
            color: #4dd69c;">
            <i class="bi bi-wallet2 me-1"></i>
            ₹<?php echo e(number_format($smartWalletBalance, 2)); ?>

        </span>
        <?php endif; ?>


        <span class="sb-member-name"><?php echo e(session('member_name')); ?></span>
        <form action="<?php echo e(route('member.logout')); ?>" method="POST" class="m-0">
            <?php echo csrf_field(); ?>
            <button type="submit" class="sb-logout-btn" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</header>


<div class="sb-overlay" id="sidebarOverlay"></div>


<nav class="sb-sidebar" id="sidebar">

    <div class="sb-section-title">Main</div>

    <a href="<?php echo e(route('member.dashboard')); ?>"
       class="nav-link <?php echo e(request()->routeIs('member.dashboard') ? 'active' : ''); ?>"
       data-bs-title="Dashboard">
        <i class="bi bi-speedometer2"></i>
        <span class="nav-label">Dashboard</span>
    </a>

    <a href="<?php echo e(route('member.profile')); ?>"
       class="nav-link <?php echo e(request()->routeIs('member.profile') ? 'active' : ''); ?>"
       data-bs-title="Profile">
        <i class="bi bi-person-circle"></i>
        <span class="nav-label">Profile</span>
    </a>

    <a href="#" class="nav-link" data-bs-title="Member Associate">
        <i class="bi bi-people-fill"></i>
        <span class="nav-label">Member Associate</span>
    </a>

    <div class="sb-section-title">Purchases</div>

    
    <a href="#purchaselistDropdown"
       class="nav-link <?php echo e(request()->routeIs('member.productpurchase*') ? 'active' : ''); ?>"
       data-bs-toggle="collapse"
       aria-expanded="<?php echo e(request()->routeIs('member.productpurchase*') ? 'true' : 'false'); ?>"
       data-bs-title="Purchase List">
        <i class="bi bi-bag-fill"></i>
        <span class="nav-label">Purchase List</span>
        <i class="bi bi-chevron-down nav-arrow"></i>
    </a>
    <div class="collapse sub-menu <?php echo e(request()->routeIs('member.productpurchase*') ? 'show' : ''); ?>"
         id="purchaselistDropdown">
        <a href="<?php echo e(route('member.productpurchase.purchaseList', 'self')); ?>"
           class="nav-link <?php echo e(request()->is('*self*') ? 'active' : ''); ?>">
            <i class="bi bi-person-check-fill"></i>
            <span class="nav-label">Self Purchases</span>
        </a>
        <a href="<?php echo e(route('member.productpurchase.purchaseList', 'other')); ?>"
           class="nav-link <?php echo e(request()->is('*other*') ? 'active' : ''); ?>">
            <i class="bi bi-people-fill"></i>
            <span class="nav-label">Other Purchases</span>
        </a>
    </div>

    <div class="sb-section-title">Finance</div>

    <a href="<?php echo e(route('member.passivebonus')); ?>"
       class="nav-link <?php echo e(request()->routeIs('member.passivebonus') ? 'active' : ''); ?>"
       data-bs-title="Passive Bonus">
        <i class="bi bi-cash-stack"></i>
        <span class="nav-label">Passive Bonus</span>
    </a>

    <a href="<?php echo e(route('member.memberstpschedules.index')); ?>"
       class="nav-link <?php echo e(request()->routeIs('member.memberstpschedules*') ? 'active' : ''); ?>"
       data-bs-title="STP Schedules">
        <i class="bi bi-calendar2-check-fill"></i>
        <span class="nav-label">STP Schedules</span>
    </a>

    <a href="#" class="nav-link" data-bs-title="Smart Wallet">
        <i class="bi bi-wallet2"></i>
        <span class="nav-label">Smart Wallet</span>
    </a>

</nav>


<?php if(isset($status) && $status == 2): ?>
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="approval-card">
                <div class="approval-icon">🥳</div>
                <h4 class="fw-700 mb-2" style="color:#1a3a6b;">Don't Panic!</h4>
                <p class="text-muted mb-3" style="font-size:13px;">Please wait for <strong>Admin Approval</strong>.</p>
                <div style="background:#f0f4f9;border-radius:8px;padding:14px;font-size:13px;line-height:1.8;">
                    <div>OR Contact Admin</div>
                    <strong>+91 8250257091</strong><br>
                    <strong>smartboatofficial@gmail.com</strong>
                </div>
                <p class="mt-3 mb-0" style="font-size:12px;color:#6c757d;">for FastTrack Approval.</p>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>


<main class="sb-main" id="sbMain">

    
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill"></i>
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle-fill"></i>
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php endif; ?>

    <?php echo $__env->yieldContent('content'); ?>

</main>


<script src="<?php echo e(asset('admin/assets/js/jquery-3.6.0.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/bootstrap.bundle.min.js')); ?>"></script>


<script src="<?php echo e(asset('admin/assets/js/admin/jquery.dataTables.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/dataTables.bootstrap5.min.js')); ?>"></script>


<script src="<?php echo e(asset('admin/assets/js/admin/jszip.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/pdfmake.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/vfs_fonts.js')); ?>"></script>


<script src="<?php echo e(asset('admin/assets/js/admin/dataTables.buttons.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/buttons.html5.min.js')); ?>"></script>
<script src="<?php echo e(asset('admin/assets/js/admin/buttons.print.min.js')); ?>"></script>


<script src="<?php echo e(asset('admin/assets/js/admin/sweetalert2@11.js')); ?>"></script>

<script>
(function () {
    var sidebar   = document.getElementById('sidebar');
    var main      = document.getElementById('sbMain');
    var overlay   = document.getElementById('sidebarOverlay');
    var toggleBtn = document.getElementById('sidebarToggle');
    var isMobile  = function () { return window.innerWidth <= 768; };

    function openMobile() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
    }
    function closeMobile() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
    }
    function toggleDesktop() {
        var collapsed = sidebar.classList.toggle('collapsed');
        main.classList.toggle('sidebar-collapsed', collapsed);
        try { localStorage.setItem('sidebarCollapsed', collapsed ? '1' : '0'); } catch(e){}
    }

    // Restore desktop state
    try {
        if (!isMobile() && localStorage.getItem('sidebarCollapsed') === '1') {
            sidebar.classList.add('collapsed');
            main.classList.add('sidebar-collapsed');
        }
    } catch(e) {}

    toggleBtn.addEventListener('click', function () {
        if (isMobile()) {
            sidebar.classList.contains('mobile-open') ? closeMobile() : openMobile();
        } else {
            toggleDesktop();
        }
    });

    overlay.addEventListener('click', closeMobile);

    window.addEventListener('resize', function () {
        if (!isMobile()) {
            closeMobile();
        }
    });

    // Approval modal
    <?php if(isset($status) && $status == 2): ?>
    document.addEventListener('DOMContentLoaded', function () {
        new bootstrap.Modal(document.getElementById('approvalModal'), {
            backdrop: 'static', keyboard: false
        }).show();
    });
    <?php endif; ?>
})();
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>

</body>
</html>
<?php /**PATH E:\xampp\htdocs\16-04-2026\resources\views/member/layouts/app.blade.php ENDPATH**/ ?>