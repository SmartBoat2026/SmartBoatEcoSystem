 <!-- ========== NAVBAR ========== -->
    <header>
        <nav class="navbar navbar-expand-lg" role="navigation" aria-label="Main navigation">
            <div class="d-flex align-items-center gap-3">
                <button class="navbar-toggler icon-btn d-lg-none" id="hambBtn" aria-label="Open menu" title="Open menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.6" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
                <a href="{{ route('admin.index') }}" class="navbar-brand">
                    <div class="logo" aria-hidden="true">
                        {{-- <img src={{ asset('admin/assets/images/icon-logo.png')}}" alt="logo"> --}}
                    </div>
                    <div class="brand-text fw-bold fs-5 text-dark">
                        SmartBoatEcosystem
                    </div>
                </a>
            </div>
            <!-- Top nav links -->
            <div class="collapse navbar-collapse me-3" id="navbarNav">
                <ul class="navbar-nav ms-auto" id="menuList">
                    <li class="nav-item">
                        <a class="nav-link active" href="" aria-current="page">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path stroke="currentColor" stroke-width="1.6" d="M3 11.5L12 4l9 7.5M9 21V12h6v9"/>
                            </svg> Home
                        </a>
                    </li>
                    {{-- <li class="nav-item">
                        <a class="nav-link" href="project.html">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path stroke="currentColor" stroke-width="1.6" d="M3 7h18M7 10h10M5 14h14M9 18h6"/>
                            </svg> Projects
                        </a>
                    </li> 
                    <li class="nav-item">
                        <a class="nav-link" href="task.html">
                            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path stroke="currentColor" stroke-width="1.6" d="M20 6L9 17l-5-5"/>
                            </svg> Tasks
                        </a>
                    </li> --}}
                </ul>
            </div>

            <!-- Header actions -->
            <div class="d-flex gap-2 ms-auto" role="group" aria-label="Header actions">
                <!-- Collapse toggle -->
                <button class="icon-btn d-none d-lg-grid" id="collapseBtn" aria-pressed="false" title="Collapse sidebar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M9 6l6 6-6 6"/>
                    </svg>
                </button>
                <!-- Theme Toggle -->
                <button class="icon-btn" id="themeToggle" aria-label="Toggle theme" title="Toggle light/dark mode">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" id="themeIcon">
                        <path stroke="currentColor" stroke-width="1.6" d="M12 17a5 5 0 100-10 5 5 0 000 10zM12 3v2M12 19v2M5 12H3M21 12h-2M6.34 6.34l-1.41 1.41M19.07 19.07l-1.41 1.41"/>
                    </svg>
                </button>
                <!-- Search Dropdown -->
            {{-- <div class="dropdown">
                <button
                    class="icon-btn dropdown-toggle"
                    id="searchBtn"
                    data-bs-toggle="dropdown"
                    data-bs-auto-close="outside"
                    aria-expanded="false"
                    aria-label="Search"
                >
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path stroke="currentColor" stroke-width="1.6" stroke-linecap="round" d="M21 21l-4.35-4.35"/>
                    <circle cx="11" cy="11" r="6" stroke="currentColor" stroke-width="1.6" fill="none"/>
                </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-end search-dropdown" id="searchDropdown">
                    <input
                        type="text"
                        class="search-input"
                        placeholder="Search..."
                        id="searchInput"
                    >
                </div>
            </div>
                <!-- Notification Dropdown -->
                <div class="dropdown notify-dropdown">
                    <div
                        class="dropdown-toggle d-flex align-items-center"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        role="button"
                    >
                        <button class="icon-btn" id="notifyBtn" aria-label="Notifications" title="Notifications">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                            <path stroke="currentColor" stroke-width="1.6" d="M15 17h5l-1.4-1.7A7 7 0 0012 6a7 7 0 00-6.6 9.3L4 17h5"/>
                        </svg>
                    </button>
                    </div>

                    <div class="dropdown-menu dropdown-menu-end mt-2">
                    <div id="Notification" class="h-380 scroll-y p-3 custom-scrollbar">
                        <ul class="timeline p-0">
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        DR
                                        <!-- <img alt="image" width="50" src="{{ asset('admin/assets/images/profile.png')}}"> -->
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Dr Smith uploaded a new report</h6>
                                        <small class="d-block">10 Dec 2023 - 08:15 AM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-info">
                                        AP
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">New Appointment Scheduled</h6>
                                        <small class="d-block">10 Dec 2023 - 09:45 AM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-success">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Patient checked in at reception</h6>
                                        <small class="d-block">10 Dec 2023 - 10:20 AM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2">
                                        AS
                                        <!-- <img alt="image" width="50" src="{{ asset('admin/assets/images/profile.png')}}"> -->
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Dr Alice shared a prescription</h6>
                                        <small class="d-block">10 Dec 2023 - 11:00 AM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-danger">
                                        EM
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Emergency Alert: Critical Patient</h6>
                                        <small class="d-block">10 Dec 2023 - 11:30 AM</small>
                                    </div>
                                </div>
                            </li>
                            <li>
                                <div class="timeline-panel">
                                    <div class="media me-2 media-primary">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                    <div class="media-body">
                                        <h6 class="mb-1">Next Appointment Reminder</h6>
                                        <small class="d-block">10 Dec 2023 - 12:00 PM</small>
                                    </div>
                                </div>
                            </li>
                        </ul>
                        
                    </div>
                    <a class="all-notification" href="#">See all notifications <i class="fas fa-arrow-right"></i></a>
                </div>
                </div> --}}

                <!-- Profile Dropdown -->
                <div class="dropdown profile-dropdown">
                    <div
                        class="dropdown-toggle d-flex align-items-center"
                        data-bs-toggle="dropdown"
                        data-bs-auto-close="outside"
                        aria-expanded="false"
                        role="button"
                    >
                        <div class="profile" id="profileBtn" tabindex="0" title="Account">
                            {{-- First letter of admin name as avatar --}}
                            <div class="avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
                            <div class="name d-none d-md-block">{{ session('admin_name', 'Admin') }}</div>
                        </div>
                    </div>

                    <ul class="dropdown-menu dropdown-menu-end mt-2">
                        <li><h6 class="dropdown-header">Settings</h6></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-user"></i> Profile Settings</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-bell"></i> Notifications</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-solid fa-shield-halved"></i> Privacy &amp; Security</a></li>
                        <li><a class="dropdown-item" href="#"><i class="fa-regular fa-credit-card"></i> Billing</a></li>
                        <!-- In nav-top.blade.php — replace the sign-out <li> -->
                        <li>
                            <div class="sign-out">
                                <form action="{{ route('logout') }}" method="POST" id="logoutForm">
                                    @csrf
                                    <a class="dropdown-item text-danger" href="#"
                                    onclick="event.preventDefault(); document.getElementById('logoutForm').submit();">
                                        <i class="fa-solid fa-right-from-bracket"></i> Sign out
                                    </a>
                                </form>
                            </div>
                        </li>
                    </ul>

                </div>


            </div>
        </nav>
    </header>