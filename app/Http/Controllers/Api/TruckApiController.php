<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Truck;
use Illuminate\Http\Request;

class TruckApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Truck::with(['drivers', 'schedules' => function ($q) {
            $q->latest('schedule_id');
        }]);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trucks = $query->get()->map(fn($truck) => $this->formatTruck($truck));

        return response()->json($trucks);
    }

    private function formatTruck(Truck $truck): array
    {
        $driver   = $truck->drivers->first();
        $schedule = $truck->schedules->first();

        return [
            'truck_id'     => $truck->truck_id,
            'truck_name'   => $truck->truck_name,
            'truck_size'   => $truck->truck_size,
            'truck_color'  => $truck->truck_color,
            'truck_picture' => $truck->truck_picture,
            'plate_number' => $truck->plate_number,
            'capacity_ton' => $truck->capacity_ton,
            'status'       => $truck->status,
            'driver'       => $driver ? [
                'full_name'      => $driver->full_name,
                'phone'          => $driver->phone,
                'driver_picture' => $driver->driver_picture,
            ] : null,
            'schedule' => $schedule ? [
                'location_truck' => $schedule->location_truck,
            ] : null,
        ];
    }
}