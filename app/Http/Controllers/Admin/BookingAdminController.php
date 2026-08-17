<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use App\Models\ExtraCharge;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public const CHARGE_TYPE_LABELS = [
        'extra_charge' => 'ការគិតប្រាក់បន្ថែម (Extra Charge)',
        'empty_return' => 'ត្រឡប់ទទេ (Empty Return)',
        'overweight'   => 'ទម្ងន់លើស (Over Weight)',
        'standby'      => 'ឈប់រង់ចាំ (Standby)',
    ];

    public function index(Request $request)
    {
        $query = Booking::with(['customer', 'truck', 'extraCharges']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $query->whereHas('customer', fn($q) =>
                $q->where('full_name','like','%'.$request->search.'%')
                  ->orWhere('phone','like','%'.$request->search.'%')
            );
        }

        $bookings = $query->latest()->paginate(10)->withQueryString();
        return view('admin.bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        if (in_array($booking->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'ការកក់នេះបានបញ្ចប់ ឬបានលុបចោលរួចហើយ — មិនអាចផ្លាស់ប្ដូរស្ថានភាពបានទេ!');
        }

        $request->validate([
            'status'      => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'total_price' => 'nullable|numeric|min:0',
        ]);

        $data = ['status' => $request->status];

        // Save total price when admin confirms (required for 50/50 payment split)
        if ($request->status === 'confirmed' && $request->filled('total_price')) {
            $data['total_price'] = $request->total_price;
        }

        $booking->update($data);

        // Sync the linked truck's status with the booking's lifecycle
        if ($booking->truck) {
            if ($request->status === 'confirmed') {
                $booking->truck->update(['status' => 'in_progress']);
            } elseif (in_array($request->status, ['completed', 'cancelled'])) {
                $booking->truck->update(['status' => 'available']);
            }
        }

        if ($request->status === 'completed') {
            BookingStatusHistory::create([
                'booking_id'     => $booking->booking_id,
                'total_price'    => $booking->fresh()->total_price,
                'completed_date' => now()->toDateString(),
            ]);
        }

        $msgs = [
            'pending'     => 'ការកក់ត្រូវបានកំណត់ជារង់ចាំ!',
            'confirmed'   => 'ការកក់បានអនុម័ត! អតិថិជននឹងបង់ 50% ដំបូង។',
            'in_progress' => 'ស្ថានភាពបានផ្លាស់ប្ដូរទៅ "កំពុងដឹក"!',
            'completed'   => 'ការដឹកបានបញ្ចប់! អតិថិជននឹងបង់ 50% ចុងក្រោយ។',
            'cancelled'   => 'ការកក់ត្រូវបានលុបចោល!',
        ];

        return back()->with('success', $msgs[$request->status] ?? 'ស្ថានភាពការកក់បានកែប្រែ!');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'ការកក់ត្រូវបានលុប!');
    }

    public function storeExtraCharge(Request $request, Booking $booking)
    {
        $request->validate([
            'charge_type' => 'required|in:extra_charge,empty_return,overweight,standby',
            'days'        => 'required_if:charge_type,standby|nullable|integer|min:1',
            'amount'      => 'required_unless:charge_type,standby|nullable|numeric|min:0.01',
            'note'        => 'nullable|string|max:500',
        ]);

        $amount = $request->charge_type === 'standby'
            ? $request->days * 50
            : $request->amount;

        $reason = self::CHARGE_TYPE_LABELS[$request->charge_type];
        if ($request->charge_type === 'standby') {
            $reason .= ' — ' . $request->days . ' ថ្ងៃ x $50';
        }
        if ($request->filled('note')) {
            $reason .= ' (' . $request->note . ')';
        }

        ExtraCharge::create([
            'booking_id' => $booking->booking_id,
            'amount'     => $amount,
            'reason'     => $reason,
            'date'       => now()->toDateString(),
        ]);

        // Extra charges add directly to the booking total, so the customer's
        // remaining 50% payment reflects the new cost.
        $newTotal = ($booking->total_price ?? 0) + $amount;
        $booking->update(['total_price' => $newTotal]);

        return back()->with('success', 'ការគិតប្រាក់បន្ថែមត្រូវបានបន្ថែម! តម្លៃសរុបថ្មី = $' . number_format($newTotal, 2));
    }
}
