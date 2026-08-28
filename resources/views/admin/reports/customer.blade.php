@extends('admin.layouts.admin')

@section('title', 'របាយការណ៍អតិថិជន')
@section('page-title')<span>របាយការណ៍</span>អតិថិជន@endsection

@push('styles')
<style>
.cr-page { display:flex; flex-direction:column; gap:22px; }

/* top bar */
.cr-topbar { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:12px; }
.cr-topbar-left { display:flex; align-items:center; gap:14px; }
.cr-icon-wrap {
    width:48px; height:48px;
    background:linear-gradient(135deg,#3b82f6,#6366f1);
    border-radius:14px;
    display:flex; align-items:center; justify-content:center;
    box-shadow:0 4px 12px rgba(59,130,246,.3);
}
.cr-icon-wrap i { color:#fff; font-size:1.2rem; }
.cr-title { font-family:var(--font-head); font-size:1.18rem; font-weight:800; color:#1e293b; margin:0; }
.cr-subtitle { font-size:.8rem; color:var(--gray); margin-top:2px; }
.cr-print-btn {
    display:inline-flex; align-items:center; gap:7px;
    padding:9px 18px;
    background:#fff; border:1.5px solid var(--border); border-radius:10px;
    color:#374151; font-family:var(--font); font-size:.88rem; font-weight:600;
    text-decoration:none; transition:all .2s;
    box-shadow:0 1px 4px rgba(0,0,0,.06);
}
.cr-print-btn:hover { background:#f8fafc; border-color:#94a3b8; color:#1e293b; }

/* filter */
.cr-filter {
    background:#fff; border-radius:16px;
    border:1.5px solid var(--border);
    box-shadow:0 2px 8px rgba(0,0,0,.05);
    padding:18px 22px;
}
.cr-filter-label {
    font-family:var(--font); font-size:.78rem; font-weight:600;
    color:var(--gray); text-transform:uppercase; letter-spacing:.04em;
    margin-bottom:6px; display:block;
}
.cr-filter-row { display:flex; align-items:flex-end; gap:12px; flex-wrap:wrap; }
.cr-filter-group { display:flex; flex-direction:column; }
.cr-input, .cr-select {
    border:1.5px solid var(--border); border-radius:9px;
    padding:8px 12px; font-family:var(--font); font-size:.9rem;
    color:#1e293b; background:#f8fafc; outline:none;
    transition:border-color .2s,box-shadow .2s;
}
.cr-input:focus,.cr-select:focus {
    border-color:#3b82f6;
    box-shadow:0 0 0 3px rgba(59,130,246,.12);
    background:#fff;
}
.cr-select { min-width:200px; }
.cr-btn-search {
    display:inline-flex; align-items:center; gap:7px;
    padding:8px 18px;
    background:linear-gradient(135deg,#3b82f6,#6366f1);
    color:#fff; border:none; border-radius:9px;
    font-family:var(--font); font-size:.9rem; font-weight:600;
    cursor:pointer; transition:opacity .2s;
    box-shadow:0 3px 10px rgba(59,130,246,.3);
}
.cr-btn-search:hover { opacity:.88; }
.cr-btn-reset {
    display:inline-flex; align-items:center; gap:7px;
    padding:8px 14px; background:#f1f5f9; color:#374151;
    border:1.5px solid var(--border); border-radius:9px;
    font-family:var(--font); font-size:.9rem; font-weight:500;
    text-decoration:none; transition:background .2s;
}
.cr-btn-reset:hover { background:#e2e8f0; }

/* stats */
.cr-stats { display:grid; grid-template-columns:repeat(3,1fr); gap:16px; }
@media(max-width:768px){ .cr-stats { grid-template-columns:1fr; } }
.cr-stat {
    background:#fff; border-radius:16px;
    border:1.5px solid var(--border);
    padding:22px 20px;
    display:flex; align-items:center; gap:16px;
    box-shadow:0 2px 8px rgba(0,0,0,.05);
    transition:transform .2s,box-shadow .2s;
    position:relative; overflow:hidden;
}
.cr-stat:hover { transform:translateY(-2px); box-shadow:0 6px 20px rgba(0,0,0,.09); }
.cr-stat::before {
    content:''; position:absolute; top:0; left:0;
    width:4px; height:100%; border-radius:16px 0 0 16px;
}
.cr-stat-blue::before   { background:linear-gradient(180deg,#3b82f6,#93c5fd); }
.cr-stat-green::before  { background:linear-gradient(180deg,#10b981,#6ee7b7); }
.cr-stat-purple::before { background:linear-gradient(180deg,#8b5cf6,#c4b5fd); }
.cr-stat-icon { width:48px; height:48px; border-radius:12px; display:flex; align-items:center; justify-content:center; font-size:1.15rem; flex-shrink:0; }
.cr-stat-blue   .cr-stat-icon { background:#eff6ff; color:#3b82f6; }
.cr-stat-green  .cr-stat-icon { background:#f0fdf4; color:#10b981; }
.cr-stat-purple .cr-stat-icon { background:#f5f3ff; color:#8b5cf6; }
.cr-stat-val { font-family:var(--font-head); font-size:1.7rem; font-weight:800; line-height:1; color:#1e293b; }
.cr-stat-lbl { font-size:.78rem; color:var(--gray); margin-top:4px; }

/* card */
.cr-card {
    background:#fff; border-radius:16px;
    border:1.5px solid var(--border);
    box-shadow:0 2px 8px rgba(0,0,0,.05);
    overflow:hidden;
}
.cr-card-head {
    display:flex; align-items:center; justify-content:space-between;
    padding:14px 20px; border-bottom:1.5px solid var(--border);
    background:#fafbfc;
}
.cr-card-head-title { display:flex; align-items:center; gap:9px; font-family:var(--font); font-weight:700; font-size:.95rem; color:#1e293b; }
.cr-head-icon { width:30px; height:30px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.85rem; }
.icon-blue   { background:#eff6ff; color:#3b82f6; }
.icon-green  { background:#f0fdf4; color:#10b981; }
.cr-badge { display:inline-flex; align-items:center; gap:5px; padding:3px 10px; border-radius:20px; font-size:.75rem; font-weight:600; }
.cr-badge-gray   { background:#f1f5f9; color:#64748b; }
.cr-badge-blue   { background:#eff6ff; color:#3b82f6; }
.cr-badge-green  { background:#f0fdf4; color:#10b981; }

/* customer summary table */
.cr-table { width:100%; border-collapse:collapse; }
.cr-table th {
    padding:11px 16px; background:#f8fafc;
    font-family:var(--font); font-size:.78rem; font-weight:700;
    color:var(--gray); text-transform:uppercase; letter-spacing:.04em;
    border-bottom:1.5px solid var(--border);
}
.cr-table td { padding:14px 16px; border-bottom:1px solid #f1f5f9; font-size:.9rem; color:#334155; vertical-align:middle; }
.cr-table tbody tr:last-child td { border-bottom:none; }
.cr-table tbody tr:hover td { background:#f8fbff; }
.cr-table tfoot td { padding:12px 16px; background:#f8fafc; border-top:2px solid var(--border); font-weight:700; }

.cr-customer-name { font-weight:700; color:#1e293b; }
.cr-customer-sub  { font-size:.78rem; color:var(--gray); margin-top:2px; }
.cr-count-badge {
    display:inline-block; padding:3px 10px;
    background:#eff6ff; color:#3b82f6;
    border-radius:20px; font-size:.78rem; font-weight:700;
}
.cr-done-badge {
    display:inline-block; padding:3px 10px;
    background:#f0fdf4; color:#10b981;
    border-radius:20px; font-size:.78rem; font-weight:700;
}
.cr-amount { font-weight:700; color:#10b981; font-family:var(--font-head); }
.cr-total-amount { font-weight:800; color:#10b981; font-family:var(--font-head); font-size:1rem; }

/* booking detail accordion */
.cr-expand-btn {
    display:inline-flex; align-items:center; gap:5px;
    padding:4px 10px; border-radius:7px;
    background:#f8fafc; border:1.5px solid var(--border);
    color:#64748b; font-size:.78rem; font-weight:600;
    cursor:pointer; transition:all .2s; font-family:var(--font);
}
.cr-expand-btn:hover { background:#eff6ff; color:#3b82f6; border-color:#bfdbfe; }
.cr-expand-btn i { transition:transform .25s; }
.cr-expand-btn.open i { transform:rotate(180deg); }

.cr-booking-sub { display:none; }
.cr-booking-sub.open { display:table-row; }
.cr-sub-inner { padding:0 16px 14px 36px; }
.cr-sub-table { width:100%; border-collapse:collapse; border-radius:10px; overflow:hidden; }
.cr-sub-table th {
    padding:8px 12px; background:#f8fafc;
    font-size:.75rem; font-weight:700; color:var(--gray);
    text-transform:uppercase; letter-spacing:.03em;
    border-bottom:1px solid var(--border);
}
.cr-sub-table td { padding:9px 12px; border-bottom:1px solid #f1f5f9; font-size:.83rem; }
.cr-sub-table tbody tr:last-child td { border-bottom:none; }

/* status chips */
.cr-status {
    display:inline-flex; align-items:center; gap:5px;
    padding:3px 10px; border-radius:20px;
    font-size:.75rem; font-weight:600;
}
.cr-status-paid    { background:#f0fdf4; color:#16a34a; }
.cr-status-pending { background:#fef9c3; color:#a16207; }
.cr-status-none    { background:#f1f5f9; color:#94a3b8; }

/* empty */
.cr-empty { display:flex; flex-direction:column; align-items:center; justify-content:center; padding:56px 20px; gap:12px; }
.cr-empty-icon { width:72px; height:72px; border-radius:20px; background:#f8fafc; display:flex; align-items:center; justify-content:center; font-size:1.8rem; color:#cbd5e1; }
.cr-empty-title { font-weight:700; color:#94a3b8; font-size:.95rem; }
</style>
@endpush

@section('content')
<div class="cr-page">

{{-- TOP BAR --}}
<div class="cr-topbar">
    <div class="cr-topbar-left">
        <div class="cr-icon-wrap"><i class="fas fa-users"></i></div>
        <div>
            <div class="cr-title">របាយការណ៍អតិថិជន</div>
            <div class="cr-subtitle">ការតាមដានការកក់ និងការទូទាត់អតិថិជនប្រចាំខែ</div>
        </div>
    </div>
    <a href="{{ route('admin.reports.customer.print', ['month'=>$month,'filter_key'=>$filterKey]) }}"
       target="_blank" class="cr-print-btn">
        <i class="fas fa-print"></i> បោះពុម្ព
    </a>
</div>

{{-- FILTER --}}
<div class="cr-filter">
    <form method="GET" action="{{ route('admin.reports.customer') }}">
        <div class="cr-filter-row">
            <div class="cr-filter-group">
                <span class="cr-filter-label"><i class="fas fa-calendar-alt" style="margin-right:4px;"></i>ខែ</span>
                <input type="month" name="month" value="{{ $month }}" class="cr-input">
            </div>
            <div class="cr-filter-group">
                <span class="cr-filter-label"><i class="fas fa-user" style="margin-right:4px;"></i>អតិថិជន / បុគ្គលិក</span>
                <select name="filter_key" class="cr-select">
                    <option value="">ទាំងអស់</option>
                    @if($customers->isNotEmpty())
                    <optgroup label="── អតិថិជន ──">
                        @foreach($customers as $c)
                            <option value="c_{{ $c->customer_id }}" {{ $filterKey === 'c_'.$c->customer_id ? 'selected' : '' }}>
                                {{ $c->full_name }}{{ $c->phone ? ' — '.$c->phone : '' }}
                            </option>
                        @endforeach
                    </optgroup>
                    @endif
                    @if($staffUsers->isNotEmpty())
                    <optgroup label="── បុគ្គលិកក្រុមហ៊ុន ──">
                        @foreach($staffUsers as $u)
                            <option value="u_{{ $u->user_id }}" {{ $filterKey === 'u_'.$u->user_id ? 'selected' : '' }}>
                                {{ $u->user_name }} ({{ ucfirst($u->role) }})
                            </option>
                        @endforeach
                    </optgroup>
                    @endif
                </select>
            </div>
            <div style="display:flex;gap:8px;align-items:flex-end;">
                <button type="submit" class="cr-btn-search"><i class="fas fa-search"></i> ស្វែងរក</button>
                <a href="{{ route('admin.reports.customer') }}" class="cr-btn-reset"><i class="fas fa-redo"></i> កំណត់ឡើងវិញ</a>
            </div>
        </div>
    </form>
</div>

{{-- STATS --}}
<div class="cr-stats">
    <div class="cr-stat cr-stat-blue">
        <div class="cr-stat-icon"><i class="fas fa-users"></i></div>
        <div>
            <div class="cr-stat-val">{{ $totalPersons }}</div>
            <div class="cr-stat-lbl">ចំនួនអតិថិជន / បុគ្គលិក</div>
        </div>
    </div>
    <div class="cr-stat cr-stat-purple">
        <div class="cr-stat-icon"><i class="fas fa-clipboard-list"></i></div>
        <div>
            <div class="cr-stat-val">{{ $totalBookings }}</div>
            <div class="cr-stat-lbl">ការកក់សរុប</div>
        </div>
    </div>
    <div class="cr-stat cr-stat-green">
        <div class="cr-stat-icon"><i class="fas fa-check-circle"></i></div>
        <div>
            <div class="cr-stat-val">{{ $completedBookings }}</div>
            <div class="cr-stat-lbl">ការទូទាត់បានបញ្ចប់</div>
        </div>
    </div>
</div>

{{-- CUSTOMER SUMMARY TABLE --}}
<div class="cr-card">
    <div class="cr-card-head">
        <div class="cr-card-head-title">
            <div class="cr-head-icon icon-blue"><i class="fas fa-users"></i></div>
            សង្ខេបអតិថិជន / បុគ្គលិក — {{ $month }}
        </div>
        <span class="cr-badge cr-badge-blue">{{ $totalPersons }} នាក់</span>
    </div>

    @if($customerSummary->isEmpty())
    <div class="cr-empty">
        <div class="cr-empty-icon"><i class="fas fa-users"></i></div>
        <div class="cr-empty-title">គ្មានទិន្នន័យអតិថិជនក្នុងខែ {{ $month }}</div>
    </div>
    @else
    <div style="overflow-x:auto;">
        <table class="cr-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>អតិថិជន</th>
                    <th style="text-align:center;">ការកក់សរុប</th>
                    <th style="text-align:center;">ទូទាត់បានបញ្ចប់</th>
                    <th style="text-align:right;">ប្រាក់ទទួលបានសរុប</th>
                    <th style="text-align:center;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($customerSummary as $key => $item)
                @php $rowId = 'cr-detail-'.str_replace('_','-',$key); @endphp
                <tr>
                    <td style="color:#94a3b8;font-size:.82rem;font-weight:600;">{{ $loop->iteration }}</td>
                    <td>
                        <div class="cr-customer-name">
                            @if($item['is_internal'])
                                <i class="fas fa-user-tie" style="color:#FF6B00;margin-right:6px;font-size:.9rem;"></i>
                            @else
                                <i class="fas fa-user-circle" style="color:#3b82f6;margin-right:6px;font-size:.9rem;"></i>
                            @endif
                            {{ $item['display_name'] }}
                        </div>
                        @if($item['display_sub'])
                            <div class="cr-customer-sub">
                                @if($item['is_internal'])
                                    <i class="fas fa-id-badge" style="margin-right:3px;"></i>
                                @else
                                    <i class="fas fa-phone" style="margin-right:3px;"></i>
                                @endif
                                {{ $item['display_sub'] }}
                            </div>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <span class="cr-count-badge">{{ $item['booking_count'] }} ដង</span>
                    </td>
                    <td style="text-align:center;">
                        <span class="cr-done-badge">{{ $item['completed_count'] }} ការកក់</span>
                    </td>
                    <td style="text-align:right;">
                        <span class="cr-amount">${{ number_format($item['total_paid'], 2) }}</span>
                    </td>
                    <td style="text-align:center;">
                        <button class="cr-expand-btn" id="btn-{{ $rowId }}"
                                onclick="toggleDetail('{{ $rowId }}')">
                            <i class="fas fa-chevron-down"></i> លម្អិត
                        </button>
                    </td>
                </tr>
                {{-- Detail sub-row --}}
                <tr class="cr-booking-sub" id="{{ $rowId }}">
                    <td colspan="6" style="padding:0;background:#f8fbff;">
                        <div class="cr-sub-inner">
                            <table class="cr-sub-table">
                                <thead>
                                    <tr>
                                        <th>លេខការកក់</th>
                                        <th>កាលបរិច្ឆេទ</th>
                                        <th>ទីតាំង</th>
                                        <th style="text-align:right;">តម្លៃ</th>
                                        <th style="text-align:center;">ស្ថានភាពទូទាត់</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($item['bookings'] as $b)
                                    @php
                                        $hasVerified = $b->payments->where('verification_status','verified')->isNotEmpty();
                                        $hasPending  = $b->payments->where('verification_status','pending')->isNotEmpty();
                                    @endphp
                                    <tr>
                                        <td style="font-weight:700;color:#1e293b;">{{ $b->formatted_id }}</td>
                                        <td style="color:#64748b;">{{ \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') }}</td>
                                        <td style="color:#475569;max-width:180px;">
                                            {{ $b->pickup_location ?? '—' }}
                                            @if($b->dropoff_location)
                                                <span style="color:#94a3b8;"> → </span>{{ $b->dropoff_location }}
                                            @endif
                                        </td>
                                        <td style="text-align:right;font-weight:700;color:#1e293b;">
                                            ${{ number_format($b->total_price ?? 0, 2) }}
                                        </td>
                                        <td style="text-align:center;">
                                            @if($hasVerified)
                                                <span class="cr-status cr-status-paid">
                                                    <i class="fas fa-check-circle"></i> បានទូទាត់
                                                </span>
                                            @elseif($hasPending)
                                                <span class="cr-status cr-status-pending">
                                                    <i class="fas fa-clock"></i> រងចាំផ្ទៀងផ្ទាត់
                                                </span>
                                            @else
                                                <span class="cr-status cr-status-none">
                                                    <i class="fas fa-minus"></i> មិនទាន់ទូទាត់
                                                </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2" style="color:#64748b;font-size:.85rem;">សរុប</td>
                    <td style="text-align:center;"><span class="cr-count-badge">{{ $totalBookings }} ដង</span></td>
                    <td style="text-align:center;"><span class="cr-done-badge">{{ $completedBookings }} ការកក់</span></td>
                    <td style="text-align:right;"><span class="cr-total-amount">${{ number_format($customerSummary->sum('total_paid'),2) }}</span></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    @endif
</div>

</div>
@endsection

@push('scripts')
<script>
function toggleDetail(rowId) {
    var row = document.getElementById(rowId);
    var btn = document.getElementById('btn-' + rowId);
    row.classList.toggle('open');
    btn.classList.toggle('open');
}
</script>
@endpush
