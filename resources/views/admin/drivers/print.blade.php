<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>បញ្ជីអ្នកបើកបរ — LS Trucking Service</title>
<link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: 13px;
    color: #1e293b;
    background: #fff;
    padding: 28px 32px;
}

/* ── No-print toolbar ── */
.no-print {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
}
.btn-print {
    padding: 8px 20px;
    background: #FF6B00;
    color: #fff;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: .88rem;
    font-weight: 600;
}
.btn-close {
    padding: 8px 16px;
    background: #f1f5f9;
    color: #374151;
    border: none;
    border-radius: 7px;
    cursor: pointer;
    font-family: 'Kantumruy Pro', sans-serif;
    font-size: .88rem;
}

/* ── Header ── */
.print-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 18px;
    padding-bottom: 14px;
    border-bottom: 2px solid #FF6B00;
}
.print-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}
.print-logo-box {
    width: 44px;
    height: 44px;
    background: #FF6B00;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.15rem;
    font-weight: 800;
    letter-spacing: -1px;
}
.print-company-name {
    font-size: 1.15rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1.2;
}
.print-company-sub {
    font-size: .75rem;
    color: #64748b;
    margin-top: 2px;
}
.print-meta {
    text-align: right;
}
.print-title {
    font-size: 1.15rem;
    font-weight: 800;
    color: #FF6B00;
}
.print-date {
    font-size: .78rem;
    color: #64748b;
    margin-top: 4px;
}

/* ── Summary boxes ── */
.print-summary {
    display: flex;
    gap: 12px;
    margin-bottom: 18px;
}
.print-sum-box {
    flex: 1;
    border: 1.5px solid #e2e8f0;
    border-radius: 9px;
    padding: 10px 14px;
    text-align: center;
}
.print-sum-val {
    font-size: 1.4rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}
