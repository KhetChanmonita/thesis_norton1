<!DOCTYPE html>
<html lang="km">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>វិក្កយបត្រ {{ $booking->formatted_id }}</title>
<link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/invoice.css') }}">
</head>
<body>

@php
    $cs   = $booking->costSheet;

    /* Extra charges — first-stage amounts are baked into booking->total_price */
    $extraCharges     = $booking->extraCharges ?? collect();
    $firstExtraTotal  = (float) $extraCharges->where('stage', 'first')->sum('amount');
    $secondExtraTotal = (float) $extraCharges->where('stage', 'second')->sum('amount');

    /*
     * Base freight = total_price minus first-stage extras.
     * cost_sheet->price sometimes stores total_price verbatim (admin copy-paste),
     * so we always derive the pure base this way to avoid double-counting.
     */
    $baseFreight = max(0, (float)($booking->total_price ?? 0) - $firstExtraTotal);

    /* Other cost-sheet add-ons (lolo, overweight, etc.) — exclude 'price' which is handled above */
    $addOnRows = [
        ['label_km' => 'LoLo',           'label_en' => 'LoLo Charge',    'field' => 'lolo'],
        ['label_km' => 'ទំងន់លើស',        'label_en' => 'Over Weight',    'field' => 'over_weight'],
        ['label_km' => 'ផ្លូវល្បឿនលឿន',   'label_en' => 'Express Way',    'field' => 'express_way'],
        ['label_km' => 'ថ្លៃបន្ថែម',       'label_en' => 'Extra',          'field' => 'extra'],
        ['label_km' => 'ត្រឡប់ទទេ',       'label_en' => 'Empty Return',   'field' => 'empty_return'],
        ['label_km' => 'រថយន្តរង់ចាំ',     'label_en' => 'Standby Truck',  'field' => 'standby_truck'],
        ['label_km' => 'ជួសជុល',          'label_en' => 'Repair',         'field' => 'repair'],
    ];
    $addOnVals = [];
    foreach ($addOnRows as $row) {
        $addOnVals[$row['field']] = $cs ? (float)($cs->{$row['field']} ?? 0) : 0;
    }
    $addOnTotal = array_sum($addOnVals);

    /* Route: fall back to booking pickup→dropoff when cost sheet has none */
    $invRoute = $cs?->route ?: collect(array_filter([$booking->pickup_location, $booking->dropoff_location]))->implode(' → ');

    /* Total = base freight + add-ons + all extra charges */
    $total = $baseFreight + $addOnTotal + $firstExtraTotal + $secondExtraTotal;
@endphp

{{-- Toolbar --}}
<div class="inv-toolbar">
    <div class="inv-toolbar-title">
        <i class="fas fa-file-invoice" style="color:#FF6B00;margin-right:6px;"></i>
        វិក្កយបត្រ — {{ $booking->formatted_id }}
    </div>
    <div class="inv-toolbar-btns">
        <a href="{{ route('admin.reports.cost-sheet', ['month' => ($booking->booking_date ?? now())->format('Y-m')]) }}"
           class="inv-btn inv-btn-ghost">
            <i class="fas fa-arrow-left"></i> ត្រឡប់
        </a>
        <button class="inv-btn inv-btn-print" onclick="window.print()">
            <i class="fas fa-print"></i> បោះពុម្ព Invoice
        </button>
    </div>
</div>

