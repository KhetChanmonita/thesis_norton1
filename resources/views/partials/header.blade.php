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
                    </ul>
                </nav>

                <!-- Auth Buttons -->
                <div class="header-actions">
                    @auth
                        <div class="user-dropdown" id="userDropdownWrap">
                            <button class="user-toggle" id="userToggle" type="button">
                                <div class="user-avatar">
                                    <i class="fas fa-user"></i>
                                </div>
                                <span class="user-name">{{ Auth::user()->user_name }}</span>
                                <i class="fas fa-chevron-down chevron-icon" id="chevronIcon"></i>
                            </button>

                            <div class="ud-menu" id="udMenu">
                                <div class="ud-header">
                                    <div class="ud-avatar">
                                        <i class="fas fa-user-circle"></i>
                                    </div>
                                    <div class="ud-info">
                                        <span class="ud-name">{{ Auth::user()->user_name }}</span>
                                        <span class="ud-role">
                                            {{ Auth::user()->role === 'admin' ? 'អ្នកគ្រប់គ្រង' : 'អ្នកប្រើប្រាស់' }}
                                        </span>
                                    </div>
                                </div>
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
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            <i class="fas fa-sign-in-alt"></i> ចូលគណនី
                        </a>
                        <a href="{{ route('register') }}" class="btn">
                            <i class="fas fa-user-plus"></i> ចុះឈ្មោះ
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</header>

<style>
/* ===== User dropdown — scoped styles, no Bootstrap conflict ===== */
#userDropdownWrap {
    position: relative;
    display: inline-block;
}
#udMenu {
    display: none;
    position: absolute;
    top: calc(100% + 8px);
    right: 0;
    min-width: 220px;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.18);
    border: 1px solid #ebebeb;
    z-index: 99999;
    overflow: hidden;
}
#udMenu.show {
    display: block;
}
.ud-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
}
.ud-avatar {
    font-size: 36px;
    color: #FF6B00;
    line-height: 1;
}
.ud-info {
    display: flex;
    flex-direction: column;
    gap: 3px;
}
.ud-name {
    font-weight: 700;
    font-size: 14px;
    color: #1a1a1a;
    font-family: 'Kantumruy Pro', sans-serif;
}
.ud-role {
    font-size: 12px;
    color: #888;
    font-family: 'Kantumruy Pro', sans-serif;
}
.ud-divider {
    height: 1px;
    background: #f0f0f0;
    margin: 0;
}
.ud-logout-btn {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 13px 16px;
    background: none;
    border: none;
    cursor: pointer;
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: 14px;
    font-weight: 600;
    color: #e53e3e;
    text-align: left;
    transition: background 0.15s;
}
.ud-logout-btn:hover {
    background: #fff5f5;
    color: #c53030;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var toggle     = document.getElementById('userToggle');
    var menu       = document.getElementById('udMenu');
    var chevron    = document.getElementById('chevronIcon');
    var logoutBtn  = document.getElementById('logoutBtn');
    var logoutForm = document.getElementById('headerLogoutForm');

    if (!toggle || !menu) return;

    /* Open / close dropdown */
    toggle.addEventListener('click', function (e) {
        e.preventDefault();
        e.stopPropagation();
        var isOpen = menu.classList.toggle('show');
        if (chevron) chevron.style.transform = isOpen ? 'rotate(180deg)' : 'rotate(0deg)';
    });

    /* Close when clicking anywhere outside */
    document.addEventListener('click', function (e) {
        if (!toggle.contains(e.target) && !menu.contains(e.target)) {
            menu.classList.remove('show');
            if (chevron) chevron.style.transform = 'rotate(0deg)';
        }
    });

    /* Logout */
    if (logoutBtn && logoutForm) {
        logoutBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            logoutForm.submit();
        });
    }
});
</script>
