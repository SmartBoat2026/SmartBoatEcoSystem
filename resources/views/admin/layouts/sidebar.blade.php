<style>
    .submenu {
    display: none;
    padding-left: 20px;
}

.menu-item.open .submenu {
    display: block;
}
</style>
<aside class="sidebar" id="sidebar" aria-label="Sidebar navigation">

    <ul class="menu" role="menu">

        @if(\App\Support\AdminPanelAccess::can('dashboard') || \App\Support\AdminPanelAccess::can('chat'))
        <li class="menu-item" role="none">
            <a href="{{ route('admin.index') }}"
               class="{{ request()->routeIs('admin.index') ? 'active' : '' }}"
               role="menuitem" title="Dashboard">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 13h8V3H3v10zM13 21h8V11h-8v10zM13 3v6h8V3h-8zM3 21h8v-6H3v6z"
                        stroke="currentColor" stroke-width="1.6"/>
                </svg>

                <span class="label">Dashboard</span>
            </a>
        </li>
        @endif

        @if(!session('admin_is_staff'))
        <li class="menu-item" role="none">
            <a href="{{ route('admin.staff.index') }}"
               class="{{ request()->routeIs('admin.staff.*') ? 'active' : '' }}"
               role="menuitem" title="Staff management">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M23 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
                <span class="label">Staff management</span>
            </a>
        </li>
        @endif

        @adminCan('manage_member')
        <li class="menu-item" role="none">
            <a href="{{ route('managereport') }}"
               class="{{ request()->routeIs('managereport') ? 'active' : '' }}"
               role="menuitem" title="Manage Report">

                <svg viewBox="0 0 24 24" fill="none">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.6"/>
                <path d="M23 21v-2a4 4 0 0 0-3-3.87" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <path d="M16 3.13a4 4 0 0 1 0 7.75" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>

                <span class="label">Manage Member</span>
            </a>
        </li>
        @endadminCan

        @adminCan('smart_wallet')
        <li class="menu-item" role="none">
            <a href="{{ route('smartwallet') }}"
               class="{{ request()->routeIs('smartwallet') ? 'active' : '' }}"
               role="menuitem" title="Smart Wallet">

                <svg viewBox="0 0 24 24" fill="none">
                <rect x="2" y="6" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/>
                <path d="M16 13a1 1 0 1 1 2 0 1 1 0 0 1-2 0z" fill="currentColor"/>
                <path d="M2 10h20" stroke="currentColor" stroke-width="1.6"/>
                <path d="M6 4l2-1h8l2 1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>

                <span class="label">Smart Wallet</span>
            </a>
        </li>
        @endadminCan

        @adminCan('smart_wallet_member_requests')
        <li class="menu-item" role="none">
            <a href="{{ route('smartwallet.memberRequest.index') }}"
               class="{{ request()->routeIs('smartwallet.memberRequest.index') ? 'active' : '' }}"
               role="menuitem" title="Smart Wallet Member Requests">

                <svg viewBox="0 0 24 24" fill="none">
                <rect x="2" y="6" width="20" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/>
                <path d="M16 13a1 1 0 1 1 2 0 1 1 0 0 1-2 0z" fill="currentColor"/>
                <path d="M2 10h20" stroke="currentColor" stroke-width="1.6"/>
                <path d="M6 4l2-1h8l2 1" stroke="currentColor" stroke-width="1.4" stroke-linecap="round"/>
                </svg>

                <span class="label">Smart Wallet Member Requests</span>
            </a>
        </li>
        @endadminCan

        @adminCan('member_activation')
        <li class="menu-item" role="none">
            <a href="{{ route('managereport.memberactive') }}"
               class="{{ request()->routeIs('managereport.memberactive') ? 'active' : '' }}"
               role="menuitem" title="Member Activate">

                <svg viewBox="0 0 24 24" fill="none">
                <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                <circle cx="9" cy="7" r="4" stroke="currentColor" stroke-width="1.6"/>
                <polyline points="16 11 18 13 22 9" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>

                <span class="label">Member Activation Requests</span>
            </a>
        </li>
        @endadminCan

        @adminCan('category')
        <li class="menu-item" role="none">
            <a href="{{ route('category') }}"
               class="{{ request()->routeIs('category') ? 'active' : '' }}"
               role="menuitem" title="Manage Category">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 7h5l2 2h11v8a2 2 0 0 1-2 2H3V7z" stroke="currentColor" stroke-width="1.6"/>
                </svg>

                <span class="label">Category</span>
            </a>
        </li>
        @endadminCan

        @adminCan('product')
        <li class="menu-item" role="none">
            <a href="{{ route('product') }}"
               class="{{ request()->routeIs('product') ? 'active' : '' }}"
               role="menuitem" title="Product">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M3 7l9-4 9 4-9 4-9-4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    <path d="M3 7v10l9 4 9-4V7" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    <path d="M12 11v10" stroke="currentColor" stroke-width="1.6"/>
                </svg>

                <span class="label">Product</span>
            </a>
        </li>
        @endadminCan

        @adminCan('product_purchase')
        <li class="menu-item" role="none">
            <a href="{{ route('productpurchase.index') }}"
               class="{{ request()->routeIs('productpurchase.*') ? 'active' : '' }}"
               role="menuitem" title="Product Purchase">

                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/>
                    <line x1="3" y1="6" x2="21" y2="6" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M16 10a4 4 0 0 1-8 0" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>

                <span class="label">Product Purchase</span>
            </a>
        </li>
        @endadminCan

        @adminCan('stp_schedule')
        <li class="menu-item" role="none">
            <a href="{{ route('stpschedule.index') }}"
               class="{{ request()->routeIs('stpschedule.*') ? 'active' : '' }}"
               role="menuitem" title="STP Schedule">

                <svg viewBox="0 0 24 24" fill="none">
                    <rect x="3" y="4" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M16 2v4M8 2v4M3 10h18" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                    <path d="M8 14h2m2 0h4M8 18h4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>

                <span class="label">STP Schedule</span>
            </a>
        </li>
        @endadminCan

        @if(\App\Support\AdminPanelAccess::can('bonus') || \App\Support\AdminPanelAccess::can('bonus_passive'))
        <li class="menu-item has-submenu" role="none">

            <a href="javascript:void(0);" class="menu-link" role="menuitem" title="Bonus">
                <svg viewBox="0 0 24 24" fill="none">
                    <rect x="3" y="8" width="18" height="13" rx="1" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M3 8h18v4H3z" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M12 8v13" stroke="currentColor" stroke-width="1.6"/>
                    <path d="M12 8c0-2 1.5-4 3-4s2 2 0 4H12z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 8c0-2-1.5-4-3-4s-2 2 0 4H12z" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="label">Bonus</span>
                <span class="arrow-icon">&#8250;</span>
            </a>

            <ul class="submenu" role="menu">
                @adminCan('bonus')
                <li role="none">
                    <a href="{{ route('bonus.index') }}" role="menuitem" title="Bonus">Bonus</a>
                </li>
                @endadminCan
                @adminCan('bonus_passive')
                <li role="none">
                    <a href="{{ route('adminpassivebonus') }}" role="menuitem" title="Passive Bonus">
                        Passive Bonus
                    </a>
                </li>
                @endadminCan
            </ul>

        </li>
        @endif

    </ul>

</aside>

<script>
document.querySelectorAll('.has-submenu > .menu-link').forEach(function(menu) {
    menu.addEventListener('click', function() {
        this.parentElement.classList.toggle('open');
    });
});
</script>
