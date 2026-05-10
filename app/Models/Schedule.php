<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $table = 'tbl_schedule';
    protected $primaryKey = 'schedule_id';

    protected $fillable = [
        'truck_id',
        'driver_id',
        'location_truck',
        'date_of_truck_available',
    ];

    protected $casts = [
        'date_of_truck_available' => 'date',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id', 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id', 'driver_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'schedule_id', 'schedule_id');
    }
}
