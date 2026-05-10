<?php
// app/Http/Controllers/TruckController.php
namespace App\Http\Controllers;

use App\Models\Truck;
use Illuminate\Http\Request;

class TruckController extends Controller
{
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