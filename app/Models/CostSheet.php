<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CostSheet extends Model
{
    use HasFactory;

    protected $table = 'tbl_cost_sheet';
    protected $primaryKey = 'cost_sheet_id';

    protected $fillable = [
        'booking_id',
        'route',
        'size',
        'price',
        'lolo',
        'over_weight',
        'express_way',
        'extra',
        'empty_return',
        'standby_truck',
        'repair',
    ];

    protected $casts = [
        'price'         => 'float',
        'lolo'          => 'float',
        'over_weight'   => 'float',
        'express_way'   => 'float',
        'extra'         => 'float',
        'empty_return'  => 'float',
        'standby_truck' => 'float',
        'repair'        => 'float',
    ];

    protected $appends = ['total'];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'booking_id');
    }

    public function getTotalAttribute()
    {
        return $this->price + $this->lolo + $this->over_weight + $this->express_way
             + $this->extra + $this->empty_return + $this->standby_truck + $this->repair;
    }
}