.print-sum-lbl {
    font-size: .72rem;
    color: #64748b;
    margin-top: 4px;
}
.sum-blue   .print-sum-val { color: #2563eb; }
.sum-green  .print-sum-val { color: #059669; }
.sum-yellow .print-sum-val { color: #d97706; }
.sum-red    .print-sum-val { color: #dc2626; }

/* ── Table ── */
table {
    width: 100%;
    border-collapse: collapse;
    font-size: .82rem;
}
thead tr {
    background: #FF6B00;
    color: #fff;
}
thead th {
    padding: 9px 10px;
    text-align: left;
    font-weight: 700;
    font-size: .78rem;
    white-space: nowrap;
}
tbody tr:nth-child(even) { background: #f8fafc; }
tbody tr:hover           { background: #fff3e8; }
tbody td {
    padding: 8px 10px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
}
.col-num   { width: 36px; text-align: center; color: #94a3b8; font-weight: 700; }
.col-name  { min-width: 160px; }
.col-phone { min-width: 120px; }
.col-hire  { min-width: 100px; white-space: nowrap; }
.col-status{ min-width: 110px; }
.col-truck { min-width: 150px; }

/* ── Driver cell ── */
.drv-cell { display: flex; align-items: center; gap: 9px; }
.drv-avatar {
    width: 34px; height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #FF6B00, #ff9500);
    display: flex; align-items: center; justify-content: center;
    color: #fff; font-weight: 700; font-size: .85rem;
    flex-shrink: 0; overflow: hidden;
}
.drv-avatar img { width: 100%; height: 100%; object-fit: cover; }
.drv-name  { font-weight: 600; color: #1e293b; }
.drv-id    { font-size: .72rem; color: #94a3b8; }

/* ── Status badges ── */
.badge {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 3px 10px; border-radius: 20px;
    font-size: .72rem; font-weight: 700;
}
.badge-active   { background: #dcfce7; color: #16a34a; }
.badge-inactive { background: #fee2e2; color: #dc2626; }
.badge-on_leave { background: #fef9c3; color: #ca8a04; }

/* ── Truck cell ── */
.truck-cell { display: flex; align-items: center; gap: 7px; }
.truck-icon { color: #FF6B00; font-size: .78rem; }
.truck-name { font-weight: 600; color: #1e293b; font-size: .82rem; }
.truck-plate{ font-size: .72rem; color: #64748b; }
.no-truck   { color: #cbd5e1; font-style: italic; }

/* ── Footer ── */
.print-footer {
    margin-top: 24px;
    padding-top: 12px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    font-size: .72rem;
    color: #94a3b8;
}

/* ── Print media ── */
@media print {
    .no-print { display: none !important; }
    body { padding: 16px; }
    thead tr { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .badge    { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    tbody tr:nth-child(even) { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
}
</style>
</head>
<body>

{{-- Toolbar (hidden on print) --}}
<div class="no-print">
    <button class="btn-print" onclick="window.print()">
        <i class="fas fa-print"></i> បោះពុម្ព
    </button>
    <button class="btn-close" onclick="window.close()">
        <i class="fas fa-times"></i> បិទ
    </button>
</div>

{{-- Header --}}
<div class="print-header">
    <div class="print-brand">
        <div class="print-logo-box">LS</div>
        <div>
            <div class="print-company-name">🚚 LS Trucking Service</div>
            <div class="print-company-sub">ប្រព័ន្ធគ្រប់គ្រងដឹកជញ្ជូន</div>
        </div>
    </div>
    <div class="print-meta">
        <div class="print-title">បញ្ជីអ្នកបើកបរ</div>
        <div class="print-date">
            <i class="fas fa-calendar-alt"></i>
            កាលបរិច្ឆេទ: {{ now()->format('d/m/Y') }}
        </div>
    </div>
</div>

{{-- Summary --}}
<div class="print-summary">
    <div class="print-sum-box sum-blue">
        <div class="print-sum-val">{{ $total }}</div>
        <div class="print-sum-lbl">អ្នកបើកបរសរុប</div>
    </div>
    <div class="print-sum-box sum-green">
        <div class="print-sum-val">{{ $totalActive }}</div>
        <div class="print-sum-lbl">កំពុងធ្វើការ</div>
    </div>
    <div class="print-sum-box sum-yellow">
        <div class="print-sum-val">{{ $totalLeave }}</div>
        <div class="print-sum-lbl">ឈប់សម្រាក</div>
    </div>
    <div class="print-sum-box sum-red">
        <div class="print-sum-val">{{ $totalInactive }}</div>
        <div class="print-sum-lbl">មិនសកម្ម</div>
    </div>
</div>

{{-- Table --}}
<table>
    <thead>
        <tr>
            <th class="col-num">ល.រ</th>
            <th class="col-name">អ្នកបើកបរ</th>
            <th class="col-phone">លេខទូរស័ព្ទ</th>
            <th class="col-hire">ថ្ងៃចូលធ្វើការ</th>
            <th class="col-status">ស្ថានភាព</th>
            <th class="col-truck">រថយន្តដែលបានកំណត់</th>
        </tr>
    </thead>
    <tbody>
        @forelse($drivers as $i => $d)
        @php
            $statusMap = [
                'active'   => ['label' => 'កំពុងធ្វើការ', 'class' => 'badge-active'],
                'inactive' => ['label' => 'មិនសកម្ម',    'class' => 'badge-inactive'],
                'on_leave' => ['label' => 'ឈប់សម្រាក',   'class' => 'badge-on_leave'],
            ];
            $st = $statusMap[$d->status] ?? ['label' => $d->status, 'class' => 'badge-inactive'];
        @endphp
        <tr>
            <td class="col-num">{{ $i + 1 }}</td>
            <td>
                <div class="drv-cell">
                    <div class="drv-avatar">
                        @if($d->driver_picture)
                            <img src="{{ asset($d->driver_picture) }}" alt="{{ $d->full_name }}">
                        @else
                            {{ strtoupper(mb_substr($d->full_name, 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <div class="drv-name">{{ $d->full_name }}</div>
                        <div class="drv-id">ID D{{ $d->driver_id }}</div>
                    </div>
                </div>
            </td>
            <td>{{ $d->phone ?: '—' }}</td>
            <td>
                {{ $d->hire_date ? \Carbon\Carbon::parse($d->hire_date)->format('d/m/Y') : '—' }}
            </td>
            <td>
                <span class="badge {{ $st['class'] }}">
                    <i class="fas fa-circle" style="font-size:.5rem;"></i>
                    {{ $st['label'] }}
                </span>
            </td>
            <td>
                @if($d->truck)
                    <div class="truck-cell">
                        <i class="fas fa-truck truck-icon"></i>
                        <div>
                            <div class="truck-name">{{ $d->truck->truck_name }}</div>
                            <div class="truck-plate">{{ $d->truck->plate_number }}</div>
                        </div>
                    </div>
                @else
                    <span class="no-truck">មិនទាន់កំណត់</span>
                @endif
            </td>
        </tr>
        @empty
        <tr>
            <td colspan="6" style="text-align:center;padding:32px;color:#94a3b8;">
                មិនមានអ្នកបើកបរ
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

{{-- Footer --}}
<div class="print-footer">
    <span>LS Trucking Service — បញ្ជីអ្នកបើកបរ</span>
    <span>បោះពុម្ព: {{ now()->format('d/m/Y H:i') }}</span>
</div>

</body>
</html>
