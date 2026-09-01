@extends('admin.layouts.admin')

@section('title', 'Dashboard')
@section('page-title')<span>ផ្ទាំង</span>គ្រប់គ្រង@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin_dashboard.css') }}">
<style>
/* ── Accountant dashboard extras ── */
.acc-stats {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    margin-bottom: 24px;
}
@media (max-width: 900px) { .acc-stats { grid-template-columns: repeat(2,1fr); } }

.acc-stat {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 16px;
    padding: 22px 20px;
    display: flex;
    align-items: center;
    gap: 16px;
    box-shadow: 0 1px 6px rgba(0,0,0,.04);
    transition: box-shadow .2s, transform .2s;
}
.acc-stat:hover { box-shadow: 0 6px 22px rgba(0,0,0,.09); transform: translateY(-2px); }

.acc-stat-icon {
    width: 52px; height: 52px; border-radius: 14px;
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-size: 1.2rem; flex-shrink: 0;
    box-shadow: 0 4px 14px rgba(0,0,0,.18);
}
.acc-stat-num {
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: 1.6rem; font-weight: 800; line-height: 1;
    margin-bottom: 4px; font-variant-numeric: tabular-nums;
}
.acc-stat-lbl { font-size: .78rem; color: #94a3b8; font-weight: 600; }
.acc-stat-sub { font-size: .7rem; color: #cbd5e1; margin-top: 3px; }

/* ── Report quick-links ── */
.acc-reports {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 24px;
}
@media (max-width: 760px) { .acc-reports { grid-template-columns: repeat(2,1fr); } }

.acc-report-link {
    display: flex; align-items: center; gap: 12px;
    background: #fff; border: 1.5px solid #e8eef4;
    border-radius: 14px; padding: 16px 18px;
    text-decoration: none; color: #1e293b;
    font-weight: 600; font-size: .875rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.04);
    transition: border-color .2s, box-shadow .2s, transform .15s;
}
.acc-report-link:hover {
    border-color: #FF6B00;
    box-shadow: 0 4px 18px rgba(255,107,0,.12);
    transform: translateY(-1px);
    color: #FF6B00;
}
.acc-report-icon {
    width: 40px; height: 40px; border-radius: 11px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1rem; flex-shrink: 0;
}

/* ── Month label ── */
.acc-month-label {
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: .7rem; font-weight: 700;
    color: #94a3b8; text-transform: uppercase;
    letter-spacing: .08em; margin-bottom: 14px;
    display: flex; align-items: center; gap: 6px;
}
.acc-month-label::after { content:''; flex:1; height:1px; background:#f1f5f9; }
</style>
@endpush

@section('content')
@php $userRole = Auth::user()->role; @endphp

{{-- ═══════════════════════════════════════════
     ADMIN / STAFF DASHBOARD
═══════════════════════════════════════════ --}}
@if(in_array($userRole, ['admin', 'operation']))

{{-- Driver arrived alerts --}}
@php
    $arrivedBookings = \App\Models\Booking::whereNotNull('driver_arrived_at')
        ->where('status', 'in_progress')
        ->with(['truck.drivers'])
        ->orderByDesc('driver_arrived_at')
        ->get();
@endphp
@if($arrivedBookings->count() > 0)
<div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border:1.5px solid #6ee7b7;border-radius:14px;padding:16px 20px;margin-bottom:20px;display:flex;align-items:center;gap:16px;">
    <div style="width:44px;height:44px;background:#059669;border-radius:12px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
        <i class="fas fa-map-marker-alt" style="color:#fff;font-size:1.1rem;"></i>
    </div>
    <div style="flex:1;">
        <div style="font-weight:800;color:#065f46;font-size:.95rem;margin-bottom:6px;">
            <i class="fas fa-bell"></i> អ្នកបើកបរបានដល់ទីតាំងហើយ! ({{ $arrivedBookings->count() }})
        </div>
        @foreach($arrivedBookings as $ab)
        <div style="font-size:.82rem;color:#047857;margin-bottom:3px;">
            <i class="fas fa-truck" style="margin-right:4px;"></i>
            <strong>{{ $ab->truck?->drivers?->first()?->full_name ?? '—' }}</strong>
            ({{ $ab->truck?->plate_number ?? '—' }})
            &nbsp;·&nbsp; {{ $ab->pickup_location ?? '—' }}
            &nbsp;·&nbsp; <span style="color:#94a3b8;font-size:.75rem;">{{ $ab->driver_arrived_at->diffForHumans() }}</span>
        </div>
        @endforeach
    </div>
    <a href="{{ route('admin.bookings.index') }}" style="background:#059669;color:#fff;text-decoration:none;padding:8px 16px;border-radius:8px;font-size:.8rem;font-weight:700;white-space:nowrap;">
        <i class="fas fa-external-link-alt"></i> មើលការកក់
    </a>
</div>
@endif

{{-- Stats Grid --}}
<div class="stats-grid">
    <a href="{{ route('admin.trucks.index') }}" class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-truck"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['trucks'] }}</div>
            <div class="lbl">រថយន្តសរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.drivers.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-id-badge"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['drivers'] }}</div>
            <div class="lbl">អ្នកបើកបរ</div>
        </div>
    </a>
    <a href="{{ route('admin.customers.index') }}" class="stat-card">
        <div class="stat-icon green"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['customers'] }}</div>
            <div class="lbl">អតិថិជន</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index') }}" class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['bookings'] }}</div>
            <div class="lbl">ការកក់សរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" class="stat-card">
        <div class="stat-icon yellow"><i class="fas fa-clock"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['pending'] }}</div>
            <div class="lbl">កក់រង់ចាំ</div>
        </div>
    </a>
    <a href="{{ route('admin.bookings.index', ['status'=>'completed']) }}" class="stat-card">
        <div class="stat-icon teal"><i class="fas fa-check-circle"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['completed'] }}</div>
            <div class="lbl">ការដឹកបានបញ្ចប់</div>
        </div>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="stat-card">
        <div class="stat-icon red"><i class="fas fa-dollar-sign"></i></div>
        <div class="stat-info">
            <div class="val">${{ number_format($stats['total_revenue'], 0) }}</div>
            <div class="lbl">ចំណូលសរុប</div>
        </div>
    </a>
    <a href="{{ route('admin.schedules.index') }}" class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-calendar-alt"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['schedules'] }}</div>
            <div class="lbl">កាលវិភាគ</div>
        </div>
    </a>
    <a href="{{ route('admin.messages.index') }}" class="stat-card">
        <div class="stat-icon purple"><i class="fas fa-envelope"></i></div>
        <div class="stat-info">
            <div class="val">{{ $stats['new_messages'] }}</div>
            <div class="lbl">សារទាក់ទងថ្មី</div>
        </div>
    </a>
