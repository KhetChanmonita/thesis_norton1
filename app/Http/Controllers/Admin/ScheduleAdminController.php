<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Truck;
use App\Models\Driver;
use Illuminate\Http\Request;

class ScheduleAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['truck', 'driver']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('location_truck', 'like', "%{$search}%")
                  ->orWhereHas('truck', fn($t) => $t->where('truck_name', 'like', "%{$search}%")
                                                      ->orWhere('plate_number', 'like', "%{$search}%"))
                  ->orWhereHas('driver', fn($d) => $d->where('full_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('date_of_truck_available', $request->date);
        }

        $schedules    = $query->latest()->paginate(10)->withQueryString();
        $trucks       = Truck::all();
        $drivers      = Driver::where('status', 'active')->get();
        $total        = Schedule::count();
        $todayCount   = Schedule::whereDate('date_of_truck_available', today())->count();
        $upcomingCount= Schedule::whereDate('date_of_truck_available', '>', today())->count();
        $pastCount    = Schedule::whereDate('date_of_truck_available', '<', today())->count();

        return view('admin.schedules.index',
            compact('schedules', 'trucks', 'drivers', 'total', 'todayCount', 'upcomingCount', 'pastCount')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'truck_id'                => 'required|exists:tbl_truck,truck_id',
            'driver_id'               => 'required|exists:tbl_driver,driver_id',
            'location_truck'          => 'nullable|string|max:200',
            'date_of_truck_available' => 'nullable|date',
        ]);

        Schedule::create($request->only('truck_id', 'driver_id', 'location_truck', 'date_of_truck_available'));

        return back()->with('success', 'កាលវិភាគបានបន្ថែមដោយជោគជ័យ!');
    }

    public function update(Request $request, Schedule $schedule)
    {
        $request->validate([
            'truck_id'                => 'required|exists:tbl_truck,truck_id',
            'driver_id'               => 'required|exists:tbl_driver,driver_id',
            'location_truck'          => 'nullable|string|max:200',
            'date_of_truck_available' => 'nullable|date',
        ]);

        $schedule->update($request->only('truck_id', 'driver_id', 'location_truck', 'date_of_truck_available'));

        return back()->with('success', 'កាលវិភាគបានកែប្រែ!');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();
        return back()->with('success', 'កាលវិភាគត្រូវបានលុប!');
    }
}
