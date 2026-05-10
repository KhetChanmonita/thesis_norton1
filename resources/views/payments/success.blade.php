
{{-- resources/views/payments/success.blade.php --}}
<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <title>កក់សេវាជោគជ័យ | Trucking System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/success.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>

@include('partials.header')

<div class="success-wrapper">
    <div class="container">
        <div class="success-container">
            <div class="success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            
            <h1>កក់សេវាជោគជ័យ!</h1>
            
            <p class="success-message">
                សូមអរគុណសម្រាប់ការកក់សេវាជាមួយពួកយើង។
                ប្រព័ន្ធបានទទួលការទូទាត់ប្រាក់របស់អ្នកដោយជោគជ័យ។
            </p>
            
            <div class="booking-details">
                <h2>ព័ត៌មានការកក់</h2>
                
                <div class="detail-card">
                    <div class="detail-row">
                        <span>លេខកក់:</span>
                        <span class="booking-number">{{ $booking->booking_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span>ប្រភេទសេវា:</span>
                        <span>{{ $booking->type == 'import' ? 'នាំចូល' : 'នាំចេញ' }}</span>
                    </div>
                    <div class="detail-row">
                        <span>កាលបរិច្ឆេទកក់:</span>
                        <span>{{ $booking->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="detail-row">
                        <span>ចំនួនទឹកប្រាក់បង់ជាមុន:</span>
                        <span class="amount-paid">${{ number_format($booking->deposit_amount, 2) }}</span>
                    </div>
                    <div class="detail-row">
                        <span>ស្ថានភាព:</span>
                        <span class="status-success">បានបញ្ជាក់</span>
                    </div>
                </div>
            </div>
            
            <div class="next-steps">
                <h3><i class="fas fa-clock"></i> ជំហានបន្ទាប់</h3>
                <ul>
                    <li>អ្នកបើកបរនឹងទំនាក់ទំនងអ្នកតាមទូរស័ព្ទ ២៤ ម៉ោងមុនពេលយកទំនិញ</li>
                    <li>សូមត្រៀមឯកសារដឹកជញ្ជូននៅថ្ងៃកំណត់</li>
                    <li>អ្នកអាចតាមដានទីតាំងរថយន្តតាមរយៈប្រព័ន្ធតាមដាន</li>
                    <li>សូមទូទាត់ថ្លៃសេវាដែលនៅសល់នៅពេលបញ្ចប់ការដឹកជញ្ជូន</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="{{ route('bookings.show', $booking) }}" class="btn btn-primary">
                    <i class="fas fa-file-alt"></i> មើលព័ត៌មានកក់
                </a>
                <a href="{{ route('dashboard') }}" class="btn btn-secondary">
                    <i class="fas fa-home"></i> ត្រឡប់ទៅទំព័រដើម
                </a>
            </div>
            
            <div class="support-info">
                <p>មានសំណួរឬត្រូវការជំនួយ?</p>
                <a href="tel:+85512345678"><i class="fas fa-phone-alt"></i> 012 345 678</a>
                <a href="mailto:support@trucking.com"><i class="fas fa-envelope"></i> support@trucking.com</a>
            </div>
        </div>
    </div>
</div>

@include('partials.footer')

</body>
</html>