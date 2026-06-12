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
            $query->where(function ($q) use ($search) {
                $q->where('transaction_reference', 'like', "%{$search}%")
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
        $totalRevenue   = Payment::sum('amount');
        $todayRevenue   = Payment::whereDate('payment_date', today())->sum('amount');
        $totalCount     = Payment::count();
        $methods        = Payment::select('payment_method')
                            ->whereNotNull('payment_method')
                            ->distinct()->pluck('payment_method');

        return view('admin.payments.index',
            compact('payments', 'totalRevenue', 'todayRevenue', 'totalCount', 'methods')
        );
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return back()->with('success', 'ការបង់ប្រាក់ត្រូវបានលុប!');
    }

    public function history(Request $request)
    {
        $query = BookingStatusHistory::with('booking.customer');

        if ($request->filled('container_number')) {
            $container = trim($request->container_number);
            $query->whereHas('booking', fn($q) => $q->where('container_number', 'like', "%{$container}%"));
        }

        $histories = $query->latest()->paginate(10)->withQueryString();

        return view('admin.history.index', compact('histories'));
    }
}
