<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingRate;

class ShippingRateSeeder extends Seeder
{
    public function run(): void
    {
        $fromShv = [
            'Phnom Penh' => 100, 'Banteay Meanchey' => 260, 'Battambang' => 230,
            'Kampong Cham' => 185, 'Kampong Chhnang' => 155, 'Kampong Speu' => 125,
            'Kampong Thom' => 170, 'Kampot' => 95, 'Kandal' => 110, 'Kep' => 105,
            'Koh Kong' => 120, 'Kratie' => 210, 'Mondulkiri' => 240,
            'Oddar Meanchey' => 290, 'Pailin' => 245, 'Preah Vihear' => 255,
            'Prey Veng' => 165, 'Pursat' => 185, 'Ratanakiri' => 270,
            'Siem Reap' => 220, 'Sihanoukville' => 30, 'Stung Treng' => 240,
            'Svay Rieng' => 175, 'Takeo' => 130, 'Tboung Khmum' => 195,
        ];

        $fromPp = [
            'Phnom Penh' => 20, 'Banteay Meanchey' => 200, 'Battambang' => 175,
            'Kampong Cham' => 90, 'Kampong Chhnang' => 80, 'Kampong Speu' => 70,
            'Kampong Thom' => 110, 'Kampot' => 95, 'Kandal' => 40, 'Kep' => 100,
            'Koh Kong' => 130, 'Kratie' => 140, 'Mondulkiri' => 185,
            'Oddar Meanchey' => 230, 'Pailin' => 195, 'Preah Vihear' => 195,
            'Prey Veng' => 75, 'Pursat' => 140, 'Ratanakiri' => 215,
            'Siem Reap' => 165, 'Sihanoukville' => 100, 'Stung Treng' => 190,
            'Svay Rieng' => 85, 'Takeo' => 75, 'Tboung Khmum' => 100,
        ];

        foreach (ShippingRate::provinces() as $p) {
            // Import: port → province
            ShippingRate::updateOrCreate(
                ['type' => 'import', 'origin' => 'sihanoukville', 'province_name_en' => $p['en']],
                ['province_name_km' => $p['km'], 'base_price' => $fromShv[$p['en']] ?? 100]
            );
            ShippingRate::updateOrCreate(
                ['type' => 'import', 'origin' => 'phnom_penh', 'province_name_en' => $p['en']],
                ['province_name_km' => $p['km'], 'base_price' => $fromPp[$p['en']] ?? 80]
            );
            // Export: province → port (admin can adjust prices independently)
            ShippingRate::updateOrCreate(
                ['type' => 'export', 'origin' => 'sihanoukville', 'province_name_en' => $p['en']],
                ['province_name_km' => $p['km'], 'base_price' => $fromShv[$p['en']] ?? 100]
            );
            ShippingRate::updateOrCreate(
                ['type' => 'export', 'origin' => 'phnom_penh', 'province_name_en' => $p['en']],
                ['province_name_km' => $p['km'], 'base_price' => $fromPp[$p['en']] ?? 80]
            );
        }
    }
}
