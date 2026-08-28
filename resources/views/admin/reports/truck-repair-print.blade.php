<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>របាយការណ៍ជួសជុលរថយន្ត — {{ $month }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Kantumruy Pro', sans-serif; font-size: 13px; color: #1e293b; background: #fff; padding: 24px; }
        .print-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px; border-bottom: 2px solid #f59e0b; padding-bottom: 14px; }
        .company-name { font-size: 1.2rem; font-weight: 700; color: #92400e; }
        .report-title { font-size: 1rem; font-weight: 700; margin-top: 4px; }
        .report-meta { font-size:.85rem; color:#64748b; margin-top:3px; }
        .print-date { font-size:.8rem; color:#94a3b8; text-align:right; }
        .stats-row { display: flex; gap: 16px; margin-bottom: 18px; }
        .stat-box { flex: 1; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; text-align: center; }
        .stat-val { font-size: 1.4rem; font-weight: 800; }
        .stat-lbl { font-size: .78rem; color: #64748b; margin-top: 2px; }
        .section-title { font-size: .9rem; font-weight: 700; margin: 16px 0 8px; color: #374151; border-left: 3px solid #f59e0b; padding-left: 8px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 16px; }
        th { background: #fef3c7; color: #92400e; font-weight: 700; padding: 8px 10px; text-align: left; border: 1px solid #fde68a; font-size:.85rem; }
        td { padding: 7px 10px; border: 1px solid #e2e8f0; vertical-align: top; }
        tr:nth-child(even) td { background: #fafafa; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .total-row td { font-weight: 700; background: #fff7ed !important; border-top: 2px solid #f59e0b; }
        .amount-red { color: #dc2626; font-weight: 700; }
        .truck-cell strong { display: block; }
        .truck-cell small { color: #64748b; font-size: .8rem; }
        .no-data { text-align: center; color: #94a3b8; padding: 24px; }
        @media print {
            body { padding: 12px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>

<div class="no-print" style="margin-bottom:16px;">
    <button onclick="window.print()" style="padding:8px 20px;background:#f59e0b;color:#fff;border:none;border-radius:6px;cursor:pointer;font-family:'Kantumruy Pro',sans-serif;font-size:.9rem;">
        &#128424; បោះពុម្ព
    </button>
    <button onclick="window.close()" style="margin-left:8px;padding:8px 16px;background:#e2e8f0;color:#374151;border:none;border-radius:6px;cursor:pointer;font-family:'Kantumruy Pro',sans-serif;font-size:.9rem;">
        បិទ
    </button>
</div>

<div class="print-header">
    <div>
        <div class="company-name">&#128666; Trucking Service</div>
        <div class="report-title">របាយការណ៍ជួសជុលរថយន្ត</div>
        <div class="report-meta">
            ខែ: {{ $month }}
            @if($selectedTruck)
                &nbsp;·&nbsp; រថយន្ត: {{ $selectedTruck->truck_name }} ({{ $selectedTruck->plate_number }})
            @else
                &nbsp;·&nbsp; រថយន្តទាំងអស់
            @endif
        </div>
    </div>
    <div class="print-date">
        បោះពុម្ព: {{ now()->format('d/m/Y H:i') }}
    </div>
</div>

{{-- Stats --}}
<div class="stats-row">
    <div class="stat-box">
        <div class="stat-val" style="color:#ea580c;">{{ $repairCount }}</div>
        <div class="stat-lbl">ចំនួនការជួសជុល</div>
    </div>
    <div class="stat-box">
        <div class="stat-val" style="color:#dc2626;">${{ number_format($grandTotal,2) }}</div>
        <div class="stat-lbl">ចំណាយសរុប</div>
    </div>
    <div class="stat-box">
        @php
            $avgCost = $repairCount > 0 ? $grandTotal / $repairCount : 0;
        @endphp
        <div class="stat-val" style="color:#7c3aed;">${{ number_format($avgCost,2) }}</div>
        <div class="stat-lbl">មធ្យមក្នុងមួយដង</div>
    </div>
</div>

{{-- Per-truck summary --}}
@php
    $byTruck = $repairs->groupBy('truck_id');
@endphp
@if($byTruck->isNotEmpty())
<div class="section-title">សង្ខេបតាមរថយន្ត</div>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>រថយន្ត</th>
            <th class="text-center">ចំនួនដង</th>
            <th class="text-end">ចំណាយសរុប</th>
        </tr>
    </thead>
    <tbody>
        @foreach($byTruck as $tid => $group)
        @php $truck = $group->first()->truck; @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td class="truck-cell">
                <strong>{{ $truck->truck_name ?? 'N/A' }}</strong>
                <small>{{ $truck->plate_number ?? '' }}</small>
            </td>
            <td class="text-center">{{ $group->count() }} ដង</td>
            <td class="text-end amount-red">${{ number_format($group->sum('amount'),2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="2" class="text-end">សរុប</td>
            <td class="text-center">{{ $repairCount }} ដង</td>
            <td class="text-end amount-red">${{ number_format($grandTotal,2) }}</td>
        </tr>
    </tfoot>
</table>
@endif

{{-- Detailed list --}}
<div class="section-title">បញ្ជីការជួសជុលលម្អិត</div>
@if($repairs->isEmpty())
    <div class="no-data">មិនមានការជួសជុលក្នុងខែ {{ $month }}</div>
@else
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>រថយន្ត</th>
            <th>កាលបរិច្ឆេទ</th>
            <th>ការពិពណ៌នា</th>
            <th class="text-end">ចំណាយ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($repairs as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td class="truck-cell">
                <strong>{{ $r->truck->truck_name ?? 'N/A' }}</strong>
                <small>{{ $r->truck->plate_number ?? '' }}</small>
            </td>
            <td>{{ \Carbon\Carbon::parse($r->expense_date)->format('d/m/Y') }}</td>
            <td>{{ $r->description ?? '—' }}</td>
            <td class="text-end amount-red">${{ number_format($r->amount,2) }}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr class="total-row">
            <td colspan="4" class="text-end">ចំណាយសរុប</td>
            <td class="text-end amount-red">${{ number_format($grandTotal,2) }}</td>
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
