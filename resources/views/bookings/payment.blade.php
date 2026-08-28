<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/png" href="{{ asset('images/trucking-logo.png') }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ការទូទាត់ {{ $booking->formatted_id }}</title>
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
            <i class="fas fa-{{ $stage === 'deposit' ? 'coins' : 'check-circle' }}" class="pay-stage-icon"></i>
            {{ $label }}
        </div>
        <div class="amount">${{ number_format($amount, 2) }}</div>
        <div class="booking-ref">ការកក់ {{ $booking->formatted_id }} &nbsp;·&nbsp; {{ $booking->customer?->full_name ?? $booking->bookedByUser?->user_name ?? '' }}</div>
    </div>
    <div class="pay-summary-pad">
        <div class="summary-row">
            <span class="lbl"><i class="fas fa-map-marker-alt pay-pickup-icon"></i>ទីតាំងទទួល</span>
            <span class="val">{{ $booking->pickup_location }}</span>
        </div>
        <div class="summary-row">
            <span class="lbl"><i class="fas fa-map-marker-alt pay-dropoff-icon"></i>ទីតាំងដឹកទៅ</span>
            <span class="val">
                {{ $booking->dropoff_location }}
                @if($booking->dropoff_location_link)
                    <a href="{{ $booking->dropoff_location_link }}" target="_blank" rel="noopener" class="pay-maps-link" title="មើលលើ Google Maps">
                        <i class="fas fa-external-link-alt pay-maps-icon"></i>
                    </a>
                @endif
            </span>
        </div>
        @if($booking->total_price)
        <div class="summary-row">
            <span class="lbl">តម្លៃដឹកជញ្ជូនសរុប</span>
            <span class="val pay-price-val">${{ number_format($booking->total_price, 2) }}</span>
        </div>
        @endif
        @if($stage === 'final')
        <div class="summary-row">
            <span class="lbl">50% ចុងក្រោយ</span>
            <span class="val pay-price-val">${{ number_format(round($booking->total_price * 0.5, 2), 2) }}</span>
        </div>
        @foreach($booking->secondStageCharges as $sc)
        <div class="summary-row">
            <span class="lbl" style="color:#b45309;">+ {{ $sc->reason }}</span>
            <span class="val pay-price-val" style="color:#b45309;">+${{ number_format($sc->amount, 2) }}</span>
        </div>
        @endforeach
        @if($booking->secondStageCharges->isNotEmpty())
        <div class="summary-row" style="border-top:1px solid #e2e8f0;margin-top:6px;padding-top:6px;">
            <span class="lbl" style="font-weight:700;">សរុបត្រូវបង់</span>
            <span class="val pay-price-val" style="font-weight:700;color:#d97706;">${{ number_format($amount, 2) }}</span>
        </div>
        @endif
        @endif
    </div>
</div>

<div class="card">
    <div class="khqr-wrap">
        <div class="khqr-title"><i class="fas fa-qrcode pay-khqr-icon"></i>ស្គែន QR ដើម្បីទូទាត់</div>
        <div class="khqr-img-wrap">
            @if(file_exists(public_path('images/khqr-payment.jpg')))
                <img src="{{ asset('images/khqr-payment.jpg') }}" alt="KHQR ACLEDA">
            @else
                <div class="pay-qr-placeholder">
                    <i class="fas fa-qrcode pay-qr-placeholder-icon"></i>
                    <p class="pay-qr-placeholder-text">
                        រក្សាទុករូបភាព QR ជា<br><code class="pay-qr-code">public/images/khqr-payment.jpg</code>
                    </p>
                </div>
            @endif
            <div class="khqr-amount-badge">${{ number_format($amount, 2) }}</div>
        </div>
        <div class="app-icons">
            <span class="app-icon app-icon-acleda"><i class="fas fa-university"></i> ACLEDA</span>
            <span class="app-icon app-icon-aba"><i class="fas fa-mobile-alt"></i> ABA</span>
        </div>
        <div class="khqr-steps">
            <div class="khqr-step"><div class="num">1</div><p>បើកកម្មវិធី <strong>ACLEDA</strong> ឬ <strong>ABA</strong></p></div>
            <div class="khqr-step"><div class="num">2</div><p>ចុច <strong>Scan / ស្គែន QR</strong> រួចស្គែនលើ QR Code ខាងលើ</p></div>
            <div class="khqr-step"><div class="num">3</div><p>បញ្ចូលចំនួន <strong>${{ number_format($amount, 2) }}</strong> ហើយបញ្ជាក់ការទូទាត់</p></div>
            <div class="khqr-step"><div class="num green">4</div><p>បន្ទាប់ពីទូទាត់ហើយ ជ្រើសរើសវិធីទូទាត់ខាងក្រោម ហើយចុច <strong>"បញ្ជាក់"</strong></p></div>
        </div>
    </div>
</div>

<div class="separator"><span>ជ្រើសរើសវិធីទូទាត់ ហើយបញ្ជាក់</span></div>

<div class="card pay-form-card">
    <form method="POST" action="{{ route('booking.pay.process', $booking->booking_id) }}" enctype="multipart/form-data" novalidate id="paymentForm">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="methods-section">
            <div class="methods-label">វិធីទូទាត់ដែលបានប្រើ</div>
            <div class="methods-grid">
                <label><input type="radio" name="payment_method" value="acleda" class="method-radio" required>
                    <div class="method-card"><i class="fas fa-university pay-method-acleda"></i><span>ACLEDA</span></div></label>
                <label><input type="radio" name="payment_method" value="aba" class="method-radio">
                    <div class="method-card"><i class="fas fa-mobile-alt pay-method-aba"></i><span>ABA</span></div></label>
            </div>
            @error('payment_method')
            <p class="pay-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
            @enderror
        </div>
        <div class="proof-upload-section">
            <div class="methods-label">ភស្តុតាងការទូទាត់ (រូបថត ឬ Screenshot)</div>
            <label for="paymentProofInput" class="proof-upload-box" id="proofUploadBox">
                <i class="fas fa-cloud-upload-alt"></i>
                <span id="proofUploadText">ចុចដើម្បីបញ្ចូលរូបភាព ឬ PDF (អតិបរមា 5MB)</span>
            </label>
            <input type="file" name="payment_proof" id="paymentProofInput" accept="image/jpeg,image/png,image/jpg,application/pdf" required class="pay-hidden" onchange="updateProofFileName(this)">
            @error('payment_proof')
            <p class="pay-error-msg"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
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

{{-- Payment validation modal --}}
<div id="payValidationOverlay" class="pv-overlay" style="display:none;" onclick="if(event.target===this)hideAlert()">
    <div class="pv-modal">
        <div class="pv-icon-circle">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="pv-title">មិនទាន់បានបំពេញ!</div>
        <div class="pv-msg" id="payValidationMsg"></div>
        <button class="pv-btn-ok" onclick="hideAlert()">យល់ព្រម</button>
    </div>
</div>

<script>
function updateProofFileName(input) {
    var box  = document.getElementById('proofUploadBox');
    var text = document.getElementById('proofUploadText');
    if (input.files && input.files[0]) {
        text.textContent = '✓ ' + input.files[0].name;
        box.classList.add('has-file');
    } else {
        text.textContent = 'ចុចដើម្បីបញ្ចូលរូបភាព ឬ PDF (អតិបរមា 5MB)';
        box.classList.remove('has-file');
    }
    hideAlert();
}

function showAlert(msg) {
    document.getElementById('payValidationMsg').textContent = msg;
    document.getElementById('payValidationOverlay').style.display = 'flex';
}

function hideAlert() {
    document.getElementById('payValidationOverlay').style.display = 'none';
}

document.getElementById('paymentForm').addEventListener('submit', function(e) {
    var method = document.querySelector('input[name="payment_method"]:checked');
    var proof  = document.getElementById('paymentProofInput');

    if (!method) {
        e.preventDefault();
        showAlert('សូមជ្រើសរើសវិធីទូទាត់ (ACLEDA, ABA, Wing...) មុនពេលបញ្ជាក់!');
        return;
    }
    if (!proof.files || proof.files.length === 0) {
        e.preventDefault();
        showAlert('សូមបញ្ចូលរូបភាពភស្តុតាងការទូទាត់ (Screenshot ឬ PDF) មុនពេលចុចបញ្ជាក់!');
        return;
    }
    hideAlert();
});

document.querySelectorAll('input[name="payment_method"]').forEach(function(radio) {
    radio.addEventListener('change', hideAlert);
});
</script>

</body>
</html>
