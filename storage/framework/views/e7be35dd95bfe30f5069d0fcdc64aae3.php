 <!-- ========== NAVBAR ========== -->
    <header>
        <nav class="navbar navbar-expand-lg" role="navigation" aria-label="Main navigation">
            <div class="d-flex align-items-center gap-3">
                <button class="navbar-toggler icon-btn d-lg-none" id="hambBtn" aria-label="Open menu" title="Open menu">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="1.6" d="M4 7h16M4 12h16M4 17h16"/>
                    </svg>
                </button>
                <a href="<?php echo e(route('admin.index')); ?>" class="navbar-brand">
                    <div class="logo" aria-hidden="true">
                        
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
                            
                            <div class="avatar"><?php echo e(strtoupper(substr(session('admin_name', 'A'), 0, 1))); ?></div>
                            <div class="name d-none d-md-block"><?php echo e(session('admin_name', 'Admin')); ?></div>
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
                                <form action="<?php echo e(route('logout')); ?>" method="POST" id="logoutForm">
                                    <?php echo csrf_field(); ?>
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
    </header><?php /**PATH /home/u642243906/domains/smartboatecosystem.com/public_html/Main/resources/views/admin/layouts/nav-top.blade.php ENDPATH**/ ?>