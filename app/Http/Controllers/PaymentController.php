<?php
// app/Http/Controllers/PaymentController.php
namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function show(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status === 'paid') {
            return redirect()->route('booking.success', $booking);
        }

        return view('payments.show', compact('booking'));
    }

    public function process(Request $request, Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        // Simulate payment processing
        // In a real application, you would integrate with a payment gateway here
        
        $payment = Payment::create([
            'booking_id' => $booking->id,
            'payment_number' => Payment::generatePaymentNumber(),
            'amount' => $booking->deposit_amount,
            'payment_type' => 'deposit',
            'payment_method' => $request->payment_method ?? 'aba',
            'status' => 'completed',
            'transaction_id' => 'TXN-' . uniqid(),
        ]);

        // Update booking status
        $booking->update([
            'status' => 'deposit_paid',
            'payment_status' => 'paid'
        ]);

        return redirect()->route('booking.success', $booking);
    }

    public function success(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403);
        }

        if ($booking->payment_status !== 'paid') {
            return redirect()->route('payment.show', $booking);
        }

        return view('payments.success', compact('booking'));
    }
}