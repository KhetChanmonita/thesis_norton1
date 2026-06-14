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
                               style="position:relative;">
                                <i class="fas fa-receipt" style="margin-right:4px;font-size:0.78rem;"></i>
                                ការកក់
                                <span style="position:absolute;top:-7px;right:-10px;background:#FF6B00;color:#fff;
                                              font-size:0.6rem;font-family:'Montserrat',sans-serif;font-weight:800;
                                              width:16px;height:16px;border-radius:50%;display:flex;
                                              align-items:center;justify-content:center;line-height:1;">
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
                                             style="width:32px;height:32px;border-radius:50%;object-fit:cover;display:block;">
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
                                                 style="width:44px;height:44px;border-radius:50%;object-fit:cover;">
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
                                    <span>Admin Panel</span>
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
                        <form id="headerLogoutForm" method="POST" action="{{ route('logout') }}" style="display:none;">
                            @csrf
                        </form>

                        {{-- Logout confirm modal --}}
                        <div id="logoutModal"
                             style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.55);z-index:999999;
                                    align-items:center;justify-content:center;padding:20px;">
                            <div style="background:#fff;border-radius:20px;width:100%;max-width:380px;
                                        box-shadow:0 20px 60px rgba(0,0,0,0.25);overflow:hidden;text-align:center;">
                                <div style="padding:32px 24px 20px;">
                                    <div style="width:64px;height:64px;border-radius:50%;background:#fff3e8;
                                                display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                                        <i class="fas fa-sign-out-alt" style="font-size:1.6rem;color:#ff6b00;"></i>
                                    </div>
                                    <div style="font-family:'Kantumruy Pro',sans-serif;font-size:1.05rem;font-weight:700;color:#1e293b;margin-bottom:8px;">
                                        ចាកចេញពីគណនី?
                                    </div>
                                    <p style="font-family:'Kantumruy Pro',sans-serif;font-size:0.88rem;color:#64748b;margin:0;">
                                        តើអ្នកពិតជាចង់ចាកចេញពីគណនីនេះមែនទេ?
                                    </p>
                                </div>
                                <div style="display:flex;gap:10px;padding:0 24px 24px;">
                                    <button type="button" id="logoutCancelBtn"
                                            style="flex:1;padding:12px;background:transparent;border:2px solid #e2e8f0;
                                                   border-radius:10px;font-family:'Kantumruy Pro',sans-serif;font-size:0.87rem;
                                                   font-weight:600;color:#64748b;cursor:pointer;">
                                        <i class="fas fa-times" style="margin-right:5px;"></i>បោះបង់
                                    </button>
                                    <button type="button" id="logoutConfirmBtn"
                                            style="flex:1;padding:12px;background:linear-gradient(135deg,#ff6b00,#e55a00);
                                                   border:none;border-radius:10px;font-family:'Kantumruy Pro',sans-serif;
                                                   font-size:0.87rem;font-weight:700;color:#fff;cursor:pointer;
                                                   display:flex;align-items:center;justify-content:center;gap:8px;
                                                   box-shadow:0 4px 14px rgba(255,107,0,0.35);">
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
