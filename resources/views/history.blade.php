<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ប្រវត្តិការកក់ - LS Trucking Service</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my_booking.css') }}">
    <link rel="stylesheet" href="{{ asset('css/history.css') }}">
</head>
<body>
@include('partials.header')

{{-- Hero Banner --}}
<div class="history-hero">
    <div class="hero-icon"><i class="fas fa-history"></i></div>
    <h1>ប្រវត្តិការកក់</h1>
    <p>ការកក់ និងប្រវត្តិការទូទាត់ទាំងអស់របស់អ្នក</p>
</div>

<div class="page-wrap" style="padding-top: 32px;">

    @if(!Auth::user()->phone)
    <div class="no-phone-notice">
        <i class="fas fa-exclamation-triangle"></i>
        <span>
            គណនីរបស់អ្នកមិនទាន់មានលេខទូរស័ព្ទ។
            <a href="{{ route('profile') }}">បន្ថែមលេខទូរស័ព្ទ</a>
            ដើម្បីមើលការកក់ទាំងអស់ដែលប្រើលេខទូរស័ព្ទនោះ។
        </span>
    </div>
    @endif

    @if($bookings->isEmpty())

    {{-- Empty state --}}
    <div class="empty-state">
        <i class="fas fa-clipboard-list"></i>
        <h3>មិនទាន់មានប្រវត្តិការកក់</h3>
        <p>អ្នកមិនទាន់បានកក់រថយន្តណាមួយ<br>ចាប់ផ្ដើមកក់ឥឡូវដើម្បីប្រើសេវាកម្មរបស់យើង!</p>
        <a href="{{ route('trucks_section') }}" class="btn-book-now">
            <i class="fas fa-truck"></i> កក់រថយន្ត
        </a>
    </div>

    @else

    {{-- Summary cards --}}
    <div class="summary-grid">
        <div class="sum-card">
            <div class="sum-icon blue"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="sum-val">{{ $bookings->count() }}</div>
                <div class="sum-lbl">ការកក់សរុប</div>
            </div>
        </div>
        <div class="sum-card">
            <div class="sum-icon orange"><i class="fas fa-clock"></i></div>
            <div>
                <div class="sum-val">{{ $pendingCount }}</div>
                <div class="sum-lbl">រង់ចាំការទូទាត់</div>
            </div>
        </div>
        <div class="sum-card">
            <div class="sum-icon green"><i class="fas fa-dollar-sign"></i></div>
            <div>
                <div class="sum-val">${{ number_format($totalPaid, 2) }}</div>
                <div class="sum-lbl">ទូទាត់សរុប</div>
            </div>
        </div>
    </div>

    {{-- Filter bar --}}
    <div class="filter-bar" id="filterBar">
        <button class="filter-btn active" data-filter="all">ទាំងអស់ ({{ $bookings->count() }})</button>
        @foreach(['pending' => 'រង់ចាំ', 'confirmed' => 'បានអនុម័ត', 'in_progress' => 'កំពុងដឹក', 'completed' => 'បានបញ្ចប់', 'cancelled' => 'បានបោះបង់'] as $val => $lbl)
            @if($bookings->where('status', $val)->count())
                <button class="filter-btn" data-filter="{{ $val }}">
                    {{ $lbl }} ({{ $bookings->where('status', $val)->count() }})
                </button>
            @endif
        @endforeach
    </div>

    {{-- Booking list --}}
    <div class="section-hdr">
        <i class="fas fa-list-alt"></i> ការកក់របស់ខ្ញុំ
    </div>

    @foreach($bookings as $b)
    @php
        $statusLabel = [
            'pending'     => 'រង់ចាំ',
            'confirmed'   => 'បានអនុម័ត',
            'in_progress' => 'កំពុងដឹក',
            'completed'   => 'បានបញ្ចប់',
            'cancelled'   => 'បានបោះបង់',
        ];
        $payLabel = [
            'unpaid'       => 'មិនទាន់បង់',
            'deposit_paid' => 'បង់ 50%',
            'fully_paid'   => 'បានបង់ពេញ',
        ];
        $typeLabel = $b->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ';
    @endphp
    <div class="booking-card" data-status="{{ $b->status }}">
        <div class="booking-card-header">
            <div>
                <div class="booking-id">
                    <i class="fas fa-hashtag" style="color:#FF6B00;font-size:0.75rem;"></i>
                    ការកក់ #{{ $b->booking_id }}
                </div>
                <div class="booking-date">
                    <i class="fas fa-calendar" style="margin-right:3px;"></i>
                    {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}
                    @if($b->customer) &nbsp;·&nbsp; {{ $b->customer->full_name }} @endif
                </div>
            </div>
            <div class="badges">
                <span class="badge badge-type">{{ $typeLabel }}</span>
                <span class="badge badge-{{ $b->status }}">
                    <i class="fas fa-circle" style="font-size:0.4rem;"></i>
                    {{ $statusLabel[$b->status] ?? $b->status }}
                </span>
                <span class="badge badge-{{ $b->payment_status }}">
                    <i class="fas fa-{{ $b->payment_status === 'fully_paid' ? 'check-circle' : ($b->payment_status === 'deposit_paid' ? 'circle-half-stroke' : 'times-circle') }}" style="font-size:0.65rem;"></i>
                    {{ $payLabel[$b->payment_status] ?? $b->payment_status }}
                </span>
            </div>
        </div>

        <div class="booking-card-body">
            <div class="booking-info">
                <div class="route-row">
                    <i class="fas fa-map-marker-alt" style="color:#FF6B00;font-size:0.75rem;"></i>
                    <span class="loc">{{ Str::limit($b->pickup_location ?? '—', 35) }}</span>
                    <i class="fas fa-long-arrow-alt-right route-arrow"></i>
                    <i class="fas fa-map-marker-alt" style="color:#10b981;font-size:0.75rem;"></i>
                    <span class="loc">{{ Str::limit($b->dropoff_location ?? '—', 35) }}</span>
                    @if($b->dropoff_location_link)
                        <a href="{{ $b->dropoff_location_link }}" target="_blank" rel="noopener" style="color:#FF6B00;" title="មើលលើ Google Maps">
                            <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i>
                        </a>
                    @endif
                </div>
                <div class="meta-row">
                    @if($b->pick_up_date)
                    <div class="meta-item">
                        <i class="fas fa-calendar-alt"></i>
                        ទទួល: {{ \Carbon\Carbon::parse($b->pick_up_date)->format('d/m/Y') }}
                    </div>
                    @endif
                    @if($b->drop_off_date)
                    <div class="meta-item">
                        <i class="fas fa-calendar-check"></i>
                        ដឹង: {{ \Carbon\Carbon::parse($b->drop_off_date)->format('d/m/Y') }}
                    </div>
                    @endif
                    @if($b->cargo_weight)
                    <div class="meta-item">
                        <i class="fas fa-weight-hanging"></i>
                        {{ number_format($b->cargo_weight) }} kg
                    </div>
                    @endif
                    @if($b->total_price)
                    <div class="meta-item">
                        <i class="fas fa-dollar-sign"></i>
                        <strong style="color:#FF6B00;">${{ number_format($b->total_price, 2) }}</strong>
                    </div>
                    @endif
                    @if($b->container_number)
                    <div class="meta-item">
                        <i class="fas fa-box"></i>
                        {{ $b->container_number }}
                    </div>
                    @endif
                </div>
                @if($b->payments->isNotEmpty())
                <div class="meta-row">
                    @foreach($b->payments as $pay)
                    <div class="meta-item" style="background:#f0fdf4;padding:3px 8px;border-radius:6px;color:#059669;">
                        <i class="fas fa-check"></i>
                        ${{ number_format($pay->amount, 2) }}
                        — {{ \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') }}
                        ({{ ucfirst($pay->payment_method ?? '—') }})
                    </div>
                    @endforeach
                </div>
                @endif
            </div>

            <div class="booking-actions">
                <a href="{{ route('booking.track', ['id'=>$b->booking_id,'token'=>$b->access_token]) }}"
                   class="btn-track">
                    <i class="fas fa-search"></i> តាមដាន
                </a>
                @if($b->payment_status === 'unpaid' && $b->status === 'confirmed')
                <a href="{{ route('booking.pay', ['id'=>$b->booking_id,'token'=>$b->access_token]) }}"
                   class="btn-pay-deposit">
                    <i class="fas fa-credit-card"></i>
                    បង់ 50%@if($b->total_price) (${{ number_format($b->total_price*0.5,2) }})@endif
                </a>
                @elseif($b->payment_status === 'deposit_paid' && $b->status === 'completed')
                <a href="{{ route('booking.pay', ['id'=>$b->booking_id,'token'=>$b->access_token]) }}"
                   class="btn-pay-final">
                    <i class="fas fa-check-circle"></i>
                    បង់ 50% ចុង@if($b->total_price) (${{ number_format($b->total_price*0.5,2) }})@endif
                </a>
                @elseif($b->payment_status === 'fully_paid')
                <div class="btn-paid">
                    <i class="fas fa-check-circle"></i> បានបង់ពេញ
                </div>
                @endif
            </div>
        </div>
    </div>
    @endforeach

    {{-- Payment history table --}}
    @php $allPayments = $bookings->flatMap->payments->sortByDesc('payment_date'); @endphp
    @if($allPayments->isNotEmpty())
    <div class="section-hdr" style="margin-top:8px;">
        <i class="fas fa-history"></i> ប្រវត្តិការទូទាត់
    </div>
    <div class="card">
        <table class="pay-table">
            <thead>
                <tr>
                    <th>ការកក់</th>
                    <th>ចំនួន</th>
                    <th>វិធីទូទាត់</th>
                    <th>លេខយោង</th>
                    <th>កាលបរិច្ឆេទ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($allPayments as $pay)
                @php
                    $icons = ['cash'=>'fa-money-bill-wave','acleda'=>'fa-university','aba'=>'fa-mobile-alt',
                              'wing'=>'fa-mobile-alt','bank_transfer'=>'fa-exchange-alt','khqr'=>'fa-qrcode'];
                    $icon  = $icons[$pay->payment_method ?? ''] ?? 'fa-coins';
                @endphp
                <tr>
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-weight:700;color:#FF6B00;
                                     background:#fff3e8;padding:2px 8px;border-radius:5px;font-size:0.78rem;">
                            #{{ $pay->booking_id }}
                        </span>
                    </td>
                    <td>
                        <strong style="font-family:'Montserrat',sans-serif;color:#10b981;font-size:0.95rem;">
                            ${{ number_format($pay->amount, 2) }}
                        </strong>
                    </td>
                    <td>
                        <i class="fas {{ $icon }}" style="color:#FF6B00;margin-right:5px;font-size:0.75rem;"></i>
                        {{ ucfirst(str_replace('_', ' ', $pay->payment_method ?? '—')) }}
                    </td>
                    <td>
                        <span style="font-family:'Montserrat',sans-serif;font-size:0.75rem;color:#64748b;
                                     background:#f8fafc;padding:2px 8px;border-radius:5px;border:1px solid #e2e8f0;">
                            {{ $pay->transaction_reference ?? '—' }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:#475569;">
                        {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div style="text-align:center;margin-top:8px;">
        <a href="{{ route('trucks_section') }}" class="btn-new-booking">
            <i class="fas fa-plus"></i> កក់ថ្មី
        </a>
    </div>

    @endif {{-- end $bookings->isEmpty() --}}

</div>

@include('partials.footer')

<script>
document.addEventListener('DOMContentLoaded', function () {
    var filterBar = document.getElementById('filterBar');
    if (!filterBar) return;

    filterBar.addEventListener('click', function (e) {
        var btn = e.target.closest('.filter-btn');
        if (!btn) return;

        filterBar.querySelectorAll('.filter-btn').forEach(function (b) {
            b.classList.remove('active');
        });
        btn.classList.add('active');

        var filter = btn.dataset.filter;
        document.querySelectorAll('.booking-card').forEach(function (card) {
            if (filter === 'all' || card.dataset.status === filter) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
});
</script>
</body>
</html>
