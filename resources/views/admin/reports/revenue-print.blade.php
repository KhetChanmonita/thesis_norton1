<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <title>របាយការណ៍ចំណូល — {{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;500;600;700&family=Kantumruy Pro:wght@600;700;800&display=swap" rel="stylesheet">
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
            <div class="print-title">របាយការណ៍ចំណូល</div>
            <div class="print-month">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->locale('km')->translatedFormat('F Y') }}</div>
            <div class="print-generated">បង្កើតនៅ {{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>

    <div class="print-summary">
        <div class="print-summary-item">
            <span class="lbl">ការកក់សរុបក្នុងខែ</span>
            <span class="val">{{ $totalCount }}</span>
        </div>
        <div class="print-summary-item">
            <span class="lbl">តម្លៃសរុបនៃការកក់</span>
            <span class="val">${{ number_format($totalValue, 2) }}</span>
        </div>
    </div>

    @php
        $typeLabel   = ['import' => 'នាំចូល', 'export' => 'នាំចេញ'];
        $payLabel    = ['unpaid' => 'មិនទាន់បង់', 'deposit_paid' => 'បង់ 50%', 'fully_paid' => 'បានបង់ប្រាក់'];
        $statusLabel = ['pending' => 'រង់ចាំ', 'confirmed' => 'បានអនុម័ត', 'in_progress' => 'កំពុងដឹក', 'completed' => 'បានបញ្ចប់', 'cancelled' => 'បានលុបចោល'];
    @endphp

    <table class="print-table">
        <thead>
            <tr>
                <th>ល.រ</th>
                <th>លេខការកក់</th>
                <th>អតិថិជន</th>
                <th>ប្រភេទ</th>
                <th>តម្លៃសរុប</th>
                <th>ស្ថានភាពទូទាត់</th>
                <th>ស្ថានភាព</th>
                <th>ថ្ងៃកក់</th>
            </tr>
        </thead>
        <tbody>
            @forelse($bookings as $i => $b)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $b->formatted_id }}</td>
                <td>{{ $b->customer?->full_name ?? $b->bookedByUser?->user_name ?? '—' }}</td>
                <td>{{ $typeLabel[$b->booking_type] ?? $b->booking_type }}</td>
                <td>{{ $b->total_price ? '$'.number_format($b->total_price, 2) : '—' }}</td>
                <td>{{ $payLabel[$b->payment_status] ?? $b->payment_status }}</td>
                <td>{{ $statusLabel[$b->status] ?? $b->status }}</td>
                <td>{{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}</td>
            </tr>
            @empty
            <tr><td colspan="8" class="print-td-empty">មិនមានការកក់សម្រាប់ខែនេះ</td></tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="print-tfoot-label">សរុប</td>
                <td class="print-tfoot-val-bold">${{ number_format($totalValue, 2) }}</td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
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