{{-- Invoice Paper --}}
<div class="inv-paper">

    {{-- Header --}}
    <div class="inv-header">
        <div>
            <div class="inv-company">
                <div class="inv-logo-box">LS</div>
                <div>
                    <div class="inv-company-name">TRUCKING SERVICE</div>
                    <div class="inv-company-sub">ប្រព័ន្ធគ្រប់គ្រងដឹកជញ្ជូន</div>
                </div>
            </div>
            <div class="inv-company-info">
                <span><i class="fas fa-map-marker-alt"></i> ផ្លូវបឹងទទឹងថ្ងៃ២ ផ្ទះលេខ ៩៨៣ ខណ្ឌជ្រោយចង្វា រាជធានីភ្នំពេញ</span>
                <span><i class="fas fa-phone"></i> 096 267 9042</span>
                <span><i class="fab fa-telegram"></i> @KCmonita11</span>
                <span><i class="fas fa-envelope"></i> khetchanmonita3@gmail.com</span>
            </div>
        </div>
        <div class="inv-meta">
            <div class="inv-title">INVOICE</div>
            <div class="inv-title-km">វិក្កយបត្រ</div>
            <div class="inv-num">INV-{{ $booking->formatted_id }}</div>
            <div class="inv-date-row">
                កាលបរិច្ឆេទ: <span>{{ now()->format('d/m/Y') }}</span>
            </div>
        </div>
    </div>

    {{-- Orange strip --}}
    <div class="inv-strip"></div>

    {{-- Bill To + Booking Details --}}
    <div class="inv-info-grid">

        {{-- Bill To --}}
        <div class="inv-info-block">
            <div class="inv-info-label"><i class="fas fa-user"></i> Bill To — អតិថិជន</div>
            <div class="inv-customer-name">{{ $booking->customer->full_name ?? '—' }}</div>
            @if($booking->customer->company_name)
            <div class="inv-customer-company">{{ $booking->customer->company_name }}</div>
            @endif
            @if($booking->customer->phone)
            <div class="inv-detail-row"><i class="fas fa-phone"></i> {{ $booking->customer->phone }}</div>
            @endif
            @if($booking->customer->email)
            <div class="inv-detail-row"><i class="fas fa-envelope"></i> {{ $booking->customer->email }}</div>
            @endif
            @if($booking->customer->address)
            <div class="inv-detail-row"><i class="fas fa-map-marker-alt"></i> {{ $booking->customer->address }}</div>
            @endif
        </div>

        {{-- Booking Details --}}
        <div class="inv-info-block">
            <div class="inv-info-label"><i class="fas fa-clipboard-list"></i> ព័ត៌មានការកក់</div>
            <div class="inv-bk-grid">
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ប្រភេទ</div>
                    <div class="inv-bk-val">
                        @if($booking->booking_type === 'import')
                            <span class="inv-type-badge inv-type-import">នាំចូល (Import)</span>
                        @else
                            <span class="inv-type-badge inv-type-export">នាំចេញ (Export)</span>
                        @endif
                    </div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ស្លាករថយន្ត</div>
                    <div class="inv-bk-val">{{ $booking->truck->plate_number ?? '—' }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">លេខកុងតឺន័រ</div>
                    <div class="inv-bk-val">{{ $booking->container_number ?: '—' }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ទីតាំង</div>
                    <div class="inv-bk-val">{{ $invRoute ?: '—' }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ទំហំ (Size)</div>
                    <div class="inv-bk-val">{{ $cs?->size ?: ($booking->container_size ?: '—') }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ថ្ងៃទទួល</div>
                    <div class="inv-bk-val">{{ $booking->pick_up_date ? $booking->pick_up_date->format('d/m/Y') : '—' }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">ថ្ងៃដល់</div>
                    <div class="inv-bk-val">{{ $booking->drop_off_date ? $booking->drop_off_date->format('d/m/Y') : '—' }}</div>
                </div>
                <div class="inv-bk-item">
                    <div class="inv-bk-key">Booking ID</div>
                    <div class="inv-bk-val" style="color:#FF6B00;">{{ $booking->formatted_id }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Cost Breakdown --}}
    <div class="inv-breakdown">


        <table class="inv-table">
            <thead>
                <tr>
                    <th>ការអធិប្បាយ / Description</th>
                    <th style="width:130px; text-align:right;">ចំនួនទឹកប្រាក់</th>
                </tr>
            </thead>
            <tbody>
                {{-- Base freight row --}}
                @if($baseFreight > 0)
                <tr>
                    <td>
                        <div class="inv-row-label">
                            <div class="inv-row-dot"></div>
                            <div>
                                <div style="font-weight:600;color:#1e293b;font-family:'Kantumruy Pro',sans-serif;">តម្លៃដឹកជញ្ជូន</div>
                            </div>
                        </div>
                    </td>
                    <td>${{ number_format($baseFreight, 2) }}</td>
                </tr>
                @endif

                {{-- Other cost-sheet add-ons (lolo, over-weight, etc.) --}}
                @foreach($addOnRows as $row)
                @php $val = $addOnVals[$row['field']]; @endphp
                @if($val > 0)
                <tr>
                    <td>
                        <div class="inv-row-label">
                            <div class="inv-row-dot"></div>
                            <div>
                                <div style="font-weight:600;color:#1e293b;font-family:'Kantumruy Pro',sans-serif;">{{ $row['label_km'] }}</div>
                            </div>
                        </div>
                    </td>
                    <td>${{ number_format($val, 2) }}</td>
                </tr>
                @endif
                @endforeach

                @foreach($extraCharges as $ec)
                <tr>
                    <td>
                        <div class="inv-row-label">
                            <div class="inv-row-dot" style="background:{{ $ec->stage === 'second' ? '#b45309' : '#0369a1' }};"></div>
                            <div>
                                <div style="font-weight:600;color:#1e293b;font-family:'Kantumruy Pro',sans-serif;">{{ $ec->reason }}</div>
                                @if($ec->stage === 'first')
                                <div style="font-size:0.72rem;color:#64748b;">(រួមបញ្ចូលក្នុងតម្លៃ 50% / included in deposit)</div>
                                @else
                                <div style="font-size:0.72rem;color:#b45309;">(ទូទាត់ពេញ / paid in full on final payment)</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td style="color:{{ $ec->stage === 'second' ? '#b45309' : '#0369a1' }};font-weight:700;">
                        +${{ number_format($ec->amount, 2) }}
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="1" class="inv-total-label">
                        <i class="fas fa-equals" style="color:#FF6B00;margin-right:8px;"></i>
                        សរុបសម្រាប់ / TOTAL AMOUNT DUE
                    </td>
                    <td>${{ number_format($total, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Signatures --}}
    <div class="inv-footer">
        <div class="inv-sig-block">
            <div class="inv-sig-label"><i class="fas fa-pen" style="margin-right:5px;"></i>ហត្ថលេខា / Customer Signature</div>

            <div class="inv-sig-line"></div>
            <div class="inv-sig-name">{{ $booking->customer->full_name ?? '—' }}</div>
        </div>
        <div class="inv-sig-block" style="align-items:flex-end;">
            <div class="inv-sig-label"><i class="fas fa-building" style="margin-right:5px;"></i>ហត្ថលេខា / Authorized Signature</div>

            <div class="inv-sig-line"></div>
            <div class="inv-sig-name">TRUCKING SERVICE</div>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="inv-bottom-bar">
        <div class="inv-bottom-note">
            អរគុណសម្រាប់ការជ្រើសរើសសេវាកម្មរបស់យើង។<br>
            Thank you for choosing Trucking Service.
        </div>
        <div class="inv-bottom-brand">TRUCKING SERVICE</div>
    </div>

</div>

<script>
// Auto-print when opened directly (optional — remove if not wanted)
// window.addEventListener('load', function() { window.print(); });
</script>
</body>
</html>
