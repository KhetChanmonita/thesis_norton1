<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — LS Trucking Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">LS</div>
        <div class="brand-text">
            <div class="name">LS Trucking Service</div>
        </div>
    </div>

    @php $userRole = Auth::user()->role; @endphp
    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">ទូទៅ</div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> ផ្ទាំងគ្រប់គ្រង
        </a>

        {{-- Driver: only sees their trips --}}
        @if($userRole === 'driver')
        <div class="nav-section"><div class="nav-section-label">ការដឹក</div></div>
        <a href="{{ route('admin.driver.trips') }}" class="nav-item {{ request()->routeIs('admin.driver.trips') ? 'active' : '' }}">
            <i class="fas fa-route"></i> ដំណើររបស់ខ្ញុំ
        </a>
        @endif

        {{-- Admin + Staff: management section --}}
        @if(in_array($userRole, ['admin', 'operation']))
        <div class="nav-section">
            <div class="nav-section-label">ការគ្រប់គ្រង</div>
        </div>
        <a href="{{ route('admin.trucks.index') }}" class="nav-item {{ request()->routeIs('admin.trucks*') ? 'active' : '' }}">
            <i class="fas fa-truck"></i> រថយន្ត
        </a>
        <a href="{{ route('admin.drivers.index') }}" class="nav-item {{ request()->routeIs('admin.drivers*') ? 'active' : '' }}">
            <i class="fas fa-id-badge"></i> អ្នកបើកបរ
        </a>
        <a href="{{ route('admin.schedules.index') }}" class="nav-item {{ request()->routeIs('admin.schedules*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> កាលវិភាគ
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-item {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> អតិថិជន
        </a>
        <a href="{{ route('admin.messages.index') }}" class="nav-item {{ request()->routeIs('admin.messages*') ? 'active' : '' }}">
            <i class="fas fa-envelope"></i> ការទំនាក់ទំនងសារ
            @php $newMsgCount = \App\Models\Contact::where('status','new')->count(); @endphp
            @if($newMsgCount > 0)
                <span class="nav-badge">{{ $newMsgCount }}</span>
            @endif
        </a>
        @endif

        {{-- Admin + Staff: bookings/payments --}}
        @if(in_array($userRole, ['admin', 'operation']))
        <div class="nav-section">
            <div class="nav-section-label">ការដឹកជញ្ជូន</div>
        </div>
        @php
            $transportOpen = request()->routeIs('admin.bookings*')
                          || request()->routeIs('admin.payments*')
                          || request()->routeIs('admin.history*')
                          || request()->routeIs('admin.shipping*')
                          || request()->routeIs('admin.reports.cost-sheet*')
                          || request()->routeIs('admin.reports.invoice*');
            $pending = \App\Models\Booking::where('status','pending')->count();
        @endphp
        <div class="nav-dropdown {{ $transportOpen ? 'open' : '' }}">
            <button type="button" class="nav-dropdown-toggle {{ $transportOpen ? 'active' : '' }}"
                    onclick="toggleNavDropdown(this)">
                <i class="fas fa-truck"></i>
                <span class="nd-label">ការដឹកជញ្ជូន</span>
                @if($pending > 0)<span class="nav-badge">{{ $pending }}</span>@endif
                <i class="fas fa-chevron-down nav-chevron"></i>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.bookings.index') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> ការកក់
                    @if($pending > 0)<span class="nav-badge" style="margin-left:auto;">{{ $pending }}</span>@endif
                </a>
                <a href="{{ route('admin.payments.index') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
                    <i class="fas fa-money-bill-wave"></i> ការទូទាត់
                    @php $pendingPayments = \App\Models\Payment::where('verification_status','pending')->count(); @endphp
                    @if($pendingPayments > 0)<span class="nav-badge" style="margin-left:auto;">{{ $pendingPayments }}</span>@endif
                </a>
                <a href="{{ route('admin.history.index') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> ប្រវត្តិការដឹក
                </a>
                <a href="{{ route('admin.reports.cost-sheet') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.cost-sheet*') || request()->routeIs('admin.reports.invoice*') ? 'active' : '' }}">
                    <i class="fas fa-file-invoice"></i> វិក្កយបត្រ
                </a>
                @if($userRole === 'admin')
                <a href="{{ route('admin.shipping.index') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.shipping*') ? 'active' : '' }}">
                    <i class="fas fa-dollar-sign"></i> តម្លៃដឹកជញ្ជូន
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Admin + Accountant: reports --}}
        @if(in_array($userRole, ['admin', 'accountant']))
        @php $reportsOpen = request()->routeIs('admin.reports*'); @endphp
        <div class="nav-section"><div class="nav-section-label">របាយការណ៍</div></div>
        <div class="nav-dropdown {{ $reportsOpen ? 'open' : '' }}">
            <button type="button" class="nav-dropdown-toggle {{ $reportsOpen ? 'active' : '' }}"
                    onclick="toggleNavDropdown(this)">
                <i class="fas fa-file-invoice-dollar"></i>
                <span class="nd-label">របាយការណ៍ទូទៅ</span>
                <i class="fas fa-chevron-down nav-chevron"></i>
            </button>
            <div class="nav-dropdown-menu">
                <a href="{{ route('admin.reports.index') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.index') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> ចំណាយទូទៅ
                </a>
                <a href="{{ route('admin.reports.revenue') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.revenue*') ? 'active' : '' }}">
                    <i class="fas fa-dollar-sign"></i> ចំណូល
                </a>
                <a href="{{ route('admin.reports.profit') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.profit*') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i> ចំណេញ/ខាត
                </a>
                <a href="{{ route('admin.reports.truck-repair') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.truck-repair*') ? 'active' : '' }}">
                    <i class="fas fa-tools"></i> ជួសជុលរថយន្ត
                </a>
                <a href="{{ route('admin.reports.customer') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.customer*') ? 'active' : '' }}">
                    <i class="fas fa-users"></i> អតិថិជន
                </a>
                <a href="{{ route('admin.reports.fuel') }}"
                   class="nav-sub-item {{ request()->routeIs('admin.reports.fuel*') ? 'active' : '' }}">
                    <i class="fas fa-gas-pump"></i> ប្រេងឥន្ធនៈ
                </a>
            </div>
        </div>

        {{-- Accountant: bookings (view+create), payments, history, invoice --}}
        @if($userRole === 'accountant')
        <div class="nav-section"><div class="nav-section-label">ការដឹកជញ្ជូន</div></div>
        @php $accPending = \App\Models\Booking::where('status','pending')->count(); @endphp
        <a href="{{ route('admin.bookings.index') }}"
           class="nav-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> ការកក់
            @if($accPending > 0)<span class="nav-badge">{{ $accPending }}</span>@endif
        </a>
        <a href="{{ route('admin.payments.index') }}"
           class="nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i> ការទូទាត់
        </a>
        <a href="{{ route('admin.history.index') }}"
           class="nav-item {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> ប្រវត្តិការទូទាត់
        </a>
        <a href="{{ route('admin.reports.cost-sheet') }}"
           class="nav-item {{ request()->routeIs('admin.reports.cost-sheet*') || request()->routeIs('admin.reports.invoice*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice"></i> វិក្កយបត្រ
        </a>
        @endif
        @endif

        {{-- Admin only: user management --}}
        @if($userRole === 'admin')
        <div class="nav-section"><div class="nav-section-label">ប្រព័ន្ធ</div></div>
        <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="fas fa-user-cog"></i> គ្រប់គ្រងគណនី
        </a>
        @endif

        <div class="nav-section"><div class="nav-section-label">ផ្សេងៗ</div></div>
        <a href="{{ route('home') }}" class="nav-item" target="_blank">
            <i class="fas fa-globe"></i> មើលវែបសាយ
        </a>
    </nav>
