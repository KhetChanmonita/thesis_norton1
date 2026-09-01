<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>របាយការណ៍អតិថិជន — {{ $month }}</title>
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        *{ box-sizing:border-box; margin:0; padding:0; }
        body{ font-family:'Kantumruy Pro',sans-serif; font-size:13px; color:#1e293b; background:#fff; padding:24px; }
        .print-header{ display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:20px; border-bottom:2px solid #3b82f6; padding-bottom:14px; }
        .company-name{ font-size:1.2rem; font-weight:700; color:#1e40af; }
        .report-title{ font-size:1rem; font-weight:700; margin-top:4px; }
        .report-meta{ font-size:.85rem; color:#64748b; margin-top:3px; }
        .print-date{ font-size:.8rem; color:#94a3b8; text-align:right; }
        .stats-row{ display:flex; gap:16px; margin-bottom:18px; }
        .stat-box{ flex:1; border:1px solid #e2e8f0; border-radius:8px; padding:12px; text-align:center; }
        .stat-val{ font-size:1.4rem; font-weight:800; }
        .stat-lbl{ font-size:.78rem; color:#64748b; margin-top:2px; }
        .section-title{ font-size:.9rem; font-weight:700; margin:16px 0 8px; color:#374151; border-left:3px solid #3b82f6; padding-left:8px; }
        table{ width:100%; border-collapse:collapse; margin-bottom:10px; }
        th{ background:#eff6ff; color:#1e40af; font-weight:700; padding:8px 10px; text-align:left; border:1px solid #bfdbfe; font-size:.82rem; }
        td{ padding:7px 10px; border:1px solid #e2e8f0; vertical-align:top; font-size:.82rem; }
        tr:nth-child(even) td{ background:#fafafa; }
        .text-center{ text-align:center; }
        .text-right{ text-align:right; }
        .total-row td{ font-weight:700; background:#eff6ff !important; border-top:2px solid #3b82f6; }
        .status-paid   { color:#16a34a; font-weight:600; }
        .status-pending{ color:#a16207; font-weight:600; }
        .status-none   { color:#94a3b8; }
        .sub-th{ background:#f8fafc; color:#64748b; font-size:.75rem; }
        .sub-td{ background:#fafbff; font-size:.78rem; padding:6px 10px; }
        .customer-name-cell{ font-weight:700; }
        .customer-sub{ font-size:.75rem; color:#64748b; }
        .no-data{ text-align:center; color:#94a3b8; padding:24px; }
        .no-print{ margin-bottom:16px; }
        @media print{ .no-print{ display:none !important; } }
    </style>
</head>
<body>

<div class="no-print">
    <button onclick="window.print()" style="padding:8px 20px;background:#3b82f6;color:#fff;border:none;border-radius:6px;cursor:pointer;font-family:'Kantumruy Pro',sans-serif;font-size:.9rem;">&#128424; បោះពុម្ព</button>
    <button onclick="window.close()" style="margin-left:8px;padding:8px 16px;background:#e2e8f0;color:#374151;border:none;border-radius:6px;cursor:pointer;font-family:'Kantumruy Pro',sans-serif;font-size:.9rem;">បិទ</button>
</div>

<div class="print-header">
    <div>
        <div class="company-name">🚚 LS Trucking Service</div>
        <div class="report-title">របាយការណ៍អតិថិជន</div>
        <div class="report-meta">
            ខែ: {{ $month }}
        </div>
    </div>
    <div class="print-date">បោះពុម្ព: {{ now()->format('d/m/Y H:i') }}</div>
</div>

<div class="stats-row">
    <div class="stat-box">
        <div class="stat-val" style="color:#3b82f6;">{{ $customerSummary->count() }}</div>
        <div class="stat-lbl">ចំនួនអតិថិជន</div>
    </div>
    <div class="stat-box">
        <div class="stat-val" style="color:#8b5cf6;">{{ $totalBookings }}</div>
        <div class="stat-lbl">ការកក់សរុប</div>
    </div>
    <div class="stat-box">
        <div class="stat-val" style="color:#10b981;">{{ $completedBookings }}</div>
        <div class="stat-lbl">ការទូទាត់បានបញ្ចប់</div>
    </div>
    <div class="stat-box">
        <div class="stat-val" style="color:#10b981;">${{ number_format($customerSummary->sum('total_paid'),2) }}</div>
        <div class="stat-lbl">ប្រាក់ទទួលបានសរុប</div>
    </div>
</div>

<div class="section-title">សង្ខេបអតិថិជន</div>
@if($customerSummary->isEmpty())
    <div class="no-data">គ្មានទិន្នន័យក្នុងខែ {{ $month }}</div>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>អតិថិជន</th>
            <th class="text-center">ការកក់</th>
            <th class="text-center">ទូទាត់បានបញ្ចប់</th>
            <th class="text-right">ប្រាក់ទទួលបាន</th>
        </tr>
    </thead>
    <tbody>
        @foreach($customerSummary as $item)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>
                <div class="customer-name-cell">{{ $item['display_name'] }}</div>
                @if($item['display_sub'])
                    <div class="customer-sub">{{ $item['display_sub'] }}</div>
                @endif
            </td>
            <td class="text-center">{{ $item['booking_count'] }} ដង</td>
            <td class="text-center">{{ $item['completed_count'] }} ការកក់</td>
            <td class="text-right" style="font-weight:700;color:#10b981;">${{ number_format($item['total_paid'],2) }}</td>
        </tr>

        {{-- Booking details for this customer --}}
        @foreach($item['bookings'] as $b)
        @php
            $hasVerified = $b->payments->where('verification_status','verified')->isNotEmpty();
            $hasPending  = $b->payments->where('verification_status','pending')->isNotEmpty();
        @endphp
        <tr>
            <td class="sub-td"></td>
            <td class="sub-td" style="padding-left:20px;">
                <span style="font-weight:700;">{{ $b->formatted_id }}</span>
                <span style="color:#94a3b8;margin-left:6px;">{{ \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') }}</span>
            </td>
            <td class="sub-td text-center" colspan="2" style="color:#475569;">
                {{ $b->pickup_location ?? '' }}
                @if($b->dropoff_location) → {{ $b->dropoff_location }} @endif
            </td>
            <td class="sub-td text-right">
                <span style="font-weight:700;">${{ number_format($b->total_price??0,2) }}</span>
                &nbsp;
                @if($hasVerified)
                    <span class="status-paid">✓ បានទូទាត់</span>
                @elseif($hasPending)
                    <span class="status-pending">⏳ រងចាំ</span>
                @else
                    <span class="status-none">— មិនទាន់</span>
                @endif
            </td>
        </tr>
        @endforeach
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="2">សរុប</td>
            <td class="text-center">{{ $totalBookings }} ដង</td>
            <td class="text-center">{{ $completedBookings }} ការកក់</td>
            <td class="text-right" style="color:#10b981;">${{ number_format($customerSummary->sum('total_paid'),2) }}</td>
        </tr>
    </tfoot>
</table>
@endif

<div style="margin-top:30px;display:flex;justify-content:space-between;font-size:.85rem;color:#64748b;">
    <div>អ្នករៀបចំ: ___________________________</div>
    <div>អ្នកអនុម័ត: ___________________________</div>
</div>

</body>
</html>
