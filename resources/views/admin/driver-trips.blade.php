@extends('admin.layouts.admin')
@section('title','ដំណើររបស់ខ្ញុំ')
@section('page-title')<span>ដំណើររបស់ខ្ញុំ</span>@endsection

@section('content')
<style>
    .dt-hero { background:linear-gradient(135deg,#FF6B00,#ff9040); border-radius:16px; padding:24px 28px; color:#fff; margin-bottom:24px; display:flex; align-items:center; gap:20px; }
    .dt-hero-avatar { width:64px; height:64px; background:rgba(255,255,255,.2); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:1.8rem; flex-shrink:0; overflow:hidden; }
    .dt-hero-avatar img { width:100%; height:100%; object-fit:cover; border-radius:50%; }
    .dt-hero-name { font-family:'Kantumruy Pro',sans-serif; font-size:1.4rem; font-weight:800; }
    .dt-hero-sub  { font-size:.85rem; opacity:.85; margin-top:2px; }
    .dt-truck-badge { display:inline-flex; align-items:center; gap:6px; background:rgba(255,255,255,.2); border-radius:20px; padding:4px 12px; font-size:.8rem; font-weight:600; margin-top:8px; }
    .dt-card { background:#fff; border-radius:14px; padding:18px 20px; border:1.5px solid #f1f5f9; box-shadow:0 2px 8px rgba(0,0,0,.05); margin-bottom:12px; display:flex; align-items:center; gap:18px; }
    .dt-card-num { width:40px; height:40px; border-radius:50%; background:#fff3e8; color:#FF6B00; display:flex; align-items:center; justify-content:center; font-weight:800; font-size:.9rem; flex-shrink:0; }
    .dt-card-body { flex:1; }
    .dt-card-date { font-weight:700; color:#1e293b; font-size:.9rem; }
    .dt-card-loc  { font-size:.8rem; color:#64748b; margin-top:4px; }
    .dt-card-sub  { font-size:.78rem; color:#94a3b8; margin-top:3px; }
    .dt-status { padding:3px 12px; border-radius:20px; font-size:.72rem; font-weight:700; white-space:nowrap; }
    .dt-empty { text-align:center; padding:60px 20px; color:#94a3b8; }
    .dt-empty-icon { font-size:3rem; margin-bottom:12px; display:block; }
    .dt-summary { display:grid; grid-template-columns:1fr 1fr; gap:14px; margin-bottom:20px; }
    .dt-sum-card { background:#fff; border-radius:14px; padding:16px 20px; border:1.5px solid #f1f5f9; box-shadow:0 2px 8px rgba(0,0,0,.05); display:flex; align-items:center; gap:14px; }
    .dt-sum-icon { width:44px; height:44px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
    .dt-sum-num  { font-size:1.5rem; font-weight:800; line-height:1; }
    .dt-sum-lbl  { font-size:.75rem; color:#64748b; margin-top:3px; }
    .dt-exp-row  { background:#fff; border-radius:12px; padding:14px 18px; border:1.5px solid #f1f5f9; margin-bottom:10px; display:flex; align-items:center; gap:14px; }
    .dt-exp-icon { width:38px; height:38px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:.95rem; flex-shrink:0; }
    .dt-exp-body { flex:1; }
    .dt-exp-type { font-weight:700; color:#1e293b; font-size:.88rem; }
    .dt-exp-date { font-size:.76rem; color:#94a3b8; margin-top:2px; }
    .dt-exp-desc { font-size:.76rem; color:#64748b; margin-top:2px; }
    .dt-exp-amounts { text-align:right; flex-shrink:0; }
    .dt-exp-fuel { font-weight:700; color:#ca8a04; font-size:.9rem; }
    .dt-exp-allow{ font-weight:700; color:#16a34a; font-size:.9rem; }
</style>

{{-- Driver hero card --}}
@if($driver)
<div class="dt-hero">
    <div class="dt-hero-avatar">
        @if($driver->driver_picture)
            <img src="{{ asset($driver->driver_picture) }}" alt="{{ $driver->full_name }}">
        @else
            <i class="fas fa-id-badge"></i>
        @endif
    </div>
    <div>
        <div class="dt-hero-name">{{ $driver->full_name }}</div>
        <div class="dt-hero-sub">{{ $driver->phone ?? '—' }} &nbsp;·&nbsp; {{ ucfirst($driver->status) }}</div>
        @if($driver->truck)
        <div class="dt-truck-badge">
            <i class="fas fa-truck"></i>
            {{ $driver->truck->truck_name }} ({{ $driver->truck->plate_number }})
        </div>
        @endif
    </div>
</div>
@else
<div style="background:#fff3e8;border-radius:12px;padding:16px 20px;margin-bottom:20px;color:#c2410c;font-size:.9rem;">
    <i class="fas fa-exclamation-triangle"></i>
    គណនីរបស់អ្នកមិនទាន់ត្រូវបានភ្ជាប់ជាមួយប្រវត្តិអ្នកបើកបរទេ។ សូមទំនាក់ទំនងអ្នកគ្រប់គ្រង។
</div>
@endif

{{-- Booking / trip list --}}
<div class="card">
    <div class="card-header">
        <div class="card-title">
            <i class="fas fa-route"></i> ដំណើរទាំងអស់
            <span class="bks-count-badge">{{ $bookings->count() }}</span>
        </div>
    </div>
    <div style="padding:16px 20px;">
        @forelse($bookings as $i => $b)
        @php
            $isPast   = $b->pick_up_date && $b->pick_up_date->toDateString() < now()->toDateString();
            $isToday  = $b->pick_up_date && $b->pick_up_date->toDateString() === now()->toDateString();
            $stColor  = match($b->status) {
                'confirmed'    => 'background:#dbeafe;color:#2563eb;',
                'in_progress'  => 'background:#fff3e8;color:#FF6B00;',
                'completed'    => 'background:#dcfce7;color:#059669;',
                'cancelled'    => 'background:#fee2e2;color:#dc2626;',
                default        => 'background:#fef3c7;color:#d97706;',
            };
            $stLabel  = match($b->status) {
                'confirmed'    => 'បានបញ្ជាក់',
                'in_progress'  => 'កំពុងដំណើរការ',
                'completed'    => 'បានបញ្ចប់',
                'cancelled'    => 'បានលុប',
                default        => 'កំពុងរង់ចាំ',
            };
        @endphp
        <div class="dt-card">
            <div class="dt-card-num">{{ $i + 1 }}</div>
            <div class="dt-card-body">
                <div class="dt-card-date">
                    <i class="fas fa-calendar-alt" style="color:#FF6B00;font-size:.85rem;"></i>
                    {{ $b->pick_up_date ? \Carbon\Carbon::parse($b->pick_up_date)->format('d/m/Y') : '—' }}
                    @if($isToday)
                        <span style="background:#FF6B00;color:#fff;font-size:.65rem;font-weight:700;padding:2px 8px;border-radius:10px;margin-left:6px;">ថ្ងៃនេះ</span>
                    @endif
                    @if($b->drop_off_date)
                        <span style="color:#94a3b8;font-size:.8rem;font-weight:400;margin-left:4px;">→ {{ \Carbon\Carbon::parse($b->drop_off_date)->format('d/m/Y') }}</span>
                    @endif
                    @if($b->booking_type)
                    @php
                        $typeIsImport = $b->booking_type === 'import';
                    @endphp
                    <span style="display:inline-flex;align-items:center;gap:3px;margin-left:6px;padding:2px 8px;border-radius:8px;font-size:.65rem;font-weight:700;{{ $typeIsImport ? 'background:#dbeafe;color:#2563eb;' : 'background:#ede9fe;color:#7c3aed;' }}">
                        <i class="fas {{ $typeIsImport ? 'fa-arrow-circle-down' : 'fa-arrow-circle-up' }}"></i>
                        {{ $typeIsImport ? 'នាំចូល' : 'នាំចេញ' }}
                    </span>
                    @endif
                </div>
                @if($b->pickup_location || $b->dropoff_location)
                <div class="dt-card-loc">
                    <i class="fas fa-circle" style="font-size:.4rem;color:#FF6B00;vertical-align:middle;"></i>
                    {{ $b->pickup_location ?? '—' }}
                    <i class="fas fa-arrow-right" style="margin:0 6px;font-size:.65rem;color:#94a3b8;"></i>
                    {{ $b->dropoff_location ?? '—' }}
                </div>
                @endif
                @if($b->customer)
                <div class="dt-card-sub">
                    <i class="fas fa-user" style="margin-right:3px;"></i>
                    {{ $b->customer->company_name ?? $b->customer->name ?? '—' }}
                    @if($b->booking_code)
                        &nbsp;·&nbsp; <span style="font-family:monospace;">{{ $b->booking_code }}</span>
                    @endif
                </div>
                @endif
                @if($b->container_number)
                <div class="dt-card-sub">
                    <i class="fas fa-box" style="margin-right:3px;"></i>
                    {{ $b->container_number }}
                    @if($b->container_size) ({{ $b->container_size }}) @endif
                </div>
                @endif
            </div>
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:6px;">
                <span class="dt-status" style="{{ $stColor }}">{{ $stLabel }}</span>
                @if($b->status === 'in_progress')
                    @if($b->driver_arrived_at)
                        <span style="font-size:.72rem;color:#059669;font-weight:600;">
                            <i class="fas fa-check-circle"></i> បានជូនដំណឹងហើយ
                        </span>
                    @else
                        <form method="POST" action="{{ route('admin.driver.arrived', $b->booking_id) }}" onsubmit="return confirm('ជូនដំណឹងអ្នកគ្រប់គ្រងថាអ្នកបានដល់ទីតាំង?')">
                            @csrf
                            <button type="submit" style="background:#FF6B00;color:#fff;border:none;border-radius:8px;padding:5px 12px;font-size:.75rem;font-weight:700;cursor:pointer;display:flex;align-items:center;gap:5px;font-family:'Kantumruy Pro',sans-serif;">
                                <i class="fas fa-map-marker-alt"></i> បានដល់ហើយ
                            </button>
                        </form>
                    @endif
                @endif
            </div>
        </div>
        @empty
        <div class="dt-empty">
            <i class="fas fa-calendar-times dt-empty-icon"></i>
            <div>មិនមានការដឹកជញ្ជូន</div>
            <div style="font-size:.82rem;margin-top:6px;">នៅពេលការដឹកត្រូវបានកក់ នឹងបង្ហាញនៅទីនេះ</div>
        </div>
        @endforelse
    </div>
</div>

{{-- Expense / Payment section --}}
@if($driver)
@php
    $totalFuel      = $expenses->where('expense_type','fuel')->sum('amount');
    $totalAllowance = $expenses->sum('driver_allowance');
@endphp
<div style="margin-top:24px;">
    <div style="font-size:.95rem;font-weight:700;color:#1e293b;margin-bottom:14px;display:flex;align-items:center;gap:8px;">
        <i class="fas fa-money-bill-wave" style="color:#16a34a;"></i> ការទូទាត់ & ចំណាយ
    </div>

    {{-- Summary cards --}}
    <div class="dt-summary">
        <div class="dt-sum-card" style="border-color:#fde68a;">
            <div class="dt-sum-icon" style="background:#fef9c3;color:#ca8a04;"><i class="fas fa-gas-pump"></i></div>
            <div>
                <div class="dt-sum-num" style="color:#ca8a04;">${{ number_format($totalFuel,2) }}</div>
                <div class="dt-sum-lbl">ប្រេងឥន្ធនៈសរុប</div>
            </div>
        </div>
        <div class="dt-sum-card" style="border-color:#d9f99d;">
            <div class="dt-sum-icon" style="background:#f0fdf4;color:#16a34a;"><i class="fas fa-wallet"></i></div>
            <div>
                <div class="dt-sum-num" style="color:#16a34a;">${{ number_format($totalAllowance,2) }}</div>
                <div class="dt-sum-lbl">លុយជើងតៃកុងសរុប</div>
            </div>
        </div>
    </div>

    {{-- Expense records --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">
                <i class="fas fa-receipt"></i> កំណត់ត្រាចំណាយ
                <span class="bks-count-badge">{{ $expenses->count() }}</span>
            </div>
        </div>
        <div style="padding:16px 20px;">
            @forelse($expenses as $e)
            @php
                $typeLabel = match($e->expense_type) {
                    'fuel'   => 'ប្រេងឥន្ធនៈ',
                    'salary' => 'ប្រាក់ខែ',
                    'repair' => 'ជួសជុល',
                    default  => 'ផ្សេងៗ',
                };
                $typeIcon  = match($e->expense_type) {
                    'fuel'   => ['fas fa-gas-pump',    '#fef9c3','#ca8a04'],
                    'salary' => ['fas fa-money-check', '#ede9fe','#7c3aed'],
                    'repair' => ['fas fa-tools',       '#fee2e2','#dc2626'],
                    default  => ['fas fa-receipt',     '#f1f5f9','#64748b'],
                };
            @endphp
            <div class="dt-exp-row">
                <div class="dt-exp-icon" style="background:{{ $typeIcon[1] }};color:{{ $typeIcon[2] }};">
                    <i class="{{ $typeIcon[0] }}"></i>
                </div>
                <div class="dt-exp-body">
                    <div class="dt-exp-type">
                        {{ $typeLabel }}
                        @if($e->booking)
                        <span style="display:inline-flex;align-items:center;gap:3px;margin-left:6px;padding:2px 7px;border-radius:6px;font-size:.62rem;font-weight:700;background:#f1f5f9;color:#475569;font-family:monospace;">
                            {{ $e->booking->booking_code ?? '#'.$e->booking->booking_id }}
                        </span>
                        @endif
                    </div>
                    <div class="dt-exp-date">
                        <i class="fas fa-calendar-alt" style="font-size:.7rem;"></i>
                        {{ $e->expense_date ? $e->expense_date->format('d/m/Y') : '—' }}
                    </div>
                    @if($e->booking)
                    <div class="dt-exp-desc" style="margin-top:4px;">
                        @if($e->booking->booking_type)
                        @php $bIsImport = $e->booking->booking_type === 'import'; @endphp
                        <span style="padding:1px 6px;border-radius:5px;font-size:.62rem;font-weight:700;margin-right:4px;{{ $bIsImport ? 'background:#dbeafe;color:#2563eb;' : 'background:#ede9fe;color:#7c3aed;' }}">
                            {{ $bIsImport ? 'នាំចូល' : 'នាំចេញ' }}
                        </span>
                        @endif
                        @if($e->booking->pickup_location || $e->booking->dropoff_location)
                        <i class="fas fa-circle" style="font-size:.35rem;color:#FF6B00;vertical-align:middle;margin-right:2px;"></i>
                        {{ $e->booking->pickup_location ?? '—' }}
                        <i class="fas fa-arrow-right" style="margin:0 4px;font-size:.6rem;color:#94a3b8;"></i>
                        {{ $e->booking->dropoff_location ?? '—' }}
                        @endif
                    </div>
                    @if($e->booking->pick_up_date)
                    <div class="dt-exp-desc">
                        <i class="fas fa-truck" style="font-size:.7rem;margin-right:2px;color:#FF6B00;"></i>
                        {{ $e->booking->pick_up_date->format('d/m/Y') }}
                        @if($e->booking->customer)
                            &nbsp;·&nbsp; {{ $e->booking->customer->company_name ?? $e->booking->customer->name ?? '—' }}
                        @endif
                    </div>
                    @endif
                    @endif
                    @if($e->description)
                    <div class="dt-exp-desc" style="color:#94a3b8;font-style:italic;">{{ $e->description }}</div>
                    @endif
                </div>
                <div class="dt-exp-amounts">
                    @if($e->amount > 0)
                    <div class="dt-exp-fuel"><i class="fas fa-gas-pump" style="font-size:.7rem;margin-right:2px;"></i>${{ number_format($e->amount,2) }}</div>
                    @endif
                    @if($e->driver_allowance > 0)
                    <div class="dt-exp-allow"><i class="fas fa-wallet" style="font-size:.7rem;margin-right:2px;"></i>${{ number_format($e->driver_allowance,2) }}</div>
                    @endif
                </div>
            </div>
            @empty
            <div class="dt-empty">
                <i class="fas fa-receipt dt-empty-icon"></i>
                <div>មិនមានកំណត់ត្រាចំណាយ</div>
            </div>
            @endforelse
        </div>
    </div>
</div>
@endif
@endsection
