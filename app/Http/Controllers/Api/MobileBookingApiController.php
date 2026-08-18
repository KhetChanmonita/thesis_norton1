<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\Request;

class MobileBookingApiController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with(['customer', 'truck', 'payments'])
            ->when($request->filled('status'), fn($q) => $q->where('status', $request->status))
            ->latest('booking_id')
            ->paginate(20);

        return response()->json($bookings);
    }

    public function show(int $id)
    {
        $booking = Booking::with(['customer', 'truck', 'payments', 'extraCharges'])->findOrFail($id);
        return response()->json($booking);
    }

    public function confirm(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Booking confirmed', 'status' => $booking->status]);
    }

    public function start(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'in_progress']);
        if ($booking->truck_id) {
            $booking->truck?->update(['status' => 'delivering']);
        }
        return response()->json(['message' => 'Booking started', 'status' => $booking->status]);
    }

    public function complete(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'completed']);
        if ($booking->truck_id) {
            $booking->truck?->update(['status' => 'available']);
        }
        return response()->json(['message' => 'Booking completed', 'status' => $booking->status]);
    }

    public function cancel(int $id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);
        if ($booking->truck_id) {
            $booking->truck?->update(['status' => 'available']);
        }
        return response()->json(['message' => 'Booking cancelled', 'status' => $booking->status]);
    }

    public function getPayments(int $id)
    {
        $booking = Booking::findOrFail($id);
        $payments = $booking->payments()->get()->map(fn($p) => $this->formatPayment($p));
        return response()->json($payments);
    }

    public function submitPayment(Request $request, int $id)
    {
        $booking = Booking::findOrFail($id);

        $data = $request->validate([
            'payment_method'        => 'nullable|string|max:100',
            'transaction_reference' => 'nullable|string|max:255',
            'proof_file'            => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $existingFirst = $booking->payments()->where('payment_stage', 'first')->exists();
        $stage  = $existingFirst ? 'second' : 'first';
        $amount = $existingFirst
            ? $booking->total_price - ($booking->payments()->where('payment_stage', 'first')->sum('amount'))
            : round($booking->total_price * 0.5, 2);

        $proofPath = null;
        if ($request->hasFile('proof_file')) {
            $proofPath = $request->file('proof_file')->store('payments/proofs', 'public');
        }

        $payment = Payment::create([
            'booking_id'            => $booking->booking_id,
            'amount'                => $amount,
            'payment_method'        => $data['payment_method'] ?? null,
            'payment_stage'         => $stage,
            'payment_date'          => now()->toDateString(),
            'transaction_reference' => $data['transaction_reference'] ?? null,
            'proof_file'            => $proofPath,
            'verification_status'   => 'pending',
        ]);

        $statusMap = ['first' => 'deposit_paid', 'second' => 'deposit_paid'];
        $booking->update(['payment_status' => $stage === 'first' ? 'deposit_paid' : $booking->payment_status]);

        return response()->json($this->formatPayment($payment), 201);
    }

    private function formatPayment(Payment $p): array
    {
        return [
            'id'                    => $p->payment_id,
            'booking_id'            => $p->booking_id,
            'amount'                => $p->amount,
            'payment_method'        => $p->payment_method,
            'payment_stage'         => $p->payment_stage,
            'payment_date'          => $p->payment_date?->toDateString(),
            'transaction_reference' => $p->transaction_reference,
            'proof_file'            => $p->proof_file,
            'verification_status'   => $p->verification_status,
        ];
    }
}