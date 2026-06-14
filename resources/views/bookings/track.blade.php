<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ការតាមដានការកក់ #{{ $booking->booking_id }}</title>
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

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-file-alt" style="margin-right:8px;color:#FF6B00;"></i>ការតាមដានការកក់ #{{ $booking->booking_id }}</h2>
        <p>{{ $booking->booking_date ? \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y') : '' }} — {{ $booking->customer->full_name ?? '' }}</p>
    </div>
    <div class="card-body">
        <div class="info-grid">
            <div class="info-item">
                <label>ស្ថានភាព</label>
                @php
                    $statusLabel = ['pending'=>'រង់ចាំ','confirmed'=>'បានអនុម័ត','in_progress'=>'កំពុងដឹក','completed'=>'បានបញ្ចប់','cancelled'=>'បានលុបចោល'];
                @endphp
                <span class="badge badge-{{ $booking->status }}">
                    <i class="fas fa-circle" style="font-size:0.45rem;"></i>
                    {{ $statusLabel[$booking->status] ?? $booking->status }}
                </span>
            </div>
            <div class="info-item">
                <label>ប្រភេទ</label>
                <span>{{ $booking->booking_type === 'import' ? 'នាំចូល (Import)' : 'នាំចេញ (Export)' }}</span>
            </div>
            <div class="info-item">
                <label>ទីតាំងទទួល</label>
                <span><i class="fas fa-map-marker-alt" style="color:#FF6B00;margin-right:4px;font-size:0.75rem;"></i>{{ $booking->pickup_location ?? '—' }}</span>
            </div>
            <div class="info-item">
                <label>ទីតាំងដឹកទៅ</label>
                <span>
                    <i class="fas fa-map-marker-alt" style="color:#10b981;margin-right:4px;font-size:0.75rem;"></i>{{ $booking->dropoff_location ?? '—' }}
                    @if($booking->dropoff_location_link)
                        <a href="{{ $booking->dropoff_location_link }}" target="_blank" rel="noopener" style="margin-left:6px;color:#FF6B00;" title="មើលលើ Google Maps">
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
                <span style="color:#FF6B00;font-family:'Montserrat',sans-serif;font-weight:800;">${{ number_format($booking->total_price, 2) }}</span>
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

@if($booking->payment_status === 'unpaid' && $booking->status === 'confirmed')
<div class="notify-box" style="margin-bottom:24px;">
    <div class="icon"><i class="fas fa-bell"></i></div>
    <div class="text">
        <h4>ការកក់របស់អ្នកបានទទួលការអនុម័ត!</h4>
        <p>សូមបង់ប្រាក់ 50% នៃតម្លៃសរុបដើម្បីបញ្ជាក់ការកក់។
        @if($booking->total_price)
            ចំនួន = <strong style="color:#c2410c;">${{ number_format($booking->total_price * 0.5, 2) }}</strong>
        @endif
        </p>
    </div>
</div>
@endif

@if($booking->payment_status === 'deposit_paid' && $booking->status === 'completed')
<div class="notify-box" style="margin-bottom:24px;background:linear-gradient(135deg,#f0fdf4,#dcfce7);border-color:#bbf7d0;">
    <div class="icon" style="background:#10b981;"><i class="fas fa-truck"></i></div>
    <div class="text">
        <h4 style="color:#065f46;">ការដឹកជញ្ជូនបានបញ្ចប់!</h4>
        <p style="color:#166534;">Admin បានបញ្ចប់ការដឹកជញ្ជូន។ សូមបង់ប្រាក់ 50% ចុងក្រោយ។
        @if($booking->total_price)
            ចំនួន = <strong>${{ number_format($booking->total_price * 0.5, 2) }}</strong>
        @endif
        </p>
    </div>
</div>
@endif

<div class="card">
    <div class="card-body">
        <h3 style="font-family:'Montserrat',sans-serif;font-size:0.95rem;font-weight:800;color:#1e293b;margin-bottom:20px;">
            <i class="fas fa-route" style="color:#FF6B00;margin-right:8px;"></i>ដំណើរការការកក់
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
<div style="text-align:center;margin-top:8px;">
    <a href="{{ route('booking.pay', ['id'=>$booking->booking_id,'token'=>$booking->access_token]) }}" class="pay-btn pay-btn-orange">
        <i class="fas fa-credit-card"></i>
        បង់ប្រាក់ 50% ដំបូង
        @if($booking->total_price) — ${{ number_format($booking->total_price * 0.5, 2) }}@endif
    </a>
</div>
@elseif($booking->payment_status === 'deposit_paid' && $booking->status === 'completed')
<div style="text-align:center;margin-top:8px;">
    <a href="{{ route('booking.pay', ['id'=>$booking->booking_id,'token'=>$booking->access_token]) }}" class="pay-btn pay-btn-green">
        <i class="fas fa-check-circle"></i>
        បង់ប្រាក់ 50% ចុងក្រោយ
        @if($booking->total_price) — ${{ number_format($booking->total_price * 0.5, 2) }}@endif
    </a>
</div>
@elseif($booking->payment_status === 'fully_paid')
<div style="text-align:center;margin-top:8px;">
    <div style="display:inline-flex;align-items:center;gap:10px;padding:14px 32px;background:#d1fae5;border-radius:12px;color:#065f46;font-weight:700;">
        <i class="fas fa-check-circle" style="font-size:1.2rem;"></i> ការទូទាត់ទាំងស្រុងបានបញ្ចប់!
    </div>
</div>
@endif

<div style="text-align:center;margin-top:20px;">
    <a href="{{ route('trucks_section') }}" style="color:#64748b;font-size:0.83rem;text-decoration:none;">
        <i class="fas fa-arrow-left" style="margin-right:5px;"></i>ត្រឡប់ទៅទំព័រកក់
    </a>
</div>

</div>
</body>
</html>
