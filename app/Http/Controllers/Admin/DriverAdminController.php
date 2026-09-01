<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Truck;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DriverAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::with('truck');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $drivers       = $query->latest()->paginate(10)->withQueryString();
        $trucks        = Truck::all();
        $total         = Driver::count();
        $totalActive   = Driver::where('status', 'active')->count();
        $totalInactive = Driver::where('status', 'inactive')->count();
        $totalLeave    = Driver::where('status', 'on_leave')->count();

        // truck_id → driver_id map for already-assigned trucks
        $takenTruckIds = Driver::whereNotNull('assigned_truck')
            ->pluck('driver_id', 'assigned_truck');

        return view('admin.drivers.index',
            compact('drivers', 'trucks', 'total', 'totalActive', 'totalInactive', 'totalLeave', 'takenTruckIds')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name'      => 'required|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'hire_date'      => 'nullable|date',
            'status'         => 'required|in:active,inactive,on_leave',
            'assigned_truck' => ['nullable', 'exists:tbl_truck,truck_id', Rule::unique('tbl_driver', 'assigned_truck')],
            'driver_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'assigned_truck.unique' => 'រថយន្តនេះត្រូវបានកំណត់ឱ្យអ្នកបើកបរផ្សេងរួចហើយ។',
        ]);

        $data = $request->only('full_name', 'phone', 'hire_date', 'status', 'assigned_truck');

        if ($request->hasFile('driver_picture')) {
            $file     = $request->file('driver_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/drivers'), $filename);
            $data['driver_picture'] = 'images/drivers/' . $filename;
        }

        Driver::create($data);

        return back()->with('success', 'អ្នកបើកបរបានបន្ថែមដោយជោគជ័យ!');
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'full_name'      => 'required|string|max:100',
            'phone'          => 'nullable|string|max:20',
            'hire_date'      => 'nullable|date',
            'status'         => 'required|in:active,inactive,on_leave',
            'assigned_truck' => ['nullable', 'exists:tbl_truck,truck_id',
                Rule::unique('tbl_driver', 'assigned_truck')->ignore($driver->driver_id, 'driver_id')],
            'driver_picture' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'assigned_truck.unique' => 'រថយន្តនេះត្រូវបានកំណត់ឱ្យអ្នកបើកបរផ្សេងរួចហើយ។',
        ]);

        $data = $request->only('full_name', 'phone', 'hire_date', 'status', 'assigned_truck');

        if ($request->hasFile('driver_picture')) {
            if ($driver->driver_picture && file_exists(public_path($driver->driver_picture))) {
                unlink(public_path($driver->driver_picture));
            }
            $file     = $request->file('driver_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/drivers'), $filename);
            $data['driver_picture'] = 'images/drivers/' . $filename;
        }

        $driver->update($data);

        return back()->with('success', 'ព័ត៌មានអ្នកបើកបរបានកែប្រែ!');
    }

    public function destroy(Driver $driver)
    {
        if ($driver->driver_picture && file_exists(public_path($driver->driver_picture))) {
            unlink(public_path($driver->driver_picture));
        }
        $driver->delete();
        return back()->with('success', 'អ្នកបើកបរត្រូវបានលុបចោល!');
    }

    public function print()
    {
        $drivers       = Driver::with('truck')->latest()->get();
        $total         = $drivers->count();
        $totalActive   = $drivers->where('status', 'active')->count();
        $totalInactive = $drivers->where('status', 'inactive')->count();
        $totalLeave    = $drivers->where('status', 'on_leave')->count();
        return view('admin.drivers.print', compact('drivers', 'total', 'totalActive', 'totalInactive', 'totalLeave'));
    }
}
