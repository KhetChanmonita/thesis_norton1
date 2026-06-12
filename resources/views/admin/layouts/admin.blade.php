<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — LS Trucking Admin</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --sidebar-w:   260px;
            --topbar-h:    64px;
            --orange:      #FF6B00;
            --orange-soft: #fff3e8;
            --dark:        #1a1a2e;
            --dark-2:      #16213e;
            --dark-3:      #0f3460;
            --white:       #ffffff;
            --gray:        #64748b;
            --gray-light:  #f1f5f9;
            --border:      #e2e8f0;
            --success:     #10b981;
            --warning:     #f59e0b;
            --danger:      #ef4444;
            --info:        #3b82f6;
            --font:        'Kantumruy Pro','Poppins',sans-serif;
            --font-head:   'Montserrat','Kantumruy Pro',sans-serif;
        }

        body { font-family: var(--font); background: var(--gray-light); color: #334155; display: flex; min-height: 100vh; }

        /* ── SIDEBAR ── */
        .sidebar {
            width: var(--sidebar-w);
            background: var(--dark);
            min-height: 100vh;
            position: fixed; top:0; left:0; z-index:1000;
            display: flex; flex-direction: column;
            transition: width 0.3s ease;
            overflow: hidden;
        }

        .sidebar-brand {
            padding: 20px 20px 16px;
            border-bottom: 1px solid rgba(255,255,255,0.07);
            display: flex; align-items: center; gap: 12px;
        }

        .brand-logo {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--orange), #ff9040);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-head);
            font-size: 0.85rem; font-weight: 800;
            color: #fff; flex-shrink: 0;
        }

        .brand-text { color: #fff; }
        .brand-text .name { font-family: var(--font-head); font-size: 0.92rem; font-weight: 700; }
        .brand-text .sub  { font-size: 0.7rem; color: rgba(255,255,255,0.5); margin-top: 1px; }

        /* Nav */
        .sidebar-nav { flex: 1; padding: 16px 0; overflow-y: auto; }

        .nav-section { padding: 10px 20px 6px; }
        .nav-section-label {
            font-size: 0.65rem; font-weight: 700;
            color: rgba(255,255,255,0.3);
            text-transform: uppercase; letter-spacing: 1.5px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.65);
            text-decoration: none;
            border-radius: 0;
            font-size: 0.87rem; font-weight: 500;
            transition: all 0.22s ease;
            position: relative;
        }

        .nav-item:hover {
            color: #fff;
            background: rgba(255,255,255,0.07);
        }

        .nav-item.active {
            color: #fff;
            background: rgba(255,107,0,0.18);
        }

        .nav-item.active::before {
            content: '';
            position: absolute; left: 0; top: 0;
            width: 3px; height: 100%;
            background: var(--orange);
            border-radius: 0 3px 3px 0;
        }

        .nav-item i { width: 18px; text-align: center; font-size: 0.9rem; flex-shrink: 0; }
        .nav-badge {
            margin-left: auto;
            background: var(--orange);
            color: #fff;
            font-size: 0.65rem; font-weight: 700;
            padding: 2px 7px;
            border-radius: 50px;
        }

        /* Sidebar footer */
        .sidebar-footer {
            padding: 14px 20px;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .sidebar-user {
            display: flex; align-items: center; gap: 10px;
        }

        .user-ava {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, var(--orange), #ff9040);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; color: #fff; font-weight: 700;
            flex-shrink: 0;
        }

        .user-info .uname { font-size: 0.82rem; font-weight: 600; color: #fff; }
        .user-info .urole { font-size: 0.68rem; color: rgba(255,255,255,0.45); }

        /* ── MAIN CONTENT ── */
        .main-wrap {
            margin-left: var(--sidebar-w);
            flex: 1;
            display: flex; flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            height: var(--topbar-h);
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 28px;
            position: sticky; top: 0; z-index: 900;
            gap: 16px;
        }

        .topbar-title {
            font-family: var(--font-head);
            font-size: 1rem; font-weight: 700;
            color: #1e293b;
            flex: 1;
        }

        .topbar-title span { color: #1e293b; }

        .topbar-actions { display: flex; align-items: center; gap: 12px; }

        .topbar-btn {
            width: 38px; height: 38px;
            background: var(--gray-light);
            border: 1px solid var(--border);
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: var(--gray); cursor: pointer;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
        }
        .topbar-btn:hover { background: var(--orange-soft); color: var(--orange); border-color: rgba(255,107,0,0.3); }

        .topbar-user {
            display: flex; align-items: center; gap: 9px;
            padding: 6px 12px 6px 6px;
            background: var(--gray-light);
            border: 1px solid var(--border);
            border-radius: 50px;
            cursor: pointer;
        }

        .topbar-user .ava {
            width: 30px; height: 30px;
            background: linear-gradient(135deg, var(--orange), #ff9040);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; color: #fff; font-weight: 700;
        }

        .topbar-user .uname { font-size: 0.82rem; font-weight: 600; color: #1e293b; }

        /* Page content */
        .page-content { flex: 1; padding: 28px; }

        /* Alert */
        .alert {
            padding: 12px 18px; border-radius: 10px;
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
            font-size: 0.87rem; font-weight: 500;
        }
        .alert-success { background: #d1fae5; color: #065f46; border: 1px solid #6ee7b7; }
        .alert-error   { background: #fee2e2; color: #991b1b; border: 1px solid #fca5a5; }

        /* ── CARDS ── */
        .card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border);
            box-shadow: 0 1px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }

        .card-header {
            padding: 16px 22px;
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
            gap: 12px;
        }

        .card-title {
            font-family: var(--font-head);
            font-size: 0.92rem; font-weight: 700;
            color: #1e293b;
            display: flex; align-items: center; gap: 8px;
        }

        .card-title i { color: var(--orange); font-size: 0.85rem; }

        .card-body { padding: 22px; }

        /* ── STAT CARDS ── */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 18px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--border);
            padding: 22px;
            display: flex; align-items: center; gap: 16px;
            transition: all 0.25s ease;
            text-decoration: none;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.10);
            border-color: rgba(255,107,0,0.25);
        }

        .stat-icon {
            width: 52px; height: 52px;
            border-radius: 14px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
        }

        .stat-icon.orange  { background: #fff3e8; color: var(--orange); }
        .stat-icon.blue    { background: #eff6ff; color: #3b82f6; }
        .stat-icon.green   { background: #ecfdf5; color: #10b981; }
        .stat-icon.purple  { background: #f5f3ff; color: #8b5cf6; }
        .stat-icon.red     { background: #fef2f2; color: #ef4444; }
        .stat-icon.teal    { background: #f0fdfa; color: #14b8a6; }
        .stat-icon.yellow  { background: #fffbeb; color: #f59e0b; }

        .stat-info .val {
            font-family: var(--font-head);
            font-size: 1.7rem; font-weight: 800;
            color: #1e293b; line-height: 1;
            margin-bottom: 4px;
        }

        .stat-info .lbl {
            font-size: 0.78rem; color: var(--gray); font-weight: 500;
            line-height: 1.4;
        }

        /* ── TABLES ── */
        .table-wrap { overflow-x: auto; }

        table {
            width: 100%; border-collapse: collapse;
            font-size: 0.85rem;
        }

        thead tr { background: var(--gray-light); }

        th {
            padding: 11px 16px;
            text-align: left;
            font-size: 0.75rem; font-weight: 700;
            color: var(--gray);
            text-transform: uppercase; letter-spacing: 0.5px;
            white-space: nowrap;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 13px 16px;
            border-bottom: 1px solid #f8fafc;
            color: #334155;
            vertical-align: middle;
        }

        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafcff; }

        /* ── BADGES ── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px;
            border-radius: 50px;
            font-size: 0.72rem; font-weight: 700;
        }

        .badge-pending    { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .badge-confirmed  { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-in_progress{ background: #f0fdfa; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-completed  { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-cancelled  { background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-active     { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-inactive   { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
        .badge-new        { background: #eff6ff; color: #1e40af; border: 1px solid #bfdbfe; }
        .badge-read       { background: #f8fafc; color: #64748b; border: 1px solid #e2e8f0; }
        .badge-replied    { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-available  { background: #ecfdf5; color: #065f46; border: 1px solid #6ee7b7; }
        .badge-busy       { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }
        .badge-maintenance{ background: #fef2f2; color: #991b1b; border: 1px solid #fca5a5; }
        .badge-on_leave   { background: #f5f3ff; color: #5b21b6; border: 1px solid #ddd6fe; }

        /* ── BUTTONS ── */
        .btn {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 9px 18px;
            border-radius: 9px;
            font-family: var(--font);
            font-size: 0.85rem; font-weight: 700;
            cursor: pointer; border: none;
            text-decoration: none;
            transition: all 0.22s ease;
        }

        .btn-orange {
            background: linear-gradient(135deg, var(--orange), #ff9040);
            color: #fff;
            box-shadow: 0 4px 14px rgba(255,107,0,0.28);
        }
        .btn-orange:hover { transform: translateY(-1px); box-shadow: 0 6px 18px rgba(255,107,0,0.38); color: #fff; }

        .btn-ghost {
            background: var(--gray-light);
            color: var(--gray);
            border: 1px solid var(--border);
        }
        .btn-ghost:hover { background: #e2e8f0; color: #1e293b; }

        .btn-danger {
            background: #fef2f2;
            color: #ef4444;
            border: 1px solid #fca5a5;
        }
        .btn-danger:hover { background: #fee2e2; }

        .btn-sm { padding: 6px 12px; font-size: 0.78rem; border-radius: 7px; }

        /* ── FORM ── */
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 18px; }
        .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 18px; }
        .form-full { grid-column: 1/-1; }

        .form-group { display: flex; flex-direction: column; gap: 6px; }

        .form-label {
            font-size: 0.8rem; font-weight: 700;
            color: #475569;
        }

        .form-control {
            width: 100%; padding: 10px 14px;
            border: 1.5px solid var(--border);
            border-radius: 9px;
            font-family: var(--font);
            font-size: 0.87rem; color: #1e293b;
            background: var(--white);
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(255,107,0,0.1);
        }

        select.form-control { cursor: pointer; }
        textarea.form-control { resize: vertical; min-height: 90px; }

        /* ── PAGINATION ── */
        .pagination { display: flex; gap: 6px; align-items: center; justify-content: flex-end; padding-top: 14px; }
        .page-btn {
            min-width: 34px; height: 34px; padding: 0 10px;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--white);
            color: var(--gray);
            font-size: 0.82rem; font-weight: 600;
            cursor: pointer; text-decoration: none;
            display: flex; align-items: center; justify-content: center;
            transition: all 0.2s;
        }
        .page-btn:hover, .page-btn.active {
            background: var(--orange); color: #fff;
            border-color: var(--orange);
        }

        .page-nav-btn {
            display: inline-flex; align-items: center; gap: 7px;
            height: 34px; padding: 0 16px;
            border: 1.5px solid var(--orange);
            border-radius: 50px;
            background: var(--white);
            color: var(--orange);
            font-size: 0.82rem; font-weight: 700;
            font-family: 'Kantumruy Pro', sans-serif;
            cursor: pointer; text-decoration: none;
            transition: all 0.2s;
        }
        .page-nav-btn:hover {
            background: var(--orange); color: #fff;
            box-shadow: 0 4px 12px rgba(255,107,0,0.3);
            transform: translateY(-1px);
        }
        .page-nav-btn.disabled {
            opacity: 0.45; cursor: default;
            border-color: var(--border); color: var(--gray);
        }
        .page-nav-btn.disabled:hover {
            background: var(--white); color: var(--gray);
            box-shadow: none; transform: none;
        }

        /* ── MODAL ── */
        .modal-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.55);
            z-index: 9999;
            align-items: flex-start;
            justify-content: center;
            padding: 60px 16px;
            overflow-y: auto;
        }
        .modal-overlay.open { display: flex; }

        .modal-box {
            background: var(--white);
            border-radius: 16px;
            width: 100%; max-width: 620px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.25);
            overflow: hidden;
            animation: modal-drop 0.28s ease;
            margin-bottom: 20px;
        }

        @keyframes modal-drop {
            from { opacity:0; transform:translateY(-16px); }
            to   { opacity:1; transform:translateY(0); }
        }

        .modal-header {
            background: linear-gradient(135deg, var(--dark), var(--dark-3));
            padding: 18px 22px;
            display: flex; align-items: center; justify-content: space-between;
            color: #fff;
        }

        .modal-header h3 {
            font-family: var(--font-head);
            font-size: 0.97rem; font-weight: 700;
            display: flex; align-items: center; gap: 10px;
        }

        .modal-header h3 i { color: #ff9040; }

        .modal-close {
            background: rgba(255,255,255,0.1);
            border: none; color: #fff;
            width: 32px; height: 32px;
            border-radius: 50%; cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            font-size: 15px; transition: all 0.2s;
        }
        .modal-close:hover { background: rgba(255,255,255,0.2); transform: rotate(90deg); }

        .modal-body { padding: 24px; }

        .modal-footer {
            padding: 16px 22px;
            border-top: 1px solid var(--border);
            display: flex; justify-content: flex-end; gap: 10px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar { width: 0; }
            .main-wrap { margin-left: 0; }
            .stats-grid { grid-template-columns: 1fr 1fr; }
            .form-grid, .form-grid-3 { grid-template-columns: 1fr; }
        }
    </style>
    @stack('styles')
</head>
<body>

{{-- ── SIDEBAR ── --}}
<aside class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <div class="brand-logo">LS</div>
        <div class="brand-text">
            <div class="name">LS Trucking</div>
            <div class="sub">Admin Panel</div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">
            <div class="nav-section-label">ទូទៅ</div>
        </div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-pie"></i> ផ្ទាំងគ្រប់គ្រង
        </a>

        <div class="nav-section" style="margin-top:8px;">
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

        <div class="nav-section" style="margin-top:8px;">
            <div class="nav-section-label">ការដឹកជញ្ជូន</div>
        </div>
        <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-list"></i> ការកក់
            @php $pending = \App\Models\Booking::where('status','pending')->count(); @endphp
            @if($pending > 0)
                <span class="nav-badge">{{ $pending }}</span>
            @endif
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave"></i> ការទូទាត់
        </a>
        <a href="{{ route('admin.history.index') }}" class="nav-item {{ request()->routeIs('admin.history*') ? 'active' : '' }}">
            <i class="fas fa-history"></i> ប្រវត្តិការដឹក
        </a>
        <a href="{{ route('admin.shipping.index') }}" class="nav-item {{ request()->routeIs('admin.shipping*') ? 'active' : '' }}">
            <i class="fas fa-dollar-sign"></i> តម្លៃដឹកជញ្ជូន
        </a>
        <a href="{{ route('admin.reports.index') }}" class="nav-item {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar"></i> របាយការណ៍ចំណាយ
        </a>

        <div class="nav-section" style="margin-top:8px;">
            <div class="nav-section-label">ប្រព័ន្ធ</div>
        </div>
        <a href="{{ route('home') }}" class="nav-item" target="_blank">
            <i class="fas fa-globe"></i> មើលវែបសាយ
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            @if(Auth::user()->profile_picture)
                <img src="{{ asset(Auth::user()->profile_picture) }}" alt="avatar"
                     style="width:36px;height:36px;border-radius:50%;object-fit:cover;flex-shrink:0;">
            @else
                <div class="user-ava">{{ strtoupper(substr(Auth::user()->user_name, 0, 2)) }}</div>
            @endif
            <div class="user-info">
                <div class="uname">{{ Auth::user()->user_name }}</div>
                <div class="urole">អ្នកគ្រប់គ្រង</div>
            </div>
        </div>
    </div>
</aside>

{{-- ── MAIN ── --}}
<div class="main-wrap">
    <header class="topbar">
        <div class="topbar-title">{!! $__env->yieldContent('page-title', '<span>ផ្ទាំង</span>គ្រប់គ្រង') !!}</div>
        <div class="topbar-actions">
            <a href="{{ route('home') }}" class="topbar-btn" title="វែបសាយ" target="_blank">
                <i class="fas fa-external-link-alt"></i>
            </a>
            <a href="{{ route('profile') }}" class="topbar-user" title="កែប្រែគណនី" style="text-decoration:none;">
                @if(Auth::user()->profile_picture)
                    <img src="{{ asset(Auth::user()->profile_picture) }}" alt="avatar"
                         style="width:32px;height:32px;border-radius:50%;object-fit:cover;flex-shrink:0;">
                @else
                    <div class="ava">{{ strtoupper(substr(Auth::user()->user_name, 0, 2)) }}</div>
                @endif
                <span class="uname">{{ Auth::user()->user_name }}</span>
            </a>
            <form id="adminLogoutForm" method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="button" class="topbar-btn" title="ចាកចេញ" onclick="document.getElementById('logoutModal').classList.add('open')">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </form>
        </div>
    </header>

    {{-- Logout confirm modal --}}
    <div class="modal-overlay" id="logoutModal" style="align-items:center;">
        <div class="modal-box" style="max-width:380px;text-align:center;">
            <div class="modal-body" style="padding:32px 24px 20px;">
                <div style="width:64px;height:64px;border-radius:50%;background:#fff3e8;
                            display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                    <i class="fas fa-sign-out-alt" style="font-size:1.6rem;color:#FF6B00;"></i>
                </div>
                <div style="font-family:var(--font-head);font-size:1.05rem;font-weight:800;color:#1e293b;margin-bottom:8px;">
                    ចាកចេញពីគណនី?
                </div>
                <p style="font-size:0.88rem;color:#64748b;margin:0;">
                    តើអ្នកពិតជាចង់ចាកចេញពីគណនីនេះមែនទេ?
                </p>
            </div>
            <div class="modal-footer" style="justify-content:center;">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('logoutModal').classList.remove('open')" style="flex:1;justify-content:center;">
                    <i class="fas fa-times"></i> បោះបង់
                </button>
                <button type="button" class="btn btn-orange" onclick="document.getElementById('adminLogoutForm').submit()" style="flex:1;justify-content:center;">
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
</body>
</html>
 