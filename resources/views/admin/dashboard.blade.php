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
    font-family: 'Montserrat', sans-serif;
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
    font-family: 'Montserrat', sans-serif;
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
                        <th>#ID</th>
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
                        <td><span class="badge badge-{{ $b->status }}">{{ $b->status }}</span></td>
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
    <i class="fas fa-calendar"></i> សង្ខេបខែ {{ now()->locale('km')->translatedFormat('F Y') }}
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
<div style="text-align:center;padding:60px 20px;">
    <div style="font-size:3rem;margin-bottom:16px;color:#FF6B00;"><i class="fas fa-route"></i></div>
    <div style="font-family:'Montserrat',sans-serif;font-size:1.2rem;font-weight:700;color:#1e293b;margin-bottom:8px;">
        សូមស្វាគមន៍, {{ Auth::user()->user_name }}!
    </div>
    <p style="color:#64748b;margin-bottom:24px;">ចូលទៅកាន់ ដំណើររបស់ខ្ញុំ ដើម្បីមើលកាលវិភាគការដឹករបស់អ្នក។</p>
    <a href="{{ route('admin.driver.trips') }}" class="btn btn-orange" style="text-decoration:none;">
        <i class="fas fa-route"></i> មើលដំណើររបស់ខ្ញុំ
    </a>
</div>
@endif

@endsection
