<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $table = 'tbl_driver';
    protected $primaryKey = 'driver_id';

    protected $fillable = [
        'full_name',
        'phone',
        'hire_date',
        'status',
        'assigned_truck',
    ];

    protected $casts = [
        'hire_date' => 'date',
    ];

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'assigned_truck', 'truck_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class, 'driver_id', 'driver_id');
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class, 'driver_id', 'driver_id');
    }
}
