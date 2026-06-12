<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingRateAdminController extends Controller
{
    public function index()
    {
        $all = ShippingRate::orderBy('province_name_en')->get();

        // Build JS-ready map: [type_origin][province_en] = {rate_id, km, price}
        $ratesMap = [];
        foreach ($all as $r) {
            $key = $r->type . '_' . $r->origin;
            $ratesMap[$key][$r->province_name_en] = [
                'rate_id' => $r->rate_id,
                'km'      => $r->province_name_km,
                'price'   => $r->base_price,
            ];
        }

        $provinces = ShippingRate::provinces();
        $updateRoute = route('admin.shipping.update', ['rate' => '__ID__']);

        return view('admin.shipping-rates.index', compact('ratesMap', 'provinces', 'updateRoute'));
    }

    public function update(Request $request, ShippingRate $rate)
    {
        $request->validate([
            'base_price' => 'required|numeric|min:0',
        ]);
        $rate->update(['base_price' => $request->base_price]);
        return redirect()->route('admin.shipping.index')
                         ->with('success', 'តម្លៃដឹកជញ្ជូនបានកែប្រែ!');
    }
}
