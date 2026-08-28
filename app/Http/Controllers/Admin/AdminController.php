<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Payment;
use App\Models\Schedule;
use App\Models\Truck;
use App\Models\User;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'trucks'       => Truck::count(),
            'drivers'      => Driver::count(),
            'customers'    => Customer::count(),
            'bookings'     => Booking::count(),
            'pending'      => Booking::where('status', 'pending')->count(),
            'confirmed'    => Booking::where('status', 'confirmed')->count(),
            'completed'    => Booking::where('status', 'completed')->count(),
            'cancelled'    => Booking::where('status', 'cancelled')->count(),
            'payments'     => Payment::count(),
            'total_revenue'=> Payment::where('verification_status', 'verified')->sum('amount'),
            'users'        => User::count(),
            'schedules'    => Schedule::count(),
            'new_messages' => Contact::where('status', 'new')->count(),
        ];

        $recentBookings = Booking::with(['customer', 'bookedByUser'])
            ->latest()
            ->take(8)
            ->get();

        $recentPayments = Payment::with(['booking.customer', 'booking.bookedByUser'])
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = Contact::latest()->take(5)->get();

        // Accountant-specific stats
        $year  = now()->year;
        $month = now()->month;
        $accountantStats = [
            'revenue_month'  => Booking::whereYear('booking_date', $year)
                                    ->whereMonth('booking_date', $month)
                                    ->where('payment_status', '!=', 'unpaid')
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total_price'),
            'expense_month'  => Expense::whereYear('expense_date', $year)
                                    ->whereMonth('expense_date', $month)
                                    ->sum('amount')
                                + Expense::whereYear('expense_date', $year)
                                    ->whereMonth('expense_date', $month)
                                    ->sum('driver_allowance'),
            'payments_total' => Payment::where('verification_status', 'verified')->count(),
            'payments_pending'=> Payment::where('verification_status', 'pending')->count(),
            'revenue_year'   => Booking::whereYear('booking_date', $year)
                                    ->where('payment_status', '!=', 'unpaid')
                                    ->where('status', '!=', 'cancelled')
                                    ->sum('total_price'),
        ];

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentPayments', 'recentMessages', 'accountantStats'));
    }

    public function driverTrips()
    {
        $user   = auth()->user();
        $driver = $user->driver;

        $schedules = $driver
            ? Schedule::with(['truck'])
                ->where('driver_id', $driver->driver_id)
                ->orderByDesc('date_of_truck_available')
                ->get()
            : collect();

        return view('admin.driver-trips', compact('driver', 'schedules'));
    }
}
