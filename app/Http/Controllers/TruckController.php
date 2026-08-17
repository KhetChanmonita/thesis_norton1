<?php
// app/Http/Controllers/TruckController.php
namespace App\Http\Controllers;

use App\Models\Truck;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class TruckController extends Controller
{
    // Customer-facing trucks page — random order, dynamic from DB
    public function customerIndex()
    {
        // Load drivers + latest schedule (for current location)
        $trucks = Truck::with(['drivers', 'schedules' => function ($q) {
            $q->latest()->limit(1);
        }])->get()
            ->filter(fn($t) => ($t->status ?? 'available') === 'available'
                               && $t->drivers->where('status', 'active')->count() > 0)
            ->shuffle()->values();

        $availableCount = $trucks->count();

        $trucksJson = $trucks->map(fn($t) => [
            'truck_name'    => $t->truck_name,
            'plate_number'  => $t->plate_number,
            'truck_size'    => $t->truck_size,
            'truck_color'   => $t->truck_color,
            'capacity_ton'  => $t->capacity_ton,
            'truck_picture' => $t->truck_picture,
            'status'        => $t->status ?? 'available',
            'can_book'      => ($t->status ?? 'available') === 'available'
                               && $t->drivers->where('status', 'active')->count() > 0,
            'block_reason'  => (function($t) {
                $st = $t->status ?? 'available';
                $hasDriver = $t->drivers->where('status', 'active')->count() > 0;
                if ($st === 'in_progress') return 'រថយន្តកំពុងដំណើរការលើការកក់មួយ';
                if ($st === 'delivering')  return 'រថយន្តកំពុងដឹកទំនិញ';
                if ($st === 'maintenance') return 'រថយន្តកំពុងជួសជុល';
                if (!$hasDriver)           return 'អ្នកបើកបរមិនអាចប្រើប្រាស់';
                return null;
            })($t),
            'location'      => $t->schedules->first()?->location_truck ?? null,
            'drivers'       => $t->drivers->map(fn($d) => [
                'full_name'      => $d->full_name,
                'phone'          => $d->phone,
                'driver_picture' => $d->driver_picture,
                'status'         => $d->status,
            ])->values(),
        ])->values();

        $provinces = ShippingRate::provinces();

        $truckTypes = Truck::select('truck_name')->distinct()->pluck('truck_name')->filter()->values();

        return view('trucks_section', compact('trucks', 'trucksJson', 'availableCount', 'provinces', 'truckTypes'));
    }

    public function getAvailableTrucks(Request $request)
    {
        $trucks = Truck::where('status', 'available')->get();
        
        return response()->json([
            'success' => true,
            'trucks' => $trucks
        ]);
    }

    public function getAllTrucks()
    {
        $trucks = Truck::all();
        
        return response()->json([
            'success' => true,
            'trucks' => $trucks
        ]);
    }
}