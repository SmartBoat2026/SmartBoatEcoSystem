<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Member Panel - SmartBoatEcosystem</title>

    <link rel="shortcut icon" href="{{ asset('admin/assets/images/favicon.ico') }}">
    <link href="{{ asset('admin/assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/css/buttons.dataTables.min.css">

    <style>
        body {
            background: #f4f7fb;
            overflow-x: hidden;
        }

        /* ===== TOPBAR ===== */
        .member-topbar {
            background: linear-gradient(135deg, #1a3c5e, #0f6b4f);
            color: #fff;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        .brand {
            font-weight: bold;
            font-size: 18px;
        }

        .brand span {
            color: #4dd69c;
        }

        .toggle-btn {
            font-size: 22px;
            cursor: pointer;
            display: none;
        }

        /* ===== SIDEBAR ===== */
        .member-sidebar {
            width: 240px;
            height: 100vh;
            position: fixed;
            top: 56px;
            left: 0;
            background: #0d2137;
            transition: 0.3s;
            overflow-y: auto;
            z-index: 998;
        }

        .member-sidebar.active {
            left: -240px;
        }

        .nav-link {
            color: #a8c0d6;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.1);
            color: #fff;
        }

        /* ===== MAIN ===== */
        .member-main {
            margin-left: 240px;
            padding: 25px;
            transition: 0.3s;
        }

        .member-main.full {
            margin-left: 0;
        }

        /* ===== CARD ===== */
        .stat-card {
            border-radius: 15px;
            padding: 20px;
            color: white;
            position: relative;
        }

        .card-green { background: linear-gradient(135deg,#0f6b4f,#1db87a); }
        .card-blue { background: linear-gradient(135deg,#1a3c5e,#2980b9); }
        .card-purple { background: linear-gradient(135deg,#6c3483,#a569bd); }
        .card-orange { background: linear-gradient(135deg,#b9451d,#e67e22); }

        /* ===== MOBILE ===== */
        @media(max-width:768px){
            .toggle-btn {
                display: block;
            }

            .member-sidebar {
                left: -240px;
            }

            .member-sidebar.show {
                left: 0;
            }

            .member-main {
                margin-left: 0;
                padding: 15px;
            }
        }

        body.modal-open {
            pointer-events: none;
        }
        .modal {
            pointer-events: all;
        }

        /* ===== PAGE HEADER ===== */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 10px;
        }
        .page-title h1 {
            font-size: 20px;
            font-weight: 700;
            color: #1a3a6b;
            margin: 0;
        }
        .page-title p {
            font-size: 13px;
            color: #6c757d;
            margin: 2px 0 0 0;
        }
        .btn-primary {
            background: #1a3a6b;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 13px;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
        }
        .btn-primary:hover {
            background: #0c2a55;
            color: #fff;
        }
    </style>
</head>

<body>

<!-- ===== TOPBAR ===== -->
<div class="member-topbar">
    <div class="d-flex align-items-center gap-3">
        <i class="bi bi-list toggle-btn" onclick="toggleSidebar()"></i>
        <div class="brand">Smart<span>Boat</span></div>
    </div>

    <div class="d-flex align-items-center gap-3">
        <span class="badge bg-light text-dark">
            {{ session('member_memberID') }}
        </span>

        <span>{{ session('member_name') }}</span>

        <form action="{{ route('member.logout') }}" method="POST">
            @csrf
            <button class="btn btn-sm btn-light">
                <i class="bi bi-box-arrow-right"></i>
            </button>
        </form>
    </div>
</div>

<!-- ===== VERIFICATION PENDING POPUP ===== -->
@if(isset($status) && $status == 2)
<div class="modal fade" id="approvalModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content text-center p-4">
      <h4>🥳 Don't Panic 💅</h4>
      <p>Please wait for <b>Admin Approval</b>.</p>
      <p>
        OR <br>
        Contact Admin <br>
        <b>+91 8250257091</b><br>
        <b>smartboatofficial@gmail.com</b>
      </p>
      <p>for FastTrack Approval.</p>
    </div>
  </div>
</div>
@endif

<!-- ===== SIDEBAR ===== -->
<div class="member-sidebar" id="sidebar">
    <a href="{{ route('member.dashboard') }}" class="nav-link {{ request()->routeIs('member.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2"></i> Dashboard
    </a>

    <a href="{{ route('member.profile') }}" class="nav-link {{ request()->routeIs('member.profile') ? 'active' : '' }}">
        <i class="bi bi-person-circle"></i> Profile
    </a>

    <a href="#" class="nav-link">
        <i class="bi bi-people-fill"></i> Member Associate
    </a>

    {{-- Purchase List Dropdown --}}
    <a href="#" class="nav-link dropdown-toggle"
        data-bs-toggle="collapse"
        data-bs-target="#purchaselistDropdown"
        aria-expanded="{{ request()->routeIs('productpurchase.*') || request()->routeIs('member.productpurchase.*') ? 'true' : 'false' }}">
        <i class="bi bi-bag-fill"></i> Purchase List
    </a>

    <div class="collapse {{ request()->routeIs('productpurchase.*') || request()->routeIs('member.productpurchase.*') ? 'show' : '' }}"
        id="purchaselistDropdown">
        <ul class="nav flex-column ms-3">
            <li class="nav-item">
                <a href="{{ route('member.productpurchase.purchaseList','self') }}"
                   class="nav-link {{ request()->routeIs('member.productpurchase.purchaseList','self') ? 'active' : '' }}">
                    <i class="bi bi-person-check-fill"></i> Self Purchases
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('member.productpurchase.purchaseList','other') }}"
                   class="nav-link {{ request()->routeIs('member.productpurchase.purchaseList','self') ? 'active' : '' }}">
                    <i class="bi bi-person-check-fill"></i> Other Purchases
                </a>
            </li>
        </ul>
    </div>

    <a href="{{ route('member.passivebonus') }}" class="nav-link {{ request()->routeIs('member.passivebonus') ? 'active' : '' }}">
        <i class="bi bi-cash"></i> Passive Bonus
    </a>

    <a href="{{ route('member.memberstpschedules.index') }}" class="nav-link {{ request()->routeIs('member.memberstpschedules*') ? 'active' : '' }}">
        <i class="bi bi-calendar2-check-fill"></i> STP Schedules
    </a>

    <a href="#" class="nav-link">
        <i class="bi bi-wallet2"></i> Smart Wallet
    </a>
</div>

<!-- ===== MAIN ===== -->
<div class="member-main" id="main">

    @if(session('success'))
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
    @endif

    @yield('content')

</div>

{{-- ===== SCRIPTS ===== --}}
<script src="{{ asset('admin/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('admin/assets/js/bootstrap.bundle.min.js') }}"></script>

{{-- DataTables --}}
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

{{-- DataTables Buttons (for Excel / PDF / Print in productpurchase page) --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/datatables-buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function toggleSidebar(){
        document.getElementById('sidebar').classList.toggle('show');
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        @if(isset($status) && $status == 2)
            var myModal = new bootstrap.Modal(document.getElementById('approvalModal'), {
                backdrop: 'static',
                keyboard: false
            });
            myModal.show();
        @endif
    });
</script>

@stack('scripts')

</body>
</html>
