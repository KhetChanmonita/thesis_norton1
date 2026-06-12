<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingStatusHistory;
use Illuminate\Http\Request;

class BookingAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with('customer');

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
}
