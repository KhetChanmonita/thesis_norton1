<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingRate extends Model
{
    protected $table      = 'tbl_shipping_rate';
    protected $primaryKey = 'rate_id';

    protected $fillable = [
        'type',
        'origin',
        'province_name_km',
        'province_name_en',
        'base_price',
    ];

    protected $casts = [
        'base_price' => 'float',
    ];

    // All 25 Cambodian provinces (used for seeding & dropdowns)
    public static function provinces(): array
    {
        return [
            ['km' => 'ភ្នំពេញ',           'en' => 'Phnom Penh'],
            ['km' => 'បន្ទាយមានជ័យ',     'en' => 'Banteay Meanchey'],
            ['km' => 'បាត់ដំបង',           'en' => 'Battambang'],
            ['km' => 'កំពង់ចាម',           'en' => 'Kampong Cham'],
            ['km' => 'កំពង់ឆ្នាំង',        'en' => 'Kampong Chhnang'],
            ['km' => 'កំពង់ស្ពឺ',          'en' => 'Kampong Speu'],
            ['km' => 'កំពង់ធំ',            'en' => 'Kampong Thom'],
            ['km' => 'កំពត',               'en' => 'Kampot'],
            ['km' => 'កណ្ដាល',             'en' => 'Kandal'],
            ['km' => 'កែប',               'en' => 'Kep'],
            ['km' => 'កោះកុង',             'en' => 'Koh Kong'],
            ['km' => 'ក្រចេះ',             'en' => 'Kratie'],
            ['km' => 'មណ្ឌលគីរី',          'en' => 'Mondulkiri'],
            ['km' => 'ឧត្ដរមានជ័យ',       'en' => 'Oddar Meanchey'],
            ['km' => 'ប៉ៃលិន',             'en' => 'Pailin'],
            ['km' => 'ព្រះវិហារ',          'en' => 'Preah Vihear'],
            ['km' => 'ព្រើយវែង',           'en' => 'Prey Veng'],
            ['km' => 'ពោធ៍សាត់',           'en' => 'Pursat'],
            ['km' => 'រតនគីរី',            'en' => 'Ratanakiri'],
            ['km' => 'សៀមរាប',             'en' => 'Siem Reap'],
            ['km' => 'ព្រះសីហនុ',          'en' => 'Sihanoukville'],
            ['km' => 'ស្ទឹងត្រែង',         'en' => 'Stung Treng'],
            ['km' => 'ស្វាយរៀង',           'en' => 'Svay Rieng'],
            ['km' => 'តាកែវ',             'en' => 'Takeo'],
            ['km' => 'ត្បូងឃ្មុំ',          'en' => 'Tboung Khmum'],
        ];
    }
}
