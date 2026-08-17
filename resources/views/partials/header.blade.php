<header class="header">
    <div class="container">
        <div class="header-content">

            <!-- Logo -->
            <a href="{{ route('home') }}" class="logo">
                <div class="logo-icon">LS</div>
                <div class="logo-text">TRUCKING SERVICE</div>
            </a>

            <!-- Navigation -->
            <div class="nav-container">
                <nav>
                    <ul class="nav-menu">
                        <li>
                            <a href="{{ route('home') }}"
                               class="{{ request()->routeIs('home') ? 'active' : '' }}">
                                ទំព័រដើម
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('services_header') }}"
                               class="{{ request()->routeIs('services_header') ? 'active' : '' }}">
                                សេវាកម្ម
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('trucks_section') }}"
                               class="{{ request()->routeIs('trucks_section') ? 'active' : '' }}">
                                អំពីឡាន
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about_us') }}"
                               class="{{ request()->routeIs('about_us') ? 'active' : '' }}">
                                អំពីយើង
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('price') }}#prices"
                               class="{{ request()->routeIs('price') ? 'active' : '' }}">
                                តម្លៃ
                            </a>
                        </li>
                        @if(session('my_booking_ids'))
                        <li>
                            <a href="{{ route('my.bookings') }}"
                               class="{{ request()->routeIs('my.bookings') ? 'active' : '' }}"
                               class="hdr-nav-booking">
                                <i class="fas fa-receipt hdr-nav-booking-icon"></i>
                                ការកក់
                                <span class="hdr-booking-badge">
                                    {{ count(session('my_booking_ids', [])) }}
                                </span>
                            </a>
                        </li>
                        @endif
                    </ul>
                </nav>

                <!-- Auth Buttons -->
                <div class="header-actions">
                    @auth
                        <div class="user-dropdown" id="userDropdownWrap">
                            <button class="user-toggle" id="userToggle" type="button">
                                <div class="user-avatar">
                                    @if(Auth::user()->profile_picture)
                                        <img src="{{ asset(Auth::user()->profile_picture) }}" alt="avatar"
                                             class="hdr-user-avatar-sm">
                                    @else
                                        <i class="fas fa-user"></i>
                                    @endif
                                </div>
                                <span class="user-name">{{ Auth::user()->user_name }}</span>
                                <i class="fas fa-chevron-down chevron-icon" id="chevronIcon"></i>
                            </button>

                            <div class="ud-menu" id="udMenu">
                                <div class="ud-header">
                                    <div class="ud-avatar">
                                        @if(Auth::user()->profile_picture)
                                            <img src="{{ asset(Auth::user()->profile_picture) }}" alt="avatar"
                                                 class="hdr-user-avatar-md">
                                        @else
                                            <i class="fas fa-user-circle"></i>
                                        @endif
                                    </div>
                                    <div class="ud-info">
                                        <span class="ud-name">{{ Auth::user()->user_name }}</span>
                                        <span class="ud-role">
                                            {{ Auth::user()->role === 'admin' ? 'អ្នកគ្រប់គ្រង' : 'អ្នកប្រើប្រាស់' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="ud-divider"></div>
                                <a href="{{ route('profile') }}" class="ud-menu-link">
                                    <i class="fas fa-user-edit"></i>
                                    <span>កែប្រែគណនី</span>
                                </a>
                                <a href="{{ route('my.bookings') }}" class="ud-menu-link">
                                    <i class="fas fa-receipt"></i>
                                    <span>ការកក់របស់ខ្ញុំ</span>
                                </a>
                                <a href="{{ route('history') }}" class="ud-menu-link {{ request()->routeIs('history') ? 'ud-menu-link-active' : '' }}">
                                    <i class="fas fa-history"></i>
                                    <span>ប្រវត្តិការកក់</span>
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="ud-menu-link">
                                    <i class="fas fa-tachometer-alt"></i>
                                    <span>អ្នកគ្រប់គ្រង</span>
                                </a>
                                @endif
                                <div class="ud-divider"></div>
                                <button type="button" class="ud-logout-btn" id="logoutBtn">
                                    <i class="fas fa-sign-out-alt"></i>
                                    <span>ចាកចេញ</span>
                                </button>
                            </div>
                        </div>

                        {{-- Hidden logout form --}}
                        <form id="headerLogoutForm" method="POST" action="{{ route('logout') }}">
                            @csrf
                        </form>

                        {{-- Logout confirm modal --}}
                        <div id="logoutModal" class="hdr-logout-overlay" style="display:none;">
                            <div class="hdr-logout-box">
                                <div class="hdr-logout-body">
                                    <div class="hdr-logout-icon-wrap">
                                        <i class="fas fa-sign-out-alt hdr-logout-icon"></i>
                                    </div>
                                    <div class="hdr-logout-title">ចាកចេញពីគណនី?</div>
                                    <p class="hdr-logout-text">
                                        តើអ្នកពិតជាចង់ចាកចេញពីគណនីនេះមែនទេ?
                                    </p>
                                </div>
                                <div class="hdr-logout-footer">
                                    <button type="button" id="logoutCancelBtn" class="hdr-logout-cancel">
                                        <i class="fas fa-times hdr-logout-cancel-icon"></i>បោះបង់
                                    </button>
                                    <button type="button" id="logoutConfirmBtn" class="hdr-logout-confirm">
                                        <i class="fas fa-sign-out-alt"></i>ចាកចេញ
                                    </button>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                           class="btn {{ request()->routeIs('register') ? 'btn-outline' : 'btn-filled' }}">
                            <i class="fas fa-sign-in-alt"></i> ចូលគណនី
                        </a>
                        <a href="{{ route('register') }}"
                           class="btn {{ request()->routeIs('register') ? 'btn-filled' : 'btn-outline' }}">
                            <i class="fas fa-user-plus"></i> ចុះឈ្មោះ
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle     = document.getElementById('userToggle');
    var menu       = document.getElementById('udMenu');
    var chevron    = document.getElementById('chevronIcon');
    var logoutBtn  = document.getElementById('logoutBtn');
    var logoutForm = document.getElementById('headerLogoutForm');

    if (!toggle || !menu) return;

    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var isOpen = menu.classList.toggle('show');
        if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    });

    document.addEventListener('click', function (e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    var logoutModal      = document.getElementById('logoutModal');
    var logoutCancelBtn  = document.getElementById('logoutCancelBtn');
    var logoutConfirmBtn = document.getElementById('logoutConfirmBtn');

    if (logoutBtn && logoutForm && logoutModal) {
        logoutBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            menu.classList.remove('show');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            logoutModal.style.display = 'flex';
        });

        logoutCancelBtn.addEventListener('click', function () {
            logoutModal.style.display = 'none';
        });

        logoutConfirmBtn.addEventListener('click', function () {
            logoutForm.submit();
        });

        logoutModal.addEventListener('click', function (e) {
            if (e.target === this) this.style.display = 'none';
        });
    }
});
</script>
