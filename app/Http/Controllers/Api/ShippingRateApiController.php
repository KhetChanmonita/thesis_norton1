<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateApiController extends Controller
{
    public function index(Request $request)
    {
        $query = ShippingRate::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('origin')) {
            $query->where('origin', $request->origin);
        }

        return response()->json($query->get());
    }
}