<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Contact;
use App\Models\Customer;
use App\Models\Driver;
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

        $recentBookings = Booking::with('customer')
            ->latest()
            ->take(8)
            ->get();

        $recentPayments = Payment::with('booking.customer')
            ->latest()
            ->take(5)
            ->get();

        $recentMessages = Contact::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'recentPayments', 'recentMessages'));
    }
}
