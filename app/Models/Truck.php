<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Truck extends Model
{
    use HasFactory;

    protected $table = 'tbl_truck';
    protected $primaryKey = 'truck_id';

    protected $fillable = [
        'truck_name',
        'truck_size',
        'truck_color',
        'truck_picture',
        'plate_number',
        'capacity_ton',
        'truck_location',
        'status',
    ];

    public function drivers()
    {
        return $this->hasMany(Driver::class, 'assigned_truck', 'truck_id');
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'truck_id', 'truck_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'truck_id', 'truck_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'truck_id', 'truck_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'truck_id', 'truck_id');
    }
}
