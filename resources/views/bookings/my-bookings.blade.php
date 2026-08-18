<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ការកក់ និងការទូទាត់របស់ខ្ញុំ</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/my_booking.css') }}">
</head>
<body>
@include('partials.header')

<div class="page-wrap">

    {{-- Page title --}}
    <div class="page-title">
        <h1><i class="fas fa-receipt mybk-title-icon"></i>ការកក់ និងការទូទាត់របស់ខ្ញុំ</h1>
        <p>តាមដានការកក់ ស្ថានភាព និងប្រវត្តិការទូទាត់</p>
    </div>

    {{-- ── Phone / container lookup ── --}}
    <div class="lookup-box">
        <div class="lookup-title">
            <i class="fas fa-search"></i>
            ស្វែងរកការកក់ដោយលេខទូរកុងតឺន័រ
        </div>
        <form method="GET" action="{{ route('my.bookings') }}" class="lookup-form">
            <div class="lookup-input-wrap">
                <i class="fas fa-box"></i>
                <input type="text" name="container_number" value="{{ $searchContainer }}"
                       placeholder="បញ្ចូលលេខកុងតឺន័រ"
                       class="lookup-input">
            </div>
            <button type="submit" class="lookup-btn">
                <i class="fas fa-search"></i> ស្វែងរក
            </button>
            @if($searchContainer)
            <a href="{{ route('my.bookings') }}" class="lookup-clear">
                <i class="fas fa-times"></i>
            </a>
            @endif
        </form>
        @if($searched && $bookings->isEmpty())
        <div class="lookup-not-found">
            <i class="fas fa-exclamation-triangle"></i>
            មិនមានការកក់សម្រាប់លេខកុងតឺន័រ <strong>{{ $searchContainer }}</strong>
        </div>
        @endif
    </div>

    @if($bookings->isEmpty() && !$searched)

    {{-- Empty state --}}
    <div class="empty-state">
        <i class="fas fa-truck"></i>
        <h3>មិនទាន់មានការកក់</h3>
        <p>បញ្ចូលលេខទូរស័ព្ទ ឬ លេខកុងតឺន័រខាងលើ ដើម្បីមើលការកក់របស់អ្នក<br>ឬចាប់ផ្ដើមកក់រថយន្តដឹកទំនិញឥឡូវ!</p>
        <a href="{{ route('trucks_section') }}" class="btn-book-now">
            <i class="fas fa-truck"></i> កក់រថយន្ត
        </a>
    </div>

    @elseif(!$bookings->isEmpty())

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
            'cancelled'   => 'បានលុប',
        ];
        $payLabel  = [
            'unpaid'       => 'មិនទាន់បង់',
            'deposit_paid' => 'បង់ 50%',
            'fully_paid'   => 'បានបង់ប្រាក់',
        ];
        $typeLabel = $b->booking_type === 'import' ? 'នាំចូល' : 'នាំចេញ';
    @endphp
    <div class="booking-card">
        <div class="booking-card-header">
            <div>
                <div class="booking-id">
                    ការកក់ {{ $b->formatted_id }}
                </div>
                <div class="booking-date">
                    <i class="fas fa-calendar mybk-cal-icon"></i>
                    {{ $b->booking_date ? \Carbon\Carbon::parse($b->booking_date)->format('d/m/Y') : '—' }}
                    @if($b->customer) &nbsp;·&nbsp; {{ $b->customer->full_name }} @endif
                </div>
            </div>
            <div class="badges">
                <span class="badge badge-type">{{ $typeLabel }}</span>
                <span class="badge badge-{{ $b->status }}">
                    <i class="fas fa-circle mybk-status-dot"></i>
                    {{ $statusLabel[$b->status] ?? $b->status }}
                </span>
                <span class="badge badge-{{ $b->payment_status }}">
                    <i class="fas fa-{{ $b->payment_status === 'fully_paid' ? 'check-circle' : ($b->payment_status === 'deposit_paid' ? 'circle-half-stroke' : 'times-circle') }} mybk-pay-icon"></i>
                    {{ $payLabel[$b->payment_status] ?? $b->payment_status }}
                </span>
            </div>
        </div>

        <div class="booking-card-body">
            <div class="booking-info">
                <div class="route-row">
                    <i class="fas fa-map-marker-alt mybk-pickup-icon"></i>
                    <span class="loc">{{ Str::limit($b->pickup_location ?? '—', 35) }}</span>
                    <i class="fas fa-long-arrow-alt-right route-arrow"></i>
                    <i class="fas fa-map-marker-alt mybk-dropoff-icon"></i>
                    <span class="loc">{{ Str::limit($b->dropoff_location ?? '—', 35) }}</span>
                    @if($b->dropoff_location_link)
                        <a href="{{ $b->dropoff_location_link }}" target="_blank" rel="noopener" class="mybk-maps-link" title="មើលលើ Google Maps">
                            <i class="fas fa-external-link-alt mybk-maps-icon"></i>
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
                        <strong class="mybk-price-val">${{ number_format($b->total_price, 2) }}</strong>
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
                    <div class="meta-item mybk-pay-meta">
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

    {{-- Payment history --}}
    @php $allPayments = $bookings->flatMap->payments->filter(fn($p) => $p->verification_status !== 'rejected')->sortByDesc('payment_date'); @endphp
    @if($allPayments->isNotEmpty())
    <div class="section-hdr mybk-history-hdr">
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
                        <span class="mybk-pay-id">
                            {{ $pay->booking?->formatted_id ?? '#'.$pay->booking_id }}
                        </span>
                    </td>
                    <td>
                        <strong class="mybk-pay-amount">
                            ${{ number_format($pay->amount, 2) }}
                        </strong>
                    </td>
                    <td>
                        <i class="fas {{ $icon }} mybk-pay-method-icon"></i>
                        {{ ucfirst(str_replace('_', ' ', $pay->payment_method ?? '—')) }}
                    </td>
                    <td>
                        <span class="mybk-pay-ref">
                            {{ $pay->transaction_reference ?? '—' }}
                        </span>
                    </td>
                    <td class="mybk-pay-date">
                        {{ $pay->payment_date ? \Carbon\Carbon::parse($pay->payment_date)->format('d/m/Y') : '—' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <div class="mybk-bottom-center">
        <a href="{{ route('trucks_section') }}" class="btn-new-booking">
            <i class="fas fa-plus"></i> កក់ថ្មី
        </a>
    </div>

    @endif {{-- end !$bookings->isEmpty() --}}

</div>

</body>
</html>
