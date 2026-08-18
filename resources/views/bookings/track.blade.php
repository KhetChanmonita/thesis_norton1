<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ការតាមដានការកក់ {{ $booking->formatted_id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/track.css') }}">
</head>
<body>
@include('partials.header')
<div class="page-wrap">

@if(session('payment_success'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('payment_success') }}
</div>
@endif

@if(session('extra_charge_response'))
<div class="alert alert-success">
    <i class="fas fa-check-circle"></i> {{ session('extra_charge_response') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-alt trk-hdr-icon"></i><span class="trk-title-text">ការតាមដានការកក់</span> <span class="trk-booking-id">{{ $booking->formatted_id }}</span></h2>
        <p>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }} — {{ $booking->customer->full_name ?? '' }}</p>
    </div>
    <div class="card-body">
        <div class="info-grid">
            <div class="info-item">
                <label>ស្ថានភាព</label>
                @php
                    $statusLabel = ['pending'=>'រង់ចាំ','confirmed'=>'បានអនុម័ត','in_progress'=>'កំពុងដឹក','completed'=>'បានបញ្ចប់','cancelled'=>'បានលុបចោល'];
                    $hasPendingFirst   = $booking->payment_status === 'deposit_paid' && $booking->status === 'confirmed';
                    $hasPendingSecond  = $booking->status === 'completed'
                        && $booking->payments->where('payment_stage', 'second')->where('verification_status', 'pending')->isNotEmpty();
                    $hasRejectedFirst  = $booking->payments->where('payment_stage', 'first')->where('verification_status', 'rejected')->isNotEmpty();
                    $hasRejectedSecond = $booking->payments->where('payment_stage', 'second')->where('verification_status', 'rejected')->isNotEmpty();
                    if ($hasPendingFirst) {
                        $displayStatus = 'កំពុងរង់ចាំ';
                    } elseif ($hasPendingSecond) {
                        $displayStatus = 'កំពុងដំណើរការ';
                    } else {
                        $displayStatus = $statusLabel[$booking->status] ?? $booking->status;
                    }
                @endphp
                <span class="badge badge-{{ $booking->status }}">
                    <i class="fas fa-circle trk-status-dot"></i>
                    {{ $displayStatus }}
                </span>
            </div>
            <div class="info-item">
                <label>ប្រភេទ</label>
                <span>{{ $booking->booking_type === 'import' ? 'នាំចូល (Import)' : 'នាំចេញ (Export)' }}</span>
            </div>
            <div class="info-item">
                <label>ទីតាំងទទួល</label>
                <span><i class="fas fa-map-marker-alt trk-pickup-icon"></i>{{ $booking->pickup_location ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>ទីតាំងដឹកទៅ</label>
                <span>
                    <i class="fas fa-map-marker-alt trk-dropoff-icon"></i>{{ $booking->dropoff_location ?? '—' }}
                    @if($booking->dropoff_location_link)
                        <a href="{{ $booking->dropoff_location_link }}" target="_blank" rel="noopener" class="trk-maps-link" title="មើលលើ Google Maps">
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    @endif
                </span>
            </div>
            <div class="info-item">
                <label>កាលបរិច្ឆេទថ្ងៃដឹក</label>
                <span>{{ $booking->pick_up_date ? \Carbon\Carbon::parse($booking->pick_up_date)->format('d/m/Y') : '—' }}</span>
            </div>
            <div class="info-item">
                <label>កាលបរិច្ឆេទថ្ងៃទម្លាក់</label>
                <span>{{ $booking->drop_off_date ? \Carbon\Carbon::parse($booking->drop_off_date)->format('d/m/Y') : '—' }}</span>
            </div>
            <div class="info-item">
                <label>ទម្ងន់ (គីឡូក្រាម)</label>
                <span>{{ $booking->cargo_weight ? number_format($booking->cargo_weight, 2) : '—' }}</span>
            </div>
            <div class="info-item">
                <label>លេខកុងតឺន័រ</label>
                <span>{{ $booking->container_number ?? '—' }}</span>
            </div>
            @if($booking->total_price)
            <div class="info-item">
                <label>តម្លៃសរុប</label>
                <span class="trk-price-val">${{ number_format($booking->total_price, 2) }}</span>
            </div>
            @endif
            @if($booking->cargo_list_file)
            <div class="info-item">
                <label>ឯកសារ</label>
                <a href="{{ asset($booking->cargo_list_file) }}" target="_blank" class="file-link">
                    <i class="fas fa-file-download"></i> ទាញយកឯកសារ
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if($booking->extraCharges->isNotEmpty())
<div class="card trk-card-mb">
    <div class="card-header">
        <h2><i class="fas fa-money-bill-wave trk-charge-icon"></i>ការគិតប្រាក់បន្ថែម</h2>
    </div>
    <div class="card-body">
        @php
            $chargeRespLabel = ['Pending'=>'រង់ចាំការឆ្លើយតប','Accepted'=>'អ្នកបានយល់ព្រម','Rejected'=>'អ្នកបានបដិសេធ'];
            $chargeRespClass = ['Pending'=>'badge-pending','Accepted'=>'badge-completed','Rejected'=>'badge-cancelled'];
        @endphp
        @foreach($booking->extraCharges as $charge)
        <div class="extra-charge-row">
            <div class="extra-charge-info">
                <div class="extra-charge-reason">{{ $charge->reason }}</div>
                <div class="extra-charge-date">{{ $charge->date ? \Carbon\Carbon::parse($charge->date)->format('d/m/Y') : '' }}</div>
            </div>
            <div class="extra-charge-amount">${{ number_format($charge->amount, 2) }}</div>
            <div class="extra-charge-status">
                <span class="badge {{ $chargeRespClass[$charge->client_response] ?? 'badge-pending' }}">
                    {{ $chargeRespLabel[$charge->client_response] ?? $charge->client_response }}
                </span>
            </div>
            @if($charge->client_response === 'Pending')
            <div class="extra-charge-actions">
                <form method="POST" action="{{ route('booking.extra-charge.respond', $charge->extra_id) }}">
                    @csrf
                    <input type="hidden" name="response" value="Accepted">
                    <input type="hidden" name="token" value="{{ $booking->access_token }}">
                    <button type="submit" class="ec-btn ec-btn-accept"><i class="fas fa-check"></i> យល់ព្រម</button>
                </form>
                <form method="POST" action="{{ route('booking.extra-charge.respond', $charge->extra_id) }}">
                    @csrf
                    <input type="hidden" name="response" value="Rejected">
                    <input type="hidden" name="token" value="{{ $booking->access_token }}">
                    <button type="submit" class="ec-btn ec-btn-reject"><i class="fas fa-times"></i> បដិសេធ</button>
                </form>
            </div>
            @endif
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- First payment: approved, not yet paid --}}
@if($booking->payment_status === 'unpaid' && $booking->status === 'confirmed' && !$hasRejectedFirst)
<div class="notify-box trk-notify-mb">
    <div class="icon"><i class="fas fa-bell"></i></div>
    <div class="text">
        <h4>ការកក់របស់អ្នកបានទទួលការអនុម័ត!</h4>
        <p>សូមបង់ប្រាក់ 50% នៃតម្លៃសរុបដើម្បីបញ្ជាក់ការកក់។
        @if($booking->total_price)
            ចំនួន = <strong class="trk-amount-orange">${{ number_format($booking->total_price * 0.5, 2) }}</strong>
        @endif
        </p>
    </div>
</div>
@endif

{{-- First payment: rejected by admin --}}
@if($booking->payment_status === 'unpaid' && $booking->status === 'confirmed' && $hasRejectedFirst)
<div class="notify-box trk-notify-mb" style="border-left:4px solid #ef4444;background:#fff5f5;">
    <div class="icon" style="color:#ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="text">
        <h4 style="color:#dc2626;">ការទូទាត់ 50% ដំបូងត្រូវបានបដិសេធ!</h4>
        <p>ប្រតិបត្តិការរបស់អ្នកមិនទាន់ចូលក្នុងគណនីរបស់យើងទេ។ សូមផ្ទុកភស្តុតាងការទូទាត់ឡើងវិញ។</p>
    </div>
</div>
@endif

{{-- First payment: pending admin verification --}}
@if($hasPendingFirst)
<div class="notify-box trk-notify-mb" style="border-left:4px solid #f59e0b;background:#fffbeb;">
    <div class="icon" style="color:#d97706;"><i class="fas fa-clock"></i></div>
    <div class="text">
        <h4 style="color:#92400e;">ការទូទាត់ 50% ដំបូងបានទទួល!</h4>
        <p>យើងកំពុងផ្ទៀងផ្ទាត់ប្រតិបត្តិការរបស់អ្នក។ សូមរង់ចាំ។</p>
    </div>
</div>
@endif

{{-- Second payment: delivery done, not yet paid --}}
@if($booking->payment_status === 'deposit_paid' && $booking->status === 'completed' && !$hasPendingSecond && !$hasRejectedSecond)
<div class="notify-box trk-notify-mb trk-notify-completed">
    <div class="icon"><i class="fas fa-truck"></i></div>
    <div class="text">
        <h4>ការដឹកជញ្ជូនបានបញ្ចប់!</h4>
        <p>Admin បានបញ្ចប់ការដឹកជញ្ជូន។ សូមបង់ប្រាក់ 50% ចុងក្រោយ។
        @if($booking->total_price)
            ចំនួន = <strong>${{ number_format($booking->total_price * 0.5, 2) }}</strong>
        @endif
        </p>
    </div>
</div>
@endif

{{-- Second payment: rejected by admin --}}
@if($booking->payment_status === 'deposit_paid' && $booking->status === 'completed' && $hasRejectedSecond && !$hasPendingSecond)
<div class="notify-box trk-notify-mb" style="border-left:4px solid #ef4444;background:#fff5f5;">
    <div class="icon" style="color:#ef4444;"><i class="fas fa-exclamation-triangle"></i></div>
    <div class="text">
        <h4 style="color:#dc2626;">ការទូទាត់ 50% ចុងក្រោយត្រូវបានបដិសេធ!</h4>
        <p>ប្រតិបត្តិការរបស់អ្នកមិនទាន់ចូលក្នុងគណនីរបស់យើងទេ។ សូមផ្ទុកភស្តុតាងការទូទាត់ឡើងវិញ។</p>
    </div>
</div>
@endif

{{-- Second payment: pending admin verification --}}
@if($hasPendingSecond)
<div class="notify-box trk-notify-mb" style="border-left:4px solid #f59e0b;background:#fffbeb;">
    <div class="icon" style="color:#d97706;"><i class="fas fa-clock"></i></div>
    <div class="text">
        <h4 style="color:#92400e;">ការទូទាត់ 50% ចុងក្រោយបានទទួល!</h4>
        <p>យើងកំពុងផ្ទៀងផ្ទាត់ប្រតិបត្តិការរបស់អ្នក។ សូមរង់ចាំ។</p>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <h3 class="trk-section-h3">
            <i class="fas fa-route trk-route-icon"></i>ដំណើរការការកក់
        </h3>
        <div class="timeline">
            @php
                $steps = [
                    ['icon'=>'fa-file-alt',       'title'=>'ការកក់ត្រូវបានដាក់ស្នើ',   'done'=>true],
                    ['icon'=>'fa-check-circle',   'title'=>'Admin បានអនុម័ត',           'done'=>in_array($booking->status,['confirmed','in_progress','completed'])],
                    ['icon'=>'fa-dollar-sign',    'title'=>'ការបង់ប្រាក់ 50% ដំបូង',   'done'=>in_array($booking->payment_status,['deposit_paid','fully_paid'])],
                    ['icon'=>'fa-truck',          'title'=>'រថយន្តកំពុងដឹកជញ្ជូន',     'done'=>in_array($booking->status,['in_progress','completed'])],
                    ['icon'=>'fa-flag-checkered', 'title'=>'Admin បានបញ្ចប់ដំណើរការ', 'done'=>$booking->status==='completed'],
                    ['icon'=>'fa-check-double',   'title'=>'ការបង់ប្រាក់ 50% ចុងក្រោយ','done'=>$booking->payment_status==='fully_paid'],
                ];
                $activeIdx = 0;
                foreach($steps as $i => $s){ if($s['done']) $activeIdx = $i + 1; }
            @endphp
            @foreach($steps as $i => $step)
            @php $isDone=$step['done']; $isActive=($i===$activeIdx)&&!$isDone; @endphp
            <div class="tl-step {{ $isDone?'done':'' }}">
                <div class="tl-line"></div>
                <div class="tl-icon {{ $isDone?'done':($isActive?'active':'waiting') }}">
                    <i class="fas {{ $step['icon'] }}"></i>
                </div>
                <div class="tl-content">
                    <div class="tl-title {{ (!$isDone&&!$isActive)?'waiting':'' }}">{{ $step['title'] }}</div>
                    <div class="tl-desc">
                        @if($isDone) ✓ បានបញ្ចប់
                        @elseif($isActive) ⏳ កំពុងដំណើរការ...
                        @else រង់ចាំ
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@if($booking->payment_status === 'unpaid' && $booking->status === 'confirmed')
<div class="trk-pay-center">
    <a href="{{ route('booking.pay', ['id'=>$booking->booking_id,'token'=>$booking->access_token]) }}" class="pay-btn pay-btn-orange">
        <i class="fas fa-credit-card"></i>
        បង់ប្រាក់ 50% ដំបូង
        @if($booking->total_price) — ${{ number_format($booking->total_price * 0.5, 2) }}@endif
    </a>
</div>
@elseif($booking->payment_status === 'deposit_paid' && $booking->status === 'completed' && !$hasPendingSecond)
<div class="trk-pay-center">
    <a href="{{ route('booking.pay', ['id'=>$booking->booking_id,'token'=>$booking->access_token]) }}" class="pay-btn pay-btn-green">
        <i class="fas fa-check-circle"></i>
        បង់ប្រាក់ 50% ចុងក្រោយ
        @if($booking->total_price) — ${{ number_format($booking->total_price * 0.5, 2) }}@endif
    </a>
</div>
@elseif($booking->payment_status === 'fully_paid')
<div class="trk-pay-center">
    <div class="trk-fully-paid">
        <i class="fas fa-check-circle trk-paid-icon"></i> ការទូទាត់ទាំងស្រុងបានបញ្ចប់!
    </div>
</div>
@endif

<div class="trk-back-center">
    <a href="{{ route('trucks_section') }}" class="trk-back-link">
        <i class="fas fa-arrow-left trk-back-icon"></i>ត្រឡប់ទៅទំព័រកក់
    </a>
</div>

</div>

</body>
</html>
