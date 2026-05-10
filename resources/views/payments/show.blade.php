{{-- resources/views/payments/show.blade.php --}}
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>ទូទាត់ប្រាក់ | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@include('partials.header')

<div class="payment-wrapper">
    <div class="container">
        <div class="payment-container">
            <div class="payment-header">
                <div class="payment-progress">
                    <div class="progress-step completed">
                        <div class="step-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span>កក់សេវា</span>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step active">
                        <div class="step-icon">
                            <i class="fas fa-credit-card"></i>
                        </div>
                        <span>ទូទាត់ប្រាក់</span>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span>បញ្ចប់</span>
                    </div>
                </div>
            </div>

            <div class="payment-content">
                <div class="payment-info">
                    <h2><i class="fas fa-receipt"></i> ព័ត៌មានការទូទាត់</h2>

                    <div class="booking-summary">
                        <div class="summary-header">
                            <h3>លេខកក់: {{ $booking->booking_number }}</h3>
                            <span class="status-badge status-{{ $booking->status }}">
                                {{ $booking->status == 'pending' ? 'រង់ចាំទូទាត់' : '' }}
                            </span>
                        </div>

                        <div class="summary-details">
                            <div class="detail-row">
                                <span>ប្រភេទសេវា:</span>
                                <span>{{ $booking->type == 'import' ? 'នាំចូល' : 'នាំចេញ' }}</span>
                            </div>
                            <div class="detail-row">
                                <span>កំពង់ផែចាប់ផ្តើម:</span>
                                <span>{{ $booking->originPort->name_km }}</span>
                            </div>
                            @if($booking->type == 'import')
                            <div class="detail-row">
                                <span>ខេត្តគោលដៅ:</span>
                                <span>{{ $booking->destinationProvince->name_km }}</span>
                            </div>
                            @else
                            <div class="detail-row">
                                <span>កំពង់ផែគោលដៅ:</span>
                                <span>{{ $booking->destinationPort->name_km }}</span>
                            </div>
                            @endif
                            <div class="detail-row">
                                <span>ទំហំកុងតឺន័រ:</span>
                                <span>{{ $booking->container_size }}</span>
                            </div>
                            <div class="detail-row">
                                <span>ទម្ងន់:</span>
                                <span>{{ number_format($booking->weight) }} គីឡូក្រាម</span>
                            </div>
                            <div class="detail-row">
                                <span>រថយន្ត:</span>
                                <span>{{ $booking->truck->plate_number }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="amount-details">
                        <div class="amount-row">
                            <span>ថ្លៃដឹកជញ្ជូនមូលដ្ឋាន:</span>
                            <span>${{ number_format($booking->transport_fee, 2) }}</span>
                        </div>
                        @if($booking->overweight_charge > 0)
                        <div class="amount-row">
                            <span>ថ្លៃបន្ថែមទម្ងន់លើស:</span>
                            <span>${{ number_format($booking->overweight_charge, 2) }}</span>
                        </div>
                        @endif
                        @if($booking->empty_return_fee > 0)
                        <div class="amount-row">
                            <span>ថ្លៃប្រគល់កុងតឺន័រវិញ:</span>
                            <span>${{ number_format($booking->empty_return_fee, 2) }}</span>
                        </div>
                        @endif
                        <div class="amount-row total">
                            <span>តម្លៃសរុប:</span>
                            <span>${{ number_format($booking->total_amount, 2) }}</span>
                        </div>
                        <div class="amount-row deposit">
                            <span>ត្រូវបង់ប្រាក់ជាមុន ៥០%:</span>
                            <span>${{ number_format($booking->deposit_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="payment-methods">
                        <h3><i class="fas fa-credit-card"></i> ជ្រើសរើសវិធីទូទាត់</h3>

                        <div class="payment-method-list">
                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="aba" checked>
                                <div class="method-content">
                                    <img src="{{ asset('images/aba-logo.png') }}" alt="ABA Pay">
                                    <div class="method-info">
                                        <h4>ABA Pay</h4>
                                        <p>ទូទាត់តាមរយៈកម្មវិធី ABA</p>
                                    </div>
                                </div>
                            </label>

                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="pi_pay">
                                <div class="method-content">
                                    <img src="{{ asset('images/pi-pay-logo.png') }}" alt="Pi Pay">
                                    <div class="method-info">
                                        <h4>Pi Pay</h4>
                                        <p>ទូទាត់តាមរយៈកម្មវិធី Pi Pay</p>
                                    </div>
                                </div>
                            </label>

                            <label class="payment-method">
                                <input type="radio" name="payment_method" value="wing">
                                <div class="method-content">
                                    <img src="{{ asset('images/wing-logo.png') }}" alt="Wing">
                                    <div class="method-info">
                                        <h4>Wing</h4>
                                        <p>ទូទាត់តាមរយៈ Wing</p>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="qr-payment">
                    <div class="qr-card">
                        <h3><i class="fas fa-qrcode"></i> ស្កេន QR ដើម្បីទូទាត់</h3>

                        <div class="qr-code">
                            <img src="{{ asset('images/qr-code-sample.png') }}" alt="QR Code">
                        </div>

                        <div class="qr-amount">
                            <span class="amount-label">ចំនួនទឹកប្រាក់ត្រូវបង់:</span>
                            <span class="amount-value">${{ number_format($booking->deposit_amount, 2) }}</span>
                        </div>

                        <div class="qr-instructions">
                            <ol>
                                <li>បើកកម្មវិធី ABA Pay / Pi Pay / Wing</li>
                                <li>ចុចលើ "ស្កេន QR"</li>
                                <li>ស្កេន QR ខាងលើ</li>
                                <li>ពិនិត្យមើលចំនួនទឹកប្រាក់</li>
                                <li>បញ្ជាក់ការទូទាត់</li>
                            </ol>
                        </div>

                        <form id="payment-form" action="{{ route('payment.process', $booking) }}" method="POST">
                            @csrf
                            <input type="hidden" name="payment_method" id="selected_payment_method" value="aba">
                            <button type="submit" class="btn-pay">
                                <i class="fas fa-check-circle"></i>
                                ខ្ញុំបានទូទាត់រួចរាល់
                            </button>
                        </form>

                        <div class="payment-note">
                            <i class="fas fa-info-circle"></i>
                            <p>សូមចុចប៊ូតុង "ខ្ញុំបានទូទាត់រួចរាល់" បន្ទាប់ពីអ្នកបានទូទាត់ប្រាក់រួច</p>
                        </div>
                    </div>

                    <div class="payment-expiry">
                        <i class="fas fa-clock"></i>
                        <span>សូមទូទាត់ប្រាក់ក្នុងរយៈពេល ២៤ ម៉ោង</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Update selected payment method
        const paymentMethods = document.querySelectorAll('input[name="payment_method"]');
        const selectedPaymentMethod = document.getElementById('selected_payment_method');

        paymentMethods.forEach(method => {
            method.addEventListener('change', function() {
                selectedPaymentMethod.value = this.value;

                // Update QR code based on selected method
                const qrImage = document.querySelector('.qr-code img');
                if (this.value === 'aba') {
                    qrImage.src = '{{ asset("images/qr-aba.png") }}';
                } else if (this.value === 'pi_pay') {
                    qrImage.src = '{{ asset("images/qr-pi-pay.png") }}';
                } else if (this.value === 'wing') {
                    qrImage.src = '{{ asset("images/qr-wing.png") }}';
                }
            });
        });

        // Form submission
        document.getElementById('payment-form').addEventListener('submit', function(e) {
            e.preventDefault();

            // In a real application, you would integrate with the payment gateway API here
            // For demo purposes, we'll just submit the form

            if (confirm('សូមបញ្ជាក់ថាអ្នកបានទូទាត់ប្រាក់រួចរាល់?')) {
                this.submit();
            }
        });
    });
</script>

</body>
</html>
