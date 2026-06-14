<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ការទូទាត់ #{{ $booking->booking_id }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Kantumruy+Pro:wght@300;400;500;600;700&family=Montserrat:wght@600;700;800&family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/booking_payment.css') }}">
</head>
<body>
@include('partials.header')
<div class="page-wrap">

<a href="{{ route('booking.track', ['id'=>$booking->booking_id,'token'=>$booking->access_token]) }}" class="back-link">
    <i class="fas fa-arrow-left"></i> ត្រឡប់ទៅការតាមដានការកក់
</a>

<div class="card">
    <div class="amount-banner {{ $stage }}">
        <div class="stage-label">
            <i class="fas fa-{{ $stage === 'deposit' ? 'coins' : 'check-circle' }}" style="margin-right:5px;"></i>
            {{ $label }}
        </div>
        <div class="amount">${{ number_format($amount, 2) }}</div>
        <div class="booking-ref">ការកក់ #{{ $booking->booking_id }} &nbsp;·&nbsp; {{ $booking->customer->full_name ?? '' }}</div>
    </div>
    <div style="padding:16px 24px;">
        <div class="summary-row">
            <span class="lbl"><i class="fas fa-map-marker-alt" style="color:#FF6B00;margin-right:5px;font-size:0.7rem;"></i>ទីតាំងទទួល</span>
            <span class="val">{{ $booking->pickup_location }}</span>
        </div>
        <div class="summary-row">
            <span class="lbl"><i class="fas fa-map-marker-alt" style="color:#10b981;margin-right:5px;font-size:0.7rem;"></i>ទីតាំងដឹកទៅ</span>
            <span class="val">
                {{ $booking->dropoff_location }}
                @if($booking->dropoff_location_link)
                    <a href="{{ $booking->dropoff_location_link }}" target="_blank" rel="noopener" style="color:#FF6B00;margin-left:6px;" title="មើលលើ Google Maps">
                        <i class="fas fa-external-link-alt" style="font-size:0.7rem;"></i>
                    </a>
                @endif
            </span>
        </div>
        @if($booking->total_price)
        <div class="summary-row">
            <span class="lbl">តម្លៃសរុប</span>
            <span class="val" style="color:#FF6B00;font-family:'Montserrat',sans-serif;">${{ number_format($booking->total_price, 2) }}</span>
        </div>
        @endif
    </div>
</div>

<div class="card">
    <div class="khqr-wrap">
        <div class="khqr-title"><i class="fas fa-qrcode" style="color:#c0392b;margin-right:6px;"></i>ស្គែន QR ដើម្បីទូទាត់</div>
        <div class="khqr-img-wrap">
            @if(file_exists(public_path('images/khqr-payment.jpg')))
                <img src="{{ asset('images/khqr-payment.jpg') }}" alt="KHQR ACLEDA">
            @else
                <div style="width:300px;height:380px;background:#1a3a5c;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:12px;">
                    <i class="fas fa-qrcode" style="font-size:5rem;color:rgba(255,255,255,0.3);"></i>
                    <p style="color:rgba(255,255,255,0.6);font-size:0.82rem;text-align:center;padding:0 20px;">
                        រក្សាទុករូបភាព QR ជា<br><code style="color:#f59e0b;">public/images/khqr-payment.jpg</code>
                    </p>
                </div>
            @endif
            <div class="khqr-amount-badge">${{ number_format($amount, 2) }}</div>
        </div>
        <div class="app-icons">
            <span class="app-icon" style="color:#c0392b;border-color:#fecaca;"><i class="fas fa-university"></i> ACLEDA</span>
            <span class="app-icon" style="color:#d97706;border-color:#fde68a;"><i class="fas fa-mobile-alt"></i> ABA</span>
            <span class="app-icon" style="color:#4f46e5;border-color:#c7d2fe;"><i class="fas fa-mobile-alt"></i> Wing</span>
            <span class="app-icon" style="color:#475569;border-color:#e2e8f0;"><i class="fas fa-th"></i> KHQR</span>
        </div>
        <div class="khqr-steps">
            <div class="khqr-step"><div class="num">1</div><p>បើកកម្មវិធី <strong>ACLEDA, ABA, Wing</strong> ឬ កម្មវិធីណាមួយដែលគាំទ្រ KHQR</p></div>
            <div class="khqr-step"><div class="num">2</div><p>ចុច <strong>Scan / ស្គែន QR</strong> រួចស្គែនលើ QR Code ខាងលើ</p></div>
            <div class="khqr-step"><div class="num">3</div><p>បញ្ចូលចំនួន <strong>${{ number_format($amount, 2) }}</strong> ហើយបញ្ជាក់ការទូទាត់</p></div>
            <div class="khqr-step"><div class="num green">4</div><p>បន្ទាប់ពីទូទាត់ហើយ ជ្រើសរើសវិធីទូទាត់ខាងក្រោម ហើយចុច <strong>"បញ្ជាក់"</strong></p></div>
        </div>
    </div>
</div>

<div class="separator"><span>ជ្រើសរើសវិធីទូទាត់ ហើយបញ្ជាក់</span></div>

<div class="card" style="margin-top:16px;">
    <form method="POST" action="{{ route('booking.pay.process', $booking->booking_id) }}">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="methods-section">
            <div class="methods-label">វិធីទូទាត់ដែលបានប្រើ</div>
            <div class="methods-grid">
                <label><input type="radio" name="payment_method" value="acleda" class="method-radio" required>
                    <div class="method-card"><i class="fas fa-university" style="color:#c0392b;"></i><span>ACLEDA</span></div></label>
                <label><input type="radio" name="payment_method" value="aba" class="method-radio">
                    <div class="method-card"><i class="fas fa-mobile-alt" style="color:#f59e0b;"></i><span>ABA</span></div></label>
                <label><input type="radio" name="payment_method" value="wing" class="method-radio">
                    <div class="method-card"><i class="fas fa-mobile-alt" style="color:#4f46e5;"></i><span>Wing</span></div></label>
                <label><input type="radio" name="payment_method" value="cash" class="method-radio">
                    <div class="method-card"><i class="fas fa-money-bill-wave" style="color:#10b981;"></i><span>សាច់ប្រាក់</span></div></label>
                <label><input type="radio" name="payment_method" value="bank_transfer" class="method-radio">
                    <div class="method-card"><i class="fas fa-exchange-alt" style="color:#3b82f6;"></i><span>ផ្ទេរប្រាក់</span></div></label>
                <label><input type="radio" name="payment_method" value="khqr" class="method-radio">
                    <div class="method-card"><i class="fas fa-qrcode" style="color:#64748b;"></i><span>KHQR ផ្សេង</span></div></label>
            </div>
            @error('payment_method')
            <p style="color:#ef4444;font-size:0.8rem;margin-top:10px;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>
        <div class="form-footer">
            <button type="submit" class="confirm-btn {{ $stage }}">
                <i class="fas fa-check-circle"></i>
                ខ្ញុំបានទូទាត់ ${{ number_format($amount, 2) }} ហើយ
            </button>
            <div class="security-note">
                <i class="fas fa-shield-alt"></i>
                ចុចប៊ូតុងនេះបន្ទាប់ពីអ្នកបានស្គែន QR និងបញ្ចប់ការទូទាត់ក្នុងកម្មវិធី
            </div>
        </div>
    </form>
</div>

</div>
</body>
</html>
