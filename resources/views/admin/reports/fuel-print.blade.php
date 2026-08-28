<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>របាយការណ៍ប្រេងឥន្ធនៈ — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin_reports_print.css') }}">
    <style>
        .fuel-summary-grid { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:20px; }
        .fuel-sum-card { background:#fff; border-radius:10px; padding:14px 16px; border:1.5px solid #f1f5f9; }
        .fuel-sum-name { font-weight:700; font-size:.85rem; color:#1e293b; margin-bottom:8px; display:flex; align-items:center; gap:6px; }
        .fuel-sum-row { display:flex; justify-content:space-between; font-size:.76rem; color:#64748b; padding:2px 0; border-bottom:1px solid #f8fafc; }
        .fuel-sum-row:last-child { border:none; font-weight:700; color:#1e293b; font-size:.8rem; }
        .fuel-sum-val { font-family:'Montserrat',sans-serif; font-weight:700; }
        .fuel-sum-val.orange { color:#FF6B00; }
        .fuel-sum-val.blue   { color:#3b82f6; }
        .fuel-sum-val.green  { color:#059669; }
        .fuel-section-title { font-size:.8rem; font-weight:700; color:#FF6B00; margin:18px 0 10px; display:flex; align-items:center; gap:6px; }
        @media print {
            .print-toolbar { display:none !important; }
            .fuel-summary-grid { grid-template-columns:repeat(3,1fr); }
        }
    </style>
</head>
<body>

<div class="print-toolbar">
    <button onclick="window.print()" class="print-btn">Print</button>
    <button onclick="window.close()" class="print-btn-ghost">Close</button>
</div>

<div class="print-page">

    {{-- Header --}}
    <div class="print-header">
        <div class="print-brand">
            <img src="{{ asset('images/trucking-logo.png') }}" alt="logo">
            <div>
                <div class="print-brand-name">LS Trucking Service</div>
                <div class="print-brand-sub">ផ្លូវបឹងទទឹងថ្ងៃ២ ផ្ទះលេខ ៩៨៣ ខណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</div>
            </div>
        </div>
        <div class="print-meta">
            <div class="print-title">របាយការណ៍ប្រេងឥន្ធនៈ</div>
            <div class="print-month">
                {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}
                @if($selectedTruck) — {{ $selectedTruck->truck_name }} ({{ $selectedTruck->plate_number }}) @endif
            </div>
            <div class="print-generated">បង្កើតនៅ {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    {{-- Summary stats --}}
    <div class="print-summary">
        <div class="print-summary-item">
            <span class="lbl">ចំណាយប្រេងសរុប</span>
            <span class="val" style="color:#FF6B00;">${{ number_format($grandFuel, 2) }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">លុយជើងតៃកុង</span>
            <span class="val" style="color:#3b82f6;">${{ number_format($grandAllowance, 2) }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">ចំណាយសរុប</span>
            <span class="val" style="color:#059669;">${{ number_format($grandTotal, 2) }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">ចំនួនការដឹក</span>
            <span class="val">{{ $fuels->count() }} ដំណើរ</span>
        </div>
    </div>

    {{-- Detail table --}}
    <div class="fuel-section-title"><i class="fas fa-gas-pump"></i> លម្អិតប្រេងឥន្ធនៈ</div>
    <table class="print-table">
        <thead>
            <tr>
                <th>ល.រ</th>
                <th>ការកក់</th>
                <th>រថយន្ត</th>
                <th>អ្នកបើកបរ</th>
                <th>ចំណាយប្រេង</th>
                <th>លុយជើងតៃកុង</th>
                <th>សរុប</th>
                <th>កាលបរិច្ឆេទ</th>
                <th>បរិយាយ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($fuels as $i => $f)
            @php
                $bk = $f->booking;
                $bookingLabel = $bk ? 'LS'.$bk->booking_date->format('ym').'-'.$bk->booking_id : null;
                $rowTotal = $f->amount + ($f->driver_allowance ?? 0);
            @endphp
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                    @if($bookingLabel)
                        <strong style="color:#c2410c;">{{ $bookingLabel }}</strong>
                        @if($bk->customer || $bk->bookedByUser)
                        <div style="font-size:.72rem;color:#64748b;">{{ $bk->customer?->full_name ?? $bk->bookedByUser?->user_name }}</div>
                        @endif
                    @else
                        <span style="color:#94a3b8;">—</span>
                    @endif
                </td>
                <td>
                    @if($f->truck)
                        {{ $f->truck->truck_name }}<br>
                        <span style="font-size:.72rem;color:#64748b;">{{ $f->truck->plate_number }}</span>
                    @else —
                    @endif
                </td>
                <td>{{ $f->driver?->full_name ?? '—' }}</td>
                <td style="color:#FF6B00;font-family:'Montserrat',sans-serif;font-weight:700;">
                    ${{ number_format($f->amount, 2) }}
                </td>
                <td style="color:#3b82f6;font-family:'Montserrat',sans-serif;font-weight:700;">
                    {{ $f->driver_allowance > 0 ? '$'.number_format($f->driver_allowance, 2) : '—' }}
                </td>
                <td style="color:#059669;font-family:'Montserrat',sans-serif;font-weight:700;">
                    ${{ number_format($rowTotal, 2) }}
                </td>
                <td>{{ $f->expense_date ? $f->expense_date->format('d/m/Y') : '—' }}</td>
                <td style="font-size:.78rem;color:#64748b;">{{ $f->description ?? '—' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" style="text-align:center;padding:32px;color:#94a3b8;">មិនមានទិន្នន័យ</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" style="text-align:right;font-weight:700;">សរុប</td>
                <td style="color:#FF6B00;font-family:'Montserrat',sans-serif;font-weight:700;">${{ number_format($grandFuel, 2) }}</td>
                <td style="color:#3b82f6;font-family:'Montserrat',sans-serif;font-weight:700;">${{ number_format($grandAllowance, 2) }}</td>
                <td style="color:#059669;font-family:'Montserrat',sans-serif;font-weight:700;">${{ number_format($grandTotal, 2) }}</td>
                <td colspan="2"></td>
            </tr>
        </tfoot>
    </table>

</div>
</body>
</html>