</div>

{{-- Booking status bar --}}
<div class="card dsh-status-card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-chart-bar"></i> ស្ថានភាពការកក់</div>
    </div>
    <div class="card-body dsh-status-body">
        @foreach(['pending'=>['label'=>'រង់ចាំ','color'=>'#f59e0b'],'confirmed'=>['label'=>'បានបញ្ជាក់','color'=>'#3b82f6'],'in_progress'=>['label'=>'កំពុងដឹក','color'=>'#10b981'],'completed'=>['label'=>'បញ្ចប់','color'=>'#059669'],'cancelled'=>['label'=>'បានបោះបង់','color'=>'#ef4444']] as $key=>$info)
        <div class="dsh-status-item">
            <div class="dsh-status-val" style="color:{{ $info['color'] }};">{{ $stats[$key] ?? 0 }}</div>
            <div class="dsh-status-lbl">{{ $info['label'] }}</div>
        </div>
        @endforeach
    </div>
</div>

{{-- Two columns --}}
<div class="dsh-two-col">
    {{-- Recent Bookings --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-clipboard-list"></i> ការកក់ថ្មីៗ</div>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-ghost btn-sm">មើលទាំងអស់</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>អតិថិជន</th>
                        <th>ប្រភេទ</th>
                        <th>ទំហំទំនិញ</th>
                        <th>ស្ថានភាព</th>
                        <th>ថ្ងៃកក់</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentBookings as $b)
                    <tr>
                        <td><strong>{{ $b->formatted_id }}</strong></td>
                        <td>{{ $b->customer?->full_name ?? $b->bookedByUser?->user_name ?? '—' }}</td>
                        <td>{{ $b->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ' }}</td>
                        <td>{{ $b->cargo_weight ? number_format($b->cargo_weight).' kg' : '—' }}</td>
                        <td>
                            <span class="badge badge-{{ $b->status }}">{{ $b->status }}</span>
                            @if($b->driver_arrived_at)
                                <span style="display:inline-flex;align-items:center;gap:3px;background:#dcfce7;color:#059669;font-size:.65rem;font-weight:700;padding:2px 7px;border-radius:10px;margin-left:4px;">
                                    <i class="fas fa-map-marker-alt"></i> ដល់ហើយ
                                </span>
                            @endif
                        </td>
                        <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="dsh-empty-cell">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Payments --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title"><i class="fas fa-money-bill-wave"></i> ការទូទាត់ថ្មីៗ</div>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost btn-sm">មើលទាំងអស់</a>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Booking</th>
                        <th>ចំនួន</th>
                        <th>វិធី</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentPayments as $p)
                    <tr>
                        <td>{{ $p->booking?->formatted_id ?? '#'.$p->booking_id }}<br><small class="dsh-sub-text">{{ $p->booking?->customer?->full_name ?? $p->booking?->bookedByUser?->user_name ?? '' }}</small></td>
                        <td><strong class="dsh-amount">${{ number_format($p->amount, 2) }}</strong></td>
                        <td>{{ $p->payment_method ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="dsh-empty-cell">មិនមានទិន្នន័យ</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Recent Messages --}}
<div class="card dsh-msg-card" id="recent-messages">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-envelope"></i> សារទាក់ទងថ្មីៗ</div>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>ឈ្មោះ</th>
                    <th>ទំនាក់ទំនង</th>
                    <th>ប្រភេទ</th>
                    <th>សារ</th>
                    <th>ស្ថានភាព</th>
                    <th>ពេលវេលា</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $inquiryLabel = ['import'=>'នាំចូល','export'=>'នាំចេញ','price'=>'សុំសម្រង់តម្លៃ','partnership'=>'ភាពជាដៃគូ','other'=>'ផ្សេងៗ'];
                    $statusLabel  = ['new'=>'ថ្មី','read'=>'បានអាន','replied'=>'បានឆ្លើយតប'];
                @endphp
                @forelse($recentMessages as $m)
                <tr>
                    <td><strong>{{ $m->full_name }}</strong>@if($m->company_name)<br><small class="dsh-sub-text">{{ $m->company_name }}</small>@endif</td>
                    <td>{{ $m->phone }}@if($m->email)<br><small class="dsh-sub-text">{{ $m->email }}</small>@endif</td>
                    <td>{{ $inquiryLabel[$m->inquiry_type] ?? $m->inquiry_type }}</td>
                    <td class="dsh-msg-cell">{{ Str::limit($m->message, 80) }}</td>
                    <td><span class="badge badge-{{ $m->status }}">{{ $statusLabel[$m->status] ?? $m->status }}</span></td>
                    <td>{{ $m->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="dsh-empty-cell">មិនមានសារ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endif {{-- end admin/operation --}}


{{-- ═══════════════════════════════════════════
     ACCOUNTANT DASHBOARD
═══════════════════════════════════════════ --}}
@if($userRole === 'accountant')
@php $nowMonth = now()->format('Y-m'); @endphp

{{-- Summary stat cards --}}
<div class="acc-month-label">
    <i class="fas fa-calendar"></i> សង្ខេបខែ {{ $accountantStats['stat_month_label'] }}
</div>
<div class="acc-stats">
    {{-- Revenue this month --}}
    <div class="acc-stat">
        <div class="acc-stat-icon" style="background:linear-gradient(135deg,#059669,#34d399);">
            <i class="fas fa-arrow-trend-up"></i>
        </div>
        <div>
            <div class="acc-stat-num" style="color:#059669;">${{ number_format($accountantStats['revenue_month'], 2) }}</div>
            <div class="acc-stat-lbl">ចំណូលខែនេះ</div>
            <div class="acc-stat-sub">រួមតែបង់ប្រាក់ហើយ</div>
        </div>
    </div>

    {{-- Expense this month --}}
    <div class="acc-stat">
        <div class="acc-stat-icon" style="background:linear-gradient(135deg,#ef4444,#f87171);">
            <i class="fas fa-arrow-trend-down"></i>
        </div>
        <div>
            <div class="acc-stat-num" style="color:#ef4444;">${{ number_format($accountantStats['expense_month'], 2) }}</div>
            <div class="acc-stat-lbl">ចំណាយខែនេះ</div>
            <div class="acc-stat-sub">បូករួម លុយជើងតៃកុង</div>
        </div>
    </div>

    {{-- Net profit this month --}}
    @php $profitMonth = $accountantStats['revenue_month'] - $accountantStats['expense_month']; @endphp
    <div class="acc-stat">
        <div class="acc-stat-icon" style="background:linear-gradient(135deg,{{ $profitMonth >= 0 ? '#0ea5e9,#38bdf8' : '#f59e0b,#fbbf24' }});">
            <i class="fas fa-{{ $profitMonth >= 0 ? 'chart-line' : 'exclamation-triangle' }}"></i>
        </div>
        <div>
            <div class="acc-stat-num" style="color:{{ $profitMonth >= 0 ? '#0369a1' : '#b45309' }};">
                {{ $profitMonth >= 0 ? '+' : '' }}${{ number_format(abs($profitMonth), 2) }}
            </div>
            <div class="acc-stat-lbl">{{ $profitMonth >= 0 ? 'ចំណេញ' : 'ខាត' }}ខែនេះ</div>
            <div class="acc-stat-sub">ចំណូល − ចំណាយ</div>
        </div>
    </div>

    {{-- Payments pending verification --}}
    <div class="acc-stat">
        <a href="{{ route('admin.payments.index') }}" style="display:contents;text-decoration:none;">
        <div class="acc-stat-icon" style="background:linear-gradient(135deg,#f59e0b,#fbbf24);">
            <i class="fas fa-clock"></i>
        </div>
        <div>
            <div class="acc-stat-num" style="color:#b45309;">{{ $accountantStats['payments_pending'] }}</div>
            <div class="acc-stat-lbl">ការទូទាត់រង់ចាំ</div>
            <div class="acc-stat-sub">ត្រូវការផ្ទៀងផ្ទាត់</div>
        </div>
        </a>
    </div>
</div>

{{-- Report quick-links --}}
<div class="acc-month-label" style="margin-bottom:14px;">
    <i class="fas fa-file-invoice-dollar"></i> ទៅកាន់របាយការណ៍
</div>
<div class="acc-reports">
    <a href="{{ route('admin.reports.index', ['month' => $nowMonth]) }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#fff3e8;color:#FF6B00;"><i class="fas fa-chart-bar"></i></div>
        ចំណាយទូទៅ
    </a>
    <a href="{{ route('admin.reports.revenue', ['month' => $nowMonth]) }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#d1fae5;color:#047857;"><i class="fas fa-dollar-sign"></i></div>
        ចំណូលសរុប
    </a>
    <a href="{{ route('admin.reports.profit', ['month' => $nowMonth]) }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#dbeafe;color:#1d4ed8;"><i class="fas fa-chart-line"></i></div>
        ចំណេញ / ខាត
    </a>
    <a href="{{ route('admin.reports.fuel', ['month' => $nowMonth]) }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#fef3c7;color:#b45309;"><i class="fas fa-gas-pump"></i></div>
        ប្រេងឥន្ធនៈ
    </a>
    <a href="{{ route('admin.reports.truck-repair', ['month' => $nowMonth]) }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#fce7f3;color:#9d174d;"><i class="fas fa-tools"></i></div>
        ជួសជុលរថយន្ត
    </a>
    <a href="{{ route('admin.reports.cost-sheet') }}" class="acc-report-link">
        <div class="acc-report-icon" style="background:#ede9fe;color:#6d28d9;"><i class="fas fa-file-invoice"></i></div>
        វិក្កយបត្រ
    </a>
</div>

{{-- Recent Payments --}}
<div class="card">
    <div class="card-header">
        <div class="card-title"><i class="fas fa-money-bill-wave"></i> ការទូទាត់ថ្មីៗ</div>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-ghost btn-sm">មើលទាំងអស់</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Booking</th>
                    <th>អតិថិជន</th>
                    <th>ចំនួន</th>
                    <th>វិធីទូទាត់</th>
                    <th>ស្ថានភាព</th>
                    <th>ថ្ងៃទូទាត់</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPayments as $p)
                <tr>
                    <td><strong>{{ $p->booking?->formatted_id ?? '#'.$p->booking_id }}</strong></td>
                    <td style="color:#64748b;font-size:.82rem;">{{ $p->booking?->customer?->full_name ?? $p->booking?->bookedByUser?->user_name ?? '—' }}</td>
                    <td><strong class="dsh-amount">${{ number_format($p->amount, 2) }}</strong></td>
                    <td style="font-size:.82rem;">{{ $p->payment_method ?? '—' }}</td>
                    <td>
                        @php
                            $vstyle = match($p->verification_status) {
                                'verified' => 'background:#d1fae5;color:#047857',
                                'rejected' => 'background:#fee2e2;color:#b91c1c',
                                default    => 'background:#fef3c7;color:#b45309',
                            };
                            $vlabel = match($p->verification_status) {
                                'verified' => 'បានផ្ទៀងផ្ទាត់',
                                'rejected' => 'បានបដិសេធ',
                                default    => 'រង់ចាំ',
                            };
                        @endphp
                        <span style="padding:2px 9px;border-radius:20px;font-size:.72rem;font-weight:700;{{ $vstyle }}">{{ $vlabel }}</span>
                    </td>
                    <td style="font-size:.78rem;color:#94a3b8;">{{ $p->created_at?->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="dsh-empty-cell">មិនមានទិន្នន័យ</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endif {{-- end accountant --}}


{{-- ═══════════════════════════════════════════
     DRIVER DASHBOARD
═══════════════════════════════════════════ --}}
@if($userRole === 'driver')
<style>
.drv-hero {
    background: linear-gradient(135deg,#FF6B00,#ff9040);
    border-radius: 16px; padding: 24px 28px; color: #fff;
    display: flex; align-items: center; gap: 20px; margin-bottom: 22px;
}
.drv-hero-avatar {
    width: 70px; height: 70px; border-radius: 50%;
    background: rgba(255,255,255,.25);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.9rem; flex-shrink: 0; overflow: hidden;
}
.drv-hero-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
.drv-hero-name  { font-size: 1.3rem; font-weight: 800; line-height: 1.2; }
.drv-hero-sub   { font-size: .82rem; opacity: .88; margin-top: 4px; }
.drv-truck-pill {
    display: inline-flex; align-items: center; gap: 6px;
    background: rgba(255,255,255,.22); border-radius: 20px;
    padding: 4px 13px; font-size: .78rem; font-weight: 700; margin-top: 8px;
}
.drv-stats { display: grid; grid-template-columns: repeat(2,1fr); gap: 14px; margin-bottom: 22px; }
.drv-stat-card {
    background: #fff; border-radius: 14px; padding: 18px 20px;
    border: 1.5px solid #f1f5f9; box-shadow: 0 2px 8px rgba(0,0,0,.05);
    display: flex; align-items: center; gap: 14px;
}
.drv-stat-icon {
    width: 44px; height: 44px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.1rem; flex-shrink: 0;
}
.drv-stat-icon.orange { background:#fff3e8; color:#FF6B00; }
.drv-stat-icon.green  { background:#dcfce7; color:#059669; }
.drv-stat-icon.blue   { background:#dbeafe; color:#2563eb; }
.drv-stat-icon.gray   { background:#f1f5f9; color:#64748b; }
.drv-stat-num  { font-size: 1.6rem; font-weight: 800; color: #1e293b; line-height: 1; }
.drv-stat-lbl  { font-size: .75rem; color: #64748b; margin-top: 3px; }

.drv-upcoming { background:#fff; border-radius:14px; border:1.5px solid #f1f5f9; box-shadow:0 2px 8px rgba(0,0,0,.05); overflow:hidden; }
.drv-upcoming-hd { padding:16px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; justify-content:space-between; }
.drv-upcoming-title { font-size:.92rem; font-weight:700; color:#1e293b; display:flex; align-items:center; gap:8px; }
.drv-trip-row { display:flex; align-items:center; gap:16px; padding:14px 20px; border-bottom:1px solid #f8fafc; }
.drv-trip-row:last-child { border-bottom:none; }
.drv-trip-num { width:36px; height:36px; border-radius:50%; background:#fff3e8; color:#FF6B00; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.85rem; flex-shrink:0; }
.drv-trip-body { flex:1; }
.drv-trip-date { font-weight:700; color:#1e293b; font-size:.88rem; }
.drv-trip-loc  { font-size:.78rem; color:#64748b; margin-top:2px; }
.drv-trip-truck{ font-size:.75rem; color:#FF6B00; font-weight:600; margin-top:2px; }
.drv-today-badge { background:#FF6B00; color:#fff; font-size:.65rem; font-weight:700; padding:2px 8px; border-radius:10px; margin-left:6px; vertical-align:middle; }
.drv-empty { text-align:center; padding:40px 20px; color:#94a3b8; }
</style>

{{-- Hero card --}}
@if($driverStats && $driverStats['driver'])
@php $drv = $driverStats['driver']; @endphp
<div class="drv-hero">
    <div class="drv-hero-avatar">
        @if($drv->driver_picture)
            <img src="{{ asset($drv->driver_picture) }}" alt="{{ $drv->full_name }}">
        @else
            <i class="fas fa-id-badge"></i>
        @endif
    </div>
    <div style="flex:1;">
        <div class="drv-hero-name">សូមស្វាគមន៍, {{ $drv->full_name }}!</div>
        <div class="drv-hero-sub">
            @if($drv->phone)<i class="fas fa-phone" style="margin-right:4px;"></i>{{ $drv->phone }} &nbsp;·&nbsp; @endif
            <i class="fas fa-calendar-alt" style="margin-right:4px;"></i>ចូលធ្វើការ: {{ $drv->hire_date ? \Carbon\Carbon::parse($drv->hire_date)->format('d/m/Y') : '—' }}
        </div>
        @if($drv->truck)
        <div class="drv-truck-pill">
            <i class="fas fa-truck"></i>
            {{ $drv->truck->truck_name }} — {{ $drv->truck->plate_number }}
            @if($drv->truck->capacity_ton) ({{ $drv->truck->capacity_ton }}T) @endif
        </div>
        @endif
    </div>
    <a href="{{ route('admin.driver.trips') }}" class="btn" style="background:rgba(255,255,255,.22);color:#fff;border:1.5px solid rgba(255,255,255,.4);text-decoration:none;white-space:nowrap;">
        <i class="fas fa-route"></i> ដំណើររបស់ខ្ញុំ
    </a>
</div>

{{-- Stats --}}
<div class="drv-stats">
    <div class="drv-stat-card">
        <div class="drv-stat-icon orange"><i class="fas fa-route"></i></div>
        <div>
            <div class="drv-stat-num">{{ $driverStats['total'] }}</div>
            <div class="drv-stat-lbl">ដំណើរសរុប</div>
        </div>
    </div>
    <div class="drv-stat-card">
        <div class="drv-stat-icon blue"><i class="fas fa-calendar-day"></i></div>
        <div>
            <div class="drv-stat-num" style="color:#FF6B00;">{{ $driverStats['today'] }}</div>
            <div class="drv-stat-lbl">ដំណើរថ្ងៃនេះ</div>
        </div>
    </div>
</div>

{{-- Payment / Expense summary --}}
<div class="drv-stats" style="margin-bottom:22px;">
    <div class="drv-stat-card" style="border-color:#fde68a;">
        <div class="drv-stat-icon" style="background:#fef9c3;color:#ca8a04;"><i class="fas fa-gas-pump"></i></div>
        <div>
            <div class="drv-stat-num" style="color:#ca8a04;font-size:1.25rem;">${{ number_format($driverStats['total_fuel'],2) }}</div>
            <div class="drv-stat-lbl">ប្រេងឥន្ធនៈ</div>
        </div>
    </div>
    <div class="drv-stat-card" style="border-color:#d9f99d;">
        <div class="drv-stat-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-wallet"></i></div>
        <div>
            <div class="drv-stat-num" style="color:#16a34a;font-size:1.25rem;">${{ number_format($driverStats['total_allowance'],2) }}</div>
            <div class="drv-stat-lbl">លុយជើងតៃកុង</div>
        </div>
    </div>
    <div class="drv-stat-card" style="grid-column:span 2;">
        <div class="drv-stat-icon" style="background:#ede9fe;color:#7c3aed;"><i class="fas fa-receipt"></i></div>
        <div>
            <div class="drv-stat-num" style="color:#7c3aed;">{{ $driverStats['expense_count'] }}</div>
            <div class="drv-stat-lbl">កំណត់ត្រាចំណាយ</div>
        </div>
        <a href="{{ route('admin.driver.trips') }}" style="margin-left:auto;font-size:.8rem;color:#7c3aed;text-decoration:none;font-weight:600;white-space:nowrap;">
            មើលទាំងអស់ →
        </a>
    </div>
</div>

{{-- Upcoming trips preview --}}
<div class="drv-upcoming">
    <div class="drv-upcoming-hd">
        <div class="drv-upcoming-title"><i class="fas fa-calendar-check" style="color:#FF6B00;"></i> ដំណើរខាងមុខ</div>
        <a href="{{ route('admin.driver.trips') }}" style="font-size:.8rem;color:#FF6B00;text-decoration:none;font-weight:600;">មើលទាំងអស់ →</a>
    </div>
    @forelse($driverStats['nextBookings'] as $i => $b)
    @php $isToday = $b->pick_up_date && $b->pick_up_date->toDateString() === now()->toDateString(); @endphp
    <div class="drv-trip-row">
        <div class="drv-trip-num">{{ $i + 1 }}</div>
        <div class="drv-trip-body">
            <div class="drv-trip-date">
                <i class="fas fa-calendar-alt" style="color:#FF6B00;font-size:.8rem;"></i>
                {{ $b->pick_up_date ? \Carbon\Carbon::parse($b->pick_up_date)->format('d/m/Y') : '—' }}
                @if($isToday)<span class="drv-today-badge">ថ្ងៃនេះ</span>@endif
            </div>
            @if($b->pickup_location || $b->dropoff_location)
            <div class="drv-trip-loc">
                <i class="fas fa-circle" style="font-size:.45rem;color:#FF6B00;"></i>
                {{ $b->pickup_location ?? '—' }}
                <i class="fas fa-arrow-right" style="margin:0 4px;font-size:.65rem;color:#94a3b8;"></i>
                {{ $b->dropoff_location ?? '—' }}
            </div>
            @endif
            @if($b->customer)
            <div class="drv-trip-truck"><i class="fas fa-user" style="margin-right:3px;"></i>{{ $b->customer->company_name ?? $b->customer->name }}</div>
            @endif
        </div>
        <div>
            @php
                $bColor = match($b->status) {
                    'confirmed'   => 'background:#dbeafe;color:#2563eb;',
                    'in_progress' => 'background:#fff3e8;color:#FF6B00;',
                    'completed'   => 'background:#dcfce7;color:#059669;',
                    'cancelled'   => 'background:#fee2e2;color:#dc2626;',
                    default       => 'background:#fef3c7;color:#d97706;',
                };
                $bLabel = match($b->status) {
                    'confirmed'   => 'បានបញ្ជាក់',
                    'in_progress' => 'កំពុងដំណើរការ',
                    'completed'   => 'បានបញ្ចប់',
                    'cancelled'   => 'បានលុប',
                    default       => 'កំពុងរង់ចាំ',
                };
            @endphp
            <span style="padding:3px 10px;border-radius:20px;font-size:.7rem;font-weight:700;{{ $bColor }}">{{ $bLabel }}</span>
        </div>
    </div>
    @empty
    <div class="drv-empty">
        <i class="fas fa-calendar-times" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
        មិនមានដំណើរខាងមុខ
    </div>
    @endforelse
</div>

@else
{{-- Driver not linked yet --}}
<div style="text-align:center;padding:60px 20px;">
    <div style="font-size:3rem;margin-bottom:16px;color:#FF6B00;"><i class="fas fa-route"></i></div>
    <div style="font-size:1.1rem;font-weight:700;color:#1e293b;margin-bottom:8px;">សូមស្វាគមន៍, {{ Auth::user()->user_name }}!</div>
    <p style="color:#64748b;margin-bottom:20px;">គណនីរបស់អ្នកមិនទាន់ត្រូវបានភ្ជាប់ជាមួយប្រវត្តិអ្នកបើកបរ។ សូមទំនាក់ទំនងអ្នកគ្រប់គ្រង។</p>
</div>
@endif
@endif

@endsection
