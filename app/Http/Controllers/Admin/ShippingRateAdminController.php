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

    private function backupPath(): string
    {
        return storage_path('app/shipping_rates_backup.json');
    }

    private function saveBackup(): void
    {
        $data = ShippingRate::all(['rate_id', 'base_price'])->toArray();
        file_put_contents($this->backupPath(), json_encode($data));
    }

    public function resetDefaults()
    {
        // Save current custom prices before overwriting
        $this->saveBackup();

        \Artisan::call('db:seed', ['--class' => 'Database\\Seeders\\ShippingRateSeeder']);

        return redirect()->route('admin.shipping.index')
            ->with('warning', 'តម្លៃត្រូវបានស្ដារទៅតម្លៃដើម។ តម្លៃចាស់របស់អ្នកត្រូវបានរក្សាទុក — ចុច «ស្ដារតម្លៃខ្ញុំ» ដើម្បីយកវិញ។');
    }

    public function restoreBackup()
    {
        $path = $this->backupPath();
        if (!file_exists($path)) {
            return redirect()->route('admin.shipping.index')
                ->with('error', 'មិនមានការរក្សាទុកតម្លៃ។ សូមកែតម្លៃផ្ទាល់។');
        }

        $rows = json_decode(file_get_contents($path), true);
        foreach ($rows as $row) {
            ShippingRate::where('rate_id', $row['rate_id'])
                ->update(['base_price' => $row['base_price']]);
        }

        return redirect()->route('admin.shipping.index')
            ->with('success', 'តម្លៃដែលអ្នកបានកែប្រែត្រូវបានយកមកវិញដោយជោគជ័យ!');
    }

    public function bulkAdjust(Request $request)
    {
        $request->validate([
            'adjust_type'  => 'required|in:percent_increase,percent_decrease,fixed_add,fixed_subtract',
            'adjust_value' => 'required|numeric|min:0.01',
            'scope_type'   => 'nullable|in:import,export',
            'scope_origin' => 'nullable|in:sihanoukville,phnom_penh',
            'round_to'     => 'nullable|in:0,0.5,1,5',
        ]);

        $query = ShippingRate::query();
        if ($request->filled('scope_type'))   $query->where('type',   $request->scope_type);
        if ($request->filled('scope_origin')) $query->where('origin', $request->scope_origin);

        $rates   = $query->get();
        $value   = (float) $request->adjust_value;
        $roundTo = (float) ($request->round_to ?? 0);

        foreach ($rates as $rate) {
            $old = (float) $rate->base_price;
            $new = match ($request->adjust_type) {
                'percent_increase' => $old * (1 + $value / 100),
                'percent_decrease' => $old * (1 - $value / 100),
                'fixed_add'        => $old + $value,
                'fixed_subtract'   => max(0, $old - $value),
            };
            if ($roundTo > 0) {
                $new = round($new / $roundTo) * $roundTo;
            }
            $rate->update(['base_price' => round(max(0, $new), 2)]);
        }

        $label = [
            'percent_increase' => '+' . $value . '%',
            'percent_decrease' => '-' . $value . '%',
            'fixed_add'        => '+$' . number_format($value, 2),
            'fixed_subtract'   => '-$' . number_format($value, 2),
        ][$request->adjust_type];

        return redirect()->route('admin.shipping.index')
            ->with('success', 'បានកែតម្លៃ ' . $rates->count() . ' ខេត្ត/ច្រក (' . $label . ') ដោយជោគជ័យ!');
    }
}
