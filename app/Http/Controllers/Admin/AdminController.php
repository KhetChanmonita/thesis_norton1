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

        // Accountant-specific stats — use the most recent month that has data
        $statMonth = \Carbon\Carbon::now();
        $hasData   = Booking::whereYear('booking_date', $statMonth->year)
                        ->whereMonth('booking_date', $statMonth->month)->exists()
                  || Payment::whereYear('payment_date', $statMonth->year)
                        ->whereMonth('payment_date', $statMonth->month)
                        ->where('verification_status', 'verified')->exists();
        if (!$hasData) {
            $lastDate = max(array_filter([
                Booking::latest('booking_date')->value('booking_date'),
                Payment::where('verification_status','verified')->latest('payment_date')->value('payment_date'),
            ]));
            if ($lastDate) $statMonth = \Carbon\Carbon::parse($lastDate);
        }
        $year  = $statMonth->year;
        $month = $statMonth->month;

        $accountantStats = [
            'revenue_month'   => Payment::whereYear('payment_date', $year)
                                    ->whereMonth('payment_date', $month)
                                    ->where('verification_status', 'verified')
                                    ->sum('amount'),
            'expense_month'   => Expense::whereYear('expense_date', $year)
                                    ->whereMonth('expense_date', $month)
                                    ->sum('amount')
                                + Expense::whereYear('expense_date', $year)
                                    ->whereMonth('expense_date', $month)
                                    ->sum('driver_allowance'),
            'payments_total'  => Payment::where('verification_status', 'verified')->count(),
            'payments_pending' => Payment::where('verification_status', 'pending')->count(),
            'revenue_year'    => Payment::whereYear('payment_date', $year)
                                    ->where('verification_status', 'verified')
                                    ->sum('amount'),
            'stat_month_label' => $statMonth->locale('km')->translatedFormat('F Y'),
        ];

        // Driver-specific stats — based on bookings for this driver's assigned truck
        $driverStats = null;
        if (auth()->user()->role === 'driver') {
            $driver = auth()->user()->driver;
            if ($driver && $driver->assigned_truck) {
                $today = now()->toDateString();

                $allBookings      = Booking::where('truck_id', $driver->assigned_truck)
                                        ->with(['customer'])
                                        ->orderBy('pick_up_date')
                                        ->get();
                $upcomingBookings = $allBookings->filter(fn($b) => $b->pick_up_date && $b->pick_up_date->toDateString() >= $today)->values();
                $pastBookings     = $allBookings->filter(fn($b) => $b->pick_up_date && $b->pick_up_date->toDateString() < $today)->values();
                $todayBookings    = $allBookings->filter(fn($b) => $b->pick_up_date && $b->pick_up_date->toDateString() === $today)->values();

                $driverExpenses = Expense::where('driver_id', $driver->driver_id)
                                        ->orderByDesc('expense_date')->get();

                $driverStats = [
                    'driver'            => $driver,
                    'total'             => $allBookings->count(),
                    'upcoming'          => $upcomingBookings->count(),
                    'past'              => $pastBookings->count(),
                    'today'             => $todayBookings->count(),
                    'nextBookings'      => $upcomingBookings->take(3),
                    'total_fuel'        => $driverExpenses->where('expense_type','fuel')->sum('amount'),
                    'total_allowance'   => $driverExpenses->sum('driver_allowance'),
                    'expense_count'     => $driverExpenses->count(),
                ];
            }
        }

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentPayments', 'recentMessages', 'accountantStats', 'driverStats'));
    }

    public function driverTrips()
    {
        $user   = auth()->user();
        $driver = $user->driver;

        $bookings = collect();
        $expenses = collect();
        if ($driver) {
            if ($driver->assigned_truck) {
                $bookings = Booking::where('truck_id', $driver->assigned_truck)
                                ->with(['customer', 'truck'])
                                ->orderByDesc('pick_up_date')
                                ->get();
            }
            $expenses = Expense::where('driver_id', $driver->driver_id)
                            ->with(['booking.customer'])
                            ->orderByDesc('expense_date')
                            ->get();
        }

        return view('admin.driver-trips', compact('driver', 'bookings', 'expenses'));
    }

    public function markArrived(Booking $booking)
    {
        $driver = auth()->user()->driver;
        // Ensure this booking belongs to the driver's truck
        if (!$driver || $booking->truck_id !== $driver->assigned_truck) {
            abort(403);
        }

        $booking->update(['driver_arrived_at' => now()]);

        return back()->with('success', 'បានជូនដំណឹងអ្នកគ្រប់គ្រងថាអ្នកបានដល់ទីតាំងហើយ!');
    }
}
