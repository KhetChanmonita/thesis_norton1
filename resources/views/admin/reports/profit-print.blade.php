<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>របាយការណ៍ប្រាក់ចំណេញ — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin_reports_print.css') }}">
</head>
<body>

<div class="print-toolbar">
    <button onclick="window.print()" class="print-btn">Print</button>
    <button onclick="window.close()" class="print-btn-ghost">Close</button>
</div>

<div class="print-page">
    <div class="print-header">
        <div class="print-brand">
            <img src="{{ asset('images/trucking-logo.png') }}" alt="logo">
            <div>
                <div class="print-brand-name">LS Trucking Service</div>
                <div class="print-brand-sub">ផ្លូវបឹងទទឹងថ្ងៃ២ ផ្ទះលេខ ៩៨៣ ខណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</div>
            </div>
        </div>
        <div class="print-meta">
            <div class="print-title">របាយការណ៍ប្រាក់ចំណេញ</div>
            <div class="print-month">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}</div>
            <div class="print-generated">បង្កើតនៅ {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="print-summary">
        <div class="print-summary-item">
            <span class="lbl">ចំណូលសរុប</span>
            <span class="val print-val-green">${{ number_format($totalRevenue, 2) }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">ចំណាយសរុប</span>
            <span class="val print-val-red">${{ number_format($totalExpense, 2) }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">ប្រាក់ចំណេញ</span>
            <span class="val" style="color:{{ $profit >= 0 ? '#059669' : '#dc2626' }};">
                {{ $profit >= 0 ? '' : '-' }}${{ number_format(abs($profit), 2) }}
            </span>
        </div>
    </div>

    <h3 class="print-section-h3">ចំណូល — ការទូទាត់</h3>
    <table class="print-table print-table-mb">
        <thead>
            <tr>
                <th>ល.រ</th>
                <th>លេខការកក់</th>
                <th>អតិថិជន</th>
                <th>ដំណាក់កាល</th>
                <th>ចំនួនទឹកប្រាក់</th>
                <th>កាលបរិច្ឆេទ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $i => $pay)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $pay->booking?->formatted_id ?? '#'.$pay->booking_id }}</td>
                <td>{{ $pay->booking?->customer?->full_name ?? $pay->booking?->bookedByUser?->user_name ?? '—' }}</td>
                <td>{{ $pay->payment_stage === 'first' ? 'លើកទី១' : ($pay->payment_stage === 'second' ? 'លើកទី២' : '—') }}</td>
                <td>${{ number_format($pay->amount, 2) }}</td>
                <td>{{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="print-td-empty">មិនមានការទូទាត់ក្នុងខែនេះ</td></tr>
            @endforelse
        </tbody>
        @if($payments->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="4" class="print-tfoot-label">សរុបចំណូល</td>
                <td class="print-tfoot-val-bold print-val-green">${{ number_format($totalRevenue, 2) }}</td>
                <td></td>
            </tr>
        </tfoot>
        @endif
    </table>

    @php
        $typeLabel = ['salary' => 'ប្រាក់ខែអ្នកបើកបរ', 'fuel' => 'ប្រេងឥន្ធនៈ', 'repair' => 'ជួសជុលរថយន្ត', 'other' => 'ផ្សេងៗ'];
    @endphp

    <h3 class="print-section-h3">ចំណាយ</h3>
    <table class="print-table">
        <thead>
            <tr>
                <th>ល.រ</th>
                <th>ប្រភេទ</th>
                <th>ចំនួនទឹកប្រាក់</th>
                <th>អ្នកបើកបរ / រថយន្ត</th>
                <th>បរិយាយ</th>
                <th>កាលបរិច្ឆេទ</th>
            </tr>
        </thead>
        <tbody>
            @forelse($expenses as $i => $e)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $typeLabel[$e->expense_type] ?? $e->expense_type }}</td>
                <td>${{ number_format($e->amount, 2) }}</td>
                <td>{{ $e->driver->full_name ?? ($e->truck->truck_name ?? '—') }}</td>
                <td>{{ $e->description ?: '—' }}</td>
                <td>{{ $e->expense_date ? $e->expense_date->format('d/m/Y') : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="6" class="print-td-empty">មិនមានចំណាយក្នុងខែនេះ</td></tr>
            @endforelse
        </tbody>
        @if($expenses->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="2" class="print-tfoot-label">សរុបចំណាយ</td>
                <td class="print-tfoot-val-bold print-val-red">${{ number_format($totalExpense, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
        @endif
    </table>

    <div class="print-footer">
        <div>ហត្ថលេខាអ្នកគ្រប់គ្រង _____________________</div>
        <div>LS Trucking Service &copy; {{ now()->year }}</div>
    </div>
</div>

<script>
    window.onload = function () { window.print(); };
</script>
</body>
</html>