</aside>

{{-- ── MAIN ── --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">{!! $__env->yieldContent('page-title', '<span>ផ្ទាំង</span>គ្រប់គ្រង') !!}</div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="topbar-btn" title="វែបសាយ" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <a href="{{ route('profile') }}" class="topbar-user" title="កែប្រែគណនី">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset(Auth::user()->profile_picture) }}" alt="avatar"
                         width="32" height="32" class="topbar-user-avatar">
                @else
                    <div class="ava">{{ strtoupper(substr(Auth::user()->user_name, 0, 2)) }}</div>
                @endif
                <div>
                    <span class="uname">{{ Auth::user()->user_name }}</span>
                    @php
                        $roleBadgeColor = ['admin'=>'#7c3aed','operation'=>'#2563eb','accountant'=>'#059669','driver'=>'#d97706'][Auth::user()->role] ?? '#64748b';
                    @endphp
                    <span style="display:block;font-size:.65rem;font-weight:700;color:{{ $roleBadgeColor }};text-transform:uppercase;letter-spacing:.05em;">
                        {{ Auth::user()->role }}
                    </span>
                </div>
            </a>
            <form id="adminLogoutForm" method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="button" class="topbar-btn" title="ចាកចេញ" onclick="document.getElementById('logoutModal').classList.add('open')">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- Logout confirm modal --}}
    <div class="modal-overlay confirm-overlay" id="logoutModal">
        <div class="modal-box confirm-modal-box">
            <div class="modal-body confirm-modal-body">
                <div class="confirm-icon-circle confirm-icon-circle-orange">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="confirm-title">
                    ចាកចេញពីគណនី?
                </div>
                <p class="confirm-subtitle">
                    តើអ្នកពិតជាចង់ចាកចេញពីគណនីនេះមែនទេ?
                </p>
            </div>
            <div class="modal-footer confirm-modal-footer">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('logoutModal').classList.remove('open')">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="button" class="btn btn-orange" onclick="document.getElementById('adminLogoutForm').submit()">
                    <i class="fas fa-sign-out-alt"></i> ចាកចេញ
                </button>
            </div>
        </div>
    </div>

    <main class="page-content">
        @if(session('success'))
            <div class="alert alert-success" id="flash-success"><i class="fas fa-check-circle"></i> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error" id="flash-error"><i class="fas fa-exclamation-circle"></i> {{ session('error') }}</div>
        @endif
        <script>
            ['flash-success','flash-error'].forEach(function(id){
                var el = document.getElementById(id);
                if(el) setTimeout(function(){ el.style.transition='opacity .4s'; el.style.opacity='0'; setTimeout(function(){ el.style.display='none'; },400); }, 5000);
            });
        </script>

        @yield('content')
    </main>
</div>

@stack('scripts')

<script>
function toggleNavDropdown(btn) {
    var wrap = btn.closest('.nav-dropdown');
    wrap.classList.toggle('open');
}
</script>

</body>
</html>
 
