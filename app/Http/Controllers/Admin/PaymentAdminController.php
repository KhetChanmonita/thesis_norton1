<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\BookingStatusHistory;
use Illuminate\Http\Request;

class PaymentAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with('booking.customer');

        if ($request->filled('search')) {
            $search = $request->search;

            // Support typing the formatted ID shown on screen (e.g. LS2606-22)
            // as well as the plain booking number (e.g. 22).
            $bookingNumber = $search;
            if (preg_match('/-(\d+)$/', $search, $m)) {
                $bookingNumber = $m[1];
            }

            $query->where(function ($q) use ($search, $bookingNumber) {
                $q->whereHas('booking', fn($b) => $b->where('booking_id', 'like', "%{$bookingNumber}%"))
                  ->orWhereHas('booking.customer', fn($c) =>
                        $c->where('full_name', 'like', "%{$search}%")
                  );
            });
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('date')) {
            $query->whereDate('payment_date', $request->date);
        }

        $payments       = $query->latest()->paginate(10)->withQueryString();
        $totalRevenue   = Payment::where('verification_status', 'verified')->sum('amount');
        $todayRevenue   = Payment::where('verification_status', 'verified')->whereDate('payment_date', today())->sum('amount');
        $totalCount     = Payment::where('verification_status', 'verified')->count();
        $methods        = Payment::select('payment_method')
                            ->whereNotNull('payment_method')
                            ->distinct()->pluck('payment_method');

        return view('admin.payments.index',
            compact('payments', 'totalRevenue', 'todayRevenue', 'totalCount', 'methods')
        );
    }

    public function verify(Payment $payment)
    {
        $payment->update(['verification_status' => 'verified']);

        $booking = $payment->booking;
        if ($booking) {
            if ($payment->payment_stage === 'first') {
                $booking->update(['status' => 'in_progress']);
                if ($booking->truck) {
                    $booking->truck->update(['status' => 'delivering']);
                }
            } elseif ($payment->payment_stage === 'second') {
                $booking->update(['payment_status' => 'fully_paid']);
            }
        }

        return back()->with('success', 'ការទូទាត់ត្រូវបានផ្ទៀងផ្ទាត់!');
    }

    public function reject(Payment $payment)
    {
        $payment->update(['verification_status' => 'rejected']);

        $booking = $payment->booking;
        if ($booking && $payment->payment_stage === 'first') {
            $booking->update(['payment_status' => 'unpaid']);
        }

        return back()->with('success', 'ការទូទាត់ត្រូវបានបដិសេធ។ អតិថិជននឹងត្រូវដាក់ស្នើឡើងវិញ។');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'ការបង់ប្រាក់ត្រូវបានលុប!');
    }

    public function history(Request $request)
    {
        $query = BookingStatusHistory::with('booking.customer', 'booking.extraCharges');

        if ($request->filled('container_number')) {
            $container = trim($request->container_number);
            $query->whereHas('booking', fn($q) => $q->where('container_number', 'like', "%{$container}%"));
        }

        $histories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.history.index', compact('histories'));
    }
}
